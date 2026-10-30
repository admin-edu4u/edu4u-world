import { notFound } from "next/navigation";
import { requireAdmin } from "@/lib/admin/auth";
import { createServiceClient } from "@/lib/supabase/server";
import { COURSE_LEVELS, COUNTRY_OPTIONS, STREAM_OPTIONS } from "@/lib/admin/constants";
import {
  AdminCard,
  AdminDetailList,
  AdminBtn,
  AdminPageHeader,
  AdminThumb,
  formatAdminDateTime,
  formatYesNo,
  labelFromMap,
} from "@/components/admin/AdminUi";
import { DeleteButton } from "@/components/admin/DeleteButton";
import { parseCsvIds } from "@/lib/admin/constants";
import { resolveUserCharacterScores } from "@/lib/admin/resolveUserCharacterScores";
import { UserDeleteButton } from "../UserDeleteButton";

const GENDER_LABELS: Record<number, string> = {
  1: "Male",
  2: "Female",
};

const NATIONALITY_LABELS: Record<number, string> = {
  1: "Malaysian",
  2: "Foreigner",
};

const EDU_LEVEL_LABELS: Record<number, string> = {
  1: "Secondary",
  2: "Pre-University",
  3: "Others",
};

const QUALIFICATION_LABELS: Record<number, string> = {
  1: "SPM",
  2: "O-Level",
  3: "STPM",
  4: "UEC",
  5: "Foundation",
  6: "Diploma",
  7: "A-Level",
  8: "IB Diploma",
  9: "CPU/CIMP",
  10: "SACE/WACE",
};

const RESULT_TYPE_LABELS: Record<number, string> = {
  1: "Final result",
  2: "Trial / forecast result",
  3: "Partial results or monthly / semester exam",
};

const REPRESENT_LEVEL_LABELS: Record<number, string> = {
  1: "School",
  2: "District",
  3: "State",
  4: "National",
};

const LEADERSHIP_ROLE_LABELS: Record<number, string> = {
  1: "President",
  2: "Vice President",
  3: "Secretary",
  4: "Treasurer",
  5: "Director / Team Lead",
  6: "Others",
};

const INCOME_LABELS: Record<number, string> = {
  1: "Below MYR 4,000",
  2: "Below MYR 8,000",
  3: "Above MYR 8,000",
};

const BUDGET_LABELS: Record<number, string> = {
  1: "Below MYR 20,000",
  2: "MYR 20,001 - MYR 40,000",
  3: "MYR 40,001 - MYR 60,000",
  4: "MYR 60,001 - MYR 80,000",
  5: "MYR 80,001 - MYR 100,000",
  6: "Above MYR 100,000",
};

const BUMIPUTERA_LABELS: Record<number, string> = {
  1: "Yes",
  2: "No",
};

const PATHWAY_LABELS: Record<number, string> = {
  ...COURSE_LEVELS,
};

const START_DATE_LABELS: Record<number, string> = {
  1: "Jan - Mar",
  2: "Apr - Jun",
  3: "Jul - Sep",
  4: "Oct - Dec",
};

const LOCATION_LABELS: Record<number, string> = {
  1: "Malaysia, for my entire studies",
  2: "Malaysia, but I plan to go overseas later",
  3: "Overseas, for my entire studies",
};

const STATE_LABELS: Record<number, string> = {
  1: "Johor",
  2: "Kedah",
  3: "Kelantan",
  4: "Melaka",
  5: "Negeri Sembilan",
  6: "Pahang",
  7: "Perak",
  8: "Perlis",
  9: "Pulau Pinang",
  10: "Selangor",
  11: "Terengganu",
  12: "Kuala Lumpur",
  13: "Putrajaya",
  14: "Sarawak",
  15: "Sabah",
  16: "Labuan",
};

const UNI_TYPE_LABELS: Record<number, string> = {
  1: "Private university",
  2: "Public university",
  3: "Both private & public university",
};

const COUNTRY_MAP = new Map(COUNTRY_OPTIONS.map((c) => [Number(c.value), c.label]));

function mapLabel(map: Record<number, string>, value: unknown, fallback = "-"): string {
  if (value == null || value === "" || value === 0 || value === "0") return fallback;
  const key = typeof value === "string" ? Number(value) : Number(value);
  if (!Number.isFinite(key)) return fallback;
  return map[key] ?? fallback;
}

