"use client";

import { useState, useEffect, useRef } from "react";
import Link from "next/link";
import { useRouter } from "next/navigation";
import { useMemberAuth } from "@/components/MemberAuthProvider";
import { useSiteFlags, useT } from "@/components/LocaleProvider";

/** Open SiteModals via custom events (shared with home #pathway CTAs). */
function openEduModal(kind: "login" | "signup" | "pathway" | "language") {
  window.dispatchEvent(new CustomEvent("edu4u:open-modal", { detail: { kind } }));
}

const LogoWhite = () => (
  <svg className="com-logo" xmlns="http://www.w3.org/2000/svg" width="72" height="26" viewBox="0 0 83 30" fill="none">
    <path d="M0 6.59174V24.4887H12.3744V19.7332H6.03377V17.6623H11.5818V13.3159H6.03377V11.3473H12.2466V6.59174H0Z" fill="white"/>
    <path d="M22.4989 19.1196C24.9533 19.1196 26.2827 17.7901 26.2827 15.5402C26.2827 13.2903 24.9533 11.9608 22.4989 11.9608H21.2972V19.1196H22.4989ZM15.2634 6.59177H23.0614C28.7884 6.59177 32.5211 10.3245 32.5211 15.5402C32.5211 20.7558 28.7884 24.4887 23.0614 24.4887H15.2634V6.59177Z" fill="white"/>
    <path d="M34.95 17.0743V6.59174H41.1371V17.1509C41.1371 18.685 41.9554 19.5031 43.2338 19.5031C44.5121 19.5031 45.3302 18.685 45.3302 17.1509V6.59174H51.5174V17.0743C51.5174 21.9831 48.5006 25 43.2338 25C37.9668 25 34.95 21.9831 34.95 17.0743Z" fill="white"/>
    <path d="M65.8347 17.0743V6.59174H72.022V17.1509C72.022 18.685 72.8395 19.5031 74.1181 19.5031C75.3966 19.5031 76.2148 18.685 76.2148 17.1509V6.59174H82.4021V17.0743C82.4021 21.9831 79.3847 25 74.1181 25C68.8514 25 65.8347 21.9831 65.8347 17.0743Z" fill="white"/>
    <path d="M59.8966 1.94003L56.9049 6.18799H59.8966V1.94003ZM59.8966 8.81583V7.07915H56.1701C55.99 7.07915 55.8438 6.93308 55.8438 6.75276V6.3394C55.8438 6.27271 55.8644 6.20743 55.9026 6.15264L59.4028 1.13963C59.4637 1.05221 59.5638 1 59.6702 1H60.6314C60.8115 1 60.9576 1.14607 60.9576 1.32633V6.18799H61.8332C62.0134 6.18799 62.1594 6.33406 62.1594 6.51418V6.75276C62.1594 6.93289 62.0134 7.07896 61.8332 7.07896H60.9576V8.81564C60.9576 8.99596 60.8115 9.14203 60.6314 9.14203H60.2228C60.0427 9.14203 59.8966 8.99596 59.8966 8.81583Z" fill="white"/>
  </svg>
);

