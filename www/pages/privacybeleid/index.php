<?php
include $_SERVER['DOCUMENT_ROOT'].'/header.php';

$year                = date('Y');
$bedrijfsnaam        = 'Windels green & deco resin';
$bedrijfsadres       = "Beukenlaan 8\n3930 Hamont-Achel\nBelgië";
$bedrijfsemail       = 'info@windelsgreen-decoresin.com';
$bedrijfstelefoon    = '+3211753319';
$laatsteWijziging    = '10 juni 2025';
$btwNr               = 'BE0803859883';
?>

<div class="privacy-container">

    <!-- Privacybeleid -->
    <section class="privacy-beleid">
        <h1>Privacybeleid</h1>
        <p><em>Laatst gewijzigd: <?php echo $laatsteWijziging; ?></em></p>

        <h2>1. Verantwoordelijke voor de Verwerking van Gegevens</h2>
        <p>
            <?php echo $bedrijfsnaam; ?> is verantwoordelijk voor de verwerking van uw persoonsgegevens.
            U kunt contact met ons opnemen via:
        </p>
        <p>
            <strong>Contactinformatie:</strong><br>
            <?php echo nl2br($bedrijfsadres); ?><br>
            E-mail: <?php echo $bedrijfsemail; ?><br>
            Telefoon: <?php echo $bedrijfstelefoon; ?>
        </p>

        <h2>2. Verzamelen en Gebruik van Persoonsgegevens</h2>
        <p>Wij kunnen de volgende persoonsgegevens verzamelen en verwerken:</p>
        <ul>
            <li><strong>Contactgegevens:</strong> naam, e-mail, telefoon, adres.</li>
            <li><strong>Accountinformatie:</strong> inloggegevens, voorkeuren, social login (Facebook/Google).</li>
            <li><strong>Transactiegegevens:</strong> aankopen, bestellingen, betalingen, leveringen.</li>
            <li><strong>Gebruiksgegevens:</strong> IP-adres, browserinformatie, bezochte pagina's.</li>
            <li><strong>OAuth-informatie:</strong> naam, e-mail, unieke ID van Facebook/Google.</li>
            <li><strong>Betalingsgegevens:</strong> betaalmethode, versleutelde bank-/creditcardgegevens, betaalgeschiedenis.</li>
        </ul>

        <h3>Doeleinden van de Gegevensverwerking</h3>
        <ul>
            <li>Authenticatie via social login.</li>
            <li>Verwerking en voltooiing van bestellingen.</li>
            <li>Klantenservice en ondersteuning.</li>
            <li>Verbetering van website en diensten.</li>
            <li>Voldoen aan wettelijke verplichtingen (o.a. FAVV).</li>
            <li>Analyse van gebruiksgedrag voor optimalisatie.</li>
            <li>Preventie van fraude en misbruik.</li>
        </ul>

        <h2>3. Rechtsgrondslagen voor Verwerking</h2>
        <ul>
            <li><strong>Uitvoering van overeenkomst:</strong> bij aankoop of accountaanmaak.</li>
            <li><strong>Wettelijke verplichtingen:</strong> boekhouding, fiscale-, voedselveiligheidsregels.</li>
            <li><strong>Gerechtvaardigd belang:</strong> analyse, beveiliging, dienstverlening.</li>
            <li><strong>Toestemming:</strong> voor social login en marketingcommunicatie.</li>
        </ul>

        <h2>4. Delen met Derden</h2>
        <p>Wij delen persoonsgegevens uitsluitend met:</p>
        <ul>
            <li>Facebook, Google (authenticatie).</li>
            <li>Mollie B.V. (betalingsverwerking: IP-adres, betaalwijze, orderinfo).</li>
            <li>Webhosting- en e-maildienstverleners.</li>
            <li>IT-beveiligingspartners.</li>
            <li>Bevoegde autoriteiten (wettelijk verplicht).</li>
        </ul>

        <h2>5. Bewaartermijnen</h2>
        <ul>
            <li><strong>Accountgegevens:</strong> zolang account actief; na 6 maanden inactiviteit verwijderen.</li>
            <li><strong>Transactiegegevens:</strong> maximaal 6 maanden na verwerking.</li>
            <li><strong>Gebruiks- en analysematerialen:</strong> maximaal 6 maanden.</li>
            <li><strong>Cookies:</strong> conform cookiebeleid, maximaal 13 maanden.</li>
        </ul>

        <h2>6. Uw Rechten</h2>
        <p>
            U heeft recht op inzage, rectificatie, verwijdering, bezwaar, beperking en overdraagbaarheid van uw persoonsgegevens.
            Toestemming social login kunt u intrekken door accountverwijdering of ontkoppeling.
        </p>
        <p>
            Neem voor uw rechten contact op via <a href="mailto:<?php echo $bedrijfsemail; ?>"><?php echo $bedrijfsemail; ?></a>
            of verwijder uw gegevens zelf via <a href="/pages/account/deletion/">deze link</a>.
        </p>

        <h2>7. Internationale Gegevensoverdracht</h2>
        <p>
            Bij social login kunnen gegevens buiten de EER worden verwerkt. Wij garanderen passende beveiliging conform AVG (standaardcontractbepalingen).
        </p>

        <h2>8. Beveiliging van Uw Gegevens</h2>
        <p>
            Wij nemen technische en organisatorische maatregelen: SSL-encryptie, toegangscontrole, firewalls en regelmatige back-ups.
        </p>

        <h2>9. Cookies en Tracking</h2>
        <p>
            Functionele, analytische en trackingcookies verbeteren uw ervaring. Derde-cookies (Facebook/Google) bij social login of embedded content.
        </p>
        <p>
            Pas uw cookie-voorkeuren aan via onze <a href="/pages/cookies/">cookie-instellingen</a> of browserinstellingen.
        </p>

        <h2>10. Wijzigingen aan dit Privacybeleid</h2>
        <p>
            Wij behouden ons het recht voor dit beleid te wijzigen. Laatste wijziging: <?php echo $laatsteWijziging; ?>.
        </p>
    </section>

</div>

<?php
include $_SERVER['DOCUMENT_ROOT'].'/footer.php';
?>
