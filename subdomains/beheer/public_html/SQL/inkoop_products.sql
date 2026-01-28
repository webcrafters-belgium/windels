use windelsbe_db;
CREATE TABLE `inkoop_products` (
                                   `id` int NOT NULL,
                                   `sku` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
                                   `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
                                   `product_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
                                   `product_description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
                                   `purchase_price` decimal(10,3) NOT NULL,
                                   `extra_parts_price` decimal(10,2) DEFAULT NULL,
                                   `margin` decimal(5,2) DEFAULT NULL,
                                   `hours_worked` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
                                   `created_by_user` int DEFAULT NULL,
                                   `company_cost_per_product` decimal(10,2) DEFAULT '94.90',
                                   `sold_in_branches` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
                                   `vat_percentage` decimal(5,2) DEFAULT '21.00',
                                   `total_product_price` decimal(10,2) DEFAULT NULL,
                                   `created_on` date DEFAULT NULL,
                                   `shipping_method` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
                                   `category` varchar(255) COLLATE utf8mb4_general_ci DEFAULT 'inkoop',
                                   `stock` int DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
