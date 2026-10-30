"use server";
import { CMS_I18N_FIELDS } from "@/lib/admin/cmsFields";
import { parseI18nForm } from "@/lib/i18n/cms";

import { revalidatePath } from "next/cache";
import { redirect } from "next/navigation";
import { requireAdmin } from "@/lib/admin/auth";
import { createServiceClient } from "@/lib/supabase/server";
import { nextSerialId } from "@/lib/nextSerialId";
import { deleteById, resolveImageUpdate } from "@/lib/admin/actions";
import { STATUS } from "@/lib/admin/status";

async function setCategoryStatus(id: number, status: number) {
  await requireAdmin();
  const supabase = createServiceClient();
  const { error } = await supabase
    .from("categories")
    .update({ c_status: status, updated_at: new Date().toISOString() })
    .eq("c_id", id);
  if (error) throw new Error(error.message);
  revalidatePath("/admin/category");
}

async function setSubCatStatus(id: number, status: number) {
  await requireAdmin();
  const supabase = createServiceClient();
  const { error } = await supabase
    .from("sub_cats")
    .update({ sc_status: status, updated_at: new Date().toISOString() })
    .eq("sc_id", id);
  if (error) throw new Error(error.message);
  revalidatePath("/admin/category");
}

export async function activateCategory(id: number) {
  await setCategoryStatus(id, STATUS.ACTIVE);
}
export async function deactivateCategory(id: number) {
  await setCategoryStatus(id, STATUS.INACTIVE);
}
export async function archiveCategory(id: number) {
  await setCategoryStatus(id, STATUS.ARCHIVED);
}
export async function restoreCategory(id: number) {
  await setCategoryStatus(id, STATUS.ACTIVE);
}
export async function deleteCategory(id: number) {
  await requireAdmin();
  const supabase = createServiceClient();
  const { error: subError } = await supabase.from("sub_cats").delete().eq("sc_main", id);
  if (subError) throw new Error(subError.message);
  const { error } = await supabase.from("categories").delete().eq("c_id", id);
  if (error) throw new Error(error.message);
  revalidatePath("/admin/category");
}

export async function activateSubCat(id: number) {
  await setSubCatStatus(id, STATUS.ACTIVE);
}
export async function deactivateSubCat(id: number) {
  await setSubCatStatus(id, STATUS.INACTIVE);
}
export async function archiveSubCat(id: number) {
  await setSubCatStatus(id, STATUS.ARCHIVED);
}
export async function restoreSubCat(id: number) {
  await setSubCatStatus(id, STATUS.ACTIVE);
}
export async function deleteSubCat(id: number) {
  await deleteById("sub_cats", "sc_id", id, "/admin/category");
}

export async function saveCategory(formData: FormData) {
  await requireAdmin();
  const supabase = createServiceClient();
  const idRaw = String(formData.get("c_id") || "");
  const img = await resolveImageUpdate(formData, "c_img", "category");
  const row: Record<string, unknown> = {
    c_name: String(formData.get("c_name") || ""),
    c_status: Number(formData.get("c_status") || STATUS.ACTIVE),
    updated_at: new Date().toISOString(),
  };
  if (img !== undefined) row.c_img = img;
  row.i18n = parseI18nForm(formData, CMS_I18N_FIELDS.category);

  if (idRaw) {
    const { error } = await supabase.from("categories").update(row).eq("c_id", Number(idRaw));
    if (error) throw new Error(error.message);
  } else {
    const id = await nextSerialId(supabase, "categories", "c_id");
    const { error } = await supabase.from("categories").insert({
      ...row,
      c_id: id,
      c_img: img !== undefined ? img : null,
      created_at: new Date().toISOString(),
    });
    if (error) throw new Error(error.message);
  }

  revalidatePath("/admin/category");
  redirect("/admin/category");
}

export async function saveSubCat(formData: FormData) {
  await requireAdmin();
  const supabase = createServiceClient();
  const idRaw = String(formData.get("sc_id") || "");
  const row = {
    sc_name: String(formData.get("sc_name") || ""),
    sc_main: Number(formData.get("sc_main") || 0),
    sc_character: Number(formData.get("sc_character") || 0),
    sc_status: Number(formData.get("sc_status") || STATUS.ACTIVE),
    updated_at: new Date().toISOString(),
  };
  const subRow = { ...row, i18n: parseI18nForm(formData, CMS_I18N_FIELDS.subCat) };

  if (idRaw) {
    const { error } = await supabase.from("sub_cats").update(subRow).eq("sc_id", Number(idRaw));
    if (error) throw new Error(error.message);
  } else {
    const id = await nextSerialId(supabase, "sub_cats", "sc_id");
    const { error } = await supabase.from("sub_cats").insert({
      ...subRow,
      sc_id: id,
      created_at: new Date().toISOString(),
    });
    if (error) throw new Error(error.message);
  }

  revalidatePath("/admin/category");
  redirect("/admin/category");
}
