#!/usr/bin/env python3
"""Parse database/database.sql and upsert all rows into Supabase via REST API."""
from __future__ import annotations

import json
import re
import sys
import time
import urllib.error
import urllib.request
from pathlib import Path

ROOT = Path(__file__).resolve().parents[1]
DUMP = ROOT / "database" / "database.sql"
ENV_PATH = ROOT / "web" / ".env.local"
JSON_DIR = ROOT / "web" / "supabase" / "migrations" / "mysql_json"
LOG_PATH = ROOT / ".tmp_import_sql.txt"

COL_MAP = {"isActive": "isactive", "isAdmin": "isadmin"}
MYSQL_TO_PG = {"users": "app_users"}

NOT_NULL_TEXT = {
    "blogs": {"b_content", "b_img", "b_title"},
    "characters": {"c_desc", "c_name"},
    "course_level_desc": {"c_level_desc"},
    "subjects": {"s_name"},
    "universities": {"u_desc", "u_name", "u_addr", "u_hours", "u_year"},
    "courses": {"c_name", "c_intake", "c_outline"},
}

TABLE_PK = {
    "academics": "a_id",
    "achievements": "a_id",
    "banners": "banner_id",
    "blog_cats": "bc_id",
    "blogs": "b_id",
    "branches": "b_id",
    "career_categories": "cc_id",
    "careers": "c_id",
    "categories": "c_id",
    "characters": "c_id",
    "consult_counsellor": "cc_id",
    "course_fees": "cf_id",
    "course_grades": "cg_id",
    "course_level_desc": "c_level_desc_id",
    "courses": "c_id",
    "enquiries": "e_id",
    "events": "e_id",
    "families": "f_id",
    "full_countries": "country_id",
    "home": "h_id",
    "int_scholarship": "s_id",
    "int_scholarship_discount": "sd_id",
    "international_branches": "int_b_id",
    "international_school": "int_id",
    "personalities": "p_id",
    "pop_up": "pu_id",
    "qualification_level": "q_id",
    "register_interests": "ri_id",
    "results": "r_id",
    "scholarship_discount": "sd_id",
    "scholarships": "s_id",
    "schools": "s_id",
    "states": "state_id",
    "sub_cats": "sc_id",
    "subject_categories": "sc_id",
    "subject_grades": "sg_id",
    "subjects": "s_id",
    "universities": "u_id",
    "university_intakes": "ui_id",
    "university_preferences": "up_id",
    "university_type": "ut_id",
    "user_documents": "ud_id",
    "app_users": "id",
    "visit_campus": "vc_id",
}

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

BATCH = 50


def log(msg: str) -> None:
    line = msg if msg.endswith("\n") else msg + "\n"
    sys.stdout.write(line)
    sys.stdout.flush()
    with LOG_PATH.open("a", encoding="utf-8") as f:
        f.write(line)


def load_env() -> tuple[str, str]:
    url = key = ""
    for line in ENV_PATH.read_text(encoding="utf-8").splitlines():
        if line.startswith("NEXT_PUBLIC_SUPABASE_URL="):
            url = line.split("=", 1)[1].strip()
        elif line.startswith("SUPABASE_SERVICE_ROLE_KEY="):
            key = line.split("=", 1)[1].strip()
    if not url or not key:
        raise SystemExit("Missing Supabase URL or service role key in web/.env.local")
    return url.rstrip("/"), key


def parse_create_columns(sql: str) -> dict[str, list[str]]:
    tables: dict[str, list[str]] = {}
    for m in re.finditer(
        r"CREATE TABLE `([^`]+)`\s*\((.*?)\)\s*ENGINE=",
        sql,
        re.DOTALL | re.IGNORECASE,
    ):
        name = m.group(1)
        body = m.group(2)
        cols = []
        for line in body.splitlines():
            line = line.strip().rstrip(",")
            if not line.startswith("`"):
                continue
            col = line.split("`", 2)[1]
            cols.append(col)
        if cols:
            tables[name] = cols
    return tables


def find_insert_values(sql: str, mysql_table: str) -> str | None:
    needle = f"INSERT INTO `{mysql_table}` VALUES"
    idx = sql.find(needle)
    if idx < 0:
        return None
    pos = idx + len(needle)
    in_str = False
    esc = False
    depth = 0
    while pos < len(sql):
        ch = sql[pos]
        if in_str:
            if esc:
                esc = False
            elif ch == "\\":
                esc = True
            elif ch == "'":
                in_str = False
        else:
            if ch == "'":
                in_str = True
            elif ch == "(":
                depth += 1
            elif ch == ")":
                depth -= 1
            elif ch == ";" and depth == 0:
                return sql[idx + len(needle) : pos]
        pos += 1
    return None


def split_row_tuples(values_blob: str) -> list[str]:
    rows: list[str] = []
    pos = 0
    n = len(values_blob)
    while pos < n:
        if values_blob[pos] != "(":
            pos += 1
            continue
        depth = 0
        i = pos
        in_str = False
        esc = False
        while i < n:
            ch = values_blob[i]
            if in_str:
                if esc:
                    esc = False
                elif ch == "\\":
                    esc = True
                elif ch == "'":
                    in_str = False
            elif ch == "'":
                in_str = True
            elif ch == "(":
                depth += 1
            elif ch == ")":
                depth -= 1
                if depth == 0:
                    rows.append(values_blob[pos : i + 1])
                    pos = i + 1
                    break
            i += 1
        else:
            break
    return rows


def split_fields(inner: str) -> list[str]:
    parts: list[str] = []
    cur: list[str] = []
    in_str = False
    esc = False
    for ch in inner:
        if in_str:
            if esc:
                cur.append(ch)
                esc = False
            elif ch == "\\":
                esc = True
            elif ch == "'":
                in_str = False
            else:
                cur.append(ch)
        elif ch == "'":
            in_str = True
        elif ch == ",":
            parts.append("".join(cur).strip())
            cur = []
        else:
            cur.append(ch)
    parts.append("".join(cur).strip())
    return parts


