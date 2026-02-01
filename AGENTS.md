# AGENTS — Windels (Final PHP Site)

## Direction (non-negotiable)
- PHP-only. No React, no Tailwind, no npm.
- Production-first: /www must keep working at all times.
- No feature removals; only fixes, refactors, or restorations.
- No secrets in git. Use ini.inc or environment and keep .env out of commits.

## Repo layout (current)
- `www/` — production PHP site (storefront + admin + API + functions).
- `backup/`, `tmp/` — local-only working copies, not production.
- `subdomains/` — local staging/legacy; not used for production.
- Root `todo.md` / `done.md` — project plan and verified completions.

## Key entrypoints
- Public site: `www/index.php`, `www/header.php`, `www/footer.php`
- Routing: `www/.htaccess` (slug routing + error docs)
- Config: `www/ini.inc` (DB + runtime config), `www/.env` (local only)
- Admin entry: `www/admin/index.php` -> dashboard
- Admin dashboard: `www/admin/pages/dashboard/index.php`
- Admin include shell: `www/admin/includes/header.php`, `www/admin/includes/footer.php`
- Admin config: `www/admin/config.php` (wraps ini.inc and graceful DB fallback)
- API: `www/API/**`
- Business logic: `www/functions/**`
- Emails: `www/templates/**`

## Admin structure
- Current admin UI is under `www/admin/pages/**`.
- There is also a legacy/duplicate tree under `www/admin/admin/**`.
- Do not remove anything until paths are mapped and verified.

## Rules for changes
- Always identify the exact file + error before changing anything.
- Minimal patches only; never drop functionality.
- After each logical change: verify the relevant URL and note evidence.
- Do not use npm or any frontend build tooling.

## Verification checklist (per change)
1) Admin URL opens (200) and renders without fatal errors.
2) Affected admin page loads and actions do not fatally error.
3) Public pages still load (home + one product page).

## Current known risks
- Secrets are stored in `www/ini.inc` and `www/.env` exists in repo.
- Duplicate admin trees (`www/admin/pages` vs `www/admin/admin`) can drift.
- Dev/test endpoints live under `www/dev` and `www/pages/dev` and `www/pages/test`.

## What this file is for
- Describe structure and rules so maintenance is consistent.
- Track how folders are intended to be used.
- Keep the repo production-safe and PHP-only.
