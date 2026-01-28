<?php
session_start();

require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/PHPMailer/PHPMailer.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/PHPMailer/SMTP.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/PHPMailer/Exception.php';

require $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Alleen POST toegestaan.']);
    exit;
}

// Valideer e-mail
$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$message = trim($_POST['message'] ?? '');

if (!$email || empty($message)) {
    echo json_encode(['success' => false, 'message' => 'Ongeldig formulier.']);
    exit;
}

$token = bin2hex(random_bytes(16));
$screenshotPath = '';

// Screenshot upload en validatie
if (isset($_FILES['screenshot']) && $_FILES['screenshot']['error'] === UPLOAD_ERR_OK) {
    // Check MIME-type afbeelding
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $_FILES['screenshot']['tmp_name']);
    finfo_close($finfo);

    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    if (!in_array($mimeType, $allowedTypes)) {
        echo json_encode(['success' => false, 'message' => 'Ongeldig bestandstype. Alleen jpg, png, gif toegestaan.']);
        exit;
    }

    $tempDir = $_SERVER['DOCUMENT_ROOT'] . '/images/pending_attachments/';
    if (!file_exists($tempDir) && !mkdir($tempDir, 0777, true)) {
        echo json_encode(['success' => false, 'message' => 'Kan uploadmap niet aanmaken.']);
        exit;
    }

    $ext = pathinfo($_FILES['screenshot']['name'], PATHINFO_EXTENSION);
    $filename = $token . '.' . $ext;
    $filepath = $tempDir . $filename;

    if (!move_uploaded_file($_FILES['screenshot']['tmp_name'], $filepath)) {
        echo json_encode(['success' => false, 'message' => 'Fout bij opslaan van screenshot.']);
        exit;
    }

    $screenshotPath = '/images/pending_attachments/' . $filename;
}

// Insert in database met foutafhandeling
$stmt = $conn->prepare("INSERT INTO error_reports (email, message, screenshot_path, token) VALUES (?, ?, ?, ?)");
if (!$stmt) {
    error_log("Prepare failed: " . $conn->error);
    echo json_encode(['success' => false, 'message' => 'Databasefout.']);
    exit;
}

if (!$stmt->bind_param("ssss", $email, $message, $screenshotPath, $token)) {
    error_log("Bind failed: " . $stmt->error);
    echo json_encode(['success' => false, 'message' => 'Databasefout.']);
    exit;
}

if (!$stmt->execute()) {
    error_log("Execute failed: " . $stmt->error);
    echo json_encode(['success' => false, 'message' => 'Databasefout.']);
    exit;
}

$stmt->close();

// PHPMailer verzenden
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

    $mail->setFrom('orders@windelsgreen-decoresin.com', 'Windels Green & Deco Resin');
    $mail->addAddress($email);
    $mail->addBCC('matthias@webcrafters.be');
    $mail->addBCC('webshop@windelsgreen-decoresin.com');

    $verifyLink = "https://windelsgreen-decoresin.com/API/dev/verify_problem.php?token=$token";

    $mail->Subject = 'Bevestig je probleemmelding';
    $mail->Body    = "Hallo,\n\nWe hebben je melding ontvangen:\n\n\"$message\"\n\nKlik op onderstaande link om dit te bevestigen:\n$verifyLink\n\nMet vriendelijke groet,\nWindelsgreen ontwikkelaar";

    $mail->send();

    echo json_encode(['success' => true, 'message' => 'Bevestigingsmail verzonden.']);
} catch (Exception $e) {
    error_log('Mailer Error: ' . $mail->ErrorInfo);
    echo json_encode(['success' => false, 'message' => 'Fout bij verzenden van bevestigingsmail.']);
    exit;
}
