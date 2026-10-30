import { NextResponse } from "next/server";
import { requireAdmin } from "@/lib/admin/auth";

type AppLocale = "en" | "ch" | "bm";

type TranslateBody = {
  text?: string;
  from?: string;
  texts?: Record<string, string>;
};

const APP_LOCALES: AppLocale[] = ["en", "ch", "bm"];

const APP_TO_MYMEMORY: Record<AppLocale, string> = {
  en: "en",
  ch: "zh-CN",
  bm: "ms",
};

const CHUNK_LIMIT = 450;

function isAppLocale(value: unknown): value is AppLocale {
  return value === "en" || value === "ch" || value === "bm";
}

function chunkText(text: string): string[] {
  const trimmed = text.trim();
  if (!trimmed) return [];
  if (trimmed.length <= CHUNK_LIMIT) return [trimmed];

  const chunks: string[] = [];
  let remaining = trimmed;
  while (remaining.length > CHUNK_LIMIT) {
    const window = remaining.slice(0, CHUNK_LIMIT);
    const breakAt = Math.max(
      window.lastIndexOf("\n"),
      window.lastIndexOf(". "),
      window.lastIndexOf("。"),
      window.lastIndexOf("! "),
      window.lastIndexOf("? "),
      window.lastIndexOf(" "),
    );
    const splitAt = breakAt > 40 ? breakAt + 1 : CHUNK_LIMIT;
    chunks.push(remaining.slice(0, splitAt).trim());
    remaining = remaining.slice(splitAt).trim();
  }
  if (remaining) chunks.push(remaining);
  return chunks;
}

/** Free MyMemory Translation API (no API key required). */
async function translateChunk(text: string, from: AppLocale, to: AppLocale): Promise<string> {
  const params = new URLSearchParams({
    q: text,
    langpair: `${APP_TO_MYMEMORY[from]}|${APP_TO_MYMEMORY[to]}`,
  });
  const res = await fetch(`https://api.mymemory.translated.net/get?${params.toString()}`, {
    cache: "no-store",
  });
  if (!res.ok) {
    const detail = await res.text();
    throw new Error(`Translator failed (${res.status}): ${detail.slice(0, 200)}`);
  }

  const payload = (await res.json()) as {
    responseStatus?: number | string;
    responseData?: { translatedText?: string };
    responseDetails?: string;
  };

  const status = Number(payload.responseStatus || 0);
  const translated = String(payload.responseData?.translatedText || "").trim();
  if (status !== 200 || !translated) {
    throw new Error(payload.responseDetails || "Translation failed");
  }
  // MyMemory sometimes echoes quota/error messages in translatedText.
  if (/^QUERY LENGTH LIMIT EXCEEDED/i.test(translated) || /^MYMEMORY WARNING/i.test(translated)) {
    throw new Error(translated);
  }
  return translated;
}

async function translateOne(text: string, from: AppLocale, to: AppLocale): Promise<string> {
  if (from === to) return text;
  const parts = chunkText(text);
  if (!parts.length) return "";
  const translated: string[] = [];
  for (const part of parts) {
    translated.push(await translateChunk(part, from, to));
  }
  return translated.join(text.includes("\n") ? "\n" : " ").trim();
}

async function translateFrom(
  texts: string[],
  from: AppLocale,
): Promise<Partial<Record<AppLocale, string[]>>> {
  const targets = APP_LOCALES.filter((locale) => locale !== from);
  const out: Partial<Record<AppLocale, string[]>> = {};
  for (const locale of targets) out[locale] = [];

  for (const text of texts) {
    const results = await Promise.all(
      targets.map(async (locale) => {
        try {
          return await translateOne(text, from, locale);
        } catch {
          return "";
        }
      }),
    );
    targets.forEach((locale, index) => {
      out[locale]!.push(results[index] || "");
    });
    if (results.every((value) => !value)) {
      throw new Error("Translation service unavailable. Try again in a moment.");
    }
  }

  return out;
}

export async function POST(request: Request) {
  try {
    await requireAdmin();
  } catch {
    return NextResponse.json({ error: "Unauthorized" }, { status: 401 });
  }

  let body: TranslateBody;
  try {
    body = (await request.json()) as TranslateBody;
  } catch {
    return NextResponse.json({ error: "Invalid JSON" }, { status: 400 });
  }

  const from: AppLocale = isAppLocale(body.from) ? body.from : "en";

  try {
    if (body.texts && typeof body.texts === "object") {
      const entries = Object.entries(body.texts).filter(([, value]) => String(value || "").trim());
      if (!entries.length) {
        return NextResponse.json({ error: "No text to translate" }, { status: 400 });
      }
      const translated = await translateFrom(
        entries.map(([, value]) => String(value)),
        from,
      );
      const result: Partial<Record<AppLocale, Record<string, string>>> = {};
      for (const locale of APP_LOCALES) {
        if (locale === from) continue;
        result[locale] = {};
        entries.forEach(([field], index) => {
          const value = translated[locale]?.[index];
          if (value) result[locale]![field] = value;
        });
      }
      return NextResponse.json(result);
    }

    const text = String(body.text || "").trim();
    if (!text) {
      return NextResponse.json({ error: "No text to translate" }, { status: 400 });
    }
    const translated = await translateFrom([text], from);
    const result: Partial<Record<AppLocale, string>> = {};
    for (const locale of APP_LOCALES) {
      if (locale === from) continue;
      result[locale] = translated[locale]?.[0] || "";
    }
    return NextResponse.json(result);
  } catch (err) {
    return NextResponse.json(
      { error: err instanceof Error ? err.message : "Translation failed" },
      { status: 500 },
    );
  }
}
