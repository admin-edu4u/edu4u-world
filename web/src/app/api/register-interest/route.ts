import { NextRequest, NextResponse } from "next/server";
import { createServiceClient } from "@/lib/supabase/server";

export async function POST(req: NextRequest) {
  try {
    const body = await req.json();
    const {
      ri_name,
      ri_email,
      ri_contact,
      ri_birthday,
      ri_school,
      ri_state,
      ri_course,
      ri_level,
      ri_uni,
      ri_remarks,
    } = body;

    if (!ri_name || !ri_email || !ri_contact) {
      return NextResponse.json({ error: "Missing required fields" }, { status: 400 });
    }

    const supabase = createServiceClient();
    const { nextSerialId } = await import("@/lib/nextSerialId");
    const nextId = await nextSerialId(supabase, "register_interests", "ri_id");
    const { error } = await supabase.from("register_interests").insert({
      ri_id: nextId,
      ri_name: String(ri_name),
      ri_email: String(ri_email),
      ri_contact: String(ri_contact),
      ri_birthday: String(ri_birthday || ""),
      ri_school: String(ri_school || ""),
      ri_state: Number(ri_state) || 0,
      ri_course: String(ri_course || ""),
      ri_level: Number(ri_level) || 0,
      ri_uni: String(ri_uni || ""),
      ri_remarks: ri_remarks || null,
      created_at: new Date().toISOString(),
      updated_at: new Date().toISOString(),
    });

    if (error) {
      console.error("register_interests insert error:", error);
      return NextResponse.json({ error: "Database error" }, { status: 500 });
    }

    return NextResponse.json({ success: true });
  } catch (err) {
    console.error("register-interest route error:", err);
    return NextResponse.json({ error: "Internal server error" }, { status: 500 });
  }
}
