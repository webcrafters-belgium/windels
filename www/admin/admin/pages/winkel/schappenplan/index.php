<?php
require_once __DIR__ . '/../../../includes/header.php';

// Haal alle schappen op
function fetchSchappen($pdo_winkel) {
    $stmt = $pdo_winkel->query("SELECT * FROM winkel_schappen");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$schappen = isset($pdo_winkel) ? fetchSchappen($pdo_winkel) : [];
?>

<!-- Main Content -->
<div class="max-w-6xl mx-auto animate-fadeInUp">
    
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-bold glow-text">Schappenplan</h1>
            <p class="text-sm mt-1" style="color: var(--text-muted);">Beheer de indeling van je winkelschappen</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="/admin/pages/winkel/" 
               class="glass px-4 py-2 rounded-xl flex items-center space-x-2 hover:bg-white/10 transition">
                <i class="bi bi-arrow-left"></i>
                <span>Terug</span>
            </a>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="flex flex-wrap gap-4 mb-8">
        <a href="add_schap.php" 
           class="accent-bg px-5 py-3 rounded-xl font-semibold text-white flex items-center space-x-2"
           data-testid="add-schap-btn">
            <i class="bi bi-plus-circle"></i>
            <span>Nieuw Schap</span>
        </a>
        <a href="add_productschap.php" 
           class="glass px-5 py-3 rounded-xl font-semibold flex items-center space-x-2 hover:bg-white/10 transition"
           data-testid="add-product-schap-btn">
            <i class="bi bi-box-seam"></i>
            <span>Product in Schap</span>
        </a>
    </div>

    <!-- Schappen Grid -->
    <?php if (empty($schappen)): ?>
        <div class="card-glass p-8 text-center">
            <i class="bi bi-layout-wtf text-5xl mb-4 block opacity-50" style="color: var(--text-muted);"></i>
            <h3 class="text-xl font-semibold mb-2">Geen schappen gevonden</h3>
            <p style="color: var(--text-muted);" class="mb-4">Voeg een nieuw schap toe om te beginnen!</p>
            <a href="add_schap.php" class="accent-bg px-5 py-2 rounded-xl font-semibold text-white inline-flex items-center space-x-2">
                <i class="bi bi-plus-circle"></i>
                <span>Nieuw Schap</span>
            </a>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6 stagger-children">
            <?php foreach ($schappen as $schap): ?>
                <div class="card-glass p-6 group" data-testid="schap-<?= $schap['id'] ?>">
                    <div class="flex items-start justify-between mb-4">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500/30 to-indigo-500/30 flex items-center justify-center">
                            <i class="bi bi-layout-wtf text-xl text-blue-400"></i>
                        </div>
                        <?php if (isset($_SESSION['admin_role']) && $_SESSION['admin_role'] === 'Admin'): ?>
                            <button class="delete-schap p-2 rounded-lg bg-rose-500/20 text-rose-400 hover:bg-rose-500/30 transition opacity-0 group-hover:opacity-100" 
                                    data-id="<?= htmlspecialchars($schap['id']) ?>"
                                    title="Verwijderen">
                                <i class="bi bi-trash"></i>
                            </button>
                        <?php endif; ?>
                    </div>
                    
                    <h3 class="text-xl font-semibold mb-2"><?= htmlspecialchars($schap['naam']) ?></h3>
                    
                    <div class="space-y-2 text-sm" style="color: var(--text-muted);">
                        <p class="flex items-center">
                            <i class="bi bi-geo-alt mr-2 text-teal-400"></i>
                            <?= htmlspecialchars($schap['locatie']) ?>
                        </p>
                        <p class="flex items-center">
                            <i class="bi bi-arrows-angle-expand mr-2 text-teal-400"></i>
                            <?= htmlspecialchars($schap['breedte']) ?> × <?= htmlspecialchars($schap['hoogte']) ?> cm
                        </p>
                    </div>

                    <?php if (isset($_SESSION['admin_role']) && $_SESSION['admin_role'] === 'Admin'): ?>
                        <a href="view_schap.php?id=<?= htmlspecialchars($schap['id']) ?>" 
                           class="mt-4 w-full glass px-4 py-2 rounded-xl flex items-center justify-center space-x-2 hover:bg-white/10 transition">
                            <i class="bi bi-eye"></i>
                            <span>Bekijken & Bewerken</span>
                        </a>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<script>
document.querySelectorAll('.delete-schap').forEach(btn => {
    btn.addEventListener('click', async function() {
        const schapId = this.dataset.id;
        if (confirm('Weet je zeker dat je dit schap wilt verwijderen?')) {
            try {
                const response = await fetch('delete_schap.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: 'id=' + schapId
                });
                const result = await response.text();
                if (result === 'success') {
                    location.reload();
                } else {
                    alert('Fout bij het verwijderen van het schap.');
                }
            } catch (error) {
                alert('Er is een fout opgetreden bij het verwijderen.');
            }
        }
    });
});
</script>

<?php require_once __DIR__ . '/../../../includes/footer.php'; ?>
