-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 07, 2022 at 06:19 PM
-- Server version: 10.4.17-MariaDB
-- PHP Version: 8.0.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `accounting`
--

-- --------------------------------------------------------

--
-- Table structure for table `account`
--

CREATE TABLE `account` (
  `id` int(11) NOT NULL,
  `name` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- RELATIONSHIPS FOR TABLE `account`:
--

-- --------------------------------------------------------

--
-- Table structure for table `cash_log`
--

CREATE TABLE `cash_log` (
  `id` int(11) NOT NULL,
  `code` varchar(30) DEFAULT NULL,
  `amount` int(11) DEFAULT NULL,
  `is_cash_in` tinyint(1) DEFAULT NULL,
  `discount_value` int(11) DEFAULT NULL,
  `part_id` int(11) DEFAULT NULL,
  `store_id` int(11) DEFAULT NULL,
  `date` date DEFAULT current_timestamp(),
  `notes` varchar(300) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- RELATIONSHIPS FOR TABLE `cash_log`:
--   `part_id`
--       `provider_customer` -> `id`
--   `store_id`
--       `store` -> `id`
--

-- --------------------------------------------------------

--
-- Table structure for table `cash_log_invoice`
--

CREATE TABLE `cash_log_invoice` (
  `id` int(11) NOT NULL,
  `cash_log_id` int(11) DEFAULT NULL,
  `invoice_id` int(11) DEFAULT NULL,
  `notes` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- RELATIONSHIPS FOR TABLE `cash_log_invoice`:
--   `cash_log_id`
--       `cash_log` -> `id`
--   `invoice_id`
--       `invoice` -> `id`
--

-- --------------------------------------------------------

--
-- Table structure for table `catergory`
--

CREATE TABLE `catergory` (
  `id` int(11) NOT NULL,
  `name` varchar(30) DEFAULT NULL,
  `number` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- RELATIONSHIPS FOR TABLE `catergory`:
--

-- --------------------------------------------------------

--
-- Table structure for table `company`
--

CREATE TABLE `company` (
  `id` int(11) NOT NULL,
  `name` varchar(30) DEFAULT NULL,
  `phone_number` varchar(30) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- RELATIONSHIPS FOR TABLE `company`:
--

-- --------------------------------------------------------

--
-- Table structure for table `drawer`
--

CREATE TABLE `drawer` (
  `id` int(11) NOT NULL,
  `name` varchar(30) DEFAULT NULL,
  `account_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- RELATIONSHIPS FOR TABLE `drawer`:
--   `account_id`
--       `account` -> `id`
--

-- --------------------------------------------------------

--
-- Table structure for table `group_setting`
--

CREATE TABLE `group_setting` (
  `id` int(11) NOT NULL,
  `group_name` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- RELATIONSHIPS FOR TABLE `group_setting`:
--

-- --------------------------------------------------------

--
-- Table structure for table `group_setting_property`
--

CREATE TABLE `group_setting_property` (
  `id` int(11) NOT NULL,
  `group_id` int(11) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `value` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- RELATIONSHIPS FOR TABLE `group_setting_property`:
--   `group_id`
--       `group_setting` -> `id`
--

-- --------------------------------------------------------

--
-- Table structure for table `invoice`
--

CREATE TABLE `invoice` (
  `id` int(11) NOT NULL,
  `code` varchar(20) DEFAULT NULL,
  `invoice_type` enum('purchase','sale','purchaseReturn','saleReturn') DEFAULT NULL,
  `part_id` int(11) DEFAULT NULL,
  `part_type` enum('priver','customer','employee') DEFAULT NULL,
  `store_id` int(11) DEFAULT NULL,
  `drawer_id` int(11) DEFAULT NULL,
  `shipping_adderss` varchar(50) DEFAULT NULL,
  `paiid` int(11) NOT NULL,
  `remainder` int(11) NOT NULL,
  `tax_value` int(11) DEFAULT NULL,
  `tax_ratio` int(11) DEFAULT NULL,
  `discount_value` int(11) DEFAULT NULL,
  `discount_ratio` int(11) DEFAULT NULL,
  `expencies` int(11) DEFAULT NULL,
  `total` int(11) DEFAULT NULL,
  `net` int(11) DEFAULT NULL,
  `is_posted` tinyint(1) DEFAULT 1,
  `out_of_store_date` date DEFAULT NULL,
  `delivering_date` date DEFAULT current_timestamp(),
  `notes` varchar(300) DEFAULT NULL,
  `source_id` int(11) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `paund_type` enum('syrian','dolar') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- RELATIONSHIPS FOR TABLE `invoice`:
--   `drawer_id`
--       `drawer` -> `id`
--   `source_id`
--       `invoice` -> `id`
--   `part_id`
--       `provider_customer` -> `id`
--   `store_id`
--       `store` -> `id`
--

-- --------------------------------------------------------

--
-- Table structure for table `invoice_details`
--

CREATE TABLE `invoice_details` (
  `id` int(11) NOT NULL,
  `invoice_id` int(11) DEFAULT NULL,
  `product_unit_id` int(11) DEFAULT NULL,
  `store_id` int(11) DEFAULT NULL,
  `item_quantity` int(11) DEFAULT NULL,
  `price` int(11) DEFAULT NULL,
  `total_price` int(11) DEFAULT NULL,
  `discount_value` int(11) DEFAULT NULL,
  `discount_ratio` int(11) DEFAULT NULL,
  `cost_value` int(11) DEFAULT NULL,
  `total_cost_value` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- RELATIONSHIPS FOR TABLE `invoice_details`:
--   `invoice_id`
--       `invoice` -> `id`
--   `product_unit_id`
--       `product_unit` -> `id`
--   `store_id`
--       `store` -> `id`
--

-- --------------------------------------------------------

--
-- Table structure for table `journal`
--

CREATE TABLE `journal` (
  `id` int(11) NOT NULL,
  `code` varchar(30) DEFAULT NULL,
  `source_id` int(11) DEFAULT NULL,
  `account_id` int(11) DEFAULT NULL,
  `credit` int(11) DEFAULT 0,
  `debit` int(11) DEFAULT 0,
  `date` date DEFAULT current_timestamp(),
  `notes` varchar(300) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- RELATIONSHIPS FOR TABLE `journal`:
--   `account_id`
--       `account` -> `id`
--   `source_id`
--       `invoice` -> `id`
--

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `category_id` int(11) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `cost_calculation_method` enum('val1','val2') DEFAULT NULL,
  `is_raw_material` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- RELATIONSHIPS FOR TABLE `product`:
--   `category_id`
--       `catergory` -> `id`
--

-- --------------------------------------------------------

--
-- Table structure for table `product_material`
--

CREATE TABLE `product_material` (
  `id` int(11) NOT NULL,
  `material_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `unit` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- RELATIONSHIPS FOR TABLE `product_material`:
--   `material_id`
--       `product` -> `id`
--   `product_id`
--       `product` -> `id`
--

-- --------------------------------------------------------

--
-- Table structure for table `product_unit`
--

CREATE TABLE `product_unit` (
  `id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `unit_id` int(11) DEFAULT NULL,
  `selling_price` float DEFAULT NULL,
  `purchace_price` float DEFAULT NULL,
  `discount_sale` float DEFAULT NULL,
  `code` varchar(20) DEFAULT NULL,
  `factor` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- RELATIONSHIPS FOR TABLE `product_unit`:
--   `product_id`
--       `product` -> `id`
--   `unit_id`
--       `unit` -> `id`
--

-- --------------------------------------------------------

--
-- Table structure for table `provider_customer`
--

CREATE TABLE `provider_customer` (
  `id` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `phone_number` int(11) DEFAULT NULL,
  `is_provider` tinyint(1) DEFAULT 0,
  `account_id` int(11) DEFAULT NULL,
  `max_credit` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- RELATIONSHIPS FOR TABLE `provider_customer`:
--   `account_id`
--       `account` -> `id`
--

-- --------------------------------------------------------

--
-- Table structure for table `screen_access`
--

CREATE TABLE `screen_access` (
  `id` int(11) NOT NULL,
  `screen_id` int(11) DEFAULT NULL,
  `can_show` tinyint(1) DEFAULT 0,
  `can_open` tinyint(1) DEFAULT 0,
  `can_add` tinyint(1) DEFAULT 0,
  `can_edit` tinyint(1) DEFAULT 0,
  `can_delete` tinyint(1) DEFAULT 0,
  `can_print` tinyint(1) DEFAULT 0,
  `group_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- RELATIONSHIPS FOR TABLE `screen_access`:
--   `group_id`
--       `screen_access_group` -> `id`
--

-- --------------------------------------------------------

--
-- Table structure for table `screen_access_group`
--

CREATE TABLE `screen_access_group` (
  `id` int(11) NOT NULL,
  `group_name` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- RELATIONSHIPS FOR TABLE `screen_access_group`:
--

-- --------------------------------------------------------

--
-- Table structure for table `store`
--

CREATE TABLE `store` (
  `id` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `inventory_account_id` int(11) DEFAULT NULL,
  `sales_account_id` int(11) DEFAULT NULL,
  `sales_return_account_id` int(11) DEFAULT NULL,
  `cost_of_sold_goods_account_id` int(11) DEFAULT NULL,
  `discount_received_account_id` int(11) DEFAULT NULL,
  `discount_allowed_account_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- RELATIONSHIPS FOR TABLE `store`:
--   `inventory_account_id`
--       `account` -> `id`
--   `sales_account_id`
--       `account` -> `id`
--   `sales_return_account_id`
--       `account` -> `id`
--   `cost_of_sold_goods_account_id`
--       `account` -> `id`
--   `discount_received_account_id`
--       `account` -> `id`
--   `discount_allowed_account_id`
--       `account` -> `id`
--

-- --------------------------------------------------------

--
-- Table structure for table `store_log`
--

CREATE TABLE `store_log` (
  `id` int(11) NOT NULL,
  `source_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `store_id` int(11) DEFAULT NULL,
  `insert_date` date DEFAULT current_timestamp(),
  `cost_value` int(11) DEFAULT NULL,
  `is_in_transaction` tinyint(1) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `notes` varchar(300) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- RELATIONSHIPS FOR TABLE `store_log`:
--   `source_id`
--       `invoice` -> `id`
--   `product_id`
--       `product_unit` -> `id`
--   `store_id`
--       `store` -> `id`
--

-- --------------------------------------------------------

--
-- Table structure for table `unit`
--

CREATE TABLE `unit` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- RELATIONSHIPS FOR TABLE `unit`:
--

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `user_name` varchar(50) DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL,
  `group_setting_id` int(11) DEFAULT NULL,
  `screen_access_group_id` int(11) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT NULL,
  `access_type` enum('admin','moderator','user') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- RELATIONSHIPS FOR TABLE `user`:
--   `group_setting_id`
--       `group_setting` -> `id`
--   `screen_access_group_id`
--       `screen_access_group` -> `id`
--

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `name`, `user_name`, `password`, `group_setting_id`, `screen_access_group_id`, `is_active`, `access_type`) VALUES
(8, 'Abdulmoty', 'Abdulmotyban', '2580d49ff4060824fc921008b52e8e6cd9380570', NULL, NULL, 1, 'admin'),
(9, '', '', 'da39a3ee5e6b4b0d3255bfef95601890afd80709', NULL, NULL, 1, ''),
(10, '', '', 'da39a3ee5e6b4b0d3255bfef95601890afd80709', NULL, NULL, 1, ''),
(11, '', '', 'da39a3ee5e6b4b0d3255bfef95601890afd80709', NULL, NULL, 1, ''),
(12, '', '', 'da39a3ee5e6b4b0d3255bfef95601890afd80709', NULL, NULL, 1, ''),
(13, 'erwt', 'erwtewrt', '393625ad17b66bad20a15b06ba1818075ae4335c', NULL, NULL, 1, ''),
(14, 'erwt', 'erwtewrt', '393625ad17b66bad20a15b06ba1818075ae4335c', NULL, NULL, 1, ''),
(15, '', '', 'da39a3ee5e6b4b0d3255bfef95601890afd80709', NULL, NULL, 1, ''),
(16, '4trew', '4trewerwt', 'ac738d8c09a39c2b59df6aa2cec1a75327f5be07', NULL, NULL, 1, ''),
(17, '4trew', '4trewerwt', 'ac738d8c09a39c2b59df6aa2cec1a75327f5be07', NULL, NULL, 1, ''),
(18, '4trew', '4trewerwt', 'ac738d8c09a39c2b59df6aa2cec1a75327f5be07', NULL, NULL, 1, ''),
(19, '4trew', '4trewerwt', 'ac738d8c09a39c2b59df6aa2cec1a75327f5be07', NULL, NULL, 1, ''),
(20, '4trew', '4trewerwt', 'ac738d8c09a39c2b59df6aa2cec1a75327f5be07', NULL, NULL, 1, ''),
(21, '', '', 'da39a3ee5e6b4b0d3255bfef95601890afd80709', NULL, NULL, 1, ''),
(22, '', '', 'da39a3ee5e6b4b0d3255bfef95601890afd80709', NULL, NULL, 1, ''),
(23, 'mahmoud', 'mahmoudaaa', '7e240de74fb1ed08fa08d38063f6a6a91462a815', NULL, NULL, 1, ''),
(24, 'Abu sutaif', 'Abu sutaif', 'da39a3ee5e6b4b0d3255bfef95601890afd80709', NULL, NULL, 1, ''),
(25, '', '', 'da39a3ee5e6b4b0d3255bfef95601890afd80709', NULL, NULL, 1, ''),
(26, 'kjdsf[ksa;', 'kjdsf[ksa;', 'da39a3ee5e6b4b0d3255bfef95601890afd80709', NULL, NULL, 1, ''),
(27, '', '', 'da39a3ee5e6b4b0d3255bfef95601890afd80709', NULL, NULL, 1, '');

-- --------------------------------------------------------

--
-- Table structure for table `user_log`
--

CREATE TABLE `user_log` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `action_type` enum('add','delete','modify','show','print') DEFAULT NULL,
  `action_date` date DEFAULT current_timestamp(),
  `screen_id` int(11) DEFAULT NULL,
  `part_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- RELATIONSHIPS FOR TABLE `user_log`:
--   `user_id`
--       `user` -> `id`
--

--
-- Indexes for dumped tables
--

--
-- Indexes for table `account`
--
ALTER TABLE `account`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cash_log`
--
ALTER TABLE `cash_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_cash_log_provider_customer` (`part_id`),
  ADD KEY `fk_cash_log_store` (`store_id`);

--
-- Indexes for table `cash_log_invoice`
--
ALTER TABLE `cash_log_invoice`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cash_log_invoice_cash_log` (`cash_log_id`),
  ADD KEY `cash_log_invoice_invoice` (`invoice_id`);

--
-- Indexes for table `catergory`
--
ALTER TABLE `catergory`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `company`
--
ALTER TABLE `company`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `drawer`
--
ALTER TABLE `drawer`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_drawer_account` (`account_id`);

--
-- Indexes for table `group_setting`
--
ALTER TABLE `group_setting`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `group_setting_property`
--
ALTER TABLE `group_setting_property`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_group_setting_property_group_setting` (`group_id`);

--
-- Indexes for table `invoice`
--
ALTER TABLE `invoice`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_invoice_provider_customer` (`part_id`),
  ADD KEY `fk_invoice_store` (`store_id`),
  ADD KEY `fk_invoice_drawer` (`drawer_id`),
  ADD KEY `fk_invoice_invoice` (`source_id`);

--
-- Indexes for table `invoice_details`
--
ALTER TABLE `invoice_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_invoice_details_invoice` (`invoice_id`),
  ADD KEY `fk_invoice_details_product_unit` (`product_unit_id`),
  ADD KEY `fk_invoice_details_store` (`store_id`);

--
-- Indexes for table `journal`
--
ALTER TABLE `journal`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_journal_account` (`account_id`),
  ADD KEY `fk_journal_invoice` (`source_id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_product_category` (`category_id`);

--
-- Indexes for table `product_material`
--
ALTER TABLE `product_material`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_material_product_1` (`material_id`),
  ADD KEY `product_material_product_2` (`product_id`);

--
-- Indexes for table `product_unit`
--
ALTER TABLE `product_unit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_product_unit_unit` (`unit_id`),
  ADD KEY `fk_product_unit_product` (`product_id`);

--
-- Indexes for table `provider_customer`
--
ALTER TABLE `provider_customer`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_provider_customer_account` (`account_id`);

--
-- Indexes for table `screen_access`
--
ALTER TABLE `screen_access`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_screen_access_screen_access_group` (`group_id`);

--
-- Indexes for table `screen_access_group`
--
ALTER TABLE `screen_access_group`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `store`
--
ALTER TABLE `store`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_store_account1` (`inventory_account_id`),
  ADD KEY `fk_store_account2` (`sales_account_id`),
  ADD KEY `fk_store_account3` (`sales_return_account_id`),
  ADD KEY `fk_store_account4` (`cost_of_sold_goods_account_id`),
  ADD KEY `fk_store_account5` (`discount_received_account_id`),
  ADD KEY `fk_store_account6` (`discount_allowed_account_id`);

--
-- Indexes for table `store_log`
--
ALTER TABLE `store_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_store_log_invoice` (`source_id`),
  ADD KEY `fk_store_log_product_unit` (`product_id`),
  ADD KEY `fk_store_log_store` (`store_id`);

--
-- Indexes for table `unit`
--
ALTER TABLE `unit`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user_group_setting` (`group_setting_id`),
  ADD KEY `fk_user_screen_access_group` (`screen_access_group_id`);

--
-- Indexes for table `user_log`
--
ALTER TABLE `user_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user_log_user` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `account`
--
ALTER TABLE `account`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cash_log`
--
ALTER TABLE `cash_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cash_log_invoice`
--
ALTER TABLE `cash_log_invoice`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `catergory`
--
ALTER TABLE `catergory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `company`
--
ALTER TABLE `company`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `drawer`
--
ALTER TABLE `drawer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `group_setting`
--
ALTER TABLE `group_setting`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `group_setting_property`
--
ALTER TABLE `group_setting_property`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoice`
--
ALTER TABLE `invoice`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoice_details`
--
ALTER TABLE `invoice_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `journal`
--
ALTER TABLE `journal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_material`
--
ALTER TABLE `product_material`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_unit`
--
ALTER TABLE `product_unit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `provider_customer`
--
ALTER TABLE `provider_customer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `screen_access`
--
ALTER TABLE `screen_access`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `screen_access_group`
--
ALTER TABLE `screen_access_group`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `store`
--
ALTER TABLE `store`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `store_log`
--
ALTER TABLE `store_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `unit`
--
ALTER TABLE `unit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `user_log`
--
ALTER TABLE `user_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cash_log`
--
ALTER TABLE `cash_log`
  ADD CONSTRAINT `fk_cash_log_provider_customer` FOREIGN KEY (`part_id`) REFERENCES `provider_customer` (`id`),
  ADD CONSTRAINT `fk_cash_log_store` FOREIGN KEY (`store_id`) REFERENCES `store` (`id`);

--
-- Constraints for table `cash_log_invoice`
--
ALTER TABLE `cash_log_invoice`
  ADD CONSTRAINT `cash_log_invoice_cash_log` FOREIGN KEY (`cash_log_id`) REFERENCES `cash_log` (`id`),
  ADD CONSTRAINT `cash_log_invoice_invoice` FOREIGN KEY (`invoice_id`) REFERENCES `invoice` (`id`);

--
-- Constraints for table `drawer`
--
ALTER TABLE `drawer`
  ADD CONSTRAINT `fk_drawer_account` FOREIGN KEY (`account_id`) REFERENCES `account` (`id`);

--
-- Constraints for table `group_setting_property`
--
ALTER TABLE `group_setting_property`
  ADD CONSTRAINT `fk_group_setting_property_group_setting` FOREIGN KEY (`group_id`) REFERENCES `group_setting` (`id`);

--
-- Constraints for table `invoice`
--
ALTER TABLE `invoice`
  ADD CONSTRAINT `fk_invoice_drawer` FOREIGN KEY (`drawer_id`) REFERENCES `drawer` (`id`),
  ADD CONSTRAINT `fk_invoice_invoice` FOREIGN KEY (`source_id`) REFERENCES `invoice` (`id`),
  ADD CONSTRAINT `fk_invoice_provider_customer` FOREIGN KEY (`part_id`) REFERENCES `provider_customer` (`id`),
  ADD CONSTRAINT `fk_invoice_store` FOREIGN KEY (`store_id`) REFERENCES `store` (`id`);

--
-- Constraints for table `invoice_details`
--
ALTER TABLE `invoice_details`
  ADD CONSTRAINT `fk_invoice_details_invoice` FOREIGN KEY (`invoice_id`) REFERENCES `invoice` (`id`),
  ADD CONSTRAINT `fk_invoice_details_product_unit` FOREIGN KEY (`product_unit_id`) REFERENCES `product_unit` (`id`),
  ADD CONSTRAINT `fk_invoice_details_store` FOREIGN KEY (`store_id`) REFERENCES `store` (`id`);

--
-- Constraints for table `journal`
--
ALTER TABLE `journal`
  ADD CONSTRAINT `fk_journal_account` FOREIGN KEY (`account_id`) REFERENCES `account` (`id`),
  ADD CONSTRAINT `fk_journal_invoice` FOREIGN KEY (`source_id`) REFERENCES `invoice` (`id`);

--
-- Constraints for table `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `fk_product_category` FOREIGN KEY (`category_id`) REFERENCES `catergory` (`id`);

--
-- Constraints for table `product_material`
--
ALTER TABLE `product_material`
  ADD CONSTRAINT `product_material_product_1` FOREIGN KEY (`material_id`) REFERENCES `product` (`id`),
  ADD CONSTRAINT `product_material_product_2` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`);

--
-- Constraints for table `product_unit`
--
ALTER TABLE `product_unit`
  ADD CONSTRAINT `fk_product_unit_product` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`),
  ADD CONSTRAINT `fk_product_unit_unit` FOREIGN KEY (`unit_id`) REFERENCES `unit` (`id`);

--
-- Constraints for table `provider_customer`
--
ALTER TABLE `provider_customer`
  ADD CONSTRAINT `fk_provider_customer_account` FOREIGN KEY (`account_id`) REFERENCES `account` (`id`);

--
-- Constraints for table `screen_access`
--
ALTER TABLE `screen_access`
  ADD CONSTRAINT `fk_screen_access_screen_access_group` FOREIGN KEY (`group_id`) REFERENCES `screen_access_group` (`id`);

--
-- Constraints for table `store`
--
ALTER TABLE `store`
  ADD CONSTRAINT `fk_store_account1` FOREIGN KEY (`inventory_account_id`) REFERENCES `account` (`id`),
  ADD CONSTRAINT `fk_store_account2` FOREIGN KEY (`sales_account_id`) REFERENCES `account` (`id`),
  ADD CONSTRAINT `fk_store_account3` FOREIGN KEY (`sales_return_account_id`) REFERENCES `account` (`id`),
  ADD CONSTRAINT `fk_store_account4` FOREIGN KEY (`cost_of_sold_goods_account_id`) REFERENCES `account` (`id`),
  ADD CONSTRAINT `fk_store_account5` FOREIGN KEY (`discount_received_account_id`) REFERENCES `account` (`id`),
  ADD CONSTRAINT `fk_store_account6` FOREIGN KEY (`discount_allowed_account_id`) REFERENCES `account` (`id`);

--
-- Constraints for table `store_log`
--
ALTER TABLE `store_log`
  ADD CONSTRAINT `fk_store_log_invoice` FOREIGN KEY (`source_id`) REFERENCES `invoice` (`id`),
  ADD CONSTRAINT `fk_store_log_product_unit` FOREIGN KEY (`product_id`) REFERENCES `product_unit` (`id`),
  ADD CONSTRAINT `fk_store_log_store` FOREIGN KEY (`store_id`) REFERENCES `store` (`id`);

--
-- Constraints for table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `fk_user_group_setting` FOREIGN KEY (`group_setting_id`) REFERENCES `group_setting` (`id`),
  ADD CONSTRAINT `fk_user_screen_access_group` FOREIGN KEY (`screen_access_group_id`) REFERENCES `screen_access_group` (`id`);

--
-- Constraints for table `user_log`
--
ALTER TABLE `user_log`
  ADD CONSTRAINT `fk_user_log_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
