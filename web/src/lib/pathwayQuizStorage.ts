import type { PathwaySubjectSelection } from "@/components/PathwaySubjectPicker";

export type PathwayQuizFormState = {
  gender?: number;
  phone: string;
  dobDay: string;
  dobMonth: string;
  dobYear: string;
  malaysian?: number;
  describe: number[];
  interest: number[];
  academic?: number;
  levelId?: number;
  levelName: string;
  schoolName: string;
  stream?: number;
  resultType?: number;
  subjects: PathwaySubjectSelection[];
  represent?: number;
  representLevel?: number;
  sportName: string;
  talent?: number;
  talentLevel?: number;
  talentName: string;
  club?: number;
  role?: number;
  clubLevel?: number;
  societyName: string;
  income?: number;
  budget?: number;
  bumi?: number;
  studyLevel?: number;
  joiningDate?: number;
  studyPlan?: number;
  states: number[];
  country?: number;
  uniType?: number;
  regUni?: number;
  uniRegName: string;
};

type PersistedPayload = {
  v: 1;
  tab: number;
  form: PathwayQuizFormState;
  savedAt: number;
};

const STORAGE_KEY = "edu4u.pathway360.progress";

function isNumberArray(value: unknown): value is number[] {
  return Array.isArray(value) && value.every((x) => typeof x === "number");
}

function parseSubjects(value: unknown): PathwaySubjectSelection[] {
  if (!Array.isArray(value)) return [];
  const out: PathwaySubjectSelection[] = [];
  for (const item of value) {
    if (
      item &&
      typeof item === "object" &&
      typeof (item as PathwaySubjectSelection).id === "number" &&
      typeof (item as PathwaySubjectSelection).name === "string"
    ) {
      const gradeId = (item as PathwaySubjectSelection).gradeId;
      out.push({
        id: (item as PathwaySubjectSelection).id,
        name: (item as PathwaySubjectSelection).name,
        gradeId: typeof gradeId === "number" ? gradeId : null,
      });
    }
  }
  return out;
}

function parseForm(raw: unknown, fallback: PathwayQuizFormState): PathwayQuizFormState | null {
  if (!raw || typeof raw !== "object") return null;
  const f = raw as Record<string, unknown>;

  return {
    ...fallback,
    gender: typeof f.gender === "number" ? f.gender : undefined,
    phone: typeof f.phone === "string" ? f.phone : fallback.phone,
    dobDay: typeof f.dobDay === "string" ? f.dobDay : fallback.dobDay,
    dobMonth: typeof f.dobMonth === "string" ? f.dobMonth : fallback.dobMonth,
    dobYear: typeof f.dobYear === "string" ? f.dobYear : fallback.dobYear,
    malaysian: typeof f.malaysian === "number" ? f.malaysian : undefined,
    describe: isNumberArray(f.describe) ? f.describe : fallback.describe,
    interest: isNumberArray(f.interest) ? f.interest : fallback.interest,
    academic: typeof f.academic === "number" ? f.academic : undefined,
    levelId: typeof f.levelId === "number" ? f.levelId : undefined,
    levelName: typeof f.levelName === "string" ? f.levelName : fallback.levelName,
    schoolName: typeof f.schoolName === "string" ? f.schoolName : fallback.schoolName,
    stream: typeof f.stream === "number" ? f.stream : undefined,
    resultType: typeof f.resultType === "number" ? f.resultType : undefined,
    subjects: parseSubjects(f.subjects),
    represent: typeof f.represent === "number" ? f.represent : undefined,
    representLevel: typeof f.representLevel === "number" ? f.representLevel : undefined,
    sportName: typeof f.sportName === "string" ? f.sportName : fallback.sportName,
    talent: typeof f.talent === "number" ? f.talent : undefined,
    talentLevel: typeof f.talentLevel === "number" ? f.talentLevel : undefined,
    talentName: typeof f.talentName === "string" ? f.talentName : fallback.talentName,
    club: typeof f.club === "number" ? f.club : undefined,
    role: typeof f.role === "number" ? f.role : undefined,
    clubLevel: typeof f.clubLevel === "number" ? f.clubLevel : undefined,
    societyName: typeof f.societyName === "string" ? f.societyName : fallback.societyName,
    income: typeof f.income === "number" ? f.income : undefined,
    budget: typeof f.budget === "number" ? f.budget : undefined,
    bumi: typeof f.bumi === "number" ? f.bumi : undefined,
    studyLevel: typeof f.studyLevel === "number" ? f.studyLevel : undefined,
    joiningDate: typeof f.joiningDate === "number" ? f.joiningDate : undefined,
    studyPlan: typeof f.studyPlan === "number" ? f.studyPlan : fallback.studyPlan,
    states: isNumberArray(f.states) ? f.states : fallback.states,
    country: typeof f.country === "number" ? f.country : undefined,
    uniType: typeof f.uniType === "number" ? f.uniType : undefined,
    regUni: typeof f.regUni === "number" ? f.regUni : undefined,
    uniRegName: typeof f.uniRegName === "string" ? f.uniRegName : fallback.uniRegName,
  };
}

