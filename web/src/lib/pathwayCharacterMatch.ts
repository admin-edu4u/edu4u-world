/** Match Pathway360 personality picks to character cards (legacy parity). */

export type CharacterVote = {
  characterId: number;
  count: number;
};

export type RankedCharacterMatch = {
  characterId: number;
  count: number;
  percentage: number;
  rank: number;
};

export function parseCharacterIdsFromPersonality(csv: string | null | undefined): number[] {
  if (!csv) return [];
  const ids: number[] = [];
  for (const part of csv.split(",")) {
    const id = Number.parseInt(part.trim(), 10);
    if (Number.isFinite(id) && id > 0) ids.push(id);
  }
  return ids;
}

export function tallyCharacterVotes(
  personalityIds: number[],
  personalityCharacterMap: Map<number, string>,
): CharacterVote[] {
  const counts = new Map<number, number>();
  for (const pid of personalityIds) {
    const csv = personalityCharacterMap.get(pid);
    for (const charId of parseCharacterIdsFromPersonality(csv)) {
      counts.set(charId, (counts.get(charId) ?? 0) + 1);
    }
  }
  return [...counts.entries()]
    .map(([characterId, count]) => ({ characterId, count }))
    .sort((a, b) => b.count - a.count || a.characterId - b.characterId);
}

/** Percentages derived from vote strength (deterministic for the same form). */
export function matchPercentagesForVotes(votes: CharacterVote[]): number[] {
  if (votes.length === 0) return [];
  const top = votes[0].count || 1;
  const rankCeilings = [97.5, 90, 83];

  return votes.map((vote, index) => {
    const ratio = vote.count / top;
    const ceiling = rankCeilings[index] ?? 78;
    const floor = index === 0 ? 90 : index === 1 ? 82 : 75;
    const raw = floor + ratio * (ceiling - floor);
    return Math.round(Math.min(99.9, Math.max(floor, raw)) * 10) / 10;
  });
}

export function rankTopCharacterMatches(
  personalityIds: number[],
  personalityCharacterMap: Map<number, string>,
  limit = 3,
): RankedCharacterMatch[] {
  const votes = tallyCharacterVotes(personalityIds, personalityCharacterMap).slice(0, limit);
  const percentages = matchPercentagesForVotes(votes);
  return votes.map((vote, i) => ({
    characterId: vote.characterId,
    count: vote.count,
    percentage: percentages[i] ?? 75,
    rank: i + 1,
  }));
}
