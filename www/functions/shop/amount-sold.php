<?php
/**
 *  /functions/shop/amount-sold.php
 *  Geeft de 10 best verkochte producten terug als zuivere JSON‑array.
 *
 *  ▸   Verkoopcijfers worden live berekend uit order_items
 *  ▸   Eerste afbeelding komt uit product_images (koppeling via product_id)
 *  ▸   Return‑formaat = plain array → sluit naadloos aan op je huidige JS
 */

header('Content-Type: application/json; charset=utf-8');

require_once $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';

/* ─────────────────────────────────────────────────────────────
   Top‑10 op basis van totaal verkochte aantallen               */
$sql = "
    SELECT
        p.id,
        p.sku,
        p.name,
        p.price,
        SUM(oi.quantity)               AS amount_sold,
        MAX(o.completed_at)            AS last_sold_at,
        MIN(pi.image_url)              AS image_url      -- eerste niet‑lege afbeelding
    FROM            order_items        oi
    JOIN            orders             o   ON o.id       = oi.order_id
    JOIN            products           p   ON p.id       = oi.product_id
    LEFT JOIN       product_images     pi  ON pi.product_id = p.id
                                           AND pi.image_url <> ''
    GROUP BY        p.id, p.sku, p.name, p.price
    ORDER BY        amount_sold DESC, last_sold_at DESC
    LIMIT 10
";

$result = $conn->query($sql);
if (!$result) {
    http_response_code(500);
    echo json_encode(['error' => $conn->error]);
    exit;
}

$products = [];
while ($row = $result->fetch_assoc()) {
    $products[] = [
        'id'          => (int)$row['id'],
        'sku'         => $row['sku'],
        'name'        => $row['name'],
        // Formatteer prijs pas in JS → daar kun je Intl.NumberFormat gebruiken
        'price'       => (float)$row['price'],
        'amount_sold' => (int)$row['amount_sold'],
        'image'       => $row['image_url'] ?: '/images/placeholder.svg'
    ];
}

echo json_encode($products, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
exit;
