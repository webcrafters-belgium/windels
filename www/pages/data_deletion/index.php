<?php
$year = date('Y');
$bedrijfsnaam = 'Windels Green & Deco Resin';
$bedrijfsadres = 'Beukenlaan 8
3930 Hamont-Achel
België';
$bedrijfsemail = "info@windelsgreen-decoresin.com";
$bedrijfstelefoon = "+3211753319";
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gegevens Verwijderen</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
            color: #333;
        }
        h1 {
            color: #d9534f;
            text-align: center;
        }
        h2 {
            color: #333;
            border-bottom: 2px solid #d9534f;
            padding-bottom: 5px;
        }
        p, li {
            line-height: 1.6;
        }
        ul {
            padding-left: 20px;
        }
        .section {
            margin-bottom: 20px;
            background-color: #fff;
            padding: 15px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        form {
            text-align: center;
        }
        input[type="email"] {
            padding: 10px;
            width: 80%;
            max-width: 400px;
            margin-bottom: 10px;
        }
        button {
            background-color: #d9534f;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: #c9302c;
        }
    </style>
</head>
<body>

<h1>Gegevens Verwijderen</h1>

<div class="section">
    <h2>1. Wat betekent het verwijderen van uw gegevens?</h2>
    <p>Wanneer u uw gegevens verwijdert, worden uw persoonlijke gegevens volledig uit ons systeem verwijderd. Dit betekent:</p>
    <ul>
        <li>Uw account en bijbehorende gegevens worden permanent verwijderd.</li>
        <li>U kunt geen toegang meer krijgen tot uw account.</li>
        <li>Bestelgeschiedenis en facturen worden gewist, tenzij wettelijk vereist.</li>
    </ul>
</div>

<div class="section">
    <h2>2. Verzoek indienen om uw gegevens te verwijderen</h2>
    <p>Vul uw e-mailadres in om een verzoek in te dienen. U ontvangt een bevestigingsmail om de verwijdering te voltooien.</p>
    <form action="/api/delete_account.php" method="POST">
        <input type="email" name="email" placeholder="Uw e-mailadres" required>
        <br>
        <button type="submit">Verwijder mijn gegevens</button>
    </form>
</div>

<div class="section">
    <h2>3. Contact</h2>
    <p>Voor vragen over het verwijderen van uw gegevens kunt u contact met ons opnemen:</p>
    <p>
        <strong>Contactinformatie:</strong><br>
        <?php echo "$bedrijfsadres"; ?><br>
        E-mail: <?php echo "$bedrijfsemail"; ?><br>
        Telefoonnummer: <?php echo "$bedrijfstelefoon"; ?>
    </p>
</div>

<footer>
    <p style="text-align: center; font-size: 0.9em; color: #666;">&copy; <?php echo "$year"; ?> Windels. Alle rechten voorbehouden.</p>
</footer>

</body>
</html>
