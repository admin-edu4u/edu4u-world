export const locales = ["en", "ch", "bm"] as const;

export type Locale = (typeof locales)[number];

export const defaultLocale: Locale = "en";

export const localeCookieName = "edu4u_locale";

export const localeLabels: Record<Locale, string> = {
  en: "EN",
  ch: "中文",
  bm: "BM",
};

export function isLocale(value: string): value is Locale {
  return (locales as readonly string[]).includes(value);
}
