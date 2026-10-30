import { notFound } from "next/navigation";
import Link from "next/link";
import { createPublicClient } from "@/lib/supabase/server";
import { CDN } from "@/lib/cdn";
import { getLocale } from "@/lib/i18n/locale";
import { pickLocalizedFields } from "@/lib/i18n/cms";
import { CMS_I18N_FIELDS } from "@/lib/admin/cmsFields";
import { getDictionary, translate } from "@/lib/i18n/dictionaries";

type Params = { code: string };

export default async function K12DetailsPage({ params }: { params: Promise<Params> }) {
  const { code } = await params;
  const supabase = createPublicClient();
  const locale = await getLocale();
  const dict = await getDictionary(locale);
  const t = (key: string) => translate(dict, key);

  let { data: school } = await supabase
    .from("international_school")
    .select("*")
    .eq("int_url_code", code)
    .maybeSingle();

  if (!school) {
    const numericId = parseInt(code, 10);
    if (!isNaN(numericId)) {
      const { data } = await supabase
        .from("international_school")
        .select("*")
        .eq("int_id", numericId)
        .maybeSingle();
      school = data;
    }
  }

  if (!school) notFound();

  school = pickLocalizedFields(school, CMS_I18N_FIELDS.k12, locale);

  // Parse images
  let images: string[] = [];
  if (school.int_img) {
    try {
      const parsed = JSON.parse(school.int_img);
      images = Array.isArray(parsed) ? parsed : [school.int_img];
    } catch {
      images = school.int_img.split(",").map((s: string) => s.trim()).filter(Boolean);
    }
  }

  // Related scholarships
  const { data: rawScholarships } = await supabase
    .from("int_scholarship")
    .select("s_id,s_url_code,s_title,i18n")
    .eq("s_int_id", school.int_id)
    .eq("s_status", 1);

  const scholarships = (rawScholarships ?? []).map((r) =>
    pickLocalizedFields(r, CMS_I18N_FIELDS.k12Scholarship, locale)
  );

  return (
    <div className="pb-20">
      <style>{`
        .uni-img-slider { width: 100%; height: 420px; object-fit: cover; display: block; border-radius: 0; }
        .uni-logo { width: 80px; height: 80px; object-fit: contain; border-radius: 8px; border: 1px solid #E4E4E4; }
        .uni-name-1 { font-size: 22px; font-weight: 700; color: #1F1F1F; }
        .uni-name { font-size: 15px; color: #666; }
        .uni-location { font-size: 14px; color: #999; }
        .separator-right { border-right: 1px solid #E4E4E4; height: 40px; }
        .criteria { font-size: 20px; font-weight: 700; color: #1F1F1F; }
        .visit-campus { border: 1px solid #1A124E; border-radius: 100px; padding: 8px 20px; font-size: 14px; font-weight: 500; color: #1A124E; text-decoration: none; }
        .course-card { border: 1px solid #E4E4E4; border-radius: 12px; padding: 16px; }
        .course-card:hover { box-shadow: 0 4px 20px rgba(0,0,0,0.08); }
      `}</style>

      {images.length > 0 && (
        <div className="mb-20">
          <img
            src={`${CDN}/images/internationalschool/images/${images[0]}`}
            className="uni-img-slider"
            alt={school.int_name}
          />
        </div>
      )}

      {!images.length && <div className="pt-80" />}

      <div className="mw-1200 mx-auto px-5 px-lg-0">
        <div className="row">
          <div className="col-12 col-lg-3 mb-5 mb-lg-0">
            {school.int_logo && (
              <img
                src={`${CDN}/images/internationalschool/logo/${school.int_logo}`}
                className="uni-logo"
                alt={school.int_name}
              />
            )}
          </div>
          <div className="col-12 col-lg-9 mt-lg-10">
            <div className="col-12 d-flex justify-content-between align-items-start flex-wrap gap-3">
              <span className="uni-name-1">{school.int_name}</span>
              <Link href={`/visit-campus`} className="visit-campus">
                {t("visitTheCampus")}
              </Link>
            </div>

            <div className="col-12 d-flex mt-5 flex-wrap gap-3">
              <div>
                <span className="d-block uni-name">&gt; {school.int_student?.toLocaleString()}</span>
                <span className="d-block uni-location">{t("studentNum")}</span>
              </div>
              <div className="mx-5 separator-right d-none d-lg-block" />
              <div>
                <span className="d-block uni-name">&gt; {school.int_staff?.toLocaleString()}</span>
                <span className="d-block uni-location">{t("staffRatio")}</span>
              </div>
              <div className="mx-5 separator-right d-none d-lg-block" />
              <div>
                <span className="d-block uni-name">{school.int_year}</span>
                <span className="d-block uni-location">{t("foundedLabel")}</span>
              </div>
            </div>

            {school.int_addr && (
              <div className="mt-5">
                <span className="uni-location">{school.int_addr}</span>
              </div>
            )}

            {school.int_desc && (
              <div className="mt-7">
                <span className="d-block uni-name">{school.int_desc}</span>
              </div>
            )}
          </div>
        </div>

        {/* Scholarships */}
        {scholarships.length > 0 && (
          <div className="mt-15 mb-10">
            <span className="criteria d-block mb-8">{t("scholarships")}</span>
            <div className="row g-5">
              {scholarships.map((sch) => (
                <div className="col-12 col-md-6" key={sch.s_id}>
                  <Link href={`/k12-scholarship-details/${sch.s_url_code || sch.s_id}`} className="text-decoration-none">
                    <div className="course-card">
                      <span className="d-block" style={{ fontWeight: 600, fontSize: 15 }}>{sch.s_title}</span>
                    </div>
                  </Link>
                </div>
              ))}
            </div>
          </div>
        )}
      </div>
    </div>
  );
}
