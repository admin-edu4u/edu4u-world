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
  formatAdminMoney,
  formatStream,
} from "@/components/admin/AdminUi";
import { pageRange, parsePagination } from "@/lib/admin/pagination";
import { applyStatusFilter, countStatusTabs, parseStatusTab, statusLabel } from "@/lib/admin/status";
import {
  activateCharacter,
  archiveCharacter,
  deactivateCharacter,
  restoreCharacter,
  deleteCharacter,
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
    .from("characters")
    .select("c_id,c_name,c_aver_salary,c_stream,c_status,c_img_male,c_img_female,created_at", { count: "exact" })
    .order("c_id", { ascending: false });
  query = applyStatusFilter(query, "c_status", tab);
  const [{ data: rows, count }, counts] = await Promise.all([
    query.range(from, to),
    countStatusTabs(supabase, "characters", "c_status"),
  ]);
  const total = count ?? 0;

  return (
    <>
      <AdminPageHeader title="Characters" actions={<AdminBtn href="/admin/characters/new">Add character</AdminBtn>} />
      <AdminStatusTabs basePath="/admin/characters" current={tab} keep={{ perPage }} counts={counts} />
      <AdminCard>
        <div className="card-body p-0">
          {!rows?.length ? (
            <EmptyState message="No characters in this view." />
          ) : (
            <AdminTable headers={["Name", "Salary", "Stream", "Status", "Created", ""]}>
              {rows.map((r) => {
                const href = `/admin/characters/${r.c_id}`;
                return (
                  <AdminRow key={r.c_id} href={href}>
                    <td className="admin-table-primary">
                      <div className="admin-entity-cell">
                        <AdminThumb
                          folder="characters"
                          filename={r.c_img_male || r.c_img_female}
                          alt=""
                        />
                        <div className="admin-entity-text">
                          <span className="admin-entity-title">{r.c_name}</span>
                        </div>
                      </div>
                    </td>
                    <td>{formatAdminMoney(r.c_aver_salary)}</td>
                    <td>{formatStream(r.c_stream)}</td>
                    <td>{statusLabel(r.c_status)}</td>
                    <td className="admin-table-date">{formatAdminDateTime(r.created_at)}</td>
                    <td className="admin-table-actions">
                      <AdminStatusActions
                        status={r.c_status}
                        editHref={href}
                        onActivate={activateCharacter.bind(null, r.c_id)}
                        onDeactivate={deactivateCharacter.bind(null, r.c_id)}
                        onArchive={archiveCharacter.bind(null, r.c_id)}
                        onRestore={restoreCharacter.bind(null, r.c_id)}
                        onDelete={deleteCharacter.bind(null, r.c_id)}
                      />
                    </td>
                  </AdminRow>
                );
              })}
            </AdminTable>
          )}
          <AdminPagination basePath="/admin/characters" page={page} perPage={perPage} total={total} keep={keep} />
        </div>
      </AdminCard>
    </>
  );
}
