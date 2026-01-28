<?php
// FILE: /pages/account/register_handler.php

session_start();
require $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';

/* ==============================
   HONEYPOT BACKEND CHECK
   ============================== */
$honeypots = ['website', 'url', 'company', 'fax'];
foreach ($honeypots as $hp) {
    if (!empty($_POST[$hp])) {
        exit('Verdachte registratie');
    }
}

// Time honeypot (min 3 sec invultijd)
$formTime = (int)($_POST['form_time'] ?? 0);
if ($formTime === 0 || (time() - $formTime) < 3) {
    exit('Verdachte registratie');
}

/* ==============================
   FORM VALIDATIE
   ============================== */
if (!isset($_POST['name'], $_POST['email'], $_POST['password'])) {
    exit('Ongeldig formulier');
}

$username = trim($_POST['name']);
$email    = trim($_POST['email']);
$password = $_POST['password'];

/* ==============================
   EMAIL UNIEK?
   ============================== */
$stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    exit("Dit e-mailadres is al geregistreerd.");
}
$stmt->close();

/* ==============================
   OPSLAAN
   ============================== */
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$stmt = $conn->prepare(
    "INSERT INTO users (username, email, password) VALUES (?, ?, ?)"
);
$stmt->bind_param("sss", $username, $email, $hashed_password);

if (!$stmt->execute()) {
    exit("Registratie mislukt");
}

/* ==============================
   AUTO LOGIN
   ============================== */
$_SESSION['user_id'] = $stmt->insert_id;
header("Location: /pages/account/accountgegevens");
exit;
