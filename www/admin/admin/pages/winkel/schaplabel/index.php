<?php
require_once __DIR__ . '/../../../includes/header.php';

$admin_role = $_SESSION['role'] ?? 'admin';
$current_date = date('Y-m-d');
$category = 'schaplabel';

// Fetch all buttons for the user's role
$stmt = $conn->prepare("
    SELECT b.* 
    FROM buttons b 
    JOIN button_roles br ON b.id = br.button_id 
    WHERE (br.role = ? OR br.role = 'All') 
        AND b.visible = 1 
        AND (b.display_date IS NULL OR b.display_date <= ?) 
        AND b.category = ? 
    ORDER BY b.position ASC
");

$stmt->execute([$admin_role, $current_date, $category]);
$result = $stmt->get_result();
$buttons = $result->fetch_all(MYSQLI_ASSOC);
?>

<!-- Main Content -->
<div class="max-w-6xl mx-auto animate-fadeInUp">
    
    <!-- Page Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold glow-text">Schaplabels</h1>
            <p class="text-sm mt-1" style="color: var(--text-muted);">Print schaplabels voor je producten</p>
        </div>
        <a href="/admin/pages/winkel/" 
           class="glass px-4 py-2 rounded-xl flex items-center space-x-2 hover:bg-white/10 transition">
            <i class="bi bi-arrow-left"></i>
            <span>Terug naar Winkel</span>
        </a>
    </div>

    <!-- Options Grid -->
    <?php if (empty($buttons)): ?>
        <div class="card-glass p-8 text-center">
            <i class="bi bi-exclamation-circle text-4xl text-amber-400 mb-4"></i>
            <h3 class="text-xl font-semibold mb-2">Geen opties beschikbaar</h3>
            <p style="color: var(--text-muted);">Neem contact op met de beheerder voor verdere assistentie.</p>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6 stagger-children">
            <?php foreach ($buttons as $button): ?>
                <a href="<?= htmlspecialchars($button['url']) ?>" class="card-glass p-6 group" data-testid="btn-<?= htmlspecialchars($button['name']) ?>">
                    <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-amber-500/30 to-orange-500/30 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                        <i class="<?= htmlspecialchars($button['icon']) ?> text-2xl text-amber-400"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2"><?= htmlspecialchars($button['name']) ?></h3>
                    <p class="text-sm flex items-center" style="color: var(--text-muted);">
                        <span>Ga naar <?= htmlspecialchars($button['name']) ?></span>
                        <i class="bi bi-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                    </p>
                </a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <!-- Quick Actions -->
    <div class="mt-8 card-glass p-6">
        <h2 class="text-lg font-semibold mb-4">Snelle acties</h2>
        <div class="flex flex-wrap gap-3">
            <a href="/admin/pages/winkel/schaplabel/schaplabel.php" class="glass px-4 py-2 rounded-xl hover:bg-white/10 transition flex items-center space-x-2">
                <i class="bi bi-tag text-teal-400"></i>
                <span>Alle labels</span>
            </a>
            <a href="/admin/pages/winkel/schaplabel/schaplabel_new.php" class="glass px-4 py-2 rounded-xl hover:bg-white/10 transition flex items-center space-x-2">
                <i class="bi bi-plus-circle text-emerald-400"></i>
                <span>Nieuwe producten</span>
            </a>
            <a href="/admin/pages/winkel/schaplabel/schaplabel_gewijzigd.php" class="glass px-4 py-2 rounded-xl hover:bg-white/10 transition flex items-center space-x-2">
                <i class="bi bi-pencil text-blue-400"></i>
                <span>Gewijzigde prijzen</span>
            </a>
            <a href="/admin/pages/winkel/schaplabel/schaplabel_korting.php" class="glass px-4 py-2 rounded-xl hover:bg-white/10 transition flex items-center space-x-2">
                <i class="bi bi-percent text-rose-400"></i>
                <span>Korting labels</span>
            </a>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../../../includes/footer.php'; ?>
