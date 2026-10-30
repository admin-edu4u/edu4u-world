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
  labelFromMap,
} from "@/components/admin/AdminUi";
import { pageRange, parsePagination } from "@/lib/admin/pagination";
import { applyStatusFilter, countStatusTabs, parseStatusTab, statusLabel } from "@/lib/admin/status";
import {
  activateGrade,
  archiveGrade,
  deactivateGrade,
  restoreGrade,
  deleteGrade,
} from "./actions";

const levelMap = new Map<number | string, string>(
  Object.entries(Q_LEVELS).flatMap(([id, label]) => [
    [Number(id), label],
    [id, label],
  ]),
);

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
    .from("subject_grades")
    .select("sg_id,sg_s_id,sg_grade,sg_status,created_at", { count: "exact" })
    .order("sg_s_id", { ascending: true })
    .order("sg_id", { ascending: true });
  query = applyStatusFilter(query, "sg_status", tab);
  const [{ data: rows, count }, counts] = await Promise.all([
    query.range(from, to),
    countStatusTabs(supabase, "subject_grades", "sg_status"),
  ]);
  const total = count ?? 0;

  return (
    <>
      <AdminPageHeader
        title="Subject grades"
        actions={<AdminBtn href="/admin/subjectsgrades/new">Add grade</AdminBtn>}
      />
      <AdminStatusTabs basePath="/admin/subjectsgrades" current={tab} keep={{ perPage }} counts={counts} />
      <AdminCard>
        <div className="card-body p-0">
          {!rows?.length ? (
            <EmptyState message="No grades in this view." />
          ) : (
            <AdminTable headers={["Level", "Grade", "Status", "Created", ""]}>
              {rows.map((r) => {
                const href = `/admin/subjectsgrades/${r.sg_id}`;
                const grade = String(r.sg_grade || "").trim();
                return (
                  <AdminRow key={r.sg_id} href={href}>
                    <td>{labelFromMap(levelMap, r.sg_s_id)}</td>
                    <td>{grade || "—"}</td>
                    <td>{statusLabel(r.sg_status)}</td>
                    <td className="admin-table-date">{formatAdminDateTime(r.created_at)}</td>
                    <td className="admin-table-actions">
                      <AdminStatusActions
                        status={r.sg_status}
                        editHref={href}
                        onActivate={activateGrade.bind(null, r.sg_id)}
                        onDeactivate={deactivateGrade.bind(null, r.sg_id)}
                        onArchive={archiveGrade.bind(null, r.sg_id)}
                        onRestore={restoreGrade.bind(null, r.sg_id)}
                        onDelete={deleteGrade.bind(null, r.sg_id)}
                      />
                    </td>
                  </AdminRow>
                );
              })}
            </AdminTable>
          )}
          <AdminPagination basePath="/admin/subjectsgrades" page={page} perPage={perPage} total={total} keep={keep} />
        </div>
      </AdminCard>
    </>
  );
}
