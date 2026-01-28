<?php

// =======================
//   TODO - WINDELS SHOP
//   Laatste update: 29/09/2025
// =======================

// -----------------------
// KORTETERMIJN
// -----------------------

// Cart (Winkelwagen)
// Todo: apply_coupon.php en remove_coupon.php ombouwen naar OOP (korting opslaan in cart meta of DB).
// Todo: Automatisch out-of-stock producten verwijderen of blokkeren in getCartItems.php.
// Todo: cart.js en checkout.js nalopen → alle endpoints corrigeren (geen /public/, enkel /cart/).

// Producten
// Todo: Product detailpagina (product.php) aanpassen → toon "Uitverkocht" en disable add-to-cart knop.
// Todo: Alle product queries uitbreiden met stock_status en stock_quantity (Undefined array key oplossen).
// Todo: Oude mysqli $conn connecties vervangen door OOP Database class (Database.php).
// Todo: Afbeelding upload in adminfunctie product toevoegen fixen → filename + path opslaan in DB.
// Todo: Placeholder image instellen indien product geen afbeelding heeft.

// Admin
// Todo: https://windelsgreen-decoresin.com/admin/voedselproblemen/index.php → Bijwerken
// Todo: https://windelsgreen-decoresin.com/admin/pages/blog/edit.php → blogpost kunnen bewerken via ID (TinyMCE + update query).
// Todo: https://windelsgreen-decoresin.com/admin/pages/blog/delete.php → blogpost verwijderen met bevestiging + redirect.

// Promoties & Kortingen
// Todo: Nakijken of we kortingen op elke pagina kunnen toepassen tussen 05 en 06 juli 2025.
// Todo: Pagina promo maken (frontend klaar, koppeling DB / acties nakijken).


// -----------------------
// LANGETERMIJN
// -----------------------

// Cart uitbreidingen
// Todo: Database schema uitbreiden voor coupons (aparte `coupons` tabel: code, type, waarde, geldigheid).
// Todo: Uitbreiding van CartManager → metadata opslaan zoals coupon codes, shipping, notes.
// Todo: Rapportage toevoegen (welke producten vaak in cart belanden maar niet besteld worden).

// Frontend & UX
// Todo: Toast/alert melding in frontend bij voorraad-fouten (bv. “Dit product is uitverkocht”).
// Todo: Checkout flow verbeteren: loading states, betere validatie (email, telefoon).
// Todo: Responsive UI tweaks voor cart overlay en checkout-pagina.


// Logging & Beheer
// Todo: Automatisch changelog systeem bouwen → wijzigingen loggen naar changelog.txt.
// Todo: Footer aanpassen om laatste changelog entries dynamisch weer te geven.
// Todo: Admin dashboard uitbreiden met log van coupons en bestellingen.
// Todo: Error logging centraliseren naar logs/ in plaats van output naar gebruiker.


declare(strict_types=1);

/**
 * Centrale TODO-lijst voor Windels Shop.
 * - Gebruik dit bestand in admin (bv. /admin/todo.php) om dynamisch te tonen.
 * - Structuur: categorieën met items (status open/doing/done) en optionele links.
 * - Laatste update: 29/09/2025
 */

