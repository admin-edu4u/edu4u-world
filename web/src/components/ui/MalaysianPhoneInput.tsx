"use client";

import {
  MY_PHONE_PLACEHOLDER,
  formatMalaysianPhoneInternational,
  malaysianPhoneError,
} from "@/lib/phone";

type Props = {
  name: string;
  className?: string;
  style?: React.CSSProperties;
  placeholder?: string;
  required?: boolean;
  value?: string;
  defaultValue?: string;
  onChange?: (value: string) => void;
  id?: string;
  "aria-label"?: string;
};

export function MalaysianPhoneInput({
  name,
  className,
  style,
  placeholder,
  required,
  value,
  defaultValue,
  onChange,
  id,
  "aria-label": ariaLabel,
}: Props) {
  function applyValidity(el: HTMLInputElement) {
    const err = malaysianPhoneError(el.value, {
      optional: !required,
      label: "phone number",
    });
    el.setCustomValidity(err ?? "");
  }

  function commitValue(raw: string, el: HTMLInputElement) {
    const trimmed = raw.trim();
    if (!trimmed) {
      el.value = "";
      onChange?.("");
      applyValidity(el);
      return;
    }
    const formatted = formatMalaysianPhoneInternational(trimmed);
    if (formatted) {
      if (!controlled) el.value = formatted;
      onChange?.(formatted);
    }
    applyValidity(el);
  }

  const controlled = value !== undefined;

  return (
    <input
      id={id}
      type="tel"
      name={name}
      inputMode="tel"
      autoComplete="tel"
      className={className}
      style={style}
      placeholder={placeholder ?? MY_PHONE_PLACEHOLDER}
      required={required}
      {...(controlled ? { value } : { defaultValue })}
      maxLength={16}
      aria-label={ariaLabel}
      onChange={(e) => {
        onChange?.(e.target.value);
        applyValidity(e.currentTarget);
      }}
      onBlur={(e) => {
        commitValue(e.target.value, e.currentTarget);
      }}
      onInvalid={(e) => applyValidity(e.currentTarget)}
    />
  );
}
