"use client";

import { useEffect, useId, useRef } from "react";
import {
  Bold,
  Heading1,
  Heading2,
  Italic,
  Link as LinkIcon,
  List,
  Underline,
} from "lucide-react";

type Command = "bold" | "italic" | "underline" | "insertUnorderedList" | "formatBlock" | "createLink";

function normalizeHtml(html: string): string {
  const trimmed = html.trim();
  if (!trimmed) return "";
  if (/^(<p>(\s|&nbsp;|<br\s*\/?>)*<\/p>)+$/i.test(trimmed)) return "";
  return html;
}

export function AdminRichTextEditor({
  id,
  name,
  value,
  onChange,
  required,
  placeholder = "Start typing…",
}: {
  id?: string;
  name: string;
  value: string;
  onChange: (value: string) => void;
  required?: boolean;
  placeholder?: string;
}) {
  const autoId = useId();
  const fieldId = id || autoId;
  const editorRef = useRef<HTMLDivElement>(null);
  const lastValueRef = useRef(value);

  useEffect(() => {
    const el = editorRef.current;
    if (!el) return;
    if (document.activeElement === el) return;
    if (el.innerHTML === (value || "")) {
      lastValueRef.current = value;
      return;
    }
    el.innerHTML = value || "";
    lastValueRef.current = value;
  }, [value]);

  function emitHtml() {
    const el = editorRef.current;
    if (!el) return;
    const next = normalizeHtml(el.innerHTML);
    lastValueRef.current = next;
    onChange(next);
  }

  function run(command: Command, commandValue?: string) {
    const el = editorRef.current;
    if (!el) return;
    el.focus();
    if (command === "createLink") {
      const url = window.prompt("Enter URL");
      if (!url) return;
      document.execCommand(command, false, url);
    } else if (command === "formatBlock") {
      document.execCommand(command, false, commandValue || "p");
    } else {
      document.execCommand(command, false);
    }
    emitHtml();
  }

  return (
    <div className="admin-richtext">
      <input type="hidden" name={name} value={value} required={required} />
      <div className="admin-richtext-toolbar" role="toolbar" aria-label="Formatting">
        <button type="button" className="admin-richtext-btn" title="Heading 1" onClick={() => run("formatBlock", "h1")}>
          <Heading1 size={14} strokeWidth={2} />
        </button>
        <button type="button" className="admin-richtext-btn" title="Heading 2" onClick={() => run("formatBlock", "h2")}>
          <Heading2 size={14} strokeWidth={2} />
        </button>
        <span className="admin-richtext-sep" aria-hidden />
        <button type="button" className="admin-richtext-btn" title="Bold" onClick={() => run("bold")}>
          <Bold size={14} strokeWidth={2} />
        </button>
        <button type="button" className="admin-richtext-btn" title="Italic" onClick={() => run("italic")}>
          <Italic size={14} strokeWidth={2} />
        </button>
        <button type="button" className="admin-richtext-btn" title="Underline" onClick={() => run("underline")}>
          <Underline size={14} strokeWidth={2} />
        </button>
        <span className="admin-richtext-sep" aria-hidden />
        <button type="button" className="admin-richtext-btn" title="Bullet list" onClick={() => run("insertUnorderedList")}>
          <List size={14} strokeWidth={2} />
        </button>
        <button type="button" className="admin-richtext-btn" title="Link" onClick={() => run("createLink")}>
          <LinkIcon size={14} strokeWidth={2} />
        </button>
      </div>
      <div
        id={fieldId}
        ref={editorRef}
        className="admin-richtext-editor form-control"
        contentEditable
        suppressContentEditableWarning
        role="textbox"
        aria-multiline="true"
        data-placeholder={placeholder}
        onInput={emitHtml}
        onBlur={emitHtml}
      />
    </div>
  );
}
