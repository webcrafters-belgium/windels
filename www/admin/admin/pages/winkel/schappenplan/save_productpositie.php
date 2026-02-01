<?php
  // Zorg ervoor dat de gebruiker is ingelogd
require $_SERVER["DOCUMENT_ROOT"] . '/ini.inc';  // Verbind met de database

// Controleer of de vereiste gegevens zijn verzonden via een POST-verzoek
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ontvang de gegevens van het AJAX-verzoek
    $product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : null;
    $positie_x = isset($_POST['positie_x']) ? (float)$_POST['positie_x'] : null;
    $plank_nummer = isset($_POST['plank_nummer']) ? (int)$_POST['plank_nummer'] : null;

    // Controleer of alle vereiste gegevens aanwezig zijn
    if ($product_id && $positie_x !== null && $plank_nummer !== null) {
        // Update de positie van het product in de `product_schap` tabel
        $stmt = $pdo_winkel->prepare("UPDATE product_schap SET positie_op_plank = ?, plank_nummer = ? WHERE id = ?");
        $stmt->execute([$positie_x, $plank_nummer, $product_id]);

        // Controleer of het bijwerken is gelukt
        if ($stmt->rowCount() > 0) {
            echo json_encode(['status' => 'success', 'message' => 'Productpositie succesvol bijgewerkt.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Er is een fout opgetreden bij het bijwerken van de productpositie.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Ongeldige invoer.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Ongeldig verzoek.']);
}
?>
