<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/PHPMailer/PHPMailer.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/PHPMailer/SMTP.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/PHPMailer/Exception.php';

function sendConfirmationEmail(
    int $orderId,
    float $totalPrice,
    float $shippingCost,
    string $customerEmail,
    string $pdfPath
): void {
    global $smtp_host, $smtp_user, $smtp_pass,
           $conn, $bedrijfsnaam, $bedrijfsadres,
           $bedrijfsBTWnr, $bedrijfsemail, $bedrijfstelefoon;

    // ───── Order ophalen ─────
    $stmt = $conn->prepare("
        SELECT id, name, email, phone,
               shipping_method, payment_method,
               shipment_id, tracking_code, tracking_url,
               created_at
        FROM orders
        WHERE id = ?
    ");
    $stmt->bind_param("i", $orderId);
    $stmt->execute();
    $order = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$order) {
        error_log("Order {$orderId} niet gevonden voor klantmail");
        return;
    }

    // ───── Tracking info ─────
    $trackingHtml = '';
    if (!empty($order['tracking_url'])) {
        $trackingHtml = '<p>U kunt uw bestelling volgen via: 
            <a href="' . htmlspecialchars($order['tracking_url']) . '">Track & Trace</a></p>';
    } elseif (!empty($order['tracking_code'])) {
        $trackingHtml = '<p>Uw trackingcode: ' . htmlspecialchars($order['tracking_code']) . '</p>';
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

        // Adressen
        $mail->setFrom('orders@windelsgreen-decoresin.com', 'Windels Green & Deco Resin');
        $mail->addAddress($customerEmail);
        $mail->addBCC('matthias@webcrafters.be');
        $mail->addBCC('webshop@windelsgreen-decoresin.com');

        // Logo inbedden
        $mail->addEmbeddedImage($_SERVER['DOCUMENT_ROOT'].'/images/logo.png', 'logo_cid', 'logo.png');

        // Onderwerp
        $mail->Subject = "Bevestiging bestelling #{$orderId}";

        // HTML body
        $body = '
        <!DOCTYPE html><html lang="nl"><head><meta charset="utf-8">
        <style>/* je CSS hier */</style></head><body>
        <div style="max-width:600px;margin:0 auto;font-family:Arial,sans-serif;color:#333;">
          <div style="text-align:center;">
            <img src="cid:logo_cid" alt="Windels Green & Deco Resin" style="max-height:60px;">
            <h2 style="color:#2f855a;">Bedankt voor je bestelling #'.htmlspecialchars($orderId).'</h2>
          </div>
          <p>Beste ' . htmlspecialchars($order['name']) . ',</p>
          <p>Wij hebben je bestelling ontvangen en de betaling van <strong>&euro;'.number_format($totalPrice, 2, ',', '.').'</strong> is verwerkt.</p>
          <p><strong>Betaalmethode:</strong> '.htmlspecialchars($order['payment_method'] ?? '-').'<br>
             <strong>Verzendmethode:</strong> '.htmlspecialchars($order['shipping_method'] ?? '-').'</p>
          '.$trackingHtml.'
          <p><strong>Verzendkosten:</strong> &euro;'.number_format($shippingCost, 2, ',', '.').'</p>
          <p><strong>Totaal:</strong> &euro;'.number_format($totalPrice, 2, ',', '.').'</p>
          <p>De factuur vind je in de bijlage.</p>
          <p>Met vriendelijke groet,<br>Team Windels Green & Deco Resin</p>
          <hr>
          <p style="font-size:0.9em;color:#666;text-align:center;">
            '.nl2br(htmlspecialchars($bedrijfsnaam)).'<br>
            '.nl2br(htmlspecialchars($bedrijfsadres)).'<br>
            BTW: '.htmlspecialchars($bedrijfsBTWnr).'<br>
            Tel: '.htmlspecialchars($bedrijfstelefoon).'<br>
            E-mail: '.htmlspecialchars($bedrijfsemail).'
          </p>
        </div></body></html>';

        // Bijlage
        if (file_exists($pdfPath)) {
            $mail->addAttachment($pdfPath, "Factuur_Order_{$orderId}.pdf");
        }

        $mail->isHTML(true);
        $mail->Body    = $body;
        $mail->AltBody = "Bedankt voor bestelling #{$orderId}\n"
            ."Betaalmethode: ".($order['payment_method'] ?? '-')."\n"
            ."Verzendmethode: ".($order['shipping_method'] ?? '-')."\n"
            ."Verzendkosten: €".number_format($shippingCost,2,',','.')."\n"
            ."Totaal: €".number_format($totalPrice,2,',','.');

        $mail->send();
    } catch (Exception $e) {
        error_log("Mailer error (klant) order {$orderId}: ".$e->getMessage());
    }
}

