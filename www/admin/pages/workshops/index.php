<?php
require $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';
include $_SERVER['DOCUMENT_ROOT'] . '/header.php';

// Haal alle workshop-inschrijvingen op
$bookings = [];
$res = $conn->query("SELECT * FROM workshop_bookings ORDER BY date DESC, time_from");
if ($res && $res->num_rows > 0) {
    while ($row = $res->fetch_assoc()) {
        $bookings[] = $row;
    }
}

// Haal geblokkeerde dagen op
$blockedDays = [];
$res2 = $conn->query("SELECT * FROM workshop_blocked_days ORDER BY date DESC");
if ($res2 && $res2->num_rows > 0) {
    while ($row = $res2->fetch_assoc()) {
        $blockedDays[] = $row;
    }
}
?>

<div class="container py-5">
    <h1 class="mb-4">🛠 Workshopbeheer</h1>

    <h3>📋 Inschrijvingen</h3>
    <?php if (count($bookings) > 0): ?>
        <table class="table table-bordered table-hover">
            <thead class="table-light">
            <tr>
                <th>Naam</th>
                <th>Email</th>
                <th>Datum</th>
                <th>Tijd</th>
                <th>Personen</th>
                <th>Status</th>
                <th>Actie</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($bookings as $b): ?>
                <tr>
                    <td><?= htmlspecialchars($b['name']) ?></td>
                    <td><?= htmlspecialchars($b['email']) ?></td>
                    <td><?= date('d-m-Y', strtotime($b['date'])) ?></td>
                    <td><?= substr($b['time_from'], 0, 5) ?> – <?= substr($b['time_to'], 0, 5) ?></td>
                    <td><?= $b['persons'] ?></td>
                    <td>
                            <span class="badge bg-<?= $b['status'] === 'approved' ? 'success' : ($b['status'] === 'declined' ? 'danger' : 'warning') ?>">
                                <?= ucfirst($b['status']) ?>
                            </span>
                    </td>
                    <td>
                        <a href="/functions/workshops/update_status.php?id=<?= $b['id'] ?>&status=approved&referer=/admin/pages/workshops/" class="btn btn-sm btn-success">✅ Goedkeuren</a>
                        <a href="/functions/workshops/update_status.php?id=<?= $b['id'] ?>&status=declined&referer=/admin/pages/workshops/" class="btn btn-sm btn-danger">✖️ Afwijzen</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Geen inschrijvingen gevonden.</p>
    <?php endif; ?>

    <hr class="my-5">

    <h3>🚫 Geblokkeerde dagen</h3>
    <?php if (count($blockedDays) > 0): ?>
        <ul class="list-group mb-4">
            <?php foreach ($blockedDays as $d): ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <?= date('d-m-Y', strtotime($d['date'])) ?> — <?= htmlspecialchars($d['reason']) ?>
                    <a href="/functions/workshops/remove_blocked_day.php?id=<?= $d['id'] ?>" class="btn btn-sm btn-outline-danger">Verwijder</a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>Geen geblokkeerde dagen ingesteld.</p>
    <?php endif; ?>
</div>

<hr class="my-5">
<section class="calendar-section d-flex flex-column justify-content-center">
    <h3 class="w-75 m-auto">📆 Kalenderoverzicht (vanaf 15 oktober)</h3>

    <div id="calendar" class="w-75 m-auto"></div>
</section>
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const calendarEl = document.getElementById('calendar');

        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: 'nl',
            firstDay: 1,
            height: 'auto',
            validRange: {
                start: '2025-10-15'
            },
            events: '/functions/workshops/get_calendar_events.php',
            eventColor: '#0d6efd',
            eventDidMount: function(info) {
                if (info.event.extendedProps.type === 'blocked') {
                    info.el.style.backgroundColor = '#dc3545';
                    info.el.style.borderColor = '#dc3545';
                } else if (info.event.extendedProps.type === 'booking') {
                    info.el.style.backgroundColor = '#198754';
                    info.el.style.borderColor = '#198754';
                }
            },
            dateClick: function(info) {
                if (confirm(`Wil je ${info.dateStr} blokkeren?`)) {
                    fetch('/functions/workshops/add_blocked_day.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                        body: `date=${info.dateStr}&reason=Geblokkeerd via kalender`
                    }).then(() => {
                        calendar.refetchEvents();
                    });
                }
            }
        });

        calendar.render();
    });
</script>


<?php include $_SERVER['DOCUMENT_ROOT'] . '/footer.php'; ?>
