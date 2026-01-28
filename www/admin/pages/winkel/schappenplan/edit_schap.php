<?php

require $_SERVER["DOCUMENT_ROOT"] . '/ini.inc';
require $_SERVER["DOCUMENT_ROOT"] . '/header.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Haal de gegevens van het schap op
$stmt = $pdo_winkel->prepare("SELECT * FROM winkel_schappen WHERE id = ?");
$stmt->execute([$id]);
$schap = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $naam = $_POST['naam'];
    $locatie = $_POST['locatie'];
    $breedte = (float)$_POST['breedte'];
    $hoogte = (float)$_POST['hoogte'];
    $aantal_planken = (int)$_POST['aantal_planken'];

    // Update de gegevens van het schap
    $stmt = $pdo_winkel->prepare("UPDATE winkel_schappen SET naam = ?, locatie = ?, breedte = ?, hoogte = ?, aantal_planken = ? WHERE id = ?");
    if ($stmt->execute([$naam, $locatie, $breedte, $hoogte, $aantal_planken, $id])) {
        echo "<div class='alert alert-success'>Schap succesvol bijgewerkt!</div>";
    } else {
        echo "<div class='alert alert-danger'>Er is een fout opgetreden bij het bijwerken van het schap.</div>";
    }
}
?>

<div class="container mt-5">
    <h2>Schap Bewerken</h2>
    <form method="POST" action="edit_schap.php?id=<?= htmlspecialchars($id); ?>">
        <div class="form-group">
            <label for="naam">Schap Naam:</label>
            <input type="text" class="form-control" id="naam" name="naam" value="<?= htmlspecialchars($schap['naam']); ?>" required>
        </div>
        <div class="form-group">
            <label for="locatie">Locatie:</label>
            <input type="text" class="form-control" id="locatie" name="locatie" value="<?= htmlspecialchars($schap['locatie']); ?>" required>
        </div>
        <div class="form-group">
            <label for="breedte">Breedte:</label>
            <input type="number" class="form-control" id="breedte" name="breedte" value="<?= htmlspecialchars($schap['breedte']); ?>" step="0.01" required>
        </div>
        <div class="form-group">
            <label for="hoogte">Hoogte:</label>
            <input type="number" class="form-control" id="hoogte" name="hoogte" value="<?= htmlspecialchars($schap['hoogte']); ?>" step="0.01" required>
        </div>
        <div class="form-group">
            <label for="aantal_planken">Aantal Planken:</label>
            <input type="number" class="form-control" id="aantal_planken" name="aantal_planken" value="<?= htmlspecialchars($schap['aantal_planken']); ?>" min="1" required>
        </div>
        <button type="submit" class="btn btn-primary">Bijwerken</button>
        <a href="index.php" class="btn btn-secondary">Terug naar Overzicht</a>
    </form>
</div>

<?php require $_SERVER["DOCUMENT_ROOT"] . '/footer.php'; ?>
