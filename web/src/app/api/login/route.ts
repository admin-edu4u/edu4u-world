import { NextRequest, NextResponse } from "next/server";
import { authenticateMember } from "@/lib/member/auth";
import { setMemberSession } from "@/lib/member/session";
import { isValidEmail } from "@/lib/email";

export async function POST(req: NextRequest) {
  try {
    const body = await req.json();
    const email = String(body.email ?? "").trim();
    const password = String(body.password ?? "");

    if (!isValidEmail(email)) {
      return NextResponse.json({
        status: false,
        msg: "Please enter a valid email address.",
      });
    }

    const result = await authenticateMember(email, password);
    if (!result.ok) {
      return NextResponse.json({ status: false, msg: result.msg });
    }

    await setMemberSession({
      userId: result.user.id,
      email: result.user.email ?? email,
    });

    return NextResponse.json({
      status: true,
      msg: "Login Successfully",
      userId: result.user.id,
      email: result.user.email ?? email,
    });
  } catch (err) {
    console.error("[login]", err);
    return NextResponse.json({ status: false, msg: "errSystem" }, { status: 500 });
  }
}
