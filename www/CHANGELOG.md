# Changelog

Alle belangrijke wijzigingen voor het project **WINDELS_deco&resin**.

---

## [2025-07-01] - Promo-module toegevoegd

### Toegevoegd
- Nieuwe adminpagina: **Pagina Promo maken**:
    - Formulier met drie selectiekeuzes:
        - 🔘 `Product`
        - 🔘 `Categorie`
        - 🔘 `Subcategorie`
    - Automatische afhandeling van `promo_type` op basis van de selectie:
        - `1` = Product
        - `2` = Categorie
        - `3` = Subcategorie

- AJAX-ondersteuning:
    - Subcategorieën worden automatisch geladen op basis van de gekozen hoofdcategorie.

- Backendbestand `save.php` toegevoegd:
    - Valideert invoer.
    - Registreert promo met juiste type.
    - Gebruikt `ini.inc` voor DB-connectie.
    - Slaat `created_at` en `created_by` mee op.

### Mappen & bestanden
- `/admin/promos/index.php` – formulierweergave
- `/admin/promos/save.php` – verwerkingslogica

### Getest
- Dynamisch laden van subcategorieën → ✅
- Juiste opslag van `promo_type` in DB → ✅
- Succesvolle opslaan van promo in database → ✅

---




# Changelog - 2025-09-29

## Winkelwagen systeem (Cart)
- Cart systeem omgezet naar volledig OOP (PHP + MySQLi).
- Nieuwe classes toegevoegd in /app/Cart:
    - Cart.php
    - CartItem.php
    - CartManager.php
    - CartStorageInterface.php
    - DatabaseCartStorage.php
- Autoloader in bootstrap.php aangepast zodat alle App\* classes correct worden geladen.
- DatabaseCartStorage implementeert opslag in MySQL (tabel cart_sessions).
- SQL schema toegevoegd:
  CREATE TABLE cart_sessions (
  session_id VARCHAR(128) PRIMARY KEY,
  data JSON NOT NULL,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
  );

## Endpoints (API)
- /cart/add.php herschreven naar OOP + DatabaseCartStorage.
- /cart/getCartItems.php herschreven naar OOP + DatabaseCartStorage.
- JSON responses uitgebreid met duidelijke foutmeldingen (success/error).

## Database
- Nieuwe OOP Database class gemaakt (MySQLi versie) in /app/Core/Database.php.
- Config opgesplitst naar /config/db.php en /config/app.php.

## Views
- /pages/shop/cart/index.php herschreven naar OOP Cart weergave (CartManager + DatabaseCartStorage).
- Oude procedural mysqli code verwijderd.
- Product grids (productsSwiper.php en populaire producten sectie) aangepast:
    - Knop "In winkelwagen" verschijnt alleen bij stock_status = 'in_stock' én stock_quantity > 0.
    - Label "Uitverkocht" toegevoegd voor niet-beschikbare producten.
    - Veilige checks ingebouwd voor ontbrekende velden (Undefined array key voorkomen).

## Bugfixes
- Strict_types error opgelost door declaratie altijd op de eerste regel van PHP-bestanden te plaatsen.
- Autoloader padcorrectie: baseDir aangepast naar __DIR__ . '/app/'.
- Frontend JS error ("addEventListener null") opgelost door null-checks op elementen.
- JSON.parse errors opgelost door endpoints altijd geldige JSON te laten teruggeven, zelfs bij fouten.

## Verbeteringen
- Couponlogica behouden, maar voorbereid op integratie in OOP Cart (meta-data in DB).
- Shipping berekening (MyParcel + lokale levering) werkt samen met nieuwe OOP Cart totalen.
- Uitbreidbaarheid voorzien: ProductRepository of voorraad-checks kunnen later geïntegreerd worden.

---

