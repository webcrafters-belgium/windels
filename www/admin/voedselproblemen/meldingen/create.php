<?php

require $_SERVER["DOCUMENT_ROOT"] . '/ini.inc';
require $_SERVER["DOCUMENT_ROOT"] . '/header.php';
require $_SERVER["DOCUMENT_ROOT"] . '/voedselproblemen/templates/header.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $dossiernummer = uniqid('DOSS_');
    $datumMelding = $_POST['datumMelding'];
    $naamKlant = htmlspecialchars($_POST['naamKlant']);
    $emailKlant = htmlspecialchars($_POST['emailKlant']);
    $productnaam = htmlspecialchars($_POST['productnaam']);
    $probleem = htmlspecialchars($_POST['probleem']);
    $gezondheidsklachten = htmlspecialchars($_POST['gezondheidsklachten']);
    $beschrijving = htmlspecialchars($_POST['beschrijving']);
    $batchnummer = htmlspecialchars($_POST['batchnummer']);
    $aankoopdatum = $_POST['aankoopdatum'];
    $houdbaarheidsdatum = $_POST['houdbaarheidsdatum'];
    $documentenMeegeleverd = htmlspecialchars($_POST['documentenMeegeleverd']);
    $status = 'Nieuw';  // Status is standaard 'Nieuw'
    $favv_dossier = 0; // Standaard op nee
    $favv_dossiernummer = null; // Geen dossiernummer bij standaard nee

    // Voorbereide SQL-query met bind parameters om SQL-injecties te voorkomen
    $stmt = $pdo_voedselproblemen->prepare("INSERT INTO meldingen (dossiernummer, datum_melding, naam_klant, email_klant, productnaam, probleem, gezondheidsklachten, beschrijving, batchnummer, aankoopdatum, houdbaarheidsdatum, documenten_meegeleverd, favv_dossier, favv_dossiernummer, status)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param('ssssssssssssiss', $dossiernummer, $datumMelding, $naamKlant, $emailKlant, $productnaam, $probleem, $gezondheidsklachten, $beschrijving, $batchnummer, $aankoopdatum, $houdbaarheidsdatum, $documentenMeegeleverd, $favv_dossier, $favv_dossiernummer, $status);

    if ($stmt->execute()) {
        header("Location: view.php?success=1&favv_dossier=$favv_dossier&favv_dossiernummer=$favv_dossiernummer");
        exit();
    } else {
        echo "<div class='alert alert-danger'>Er is een fout opgetreden: " . htmlspecialchars($stmt->error) . "</div>";
    }

    $stmt->close();
}
?>

<div class="card">
    <div class="card-header bg-success text-white">
        <h3>Nieuwe Melding Aanmaken</h3>
    </div>
    <div class="card-body">
        <form action="create.php" method="post">
            <div class="form-row">
                <div class="col-md-6 form-group form-col">
                    <label for="datumMelding">Datum Melding</label>
                    <input type="date" class="form-control" id="datumMelding" name="datumMelding" required>
                </div>
                <div class="col-md-6 form-group form-col">
                    <label for="naamKlant">Naam Klant</label>
                    <input type="text" class="form-control" id="naamKlant" name="naamKlant" required>
                </div>
            </div>
            <div class="form-row">
                <div class="col-md-6 form-group form-col">
                    <label for="emailKlant">E-mail Klant</label>
                    <input type="email" class="form-control" id="emailKlant" name="emailKlant" required>
                </div>
                <div class="col-md-6 form-group form-col">
                    <label for="productnaam">Productnaam</label>
                    <input type="text" class="form-control" id="productnaam" name="productnaam" required>
                </div>
            </div>
            <div class="form-row">
                <div class="col-md-6 form-group form-col">
                    <label for="probleem">Probleem</label>
                    <input type="text" class="form-control" id="probleem" name="probleem" required>
                </div>
                <div class="col-md-6 form-group form-col">
                    <label for="gezondheidsklachten">Gezondheidsklachten</label>
                    <select class="form-control" id="gezondheidsklachten" name="gezondheidsklachten" required>
                        <option value="Ja">Ja</option>
                        <option value="Nee">Nee</option>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="col-md-6 form-group form-col">
                    <label for="batchnummer">Batchnummer</label>
                    <input type="text" class="form-control" id="batchnummer" name="batchnummer" required>
                </div>
                <div class="col-md-6 form-group form-col">
                    <label for="aankoopdatum">Aankoopdatum</label>
                    <input type="date" class="form-control" id="aankoopdatum" name="aankoopdatum" required>
                </div>
            </div>
            <div class="form-row">
                <div class="col-md-6 form-group form-col">
                    <label for="houdbaarheidsdatum">Houdbaarheidsdatum</label>
                    <input type="date" class="form-control" id="houdbaarheidsdatum" name="houdbaarheidsdatum" required>
                </div>
                <div class="col-md-6 form-group form-col">
                    <label for="documentenMeegeleverd">Documenten Meegeleverd</label>
                    <select class="form-control" id="documentenMeegeleverd" name="documentenMeegeleverd" required>
                        <option value="Ja">Ja</option>
                        <option value="Nee">Nee</option>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="col-md-12 form-group form-col">
                    <label for="beschrijving">Beschrijving</label>
                    <textarea class="form-control" id="beschrijving" name="beschrijving" rows="3" required></textarea>
                </div>
            </div>
            <div class="form-row">
                <div class="col-md-12 text-right form-col">
                    <button type="submit" class="btn btn-success">Melding Aanmaken</button>
                </div>
            </div>
        </form>
        <a href="view.php" class="btn btn-primary mt-3">Terug</a>
        <a href="../index.php" class="btn btn-secondary mt-3">Home</a>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('datumMelding').value = today;

    document.getElementById('favv_dossier').addEventListener('change', function() {
        document.getElementById('favv_dossiernummer_group').style.display = this.checked ? 'block' : 'none';
    });
});
</script>

<?php
require $_SERVER["DOCUMENT_ROOT"] . '/footer.php';
?>
