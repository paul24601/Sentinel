-- Create the product_parameters table if it doesn't exist
CREATE TABLE IF NOT EXISTS `product_parameters` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `product_name` VARCHAR(255) UNIQUE,
    `cycle_time_target` DECIMAL(10,2),
    `weight_standard` DECIMAL(10,2),
    `cavity_designed` INT,
    `mold_code` INT,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Add indexes for better performance
ALTER TABLE `product_parameters` ADD INDEX `idx_product_name` (`product_name`);

-- Example data (optional - remove if not needed)
INSERT INTO `product_parameters` (`product_name`, `cycle_time_target`, `weight_standard`, `cavity_designed`, `mold_code`) VALUES
('EXAMPLE PRODUCT 1', 15.50, 25.30, 4, 1001),
('EXAMPLE PRODUCT 2', 12.75, 18.20, 2, 1002);

-- Grant necessary permissions (adjust username and password as needed)
GRANT SELECT, INSERT, UPDATE, DELETE ON dailymonitoringsheet.product_parameters TO 'root'@'localhost';
FLUSH PRIVILEGES; 