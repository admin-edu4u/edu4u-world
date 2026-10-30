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
  formatVisitDate,
  formatVisitTime,
  labelsFromCsvIds,
} from "@/components/admin/AdminUi";
import { DeleteButton } from "@/components/admin/DeleteButton";
import { pageRange, parsePagination } from "@/lib/admin/pagination";
import { deleteVisit } from "./actions";

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
  const [{ data: rows, count }, { data: unis }] = await Promise.all([
    supabase
      .from("visit_campus")
      .select(
        "vc_id,vc_name,vc_ic_num,vc_contact_no,vc_uni_id,vc_date,vc_month,vc_year,vc_time,created_at",
        { count: "exact" },
      )
      .order("vc_id", { ascending: false })
      .range(from, to),
    supabase.from("universities").select("u_id,u_name"),
  ]);
  const uniMap = new Map((unis || []).map((u) => [Number(u.u_id), String(u.u_name || "")]));
  const total = count ?? 0;
  return (
    <>
      <AdminPageHeader
        title="Campus visits"
        actions={
          <AdminBtn href="/api/admin/export/visitcampus" variant="secondary">
            Export
          </AdminBtn>
        }
      />
      <AdminCard>
        <div className="card-body p-0">
          {!rows?.length ? (
            <EmptyState message="No visits." />
          ) : (
            <AdminTable headers={["Name", "IC", "Contact", "University", "Date", "Time", "Created", ""]}>
              {rows.map((r) => {
                const href = `/admin/visitcampus/${r.vc_id}`;
                const ic = String(r.vc_ic_num || "").trim();
                return (
                  <AdminRow key={r.vc_id} href={href}>
                    <td>
                      <span className="admin-entity-title">{r.vc_name || "—"}</span>
                    </td>
                    <td className="admin-table-ic">{ic || "—"}</td>
                    <td className="admin-table-contact">{formatAdminPhone(r.vc_contact_no)}</td>
                    <td className="admin-table-nowrap">
                      {labelsFromCsvIds(
                        uniMap,
                        r.vc_uni_id == null ? "" : String(r.vc_uni_id),
                        "—",
                      )}
                    </td>
                    <td className="admin-table-nowrap">{formatVisitDate(r.vc_date, r.vc_month, r.vc_year)}</td>
                    <td className="admin-table-nowrap">{formatVisitTime(r.vc_time)}</td>
                    <td className="admin-table-date">{formatAdminDateTime(r.created_at)}</td>
                    <td className="admin-table-actions">
                      <div className="admin-actions">
                        <AdminIconLink href={href} label="View" icon="view" />
                        <DeleteButton
                          action={deleteVisit.bind(null, r.vc_id)}
                          label="Delete"
                          icon="delete"
                          confirmText="Permanently delete this visit? This cannot be undone."
                        />
                      </div>
                    </td>
                  </AdminRow>
                );
              })}
            </AdminTable>
          )}
          <AdminPagination basePath="/admin/visitcampus" page={page} perPage={perPage} total={total} keep={{}} />
        </div>
      </AdminCard>
    </>
  );
}
