"use server";

import { redirect } from "next/navigation";
import {
  activateCareerCategory,
  deactivateCareerCategory,
  archiveCareerCategory,
  restoreCareerCategory,
  deleteCareerCategory,
  saveCareerCategory,
} from "../job/actions";

export async function activateItem(id: number) {
  await activateCareerCategory(id);
}

export async function deactivateItem(id: number) {
  await deactivateCareerCategory(id);
}

export async function archiveItem(id: number) {
  await archiveCareerCategory(id);
}

export async function restoreItem(id: number) {
  await restoreCareerCategory(id);
}

export async function deleteItem(id: number) {
  await deleteCareerCategory(id);
}

export async function saveItem(formData: FormData) {
  await saveCareerCategory(formData);
  redirect("/admin/job");
}
