import { notFound } from "next/navigation";
import { requireAdmin } from "@/lib/admin/auth";
import { createServiceClient } from "@/lib/supabase/server";
import { PERSONALITY_TYPES, optionsFromRecord } from "@/lib/admin/constants";
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
  const [{ data: row }, { data: characters }] = await Promise.all([
    supabase.from("personalities").select("*").eq("p_id", Number(id)).maybeSingle(),
    // Include inactive/archived so linked character names still resolve in the select.
    supabase.from("characters").select("c_id,c_name,c_status").order("c_name"),
  ]);
  if (!row) notFound();

  const characterOptions = (characters || [])
    .map((c) => ({
      value: String(c.c_id),
      label: String(c.c_name || "").trim(),
    }))
    .filter((c) => c.label);

  return (
    <>
      <AdminPageHeader title="Edit personality" />
      <AdminForm action={saveItem}>
            <AdminFormSection title="Basics" description="Name, linked characters, type, and status.">
            <input type="hidden" name="p_id" value={row.p_id} />
            <AdminLocalizedField
              name="p_name"
              label="Name"
              span={8}
              required
              defaultValue={row.p_name || ""}
              defaultValueCh={i18nFieldValue(row, "p_name", "ch")}
              defaultValueBm={i18nFieldValue(row, "p_name", "bm")}
            />
            <AdminField label="Character" span={6}>
              <AdminSelect
                name="p_character"
                multiple
                placeholder="Search characters..."
                defaultValue={row.p_character || ""}
                options={characterOptions}
              />
            </AdminField>
            <AdminField label="Type" span={6}>
              <AdminSelect
                name="p_type"
                placeholder="Search type..."
                defaultValue={row.p_type || ""}
                options={optionsFromRecord(PERSONALITY_TYPES)}
              />
            </AdminField>
            <AdminStatusSelect name="p_status" value={row.p_status} />
          </AdminFormSection>
            <AdminFormActions backHref="/admin/personality">
              <SubmitButton />
            </AdminFormActions>
          </AdminForm>
    </>
  );
}
