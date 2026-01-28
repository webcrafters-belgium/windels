-- Migration for Windels Green Admin Rebuild
-- Date: 2026-01-25
-- Purpose: Add material tracking for products (Candles, Terrazzo, Epoxy)

-- Create product_materials table for tracking material usage
CREATE TABLE IF NOT EXISTS product_materials (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    material_type ENUM('stearine', 'paraffine', 'epoxy', 'terrazzo_powder') NOT NULL,
    grams DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    INDEX idx_product_id (product_id),
    INDEX idx_material_type (material_type)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create admin_activity_log for tracking admin actions
CREATE TABLE IF NOT EXISTS admin_activity_log (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    action_type ENUM('create', 'update', 'delete', 'view') NOT NULL,
    entity_type VARCHAR(50) NOT NULL,
    entity_id INT,
    description TEXT,
    ip_address VARCHAR(45),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_user_id (user_id),
    INDEX idx_created_at (created_at),
    INDEX idx_entity (entity_type, entity_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Add product_type field to products table if it doesn't exist
-- This will help categorize products as candles, terrazzo, or epoxy
-- Note: Will fail silently if column already exists (that's OK)
SET @sql = IF((SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS 
               WHERE table_schema = DATABASE() 
               AND table_name = 'products' 
               AND column_name = 'product_type') = 0,
              'ALTER TABLE products ADD COLUMN product_type ENUM(''candle'', ''terrazzo'', ''epoxy'', ''other'') DEFAULT ''other'' AFTER type',
              'SELECT ''Column product_type already exists'' AS msg');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Migration complete
-- No existing data is modified, only new structures added
