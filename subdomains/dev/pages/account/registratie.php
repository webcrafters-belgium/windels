<?php
// Namespaces & autoload helemaal bovenaan:
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Dotenv\Dotenv;

// ⬇️ Zorg dat $mysqli een geldige mysqli-connectie is.
// Voorbeeld: $mysqli = new mysqli($_ENV['DB_HOST'], $_ENV['DB_USER'], $_ENV['DB_PASS'], 'pdo_uitvaart');
include dirname($_SERVER['DOCUMENT_ROOT']) . '/secure/ini.inc';


$errors=[];
$success='';

// .env laden
try{
    $dotenv=Dotenv::createImmutable(dirname($_SERVER['DOCUMENT_ROOT']));
    $dotenv->load();
}catch(Throwable $e){
    error_log('Dotenv load failed: '.$e->getMessage());
}

// Helper: 1 plek voor SMTP-config
function makeMailer(): PHPMailer {
    $m=new PHPMailer(true);
    $m->isSMTP();
    $m->Host='smtp-auth.mailprotect.be';
    $m->SMTPAuth=true;
    $m->Username=$_ENV['MAIL_USERNAME'] ?? '';
    $m->Password=$_ENV['MAIL_PASSWORD'] ?? '';
    $m->SMTPSecure='ssl';
    $m->Port=465;
    $m->CharSet='UTF-8';
    $m->isHTML(true);
    $m->Timeout=15;
    $m->SMTPKeepAlive=false;
    return $m;
}

// Emailtemplate ophalen
function renderTemplate(string $title,string $header,string $body): string {
    $tplPath=$_SERVER['DOCUMENT_ROOT'].'/emailtemplate.php';
    if(!is_file($tplPath)){
        error_log('Emailtemplate ontbreekt: '.$tplPath);
        return "<h2>".htmlspecialchars($header,ENT_QUOTES,'UTF-8')."</h2>".$body;
    }
    $tpl=file_get_contents($tplPath);
    return str_replace(['{titlemail}','{headermail}','{bodymail}'],[$title,$header,$body],$tpl);
}

if(!empty($_GET['success'])){
    $success=htmlspecialchars($_GET['success'],ENT_QUOTES,'UTF-8');
}