def coerce_field(raw: str):
    if raw == "NULL" or raw == "null":
        return None
    if re.fullmatch(r"-?\d+", raw):
        return int(raw)
    if re.fullmatch(r"-?\d+\.\d+", raw):
        return float(raw)
    return raw


def row_to_dict(cols: list[str], fields: list[str]) -> dict:
    if len(fields) != len(cols):
        raise ValueError(f"column count {len(cols)} != field count {len(fields)}")
    out = {}
    for c, v in zip(cols, fields):
        out[c] = coerce_field(v)
    return out


def normalize_row(table: str, row: dict) -> dict:
    nn = NOT_NULL_TEXT.get(table, set())
    out = {}
    for k, v in row.items():
        col = COL_MAP.get(k, k)
        if col in nn and v is None:
            v = ""
        out[col] = v
    return out


def upsert_batch(base_url: str, key: str, table: str, pk: str, rows: list[dict]) -> tuple[bool, str]:
    url = f"{base_url}/rest/v1/{table}?on_conflict={pk}"
    data = json.dumps(rows, ensure_ascii=False).encode("utf-8")
    req = urllib.request.Request(
        url,
        data=data,
        method="POST",
        headers={
            "apikey": key,
            "Authorization": f"Bearer {key}",
            "Content-Type": "application/json",
            "Prefer": "resolution=merge-duplicates,return=minimal",
        },
    )
    try:
        with urllib.request.urlopen(req, timeout=120) as r:
            r.read()
        return True, ""
    except urllib.error.HTTPError as e:
        body = e.read().decode("utf-8", errors="replace")
        return False, f"{e.code} {body[:800]}"
    except Exception as e:
        return False, str(e)


def upsert_table(base_url: str, key: str, table: str, rows: list[dict]) -> tuple[int, bool]:
    if not rows:
        return 0, True
    pk = TABLE_PK.get(table)
    if not pk:
        log(f"  no PK for {table}, skip")
        return 0, False
    ok_count = 0
    for i in range(0, len(rows), BATCH):
        batch = rows[i : i + BATCH]
        ok, err = upsert_batch(base_url, key, table, pk, batch)
        if not ok:
            for j, row in enumerate(batch):
                ok1, err1 = upsert_batch(base_url, key, table, pk, [row])
                if ok1:
                    ok_count += 1
                else:
                    log(f"  SKIP {table} row {i+j}: {err1[:300]}")
            continue
        ok_count += len(batch)
        time.sleep(0.05)
    return ok_count, ok_count == len(rows)


def parse_all(sql: str, create_cols: dict[str, list[str]]) -> dict[str, list[dict]]:
    parsed: dict[str, list[dict]] = {}
    for mysql_name, cols in create_cols.items():
        blob = find_insert_values(sql, mysql_name)
        if blob is None:
            continue
        pg_name = MYSQL_TO_PG.get(mysql_name, mysql_name)
        rows = []
        for tup in split_row_tuples(blob):
            inner = tup[1:-1]
            fields = split_fields(inner)
            try:
                row = normalize_row(pg_name, row_to_dict(cols, fields))
            except ValueError as e:
                log(f"parse error {mysql_name}: {e}")
                continue
            rows.append(row)
        if rows:
            if pg_name == "app_users":
                rows = [r for r in rows if int(r.get("isadmin") or 0) != 1]
            parsed[pg_name] = rows
    return parsed


def write_json_exports(data: dict[str, list[dict]]) -> None:
    JSON_DIR.mkdir(parents=True, exist_ok=True)
    manifest = {t: len(rows) for t, rows in data.items()}
    for t, rows in data.items():
        (JSON_DIR / f"{t}.json").write_text(
            json.dumps(rows, ensure_ascii=False, indent=2), encoding="utf-8"
        )
    (JSON_DIR / "manifest.json").write_text(
        json.dumps(manifest, indent=2, sort_keys=True), encoding="utf-8"
    )


def main() -> int:
    LOG_PATH.write_text("", encoding="utf-8")
    if not DUMP.exists():
        log(f"missing dump {DUMP}")
        return 1

    log("Reading SQL dump…")
    sql = DUMP.read_text(encoding="utf-8", errors="replace")
    create_cols = parse_create_columns(sql)
    log(f"CREATE TABLE entries: {len(create_cols)}")

    data = parse_all(sql, create_cols)
    total_rows = sum(len(v) for v in data.values())
    log(f"Parsed {len(data)} tables, {total_rows} rows")
    for t in ORDER:
        if t in data:
            log(f"  {t}: {len(data[t])}")

    write_json_exports(data)
    log(f"Wrote mysql_json + manifest to {JSON_DIR}")

    base_url, key = load_env()

    skip = {"migrations"}
    tables = [t for t in ORDER if t in data and t not in skip]
    for t in sorted(data):
        if t not in tables and t not in skip:
            tables.append(t)

    results: dict[str, tuple[int, bool]] = {}
    for t in tables:
        rows = data[t]
        if t == "app_users":
            rows = [r for r in rows if int(r.get("isadmin") or 0) != 1]
        log(f"Upsert {t} ({len(rows)} rows)…")
        n, ok = upsert_table(base_url, key, t, rows)
        results[t] = (n, ok)
        log(f"  {t}: {n}/{len(rows)} ok={ok}")

    failed = [t for t, (_, ok) in results.items() if not ok]
    log(f"DONE failed={failed}")
    return 1 if failed else 0


if __name__ == "__main__":
    raise SystemExit(main())
