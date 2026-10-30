#!/usr/bin/env python3
import json, urllib.request
from pathlib import Path

ROOT = Path("/Users/ben/Documents/Edu4u")
manifest = json.loads((ROOT / "web/supabase/migrations/mysql_json/manifest.json").read_text())
token = (ROOT / ".tmp_sb_token.txt").read_text().strip()
API = "https://api.supabase.com/v1/projects/fmlkrtshkvgatzpnymlo/database/query"

unions = " UNION ALL ".join(
    f"SELECT '{t}' AS t, COUNT(*)::int AS c FROM \"{t}\"" for t in sorted(manifest)
)
q = f"SELECT json_object_agg(t, c) FROM ({unions}) x"
req = urllib.request.Request(
    API,
    data=json.dumps({"query": q}).encode(),
    method="POST",
    headers={"Authorization": f"Bearer {token}", "Content-Type": "application/json"},
)
with urllib.request.urlopen(req, timeout=120) as r:
    body = json.loads(r.read().decode())
counts = body[0]["json_object_agg"]
mismatches = []
ok = 0
for t, exp in sorted(manifest.items()):
    got = counts.get(t, 0)
    if got == exp:
        ok += 1
    else:
        mismatches.append(f"{t}: got={got} expected={exp}")
out = ROOT / ".tmp_verify_counts.txt"
lines = [
    f"ok {ok}/{len(manifest)}",
    f"total_rows {sum(counts.values())} expected {sum(manifest.values())}",
]
if mismatches:
    lines.append("MISMATCHES:")
    lines.extend(mismatches)
else:
    lines.append("ALL MATCH")
out.write_text("\n".join(lines) + "\n")
print("\n".join(lines))
