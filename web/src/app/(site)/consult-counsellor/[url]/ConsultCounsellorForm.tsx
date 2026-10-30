"use client";

import { useState } from "react";
import { useRouter } from "next/navigation";
import { useT } from "@/components/LocaleProvider";
import {
  CounsellorRequestForm,
  counsellorFormStyles,
  type CounsellorFormSummary,
  type Qualification,
} from "@/components/counsellor/CounsellorRequestForm";

interface Props {
  courseId: number;
  courseName: string;
  universityName: string;
  qualifications: Qualification[];
}

export default function ConsultCounsellorForm({ courseId, courseName, universityName, qualifications }: Props) {
  const t = useT();
  const router = useRouter();
  const [submitted, setSubmitted] = useState(false);
  const [summary, setSummary] = useState<CounsellorFormSummary>({});

  if (submitted) {
    return (
      <div style={{ paddingTop: 40 }}>
        <div style={{ display: "flex", justifyContent: "center" }}>
          <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 48 48" fill="none">
            <path fillRule="evenodd" clipRule="evenodd" d="M24 48C37.2548 48 48 37.2548 48 24C48 10.7452 37.2548 0 24 0C10.7452 0 0 10.7452 0 24C0 37.2548 10.7452 48 24 48ZM33.9007 18.1221C34.6309 17.2932 34.5509 16.0294 33.7221 15.2993C32.8932 14.5691 31.6294 14.6491 30.8993 15.4779L19.7143 28.1744L17.1007 25.2077C16.3706 24.3788 15.1068 24.2989 14.2779 25.029C13.4491 25.7592 13.3691 27.023 14.0993 27.8518L18.2136 32.5221C18.5933 32.953 19.1399 33.2 19.7143 33.2C20.2887 33.2 20.8353 32.953 21.215 32.5221L33.9007 18.1221Z" fill="#29DC50"/>
          </svg>
        </div>
        <span className="visit-campus-text" style={{ display: "block", paddingTop: 12 }}>{t("submitSuccess")}</span>
        <span className="uni-name" style={{ display: "block", paddingTop: 8 }}>{t("counsellorWillContact")}</span>
        <span className="submission-details-text" style={{ display: "block", paddingTop: 40 }}>{t("submitDetails")}</span>

        <div style={{ maxWidth: 560, margin: "20px auto 0" }}>
          <div className="submission-div">
            {[
              [t("salutation"), summary.salutation],
              [t("fullName"), summary.name],
              [t("gender"), summary.gender],
              [t("dateOfBirth"), summary.dob],
              [t("secondarySchool"), summary.school_name],
              [t("mobileNumber"), summary.contact_no],
              [t("homePhoneNumber"), summary.home_contact_no],
              [t("prefCourse"), summary.preferred_course],
              [t("highestQ"), summary.qualification],
              [t("remarks"), summary.remarks],
            ].map(([label, value]) => (
              <div key={label} style={{ display: "flex", marginBottom: 8 }}>
                <span className="submission-user-label" style={{ minWidth: 200 }}>{label}</span>
                <span className="submission-user-details">{value || "-"}</span>
              </div>
            ))}
          </div>
          <div style={{ display: "flex", justifyContent: "center" }}>
            <button
              className="btn-submit-visit-form"
              style={{ maxWidth: 260 }}
              onClick={() => router.push("/")}
            >
              {t("backToHome")}
            </button>
          </div>
        </div>
      </div>
    );
  }

  return (
    <div className="submission-form counsellor-form-shell" style={{ marginTop: 40 }}>
      <style>{counsellorFormStyles}</style>
      <span className="visit-campus-text" style={{ display: "block" }}>{t("consultOurCounsellor")}</span>
      <span className="uni-name" style={{ display: "block", paddingTop: 12 }}>
        {t("consultCounsellorLead")}{" "}
        <strong>{courseName}</strong> ({universityName}).
      </span>

      <div style={{ maxWidth: 560, margin: "0 auto" }}>
        <CounsellorRequestForm
          courseId={courseId}
          qualifications={qualifications}
          onSuccess={(s) => {
            setSummary(s);
            setSubmitted(true);
            window.scrollTo(0, 0);
          }}
        />
      </div>
    </div>
  );
}
