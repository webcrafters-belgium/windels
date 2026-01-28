<?php
session_start();

include dirname($_SERVER['DOCUMENT_ROOT']) . '/secure/ini.inc';

if (!isset($mysqli) || $mysqli->connect_errno) {
    die("DB-verbinding mislukt: " . ($mysqli->connect_error ?? 'mysqli is niet ingesteld'));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($username === '' || $password === '') {
        die("Gebruikersnaam of wachtwoord niet ingevuld");
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
                    die("Tijdelijke code ongeldig");
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
                die("Ongeldig wachtwoord");
            }
        } else {
            die("Gebruiker niet gevonden");
        }

        $stmt->close();
    } else {
        die("Databasefout: " . $mysqli->error);
    }
} else {
    header("Location: login.php");
    exit;
}
