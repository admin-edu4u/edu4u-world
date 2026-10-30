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
import { saveItem } from "../actions";

export default async function Page() {
  await requireAdmin();
  const supabase = createServiceClient();
  const { data: characters } = await supabase
    .from("characters")
    .select("c_id,c_name")
    .order("c_name");
  const characterOptions = (characters || [])
    .map((c) => ({ value: String(c.c_id), label: String(c.c_name || "").trim() }))
    .filter((c) => c.label);

  return (
    <>
      <AdminPageHeader title="Add personality" />
      <AdminForm action={saveItem}>
        <AdminFormSection title="Basics" description="Name, linked characters, type, and status.">
          <AdminLocalizedField name="p_name" label="Name" span={8} required />
          <AdminField label="Character" span={6}>
            <AdminSelect
              name="p_character"
              multiple
              placeholder="Search characters..."
              options={characterOptions}
            />
          </AdminField>
          <AdminField label="Type" span={6}>
            <AdminSelect
              name="p_type"
              placeholder="Search type..."
              options={optionsFromRecord(PERSONALITY_TYPES)}
            />
          </AdminField>
          <AdminStatusSelect name="p_status" />
        </AdminFormSection>
        <AdminFormActions backHref="/admin/personality">
          <SubmitButton />
        </AdminFormActions>
      </AdminForm>
    </>
  );
}
