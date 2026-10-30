import { requireAdmin } from "@/lib/admin/auth";
import { createServiceClient } from "@/lib/supabase/server";
import { Q_LEVELS } from "@/lib/admin/constants";
import {
  AdminCard,
  AdminPageHeader,
  AdminPagination,
  AdminStatusTabs,
} from "@/components/admin/AdminUi";
import { SubjectLevelsExpandableList } from "@/components/admin/SubjectLevelsExpandableList";
import { SubjectsAdminControls } from "@/components/admin/SubjectsAdminControls";
import { pageRange, parsePagination } from "@/lib/admin/pagination";
import {
  applyStatusFilter,
  countStatusTabs,
  parseStatusTab,
  STATUS,
} from "@/lib/admin/status";
import { i18nFieldValue } from "@/lib/i18n/cms";
import {
  activateSubject,
  archiveSubject,
  deactivateSubject,
  deleteSubject,
  restoreSubject,
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
  const levels = Object.entries(Q_LEVELS).map(([id, name]) => ({
    id: Number(id),
    name,
  }));
  const total = levels.length;
  const { from, to } = pageRange(page, perPage);
  const slice = levels.slice(from, to + 1);
  const levelIds = slice.map((level) => level.id);
  const keep = { status: tab === "active" ? undefined : tab };

  const supabase = createServiceClient();
  let subjectsQuery = supabase
    .from("subjects")
    .select("*")
    .in("s_level_id", levelIds.length ? levelIds : [-1])
    .order("s_name", { ascending: true });
  subjectsQuery = applyStatusFilter(subjectsQuery, "s_status", tab);

  const [{ data: subjects }, { data: cats }, counts] = await Promise.all([
    subjectsQuery,
    supabase
      .from("subject_categories")
      .select("sc_id,sc_name,sc_status,i18n")
      .order("sc_name"),
    countStatusTabs(supabase, "subjects", "s_status"),
  ]);

  const catMap = new Map(
    (cats || [])
      .filter((c) => Number(c.sc_status) === STATUS.ACTIVE)
      .map((c) => [c.sc_id, c.sc_name]),
  );

  return (
    <>
      <AdminPageHeader
        title="Subjects"
        description="Expand a level to manage its subjects."
        actions={<SubjectsAdminControls
          categories={(cats || []).map((c) => ({
            sc_id: Number(c.sc_id),
            sc_name: String(c.sc_name || ""),
            sc_status: c.sc_status == null ? null : Number(c.sc_status),
            nameCh: i18nFieldValue(c, "sc_name", "ch"),
            nameBm: i18nFieldValue(c, "sc_name", "bm"),
          }))}
        />}
      />
      <AdminStatusTabs
        basePath="/admin/subjects"
        current={tab}
        keep={{ perPage }}
        counts={counts}
      />
      <AdminCard>
        <div className="card-body p-0">
          <SubjectLevelsExpandableList
            levels={slice.map((level) => ({
              id: level.id,
              name: level.name,
              addHref: `/admin/subjects/${level.id}/new`,
            }))}
            subjects={(subjects || []).map((r) => ({
              id: Number(r.s_id),
              levelId: Number(r.s_level_id),
              name: String(r.s_name || ""),
              category: String(catMap.get(r.s_subject_cat_id) || r.s_subject_cat_id || ""),
              preselect: r.s_is_preselect == null ? null : Number(r.s_is_preselect),
              status: Number(r.s_status),
              createdAt: r.created_at ?? null,
              editHref: `/admin/subjects/${r.s_level_id}/${r.s_id}`,
              onActivate: activateSubject.bind(null, Number(r.s_id), Number(r.s_level_id)),
              onDeactivate: deactivateSubject.bind(null, Number(r.s_id), Number(r.s_level_id)),
              onArchive: archiveSubject.bind(null, Number(r.s_id), Number(r.s_level_id)),
              onRestore: restoreSubject.bind(null, Number(r.s_id), Number(r.s_level_id)),
              onDelete: deleteSubject.bind(null, Number(r.s_id), Number(r.s_level_id)),
            }))}
          />
          <AdminPagination
            basePath="/admin/subjects"
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
