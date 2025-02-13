-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 12, 2025 at 02:25 PM
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
-- Database: `dailymonitoringsheet`
--

-- --------------------------------------------------------

--
-- Table structure for table `visits`
--

CREATE TABLE `visits` (
  `id` int(11) NOT NULL,
  `user` varchar(255) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `visit_time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `visits`
--

INSERT INTO `visits` (`id`, `user`, `ip_address`, `visit_time`) VALUES
(1, 'John Doe', '192.168.0.1', '2025-02-10 12:34:56'),
(2, 'Jane Smith', '192.168.0.2', '2025-02-10 12:35:10'),
(3, 'Alice Johnson', '192.168.0.3', '2025-02-10 12:36:00'),
(4, 'Bob Brown', '192.168.0.4', '2025-02-10 12:37:15'),
(5, 'Charlie Davis', '192.168.0.5', '2025-02-10 12:38:20'),
(6, 'Diana Prince', '10.0.0.1', '2025-02-11 09:15:30'),
(7, 'Bruce Wayne', '10.0.0.2', '2025-02-11 09:17:45'),
(8, 'Clark Kent', '10.0.0.3', '2025-02-11 09:20:00'),
(9, 'Barry Allen', '10.0.0.4', '2025-02-11 09:22:10'),
(10, 'Hal Jordan', '10.0.0.5', '2025-02-11 09:25:00'),
(11, 'Aeron Paul Daliva', '::1', '2025-02-12 13:41:58'),
(12, 'Aeron Paul Daliva', '::1', '2025-02-12 13:42:03'),
(13, 'Aeron Paul Daliva', '::1', '2025-02-12 13:47:54'),
(14, 'Aeron Paul Daliva', '::1', '2025-02-12 13:48:24'),
(15, 'Aeron Paul Daliva', '::1', '2025-02-12 13:48:41'),
(16, 'Aeron Paul Daliva', '::1', '2025-02-12 13:48:51'),
(17, 'John Doe', '192.168.0.1', '2025-02-12 09:00:00'),
(18, 'Jane Smith', '192.168.0.2', '2025-02-12 10:00:00'),
(19, 'Alice Johnson', '192.168.0.3', '2025-02-11 10:15:00'),
(20, 'Bob Brown', '192.168.0.4', '2025-02-11 17:45:00'),
(21, 'Charlie Davis', '192.168.0.5', '2025-02-10 11:30:00'),
(22, 'Diana Prince', '10.0.0.1', '2025-02-10 13:45:00'),
(23, 'Bruce Wayne', '10.0.0.2', '2025-02-09 09:15:00'),
(24, 'Clark Kent', '10.0.0.3', '2025-02-09 14:20:00'),
(25, 'Barry Allen', '10.0.0.4', '2025-02-08 08:30:00'),
(26, 'Hal Jordan', '10.0.0.5', '2025-02-08 18:10:00'),
(27, 'Lois Lane', '10.0.0.6', '2025-02-07 10:00:00'),
(28, 'Peter Parker', '10.0.0.7', '2025-02-07 16:45:00'),
(29, 'Tony Stark', '10.0.0.8', '2025-02-06 12:00:00'),
(30, 'Steve Rogers', '10.0.0.9', '2025-02-06 17:00:00'),
(31, 'Natasha Romanoff', '10.0.1.1', '2025-02-05 09:45:00'),
(32, 'Bruce Banner', '10.0.1.2', '2025-02-05 13:15:00'),
(33, 'Wade Wilson', '10.0.1.3', '2025-02-04 11:30:00'),
(34, 'Frank Castle', '10.0.1.4', '2025-02-04 15:30:00'),
(35, 'Matt Murdock', '10.0.1.5', '2025-02-03 10:15:00'),
(36, 'Olivia Pope', '10.0.1.6', '2025-02-03 14:00:00'),
(37, 'Michael Scott', '10.0.1.7', '2025-02-02 09:00:00'),
(38, 'Jim Halpert', '10.0.1.8', '2025-02-02 16:00:00'),
(39, 'Pam Beesly', '10.0.1.9', '2025-02-01 10:30:00'),
(40, 'Dwight Schrute', '10.0.1.10', '2025-02-01 13:30:00'),
(41, 'Leslie Knope', '10.0.2.1', '2025-01-31 08:45:00'),
(42, 'Ron Swanson', '10.0.2.2', '2025-01-31 15:15:00'),
(43, 'Sherlock Holmes', '10.0.2.3', '2025-01-30 11:00:00'),
(44, 'John Watson', '10.0.2.4', '2025-01-30 12:00:00'),
(45, 'Hermione Granger', '10.0.2.5', '2025-01-29 09:30:00'),
(46, 'Harry Potter', '10.0.2.6', '2025-01-29 10:30:00'),
(47, 'Aeron Paul Daliva', '::1', '2025-02-12 14:16:50'),
(48, 'Aeron Paul Daliva', '::1', '2025-02-12 14:19:08');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `visits`
--
ALTER TABLE `visits`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `visits`
--
ALTER TABLE `visits`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
