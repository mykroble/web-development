DROP DATABASE IF EXISTS `Lobster_Inventory`;

CREATE DATABASE `Lobster_Inventory`;
USE `Lobster_Inventory`;


CREATE TABLE `suppliers` (
    `supplier_id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `supplier_name` varchar(50),
    `contact_number` varchar(20),
    `street` varchar(50),
    `City` varchar(50),
    `Province` varchar(50)
);

CREATE TABLE `raw_materials`(
    `raw_material_id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `supplier_id` int NOT NULL,
    `material_name` varchar(50),
    `weight_in_kg` decimal(6,2),
    `delivery_date` date,
    CONSTRAINT `supplier_id_fk` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers`(`supplier_id`)
);

CREATE TABLE `inventory_pack` (
    `inventory_pack_id` int AUTO_INCREMENT PRIMARY KEY,
    `name` varchar(50),
    `quantity` int,
    `minquantity` int,
    `category` varchar(50),
    `weight` decimal(6,2),
    `price` decimal(7,2),
    `status` ENUM('Available', 'Unavailable') DEFAULT 'Available',
    `active_status` ENUM('Active', 'Inactive') DEFAULT 'Active'
);

CREATE TABLE `inventory_daily_record` (
    `date` date,
    `inventory_pack_id` int(11),
    `starting_quantity` int,
    `additional_quantity` int DEFAULT 0,
    `sold_quantity` int DEFAULT 0,
    `wasted_quantity` int DEFAULT 0,
    `ending_quantity` int,
    `total_sales` decimal(10,2),
    CONSTRAINT `inventory_pack_id_fk` FOREIGN KEY (`inventory_pack_id`) REFERENCES `inventory_pack`(`inventory_pack_id`),
    PRIMARY KEY (`date`, `inventory_pack_id`)
);

CREATE TABLE `role`(
    `role_id` int(11) NOT NULL AUTO_INCREMENT,
    `role_name` varchar(10),
    PRIMARY KEY(`role_id`)
) ENGINE=INNODB;

CREATE TABLE `users`(
    `user_id` int(11) NOT NULL AUTO_INCREMENT,
    `role_id` int(11),
    `icon_path` varchar(255) NOT NULL,
    `user_fname` varchar(50) NOT NULL,
    `user_lname` varchar(50) NOT NULL,
    `email` varchar(50) NOT NULL,
    `user_password` varchar(255) NOT NULL,
    PRIMARY KEY(`user_id`),
    CONSTRAINT `role_id_fk` FOREIGN KEY (`role_id`) REFERENCES `role`(`role_id`)
) ENGINE=INNODB;

CREATE TABLE `actions`(
    `action_id` int(11) NOT NULL AUTO_INCREMENT,
    `action_name` varchar(50) NOT NULL,
    PRIMARY KEY(`action_id`)
) ENGINE=INNODB;

-- Inserting Roles and Users

INSERT INTO `role` (`role_id`, `role_name`) VALUES
(1, 'Owner'),
(2, 'Manager'),
(3, 'Staff');

INSERT INTO `users` (`user_id`, `role_id`, `icon_path`, `user_fname`, `user_lname`, `email`, `user_password`) VALUES
(1, 1, 'employeeProfile/owner.jpg', 'Sam', 'Russ', 'samuel@gmail.com', '$2y$10$W0WhXwQDcjdwOSavwvg2TOYUeemHHTMDfCxiY88Q8mI44G03RguJ6'),
(2, 2, 'employeeProfile/profile1.jpg', 'Rami', 'Malek', 'manager@gmail.com', '$2y$10$qvyaN.7Vt8qYinmKnrhGRuWewZo/GJe6X6I.USW50DD87ujtiWgK6'),
(3, 3, 'employeeProfile/profile2.jpg', 'Redford', 'White', 'staff1@gmail.com', '$2y$10$.b.9za8cUuVieOHI4aBrluGttbC6mvPMGi5UVeapXq5iBpzquK0lS'),
(4, 3, 'employeeProfile/profile3.jpg', 'Mikha', 'Lim', 'staff2@gmail.com', '$2y$10$7B/NwkNADjfWFnoE6cozNOFb/HSDoQ8f5E87oMrOA0EmdbN4uxfEK');

-- Inserting Suppliers and Raw Materials

INSERT INTO `suppliers` (`supplier_name`, `contact_number`, `street`, `City`, `Province`) VALUES
('Live Seafoods', '1234567890', 'Marigondon St.', 'Lapu-Lapu City', 'Cebu'),
('Meat Products', '0987654321', 'Colon St.', 'Cebu', 'Cebu');

INSERT INTO `raw_materials` (`supplier_id`, `material_name`, `weight_in_kg`, `delivery_date`) VALUES
(1, 'Lobster', 50.00, '2024-07-01'),
(2, 'Chicken', 100.00, '2024-07-02'),
(2, 'Pork', 200.00, '2024-07-02'),
(2, 'Beef', 300.00, '2024-07-03');

-- Inserting Inventory Pack Data

INSERT INTO inventory_pack (name, quantity, minquantity, category, weight, price, status, active_status) VALUES
('Live Tiger Lobster', 10, 5, 'Live Seafoods', 1.00, 5000.00, 'Available', 'Active'),
('Live Alimango', 20, 10, 'Live Seafoods', 0.40, 2400.00, 'Available', 'Active'),
('Live Lapu-Lapu', 30, 15, 'Live Seafoods', 1.00, 1600.00, 'Available', 'Active'),
('Squid', 50, 20, 'Seafoods', 1.00, 1300.00, 'Available', 'Active'),
('Prawns Medium', 40, 20, 'Seafoods', 1.00, 1550.00, 'Available', 'Active'),
('Prawns Large', 30, 15, 'Seafoods', 1.00, 1750.00, 'Available', 'Active'),
('Prawns XL', 20, 10, 'Seafoods', 1.00, 1850.00, 'Available', 'Active'),
('Soft Shell Crabs', 40, 20, 'Seafoods', 1.00, 1600.00, 'Available', 'Active'),
('Scallops', 50, 25, 'Seafoods', 1.00, 850.00, 'Available', 'Active'),
('Mussels Chilean', 60, 30, 'Seafoods', 1.00, 950.00, 'Available', 'Active'),
('Pampano', 30, 15, 'Seafoods', 1.00, 480.00, 'Available', 'Active'),
('Curacha', 25, 10, 'Seafoods', 1.00, 1500.00, 'Available', 'Active'),
('Shrimps Tempura', 60, 30, 'Seafoods', 0.50, 550.00, 'Available', 'Active'),
('Shrimps Platter', 60, 30, 'Seafoods', 0.50, 550.00, 'Available', 'Active'),
('Salt & Pepper Shrimps', 60, 30, 'Seafoods', 0.50, 550.00, 'Available', 'Active'),
('Seafood Boil Full', 20, 10, 'Seafoods', 1.50, 1799.00, 'Available', 'Active'),
('Seafood Boil Half', 20, 10, 'Seafoods', 0.75, 1299.00, 'Available', 'Active'),
('Sweet N Sour Pork', 80, 40, 'Pork', 0.35, 350.00, 'Available', 'Active'),
('Salt N Pepper Spare Ribs', 70, 35, 'Pork', 0.35, 350.00, 'Available', 'Active'),
('Stir Fry Pork', 60, 30, 'Pork', 0.35, 350.00, 'Available', 'Active'),
('Sisig', 90, 45, 'Pork', 0.28, 280.00, 'Available', 'Active'),
('Steak Bites with Garlic Sauce', 40, 20, 'Beef', 0.40, 390.00, 'Available', 'Active'),
('Bistek Tagalog', 40, 20, 'Beef', 0.40, 390.00, 'Available', 'Active'),
('Beef Stir Fry', 50, 25, 'Beef', 0.40, 390.00, 'Available', 'Active'),
('Lemon Chicken', 30, 15, 'Chicken', 0.30, 330.00, 'Available', 'Active'),
('3 Cups Chicken', 30, 15, 'Chicken', 0.30, 330.00, 'Available', 'Active'),
('Deep Fried Chicken Whole', 40, 20, 'Chicken', 1.00, 550.00, 'Available', 'Active'),
('Deep Fried Chicken Half', 50, 25, 'Chicken', 0.50, 300.00, 'Available', 'Active');


