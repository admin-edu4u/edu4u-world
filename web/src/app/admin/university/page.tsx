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
  labelFromMap,
} from "@/components/admin/AdminUi";
import { pageRange, parsePagination } from "@/lib/admin/pagination";
import { applyStatusFilter, countStatusTabs, parseStatusTab, statusLabel } from "@/lib/admin/status";
import {
  activateUniversity,
  archiveUniversity,
  deactivateUniversity,
  restoreUniversity,
  deleteUniversity,
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
    .from("universities")
    .select("u_id,u_name,u_type,u_status,u_country,u_state,u_logo,created_at", { count: "exact" })
    .order("u_id", { ascending: false });
  query = applyStatusFilter(query, "u_status", tab);
  const [{ data: rows, count }, { data: types }, counts] = await Promise.all([
    query.range(from, to),
    supabase.from("university_type").select("ut_id,ut_name"),
    countStatusTabs(supabase, "universities", "u_status"),
  ]);
  const typeMap = new Map((types || []).map((t) => [t.ut_id, t.ut_name]));
  const total = count ?? 0;

  return (
    <>
      <AdminPageHeader title="Universities" actions={<AdminBtn href="/admin/university/new">Add university</AdminBtn>} />
      <AdminStatusTabs basePath="/admin/university" current={tab} keep={{ perPage }} counts={counts} />
      <AdminCard>
        <div className="card-body p-0">
          {!rows?.length ? (
            <EmptyState message="No universities in this view." />
          ) : (
            <AdminTable headers={["Name", "Type", "Status", "Created", ""]}>
              {rows.map((r) => {
                const href = `/admin/university/${r.u_id}`;
                return (
                  <AdminRow key={r.u_id} href={href}>
                    <td className="admin-table-primary">
                      <div className="admin-entity-cell">
                        <AdminThumb folder="university/logo" filename={r.u_logo} alt="" />
                        <div className="admin-entity-text">
                          <span className="admin-entity-title">{r.u_name}</span>
                        </div>
                      </div>
                    </td>
                    <td>{labelFromMap(typeMap, r.u_type)}</td>
                    <td>{statusLabel(r.u_status)}</td>
                    <td className="admin-table-date">{formatAdminDateTime(r.created_at)}</td>
                    <td className="admin-table-actions">
                      <AdminStatusActions
                        status={r.u_status}
                        editHref={href}
                        onActivate={activateUniversity.bind(null, r.u_id)}
                        onDeactivate={deactivateUniversity.bind(null, r.u_id)}
                        onArchive={archiveUniversity.bind(null, r.u_id)}
                        onRestore={restoreUniversity.bind(null, r.u_id)}
                        onDelete={deleteUniversity.bind(null, r.u_id)}
                      />
                    </td>
                  </AdminRow>
                );
              })}
            </AdminTable>
          )}
          <AdminPagination basePath="/admin/university" page={page} perPage={perPage} total={total} keep={keep} />
        </div>
      </AdminCard>
    </>
  );
}
