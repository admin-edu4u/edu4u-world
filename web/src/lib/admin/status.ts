export const STATUS = {
  ACTIVE: 1,
  INACTIVE: 98,
  ARCHIVED: 99,
} as const;

export type StatusTab = "active" | "inactive" | "archived";

export const STATUS_TABS: { id: StatusTab; label: string }[] = [
  { id: "active", label: "Active" },
  { id: "inactive", label: "Inactive" },
  { id: "archived", label: "Archived" },
];

export function parseStatusTab(value: string | string[] | undefined): StatusTab {
  const raw = Array.isArray(value) ? value[0] : value;
  if (raw === "inactive" || raw === "archived" || raw === "active") return raw;
  return "active";
}

export function statusLabel(status: number | string | null | undefined): string {
  if (status == null || status === "") return "-";
  const value = typeof status === "string" ? Number(status) : status;
  if (value === STATUS.ACTIVE) return "Active";
  if (value === STATUS.INACTIVE) return "Inactive";
  if (value === STATUS.ARCHIVED) return "Archived";
  if (!Number.isFinite(value)) return String(status);
  return String(value);
}

/** Apply Active / Inactive / Archived filter to a Supabase query builder. */
export function applyStatusFilter<T extends { eq: (column: string, value: number) => T }>(
  query: T,
  column: string,
  tab: StatusTab,
): T {
  if (tab === "inactive") return query.eq(column, STATUS.INACTIVE);
  if (tab === "archived") return query.eq(column, STATUS.ARCHIVED);
  return query.eq(column, STATUS.ACTIVE);
}

export type StatusCounts = Record<StatusTab, number>;

type StatusCountResult = { count: number | null };

type StatusCountQuery = PromiseLike<StatusCountResult> & {
  eq: (column: string, value: number) => StatusCountQuery;
};

type StatusCountClient = {
  from: (table: string) => {
    select: (columns: string, options: { count: "exact"; head: boolean }) => StatusCountQuery;
  };
};

/** Count rows for Active / Inactive / Archived tabs (head-only queries). */
export async function countStatusTabs(
  supabase: unknown,
  table: string,
  column: string,
  applyExtra?: (query: StatusCountQuery) => StatusCountQuery,
): Promise<StatusCounts> {
  const client = supabase as StatusCountClient;
  const base = (): StatusCountQuery => {
    const query = client.from(table).select("*", { count: "exact", head: true });
    return applyExtra ? applyExtra(query) : query;
  };

  const [active, inactive, archived] = await Promise.all([
    base().eq(column, STATUS.ACTIVE),
    base().eq(column, STATUS.INACTIVE),
    base().eq(column, STATUS.ARCHIVED),
  ]);

  return {
    active: active.count ?? 0,
    inactive: inactive.count ?? 0,
    archived: archived.count ?? 0,
  };
}

export function sumStatusCounts(...counts: StatusCounts[]): StatusCounts {
  return {
    active: counts.reduce((n, c) => n + c.active, 0),
    inactive: counts.reduce((n, c) => n + c.inactive, 0),
    archived: counts.reduce((n, c) => n + c.archived, 0),
  };
}
