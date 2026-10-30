import { NextRequest, NextResponse } from "next/server";
import { createPublicClient } from "@/lib/supabase/server";

function escapeIlike(value: string): string {
  return value.replace(/[%_\\]/g, "\\$&");
}

export async function GET(req: NextRequest) {
  const raw =
    new URL(req.url).searchParams.get("q") ||
    new URL(req.url).searchParams.get("search") ||
    "";
  const q = raw.trim().replace(/[%_\\]/g, " ").replace(/\s+/g, " ").trim();

  if (q.length < 3) {
    return NextResponse.json({ status: false, list: [] as { id: number; name: string }[] });
  }

  const supabase = createPublicClient();
  const { data, error } = await supabase
    .from("schools")
    .select("s_id,s_name")
    .eq("s_status", 1)
    .ilike("s_name", `%${escapeIlike(q)}%`)
    .order("s_name", { ascending: true })
    .limit(4);

  if (error) {
    return NextResponse.json({ status: false, list: [] });
  }

  if (!data?.length) {
    return NextResponse.json({ status: false, list: [] });
  }

  return NextResponse.json({
    status: true,
    list: data.map((row) => ({ id: row.s_id, name: row.s_name })),
  });
}
