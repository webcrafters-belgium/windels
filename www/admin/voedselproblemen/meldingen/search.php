<?php
require $_SERVER["DOCUMENT_ROOT"] . '/ini.inc';

$search = '';
if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $sql = "SELECT id, dossiernummer, datum_melding, naam_klant, productnaam, gezondheidsklachten, status, locked_by FROM meldingen WHERE naam_klant LIKE '%$search%' OR email_klant LIKE '%$search%' OR productnaam LIKE '%$search%' OR dossiernummer LIKE '%$search%'";
} else {
    $sql = "SELECT id, dossiernummer, datum_melding, naam_klant, productnaam, gezondheidsklachten, status, locked_by FROM meldingen";
}

$result = $pdo_voedselproblemen->query($sql);

if ($result->num_rows > 0): ?>
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>Dossiernummer</th>
                <th>Datum Melding</th>
                <th>Naam Klant</th>
                <th>Productnaam</th>
                <th>Gezondheidsklachten</th>
                <th>Status</th>
                <th>Acties</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr <?php if ($row['gezondheidsklachten'] == 'Ja') echo 'class="table-danger"'; ?>>
                    <td><?php echo $row['dossiernummer']; ?></td>
                    <td><?php echo $row['datum_melding']; ?></td>
                    <td><?php echo $row['naam_klant']; ?></td>
                    <td><?php echo $row['productnaam']; ?></td>
                    <td><?php echo $row['gezondheidsklachten'] == 'Ja' ? '<span class="badge badge-danger">Ja</span>' : 'Nee'; ?></td>
                    <td><?php echo $row['status'] == 'Afgerond' ? '<span class="badge badge-success">Afgerond</span>' : ($row['status'] == 'In behandeling' ? '<span class="badge badge-warning">In behandeling</span>' : $row['status']); ?></td>
                    <td>
                        <a href="details.php?id=<?php echo $row['id']; ?>" class="btn btn-info btn-sm">Details</a>
                        <?php if ($row['status'] != 'Afgerond' && $row['locked_by'] == NULL): ?>
                            <a href="edit.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">Bewerken</a>
                        <?php elseif ($row['status'] == 'Afgerond'): ?>
                            <button class="btn btn-warning btn-sm" onclick="alert('Dit dossier is afgerond en kan niet meer bewerkt worden.');" disabled>Bewerken</button>
                        <?php else: ?>
                            <button class="btn btn-warning btn-sm" onclick="alert('Deze melding is momenteel in bewerking door een andere medewerker.');" disabled>Bewerken</button>
                            <span class="badge badge-secondary">🔒</span>
                        <?php endif; ?>
                        <?php if ($_SESSION['admin_id'] <= 1): ?>
                            <a href="delete.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Weet je zeker dat je deze melding wilt verwijderen?');">Verwijderen</a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
<?php else: ?>
    <div class="alert alert-info">Geen meldingen gevonden.</div>
<?php endif; ?>
