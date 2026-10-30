"use client";

import Link from "next/link";
import { Eye, Pencil, Plus, type LucideIcon } from "lucide-react";
import { AdminTooltip } from "./AdminTooltip";

const ICONS = {
  edit: Pencil,
  view: Eye,
  add: Plus,
} as const;

export function AdminIconLink({
  href,
  label = "Edit",
  icon = "edit",
}: {
  href: string;
  label?: string;
  icon?: keyof typeof ICONS;
}) {
  const Icon: LucideIcon = ICONS[icon];
  return (
    <AdminTooltip label={label}>
      {(tip) => (
        <Link
          href={href}
          className="admin-icon-btn"
          aria-label={tip["aria-label"]}
          aria-describedby={tip["aria-describedby"]}
          onMouseEnter={tip.onMouseEnter}
          onMouseLeave={tip.onMouseLeave}
          onFocus={tip.onFocus}
          onBlur={tip.onBlur}
          onClick={(e) => e.stopPropagation()}
        >
          <Icon size={16} strokeWidth={1.75} aria-hidden />
        </Link>
      )}
    </AdminTooltip>
  );
}
