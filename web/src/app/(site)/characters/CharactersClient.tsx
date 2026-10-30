"use client";

import { useCallback, useEffect, useState } from "react";
import Link from "next/link";
import { formatMoney } from "@/lib/money";
import { useT } from "@/components/LocaleProvider";
import type { RecommendedCourseRow } from "@/lib/pathwayRecommendedCourses";
import {
  loadPathwayMatchInput,
  loadPathwayMatchResults,
  savePathwayMatchResults,
  type PathwayMatchResultCard,
} from "@/lib/pathwayQuizStorage";

import {
  PathwayUnlockCard,
  pathwayUnlockCardStyles,
} from "@/components/pathway/PathwayUnlockCard";
import { ConsultCounsellorModal } from "@/components/counsellor/ConsultCounsellorModal";
import type { Qualification } from "@/components/counsellor/CounsellorRequestForm";
function openSignupModal() {
  window.dispatchEvent(new CustomEvent("edu4u:open-modal", { detail: { kind: "signup" } }));
}

function normalizeCharacterAvatar(url: string): string {
  if (!url) return "";
  if (url.includes("/images/characters/")) return url;
  const filename = url.split("/").pop() ?? "";
  if (/^character-(male|female)-/.test(filename)) {
    return `https://edu4u.world/images/characters/${filename}`;
  }
  return url;
}

function normalizeMatchCards(cards: PathwayMatchResultCard[]): PathwayMatchResultCard[] {
  return cards.map((c) => ({ ...c, avatar: normalizeCharacterAvatar(c.avatar) }));
}

/** Display order: 2nd, 1st, 3rd (matches legacy layout). */
const DISPLAY_SLOTS = [
  { dataIndex: 1, rank: 2 },
  { dataIndex: 0, rank: 1 },
  { dataIndex: 2, rank: 3 },
] as const;

