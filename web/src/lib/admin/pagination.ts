export const PAGE_SIZE_OPTIONS = [10, 25, 50, 100, 250] as const;
export const DEFAULT_PAGE_SIZE = 25;

export type ListQuery = Record<string, string | number | undefined | null>;

export function parsePage(value: string | string[] | undefined): number {
  const raw = Array.isArray(value) ? value[0] : value;
  const n = Number(raw || 1);
  if (!Number.isFinite(n) || n < 1) return 1;
  return Math.floor(n);
}

export function parsePerPage(value: string | string[] | undefined): number {
  const raw = Array.isArray(value) ? value[0] : value;
  const n = Number(raw || DEFAULT_PAGE_SIZE);
  if ((PAGE_SIZE_OPTIONS as readonly number[]).includes(n)) return n;
  return DEFAULT_PAGE_SIZE;
}

export function parsePagination(searchParams: {
  page?: string | string[];
  perPage?: string | string[];
}) {
  return {
    page: parsePage(searchParams.page),
    perPage: parsePerPage(searchParams.perPage),
  };
}

export function pageRange(page: number, perPage: number) {
  const from = (page - 1) * perPage;
  const to = from + perPage - 1;
  return { from, to };
}

export function totalPages(total: number, perPage: number) {
  return Math.max(1, Math.ceil(Math.max(0, total) / perPage));
}

/** Drop defaults so URLs stay clean. */
export function buildListHref(basePath: string, query: ListQuery = {}) {
  const sp = new URLSearchParams();
  for (const [key, value] of Object.entries(query)) {
    if (value === undefined || value === null || value === "") continue;
    if (key === "status" && value === "active") continue;
    if (key === "page" && Number(value) <= 1) continue;
    if (key === "perPage" && Number(value) === DEFAULT_PAGE_SIZE) continue;
    if (key === "subPage" && Number(value) <= 1) continue;
    sp.set(key, String(value));
  }
  const qs = sp.toString();
  return qs ? `${basePath}?${qs}` : basePath;
}

export function pageItems(current: number, pages: number): Array<number | "ellipsis"> {
  if (pages <= 7) {
    return Array.from({ length: pages }, (_, i) => i + 1);
  }

  const set = new Set<number>();
  set.add(1);
  set.add(pages);
  for (let i = current - 1; i <= current + 1; i += 1) {
    if (i >= 1 && i <= pages) set.add(i);
  }
  if (current <= 3) {
    set.add(2);
    set.add(3);
    set.add(4);
  }
  if (current >= pages - 2) {
    set.add(pages - 1);
    set.add(pages - 2);
    set.add(pages - 3);
  }

  const sorted = [...set].sort((a, b) => a - b);
  const items: Array<number | "ellipsis"> = [];
  for (let i = 0; i < sorted.length; i += 1) {
    const n = sorted[i]!;
    if (i > 0 && n - sorted[i - 1]! > 1) items.push("ellipsis");
    items.push(n);
  }
  return items;
}
