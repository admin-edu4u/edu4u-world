"use client";

import { useState } from "react";
import { SearchableSelect } from "@/components/ui/SearchableSelect";
import { MalaysianPhoneInput } from "@/components/ui/MalaysianPhoneInput";
import { malaysianPhoneError } from "@/lib/phone";
import { useT } from "@/components/LocaleProvider";

export type Qualification = { q_id: number; q_level: string };

export type CounsellorFormSummary = Record<string, string>;

export type CounsellorRequestFormProps = {
  courseId: number | null;
  qualifications: Qualification[];
  pathwayCharacterName?: string;
  defaultPreferredCourse?: string;
  submitClassName?: string;
  onSuccess: (summary: CounsellorFormSummary) => void;
};

const phoneInputStyle: React.CSSProperties = {
  padding: "18px 16px",
  borderRadius: 8,
  backgroundColor: "#F6F6F6",
  border: "1px solid #E4E4E4",
  fontSize: 14,
  width: "100%",
  outline: "none",
};

export function CounsellorRequestForm({
  courseId,
  qualifications,
  pathwayCharacterName,
  defaultPreferredCourse = "",
  submitClassName = "btn-submit-visit-form",
  onSuccess,
}: CounsellorRequestFormProps) {
  const t = useT();
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState("");

  async function handleSubmit(e: React.FormEvent<HTMLFormElement>) {
    e.preventDefault();
    setError("");

    const fd = new FormData(e.currentTarget);
    const get = (k: string) => (fd.get(k) as string) || "";

    const contactNo = get("contact_no");
    const homeContact = get("home_contact_no");
    const phoneErr =
      malaysianPhoneError(contactNo, { label: t("mobileNumber") }) ||
      malaysianPhoneError(homeContact, { optional: true, label: t("homePhoneNumber") });
    if (phoneErr) {
      setError(phoneErr);
      return;
    }

    setLoading(true);

    const body = {
      course_id: courseId,
      salutation: get("salutation"),
      name: get("name"),
      gender: get("gender"),
      dob_date: get("dob_date"),
      dob_month: get("dob_month"),
      dob_year: get("dob_year"),
      school_name: get("school_name"),
      contact_no: contactNo,
      home_contact_no: homeContact,
      preferred_course: get("preferred_course"),
      qualification: get("qualification"),
      remarks: get("remarks"),
      pathway_character: pathwayCharacterName || null,
    };

    const qualLabel =
      qualifications.find((q) => String(q.q_id) === body.qualification)?.q_level ?? body.qualification;

    const summary: CounsellorFormSummary = {
      salutation: body.salutation,
      name: body.name,
      gender: body.gender === "1" ? t("male") : body.gender === "2" ? t("female") : body.gender,
      dob: `${body.dob_date}/${body.dob_month}/${body.dob_year}`,
      school_name: body.school_name,
      contact_no: body.contact_no,
      home_contact_no: body.home_contact_no || "-",
      preferred_course: body.preferred_course,
      qualification: qualLabel,
      remarks: body.remarks || "-",
    };

    try {
      const res = await fetch("/api/consult-counsellor", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(body),
      });
      if (!res.ok) {
        const d = await res.json();
        setError(d.error || t("somethingWentWrong"));
        setLoading(false);
        return;
      }
      onSuccess(summary);
    } catch {
      setError(t("networkError"));
    } finally {
      setLoading(false);
    }
  }

  return (
    <form onSubmit={handleSubmit}>
      {error ? (
        <div
          style={{
            marginBottom: 16,
            background: "#fff0f0",
            border: "1px solid #f88",
            borderRadius: 8,
            padding: "12px 16px",
            color: "#c00",
            fontSize: 14,
          }}
        >
          {error}
        </div>
      ) : null}

      <FormSelect
        name="salutation"
        label={t("salutation")}
        placeholder={t("selectSalutation")}
        required
        options={[
          { value: "Mr", label: t("mr") },
          { value: "Ms", label: t("ms") },
          { value: "Mrs", label: t("mrs") },
        ]}
      />
      <FormInput name="name" label={t("fullName")} placeholder={t("enterFullname")} required />
      <FormSelect
        name="gender"
        label={t("gender")}
        placeholder={t("gender")}
        required
        options={[
          { value: "1", label: t("male") },
          { value: "2", label: t("female") },
        ]}
      />
      <div className="mb-5">
        <label className="form-label">{t("dateOfBirth")}</label>
        <div className="row">
          <div className="col-4">
            <input
              type="number"
              name="dob_date"
              className="form-control-solid"
              placeholder={t("dd")}
              required
              style={phoneInputStyle}
            />
          </div>
          <div className="col-4">
            <input
              type="number"
              name="dob_month"
              className="form-control-solid"
              placeholder={t("mm")}
              required
              style={phoneInputStyle}
            />
          </div>
          <div className="col-4">
            <input
              type="number"
              name="dob_year"
              className="form-control-solid"
              placeholder={t("yyyy")}
              required
              style={phoneInputStyle}
            />
          </div>
        </div>
      </div>
      <FormInput
        name="school_name"
        label={t("secondarySchool")}
        placeholder={t("enterSecondarySchool")}
        required
      />
      <FormPhone name="contact_no" label={t("mobileNumber")} required />
      <div className="mb-5">
        <label className="form-label">
          {t("homePhoneNumber")} <span className="option-text">{t("optional")}</span>
        </label>
        <MalaysianPhoneInput
          name="home_contact_no"
          className="form-control-solid"
          style={phoneInputStyle}
        />
      </div>
      <FormInput
        name="preferred_course"
        label={t("prefCourse")}
        placeholder={t("enterPrefCourse")}
        required
        defaultValue={defaultPreferredCourse}
      />
      <FormSelect
        name="qualification"
        label={t("highestQ")}
        placeholder={t("selectHighestQualification")}
        required
        options={qualifications.map((q) => ({ value: String(q.q_id), label: q.q_level }))}
      />
      <div className="mb-5">
        <label className="form-label">
          {t("remarks")} <span className="option-text">{t("optional")}</span>
        </label>
        <textarea
          name="remarks"
          placeholder={t("enterRemarks")}
          rows={4}
          style={{ ...phoneInputStyle, resize: "vertical" }}
        />
      </div>

      <button className={submitClassName} type="submit" disabled={loading}>
        {loading ? t("submitting") : t("submit")}
      </button>
    </form>
  );
}

