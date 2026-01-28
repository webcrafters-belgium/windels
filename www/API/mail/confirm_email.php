<?php
include $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';
include $_SERVER['DOCUMENT_ROOT'] . '/header.php';

if (!isset($_GET['token']) || empty($_GET['token'])) {
    die("Ongeldige bevestigingslink.");
}

$token = $_GET['token'];

// Zoek gebruiker op basis van token
$query = $conn->prepare("SELECT id FROM users WHERE confirmation_token = ? AND is_confirmed = 0");
$query->bind_param("s", $token);
$query->execute();
$query->store_result();

if ($query->num_rows > 0) {
    $query->bind_result($user_id);
    $query->fetch();
    $query->close();

    // Bevestig account
    $update = $conn->prepare("UPDATE users SET is_confirmed = 1, confirmation_token = NULL WHERE id = ?");
    $update->bind_param("i", $user_id);

    if ($update->execute()) {
        echo "<h2>Bevestiging gelukt!</h2><p>Je account is geverifieerd. <a href='/pages/account/login/'>Log in</a></p>";
    } else {
        echo "<h2>Er ging iets mis</h2><p>Probeer het later opnieuw.</p>";
    }
    $update->close();
} else {
    echo "<h2>Ongeldige of verlopen link</h2><p>Deze link is al gebruikt of is ongeldig.</p>";
}

$conn->close();

include $_SERVER['DOCUMENT_ROOT'] . '/footer.php';
?>
