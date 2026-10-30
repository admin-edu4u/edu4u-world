import type { Locale } from "./config";

export type CmsI18n = {
  ch?: Record<string, string>;
  bm?: Record<string, string>;
};

export type CmsRow = Record<string, unknown> & {
  i18n?: CmsI18n | null;
};

function asRecord(value: unknown): Record<string, string> {
  if (!value || typeof value !== "object" || Array.isArray(value)) return {};
  const out: Record<string, string> = {};
  for (const [key, item] of Object.entries(value as Record<string, unknown>)) {
    if (typeof item === "string") out[key] = item;
  }
  return out;
}

export function parseCmsI18n(value: unknown): CmsI18n {
  if (!value || typeof value !== "object" || Array.isArray(value)) return {};
  const raw = value as Record<string, unknown>;
  return {
    ch: asRecord(raw.ch),
    bm: asRecord(raw.bm),
  };
}

function nonEmpty(value: unknown): string | null {
  if (typeof value !== "string") return null;
  const trimmed = value.trim();
  return trimmed ? trimmed : null;
}

/** Resolve a CMS field for the active locale, falling back to English. */
export function getLocalized(
  row: CmsRow | null | undefined,
  field: string,
  locale: Locale,
): string {
  if (!row) return "";
  const english = row[field] == null ? "" : String(row[field]);
  if (locale === "en") return english;
  const i18n = parseCmsI18n(row.i18n);
  const localized = nonEmpty(i18n[locale]?.[field]);
  return localized ?? english;
}

export function pickLocalizedFields<T extends CmsRow>(
  row: T,
  fields: readonly string[],
  locale: Locale,
): T {
  const next = { ...row };
  for (const field of fields) {
    (next as Record<string, unknown>)[field] = getLocalized(row, field, locale);
  }
  return next;
}

/** Read `field__ch` / `field__bm` from a form and build the i18n jsonb payload. */
export function parseI18nForm(formData: FormData, fields: readonly string[]): CmsI18n {
  const ch: Record<string, string> = {};
  const bm: Record<string, string> = {};

  for (const field of fields) {
    const chValue = nonEmpty(formData.get(`${field}__ch`));
    const bmValue = nonEmpty(formData.get(`${field}__bm`));
    if (chValue) ch[field] = chValue;
    if (bmValue) bm[field] = bmValue;
  }

  const out: CmsI18n = {};
  if (Object.keys(ch).length) out.ch = ch;
  if (Object.keys(bm).length) out.bm = bm;
  return out;
}

export function i18nFieldValue(
  row: CmsRow | null | undefined,
  field: string,
  locale: "ch" | "bm",
): string {
  if (!row) return "";
  return parseCmsI18n(row.i18n)[locale]?.[field] || "";
}
