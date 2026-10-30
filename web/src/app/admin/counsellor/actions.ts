"use server";

import { deleteById } from "@/lib/admin/actions";

export async function deleteCounsellor(id: number) {
  await deleteById("consult_counsellor", "cc_id", id, "/admin/counsellor");
}
