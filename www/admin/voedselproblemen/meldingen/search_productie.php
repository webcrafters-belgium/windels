<?php
require $_SERVER["DOCUMENT_ROOT"] . '/ini.inc';

$search_term = isset($_GET['search']) ? $_GET['search'] : '';

// Haal productiegegevens op met de zoekterm, indien aanwezig
try {
    $sql = "
        SELECT vp.lotnummer, vp.aantal_gemaakt, vp.productie_datum, vp.vervaldatum, p.title 
        FROM vers_productie vp
        JOIN winkel.vers_products p ON vp.vers_product_id = p.id
    ";

    if ($search_term) {
        $sql .= "WHERE vp.lotnummer LIKE :search OR vp.productie_datum LIKE :search OR vp.vervaldatum LIKE :search";
    }

    $sql .= " ORDER BY vp.productie_datum DESC";

    $stmt = $pdo_voedselproblemen->prepare($sql);

    if ($search_term) {
        $stmt->bindValue(':search', '%' . $search_term . '%', PDO::PARAM_STR);
    }

    $stmt->execute();
    $productiegegevens = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Stuur de resultaten terug als JSON
    echo json_encode($productiegegevens);

} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}

?>
