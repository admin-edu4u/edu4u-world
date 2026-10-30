"use client";

type PathwayUnlockCardProps = {
  name: string;
  avatarSrc: string;
  percentage: number;
  rank: 1 | 2 | 3;
  isActive: boolean;
  matchRateLabel: string;
  onSelect: () => void;
};

export function PathwayUnlockCard({
  name,
  avatarSrc,
  percentage,
  rank,
  isActive,
  matchRateLabel,
  onSelect,
}: PathwayUnlockCardProps) {
  return (
    <button
      type="button"
      className={`pathway-unlock-card pathway-unlock-card--rank-${rank}${isActive ? " is-active" : ""}`}
      onClick={onSelect}
      aria-pressed={isActive}
      aria-label={`${name}, ${matchRateLabel} ${percentage}%`}
    >
      <span className="pathway-unlock-card__rank" aria-hidden>
        <span className="pathway-unlock-card__rank-num">{rank}</span>
      </span>
      <div className="pathway-unlock-card__panel">
        <div className="pathway-unlock-card__sheen" aria-hidden />
        <h3 className="pathway-unlock-card__name">{name}</h3>
        <div className="pathway-unlock-card__avatar-wrap">
          {avatarSrc ? (
            // eslint-disable-next-line @next/next/no-img-element
            <img className="pathway-unlock-card__avatar" src={avatarSrc} alt="" />
          ) : (
            <div className="pathway-unlock-card__avatar-placeholder" aria-hidden />
          )}
        </div>
        <p className="pathway-unlock-card__match">
          <span>
            {matchRateLabel}: {percentage}%
          </span>
        </p>
      </div>
    </button>
  );
}

export const pathwayUnlockCardStyles = `
  .character-cards-row {
    display: flex;
    justify-content: center;
    align-items: flex-end;
    gap: 20px;
    margin-top: 56px;
    flex-wrap: nowrap;
  }
  .pathway-unlock-card {
    position: relative;
    flex: 0 0 auto;
    width: min(100%, 280px);
    padding: 0;
    border: none;
    background: transparent;
    cursor: pointer;
    text-align: center;
    font: inherit;
    transition: transform 0.28s ease, opacity 0.28s ease, filter 0.28s ease;
    opacity: 0.62;
    filter: saturate(0.85);
  }
  .pathway-unlock-card.is-active {
    opacity: 1;
    filter: none;
    transform: translateY(-8px) scale(1.04);
    z-index: 3;
  }
  .pathway-unlock-card--rank-1:not(.is-active) {
    transform: translateY(-4px) scale(1.02);
    opacity: 0.78;
  }
  .pathway-unlock-card__rank {
    position: absolute;
    top: -14px;
    left: 50%;
    z-index: 2;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    transform: translateX(-50%) rotate(45deg);
    border-radius: 6px;
    font-size: 15px;
    font-weight: 700;
    line-height: 1;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.35);
  }
  .pathway-unlock-card--rank-1 .pathway-unlock-card__rank {
    background: #c8f462;
    color: #0d2227;
  }
  .pathway-unlock-card--rank-2 .pathway-unlock-card__rank {
    background: #8a9396;
    color: #fff;
  }
  .pathway-unlock-card--rank-3 .pathway-unlock-card__rank {
    background: #a67c52;
    color: #fff;
  }
  .pathway-unlock-card__rank-num {
    display: block;
    transform: rotate(-45deg);
  }
  .pathway-unlock-card__panel {
    position: relative;
    overflow: hidden;
    border-radius: 24px;
    border: 1px solid rgba(255, 255, 255, 0.16);
    background: linear-gradient(168deg, #1a124e 0%, #152638 48%, #0f2830 100%);
    padding: 52px 20px 22px;
    min-height: 400px;
    display: flex;
    flex-direction: column;
    align-items: center;
    box-shadow: 0 16px 40px rgba(0, 0, 0, 0.25);
    transition: border-color 0.28s ease, box-shadow 0.28s ease;
  }
  .pathway-unlock-card.is-active .pathway-unlock-card__panel {
    border-color: #c8f462;
    box-shadow:
      0 0 0 1px rgba(200, 244, 98, 0.45),
      0 0 48px rgba(200, 244, 98, 0.18),
      0 24px 48px rgba(0, 0, 0, 0.35);
  }
  .pathway-unlock-card__sheen {
    pointer-events: none;
    position: absolute;
    inset: 0;
    background: linear-gradient(
      125deg,
      transparent 0%,
      transparent 42%,
      rgba(255, 255, 255, 0.06) 50%,
      transparent 58%,
      transparent 100%
    );
    opacity: 0.9;
  }
  .pathway-unlock-card__sheen::after {
    content: "";
    position: absolute;
    left: -20%;
    bottom: 28%;
    width: 140%;
    height: 55%;
    background: rgba(26, 18, 78, 0.35);
    transform: skewY(-38deg);
    transform-origin: left bottom;
  }
  .pathway-unlock-card__name {
    position: relative;
    z-index: 1;
    margin: 0;
    min-height: 52px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    font-weight: 700;
    color: #fff;
    line-height: 1.25;
    max-width: 100%;
  }
  .pathway-unlock-card__avatar-wrap {
    position: relative;
    z-index: 1;
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    min-height: 200px;
    margin: 8px 0 12px;
  }
  .pathway-unlock-card__avatar {
    width: 200px;
    height: 200px;
    object-fit: contain;
    display: block;
  }
  .pathway-unlock-card__avatar-placeholder {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    border: 2px dashed rgba(255, 255, 255, 0.2);
  }
  .pathway-unlock-card__match {
    position: relative;
    z-index: 1;
    margin: 0;
    width: 100%;
    border-radius: 999px;
    padding: 12px 16px;
    font-size: 14px;
    font-weight: 600;
    line-height: 1.2;
  }
  .pathway-unlock-card--rank-1 .pathway-unlock-card__match {
    background: #c8f462;
    color: #1a124e;
  }
  .pathway-unlock-card--rank-2 .pathway-unlock-card__match {
    background: rgba(255, 255, 255, 0.14);
    color: #fff;
  }
  .pathway-unlock-card--rank-3 .pathway-unlock-card__match {
    background: rgba(166, 124, 82, 0.55);
    color: #fff;
  }
  .pathway-unlock-card.is-active .pathway-unlock-card__match {
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.2);
  }
  .pathway-unlock-card:focus-visible {
    outline: 2px solid #c8f462;
    outline-offset: 4px;
    border-radius: 28px;
  }
  @media (max-width: 767px) {
    .character-cards-row {
      justify-content: flex-start;
      overflow-x: auto;
      padding: 0 16px 20px;
      scroll-snap-type: x mandatory;
      scrollbar-width: none;
    }
    .character-cards-row::-webkit-scrollbar {
      display: none;
    }
    .pathway-unlock-card {
      scroll-snap-align: center;
      width: 260px;
      opacity: 1;
      filter: none;
    }
    .pathway-unlock-card:not(.is-active) {
      opacity: 0.75;
    }
  }
`;
