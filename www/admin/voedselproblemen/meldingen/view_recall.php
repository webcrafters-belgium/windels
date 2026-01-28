<?php


$web = $_SERVER['PHP_SELF'];
$usernameadmin = $_SESSION['admin_username'];
$admin_id = $_SESSION['admin_id'];

$secondsWait = 3600;
header("refresh:$secondsWait; /loginout.php?web=$web&adminuser=$usernameadmin");

require $_SERVER["DOCUMENT_ROOT"] . '/ini.inc';
require $_SERVER["DOCUMENT_ROOT"] . '/header.php';
require $_SERVER["DOCUMENT_ROOT"] . '/voedselproblemen/templates/header.php';
?>
<div class="card">
    <div class="card-header bg-primary text-white">
        <h3>Terugroepacties Overzicht</h3>
    </div>
    <div class="card-body">
        <?php
        $sql = "SELECT * FROM terugroepacties";
        $stmt = $pdo_voedselproblemen->query($sql);

        if ($stmt->rowCount() > 0) {
            echo '<table class="table table-striped table-bordered">';
            echo '<thead><tr><th>Dossiernummer</th><th>Productnaam</th><th>Klacht</th><th>Datum Terugroepactie</th><th>Status</th><th>Acties</th></tr></thead>';
            echo '<tbody>';
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo '<tr>';
                echo '<td>' . $row['dossiernummer'] . '</td>';
                echo '<td>' . $row['productnaam'] . '</td>';
                echo '<td>' . $row['klacht'] . '</td>';
                echo '<td>' . $row['datum_terugroepactie'] . '</td>';
                echo '<td>' . $row['status'] . '</td>';
                echo '<td>';
                echo '<a href="details_recall.php?id=' . $row['id'] . '" class="btn btn-info btn-sm">Details</a> ';
                echo '<a href="edit_recall.php?id=' . $row['id'] . '" class="btn btn-warning btn-sm">Bewerken</a> ';
                echo '<a href="delete_recall.php?id=' . $row['id'] . '" class="btn btn-danger btn-sm" onclick="return confirm(\'Weet je zeker dat je deze terugroepactie wilt verwijderen?\');">Verwijderen</a>';
                echo '</td>';
                echo '</tr>';
            }
            echo '</tbody></table>';
        } else {
            echo '<div class="alert alert-info">Er zijn geen terugroepacties geregistreerd. Goed bezig! 👍</div>';
        }
        ?>
        <a href="add_recall.php" class="btn btn-success mt-3">Nieuwe Terugroepactie</a>
        <a href="../index.php" class="btn btn-secondary mt-3">Home</a>
    </div>
</div>

<?php require $_SERVER["DOCUMENT_ROOT"] . '/footer.php'; ?>
