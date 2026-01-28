<?php

require $_SERVER["DOCUMENT_ROOT"] . '/ini.inc';

// Controleer of er een schap-ID is opgegeven
if (isset($_POST['id'])) {
    $schap_id = (int)$_POST['id'];

    try {
        // Start een transactie
        $pdo_winkel->beginTransaction();

        // Controleer of er producten in het schap staan
        $checkProductsStmt = $pdo_winkel->prepare("SELECT COUNT(*) FROM product_schap WHERE schap_id = ?");
        $checkProductsStmt->execute([$schap_id]);
        $productCount = $checkProductsStmt->fetchColumn();

        if ($productCount > 0) {
            // Verwijder de producten uit het schap
            $deleteProductsStmt = $pdo_winkel->prepare("DELETE FROM product_schap WHERE schap_id = ?");
            if (!$deleteProductsStmt->execute([$schap_id])) {
                throw new Exception('Fout bij het verwijderen van producten.');
            }
        }

        // Verwijder het schap uit de `winkel_schappen` tabel
        $deleteSchapStmt = $pdo_winkel->prepare("DELETE FROM winkel_schappen WHERE id = ?");
        if (!$deleteSchapStmt->execute([$schap_id])) {
            throw new Exception('Fout bij het verwijderen van het schap.');
        }

        // Commit de transactie als alles goed is gegaan
        $pdo_winkel->commit();
        echo 'success'; // Verwijdering succesvol
        header('Location: producten.php');

    } catch (Exception $e) {
        // Rol de transactie terug bij een fout
        $pdo_winkel->rollBack();
        echo 'error: ' . $e->getMessage(); // Toon een foutbericht
    }

} else {
    echo 'invalid'; // Ongeldig verzoek
}
?>
