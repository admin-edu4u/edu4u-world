#!/usr/bin/env python3
"""Retry failed tables after rate limits / null coercion."""
from __future__ import annotations

import json
import sys
import time
import urllib.error
import urllib.request
from pathlib import Path

ROOT = Path(__file__).resolve().parents[1]
JSON_DIR = ROOT / "web/supabase/migrations/mysql_json"
TOKEN_PATH = ROOT / ".tmp_sb_token.txt"
LOG_PATH = ROOT / ".tmp_import_retry.txt"
REF = "fmlkrtshkvgatzpnymlo"
API = f"https://api.supabase.com/v1/projects/{REF}/database/query"

COL_MAP = {"isActive": "isactive", "isAdmin": "isadmin"}
QUOTE_COLS = {"character"}
INT_DEFAULTS = {
    "characters": {"c_stream": 0, "c_aver_salary": 0, "c_status": 0},
    "university_intakes": {"ui_status": 1},
    "schools": {},
    "user_documents": {},
}
TEXT_DEFAULTS = {
    "characters": {"c_desc": "", "c_name": "", "c_major": ""},
}


def log(msg: str) -> None:
    line = msg if msg.endswith("\n") else msg + "\n"
    sys.stdout.write(line)
    sys.stdout.flush()
    with LOG_PATH.open("a", encoding="utf-8") as f:
        f.write(line)


def sql_literal(v):
    if v is None:
        return "NULL"
    if isinstance(v, bool):
        return "TRUE" if v else "FALSE"
    if isinstance(v, (int, float)) and not isinstance(v, bool):
        return str(v)
    if isinstance(v, (dict, list)):
        s = json.dumps(v, ensure_ascii=False)
    else:
        s = str(v)
    return "'" + s.replace("'", "''") + "'"


def quote_ident(col: str) -> str:
    if col in QUOTE_COLS or not col.isidentifier():
        return '"' + col.replace('"', '""') + '"'
    return col


def run_sql(query: str, label: str = "", timeout: int = 180) -> tuple[bool, str]:
    token = TOKEN_PATH.read_text().strip()
    req = urllib.request.Request(
        API,
        data=json.dumps({"query": query}).encode(),
        method="POST",
        headers={
            "Authorization": f"Bearer {token}",
            "Content-Type": "application/json",
        },
    )
    for attempt in range(8):
        try:
            with urllib.request.urlopen(req, timeout=timeout) as r:
                return True, r.read().decode()
        except urllib.error.HTTPError as e:
            body = e.read().decode()
            if e.code in (429, 500, 502, 503, 504) and attempt < 7:
                wait = 3 * (attempt + 1)
                log(f"{label} throttle {e.code}, sleep {wait}s")
                time.sleep(wait)
                continue
            log(f"{label} FAIL {e.code} {body[:400]}")
            return False, body
        except Exception as e:
            if attempt < 7:
                time.sleep(2 * (attempt + 1))
                continue
            log(f"{label} ERR {e}")
            return False, str(e)
    return False, "exhausted"


def normalize(table: str, row: dict) -> dict:
    out = {}
    idef = INT_DEFAULTS.get(table, {})
    tdef = TEXT_DEFAULTS.get(table, {})
    for k, v in row.items():
        col = COL_MAP.get(k, k)
        if v is None and col in idef:
            v = idef[col]
        if v is None and col in tdef:
            v = tdef[col]
        out[col] = v
    for col, d in idef.items():
        out.setdefault(col, d)
    for col, d in tdef.items():
        if out.get(col) is None:
            out[col] = d
    return out


