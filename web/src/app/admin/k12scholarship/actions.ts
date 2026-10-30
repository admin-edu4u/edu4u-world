"use server";
import { CMS_I18N_FIELDS } from "@/lib/admin/cmsFields";
import { parseI18nForm } from "@/lib/i18n/cms";
import { revalidatePath } from "next/cache";
import { redirect } from "next/navigation";
import { requireAdmin } from "@/lib/admin/auth";
import { createServiceClient } from "@/lib/supabase/server";
import { nextSerialId } from "@/lib/nextSerialId";
import { resolveImageUpdate, deleteById} from "@/lib/admin/actions";

import { joinCsvIds } from "@/lib/admin/constants";
import { STATUS } from "@/lib/admin/status";

async function setK12ScholarshipStatus(id: number, status: number) {
  await requireAdmin();
  const supabase = createServiceClient();
  const { error } = await supabase.from("int_scholarship").update({ s_status: status, updated_at: new Date().toISOString() }).eq("s_id", id);
  if (error) throw new Error(error.message);
  revalidatePath("/admin/k12scholarship");
}

export async function activateK12Scholarship(id: number) {
  await setK12ScholarshipStatus(id, STATUS.ACTIVE);
}

export async function deactivateK12Scholarship(id: number) {
  await setK12ScholarshipStatus(id, STATUS.INACTIVE);
}

export async function archiveK12Scholarship(id: number) {
  await setK12ScholarshipStatus(id, STATUS.ARCHIVED);
}

export async function restoreK12Scholarship(id: number) {
  await setK12ScholarshipStatus(id, STATUS.ACTIVE);
}

export async function deleteK12Scholarship(id: number) {
  await deleteById("int_scholarship", "s_id", id, "/admin/k12scholarship");
}

export async function saveK12Scholarship(formData: FormData) {
  await requireAdmin();
  const supabase = createServiceClient();
  const idRaw = String(formData.get("s_id") || "");
  const logo = await resolveImageUpdate(formData, "logo", "scholarships");
  const title = String(formData.get("s_title") || "");
  const row: Record<string, unknown> = {
    s_title: title,
    s_is_corporate: Number(formData.get("s_is_corporate") || 0),
    s_c_name: String(formData.get("s_c_name") || ""),
    s_int_id: Number(formData.get("s_int_id") || 0),
    s_has_interview: Number(formData.get("s_has_interview") || 0),
    s_criteria: String(formData.get("s_criteria") || ""),
    s_details: String(formData.get("s_details") || ""),
    s_application: String(formData.get("s_application") || ""),
    s_type: joinCsvIds(formData, "s_type"),
    s_coverage: joinCsvIds(formData, "s_coverage"),
    s_requirement: joinCsvIds(formData, "s_requirement"),
    s_status: Number(formData.get("s_status") || STATUS.ACTIVE),
    updated_at: new Date().toISOString(),
  };
  if (logo !== undefined) row.s_c_logo = logo;
  row.i18n = parseI18nForm(formData, CMS_I18N_FIELDS.k12Scholarship);
  if (idRaw) {
    const { error } = await supabase.from("int_scholarship").update(row).eq("s_id", Number(idRaw));
    if (error) throw new Error(error.message);
  } else {
    const id = await nextSerialId(supabase, "int_scholarship", "s_id");
    const url = title.replace(/[^a-zA-Z0-9_ -]/g, "").replace(/ /g, "_") + "_" + Date.now();
    const { error } = await supabase.from("int_scholarship").insert({
      ...row, s_id: id, s_url_code: url, created_at: new Date().toISOString(),
    });
    if (error) throw new Error(error.message);
  }
  revalidatePath("/admin/k12scholarship");
  redirect("/admin/k12scholarship");
}
