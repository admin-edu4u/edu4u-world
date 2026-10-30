#!/usr/bin/env python3
"""Import mysql_json/*.json into Supabase via Management SQL API."""
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
LOG_PATH = ROOT / ".tmp_import_sql.txt"
REF = "fmlkrtshkvgatzpnymlo"
API = f"https://api.supabase.com/v1/projects/{REF}/database/query"

# DB folded unquoted camelCase to lowercase; reserved words need quotes
COL_MAP = {
    "isActive": "isactive",
    "isAdmin": "isadmin",
}
QUOTE_COLS = {"character"}

# NOT NULL text columns: coerce null -> empty string
NOT_NULL_TEXT = {
    "blogs": {"b_content", "b_img", "b_title"},
    "characters": {"c_desc", "c_name"},
    "course_level_desc": {"c_level_desc"},
    "subjects": {"s_name"},
    "universities": {"u_desc", "u_name", "u_addr", "u_hours", "u_year"},
    "courses": {"c_name", "c_intake", "c_outline"},
}

# Import order: parents before children where FKs may exist (none enforced yet, but keep sensible)
ORDER = [
    "full_countries",
    "states",
    "university_type",
    "qualification_level",
    "categories",
    "blog_cats",
    "career_categories",
    "subject_categories",
    "personalities",
    "characters",
    "banners",
    "home",
    "pop_up",
    "universities",
    "branches",
    "university_intakes",
    "courses",
    "course_fees",
    "course_grades",
    "course_level_desc",
    "subjects",
    "subject_grades",
    "sub_cats",
    "scholarships",
    "scholarship_discount",
    "int_scholarship",
    "int_scholarship_discount",
    "international_school",
    "international_branches",
    "schools",
    "careers",
    "blogs",
    "events",
    "app_users",
    "academics",
    "achievements",
    "families",
    "results",
    "university_preferences",
    "user_documents",
    "enquiries",
    "register_interests",
    "consult_counsellor",
    "visit_campus",
]

BATCH = 40


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
    s = s.replace("'", "''")
    return "'" + s + "'"


def quote_ident(col: str) -> str:
    if col in QUOTE_COLS or col in {"character"} or not col.isidentifier():
        return '"' + col.replace('"', '""') + '"'
    return col


def normalize_row(table: str, row: dict) -> dict:
    out = {}
    nn = NOT_NULL_TEXT.get(table, set())
    for k, v in row.items():
        col = COL_MAP.get(k, k)
        if col in nn and v is None:
            v = ""
        out[col] = v
    return out


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
    for attempt in range(4):
        try:
            with urllib.request.urlopen(req, timeout=timeout) as r:
                body = r.read().decode()
                return True, body
        except urllib.error.HTTPError as e:
            body = e.read().decode()
            if e.code in (429, 500, 502, 503, 504) and attempt < 3:
                time.sleep(1.5 * (attempt + 1))
                continue
            log(f"{label} FAIL {e.code} {body[:500].replace(chr(10), ' ')}")
            return False, body
        except Exception as e:
            if attempt < 3:
                time.sleep(1.5 * (attempt + 1))
                continue
            log(f"{label} ERR {type(e).__name__}: {e}")
            return False, str(e)
    return False, "retries exhausted"


def truncate_all(tables: list[str]) -> bool:
    parts = [f'TRUNCATE TABLE "{t}" RESTART IDENTITY CASCADE' for t in tables]
    # truncate in one statement may fail on missing; do groups
    ok, _ = run_sql(";".join(parts), "truncate")
    return ok


def insert_table(table: str, rows: list[dict]) -> tuple[int, bool]:
    if not rows:
        return 0, True
    cols = list(rows[0].keys())
    col_sql = ", ".join(quote_ident(c) for c in cols)
    inserted = 0
    for i in range(0, len(rows), BATCH):
        batch = rows[i : i + BATCH]
        values = []
        for row in batch:
            vals = ", ".join(sql_literal(row.get(c)) for c in cols)
            values.append(f"({vals})")
        q = f'INSERT INTO "{table}" ({col_sql}) VALUES ' + ", ".join(values)
        ok, body = run_sql(q, f"ins:{table}:{i}")
        if not ok:
            # retry row-by-row to isolate bad rows
            for j, row in enumerate(batch):
                vals = ", ".join(sql_literal(row.get(c)) for c in cols)
                q1 = f'INSERT INTO "{table}" ({col_sql}) VALUES ({vals})'
                ok1, body1 = run_sql(q1, f"row:{table}:{i+j}")
                if ok1:
                    inserted += 1
                else:
                    log(f"  SKIP {table} row {i+j}: {body1[:200].replace(chr(10), ' ')}")
            continue
        inserted += len(batch)
    return inserted, inserted == len(rows)


