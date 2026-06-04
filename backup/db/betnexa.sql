-- phpMyAdmin SQL Dump
-- version 5.2.3deb1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 04, 2026 at 08:29 AM
-- Server version: 8.4.8-0ubuntu1
-- PHP Version: 8.5.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `betnexa`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`, `created_at`) VALUES
(1, 'admin', '$2y$10$py9qN/aa6CF2braKh7iZqODsbnXsk0MnN7UQsnghGrNM8Z.pkiyue', '2026-05-30 22:59:23');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `subscription_id` int DEFAULT NULL,
  `plan_id` int DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_method` enum('Mpesa','Manual','Other') NOT NULL DEFAULT 'Mpesa',
  `mpesa_code` varchar(50) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `status` enum('Pending','Verified','Rejected') NOT NULL DEFAULT 'Pending',
  `paid_at` datetime DEFAULT NULL,
  `verified_at` datetime DEFAULT NULL,
  `verified_by` int DEFAULT NULL,
  `notes` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `predictions`
--

CREATE TABLE `predictions` (
  `id` int NOT NULL,
  `sport` enum('Football','Basketball','Tennis','Baseball') NOT NULL,
  `home_team` varchar(100) NOT NULL,
  `away_team` varchar(100) NOT NULL,
  `prediction` varchar(150) NOT NULL,
  `odds` varchar(20) DEFAULT NULL,
  `access_type` enum('Free','VIP') NOT NULL DEFAULT 'Free',
  `match_date` date NOT NULL,
  `match_time` time DEFAULT NULL,
  `status` enum('Pending','Won','Lost','Void') NOT NULL DEFAULT 'Pending',
  `created_by` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `predictions`
--

INSERT INTO `predictions` (`id`, `sport`, `home_team`, `away_team`, `prediction`, `odds`, `access_type`, `match_date`, `match_time`, `status`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'Football', 'Arsenal', 'PSG', 'Over 2.5', '1.35', 'Free', '2026-06-02', '22:00:00', 'Won', 1, '2026-06-01 14:21:32', '2026-06-01 14:21:32'),
(2, 'Tennis', 'Le Blanc', 'Tina Santa', 'Draw', '3.50', 'VIP', '2026-07-16', '14:15:00', 'Lost', 1, '2026-06-01 14:37:59', '2026-06-01 14:37:59'),
(3, 'Football', 'Manchester City', 'Chelsea', 'Over 1.05', '1.00', 'VIP', '2026-07-05', '14:56:00', 'Lost', 1, '2026-06-01 14:45:17', '2026-06-01 14:45:17'),
(4, 'Football', 'Liverpool', 'Manchester United', 'Over 1.05', '1.00', 'VIP', '2026-07-05', '14:56:00', 'Lost', 1, '2026-06-01 14:46:34', '2026-06-01 14:46:34'),
(5, 'Baseball', 'India', 'Pakistan', 'Over 2.5', '3.50', 'VIP', '2026-07-14', '12:45:00', 'Lost', 1, '2026-06-01 14:50:09', '2026-06-01 14:50:09'),
(6, 'Tennis', 'Xen', 'Nyx', 'Draw', '1.00', 'VIP', '2026-06-03', '14:10:00', 'Pending', 1, '2026-06-01 14:58:19', '2026-06-01 14:58:19'),
(7, 'Football', 'West Ham', 'Manchester City', 'Draw 2:2', '2.50', 'Free', '2026-07-14', '10:10:00', 'Lost', 1, '2026-06-02 08:33:08', '2026-06-04 08:28:03'),
(8, 'Basketball', 'Bulls', 'Grazers', '4.5', '3', 'Free', '2026-08-23', '22:10:00', 'Won', 1, '2026-06-02 08:35:09', '2026-06-04 08:27:08');

-- --------------------------------------------------------

--
-- Table structure for table `subscriptions`
--

CREATE TABLE `subscriptions` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `plan_id` int NOT NULL,
  `start_date` datetime NOT NULL,
  `expiry_date` datetime NOT NULL,
  `status` enum('Pending','Active','Expired','Cancelled') NOT NULL DEFAULT 'Pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subscription_plans`
--

CREATE TABLE `subscription_plans` (
  `id` int NOT NULL,
  `name` varchar(50) NOT NULL,
  `slug` varchar(50) NOT NULL,
  `duration_days` int NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `status` enum('Active','Inactive') NOT NULL DEFAULT 'Active',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `subscription_plans`
--

INSERT INTO `subscription_plans` (`id`, `name`, `slug`, `duration_days`, `price`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Daily', 'daily', 1, 100.00, 'Active', '2026-05-31 10:29:26', '2026-05-31 10:29:26'),
(2, 'Monthly', 'monthly', 30, 1000.00, 'Active', '2026-05-31 10:29:26', '2026-05-31 10:29:26'),
(3, 'Annual', 'annual', 365, 8000.00, 'Active', '2026-05-31 10:29:26', '2026-05-31 10:29:26');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `email` varchar(120) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `status` enum('Active','Suspended') NOT NULL DEFAULT 'Active',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `full_name`, `phone`, `email`, `password`, `status`, `created_at`, `updated_at`) VALUES
(1, 'John Mwangi', '0712345671', 'john@example.com', '$2y$10$N9qo8uLOickgx2ZMRZoMyeIjZAgcfl7p92ldGxad68LJZdL17lhWy', 'Active', '2026-05-31 10:31:49', '2026-05-31 10:31:49'),
(2, 'Brian Otieno', '0712345672', 'brian@example.com', '$2y$10$N9qo8uLOickgx2ZMRZoMyeIjZAgcfl7p92ldGxad68LJZdL17lhWy', 'Active', '2026-05-31 10:31:49', '2026-05-31 10:31:49'),
(3, 'Kevin Kiptoo', '0712345673', 'kevin@example.com', '$2y$10$N9qo8uLOickgx2ZMRZoMyeIjZAgcfl7p92ldGxad68LJZdL17lhWy', 'Active', '2026-05-31 10:31:49', '2026-05-31 10:31:49'),
(4, 'Faith Wanjiku', '0712345674', 'faith@example.com', '$2y$10$N9qo8uLOickgx2ZMRZoMyeIjZAgcfl7p92ldGxad68LJZdL17lhWy', 'Active', '2026-05-31 10:31:49', '2026-05-31 10:31:49'),
(5, 'Mercy Akinyi', '0712345675', 'mercy@example.com', '$2y$10$N9qo8uLOickgx2ZMRZoMyeIjZAgcfl7p92ldGxad68LJZdL17lhWy', 'Suspended', '2026-05-31 10:31:49', '2026-05-31 10:31:49'),
(6, 'David Kamau', '0712345676', 'david@example.com', '$2y$10$N9qo8uLOickgx2ZMRZoMyeIjZAgcfl7p92ldGxad68LJZdL17lhWy', 'Active', '2026-05-31 10:31:49', '2026-05-31 10:31:49'),
(7, 'Esther Chebet', '0712345677', 'esther@example.com', '$2y$10$N9qo8uLOickgx2ZMRZoMyeIjZAgcfl7p92ldGxad68LJZdL17lhWy', 'Active', '2026-05-31 10:31:49', '2026-05-31 10:31:49');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `mpesa_code` (`mpesa_code`),
  ADD KEY `verified_by` (`verified_by`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `subscription_id` (`subscription_id`),
  ADD KEY `plan_id` (`plan_id`),
  ADD KEY `status` (`status`),
  ADD KEY `mpesa_code_2` (`mpesa_code`);

--
-- Indexes for table `predictions`
--
ALTER TABLE `predictions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `sport` (`sport`),
  ADD KEY `access_type` (`access_type`),
  ADD KEY `status` (`status`),
  ADD KEY `match_date` (`match_date`);

--
-- Indexes for table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `plan_id` (`plan_id`),
  ADD KEY `status` (`status`),
  ADD KEY `expiry_date` (`expiry_date`);

--
-- Indexes for table `subscription_plans`
--
ALTER TABLE `subscription_plans`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `phone` (`phone`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `predictions`
--
ALTER TABLE `predictions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `subscriptions`
--
ALTER TABLE `subscriptions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subscription_plans`
--
ALTER TABLE `subscription_plans`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `payments_ibfk_2` FOREIGN KEY (`subscription_id`) REFERENCES `subscriptions` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `payments_ibfk_3` FOREIGN KEY (`plan_id`) REFERENCES `subscription_plans` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `payments_ibfk_4` FOREIGN KEY (`verified_by`) REFERENCES `admins` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `predictions`
--
ALTER TABLE `predictions`
  ADD CONSTRAINT `predictions_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `admins` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD CONSTRAINT `subscriptions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `subscriptions_ibfk_2` FOREIGN KEY (`plan_id`) REFERENCES `subscription_plans` (`id`) ON DELETE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
