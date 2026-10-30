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
import { deleteCounsellor } from "./actions";

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
    .from("consult_counsellor")
    .select(
      "cc_id,cc_name,cc_email,cc_contact_no,cc_course,cc_school_name,cc_status,created_at",
      { count: "exact" },
    )
    .order("cc_id", { ascending: false })
    .range(from, to);
  const total = count ?? 0;
  return (
    <>
      <AdminPageHeader
        title="Counsellor requests"
        actions={
          <AdminBtn href="/api/admin/export/counsellor" variant="secondary">
            Export
          </AdminBtn>
        }
      />
      <AdminCard>
        <div className="card-body p-0">
          {!rows?.length ? (
            <EmptyState message="No requests." />
          ) : (
            <AdminTable headers={["Name", "Contact", "Course", "School", "Status", "Created", ""]}>
              {rows.map((r) => {
                const href = `/admin/counsellor/${r.cc_id}`;
                const email = String(r.cc_email || "").trim();
                return (
                  <AdminRow key={r.cc_id} href={href}>
                    <td className="admin-table-person">
                      <div className="admin-entity-text">
                        <span className="admin-entity-title">{r.cc_name || "—"}</span>
                        {email ? <span className="admin-entity-meta">{email}</span> : null}
                      </div>
                    </td>
                    <td className="admin-table-contact">{formatAdminPhone(r.cc_contact_no)}</td>
                    <td>{r.cc_course || "—"}</td>
                    <td className="admin-table-fill">{r.cc_school_name || "—"}</td>
                    <td className="admin-table-status">{statusLabel(r.cc_status)}</td>
                    <td className="admin-table-date">{formatAdminDateTime(r.created_at)}</td>
                    <td className="admin-table-actions">
                      <div className="admin-actions">
                        <AdminIconLink href={href} label="View" icon="view" />
                        <DeleteButton
                          action={deleteCounsellor.bind(null, r.cc_id)}
                          label="Delete"
                          icon="delete"
                          confirmText="Permanently delete this request? This cannot be undone."
                        />
                      </div>
                    </td>
                  </AdminRow>
                );
              })}
            </AdminTable>
          )}
          <AdminPagination basePath="/admin/counsellor" page={page} perPage={perPage} total={total} keep={{}} />
        </div>
      </AdminCard>
    </>
  );
}
