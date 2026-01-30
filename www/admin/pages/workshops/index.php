<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/admin/includes/header.php';

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

<!-- PAGE HEADER -->
<div class="mb-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-4xl font-bold mb-2 glow-text" style="color: var(--text-primary);">
                <i class="bi bi-tools accent-primary mr-3"></i>Workshopbeheer
            </h1>
            <p class="text-lg" style="color: var(--text-muted);">Beheer inschrijvingen en geblokkeerde dagen</p>
        </div>
    </div>
</div>

<!-- BOOKINGS TABLE -->
<div class="card-glass p-8 mb-8">
    <div class="flex justify-between items-center mb-6">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-500/30 to-green-500/30 flex items-center justify-center">
                <i class="bi bi-clipboard-check text-xl text-emerald-400"></i>
            </div>
            <h2 class="text-2xl font-bold" style="color: var(--text-primary);">Inschrijvingen</h2>
        </div>
        <span class="px-4 py-2 rounded-xl glass text-sm font-medium" style="color: var(--text-muted);">
            <?= count($bookings) ?> inschrijvingen
        </span>
    </div>

    <?php if (count($bookings) > 0): ?>
        <div class="overflow-x-auto rounded-xl">
            <table class="w-full">
                <thead>
                    <tr class="border-b" style="border-color: var(--border-glass); background: var(--bg-glass);">
                        <th class="text-left py-4 px-4 font-semibold text-sm uppercase tracking-wider" style="color: var(--text-muted);">Naam</th>
                        <th class="text-left py-4 px-4 font-semibold text-sm uppercase tracking-wider" style="color: var(--text-muted);">Email</th>
                        <th class="text-left py-4 px-4 font-semibold text-sm uppercase tracking-wider" style="color: var(--text-muted);">Datum</th>
                        <th class="text-left py-4 px-4 font-semibold text-sm uppercase tracking-wider" style="color: var(--text-muted);">Tijd</th>
                        <th class="text-left py-4 px-4 font-semibold text-sm uppercase tracking-wider" style="color: var(--text-muted);">Personen</th>
                        <th class="text-left py-4 px-4 font-semibold text-sm uppercase tracking-wider" style="color: var(--text-muted);">Status</th>
                        <th class="text-right py-4 px-4 font-semibold text-sm uppercase tracking-wider" style="color: var(--text-muted);">Acties</th>
                    </tr>
                </thead>
                <tbody class="divide-y" style="--tw-divide-opacity: 0.1;">
                <?php foreach ($bookings as $b): 
                    $statusConfig = [
                        'approved' => ['bg' => 'bg-emerald-500/20', 'text' => 'text-emerald-400', 'border' => 'border-emerald-500/30'],
                        'declined' => ['bg' => 'bg-rose-500/20', 'text' => 'text-rose-400', 'border' => 'border-rose-500/30'],
                        'pending' => ['bg' => 'bg-amber-500/20', 'text' => 'text-amber-400', 'border' => 'border-amber-500/30'],
                    ];
                    $config = $statusConfig[$b['status']] ?? $statusConfig['pending'];
                ?>
                    <tr class="group hover:bg-white/5 transition-colors">
                        <td class="py-4 px-4 font-semibold"><?= htmlspecialchars($b['name']) ?></td>
                        <td class="py-4 px-4 text-sm" style="color: var(--text-muted);"><?= htmlspecialchars($b['email']) ?></td>
                        <td class="py-4 px-4 text-sm"><?= date('d-m-Y', strtotime($b['date'])) ?></td>
                        <td class="py-4 px-4 text-sm" style="color: var(--text-muted);"><?= substr($b['time_from'], 0, 5) ?> – <?= substr($b['time_to'], 0, 5) ?></td>
                        <td class="py-4 px-4"><?= $b['persons'] ?></td>
                        <td class="py-4 px-4">
                            <span class="px-3 py-1.5 rounded-lg text-xs font-semibold border <?= $config['bg'] ?> <?= $config['text'] ?> <?= $config['border'] ?>">
                                <?= ucfirst($b['status']) ?>
                            </span>
                        </td>
                        <td class="py-4 px-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="/functions/workshops/update_status.php?id=<?= $b['id'] ?>&status=approved&referer=/admin/pages/workshops/" 
                                   class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 hover:bg-emerald-500/30 transition">
                                    <i class="bi bi-check-lg"></i> Goedkeuren
                                </a>
                                <a href="/functions/workshops/update_status.php?id=<?= $b['id'] ?>&status=declined&referer=/admin/pages/workshops/" 
                                   class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-rose-500/20 text-rose-400 border border-rose-500/30 hover:bg-rose-500/30 transition">
                                    <i class="bi bi-x-lg"></i> Afwijzen
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="py-16 text-center">
            <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-slate-500/20 to-slate-600/20 flex items-center justify-center mx-auto mb-4">
                <i class="bi bi-clipboard text-4xl" style="color: var(--text-muted);"></i>
            </div>
            <p class="text-lg font-semibold mb-1" style="color: var(--text-secondary);">Geen inschrijvingen gevonden</p>
        </div>
    <?php endif; ?>
