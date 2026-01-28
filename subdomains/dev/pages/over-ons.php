<?php include dirname($_SERVER['DOCUMENT_ROOT']) . '/partials/header.php'; ?>

<main class="overons-hero">
    <div class="overons-container">
        <h1>Over Ons</h1>
        <p class="subtitel">Een verhaal van groei, ambacht en betekenis</p>
    </div>
</main>

<section class="overons-inhoud">
    <div class="container overons-grid">
        <div class="overons-foto">
            <img src="/img/mijn-foto.jpg" alt="Foto van de oprichter" loading="lazy">
        </div>
        <div class="overons-tekst">
            <h2>Hoe het allemaal begon</h2>
            <p>
            Mijn naam is Andy Windels, ik ben <?php $startJaar = 1991; $huidigJaar = date("Y"); $jarenVerstreken = $huidigJaar - $startJaar; echo "$jarenVerstreken";?>jaar, en ik ben woonachtig in Hamont-Achel.
            </p>
            <p>
                In 2016 begon ik uit pure nieuwsgierigheid met het verkopen van tweedehands spullen en… walnoten. Het was gewoon een hobby. Maar het bracht me in contact met mensen, met creativiteit en met het plezier van iets tastbaars aanbieden. In 2019 zijn we gestart met de verkoop van verse groenten, en dat groeide langzaam uit tot een mooi, lokaal netwerk.
            </p>
            <p>
                In juli 2023 heb ik de stap gezet om mijn passie officieel te maken en ben ik gestart als zelfstandige in bijberoep, onder de naam <strong>Windels green & deco resin</strong>. Vanaf toen zijn we begonnen met werken met hars — een fascinerend materiaal dat toelaat om unieke decoraties, terrazzo-elementen en kaarsen te maken. Alles met de hand, in kleine oplages, met zorg.
            </p>
            <p>
                Maar de echte wending kwam er dankzij een tevreden klant. Die vroeg ons of we iets konden maken waarin een beetje as verwerkt kon worden — een tastbaar aandenken aan een dierbare. We zijn gaan experimenteren, met respect en voorzichtigheid. Het resultaat raakte niet alleen hem of haar… maar ook ons.
            </p>
            <p>
                Sindsdien maken we in opdracht van uitvaartverzorgers unieke herinneringsproducten waarin as verwerkt zit. Elk stuk is een eerbetoon. Geen massa, geen kopieën — alleen eerlijke, handgemaakte creaties voor mensen die afscheid moeten nemen.
            </p>
            <p>
                Wat ooit begon met een doos walnoten, is uitgegroeid tot iets dat écht betekenis heeft. En dat is iets waar ik elke dag met trots aan werk.
            </p>
            <p>
                Naast mijn werk in bijberoep blijf ik ook actief als winkelmedewerker. Het combineren van beide werelden vraagt veel inzet, maar het geeft me ook energie. Mijn passie voor ambacht en het helpen van mensen loopt als een rode draad door alles wat ik doe. Uit respect voor mijn huidige werkgever delen we verder geen details over deze functie — maar het is net die balans tussen vast werk en creatieve vrijheid die me vooruit drijft.
            </p>
            <p>
            Met die balans tussen zekerheid en creatieve vrijheid wil ik elke dag met aandacht, vakmanschap en respect werken aan wat er écht toe doet. Bedankt om mijn verhaal te lezen — als ik iets voor jou of een nabestaande kan betekenen, hoor ik het graag.
            </p>
            <div class="cta-buttons" style="margin-top:2rem; text-align:center;">
                <a href="/pages/contact.php" class="btn">Neem contact op</a>
                <a href="/index.php" class="btn" style="margin-left:1rem; background-color:#e2e2e2; color:#333;">← Terug naar home</a>
            </div>
        </div>
    </div>
</section>

<?php include dirname($_SERVER['DOCUMENT_ROOT']) . '/partials/footer.php'; ?>
