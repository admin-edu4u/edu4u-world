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
  EmptyState,
  formatAdminDateTime,
  formatAdminPhone,
} from "@/components/admin/AdminUi";
import { DeleteButton } from "@/components/admin/DeleteButton";
import { pageRange, parsePagination } from "@/lib/admin/pagination";
import { statusLabel } from "@/lib/admin/status";
import { deleteEnquiry } from "./actions";

export default async function Page({
  searchParams,
}: {
  searchParams: Promise<{ page?: string; perPage?: string }>;
}) {
  await requireAdmin();
  const sp = await searchParams;
  const { page, perPage } = parsePagination(sp);
  const { from, to } = pageRange(page, perPage);
  const supabase = createServiceClient();
  const { data: rows, count } = await supabase
    .from("enquiries")
    .select("e_id,e_name,e_email,e_phone_num,e_message,e_status,created_at", { count: "exact" })
    .order("e_id", { ascending: false })
    .range(from, to);
  const total = count ?? 0;
  return (
    <>
      <AdminPageHeader
        title="Enquiries"
        actions={
          <AdminBtn href="/api/admin/export/enquiries" variant="secondary">
            Export
          </AdminBtn>
        }
      />
      <AdminCard>
        <div className="card-body p-0">
          {!rows?.length ? (
            <EmptyState message="No enquiries." />
          ) : (
            <AdminTable headers={["Name", "Phone", "Message", "Status", "Created", ""]}>
              {rows.map((r) => {
                const href = `/admin/enquiries/${r.e_id}`;
                const email = String(r.e_email || "").trim();
                return (
                  <AdminRow key={r.e_id} href={href}>
                    <td className="admin-table-person">
                      <div className="admin-entity-text">
                        <span className="admin-entity-title">{r.e_name || "—"}</span>
                        {email ? <span className="admin-entity-meta">{email}</span> : null}
                      </div>
                    </td>
                    <td className="admin-table-contact">{formatAdminPhone(r.e_phone_num)}</td>
                    <td className="admin-table-fill">{r.e_message || "—"}</td>
                    <td className="admin-table-status">{statusLabel(r.e_status)}</td>
                    <td className="admin-table-date">{formatAdminDateTime(r.created_at)}</td>
                    <td className="admin-table-actions">
                      <div className="admin-actions">
                        <AdminIconLink href={href} label="View" icon="view" />
                        <DeleteButton
                          action={deleteEnquiry.bind(null, r.e_id)}
                          label="Delete"
                          icon="delete"
                          confirmText="Permanently delete this enquiry? This cannot be undone."
                        />
                      </div>
                    </td>
                  </AdminRow>
                );
              })}
            </AdminTable>
          )}
          <AdminPagination basePath="/admin/enquiries" page={page} perPage={perPage} total={total} keep={{}} />
        </div>
      </AdminCard>
    </>
  );
}
