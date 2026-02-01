<?php
declare(strict_types=1);

/**
 * Check whether the products table already has a parent_id column.
 */
function hasProductParentColumn(mysqli $conn): bool
{
    static $hasColumn = null;
    if ($hasColumn !== null) {
        return $hasColumn;
    }

    $stmt = $conn->prepare("SHOW COLUMNS FROM products LIKE 'parent_id'");
    if (!$stmt) {
        error_log('hasProductParentColumn: failed to prepare statement: ' . $conn->error);
        return $hasColumn = false;
    }

    $stmt->execute();
    $stmt->store_result();
    $hasColumn = $stmt->num_rows > 0;
    $stmt->close();
    return $hasColumn;
}

/**
 * Ensure the products table can store variant relationships by adding parent_id if missing.
 */
function ensureProductParentColumn(mysqli $conn): bool
{
    static $ensured = null;
    if ($ensured !== null) {
        return $ensured;
    }

    if (hasProductParentColumn($conn)) {
        return $ensured = true;
    }

    $alterSql = "ALTER TABLE products ADD COLUMN parent_id INT NULL AFTER stock_status";
    if ($conn->query($alterSql)) {
        return $ensured = true;
    }

    error_log('ensureProductParentColumn: failed to add parent_id column: ' . $conn->error);
    return $ensured = false;
}
