<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';
session_start();

if (!isset($_SESSION['user']['role']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: /pages/account/login?referer=/admin/pages/settings/index.php');
    exit;
}

// Compat route: oude settings URL doorsturen naar actuele config module.
header('Location: /admin/config/', true, 302);
exit;
?>
