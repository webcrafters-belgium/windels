<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require $_SERVER['DOCUMENT_ROOT'] . '/lib/PHPMailer/PHPMailer.php';
require $_SERVER['DOCUMENT_ROOT'] . '/lib/PHPMailer/SMTP.php';
require $_SERVER['DOCUMENT_ROOT'] . '/lib/PHPMailer/Exception.php';
require $_SERVER['DOCUMENT_ROOT'] . '/lib/fpdf/fpdf.php';
require $_SERVER['DOCUMENT_ROOT'] . '/ini.inc'; // voor bedrijfsgegevens etc.

function verzendFactuurEmail($order) {
    global $conn;

    // Haal order items op
    $stmt = $conn->prepare("SELECT oi.*, p.name AS product_name FROM order_items oi LEFT JOIN products p ON oi.product_id = p.id WHERE order_id = ?");
    $stmt->bind_param("i", $order['id']);
    $stmt->execute();
    $result = $stmt->get_result();

    $items = [];
    while ($row = $result->fetch_assoc()) {
        $items[] = [
            'name'     => $row['product_name'] ?: 'Onbekend product',
            'quantity' => $row['quantity'],
            'price'    => $row['total_price'],
        ];
    }

    // Klantgegevens
    $klant = [
        'naam'     => $order['name'] ?? '',
        'straat'   => $order['street'] . ' ' . $order['number'],
        'postcode' => $order['zipcode'],
        'stad'     => $order['city'],
        'land'     => $order['country'],
        'email'    => $order['email'],
    ];

    // PDF genereren (zoals jouw voorbeeld)
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', '', 11);

    $logo = $_SERVER['DOCUMENT_ROOT'] . '/images/windels-logo.png';
    if (file_exists($logo)) $pdf->Image($logo, 10, 10, 50);

    $pdf->SetXY(130, 10);
    $pdf->SetFont('Arial', '', 10);
    $bedrijfstekst = "Windels Green & Deco\nStationsstraat 1\nBTW: BE0123456789\nTel: 011/123456\nE-mail: info@windelsgreen-decoresin.com";
    $pdf->MultiCell(70, 5, iconv("UTF-8", "Windows-1252//TRANSLIT", $bedrijfstekst), 0, 'R');
    $pdf->Ln(30);

    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(0, 10, "Factuur #" . $order['id'], 0, 1);
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 8, 'Datum: ' . date('d-m-Y', strtotime($order['created_at'])), 0, 1);
    $pdf->Cell(0, 8, 'Totaal bedrag: € ' . number_format($order['total_price'], 2, ',', '.'), 0, 1);
    $pdf->Cell(0, 8, 'Verzendkosten: € ' . number_format($order['shipping_cost'], 2, ',', '.'), 0, 1);
    $pdf->Ln(10);

    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 8, 'Klantgegevens:', 0, 1);
    $pdf->SetFont('Arial', '', 11);
    $pdf->MultiCell(0, 6, iconv("UTF-8", "Windows-1252//TRANSLIT",
        $klant['naam'] . "\n" .
        $klant['straat'] . "\n" .
        $klant['postcode'] . ' ' . $klant['stad'] . "\n" .
        $klant['land'] . "\n" .
        $klant['email']
    ));
    $pdf->Ln(8);

    // Tabel producten
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(100, 8, 'Product', 1);
    $pdf->Cell(30, 8, 'Aantal', 1, 0, 'C');
    $pdf->Cell(40, 8, 'Prijs per stuk', 1, 1, 'R');

    $pdf->SetFont('Arial', '', 12);
    foreach ($items as $p) {
        $pdf->Cell(100, 8, iconv("UTF-8", "Windows-1252//TRANSLIT", $p['name']), 1);
        $pdf->Cell(30, 8, $p['quantity'], 1, 0, 'C');
        $pdf->Cell(40, 8, '€ ' . number_format($p['price'], 2, ',', '.'), 1, 1, 'R');
    }

    // Totaal
    $pdf->Ln(5);
    $pdf->Cell(130);
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(30, 8, 'Totaal:', 0);
    $pdf->Cell(30, 8, '€ ' . number_format($order['total_price'], 2, ',', '.'), 0, 1, 'R');

    $pdf->SetY(-30);
    $pdf->SetFont('Arial', 'I', 8);
    $pdf->Cell(0, 10, iconv("UTF-8", "Windows-1252//TRANSLIT", "Factuur gegenereerd op " . date('d-m-Y')), 0, 1, 'C');

    // PDF opslaan in geheugen
    $pdfOutput = $pdf->Output('', 'S'); // Return as string

    // Mail versturen met PHPMailer
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = $smtp_host;
        $mail->SMTPAuth = true;
        $mail->Username = $smtp_user;
        $mail->Password = $smtp_pass; // ❗ Zet hier je SMTP-wachtwoord
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;

        $mail->setFrom('orders@windelsgreen-decoresin.com', 'Windels Green & Deco');
        $mail->addAddress($order['email'], $order['name']);

        $mail->isHTML(true);
        $mail->Subject = 'Uw factuur van bestelling #' . $order['id'];
        $mail->Body    = "<p>Beste {$order['name']},</p>
                          <p>In de bijlage vindt u de factuur van uw bestelling bij Windels Green & Deco.</p>
                          <p>Met vriendelijke groeten,<br>Windels Green & Deco</p>";
        $mail->addStringAttachment($pdfOutput, 'factuur_' . $order['id'] . '.pdf');

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log('Fout bij verzenden van factuur: ' . $mail->ErrorInfo);
        return false;
    }
}
