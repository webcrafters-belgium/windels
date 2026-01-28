<?php

// Schaplabel.php

// Vereiste bestanden
require $_SERVER["DOCUMENT_ROOT"] . '/ini.inc'; // Verwijs hier naar config.php
require $_SERVER["DOCUMENT_ROOT"] . '/assets/admin/fpdf/fpdf.php'; // FPDF-bibliotheek

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Controleer de databaseverbinding
if ($conn->connect_error) {
    die('Fout bij verbinden met de database: ' . $conn->connect_error) ;
}

// Verwerking van de aanvraag om een product op SKU te zoeken
if (isset($_GET['sku'])) {
    try {
        $sku = $_GET['sku'];
        $product = fetchProductBySKU($conn, $sku);
        echo json_encode($product);
    } catch (Exception $e) {
        error_log($e->getMessage());
        echo json_encode([]);
    }
}

// Verwerking van de aanvraag om products per categorie te zoeken
if (isset($_GET['category'])) {
    try {
        $category = $_GET['category'];
        $products = fetchProductsByCategory($conn, $category);
        echo json_encode($products);
    } catch (Exception $e) {
        error_log($e->getMessage());
        echo json_encode([]);
    }
}

// Functie om products op SKU op te halen
function fetchProductBySKU($mysqli, $sku) {
    $query = "
        SELECT sku, title, total_product_price
        FROM (
            SELECT sku, title, total_product_price FROM epoxy_products
            UNION ALL
            SELECT sku, title, total_product_price FROM kaarsen_products
            UNION ALL
            SELECT sku, title, total_product_price FROM vers_products
        ) AS combined
        WHERE sku = ?
    ";

    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('s', $sku);
    $stmt->execute();
    $result = $stmt->get_result();

    return $result->fetch_assoc();
}

// Functie om products per categorie op te halen
function fetchProductsByCategory($mysqli, $category) {
    $query = "
        SELECT sku, title, total_product_price
        FROM (
            SELECT sku, title, total_product_price, 'epoxy' AS category FROM epoxy_products
            UNION ALL
            SELECT sku, title, total_product_price, 'kaars' AS category FROM kaarsen_products
            UNION ALL
            SELECT sku, title, total_product_price, 'vers' AS category FROM vers_products
        ) AS combined
        WHERE category = ?
    ";

    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('s', $category);
    $stmt->execute();
    $result = $stmt->get_result();

    return $result->fetch_all(MYSQLI_ASSOC);
}

// Verwerking van POST-aanvragen voor het genereren van labels
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $labels = json_decode($_POST['labels'], true);

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
        $marginTop = 4;
        $labelsPerRow = 3;
        $rowsPerPage = 8;
        $currentRow = 0;

        $pdf->AddFont('Roboto', '', 'Roboto-Regular.php');

        foreach ($labels as $label) {
            for ($j = 0; $j < $label['quantity']; $j++) {
                $x = $marginLeft + ($labelWidth * ($currentRow % $labelsPerRow));
                $y = $marginTop + ($labelHeight * floor($currentRow / $labelsPerRow));

                if ($currentRow > 0 && $currentRow % ($labelsPerRow * $rowsPerPage) === 0) {
                    $pdf->AddPage();
                    $y = $marginTop;
                    $currentRow = 0;
                }

                // Stel het font in voor de prijs (17pt, Roboto)
                $pdf->SetFont('Roboto', '', 17);
                $pdf->SetXY($x, $y);
                $pdf->Cell($labelWidth, 8, chr(128) . ' ' . number_format((float)$label['total_product_price'], 2), 0, 1, 'C');

                // Stel het font in voor de titel (11pt, Roboto)
                $pdf->SetFont('Roboto', '', 11);
                $pdf->SetXY($x, $y + 8); // Dit zorgt ervoor dat de titel onder de prijs komt
                $pdf->MultiCell($labelWidth, 4, $label['title'], 0, 'C');

                // Stel het font in voor de SKU (8pt, Arial, regulier)
                $pdf->SetFont('Arial', '', 8);
                $pdf->SetXY($x, $y + 15);
                $pdf->Cell($labelWidth, 4, 'SKU (' . $label['sku'] . ')', 0, 1, 'C');

                $currentRow++;
            }
        }


        $pdf->Output('I', 'Labels.pdf');
        exit;
    }
}

require $_SERVER['DOCUMENT_ROOT'] . '/header.php';
?>
<style>
    #productSelectDiv {
        display: block !important;
    }
