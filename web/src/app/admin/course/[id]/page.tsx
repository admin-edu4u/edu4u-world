import { notFound } from "next/navigation";
import { requireAdmin } from "@/lib/admin/auth";
import { createServiceClient } from "@/lib/supabase/server";
import { COURSE_LEVELS, INTAKE_MONTHS, STREAM_OPTIONS, optionsFromRecord } from "@/lib/admin/constants";
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
import { saveCourse } from "../actions";

export default async function Page({ params }: { params: Promise<{ id: string }> }) {
  await requireAdmin();
  const { id } = await params;
  const supabase = createServiceClient();
  const [{ data: row }, { data: unis }, { data: cats }, { data: subCats }] = await Promise.all([
    supabase.from("courses").select("*").eq("c_id", Number(id)).maybeSingle(),
    supabase.from("universities").select("u_id,u_name").order("u_name"),
    supabase.from("categories").select("c_id,c_name").order("c_name"),
    supabase.from("sub_cats").select("sc_id,sc_name").order("sc_name"),
  ]);
  if (!row) notFound();
  const asHtml = (v: unknown) => {
    if (v == null) return "";
    if (typeof v === "string") {
      const trimmed = v.trim();
      if (!trimmed || trimmed === "[]") return "";
      if (trimmed.startsWith("[")) {
        try {
          const parsed = JSON.parse(trimmed) as unknown;
          if (Array.isArray(parsed)) {
            return parsed
              .map((item) => {
                if (typeof item === "string") return item;
                if (item && typeof item === "object") {
                  const rowItem = item as { title?: string; desc?: string };
                  const title = rowItem.title ? `<h2>${rowItem.title}</h2>` : "";
                  return `${title}${rowItem.desc || ""}`;
                }
                return "";
              })
              .join("");
          }
        } catch {
          /* treat as HTML */
        }
      }
      return v;
    }
    if (Array.isArray(v)) {
      return v
        .map((item) => {
          if (typeof item === "string") return item;
          if (item && typeof item === "object") {
            const rowItem = item as { title?: string; desc?: string };
            const title = rowItem.title ? `<h2>${rowItem.title}</h2>` : "";
            return `${title}${rowItem.desc || ""}`;
          }
          return "";
        })
        .join("");
    }
    return String(v);
  };
  return (
    <>
      <AdminPageHeader title="Edit course" />
      <AdminForm action={saveCourse}>
            <AdminFormSection title="Basics" description="Name, university, and status.">
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
            <AdminField label="University" span={4}>
              <AdminSelect
                name="c_uni"
                required
                placeholder="Search university..."
                defaultValue={row.c_uni || ""}
                options={(unis || []).map((u) => ({ value: String(u.u_id), label: u.u_name }))}
              />
            </AdminField>
            <AdminStatusSelect name="c_status" value={row.c_status} />
          </AdminFormSection>

            <AdminFormSection title="Program" description="Level, stream, duration, and categories.">
            <AdminField label="Level" span={6}>
              <AdminSelect
                name="c_level"
                placeholder="Search level..."
                defaultValue={row.c_level || ""}
                options={Object.entries(COURSE_LEVELS).map(([k, v]) => ({ value: k, label: v }))}
              />
            </AdminField>
            <AdminField label="Stream" span={6}>
              <AdminSelect
                name="c_stream"
                placeholder="Search stream..."
                defaultValue={row.c_stream ?? 0}
                options={optionsFromRecord(STREAM_OPTIONS)}
              />
            </AdminField>
            <AdminField label="Duration" span={6}>
              <input name="c_duration" className="form-control" defaultValue={row.c_duration || ""} />
            </AdminField>
            <AdminLocalizedField
              name="c_award"
              label="Award"
              span={6}
              defaultValue={row.c_award || ""}
              defaultValueCh={i18nFieldValue(row, "c_award", "ch")}
              defaultValueBm={i18nFieldValue(row, "c_award", "bm")}
            />
          </AdminFormSection>

            <AdminFormSection title="Fees & intake" description="Pricing, audience, and intake months.">
            <AdminField label="Audience" span={6}>
              <AdminSelect
                name="c_is_malaysian"
                defaultValue={row.c_is_malaysian ?? 2}
                placeholder="Search audience..."
                options={[
                  { value: "0", label: "International" },
                  { value: "1", label: "Malaysian" },
                  { value: "2", label: "Both" },
                ]}
              />
            </AdminField>
            <AdminField label="Amount" span={6}>
              <input name="c_amount" type="number" className="form-control" defaultValue={row.c_amount || 0} />
            </AdminField>
            <AdminField label="Amount bumi" span={6}>
              <input
                name="c_amount_bumi"
                type="number"
                className="form-control"
                defaultValue={row.c_amount_bumi || 0}
              />
            </AdminField>
            <AdminField label="Amount intl" span={6}>
              <input
                name="c_amount_international"
                type="number"
                className="form-control"
                defaultValue={row.c_amount_international || 0}
              />
            </AdminField>
            <AdminField label="Main category" span={6}>
              <AdminSelect
                name="c_main_cat"
                placeholder="Search category..."
                defaultValue={row.c_main_cat || ""}
                emptyLabel="None"
                options={(cats || []).map((c) => ({ value: String(c.c_id), label: c.c_name }))}
              />
            </AdminField>
            <AdminField label="Sub category" span={6}>
              <AdminSelect
                name="c_sub_cat"
                placeholder="Search sub category..."
                emptyLabel="None"
                defaultValue={row.c_sub_cat || ""}
                options={(subCats || []).map((c) => ({ value: String(c.sc_id), label: c.sc_name }))}
              />
            </AdminField>
            <AdminYesNoSelect name="c_has_internship" label="Internship" value={row.c_has_internship} />
            <AdminLocalizedField
              name="c_internship_duration"
              label="Intern duration"
              span={6}
              defaultValue={row.c_internship_duration || ""}
              defaultValueCh={i18nFieldValue(row, "c_internship_duration", "ch")}
              defaultValueBm={i18nFieldValue(row, "c_internship_duration", "bm")}
            />
            <AdminField label="Intake months" span={12}>
              <AdminSelect
                name="c_intake"
                multiple
                placeholder="Search intake months..."
                defaultValue={row.c_intake || ""}
                options={optionsFromRecord(INTAKE_MONTHS)}
              />
            </AdminField>
          </AdminFormSection>

            <AdminFormSection title="Content" description="Outline, requirements, and modules.">
            <AdminLocalizedField
              name="c_outline"
              label="Outline"
              span={12}
              kind="textarea"
              rows={3}
              defaultValue={row.c_outline || ""}
              defaultValueCh={i18nFieldValue(row, "c_outline", "ch")}
              defaultValueBm={i18nFieldValue(row, "c_outline", "bm")}
            />
            <AdminLocalizedField
              name="c_entry_req"
              label="Entry requirements"
              span={12}
              kind="textarea"
              rows={3}
              defaultValue={row.c_entry_req || ""}
              defaultValueCh={i18nFieldValue(row, "c_entry_req", "ch")}
              defaultValueBm={i18nFieldValue(row, "c_entry_req", "bm")}
            />
            <AdminLocalizedField
              name="c_english_required"
              label="English required"
              span={12}
              defaultValue={row.c_english_required || ""}
              defaultValueCh={i18nFieldValue(row, "c_english_required", "ch")}
              defaultValueBm={i18nFieldValue(row, "c_english_required", "bm")}
            />
            <AdminLocalizedField
              name="c_core"
              label="Core modules"
              span={12}
              kind="richtext"
              defaultValue={asHtml(row.c_core)}
              defaultValueCh={asHtml(i18nFieldValue(row, "c_core", "ch"))}
              defaultValueBm={asHtml(i18nFieldValue(row, "c_core", "bm"))}
            />
            <AdminLocalizedField
              name="c_elective"
              label="Elective modules"
              span={12}
              kind="richtext"
              defaultValue={asHtml(row.c_elective)}
              defaultValueCh={asHtml(i18nFieldValue(row, "c_elective", "ch"))}
              defaultValueBm={asHtml(i18nFieldValue(row, "c_elective", "bm"))}
            />
            <AdminLocalizedField
              name="c_other"
              label="Other modules"
              span={12}
              kind="richtext"
              defaultValue={asHtml(row.c_other)}
              defaultValueCh={asHtml(i18nFieldValue(row, "c_other", "ch"))}
              defaultValueBm={asHtml(i18nFieldValue(row, "c_other", "bm"))}
            />
          </AdminFormSection>
            <AdminFormActions backHref="/admin/course">
              <SubmitButton />
            </AdminFormActions>
          </AdminForm>
    </>
  );
}
