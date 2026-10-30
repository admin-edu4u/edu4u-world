"use client";

import { useRouter } from "next/navigation";
import { useState } from "react";
import { useFormStatus } from "react-dom";
import {
  Archive,
  ArrowDown,
  ArrowUp,
  Ban,
  CheckCircle2,
  EyeOff,
  RotateCcw,
  Star,
  StarOff,
  Trash2,
  type LucideIcon,
} from "lucide-react";
import { AdminTooltip } from "./AdminTooltip";

const ICONS = {
  archive: Archive,
  delete: Trash2,
  hide: EyeOff,
  activate: CheckCircle2,
  deactivate: Ban,
  restore: RotateCcw,
  pin: Star,
  unpin: StarOff,
  up: ArrowUp,
  down: ArrowDown,
} as const;

type IconKind = keyof typeof ICONS;

function resolveIcon(label: string, icon?: IconKind): IconKind {
  if (icon) return icon;
  const value = label.toLowerCase();
  if (value === "delete" || value.startsWith("delete ") || value.includes("permanently")) {
    return "delete";
  }
  if (value.includes("hide")) return "hide";
  if (value === "restore" || value.startsWith("restore ")) return "restore";
  if (value === "activate" || value.startsWith("activate ")) return "activate";
  if (value.includes("deactivate") || value.includes("inactive")) return "deactivate";
  if (value === "pin") return "pin";
  if (value === "unpin") return "unpin";
  return "archive";
}

export function DeleteButton({
  action,
  label = "Archive",
  confirmText,
  icon,
  requireConfirm,
}: {
  action: () => Promise<void>;
  label?: string;
  confirmText?: string;
  icon?: IconKind;
  requireConfirm?: boolean;
}) {
  const router = useRouter();
  const [busy, setBusy] = useState(false);
  const kind = resolveIcon(label, icon);
  const Icon: LucideIcon = ICONS[kind];
  const shouldConfirm = requireConfirm ?? !["pin", "unpin", "up", "down"].includes(kind);
  const message = confirmText ?? `${label} this record?`;
  const tone =
    kind === "archive" || kind === "delete" || kind === "deactivate" || kind === "hide"
      ? "danger"
      : "muted";

  return (
    <AdminTooltip label={label}>
      {(tip) => (
        <button
          type="button"
          className={`admin-icon-btn admin-icon-btn-${tone}`}
          aria-label={tip["aria-label"]}
          aria-describedby={tip["aria-describedby"]}
          disabled={busy}
          onMouseEnter={tip.onMouseEnter}
          onMouseLeave={tip.onMouseLeave}
          onFocus={tip.onFocus}
          onBlur={tip.onBlur}
          onClick={async (e) => {
            e.stopPropagation();
            if (shouldConfirm && !confirm(message)) return;
            setBusy(true);
            try {
              await action();
              router.refresh();
            } catch (err) {
              alert(err instanceof Error ? err.message : `${label} failed`);
              setBusy(false);
            }
          }}
        >
          {busy ? (
            <span className="admin-icon-btn-busy">…</span>
          ) : (
            <Icon size={16} strokeWidth={1.75} aria-hidden />
          )}
        </button>
      )}
    </AdminTooltip>
  );
}

export function SubmitButton({
  label = "Save",
  pendingLabel = "Saving…",
}: {
  label?: string;
  pendingLabel?: string;
}) {
  // useFormStatus tracks the parent form action. Do not disable the button in
  // onClick before submit, or browsers cancel the submission and leave Saving stuck.
  const { pending } = useFormStatus();
  return (
    <button
      type="submit"
      className="admin-btn admin-btn-primary"
      disabled={pending}
      aria-busy={pending}
    >
      {pending ? pendingLabel : label}
    </button>
  );
}
