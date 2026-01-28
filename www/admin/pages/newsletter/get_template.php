<?php
// /admin/newsletter/get_template.php
$template = basename($_GET['file'] ?? '');
$allowed = ['promo.php', 'default.html', 'nieuwe_producten.html'];

if (!in_array($template, $allowed)) {
    http_response_code(403);
    exit("Niet toegestaan");
}

$templatePath = $_SERVER['DOCUMENT_ROOT'] . "/templates/newsletters/$template";

if (!file_exists($templatePath)) {
    http_response_code(404);
    exit("Bestand niet gevonden");
}

ob_start();
include $templatePath;
$content = ob_get_clean();
echo $content;
