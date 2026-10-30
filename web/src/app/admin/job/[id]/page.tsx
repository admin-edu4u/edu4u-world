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
import { saveJob } from "../actions";

export default async function Page({ params }: { params: Promise<{ id: string }> }) {
  await requireAdmin();
  const { id } = await params;
  const supabase = createServiceClient();
  const [{ data: row }, { data: cats }] = await Promise.all([
    supabase.from("careers").select("*").eq("c_id", Number(id)).maybeSingle(),
    supabase.from("career_categories").select("cc_id,cc_title").order("cc_title"),
  ]);
  if (!row) notFound();
  return (
    <>
      <AdminPageHeader title="Edit career" />
      <AdminForm action={saveJob}>
            <AdminFormSection title="Basics" description="Title, type, location, and pay.">
            <input type="hidden" name="c_id" value={row.c_id} />
            <AdminLocalizedField
              name="c_title"
              label="Title"
              span={8}
              required
              defaultValue={row.c_title || ""}
              defaultValueCh={i18nFieldValue(row, "c_title", "ch")}
              defaultValueBm={i18nFieldValue(row, "c_title", "bm")}
            />
            <AdminField label="Type" span={6}>
              <AdminSelect
                name="c_type"
                defaultValue={row.c_type ?? 1}
                placeholder="Search type..."
                options={[
                  { value: "1", label: "Full-time" },
                  { value: "2", label: "Part-time" },
                ]}
              />
            </AdminField>
            <AdminField label="Location" span={6}>
              <input name="c_state" className="form-control" defaultValue={row.c_state || ""} />
            </AdminField>
            <AdminField label="Salary min" span={6}>
              <input name="c_salary_min" type="number" className="form-control" defaultValue={row.c_salary_min || 0} />
            </AdminField>
            <AdminField label="Salary max" span={6}>
              <input name="c_salary_max" type="number" className="form-control" defaultValue={row.c_salary_max || 0} />
            </AdminField>
          </AdminFormSection>

            <AdminFormSection title="Settings" description="Category, status, and application URL.">
            <AdminField label="Category" span={4}>
              <AdminSelect
                name="c_category"
                placeholder="Search category..."
                defaultValue={row.c_category || ""}
                options={(cats || []).map((c) => ({ value: String(c.cc_id), label: c.cc_title }))}
              />
            </AdminField>
            <AdminStatusSelect name="c_status" value={row.c_status} />
            <AdminField label="URL" span={12}>
              <input name="c_url" className="form-control" defaultValue={row.c_url || ""} />
            </AdminField>
          </AdminFormSection>
            <AdminFormActions backHref="/admin/job">
              <SubmitButton />
            </AdminFormActions>
          </AdminForm>
    </>
  );
}
