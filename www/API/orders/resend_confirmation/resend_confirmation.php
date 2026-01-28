<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';
require $_SERVER['DOCUMENT_ROOT'] . '/lib/PHPMailer/PHPMailer.php';
require $_SERVER['DOCUMENT_ROOT'] . '/lib/PHPMailer/SMTP.php';
require $_SERVER['DOCUMENT_ROOT'] . '/lib/PHPMailer/Exception.php';
require $_SERVER['DOCUMENT_ROOT'] . '/templates/emails/order_confirmation_template.php'; // ✅ TEMPLATE

$orderId = isset($_GET['order_id']) ? (int)$_GET['order_id'] : 0;

if (!$orderId) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Ongeldige aanvraag.']);
    exit;
}

// Bestelling ophalen
$stmt = $conn->prepare("SELECT email, total_price, shipping_cost FROM orders WHERE id = ?");
$stmt->bind_param("i", $orderId);
$stmt->execute();
$result = $stmt->get_result();
$order = $result->fetch_assoc();
$stmt->close();

if (!$order) {
    echo json_encode(['success' => false, 'message' => 'Bestelling niet gevonden.']);
    exit;
}

$email         = $order['email'] ?? null;
$totalPrice    = (float) $order['total_price'];
$shippingCost  = (float) $order['shipping_cost'];

if (!$email) {
    echo json_encode(['success' => false, 'message' => 'Geen e-mailadres gevonden.']);
    exit;
}

$mail = new PHPMailer(true);

try {
    // SMTP-instellingen
    $mail->isSMTP();
    $mail->Host       = $smtp_host;
    $mail->SMTPAuth   = true;
    $mail->Username   = $smtp_user;
    $mail->Password   = $smtp_pass;
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;
    $mail->CharSet    = 'UTF-8';

    // Ontvangers
    $mail->setFrom('orders@windelsgreen-decoresin.com', 'Windels Green & Deco Resin');
    $mail->addAddress($email); // klant
    $mail->addBCC('webshop@windelsgreen-decoresin.com');
    $mail->addBCC('matthias@webcrafters.be');

    // Logo embedden
    $mail->addEmbeddedImage($_SERVER['DOCUMENT_ROOT'].'/images/logo.png', 'logo_cid', 'logo.png');

    // HTML body via template
    $mail->Subject = "Bevestiging bestelling #{$orderId}";
    $mail->isHTML(true);
    $mail->Body    = renderOrderConfirmationEmail($orderId, $totalPrice, $shippingCost, $conn, $mail);
    $mail->AltBody = "Bedankt voor je bestelling #{$orderId}\nTotaal: €" . number_format($totalPrice, 2, ',', '.');

    $mail->send();

    echo json_encode(['success' => true, 'message' => '✅ Orderbevestiging opnieuw verzonden naar ' . $email]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Fout bij verzenden: ' . $mail->ErrorInfo]);
}
