"use client";

import { useMemo, useState } from "react";
import { SearchableSelect } from "@/components/ui/SearchableSelect";
import { MalaysianPhoneInput } from "@/components/ui/MalaysianPhoneInput";
import { useT } from "@/components/LocaleProvider";
import { malaysianPhoneError } from "@/lib/phone";

type State = { state_id: number; state_name: string };

export default function RegisterInterestForm({ states }: { states: State[] }) {
  const t = useT();
  const levels = useMemo(
    () => [
      { value: 1, label: t("studyLevelAdvancedDiploma") },
      { value: 2, label: t("studyLevelCertificate") },
      { value: 3, label: t("studyLevelDegree") },
      { value: 4, label: t("studyLevelDiploma") },
      { value: 5, label: t("studyLevelFoundationPreU") },
      { value: 6, label: t("studyLevelPgDiploma") },
      { value: 7, label: t("studyLevelPgMasters") },
      { value: 8, label: t("studyLevelPgPhd") },
      { value: 9, label: t("studyLevelProfessional") },
    ],
    [t],
  );
  const [submitted, setSubmitted] = useState(false);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState("");

  async function handleSubmit(e: React.FormEvent<HTMLFormElement>) {
    e.preventDefault();
    setError("");
    const fd = new FormData(e.currentTarget);
    const contact = String(fd.get("contact") || "");
    const phoneErr = malaysianPhoneError(contact, { label: t("contactNum") });
    if (phoneErr) {
      setError(phoneErr);
      return;
    }
    setLoading(true);
    const body = {
      ri_name: fd.get("name"),
      ri_email: fd.get("email"),
      ri_contact: contact,
      ri_birthday: fd.get("birthday"),
      ri_school: fd.get("school"),
      ri_state: Number(fd.get("state") || 0),
      ri_course: fd.get("course"),
      ri_level: Number(fd.get("level") || 0),
      ri_uni: fd.get("uni"),
      ri_remarks: fd.get("remarks"),
    };
    try {
      const res = await fetch("/api/register-interest", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(body),
      });
      if (!res.ok) throw new Error("Submission failed");
      setSubmitted(true);
    } catch {
      setError(t("somethingWentWrong"));
    } finally {
      setLoading(false);
    }
  }

  if (submitted) {
    return (
      <div className="sucess-submission text-center py-20">
        <div className="text-center">
          <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 48 48" fill="none">
            <path fillRule="evenodd" clipRule="evenodd" d="M24 48C37.2548 48 48 37.2548 48 24C48 10.7452 37.2548 0 24 0C10.7452 0 0 10.7452 0 24C0 37.2548 10.7452 48 24 48ZM33.9007 18.1221C34.6309 17.2932 34.5509 16.0294 33.7221 15.2993C32.8932 14.5691 31.6294 14.6491 30.8993 15.4779L19.7143 28.1744L17.1007 25.2077C16.3706 24.3788 15.1068 24.2989 14.2779 25.029C13.4491 25.7592 13.3691 27.023 14.0993 27.8518L18.2136 32.5221C18.5933 32.953 19.1399 33.2 19.7143 33.2C20.2887 33.2 20.8353 32.953 21.215 32.5221L33.9007 18.1221Z" fill="#29DC50" />
          </svg>
        </div>
        <span className="pt-3 d-block visit-campus-text">{t("submitSuccess")}</span>
        <span className="d-block uni-name text-center pt-3">{t("registerInterestSuccess")}</span>
      </div>
    );
  }

  return (
    <div className="submission-form mt-10">
      <span className="d-block visit-campus-text">{t("registerInterest")}</span>
      <span className="d-block uni-name text-center pt-3">{t("registerInterestDesc")}</span>

      <div className="col-12 col-lg-4 mx-auto">
        <form id="submit_interest" onSubmit={handleSubmit}>
          <div className="mb-5 mt-10">
            <label className="form-label">{t("fullname")}</label>
            <input type="text" className="form-control form-control-solid" name="name" placeholder={t("enterFullname")} required />
          </div>
          <div className="mb-5">
            <label className="form-label">{t("emailAddr")}</label>
            <input type="email" className="form-control form-control-solid" name="email" placeholder={t("enterEmailAddr")} required />
          </div>
          <div className="mb-5">
            <label className="form-label">{t("contactNum")}</label>
            <MalaysianPhoneInput className="form-control form-control-solid" name="contact" required />
          </div>
          <div className="mb-5">
            <label className="form-label">{t("birthday")}</label>
            <input type="date" className="form-control form-control-solid" name="birthday" required />
          </div>
          <div className="mb-5">
            <label className="form-label">{t("secondarySchool")}</label>
            <input type="text" className="form-control form-control-solid" name="school" placeholder={t("enterSecondarySchool")} required />
          </div>
          <div className="mb-5">
            <label className="form-label">{t("stateOfResidence")}</label>
            <SearchableSelect
              name="state"
              required
              placeholder={t("selectState")}
              inputClassName="form-control form-control-solid"
              options={states.map((s) => ({ value: String(s.state_id), label: s.state_name }))}
            />
          </div>
          <div className="mb-5">
            <label className="form-label">{t("courseInterested")}</label>
            <input type="text" className="form-control form-control-solid" name="course" placeholder={t("enterCourseInterested")} required />
          </div>
          <div className="mb-5">
            <label className="form-label">{t("levelInterested")}</label>
            <SearchableSelect
              name="level"
              required
              placeholder={t("selectLevelInterested")}
              inputClassName="form-control form-control-solid"
              options={levels.map((l) => ({ value: String(l.value), label: l.label }))}
            />
          </div>
          <div className="mb-5">
            <label className="form-label">{t("uniInterested")}</label>
            <input type="text" className="form-control form-control-solid" name="uni" placeholder={t("enterUniInterested")} required />
          </div>
          <div className="mb-5">
            <label className="form-label">{t("remarks")}</label>
            <textarea className="form-control form-control-solid" name="remarks" rows={3} placeholder={t("addRemarks")} />
          </div>

          {error && <p style={{ color: "red", fontSize: 14, marginBottom: 8 }}>{error}</p>}

          <button
            className="w-100 mt-5 btn-submit-visit-form"
            id="btn"
            type="submit"
            disabled={loading}
          >
            {loading ? t("submitting") : t("submit")}
          </button>
        </form>
      </div>
    </div>
  );
}
