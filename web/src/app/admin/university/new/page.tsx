import { requireAdmin } from "@/lib/admin/auth";
import { createServiceClient } from "@/lib/supabase/server";
import { COUNTRY_OPTIONS } from "@/lib/admin/constants";
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
import { saveUniversity } from "../actions";

export default async function Page() {
  await requireAdmin();
  const supabase = createServiceClient();
  const [{ data: types }, { data: states }] = await Promise.all([
    supabase.from("university_type").select("ut_id,ut_name").order("ut_id"),
    supabase.from("states").select("state_id,state_name").order("state_name"),
  ]);
  return (
    <>
      <AdminPageHeader title="Add university" />
      <AdminForm action={saveUniversity} encType="multipart/form-data">
            <AdminFormSection title="Basics" description="Name, type, and status. Fill Chinese and Bahasa Melayu, or use Translate from English.">
            <AdminLocalizedField name="u_name" label="Name" span={12} required />
            <AdminField label="Type" span={6}>
              <AdminSelect
                name="u_type"
                placeholder="Search type..."
                options={(types || [])
                  .map((t) => ({
                    value: String(t.ut_id),
                    label: String(t.ut_name || "").trim(),
                  }))
                  .filter((t) => t.label)}
              />
            </AdminField>
            <AdminStatusSelect name="u_status" />
            <AdminYesNoSelect name="u_has_rank" label="Has rank" value={0} />
          </AdminFormSection>

            <AdminFormSection title="Stats & location" description="Ranking, size, and address.">
            <AdminLocalizedField name="u_ranking" label="Ranking" span={12} />
            <AdminField label="Students" span={6}>
              <input name="u_student" className="form-control" />
            </AdminField>
            <AdminField label="Staff" span={6}>
              <input name="u_staff" className="form-control" />
            </AdminField>
            <AdminLocalizedField name="u_year" label="Year" span={12} />
            <AdminLocalizedField name="u_addr" label="Address" span={12} />
            <AdminField label="State" span={6}>
              <AdminSelect
                name="u_state"
                placeholder="Search state..."
                options={(states || []).map((s) => ({ value: String(s.state_id), label: s.state_name }))}
              />
            </AdminField>
            <AdminField label="Country" span={6}>
              <AdminSelect
                name="u_country"
                placeholder="Search country..."
                options={COUNTRY_OPTIONS}
              />
            </AdminField>
          </AdminFormSection>

            <AdminFormSection title="Settings" description="Hours and branch options.">
            <AdminField label="Hours from" span={6}>
              <input name="hours1" className="form-control" />
            </AdminField>
            <AdminField label="Hours to" span={6}>
              <input name="hours2" className="form-control" />
            </AdminField>
            <AdminYesNoSelect name="u_has_branch" label="Has branch" value={0} />
          </AdminFormSection>

            <AdminFormSection title="Content & media" description="Description and images.">
            <AdminLocalizedField name="u_desc" label="Description" span={12} kind="textarea" rows={4} />
            <AdminImageField name="logo" label="Logo" folder="university/logo" />
            <AdminImageField name="img" label="Images" folder="university/images" multiple />
          </AdminFormSection>
            <AdminFormActions backHref="/admin/university">
              <SubmitButton />
            </AdminFormActions>
          </AdminForm>
    </>
  );
}
