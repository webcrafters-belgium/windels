<?php

require $_SERVER["DOCUMENT_ROOT"] . '/ini.inc';
require $_SERVER["DOCUMENT_ROOT"] . '/assets/admin/fpdf/fpdf.php';
//require_once $_SERVER["DOCUMENT_ROOT"]. '/vendor/autoload.php';

//use Com\Tecnick\Barcode\Barcode;

error_reporting(E_ALL);
ini_set('display_errors', 1);

// -----------------------------
// FUNCTIE: NIEUWE PRODUCTEN OPHALEN UIT products TABEL
// -----------------------------
function fetchNewProducts($conn, $interval = 15, $startDate = null, $endDate = null) {

    $whereClauses = [];
    $params = [];
    $types = '';

    if ($startDate && $endDate) {
        $whereClauses[] = 'p.created_at BETWEEN ? AND ?';
        $params[] = $startDate . ' 00:00:00';
        $params[] = $endDate . ' 23:59:59';
        $types .= 'ss';
    } elseif ($interval) {
        $whereClauses[] = 'p.created_at >= NOW() - INTERVAL ? DAY';
        $params[] = $interval;
        $types .= 'i';
    } else {
        $whereClauses[] = 'p.created_at >= NOW() - INTERVAL 15 DAY';
    }

    $whereSql = implode(' AND ', $whereClauses);

    $query = "
        SELECT p.sku, p.name AS title, p.price AS total_product_price
        FROM products p
        WHERE $whereSql
        ORDER BY p.name ASC
    ";

    $stmt = $conn->prepare($query);

    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    return $result->fetch_all(MYSQLI_ASSOC);
}

// -----------------------------
// FILTERS OPHALEN
// -----------------------------

$interval = isset($_POST['interval']) ? (int)$_POST['interval'] : 15;
$startDate = isset($_POST['startDate']) ? $_POST['startDate'] : null;
$endDate = isset($_POST['endDate']) ? $_POST['endDate'] : null;

$products = fetchNewProducts($conn, $interval, $startDate, $endDate);

// -----------------------------
// POST: PDF GENEREREN
// -----------------------------

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['labels'])) {

    $labels = json_decode($_POST['labels'], true);
    $displayOption = $_POST['displayOption'] ?? 'price';

    if (!$labels || !is_array($labels)) {
        die('Ongeldige gegevens ontvangen.');
    }

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
                $pdf->Cell($labelWidth, 8, chr(128) . ' schaplabel_new.php' . number_format((float)$label['total_product_price'], 2), 0, 1, 'C');

                $pdf->SetFont('Roboto', '', 11);
                $pdf->SetXY($x, $y + 8);
                $title = iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $label['title']);
                $wrappedText = wordwrap($title, 30, "\n", true);
                $titleLines = explode("\n", $wrappedText);
                $titleLimited = implode("\n", array_slice($titleLines, 0, 2));
                $pdf->MultiCell($labelWidth, 4, $titleLimited, 0, 'C');

                $pdf->SetFont('Arial', '', 8);
                $pdf->SetXY($x, $y + 15);
                $pdf->Cell($labelWidth, 10, 'SKU (' . $label['sku'] . ')', 0, 1, 'C');

            } elseif ($displayOption === 'barcode') {

                $pdf->SetFont('Roboto', '', 11);
                $pdf->SetXY($x, $y + 5);
                $wrappedText = wordwrap(iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $label['title']), 30, "\n", true);
                $pdf->MultiCell($labelWidth, 4, $wrappedText, 0, 'C');

                $barcode = new Barcode();
                $bobj = $barcode->getBarcodeObj('C128', $label['sku'], -0, -30, 'black', [0, 0, 2, 0])->setBackgroundColor('white');
                $barcodeFile = 'barcode-' . $label['sku'] . '.png';
                file_put_contents($barcodeFile, $bobj->getPngData());
                $pdf->Image($barcodeFile, $x + ($labelWidth - 50) / 2, $y + 15, 50, 10);
                unlink($barcodeFile);

                $pdf->SetFont('Arial', '', 8);
                $pdf->SetXY($x, $y + 22);
                $pdf->Cell($labelWidth, 10, 'SKU (' . $label['sku'] . ')', 0, 1, 'C');
            }

            $currentRow++;
        }
    }

    $pdf->Output('I', 'Labels.pdf');
    exit;
}

