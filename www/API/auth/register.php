<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';
include $_SERVER['DOCUMENT_ROOT'] . '/API/mail/mail_config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["success" => false, "message" => "Ongeldige aanvraag."]);
    exit;
}

// ✅ Formulierdata ophalen
$username = trim($_POST['username'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$confirm_password = $_POST['confirm-password'] ?? '';
$first_name = trim($_POST['first_name'] ?? '');
$last_name = trim($_POST['last_name'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$address = trim($_POST['address'] ?? '');
$zipcode = trim($_POST['zipcode'] ?? '');
$city = trim($_POST['city'] ?? '');
$country = trim($_POST['country'] ?? '');

// ✅ Basisvalidaties
if (empty($username) || empty($email) || empty($password) || empty($confirm_password) ||
    empty($first_name) || empty($last_name) || empty($address) || empty($zipcode) || empty($city) || empty($country)) {
    echo json_encode(["success" => false, "message" => "Vul alle verplichte velden in."]);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(["success" => false, "message" => "Ongeldig e-mailadres."]);
    exit;
}

if ($password !== $confirm_password) {
    echo json_encode(["success" => false, "message" => "Wachtwoorden komen niet overeen."]);
    exit;
}

// ✅ Hash wachtwoord en genereer een verificatie-token
$hashed_password = password_hash($password, PASSWORD_BCRYPT);
$confirmation_token = bin2hex(random_bytes(32)); // 64-karakter lange token

// ✅ Controleer of e-mail al bestaat
$query = $conn->prepare("SELECT id FROM users WHERE email = ?");
$query->bind_param("s", $email);
$query->execute();
$query->store_result();

if ($query->num_rows > 0) {
    echo json_encode(["success" => false, "message" => "E-mailadres is al geregistreerd."]);
    exit;
}
$query->close();

// ✅ Voeg gebruiker toe in `users`-tabel
$query = $conn->prepare("INSERT INTO users (username, email, password, first_name, last_name, phone, confirmation_token, is_confirmed) 
                         VALUES (?, ?, ?, ?, ?, ?, ?, 0)");
$query->bind_param("sssssss", $username, $email, $hashed_password, $first_name, $last_name, $phone, $confirmation_token);

if (!$query->execute()) {
    error_log("❌ Fout bij registreren: " . $query->error);
    echo json_encode(["success" => false, "message" => "Registratie mislukt."]);
    exit;
}

$user_id = $query->insert_id;
$query->close();

// ✅ Voeg adres toe aan `addresses`-tabel
$query = $conn->prepare("INSERT INTO addresses (user_id, address, city, zipcode, country, type) VALUES (?, ?, ?, ?, ?, 'shipping')");
$query->bind_param("issss", $user_id, $address, $city, $zipcode, $country);

if (!$query->execute()) {
    error_log("❌ Fout bij adres opslaan: " . $query->error);
}
$query->close();

// ✅ Stuur verificatie-e-mail
$subject = "Bevestig je registratie bij Windels";
$body = "
    <h1>Welkom bij Windels, $username!</h1>
    <p>Bedankt voor je registratie. Klik op de link hieronder om je e-mailadres te bevestigen:</p>
    <p><a href='https://windelsgreen-decoresin.com/API/mail/confirm_email.php?token=$confirmation_token'>Bevestig je e-mail</a></p>
    <p>Als je deze registratie niet hebt aangevraagd, negeer dan deze e-mail.</p>
";

if (sendMail($email, $subject, $body)) {
    echo json_encode(["success" => true, "message" => "Registratie geslaagd! Controleer je e-mail voor bevestiging."]);
} else {
    echo json_encode(["success" => false, "message" => "Registratie geslaagd, maar e-mail kon niet worden verzonden."]);
}

$conn->close();
?>
