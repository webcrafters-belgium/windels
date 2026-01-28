<?php
session_start();

if (isset($_GET['referer'])) {
    $referer = $_GET['referer'];
}

if (isset($_SESSION['email_verification_warning'])) {
    echo "<div class='alert alert-warning'>" . $_SESSION['email_verification_warning'] . "</div>";
    unset($_SESSION['email_verification_warning']); // Melding verwijderen na weergave
}

include $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';
include $_SERVER['DOCUMENT_ROOT'] . '/header.php';
include $_SERVER['DOCUMENT_ROOT'] . '/partials/forms/login_form.php';
include $_SERVER['DOCUMENT_ROOT'] . '/footer.php'; ?>
 