export default function CharactersClient({ qualifications }: { qualifications: Qualification[] }) {
  const t = useT();
  const [topThree, setTopThree] = useState<PathwayMatchResultCard[]>([]);
  const [activeIndex, setActiveIndex] = useState(0);
  const [loading, setLoading] = useState(true);
  const [loadError, setLoadError] = useState("");
  const [courses, setCourses] = useState<RecommendedCourseRow[]>([]);
  const [coursesLoading, setCoursesLoading] = useState(false);
  const [courseStats, setCourseStats] = useState({ courses: 0, universities: 0 });
  const [counsellorOpen, setCounsellorOpen] = useState(false);

  const fetchMatches = useCallback(async () => {
    setLoading(true);
    setLoadError("");
    const cached = loadPathwayMatchResults();
    if (cached && cached.length > 0) {
      setTopThree(normalizeMatchCards(cached));
      setLoading(false);
      return;
    }

    const input = loadPathwayMatchInput();
    if (!input) {
      setTopThree([]);
      setLoading(false);
      return;
    }

    try {
      const res = await fetch("/api/pathway360/match", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(input),
      });
      const data = (await res.json()) as { status: boolean; cards?: PathwayMatchResultCard[]; msg?: string };
      if (!res.ok || !data.status) {
        setLoadError(data.msg ?? t("somethingWentWrong"));
        setTopThree([]);
        return;
      }
      const cards = normalizeMatchCards(data.cards ?? []);
      setTopThree(cards);
      if (cards.length > 0) {
        savePathwayMatchResults(cards);
      } else {
        setLoadError(t("pathwayMatchEmpty"));
      }
    } catch {
      setLoadError(t("networkError"));
      setTopThree([]);
    } finally {
      setLoading(false);
    }
  }, [t]);

  useEffect(() => {
    void fetchMatches();
  }, [fetchMatches]);

  useEffect(() => {
    document.body.classList.add("characters-page-body");
    return () => document.body.classList.remove("characters-page-body");
  }, []);

  const loadCoursesForCharacter = useCallback(
    async (characterId: number) => {
      const prefs = loadPathwayMatchInput();
      setCoursesLoading(true);
      try {
        const res = await fetch("/api/pathway360/recommended-courses", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({
            characterId,
            studyLevel: prefs?.studyLevel,
            budget: prefs?.budget,
            income: prefs?.income,
            malaysian: prefs?.malaysian,
            uniType: prefs?.uniType,
            studyPlan: prefs?.studyPlan,
            states: prefs?.states,
            country: prefs?.country,
          }),
        });
        const data = (await res.json()) as {
          status: boolean;
          list?: RecommendedCourseRow[];
          ttl_course?: number;
          ttl_uni?: number;
        };
        if (data.status && data.list) {
          setCourses(data.list);
          setCourseStats({
            courses: data.ttl_course ?? data.list.length,
            universities: data.ttl_uni ?? 0,
          });
        } else {
          setCourses([]);
          setCourseStats({ courses: 0, universities: 0 });
        }
      } catch {
        setCourses([]);
        setCourseStats({ courses: 0, universities: 0 });
      } finally {
        setCoursesLoading(false);
      }
    },
    [],
  );

  const active = topThree[activeIndex];

  useEffect(() => {
    if (!active?.id) return;
    setCourseStats({
      courses: active.courseCount ?? 0,
      universities: active.universityCount ?? 0,
    });
    void loadCoursesForCharacter(active.id);
  }, [active?.id, active?.courseCount, active?.universityCount, loadCoursesForCharacter]);

  function selectCharacter(dataIndex: number) {
    setActiveIndex(dataIndex);
  }

  return (
    <div className="characters-page-root">
      <style>{`
        body.characters-page-body { background-color: #0D2227 !important; }
        .characters-page-root {
          background-color: #0D2227;
          flex: 1;
          width: 100%;
          min-height: 100vh;
        }
        .characters-page { padding-top: 80px; padding-bottom: 80px; }
        .result-character { font-size: 36px; font-weight: 700; color: #fff; margin-top: 48px; }
        .result-character-desc { font-size: 16px; color: #9aa8ab; margin-top: 12px; }
        ${pathwayUnlockCardStyles}
        .character-detail-panel { margin-top: 48px; }
        .character-result-desc {
          font-size: 15px;
          color: #b8c4c7;
          max-width: 720px;
          margin: 0 auto;
          line-height: 1.65;
          text-align: center;
        }
        .stats-row {
          display: grid;
          grid-template-columns: repeat(3, minmax(0, 1fr));
          gap: 16px;
          margin-top: 32px;
          max-width: 720px;
          margin-left: auto;
          margin-right: auto;
          width: 100%;
        }
        .character-result-div {
          background: rgba(255,255,255,0.06);
          border: 1px solid rgba(255,255,255,0.12);
          border-radius: 12px;
          padding: 20px 12px;
          min-width: 0;
          width: 100%;
          text-align: center;
          box-sizing: border-box;
        }
        .characters-page-root .character-result-div-ttl { font-size: 22px; font-weight: 700; color: #fff !important; }
        .characters-page-root .character-result-div-ttl-salary { color: #c8a86a !important; }
        .characters-page-root .character-result-div-ttl-desc { font-size: 12px; color: #9aa8ab !important; margin-top: 8px; }
        .action-btns {
          margin-top: 32px;
          display: grid;
          grid-template-columns: repeat(3, minmax(0, 1fr));
          gap: 12px;
          max-width: 840px;
          margin-left: auto;
          margin-right: auto;
          width: 100%;
        }
        .action-btn-link { display: block; min-width: 0; text-decoration: none; }
        .characters-page-root .action-btns .retake-test-btn,
        .characters-page-root .action-btns .talk-to-counsellor-btn {
          width: 100%;
          white-space: normal !important;
          height: auto !important;
          min-height: 48px !important;
          max-height: none !important;
          padding-top: 12px !important;
          padding-bottom: 12px !important;
        }
        @media (max-width: 767px) {
          .action-btns { grid-template-columns: 1fr; }
        }
        .characters-loading { color: #9aa8ab; margin-top: 80px; font-size: 16px; }
        .characters-page-root .personality-title-2 {
          font-size: 22px;
          font-weight: 700;
          color: #fff !important;
          margin-top: 56px;
          margin-bottom: 20px;
          text-align: center;
        }
        .pathway-course-list { margin-top: 8px; display: flex; flex-direction: column; gap: 12px; }
        .pathway-course-row {
          display: flex;
          align-items: center;
          gap: 16px;
          padding: 20px;
          border-radius: 12px;
          border: 1px solid rgba(255,255,255,0.12);
          background: rgba(255,255,255,0.05);
          text-align: left;
        }
        .pathway-course-row-logo {
          width: 56px;
          height: 56px;
          border-radius: 8px;
          object-fit: contain;
          background: #fff;
          flex-shrink: 0;
        }
        .pathway-course-row-main { flex: 1; min-width: 0; }
        .pathway-course-row-title { font-size: 16px; font-weight: 600; color: #fff; }
        .pathway-course-row-uni { font-size: 13px; color: #9aa8ab; margin-top: 6px; }
        .pathway-course-row-meta { display: flex; flex-wrap: wrap; gap: 16px; margin-top: 10px; font-size: 13px; color: #b8c4c7; }
        .pathway-course-row-fee { text-align: right; flex-shrink: 0; }
        .pathway-course-row-price { font-size: 16px; font-weight: 700; color: #fff; }
        .pathway-course-row-price-label { font-size: 12px; color: #9aa8ab; margin-top: 4px; }
        .pathway-course-apply {
          display: inline-block;
          margin-top: 10px;
          padding: 10px 18px;
          border-radius: 6px;
          border: 1px solid #fff;
          color: #fff;
          font-size: 13px;
          font-weight: 500;
          text-decoration: none;
        }
        .pathway-course-apply:hover { background: rgba(255,255,255,0.08); color: #fff; }
        @media (max-width: 767px) {
          .result-character { font-size: 24px; }
        }
      `}</style>

      <div className="characters-page mw-1200 mx-auto px-3">
        <div className="text-center">
          {loading ? (
            <p className="characters-loading">{t("analyseProfile")}…</p>
          ) : (
            <>
              <span className="d-block result-character">
                {topThree.length > 0
                  ? `${topThree.length} ${t("charactersUnlocked")}`
                  : t("yourCharacters")}
              </span>
              <span className="d-block result-character-desc">
                {topThree.length > 0 ? t("resonateMost") : t("takeQuizDiscover")}
              </span>

              {loadError && (
                <p className="mt-4" style={{ color: "#f87171" }}>
                  {loadError}
                </p>
              )}

              {topThree.length === 0 && !loadError && !loading && loadPathwayMatchInput() ? (
                <p className="mt-4 result-character-desc">{t("pathwayMatchRetry")}</p>
              ) : null}

              {topThree.length === 0 && !loadPathwayMatchInput() ? (
                <EmptyState t={t} />
              ) : null}
              {topThree.length > 0 ? (
                <>
                  <div className="character-cards-row">
                    {DISPLAY_SLOTS.map((slot) => {
                      const card = topThree[slot.dataIndex];
                      if (!card) return null;
                      const isActive = activeIndex === slot.dataIndex;
                      const rank = slot.rank as 1 | 2 | 3;
                      return (
                        <PathwayUnlockCard
                          key={card.id}
                          name={card.name}
                          avatarSrc={normalizeCharacterAvatar(card.avatar)}
                          percentage={card.percentage}
                          rank={rank}
                          isActive={isActive}
                          matchRateLabel={t("matchRate")}
                          onSelect={() => selectCharacter(slot.dataIndex)}
                        />
                      );
                    })}
                  </div>

                  {active && (
                    <div className="character-detail-panel">
                      <p className="character-result-desc">{active.desc}</p>
                      <div className="stats-row">
                        <div className="character-result-div">
                          <span className="d-block character-result-div-ttl">
                            {coursesLoading
                              ? "…"
                              : courseStats.courses || active?.courseCount || 0}
                          </span>
                          <span className="d-block character-result-div-ttl-desc">{t("numOfCourse")}</span>
                        </div>
                        <div className="character-result-div">
                          <span className="d-block character-result-div-ttl">
                            {coursesLoading
                              ? "…"
                              : courseStats.universities || active?.universityCount || 0}
                          </span>
                          <span className="d-block character-result-div-ttl-desc">{t("numOfUniversities")}</span>
                        </div>
                        <div className="character-result-div">
                          <span className="d-block character-result-div-ttl character-result-div-ttl-salary">
                            {formatMoney(active.avg_salary)}
                          </span>
                          <span className="d-block character-result-div-ttl-desc">{t("avgSalaryLabel")}</span>
                        </div>
                      </div>

                      <div className="action-btns">
                        <button
                          type="button"
                          className="retake-test-btn save-profile-btn"
                          onClick={openSignupModal}
                        >
                          {t("saveMyProfile")}
                        </button>
                        <button
                          type="button"
                          className="talk-to-counsellor-btn"
                          onClick={() => setCounsellorOpen(true)}
                        >
                          {t("talkToCounsellor")}
                        </button>
                        <Link href="/personality" className="action-btn-link">
                          <button type="button" className="talk-to-counsellor-btn">
                            {t("retakeTest")}
                          </button>
                        </Link>
                      </div>
                    </div>
                  )}

                  {active && (
                    <>
                      <h2 className="personality-title-2">{t("fieldOfStudyChoice")}</h2>
                      {coursesLoading ? (
                        <p className="result-character-desc">{t("loadingCourses")}</p>
                      ) : courses.length === 0 ? (
                        <p className="result-character-desc">{t("noRecCoursesForCharacter")}</p>
                      ) : (
                        <div className="pathway-course-list">
                          {courses.map((course) => (
                            <PathwayCourseListRow key={course.id} course={course} t={t} />
                          ))}
                        </div>
                      )}
                    </>
                  )}
                </>
              ) : null}
            </>
          )}
        </div>
      </div>

      <ConsultCounsellorModal
        open={counsellorOpen}
        onClose={() => setCounsellorOpen(false)}
        qualifications={qualifications}
        courseId={courses[0]?.id ?? null}
        pathwayCharacterName={topThree[activeIndex]?.name}
        defaultPreferredCourse={courses[0]?.name ?? topThree[activeIndex]?.name ?? ""}
      />
    </div>
  );
}

