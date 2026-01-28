<?php
session_start();
include dirname($_SERVER['DOCUMENT_ROOT']) . '/secure/ini.inc';

// Laad Composer autoload
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/vendor/autoload.php';
$logoData = base64_encode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/assets/logo/logo.png'));
$logoSrc = 'data:image/png;base64,' . $logoData;

// Laad .env variabelen
 
use Mpdf\Mpdf;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Dotenv\Dotenv;

   $dotenv = Dotenv::createImmutable(dirname($_SERVER['DOCUMENT_ROOT']));
    $dotenv->load();
    putenv("MAIL_USERNAME={$_ENV['MAIL_USERNAME']}");
    putenv("MAIL_PASSWORD={$_ENV['MAIL_PASSWORD']}");


// Alleen toegankelijk voor ingelogde uitvaartdiensten
if (!isset($_SESSION['partner_id'])) {
    header("Location: /pages/account/login.php");
    exit;
}

// Controleer of formulier verzonden is
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: bestel.php");
    exit;
}

function encryptField($data, $key) {
    $iv = random_bytes(16);
    $cipher = "AES-256-CBC";
    $encrypted = openssl_encrypt($data, $cipher, $key, 0, $iv);
    return base64_encode($iv . $encrypted);
}

function decryptField($data, $key) {
    $cipher = "AES-256-CBC";
    $data = base64_decode($data);
    $iv = substr($data, 0, 16);
    $encrypted = substr($data, 16);
    return openssl_decrypt($encrypted, $cipher, $key, 0, $iv);
}

// Veilig data ophalen
$klant_naam = trim($_POST['klant_naam'] ?? '');
$klant_email = trim($_POST['klant_email'] ?? '');
$klant_telefoon = trim($_POST['klant_telefoon'] ?? '');
$klant_adres = trim($_POST['klant_adres'] ?? '');
$klantnummer_partner = trim($_POST['klantnummer_partner'] ?? '');
$producten = $_POST['producten'] ?? [];

if ($klant_naam === '' || $klant_email === '' || empty($producten)) {
    die("Fout: Naam, e-mail en minstens één product zijn verplicht.");
}

if (!filter_var($klant_email, FILTER_VALIDATE_EMAIL)) {
    die("Ongeldig e-mailadres.");
}
// Functie om uniek ordernummer te maken: ORD-JJJJ-XXXX
function generateOrderNumber($mysqli) {
    $year = date('Y');
    do {
        $rand = str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
        $orderNumber = "ORD-$year-$rand";
        $res = $mysqli->query("SELECT id FROM orders WHERE order_number = '".$mysqli->real_escape_string($orderNumber)."'");
    } while ($res && $res->num_rows > 0);
    return $orderNumber;
}

// Start transactie (zodat alles samen opslaat)
$mysqli->begin_transaction();

