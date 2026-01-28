CREATE TABLE `stock_count` (
                               `id` int NOT NULL,
                               `product_sku` varchar(255) NOT NULL,
                               `category` varchar(255) NOT NULL,
                               `counted_stock` int NOT NULL,
                               `counted_by_user` int NOT NULL,
                               `count_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
