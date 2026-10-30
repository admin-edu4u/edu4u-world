"use server";

import { revalidatePath } from "next/cache";
import { redirect } from "next/navigation";
import { requireAdmin } from "@/lib/admin/auth";
import { createServiceClient } from "@/lib/supabase/server";
import { nextSerialId } from "@/lib/nextSerialId";
import { deleteById, resolveImageUpdate } from "@/lib/admin/actions";
import { STATUS } from "@/lib/admin/status";

function revalidatePopup() {
  revalidatePath("/admin/popup");
  revalidatePath("/");
}

async function setStatus(id: number, status: number) {
  await requireAdmin();
  const supabase = createServiceClient();
  const { error } = await supabase
    .from("pop_up")
    .update({ pu_status: status, updated_at: new Date().toISOString() })
    .eq("pu_id", id);
  if (error) throw new Error(error.message);
  revalidatePopup();
}

export async function activateItem(id: number) {
  await setStatus(id, STATUS.ACTIVE);
}

export async function deactivateItem(id: number) {
  await setStatus(id, STATUS.INACTIVE);
}

export async function archiveItem(id: number) {
  await setStatus(id, STATUS.ARCHIVED);
}

export async function restoreItem(id: number) {
  await setStatus(id, STATUS.ACTIVE);
}

export async function deleteItem(id: number) {
  await deleteById("pop_up", "pu_id", id, "/admin/popup");
  revalidatePath("/");
}

export async function movePopup(id: number, direction: "up" | "down") {
  await requireAdmin();
  const supabase = createServiceClient();
  const { data: rows, error } = await supabase
    .from("pop_up")
    .select("pu_id,pu_sort")
    .eq("pu_status", STATUS.ACTIVE)
    .order("pu_sort", { ascending: true })
    .order("pu_id", { ascending: true });
  if (error) throw new Error(error.message);
  const list = rows || [];
  const index = list.findIndex((r) => Number(r.pu_id) === id);
  if (index < 0) return;
  const swapWith = direction === "up" ? index - 1 : index + 1;
  if (swapWith < 0 || swapWith >= list.length) return;

  const a = list[index];
  const b = list[swapWith];
  const aSort = Number(a.pu_sort);
  const bSort = Number(b.pu_sort);
  const now = new Date().toISOString();

  // If sorts collide, rewrite a clean sequence after the swap.
  if (aSort === bSort) {
    const reordered = [...list];
    const [moved] = reordered.splice(index, 1);
    reordered.splice(swapWith, 0, moved);
    for (let i = 0; i < reordered.length; i += 1) {
      const { error: err } = await supabase
        .from("pop_up")
        .update({ pu_sort: i + 1, updated_at: now })
        .eq("pu_id", reordered[i].pu_id);
      if (err) throw new Error(err.message);
    }
  } else {
    const { error: errA } = await supabase
      .from("pop_up")
      .update({ pu_sort: bSort, updated_at: now })
      .eq("pu_id", a.pu_id);
    if (errA) throw new Error(errA.message);
    const { error: errB } = await supabase
      .from("pop_up")
      .update({ pu_sort: aSort, updated_at: now })
      .eq("pu_id", b.pu_id);
    if (errB) throw new Error(errB.message);
  }

  revalidatePopup();
}

export async function saveItem(formData: FormData) {
  await requireAdmin();
  const supabase = createServiceClient();
  const pu_id = String(formData.get("pu_id") || "");
  const pu_url = String(formData.get("pu_url") || "").trim();
  if (!pu_url) throw new Error("Please enter banner url");
  const pu_status = Number(formData.get("pu_status") || STATUS.ACTIVE);
  const img = await resolveImageUpdate(formData, "img", "home");
  const now = new Date().toISOString();

  if (pu_id) {
    const patch: Record<string, unknown> = {
      pu_url,
      pu_status,
      updated_at: now,
    };
    if (img !== undefined) {
      if (!img) throw new Error("Banner image is required");
      patch.pu_img = img;
    }
    const { error } = await supabase.from("pop_up").update(patch).eq("pu_id", Number(pu_id));
    if (error) throw new Error(error.message);
  } else {
    if (!img) throw new Error("Please upload banner");
    const id = await nextSerialId(supabase, "pop_up", "pu_id");
    const { data: maxRow } = await supabase
      .from("pop_up")
      .select("pu_sort")
      .order("pu_sort", { ascending: false })
      .limit(1)
      .maybeSingle();
    const nextSort = Number(maxRow?.pu_sort || 0) + 1;
    const { error } = await supabase.from("pop_up").insert({
      pu_id: id,
      pu_img: img,
      pu_url,
      pu_status,
      pu_sort: nextSort,
      created_at: now,
      updated_at: now,
    });
    if (error) throw new Error(error.message);
  }

  revalidatePopup();
  redirect("/admin/popup");
}
