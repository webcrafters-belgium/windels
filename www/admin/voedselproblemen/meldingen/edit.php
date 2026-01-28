<?php


$web = $_SERVER['PHP_SELF'];
$usernameadmin = $_SESSION['admin_username'];
$admin_id = $_SESSION['admin_id'];

$secondsWait = 3600;
header("refresh:$secondsWait; /loginout.php?web=$web&adminuser=$usernameadmin");

require $_SERVER["DOCUMENT_ROOT"] . '/ini.inc';
require $_SERVER["DOCUMENT_ROOT"] . '/header.php';
require $_SERVER["DOCUMENT_ROOT"] . '/voedselprobelemen/templates/header.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM meldingen WHERE id = $id";
    $result = $pdo_voedselproblemen->query($sql);
    $row = $result->fetch_assoc();

    if ($row['locked_by'] !== NULL && $row['locked_by'] != $admin_id) {
        echo '<div class="alert alert-warning">Deze melding is momenteel in bewerking door een andere medewerker.</div>';
        echo '<a href="view.php" class="btn btn-primary">Terug naar Overzicht</a>';
        require $_SERVER["DOCUMENT_ROOT"] . '/voedselproblemen/templates/footer.php';
        exit();
    } else {
        $sql = "UPDATE meldingen SET locked_by = $admin_id WHERE id = $id";
        $pdo_voedselproblemen->query($sql);
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $datumMelding = $_POST['datumMelding'];
    $naamKlant = $_POST['naamKlant'];
    $emailKlant = $_POST['emailKlant'];
    $productnaam = $_POST['productnaam'];
    $probleem = $_POST['probleem'];
    $gezondheidsklachten = $_POST['gezondheidsklachten'];
    $beschrijving = $_POST['beschrijving'];
    $batchnummer = $_POST['batchnummer'];
    $aankoopdatum = $_POST['aankoopdatum'];
    $houdbaarheidsdatum = $_POST['houdbaarheidsdatum'];
    $documentenMeegeleverd = $_POST['documentenMeegeleverd'];
    $status = $_POST['status'];
    $favv_dossier = isset($_POST['favv_dossier']) ? 1 : 0;
    $favv_dossiernummer = $_POST['favv_dossiernummer'] ?? null;

    // Bewaar oude waarden voor bewerkingsgeschiedenis
    $oude_waarden_sql = "SELECT * FROM meldingen WHERE id = $id";
    $oude_waarden_result = $conn->query($oude_waarden_sql);
    $oude_waarden = $oude_waarden_result->fetch_assoc();

    // Functie om bewerkingsgeschiedenis bij te werken
    function updateBewerkingsgeschiedenis($conn, $id, $admin_id, $veld, $oude_waarde, $nieuwe_waarde) {
        if ($oude_waarde != $nieuwe_waarde) {
            $sql = "INSERT INTO bewerkingsgeschiedenis (melding_id, medewerker_id, veld, oude_waarde, nieuwe_waarde) VALUES ('$id', '$admin_id', '$veld', '$oude_waarde', '$nieuwe_waarde')";
            $conn->query($sql);
        }
    }

    // Update bewerkingsgeschiedenis voor elk veld
    updateBewerkingsgeschiedenis($conn, $id, $admin_id, 'datum_melding', $oude_waarden['datum_melding'], $datumMelding);
    updateBewerkingsgeschiedenis($conn, $id, $admin_id, 'naam_klant', $oude_waarden['naam_klant'], $naamKlant);
    updateBewerkingsgeschiedenis($conn, $id, $admin_id, 'email_klant', $oude_waarden['email_klant'], $emailKlant);
    updateBewerkingsgeschiedenis($conn, $id, $admin_id, 'productnaam', $oude_waarden['productnaam'], $productnaam);
    updateBewerkingsgeschiedenis($conn, $id, $admin_id, 'probleem', $oude_waarden['probleem'], $probleem);
    updateBewerkingsgeschiedenis($conn, $id, $admin_id, 'gezondheidsklachten', $oude_waarden['gezondheidsklachten'], $gezondheidsklachten);
    updateBewerkingsgeschiedenis($conn, $id, $admin_id, 'beschrijving', $oude_waarden['beschrijving'], $beschrijving);
    updateBewerkingsgeschiedenis($conn, $id, $admin_id, 'batchnummer', $oude_waarden['batchnummer'], $batchnummer);
    updateBewerkingsgeschiedenis($conn, $id, $admin_id, 'aankoopdatum', $oude_waarden['aankoopdatum'], $aankoopdatum);
    updateBewerkingsgeschiedenis($conn, $id, $admin_id, 'houdbaarheidsdatum', $oude_waarden['houdbaarheidsdatum'], $houdbaarheidsdatum);
    updateBewerkingsgeschiedenis($conn, $id, $admin_id, 'documenten_meegeleverd', $oude_waarden['documenten_meegeleverd'], $documentenMeegeleverd);
    updateBewerkingsgeschiedenis($conn, $id, $admin_id, 'status', $oude_waarden['status'], $status);
    updateBewerkingsgeschiedenis($conn, $id, $admin_id, 'favv_dossier', $oude_waarden['favv_dossier'], $favv_dossier);
    updateBewerkingsgeschiedenis($conn, $id, $admin_id, 'favv_dossiernummer', $oude_waarden['favv_dossiernummer'], $favv_dossiernummer);

    // Update de melding
    $sql = "UPDATE meldingen SET datum_melding='$datumMelding', naam_klant='$naamKlant', email_klant='$emailKlant', productnaam='$productnaam', probleem='$probleem', gezondheidsklachten='$gezondheidsklachten', beschrijving='$beschrijving', batchnummer='$batchnummer', aankoopdatum='$aankoopdatum', houdbaarheidsdatum='$houdbaarheidsdatum', documenten_meegeleverd='$documentenMeegeleverd', status='$status', favv_dossier='$favv_dossier', favv_dossiernummer='$favv_dossiernummer', locked_by=NULL, laatste_bewerking_medewerker_id='$admin_id' WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        header("Location: view.php?success=1");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>
<div class="card">
    <div class="card-header bg-success text-white">
        <h3>Melding Bewerken</h3>
    </div>
    <div class="card-body">
        <form action="edit.php" method="post">
            <div class="form-row">
                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                <div class="col-md-6 form-group form-col">
                    <label for="datumMelding">Datum Melding</label>
                    <input type="date" class="form-control" id="datumMelding" name="datumMelding" value="<?php echo $row['datum_melding']; ?>" required>
                </div>
                <div class="col-md-6 form-group form-col">
                    <label for="naamKlant">Naam Klant</label>
                    <input type="text" class="form-control" id="naamKlant" name="naamKlant" value="<?php echo $row['naam_klant']; ?>" required>
                </div>
            </div>
            <div class="form-row">
            <div class="col-md-6 form-group form-col">
                    <label for="emailKlant">E-mail Klant</label>
                    <input type="email" class="form-control" id="emailKlant" name="emailKlant" value="<?php echo $row['email_klant']; ?>" required>
                </div>
                <div class="col-md-6 form-group form-col">
                    <label for="productnaam">Productnaam</label>
                    <input type="text" class="form-control" id="productnaam" name="productnaam" value="<?php echo $row['productnaam']; ?>" required>
                </div>
            </div>
            <div class="form-row">
                <div class="col-md-6 form-group form-col">
                    <label for="probleem">Probleem</label>
                    <select class="form-control" id="probleem" name="probleem" required>
                        <option value="Bederf" <?php if ($row['probleem'] == 'Bederf') echo 'selected'; ?>>Bederf</option>
                        <option value="Contaminatie" <?php if ($row['probleem'] == 'Contaminatie') echo 'selected'; ?>>Contaminatie</option>
                        <option value="Allergische reactie" <?php if ($row['probleem'] == 'Allergische reactie') echo 'selected'; ?>>Allergische reactie</option>
                    </select>
                </div>
                <div class="col-md-6 form-group form-col">
                    <label for="gezondheidsklachten">Gezondheidsklachten</label>
                    <select class="form-control" id="gezondheidsklachten" name="gezondheidsklachten" required>
                        <option value="Ja" <?php if ($row['gezondheidsklachten'] == 'Ja') echo 'selected'; ?>>Ja</option>
                        <option value="Nee" <?php if ($row['gezondheidsklachten'] == 'Nee') echo 'selected'; ?>>Nee</option>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="col-md-6 form-group form-col">
                    <label for="batchnummer">Batchnummer</label>
                    <input type="text" class="form-control" id="batchnummer" name="batchnummer" value="<?php echo $row['batchnummer']; ?>" required>
                </div>
                <div class="col-md-6 form-group form-col">
                    <label for="aankoopdatum">Aankoopdatum</label>
                    <input type="date" class="form-control" id="aankoopdatum" name="aankoopdatum" value="<?php echo $row['aankoopdatum']; ?>" required>
                </div>
            </div>
            <div class="form-row">
                <div class="col-md-6 form-group form-col">
                    <label for="houdbaarheidsdatum">Houdbaarheidsdatum</label>
                    <input type="date" class="form-control" id="houdbaarheidsdatum" name="houdbaarheidsdatum" value="<?php echo $row['houdbaarheidsdatum']; ?>" required>
                </div>
                <div class="col-md-6 form-group form-col">
                    <label for="documentenMeegeleverd">Documenten Meegeleverd</label>
                    <select class="form-control" id="documentenMeegeleverd" name="documentenMeegeleverd" required>
                        <option value="Ja" <?php if ($row['documenten_meegeleverd'] == 'Ja') echo 'selected'; ?>>Ja</option>
                        <option value="Nee" <?php if ($row['documenten_meegeleverd'] == 'Nee') echo 'selected'; ?>>Nee</option>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="col-md-6 form-group form-col">
                    <label for="status">Status</label>
                    <select class="form-control" id="status" name="status" required>
                        <option value="In behandeling" <?php if ($row['status'] == 'In behandeling') echo 'selected'; ?>>In behandeling</option>
                        <option value="Afgerond" <?php if ($row['status'] == 'Afgerond') echo 'selected'; ?>>Afgerond</option>
                    </select>
                </div>
                <div class="col-md-6 form-group">
                    <label for="favv_dossier">Dossier bij FAVV:</label>
                    <input type="checkbox" id="favv_dossier" name="favv_dossier" <?php echo $row['favv_dossier'] ? 'checked' : ''; ?> onchange="toggleFavvDossiernummer()">
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 form-group" id="favv_dossiernummer_field" style="<?php echo $row['favv_dossier'] ? 'display: block;' : 'display: none;'; ?>">
                    <label for="favv_dossiernummer">FAVV Dossiernummer:</label>
                    <input type="text" class="form-control" id="favv_dossiernummer" name="favv_dossiernummer" value="<?php echo $row['favv_dossiernummer']; ?>">
                </div>
                <div class="col-md-6 form-group">
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 form-group form-col">
                    <label for="beschrijving">Beschrijving</label>
                    <textarea class="form-control" id="beschrijving" name="beschrijving" rows="3" required><?php echo $row['beschrijving']; ?></textarea>
                </div>
                
            </div>
            <div class="form-row">
                <div class="col-md-12 text-right form-col">
                    <button type="submit" class="btn btn-success">Melding Bijwerken</button>
                </div>
            </div>
        </form>
        <a href="javascript:history.back()" class="btn btn-primary mt-3">Terug</a>
        <a href="../index.php" class="btn btn-secondary mt-3">Home</a>
    </div>
</div>
<script>
    function toggleFavvDossiernummer() {
        var favvDossier = document.getElementById('favv_dossier').checked;
        var favvDossiernummerField = document.getElementById('favv_dossiernummer_field');
        if (favvDossier) {
            favvDossiernummerField.style.display = 'block';
        } else {
            favvDossiernummerField.style.display = 'none';
        }
    }
</script>
<script>
     function unlockMelding(id) {
        fetch('unlock.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: new URLSearchParams({ id })
        })
        .then(response => response.text())
        .then(data => console.log(data))
        .catch(error => console.error('Error:', error));
    }

    window.addEventListener('beforeunload', function (event) {
        unlockMelding(<?php echo $id; ?>);
    });
</script>
<?php
require $_SERVER["DOCUMENT_ROOT"] . '/footer.php';
?>
