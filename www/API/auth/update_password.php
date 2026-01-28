<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';
require_once $_SERVER['DOCUMENT_ROOT'] . '/API/mail/mail_config.php';

if (!isset($_SESSION['user']['id'])) {
    echo json_encode(["success" => false, "message" => "Je moet ingelogd zijn."]);
    exit();
}

$user_id = $_SESSION['user']['id'];
$new_password = $_POST['new_password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';

if (strlen($new_password) < 8) {
    echo json_encode(["success" => false, "message" => "Het wachtwoord moet minstens 8 tekens lang zijn."]);
    exit();
}

if ($new_password !== $confirm_password) {
    echo json_encode(["success" => false, "message" => "De wachtwoorden komen niet overeen."]);
    exit();
}

// **Hash het wachtwoord en sla het op**
$hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

$query = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
$query->bind_param("si", $hashed_password, $user_id);

if ($query->execute()) {
    // ✅ **Stuur bevestigingsmail**
    $email = $_SESSION['user']['email'];
    $subject = "Je wachtwoord is gewijzigd";
    $body = "
        <h1>Je wachtwoord is succesvol gewijzigd</h1>
        <p>Hallo,</p>
        <p>Je wachtwoord is zojuist gewijzigd. Als jij dit niet was, neem dan onmiddellijk contact op met de support.</p>
        <p>Met vriendelijke groeten,<br>Het Windels Team</p>
    ";

    sendMail($email, $subject, $body);

    echo json_encode(["success" => true, "message" => "Wachtwoord succesvol bijgewerkt."]);
} else {
    echo json_encode(["success" => false, "message" => "Er is een fout opgetreden bij het bijwerken van het wachtwoord."]);
}

$query->close();
$conn->close();
?>
