import { requireAdmin } from "@/lib/admin/auth";
import { createServiceClient } from "@/lib/supabase/server";
import {
  AdminBtn,
  AdminCard,
  AdminPageHeader,
  AdminPagination,
  AdminRow,
  AdminStatusActions,
  AdminStatusTabs,
  AdminTable,
  AdminThumb,
  EmptyState,
  formatAdminDateTime,
  formatYesNo,
  labelFromMap,
} from "@/components/admin/AdminUi";
import { pageRange, parsePagination } from "@/lib/admin/pagination";
import { applyStatusFilter, countStatusTabs, parseStatusTab, statusLabel } from "@/lib/admin/status";
import {
  activateScholarship,
  archiveScholarship,
  deactivateScholarship,
  restoreScholarship,
  deleteScholarship,
} from "./actions";

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
  let query = supabase
    .from("scholarships")
    .select("s_id,s_title,s_uni_id,s_is_corporate,s_status,s_c_logo,created_at", { count: "exact" })
    .order("s_id", { ascending: false });
  query = applyStatusFilter(query, "s_status", tab);
  const [{ data: rows, count }, { data: unis }, counts] = await Promise.all([
    query.range(from, to),
    supabase.from("universities").select("u_id,u_name"),
    countStatusTabs(supabase, "scholarships", "s_status"),
  ]);
  const uniMap = new Map((unis || []).map((u) => [u.u_id, u.u_name]));
  const total = count ?? 0;

  return (
    <>
      <AdminPageHeader title="Scholarships" actions={<AdminBtn href="/admin/scholarship/new">Add scholarship</AdminBtn>} />
      <AdminStatusTabs basePath="/admin/scholarship" current={tab} keep={{ perPage }} counts={counts} />
      <AdminCard>
        <div className="card-body p-0">
          {!rows?.length ? (
            <EmptyState message="No scholarships in this view." />
          ) : (
            <AdminTable headers={["Title", "Uni", "Corporate", "Status", "Created", ""]}>
              {rows.map((r) => {
                const href = `/admin/scholarship/${r.s_id}`;
                return (
                  <AdminRow key={r.s_id} href={href}>
                    <td className="admin-table-primary">
                      <div className="admin-entity-cell">
                        <AdminThumb folder="scholarships" filename={r.s_c_logo} alt="" />
                        <div className="admin-entity-text">
                          <span className="admin-entity-title">{r.s_title}</span>
                        </div>
                      </div>
                    </td>
                    <td>{labelFromMap(uniMap, r.s_uni_id)}</td>
                    <td>{formatYesNo(r.s_is_corporate)}</td>
                    <td>{statusLabel(r.s_status)}</td>
                    <td className="admin-table-date">{formatAdminDateTime(r.created_at)}</td>
                    <td className="admin-table-actions">
                      <AdminStatusActions
                        status={r.s_status}
                        editHref={href}
                        onActivate={activateScholarship.bind(null, r.s_id)}
                        onDeactivate={deactivateScholarship.bind(null, r.s_id)}
                        onArchive={archiveScholarship.bind(null, r.s_id)}
                        onRestore={restoreScholarship.bind(null, r.s_id)}
                        onDelete={deleteScholarship.bind(null, r.s_id)}
                      />
                    </td>
                  </AdminRow>
                );
              })}
            </AdminTable>
          )}
          <AdminPagination basePath="/admin/scholarship" page={page} perPage={perPage} total={total} keep={keep} />
        </div>
      </AdminCard>
    </>
  );
}