def main() -> int:
    LOG_PATH.write_text("", encoding="utf-8")
    if not TOKEN_PATH.exists():
        log("missing token file")
        return 1

    manifest = json.loads((JSON_DIR / "manifest.json").read_text())
    tables = [t for t in ORDER if t in manifest]
    for t in sorted(manifest):
        if t not in tables:
            tables.append(t)

    log(f"tables {len(tables)} expected_rows {sum(manifest.values())}")

    # ensure schema cache / quote fix: recreate camelCase cols if needed is skip;
    # reload postgrest after
    ok, body = run_sql(
        "SELECT relname AS table, n_live_tup::int AS est FROM pg_stat_user_tables WHERE schemaname='public' ORDER BY relname",
        "counts_before",
    )
    log(f"counts_before ok={ok} {body[:300]}")

    # Truncate known tables
    trunc_list = tables[:]
    if not truncate_all(trunc_list):
        # fallback one-by-one
        for t in trunc_list:
            run_sql(f'TRUNCATE TABLE "{t}" RESTART IDENTITY CASCADE', f"trunc:{t}")

    results = {}
    for t in tables:
        path = JSON_DIR / f"{t}.json"
        if not path.exists():
            log(f"MISSING json {t}")
            results[t] = (0, False)
            continue
        raw = json.loads(path.read_text())
        rows = [normalize_row(t, r) for r in raw]
        n, all_ok = insert_table(t, rows)
        exp = manifest.get(t, len(rows))
        results[t] = (n, all_ok and n == exp)
        log(f"{t}: {n}/{exp} ok={results[t][1]}")

    # sequences
    seq_sql = (ROOT / ".tmp_sql_chunks/99_sequences.sql").read_text()
    run_sql(seq_sql.replace("\n", " "), "sequences")

    run_sql("NOTIFY pgrst, 'reload schema'", "reload")

    ok, body = run_sql(
        """
        SELECT json_object_agg(c.table_name, c.cnt) FROM (
          SELECT 'academics' AS table_name, COUNT(*)::int AS cnt FROM academics
          UNION ALL SELECT 'achievements', COUNT(*)::int FROM achievements
          UNION ALL SELECT 'app_users', COUNT(*)::int FROM app_users
          UNION ALL SELECT 'blogs', COUNT(*)::int FROM blogs
          UNION ALL SELECT 'categories', COUNT(*)::int FROM categories
          UNION ALL SELECT 'characters', COUNT(*)::int FROM characters
          UNION ALL SELECT 'courses', COUNT(*)::int FROM courses
          UNION ALL SELECT 'course_fees', COUNT(*)::int FROM course_fees
          UNION ALL SELECT 'course_level_desc', COUNT(*)::int FROM course_level_desc
          UNION ALL SELECT 'results', COUNT(*)::int FROM results
          UNION ALL SELECT 'schools', COUNT(*)::int FROM schools
          UNION ALL SELECT 'subjects', COUNT(*)::int FROM subjects
          UNION ALL SELECT 'universities', COUNT(*)::int FROM universities
          UNION ALL SELECT 'university_preferences', COUNT(*)::int FROM university_preferences
          UNION ALL SELECT 'scholarships', COUNT(*)::int FROM scholarships
          UNION ALL SELECT 'personalities', COUNT(*)::int FROM personalities
        ) c
        """,
        "verify",
    )
    log(f"verify {body[:800]}")

    failed = [t for t, (n, ok) in results.items() if not ok]
    log(f"DONE failed={failed}")
    return 1 if failed else 0


if __name__ == "__main__":
    raise SystemExit(main())
