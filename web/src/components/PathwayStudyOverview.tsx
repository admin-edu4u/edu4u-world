"use client";

import { useT } from "@/components/LocaleProvider";
import { preferredPathwayOverview } from "@/data/pathwayStudyLevels";

type Props = {
  levelId?: number;
};

export function PathwayStudyOverview({ levelId }: Props) {
  const t = useT();
  const overview = preferredPathwayOverview(levelId);
  if (!overview?.bulletKeys || !overview.overviewTitleKey) return null;

  return (
    <div className="pathway-study-overview" aria-live="polite">
      <p className="pathway-study-overview-title">{t(overview.overviewTitleKey)}</p>
      <ul className="pathway-study-overview-list">
        {overview.bulletKeys.map((key) => (
          <li key={key}>{t(key)}</li>
        ))}
      </ul>
    </div>
  );
}
