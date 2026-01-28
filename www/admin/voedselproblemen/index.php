<?php
require $_SERVER["DOCUMENT_ROOT"] . '/ini.inc';

require $_SERVER["DOCUMENT_ROOT"] . '/header.php';
?>

<div class="container">
    <div class="jumbotron mt-4 text-center">
    <h1 class="custom-display-4">Welkom bij de Voedselproblemen Applicatie</h1>
        <p class="lead">
            Deze applicatie is ontworpen om meldingen van voedselproblemen te registreren en te beheren. Het is een cruciaal hulpmiddel voor het Federaal Agentschap voor de Veiligheid van de Voedselketen (FAVV) om de veiligheid van onze voedselketen te waarborgen.
        </p>
        <hr class="my-4">
        <p>
            Door meldingen van voedselproblemen zorgvuldig bij te houden en te analyseren, kan het FAVV snel en efficiënt reageren op mogelijke gevaren voor de volksgezondheid. Dit systeem helpt bij het identificeren van problemen zoals bederf, contaminatie en allergische reacties, en zorgt ervoor dat passende maatregelen worden genomen om deze risico's te minimaliseren.
        </p>
        <p>
            Het gebruik van deze applicatie draagt bij aan een veiligere voedselomgeving voor consumenten door ervoor te zorgen dat voedselproducten die op de markt worden gebracht voldoen aan de hoogste veiligheidsnormen. Uw samenwerking en nauwkeurige rapportage zijn van vitaal belang om dit doel te bereiken.
        </p>
        <div class="row mt-4">
            <div class="col-md-4 mb-3">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <i class="fa fa-home fa-3x mb-3 icon-gray" aria-hidden="true"></i>
                        <h5 class="card-title">Home</h5>
                        <p class="card-text">Ga terug naar de homepagina.</p>
                        <a href="/winkel/index.php" class="btn btn-secondary">Ga naar</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <i class="fa fa-eye fa-3x mb-3 icon-blue" aria-hidden="true"></i>
                        <h5 class="card-title">Bekijk Meldingen</h5>
                        <p class="card-text">Bekijk alle meldingen van voedselproblemen.</p>
                        <a href="/voedselproblemen/meldingen/view.php" class="btn btn-primary">Ga naar</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <i class="fa fa-plus fa-3x mb-3 icon-green" aria-hidden="true"></i>
                        <h5 class="card-title">Nieuwe Melding Aanmaken</h5>
                        <p class="card-text">Voeg een nieuwe melding van een voedselprobleem toe.</p>
                        <a href="/voedselproblemen/meldingen/create.php" class="btn btn-success">Ga naar</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <i class="fa fa-clipboard-list fa-3x mb-3 icon-yellow" aria-hidden="true"></i>
                        <h5 class="card-title">Bekijk Terugroepacties</h5>
                        <p class="card-text">Bekijk alle geregistreerde terugroepacties.</p>
                        <a href="/voedselproblemen/meldingen/view_recall.php" class="btn btn-warning">Ga naar</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <i class="fa fa-plus-circle fa-3x mb-3 icon-red" aria-hidden="true"></i>
                        <h5 class="card-title">Nieuwe Terugroepactie Aanmaken</h5>
                        <p class="card-text">Voeg een nieuwe terugroepactie toe.</p>
                        <a href="/voedselproblemen/meldingen/add_recall.php" class="btn btn-danger">Ga naar</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <i class="fa fa-chart-bar fa-3x mb-3 icon-lightblue" aria-hidden="true"></i>
                        <h5 class="card-title">Rapporten</h5>
                        <p class="card-text">Genereer rapporten over meldingen en terugroepacties.</p>
                        <a href="/voedselproblemen/meldingen/reports.php" class="btn btn-info">Ga naar</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <i class="fa fa-plus fa-3x mb-3 icon-yellow" aria-hidden="true"></i>
                        <h5 class="card-title">Nieuwe productie Aanmaken</h5>
                        <p class="card-text">Voeg een nieuwe vers gemaakt product toe.</p>
                        <a href="/voedselproblemen/meldingen/add_productie.php" class="btn btn-warning">Ga naar</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-apple-alt fa-3x mb-3 icon-green" aria-hidden="true"></i>
                        <h5 class="card-title">productie bekijken</h5>
                        <p class="card-text">Bekijk alle productie van verse producten die gemaakt zijn.</p>
                        <a href="/voedselproblemen/meldingen/view_productie.php" class="btn btn-success">Ga naar</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<h4>Terug naar dashboard </h4>
<button href="/admin" onclick="window.location.href='/admin'">KLik hier</button>


<?php
require $_SERVER["DOCUMENT_ROOT"] . '/footer.php';
?>
