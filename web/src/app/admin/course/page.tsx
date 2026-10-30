import { requireAdmin } from "@/lib/admin/auth";
import { createServiceClient } from "@/lib/supabase/server";
import { COURSE_LEVELS } from "@/lib/admin/constants";
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
  formatAdminMoney,
} from "@/components/admin/AdminUi";
import { pageRange, parsePagination } from "@/lib/admin/pagination";
import { applyStatusFilter, countStatusTabs, parseStatusTab, statusLabel } from "@/lib/admin/status";
import {
  activateCourse,
  archiveCourse,
  deactivateCourse,
  restoreCourse,
  deleteCourse,
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
    .from("courses")
    .select("c_id,c_name,c_uni,c_level,c_amount,c_status,created_at", { count: "exact" })
    .order("c_id", { ascending: false });
  query = applyStatusFilter(query, "c_status", tab);
  const [{ data: rows, count }, { data: unis }, counts] = await Promise.all([
    query.range(from, to),
    supabase.from("universities").select("u_id,u_name,u_logo"),
    countStatusTabs(supabase, "courses", "c_status"),
  ]);
  const uniMap = new Map((unis || []).map((u) => [u.u_id, { name: u.u_name, logo: u.u_logo }]));
  const total = count ?? 0;

  return (
    <>
      <AdminPageHeader title="Courses" actions={<AdminBtn href="/admin/course/new">Add course</AdminBtn>} />
      <AdminStatusTabs basePath="/admin/course" current={tab} keep={{ perPage }} counts={counts} />
      <AdminCard>
        <div className="card-body p-0">
          {!rows?.length ? (
            <EmptyState message="No courses in this view." />
          ) : (
            <AdminTable headers={["Name", "Level", "Amount", "Status", "Created", ""]}>
              {rows.map((r) => {
                const href = `/admin/course/${r.c_id}`;
                const uni = uniMap.get(r.c_uni) ?? uniMap.get(Number(r.c_uni));
                return (
                  <AdminRow key={r.c_id} href={href}>
                    <td className="admin-table-primary">
                      <div className="admin-entity-cell">
                        <AdminThumb folder="university/logo" filename={uni?.logo} alt="" />
                        <div className="admin-entity-text">
                          <span className="admin-entity-title">{r.c_name}</span>
                          <span className="admin-entity-meta">{uni?.name || "-"}</span>
                        </div>
                      </div>
                    </td>
                    <td>{COURSE_LEVELS[r.c_level as number] || r.c_level}</td>
                    <td>{formatAdminMoney(r.c_amount)}</td>
                    <td>{statusLabel(r.c_status)}</td>
                    <td className="admin-table-date">{formatAdminDateTime(r.created_at)}</td>
                    <td className="admin-table-actions">
                      <AdminStatusActions
                        status={r.c_status}
                        editHref={href}
                        onActivate={activateCourse.bind(null, r.c_id)}
                        onDeactivate={deactivateCourse.bind(null, r.c_id)}
                        onArchive={archiveCourse.bind(null, r.c_id)}
                        onRestore={restoreCourse.bind(null, r.c_id)}
                        onDelete={deleteCourse.bind(null, r.c_id)}
                      />
                    </td>
                  </AdminRow>
                );
              })}
            </AdminTable>
          )}
          <AdminPagination basePath="/admin/course" page={page} perPage={perPage} total={total} keep={keep} />
        </div>
      </AdminCard>
    </>
  );
}
