import { notFound } from "next/navigation";
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
import { i18nFieldValue } from "@/lib/i18n/cms";
import { saveSubCat } from "../../actions";

export default async function Page({ params }: { params: Promise<{ id: string }> }) {
  await requireAdmin();
  const { id } = await params;
  const supabase = createServiceClient();
  const [{ data: row }, { data: cats }, { data: chars }] = await Promise.all([
    supabase.from("sub_cats").select("*").eq("sc_id", Number(id)).maybeSingle(),
    supabase.from("categories").select("c_id,c_name").order("c_name"),
    supabase.from("characters").select("c_id,c_name").order("c_name"),
  ]);
  if (!row) notFound();

  return (
    <>
      <AdminPageHeader title="Edit sub category" />
      <AdminForm action={saveSubCat}>
            <AdminFormSection title="Basics" description="Name, parent category, character, and status.">
            <input type="hidden" name="sc_id" value={row.sc_id} />
            <AdminLocalizedField
              name="sc_name"
              label="Name"
              span={8}
              required
              defaultValue={row.sc_name || ""}
              defaultValueCh={i18nFieldValue(row, "sc_name", "ch")}
              defaultValueBm={i18nFieldValue(row, "sc_name", "bm")}
            />
            <AdminField label="Main category" span={6}>
              <AdminSelect
                name="sc_main"
                placeholder="Search category..."
                defaultValue={row.sc_main || ""}
                options={(cats || [])
                  .map((c) => ({ value: String(c.c_id), label: String(c.c_name || "").trim() }))
                  .filter((c) => c.label)}
              />
            </AdminField>
            <AdminField label="Character" span={6}>
              <AdminSelect
                name="sc_character"
                placeholder="Search character..."
                defaultValue={row.sc_character ?? 0}
                options={[
                  { value: "0", label: "None" },
                  ...(chars || [])
                    .map((c) => ({ value: String(c.c_id), label: String(c.c_name || "").trim() }))
                    .filter((c) => c.label),
                ]}
              />
            </AdminField>
            <AdminStatusSelect name="sc_status" value={row.sc_status} />
          </AdminFormSection>
            <AdminFormActions backHref="/admin/category">
              <SubmitButton />
            </AdminFormActions>
          </AdminForm>
    </>
  );
}
