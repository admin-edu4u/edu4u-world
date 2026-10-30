import { requireAdmin } from "@/lib/admin/auth";
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
      <AdminPageHeader title="Add university type" />
      <AdminForm action={saveItem}>
            <AdminFormSection title="Basics" description="Name, ownership, and status.">
            <AdminLocalizedField name="ut_name" label="Name" span={8} required />
            <AdminField label="Belongs to" span={6}>
              <AdminSelect
                name="ut_type"
                defaultValue={1}
                placeholder="Search..."
                options={[
                  { value: "1", label: "Private" },
                  { value: "2", label: "Public" },
                ]}
              />
            </AdminField>
            <AdminStatusSelect name="ut_status" />
          </AdminFormSection>
            <AdminFormActions backHref="/admin/universitytype">
              <SubmitButton />
            </AdminFormActions>
          </AdminForm>
    </>
  );
}
