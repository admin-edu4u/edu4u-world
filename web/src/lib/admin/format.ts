/** Format MY contact numbers as +60… with no spaces. */
export function formatAdminPhone(value: unknown): string {
  if (value == null) return "—";
  let text = String(value).trim().replace(/\s+/g, "");
  if (!text || text === "0" || text === "—") return "—";
  if (text.startsWith("+")) return text;
  if (/^60\d+$/.test(text)) return `+${text}`;
  if (/^\d+$/.test(text)) {
    if (text.startsWith("0")) text = text.slice(1);
    return `+60${text}`;
  }
  return text;
}

export function formatAdminDateTime(value: string | null | undefined): string {
  if (!value) return "—";
  const date = new Date(value);
  if (Number.isNaN(date.getTime())) return String(value);
  return new Intl.DateTimeFormat("en-GB", {
    day: "2-digit",
    month: "short",
    year: "numeric",
    hour: "numeric",
    minute: "2-digit",
    hour12: true,
  }).format(date);
}

export { formatMoney as formatAdminMoney, formatMoneyRange as formatAdminMoneyRange } from "@/lib/money";

export function formatYesNo(value: unknown): string {
  if (value === true || value === 1 || value === "1") return "Yes";
  if (value === false || value === 0 || value === "0") return "No";
  if (value == null || value === "") return "—";
  return String(value);
}

export function labelFromMap(
  map: Map<number | string, string>,
  id: number | string | null | undefined,
  fallback: string = "—",
): string {
  if (id == null || id === "") return fallback;
  return map.get(id) ?? map.get(Number(id)) ?? map.get(String(id)) ?? fallback;
}

/** Resolve comma-separated IDs (e.g. personality characters) to names. */
export function labelsFromCsvIds(
  map: Map<number | string, string>,
  csv: string | null | undefined,
  fallback: string = "-",
): string {
  const names = resolveCsvIdLabels(map, csv);
  return names.length ? names.join(", ") : fallback;
}

/** Resolve comma-separated IDs to label list, skipping empty / zero IDs. */
export function resolveCsvIdLabels(
  map: Map<number | string, string>,
  csv: string | null | undefined,
): string[] {
  if (!csv?.trim()) return [];
  return csv
    .split(",")
    .map((part) => part.trim())
    .filter((id) => id && id !== "0")
    .map((id) => labelFromMap(map, id, ""))
    .filter(Boolean);
}

export const STREAM_LABELS: Record<number, string> = {
  0: "Others",
  1: "Science stream",
  2: "Arts stream",
  3: "Science stream with additional subjects",
};

const PERSONALITY_TYPE_LABELS: Record<number, string> = {
  1: "Describe",
  2: "Interest",
};

export function formatPersonalityType(value: number | string | null | undefined): string {
  if (value == null || value === "") return "—";
  const key = typeof value === "string" ? Number(value) : value;
  if (!Number.isNaN(key) && key in PERSONALITY_TYPE_LABELS) {
    return PERSONALITY_TYPE_LABELS[key as keyof typeof PERSONALITY_TYPE_LABELS];
  }
  return String(value);
}

export function formatStream(value: number | null | undefined): string {
  if (value == null) return "—";
  return STREAM_LABELS[value] || String(value);
}

export const VISIT_TIME_LABELS: Record<number, string> = {
  1: "9:00 AM - 11:00 AM",
  2: "11:00 AM - 1:00 PM",
  3: "2:00 PM - 4:00 PM",
  4: "4:00 PM - 6:00 PM",
};

const MONTH_LABELS = [
  "January",
  "February",
  "March",
  "April",
  "May",
  "June",
  "July",
  "August",
  "September",
  "October",
  "November",
  "December",
];

export function formatVisitTime(value: number | string | null | undefined): string {
  if (value == null || value === "" || value === 0 || value === "0") return "—";
  const key = Number(value);
  if (!Number.isFinite(key)) return String(value);
  return VISIT_TIME_LABELS[key] || String(value);
}

export function formatVisitDate(
  day: number | string | null | undefined,
  month: number | string | null | undefined,
  year: number | string | null | undefined,
): string {
  const d = Number(day);
  const m = Number(month);
  const y = Number(year);
  if (!Number.isFinite(d) || !Number.isFinite(m) || !Number.isFinite(y) || !d || !m || !y) {
    return "—";
  }
  const monthName = MONTH_LABELS[m - 1];
  if (!monthName) return `${d}/${m}/${y}`;
  return `${d} ${monthName} ${y}`;
}
