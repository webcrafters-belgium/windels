<?php
require $_SERVER["DOCUMENT_ROOT"] . '/ini.inc';


if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Zorg ervoor dat de ID een geldig getal is

    // Gebruik een voorbereide statement om SQL-injecties te voorkomen
    $stmt = $pdo_voedselproblemen->prepare("SELECT * FROM terugroepacties WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        // Als de ID niet bestaat, redirect naar het overzicht met een foutmelding
        header("Location: view_recall.php?error=notfound");
        exit();
    }
} else {
    // Als er geen ID is opgegeven, redirect naar het overzicht
    header("Location: view_recall.php");
    exit();
}
?>
<?php require $_SERVER["DOCUMENT_ROOT"] . '/header.php';
require $_SERVER["DOCUMENT_ROOT"] . '/voedselproblemen/templates/header.php'; ?>
<div class="card">
    <div class="card-header bg-primary text-white">
        <h3>Terugroepactie Details</h3>
    </div>
    <div class="card-body">
        <p><strong>Dossiernummer:</strong> <?php echo htmlspecialchars($row['dossiernummer']); ?></p>
        <p><strong>Productnaam:</strong> <?php echo htmlspecialchars($row['productnaam']); ?></p>
        <p><strong>Klacht:</strong> <?php echo htmlspecialchars($row['klacht']); ?></p>
        <p><strong>Klachtinformatie:</strong> <?php echo htmlspecialchars($row['klachtinformatie']); ?></p>
        <p><strong>FAVV Dossiernummer:</strong> <?php echo htmlspecialchars($row['favv_dossiernummer']); ?></p>
        <p><strong>FAVV Klachtinformatie:</strong> <?php echo htmlspecialchars($row['favv_klachtinformatie']); ?></p>
        <p><strong>Datum Terugroepactie:</strong> <?php echo htmlspecialchars($row['datum_terugroepactie']); ?></p>
        <p><strong>Status:</strong> <?php echo htmlspecialchars($row['status']); ?></p>

        <a href="view_recall.php" class="btn btn-primary">Terug naar overzicht</a>
        <a href="edit_recall.php?id=<?php echo htmlspecialchars($row['id']); ?>" class="btn btn-warning">Bewerken</a>
    </div>
</div>

<?php require $_SERVER["DOCUMENT_ROOT"] . '/footer.php'; ?>
