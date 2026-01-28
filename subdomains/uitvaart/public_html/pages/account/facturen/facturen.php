<?php
session_start();
if (!isset($_SESSION['partner_id'])) {
    header("Location: ../login.php");
    exit;
}

require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/secure/ini.inc'; // mysqli_medewerkers zit hierin

$partner_id = $_SESSION['partner_id'];

// Facturen ophalen uit medewerkers-database
$query = "SELECT factuurnummer, factuurdatum, bestand, status FROM facturen WHERE partner_id = ? ORDER BY factuurdatum DESC";
$stmt = $mysqli_medewerkers->prepare($query);
$stmt->bind_param("i", $partner_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<?php include dirname($_SERVER['DOCUMENT_ROOT']) . '/partials/header.php'; ?>
<style>
      body {
    background: url('/img/uitvaartachtergrond.jpg') no-repeat center center fixed;
    background-size: cover;
     }
     .dashboard-page {
    background-color: rgba(255, 255, 255, 0.9);
    padding: 3rem 2rem;
    border-radius: 12px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    margin: 3rem auto 2rem auto;
}
    .info-box {
    background-color: #f5f5f5;
    border-left: 4px solid #5a7d5a; /* donkergroen zoals in huisstijl */
    padding: 1rem 1.5rem;
    margin: 1.5rem 0;
    font-size: 1rem;
    color: #333;
    border-radius: 0.5rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
}
td{
    text-align: center;
}
.status-badge {
    display: inline-block;
    padding: 0.3rem 0.6rem;
    font-size: 0.85rem;
    border-radius: 12px;
    color: white;
    font-weight: bold;
}
.status-betaald {
    background-color: #4caf50; /* groen */
}
.status-verzonden {
    background-color: #ff9800; /* oranje */
}

.btn.btn-small {
    padding: 0.4rem 0.8rem;
    font-size: 0.9rem;
    background-color: #5a7d5a;
    color: white;
    text-decoration: none;
    border-radius: 4px;
}
.btn.btn-small:hover {
    background-color: #466346;
}
</style>
<main class="dashboard-page container">
    <div class="dashboard-welcome">
        <h1>Mijn Facturen</h1>
        <p>Bekijk hieronder het overzicht van alle facturen die door onze administratie zijn opgemaakt. Je kan ze eenvoudig downloaden in PDF-formaat.</p>
    </div>

    <?php if ($result->num_rows === 0): ?>
        <div class="info-box">
            <p>Er zijn nog geen facturen beschikbaar voor jouw account.</p>
        </div>
    <?php else: ?>
        <div class="dashboard-table">
            <table class="simple-table">
                <thead>
                    <tr>
                        <th>Factuurnummer</th>
                        <th>Datum</th>
                        <th>Status</th>
                        <th>Download</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($factuur = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($factuur['factuurnummer']) ?></td>
                            <td><?= date('d/m/Y', strtotime($factuur['factuurdatum'])) ?></td>
                            <td>
                                <?php
                                    $status = strtolower(trim($factuur['status']));
                                    $badge_class = match ($status) {
                                        'betaald' => 'status-betaald',
                                        'verzonden' => 'status-verzonden',
                                        default => '',
                                    };
                                ?>
                                <span class="status-badge <?= $badge_class ?>">
                                    <?= htmlspecialchars($factuur['status']) ?>
                                </span>
                            </td>
                            <td>
                                <a class="btn btn-small" href="/pages/account/facturen/pdf/<?= urlencode($factuur['bestand']) ?>" target="_blank" download>
                                    PDF Downloaden
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

    <div class="dashboard-actions">
        <div class="dashboard-card">
            <h2>Terug naar Overzicht</h2>
            <p>Keer terug naar het facturatiegedeelte van je account.</p>
            <a href="index.php" class="btn">Terug naar facturen</a>
        </div>
    </div>
</main>

<?php include dirname($_SERVER['DOCUMENT_ROOT']) . '/partials/footer.php'; ?>
