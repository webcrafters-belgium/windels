# AGENT ROADMAP

## Non-negotiable
- Use a maximum of 50K tokens/minute, split tasks in manageable pieces. 
- Dont use herestrings to put data to a file
- No React: keep this repository PHP-only and avoid adding React assets or plans.


## Context
- The workspace root is `C:\Users\matth\Desktop\DEV\websites\Windels` ("root"). Treat everything here as part of the project scope; `dist/` is the legacy PHP site.
- Remote access/credentials must never be committed. If DB or SSH access is needed, paste only the minimum schema/data required into the chat (no secrets).
- Ignore heavy vendor trees (`node_modules`, `bin`, `lib`, `vendor`, etc.) unless specifically required.

## Workflow rules
1. Always record intent and status in `todo.md`/`done.md` at the repo root, never inside `dist/`. Use `AGENTS.md` to describe how tasks touch each folder.
2. For each folder we tackle (e.g., `admin/`, `API/`, `css/`, `pages/`), document purpose, dependencies, and styling notes before refactoring. Keep the PHP rendering and endpoints intact while modernizing styles or markup.
3. Capture data/API contracts per folder: which tables, config files, or services are consumed. If an area requires DB info, request a schema snippet and note where it will be stored (e.g., `.env`, `config/`).
4. Every root-level change must keep the legacy `dist/` runnable until its replacement is deployed.
5. Maintain a living checklist per folder inside `todo.md` (subtasks, statuses). Once a folder is fully ported and verified, move the entry to `done.md` with a brief summary of what was completed.
6. Keep communication concise: mention the PHP stack and the need to respect existing backend services. Any blocked steps should note the missing info (e.g., API schema) so we can follow up.

## www inventory and PHP modernization plan (2026-01-28)
This section maps the legacy `www/` tree and defines how each area should be modernized while preserving PHP backends.

### Root-level files
- `www/index.php`, `www/header.php`, `www/footer.php`: primary storefront entry and shared layout; keep PHP includes intact while modernizing markup/styles.
- `www/ini.inc`, `www/config/credentials.json`: runtime config and external credentials; document required keys in `.env` when needed.
- `www/composer.json`, `www/composer.lock`: PHP deps for backend utilities; keep for server-side mail, shipping, and order tooling.
- `www/site.webmanifest`, `www/robots.txt`, `www/request.json`, `www/readme.md`, `www/summary.md`, `www/categories.md`, `www/CHANGELOG.md`: metadata and notes; use to mirror SEO and category structure in PHP routes.

### Pages (public routes)
- `www/pages/`: public page routes; keep PHP routes and update markup/styles as needed.
  - Account: `account/` (login, register, accountgegevens, bestellingen, forgot_password, deletion, logout).
  - Shop: `shop/` (index, category, search, cart, shopping-cart, products/product, promo, deal-van-de-week, orders-tracking, terrazzo, vers, cadeaus).
  - Content: `about/`, `over-ons/`, `blogs/`, `contact/`, `privacybeleid/`, `terms_of_service/`, `cookies/`, `uitvaart/`, `workshops/`.
  - Dev/test: `pages/test/`, `pages/dev/` (retain only as backend diagnostics).

### Partials (shared UI)
- `www/partials/`: shared UI sections and forms; keep PHP partials and modernize styles.
  - `partials/header.php`, `partials/footer.php`: global shell components.
  - `partials/home/*`: homepage sections (banners, swipers, promos, newsletters, blog).
  - `partials/shop/sidebar_filters.php`: category/filter UI for shop pages.
  - `partials/forms/*`: form components (login, blog add, workshop).
  - `partials/blog/recent-posts.php`, `partials/theme/banners.json`: blog and banner data sources.

