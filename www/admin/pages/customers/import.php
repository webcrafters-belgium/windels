<?php include $_SERVER['DOCUMENT_ROOT'] . '/header.php'; ?>
<h2>📥 Klanten importeren</h2>
<form action="import_csv.php" method="POST" enctype="multipart/form-data">
    <input type="file" name="csv_file" required>
    <button type="submit">Importeer CSV</button>
</form>
<?php include $_SERVER['DOCUMENT_ROOT'] . '/footer.php'; ?>
