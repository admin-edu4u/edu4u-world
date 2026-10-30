/** Display currency as RMX,XXX.XX (always 2 decimals, comma thousands). */
export function formatMoney(value: number | string | null | undefined): string {
  if (value == null || value === "") return "—";
  const raw =
    typeof value === "number"
      ? value
      : Number(String(value).replace(/[^0-9.-]/g, ""));
  if (!Number.isFinite(raw)) return String(value);
  return `RM${raw.toLocaleString("en-MY", {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2,
  })}`;
}

export function formatMoneyRange(
  min: number | string | null | undefined,
  max: number | string | null | undefined,
): string {
  const hasMin = min != null && min !== "" && Number.isFinite(Number(String(min).replace(/[^0-9.-]/g, "")));
  const hasMax = max != null && max !== "" && Number.isFinite(Number(String(max).replace(/[^0-9.-]/g, "")));
  if (!hasMin && !hasMax) return "—";
  if (!hasMin) return formatMoney(max);
  if (!hasMax) return formatMoney(min);
  return `${formatMoney(min)} - ${formatMoney(max)}`;
}
