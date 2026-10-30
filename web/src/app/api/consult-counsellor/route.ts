import { NextRequest, NextResponse } from "next/server";
import { createServiceClient } from "@/lib/supabase/server";

export async function POST(req: NextRequest) {
  try {
    const body = await req.json();
    const {
      course_id,
      salutation,
      name,
      gender,
      dob_date,
      dob_month,
      dob_year,
      school_name,
      contact_no,
      home_contact_no,
      preferred_course,
      qualification,
      remarks,
      pathway_character,
    } = body;

    if (!name || !contact_no) {
      return NextResponse.json({ error: "Missing required fields" }, { status: 400 });
    }

    const courseIdNum =
      course_id != null && course_id !== "" && Number(course_id) > 0 ? Number(course_id) : 0;

    let remarksFinal = remarks ? String(remarks).trim() : "";
    if (pathway_character) {
      const prefix = `Pathway360 character: ${String(pathway_character).trim()}`;
      remarksFinal = remarksFinal ? `${prefix}\n\n${remarksFinal}` : prefix;
    }

    const dob = dob_date && dob_month && dob_year
      ? `${String(dob_date).padStart(2, "0")}/${String(dob_month).padStart(2, "0")}/${dob_year}`
      : null;

    const supabase = createServiceClient();
    const { nextSerialId } = await import("@/lib/nextSerialId");
    const nextId = await nextSerialId(supabase, "consult_counsellor", "cc_id");
    const { error } = await supabase.from("consult_counsellor").insert({
      cc_id: nextId,
      cc_c_id: courseIdNum,
      cc_salutation: salutation || null,
      cc_name: String(name),
      cc_contact_no: String(contact_no),
      cc_home_contact_no: home_contact_no ? String(home_contact_no) : null,
      cc_dob: dob,
      cc_school_name: school_name || null,
      cc_gender: gender ? Number(gender) : null,
      cc_course: preferred_course || null,
      cc_qualification: qualification ? Number(qualification) : null,
      cc_remarks: remarksFinal || null,
      cc_status: 1,
      created_at: new Date().toISOString(),
      updated_at: new Date().toISOString(),
    });

    if (error) {
      console.error("consult_counsellor insert error:", error);
      return NextResponse.json({ error: "Database error" }, { status: 500 });
    }

    return NextResponse.json({ success: true });
  } catch (err) {
    console.error("consult-counsellor route error:", err);
    return NextResponse.json({ error: "Internal server error" }, { status: 500 });
  }
}
