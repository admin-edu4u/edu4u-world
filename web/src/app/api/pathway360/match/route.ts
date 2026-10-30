import { NextResponse } from "next/server";
import { createPublicClient } from "@/lib/supabase/server";
import { mediaUrl } from "@/lib/admin/media";
import { getLocale } from "@/lib/i18n/locale";
import { pickLocalizedFields } from "@/lib/i18n/cms";
import { CMS_I18N_FIELDS } from "@/lib/admin/cmsFields";
import {
  matchPercentagesForVotes,
  tallyCharacterVotes,
  type CharacterVote,
} from "@/lib/pathwayCharacterMatch";

type Body = {
  describe?: number[];
  interest?: number[];
  gender?: number;
};

function isIdArray(value: unknown): value is number[] {
  return Array.isArray(value) && value.every((x) => typeof x === "number" && Number.isFinite(x));
}

function isActivePersonality(status: unknown): boolean {
  return String(status) === "1";
}

async function countCoursesForCharacter(supabase: ReturnType<typeof createPublicClient>, characterId: number) {
  const { data: subCats } = await supabase.from("sub_cats").select("sc_id, sc_character").eq("sc_status", 1);

  const subCatIds = (subCats ?? [])
    .filter((row) => Number(row.sc_character) === characterId || String(row.sc_character) === String(characterId))
    .map((r) => r.sc_id);

  if (subCatIds.length === 0) {
    return { courseCount: 0, universityCount: 0 };
  }

  const { data: courses } = await supabase
    .from("courses")
    .select("c_id, c_uni")
    .eq("c_status", 1)
    .in("c_sub_cat", subCatIds);

  const rows = courses ?? [];
  const uniIds = new Set(rows.map((c) => c.c_uni).filter((id) => typeof id === "number"));
  return { courseCount: rows.length, universityCount: uniIds.size };
}

function pickTopActiveVotes(allVotes: CharacterVote[], activeIds: Set<number>, limit: number) {
  const eligible = allVotes.filter((v) => activeIds.has(v.characterId)).slice(0, limit);
  const percentages = matchPercentagesForVotes(eligible);
  return eligible.map((vote, i) => ({
    characterId: vote.characterId,
    count: vote.count,
    percentage: percentages[i] ?? 75,
    rank: i + 1,
  }));
}

export async function POST(request: Request) {
  try {
    const body = (await request.json()) as Body;
    const describe = isIdArray(body.describe) ? body.describe : [];
    const interest = isIdArray(body.interest) ? body.interest : [];
    const personalityIds = [...new Set([...describe, ...interest])];

    if (personalityIds.length === 0) {
      return NextResponse.json({ status: false, msg: "No personality selections" }, { status: 400 });
    }

    const supabase = createPublicClient();
    const locale = await getLocale();

    const { data: personalities, error: pErr } = await supabase
      .from("personalities")
      .select("p_id, p_character, p_status")
      .in("p_id", personalityIds);

    if (pErr) {
      return NextResponse.json({ status: false, msg: pErr.message }, { status: 500 });
    }

    const personalityMap = new Map<number, string>();
    for (const row of personalities ?? []) {
      if (!isActivePersonality(row.p_status)) continue;
      personalityMap.set(row.p_id, row.p_character ?? "");
    }

    const allVotes = tallyCharacterVotes(personalityIds, personalityMap);
    if (allVotes.length === 0) {
      return NextResponse.json({ status: true, cards: [] });
    }

    const { data: activeCharacters, error: acErr } = await supabase
      .from("characters")
      .select("c_id, c_name, c_major, c_aver_salary, c_desc, c_img_male, c_img_female, i18n")
      .eq("c_status", 1);

    if (acErr) {
      return NextResponse.json({ status: false, msg: acErr.message }, { status: 500 });
    }

    const activeIds = new Set((activeCharacters ?? []).map((c) => c.c_id));
    const ranked = pickTopActiveVotes(allVotes, activeIds, 3);
    if (ranked.length === 0) {
      return NextResponse.json({ status: true, cards: [] });
    }

    const byId = new Map(
      (activeCharacters ?? []).map((raw) => {
        const c = pickLocalizedFields(raw, CMS_I18N_FIELDS.character, locale);
        return [c.c_id, c] as const;
      }),
    );

    const gender = body.gender === 2 ? 2 : 1;
    const cards = [];

    for (const match of ranked) {
      const c = byId.get(match.characterId);
      if (!c) continue;
      const img = gender === 2 ? c.c_img_female : c.c_img_male;
      const { courseCount, universityCount } = await countCoursesForCharacter(supabase, match.characterId);
      cards.push({
        id: c.c_id,
        name: c.c_name,
        major: c.c_major ?? "",
        avg_salary: c.c_aver_salary,
        desc: c.c_desc,
        avatar: img ? mediaUrl("characters", img) : "",
        percentage: match.percentage,
        rank: match.rank,
        courseCount,
        universityCount,
      });
    }

    return NextResponse.json({ status: true, cards });
  } catch (e) {
    const msg = e instanceof Error ? e.message : "Match failed";
    return NextResponse.json({ status: false, msg }, { status: 500 });
  }
}
