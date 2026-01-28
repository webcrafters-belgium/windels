<?php

require $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require $_SERVER['DOCUMENT_ROOT'] . '/lib/PHPMailer/PHPMailer.php';
require $_SERVER['DOCUMENT_ROOT'] . '/lib/PHPMailer/SMTP.php';
require $_SERVER['DOCUMENT_ROOT'] . '/lib/PHPMailer/Exception.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    exit("Ongeldig ID.");
}

$newsletterId = (int)$_GET['id'];

// Nieuwsbrief ophalen
$stmt = $conn->prepare("SELECT subject, message FROM newsletters WHERE id = ?");
$stmt->bind_param("i", $newsletterId);
$stmt->execute();
$result = $stmt->get_result();
$newsletter = $result->fetch_assoc();
$stmt->close();

if (!$newsletter) {
    exit("Nieuwsbrief niet gevonden.");
}

// Abonnees ophalen
$subs = $conn->query("SELECT email FROM subscribers");
if ($subs->num_rows === 0) {
    exit("Geen abonnees gevonden.");
}

// Mail verzenden naar elke abonnee
$successCount = 0;
while ($sub = $subs->fetch_assoc()) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = $smtp_host;
        $mail->SMTPAuth = true;
        $mail->Username = $smtp_user;
        $mail->Password = $smtp_pass;
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom("news@windelsgreen-decoresin.com", "Windels Deco & Resin");
        $mail->addAddress($sub['email']);

        $mail->isHTML(true);
        $mail->Subject = $newsletter['subject'];
        $mail->Body = $newsletter['message'];

        $mail->send();
        $successCount++;
    } catch (Exception $e) {
        error_log("❌ Mail naar {$sub['email']} mislukt: " . $mail->ErrorInfo);
    }
}

echo "<h2 style='padding:2rem;'>✅ Nieuwsbrief verzonden naar $successCount abonnees.</h2>";
echo "<a href='/admin/pages/newsletter/' style='margin-left:2rem;' class='btn btn-primary'>Terug</a>";
