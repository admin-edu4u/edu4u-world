import { requireAdmin } from "@/lib/admin/auth";
import { createServiceClient } from "@/lib/supabase/server";
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
import { saveJob } from "../actions";

export default async function Page() {
  await requireAdmin();
  const supabase = createServiceClient();
  const { data: cats } = await supabase.from("career_categories").select("cc_id,cc_title").order("cc_title");
  return (
    <>
      <AdminPageHeader title="Add career" />
      <AdminForm action={saveJob}>
            <AdminFormSection title="Basics" description="Title, type, location, and pay.">
            <AdminLocalizedField name="c_title" label="Title" span={8} required />
            <AdminField label="Type" span={6}>
              <AdminSelect
                name="c_type"
                defaultValue={1}
                placeholder="Search type..."
                options={[
                  { value: "1", label: "Full-time" },
                  { value: "2", label: "Part-time" },
                ]}
              />
            </AdminField>
            <AdminField label="Location" span={6}>
              <input name="c_state" className="form-control" />
            </AdminField>
            <AdminField label="Salary min" span={6}>
              <input name="c_salary_min" type="number" className="form-control" defaultValue={0} />
            </AdminField>
            <AdminField label="Salary max" span={6}>
              <input name="c_salary_max" type="number" className="form-control" defaultValue={0} />
            </AdminField>
          </AdminFormSection>

            <AdminFormSection title="Settings" description="Category, status, and application URL.">
            <AdminField label="Category" span={4}>
              <AdminSelect
                name="c_category"
                placeholder="Search category..."
                options={(cats || []).map((c) => ({ value: String(c.cc_id), label: c.cc_title }))}
              />
            </AdminField>
            <AdminStatusSelect name="c_status" />
            <AdminField label="URL" span={12}>
              <input name="c_url" className="form-control" />
            </AdminField>
          </AdminFormSection>
            <AdminFormActions backHref="/admin/job">
              <SubmitButton />
            </AdminFormActions>
          </AdminForm>
    </>
  );
}
