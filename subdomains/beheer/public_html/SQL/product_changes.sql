CREATE TABLE `product_changes` (
                                   `id` int NOT NULL,
                                   `product_id` int NOT NULL,
                                   `category` varchar(255) NOT NULL,
                                   `field_name` varchar(255) NOT NULL,
                                   `old_value` text,
                                   `new_value` text,
                                   `changed_by_user` int DEFAULT NULL,
                                   `change_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
