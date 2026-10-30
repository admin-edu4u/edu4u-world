import { NextRequest, NextResponse } from "next/server";
import { createPublicClient } from "@/lib/supabase/server";
import { CDN } from "@/lib/cdn";
import { getLocale } from "@/lib/i18n/locale";
import { getLocalized } from "@/lib/i18n/cms";

const COUNTRY_NAMES: Record<number, string> = {
  129: "Malaysia",
  192: "Singapore",
  13: "Australia",
  826: "United Kingdom",
  840: "United States",
};

export async function GET(req: NextRequest) {
  const supabase = createPublicClient();
  const locale = await getLocale();
  const { searchParams } = new URL(req.url);

  const search = searchParams.get("search") || "";
  const sort = searchParams.get("sort") || "1";
  const studyField = searchParams.getAll("study_field[]");
  const level = searchParams.getAll("level[]");
  const country = searchParams.getAll("country[]");
  const state = searchParams.getAll("state[]");

  try {
    // Fetch states for name lookup
    const { data: statesData } = await supabase
      .from("states")
      .select("state_id,state_name");
    const statesMap: Record<number, string> = {};
    (statesData || []).forEach((s: { state_id: number; state_name: string }) => {
      statesMap[s.state_id] = s.state_name;
    });

    let query = supabase
      .from("universities")
      .select(
        "u_id,u_url_code,u_name,u_logo,u_country,u_state,u_has_branch,u_ranking,u_has_rank,i18n"
      )
      .eq("u_status", 1);

    if (search) query = query.ilike("u_name", `%${search}%`);
    if (country.length) query = query.in("u_country", country.map(Number));
    if (state.length) query = query.in("u_state", state.map(Number));

    query = query.order("u_name", { ascending: sort !== "2" });

    const { data, error } = await query;
    if (error) {
      console.error("Universities query error:", error);
      return NextResponse.json({ status: false, list: [] });
    }

    const uniIds = (data || []).map((u) => u.u_id);
    const courseCounts: Record<number, number> = {};
    const scholarshipCounts: Record<number, number> = {};

    if (uniIds.length) {
      const [{ data: courses }, { data: scholarships }] = await Promise.all([
        supabase.from("courses").select("c_uni").eq("c_status", 1).in("c_uni", uniIds),
        supabase.from("scholarships").select("s_uni_id,s_status").in("s_uni_id", uniIds),
      ]);
      for (const c of courses || []) {
        courseCounts[c.c_uni] = (courseCounts[c.c_uni] || 0) + 1;
      }
      for (const s of scholarships || []) {
        // Count active (1) and imported soft-deleted (99) so cards aren't all zeros
        if (s.s_status === 1 || s.s_status === 99) {
          scholarshipCounts[s.s_uni_id] =
            (scholarshipCounts[s.s_uni_id] || 0) + 1;
        }
      }
    }

    const list = (data || []).map((u) => {
      let stateName = "";
      if (u.u_country === 129 && u.u_state) {
        stateName = statesMap[u.u_state] || "";
      } else {
        stateName = COUNTRY_NAMES[u.u_country] || "";
      }

      return {
        id: u.u_id,
        url: u.u_url_code || String(u.u_id),
        name: getLocalized(u, "u_name", locale) || "",
        img: u.u_logo ? `${CDN}/images/university/logo/${u.u_logo}` : "",
        has_branch: u.u_has_branch === 1,
        ttl_branch: 0,
        ttl_course: courseCounts[u.u_id] || 0,
        ttl_scholarship: scholarshipCounts[u.u_id] || 0,
        state: stateName,
      };
    });

    return NextResponse.json({ status: true, list });
  } catch (e) {
    console.error("Universities API error:", e);
    return NextResponse.json({ status: false, list: [] }, { status: 500 });
  }
}
