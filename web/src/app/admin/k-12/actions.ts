"use server";
import { CMS_I18N_FIELDS } from "@/lib/admin/cmsFields";
import { joinCsvIds } from "@/lib/admin/constants";
import { parseI18nForm } from "@/lib/i18n/cms";
import { revalidatePath } from "next/cache";
import { redirect } from "next/navigation";
import { requireAdmin } from "@/lib/admin/auth";
import { createServiceClient } from "@/lib/supabase/server";
import { nextSerialId } from "@/lib/nextSerialId";
import { resolveImageUpdate, resolveMultiImageUpdate, deleteById } from "@/lib/admin/actions";

import { STATUS } from "@/lib/admin/status";

async function setK12Status(id: number, status: number) {
  await requireAdmin();
  const supabase = createServiceClient();
  const { error } = await supabase.from("international_school").update({ int_status: status, updated_at: new Date().toISOString() }).eq("int_id", id);
  if (error) throw new Error(error.message);
  revalidatePath("/admin/k-12");
}

export async function activateK12(id: number) {
  await setK12Status(id, STATUS.ACTIVE);
}

export async function deactivateK12(id: number) {
  await setK12Status(id, STATUS.INACTIVE);
}

export async function archiveK12(id: number) {
  await setK12Status(id, STATUS.ARCHIVED);
}

export async function restoreK12(id: number) {
  await setK12Status(id, STATUS.ACTIVE);
}

export async function deleteK12(id: number) {
  await deleteById("international_school", "int_id", id, "/admin/k-12");
}

export async function saveK12(formData: FormData) {
  await requireAdmin();
  const supabase = createServiceClient();
  const idRaw = String(formData.get("int_id") || "");
  const logo = await resolveImageUpdate(formData, "logo", "internationalschool/logo");
  const images = await resolveMultiImageUpdate(formData, "img", "internationalschool/images");
  const name = String(formData.get("int_name") || "");
  const hours1 = String(formData.get("hours1") || "");
  const hours2 = String(formData.get("hours2") || "");
  const row: Record<string, unknown> = {
    int_name: name,
    int_level: joinCsvIds(formData, "int_level"),
    int_student: String(formData.get("int_student") || ""),
    int_staff: String(formData.get("int_staff") || ""),
    int_year: String(formData.get("int_year") || ""),
    int_addr: String(formData.get("int_addr") || ""),
    int_state: Number(formData.get("int_state") || 0),
    int_country: Number(formData.get("int_country") || 0),
    int_has_branch: Number(formData.get("int_has_branch") || 0),
    int_hours: hours1 && hours2 ? `${hours1}~${hours2}` : String(formData.get("int_hours") || ""),
    int_desc: String(formData.get("int_desc") || ""),
    int_status: Number(formData.get("int_status") || STATUS.ACTIVE),
    updated_at: new Date().toISOString(),
  };
  if (logo !== undefined) row.int_logo = logo;
  if (images !== undefined) row.int_img = images;
  row.i18n = parseI18nForm(formData, CMS_I18N_FIELDS.k12);
  if (idRaw) {
    const { error } = await supabase.from("international_school").update(row).eq("int_id", Number(idRaw));
    if (error) throw new Error(error.message);
  } else {
    const id = await nextSerialId(supabase, "international_school", "int_id");
    const url = name.replace(/[^a-zA-Z0-9_ -]/g, "").replace(/ /g, "_") + "_" + Date.now();
    const { error } = await supabase.from("international_school").insert({
      ...row, int_id: id, int_url_code: url, created_at: new Date().toISOString(),
    });
    if (error) throw new Error(error.message);
  }
  revalidatePath("/admin/k-12");
  redirect("/admin/k-12");
}
