<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';

$message = "Ongeldige token of melding niet gevonden.";

$token = $_GET['token'] ?? '';
if (preg_match('/^[a-f0-9]{32}$/', $token)) {
    $stmt = $conn->prepare("UPDATE error_reports SET verified = 1 WHERE token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();

    if ($stmt->affected_rows === 1) {
        // Melding ophalen
        $stmt = $conn->prepare("SELECT email, message, screenshot_path FROM error_reports WHERE token = ?");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $result = $stmt->get_result();
        $report = $result->fetch_assoc();

        if ($report) {
            $email      = $report['email'];
            $message    = $report['message'];
            $screenshot = $report['screenshot_path'];

            require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/PHPMailer/Exception.php';
            require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/PHPMailer/SMTP.php';
            require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/PHPMailer/PHPMailer.php';

            $mail = new PHPMailer\PHPMailer\PHPMailer(true);

            try {
                $mail->isSMTP();
                $mail->Host       = $smtp_host;
                $mail->SMTPAuth   = true;
                $mail->Username   = $smtp_user;
                $mail->Password   = $smtp_pass;
                $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port       = 587;
                $mail->CharSet    = 'UTF-8';

                $mail->setFrom('noreply@windelsgreen-decoresin.com', 'Meldingssysteem');
                $mail->addAddress('matthias@webcrafters.be'); // Ontvanger

                $mail->Subject = "Nieuwe bevestigde probleemmelding";

                $body = "Er is een nieuwe bevestigde melding:\n\n"
                    . "E-mailadres: $email\n"
                    . "Bericht:\n$message\n\n"
                    . "Screenshot: " . ($screenshot ? basename($screenshot) : 'geen') . "\n";

                $mail->Body = $body;

                if ($screenshot) {
                    // Zorg dat pad absoluut is
                    $absScreenshotPath = $_SERVER['DOCUMENT_ROOT'] . $screenshot;
                    if (file_exists($absScreenshotPath)) {
                        $mail->addAttachment($absScreenshotPath);
                    }
                }

                $mail->send();

                $message = "Bedankt! Je melding is bevestigd en wordt verwerkt.";
            } catch (Exception $e) {
                error_log("Mailfout bij melding: {$mail->ErrorInfo}");
                $message = "Bedankt! Je melding is bevestigd, maar de notificatie kon niet worden verzonden.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8" />
    <title>Bevestiging Melding</title>
    <meta http-equiv="refresh" content="5;url=/" />
    <style>
        body {
            font-family: sans-serif;
            padding: 2em;
            background: #f5f5f5;
            text-align: center;
        }
        .box {
            background: white;
            padding: 2em;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            display: inline-block;
        }
    </style>
</head>
<body>
<div class="box">
    <h2><?= htmlspecialchars($message, ENT_QUOTES | ENT_HTML5) ?></h2>
    <p>Je wordt binnen enkele seconden teruggestuurd naar de homepage.</p>
</div>
</body>
</html>
