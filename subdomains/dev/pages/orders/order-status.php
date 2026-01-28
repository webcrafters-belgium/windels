<?php
// order-status.php
declare(strict_types=1);

require_once dirname($_SERVER['DOCUMENT_ROOT'])."/secure/ini.inc"; // $mysqli, $encryption_key
session_start();

/* ===== Helpers ===== */
if (!function_exists('h')) {
    function h(string $s): string { return htmlspecialchars($s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }
}
function csrf_token(): string { if (empty($_SESSION['csrf'])) $_SESSION['csrf']=bin2hex(random_bytes(32)); return $_SESSION['csrf']; }
function csrf_check(string $t): bool { return isset($_SESSION['csrf']) && hash_equals($_SESSION['csrf'], $t); }
function redirect(array $qs): void { header("Location: order-status.php?".http_build_query($qs), true, 303); exit; }
function normalize_email(string $e): string { return strtolower(trim($e)); }
function db_err(mysqli $db): string { return "[{$db->errno}] {$db->error}"; }

/* Kolom-detectie (klant_email vs client_email) */
function email_col(mysqli $db): string {
    $q1 = $db->query("SHOW COLUMNS FROM `order_private` LIKE 'klant_email'");
    if ($q1 && $q1->num_rows) { $q1->close(); return 'klant_email'; }
    if ($q1) $q1->close();
    $q2 = $db->query("SHOW COLUMNS FROM `order_private` LIKE 'client_email'");
    if ($q2 && $q2->num_rows) { $q2->close(); return 'client_email'; }
    if ($q2) $q2->close();
    return ''; // geen match
}

/* Storage engine check (voor FOR UPDATE) */
function supports_for_update(mysqli $db, string $table): bool {
    $stmt = $db->prepare("SELECT ENGINE FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? LIMIT 1");
    if (!$stmt) return false;
    $stmt->bind_param('s', $table);
    $stmt->execute();
    $stmt->bind_result($engine);
    $ok = $stmt->fetch();
    $stmt->close();
    if (!$ok) return false;
    // InnoDB ondersteunt FOR UPDATE; MyISAM niet
    return (strcasecmp((string)$engine, 'InnoDB') === 0);
}

/* AES-256-GCM decrypt passend bij encryptField */
function decryptField(string $blob, string $key): string {
    if ($blob==='') return '';
    $raw = base64_decode($blob, true);
    if ($raw===false || strlen($raw) < 12+16) return '';
    $iv  = substr($raw, 0, 12);
    $tag = substr($raw, 12, 16);
    $ct  = substr($raw, 28);
    $pt  = openssl_decrypt($ct, 'AES-256-GCM', $key, OPENSSL_RAW_DATA, $iv, $tag, '');
    return $pt===false ? '' : $pt;
}

/* ===== State ===== */
$errors  = [];
$debugs  = [];
$updated = !empty($_GET['updated']);
$emailPlain = filter_input(INPUT_GET, 'email', FILTER_VALIDATE_EMAIL) ?: '';
$order      = trim((string)($_GET['order'] ?? ''));

/* Validatie */
if ($emailPlain && $order && !preg_match('/^[A-Z0-9\-]{4,50}$/i', $order)) {
    $errors[] = 'Ongeldig ordernummerformaat.';
}

/* Detect kolomnaam en FOR UPDATE support */
$EMAIL_COL = email_col($mysqli);
if ($EMAIL_COL === '') {
    $errors[] = 'E-mailkolom in order_private niet gevonden (verwacht klant_email of client_email).';
}
$FOR_UPDATE_OK = supports_for_update($mysqli, 'orders');
if (DEBUG) {
    $debugs[] = 'E-mailkolom: '.h($EMAIL_COL);
    $debugs[] = 'FOR UPDATE: '.($FOR_UPDATE_OK ? 'JA' : 'NEE');
}

/* ===== POST: status update ===== */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postEmailPlain = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL) ?: '';
    $postOrder      = trim((string)($_POST['order'] ?? ''));
    $action         = $_POST['action'] ?? '';
    $token          = $_POST['csrf'] ?? '';

    if (!csrf_check($token))               $errors[] = 'Ongeldige of ontbrekende veiligheidscontrole (CSRF).';
    if (!$postEmailPlain || !$postOrder)   $errors[] = 'E-mailadres en ordernummer zijn verplicht.';
    if ($action !== 'update_status')       $errors[] = 'Ongeldige actie.';
    if ($EMAIL_COL === '')                 $errors[] = 'Interne fout: onbekende e-mailkolom.';

    // Doelstatus server-side vastzetten
    $targetStatus = 'as_verzonden';

    // (Optioneel) check ENUM bevat target
    if (!$errors) {
        try {
            $q = $mysqli->prepare("
                SELECT COLUMN_TYPE
                FROM INFORMATION_SCHEMA.COLUMNS
                WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'orders' AND COLUMN_NAME = 'status' LIMIT 1
            ");
            if ($q) {
                $q->execute();
                $q->bind_result($colType);
                if ($q->fetch()) {
                    if (strpos((string)$colType, "'".$targetStatus."'") === false) {
                        $errors[] = "Database ondersteunt status '".h($targetStatus)."' nog niet in ENUM.";
                    }
                }
                $q->close();
            }
        } catch (Throwable $ex) {
            if (DEBUG) $debugs[] = 'ENUM-check fout: '.h($ex->getMessage());
        }
    }

    if (!$errors) {
        try {
            $mysqli->begin_transaction();

            // 1) Selecteer op ordernummer (en lock indien kan)
            $sql = "SELECT o.id, o.order_number, o.status, o.created_at, op.`$EMAIL_COL`
                    FROM orders o
                    JOIN order_private op ON op.order_id = o.id
                    WHERE o.order_number = ?
                    ORDER BY o.id DESC
                    LIMIT 1".($FOR_UPDATE_OK ? " FOR UPDATE" : "");
            $stmt = $mysqli->prepare($sql);
            if (!$stmt) throw new RuntimeException('Kon statement niet voorbereiden (POST select). '.db_err($mysqli));
            $stmt->bind_param('s', $postOrder);
            $stmt->execute();
            $stmt->bind_result($id, $order_number, $status, $created_at, $enc_client_email);

            $orderRow = null;
            if ($stmt->fetch()) {
                $orderRow = [
                    'id'           => $id,
                    'order_number' => $order_number,
                    'status'       => $status,
                    'created_at'   => $created_at,
                    $EMAIL_COL     => $enc_client_email,
                ];
            }
            $stmt->close();

            if (!$orderRow) throw new RuntimeException('Bestelling niet gevonden.');

            // 2) Decrypt e-mail uit DB en vergelijk in PHP
            $clientEmailPlain = '';
            if (!empty($orderRow[$EMAIL_COL])) {
                $clientEmailPlain = decryptField($orderRow[$EMAIL_COL], $encryption_key);
            }
            if (!hash_equals(normalize_email($clientEmailPlain), normalize_email($postEmailPlain))) {
                throw new RuntimeException('E-mailadres komt niet overeen met de bestelling.');
            }

            // 3) Als status al target is, behandel als success (geen no-op fout)
            if ($orderRow['status'] === $targetStatus) {
                if (DEBUG) $debugs[] = 'Status is al as_verzonden; no-op als success.';
                $mysqli->commit();
                redirect(['email'=>$postEmailPlain,'order'=>$postOrder,'updated'=>1]);
            }

            // 4) Alleen toegestaan vanuit 'aangemaakt'
            $allowedFrom = 'aangemaakt';
            if ($orderRow['status'] !== $allowedFrom) {
                throw new RuntimeException('Status kan enkel aangepast worden als deze nog "aangemaakt" is.');
            }

            // 5) Update
            $upd = $mysqli->prepare("UPDATE orders SET status = ? WHERE id = ? LIMIT 1");
            if (!$upd) throw new RuntimeException('Kon update niet voorbereiden. '.db_err($mysqli));

            $idInt = (int)$orderRow['id'];          // <<< bind_param heeft een variabele nodig
            $okBind = $upd->bind_param('si', $targetStatus, $idInt);
            if (!$okBind) {
                throw new RuntimeException('Kon parameters niet binden (update). '.db_err($mysqli));
            }

            $okExec = $upd->execute();
            if (!$okExec) {
                $err = db_err($mysqli);
                $upd->close();
                throw new RuntimeException('DB-fout bij update-execute: '.$err);
            }

            // Let op: affected_rows kan 0 zijn als optimizer nothing-to-do denkt; dubbel check
            if ($upd->errno) {
                $err = db_err($mysqli);
                $upd->close();
                throw new RuntimeException('DB-fout bij update: '.$err);
            }
            $affected = $upd->affected_rows;
            $upd->close();

            if ($affected === 0) {
                // Double-check huidige status
                $chk = $mysqli->prepare("SELECT status FROM orders WHERE id=? LIMIT 1");
                if ($chk) {
                    $chk->bind_param('i', $orderRow['id']);
                    $chk->execute();
                    $chk->bind_result($curr);
                    $chk->fetch();
                    $chk->close();
                    if ($curr === $targetStatus) {
                        // beschouw als success
                        if (DEBUG) $debugs[] = 'affected_rows=0 maar status nu as_verzonden → success.';
                        $mysqli->commit();
                        redirect(['email'=>$postEmailPlain,'order'=>$postOrder,'updated'=>1]);
                    }
                }
                throw new RuntimeException('Status kon niet gewijzigd worden (geen rijen aangepast).');
            }

            $mysqli->commit();

            // PRG
            redirect(['email'=>$postEmailPlain,'order'=>$postOrder,'updated'=>1]);
        } catch (Throwable $e) {
            $mysqli->rollback();
            error_log('[order-status.php POST] '.$e->getMessage());
            $errors[] = 'Er ging iets mis bij het bijwerken van de bestelling.';
            if (DEBUG) $errors[] = h($e->getMessage());
        }
    }

    // State bewaren bij fouten
    $emailPlain = $postEmailPlain ?: $emailPlain;
    $order      = $postOrder ?: $order;
}