const LogoDark = () => (
  <svg className="com-logo" xmlns="http://www.w3.org/2000/svg" width="72" height="26" viewBox="0 0 83 30" fill="none">
    <path d="M0 6.59174V24.4887H12.3744V19.7332H6.03377V17.6623H11.5818V13.3159H6.03377V11.3473H12.2466V6.59174H0Z" fill="#1A124E"/>
    <path d="M22.4989 19.1195C24.9533 19.1195 26.2827 17.7901 26.2827 15.5401C26.2827 13.2902 24.9533 11.9608 22.4989 11.9608H21.2972V19.1195H22.4989ZM15.2634 6.59174H23.0614C28.7884 6.59174 32.5211 10.3245 32.5211 15.5401C32.5211 20.7558 28.7884 24.4886 23.0614 24.4886H15.2634V6.59174Z" fill="#1A124E"/>
    <path d="M34.95 17.0743V6.59174H41.1371V17.1509C41.1371 18.685 41.9554 19.5031 43.2338 19.5031C44.5121 19.5031 45.3302 18.685 45.3302 17.1509V6.59174H51.5174V17.0743C51.5174 21.9831 48.5006 25 43.2338 25C37.9668 25 34.95 21.9831 34.95 17.0743Z" fill="#1A124E"/>
    <path d="M65.8347 17.0743V6.59174H72.022V17.1509C72.022 18.685 72.8395 19.5031 74.1181 19.5031C75.3966 19.5031 76.2148 18.685 76.2148 17.1509V6.59174H82.4021V17.0743C82.4021 21.9831 79.3847 25 74.1181 25C68.8514 25 65.8347 21.9831 65.8347 17.0743Z" fill="#1A124E"/>
    <path d="M59.8966 1.94003L56.9049 6.18799H59.8966V1.94003ZM59.8966 8.81583V7.07915H56.1701C55.99 7.07915 55.8438 6.93308 55.8438 6.75276V6.3394C55.8438 6.27271 55.8644 6.20743 55.9026 6.15264L59.4028 1.13963C59.4637 1.05221 59.5638 1 59.6702 1H60.6314C60.8115 1 60.9576 1.14607 60.9576 1.32633V6.18799H61.8332C62.0134 6.18799 62.1594 6.33406 62.1594 6.51418V6.75276C62.1594 6.93289 62.0134 7.07896 61.8332 7.07896H60.9576V8.81564C60.9576 8.99596 60.8115 9.14203 60.6314 9.14203H60.2228C60.0427 9.14203 59.8966 8.99596 59.8966 8.81583Z" fill="#1A124E"/>
  </svg>
);

const ChevronRight = () => (
  <svg xmlns="http://www.w3.org/2000/svg" width="6" height="10" viewBox="0 0 6 10" fill="none">
    <path d="M1 1L5 5L1 9" stroke="#1F1F1F" strokeLinecap="round" strokeLinejoin="round"/>
  </svg>
);

const GlobeIcon = ({ stroke = "white" }: { stroke?: string }) => (
  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
    <path d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z" stroke={stroke} strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round"/>
    <path d="M2 12H22M12 2C9.43223 4.69615 8 8.27674 8 12C8 15.7233 9.43223 19.3038 12 22C14.5678 19.3038 16 15.7233 16 12C16 8.27674 14.5678 4.69615 12 2Z" stroke={stroke} strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round"/>
  </svg>
);

const exploreIcons = {
  university: (
    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
      <path d="M21 9.99999C21.2652 9.99999 21.5196 9.89463 21.7071 9.70709C21.8946 9.51956 22 9.2652 22 8.99999V5.99999C22 5.79016 21.9339 5.58566 21.8111 5.41549C21.6884 5.24533 21.5151 5.11815 21.316 5.05199L12.316 2.05199C12.1108 1.98381 11.8892 1.98381 11.684 2.05199L2.684 5.05199C2.48487 5.11815 2.31164 5.24533 2.18886 5.41549C2.06609 5.58566 2.00001 5.79016 2 5.99999V8.99999C2 9.2652 2.10536 9.51956 2.29289 9.70709C2.48043 9.89463 2.73478 9.99999 3 9.99999H4V17.184C3.41669 17.3894 2.91128 17.7704 2.55318 18.2746C2.19507 18.7789 2.00184 19.3816 2 20V22C2 22.2652 2.10536 22.5196 2.29289 22.7071C2.48043 22.8946 2.73478 23 3 23H21C21.2652 23 21.5196 22.8946 21.7071 22.7071C21.8946 22.5196 22 22.2652 22 22V20C21.9982 19.3816 21.8049 18.7789 21.4468 18.2746C21.0887 17.7704 20.5833 17.3894 20 17.184V9.99999H21ZM20 21H4V20C4.00026 19.7349 4.10571 19.4807 4.29319 19.2932C4.48066 19.1057 4.73486 19.0003 5 19H19C19.2651 19.0003 19.5193 19.1057 19.7068 19.2932C19.8943 19.4807 19.9997 19.7349 20 20V21ZM6 17V9.99999H8V17H6ZM10 17V9.99999H14V17H10ZM16 17V9.99999H18V17H16ZM4 7.99999V6.71999L12 4.05399L20 6.72099V7.99999H4Z" fill="#1A124E"/>
    </svg>
  ),
  course: (
    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
      <path d="M18 2H6C4.9 2 4 2.9 4 4V20C4 21.1 4.9 22 6 22H18C19.1 22 20 21.1 20 20V4C20 2.9 19.1 2 18 2ZM9 4H11V9L10 8.25L9 9V4ZM18 20H6V4H7V13L10 10.75L13 13V4H18V20Z" fill="#1A124E"/>
    </svg>
  ),
  compare: (
    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
      <path d="M2 12C2 17.523 6.477 22 12 22C17.523 22 22 17.523 22 12C22 6.477 17.523 2 12 2C6.477 2 2 6.477 2 12Z" stroke="#1A124E" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"/>
      <path d="M14 14.25C14 14.664 14.336 15 14.75 15H16C16.2652 15 16.5196 14.8946 16.7071 14.7071C16.8946 14.5196 17 14.2652 17 14V13C17 12.7348 16.8946 12.4804 16.7071 12.2929C16.5196 12.1054 16.2652 12 16 12H15C14.7348 12 14.4804 11.8946 14.2929 11.7071C14.1054 11.5196 14 11.2652 14 11V10C14 9.73478 14.1054 9.48043 14.2929 9.29289C14.4804 9.10536 14.7348 9 15 9H16.25C16.4489 9 16.6397 9.07902 16.7803 9.21967C16.921 9.36032 17 9.55109 17 9.75M7 9L9 15L11 9" stroke="#1A124E" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"/>
    </svg>
  ),
  visit: (
    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
      <path d="M5.252 9.97501L16.912 4.42301C18.612 3.61301 20.386 5.38801 19.577 7.08901L14.025 18.748C13.266 20.341 10.966 20.243 10.346 18.59L9.32 15.851C9.21975 15.5838 9.06347 15.3412 8.86167 15.1393C8.65986 14.9375 8.41721 14.7813 8.15 14.681L5.41 13.654C3.758 13.034 3.659 10.734 5.252 9.97501Z" stroke="#1A124E" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"/>
    </svg>
  ),
};

