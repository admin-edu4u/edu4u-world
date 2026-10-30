/**
 * Upsert full `characters` rows from database/database.sql into Supabase.
 * Run from web/: node scripts/sync-characters-from-sql-dump.mjs
 */
import fs from "fs";
import path from "path";
import { createClient } from "@supabase/supabase-js";

function loadEnv(filePath) {
  if (!fs.existsSync(filePath)) return;
  for (const line of fs.readFileSync(filePath, "utf8").split("\n")) {
    const m = line.match(/^([^#=]+)=(.*)$/);
    if (m) process.env[m[1].trim()] = m[2].trim();
  }
}

function parseCharactersFromDump(sql) {
  const idx = sql.indexOf("INSERT INTO `characters` VALUES");
  if (idx < 0) throw new Error("characters INSERT not found in dump");
  const chunk = sql.slice(idx);
  const end = chunk.indexOf(";\n");
  const vals = chunk.slice("INSERT INTO `characters` VALUES".length, end);

  const rows = [];
  let pos = 0;
  while (pos < vals.length) {
    if (vals[pos] !== "(") {
      pos += 1;
      continue;
    }
    let depth = 0;
    let i = pos;
    let inStr = false;
    let esc = false;
    while (i < vals.length) {
      const ch = vals[i];
      if (inStr) {
        if (esc) esc = false;
        else if (ch === "\\") esc = true;
        else if (ch === "'") inStr = false;
      } else if (ch === "'") inStr = true;
      else if (ch === "(") depth += 1;
      else if (ch === ")") {
        depth -= 1;
        if (depth === 0) {
          rows.push(vals.slice(pos, i + 1));
          pos = i + 1;
          break;
        }
      }
      i += 1;
    }
    if (i >= vals.length) break;
  }

  return rows
    .map((row) => {
      const inner = row.slice(1, -1);
      const parts = [];
      let cur = "";
      let inStr = false;
      let esc = false;
      for (const ch of inner) {
        if (inStr) {
          if (esc) {
            cur += ch;
            esc = false;
          } else if (ch === "\\") esc = true;
          else if (ch === "'") inStr = false;
          else cur += ch;
        } else if (ch === "'") inStr = true;
        else if (ch === ",") {
          parts.push(cur.trim());
          cur = "";
        } else cur += ch;
      }
      parts.push(cur.trim());
      if (parts.length < 11) return null;
      return {
        c_id: Number(parts[0]),
        c_name: parts[1],
        c_major: parts[2] || null,
        c_aver_salary: Number(parts[3]) || 0,
        c_desc: parts[4],
        c_img_male: parts[5] || null,
        c_img_female: parts[6] || null,
        c_stream: Number(parts[7]) || 0,
        c_status: Number(parts[8]) || 1,
        created_at: parts[9] === "NULL" ? null : parts[9],
        updated_at: parts[10] === "NULL" ? null : parts[10],
      };
    })
    .filter(Boolean);
}

loadEnv(path.join(process.cwd(), ".env.local"));
const url = process.env.NEXT_PUBLIC_SUPABASE_URL;
const key = process.env.SUPABASE_SERVICE_ROLE_KEY;
if (!url || !key) {
  console.error("Missing NEXT_PUBLIC_SUPABASE_URL or SUPABASE_SERVICE_ROLE_KEY in .env.local");
  process.exit(1);
}

const dumpPath = path.join(process.cwd(), "..", "database", "database.sql");
const sql = fs.readFileSync(dumpPath, "utf8");
const characters = parseCharactersFromDump(sql);
const supabase = createClient(url, key);

const batchSize = 25;
let upserted = 0;
for (let i = 0; i < characters.length; i += batchSize) {
  const batch = characters.slice(i, i + batchSize);
  const { error } = await supabase.from("characters").upsert(batch, { onConflict: "c_id" });
  if (error) {
    console.error(error.message);
    process.exit(1);
  }
  upserted += batch.length;
}
console.log(`Upserted ${upserted} characters.`);