function EmptyState({ t }: { t: (key: string) => string }) {
  return (
    <div style={{ marginTop: 48 }}>
      <Link href="/personality">
        <button type="button" className="retake-test-btn">
          {t("takeTheQuiz")}
        </button>
      </Link>
    </div>
  );
}

function PathwayCourseListRow({
  course,
  t,
}: {
  course: RecommendedCourseRow;
  t: (key: string, fallback?: string) => string;
}) {
  return (
    <article className="pathway-course-row">
      {course.uni_logo ? (
        // eslint-disable-next-line @next/next/no-img-element
        <img className="pathway-course-row-logo" src={course.uni_logo} alt="" />
      ) : (
        <div className="pathway-course-row-logo" aria-hidden />
      )}
      <div className="pathway-course-row-main">
        <div className="pathway-course-row-title">{course.name}</div>
        <div className="pathway-course-row-uni">{course.uni_name}</div>
        <div className="pathway-course-row-meta">
          <span>
            {t("durationLabel")}: {course.duration}
          </span>
          <span>
            {t("intake")}: {course.intake || "—"}
          </span>
        </div>
        <Link href={`/course-application/${course.url_code}`} className="pathway-course-apply">
          {t("application")}
        </Link>
      </div>
      <div className="pathway-course-row-fee">
        <div className="pathway-course-row-price">{course.ttl_fees}</div>
        <div className="pathway-course-row-price-label">{t("totalFeeLabel")}</div>
      </div>
    </article>
  );
}
