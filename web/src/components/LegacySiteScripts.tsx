"use client";

import { useEffect } from "react";

const SCRIPTS = [
  "/assets/plugins/global/plugins.bundle.js",
  "/assets/js/crawler.js",
] as const;

function loadScript(src: string): Promise<void> {
  return new Promise((resolve, reject) => {
    const existing = document.querySelector<HTMLScriptElement>(`script[data-edu4u-legacy="${src}"]`);
    if (existing) {
      if (existing.dataset.loaded === "1") resolve();
      else existing.addEventListener("load", () => resolve(), { once: true });
      return;
    }
    const el = document.createElement("script");
    el.src = src;
    el.async = false;
    el.dataset.edu4uLegacy = src;
    el.onload = () => {
      el.dataset.loaded = "1";
      resolve();
    };
    el.onerror = () => reject(new Error(`Failed to load ${src}`));
    document.body.appendChild(el);
  });
}

/** Metronic plugins + crawler for the public site (jQuery/Bootstrap from plugins.bundle). */
export function LegacySiteScripts() {
  useEffect(() => {
    let cancelled = false;
    (async () => {
      try {
        for (const src of SCRIPTS) {
          if (cancelled) return;
          await loadScript(src);
        }
      } catch (e) {
        console.warn("[LegacySiteScripts]", e);
      }
    })();
    return () => {
      cancelled = true;
    };
  }, []);

  return null;
}
