"use server";

import { revalidatePath } from "next/cache";
import { redirect } from "next/navigation";
import { requireAdmin } from "@/lib/admin/auth";
import { createServiceClient } from "@/lib/supabase/server";
import { nextSerialId } from "@/lib/nextSerialId";
import { uploadAdminFile } from "@/lib/admin/upload";

export async function revalidateAdmin(path: string) {
  revalidatePath(path);
}

export async function deleteById(
  table: string,
  idCol: string,
  id: number | string,
  revalidate: string,
) {
  await requireAdmin();
  const supabase = createServiceClient();
  const { error } = await supabase.from(table).delete().eq(idCol, id);
  if (error) throw new Error(error.message);
  revalidatePath(revalidate);
}

export async function softHide(
  table: string,
  idCol: string,
  id: number | string,
  statusCol: string,
  statusVal: number,
  revalidate: string,
) {
  await requireAdmin();
  const supabase = createServiceClient();
  const { error } = await supabase.from(table).update({ [statusCol]: statusVal }).eq(idCol, id);
  if (error) throw new Error(error.message);
  revalidatePath(revalidate);
}

export async function formString(formData: FormData, key: string) {
  const v = formData.get(key);
  return v == null ? "" : String(v);
}

export async function optionalUpload(
  formData: FormData,
  field: string,
  folder: string,
): Promise<string | null> {
  const file = formData.get(field);
  if (!file || !(file instanceof File) || file.size === 0) return null;
  return uploadAdminFile(folder, file);
}

/**
 * Resolve a single-image field from AdminImageField.
 * - new upload → filename
 * - removed → ""
 * - unchanged → undefined
 */
export async function resolveImageUpdate(
  formData: FormData,
  field: string,
  folder: string,
): Promise<string | undefined> {
  const uploaded = await optionalUpload(formData, field, folder);
  if (uploaded) return uploaded;
  if (String(formData.get(`${field}_remove`) || "") === "1") return "";
  return undefined;
}

/** Resolve multi-image fields (keep list + newly uploaded files). */
export async function resolveMultiImageUpdate(
  formData: FormData,
  field: string,
  folder: string,
): Promise<string | undefined> {
  if (!formData.has(`${field}_keep`)) return undefined;
  const keep = String(formData.get(`${field}_keep`) || "")
    .split(",")
    .map((s) => s.trim())
    .filter(Boolean);
  const files = formData.getAll(field).filter((f): f is File => f instanceof File && f.size > 0);
  const uploaded: string[] = [];
  for (const file of files) {
    uploaded.push(await uploadAdminFile(folder, file));
  }
  return [...keep, ...uploaded].join(",");
}

export { nextSerialId, requireAdmin, createServiceClient, uploadAdminFile, redirect, revalidatePath };
