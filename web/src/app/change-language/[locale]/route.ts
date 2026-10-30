import { NextResponse } from "next/server";
import {
  defaultLocale,
  isLocale,
  localeCookieName,
} from "@/lib/i18n/config";

export async function GET(
  request: Request,
  context: { params: Promise<{ locale: string }> },
) {
  const { locale: raw } = await context.params;
  const locale = isLocale(raw) ? raw : defaultLocale;
  const url = new URL(request.url);
  const referer = request.headers.get("referer");
  const fallback = new URL("/", url.origin);
  let redirectTo = fallback;

  if (referer) {
    try {
      const ref = new URL(referer);
      if (ref.origin === url.origin && !ref.pathname.startsWith("/change-language")) {
        redirectTo = ref;
      }
    } catch {
      // ignore invalid referer
    }
  }

  const response = NextResponse.redirect(redirectTo);
  response.headers.set("Cache-Control", "no-store, no-cache, must-revalidate");
  response.cookies.set(localeCookieName, locale, {
    path: "/",
    maxAge: 60 * 60 * 24 * 365,
    sameSite: "lax",
  });
  return response;
}
