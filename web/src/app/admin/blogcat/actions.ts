"use server";

import { redirect } from "next/navigation";
import {
  activateBlogCategory,
  deactivateBlogCategory,
  archiveBlogCategory,
  restoreBlogCategory,
  deleteBlogCategory,
  saveBlogCategory,
} from "../blog/actions";

export async function activateItem(id: number) {
  await activateBlogCategory(id);
}

export async function deactivateItem(id: number) {
  await deactivateBlogCategory(id);
}

export async function archiveItem(id: number) {
  await archiveBlogCategory(id);
}

export async function restoreItem(id: number) {
  await restoreBlogCategory(id);
}

export async function deleteItem(id: number) {
  await deleteBlogCategory(id);
}

export async function saveItem(formData: FormData) {
  await saveBlogCategory(formData);
  redirect("/admin/blog");
}
