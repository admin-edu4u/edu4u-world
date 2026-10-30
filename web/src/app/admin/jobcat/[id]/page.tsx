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
    .from("career_categories")
    .select("*")
    .eq("cc_id", Number(id))
    .maybeSingle();
  if (!row) notFound();

  return (
    <>
      <AdminPageHeader title="Edit career category" />
      <AdminForm action={saveItem}>
            <AdminFormSection title="Basics" description="Title and status.">
            <input type="hidden" name="cc_id" value={row.cc_id} />
            <AdminLocalizedField
              name="cc_title"
              label="Title"
              span={8}
              required
              defaultValue={row.cc_title || ""}
              defaultValueCh={i18nFieldValue(row, "cc_title", "ch")}
              defaultValueBm={i18nFieldValue(row, "cc_title", "bm")}
            />
            <AdminStatusSelect name="cc_status" value={row.cc_status} />
          </AdminFormSection>
            <AdminFormActions backHref="/admin/job">
              <SubmitButton />
            </AdminFormActions>
          </AdminForm>
    </>
  );
}
