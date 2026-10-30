import { requireAdmin } from "@/lib/admin/auth";
import { createServiceClient } from "@/lib/supabase/server";
import { K12_LEVELS } from "@/lib/admin/constants";
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
  resolveCsvIdLabels,
} from "@/components/admin/AdminUi";
import { pageRange, parsePagination } from "@/lib/admin/pagination";
import { applyStatusFilter, countStatusTabs, parseStatusTab, statusLabel } from "@/lib/admin/status";
import {
  activateK12,
  archiveK12,
  deactivateK12,
  restoreK12,
  deleteK12,
} from "./actions";

const levelMap = new Map<number | string, string>(
  Object.entries(K12_LEVELS).flatMap(([id, label]) => [
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
    .from("international_school")
    .select("int_id,int_name,int_level,int_status,int_logo,created_at", { count: "exact" })
    .order("int_id", { ascending: false });
  query = applyStatusFilter(query, "int_status", tab);
  const [{ data: rows, count }, counts] = await Promise.all([
    query.range(from, to),
    countStatusTabs(supabase, "international_school", "int_status"),
  ]);
  const total = count ?? 0;

  return (
    <>
      <AdminPageHeader title="K-12 study" actions={<AdminBtn href="/admin/k-12/new">Add school</AdminBtn>} />
      <AdminStatusTabs basePath="/admin/k-12" current={tab} keep={{ perPage }} counts={counts} />
      <AdminCard>
        <div className="card-body p-0">
          {!rows?.length ? (
            <EmptyState message="No schools in this view." />
          ) : (
            <AdminTable headers={["Name", "Level", "Status", "Created", ""]}>
              {rows.map((r) => {
                const href = `/admin/k-12/${r.int_id}`;
                const levels = resolveCsvIdLabels(levelMap, r.int_level);
                return (
                  <AdminRow key={r.int_id} href={href}>
                    <td className="admin-table-primary">
                      <div className="admin-entity-cell">
                        <AdminThumb folder="internationalschool/logo" filename={r.int_logo} alt="" />
                        <div className="admin-entity-text">
                          <span className="admin-entity-title">{r.int_name}</span>
                        </div>
                      </div>
                    </td>
                    <td className="admin-table-tags">
                      {levels.length ? (
                        <span className="admin-user-tags">
                          {levels.map((label) => (
                            <span key={label} className="admin-user-tag">
                              {label}
                            </span>
                          ))}
                        </span>
                      ) : (
                        "-"
                      )}
                    </td>
                    <td>{statusLabel(r.int_status)}</td>
                    <td className="admin-table-date">{formatAdminDateTime(r.created_at)}</td>
                    <td className="admin-table-actions">
                      <AdminStatusActions
                        status={r.int_status}
                        editHref={href}
                        onActivate={activateK12.bind(null, r.int_id)}
                        onDeactivate={deactivateK12.bind(null, r.int_id)}
                        onArchive={archiveK12.bind(null, r.int_id)}
                        onRestore={restoreK12.bind(null, r.int_id)}
                        onDelete={deleteK12.bind(null, r.int_id)}
                      />
                    </td>
                  </AdminRow>
                );
              })}
            </AdminTable>
          )}
          <AdminPagination basePath="/admin/k-12" page={page} perPage={perPage} total={total} keep={keep} />
        </div>
      </AdminCard>
    </>
  );
}
