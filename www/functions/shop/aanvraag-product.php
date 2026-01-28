<?php
include $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';
session_start();

require $_SERVER['DOCUMENT_ROOT'] . '/lib/PHPMailer/PHPMailer.php';
require $_SERVER['DOCUMENT_ROOT'] . '/lib/PHPMailer/SMTP.php';
require $_SERVER['DOCUMENT_ROOT'] . '/lib/PHPMailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

header('Content-Type: application/json');

// Check POST en product_id
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['product_id'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Ongeldig verzoek.']);
    exit;
}

$product_id = (int)$_POST['product_id'];
$email = $_POST['email'] ?? '';
$note = $_POST['note'] ?? '';

if (empty($email) || empty($note)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'E-mail en aanvraag zijn verplicht.']);
    exit;
}

// Voorbereiden statement met note erbij
$stmt = $conn->prepare("INSERT INTO product_requests (product_id, email, note, request_date) VALUES (?, ?, ?, NOW())");
if (!$stmt) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database fout: ' . $conn->error]);
    exit;
}

$stmt->bind_param('iss', $product_id, $email, $note);

if ($stmt->execute()) {
    // Productnaam ophalen uit DB om in mail te gebruiken
    $product_name = 'Onbekend product';
    $stmt2 = $conn->prepare("SELECT name FROM products WHERE id = ?");
    if ($stmt2) {
        $stmt2->bind_param("i", $product_id);
        $stmt2->execute();
        $stmt2->bind_result($prod_name_db);
        if ($stmt2->fetch()) {
            $product_name = htmlspecialchars($prod_name_db, ENT_QUOTES);
        }
        $stmt2->close();
    }

    // Mail verzenden
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.yourwww.email';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'server@windelsgreen-decoresin.com';
        $mail->Password   = $smtp_pass; // Komt uit ini.inc
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom('no-reply@windelsgreen-decoresin.com', 'Windels Green DecoResin');
        $mail->addAddress('info@windelsgreen-decoresin.com');
        $mail->addReplyTo($email);

        $mail->isHTML(true);
        $mail->Subject = 'Nieuwe productaanvraag: ' . $product_name;
        $mail->Body = '
        <html>
            <body style="font-family: Arial, sans-serif; color: #333;">
                <h2>Nieuwe productaanvraag ontvangen</h2>
                <p><strong>Product ID:</strong> ' . $product_id . '</p>
                <p><strong>Productnaam:</strong> ' . $product_name . '</p>
                <p><strong>Aanvrager e-mail:</strong> ' . htmlspecialchars($email, ENT_QUOTES) . '</p>
                <p><strong>Opmerking / aanvraag:</strong><br>' . nl2br(htmlspecialchars($note, ENT_QUOTES)) . '</p>
                <hr>
                <p>Verzonden op ' . date('d-m-Y H:i:s') . '</p>
            </body>
        </html>
        ';

        $mail->send();

        echo json_encode(['success' => true, 'message' => 'Je aanvraag is succesvol ontvangen. We nemen zo snel mogelijk contact op.']);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Fout bij verzenden mail: ' . $mail->ErrorInfo]);
    }
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Fout bij verwerken aanvraag: ' . $stmt->error]);
}

$stmt->close();