/* ===== GET: order ophalen voor weergave ===== */
$orderRow = null;
$clientEmailPlainForView = '';

if ($EMAIL_COL !== '' && $emailPlain && $order) {
    try {
        $sql = "SELECT o.id, o.order_number, o.status, o.created_at, op.`$EMAIL_COL`
                FROM orders o
                JOIN order_private op ON op.order_id = o.id
                WHERE o.order_number = ?
                LIMIT 1";
        $stmt = $mysqli->prepare($sql);
        if (!$stmt) throw new RuntimeException('Kon statement niet voorbereiden (GET select). '.db_err($mysqli));
        $stmt->bind_param('s', $order);
        $stmt->execute();

        // bind_result ipv get_result
        $stmt->bind_result($id, $order_number, $status, $created_at, $enc_client_email);
        if ($stmt->fetch()) {
            $orderRow = [
                'id'           => $id,
                'order_number' => $order_number,
                'status'       => $status,
                'created_at'   => $created_at,
                $EMAIL_COL     => $enc_client_email,
            ];
        }
        $stmt->close();

        if ($orderRow && !empty($orderRow[$EMAIL_COL])) {
            $clientEmailPlainForView = decryptField($orderRow[$EMAIL_COL], $encryption_key);
            // Alleen tonen als e-mail overeenkomt
            $eq = hash_equals(normalize_email($clientEmailPlainForView), normalize_email($emailPlain));
            if (!$eq) { $orderRow = null; }
        } elseif ($orderRow) {
            $orderRow = null;
        }
    } catch (Throwable $e) {
        error_log('[order-status.php GET] '.$e->getMessage());
        $errors[] = 'Er ging iets mis bij het ophalen van de bestelling.';
        if (DEBUG) $errors[] = h($e->getMessage());
    }
}

