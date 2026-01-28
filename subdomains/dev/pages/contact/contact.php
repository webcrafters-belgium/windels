<?php
$locale = locale_accept_from_http($_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? '');
$country = str_contains(strtolower($locale), 'nl-nl') ? 'NL' : 'BE'; // default BE

if ($country === 'NL') {
    $regLabel = 'KvK-nummer';
    $regTip = 'Kamer van Koophandel (NL)';
} else {
    $regLabel = 'RPR';
    $regTip = 'Ondernemingsnummer (BE)';
}

// Uitzonderlijke dagen includen
include '/var/www/medewerkers.windelsgreen-decoresin.com/public_html/status_uitzonderlijk_winkel.php';
?>

<?php include dirname($_SERVER['DOCUMENT_ROOT']).'/partials/header.php'; ?>
<main>
    <section class="contact-hero">
        <div class="container">
            <h1>Neem contact met ons op</h1>
            <p>Heeft u vragen over onze herinneringsproducten of wilt u advies?<br>Wij staan voor u klaar.</p>
        </div>
    </section>

    <section class="contact-content">
        <div class="container">
            <div class="contact-info-box">
                <h2>Onze gegevens</h2>
                <img src="/img/logo.png" alt="" style="max-width:250px; margin-bottom:-2rem;">
                <p><strong>Website:</strong></p>
                <ul class="website">
                    <li><a href="https://windelsgreen-decoresin.com">www.windelsgreen-decoresin.com</a>,</li>
                    <li><a href="https://vers.windelsgreen-decoresin.com">vers.windelsgreen-decoresin.com</a>,</li>
                    <li><a href="https://uitvaart.windelsgreen-decoresin.com">uitvaart.windelsgreen-decoresin.com</a></li>
                </ul>

                <p><strong>E-mail:</strong></p>
                <ul class="mail">
                    <li><a href="mailto:info@windelsgreen-decoresin.com">info@windelsgreen-decoresin.com</a></li>
                    <li><a href="mailto:uitvaart@windelsgreen-decoresin.com">uitvaart@windelsgreen-decoresin.com</a></li>
                    <li><a href="mailto:support@windelsgreen-decoresin.com">support@windelsgreen-decoresin.com</a></li>
                </ul>

                <p><strong>Telefoon:</strong> <a href="tel:+3211753319">+32 1175 33 19</a></p>
                <p><strong>Adres:</strong> Beukenlaan 8, 3930 Hamont-Achel, België</p>
                <p class="tooltip"><strong><?= $regLabel ?>:</strong> 0803859883<span class="tooltiptext"><?= $regTip ?></span></p><br>
                <p class="tooltip"><strong>BTW:</strong> BE0803859883<span class="tooltiptext">B.T.W-nummer</span></p>

                <hr style="margin: 1rem 0; border: none; border-top: 1px solid #ddd;">

                <p><strong>Bereikbaarheid Kantoor/Winkel:</strong></p>
                <ul class="openingsuren">
                    <li>Ma - Wo: 19:00 - 21:00</li>
                    <?php
                    $maand = (int)date('n');
                    if ($maand >= 6 && $maand <= 10) {
                        echo '<li>Do - Vr: 10:00 - 21:00</li>';
                    } else {
                        echo '<li>Do - Vr: 10:00 - 18:00 <span style="font-size:0.95rem; color:#666; font-style:italic;">(na 18:00 enkel op afspraak)</span></li>';
                    }
                    ?>
                    <li>Za: 10:00 - 18:00</li>
                </ul>

                <?php if (!empty($uitzonderlijkeDagen)): ?>
                    <hr style="margin:0.5rem 0;border:none;border-top:0.5px solid #ddd;">
                    <p><strong>Uitzonderlijke Bereikbaarheid Kantoor/winkel<br>(Komende 14 dagen):</strong></p>
                    <ul class="uitzonderlijke-dagen">
                        <?php
                        $vandaag = strtotime('today');
                        $over14dagen = strtotime('+14 days');
                        $gevonden = false;

                        // Sorteer op datum
                        ksort($uitzonderlijkeDagen);

                        foreach ($uitzonderlijkeDagen as $datum => $info) {
                            $ts = strtotime($datum);
                            if ($ts >= $vandaag && $ts <= $over14dagen) {
                                $gevonden = true;
                                $formattedDate = date('d/m/Y', $ts);

                                if (isset($info[0]) && strtolower($info[0]) === 'open') {
                                    // Toon reden 3 bij open (meestal de tijden)
                                    $tekst = isset($info[2]) ? $info[2] : '';
                                } else {
                                    // Toon reden 1 bij gesloten
                                    $tekst = isset($info[0]) ? 'Gesloten' : '';
                                }

                                $tekst = htmlspecialchars($tekst);
                                echo "<li><strong>{$formattedDate}</strong>: {$tekst}</li>";
                            }
                        }

                        if (!$gevonden) {
                            echo '<li><em>Geen uitzonderlijke dagen gepland in de komende 14 dagen.</em></li>';
                        }
                        ?>
                    </ul>
                <?php endif; ?>


                <hr style="margin: 0.5rem 0; border: none; border-top: 0.5px solid #ddd;">
                <p><strong>Sociale media:</strong></p>
                <div class="social-buttons">
                    <a href="https://facebook.com/decoresinwindels" target="_blank" class="social-btn facebook"><i class="fab fa-facebook-f"></i> Facebook</a>
                    <a href="https://instagram.com/windelsdecoresin" target="_blank" class="social-btn instagram"><i class="fab fa-instagram"></i> Instagram</a>
                    <a href="https://twitter.com/Win_Green_Deco" target="_blank" class="social-btn twitter"><i class="fab fa-x-twitter"></i> Twitter</a>
                    <a href="https://www.tiktok.com/@versshoplkvk" target="_blank" class="social-btn tiktok"><i class="fab fa-tiktok"></i> TikTok</a>
                    <a href="https://linkedin.com/in/andy-windels-7448b2379" target="_blank" class="social-btn linkedin"><i class="fab fa-linkedin"></i> Linkedin</a>
                    <a href="https://wa.me/003211753319" target="_blank" class="social-btn whatsapp"><i class="fab fa-whatsapp"></i> Whatsapp</a>
                </div>

                <div style="margin-top:2.5rem; text-align:left;">
                    <div class="trustpilot-widget"
                        data-locale="nl-NL"
                        data-template-id="56278e9abfbbba0bdcd568bc"
                        data-businessunit-id="65918a1d144b2f2215512dc0"
                        data-style-height="54px"
                        data-style-width="100%"
                        data-theme="dark">
                        <a href="https://nl.trustpilot.com/review/windelsgreen-decoresin.com" target="_blank" rel="noopener">Bekijk onze reviews op Trustpilot</a>
                    </div>
                </div>
            </div>

            <div class="contact-form-box">
                <h2>Stuur ons een bericht</h2>
                <form method="post" action="verstuur_contact.php">
                    <label for="name">Naam *</label>
                    <input type="text" id="name" name="name" required>

                    <label for="email">E-mail *</label>
                    <input type="email" id="email" name="email" required>

                    <label for="phone">Telefoon</label>
                    <input type="tel" id="phone" name="phone">

                    <label for="message">Bericht *</label>
                    <textarea id="message" name="message" rows="5" required></textarea>

                    <button type="submit" class="btn">Verstuur bericht</button>
                </form>
            </div>
        </div>
    </section>
</main>
<?php include dirname($_SERVER['DOCUMENT_ROOT']).'/partials/footer.php'; ?>
