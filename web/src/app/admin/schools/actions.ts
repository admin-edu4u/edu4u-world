"use server";
import { CMS_I18N_FIELDS } from "@/lib/admin/cmsFields";
import { parseI18nForm } from "@/lib/i18n/cms";

import { revalidatePath } from "next/cache";
import { redirect } from "next/navigation";
import { requireAdmin } from "@/lib/admin/auth";
import { createServiceClient } from "@/lib/supabase/server";
import { nextSerialId } from "@/lib/nextSerialId";
import { deleteById } from "@/lib/admin/actions";
import { joinCsvIds } from "@/lib/admin/constants";
import { STATUS } from "@/lib/admin/status";

async function setStatus(id: number, status: number) {
  await requireAdmin();
  const supabase = createServiceClient();
  const { error } = await supabase
    .from("schools")
    .update({ s_status: status, updated_at: new Date().toISOString() })
    .eq("s_id", id);
  if (error) throw new Error(error.message);
  revalidatePath("/admin/schools");
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
  await deleteById("schools", "s_id", id, "/admin/schools");
}

export async function saveItem(formData: FormData) {
  await requireAdmin();
  const supabase = createServiceClient();
  const idRaw = String(formData.get("s_id") || "");
  const row: Record<string, unknown> = {
    s_name: String(formData.get("s_name") || ""),
    s_qualification: joinCsvIds(formData, "s_qualification"),
    s_status: Number(formData.get("s_status") || STATUS.ACTIVE),
    updated_at: new Date().toISOString(),
  };
  row.i18n = parseI18nForm(formData, CMS_I18N_FIELDS.school);

  if (idRaw) {
    const { error } = await supabase.from("schools").update(row).eq("s_id", Number(idRaw));
    if (error) throw new Error(error.message);
  } else {
    const id = await nextSerialId(supabase, "schools", "s_id");
    const { error } = await supabase.from("schools").insert({
      ...row,
      s_id: id,
      created_at: new Date().toISOString(),
    });
    if (error) throw new Error(error.message);
  }

  revalidatePath("/admin/schools");
  redirect("/admin/schools");
}
