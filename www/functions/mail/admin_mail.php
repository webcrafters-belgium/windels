<?php
// ADMIN MAIL

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/PHPMailer/PHPMailer.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/PHPMailer/SMTP.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/PHPMailer/Exception.php';

require_once $_SERVER['DOCUMENT_ROOT'] . '/admin/pages/orders/_pdf_lib.php'; // nieuwe PDF library

function sendAdminMail(int $orderId, float $totalPrice, float $shippingCost, ?string $pdfFactuurPath = null, ?string $xmlFactuurPath = null): void
{
    global $conn, $smtp_host, $smtp_user, $smtp_pass;

    try {
        // Order en items ophalen via library
        [$order, $items] = fetchOrderAndItems($conn, $orderId);
    } catch (\RuntimeException $e) {
        error_log("❌ Admin mail error: " . $e->getMessage());
        return;
    }

    // Tijdelijke PDF-paden
    $tempDir = $_SERVER['DOCUMENT_ROOT'] . '/temp';
    if (!is_dir($tempDir)) mkdir($tempDir, 0775, true);

    $pdfFactuurPath = $pdfFactuurPath ?? $tempDir . "/factuur_{$orderId}.pdf";
    $pdfPakbonPath  = $tempDir . "/pakbon_{$orderId}.pdf";
    $xmlFactuurPath = $xmlFactuurPath ?? $tempDir . "/factuur_{$orderId}.xml";

    // Maak PDF's via library-functies
    if (!file_exists($pdfFactuurPath)) {
        file_put_contents($pdfFactuurPath, buildInvoicePdfString($order, $items));
    }
    file_put_contents($pdfPakbonPath, buildPackingPdfString($order, $items));
    if (!file_exists($xmlFactuurPath)) {
        file_put_contents($xmlFactuurPath, buildInvoiceXmlString($order, $items));
    }

    try {
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host       = $smtp_host;
        $mail->SMTPAuth   = true;
        $mail->Username   = $smtp_user;
        $mail->Password   = $smtp_pass;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;
        $mail->CharSet    = 'UTF-8';

        $mail->setFrom('orders@windelsgreen-decoresin.com', 'Webshop Admin');
        $mail->addAddress('webshop@windelsgreen-decoresin.com');
        $mail->addBCC('matthias@webcrafters.be');

        $mail->Subject = "Nieuwe bestelling ontvangen #{$orderId}";
        $mail->isHTML(true);

        $mail->Body = "
            <h3>Bestelling #{$orderId}</h3>
            <p>Er is een nieuwe bestelling geplaatst ter waarde van 
            <strong>€" . number_format($totalPrice, 2, ',', '.') . "</strong>.</p>
            <p>Klant: <strong>" . htmlspecialchars($order['name']) . "</strong><br>
            Email: <strong>" . htmlspecialchars($order['email']) . "</strong><br>
            Telefoon: <strong>" . htmlspecialchars($order['phone']) . "</strong></p>
        ";

        // Voeg PDF's toe
        $mail->addAttachment($pdfFactuurPath, "Factuur_Order_{$orderId}.pdf");
        $mail->addAttachment($pdfPakbonPath, "Pakbon_Order_{$orderId}.pdf");
        if (file_exists($xmlFactuurPath)) {
            $mail->addAttachment($xmlFactuurPath, "Factuur_Order_{$orderId}.xml");
        }

        $mail->send();

    } catch (Exception $e) {
        error_log("❌ Admin mail error: " . $e->getMessage());
    } finally {
        // Verwijder tijdelijke bestanden
        if (file_exists($pdfPakbonPath)) unlink($pdfPakbonPath);
        if (file_exists($pdfFactuurPath) && $pdfFactuurPath === ($tempDir . "/factuur_{$orderId}.pdf")) {
            unlink($pdfFactuurPath);
        }
        if (file_exists($xmlFactuurPath) && $xmlFactuurPath === ($tempDir . "/factuur_{$orderId}.xml")) {
            unlink($xmlFactuurPath);
        }
    }
}
