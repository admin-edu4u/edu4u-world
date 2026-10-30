import { Figtree } from "next/font/google";
import "./admin.css";
import { requireAdmin } from "@/lib/admin/auth";
import { AdminShell } from "@/components/admin/AdminShell";

const figtree = Figtree({
  subsets: ["latin"],
  weight: ["400", "500", "600", "700"],
  variable: "--admin-font-family",
  display: "swap",
});

export default async function AdminLayout({ children }: { children: React.ReactNode }) {
  const { getAdminSession } = await import("@/lib/admin/session");
  const session = await getAdminSession();

  if (!session) {
    return <div className={`${figtree.variable} admin-font-scope`}>{children}</div>;
  }

  await requireAdmin();
  return (
    <div className={`${figtree.variable} admin-font-scope`}>
      <AdminShell username={session.username}>{children}</AdminShell>
    </div>
  );
}
