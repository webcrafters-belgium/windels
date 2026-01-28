<?php
session_start();
if (!isset($_SESSION['partner_id'])) {
    header("Location: ../login.php");
    exit;
}

require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/config/database.php';

$partner_id = $_SESSION['partner_id'];
$upload_dir = $_SERVER['DOCUMENT_ROOT'] . '/uploads/logos/';
$success = '';
$error = '';

// Zorg dat de map bestaat
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0775, true);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['logo'])) {
    $file = $_FILES['logo'];
    $allowed = ['image/jpg', 'image/jpeg', 'image/png', 'image/svg+xml'];

    if ($file['error'] === 0 && in_array($file['type'], $allowed) && $file['size'] < 2 * 1024 * 1024) {
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = 'logo_' . $partner_id . '.' . $ext;
        $target_path = $upload_dir . $filename;

        if (move_uploaded_file($file['tmp_name'], $target_path)) {
            $stmt = $pdo->prepare("UPDATE funeral_partners SET logo = ? WHERE id = ?");
            $stmt->execute([$filename, $partner_id]);
            $success = "Logo succesvol geüpload.";
        } else {
            $error = "Uploaden is mislukt. Probeer opnieuw.";
        }
    } else {
        $error = "Ongeldig bestandstype of bestand te groot.";
    }
}

$stmt = $pdo->prepare("SELECT logo FROM funeral_partners WHERE id = ?");
$stmt->execute([$partner_id]);
$current_logo = $stmt->fetchColumn();
?>

<?php include dirname($_SERVER['DOCUMENT_ROOT']).'/partials/header.php'; ?>

<main class="container profiel-pagina">
    <h1>Bedrijfslogo Uploaden</h1>

    <?php if ($success): ?>
        <p class="alert success"><?= htmlspecialchars($success) ?></p>
    <?php elseif ($error): ?>
        <p class="alert error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <?php if ($current_logo): ?>
        <p>Huidig logo:</p>
        <img src="/uploads/logos/<?= htmlspecialchars($current_logo) ?>" alt="Bedrijfslogo" style="max-width: 200px; height: auto; margin-bottom: 1rem;">
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data" class="simple-form">
        <label for="logo">Kies een nieuw logo (JPG, JPEG, PNG, SVG – max 2MB)</label>
        <input type="file" name="logo" accept=".jpg,.jpeg,.png,.svg" required>

        <button type="submit" class="btn">Uploaden</button>
    </form>

    <p><a href="index.php">&larr; Terug naar Accountbeheer</a></p>
</main>

<?php include dirname($_SERVER['DOCUMENT_ROOT']).'/partials/footer.php'; ?>
