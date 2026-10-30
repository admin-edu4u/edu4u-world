import { notFound } from "next/navigation";
import { requireAdmin } from "@/lib/admin/auth";
import { createServiceClient } from "@/lib/supabase/server";
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

export default async function Page({ params }: { params: Promise<{ id: string }> }) {
  await requireAdmin();
  const { id } = await params;
  const supabase = createServiceClient();
  const { data: row } = await supabase
    .from("subject_grades")
    .select("*")
    .eq("sg_id", Number(id))
    .maybeSingle();
  if (!row) notFound();

  return (
    <>
      <AdminPageHeader title="Edit subject grade" />
      <AdminForm action={saveGrade}>
        <AdminFormSection title="Basics" description="Level, grade, and status.">
          <input type="hidden" name="sg_id" value={row.sg_id} />
          <AdminField label="Level" span={6}>
            <AdminSelect
              name="sg_s_id"
              required
              placeholder="Search level..."
              defaultValue={row.sg_s_id || ""}
              options={optionsFromRecord(Q_LEVELS)}
            />
          </AdminField>
          <AdminField label="Grade" span={6}>
            <input
              name="sg_grade"
              className="form-control"
              required
              defaultValue={row.sg_grade || ""}
              placeholder="e.g. A+"
            />
          </AdminField>
          <AdminStatusSelect name="sg_status" value={row.sg_status} />
        </AdminFormSection>
        <AdminFormActions backHref="/admin/subjectsgrades">
          <SubmitButton />
        </AdminFormActions>
      </AdminForm>
    </>
  );
}
