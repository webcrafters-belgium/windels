<?php
include $_SERVER['DOCUMENT_ROOT'] . '/header.php';
include $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';
?>

<div class="admin-promo-list">
    <div class="header">
        <h1>Overzicht van Promo’s</h1>
        <a href="add.php" class="btn">➕ Nieuwe Promo</a>
    </div>

    <table class="promo-table">
        <thead>
        <tr>
            <th>Type</th>
            <th>Target</th>
            <th>Titel</th>
            <th>Korting</th>
            <th>Geldig van</th>
            <th>Geldig tot</th>
            <th>Aangemaakt door</th>
            <th>Acties</th>
        </tr>
        </thead>
        <tbody>
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
        while ($row = $result->fetch_assoc()) {
            $type = $row['promo_type'];
            $target = '-';
            if ($type === 'product') $target = $row['product_sku'];
            elseif ($type === 'category') $target = $row['category_name'] ?? 'Categorie verwijderd';
            elseif ($type === 'subcategory') $target = $row['subcategory_name'] ?? 'Subcategorie verwijderd';

            echo '<tr>';
            echo '<td>' . ucfirst($type) . '</td>';
            echo '<td>' . htmlspecialchars($target) . '</td>';
            echo '<td>' . htmlspecialchars($row['title']) . '</td>';
            echo '<td>' . $row['discount_percentage'] . '%</td>';
            echo '<td>' . ($row['start_date'] ? date('d/m/Y', strtotime($row['start_date'])) : '-') . '</td>';
            echo '<td>' . ($row['end_date'] ? date('d/m/Y', strtotime($row['end_date'])) : '-') . '</td>';
            echo '<td>' . ($row['creator'] ?? '-') . '</td>';
            echo '<td>
                <a href="edit.php?id='.$row['id'].'" class="btn-sm">✏️</a>
                <a href="delete.php?id='.$row['id'].'" class="btn-sm red" onclick="return confirm(\'Weet je zeker dat je deze promo wil verwijderen?\')">🗑️</a>
              </td>';
            echo '</tr>';
        }
        ?>
        </tbody>
    </table>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/footer.php'; ?>
