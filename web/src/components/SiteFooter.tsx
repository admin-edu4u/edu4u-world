"use client";

import Link from "next/link";
import { useSiteFlags, useT } from "@/components/LocaleProvider";

const EduLogo = () => (
  <svg xmlns="http://www.w3.org/2000/svg" width="83" height="30" viewBox="0 0 83 30" fill="none">
    <path d="M0 6.5918V24.4888H12.3744V19.7332H6.03377V17.6623H11.5818V13.316H6.03377V11.3473H12.2466V6.5918H0Z" fill="white"/>
    <path d="M22.4989 19.1196C24.9533 19.1196 26.2827 17.7902 26.2827 15.5402C26.2827 13.2903 24.9533 11.9609 22.4989 11.9609H21.2972V19.1196H22.4989ZM15.2634 6.5918H23.0614C28.7884 6.5918 32.5211 10.3245 32.5211 15.5402C32.5211 20.7559 28.7884 24.4887 23.0614 24.4887H15.2634V6.5918Z" fill="white"/>
    <path d="M34.95 17.0743V6.5918H41.1371V17.151C41.1371 18.685 41.9554 19.5032 43.2338 19.5032C44.5121 19.5032 45.3302 18.685 45.3302 17.151V6.5918H51.5174V17.0743C51.5174 21.9832 48.5006 25 43.2338 25C37.9668 25 34.95 21.9832 34.95 17.0743Z" fill="white"/>
    <path d="M65.8347 17.0743V6.5918H72.022V17.151C72.022 18.685 72.8395 19.5032 74.1181 19.5032C75.3966 19.5032 76.2148 18.685 76.2148 17.151V6.5918H82.4021V17.0743C82.4021 21.9832 79.3847 25 74.1181 25C68.8514 25 65.8347 21.9832 65.8347 17.0743Z" fill="white"/>
    <path d="M59.8966 1.94003L56.9049 6.18799H59.8966V1.94003ZM59.8966 8.81583V7.07915H56.1701C55.99 7.07915 55.8438 6.93308 55.8438 6.75276V6.3394C55.8438 6.27271 55.8644 6.20743 55.9026 6.15264L59.4028 1.13963C59.4637 1.05221 59.5638 1 59.6702 1H60.6314C60.8115 1 60.9576 1.14607 60.9576 1.32633V6.18799H61.8332C62.0134 6.18799 62.1594 6.33406 62.1594 6.51418V6.75276C62.1594 6.93289 62.0134 7.07896 61.8332 7.07896H60.9576V8.81564C60.9576 8.99596 60.8115 9.14203 60.6314 9.14203H60.2228C60.0427 9.14203 59.8966 8.99596 59.8966 8.81583Z" fill="white"/>
  </svg>
);

const FacebookIcon = () => (
  <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32" fill="none">
    <path d="M29.3334 15.9998C29.3334 8.63984 23.3601 2.6665 16.0001 2.6665C8.64008 2.6665 2.66675 8.63984 2.66675 15.9998C2.66675 22.4532 7.25341 27.8265 13.3334 29.0665V19.9998H10.6667V15.9998H13.3334V12.6665C13.3334 10.0932 15.4267 7.99984 18.0001 7.99984H21.3334V11.9998H18.6667C17.9334 11.9998 17.3334 12.5998 17.3334 13.3332V15.9998H21.3334V19.9998H17.3334V29.2665C24.0667 28.5998 29.3334 22.9198 29.3334 15.9998Z" fill="white"/>
  </svg>
);

