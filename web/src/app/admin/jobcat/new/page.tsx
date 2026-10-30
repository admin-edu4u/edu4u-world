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
      <AdminPageHeader title="Add career category" />
      <AdminForm action={saveItem}>
            <AdminFormSection title="Basics" description="Title and status.">
            <AdminLocalizedField name="cc_title" label="Title" span={8} required />
            <AdminStatusSelect name="cc_status" />
          </AdminFormSection>
            <AdminFormActions backHref="/admin/job">
              <SubmitButton />
            </AdminFormActions>
          </AdminForm>
    </>
  );
}
