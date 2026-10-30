import { createPublicClient } from "@/lib/supabase/server";
import { notFound } from "next/navigation";
import CourseApplicationForm from "./CourseApplicationForm";
import { getLocale } from "@/lib/i18n/locale";
import { getLocalized, pickLocalizedFields } from "@/lib/i18n/cms";
import { CMS_I18N_FIELDS } from "@/lib/admin/cmsFields";

interface Props {
  params: Promise<{ url: string }>;
}

export default async function CourseApplicationPage({ params }: Props) {
  const { url } = await params;
  const supabase = createPublicClient();
  const locale = await getLocale();

  const { data: rawCourse } = await supabase
    .from("courses")
    .select("c_id, c_name, c_uni, c_url_code, i18n")
    .eq("c_url_code", url)
    .eq("c_status", 1)
    .single();

  if (!rawCourse) notFound();
  const course = pickLocalizedFields(rawCourse, CMS_I18N_FIELDS.course, locale);

  const { data: rawUni } = await supabase
    .from("universities")
    .select("u_name, i18n")
    .eq("u_id", course.c_uni)
    .single();
  const university = rawUni
    ? { u_name: getLocalized(rawUni, "u_name", locale) }
    : null;

  const { data: qualifications } = await supabase
    .from("qualification_level")
    .select("q_id, q_level")
    .eq("q_status", 1)
    .order("q_id");

  return (
    <div className="mw-1200 mx-auto pt-140 pb-20 px-5 px-lg-0">
      <style>{`
        .form-label { color: #1F1F1F; font-size: 16px; font-weight: 400; line-height: normal; }
        .option-text { color: #999; font-size: 12px; font-weight: 400; margin-left: 6px; }
        .form-control-solid, .form-select-solid, .form-control, .form-select {
          padding: 18px 16px;
          border-radius: 8px !important;
          background-color: #F6F6F6 !important;
          border: 1px solid #E4E4E4;
          font-size: 14px;
          width: 100%;
          outline: none;
        }
        .form-control-solid:focus, .form-select-solid:focus, .form-control:focus, .form-select:focus {
          border-color: #1A124E;
          box-shadow: none;
        }
        ::placeholder { color: #CCC !important; font-size: 14px; font-weight: 400; opacity: 1; }
        .separator-top { border-top: 1px solid #E4E4E4; }
        .visit-campus-text { font-size: 32px; font-weight: 700; color: #1F1F1F; text-align: center; }
        .uni-name { font-size: 14px; color: #666; text-align: center; }
        .course-application-div { background: #F6F6F6; border-radius: 12px; padding: 20px; }
        .course-application-text-1 { font-size: 13px; color: #999; }
        .course-application-text-2 { font-size: 14px; font-weight: 600; color: #1F1F1F; }
        .application-title { font-size: 16px; font-weight: 600; color: #1A124E; }
        .btn-submit-visit-form {
          border: 2px solid #1A124E;
          border-radius: 8px;
          padding: 14px 24px;
          background: #1A124E;
          color: white;
          font-weight: 600;
          font-size: 15px;
          cursor: pointer;
          width: 100%;
          margin-top: 20px;
        }
        .btn-submit-visit-form:hover { background: white; color: #1A124E; }
        .btn-submit-visit-form:disabled { opacity: 0.5; cursor: not-allowed; }
        .submission-div { border: 1px solid #E4E4E4; border-radius: 12px; padding: 24px; }
        .submission-user-label { font-size: 13px; color: #999; }
        .submission-user-details { font-size: 14px; font-weight: 600; color: #1F1F1F; }
        .course-application-details-header { font-size: 15px; font-weight: 700; color: #1A124E; }
        .submission-details-text { font-size: 16px; font-weight: 600; color: #1F1F1F; text-align: center; }
        .success-icon-wrap { display: flex; justify-content: center; padding-top: 40px; }
        .form-check-input { width: 16px; height: 16px; margin-right: 8px; cursor: pointer; }
        .p_addr_label { font-size: 14px; color: #1F1F1F; cursor: pointer; }
        .mb-5 { margin-bottom: 20px; }
        .mt-5 { margin-top: 20px; }
        .mt-10 { margin-top: 40px; }
        .mt-20 { margin-top: 80px; }
        .ms-3 { margin-left: 12px; }
        .pt-3 { padding-top: 12px; }
        .row { display: flex; flex-wrap: wrap; margin: 0 -8px; }
        .col-4 { flex: 0 0 33.333%; max-width: 33.333%; padding: 0 8px; }
        .col-8 { flex: 0 0 66.666%; max-width: 66.666%; padding: 0 8px; }
        .col-5 { flex: 0 0 41.666%; max-width: 41.666%; padding: 0 8px; }
        .col-7 { flex: 0 0 58.333%; max-width: 58.333%; padding: 0 8px; }
        .mx-auto { margin-left: auto; margin-right: auto; }
        @media (max-width: 768px) {
          .col-lg-4 { max-width: 100%; flex: 0 0 100%; }
          .col-lg-7 { max-width: 100%; flex: 0 0 100%; }
          .visit-campus-text { font-size: 24px; }
        }
      `}</style>
      <CourseApplicationForm
        courseId={course.c_id}
        courseName={course.c_name}
        universityName={university?.u_name ?? ""}
        qualifications={qualifications ?? []}
      />
    </div>
  );
}
