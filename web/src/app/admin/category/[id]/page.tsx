import { notFound } from "next/navigation";
import { requireAdmin } from "@/lib/admin/auth";
import { createServiceClient } from "@/lib/supabase/server";
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
import { i18nFieldValue } from "@/lib/i18n/cms";
import { saveCategory } from "../actions";

export default async function Page({ params }: { params: Promise<{ id: string }> }) {
  await requireAdmin();
  const { id } = await params;
  const supabase = createServiceClient();
  const { data: row } = await supabase.from("categories").select("*").eq("c_id", Number(id)).maybeSingle();
  if (!row) notFound();

  return (
    <>
      <AdminPageHeader title="Edit category" />
      <AdminForm action={saveCategory} encType="multipart/form-data">
            <AdminFormSection title="Basics" description="Name, image, and status.">
            <input type="hidden" name="c_id" value={row.c_id} />
            <AdminLocalizedField
              name="c_name"
              label="Name"
              span={8}
              required
              defaultValue={row.c_name || ""}
              defaultValueCh={i18nFieldValue(row, "c_name", "ch")}
              defaultValueBm={i18nFieldValue(row, "c_name", "bm")}
            />
            <AdminImageField
              name="c_img"
              label="Image"
              folder="category"
              filename={row.c_img}
             
            />
            <AdminStatusSelect name="c_status" value={row.c_status} />
          </AdminFormSection>
            <AdminFormActions backHref="/admin/category">
              <SubmitButton />
            </AdminFormActions>
          </AdminForm>
    </>
  );
}
