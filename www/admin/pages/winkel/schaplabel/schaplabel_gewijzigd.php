<?php

use fpdf\fpdf\FPDF;

require $_SERVER["DOCUMENT_ROOT"] . '/ini.inc';
require $_SERVER["DOCUMENT_ROOT"] . '/assets/admin/fpdf/fpdf.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Categorieën ophalen
function fetchAllCategories($conn) {
    $query = "SELECT id, name FROM categories ORDER BY name ASC";
    $result = $conn->query($query);
    return $result->fetch_all(MYSQLI_ASSOC);
}

$categories = fetchAllCategories($conn);

// PDF GENEREREN
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $labels = json_decode($_POST['labels'], true);
    $displayOption = $_POST['displayOption'] ?? 'price';

    if (!$labels || !is_array($labels)) {
        die('Ongeldige gegevens ontvangen.');
    }

    if (!empty($labels)) {
        $pdf = new FPDF('P', 'mm', 'A4');
        $pdf->SetMargins(0, 0, 0);
        $pdf->SetAutoPageBreak(false);
        $pdf->AddPage();

        $labelWidth = 70;
        $labelHeight = 37;
        $marginLeft = 0;
        $marginTop = 7;
        $labelsPerRow = 3;
        $rowsPerPage = 8;
        $currentRow = 0;

        $pdf->AddFont('Roboto', '', 'Roboto-Regular.php');

        foreach ($labels as $label) {
            for ($j = 0; $j < $label['quantity']; $j++) {
                $x = $marginLeft + ($labelWidth * ($currentRow % $labelsPerRow));
                $y = $marginTop + ($labelHeight * floor($currentRow / $labelsPerRow)) + 5;

                if ($currentRow > 0 && $currentRow % ($labelsPerRow * $rowsPerPage) === 0) {
                    $pdf->AddPage();
                    $y = $marginTop;
                    $currentRow = 0;
                }

                if ($displayOption === 'price') {
                    $pdf->SetFont('Roboto', '', 17);
                    $pdf->SetXY($x, $y);
                    $pdf->Cell($labelWidth, 8, chr(128) . ' schaplabel_gewijzigd.php' . number_format((float)$label['total_product_price'], 2), 0, 1, 'C');

                    $pdf->SetFont('Roboto', '', 11);
                    $pdf->SetXY($x, $y + 8);
                    $maxCharsPerLine = 30;
                    $title = iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $label['name']);
                    $wrappedText = wordwrap($title, $maxCharsPerLine, "\n", true);
                    $titleLines = explode("\n", $wrappedText);
                    $titleLimited = implode("\n", array_slice($titleLines, 0, 2));
                    $pdf->MultiCell($labelWidth, 4, $titleLimited, 0, 'C');

                    $pdf->SetFont('Arial', '', 8);
                    $pdf->SetXY($x, $y + 15);
                    $pdf->Cell($labelWidth, 10, 'SKU (' . $label['sku'] . ')', 0, 1, 'C');

                } elseif ($displayOption === 'barcode') {
                    // Barcode weergave
                    $x = $marginLeft + ($labelWidth * ($currentRow % $labelsPerRow));
                    $y = $marginTop + ($labelHeight * floor($currentRow / $labelsPerRow)) - 5;

                    if ($currentRow > 0 && $currentRow % ($labelsPerRow * $rowsPerPage) === 0) {
                        $pdf->AddPage();
                        $y = $marginTop;
                        $currentRow = 0;
                    }

                    $pdf->SetFont('Roboto', '', 11);
                    $pdf->SetXY($x, $y + 5);
                    $wrappedText = wordwrap(iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $label['name']), 30, "\n", true);
                    $pdf->MultiCell($labelWidth, 4, $wrappedText, 0, 'C');

                    // Barcode (vereist dat je barcode-code toevoegt of activeert)
                    // $barcode = new Barcode();
                    // $bobj = $barcode->getBarcodeObj('C128', $label['sku'], -0, -30, 'black', [0, 0, 2, 0])->setBackgroundColor('white');
                    // $barcodeFile = 'barcode-' . $label['sku'] . '.png';
                    // file_put_contents($barcodeFile, $bobj->getPngData());
                    // $pdf->Image($barcodeFile, $x + ($labelWidth - 50) / 2, $y + 15, 50, 10);
                    // unlink($barcodeFile);

                    $pdf->SetFont('Arial', '', 8);
                    $pdf->SetXY($x, $y + 22);
                    $pdf->Cell($labelWidth, 10, 'SKU (' . $label['sku'] . ')', 0, 1, 'C');
                }

                $currentRow++;
            }
        }

        $pdf->Output('I', 'Labels.pdf');
    }
}

require_once $_SERVER['DOCUMENT_ROOT'] . '/admin/includes/header.php';
?>
<style>
    #productSelectDiv {
        display: block;
    }

    .product-item {
        display: flex;
        align-items: center;
        padding-bottom: 10px;
        margin-bottom: 10px;
        border-bottom: 1px solid #ddd;
    }
    .product-label {
        flex: 1;
        padding-left: 10px;
    }
    .quantity-input {
        width: 60px;
        text-align: center;
    }
</style>

