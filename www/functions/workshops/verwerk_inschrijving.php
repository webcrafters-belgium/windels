<?php

require $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';

// Gegevens uit het formulier
$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$persons = intval($_POST['persons'] ?? 1);
$date = $_POST['date'] ?? '';
$timeslot = $_POST['timeslot'] ?? '';

// Validatie
if (empty($name) || empty($email) || empty($date) || empty($timeslot)) {
    header('Location: /pages/workshops/inschrijven/index.php?error=Lege velden');
    exit;
}

// Check op geblokkeerde dagen
$stmt = $conn->prepare("SELECT COUNT(*) as blocked FROM workshop_blocked_days WHERE date = ?");
$stmt->bind_param("s", $date);
$stmt->execute();
$checkResult = $stmt->get_result()->fetch_assoc();

if ($checkResult['blocked'] > 0) {
    header("Location: /pages/workshops/inschrijven/index.php?error=Deze dag is geblokkeerd");
    exit;
}

// Voeg boeking toe aan database
$stmt = $conn->prepare("
    INSERT INTO workshop_bookings (name, email, date, time_from, time_to, persons, status, created_at)
    VALUES (?, ?, ?, ?, ?, ?, 'pending', NOW())
");

[$from, $to] = explode('–', str_replace('–', '-', $timeslot)); // veilige fallback voor typografisch streepje
$stmt->bind_param("sssssi", $name, $email, $date, $from, $to, $persons);

if ($stmt->execute()) {
    header("Location: /pages/workshops/inschrijven/index.php?success=1");
    exit;
} else {
    header("Location: /pages/workshops/inschrijven/index.php?error=Fout bij inschrijven");
    exit;
}