const InstagramIcon = () => (
  <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32" fill="none">
    <path d="M17.3708 2.66814C18.3438 2.66441 19.3167 2.67419 20.2895 2.69748L20.5481 2.70681C20.8468 2.71748 21.1415 2.73081 21.4975 2.74681C22.9161 2.81348 23.8841 3.03748 24.7335 3.36681C25.6135 3.70548 26.3548 4.16414 27.0961 4.90548C27.774 5.57162 28.2986 6.37741 28.6335 7.26681C28.9628 8.11614 29.1868 9.08548 29.2535 10.5041C29.2695 10.8588 29.2828 11.1548 29.2935 11.4535L29.3015 11.7121C29.3251 12.6844 29.3354 13.6569 29.3321 14.6295L29.3335 15.6241V17.3708C29.3367 18.3438 29.3265 19.3168 29.3028 20.2895L29.2948 20.5481C29.2841 20.8468 29.2708 21.1415 29.2548 21.4975C29.1881 22.9161 28.9615 23.8841 28.6335 24.7335C28.2997 25.6238 27.7749 26.4303 27.0961 27.0961C26.4294 27.7739 25.6232 28.2985 24.7335 28.6335C23.8841 28.9628 22.9161 29.1868 21.4975 29.2535C21.1415 29.2695 20.8468 29.2828 20.5481 29.2935L20.2895 29.3015C19.3168 29.3252 18.3438 29.3354 17.3708 29.3321L16.3761 29.3335H14.6308C13.6578 29.3368 12.6848 29.3265 11.7121 29.3028L11.4535 29.2948C11.1369 29.2833 10.8205 29.27 10.5041 29.2548C9.08545 29.1881 8.11745 28.9615 7.26678 28.6335C6.37703 28.2993 5.5711 27.7746 4.90545 27.0961C4.22685 26.4299 3.70175 25.6236 3.36679 24.7335C3.03745 23.8841 2.81345 22.9161 2.74678 21.4975C2.73194 21.1811 2.7186 20.8647 2.70678 20.5481L2.70012 20.2895C2.67554 19.3168 2.66442 18.3438 2.66678 17.3708V14.6295C2.66306 13.6569 2.67284 12.6844 2.69612 11.7121L2.70545 11.4535C2.71612 11.1548 2.72945 10.8588 2.74545 10.5041C2.81212 9.08414 3.03612 8.11748 3.36545 7.26681C3.70062 6.37697 4.22674 5.5714 4.90679 4.90681C5.57198 4.22781 6.3774 3.70224 7.26678 3.36681C8.11745 3.03748 9.08412 2.81348 10.5041 2.74681L11.4535 2.70681L11.7121 2.70014C12.6844 2.67558 13.6569 2.66446 14.6295 2.66681L17.3708 2.66814ZM16.0001 9.33481C15.1168 9.32232 14.2398 9.48551 13.4201 9.8149C12.6004 10.1443 11.8543 10.6333 11.2252 11.2536C10.5962 11.8738 10.0967 12.6129 9.7557 13.4278C9.41475 14.2428 9.23917 15.1174 9.23917 16.0008C9.23917 16.8842 9.41475 17.7588 9.7557 18.5738C10.0967 19.3888 10.5962 20.1278 11.2252 20.7481C11.8543 21.3683 12.6004 21.8573 13.4201 22.1867C14.2398 22.5161 15.1168 22.6793 16.0001 22.6668C17.7682 22.6668 19.4639 21.9644 20.7142 20.7142C21.9644 19.4639 22.6668 17.7683 22.6668 16.0001C22.6668 14.232 21.9644 12.5363 20.7142 11.2861C19.4639 10.0359 17.7682 9.33481 16.0001 9.33481ZM16.0001 12.0015C16.5315 11.9917 17.0594 12.0879 17.5532 12.2844C18.0469 12.481 18.4966 12.7739 18.8758 13.1462C19.2551 13.5185 19.5564 13.9626 19.762 14.4526C19.9677 14.9426 20.0737 15.4687 20.0738 16.0001C20.0739 16.5316 19.9681 17.0577 19.7626 17.5478C19.557 18.0378 19.2559 18.4821 18.8768 18.8545C18.4976 19.2269 18.0481 19.52 17.5544 19.7167C17.0607 19.9134 16.5328 20.0098 16.0015 20.0001C14.9406 20.0001 13.9232 19.5787 13.173 18.8286C12.4229 18.0784 12.0015 17.061 12.0015 16.0001C12.0015 14.9393 12.4229 13.9219 13.173 13.1717C13.9232 12.4216 14.9406 12.0001 16.0015 12.0001L16.0001 12.0015ZM23.0001 7.33481C22.57 7.35203 22.1632 7.53501 21.8649 7.84544C21.5667 8.15587 21.4001 8.56966 21.4001 9.00014C21.4001 9.43063 21.5667 9.84442 21.8649 10.1548C22.1632 10.4653 22.57 10.6483 23.0001 10.6655C23.4421 10.6655 23.8661 10.4899 24.1786 10.1773C24.4912 9.86476 24.6668 9.44084 24.6668 8.99881C24.6668 8.55678 24.4912 8.13286 24.1786 7.8203C23.8661 7.50774 23.4421 7.33214 23.0001 7.33214V7.33481Z" fill="white"/>
  </svg>
);

