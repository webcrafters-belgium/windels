<?php

require $_SERVER["DOCUMENT_ROOT"] . '/ini.inc';
require $_SERVER["DOCUMENT_ROOT"] . '/lib/fpdf/fpdf.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

// -----------------------------
// DATABASE CONNECTIE CONTROLE
// -----------------------------
if ($conn->connect_error) {
    die('Fout bij verbinden met de database: ' . $conn->connect_error);
}

// -----------------------------
// FUNCTIE: ALLE categorieën OPHALEN
// -----------------------------
function fetchAllCategories($conn) {
    $query = "SELECT id, name FROM categories ORDER BY name ASC";
    $result = $conn->query($query);
    return $result->fetch_all(MYSQLI_ASSOC);
}
$categories = fetchAllCategories($conn);

// -----------------------------
// FUNCTIE: PRODUCTEN OPHALEN PER CATEGORIE
// -----------------------------
function fetchProductsByCategory($conn, $categoryId = null) {
    if ($categoryId) {
        $query = "
            SELECT p.sku, p.name, p.price AS total_product_price 
            FROM products p
            JOIN product_categories pc ON p.id = pc.product_id
            WHERE pc.category_id = ?
            ORDER BY p.name ASC
        ";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('i', $categoryId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    } else {
        // Geen categorie gekozen -> alles tonen
        $query = "SELECT sku, name, price AS total_product_price FROM products ORDER BY name ASC";
        $result = $conn->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}

// -----------------------------
// CATEGORIE ID UIT URL HALEN
// -----------------------------
$selectedCategory = isset($_GET['category']) ? (int)$_GET['category'] : null;
$products = fetchProductsByCategory($conn, $selectedCategory);

// -----------------------------
// POST: PDF GENEREREN
// -----------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $labels = json_decode($_POST['labels'], true);

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

            $pdf->SetFont('Roboto', '', 20);
            $pdf->SetXY($x, $y);
            $pdf->Cell($labelWidth, 6, chr(128) . ' schaplabel.php' . number_format((float)$label['total_product_price'], 2), 0, 1, 'C');

            $pdf->SetFont('Roboto', '', 11);
            $pdf->SetXY($x, $y + 8);
            $title = iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $label['name']);
            $wrappedText = wordwrap($title, 30, "\n", true);
            $pdf->MultiCell($labelWidth, 4, $wrappedText, 0, 'C');

            $pdf->SetFont('Arial', '', 8);
            $pdf->SetXY($x, $y + 15);
            $pdf->Cell($labelWidth, 10, 'SKU (' . $label['sku'] . ')', 0, 1, 'C');

            $currentRow++;
        }
    }

    $pdf->Output('I', 'Labels.pdf');
    exit;
}

require $_SERVER['DOCUMENT_ROOT'] . '/header.php';
?>

<style>
    .product-item {
        display: flex;
        align-items: center;
        margin-bottom: 5px;
    }
    .product-label {
        flex: 1;
        padding-left: 10px;
    }
    .quantity-input {
        width: 60px;
        margin-left: 10px;
    }
</style>

<div class="container mt-5">
    <h2>Etiketten Genereren</h2>

    <div class="form-group">
        <label for="categorySelect">Filter op categorie</label>
        <select id="categorySelect" class="form-control mb-3" onchange="onCategoryChange()">
            <option value="">Alle categorieën</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?php echo $cat['id']; ?>" <?php if($selectedCategory == $cat['id']) echo 'selected'; ?>>
                    <?php echo htmlspecialchars($cat['name']); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group">
        <label for="skuSearch">Zoek op SKU of naam</label>
        <input type="text" id="skuSearch" class="form-control mb-3" placeholder="Typ een SKU of naam..." oninput="filterProducts()">
    </div>

    <form id="labelForm" method="post" action="schaplabel.php" target="_blank">
        <div class="form-group">
            <label>Producten</label>
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
                            <?php echo htmlspecialchars($product['name']); ?>
                            (€<?php echo number_format($product['total_product_price'], 2); ?>)
                        </label>
                        <input type="number" value="1" min="1" class="quantity-input">
                    </div>
                <?php endforeach; ?>
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

    function filterProducts() {
        const search = document.getElementById('skuSearch').value.toLowerCase();
        document.querySelectorAll('#productSelectDiv .product-item').forEach(item => {
            const text = item.querySelector('.product-label').textContent.toLowerCase();
            if (text.includes(search)) {
                item.style.display = '';
            } else {
                item.style.display = 'none';
            }
        });
    }

    function onCategoryChange() {
        const categoryId = document.getElementById('categorySelect').value;
        const params = new URLSearchParams(window.location.search);
        if (categoryId) {
            params.set('category', categoryId);
        } else {
            params.delete('category');
        }
        window.location.search = params.toString();
    }
</script>

<?php require $_SERVER['DOCUMENT_ROOT'] . '/footer.php'; ?>
