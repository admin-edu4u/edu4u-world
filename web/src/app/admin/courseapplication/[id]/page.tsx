import { notFound } from "next/navigation";
import { requireAdmin } from "@/lib/admin/auth";
import { createServiceClient } from "@/lib/supabase/server";
import {
  AdminCard,
  AdminDetailList,
  AdminBtn,
  AdminPageHeader,
} from "@/components/admin/AdminUi";

export default async function Page({ params }: { params: Promise<{ id: string }> }) {
  await requireAdmin();
  const { id } = await params;
  const supabase = createServiceClient();
  const { data: row } = await supabase.from("course_application").select("*").eq("ca_id", Number(id)).maybeSingle();
  if (!row) notFound();
  return (
    <>
      <AdminPageHeader
        title={String(row.ca_name || "Course application")}
        actions={<AdminBtn href="/admin/courseapplication" variant="secondary">Back</AdminBtn>}
      />
      <AdminCard>
        <AdminDetailList
          items={Object.entries(row).map(([k, v]) => ({
            label: k,
            value: v == null ? "" : String(v),
          }))}
        />
      </AdminCard>
    </>
  );
}
