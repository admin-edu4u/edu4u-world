import { requireAdmin } from "@/lib/admin/auth";
import { createServiceClient } from "@/lib/supabase/server";
import {
  AdminField,
  AdminForm,
  AdminFormActions,
  AdminFormSection,
  AdminLocalizedField,
  AdminPageHeader,
  AdminSelect,
  AdminStatusSelect,
} from "@/components/admin/AdminUi";
import { SubmitButton } from "@/components/admin/DeleteButton";
import { saveSubCat } from "../../actions";

export default async function Page({
  searchParams,
}: {
  searchParams: Promise<{ main?: string }>;
}) {
  await requireAdmin();
  const sp = await searchParams;
  const mainDefault = sp.main ? Number(sp.main) : "";
  const supabase = createServiceClient();
  const [{ data: cats }, { data: chars }] = await Promise.all([
    supabase.from("categories").select("c_id,c_name").order("c_name"),
    supabase.from("characters").select("c_id,c_name").order("c_name"),
  ]);

  return (
    <>
      <AdminPageHeader title="Add sub category" />
      <AdminForm action={saveSubCat}>
            <AdminFormSection title="Basics" description="Name, parent category, character, and status.">
            <AdminLocalizedField name="sc_name" label="Name" span={8} required />
            <AdminField label="Main category" span={6}>
              <AdminSelect
                name="sc_main"
                placeholder="Search category..."
                defaultValue={mainDefault}
                options={(cats || [])
                  .map((c) => ({ value: String(c.c_id), label: String(c.c_name || "").trim() }))
                  .filter((c) => c.label)}
              />
            </AdminField>
            <AdminField label="Character" span={6}>
              <AdminSelect
                name="sc_character"
                defaultValue={0}
                placeholder="Search character..."
                options={[
                  { value: "0", label: "None" },
                  ...(chars || [])
                    .map((c) => ({ value: String(c.c_id), label: String(c.c_name || "").trim() }))
                    .filter((c) => c.label),
                ]}
              />
            </AdminField>
            <AdminStatusSelect name="sc_status" />
          </AdminFormSection>
            <AdminFormActions backHref="/admin/category">
              <SubmitButton />
            </AdminFormActions>
          </AdminForm>
    </>
  );
}
