import { cookies } from "next/headers";
import {
  ADMIN_COOKIE,
  decodeAdminSession,
  encodeAdminSession,
  type AdminSession,
} from "@/lib/admin/session-token";

export { ADMIN_COOKIE, decodeAdminSession, encodeAdminSession, type AdminSession };

export async function getAdminSession(): Promise<AdminSession | null> {
  const jar = await cookies();
  return decodeAdminSession(jar.get(ADMIN_COOKIE)?.value);
}

export async function setAdminSession(session: Omit<AdminSession, "exp">) {
  const jar = await cookies();
  jar.set(ADMIN_COOKIE, await encodeAdminSession(session), {
    httpOnly: true,
    sameSite: "lax",
    secure: process.env.NODE_ENV === "production",
    path: "/",
    maxAge: 60 * 60 * 12,
  });
}

export async function clearAdminSession() {
  const jar = await cookies();
  jar.set(ADMIN_COOKIE, "", {
    httpOnly: true,
    sameSite: "lax",
    secure: process.env.NODE_ENV === "production",
    path: "/",
    maxAge: 0,
  });
}
