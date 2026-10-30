import { requireAdmin } from "@/lib/admin/auth";
import { createServiceClient } from "@/lib/supabase/server";
import {
  AdminForm,
  AdminFormActions,
  AdminFormSection,
  AdminImageField,
  AdminPageHeader,
} from "@/components/admin/AdminUi";
import { updateHomeBgAction } from "./actions";
import { SubmitButton } from "@/components/admin/DeleteButton";

export default async function AdminHomeBgPage() {
  await requireAdmin();
  const supabase = createServiceClient();
  const { data: row } = await supabase.from("home").select("*").eq("h_id", 1).maybeSingle();

  return (
    <>
      <AdminPageHeader title="Home background" />
      <AdminForm action={updateHomeBgAction} encType="multipart/form-data">
        <AdminFormSection title="Background images" description="Images shown on the homepage hero.">
          <AdminImageField
            name="images"
            label="Background images"
            folder="home"
            filename={row?.h_img}
            multiple
            span={12}
          />
        </AdminFormSection>
        <AdminFormActions backHref="/admin/dashboard">
          <SubmitButton />
        </AdminFormActions>
      </AdminForm>
    </>
  );
}
