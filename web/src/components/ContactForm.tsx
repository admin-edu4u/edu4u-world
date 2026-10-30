"use client";

import { useState } from "react";
import { MalaysianPhoneInput } from "@/components/ui/MalaysianPhoneInput";
import { useT } from "@/components/LocaleProvider";
import { malaysianPhoneError } from "@/lib/phone";
import { isValidEmail } from "@/lib/email";

type FormState = "idle" | "loading" | "success" | "error";

export function ContactForm() {
  const t = useT();
  const [name, setName] = useState("");
  const [email, setEmail] = useState("");
  const [contactNo, setContactNo] = useState("");
  const [message, setMessage] = useState("");
  const [state, setState] = useState<FormState>("idle");
  const [msg, setMsg] = useState("");

  async function handleSubmit() {
    const phoneErr = malaysianPhoneError(contactNo, { label: t("phoneNumber") });
    if (phoneErr) {
      setState("error");
      setMsg(phoneErr);
      return;
    }
    if (!isValidEmail(email)) {
      setState("error");
      setMsg(t("invalidEmailAddress"));
      return;
    }
    setState("loading");
    try {
      const res = await fetch("/api/contact", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ name, email, contact_no: contactNo, message }),
      });
      const data = await res.json();
      if (data.status) {
        setState("success");
        setMsg(data.msg);
        setName("");
        setEmail("");
        setContactNo("");
        setMessage("");
      } else {
        setState("error");
        setMsg(data.msg);
      }
    } catch {
      setState("error");
      setMsg(t("somethingWentWrong", "Something went wrong. Please try again."));
    }
  }

  return (
    <div className="col-12 col-lg-6">
      <span className="d-block event-title-2">{t("enquiry")}</span>
      <div className="mb-10 mt-5">
        <label className="form-label">{t("whatsYourName")}</label>
        <input
          type="text"
          name="name"
          className="form-control form-control-solid"
          placeholder={t("enterFullname")}
          value={name}
          onChange={(e) => setName(e.target.value)}
        />
      </div>
      <div className="mb-10">
        <label className="form-label">{t("whatsYourEmail")}</label>
        <input
          type="email"
          inputMode="email"
          autoComplete="email"
          required
          name="email"
          className="form-control form-control-solid"
          placeholder={t("enterEmailAddr")}
          value={email}
          onChange={(e) => setEmail(e.target.value)}
        />
      </div>
      <div className="mb-10">
        <label className="form-label">{t("whatsYourPhoneNumber")}</label>
        <MalaysianPhoneInput
          name="contact_no"
          className="form-control form-control-solid"
          required
          value={contactNo}
          onChange={setContactNo}
        />
      </div>
      <div className="mb-10">
        <label className="form-label">{t("addMessage")}</label>
        <textarea
          className="form-control form-control-solid"
          id="message"
          name="message"
          placeholder={t("enterMessage")}
          value={message}
          onChange={(e) => setMessage(e.target.value)}
        />
      </div>
      {state === "success" && (
        <div className="alert alert-success mb-5">{msg}</div>
      )}
      {state === "error" && (
        <div className="alert alert-danger mb-5">{msg}</div>
      )}
      <button
        className="btn-submit-contact-us w-100"
        onClick={handleSubmit}
        disabled={state === "loading"}
      >
        {state === "loading" ? t("submitting", "Submitting...") : t("submit")}
      </button>
    </div>
  );
}
