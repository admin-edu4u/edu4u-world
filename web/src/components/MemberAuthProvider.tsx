"use client";

import {
  createContext,
  useCallback,
  useContext,
  useEffect,
  useMemo,
  useState,
  type ReactNode,
} from "react";

export type MemberAuthUser = {
  userId: number;
  email: string;
};

type MemberAuthContextValue = {
  member: MemberAuthUser | null;
  logout: () => Promise<void>;
};

const MemberAuthContext = createContext<MemberAuthContextValue | null>(null);

export function MemberAuthProvider({
  initialMember,
  children,
}: {
  initialMember: MemberAuthUser | null;
  children: ReactNode;
}) {
  const [member, setMember] = useState<MemberAuthUser | null>(initialMember);

  useEffect(() => {
    setMember(initialMember);
  }, [initialMember]);

  useEffect(() => {
    const onLogin = (e: Event) => {
      const detail = (e as CustomEvent<Partial<MemberAuthUser>>).detail;
      if (detail?.userId && detail?.email) {
        setMember({ userId: detail.userId, email: detail.email });
      }
    };
    const onLogout = () => setMember(null);
    window.addEventListener("edu4u:member-login", onLogin);
    window.addEventListener("edu4u:member-logout", onLogout);
    return () => {
      window.removeEventListener("edu4u:member-login", onLogin);
      window.removeEventListener("edu4u:member-logout", onLogout);
    };
  }, []);

  const logout = useCallback(async () => {
    await fetch("/api/logout", { method: "POST", credentials: "include" });
    setMember(null);
    window.dispatchEvent(new CustomEvent("edu4u:member-logout"));
  }, []);

  const value = useMemo(() => ({ member, logout }), [member, logout]);

  return (
    <MemberAuthContext.Provider value={value}>{children}</MemberAuthContext.Provider>
  );
}

export function useMemberAuth() {
  const ctx = useContext(MemberAuthContext);
  if (!ctx) {
    throw new Error("useMemberAuth must be used within MemberAuthProvider");
  }
  return ctx;
}
