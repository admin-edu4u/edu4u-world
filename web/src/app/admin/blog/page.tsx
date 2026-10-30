import { requireAdmin } from "@/lib/admin/auth";
import { createServiceClient } from "@/lib/supabase/server";
import {
  AdminCard,
  AdminPageHeader,
  AdminPagination,
  AdminRow,
  AdminStatusActions,
  AdminStatusTabs,
  AdminTable,
  AdminThumb,
  EmptyState,
  formatAdminDateTime,
  formatYesNo,
} from "@/components/admin/AdminUi";
import { BlogAdminControls } from "@/components/admin/BlogAdminControls";
import { DeleteButton } from "@/components/admin/DeleteButton";
import { pageRange, parsePagination } from "@/lib/admin/pagination";
import { applyStatusFilter, countStatusTabs, parseStatusTab, statusLabel } from "@/lib/admin/status";
import { i18nFieldValue } from "@/lib/i18n/cms";
import {
  activateBlog,
  archiveBlog,
  deactivateBlog,
  pinBlog,
  restoreBlog,
  deleteBlog,
} from "./actions";

export default async function AdminBlogPage({
  searchParams,
}: {
  searchParams: Promise<{ status?: string; page?: string; perPage?: string }>;
}) {
  await requireAdmin();
  const sp = await searchParams;
  const tab = parseStatusTab(sp.status);
  const { page, perPage } = parsePagination(sp);
  const { from, to } = pageRange(page, perPage);
  const keep = { status: tab === "active" ? undefined : tab };
  const supabase = createServiceClient();
  let query = supabase.from("blogs").select("*", { count: "exact" }).order("b_id", { ascending: false });
  query = applyStatusFilter(query, "b_status", tab);
  const [{ data: blogs, count }, { data: cats }, counts] = await Promise.all([
    query.range(from, to),
    supabase.from("blog_cats").select("bc_id,bc_name,bc_status,i18n").order("bc_name"),
    countStatusTabs(supabase, "blogs", "b_status"),
  ]);
  const catMap = new Map((cats || []).map((c) => [c.bc_id, c.bc_name]));
  const total = count ?? 0;

  return (
    <>
      <AdminPageHeader
        title="Blog"
        actions={
          <BlogAdminControls
            categories={(cats || []).map((c) => ({
              bc_id: Number(c.bc_id),
              bc_name: String(c.bc_name || ""),
              bc_status: c.bc_status == null ? null : Number(c.bc_status),
              nameCh: i18nFieldValue(c, "bc_name", "ch"),
              nameBm: i18nFieldValue(c, "bc_name", "bm"),
            }))}
          />
        }
      />
      <AdminStatusTabs basePath="/admin/blog" current={tab} keep={{ perPage }} counts={counts} />
      <AdminCard>
        <div className="card-body p-0">
          {!blogs?.length ? (
            <EmptyState message="No blogs in this view." />
          ) : (
            <AdminTable headers={["Title", "Category", "Status", "Star", "Created", ""]}>
              {blogs.map((b) => {
                const href = `/admin/blog/${b.b_id}`;
                return (
                  <AdminRow key={b.b_id} href={href}>
                    <td className="admin-table-primary">
                      <div className="admin-entity-cell">
                        <AdminThumb folder="blogs" filename={b.b_img} alt="" />
                        <div className="admin-entity-text">
                          <span className="admin-entity-title">{b.b_title}</span>
                        </div>
                      </div>
                    </td>
                    <td>{catMap.get(b.b_cat_id) || b.b_cat_id}</td>
                    <td>{statusLabel(b.b_status)}</td>
                    <td>{formatYesNo(b.b_is_star)}</td>
                    <td className="admin-table-date">{formatAdminDateTime(b.created_at)}</td>
                    <td className="admin-table-actions">
                      <AdminStatusActions
                        status={b.b_status}
                        editHref={href}
                        leading={
                          <DeleteButton
                            action={pinBlog.bind(null, b.b_id, b.b_is_star ? 0 : 1)}
                            label={b.b_is_star ? "Unpin" : "Pin"}
                            requireConfirm={false}
                          />
                        }
                        onActivate={activateBlog.bind(null, b.b_id)}
                        onDeactivate={deactivateBlog.bind(null, b.b_id)}
                        onArchive={archiveBlog.bind(null, b.b_id)}
                        onRestore={restoreBlog.bind(null, b.b_id)}
                        onDelete={deleteBlog.bind(null, b.b_id)}
                      />
                    </td>
                  </AdminRow>
                );
              })}
            </AdminTable>
          )}
          <AdminPagination basePath="/admin/blog" page={page} perPage={perPage} total={total} keep={keep} />
        </div>
      </AdminCard>
    </>
  );
}
