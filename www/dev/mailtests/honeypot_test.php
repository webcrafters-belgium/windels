<?php
/* =========================================================
   BESTAND: /dev/mailtests/honeypot_test.php
   Doel: lokaal/dev je contactformulier-anti-bot checks testen
   ========================================================= */

declare(strict_types=1);

require $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


function hpEnsureTable(): void
{
    global $conn;
    if (!($conn instanceof mysqli)) return;

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

function hpLogDb(string $reason, array $post, array $server): void
{
    global $conn;
    if (!($conn instanceof mysqli)) return;

    hpEnsureTable();

    $site = (string)($server['HTTP_HOST'] ?? ($_SERVER['HTTP_HOST'] ?? ''));
    $path = (string)($server['REQUEST_URI'] ?? ($_SERVER['REQUEST_URI'] ?? ''));
    $ip   = (string)($server['REMOTE_ADDR'] ?? ($_SERVER['REMOTE_ADDR'] ?? ''));
    $ua   = (string)($server['HTTP_USER_AGENT'] ?? ($_SERVER['HTTP_USER_AGENT'] ?? ''));

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

/* ---------------------------------------------------------
   LOG
--------------------------------------------------------- */
function log_honeypot_hit(array $post, array $server): void
{
    $logFile = $_SERVER['DOCUMENT_ROOT'] . '/logs/contactform-bots.log';

    // Zorg dat de map bestaat (handig op dev)
    $dir = dirname($logFile);
    if (!is_dir($dir)) {
        @mkdir($dir, 0755, true);
    }

    $entry = [
        'time' => date('Y-m-d H:i:s'),
        'ip'   => $server['REMOTE_ADDR'] ?? '',
        'ua'   => $server['HTTP_USER_AGENT'] ?? '',
        'data' => $post
    ];

    @file_put_contents($logFile, json_encode($entry, JSON_UNESCAPED_UNICODE) . PHP_EOL, FILE_APPEND);

    // ✅ ook naar DB
    hpLogDb('honeypot_filled', $post, $server);
}


function validate_contact_submission(array $post, array $server, string &$reason): bool
{
    // 1) Honeypot
    if (!empty($post['company_field'] ?? '')) {
        log_honeypot_hit($post, $server); // ✅ file + DB log
        $reason = 'honeypot_filled';
        return false;
    }

    // 2) Minimum invultijd
    $loadTs = (int)($post['ts'] ?? 0);
    if ($loadTs <= 0) {
        $reason = 'missing_ts';
        return false;
    }
    if (time() - $loadTs < 3) {
        $reason = 'too_fast';
        return false;
    }

    // 3) IP rate limit (test-scope in /tmp, zodat je live ratefiles niet raakt)
    $ip = (string)($server['REMOTE_ADDR'] ?? '127.0.0.1');

    $whitelist = ['192.168.128.1', '192.168.129.19', '84.195.3.214'];

    if (!in_array($ip, $whitelist, true)) {

        // veilige sleutel voor bestandsnaam (werkt ook met IPv6)
        $ipKey = substr(hash('sha256', $ip), 0, 16);

        // test-scope file (jouw variant), of live-scope zonder "-test-"
        $rateFile = "/tmp/formrate-test-" . $ipKey;

        $count = file_exists($rateFile) ? (int)file_get_contents($rateFile) : 0;

        if ($count > 8) {
            // in validator/test: return false + reason
            $reason = 'rate_limited';
            return false;

            // in live handler: redirect
            // header("Location: /pages/contact/index.php?error=Te+veel+aanvragen");
            // exit;
        }

        file_put_contents($rateFile, (string)($count + 1));
    }

    // 4) Input controleren
    $name    = trim((string)($post['name'] ?? ''));
    $email   = trim((string)($post['email'] ?? ''));
    $message = trim((string)($post['message'] ?? ''));

    if ($name === '' || $email === '' || $message === '') {
        $reason = 'missing_fields';
        return false;
    }

    // 5) Unicode / Cyrillic filtering
    if (preg_match('/[\p{Cyrillic}]/u', $message)) {
        $reason = 'cyrillic_block';
        return false;
    }

    if (!preg_match('/^[\p{Latin}\p{N}\p{P}\p{Z}\r\n]+$/u', $message)) {
        $reason = 'invalid_chars';
        return false;
    }

    $reason = 'ok';
    return true;
}

function reset_test_ratefile(string $ip): void
{
    $ipKey = substr(hash('sha256', $ip), 0, 16);
    $rateFile = "/tmp/formrate-test-" . $ipKey;
    if (file_exists($rateFile)) @unlink($rateFile);
}

$testIp = (string)($_GET['ip'] ?? ($_SERVER['REMOTE_ADDR'] ?? '127.0.0.1'));

if (isset($_GET['reset'])) {
    reset_test_ratefile($testIp);
    header("Location: /dev/mailtests/honeypot_test.php?ip=" . urlencode($testIp));
    exit;
}

// Test suite (op aanvraag)
$results = [];
if (isset($_GET['run'])) {
    reset_test_ratefile($testIp);

    $base = [
        'company_field' => '',
        'ts'            => time() - 5,
        'name'          => 'Matthias',
        'email'         => 'matthias@example.com',
        'message'       => "Testbericht.\nMet nieuwe lijn.",
    ];

    $cases = [
        'HUMAN_OK' => $base,
        'BOT_HONEYPOT' => array_merge($base, ['company_field' => 'ACME BV']),
        'TOO_FAST' => array_merge($base, ['ts' => time()]),
        'CYRILLIC' => array_merge($base, ['message' => "Привет, ik ben spam."]),
        'INVALID_CHARS' => array_merge($base, ['message' => "Hello 😊 (emoji)"]),
    ];

    // Rate limit case: 10 submits op dezelfde IP
    $rateCase = $base;

    foreach ($cases as $label => $post) {
        $reason = '';
        $ok = validate_contact_submission($post, ['REMOTE_ADDR' => $testIp, 'HTTP_USER_AGENT' => $_SERVER['HTTP_USER_AGENT'] ?? '', 'HTTP_HOST' => $_SERVER['HTTP_HOST'] ?? '', 'REQUEST_URI' => $_SERVER['REQUEST_URI'] ?? ''], $reason);
        $results[] = [$label, $ok ? 'OK' : 'BLOCK', $reason];
    }

    for ($i = 1; $i <= 10; $i++) {
        $reason = '';
        $ok = validate_contact_submission($rateCase, ['REMOTE_ADDR' => $testIp, 'HTTP_USER_AGENT' => $_SERVER['HTTP_USER_AGENT'] ?? '', 'HTTP_HOST' => $_SERVER['HTTP_HOST'] ?? '', 'REQUEST_URI' => $_SERVER['REQUEST_URI'] ?? ''], $reason);
        $results[] = ["RATE_$i", $ok ? 'OK' : 'BLOCK', $reason];
    }
}

// Manual POST test (formulier hieronder)
$manualOutcome = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reason = '';
    $ok = validate_contact_submission($_POST, ['REMOTE_ADDR' => $testIp, 'HTTP_USER_AGENT' => $_SERVER['HTTP_USER_AGENT'] ?? '', 'HTTP_HOST' => $_SERVER['HTTP_HOST'] ?? '', 'REQUEST_URI' => $_SERVER['REQUEST_URI'] ?? ''], $reason);
    $manualOutcome = ['ok' => $ok, 'reason' => $reason, 'post' => $_POST];
}

// HTML output
?><!doctype html>
<html lang="nl">
<head>
    <meta charset="utf-8">
    <title>Honeypot Test</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body{font-family:system-ui,Segoe UI,Roboto,Arial,sans-serif;margin:24px;line-height:1.4}
        .row{display:flex;gap:10px;flex-wrap:wrap;margin-bottom:14px}
        .card{border:1px solid #ddd;border-radius:12px;padding:14px;max-width:980px}
        table{border-collapse:collapse;width:100%}
        th,td{border-bottom:1px solid #eee;padding:8px;text-align:left;font-size:14px}
        .ok{color:#0a7}
        .bad{color:#c33}
        label{display:block;font-weight:600;margin-top:10px}
        input,textarea{width:100%;padding:10px;border:1px solid #ccc;border-radius:10px}
        button,a.btn{display:inline-block;padding:10px 14px;border-radius:10px;border:1px solid #ccc;background:#f7f7f7;text-decoration:none;color:#111}
        .muted{color:#666;font-size:13px}
        .pill{display:inline-block;padding:2px 8px;border-radius:999px;background:#f2f2f2;font-size:12px}
        pre{white-space:pre-wrap;background:#f7f7f7;border:1px solid #eee;border-radius:10px;padding:10px}
        .hp{position:absolute;left:-10000px;width:1px;height:1px;overflow:hidden}
    </style>
</head>
<body>

<h1>Honeypot / Anti-bot Test</h1>
<p class="muted">
    Test-IP: <span class="pill"><?= htmlspecialchars($testIp, ENT_QUOTES, 'UTF-8') ?></span>
    — Dit script verstuurt geen mails, het test alleen de checks.
</p>

<div class="row">
    <a class="btn" href="/dev/mailtests/honeypot_test.php?run=1&ip=<?= urlencode($testIp) ?>">Run testsuite</a>
    <a class="btn" href="/dev/mailtests/honeypot_test.php?reset=1&ip=<?= urlencode($testIp) ?>">Reset rate-limit (test)</a>
</div>

<?php if (!empty($results)): ?>
    <div class="card">
        <h2>Testsuite resultaten</h2>
        <table>
            <thead>
            <tr><th>Case</th><th>Status</th><th>Reason</th></tr>
            </thead>
            <tbody>
            <?php foreach ($results as [$label, $status, $reason]): ?>
                <tr>
                    <td><?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?></td>
                    <td class="<?= $status === 'OK' ? 'ok' : 'bad' ?>"><?= htmlspecialchars($status, ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($reason, ENT_QUOTES, 'UTF-8') ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <p class="muted">BOT_HONEYPOT -> zou nu ook een rij in DB moeten maken (honeypot_events).</p>
    </div>
<?php endif; ?>

<div class="card" style="margin-top:16px;">
    <h2>Handmatige test</h2>
    <p class="muted">Laat “Bedrijf” leeg voor een normale submit. Vul het in om “bot” te simuleren (en het logbestand + DB te triggeren).</p>

    <?php if ($manualOutcome !== null): ?>
        <p>
            Resultaat:
            <?php if ($manualOutcome['ok']): ?>
                <span class="ok"><b>OK</b></span>
            <?php else: ?>
                <span class="bad"><b>BLOCK</b></span>
            <?php endif; ?>
            — reason: <span class="pill"><?= htmlspecialchars($manualOutcome['reason'], ENT_QUOTES, 'UTF-8') ?></span>
        </p>
        <pre><?= htmlspecialchars(print_r($manualOutcome['post'], true), ENT_QUOTES, 'UTF-8') ?></pre>
    <?php endif; ?>

    <form method="post" action="/dev/mailtests/honeypot_test.php?ip=<?= urlencode($testIp) ?>">
        <div class="hp">
            <label>Bedrijf (honeypot)</label>
            <input type="text" name="company_field" value="">
        </div>

        <label>Naam</label>
        <input type="text" name="name" value="Matthias" required>

        <label>E-mailadres</label>
        <input type="email" name="email" value="info@webcrafters.be" required>

        <label>Bericht</label>
        <textarea name="message" rows="5" required>Testbericht zonder rare tekens.</textarea>

        <input type="hidden" name="ts" value="<?= (int)(time() - 5) ?>">

        <div class="row" style="margin-top:12px;">
            <button type="submit">Valideer (zonder mail)</button>
        </div>
    </form>
</div>

</body>
</html>
