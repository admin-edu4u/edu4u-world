"use client";

import {
  createContext,
  useCallback,
  useContext,
  useMemo,
  type ReactNode,
} from "react";
import type { Locale } from "@/lib/i18n/config";
import type { Dictionary } from "@/lib/i18n/dictionaries";
import { translate } from "@/lib/i18n/dictionaries";

type SiteFlags = {
  careersHidden: boolean;
};

type LocaleContextValue = {
  locale: Locale;
  t: (key: string, fallback?: string) => string;
  flags: SiteFlags;
};

const LocaleContext = createContext<LocaleContextValue | null>(null);

export function LocaleProvider({
  locale,
  dictionary,
  flags,
  children,
}: {
  locale: Locale;
  dictionary: Dictionary;
  flags?: Partial<SiteFlags>;
  children: ReactNode;
}) {
  const t = useCallback(
    (key: string, fallback?: string) => translate(dictionary, key, fallback),
    [dictionary],
  );

  const resolvedFlags = useMemo<SiteFlags>(
    () => ({
      careersHidden: Boolean(flags?.careersHidden),
    }),
    [flags?.careersHidden],
  );

  const value = useMemo(
    () => ({ locale, t, flags: resolvedFlags }),
    [locale, t, resolvedFlags],
  );

  return (
    <LocaleContext.Provider value={value}>{children}</LocaleContext.Provider>
  );
}

export function useLocale() {
  const ctx = useContext(LocaleContext);
  if (!ctx) {
    throw new Error("useLocale must be used within LocaleProvider");
  }
  return ctx;
}

export function useT() {
  return useLocale().t;
}

export function useSiteFlags() {
  return useLocale().flags;
}
