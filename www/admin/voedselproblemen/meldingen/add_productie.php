<?php
require $_SERVER["DOCUMENT_ROOT"] . '/ini.inc';


// Stel PDO-foutmeldingen in om uitzonderingen te werpen
$pdo_winkel->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Functie om een automatisch lotnummer te genereren
function generateLotnummer($vers_product_id) {
    $date = date('Ymd');
    return $vers_product_id . '-' . $date . '-' . strtoupper(bin2hex(random_bytes(4)));
}

// Functie om de vervaldatum te berekenen
function calculateVervaldatum($duur) {
    $productionDate = new DateTime(); // huidige datum
    return $productionDate->add(new DateInterval($duur))->format('Y-m-d');
}

// Haal producten op uit de database
try {
    $stmt = $pdo_winkel->query("SELECT id, title FROM vers_products");
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    echo "<div class='alert alert-danger'>Fout bij het ophalen van producten: " . htmlspecialchars($e->getMessage()) . "</div>";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $vers_product_id = intval($_POST['vers_product_id']);
    $aantal_gemaakt = intval($_POST['aantal_gemaakt']);
    $duur = $_POST['duur'];

    // Validatie van de duur
    $valid_durations = ['P3M', 'P6M', 'P1Y', 'P1Y6M', 'P2Y', 'P3Y', 'P4Y', 'P5Y'];
    if (!in_array($duur, $valid_durations)) {
        echo "<div class='alert alert-danger'>Ongeldige houdbaarheidsduur geselecteerd.</div>";
    } elseif ($aantal_gemaakt <= 0) {
        echo "<div class='alert alert-danger'>Het aantal gemaakte producten moet groter zijn dan 0.</div>";
    } else {
        $lotnummer = generateLotnummer($vers_product_id);
        $vervaldatum = calculateVervaldatum($duur);

        // Start een database transactie
        try {
            $pdo_winkel->beginTransaction();

            // Sla de productiegegevens op in de database
            $stmt = $pdo_voedselproblemen->prepare("INSERT INTO vers_productie (lotnummer, vers_product_id, aantal_gemaakt, vervaldatum) VALUES (:lotnummer, :vers_product_id, :aantal_gemaakt, :vervaldatum)");
            $stmt->bindParam(':lotnummer', $lotnummer);
            $stmt->bindParam(':vers_product_id', $vers_product_id, PDO::PARAM_INT);
            $stmt->bindParam(':aantal_gemaakt', $aantal_gemaakt, PDO::PARAM_INT);
            $stmt->bindParam(':vervaldatum', $vervaldatum);

            // Check of het succesvol was
            if (!$stmt->execute()) {
                throw new Exception("Fout bij het invoegen van productiegegevens.");
            }

            // Update de voorraad in de vers_products tabel
            $stmt = $pdo_winkel->prepare("UPDATE vers_products SET stock = stock + :aantal_gemaakt WHERE id = :vers_product_id");
            $stmt->bindParam(':aantal_gemaakt', $aantal_gemaakt, PDO::PARAM_INT);
            $stmt->bindParam(':vers_product_id', $vers_product_id, PDO::PARAM_INT);

            // Check of de voorraad succesvol is geüpdatet
            if (!$stmt->execute()) {
                throw new Exception("Fout bij het updaten van de voorraad.");
            }

            // Commit de transactie
            $pdo_winkel->commit();

            echo "<div class='alert alert-success'>Productie succesvol geregistreerd! Lotnummer: $lotnummer, Vervaldatum: $vervaldatum. Voorraad bijgewerkt met $aantal_gemaakt stuks.</div>";

        } catch (Exception $e) {
            // In geval van een fout, rollback de transactie
            $pdo_winkel->rollBack();
            error_log("Fout bij productie registratie: " . $e->getMessage());
            echo "<div class='alert alert-danger'>Er is een fout opgetreden bij het registreren van de productie: " . htmlspecialchars($e->getMessage()) . "</div>";
        }
    }
}
require $_SERVER["DOCUMENT_ROOT"] . '/header.php';
require $_SERVER["DOCUMENT_ROOT"] . '/voedselproblemen/templates/header.php';
?>

<div class="container mt-5">
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Productie Registratie van Verse Producten</h2>
        </div>
        <div class="card-body">
            <form method="post" action="">
                <div class="form-group">
                    <label for="vers_product_id">Product:</label>
                    <select class="form-control" id="vers_product_id" name="vers_product_id" required>
                        <?php foreach ($products as $product): ?>
                            <option value="<?= htmlspecialchars($product['id']) ?>"><?= htmlspecialchars($product['title']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="aantal_gemaakt">Aantal Gemaakt:</label>
                    <input type="number" class="form-control" id="aantal_gemaakt" name="aantal_gemaakt" min="1" required>
                </div>
                <div class="form-group">
                    <label for="duur">Houdbaarheid:</label>
                    <select class="form-control" id="duur" name="duur" required>
                        <option value="P3M">3 maanden</option>
                        <option value="P6M">6 maanden</option>
                        <option value="P1Y">1 jaar</option>
                        <option value="P1Y6M">1,5 jaar</option>
                        <option value="P2Y">2 jaar</option>
                        <option value="P3Y">3 jaar</option>
                        <option value="P4Y">4 jaar</option>
                        <option value="P5Y">5 jaar</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Productie Registreren</button>
            </form>
        </div>
    </div>
</div>

<?php
require $_SERVER["DOCUMENT_ROOT"] . '/footer.php';
?>
