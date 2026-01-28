<?php
$year = date('Y');
$bedrijfsnaam = 'Windels Green & Deco Resin';
$bedrijfsadres = 'Beukenlaan 8
3930 Hamont-Achel
België';
$bedrijfsemail = "info@windelsgreen-decoresin.com";
$bedrijfstelefoon = "+3211753319";
$btwNr               = 'BE0803859883';

include $_SERVER['DOCUMENT_ROOT'] . '/header.php';
?>

<div class="privacy-container">
    <!-- Algemene Voorwaarden -->
    <section class="algemene-voorwaarden">
        <h1>Algemene Voorwaarden</h1>
        <p><em>Laatst gewijzigd: 10/06/2025</em></p>

        <h2>Artikel 1 – Identiteit van de ondernemer</h2>
        <p>
            Windels Green & Deco Resin<br>
            <?php echo nl2br($bedrijfsadres); ?><br>
            E-mail: <?php echo $bedrijfsemail; ?><br>
            Tel: <?php echo $bedrijfstelefoon; ?><br>
            Btw-nummer: <?php echo $btwNr; ?><br>
        </p>

        <h2>Artikel 2 – Toepasselijkheid</h2>
        <p>
            Deze voorwaarden gelden voor alle aanbiedingen, bestellingen en overeenkomsten op afstand.
            Door te bestellen gaat u akkoord.
        </p>

        <h2>Artikel 3 – Het aanbod</h2>
        <ul>
            <li>Aanbiedingen kunnen beperkt geldig of onder voorwaarden zijn; dat wordt altijd vermeld.</li>
            <li>Onze beschrijvingen zijn volledig en nauwkeurig; zichtbare fouten binden ons niet.</li>
        </ul>

        <h2>Artikel 4 – De overeenkomst</h2>
        <ul>
            <li>De overeenkomst komt tot stand bij uw aanvaarding en voorwaarde(n).</li>
            <li>Bij elektronische aanvaarding bevestigen wij direct de ontvangst.</li>
        </ul>

        <h2>Artikel 5 – Prijzen</h2>
        <ul>
            <li>Alle prijzen in Euro, inclusief btw, tenzij anders vermeld.</li>
            <li>Verzendkosten worden vooraf duidelijk vermeld.</li>
        </ul>

        <h2>Artikel 6 – Betaling</h2>
        <p>
            Betalingen verlopen via aangeboden methoden; Mollie B.V. verwerkt de betaling.
            Wij hebben geen toegang tot uw volledige bankgegevens.
        </p>

        <h2>Artikel 7 – Levering</h2>
        <p>
            Wij doen onze uiterste best bestellingen zorgvuldig te verwerken.
            Overschrijding van levertijden geeft geen recht op schadevergoeding.
        </p>

        <h2>Artikel 8 – Herroepingsrecht</h2>
        <p>
            U kunt binnen 14 dagen zonder opgave van redenen ontbinden.
            Retourneer onbeschadigd en in originele verpakking.
        </p>

        <h2>Artikel 9 – Garantie en conformiteit</h2>
        <p>
            Producten voldoen aan de overeenkomst en redelijke eisen.
            Meld gebreken binnen redelijke termijn.
        </p>

        <h2>Artikel 10 – Klachtenregeling</h2>
        <p>
            Dien klachten tijdig en volledig omschreven in.
            Wij beantwoorden binnen 14 dagen of geven een indicatie.
        </p>

        <h2>Artikel 11 – Intellectueel eigendom</h2>
        <p>
            Alle rechten op producten, ontwerpen, afbeeldingen en teksten berusten bij ons of licentiegevers.
        </p>

        <h2>Artikel 12 – Aansprakelijkheid</h2>
        <p>
            Wij zijn niet aansprakelijk voor indirecte schade.
            Onze aansprakelijkheid is maximaal de aankoopprijs van het product.
        </p>

        <h2>Artikel 13 – Toepasselijk recht en bevoegde rechter</h2>
        <p>
            Belgisch recht is van toepassing.
            Geschillen worden voorgelegd aan de bevoegde rechter in het arrondissement [invullen].
        </p>
    </section>

    <!-- Winkelregels -->
    <section class="winkelregels">
        <h1>Regels in de Winkel</h1>
        <p><em>Laatst gewijzigd: 16/06/2025</em></p>
        <ul>
            <li><strong>Respectvol gedrag:</strong> ongepast gedrag wordt niet getolereerd.</li>
            <li><strong>Producten hanteren:</strong> beschadigingen kunnen in rekening worden gebracht.</li>
            <li><strong>Huisdieren:</strong> niet toegestaan, behalve geleidehonden.</li>
            <li><strong>Eten en drinken:</strong> alleen in daartoe aangewezen zones.</li>
            <li><strong>Fotograferen/filmen:</strong> toegestaan voor persoonlijk gebruik, commercieel na toestemming.</li>
            <li><strong>Tascontrole:</strong> wij behouden ons dit recht voor bij twijfel.</li>
            <li><strong>Diefstal:</strong> wordt direct gemeld bij de politie.</li>
            <li><strong>Instructies personeel:</strong> dienen te worden opgevolgd.</li>
            <li><strong>Veiligheid:</strong> let op eigen veiligheid en die van anderen.</li>
            <li><strong>Schade:</strong> opzettelijke of grove nalatigheid wordt verhaald.</li>
        </ul>
    </section>
</div>
<?php
include $_SERVER['DOCUMENT_ROOT'] . '/footer.php';
?>