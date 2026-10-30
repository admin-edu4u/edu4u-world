import { createPublicClient } from "@/lib/supabase/server";
import CompareCourseClient from "./CompareCourseClient";
import { getLocale } from "@/lib/i18n/locale";
import { pickLocalizedFields } from "@/lib/i18n/cms";
import { CMS_I18N_FIELDS } from "@/lib/admin/cmsFields";

export default async function CompareCoursePage() {
  const supabase = createPublicClient();
  const locale = await getLocale();

  const { data: courses } = await supabase
    .from("courses")
    .select("c_id,c_url_code,c_name,c_uni,c_level,c_duration,c_award,c_amount,i18n")
    .eq("c_status", 1)
    .order("c_name");

  const uniIds = [
    ...new Set((courses || []).map((c) => c.c_uni).filter(Boolean)),
  ];
  const uniMap: Record<number, { u_name: string; u_addr: string | null }> = {};
  if (uniIds.length) {
    const { data: unis } = await supabase
      .from("universities")
      .select("u_id,u_name,u_addr,i18n")
      .in("u_id", uniIds);
    for (const u of unis || []) {
      const localized = pickLocalizedFields(u, CMS_I18N_FIELDS.university, locale);
      uniMap[u.u_id] = { u_name: localized.u_name, u_addr: localized.u_addr };
    }
  }

  const flatCourses = (courses || []).map((c) => {
    const localized = pickLocalizedFields(c, CMS_I18N_FIELDS.course, locale);
    return {
      c_id: localized.c_id,
      c_url_code: localized.c_url_code,
      c_name: localized.c_name,
      c_uni: localized.c_uni,
      c_level: localized.c_level,
      c_duration: localized.c_duration,
      c_award: localized.c_award,
      c_amount: localized.c_amount,
      u_name: uniMap[c.c_uni]?.u_name ?? "",
      u_country: "Malaysia",
      u_state: "",
    };
  });

  return <CompareCourseClient allCourses={flatCourses} />;
}
