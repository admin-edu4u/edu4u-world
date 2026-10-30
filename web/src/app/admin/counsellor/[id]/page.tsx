import { notFound } from "next/navigation";
import { requireAdmin } from "@/lib/admin/auth";
import { createServiceClient } from "@/lib/supabase/server";
import {
  AdminCard,
  AdminDetailList,
  AdminBtn,
  AdminPageHeader,
  formatAdminDateTime,
  formatAdminPhone,
  labelFromMap,
} from "@/components/admin/AdminUi";
import { statusLabel } from "@/lib/admin/status";

const GENDER_LABELS: Record<number, string> = {
  1: "Male",
  2: "Female",
};

function display(value: unknown): string {
  if (value == null) return "—";
  const text = String(value).trim();
  if (!text || text === "0" || text === "—") return "—";
  return text;
}

function mapLabel(map: Record<number, string>, value: unknown): string {
  if (value == null || value === "") return "—";
  const key = Number(value);
  if (!Number.isFinite(key)) return String(value);
  return map[key] ?? String(value);
}

export default async function Page({ params }: { params: Promise<{ id: string }> }) {
  await requireAdmin();
  const { id } = await params;
  const supabase = createServiceClient();
  const { data: row } = await supabase
    .from("consult_counsellor")
    .select("*")
    .eq("cc_id", Number(id))
    .maybeSingle();
  if (!row) notFound();

  const courseId = row.cc_c_id == null ? null : Number(row.cc_c_id);
  const qualId = row.cc_qualification == null ? null : Number(row.cc_qualification);

  const [{ data: course }, { data: qualifications }] = await Promise.all([
    courseId
      ? supabase.from("courses").select("c_id,c_name,c_uni").eq("c_id", courseId).maybeSingle()
      : Promise.resolve({ data: null }),
    supabase.from("qualification_level").select("q_id,q_level").order("q_id"),
  ]);

  const { data: university } = course?.c_uni
    ? await supabase.from("universities").select("u_name").eq("u_id", course.c_uni).maybeSingle()
    : { data: null };

  const qualMap = new Map(
    (qualifications || []).map((q) => [Number(q.q_id), String(q.q_level || "")]),
  );

  const uniName = university?.u_name ? String(university.u_name) : "";
  const courseLabel = course?.c_name
    ? uniName
      ? `${course.c_name} (${uniName})`
      : String(course.c_name)
    : "—";

  const fullName = [row.cc_salutation, row.cc_name].filter(Boolean).join(" ").trim() || "Counsellor request";
  const gender = mapLabel(GENDER_LABELS, row.cc_gender);
  const contact = formatAdminPhone(row.cc_contact_no);

  return (
    <>
      <AdminPageHeader
        title={fullName}
        description="Counsellor request details"
        actions={
          <AdminBtn href="/admin/counsellor" variant="secondary">
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
              <span>{gender}</span>
              <span>{statusLabel(row.cc_status)}</span>
              <span>{formatAdminDateTime(row.created_at)}</span>
            </div>
          </div>
        </div>
      </AdminCard>

      <div className="admin-user-sections">
        <AdminCard>
          <div className="admin-user-section-head">
            <h3>Contact</h3>
          </div>
          <AdminDetailList
            items={[
              { label: "Salutation", value: display(row.cc_salutation) },
              { label: "Full name", value: display(row.cc_name) },
              { label: "Gender", value: gender },
              { label: "Date of birth", value: display(row.cc_dob) },
              { label: "Mobile number", value: contact },
              { label: "Home phone number", value: formatAdminPhone(row.cc_home_contact_no) },
              ...(row.cc_email != null
                ? [{ label: "Email", value: display(row.cc_email) }]
                : []),
            ]}
          />
        </AdminCard>

        <AdminCard>
          <div className="admin-user-section-head">
            <h3>Request</h3>
          </div>
          <AdminDetailList
            items={[
              { label: "Course of interest", value: courseLabel },
              { label: "Preferred course", value: display(row.cc_course) },
              {
                label: "Highest qualification",
                value: labelFromMap(qualMap, qualId),
              },
              { label: "Secondary school", value: display(row.cc_school_name) },
              ...(row.cc_transcript != null
                ? [{ label: "Transcript", value: display(row.cc_transcript) }]
                : []),
              { label: "Status", value: statusLabel(row.cc_status) },
              { label: "Submitted", value: formatAdminDateTime(row.created_at) },
              { label: "Updated", value: formatAdminDateTime(row.updated_at) },
            ]}
          />
        </AdminCard>

        <AdminCard>
          <div className="admin-user-section-head">
            <h3>Remarks</h3>
          </div>
          <div className="admin-counsellor-remarks">
            {display(row.cc_remarks)}
          </div>
        </AdminCard>
      </div>
    </>
  );
}
