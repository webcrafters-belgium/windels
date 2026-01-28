<?php
$currentPage = $_SERVER['REQUEST_URI'];
$isContactPage = strpos($currentPage, '/pages/contact/contact.php') !== false;
?>
<footer class="site-footer">
<?php if (!$isContactPage): ?>
    <div class="footer-header">
        <div class="container">
            <p>
                Windels Green & Deco Resin – Beukenlaan 8, 3930 Hamont-Achel, België<br>
                BTW BE0803.859.883 – <a href="/pages/contact/contact.php">Contacteer ons</a>
            </p>
           
            <p class="footer-links">
                <a href="/pages/algemene-voorwaarden.php">Algemene Voorwaarden</a> | 
                <a href="/pages/privacy.php">Privacybeleid</a> | 
                <a href="/pages/cookies.php">Cookiebeleid</a>
            </p>
        </div>
    </div>
     <?php endif; ?>
    <div class="footer-main">
        <div class="container">
            <p>&copy; <?= date('Y'); ?> Windels - Uitvaartzorg Webshop. Alle rechten voorbehouden.</p>
            <?php if ($isContactPage): ?>
            <p class="footer-links">
                <a href="/pages/algemene-voorwaarden.php">Algemene Voorwaarden</a> | 
                <a href="/pages/privacy.php">Privacybeleid</a> | 
                <a href="/pages/cookies.php">Cookiebeleid</a>
            </p>
            <?php endif; ?>
        </div>
    </div>
</footer>


<script>
document.addEventListener('DOMContentLoaded', function() {
    const toggle = document.querySelector('.menu-toggle');
    const nav = document.querySelector('.site-nav');
    if(toggle && nav){
        toggle.addEventListener('click', function() {
            nav.classList.toggle('active');
        });
    }
});
</script>
</body>
</html>

