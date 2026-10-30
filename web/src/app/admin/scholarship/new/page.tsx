import { requireAdmin } from "@/lib/admin/auth";
import { createServiceClient } from "@/lib/supabase/server";
import {
  Q_LEVELS,
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
import { saveScholarship } from "../actions";

export default async function Page() {
  await requireAdmin();
  const supabase = createServiceClient();
  const [{ data: unis }, { data: subCats }] = await Promise.all([
    supabase.from("universities").select("u_id,u_name").eq("u_status", 1).order("u_name"),
    supabase.from("sub_cats").select("sc_id,sc_name").order("sc_name"),
  ]);
  return (
    <>
      <AdminPageHeader title="Add scholarship" />
      <AdminForm action={saveScholarship} encType="multipart/form-data">
            <AdminFormSection title="Basics" description="Core details for this record.">
            <AdminLocalizedField name="s_title" label="Title" span={8} required />
            <AdminField label="University" span={4}>
              <AdminSelect
                name="s_uni_id"
                defaultValue=""
                placeholder="Search university..."
                options={[
                  { value: "0", label: "None / corporate" },
                  ...(unis || []).map((u) => ({ value: String(u.u_id), label: u.u_name })),
                ]}
              />
            </AdminField>
            <AdminStatusSelect name="s_status" />
            <AdminYesNoSelect name="s_is_corporate" label="Corporate" value={0} />
            <AdminLocalizedField name="s_c_name" label="Company name" span={6} />
            <AdminYesNoSelect name="s_has_interview" label="Interview" value={0} />
            <AdminImageField name="logo" label="Logo" folder="scholarships" />
          </AdminFormSection>

            <AdminFormSection title="Classification" description="Levels, fields, and scholarship attributes.">
            <AdminField label="Levels" span={6}>
              <AdminSelect
                name="s_level_id"
                multiple
                placeholder="Search levels..."
                options={optionsFromRecord(Q_LEVELS)}
              />
            </AdminField>
            <AdminField label="Study fields" span={6}>
              <AdminSelect
                name="s_study_field"
                multiple
                placeholder="Search study fields..."
                options={(subCats || []).map((c) => ({ value: String(c.sc_id), label: c.sc_name }))}
              />
            </AdminField>
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
            <AdminFormActions backHref="/admin/scholarship">
              <SubmitButton />
            </AdminFormActions>
          </AdminForm>
    </>
  );
}
