<?php
session_start();
require $_SERVER['DOCUMENT_ROOT'] . '/header.php';
?>

<main>
<div class="container h-auto p-5 mx-auto" style="margin-top: -10em">
    <h2>Wachtwoord vergeten?</h2>
    <p>Vul je e-mailadres in om een link voor het opnieuw instellen van je wachtwoord te ontvangen.</p>

    <?php if (isset($_SESSION['forgot_success'])): ?>
        <div class="success"><?= $_SESSION['forgot_success']; unset($_SESSION['forgot_success']); ?></div>
    <?php endif; ?>

    <?php if (isset($_SESSION['forgot_error'])): ?>
        <div class="error"><?= $_SESSION['forgot_error']; unset($_SESSION['forgot_error']); ?></div>
    <?php endif; ?>

    <form action="/API/auth/handle_forgot_password.php" method="POST">
        <label for="email">E-mailadres:</label>
        <input type="email" id="email" name="email" required>

        <button type="submit">Verstuur herstel-link</button>
    </form>
</div>
</main>
<?php require $_SERVER['DOCUMENT_ROOT'] . '/footer.php'; ?>
