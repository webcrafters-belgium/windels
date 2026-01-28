<?php
session_start();
require $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';

// Laad Facebook SDK handmatig
require $_SERVER['DOCUMENT_ROOT'] . '/lib/php-graph-sdk-5.7.0/src/Facebook/autoload.php';

use \Facebook\Facebook;

// Configureer Facebook OAuth Client
$fb = new Facebook([
    'app_id' => '969637121765527',
    'app_secret' => 'cb9f9b4dfb7ac3a4493894c3caa11859',
    'default_graph_version' => 'v18.0',
]);

$helper = $fb->getRedirectLoginHelper();
$permissions = ['email']; // Permissies die we nodig hebben
$loginUrl = $helper->getLoginUrl('https://windelsgreen-decoresin.com/API/facebook/oauth2callback.php', $permissions);

try {
    // Stuur de gebruiker door naar Facebook login
    header("Location: " . filter_var($loginUrl, FILTER_SANITIZE_URL));
    exit;
} catch (Exception $e) {
    error_log("Facebook Login Error: " . $e->getMessage());
    header("Location: /pages/account/login.php?error=facebook_login_failed");
    exit;
}
