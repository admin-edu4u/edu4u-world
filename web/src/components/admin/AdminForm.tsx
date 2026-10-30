"use client";

import { useState } from "react";
import { unstable_rethrow } from "next/navigation";

type Span = 3 | 4 | 6 | 8 | 12;

export function AdminForm({
  action,
  encType,
  children,
}: {
  action: (formData: FormData) => void | Promise<void>;
  encType?: string;
  children: React.ReactNode;
}) {
  const [error, setError] = useState<string | null>(null);

  return (
    <div className="admin-form-shell">
      {error ? (
        <div className="admin-form-error" role="alert">
          {error}
        </div>
      ) : null}
      <form
        action={async (formData) => {
          setError(null);
          try {
            await action(formData);
          } catch (err) {
            unstable_rethrow(err);
            setError(err instanceof Error ? err.message : "Save failed");
          }
        }}
        encType={encType}
        className="admin-form"
      >
        {children}
      </form>
    </div>
  );
}

export function AdminFormSection({
  title,
  description,
  children,
}: {
  title: string;
  description?: string;
  children: React.ReactNode;
}) {
  return (
    <section className="admin-form-section">
      <div className="admin-form-section-head">
        <h2>{title}</h2>
        {description ? <p>{description}</p> : null}
      </div>
      <div className="admin-form-section-body">{children}</div>
    </section>
  );
}

export function AdminField({
  label,
  span = 6,
  children,
  className,
  htmlFor,
}: {
  label?: string;
  span?: Span;
  children: React.ReactNode;
  className?: string;
  htmlFor?: string;
}) {
  return (
    <div className={`admin-field admin-field-${span}${className ? ` ${className}` : ""}`}>
      {label ? (
        <label className="form-label" htmlFor={htmlFor}>
          {label}
        </label>
      ) : null}
      {children}
    </div>
  );
}

export function AdminFormActions({
  children,
  backHref,
  backLabel = "Cancel",
}: {
  children: React.ReactNode;
  backHref?: string;
  backLabel?: string;
}) {
  return (
    <div className="admin-form-actions">
      <div className="admin-form-actions-inner">
        {backHref ? (
          <a href={backHref} className="admin-btn admin-btn-secondary">
            {backLabel}
          </a>
        ) : null}
        <div className="admin-form-actions-primary">{children}</div>
      </div>
    </div>
  );
}

export function AdminDetailList({
  items,
}: {
  items: { label: string; value: React.ReactNode }[];
}) {
  return (
    <dl className="admin-detail-list">
      {items.map((item) => (
        <div key={item.label} className="admin-detail-item">
          <dt>{item.label}</dt>
          <dd>{item.value == null || item.value === "" ? "—" : item.value}</dd>
        </div>
      ))}
    </dl>
  );
}
