import { Suspense } from "react";
import Link from "next/link";
import { createPublicClient } from "@/lib/supabase/server";
import { SiteHeader } from "@/components/SiteHeader";
import { HomeEffects } from "@/components/HomeEffects";
import { HomePopups } from "@/components/HomePopups";
import { STATUS } from "@/lib/admin/status";
import { mediaUrl } from "@/lib/admin/media";
import { getDictionary, translate } from "@/lib/i18n/dictionaries";
import { getLocale } from "@/lib/i18n/locale";
import { pickLocalizedFields } from "@/lib/i18n/cms";
import { CMS_I18N_FIELDS } from "@/lib/admin/cmsFields";
import { getSiteSettings } from "@/lib/siteSettings";

const CDN = "https://edu4u.world";

const MARQUEE_KEYS = [
  { img: `${CDN}/images/assets/cat-1.png`, key: "homeMarquee1" },
  { img: `${CDN}/images/assets/cat-2.png`, key: "homeMarquee2" },
  { img: `${CDN}/images/assets/cat-3.png`, key: "homeMarquee3" },
  { img: `${CDN}/images/assets/cat-4.png`, key: "homeMarquee4" },
  { img: `${CDN}/images/assets/cat-5.png`, key: "homeMarquee5" },
  { img: `${CDN}/images/assets/cat-6.png`, key: "homeMarquee6" },
  { img: `${CDN}/images/assets/cat-7.png`, key: "homeMarquee7" },
  { img: `${CDN}/images/assets/cat-8.png`, key: "homeMarquee8" },
  { img: `${CDN}/images/assets/cat-9.png`, key: "homeMarquee9" },
  { img: `${CDN}/images/assets/cat-10.png`, key: "homeMarquee10" },
  { img: `${CDN}/images/assets/cat-11.png`, key: "homeMarquee11" },
  { img: `${CDN}/images/assets/cat-12.png`, key: "homeMarquee12" },
  { img: `${CDN}/images/assets/cat-13.png`, key: "homeMarquee13" },
] as const;

const TESTIMONIALS = [
  {
    img: `${CDN}/images/assets/review-img-1.png`,
    name: "Tan Wei Jie",
    field: "Information Technology",
    quote:
      "Edu4U's Information Technology listings are comprehensive. The platform's simplicity and quick application process made my academic journey hassle-free.",
  },
  {
    img: `${CDN}/images/assets/review-img-2.png`,
    name: "Nurul Hidayah",
    field: "Hospitality Management",
    quote:
      "Edu4U's extensive courses and scholarships in Hospitality Management made my choice clear. The straightforward application process was the cherry on top.",
  },
  {
    img: `${CDN}/images/assets/review-img-3.png`,
    name: "Kavi Sharma",
    field: "Marketing",
    quote:
      "Edu4U's Marketing course options are exceptional. The platform's seamless navigation and quick application process made my educational journey enjoyable.",
  },
  {
    img: `${CDN}/images/assets/review-img-4.png`,
    name: "Aisha Tan",
    field: "Business Administration",
    quote:
      "Edu4U guided me to the perfect Business Administration course in Malaysia. The platform's simplicity made my application process stress-free.",
  },
  {
    img: `${CDN}/images/assets/review-img-5.png`,
    name: "Raj Kumar",
    field: "Computer Science",
    quote:
      "Discovering Edu4U was a game-changer for my Computer Science journey. The diverse course options and easy application process made it an ideal choice.",
  },
  {
    img: `${CDN}/images/assets/review-img-6.png`,
    name: "Mei Ling Wong",
    field: "Environmental Science",
    quote:
      "Edu4U's user-friendly interface and extensive scholarships helped me find the ideal Environmental Science program in Malaysia. Highly recommended!",
  },
  {
    img: `${CDN}/images/assets/review-img-7.png`,
    name: "Amirul bin Hassan",
    field: "Mechanical Engineering",
    quote:
      "Edu4U streamlined my search for Mechanical Engineering courses. The platform's simplicity and wealth of information made my decision-making easy.",
  },
  {
    img: `${CDN}/images/assets/review-img-8.png`,
    name: "Priya Patel",
    field: "Psychology",
    quote:
      "Edu4U's platform made my Psychology course search seamless. The diverse scholarship opportunities and straightforward application process were a lifesaver.",
  },
  {
    img: `${CDN}/images/assets/review-img-9.png`,
    name: "Ahmad Ibrahim",
    field: "Architecture",
    quote:
      "Edu4U's architecture course listings are unparalleled. The ease of finding, applying, and securing scholarships made it my go-to platform for higher education.",
  },
  {
    img: `${CDN}/images/assets/review-img.png`,
    name: "Sara Abdullah",
    field: "Biotechnology",
    quote:
      "Edu4U's commitment to students shines through. I found the perfect Biotechnology course with ease, thanks to the platform's intuitive design.",
  },
];

