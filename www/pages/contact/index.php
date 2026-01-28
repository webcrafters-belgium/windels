<?php
require $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';
include $_SERVER['DOCUMENT_ROOT'] . '/header.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require $_SERVER['DOCUMENT_ROOT'] . '/classes/PHPMailer/src/Exception.php';
require $_SERVER['DOCUMENT_ROOT'] . '/classes/PHPMailer/src/PHPMailer.php';
require $_SERVER['DOCUMENT_ROOT'] . '/classes/PHPMailer/src/SMTP.php';
?>

<section class="contact-hero">
    <div class="contact-card">

        <div class="contact-left">
            <h1>Let's chat.</h1>
            <p>Heb je vragen over epoxy, terrazzo of geuren?
                Stuur ons een bericht – wij helpen je persoonlijk verder.</p>

            <div class="contact-details">
                <h3>Contactgegevens</h3>
                <p><strong>Email:</strong> info@windelsgreen-decoresin.com</p>
                <p><strong>Telefoon:</strong> +32 11 75/33.19</p>
                <p><strong>Adres:</strong> Beukenlaan 8<br> Hamont-Achel, België</p>
            </div>
        </div>

        <div class="contact-right">
            <form action="/functions/contact/handle_contact.php" method="post" class="contact-form-modern">

                <!-- Honeypot -->
                <div class="hidden-field">
                    <label>Bedrijf</label>
                    <input type="text" name="company_field" value="">
                </div>

                <!-- Timestamp -->
                <input type="hidden" name="ts" value="<?=time()?>">

                <div class="form-group">
                    <label>Naam</label>
                    <input type="text" name="name" required>
                </div>

                <div class="form-group">
                    <label>E-mailadres</label>
                    <input type="email" name="email" required>
                </div>

                <div class="form-group">
                    <label>Bericht</label>
                    <textarea name="message" rows="5" required></textarea>
                </div>

                <button type="submit" class="send-btn">Verstuur bericht</button>

                <?php
                if (isset($_GET['success'])) {
                    echo "<div class='form-success'>Bericht verzonden.</div>";
                }

                if (isset($_GET['error'])) {
                    echo "<div class='form-error'>".$_GET['error']."</div>";
                }
                ?>

            </form>
        </div>
    </div>
</section>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/footer.php'; ?>
