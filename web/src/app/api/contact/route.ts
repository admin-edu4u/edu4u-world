import { NextRequest, NextResponse } from "next/server";
import { createServiceClient } from "@/lib/supabase/server";
import { nextSerialId } from "@/lib/nextSerialId";
import { getDictionary, translate } from "@/lib/i18n/dictionaries";
import { getLocale } from "@/lib/i18n/locale";

export async function POST(req: NextRequest) {
  const locale = await getLocale();
  const dict = await getDictionary(locale);
  const t = (key: string) => translate(dict, key);

  try {
    const body = await req.json();
    const { name, email, contact_no, message } = body;

    if (!name || !email || !contact_no || !message) {
      return NextResponse.json(
        { status: false, msg: t("contactAllFieldsRequired") },
        { status: 400 },
      );
    }

    const supabase = createServiceClient();
    const nextId = await nextSerialId(supabase, "enquiries", "e_id");

    const { error } = await supabase.from("enquiries").insert({
      e_id: nextId,
      e_name: name,
      e_email: email,
      e_phone_num: contact_no,
      e_message: message,
      e_status: 1,
      created_at: new Date().toISOString(),
      updated_at: new Date().toISOString(),
    });

    if (error) {
      console.error("Contact insert error:", error);
      return NextResponse.json(
        { status: false, msg: t("contactSubmitFailed") },
        { status: 500 },
      );
    }

    return NextResponse.json({
      status: true,
      msg: t("contactThankYou"),
    });
  } catch (err) {
    console.error("Contact route error:", err);
    return NextResponse.json(
      { status: false, msg: t("contactUnexpectedError") },
      { status: 500 },
    );
  }
}
