"use server";
import { CMS_I18N_FIELDS } from "@/lib/admin/cmsFields";
import { parseI18nForm } from "@/lib/i18n/cms";
import { revalidatePath } from "next/cache";
import { redirect } from "next/navigation";
import { requireAdmin } from "@/lib/admin/auth";
import { createServiceClient } from "@/lib/supabase/server";
import { nextSerialId } from "@/lib/nextSerialId";
import { resolveImageUpdate, deleteById} from "@/lib/admin/actions";

import { STATUS } from "@/lib/admin/status";

async function setScholarshipStatus(id: number, status: number) {
  await requireAdmin();
  const supabase = createServiceClient();
  const { error } = await supabase.from("scholarships").update({ s_status: status, updated_at: new Date().toISOString() }).eq("s_id", id);
  if (error) throw new Error(error.message);
  revalidatePath("/admin/scholarship");
}

export async function activateScholarship(id: number) {
  await setScholarshipStatus(id, STATUS.ACTIVE);
}

export async function deactivateScholarship(id: number) {
  await setScholarshipStatus(id, STATUS.INACTIVE);
}

export async function archiveScholarship(id: number) {
  await setScholarshipStatus(id, STATUS.ARCHIVED);
}

export async function restoreScholarship(id: number) {
  await setScholarshipStatus(id, STATUS.ACTIVE);
}

export async function deleteScholarship(id: number) {
  await deleteById("scholarships", "s_id", id, "/admin/scholarship");
}

export async function saveScholarship(formData: FormData) {
  await requireAdmin();
  const supabase = createServiceClient();
  const idRaw = String(formData.get("s_id") || "");
  const logo = await resolveImageUpdate(formData, "logo", "scholarships");
  const title = String(formData.get("s_title") || "");
  const levels = formData.getAll("s_level_id").map(String).filter(Boolean);
  const types = formData.getAll("s_type").map(String).filter(Boolean);
  const coverage = formData.getAll("s_coverage").map(String).filter(Boolean);
  const req = formData.getAll("s_requirement").map(String).filter(Boolean);
  const study = formData.getAll("s_study_field").map(String).filter(Boolean);
  const row: Record<string, unknown> = {
    s_title: title,
    s_is_corporate: Number(formData.get("s_is_corporate") || 0),
    s_c_name: String(formData.get("s_c_name") || ""),
    s_uni_id: Number(formData.get("s_uni_id") || 0),
    s_level_id: levels.length ? levels.join(",") : String(formData.get("s_level_id_text") || ""),
    s_has_interview: Number(formData.get("s_has_interview") || 0),
    s_study_field: study.length ? study.join(",") : String(formData.get("s_study_field_text") || ""),
    s_criteria: String(formData.get("s_criteria") || ""),
    s_details: String(formData.get("s_details") || ""),
    s_application: String(formData.get("s_application") || ""),
    s_type: types.length ? types.join(",") : String(formData.get("s_type_text") || ""),
    s_coverage: coverage.length ? coverage.join(",") : String(formData.get("s_coverage_text") || ""),
    s_requirement: req.length ? req.join(",") : String(formData.get("s_requirement_text") || ""),
    s_status: Number(formData.get("s_status") || STATUS.ACTIVE),
    updated_at: new Date().toISOString(),
  };
  if (logo !== undefined) row.s_c_logo = logo;
  row.i18n = parseI18nForm(formData, CMS_I18N_FIELDS.scholarship);
  if (idRaw) {
    const { error } = await supabase.from("scholarships").update(row).eq("s_id", Number(idRaw));
    if (error) throw new Error(error.message);
  } else {
    const id = await nextSerialId(supabase, "scholarships", "s_id");
    const url = title.replace(/[^a-zA-Z0-9_ -]/g, "").replace(/ /g, "_") + "_" + Date.now();
    const { error } = await supabase.from("scholarships").insert({
      ...row, s_id: id, s_url_code: url, created_at: new Date().toISOString(),
    });
    if (error) throw new Error(error.message);
  }
  revalidatePath("/admin/scholarship");
  redirect("/admin/scholarship");
}