function FormInput({
  name,
  label,
  placeholder,
  required,
  type = "text",
  defaultValue,
}: {
  name: string;
  label: string;
  placeholder: string;
  required?: boolean;
  type?: string;
  defaultValue?: string;
}) {
  return (
    <div className="mb-5">
      <label className="form-label">{label}</label>
      <input
        type={type}
        name={name}
        className="form-control-solid"
        placeholder={placeholder}
        required={required}
        defaultValue={defaultValue}
        style={phoneInputStyle}
      />
    </div>
  );
}

function FormPhone({ name, label, required }: { name: string; label: string; required?: boolean }) {
  return (
    <div className="mb-5">
      <label className="form-label">{label}</label>
      <MalaysianPhoneInput
        name={name}
        className="form-control-solid"
        required={required}
        style={phoneInputStyle}
      />
    </div>
  );
}

function FormSelect({
  name,
  label,
  placeholder,
  required,
  options,
}: {
  name: string;
  label: string;
  placeholder: string;
  required?: boolean;
  options: { value: string; label: string }[];
}) {
  return (
    <div className="mb-5">
      <label className="form-label">{label}</label>
      <SearchableSelect
        name={name}
        required={required}
        placeholder={placeholder}
        options={options}
        inputClassName="form-control form-control-solid"
      />
    </div>
  );
}

export const counsellorFormStyles = `
  .counsellor-form-shell .form-label { color: #1F1F1F; font-size: 16px; font-weight: 400; line-height: normal; }
  .counsellor-form-shell .option-text { color: #999; font-size: 12px; font-weight: 400; margin-left: 6px; }
  .counsellor-form-shell .form-control-solid,
  .counsellor-form-shell .form-select-solid,
  .counsellor-form-shell .form-control,
  .counsellor-form-shell .form-select {
    padding: 18px 16px;
    border-radius: 8px !important;
    background-color: #F6F6F6 !important;
    border: 1px solid #E4E4E4;
    font-size: 14px;
    width: 100%;
    outline: none;
  }
  .counsellor-form-shell ::placeholder { color: #CCC !important; font-size: 14px; font-weight: 400; opacity: 1; }
  .counsellor-form-shell .mb-5 { margin-bottom: 20px; }
  .counsellor-form-shell .row { display: flex; flex-wrap: wrap; margin: 0 -8px; }
  .counsellor-form-shell .col-4 { flex: 0 0 33.333%; max-width: 33.333%; padding: 0 8px; }
  .counsellor-form-shell .btn-submit-visit-form {
    border: 2px solid #1A124E;
    border-radius: 8px;
    padding: 14px 24px;
    background: #1A124E;
    color: white;
    font-weight: 600;
    font-size: 15px;
    cursor: pointer;
    width: 100%;
    margin-top: 8px;
  }
  .counsellor-form-shell .btn-submit-visit-form:hover { background: white; color: #1A124E; }
  .counsellor-form-shell .btn-submit-visit-form:disabled { opacity: 0.5; cursor: not-allowed; }
`;
