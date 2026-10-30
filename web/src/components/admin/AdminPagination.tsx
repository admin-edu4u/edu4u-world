"use client";

import Link from "next/link";
import { useRouter } from "next/navigation";
import { useEffect, useState } from "react";
import {
  PAGE_SIZE_OPTIONS,
  buildListHref,
  pageItems,
  totalPages,
  type ListQuery,
} from "@/lib/admin/pagination";
import { SearchableSelect } from "@/components/ui/SearchableSelect";

export function AdminPagination({
  basePath,
  page,
  perPage,
  total,
  keep = {},
  pageParam = "page",
}: {
  basePath: string;
  page: number;
  perPage: number;
  total: number;
  keep?: ListQuery;
  pageParam?: "page" | "subPage";
}) {
  const router = useRouter();
  const pages = totalPages(total, perPage);
  const current = Math.min(Math.max(1, page), pages);
  const [draft, setDraft] = useState(String(current));
  const items = pageItems(current, pages);

  useEffect(() => {
    setDraft(String(current));
  }, [current]);
  const from = total === 0 ? 0 : (current - 1) * perPage + 1;
  const to = Math.min(current * perPage, total);

  function hrefFor(nextPage: number, nextPerPage = perPage) {
    return buildListHref(basePath, {
      ...keep,
      perPage: nextPerPage,
      [pageParam]: nextPage,
    });
  }

  function goToPage(raw: string) {
    const n = Number(raw);
    if (!Number.isFinite(n) || n < 1) {
      setDraft(String(current));
      return;
    }
    const next = Math.min(Math.floor(n), pages);
    setDraft(String(next));
    if (next !== current) router.push(hrefFor(next));
  }

  if (total === 0) return null;

  return (
    <div className="admin-pagination">
      <div className="admin-pagination-left">
        <label className="admin-pagination-size">
          <span>Show</span>
          <SearchableSelect
            name="perPage"
            value={String(perPage)}
            onChange={(next) => router.push(hrefFor(1, Number(next)))}
            options={PAGE_SIZE_OPTIONS.map((n) => ({ value: String(n), label: String(n) }))}
            placeholder="Rows"
            inputClassName="form-select"
            style={{ width: 88 }}
            menuPlacement="top"
          />
          <span>rows</span>
        </label>
        <span className="admin-pagination-meta">
          {from}-{to} of {total}
        </span>
      </div>

      <div className="admin-pagination-right">
        <nav className="admin-pagination-pages" aria-label="Pagination">
          {current > 1 ? (
            <Link href={hrefFor(current - 1)} className="admin-pagination-btn" aria-label="Previous page">
              Prev
            </Link>
          ) : (
            <span className="admin-pagination-btn is-disabled">Prev</span>
          )}

          {items.map((item, i) =>
            item === "ellipsis" ? (
              <span key={`e-${i}`} className="admin-pagination-ellipsis">
                …
              </span>
            ) : (
              <Link
                key={item}
                href={hrefFor(item)}
                className={`admin-pagination-btn${item === current ? " is-active" : ""}`}
                aria-current={item === current ? "page" : undefined}
              >
                {item}
              </Link>
            ),
          )}

          {current < pages ? (
            <Link href={hrefFor(current + 1)} className="admin-pagination-btn" aria-label="Next page">
              Next
            </Link>
          ) : (
            <span className="admin-pagination-btn is-disabled">Next</span>
          )}
        </nav>

        <label className="admin-pagination-goto">
          <span>Page</span>
          <input
            type="number"
            min={1}
            max={pages}
            value={draft}
            aria-label="Go to page"
            onChange={(e) => setDraft(e.target.value)}
            onBlur={() => goToPage(draft)}
            onKeyDown={(e) => {
              if (e.key === "Enter") {
                e.preventDefault();
                goToPage(draft);
              }
            }}
          />
          <span>of {pages}</span>
        </label>
      </div>
    </div>
  );
}
