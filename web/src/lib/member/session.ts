import { cookies } from "next/headers";
import {
  MEMBER_COOKIE,
  decodeMemberSession,
  encodeMemberSession,
  type MemberSession,
} from "@/lib/admin/session-token";

export { MEMBER_COOKIE, decodeMemberSession, encodeMemberSession, type MemberSession };

export async function getMemberSession(): Promise<MemberSession | null> {
  const jar = await cookies();
  return decodeMemberSession(jar.get(MEMBER_COOKIE)?.value);
}

export async function setMemberSession(session: Omit<MemberSession, "exp" | "isAdmin">) {
  const jar = await cookies();
  jar.set(MEMBER_COOKIE, await encodeMemberSession({ ...session, isAdmin: false }), {
    httpOnly: true,
    sameSite: "lax",
    secure: process.env.NODE_ENV === "production",
    path: "/",
    maxAge: 60 * 60 * 24 * 14,
  });
}

export async function clearMemberSession() {
  const jar = await cookies();
  jar.set(MEMBER_COOKIE, "", {
    httpOnly: true,
    sameSite: "lax",
    secure: process.env.NODE_ENV === "production",
    path: "/",
    maxAge: 0,
  });
}
