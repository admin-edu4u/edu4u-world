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
    .from("subject_categories")
    .select("*")
    .eq("sc_id", Number(id))
    .maybeSingle();
  if (!row) notFound();

  return (
    <>
      <AdminPageHeader title="Edit subject category" />
      <AdminForm action={saveItem}>
            <AdminFormSection title="Basics" description="Name and status.">
            <input type="hidden" name="sc_id" value={row.sc_id} />
            <AdminLocalizedField
              name="sc_name"
              label="Name"
              span={8}
              required
              defaultValue={row.sc_name || ""}
              defaultValueCh={i18nFieldValue(row, "sc_name", "ch")}
              defaultValueBm={i18nFieldValue(row, "sc_name", "bm")}
            />
            <AdminStatusSelect name="sc_status" value={row.sc_status} />
          </AdminFormSection>
            <AdminFormActions backHref="/admin/subjects">
              <SubmitButton />
            </AdminFormActions>
          </AdminForm>
    </>
  );
}
