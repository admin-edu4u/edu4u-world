import { requireAdmin } from "@/lib/admin/auth";
import { createServiceClient } from "@/lib/supabase/server";
import {
  SCHOLARSHIP_COVERAGE,
  SCHOLARSHIP_REQUIREMENTS,
  SCHOLARSHIP_TYPES,
  optionsFromRecord,
} from "@/lib/admin/constants";
import {
  AdminField,
  AdminForm,
  AdminFormActions,
  AdminFormSection,
  AdminImageField,
  AdminLocalizedField,
  AdminPageHeader,
  AdminSelect,
  AdminStatusSelect,
  AdminYesNoSelect,
} from "@/components/admin/AdminUi";
import { SubmitButton } from "@/components/admin/DeleteButton";
import { saveK12Scholarship } from "../actions";

export default async function Page() {
  await requireAdmin();
  const supabase = createServiceClient();
  const { data: schools } = await supabase
    .from("international_school")
    .select("int_id,int_name")
    .eq("int_status", 1)
    .order("int_name");
  return (
    <>
      <AdminPageHeader title="Add K-12 scholarship" />
      <AdminForm action={saveK12Scholarship} encType="multipart/form-data">
            <AdminFormSection title="Basics" description="Core details for this record.">
            <AdminLocalizedField name="s_title" label="Title" span={8} required />
            <AdminField label="School" span={4}>
              <AdminSelect
                name="s_int_id"
                placeholder="Search school..."
                options={(schools || []).map((s) => ({ value: String(s.int_id), label: s.int_name }))}
              />
            </AdminField>
            <AdminStatusSelect name="s_status" />
            <AdminYesNoSelect name="s_is_corporate" label="Corporate" value={0} />
            <AdminLocalizedField name="s_c_name" label="Company" span={6} />
            <AdminYesNoSelect name="s_has_interview" label="Interview" value={0} />
            <AdminImageField name="logo" label="Logo" folder="scholarships" />
          </AdminFormSection>

            <AdminFormSection title="Classification" description="Type, coverage, and requirements.">
            <AdminField label="Type" span={6}>
              <AdminSelect
                name="s_type"
                multiple
                placeholder="Search types..."
                options={optionsFromRecord(SCHOLARSHIP_TYPES)}
              />
            </AdminField>
            <AdminField label="Coverage" span={6}>
              <AdminSelect
                name="s_coverage"
                multiple
                placeholder="Search coverage..."
                options={optionsFromRecord(SCHOLARSHIP_COVERAGE)}
              />
            </AdminField>
            <AdminField label="Requirement" span={6}>
              <AdminSelect
                name="s_requirement"
                multiple
                placeholder="Search requirements..."
                options={optionsFromRecord(SCHOLARSHIP_REQUIREMENTS)}
              />
            </AdminField>
          </AdminFormSection>

            <AdminFormSection title="Content" description="Long-form text for the public page.">
            <AdminLocalizedField name="s_criteria" label="Criteria" span={12} kind="textarea" rows={3} />
            <AdminLocalizedField name="s_details" label="Details" span={12} kind="textarea" rows={3} />
            <AdminLocalizedField name="s_application" label="Application" span={12} kind="textarea" rows={3} />
          </AdminFormSection>
            <AdminFormActions backHref="/admin/k12scholarship">
              <SubmitButton />
            </AdminFormActions>
          </AdminForm>
    </>
  );
}
