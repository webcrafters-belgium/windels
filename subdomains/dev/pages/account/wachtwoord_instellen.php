<?php 
include dirname($_SERVER['DOCUMENT_ROOT']) . '/secure/ini.inc';
session_start();


if (!isset($_SESSION['partner_id'])) {
    header("Location: /pages/account/login.php");
    exit;
}

$partner_id = $_SESSION['partner_id'];

// Check of wachtwoord al is ingesteld
$stmt = $mysqli->prepare("SELECT first_login_completed FROM funeral_partners WHERE id = ?");
$stmt->bind_param("i", $partner_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    die("Gebruiker niet gevonden.");
}

if ((int)$user['first_login_completed'] === 1) {
    header("Location: dashboard.php");
    exit;
}


$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if ($new_password !== $confirm_password) {
        $error = "Wachtwoorden komen niet overeen.";
    } elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@!%#&])[A-Za-z\d@!%#&]{6,}$/', $new_password)) {
        $error = "Wachtwoord moet minstens 6 tekens bevatten, met een hoofdletter, een kleine letter, een cijfer en een speciaal teken (@!%#&).";
    } else {
        $hash = password_hash($new_password, PASSWORD_DEFAULT);
        $stmt = $mysqli->prepare("UPDATE funeral_partners SET password_hash = ?, first_login_completed = 1, temp_code = NULL WHERE id = ?");
        $stmt->execute([$hash, $partner_id]);

        $success = "Wachtwoord succesvol ingesteld. Je wordt doorgestuurd...";
        header("refresh:3;url=dashboard.php");
        exit();
    }
}
include dirname($_SERVER['DOCUMENT_ROOT']) . '/partials/header.php'; 
?>
<style>
    .wachtwoord-container{max-width:400px;margin:50px auto;padding:30px;border-radius:12px;background:#f9f9f9;box-shadow:0 0 10px rgba(0,0,0,0.1);font-family:sans-serif}
.wachtwoord-container h2{font-size:24px;margin-bottom:20px;color:#2c3e50}
.wachtwoord-container label{display:block;margin-top:15px;margin-bottom:5px;color:#34495e}
.wachtwoord-container input[type="password"]{width:100%;padding:10px;border:1px solid #ccc;border-radius:6px;box-sizing:border-box}
.wachtwoord-container button{margin-top:20px;width:100%;padding:12px;background:#2e5e4e;color:#fff;border:none;border-radius:8px;cursor:pointer;font-weight:bold;transition:background 0.3s}
.wachtwoord-container button:hover{background:#264c3f}
.success,.error{margin-top:15px;padding:10px;border-radius:6px}
.success{background:#d4edda;color:#155724;border:1px solid #c3e6cb}
.error{background:#f8d7da;color:#721c24;border:1px solid #f5c6cb}
</style>
    <div class="wachtwoord-container">
        <h2>Nieuw wachtwoord instellen</h2>
        <?php if ($error): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php elseif ($success): ?>
            <div class="success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <?php if (!$success): ?>
        <form method="post">
            <label for="new_password">Nieuw wachtwoord:</label>
            <input type="password" name="new_password" id="new_password" required>

            <label for="confirm_password">Bevestig wachtwoord:</label>
            <input type="password" name="confirm_password" id="confirm_password" required>

            <button type="submit">Wachtwoord instellen</button>
        </form>
        <?php endif; ?>
    </div>
<?php include dirname($_SERVER['DOCUMENT_ROOT']) . '/partials/footer.php'; ?>