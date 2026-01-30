<?php

require $_SERVER["DOCUMENT_ROOT"] . '/ini.inc';
require $_SERVER["DOCUMENT_ROOT"] . '/assets/admin/fpdf/fpdf.php';


error_reporting(E_ALL);
ini_set('display_errors', 1);

// Controleer de databaseverbinding
if ($conn->connect_error) {
    die('Fout bij verbinden met de database: ' . $conn->connect_error);
}

// FUNCTIE: PRODUCT OPHALEN OP SKU
function fetchProductBySKU($conn, $sku) {
    $query = "SELECT sku, name AS title, price AS total_product_price FROM products WHERE sku = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $sku);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

// FUNCTIE: PRODUCTEN PER CATEGORIE
function fetchProductsByCategory($conn, $category) {
    $query = "
        SELECT p.sku, p.name AS title, p.price AS total_product_price
        FROM products p
        JOIN product_categories pc ON p.id = pc.product_id
        JOIN categories c ON pc.category_id = c.id
        WHERE c.slug = ?
    ";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $category);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

// AJAX CALLS
if (isset($_GET['sku'])) {
    try {
        $sku = $_GET['sku'];
        $product = fetchProductBySKU($conn, $sku);
        echo json_encode($product ? [$product] : []);
        exit;
    } catch (Exception $e) {
        error_log($e->getMessage());
        echo json_encode([]);
        exit;
    }
}

if (isset($_GET['category'])) {
    try {
        $category = $_GET['category'];
        $products = fetchProductsByCategory($conn, $category);
        echo json_encode($products);
        exit;
    } catch (Exception $e) {
        error_log($e->getMessage());
        echo json_encode([]);
        exit;
    }
}

// PDF & DATABASE KORTINGEN
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['labels'])) {

    $labels = json_decode($_POST['labels'], true);
    $displayOption = $_POST['displayOption'] ?? 'price';

    if (!$labels || !is_array($labels)) {
        die('Ongeldige gegevens ontvangen.');
    }

    // VOORBEREIDING SQL
    $kortingQuery = "
        INSERT INTO kortingen 
        (sku, title, original_price, discount_percentage, discounted_price, start_date, end_date)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ";
    $stmt = $conn->prepare($kortingQuery);

    if (!$stmt) {
        die('Fout bij voorbereiden van query: ' . $conn->error);
    }

    $pdf = new FPDF('P', 'mm', 'A4');
    $pdf->SetMargins(0, 0, 0);
    $pdf->SetAutoPageBreak(false);
    $pdf->AddPage();

    $labelWidth = 70;
    $labelHeight = 37;
    $marginLeft = 0;
    $marginTop = 0;
    $labelsPerRow = 3;
    $rowsPerPage = 8;
    $currentRow = 0;

    $pdf->AddFont('Roboto', '', 'Roboto-Regular.php');

    foreach ($labels as $label) {

        $sku = $label['sku'];
        $title = !empty($label['title']) ? $label['title'] : "Geen titel";
        $totalPrice = isset($label['total_product_price']) ? (float)$label['total_product_price'] : 0.00;
        $discountPercentage = isset($label['discount']) ? (float)$label['discount'] : 10; // default 10%
        $newPrice = $totalPrice * ((100 - $discountPercentage) / 100);

        $startDate = $label['start_date'] ?? date('Y-m-d');
        $endDate = $label['end_date'] ?? date('Y-m-d', strtotime($startDate . ' +6 days'));

        // INVOEGEN IN DATABASE
        $stmt->bind_param("ssddsss", $sku, $title, $totalPrice, $discountPercentage, $newPrice, $startDate, $endDate);
        $stmt->execute();

        for ($j = 0; $j < $label['quantity']; $j++) {

            $x = $marginLeft + ($labelWidth * ($currentRow % $labelsPerRow));
            $y = $marginTop + ($labelHeight * floor($currentRow / $labelsPerRow));

            if ($currentRow > 0 && $currentRow % ($labelsPerRow * $rowsPerPage) === 0) {
                $pdf->AddPage();
                $y = $marginTop;
                $currentRow = 0;
            }

            // -------------------------------
            // LABEL OPBOUW - PRICE
            // -------------------------------

            if ($displayOption === 'price') {

                $pdf->SetFillColor(34, 150, 34);
                $pdf->Rect($x, $y, 10, 37, 'F');

                $pdf->SetFont('Arial', 'B', 10);
                $pdf->SetXY($x, $y + 5);
                $wrappedText = wordwrap(iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $title), 30, "\n", true);
                $lines = explode("\n", $wrappedText);
                $pdf->MultiCell($labelWidth, 4, implode("\n", array_slice($lines, 0, 2)), 0, 'C');

                // Oude prijs
                $pdf->SetFont('Arial', '', 12);
                $pdf->SetXY($x + 10, $y + 12);
                $prijs = schaplabel_korting . phpchr(128) . number_format($totalPrice, 2);
                $pdf->Cell(0, 8, $prijs, 0, 1, 'L');
                $breedte = $pdf->GetStringWidth($prijs);
                $pdf->Line($x + 11, $y + 16, $x + 10 + $breedte, $y + 15);

                // Nieuwe prijs
                $pdf->SetFont('Arial', 'B', 23);
                $pdf->SetTextColor(34, 150, 34);
                $pdf->SetXY($x + 27, $y + 17);
                $pdf->Cell($labelWidth / 2, 8, schaplabel_korting . phpchr(128) . number_format($newPrice, 2), 0, 1, 'R');
                $pdf->SetTextColor(0, 0, 0);

                // SKU
                $pdf->SetFont('Roboto', '', 8);
                $pdf->SetXY($x, $y + 24);
                $pdf->Cell($labelWidth, 10, 'SKU (' . $sku . ')', 0, 1, 'C');

                // Datum
                $pdf->SetFont('Roboto', '', 8);
                $pdf->SetXY($x, $y + 30);
                $pdf->Cell($labelWidth, 6, 'Van ' . date('d-m-y', strtotime($startDate)) . ' t/m ' . date('d-m-y', strtotime($endDate)), 0, 1, 'C');

            }

            $currentRow++;
        }
    }

    $pdf->Output('I', 'Labels.pdf');
    $stmt->close();
    $conn->close();
    exit;
}

