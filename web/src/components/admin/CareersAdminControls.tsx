"use client";

import { useEffect, useState, useTransition } from "react";
import { useRouter } from "next/navigation";
import { FolderTree, Settings } from "lucide-react";
import { AdminLocalizedField } from "@/components/admin/AdminLocalizedField";
import { AdminModal } from "@/components/admin/AdminModal";
import { DeleteButton, SubmitButton } from "@/components/admin/DeleteButton";
import { statusLabel, STATUS } from "@/lib/admin/status";
import {
  activateCareerCategory,
  archiveCareerCategory,
  deactivateCareerCategory,
  deleteCareerCategory,
  restoreCareerCategory,
  saveCareerCategory,
  setCareersPageHidden,
} from "@/app/admin/job/actions";

export type CareerCategoryRow = {
  cc_id: number;
  cc_title: string;
  cc_status: number | null;
  titleCh?: string;
  titleBm?: string;
};

type ModalKind = "categories" | "settings" | null;

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

export function CareersAdminControls({
  categories,
  careersHidden,
}: {
  categories: CareerCategoryRow[];
  careersHidden: boolean;
}) {
  const router = useRouter();
  const [modal, setModal] = useState<ModalKind>(null);
  const [editingId, setEditingId] = useState<number | "new" | null>(null);
  const [hidden, setHidden] = useState(careersHidden);
  const [settingsError, setSettingsError] = useState("");
  const [categoryError, setCategoryError] = useState("");
  const [pending, startTransition] = useTransition();

  useEffect(() => {
    setHidden(careersHidden);
  }, [careersHidden]);

  function closeAll() {
    setModal(null);
    setEditingId(null);
    setCategoryError("");
    setSettingsError("");
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
      : categories.find((c) => c.cc_id === editingId) || null;
  const editorOpen = modal === "categories" && editingId != null;

  return (
    <>
      <div className="admin-actions">
        <button
          type="button"
          className="admin-btn admin-btn-secondary"
          onClick={() => {
            setModal("categories");
            setEditingId(null);
            setCategoryError("");
          }}
        >
          <FolderTree size={16} strokeWidth={1.75} aria-hidden />
          Categories
        </button>
        <button
          type="button"
          className="admin-btn admin-btn-secondary"
          onClick={() => {
            setModal("settings");
            setSettingsError("");
          }}
        >
          <Settings size={16} strokeWidth={1.75} aria-hidden />
          Settings
        </button>
        <a href="/admin/job/new" className="admin-btn admin-btn-primary">
          Add career
        </a>
      </div>

      <AdminModal
        open={modal === "categories"}
        title="Career categories"
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
            Manage categories used when creating careers. Empty Chinese or Bahasa Melayu falls back to English.
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
                <th>Title</th>
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
                  <tr key={cat.cc_id} className="admin-table-row">
                    <td>{cat.cc_title}</td>
                    <td className="admin-table-status">{statusLabel(cat.cc_status)}</td>
                    <td className="admin-table-actions">
                      <CategoryStatusActions
                        status={cat.cc_status}
                        onEdit={() => {
                          setEditingId(cat.cc_id);
                          setCategoryError("");
                        }}
                        onActivate={async () => {
                          await activateCareerCategory(cat.cc_id);
                          refresh();
                        }}
                        onDeactivate={async () => {
                          await deactivateCareerCategory(cat.cc_id);
                          refresh();
                        }}
                        onArchive={async () => {
                          await archiveCareerCategory(cat.cc_id);
                          refresh();
                        }}
                        onRestore={async () => {
                          await restoreCareerCategory(cat.cc_id);
                          refresh();
                        }}
                        onDelete={async () => {
                          await deleteCareerCategory(cat.cc_id);
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
              await saveCareerCategory(formData);
              closeEditor();
              refresh();
            } catch (err) {
              setCategoryError(err instanceof Error ? err.message : "Save failed");
            }
          }}
        >
          {editing ? <input type="hidden" name="cc_id" value={editing.cc_id} /> : null}
          <AdminLocalizedField
            name="cc_title"
            label="Title"
            span={12}
            required
            defaultValue={editing?.cc_title || ""}
            defaultValueCh={editing?.titleCh || ""}
            defaultValueBm={editing?.titleBm || ""}
          />
          <label className="form-label" htmlFor="cc_status_modal">
            Status
          </label>
          <select
            id="cc_status_modal"
            name="cc_status"
            className="form-control"
            defaultValue={String(editing?.cc_status ?? STATUS.ACTIVE)}
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

      <AdminModal open={modal === "settings"} title="Careers settings" onClose={closeAll}>
        <div className="admin-careers-settings">
          <div className="admin-careers-settings-row">
            <div>
              <div className="admin-careers-settings-label">Hide careers page</div>
              <p className="admin-field-hint" style={{ margin: "4px 0 0" }}>
                When on, the public careers page and careers links are hidden site-wide.
              </p>
            </div>
            <button
              type="button"
              className={`admin-toggle${hidden ? " is-on" : ""}`}
              role="switch"
              aria-checked={hidden}
              disabled={pending}
              onClick={() => {
                const next = !hidden;
                setHidden(next);
                setSettingsError("");
                startTransition(async () => {
                  try {
                    await setCareersPageHidden(next);
                    refresh();
                  } catch (err) {
                    setHidden(!next);
                    setSettingsError(err instanceof Error ? err.message : "Could not update setting");
                  }
                });
              }}
            >
              <span className="admin-toggle-knob" />
            </button>
          </div>
          {settingsError ? (
            <p className="admin-localized-error" role="alert">
              {settingsError}
            </p>
          ) : null}
        </div>
      </AdminModal>
    </>
  );
}
