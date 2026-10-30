import { notFound } from "next/navigation";
import { requireAdmin } from "@/lib/admin/auth";
import { createServiceClient } from "@/lib/supabase/server";
import { COUNTRY_OPTIONS, K12_LEVELS, optionsFromRecord } from "@/lib/admin/constants";
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
  AdminYesNoSelect,
} from "@/components/admin/AdminUi";
import { SubmitButton } from "@/components/admin/DeleteButton";
import { i18nFieldValue } from "@/lib/i18n/cms";
import { saveK12 } from "../actions";

export default async function Page({ params }: { params: Promise<{ id: string }> }) {
  await requireAdmin();
  const { id } = await params;
  const supabase = createServiceClient();
  const [{ data: row }, { data: states }] = await Promise.all([
    supabase.from("international_school").select("*").eq("int_id", Number(id)).maybeSingle(),
    supabase.from("states").select("state_id,state_name").order("state_name"),
  ]);
  if (!row) notFound();
  const hours = String(row.int_hours || "").split("~");
  return (
    <>
      <AdminPageHeader title="Edit K-12 school" />
      <AdminForm action={saveK12} encType="multipart/form-data">
            <AdminFormSection title="Basics" description="School name, level, and status.">
            <input type="hidden" name="int_id" value={row.int_id} />
            <AdminLocalizedField
              name="int_name"
              label="Name"
              span={8}
              required
              defaultValue={row.int_name || ""}
              defaultValueCh={i18nFieldValue(row, "int_name", "ch")}
              defaultValueBm={i18nFieldValue(row, "int_name", "bm")}
            />
            <AdminField label="Level" span={6}>
              <AdminSelect
                name="int_level"
                multiple
                required
                placeholder="Search levels..."
                defaultValue={row.int_level || ""}
                options={optionsFromRecord(K12_LEVELS)}
              />
            </AdminField>
            <AdminStatusSelect name="int_status" value={row.int_status} />
          </AdminFormSection>

            <AdminFormSection title="Stats & location" description="Size, address, and region.">
            <AdminLocalizedField
              name="int_year"
              label="Year"
              span={6}
              defaultValue={row.int_year || ""}
              defaultValueCh={i18nFieldValue(row, "int_year", "ch")}
              defaultValueBm={i18nFieldValue(row, "int_year", "bm")}
            />
            <AdminField label="Students" span={6}>
              <input name="int_student" className="form-control" defaultValue={row.int_student || ""} />
            </AdminField>
            <AdminField label="Staff" span={6}>
              <input name="int_staff" className="form-control" defaultValue={row.int_staff || ""} />
            </AdminField>
            <AdminLocalizedField
              name="int_addr"
              label="Address"
              span={6}
              defaultValue={row.int_addr || ""}
              defaultValueCh={i18nFieldValue(row, "int_addr", "ch")}
              defaultValueBm={i18nFieldValue(row, "int_addr", "bm")}
            />
            <AdminField label="State" span={6}>
              <AdminSelect
                name="int_state"
                placeholder="Search state..."
                defaultValue={row.int_state || ""}
                options={(states || []).map((s) => ({ value: String(s.state_id), label: s.state_name }))}
              />
            </AdminField>
            <AdminField label="Country" span={6}>
              <AdminSelect
                name="int_country"
                placeholder="Search country..."
                defaultValue={row.int_country || ""}
                options={COUNTRY_OPTIONS}
              />
            </AdminField>
          </AdminFormSection>

            <AdminFormSection title="Settings" description="Hours and branch options.">
            <AdminField label="Hours from" span={6}>
              <input name="hours1" className="form-control" defaultValue={hours[0] || ""} />
            </AdminField>
            <AdminField label="Hours to" span={6}>
              <input name="hours2" className="form-control" defaultValue={hours[1] || ""} />
            </AdminField>
            <AdminYesNoSelect name="int_has_branch" label="Has branch" value={row.int_has_branch} />
          </AdminFormSection>

            <AdminFormSection title="Content & media" description="Description and images.">
            <AdminLocalizedField
              name="int_desc"
              label="Description"
              span={12}
              kind="textarea"
              rows={4}
              defaultValue={row.int_desc || ""}
              defaultValueCh={i18nFieldValue(row, "int_desc", "ch")}
              defaultValueBm={i18nFieldValue(row, "int_desc", "bm")}
            />
            <AdminImageField
              name="logo"
              label="Logo"
              folder="internationalschool/logo"
              filename={row.int_logo}
            />
            <AdminImageField
              name="img"
              label="Images"
              folder="internationalschool/images"
              filename={row.int_img}
              multiple
            />
          </AdminFormSection>
            <AdminFormActions backHref="/admin/k-12">
              <SubmitButton />
            </AdminFormActions>
          </AdminForm>
    </>
  );
}
