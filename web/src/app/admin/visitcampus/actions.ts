"use server";

import { deleteById } from "@/lib/admin/actions";

export async function deleteVisit(id: number) {
  await deleteById("visit_campus", "vc_id", id, "/admin/visitcampus");
}
