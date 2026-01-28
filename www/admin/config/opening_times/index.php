<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';
session_start();

if (!isset($_SESSION['user']['role']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: /pages/account/login");
    exit;
}

include $_SERVER['DOCUMENT_ROOT'] . '/header.php';
?>

<div class="container py-5">
    <h2 class="mb-4"><i class="bi bi-clock-history"></i> Beheer Openingstijden</h2>
    <p class="mb-4">Hieronder kan je alle tijdsinstellingen van de winkel beheren: standaardtijden, speciale openingen en vakantieperiodes.</p>

    <div class="row g-4">
        <!-- Standaard openingstijden -->
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body text-center">
                    <h5><i class="bi bi-calendar-week"></i> Standaardtijden</h5>
                    <p class="text-muted">Bewerk vaste openingstijden per weekdag</p>
                    <a href="standard.php" class="btn btn-outline-primary w-100">Open standaardtijden</a>
                </div>
            </div>
        </div>

        <!-- Speciale openingstijden -->
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body text-center">
                    <h5><i class="bi bi-stars"></i> Speciale dagen</h5>
                    <p class="text-muted">Instellen van uitzonderlijke openingen of sluitingen (feestdagen)</p>
                    <a href="specials.php" class="btn btn-outline-warning w-100">Open speciale dagen</a>
                </div>
            </div>
        </div>

        <!-- Vakantieperiodes -->
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body text-center">
                    <h5><i class="bi bi-calendar-x"></i> Vakantieperiodes</h5>
                    <p class="text-muted">Periodes waarin de winkel volledig gesloten is</p>
                    <a href="vacation/index.php" class="btn btn-outline-danger w-100">Open vakantiebeheer</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/footer.php'; ?>