const ProfileIcon = ({ stroke = "#1F1F1F" }: { stroke?: string }) => (
  <svg width="40" height="40" viewBox="0 0 26 26" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden>
    <circle cx="13" cy="13" r="12" fill="#F6F6F6" stroke="#E4E4E4" />
    <path
      d="M17.9343 17.12C17.2995 15.9651 16.3083 15.0653 15.1202 14.5653C15.711 14.106 16.1475 13.4656 16.3677 12.7349C16.5879 12.0042 16.5807 11.2202 16.3471 10.494C16.1136 9.76775 15.6655 9.13609 15.0664 8.68848C14.4673 8.24087 13.7475 8 13.009 8C12.2705 8 11.5507 8.24087 10.9516 8.68848C10.3525 9.13609 9.90445 9.76775 9.67091 10.494C9.43737 11.2202 9.43019 12.0042 9.65039 12.7349C9.87059 13.4656 10.307 14.106 10.8978 14.5653C9.70976 15.0653 8.71857 15.9651 8.0838 17.12C8.04446 17.1855 8.01825 17.2586 8.00671 17.3349C7.99517 17.4111 7.99854 17.489 8.01663 17.5639C8.03471 17.6388 8.06714 17.7092 8.11198 17.7708C8.15682 17.8325 8.21316 17.8841 8.27764 17.9228C8.34213 17.9614 8.41345 17.9862 8.48737 17.9956C8.56128 18.0051 8.63628 17.9991 8.70791 17.9779C8.77953 17.9568 8.84632 17.9209 8.9043 17.8724C8.96228 17.824 9.01027 17.7639 9.04541 17.6959C9.88434 16.1927 11.3659 15.2962 13.009 15.2962C14.6522 15.2962 16.1337 16.1932 16.9726 17.6959C17.0488 17.8228 17.1696 17.9141 17.3095 17.9504C17.4494 17.9867 17.5975 17.9652 17.7224 17.8904C17.8472 17.8156 17.9392 17.6934 17.9787 17.5496C18.0183 17.4058 18.0023 17.2518 17.9343 17.12ZM10.6015 11.6488C10.6015 11.1553 10.7427 10.6728 11.0072 10.2624C11.2718 9.85197 11.6478 9.5321 12.0877 9.34322C12.5276 9.15433 13.0117 9.10491 13.4787 9.2012C13.9457 9.2975 14.3747 9.53518 14.7114 9.88419C15.0481 10.2332 15.2774 10.6779 15.3703 11.162C15.4632 11.6461 15.4155 12.1479 15.2333 12.6039C15.0511 13.0599 14.7425 13.4496 14.3466 13.7239C13.9507 13.9981 13.4852 14.1444 13.009 14.1444C12.3707 14.1437 11.7588 13.8805 11.3075 13.4127C10.8561 12.9448 10.6022 12.3105 10.6015 11.6488Z"
      fill={stroke}
    />
  </svg>
);

