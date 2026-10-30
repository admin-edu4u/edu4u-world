"use client";

import { useState } from "react";
import Link from "next/link";
import { ChevronDown } from "lucide-react";
import {
  AdminStatusActions,
  AdminTable,
  AdminThumb,
  EmptyState,
  formatAdminDateTime,
} from "@/components/admin/AdminUi";
import { statusLabel } from "@/lib/admin/status";

export type CategoryListItem = {
  id: number;
  name: string;
  img: string | null;
  status: number;
  createdAt: string | null;
  editHref: string;
  addSubHref: string;
  onActivate: () => Promise<void>;
  onDeactivate: () => Promise<void>;
  onArchive: () => Promise<void>;
  onRestore: () => Promise<void>;
  onDelete: () => Promise<void>;
};

export type SubCategoryListItem = {
  id: number;
  mainId: number;
  name: string;
  character: string;
  status: number;
  createdAt: string | null;
  editHref: string;
  onActivate: () => Promise<void>;
  onDeactivate: () => Promise<void>;
  onArchive: () => Promise<void>;
  onRestore: () => Promise<void>;
  onDelete: () => Promise<void>;
};

function CategoryGroup({
  cat,
  subs,
}: {
  cat: CategoryListItem;
  subs: SubCategoryListItem[];
}) {
  const [open, setOpen] = useState(false);

  return (
    <>
      <tr className="admin-table-row">
        <td className="admin-table-primary">
          <div className="admin-entity-cell">
            <AdminThumb folder="category" filename={cat.img} alt="" />
            <button
              type="button"
              className={`admin-cat-toggle${open ? " is-open" : ""}`}
              aria-expanded={open}
              onClick={() => setOpen((value) => !value)}
            >
              <span className="admin-cat-toggle-label">{cat.name}</span>
              {subs.length > 0 ? (
                <span className="admin-cat-toggle-count">{subs.length}</span>
              ) : null}
              <ChevronDown
                size={16}
                strokeWidth={1.75}
                className="admin-cat-toggle-chevron"
                aria-hidden
              />
            </button>
          </div>
        </td>
        <td className="admin-table-status">{statusLabel(cat.status)}</td>
        <td className="admin-table-date">{formatAdminDateTime(cat.createdAt)}</td>
        <td className="admin-table-actions">
          <AdminStatusActions
            status={cat.status}
            editHref={cat.editHref}
            onActivate={cat.onActivate}
            onDeactivate={cat.onDeactivate}
            onArchive={cat.onArchive}
            onRestore={cat.onRestore}
            onDelete={cat.onDelete}
          />
        </td>
      </tr>
      {open
        ? subs.map((sub) => (
            <tr key={`sub-${sub.id}`} className="admin-table-row admin-cat-sub-row">
              <td className="admin-table-primary">
                <div className="admin-cat-sub-cell">
                  <Link href={sub.editHref} className="admin-cat-sub-name">
                    {sub.name}
                  </Link>
                  {sub.character ? (
                    <span className="admin-cat-sub-meta">{sub.character}</span>
                  ) : null}
                </div>
              </td>
              <td className="admin-table-status">{statusLabel(sub.status)}</td>
              <td className="admin-table-date">{formatAdminDateTime(sub.createdAt)}</td>
              <td className="admin-table-actions">
                <AdminStatusActions
                  status={sub.status}
                  editHref={sub.editHref}
                  onActivate={sub.onActivate}
                  onDeactivate={sub.onDeactivate}
                  onArchive={sub.onArchive}
                  onRestore={sub.onRestore}
                  onDelete={sub.onDelete}
                />
              </td>
            </tr>
          ))
        : null}
      {open ? (
        <tr className="admin-cat-sub-row admin-cat-sub-empty">
          <td colSpan={4}>
            {subs.length === 0 ? (
              <span className="admin-cat-sub-empty-text">No sub categories in this view.</span>
            ) : null}
            <Link href={cat.addSubHref} className="admin-cat-sub-add">
              Add sub category
            </Link>
          </td>
        </tr>
      ) : null}
    </>
  );
}

export function CategoryExpandableList({
  cats,
  subs,
}: {
  cats: CategoryListItem[];
  subs: SubCategoryListItem[];
}) {
  if (!cats.length) {
    return <EmptyState message="No categories in this view." />;
  }

  const byMain = new Map<number, SubCategoryListItem[]>();
  for (const sub of subs) {
    const list = byMain.get(sub.mainId) || [];
    list.push(sub);
    byMain.set(sub.mainId, list);
  }

  return (
    <AdminTable headers={["Name", "Status", "Created", ""]}>
      {cats.map((cat) => (
        <CategoryGroup key={cat.id} cat={cat} subs={byMain.get(cat.id) || []} />
      ))}
    </AdminTable>
  );
}
