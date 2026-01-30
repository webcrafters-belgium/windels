# Windels Green & Deco Resin - Admin Panel PRD

## Original Problem Statement
Complete admin panel overhaul for a webshop using PHP and Mollie payments. The existing admin panel at windelsgreen-decoresin.com/admin was missing critical functions.

## Architecture
- **Frontend**: PHP with Tailwind CSS, Glassmorphism UI, Bootstrap Icons
- **Backend**: PHP with MySQL/MySQLi
- **Payments**: Mollie integration
- **Design**: Dark/Light mode, Accessibility features (large text, high contrast)

## User Personas
1. **Shop Owner (Admin)**: Manages products, orders, customers, settings
2. **Staff**: Views orders, updates shipping status
3. **Marketing**: Manages coupons, views reports

## Core Requirements (Static)
- Product management (CRUD with materials tracking for candles/terrazzo/epoxy)
- Order management with status updates
- Customer management with order history
- Coupon/discount code management
- Analytics and reporting
- Settings management (shop info, Mollie, SMTP)
- Shipping/tracking management

## What's Been Implemented ✅

### January 2026 - Glassmorphism Migration

**Pages Updated to New Design (21 files):**
- `/admin/pages/dashboard/index.php` ✅
- `/admin/pages/products/` (index, add, edit) ✅
- `/admin/pages/orders/` (index, view) ✅
- `/admin/pages/customers/` (index, view, export) ✅
- `/admin/pages/coupons/index.php` ✅
- `/admin/pages/reports/index.php` ✅
- `/admin/pages/settings/index.php` ✅
- `/admin/pages/shipments/index.php` ✅
- `/admin/pages/blogs/` (index, add, edit) ✅
- `/admin/pages/winkel/index.php` ✅
- `/admin/pages/winkel/producten/index.php` ✅
- `/admin/pages/winkel/schaplabel/index.php` ✅
- `/admin/pages/winkel/schappenplan/index.php` ✅
- `/admin/pages/winkel/orders/orders_view.php` ✅
- `/admin/pages/manage_admin_pages/add.php` ✅
- `/app/index.php` (main admin home) ✅

**Pages Still Using Old Design (10 files):**
- `/admin/pages/winkel/producten/edit_product.php`
- `/admin/pages/winkel/schaplabel/` (schaplabel.php, schaplabel_new.php, schaplabel_gewijzigd.php, schaplabel_korting.php)
- `/admin/pages/winkel/schappenplan/` (add_schap.php, edit_schap.php, view_schap.php, add_productschap.php, edit_productschap.php)

### Backend Functions
- `/admin/functions/products/save.php` - Handles create AND update
- `/admin/functions/products/delete.php` - Proper cleanup

## Access URLs
- Admin Panel: `/admin/` → Redirects to dashboard
- Admin Home: `/index.php` (with dynamic pages from DB)

## Prioritized Backlog

### P0 (Critical) - DONE
- [x] Product management pages
- [x] Order management pages
- [x] Customer management pages
- [x] Main admin navigation

### P1 (Important) - IN PROGRESS
- [x] Coupon management
- [x] Analytics/Reports
- [x] Settings management
- [x] Blog management
- [x] Winkel main pages
- [ ] Remaining winkel subpages (10 files)

### P2 (Nice to Have)
- [ ] Email notifications on order status change
- [ ] Bulk product actions
- [ ] Newsletter management
- [ ] Multi-image upload per product
- [ ] Advanced reporting (export to Excel)

## Technical Notes
- All new pages use `/admin/includes/header.php` for glassmorphism design
- Old pages use `/admin/header.php` or `/header.php`
- Database: Uses existing MySQL tables + new tables
- Session: Requires admin role in session
- CSRF: Token protection on all POST requests

## Last Updated
January 2026 - Glassmorphism migration (21/31 admin pages updated)
