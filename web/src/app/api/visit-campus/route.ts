import { NextRequest, NextResponse } from "next/server";
import { createServiceClient } from "@/lib/supabase/server";
import { nextSerialId } from "@/lib/nextSerialId";

export async function POST(req: NextRequest) {
  try {
    const body = await req.json();
    const {
      vc_uni_id,
      vc_salutation,
      vc_name,
      vc_ic_num,
      vc_contact_no,
      vc_pax,
      vc_date,
      vc_month,
      vc_year,
      vc_time,
      vc_remarks,
    } = body;

    if (!vc_name || !vc_ic_num || !vc_contact_no) {
      return NextResponse.json({ error: "Missing required fields" }, { status: 400 });
    }

    const supabase = createServiceClient();
    const nextId = await nextSerialId(supabase, "visit_campus", "vc_id");
    const { error } = await supabase.from("visit_campus").insert({
      vc_id: nextId,
      vc_uni_id: String(vc_uni_id || ""),
      vc_salutation: vc_salutation || null,
      vc_name: String(vc_name),
      vc_ic_num: String(vc_ic_num),
      vc_contact_no: String(vc_contact_no),
      vc_pax: Number(vc_pax) || 1,
      vc_date: Number(vc_date) || 0,
      vc_month: Number(vc_month) || 0,
      vc_year: Number(vc_year) || 0,
      vc_time: Number(vc_time) || 0,
      vc_remarks: vc_remarks || null,
      created_at: new Date().toISOString(),
      updated_at: new Date().toISOString(),
    });

    if (error) {
      console.error("visit_campus insert error:", error);
      return NextResponse.json({ error: "Database error" }, { status: 500 });
    }

    return NextResponse.json({ success: true });
  } catch (err) {
    console.error("visit-campus route error:", err);
    return NextResponse.json({ error: "Internal server error" }, { status: 500 });
  }
}