function display(value: unknown, fallback = "-"): string {
  if (value == null || value === "") return fallback;
  return String(value);
}

function formatDob(day: number | null | undefined, month: number | null | undefined, year: number | null | undefined): string {
  if (!day || !month || !year) return "-";
  return `${day}/${month}/${year}`;
}

function formatContact(code: string | null | undefined, no: string | null | undefined): string {
  if (!no) return "-";
  return code ? `+${code}${no}` : no;
}

function TagList({ labels }: { labels: string[] }) {
  if (!labels.length) return <>-</>;
  return (
    <span className="admin-user-tags">
      {labels.map((label) => (
        <span key={label} className="admin-user-tag">
          {label}
        </span>
      ))}
    </span>
  );
}

export default async function Page({ params }: { params: Promise<{ id: string }> }) {
  await requireAdmin();
  const { id } = await params;
  const userId = Number(id);
  if (!Number.isFinite(userId)) notFound();

  const supabase = createServiceClient();
  const { data: row } = await supabase.from("app_users").select("*").eq("id", userId).maybeSingle();
  if (!row) notFound();

  const personalityIds = parseCsvIds(row.personality);
  const interestIds = parseCsvIds(row.interests);
  const personalityLookupIds = [...new Set([...personalityIds, ...interestIds].map(Number).filter(Number.isFinite))];
  const characterScores = await resolveUserCharacterScores(supabase, row);
  const characterIds = characterScores.map((c) => c.id);

  const [
    { data: personalities },
    { data: characters },
    { data: categories },
    { data: subCats },
    { data: courses },
    { data: academic },
    { data: achievements },
    { data: family },
    { data: uniPref },
    { data: results },
  ] = await Promise.all([
    personalityLookupIds.length
      ? supabase.from("personalities").select("p_id,p_name").in("p_id", personalityLookupIds)
      : Promise.resolve({ data: [] as { p_id: number; p_name: string }[] }),
    characterIds.length
      ? supabase.from("characters").select("c_id,c_name,c_img_male,c_img_female").in("c_id", characterIds)
      : Promise.resolve({ data: [] as { c_id: number; c_name: string; c_img_male: string | null; c_img_female: string | null }[] }),
    supabase.from("categories").select("c_id,c_name"),
    supabase.from("sub_cats").select("sc_id,sc_name"),
    row.preferred_course
      ? supabase.from("courses").select("c_id,c_name").eq("c_id", row.preferred_course).maybeSingle()
      : Promise.resolve({ data: null }),
    supabase.from("academics").select("*").eq("a_user_id", userId).maybeSingle(),
    supabase.from("achievements").select("*").eq("a_user_id", userId),
    supabase.from("families").select("*").eq("f_user_id", userId).maybeSingle(),
    supabase.from("university_preferences").select("*").eq("up_user_id", userId).maybeSingle(),
    supabase.from("results").select("*").eq("r_user_id", userId).eq("r_status", 1),
  ]);

  const personalityMap = new Map((personalities || []).map((p) => [p.p_id, p.p_name]));
  const characterMap = new Map((characters || []).map((c) => [c.c_id, c]));
  const categoryMap = new Map((categories || []).map((c) => [c.c_id, c.c_name]));
  const subCatMap = new Map((subCats || []).map((c) => [c.sc_id, c.sc_name]));

  const subjectIds = (results || []).map((r) => r.r_subject_id).filter(Boolean);
  const gradeIds = (results || []).map((r) => r.r_grade).filter(Boolean);
  const [{ data: subjects }, { data: grades }] = await Promise.all([
    subjectIds.length
      ? supabase.from("subjects").select("s_id,s_name").in("s_id", subjectIds)
      : Promise.resolve({ data: [] as { s_id: number; s_name: string }[] }),
    gradeIds.length
      ? supabase.from("subject_grades").select("sg_id,sg_grade").in("sg_id", gradeIds)
      : Promise.resolve({ data: [] as { sg_id: number; sg_grade: string | null }[] }),
  ]);
  const subjectMap = new Map((subjects || []).map((s) => [s.s_id, s.s_name]));
  const gradeMap = new Map((grades || []).map((g) => [g.sg_id, g.sg_grade || "-"]));

  const displayName =
    row.fullname || [row.first_name, row.last_name].filter(Boolean).join(" ") || row.username || "User";
  const personalityLabels = personalityIds
    .map((pid) => labelFromMap(personalityMap, pid, ""))
    .filter(Boolean);
  const interestLabels = interestIds
    .map((pid) => labelFromMap(personalityMap, pid, ""))
    .filter(Boolean);

  const sports = (achievements || []).find((a) => Number(a.a_type) === 1);
  const talents = (achievements || []).find((a) => Number(a.a_type) === 2);
  const leadership = (achievements || []).find((a) => Number(a.a_type) === 3);

  const resultType = results?.[0]?.r_type;
  const cgpaOnly = (results || []).length === 1 && results?.[0]?.r_cgpa;

  const characterImageFolder = "characters";
  const isMale = Number(row.gender) === 1;

  return (
    <>
      <AdminPageHeader
        title={displayName}
        description={row.username ? `@${row.username}` : undefined}
        actions={
          <div className="admin-actions">
            <AdminBtn href="/admin/users" variant="secondary">
              Back
            </AdminBtn>
            {!Number(row.isadmin ?? row.isAdmin) ? <UserDeleteButton userId={userId} /> : null}
          </div>
        }
      />

      <AdminCard>
        <div className="admin-user-hero">
          <AdminThumb folder="profile" filename={row.profile_pic} alt="" />
          <div className="admin-user-hero-text">
            <h2>{displayName}</h2>
            <p>{row.email || "-"}</p>
            <div className="admin-user-hero-meta">
              <span>{mapLabel(GENDER_LABELS, row.gender)}</span>
              <span>{formatContact(row.contact_code, row.contact_no)}</span>
              <span>{mapLabel(NATIONALITY_LABELS, row.is_malaysian)}</span>
            </div>
          </div>
        </div>
      </AdminCard>

      <div className="admin-user-sections">
        <AdminCard>
          <div className="admin-user-section-head">
            <h3>Profile</h3>
          </div>
          <AdminDetailList
            items={[
              { label: "Username", value: display(row.username) },
              { label: "Full name", value: display(row.fullname) },
              { label: "First name", value: display(row.first_name) },
              { label: "Last name", value: display(row.last_name) },
              { label: "Date of birth", value: formatDob(row.dob_day, row.dob_month, row.dob_year) },
              { label: "Gender", value: mapLabel(GENDER_LABELS, row.gender) },
              { label: "Nationality", value: mapLabel(NATIONALITY_LABELS, row.is_malaysian) },
              { label: "Active", value: formatYesNo(row.isactive ?? row.isActive) },
              { label: "Admin", value: formatYesNo(row.isadmin ?? row.isAdmin) },
              { label: "Created", value: formatAdminDateTime(row.created_at) },
            ]}
          />
        </AdminCard>

        <AdminCard>
          <div className="admin-user-section-head">
            <h3>Preferences</h3>
          </div>
          <AdminDetailList
            items={[
              {
                label: "Personality",
                value: <TagList labels={personalityLabels} />,
              },
              {
                label: "Interests",
                value: <TagList labels={interestLabels} />,
              },
              {
                label: "Preferred field of study",
                value: labelFromMap(categoryMap, row.pref_main_cat),
              },
              {
                label: "Preferred major",
                value: labelFromMap(subCatMap, row.pref_sub_cat),
              },
              {
                label: "Preferred course",
                value: courses?.c_name || "-",
              },
              {
                label: "Study stream",
                value: mapLabel(STREAM_OPTIONS, row.stream),
              },
            ]}
          />
        </AdminCard>

        <AdminCard>
          <div className="admin-user-section-head">
            <h3>Education</h3>
          </div>
          <AdminDetailList
            items={[
              { label: "Educational level", value: mapLabel(EDU_LEVEL_LABELS, academic?.a_level) },
              { label: "Qualification", value: mapLabel(QUALIFICATION_LABELS, academic?.a_qualification) },
              { label: "School", value: display(academic?.a_school) },
              { label: "Results type", value: mapLabel(RESULT_TYPE_LABELS, resultType) },
              ...(cgpaOnly
                ? [{ label: "CGPA / score", value: display(results?.[0]?.r_cgpa) }]
                : (results || [])
                    .filter((r) => r.r_subject_id)
                    .map((r) => ({
                      label: labelFromMap(subjectMap, r.r_subject_id, "Subject"),
                      value: labelFromMap(gradeMap, r.r_grade),
                    }))),
              {
                label: "Sports representation",
                value: sports ? formatYesNo(sports.a_is_represent) : "-",
              },
              ...(sports && Number(sports.a_is_represent) === 1
                ? [
                    { label: "Sports level", value: mapLabel(REPRESENT_LEVEL_LABELS, sports.a_level) },
                    { label: "Sports", value: display(sports.a_represent) },
                  ]
                : []),
              {
                label: "Talents representation",
                value: talents ? formatYesNo(talents.a_is_represent) : "-",
              },
              ...(talents && Number(talents.a_is_represent) === 1
                ? [
                    { label: "Talents level", value: mapLabel(REPRESENT_LEVEL_LABELS, talents.a_level) },
                    { label: "Talents", value: display(talents.a_represent) },
                  ]
                : []),
              {
                label: "Leadership role",
                value: leadership ? formatYesNo(leadership.a_is_represent) : "-",
              },
              ...(leadership && Number(leadership.a_is_represent) === 1
                ? [
                    { label: "Role", value: mapLabel(LEADERSHIP_ROLE_LABELS, leadership.a_role) },
                    { label: "Leadership level", value: mapLabel(REPRESENT_LEVEL_LABELS, leadership.a_level) },
                    { label: "Society / event", value: display(leadership.a_represent) },
                  ]
                : []),
            ]}
          />
        </AdminCard>

        <AdminCard>
          <div className="admin-user-section-head">
            <h3>Family & university preferences</h3>
          </div>
          <AdminDetailList
            items={[
              { label: "Family monthly income", value: mapLabel(INCOME_LABELS, family?.f_income) },
              { label: "Budget for course", value: mapLabel(BUDGET_LABELS, family?.f_budget) },
              { label: "Bumiputera", value: mapLabel(BUMIPUTERA_LABELS, family?.f_is_bumiputera) },
              { label: "Preferred pathway", value: mapLabel(PATHWAY_LABELS, uniPref?.up_pathway) },
              { label: "Planning start date", value: mapLabel(START_DATE_LABELS, uniPref?.up_start) },
              { label: "Preferred location", value: mapLabel(LOCATION_LABELS, uniPref?.up_location) },
              {
                label: "Preferred state",
                value: mapLabel(STATE_LABELS, uniPref?.up_state),
              },
              {
                label: "Preferred country",
                value: labelFromMap(COUNTRY_MAP, uniPref?.up_country),
              },
              { label: "Preferred university type", value: mapLabel(UNI_TYPE_LABELS, uniPref?.up_type) },
              { label: "Paid register fee", value: uniPref ? formatYesNo(uniPref.up_is_fee_paid) : "-" },
              { label: "Registered university", value: display(uniPref?.up_uni_name) },
            ]}
          />
        </AdminCard>

        <AdminCard>
          <div className="admin-user-section-head">
            <h3>Characters</h3>
          </div>
          {characterScores.length ? (
            <div className="admin-user-characters">
              {characterScores
                .slice()
                .sort((a, b) => (b.percentage || 0) - (a.percentage || 0))
                .slice(0, 3)
                .map((score) => {
                  const character = characterMap.get(score.id);
                  const img = isMale ? character?.c_img_male : character?.c_img_female;
                  return (
                    <div key={score.id} className="admin-user-character">
                      <AdminThumb folder={characterImageFolder} filename={img} alt="" />
                      <div className="admin-entity-text">
                        <span className="admin-entity-title">{character?.c_name || `Character ${score.id}`}</span>
                        <span className="admin-entity-meta">
                          {Number.isFinite(score.percentage) ? `${score.percentage}%` : "-"}
                        </span>
                      </div>
                    </div>
                  );
                })}
            </div>
          ) : (
            <p className="admin-user-empty">No characters selected.</p>
          )}
        </AdminCard>
      </div>
    </>
  );
}
