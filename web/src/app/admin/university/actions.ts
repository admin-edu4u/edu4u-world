"use server";
import { CMS_I18N_FIELDS } from "@/lib/admin/cmsFields";
import { parseI18nForm } from "@/lib/i18n/cms";
import { revalidatePath } from "next/cache";
import { redirect } from "next/navigation";
import { requireAdmin } from "@/lib/admin/auth";
import { createServiceClient } from "@/lib/supabase/server";
import { nextSerialId } from "@/lib/nextSerialId";
import { resolveImageUpdate, resolveMultiImageUpdate, deleteById} from "@/lib/admin/actions";

import { STATUS } from "@/lib/admin/status";

async function setUniversityStatus(id: number, status: number) {
  await requireAdmin();
  const supabase = createServiceClient();
  const { error } = await supabase.from("universities").update({ u_status: status, updated_at: new Date().toISOString() }).eq("u_id", id);
  if (error) throw new Error(error.message);
  revalidatePath("/admin/university");
}

export async function activateUniversity(id: number) { await setUniversityStatus(id, STATUS.ACTIVE); }
export async function deactivateUniversity(id: number) { await setUniversityStatus(id, STATUS.INACTIVE); }
export async function archiveUniversity(id: number) { await setUniversityStatus(id, STATUS.ARCHIVED); }
export async function restoreUniversity(id: number) { await setUniversityStatus(id, STATUS.ACTIVE); }
export async function hideUniversity(id: number) { await deactivateUniversity(id); }
export async function deleteUniversity(id: number) {
  await deleteById("universities", "u_id", id, "/admin/university");
}

export async function saveUniversity(formData: FormData) {
  await requireAdmin();
  const supabase = createServiceClient();
  const idRaw = String(formData.get("u_id") || "");
  const logo = await resolveImageUpdate(formData, "logo", "university/logo");
  const images = await resolveMultiImageUpdate(formData, "img", "university/images");
  const hours1 = String(formData.get("hours1") || "");
  const hours2 = String(formData.get("hours2") || "");
  const title = String(formData.get("u_name") || "");
  const row: Record<string, unknown> = {
    u_name: title,
    u_has_rank: Number(formData.get("u_has_rank") || 0),
    u_ranking: String(formData.get("u_ranking") || ""),
    u_student: String(formData.get("u_student") || ""),
    u_staff: String(formData.get("u_staff") || ""),
    u_year: String(formData.get("u_year") || ""),
    u_desc: String(formData.get("u_desc") || ""),
    u_addr: String(formData.get("u_addr") || ""),
    u_state: Number(formData.get("u_state") || 0),
    u_country: Number(formData.get("u_country") || 0),
    u_hours: hours1 && hours2 ? `${hours1}~${hours2}` : String(formData.get("u_hours") || ""),
    u_has_branch: Number(formData.get("u_has_branch") || 0),
    u_type: Number(formData.get("u_type") || 0),
    u_status: Number(formData.get("u_status") || STATUS.ACTIVE),
    updated_at: new Date().toISOString(),
  };
  if (logo !== undefined) row.u_logo = logo;
  if (images !== undefined) row.u_img = images;
  row.i18n = parseI18nForm(formData, CMS_I18N_FIELDS.university);
  if (idRaw) {
    const { error } = await supabase.from("universities").update(row).eq("u_id", Number(idRaw));
    if (error) throw new Error(error.message);
  } else {
    const id = await nextSerialId(supabase, "universities", "u_id");
    const url = title.replace(/[^a-zA-Z0-9_ -]/g, "").replace(/ /g, "_") + "_" + Date.now();
    const { error } = await supabase.from("universities").insert({
      ...row, u_id: id, u_url_code: url, created_at: new Date().toISOString(),
    });
    if (error) throw new Error(error.message);
  }
  revalidatePath("/admin/university");
  redirect("/admin/university");
}
