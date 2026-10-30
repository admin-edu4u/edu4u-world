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
import { deleteApplication } from "./actions";

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
    .from("course_application")
    .select("ca_id,ca_name,ca_email,ca_phone,ca_programme_name,ca_status,created_at", { count: "exact" })
    .order("ca_id", { ascending: false })
    .range(from, to);
  const total = count ?? 0;
  return (
    <>
      <AdminPageHeader
        title="Course applications"
        actions={
          <AdminBtn href="/api/admin/export/courseapplication" variant="secondary">
            Export
          </AdminBtn>
        }
      />
      <AdminCard>
        <div className="card-body p-0">
          {!rows?.length ? (
            <EmptyState message="No applications." />
          ) : (
            <AdminTable headers={["Name", "Phone", "Programme", "Status", "Created", ""]}>
              {rows.map((r) => {
                const href = `/admin/courseapplication/${r.ca_id}`;
                const email = String(r.ca_email || "").trim();
                return (
                  <AdminRow key={r.ca_id} href={href}>
                    <td className="admin-table-person">
                      <div className="admin-entity-text">
                        <span className="admin-entity-title">{r.ca_name || "—"}</span>
                        {email ? <span className="admin-entity-meta">{email}</span> : null}
                      </div>
                    </td>
                    <td className="admin-table-contact">{formatAdminPhone(r.ca_phone)}</td>
                    <td className="admin-table-fill">{r.ca_programme_name || "—"}</td>
                    <td className="admin-table-status">{statusLabel(r.ca_status)}</td>
                    <td className="admin-table-date">{formatAdminDateTime(r.created_at)}</td>
                    <td className="admin-table-actions">
                      <div className="admin-actions">
                        <AdminIconLink href={href} label="View" icon="view" />
                        <DeleteButton
                          action={deleteApplication.bind(null, r.ca_id)}
                          label="Delete"
                          icon="delete"
                          confirmText="Permanently delete this application? This cannot be undone."
                        />
                      </div>
                    </td>
                  </AdminRow>
                );
              })}
            </AdminTable>
          )}
          <AdminPagination
            basePath="/admin/courseapplication"
            page={page}
            perPage={perPage}
            total={total}
            keep={{}}
          />
        </div>
      </AdminCard>
    </>
  );
}
