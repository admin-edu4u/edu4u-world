import { createPublicClient } from "@/lib/supabase/server";
import { CDN } from "@/lib/cdn";
import RegisterInterestForm from "./RegisterInterestForm";

export default async function RegisterInterestPage() {
  const supabase = createPublicClient();

  // Fetch banner if available
  const { data: banner } = await supabase
    .from("banners")
    .select("banner_id,banner_img")
    .eq("banner_status", 1)
    .limit(1)
    .single();

  // Fetch states for the form
  const { data: states } = await supabase
    .from("states")
    .select("state_id,state_name")
    .eq("state_status", 1)
    .order("state_name");

  return (
    <div>
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
        .banner-register { max-width: 100%; height: auto; display: block; }
      `}</style>

      {banner && (
        <img
          src={`${CDN}/images/banners/${banner.banner_img}`}
          className="banner-register mx-auto"
          alt="Register interest banner"
        />
      )}

      <div className="mw-1200 mx-auto px-5 px-lg-0 pb-20">
        <RegisterInterestForm states={states || []} />
      </div>
    </div>
  );
}
