import { NextRequest, NextResponse } from "next/server";
import { createServiceClient } from "@/lib/supabase/server";

export async function POST(req: NextRequest) {
  try {
    const body = await req.json();
    const {
      course_id,
      salutation,
      name,
      dob_date,
      dob_month,
      dob_year,
      ic_num,
      gender,
      nationality,
      martial_status,
      ethnicity,
      religion,
      home_contact_no,
      contact_no,
      email,
      emergency_contact_name,
      emergency_contact_no,
      emergency_contact_email,
      relationship,
      p_addr,
      p_city,
      p_state,
      p_country,
      p_postcode,
      m_addr,
      m_city,
      m_state,
      m_country,
      m_postcode,
      qualification,
      school_name,
      year_completion,
      credit_transfer,
      member_uni,
      english_q,
      english_score,
      english_year,
      income,
      programme_name,
      programme_month,
      programme_year,
      disability,
      disability_name,
    } = body;

    if (!name || !contact_no || !email || !course_id) {
      return NextResponse.json({ error: "Missing required fields" }, { status: 400 });
    }

    const dob = dob_date && dob_month && dob_year
      ? `${String(dob_date).padStart(2, "0")}/${String(dob_month).padStart(2, "0")}/${dob_year}`
      : null;

    const supabase = createServiceClient();
    const { nextSerialId } = await import("@/lib/nextSerialId");
    const nextId = await nextSerialId(supabase, "course_application", "ca_id");
    const { error } = await supabase.from("course_application").insert({
      ca_id: nextId,
      ca_c_id: Number(course_id),
      ca_salutation: salutation || null,
      ca_name: String(name),
      ca_dob: dob,
      ca_ic_num: ic_num ? String(ic_num) : null,
      ca_gender: gender ? Number(gender) : null,
      ca_nationality: nationality ? Number(nationality) : null,
      ca_martial_status: martial_status ? Number(martial_status) : null,
      ca_ethnicity: ethnicity ? Number(ethnicity) : null,
      ca_religion: religion ? Number(religion) : null,
      ca_home_phone: home_contact_no ? String(home_contact_no) : null,
      ca_phone: String(contact_no),
      ca_email: String(email),
      ca_e_name: emergency_contact_name ? String(emergency_contact_name) : null,
      ca_e_contact: emergency_contact_no ? String(emergency_contact_no) : null,
      ca_e_email: emergency_contact_email ? String(emergency_contact_email) : null,
      ca_e_relationship: relationship ? Number(relationship) : null,
      ca_p_addr: p_addr || null,
      ca_p_city: p_city || null,
      ca_p_state: p_state || null,
      ca_p_country: p_country || null,
      ca_p_postcode: p_postcode || null,
      ca_m_addr: m_addr || null,
      ca_m_city: m_city || null,
      ca_m_state: m_state || null,
      ca_m_country: m_country || null,
      ca_m_postcode: m_postcode || null,
      ca_qualification: qualification ? Number(qualification) : null,
      ca_qualification_school_name: school_name || null,
      ca_qualification_year: year_completion || null,
      ca_credit_transfer: credit_transfer ? String(credit_transfer) : null,
      ca_first_family: member_uni ? String(member_uni) : null,
      ca_e_qualification: english_q ? Number(english_q) : null,
      ca_e_score: english_score || null,
      ca_e_year: english_year ? String(english_year) : null,
      ca_h_income: income ? Number(income) : null,
      ca_programme_name: programme_name || null,
      ca_programme_month: programme_month ? String(programme_month) : null,
      ca_programme_year: programme_year ? String(programme_year) : null,
      ca_has_disability: disability ? Number(disability) : null,
      ca_disability_desc: disability_name || null,
      ca_status: 1,
      created_at: new Date().toISOString(),
      updated_at: new Date().toISOString(),
    });

    if (error) {
      console.error("course_application insert error:", error);
      return NextResponse.json({ error: "Database error" }, { status: 500 });
    }

    return NextResponse.json({ success: true });
  } catch (err) {
    console.error("course-application route error:", err);
    return NextResponse.json({ error: "Internal server error" }, { status: 500 });
  }
}
