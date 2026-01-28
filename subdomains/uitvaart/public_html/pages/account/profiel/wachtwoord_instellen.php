<?php
session_start();
include dirname($_SERVER['DOCUMENT_ROOT']) . '/secure/ini.inc';

// Alleen toegankelijk als niet ingelogd of tijdens eerste login
if (!isset($_SESSION['partner_id'])) {
    header("Location: /pages/account/login.php");
    exit;
}

// Verwerk indien POST
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm'] ?? '';

    // Validatie
    if (strlen($password) < 6 ||
        !preg_match('/[a-z]/', $password) ||
        !preg_match('/[A-Z]/', $password) ||
        !preg_match('/[0-9]/', $password) ||
        !preg_match('/[@&!%#]/', $password)) {
        $error = "Het wachtwoord voldoet niet aan de vereisten.";
    } elseif ($password !== $confirm) {
        $error = "Wachtwoorden komen niet overeen.";
    } else {
        // Opslaan in DB
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $mysqli->prepare("UPDATE funeral_partners SET wachtwoord = ?, first_login_completed = 1, temp_code = NULL WHERE id = ?");
        $stmt->bind_param('si', $hashed, $_SESSION['pending_partner_id']);
        if ($stmt->execute()) {
            unset($_SESSION['pending_partner_id']);
            $_SESSION['partner_id'] = $stmt->insert_id; // of hergebruik originele id
            header("Location: /pages/account/profiel/index.php");
            exit;
        } else {
            $error = "Er ging iets mis bij het opslaan.";
        }
    }
}
?>

<style>

         body {
    background: url('/img/uitvaartachtergrond.jpg') no-repeat center center fixed;
    background-size: cover;
     }

.wachtwoord-container {
    max-width: 640px;
    margin: 2rem auto;
    padding: 2rem;
    background-color: rgba(255, 255, 255, 0.9);
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.5);
    box-sizing: border-box;
    width: 100%;
}

@media (max-width: 768px) {
    .wachtwoord-container {
        margin: 1rem;
        padding: 1.5rem;
        border-radius: 10px;
    }
}

.wachtwoord-container h2 {
    font-size: 1.75rem;
    margin-bottom: 1rem;
    color: #004d36;
}

.wachtwoord-container p,
.wachtwoord-container ul {
    font-size: 1rem;
    color: #444;
    line-height: 1.6;
}

.wachtwoord-container ul {
    margin-bottom: 2rem;
    padding-left: 1.2rem;
}

.wachtwoord-container ul li {
    margin-bottom: 0.5rem;
}

/* Formulier */
.wachtwoord-container form {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.wachtwoord-container label {
    font-weight: 600;
    color: #333;
}

.wachtwoord-container input[type="password"] {
    padding: 0.75rem;
    border: 1px solid #ccc;
    border-radius: 8px;
    font-size: 1rem;
    box-sizing: border-box;
    width: 100%;
    transition: border-color 0.3s;
}

.wachtwoord-container input[type="password"]:focus {
    border-color: #006c4d;
    outline: none;
}

.wachtwoord-container .btn {
    background-color: #006c4d;
    color: white;
    padding: 0.75rem 1.25rem;
    border: none;
    border-radius: 8px;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: background-color 0.3s;
}

.wachtwoord-container .btn:hover {
    background-color: #004d36;
}

/* Foutmelding */
.wachtwoord-container .alert-danger {
    background-color: #fdd;
    color: #900;
    padding: 1rem;
    border-radius: 8px;
    margin-bottom: 1rem;
    font-weight: 500;
}


/* Mobiel */
@media (max-width: 600px) {
    .wachtwoord-container {
        padding: 1.5rem 1rem;
    }

    .wachtwoord-container .btn {
        font-size: 1rem;
        padding: 0.75rem;
    }
}</style>
<?php include dirname($_SERVER['DOCUMENT_ROOT']) . '/partials/header.php'; ?>
<main class="wachtwoord-container">
    <h2>Stel uw wachtwoord in</h2>
    <p>Gelieve hieronder een nieuw wachtwoord te kiezen dat voldoet aan de volgende voorwaarden:</p>
    <ul>
        <li>Minimaal 6 tekens</li>
        <li>Minstens één hoofdletter</li>
        <li>Minstens één kleine letter</li>
        <li>Minstens één cijfer</li>
        <li>Minstens één speciaal teken: @, &, !, %, #</li>
    </ul>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="post">
        <label for="password">Nieuw wachtwoord:</label>
        <input type="password" name="password" id="password" required>

        <label for="confirm">Bevestig wachtwoord:</label>
        <input type="password" name="confirm" id="confirm" required>

        <button type="submit" class="btn">Wachtwoord instellen</button>
    </form>
</main>
<?php include dirname($_SERVER['DOCUMENT_ROOT']) . '/partials/footer.php'; ?>
