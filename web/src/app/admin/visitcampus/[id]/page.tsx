import { notFound } from "next/navigation";
import { requireAdmin } from "@/lib/admin/auth";
import { createServiceClient } from "@/lib/supabase/server";
import {
  AdminCard,
  AdminDetailList,
  AdminBtn,
  AdminPageHeader,
  formatAdminDateTime,
  formatVisitDate,
  formatVisitTime,
  labelsFromCsvIds,
} from "@/components/admin/AdminUi";

function display(value: unknown): string {
  if (value == null) return "—";
  const text = String(value).trim();
  if (!text || text === "0" || text === "—") return "—";
  return text;
}

function formatPhone(value: unknown): string {
  const text = display(value);
  if (text === "—") return text;
  if (/^\d+$/.test(text) && !text.startsWith("0") && text.length >= 9) {
    return `+60${text}`;
  }
  return text;
}

export default async function Page({ params }: { params: Promise<{ id: string }> }) {
  await requireAdmin();
  const { id } = await params;
  const supabase = createServiceClient();
  const [{ data: row }, { data: unis }] = await Promise.all([
    supabase.from("visit_campus").select("*").eq("vc_id", Number(id)).maybeSingle(),
    supabase.from("universities").select("u_id,u_name"),
  ]);
  if (!row) notFound();

  const uniMap = new Map((unis || []).map((u) => [Number(u.u_id), String(u.u_name || "")]));
  const universityLabel = labelsFromCsvIds(
    uniMap,
    row.vc_uni_id == null ? "" : String(row.vc_uni_id),
    "—",
  );
  const visitDate = formatVisitDate(row.vc_date, row.vc_month, row.vc_year);
  const visitTime = formatVisitTime(row.vc_time);
  const fullName =
    [row.vc_salutation, row.vc_name].filter(Boolean).join(" ").trim() || "Campus visit";
  const contact = formatPhone(row.vc_contact_no);

  return (
    <>
      <AdminPageHeader
        title={fullName}
        description="Campus visit details"
        actions={
          <AdminBtn href="/admin/visitcampus" variant="secondary">
            Back
          </AdminBtn>
        }
      />

      <AdminCard>
        <div className="admin-user-hero">
          <div className="admin-user-hero-text">
            <h2>{fullName}</h2>
            <p>{contact}</p>
            <div className="admin-user-hero-meta">
              <span>{universityLabel}</span>
              <span>{visitDate}</span>
              <span>{visitTime}</span>
            </div>
          </div>
        </div>
      </AdminCard>

      <div className="admin-user-sections">
        <AdminCard>
          <div className="admin-user-section-head">
            <h3>Visitor</h3>
          </div>
          <AdminDetailList
            items={[
              { label: "Salutation", value: display(row.vc_salutation) },
              { label: "Full name", value: display(row.vc_name) },
              { label: "IC number", value: display(row.vc_ic_num) },
              { label: "Contact number", value: contact },
              { label: "Number of pax", value: display(row.vc_pax) },
            ]}
          />
        </AdminCard>

        <AdminCard>
          <div className="admin-user-section-head">
            <h3>Visit details</h3>
          </div>
          <AdminDetailList
            items={[
              { label: "University preference", value: universityLabel },
              ...(row.vc_branch != null
                ? [{ label: "Branch", value: display(row.vc_branch) }]
                : []),
              { label: "Preferred date", value: visitDate },
              { label: "Preferred time", value: visitTime },
              { label: "Submitted", value: formatAdminDateTime(row.created_at) },
              { label: "Updated", value: formatAdminDateTime(row.updated_at) },
            ]}
          />
        </AdminCard>

        <AdminCard>
          <div className="admin-user-section-head">
            <h3>Remarks</h3>
          </div>
          <div className="admin-counsellor-remarks">{display(row.vc_remarks)}</div>
        </AdminCard>
      </div>
    </>
  );
}
