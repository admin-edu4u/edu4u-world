"use client";

import { useRouter } from "next/navigation";

export function AdminRow({
  href,
  children,
}: {
  href?: string;
  children: React.ReactNode;
}) {
  const router = useRouter();
  const clickable = Boolean(href);

  return (
    <tr
      className={clickable ? "admin-table-row is-clickable" : undefined}
      onClick={
        clickable
          ? (e) => {
              const target = e.target as HTMLElement;
              if (target.closest("a, button, input, select, textarea, label, .admin-table-actions")) {
                return;
              }
              router.push(href!);
            }
          : undefined
      }
    >
      {children}
    </tr>
  );
}
