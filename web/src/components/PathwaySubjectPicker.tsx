"use client";

import { useCallback, useEffect, useMemo, useRef, useState } from "react";
import { CustomDropdown } from "@/components/ui/CustomDropdown";
import { useT } from "@/components/LocaleProvider";

export type PathwaySubjectSelection = {
  id: number;
  name: string;
  gradeId: number | null;
};

type SubjectPickerGrade = { sg_id: number; sg_grade: string };
type SubjectPickerItem = { id: number; name: string };
type SubjectPickerGroup = { name: string; data: SubjectPickerItem[] };

type CatalogResponse = {
  status: boolean;
  list?: SubjectPickerGroup[];
  preSelected?: SubjectPickerItem[];
  grade?: SubjectPickerGrade[];
};

type Props = {
  levelId?: number;
  levelLabel: string;
  value: PathwaySubjectSelection[];
  onChange: (next: PathwaySubjectSelection[]) => void;
};

function mergeSelections(
  current: PathwaySubjectSelection[],
  picked: SubjectPickerItem[],
): PathwaySubjectSelection[] {
  const byId = new Map(current.map((s) => [s.id, s]));
  for (const item of picked) {
    if (!byId.has(item.id)) {
      byId.set(item.id, { id: item.id, name: item.name, gradeId: null });
    }
  }
  const pickedIds = new Set(picked.map((p) => p.id));
  return [...byId.values()].filter((s) => pickedIds.has(s.id));
}

