-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 04, 2024 at 09:33 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `jasmin_rice`
--

-- --------------------------------------------------------

--
-- Table structure for table `rice_inventory`
--

CREATE TABLE `rice_inventory` (
  `rice_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `stocks` int(11) NOT NULL,
  `kilograms` decimal(10,2) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `expiration_date` date NOT NULL,
  `status` enum('1','2','3') NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rice_inventory`
--

INSERT INTO `rice_inventory` (`rice_id`, `name`, `stocks`, `kilograms`, `price`, `expiration_date`, `status`) VALUES
(47, 'jasmine', 600, 25.00, 1200.00, '2024-04-17', '1'),
(48, '170', 99, 25.00, 1400.00, '2024-04-26', '2'),
(49, 'golden rice', 1000, 25.00, 1350.00, '2024-05-09', '1'),
(50, 'glutinous rice', 50, 25.00, 1300.00, '2024-05-09', '1'),
(51, 'super angelica', 100, 25.00, 1200.00, '0000-00-00', '1'),
(52, 'jasmin', 900, 25.00, 1500.00, '2024-04-16', '3'),
(53, '160', 900, 5.00, 1200.00, '2024-04-30', '1'),
(54, 'black rice', 900, 25.00, 3000.00, '2024-05-14', '1');

-- --------------------------------------------------------

--
-- Table structure for table `tokens`
--

CREATE TABLE `tokens` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `token` int(11) NOT NULL,
  `creat_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tokens`
--

INSERT INTO `tokens` (`id`, `user_id`, `token`, `creat_at`) VALUES
(21, 11, 3, '2024-05-03 15:28:50');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') NOT NULL DEFAULT 'user',
  `last_login` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`, `last_login`) VALUES
(11, 'jasmin', 'jasminmilla1@gmail.com', '$2y$10$tULHnTtRx6m9Ll1FwU5sfehlUML3kLCGkbfTO4k2lMmKxJbwqZaWe', 'admin', '2024-05-03 15:28:50'),
(14, 'kalabog', 'vjohnlawrence321@gmail.com', '$2y$10$.i5d7jv3J946IkqKTVpui.6vuMaYTvPgbKqSyln10HDywnxoJeavO', 'user', '2024-05-03 15:06:33'),
(15, 'edgen', 'edgen1@gmail.com', '$2y$10$dYKezwfzBc.6q791M.zF5eOw6XwsJc090a.A4TZTN5lTgBC5rr9ua', 'user', '2024-05-03 15:12:50');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `rice_inventory`
--
ALTER TABLE `rice_inventory`
  ADD PRIMARY KEY (`rice_id`);

--
-- Indexes for table `tokens`
--
ALTER TABLE `tokens`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `rice_inventory`
--
ALTER TABLE `rice_inventory`
  MODIFY `rice_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `tokens`
--
ALTER TABLE `tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
