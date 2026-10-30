import { notFound } from "next/navigation";
import { requireAdmin } from "@/lib/admin/auth";
import { createServiceClient } from "@/lib/supabase/server";
import { Q_LEVELS, optionsFromRecord } from "@/lib/admin/constants";
import {
  AdminField,
  AdminForm,
  AdminFormActions,
  AdminFormSection,
  AdminLocalizedField,
  AdminPageHeader,
  AdminSelect,
  AdminStatusSelect,
} from "@/components/admin/AdminUi";
import { SubmitButton } from "@/components/admin/DeleteButton";
import { i18nFieldValue } from "@/lib/i18n/cms";
import { saveItem } from "../actions";

export default async function Page({ params }: { params: Promise<{ id: string }> }) {
  await requireAdmin();
  const { id } = await params;
  const supabase = createServiceClient();
  const { data: row } = await supabase.from("schools").select("*").eq("s_id", Number(id)).maybeSingle();
  if (!row) notFound();

  return (
    <>
      <AdminPageHeader title="Edit school" />
      <AdminForm action={saveItem}>
        <AdminFormSection title="Basics" description="Name, qualifications, and status.">
          <input type="hidden" name="s_id" value={row.s_id} />
          <AdminLocalizedField
            name="s_name"
            label="Name"
            span={8}
            required
            defaultValue={row.s_name || ""}
            defaultValueCh={i18nFieldValue(row, "s_name", "ch")}
            defaultValueBm={i18nFieldValue(row, "s_name", "bm")}
          />
          <AdminField label="Qualification" span={8}>
            <AdminSelect
              name="s_qualification"
              multiple
              placeholder="Search qualifications..."
              defaultValue={row.s_qualification || ""}
              options={optionsFromRecord(Q_LEVELS)}
            />
          </AdminField>
          <AdminStatusSelect name="s_status" value={row.s_status} />
        </AdminFormSection>
        <AdminFormActions backHref="/admin/schools">
          <SubmitButton />
        </AdminFormActions>
      </AdminForm>
    </>
  );
}
