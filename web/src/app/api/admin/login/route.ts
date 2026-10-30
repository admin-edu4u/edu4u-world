import { NextResponse } from "next/server";
import { authenticateAdmin } from "@/lib/admin/auth";
import { setAdminSession } from "@/lib/admin/session";

export async function POST(request: Request) {
  try {
    const body = await request.json();
    const username = String(body.username || "");
    const password = String(body.password || "");
    if (!username || !password) {
      return NextResponse.json({ error: "Username and password are required" }, { status: 400 });
    }
    const result = await authenticateAdmin(username, password);
    if (!result.ok) {
      return NextResponse.json({ error: result.error }, { status: 401 });
    }
    await setAdminSession({
      userId: result.user.id,
      username: result.user.username || username,
      isAdmin: true,
    });
    return NextResponse.json({ ok: true });
  } catch (e) {
    console.error("[admin login]", e);
    return NextResponse.json(
      { error: e instanceof Error ? e.message : "Login failed" },
      { status: 500 },
    );
  }
}
