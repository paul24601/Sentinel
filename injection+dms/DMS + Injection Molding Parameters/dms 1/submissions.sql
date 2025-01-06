-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 04, 2024 at 09:45 AM
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
-- Database: `production_data`
--

-- --------------------------------------------------------

--
-- Table structure for table `submissions`
--

CREATE TABLE `submissions` (
  `id` int(11) NOT NULL,
  `date` date NOT NULL,
  `machine` varchar(255) NOT NULL,
  `prn` varchar(255) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `mold_code` int(255) NOT NULL,
  `cycle_time_target` int(11) NOT NULL,
  `cycle_time_actual` int(11) NOT NULL,
  `weight_standard` decimal(10,2) NOT NULL,
  `weight_gross` decimal(10,2) NOT NULL,
  `weight_net` decimal(10,2) NOT NULL,
  `cavity_designed` int(11) NOT NULL,
  `cavity_active` int(11) NOT NULL,
  `remarks` text DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `shift` varchar(50) NOT NULL,
  `search` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `submissions`
--

INSERT INTO `submissions` (`id`, `date`, `machine`, `prn`, `product_name`, `cycle_time_target`, `cycle_time_actual`, `weight_standard`, `weight_gross`, `weight_net`, `cavity_designed`, `cavity_active`, `remarks`, `name`, `shift`, `search`) VALUES
(1, '2024-08-30', 'PER 123', 'asdm123', 'Chair', 65, 12, 32.00, 12.00, 24.00, 8, 12, 'It was good.', 'Ella', 'Night Shift', 'ser342'),
(2, '2024-08-30', 'SUM 260C', '123abc', 'Table', 2, 3, 5.00, 1.00, 2.00, 7, 9, 'Great!', 'Aeron Paul Daliva', 'Day Shift', 's001'),
(3, '2024-08-30', 'SEM 982', 'shm123', 'Desk', 43, 12, 93.00, 23.00, 53.00, 4, 5, 'it was good as well.', 'Arianne Cagsawa', 'Day Shift', 'soe594'),
(4, '2024-08-30', 'SOT 564', 'dgs123', 'Organizer', 344, 32, 23.00, 43.00, 11.00, 4, 21, 'There is an error on production.', 'Aeron Paul Daliva', 'Day Shift', '123asd'),
(5, '2024-08-30', 'RTC 543', 'h3bhb5j4', 'Cabinet', 23, 2, 1.00, 1.00, 1.00, 1, 1, 'it had some errors.', 'Aeron Paul Daliva', 'Night Shift', 'igk45kmm'),
(6, '2024-08-30', 'PER 123', 'asdm123', 'Chair', 65, 12, 32.00, 12.00, 24.00, 8, 12, 'had some errors as well', 'Ella', 'Night Shift', 'ser342'),
(7, '2024-08-30', 'PER 123', 'asdm123', 'Chair', 65, 12, 32.00, 12.00, 24.00, 8, 12, 'adsadsadadsa', 'Ella', 'Night Shift', 'ser342'),
(8, '2024-08-31', 'MKS 970', 'ago60g', 'Table', 2, 4, 12.00, 94.00, 4.00, 2, 8, 'no issues.', 'Juan Dela Cruz', 'Day Shift', 'i2i4i3i3'),
(9, '2024-09-02', 'SUC 123', 'aem456', 'Table', 4, 5, 3.00, 4.00, 1.00, 2, 3, 'job well done!', 'Aeron Paul Daliva', 'Day Shift', 'aiskf342'),
(10, '2024-09-02', 'MRS 123', 'asdf123', 'Chair', 45, 34, 2.00, 1.00, 3.00, 4, 5, 'great', 'Aeron Paul Daliva', 'Night Shift', 'asdh432'),
(11, '2024-09-02', 'SEC 324', 'asd435', 'Chair', 34, 35, 23.00, 24.00, 25.00, 3, 4, 'awesome', 'Aeron Paul Daliva', 'Night Shift', 'yui234');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `submissions`
--
ALTER TABLE `submissions`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `submissions`
--
ALTER TABLE `submissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
