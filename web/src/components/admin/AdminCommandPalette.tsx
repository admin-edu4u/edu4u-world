"use client";

import {
  useCallback,
  useEffect,
  useId,
  useMemo,
  useRef,
  useState,
  type ComponentType,
  type KeyboardEvent,
} from "react";
import { useRouter } from "next/navigation";
import {
  ArrowRight,
  Clock3,
  CornerDownLeft,
  Search,
  type LucideProps,
} from "lucide-react";

export type AdminSearchItem = {
  href: string;
  label: string;
  section: string;
  icon: ComponentType<LucideProps>;
  keywords?: string[];
};

const RECENTS_KEY = "edu4u-admin-search-recents";
const MAX_RECENTS = 8;

type RankedItem = AdminSearchItem & { score: number };

function readRecents(): string[] {
  try {
    const raw = localStorage.getItem(RECENTS_KEY);
    if (!raw) return [];
    const parsed = JSON.parse(raw) as unknown;
    return Array.isArray(parsed) ? parsed.filter((x): x is string => typeof x === "string") : [];
  } catch {
    return [];
  }
}

function writeRecent(href: string) {
  const next = [href, ...readRecents().filter((h) => h !== href)].slice(0, MAX_RECENTS);
  localStorage.setItem(RECENTS_KEY, JSON.stringify(next));
}

function clearRecentsStorage() {
  localStorage.removeItem(RECENTS_KEY);
}

function scoreItem(item: AdminSearchItem, q: string): number {
  const label = item.label.toLowerCase();
  const section = item.section.toLowerCase();
  const href = item.href.toLowerCase();
  const keywords = (item.keywords || []).map((k) => k.toLowerCase());
  const haystack = [label, section, href, ...keywords].join(" ");

  if (!q) return 0;
  if (label === q) return 100;
  if (label.startsWith(q)) return 90;
  if (keywords.some((k) => k === q || k.startsWith(q))) return 85;
  if (section === q || section.startsWith(q)) return 75;
  if (label.includes(q)) return 60;
  if (keywords.some((k) => k.includes(q))) return 55;
  if (section.includes(q)) return 45;
  if (href.includes(q)) return 35;
  // multi-word: all tokens must match somewhere
  const tokens = q.split(/\s+/).filter(Boolean);
  if (tokens.length > 1 && tokens.every((t) => haystack.includes(t))) return 40;
  return 0;
}

function highlightMatch(text: string, query: string) {
  const q = query.trim();
  if (!q) return text;
  const lower = text.toLowerCase();
  const idx = lower.indexOf(q.toLowerCase());
  if (idx < 0) return text;
  return (
    <>
      {text.slice(0, idx)}
      <mark className="admin-cmd-mark">{text.slice(idx, idx + q.length)}</mark>
      {text.slice(idx + q.length)}
    </>
  );
}

function groupBySection(items: AdminSearchItem[]) {
  const map = new Map<string, AdminSearchItem[]>();
  for (const item of items) {
    const list = map.get(item.section) || [];
    list.push(item);
    map.set(item.section, list);
  }
  return Array.from(map.entries());
}