<div class="container mt-5">
    <h2>Gewijzigde producten Etiketten Genereren</h2>
    <a href="index.php" class="btn btn-primary">Terug naar menu schaplabel</a>
    <form id="labelForm" method="post" action="schaplabel.php" target="_blank">
        <div class="card mt-2">
            <div class="row">
                <div class="col-md-3">
                    <label for="categorySelect">Filter op categorie:</label>
                    <select id="categorySelect" class="form-control">
                        <option value="">Alle categorieën</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="interval">Gewijzigd in de laatste:</label>
                    <select id="interval" class="form-control">
                        <option value="1">Gisteren</option>
                        <option value="14">Laatste 14 dagen</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="startDate">Startdatum:</label>
                    <input type="date" id="startDate" class="form-control">
                </div>
                <div class="col-md-3">
                    <label for="endDate">Einddatum:</label>
                    <input type="date" id="endDate" class="form-control">
                </div>
            </div>
            <div class="col-md-3 mt-2">
                <label>Kies weergave:</label><br>
                <input type="radio" name="displayOption" value="price" checked> Prijs
                <input type="radio" name="displayOption" value="barcode" style="margin-left:10px;"> Barcode
            </div>
        </div>

        <div class="card mt-2">
            <div class="form-group">
                <label>Selecteer Producten</label>
                <div id="productSelectDiv"></div>
            </div>
        </div>

        <input type="hidden" name="labels" id="labelsInput">
        <button type="submit" class="btn btn-primary">Genereer Etiketten</button>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        let selectedProducts = [];

        const intervalSelect = document.getElementById('interval');
        const startDateInput = document.getElementById('startDate');
        const endDateInput = document.getElementById('endDate');
        const categorySelect = document.getElementById('categorySelect');

        function fetchNewProducts() {
            let interval = intervalSelect.value;
            let startDate = startDateInput.value;
            let endDate = endDateInput.value;
            let categoryId = categorySelect.value;

            let postData = "";

            if (startDate && endDate) {
                postData = `startDate=${startDate}&endDate=${endDate}`;
            } else {
                postData = `interval=${interval}`;
            }

            if (categoryId) {
                postData += `&categoryId=${categoryId}`;
            }

            fetch('fetch_gewijzigd_products.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: postData
            })
                .then(response => response.json())
                .then(data => {
                    const div = document.getElementById('productSelectDiv');
                    div.innerHTML = '';

                    if (data && data.length > 0) {
                        addSelectAllCheckbox();
                        data.forEach(product => addProductToSelect(product));
                    } else {
                        div.innerHTML = '<p>Geen producten gevonden.</p>';
                    }
                })
                .catch(error => console.error('Fout bij ophalen producten:', error));
        }

        function addSelectAllCheckbox() {
            const div = document.getElementById('productSelectDiv');
            const container = document.createElement('div');
            container.classList.add('product-item');

            const checkbox = document.createElement('input');
            checkbox.type = 'checkbox';
            checkbox.id = 'selectAll';

            const label = document.createElement('label');
            label.textContent = 'Alles selecteren/deselecteren';
            label.style.marginLeft = '10px';

            container.appendChild(checkbox);
            container.appendChild(label);
            div.appendChild(container);

            checkbox.addEventListener('change', function () {
                document.querySelectorAll('.product-checkbox').forEach(cb => {
                    cb.checked = this.checked;
                    toggleProduct(cb);
                });
            });
        }

        function addProductToSelect(product) {
            const div = document.getElementById('productSelectDiv');

            const container = document.createElement('div');
            container.classList.add('product-item');

            const checkbox = document.createElement('input');
            checkbox.type = 'checkbox';
            checkbox.value = product.sku;
            checkbox.classList.add('product-checkbox');
            checkbox.dataset.name = product.name;
            checkbox.dataset.price = product.total_product_price;

            const label = document.createElement('label');
            label.textContent = `${product.sku} - ${product.name} (€${parseFloat(product.total_product_price).toFixed(2)})`;
            label.classList.add('product-label');

            const qty = document.createElement('input');
            qty.type = 'number';
            qty.value = 1;
            qty.min = 1;
            qty.classList.add('quantity-input');

            container.appendChild(checkbox);
            container.appendChild(label);
            container.appendChild(qty);
            div.appendChild(container);

            checkbox.addEventListener('change', function () {
                toggleProduct(checkbox);
            });

            qty.addEventListener('change', function () {
                toggleProduct(checkbox);
            });
        }

        function toggleProduct(checkbox) {
            const sku = checkbox.value;
            const name = checkbox.dataset.name;
            const price = parseFloat(checkbox.dataset.price);
            const qtyInput = checkbox.parentElement.querySelector('.quantity-input');
            const qty = parseInt(qtyInput.value) || 1;

            const index = selectedProducts.findIndex(p => p.sku === sku);

            if (checkbox.checked) {
                if (index === -1) {
                    selectedProducts.push({sku: sku, name: name, total_product_price: price, quantity: qty});
                } else {
                    selectedProducts[index].quantity = qty;
                }
            } else {
                if (index !== -1) {
                    selectedProducts.splice(index, 1);
                }
            }

            document.getElementById('labelsInput').value = JSON.stringify(selectedProducts);
        }

        // Filters
        intervalSelect.addEventListener('change', fetchNewProducts);
        startDateInput.addEventListener('change', fetchNewProducts);
        endDateInput.addEventListener('change', fetchNewProducts);
        categorySelect.addEventListener('change', fetchNewProducts);

        fetchNewProducts(); // Eerste keer laden
    });
</script>


<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/admin/includes/footer.php'; ?>
