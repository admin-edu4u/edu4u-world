import { requireAdmin } from "@/lib/admin/auth";
import { createServiceClient } from "@/lib/supabase/server";
import {
  AdminCard,
  AdminPageHeader,
  AdminPagination,
  AdminRow,
  AdminStatusActions,
  AdminStatusTabs,
  AdminTable,
  EmptyState,
  formatAdminDateTime,
  formatAdminMoneyRange,
} from "@/components/admin/AdminUi";
import { CareersAdminControls } from "@/components/admin/CareersAdminControls";
import { pageRange, parsePagination } from "@/lib/admin/pagination";
import { applyStatusFilter, countStatusTabs, parseStatusTab, statusLabel } from "@/lib/admin/status";
import { i18nFieldValue } from "@/lib/i18n/cms";
import { getSiteSettingsAdmin } from "@/lib/siteSettings";
import {
  activateJob,
  archiveJob,
  deactivateJob,
  restoreJob,
  deleteJob,
} from "./actions";

const TYPE_LABELS: Record<number, string> = {
  1: "Full-time",
  2: "Part-time",
};

export default async function Page({
  searchParams,
}: {
  searchParams: Promise<{ status?: string; page?: string; perPage?: string }>;
}) {
  await requireAdmin();
  const sp = await searchParams;
  const tab = parseStatusTab(sp.status);
  const { page, perPage } = parsePagination(sp);
  const { from, to } = pageRange(page, perPage);
  const keep = { status: tab === "active" ? undefined : tab };
  const supabase = createServiceClient();
  let query = supabase.from("careers").select("*", { count: "exact" }).order("c_id", { ascending: false });
  query = applyStatusFilter(query, "c_status", tab);
  const [{ data: rows, count }, { data: cats }, counts, settings] = await Promise.all([
    query.range(from, to),
    supabase
      .from("career_categories")
      .select("cc_id,cc_title,cc_status,i18n")
      .order("cc_title"),
    countStatusTabs(supabase, "careers", "c_status"),
    getSiteSettingsAdmin(),
  ]);
  const catMap = new Map((cats || []).map((c) => [c.cc_id, c.cc_title]));
  const total = count ?? 0;
  const categories = (cats || []).map((c) => ({
    cc_id: c.cc_id,
    cc_title: c.cc_title || "",
    cc_status: c.cc_status,
    titleCh: i18nFieldValue(c, "cc_title", "ch"),
    titleBm: i18nFieldValue(c, "cc_title", "bm"),
  }));

  return (
    <>
      <AdminPageHeader
        title="Careers"
        description={
          settings.careersHidden
            ? "Public careers page is currently hidden."
            : undefined
        }
        actions={
          <CareersAdminControls
            categories={categories}
            careersHidden={settings.careersHidden}
          />
        }
      />
      <AdminStatusTabs basePath="/admin/job" current={tab} keep={{ perPage }} counts={counts} />
      <AdminCard>
        <div className="card-body p-0">
          {!rows?.length ? (
            <EmptyState message="No careers in this view." />
          ) : (
            <AdminTable headers={["Title", "Location", "Type", "Salary", "Category", "Status", "Created", ""]}>
              {rows.map((r) => {
                const href = `/admin/job/${r.c_id}`;
                return (
                  <AdminRow key={r.c_id} href={href}>
                    <td>{r.c_title}</td>
                    <td>{r.c_state}</td>
                    <td>{TYPE_LABELS[r.c_type as number] || r.c_type}</td>
                    <td>{formatAdminMoneyRange(r.c_salary_min, r.c_salary_max)}</td>
                    <td>{catMap.get(r.c_category) || r.c_category}</td>
                    <td>{statusLabel(r.c_status)}</td>
                    <td className="admin-table-date">{formatAdminDateTime(r.created_at)}</td>
                    <td className="admin-table-actions">
                      <AdminStatusActions
                        status={r.c_status}
                        editHref={href}
                        onActivate={activateJob.bind(null, r.c_id)}
                        onDeactivate={deactivateJob.bind(null, r.c_id)}
                        onArchive={archiveJob.bind(null, r.c_id)}
                        onRestore={restoreJob.bind(null, r.c_id)}
                        onDelete={deleteJob.bind(null, r.c_id)}
                      />
                    </td>
                  </AdminRow>
                );
              })}
            </AdminTable>
          )}
          <AdminPagination
            basePath="/admin/job"
            page={page}
            perPage={perPage}
            total={total}
            keep={keep}
          />
        </div>
      </AdminCard>
    </>
  );
}
