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
} from "@/components/admin/AdminUi";
import { DeleteButton } from "@/components/admin/DeleteButton";
import { pageRange, parsePagination } from "@/lib/admin/pagination";
import { applyStatusFilter, countStatusTabs, parseStatusTab, statusLabel } from "@/lib/admin/status";
import {
  activateItem,
  archiveItem,
  deactivateItem,
  movePopup,
  restoreItem,
  deleteItem,
} from "./actions";

export default async function AdminPopupPage({
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
    .from("pop_up")
    .select("*", { count: "exact" })
    .order("pu_sort", { ascending: true })
    .order("pu_id", { ascending: true });
  query = applyStatusFilter(query, "pu_status", tab);
  const [{ data: rows, count }, counts] = await Promise.all([
    query.range(from, to),
    countStatusTabs(supabase, "pop_up", "pu_status"),
  ]);
  const total = count ?? 0;
  const list = rows || [];

  return (
    <>
      <AdminPageHeader
        title="Pop up"
        description="Homepage banners shown one after another as visitors close each modal."
        actions={<AdminBtn href="/admin/popup/new">Add pop up</AdminBtn>}
      />
      <AdminStatusTabs basePath="/admin/popup" current={tab} keep={{ perPage }} counts={counts} />
      <AdminCard>
        <div className="card-body p-0">
          {!list.length ? (
            <EmptyState message="No records in this view." />
          ) : (
            <AdminTable headers={["URL", "Order", "Status", "Created", ""]}>
              {list.map((r, index) => {
                const href = `/admin/popup/${r.pu_id}`;
                return (
                  <AdminRow key={String(r.pu_id)} href={href}>
                    <td className="admin-table-primary">
                      <div className="admin-entity-cell">
                        <AdminThumb folder="home" filename={r.pu_img} alt="" />
                        <div className="admin-entity-text">
                          <span className="admin-entity-title" title={r.pu_url || ""}>
                            {r.pu_url}
                          </span>
                        </div>
                      </div>
                    </td>
                    <td className="admin-table-order">{r.pu_sort ?? index + 1}</td>
                    <td className="admin-table-status">{statusLabel(r.pu_status)}</td>
                    <td className="admin-table-date">{formatAdminDateTime(r.created_at)}</td>
                    <td className="admin-table-actions">
                      <AdminStatusActions
                        status={r.pu_status}
                        editHref={href}
                        leading={
                          tab === "active" ? (
                            <>
                              <DeleteButton
                                action={movePopup.bind(null, Number(r.pu_id), "up")}
                                label="Move up"
                                requireConfirm={false}
                                icon="up"
                              />
                              <DeleteButton
                                action={movePopup.bind(null, Number(r.pu_id), "down")}
                                label="Move down"
                                requireConfirm={false}
                                icon="down"
                              />
                            </>
                          ) : null
                        }
                        onActivate={activateItem.bind(null, Number(r.pu_id))}
                        onDeactivate={deactivateItem.bind(null, Number(r.pu_id))}
                        onArchive={archiveItem.bind(null, Number(r.pu_id))}
                        onRestore={restoreItem.bind(null, Number(r.pu_id))}
                        onDelete={deleteItem.bind(null, Number(r.pu_id))}
                      />
                    </td>
                  </AdminRow>
                );
              })}
            </AdminTable>
          )}
          <AdminPagination basePath="/admin/popup" page={page} perPage={perPage} total={total} keep={keep} />
        </div>
      </AdminCard>
    </>
  );
}
