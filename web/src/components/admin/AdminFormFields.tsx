import { STATUS } from "@/lib/admin/status";
import { parseCsvIds } from "@/lib/admin/constants";
import { SearchableSelect, type SearchableOption } from "@/components/ui/SearchableSelect";
import { AdminField } from "./AdminForm";

export { AdminImageField } from "./AdminImageField";

export function AdminSelect({
  name,
  options,
  defaultValue,
  placeholder = "Select...",
  required,
  emptyLabel,
  className,
  multiple = false,
}: {
  name: string;
  options: SearchableOption[];
  defaultValue?: string | number | string[] | null;
  placeholder?: string;
  required?: boolean;
  emptyLabel?: string;
  className?: string;
  multiple?: boolean;
}) {
  const resolvedDefault = Array.isArray(defaultValue)
    ? defaultValue.map(String)
    : typeof defaultValue === "string" && (defaultValue.includes(",") || /^\d+$/.test(defaultValue.trim()))
      ? parseCsvIds(defaultValue)
      : defaultValue == null
        ? multiple
          ? []
          : ""
        : String(defaultValue);

  return (
    <SearchableSelect
      name={name}
      options={options.filter((option) => String(option.label || "").trim())}
      defaultValue={resolvedDefault}
      placeholder={placeholder}
      required={required}
      emptyLabel={emptyLabel}
      className={className}
      inputClassName="form-select"
      multiple={multiple}
    />
  );
}

export function AdminStatusSelect({
  name,
  value,
  span = 4,
  className,
}: {
  name: string;
  value?: number | null;
  span?: 3 | 4 | 6 | 8 | 12;
  className?: string;
}) {
  return (
    <AdminField label="Status" span={span} className={className}>
      <AdminSelect
        name={name}
        defaultValue={value ?? STATUS.ACTIVE}
        placeholder="Select status..."
        options={[
          { value: String(STATUS.ACTIVE), label: "Active" },
          { value: String(STATUS.INACTIVE), label: "Inactive" },
          { value: String(STATUS.ARCHIVED), label: "Archived" },
        ]}
      />
    </AdminField>
  );
}

export function AdminYesNoSelect({
  name,
  label,
  value,
  span = 4,
  className,
}: {
  name: string;
  label: string;
  value?: number | null;
  span?: 3 | 4 | 6 | 8 | 12;
  className?: string;
}) {
  return (
    <AdminField label={label} span={span} className={className}>
      <AdminSelect
        name={name}
        defaultValue={Number(value) ? 1 : 0}
        placeholder={`Select ${label.toLowerCase()}...`}
        options={[
          { value: "0", label: "No" },
          { value: "1", label: "Yes" },
        ]}
      />
    </AdminField>
  );
}

