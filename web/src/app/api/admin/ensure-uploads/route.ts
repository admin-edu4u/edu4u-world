import { NextResponse } from "next/server";
import { getAdminSession } from "@/lib/admin/session";
import { ensureAdminBucket } from "@/lib/admin/upload";

export async function POST() {
  const session = await getAdminSession();
  if (!session) return NextResponse.json({ error: "Unauthorized" }, { status: 401 });
  try {
    await ensureAdminBucket();
    return NextResponse.json({ ok: true, bucket: "admin-uploads" });
  } catch (e) {
    return NextResponse.json(
      { error: e instanceof Error ? e.message : "Failed to ensure bucket" },
      { status: 500 },
    );
  }
}
