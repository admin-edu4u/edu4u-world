import { notFound } from "next/navigation";
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
import { i18nFieldValue } from "@/lib/i18n/cms";
import { saveK12Scholarship } from "../actions";

export default async function Page({ params }: { params: Promise<{ id: string }> }) {
  await requireAdmin();
  const { id } = await params;
  const supabase = createServiceClient();
  const [{ data: row }, { data: schools }] = await Promise.all([
    supabase.from("int_scholarship").select("*").eq("s_id", Number(id)).maybeSingle(),
    supabase.from("international_school").select("int_id,int_name").order("int_name"),
  ]);
  if (!row) notFound();
  return (
    <>
      <AdminPageHeader title="Edit K-12 scholarship" />
      <AdminForm action={saveK12Scholarship} encType="multipart/form-data">
            <AdminFormSection title="Basics" description="Core details for this record.">
            <input type="hidden" name="s_id" value={row.s_id} />
            <AdminLocalizedField
              name="s_title"
              label="Title"
              span={8}
              required
              defaultValue={row.s_title || ""}
              defaultValueCh={i18nFieldValue(row, "s_title", "ch")}
              defaultValueBm={i18nFieldValue(row, "s_title", "bm")}
            />
            <AdminField label="School" span={4}>
              <AdminSelect
                name="s_int_id"
                placeholder="Search school..."
                defaultValue={row.s_int_id || ""}
                options={(schools || []).map((s) => ({ value: String(s.int_id), label: s.int_name }))}
              />
            </AdminField>
            <AdminStatusSelect name="s_status" value={row.s_status} />
            <AdminYesNoSelect name="s_is_corporate" label="Corporate" value={row.s_is_corporate} />
            <AdminLocalizedField
              name="s_c_name"
              label="Company"
              span={6}
              defaultValue={row.s_c_name || ""}
              defaultValueCh={i18nFieldValue(row, "s_c_name", "ch")}
              defaultValueBm={i18nFieldValue(row, "s_c_name", "bm")}
            />
            <AdminYesNoSelect name="s_has_interview" label="Interview" value={row.s_has_interview} />
            <AdminImageField
              name="logo"
              label="Logo"
              folder="scholarships"
              filename={row.s_c_logo}
             
            />
          </AdminFormSection>

            <AdminFormSection title="Classification" description="Type, coverage, and requirements.">
            <AdminField label="Type" span={6}>
              <AdminSelect
                name="s_type"
                multiple
                placeholder="Search types..."
                defaultValue={row.s_type || ""}
                options={optionsFromRecord(SCHOLARSHIP_TYPES)}
              />
            </AdminField>
            <AdminField label="Coverage" span={6}>
              <AdminSelect
                name="s_coverage"
                multiple
                placeholder="Search coverage..."
                defaultValue={row.s_coverage || ""}
                options={optionsFromRecord(SCHOLARSHIP_COVERAGE)}
              />
            </AdminField>
            <AdminField label="Requirement" span={6}>
              <AdminSelect
                name="s_requirement"
                multiple
                placeholder="Search requirements..."
                defaultValue={row.s_requirement || ""}
                options={optionsFromRecord(SCHOLARSHIP_REQUIREMENTS)}
              />
            </AdminField>
          </AdminFormSection>

            <AdminFormSection title="Content" description="Long-form text for the public page.">
            <AdminLocalizedField
              name="s_criteria"
              label="Criteria"
              span={12}
              kind="textarea"
              rows={3}
              defaultValue={row.s_criteria || ""}
              defaultValueCh={i18nFieldValue(row, "s_criteria", "ch")}
              defaultValueBm={i18nFieldValue(row, "s_criteria", "bm")}
            />
            <AdminLocalizedField
              name="s_details"
              label="Details"
              span={12}
              kind="textarea"
              rows={3}
              defaultValue={row.s_details || ""}
              defaultValueCh={i18nFieldValue(row, "s_details", "ch")}
              defaultValueBm={i18nFieldValue(row, "s_details", "bm")}
            />
            <AdminLocalizedField
              name="s_application"
              label="Application"
              span={12}
              kind="textarea"
              rows={3}
              defaultValue={row.s_application || ""}
              defaultValueCh={i18nFieldValue(row, "s_application", "ch")}
              defaultValueBm={i18nFieldValue(row, "s_application", "bm")}
            />
          </AdminFormSection>
            <AdminFormActions backHref="/admin/k12scholarship">
              <SubmitButton />
            </AdminFormActions>
          </AdminForm>
    </>
  );
}