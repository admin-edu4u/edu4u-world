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

async function setStatus(id: number, status: number) {
  await requireAdmin();
  const supabase = createServiceClient();
  const { error } = await supabase
    .from("personalities")
    .update({ p_status: status, updated_at: new Date().toISOString() })
    .eq("p_id", id);
  if (error) throw new Error(error.message);
  revalidatePath("/admin/personality");
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
  await deleteById("personalities", "p_id", id, "/admin/personality");
}

export async function saveItem(formData: FormData) {
  await requireAdmin();
  const supabase = createServiceClient();
  const idRaw = String(formData.get("p_id") || "");
  const row: Record<string, unknown> = {
    p_name: String(formData.get("p_name") || ""),
    p_character: joinCsvIds(formData, "p_character"),
    p_type: String(formData.get("p_type") || ""),
    p_status: Number(formData.get("p_status") || STATUS.ACTIVE),
    updated_at: new Date().toISOString(),
  };
  row.i18n = parseI18nForm(formData, CMS_I18N_FIELDS.personality);

  if (idRaw) {
    const { error } = await supabase.from("personalities").update(row).eq("p_id", Number(idRaw));
    if (error) throw new Error(error.message);
  } else {
    const id = await nextSerialId(supabase, "personalities", "p_id");
    const { error } = await supabase.from("personalities").insert({
      ...row,
      p_id: id,
      created_at: new Date().toISOString(),
    });
    if (error) throw new Error(error.message);
  }

  revalidatePath("/admin/personality");
  redirect("/admin/personality");
}
