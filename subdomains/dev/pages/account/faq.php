<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']).'/secure/ini.inc';
session_start();
if(!isset($_SESSION['partner_id'])){
    header("Location: /pages/account/login.php");
    exit;
}
$partner_id=$_SESSION['partner_id'];
$partner_naam=$_SESSION['bedrijf_naam']??'Partner';
$is_actief=0;
$partner_email='';
$stmt=$mysqli->prepare("SELECT email,is_actief FROM funeral_partners WHERE id=?");
$stmt->bind_param('i',$partner_id);
$stmt->execute();
$result=$stmt->get_result();
if($row=$result->fetch_assoc()){
    $is_actief=(int)$row['is_actief'];
    $partner_email=isset($row['email'])?(string)$row['email']:'';
}
$stmt->close();
?>
<?php include dirname($_SERVER['DOCUMENT_ROOT']).'/partials/header.php'; ?>

<style>
body{background:url('/img/uitvaartachtergrond.jpg') no-repeat center center fixed;background-size:cover}
.faq-page{background-color:rgba(255,255,255,0.9);padding:3rem 2rem;border-radius:12px;box-shadow:0 4px 10px rgba(0,0,0,0.05);margin:3rem auto 2rem auto}
.faq-header{text-align:center;margin-bottom:1.5rem}
.faq-header h1{font-size:1.5rem;color:#2a5934;margin-bottom:.4rem}
.faq-header p{color:#555;font-size:.95rem}
.faq-intro{background:#f4f6f4;padding:1rem 1.2rem;border-radius:8px;margin-bottom:1.5rem;font-size:.9rem;color:#444}
.faq-list{max-width:900px;margin:0 auto}
.faq-item{margin-bottom:.7rem;border-radius:6px;background:#fff;box-shadow:0 2px 6px rgba(0,0,0,.04);overflow:hidden}
.faq-item summary{cursor:pointer;padding:.7rem 1rem;font-weight:600;font-size:.95rem;color:#2a5934;list-style:none;position:relative}
.faq-item summary::-webkit-details-marker{display:none}
.faq-item summary:after{content:'+';position:absolute;right:1rem;font-weight:700}
.faq-item[open] summary:after{content:'–'}
.faq-item p{padding:0 1rem 1rem 1rem;font-size:.9rem;color:#444;line-height:1.4}
.faq-item ul{padding:0 1.2rem 1rem 2.2rem;margin:0;font-size:.9rem;color:#444}
.faq-item li{margin-bottom:.25rem}
.faq-contact{margin-top:1.5rem;font-size:.9rem;text-align:center;color:#444}
.alert-deactivated{margin:20px auto;padding:15px;border-radius:8px;background:#f8d7da;color:#721c24;border:1px solid #f5c6cb;font-weight:bold;text-align:center}
.btn-home {
    background-color: #1e4025;
    color: white;
    padding: 0.7rem 1.5rem;
    border-radius: 30px;
    border: none;
    cursor: pointer;
    display: block;
    width: fit-content;
    margin: 2rem auto 0 auto;
    font-weight: 600;
    text-align: center;
    transition: background-color 0.2s ease;
    text-decoration: none;
}

.btn-home:hover {
    background-color: #2e6a3f;
}
</style>

<main class="faq-page container">
    <div class="faq-header">
        <h1>Veelgestelde vragen voor uitvaartpartners</h1>
        <p>Praktische info over bestellingen, as-verwerking en levering via Windels green &amp; deco resin.</p>
    </div>

    <?php if(empty($partner_email)): ?>
        <div class="alert-deactivated" role="alert">
            <strong>Opgelet:</strong> je account is ❌ verwijderd terwijl je nog was aangemeld.<br>
            Alle functies zijn uitgeschakeld. Log uit en neem contact op als je opnieuw toegang wenst.
        </div>
    <?php elseif(!$is_actief): ?>
        <div class="alert-deactivated" role="alert">
            <strong>Opgelet:</strong> Dit account is ❌ gedeactiveerd.<br>
            Bestellen is momenteel niet mogelijk. Neem contact met ons op om de samenwerking te heractiveren.
        </div>
    <?php endif; ?>

    <section class="faq-intro">
        <strong>Tip:</strong> gebruik deze pagina als houvast bij gesprekken met nabestaanden.  
        Hier vind je de meest voorkomende vragen rond levertijden, as-verwerking, personalisatie en facturatie.
    </section>

    <section class="faq-list">
        <details class="faq-item">
            <summary>Hoe geef ik een bestelling door namens een familie?</summary>
            <p>
                Bestellen verloopt uitsluitend via het partnerportaal. Ga in het menu naar <strong>“Nieuwe bestelling”</strong>,
                kies het gewenste product, vul de gegevens van de overledene en de familie in en bevestig de bestelling.
            </p>
            <p>
                Na het plaatsen ontvang je een bevestiging met ordernummer. Dit nummer kun je communiceren naar de familie.
            </p>
        </details>

        <details class="faq-item">
            <summary>Wanneer en hoe moet de as aangeleverd worden?</summary>
            <p>
                As hoeft niet tegelijk met de keuze van het product aangeleverd te worden. In de orderbevestiging staat
                duidelijk vermeld:
            </p>
            <ul>
                <li>hoeveel as wij nodig hebben per type product;</li>
                <li>in welke verpakking de as mag binnenkomen;</li>
                <li>naar welk adres de as verzonden of gebracht wordt.</li>
            </ul>
            <p>
                We vragen om de as altijd <strong>goed gelabeld</strong> aan te leveren met naam overledene en ordernummer.
            </p>
        </details>

        <details class="faq-item">
            <summary>Welke gemiddelde levertijd hanteren jullie?</summary>
            <p>
                De exacte levertijd wordt <strong>niet weergegeven in het partnerportaal</strong>, omdat deze afhangt van onze planning,
                de drukte, het type product en de levermethode richting de uitvaartpartner.
            </p>
            <p>
                Voor elk product hebben wij gemiddeld <strong>48 tot 72 uur nodig</strong> om het zorgvuldig te vervaardigen en volledig
                te laten uitharden. Daarna volgt de verzending of aflevering, afhankelijk van de snelheid van transport naar de
                uitvaartpartner.
            </p>
            <p>
                Als er tijdsdruk is of een uitvaart uitzonderlijk snel plaatsvindt, mag je ons altijd contacteren zodat we kunnen
                bekijken wat haalbaar is.
            </p>
        </details>


        <details class="faq-item">
            <summary>Kan het afgewerkte product na verloop van tijd vergelen?</summary>
            <p>
                We werken met kwalitatieve materialen en aandacht voor afwerking. Net als bij andere decoratieve stukken
                kan er op lange termijn lichte veroudering optreden, zeker bij blootstelling aan veel zonlicht of warmte.
            </p>
            <p>
                Adviseer families om het product niet in <strong>volle zon</strong> of in een <strong>vochtige omgeving</strong> te plaatsen.
                Zo blijft het resultaat zo lang mogelijk mooi. Een eventuele lichte verkleuring heeft geen invloed op de
                zorgvuldige verwerking van de as zelf.
            </p>
        </details>

        <details class="faq-item">
            <summary>Kunnen producten gepersonaliseerd worden (kleur, tekst, foto)?</summary>
            <p>
                Ja, de meeste items kunnen gepersonaliseerd worden in functie van de <strong>kleur</strong>. Denk aan:
            </p>
            <ul>
                <li>kleuraccenten in lijn met de uitvaartstijl;</li>
                <li>zachtere of net warmere tinten op vraag van de familie.</li>
            </ul>
            <p>
                Tekst- en <strong>fotoverwerking in het product zelf zijn op dit moment nog niet mogelijk</strong>.
                Kleurkeuze is dus wél mogelijk en biedt veel ruimte om het stuk toch persoonlijk en uniek te maken.
            </p>
        </details>

        <details class="faq-item">
            <summary>Hoe verloopt de communicatie over de status van een bestelling?</summary>
            <p>
                Via het dashboard zie je per bestelling de actuele status (bijvoorbeeld “As ontvangen”, “In productie”,
                “Klaar voor levering”). Bij belangrijke updates sturen we eventueel ook een e-mail naar het bij ons bekende
                contactadres.
            </p>
        </details>

        <details class="faq-item">
            <summary>Hoe en wanneer worden facturen opgemaakt?</summary>
            <p>
                Als uitvaartpartner kun je in je dashboard onder <strong>“Mijn facturen” → “Facturatie-instellingen”</strong>
                zelf aanduiden <strong>wanneer en hoe</strong> je gefactureerd wilt worden (bijvoorbeeld per bestelling,
                wekelijks of maandelijks).
            </p>
            <p>
                Op basis van die instelling worden facturen automatisch via e-mail verstuurd. Ze zijn ook steeds terug te
                vinden in het partnerportaal onder <strong>“Mijn facturen”</strong>, met een duidelijk overzicht per
                dossier en bestelling.
            </p>
        </details>

        <details class="faq-item">
            <summary>Wat als er een fout of wijziging is in een bestelling?</summary>
            <p>
                Neem zo snel mogelijk contact op via het portaal (contactpagina) of telefonisch. Vermeld altijd het
                <strong>ordernummer</strong> en de naam van de overledene of het dossier.
            </p>
            <p>
                Zolang een product nog niet gegoten of definitief afgewerkt is, zoeken we samen naar een passende oplossing.
            </p>
        </details>

        <details class="faq-item">
            <summary>Kunnen families rechtstreeks bij jullie bestellen?</summary>
            <p>
                Ja, particulieren kunnen ook rechtstreeks bij ons terecht. Voor producten waarbij as-verwerking nodig is,
                werken we echter bij voorkeur via jullie als uitvaartpartner, zodat alles vlot en respectvol geregeld wordt.
            </p>
        </details>

        <details class="faq-item">
            <summary>Wat bij spoed of speciale wensen (bv. strakke deadline)?</summary>
            <p>
                In uitzonderlijke situaties kun je best even telefonisch of via e-mail contact opnemen. Geef duidelijk aan:
            </p>
            <ul>
                <li>welk product gewenst is;</li>
                <li>tegen welke datum het klaar moet zijn;</li>
                <li>in welke regio de uitvaart plaatsvindt.</li>
            </ul>
            <p>
                We bekijken dan samen wat haalbaar is.
            </p>
        </details>
    </section>

    <div class="faq-contact">
        Nog vragen of een specifieke situatie?<br>
        Neem contact op met ons team via het partnerportaal of de gekende contactgegevens.
    </div>
    <a href="/pages/account/dashboard.php" class="btn-home">← Terug naar dashboard</a>  
</main>

<?php include dirname($_SERVER['DOCUMENT_ROOT']).'/partials/footer.php'; ?>
