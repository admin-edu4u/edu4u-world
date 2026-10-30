import { NextResponse } from "next/server";
import { getAdminSession } from "@/lib/admin/session";
import { createServiceClient } from "@/lib/supabase/server";
import { csvResponse } from "@/lib/admin/csv";

export async function GET() {
  const session = await getAdminSession();
  if (!session) return NextResponse.json({ error: "Unauthorized" }, { status: 401 });
  const supabase = createServiceClient();
  const { data } = await supabase.from("visit_campus").select("*").order("vc_id", { ascending: false });
  return csvResponse("visit_campus.csv", (data || []) as Record<string, unknown>[]);
}
