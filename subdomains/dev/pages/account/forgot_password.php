<?php
session_start();
include dirname($_SERVER['DOCUMENT_ROOT']) . '/secure/ini.inc';

$message = '';
$newPassword = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');

    if ($username === '') {
        $message = "Vul je gebruikersnaam in.";
    } else {
        // Kijk of de partner bestaat
        if ($stmt = $mysqli->prepare("SELECT id FROM funeral_partners WHERE username = ? LIMIT 1")) {
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                $stmt->bind_result($partnerId);
                $stmt->fetch();

                // Genereer nieuw wachtwoord (8 tekens)
                $newPassword = substr(bin2hex(random_bytes(4)), 0, 8);

                // Hash opslaan in DB
                $newHash = password_hash($newPassword, PASSWORD_BCRYPT);
                $update = $mysqli->prepare("UPDATE funeral_partners SET password_hash = ? WHERE id = ?");
                $update->bind_param("si", $newHash, $partnerId);
                $update->execute();
                $update->close();

                $message = "Je wachtwoord is gereset. Gebruik dit om in te loggen:";
            } else {
                $message = "Geen partner gevonden met deze gebruikersnaam.";
            }

            $stmt->close();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Wachtwoord opnieuw instellen</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <main class="login-page">
        <div class="login-container">
            <div class="login-card">
                <h1>Wachtwoord herstellen</h1>
                <?php if ($message): ?>
                    <p><?= htmlspecialchars($message) ?></p>
                <?php if ($newPassword): ?>
                    <p style="font-size:1.5rem;color:#2a5934;font-weight:bold;">
                        <?= htmlspecialchars($newPassword) ?>
                    </p>
                    <p><a href="/pages/login.php" class="btn">Ga naar inloggen</a></p>
                <?php endif; ?>
                <?php endif; ?>
                <?php if (!$newPassword): ?>
                <form method="post">
                    <label for="username">Gebruikersnaam</label>
                    <input type="text" name="username" id="username" required>
                    <button type="submit">Genereer nieuw wachtwoord</button>
                </form>
                <?php endif; ?>
            </div>
        </div>
    </main>
</body>
</html>
