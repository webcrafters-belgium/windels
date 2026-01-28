<?php
require_once '../../includes/header.php';

// Filters
$category_filter = isset($_GET['category']) ? (int)$_GET['category'] : 0;
$type_filter = isset($_GET['type']) ? $_GET['type'] : '';
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$stock_filter = isset($_GET['stock']) ? $_GET['stock'] : '';

// Pagination
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$limit = ITEMS_PER_PAGE;
$offset = ($page - 1) * $limit;

// Build query
$where = [];
$params = [];
$types = '';

if ($category_filter > 0) {
    $where[] = "pc.category_id = ?";
    $params[] = $category_filter;
    $types .= 'i';
}

if ($type_filter && in_array($type_filter, ['candle', 'terrazzo', 'epoxy', 'other'])) {
    $where[] = "p.product_type = ?";
    $params[] = $type_filter;
    $types .= 's';
}

if ($search) {
    $where[] = "(p.name LIKE ? OR p.sku LIKE ?)";
    $searchParam = "%$search%";
    $params[] = $searchParam;
    $params[] = $searchParam;
    $types .= 'ss';
}

if ($stock_filter === 'low') {
    $where[] = "p.stock_quantity < 5";
}

$whereClause = $where ? 'WHERE ' . implode(' AND ', $where) : '';

// Count total
$countQuery = "SELECT COUNT(DISTINCT p.id) as total 
               FROM products p
               LEFT JOIN product_categories pc ON p.id = pc.product_id
               $whereClause";

$stmt = $conn->prepare($countQuery);
if ($params) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$total = $stmt->fetch_result()->fetch_assoc()['total'];
$totalPages = max(1, ceil($total / $limit));
$stmt->close();

// Fetch products
$query = "SELECT p.*, c.name as category_name,
          (SELECT image_path FROM product_images WHERE product_id = p.id AND is_main = 1 LIMIT 1) as main_image
          FROM products p
          LEFT JOIN product_categories pc ON p.id = pc.product_id
          LEFT JOIN categories c ON pc.category_id = c.id
          $whereClause
          GROUP BY p.id
          ORDER BY p.updated_at DESC
          LIMIT ? OFFSET ?";

$stmt = $conn->prepare($query);
$bindParams = $params;
$bindParams[] = $limit;
$bindParams[] = $offset;
$bindTypes = $types . 'ii';
$stmt->bind_param($bindTypes, ...$bindParams);
$stmt->execute();
$products = $stmt->get_result();
$stmt->close();

// Get categories for filter
$categories = $conn->query("SELECT id, name FROM categories ORDER BY name");
?>

<!-- Page Header -->
<div class="mb-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-4xl font-bold mb-2" style="color: var(--text-primary);">
                <i class="bi bi-box-seam accent-primary mr-3"></i>Producten Beheer
            </h1>
            <p class="text-lg" style="color: var(--text-muted);">Beheer al je producten, voorraad en materialen</p>
        </div>
        <a href="/admin_new/pages/products/add.php" class="accent-bg text-white px-8 py-4 rounded-lg font-bold text-lg hover:opacity-90 transition flex items-center">
            <i class="bi bi-plus-circle mr-2 text-2xl"></i>
            Nieuw Product
        </a>
    </div>
</div>

