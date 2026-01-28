<?php


$web = $_SERVER['PHP_SELF'];
$usernameadmin = $_SESSION['admin_username'];

$secondsWait = 3600; // 1 uur
header("refresh:$secondsWait; /loginout.php?web=$web&adminuser=$usernameadmin");

require $_SERVER["DOCUMENT_ROOT"] . '/ini.inc';
require $_SERVER["DOCUMENT_ROOT"] . '/header.php';
require $_SERVER["DOCUMENT_ROOT"] . '/voedselproblemen/templates/header.php';

$dossiernummer = isset($_GET['dossiernummer']) ? $_GET['dossiernummer'] : '';
$favv_dossiernummer = isset($_GET['favv_dossiernummer']) ? $_GET['favv_dossiernummer'] : '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $dossiernummer = $_POST['dossiernummer'];
    $productnaam = $_POST['productnaam'];
    $klacht = $_POST['klacht'];
    $klachtinformatie = $_POST['klachtinformatie'];
    $favv_dossiernummer = $_POST['favv_dossiernummer'];
    $favv_klachtinformatie = $_POST['favv_klachtinformatie'];
    $datum_terugroepactie = $_POST['datum_terugroepactie'];
    $status = $_POST['status'];

    $sql = "INSERT INTO terugroepacties (dossiernummer, productnaam, klacht, klachtinformatie, favv_dossiernummer, favv_klachtinformatie, datum_terugroepactie, status)
            VALUES (:dossiernummer, :productnaam, :klacht, :klachtinformatie, :favv_dossiernummer, :favv_klachtinformatie, :datum_terugroepactie, :status)";
    
    $stmt = $pdo_voedselproblemen->prepare($sql);
    $stmt->bindParam(':dossiernummer', $dossiernummer);
    $stmt->bindParam(':productnaam', $productnaam);
    $stmt->bindParam(':klacht', $klacht);
    $stmt->bindParam(':klachtinformatie', $klachtinformatie);
    $stmt->bindParam(':favv_dossiernummer', $favv_dossiernummer);
    $stmt->bindParam(':favv_klachtinformatie', $favv_klachtinformatie);
    $stmt->bindParam(':datum_terugroepactie', $datum_terugroepactie);
    $stmt->bindParam(':status', $status);

    if ($stmt->execute()) {
        header("Location: view_recall.php?success=1");
        exit();
    } else {
        echo "<div class='alert alert-danger'>Er is een fout opgetreden bij het toevoegen van de terugroepactie. Probeer het opnieuw.</div>";
    }
}
?>

<div class="card">
    <div class="card-header bg-success text-white">
        <h3>Nieuwe Terugroepactie</h3>
    </div>
    <div class="card-body">
        <form action="add_recall.php" method="post">
            <div class="form-row">
                <div class="col-md-6 form-group form-col">
                    <label for="dossiernummer">Dossiernummer</label>
                    <input type="text" class="form-control" id="dossiernummer" name="dossiernummer" value="<?php echo htmlspecialchars($dossiernummer); ?>" <?php echo $dossiernummer ? 'readonly' : ''; ?>>
                </div>
                <div class="col-md-6 form-group form-col">
                    <label for="favv_dossiernummer">FAVV Dossiernummer</label>
                    <input type="text" class="form-control" id="favv_dossiernummer" name="favv_dossiernummer" value="<?php echo htmlspecialchars($favv_dossiernummer); ?>" <?php echo $favv_dossiernummer ? 'readonly' : ''; ?>>
                </div>
            </div>
            <div class="form-row">
                <div class="col-md-6 form-group form-col">
                    <label for="productnaam">Productnaam</label>
                    <input type="text" class="form-control" id="productnaam" name="productnaam" required>
                </div>
                <div class="col-md-6 form-group form-col">
                    <label for="klacht">Klacht</label>
                    <input type="text" class="form-control" id="klacht" name="klacht" required>
                </div>
            </div>
            <div class="form-row">
                <div class="col-md-12 form-group form-col">
                    <label for="klachtinformatie">Klachtinformatie</label>
                    <textarea class="form-control" id="klachtinformatie" name="klachtinformatie" rows="3" required></textarea>
                </div>
            </div>
            <div class="form-row">
                <div class="col-md-12 form-group form-col">
                    <label for="favv_klachtinformatie">FAVV Klachtinformatie</label>
                    <textarea class="form-control" id="favv_klachtinformatie" name="favv_klachtinformatie" rows="3" required></textarea>
                </div>
            </div>
            <div class="form-row">
                <div class="col-md-6 form-group form-col">
                    <label for="datum_terugroepactie">Datum Terugroepactie</label>
                    <input type="date" class="form-control" id="datum_terugroepactie" name="datum_terugroepactie" required>
                </div>
                <div class="col-md-6 form-group form-col">
                    <label for="status">Status</label>
                    <select class="form-control" id="status" name="status" required>
                        <option value="In behandeling">In behandeling</option>
                        <option value="Afgerond">Afgerond</option>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="col-md-12 text-right">
                    <button type="submit" class="btn btn-success">Toevoegen</button>
                </div>
            </div>
        </form>
        <a href="view_recall.php" class="btn btn-primary mt-3">Terug naar overzicht</a>
        <a href="../index.php" class="btn btn-secondary mt-3">Home</a>
    </div>
</div>

<?php require $_SERVER["DOCUMENT_ROOT"] . '/footer.php'; ?>
