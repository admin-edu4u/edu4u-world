"use server";

import { redirect } from "next/navigation";
import {
  activateSubjectCategory,
  deactivateSubjectCategory,
  archiveSubjectCategory,
  restoreSubjectCategory,
  deleteSubjectCategory,
  saveSubjectCategory,
} from "../subjects/actions";

export async function activateItem(id: number) {
  await activateSubjectCategory(id);
}

export async function deactivateItem(id: number) {
  await deactivateSubjectCategory(id);
}

export async function archiveItem(id: number) {
  await archiveSubjectCategory(id);
}

export async function restoreItem(id: number) {
  await restoreSubjectCategory(id);
}

export async function deleteItem(id: number) {
  await deleteSubjectCategory(id);
}

export async function saveItem(formData: FormData) {
  await saveSubjectCategory(formData);
  redirect("/admin/subjects");
}
