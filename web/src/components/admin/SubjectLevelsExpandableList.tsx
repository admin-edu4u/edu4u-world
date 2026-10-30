"use client";

import { useState } from "react";
import Link from "next/link";
import { ChevronDown } from "lucide-react";
import {
  AdminStatusActions,
  AdminTable,
  EmptyState,
  formatAdminDateTime,
  formatYesNo,
} from "@/components/admin/AdminUi";
import { statusLabel } from "@/lib/admin/status";

export type SubjectLevelItem = {
  id: number;
  name: string;
  addHref: string;
};

export type SubjectListItem = {
  id: number;
  levelId: number;
  name: string;
  category: string;
  preselect: number | null;
  status: number;
  createdAt: string | null;
  editHref: string;
  onActivate: () => Promise<void>;
  onDeactivate: () => Promise<void>;
  onArchive: () => Promise<void>;
  onRestore: () => Promise<void>;
  onDelete: () => Promise<void>;
};

function LevelGroup({
  level,
  subjects,
}: {
  level: SubjectLevelItem;
  subjects: SubjectListItem[];
}) {
  const [open, setOpen] = useState(false);

  return (
    <>
      <tr className="admin-table-row">
        <td>
          <button
            type="button"
            className={`admin-cat-toggle${open ? " is-open" : ""}`}
            aria-expanded={open}
            onClick={() => setOpen((value) => !value)}
          >
            <span className="admin-cat-toggle-label">{level.name}</span>
            {subjects.length > 0 ? (
              <span className="admin-cat-toggle-count">{subjects.length}</span>
            ) : null}
            <ChevronDown
              size={16}
              strokeWidth={1.75}
              className="admin-cat-toggle-chevron"
              aria-hidden
            />
          </button>
        </td>
        <td className="admin-table-actions" />
      </tr>
      {open
        ? subjects.map((subject) => (
            <tr key={`subject-${subject.id}`} className="admin-table-row admin-cat-sub-row">
              <td>
                <div className="admin-cat-sub-cell">
                  <Link href={subject.editHref} className="admin-cat-sub-name">
                    {subject.name}
                  </Link>
                  <span className="admin-cat-sub-meta">
                    {[
                      subject.category || null,
                      formatYesNo(subject.preselect) === "Yes" ? "Preselect" : null,
                      statusLabel(subject.status),
                      formatAdminDateTime(subject.createdAt),
                    ]
                      .filter(Boolean)
                      .join(" · ")}
                  </span>
                </div>
              </td>
              <td className="admin-table-actions">
                <AdminStatusActions
                  status={subject.status}
                  editHref={subject.editHref}
                  onActivate={subject.onActivate}
                  onDeactivate={subject.onDeactivate}
                  onArchive={subject.onArchive}
                  onRestore={subject.onRestore}
                  onDelete={subject.onDelete}
                />
              </td>
            </tr>
          ))
        : null}
      {open ? (
        <tr className="admin-cat-sub-row admin-cat-sub-empty">
          <td colSpan={2}>
            {subjects.length === 0 ? (
              <span className="admin-cat-sub-empty-text">No subjects in this view.</span>
            ) : null}
            <Link href={level.addHref} className="admin-cat-sub-add">
              Add subject
            </Link>
          </td>
        </tr>
      ) : null}
    </>
  );
}

export function SubjectLevelsExpandableList({
  levels,
  subjects,
}: {
  levels: SubjectLevelItem[];
  subjects: SubjectListItem[];
}) {
  if (!levels.length) {
    return <EmptyState message="No levels." />;
  }

  const byLevel = new Map<number, SubjectListItem[]>();
  for (const subject of subjects) {
    const list = byLevel.get(subject.levelId) || [];
    list.push(subject);
    byLevel.set(subject.levelId, list);
  }

  return (
    <AdminTable headers={["Level", ""]}>
      {levels.map((level) => (
        <LevelGroup
          key={level.id}
          level={level}
          subjects={byLevel.get(level.id) || []}
        />
      ))}
    </AdminTable>
  );
}