export function loadPathwayQuizProgress(
  fallbackForm: PathwayQuizFormState,
): { tab: number; form: PathwayQuizFormState } | null {
  if (typeof window === "undefined") return null;
  try {
    const raw = window.localStorage.getItem(STORAGE_KEY);
    if (!raw) return null;
    const data = JSON.parse(raw) as PersistedPayload;
    if (data.v !== 1 || typeof data.tab !== "number") return null;
    const tab = Math.min(9, Math.max(0, Math.floor(data.tab)));
    const form = parseForm(data.form, fallbackForm);
    if (!form) return null;
    return { tab, form };
  } catch {
    return null;
  }
}

export function savePathwayQuizProgress(tab: number, form: PathwayQuizFormState): void {
  if (typeof window === "undefined") return;
  try {
    const payload: PersistedPayload = {
      v: 1,
      tab: Math.min(9, Math.max(0, tab)),
      form,
      savedAt: Date.now(),
    };
    window.localStorage.setItem(STORAGE_KEY, JSON.stringify(payload));
  } catch {
    // Quota or private mode; ignore.
  }
}

export function clearPathwayQuizProgress(): void {
  if (typeof window === "undefined") return;
  try {
    window.localStorage.removeItem(STORAGE_KEY);
  } catch {
    // ignore
  }
}

export type PathwayMatchInput = {
  describe: number[];
  interest: number[];
  gender?: number;
  malaysian?: number;
  stream?: number;
  studyLevel?: number;
  budget?: number;
  income?: number;
  bumi?: number;
  studyPlan?: number;
  states?: number[];
  country?: number;
  uniType?: number;
};

export type PathwayMatchResultCard = {
  id: number;
  name: string;
  major: string;
  avg_salary: number;
  desc: string;
  avatar: string;
  percentage: number;
  rank: number;
  courseCount: number;
  universityCount: number;
};

const MATCH_INPUT_KEY = "edu4u.pathway360.matchInput";
const MATCH_RESULTS_KEY = "edu4u.pathway360.matchResults";

export function savePathwayMatchInput(input: PathwayMatchInput): void {
  if (typeof window === "undefined") return;
  try {
    window.localStorage.removeItem(MATCH_RESULTS_KEY);
    window.localStorage.setItem(MATCH_INPUT_KEY, JSON.stringify({ v: 1, ...input, savedAt: Date.now() }));
  } catch {
    // ignore
  }
}

export function loadPathwayMatchInput(): PathwayMatchInput | null {
  if (typeof window === "undefined") return null;
  try {
    const raw = window.localStorage.getItem(MATCH_INPUT_KEY);
    if (!raw) return null;
    const data = JSON.parse(raw) as PathwayMatchInput & { v?: number };
    if (!isNumberArray(data.describe) || !isNumberArray(data.interest)) return null;
    return {
      describe: data.describe,
      interest: data.interest,
      gender: typeof data.gender === "number" ? data.gender : undefined,
      malaysian: typeof data.malaysian === "number" ? data.malaysian : undefined,
      stream: typeof data.stream === "number" ? data.stream : undefined,
      studyLevel: typeof data.studyLevel === "number" ? data.studyLevel : undefined,
      budget: typeof data.budget === "number" ? data.budget : undefined,
      income: typeof data.income === "number" ? data.income : undefined,
      bumi: typeof data.bumi === "number" ? data.bumi : undefined,
      studyPlan: typeof data.studyPlan === "number" ? data.studyPlan : undefined,
      states: isNumberArray(data.states) ? data.states : undefined,
      country: typeof data.country === "number" ? data.country : undefined,
      uniType: typeof data.uniType === "number" ? data.uniType : undefined,
    };
  } catch {
    return null;
  }
}

export function savePathwayMatchResults(cards: PathwayMatchResultCard[]): void {
  if (typeof window === "undefined") return;
  try {
    window.localStorage.setItem(MATCH_RESULTS_KEY, JSON.stringify({ v: 1, cards, savedAt: Date.now() }));
  } catch {
    // ignore
  }
}

export function loadPathwayMatchResults(): PathwayMatchResultCard[] | null {
  if (typeof window === "undefined") return null;
  try {
    const raw = window.localStorage.getItem(MATCH_RESULTS_KEY);
    if (!raw) return null;
    const data = JSON.parse(raw) as { v?: number; cards?: PathwayMatchResultCard[] };
    if (data.v !== 1 || !Array.isArray(data.cards)) return null;
    return data.cards;
  } catch {
    return null;
  }
}

export function clearPathwayMatchSession(): void {
  if (typeof window === "undefined") return;
  try {
    window.localStorage.removeItem(MATCH_INPUT_KEY);
    window.localStorage.removeItem(MATCH_RESULTS_KEY);
  } catch {
    // ignore
  }
}
