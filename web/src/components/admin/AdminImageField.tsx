"use client";

import { useEffect, useId, useRef, useState } from "react";
import { ImagePlus, Trash2 } from "lucide-react";
import { mediaUrl } from "@/lib/admin/media";
import { AdminField } from "./AdminForm";

function resolveImageSrc(folder: string | undefined, filename: string) {
  if (filename.startsWith("http://") || filename.startsWith("https://")) return filename;
  if (filename.includes("/")) return `https://edu4u.world/${filename.replace(/^\/+/, "")}`;
  if (folder) return mediaUrl(folder, filename);
  return `https://edu4u.world/images/${filename}`;
}

type ExistingItem = { id: string; kind: "existing"; filename: string; src: string };
type NewItem = { id: string; kind: "new"; file: File; src: string };
type Item = ExistingItem | NewItem;

export function AdminImageField({
  name,
  label,
  folder,
  filename,
  multiple = false,
  span = 6,
  className,
}: {
  name: string;
  label: string;
  folder?: string;
  filename?: string | null;
  multiple?: boolean;
  span?: 3 | 4 | 6 | 8 | 12;
  className?: string;
}) {
  const inputId = useId();
  const inputRef = useRef<HTMLInputElement>(null);
  const replaceIndexRef = useRef<number | null>(null);
  const [allowMultiPick, setAllowMultiPick] = useState(multiple);

  const initial = String(filename || "")
    .split(",")
    .map((s) => s.trim())
    .filter(Boolean)
    .map(
      (file, index): ExistingItem => ({
        id: `existing-${index}-${file}`,
        kind: "existing",
        filename: file,
        src: resolveImageSrc(folder, file),
      }),
    );

  const [items, setItems] = useState<Item[]>(initial);
  const [removed, setRemoved] = useState(false);

  useEffect(() => {
    return () => {
      items.forEach((item) => {
        if (item.kind === "new") URL.revokeObjectURL(item.src);
      });
    };
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, []);

  function openPicker(replaceIndex: number | null = null) {
    replaceIndexRef.current = replaceIndex;
    setAllowMultiPick(multiple && replaceIndex == null);
    queueMicrotask(() => {
      if (inputRef.current) {
        inputRef.current.value = "";
        inputRef.current.click();
      }
    });
  }

  function onFilesSelected(fileList: FileList | null) {
    if (!fileList?.length) return;
    const files = Array.from(fileList).filter((f) => f.type.startsWith("image/"));
    if (!files.length) return;

    setItems((prev) => {
      const next = [...prev];
      const replaceIndex = replaceIndexRef.current;

      if (!multiple) {
        next.forEach((item) => {
          if (item.kind === "new") URL.revokeObjectURL(item.src);
        });
        const file = files[0]!;
        return [
          {
            id: `new-${Date.now()}`,
            kind: "new",
            file,
            src: URL.createObjectURL(file),
          },
        ];
      }

      if (replaceIndex != null && replaceIndex >= 0 && replaceIndex < next.length) {
        const old = next[replaceIndex]!;
        if (old.kind === "new") URL.revokeObjectURL(old.src);
        const file = files[0]!;
        next[replaceIndex] = {
          id: `new-${Date.now()}`,
          kind: "new",
          file,
          src: URL.createObjectURL(file),
        };
        return next;
      }

      for (const file of files) {
        next.push({
          id: `new-${Date.now()}-${file.name}-${Math.random().toString(36).slice(2, 6)}`,
          kind: "new",
          file,
          src: URL.createObjectURL(file),
        });
      }
      return next;
    });
    setRemoved(false);
    replaceIndexRef.current = null;
  }

  function removeAt(index: number) {
    setItems((prev) => {
      const next = [...prev];
      const old = next[index];
      if (old?.kind === "new") URL.revokeObjectURL(old.src);
      next.splice(index, 1);
      if (next.length === 0) setRemoved(true);
      return next;
    });
  }

  const keepExisting = items
    .filter((item): item is ExistingItem => item.kind === "existing")
    .map((item) => item.filename);
  const newFiles = items.filter((item): item is NewItem => item.kind === "new");
  const showEmpty = items.length === 0;

  return (
    <AdminField label={label} span={span} className={className}>
      <input type="hidden" name={`${name}_remove`} value={removed && newFiles.length === 0 ? "1" : "0"} />
      <input type="hidden" name={`${name}_keep`} value={keepExisting.join(",")} />

      <input
        ref={inputRef}
        id={inputId}
        type="file"
        accept="image/*"
        multiple={allowMultiPick}
        className="admin-image-input"
        onChange={(e) => onFilesSelected(e.target.files)}
      />

      <div className={`admin-image-uploader${multiple ? " is-multiple" : ""}`}>
        {items.map((item, index) => (
          <div key={item.id} className="admin-image-tile">
            <button
              type="button"
              className="admin-image-tile-preview"
              onClick={() => openPicker(index)}
              title="Replace image"
            >
              {/* eslint-disable-next-line @next/next/no-img-element */}
              <img src={item.src} alt="" />
              <span className="admin-image-tile-overlay">Replace</span>
            </button>
            <button
              type="button"
              className="admin-image-tile-remove"
              onClick={() => removeAt(index)}
              aria-label="Remove image"
              title="Remove"
            >
              <Trash2 size={14} strokeWidth={1.75} aria-hidden />
            </button>
          </div>
        ))}

        {showEmpty || multiple ? (
          <button
            type="button"
            className={`admin-image-add${showEmpty ? " is-empty" : ""}`}
            onClick={() => openPicker(null)}
          >
            <ImagePlus size={18} strokeWidth={1.75} aria-hidden />
            <span>{showEmpty ? "Click to upload" : "Add image"}</span>
          </button>
        ) : null}
      </div>

      {newFiles.map((item) => (
        <NewFileCarrier key={item.id} name={name} file={item.file} />
      ))}
    </AdminField>
  );
}

function NewFileCarrier({ name, file }: { name: string; file: File }) {
  const ref = useRef<HTMLInputElement>(null);
  useEffect(() => {
    const input = ref.current;
    if (!input) return;
    const dt = new DataTransfer();
    dt.items.add(file);
    input.files = dt.files;
  }, [file]);
  return <input ref={ref} type="file" name={name} className="admin-image-input" tabIndex={-1} aria-hidden />;
}
