/** Returns an i18n key for the first password rule that fails, or null if valid. */
export function signupPasswordErrorKey(password: string): string | null {
  if (password.length < 8) return "passwordMinLength";
  if (!/[A-Z]/.test(password)) return "passwordNeedsUppercase";
  if (!/[0-9]/.test(password)) return "passwordNeedsNumber";
  if (!/['^£$%&*()}{@#~?><>,|=_+¬-]/.test(password)) return "passwordNeedsSpecial";
  return null;
}
