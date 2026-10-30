/** Shared Pathway360 quiz labels as dictionary keys (not display strings). */

export const PATHWAY_STEP_LABEL_KEYS = [
  "pathwayNavAccount",
  "pathwayNavPersonality",
  "pathwayNavAcademic",
  "pathwayNavResults",
  "pathwayNavRepresentation",
  "pathwayNavFamily",
  "pathwayNavPathway",
  "pathwayNavLocation",
  "pathwayNavUniversity",
  "pathwayNavMatched",
] as const;

export const PATHWAY_STATE_KEYS: Record<number, string> = {
  1: "johor",
  2: "kedah",
  3: "kelantan",
  4: "melaka",
  5: "negeriSembilan",
  6: "pahang",
  7: "perak",
  8: "perlis",
  9: "pulauPinang",
  10: "selangor",
  11: "terengganu",
  12: "kualaLumpur",
  13: "putrajaya",
  14: "sarawak",
  15: "sabah",
  16: "labuan",
};

export const PATHWAY_STREAM_OPTIONS = [
  { id: 1, key: "sciStream" },
  { id: 3, key: "subSciStream" },
  { id: 2, key: "artStream" },
  { id: 0, key: "others" },
] as const;

export const PATHWAY_ACADEMIC_LEVEL_OPTIONS = [
  { id: 1, key: "academicLevelSpm" },
  { id: 2, key: "academicLevelHigher" },
  { id: 3, key: "others" },
] as const;

export const PATHWAY_QUAL_SPM = [
  { id: 1, key: "qualSpm" },
  { id: 2, key: "qualOLevel" },
  { id: 12, key: "others" },
] as const;

export const PATHWAY_QUAL_POST_SPM: { id: number; key: string }[] = [
  { id: 3, key: "qualStpm" },
  { id: 4, key: "qualUec" },
  { id: 5, key: "qualFoundation" },
  { id: 6, key: "qualDiploma" },
  { id: 7, key: "qualALevel" },
  { id: 8, key: "qualIB" },
  { id: 9, key: "qualCPU" },
  { id: 10, key: "qualSACE" },
  { id: 13, key: "qualSKM" },
  { id: 14, key: "qualPreU" },
  { id: 15, key: "qualMUFY" },
  { id: 16, key: "qualMaster" },
  { id: 17, key: "qualPhd" },
  { id: 11, key: "others" },
];

export const PATHWAY_REPRESENT_LEVELS = [
  { id: 1, key: "school" },
  { id: 2, key: "district" },
  { id: 3, key: "state" },
  { id: 4, key: "national" },
] as const;

export const PATHWAY_ROLE_KEYS = [
  "president",
  "vicePresident",
  "secretary",
  "treasurer",
  "director",
  "others",
] as const;

export const PATHWAY_INCOME_OPTIONS = [
  { id: 1, key: "incomeBelow5000" },
  { id: 2, key: "incomeBelow8000" },
  { id: 3, key: "incomeAbove8000" },
] as const;

export const PATHWAY_BUDGET_OPTIONS = [
  { id: 1, key: "exploreFeeBelow20k" },
  { id: 2, key: "exploreFee20to40k" },
  { id: 3, key: "exploreFee40to60k" },
  { id: 4, key: "exploreFee60to80k" },
  { id: 5, key: "exploreFee80to100k" },
  { id: 6, key: "exploreFeeAbove100k" },
] as const;

export const PATHWAY_JOIN_OPTIONS = [
  { id: 1, key: "plan1" },
  { id: 2, key: "plan2" },
  { id: 3, key: "plan3" },
  { id: 4, key: "plan4" },
] as const;

export const PATHWAY_STUDY_PLAN_OPTIONS = [
  { id: 1, key: "planAt1" },
  { id: 2, key: "planAt2" },
  { id: 3, key: "planAt3" },
] as const;

export const PATHWAY_ABROAD_COUNTRY_OPTIONS = [
  { id: 192, key: "singapore" },
  { id: 13, key: "australia" },
  { id: 826, key: "unitedKingdom" },
  { id: 840, key: "unitedStates" },
] as const;

export const PATHWAY_LOADING_MSG_KEYS = [
  "analyseProfile",
  "buildingCharacter",
  "findBestCourse",
] as const;

export function personalityDictKey(id: number): string {
  return `personality${id}`;
}
