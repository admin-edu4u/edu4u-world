"use server";
import { CMS_I18N_FIELDS } from "@/lib/admin/cmsFields";
import { parseI18nForm } from "@/lib/i18n/cms";

import { revalidatePath } from "next/cache";
import { redirect } from "next/navigation";
import { requireAdmin } from "@/lib/admin/auth";
import { createServiceClient } from "@/lib/supabase/server";
import { nextSerialId } from "@/lib/nextSerialId";
import { deleteById } from "@/lib/admin/actions";
import { STATUS } from "@/lib/admin/status";

async function setSubjectStatus(id: number, status: number, levelId: number) {
  await requireAdmin();
  const supabase = createServiceClient();
  const { error } = await supabase
    .from("subjects")
    .update({ s_status: status, updated_at: new Date().toISOString() })
    .eq("s_id", id);
  if (error) throw new Error(error.message);
  revalidatePath("/admin/subjects");
  revalidatePath(`/admin/subjects/${levelId}`);
}

export async function activateSubject(id: number, levelId: number) {
  await setSubjectStatus(id, STATUS.ACTIVE, levelId);
}

export async function deactivateSubject(id: number, levelId: number) {
  await setSubjectStatus(id, STATUS.INACTIVE, levelId);
}

export async function archiveSubject(id: number, levelId: number) {
  await setSubjectStatus(id, STATUS.ARCHIVED, levelId);
}

export async function restoreSubject(id: number, levelId: number) {
  await setSubjectStatus(id, STATUS.ACTIVE, levelId);
}

export async function deleteSubject(id: number, levelId: number) {
  await deleteById("subjects", "s_id", id, `/admin/subjects/${levelId}`);
  revalidatePath("/admin/subjects");
}

export async function saveSubject(formData: FormData) {
  await requireAdmin();
  const supabase = createServiceClient();
  const levelId = Number(formData.get("s_level_id") || 0);
  const idRaw = String(formData.get("s_id") || "");
  const row: Record<string, unknown> = {
    s_level_id: levelId,
    s_subject_cat_id: Number(formData.get("s_subject_cat_id") || 0),
    s_name: String(formData.get("s_name") || ""),
    s_is_preselect: Number(formData.get("s_is_preselect") || 0),
    s_status: Number(formData.get("s_status") || STATUS.ACTIVE),
    updated_at: new Date().toISOString(),
  };
  row.i18n = parseI18nForm(formData, CMS_I18N_FIELDS.subject);

  if (idRaw) {
    const { error } = await supabase.from("subjects").update(row).eq("s_id", Number(idRaw));
    if (error) throw new Error(error.message);
  } else {
    const id = await nextSerialId(supabase, "subjects", "s_id");
    const { error } = await supabase.from("subjects").insert({
      ...row,
      s_id: id,
      created_at: new Date().toISOString(),
    });
    if (error) throw new Error(error.message);
  }

  revalidatePath("/admin/subjects");
  revalidatePath(`/admin/subjects/${levelId}`);
  redirect(`/admin/subjects/${levelId}`);
}

function revalidateSubjectsAdmin() {
  revalidatePath("/admin/subjects");
}

async function setSubjectCategoryStatus(id: number, status: number) {
  await requireAdmin();
  const supabase = createServiceClient();
  const { error } = await supabase
    .from("subject_categories")
    .update({ sc_status: status, updated_at: new Date().toISOString() })
    .eq("sc_id", id);
  if (error) throw new Error(error.message);
  revalidateSubjectsAdmin();
}

export async function activateSubjectCategory(id: number) {
  await setSubjectCategoryStatus(id, STATUS.ACTIVE);
}

export async function deactivateSubjectCategory(id: number) {
  await setSubjectCategoryStatus(id, STATUS.INACTIVE);
}

export async function archiveSubjectCategory(id: number) {
  await setSubjectCategoryStatus(id, STATUS.ARCHIVED);
}

export async function restoreSubjectCategory(id: number) {
  await setSubjectCategoryStatus(id, STATUS.ACTIVE);
}

export async function deleteSubjectCategory(id: number) {
  await deleteById("subject_categories", "sc_id", id, "/admin/subjects");
}

/** Save subject category from the subjects modal (no redirect). */
export async function saveSubjectCategory(formData: FormData) {
  await requireAdmin();
  const supabase = createServiceClient();
  const idRaw = String(formData.get("sc_id") || "");
  const row: Record<string, unknown> = {
    sc_name: String(formData.get("sc_name") || "").trim(),
    sc_status: Number(formData.get("sc_status") || STATUS.ACTIVE),
    updated_at: new Date().toISOString(),
  };
  if (!row.sc_name) throw new Error("Category name is required.");
  row.i18n = parseI18nForm(formData, CMS_I18N_FIELDS.subjectCategory);

  if (idRaw) {
    const { error } = await supabase
      .from("subject_categories")
      .update(row)
      .eq("sc_id", Number(idRaw));
    if (error) throw new Error(error.message);
  } else {
    const id = await nextSerialId(supabase, "subject_categories", "sc_id");
    const { error } = await supabase.from("subject_categories").insert({
      ...row,
      sc_id: id,
      created_at: new Date().toISOString(),
    });
    if (error) throw new Error(error.message);
  }

  revalidateSubjectsAdmin();
}
