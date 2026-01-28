<?php
global $user;
session_start();

require_once $_SERVER['DOCUMENT_ROOT'] . '/ini.inc'; // voor $mysqli
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/php-graph-sdk-5.7.0/src/Facebook/autoload.php';

use Facebook\Facebook;

$fb = new Facebook([
    'app_id' => '969637121765527',
    'app_secret' => 'cb9f9b4dfb7ac3a4493894c3caa11859',
    'default_graph_version' => 'v18.0',
]);

$helper = $fb->getRedirectLoginHelper();

try {
    $accessToken = $helper->getAccessToken();
} catch (Facebook\Exceptions\FacebookResponseException $e) {
    echo 'Graph fout: ' . $e->getMessage();
    exit;
} catch (Facebook\Exceptions\FacebookSDKException $e) {
    echo 'SDK fout: ' . $e->getMessage();
    exit;
}

if (!isset($accessToken)) {
    if ($helper->getError()) {
        echo "Fout: " . htmlspecialchars($helper->getError()) . "<br>";
        echo "Code: " . htmlspecialchars($helper->getErrorCode()) . "<br>";
        echo "Reden: " . htmlspecialchars($helper->getErrorReason()) . "<br>";
        echo "Beschrijving: " . htmlspecialchars($helper->getErrorDescription()) . "<br>";
    } else {
        echo 'Onbekende fout bij het verkrijgen van toegangstoken.';
    }
    exit;
}

// Token opslaan in sessie
$_SESSION['fb_access_token'] = (string) $accessToken;

// Optional: long-lived token (blijft behouden voor latere requests)
$oAuth2Client = $fb->getOAuth2Client();
if (!$accessToken->isLongLived()) {
    try {
        $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
        $_SESSION['fb_access_token'] = (string) $accessToken;
    } catch (Facebook\Exceptions\FacebookSDKException $e) {
        echo "Kan geen long-lived token verkrijgen: " . $e->getMessage();
        exit;
    }
}

// 📡 Gebruikersgegevens ophalen via cURL (zonder SDK)
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://graph.facebook.com/v18.0/me?fields=id,email,first_name,last_name&access_token=' . $accessToken);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

$userData = json_decode($response, true);

// Check op fout of incomplete response
if (!isset($userData['id']) || !isset($userData['email'])) {
    echo "Kan gebruikersgegevens niet ophalen. Probeer opnieuw.";
    exit;
}

// 📝 Registratie in de database
$facebook_id = $userData['id'];
$email = $userData['email'];
$first_name = $userData['first_name'] ?? '';
$last_name = $userData['last_name'] ?? '';
$full_name = trim($first_name . ' ' . $last_name);

// Controleer of gebruiker al bestaat
$stmt = $mysqli->prepare("SELECT id, name, email FROM users WHERE facebook_id = ? OR email = ?");
$stmt->bind_param("ss", $facebook_id, $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Gebruiker bestaat al
    $user = $result->fetch_assoc();
} else {
    // Nieuwe gebruiker registreren
    $stmt = $mysqli->prepare("INSERT INTO users (facebook_id, name, email) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $facebook_id, $full_name, $email);
    $stmt->execute();

    $user = [
        'id' => $stmt->insert_id,
        'name' => $full_name,
        'email' => $email
    ];
}

// Sessie opslaan
$_SESSION['user'] = $user;

// ✅ Klaar! Doorsturen
header('Location: /');
exit;
