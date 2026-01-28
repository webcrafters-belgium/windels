<?php
require $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';
header('Content-Type: application/json');

$maxPerDay = 12; // eventueel dynamisch ophalen uit workshop_settings
$events = [];
$dagenMetEvents = []; // zowel voor geplande als geblokkeerde dagen

// 📌 Stap 1: Geplande workshops ophalen
$sql = "SELECT date, SUM(persons) AS totaal FROM workshop_bookings 
        WHERE status = 'approved' AND date >= CURDATE()
        GROUP BY date";
$res = $conn->query($sql);
while ($row = $res->fetch_assoc()) {
    $date = $row['date'];
    $totaal = (int)$row['totaal'];
    $kleur = ($totaal >= $maxPerDay) ? 'danger' : (($totaal >= $maxPerDay - 2) ? 'warning' : 'success');

    $events[] = [
        'title' => $totaal . ' ingeschreven',
        'start' => $date,
        'allDay' => true,
        'type' => 'booking',
        'status' => $kleur
    ];

    $dagenMetEvents[] = $date;
}

// 📌 Stap 2: Geblokkeerde dagen ophalen
$res2 = $conn->query("SELECT * FROM workshop_blocked_days WHERE date >= CURDATE()");
while ($row = $res2->fetch_assoc()) {
    $date = $row['date'];
    $events[] = [
        'title' => $row['reason'],
        'start' => $date,
        'allDay' => true,
        'type' => 'blocked',
        'status' => 'danger'
    ];
    $dagenMetEvents[] = $date;
}

// 📌 Stap 3: Voeg rode (lege) dagen toe tot 2 maanden vooruit
$start = new DateTime('2025-10-15');
$end = new DateTime('+2 months');
$interval = new DateInterval('P1D');
$range = new DatePeriod($start, $interval, $end);

foreach ($range as $dag) {
    $dateStr = $dag->format('Y-m-d');
    if (!in_array($dateStr, $dagenMetEvents)) {
        $events[] = [
            'title' => 'Geen sessie gepland',
            'start' => $dateStr,
            'allDay' => true,
            'type' => 'empty',
            'status' => 'danger'
        ];
    }
}

echo json_encode($events);
