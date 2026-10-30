import { requireAdmin } from "@/lib/admin/auth";
import {
  AdminForm,
  AdminFormActions,
  AdminFormSection,
  AdminImageField,
  AdminLocalizedField,
  AdminPageHeader,
  AdminStatusSelect,
} from "@/components/admin/AdminUi";
import { SubmitButton } from "@/components/admin/DeleteButton";
import { saveCategory } from "../actions";

export default async function Page() {
  await requireAdmin();
  return (
    <>
      <AdminPageHeader title="Add category" />
      <AdminForm action={saveCategory} encType="multipart/form-data">
            <AdminFormSection title="Basics" description="Name, image, and status.">
            <AdminLocalizedField name="c_name" label="Name" span={8} required />
            <AdminImageField name="c_img" label="Image" folder="category" />
            <AdminStatusSelect name="c_status" />
          </AdminFormSection>
            <AdminFormActions backHref="/admin/category">
              <SubmitButton />
            </AdminFormActions>
          </AdminForm>
    </>
  );
}
