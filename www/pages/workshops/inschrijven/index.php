<?php
require $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';
include $_SERVER['DOCUMENT_ROOT'] . '/header.php';
?>

<div class="container py-5">
    <h1 class="mb-4 text-primary">📆 Beschikbare workshops vanaf 15 oktober</h1>
    <p>Klik op een beschikbare dag in de kalender om je in te schrijven. Vol = vol.</p>
    <div id="calendar"></div>
</div>

<!-- Modal -->
<div class="modal fade" id="workshopModal" tabindex="-1" aria-labelledby="workshopModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="post" action="/functions/workshops/verwerk_inschrijving.php" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="workshopModalLabel">✍️ Inschrijven voor workshop</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Sluiten"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="date" id="modal-date">
                <input type="hidden" name="timeslot" id="modal-timeslot">

                <div class="mb-3">
                    <label for="name" class="form-label">Naam</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">E-mailadres</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="persons" class="form-label">Aantal personen</label>
                    <input type="number" name="persons" class="form-control" min="1" max="12" value="1" required>
                </div>
                <div class="alert alert-info" id="selected-date-info"></div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Bevestig inschrijving</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuleer</button>
            </div>
        </form>
    </div>
</div>


<script src="/js/fullcalendar/index.global.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const calendarEl = document.getElementById('calendar');
        const modal = new bootstrap.Modal(document.getElementById('workshopModal'));

        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: 'nl',
            firstDay: 1,
            validRange: { start: '2025-10-15' },
            height: 'auto',
            events: '/functions/workshops/get_calendar_events_public.php',
            eventDidMount: function(info) {
                const status = info.event.extendedProps.status;

                if (status === 'danger') {
                    info.el.style.backgroundColor = '#dc3545'; // rood
                } else if (status === 'warning') {
                    info.el.style.backgroundColor = '#fd7e14'; // oranje
                } else if (status === 'success') {
                    info.el.style.backgroundColor = '#198754'; // groen
                }

                info.el.style.borderColor = info.el.style.backgroundColor;
                info.el.title = info.event.title;
            },

            dateClick: function(info) {
                const clickedDate = info.dateStr;

                // Check of de dag geblokkeerd is
                const events = calendar.getEvents().filter(e => e.startStr === clickedDate && e.extendedProps.type === 'blocked');
                if (events.length > 0) return;

                // Bepaal tijdslot obv weekdag
                const weekday = new Date(clickedDate).getDay(); // 0 = zondag, 6 = zaterdag
                const timeslot = (weekday === 0) ? '10:00–18:00' : '18:30–23:00';

                document.getElementById('modal-date').value = clickedDate;
                document.getElementById('modal-timeslot').value = timeslot;
                document.getElementById('selected-date-info').innerText = `Datum: ${clickedDate} • Tijdslot: ${timeslot}`;

                modal.show();
            }
        });

        calendar.render();
    });
</script>


<?php include $_SERVER['DOCUMENT_ROOT'] . '/footer.php'; ?>
