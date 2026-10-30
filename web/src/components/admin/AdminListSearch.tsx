"use client";

import { useRouter, useSearchParams } from "next/navigation";
import { useEffect, useRef, useState } from "react";
import { buildListHref, type ListQuery } from "@/lib/admin/pagination";

const DEBOUNCE_MS = 320;

export function AdminListSearch({
  basePath,
  placeholder = "Search…",
  paramName = "q",
  keep = {},
}: {
  basePath: string;
  placeholder?: string;
  paramName?: string;
  keep?: ListQuery;
}) {
  const router = useRouter();
  const searchParams = useSearchParams();
  const active = searchParams.get(paramName) ?? "";
  const [value, setValue] = useState(active);
  const keepRef = useRef(keep);
  keepRef.current = keep;

  useEffect(() => {
    setValue(active);
  }, [active]);

  useEffect(() => {
    const trimmed = value.trim();
    if (trimmed === active.trim()) return;

    const timer = window.setTimeout(() => {
      router.push(
        buildListHref(basePath, {
          ...keepRef.current,
          [paramName]: trimmed || undefined,
          page: 1,
        }),
      );
    }, DEBOUNCE_MS);

    return () => window.clearTimeout(timer);
  }, [value, active, basePath, paramName, router]);

  const showClear = value.trim().length > 0;

  return (
    <div className="admin-list-search" role="search">
      <input
        type="search"
        name={paramName}
        value={value}
        onChange={(e) => setValue(e.target.value)}
        placeholder={placeholder}
        className="form-control admin-list-search-input"
        autoComplete="off"
        spellCheck={false}
        aria-label={placeholder}
      />
      {showClear ? (
        <button
          type="button"
          className="admin-btn admin-btn-secondary admin-btn-sm"
          onClick={() => setValue("")}
        >
          Clear
        </button>
      ) : null}
    </div>
  );
}
