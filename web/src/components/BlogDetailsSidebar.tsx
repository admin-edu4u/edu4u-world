"use client";

import { CDN } from "@/lib/cdn";
import { BlogDetailsShare } from "@/components/BlogDetailsShare";
import { useT } from "@/components/LocaleProvider";

type Props = {
  pageUrl: string;
};

export function BlogDetailsSidebar({ pageUrl }: Props) {
  const t = useT();
  function openPathwayModal() {
    window.dispatchEvent(new CustomEvent("edu4u:open-modal", { detail: { kind: "pathway" } }));
  }

  return (
    <div className="blog-share col-3">
      <div className="blog-share-div text-center">
        <svg width="62" height="62" viewBox="0 0 62 62" fill="none" xmlns="http://www.w3.org/2000/svg">
          <circle cx="31" cy="31" r="30" fill="white" stroke="#E4E4E4" />
          <g clipPath="url(#clip0_209_1776)">
            <path d="M43.3356 41.2999C41.7487 38.4128 39.2707 36.1632 36.3006 34.9133C37.7776 33.765 38.8686 32.1641 39.4191 30.3374C39.9696 28.5106 39.9517 26.5506 39.3678 24.735C38.784 22.9194 37.6639 21.3402 36.1661 20.2212C34.6683 19.1022 32.8688 18.5 31.0226 18.5C29.1763 18.5 27.3768 19.1022 25.879 20.2212C24.3813 21.3402 23.2611 22.9194 22.6773 24.735C22.0934 26.5506 22.0755 28.5106 22.626 30.3374C23.1765 32.1641 24.2675 33.765 25.7445 34.9133C22.7744 36.1632 20.2964 38.4128 18.7095 41.2999C18.6112 41.4638 18.5456 41.6465 18.5168 41.8372C18.4879 42.0278 18.4964 42.2226 18.5416 42.4098C18.5868 42.5971 18.6678 42.773 18.7799 42.9271C18.892 43.0812 19.0329 43.2103 19.1941 43.3069C19.3553 43.4034 19.5336 43.4654 19.7184 43.4891C19.9032 43.5128 20.0907 43.4977 20.2698 43.4448C20.4488 43.3919 20.6158 43.3022 20.7607 43.1811C20.9057 43.0599 21.0257 42.9098 21.1135 42.7396C23.2109 38.9819 26.9147 36.7406 31.0226 36.7406C35.1304 36.7406 38.8343 38.9831 40.9316 42.7396C41.122 43.057 41.424 43.2852 41.7738 43.376C42.1236 43.4667 42.4937 43.4129 42.8059 43.2259C43.1181 43.039 43.3479 42.7335 43.4468 42.374C43.5456 42.0146 43.5058 41.6294 43.3356 41.2999ZM25.0038 27.6221C25.0038 26.3882 25.3568 25.1819 26.0181 24.1559C26.6795 23.1299 27.6195 22.3303 28.7193 21.858C29.8191 21.3858 31.0292 21.2623 32.1968 21.503C33.3643 21.7437 34.4367 22.3379 35.2785 23.2105C36.1202 24.083 36.6935 25.1947 36.9257 26.4049C37.1579 27.6152 37.0387 28.8696 36.5832 30.0097C36.1277 31.1497 35.3562 32.1241 34.3664 32.8096C33.3766 33.4952 32.213 33.8611 31.0226 33.8611C29.4268 33.8592 27.897 33.2013 26.7687 32.0316C25.6403 30.862 25.0056 29.2762 25.0038 27.6221Z" fill="#1F1F1F" />
          </g>
          <defs>
            <clipPath id="clip0_209_1776">
              <rect width="25" height="25" fill="white" transform="translate(18.5 18.5)" />
            </clipPath>
          </defs>
        </svg>
        <span className="d-block pt-5">{t("blogDesc")}</span>
        <button className="mt-7" onClick={openPathwayModal}>
          {t("registerNow")}
        </button>
      </div>
      <span className="share-post-text d-block pt-15">{t("sharePost")}</span>
      <div className="d-flex mt-5">
        <a href={`https://twitter.com/intent/tweet?url=${encodeURIComponent(pageUrl)}`} target="_blank" rel="noopener noreferrer">
          <svg className="me-10" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
            <path d="M22.4591 6C21.6891 6.35 20.8591 6.58 19.9991 6.69C20.8791 6.16 21.5591 5.32 21.8791 4.31C21.0491 4.81 20.1291 5.16 19.1591 5.36C18.3691 4.5 17.2591 4 15.9991 4C13.6491 4 11.7291 5.92 11.7291 8.29C11.7291 8.63 11.7691 8.96 11.8391 9.27C8.27906 9.09 5.10906 7.38 2.99906 4.79C2.62906 5.42 2.41906 6.16 2.41906 6.94C2.41906 8.43 3.16906 9.75 4.32906 10.5C3.61906 10.5 2.95906 10.3 2.37906 10V10.03C2.37906 12.11 3.85906 13.85 5.81906 14.24C5.18979 14.4122 4.52916 14.4362 3.88906 14.31C4.16067 15.1625 4.6926 15.9084 5.41008 16.4429C6.12756 16.9775 6.99451 17.2737 7.88906 17.29C6.37269 18.4904 4.49306 19.1393 2.55906 19.13C2.21906 19.13 1.87906 19.11 1.53906 19.07C3.43906 20.29 5.69906 21 8.11906 21C15.9991 21 20.3291 14.46 20.3291 8.79C20.3291 8.6 20.3291 8.42 20.3191 8.23C21.1591 7.63 21.8791 6.87 22.4591 6Z" fill="#999999" />
          </svg>
        </a>
        <a href={`https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(pageUrl)}&display=popup`} target="_blank" rel="noopener noreferrer">
          <svg className="me-10" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
            <path d="M22 12C22 6.48 17.52 2 12 2C6.48 2 2 6.48 2 12C2 16.84 5.44 20.87 10 21.8V15H8V12H10V9.5C10 7.57 11.57 6 13.5 6H16V9H14C13.45 9 13 9.45 13 10V12H16V15H13V21.95C18.05 21.45 22 17.19 22 12Z" fill="#999999" />
          </svg>
        </a>
        <BlogDetailsShare />
      </div>
    </div>
  );
}

export function BlogDetailsPathwayCTA({ imgSrc }: { imgSrc: string }) {
  const t = useT();
  function openPathwayModal() {
    window.dispatchEvent(new CustomEvent("edu4u:open-modal", { detail: { kind: "pathway" } }));
  }

  return (
    <div className="mt-20">
      <div className="mw-1024 mx-auto">
        <div className="col-12 m-0 row pt-20 pb-lg-20 align-items-center">
          <div className="col-lg-7 col-12 order-2 order-lg-1 mt-lg-0 mt-20">
            <span className="d-block text-center text-lg-start home-title-3 mb-3">
              {t("blogDesc")}
            </span>
            <a className="home-btn-get-started-2" style={{ cursor: "pointer" }} onClick={openPathwayModal}>
              {t("registerNow")}
            </a>
          </div>
          <div className="col-lg-5 col-12 d-flex justify-lg-content-end justify-content-center order-1 order-lg-2">
            <img src={imgSrc} alt="Register" />
          </div>
        </div>
      </div>
    </div>
  );
}
