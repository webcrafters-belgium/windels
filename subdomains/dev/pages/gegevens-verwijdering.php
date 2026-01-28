<?php
// /pages/gegevens-verwijdering.php
include dirname($_SERVER['DOCUMENT_ROOT']).'/partials/header.php';
?>
<main class="hero" id="top">
  <section class="hero-content">
    <h1>Verzoek tot Gegevensverwijdering</h1>
    <p>Hier lees je hoe je jouw persoonsgegevens kunt laten verwijderen volgens de AVG (GDPR).</p>
    <?php $lastUpdate="02-09-2025"; ?>
    <p class="last-update">Laatst bijgewerkt: <?php echo $lastUpdate; ?></p>
</section>
</main>

<main class="uitvaart-container">
    <div class="container">
        <div class="voorwaarden-card">
            <section>
            <h2>1. Uw recht op gegevensverwijdering</h2>
            <p>U heeft het recht om te verzoeken dat wij uw persoonsgegevens verwijderen, in overeenstemming met de Algemene Verordening Gegevensbescherming (AVG/GDPR). Dit recht staat ook bekend als het <em>recht om vergeten te worden</em>.</p>
            <p>Verwerkingsverantwoordelijke: <strong>Windels Andy</strong>, handelend onder de naam <strong>Windels Green &amp; Deco Resin</strong> (info@windelsgreen-decoresin.com).</p>
            </section>

            <section>
            <h2>2. Wat wordt verwijderd?</h2>
            <p>Bij een geldig verzoek verwijderen of anonimiseren wij onder meer:</p>
            <ul class="voorwaarden">
                <li>Account- en profielgegevens</li>
                <li>Contact- en communicatiehistoriek</li>
                <li>Bestel- en transactiegegevens <em>voor zover wettelijk toegestaan</em></li>
                <li>Login-/authenticatiegegevens (bv. sociale login)</li>
                <li>Technische logs die niet langer noodzakelijk zijn</li>
            </ul>
            <p class="note">Let op: bepaalde gegevens (zoals facturen en boekhoudstukken) moeten wij <strong>wettelijk bewaren (meestal 7 jaar)</strong>. Ook kunnen wij minimale gegevens bewaren voor het vaststellen, uitoefenen of onderbouwen van rechtsvorderingen en om te voldoen aan <em>wettelijke verjaringstermijnen</em>.</p>
            </section>

            <section>
            <h2>3. Hoe een verzoek indienen?</h2>
            <p>Dien uw verzoek in via <a href="mailto:info@windelsgreen-decoresin.com?subject=Verzoek%20tot%20gegevensverwijdering">info@windelsgreen-decoresin.com</a> met onderwerp <strong>&quot;Verzoek tot gegevensverwijdering&quot;</strong> en vermeld:</p>
            <ul class="voorwaarden">
                <li>Uw volledige naam</li>
                <li>Het geregistreerde e-mailadres</li>
                <li>Korte toelichting (optioneel)</li>
            </ul>
            <p>Wij kunnen om aanvullende identificatie vragen om misbruik te voorkomen.</p>
            </section>

            <section>
            <h2>4. Bevestiging en afhandeling</h2>
            <p>Wij reageren binnen <strong>30 dagen</strong> op uw verzoek. Bij complexe of meerdere verzoeken kan deze termijn met maximaal 2 maanden worden verlengd; u wordt daarvan op de hoogte gebracht.</p>
            <p>Na goedkeuring verwijderen of anonimiseren wij uw gegevens en ontvangt u een schriftelijke bevestiging. Gegevens in back-ups worden niet actief bewerkt maar verdwijnen door <em>rollover</em> volgens ons retentiebeleid en zijn in de tussentijd niet operationeel toegankelijk.</p>
            </section>

            <section>
            <h2>5. Gegevens bij verwerkers/derden</h2>
            <p>Waar van toepassing sturen wij uw verzoek door naar relevante verwerkers die wij inschakelen (zoals <strong>Mollie</strong> voor betalingen en <strong>Onfact</strong> voor facturatie), voor zover verwijdering juridisch mogelijk is. Houd rekening met hun wettelijke bewaarplichten.</p>
            </section>

            <section>
            <h2>6. Grenzen en weigering</h2>
            <ul class="voorwaarden">
                <li>Wij kunnen verzoeken weigeren die kennelijk ongegrond of buitensporig zijn (AVG art. 12).</li>
                <li>Verwijdering is niet mogelijk voor gegevens die wij wettelijk moeten bewaren (bv. facturen) of die noodzakelijk zijn voor rechtsvorderingen.</li>
                <li>Indien uw bestelling via een uitvaartpartner liep, kan afstemming met die partner nodig zijn om gekoppelde gegevens correct te verwijderen.</li>
            </ul>
            <p>Meer info over uw rechten vindt u in ons <a href="/pages/privacybeleid">privacybeleid</a> en de GDPR-pagina.</p>
            </section>
            <hr style="margin:25px 0;border:0;border-top:1px solid #ddd;">
            <p style="font-size:14px;color:#555;margin-top:10px;">
            Dit formulier of verzoek geldt enkel voor persoonsgegevens die door 
            <strong>Windels Green &amp; Deco Resin</strong> worden beheerd. 
            Voor gegevens die via een uitvaartpartner aan ons zijn bezorgd, kan ook de partner zelf verantwoordelijk zijn 
            voor verwerking en verwijdering.
            </p>

            <p>
            <strong>Verwerkingsverantwoordelijke:</strong><br>
            Windels Andy, handelend onder de naam <strong>Windels Green &amp; Deco Resin</strong><br>
            Beukenlaan 8, 3930 Hamont-Achel, België<br>
            E-mail: <a href="mailto:info@windelsgreen-decoresin.com">info@windelsgreen-decoresin.com</a>
            </p>
        </div>
    </div>
</main>

<style>
/* compacte serene stijl, in lijn met de rest */
.note{background:#fbfcfb;border:1px solid #cfd8cf;border-radius:10px;padding:10px;margin-top:1rem;}
.voorwaarden-card ul.voorwaarden{margin-left:2rem;list-style:disc;}

</style>

<?php include dirname($_SERVER['DOCUMENT_ROOT']).'/partials/footer.php'; ?>
