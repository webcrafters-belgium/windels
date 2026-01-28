<?php
function getCategories($conn): mysqli_result|false {
    $sql = "SELECT id, name, slug FROM categories WHERE LOWER(name) NOT LIKE '%vers%'";
    return $conn->query($sql);
}

function getSubcategories($conn): array {
    $result = $conn->query("SELECT id, name, slug, category_id FROM subcategories");
    $subs = [];
    while ($row = $result->fetch_assoc()) {
        $subs[$row['category_id']][] = $row;
    }
    return $subs;
}
