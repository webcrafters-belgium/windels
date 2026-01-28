<?php
include $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';
include $_SERVER['DOCUMENT_ROOT'] . '/header.php';

// Klanten ophalen (gekoppeld op e-mail)
$stmt = $conn->prepare("SELECT u.id, u.first_name, u.last_name, u.email, u.phone, u.created_at,
  (SELECT COUNT(*) FROM orders o WHERE o.email = u.email) as aantal_bestellingen
  FROM users u
  WHERE u.role IN ('customer', 'admin')
  ORDER BY u.created_at DESC");

$stmt->execute();
$result = $stmt->get_result();
?>
<div class="admin-klanten-pagina">
    <h1>🧍 Klantenoverzicht</h1>
    <div class="zoekbalk">
        <input type="text" id="zoek-input" placeholder="Zoek op naam of e-mail...">
    </div>

    <div class="import-customers">
        <div class="import-customer">
            <input type="file" id="csv_file" name="csv_file" accept=".csv" style="display: none;">
            <button class="import-customers-button" id="uploadBtn">📥 Importeer klanten (.csv)</button>
        </div>
        <div id="import-feedback" style="margin-top: 1rem;"></div>
    </div>



    <table id="klanten-tabel">
        <thead>
        <tr>
            <th>Naam</th>
            <th>E-mail</th>
            <th>Telefoon</th>
            <th>Aangemaakt op</th>
            <th>Bestellingen</th>
            <th>Acties</th>
        </tr>
        </thead>
        <tbody>
        <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td><?= htmlspecialchars($row['phone'] ?? '') ?></td>
                <td><?= date('d/m/Y', strtotime($row['created_at'])) ?></td>
                <td><?= $row['aantal_bestellingen'] ?></td>
                <td>
                    <a href="/admin/pages/customers/detail.php?id=<?= $row['id'] ?>" class="btn-small">👁 Bekijk</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>

<script>
    document.getElementById('zoek-input').addEventListener('keyup', function () {
        const zoekTerm = this.value.toLowerCase();
        const rows = document.querySelectorAll('#klanten-tabel tbody tr');
        rows.forEach(row => {
            const tekst = row.innerText.toLowerCase();
            row.style.display = tekst.includes(zoekTerm) ? '' : 'none';
        });
    });
</script>

<script>
    document.getElementById('uploadBtn').addEventListener('click', () => {
        document.getElementById('csv_file').click();
    });

    document.getElementById('csv_file').addEventListener('change', function () {
        const file = this.files[0];
        if (!file) return;

        const formData = new FormData();
        formData.append('csv_file', file);

        fetch('/admin/pages/customers/import_csv.php', {
            method: 'POST',
            body: formData
        })
            .then(res => res.text())
            .then(msg => {
                document.getElementById('import-feedback').innerHTML = msg;
            })
            .catch(err => {
                document.getElementById('import-feedback').innerHTML = '❌ Fout bij uploaden.';
            });
    });
</script>


<?php include $_SERVER['DOCUMENT_ROOT'] . '/footer.php'; ?>