export function PathwaySubjectPicker({ levelId, levelLabel, value, onChange }: Props) {
  const t = useT();
  const [open, setOpen] = useState(false);
  const [loading, setLoading] = useState(false);
  const [groups, setGroups] = useState<SubjectPickerGroup[]>([]);
  const [preSelected, setPreSelected] = useState<SubjectPickerItem[]>([]);
  const [grades, setGrades] = useState<SubjectPickerGrade[]>([]);
  const [draftIds, setDraftIds] = useState<Set<number>>(new Set());
  const preMergedForLevel = useRef<number | null>(null);
  const valueRef = useRef(value);
  const onChangeRef = useRef(onChange);
  valueRef.current = value;
  onChangeRef.current = onChange;

  const applyCatalog = useCallback((data: CatalogResponse, mergePreselected: boolean) => {
    setGroups(data.list || []);
    setPreSelected(data.preSelected || []);
    setGrades(data.grade || []);

    if (mergePreselected && levelId && data.preSelected?.length) {
      if (preMergedForLevel.current !== levelId) {
        preMergedForLevel.current = levelId;
        onChangeRef.current(mergeSelections(valueRef.current, data.preSelected));
      }
    }
  }, [levelId]);

  const fetchCatalog = useCallback(
    async (mergePreselected: boolean) => {
      if (!levelId) return;
      setLoading(true);
      try {
        const res = await fetch(`/api/subjects/by-level?level=${levelId}`);
        const data = (await res.json()) as CatalogResponse;
        if (!data.status) {
          setGroups([]);
          setPreSelected([]);
          setGrades([]);
          return;
        }
        applyCatalog(data, mergePreselected);
      } finally {
        setLoading(false);
      }
    },
    [applyCatalog, levelId],
  );

  useEffect(() => {
    if (!levelId) {
      preMergedForLevel.current = null;
      setGroups([]);
      setPreSelected([]);
      setGrades([]);
      return;
    }
    void fetchCatalog(true);
  }, [levelId, fetchCatalog]);

  function openModal() {
    if (!levelId) return;
    setDraftIds(new Set(value.map((v) => v.id)));
    setOpen(true);
    if (groups.length === 0 && preSelected.length === 0) {
      void fetchCatalog(false);
    }
  }

  function toggleDraft(id: number) {
    setDraftIds((prev) => {
      const next = new Set(prev);
      if (next.has(id)) next.delete(id);
      else next.add(id);
      return next;
    });
  }

  function confirmModal() {
    const nameById = new Map<number, string>();
    for (const g of groups) {
      for (const item of g.data) nameById.set(item.id, item.name);
    }
    for (const item of preSelected) nameById.set(item.id, item.name);

    const picked: SubjectPickerItem[] = [];
    for (const id of draftIds) {
      const name = nameById.get(id);
      if (name) picked.push({ id, name });
    }
    onChange(mergeSelections(value, picked));
    setOpen(false);
  }

  function setGrade(subjectId: number, gradeId: number) {
    onChange(value.map((s) => (s.id === subjectId ? { ...s, gradeId } : s)));
  }

  const gradeOptions = useMemo(
    () => grades.map((g) => ({ value: String(g.sg_id), label: g.sg_grade })),
    [grades],
  );

  return (
    <div className="pathway-subject-picker">
      {value.length > 0 && (
        <div className="pathway-subject-results">
          {value.map((subject) => (
            <div key={subject.id} className="pathway-subject-result-row">
              <span className="pathway-subject-result-name">{subject.name}</span>
              <CustomDropdown
                className="pathway-subject-grade-dropdown"
                value={subject.gradeId != null ? String(subject.gradeId) : ""}
                options={gradeOptions}
                placeholder={t("grade")}
                onChange={(next) => setGrade(subject.id, Number(next))}
                disabled={gradeOptions.length === 0}
              />
            </div>
          ))}
        </div>
      )}

      <div className="pathway-choices pathway-choices--hug">
        <button
          type="button"
          className="pathway-choice pathway-choice--pill"
          id="add_subject_btn"
          disabled={!levelId || loading}
          onClick={openModal}
        >
          {t("addSubjects")}
        </button>
      </div>

      {!levelId && (
        <p className="pathway-subject-hint">{t("pathwaySubjectSelectQual")}</p>
      )}

      {open && (
        <div className="pathway-subject-modal-overlay" role="presentation" onClick={() => setOpen(false)}>
          <div
            className="pathway-subject-modal edu-modal-content"
            role="dialog"
            aria-modal
            aria-labelledby="pathway-subject-modal-title"
            onClick={(e) => e.stopPropagation()}
          >
            <h3 id="pathway-subject-modal-title" className="pathway-subject-modal-title">
              {t("addSubjects")}
            </h3>
            <p className="pathway-subject-modal-sub">{levelLabel}</p>

            {loading && <p className="pathway-subject-hint">{t("loadingSubjects")}</p>}

            {!loading && preSelected.length > 0 && (
              <div className="pathway-subject-preselected">
                <span className="pathway-subject-preselected-label">{t("preselectedSubjects")}</span>
                <div className="pathway-choices pathway-choices--stagger">
                  {preSelected.map((item) => (
                    <button
                      key={item.id}
                      type="button"
                      className={`pathway-choice pathway-choice--pill pathway-choice--compact${draftIds.has(item.id) ? " is-active" : ""}`}
                      onClick={() => toggleDraft(item.id)}
                    >
                      {item.name}
                    </button>
                  ))}
                </div>
              </div>
            )}

            {!loading &&
              groups.map((group) => (
                <div key={group.name} className="pathway-subject-group">
                  <span className="subject-heading">{group.name}</span>
                  <div className="pathway-choices pathway-choices--stagger">
                    {group.data.map((item) => (
                      <button
                        key={item.id}
                        type="button"
                        className={`pathway-choice pathway-choice--pill pathway-choice--compact${draftIds.has(item.id) ? " is-active" : ""}`}
                        onClick={() => toggleDraft(item.id)}
                      >
                        {item.name}
                      </button>
                    ))}
                  </div>
                </div>
              ))}

            {!loading && groups.length === 0 && preSelected.length === 0 && (
              <p className="pathway-subject-hint">{t("pathwaySubjectNoResults")}</p>
            )}

            <div className="pathway-subject-modal-actions">
              <button type="button" className="btn-cancel" onClick={() => setOpen(false)}>
                {t("cancel")}
              </button>
              <button type="button" className="home-btn-get-started-2 modal-confirm-btn" onClick={confirmModal}>
                {t("confirm")}
              </button>
            </div>
          </div>
        </div>
      )}
    </div>
  );
}