const XhsIcon = () => (
  <svg width="32" height="33" viewBox="0 0 32 33" fill="none" xmlns="http://www.w3.org/2000/svg">
    <rect x="3" y="3.38281" width="26" height="26" rx="4" fill="white"/>
    <path d="M12.7559 18.9692C13.0056 19.0479 13.522 19.0831 14.4148 19.0635" stroke="#1A124E" strokeLinecap="round" strokeLinejoin="round"/>
    <path d="M6.48718 15.0942C6.48718 16.2254 6.34718 17.6972 6 18.2543" stroke="#1A124E" strokeLinecap="round" strokeLinejoin="round"/>
    <path d="M8.88974 13.5078V18.6185C8.88988 18.6839 8.87516 18.7487 8.84642 18.8092C8.81768 18.8696 8.77548 18.9246 8.72224 18.9709C8.66901 19.0173 8.60576 19.054 8.53613 19.0792C8.4665 19.1043 8.39184 19.1173 8.31641 19.1174H7.7959" stroke="#1A124E" strokeLinecap="round" strokeLinejoin="round"/>
    <path d="M15.0648 17.3951H14.5187C13.7946 17.3951 13.6454 17.4626 13.9433 16.8957L14.73 15.4004" stroke="#1A124E" strokeLinecap="round" strokeLinejoin="round"/>
    <path d="M25.1836 13.8862C25.5913 14.0214 25.8364 14.2642 26.0005 14.6061" stroke="#1A124E" strokeLinecap="round" strokeLinejoin="round"/>
    <path d="M20.4316 14.2275H23.4357C23.7547 14.2275 24.0111 14.4499 24.0111 14.7264V16.32" stroke="#1A124E" strokeLinecap="round" strokeLinejoin="round"/>
    <path d="M21.8018 13.3828V19.3828" stroke="#1A124E" strokeLinecap="round" strokeLinejoin="round"/>
    <path d="M17.733 14.2231V19.0653M15.7109 19.0653H19.475M16.3443 14.2231H19.015" stroke="#1A124E" strokeLinecap="round" strokeLinejoin="round"/>
    <path d="M11.2383 15.0942C11.2383 16.2254 11.3783 17.6972 11.7255 18.2543" stroke="#1A124E" strokeLinecap="round" strokeLinejoin="round"/>
    <path d="M15.122 15.398H14.1733C13.4487 15.398 13.2995 15.4656 13.5979 14.8987L14.3841 13.4033" stroke="#1A124E" strokeLinecap="round" strokeLinejoin="round"/>
    <path d="M19.9141 16.4741H25.0484C25.3674 16.4741 25.6243 16.6964 25.6243 16.973V18.5666C25.6245 18.632 25.6097 18.6968 25.581 18.7573C25.5523 18.8177 25.5101 18.8727 25.4568 18.919C25.4036 18.9653 25.3403 19.0021 25.2707 19.0272C25.2011 19.0524 25.1264 19.0654 25.051 19.0655H24.1171" stroke="#1A124E" strokeLinecap="round" strokeLinejoin="round"/>
  </svg>
);

