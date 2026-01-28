<?php
session_start();
require $_SERVER['DOCUMENT_ROOT'] . '/ini.inc'; // bevat DB-connectie

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['email'])) {
    $email = strtolower(trim($_POST['email']));


    // Controleer of e-mail bestaat
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 0) {
        $_SESSION['forgot_error'] = "Geen gebruiker gevonden met dit e-mailadres.";
        header("Location: /pages/account/forgot_password/");
        exit;
    }

    // Token genereren
    $token = bin2hex(random_bytes(32));
    $expires = date("Y-m-d H:i:s", time() + 3600); // 1 uur geldig

    // Token opslaan
    $stmt = $conn->prepare("INSERT INTO password_resets (email, token, expires_at) VALUES (?, ?, ?)
        ON DUPLICATE KEY UPDATE token = VALUES(token), expires_at = VALUES(expires_at)");
    $stmt->bind_param("sss", $email, $token, $expires);
    $stmt->execute();

    // Reset-link aanmaken
    $resetLink = "https://windelsgreen-decoresin.com/pages/account/reset_password/?token=$token";

    // E-mail verzenden
    $subject = "Wachtwoord opnieuw instellen";
    $message = "Hallo,\n\nJe hebt een verzoek gedaan om je wachtwoord opnieuw in te stellen.\nKlik op de onderstaande link:\n\n$resetLink\n\nDeze link is 1 uur geldig.\n\nAls je dit niet hebt aangevraagd, kun je dit bericht negeren.";

    if (mail($email, $subject, $message)) {
        $_SESSION['forgot_success'] = "Er is een herstel-link verzonden naar je e-mailadres.";
    } else {
        $_SESSION['forgot_error'] = "Er ging iets mis bij het verzenden van de e-mail.";
    }

    header("Location: /pages/account/forgot_password/");
    exit;
} else {
    $_SESSION['forgot_error'] = "Ongeldige aanvraag.";
    header("Location: /pages/account/forgot_password/");
    exit;
}
