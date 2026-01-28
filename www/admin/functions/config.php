<?php
$host = "localhost";
$user = "root";
$pass = "Admin050591@";
$charset = 'utf8mb4';

$databases = [
    "company_admin",
    "winkel",
    "magazijn",
    "kantoor",
    "voedselproblemen",
    "kassa",
    "boekhouding"
];

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

try {
    foreach ($databases as $dbname) {
        ${"pdo_" . $dbname} = new PDO("mysql:host=$host;dbname=$dbname;charset=$charset", $user, $pass, $options);
    }
} catch (PDOException $e) {
    echo "Verbindingsfout met database: " . $e->getMessage(); // Specifieke foutmelding
    throw new PDOException($e->getMessage(), (int)$e->getCode());
}
?>