try {
    // 1. Maak een orderrecord aan
    $orderNumber = generateOrderNumber($mysqli);
    $partner_id = $_SESSION['partner_id'];

    $stmt = $mysqli->prepare("INSERT INTO orders (funeral_partner_id, order_number, klantnummer_partner) VALUES (?, ?, ?)");
    $stmt->bind_param('iss', $partner_id, $orderNumber, $klantnummer_partner);
    $stmt->execute();
    $order_id = $stmt->insert_id;
    $stmt->close();

    // 2. Voeg klantgegevens toe (alleen zichtbaar voor uitvaartdienst)
    $naam_enc = encryptField($klant_naam, $encryption_key);
    $email_enc = encryptField($klant_email, $encryption_key);
    $tel_enc = encryptField($klant_telefoon, $encryption_key);
    $adres_enc = encryptField($klant_adres, $encryption_key);
    $klantnummer_partner_enc = encryptField($klantnummer_partner, $encryption_key);

    $stmt = $mysqli->prepare("INSERT INTO order_private (order_id, klant_naam, klant_email, klant_telefoon, klant_adres, klantnummer_partner) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param('isssss', $order_id, $naam_enc, $email_enc, $tel_enc, $adres_enc, $klantnummer_partner_enc);
    $stmt->execute();
    $stmt->close();

    // 3. Voeg bestelde producten toe
    $stmt = $mysqli->prepare("INSERT INTO order_products (order_id, product_id, quantity) VALUES (?, ?, ?)");
    $stmt->bind_param('iii', $order_id, $pid, $qty);
    
    foreach ($producten as $product) {
        $pid = (int)$product['id'];
        $qty = max(1, (int)$product['qty']);
        $stmt->execute();
    }
    $stmt->close();

    // Alles goed → commit
    $mysqli->commit();
    
// Uitvaartdienstgegevens
$uitvaart_bedrijf = $_SESSION['bedrijf_naam'] ?? '';
$uitvaart_contact = $_SESSION['contact_naam'] ?? '';
$uitvaart_adres = $_SESSION['adres'] ?? '';
$uitvaart_telefoon = $_SESSION['telefoon'] ?? '';
$uitvaart_email = $_SESSION['email'] ?? '';
$uitvaart_btw = $_SESSION['btw_nummer'] ?? '';
$logoSrc = $_SERVER['DOCUMENT_ROOT'].'/assets/logo/logo.png';
// HTML inhoud pakbon
$html = '
<style>
    body { font-family: sans-serif; font-size: 11pt; }
    h1 { font-size: 18pt; margin-bottom: 0; }
    h3 { font-size: 13pt; margin-top: 30px; }
    p, td { font-size: 11pt; }
    .kop { background-color: #f2f2f2; padding: 6px; font-weight: bold; }
    .orderinfo, .partnerinfo { width: 100%; border-collapse: collapse; margin-top: 10px; }
    .orderinfo td, .partnerinfo td { padding: 6px; border: 1px solid #ccc; }
    .productentabel { width: 100%; border-collapse: collapse; margin-top: 20px; }
    .productentabel th, .productentabel td { border: 1px solid #ccc; padding: 8px; text-align: left; }
    .productentabel th { background-color: #e8e8e8; }
    .logo { max-width: 200px; margin-bottom: 10px; }
</style>

<img src="' . $logoSrc . '" class="logo" alt="Logo">

<h1>Pakbon</h1>

<table class="orderinfo">
    <tr><td class="kop">Ordernummer</td><td>' . htmlspecialchars($orderNumber) . '</td></tr>
    <tr><td class="kop">Datum</td><td>' . date('d/m/Y') . '</td></tr>
</table>

<h3>Uitvaartdienst</h3>
<table class="partnerinfo">
    <tr><td class="kop">Bedrijf</td><td>' . htmlspecialchars($uitvaart_bedrijf) . '</td></tr>
    <tr><td class="kop">Contactpersoon</td><td>' . htmlspecialchars($uitvaart_contact) . '</td></tr>
    <tr><td class="kop">Adres</td><td>' . htmlspecialchars($uitvaart_adres) . '</td></tr>
    <tr><td class="kop">Telefoon</td><td>' . htmlspecialchars($uitvaart_telefoon) . '</td></tr>
    <tr><td class="kop">E-mail</td><td>' . htmlspecialchars($uitvaart_email) . '</td></tr>
    <tr><td class="kop">BTW-nummer</td><td>' . htmlspecialchars($uitvaart_btw) . '</td></tr>
    <tr><td class="kop">Klantnummer bij partner</td><td>' . htmlspecialchars(decryptField($klantnummer_partner_enc, $encryption_key)) . '</td></tr>
</table>

<h3>Bestelde Producten</h3>
<table class="productentabel">
    <tr>
        <th>#</th>
        <th>Product</th>
        <th>Aantal</th>
    </tr>
';

$i = 1;
foreach ($producten as $product) {
    $pid = (int)$product['id'];
    $qty = max(1, (int)$product['qty']);
    $productnaam = null;

    // Eerst in epoxy_products
    $res = $mysqli_medewerkers->query("SELECT title FROM epoxy_products WHERE id = $pid AND sub_category = 'uitvaart'");
    if ($res && $res->num_rows > 0) {
        $row = $res->fetch_assoc();
        $productnaam = $row['title'];
    }

    // Als niet gevonden, probeer kaarsen_products
    if (!$productnaam) {
        $res = $mysqli_medewerkers->query("SELECT title FROM kaarsen_products WHERE id = $pid AND sub_category = 'uitvaart'");
        if ($res && $res->num_rows > 0) {
            $row = $res->fetch_assoc();
            $productnaam = $row['title'];
        }
    }

    if (!$productnaam) {
        $res = $mysqli_medewerkers->query("SELECT title FROM inkoop_products WHERE id = $pid AND sub_category = 'uitvaart'");
        if ($res && $res->num_rows > 0) {
            $row = $res->fetch_assoc();
            $productnaam = $row['title'];
        }
    }

    if (!$productnaam) continue;

    $productnaam = htmlspecialchars($productnaam);
    $html .= "<tr><td>$i</td><td>$productnaam</td><td>$qty</td></tr>";
    $i++;
}

$html .= '</table>';


// Genereer PDF
$pdfPath = $_SERVER['DOCUMENT_ROOT']."/pages/account/orders/pdf/pakbon_$order_id.pdf";
$mpdf = new Mpdf();
$mpdf->WriteHTML($html);
$mpdf->Output($pdfPath, \Mpdf\Output\Destination::FILE);
// Verstuur e-mail met pakbon
$mail = new PHPMailer(true);
try {
    $titlemail = "Nieuwe bestelling: $orderNumber";
    $bodymail = "Beste Medewerker,\n\nEr werd een nieuwe bestelling geplaatst.\nOrdernummer: $orderNumber\n\nDe pakbon vindt u in bijlage.";

    $headermail = "Nieuw Bestelling voor op maat gemaakt";
    $mail->setFrom('info@windelsgreen-decoresin.com', 'Windels Green & Deco Resin');
    $mail->CharSet = 'UTF-8';
    $recipients = [
        "webshop@windelsgreen-decoresin.com",
        "windelsfranky@gmail.com"
    ];
    foreach ($recipients as $rcpt) {
        $mail->addAddress($rcpt);
    }
    $mail->isHTML(true);
    $mail->Subject = "Nieuwe bestelling: $orderNumber";
    $mail->Body = str_replace(
        ['{titlemail}', '{headermail}', '{bodymail}'],
        [$titlemail, $headermail, $bodymail],
        file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/emailtemplate.php')
    );
    $mail->addAttachment($pdfPath);

    $mail->isSMTP();
    $mail->Host = 'smtp-auth.mailprotect.be';     // ← Pas dit aan!
    $mail->SMTPAuth = true;
    $mail->Username = getenv('MAIL_USERNAME');
    $mail->Password = getenv('MAIL_PASSWORD'); // ← Pas dit aan!
    $mail->SMTPSecure = 'ssl';
    $mail->Port = 465;

    $mail->send();
} catch (Exception $e) {
    error_log("Mailfout: " . $mail->ErrorInfo);
}
// Alles is succesvol → redirect naar bevestigingspagina
header("Location: /pages/account/orders/bevestiging.php?order_id=$order_id");
exit;

} catch (Exception $e) {
    $mysqli->rollback();
    die("Fout bij opslaan van bestelling: " . $e->getMessage());
}
?>
