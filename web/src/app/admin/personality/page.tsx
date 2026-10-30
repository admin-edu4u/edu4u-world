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
  formatPersonalityType,
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

function CharacterLabels({ names }: { names: string[] }) {
  if (!names.length) return <>-</>;
  const visible = names.slice(0, 5);
  const extra = names.length - visible.length;
  return (
    <span className="admin-user-tags" title={names.join(", ")}>
      {visible.map((name) => (
        <span key={name} className="admin-user-tag">
          {name}
        </span>
      ))}
      {extra > 0 ? <span className="admin-user-tag">+{extra}</span> : null}
    </span>
  );
}

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
  let query = supabase.from("personalities").select("*", { count: "exact" }).order("p_id", { ascending: false });
  query = applyStatusFilter(query, "p_status", tab);
  const [{ data: rows, count }, { data: chars }, counts] = await Promise.all([
    query.range(from, to),
    supabase.from("characters").select("c_id,c_name"),
    countStatusTabs(supabase, "personalities", "p_status"),
  ]);
  const charMap = new Map<number | string, string>();
  for (const c of chars || []) {
    charMap.set(c.c_id, c.c_name);
    charMap.set(String(c.c_id), c.c_name);
    charMap.set(Number(c.c_id), c.c_name);
  }
  const total = count ?? 0;

  return (
    <>
      <AdminPageHeader
        title="Personality"
        actions={<AdminBtn href="/admin/personality/new">Add personality</AdminBtn>}
      />
      <AdminStatusTabs basePath="/admin/personality" current={tab} keep={{ perPage }} counts={counts} />
      <AdminCard>
        <div className="card-body p-0">
          {!rows?.length ? (
            <EmptyState message="No records in this view." />
          ) : (
            <AdminTable headers={["Name", "Character", "Type", "Status", "Created", ""]}>
              {rows.map((r) => {
                const href = `/admin/personality/${r.p_id}`;
                const characterNames = resolveCsvIdLabels(charMap, r.p_character);
                return (
                  <AdminRow key={String(r.p_id)} href={href}>
                    <td>{r.p_name}</td>
                    <td>
                      <CharacterLabels names={characterNames} />
                    </td>
                    <td>{formatPersonalityType(r.p_type)}</td>
                    <td>{statusLabel(r.p_status)}</td>
                    <td className="admin-table-date">{formatAdminDateTime(r.created_at)}</td>
                    <td className="admin-table-actions">
                      <AdminStatusActions
                        status={Number(r.p_status)}
                        editHref={href}
                        onActivate={activateItem.bind(null, Number(r.p_id))}
                        onDeactivate={deactivateItem.bind(null, Number(r.p_id))}
                        onArchive={archiveItem.bind(null, Number(r.p_id))}
                        onRestore={restoreItem.bind(null, Number(r.p_id))}
                        onDelete={deleteItem.bind(null, Number(r.p_id))}
                      />
                    </td>
                  </AdminRow>
                );
              })}
            </AdminTable>
          )}
          <AdminPagination basePath="/admin/personality" page={page} perPage={perPage} total={total} keep={keep} />
        </div>
      </AdminCard>
    </>
  );
}
