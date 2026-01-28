<?php
include $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';

$pagetitle="Openingstijden Toevoegen";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $dayOfWeek = $conn->real_escape_string($_POST['day_of_week']);
    $openingTime = $conn->real_escape_string($_POST['opening_time']);
    $closingTime = $conn->real_escape_string($_POST['closing_time']);

    $query = "INSERT INTO opening_hours (day_of_week, opening_time, closing_time) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sss", $dayOfWeek, $openingTime, $closingTime);

    if ($stmt->execute()) {
        header('Location: /pages/calendar/opening_hours.php?success=1');
    } else {
        echo "Error: " . $conn->error;
    }
}

include $_SERVER['DOCUMENT_ROOT'] . '/header.php';
?>

<div class="container-fluid w-50">
    <div class="card shadow">
        <h2>Nieuwe Openingstijden Toevoegen</h2>
        <form method="post">
            <div class="mb-3">
                <label for="day_of_week" class="form-label">Dag van de Week</label>
                <select id="day_of_week" name="day_of_week" class="form-select" required>
                    <option value="Maandag">Maandag</option>
                    <option value="Dinsdag">Dinsdag</option>
                    <option value="Woensdag">Woensdag</option>
                    <option value="Donderdag">Donderdag</option>
                    <option value="Vrijdag">Vrijdag</option>
                    <option value="Zaterdag">Zaterdag</option>
                    <option value="Zondag">Zondag</option>
                </select>
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
