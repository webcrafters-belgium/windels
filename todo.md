# TODO

## Phase 1 – Inventory & Analysis
1. Document `admin/` inside `htdocs/` (screens, PHP templates, CSS, JS, API hooks) and note what needs porting to React.
2. Catalog `pages/`, `partials/`, and `templates/` to understand current storefront layout relationships.
3. Capture shared helpers and configs (`classes/`, `functions/`, `config/`, `ini.inc`, `.env`) that power public pages.
4. List static assets (`css/`, `styles/`, `assets/`, `fonts/`, `images/`) necessary for the brand’s look and note whether they belong in the new React build or stay as legacy resources.
5. Review `API/` endpoints, `py/` utilities, and `dev/` tools to decide what should be preserved or re-implemented.
6. Document `www/` inventory and migration plan in `AGENTS.md` (completed 2026-01-28).

## Phase 2 – React/Tailwind Migration
7. Begin rebuilding `admin/` as a React+Tailwind SPA under `subdomains/matthias/htdocs`, defining routes, components, and the API layer.
8. Sketch new React-based storefront that mirrors `index.php`/`pages/`, referencing data feeds and assets needed from the backend.
9. Establish backend strategy: keep select PHP/Laravel endpoints, or wrap them in a new Laravel API that feeds the React frontend.
10. Plan Tailwind migration: capture color/typography tokens, define utility classes, and decide which legacy CSS files to retire or port.
11. Define build/deploy steps for the new subdomain (React build command, asset syncing, proxies for PHP APIs, environment alignment).
12. Scaffold the Vite+React+Tailwind workspace in `subdomains/matthias` (completed).

## Phase 3 – Verification & Documentation 
13. Create QA checklists per folder (visual diff with legacy site, API contract verification, performance assertions).
14. Set up `done.md` entries per folder once React replacements are verified, noting what was migrated and any remaining backend ties.
15. Track ongoing blockers (missing DB schema, unknown API) and log what info is needed in future updates.
16. Audit legacy PHP for hard-coded secrets (contact SMTP, Recaptcha) and plan env-based configuration before React releases.

## www folder checklist (React migration readiness)
- www/ (root index, header/footer) - pending.
- www/pages (public routes) - pending.
- www/partials (shared UI sections) - pending.
- www/admin + www/admin/admin_new (admin UI) - pending.
- www/API (backend endpoints) - pending.
- www/functions (business logic) - pending.
- www/classes (server libraries) - pending.
- www/templates (emails/newsletters) - pending.
- www/css + www/assets + www/js (frontend assets) - pending.
- www/errors + www/policies (static/legal) - pending.
- www/config + www/ini.inc (config) - pending.
- www/dev + www/py + www/glowy + www/logs + www/phpmyadmin + www/fonts + www/images (tooling/vendor/assets) - pending.

## Route mapping
- React route map from `www/**/index.php` -> `AGENTS.md` (completed 2026-01-28).
- React route map from non-index PHP routes -> `AGENTS.md` (completed 2026-01-28).

## Documentation
- Documented purpose/dependencies/styling notes for `www/` folders in `AGENTS.md` (completed 2026-01-28).
- Defined React app structure target in `AGENTS.md` (completed 2026-01-28).
- Added inferred route -> API contract mapping in `AGENTS.md` (completed 2026-01-28).
- Drafted React route/component tree in `AGENTS.md` (completed 2026-01-28).
- Port About page to React (routes/AboutPage.tsx) and Tailwind.
 