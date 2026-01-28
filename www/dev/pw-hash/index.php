<?php
include $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';
//include $_SERVER['DOCUMENT_ROOT'] . '/header.php';
?>

<h2>Wachtwoord Hasher</h2>

<form method="post">
    <label>Wachtwoord (kan leeg zijn):</label><br>
    <input type="text" name="password" style="width:300px;"><br><br>
    <input type="submit" value="Genereer hash">
</form>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'] ?? '';

    // Maak een hash van het opgegeven wachtwoord (ook al is het leeg)
    $hash = password_hash($password, PASSWORD_DEFAULT);

    echo "<h3>Gegenereerde Hash:</h3>";
    echo "<code>$hash</code>";
}
?>

<?php
//include $_SERVER['DOCUMENT_ROOT'] . '/footer.php';
?>
