"use client";

import { useState } from "react";
import { useRouter } from "next/navigation";
import { FolderTree } from "lucide-react";
import { AdminLocalizedField } from "@/components/admin/AdminLocalizedField";
import { AdminModal } from "@/components/admin/AdminModal";
import { DeleteButton, SubmitButton } from "@/components/admin/DeleteButton";
import { statusLabel, STATUS } from "@/lib/admin/status";
import {
  activateBlogCategory,
  archiveBlogCategory,
  deactivateBlogCategory,
  deleteBlogCategory,
  restoreBlogCategory,
  saveBlogCategory,
} from "@/app/admin/blog/actions";

export type BlogCategoryRow = {
  bc_id: number;
  bc_name: string;
  bc_status: number | null;
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

export function BlogAdminControls({
  categories,
}: {
  categories: BlogCategoryRow[];
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
      : categories.find((c) => c.bc_id === editingId) || null;
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
        <a href="/admin/blog/new" className="admin-btn admin-btn-primary">
          Add blog
        </a>
      </div>

      <AdminModal
        open={open}
        title="Blog categories"
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
            Manage categories used when creating blogs. Empty Chinese or Bahasa Melayu falls back to English.
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
                  <tr key={cat.bc_id} className="admin-table-row">
                    <td>{cat.bc_name}</td>
                    <td className="admin-table-status">{statusLabel(cat.bc_status)}</td>
                    <td className="admin-table-actions">
                      <CategoryStatusActions
                        status={cat.bc_status}
                        onEdit={() => {
                          setEditingId(cat.bc_id);
                          setCategoryError("");
                        }}
                        onActivate={async () => {
                          await activateBlogCategory(cat.bc_id);
                          refresh();
                        }}
                        onDeactivate={async () => {
                          await deactivateBlogCategory(cat.bc_id);
                          refresh();
                        }}
                        onArchive={async () => {
                          await archiveBlogCategory(cat.bc_id);
                          refresh();
                        }}
                        onRestore={async () => {
                          await restoreBlogCategory(cat.bc_id);
                          refresh();
                        }}
                        onDelete={async () => {
                          await deleteBlogCategory(cat.bc_id);
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
              await saveBlogCategory(formData);
              closeEditor();
              refresh();
            } catch (err) {
              setCategoryError(err instanceof Error ? err.message : "Save failed");
            }
          }}
        >
          {editing ? <input type="hidden" name="bc_id" value={editing.bc_id} /> : null}
          <AdminLocalizedField
            name="bc_name"
            label="Name"
            span={12}
            required
            defaultValue={editing?.bc_name || ""}
            defaultValueCh={editing?.nameCh || ""}
            defaultValueBm={editing?.nameBm || ""}
          />
          <label className="form-label" htmlFor="bc_status_modal">
            Status
          </label>
          <select
            id="bc_status_modal"
            name="bc_status"
            className="form-control"
            defaultValue={String(editing?.bc_status ?? STATUS.ACTIVE)}
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
