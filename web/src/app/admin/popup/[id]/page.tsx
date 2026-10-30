import { notFound } from "next/navigation";
import { requireAdmin } from "@/lib/admin/auth";
import { createServiceClient } from "@/lib/supabase/server";
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
import { saveItem } from "../actions";

export default async function EditPopupPage({ params }: { params: Promise<{ id: string }> }) {
  await requireAdmin();
  const { id } = await params;
  const supabase = createServiceClient();
  const { data: row } = await supabase
    .from("pop_up")
    .select("*")
    .eq("pu_id", Number(id))
    .maybeSingle();
  if (!row) notFound();

  return (
    <>
      <AdminPageHeader title="Edit pop up" />
      <AdminForm action={saveItem} encType="multipart/form-data">
        <input type="hidden" name="pu_id" value={row.pu_id} />
        <AdminFormSection
          title="Banner"
          description="Image and link for this homepage pop up."
        >
          <AdminImageField
            name="img"
            label="Banner image"
            folder="home"
            filename={row.pu_img}
            span={12}
          />
          <AdminField label="Banner URL" span={8}>
            <input
              type="url"
              name="pu_url"
              className="form-control"
              defaultValue={row.pu_url ?? ""}
              placeholder="https://..."
              required
            />
            <p className="admin-field-hint">Visitors go here when they click the pop up.</p>
          </AdminField>
          <AdminStatusSelect name="pu_status" value={row.pu_status} />
        </AdminFormSection>
        <AdminFormActions backHref="/admin/popup">
          <SubmitButton />
        </AdminFormActions>
      </AdminForm>
    </>
  );
}
