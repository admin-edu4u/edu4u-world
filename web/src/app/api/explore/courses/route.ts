import { NextRequest, NextResponse } from "next/server";
import { createPublicClient } from "@/lib/supabase/server";
import { CDN } from "@/lib/cdn";
import { getLocale } from "@/lib/i18n/locale";
import { getLocalized } from "@/lib/i18n/cms";
import { formatMoney } from "@/lib/money";

function formatDuration(months: number): string {
  const years = months / 12;
  if (years === Math.floor(years)) {
    return `${Math.floor(years)} ${Math.floor(years) === 1 ? "Year" : "Years"}`;
  }
  return `${years.toFixed(1)} Years`;
}

function formatIntake(intake: string): string {
  const MONTHS = [
    "",
    "Jan",
    "Feb",
    "Mar",
    "Apr",
    "May",
    "Jun",
    "Jul",
    "Aug",
    "Sep",
    "Oct",
    "Nov",
    "Dec",
  ];
  return (intake || "")
    .split(",")
    .map((m) => MONTHS[parseInt(m, 10)] || "")
    .filter(Boolean)
    .join(" ");
}

export async function GET(req: NextRequest) {
  const supabase = createPublicClient();
  const locale = await getLocale();
  const { searchParams } = new URL(req.url);

  const search = searchParams.get("search") || "";
  const sort = searchParams.get("sort") || "1";
  const level = searchParams.getAll("level[]");
  const country = searchParams.getAll("country[]");
  const state = searchParams.getAll("state[]");
  const feeRanges = searchParams.getAll("fee[]");
  const durationYears = searchParams.getAll("duration[]");
  const months = searchParams.getAll("month[]");
  const studyField = searchParams.getAll("study_field[]");

  try {
    const feeFilters: { min: number; max: number }[] = [];
    feeRanges.forEach((v) => {
      const n = Number(v);
      if (n === 1) feeFilters.push({ min: 0, max: 20000 });
      else if (n === 2) feeFilters.push({ min: 20001, max: 40000 });
      else if (n === 3) feeFilters.push({ min: 40001, max: 60000 });
      else if (n === 4) feeFilters.push({ min: 60001, max: 80000 });
      else if (n === 5) feeFilters.push({ min: 80001, max: 100000 });
      else if (n === 6) feeFilters.push({ min: 100001, max: 99999999 });
    });

    let query = supabase
      .from("courses")
      .select(
        "c_id,c_url_code,c_name,c_intake,c_duration,c_amount,c_uni,c_main_cat,c_level,i18n",
      )
      .eq("c_status", 1);

    if (search) query = query.ilike("c_name", `%${search}%`);
    if (level.length) query = query.in("c_level", level.map(Number));
    if (studyField.length) query = query.in("c_main_cat", studyField.map(Number));

    if (sort === "2") {
      query = query.order("c_amount", { ascending: false });
    } else {
      query = query.order("c_amount", { ascending: true });
    }

    const { data, error } = await query;
    if (error) {
      console.error("Courses query error:", error);
      return NextResponse.json({ status: false, list: [], msg: error.message });
    }

    const uniIds = [
      ...new Set((data || []).map((c) => c.c_uni).filter(Boolean)),
    ];
    const uniMap: Record<
      number,
      { u_name: string; u_logo: string | null; u_country: number; u_state: number }
    > = {};
    if (uniIds.length) {
      const { data: unis } = await supabase
        .from("universities")
        .select("u_id,u_name,u_logo,u_country,u_state,i18n")
        .in("u_id", uniIds);
      for (const u of unis || []) {
        uniMap[u.u_id] = {
          ...u,
          u_name: getLocalized(u, "u_name", locale),
        };
      }
    }

    let list = (data || []).map((c) => {
      const uni = uniMap[c.c_uni];
      return {
        id: c.c_id,
        url_code: c.c_url_code || String(c.c_id),
        name: getLocalized(c, "c_name", locale) || "",
        uni_name: uni?.u_name || "",
        uni_logo: uni?.u_logo
          ? `${CDN}/images/university/logo/${uni.u_logo}`
          : "",
        uni_country: uni?.u_country,
        uni_state: uni?.u_state,
        duration: formatDuration(c.c_duration || 0),
        intake: formatIntake(c.c_intake || ""),
        ttl_fees: formatMoney(c.c_amount || 0),
        deals: "0",
        scholarship: "?",
        ttl_amt: formatMoney(c.c_amount || 0),
        c_amount: c.c_amount || 0,
        c_duration_months: c.c_duration || 0,
        c_intake_raw: c.c_intake || "",
      };
    });

    if (feeFilters.length > 0) {
      list = list.filter((c) =>
        feeFilters.some((r) => c.c_amount >= r.min && c.c_amount <= r.max),
      );
    }

    if (durationYears.length > 0) {
      list = list.filter((c) => {
        const yearVal = Math.round(c.c_duration_months / 12);
        return durationYears.map(Number).includes(yearVal);
      });
    }

    if (months.length > 0) {
      list = list.filter((c) => {
        const intakeMonths = c.c_intake_raw
          .split(",")
          .map((m: string) => m.trim());
        return months.some((m) => intakeMonths.includes(m));
      });
    }

    if (country.length > 0) {
      list = list.filter((c) => country.includes(String(c.uni_country)));
    }
    if (state.length > 0) {
      list = list.filter((c) => state.includes(String(c.uni_state)));
    }

    const clean = list.map(
      ({
        c_amount,
        c_duration_months,
        c_intake_raw,
        uni_country,
        uni_state,
        ...rest
      }) => rest,
    );

    return NextResponse.json({ status: true, list: clean });
  } catch (e) {
    console.error("Courses API error:", e);
    return NextResponse.json({ status: false, list: [] }, { status: 500 });
  }
}
