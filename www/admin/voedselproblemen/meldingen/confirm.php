<?php


 $web = $_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING'];
 $usernameadmin = $_SESSION['admin_username'];

 $secondsWait = 3600; // these are seconds so it is 300s=5minutes
 //$secondsWait = 60;
 //1200=60minutes
 header("refresh:$secondsWait; /loginout.php?web=$web&adminuser=$usernameadmin");
require $_SERVER["DOCUMENT_ROOT"] . '/ini.inc';
require $_SERVER["DOCUMENT_ROOT"] . '/header.php';
require $_SERVER["DOCUMENT_ROOT"] . '/voedselproblemen/templates/header.php';

if (isset($_GET['dossiernummer'])) {
    $dossiernummer = $_GET['dossiernummer'];
} else {
    header("Location: view.php");
    exit();
}
?>

<div class="card">
    <div class="card-header bg-success text-white">
        <h3>Melding Succesvol Aangemaakt</h3>
    </div>
    <div class="card-body">
        <div class="alert alert-success">
            Uw melding is succesvol opgeslagen met dossiernummer: <strong><?php echo htmlspecialchars($dossiernummer); ?></strong>
        </div>
        <a href="view.php" class="btn btn-primary">Bekijk Alle Meldingen</a>
        <a href="../index.php" class="btn btn-secondary">Home</a>
    </div>
</div>

<?php
require $_SERVER["DOCUMENT_ROOT"] . '/footer.php';
?>
