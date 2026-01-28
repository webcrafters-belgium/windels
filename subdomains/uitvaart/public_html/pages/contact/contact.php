<?php include dirname($_SERVER['DOCUMENT_ROOT']).'/partials/header.php'; ?>
<main>
    <section class="contact-hero">
        <div class="container">
            <h1>Neem contact met ons op</h1>
            <p>Heeft u vragen over onze herinneringsproducten of wilt u advies? Wij staan voor u klaar.</p>
        </div>
    </section>

    <section class="contact-content">
        <div class="container">
            <div class="contact-info-box">
                <h2>Onze gegevens</h2>
                <img src="/img/logo.png" alt="" style="max-width:250px; margin-bottom:-2rem;">
                <p><strong>Website:</strong> <a href="https://windelsgreen-decoresin.com">www.windelsgreen-decoresin.com</a></p>
                <p><strong>Telefoon:</strong> <a href="tel:+3211753319">+32 1175 33 19</a></p>
                <p><strong>E-mail:</strong> <a href="mailto:info@windelsgreen-decoresin.com">info@windelsgreen-decoresin.com</a></p>
                <p><strong>Adres:</strong> Beukenlaan 8, 3930 Hamont-Achel, België</p>
                <p><strong>RPR:</strong> 0803859883</p>
                <p><strong>BTW:</strong> BE0803859883</p>
                <hr style="margin: 1rem 0; border: none; border-top: 1px solid #ddd;">
                 <p><strong>Kantoor Bereikbaarheid:</strong></p>
                <ul>
                    <li>Ma - Wo: 19:00 - 21:00</li>
                    <?php
                        $maand = (int)date('n'); // huidige maand (1–12)

                        if ($maand >= 6 && $maand <= 10) {
                            // Juni t.e.m. oktober
                            echo '<li>Do - Vr: 10:00 - 21:00</li>';
                        } else {
                            // November t.e.m. mei
                            echo '<li>
                                Do - Vr: 10:00 - 18:00 
                                <span style="font-size:0.95rem; color:#666; font-style:italic;">
                                    (na 18:00 enkel op afspraak)
                                </span>
                            </li>';

                        }
                    ?>
                    <li>Za: 10:00 - 18:00</li>
                </ul>
                <hr style="margin: 0.5rem 0; border: none; border-top: 0.5px solid #ddd;">
               <p><strong>Sociale media:</strong></p>
                <div class="social-buttons">
                    <a href="https://facebook.com/decoresinwindels" target="_blank" class="social-btn facebook">
                        <i class="fab fa-facebook-f"></i> Facebook
                    </a>
                    <a href="https://instagram.com/windelsdecoresin" target="_blank" class="social-btn instagram">
                        <i class="fab fa-instagram"></i> Instagram
                    </a>
                    <a href="https://twitter.com/Win_Green_Deco" target="_blank" class="social-btn twitter">
                        <i class="fab fa-x-twitter"></i> Twitter
                    </a>
                    <a href="https://www.tiktok.com/@versshoplkvk" target="_blank" class="social-btn tiktok">
                        <i class="fab fa-tiktok"></i> TikTok
                    </a>
                    <a href="https://wa.me/003211753319" target="_blank" class="social-btn whatsapp">
                        <i class="fab fa-whatsapp"></i> Whatsapp
                    </a>
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
