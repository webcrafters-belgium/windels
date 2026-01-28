<?php

require $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';
require $_SERVER['DOCUMENT_ROOT'] . '/header.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<div class='container py-5'><h3>❌ Geen geldig ID opgegeven.</h3></div>";
    require $_SERVER['DOCUMENT_ROOT'] . '/footer.php';
    exit;
}

$newsletterId = (int)$_GET['id'];

$stmt = $conn->prepare("SELECT subject, message, created_at FROM newsletters WHERE id = ?");
$stmt->bind_param("i", $newsletterId);
$stmt->execute();
$result = $stmt->get_result();
$newsletter = $result->fetch_assoc();
$stmt->close();

if (!$newsletter) {
    echo "<div class='container py-5'><h3>❌ Nieuwsbrief niet gevonden.</h3></div>";
    require $_SERVER['DOCUMENT_ROOT'] . '/footer.php';
    exit;
}
?>

<div class="container py-5">
    <h1 class="mb-4">📬 Nieuwsbrief bekijken</h1>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h3 class="card-title"><?= htmlspecialchars($newsletter['subject']) ?></h3>
            <p class="text-muted">Verzonden op <?= date('d-m-Y H:i', strtotime($newsletter['created_at'])) ?></p>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <h5 class="card-subtitle mb-3">Berichtinhoud:</h5>
            <div class="newsletter-message" style="white-space:pre-wrap;"><?= nl2br(htmlspecialchars($newsletter['message'])) ?></div>
        </div>
    </div>

    <a href="/admin/pages/newsletter/" class="btn btn-secondary mt-4">← Terug naar overzicht</a>
</div>

<?php require $_SERVER['DOCUMENT_ROOT'] . '/footer.php'; ?>
