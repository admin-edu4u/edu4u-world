const SEARCHABLE_COLUMNS = [
  "email",
  "username",
  "fullname",
  "first_name",
  "last_name",
  "contact_no",
  "contact_code",
  "mlm_id",
] as const;

export function parseUserSearchQuery(
  value: string | string[] | undefined,
): string {
  const raw = Array.isArray(value) ? value[0] : value;
  return String(raw ?? "")
    .trim()
    .replace(/\s+/g, " ")
    .slice(0, 120);
}

/** PostgREST `.or()` filter matching name, email, phone, and member id fields. */
export function userSearchOrFilter(term: string): string | null {
  const t = parseUserSearchQuery(term);
  if (!t) return null;

  const inner = t.replace(/\\/g, "\\\\").replace(/"/g, '""').replace(/,/g, "");
  const pattern = `"%${inner}%"`;

  return SEARCHABLE_COLUMNS.map((column) => `${column}.ilike.${pattern}`).join(",");
}
