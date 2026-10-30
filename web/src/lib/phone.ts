/** Malaysian phone helpers: accepts local, +60, or national digits; stores as +60… */

export const MY_PHONE_PLACEHOLDER = "+60123456789";
/** @deprecated Use MY_PHONE_PLACEHOLDER */
export const MY_PHONE_NATIONAL_PLACEHOLDER = MY_PHONE_PLACEHOLDER;

export type MalaysianPhoneMessages = {
  required?: string;
  invalid?: string;
  tooShort?: string;
  tooLong?: string;
};

export type MalaysianPhoneOptions = {
  optional?: boolean;
  /** @deprecated Single-field +60 format is always used */
  nationalOnly?: boolean;
  label?: string;
  messages?: MalaysianPhoneMessages;
};

function digitsOnly(value: string): string {
  return value.replace(/\D/g, "");
}

/** National digits without country code or leading 0 (e.g. 123456789). */
export function malaysianNationalDigits(value: string): string {
  let digits = digitsOnly(value.trim());
  if (digits.startsWith("60")) digits = digits.slice(2);
  if (digits.startsWith("0")) digits = digits.slice(1);
  return digits;
}

/** Format for display and storage: +60123456789 */
export function formatMalaysianPhoneInternational(value: string): string {
  const trimmed = value.trim();
  if (!trimmed) return "";
  const national = malaysianNationalDigits(trimmed);
  if (!national) return trimmed.startsWith("+") ? "+" : "";
  return `+60${national}`;
}

/** Normalize to local format starting with 0 (e.g. 0123456789). */
export function normalizeMalaysianPhone(value: string): string {
  const national = malaysianNationalDigits(value);
  if (!national) return "";
  return `0${national}`;
}

function isValidNational(digits: string): boolean {
  if (/^1\d{8,9}$/.test(digits)) return true;
  if (/^[2-9]\d{7,8}$/.test(digits)) return true;
  if (digits.startsWith("0")) return /^0\d{8,10}$/.test(digits);
  return false;
}

export function isValidMalaysianPhone(
  value: string,
  options: MalaysianPhoneOptions = {},
): boolean {
  const trimmed = value.trim();
  if (!trimmed) return !!options.optional;

  const national = malaysianNationalDigits(trimmed);
  if (!national) return false;

  return isValidNational(national);
}

export function malaysianPhoneError(
  value: string,
  options: MalaysianPhoneOptions = {},
): string | null {
  const msg = options.messages ?? {};
  const trimmed = value.trim();

  if (!trimmed) {
    return options.optional ? null : (msg.required ?? "Please enter your phone number");
  }

  if (isValidMalaysianPhone(trimmed, options)) return null;

  const national = malaysianNationalDigits(trimmed);
  const digitCount = national.length;

  if (digitCount > 0 && digitCount < 8) {
    return msg.tooShort ?? "Please enter a valid Malaysian phone number (too short)";
  }
  if (digitCount > 11) {
    return msg.tooLong ?? "Please enter a valid Malaysian phone number (too long)";
  }
  return msg.invalid ?? `Please enter a valid Malaysian phone number (e.g. ${MY_PHONE_PLACEHOLDER})`;
}
