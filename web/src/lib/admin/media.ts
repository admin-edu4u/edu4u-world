const BUCKET = "admin-uploads";

export function publicUploadUrl(folder: string, filename: string): string {
  const base = process.env.NEXT_PUBLIC_SUPABASE_URL;
  return `${base}/storage/v1/object/public/${BUCKET}/${folder}/${filename}`;
}

/** Prefer CDN path used by the public site when filename-only is stored in DB. */
export function mediaUrl(
  folder: string,
  filename: string | null | undefined,
  cdn = "https://edu4u.world",
) {
  if (!filename) return "";
  if (filename.startsWith("http://") || filename.startsWith("https://")) return filename;
  return `${cdn}/images/${folder}/${filename}`;
}
