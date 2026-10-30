"use server";

import { deleteById } from "@/lib/admin/actions";

export async function deleteApplication(id: number) {
  await deleteById("course_application", "ca_id", id, "/admin/courseapplication");
}