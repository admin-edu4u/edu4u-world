"use client";

import { useState } from "react";
import { useRouter } from "next/navigation";
import { FolderTree } from "lucide-react";
import { AdminLocalizedField } from "@/components/admin/AdminLocalizedField";
import { AdminModal } from "@/components/admin/AdminModal";
import { DeleteButton, SubmitButton } from "@/components/admin/DeleteButton";
import { statusLabel, STATUS } from "@/lib/admin/status";
import {
  activateSubjectCategory,
  archiveSubjectCategory,
  deactivateSubjectCategory,
  deleteSubjectCategory,
  restoreSubjectCategory,
  saveSubjectCategory,
} from "@/app/admin/subjects/actions";

export type SubjectCategoryRow = {
  sc_id: number;
  sc_name: string;
  sc_status: number | null;
  nameCh?: string;
  nameBm?: string;
};

function CategoryStatusActions({
  status,
  onActivate,
  onDeactivate,
  onArchive,
  onRestore,
  onDelete,
  onEdit,
}: {
  status: number | null;
  onActivate: () => Promise<void>;
  onDeactivate: () => Promise<void>;
  onArchive: () => Promise<void>;
  onRestore: () => Promise<void>;
  onDelete: () => Promise<void>;
  onEdit: () => void;
}) {
  const value = Number(status ?? STATUS.ACTIVE);
  return (
    <div className="admin-actions">
      <button type="button" className="admin-btn admin-btn-secondary admin-btn-sm" onClick={onEdit}>
        Edit
      </button>
      {value === STATUS.ACTIVE ? (
        <>
          <DeleteButton action={onDeactivate} label="Deactivate" confirmText="Mark this category as inactive?" />
          <DeleteButton action={onArchive} label="Archive" confirmText="Archive this category?" />
        </>
      ) : null}
      {value === STATUS.INACTIVE ? (
        <>
          <DeleteButton action={onActivate} label="Activate" confirmText="Activate this category?" />
          <DeleteButton action={onArchive} label="Archive" confirmText="Archive this category?" />
        </>
      ) : null}
      {value === STATUS.ARCHIVED ? (
        <DeleteButton action={onRestore} label="Restore" confirmText="Restore this category to active?" />
      ) : null}
      <DeleteButton
        action={onDelete}
        label="Delete"
        icon="delete"
        confirmText="Permanently delete this category? This cannot be undone."
      />
    </div>
  );
}

export function SubjectsAdminControls({
  categories,
}: {
  categories: SubjectCategoryRow[];
}) {
  const router = useRouter();
  const [open, setOpen] = useState(false);
  const [editingId, setEditingId] = useState<number | "new" | null>(null);
  const [categoryError, setCategoryError] = useState("");

  function closeAll() {
    setOpen(false);
    setEditingId(null);
    setCategoryError("");
  }

  function closeEditor() {
    setEditingId(null);
    setCategoryError("");
  }

  function refresh() {
    router.refresh();
  }

  const editing =
    editingId === "new"
      ? null
      : categories.find((c) => c.sc_id === editingId) || null;
  const editorOpen = open && editingId != null;

  return (
    <>
      <div className="admin-actions">
        <button
          type="button"
          className="admin-btn admin-btn-secondary"
          onClick={() => {
            setOpen(true);
            setEditingId(null);
            setCategoryError("");
          }}
        >
          <FolderTree size={16} strokeWidth={1.75} aria-hidden />
          Categories
        </button>
      </div>

      <AdminModal
        open={open}
        title="Subject categories"
        wide
        onClose={() => {
          if (editingId != null) {
            closeEditor();
            return;
          }
          closeAll();
        }}
      >
        <div className="admin-careers-modal-toolbar">
          <p className="admin-field-hint" style={{ margin: 0 }}>
            Manage categories used when creating subjects. Empty Chinese or Bahasa Melayu falls back to English.
          </p>
          <button
            type="button"
            className="admin-btn admin-btn-primary admin-btn-sm"
            onClick={() => {
              setEditingId("new");
              setCategoryError("");
            }}
          >
            Add category
          </button>
        </div>

        <div className="admin-table-wrap" style={{ marginTop: 12 }}>
          <table className="admin-table">
            <thead>
              <tr>
                <th>Name</th>
                <th className="admin-table-status">Status</th>
                <th className="admin-table-actions" />
              </tr>
            </thead>
            <tbody>
              {!categories.length ? (
                <tr>
                  <td colSpan={3}>
                    <p className="admin-empty">No categories yet.</p>
                  </td>
                </tr>
              ) : (
                categories.map((cat) => (
                  <tr key={cat.sc_id} className="admin-table-row">
                    <td>{cat.sc_name}</td>
                    <td className="admin-table-status">{statusLabel(cat.sc_status)}</td>
                    <td className="admin-table-actions">
                      <CategoryStatusActions
                        status={cat.sc_status}
                        onEdit={() => {
                          setEditingId(cat.sc_id);
                          setCategoryError("");
                        }}
                        onActivate={async () => {
                          await activateSubjectCategory(cat.sc_id);
                          refresh();
                        }}
                        onDeactivate={async () => {
                          await deactivateSubjectCategory(cat.sc_id);
                          refresh();
                        }}
                        onArchive={async () => {
                          await archiveSubjectCategory(cat.sc_id);
                          refresh();
                        }}
                        onRestore={async () => {
                          await restoreSubjectCategory(cat.sc_id);
                          refresh();
                        }}
                        onDelete={async () => {
                          await deleteSubjectCategory(cat.sc_id);
                          refresh();
                        }}
                      />
                    </td>
                  </tr>
                ))
              )}
            </tbody>
          </table>
        </div>
      </AdminModal>

      <AdminModal
        open={editorOpen}
        title={editing ? "Edit category" : "Add category"}
        layer={2}
        onClose={closeEditor}
      >
        <form
          className="admin-careers-category-form"
          key={editingId === "new" ? "new" : `edit-${editingId}`}
          action={async (formData) => {
            setCategoryError("");
            try {
              await saveSubjectCategory(formData);
              closeEditor();
              refresh();
            } catch (err) {
              setCategoryError(err instanceof Error ? err.message : "Save failed");
            }
          }}
        >
          {editing ? <input type="hidden" name="sc_id" value={editing.sc_id} /> : null}
          <AdminLocalizedField
            name="sc_name"
            label="Name"
            span={12}
            required
            defaultValue={editing?.sc_name || ""}
            defaultValueCh={editing?.nameCh || ""}
            defaultValueBm={editing?.nameBm || ""}
          />
          <label className="form-label" htmlFor="sc_status_modal">
            Status
          </label>
          <select
            id="sc_status_modal"
            name="sc_status"
            className="form-control"
            defaultValue={String(editing?.sc_status ?? STATUS.ACTIVE)}
          >
            <option value={STATUS.ACTIVE}>Active</option>
            <option value={STATUS.INACTIVE}>Inactive</option>
            <option value={STATUS.ARCHIVED}>Archived</option>
          </select>
          {categoryError ? (
            <p className="admin-localized-error" role="alert">
              {categoryError}
            </p>
          ) : null}
          <div className="admin-careers-modal-toolbar" style={{ marginTop: 12 }}>
            <button type="button" className="admin-btn admin-btn-secondary admin-btn-sm" onClick={closeEditor}>
              Cancel
            </button>
            <SubmitButton label={editing ? "Save category" : "Create category"} />
          </div>
        </form>
      </AdminModal>
    </>
  );
}
