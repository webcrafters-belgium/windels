<?php
// FILE: /pages/account/register.php

session_start();
require $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';

/* ==============================
   PHPMailer standalone includes
   ============================== */
require $_SERVER['DOCUMENT_ROOT'] . '/classes/PHPMailer/src/PHPMailer.php';
require $_SERVER['DOCUMENT_ROOT'] . '/classes/PHPMailer/src/SMTP.php';
require $_SERVER['DOCUMENT_ROOT'] . '/classes/PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/* ==============================
   Redirect als ingelogd
   ============================== */
if (isset($_SESSION['user_id'])) {
    header("Location: /pages/account/dashboard.php");
    exit;
}

/* ==============================
   POST: registratie afhandelen
   ============================== */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');

    /* ==============================
       HONEYPOT CHECKS
       ============================== */
    $honeypots = ['website', 'url', 'company', 'fax'];
    foreach ($honeypots as $hp) {
        if (!empty($_POST[$hp])) {
            echo json_encode(['success' => false, 'message' => 'Verdachte registratie']);
            exit;
        }
    }

    // Time-based honeypot (te snel = bot)
    $formTime = (int)($_POST['form_time'] ?? 0);
    if ($formTime === 0 || (time() - $formTime) < 3) {
        echo json_encode(['success' => false, 'message' => 'Verdachte registratie']);
        exit;
    }

    $required = [
            'username','email','password','confirm-password',
            'first_name','last_name','address','zipcode','city','country'
    ];

    foreach ($required as $field) {
        if (empty($_POST[$field])) {
            echo json_encode(['success' => false, 'message' => 'Niet alle verplichte velden zijn ingevuld']);
            exit;
        }
    }

    if ($_POST['password'] !== $_POST['confirm-password']) {
        echo json_encode(['success' => false, 'message' => 'Wachtwoorden komen niet overeen']);
        exit;
    }

    /* ==============================
       Uniekheid check
       ============================== */
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? OR username = ?");
    $stmt->bind_param("ss", $_POST['email'], $_POST['username']);
    $stmt->execute();
    if ($stmt->get_result()->num_rows > 0) {
        echo json_encode(['success' => false, 'message' => 'Gebruiker of e-mail bestaat al']);
        exit;
    }

    /* ==============================
       Gebruiker opslaan
       ============================== */
    $passwordHash = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("
        INSERT INTO users
        (username,email,password,first_name,last_name,phone,address,zipcode,city,country,created_at)
        VALUES (?,?,?,?,?,?,?,?,?,?,NOW())
    ");

    $stmt->bind_param(
            "ssssssssss",
            $_POST['username'],
            $_POST['email'],
            $passwordHash,
            $_POST['first_name'],
            $_POST['last_name'],
            $_POST['phone'],
            $_POST['address'],
            $_POST['zipcode'],
            $_POST['city'],
            $_POST['country']
    );

    if (!$stmt->execute()) {
        echo json_encode(['success' => false, 'message' => 'Registratie mislukt']);
        exit;
    }

    /* ==============================
       Bevestigingsmail
       ============================== */
    try {
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host       = $smtp_host;
        $mail->SMTPAuth   = true;
        $mail->Username   = $smtp_user;
        $mail->Password   = $smtp_pass;
        $mail->Port       = $smtp_port;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->CharSet    = 'UTF-8';

        $mail->setFrom($smtp_user, 'Webcrafters');
        $mail->addAddress($_POST['email'], $_POST['first_name']);
        $mail->isHTML(true);
        $mail->Subject = 'Welkom bij Webcrafters';
        $mail->Body = "
            <h2>Welkom {$_POST['first_name']}</h2>
            <p>Je account is succesvol aangemaakt.</p>
        ";
        $mail->send();
    } catch (Exception $e) {}

    echo json_encode(['success' => true]);
    exit;
}

/* ==============================
   GET: formulier tonen
   ============================== */
include $_SERVER['DOCUMENT_ROOT'] . '/header.php';
?>

<div class="register-container">
    <h2>Registreren</h2>

    <form id="register-form" method="post">
        <input name="username" placeholder="Gebruikersnaam" required>
        <input name="email" type="email" placeholder="E-mail" required>
        <input name="password" type="password" placeholder="Wachtwoord" required>
        <input name="confirm-password" type="password" placeholder="Bevestig wachtwoord" required>

        <input name="first_name" placeholder="Voornaam" required>
        <input name="last_name" placeholder="Achternaam" required>
        <input name="phone" placeholder="Telefoon">
        <input name="address" placeholder="Adres" required>
        <input name="zipcode" placeholder="Postcode" required>
        <input name="city" placeholder="Stad" required>
        <input name="country" placeholder="Land" required>

        <!-- HONEYPOTS -->
        <input type="text" name="website" tabindex="-1" autocomplete="off" style="display:none">
        <input type="text" name="url" tabindex="-1" autocomplete="off" style="display:none">
        <input type="text" name="company" tabindex="-1" autocomplete="off" style="display:none">
        <input type="text" name="fax" tabindex="-1" autocomplete="off" style="display:none">

        <!-- TIME HONEYPOT -->
        <input type="hidden" name="form_time" value="<?= time() ?>">

        <button type="submit">Registreren</button>
    </form>
</div>

<script>
    document.getElementById('register-form').addEventListener('submit', e => {
        e.preventDefault();
        fetch(location.href, { method:'POST', body:new FormData(e.target) })
            .then(r => r.json())
            .then(d => d.success
                ? location.href = '/pages/account/login/'
                : alert(d.message)
            );
    });
</script>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/footer.php'; ?>
