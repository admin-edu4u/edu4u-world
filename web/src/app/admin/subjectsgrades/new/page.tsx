import { requireAdmin } from "@/lib/admin/auth";
import { Q_LEVELS, optionsFromRecord } from "@/lib/admin/constants";
import {
  AdminField,
  AdminForm,
  AdminFormActions,
  AdminFormSection,
  AdminPageHeader,
  AdminSelect,
  AdminStatusSelect,
} from "@/components/admin/AdminUi";
import { SubmitButton } from "@/components/admin/DeleteButton";
import { saveGrade } from "../actions";

export default async function Page() {
  await requireAdmin();

  return (
    <>
      <AdminPageHeader title="Add subject grade" />
      <AdminForm action={saveGrade}>
        <AdminFormSection title="Basics" description="Level, grade, and status.">
          <AdminField label="Level" span={6}>
            <AdminSelect
              name="sg_s_id"
              required
              placeholder="Search level..."
              options={optionsFromRecord(Q_LEVELS)}
            />
          </AdminField>
          <AdminField label="Grade" span={6}>
            <input name="sg_grade" className="form-control" required placeholder="e.g. A+" />
          </AdminField>
          <AdminStatusSelect name="sg_status" />
        </AdminFormSection>
        <AdminFormActions backHref="/admin/subjectsgrades">
          <SubmitButton />
        </AdminFormActions>
      </AdminForm>
    </>
  );
}
