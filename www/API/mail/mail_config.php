<?php

use PHPMailer\Exception;
use PHPMailer\PHPMailer;

require $_SERVER['DOCUMENT_ROOT'] . '/lib/PHPMailer/Exception.php';
require $_SERVER['DOCUMENT_ROOT'] . '/lib/PHPMailer/PHPMailer.php';
require $_SERVER['DOCUMENT_ROOT'] . '/lib/PHPMailer/SMTP.php';

function sendMail($to, $subject, $body) {
    $mail = new PHPMailer(true);

    try {
        // SMTP instellingen
        $mail->isSMTP();
        $mail->Host = 'mail.webcrafters.be'; // SMTP-server (aanpassen)
        $mail->SMTPAuth = true;
        $mail->Username = 'noreply@mailout.windelsgreen-decoresin.com'; // SMTP-gebruikersnaam
        $mail->Password = '97,AmorDA,='; // SMTP-wachtwoord
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Beveiliging (TLS/SSL)
        $mail->Port = 587; // Poort (587 voor TLS, 465 voor SSL)

        // Afzender en ontvanger
        $mail->setFrom('noreply@mailout.windelsgreen-decoresin.com', 'Windels Registratie');
        $mail->addAddress($to);

        // E-mail inhoud
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $body;

        // Verzenden
        if ($mail->send()) {
            return true;
        } else {
            throw new Exception("E-mail kon niet worden verzonden.");
        }

    } catch (Exception $e) {
        error_log("Mailer Error: {$mail->ErrorInfo}");
        return false;
    }
}
?>
