<?php
global $conn;
ini_set('display_errors', 1);
ini_set('log_errors', 1);
error_log('/php_errors.log');

// Databaseverbinding
require $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';

// Zet JSON-header
header('Content-Type: application/json');

$query = "SELECT id, title, FORMAT(total_product_price, 2) AS total_product_price, stock, product_image FROM products ORDER BY title";
$result = $conn->query($query);

if ($result) {
    if ($result->num_rows > 0) {
        $html = '<table class="product-management-table">';
        $html .= '<tr>';
        $html .= '<th>Product</th>';
        $html .= '<th>Prijs</th>';
        $html .= '<th>Voorraad</th>';
        $html .= '<th>Acties</th>';
        $html .= '</tr>';
        while ($row = $result->fetch_assoc()) {
            $html .= '<tr>';
            $html .= '<td>' . htmlspecialchars($row['title']) . '</td>';
            $html .= '<td>€' . htmlspecialchars($row['total_product_price']) . '</td>'; // Prijs als decimaal
            $html .= '<td>' . htmlspecialchars($row['stock']) . '</td>';
            $html .= '<td><a href="/pages/products/edit.php?id=' . $row['id'] . '">Bewerken</a></td>';
            $html .= '</tr>';
        }
        $html .= '</table>';
        echo json_encode(['success' => true, 'html' => $html]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Geen products gevonden']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Fout bij query: ' . $conn->error]);
}
exit;
