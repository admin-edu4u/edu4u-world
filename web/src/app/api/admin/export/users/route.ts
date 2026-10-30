import { NextRequest, NextResponse } from "next/server";
import { getAdminSession } from "@/lib/admin/session";
import { createServiceClient } from "@/lib/supabase/server";
import { csvResponse } from "@/lib/admin/csv";
import { parseUserSearchQuery, userSearchOrFilter } from "@/lib/admin/userSearch";

export async function GET(req: NextRequest) {
  const session = await getAdminSession();
  if (!session) return NextResponse.json({ error: "Unauthorized" }, { status: 401 });
  const q = parseUserSearchQuery(req.nextUrl.searchParams.get("q") ?? undefined);
  const supabase = createServiceClient();
  let query = supabase
    .from("app_users")
    .select("id,username,email,fullname,first_name,last_name,contact_code,contact_no,isactive,isadmin,created_at")
    .eq("isadmin", 0);
  const searchFilter = userSearchOrFilter(q);
  if (searchFilter) query = query.or(searchFilter);
  const { data } = await query
    .order("created_at", { ascending: false, nullsFirst: false })
    .order("id", { ascending: false });
  return csvResponse("users.csv", (data || []) as Record<string, unknown>[]);
}
