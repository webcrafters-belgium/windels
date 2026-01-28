<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = (int)$_POST['product_id'];
    $title = $conn->real_escape_string($_POST['title']);
    $description = $conn->real_escape_string($_POST['description']);
    $start_date = $conn->real_escape_string($_POST['start_date']);
    $end_date = $conn->real_escape_string($_POST['end_date']);
    $created_at = date('Y-m-d H:i:s');

    // 1️⃣ Oude actieve weekdeal producten terugzetten naar regular_price
    $oldDeals = $conn->query("SELECT product_id FROM weekly_deals WHERE end_date >= CURDATE()");
    if ($oldDeals && $oldDeals->num_rows > 0) {
        while ($old = $oldDeals->fetch_assoc()) {
            $pid = (int)$old['product_id'];
            $updateOld = "
                UPDATE products
                SET 
                    price = regular_price,
                    sale_price = 0,
                    updated_at = NOW()
                WHERE id = $pid
            ";
            $conn->query($updateOld);
        }
    }

    // 2️⃣ Oude weekdeals verwijderen
    $conn->query("DELETE FROM weekly_deals WHERE end_date >= CURDATE()");

    // 3️⃣ Originele prijs van nieuwe product ophalen
    $result = $conn->query("SELECT price FROM products WHERE id = {$product_id} LIMIT 1");
    if (!$result || $result->num_rows === 0) {
        die("Product niet gevonden.");
    }
    $row = $result->fetch_assoc();
    $original_price = (float)$row['price'];

    // 4️⃣ 10% korting berekenen
    $discount_percentage = 10;
    $new_price = round($original_price * 0.9, 2);

    // 5️⃣ Nieuwe weekdeal invoegen
    $sql = "
        INSERT INTO weekly_deals 
        (product_id, title, description, new_price, discount_percentage, start_date, end_date, created_at, updated_at)
        VALUES 
        ($product_id, '$title', '$description', $new_price, $discount_percentage, '$start_date', '$end_date', '$created_at', '$created_at')
    ";
    if ($conn->query($sql) !== TRUE) {
        die("Fout bij toevoegen weekdeal: " . $conn->error);
    }

    // 6️⃣ Nieuwe productprijzen instellen
    $updateProductSql = "
        UPDATE products
        SET 
            regular_price = $original_price,
            sale_price = $new_price,
            price = $new_price,
            updated_at = NOW()
        WHERE id = $product_id
    ";
    if ($conn->query($updateProductSql) !== TRUE) {
        die("Fout bij updaten productprijs: " . $conn->error);
    }

    // Klaar → terug naar overzicht
    header('Location: /admin/deal_van_de_week/index.php?success=1');
    exit;

} else {
    header('Location: /admin/deal_van_de_week');
    exit;
}
