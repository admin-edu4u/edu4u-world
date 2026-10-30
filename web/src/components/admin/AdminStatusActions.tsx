import { AdminIconLink } from "./AdminIconLink";
import { DeleteButton } from "./DeleteButton";
import { STATUS } from "@/lib/admin/status";

export function AdminStatusActions({
  status,
  editHref,
  leading,
  onActivate,
  onDeactivate,
  onArchive,
  onRestore,
  onDelete,
}: {
  status: number | null | undefined;
  editHref?: string;
  leading?: React.ReactNode;
  onActivate: () => Promise<void>;
  onDeactivate: () => Promise<void>;
  onArchive: () => Promise<void>;
  onRestore: () => Promise<void>;
  onDelete?: () => Promise<void>;
}) {
  const value = Number(status ?? STATUS.ACTIVE);

  return (
    <div className="admin-actions">
      {leading}
      {editHref ? <AdminIconLink href={editHref} /> : null}
      {value === STATUS.ACTIVE ? (
        <>
          <DeleteButton
            action={onDeactivate}
            label="Deactivate"
            confirmText="Mark this record as inactive?"
          />
          <DeleteButton
            action={onArchive}
            label="Archive"
            confirmText="Archive this record?"
          />
        </>
      ) : null}
      {value === STATUS.INACTIVE ? (
        <>
          <DeleteButton
            action={onActivate}
            label="Activate"
            confirmText="Activate this record?"
          />
          <DeleteButton
            action={onArchive}
            label="Archive"
            confirmText="Archive this record?"
          />
        </>
      ) : null}
      {value === STATUS.ARCHIVED ? (
        <DeleteButton
          action={onRestore}
          label="Restore"
          confirmText="Restore this record to active?"
        />
      ) : null}
      {value !== STATUS.ACTIVE && value !== STATUS.INACTIVE && value !== STATUS.ARCHIVED ? (
        <>
          <DeleteButton action={onActivate} label="Activate" confirmText="Activate this record?" />
          <DeleteButton action={onArchive} label="Archive" confirmText="Archive this record?" />
        </>
      ) : null}
      {onDelete ? (
        <DeleteButton
          action={onDelete}
          label="Delete"
          icon="delete"
          confirmText="Permanently delete this record? This cannot be undone."
        />
      ) : null}
    </div>
  );
}
