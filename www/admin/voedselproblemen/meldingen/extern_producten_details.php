<?php
require $_SERVER["DOCUMENT_ROOT"] . '/ini.inc';

require $_SERVER["DOCUMENT_ROOT"] . '/header.php';
require $_SERVER["DOCUMENT_ROOT"] . '/voedselproblemen/templates/header.php';

// Functie om alle vers_productie records op te halen
function getVersProductie($pdo_voedselproblemen) {
    $stmt = $pdo_voedselproblemen->query("SELECT id, lotnummer FROM vers_productie");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Voeg nieuw extern product toe via POST verzoek
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $vers_productie_id = $_POST['vers_productie_id'];
    $product_naam = $_POST['product_naam'];
    $aankoop_datum = $_POST['aankoop_datum'];
    $lotnummer_fabrikant = $_POST['lotnummer_fabrikant'];
    $gebruikte_hoeveelheid = $_POST['gebruikte_hoeveelheid'];

    $stmt = $pdo_voedselproblemen->prepare("
        INSERT INTO extern_producten (vers_productie_id, product_naam, aankoop_datum, lotnummer_fabrikant, gebruikte_hoeveelheid) 
        VALUES (?, ?, ?, ?, ?)
    ");
    $stmt->execute([$vers_productie_id, $product_naam, $aankoop_datum, $lotnummer_fabrikant, $gebruikte_hoeveelheid]);

    echo "<div class='alert alert-success'>Extern product succesvol geregistreerd!</div>";
}

// Haal alle vers_productie records op
$vers_productie = getVersProductie($pdo_voedselproblemen);
?>

<div class="container mt-5">
    <div class="card">
        <div class="card-header bg-success text-white">
            <h2 class="card-title">Registratie Extern Producten</h2>
        </div>
        <div class="card-body">
            <form method="POST" action="">
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="vers_productie_id">Gekoppeld aan Lotnummer:</label>
                        <select id="vers_productie_id" name="vers_productie_id" class="form-control" required>
                            <?php foreach ($vers_productie as $productie): ?>
                                <option value="<?= $productie['id'] ?>"><?= htmlspecialchars($productie['lotnummer']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="product_naam">Naam Product:</label>
                        <input type="text" id="product_naam" name="product_naam" class="form-control" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="aankoop_datum">Aankoop Datum:</label>
                        <input type="date" id="aankoop_datum" name="aankoop_datum" class="form-control" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="lotnummer_fabrikant">Lotnummer Fabrikant:</label>
                        <input type="text" id="lotnummer_fabrikant" name="lotnummer_fabrikant" class="form-control" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="gebruikte_hoeveelheid">Gebruikte Hoeveelheid (in kg):</label>
                    <input type="number" step="0.0001" id="gebruikte_hoeveelheid" name="gebruikte_hoeveelheid" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-success">Registreren</button>
            </form>
        </div>
    </div>

    <!-- Overzicht van geregistreerde externe producten -->
    <div class="card mt-5">
        <div class="card-header bg-success text-white">
            <h2 class="card-title">Geregistreerde Extern Producten</h2>
        </div>
        <div class="card-body">
            <?php
            // Haal alleen de externe producten op die gekoppeld zijn aan een lotnummer uit vers_productie
            $stmt = $pdo_voedselproblemen->query("
                SELECT ep.*, vp.lotnummer as productie_lotnummer 
                FROM extern_producten ep 
                JOIN vers_productie vp ON ep.vers_productie_id = vp.id
                WHERE vp.lotnummer IS NOT NULL
            ");
            $extern_producten = $stmt->fetchAll(PDO::FETCH_ASSOC);
            ?>

            <?php if (empty($extern_producten)): ?>
                <div class="alert alert-warning text-center">
                    <strong>Geen externe producten met gekoppeld lotnummer geregistreerd.</strong>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="thead-green">
                            <tr>
                                <th>ID</th>
                                <th>Lotnummer Productie</th>
                                <th>Product Naam</th>
                                <th>Aankoop Datum</th>
                                <th>Lotnummer Fabrikant</th>
                                <th>Gebruikte Hoeveelheid</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($extern_producten as $product): ?>
                            <tr>
                                <td><?= $product['id'] ?></td>
                                <td><?= htmlspecialchars($product['productie_lotnummer']) ?></td>
                                <td><?= htmlspecialchars($product['product_naam']) ?></td>
                                <td><?= htmlspecialchars($product['aankoop_datum']) ?></td>
                                <td><?= htmlspecialchars($product['lotnummer_fabrikant']) ?></td>
                                <td><?= htmlspecialchars($product['gebruikte_hoeveelheid']) ?> kg</td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Knop om terug te gaan naar view_productie.php -->
    <a href="view_productie.php" class="btn btn-secondary mt-3">Terug naar Overzicht Productie</a>
</div>

<?php
require $_SERVER["DOCUMENT_ROOT"] . '/footer.php';
?>
