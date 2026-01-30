<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/admin/includes/header.php'; ?>
<h2>📥 Klanten importeren</h2>
<form action="import_csv.php" method="POST" enctype="multipart/form-data">
    <input type="file" name="csv_file" required>
    <button type="submit">Importeer CSV</button>
</form>
<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/admin/includes/footer.php'; ?>
