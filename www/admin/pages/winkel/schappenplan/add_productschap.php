<?php

 // Verbind met de database

// Haal alle schappen op
function fetchSchappen($pdo_winkel) {
    $stmt = $pdo_winkel->query("SELECT id, naam FROM winkel_schappen");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Haal producten op basis van het SKU-nummer en type
function fetchProductBySKU($pdo_winkel, $type, $sku) {
    switch ($type) {
        case 'epoxy':
            $stmt = $pdo_winkel->prepare("SELECT id FROM epoxy_products WHERE sku = ?");
            break;
        case 'kaarsen':
            $stmt = $pdo_winkel->prepare("SELECT id FROM kaarsen_products WHERE sku = ?");
            break;
        case 'vers':
            $stmt = $pdo_winkel->prepare("SELECT id FROM vers_products WHERE sku = ?");
            break;
            case 'inkoop':
                $stmt = $pdo_winkel->prepare("SELECT id FROM inkoop_products WHERE sku = ?");
                break;
        default:
            return null;
    }
    $stmt->execute([$sku]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Controleren of het formulier is ingediend
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $schap_id = (int)$_POST['schap_id'];
    $plank_nummer = (int)$_POST['plank_nummer'];
    $positie_op_plank = (float)$_POST['positie_op_plank'];
    $product_type = $_POST['product_type'];
    $sku = $_POST['sku'];

    // Haal product op basis van SKU en type
    $product = fetchProductBySKU($pdo_winkel, $product_type, $sku);

    if (!$product) {
        echo "<div class='alert alert-danger'>Product niet gevonden met SKU: $sku</div>";
    } else {
        // Stel de juiste kolom voor product_id op basis van product_type
        $epoxy_product_id = null;
        $kaarsen_product_id = null;
        $vers_product_id = null;

        switch ($product_type) {
            case 'epoxy':
                $epoxy_product_id = $product['id'];
                break;
            case 'kaarsen':
                $kaarsen_product_id = $product['id'];
                break;
            case 'vers':
                $vers_product_id = $product['id'];
                break;
        }

        // Voeg product toe aan schap met de juiste kolom
        $stmt = $pdo_winkel->prepare("INSERT INTO product_schap (schap_id, plank_nummer, positie_op_plank, epoxy_product_id, kaarsen_product_id, vers_product_id) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$schap_id, $plank_nummer, $positie_op_plank, $epoxy_product_id, $kaarsen_product_id, $vers_product_id]);

        echo "<div class='alert alert-success'>Product succesvol toegevoegd aan schap!</div>";
    }
}

// Haal beschikbare schappen op
$schappen = fetchSchappen($pdo_winkel);

require_once $_SERVER['DOCUMENT_ROOT'] . '/admin/includes/header.php';
?>

<div class="container mt-5">
    <h2>Product Toevoegen aan Schap</h2>
    <form method="POST" action="add_productschap.php">
        <div class="form-group">
            <label for="schap_id">Kies Schap:</label>
            <select class="form-control" id="schap_id" name="schap_id" required>
                <?php foreach ($schappen as $schap): ?>
                    <option value="<?= htmlspecialchars($schap['id']); ?>"><?= htmlspecialchars($schap['naam']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="plank_nummer">Plank Nummer:</label>
            <input type="number" class="form-control" id="plank_nummer" name="plank_nummer" min="1" required>
        </div>
        <div class="form-group">
            <label for="positie_op_plank">Positie op Plank (cm):</label>
            <input type="number" step="0.1" class="form-control" id="positie_op_plank" name="positie_op_plank" required>
        </div>
        <div class="form-group">
            <label for="product_type">Product Type:</label>
            <select class="form-control" id="product_type" name="product_type" required>
                <option value="epoxy">Epoxy Product</option>
                <option value="kaarsen">Kaarsen Product</option>
                <option value="vers">Vers Product</option>
            </select>
        </div>
        <div class="form-group">
            <label for="sku">Product SKU:</label>
            <input type="text" class="form-control" id="sku" name="sku" required>
        </div>
        <button type="submit" class="btn btn-primary">Product Toevoegen</button>
        <a href="index.php" class="btn btn-secondary">Terug naar Schappen Beheer</a>
    </form>
</div>

<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/admin/includes/footer.php'; ?>
