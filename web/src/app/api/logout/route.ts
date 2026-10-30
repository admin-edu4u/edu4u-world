import { NextResponse } from "next/server";
import { clearMemberSession } from "@/lib/member/session";

export async function POST() {
  try {
    await clearMemberSession();
    return NextResponse.json({ status: true });
  } catch (err) {
    console.error("[logout]", err);
    return NextResponse.json({ status: false, msg: "errSystem" }, { status: 500 });
  }
}