### API (backend endpoints to keep)
- `www/API/`: JSON or action endpoints; keep PHP/Laravel endpoints as the data source for the admin and storefront.
  - Auth: `API/auth/*` (login, register, confirm, oauth, forgot password).
  - Shop: `API/shop/*`, cart flow uses `functions/shop/cart/*`.
  - Orders: `API/orders/*` (invoice, packing slip, resend).
  - Shipping: `API/shipping/calculate_shipping.php`, `functions/shipping/*`.
  - Mail: `API/mail/*`, email confirmations.
  - Blog: `API/blog/add_blog_post.php`.
  - OAuth: `API/google/*`, `API/facebook/*`.
  - AI: `API/AI/*` (chatbot and history).
  - Webhooks: `API/sendcloud/webhook/index.php`.

### Functions (business logic and helpers)
- `www/functions/`: PHP business logic; keep as server-side services until replaced.
  - Shop/cart: `functions/shop/*`, `functions/shop/cart/*`, `functions/categories/*`.
  - Account: `functions/account/*`.
  - Newsletter/mail: `functions/newsletter/*`, `functions/mail/*`.
  - Contact/workshops: `functions/contact/*`, `functions/workshops/*`.
  - Helpers: `functions/helpers/*`, `functions/GoogleAuthenticator/*`.
  - Shipping: `functions/shipping/*`.
  - Admin: `functions/admin/*`.
  - Dev exports/backups: `functions/dev/*` (legacy references only).

### Admin (admin UI)
- `www/admin/`: admin UI and tools; keep PHP admin screens and modernize styles.
  - `admin/pages/*` and `admin/functions/*`: orders, products, customers, promo, newsletter, winkel, magazijn, workshops, tools.
  - `admin/admin_new/*`: newer admin variant; consolidate into single PHP admin experience.
  - `admin/voedselproblemen/*`: special workflow area; keep backend PHP and re-skin later.

### Styling and scripts (modernize legacy assets)
- `www/css/*`, `www/assets/scss/*`: legacy CSS/SCSS (bootstrap, flatsome, tailwind outputs); consolidate tokens and retire unused CSS.
- `www/js/*`: swiper, cart, checkout, home scripts; refactor to maintainable vanilla JS or PHP-friendly enhancements.
- `www/assets/svg/iconsList.php`: icon source; keep PHP-driven icons or inline SVGs as needed.

### Templates and services (stay server-side for now)
- `www/templates/*`: email/newsletter templates; keep PHP templates for outgoing mail.
- `www/classes/*`: PHPMailer, Transip API; keep as PHP backend deps.
- `www/errors/*`, `www/policies/*`: static content; keep PHP or static assets.
- `www/py/*`: utility scripts; not part of frontend.
- `www/glowy/*`: standalone demo; ignore unless marketing wants it.
- `www/phpmyadmin/*`, `www/lib/*`: vendor trees; do not touch for migration work.

### Data/API contract notes
- DB schema needed for: shop products/categories, cart/order flows, account/auth, newsletters, workshops, blog, admin reporting. Request relevant table schemas before porting.
- External services in use: Mollie (payments), Sendcloud/Packlink (shipping), Onfact (invoicing), Google/Facebook OAuth, PHPMailer (email), Transip (domains), AI chatbot endpoint.

## www folder-by-folder modernization plan (index.php driven)
Target: modernize public/admin `index.php` routes while keeping PHP rendering and endpoints intact.

### www/ (root)
- Key entry: `www/index.php`.
- Shared shell: `www/header.php`, `www/footer.php`, `www/partials/header.php`, `www/partials/footer.php`.
- Plan: modernize homepage sections from `www/partials/home/*` while keeping PHP includes.

### www/pages (public routes)
- Key entries: all `www/pages/**/index.php` + `www/pages/**/product.php` + `www/pages/**/search.php`.
- Plan: keep URL structure and PHP routes. Update view logic and styling while keeping `www/API/*` + `www/functions/*` as data sources.
  - Account: `account/*` (login/register/logout/forgot/bestellingen/accountgegevens/deletion).
  - Shop: `shop/*` (index, category, search, cart, products/product, promo, deal-van-de-week, orders-tracking, vers, terrazzo, cadeaus, shopping-cart).
  - Content: `about/`, `over-ons/`, `blogs/`, `contact/`, `privacybeleid/`, `terms_of_service/`, `cookies/`, `uitvaart/`, `workshops/`.
  - Dev/test: `pages/dev/*`, `pages/test/*` (retain only as backend diagnostics).
