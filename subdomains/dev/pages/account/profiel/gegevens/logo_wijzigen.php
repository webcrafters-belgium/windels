<?php
session_start();
if (!isset($_SESSION['partner_id'])) {
    header("Location: /pages/account/login.php");
    exit;
}

require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/secure/ini.inc';

$partner_id = $_SESSION['partner_id'];
$upload_dir = $_SERVER['DOCUMENT_ROOT'] . '/uploads/logos/';
$success = '';
$error = '';

// Zorg dat de map bestaat
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0775, true);
}

// Verwijder logo als gevraagd


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['logo'])) {
    $file = $_FILES['logo'];
    $allowed = ['image/jpeg', 'image/png', 'image/svg+xml'];

    if ($file['error'] === 0 && in_array($file['type'], $allowed) && $file['size'] < 2 * 1024 * 1024) {
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = 'logo_' . $partner_id . '.' . $ext;
        $target_path = $upload_dir . $filename;

        // Verwijder eventuele oude versies
        foreach (['jpg', 'jpeg', 'png', 'svg'] as $old_ext) {
            $old_file = $upload_dir . 'logo_' . $partner_id . '.' . $old_ext;
            if (file_exists($old_file)) {
                unlink($old_file);
            }
        }

        if (move_uploaded_file($file['tmp_name'], $target_path)) {
            $stmt = $mysqli->prepare("UPDATE funeral_partners SET logo = ? WHERE id = ?");
            $stmt->bind_param('si', $filename, $partner_id);
            $stmt->execute();
            $success = "Logo succesvol gewijzigd.";
        } else {
            $error = "Uploaden is mislukt. Probeer opnieuw.";
        }
    } else {
        $error = "Ongeldig bestandstype of bestand te groot.";
    }
}

// Logo ophalen
$stmt = $mysqli->prepare("SELECT logo FROM funeral_partners WHERE id = ?");
$stmt->bind_param('i', $partner_id);
$stmt->execute();
$result = $stmt->get_result();
$current_logo = null;
if ($row = $result->fetch_assoc()) {
    $current_logo = $row['logo'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['verwijder_logo'])) {
    if ($current_logo) {
        $bestand = $upload_dir . $current_logo;
        if (file_exists($bestand)) {
            unlink($bestand);
        }

        $stmt = $mysqli->prepare("UPDATE funeral_partners SET logo = NULL WHERE id = ?");
        $stmt->bind_param('i', $partner_id);
        $stmt->execute();

        $success = "Logo succesvol verwijderd.";
        $current_logo = null;
    } else {
        $error = "Er is geen logo om te verwijderen.";
    }
}

?>

<?php include dirname($_SERVER['DOCUMENT_ROOT']) . '/partials/header.php'; ?>
<style>

         body {
    background: url('/img/uitvaartachtergrond.jpg') no-repeat center center fixed;
    background-size: cover;
     }
     .dashboard-page {
    background-color: rgba(255, 255, 255, 0.9);
    padding: 3rem 2rem;
    border-radius: 12px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    margin: 3rem auto 2rem auto;
}
</style>

<main class="dashboard-page container">
    <div class="dashboard-welcome">
        <h1>Bedrijfslogo Wijzigen</h1>
        <p>Upload hier een nieuw bedrijfslogo dat gebruikt kan worden op facturen en toekomstige documenten.</p>
    </div>

    <?php if ($success): ?>
        <p class="alert success"><?= htmlspecialchars($success) ?></p>
    <?php elseif ($error): ?>
        <p class="alert error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <div class="dashboard-actions">
        <div class="dashboard-card">
            <h2>Nieuw logo uploaden</h2>
            <?php if ($current_logo): ?>
                <p>Huidig logo:</p>
                <img src="/uploads/logos/<?= htmlspecialchars($current_logo) ?>" alt="Bedrijfslogo" style="max-width: 200px; height: auto; margin-bottom: 1rem; border: 1px solid #ccc; padding: 5px; background: #fff;">

                <form method="post" onsubmit="return confirm('Weet je zeker dat je het logo wilt verwijderen?');" style="margin-top: 1rem;">
                    <button type="submit" name="verwijder_logo" value="1" class="btn btn-remove">Verwijder logo</button>
                </form>
            <?php else: ?>

                <p>Er is nog geen logo geüpload.</p>
            <?php endif; ?>

            <form method="post" enctype="multipart/form-data" class="simple-form">
                <label for="logo">Kies een nieuw logo (JPG, PNG, SVG – max 2MB)</label>
                <input type="file" name="logo" accept=".jpg,.jpeg,.png,.svg" required>

                <button type="submit" class="btn">Logo wijzigen</button>
            </form>
        </div>

        <div class="dashboard-card">
            <h2>Terug naar Accountbeheer</h2>
            <p>Keer terug naar je profielinstellingen.</p>
            <a href="index.php" class="btn">← Terug</a>
        </div>
    </div>
</main>

<?php include dirname($_SERVER['DOCUMENT_ROOT']) . '/partials/footer.php'; ?>
