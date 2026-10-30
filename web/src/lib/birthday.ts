/** Validate split day / month / year birthday fields (Pathway360 and similar). */

export type BirthdayParts = {
  day: string;
  month: string;
  year: string;
};

export function sanitizeBirthdayDigits(value: string, maxLength: number): string {
  return value.replace(/\D/g, "").slice(0, maxLength);
}

function parseParts(parts: BirthdayParts): { day: number; month: number; year: number } | null {
  if (!parts.day.trim() || !parts.month.trim() || !parts.year.trim()) return null;
  const day = Number.parseInt(parts.day, 10);
  const month = Number.parseInt(parts.month, 10);
  const year = Number.parseInt(parts.year, 10);
  if (!Number.isFinite(day) || !Number.isFinite(month) || !Number.isFinite(year)) return null;
  return { day, month, year };
}

export function isValidCalendarDate(day: number, month: number, year: number): boolean {
  if (year < 1000 || year > 9999) return false;
  if (month < 1 || month > 12) return false;
  if (day < 1) return false;
  const date = new Date(year, month - 1, day);
  return (
    date.getFullYear() === year &&
    date.getMonth() === month - 1 &&
    date.getDate() === day
  );
}

function ageOnDate(birth: Date, on: Date): number {
  let age = on.getFullYear() - birth.getFullYear();
  const monthDiff = on.getMonth() - birth.getMonth();
  if (monthDiff < 0 || (monthDiff === 0 && on.getDate() < birth.getDate())) {
    age -= 1;
  }
  return age;
}

export type BirthdayValidationMessages = {
  required?: string;
  year?: string;
  invalid?: string;
  invalidDate?: string;
  past?: string;
  minAge?: (minAge: number) => string;
};

export type BirthdayValidationOptions = {
  minAge?: number;
  maxAge?: number;
  messages?: BirthdayValidationMessages;
};

export function birthdayError(
  parts: BirthdayParts,
  options: BirthdayValidationOptions = {},
): string | null {
  const minAge = options.minAge ?? 10;
  const maxAge = options.maxAge ?? 100;
  const msg = options.messages ?? {};

  if (!parts.day.trim() || !parts.month.trim() || !parts.year.trim()) {
    return msg.required ?? "Please enter your birthday";
  }

  if (parts.year.trim().length !== 4) {
    return msg.year ?? "Please enter a valid 4-digit year";
  }

  const parsed = parseParts(parts);
  if (!parsed) return msg.invalid ?? "Please enter a valid birthday";

  const { day, month, year } = parsed;

  if (!isValidCalendarDate(day, month, year)) {
    return msg.invalidDate ?? "Please enter a valid date of birth";
  }

  const birth = new Date(year, month - 1, day);
  const today = new Date();
  today.setHours(0, 0, 0, 0);

  if (birth.getTime() >= today.getTime()) {
    return msg.past ?? "Date of birth must be in the past";
  }

  const age = ageOnDate(birth, today);
  if (age < minAge) {
    if (msg.minAge) return msg.minAge(minAge);
    return `Please enter a valid date of birth (must be at least ${minAge} years old)`;
  }
  if (age > maxAge) {
    return msg.invalidDate ?? "Please enter a valid date of birth";
  }

  return null;
}
