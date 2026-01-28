<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';

header("Content-Type: application/json");

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';
$referer = $_POST['referer'] ?? '';

// ✅ Referer sanity check
if (!empty($referer) && str_starts_with($referer, '/')) {
    $redirect = $referer;
} else {
    $redirect = '/pages/account/';
}

if (empty($email) || empty($password)) {
    echo json_encode(["success" => false, "message" => "Vul alle velden in."]);
    exit();
}

// ✅ **Controleer of de gebruiker bestaat**
$query = $conn->prepare("SELECT id, username, password, role FROM users WHERE email = ?");
$query->bind_param("s", $email);
$query->execute();
$result = $query->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();
    $hashed_password = $user['password'];

    // ✅ **Controleer wachtwoord**
    if (password_verify($password, $hashed_password)) {
        // ✅ **Sla gebruiker op in de sessie**
        $_SESSION['user'] = [
            'id' => $user['id'],
            'name' => $user['username'],
            'email' => $email,
            'role' => $user['role']
        ];

        echo json_encode([
            "success" => true,
            "message" => "Inloggen geslaagd.",
            "referer" => $redirect
        ]);
        exit();
    } else {
        echo json_encode(["success" => false, "message" => "Ongeldig wachtwoord."]);
        exit();
    }
} else {
    echo json_encode(["success" => false, "message" => "Geen account gevonden met dit e-mailadres."]);
    exit();
}
?>
