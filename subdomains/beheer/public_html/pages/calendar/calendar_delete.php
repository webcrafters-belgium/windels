<?php
include $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';

// Controleer of de `id` parameter is ingesteld in de URL
if (isset($_GET['id'])) {
    $eventId = intval($_GET['id']); // Zorg ervoor dat het een integer is om SQL-injectie te voorkomen

    // Controleer of het event bestaat
    $query = "SELECT id FROM calendar_events WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $eventId);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Als het event bestaat, verwijder het
        $deleteQuery = "DELETE FROM calendar_events WHERE id = ?";
        $deleteStmt = $conn->prepare($deleteQuery);
        $deleteStmt->bind_param("i", $eventId);

        if ($deleteStmt->execute()) {
            header('Location: /pages/calendar/calendar.php?success=1'); // Redirect naar de kalenderpagina
            exit();
        } else {
            echo "Er is een fout opgetreden bij het verwijderen van de gebeurtenis: " . $conn->error;
        }
        $deleteStmt->close();
    } else {
        echo "De opgegeven gebeurtenis bestaat niet.";
    }

    $stmt->close();
} else {
    echo "Geen geldige ID opgegeven.";
}

// Sluit de databaseverbinding
$conn->close();
?>
