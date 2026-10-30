"use server";

import { revalidatePath } from "next/cache";
import { requireAdmin } from "@/lib/admin/auth";
import { createServiceClient } from "@/lib/supabase/server";
import { resolveMultiImageUpdate } from "@/lib/admin/actions";

export async function updateHomeBgAction(formData: FormData) {
  await requireAdmin();
  const supabase = createServiceClient();
  const h_img = await resolveMultiImageUpdate(formData, "images", "home");
  if (!h_img) throw new Error("Please provide background images");
  const { error } = await supabase
    .from("home")
    .upsert({ h_id: 1, h_img, updated_at: new Date().toISOString() });
  if (error) throw new Error(error.message);
  revalidatePath("/admin/home");
  revalidatePath("/");
}
