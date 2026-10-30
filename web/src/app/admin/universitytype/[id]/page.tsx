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
import { saveItem } from "../actions";

export default async function Page({ params }: { params: Promise<{ id: string }> }) {
  await requireAdmin();
  const { id } = await params;
  const supabase = createServiceClient();
  const { data: row } = await supabase
    .from("university_type")
    .select("*")
    .eq("ut_id", Number(id))
    .maybeSingle();
  if (!row) notFound();

  return (
    <>
      <AdminPageHeader title="Edit university type" />
      <AdminForm action={saveItem}>
            <AdminFormSection title="Basics" description="Name, ownership, and status.">
            <input type="hidden" name="ut_id" value={row.ut_id} />
            <AdminLocalizedField
              name="ut_name"
              label="Name"
              span={8}
              required
              defaultValue={row.ut_name || ""}
              defaultValueCh={i18nFieldValue(row, "ut_name", "ch")}
              defaultValueBm={i18nFieldValue(row, "ut_name", "bm")}
            />
            <AdminField label="Belongs to" span={6}>
              <AdminSelect
                name="ut_type"
                defaultValue={row.ut_type ?? 1}
                placeholder="Search..."
                options={[
                  { value: "1", label: "Private" },
                  { value: "2", label: "Public" },
                ]}
              />
            </AdminField>
            <AdminStatusSelect name="ut_status" value={row.ut_status} />
          </AdminFormSection>
            <AdminFormActions backHref="/admin/universitytype">
              <SubmitButton />
            </AdminFormActions>
          </AdminForm>
    </>
  );
}