if($_SERVER['REQUEST_METHOD']==='POST'){
    // Input ophalen en opschonen
    $bedrijf = trim($_POST['bedrijf_naam'] ?? '');
    $contact = trim($_POST['contact_naam'] ?? '');
    $email   = trim($_POST['email'] ?? '');
    $telefoon= trim($_POST['telefoon'] ?? '');
    $btw     = trim($_POST['btw_nummer'] ?? '');
    $street  = trim($_POST['street'] ?? '');
    $zipcode = trim($_POST['zipcode'] ?? '');
    $city    = trim($_POST['city'] ?? '');
    $country = trim($_POST['country'] ?? '');

    // Adres samenstellen
    $adresParts=array_filter([$street, trim("$zipcode $city"), $country]);
    $adres=implode(', ',$adresParts);

    // BTW opschonen
    $btw=strtoupper(str_replace([' ','-','.'],'', $btw));

    // Validaties
    if($bedrijf===''||$contact===''||$email===''||$btw===''||$street===''||$zipcode===''||$city===''||$country===''){
        $errors[]="Alle verplichte velden invullen.";
    }
    if($email!=='' && !filter_var($email,FILTER_VALIDATE_EMAIL)){
        $errors[]="Vul een geldig e-mailadres in.";
    }
    if($btw!=='' && !preg_match('/^(BE[0-9]{10}|NL[0-9]{9}B[0-9]{2})$/',$btw)){
        $errors[]="Vul een geldig BTW-nummer in (bv. BE0123456789).";
    }

    // Dubbele registratie checken (mysqli)
    if(empty($errors)){
        if(!isset($mysqli) || !($mysqli instanceof mysqli)){
            $errors[]="Databaseverbinding ontbreekt.";
        } else {
            $sql="SELECT COUNT(*) FROM funeral_partners_aanvraag WHERE email=? OR btw_nummer=?";
            if($stmt=$mysqli->prepare($sql)){
                $stmt->bind_param("ss", $email, $btw);
                if($stmt->execute()){
                    $stmt->bind_result($cnt);
                    $stmt->fetch();
                    if(($cnt ?? 0) > 0){
                        $errors[]="Dit e-mailadres of BTW-nummer is al geregistreerd.";
                    }
                } else {
                    error_log('mysqli execute error (duplicate check): '.$stmt->error);
                    $errors[]="Technische fout bij controle. Probeer het later opnieuw.";
                }
                $stmt->close();
            } else {
                error_log('mysqli prepare error (duplicate check): '.$mysqli->error);
                $errors[]="Technische fout bij controle. Probeer het later opnieuw.";
            }
        }
    }

    // Opslaan & mails (mysqli)
    if(empty($errors)){
        $sql="INSERT INTO funeral_partners_aanvraag
              (bedrijf_naam, contact_naam, email, telefoon, btw_nummer, adres, created_at)
              VALUES (?,?,?,?,?,?,NOW())";
        if($stmt=$mysqli->prepare($sql)){
            $stmt->bind_param("ssssss", $bedrijf, $contact, $email, $telefoon, $btw, $adres);
            if($stmt->execute() && $stmt->affected_rows>0){

                // ========== MAIL NAAR BEHEER ==========
                try{
                    $mailAdmin=makeMailer();
                    $mailAdmin->setFrom('info@windelsgreen-decoresin.com','Website Registratie');
                    $mailAdmin->addAddress('uitvaart@windelsgreen-decoresin.com'); // alleen intern
                    if(filter_var($email,FILTER_VALIDATE_EMAIL)){
                        $mailAdmin->addReplyTo($email, $contact ?: $email);
                    }

                    $title='Nieuwe registratieaanvraag: '.htmlspecialchars($bedrijf,ENT_QUOTES,'UTF-8');
                    $header='Nieuwe aanvraag voor een uitvaartdienst-account';
                    $body =
                        "<p><strong>Bedrijfsnaam:</strong> ".htmlspecialchars($bedrijf,ENT_QUOTES,'UTF-8')."</p>".
                        "<p><strong>Contactpersoon:</strong> ".htmlspecialchars($contact,ENT_QUOTES,'UTF-8')."</p>".
                        "<p><strong>E-mail:</strong> ".htmlspecialchars($email,ENT_QUOTES,'UTF-8')."</p>".
                        "<p><strong>Telefoon:</strong> ".htmlspecialchars($telefoon,ENT_QUOTES,'UTF-8')."</p>".
                        "<p><strong>BTW-nummer:</strong> ".htmlspecialchars($btw,ENT_QUOTES,'UTF-8')."</p>".
                        "<p><strong>Adres:</strong> ".htmlspecialchars($adres,ENT_QUOTES,'UTF-8')."</p>";

                    $mailAdmin->Subject=$title;
                    $mailAdmin->Body=renderTemplate($title,$header,$body);
                    $mailAdmin->AltBody=strip_tags(
                        $header."\n\n".
                        preg_replace('/<br\s*\/?>/i',"\n",str_replace(['</p>','<p>'],["\n","\n"],$body))
                    );
                    $mailAdmin->send();
                }catch(Exception $e){
                    error_log('Registratiemail admin fout: '.$e->getMessage());
                }

                // ========== MAIL NAAR AANVRAGER ==========
                try{
                    $mailKlant=makeMailer();
                    $mailKlant->setFrom('info@windelsgreen-decoresin.com','Windels Green & Deco Resin');
                    $mailKlant->addAddress($email); // alleen naar de klant

                    $title='Bevestiging registratieaanvraag';
                    $header='Uw registratieaanvraag is ontvangen';
                    $body=
                        "<p>Beste ".htmlspecialchars($contact,ENT_QUOTES,'UTF-8').",</p>".
                        "<p>We hebben uw aanvraag voor toegang tot ons bestelplatform ontvangen. ".
                        "Na controle van uw gegevens ontvangt u van ons een gebruikersnaam en tijdelijk wachtwoord.</p>".
                        "<p>Met vriendelijke groet,<br>Windels Green & Deco Resin</p>";

                    $mailKlant->Subject=$title;
                    $mailKlant->Body=renderTemplate($title,$header,$body);
                    $mailKlant->AltBody=strip_tags(
                        $header."\n\n".
                        preg_replace('/<br\s*\/?>/i',"\n",str_replace(['</p>','<p>'],["\n","\n"],$body))
                    );
                    $mailKlant->send();
                }catch(Exception $e){
                    error_log('Registratiemail klant fout: '.$e->getMessage());
                }

                header("Location: ".$_SERVER['PHP_SELF']."?success=".urlencode("Aanvraag succesvol verzonden. U ontvangt spoedig een reactie."));
                exit;
            } else {
                error_log('mysqli execute/affected_rows error (insert): '.$stmt->error);
                $errors[]="Er is iets misgegaan bij het aanvragen. Probeer het later opnieuw.";
            }
            $stmt->close();
        } else {
            error_log('mysqli prepare error (insert): '.$mysqli->error);
            $errors[]="Er is iets misgegaan bij het aanvragen. Probeer het later opnieuw.";
        }
    }
}
include dirname($_SERVER['DOCUMENT_ROOT']) . '/partials/header.php';
?>
<style>
.simple-form label{display:block;margin-bottom:.2rem;font-weight:500}
.simple-form input,
.simple-form select{
width:100%;
padding:.7rem 1rem;
border:1px solid #ccc;
border-radius:6px;
font-size:1rem;
box-sizing:border-box;
margin-top:.3rem;
}
.simple-form input:focus,
.simple-form select:focus{
border-color:#2d4f39;
outline:none;
}
.simple-form p{margin:1rem 0 .1rem;font-weight:600}

