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
      <AdminPageHeader title="Add blog category" />
      <AdminForm action={saveItem}>
            <AdminFormSection title="Basics" description="Name and status.">
            <AdminLocalizedField name="bc_name" label="Name" span={8} required />
            <AdminStatusSelect name="bc_status" />
          </AdminFormSection>
            <AdminFormActions backHref="/admin/blog">
              <SubmitButton />
            </AdminFormActions>
          </AdminForm>
    </>
  );
}