- Purpose: public storefront and content pages.
- Dependencies: `www/partials/*`, `www/functions/*` (shop/account/newsletter/contact), `www/API/*` (auth, shop, orders, shipping), session/auth.
- Styling notes: heavy use of legacy CSS (bootstrap/flatsome/custom), Swiper sliders, and custom JS.

### Route map (from all `www/**/index.php`)
Public site:
- `/` -> `www/index.php`
- `/about` -> `www/pages/about/index.php`
- `/about/producten/over-epoxyhars` -> `www/pages/about/producten/over-epoxyhars/index.php`
- `/account` -> `www/pages/account/index.php`
- `/account/accountgegevens` -> `www/pages/account/accountgegevens/index.php`
- `/account/accountgegevens/edit` -> `www/pages/account/accountgegevens/edit/index.php`
- `/account/bestellingen` -> `www/pages/account/bestellingen/index.php`
- `/account/deletion` -> `www/pages/account/deletion/index.php`
- `/account/forgot_password` -> `www/pages/account/forgot_password/index.php`
- `/account/login` -> `www/pages/account/login/index.php`
- `/account/logout` -> `www/pages/account/logout/index.php`
- `/account/register` -> `www/pages/account/register/index.php`
- `/blogs` -> `www/pages/blogs/index.php`
- `/cadeaus` -> `www/pages/shop/cadeaus/index.php`
- `/contact` -> `www/pages/contact/index.php`
- `/cookies` -> `www/pages/cookies/index.php`
- `/data_deletion` -> `www/pages/data_deletion/index.php`
- `/over-ons` -> `www/pages/over-ons/index.php`
- `/privacybeleid` -> `www/pages/privacybeleid/index.php`
- `/shop` -> `www/pages/shop/index.php`
- `/shop/cart` -> `www/pages/shop/cart/index.php`
- `/shop/deal-van-de-week` -> `www/pages/shop/deal-van-de-week/index.php`
- `/shop/orders-tracking` -> `www/pages/shop/orders-tracking/index.php`
- `/shop/promo` -> `www/pages/shop/promo/index.php`
- `/shop/terrazzo` -> `www/pages/shop/terrazzo/index.php`
- `/shop/vers` -> `www/pages/shop/vers/index.php`
- `/terms_of_service` -> `www/pages/terms_of_service/index.php`
- `/uitvaart` -> `www/pages/uitvaart/index.php`
- `/workshops` -> `www/pages/workshops/index.php`
- `/workshops/inschrijven` -> `www/pages/workshops/inschrijven/index.php`

