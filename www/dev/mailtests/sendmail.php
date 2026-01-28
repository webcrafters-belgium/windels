<?php

require $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';
require $_SERVER['DOCUMENT_ROOT'] . '/lib/PHPMailer/Exception.php';
require $_SERVER['DOCUMENT_ROOT'] . '/lib/PHPMailer/PHPMailer.php';
require $_SERVER['DOCUMENT_ROOT'] . '/lib/PHPMailer/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// ✅ E-mail verzendfunctie
function sendMail($to, $subject, $body)
{
    global $smtp_host, $smtp_user, $smtp_pass;

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = $smtp_host;
        $mail->SMTPAuth = true;
        $mail->Username = $smtp_user;
        $mail->Password = $smtp_pass;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('orders@windelsgreen-decoresin.com', 'Windels');
        $mail->addAddress($to);

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $body;

        return $mail->send();
    } catch (Exception $e) {
        error_log("❌ Mailer error: " . $mail->ErrorInfo);
        return false;
    }
}

// ✅ Klantmail opstellen
$to = 'matthi_gielen@hotmail.be';
$subject = 'Bevestiging van je bestelling (#33)';
$body = '
    <h1>Bevestiging van je bestelling</h1>
    <p>Beste klant,</p>
    <p>Onze excuses: wegens een technisch probleem werd de bevestigingsmail niet automatisch verzonden.</p>
    <p>We bevestigen hierbij dat je betaling van <strong>&euro;30,23</strong> voor <strong>order #33</strong> (artikel: <em>Drie Wijze haasjes</em>) succesvol is ontvangen.</p>
    <p>We zijn momenteel bezig met de verwerking van je bestelling.</p>
    <p>Dankjewel voor je begrip en je vertrouwen!</p>
    <p>Met vriendelijke groet,<br>
    Het Windels green & deco resin Team</p>
';

echo '<h2>Mail versturen...</h2>';

if (sendMail($to, $subject, $body)) {
    echo '✅ Mail verzonden naar ' . htmlspecialchars($to);

    // ✅ Mail loggen in database
    $stmt = $conn->prepare("INSERT INTO emails (recipient, subject, message) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $to, $subject, $body);
    if ($stmt->execute()) {
        echo '<br>✅ Mail opgeslagen in database.';
    } else {
        echo '<br>❌ Fout bij opslaan in database: ' . $stmt->error;
    }
    $stmt->close();

} else {
    echo '❌ Verzenden mislukt. Bekijk de serverlog voor foutdetails.';
}
?>
