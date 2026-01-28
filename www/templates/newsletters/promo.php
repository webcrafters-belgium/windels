<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';

$today = date('Y-m-d');
$future = date('Y-m-d', strtotime('+14 days'));

$query = "
SELECT 
    p.product_sku, 
    p.title AS promo_title,
    p.subtitle,
    p.discount_percentage,
    p.image_path,
    pr.name AS product_name,
    pr.slug,
    pr.price
FROM promos p
JOIN products pr ON pr.sku = p.product_sku
WHERE p.start_date <= ? AND p.end_date >= ?
ORDER BY p.start_date ASC
LIMIT 6
";

$stmt = $conn->prepare($query);
$stmt->bind_param("ss", $future, $today);

$stmt->execute();
$result = $stmt->get_result();

$promos = [];
while ($row = $result->fetch_assoc()) {
    $promos[] = $row;
}
?>

<h2 style="color: #222;">🎉 Onze huidige promoties</h2>
<p>Deze week bij Windels Green & Deco Resin:</p>

<table width="100%" cellpadding="10" cellspacing="0" border="0" style="font-family: sans-serif;">
    <?php foreach ($promos as $promo): ?>
        <tr>
            <td width="120">
                <img src="https://windelsgreen-decoresin.com<?= htmlspecialchars($promo['image_path']) ?>" alt="<?= htmlspecialchars($promo['product_name']) ?>" width="100" style="border-radius:5px;">
            </td>
            <td>
                <strong><?= htmlspecialchars($promo['promo_title']) ?> – <?= htmlspecialchars($promo['discount_percentage']) ?>%</strong><br>
                <span style="font-size: 14px; color: #555;"><?= htmlspecialchars($promo['subtitle']) ?></span><br>
                <span style="color: #0a6847; font-weight: bold;">€<?= number_format($promo['price'], 2, ',', '.') ?></span><br>
                <a href="https://windelsgreen-decoresin.com/shop/<?= htmlspecialchars($promo['slug']) ?>" style="font-size: 14px; text-decoration: underline;">Bekijk product</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

<?php if (count($promos) === 0): ?>
    <p style="color: #888;">Er zijn momenteel geen actieve promoties.</p>
<?php endif; ?>

<p style="margin-top: 30px;">
    👉 <a href="https://windelsgreen-decoresin.com/shop" style="display:inline-block;background:#0a6847;color:white;padding:10px 20px;text-decoration:none;border-radius:4px;">Bekijk het volledige aanbod</a>
</p>
