import { NextResponse } from "next/server";
import { getAdminSession } from "@/lib/admin/session";
import { createServiceClient } from "@/lib/supabase/server";
import { csvResponse } from "@/lib/admin/csv";

export async function GET() {
  const session = await getAdminSession();
  if (!session) return NextResponse.json({ error: "Unauthorized" }, { status: 401 });
  const supabase = createServiceClient();
  const { data } = await supabase.from("enquiries").select("*").order("e_id", { ascending: false });
  return csvResponse("enquiries.csv", (data || []) as Record<string, unknown>[]);
}
