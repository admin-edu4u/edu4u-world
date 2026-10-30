"use client";

import { FormEvent, Suspense, useState } from "react";
import { useRouter, useSearchParams } from "next/navigation";

function SignInForm() {
  const router = useRouter();
  const params = useSearchParams();
  const [error, setError] = useState("");
  const [loading, setLoading] = useState(false);

  async function onSubmit(e: FormEvent<HTMLFormElement>) {
    e.preventDefault();
    setError("");
    setLoading(true);
    const fd = new FormData(e.currentTarget);
    try {
      const res = await fetch("/api/admin/login", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
          username: fd.get("username"),
          password: fd.get("password"),
        }),
      });
      const data = await res.json();
      if (!res.ok) {
        setError(data.error || "Invalid credentials");
        setLoading(false);
        return;
      }
      router.push(params.get("next") || "/admin/dashboard");
      router.refresh();
    } catch {
      setError("Login failed");
      setLoading(false);
    }
  }

  return (
    <div className="admin-root admin-login">
      <div className="admin-login-card">
        <div className="admin-login-brand">
          {/* eslint-disable-next-line @next/next/no-img-element */}
          <img src="/images/logo/edu4ulogo.jpg" alt="Edu4u" width={132} height={38} />
          <h1>Admin portal</h1>
          <p>Sign in with your admin username and password</p>
        </div>
        {error ? <div className="admin-alert">{error}</div> : null}
        <form onSubmit={onSubmit}>
          <label htmlFor="admin-username">Username</label>
          <input
            id="admin-username"
            className="form-control"
            type="text"
            name="username"
            required
            autoComplete="username"
          />
          <label htmlFor="admin-password">Password</label>
          <input
            id="admin-password"
            className="form-control"
            type="password"
            name="password"
            required
            autoComplete="current-password"
          />
          <button type="submit" className="admin-btn admin-btn-primary" disabled={loading}>
            {loading ? "Signing in…" : "Sign in"}
          </button>
        </form>
      </div>
    </div>
  );
}

export default function AdminSignInPage() {
  return (
    <Suspense
      fallback={
        <div className="admin-root admin-login">
          <div className="admin-login-card">Loading…</div>
        </div>
      }
    >
      <SignInForm />
    </Suspense>
  );
}
