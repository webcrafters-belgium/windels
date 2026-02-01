# TODO — Windels (PHP Production)

## P0 — Stability (production must stay online)
- [ ] Verify admin root and dashboard still load (200, no fatal).
- [ ] Verify public home, product page, and cart page load.
- [ ] Map which admin tree is canonical: `www/admin/pages/**` vs `www/admin/admin/**`.
- [ ] Remove or guard any admin include that hard-fails on missing DB.

## P1 — Security & secrets
- [ ] Move secrets out of `www/ini.inc` and `www/.env` into deployment-only config.
- [ ] Add `ini.inc.example` and `.env.example` with placeholders (no secrets).
- [ ] Confirm `.gitignore` excludes `.env`, `ini.inc`, and dumps.

## P2 — Dev/test exposure
- [ ] Audit `www/dev/**`, `www/pages/dev/**`, `www/pages/test/**`, `www/API/dev/**`.
- [ ] Add access guards or disable public exposure of dev/test routes.
- [ ] Identify any writable endpoints without auth.

## P3 — Admin cleanup (no feature removals)
- [ ] Inventory admin pages, functions, tools, and links.
- [ ] Align headers/footers and shared styles across admin pages.
- [ ] Document any duplicate pages between `admin/pages` and `admin/admin`.

## P4 — Documentation
- [ ] Update `README.md` with run/deploy notes (PHP-only).
- [ ] Update `www/todo.php` with current admin tasks and risks.
