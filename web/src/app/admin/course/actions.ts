"use server";
import { CMS_I18N_FIELDS } from "@/lib/admin/cmsFields";
import { parseI18nForm } from "@/lib/i18n/cms";
import { revalidatePath } from "next/cache";
import { redirect } from "next/navigation";
import { requireAdmin } from "@/lib/admin/auth";
import { createServiceClient } from "@/lib/supabase/server";
import { nextSerialId } from "@/lib/nextSerialId";

import { joinCsvIds } from "@/lib/admin/constants";
import { deleteById } from "@/lib/admin/actions";
import { STATUS } from "@/lib/admin/status";

async function setCourseStatus(id: number, status: number) {
  await requireAdmin();
  const supabase = createServiceClient();
  const { error } = await supabase.from("courses").update({ c_status: status, updated_at: new Date().toISOString() }).eq("c_id", id);
  if (error) throw new Error(error.message);
  revalidatePath("/admin/course");
}

export async function activateCourse(id: number) { await setCourseStatus(id, STATUS.ACTIVE); }
export async function deactivateCourse(id: number) { await setCourseStatus(id, STATUS.INACTIVE); }
export async function archiveCourse(id: number) { await setCourseStatus(id, STATUS.ARCHIVED); }
export async function restoreCourse(id: number) { await setCourseStatus(id, STATUS.ACTIVE); }
export async function hideCourse(id: number) { await deactivateCourse(id); }
export async function deleteCourse(id: number) {
  await deleteById("courses", "c_id", id, "/admin/course");
}

export async function saveCourse(formData: FormData) {
  await requireAdmin();
  const supabase = createServiceClient();
  const idRaw = String(formData.get("c_id") || "");
  const name = String(formData.get("c_name") || "");
  const uni = Number(formData.get("c_uni") || 0);
  const row: Record<string, unknown> = {
    c_name: name,
    c_uni: uni,
    c_level: Number(formData.get("c_level") || 0),
    c_stream: Number(formData.get("c_stream") || 0),
    c_intake: joinCsvIds(formData, "c_intake"),
    c_duration: String(formData.get("c_duration") || ""),
    c_award: String(formData.get("c_award") || ""),
    c_has_internship: Number(formData.get("c_has_internship") || 0),
    c_internship_duration: String(formData.get("c_internship_duration") || ""),
    c_outline: String(formData.get("c_outline") || ""),
    c_is_malaysian: Number(formData.get("c_is_malaysian") || 2),
    c_amount: Number(formData.get("c_amount") || 0),
    c_amount_bumi: Number(formData.get("c_amount_bumi") || 0),
    c_amount_international: Number(formData.get("c_amount_international") || 0),
    c_is_bumiputra: Number(formData.get("c_is_bumiputra") || 0),
    c_main_cat: Number(formData.get("c_main_cat") || 0),
    c_sub_cat: Number(formData.get("c_sub_cat") || 0),
    c_english_required: String(formData.get("c_english_required") || ""),
    c_entry_req: String(formData.get("c_entry_req") || ""),
    c_core: String(formData.get("c_core") || ""),
    c_elective: String(formData.get("c_elective") || ""),
    c_other: String(formData.get("c_other") || ""),
    c_status: Number(formData.get("c_status") || STATUS.ACTIVE),
    updated_at: new Date().toISOString(),
  };
  row.i18n = parseI18nForm(formData, CMS_I18N_FIELDS.course);
  if (idRaw) {
    const { error } = await supabase.from("courses").update(row).eq("c_id", Number(idRaw));
    if (error) throw new Error(error.message);
  } else {
    const id = await nextSerialId(supabase, "courses", "c_id");
    const url = (name + "_" + uni).replace(/[^a-zA-Z0-9_ -]/g, "").replace(/ /g, "_") + "_" + Date.now();
    const { error } = await supabase.from("courses").insert({
      ...row, c_id: id, c_url_code: url, created_at: new Date().toISOString(),
    });
    if (error) throw new Error(error.message);
  }
  revalidatePath("/admin/course");
  redirect("/admin/course");
}
