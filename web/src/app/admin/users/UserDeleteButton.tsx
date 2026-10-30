"use client";

import { useRouter } from "next/navigation";
import { DeleteButton } from "@/components/admin/DeleteButton";
import { deleteUser } from "./actions";

export function UserDeleteButton({ userId }: { userId: number }) {
  const router = useRouter();
  return (
    <DeleteButton
      action={async () => {
        await deleteUser(userId);
        router.push("/admin/users");
        router.refresh();
      }}
      label="Delete user"
      icon="delete"
      confirmText="Permanently delete this user and all profile data? This cannot be undone."
    />
  );
}
