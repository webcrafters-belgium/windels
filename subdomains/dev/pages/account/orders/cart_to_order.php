<?php

include dirname($_SERVER['DOCUMENT_ROOT']).'/secure/ini.inc';
session_start();

if (empty($_SESSION['partner_id'])) {
    header('Location:/pages/account/login.php');
    exit;
}
$partner_id = (int)$_SESSION['partner_id'];

// NL-partner detecteren
$btwNummer   = isset($_SESSION['btw_nummer']) ? strtoupper($_SESSION['btw_nummer']) : '';
$isNlPartner = str_starts_with($btwNummer, 'NL');

if (empty($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
}
$csrf = $_SESSION['csrf'];

$percentageuitvaart = 1; // standaardwaarde

if (isset($mysqli) && $mysqli instanceof mysqli) {
    $db = $mysqli;
    try {
        if ($st = $db->prepare("
            SELECT level 
            FROM uitvaart_level_schedule 
            WHERE applied = 1
            ORDER BY effective_date DESC
            LIMIT 1
        ")) {
            if ($st->execute()) {
                $st->bind_result($lvl);
                if ($st->fetch()) {
                    $lvl = (int)$lvl;
                    if ($lvl >= 1 && $lvl <= 5) {
                        $percentageuitvaart = $lvl;
                    }
                }
            }
            $st->close();
        }
    } catch (mysqli_sql_exception $e) {
        // tabel niet aanwezig of andere fout → percentageuitvaart blijft 1
    }
}

// DB-handle kiezen
$DB = null;
if (isset($mysqli_medewerkers) && $mysqli_medewerkers instanceof mysqli) {
    $DB = $mysqli_medewerkers;
} elseif (isset($mysqli) && $mysqli instanceof mysqli) {
    $DB = $mysqli;
}
if (!$DB) {
    http_response_code(500);
    die('DB-verbinding ontbreekt.');
}

// Cart ophalen (voor deze partner)
$cart_id = 0;
if ($st = $DB->prepare("SELECT id FROM carts WHERE partner_id=?")) {
    $st->bind_param('i', $partner_id);
    $st->execute();
    $st->bind_result($cart_id);
    $st->fetch();
    $st->close();
}
if (!$cart_id) {
    header('Location:/pages/account/orders/cart.php');
    exit;
}

// Items + totaal + variant_meta
$items = [];
$total = 0.0;

if ($st = $DB->prepare("
  SELECT 
    product_type,
    product_id,
    name,
    unit_price,
    qty,
    variant_meta,
    CASE 
      WHEN product_type='epoxy'  THEN (SELECT margin FROM epoxy_products  e WHERE e.id=product_id LIMIT 1)
      WHEN product_type='kaars'  THEN (SELECT margin FROM kaarsen_products k WHERE k.id=product_id LIMIT 1)
      WHEN product_type='inkoop' THEN (SELECT margin FROM inkoop_products i WHERE i.id=product_id LIMIT 1)
      ELSE NULL
    END AS margin
  FROM cart_items
  WHERE cart_id IN (SELECT id FROM carts WHERE id=? AND partner_id=?)
  ORDER BY id ASC
")) {
    $st->bind_param('ii', $cart_id, $partner_id);
    $st->execute();
    $st->bind_result($ptype, $pid, $name, $price, $qty, $vmeta, $margin);

    // basis-btw op consumentenprijs is altijd 21%
    $BASE_VAT_RATE = 0.21;

    while ($st->fetch()) {
        $qtyInt    = (int)$qty;
        $priceF    = (float)$price; // consumentenprijs incl. 21% btw
        $isBlocked = in_array((int)$pid, [34, 35, 36, 38, 39], true);
        $marginPct = ($margin !== null) ? (float)$margin : 0.0;

        // partnerprijs per stuk (incl. btw voor BE, excl. btw voor NL)
        if ($isBlocked) {
            // geen korting → partner betaalt consumentenprijs
            // NL-partner: prijs excl. btw
            if ($isNlPartner) {
                $unit_partner = $priceF / (1 + $BASE_VAT_RATE);
            } else {
                $unit_partner = $priceF;
            }
        } else {
            // stap 1: consumentenprijs -> excl. 21% btw
            $priceExVat = $priceF / (1 + $BASE_VAT_RATE);

            // marge als markup op kostprijs
            $markup = $marginPct / 100;
            if ($markup <= -1) {
                // safety: onzinmarge → geen korting toepassen
                $partnerPriceExVat = $priceExVat;
            } else {
                // kostprijs uit markup
                $productCost  = $priceExVat / (1 + $markup);
                $marginAmount = $priceExVat - $productCost;

                // helft marge terug aan partner (via level)
                $halfMargin        = $marginAmount / $percentageuitvaart;
                $partnerPriceExVat = $productCost + $halfMargin;
            }

            // BE-partner: incl. btw, NL-partner: excl. btw
            if ($isNlPartner) {
                $unit_partner = $partnerPriceExVat;
            } else {
                $unit_partner = $partnerPriceExVat * (1 + $BASE_VAT_RATE);
            }
        }

        $line_partner = $unit_partner * $qtyInt;
        // NL-partner: geen btw berekenen
        $line_vat = $isNlPartner ? 0.0 : vat_from_gross($line_partner, $BASE_VAT_RATE);

        $total += $line_partner;

        // variant_meta decoderen
        $vm = null;
        if ($vmeta) {
            $dec = json_decode($vmeta, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($dec)) {
                $vm = $dec;
            }
        }

        $items[] = [
                'ptype'        => $ptype,
                'pid'          => $pid,
                'name'         => $name,
                'price'        => $unit_partner,     // partnerprijs per stuk
                'qty'          => $qtyInt,
                'line'         => $line_partner,     // lijnbedrag incl. btw
                'line_vat'     => $line_vat,         // btw per lijn
                'variant_meta' => $vm,
                'blocked'      => $isBlocked,
        ];
    }
    $st->close();
}

if (empty($items)) {
    header('Location:/pages/account/orders/cart.php');
    exit;
}

function h($s) {
    return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8');
}

// BTW/valuta
$currency = '<sup>€</sup>';

// NL-partner: 0% btw, BE: 21%
$VAT_RATE = $isNlPartner ? 0.0 : 0.21;

function vat_from_gross($g, $r = 0.21) {
    if ($r <= 0) {
        return 0.0;
    }
    return $g * ($r / (1 + $r)); // 21/121
}

$vat_total_products = vat_from_gross($total, $VAT_RATE);

// INSTELLINGEN
$SHOP_ADDRESS = 'Beukenlaan 8, 3930 Hamont-Achel, BE';

$SUMMER_HOURS = [
        0 => '',
        1 => '19:00–21:00',
        2 => '19:00–21:00',
        3 => '19:00–21:00',
        4 => '10:00–21:00',
        5 => '10:00–21:00',
        6 => '10:00–18:00',
];
$WINTER_HOURS = [
        0 => '',
        1 => '19:00–21:00',
        2 => '19:00–21:00',
        3 => '19:00–21:00',
        4 => '10:00–18:00',
        5 => '10:00–18:00',
        6 => '10:00–18:00',
];

// Uitzonderlijke dagen includen via filesystem
$EXCEPTIONS = [];
(function () use (&$EXCEPTIONS) {
    $docroot = rtrim($_SERVER['DOCUMENT_ROOT'] ?? '', '/');
    $candidates = [
            dirname($docroot) . '/medewerkers.windelsgreen-decoresin.com/status_uitzonderlijk_winkel.php',
            '/var/www/medewerkers.windelsgreen-decoresin.com/public_html/status_uitzonderlijk_winkel.php',
    ];
    $found = null;
    foreach ($candidates as $p) {
        if (is_readable($p)) {
            $found = $p;
            break;
        }
    }
    if (!$found) {
        $maybe = glob(dirname($docroot) . '/*/status_uitzonderlijk_winkel.php');
        if (is_array($maybe)) {
            foreach ($maybe as $p) {
                if (is_readable($p)) {
                    $found = $p;
                    break;
                }
            }
        }
    }
    if ($found) {
        $uitzonderlijkeDagen = [];
        (function ($file, &$uitzonderlijkeDagen) {
            include $file;
            if (!isset($uitzonderlijkeDagen) || !is_array($uitzonderlijkeDagen)) {
                $uitzonderlijkeDagen = [];
            }
        })($found, $uitzonderlijkeDagen);

        foreach ($uitzonderlijkeDagen as $d => $arr) {
            $EXCEPTIONS[$d] = [
                    'status' => isset($arr[0]) ? strtolower(trim($arr[0])) : 'gesloten',
                    'reason' => $arr[1] ?? '',
                    'start'  => $arr[2] ?? '',
                    'end'    => $arr[3] ?? '',
            ];
        }
    }
})();

include dirname($_SERVER['DOCUMENT_ROOT']).'/partials/header.php';
?>

<link rel="stylesheet"href="/css/orders/cart_to_order.css">


<main
        class="checkout-wrap"
        data-shop-address="<?= h($SHOP_ADDRESS) ?>"
        data-summer-hours='<?= json_encode($SUMMER_HOURS, JSON_UNESCAPED_UNICODE) ?>'
        data-winter-hours='<?= json_encode($WINTER_HOURS, JSON_UNESCAPED_UNICODE) ?>'
        data-exceptions='<?= json_encode($EXCEPTIONS, JSON_UNESCAPED_UNICODE) ?>'
        data-is-nl-partner="<?= $isNlPartner ? '1' : '0' ?>"
>

    <h2 class="h2">Afrekenen</h2>

    <div class="grid">

        <section>

            <form action="/pages/account/orders/bestel_verwerk.php" method="post" id="order-form" novalidate>

                <input type="hidden" name="csrf" value="<?= h($csrf) ?>">
                <?php foreach ($items as $i => $it) {
                    echo '<input type="hidden" name="producten['.$i.'][id]" value="'.(int)$it['pid'].'">';
                    echo '<input type="hidden" name="producten['.$i.'][qty]" value="'.(int)$it['qty'].'">';
                } ?>

                <fieldset>
                    <legend>Klantgegevens</legend>
                    <label for="klant_naam">Naam*</label>
                    <input type="text" name="klant_naam" id="klant_naam" required>

                    <label for="klant_email">E-mail*</label>
                    <input type="email" name="klant_email" id="klant_email" required>

                    <label for="klant_telefoon">Telefoon*</label>
                    <input type="text" name="klant_telefoon" id="klant_telefoon" required>

                    <label for="klant_adres">Adres*</label>
                    <textarea name="klant_adres" id="klant_adres" rows="3" required placeholder="Straat + nr, postcode, gemeente, land"></textarea>

                    <label for="klant_land">Land*</label>
                    <select name="klant_land" id="klant_land" required>
                        <option value="BE" selected>België</option>
                        <option value="NL">Nederland</option>
                    </select>

                    <div class="small muted">
                        Afstand wordt automatisch berekend op basis van luchtlijnafstand (Haversine-formule).
                        Indien geen resultaat: handmatige km-invoer beschikbaar.
                    </div>
                    <div class="row" style="margin-top:6px">
                        <button type="button" class="btn" id="btn_calc_distance">Bereken afstand</button>
                        <span class="small muted" id="distance_status">Nog niet berekend</span>
                    </div>

                    <label for="partner_opmerking">Opmerking (optioneel)</label>
                    <textarea name="partner_opmerking" id="partner_opmerking" rows="4" maxlength="5000" placeholder="Bijv. gravure, leveropmerking…"></textarea>
                </fieldset>

                <fieldset>
                    <legend>As aanleveren</legend>

                    <label class="inline">
                        <input type="radio" name="ashes_delivery_method" value="zelf_bezorgen" required>
                        <span>Zelf bezorgen</span>
                    </label>
                    <div id="ashes_self_info" class="small muted hidden" style="margin-top:6px">
                        Adres: <span class="badge"><?= h($SHOP_ADDRESS) ?></span>
                        <div style="margin-top:6px">
                            <div class="small muted" style="margin-bottom:4px">Openingsuren</div>
                            <ul id="self_opening_hours" class="small" style="margin:0;padding-left:16px"></ul>
                        </div>
                    </div>

                    <label class="inline" style="margin-top:8px">
                        <input type="radio" name="ashes_delivery_method" value="afgehaald_door_ons">
                        <span>Wordt afgehaald door ons (tegen tarief)</span>
                    </label>
                    <div id="ashes_collect_box" class="hidden" style="margin-top:6px">
                        <div class="row">
                            <div style="flex:1;min-width:220px">
                                <label for="ashes_collect_date">Datum</label>
                                <input type="date" id="ashes_collect_date" name="ashes_collect_date" min="<?= date('Y-m-d') ?>">
                            </div>
                            <div style="flex:1;min-width:220px">
                                <label for="ashes_collect_time">Uur</label>
                                <input type="time" id="ashes_collect_time" name="ashes_collect_time" step="900">
                            </div>
                        </div>
                        <div id="exception_msg" class="note hidden" style="margin-top:6px"></div>
                        <div class="note" style="margin-top:8px">Kies wanneer wij bij u mogen langskomen om de as op te halen.</div>
                        <div class="row">
                            <div style="flex:1;min-width:220px">
                                <label>Afstand vanaf winkel</label>:
                                <span class="badge" id="distance_display_ashes">0 km</span>
                                <div class="small muted">
                                    Tarief: <span id="delivery_tariff_text"><sup>€</sup>3,80 per 10 km</span> (pro rata), max 50 km
                                </div>
                                <div class="small warn hidden" id="distance_warning_ashes" style="margin-top:6px"></div>
                            </div>
                            <div style="flex:1;min-width:220px">
                                <div class="small muted">
                                    <label>Kost enkel rit (incl. btw)</label>
                                    <div class="badge" id="fee_badge_ashes"><sup>€</sup>0,00</div>
                                    <div class="small muted">Waarvan btw (21%): <span id="fee_vat_ashes"><sup>€</sup>0,00</span></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <label class="inline" style="margin-top:8px">
                        <input type="radio" name="ashes_delivery_method" value="koerier">
                        <span>Verzenden via koerier</span>
                    </label>
                    <div id="ashes_courier_note" class="small note hidden" style="margin-top:6px">
                        Verzending via koerier gebeurt <strong>op eigen risico</strong>. Verpak de as veilig volgens de instructies die je per e-mail ontvangt.
                    </div>
                </fieldset>

                <fieldset>
                    <legend>Afgewerkt product ontvangen</legend>

                    <label class="inline">
                        <input type="radio" name="finished_delivery_method" value="afhalen_winkel" required>
                        <span>Afhalen in winkel</span>
                    </label>
                    <div id="finished_pickup_info" class="small muted hidden">
                        Adres: <span class="badge"><?= h($SHOP_ADDRESS) ?></span>
                        <div style="margin-top:6px">
                            <div class="small muted" style="margin-bottom:4px">Openingsuren</div>
                            <ul id="finished_opening_hours" class="small" style="margin:0;padding-left:16px"></ul>
                        </div>
                    </div>

                    <label class="inline" style="margin-top:8px">
                        <input type="radio" name="finished_delivery_method" value="bezorgen">
                        <span>Bezorgen (tegen tarief)</span>
                    </label>
                    <div id="finished_delivery_box" class="hidden" style="margin-top:6px">
                        <div class="row">
                            <div style="flex:1;min-width:220px">
                                <label>Afstand vanaf winkel</label>
                                <div class="badge" id="distance_display">0 km</div>
                                <div class="small muted">
                                    Tarief: <span id="delivery_tariff_text"><sup>€</sup>3,80 per 10 km</span> (pro rata), max 50 km
                                </div>

                                <div class="small warn" id="distance_warning" style="display:none">
                                    Afstand kon niet automatisch berekend worden. Vul onderstaande <b>handmatige afstand</b> in.
                                </div>
                                <div id="manual_distance_wrap" class="hidden" style="margin-top:6px">
                                    <label for="manual_distance_km">Handmatige afstand (km)</label>
                                    <input type="number" id="manual_distance_km" min="1" max="50" step="0.1" placeholder="bijv. 12.5">
                                    <div class="small muted">We hanteren een maximumafstand van 50 km.</div>
                                </div>
                            </div>
                            <div style="flex:1;min-width:220px">
                                <label>Kost enkel rit (incl. btw)</label>
                                <div class="badge" id="fee_badge_finished"><sup>€</sup>0,00</div>
                                <div class="small muted">Waarvan btw (21%): <span id="fee_vat_finished"><sup>€</sup>0,00</span></div>

                                <div class="small note hidden" id="finished_total_row" style="margin-top:6px">
                                    Totaal leveringskosten (2 ritten): <span class="badge" id="finished_total_fee"><sup>€</sup>0,00</span>
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="finished_delivery_fee" id="finished_delivery_fee" value="0">
                        <input type="hidden" name="distance_km_raw" id="distance_km_raw" value="">
                        <input type="hidden" name="distance_km_capped" id="distance_km_capped" value="">
                        <input type="hidden" name="distance_method" id="distance_method" value="">
                    </div>
                </fieldset>

                <div style="display:flex;gap:10px;flex-wrap:wrap">
                    <a class="btn" href="/pages/account/orders/cart.php">← Terug naar winkelwagen</a>
                    <button type="submit" class="btn btn-primary">Ga naar betalen</button>
                </div>

                <input type="hidden" id="order_products_total" value="<?= number_format($total, 2, '.', '') ?>">
                <input type="hidden" name="delivery_cost_total" id="delivery_cost_total" value="0">
                <input type="hidden" name="vat_products_total" id="vat_products_total" value="<?= number_format($vat_total_products, 2, '.', '') ?>">
                <input type="hidden" name="vat_delivery_total" id="vat_delivery_total" value="0">
                <input type="hidden" id="out_of_zone_flag" value="0">
                <input type="hidden" name="vat_order_total" id="vat_order_total" value="<?= number_format($vat_total_products, 2, '.', '') ?>">
                <input type="hidden" name="net_order_total" id="net_order_total" value="<?= number_format($total - $vat_total_products, 2, '.', '') ?>">
            </form>
        </section>

        <aside>
            <div class="summary">
                <h3 class="h2" style="margin-top:0">Overzicht</h3>
                <table class="table">
                    <thead>
                    <tr>
                        <th>Product</th>
                        <th>Type</th>
                        <th><sup>€</sup>/st</th>
                        <th>Aantal</th>
                        <th>Lijn (incl.)</th>
                        <th>BTW (21%)</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($items as $it):
                        $name     = h($it['name']);
                        $ptype    = h($it['ptype']);
                        $price    = number_format($it['price'], 2, ',', '.');
                        $qty      = (int)$it['qty'];
                        $lineGross= $it['line'];
                        $line     = number_format($lineGross, 2, ',', '.');
                        $lineVat  = number_format($it['line_vat'], 2, ',', '.');
                        $vm       = $it['variant_meta'] ?? null;

                        $variantHtml = '';
                        if (is_array($vm) && (!empty($vm['color']) || (!empty($vm['options']) && is_array($vm['options'])))) {
                            $parts = [];
                            if (!empty($vm['color'])) {
                                $hex = h($vm['color']);
                                $parts[] = '<span class="variant-color"><span class="swatch" style="background:'.$hex.'"></span>'.$hex.'</span>';
                            }
                            if (!empty($vm['options'])) {
                                $chips = '';
                                foreach ($vm['options'] as $op) {
                                    $chips .= '<span class="variant-chip">'.h($op).'</span>';
                                }
                                $parts[] = $chips;
                            }
                            if ($parts) {
                                $variantHtml = '<div class="variant-mini">'.implode(' ', $parts).'</div>';
                            }
                        }
                        ?>
                        <tr>
                            <td><?= $name ?><?= $variantHtml ?></td>
                            <td><?= $ptype ?></td>
                            <td><?= $currency ?><?= $price ?></td>
                            <td><?= $qty ?></td>
                            <td><?= $currency ?><?= $line ?></td>
                            <td><?= $currency ?><?= $lineVat ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>

                <div class="hr"></div>
                <div class="total-sub">
                    <div>BTW op producten (21%):</div>
                    <div id="summary_vat_products"><?= $currency ?><?= number_format($vat_total_products, 2, ',', '.') ?></div>
                </div>
                <div id="summary_delivery_lines" class="total-sub hidden">
                    <div>Leveringskosten (incl.):</div>
                    <div id="summary_delivery_amount"><?= $currency ?>0,00</div>
                </div>
                <div id="summary_delivery_vat_line" class="total-sub hidden">
                    <div>BTW op levering (21%):</div>
                    <div id="summary_vat_delivery"><?= $currency ?>0,00</div>
                </div>

                <div class="hr"></div>
                <div class="total-sub">
                    <div>Netto (excl. btw):</div>
                    <div id="summary_net_total"><?= $currency ?><?= number_format($total - $vat_total_products, 2, ',', '.') ?></div>
                </div>
                <div class="total-sub">
                    <div>BTW totaal (21%):</div>
                    <div id="summary_vat_total"><?= $currency ?><?= number_format($vat_total_products, 2, ',', '.') ?></div>
                </div>
                <div class="hr"></div>
                <div class="total-line">
                    <div>Totaal te betalen (incl.): </div>
                    <div id="grand_total"><?= $currency ?><?= number_format($total, 2, ',', '.') ?></div>
                </div>
            </div>
        </aside>
    </div>
</main>

<script src="/js/cart_to_order.js"></script>

<?php include dirname($_SERVER['DOCUMENT_ROOT']).'/partials/footer.php'; ?>