Admin (PHP admin):
- `/admin` -> `www/admin/index.php`
- `/admin/add_pages` -> `www/admin/add_pages/index.php`
- `/admin/config` -> `www/admin/config/index.php`
- `/admin/config/opening_times` -> `www/admin/config/opening_times/index.php`
- `/admin/config/opening_times/vacation` -> `www/admin/config/opening_times/vacation/index.php`
- `/admin/customers/coupons` -> `www/admin/customers/coupons/index.php`
- `/admin/pages/blogs` -> `www/admin/pages/blogs/index.php`
- `/admin/pages/customers` -> `www/admin/pages/customers/index.php`
- `/admin/pages/deal_van_de_week` -> `www/admin/pages/deal_van_de_week/index.php`
- `/admin/pages/kantoor` -> `www/admin/pages/kantoor/index.php`
- `/admin/pages/newsletter` -> `www/admin/pages/newsletter/index.php`
- `/admin/pages/orders` -> `www/admin/pages/orders/index.php`
- `/admin/pages/products` -> `www/admin/pages/products/index.php`
- `/admin/pages/products/add` -> `www/admin/pages/products/add/index.php`
- `/admin/pages/promo` -> `www/admin/pages/promo/index.php`
- `/admin/pages/test-mail` -> `www/admin/pages/test-mail/index.php`
- `/admin/pages/winkel` -> `www/admin/pages/winkel/index.php`
- `/admin/pages/winkel/producten` -> `www/admin/pages/winkel/producten/index.php`
- `/admin/pages/winkel/schaplabel` -> `www/admin/pages/winkel/schaplabel/index.php`
- `/admin/pages/winkel/schappenplan` -> `www/admin/pages/winkel/schappenplan/index.php`
- `/admin/pages/workshops` -> `www/admin/pages/workshops/index.php`
- `/admin/tools/mailing` -> `www/admin/tools/mailing/index.php`
- `/admin/tools/onfact` -> `www/admin/tools/onfact/index.php`
- `/admin/tools/onfact/debug` -> `www/admin/tools/onfact/debug/index.php`
- `/admin/voedselproblemen` -> `www/admin/voedselproblemen/index.php`
- `/admin/admin_new` -> `www/admin/admin_new/index.php`
- `/admin/admin_new/pages/dashboard` -> `www/admin/admin_new/pages/dashboard/index.php`
- `/admin/admin_new/pages/orders` -> `www/admin/admin_new/pages/orders/index.php`
- `/admin/admin_new/pages/products` -> `www/admin/admin_new/pages/products/index.php`
- `/admin/admin_new/pages/settings` -> `www/admin/admin_new/pages/settings/index.php`
- `/admin/admin_new/pages/shipments` -> `www/admin/admin_new/pages/shipments/index.php`
 
Other (keep server-side):
- `/errors/403` -> `www/errors/403/index.php`
- `/errors/404` -> `www/errors/404/index.php`
- `/phpmyadmin` -> `www/phpmyadmin/index.php`
- `/api/sendcloud/webhook` -> `www/API/sendcloud/webhook/index.php`
- `/dev` -> `www/dev/index.php`
- `/dev/add_pages_tests` -> `www/dev/add_pages_tests/index.php`
- `/dev/myparcell` -> `www/dev/myparcell/index.php`
- `/dev/pw-hash` -> `www/dev/pw-hash/index.php`
- `/pages/dev` -> `www/pages/dev/index.php`
- `/pages/dev/orders/test_orders` -> `www/pages/dev/orders/test_orders/index.php`
- `/pages/dev/images` -> `www/pages/dev/images/index.php`
- `/pages/test` -> `www/pages/test/index.php`

Notes:
- Additional non-index routes (e.g., `www/pages/shop/products/product.php`, `www/pages/shop/search.php`, `www/pages/blogs/post.php`) are listed separately when we inventory non-index PHP files.

### Route map (non-index PHP routes)
Public site:
- `/blogs/post` -> `www/pages/blogs/post.php`
- `/pages/about/producten/over-epoxyhars/epoxyhars-garantie` -> `www/pages/about/producten/over-epoxyhars/epoxyhars-garantie.php`
- `/pages/about/producten/epoxyhars-garantie` -> `www/pages/about/producten/epoxyhars-garantie.php`
- `/pages/account/bestellingen/detail` -> `www/pages/account/bestellingen/detail.php`
- `/pages/account/recaptcha_enterprise_verify` -> `www/pages/account/recaptcha_enterprise_verify.php`
- `/pages/account/login/test_google` -> `www/pages/account/login/test_google.php`
- `/pages/account/login/login-succes` -> `www/pages/account/login/login-succes.php`
- `/pages/account/login/googleLogin` -> `www/pages/account/login/googleLogin.php`
- `/pages/account/login/facebookLogin` -> `www/pages/account/login/facebookLogin.php`
- `/shop/category` -> `www/pages/shop/category.php`
- `/shop/search` -> `www/pages/shop/search.php`
- `/shop/products/product` -> `www/pages/shop/products/product.php`
- `/shop/shopping-cart/checkout-success` -> `www/pages/shop/shopping-cart/checkout-success.php`
- `/shop/shopping-cart/send-testmail` -> `www/pages/shop/shopping-cart/send-testmail.php`

