"use client";

import { useEffect, useId } from "react";
import { X } from "lucide-react";

export function AdminModal({
  open,
  title,
  onClose,
  children,
  wide,
  layer = 1,
}: {
  open: boolean;
  title: string;
  onClose: () => void;
  children: React.ReactNode;
  wide?: boolean;
  /** Higher layers sit above lower ones (use 2 for edit-on-top). */
  layer?: 1 | 2;
}) {
  const titleId = useId();

  useEffect(() => {
    if (!open) return;
    const onKey = (e: KeyboardEvent) => {
      if (e.key !== "Escape") return;
      e.preventDefault();
      onClose();
    };
    // Capture so the topmost modal (registered last) can close first.
    window.addEventListener("keydown", onKey, true);
    return () => window.removeEventListener("keydown", onKey, true);
  }, [open, onClose]);

  if (!open) return null;

  return (
    <div
      className={`admin-modal admin-modal-layer-${layer}`}
      role="presentation"
    >
      <button type="button" className="admin-modal-backdrop" aria-label="Close" onClick={onClose} />
      <div
        className={`admin-modal-dialog${wide ? " admin-modal-dialog-wide" : ""}`}
        role="dialog"
        aria-modal="true"
        aria-labelledby={titleId}
      >
        <div className="admin-modal-head">
          <h2 id={titleId}>{title}</h2>
          <button
            type="button"
            className="admin-icon-btn admin-icon-btn-muted"
            aria-label="Close"
            onClick={onClose}
          >
            <X size={16} strokeWidth={1.75} aria-hidden />
          </button>
        </div>
        <div className="admin-modal-body">{children}</div>
      </div>
    </div>
  );
}
