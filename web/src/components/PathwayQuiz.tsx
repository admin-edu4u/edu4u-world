"use client";

import { useEffect, useMemo, useRef, useState } from "react";
import Link from "next/link";
import { useRouter } from "next/navigation";
import { useT } from "@/components/LocaleProvider";
import {
  DESCRIBE_OPTIONS,
  INTEREST_OPTIONS,
} from "@/data/personalityOptions";
import { PREFERRED_PATHWAY_OPTIONS } from "@/data/pathwayStudyLevels";
import {
  PATHWAY_ABROAD_COUNTRY_OPTIONS,
  PATHWAY_ACADEMIC_LEVEL_OPTIONS,
  PATHWAY_BUDGET_OPTIONS,
  PATHWAY_INCOME_OPTIONS,
  PATHWAY_JOIN_OPTIONS,
  PATHWAY_LOADING_MSG_KEYS,
  PATHWAY_QUAL_POST_SPM,
  PATHWAY_QUAL_SPM,
  PATHWAY_REPRESENT_LEVELS,
  PATHWAY_ROLE_KEYS,
  PATHWAY_STATE_KEYS,
  PATHWAY_STEP_LABEL_KEYS,
  PATHWAY_STREAM_OPTIONS,
  PATHWAY_STUDY_PLAN_OPTIONS,
  personalityDictKey,
} from "@/lib/i18n/pathwayQuizMeta";
import { PathwayStudyOverview } from "@/components/PathwayStudyOverview";
import { MalaysianPhoneInput } from "@/components/ui/MalaysianPhoneInput";
import { SchoolSearchInput } from "@/components/ui/SchoolSearchInput";
import { malaysianPhoneError, formatMalaysianPhoneInternational } from "@/lib/phone";
import { birthdayError, sanitizeBirthdayDigits } from "@/lib/birthday";
import {
  PathwaySubjectPicker,
} from "@/components/PathwaySubjectPicker";
import {
  loadPathwayQuizProgress,
  savePathwayQuizProgress,
  clearPathwayQuizProgress,
  savePathwayMatchInput,
  savePathwayMatchResults,
  type PathwayMatchResultCard,
  type PathwayQuizFormState,
} from "@/lib/pathwayQuizStorage";

const CGPA_LEVEL_IDS = new Set([5, 6, 8, 9, 10, 13, 14, 15, 16, 17]);

function usesCgpaResults(levelId?: number) {
  return levelId != null && CGPA_LEVEL_IDS.has(levelId);
}

function qualLabelKeyForLevelId(levelId?: number): string | null {
  if (levelId == null) return null;
  const fromSpm = PATHWAY_QUAL_SPM.find((q) => q.id === levelId);
  if (fromSpm) return fromSpm.key;
  const fromPost = PATHWAY_QUAL_POST_SPM.find((q) => q.id === levelId);
  return fromPost?.key ?? null;
}

function resolveLevelLabel(
  form: FormState,
  t: (key: string, fallback?: string) => string,
): string {
  if (form.academic === 3) return t("thisQualification");
  const key = qualLabelKeyForLevelId(form.levelId);
  if (key) return t(key);
  return form.levelName || t("thisQualification");
}

function Choice({
  active,
  onClick,
  children,
  className = "",
}: {
  active?: boolean;
  onClick?: () => void;
  children: React.ReactNode;
  className?: string;
}) {
  return (
    <button
      type="button"
      className={`pathway-choice${active ? " is-active" : ""}${className ? ` ${className}` : ""}`}
      aria-pressed={!!active}
      onClick={onClick}
    >
      {children}
    </button>
  );
}

type FormState = PathwayQuizFormState;

const initialForm: FormState = {
  phone: "",
  dobDay: "",
  dobMonth: "",
  dobYear: "",
  describe: [],
  interest: [],
  levelName: "SPM",
  schoolName: "",
  sportName: "",
  talentName: "",
  societyName: "",
  states: [],
  uniRegName: "",
  subjects: [],
};

