<?php
// Try multiple paths for config
$configPaths = [
    $_SERVER['DOCUMENT_ROOT'] . '/admin/admin/config.php',
    __DIR__ . '/../../config.php'
];

foreach ($configPaths as $path) {
    if (file_exists($path)) {
        require_once $path;
        break;
    }
}
requireAdmin();

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="klanten_' . date('Y-m-d_H-i') . '.csv"');

$output = fopen('php://output', 'w');

// BOM for Excel UTF-8 compatibility
fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

// Header row
fputcsv($output, ['ID', 'Naam', 'Email', 'Telefoon', 'Straat', 'Nummer', 'Postcode', 'Stad', 'Land', 'Lid Sinds', 'Totaal Orders', 'Totaal Besteed'], ';');

// Fetch all customers with stats
$customers = $conn->query("
    SELECT u.*, 
           COUNT(DISTINCT o.id) as order_count,
           COALESCE(SUM(o.total_price), 0) as total_spent
    FROM users u
    LEFT JOIN orders o ON u.email = o.email
    GROUP BY u.id
    ORDER BY u.created_at DESC
");

while ($customer = $customers->fetch_assoc()) {
    fputcsv($output, [
        $customer['id'],
        $customer['name'],
        $customer['email'],
        $customer['phone'] ?? '',
        $customer['street'] ?? '',
        $customer['number'] ?? '',
        $customer['zipcode'] ?? '',
        $customer['city'] ?? '',
        $customer['country'] ?? '',
        date('d-m-Y', strtotime($customer['created_at'])),
        $customer['order_count'],
        number_format($customer['total_spent'], 2, ',', '.')
    ], ';');
}

fclose($output);
exit;
