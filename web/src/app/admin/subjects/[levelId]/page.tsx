import { requireAdmin } from "@/lib/admin/auth";
import { createServiceClient } from "@/lib/supabase/server";
import { Q_LEVELS } from "@/lib/admin/constants";
import {
  AdminBtn,
  AdminCard,
  AdminPageHeader,
  AdminPagination,
  AdminRow,
  AdminStatusActions,
  AdminStatusTabs,
  AdminTable,
  EmptyState,
  formatAdminDateTime,
  formatYesNo,
} from "@/components/admin/AdminUi";
import { pageRange, parsePagination } from "@/lib/admin/pagination";
import { applyStatusFilter, countStatusTabs, parseStatusTab, statusLabel, STATUS } from "@/lib/admin/status";
import {
  activateSubject,
  archiveSubject,
  deactivateSubject,
  deleteSubject,
  restoreSubject,
} from "../actions";

export default async function Page({
  params,
  searchParams,
}: {
  params: Promise<{ levelId: string }>;
  searchParams: Promise<{ status?: string; page?: string; perPage?: string }>;
}) {
  await requireAdmin();
  const { levelId } = await params;
  const sp = await searchParams;
  const tab = parseStatusTab(sp.status);
  const { page, perPage } = parsePagination(sp);
  const { from, to } = pageRange(page, perPage);
  const keep = { status: tab === "active" ? undefined : tab };
  const lid = Number(levelId);
  const basePath = `/admin/subjects/${lid}`;
  const supabase = createServiceClient();
  let query = supabase.from("subjects").select("*", { count: "exact" }).eq("s_level_id", lid).order("s_id");
  query = applyStatusFilter(query, "s_status", tab);
  const [{ data: rows, count }, { data: cats }, counts] = await Promise.all([
    query.range(from, to),
    supabase.from("subject_categories").select("sc_id,sc_name").eq("sc_status", STATUS.ACTIVE),
    countStatusTabs(supabase, "subjects", "s_status", (q) => q.eq("s_level_id", lid)),
  ]);
  const catMap = new Map((cats || []).map((c) => [c.sc_id, c.sc_name]));
  const total = count ?? 0;

  return (
    <>
      <AdminPageHeader
        title={`Subjects: ${Q_LEVELS[lid] || levelId}`}
        actions={
          <>
            <AdminBtn href="/admin/subjects" variant="secondary">
              Back
            </AdminBtn>
            <AdminBtn href={`/admin/subjects/${lid}/new`}>Add subject</AdminBtn>
          </>
        }
      />
      <AdminStatusTabs basePath={basePath} current={tab} keep={{ perPage }} counts={counts} />
      <AdminCard>
        <div className="card-body p-0">
          {!rows?.length ? (
            <EmptyState message="No subjects in this view." />
          ) : (
            <AdminTable headers={["Name", "Category", "Preselect", "Status", "Created", ""]}>
              {rows.map((r) => {
                const href = `/admin/subjects/${lid}/${r.s_id}`;
                return (
                  <AdminRow key={r.s_id} href={href}>
                    <td>{r.s_name}</td>
                    <td>{catMap.get(r.s_subject_cat_id) || r.s_subject_cat_id}</td>
                    <td>{formatYesNo(r.s_is_preselect)}</td>
                    <td>{statusLabel(r.s_status)}</td>
                    <td className="admin-table-date">{formatAdminDateTime(r.created_at)}</td>
                    <td className="admin-table-actions">
                      <AdminStatusActions
                        status={r.s_status}
                        editHref={href}
                        onActivate={activateSubject.bind(null, r.s_id, lid)}
                        onDeactivate={deactivateSubject.bind(null, r.s_id, lid)}
                        onArchive={archiveSubject.bind(null, r.s_id, lid)}
                        onRestore={restoreSubject.bind(null, r.s_id, lid)}
                        onDelete={deleteSubject.bind(null, r.s_id, lid)}
                      />
                    </td>
                  </AdminRow>
                );
              })}
            </AdminTable>
          )}
          <AdminPagination basePath={basePath} page={page} perPage={perPage} total={total} keep={keep} />
        </div>
      </AdminCard>
    </>
  );
}
