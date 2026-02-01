<?php

require $_SERVER["DOCUMENT_ROOT"] . '/ini.inc';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Haal schapgegevens op
$stmt = $pdo_winkel->prepare("SELECT * FROM winkel_schappen WHERE id = ?");
$stmt->execute([$id]);
$schap = $stmt->fetch(PDO::FETCH_ASSOC);

// Functie om producten op te halen voor een schap
function fetchProductsForSchap($pdo_winkel, $schap_id) {
    $stmt = $pdo_winkel->prepare("SELECT * FROM product_schap WHERE schap_id = ?");
    $stmt->execute([$schap_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Functie om productdetails op te halen op basis van producttype
function fetchProductDetails($pdo_winkel, $product_id, $product_type) {
    switch ($product_type) {
        case 'epoxy':
            $stmt = $pdo_winkel->prepare("SELECT * FROM epoxy_products WHERE id = ?");
            break;
        case 'kaarsen':
            $stmt = $pdo_winkel->prepare("SELECT * FROM kaarsen_products WHERE id = ?");
            break;
        case 'vers':
            $stmt = $pdo_winkel->prepare("SELECT * FROM vers_products WHERE id = ?");
            break;
        case 'inkoop':
            $stmt = $pdo_winkel->prepare("SELECT * FROM inkoop_products WHERE id = ?");
            break;
        default:
            return null;
    }
    $stmt->execute([$product_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

$producten = fetchProductsForSchap($pdo_winkel, $id);

require $_SERVER["DOCUMENT_ROOT"] . '/header.php';
?>

<head>
    <meta charset="UTF-8">
    <title>Schap Bekijken en Bewerken</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.6.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <!-- Voeg FontAwesome toe voor iconen -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        .container {
            position: relative;
        }

        .schap-container {
            border: 1px solid #333;
            background-color: #f8f9fa;
            width: <?= $schap['breedte'] * 10; ?>px; /* Omrekenen van cm naar pixels */
            height: <?= $schap['hoogte'] * 10; ?>px; /* Omrekenen van cm naar pixels */
            margin: 20px auto;
            position: relative;
            overflow: hidden; /* Behoud de producten binnen de schap */
            transform: scale(1.0); /* Standaard zoom op 50% */
            transform-origin: top left; /* Zorgt voor correcte schaal */
        }

        .plank {
            border-top: 2px solid #666;
            width: 100%;
            position: absolute;
        }

        .product {
            border: 1px solid #adb5bd;
            background-color: #e2e6ea;
            width: 200px;
            height: 100px;
            position: absolute;
            cursor: move;
            padding: 2px;
        }

        .edit-product, .remove-product {
            float: right;
            margin-left: 10px;
            cursor: pointer;
        }

        .edit-product {
            color: blue;
        }

        .remove-product {
            color: red;
        }

        .schappaal {
            border-left: 4px solid #333;
            height: 100%;
            position: absolute;
            top: 0;
        }

        .schappaal-left {
            left: 0;
        }

        .schappaal-right {
            right: 0;
        }

        .hoogte-label {
            position: absolute;
            left: 10px; /* Verplaats de label naar links buiten de schap-container */
            font-size: 12px;
            color: #333;
            background-color: #f8f9fa; /* Achtergrond voor betere zichtbaarheid */
            padding: 2px 5px; /* Padding om de labels */
            border: 1px solid #ccc; /* Rand om de labels */
        }

        .zoom-control {
            position: fixed;
            top: 10px;
            right: 10px;
            z-index: 1000;
        }

        .zoom-percentage {
            margin-left: 10px;
            font-weight: bold;
        }

        .action-buttons {
            margin-top: 20px;
        }

        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
</head>

<div class="container mt-5">
    <div class="zoom-control">
        <button class="btn btn-secondary" id="zoom-out"><i class="fas fa-search-minus"></i> Uitzoomen</button>
        <button class="btn btn-secondary" id="zoom-in"><i class="fas fa-search-plus"></i> Inzoomen</button>
        <span class="zoom-percentage" id="zoom-percentage">Zoom: 50%</span> <!-- Zoompercentage label -->
    </div>

    <h2>Schap: <?= htmlspecialchars($schap['naam']); ?></h2>
    <div class="action-buttons">
        <!-- Knop om schap te bewerken -->
        <a href="index.php" class="btn btn-primary"><i class="fas fa-home"></i> terug naar schappen</a>
        
        <a href="edit_schap.php?id=<?= $schap['id']; ?>" class="btn btn-primary"><i class="fas fa-edit"></i> Schap Bewerken</a>
        
        <!-- Knop om een product toe te voegen -->
        <a href="add_productschap.php?schap_id=<?= $schap['id']; ?>" class="btn btn-success"><i class="fas fa-plus"></i> Product Toevoegen aan Schap</a>
        <!-- <button class="btn btn-secondary no-print" onclick="printSchap()">Print Schap</button> -->
        <a href="print_schap.php?id=<?= $schap['id']; ?>" class="btn btn-info"><i class="fas fa-print"></i> Print Schap</a>
    </div>

    <div class="schap-container" id="schap-container">
        <!-- Schappalen toevoegen -->
        <div class="schappaal schappaal-left"></div>
        <div class="schappaal schappaal-right"></div>

        <!-- Planken dynamisch toevoegen -->
        <?php
        $aantal_planken = (int)$schap['aantal_planken']; // Aantal planken in het schap
        $plank_hoogte = 4; // Plankdikte in cm
        $bovenruimte = 16; // Ruimte tussen bovenkant schap en eerste plank
        $onderruimte = 14; // Ruimte tussen laatste plank en onderkant schap
        $beschikbare_hoogte = $schap['hoogte'] - $bovenruimte - $onderruimte; // Hoogte beschikbaar voor planken
        $ruimte_tussen_planken = ($beschikbare_hoogte - ($aantal_planken * $plank_hoogte)) / ($aantal_planken - 1); // Ruimte tussen de planken

        // Planken dynamisch toevoegen
        for ($i = 0; $i < $aantal_planken; $i++):
            $top_positie = ($schap['hoogte'] - $onderruimte - ($i * ($ruimte_tussen_planken + $plank_hoogte))) * 10; // Bereken de positie van elke plank vanaf de onderkant
            $hoogte_cm = round($onderruimte + $i * ($ruimte_tussen_planken + $plank_hoogte), 1); // Bereken de hoogte in cm van onder naar boven
        ?>
            <div class="plank" style="top: <?= $top_positie; ?>px;"></div>
            <!-- Hoogte label voor elke plank -->
            <span class="hoogte-label" style="top: <?= $top_positie - 10; ?>px;"><?= $hoogte_cm; ?> cm</span>
        <?php endfor; ?>

        <!-- Cm-labels dynamisch toevoegen vanaf de onderkant -->
        <?php
        $stap_cm = 100; // Stapgrootte voor elke cm-label
        $max_hoogte = $schap['hoogte']; // Maximale hoogte van het schap in cm

        for ($cm = 0; $cm <= $max_hoogte; $cm += $stap_cm):
            $cm_positie = ($cm / $max_hoogte) * ($schap['hoogte'] * 10); // Bereken de positie in pixels
            $cm_positie = ($schap['hoogte'] * 10) - $cm_positie; // Draai de cm-labels om zodat 0 cm onderaan begint en oploopt
        ?>
            <span class="hoogte-label" style="top: <?= $cm_positie; ?>px;"><?= $cm; ?> cm</span>
        <?php endfor; ?>

        <!-- Producten in het schap tonen -->
        <?php foreach ($producten as $product): 
            // Bepaal product ID en type
            $product_id = null;
            $product_type = null;

            if (!empty($product['epoxy_product_id'])) {
                $product_id = $product['epoxy_product_id'];
                $product_type = 'epoxy';
            } elseif (!empty($product['kaarsen_product_id'])) {
                $product_id = $product['kaarsen_product_id'];
                $product_type = 'kaarsen';
            } elseif (!empty($product['vers_product_id'])) {
                $product_id = $product['vers_product_id'];
                $product_type = 'vers';
            } elseif (!empty($product['inkoop_product_id'])) {
                $product_id = $product['inkoop_product_id'];
                $product_type = 'inkoop';
            }

            if ($product_id && $product_type):
                $plank_index = $product['plank_nummer'] - 1; // Correctie van planknummer naar index
                
                // Bereken de top-positie zodat de onderkant van het product gelijk is met de bovenkant van de plank
                $product_height_px = 100; // Hoogte van het product in pixels (moet overeenkomen met de CSS .product height)
                $product_top = ($schap['hoogte'] - $onderruimte - ($plank_index * ($ruimte_tussen_planken + $plank_hoogte))) * 10 - $product_height_px; 
                
                // Haal de productdetails op
                $product_details = fetchProductDetails($pdo_winkel, $product_id, $product_type);

                if ($product_details):  // Controleer of productdetails succesvol zijn opgehaald
                    $product_name = htmlspecialchars($product_details['title']); // Vervang 'title' met het juiste kolomnaam van de database
                    $product_sku = htmlspecialchars($product_details['sku']); // Vervang 'sku' met het juiste kolomnaam van de database
        ?>
        <div class="product" id="product-<?= htmlspecialchars($product['id']); ?>" 
            style="left: <?= htmlspecialchars($product['positie_op_plank'] * 10); ?>px; top: <?= $product_top; ?>px;">
            <?= $product_name; ?> (SKU: <?= $product_sku; ?>)
            <!-- Bewerkknop voor elk product -->
            <?php if ($_SESSION['admin_role'] === 'Admin'): ?>
            <a href="edit_productschap.php?id=<?= htmlspecialchars($product['id']); ?>" class="edit-product"><i class="fas fa-edit"></i></a>
            <!-- Verwijderknop voor elk product -->
            <span class="remove-product" onclick="verwijderProduct(<?= htmlspecialchars($product['id']); ?>)"><i class="fas fa-trash-alt"></i></span>
       <?php endif; ?>
        </div>
        <?php 
                else:  // Voeg een fallback toe als productdetails niet worden gevonden
                    echo '<div class="product">Product details niet beschikbaar</div>';
                endif; 
            endif; 
        endforeach; ?>
    </div>
</div>

<script>
$(function() {
    let scale = 1.0;  // Start zoom niveau op 50%

    // Functie om het zoompercentage bij te werken
    function updateZoomPercentage() {
        $('#zoom-percentage').text('Zoom: ' + Math.round(scale * 100) + '%');
    }

    $(".product").draggable({
        containment: "#schap-container",
        stop: function(event, ui) {
            const productId = $(this).attr("id").split('-')[1];
            const positieX = ui.position.left / 10;  // Omrekenen naar cm
            const positieY = ui.position.top / 10;  // Omrekenen naar cm

            // Bepaal het planknummer op basis van de Y-positie en ruimte tussen planken
            const plankNummer = Math.floor((<?= $schap['hoogte']; ?> - (positieY / 10) - <?= $onderruimte; ?>) / (<?= $ruimte_tussen_planken + $plank_hoogte; ?>)) + 1;

            // Controleer of het planknummer binnen het bereik is
            if (plankNummer >= 1 && plankNummer <= <?= $aantal_planken; ?>) {
                // AJAX-verzoek om de nieuwe positie op te slaan
                $.ajax({
                    url: 'save_productpositie.php',
                    type: 'POST',
                    data: { 
                        product_id: productId, 
                        positie_x: positieX, 
                        plank_nummer: plankNummer 
                    },
                    success: function(response) {
                        alert('Product positie bijgewerkt!');
                    },
                    error: function() {
                        alert('Fout bij het opslaan van de positie.');
                    }
                });
            } else {
                alert('Product positie is buiten de toegestane planken.');
                $(this).css({
                    top: 'initial',  // Zet terug naar de oorspronkelijke positie
                    left: 'initial'
                });
            }
        }
    });

    $("#zoom-in").click(function() {
        scale += 0.1;
        $("#schap-container").css("transform", "scale(" + scale + ")");
        updateZoomPercentage();  // Werk het zoompercentage bij
    });

    $("#zoom-out").click(function() {
        scale -= 0.1;
        if (scale < 0.1) scale = 0.1;  // Minimaal zoomniveau
        $("#schap-container").css("transform", "scale(" + scale + ")");
        updateZoomPercentage();  // Werk het zoompercentage bij
    });

    // Update het zoompercentage bij het laden van de pagina
    updateZoomPercentage();
});

// Functie om product te verwijderen
function verwijderProduct(productId) {
    if (confirm('Weet je zeker dat je dit product wilt verwijderen?')) {
        $.ajax({
            url: 'delete_product_schap.php',
            type: 'POST',
            data: { product_id: productId },
            success: function(response) {
                if (response == 'success') {
                    $('#product-' + productId).remove(); // Verwijder het product uit de weergave
                    alert('Product verwijderd!');
                } else {
                    alert('Fout bij het verwijderen van het product.');
                }
            },
            error: function() {
                alert('Er is een fout opgetreden bij het verwijderen van het product.');
            }
        });
    }
}

</script>

<?php require $_SERVER["DOCUMENT_ROOT"] . '/footer.php'; ?>
