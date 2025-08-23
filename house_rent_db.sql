-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Aug 23, 2025 at 08:41 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `house_rent_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `houses`
--

CREATE TABLE `houses` (
  `id` int(11) NOT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `location` varchar(100) DEFAULT NULL,
  `bedrooms` int(11) DEFAULT NULL,
  `garden` tinyint(1) DEFAULT NULL,
  `garage` tinyint(1) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `discount` decimal(5,2) DEFAULT 0.00,
  `owner_id` int(11) DEFAULT NULL,
  `status` enum('available','sold') DEFAULT 'available'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `houses`
--

INSERT INTO `houses` (`id`, `photo`, `location`, `bedrooms`, `garden`, `garage`, `price`, `discount`, `owner_id`, `status`) VALUES
(1, 'images/house1.jpg', 'Colombo', 3, 1, 1, 250000.00, 5.00, 2, 'available'),
(2, 'images/house2.jpg', 'Kandy', 4, 1, 0, 180000.00, 0.00, 3, 'available'),
(3, 'images/house3.jpg', 'Galle', 2, 0, 1, 120000.00, 10.00, 2, 'sold'),
(4, 'images/house4.jpg', 'Kelaniya', 3, 1, 0, 232000.00, 5.00, 2, 'available'),
(5, 'images/house5.jpg', 'Jaffna', 2, 0, 1, 170000.00, 0.00, 2, 'available'),
(6, 'images/house6.jpg', 'Gampaha', 1, 0, 1, 190000.00, 10.00, 5, 'sold');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `house_id` int(11) DEFAULT NULL,
  `buyer_id` int(11) DEFAULT NULL,
  `price_paid` decimal(10,2) DEFAULT NULL,
  `date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `house_id`, `buyer_id`, `price_paid`, `date`) VALUES
(1, 3, 1, 108000.00, '2025-08-24 00:10:03'),
(2, 6, 3, 171000.00, '2025-08-24 00:10:03');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `role` enum('member','manager','admin') NOT NULL DEFAULT 'member'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `role`) VALUES
(1, 'nalindu', 'nalindu', 'nalindugra@gmail.com', 'member'),
(2, 'ushani', 'ushani', 'saubhagyaushani03@gmail.com', 'manager'),
(3, 'imesh', 'imesh', 'imeshnbandara826@gmail.com', 'member'),
(4, 'rukshan', 'rukshan', 'maleesharukshan19@gmail.com', 'admin'),
(5, 'kavindu', 'kavindu', 'kavindu@gmail.com', 'member'),
(6, 'kasun', 'kasun', 'kasun20@gmail.com', 'member');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `houses`
--
ALTER TABLE `houses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `owner_id` (`owner_id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `house_id` (`house_id`),
  ADD KEY `buyer_id` (`buyer_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `houses`
--
ALTER TABLE `houses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `houses`
--
ALTER TABLE `houses`
  ADD CONSTRAINT `houses_ibfk_1` FOREIGN KEY (`owner_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`house_id`) REFERENCES `houses` (`id`),
  ADD CONSTRAINT `transactions_ibfk_2` FOREIGN KEY (`buyer_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
