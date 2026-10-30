import { notFound } from "next/navigation";
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
import { i18nFieldValue } from "@/lib/i18n/cms";
import { saveScholarship } from "../actions";

export default async function Page({ params }: { params: Promise<{ id: string }> }) {
  await requireAdmin();
  const { id } = await params;
  const supabase = createServiceClient();
  const [{ data: unis }, { data: subCats }, { data: row }] = await Promise.all([
    supabase.from("universities").select("u_id,u_name").order("u_name"),
    supabase.from("sub_cats").select("sc_id,sc_name").order("sc_name"),
    supabase.from("scholarships").select("*").eq("s_id", Number(id)).maybeSingle(),
  ]);
  if (!row) notFound();
  return (
    <>
      <AdminPageHeader title="Edit scholarship" />
      <AdminForm action={saveScholarship} encType="multipart/form-data">
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
            <AdminField label="University" span={4}>
              <AdminSelect
                name="s_uni_id"
                placeholder="Search university..."
                defaultValue={row.s_uni_id ?? 0}
                options={[
                  { value: "0", label: "None / corporate" },
                  ...(unis || []).map((u) => ({ value: String(u.u_id), label: u.u_name })),
                ]}
              />
            </AdminField>
            <AdminStatusSelect name="s_status" value={row.s_status} />
            <AdminYesNoSelect name="s_is_corporate" label="Corporate" value={row.s_is_corporate} />
            <AdminLocalizedField
              name="s_c_name"
              label="Company name"
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

            <AdminFormSection title="Classification" description="Levels, fields, and scholarship attributes.">
            <AdminField label="Levels" span={6}>
              <AdminSelect
                name="s_level_id"
                multiple
                placeholder="Search levels..."
                defaultValue={row.s_level_id || ""}
                options={optionsFromRecord(Q_LEVELS)}
              />
            </AdminField>
            <AdminField label="Study fields" span={6}>
              <AdminSelect
                name="s_study_field"
                multiple
                placeholder="Search study fields..."
                defaultValue={row.s_study_field || ""}
                options={(subCats || []).map((c) => ({ value: String(c.sc_id), label: c.sc_name }))}
              />
            </AdminField>
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
            <AdminFormActions backHref="/admin/scholarship">
              <SubmitButton />
            </AdminFormActions>
          </AdminForm>
    </>
  );
}