export function SiteHeader({
  overlayHero = false,
}: {
  /** When true, use light (white) chrome while the transparent header overlays a dark hero. */
  overlayHero?: boolean;
}) {
  const t = useT();
  const router = useRouter();
  const { member, logout } = useMemberAuth();
  const { careersHidden } = useSiteFlags();
  const [scrolled, setScrolled] = useState(false);
  const [exploreOpen, setExploreOpen] = useState(false);
  const exploreRef = useRef<HTMLDivElement>(null);

  const exploreItems = [
    {
      href: "/explore?tab=university",
      title: t("uni"),
      desc: t("browseUni"),
      icon: exploreIcons.university,
    },
    {
      href: "/explore?tab=course",
      title: t("courses"),
      desc: t("discoverCourse"),
      icon: exploreIcons.course,
    },
    {
      href: "/compare-course",
      title: t("compareCourse"),
      desc: t("compareDesc"),
      icon: exploreIcons.compare,
    },
    {
      href: "/visit-campus",
      title: t("campusVisit"),
      desc: t("visitDesc"),
      icon: exploreIcons.visit,
    },
  ];

  useEffect(() => {
    const onScroll = () => setScrolled(window.scrollY > 60);
    onScroll();
    window.addEventListener("scroll", onScroll, { passive: true });
    return () => window.removeEventListener("scroll", onScroll);
  }, []);

  // Close explore dropdown on outside click
  useEffect(() => {
    const handler = (e: MouseEvent) => {
      if (exploreRef.current && !exploreRef.current.contains(e.target as Node)) {
        setExploreOpen(false);
      }
    };
    document.addEventListener("mousedown", handler);
    return () => document.removeEventListener("mousedown", handler);
  }, []);

  const onLogout = async () => {
    await logout();
    router.refresh();
  };

  // Transparent at top; solid white after scroll. Never use drop shadows.
  // Dark photo heroes need light chrome while transparent; light heroes keep dark chrome.
  const isTransparent = !scrolled;
  const useLightChrome = overlayHero && isTransparent;
  const textColorClass = useLightChrome ? "header-option" : "header-option-black";
  const headerBg = isTransparent ? "custom-header" : "custom-header bg-white";

  return (
    <div
      className={`position-fixed w-100 ${headerBg}`}
      style={{ zIndex: 100, top: 0, left: 0 }}
    >
      <div className="header-bar pr-44 pl-44 d-flex justify-content-between align-items-center">
        {/* Left: logo + nav */}
        <div className="d-flex align-items-center header-nav-left">
          <Link href="/" className="d-flex align-items-center">
            {useLightChrome ? <LogoWhite /> : <LogoDark />}
          </Link>

          <nav className="d-none d-lg-flex align-items-center header-menu">
            <div
              className="position-relative"
              ref={exploreRef}
              onMouseEnter={() => setExploreOpen(true)}
              onMouseLeave={() => setExploreOpen(false)}
            >
              <span
                className={`${textColorClass} cursor menu-explore`}
                role="button"
                tabIndex={0}
                aria-expanded={exploreOpen}
                aria-haspopup="true"
                onFocus={() => setExploreOpen(true)}
                onKeyDown={(e) => {
                  if (e.key === "Enter" || e.key === " ") {
                    e.preventDefault();
                    setExploreOpen((v) => !v);
                  }
                  if (e.key === "Escape") setExploreOpen(false);
                }}
              >
                Explore
              </span>
              {exploreOpen && (
                <div className="dropdown-option header-explore-dropdown" style={{ display: "block" }}>
                  {exploreItems.map((item) => (
                    <div key={item.href} className="home-dropdown-option-outer">
                      <Link href={item.href} onClick={() => setExploreOpen(false)}>
                        <div className="d-flex align-items-center">
                          <div className="dropdown-option-icon">{item.icon}</div>
                          <div className="ms-3 flex-grow-1">
                            <span className="dropdown-option-icon-main-title">{item.title}</span>
                            <span className="dropdown-option-icon-desc">{item.desc}</span>
                          </div>
                          <div className="ms-2 flex-shrink-0"><ChevronRight /></div>
                        </div>
                      </Link>
                    </div>
                  ))}
                </div>
              )}
            </div>

            {!careersHidden ? (
              <Link className={`${textColorClass} cursor`} href="/careers">{t("careers")}</Link>
            ) : null}
            <Link className={`${textColorClass} cursor`} href="/blog">{t("blog")}</Link>
            <span
              className={`${textColorClass} cursor`}
              id="pathway"
              role="button"
              tabIndex={0}
              onClick={() => openEduModal("pathway")}
              onKeyDown={(e) => e.key === "Enter" && openEduModal("pathway")}
            >
              {t("pathway360")}
            </span>
            <Link className={`${textColorClass} cursor`} href="/contact-us">{t("enquireNow")}</Link>
          </nav>
        </div>

        {/* Right: language + auth (desktop) */}
        <div className="d-none d-lg-flex align-items-center header-actions">
          <button
            type="button"
            className={`header-lang-btn ${useLightChrome ? "header-lang-btn-light" : "header-lang-btn-dark"}`}
            aria-label={t("selectLanguage")}
            onClick={() => openEduModal("language")}
          >
            <GlobeIcon stroke={useLightChrome ? "white" : "#1A124E"} />
          </button>
          {member ? (
            <>
              <Link
                href="/characters"
                className="ms-3 d-inline-flex"
                id="profile_btn"
                aria-label={t("myAccount")}
                title={member.email}
              >
                <ProfileIcon />
              </Link>
              <button
                type="button"
                className={`ms-4 border-0 bg-transparent cursor ${useLightChrome ? "header-login" : "header-login-active"}`}
                onClick={onLogout}
              >
                {t("logOut")}
              </button>
            </>
          ) : (
            <>
              <span
                className={useLightChrome ? "header-login cursor" : "header-login-active cursor"}
                id="login_btn"
                role="button"
                tabIndex={0}
                onClick={() => openEduModal("login")}
                onKeyDown={(e) => e.key === "Enter" && openEduModal("login")}
              >
                {t("logIn")}
              </span>
              <span
                className={`cursor text-center ${useLightChrome ? "header-started" : "header-started-active"}`}
                id="join_btn"
                role="button"
                tabIndex={0}
                onClick={() => openEduModal("signup")}
                onKeyDown={(e) => e.key === "Enter" && openEduModal("signup")}
              >
                {t("join")}
              </span>
            </>
          )}
        </div>

        {/* Mobile: language + hamburger */}
        <div className="d-flex d-lg-none align-items-center gap-2">
          <button
            type="button"
            className={`header-lang-btn ${useLightChrome ? "header-lang-btn-light" : "header-lang-btn-dark"}`}
            aria-label={t("selectLanguage")}
            onClick={() => openEduModal("language")}
          >
            <GlobeIcon stroke={useLightChrome ? "white" : "#1A124E"} />
          </button>
          {member ? (
            <Link href="/characters" className="d-inline-flex" id="profile_btn" aria-label={t("myAccount")}>
              <ProfileIcon stroke={useLightChrome ? "white" : "#1F1F1F"} />
            </Link>
          ) : null}
          <svg
            id="kt_drawer_example_dismiss_button"
            width="32"
            height="32"
            viewBox="0 0 32 32"
            fill="none"
            xmlns="http://www.w3.org/2000/svg"
            className="cursor"
            role="button"
            tabIndex={0}
            aria-label="Open menu"
            onClick={() => window.dispatchEvent(new CustomEvent("edu4u:open-drawer"))}
          >
            <path
              d="M5.3335 16H26.6668M5.3335 8H26.6668M5.3335 24H26.6668"
              stroke={useLightChrome ? "white" : "#1A124E"}
              strokeWidth="2"
              strokeLinecap="round"
              strokeLinejoin="round"
            />
          </svg>
        </div>
      </div>
    </div>
  );
}
