import { notFound } from "next/navigation";
import { requireAdmin } from "@/lib/admin/auth";
import { createServiceClient } from "@/lib/supabase/server";
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
import { saveBlog } from "../actions";

export default async function EditBlogPage({ params }: { params: Promise<{ id: string }> }) {
  await requireAdmin();
  const { id } = await params;
  const supabase = createServiceClient();
  const [{ data: blog }, { data: cats }] = await Promise.all([
    supabase.from("blogs").select("*").eq("b_id", Number(id)).maybeSingle(),
    supabase.from("blog_cats").select("bc_id,bc_name").order("bc_name"),
  ]);
  if (!blog) notFound();

  return (
    <>
      <AdminPageHeader title="Edit blog" />
      <AdminForm action={saveBlog} encType="multipart/form-data">
        <input type="hidden" name="b_id" value={blog.b_id} />
        <AdminFormSection title="Basics" description="Title, category, URL, and status.">
          <AdminLocalizedField
            name="b_title"
            label="Title"
            span={8}
            required
            defaultValue={blog.b_title || ""}
            defaultValueCh={i18nFieldValue(blog, "b_title", "ch")}
            defaultValueBm={i18nFieldValue(blog, "b_title", "bm")}
          />
          <AdminField label="Category" span={4}>
            <AdminSelect
              name="b_cat_id"
              required
              placeholder="Search category..."
              defaultValue={blog.b_cat_id || ""}
              options={(cats || []).map((c) => ({ value: String(c.bc_id), label: c.bc_name }))}
            />
          </AdminField>
          <AdminField label="URL code" span={8}>
            <input name="b_url_code" className="form-control" defaultValue={blog.b_url_code || ""} />
          </AdminField>
          <AdminStatusSelect name="b_status" value={blog.b_status} />
        </AdminFormSection>
        <AdminFormSection title="Media" description="Featured image.">
          <AdminImageField name="img" label="Replace image" folder="blogs" filename={blog.b_img} />
        </AdminFormSection>
        <AdminFormSection title="Content" description="HTML body.">
          <AdminLocalizedField
            name="b_content"
            label="Content (HTML)"
            span={12}
            kind="textarea"
            rows={12}
            defaultValue={blog.b_content || ""}
            defaultValueCh={i18nFieldValue(blog, "b_content", "ch")}
            defaultValueBm={i18nFieldValue(blog, "b_content", "bm")}
          />
        </AdminFormSection>
        <AdminFormActions backHref="/admin/blog">
          <SubmitButton />
        </AdminFormActions>
      </AdminForm>
    </>
  );
}