export function PathwayQuiz() {
  const t = useT();
  const router = useRouter();
  const [tab, setTab] = useState(0);
  const [form, setForm] = useState<FormState>(initialForm);
  const [error, setError] = useState("");
  const [loadingMsg, setLoadingMsg] = useState("");
  const [bootstrapped, setBootstrapped] = useState(false);
  const persistReady = useRef(false);
  const formRef = useRef(form);
  formRef.current = form;

  const phoneMessages = useMemo(
    () => ({
      required: t("errPhoneRequired"),
      invalid: t("errPhoneInvalid"),
      tooShort: t("errPhoneShort"),
      tooLong: t("errPhoneLong"),
    }),
    [t],
  );

  const birthdayMessages = useMemo(
    () => ({
      required: t("errBirthdayRequired"),
      year: t("errBirthdayYear"),
      invalid: t("errBirthdayInvalid"),
      invalidDate: t("errBirthdayDate"),
      past: t("errBirthdayPast"),
      minAge: (minAge: number) => t("errBirthdayMinAge").replace("{minAge}", String(minAge)),
    }),
    [t],
  );

  useEffect(() => {
    const saved = loadPathwayQuizProgress(initialForm);
    if (saved) {
      setTab(saved.tab);
      setForm(saved.form);
    }
    persistReady.current = true;
    setBootstrapped(true);
  }, []);

  useEffect(() => {
    if (!persistReady.current) return;
    savePathwayQuizProgress(tab, form);
  }, [tab, form]);

  useEffect(() => {
    if (tab !== 9) return;

    const words = PATHWAY_LOADING_MSG_KEYS.map((key) => t(key));
    setLoadingMsg(words[0]);
    let count = 0;

    const iv = window.setInterval(() => {
      count += 1;
      if (count <= 2) {
        setLoadingMsg(words[count]);
      } else {
        window.clearInterval(iv);
        void (async () => {
          const matchInput = {
            describe: formRef.current.describe,
            interest: formRef.current.interest,
            gender: formRef.current.gender,
            malaysian: formRef.current.malaysian,
            stream: formRef.current.stream,
            studyLevel: formRef.current.studyLevel,
            budget: formRef.current.budget,
            income: formRef.current.income,
            bumi: formRef.current.bumi,
            studyPlan: formRef.current.studyPlan,
            states: formRef.current.states,
            country: formRef.current.country,
            uniType: formRef.current.uniType,
          };
          savePathwayMatchInput(matchInput);
          try {
            const res = await fetch("/api/pathway360/match", {
              method: "POST",
              headers: { "Content-Type": "application/json" },
              body: JSON.stringify(matchInput),
            });
            const data = (await res.json()) as { status?: boolean; cards?: unknown[] };
            if (res.ok && data.status && Array.isArray(data.cards) && data.cards.length > 0) {
              savePathwayMatchResults(data.cards as PathwayMatchResultCard[]);
            }
          } catch {
            // Characters page will retry match using saved input.
          }
          clearPathwayQuizProgress();
          router.replace("/characters");
        })();
      }
    }, 2000);

    return () => window.clearInterval(iv);
  }, [tab, router, t]);

  const showSidebar = tab < 9;
  const isLoading = tab === 9;

  const toggleMulti = (key: "describe" | "interest", id: number, max = 10) => {
    setForm((f) => {
      const list = f[key];
      if (list.includes(id)) return { ...f, [key]: list.filter((x) => x !== id) };
      if (list.length >= max) return f;
      return { ...f, [key]: [...list, id] };
    });
  };

  const validate = (n: number, data: FormState = form): string | null => {
    if (n === 0) {
      if (!data.gender) return t("errPathwayGender");
      const phoneErr = malaysianPhoneError(data.phone, { messages: phoneMessages });
      if (phoneErr) return phoneErr;
      const dobErr = birthdayError(
        { day: data.dobDay, month: data.dobMonth, year: data.dobYear },
        { minAge: 10, maxAge: 100, messages: birthdayMessages },
      );
      if (dobErr) return dobErr;
      if (!data.malaysian) return t("errPathwayNationality");
    }
    if (n === 1) {
      if (data.describe.length < 5) return t("errPathwayDescribeMin");
      if (data.interest.length < 5) return t("errPathwayInterestMin");
    }
    if (n === 2) {
      if (!data.academic) return t("errPathwayAcademic");
      if (!data.schoolName) return t("errPathwaySchool");
      if (!data.stream && data.stream !== 0) return t("errPathwayStream");
      if (data.academic !== 3 && !data.levelId) return t("errPathwayQualification");
    }
    if (n === 3) {
      if (!data.resultType) return t("errPathwayResultType");
      if (!usesCgpaResults(data.levelId)) {
        if (data.subjects.length === 0) return t("errPathwaySubjectMin");
        if (data.subjects.some((s) => !s.gradeId)) {
          return t("errPathwaySubjectGrade");
        }
      }
    }
    if (n === 4) {
      if (!data.represent || !data.talent || !data.club) {
        return t("errPathwaySelectAll");
      }
      if (data.represent === 1) {
        if (!data.representLevel) return t("errPathwayRepresentLevel");
        if (!data.sportName.trim()) return t("errPathwaySport");
      }
      if (data.talent === 1) {
        if (!data.talentLevel) {
          return t("errPathwayTalentLevel");
        }
        if (!data.talentName.trim()) {
          return t("errPathwayTalentName");
        }
      }
      if (data.club === 1) {
        if (!data.role) return t("errPathwayRole");
        if (!data.clubLevel) return t("errPathwayClubLevel");
        if (!data.societyName.trim()) {
          return t("errPathwaySociety");
        }
      }
    }
    if (n === 5) {
      if (!data.income || !data.budget || !data.bumi) {
        return t("errPathwayIncome");
      }
    }
    if (n === 6) {
      if (!data.studyLevel) return t("errPathwayStudyLevel");
      if (!data.joiningDate) return t("errPathwayJoiningDate");
    }
    if (n === 7) {
      if (!data.studyPlan) return t("errPathwayStudyPlan");
      if (data.studyPlan === 1 || data.studyPlan === 2) {
        if (data.states.length === 0) return t("errPathwayStates");
      }
      if (data.studyPlan === 2 || data.studyPlan === 3) {
        if (!data.country) return t("errPathwayCountry");
      }
    }
    if (n === 8) {
      if (!data.uniType) return t("errPathwayUniType");
      if (!data.regUni) return t("errPathwayRegUni");
      if (data.regUni === 1 && !data.uniRegName.trim()) {
        return t("errPathwayUniName");
      }
    }
    return null;
  };

  const nextPrev = (dir: number) => {
    if (dir > 0) {
      let workingForm = form;
      if (tab === 0 && form.phone.trim()) {
        const formatted = formatMalaysianPhoneInternational(form.phone);
        if (formatted) workingForm = { ...form, phone: formatted };
      }
      const err = validate(tab, workingForm);
      if (err) {
        setError(err);
        window.setTimeout(() => setError(""), 3000);
        return;
      }
      if (workingForm.phone !== form.phone) setForm(workingForm);
    }
    setError("");
    let next = tab + dir;
    if (next === 3 && (form.academic === 3 || form.levelId === 11 || form.levelId === 12)) {
      next = dir > 0 ? 4 : 2;
    }
    if (next < 0) next = 0;
    if (next > 9) next = 9;
    setTab(next);
  };

  const nextLabel = useMemo(() => (tab === 8 ? t("getResults") : t("next")), [tab, t]);

  const levelLabel = useMemo(() => resolveLevelLabel(form, t), [form, t]);

  if (!bootstrapped) {
    return <div className="pathway-quiz pathway-quiz--bootstrapping" aria-busy="true" />;
  }

  return (
    <div className="pathway-quiz">
      {error && (
        <div className="pathway-toast" role="alert">
          <span className="err-msg text-danger">{error}</span>
        </div>
      )}

      <div className={`pathway-quiz-body${isLoading ? " is-loading" : ""}`}>
        {showSidebar && (
          <nav className="pathway-steps" aria-label={t("pathwayStepsNav")}>
            {PATHWAY_STEP_LABEL_KEYS.map((key, i) => (
              <p
                key={key}
                className={`pathway-step${i < tab ? " is-done" : ""}${i === tab ? " is-current" : ""}`}
                aria-label={`${t("pathwayStepWord")} ${i + 1}, ${t(key)}`}
                aria-current={i === tab ? "step" : undefined}
              >
                <span className="pathway-step-dot" aria-hidden />
                <span className="pathway-step-label">{t(key)}</span>
              </p>
            ))}
          </nav>
        )}

        {!isLoading && (
          <div className="pathway-form">
            <p className="pathway-mobile-step">{t(PATHWAY_STEP_LABEL_KEYS[tab])}</p>

            {tab === 0 && (
              <div className="pathway-fields">
                <div className="pathway-field">
                  <label className="pathway-field-label">{t("whatsYourGender")}</label>
                  <div className="pathway-choices">
                    <Choice className="pathway-choice--pill" active={form.gender === 1} onClick={() => setForm({ ...form, gender: 1 })}>
                      {t("male")}
                    </Choice>
                    <Choice className="pathway-choice--pill" active={form.gender === 2} onClick={() => setForm({ ...form, gender: 2 })}>
                      {t("female")}
                    </Choice>
                  </div>
                </div>

                <div className="pathway-field">
                  <label className="pathway-field-label" htmlFor="pathway_phone">
                    {t("whatsYourPhoneNumber")}
                  </label>
                  <MalaysianPhoneInput
                    id="pathway_phone"
                    name="phone_number"
                    required
                    className="pathway-input custom-input-placeholder"
                    value={form.phone}
                    onChange={(phone) => setForm({ ...form, phone })}
                  />
                </div>

                <div className="pathway-field">
                  <label className="pathway-field-label">{t("whensYourBirthday")}</label>
                  <div className="pathway-dob">
                    <input
                      type="text"
                      inputMode="numeric"
                      autoComplete="bday-day"
                      className="pathway-input custom-input-placeholder"
                      placeholder={t("dd")}
                      aria-label={t("day")}
                      maxLength={2}
                      value={form.dobDay}
                      onChange={(e) =>
                        setForm({ ...form, dobDay: sanitizeBirthdayDigits(e.target.value, 2) })
                      }
                    />
                    <input
                      type="text"
                      inputMode="numeric"
                      autoComplete="bday-month"
                      className="pathway-input custom-input-placeholder"
                      placeholder={t("mm")}
                      aria-label={t("month")}
                      maxLength={2}
                      value={form.dobMonth}
                      onChange={(e) =>
                        setForm({ ...form, dobMonth: sanitizeBirthdayDigits(e.target.value, 2) })
                      }
                    />
                    <input
                      type="text"
                      inputMode="numeric"
                      autoComplete="bday-year"
                      className="pathway-input custom-input-placeholder"
                      placeholder={t("yyyy")}
                      aria-label={t("vcyear")}
                      maxLength={4}
                      value={form.dobYear}
                      onChange={(e) =>
                        setForm({ ...form, dobYear: sanitizeBirthdayDigits(e.target.value, 4) })
                      }
                    />
                  </div>
                </div>

                <div className="pathway-field">
                  <label className="pathway-field-label">{t("areYouMalaysian")}</label>
                  <div className="pathway-choices">
                    <Choice className="pathway-choice--pill" active={form.malaysian === 1} onClick={() => setForm({ ...form, malaysian: 1 })}>
                      {t("yes")}
                    </Choice>
                    <Choice className="pathway-choice--pill" active={form.malaysian === 2} onClick={() => setForm({ ...form, malaysian: 2 })}>
                      {t("no")}
                    </Choice>
                  </div>
                </div>
              </div>
            )}

            {tab === 1 && (
              <div className="pathway-fields">
                <div className="pathway-field">
                  <div className="pathway-field-label-row">
                    <label className="pathway-field-label">{t("whatsYourDescribe")}</label>
                    <span className="pathway-field-meta">
                      {t("selectAtLeastFive")} ({form.describe.length}/10)
                    </span>
                  </div>
                  <div className="pathway-choices pathway-choices--stagger">
                    {DESCRIBE_OPTIONS.map((d) => (
                      <Choice
                        key={d.id}
                        className="pathway-choice--pill pathway-choice--compact"
                        active={form.describe.includes(d.id)}
                        onClick={() => toggleMulti("describe", d.id)}
                      >
                        {t(personalityDictKey(d.id), d.name)}
                      </Choice>
                    ))}
                  </div>
                </div>
                <div className="pathway-field">
                  <div className="pathway-field-label-row">
                    <label className="pathway-field-label">{t("whatsYourInterest")}</label>
                    <span className="pathway-field-meta">
                      {t("selectAtLeastFive")} ({form.interest.length}/10)
                    </span>
                  </div>
                  <div className="pathway-choices pathway-choices--stagger">
                    {INTEREST_OPTIONS.map((d) => (
                      <Choice
                        key={d.id}
                        className="pathway-choice--pill pathway-choice--compact"
                        active={form.interest.includes(d.id)}
                        onClick={() => toggleMulti("interest", d.id)}
                      >
                        {t(personalityDictKey(d.id), d.name)}
                      </Choice>
                    ))}
                  </div>
                </div>
              </div>
            )}

            {tab === 2 && (
              <div className="pathway-fields">
                <div className="pathway-field">
                  <label className="pathway-field-label">{t("whatsYourEducationalLevel")}</label>
                  <div className="pathway-choices">
                    {PATHWAY_ACADEMIC_LEVEL_OPTIONS.map((opt) => (
                      <Choice
                        key={opt.id}
                        className="pathway-choice--pill"
                        active={form.academic === opt.id}
                        onClick={() =>
                          setForm({
                            ...form,
                            academic: opt.id,
                            levelId: undefined,
                            ...(opt.id === 3 ? { levelName: "this qualification" } : {}),
                          })
                        }
                      >
                        {t(opt.key)}
                      </Choice>
                    ))}
                  </div>
                </div>

                {form.academic !== 3 && (
                  <div className="pathway-field">
                    <label className="pathway-field-label">{t("whatsYourQualification")}</label>
                    <div className="pathway-choices">
                      {form.academic === 1 &&
                        PATHWAY_QUAL_SPM.map((q) => (
                          <Choice
                            key={q.id}
                            className="pathway-choice--pill"
                            active={form.levelId === q.id}
                            onClick={() =>
                              setForm({
                                ...form,
                                levelId: q.id,
                                levelName: q.key === "others" ? "this qualification" : t(q.key),
                                subjects: [],
                              })
                            }
                          >
                            {t(q.key)}
                          </Choice>
                        ))}
                      {form.academic === 2 &&
                        PATHWAY_QUAL_POST_SPM.map((q) => (
                          <Choice
                            key={q.id}
                            className="pathway-choice--pill"
                            active={form.levelId === q.id}
                            onClick={() =>
                              setForm({
                                ...form,
                                levelId: q.id,
                                levelName: q.key === "others" ? "this qualification" : t(q.key),
                                subjects: [],
                              })
                            }
                          >
                            {t(q.key)}
                          </Choice>
                        ))}
                    </div>
                  </div>
                )}

                <div className="pathway-field">
                  <label className="pathway-field-label" htmlFor="pathway_school">
                    {t("whereYouStudyFor")} {levelLabel}?
                  </label>
                  <SchoolSearchInput
                    id="pathway_school"
                    name="school_name"
                    className="pathway-input custom-input-placeholder"
                    placeholder={t("enterSchoolName")}
                    required
                    value={form.schoolName}
                    onChange={(schoolName) => setForm({ ...form, schoolName })}
                  />
                </div>

                <div className="pathway-field">
                  <label className="pathway-field-label">{t("whatStream")}</label>
                  <div className="pathway-choices">
                    {PATHWAY_STREAM_OPTIONS.map((s) => (
                      <Choice
                        key={s.id}
                        className="pathway-choice--pill"
                        active={form.stream === s.id}
                        onClick={() => setForm({ ...form, stream: s.id })}
                      >
                        {t(s.key)}
                      </Choice>
                    ))}
                  </div>
                </div>
              </div>
            )}

            {tab === 3 && (
              <div className="pathway-fields">
                <div className="pathway-field">
                  <label className="pathway-field-label">
                    {t("whatIsYour")} {levelLabel} {t("result")}?
                  </label>
                  <div className="pathway-choices">
                    <Choice
                      className="pathway-choice--pill"
                      active={form.resultType === 1}
                      onClick={() => setForm({ ...form, resultType: 1 })}
                    >
                      {t("finalResult")}
                    </Choice>
                    <Choice
                      className="pathway-choice--pill"
                      active={form.resultType === 2}
                      onClick={() => setForm({ ...form, resultType: 2 })}
                    >
                      {t("trialResult")}
                    </Choice>
                    <Choice
                      className="pathway-choice--pill"
                      active={form.resultType === 3}
                      onClick={() => setForm({ ...form, resultType: 3 })}
                    >
                      {t("partialResultLong")}
                    </Choice>
                  </div>
                </div>
                <div className="pathway-field">
                  <label className="pathway-field-label">
                    {levelLabel} {t("subjectResult")}
                  </label>
                  {!usesCgpaResults(form.levelId) && (
                    <PathwaySubjectPicker
                      levelId={form.levelId}
                      levelLabel={levelLabel}
                      value={form.subjects}
                      onChange={(subjects) => setForm({ ...form, subjects })}
                    />
                  )}
                </div>
              </div>
            )}

            {tab === 4 && (
              <div className="pathway-fields">
                <div className="pathway-field">
                  <label className="pathway-field-label">{t("sportsRepresent")}</label>
                  <div className="pathway-choices">
                    <Choice className="pathway-choice--pill" active={form.represent === 1} onClick={() => setForm({ ...form, represent: 1 })}>
                      {t("yes")}
                    </Choice>
                    <Choice className="pathway-choice--pill" active={form.represent === 2} onClick={() => setForm({ ...form, represent: 2 })}>
                      {t("no")}
                    </Choice>
                  </div>
                  {form.represent === 1 && (
                    <div className="pathway-suboptions">
                      <div>
                        <span className="option-title">{t("iRepresentMy")}</span>
                        <div className="pathway-choices">
                          {PATHWAY_REPRESENT_LEVELS.map((x) => (
                            <Choice
                              key={x.id}
                              className="pathway-choice--pill"
                              active={form.representLevel === x.id}
                              onClick={() => setForm({ ...form, representLevel: x.id })}
                            >
                              {t(x.key)}
                            </Choice>
                          ))}
                        </div>
                      </div>
                      <div>
                        <span className="option-title">{t("inThisSport")}</span>
                        <input
                          type="text"
                          className="pathway-input custom-input-placeholder"
                          placeholder={t("sportsDesc")}
                          value={form.sportName}
                          onChange={(e) => setForm({ ...form, sportName: e.target.value })}
                        />
                      </div>
                    </div>
                  )}
                </div>

                <div className="pathway-field">
                  <label className="pathway-field-label">{t("talentsRepresent")}</label>
                  <div className="pathway-choices">
                    <Choice className="pathway-choice--pill" active={form.talent === 1} onClick={() => setForm({ ...form, talent: 1 })}>
                      {t("yes")}
                    </Choice>
                    <Choice className="pathway-choice--pill" active={form.talent === 2} onClick={() => setForm({ ...form, talent: 2 })}>
                      {t("no")}
                    </Choice>
                  </div>
                  {form.talent === 1 && (
                    <div className="pathway-suboptions">
                      <div>
                        <span className="option-title">{t("completeVolunteer")}</span>
                        <input
                          type="text"
                          className="pathway-input custom-input-placeholder"
                          placeholder={t("talentsDesc")}
                          value={form.talentName}
                          onChange={(e) => setForm({ ...form, talentName: e.target.value })}
                        />
                      </div>
                      <div>
                        <span className="option-title">{t("competitionCommunity")}</span>
                        <div className="pathway-choices">
                          {PATHWAY_REPRESENT_LEVELS.map((x) => (
                            <Choice
                              key={x.id}
                              className="pathway-choice--pill"
                              active={form.talentLevel === x.id}
                              onClick={() => setForm({ ...form, talentLevel: x.id })}
                            >
                              {t(x.key)}
                            </Choice>
                          ))}
                        </div>
                      </div>
                    </div>
                  )}
                </div>

                <div className="pathway-field">
                  <label className="pathway-field-label">{t("holdLeadership")}</label>
                  <div className="pathway-choices">
                    <Choice className="pathway-choice--pill" active={form.club === 1} onClick={() => setForm({ ...form, club: 1 })}>
                      {t("yes")}
                    </Choice>
                    <Choice className="pathway-choice--pill" active={form.club === 2} onClick={() => setForm({ ...form, club: 2 })}>
                      {t("no")}
                    </Choice>
                  </div>
                  {form.club === 1 && (
                    <div className="pathway-suboptions">
                      <div>
                        <span className="option-title">{t("iAmThe")}</span>
                        <div className="pathway-choices">
                          {PATHWAY_ROLE_KEYS.map((key, i) => (
                            <Choice
                              key={key}
                              className="pathway-choice--pill"
                              active={form.role === i + 1}
                              onClick={() => setForm({ ...form, role: i + 1 })}
                            >
                              {t(key)}
                            </Choice>
                          ))}
                        </div>
                      </div>
                      <div>
                        <span className="option-title">{t("ofSocietyEvent")}</span>
                        <input
                          type="text"
                          className="pathway-input custom-input-placeholder"
                          placeholder={t("eventDesc")}
                          value={form.societyName}
                          onChange={(e) => setForm({ ...form, societyName: e.target.value })}
                        />
                      </div>
                      <div>
                        <span className="option-title">{t("atThisLevel")}</span>
                        <div className="pathway-choices">
                          {PATHWAY_REPRESENT_LEVELS.map((x) => (
                            <Choice
                              key={x.id}
                              className="pathway-choice--pill"
                              active={form.clubLevel === x.id}
                              onClick={() => setForm({ ...form, clubLevel: x.id })}
                            >
                              {t(x.key)}
                            </Choice>
                          ))}
                        </div>
                      </div>
                    </div>
                  )}
                </div>
              </div>
            )}

            {tab === 5 && (
              <div className="pathway-fields">
                <div className="pathway-field">
                  <label className="pathway-field-label">{t("parentIncome")}</label>
                  <div className="pathway-choices">
                    {PATHWAY_INCOME_OPTIONS.map((x) => (
                      <Choice
                        key={x.id}
                        className="pathway-choice--pill"
                        active={form.income === x.id}
                        onClick={() => setForm({ ...form, income: x.id })}
                      >
                        {t(x.key)}
                      </Choice>
                    ))}
                  </div>
                </div>
                <div className="pathway-field">
                  <label className="pathway-field-label">{t("courseBudget")}</label>
                  <div className="pathway-choices">
                    {PATHWAY_BUDGET_OPTIONS.map((x) => (
                      <Choice
                        key={x.id}
                        className="pathway-choice--pill"
                        active={form.budget === x.id}
                        onClick={() => setForm({ ...form, budget: x.id })}
                      >
                        {t(x.key)}
                      </Choice>
                    ))}
                  </div>
                </div>
                <div className="pathway-field">
                  <label className="pathway-field-label">{t("areYouBumi")}</label>
                  <div className="pathway-choices">
                    <Choice className="pathway-choice--pill" active={form.bumi === 1} onClick={() => setForm({ ...form, bumi: 1 })}>
                      {t("yes")}
                    </Choice>
                    <Choice className="pathway-choice--pill" active={form.bumi === 2} onClick={() => setForm({ ...form, bumi: 2 })}>
                      {t("no")}
                    </Choice>
                  </div>
                </div>
              </div>
            )}

            {tab === 6 && (
              <div className="pathway-fields">
                <div className="pathway-field">
                  <label className="pathway-field-label">{t("preferredPathway")}</label>
                  <div className="pathway-choices">
                    {PREFERRED_PATHWAY_OPTIONS.map((x) => (
                      <Choice
                        key={x.id}
                        className="pathway-choice--pill"
                        active={form.studyLevel === x.id}
                        onClick={() => setForm({ ...form, studyLevel: x.id })}
                      >
                        {t(x.labelKey)}
                      </Choice>
                    ))}
                  </div>
                  <PathwayStudyOverview levelId={form.studyLevel} />
                </div>
                <div className="pathway-field">
                  <label className="pathway-field-label">{t("planStart")}</label>
                  <div className="pathway-choices">
                    {PATHWAY_JOIN_OPTIONS.map((x) => (
                      <Choice
                        key={x.id}
                        className="pathway-choice--pill"
                        active={form.joiningDate === x.id}
                        onClick={() => setForm({ ...form, joiningDate: x.id })}
                      >
                        {t(x.key)}
                      </Choice>
                    ))}
                  </div>
                </div>
              </div>
            )}

            {tab === 7 && (
              <div className="pathway-fields">
                <div className="pathway-field">
                  <label className="pathway-field-label">{t("planStudyAt")}</label>
                  <div className="pathway-choices">
                    {PATHWAY_STUDY_PLAN_OPTIONS.map((x) => (
                      <Choice
                        key={x.id}
                        className="pathway-choice--pill"
                        active={form.studyPlan === x.id}
                        onClick={() =>
                          setForm((f) => ({
                            ...f,
                            studyPlan: x.id,
                            ...(x.id === 3 ? { states: [] } : {}),
                            ...(x.id === 1 ? { country: undefined } : {}),
                          }))
                        }
                      >
                        {t(x.key)}
                      </Choice>
                    ))}
                  </div>
                </div>
                {form.studyPlan !== 3 && (
                  <div className="pathway-field">
                    <label className="pathway-field-label">{t("preferredLocation")}</label>
                    <div className="pathway-choices">
                      {Object.entries(PATHWAY_STATE_KEYS).map(([id, key]) => {
                        const stateId = Number(id);
                        return (
                          <Choice
                            key={stateId}
                            className="pathway-choice--pill"
                            active={form.states.includes(stateId)}
                            onClick={() =>
                              setForm((f) => ({
                                ...f,
                                states: f.states.includes(stateId)
                                  ? f.states.filter((x) => x !== stateId)
                                  : [...f.states, stateId],
                              }))
                            }
                          >
                            {t(key)}
                          </Choice>
                        );
                      })}
                    </div>
                  </div>
                )}
                {(form.studyPlan === 2 || form.studyPlan === 3) && (
                  <div className="pathway-field">
                    <label className="pathway-field-label">{t("studyAbroad")}</label>
                    <div className="pathway-choices">
                      {PATHWAY_ABROAD_COUNTRY_OPTIONS.map((c) => (
                        <Choice
                          key={c.id}
                          className="pathway-choice--pill"
                          active={form.country === c.id}
                          onClick={() => setForm({ ...form, country: c.id })}
                        >
                          {t(c.key)}
                        </Choice>
                      ))}
                    </div>
                  </div>
                )}
              </div>
            )}

            {tab === 8 && (
              <div className="pathway-fields">
                <div className="pathway-field">
                  <label className="pathway-field-label">{t("typeOfUniversity")}</label>
                  <div className="pathway-choices">
                    <Choice
                      className="pathway-choice--pill"
                      active={form.uniType === 1}
                      onClick={() => setForm({ ...form, uniType: 1 })}
                    >
                      {t("privateUniversityExamples")}
                    </Choice>
                    <Choice
                      className="pathway-choice--pill"
                      active={form.uniType === 2}
                      onClick={() => setForm({ ...form, uniType: 2 })}
                    >
                      {t("publicUniversityExamples")}
                    </Choice>
                    <Choice
                      className="pathway-choice--pill"
                      active={form.uniType === 3}
                      onClick={() => setForm({ ...form, uniType: 3 })}
                    >
                      {t("bothUniversity")}
                    </Choice>
                  </div>
                </div>
                <div className="pathway-field">
                  <label className="pathway-field-label">{t("appliedUniversity")}</label>
                  <div className="pathway-choices">
                    <Choice className="pathway-choice--pill" active={form.regUni === 1} onClick={() => setForm({ ...form, regUni: 1 })}>
                      {t("yes")}
                    </Choice>
                    <Choice className="pathway-choice--pill" active={form.regUni === 2} onClick={() => setForm({ ...form, regUni: 2 })}>
                      {t("no")}
                    </Choice>
                  </div>
                </div>
                {form.regUni === 1 && (
                  <div className="pathway-field">
                    <label className="pathway-field-label" htmlFor="pathway_uni">
                      {t("whichUniversity")}
                    </label>
                    <input
                      id="pathway_uni"
                      type="text"
                      name="uni_reg_name"
                      className="pathway-input custom-input-placeholder"
                      placeholder={t("registeredUniDesc")}
                      value={form.uniRegName}
                      onChange={(e) => setForm({ ...form, uniRegName: e.target.value })}
                    />
                  </div>
                )}
              </div>
            )}
          </div>
        )}

        {isLoading && (
          <div className="pathway-loading">
            <span className="pathway-loading-text">{loadingMsg}</span>
            {/* eslint-disable-next-line @next/next/no-img-element */}
            <img src="https://edu4u.world/images/assets/loding-gif.gif" style={{ width: 60 }} alt="" />
          </div>
        )}
      </div>

      {!isLoading && (
        <div className="pathway-footer">
          <div className="pathway-footer-inner">
            {tab === 0 ? (
              <Link id="btn_cancel" className="btn-cancel" href="/">
                {t("cancel")}
              </Link>
            ) : (
              <button id="btn_back" type="button" className="btn-cancel" onClick={() => nextPrev(-1)}>
                {t("back")}
              </button>
            )}
            <button type="button" className="home-btn-get-started-2" id="btn_next" onClick={() => nextPrev(1)}>
              {nextLabel}
            </button>
          </div>
        </div>
      )}
      <form id="personality_form" />
    </div>
  );
}
