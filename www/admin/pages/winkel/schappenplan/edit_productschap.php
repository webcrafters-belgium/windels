<?php


require_once $_SERVER['DOCUMENT_ROOT'] . '/admin/includes/header.php';

$schap_id = isset($_GET['schap_id']) ? (int)$_GET['schap_id'] : 0;

// Haal producten in het schap op
$stmt = $pdo_winkel->prepare("SELECT * FROM product_schap WHERE schap_id = ?");
$stmt->execute([$schap_id]);
$product_schappen = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = (int)$_POST['id'];
    $plank_nummer = (int)$_POST['plank_nummer'];
    $positie_op_plank = (float)$_POST['positie_op_plank'];

    // Update de productpositie in het schap
    $stmt = $pdo_winkel->prepare("UPDATE product_schap SET plank_nummer = ?, positie_op_plank = ? WHERE id = ?");
    if ($stmt->execute([$plank_nummer, $positie_op_plank, $id])) {
        echo "<div class='alert alert-success'>Productpositie succesvol bijgewerkt!</div>";
    } else {
        echo "<div class='alert alert-danger'>Er is een fout opgetreden bij het bijwerken van de productpositie.</div>";
    }
}

// Haal schapnaam op
$stmt = $pdo_winkel->prepare("SELECT naam FROM winkel_schappen WHERE id = ?");
$stmt->execute([$schap_id]);
$schap_naam = $stmt->fetchColumn();
?>

<div class="container mt-5">
    <h2>Producten Bewerken in Schap: <?= htmlspecialchars($schap_naam); ?></h2>
    <?php foreach ($product_schappen as $product_schap): ?>
        <form method="POST" action="edit_productschap.php?schap_id=<?= htmlspecialchars($schap_id); ?>">
            <input type="hidden" name="id" value="<?= htmlspecialchars($product_schap['id']); ?>">
            <div class="form-group">
                <label for="plank_nummer">Plank Nummer:</label>
                <input type="number" class="form-control" id="plank_nummer" name="plank_nummer" value="<?= htmlspecialchars($product_schap['plank_nummer']); ?>" min="1" required>
            </div>
            <div class="form-group">
                <label for="positie_op_plank">Positie op Plank:</label>
                <input type="number" class="form-control" id="positie_op_plank" name="positie_op_plank" value="<?= htmlspecialchars($product_schap['positie_op_plank']); ?>" step="0.01" required>
            </div>
            <button type="submit" class="btn btn-primary">Bijwerken</button>
        </form>
        <hr>
    <?php endforeach; ?>
    <a href="index.php" class="btn btn-secondary">Terug naar Overzicht</a>
</div>

<?php require '../footer.php'; ?>
