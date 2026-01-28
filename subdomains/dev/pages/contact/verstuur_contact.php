<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(dirname($_SERVER['DOCUMENT_ROOT']));
$dotenv->load();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /pages/contact/contact.php');
    exit;
}

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$message = trim($_POST['message'] ?? '');

if ($name === '' || $email === '' || $message === '') {
    die('Gelieve alle verplichte velden in te vullen.');
}

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host = 'smtp-auth.mailprotect.be';
    $mail->SMTPAuth = true;
    $mail->Username = $_ENV['MAIL_USERNAME'];
    $mail->Password = $_ENV['MAIL_PASSWORD'];
    $mail->SMTPSecure = 'ssl';
    $mail->Port = 465;

    $mail->setFrom('info@windelsgreen-decoresin.com', 'Website Contactformulier');
    $mail->addAddress('info@windelsgreen-decoresin.com');
    $mail->addCC($email);
    $mail->CharSet = 'UTF-8';
    $mail->isHTML(true);

    $titlemail = 'Nieuw contactbericht van ' . htmlspecialchars($name);
    $headermail = 'Nieuw bericht via het contactformulier';
    $bodymail = "<p><strong>Naam:</strong> " . htmlspecialchars($name) . "</p>" .
                "<p><strong>E-mail:</strong> " . htmlspecialchars($email) . "</p>" .
                ($phone !== '' ? "<p><strong>Telefoon:</strong> " . htmlspecialchars($phone) . "</p>" : '') .
                "<p><strong>Bericht:</strong><br>" . nl2br(htmlspecialchars($message)) . "</p>";

    $mail->Subject = $titlemail;
    $mail->Body = str_replace(
        ['{titlemail}', '{headermail}', '{bodymail}'],
        [$titlemail, $headermail, $bodymail],
        file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/emailtemplate.php')
    );

    $mail->send();
    header('Location: contact_bedankt.php');
    exit;
} catch (Exception $e) {
    error_log('Contactformulier fout: ' . $mail->ErrorInfo);
    die('Er is een fout opgetreden bij het verzenden van uw bericht. Probeer later opnieuw.');
}
?>
