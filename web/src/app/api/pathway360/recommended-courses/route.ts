import { NextResponse } from "next/server";
import { createPublicClient } from "@/lib/supabase/server";
import { getLocale } from "@/lib/i18n/locale";
import {
  countUniversities,
  fetchPathwayRecommendedCourses,
  type PathwayCourseFilters,
} from "@/lib/pathwayRecommendedCourses";

type Body = Partial<PathwayCourseFilters>;

export async function POST(request: Request) {
  try {
    const body = (await request.json()) as Body;
    const characterId = Number(body.characterId);
    if (!Number.isFinite(characterId) || characterId <= 0) {
      return NextResponse.json({ status: false, msg: "Invalid character" }, { status: 400 });
    }

    const supabase = createPublicClient();
    const locale = await getLocale();

    const filters: PathwayCourseFilters = {
      characterId,
      studyLevel: typeof body.studyLevel === "number" ? body.studyLevel : undefined,
      budget: typeof body.budget === "number" ? body.budget : undefined,
      income: typeof body.income === "number" ? body.income : undefined,
      malaysian: typeof body.malaysian === "number" ? body.malaysian : undefined,
      uniType: typeof body.uniType === "number" ? body.uniType : undefined,
      studyPlan: typeof body.studyPlan === "number" ? body.studyPlan : undefined,
      states: Array.isArray(body.states) ? body.states.filter((n) => typeof n === "number") : undefined,
      country: typeof body.country === "number" ? body.country : undefined,
    };

    const list = await fetchPathwayRecommendedCourses(supabase, filters, locale);

    return NextResponse.json({
      status: true,
      list,
      ttl_course: list.length,
      ttl_uni: countUniversities(list),
    });
  } catch (e) {
    const msg = e instanceof Error ? e.message : "Failed to load courses";
    return NextResponse.json({ status: false, msg, list: [] }, { status: 500 });
  }
}
