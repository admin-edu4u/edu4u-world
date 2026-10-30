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

function revalidateCareersAdmin() {
  revalidatePath("/admin/job");
  revalidatePath("/careers");
  revalidatePath("/");
}

async function setJobStatus(id: number, status: number) {
  await requireAdmin();
  const supabase = createServiceClient();
  const { error } = await supabase
    .from("careers")
    .update({ c_status: status, updated_at: new Date().toISOString() })
    .eq("c_id", id);
  if (error) throw new Error(error.message);
  revalidateCareersAdmin();
}

export async function activateJob(id: number) {
  await setJobStatus(id, STATUS.ACTIVE);
}

export async function deactivateJob(id: number) {
  await setJobStatus(id, STATUS.INACTIVE);
}

export async function archiveJob(id: number) {
  await setJobStatus(id, STATUS.ARCHIVED);
}

export async function restoreJob(id: number) {
  await setJobStatus(id, STATUS.ACTIVE);
}

export async function deleteJob(id: number) {
  await deleteById("careers", "c_id", id, "/admin/job");
  revalidatePath("/careers");
}

export async function saveJob(formData: FormData) {
  await requireAdmin();
  const supabase = createServiceClient();
  const idRaw = String(formData.get("c_id") || "");
  const row: Record<string, unknown> = {
    c_title: String(formData.get("c_title") || ""),
    c_state: String(formData.get("c_state") || ""),
    c_type: Number(formData.get("c_type") || 1),
    c_salary_min: Number(formData.get("c_salary_min") || 0),
    c_salary_max: Number(formData.get("c_salary_max") || 0),
    c_category: Number(formData.get("c_category") || 0),
    c_url: String(formData.get("c_url") || ""),
    c_status: Number(formData.get("c_status") || STATUS.ACTIVE),
    updated_at: new Date().toISOString(),
  };
  row.i18n = parseI18nForm(formData, CMS_I18N_FIELDS.job);
  if (idRaw) {
    const { error } = await supabase.from("careers").update(row).eq("c_id", Number(idRaw));
    if (error) throw new Error(error.message);
  } else {
    const id = await nextSerialId(supabase, "careers", "c_id");
    const { error } = await supabase.from("careers").insert({ ...row, c_id: id, created_at: new Date().toISOString() });
    if (error) throw new Error(error.message);
  }
  revalidateCareersAdmin();
  redirect("/admin/job");
}

async function setCategoryStatus(id: number, status: number) {
  await requireAdmin();
  const supabase = createServiceClient();
  const { error } = await supabase
    .from("career_categories")
    .update({ cc_status: status, updated_at: new Date().toISOString() })
    .eq("cc_id", id);
  if (error) throw new Error(error.message);
  revalidateCareersAdmin();
}

export async function activateCareerCategory(id: number) {
  await setCategoryStatus(id, STATUS.ACTIVE);
}

export async function deactivateCareerCategory(id: number) {
  await setCategoryStatus(id, STATUS.INACTIVE);
}

export async function archiveCareerCategory(id: number) {
  await setCategoryStatus(id, STATUS.ARCHIVED);
}

export async function restoreCareerCategory(id: number) {
  await setCategoryStatus(id, STATUS.ACTIVE);
}

export async function deleteCareerCategory(id: number) {
  await deleteById("career_categories", "cc_id", id, "/admin/job");
  revalidatePath("/careers");
}

/** Save category from the careers modal (no redirect). */
export async function saveCareerCategory(formData: FormData) {
  await requireAdmin();
  const supabase = createServiceClient();
  const idRaw = String(formData.get("cc_id") || "");
  const row: Record<string, unknown> = {
    cc_title: String(formData.get("cc_title") || "").trim(),
    cc_status: Number(formData.get("cc_status") || STATUS.ACTIVE),
    updated_at: new Date().toISOString(),
  };
  if (!row.cc_title) throw new Error("Category title is required.");
  row.i18n = parseI18nForm(formData, CMS_I18N_FIELDS.jobCat);

  if (idRaw) {
    const { error } = await supabase.from("career_categories").update(row).eq("cc_id", Number(idRaw));
    if (error) throw new Error(error.message);
  } else {
    const id = await nextSerialId(supabase, "career_categories", "cc_id");
    const { error } = await supabase.from("career_categories").insert({
      ...row,
      cc_id: id,
      created_at: new Date().toISOString(),
    });
    if (error) throw new Error(error.message);
  }

  revalidateCareersAdmin();
}

export async function setCareersPageHidden(hidden: boolean) {
  await requireAdmin();
  const supabase = createServiceClient();
  const { error } = await supabase.from("site_settings").upsert(
    {
      id: 1,
      careers_hidden: hidden,
      updated_at: new Date().toISOString(),
    },
    { onConflict: "id" },
  );
  if (error) throw new Error(error.message);
  revalidateCareersAdmin();
}
