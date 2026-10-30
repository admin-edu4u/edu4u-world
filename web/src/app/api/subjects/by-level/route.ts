import { NextRequest, NextResponse } from "next/server";
import { createPublicClient, createServiceClient } from "@/lib/supabase/server";

export type SubjectPickerGrade = { sg_id: number; sg_grade: string };
export type SubjectPickerItem = { id: number; name: string };
export type SubjectPickerGroup = { name: string; data: SubjectPickerItem[] };

export async function GET(req: NextRequest) {
  const level = Number(new URL(req.url).searchParams.get("level"));
  if (!Number.isFinite(level) || level <= 0) {
    return NextResponse.json({ status: false, msg: "Invalid level" });
  }

  const supabase = createPublicClient();

  const { data: subjects, error: subErr } = await supabase
    .from("subjects")
    .select("s_id,s_name,s_is_preselect,s_subject_cat_id")
    .eq("s_status", 1)
    .eq("s_level_id", level)
    .order("s_name");

  const service = createServiceClient();
  const { data: grades, error: gradeErr } = await service
    .from("subject_grades")
    .select("sg_id,sg_grade")
    .eq("sg_status", 1)
    .eq("sg_s_id", level)
    .not("sg_grade", "is", null)
    .order("sg_id");

  if (subErr || !subjects?.length) {
    return NextResponse.json({
      status: false,
      list: [] as SubjectPickerGroup[],
      preSelected: [] as SubjectPickerItem[],
      grade: [] as SubjectPickerGrade[],
    });
  }

  const catIds = [...new Set(subjects.map((s) => s.s_subject_cat_id).filter(Boolean))];
  const { data: categories } = catIds.length
    ? await supabase.from("subject_categories").select("sc_id,sc_name").in("sc_id", catIds)
    : { data: [] as { sc_id: number; sc_name: string }[] };

  const catName = new Map((categories || []).map((c) => [c.sc_id, c.sc_name]));

  const preSelected: SubjectPickerItem[] = [];
  const grouped = new Map<string, SubjectPickerItem[]>();

  for (const s of subjects) {
    const item = { id: s.s_id, name: s.s_name };
    if (s.s_is_preselect === 1) {
      preSelected.push(item);
      continue;
    }
    const groupName = catName.get(s.s_subject_cat_id) || "Subjects";
    const list = grouped.get(groupName) || [];
    list.push(item);
    grouped.set(groupName, list);
  }

  const list: SubjectPickerGroup[] = [...grouped.entries()].map(([name, data]) => ({
    name,
    data,
  }));

  return NextResponse.json({
    status: true,
    list,
    preSelected,
    grade: gradeErr ? [] : (grades || []).map((g) => ({ sg_id: g.sg_id, sg_grade: g.sg_grade })),
  });
}
