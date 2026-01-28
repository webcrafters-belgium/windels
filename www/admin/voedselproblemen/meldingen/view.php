<?php


$web = $_SERVER['PHP_SELF'];
$usernameadmin = $_SESSION['admin_username'];
$admin_id = $_SESSION['admin_id'];

$secondsWait = 3600;
header("refresh:$secondsWait; /loginout.php?web=$web&adminuser=$usernameadmin");

require $_SERVER["DOCUMENT_ROOT"] . '/ini.inc';
require $_SERVER["DOCUMENT_ROOT"] . '/header.php';
require $_SERVER["DOCUMENT_ROOT"] . '/voedselproblemen/templates/header.php';

$favv_dossier = isset($_GET['favv_dossier']) ? $_GET['favv_dossier'] : null;
$favv_dossiernummer = isset($_GET['favv_dossiernummer']) ? $_GET['favv_dossiernummer'] : null;
?>
<div class="container mt5">
    <div class="card">
        <div class="card-header bg-secondary text-white">
            <h3>Alle Meldingen</h3>
        </div>
        <div class="card-body">
            <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success">
                    Melding succesvol toegevoegd! Zorg ervoor dat u het dossier zorgvuldig behandelt voor de veiligheid van ons voedsel. Volg de onderstaande stappen om het dossier af te handelen:
                    <ol>
                        <li>Druk de melding af en voeg deze toe aan de documentatie van de klant.</li>
                        <li>Maak een aparte map aan voor elke klant om een georganiseerde administratie bij te houden.</li>
                        <li>Controleer of alle relevante documenten zijn meegeleverd.</li>
                        <li>Volg de procedures voor het afhandelen van voedselveiligheidsproblemen zoals voorgeschreven door het FAVV.</li>
                        <li>Houd de status van het dossier bij en werk deze bij wanneer nodig.</li>
                        <li>Informeer de klant over de voortgang en uitkomst van het onderzoek.</li>
                    </ol>
                    <?php if ($favv_dossier): ?>
                        <br>FAVV-dossier is gemaakt met dossiernummer: <?php echo htmlspecialchars($favv_dossiernummer); ?>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            <div class="alert alert-warning">
                Nadat u een melding heeft gemaakt, druk deze dan af en voeg deze toe aan de documentatie van de klant. Maak voor elke klant een aparte map aan voor een georganiseerde administratie.
            </div>
            <form id="search-form" class="form-inline mb-3">
                <input type="text" id="search" name="search" class="form-control mr-sm-2" placeholder="Zoeken..." value="">
                <button type="submit" class="btn btn-outline-success">Zoeken</button>
            </form>
            <div id="result" class="table-responsive"></div>
            <a href="javascript:history.back()" class="btn btn-primary mt-3">Terug</a>
            <a href="../index.php" class="btn btn-secondary mt-3">Home</a>
        </div>
    </div>
</div>
<script>
const adminId = <?php echo json_encode($admin_id); ?>;

function fetchMeldingen() {
    const search = document.getElementById('search').value;
    fetch(`get_meldingen.php?search=${encodeURIComponent(search)}`)
        .then(response => response.json())
        .then(data => {
            if (data.length === 0) {
                document.getElementById('result').innerHTML = '<div class="alert alert-info">Er zijn geen meldingen geregistreerd. Goed bezig! 👍</div>';
                return;
            }
            const table = document.createElement('table');
            table.classList.add('table', 'table-striped', 'table-bordered');
            table.innerHTML = `
                <thead>
                    <tr>
                        <th>Dossiernummer</th>
                        <th>Datum Melding</th>
                        <th>Naam Klant</th>
                        <th>Productnaam</th>
                        <th>Gezondheidsklachten</th>
                        <th>Status</th>
                        <th>FAVV Dossier</th>
                        <th>Acties</th>
                    </tr>
                </thead>
                <tbody>
                    ${data.map(row => `
                        <tr ${row.gezondheidsklachten === 'Ja' ? 'class="table-danger"' : ''}>
                            <td>${row.dossiernummer}</td>
                            <td>${row.datum_melding}</td>
                            <td>${row.naam_klant}</td>
                            <td>${row.productnaam}</td>
                            <td>${row.gezondheidsklachten === 'Ja' ? '<span class="badge badge-danger">Ja</span>' : 'Nee'}</td>
                            <td>${row.status === 'Afgerond' ? '<span class="badge badge-success">Afgerond</span>' : (row.status === 'In behandeling' ? '<span class="badge badge-warning">In behandeling</span>' : row.status)}</td>
                            <td>${row.favv_dossier == 1 ? `Ja${row.favv_dossiernummer ? ` (${row.favv_dossiernummer})` : ''}` : 'Nee'}</td>
                            <td>
                                <a href="details.php?id=${row.id}" class="btn btn-info btn-sm">Details</a>
                                ${row.status !== 'Afgerond' && row.locked_by === null ? `<a href="edit.php?id=${row.id}" class="btn btn-warning btn-sm">Bewerken</a>` : row.status === 'Afgerond' ? `<button class="btn btn-warning btn-sm" onclick="alert('Dit dossier is afgerond en kan niet meer bewerkt worden.');" disabled>Bewerken</button>` : `<button class="btn btn-warning btn-sm" onclick="alert('Deze melding is momenteel in bewerking door een andere medewerker.');" disabled>Bewerken</button><span class="badge badge-secondary">🔒</span>`}
                                ${adminId <= 1 ? `<a href="delete.php?id=${row.id}" class="btn btn-danger btn-sm" onclick="return confirm('Weet je zeker dat je deze melding wilt verwijderen?');">Verwijderen</a>` : ''}
                            </td>
                        </tr>
                    `).join('')}
                </tbody>
            `;
            document.getElementById('result').innerHTML = '';
            document.getElementById('result').appendChild(table);
        })
        .catch(error => console.error('Error:', error));
}

document.getElementById('search-form').addEventListener('submit', function(event) {
    event.preventDefault();
    fetchMeldingen();
});

fetchMeldingen();
setInterval(fetchMeldingen, 5000); // Ververs elke 5 seconden
</script>

<?php
require $_SERVER["DOCUMENT_ROOT"] . '/footer.php';
?>
