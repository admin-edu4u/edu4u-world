import { notFound } from "next/navigation";
import { requireAdmin } from "@/lib/admin/auth";
import { createServiceClient } from "@/lib/supabase/server";
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
import { i18nFieldValue } from "@/lib/i18n/cms";
import { saveCharacter } from "../actions";

export default async function Page({ params }: { params: Promise<{ id: string }> }) {
  await requireAdmin();
  const { id } = await params;
  const supabase = createServiceClient();
  const { data: row } = await supabase.from("characters").select("*").eq("c_id", Number(id)).maybeSingle();
  if (!row) notFound();
  return (
    <>
      <AdminPageHeader title="Edit character" />
      <AdminForm action={saveCharacter} encType="multipart/form-data">
            <AdminFormSection title="Basics" description="Name, salary, and stream.">
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
            <AdminField label="Avg salary" span={6}>
              <input name="c_aver_salary" className="form-control" defaultValue={row.c_aver_salary || ""} />
            </AdminField>
            <AdminField label="Stream" span={6}>
              <AdminSelect
                name="c_stream"
                placeholder="Search stream..."
                defaultValue={row.c_stream ?? 0}
                options={optionsFromRecord(STREAM_OPTIONS)}
              />
            </AdminField>
            <AdminStatusSelect name="c_status" value={row.c_status} />
          </AdminFormSection>

            <AdminFormSection title="Content & media" description="Description and character images.">
            <AdminLocalizedField
              name="c_desc"
              label="Description"
              span={12}
              kind="textarea"
              rows={4}
              defaultValue={row.c_desc || ""}
              defaultValueCh={i18nFieldValue(row, "c_desc", "ch")}
              defaultValueBm={i18nFieldValue(row, "c_desc", "bm")}
            />
            <AdminImageField
              name="c_img_male"
              label="Male image"
              folder="characters"
              filename={row.c_img_male}
            />
            <AdminImageField
              name="c_img_female"
              label="Female image"
              folder="characters"
              filename={row.c_img_female}
            />
          </AdminFormSection>
            <AdminFormActions backHref="/admin/characters">
              <SubmitButton />
            </AdminFormActions>
          </AdminForm>
    </>
  );
}
