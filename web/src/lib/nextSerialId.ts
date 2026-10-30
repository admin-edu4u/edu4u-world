import type { SupabaseClient } from "@supabase/supabase-js";

/** Allocate next integer PK when Postgres sequences are out of sync after MySQL import. */
export async function nextSerialId(
  supabase: SupabaseClient,
  table: string,
  column: string,
): Promise<number> {
  const { data } = await supabase
    .from(table)
    .select(column)
    .order(column, { ascending: false })
    .limit(1)
    .maybeSingle();
  const current = (data as Record<string, number> | null)?.[column] || 0;
  return current + 1;
}
