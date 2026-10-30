import { randomBytes, scryptSync, timingSafeEqual } from "crypto";

const KEY_LEN = 64;
const SCRYPT_OPTIONS = { N: 16384, r: 8, p: 1, maxmem: 128 * 16384 * 8 * 2 } as const;

/** Stored as scrypt$<salt_b64url>$<hash_b64url> */
export function hashPassword(password: string): string {
  const salt = randomBytes(16);
  const hash = scryptSync(password, salt, KEY_LEN, SCRYPT_OPTIONS);
  return `scrypt$${salt.toString("base64url")}$${hash.toString("base64url")}`;
}

export function isModernPasswordHash(stored: string): boolean {
  return stored.startsWith("scrypt$");
}

export function verifyPassword(password: string, stored: string): boolean {
  if (!isModernPasswordHash(stored)) return false;
  const parts = stored.split("$");
  if (parts.length !== 3) return false;
  const saltPart = parts[1];
  const hashPart = parts[2];
  if (!saltPart || !hashPart) return false;
  try {
    const salt = Buffer.from(saltPart, "base64url");
    const expected = Buffer.from(hashPart, "base64url");
    const hash = scryptSync(password, salt, expected.length, SCRYPT_OPTIONS);
    if (hash.length !== expected.length) return false;
    return timingSafeEqual(hash, expected);
  } catch {
    return false;
  }
}
