<?php
session_start();
if(!isset($_SESSION['partner_id'])){
    header("Location:/pages/account/login.php");
    exit;
}

require_once dirname($_SERVER['DOCUMENT_ROOT']).'/secure/ini.inc';

$partner_id=$_SESSION['partner_id'];
$success='';
$error='';

// ✅ Verwerk formulier
if($_SERVER['REQUEST_METHOD']==='POST'){
    $bedrijfsnaam=trim($_POST['bedrijfsnaam']??'');
    $adres=trim($_POST['adres']??'');
    $telefoon=trim($_POST['telefoon']??'');

    if($bedrijfsnaam!=='' && $adres!==''){
        $stmt=$mysqli->prepare("UPDATE funeral_partners SET bedrijf_naam=?, adres=?, telefoon=? WHERE id=?");
        $stmt->bind_param("sssi",$bedrijfsnaam,$adres,$telefoon,$partner_id);
        if($stmt->execute()){
            $success="Gegevens succesvol bijgewerkt.";
        }else{
            $error="Er is iets misgegaan bij het opslaan.";
        }
        $stmt->close();
    }else{
        $error="Gelieve alle verplichte velden in te vullen.";
    }
}

// ✅ Haal actuele data op
$stmt=$mysqli->prepare("SELECT bedrijf_naam, adres, telefoon, email, btw_nummer FROM funeral_partners WHERE id=?");
$stmt->bind_param('i',$partner_id);
$stmt->execute();
$result=$stmt->get_result();
$partner=$result->fetch_assoc()??[
    'bedrijf_naam'=>'',
    'adres'=>'',
    'telefoon'=>'',
    'email'=>'',
    'btw_nummer'=>''
];
$stmt->close();

include dirname($_SERVER['DOCUMENT_ROOT']).'/partials/header.php';
?>
<style>
body{background:url('/img/uitvaartachtergrond.jpg') no-repeat center center fixed;background-size:cover;}
.profiel-pagina{background-color:rgba(255,255,255,.9);padding:3rem 2rem;border-radius:18px;box-shadow:0 4px 10px rgba(0,0,0,.05);margin:3rem auto 2rem auto;max-width:800px;}
.profiel-pagina h1{font-size:1.9rem;margin-bottom:.5rem;color:#1e4025;}
.profiel-subtext{margin:0 0 1.5rem 0;color:#444;font-size:.95rem;}
.back-wrapper{text-align:left;margin-bottom:1.5rem;}
.back-btn{display:inline-block;background-color:#1e4025;color:#fff !important;padding:.45rem 1.2rem;border-radius:30px;text-decoration:none;font-weight:600;font-size:.9rem;transition:background-color .2s ease;}
.back-btn:hover{background-color:#2e6a3f;}
.simple-form{display:flex;flex-direction:column;gap:1rem;margin-top:1rem;}
.simple-form label{display:block;font-weight:500;color:#1e4025;font-size:.95rem;}
.simple-form input[type="text"],
.simple-form textarea{width:100%;margin-top:.25rem;border:1px solid #ccc;border-radius:10px;padding:.6rem;font-size:.95rem;}
.simple-form textarea{min-height:80px;resize:vertical;}
.simple-form small{font-size:.8rem;}
.btn{margin-top:1rem;background-color:#1e4025;color:#fff;padding:.6rem 1.4rem;border-radius:30px;border:none;font-weight:600;cursor:pointer;}
.btn:hover{background-color:#2e6a3f;}
.alert{padding:.7rem 1rem;border-radius:8px;margin-bottom:1rem;font-size:.9rem;}
.alert.success{background-color:#e0f4e5;border:1px solid #bde5ce;color:#155724;}
.alert.error{background-color:#f8d7da;border:1px solid #f5c2c7;color:#842029;}
.profiel-pagina a{text-decoration:none;color:#1e4025;font-weight:500;}
.profiel-pagina a:hover{text-decoration:underline;}
@media(max-width:768px){
    .profiel-pagina{padding:2rem 1.5rem;margin:2rem 1rem;}
    .profiel-pagina h1{font-size:1.6rem;}
}
</style>

<main class="container profiel-pagina">
    <div class="back-wrapper">
        <a href="/pages/account/profiel/index.php" class="back-btn">← Terug naar profiel</a>
    </div>

    <h1>Accountgegevens wijzigen</h1>
    <p class="profiel-subtext">
        Dit zijn jouw interne accountgegevens als uitvaartpartner. Deze informatie wordt niet publiek getoond, maar gebruikt voor facturatie en contact.
    </p>

    <?php if($success):?>
        <p class="alert success"><?=htmlspecialchars($success)?></p>
    <?php elseif($error):?>
        <p class="alert error"><?=htmlspecialchars($error)?></p>
    <?php endif;?>

    <form method="post" class="simple-form">
        <label>Bedrijfsnaam
            <input type="text" name="bedrijfsnaam" value="<?=htmlspecialchars($partner['bedrijf_naam'])?>" required>
        </label>

        <label>B.T.W. nummer <span style="color:red">*</span>
            <input type="text" value="<?=htmlspecialchars($partner['btw_nummer'])?>" readonly>
        </label>

        <label>Adres
            <textarea name="adres" required><?=htmlspecialchars($partner['adres'])?></textarea>
        </label>

        <label>Telefoonnummer
            <input type="text" name="telefoon" value="<?=htmlspecialchars($partner['telefoon'])?>" required>
        </label>

        <label>E-mailadres <span style="color:red">*</span>
            <input type="text" value="<?=htmlspecialchars($partner['email'])?>" readonly>
        </label>

        <small style="color:red;display:block;">
            <i>* Het btw-nummer en e-mailadres kunnen niet worden gewijzigd. Aanpassingen zijn alleen mogelijk via een aanvraag per e-mail.</i>
        </small>

        <button type="submit" class="btn">Opslaan</button>
    </form>
</main>

<?php include dirname($_SERVER['DOCUMENT_ROOT']).'/partials/footer.php'; ?>