def upsert_missing(table: str, pk: str) -> tuple[int, int]:
    rows = [normalize(table, r) for r in json.loads((JSON_DIR / f"{table}.json").read_text())]
    okc, body = run_sql(f'SELECT {pk} FROM "{table}"', f"ids:{table}")
    existing = set()
    if okc:
        try:
            data = json.loads(body)
            existing = {row[pk] for row in data}
        except Exception:
            pass
    missing = [r for r in rows if r.get(pk) not in existing]
    log(f"{table}: have={len(existing)} missing={len(missing)} total={len(rows)}")
    if not missing and len(existing) >= len(rows):
        # full replace if over/under
        if len(existing) != len(rows):
            run_sql(f'TRUNCATE TABLE "{table}" RESTART IDENTITY CASCADE', f"trunc:{table}")
            missing = rows
            existing = set()
        else:
            return len(existing), 0

    # if partial and we want clean: for characters truncate and reload all
    if table == "characters" and (missing or len(existing) != len(rows)):
        run_sql(f'TRUNCATE TABLE "{table}" RESTART IDENTITY CASCADE', f"trunc:{table}")
        missing = rows

    if table in ("university_intakes", "user_documents") and missing:
        # insert only missing
        pass
    elif table == "schools" and missing:
        pass

    inserted = 0
    for i in range(0, len(missing), 25):
        batch = missing[i : i + 25]
        cols = list(batch[0].keys())
        col_sql = ", ".join(quote_ident(c) for c in cols)
        values = []
        for row in batch:
            values.append("(" + ", ".join(sql_literal(row.get(c)) for c in cols) + ")")
        q = f'INSERT INTO "{table}" ({col_sql}) VALUES ' + ", ".join(values)
        time.sleep(0.35)
        ok, body = run_sql(q, f"ins:{table}:{i}")
        if ok:
            inserted += len(batch)
        else:
            for j, row in enumerate(batch):
                time.sleep(0.5)
                q1 = f'INSERT INTO "{table}" ({col_sql}) VALUES (' + ", ".join(
                    sql_literal(row.get(c)) for c in cols
                ) + ")"
                ok1, _ = run_sql(q1, f"row:{table}:{i+j}")
                if ok1:
                    inserted += 1
    return len(existing) + inserted, len(missing) - inserted


def main() -> int:
    LOG_PATH.write_text("", encoding="utf-8")
    log("sleeping 8s to clear throttle")
    time.sleep(8)

    specs = [
        ("characters", "c_id"),
        ("university_intakes", "ui_id"),
        ("schools", "s_id"),
        ("user_documents", "ud_id"),
    ]
    bad = []
    for table, pk in specs:
        have, left = upsert_missing(table, pk)
        log(f"{table} result have~{have} remaining_fail={left}")
        if left:
            bad.append(table)
        time.sleep(2)

    # reset sequences for these
    run_sql(
        """
        SELECT setval(pg_get_serial_sequence('characters', 'c_id'), COALESCE((SELECT MAX(c_id) FROM characters), 1), true);
        SELECT setval(pg_get_serial_sequence('university_intakes', 'ui_id'), COALESCE((SELECT MAX(ui_id) FROM university_intakes), 1), true);
        SELECT setval(pg_get_serial_sequence('schools', 's_id'), COALESCE((SELECT MAX(s_id) FROM schools), 1), true);
        SELECT setval(pg_get_serial_sequence('user_documents', 'ud_id'), COALESCE((SELECT MAX(ud_id) FROM user_documents), 1), true);
        """,
        "seq",
    )
    run_sql("NOTIFY pgrst, 'reload schema'", "reload")

    ok, body = run_sql(
        """
        SELECT json_build_object(
          'characters', (SELECT COUNT(*)::int FROM characters),
          'university_intakes', (SELECT COUNT(*)::int FROM university_intakes),
          'schools', (SELECT COUNT(*)::int FROM schools),
          'user_documents', (SELECT COUNT(*)::int FROM user_documents),
          'universities', (SELECT COUNT(*)::int FROM universities),
          'courses', (SELECT COUNT(*)::int FROM courses),
          'app_users', (SELECT COUNT(*)::int FROM app_users),
          'blogs', (SELECT COUNT(*)::int FROM blogs),
          'categories', (SELECT COUNT(*)::int FROM categories),
          'subjects', (SELECT COUNT(*)::int FROM subjects),
          'results', (SELECT COUNT(*)::int FROM results),
          'total_sample', (
            (SELECT COUNT(*) FROM schools) + (SELECT COUNT(*) FROM results) +
            (SELECT COUNT(*) FROM course_fees) + (SELECT COUNT(*) FROM app_users)
          )
        )
        """,
        "verify",
    )
    log(f"verify {body}")
    log(f"DONE bad={bad}")
    return 1 if bad else 0


if __name__ == "__main__":
    raise SystemExit(main())
