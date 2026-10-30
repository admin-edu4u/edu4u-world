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

async function setCharacterStatus(id: number, status: number) {
  await requireAdmin();
  const supabase = createServiceClient();
  const { error } = await supabase.from("characters").update({ c_status: status, updated_at: new Date().toISOString() }).eq("c_id", id);
  if (error) throw new Error(error.message);
  revalidatePath("/admin/characters");
}

export async function activateCharacter(id: number) {
  await setCharacterStatus(id, STATUS.ACTIVE);
}

export async function deactivateCharacter(id: number) {
  await setCharacterStatus(id, STATUS.INACTIVE);
}

export async function archiveCharacter(id: number) {
  await setCharacterStatus(id, STATUS.ARCHIVED);
}

export async function restoreCharacter(id: number) {
  await setCharacterStatus(id, STATUS.ACTIVE);
}

export async function deleteCharacter(id: number) {
  await deleteById("characters", "c_id", id, "/admin/characters");
}

export async function saveCharacter(formData: FormData) {
  await requireAdmin();
  const supabase = createServiceClient();
  const idRaw = String(formData.get("c_id") || "");
  const male = await resolveImageUpdate(formData, "c_img_male", "characters");
  const female = await resolveImageUpdate(formData, "c_img_female", "characters");
  const row: Record<string, unknown> = {
    c_name: String(formData.get("c_name") || ""),
    c_aver_salary: String(formData.get("c_aver_salary") || ""),
    c_desc: String(formData.get("c_desc") || ""),
    c_stream: Number(formData.get("c_stream") || 0),
    c_status: Number(formData.get("c_status") || STATUS.ACTIVE),
    updated_at: new Date().toISOString(),
  };
  if (male !== undefined) row.c_img_male = male;
  if (female !== undefined) row.c_img_female = female;
  row.i18n = parseI18nForm(formData, CMS_I18N_FIELDS.character);
  if (idRaw) {
    const { error } = await supabase.from("characters").update(row).eq("c_id", Number(idRaw));
    if (error) throw new Error(error.message);
  } else {
    const id = await nextSerialId(supabase, "characters", "c_id");
    const { error } = await supabase.from("characters").insert({
      ...row, c_id: id, created_at: new Date().toISOString(),
    });
    if (error) throw new Error(error.message);
  }
  revalidatePath("/admin/characters");
  redirect("/admin/characters");
}
