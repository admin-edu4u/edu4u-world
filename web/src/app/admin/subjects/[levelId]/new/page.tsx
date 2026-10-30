import { requireAdmin } from "@/lib/admin/auth";
import { createServiceClient } from "@/lib/supabase/server";
import { Q_LEVELS } from "@/lib/admin/constants";
import {
  AdminField,
  AdminForm,
  AdminFormActions,
  AdminFormSection,
  AdminLocalizedField,
  AdminPageHeader,
  AdminSelect,
  AdminStatusSelect,
  AdminYesNoSelect,
} from "@/components/admin/AdminUi";
import { SubmitButton } from "@/components/admin/DeleteButton";
import { saveSubject } from "../../actions";

export default async function Page({ params }: { params: Promise<{ levelId: string }> }) {
  await requireAdmin();
  const { levelId } = await params;
  const lid = Number(levelId);
  const supabase = createServiceClient();
  const { data: cats } = await supabase
    .from("subject_categories")
    .select("sc_id,sc_name")
    .order("sc_name");

  return (
    <>
      <AdminPageHeader title={`Add subject: ${Q_LEVELS[lid] || "Level"}`} />
      <AdminForm action={saveSubject}>
            <AdminFormSection title="Basics" description="Subject details for this level.">
            <input type="hidden" name="s_level_id" value={lid} />
            <AdminLocalizedField name="s_name" label="Name" span={8} required />
            <AdminField label="Category" span={4}>
              <AdminSelect
                name="s_subject_cat_id"
                placeholder="Search category..."
                options={(cats || [])
                  .map((c) => ({ value: String(c.sc_id), label: String(c.sc_name || "").trim() }))
                  .filter((c) => c.label)}
              />
            </AdminField>
            <AdminYesNoSelect name="s_is_preselect" label="Preselect" value={0} />
            <AdminStatusSelect name="s_status" />
          </AdminFormSection>
            <AdminFormActions backHref={`/admin/subjects/${levelId}`}>
              <SubmitButton />
            </AdminFormActions>
          </AdminForm>
    </>
  );
}
