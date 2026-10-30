import { notFound } from "next/navigation";
import { requireAdmin } from "@/lib/admin/auth";
import { createServiceClient } from "@/lib/supabase/server";
import {
  AdminCard,
  AdminDetailList,
  AdminBtn,
  AdminPageHeader,
  formatAdminDateTime,
} from "@/components/admin/AdminUi";
import { statusLabel } from "@/lib/admin/status";

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
  const { data: row } = await supabase
    .from("enquiries")
    .select("*")
    .eq("e_id", Number(id))
    .maybeSingle();
  if (!row) notFound();

  const name = display(row.e_name);
  const email = display(row.e_email);
  const phone = formatPhone(row.e_phone_num);
  const status = statusLabel(row.e_status);

  return (
    <>
      <AdminPageHeader
        title={name === "—" ? "Enquiry" : name}
        description="Enquiry details"
        actions={
          <AdminBtn href="/admin/enquiries" variant="secondary">
            Back
          </AdminBtn>
        }
      />

      <AdminCard>
        <div className="admin-user-hero">
          <div className="admin-user-hero-text">
            <h2>{name}</h2>
            <p>{email}</p>
            <div className="admin-user-hero-meta">
              <span>{phone}</span>
              <span>{status}</span>
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
              { label: "Name", value: name },
              { label: "Email", value: email },
              { label: "Phone number", value: phone },
              { label: "Status", value: status },
              { label: "Submitted", value: formatAdminDateTime(row.created_at) },
              { label: "Updated", value: formatAdminDateTime(row.updated_at) },
            ]}
          />
        </AdminCard>

        <AdminCard>
          <div className="admin-user-section-head">
            <h3>Message</h3>
          </div>
          <div className="admin-counsellor-remarks">{display(row.e_message)}</div>
        </AdminCard>
      </div>
    </>
  );
}
