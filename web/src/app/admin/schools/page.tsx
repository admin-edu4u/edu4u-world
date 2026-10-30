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
  resolveCsvIdLabels,
} from "@/components/admin/AdminUi";
import { pageRange, parsePagination } from "@/lib/admin/pagination";
import { applyStatusFilter, countStatusTabs, parseStatusTab, statusLabel } from "@/lib/admin/status";
import {
  activateItem,
  archiveItem,
  deactivateItem,
  restoreItem,
  deleteItem,
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
  let query = supabase.from("schools").select("*", { count: "exact" }).order("s_id", { ascending: false });
  query = applyStatusFilter(query, "s_status", tab);
  const [{ data: rows, count }, counts] = await Promise.all([
    query.range(from, to),
    countStatusTabs(supabase, "schools", "s_status"),
  ]);
  const total = count ?? 0;
  const qualMap = new Map<number | string, string>(
    Object.entries(Q_LEVELS).flatMap(([id, label]) => [
      [Number(id), label],
      [id, label],
    ]),
  );

  return (
    <>
      <AdminPageHeader
        title="Schools"
        actions={<AdminBtn href="/admin/schools/new">Add school</AdminBtn>}
      />
      <AdminStatusTabs basePath="/admin/schools" current={tab} keep={{ perPage }} counts={counts} />
      <AdminCard>
        <div className="card-body p-0">
          {!rows?.length ? (
            <EmptyState message="No records in this view." />
          ) : (
            <AdminTable headers={["Name", "Qualification", "Status", "Created", ""]}>
              {rows.map((r) => {
                const href = `/admin/schools/${r.s_id}`;
                const qualifications = resolveCsvIdLabels(qualMap, r.s_qualification);
                return (
                  <AdminRow key={String(r.s_id)} href={href}>
                    <td className="admin-table-primary" title={r.s_name || undefined}>
                      {r.s_name}
                    </td>
                    <td className="admin-table-tags">
                      {qualifications.length ? (
                        <span className="admin-user-tags">
                          {qualifications.map((label) => (
                            <span key={label} className="admin-user-tag">
                              {label}
                            </span>
                          ))}
                        </span>
                      ) : (
                        "-"
                      )}
                    </td>
                    <td>{statusLabel(r.s_status)}</td>
                    <td className="admin-table-date">{formatAdminDateTime(r.created_at)}</td>
                    <td className="admin-table-actions">
                      <AdminStatusActions
                        status={r.s_status}
                        editHref={href}
                        onActivate={activateItem.bind(null, Number(r.s_id))}
                        onDeactivate={deactivateItem.bind(null, Number(r.s_id))}
                        onArchive={archiveItem.bind(null, Number(r.s_id))}
                        onRestore={restoreItem.bind(null, Number(r.s_id))}
                        onDelete={deleteItem.bind(null, Number(r.s_id))}
                      />
                    </td>
                  </AdminRow>
                );
              })}
            </AdminTable>
          )}
          <AdminPagination basePath="/admin/schools" page={page} perPage={perPage} total={total} keep={keep} />
        </div>
      </AdminCard>
    </>
  );
}
