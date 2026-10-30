import { requireAdmin } from "@/lib/admin/auth";
import {
  AdminForm,
  AdminFormActions,
  AdminFormSection,
  AdminLocalizedField,
  AdminPageHeader,
  AdminStatusSelect,
} from "@/components/admin/AdminUi";
import { SubmitButton } from "@/components/admin/DeleteButton";
import { saveItem } from "../actions";

export default async function Page() {
  await requireAdmin();
  return (
    <>
      <AdminPageHeader title="Add subject category" />
      <AdminForm action={saveItem}>
            <AdminFormSection title="Basics" description="Name and status.">
            <AdminLocalizedField name="sc_name" label="Name" span={8} required />
            <AdminStatusSelect name="sc_status" />
          </AdminFormSection>
            <AdminFormActions backHref="/admin/subjects">
              <SubmitButton />
            </AdminFormActions>
          </AdminForm>
    </>
  );
}
