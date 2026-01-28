<?php
include dirname($_SERVER['DOCUMENT_ROOT']) . '/secure/ini.inc';
include dirname($_SERVER['DOCUMENT_ROOT']) . '/partials/header.php';

$zoekterm = trim($_GET['zoek'] ?? '');
$diensten = [];

$sql = "
    SELECT id, bedrijf_naam, contact_naam, adres, telefoon, email 
    FROM funeral_partners 
";

// Voeg filter toe indien zoekterm bestaat
if ($zoekterm !== '') {
    $zoekEscaped = $mysqli->real_escape_string($zoekterm);

    $woorden = explode(' ', $zoekEscaped);
    $filters = [];

    foreach ($woorden as $woord) {
        $woord = trim($woord);
        if ($woord !== '') {
            $filters[] = "(bedrijf_naam LIKE '%$woord%' OR adres LIKE '%$woord%' OR contact_naam LIKE '%$woord%')";
        }
    }

    if (!empty($filters)) {
        $sql .= " WHERE " . implode(' OR ', $filters);
    }
}


$sql .= " ORDER BY bedrijf_naam ASC";
$res = $mysqli->query($sql);
while ($row = $res->fetch_assoc()) {
    $diensten[] = $row;
}
?>

<style>
.uitvaart-container {
    padding: 3rem 0;
}
.uitvaart-container .search-bar {
    text-align: center;
    margin-bottom: 2rem;
}
.uitvaart-container input[type="text"] {
    padding: 0.5rem 1rem;
    border: 1px solid #ccc;
    border-radius: 25px;
    width: 300px;
    max-width: 100%;
    font-size: 1rem;
}
.uitvaart-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 1.5rem;
}
.uitvaart-card {
    background: url('/img/uitvaartachtergrond.jpg') no-repeat center center/cover;
    color: #f5f5f5;
    border-radius: 18px;
    padding: 2rem;
    position: relative;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    display: flex;
    flex-direction: column;
    justify-content: space-between; /* ❗ content boven / knop onder */
    height: 100%; /* ❗ alle kaarten zelfde hoogte */
    min-height: 320px;
    transition: transform 0.3s ease;
}


.uitvaart-card:hover {
    transform: translateY(-4px);
}

.uitvaart-card::before {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(to bottom, rgba(0,0,0,0.2), rgba(0,0,0,0.6));
    z-index: 0;
    border-radius: 18px;
}

.uitvaart-card > * {
    position: relative;
    z-index: 1;
}

.uitvaart-card h3 {
    font-size: 1.4rem;
    margin-bottom: .5rem;
    color: #ffffff;
    text-shadow: 0 1px 3px rgba(0,0,0,0.4);
}

.uitvaart-card p {
    margin: 0.25rem 0;
    font-size: 1rem;
    color: #f0f0f0;
    text-shadow: 0 1px 2px rgba(0,0,0,0.4);
}

.uitvaart-card a.mail{
    color: #aad2f0;
    text-decoration: underline;
    font-weight: 500;
}

.uitvaart-card .btn {
    background-color: #ffffff;
    color: #1e4025;
    padding: 0.5rem 1.2rem;
    border-radius: 30px;
    text-align: center;
    font-weight: 600;
    text-decoration: none;
    margin-top: 1rem;
    transition: background-color 0.3s ease;
}

.uitvaart-card .btn:hover {
    background-color: #d9f0e3;
}
</style>

<main class="uitvaart-container">
    <div class="container">
        <h1>Onze uitvaartpartners</h1>
        <p>Onderstaande uitvaartdiensten werken samen met ons. Je kan hen contacteren om een bestelling door te geven.</p>

        <form method="get" class="search-bar" style="margin-top: 2rem;">
            <input type="text" name="zoek" placeholder="Zoek op adres..." value="<?= htmlspecialchars($zoekterm) ?>">
            <?php if ($zoekterm): ?>
                <a href="contacteer-uitvaartdienst.php" class="btn btn-secondary" style="margin-left:10px; background-color:#1e4025; color: #fff; border-radius: 30px;">Reset</a>
            <?php endif; ?>
        </form>

        <?php if (count($diensten) === 0): ?>
            <p style="text-align:center; margin-top:2rem;">Geen uitvaartdiensten gevonden voor deze zoekterm.</p>
        <?php else: ?>
            <div class="uitvaart-grid">
                <?php foreach ($diensten as $d): ?>
                    <div class="uitvaart-card">
                        <h3><?= htmlspecialchars($d['bedrijf_naam']) ?></h3>
                        <p><strong>Contact:</strong> <?= htmlspecialchars($d['contact_naam']) ?></p>
                        <p><strong>Adres:</strong> <?= nl2br(htmlspecialchars($d['adres'])) ?></p>
                        <?php if ($d['telefoon']): ?>
                            <p><strong>Tel:</strong> <?= htmlspecialchars($d['telefoon']) ?></p>
                        <?php endif; ?>
                        <p><strong>E-mail:</strong>
                            <a class="mail" href="mailto:<?= htmlspecialchars($d['email']) ?>">
                                <?= htmlspecialchars($d['email']) ?>
                            </a>
                        </p>
                        <a href="partner.php?id=<?= $d['id'] ?>" class="btn btn-light">Meer info</a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php include dirname($_SERVER['DOCUMENT_ROOT']) . '/partials/footer.php'; ?>
