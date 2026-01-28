<?php
// footer.php

// Zorg dat DB-verbinding beschikbaar is
if (!isset($conn)) {
    require $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';
}

if (!isset($year)) {
    $year = date('Y');
}

// Vertalingen voor dagen/maanden
$dayTranslation = [
        'Monday'    => 'Ma',
        'Tuesday'   => 'Di',
        'Wednesday' => 'Wo',
        'Thursday'  => 'Do',
        'Friday'    => 'Vr',
        'Saturday'  => 'Za',
        'Sunday'    => 'Zo'
];

$monthTranslation = [
        'Jan' => 'Jan', 'Feb' => 'Feb', 'Mar' => 'Mrt', 'Apr' => 'Apr',
        'May' => 'Mei', 'Jun' => 'Jun', 'Jul' => 'Jul', 'Aug' => 'Aug',
        'Sep' => 'Sep', 'Oct' => 'Okt', 'Nov' => 'Nov', 'Dec' => 'Dec'
];

// Normale openingstijden
$openingHours = [];
$res = $conn->query("SELECT * FROM opening_hours");
if ($res) {
    $openingHours = $res->fetch_all(MYSQLI_ASSOC);
}

// Speciale openingstijden
$specialOpeningHours = [];
$res = $conn->query("
    SELECT event_date, opening_time, closing_time, event_name 
    FROM calendar_events 
    JOIN special_opening_hours 
      ON calendar_events.id = special_opening_hours.event_id
    ORDER BY event_date ASC
");
if ($res) {
    $specialOpeningHours = $res->fetch_all(MYSQLI_ASSOC);
}

// Vakantieperiodes
$vacations = $conn->query("
    SELECT title, start_date, end_date, note
    FROM vacation_periods
    WHERE end_date >= CURDATE()
    ORDER BY start_date ASC
");
?>

<!-- CHATBOT -->
<div id="chatbot-container" class="minimized">
    <div id="collapseButton"><i class="bi bi-x-square"></i></div>
    <div id="chat-header">💬 Assistent</div>
    <div id="chat-box"><div id="messages"></div></div>
    <div id="chat-input-area">
        <button id="emoji-btn">😊</button>
        <input type="text" id="user-input" placeholder="Typ hier een vraag...">
        <button id="send-btn">📨</button>
    </div>
</div>
<div id="chatbot-icon" class="d-block">
    <i class="bi bi-chat-dots"></i>
</div>

<!-- FOOTER -->
<footer class="footer bg-offwhite mt-5 pt-5 pb-4">
    <div class="container">
        <div class="row gy-4">

            <!-- CONTACT -->
            <div class="col-md-4 text-center text-md-start">
                <h5 class="text-uppercase text-muted">Contacteer ons</h5>
                <p class="mt-3 mb-2">
                    Beukenlaan 8<br>
                    3930 Hamont-Achel, België<br>
                    B.T.W: BE0803.859.883
                </p>
                <p class="mb-1">
                    <a class="text-decoration-none text-dark" href="mailto:info@windelsgreen-decoresin.com">
                        info@windelsgreen-decoresin.com
                    </a>
                </p>
                <p class="mb-3">
                    <a class="text-decoration-none text-dark" href="tel:+3211753319">
                        +32 (0)11 75 33 19
                    </a>
                </p>

                <div class="d-flex justify-content-center justify-content-md-start gap-2 mt-3">
                    <a href="https://www.facebook.com/Decoresinwindels" target="_blank"
                       class="btn btn-outline-dark btn-sm rounded-circle">
                        <i class="bi bi-facebook"></i>
                    </a>
                    <a href="https://x.com/Win_Green_Deco" target="_blank"
                       class="btn btn-outline-dark btn-sm rounded-circle">
                        <i class="bi bi-twitter-x"></i>
                    </a>
                    <a href="https://www.instagram.com/windelsdecoresin/" target="_blank"
                       class="btn btn-outline-dark btn-sm rounded-circle">
                        <i class="bi bi-instagram"></i>
                    </a>
                    <a href="https://tiktok.com/Versshoplkvk" target="_blank"
                       class="btn btn-outline-dark btn-sm rounded-circle">
                        <i class="bi bi-tiktok"></i>
                    </a>
                </div>
            </div>

            <!-- LOGO / USP -->
            <div class="col-md-4 text-center">
                <img loading="lazy"
                     src="/images/windels-logo.svg"
                     class="img-fluid mb-3"
                     style="max-width: 210px;"
                     alt="Windels Green & Deco Resin logo">
                <p class="text-muted mb-0">
                    Handgemaakte epoxy-, terrazzo- en kaarsencollecties,
                    met zorg gegoten in ons atelier in Hamont-Achel.
                </p>
            </div>

            <!-- OPENINGSTIJDEN -->
            <div class="col-md-4 text-center text-md-start">
                <h5 class="text-uppercase text-muted">Normale openingstijden</h5>
                <table class="table table-sm table-borderless mb-3">
                    <tbody>
                    <?php foreach ($openingHours as $day): ?>
                        <tr>
                            <th class="fw-normal">
                                <?= $dayTranslation[$day['day_of_week']] ?? $day['day_of_week']; ?>:
                            </th>
                            <td>
                                <?= date('H:i', strtotime($day['opening_time'])); ?> –
                                <?= date('H:i', strtotime($day['closing_time'])); ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>

                <h6 class="text-uppercase text-muted">Speciale openingstijden</h6>
                <table class="table table-sm table-borderless mb-3">
                    <tbody>
                    <?php
                    $countSpecial = 0;
                    foreach ($specialOpeningHours as $special):
                        $eventDate = strtotime($special['event_date']);
                        if ($eventDate < strtotime('today')) {
                            continue;
                        }
                        $countSpecial++;
                        $dayShort   = $dayTranslation[date('l', $eventDate)] ?? date('D', $eventDate);
                        $monthShort = $monthTranslation[date('M', $eventDate)] ?? date('M', $eventDate);
                        $dateFormatted = date('d', $eventDate) . ' ' . $monthShort . ' ' . date('Y', $eventDate);

                        $opening = $special['opening_time'];
                        $closing = $special['closing_time'];
                        $isClosed = ($opening === '00:00:00' && $closing === '00:00:00');
                        ?>
                        <tr>
                            <th class="fw-normal"><?= $dayShort . ' ' . $dateFormatted; ?>:</th>
                            <td>
                                <?php if ($isClosed): ?>
                                    <?= htmlspecialchars($special['event_name']); ?> — gesloten
                                <?php else: ?>
                                    <?= date('H:i', strtotime($opening)); ?> – <?= date('H:i', strtotime($closing)); ?>
                                    <span class="text-muted d-block small">
                                        <?= htmlspecialchars($special['event_name']); ?>
                                    </span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>

                    <?php if ($countSpecial === 0): ?>
                        <tr>
                            <td colspan="2" class="text-muted small">Geen speciale openingstijden</td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>

                <h6 class="text-uppercase text-muted">Vakantieperiodes</h6>
                <table class="table table-sm table-borderless">
                    <tbody>
                    <?php if ($vacations && $vacations->num_rows > 0): ?>
                        <?php while ($v = $vacations->fetch_assoc()): ?>
                            <?php
                            $start = date('d/m/Y', strtotime($v['start_date']));
                            $end   = date('d/m/Y', strtotime($v['end_date']));
                            ?>
                            <tr>
                                <th class="fw-normal"><?= $start; ?> — <?= $end; ?>:</th>
                                <td>
                                    <?= htmlspecialchars($v['title']); ?>
                                    <?php if (!empty($v['note'])): ?>
                                        — <?= htmlspecialchars($v['note']); ?>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="2" class="text-muted small">Geen vakantieperiodes gepland</td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- HANDIGE LINKS + TRUSTPILOT -->
        <div class="row mt-4 align-items-center">
            <div class="col-md-6">
                <h6 class="text-uppercase text-muted mb-2">Handige links</h6>
                <ul class="list-unstyled mb-0">
                    <li><a href="/pages/privacybeleid/" class="text-decoration-none text-dark">Privacybeleid</a></li>
                    <li><a href="/pages/terms_of_service/" class="text-decoration-none text-dark">Algemene voorwaarden</a></li>
                    <li><a href="/pages/account/deletion" class="text-decoration-none text-dark">Gegevensverwijdering</a></li>
                </ul>
            </div>
            <div class="col-md-6 mt-3 mt-md-0">
                <div class="trustpilot-widget"
                     data-locale="nl-NL"
                     data-template-id="56278e9abfbbba0bdcd568bc"
                     data-businessunit-id="65918a1d144b2f2215512dc0"
                     data-style-height="52px"
                     data-style-width="100%">
                    <a href="https://nl.trustpilot.com/review/windelsgreen-decoresin.com"
                       target="_blank">Trustpilot</a>
                </div>
            </div>
        </div>
    </div>
</footer>

<div class="bg-undertext py-3 text-center small">
    © <?= $year; ?> Windels. Gebouwd door
    <a href="https://asp.webcrafters.be/" target="_blank" class="text-decoration-none fw-semibold">Matthias Gielen</a>,
    verdeeld door
    <a href="https://www.webcrafters.be" target="_blank" class="text-decoration-none fw-semibold">Webcrafters</a>.
</div>

<!-- COOKIE BANNER -->
<div id="cookie-banner" class="cookie-banner" style="display:none;">
    Deze website maakt gebruik van cookies om je winkelervaring te verbeteren.
    <button id="accept-cookies" class="btn btn-sm btn-primary ms-2">Accepteer cookies</button>
</div>

<!-- SCRIPTS -->
<script src="/js/bootstrap/bootstrap.bundle.min.js" defer></script>
<script src="/js/chatbot/main.js" defer></script>
<script src="/js/plugins.js" defer></script>
<script src="/js/script.js" defer></script>
<script src="/js/cart.js" defer></script>
<script src="/js/checkout.js" defer></script>


<script async defer crossorigin="anonymous"
        src="https://connect.facebook.net/nl_NL/sdk.js#xfbml=1&version=v18.0&appId=969637121765527&autoLogAppEvents=1">
</script>

<script>
    // Simpele cookie-banner logica
    document.addEventListener('DOMContentLoaded', function () {
        const banner = document.getElementById('cookie-banner');
        const btn    = document.getElementById('accept-cookies');
        if (!banner || !btn) return;

        if (!localStorage.getItem('cookiesAccepted')) {
            banner.style.display = 'block';
        }

        btn.addEventListener('click', function () {
            localStorage.setItem('cookiesAccepted', '1');
            banner.style.display = 'none';
        });
    });
</script>

</body>
</html>
