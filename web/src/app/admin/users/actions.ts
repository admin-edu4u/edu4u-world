"use server";

import { revalidatePath } from "next/cache";
import { requireAdmin } from "@/lib/admin/auth";
import { getAdminSession } from "@/lib/admin/session";
import { createServiceClient } from "@/lib/supabase/server";

const USER_RELATED_TABLES: { table: string; column: string }[] = [
  { table: "results", column: "r_user_id" },
  { table: "achievements", column: "a_user_id" },
  { table: "academics", column: "a_user_id" },
  { table: "families", column: "f_user_id" },
  { table: "university_preferences", column: "up_user_id" },
  { table: "user_documents", column: "ud_user_id" },
];

export async function deleteUser(id: number) {
  await requireAdmin();
  const session = await getAdminSession();
  if (session?.userId === id) {
    throw new Error("You cannot delete your own account while signed in.");
  }

  const supabase = createServiceClient();
  const { data: user, error: loadError } = await supabase
    .from("app_users")
    .select("id,isadmin")
    .eq("id", id)
    .maybeSingle();

  if (loadError) throw new Error(loadError.message);
  if (!user) throw new Error("User not found.");

  if (Number(user.isadmin) === 1) {
    throw new Error("Admin accounts cannot be deleted from the users list.");
  }

  for (const { table, column } of USER_RELATED_TABLES) {
    const { error } = await supabase.from(table).delete().eq(column, id);
    if (error) throw new Error(`Could not remove related ${table} data: ${error.message}`);
  }

  const { error: deleteError } = await supabase.from("app_users").delete().eq("id", id);
  if (deleteError) throw new Error(deleteError.message);

  revalidatePath("/admin/users");
  revalidatePath(`/admin/users/${id}`);
}