<?php
session_start();

include dirname($_SERVER['DOCUMENT_ROOT']) . '/secure/ini.inc';

if (!isset($mysqli) || $mysqli->connect_errno) {
    $_SESSION['error'] = "Er is een technisch probleem met de verbinding. Probeer het later opnieuw of neem contact met ons op.";
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($username === '' || $password === '') {
        $_SESSION['error'] = "Vul alstublieft zowel uw gebruikersnaam als wachtwoord in.";
        header("Location: login.php");
        exit;
    }

    if ($stmt = $mysqli->prepare("SELECT * FROM funeral_partners WHERE username = ? LIMIT 1")) {
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            // Eerste login via tijdelijke code
            if ((int)$row['first_login_completed'] === 0) {
                if ($password === $row['temp_code']) {
                    $_SESSION['partner_id'] = $row['id'];
                    $_SESSION['partner_username'] = $row['username'];
                    $_SESSION['bedrijf_naam'] = $row['bedrijf_naam'];
                    $_SESSION['contact_naam'] = $row['contact_naam'];
                    $_SESSION['adres'] = $row['adres'];
                    $_SESSION['telefoon'] = $row['telefoon'];
                    $_SESSION['email'] = $row['email'];
                    $_SESSION['btw_nummer'] = $row['btw_nummer'];
                    header("Location: wachtwoord_instellen.php");
                    exit;
                } else {
                    $_SESSION['error'] = "De ingevoerde tijdelijke code is onjuist. Controleer de code en probeer het opnieuw.";
                    header("Location: login.php");
                    exit;
                }
            }

            // Normale login
            if (password_verify($password, $row['password_hash'])) {
                $_SESSION['partner_id'] = $row['id'];
                $_SESSION['partner_username'] = $row['username'];
                $_SESSION['bedrijf_naam'] = $row['bedrijf_naam'];
                $_SESSION['contact_naam'] = $row['contact_naam'];
                $_SESSION['adres'] = $row['adres'];
                $_SESSION['telefoon'] = $row['telefoon'];
                $_SESSION['email'] = $row['email'];
                $_SESSION['btw_nummer'] = $row['btw_nummer'];

                header("Location: dashboard.php");
                exit;
            } else {
                $_SESSION['error'] = "Het wachtwoord is onjuist. Probeer het opnieuw of vraag een nieuw wachtwoord aan.";
                header("Location: login.php");
                exit;
            }
        } else {
            $_SESSION['error'] = "Er is geen account gevonden met deze gebruikersnaam. Neem contact met ons op om een account aan te vragen.";
            header("Location: login.php");
            exit;
        }

        $stmt->close();
    } else {
        $_SESSION['error'] = "Er is een technisch probleem opgetreden. Probeer het later opnieuw.";
        header("Location: login.php");
        exit;
    }
} else {
    header("Location: login.php");
    exit;
}
