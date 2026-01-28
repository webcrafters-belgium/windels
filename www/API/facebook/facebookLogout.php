<?php
session_start();

// Verwijder alleen Facebook-gerelateerde sessiegegevens
unset($_SESSION['fb_access_token']);
unset($_SESSION['fb_user']);

// (Optioneel) Alles uit de sessie wissen
// session_destroy();

// (Optioneel) Ook bij Facebook uitloggen via logout-url
$fbLogoutUrl = 'https://www.facebook.com/logout.php?next=' . urlencode('https://windelsgreen-decoresin.com/pages/account/login.php') . '&access_token=' . $_SESSION['fb_access_token'];

// Maar als de gebruiker al uitgelogd is op Facebook, heeft dit geen zin
// Je kunt dus ook gewoon terugsturen naar je eigen loginpagina:

header("Location: /pages/account/login.php");
exit;
