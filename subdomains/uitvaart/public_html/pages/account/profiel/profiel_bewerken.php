<?php
session_start();
if (!isset($_SESSION['partner_id'])) {
    header("Location: ../login.php");
    exit;
}

require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/secure/ini.inc';

$partner_id = $_SESSION['partner_id'];
$success = '';
$error = '';

// ✅ Verwerk formulier
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bedrijfsnaam = trim($_POST['bedrijfsnaam'] ?? '');
    $adres = trim($_POST['adres'] ?? '');

    if ($bedrijfsnaam !== '' && $adres !== '') {
        $stmt = $mysqli->prepare("UPDATE funeral_partners SET bedrijf_naam = ?, adres = ? WHERE id = ?");
        $stmt->bind_param("ssi", $bedrijfsnaam, $adres, $partner_id);
        if ($stmt->execute()) {
            $success = "Gegevens succesvol bijgewerkt.";
        } else {
            $error = "Er is iets misgegaan bij het opslaan.";
        }
        $stmt->close();
    } else {
        $error = "Gelieve alle velden in te vullen.";
    }
}

// ✅ Haal actuele data op
$stmt = $mysqli->prepare("SELECT bedrijf_naam, adres FROM funeral_partners WHERE id = ?");
$stmt->bind_param('i', $partner_id);
$stmt->execute();
$result = $stmt->get_result();
$partner = $result->fetch_assoc() ?? ['bedrijf_naam' => '', 'adres' => ''];
$stmt->close();
?>


<?php include dirname($_SERVER['DOCUMENT_ROOT']).'/partials/header.php'; ?>
<style>
         body {
    background: url('/img/uitvaartachtergrond.jpg') no-repeat center center fixed;
    background-size: cover;
     }
     .profiel-pagina {
    background-color: rgba(255, 255, 255, 0.9);
    padding: 3rem 2rem;
    border-radius: 12px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    margin: 3rem auto 2rem auto;
}
</style>
<main class="container profiel-pagina">
    <h1>Accountgegevens Wijzigen</h1>

    <?php if ($success): ?>
        <p class="alert success"><?= htmlspecialchars($success) ?></p>
    <?php elseif ($error): ?>
        <p class="alert error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="post" class="simple-form">
        <label>Bedrijfsnaam</label>
        <input type="text" name="bedrijfsnaam" value="<?= htmlspecialchars($partner['bedrijf_naam']) ?>" required>

        <label>Adres</label>
        <textarea type="text" name="adres" required><?= htmlspecialchars($partner['adres']) ?></textarea>

        <button type="submit" class="btn">Opslaan</button>
    </form>

    <p><a href="index.php">&larr; Terug naar Accountbeheer</a></p>
</main>

<?php include dirname($_SERVER['DOCUMENT_ROOT']).'/partials/footer.php'; ?>
