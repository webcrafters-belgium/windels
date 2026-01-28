<?php
// Databaseverbinding
include $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';
include $_SERVER['DOCUMENT_ROOT'] . '/header.php';

// Controleer of er een zoekopdracht is
if (isset($_POST['order_id'])) {
    $order_id = $conn->real_escape_string($_POST['order_id']);

    // Query om ordergegevens op te halen
    $query = "SELECT * FROM orders WHERE order_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $order_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $order = $result->fetch_assoc();
        echo "<h3>Bestelstatus voor Order #{$order['order_id']}:</h3>";
        echo "<p>Status: {$order['status']}</p>";
        echo "<p>Tracking Nummer: {$order['tracking_number']}</p>";
        echo "<p>Laatst Bijgewerkt: {$order['updated_at']}</p>";
    } else {
        echo "Geen bestelling gevonden met dat ordernummer.";
    }
}
?>
<form method="post">
    <label for="order_id">Vul uw ordernummer in:</label>
    <input type="text" name="order_id" id="order_id" required>
    <button type="submit">Track Order</button>
</form>


<?php
include $_SERVER['DOCUMENT_ROOT'] . '/header.php';
?>