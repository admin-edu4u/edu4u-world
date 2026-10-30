import { NextRequest, NextResponse } from "next/server";
import { createServiceClient } from "@/lib/supabase/server";
import { hashPassword } from "@/lib/passwordHash";
import { isValidEmail } from "@/lib/email";
import { signupPasswordErrorKey } from "@/lib/password";

export async function POST(req: NextRequest) {
  try {
    const body = await req.json();
    const email = String(body.email ?? "").trim();
    const password = String(body.password ?? "");
    const confirmPassword = String(body.confirm_password ?? "");

    if (!isValidEmail(email)) {
      return NextResponse.json({ status: false, msg: "Please enter a valid email address." });
    }
    if (!password) {
      return NextResponse.json({ status: false, msg: "Please enter password." });
    }
    if (!confirmPassword) {
      return NextResponse.json({ status: false, msg: "Please enter repeat password." });
    }

    const passwordKey = signupPasswordErrorKey(password);
    if (passwordKey) {
      return NextResponse.json({ status: false, msg: passwordKey });
    }
    if (password !== confirmPassword) {
      return NextResponse.json({ status: false, msg: "Passwords must match." });
    }

    const supabase = createServiceClient();
    const { data: existing } = await supabase
      .from("app_users")
      .select("id")
      .eq("email", email)
      .maybeSingle();

    if (existing) {
      return NextResponse.json({ status: false, msg: "Email is already registered." });
    }

    const hashedPassword = hashPassword(password);

    const { nextSerialId } = await import("@/lib/nextSerialId");
    const nextId = await nextSerialId(supabase, "app_users", "id");
    const now = new Date().toISOString();

    const { error } = await supabase.from("app_users").insert({
      id: nextId,
      email,
      username: email,
      password: hashedPassword,
      referral: 1,
      isactive: 0,
      isadmin: 0,
      created_at: now,
      updated_at: now,
    });

    if (error) {
      console.error("sign-up insert error:", error);
      return NextResponse.json({ status: false, msg: "Something went wrong. Please try again." }, { status: 500 });
    }

    const mlmId = `SP${String(nextId).padStart(4, "0")}`;
    await supabase.from("app_users").update({ mlm_id: mlmId, updated_at: now }).eq("id", nextId);

    return NextResponse.json({ status: true, msg: "Sign up successful." });
  } catch (err) {
    console.error("sign-up route error:", err);
    return NextResponse.json({ status: false, msg: "Something went wrong. Please try again." }, { status: 500 });
  }
}
