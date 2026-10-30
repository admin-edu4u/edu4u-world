import { requireAdmin } from "@/lib/admin/auth";
import { createServiceClient } from "@/lib/supabase/server";
import { COURSE_LEVELS, INTAKE_MONTHS, STREAM_OPTIONS, optionsFromRecord } from "@/lib/admin/constants";
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
import { saveCourse } from "../actions";

export default async function Page() {
  await requireAdmin();
  const supabase = createServiceClient();
  const [{ data: unis }, { data: cats }, { data: subCats }] = await Promise.all([
    supabase.from("universities").select("u_id,u_name").eq("u_status", 1).order("u_name"),
    supabase.from("categories").select("c_id,c_name").eq("c_status", 1).order("c_name"),
    supabase.from("sub_cats").select("sc_id,sc_name").order("sc_name"),
  ]);
  return (
    <>
      <AdminPageHeader title="Add course" />
      <AdminForm action={saveCourse}>
            <AdminFormSection title="Basics" description="Name, university, and status.">
            <AdminLocalizedField name="c_name" label="Name" span={8} required />
            <AdminField label="University" span={4}>
              <AdminSelect
                name="c_uni"
                required
                placeholder="Search university..."
                options={(unis || []).map((u) => ({ value: String(u.u_id), label: u.u_name }))}
              />
            </AdminField>
            <AdminStatusSelect name="c_status" />
          </AdminFormSection>

            <AdminFormSection title="Program" description="Level, stream, duration, and categories.">
            <AdminField label="Level" span={6}>
              <AdminSelect
                name="c_level"
                placeholder="Search level..."
                options={Object.entries(COURSE_LEVELS).map(([k, v]) => ({ value: k, label: v }))}
              />
            </AdminField>
            <AdminField label="Stream" span={6}>
              <AdminSelect
                name="c_stream"
                placeholder="Search stream..."
                defaultValue={0}
                options={optionsFromRecord(STREAM_OPTIONS)}
              />
            </AdminField>
            <AdminField label="Duration" span={6}>
              <input name="c_duration" className="form-control" />
            </AdminField>
            <AdminLocalizedField name="c_award" label="Award" span={6} />
          </AdminFormSection>

            <AdminFormSection title="Fees & intake" description="Pricing, audience, and intake months.">
            <AdminField label="Audience" span={6}>
              <AdminSelect
                name="c_is_malaysian"
                defaultValue={2}
                placeholder="Search audience..."
                options={[
                  { value: "0", label: "International" },
                  { value: "1", label: "Malaysian" },
                  { value: "2", label: "Both" },
                ]}
              />
            </AdminField>
            <AdminField label="Amount" span={6}>
              <input name="c_amount" type="number" className="form-control" defaultValue={0} />
            </AdminField>
            <AdminField label="Amount bumi" span={6}>
              <input name="c_amount_bumi" type="number" className="form-control" defaultValue={0} />
            </AdminField>
            <AdminField label="Amount intl" span={6}>
              <input name="c_amount_international" type="number" className="form-control" defaultValue={0} />
            </AdminField>
            <AdminField label="Main category" span={6}>
              <AdminSelect
                name="c_main_cat"
                placeholder="Search category..."
                emptyLabel="None"
                options={(cats || []).map((c) => ({ value: String(c.c_id), label: c.c_name }))}
              />
            </AdminField>
            <AdminField label="Sub category" span={6}>
              <AdminSelect
                name="c_sub_cat"
                placeholder="Search sub category..."
                emptyLabel="None"
                defaultValue=""
                options={(subCats || []).map((c) => ({ value: String(c.sc_id), label: c.sc_name }))}
              />
            </AdminField>
            <AdminYesNoSelect name="c_has_internship" label="Internship" value={0} />
            <AdminLocalizedField name="c_internship_duration" label="Intern duration" span={6} />
            <AdminField label="Intake months" span={12}>
              <AdminSelect
                name="c_intake"
                multiple
                placeholder="Search intake months..."
                options={optionsFromRecord(INTAKE_MONTHS)}
              />
            </AdminField>
          </AdminFormSection>

            <AdminFormSection title="Content" description="Outline, requirements, and modules.">
            <AdminLocalizedField name="c_outline" label="Outline" span={12} kind="textarea" rows={3} />
            <AdminLocalizedField name="c_entry_req" label="Entry requirements" span={12} kind="textarea" rows={3} />
            <AdminLocalizedField name="c_english_required" label="English required" span={12} />
            <AdminLocalizedField name="c_core" label="Core modules" span={12} kind="richtext" />
            <AdminLocalizedField name="c_elective" label="Elective modules" span={12} kind="richtext" />
            <AdminLocalizedField name="c_other" label="Other modules" span={12} kind="richtext" />
          </AdminFormSection>
            <AdminFormActions backHref="/admin/course">
              <SubmitButton />
            </AdminFormActions>
          </AdminForm>
    </>
  );
}