<!-- Filters -->
<div class="card-glass p-6 mb-8">
    <form method="get" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Search -->
        <div>
            <label class="block text-sm font-semibold mb-2" style="color: var(--text-secondary);">Zoeken</label>
            <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" 
                   placeholder="Naam of SKU..."
                   class="w-full px-4 py-2 rounded-lg glass border" style="border-color: var(--border-glass);">
        </div>
        
        <!-- Category -->
        <div>
            <label class="block text-sm font-semibold mb-2" style="color: var(--text-secondary);">Categorie</label>
            <select name="category" class="w-full px-4 py-2 rounded-lg glass border" style="border-color: var(--border-glass);">
                <option value="">Alle categorieën</option>
                <?php while ($cat = $categories->fetch_assoc()): ?>
                    <option value="<?= $cat['id'] ?>" <?= $category_filter == $cat['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat['name']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        
        <!-- Product Type -->
        <div>
            <label class="block text-sm font-semibold mb-2" style="color: var(--text-secondary);">Product Type</label>
            <select name="type" class="w-full px-4 py-2 rounded-lg glass border" style="border-color: var(--border-glass);">
                <option value="">Alle types</option>
                <option value="candle" <?= $type_filter === 'candle' ? 'selected' : '' ?>>Kaarsen</option>
                <option value="terrazzo" <?= $type_filter === 'terrazzo' ? 'selected' : '' ?>>Terrazzo</option>
                <option value="epoxy" <?= $type_filter === 'epoxy' ? 'selected' : '' ?>>Epoxy</option>
                <option value="other" <?= $type_filter === 'other' ? 'selected' : '' ?>>Overig</option>
            </select>
        </div>
        
        <!-- Stock Filter -->
        <div>
            <label class="block text-sm font-semibold mb-2" style="color: var(--text-secondary);">Voorraad</label>
            <select name="stock" class="w-full px-4 py-2 rounded-lg glass border" style="border-color: var(--border-glass);">
                <option value="">Alle voorraad</option>
                <option value="low" <?= $stock_filter === 'low' ? 'selected' : '' ?>>Laag (< 5)</option>
            </select>
        </div>
        
        <!-- Submit -->
        <div class="md:col-span-2 lg:col-span-4 flex gap-4">
            <button type="submit" class="accent-bg text-white px-6 py-2 rounded-lg font-semibold hover:opacity-90 transition">
                <i class="bi bi-search mr-2"></i>Filteren
            </button>
            <a href="/admin_new/pages/products/index.php" class="glass px-6 py-2 rounded-lg font-semibold hover:opacity-90 transition">
                <i class="bi bi-x-circle mr-2"></i>Reset
            </a>
        </div>
    </form>
</div>

<!-- Products Grid -->
<div class="card-glass p-8">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold" style="color: var(--text-primary);">Producten (<?= number_format($total) ?>)</h2>
        <span class="text-sm" style="color: var(--text-muted);">Pagina <?= $page ?> van <?= $totalPages ?></span>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b" style="border-color: var(--border-glass);">
                    <th class="text-left py-3 px-4 font-semibold" style="color: var(--text-secondary);">Afbeelding</th>
                    <th class="text-left py-3 px-4 font-semibold" style="color: var(--text-secondary);">SKU</th>
                    <th class="text-left py-3 px-4 font-semibold" style="color: var(--text-secondary);">Naam</th>
                    <th class="text-left py-3 px-4 font-semibold" style="color: var(--text-secondary);">Type</th>
                    <th class="text-left py-3 px-4 font-semibold" style="color: var(--text-secondary);">Categorie</th>
                    <th class="text-left py-3 px-4 font-semibold" style="color: var(--text-secondary);">Voorraad</th>
                    <th class="text-left py-3 px-4 font-semibold" style="color: var(--text-secondary);">Prijs</th>
                    <th class="text-right py-3 px-4 font-semibold" style="color: var(--text-secondary);">Acties</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($products && $products->num_rows > 0): ?>
                    <?php while ($p = $products->fetch_assoc()): ?>
                        <tr class="border-b hover:bg-opacity-50 transition" style="border-color: var(--border-glass);">
                            <td class="py-4 px-4">
                                <?php 
                                $img = $p['main_image'] ?? '/images/products/placeholder.png';
                                if (!file_exists($_SERVER['DOCUMENT_ROOT'] . $img)) {
                                    $img = '/images/products/placeholder.png';
                                }
                                ?>
                                <img src="<?= $img ?>" class="w-16 h-16 object-cover rounded-lg" alt="<?= htmlspecialchars($p['name']) ?>">
                            </td>
                            <td class="py-4 px-4 font-mono text-sm"><?= htmlspecialchars($p['sku']) ?></td>
                            <td class="py-4 px-4 font-semibold"><?= htmlspecialchars($p['name']) ?></td>
                            <td class="py-4 px-4">
                                <?php
                                $typeLabels = [
                                    'candle' => ['Kaars', 'text-yellow-600', 'bg-yellow-500/20', 'border-yellow-500'],
                                    'terrazzo' => ['Terrazzo', 'text-purple-600', 'bg-purple-500/20', 'border-purple-500'],
                                    'epoxy' => ['Epoxy', 'text-blue-600', 'bg-blue-500/20', 'border-blue-500'],
                                    'other' => ['Overig', 'text-gray-600', 'bg-gray-500/20', 'border-gray-500'],
                                ];
                                $typeInfo = $typeLabels[$p['product_type']] ?? $typeLabels['other'];
                                ?>
                                <span class="px-3 py-1 rounded-full text-xs font-semibold border <?= $typeInfo[1] ?> <?= $typeInfo[2] ?> <?= $typeInfo[3] ?>">
                                    <?= $typeInfo[0] ?>
                                </span>
                            </td>
                            <td class="py-4 px-4 text-sm"><?= htmlspecialchars($p['category_name'] ?? '-') ?></td>
                            <td class="py-4 px-4">
                                <?php if ($p['stock_quantity'] < 5): ?>
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold border text-red-600 bg-red-500/20 border-red-500">
                                        <?= $p['stock_quantity'] ?> stuks
                                    </span>
                                <?php else: ?>
                                    <span class="text-sm"><?= $p['stock_quantity'] ?> stuks</span>
                                <?php endif; ?>
                            </td>
                            <td class="py-4 px-4 font-semibold">€<?= number_format($p['price'], 2, ',', '.') ?></td>
                            <td class="py-4 px-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="/admin_new/pages/products/edit.php?id=<?= $p['id'] ?>" 
                                       class="px-3 py-1 rounded-lg glass-hover text-blue-600 font-semibold text-sm" title="Bewerken">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button onclick="deleteProduct(<?= $p['id'] ?>)" 
                                            class="px-3 py-1 rounded-lg glass-hover text-red-600 font-semibold text-sm" title="Verwijderen">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="py-12 text-center" style="color: var(--text-muted);">
                            <i class="bi bi-inbox text-6xl mb-4 block accent-primary"></i>
                            <p class="text-xl font-semibold mb-2">Geen producten gevonden</p>
                            <p>Probeer je filters aan te passen of voeg een nieuw product toe</p>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    <?php if ($totalPages > 1): ?>
        <div class="mt-8 flex items-center justify-between">
            <div class="flex gap-2">
                <?php if ($page > 1): ?>
                    <a href="?page=1<?= $category_filter ? '&category='.$category_filter : '' ?><?= $type_filter ? '&type='.$type_filter : '' ?><?= $search ? '&search='.urlencode($search) : '' ?>" 
                       class="px-4 py-2 rounded-lg glass-hover font-semibold">« Eerste</a>
                    <a href="?page=<?= $page - 1 ?><?= $category_filter ? '&category='.$category_filter : '' ?><?= $type_filter ? '&type='.$type_filter : '' ?><?= $search ? '&search='.urlencode($search) : '' ?>" 
                       class="px-4 py-2 rounded-lg glass-hover font-semibold">‹ Vorige</a>
                <?php endif; ?>
                
                <span class="px-4 py-2 rounded-lg accent-bg text-white font-bold">Pagina <?= $page ?></span>
                
                <?php if ($page < $totalPages): ?>
                    <a href="?page=<?= $page + 1 ?><?= $category_filter ? '&category='.$category_filter : '' ?><?= $type_filter ? '&type='.$type_filter : '' ?><?= $search ? '&search='.urlencode($search) : '' ?>" 
                       class="px-4 py-2 rounded-lg glass-hover font-semibold">Volgende ›</a>
                    <a href="?page=<?= $totalPages ?><?= $category_filter ? '&category='.$category_filter : '' ?><?= $type_filter ? '&type='.$type_filter : '' ?><?= $search ? '&search='.urlencode($search) : '' ?>" 
                       class="px-4 py-2 rounded-lg glass-hover font-semibold">Laatste »</a>
                <?php endif; ?>
            </div>
            
            <span style="color: var(--text-muted);">
                Toon <?= $offset + 1 ?> - <?= min($offset + $limit, $total) ?> van <?= $total ?>
            </span>
        </div>
    <?php endif; ?>
</div>

<script>
async function deleteProduct(id) {
    if (!confirm('Weet je zeker dat je dit product wilt verwijderen? Dit kan niet ongedaan worden gemaakt.')) {
        return;
    }
    
    try {
        const response = await fetchWithCSRF('/admin_new/functions/products/delete.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({id: id})
        });
        
        const result = await response.json();
        if (result.success) {
            alert('Product succesvol verwijderd');
            location.reload();
        } else {
            alert('Fout: ' + result.message);
        }
    } catch (error) {
        alert('Er is een fout opgetreden');
    }
}
</script>

<?php require_once '../../includes/footer.php'; ?>