import { notFound } from "next/navigation";
import { requireAdmin } from "@/lib/admin/auth";
import { createServiceClient } from "@/lib/supabase/server";
import { Q_LEVELS } from "@/lib/admin/constants";
import {
  AdminField,
  AdminForm,
  AdminFormActions,
  AdminFormSection,
  AdminLocalizedField,
  AdminPageHeader,
  AdminSelect,
  AdminStatusSelect,
  AdminYesNoSelect,
} from "@/components/admin/AdminUi";
import { SubmitButton } from "@/components/admin/DeleteButton";
import { i18nFieldValue } from "@/lib/i18n/cms";
import { saveSubject } from "../../actions";

export default async function Page({
  params,
}: {
  params: Promise<{ levelId: string; subjectId: string }>;
}) {
  await requireAdmin();
  const { levelId, subjectId } = await params;
  const lid = Number(levelId);
  const supabase = createServiceClient();
  const [{ data: row }, { data: cats }] = await Promise.all([
    supabase.from("subjects").select("*").eq("s_id", Number(subjectId)).maybeSingle(),
    supabase.from("subject_categories").select("sc_id,sc_name").order("sc_name"),
  ]);
  if (!row) notFound();

  return (
    <>
      <AdminPageHeader title={`Edit subject: ${Q_LEVELS[lid] || "Level"}`} />
      <AdminForm action={saveSubject}>
            <AdminFormSection title="Basics" description="Subject details for this level.">
            <input type="hidden" name="s_id" value={row.s_id} />
            <input type="hidden" name="s_level_id" value={lid} />
            <AdminLocalizedField
              name="s_name"
              label="Name"
              span={8}
              required
              defaultValue={row.s_name || ""}
              defaultValueCh={i18nFieldValue(row, "s_name", "ch")}
              defaultValueBm={i18nFieldValue(row, "s_name", "bm")}
            />
            <AdminField label="Category" span={4}>
              <AdminSelect
                name="s_subject_cat_id"
                placeholder="Search category..."
                defaultValue={row.s_subject_cat_id || ""}
                options={(cats || []).map((c) => ({ value: String(c.sc_id), label: c.sc_name }))}
              />
            </AdminField>
            <AdminYesNoSelect name="s_is_preselect" label="Preselect" value={row.s_is_preselect} />
            <AdminStatusSelect name="s_status" value={row.s_status} />
          </AdminFormSection>
            <AdminFormActions backHref={`/admin/subjects/${levelId}`}>
              <SubmitButton />
            </AdminFormActions>
          </AdminForm>
    </>
  );
}