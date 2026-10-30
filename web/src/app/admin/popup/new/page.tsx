import { requireAdmin } from "@/lib/admin/auth";
import {
  AdminField,
  AdminForm,
  AdminFormActions,
  AdminFormSection,
  AdminImageField,
  AdminPageHeader,
  AdminStatusSelect,
} from "@/components/admin/AdminUi";
import { SubmitButton } from "@/components/admin/DeleteButton";
import { STATUS } from "@/lib/admin/status";
import { saveItem } from "../actions";

export default async function NewPopupPage() {
  await requireAdmin();

  return (
    <>
      <AdminPageHeader title="Add pop up" />
      <AdminForm action={saveItem} encType="multipart/form-data">
        <AdminFormSection
          title="Banner"
          description="Image and link for this homepage pop up."
        >
          <AdminImageField name="img" label="Banner image" folder="home" span={12} />
          <AdminField label="Banner URL" span={8}>
            <input
              type="url"
              name="pu_url"
              className="form-control"
              placeholder="https://..."
              required
            />
            <p className="admin-field-hint">Visitors go here when they click the pop up.</p>
          </AdminField>
          <AdminStatusSelect name="pu_status" value={STATUS.ACTIVE} />
        </AdminFormSection>
        <AdminFormActions backHref="/admin/popup">
          <SubmitButton />
        </AdminFormActions>
      </AdminForm>
    </>
  );
}
