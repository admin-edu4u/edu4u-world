import { notFound } from "next/navigation";
import Link from "next/link";
import { createPublicClient } from "@/lib/supabase/server";
import { CDN } from "@/lib/cdn";
import { getLocale } from "@/lib/i18n/locale";
import { pickLocalizedFields } from "@/lib/i18n/cms";
import { CMS_I18N_FIELDS } from "@/lib/admin/cmsFields";
import { formatMoney } from "@/lib/money";
import { getDictionary, translate } from "@/lib/i18n/dictionaries";

type Params = { url: string };

const levelMap: Record<number, string> = {
  1: "Foundation / Pre-U",
  2: "Diploma",
  3: "Advanced Diploma",
  4: "Degree",
  5: "Masters",
  6: "PhD",
  7: "Professional",
};

export default async function CourseDetailsPage({ params }: { params: Promise<Params> }) {
  const { url } = await params;
  const supabase = createPublicClient();
  const locale = await getLocale();
  const dict = await getDictionary(locale);
  const t = (key: string) => translate(dict, key);

  // Fetch course with university info via join
  const { data: rawCourse } = await supabase
    .from("courses")
    .select("*")
    .eq("c_url_code", url)
    .eq("c_status", 1)
    .single();

  if (!rawCourse) notFound();

  const course = pickLocalizedFields(rawCourse, CMS_I18N_FIELDS.course, locale);

  // Fetch university info
  const { data: rawUni } = await supabase
    .from("universities")
    .select("u_id,u_url_code,u_name,u_logo,u_desc,u_addr,i18n")
    .eq("u_id", course.c_uni)
    .single();

  const uni = rawUni ? pickLocalizedFields(rawUni, CMS_I18N_FIELDS.university, locale) : null;

  // Fees
  const { data: fees } = await supabase
    .from("course_fees")
    .select("*")
    .eq("cf_c_id", course.c_id)
    .eq("cf_status", 1);

  const intakeStr = course.c_intake || "";
  const year = new Date().getFullYear();
  const nextYear = year + 1;

  return (
    <div className="pb-20">
      <style>{`
        .image-container {
          background-color: #f8f8f8;
          top: 0; left: 0;
          min-height: 460px;
          z-index: 0;
        }
        .course-outer-div { padding: 20px 0; }
        .uni-logo { width: 80px; height: 80px; object-fit: contain; border-radius: 8px; border: 1px solid #E4E4E4; }
        .course-details-div {
          border: 1px solid #E4E4E4;
          border-radius: 12px;
          padding: 16px;
          height: 100%;
        }
        .course-details-course-intake { font-size: 16px; font-weight: 600; color: #1F1F1F; }
        .course-details-course-intake-desc { font-size: 12px; color: #999; }
        .course-details-desc { font-size: 14px; color: #666; }
        .course-details-uni-name { font-size: 15px; color: #666; }
        .programme-outline { margin-top: 50px; }
        .details-div-border-right { border-right: 1px solid #E4E4E4; }
        .criteria { font-size: 20px; font-weight: 700; color: #1F1F1F; }
        .options-toggle { font-size: 14px; font-weight: 500; color: #1F1F1F; border-bottom: 2px solid transparent; border-radius: 0; padding-bottom: 8px; }
        .options-toggle.active { border-bottom-color: #1A124E; color: #1A124E; }
        .do-next-btn-1 { border: 2px solid #1A124E; border-radius: 8px; padding: 12px 24px; background: #1A124E; color: white; font-weight: 600; font-size: 15px; text-decoration: none; display: inline-block; }
        .do-next-btn-1:hover { color: #1A124E; background-color: white; }
        .do-next-btn-2 { border: 2px solid white; border-radius: 8px; padding: 12px 24px; background: #666; color: white; font-weight: 600; font-size: 15px; text-decoration: none; display: inline-block; }
        .do-next-btn-2:hover { color: white; background-color: #1A124E; }
        @media (max-width: 768px) { .details-div-border-right { border-right: 0; } }
      `}</style>

      <div>
        <div className="image-container">
          <div className="mw-1200 mx-auto" style={{ paddingTop: 86 }}>
            <div className="col-12 mx-auto course-outer-div row align-items-start">
              <div className="col-2 col-lg-2">
                {uni?.u_logo && (
                  <img
                    src={`${CDN}/images/university/${uni.u_logo}`}
                    className="uni-logo"
                    alt={uni?.u_name}
                  />
                )}
              </div>
              <div className="col-10 col-lg-10 mt-2">
                <div className="ms-3 ms-lg-5">
                  <div className="d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <span className="d-block visit-campus-text text-start">{course.c_name}</span>
                  </div>
                  {uni && (
                    <Link href={`/university/${uni.u_url_code || uni.u_id}`} className="d-block course-details-uni-name text-start pt-3" style={{ color: "#666" }}>
                      {uni.u_name}
                    </Link>
                  )}

                  {/* Info cards */}
                  <div className="col-12 row mt-10">
                    {/* Intake */}
                    <div className="col-6 col-lg-3 px-1 mb-3">
                      <div className="course-details-div">
                        <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 36 36" fill="none">
                          <path d="M3 18C3 12.3435 3 9.5145 4.758 7.758C6.5145 6 9.3435 6 15 6H21C26.6565 6 29.4855 6 31.242 7.758C33 9.5145 33 12.3435 33 18V21C33 26.6565 33 29.4855 31.242 31.242C29.4855 33 26.6565 33 21 33H15C9.3435 33 6.5145 33 4.758 31.242C3 29.4855 3 26.6565 3 21V18Z" stroke="#1F1F1F" strokeWidth="2" />
                          <path d="M10.5 6V3.75M25.5 6V3.75M3.75 13.5H32.25" stroke="#1F1F1F" strokeWidth="2" strokeLinecap="round" />
                        </svg>
                        <span className="course-details-course-intake d-block pt-2">{intakeStr}</span>
                        <span className="course-details-course-intake-desc pt-1 d-block">{t("intakeFor")}{year} / {nextYear}</span>
                      </div>
                    </div>

                    {/* Duration */}
                    <div className="col-6 col-lg-3 px-1 mb-3">
                      <div className="course-details-div">
                        <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 36 36" fill="none">
                          <path d="M4.5 18C4.5 25.456 10.544 31.5 18 31.5C25.456 31.5 31.5 25.456 31.5 18C31.5 10.544 25.456 4.5 18 4.5C10.544 4.5 4.5 10.544 4.5 18Z" stroke="#1F1F1F" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" />
                          <path d="M18 10.5V18L22.5 22.5" stroke="#1F1F1F" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" />
                        </svg>
                        <span className="course-details-course-intake d-block pt-2">{course.c_duration} {course.c_duration === 1 ? t("yearSingular") : t("years")}</span>
                        <span className="course-details-course-intake-desc pt-1 d-block">{t("durationOfStudies")}</span>
                      </div>
                    </div>

                    {/* Award */}
                    <div className="col-6 col-lg-3 px-1 mb-3">
                      <div className="course-details-div">
                        <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 36 36" fill="none">
                          <path d="M18 24C23.799 24 28.5 19.299 28.5 13.5C28.5 7.701 23.799 3 18 3C12.201 3 7.5 7.701 7.5 13.5C7.5 19.299 12.201 24 18 24Z" stroke="#1F1F1F" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" />
                          <path d="M18 18C20.485 18 22.5 15.985 22.5 13.5C22.5 11.015 20.485 9 18 9C15.515 9 13.5 11.015 13.5 13.5C13.5 15.985 15.515 18 18 18Z" fill="#1F1F1F" />
                          <path d="M10.5 21V30.351L18 28.75L25.5 30.351V21" stroke="#1F1F1F" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" />
                        </svg>
                        <span className="course-details-course-intake d-block pt-2">{course.c_award || "-"}</span>
                        <span className="course-details-course-intake-desc pt-1 d-block">{t("award")}</span>
                      </div>
                    </div>

                    {/* Internship */}
                    <div className="col-6 col-lg-3 px-1 mb-3">
                      <div className="course-details-div">
                        <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 36 36" fill="none">
                          <path d="M12 10.5V7.5C12 6.704 12.316 5.941 12.879 5.379C13.441 4.816 14.204 4.5 15 4.5H21C21.796 4.5 22.559 4.816 23.121 5.379C23.684 5.941 24 6.704 24 7.5V10.5M18 18V18.015M4.5 13.5C4.5 12.704 4.816 11.941 5.379 11.379C5.941 10.816 6.704 10.5 7.5 10.5H28.5C29.296 10.5 30.059 10.816 30.621 11.379C31.184 11.941 31.5 12.704 31.5 13.5V27C31.5 27.796 31.184 28.559 30.621 29.121C30.059 29.684 29.296 30 28.5 30H7.5C6.704 30 5.941 29.684 5.379 29.121C4.816 28.559 4.5 27.796 4.5 27V13.5Z" stroke="black" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" />
                          <path d="M4.5 19.5C8.687 21.61 13.311 22.709 18 22.709C22.689 22.709 27.313 21.61 31.5 19.5" stroke="black" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" />
                        </svg>
                        <span className="course-details-course-intake d-block pt-2">
                          {course.c_has_internship
                            ? `${course.c_internship_duration || ""} ${t("monthsWord")}`
                            : t("noInternship")}
                        </span>
                        <span className="course-details-course-intake-desc pt-1 d-block">{t("internship")}</span>
                      </div>
                    </div>
                  </div>

                  {uni?.u_desc && (
                    <span className="course-details-desc d-block pt-10">{uni.u_desc}</span>
                  )}
                </div>
              </div>
            </div>
          </div>
        </div>

        {/* Programme outline + sidebar */}
        <div className="programme-outline mw-1200 mx-auto px-5 px-lg-0">
          <div className="col-12 row m-0">
            <div className="col-12 col-lg-8 details-div-border-right pe-lg-10">
              {/* Outline */}
              <div>
                <span className="criteria d-block">{t("programmeOutline")}</span>
                <div
                  className="mt-5"
                  dangerouslySetInnerHTML={{ __html: course.c_outline || "" }}
                />
              </div>

              {/* Core / Elective / Others tabs */}
              {(course.c_core || course.c_elective || course.c_other) && (
                <div className="mt-15">
                  <ul className="nav nav-pills mt-4 gap-5" id="pills-tab" role="tablist">
                    <li className="nav-item" role="presentation">
                      <button className="p-0 me-5 nav-link options-toggle active" data-bs-toggle="pill" data-bs-target="#pills-core" type="button">{t("core")}</button>
                    </li>
                    <li className="nav-item" role="presentation">
                      <button className="p-0 me-5 nav-link options-toggle" data-bs-toggle="pill" data-bs-target="#pills-elective" type="button">{t("elective")}</button>
                    </li>
                    <li className="nav-item" role="presentation">
                      <button className="p-0 nav-link options-toggle" data-bs-toggle="pill" data-bs-target="#pills-others" type="button">{t("others")}</button>
                    </li>
                  </ul>
                  <div className="tab-content mt-5 mb-10" id="pills-tabContent">
                    <div className="tab-pane fade show active" id="pills-core" role="tabpanel">
                      <div className="col-12 mb-5" dangerouslySetInnerHTML={{ __html: course.c_core || "" }} />
                    </div>
                    <div className="tab-pane fade" id="pills-elective" role="tabpanel">
                      <div className="col-12 mb-5" dangerouslySetInnerHTML={{ __html: course.c_elective || "" }} />
                    </div>
                    <div className="tab-pane fade" id="pills-others" role="tabpanel">
                      <div className="col-12 mb-5" dangerouslySetInnerHTML={{ __html: course.c_other || "" }} />
                    </div>
                  </div>
                </div>
              )}

              {/* Entry requirements */}
              {course.c_entry_req && (
                <div className="mt-15">
                  <span className="criteria d-block">{t("entryRequirements")}</span>
                  <div className="mt-5" dangerouslySetInnerHTML={{ __html: course.c_entry_req }} />
                </div>
              )}

              {/* Level */}
              <div className="mt-10">
                <span className="uni-location">{t("level")}: {levelMap[course.c_level] || course.c_level}</span>
              </div>
            </div>

            {/* Sidebar */}
            <div className="col-12 col-lg-4 ps-lg-10 mt-10 mt-lg-0">
              {/* Fees */}
              <div className="course-details-div mb-8">
                <span className="criteria d-block mb-5" style={{ fontSize: 16 }}>{t("fees")}</span>
                {fees && fees.length > 0 ? (
                  fees.map((f) => (
                    <div key={f.cf_id} className="d-flex justify-content-between align-items-center mb-3">
                      <span className="uni-location">{f.cf_name}</span>
                      <span style={{ fontWeight: 600, color: "#1A124E" }}>{formatMoney(f.cf_amount)}</span>
                    </div>
                  ))
                ) : course.c_amount > 0 ? (
                  <div className="d-flex justify-content-between align-items-center">
                    <span className="uni-location">{t("totalFees")}</span>
                    <span style={{ fontWeight: 600, color: "#1A124E" }}>{formatMoney(course.c_amount)}</span>
                  </div>
                ) : (
                  <span className="uni-location">{t("contactForFees")}</span>
                )}
              </div>

              {/* CTAs */}
              <div className="d-flex flex-column gap-4">
                <Link href={`/course-application/${course.c_url_code || course.c_id}`} className="do-next-btn-1 text-center">
                  {t("applyNowUncap")}
                </Link>
                <Link href={`/consult-counsellor/${course.c_url_code || course.c_id}`} className="do-next-btn-2 text-center">
                  {t("consultOurCounsellor")}
                </Link>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}
