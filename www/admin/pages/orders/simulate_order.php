<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';

require_once $_SERVER['DOCUMENT_ROOT'] . '/classes/PHPMailer/src/PHPMailer.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/classes/PHPMailer/src/SMTP.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/classes/PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function logLine(string $msg): void {
    echo '<div style="font-family: monospace; padding:4px;">' . htmlspecialchars($msg) . '</div>';
}

function simulateOrder(int $userId = null): int
{
    global $conn;

    logLine("▶ Start simulateOrder()");

    // Dummy klantgegevens
    $name = "Test Klant";
    $street = "Teststraat";
    $number = "1";
    $zipcode = "1000";
    $city = "Brussel";
    $country = "BE";
    $phone = "0123456789";
    $email = "test@example.com";

    $shipping_cost = 0.00;
    $created_at = date('Y-m-d H:i:s');

    // Order aanmaken
    $stmt = $conn->prepare("
        INSERT INTO orders 
        (user_id, name, street, number, zipcode, city, country, phone, email, shipping_cost, total_price, status, created_at)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'paid', ?)
    ");
    $dummyTotal = 0.00;
    $stmt->bind_param(
        "issssssssdds",
        $userId,
        $name,
        $street,
        $number,
        $zipcode,
        $city,
        $country,
        $phone,
        $email,
        $shipping_cost,
        $dummyTotal,
        $created_at
    );
    $stmt->execute();
    $orderId = $stmt->insert_id;
    $stmt->close();

    logLine("✔ Order aangemaakt (ID: {$orderId})");

    // Dummy product
    $product = $conn->query("SELECT id, price, name FROM products LIMIT 1")->fetch_assoc();
    if (!$product) {
        throw new Exception("Geen product in database.");
    }

    $quantity = 2;
    $total_price = $product['price'] * $quantity;

    $stmt = $conn->prepare("
        INSERT INTO order_items (order_id, product_id, quantity, total_price)
        VALUES (?, ?, ?, ?)
    ");
    $stmt->bind_param("iiid", $orderId, $product['id'], $quantity, $total_price);
    $stmt->execute();
    $stmt->close();

    logLine("✔ Order item toegevoegd ({$product['name']} x {$quantity})");

    $total = $total_price + $shipping_cost;
    $stmt = $conn->prepare("UPDATE orders SET total_price = ? WHERE id = ?");
    $stmt->bind_param("di", $total, $orderId);
    $stmt->execute();
    $stmt->close();

    logLine("✔ Order totaal bijgewerkt (€" . number_format($total, 2) . ")");

    // MAIL VERZENDEN
    sendOrderMail($orderId, $name, $email, $total);

    return $orderId;
}

function sendOrderMail(int $orderId, string $customerName, string $customerEmail, float $total): void
{
    global $smtp_host, $smtp_user, $smtp_pass, $smtp_port;
    logLine("▶ Start mailverzending");

    $mail = new PHPMailer(true);

    try {
        // SMTP config
        $mail->isSMTP();
        $mail->Host       = $smtp_host;
        $mail->SMTPAuth   = true;
        $mail->Username   = $smtp_user;
        $mail->Password   = $smtp_pass;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = $smtp_port;

        $mail->setFrom('info@windelsgreen-decoresin.com', 'Windels Green & Deco Resin');

        // Ontvangers
        $mail->addAddress($customerEmail, $customerName);
        $mail->addBCC('info@windelsgreen-decoresin.com');

        $mail->isHTML(true);
        $mail->Subject = "Bevestiging van je bestelling (#{$orderId})";

        $mail->Body = "
            <h2>Bedankt voor je bestelling</h2>
            <p>Hallo {$customerName},</p>
            <p>Je bestelling <strong>#{$orderId}</strong> werd succesvol geplaatst.</p>
            <p><strong>Totaal:</strong> €" . number_format($total, 2, ',', '.') . "</p>
            <p>Groeten,<br>Windels Green & Deco Resin</p>
        ";

        $mail->AltBody = "Bestelling #{$orderId} bevestigd. Totaal: €" . number_format($total, 2);

        $mail->send();
        logLine("✔ Mail verzonden naar klant + info@");

    } catch (Exception $e) {
        logLine("✖ Mailfout: " . $mail->ErrorInfo);
    }
}

// TEST
try {
    $newOrderId = simulateOrder(1);
    logLine("🎉 Testorder klaar (ID {$newOrderId})");
} catch (Exception $e) {
    logLine("❌ Fout: " . $e->getMessage());
}