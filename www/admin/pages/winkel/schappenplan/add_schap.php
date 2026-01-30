<?php
  // Controleer of de gebruiker is ingelogd
  // Verbind met de database
require_once $_SERVER['DOCUMENT_ROOT'] . '/admin/includes/header.php';  // Laad de header in

// Controleren of het formulier is ingediend
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $naam = $_POST['naam'];  
    $locatie = $_POST['locatie'];  
    $breedte = (float)$_POST['breedte'];  
    $hoogte = (float)$_POST['hoogte'];  
    $aantal_planken = (int)$_POST['aantal_planken'];

    // SQL-instructie om een nieuw schap toe te voegen
    $stmt = $pdo_winkel->prepare("INSERT INTO winkel_schappen (naam, locatie, breedte, hoogte, aantal_planken) VALUES (?, ?, ?, ?, ?)");
    
    // Uitvoeren van de SQL-query
    if ($stmt->execute([$naam, $locatie, $breedte, $hoogte, $aantal_planken])) {
        echo "<div class='alert alert-success'>Nieuw schap succesvol toegevoegd!</div>";
    } else {
        echo "<div class='alert alert-danger'>Er is een fout opgetreden bij het toevoegen van het schap.</div>";
    }
}
?>

<div class="container mt-5">
    <h2>Nieuw Schap Toevoegen</h2>
    <form method="POST" action="add_schap.php">
        <div class="form-group">
            <label for="naam">Schap Naam:</label>
            <input type="text" class="form-control" id="naam" name="naam" required>
        </div>
        <div class="form-group">
            <label for="locatie">Locatie:</label>
            <input type="text" class="form-control" id="locatie" name="locatie" required>
        </div>
        <div class="form-group">
            <label for="breedte">Breedte:</label>
            <input type="number" class="form-control" id="breedte" name="breedte" step="0.01" required>
        </div>
        <div class="form-group">
            <label for="hoogte">Hoogte:</label>
            <input type="number" class="form-control" id="hoogte" name="hoogte" step="0.01" required>
        </div>
        <div class="form-group">
            <label for="aantal_planken">Aantal Planken:</label>
            <input type="number" class="form-control" id="aantal_planken" name="aantal_planken" min="1" required>
        </div>
        <button type="submit" class="btn btn-primary">Schap Toevoegen</button>
        <a href="index.php" class="btn btn-secondary">Terug naar Schappen Beheer</a>
    </form>
</div>

<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/admin/includes/footer.php';  // Laad de footer in ?>
