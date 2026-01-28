<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/google_api_client/vendor/autoload.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';
require_once $_SERVER['DOCUMENT_ROOT'] . '/API/mail/mail_config.php';
global $conn;

$is_dev = false;

if (!isset($conn)) {
    die("⛔ Databaseverbinding niet geladen! Controleer ini.inc");
}

// **Stap 1: Google OAuth Client instellen**
$client = new Google_Client();
$client->setAuthConfig($_SERVER['DOCUMENT_ROOT'] . '/config/credentials.json');
$client->setRedirectUri('https://windelsgreen-decoresin.com/API/auth/google.php');
$client->addScope(['openid', 'email', 'profile']);
$client->setAccessType('offline');

if (!isset($_GET['code'])) {
    $_SESSION['error_message'] = "Geen OAuth-code ontvangen!";
    header("Location: /pages/account/login/");
    exit();
}

try {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    if (isset($token['error'])) {
        $_SESSION['error_message'] = "Google OAuth fout: " . json_encode($token);
        header("Location: /pages/account/login/");
        exit();
    }

    $client->setAccessToken($token);
    $oauth2 = new Google_Service_Oauth2($client);
    $userInfo = $oauth2->userinfo->get();

    $email = $conn->real_escape_string($userInfo->email);
    $profilePicture = $userInfo->picture; // ✅ Profielfoto ophalen

    $query = "SELECT id, is_confirmed FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($user_id, $is_confirmed);
        $stmt->fetch();
        $stmt->close();

        if ($is_confirmed == 0) {
            $_SESSION['error_message'] = "Je account is nog niet bevestigd. Controleer je e-mail.";
            header("Location: /pages/account/login/");
            exit();
        }

        $_SESSION['user'] = ['id' => $user_id, 'name' => $userInfo->name, 'email' => $userInfo->email, 'profile_picture' => $profilePicture];
        header("Location: /pages/account/");
        exit();
    } else {
        $stmt->close();

        // ✅ Variabelen instellen
        $nameParts = explode(" ", $userInfo->name, 2);
        $first_name = $nameParts[0] ?? '';
        $last_name = $nameParts[1] ?? '';
        $created_at = date('Y-m-d H:i:s');
        $confirmation_token = bin2hex(random_bytes(32));
        $role = 'customer';

        // ✅ Nieuwe gebruiker toevoegen
        $insertQuery = "INSERT INTO users (username, email, first_name, last_name, is_confirmed, created_at, confirmation_token, role) 
                        VALUES (?, ?, ?, ?, 0, ?, ?, ?)";
        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param("sssssss", $userInfo->name, $email, $first_name, $last_name, $created_at, $confirmation_token, $role);

        if (!$stmt->execute()) {
            error_log("❌ Fout bij Google-registratie: " . $stmt->error);
            die("⛔ Fout bij registreren: " . $stmt->error);
        }

        // ✅ Gebruiker is succesvol toegevoegd, haal het user_id op
        $user_id = $stmt->insert_id;
        $stmt->close();

        // ✅ Profielfoto opslaan in user_images tabel
        $insertImgQuery = "INSERT INTO user_images (user_id, image_path) VALUES (?, ?)";
        $stmt_img = $conn->prepare($insertImgQuery);
        $stmt_img->bind_param("is", $user_id, $profilePicture);

        if (!$stmt_img->execute()) {
            error_log("❌ Fout bij opslaan profielfoto: " . $stmt_img->error);
        }

        $stmt_img->close();

        // ✅ E-mail verificatie verzenden
        $subject = "Bevestig je Google-registratie bij Windels";
        $body = "
            <h1>Welkom bij Windels, {$userInfo->name}!</h1>
            <p>Bedankt voor je registratie via Google. Klik op de link hieronder om je e-mailadres te bevestigen:</p>
            <p><a href='https://windelsgreen-decoresin.com/API/auth/confirm.php?token=$confirmation_token'>Bevestig je e-mail</a></p>
            <p>Als je deze registratie niet hebt aangevraagd, negeer dan deze e-mail.</p>
        ";

        if (sendMail($email, $subject, $body)) {
            $_SESSION['email_verification_message'] = "Je account is geregistreerd! Controleer je e-mail om je registratie te bevestigen.";
        } else {
            $_SESSION['email_verification_message'] = "Je account is geregistreerd, maar we konden de bevestigingsmail niet verzenden.";
        }

        $_SESSION['user'] = ['id' => $user_id, 'name' => $userInfo->name, 'email' => $userInfo->email, 'profile_picture' => $profilePicture];
        header("Location: /pages/account/login/producten.php?mail_confirmed=0");
        exit();
    }

} catch (Exception $e) {
    $_SESSION['error_message'] = "⛔ Er is een fout opgetreden, probeer het later opnieuw.";
    header("Location: /pages/account/register/");
    exit();
}
?>
