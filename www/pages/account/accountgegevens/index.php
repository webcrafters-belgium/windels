<?php
include $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';
session_start();
include $_SERVER['DOCUMENT_ROOT'] . '/header.php';

// Controleren of gebruiker is ingelogd
if (isset($_SESSION['user_id'])) {
    // Gebruiker is ingelogd, accountgegevens ophalen
    $stmt = $mysqli->prepare("SELECT name, email, phone FROM users WHERE id = ?");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $stmt->bind_result($name, $email, $phone);
    $stmt->fetch();
    $stmt->close();
    ?>

    <div class="accountgegevens">
        <h1>Mijn account</h1>
        <p><strong>Naam:</strong> <?php echo htmlspecialchars($name); ?></p>
        <p><strong>E-mailadres:</strong> <?php echo htmlspecialchars($email); ?></p>
        <p><strong>Telefoonnummer:</strong> <?php echo htmlspecialchars($phone ?: 'Niet opgegeven'); ?></p>
        <a href="/pages/account/accountgegevens/edit/">Accountgegevens wijzigen</a>
    </div>

    <?php
} else {
    // Niet ingelogd → Registratieformulier tonen
    ?>

    <div class="registratieformulier">
        <h1>Registreren</h1>
        <form action="/functions/account/register/register.php" method="post">
            <label>Naam:</label>
            <input type="text" name="name" required>
            <label>E-mailadres:</label>
            <input type="email" name="email" required>
            <label>Wachtwoord:</label>
            <input type="password" name="password" required>
            <label>Telefoonnummer (optioneel):</label>
            <input type="text" name="phone">
            <button type="submit">Registreer</button>
        </form>
        <p>Heb je al een account? <a href="/pages/account/login">Log hier in</a>.</p>
    </div>

    <?php
}

include $_SERVER['DOCUMENT_ROOT'] . '/footer.php';
?>
