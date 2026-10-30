import { createPublicClient } from "@/lib/supabase/server";
import VisitCampusForm from "./VisitCampusForm";
import { getLocale } from "@/lib/i18n/locale";
import { getLocalized } from "@/lib/i18n/cms";

export default async function VisitCampusPage() {
  const supabase = createPublicClient();
  const locale = await getLocale();

  const { data: universities } = await supabase
    .from("universities")
    .select("u_id,u_name,i18n")
    .eq("u_status", 1)
    .order("u_name");

  const localizedUnis = (universities || []).map((u) => ({
    u_id: u.u_id,
    u_name: getLocalized(u, "u_name", locale),
  }));

  return (
    <div className="mw-1200 mx-auto pt-140 pb-20 px-5 px-lg-0">
      <style>{`
        .form-label { color: #1F1F1F; font-size: 16px; font-style: normal; font-weight: 600; line-height: normal; }
        .form-control-solid, .form-select-solid, .form-control, .form-select {
          padding: 18px 16px;
          border-radius: 8px !important;
          background-color: #F6F6F6 !important;
          border: 1px solid #F6F6F6;
          font-size: 14px;
        }
        ::placeholder { color: #CCC !important; font-size: 14px; font-weight: 400; opacity: 1; }
        .separator { border-bottom: 1px solid #E4E4E4 !important; }
        .btn-submit-visit-form { border: 2px solid #1A124E; border-radius: 8px; padding: 14px 24px; background: #1A124E; color: white; font-weight: 600; font-size: 15px; cursor: pointer; }
        .btn-submit-visit-form:hover { color: #1A124E; background-color: white; }
        .btn-submit-visit-form:disabled { opacity: 0.5; cursor: not-allowed; }
        .visit-campus-text { font-size: 24px; font-weight: 700; color: #1F1F1F; text-align: center; }
        .uni-name { font-size: 14px; color: #666; }
        .uni-location { font-size: 14px; color: #999; }
        .submission-div { border: 1px solid #E4E4E4; border-radius: 12px; padding: 20px; }
        .submission-user-label { font-size: 13px; color: #999; }
        .submission-user-details { font-size: 14px; font-weight: 600; color: #1F1F1F; }
        .submission-details-text { font-size: 16px; font-weight: 600; color: #1F1F1F; text-align: center; }
      `}</style>

      <VisitCampusForm universities={localizedUnis} />
    </div>
  );
}
