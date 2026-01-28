<?php
include $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';

if (!isset($_FILES['csv_file']) || $_FILES['csv_file']['error'] !== 0) {
    echo "❌ Ongeldige upload.";
    exit;
}

if (($handle = fopen($_FILES['csv_file']['tmp_name'], "r")) !== FALSE) {
    $header = fgetcsv($handle, 1000, ","); // Sla eerste rij (kopteksten) over
    $inserted = 0;

    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        // Pas deze volgorde aan op basis van jouw CSV-kolommen
        [$email, $first_name, $last_name, $phone, $address, $city, $zipcode, $country] = $data;

        // Check of klant al bestaat (via email)
        $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $check->store_result();

        if ($check->num_rows === 0) {
            // Voeg nieuwe klant toe
            $stmt = $conn->prepare("INSERT INTO users 
                (email, first_name, last_name, phone, address, city, zipcode, country, role, is_confirmed, status) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'customer', 1, 'active')");
            $stmt->bind_param("ssssssss", $email, $first_name, $last_name, $phone, $address, $city, $zipcode, $country);
            $stmt->execute();
            $inserted++;
        }
    }

    fclose($handle);
    echo "✅ $inserted klanten succesvol geïmporteerd.";
} else {
    echo "❌ Kon CSV-bestand niet openen.";
}