const ArrowRight = () => (
  <svg xmlns="http://www.w3.org/2000/svg" width="40" height="23" viewBox="0 0 40 23" fill="none">
    <path d="M2 10.2686C1.17157 10.2686 0.5 10.9401 0.5 11.7686C0.5 12.597 1.17157 13.2686 2 13.2686V10.2686ZM39.0607 12.8292C39.6465 12.2434 39.6465 11.2937 39.0607 10.7079L29.5147 1.16195C28.9289 0.576166 27.9792 0.576166 27.3934 1.16195C26.8076 1.74774 26.8076 2.69749 27.3934 3.28327L35.8787 11.7686L27.3934 20.2538C26.8076 20.8396 26.8076 21.7894 27.3934 22.3752C27.9792 22.9609 28.9289 22.9609 29.5147 22.3752L39.0607 12.8292ZM2 13.2686H38V10.2686H2V13.2686Z" fill="#CCCCCC"/>
  </svg>
);

function blogExcerpt(html: string | null | undefined) {
  if (!html) return "";
  return html
    .replace(/<[^>]+>/g, " ")
    .replace(/&nbsp;/g, " ")
    .replace(/&amp;/g, "&")
    .replace(/&quot;/g, '"')
    .replace(/&#39;/g, "'")
    .replace(/\s+/g, " ")
    .trim();
}

async function HomeContent() {
  const locale = await getLocale();
  const dict = await getDictionary(locale);
  const t = (key: string) => translate(dict, key);

  const supabase = createPublicClient();
  const [{ data: home }, { data: blogs }, { data: popups }, settings] = await Promise.all([
    supabase.from("home").select("*").eq("h_id", 1).maybeSingle(),
    supabase
      .from("blogs")
      .select("b_id,b_url_code,b_title,b_img,b_content,i18n")
      .order("created_at", { ascending: false })
      .limit(3),
    supabase
      .from("pop_up")
      .select("pu_id,pu_img,pu_url,pu_sort")
      .eq("pu_status", STATUS.ACTIVE)
      .order("pu_sort", { ascending: true })
      .order("pu_id", { ascending: true }),
    getSiteSettings(),
  ]);

  const heroFiles = (home?.h_img || "")
    .split(",")
    .map((s: string) => s.trim())
    .filter(Boolean);
  const heroImages = heroFiles.length
    ? heroFiles.map((f: string) => `${CDN}/images/home/${f}`)
    : [`${CDN}/images/assets/home-1.png`];
  const heroSrc = heroImages[0];
  const popupItems = (popups || [])
    .filter((p) => p.pu_img && p.pu_url)
    .map((p) => ({
      id: Number(p.pu_id),
      img: mediaUrl("home", p.pu_img),
      url: String(p.pu_url),
    }));

  return (
    <>
      <HomeEffects images={heroImages} />
      <HomePopups items={popupItems} />
      {/* ===== Hero section ===== */}
      <div className="position-relative">
        <div className="image-container">
          {/* eslint-disable-next-line @next/next/no-img-element */}
          <img
            id="rotatingImage"
            className="w-100"
            style={{ height: 640, objectFit: "cover", transition: "opacity 1s" }}
            src={heroSrc}
            alt="Edu4u"
          />
          <div className="overlay" />
        </div>

        <div className="mw-1024 position-absolute" style={{ top: 150, left: 0, right: 0, marginLeft: "auto", marginRight: "auto", zIndex: 1 }}>
          <div className="d-flex align-items-center mb-10">
            <div className="col-6">
              <span className="home-title-1 d-block">{t("homeTitle1")}</span>
              <span className="home-title-2 d-block pt-6 pb-6">
                {t("homeSubTitle1")}
              </span>
              <span className="home-personality cursor" id="pathway" role="button" tabIndex={0}>
                {t("startPathway360")}
              </span>
            </div>
            <div className="col-6 d-flex justify-content-end">
              {/* eslint-disable-next-line @next/next/no-img-element */}
              <img src={`${CDN}/images/assets/ubie_sitting_down_with_arrow.png`} style={{ height: 307 }} alt="Ubie mascot" />
            </div>
          </div>
        </div>

        {/* Category marquee bar */}
        <div>
          <div className="marquee" id="mycrawler">
            <div className="d-flex">
              {MARQUEE_KEYS.map((item) => {
                const label = t(item.key);
                return (
                  <div className="d-flex ms-10" key={item.key}>
                    {/* eslint-disable-next-line @next/next/no-img-element */}
                    <img src={item.img} alt={label} />
                    <span className="home-cat-text ms-3">{label}</span>
                  </div>
                );
              })}
            </div>
          </div>
        </div>
      </div>

      {/* ===== Stats section ===== */}
      <div className="mw-1024 mx-auto">
        <div className="d-flex justify-content-between mt-20">
          <div>
            <span className="home-data-1 d-block text-center">100+</span>
            <span className="home-desc-1">{t("homeUniPartners")}</span>
          </div>
          <div>
            <span className="home-data-1 d-block text-center">1,260+</span>
            <span className="home-desc-1">{t("homeCourseAvail")}</span>
          </div>
          <div>
            <span className="home-data-1 d-block text-center">500,000+</span>
            <span className="home-desc-1">{t("homeHappyStudent")}</span>
          </div>
        </div>
      </div>

      {/* ===== "3 minutes" / AI Ubie CTA section ===== */}
      <div className="bg-grey mt-20">
        <div className="mw-1024 mx-auto">
          <div className="d-none d-lg-flex pt-20 pb-20 align-items-center">
            <div className="col-7">
              <span className="d-block home-title-3">
                {t("home3Minutes")}
              </span>
              <span className="d-block home-title-4 pt-7 pb-7">
                {t("homeSubTitle2")}
              </span>
              <span
                className="home-btn-get-started hovertype1 cursor"
                id="pathway"
                role="button"
                tabIndex={0}
              >
                {t("homeGetStarted")}
              </span>
            </div>
            <div className="col-5 ms-10 d-flex justify-content-end">
              {/* eslint-disable-next-line @next/next/no-img-element */}
              <img
                src="/images/assets/home-describe-demo.svg"
                alt="What would your friends describe you as?"
                width={404}
                height={259}
                style={{ maxWidth: "100%", height: "auto" }}
              />
            </div>
          </div>
          <div className="d-lg-none pt-10 pb-10 px-5">
            <span className="d-block home-title-3 text-center">
              {t("home3Minutes")}
            </span>
            <span className="d-block home-title-4 pt-7 pb-7 text-center">
              {t("homeSubTitle2")}
            </span>
            <div className="d-flex justify-content-center mb-8">
              {/* eslint-disable-next-line @next/next/no-img-element */}
              <img
                src="/images/assets/home-describe-demo.svg"
                alt="What would your friends describe you as?"
                width={404}
                height={259}
                style={{ maxWidth: "100%", height: "auto" }}
              />
            </div>
            <span
              className="col-11 mx-auto home-btn-get-started hovertype1 cursor"
              id="pathway"
              role="button"
              tabIndex={0}
            >
              {t("homeGetStarted")}
            </span>
          </div>
        </div>
      </div>

      {/* ===== Careers section ===== */}
      {!settings.careersHidden ? (
        <div className="mt-20">
          <div className="mw-1024 mx-auto">
            <div className="col-12 m-0 row pt-20 pb-lg-20 align-items-center">
              <div className="col-lg-7 col-12 order-2 order-lg-1 mt-lg-0 mt-20">
                <span className="d-block text-center text-lg-start home-title-3">
                  {t("homeCareer")}
                </span>
                <span className="d-block text-center text-lg-start home-title-4 pt-7 pb-7">
                  {t("homeSubTitle3")}
                </span>
                <Link
                  className="home-btn-get-started-2 btn-desktop-only hovertype2"
                  href="/careers"
                >
                  {t("homeBrowseCareer")}
                </Link>
                <Link
                  className="col-11 mx-auto home-btn-get-started-2 btn-mobile-only hovertype2"
                  href="/careers"
                >
                  {t("homeBrowseCareer")}
                </Link>
              </div>
              <div className="col-lg-5 col-12 d-flex justify-content-lg-end justify-content-center order-1 order-lg-2">
                {/* eslint-disable-next-line @next/next/no-img-element */}
                <img
                  src={`${CDN}/images/assets/girl_light_bulb.png`}
                  alt="Careers"
                  style={{ maxWidth: "100%", height: "auto" }}
                />
              </div>
            </div>
          </div>
        </div>
      ) : null}

      {/* ===== Pathway360 / personality section ===== */}
      <div className="bg-grey mt-20">
        <div className="mw-1024 mx-auto">
          <div className="col-12 m-0 row py-20 pt-lg-140 pb-lg-140 align-items-center">
            <div className="col-lg-5 col-12 d-flex align-items-center justify-content-center justify-content-lg-start">
              {/* eslint-disable-next-line @next/next/no-img-element */}
              <img
                src={`${CDN}/images/assets/ubie_lightbulb.png`}
                alt="Pathway360"
              />
            </div>
            <div className="col-lg-7 col-12 mt-lg-0 mt-20 d-flex flex-column justify-content-center">
              <span className="d-block text-center text-lg-start home-title-3">
                {t("homeTitle4")}
              </span>
              <span className="d-block text-center text-lg-start home-title-4 pt-7 pb-7">
                {t("homeSubTitle4")}
              </span>
              <span
                className="home-btn-get-started hovertype1 cursor align-self-center align-self-lg-start"
                id="pathway"
                role="button"
                tabIndex={0}
              >
                {t("startPathway360")}
              </span>
            </div>
          </div>
        </div>
      </div>

      {/* ===== Avatar / Top 3 professions section ===== */}
      <div className="mt-10 mt-lg-20 mb-20 mb-lg-0">
        <div className="mw-1024 mx-auto">
          <div className="d-flex flex-column align-items-center">
            <div className="d-flex justify-content-center mb-8 mb-lg-10">
              {/* eslint-disable-next-line @next/next/no-img-element */}
              <img
                src={`${CDN}/images/assets/girl_group_together.png`}
                alt="AI Ubie avatars"
                style={{ width: 466, maxWidth: "100%" }}
              />
            </div>
            <span className="d-block home-title-3 text-center ms-10 me-10 ms-lg-10 me-lg-0">
              {t("homeTitle5")}
            </span>
            <span className="d-block home-title-4 text-center pt-7 pb-7 ms-10 me-10 ms-lg-10 me-lg-0">
              {t("homeSubTitle5")}
            </span>
            <div className="text-center mt-7">
              <span
                className="home-btn-get-started-2 btn-desktop-only hovertype2 cursor"
                id="pathway"
                role="button"
                tabIndex={0}
              >
                {t("homeGetStarted")}
              </span>
              <span
                className="col-11 mx-auto home-btn-get-started-2 btn-mobile-only hovertype2 cursor"
                id="pathway"
                role="button"
                tabIndex={0}
              >
                {t("homeGetStarted")}
              </span>
            </div>
          </div>
        </div>
      </div>

      {/* ===== University + Scholarship cards (dark blue) ===== */}
      <div className="bg-blue mt-10 mt-lg-20">
        <div className="mw-1024 mx-auto pt-lg-140 pt-20 pb-lg-140 pb-20">
          <span className="home-title-1 d-block text-center">
            {t("homeTitle6")}
          </span>
          <div className="enrolment-cards row g-4 justify-content-center mt-10 mt-lg-20 mx-0">
            {/* Universities */}
            <div className="col-11 col-lg-6">
              <div className="enrolment-card">
                <div className="enrolment-card-media d-flex justify-content-center align-items-center">
                  {/* eslint-disable-next-line @next/next/no-img-element */}
                  <img src={`${CDN}/images/assets/uni-1-1.png`} className="me-8" style={{ height: 36 }} alt="Heriot-Watt University" />
                  {/* eslint-disable-next-line @next/next/no-img-element */}
                  <img src={`${CDN}/images/assets/uni-2-2.png`} className="me-8" style={{ height: 36 }} alt="HELP University" />
                  {/* eslint-disable-next-line @next/next/no-img-element */}
                  <img src={`${CDN}/images/assets/uni-3-2.png`} style={{ height: 36 }} alt="UCSI University" />
                </div>
                <div className="enrolment-card-body text-center px-5">
                  <span className="d-block text-white connect-uni">
                    {t("homeSubTitle6")}
                  </span>
                  <span className="d-block pt-5 connect-uni-sub">
                    {t("homeDesc6")}
                  </span>
                </div>
                <div className="enrolment-card-action text-center">
                  <Link className="home-btn-see-uni hovertype3" href="/explore">
                    {t("homeBtn6")}
                  </Link>
                </div>
              </div>
            </div>

            {/* Scholarships */}
            <div className="col-11 col-lg-6">
              <div className="enrolment-card">
                <div className="enrolment-card-media d-flex justify-content-center align-items-center">
                  {/* eslint-disable-next-line @next/next/no-img-element */}
                  <img
                    className="mass-comm-girl-img"
                    src={`${CDN}/images/assets/girl_mass_com_1.png`}
                    alt="Scholarships"
                  />
                </div>
                <div className="enrolment-card-body text-center px-5">
                  <span className="d-block text-white connect-uni">
                    {t("homeSubTitle7")}
                  </span>
                  <span className="d-block pt-5 connect-uni-sub">
                    {t("homeDesc7")}
                  </span>
                </div>
                <div className="enrolment-card-action text-center">
                  <Link
                    className="home-btn-see-uni hovertype3"
                    href="/explore/?tab=scholarship"
                  >
                    {t("homeBtn7")}
                  </Link>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      {/* ===== How it works / 3 steps ===== */}
      <div className="how-it-works mt-10 mt-lg-20 ps-2 pe-2">
        <div className="mw-1024 mx-auto">
          <span className="d-block home-title-3 text-center pt-10 px-lg-20">
            {t("homeTitle8")}
          </span>
          <div className="how-it-works-steps d-flex flex-column flex-lg-row align-items-center justify-content-between mt-10 mt-lg-20">
            <div className="steps text-center">
              <span className="home-guide-title d-block">{t("homeSubTitle8")}</span>
              <span className="home-guide-desc d-block pt-5">
                {t("homeDesc8")}
              </span>
            </div>
            <div className="steps-arrow d-flex justify-content-center my-8 my-lg-0">
              <ArrowRight />
            </div>
            <div className="steps text-center">
              <span className="home-guide-title d-block">{t("homeSubTitle9")}</span>
              <span className="home-guide-desc d-block pt-5">
                {t("homeDesc9")}
              </span>
            </div>
            <div className="steps-arrow d-flex justify-content-center my-8 my-lg-0">
              <ArrowRight />
            </div>
            <div className="steps text-center">
              <span className="home-guide-title d-block">{t("homeSubTitle10")}</span>
              <span className="home-guide-desc d-block pt-5">
                {t("homeDesc10")}
              </span>
            </div>
          </div>
        </div>
      </div>

      {/* ===== Dialog image full-width ===== */}
      <div className="mt-20 d-flex justify-content-center">
        {/* eslint-disable-next-line @next/next/no-img-element */}
        <img
          src={`${CDN}/images/assets/home-dialog-img-1.jpg`}
          className="w-100"
          alt="Edu4u app preview"
        />
      </div>

      {/* ===== Blog section ===== */}
      <div className="latest-blogs mt-20">
        <div className="mw-1024 mx-auto">
          <span className="home-blog d-block pt-20 pb-5">{t("latestBlogs")}</span>
          <div className="latest-blogs-grid">
            {(blogs && blogs.length > 0
              ? blogs.map((raw) => {
                  const post = pickLocalizedFields(raw, CMS_I18N_FIELDS.blog, locale);
                  return {
                  key: String(post.b_id),
                  href: `/blog-details/${post.b_url_code}`,
                  img: `${CDN}/images/blogs/${post.b_img}`,
                  title: post.b_title as string,
                  excerpt: blogExcerpt(post.b_content as string),
                };
                })
              : [
                  {
                    key: "1",
                    href: "/blog",
                    img: `${CDN}/images/assets/blog-1.png`,
                    title: "How to explore careers and choose a course",
                    excerpt:
                      "Find out what you should know before you choose your course, so that it is aligned with your interest, ability and career goals.",
                  },
                  {
                    key: "2",
                    href: "/blog",
                    img: `${CDN}/images/assets/blog-2.png`,
                    title: "Must knows before choosing a Pre-U, Diploma or Degree",
                    excerpt:
                      "Understand the differences between pathway options before you commit to your next study chapter.",
                  },
                  {
                    key: "3",
                    href: "/blog",
                    img: `${CDN}/images/assets/blog-3.png`,
                    title: "Fees, PTPTN and scholarships explained",
                    excerpt:
                      "A practical guide to funding your studies with fees planning, PTPTN and scholarship options.",
                  },
                ]
            ).map((post) => (
              <Link
                key={post.key}
                href={post.href}
                className="latest-blog-card cursor text-decoration-none"
              >
                {/* eslint-disable-next-line @next/next/no-img-element */}
                <img src={post.img} alt={post.title} />
                <span className="blog-title d-block pt-2">{post.title}</span>
                <span className="home-guide-desc d-block text-start pt-2">{post.excerpt}</span>
              </Link>
            ))}
          </div>
          <div className="text-center mt-15 pb-20">
            <Link className="home-btn-get-started-2 hovertype2" href="/blog">
              {t("seeAllBlogs")}
            </Link>
          </div>
        </div>
      </div>

      {/* ===== Review / testimonial marquee (mycrawlerReview) ===== */}
      <div className="mt-20 mb-20">
        <div className="marquee" id="mycrawlerReview">
          <div className="d-flex">
            {TESTIMONIALS.map((review) => (
              <div className="ms-5 home-sliding-div" key={review.name}>
                <div className="d-flex align-items-center">
                  <div>
                    {/* eslint-disable-next-line @next/next/no-img-element */}
                    <img
                      className="review-avatar"
                      src={review.img}
                      alt={review.name}
                      width={40}
                      height={40}
                    />
                  </div>
                  <div className="ms-4">
                    <span className="review-name d-block">{review.name}</span>
                    <span className="review-title d-block">{review.field}</span>
                  </div>
                </div>
                <div className="review-desc d-block pt-7">{review.quote}</div>
              </div>
            ))}
          </div>
        </div>
      </div>

      {/* ===== Start your education pathway CTA ===== */}
      <div className="pathway-cta mw-1024 mx-auto pt-lg-140">
        <div className="position-relative pathway-cta-inner">
          <div className="d-flex justify-content-center pathway-cta-arch">
            <svg
              className="d-none d-lg-block"
              width="778"
              height="294"
              viewBox="0 0 778 294"
              fill="none"
              xmlns="http://www.w3.org/2000/svg"
              aria-hidden
            >
              <path
                d="M777.5 293.1C758.805 227.654 723.69 167.382 674.69 118.398C598.92 42.653 496.155 0.0996094 389 0.0996094C281.845 0.0996094 179.08 42.653 103.31 118.398C54.3097 167.382 19.1954 227.654 0.5 293.1H777.5Z"
                fill="#707EF9"
                fillOpacity="0.1"
              />
              <path
                d="M736.5 293.1C719.778 234.578 688.369 180.684 644.54 136.882C576.766 69.1508 484.846 31.0996 389 31.0996C293.154 31.0996 201.234 69.1508 133.46 136.882C89.6309 180.684 58.2224 234.578 41.5 293.1H736.5Z"
                fill="#707EF9"
                fillOpacity="0.1"
              />
            </svg>
            <svg
              className="d-lg-none w-100"
              width="375"
              height="368"
              viewBox="0 0 375 368"
              fill="none"
              xmlns="http://www.w3.org/2000/svg"
              aria-hidden
            >
              <path
                d="M674.891 368C651.41 285.802 607.307 210.103 545.764 148.579C450.599 53.4459 321.529 0 186.945 0C52.3622 0 -76.7087 53.4459 -171.873 148.579C-233.417 210.103 -277.519 285.802 -301 368H674.891Z"
                fill="#707EF9"
                fillOpacity="0.1"
              />
              <path
                d="M623.395 368C602.392 294.499 562.944 226.808 507.896 171.794C422.774 86.7263 307.325 38.9351 186.945 38.9351C66.5646 38.9351 -48.8849 86.7263 -134.006 171.794C-189.055 226.808 -228.503 294.499 -249.506 368H623.395Z"
                fill="#707EF9"
                fillOpacity="0.1"
              />
            </svg>
          </div>
          <div className="pathway-absolute">
            <span className="start-pathway d-block ms-10 me-10 ms-lg-0 me-lg-0">
              {t("startEducationPathway")}
            </span>
            <div className="text-center mt-lg-10 mt-5 mb-5 mb-lg-0 px-5 px-lg-0">
              <span
                className="home-btn-get-started-2 hovertype2 cursor"
                id="pathway"
                role="button"
                tabIndex={0}
              >
                {t("homeGetStarted")}
              </span>
            </div>
            <div className="d-flex justify-content-center pathway-cta-character">
              {/* eslint-disable-next-line @next/next/no-img-element */}
              <img
                src={`${CDN}/images/logo/Edu4ugirlhs.png`}
                className="mt-20"
                style={{ width: 133, height: 141 }}
                alt=""
              />
            </div>
          </div>
        </div>
      </div>
    </>
  );
}

export default function HomePage() {
  return (
    <>
      {/* Dark photo hero: light chrome while transparent, solid white after scroll */}
      <SiteHeader overlayHero />
      <main>
        <Suspense
          fallback={
            <div className="mw-1024 mx-auto py-20 text-center text-muted">Loading Edu4u…</div>
          }
        >
          <HomeContent />
        </Suspense>
      </main>
    </>
  );
}
