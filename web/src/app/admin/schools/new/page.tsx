import { requireAdmin } from "@/lib/admin/auth";
import { Q_LEVELS, optionsFromRecord } from "@/lib/admin/constants";
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
  return (
    <>
      <AdminPageHeader title="Add school" />
      <AdminForm action={saveItem}>
        <AdminFormSection title="Basics" description="Name, qualifications, and status.">
          <AdminLocalizedField name="s_name" label="Name" span={8} required />
          <AdminField label="Qualification" span={8}>
            <AdminSelect
              name="s_qualification"
              multiple
              placeholder="Search qualifications..."
              options={optionsFromRecord(Q_LEVELS)}
            />
          </AdminField>
          <AdminStatusSelect name="s_status" />
        </AdminFormSection>
        <AdminFormActions backHref="/admin/schools">
          <SubmitButton />
        </AdminFormActions>
      </AdminForm>
    </>
  );
}