</style>
<div class="container mt-5">
    <h2>Etiketten Genereren</h2>
    <form id="labelForm" method="post" action="schaplabel.php">
        <div class="form-group">
            <label for="sku">Zoek op SKU</label>
            <input type="text" id="sku" class="form-control" placeholder="SKU nummer" onblur="fetchProductBySKU()">
        </div>
        <div class="form-group">
            <label for="category">Selecteer Categorie</label>
            <select id="category" name="category" class="form-control" onchange="fetchProductsByCategory()">
                <option value="">Selecteer</option>
                <option value="epoxy">Epoxy</option>
                <option value="kaars">Kaars</option>
                <option value="vers">Vers</option>
            </select>
        </div>

        <div class="form-group">
            <label for="productSelectDiv">Selecteer Producten voor Labels</label>
            <div id="productSelectDiv"></div>
        </div>

        <input type="hidden" name="labels" id="labelsInput">
        <button type="submit" class="btn btn-primary">Genereer Etiketten</button>
    </form>
    <script>
        // Maintain a global array for selected products
        const selectedProducts = [];

        document.getElementById('sku').addEventListener('input', function () {
            clearProductList(); // Maak de lijst leeg
            fetchProductBySKU(); // Roep de fetchProductBySKU functie aan bij elke toetsdruk
        });

        document.getElementById('category').addEventListener('input', function () {
            clearProductList(); // Maak de lijst leeg
            fetchProductsByCategory(); // Roep de fetchProductsByCategory functie aan bij elke toetsdruk
        });

        // Leeg de productlijst, behalve de geselecteerde products
        function clearProductList() {
            const productSelectDiv = document.getElementById('productSelectDiv');
            if (productSelectDiv) {
                // Verwijder alle huidige products
                const productItems = productSelectDiv.getElementsByClassName('product-item');
                while (productItems.length > 0) {
                    productItems[0].remove(); // Verwijder elk product item
                }

                // Voeg de geselecteerde products opnieuw toe
                selectedProducts.forEach(product => {
                    addProductToSelect(product, true); // De tweede parameter zorgt ervoor dat geselecteerde products worden toegevoegd
                });
            }
        }

        // Fetch product by SKU
        function fetchProductBySKU() {
            const sku = document.getElementById('sku').value.trim();
            if (sku) {
                fetch(`fetch_product.php?sku=${encodeURIComponent(sku)}`)
                    .then(response => response.json())
                    .then(data => {
                        console.log("API Response:", data);

                        // Controleer of data een array van products is
                        if (Array.isArray(data)) {
                            // Loop door elk product in de array en voeg het toe
                            data.forEach(product => {
                                addProductToSelect(product);
                            });
                        } else {
                            alert('Geen producten gevonden.');
                        }
                    })
                    .catch(error => {
                        console.error('Fout bij ophalen van gegevens:', error);
                    });
            }
        }

        // Fetch products by category
        function fetchProductsByCategory() {
            const category = document.getElementById('category').value;
            if (category) {
                fetch(`fetch_product.php?category=${category}`)
                    .then(response => response.json())
                    .then(data => {
                        const productSelectDiv = document.getElementById('productSelectDiv');
                        productSelectDiv.style.display = 'block';
                        if (data && data.length > 0) {
                            data.forEach(product => addProductToSelect(product));
                        } else {
                            alert('Geen producten gevonden voor deze categorie.');
                        }
                    })
                    .catch(error => alert('Fout bij ophalen van categorie: ' + error));
            }
        }

        // Add a product to the select list
        function addProductToSelect(product, isSelected = false) {
            console.log("Adding product:", product);

            const productSelectDiv = document.getElementById('productSelectDiv');
            if (!productSelectDiv) {
                console.error("Element #productSelectDiv niet gevonden!");
                return;
            }

            // Check of product al is toegevoegd (doe dit op basis van SKU)
            if (document.getElementById(`checkbox-${product.sku}`)) {
                console.warn(`Product met SKU ${product.sku} is al toegevoegd.`);
                return;
            }

            // Maak een nieuw div-element voor het product
            const div = document.createElement('div');
            div.classList.add('product-item');

            const checkbox = document.createElement('input');
            checkbox.type = 'checkbox';
            checkbox.value = product.sku;
            checkbox.id = `checkbox-${product.sku}`;
            checkbox.classList.add('product-checkbox');

            checkbox.addEventListener('change', () => toggleProductSelection(product, checkbox.checked));

            const label = document.createElement('label');
            label.setAttribute('for', `checkbox-${product.sku}`);
            label.textContent = `${product.sku} - ${product.title} (€${parseFloat(product.total_product_price).toFixed(2)})`;

            div.appendChild(checkbox);
            div.appendChild(label);
            productSelectDiv.appendChild(div);

            // Als het product al geselecteerd is, vink de checkbox aan
            if (isSelected) {
                checkbox.checked = true;
            }

            console.log("Product toegevoegd aan DOM:", div);
        }

        // Toggle product selection
        function toggleProductSelection(product, isSelected) {
            // Als het product geselecteerd is, voeg het toe aan de array
            if (isSelected) {
                // Controleer of het product al in de array zit om duplicaten te voorkomen
                if (!selectedProducts.some(p => p.sku === product.sku)) {
                    selectedProducts.push({
                        sku: product.sku,
                        title: product.title,
                        total_product_price: product.total_product_price,
                        quantity: 1, // Begin met een hoeveelheid van 1
                    });
                }
            } else {
                // Als het product niet geselecteerd is, verwijder het uit de array
                const index = selectedProducts.findIndex(p => p.sku === product.sku);
                if (index !== -1) {
                    selectedProducts.splice(index, 1); // Verwijder het product uit de array
                }
            }

            // Update het verborgen invoerveld dat de geselecteerde products bevat
            document.getElementById('labelsInput').value = JSON.stringify(selectedProducts);
        }

        // Handle form submission
        document.getElementById('labelForm').addEventListener('submit', function (e) {
            e.preventDefault();

            if (selectedProducts.length > 0) {
                document.getElementById('labelsInput').value = JSON.stringify(selectedProducts);
                this.submit(); // Sta de form submission toe
            } else {
                alert('Selecteer minstens één product.');
            }
        });

    </script>

</div>

<?php require $_SERVER['DOCUMENT_ROOT'] . '/footer.php'; ?>
