# Windels Webshop -- Technical TODO

## Webshop Frontend

### Product Tags (Badges)

-   Add product tags such as **"New in assortiment"** and **"Actie"**.
-   Display badge on product cards.
-   Suggested implementation:
    -   Add `tag` column to `products` table OR create `product_tags`
        table.
    -   Possible values: `new`, `sale`, `null`.
-   Frontend should show visual badge on product grid and product page.

### Homepage Sections

Create two extra homepage sections:

1.  **Nieuwe producten**
    -   Query newest products ordered by `created_at`.
    -   Limit to 6--8 items.
2.  **Actie producten**
    -   Filter products where `tag = sale`.

Ensure both sections follow the existing shop layout.

### Discount Codes (€ vs %)

Current issue: - System seems to always apply **€ discount**, even when
**% is selected**.

Tasks: - Verify database structure: - `discount_type` → (`fixed`,
`percent`) - `discount_value` - Correct calculation logic:

    if(type == "percent"){
       discount = price * (value / 100)
    }else{
       discount = value
    }

    final_price = price - discount

-   Verify admin list view and edit form store the same `discount_type`.

------------------------------------------------------------------------

## Admin Panel

### Opening Hours Page

Problem: - Page returns **404 / not found**.

Tasks: - Check route mapping. - Verify correct admin URL. - Verify
controller / template exists. - Ensure settings are saved correctly in
database.

### Blog -- Image Upload

Problem: - Image upload no longer works.

Check: - Upload directory permissions. - PHP upload limits
(`upload_max_filesize`, `post_max_size`). - FormData / request
handling. - Storage path (e.g. `/uploads/blog/`).

### Discount Codes (Admin)

Same issue as frontend.

Tasks: - Verify: - edit form fields - save logic - database values -
Ensure `%` discounts are stored and calculated correctly.

### Custom Webshop Pages (Mini CMS)

Allow admin to create pages with same layout as existing shop pages.

Suggested table:

    pages
    id
    title
    slug
    content
    layout
    status
    created_at

Features: - Create page - Edit page - Delete page - Access page via
`/page/{slug}`

### New Order Status

Add new status:

    Afgerond

Flow example:

    Pending
    Paid
    Shipped
    Completed (Afgerond)
    Cancelled

Update: - database - admin dropdown - order logic

### Newsletter System

Tasks: - Allow admin to send newsletters. - Subscriber list.

Suggested table:

    newsletter_subscribers
    id
    email
    created_at

Admin features: - send newsletter - view subscribers - basic layout
template

### Anti‑Spam Protection (Registrations)

Too many spam accounts.

Solution: **Honeypot field**.

Add hidden field:

    <input type="text" name="website" style="display:none">

Server check:

    if(!empty($_POST['website'])){
       reject registration
    }

Optional: - Add rate limiting. - Add reCAPTCHA.

### Logout Fix

Problem: - Logout not working correctly for customers and admin.

Tasks: - Verify session destroy logic.

Correct example:

    session_unset();
    session_destroy();
    setcookie(session_name(), '', time()-3600);

-   Verify redirect after logout.
-   Verify admin and user sessions are separated.

------------------------------------------------------------------------

# Suggested Development Order

1.  Fix discount code calculation
2.  Fix logout functionality
3.  Fix opening hours admin page
4.  Repair blog image upload
5.  Add anti‑spam honeypot
6.  Add order status "Afgerond"
7.  Implement product badges
8.  Add homepage sections
9.  Build custom page CMS
10. Implement newsletter system
11. Then continue with steps below

------------------------------------------------------------------------

Status goal: When all tasks above are complete, the webshop and admin
panel should be technically stable and production‑ready.

## Progress Update
- 2026-03-09 (www): Discount code handling bijgewerkt zodat zowel percentage als vast bedrag correct werken in cart + checkout.
- Gewijzigd in `www/functions/shop/cart/apply_coupon.php`, `www/functions/shop/cart/checkout.php`, `www/pages/shop/cart/index.php`, `www/js/checkout.js`.
- Admin couponbeheer is bewust nog niet aangepast (volgens prioriteit: eerst www).
- 2026-03-09 (www): Logout-flow hersteld (session cookie + session destroy + redirect naar /pages/account/login/), inclusief compat-route `/pages/account/logout.php`.
- 2026-03-09 (www): Anti-spam honeypot ook toegevoegd aan `www/API/auth/register.php` (extra bescherming voor alternatieve registratie-endpoint).
- 2026-03-09 (www): Product tags/badges toegevoegd op homepage, shop-grid en productdetail (`new` = "New in assortiment", `sale` = "Actie").
- 2026-03-09 (www): Homepage uitgebreid met 2 extra secties: "Nieuwe producten" (created_at desc) en "Actie producten" (tag = sale).
- Implementatie bevat fallback wanneer `products.tag` nog niet bestaat (geen fatale SQL error, sectie Actie blijft dan leeg).
- 2026-03-09 (admin): 404 op instellingen/openingstijden opgelost via compat-route `www/admin/pages/settings/index.php` -> `/admin/config/`.
- Root-cause: navigatie verwees naar niet-bestaande settings pagina onder `/admin/pages/settings/` terwijl werkende module onder `/admin/config/` staat.
- 2026-03-09 (admin): Blog image upload hersteld door add/edit uploadpad te aligneren op `/images/uploads/blog/`.
- 2026-03-09 (admin): Uploadflow verhard met map-write checks (`is_writable`) en upload-validatie (`is_uploaded_file`).
