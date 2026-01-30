<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/admin/includes/header.php';

// Haal alle schappen op
function fetchSchappen($pdo_winkel) {
    $stmt = $pdo_winkel->query("SELECT * FROM winkel_schappen");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$schappen = fetchSchappen($pdo_winkel);
?>

<div class="container mt-5">
    <h2>Schappen Overzicht</h2>

    <!-- Knoppen voor toevoegen van nieuw schap en nieuwe producten -->
    <div class="mb-4">
        <a href="add_schap.php" class="btn btn-success">Nieuw Schap Toevoegen</a>
        <a href="add_productschap.php" class="btn btn-info">Nieuw Product in Schap Toevoegen</a>
    </div>

    <!-- Controleer of er schappen zijn -->
    <?php if (empty($schappen)): ?>
        <div class="alert alert-info" role="alert">
            Er zijn momenteel geen schappen beschikbaar. Voeg een nieuw schap toe om te beginnen!
        </div>
    <?php else: ?>
        <div class="row">
            <?php foreach ($schappen as $schap): ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title"><?= htmlspecialchars($schap['naam']); ?></h4>
                            <p>Locatie: <?= htmlspecialchars($schap['locatie']); ?></p>
                            <p>Breedte: <?= htmlspecialchars($schap['breedte']); ?> cm, Hoogte: <?= htmlspecialchars($schap['hoogte']); ?> cm</p>
                            <!-- Knoppen om schap te bekijken/bewerken en te verwijderen -->
                            <?php if ($_SESSION['admin_role'] === 'Admin'): ?>
                                <a href="view_schap.php?id=<?= htmlspecialchars($schap['id']); ?>" class="btn btn-primary">Bekijk en Bewerken</a>
                                <button class="btn btn-danger delete-schap" data-id="<?= htmlspecialchars($schap['id']); ?>">Verwijder</button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // AJAX-verzoek om een schap te verwijderen
    $(".delete-schap").click(function() {
        var schapId = $(this).data("id");
        if (confirm("Weet je zeker dat je dit schap wilt verwijderen?")) {
            $.ajax({
                url: 'delete_schap.php', // Verwijder-script
                type: 'POST',
                data: { id: schapId },
                success: function(response) {
                    if (response === 'success') {
                        alert('Schap succesvol verwijderd!');
                        location.reload(); // Pagina herladen om de verwijdering weer te geven
                    } else {
                        alert('Fout bij het verwijderen van het schap.');
                    }
                },
                error: function() {
                    alert('Er is een fout opgetreden bij het verwijderen van het schap.');
                }
            });
        }
    });
});
</script>

<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/admin/includes/footer.php'; ?>
