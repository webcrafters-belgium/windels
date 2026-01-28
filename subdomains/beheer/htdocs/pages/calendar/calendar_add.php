<?php

include $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';
$pagetitle = "Evenement Toevoegen";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $eventDate = $conn->real_escape_string($_POST['event_date']);
    $eventName = $conn->real_escape_string($_POST['event_name']);
    $openingTime = $conn->real_escape_string($_POST['opening_time']);
    $closingTime = $conn->real_escape_string($_POST['closing_time']);

    // Voeg het evenement toe
    $query = "INSERT INTO calendar_events (event_date, event_name) VALUES (?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $eventDate, $eventName);

    if ($stmt->execute()) {
        $eventId = $stmt->insert_id; // Verkrijg het ID van het nieuwe evenement

        // Voeg de aangepaste openingstijden toe
        $query = "INSERT INTO special_opening_hours (event_id, opening_time, closing_time) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("iss", $eventId, $openingTime, $closingTime);

        if ($stmt->execute()) {
            header('Location: /pages/calendar/calendar.php?success=1');
            exit();
        } else {
            echo "Error bij het opslaan van de openingstijden: " . $conn->error;
        }
    } else {
        echo "Error bij het opslaan van het evenement: " . $conn->error;
    }
}


include $_SERVER['DOCUMENT_ROOT'] . '/header.php';
?>

<div class="container-fluid w-50">
    <div class="card shadow">
        <h2>Nieuw Evenement Toevoegen</h2>
        <form method="post">
            <div class="mb-3">
                <label for="event_date" class="form-label">Datum</label>
                <input type="date" id="event_date" name="event_date" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="event_name" class="form-label">Evenement Naam</label>
                <input type="text" id="event_name" name="event_name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="opening_time" class="form-label">Openingstijd</label>
                <input type="time" id="opening_time" name="opening_time" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="closing_time" class="form-label">Sluitingstijd</label>
                <input type="time" id="closing_time" name="closing_time" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Opslaan</button>
        </form>
    </div>
</div>


<?php
include $_SERVER['DOCUMENT_ROOT'] . '/footer.php';
?>