export function AdminCommandPalette({
  open,
  onOpenChange,
  items,
}: {
  open: boolean;
  onOpenChange: (open: boolean) => void;
  items: AdminSearchItem[];
}) {
  const router = useRouter();
  const inputRef = useRef<HTMLInputElement>(null);
  const listRef = useRef<HTMLDivElement>(null);
  const listId = useId();
  const [query, setQuery] = useState("");
  const [activeIndex, setActiveIndex] = useState(0);
  const [recentHrefs, setRecentHrefs] = useState<string[]>([]);

  const close = useCallback(() => {
    onOpenChange(false);
    setQuery("");
    setActiveIndex(0);
  }, [onOpenChange]);

  const go = useCallback(
    (href: string) => {
      writeRecent(href);
      setRecentHrefs(readRecents());
      close();
      router.push(href);
    },
    [close, router],
  );

  useEffect(() => {
    if (!open) return;
    setRecentHrefs(readRecents());
    setQuery("");
    setActiveIndex(0);
    const t = window.setTimeout(() => inputRef.current?.focus(), 0);
    const prev = document.body.style.overflow;
    document.body.style.overflow = "hidden";
    return () => {
      window.clearTimeout(t);
      document.body.style.overflow = prev;
    };
  }, [open]);

  const filtered = useMemo(() => {
    const q = query.trim().toLowerCase();
    if (!q) return [] as RankedItem[];
    return items
      .map((item) => ({ ...item, score: scoreItem(item, q) }))
      .filter((item) => item.score > 0)
      .sort((a, b) => b.score - a.score || a.label.localeCompare(b.label));
  }, [items, query]);

  const recents = useMemo(() => {
    return recentHrefs
      .map((href) => items.find((item) => item.href === href))
      .filter((item): item is AdminSearchItem => Boolean(item));
  }, [items, recentHrefs]);

  const browsing = useMemo(() => {
    if (query.trim()) return [] as AdminSearchItem[];
    // Prefer pages that aren't already in recents for "Jump to"
    const recentSet = new Set(recents.map((r) => r.href));
    return items.filter((item) => !recentSet.has(item.href));
  }, [items, query, recents]);

  const flatResults = useMemo(() => {
    if (query.trim()) return filtered;
    return [...recents, ...browsing];
  }, [query, filtered, recents, browsing]);

  const resultGroups = useMemo(() => {
    if (query.trim()) {
      return groupBySection(filtered);
    }
    const groups: [string, AdminSearchItem[]][] = [];
    if (recents.length) groups.push(["Recent", recents]);
    for (const [section, sectionItems] of groupBySection(browsing)) {
      groups.push([section, sectionItems]);
    }
    return groups;
  }, [query, filtered, recents, browsing]);

  useEffect(() => {
    setActiveIndex(0);
  }, [query, open]);

  useEffect(() => {
    if (!open || !listRef.current) return;
    const active = listRef.current.querySelector<HTMLElement>("[data-active='true']");
    active?.scrollIntoView({ block: "nearest" });
  }, [activeIndex, open, flatResults]);

  useEffect(() => {
    if (!open) return;
    const onKey = (e: globalThis.KeyboardEvent) => {
      if (e.key === "Escape") {
        e.preventDefault();
        if (query.trim()) {
          setQuery("");
          setActiveIndex(0);
          return;
        }
        close();
      }
    };
    window.addEventListener("keydown", onKey);
    return () => window.removeEventListener("keydown", onKey);
  }, [open, close, query]);

  function onInputKeyDown(e: KeyboardEvent<HTMLInputElement>) {
    if (e.key === "ArrowDown") {
      e.preventDefault();
      if (!flatResults.length) return;
      setActiveIndex((i) => (i + 1) % flatResults.length);
    } else if (e.key === "ArrowUp") {
      e.preventDefault();
      if (!flatResults.length) return;
      setActiveIndex((i) => (i - 1 + flatResults.length) % flatResults.length);
    } else if (e.key === "Enter") {
      e.preventDefault();
      const item = flatResults[activeIndex];
      if (item) go(item.href);
    }
  }

  function clearRecents() {
    clearRecentsStorage();
    setRecentHrefs([]);
  }

  if (!open) return null;

  let runningIndex = -1;
  const hasQuery = Boolean(query.trim());
  const emptySearch = hasQuery && filtered.length === 0;

  return (
    <div className="admin-cmd" role="presentation">
      <button type="button" className="admin-cmd-backdrop" aria-label="Close search" onClick={close} />
      <div
        className="admin-cmd-dialog"
        role="dialog"
        aria-modal="true"
        aria-label="Search admin pages"
      >
        <div className="admin-cmd-input-row">
          <Search size={18} strokeWidth={1.75} aria-hidden />
          <input
            ref={inputRef}
            value={query}
            onChange={(e) => setQuery(e.target.value)}
            onKeyDown={onInputKeyDown}
            placeholder="Search pages, sections, or features..."
            aria-controls={listId}
            aria-autocomplete="list"
            aria-activedescendant={
              flatResults[activeIndex] ? `${listId}-opt-${activeIndex}` : undefined
            }
          />
          {query ? (
            <button type="button" className="admin-cmd-clear" onClick={() => setQuery("")}>
              Clear
            </button>
          ) : null}
          <kbd>Esc</kbd>
        </div>

        <div className="admin-cmd-body" id={listId} role="listbox" ref={listRef}>
          {emptySearch ? (
            <div className="admin-cmd-empty">
              <p>No pages match “{query.trim()}”.</p>
              <p className="admin-cmd-empty-hint">Try a section name like University or Inbound.</p>
            </div>
          ) : null}

          {!emptySearch
            ? resultGroups.map(([title, groupItems]) => (
                <div className="admin-cmd-group" key={title}>
                  <div className="admin-cmd-group-head">
                    <div className="admin-cmd-group-title">
                      {title === "Recent" ? (
                        <>
                          <Clock3 size={12} strokeWidth={2} aria-hidden />
                          Recent
                        </>
                      ) : (
                        title
                      )}
                      <span className="admin-cmd-group-count">{groupItems.length}</span>
                    </div>
                    {title === "Recent" ? (
                      <button type="button" className="admin-cmd-group-action" onClick={clearRecents}>
                        Clear
                      </button>
                    ) : null}
                  </div>
                  <ul className="admin-cmd-list">
                    {groupItems.map((item) => {
                      runningIndex += 1;
                      const index = runningIndex;
                      const Icon = item.icon;
                      const active = index === activeIndex;
                      return (
                        <li key={`${title}-${item.href}`} role="option" aria-selected={active}>
                          <button
                            type="button"
                            id={`${listId}-opt-${index}`}
                            data-active={active ? "true" : "false"}
                            className={`admin-cmd-item${active ? " is-active" : ""}`}
                            onClick={() => go(item.href)}
                            onMouseEnter={() => setActiveIndex(index)}
                          >
                            <span className="admin-cmd-item-icon">
                              <Icon size={16} strokeWidth={1.75} aria-hidden />
                            </span>
                            <span className="admin-cmd-item-text">
                              <span className="admin-cmd-item-label">
                                {highlightMatch(item.label, query)}
                              </span>
                              <span className="admin-cmd-item-meta">
                                {title === "Recent" || hasQuery
                                  ? item.section
                                  : item.href.replace(/^\/admin\/?/, "/") || "/"}
                              </span>
                            </span>
                            <ArrowRight size={14} strokeWidth={1.75} aria-hidden />
                          </button>
                        </li>
                      );
                    })}
                  </ul>
                </div>
              ))
            : null}
        </div>

        <div className="admin-cmd-footer">
          <span>
            <kbd>↑</kbd>
            <kbd>↓</kbd>
            navigate
          </span>
          <span>
            <kbd>
              <CornerDownLeft size={11} strokeWidth={2} aria-hidden />
            </kbd>
            open
          </span>
          <span>
            <kbd>Esc</kbd>
            {hasQuery ? "clear" : "close"}
          </span>
          {!hasQuery ? (
            <span className="admin-cmd-footer-meta">{items.length} pages</span>
          ) : (
            <span className="admin-cmd-footer-meta">
              {filtered.length} result{filtered.length === 1 ? "" : "s"}
            </span>
          )}
        </div>
      </div>
    </div>
  );
}
