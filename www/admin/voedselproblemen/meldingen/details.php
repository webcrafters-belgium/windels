<?php


$web = $_SERVER['PHP_SELF'];
$usernameadmin = $_SESSION['admin_username'];
$admin_id = $_SESSION['admin_id'];

$secondsWait = 3600;
header("refresh:$secondsWait; /loginout.php?web=$web&adminuser=$usernameadmin");

require $_SERVER["DOCUMENT_ROOT"] . '/ini.inc';
require $_SERVER["DOCUMENT_ROOT"] . '/header.php';
require $_SERVER["DOCUMENT_ROOT"] . '/voedselproblemen/templates/header.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT m.*, med.username AS laatste_bewerking_medewerker FROM meldingen m LEFT JOIN medewerkers med ON m.laatste_bewerking_medewerker_id = med.id WHERE m.id = $id";
    $result = $pdo_voedselproblemen->query($sql);
    $row = $result->fetch_assoc();

    $notities_sql = "SELECT n.*, med.username FROM notities n LEFT JOIN medewerkers med ON n.medewerker_id = med.id WHERE melding_id = $id ORDER BY datum_tijd DESC";
    $notities_result = $pdo_voedselproblemen->query($notities_sql);

    $bewerkingsgeschiedenis_sql = "SELECT b.*, med.username FROM bewerkingsgeschiedenis b LEFT JOIN medewerkers med ON b.medewerker_id = med.id WHERE b.melding_id = $id ORDER BY b.datum_tijd DESC";
    $bewerkingsgeschiedenis_result = $pdo_voedselproblemen->query($bewerkingsgeschiedenis_sql);

    $dossiernummer = $row['dossiernummer'];
    $favv_dossiernummer = $row['favv_dossiernummer'];

    $recall_sql = "SELECT * FROM terugroepacties WHERE dossiernummer = '$dossiernummer'";
    $recall_result = $pdo_voedselproblemen->query($recall_sql);
} else {
    header("Location: view.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['notitie'])) {
    $notitie = $_POST['notitie'];
    $melding_id = $_POST['melding_id'];
    $medewerker_id = $_SESSION['admin_id'];

    $sql = "INSERT INTO notities (melding_id, medewerker_id, notitie) VALUES ('$melding_id', '$medewerker_id', '$notitie')";
    if ($pdo_voedselproblemen->query($sql) === TRUE) {
        header("Location: details.php?id=$melding_id");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<div class="card">
    <div class="card-header bg-info text-white">
        <h3>Melding Details</h3>
    </div>
    <div class="card-body" id="printableArea">
        <table class="table table-bordered">
            <tr>
                <th>Dossiernummer</th>
                <td><?php echo $row['dossiernummer']; ?></td>
            </tr>
            <tr>
                <th>Datum Melding</th>
                <td><?php echo $row['datum_melding']; ?></td>
            </tr>
            <tr>
                <th>Naam Klant</th>
                <td><?php echo $row['naam_klant']; ?></td>
            </tr>
            <tr>
                <th>E-mail Klant</th>
                <td><?php echo $row['email_klant']; ?></td>
            </tr>
            <tr>
                <th>Productnaam</th>
                <td><?php echo $row['productnaam']; ?></td>
            </tr>
            <tr>
                <th>Probleem</th>
                <td><?php echo $row['probleem']; ?></td>
            </tr>
            <tr>
                <th>Gezondheidsklachten</th>
                <td><?php echo $row['gezondheidsklachten'] == 'Ja' ? '<span class="badge badge-danger">Ja</span>' : 'Nee'; ?></td>
            </tr>
            <tr>
                <th>Beschrijving</th>
                <td><?php echo $row['beschrijving']; ?></td>
            </tr>
            <tr>
                <th>Batchnummer</th>
                <td><?php echo $row['batchnummer']; ?></td>
            </tr>
            <tr>
                <th>Aankoopdatum</th>
                <td><?php echo $row['aankoopdatum']; ?></td>
            </tr>
            <tr>
                <th>Houdbaarheidsdatum</th>
                <td><?php echo $row['houdbaarheidsdatum']; ?></td>
            </tr>
            <tr>
                <th>Documenten Meegeleverd</th>
                <td><?php echo $row['documenten_meegeleverd']; ?></td>
            </tr>
            <tr>
                <th>Status</th>
                <td><?php echo $row['status'] == 'Afgerond' ? '<span class="badge badge-success">Afgerond</span>' : ($row['status'] == 'In behandeling' ? '<span class="badge badge-warning">In behandeling</span>' : $row['status']); ?></td>
            </tr>
            <tr>
                <th>FAVV Dossier Gemaakt</th>
                <td><?php echo $row['favv_dossier'] ? 'Ja' : 'Nee'; ?></td>
            </tr>
            <?php if ($row['favv_dossier']): ?>
            <tr>
                <th>FAVV Dossiernummer</th>
                <td><?php echo $row['favv_dossiernummer']; ?></td>
            </tr>
            <?php endif; ?>
        </table>
        <!-- Notities sectie -->
        <h2>Notities</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Datum/Tijd</th>
                    <th>Medewerker</th>
                    <th>Notitie</th>
                </tr>
            </thead>
            <tbody>
                <?php if($notities_result->num_rows > 0){
                    while($notitie = $notities_result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $notitie['datum_tijd']; ?></td>
                        <td><?php echo $notitie['username']; ?></td>
                        <td><?php echo $notitie['notitie']; ?></td>
                    </tr>
                <?php endwhile; 
                } else {
                    echo '<tr><td colspan="3"><div class="alert alert-info">Er zijn geen notisies geregistreerd voor deze melding.</div></td></tr>';
                }?>
            </tbody>
        </table>

        <form action="details.php?id=<?php echo $id; ?>" method="post">
            <div class="form-group no-print">
                <label class="no-print" for="notitie">Nieuwe Notitie</label>
                <textarea class="form-control no-print" id="notitie" name="notitie" rows="3" required></textarea>
            </div>
            <input class="no-print" type="hidden" name="melding_id" value="<?php echo $id; ?>">
            <button type="submit" class="btn btn-primary no-print">Toevoegen</button>
        </form>

        <h2>Terugroepacties</h2>
        <?php
        if ($recall_result->num_rows > 0) {
            while ($recall_row = $recall_result->fetch_assoc()) {
                echo '<div class="card mt-3">';
                echo '<div class="card-header bg-warning text-dark"><h4>Terugroepactie</h4></div>';
                echo '<div class="card-body">';
                echo '<p><strong>Productnaam:</strong> ' . $recall_row['productnaam'] . '</p>';
                echo '<p><strong>Klacht:</strong> ' . $recall_row['klacht'] . '</p>';
                echo '<p><strong>Klachtinformatie:</strong> ' . $recall_row['klachtinformatie'] . '</p>';
                echo '<p><strong>FAVV Dossiernummer:</strong> ' . $recall_row['favv_dossiernummer'] . '</p>';
                echo '<p><strong>FAVV Klachtinformatie:</strong> ' . $recall_row['favv_klachtinformatie'] . '</p>';
                echo '<p><strong>Datum Terugroepactie:</strong> ' . $recall_row['datum_terugroepactie'] . '</p>';
                echo '<p><strong>Status:</strong> ' . $recall_row['status'] . '</p>';
                echo '<a href="delete_recall.php?id=' . $recall_row['id'] . '" class="btn btn-danger mt-3" onclick="return confirm(\'Weet je zeker dat je deze terugroepactie wilt verwijderen?\');">Verwijder Terugroepactie</a>';
                echo '</div>';
                echo '</div>';
            }
        } else {
            echo '<div class="alert alert-info">Er zijn geen terugroepacties geregistreerd voor deze melding.</div>';
        }
        ?>
        <?php if ($row['status'] != 'Afgerond' && $row['locked_by'] == NULL): ?>
           <a href="add_recall.php?dossiernummer=<?php echo $dossiernummer; ?>&favv_dossiernummer=<?php echo $favv_dossiernummer; ?>" class="btn btn-danger no-print">Start Terugroepactie</a>
    
        <?php elseif ($row['status'] == 'Afgerond'): ?>
           <button class="btn btn-danger no-print" onclick="alert('Dit dossier is afgerond en kan niet meer bewerkt worden.');" disabled>Start Terugroepactie</button>
    
            <?php else: ?>
           <button  class="btn btn-danger no-print" onclick="alert('Deze melding is momenteel in bewerking door een andere medewerker.');" disabled>Start Terugroepactie</button>
            <span class="badge badge-secondary no-print">🔒</span>
        <?php endif; ?>
        <!-- Bewerking Geschiedenis sectie -->
        <h2>Bewerking Geschiedenis</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Datum/Tijd</th>
                    <th>Medewerker</th>
                    <th>Veld</th>
                    <th>Oude Waarde</th>
                    <th>Nieuwe Waarde</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                if ($bewerkingsgeschiedenis_result->num_rows > 0) {
                    while($bewerking = $bewerkingsgeschiedenis_result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $bewerking['datum_tijd']; ?></td>
                        <td><?php echo $bewerking['username']; ?></td>
                        <td><?php echo $bewerking['veld']; ?></td>
                        <td><?php echo $bewerking['oude_waarde']; ?></td>
                        <td><?php echo $bewerking['nieuwe_waarde']; ?></td>
                    </tr>
                <?php endwhile; 
                } else {
                    echo '<tr><td colspan="5"><div class="alert alert-info">Er zijn geen bewerkingen geregistreerd voor deze melding.</div></td></tr>';
                }?>
            </tbody>
        </table>

        <a href="view.php" class="btn btn-primary no-print">Terug naar Overzicht</a>
        <?php if ($row['status'] != 'Afgerond' && $row['locked_by'] == NULL): ?>
            <a href="edit.php?id=<?php echo $row['id']; ?>" class="btn btn-warning no-print">Bewerken</a>
            <?php if ($admin_id <= 1): ?>
                <a href="https://www.favv-afsca.be/home/Default.asp?dossiernummer=<?php echo $favv_dossiernummer; ?>" target="_blank" class="btn btn-info mt-3">Link met FAVV</a>
            <?php endif; ?>
            <?php elseif ($row['status'] == 'Afgerond'): ?>
            <button class="btn btn-warning no-print" onclick="alert('Dit dossier is afgerond en kan niet meer bewerkt worden.');" disabled>Bewerken</button>
            <?php if ($admin_id <= 1): ?>
                <button class="btn btn-warning no-print" onclick="alert('Dit dossier is afgerond. Er kan geen melding meer gemaakt worden bij het FAVV');" disabled>Link met FAVV</button>
            <?php endif; ?>
            <?php else: ?>
            <button class="btn btn-warning no-print" onclick="alert('Deze melding is momenteel in bewerking door een andere medewerker.');" disabled>Bewerken</button>
            <?php if ($admin_id <= 1): ?>
                <button class="btn btn-warning no-print" onclick="alert('Dit dossier is afgerond. Er kan geen melding meer gemaakt worden bij het FAVV');" disabled>Link met FAVV</button>
            <?php endif; ?>
            <span class="badge badge-secondary no-print">🔒</span>
        <?php endif; ?>
        <?php if ($admin_id <= 1): ?>
            <a href="delete.php?id=<?php echo $row['id']; ?>" class="btn btn-danger no-print" onclick="return confirm('Weet je zeker dat je deze melding wilt verwijderen?');">Verwijderen</a>
        <?php endif; ?>
        <button onclick="printDiv('printableArea')" class="btn btn-secondary no-print">Afdrukken</button>
        <button onclick="showEmailContent()" class="btn btn-success  no-print">Verstuur E-mail</button>

        <div id="emailContent" style="display:none; margin-top:20px;">
            <h4>Kopieer en plak de onderstaande inhoud in uw Outlook-e-mailprogramma:</h4>
            <textarea class="form-control" rows="10" readonly>
                Beste <?php echo $row['naam_klant']; ?>,
                Bedankt voor je melding. Wij gaan deze nu in behandeling nemen je kan je melding volgen via onze website
                <a href='https://windelsgreen-decoresin.com/meld-punt-voedselproblemen-dossier-inkijken/'>https://windelsgreen-decoresin.com/meld-punt-voedselproblemen-dossier-inkijken/</a>
                Voeg in de zoek balk het dossiernummer in. Dan kan u nu volgen hoe ver we staan met jouw dossier.

                                Dossier Details:
                Dossiernummer: <?php echo $row['dossiernummer']; ?>

                Datum Melding: <?php echo $row['datum_melding']; ?>
                Naam Klant: <?php echo $row['naam_klant']; ?>
                Email Klant: <?php echo $row['email_klant']; ?>
                Productnaam: <?php echo $row['productnaam']; ?>
                Probleem: <?php echo $row['probleem']; ?>
                Gezondheidsklachten: <?php echo $row['gezondheidsklachten']; ?>
                Beschrijving: <?php echo $row['beschrijving']; ?>
                Batchnummer: <?php echo $row['batchnummer']; ?>
                Aankoopdatum: <?php echo $row['aankoopdatum']; ?>
                Houdbaarheidsdatum: <?php echo $row['houdbaarheidsdatum']; ?>
                Documenten Meegeleverd: <?php echo $row['documenten_meegeleverd']; ?>
                Status: <?php echo $row['status']; ?>
                FAVV Dossier: <?php echo $row['favv_dossier'] ? 'Ja' : 'Nee'; ?>
                FAVV Dossiernummer: <?php echo $row['favv_dossiernummer']; ?>

                Uw dossier is aangemaakt en wordt zo snel mogelijk behandeld.
            </textarea>
            <p><strong>Ontvanger:</strong> <?php echo $row['email_klant']; ?></p>
            <p><strong>Onderwerp:</strong> Dossier Aangemaakt - <?php echo $row['dossiernummer']; ?></p>
        </div>
    </div>
</div>

<script>
function showEmailContent() {
    document.getElementById('emailContent').style.display = 'block';
}
</script>
    </div>
</div>

<script>
function printDiv(divName) {
    
    var printContents = document.getElementById(divName).innerHTML;
    var originalContents = document.body.innerHTML;

    var printWindow = window.open('', '', 'height=600,width=800');
    printWindow.document.write('<html><head><title>Afdrukken</title>');
    printWindow.document.write('<style>@media print {.no-print {display: none !important;}.card {border: none;box-shadow: none;}.card-header {background-color: #fff !important;color: #000 !important;}.card-body {padding: 0;}.table {width: 100%;margin-bottom: 1rem;color: #212529;background-color: transparent;}.table-bordered {border: 1px solid #dee2e6;}.table-bordered th,.table-bordered td {border: 1px solid #dee2e6;}.badge {border-radius: 0.2rem;display: inline-block;font-size: 75%;font-weight: 700;line-height: 1;padding: 0.25em 0.4em;text-align: center;vertical-align: baseline;white-space: nowrap;}.badge-danger {background-color: #dc3545;color: #fff;}.badge-success {background-color: #28a745;color: #fff;}.badge-warning {background-color: #ffc107;color: #212529;}.badge-secondary {background-color: #6c757d;color: #fff;}}</style>');
    printWindow.document.write('<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.6.0/css/bootstrap.min.css" rel="stylesheet"><link href="/voedselproblemen/css/styles.css" rel="stylesheet"><link href="/voedselproblemen/bootstrap/css/bootstrap.min.css" rel="stylesheet"><link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&display=swap" rel="stylesheet"></head><body><h1>Melding Details</h1>');
    printWindow.document.write(printContents);
    printWindow.document.write('</body></html>');
    printWindow.document.close();
    printWindow.print();
}
</script>



<?php
require $_SERVER["DOCUMENT_ROOT"] . '/footer.php';
?>
