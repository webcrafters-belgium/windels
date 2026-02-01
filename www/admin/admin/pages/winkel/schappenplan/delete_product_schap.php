<?php

require $_SERVER["DOCUMENT_ROOT"] . '/ini.inc';

// Controleer of er een product-ID is opgegeven
if (isset($_POST['product_id'])) {
    $product_id = (int)$_POST['product_id'];

    // Verwijder het product uit de `product_schap` tabel
    $stmt = $pdo_winkel->prepare("DELETE FROM product_schap WHERE id = ?");
    if ($stmt->execute([$product_id])) {
        echo 'success'; // Verwijdering succesvol
    } else {
        echo 'error'; // Fout bij het verwijderen
    }
} else {
    echo 'invalid'; // Ongeldig verzoek
}
?>