require_once $_SERVER['DOCUMENT_ROOT'] . '/admin/includes/header.php';
?>


<style>
 #productSelectDiv {
    display: block;
}

.product-item {
    display: flex; /* Flexbox activeren */
    align-items: center; /* Elementen verticaal centreren */
    padding-bottom: 10px;
    margin-bottom: 10px;
    border-bottom: 1px solid #ddd; /* Lijn tussen items */
}
.product-label {
    flex: 1; /* Laat het label de overgebleven ruimte innemen */
}

.quantity-input {
    width: 60px; /* Vaste breedte voor consistentie */
    text-align: center;
}
.product-item:last-child {
    border-bottom: none; /* Geen lijn onder het laatste item */
}
</style>
<div class="container mt-5">
    <h2>Actie/promo producten Etiketten Genereren</h2>
    <a href="index.php" class="btn btn-primary">Terug naar menu schaplabel</a>
    <form id="labelForm" method="post" action="schaplabel_korting.php" target="_blank">
        <div class="card mt-2">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="sku">Zoek op SKU</label>
                        <input type="text" id="sku" class="form-control" placeholder="SKU nummer" onblur="fetchProductBySKU()">
                    </div>
                </div>
                <!-- <div class="col-md-3">
                    <div class="form-group">
                        <label for="category">Selecteer Categorie</label>
                        <select id="category" name="category" class="form-control" onchange="fetchProductsByCategory()">
                            <option value="">Selecteer</option>
                            <option value="epoxy">Epoxy</option>
                            <option value="kaars">Kaars</option>
                            <option value="vers">Vers</option>
                            <option value="inkoop">Inkoop</option>
                        </select>
                    </div>
                </div> -->
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Kies weergave:</label><br>
                        <input type="radio" id="showPrice" name="displayOption" value="price" checked>
                        <label for="showPrice">Prijs</label>
                        <!-- <input class="ml-2" type="radio" id="showBarcode" name="displayOption" value="barcode">
                        <label for="showBarcode">Barcode</label> -->
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="form-group">
                <label for="productSelectDiv">Selecteer Producten voor Labels</label>
                <div id="productSelectDiv">
                    <div class="product-list"></div>
                </div>
            </div>
        </div>


        <input type="hidden" name="labels" id="labelsInput">
        <button type="submit" class="btn btn-primary">Genereer Etiketten</button>
    </form>
    <script>
        let selectedProducts = [];

        function fetchProductBySKU() {
            const sku = document.getElementById('sku').value.trim();
            if (sku) {
                fetch(`fetch_product.php?sku=${encodeURIComponent(sku)}`)
                    .then(response => response.json())
                    .then(data => {
                        if (Array.isArray(data)) {
                            data.forEach(product => addProductToSelect(product));
                        } else {
                            alert('Geen producten gevonden.');
                        }
                    })
                    .catch(error => console.error('Fout bij ophalen van gegevens:', error));
            }
        }

        function addProductToSelect(product) {
            const productSelectDiv = document.getElementById('productSelectDiv');

            if (document.getElementById(`checkbox-${product.sku}`)) return;

            const div = document.createElement('div');
            div.classList.add('product-item');

            const checkbox = document.createElement('input');
            checkbox.type = 'checkbox';
            checkbox.value = product.sku;
            checkbox.id = `checkbox-${product.sku}`;
            checkbox.classList.add('product-checkbox');
            checkbox.dataset.title = product.name;  // <-- BELANGRIJK: name gebruiken
            checkbox.dataset.price = parseFloat(product.total_product_price);
            checkbox.style.marginRight = "10px";

            const label = document.createElement('label');
            label.setAttribute('for', `checkbox-${product.sku}`);
            label.textContent = `${product.sku} - ${product.name} (€${parseFloat(product.total_product_price).toFixed(2)})`;
            label.classList.add('product-label');

            // Rest blijft hetzelfde...
            const discountType = document.createElement('select');
            discountType.id = `discountType-${product.sku}`;
            discountType.classList.add('form-control', 'discount-type');
            discountType.innerHTML = '<option value="weekdeal">Weekdeal (10%)</option><option value="custom">Aangepaste korting</option>';
            discountType.style.width = '120px';

            const discountInput = document.createElement('input');
            discountInput.type = 'number';
            discountInput.id = `discount-${product.sku}`;
            discountInput.classList.add('form-control', 'discount-input');
            discountInput.placeholder = 'Korting %';
            discountInput.min = 0;
            discountInput.max = 100;
            discountInput.style.width = '120px';
            discountInput.style.display = 'none';

            discountType.addEventListener('change', function () {
                discountInput.style.display = this.value === 'custom' ? 'block' : 'none';
            });

            const quantityInput = document.createElement('input');
            quantityInput.type = 'number';
            quantityInput.id = `quantity-${product.sku}`;
            quantityInput.classList.add('form-control', 'quantity-input');
            quantityInput.value = 1;
            quantityInput.min = 1;
            quantityInput.style.width = '60px';

            const startDateInput = document.createElement('input');
            startDateInput.type = 'date';
            startDateInput.id = `startDate-${product.sku}`;
            startDateInput.classList.add('form-control', 'start-date-input');
            startDateInput.style.width = '130px';

            const endDateInput = document.createElement('input');
            endDateInput.type = 'date';
            endDateInput.id = `endDate-${product.sku}`;
            endDateInput.classList.add('form-control', 'end-date-input');
            endDateInput.style.width = '130px';

            div.appendChild(checkbox);
            div.appendChild(label);
            div.appendChild(discountType);
            div.appendChild(discountInput);
            div.appendChild(quantityInput);
            div.appendChild(startDateInput);
            div.appendChild(endDateInput);
            productSelectDiv.appendChild(div);

            checkbox.addEventListener('change', function () {
                toggleProductSelection({
                    sku: checkbox.value,
                    title: checkbox.dataset.title,   // NIET meer product.name gebruiken hier!
                    total_product_price: parseFloat(checkbox.dataset.price)
                }, checkbox.checked);
                updateSelectAllCheckbox();
            });
        }

    </script>

</div>

<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/admin/includes/footer.php'; ?>