</div>

<!-- BLOCKED DAYS -->
<div class="card-glass p-8 mb-8">
    <div class="flex justify-between items-center mb-6">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-rose-500/30 to-red-500/30 flex items-center justify-center">
                <i class="bi bi-calendar-x text-xl text-rose-400"></i>
            </div>
            <h2 class="text-2xl font-bold" style="color: var(--text-primary);">Geblokkeerde dagen</h2>
        </div>
    </div>

    <?php if (count($blockedDays) > 0): ?>
        <div class="space-y-3">
            <?php foreach ($blockedDays as $d): ?>
                <div class="card-glass p-4 flex justify-between items-center">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-lg bg-rose-500/20 flex items-center justify-center">
                            <i class="bi bi-calendar-x text-rose-400"></i>
                        </div>
                        <div>
                            <span class="font-semibold"><?= date('d-m-Y', strtotime($d['date'])) ?></span>
                            <span class="mx-2" style="color: var(--text-muted);">—</span>
                            <span style="color: var(--text-muted);"><?= htmlspecialchars($d['reason']) ?></span>
                        </div>
                    </div>
                    <a href="/functions/workshops/remove_blocked_day.php?id=<?= $d['id'] ?>" 
                       class="px-4 py-2 rounded-lg text-sm font-semibold bg-rose-500/20 text-rose-400 border border-rose-500/30 hover:bg-rose-500/30 transition">
                        <i class="bi bi-trash"></i> Verwijder
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p style="color: var(--text-muted);">Geen geblokkeerde dagen ingesteld.</p>
    <?php endif; ?>
</div>

<!-- CALENDAR -->
<div class="card-glass p-8">
    <div class="flex items-center space-x-3 mb-6">
        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500/30 to-indigo-500/30 flex items-center justify-center">
            <i class="bi bi-calendar3 text-xl text-blue-400"></i>
        </div>
        <h2 class="text-2xl font-bold" style="color: var(--text-primary);">Kalenderoverzicht</h2>
    </div>
    
    <div id="calendar" class="rounded-xl overflow-hidden"></div>
</div>

<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>

<style>
    .fc { background: var(--bg-glass); border-radius: 1rem; }
    .fc-theme-standard td, .fc-theme-standard th { border-color: var(--border-glass); }
    .fc-daygrid-day:hover { background: var(--bg-glass-hover); }
    .fc-col-header-cell { background: var(--bg-glass); }
    .fc-toolbar-title { color: var(--text-primary) !important; }
    .fc-button { background: var(--accent) !important; border: none !important; }
    .fc-button:hover { background: var(--accent-hover) !important; }
</style>

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
            eventColor: '#14b8a6',
            eventDidMount: function(info) {
                if (info.event.extendedProps.type === 'blocked') {
                    info.el.style.backgroundColor = '#f43f5e';
                    info.el.style.borderColor = '#f43f5e';
                } else if (info.event.extendedProps.type === 'booking') {
                    info.el.style.backgroundColor = '#10b981';
                    info.el.style.borderColor = '#10b981';
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

<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/admin/includes/footer.php'; ?>
