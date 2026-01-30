<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/admin/includes/header.php';
?>

<!-- PAGE HEADER -->
<div class="mb-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-4xl font-bold mb-2 glow-text" style="color: var(--text-primary);">
                <i class="bi bi-percent accent-primary mr-3"></i>Promo Overzicht
            </h1>
            <p class="text-lg" style="color: var(--text-muted);">Beheer alle promoties en kortingen</p>
        </div>
        <a href="add.php" class="accent-bg text-white px-6 py-3 rounded-xl font-semibold hover:opacity-90 transition flex items-center gap-2">
            <i class="bi bi-plus-circle"></i>Nieuwe Promo
        </a>
    </div>
</div>

<!-- PROMO TABLE -->
<div class="card-glass p-8">
    <div class="flex justify-between items-center mb-6">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-rose-500/30 to-pink-500/30 flex items-center justify-center">
                <i class="bi bi-tags-fill text-xl text-rose-400"></i>
            </div>
            <h2 class="text-2xl font-bold" style="color: var(--text-primary);">Alle Promo's</h2>
        </div>
    </div>

    <div class="overflow-x-auto rounded-xl">
        <table class="w-full">
            <thead>
                <tr class="border-b" style="border-color: var(--border-glass); background: var(--bg-glass);">
                    <th class="text-left py-4 px-4 font-semibold text-sm uppercase tracking-wider" style="color: var(--text-muted);">Type</th>
                    <th class="text-left py-4 px-4 font-semibold text-sm uppercase tracking-wider" style="color: var(--text-muted);">Target</th>
                    <th class="text-left py-4 px-4 font-semibold text-sm uppercase tracking-wider" style="color: var(--text-muted);">Titel</th>
                    <th class="text-left py-4 px-4 font-semibold text-sm uppercase tracking-wider" style="color: var(--text-muted);">Korting</th>
                    <th class="text-left py-4 px-4 font-semibold text-sm uppercase tracking-wider" style="color: var(--text-muted);">Geldig van</th>
                    <th class="text-left py-4 px-4 font-semibold text-sm uppercase tracking-wider" style="color: var(--text-muted);">Geldig tot</th>
                    <th class="text-left py-4 px-4 font-semibold text-sm uppercase tracking-wider" style="color: var(--text-muted);">Door</th>
                    <th class="text-right py-4 px-4 font-semibold text-sm uppercase tracking-wider" style="color: var(--text-muted);">Acties</th>
                </tr>
            </thead>
            <tbody class="divide-y" style="--tw-divide-opacity: 0.1;">
            <?php
            $query = "
            SELECT p.*, 
              c.name AS category_name, 
              sc.name AS subcategory_name,
              u.username AS creator
            FROM promos p
            LEFT JOIN categories c ON p.category_id = c.id
            LEFT JOIN subcategories sc ON p.subcategory_id = sc.id
            LEFT JOIN users u ON p.created_by = u.id
            ORDER BY p.created_at DESC
          ";

            $result = $conn->query($query);
            if ($result && $result->num_rows > 0):
                while ($row = $result->fetch_assoc()):
                    $type = $row['promo_type'];
                    $target = '-';
                    if ($type === 'product') $target = $row['product_sku'];
                    elseif ($type === 'category') $target = $row['category_name'] ?? 'Categorie verwijderd';
                    elseif ($type === 'subcategory') $target = $row['subcategory_name'] ?? 'Subcategorie verwijderd';
                    
                    $typeColors = [
                        'product' => 'bg-blue-500/20 text-blue-400 border-blue-500/30',
                        'category' => 'bg-emerald-500/20 text-emerald-400 border-emerald-500/30',
                        'subcategory' => 'bg-violet-500/20 text-violet-400 border-violet-500/30',
                    ];
                    $typeColor = $typeColors[$type] ?? 'bg-slate-500/20 text-slate-400 border-slate-500/30';
            ?>
                <tr class="group hover:bg-white/5 transition-colors">
                    <td class="py-4 px-4">
                        <span class="px-3 py-1.5 rounded-lg text-xs font-semibold border <?= $typeColor ?>"><?= ucfirst($type) ?></span>
                    </td>
                    <td class="py-4 px-4 font-medium"><?= htmlspecialchars($target) ?></td>
                    <td class="py-4 px-4"><?= htmlspecialchars($row['title']) ?></td>
                    <td class="py-4 px-4">
                        <span class="font-bold text-rose-400"><?= $row['discount_percentage'] ?>%</span>
                    </td>
                    <td class="py-4 px-4 text-sm" style="color: var(--text-muted);"><?= $row['start_date'] ? date('d/m/Y', strtotime($row['start_date'])) : '-' ?></td>
                    <td class="py-4 px-4 text-sm" style="color: var(--text-muted);"><?= $row['end_date'] ? date('d/m/Y', strtotime($row['end_date'])) : '-' ?></td>
                    <td class="py-4 px-4 text-sm" style="color: var(--text-muted);"><?= $row['creator'] ?? '-' ?></td>
                    <td class="py-4 px-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="edit.php?id=<?= $row['id'] ?>" class="p-2 rounded-lg glass-hover text-amber-400 hover:bg-amber-500/20" title="Bewerken">
                                <i class="bi bi-pencil-fill"></i>
                            </a>
                            <a href="delete.php?id=<?= $row['id'] ?>" onclick="return confirm('Weet je zeker dat je deze promo wil verwijderen?')" class="p-2 rounded-lg glass-hover text-rose-400 hover:bg-rose-500/20" title="Verwijderen">
                                <i class="bi bi-trash-fill"></i>
                            </a>
                        </div>
                    </td>
                </tr>
            <?php endwhile; else: ?>
                <tr>
                    <td colspan="8" class="py-16 text-center">
                        <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-slate-500/20 to-slate-600/20 flex items-center justify-center mx-auto mb-4">
                            <i class="bi bi-tags text-4xl" style="color: var(--text-muted);"></i>
                        </div>
                        <p class="text-lg font-semibold mb-1" style="color: var(--text-secondary);">Geen promo's gevonden</p>
                        <p class="text-sm" style="color: var(--text-muted);">Maak je eerste promotie aan</p>
                    </td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/admin/includes/footer.php'; ?>
