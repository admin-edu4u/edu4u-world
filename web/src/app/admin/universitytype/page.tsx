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
  EmptyState,
  formatAdminDateTime,
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

const TYPE_LABELS: Record<number, string> = {
  1: "Private",
  2: "Public",
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
  let query = supabase.from("university_type").select("*", { count: "exact" }).order("ut_id", { ascending: false });
  query = applyStatusFilter(query, "ut_status", tab);
  const [{ data: rows, count }, counts] = await Promise.all([
    query.range(from, to),
    countStatusTabs(supabase, "university_type", "ut_status"),
  ]);
  const total = count ?? 0;

  return (
    <>
      <AdminPageHeader
        title="University types"
        actions={<AdminBtn href="/admin/universitytype/new">Add type</AdminBtn>}
      />
      <AdminStatusTabs basePath="/admin/universitytype" current={tab} keep={{ perPage }} counts={counts} />
      <AdminCard>
        <div className="card-body p-0">
          {!rows?.length ? (
            <EmptyState message="No university types in this view." />
          ) : (
            <AdminTable headers={["Name", "Belongs to", "Status", "Created", ""]}>
              {rows.map((r) => {
                const href = `/admin/universitytype/${r.ut_id}`;
                return (
                  <AdminRow key={r.ut_id} href={href}>
                    <td>{r.ut_name}</td>
                    <td>{TYPE_LABELS[Number(r.ut_type)] || r.ut_type}</td>
                    <td>{statusLabel(r.ut_status)}</td>
                    <td className="admin-table-date">{formatAdminDateTime(r.created_at)}</td>
                    <td className="admin-table-actions">
                      <AdminStatusActions
                        status={r.ut_status}
                        editHref={href}
                        onActivate={activateItem.bind(null, Number(r.ut_id))}
                        onDeactivate={deactivateItem.bind(null, Number(r.ut_id))}
                        onArchive={archiveItem.bind(null, Number(r.ut_id))}
                        onRestore={restoreItem.bind(null, Number(r.ut_id))}
                        onDelete={deleteItem.bind(null, Number(r.ut_id))}
                      />
                    </td>
                  </AdminRow>
                );
              })}
            </AdminTable>
          )}
          <AdminPagination basePath="/admin/universitytype" page={page} perPage={perPage} total={total} keep={keep} />
        </div>
      </AdminCard>
    </>
  );
}
