export type PreferredPathwayOption = {
  id: number;
  labelKey: string;
  overviewTitleKey?: string;
  bulletKeys?: readonly [string, string, string];
};

export const PREFERRED_PATHWAY_OPTIONS: PreferredPathwayOption[] = [
  {
    id: 1,
    labelKey: "exploreLevelFoundation",
    overviewTitleKey: "foundationOverview",
    bulletKeys: ["foundation1", "foundation2", "foundation3"],
  },
  {
    id: 2,
    labelKey: "pathwayPrefPreU",
    overviewTitleKey: "preUOverview",
    bulletKeys: ["preU1", "preU2", "preU3"],
  },
  {
    id: 3,
    labelKey: "exploreLevelDiploma",
    overviewTitleKey: "diplomaOverview",
    bulletKeys: ["diploma1", "diploma2", "diploma3"],
  },
  {
    id: 4,
    labelKey: "exploreLevelDegree",
    overviewTitleKey: "degreeOverview",
    bulletKeys: ["degree1", "degree2", "degree3"],
  },
  { id: 5, labelKey: "exploreLevelMasters" },
  { id: 6, labelKey: "exploreLevelPhd" },
];

export function preferredPathwayOverview(levelId?: number) {
  if (!levelId) return null;
  return PREFERRED_PATHWAY_OPTIONS.find((o) => o.id === levelId && o.bulletKeys) ?? null;
}
