export const Q_LEVELS: Record<number, string> = {
  1: "SPM",
  2: "IGCSE/O-Level",
  3: "STPM",
  4: "UEC",
  5: "Foundation",
  6: "Diploma",
  7: "A-Level",
  8: "IB Diploma",
  9: "CPU/CIMP",
  10: "Ausmat/SACEi/Atar",
  11: "K-12",
  12: "Others",
  13: "Master",
  14: "PHD",
  15: "SKM/Certificate",
  16: "Pre-University",
  17: "Degree",
  18: "Monash University Foundation Year (MUFY)",
  19: "SMA 3",
  20: "India CBSE / HSC",
  21: "Matriculation",
};

export const COURSE_LEVELS: Record<number, string> = {
  1: "Foundation",
  2: "International Pre-University",
  3: "Diploma",
  4: "Degree",
  5: "Master",
  6: "PHD",
  7: "K-12",
  8: "Certificate",
  9: "Post Basic Certificate",
  10: "Executive Diploma",
  11: "Ausmat",
  12: "A-Levels",
  13: "Professional Accountancy",
};

export const K12_LEVELS: Record<number, string> = {
  1: "Year 7",
  2: "Year 8",
  3: "Year 9",
  4: "Year 10",
  5: "Year 11",
  6: "Year 12",
};

export const SCHOLARSHIP_TYPES: Record<number, string> = {
  1: "Full scholarship",
  2: "Partial scholarship",
  3: "Financial Aid",
  4: "Deal",
  5: "Benefit",
};

export const SCHOLARSHIP_COVERAGE: Record<number, string> = {
  1: "Tuition Fee",
  2: "Resource Fee",
  3: "Accommodation",
  4: "Job Placement",
  5: "Others",
};

export const SCHOLARSHIP_REQUIREMENTS: Record<number, string> = {
  1: "Financial",
  2: "Merit",
  3: "Sports",
  4: "Talent & Leadership",
  5: "Others",
};

export const INTAKE_MONTHS: Record<number, string> = {
  1: "January",
  2: "February",
  3: "March",
  4: "April",
  5: "May",
  6: "June",
  7: "July",
  8: "August",
  9: "September",
  10: "October",
  11: "November",
  12: "December",
};

export const STREAM_OPTIONS: Record<number, string> = {
  0: "Others",
  1: "Science stream",
  2: "Arts stream",
  3: "Science stream with additional subjects",
};

export const PERSONALITY_TYPES: Record<number, string> = {
  1: "Describe",
  2: "Interest",
};

export const COUNTRY_OPTIONS: { value: string; label: string }[] = [
  { value: "129", label: "Malaysia" },
  { value: "192", label: "Singapore" },
  { value: "13", label: "Australia" },
  { value: "826", label: "United Kingdom" },
  { value: "840", label: "United States" },
];

export function optionsFromRecord(record: Record<number, string>) {
  return Object.entries(record).map(([value, label]) => ({ value, label }));
}

export function parseCsvIds(value: string | null | undefined): string[] {
  if (!value) return [];
  return String(value)
    .split(",")
    .map((part) => part.trim())
    .filter(Boolean);
}

/**
 * Merge rows into select options and guarantee currently selected ids are present
 * when a matching row exists (e.g. inactive records excluded from the main query).
 */
export function mergeSelectOptions(
  options: { value: string; label: string }[],
  extraRows: { value: string | number; label: string | null | undefined }[],
): { value: string; label: string }[] {
  const byValue = new Map<string, { value: string; label: string }>();
  for (const option of options) {
    const label = String(option.label || "").trim();
    if (!label) continue;
    byValue.set(String(option.value), { value: String(option.value), label });
  }
  for (const row of extraRows) {
    const value = String(row.value ?? "").trim();
    const label = String(row.label || "").trim();
    if (!value || !label || byValue.has(value)) continue;
    byValue.set(value, { value, label });
  }
  return Array.from(byValue.values());
}

export function joinCsvIds(formData: FormData, name: string, fallbackName?: string): string {
  const values = formData.getAll(name).map(String).filter(Boolean);
  if (values.length) return values.join(",");
  if (fallbackName) return String(formData.get(fallbackName) || "");
  return "";
}
