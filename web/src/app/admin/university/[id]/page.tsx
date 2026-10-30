import { notFound } from "next/navigation";
import { requireAdmin } from "@/lib/admin/auth";
import { createServiceClient } from "@/lib/supabase/server";
import { COUNTRY_OPTIONS } from "@/lib/admin/constants";
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
import { saveUniversity } from "../actions";

export default async function Page({ params }: { params: Promise<{ id: string }> }) {
  await requireAdmin();
  const { id } = await params;
  const supabase = createServiceClient();
  const [{ data: row }, { data: types }, { data: states }] = await Promise.all([
    supabase.from("universities").select("*").eq("u_id", Number(id)).maybeSingle(),
    supabase.from("university_type").select("ut_id,ut_name").order("ut_id"),
    supabase.from("states").select("state_id,state_name").order("state_name"),
  ]);
  if (!row) notFound();
  const hours = String(row.u_hours || "").split("~");
  return (
    <>
      <AdminPageHeader title="Edit university" />
      <AdminForm action={saveUniversity} encType="multipart/form-data">
            <AdminFormSection title="Basics" description="Name, type, and status. Empty Chinese or Bahasa Melayu falls back to English on the site.">
            <input type="hidden" name="u_id" value={row.u_id} />
            <AdminLocalizedField
              name="u_name"
              label="Name"
              span={12}
              required
              defaultValue={row.u_name || ""}
              defaultValueCh={i18nFieldValue(row, "u_name", "ch")}
              defaultValueBm={i18nFieldValue(row, "u_name", "bm")}
            />
            <AdminField label="Type" span={6}>
              <AdminSelect
                name="u_type"
                placeholder="Search type..."
                defaultValue={row.u_type || ""}
                options={(types || [])
                  .map((t) => ({
                    value: String(t.ut_id),
                    label: String(t.ut_name || "").trim(),
                  }))
                  .filter((t) => t.label)}
              />
            </AdminField>
            <AdminStatusSelect name="u_status" value={row.u_status} />
            <AdminYesNoSelect name="u_has_rank" label="Has rank" value={row.u_has_rank} />
          </AdminFormSection>

            <AdminFormSection title="Stats & location" description="Ranking, size, and address.">
            <AdminLocalizedField
              name="u_ranking"
              label="Ranking"
              span={12}
              defaultValue={row.u_ranking || ""}
              defaultValueCh={i18nFieldValue(row, "u_ranking", "ch")}
              defaultValueBm={i18nFieldValue(row, "u_ranking", "bm")}
            />
            <AdminField label="Students" span={6}>
              <input name="u_student" className="form-control" defaultValue={row.u_student || ""} />
            </AdminField>
            <AdminField label="Staff" span={6}>
              <input name="u_staff" className="form-control" defaultValue={row.u_staff || ""} />
            </AdminField>
            <AdminLocalizedField
              name="u_year"
              label="Year"
              span={12}
              defaultValue={row.u_year || ""}
              defaultValueCh={i18nFieldValue(row, "u_year", "ch")}
              defaultValueBm={i18nFieldValue(row, "u_year", "bm")}
            />
            <AdminLocalizedField
              name="u_addr"
              label="Address"
              span={12}
              defaultValue={row.u_addr || ""}
              defaultValueCh={i18nFieldValue(row, "u_addr", "ch")}
              defaultValueBm={i18nFieldValue(row, "u_addr", "bm")}
            />
            <AdminField label="State" span={6}>
              <AdminSelect
                name="u_state"
                placeholder="Search state..."
                defaultValue={row.u_state || ""}
                options={(states || []).map((s) => ({ value: String(s.state_id), label: s.state_name }))}
              />
            </AdminField>
            <AdminField label="Country" span={6}>
              <AdminSelect
                name="u_country"
                placeholder="Search country..."
                defaultValue={row.u_country || ""}
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
            <AdminYesNoSelect name="u_has_branch" label="Has branch" value={row.u_has_branch} />
          </AdminFormSection>

            <AdminFormSection title="Content & media" description="Description and images.">
            <AdminLocalizedField
              name="u_desc"
              label="Description"
              span={12}
              kind="textarea"
              rows={4}
              defaultValue={row.u_desc || ""}
              defaultValueCh={i18nFieldValue(row, "u_desc", "ch")}
              defaultValueBm={i18nFieldValue(row, "u_desc", "bm")}
            />
            <AdminImageField name="logo" label="Logo" folder="university/logo" filename={row.u_logo} />
            <AdminImageField
              name="img"
              label="Add images"
              folder="university/images"
              filename={row.u_img}
              multiple
            />
          </AdminFormSection>
            <AdminFormActions backHref="/admin/university">
              <SubmitButton />
            </AdminFormActions>
          </AdminForm>
    </>
  );
}
