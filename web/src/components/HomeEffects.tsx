"use client";

import { useEffect } from "react";
import Script from "next/script";

declare global {
  interface Window {
    marqueeInit?: (config: Record<string, unknown>) => void;
  }
}

type Props = {
  images: string[];
};

function initMarquees() {
  if (typeof window.marqueeInit !== "function") return;
  window.marqueeInit({
    uniqueid: "mycrawler",
    inc: 1,
    mouse: "false",
    moveatleast: 2,
    neutral: 150,
    savedirection: true,
    random: true,
  });
  window.marqueeInit({
    uniqueid: "mycrawlerReview",
    inc: 1,
    mouse: "false",
    moveatleast: 2,
    neutral: 150,
    savedirection: true,
    random: true,
  });
}

export function HomeEffects({ images }: Props) {
  useEffect(() => {
    if (images.length <= 1) return;
    const el = document.getElementById("rotatingImage") as HTMLImageElement | null;
    if (!el) return;

    let currentIndex = 0;
    const timer = window.setInterval(() => {
      el.style.opacity = "0.1";
      window.setTimeout(() => {
        currentIndex = (currentIndex + 1) % images.length;
        el.src = images[currentIndex];
        el.style.opacity = "1";
      }, 700);
    }, 7000);

    return () => window.clearInterval(timer);
  }, [images]);

  useEffect(() => {
    // Retry briefly until crawler.js has defined marqueeInit
    let tries = 0;
    const id = window.setInterval(() => {
      tries += 1;
      if (typeof window.marqueeInit === "function" || tries > 40) {
        window.clearInterval(id);
        if (typeof window.marqueeInit === "function") initMarquees();
      }
    }, 100);
    return () => window.clearInterval(id);
  }, []);

  return (
    <Script
      src="/assets/js/crawler.js"
      strategy="afterInteractive"
      onLoad={initMarquees}
    />
  );
}
