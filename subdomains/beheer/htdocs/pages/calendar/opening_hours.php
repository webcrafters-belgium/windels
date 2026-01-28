<?php
include $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';
$pagetitle="Openingstijden Toevoegen";

include $_SERVER['DOCUMENT_ROOT'] . '/header.php';

$pagetitle = "Openingstijden Beheer";
?>

<div class="container-fluid w-75">
    <div class="card radius-1">
        <h2 class="mt-5">Openingstijden Beheer</h2>
        <p>Hier kun je de openingstijden beheren.</p>
        <a href="/pages/calendar/opening_hours_add.php" class="btn btn-primary">Nieuwe Openingstijd Toevoegen</a>
        <hr>
        <h3>Bestaande Openingstijden</h3>
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>Dag</th>
                <th>Openingstijd</th>
                <th>Sluitingstijd</th>
                <th>Acties</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $query = "SELECT id, day_of_week, opening_time, closing_time FROM opening_hours ORDER BY FIELD(day_of_week, 'Maandag', 'Dinsdag', 'Woensdag', 'Donderdag', 'Vrijdag', 'Zaterdag', 'Zondag')";
            $result = $conn->query($query);

            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['day_of_week']) . "</td>";
                    echo "<td>" . date('H:i', strtotime($row['opening_time'])) . "</td>";
                    echo "<td>" . date('H:i', strtotime($row['closing_time'])) . "</td>";
                    echo "<td>
                                <a href='/pages/opening_hours_edit.php?id=" . $row['id'] . "' class='btn btn-warning'>Bewerken</a>
                                <a href='/pages/opening_hours_delete.php?id=" . $row['id'] . "' class='btn btn-danger'>Verwijderen</a>
                              </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='4'>Geen openingstijden gevonden.</td></tr>";
            }
            ?>
            </tbody>
        </table>
    </div>
    <div class="extra-options">
        <h2 class="mt-5 text-primary">Extra opties</h2>
        <div>
            <a href="/pages/calendar/add_closed_day.php" class="btn btn-primary">Beheer sluitingsdagen</a>
        </div>
    </div>
</div>

<?php
include $_SERVER['DOCUMENT_ROOT'] . '/footer.php';
?>
