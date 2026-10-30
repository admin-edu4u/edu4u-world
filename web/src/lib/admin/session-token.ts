export const ADMIN_COOKIE = "edu4u_admin_session";
export const MEMBER_COOKIE = "edu4u_member_session";

export type AdminSession = {
  userId: number;
  username: string;
  isAdmin: true;
  exp: number;
};

export type MemberSession = {
  userId: number;
  email: string;
  isAdmin: false;
  exp: number;
};

function getSecret(): string {
  const secret = process.env.ADMIN_SESSION_SECRET;
  if (!secret || secret.length < 16) {
    throw new Error("ADMIN_SESSION_SECRET must be set (min 16 chars)");
  }
  return secret;
}

function toBase64Url(bytes: ArrayBuffer | Uint8Array): string {
  const u8 = bytes instanceof Uint8Array ? bytes : new Uint8Array(bytes);
  let binary = "";
  for (let i = 0; i < u8.length; i++) binary += String.fromCharCode(u8[i]!);
  return btoa(binary).replace(/\+/g, "-").replace(/\//g, "_").replace(/=+$/g, "");
}

function fromBase64Url(value: string): Uint8Array {
  const padded = value.replace(/-/g, "+").replace(/_/g, "/");
  const pad = padded.length % 4 === 0 ? "" : "=".repeat(4 - (padded.length % 4));
  const binary = atob(padded + pad);
  const out = new Uint8Array(binary.length);
  for (let i = 0; i < binary.length; i++) out[i] = binary.charCodeAt(i);
  return out;
}

async function sign(payloadB64: string): Promise<string> {
  const key = await crypto.subtle.importKey(
    "raw",
    new TextEncoder().encode(getSecret()),
    { name: "HMAC", hash: "SHA-256" },
    false,
    ["sign"],
  );
  const sig = await crypto.subtle.sign("HMAC", key, new TextEncoder().encode(payloadB64));
  return toBase64Url(sig);
}

function timingSafeEqual(a: string, b: string): boolean {
  if (a.length !== b.length) return false;
  let diff = 0;
  for (let i = 0; i < a.length; i++) diff |= a.charCodeAt(i)! ^ b.charCodeAt(i)!;
  return diff === 0;
}

export async function encodeAdminSession(
  session: Omit<AdminSession, "exp">,
  ttlSeconds = 60 * 60 * 12,
): Promise<string> {
  const full: AdminSession = {
    ...session,
    exp: Math.floor(Date.now() / 1000) + ttlSeconds,
  };
  const payloadB64 = toBase64Url(new TextEncoder().encode(JSON.stringify(full)));
  const sig = await sign(payloadB64);
  return `${payloadB64}.${sig}`;
}

export async function decodeAdminSession(
  token: string | undefined | null,
): Promise<AdminSession | null> {
  if (!token) return null;
  const [payloadB64, sig] = token.split(".");
  if (!payloadB64 || !sig) return null;
  try {
    const expected = await sign(payloadB64);
    if (!timingSafeEqual(sig, expected)) return null;
    const json = new TextDecoder().decode(fromBase64Url(payloadB64));
    const session = JSON.parse(json) as AdminSession;
    if (!session?.isAdmin || !session.userId || !session.exp) return null;
    if (session.exp < Math.floor(Date.now() / 1000)) return null;
    return session;
  } catch {
    return null;
  }
}

export async function encodeMemberSession(
  session: Omit<MemberSession, "exp">,
  ttlSeconds = 60 * 60 * 24 * 14,
): Promise<string> {
  const full: MemberSession = {
    ...session,
    isAdmin: false,
    exp: Math.floor(Date.now() / 1000) + ttlSeconds,
  };
  const payloadB64 = toBase64Url(new TextEncoder().encode(JSON.stringify(full)));
  const sig = await sign(payloadB64);
  return `${payloadB64}.${sig}`;
}

export async function decodeMemberSession(
  token: string | undefined | null,
): Promise<MemberSession | null> {
  if (!token) return null;
  const [payloadB64, sig] = token.split(".");
  if (!payloadB64 || !sig) return null;
  try {
    const expected = await sign(payloadB64);
    if (!timingSafeEqual(sig, expected)) return null;
    const json = new TextDecoder().decode(fromBase64Url(payloadB64));
    const session = JSON.parse(json) as MemberSession;
    if (session?.isAdmin !== false || !session.userId || !session.email || !session.exp) return null;
    if (session.exp < Math.floor(Date.now() / 1000)) return null;
    return session;
  } catch {
    return null;
  }
}
