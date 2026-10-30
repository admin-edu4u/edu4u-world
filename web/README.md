# Edu4u (Next.js)

Next.js rewrite of the Edu4u Laravel app, backed by Supabase and deployable to Cloudflare.

## Stack

- Next.js App Router (`web/`)
- Supabase Postgres + Auth (project `fmlkrtshkvgatzpnymlo`)
- Existing Metronic CSS/assets under `public/assets`
- Cloudflare Pages via OpenNext (`wrangler.toml`)

## Local setup

```bash
cd web
cp .env.example .env.local   # fill Supabase keys
npm install
npm run dev
```

## Database

Core schema: `supabase/migrations/` (applied to project `fmlkrtshkvgatzpnymlo`).

Full MySQL dump data lives in `../database/database.sql`. To re-sync Supabase from that file (uses `web/.env.local` service role):

```bash
python3 ../scripts/import_database_sql_to_supabase.py
```

This parses the dump, refreshes `supabase/migrations/mysql_json/`, and upserts all application tables (skips Laravel `migrations`).

## Cloudflare

```bash
npm i -D @opennextjs/cloudflare wrangler
npx opennextjs-cloudflare build
npx wrangler pages deploy
```

Point `edu4u.world` DNS (Squarespace) at Cloudflare when ready.
