# DONE

- 2026-02-01: Fixed admin include path resolution to load `www/admin/config.php` reliably.
- 2026-02-01: Ensured admin uses `ini.inc` DB connection when available (no null `$conn` crash).
- 2026-02-01: Restored dashboard stats layout with 6 cards only (products, orders, pending, revenue, 30 days, low stock).

## Legacy (not production)
- 2026-01-28: Added `.gitignore` (ignore vendor trees, node_modules, logs, env/secrets, phpmyadmin/lib, dev exports).
- 2026-01-28: Scaffolded Vite+React+Tailwind workspace in `subdomains/matthias` (Vite config, tailwind, ESLint, sample components, `.env.example`).
- 2026-01-28: Added API helper/tracking (`src/api/shop.ts`) and two React routes (`/` home + `/shop` listing) with placeholder product cards.
- 2026-01-28: Ported About page from PHP to React with Tailwind (subdomains/matthias/src/routes/AboutPage.tsx).
- 2026-01-28: Rebuilt site shell in React: new Header with nav/offcanvas placeholders, Footer matching legacy sections, seasonal Snowflakes component, and RootLayout wrapping all routes.
- 2026-01-28: Updated routing to use RootLayout and added bootstrap-icons CDN to index.html for UI parity.
- 2026-01-28: Created reusable ProductCard + SidebarFilters components and restyled `/shop` index page with legacy-like static placeholders.
