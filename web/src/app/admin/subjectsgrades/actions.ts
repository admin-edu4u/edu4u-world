"use server";

import { revalidatePath } from "next/cache";
import { redirect } from "next/navigation";
import { requireAdmin } from "@/lib/admin/auth";
import { createServiceClient } from "@/lib/supabase/server";
import { nextSerialId } from "@/lib/nextSerialId";
import { deleteById } from "@/lib/admin/actions";
import { STATUS } from "@/lib/admin/status";

async function setGradeStatus(id: number, status: number) {
  await requireAdmin();
  const supabase = createServiceClient();
  const { error } = await supabase
    .from("subject_grades")
    .update({ sg_status: status, updated_at: new Date().toISOString() })
    .eq("sg_id", id);
  if (error) throw new Error(error.message);
  revalidatePath("/admin/subjectsgrades");
}

export async function activateGrade(id: number) {
  await setGradeStatus(id, STATUS.ACTIVE);
}

export async function deactivateGrade(id: number) {
  await setGradeStatus(id, STATUS.INACTIVE);
}

export async function archiveGrade(id: number) {
  await setGradeStatus(id, STATUS.ARCHIVED);
}

export async function restoreGrade(id: number) {
  await setGradeStatus(id, STATUS.ACTIVE);
}

export async function deleteGrade(id: number) {
  await deleteById("subject_grades", "sg_id", id, "/admin/subjectsgrades");
}

export async function saveGrade(formData: FormData) {
  await requireAdmin();
  const supabase = createServiceClient();
  const idRaw = String(formData.get("sg_id") || "");
  const row = {
    sg_s_id: Number(formData.get("sg_s_id") || 0),
    sg_grade: String(formData.get("sg_grade") || ""),
    sg_status: Number(formData.get("sg_status") || STATUS.ACTIVE),
    updated_at: new Date().toISOString(),
  };
  if (idRaw) {
    const { error } = await supabase.from("subject_grades").update(row).eq("sg_id", Number(idRaw));
    if (error) throw new Error(error.message);
  } else {
    const id = await nextSerialId(supabase, "subject_grades", "sg_id");
    const { error } = await supabase
      .from("subject_grades")
      .insert({ ...row, sg_id: id, created_at: new Date().toISOString() });
    if (error) throw new Error(error.message);
  }
  revalidatePath("/admin/subjectsgrades");
  redirect("/admin/subjectsgrades");
}
