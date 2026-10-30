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

export default async function ScholarshipPage({ params }: { params: Promise<Params> }) {
  const { url } = await params;
  const supabase = createPublicClient();
  const locale = await getLocale();
  const dict = await getDictionary(locale);
  const t = (key: string) => translate(dict, key);

  let { data: scholarship } = await supabase
    .from("scholarships")
    .select("*")
    .eq("s_url_code", url)
    .eq("s_status", 1)
    .maybeSingle();

  // Imported dump soft-deletes many scholarships as status 99; still show by slug.
  if (!scholarship) {
    const fallback = await supabase
      .from("scholarships")
      .select("*")
      .eq("s_url_code", url)
      .maybeSingle();
    scholarship = fallback.data;
  }

  if (!scholarship) notFound();

  scholarship = pickLocalizedFields(scholarship, CMS_I18N_FIELDS.scholarship, locale);

  // Fetch university
  const { data: rawUni } =
    scholarship.s_uni_id && scholarship.s_uni_id !== 0
      ? await supabase
          .from("universities")
          .select("u_id,u_url_code,u_name,u_logo,i18n")
          .eq("u_id", scholarship.s_uni_id)
          .single()
      : { data: null };

  const uni = rawUni ? pickLocalizedFields(rawUni, CMS_I18N_FIELDS.university, locale) : null;

  // Fetch discount tiers
  const { data: discounts } = await supabase
    .from("scholarship_discount")
    .select("*")
    .eq("sd_s_id", scholarship.s_id)
    .eq("sd_status", 1)
    .order("sd_level_id");

  // Max discount
  let maxDiscount = "";
  if (discounts && discounts.length > 0) {
    const maxPct = Math.max(...discounts.map((d) => d.sd_percentage || 0));
    const maxAmt = Math.max(...discounts.map((d) => d.sd_amt || 0));
    if (maxPct > 0) maxDiscount = `${maxPct}%`;
    else if (maxAmt > 0) maxDiscount = formatMoney(maxAmt);
  }

  const logo = scholarship.s_is_corporate
    ? scholarship.s_c_logo
      ? `${CDN}/images/corporate/${scholarship.s_c_logo}`
      : null
    : uni?.u_logo
    ? `${CDN}/images/university/${uni.u_logo}`
    : null;

  const entityName = scholarship.s_is_corporate
    ? scholarship.s_c_name
    : uni?.u_name || "";

  const levelIds: string[] = scholarship.s_level_id
    ? String(scholarship.s_level_id)
        .split(",")
        .map((s: string) => s.trim())
        .filter(Boolean)
    : [];

  const levelMap: Record<string, string> = {
    "1": "Foundation / Pre-U",
    "2": "Diploma",
    "3": "Advanced Diploma",
    "4": "Degree",
    "5": "Masters",
    "6": "PhD",
    "7": "Professional",
  };

  return (
    <div className="mw-1200 mx-auto pt-140 pb-20 px-5 px-lg-0">
      <style>{`
        .award-text { font-size: 14px; font-weight: 600; color: #1F1F1F; }
        .award-amt { font-size: 24px; font-weight: 700; color: #1A124E; }
        .submission-user-label { font-size: 13px; color: #999; }
        .course-details { font-size: 14px; color: #1F1F1F; }
        .criteria { font-size: 20px; font-weight: 700; color: #1F1F1F; }
        .table-header { font-size: 13px; font-weight: 600; color: #999; padding-bottom: 8px; border-bottom: 1px solid #E4E4E4; }
        .table-body { font-size: 14px; color: #1F1F1F; padding: 8px 0; border-bottom: 1px solid #E4E4E4; vertical-align: top; }
        .uni-name-1 { font-size: 22px; font-weight: 700; color: #1F1F1F; }
        .review-desc { font-size: 15px; color: #666; }
        .uni-logo { width: 80px; height: 80px; object-fit: contain; border-radius: 8px; border: 1px solid #E4E4E4; }
        .home-btn-get-started-2 { border: 2px solid #1A124E; border-radius: 8px; padding: 12px 24px; background: #1A124E; color: white; font-weight: 600; font-size: 15px; text-decoration: none; display: inline-block; }
        .home-btn-get-started-2:hover { background-color: white; color: #1A124E; }
      `}</style>

      <div className="row">
        <div className="col-2">
          {logo ? (
            <img src={logo} className="uni-logo" alt={entityName} />
          ) : (
            <div className="uni-logo d-flex align-items-center justify-content-center" style={{ background: "#f8f8f8" }}>
              <span style={{ fontSize: 10, color: "#999" }}>{t("noLogo")}</span>
            </div>
          )}
        </div>

        <div className="col-10 mt-5">
          <div className="ms-5">
            <div className="col-12 d-flex justify-content-between flex-wrap gap-2">
              <span className="uni-name-1">{scholarship.s_title}</span>
            </div>
            {entityName && <span className="review-desc d-block">{entityName}</span>}

            {/* Max award */}
            {maxDiscount && (
              <div className="d-flex align-items-center mt-5 gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 36 36" fill="none">
                  <path d="M18 24C23.799 24 28.5 19.299 28.5 13.5C28.5 7.701 23.799 3 18 3C12.201 3 7.5 7.701 7.5 13.5C7.5 19.299 12.201 24 18 24Z" stroke="#1F1F1F" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" />
                  <path d="M18 18C20.485 18 22.5 15.985 22.5 13.5C22.5 11.015 20.485 9 18 9C15.515 9 13.5 11.015 13.5 13.5C13.5 15.985 15.515 18 18 18Z" fill="#1F1F1F" />
                  <path d="M10.5 21V30.351L18 28.75L25.5 30.351V21" stroke="#1F1F1F" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" />
                </svg>
                <span className="award-text">{t("awardsUpTo")}</span>
                <span className="award-amt">{maxDiscount}</span>
              </div>
            )}

            {/* Location */}
            {entityName && (
              <div className="col-12 mt-8 d-flex align-items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                  <path d="M8 9C6.343 9 5 7.657 5 6C5 4.343 6.343 3 8 3C9.657 3 11 4.343 11 6C11 7.657 9.657 9 8 9ZM8 1C5.239 1 3 3.239 3 6C3 9.5 8 15 8 15C8 15 13 9.5 13 6C13 3.239 10.761 1 8 1Z" fill="#999999" />
                </svg>
                <span className="submission-user-label">{t("locationLabel")}:</span>
                <span className="course-details">{entityName}</span>
              </div>
            )}

            {/* Level */}
            {levelIds.length > 0 && (
              <div className="col-12 mt-3 d-flex align-items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                  <path d="M14.25 9.25V6L8 2.75L1.75 6L8 9.25L11.25 7.75V11.25C11.25 12.25 9.75 13.25 8 13.25C6.25 13.25 4.75 12.25 4.75 11.25V7.75" stroke="#999999" strokeLinecap="round" strokeLinejoin="round" />
                </svg>
                <span className="submission-user-label">{t("courseLevel")}:</span>
                <span className="course-details">
                  {levelIds.map((id) => levelMap[id] || id).join(", ")}
                </span>
              </div>
            )}

            {/* Field of study */}
            {scholarship.s_study_field && (
              <div className="col-12 mt-3 d-flex align-items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                  <path d="M1 3h14v10H1z" stroke="#999999" strokeLinejoin="round" />
                </svg>
                <span className="submission-user-label">{t("fieldOfStudy")}:</span>
                <span className="course-details">{scholarship.s_study_field}</span>
              </div>
            )}

            {/* Criteria */}
            {scholarship.s_criteria && (
              <div className="mt-10">
                <span className="criteria d-block">{t("criteria")}</span>
                <div className="mt-5" dangerouslySetInnerHTML={{ __html: scholarship.s_criteria }} />
              </div>
            )}

            {/* Highlights */}
            {scholarship.s_highlights && (
              <div className="mt-20">
                <span className="criteria d-block">{t("highlights")}</span>
                <div className="mt-5" dangerouslySetInnerHTML={{ __html: scholarship.s_highlights }} />
              </div>
            )}

            {/* Awards detail */}
            {scholarship.s_award && (
              <div className="mt-20">
                <span className="criteria d-block">{t("award")}</span>
                <div className="mt-5" dangerouslySetInnerHTML={{ __html: scholarship.s_award }} />
              </div>
            )}

            {/* Discount table */}
            {discounts && discounts.length > 0 && (
              <div className="mt-8">
                <table className="col-12">
                  <thead>
                    <tr>
                      <th className="col-4 award-text table-header">{t("course")}</th>
                      <th className="col-4 award-text table-header">{t("grade")}</th>
                      <th className="col-4 award-text table-header">{t("scholarshipAmount")}</th>
                    </tr>
                  </thead>
                  <tbody>
                    {discounts.map((d) => (
                      <tr key={d.sd_id}>
                        <td className="award-text table-body">{d.sd_desc || "-"}</td>
                        <td className="table-body">
                          <span className="award-text d-block pt-3">
                            {d.sd_is_above ? "≥" : ""} {d.sd_grade}
                            {d.sd_count > 0 ? ` (min ${d.sd_count})` : ""}
                          </span>
                        </td>
                        <td className="table-body">
                          <span className="award-text d-block pt-3">
                            {d.sd_is_amt
                              ? formatMoney(d.sd_amt)
                              : `${d.sd_percentage}%`}
                          </span>
                        </td>
                      </tr>
                    ))}
                  </tbody>
                </table>
              </div>
            )}

            {/* Application */}
            {scholarship.s_application && (
              <div className="mt-20">
                <span className="criteria d-block">{t("howToApply")}</span>
                <div className="mt-5" dangerouslySetInnerHTML={{ __html: scholarship.s_application }} />
              </div>
            )}

            {/* Details */}
            {scholarship.s_details && (
              <div className="mt-20">
                <span className="criteria d-block">{t("details")}</span>
                <div className="mt-5" dangerouslySetInnerHTML={{ __html: scholarship.s_details }} />
              </div>
            )}

            {/* Back link */}
            <div className="mt-15">
              {uni && (
                <Link href={`/university/${uni.u_url_code || uni.u_id}`} className="home-btn-get-started-2">
                  {t("viewUniversity")}
                </Link>
              )}
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}
