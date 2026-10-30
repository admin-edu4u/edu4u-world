"use client";

import { useEffect, useState } from "react";
import { useT } from "@/components/LocaleProvider";
import {
  CounsellorRequestForm,
  counsellorFormStyles,
  type CounsellorFormSummary,
  type Qualification,
} from "@/components/counsellor/CounsellorRequestForm";

const CloseSvg = () => (
  <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 10 10" fill="none">
    <path d="M1 1L9 9" stroke="white" strokeWidth="1.5" strokeLinecap="round" />
    <path d="M9 1L1 9" stroke="white" strokeWidth="1.5" strokeLinecap="round" />
  </svg>
);

export type ConsultCounsellorModalProps = {
  open: boolean;
  onClose: () => void;
  qualifications: Qualification[];
  courseId?: number | null;
  pathwayCharacterName?: string;
  defaultPreferredCourse?: string;
};

export function ConsultCounsellorModal({
  open,
  onClose,
  qualifications,
  courseId = null,
  pathwayCharacterName,
  defaultPreferredCourse,
}: ConsultCounsellorModalProps) {
  const t = useT();
  const [submitted, setSubmitted] = useState(false);

  useEffect(() => {
    if (!open) {
      setSubmitted(false);
    }
  }, [open]);

  useEffect(() => {
    if (!open) return;
    const onKey = (e: KeyboardEvent) => {
      if (e.key === "Escape") onClose();
    };
    window.addEventListener("keydown", onKey);
    return () => window.removeEventListener("keydown", onKey);
  }, [open, onClose]);

  if (!open) return null;

  function handleSuccess(_summary: CounsellorFormSummary) {
    setSubmitted(true);
  }

  return (
    <>
      <style>{counsellorFormStyles}</style>
      <div
        className="modal fade edu-site-modal show"
        tabIndex={-1}
        role="dialog"
        aria-modal
        style={{ background: "rgba(0, 0, 0, 0.50)", display: "flex" }}
        onClick={(e) => {
          if (e.target === e.currentTarget) onClose();
        }}
      >
        <div className="modal-dialog modal-dialog-centered modal-dialog-scrollable edu-modal-dialog">
          <div className="modal-content br-12 edu-modal-content" style={{ padding: 24 }}>
            <div className="edu-sheet-handle" aria-hidden />
            <div className="modal-header p-0">
              <h3 className="modal-title login_title">{t("consultOurCounsellor")}</h3>
              <div
                className="btn btn-icon btn-sm ms-2 btn-close-modal"
                onClick={onClose}
                aria-label={t("ok")}
                role="button"
                tabIndex={0}
              >
                <CloseSvg />
              </div>
            </div>
            <div className="modal-body p-0 counsellor-form-shell">
              {submitted ? (
                <div style={{ textAlign: "center", padding: "24px 8px 8px" }}>
                  <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 48 48" fill="none">
                    <path
                      fillRule="evenodd"
                      clipRule="evenodd"
                      d="M24 48C37.2548 48 48 37.2548 48 24C48 10.7452 37.2548 0 24 0C10.7452 0 0 10.7452 0 24C0 37.2548 10.7452 48 24 48ZM33.9007 18.1221C34.6309 17.2932 34.5509 16.0294 33.7221 15.2993C32.8932 14.5691 31.6294 14.6491 30.8993 15.4779L19.7143 28.1744L17.1007 25.2077C16.3706 24.3788 15.1068 24.2989 14.2779 25.029C13.4491 25.7592 13.3691 27.023 14.0993 27.8518L18.2136 32.5221C18.5933 32.953 19.1399 33.2 19.7143 33.2C20.2887 33.2 20.8353 32.953 21.215 32.5221L33.9007 18.1221Z"
                      fill="#29DC50"
                    />
                  </svg>
                  <p style={{ fontSize: 18, fontWeight: 700, color: "#1F1F1F", marginTop: 16 }}>{t("submitSuccess")}</p>
                  <p style={{ fontSize: 14, color: "#666", marginTop: 8 }}>{t("counsellorWillContact")}</p>
                  <button type="button" className="apply-scholarship-btn w-100 mt-5" onClick={onClose}>
                    {t("ok")}
                  </button>
                </div>
              ) : (
                <div className="mt-3">
                  <p style={{ fontSize: 14, color: "#666", marginBottom: 20, lineHeight: 1.5 }}>
                    {pathwayCharacterName ? (
                      <>
                        {t("consultCounsellorLead")}{" "}
                        <strong>{pathwayCharacterName}</strong>.
                      </>
                    ) : (
                      t("consultCounsellorModalIntro")
                    )}
                  </p>
                  <CounsellorRequestForm
                    courseId={courseId}
                    qualifications={qualifications}
                    pathwayCharacterName={pathwayCharacterName}
                    defaultPreferredCourse={defaultPreferredCourse}
                    onSuccess={handleSuccess}
                  />
                </div>
              )}
            </div>
          </div>
        </div>
      </div>
    </>
  );
}