return [
    'metadata' => [
        'last_updated' => '2025-09-29 16:30',
        'project' => 'Windels Shop',
        'owner' => 'Matthias',
    ],

    // -----------------------
    // KORTETERMIJN
    // -----------------------
    'short_term' => [
        'Cart (Winkelwagen)' => [
            [
                'title' => 'Endpoints cart/remove.php en cart/count.php herschrijven naar OOP + DatabaseCartStorage.',
                'status' => 'done',
                'notes' => 'remove.php gemigreerd; count.php toegevoegd.',
                'links' => ['/cart/remove.php', '/cart/count.php'],
            ],
            [
                'title' => 'apply_coupon.php en remove_coupon.php ombouwen naar OOP (korting opslaan in cart meta of DB).',
                'status' => 'open',
                'links' => [],
            ],
            [
                'title' => 'Automatisch out-of-stock producten verwijderen of blokkeren in getCartItems.php.',
                'status' => 'done',
                'notes' => 'Basiscontrole toegevoegd tegen products.stock_status/stock_quantity.',
                'links' => ['/cart/getCartItems.php'],
            ],
            [
                'title' => 'Extra check bij checkout: voorraad controleren om race conditions te vermijden.',
                'status' => 'open',
                'links' => ['/checkout.php'],
            ],
            [
                'title' => 'cart.js en checkout.js nalopen → alle endpoints corrigeren (geen /public/, enkel /cart/).',
                'status' => 'open',
                'links' => ['/assets/js/cart.js', '/assets/js/checkout.js'],
            ],
        ],

        'Producten' => [
            [
                'title' => 'Product detailpagina (product.php) aanpassen → toon "Uitverkocht" en disable add-to-cart knop.',
                'status' => 'open',
                'links' => ['/product.php'],
            ],
            [
                'title' => 'Alle product queries uitbreiden met stock_status en stock_quantity (Undefined array key oplossen).',
                'status' => 'open',
                'links' => [],
            ],
            [
                'title' => 'Oude mysqli $conn connecties vervangen door OOP Database class (Database.php).',
                'status' => 'doing',
                'links' => ['/core/Database.php'],
            ],
            [
                'title' => 'Afbeelding upload in adminfunctie product toevoegen fixen → filename + path opslaan in DB.',
                'status' => 'open',
                'links' => ['/admin/products/create.php'],
            ],
            [
                'title' => 'Placeholder image instellen indien product geen afbeelding heeft.',
                'status' => 'open',
                'links' => ['/assets/img/placeholder.png'],
            ],
        ],

        'Admin' => [
            [
                'title' => 'Bijwerken: /admin/voedselproblemen/index.php',
                'status' => 'open',
                'links' => ['/admin/voedselproblemen/index.php'],
            ],
            [
                'title' => 'Blog edit: /admin/pages/blog/edit.php (TinyMCE + update query via ID).',
                'status' => 'open',
                'links' => ['/admin/pages/blog/edit.php'],
            ],
            [
                'title' => 'Blog delete: /admin/pages/blog/delete.php (bevestiging + redirect).',
                'status' => 'open',
                'links' => ['/admin/pages/blog/delete.php'],
            ],
        ],

        'Promoties & Kortingen' => [
            [
                'title' => 'Nakijken of we kortingen op elke pagina kunnen toepassen tussen 05 en 06 juli 2025.',
                'status' => 'open',
                'links' => [],
            ],
            [
                'title' => 'Pagina promo maken (frontend klaar, koppeling DB / acties nakijken).',
                'status' => 'open',
                'links' => ['/promo.php'],
            ],
        ],
    ],

    // -----------------------
    // LANGETERMIJN
    // -----------------------
    'long_term' => [
        'Cart uitbreidingen' => [
            [
                'title' => 'Database schema uitbreiden voor coupons (tabel coupons: code, type, waarde, geldigheid).',
                'status' => 'open',
            ],
            [
                'title' => 'Uitbreiding van CartManager → metadata opslaan zoals coupon codes, shipping, notes.',
                'status' => 'open',
            ],
            [
                'title' => 'Rapportage: welke producten belanden vaak in cart maar worden niet besteld.',
                'status' => 'open',
            ],
        ],

        'Frontend & UX' => [
            [
                'title' => 'Toast/alert melding in frontend bij voorraad-fouten (“Dit product is uitverkocht”).',
                'status' => 'open',
            ],
            [
                'title' => 'Checkout flow verbeteren: loading states, betere validatie (email, telefoon).',
                'status' => 'open',
            ],
            [
                'title' => 'Responsive UI tweaks voor cart overlay en checkout-pagina.',
                'status' => 'open',
            ],
        ],

        'Logging & Beheer' => [
            [
                'title' => 'Automatisch changelog systeem bouwen → wijzigingen loggen naar changelog.txt.',
                'status' => 'done',
                'links' => ['/changelog.txt'],
            ],
            [
                'title' => 'Footer aanpassen om laatste changelog entries dynamisch weer te geven.',
                'status' => 'open',
            ],
            [
                'title' => 'Admin dashboard uitbreiden met log van coupons en bestellingen.',
                'status' => 'open',
            ],
            [
                'title' => 'Error logging centraliseren naar logs/ i.p.v. output naar gebruiker.',
                'status' => 'open',
            ],
        ],
    ],
];


