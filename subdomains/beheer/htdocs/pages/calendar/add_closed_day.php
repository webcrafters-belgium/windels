<?php
include $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';

$pagetitle = "Sluitingsdagen Toevoegen";

// Als het formulier is verzonden
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $startDate = $conn->real_escape_string($_POST['start_date']);
    $endDate = !empty($_POST['end_date']) ? $conn->real_escape_string($_POST['end_date']) : $startDate;
    $reason = $conn->real_escape_string($_POST['reason']);

    // Voeg data toe aan de database
    $query = "INSERT INTO closed_days (closed_date, reason) VALUES (?, ?)";
    $stmt = $conn->prepare($query);

    $currentDate = $startDate;
    while (strtotime($currentDate) <= strtotime($endDate)) {
        $stmt->bind_param("ss", $currentDate, $reason);
        if (!$stmt->execute()) {
            echo "Error: " . $conn->error;
        }
        $currentDate = date('Y-m-d', strtotime($currentDate . ' +1 day'));
    }

    header('Location: /pages/calendar/add_closed_day.php?success=1');
}

include $_SERVER['DOCUMENT_ROOT'] . '/header.php';
?>

<div class="container-fluid w-50">
    <div class="card shadow">
        <h2>Sluitingsdagen Toevoegen</h2>
        <form method="post">
            <div class="mb-3">
                <label for="start_date" class="form-label">Startdatum</label>
                <input type="date" id="start_date" name="start_date" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="end_date" class="form-label">Einddatum (optioneel)</label>
                <input type="date" id="end_date" name="end_date" class="form-control">
                <small class="text-muted">Laat leeg als het om één dag gaat.</small>
            </div>
            <div class="mb-3">
                <label for="reason" class="form-label">Reden (optioneel)</label>
                <input type="text" id="reason" name="reason" class="form-control" placeholder="Bijvoorbeeld: Feestdag">
            </div>
            <button type="submit" class="btn btn-primary">Opslaan</button>
        </form>
    </div>

    <?php
    // Succesbericht
    if (isset($_GET['success']) && $_GET['success'] == 1) {
        echo '<div class="alert alert-success mt-3">Sluitingsdagen succesvol toegevoegd!</div>';
    }
    ?>

    <div class="card shadow mt-4">
        <h3>Bestaande Sluitingsdagen</h3>
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>Datum</th>
                <th>Reden</th>
                <th>Acties</th>
            </tr>
            </thead>
            <tbody>
            <?php
            // Haal bestaande sluitingsdagen op
            $query = "SELECT id, closed_date, reason FROM closed_days ORDER BY closed_date ASC";
            $result = $conn->query($query);

            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . date('d-m-Y', strtotime($row['closed_date'])) . "</td>";
                    echo "<td>" . htmlspecialchars($row['reason']) . "</td>";
                    echo "<td>
                                <a href='/pages/calendar/edit_closed_day.php?id=" . $row['id'] . "' class='btn btn-warning'>Bewerken</a>
                                <a href='/pages/calendar/delete_closed_day.php?id=" . $row['id'] . "' class='btn btn-danger'>Verwijderen</a>
                              </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='3'>Geen sluitingsdagen gevonden.</td></tr>";
            }
            ?>
            </tbody>
        </table>
    </div>
</div>

<?php
include $_SERVER['DOCUMENT_ROOT'] . '/footer.php';
?>
