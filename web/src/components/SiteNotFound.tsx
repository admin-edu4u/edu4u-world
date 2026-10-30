"use client";

import Link from "next/link";
import { useT } from "@/components/LocaleProvider";

export function SiteNotFound() {
  const t = useT();

  return (
    <main className="not-found-page">
      <p className="not-found-code" aria-hidden="true">
        404
      </p>
      <h1 className="not-found-title">{t("404Desc")}</h1>
      <Link href="/" className="not-found-home-btn mt-10">
        {t("backToHome")}
      </Link>
    </main>
  );
}
