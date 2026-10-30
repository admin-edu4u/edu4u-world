import { requireAdmin } from "@/lib/admin/auth";
import { createServiceClient } from "@/lib/supabase/server";
import {
  AdminBtn,
  AdminCard,
  AdminPageHeader,
  AdminPagination,
  AdminStatusTabs,
  labelFromMap,
} from "@/components/admin/AdminUi";
import { CategoryExpandableList } from "@/components/admin/CategoryExpandableList";
import { pageRange, parsePagination } from "@/lib/admin/pagination";
import { applyStatusFilter, countStatusTabs, parseStatusTab } from "@/lib/admin/status";
import {
  activateCategory,
  activateSubCat,
  archiveCategory,
  archiveSubCat,
  deactivateCategory,
  deactivateSubCat,
  deleteCategory,
  deleteSubCat,
  restoreCategory,
  restoreSubCat,
} from "./actions";

export default async function Page({
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

  let catsQuery = supabase
    .from("categories")
    .select("*", { count: "exact" })
    .order("c_name", { ascending: true });
  catsQuery = applyStatusFilter(catsQuery, "c_status", tab);

  const [{ data: cats, count }, { data: allSubs }, { data: chars }, counts] = await Promise.all([
    catsQuery.range(from, to),
    (() => {
      let q = supabase.from("sub_cats").select("*").order("sc_name", { ascending: true });
      q = applyStatusFilter(q, "sc_status", tab);
      return q;
    })(),
    supabase.from("characters").select("c_id,c_name").order("c_name"),
    countStatusTabs(supabase, "categories", "c_status"),
  ]);

  const total = count ?? 0;
  const catIds = new Set((cats || []).map((c) => Number(c.c_id)));
  const charMap = new Map((chars || []).map((c) => [c.c_id, c.c_name]));
  const subs = (allSubs || []).filter((s) => catIds.has(Number(s.sc_main)));

  return (
    <>
      <AdminPageHeader
        title="Course categories"
        description="Expand a category to manage its sub categories."
        actions={
          <>
            <AdminBtn href="/admin/category/sub/new" variant="secondary">
              Add sub category
            </AdminBtn>
            <AdminBtn href="/admin/category/new">Add category</AdminBtn>
          </>
        }
      />
      <AdminStatusTabs basePath="/admin/category" current={tab} keep={{ perPage }} counts={counts} />
      <AdminCard>
        <div className="card-body p-0">
          <CategoryExpandableList
            cats={(cats || []).map((r) => ({
              id: Number(r.c_id),
              name: String(r.c_name || ""),
              img: r.c_img ?? null,
              status: Number(r.c_status),
              createdAt: r.created_at ?? null,
              editHref: `/admin/category/${r.c_id}`,
              addSubHref: `/admin/category/sub/new?main=${r.c_id}`,
              onActivate: activateCategory.bind(null, Number(r.c_id)),
              onDeactivate: deactivateCategory.bind(null, Number(r.c_id)),
              onArchive: archiveCategory.bind(null, Number(r.c_id)),
              onRestore: restoreCategory.bind(null, Number(r.c_id)),
              onDelete: deleteCategory.bind(null, Number(r.c_id)),
            }))}
            subs={subs.map((r) => ({
              id: Number(r.sc_id),
              mainId: Number(r.sc_main),
              name: String(r.sc_name || ""),
              character: (() => {
                if (!r.sc_character || Number(r.sc_character) === 0) return "";
                const name = labelFromMap(charMap, r.sc_character, "");
                return name || "";
              })(),
              status: Number(r.sc_status),
              createdAt: r.created_at ?? null,
              editHref: `/admin/category/sub/${r.sc_id}`,
              onActivate: activateSubCat.bind(null, Number(r.sc_id)),
              onDeactivate: deactivateSubCat.bind(null, Number(r.sc_id)),
              onArchive: archiveSubCat.bind(null, Number(r.sc_id)),
              onRestore: restoreSubCat.bind(null, Number(r.sc_id)),
              onDelete: deleteSubCat.bind(null, Number(r.sc_id)),
            }))}
          />
          <AdminPagination
            basePath="/admin/category"
            page={page}
            perPage={perPage}
            total={total}
            keep={keep}
          />
        </div>
      </AdminCard>
    </>
  );
}