export function SiteFooter() {
  const t = useT();
  const { careersHidden } = useSiteFlags();

  return (
    <div className="bg-blue pt-20 pb-20">
      <div className="mw-1024 mx-auto">
        {/* Desktop layout */}
        <div className="d-none d-md-flex">
          {/* Products column */}
          <div className="col-3">
            <span className="footer-title">{t("products")}</span>
            <div className="mt-5">
              <span className="footer-sub-title d-block pt-5">
                <Link href="/explore?tab=university" style={{ color: "white" }}>{t("uni")}</Link>
              </span>
              <span className="footer-sub-title d-block pt-5">
                <Link href="/explore?tab=scholarship" style={{ color: "white" }}>{t("scholarships")}</Link>
              </span>
              <span className="footer-sub-title d-block pt-5">
                <Link href="/explore?tab=course" style={{ color: "white" }}>{t("courses")}</Link>
              </span>
              <span className="footer-sub-title d-block pt-5">
                <Link href="/compare-course" style={{ color: "white" }}>{t("comparisonTool")}</Link>
              </span>
              <span className="footer-sub-title d-block pt-5" id="pathway" style={{ cursor: "pointer" }}>
                {t("pathway360")}
              </span>
            </div>
          </div>

          {/* About us column */}
          <div className="col-2">
            <span className="footer-title">{t("aboutUs")}</span>
            <div className="mt-5">
              {!careersHidden ? (
                <span className="footer-sub-title d-block pt-5">
                  <Link href="/careers" style={{ color: "white" }}>{t("careers")}</Link>
                </span>
              ) : null}
              <span className="footer-sub-title d-block pt-5">
                <Link href="/contact-us" style={{ color: "white" }}>{t("contactUs")}</Link>
              </span>
              <span className="footer-sub-title d-block pt-5">
                <Link href="/blog" style={{ color: "white" }}>{t("blog")}</Link>
              </span>
            </div>
          </div>

          {/* Support column */}
          <div className="col-2">
            <span className="footer-title">{t("support")}</span>
            <div className="mt-5">
              <span className="footer-sub-title d-block pt-5">
                <Link href="/terms" style={{ color: "white" }}>{t("terms")}</Link>
              </span>
              <span className="footer-sub-title d-block pt-5">
                <Link href="/privacy" style={{ color: "white" }}>{t("privacy")}</Link>
              </span>
            </div>
          </div>

          {/* Logo + social + copyright */}
          <div className="col-5 d-flex flex-column align-items-end justify-content-between">
            <EduLogo />
            <div className="d-flex flex-column align-items-end">
              <div className="d-flex mb-3">
                <Link className="me-5" href="https://www.facebook.com/edu4Uworld/">
                  <FacebookIcon />
                </Link>
                <Link className="me-5" href="https://www.instagram.com/edu4u_/">
                  <InstagramIcon />
                </Link>
                <Link href="https://www.xiaohongshu.com/user/profile/644e67d8000000002a00bf55">
                  <XhsIcon />
                </Link>
              </div>
              <div style={{ color: "#FFFFFF", opacity: 0.5, fontSize: 13, textAlign: "right", lineHeight: 1.4 }}>
                Copyright &copy; 2024 EDU4U SDN. BHD.
                <br />
                202301019089 (1513011-U)
              </div>
            </div>
          </div>
        </div>

        {/* Mobile layout */}
        <div className="d-md-none col-12 row">
          <div className="col-6 ps-10">
            <span className="footer-title">{t("products")}</span>
            <div className="mb-15">
              <span className="footer-sub-title d-block pt-3"><Link href="/explore" style={{ color: "white" }}>{t("uni")}</Link></span>
              <span className="footer-sub-title d-block pt-3"><Link href="/explore" style={{ color: "white" }}>{t("scholarships")}</Link></span>
              <span className="footer-sub-title d-block pt-3"><Link href="/explore" style={{ color: "white" }}>{t("courses")}</Link></span>
              <span className="footer-sub-title d-block pt-3"><Link href="/compare-course" style={{ color: "white" }}>{t("comparisonTool")}</Link></span>
              <span className="footer-sub-title d-block pt-3" style={{ cursor: "pointer" }}>{t("pathway360")}</span>
            </div>
            <span className="footer-title mt-20">{t("aboutUs")}</span>
            <div className="mb-15">
              {!careersHidden ? (
                <span className="footer-sub-title d-block pt-3"><Link href="/careers" style={{ color: "white" }}>{t("careers")}</Link></span>
              ) : null}
              <span className="footer-sub-title d-block pt-3"><Link href="/contact-us" style={{ color: "white" }}>{t("contactUs")}</Link></span>
              <span className="footer-sub-title d-block pt-3"><Link href="/blog" style={{ color: "white" }}>{t("blog")}</Link></span>
            </div>
            <span className="footer-title">{t("support")}</span>
            <div className="mb-15">
              <span className="footer-sub-title d-block pt-3"><Link href="/terms" style={{ color: "white" }}>{t("terms")}</Link></span>
              <span className="footer-sub-title d-block pt-3"><Link href="/privacy" style={{ color: "white" }}>{t("privacy")}</Link></span>
            </div>
          </div>
          <div className="col-6">
            <div className="d-flex justify-content-end">
              <EduLogo />
            </div>
          </div>
        </div>

        {/* Mobile social + copyright */}
        <div className="d-md-none col-12 d-flex justify-content-between mt-10">
          <div>
            <Link href="https://www.facebook.com/edu4Uworld/">
              <svg className="ms-7 me-5" xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32" fill="none">
                <path d="M29.3337 15.9998C29.3337 8.63984 23.3603 2.6665 16.0003 2.6665C8.64033 2.6665 2.66699 8.63984 2.66699 15.9998C2.66699 22.4532 7.25366 27.8265 13.3337 29.0665V19.9998H10.667V15.9998H13.3337V12.6665C13.3337 10.0932 15.427 7.99984 18.0003 7.99984H21.3337V11.9998H18.667C17.9337 11.9998 17.3337 12.5998 17.3337 13.3332V15.9998H21.3337V19.9998H17.3337V29.2665C24.067 28.5998 29.3337 22.9198 29.3337 15.9998Z" fill="white"/>
              </svg>
            </Link>
            <Link href="https://www.instagram.com/edu4u_/">
              <InstagramIcon />
            </Link>
          </div>
          <div className="float-end me-7" style={{ fontSize: 14, color: "#FFFFFF", opacity: 0.5, alignContent: "center" }}>
            &copy; 2024 EDU4U SDN. BHD.
            <div>202301019089 (1513011-U)</div>
          </div>
        </div>
      </div>
    </div>
  );
}
