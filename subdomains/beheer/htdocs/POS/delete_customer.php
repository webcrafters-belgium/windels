<?php
require_once $_SERVER["DOCUMENT_ROOT"] . '/ini.inc';
//require_once $_SERVER["DOCUMENT_ROOT"] . '/authenticatelogg.php';
require_once $_SERVER["DOCUMENT_ROOT"] . '/POS/LoyverseClient.php';

$api_token = '56f733c909bf417f98a0ff88b1f3a983';
$loyverse = new LoyverseClient($api_token);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['customer_id'])) {
    $customer_id = $_POST['customer_id'];

    try {
        $loyverse->deleteCustomer($customer_id);
        echo "<div class='alert alert-success'>Klant succesvol verwijderd.</div>";
        header("Location: klantenbestand.php"); // Terug naar de lijst
        exit;
    } catch (Exception $e) {
        echo "<div class='alert alert-danger'>Fout bij het verwijderen van de klant: " . $e->getMessage() . "</div>";
    }
} else {
    echo "<div class='alert alert-danger'>Ongeldig verzoek.</div>";
}
?>
