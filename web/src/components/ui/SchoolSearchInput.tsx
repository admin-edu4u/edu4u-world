"use client";

import { useEffect, useId, useRef, useState } from "react";

type SchoolHit = { id: number; name: string };

type Props = {
  id?: string;
  name: string;
  value: string;
  onChange: (value: string) => void;
  placeholder?: string;
  className?: string;
  required?: boolean;
  minQueryLength?: number;
};

export function SchoolSearchInput({
  id: idProp,
  name,
  value,
  onChange,
  placeholder = "Enter school name",
  className = "",
  required,
  minQueryLength = 3,
}: Props) {
  const autoId = useId();
  const inputId = idProp ?? autoId;
  const rootRef = useRef<HTMLDivElement>(null);
  const [open, setOpen] = useState(false);
  const [loading, setLoading] = useState(false);
  const [results, setResults] = useState<SchoolHit[]>([]);
  const [notInDatabase, setNotInDatabase] = useState(false);

  useEffect(() => {
    const query = value.trim();
    if (query.length < minQueryLength) {
      setResults([]);
      setNotInDatabase(false);
      setOpen(false);
      setLoading(false);
      return;
    }

    let cancelled = false;
    setLoading(true);

    const timer = window.setTimeout(() => {
      void fetch(`/api/schools/search?q=${encodeURIComponent(query)}`)
        .then((res) => res.json())
        .then((data: { status?: boolean; list?: SchoolHit[] }) => {
          if (cancelled) return;
          if (data.status && data.list?.length) {
            setResults(data.list);
            setNotInDatabase(false);
          } else {
            setResults([]);
            setNotInDatabase(true);
          }
          setOpen(true);
        })
        .catch(() => {
          if (cancelled) return;
          setResults([]);
          setNotInDatabase(true);
          setOpen(true);
        })
        .finally(() => {
          if (!cancelled) setLoading(false);
        });
    }, 250);

    return () => {
      cancelled = true;
      window.clearTimeout(timer);
    };
  }, [value, minQueryLength]);

  useEffect(() => {
    function onPointerDown(event: MouseEvent) {
      if (!rootRef.current?.contains(event.target as Node)) {
        setOpen(false);
      }
    }
    document.addEventListener("mousedown", onPointerDown);
    return () => document.removeEventListener("mousedown", onPointerDown);
  }, []);

  function pick(name: string) {
    onChange(name);
    setOpen(false);
  }

  const showPanel = open && value.trim().length >= minQueryLength;

  return (
    <div className="pathway-school-search" ref={rootRef}>
      <input
        id={inputId}
        type="search"
        name={name}
        className={className}
        placeholder={placeholder}
        required={required}
        value={value}
        autoComplete="off"
        aria-autocomplete="list"
        aria-expanded={showPanel}
        aria-controls={`${inputId}-school-list`}
        onChange={(e) => onChange(e.target.value)}
        onFocus={() => {
          if (value.trim().length >= minQueryLength) setOpen(true);
        }}
      />
      {showPanel && (
        <div
          id={`${inputId}-school-list`}
          className="pathway-school-search-panel school-option-list"
          role="listbox"
        >
          {loading && (
            <div className="pathway-school-search-row pathway-school-search-muted">Searching…</div>
          )}
          {!loading &&
            results.map((hit) => (
              <button
                key={hit.id}
                type="button"
                role="option"
                className="pathway-school-search-row school-options"
                onMouseDown={(e) => e.preventDefault()}
                onClick={() => pick(hit.name)}
              >
                {hit.name}
              </button>
            ))}
          {!loading && notInDatabase && (
            <button
              type="button"
              role="option"
              className="pathway-school-search-row school-options pathway-school-search-fallback"
              onMouseDown={(e) => e.preventDefault()}
              onClick={() => pick(value.trim())}
            >
              <span className="d-block">{value.trim()}</span>
              <span className="d-block school-not-found mt-2">
                This school does not exist in our database, but you can still continue
              </span>
            </button>
          )}
        </div>
      )}
    </div>
  );
}
