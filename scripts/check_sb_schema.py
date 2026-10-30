#!/usr/bin/env python3
import json, urllib.request
from pathlib import Path

REF = "fmlkrtshkvgatzpnymlo"
token = Path("/Users/ben/Documents/Edu4u/.tmp_sb_token.txt").read_text().strip()
API = f"https://api.supabase.com/v1/projects/{REF}/database/query"

def run(q):
    req = urllib.request.Request(
        API,
        data=json.dumps({"query": q}).encode(),
        method="POST",
        headers={"Authorization": f"Bearer {token}", "Content-Type": "application/json"},
    )
    with urllib.request.urlopen(req, timeout=60) as r:
        return r.read().decode()

out = Path("/Users/ben/Documents/Edu4u/.tmp_schema_check.txt")
parts = []
for t in ["app_users", "categories", "blogs", "universities", "subjects", "characters"]:
    body = run(
        f"SELECT column_name, data_type, is_nullable FROM information_schema.columns WHERE table_schema='public' AND table_name='{t}' ORDER BY ordinal_position"
    )
    parts.append(f"=== {t} ===\n{body}\n")
parts.append("=== counts ===\n")
parts.append(
    run(
        "SELECT relname, n_live_tup::int FROM pg_stat_user_tables WHERE schemaname='public' ORDER BY n_live_tup DESC"
    )
)
out.write_text("\n".join(parts))
print("wrote", out)
