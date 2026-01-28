<?php
session_start();
if(!isset($_SESSION['partner_id'])){
    header("Location: /pages/account/login.php");
    exit;
}
require_once dirname($_SERVER['DOCUMENT_ROOT']).'/secure/ini.inc';

$partner_id=(int)$_SESSION['partner_id'];
$is_actief=0;
$partner_email='';
$success='';
$error='';

// Partnerstatus ophalen
$stmt=$mysqli->prepare("SELECT email,is_actief FROM funeral_partners WHERE id=?");
$stmt->bind_param('i',$partner_id);
$stmt->execute();
$result=$stmt->get_result();
if($row=$result->fetch_assoc()){
    $is_actief=(int)$row['is_actief'];
    $partner_email=$row['email']??'';
}
$stmt->close();

// datumlogica
$today=new DateTime('today');
$minDate=(clone $today)->modify('+30 days');
$minDateStr=$minDate->format('Y-m-d');

// ✅ Verwerk formulier (alleen als account nog actief is)
if($_SERVER['REQUEST_METHOD']==='POST' && $is_actief){

    $end_date_str=$_POST['end_date']??'';
    $reason=trim($_POST['reason']??'');
    $confirm=isset($_POST['confirm'])?1:0;

    // basisvalidatie
    if(empty($end_date_str)){
        $error="Gelieve een gewenste einddatum te kiezen.";
    }else{
        $endDate=DateTime::createFromFormat('Y-m-d',$end_date_str);
        $dateErrors=DateTime::getLastErrors();

        if(!$endDate || !empty($dateErrors['warning_count']) || !empty($dateErrors['error_count'])){
            $error="De opgegeven datum is ongeldig.";
        }elseif($endDate < $minDate){
            $error="De einddatum moet minstens 30 dagen na vandaag liggen (minimaal ".$minDateStr.").";
        }
    }

    if(!$error && !$confirm){
        $error="Bevestig dat je zelf lopende abonnementen tijdig stopzet om extra kosten te vermijden.";
    }

    if(!$error){
        // Opzegaanvraag registreren
        // Zorg dat tabel funeral_partner_cancellations bestaat:
        // id (PK), partner_id (int), end_date (date), reason (text), created_at (datetime)
        $stmt=$mysqli->prepare("
            INSERT INTO funeral_partner_cancellations (partner_id,end_date,reason,created_at)
            VALUES (?,?,?,NOW())
        ");
        $end_date_db=$endDate->format('Y-m-d');
        $stmt->bind_param('iss',$partner_id,$end_date_db,$reason);
        if($stmt->execute()){
            $success="Je opzegging is geregistreerd. We nemen je aanvraag in behandeling. Je account blijft actief tot ".$end_date_db.".";
        }else{
            $error="Er is iets misgegaan bij het registreren van de opzegging. Probeer later opnieuw of contacteer ons.";
        }
        $stmt->close();
    }
}

include dirname($_SERVER['DOCUMENT_ROOT']).'/partials/header.php';
?>
<style>
body{
    background:url('/img/uitvaartachtergrond.jpg') no-repeat center center fixed;
    background-size:cover;
}
.dashboard-page{
    background-color:rgba(255,255,255,.9);
    padding:3rem 2rem;
    border-radius:12px;
    box-shadow:0 4px 10px rgba(0,0,0,.05);
    margin:3rem auto 2rem auto;
    max-width:800px;
}
.dashboard-welcome{
    background-color:#f4f6f4;
    padding:1.5rem 1.5rem;
    border-radius:8px;
    margin-bottom:1.5rem;
    text-align:center;
    box-shadow:0 4px 12px rgba(0,0,0,.04);
}
.dashboard-welcome h1{
    margin-bottom:.5rem;
    font-size:1.6rem;
    color:#2a5934;
}
.dashboard-welcome p{
    font-size:.98rem;
    color:#444;
    margin-bottom:.3rem;
}
.alert-deactivated{
    margin:20px auto;
    padding:15px;
    border-radius:8px;
    background:#f8d7da;
    color:#721c24;
    border:1px solid #f5c6cb;
    font-weight:bold;
    max-width:600px;
    text-align:center;
}
.alert-success{
    margin:20px auto;
    padding:15px;
    border-radius:8px;
    background:#e0f4e5;
    color:#155724;
    border:1px solid #bde5ce;
    max-width:600px;
    text-align:center;
}
.alert-error{
    margin:20px auto;
    padding:15px;
    border-radius:8px;
    background:#f8d7da;
    color:#721c24;
    border:1px solid #f5c6cb;
    max-width:600px;
    text-align:center;
}
.opzeg-form{
    margin-top:1.5rem;
}
.opzeg-form label{
    display:block;
    margin-bottom:.8rem;
    font-weight:500;
    color:#1e4025;
}
.opzeg-form input[type="date"],
.opzeg-form textarea{
    width:100%;
    margin-top:.25rem;
    border:1px solid #ccc;
    border-radius:10px;
    padding:.6rem;
    font-size:.95rem;
}
.opzeg-form textarea{min-height:90px;resize:vertical;}
.opzeg-form small{
    display:block;
    margin-top:.2rem;
    font-size:.8rem;
    color:#666;
}
.opzeg-form .checkbox-row{
    margin-top:1rem;
}
.opzeg-form .checkbox-row label{
    font-weight:400;
    display:flex;
    align-items:flex-start;
    gap:.5rem;
    color:#333;
}
.opzeg-form .checkbox-row input[type="checkbox"]{
    margin-top:.15rem;
}
.btn-home{
    background-color:#1e4025;
    color:white !important;
    padding:.7rem 1.5rem;
    border-radius:30px;
    border:none;
    cursor:pointer;
    display:block;
    width:fit-content;
    margin:2rem auto 0 auto;
    font-weight:600;
    text-align:center;
    transition:background-color .2s ease;
    text-decoration:none;
}
.btn-home:hover{
    background-color:#2e6a3f;
}
.btn-submit{
    margin-top:1rem;
    background-color:#1e4025;
    color:#fff;
    padding:.6rem 1.4rem;
    border-radius:30px;
    border:none;
    font-weight:600;
    cursor:pointer;
}
.btn-submit:hover{
    background-color:#2e6a3f;
}
.info-opzeg{
    font-size:.9rem;
    color:#444;
    margin-top:.5rem;
}
.info-opzeg strong{color:#1e4025;}
</style>

<main class="dashboard-page container">
    <div class="dashboard-welcome">
        <h1>Account beëindigen</h1>
        <p>Hier kan je jouw samenwerking als uitvaartpartner opzeggen.</p>
        <p class="info-opzeg">
            We hanteren een <strong>opzegtermijn van minstens 30 dagen</strong>. 
            De gekozen einddatum moet dus minimaal <?=htmlspecialchars($minDateStr)?> zijn.
        </p>
        <p class="info-opzeg">
            Let op: heb je lopende <strong>abonnementen of externe diensten</strong> (bijvoorbeeld software, advertenties of koppelingen)? 
            Zorg dat je deze ook <strong>tijdig zelf stopzet</strong> om onnodige kosten te vermijden.
        </p>
    </div>

    <?php if(!$is_actief):?>
        <div class="alert-deactivated">
            Dit account is reeds gedeactiveerd. Er kan geen nieuwe opzegging meer geregistreerd worden.
        </div>
    <?php else:?>

        <?php if($success):?>
            <div class="alert-success"><?=htmlspecialchars($success)?></div>
        <?php elseif($error):?>
            <div class="alert-error"><?=htmlspecialchars($error)?></div>
        <?php endif;?>

        <form method="post" class="opzeg-form">
            <label for="end_date">
                Gewenste einddatum samenwerking
                <input type="date" id="end_date" name="end_date" min="<?=htmlspecialchars($minDateStr)?>" required>
                <small>De einddatum moet minstens 30 dagen na vandaag liggen.</small>
            </label>

            <label for="reason">
                Reden van opzegging (optioneel)
                <textarea id="reason" name="reason" placeholder="Je mag hier kort toelichten waarom je wilt stoppen."></textarea>
            </label>

            <div class="checkbox-row">
                <label>
                    <input type="checkbox" name="confirm" value="1">
                    <span>Ik bevestig dat ik zelf eventuele lopende abonnementen en externe diensten tijdig stopzet om overbodige kosten te vermijden.</span>
                </label>
            </div>

            <button type="submit" class="btn-submit">Opzegging doorgeven</button>
        </form>
    <?php endif;?>

    <a href="../dashboard.php" class="btn-home">Terug naar dashboard</a>
</main>

<?php include dirname($_SERVER['DOCUMENT_ROOT']).'/partials/footer.php'; ?>
