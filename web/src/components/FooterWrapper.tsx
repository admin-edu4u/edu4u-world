"use client";

import { usePathname } from "next/navigation";
import { SiteFooter } from "./SiteFooter";

/** Pages that use a full-bleed dark results layout without the site footer. */
function hideFooter(pathname: string) {
  return pathname === "/characters";
}

export function FooterWrapper() {
  const pathname = usePathname();
  if (hideFooter(pathname)) return null;
  return <SiteFooter />;
}
