import type { Locale } from "./config";
import { defaultLocale } from "./config";

export type Dictionary = Record<string, string>;

const dictionaries: Record<Locale, () => Promise<Dictionary>> = {
  en: () => import("./dictionaries/en.json").then((m) => m.default as Dictionary),
  ch: () => import("./dictionaries/ch.json").then((m) => m.default as Dictionary),
  bm: () => import("./dictionaries/bm.json").then((m) => m.default as Dictionary),
};

export async function getDictionary(locale: Locale): Promise<Dictionary> {
  const load = dictionaries[locale] ?? dictionaries[defaultLocale];
  return load();
}

export function translate(dict: Dictionary, key: string, fallback?: string): string {
  return dict[key] ?? fallback ?? key;
}
