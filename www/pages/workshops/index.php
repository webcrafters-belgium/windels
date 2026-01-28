<?php
require $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';
include $_SERVER['DOCUMENT_ROOT'] . '/header.php';

// Haal goedgekeurde workshops op (toekomstige)
$bookings = [];
$today = date('Y-m-d');
$stmt = $conn->prepare("SELECT * FROM workshop_bookings WHERE status = 'approved' AND date >= ? ORDER BY date ASC");
$stmt->bind_param("s", $today);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $bookings[] = $row;
}

// Haal geblokkeerde dagen op (optioneel)
$blocked = [];
$res2 = $conn->query("SELECT * FROM workshop_blocked_days WHERE date >= CURDATE() ORDER BY date ASC");
if ($res2 && $res2->num_rows > 0) {
    while ($r = $res2->fetch_assoc()) {
        $blocked[] = $r;
    }
}
?>

<div class="container py-5">
    <h1 class="mb-4 text-primary">🎨 Workshops bij Windels Green & Deco Resin</h1>

    <p>Welkom bij onze creatieve workshops! We bieden vanaf 15 oktober 2025 sessies aan rond <strong>epoxy, kaarsen en terrazzo</strong>, in een ontspannen sfeer en met professionele begeleiding.</p>

    <p>
        📍 <strong>Locatie:</strong> Beukenlaan 8, 3930 Hamont-Achel<br>
        👥 <strong>Max. 12 deelnemers</strong> per sessie<br>
        ⏰ <strong>Tijdsloten:</strong> Donderdag–Zaterdag 18:30–23:00, Zondag 10:00–18:00</p>

    <div class="mt-4 text-center">
        <a href="/pages/workshops/inschrijven/" class="btn btn-primary btn-lg">✍️ Schrijf je nu in</a>
    </div>

    <hr class="my-5">

    <h3 class="mb-3">📅 Aankomende workshops</h3>
    <?php if (count($bookings) > 0): ?>
        <ul class="list-group mb-4">
            <?php foreach ($bookings as $b): ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <?= date('d-m-Y', strtotime($b['date'])) ?> (<?= substr($b['time_from'], 0, 5) ?> – <?= substr($b['time_to'], 0, 5) ?>)
                    <span class="badge bg-success"><?= $b['persons'] ?> ingeschreven</span>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>Er zijn nog geen geplande workshops zichtbaar.</p>
    <?php endif; ?>

    <?php if (count($blocked) > 0): ?>
        <h5 class="mt-5">❌ Geen workshops op deze data:</h5>
        <ul class="list-inline">
            <?php foreach ($blocked as $d): ?>
                <li class="list-inline-item badge bg-danger m-1">
                    <?= date('d-m-Y', strtotime($d['date'])) ?> (<?= htmlspecialchars($d['reason']) ?>)
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/footer.php'; ?>
