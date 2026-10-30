import { requireAdmin } from "@/lib/admin/auth";
import { STREAM_OPTIONS, optionsFromRecord } from "@/lib/admin/constants";
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
} from "@/components/admin/AdminUi";
import { SubmitButton } from "@/components/admin/DeleteButton";
import { saveCharacter } from "../actions";

export default async function Page() {
  await requireAdmin();
  return (
    <>
      <AdminPageHeader title="Add character" />
      <AdminForm action={saveCharacter} encType="multipart/form-data">
            <AdminFormSection title="Basics" description="Name, salary, and stream.">
            <AdminLocalizedField name="c_name" label="Name" span={8} required />
            <AdminField label="Avg salary" span={6}>
              <input name="c_aver_salary" className="form-control" />
            </AdminField>
            <AdminField label="Stream" span={6}>
              <AdminSelect
                name="c_stream"
                placeholder="Search stream..."
                defaultValue={0}
                options={optionsFromRecord(STREAM_OPTIONS)}
              />
            </AdminField>
            <AdminStatusSelect name="c_status" />
          </AdminFormSection>

            <AdminFormSection title="Content & media" description="Description and character images.">
            <AdminLocalizedField name="c_desc" label="Description" span={12} kind="textarea" rows={4} />
            <AdminImageField name="c_img_male" label="Male image" folder="characters" />
            <AdminImageField name="c_img_female" label="Female image" folder="characters" />
          </AdminFormSection>
            <AdminFormActions backHref="/admin/characters">
              <SubmitButton />
            </AdminFormActions>
          </AdminForm>
    </>
  );
}
