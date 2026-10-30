"use client";

import { useEffect, useId, useRef, useState } from "react";
import { AdminRichTextEditor } from "./AdminRichTextEditor";

type Kind = "input" | "textarea" | "richtext";
type Lang = "en" | "ch" | "bm";

const LANGS: { id: Lang; label: string }[] = [
  { id: "en", label: "English" },
  { id: "ch", label: "中文" },
  { id: "bm", label: "BM" },
];

const DEBOUNCE_MS = 700;

function isBlankRichText(html: string): boolean {
  return !html.replace(/<[^>]*>/g, "").replace(/&nbsp;/g, " ").trim();
}

export function AdminLocalizedField({
  name,
  label,
  kind = "input",
  required,
  defaultValue = "",
  defaultValueCh = "",
  defaultValueBm = "",
  rows = 3,
  type = "text",
}: {
  name: string;
  label: string;
  /** Kept for call-site compatibility. Localized fields always span a full row. */
  span?: 3 | 4 | 6 | 8 | 12;
  kind?: Kind;
  required?: boolean;
  defaultValue?: string;
  defaultValueCh?: string;
  defaultValueBm?: string;
  rows?: number;
  type?: string;
}) {
  const baseId = useId();
  const [en, setEn] = useState(defaultValue || "");
  const [ch, setCh] = useState(defaultValueCh || "");
  const [bm, setBm] = useState(defaultValueBm || "");
  const [busy, setBusy] = useState(false);
  const [error, setError] = useState("");
  const requestId = useRef(0);
  const debounceRef = useRef<ReturnType<typeof setTimeout> | null>(null);

  const values: Record<Lang, string> = { en, ch, bm };
  const setters: Record<Lang, (value: string) => void> = {
    en: setEn,
    ch: setCh,
    bm: setBm,
  };

  useEffect(() => {
    return () => {
      if (debounceRef.current) clearTimeout(debounceRef.current);
    };
  }, []);

  function scheduleTranslate(source: Lang, text: string) {
    if (debounceRef.current) clearTimeout(debounceRef.current);
    const payload = kind === "richtext" ? text : text.trim();
    const empty = kind === "richtext" ? isBlankRichText(payload) : !payload.trim();
    if (empty) {
      setBusy(false);
      setError("");
      return;
    }

    setBusy(true);
    debounceRef.current = setTimeout(async () => {
      const id = ++requestId.current;
      setError("");
      try {
        const res = await fetch("/api/admin/translate", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({ text: payload, from: source }),
        });
        const data = (await res.json()) as Partial<Record<Lang, string>> & { error?: string };
        if (!res.ok) throw new Error(data.error || "Translation failed");
        if (id !== requestId.current) return;

        if (source !== "en" && typeof data.en === "string") setEn(data.en);
        if (source !== "ch" && typeof data.ch === "string") setCh(data.ch);
        if (source !== "bm" && typeof data.bm === "string") setBm(data.bm);
      } catch (err) {
        if (id !== requestId.current) return;
        setError(err instanceof Error ? err.message : "Translation failed");
      } finally {
        if (id === requestId.current) setBusy(false);
      }
    }, DEBOUNCE_MS);
  }

  function onChange(source: Lang, value: string) {
    setters[source](value);
    scheduleTranslate(source, value);
  }

  function renderControl(lang: Lang) {
    const id = `${baseId}-${lang}`;
    const fieldName = lang === "en" ? name : `${name}__${lang}`;
    const value = values[lang];

    if (kind === "richtext") {
      return (
        <AdminRichTextEditor
          id={id}
          name={fieldName}
          value={value}
          onChange={(next) => onChange(lang, next)}
          required={lang === "en" ? required : undefined}
        />
      );
    }

    const common = {
      id,
      name: fieldName,
      className: "form-control",
      value,
      onChange: (e: React.ChangeEvent<HTMLInputElement | HTMLTextAreaElement>) =>
        onChange(lang, e.target.value),
    };

    if (kind === "textarea") {
      return (
        <textarea
          {...common}
          rows={rows}
          required={lang === "en" ? required : undefined}
        />
      );
    }

    return (
      <input
        {...common}
        type={type}
        required={lang === "en" ? required : undefined}
      />
    );
  }

  return (
    <div className={`admin-field admin-field-12 admin-localized-field${kind === "richtext" ? " is-richtext" : ""}`}>
      <div className="admin-localized-head">
        <span className="form-label" id={`${baseId}-label`}>
          {label}
        </span>
        <span className={`admin-localized-status${busy ? " is-busy" : ""}${error ? " is-error" : ""}`} aria-live="polite">
          {busy ? "Translating…" : error ? "Translation failed" : "Auto-translates as you type"}
        </span>
      </div>
      {error ? <p className="admin-localized-error">{error}</p> : null}

      <div className="admin-localized-columns" role="group" aria-labelledby={`${baseId}-label`}>
        {LANGS.map((item) => (
          <div key={item.id} className="admin-localized-column">
            <label className="admin-localized-lang" htmlFor={`${baseId}-${item.id}`}>
              {item.label}
            </label>
            {renderControl(item.id)}
          </div>
        ))}
      </div>
    </div>
  );
}
