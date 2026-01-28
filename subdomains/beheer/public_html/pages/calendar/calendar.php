<?php
// calendar.php
include $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';


$pagetitle = "Kalender Beheer";

include $_SERVER['DOCUMENT_ROOT'] . '/header.php';
?>

<div class="container-fluid w-75">
    <div class="card shadow">
        <h2>Speciale tijden beheren</h2>
        <p>Hier kun je speciale tijden toevoegen, bewerken, of verwijderen.</p>
        <a href="/pages/calendar/calendar_add.php" class="btn btn-primary">Nieuwe openingsuren Toevoegen</a>
        <hr>
        <h3>Speciale openingsuren</h3>
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>Datum</th>
                <th>Evenement</th>
                <th>Acties</th>
                <th>Openingstijden</th>
            </tr>
            </thead>
            <tbody>
            <?php
            // Query om bestaande evenementen op te halen
            $query = "SELECT id, event_date, event_name FROM calendar_events ORDER BY event_date ASC";
            $result = $conn->query($query);

            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $eventId = $row['id'];

                    // Query om openingstijden voor dit evenement op te halen
                    $timeQuery = "SELECT opening_time, closing_time FROM special_opening_hours WHERE event_id = ?";
                    $timeStmt = $conn->prepare($timeQuery);
                    $timeStmt->bind_param("i", $eventId);
                    $timeStmt->execute();
                    $timeResult = $timeStmt->get_result();

                    $openingTimes = "";
                    if ($timeResult && $timeResult->num_rows > 0) {
                        while ($timeRow = $timeResult->fetch_assoc()) {
                            $openingTimes .= date('H:i', strtotime($timeRow['opening_time'])) . " - " . date('H:i', strtotime($timeRow['closing_time'])) . "<br>";
                        }
                    } else {
                        $openingTimes = "Geen openingstijden ingesteld";
                    }

                    echo "<tr>";
                    echo "<td>" . date('d-m-Y', strtotime($row['event_date'])) . "</td>";
                    echo "<td>" . htmlspecialchars($row['event_name']) . "</td>";
                    echo "<td>
                                <a href='/pages/calendar/calendar_edit.php?id=" . $row['id'] . "' class='btn btn-warning'>Bewerken</a>
                                <a href='/pages/calendar/calendar_delete.php?id=" . $row['id'] . "' class='btn btn-danger'>Verwijderen</a>
                              </td>";
                    echo "<td>" . $openingTimes . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='4'>Geen speciale openingstijden gevonden.</td></tr>";
            }
            ?>
            </tbody>
        </table>
    </div>
    <div class="extra-options">
        <h2 class="mt-5 text-primary">Extra opties</h2>
        <div class="d-flex flex-column w-25">
            <a href="/pages/calendar/add_closed_day.php" class="btn btn-primary mt-1">Beheer sluitingsdagen</a>
            <a href="/pages/calendar/opening_hours.php" class="btn btn-primary mt-1">Beheer openingsuren</a>
        </div>
    </div>
</div>


<?php
include $_SERVER['DOCUMENT_ROOT'] . '/footer.php';
?>
