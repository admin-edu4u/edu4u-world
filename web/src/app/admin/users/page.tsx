import { requireAdmin } from "@/lib/admin/auth";
import { createServiceClient } from "@/lib/supabase/server";
import {
  AdminBtn,
  AdminCard,
  AdminIconLink,
  AdminPageHeader,
  AdminPagination,
  AdminRow,
  AdminTable,
  AdminThumb,
  EmptyState,
  formatAdminDateTime,
} from "@/components/admin/AdminUi";
import { DeleteButton } from "@/components/admin/DeleteButton";
import { pageRange, parsePagination, buildListHref } from "@/lib/admin/pagination";
import { parseUserSearchQuery, userSearchOrFilter } from "@/lib/admin/userSearch";
import { deleteUser } from "./actions";
import { Suspense } from "react";
import { AdminListSearch } from "@/components/admin/AdminListSearch";

function formatContact(code: string | null | undefined, no: string | null | undefined): string {
  if (!no) return "-";
  return code ? `+${code}${no}` : no;
}

export default async function Page({
  searchParams,
}: {
  searchParams: Promise<{ page?: string; perPage?: string; q?: string }>;
}) {
  await requireAdmin();
  const sp = await searchParams;
  const { page, perPage } = parsePagination(sp);
  const q = parseUserSearchQuery(sp.q);
  const { from, to } = pageRange(page, perPage);
  const supabase = createServiceClient();
  let query = supabase
    .from("app_users")
    .select(
      "id,mlm_id,username,email,fullname,first_name,last_name,contact_code,contact_no,profile_pic,isactive,isadmin,created_at",
      { count: "exact" },
    )
    .eq("isadmin", 0);
  const searchFilter = userSearchOrFilter(q);
  if (searchFilter) query = query.or(searchFilter);
  const { data: rows, count } = await query
    .order("created_at", { ascending: false, nullsFirst: false })
    .order("id", { ascending: false })
    .range(from, to);
  const total = count ?? 0;
  const listKeep = { q: q || undefined };
  const exportHref = buildListHref("/api/admin/export/users", listKeep);
  return (
    <>
      <AdminPageHeader
        title="Users"
        actions={
          <div className="admin-actions admin-page-header-tools">
            <Suspense fallback={null}>
              <AdminListSearch
                basePath="/admin/users"
                placeholder="Search name, email, phone, member id…"
                keep={{ perPage: perPage !== 25 ? perPage : undefined }}
              />
            </Suspense>
            <AdminBtn href={exportHref} variant="secondary">
              Export Excel
            </AdminBtn>
          </div>
        }
      />
      <AdminCard>
        <div className="card-body p-0">
          {!rows?.length ? (
            <EmptyState message={q ? "No users match your search." : "No users."} />
          ) : (
            <AdminTable headers={["User", "Contact", "Status", "Created", ""]}>
              {rows.map((r) => {
                const href = `/admin/users/${r.id}`;
                const name =
                  r.fullname || [r.first_name, r.last_name].filter(Boolean).join(" ") || r.username || "-";
                const email = String(r.email || "").trim();
                return (
                  <AdminRow key={r.id} href={href}>
                    <td className="admin-table-person">
                      <div className="admin-entity-cell">
                        <AdminThumb folder="profile" filename={r.profile_pic} alt="" />
                        <div className="admin-entity-text">
                          <span className="admin-entity-title">{name}</span>
                          {email ? <span className="admin-entity-meta">{email}</span> : null}
                        </div>
                      </div>
                    </td>
                    <td className="admin-table-contact">{formatContact(r.contact_code, r.contact_no)}</td>
                    <td>
                      <span className="admin-user-status">
                        <span className={`admin-user-pill ${r.isactive ? "is-on" : "is-off"}`}>
                          {r.isactive ? "Active" : "Inactive"}
                        </span>
                        {r.isadmin ? <span className="admin-user-pill is-admin">Admin</span> : null}
                      </span>
                    </td>
                    <td className="admin-table-date">{formatAdminDateTime(r.created_at)}</td>
                    <td className="admin-table-actions">
                      <div className="admin-actions">
                        <AdminIconLink href={href} label="View" icon="view" />
                        {!r.isadmin ? (
                          <DeleteButton
                            action={deleteUser.bind(null, r.id)}
                            label="Delete user"
                            icon="delete"
                            confirmText="Permanently delete this user and all profile data? This cannot be undone."
                          />
                        ) : null}
                      </div>
                    </td>
                  </AdminRow>
                );
              })}
            </AdminTable>
          )}
          <AdminPagination
            basePath="/admin/users"
            page={page}
            perPage={perPage}
            total={total}
            keep={listKeep}
          />
        </div>
      </AdminCard>
    </>
  );
}
