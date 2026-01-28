<?php
echo'
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="utf-8">
    <style>
        body { font-family:Arial,sans-serif; color:#333; }
        .header { text-align:center; margin-bottom:20px; }
        .header img { max-height:60px; }
        .content { max-width:600px; margin:0 auto; }
        table { width:100%; border-collapse:collapse; margin:20px 0; }
        th, td { padding:8px; border:1px solid #ddd; }
        thead { background:#f7fafc; }
        .total { text-align:right; font-size:1.1em; margin-top:10px; }
        .cta-box { background:#f7fafc; padding:15px; border-radius:8px; margin:20px 0; }
        .cta-box h3 { margin-top:0; color:#2f855a; }
        .footer { font-size:0.9em; color:#666; margin-top:30px; }
        a.button { display:inline-block; background:#2f855a; color:#fff; padding:10px 20px; border-radius:5px; text-decoration:none; }
    </style>
</head>
<body>
<div class="content">
    <div class="header">
        <img src="cid:logo_cid" alt="Windels Green & Deco Resin">
        <h2>Bedankt voor je bestelling #'.htmlspecialchars($orderId).'</h2>
    </div>
    <p>Beste klant,</p>
    <p>Je bestelling <strong>#'.htmlspecialchars($orderId).'</strong> is succesvol ontvangen en we hebben je betaling van <strong>&euro;'.number_format($totalPrice,2,',','.').'</strong> verwerkt.</p>
    <p>Wij streven ernaar je bestelling binnen <strong>1–3 werkdagen</strong> bij je af te leveren. Je ontvangt een track & trace-link zodra je pakket is aangeboden bij de vervoerder.</p>
    <table>
        <thead>
        <tr>
            <th>Product</th>
            <th>Aantal</th>
            <th style="text-align:right">Prijs per stuk</th>
        </tr>
        </thead>
        <tbody>';
        // Haal order_items op
        global $conn;
        $stmt = $conn->prepare("
        SELECT p.name, oi.quantity, oi.unit_price
        FROM order_items oi
        JOIN products p ON p.id = oi.product_id
        WHERE oi.order_id = ?
        ");
        $stmt->bind_param("i", $orderId);
        $stmt->execute();
        $items = $stmt->get_result();
        while ($item = $items->fetch_assoc()) {
        $body .= '<tr>
            <td>'.htmlspecialchars($item['name']).'</td>
            <td style="text-align:center">'.$item['quantity'].'</td>
            <td style="text-align:right">&euro;'.number_format($item['unit_price'],2,',','.').'</td>
        </tr>';
        }
        $stmt->close();
        // Tabel en totaalprijs sluiten
        $body .= '</tbody></table>';
    $body .= '<p class="total"><strong>Totaal: &euro;'.number_format($totalPrice,2,',','.').'</strong></p>';
    // Call-to-action om account aan te maken
    $body .= '<div class="cta-box">
        <h3>Maak een account aan voor extra voordelen!</h3>
        <ul style="list-style:disc inside; text-align:left; margin:0; padding:0 0 0 20px;">
            <li>🎁 10% welkomstkorting op je volgende bestelling</li>
            <li>📬 Exclusieve nieuwsbrief met aanbiedingen</li>
            <li>🚚 Realtime tracking in je dashboard</li>
            <li>⭐️ Wishlist voor je favoriete producten</li>
            <li>🔒 Beheer al je bestellingen en facturen</li>
        </ul>
        <p style="text-align:center; margin-top:15px;">
            <a href="https://windelsgreen-decoresin.com/pages/account/register" class="button">Maak nu je account aan</a>
        </p>
    </div>';
    // Footer en afsluiting
    $body .= '<p class="footer">Heb je vragen? Mail naar <a href="mailto:info@windelsgreen-decoresin.com">info@windelsgreen-decoresin.com</a> of bel +32 11 75 33 19.</p>';
    $body .= '<p class="footer">Met vriendelijke groet,<br>Team Windels Green & Deco Resin</p>';
    $body .= '</div></body></html>';