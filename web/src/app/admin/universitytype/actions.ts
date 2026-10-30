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

async function setStatus(id: number, status: number) {
  await requireAdmin();
  const supabase = createServiceClient();
  const { error } = await supabase
    .from("university_type")
    .update({ ut_status: status, updated_at: new Date().toISOString() })
    .eq("ut_id", id);
  if (error) throw new Error(error.message);
  revalidatePath("/admin/universitytype");
}

export async function activateItem(id: number) {
  await setStatus(id, STATUS.ACTIVE);
}

export async function deactivateItem(id: number) {
  await setStatus(id, STATUS.INACTIVE);
}

export async function archiveItem(id: number) {
  await setStatus(id, STATUS.ARCHIVED);
}

export async function restoreItem(id: number) {
  await setStatus(id, STATUS.ACTIVE);
}

export async function deleteItem(id: number) {
  await deleteById("university_type", "ut_id", id, "/admin/universitytype");
}

export async function saveItem(formData: FormData) {
  await requireAdmin();
  const supabase = createServiceClient();
  const idRaw = String(formData.get("ut_id") || "");
  const row: Record<string, unknown> = {
    ut_name: String(formData.get("ut_name") || ""),
    ut_type: Number(formData.get("ut_type") || 1),
    ut_status: Number(formData.get("ut_status") || STATUS.ACTIVE),
    updated_at: new Date().toISOString(),
  };
  row.i18n = parseI18nForm(formData, CMS_I18N_FIELDS.universityType);

  if (idRaw) {
    const { error } = await supabase.from("university_type").update(row).eq("ut_id", Number(idRaw));
    if (error) throw new Error(error.message);
  } else {
    const id = await nextSerialId(supabase, "university_type", "ut_id");
    const { error } = await supabase.from("university_type").insert({
      ...row,
      ut_id: id,
      created_at: new Date().toISOString(),
    });
    if (error) throw new Error(error.message);
  }

  revalidatePath("/admin/universitytype");
  redirect("/admin/universitytype");
}
