<?php include dirname($_SERVER['DOCUMENT_ROOT']) . '/partials/header.php'; ?>
<style>
    .login-card{
        background-color: rgba(255, 255, 255, 0.92);
    padding: 3rem 2rem;
    border-radius: 12px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.5);
    margin: 3rem auto 3rem auto;
    max-width: 720px;
    width: 100%;
    box-sizing: border-box;
    }
    .alert-error{background:#ffdddd;color:#a94442;padding:10px;border:1px solid #a94442;border-radius:5px;margin-bottom:15px;text-align:center;font-size:15px;}

</style>
<main class="login-page">
    <div class="login-container">
        <div class="login-card">
            <h1>Uitvaartdiensten Login</h1>
            <p>
                Alleen erkende uitvaartdiensten kunnen hier inloggen.<br>
                Neem contact met ons op om een gebruikersnaam en wachtwoord te ontvangen.
            </p>

            <?php if (!empty($_SESSION['error'])): ?>
                <div class="alert-error">
                    <?php echo htmlspecialchars($_SESSION['error']); ?>
               </div>
            <?php
                unset($_SESSION['error']);
                endif;
            ?>
            <form method="post" action="process_login.php">
                <label for="username">Gebruikersnaam</label>
                <input type="text" id="username" name="username" required>

                <label for="password">Wachtwoord</label>
                <input type="password" id="password" name="password" required>

                <button type="submit" class="btn">Inloggen</button>
            </form>

            <p class="login-note">
                Nog geen login? <a href="registratie.php">Maak een account aan</a><br>
                Problemen met inloggen? Neem contact op via
                <a href="/pages/contact.php">onze contactpagina</a>.
            </p>
        </div>
    </div>
</main>
<?php include dirname($_SERVER['DOCUMENT_ROOT']) . '/partials/footer.php'; ?>