Admin (actions/endpoints; keep server-side):
- Admin forms/actions under `www/admin/pages/**` (e.g., orders pdf, delete, update_status; blog add/edit; promo save/add).
- Admin functions under `www/admin/functions/**` and `www/admin/admin_new/functions/**`.
- Tools endpoints under `www/admin/tools/**`.

### www/partials (shared UI)
- Key entries: `partials/header.php`, `partials/footer.php`, `partials/home/*`, `partials/shop/sidebar_filters.php`, `partials/forms/*`, `partials/blog/*`.
- Plan: modernize PHP partials and keep slider behavior via existing JS libraries where needed.
- Purpose: shared UI sections and forms.
- Dependencies: `www/js/*`, `www/partials/theme/banners.json`, `www/functions/helpers/*`.
- Styling notes: tied to legacy CSS classes and Swiper markup; needs Tailwind component equivalents.

### www/admin (admin UI)
- Key entries: all `www/admin/**/index.php` and `www/admin/admin_new/**/index.php`.
- Plan: keep PHP admin endpoints in `www/admin/functions/*` + `www/functions/admin/*` and modernize admin markup/styles.
- Purpose: admin dashboards, orders, products, customers, promos, newsletters, tools.
- Dependencies: `www/admin/functions/*`, `www/functions/admin/*`, `www/functions/shop/*`, admin auth, PDFs/invoices.
- Styling notes: admin uses bootstrap + custom admin CSS; plan Tailwind admin design system.

### www/API (backend endpoints)
- Key entries: `www/API/**` (auth, shop, orders, shipping, mail, blog, oauth, AI, webhooks).
- Plan: keep as backend JSON endpoints; document request/response contracts per endpoint.
- Purpose: API layer for auth/shop/orders/shipping/mail/blog/AI.
- Dependencies: `www/functions/*`, `www/classes/*`, external services (Mollie, Sendcloud/Packlink, Onfact, OAuth).
- Styling notes: n/a (backend).

### www/functions (business logic)
- Key entries: `www/functions/**` (shop/cart, categories, account, newsletter, mail, contact, workshops, shipping, helpers).
- Plan: keep server-side; PHP pages consume through `www/API/*` or existing includes as needed.
- Purpose: core business logic, DB access, transactions, validation.
- Dependencies: DB schema, `www/classes/*`, configs, external APIs.
- Styling notes: n/a (backend).

### www/classes (libraries)
- Key entries: PHPMailer, Transip API, GoogleAuthenticator.
- Plan: keep server-side.
- Purpose: third-party PHP libraries.
- Dependencies: composer/runtime PHP.
- Styling notes: n/a.

### www/templates (email/newsletter)
- Key entries: `www/templates/**`.
- Plan: keep server-side; only adjust if email content must change.
- Purpose: email/newsletter templates.
- Dependencies: mail functions and PHPMailer.
- Styling notes: inline HTML/CSS for email.

### www/css, www/assets, www/js (frontend assets)
- Key entries: `www/css/*`, `www/assets/scss/*`, `www/js/*`.
- Plan: extract design tokens into shared CSS variables; replace legacy JS with maintainable vanilla JS; deprecate unused CSS when parity achieved.
- Purpose: legacy styles, vendor CSS/JS, Swiper, Bootstrap, custom scripts.
- Dependencies: tied to current PHP markup/partials.
- Styling notes: migrate to Tailwind tokens/components; keep temporary compatibility layer if needed.

### www/errors, www/policies
- Key entries: `www/errors/**/index.php`, `www/errors/404.html`, `www/policies/*.txt`.
- Plan: keep PHP or static fallbacks on server.
- Purpose: static error/legal content.
- Dependencies: none.
- Styling notes: simple content layouts.

