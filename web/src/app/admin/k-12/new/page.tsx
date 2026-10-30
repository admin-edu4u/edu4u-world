import { requireAdmin } from "@/lib/admin/auth";
import { createServiceClient } from "@/lib/supabase/server";
import { COUNTRY_OPTIONS, K12_LEVELS, optionsFromRecord } from "@/lib/admin/constants";
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
import { saveK12 } from "../actions";

export default async function Page() {
  await requireAdmin();
  const supabase = createServiceClient();
  const { data: states } = await supabase.from("states").select("state_id,state_name").order("state_name");
  return (
    <>
      <AdminPageHeader title="Add K-12 school" />
      <AdminForm action={saveK12} encType="multipart/form-data">
            <AdminFormSection title="Basics" description="School name, level, and status.">
            <AdminLocalizedField name="int_name" label="Name" span={8} required />
            <AdminField label="Level" span={6}>
              <AdminSelect
                name="int_level"
                multiple
                required
                placeholder="Search levels..."
                options={optionsFromRecord(K12_LEVELS)}
              />
            </AdminField>
            <AdminStatusSelect name="int_status" />
          </AdminFormSection>

            <AdminFormSection title="Stats & location" description="Size, address, and region.">
            <AdminLocalizedField name="int_year" label="Year" span={6} />
            <AdminField label="Students" span={6}>
              <input name="int_student" className="form-control" />
            </AdminField>
            <AdminField label="Staff" span={6}>
              <input name="int_staff" className="form-control" />
            </AdminField>
            <AdminLocalizedField name="int_addr" label="Address" span={6} />
            <AdminField label="State" span={6}>
              <AdminSelect
                name="int_state"
                placeholder="Search state..."
                options={(states || []).map((s) => ({ value: String(s.state_id), label: s.state_name }))}
              />
            </AdminField>
            <AdminField label="Country" span={6}>
              <AdminSelect name="int_country" placeholder="Search country..." options={COUNTRY_OPTIONS} />
            </AdminField>
          </AdminFormSection>

            <AdminFormSection title="Settings" description="Hours and branch options.">
            <AdminField label="Hours from" span={6}>
              <input name="hours1" className="form-control" />
            </AdminField>
            <AdminField label="Hours to" span={6}>
              <input name="hours2" className="form-control" />
            </AdminField>
            <AdminYesNoSelect name="int_has_branch" label="Has branch" value={0} />
          </AdminFormSection>

            <AdminFormSection title="Content & media" description="Description and images.">
            <AdminLocalizedField name="int_desc" label="Description" span={12} kind="textarea" rows={4} />
            <AdminImageField name="logo" label="Logo" folder="internationalschool/logo" />
            <AdminImageField name="img" label="Images" folder="internationalschool/images" multiple />
          </AdminFormSection>
            <AdminFormActions backHref="/admin/k-12">
              <SubmitButton />
            </AdminFormActions>
          </AdminForm>
    </>
  );
}