require_once dirname($_SERVER['DOCUMENT_ROOT'])."/partials/header.php";
?>
<style>
.cart-page{background-color:rgba(255,255,255,.92);padding:24px;border-radius:12px;box-shadow:0 4px 10px rgba(0,0,0,.05);margin:32px auto 24px;max-width:900px;width:92%;box-sizing:border-box}
h1{font-size:22px;margin:0 0 10px}
.row{display:flex;gap:16px;flex-wrap:wrap}
.kv{flex:1 1 220px;background:#f5f7f4;border:1px solid #dfe6df;border-radius:12px;padding:12px}
.kv .k{font-size:12px;color:#406a50;text-transform:uppercase;letter-spacing:.04em}
.kv .v{font-weight:700;margin-top:4px}
.alert{margin:12px 0;padding:12px;border-radius:12px}
.alert-ok{background:#ecf8f0;border:1px solid #bfe1cc;color:#1f4d35}
.alert-err{background:#fff3f3;border:1px solid #f1cccc;color:#7a1e1e}
.actions{margin-top:16px}
button.linklike{background:#1f4d35;color:#fff;border:0;border-radius:12px;padding:10px 14px;cursor:pointer}
a.btn{display:inline-block;text-decoration:none;background:#1f4d35;color:#fff;border-radius:12px;padding:10px 14px}
hr{border:none;border-top:1px solid #e6eee6;margin:16px 0}
.small{font-size:12px;color:#406a50}
.card{background:#fff;border-radius:16px;box-shadow:0 6px 24px rgba(0,0,0,.06);padding:20px}
.debug{font-size:12px;background:#fffbe6;border:1px dashed #e6d67a;color:#574a00;border-radius:10px;padding:10px;margin-top:10px}
</style>

<main class="overons-hero">
    <div class="overons-container">
        <h1>Orderstatus</h1>
        <p class="subtitel">Hier kan je jouw bestelling opvolgen.</p>
    </div>
</main>

<section class="overons-inhoud">
    <div class="cart-page">
    <?php if($updated): ?>
      <div class="alert alert-ok">De status is bijgewerkt naar <strong>as verzonden</strong>.</div>
    <?php endif; ?>

    <?php if($errors): ?>
      <div class="alert alert-err"><?php echo h(implode("<br>", $errors)); ?></div>
    <?php endif; ?>

    <?php if(DEBUG && $debugs): ?>
      <div class="debug">
        <strong>Debug:</strong><br>
        <?php echo h(implode(" | ", $debugs)); ?>
      </div>
    <?php endif; ?>

    <?php if($orderRow): ?>
      <div class="row">
        <div class="kv"><div class="k">Ordernummer</div><div class="v"><?php echo h($orderRow['order_number']); ?></div></div>
        <div class="kv"><div class="k">E-mailadres</div><div class="v"><?php echo h($clientEmailPlainForView); ?></div></div>
        <div class="kv"><div class="k">Aangemaakt op</div><div class="v"><?php echo h($orderRow['created_at']); ?></div></div>
        <div class="kv"><div class="k">Huidige status</div><div class="v"><?php echo h($orderRow['status']); ?></div></div>
      </div>

      <div class="actions">
        <?php if($orderRow['status'] === 'aangemaakt'): ?>
          <form method="post" action="order-status.php">
            <input type="hidden" name="csrf" value="<?php echo h(csrf_token()); ?>">
            <input type="hidden" name="email" value="<?php echo h($emailPlain); ?>">
            <input type="hidden" name="order" value="<?php echo h($order); ?>">
            <input type="hidden" name="action" value="update_status">
            <button type="submit" class="linklike">Markeer als 'as verzonden'</button>
          </form>
        <?php else: ?>
          <div class="small">De status kan niet meer gewijzigd worden.</div>
        <?php endif; ?>
      </div>

      <hr>
      <div><a class="btn" href="track-order.php">Terug naar zoeken</a></div>
    <?php else: ?>
      <p>Geen bestelling gevonden voor dit e-mailadres en ordernummer.</p>
      <hr>
      <div><a class="btn" href="track-order.php">Opnieuw proberen</a></div>
    <?php endif; ?>
    </div>
</section>

<?php require_once dirname($_SERVER['DOCUMENT_ROOT'])."/partials/footer.php"; ?>