### www/config, www/ini.inc
- Key entries: `www/ini.inc`, `www/config/credentials.json`.
- Plan: document required env vars; never move secrets into frontend.
- Purpose: runtime config and credentials.
- Dependencies: server runtime.
- Styling notes: n/a.

### www/dev, www/py, www/glowy, www/logs, www/phpmyadmin, www/fonts, www/images
- Plan: treat as tooling/assets/vendor. Keep out of production builds unless assets are explicitly needed.
- Purpose: tooling, logs, vendor/admin tools, assets.
- Dependencies: n/a.
- Styling notes: n/a.

## Security findings
- `www/functions/contact/handle_contact.php` still hard-codes `smtp_host`, `smtp_user`, and `smtp_pass`. Move these credentials into resource config (e.g., `ini.inc`) or server `.env` and update `todo.md` entry 16 accordingly.
- `www/pages/account/recaptcha_enterprise_verify.php` contains the Recaptcha keys; store them in config and expose only as needed through `www/API/...` endpoints so secrets never ship to the client.
- `www/API/facebook/facebookLogout.php` references OAuth secrets; keep those values in environment config and document which keys are required for the new admin/auth flows.

## Route -> API contract mapping (inferred from file names)
Public storefront:
- `/` (home): products, categories, banners, deals -> `www/API/shop/*`, `www/functions/shop/products.php`, `www/partials/theme/banners.json`.
- `/shop` + `/shop/category` + `/shop/search`: product listing/filtering -> `www/API/shop/search_products.php`, `www/API/shop/get_subcategories.php`, `www/functions/shop/products.php`, `www/functions/helpers/product_filters.php`.
- `/shop/products/product`: product detail -> `www/functions/shop/products.php`, `www/functions/shop/notify_when_available.php`.
- `/shop/cart`: cart state -> `www/functions/shop/cart/*` (add/remove/apply_coupon/getCartItems/count/get_shipping_methods).
- `/shop/shopping-cart/checkout-success`: order confirmation -> `www/functions/shop/cart/checkout.php`, `www/API/orders/*`.
- `/shop/orders-tracking`: tracking -> `www/API/orders/*`, shipping helpers.
- `/account/login` + `/account/register` + `/account/forgot_password` + `/account/logout`: auth -> `www/API/auth/*`, `www/functions/account/*`.
- `/account/accountgegevens` + `/account/bestellingen`: account data + orders -> `www/API/orders/*`, `www/functions/account/*`.
- `/workshops` + `/workshops/inschrijven`: workshops -> `www/functions/workshops/*`, `www/partials/forms/workshop_form.php`.
- `/blogs` + `/blogs/post`: blog -> `www/API/blog/add_blog_post.php` (admin), plus legacy blog data source (needs schema).
- `/contact`: contact form -> `www/functions/contact/handle_contact.php`.
- `/privacybeleid`, `/terms_of_service`, `/cookies`, `/uitvaart`, `/over-ons`, `/about/*`: mostly static content; confirm any dynamic pulls.

Admin (PHP admin):
- Products: `www/admin/pages/products/*`, `www/admin/functions/shop/products/*`, `www/admin/admin_new/functions/products/*`.
- Orders: `www/admin/pages/orders/*`, `www/API/orders/*`, `www/functions/mail/*`, `www/admin/pages/orders/pdf_*`.
- Customers: `www/admin/pages/customers/*`.
- Promo/deals: `www/admin/pages/promo/*`, `www/admin/functions/deals/*`.
- Newsletter: `www/admin/pages/newsletter/*`, `www/templates/newsletters/*`, `www/functions/newsletter/*`.
- Workshops: `www/admin/pages/workshops/*`, `www/functions/workshops/*`.
- Tools: `www/admin/tools/*` (Onfact, mailing).
- Config: `www/admin/config/*`, `www/functions/admin/*`.

Notes:
- This mapping is filename-based. Validate contracts by inspecting each PHP file before porting.
