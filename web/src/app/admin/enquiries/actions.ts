"use server";

import { deleteById } from "@/lib/admin/actions";

export async function deleteEnquiry(id: number) {
  await deleteById("enquiries", "e_id", id, "/admin/enquiries");
}
