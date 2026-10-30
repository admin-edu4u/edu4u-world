"use client";

import { usePathname } from "next/navigation";
import { SiteHeader } from "./SiteHeader";

/** Routes whose top area is a dark background, so the transparent header needs light chrome. */
function isDarkOverlayHero(pathname: string) {
  return (
    pathname === "/characters" ||
    pathname.startsWith("/university/") ||
    pathname.startsWith("/k12-details/")
  );
}

/**
 * Renders SiteHeader for every page except the home page ("/"),
 * which renders its own header directly in page.tsx with overlayHero.
 */
export function HeaderWrapper() {
  const pathname = usePathname();
  if (pathname === "/") return null;
  return <SiteHeader overlayHero={isDarkOverlayHero(pathname)} />;
}
