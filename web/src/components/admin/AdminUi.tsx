import Link from "next/link";
import { mediaUrl } from "@/lib/admin/media";

export { AdminRow } from "./AdminRow";
export { AdminIconLink } from "./AdminIconLink";
export { AdminStatusTabs } from "./AdminStatusTabs";
export { AdminStatusActions } from "./AdminStatusActions";
export { AdminPagination } from "./AdminPagination";
export {
  AdminSelect,
  AdminStatusSelect,
  AdminYesNoSelect,
  AdminImageField,
} from "./AdminFormFields";
export { AdminLocalizedField } from "./AdminLocalizedField";
export {
  AdminForm,
  AdminFormSection,
  AdminField,
  AdminFormActions,
  AdminDetailList,
} from "./AdminForm";
export {
  formatAdminDateTime,
  formatAdminMoney,
  formatAdminMoneyRange,
  formatAdminPhone,
  formatYesNo,
  formatStream,
  formatPersonalityType,
  formatVisitTime,
  formatVisitDate,
  labelFromMap,
  labelsFromCsvIds,
  resolveCsvIdLabels,
  VISIT_TIME_LABELS,
} from "@/lib/admin/format";

export function AdminPageHeader({
  title,
  description,
  actions,
}: {
  title: string;
  description?: string;
  actions?: React.ReactNode;
}) {
  return (
    <div className="admin-page-header">
      <div>
        <h1>{title}</h1>
        {description ? <p>{description}</p> : null}
      </div>
      {actions ? <div className="admin-actions">{actions}</div> : null}
    </div>
  );
}

export function AdminCard({ children }: { children: React.ReactNode }) {
  return <div className="admin-card">{children}</div>;
}

export function AdminThumb({
  folder,
  filename,
  alt = "",
}: {
  folder?: string;
  filename?: string | null;
  alt?: string;
}) {
  const raw = filename?.split(",")[0]?.trim() || "";
  let src = "";
  if (raw) {
    if (raw.startsWith("http://") || raw.startsWith("https://")) {
      src = raw;
    } else if (raw.includes("/")) {
      src = `https://edu4u.world/${raw.replace(/^\/+/, "")}`;
    } else if (folder) {
      src = mediaUrl(folder, raw);
    } else {
      src = `https://edu4u.world/images/${raw}`;
    }
  }
  return (
    <span className="admin-thumb">
      {src ? (
        // eslint-disable-next-line @next/next/no-img-element
        <img src={src} alt={alt} />
      ) : (
        <span className="admin-thumb-empty" aria-hidden />
      )}
    </span>
  );
}

function adminTableHeaderClass(label: string, index: number, total: number) {
  const key = label.trim().toLowerCase();
  if (!key && index === 0) return "admin-table-thumb";
  if (!key && index === total - 1) return "admin-table-actions";
  if (key === "created" || key === "updated") return "admin-table-date";
  if (key === "status") return "admin-table-status";
  if (key === "order") return "admin-table-order";
  if (key === "ic") return "admin-table-ic";
  if (key === "contact" || key === "phone") return "admin-table-contact";
  if (key === "date" || key === "time") return "admin-table-nowrap";
  if (key === "qualification") return "admin-table-tags";
  if (key === "level" || key === "grade") return "admin-table-nowrap";
  if (key === "name" || key === "title" || key === "user" || key === "url" || key === "school") {
    return "admin-table-primary";
  }
  if (
    key === "school" ||
    key === "university" ||
    key === "programme" ||
    key === "message" ||
    key === "course"
  ) {
    return "admin-table-nowrap";
  }
  return undefined;
}

export function AdminTable({
  headers,
  children,
}: {
  headers: string[];
  children: React.ReactNode;
}) {
  return (
    <div className="admin-table-wrap">
      <table className="admin-table">
        <thead>
          <tr>
            {headers.map((h, i) => (
              <th key={`${h}-${i}`} className={adminTableHeaderClass(h, i, headers.length)}>
                {h}
              </th>
            ))}
          </tr>
        </thead>
        <tbody>{children}</tbody>
      </table>
    </div>
  );
}

export function AdminBtn({
  href,
  children,
  variant = "primary",
}: {
  href: string;
  children: React.ReactNode;
  variant?: "primary" | "secondary" | "danger" | "light";
}) {
  return (
    <Link href={href} className={`admin-btn admin-btn-${variant}`}>
      {children}
    </Link>
  );
}

export function EmptyState({ message }: { message: string }) {
  return <p className="admin-empty">{message}</p>;
}
