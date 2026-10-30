import type { SupabaseClient } from "@supabase/supabase-js";
import { parseCsvIds } from "@/lib/admin/constants";
import { rankTopCharacterMatches } from "@/lib/pathwayCharacterMatch";

export type UserCharacterScore = { id: number; percentage: number };

export function parseCharacterScores(raw: unknown): UserCharacterScore[] {
  if (!raw) return [];
  try {
    const parsed = typeof raw === "string" ? JSON.parse(raw) : raw;
    if (!Array.isArray(parsed)) return [];
    return parsed
      .map((item) => ({
        id: Number(item?.id),
        percentage: Number(item?.percentage),
      }))
      .filter((item) => Number.isFinite(item.id));
  } catch {
    return [];
  }
}

/** When stored character ids are missing from DB, re-derive from personality votes (legacy parity). */
export async function resolveUserCharacterScores(
  supabase: SupabaseClient,
  row: {
    character: unknown;
    personality: unknown;
    interests: unknown;
  },
): Promise<UserCharacterScore[]> {
  const stored = parseCharacterScores(row.character);
  if (stored.length === 0) return [];

  const ids = stored.map((s) => s.id);
  const { data: existing } = await supabase.from("characters").select("c_id").in("c_id", ids);
  const found = new Set((existing ?? []).map((c) => c.c_id));
  if (ids.every((id) => found.has(id))) return stored;

  const personalityIds = [
    ...new Set(
      [...parseCsvIds(String(row.personality ?? "")), ...parseCsvIds(String(row.interests ?? ""))]
        .map(Number)
        .filter(Number.isFinite),
    ),
  ];
  if (personalityIds.length === 0) return stored;

  const { data: personalities } = await supabase
    .from("personalities")
    .select("p_id,p_character,p_status")
    .in("p_id", personalityIds);

  const personalityMap = new Map<number, string>();
  for (const p of personalities ?? []) {
    if (String(p.p_status) !== "1") continue;
    personalityMap.set(p.p_id, p.p_character ?? "");
  }

  const ranked = rankTopCharacterMatches(personalityIds, personalityMap, stored.length);
  if (ranked.length === 0) return stored;

  const percentages = [...stored]
    .sort((a, b) => b.percentage - a.percentage)
    .map((s) => s.percentage);

  return ranked.map((match, index) => ({
    id: match.characterId,
    percentage: Number.isFinite(percentages[index]) ? percentages[index]! : match.percentage,
  }));
}
