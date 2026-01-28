<?php
/* =======================================================================
   BESTAND: /functions/contact/handle_contact.php   (LIVE)
   Doel: zelfde anti-bot checks als nu, maar honeypot -> log naar DB + file
   (ALLEEN mysqli, NOOIT PDO)
   DB: webcraftersbe_private
   ======================================================================= */

require $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require $_SERVER['DOCUMENT_ROOT'] . '/classes/PHPMailer/src/Exception.php';
require $_SERVER['DOCUMENT_ROOT'] . '/classes/PHPMailer/src/PHPMailer.php';
require $_SERVER['DOCUMENT_ROOT'] . '/classes/PHPMailer/src/SMTP.php';

/* ---------------------------------------------------------
   DB helpers (mysqli only)
--------------------------------------------------------- */
function hpEnsureTableLive(): void
{
    global $conn;
    if (!($conn instanceof mysqli)) return;

    @mysqli_select_db($conn, 'webcraftersbe_private');

    static $done = false;
    if ($done) return;

    $conn->query("
        CREATE TABLE IF NOT EXISTS honeypot_events (
            id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
            created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            site VARCHAR(255) NOT NULL,
            path VARCHAR(1024) NOT NULL,
            reason VARCHAR(64) NOT NULL,
            ip VARCHAR(64) NOT NULL,
            user_agent TEXT NOT NULL,
            name VARCHAR(255) NOT NULL DEFAULT '',
            email VARCHAR(255) NOT NULL DEFAULT '',
            post_json LONGTEXT NOT NULL,
            processed TINYINT(1) NOT NULL DEFAULT 0,
            processed_at DATETIME NULL,
            INDEX idx_created_at (created_at),
            INDEX idx_reason (reason),
            INDEX idx_ip (ip),
            INDEX idx_processed (processed)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ");

    $done = true;
}

function hpLogDbLive(string $reason, array $post): void
{
    global $conn;
    if (!($conn instanceof mysqli)) return;

    hpEnsureTableLive();

    $site = (string)($_SERVER['HTTP_HOST'] ?? '');
    $path = (string)($_SERVER['REQUEST_URI'] ?? '');
    $ip   = (string)($_SERVER['REMOTE_ADDR'] ?? '');
    $ua   = (string)($_SERVER['HTTP_USER_AGENT'] ?? '');

    $name  = trim((string)($post['name'] ?? ''));
    $email = trim((string)($post['email'] ?? ''));

    $postJson = json_encode($post, JSON_UNESCAPED_UNICODE);
    if ($postJson === false) $postJson = '{}';

    $stmt = $conn->prepare("
        INSERT INTO honeypot_events (site, path, reason, ip, user_agent, name, email, post_json)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ");
    if (!$stmt) return;

    $stmt->bind_param('ssssssss', $site, $path, $reason, $ip, $ua, $name, $email, $postJson);
    @$stmt->execute();
    @$stmt->close();
}

function hpLogFileLive(array $entry): void
{
    $logFile = $_SERVER['DOCUMENT_ROOT'] . '/logs/contactform-bots.log';
    $dir = dirname($logFile);
    if (!is_dir($dir)) @mkdir($dir, 0755, true);

    @file_put_contents($logFile, json_encode($entry, JSON_UNESCAPED_UNICODE) . PHP_EOL, FILE_APPEND);
}

/* ---------------------------------------------------------
   1. Honeypot
--------------------------------------------------------- */
if (!empty($_POST['company_field'])) {

    $entry = [
        'time' => date('Y-m-d H:i:s'),
        'ip'   => $_SERVER['REMOTE_ADDR'] ?? '',
        'ua'   => $_SERVER['HTTP_USER_AGENT'] ?? '',
        'data' => $_POST
    ];

    hpLogFileLive($entry);
    hpLogDbLive('honeypot_filled', $_POST);

    header("Location: /pages/contact/index.php?error=Formulier+ongeldig");
    exit;
}

/* ---------------------------------------------------------
   2. Minimum invultijd
--------------------------------------------------------- */
$loadTs = (int)($_POST['ts'] ?? 0);
if ($loadTs <= 0 || (time() - $loadTs < 3)) {
    // optioneel loggen:
    // hpLogDbLive('too_fast', $_POST);
    header("Location: /pages/contact/index.php?error=Formulier+ongeldig");
    exit;
}

/* ---------------------------------------------------------
   3. IP rate limit
--------------------------------------------------------- */
$ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
$rateFile = "/tmp/formrate-" . $ip;

$count = file_exists($rateFile) ? (int)file_get_contents($rateFile) : 0;

if ($count > 8) {
    // optioneel loggen:
    // hpLogDbLive('rate_limited', $_POST);
    header("Location: /pages/contact/index.php?error=Te+veel+aanvragen");
    exit;
}
file_put_contents($rateFile, (string)($count + 1));

/* ---------------------------------------------------------
   4. Input controleren
--------------------------------------------------------- */
$name    = trim($_POST['name'] ?? '');
$email   = trim($_POST['email'] ?? '');
$message = trim($_POST['message'] ?? '');

if ($name === '' || $email === '' || $message === '') {
    header("Location: /pages/contact/index.php?error=Alle+velden+invullen");
    exit;
}

/* ---------------------------------------------------------
   5. Unicode / Cyrillic filtering
--------------------------------------------------------- */
if (preg_match('/[\p{Cyrillic}]/u', $message)) {
    header("Location: /pages/contact/index.php?error=Bericht+geweigerd");
    exit;
}

if (!preg_match('/^[\p{Latin}\p{N}\p{P}\p{Z}\r\n]+$/u', $message)) {
    header("Location: /pages/contact/index.php?error=Bericht+ongeldig");
    exit;
}

/* ---------------------------------------------------------
   6. SMTP instellingen
   (Tip: zet smtp_pass in ini.inc/env, niet hardcoded)
--------------------------------------------------------- */
$smtp_host = 'mail.webcrafters.be';
$smtp_user = 'noreply@mailout.windelsgreen-decoresin.com';
$smtp_pass = 'liNDEW,;,32';

function setupMailer($host, $user, $pass) {
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host       = $host;
    $mail->SMTPAuth   = true;
    $mail->Username   = $user;
    $mail->Password   = $pass;
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;
    $mail->CharSet    = 'UTF-8';
    return $mail;
}

/* ---------------------------------------------------------
   7. Mail naar eigenaar
--------------------------------------------------------- */
try {
    $mailOwner = setupMailer($smtp_host, $smtp_user, $smtp_pass);
    $mailOwner->setFrom($smtp_user, 'Website Contactformulier');
    $mailOwner->addReplyTo($email);
    $mailOwner->addAddress('info@windelsgreen-decoresin.com');

    $mailOwner->isHTML(true);
    $mailOwner->Subject = "Nieuw bericht van $name via website";
    $mailOwner->Body =
        "<strong>Naam:</strong> " . htmlspecialchars($name, ENT_QUOTES, 'UTF-8') . "<br>" .
        "<strong>Email:</strong> " . htmlspecialchars($email, ENT_QUOTES, 'UTF-8') . "<br>" .
        "<strong>Bericht:</strong><br>" . nl2br(htmlspecialchars($message, ENT_QUOTES, 'UTF-8'));

    $mailOwner->send();

} catch (Exception $e) {
    header("Location: /pages/contact/index.php?error=Beheerder+mail+mislukt");
    exit;
}

/* ---------------------------------------------------------
   8. Bevestiging naar gebruiker
--------------------------------------------------------- */

// --- Mooie HTML mail template (header + footer + styles) ---
// Gebruik: $mailUser->Body = buildUserConfirmationEmail($name, $message);

function buildUserConfirmationEmail(string $name, string $message): string
{
    $safeName = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
    $safeMsg  = nl2br(htmlspecialchars($message, ENT_QUOTES, 'UTF-8'));

    $year = date('Y');
    $logo = 'https://windelsgreen-decoresin.com/images/windels-logo.svg';

    return <<<HTML
<!doctype html>
<html lang="nl">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <meta name="x-apple-disable-message-reformatting">
  <title>We hebben je bericht ontvangen</title>
  <style>
    /* --- Reset + email-safe basics --- */
    html,body{margin:0!important;padding:0!important;height:100%!important;width:100%!important}
    *{-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%}
    table,td{mso-table-lspace:0pt!important;mso-table-rspace:0pt!important}
    img{-ms-interpolation-mode:bicubic;border:0;outline:none;text-decoration:none;display:block}
    a{text-decoration:none}
    /* --- Design tokens --- */
    .bg{background:#0b1220}
    .card{background:#ffffff;border-radius:18px;overflow:hidden}
    .shadow{box-shadow:0 18px 45px rgba(0,0,0,.28)}
    .text{font-family:Arial,Helvetica,sans-serif;color:#1f2937}
    .muted{color:#6b7280}
    .btn{display:inline-block;background:#3c8c72;color:#ffffff!important;padding:12px 18px;border-radius:12px;font-weight:bold}
    .pill{display:inline-block;background:#f3f4f6;color:#111827;padding:8px 10px;border-radius:12px;font-size:13px;line-height:1.35}
    .hr{height:1px;background:#e5e7eb}
    /* --- Mobile --- */
    @media (max-width:600px){
      .container{width:100%!important}
      .pad{padding:18px!important}
      .h1{font-size:22px!important}
    }
  </style>
</head>
<body class="bg" style="background:#0b1220;margin:0;padding:0;">
  <!-- Preheader (onzichtbaar, maar handig in inbox preview) -->
  <div style="display:none;max-height:0;overflow:hidden;opacity:0;color:transparent;">
    We hebben je bericht ontvangen — we reageren zo snel mogelijk.
  </div>

  <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#0b1220;">
    <tr>
      <td align="center" style="padding:28px 14px;">
        <table role="presentation" class="container" width="600" cellpadding="0" cellspacing="0" style="width:600px;max-width:600px;">
          <!-- Topbar -->
          <tr>
            <td style="padding:0 0 14px 0;">
              <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                <tr>
                  <td align="left" style="padding:0 6px;">
                    <span class="text muted" style="font-family:Arial,Helvetica,sans-serif;color:#b6c0d1;font-size:13px;">
                      Windels Green &amp; Deco Resin
                    </span>
                  </td>
                  <td align="right" style="padding:0 6px;">
                    <span class="text muted" style="font-family:Arial,Helvetica,sans-serif;color:#b6c0d1;font-size:13px;">
                      {$year}
                    </span>
                  </td>
                </tr>
              </table>
            </td>
          </tr>

          <!-- Card -->
          <tr>
            <td class="card shadow" style="background:#ffffff;border-radius:18px;overflow:hidden;box-shadow:0 18px 45px rgba(0,0,0,.28);">
              <!-- Header -->
              <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                <tr>
                  <td class="pad" style="padding:22px 26px;background:linear-gradient(135deg,#2f6b59 0%,#3c8c72 55%,#f4c96b 140%);">
                    <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                      <tr>
                        <td align="left" style="vertical-align:middle;">
                          <!-- Logo -->
                          <img src="{$logo}" width="140" alt="Windels Green &amp; Deco Resin" style="display:block;width:140px;height:auto;">
                        </td>
                        <td align="right" style="vertical-align:middle;">
                          <span class="text" style="font-family:Arial,Helvetica,sans-serif;color:#ffffff;font-size:12px;opacity:.92;">
                            Bevestiging contactformulier
                          </span>
                        </td>
                      </tr>
                    </table>
                  </td>
                </tr>
              </table>

              <!-- Body -->
              <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                <tr>
                  <td class="pad text" style="padding:26px;font-family:Arial,Helvetica,sans-serif;color:#1f2937;">
                    <div class="h1" style="font-size:26px;font-weight:800;line-height:1.25;margin:0 0 10px 0;">
                      Bedankt, {$safeName}!
                    </div>
                    <p style="margin:0 0 14px 0;font-size:15px;line-height:1.6;">
                      We hebben je bericht goed ontvangen. We nemen zo snel mogelijk contact met je op.
                    </p>

                    <div class="hr" style="height:1px;background:#e5e7eb;margin:18px 0;"></div>

                    <div style="font-size:14px;font-weight:700;margin:0 0 10px 0;">Jouw bericht</div>
                    <div class="pill" style="display:inline-block;background:#f3f4f6;color:#111827;padding:12px 14px;border-radius:12px;font-size:13px;line-height:1.55;width:100%;box-sizing:border-box;">
                      {$safeMsg}
                    </div>

                    <div style="margin:18px 0 0 0;">
                      <a class="btn" href="https://windelsgreen-decoresin.com" style="display:inline-block;background:#3c8c72;color:#ffffff!important;padding:12px 18px;border-radius:12px;font-weight:700;">
                        Bezoek onze webshop
                      </a>
                    </div>

                    <p class="muted" style="margin:18px 0 0 0;font-size:13px;line-height:1.5;color:#6b7280;">
                      Tip: antwoord gerust op deze mail als je nog extra info wil toevoegen.
                    </p>
                  </td>
                </tr>
              </table>

              <!-- Footer -->
              <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                <tr>
                  <td style="padding:0 26px 18px 26px;">
                    <div class="hr" style="height:1px;background:#e5e7eb;"></div>
                  </td>
                </tr>
                <tr>
                  <td class="pad text" style="padding:0 26px 24px 26px;font-family:Arial,Helvetica,sans-serif;color:#1f2937;">
                    <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                      <tr>
                        <td class="muted" style="font-size:13px;color:#6b7280;line-height:1.5;">
                          <strong style="color:#111827;">Windels Green &amp; Deco Resin</strong><br>
                          Beukenlaan 8, Hamont-Achel, België<br>
                          <a href="mailto:info@windelsgreen-decoresin.com" style="color:#3c8c72;">info@windelsgreen-decoresin.com</a>
                        </td>
                        <td align="right" class="muted" style="font-size:13px;color:#6b7280;line-height:1.5;">
                          <a href="https://windelsgreen-decoresin.com/pages/contact/" style="color:#3c8c72;">Contact</a><br>
                          <a href="https://windelsgreen-decoresin.com" style="color:#3c8c72;">Website</a>
                        </td>
                      </tr>
                    </table>
                    <div class="muted" style="margin-top:14px;font-size:12px;color:#9ca3af;line-height:1.5;">
                      © {$year} Windels Green &amp; Deco Resin. Dit is een automatische bevestiging.
                    </div>
                  </td>
                </tr>
              </table>
            </td>
          </tr>

          <!-- Small disclaimer -->
          <tr>
            <td align="center" style="padding:14px 10px 0 10px;">
              <div class="muted" style="font-family:Arial,Helvetica,sans-serif;color:#8b96aa;font-size:12px;line-height:1.5;">
                Ontving je deze mail per ongeluk? Dan mag je dit bericht negeren.
              </div>
            </td>
          </tr>

        </table>
      </td>
    </tr>
  </table>
</body>
</html>
HTML;
}

/* ---------------------------------------------------------
   Gebruik in je bestaande try/catch
--------------------------------------------------------- */
try {
    $mailUser = setupMailer($smtp_host, $smtp_user, $smtp_pass);
    $mailUser->setFrom($smtp_user, 'Windels Green & Deco Resin');
    $mailUser->addAddress($email);
    $mailUser->addReplyTo('info@windelsgreen-decoresin.com');

    $mailUser->isHTML(true);
    $mailUser->Subject = "We hebben je bericht ontvangen";
    $mailUser->Body    = buildUserConfirmationEmail($name, $message);

    // Optional: plain-text fallback (altijd nice)
    $mailUser->AltBody =
        "Beste {$name},\n\n" .
        "Dank voor je bericht. We nemen spoedig contact op.\n\n" .
        "Jouw bericht:\n{$message}\n\n" .
        "Met vriendelijke groeten,\nWindels Green & Deco Resin\n" .
        "https://windelsgreen-decoresin.com";

    $mailUser->send();

} catch (Exception $e) {
    header("Location: /pages/contact/index.php?error=Bevestiging+mislukt");
    exit;
}

header("Location: /pages/contact/index.php?success=1");
exit;