</style>
<main class="registratie-info">
    <section class="registratie-hero">
        <div class="container">
            <h1>Registratie Uitvaartdienst</h1>
            <p class="subtitel">Alleen erkende uitvaartdiensten kunnen een account aanvragen via persoonlijk contact of het onderstaande formulier</p>
        </div>
    </section>

    <section class="registratie-uitleg">
        <div class="container">
            <div class="uitleg-tekst">
                <p>
                    Om toegang te krijgen tot ons gesloten bestelsysteem voor uitvaartdiensten, vragen wij u om contact met ons op te nemen of het formulier hiernaast in te vullen.<br><br>
                    Wij werken uitsluitend met erkende uitvaartdiensten.
                    Bij uw aanvraag hebben wij de volgende gegevens nodig:
                </p>
                <ul>
                    <li>Geldige bedrijfsnaam</li>
                    <li>(Geldige) BTW-nummer <small>(ook voor niet btw plichtige bedrijven)</small></li>
                    <li>Volledig adres van uw onderneming</li>
                    <li>Zakelijk e-mailadres</li>
                    <li>Telefoonnummer</li>
                </ul>
                <p>
                    Na controle van uw gegevens ontvangt u van ons een gebruikersnaam en een tijdelijk wachtwoord.
                    Daarna kunt u veilig inloggen en gebruikmaken van onze bestelomgeving voor gepersonaliseerde herinneringsproducten.
                </p>
                <p>
                    Voor uitvaartpartners uit Nederland wordt de btw automatisch verlegd volgens de geldende Europese regelgeving.
                </p>

                <p>
                    Na goedkeuring krijgt u toegang tot uw volledige partneromgeving. Daar kunt u:
                </p>
                <ul>
                    <li>Bestellingen plaatsen voor gepersonaliseerde decoraties met asverwerking.</li>
                    <li>Productinformatie, prijzen en levertijden raadplegen.</li>
                    <li>Besteloverzicht en orderhistoriek bekijken.</li>
                    <li>Maandelijkse facturen inzien en downloaden.</li>
                    <li>Uw bedrijfsgegevens en contactinformatie beheren.</li>
                </ul>
                <a href="/pages/contact/contact.php" class="btn">Neem contact op</a>
                <a href="/index.php" class="btn" style="margin-left:1rem;">Terug naar homepagina</a>
            </div>
            <div class="form-col">
                <div class="card">
                    <?php if ($success): ?>
                        <div class="custom-alert success">
                            <span class="alert-icon">&#10003;</span>
                            <?= htmlspecialchars($success, ENT_QUOTES, 'UTF-8') ?>
                        </div>
                    <?php endif; ?>

                    <?php foreach ($errors as $err): ?>
                        <div class="custom-alert error">
                            <span class="alert-icon">&#9888;</span>
                            <?= htmlspecialchars($err, ENT_QUOTES, 'UTF-8') ?>
                        </div>
                    <?php endforeach; ?>

                    <form method="post" class="simple-form">
                        <label>Bedrijfsnaam
                        <input type="text" name="bedrijf_naam" value="<?= htmlspecialchars($bedrijf ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
                        </label>
                        <label>Contactpersoon
                        <input type="text" name="contact_naam" value="<?= htmlspecialchars($contact ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
                        </label>
                        <p>Bedrijfsgegevens</p>
                        <label>Straat + huisnummer + postbus
                        <input type="text" name="street" value="<?= htmlspecialchars($street ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
                        </label>
                        <label>Postcode
                        <input type="text" name="zipcode" value="<?= htmlspecialchars($zipcode ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
                        </label>
                        <label>Gemeente/stad
                        <input type="text" name="city" value="<?= htmlspecialchars($city ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
                        </label>
                        <label>Land
                        <select name="country" required>
                            <option value="Belgie" <?= ($country ?? '')==='Belgie' ? 'selected' : '' ?>>Belgie</option>
                            <option value="Nederland" <?= ($country ?? '')==='Nederland' ? 'selected' : '' ?>>Nederland</option>
                        </select>
                        </label>
                        <label>Vaste Telefoon of Mobiel
                        <input type="tel" name="telefoon" value="<?= htmlspecialchars($telefoon ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
                        </label>
                        <label>Emailadres
                        <input type="email" name="email" value="<?= htmlspecialchars($email ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
                        </label>
                        <label>B.T.W. Nummer
                        <input type="text" name="btw_nummer" value="<?= htmlspecialchars($btw ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
                        </label>
                        <p>Alle velden zijn verplicht in te vullen.</p>
                        <button type="submit" class="btn">Accountaanvraag indienen</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
</main>

<?php include dirname($_SERVER['DOCUMENT_ROOT']) . '/partials/footer.php'; ?>
