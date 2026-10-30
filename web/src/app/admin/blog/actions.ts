"use server";
import { CMS_I18N_FIELDS } from "@/lib/admin/cmsFields";
import { parseI18nForm } from "@/lib/i18n/cms";

import { revalidatePath } from "next/cache";
import { redirect } from "next/navigation";
import { requireAdmin } from "@/lib/admin/auth";
import { createServiceClient } from "@/lib/supabase/server";
import { nextSerialId } from "@/lib/nextSerialId";
import { resolveImageUpdate, deleteById} from "@/lib/admin/actions";

import { STATUS } from "@/lib/admin/status";

async function setBlogStatus(id: number, status: number) {
  await requireAdmin();
  const supabase = createServiceClient();
  const { error } = await supabase.from("blogs").update({ b_status: status, updated_at: new Date().toISOString() }).eq("b_id", id);
  if (error) throw new Error(error.message);
  revalidatePath("/admin/blog");
}

export async function activateBlog(id: number) { await setBlogStatus(id, STATUS.ACTIVE); }
export async function deactivateBlog(id: number) { await setBlogStatus(id, STATUS.INACTIVE); }
export async function archiveBlog(id: number) { await setBlogStatus(id, STATUS.ARCHIVED); }
export async function restoreBlog(id: number) { await setBlogStatus(id, STATUS.ACTIVE); }
export async function deleteBlog(id: number) {
  await deleteById("blogs", "b_id", id, "/admin/blog");
}

export async function pinBlog(id: number, star: number) {
  await requireAdmin();
  const supabase = createServiceClient();
  const { error } = await supabase.from("blogs").update({ b_is_star: star }).eq("b_id", id);
  if (error) throw new Error(error.message);
  revalidatePath("/admin/blog");
}

export async function saveBlog(formData: FormData) {
  await requireAdmin();
  const supabase = createServiceClient();
  const idRaw = String(formData.get("b_id") || "");
  const uploaded = await resolveImageUpdate(formData, "img", "blogs");
  const row: Record<string, unknown> = {
    b_title: String(formData.get("b_title") || ""),
    b_cat_id: Number(formData.get("b_cat_id") || 0),
    b_content: String(formData.get("b_content") || ""),
    b_status: Number(formData.get("b_status") || STATUS.ACTIVE),
    b_url_code: String(formData.get("b_url_code") || "").trim() || String(Date.now()),
    updated_at: new Date().toISOString(),
  };
  if (uploaded !== undefined) row.b_img = uploaded;
  row.i18n = parseI18nForm(formData, CMS_I18N_FIELDS.blog);

  if (idRaw) {
    const { error } = await supabase.from("blogs").update(row).eq("b_id", Number(idRaw));
    if (error) throw new Error(error.message);
  } else {
    const id = await nextSerialId(supabase, "blogs", "b_id");
    const { error } = await supabase.from("blogs").insert({
      ...row,
      b_id: id,
      b_is_star: 0,
      created_at: new Date().toISOString(),
    });
    if (error) throw new Error(error.message);
  }
  revalidatePath("/admin/blog");
  redirect("/admin/blog");
}

function revalidateBlogAdmin() {
  revalidatePath("/admin/blog");
}

async function setBlogCategoryStatus(id: number, status: number) {
  await requireAdmin();
  const supabase = createServiceClient();
  const { error } = await supabase
    .from("blog_cats")
    .update({ bc_status: status, updated_at: new Date().toISOString() })
    .eq("bc_id", id);
  if (error) throw new Error(error.message);
  revalidateBlogAdmin();
}

export async function activateBlogCategory(id: number) {
  await setBlogCategoryStatus(id, STATUS.ACTIVE);
}

export async function deactivateBlogCategory(id: number) {
  await setBlogCategoryStatus(id, STATUS.INACTIVE);
}

export async function archiveBlogCategory(id: number) {
  await setBlogCategoryStatus(id, STATUS.ARCHIVED);
}

export async function restoreBlogCategory(id: number) {
  await setBlogCategoryStatus(id, STATUS.ACTIVE);
}

export async function deleteBlogCategory(id: number) {
  await deleteById("blog_cats", "bc_id", id, "/admin/blog");
}

/** Save blog category from the blog modal (no redirect). */
export async function saveBlogCategory(formData: FormData) {
  await requireAdmin();
  const supabase = createServiceClient();
  const idRaw = String(formData.get("bc_id") || "");
  const row: Record<string, unknown> = {
    bc_name: String(formData.get("bc_name") || "").trim(),
    bc_status: Number(formData.get("bc_status") || STATUS.ACTIVE),
    updated_at: new Date().toISOString(),
  };
  if (!row.bc_name) throw new Error("Category name is required.");
  row.i18n = parseI18nForm(formData, CMS_I18N_FIELDS.blogCat);

  if (idRaw) {
    const { error } = await supabase.from("blog_cats").update(row).eq("bc_id", Number(idRaw));
    if (error) throw new Error(error.message);
  } else {
    const id = await nextSerialId(supabase, "blog_cats", "bc_id");
    const { error } = await supabase.from("blog_cats").insert({
      ...row,
      bc_id: id,
      created_at: new Date().toISOString(),
    });
    if (error) throw new Error(error.message);
  }

  revalidateBlogAdmin();
}
