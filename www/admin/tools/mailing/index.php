<?php
// =======================================
// Dev mailpagina – enkel mails verzenden
// =======================================

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// PHPMailer laden
require $_SERVER['DOCUMENT_ROOT'] . '/classes/PHPMailer/src/Exception.php';
require $_SERVER['DOCUMENT_ROOT'] . '/classes/PHPMailer/src/PHPMailer.php';
require $_SERVER['DOCUMENT_ROOT'] . '/classes/PHPMailer/src/SMTP.php';

$result = "";
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $to      = trim($_POST['to'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if ($to === '' || $subject === '' || $message === '') {
        $error = "Alle velden zijn verplicht.";
    } else {

        try {
            $mail = new PHPMailer(true);

            // SMTP configuratie
            $mail->isSMTP();
            $mail->Host       = 'mail.webcrafters.be';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'noreply@mailout.windelsgreen-decoresin.com';
            $mail->Password   = 'liNDEW,;,32';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            $mail->setFrom('noreply@mailout.windelsgreen-decoresin.com', 'Windels green & deco resin');
            $mail->addAddress($to);

            $mail->Subject = $subject;
            $mail->Body    = $message;

            $mail->send();
            $result = "Mail verzonden naar {$to}.";

        } catch (Exception $e) {
            $error = "Fout: " . $mail->ErrorInfo;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Dev Mail Sender</title>
    <style>
        body { font-family: Arial; padding: 30px; max-width: 600px; }
        input, textarea { width: 100%; padding: 10px; margin: 8px 0; }
        button { padding: 10px 15px; cursor: pointer; }
        .ok { color: green; margin-bottom: 10px; }
        .err { color: red; margin-bottom: 10px; }
    </style>
</head>
<body>

<h2>Dev Mail Sender</h2>
<p>Enkel voor testen van mail verzending.</p>

<?php if ($result): ?>
    <div class="ok"><?= htmlspecialchars($result) ?></div>
<?php endif; ?>

<?php if ($error): ?>
    <div class="err"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<form method="post">
    <label>Ontvanger (email)</label>
    <input type="email" name="to" required>

    <label>Onderwerp</label>
    <input type="text" name="subject" required>

    <label>Bericht</label>
    <textarea name="message" rows="6" required></textarea>

    <button type="submit">Verzend</button>
</form>

</body>
</html>
