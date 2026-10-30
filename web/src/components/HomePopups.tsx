"use client";

import { useEffect, useState } from "react";
import { useMemberAuth } from "@/components/MemberAuthProvider";

export type HomePopupItem = {
  id: number;
  img: string;
  url: string;
};

type Props = {
  items: HomePopupItem[];
};

export function HomePopups({ items }: Props) {
  const { member } = useMemberAuth();
  const [index, setIndex] = useState(0);
  const [open, setOpen] = useState(false);

  useEffect(() => {
    if (member) {
      setOpen(false);
      return;
    }
    if (!items.length) {
      setOpen(false);
      return;
    }
    setIndex(0);
    setOpen(true);
  }, [items, member]);

  const dismiss = useCallback(() => {
    if (index + 1 >= items.length) {
      setOpen(false);
      return;
    }
    setIndex((current) => current + 1);
  }, [index, items.length]);

  useEffect(() => {
    if (!open) return;
    const onKey = (e: KeyboardEvent) => {
      if (e.key === "Escape") dismiss();
    };
    window.addEventListener("keydown", onKey);
    return () => window.removeEventListener("keydown", onKey);
  }, [open, dismiss]);

  if (!open || !items.length) return null;
  const item = items[index];
  if (!item) return null;

  return (
    <div
      className="home-popup-overlay"
      role="dialog"
      aria-modal="true"
      aria-label="Promotion"
      onClick={(e) => {
        if (e.target === e.currentTarget) dismiss();
      }}
    >
      <div className="home-popup">
        <button type="button" className="home-popup-close" aria-label="Close" onClick={dismiss}>
          ×
        </button>
        <a href={item.url} target="_blank" rel="noopener noreferrer">
          {/* eslint-disable-next-line @next/next/no-img-element */}
          <img src={item.img} alt="" />
        </a>
      </div>
    </div>
  );
}
