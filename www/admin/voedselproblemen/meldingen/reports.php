<?php

require $_SERVER["DOCUMENT_ROOT"] . '/header.php';
require $_SERVER["DOCUMENT_ROOT"] . '/voedselproblemen/templates/header.php';
require $_SERVER["DOCUMENT_ROOT"] . '/ini.inc';

$reportType = isset($_POST['reportType']) ? $_POST['reportType'] : '';
$startDate = isset($_POST['startDate']) ? $_POST['startDate'] : '';
$endDate = isset($_POST['endDate']) ? $_POST['endDate'] : '';
$reportData = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($reportType == 'meldingen') {
        $sql = "SELECT * FROM meldingen WHERE datum_melding BETWEEN '$startDate' AND '$endDate'";
    } elseif ($reportType == 'terugroepacties') {
        $sql = "SELECT * FROM terugroepacties WHERE datum_terugroepactie BETWEEN '$startDate' AND '$endDate'";
    }

    $result = $pdo_voedselproblemen->query($sql);
    if ($result && $result->rowcount() > 0) {
        while ($row = $result->fetch_assoc()) {
            $reportData[] = $row;
        }
    }
}
?>

<div class="container mt-4">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h3>Rapporten</h3>
        </div>
        <div class="card-body">
            <form action="reports.php" method="post">
                <div class="form-group">
                    <label for="reportType">Selecteer Rapporttype:</label>
                    <select class="form-control" id="reportType" name="reportType">
                        <option value="meldingen" <?php echo $reportType == 'meldingen' ? 'selected' : ''; ?>>Meldingen</option>
                        <option value="terugroepacties" <?php echo $reportType == 'terugroepacties' ? 'selected' : ''; ?>>Terugroepacties</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="startDate">Startdatum:</label>
                    <input type="date" class="form-control" id="startDate" name="startDate" value="<?php echo $startDate; ?>" required>
                </div>
                <div class="form-group">
                    <label for="endDate">Einddatum:</label>
                    <input type="date" class="form-control" id="endDate" name="endDate" value="<?php echo $endDate; ?>" required>
                </div>
                <button type="submit" class="btn btn-primary">Genereer Rapport</button>
            </form>
        </div>
    </div>
    
    <?php if ($_SERVER["REQUEST_METHOD"] == "POST"): ?>
        <div class="card mt-4">
            <div class="card-header bg-secondary text-white">
                <h3>Rapport Resultaten</h3>
            </div>
            <div class="card-body">
                <?php if (empty($reportData)): ?>
                    <div class="alert alert-info">Er zijn geen gegevens gevonden voor de geselecteerde periode en rapporttype.</div>
                <?php else: ?>
                    <?php if ($reportType == 'meldingen'): ?>
                        <h4>Meldingen van <?php echo $startDate; ?> tot <?php echo $endDate; ?></h4>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Dossiernummer</th>
                                    <th>Datum Melding</th>
                                    <th>Naam Klant</th>
                                    <th>Productnaam</th>
                                    <th>Probleem</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($reportData as $row): ?>
                                    <tr>
                                        <td><?php echo $row['dossiernummer']; ?></td>
                                        <td><?php echo $row['datum_melding']; ?></td>
                                        <td><?php echo $row['naam_klant']; ?></td>
                                        <td><?php echo $row['productnaam']; ?></td>
                                        <td><?php echo $row['probleem']; ?></td>
                                        <td><?php echo $row['status']; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php elseif ($reportType == 'terugroepacties'): ?>
                        <h4>Terugroepacties van <?php echo $startDate; ?> tot <?php echo $endDate; ?></h4>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Dossiernummer</th>
                                    <th>Productnaam</th>
                                    <th>Klacht</th>
                                    <th>Datum Terugroepactie</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($reportData as $row): ?>
                                    <tr>
                                        <td><?php echo $row['id']; ?></td>
                                        <td><?php echo $row['dossiernummer']; ?></td>
                                        <td><?php echo $row['productnaam']; ?></td>
                                        <td><?php echo $row['klacht']; ?></td>
                                        <td><?php echo $row['datum_terugroepactie']; ?></td>
                                        <td><?php echo $row['status']; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php require $_SERVER["DOCUMENT_ROOT"] . '/footer.php'; ?>
