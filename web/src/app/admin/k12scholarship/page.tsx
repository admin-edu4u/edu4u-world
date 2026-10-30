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
  activateK12Scholarship,
  archiveK12Scholarship,
  deactivateK12Scholarship,
  restoreK12Scholarship,
  deleteK12Scholarship,
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
    .from("int_scholarship")
    .select("s_id,s_title,s_int_id,s_status,s_c_logo,created_at", { count: "exact" })
    .order("s_id", { ascending: false });
  query = applyStatusFilter(query, "s_status", tab);
  const [{ data: rows, count }, { data: schools }, counts] = await Promise.all([
    query.range(from, to),
    supabase.from("international_school").select("int_id,int_name"),
    countStatusTabs(supabase, "int_scholarship", "s_status"),
  ]);
  const schoolMap = new Map((schools || []).map((s) => [s.int_id, s.int_name]));
  const total = count ?? 0;

  return (
    <>
      <AdminPageHeader title="K-12 scholarships" actions={<AdminBtn href="/admin/k12scholarship/new">Add scholarship</AdminBtn>} />
      <AdminStatusTabs basePath="/admin/k12scholarship" current={tab} keep={{ perPage }} counts={counts} />
      <AdminCard>
        <div className="card-body p-0">
          {!rows?.length ? (
            <EmptyState message="No scholarships in this view." />
          ) : (
            <AdminTable headers={["Title", "School", "Status", "Created", ""]}>
              {rows.map((r) => {
                const href = `/admin/k12scholarship/${r.s_id}`;
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
                    <td>{labelFromMap(schoolMap, r.s_int_id)}</td>
                    <td>{statusLabel(r.s_status)}</td>
                    <td className="admin-table-date">{formatAdminDateTime(r.created_at)}</td>
                    <td className="admin-table-actions">
                      <AdminStatusActions
                        status={r.s_status}
                        editHref={href}
                        onActivate={activateK12Scholarship.bind(null, r.s_id)}
                        onDeactivate={deactivateK12Scholarship.bind(null, r.s_id)}
                        onArchive={archiveK12Scholarship.bind(null, r.s_id)}
                        onRestore={restoreK12Scholarship.bind(null, r.s_id)}
                        onDelete={deleteK12Scholarship.bind(null, r.s_id)}
                      />
                    </td>
                  </AdminRow>
                );
              })}
            </AdminTable>
          )}
          <AdminPagination basePath="/admin/k12scholarship" page={page} perPage={perPage} total={total} keep={keep} />
        </div>
      </AdminCard>
    </>
  );
}