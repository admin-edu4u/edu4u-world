"use client";

import {
  useId,
  useState,
  type FocusEvent,
  type MouseEvent,
  type ReactNode,
} from "react";
import { createPortal } from "react-dom";

type Pos = { top: number; left: number };

export type AdminTooltipChildProps = {
  "aria-label": string;
  "aria-describedby"?: string;
  onMouseEnter: (e: MouseEvent<HTMLElement>) => void;
  onMouseLeave: () => void;
  onFocus: (e: FocusEvent<HTMLElement>) => void;
  onBlur: () => void;
};

export function AdminTooltip({
  label,
  children,
}: {
  label: string;
  children: (props: AdminTooltipChildProps) => ReactNode;
}) {
  const tooltipId = useId();
  const [pos, setPos] = useState<Pos | null>(null);

  function show(e: MouseEvent<HTMLElement> | FocusEvent<HTMLElement>) {
    const rect = e.currentTarget.getBoundingClientRect();
    setPos({
      top: rect.top - 8,
      left: rect.left + rect.width / 2,
    });
  }

  function hide() {
    setPos(null);
  }

  return (
    <>
      {children({
        "aria-label": label,
        "aria-describedby": pos ? tooltipId : undefined,
        onMouseEnter: show,
        onMouseLeave: hide,
        onFocus: show,
        onBlur: hide,
      })}
      {pos && typeof document !== "undefined"
        ? createPortal(
            <div
              id={tooltipId}
              role="tooltip"
              className="admin-tooltip"
              style={{ top: pos.top, left: pos.left }}
            >
              {label}
            </div>,
            document.body,
          )
        : null}
    </>
  );
}
