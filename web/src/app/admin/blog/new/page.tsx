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
import { STATUS } from "@/lib/admin/status";
import { saveBlog } from "../actions";

export default async function NewBlogPage() {
  await requireAdmin();
  const supabase = createServiceClient();
  const { data: cats } = await supabase.from("blog_cats").select("bc_id,bc_name").order("bc_name");

  return (
    <>
      <AdminPageHeader title="Add blog" />
      <AdminForm action={saveBlog} encType="multipart/form-data">
        <AdminFormSection title="Basics" description="Title, category, URL, and status.">
          <AdminLocalizedField name="b_title" label="Title" span={8} required />
          <AdminField label="Category" span={4}>
            <AdminSelect
              name="b_cat_id"
              required
              placeholder="Search category..."
              options={(cats || []).map((c) => ({ value: String(c.bc_id), label: c.bc_name }))}
            />
          </AdminField>
          <AdminField label="URL code" span={8}>
            <input name="b_url_code" className="form-control" />
          </AdminField>
          <AdminStatusSelect name="b_status" value={STATUS.ACTIVE} />
        </AdminFormSection>
        <AdminFormSection title="Media" description="Featured image.">
          <AdminImageField name="img" label="Image" folder="blogs" />
        </AdminFormSection>
        <AdminFormSection title="Content" description="HTML body.">
          <AdminLocalizedField name="b_content" label="Content (HTML)" span={12} kind="textarea" rows={12} />
        </AdminFormSection>
        <AdminFormActions backHref="/admin/blog">
          <SubmitButton />
        </AdminFormActions>
      </AdminForm>
    </>
  );
}