require $_SERVER['DOCUMENT_ROOT'] . '/header.php';
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
    .product-item:last-child {
        border-bottom: none;
    }
</style>

<div class="container mt-5">
    <h2>Nieuwe producten Etiketten Genereren</h2>
    <a href="index.php" class="btn btn-primary">Terug naar menu schaplabel</a>
    <form id="labelForm" method="post" action="" target="_blank">

        <div class="card mt-2">
            <div class="row">
                <div class="col-md-3">
                    <label>Gewijzigd in de laatste:</label>
                    <select name="interval" id="interval" class="form-control">
                        <option value="1">Gisteren</option>
                        <option value="14" selected>Laatste 14 dagen</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label>Startdatum:</label>
                    <input type="date" id="startDate" class="form-control" name="startDate">
                </div>
                <div class="col-md-3">
                    <label>Einddatum:</label>
                    <input type="date" id="endDate" class="form-control" name="endDate">
                </div>
                <div class="col-md-3">
                    <label>Kies weergave:</label><br>
                    <input type="radio" name="displayOption" value="price" checked> Prijs
                    <input type="radio" name="displayOption" value="barcode" style="margin-left:10px;"> Barcode
                </div>
            </div>
        </div>

        <div class="card mt-2">
            <div class="form-group">
                <label>Selecteer Producten</label>
                <div>
                    <input type="checkbox" id="selectAll"> Alles selecteren
                </div>
                <div id="productSelectDiv">
                    <?php foreach ($products as $product): ?>
                        <div class="product-item">
                            <input type="checkbox" class="product-checkbox"
                                   data-product='<?php echo json_encode($product); ?>'>
                            <label class="product-label">
                                <?php echo htmlspecialchars($product['sku']); ?> -
                                <?php echo htmlspecialchars($product['title']); ?>
                                (€<?php echo number_format($product['total_product_price'], 2); ?>)
                            </label>
                            <input type="number" value="1" min="1" class="quantity-input">
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <input type="hidden" name="labels" id="labelsInput">
        <button type="submit" class="btn btn-primary">Genereer Etiketten</button>

    </form>
</div>

<script>
    const selectedProducts = [];

    document.getElementById('selectAll').addEventListener('change', function () {
        document.querySelectorAll('.product-checkbox').forEach(cb => {
            cb.checked = this.checked;
            toggleProduct(cb);
        });
    });

    document.querySelectorAll('.product-checkbox').forEach(cb => {
        cb.addEventListener('change', function () {
            toggleProduct(cb);
        });
    });

    document.querySelectorAll('.quantity-input').forEach((input, index) => {
        input.addEventListener('input', function () {
            const cb = document.querySelectorAll('.product-checkbox')[index];
            if (cb.checked) {
                updateQuantity(cb, input.value);
            }
        });
    });

    function toggleProduct(checkbox) {
        const product = JSON.parse(checkbox.dataset.product);
        const quantity = checkbox.parentElement.querySelector('.quantity-input').value || 1;
        const index = selectedProducts.findIndex(p => p.sku === product.sku);

        if (checkbox.checked && index === -1) {
            selectedProducts.push({...product, quantity: parseInt(quantity)});
        } else if (!checkbox.checked && index !== -1) {
            selectedProducts.splice(index, 1);
        }
        updateHiddenInput();
    }

    function updateQuantity(checkbox, quantity) {
        const product = JSON.parse(checkbox.dataset.product);
        const prod = selectedProducts.find(p => p.sku === product.sku);
        if (prod) {
            prod.quantity = parseInt(quantity) || 1;
        }
        updateHiddenInput();
    }

    function updateHiddenInput() {
        document.getElementById('labelsInput').value = JSON.stringify(selectedProducts);
    }

    document.getElementById('labelForm').addEventListener('submit', function (e) {
        if (selectedProducts.length === 0) {
            e.preventDefault();
            alert('Selecteer minstens één product.');
        }
    });

</script>

<?php require $_SERVER['DOCUMENT_ROOT'] . '/footer.php'; ?>
