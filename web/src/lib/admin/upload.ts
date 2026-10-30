import { createServiceClient } from "@/lib/supabase/server";
import { publicUploadUrl, mediaUrl } from "@/lib/admin/media";

export { publicUploadUrl, mediaUrl };

const BUCKET = "admin-uploads";

export async function ensureAdminBucket() {
  const supabase = createServiceClient();
  const { data } = await supabase.storage.getBucket(BUCKET);
  if (!data) {
    await supabase.storage.createBucket(BUCKET, { public: true });
  }
}

/** Upload a file into folder (e.g. home, blogs, university/logo). Returns stored filename. */
export async function uploadAdminFile(
  folder: string,
  file: File,
  preferredName?: string,
): Promise<string> {
  await ensureAdminBucket();
  const supabase = createServiceClient();
  const ext = file.name.includes(".") ? file.name.split(".").pop() : "bin";
  const filename =
    preferredName ||
    `${Date.now()}_${Math.random().toString(36).slice(2, 8)}.${ext}`;
  const path = `${folder.replace(/^\/+|\/+$/g, "")}/${filename}`;
  const buf = Buffer.from(await file.arrayBuffer());
  const { error } = await supabase.storage.from(BUCKET).upload(path, buf, {
    contentType: file.type || "application/octet-stream",
    upsert: true,
  });
  if (error) throw new Error(error.message);
  return filename;
}
