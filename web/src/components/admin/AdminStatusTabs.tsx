import Link from "next/link";
import { buildListHref, type ListQuery } from "@/lib/admin/pagination";
import { STATUS_TABS, type StatusCounts, type StatusTab } from "@/lib/admin/status";

export function AdminStatusTabs({
  basePath,
  current,
  keep = {},
  counts,
}: {
  basePath: string;
  current: StatusTab;
  keep?: ListQuery;
  counts?: StatusCounts;
}) {
  return (
    <div className="admin-status-tabs" role="tablist" aria-label="Status filter">
      {STATUS_TABS.map((tab) => {
        const href = buildListHref(basePath, {
          ...keep,
          status: tab.id,
          page: 1,
          subPage: 1,
        });
        const active = current === tab.id;
        const count = counts?.[tab.id];
        return (
          <Link
            key={tab.id}
            href={href}
            className={`admin-status-tab${active ? " is-active" : ""}`}
            role="tab"
            aria-selected={active}
          >
            <span>{tab.label}</span>
            {count != null ? <span className="admin-status-tab-count">{count}</span> : null}
          </Link>
        );
      })}
    </div>
  );
}
