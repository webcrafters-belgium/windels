<?php
require $_SERVER["DOCUMENT_ROOT"] . '/ini.inc';

require $_SERVER["DOCUMENT_ROOT"] . '/header.php';
require $_SERVER["DOCUMENT_ROOT"] . '/voedselproblemen/templates/header.php';

// Functie om vers_productie record op te halen op basis van lotnummer uit $_GET
function getVersProductieByLotnummer($pdo_voedselproblemen, $lotnummer) {
    $stmt = $pdo_voedselproblemen->prepare("SELECT id, lotnummer, vers_product_id FROM vers_productie WHERE lotnummer = :lotnummer");
    $stmt->execute([':lotnummer' => $lotnummer]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Functie om alle vers_products op te halen
function getVersProducten($pdo_winkel) {
    $stmt = $pdo_winkel->query("SELECT id, title FROM vers_products");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Controleer of lotnummer is doorgegeven via $_GET
if (isset($_GET['lotnummer']) && !empty($_GET['lotnummer'])) {
    $lotnummer = htmlspecialchars($_GET['lotnummer']);
    $vers_productie = getVersProductieByLotnummer($pdo_voedselproblemen, $lotnummer);
} else {
    echo "<div class='alert alert-danger'>Geen geldig lotnummer opgegeven.</div>";
    exit;
}

// Voeg nieuw extern product toe via POST verzoek
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $vers_productie_id = $_POST['vers_productie_id'];
    $product_naam = $_POST['product_naam'];
    $aankoop_datum = $_POST['aankoop_datum'];
    $lotnummer_fabrikant = $_POST['lotnummer_fabrikant'];
    $gebruikte_hoeveelheid = $_POST['gebruikte_hoeveelheid'];
    $vers_lotnummerproduct = $_POST['vers_lotnummerproduct'];
    $vers_products_id = $_POST['vers_products_id'];
    $fabrikant = $_POST['fabrikant'];

    $stmt = $pdo_voedselproblemen->prepare("INSERT INTO extern_producten (vers_productie_id, vers_lotnummerproduct, vers_products_id, product_naam, fabrikant, aankoop_datum, lotnummer_fabrikant, gebruikte_hoeveelheid) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$vers_productie_id, $vers_lotnummerproduct, $vers_products_id, $product_naam, $fabrikant, $aankoop_datum, $lotnummer_fabrikant, $gebruikte_hoeveelheid]);

    echo "<div class='alert alert-success'>Extern product succesvol geregistreerd!</div>";
}

// Haal alle vers_products op voor de selectie van producten
$vers_producten = getVersProducten($pdo_winkel);
?>

<div class="container mt-5">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h2 class="card-title">Registratie Extern Producten</h2>
        </div>
        <div class="card-body">
            <form method="POST" action="">
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="vers_productie_id">Gekoppeld aan Productie ID:</label>
                        <input type="text" id="vers_productie_id" name="vers_productie_id" class="form-control" value="<?= htmlspecialchars($vers_productie['id']) ?>" readonly required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="vers_products_id">Gekoppeld aan Product ID:</label>
                        <input type="text" id="vers_products_id" name="vers_products_id" class="form-control" value="<?= htmlspecialchars($vers_productie['vers_product_id']) ?>" readonly required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="product_naam">Naam Product:</label>
                        <input type="text" id="product_naam" name="product_naam" class="form-control" required>
                        <input type="hidden" id="vers_lotnummerproduct" name="vers_lotnummerproduct" class="form-control" value="<?= htmlspecialchars($vers_productie['lotnummer']) ?>">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="aankoop_datum">Aankoop Datum:</label>
                        <input type="date" id="aankoop_datum" name="aankoop_datum" class="form-control" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="lotnummer_fabrikant">Lotnummer Fabrikant:</label>
                        <input type="text" id="lotnummer_fabrikant" name="lotnummer_fabrikant" class="form-control" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="fabrikant">Fabrikant:</label>
                        <input type="text" id="fabrikant" name="fabrikant" class="form-control" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label for="gebruikte_hoeveelheid">Gebruikte Hoeveelheid (in kg):</label>
                        <input type="number" step="0.001" id="gebruikte_hoeveelheid" name="gebruikte_hoeveelheid" class="form-control" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-success">Registreren</button>
            </form>
        </div>
    </div>



    <!-- Overzicht van geregistreerde externe producten -->
    <div class="card mt-5">
        <div class="card-header bg-secondary text-white">
            <h2 class="card-title">Geregistreerde Extern Producten</h2>
        </div>
        <div class="card-body">
            <?php
           // Controleer of de 'lotnummer' parameter is doorgegeven via GET
if (isset($_GET['lotnummer']) && !empty($_GET['lotnummer'])) {
    // Veilig ophalen van de lotnummer waarde uit de GET parameter
    $lotnummer = htmlspecialchars($_GET['lotnummer']);

    // Bereid een veilige query voor om gegevens op te halen op basis van het lotnummer
    $stmt = $pdo_voedselproblemen->prepare("
        SELECT *, product_naam as productie_naam
        FROM extern_producten ep
        JOIN vers_productie vp ON ep.vers_productie_id = vp.id
        WHERE vp.lotnummer = :lotnummer
    ");
    
    // Voer de query uit met de opgehaalde lotnummer
    $stmt->execute([':lotnummer' => $lotnummer]);
    
    // Haal alle resultaten op
    $extern_producten = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    // Als er geen lotnummer is opgegeven, geef een foutmelding of voer een algemene query uit zonder filter
    echo "<div class='alert alert-warning'>Geen geldig lotnummer opgegeven. Geef een lotnummer op om de gegevens te bekijken.</div>";
    $extern_producten = [];
}
            ?>

            <?php if (empty($extern_producten)): ?>
                <div class="alert alert-warning text-center">
                    <strong>Geen externe producten geregistreerd.</strong>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="thead-dark">
                            <tr>
                                <th>ID</th>
                                <th>Productie Naam</th>
                                <th>Product Naam</th>
                                <th>Aankoop Datum</th>
                                <th>Lotnummer Fabrikant</th>
                                <th>Fabrikant</th>
                                <th>Gebruikte Hoeveelheid</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($extern_producten as $product): ?>
                            <tr>
                                <td><?= $product['id'] ?></td>
                                <td><?= htmlspecialchars($product['productie_naam']) ?></td>
                                <td><?= htmlspecialchars($product['product_naam']) ?></td>
                                <td><?= htmlspecialchars($product['aankoop_datum']) ?></td>
                                <td><?= htmlspecialchars($product['lotnummer_fabrikant']) ?></td>
                                <td><?= htmlspecialchars($product['fabrikant']) ?></td>
                                <td><?= htmlspecialchars($product['gebruikte_hoeveelheid']) ?> kg</td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
require $_SERVER["DOCUMENT_ROOT"] . '/footer.php';
?>
