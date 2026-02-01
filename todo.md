# TODO

## Phase 1 – Inventory & Analysis
1. Document `admin/` inside `htdocs/` (screens, PHP templates, CSS, JS, API hooks) and note what needs modernization.
2. Catalog `pages/`, `partials/`, and `templates/` to understand current storefront layout relationships.
3. Capture shared helpers and configs (`classes/`, `functions/`, `config/`, `ini.inc`, `.env`) that power public pages.
4. List static assets (`css/`, `styles/`, `assets/`, `fonts/`, `images/`) necessary for the brand’s look and note whether they belong in the main PHP site or stay as legacy resources.
5. Review `API/` endpoints, `py/` utilities, and `dev/` tools to decide what should be preserved or re-implemented.
6. Document `www/` inventory and migration plan in `AGENTS.md` (completed 2026-01-28).

## Phase 2 – PHP Modernization
7. Modernize `admin/` markup/styles while keeping PHP rendering and backend endpoints intact.
8. Refresh storefront PHP pages in `www/pages/` to match updated branding while preserving existing routes and includes.
9. Establish backend strategy: keep select PHP/Laravel endpoints or refactor for clearer separation without introducing client-side frameworks.
10. Plan CSS consolidation: capture color/typography tokens, define utility classes, and decide which legacy CSS files to retire or port.
11. Define build/deploy steps for PHP assets (cache busting, asset syncing, environment alignment).

## Phase 3 – Verification & Documentation 
13. Create QA checklists per folder (visual diff with legacy site, API contract verification, performance assertions).
14. Set up `done.md` entries per folder once PHP updates are verified, noting what was migrated and any remaining backend ties.
15. Track ongoing blockers (missing DB schema, unknown API) and log what info is needed in future updates.
16. Audit legacy PHP for hard-coded secrets (contact SMTP, Recaptcha) and plan env-based configuration before releases.
17. Restore admin glassmorphism styling and redirect legacy `/admin_new` paths to `/admin` (completed 2026-02-01).

## www folder checklist (PHP modernization readiness)
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
- Route map from `www/**/index.php` -> `AGENTS.md` (completed 2026-01-28).
- Route map from non-index PHP routes -> `AGENTS.md` (completed 2026-01-28).

## Documentation
- Documented purpose/dependencies/styling notes for `www/` folders in `AGENTS.md` (completed 2026-01-28).
- Added inferred route -> API contract mapping in `AGENTS.md` (completed 2026-01-28).
- Refresh About page content in PHP with updated styling.
- Build a unified PHP site shell matching legacy header/footer and wire any new navigation updates.
- Flesh out `/shop` page layout with static placeholders mirroring legacy grid; then connect to live product API.
- Connect cart, checkout, account pages to backend API once product feed is live.
- Hook admin PHP pages to live DB-backed API for products/orders/customers/promos CRUD and verification.
 
