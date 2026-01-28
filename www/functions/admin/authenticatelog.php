<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Databaseverbinding
global $conn;
if ($conn->connect_error) {
    die("Verbinding mislukt: " . $conn->connect_error);
}

// Controleer of de gebruiker is ingelogd
if (!isset($_SESSION['admin_id'])) {
    $_SESSION['status'] = "U moet ingelogd zijn om toegang te krijgen tot deze pagina.";
    $_SESSION['status_code'] = "error";
    header("Location: /login.php");
    exit();
}

// Haal de 2FA-status op
$stmt = $conn->prepare("SELECT 2fa_enabled FROM admins_workforce WHERE admin_id = ?");
$stmt->bind_param("i", $_SESSION['admin_id']);
$stmt->execute();
$stmt->bind_result($two_fa_enabled);
$stmt->fetch();
$stmt->close();

// Controleer of de gebruiker bestaat
if ($two_fa_enabled === null) {
    $_SESSION['status'] = "Uw account kon niet worden gevonden. Neem contact op met de beheerder.";
    $_SESSION['status_code'] = "error";
    session_unset();
    session_destroy();
    //header("Location: /login.php");
    //exit();
}

// Controleer of 2FA is ingeschakeld
if ($two_fa_enabled && (!isset($_SESSION['2fa_verified']) || $_SESSION['2fa_verified'] !== true)) {
    $_SESSION['status'] = "2FA-verificatie vereist. Voltooi de verificatie om verder te gaan.";
    $_SESSION['status_code'] = "error";
    //header("Location: /2fa_verification.php");
    //exit();
}

// Controleer inactiviteit van de sessie (2 uur)
$inactive = 7200; // 2 uur in seconden
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $inactive) {
    session_start();
    session_destroy();
    $_SESSION['status'] = "Sessie verlopen wegens inactiviteit van 2 uur. Log opnieuw in.";
    $_SESSION['status_code'] = "error";
    header("Location: /login.php");
    exit();
} else {
    $_SESSION['last_activity'] = time();
}

// Werk laatste activiteit bij in de database
$stmt = $conn->prepare("UPDATE admins_workforce SET last_activity = NOW() WHERE admin_id = ?");
$stmt->bind_param("i", $_SESSION['admin_id']);
$stmt->execute();
$stmt->close();

// Controleer op beperkte toegang en restricties voor bepaalde pagina's
$current_page = basename($_SERVER['PHP_SELF']);
$allowed_pages = ['profile.php', 'gdpr.php', 'whistleblower.php'];
if (isset($_SESSION['limited_access']) && $_SESSION['limited_access'] === true && !in_array($current_page, $allowed_pages)) {
    $_SESSION['status'] = "Beperkte toegang: je contract is verlopen. Je kunt alleen profielgerelateerde pagina's bekijken.";
    $_SESSION['status_code'] = "warning";
    header("Location: /profile.php");
    exit();
}

// Controleer sessie-ID consistentie om dubbele inlog te voorkomen
$stmt = $conn->prepare("SELECT session_id FROM admins_workforce WHERE admin_id = ?");
$stmt->bind_param("i", $_SESSION['admin_id']);
$stmt->execute();
$stmt->bind_result($db_session_id);
$stmt->fetch();
$stmt->close();

if ($db_session_id && $db_session_id !== $_SESSION['session_id']) {
    session_unset();
    session_destroy();
    session_start();
    $_SESSION['status'] = "Je bent uitgelogd vanwege een dubbele inlog op een ander apparaat.";
    $_SESSION['status_code'] = "error";
    header("Location: /login.php");
    exit();
}
?>
