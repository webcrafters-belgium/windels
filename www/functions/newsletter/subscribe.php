<?php

require $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';

// PHPMailer handmatig includen
require $_SERVER['DOCUMENT_ROOT'] . '/lib/PHPMailer/PHPMailer.php';
require $_SERVER['DOCUMENT_ROOT'] . '/lib/PHPMailer/SMTP.php';
require $_SERVER['DOCUMENT_ROOT'] . '/lib/PHPMailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');

    if (empty($email)) {
        echo json_encode(['status' => 'error', 'message' => 'E-mailadres is verplicht.']);
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['status' => 'error', 'message' => 'Ongeldig e-mailadres.']);
        exit;
    }

    // Check of e-mail al bestaat
    $check = $conn->prepare("SELECT id FROM subscribers WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        echo json_encode(['status' => 'error', 'message' => 'Je bent al ingeschreven.']);
        $check->close();
        $conn->close();
        exit;
    }
    $check->close();

    // Verstuur bevestigingsmail
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.yourwww.email';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'server@windelsgreen-decoresin.com';
        $mail->Password   = $smtp_pass;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom('no-reply@windelsgreen-decoresin.com', 'Windels Green DecoResin');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'Bevestiging inschrijving nieuwsbrief';
        $mail->Body    = '
            <html>
                <body style="font-family: Arial, sans-serif; color: #333;">
                    <h2>Bedankt voor je inschrijving!</h2>
                    <p>We houden je op de hoogte van nieuwe producten, aanbiedingen en inspiratie.</p>
                    <p>Team Windels Green DecoResin</p>
                </body>
            </html>';

        $mail->send();

        // Inschrijven in database NA verzenden mail
        $stmt = $conn->prepare("INSERT INTO subscribers (email) VALUES (?)");
        $stmt->bind_param("s", $email);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Bevestigingsmail is verzonden. Je bent ingeschreven!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Fout bij opslaan in de database.']);
        }

        $stmt->close();
        $conn->close();

    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => 'Mail fout: ' . $mail->ErrorInfo]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Ongeldig verzoek.']);
}
