<div class="login-container">
    <h2>Inloggen</h2>
    <form id="login-form">
        <input type="hidden" name="referer" value="<?= isset($referer) ? htmlspecialchars($referer) : '' ?>">
        <label for="email">E-mail:</label>
        <input type="email" id="email" name="email" required>

        <label for="password">Wachtwoord:</label>
        <input type="password" id="password" name="password" required>

        <button type="submit">Inloggen</button>
    </form>

    <p><a href="/pages/account/forgot_password/">Wachtwoord vergeten?</a></p>
    <p><a href="/pages/account/register">Account aanmaken</a></p>

    <div class="social-login">
        <button onclick="window.location.href='/pages/account/login/googleLogin.php'" class="google"><i class="bi bi-google"></i> Login met Google</button>
        <button onclick="window.location.href='/pages/account/login/facebookLogin.php'" class="facebook"><i class="bi bi-facebook"></i> Login met Facebook</button>
    </div>
</div>

<script>
    document.getElementById("login-form").addEventListener("submit", function(event) {
        event.preventDefault();
        let formData = new FormData(this);

        fetch("/functions/account/login.php", {
            method: "POST",
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // ✅ Check of de referer bestaat
                    if (data.referer && data.referer !== '') {
                        window.location.href = data.referer;
                    } else {
                        window.location.href = "/pages/account/";
                    }
                } else {
                    alert(data.message);
                }
            })
            .catch(error => console.error("Login mislukt", error));
    });

</script>