import { notFound } from "next/navigation";
import { requireAdmin } from "@/lib/admin/auth";
import { createServiceClient } from "@/lib/supabase/server";
import {
  AdminForm,
  AdminFormActions,
  AdminFormSection,
  AdminLocalizedField,
  AdminPageHeader,
  AdminStatusSelect,
} from "@/components/admin/AdminUi";
import { SubmitButton } from "@/components/admin/DeleteButton";
import { i18nFieldValue } from "@/lib/i18n/cms";
import { saveItem } from "../actions";

export default async function Page({ params }: { params: Promise<{ id: string }> }) {
  await requireAdmin();
  const { id } = await params;
  const supabase = createServiceClient();
  const { data: row } = await supabase
    .from("blog_cats")
    .select("*")
    .eq("bc_id", Number(id))
    .maybeSingle();
  if (!row) notFound();

  return (
    <>
      <AdminPageHeader title="Edit blog category" />
      <AdminForm action={saveItem}>
            <AdminFormSection title="Basics" description="Name and status.">
            <input type="hidden" name="bc_id" value={row.bc_id} />
            <AdminLocalizedField
              name="bc_name"
              label="Name"
              span={8}
              required
              defaultValue={row.bc_name || ""}
              defaultValueCh={i18nFieldValue(row, "bc_name", "ch")}
              defaultValueBm={i18nFieldValue(row, "bc_name", "bm")}
            />
            <AdminStatusSelect name="bc_status" value={row.bc_status} />
          </AdminFormSection>
            <AdminFormActions backHref="/admin/blog">
              <SubmitButton />
            </AdminFormActions>
          </AdminForm>
    </>
  );
}