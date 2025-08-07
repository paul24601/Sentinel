-- phpMyAdmin SQL Dump
-- version 5.2.1deb3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Aug 06, 2025 at 03:45 PM
-- Server version: 10.11.13-MariaDB-0ubuntu0.24.04.1
-- PHP Version: 8.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `injectionmoldingparameters`
--

-- --------------------------------------------------------

--
-- Table structure for table `additionalinformation`
--

CREATE TABLE `additionalinformation` (
  `id` int(11) NOT NULL,
  `record_id` varchar(20) DEFAULT NULL,
  `Info` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `additionalinformation`
--

INSERT INTO `additionalinformation` (`id`, `record_id`, `Info`) VALUES
(13, 'PARAM_20250522_988df', 'Production run completed with standard parameters. All quality checks passed. Material batch: MB-3604'),
(20, 'PARAM_20250718_1333e', ''),
(21, 'PARAM_20250718_210a5', ''),
(22, 'PARAM_20250718_dd40b', 'W/ HPP'),
(24, 'PARAM_20250718_9ec7c', 'F'),
(25, 'PARAM_20250718_18709', ''),
(26, 'PARAM_20250718_634d7', ''),
(27, 'PARAM_20250718_1fe34', '2 GATES ONLY DUE TO GAS TRAP.'),
(28, 'PARAM_20250721_15806', 'AIR BLOW CONNECTED TO THE CORE SEQUENCE \r\nAn air line is connected to the Mold cavity; it blows air due to the mold open.\r\nCore mold \r\n1 1 - 1 3.0 7 - 1 2.0\r\n'),
(29, 'PARAM_20250721_73997', 'AIR BLOW CONNECTED TO THE CORE SEQUENCE \r\nAn air line is connected to the Mold cavity; it blows air due to the mold open.\r\nCore mold \r\n1 1 - 1 3.0 7 - 1 2.0\r\n'),
(30, 'PARAM_20250731_9b44f', 'Production run completed with standard parameters. All quality checks passed. Material batch: MB-1198'),
(31, 'PARAM_20250731_a2a58', 'Production run completed with standard parameters. All quality checks passed. Material batch: MB-7349'),
(32, 'PARAM_20250731_3be2b', 'Production run completed with standard parameters. All quality checks passed. Material batch: MB-3830'),
(33, 'PARAM_20250731_da614', 'Production run completed with standard parameters. All quality checks passed. Material batch: MB-2188'),
(34, 'PARAM_20250731_ea370', 'Production run completed with standard parameters. All quality checks passed. Material batch: MB-5876'),
(35, 'PARAM_20250731_bf47c', 'Production run completed with standard parameters. All quality checks passed. Material batch: MB-1358'),
(36, 'PARAM_20250731_9d7d3', 'Production run completed with standard parameters. All quality checks passed. Material batch: MB-6179'),
(37, 'PARAM_20250731_50626', 'Production run completed with standard parameters. All quality checks passed. Material batch: MB-6908'),
(38, 'PARAM_20250806_0bf3f', 'Production run completed with standard parameters. All quality checks passed. Material batch: MB-6908'),
(39, 'PARAM_20250806_b3e70', 'Production run completed with standard parameters. All quality checks passed. Material batch: MB-9961'),
(40, 'PARAM_20250806_89709', 'Production run completed with standard parameters. All quality checks passed. Material batch: MB-9961'),
(41, 'PARAM_20250806_6a12f', 'Production run completed with standard parameters. All quality checks passed. Material batch: MB-7740');

-- --------------------------------------------------------

--
-- Table structure for table `attachments`
--

CREATE TABLE `attachments` (
  `id` int(11) NOT NULL,
  `record_id` varchar(20) DEFAULT NULL,
  `FileName` varchar(255) NOT NULL,
  `FilePath` varchar(255) NOT NULL,
  `FileType` varchar(100) NOT NULL,
  `UploadedAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attachments`
--

INSERT INTO `attachments` (`id`, `record_id`, `FileName`, `FilePath`, `FileType`, `UploadedAt`) VALUES
(29, 'PARAM_20250522_988df', 'IMG_0711.jpeg', 'parameters/uploads/IRN64463_20250522_1436_CLF750B_RN1660_682ec60b995f0.jpeg', 'image/jpeg', '2025-05-22 06:36:59'),
(38, 'PARAM_20250718_dd40b', '1000001234.jpg', 'parameters/uploads/_20250718_1147_CLF750B__6879c43ade189.jpg', 'image/jpeg', '2025-07-18 03:49:14'),
(39, 'PARAM_20250718_dd40b', '1000001233.jpg', 'parameters/uploads/_20250718_1147_CLF750B__6879c43adf604.jpg', 'image/jpeg', '2025-07-18 03:49:14'),
(40, 'PARAM_20250718_9ec7c', '1000001237.jpg', 'parameters/uploads/_20250718_1255_SUM350__6879d3ffa0b45.jpg', 'image/jpeg', '2025-07-18 04:56:31'),
(41, 'PARAM_20250718_18709', '1000001238.jpg', 'parameters/uploads/_20250718_1328_MIT1050B__6879db8f18f69.jpg', 'image/jpeg', '2025-07-18 05:28:47'),
(42, 'PARAM_20250806_6a12f', 'IMG_20241004_113233_133.jpg', 'parameters/uploads/IRN35119_20250806_2339_ARB50_RN4741_689377956e7e4.jpg', 'image/jpeg', '2025-08-06 15:41:09'),
(43, 'PARAM_20250806_6a12f', 'Parameters - Data Entry - Brave 2025-07-26 08-19-27.mp4', 'parameters/uploads/IRN35119_20250806_2339_ARB50_RN4741_689377957056a.mp4', 'video/mp4', '2025-08-06 15:41:09');

-- --------------------------------------------------------

--
-- Table structure for table `barrelheatertemperatures`
--

CREATE TABLE `barrelheatertemperatures` (
  `id` int(11) NOT NULL,
  `record_id` varchar(20) DEFAULT NULL,
  `Zone0` float DEFAULT NULL,
  `Zone1` float DEFAULT NULL,
  `Zone2` float DEFAULT NULL,
  `Zone3` float DEFAULT NULL,
  `Zone4` float DEFAULT NULL,
  `Zone5` float DEFAULT NULL,
  `Zone6` float DEFAULT NULL,
  `Zone7` float DEFAULT NULL,
  `Zone8` float DEFAULT NULL,
  `Zone9` float DEFAULT NULL,
  `Zone10` float DEFAULT NULL,
  `Zone11` float DEFAULT NULL,
  `Zone12` float DEFAULT NULL,
  `Zone13` float DEFAULT NULL,
  `Zone14` float DEFAULT NULL,
  `Zone15` float DEFAULT NULL,
  `Zone16` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `barrelheatertemperatures`
--

INSERT INTO `barrelheatertemperatures` (`id`, `record_id`, `Zone0`, `Zone1`, `Zone2`, `Zone3`, `Zone4`, `Zone5`, `Zone6`, `Zone7`, `Zone8`, `Zone9`, `Zone10`, `Zone11`, `Zone12`, `Zone13`, `Zone14`, `Zone15`, `Zone16`) VALUES
(18, 'PARAM_20250522_988df', 106.6, 121.1, 85.6, 238.9, 237.1, 70.4, 130.4, 190.1, 128.5, 144.8, 131.8, 43.8, 182.2, 248.2, 211.2, 61.7, 198.4),
(33, 'PARAM_20250718_1333e', 220, 220, 210, 200, 200, 200, 200, 200, 190, 190, 0, 0, 0, 0, 0, 0, 0),
(34, 'PARAM_20250718_210a5', 230, 230, 220, 220, 220, 220, 210, 210, 210, 200, 200, 190, 0, 0, 0, 0, 0),
(35, 'PARAM_20250718_dd40b', 230, 230, 220, 220, 220, 220, 210, 210, 210, 200, 200, 190, 0, 0, 0, 0, 0),
(36, 'PARAM_20250718_c09f9', 360, 320, 300, 240, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(38, 'PARAM_20250718_9ec7c', 360, 320, 300, 240, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(39, 'PARAM_20250718_18709', 250, 250, 240, 230, 200, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(40, 'PARAM_20250718_634d7', 235, 230, 220, 210, 200, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(41, 'PARAM_20250718_1fe34', 220, 210, 210, 210, 180, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(42, 'PARAM_20250721_15806', 240, 220, 210, 200, 200, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(43, 'PARAM_20250721_73997', 240, 220, 210, 200, 200, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(44, 'PARAM_20250731_9b44f', 96.4, 192.6, 62.9, 245.7, 127.7, 114, 210.9, 182, 53.9, 200.1, 201.9, 179.8, 149.2, 91, 227.8, 166, 238.6),
(45, 'PARAM_20250731_a2a58', 197.8, 48.7, 225.2, 128.7, 85.8, 230.1, 225.4, 109.2, 119.8, 81.8, 33.2, 149.6, 165.9, 137.5, 224.9, 209.4, 40.4),
(46, 'PARAM_20250731_9f4a2', 143, 90.7, 229.2, 206.4, 36.5, 186.4, 62.2, 91.7, 142.9, 190.8, 198.1, 76, 97.4, 103.8, 109.3, 39.5, 123.7),
(47, 'PARAM_20250731_efacc', 143, 90.7, 229.2, 206.4, 36.5, 186.4, 62.2, 91.7, 142.9, 190.8, 198.1, 76, 97.4, 103.8, 109.3, 39.5, 123.7),
(48, 'PARAM_20250731_7144f', 143, 90.7, 229.2, 206.4, 36.5, 186.4, 62.2, 91.7, 142.9, 190.8, 198.1, 76, 97.4, 103.8, 109.3, 39.5, 123.7),
(49, 'PARAM_20250731_3be2b', 143, 90.7, 229.2, 206.4, 36.5, 186.4, 62.2, 91.7, 142.9, 190.8, 198.1, 76, 97.4, 103.8, 109.3, 39.5, 123.7),
(50, 'PARAM_20250731_da614', 210.7, 193.6, 105.1, 114, 113.7, 157.2, 60.1, 47.6, 55.9, 128.9, 62.5, 152.2, 119.9, 219.3, 86.3, 211.3, 182),
(51, 'PARAM_20250731_ea370', 96, 206, 230.7, 69, 125.8, 228.2, 93.7, 46.8, 41.6, 219.6, 39.4, 92.4, 60.5, 100.8, 191.6, 130, 60.5),
(52, 'PARAM_20250731_bf47c', 204.4, 61.9, 216.3, 70.5, 44.4, 175.4, 210.3, 97.9, 151.6, 43.2, 214.4, 247, 44.1, 113.2, 133.6, 119.9, 44.1),
(53, 'PARAM_20250731_9d7d3', 246.5, 159.3, 231.1, 71.1, 91.1, 177.8, 42.5, 34.5, 31.9, 135.6, 126.6, 39.5, 165.7, 77.2, 231.5, 139.7, 88.4),
(54, 'PARAM_20250731_50626', 194.3, 174.9, 246.5, 48.4, 70.9, 91.3, 135.9, 190.3, 119, 248.8, 68.8, 68.5, 182.1, 192.1, 226.5, 177, 90),
(55, 'PARAM_20250806_0bf3f', 194.3, 131.5, 31.9, 186.2, 155.5, 203.7, 187, 115.6, 220.4, 72.5, 120.7, 85.7, 202.6, 34.7, 217.5, 70.4, 150),
(56, 'PARAM_20250806_b3e70', 129.9, 249, 144.1, 143.1, 225.6, 105.6, 224, 172.7, 150.7, 71.3, 72.6, 39.2, 37.2, 159.2, 107.3, 101.8, 63),
(57, 'PARAM_20250806_89709', 129.9, 192.1, 34.6, 175.4, 145.3, 126.9, 192.6, 219.2, 166.4, 151.3, 79.4, 171.9, 202.1, 33.9, 34.8, 200.2, 104.1),
(58, 'PARAM_20250806_6a12f', 56, 206.7, 66.6, 246, 88.9, 214, 162.2, 72, 60.7, 180.1, 60.1, 187, 241.5, 207.7, 106.8, 129, 220.5);

-- --------------------------------------------------------

--
-- Table structure for table `colorantdetails`
--

CREATE TABLE `colorantdetails` (
  `id` int(11) NOT NULL,
  `record_id` varchar(20) DEFAULT NULL,
  `Colorant` varchar(255) DEFAULT NULL,
  `Color` varchar(255) DEFAULT NULL,
  `Dosage` varchar(255) DEFAULT NULL,
  `Stabilizer` varchar(255) DEFAULT NULL,
  `StabilizerDosage` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `colorantdetails`
--

INSERT INTO `colorantdetails` (`id`, `record_id`, `Colorant`, `Color`, `Dosage`, `Stabilizer`, `StabilizerDosage`) VALUES
(18, 'PARAM_20250522_988df', 'Blue', 'Clear', 'White', 'White', 'Blue'),
(33, 'PARAM_20250718_1333e', 'MBW 5937', 'BLUE', '375', 'UV-STAB', '62.5'),
(34, 'PARAM_20250718_210a5', 'MB W5937', 'BLUE', '375', 'UV STAB', '62.5'),
(35, 'PARAM_20250718_dd40b', 'MB W5937', 'BLUE', '375', 'UV STAB', '62.5'),
(36, 'PARAM_20250718_c09f9', '', '', '', '', ''),
(38, 'PARAM_20250718_9ec7c', '', '', '', '', ''),
(39, 'PARAM_20250718_18709', 'MB W5337', 'BLUE', '375', 'UV STAB', '62.5'),
(40, 'PARAM_20250718_634d7', 'MB W5937', 'BLUE', '375', 'UV STAB E7268', '62.5'),
(41, 'PARAM_20250718_1fe34', 'BLUE MB W5937', 'BLUE', '375', 'UV STAB E7268', '62.5'),
(42, 'PARAM_20250721_15806', 'MB AF9548', 'GRAY', '500', '', ''),
(43, 'PARAM_20250721_73997', 'MB AF9548', 'GRAY', '500', '', ''),
(44, 'PARAM_20250731_9b44f', 'Blue', 'Gray', 'Red', 'Green', 'Black'),
(45, 'PARAM_20250731_a2a58', 'Green', 'Clear', 'Yellow', 'Blue', 'White'),
(46, 'PARAM_20250731_9f4a2', 'Blue', 'Yellow', 'Black', 'Gray', 'White'),
(47, 'PARAM_20250731_efacc', 'Blue', 'Yellow', 'Black', 'Gray', 'White'),
(48, 'PARAM_20250731_7144f', 'Blue', 'Yellow', 'Black', 'Gray', 'White'),
(49, 'PARAM_20250731_3be2b', 'Blue', 'Yellow', 'Black', 'Gray', 'White'),
(50, 'PARAM_20250731_da614', 'Red', 'Blue', 'Green', 'Clear', 'Green'),
(51, 'PARAM_20250731_ea370', 'White', 'Green', 'Blue', 'Blue', 'Yellow'),
(52, 'PARAM_20250731_bf47c', 'Gray', 'Clear', 'White', 'Yellow', 'White'),
(53, 'PARAM_20250731_9d7d3', 'Clear', 'Green', 'Red', 'Black', 'Green'),
(54, 'PARAM_20250731_50626', 'Clear', 'White', 'Red', 'White', 'Clear'),
(55, 'PARAM_20250806_0bf3f', 'Clear', 'White', 'Red', 'White', 'Clear'),
(56, 'PARAM_20250806_b3e70', 'Clear', 'Blue', 'Gray', 'Clear', 'Gray'),
(57, 'PARAM_20250806_89709', 'Clear', 'Blue', 'Gray', 'Clear', 'Gray'),
(58, 'PARAM_20250806_6a12f', 'Clear', 'Green', 'Blue', 'Yellow', 'Black');

-- --------------------------------------------------------

--
-- Table structure for table `corepullsettings`
--

CREATE TABLE `corepullsettings` (
  `id` int(11) NOT NULL,
  `record_id` varchar(20) DEFAULT NULL,
  `Section` varchar(255) DEFAULT NULL,
  `Sequence` int(11) DEFAULT NULL,
  `Pressure` float DEFAULT NULL,
  `Speed` float DEFAULT NULL,
  `Position` float DEFAULT NULL,
  `Time` float DEFAULT NULL,
  `LimitSwitch` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `corepullsettings`
--

INSERT INTO `corepullsettings` (`id`, `record_id`, `Section`, `Sequence`, `Pressure`, `Speed`, `Position`, `Time`, `LimitSwitch`) VALUES
(43, 'PARAM_20250522_988df', 'Core Set A', 65, 163.4, 20.3, 30.41, 13.1, '43.25'),
(44, 'PARAM_20250522_988df', 'Core Pull A', 43, 19.4, 66.2, 17.43, 19.9, '95.49'),
(45, 'PARAM_20250522_988df', 'Core Set B', 50, 59.2, 39.3, 64.03, 3.9, '33.33'),
(46, 'PARAM_20250522_988df', 'Core Pull B', 22, 179.6, 31.6, 10.13, 21.2, '4.37'),
(84, 'PARAM_20250718_1333e', 'Core Set A', 1, 50, 15, 700, 3, ''),
(85, 'PARAM_20250718_1333e', 'Core Pull A', 1, 98, 9, 700, 3, ''),
(86, 'PARAM_20250718_dd40b', 'Core Set A', 0, 65, 35, 620, 6, ''),
(87, 'PARAM_20250718_dd40b', 'Core Pull A', 1, 85, 70, 600, 4, ''),
(88, 'PARAM_20250731_9b44f', 'Core Set A', 89, 148.3, 90.6, 31.61, 11.7, '23.37'),
(89, 'PARAM_20250731_9b44f', 'Core Pull A', 23, 73.7, 31.1, 42.3, 24.3, '47.75'),
(90, 'PARAM_20250731_9b44f', 'Core Set B', 55, 180.3, 67.8, 141.49, 26.8, '72.07'),
(91, 'PARAM_20250731_9b44f', 'Core Pull B', 84, 128.4, 88.3, 14.21, 18.6, '50.88'),
(92, 'PARAM_20250731_a2a58', 'Core Set A', 72, 198.4, 98.9, 80.52, 22.5, '16.79'),
(93, 'PARAM_20250731_a2a58', 'Core Pull A', 69, 24.4, 70.3, 25.99, 10.2, '10.47'),
(94, 'PARAM_20250731_a2a58', 'Core Set B', 8, 44, 65, 60.23, 16.5, '23.98'),
(95, 'PARAM_20250731_a2a58', 'Core Pull B', 77, 151.1, 17.7, 10.95, 5.7, '12.93'),
(96, 'PARAM_20250731_3be2b', 'Core Set A', 26, 161, 21.9, 131.52, 20.1, '86.56'),
(97, 'PARAM_20250731_3be2b', 'Core Pull A', 79, 126.6, 13.3, 96.25, 29, '83.83'),
(98, 'PARAM_20250731_3be2b', 'Core Set B', 16, 158.2, 16.2, 77.9, 3.5, '83.96'),
(99, 'PARAM_20250731_3be2b', 'Core Pull B', 12, 105.9, 43.6, 114.75, 7.4, '17.24'),
(100, 'PARAM_20250731_da614', 'Core Set A', 24, 14.6, 72.3, 74.91, 28.1, '99.59'),
(101, 'PARAM_20250731_da614', 'Core Pull A', 37, 175.3, 82.8, 62.73, 6.9, '48.32'),
(102, 'PARAM_20250731_da614', 'Core Set B', 1, 14.3, 84.5, 95.25, 26.4, '85.81'),
(103, 'PARAM_20250731_da614', 'Core Pull B', 4, 191.2, 67.7, 47.18, 19.4, '80.88'),
(104, 'PARAM_20250731_ea370', 'Core Set A', 46, 94.2, 78.4, 61.93, 19.7, '32.20'),
(105, 'PARAM_20250731_ea370', 'Core Pull A', 82, 19.3, 38.8, 22.89, 28.8, '36.70'),
(106, 'PARAM_20250731_ea370', 'Core Set B', 81, 27.7, 92.1, 22.44, 14.2, '10.50'),
(107, 'PARAM_20250731_ea370', 'Core Pull B', 57, 148.2, 83.5, 34.45, 11.7, '19.84'),
(108, 'PARAM_20250731_bf47c', 'Core Set A', 42, 157.7, 25.4, 62.72, 8, '22.86'),
(109, 'PARAM_20250731_bf47c', 'Core Pull A', 14, 14.5, 23.3, 139.65, 4.3, '1.63'),
(110, 'PARAM_20250731_bf47c', 'Core Set B', 36, 172.1, 46.9, 84.3, 15.9, '52.87'),
(111, 'PARAM_20250731_bf47c', 'Core Pull B', 11, 155.2, 97.7, 74.73, 13.6, '64.35'),
(112, 'PARAM_20250731_9d7d3', 'Core Set A', 96, 35.4, 79.1, 86.24, 15.1, '11.18'),
(113, 'PARAM_20250731_9d7d3', 'Core Pull A', 42, 143.4, 18.3, 129.35, 14.4, '79.69'),
(114, 'PARAM_20250731_9d7d3', 'Core Set B', 68, 12.3, 94.4, 12.81, 9.4, '88.52'),
(115, 'PARAM_20250731_9d7d3', 'Core Pull B', 12, 191, 29.4, 129.02, 27, '7.35'),
(116, 'PARAM_20250731_50626', 'Core Set A', 16, 171.5, 97.7, 107.69, 15.5, '85.19'),
(117, 'PARAM_20250731_50626', 'Core Pull A', 90, 35.7, 26.1, 130.98, 0.5, '26.02'),
(118, 'PARAM_20250731_50626', 'Core Set B', 46, 158.3, 77, 97.85, 15.3, '82.68'),
(119, 'PARAM_20250731_50626', 'Core Pull B', 30, 199.2, 95, 55.14, 5.3, '65.68'),
(120, 'PARAM_20250806_b3e70', 'Core Set A', 22, 46.1, 30.4, 57.79, 19.1, '30.94'),
(121, 'PARAM_20250806_b3e70', 'Core Pull A', 17, 97.6, 53.6, 47.66, 27.7, '2.80'),
(122, 'PARAM_20250806_b3e70', 'Core Set B', 68, 56.5, 64, 134.43, 10, '69.79'),
(123, 'PARAM_20250806_b3e70', 'Core Pull B', 15, 57.5, 88.4, 99.51, 13.6, '3.13'),
(124, 'PARAM_20250806_6a12f', 'Core Set A', 59, 46.8, 80.2, 79.43, 21.4, '26.82'),
(125, 'PARAM_20250806_6a12f', 'Core Pull A', 76, 113.9, 31.1, 128.61, 3.5, '53.83'),
(126, 'PARAM_20250806_6a12f', 'Core Set B', 80, 145, 81.3, 78.75, 10.7, '42.08'),
(127, 'PARAM_20250806_6a12f', 'Core Pull B', 54, 168.7, 89, 43.29, 22.2, '66.08');

-- --------------------------------------------------------

--
-- Table structure for table `ejectionparameters`
--

CREATE TABLE `ejectionparameters` (
  `id` int(11) NOT NULL,
  `record_id` varchar(20) DEFAULT NULL,
  `AirBlowTimeA` float DEFAULT NULL,
  `AirBlowPositionA` float DEFAULT NULL,
  `AirBlowADelay` float DEFAULT NULL,
  `AirBlowTimeB` float DEFAULT NULL,
  `AirBlowPositionB` float DEFAULT NULL,
  `AirBlowBDelay` float DEFAULT NULL,
  `EjectorForwardPosition1` float DEFAULT NULL,
  `EjectorForwardPosition2` float DEFAULT NULL,
  `EjectorForwardSpeed1` float DEFAULT NULL,
  `EjectorRetractPosition1` float DEFAULT NULL,
  `EjectorRetractPosition2` float DEFAULT NULL,
  `EjectorRetractSpeed1` float DEFAULT NULL,
  `EjectorForwardSpeed2` float DEFAULT NULL,
  `EjectorForwardPressure1` float DEFAULT NULL,
  `EjectorRetractSpeed2` float DEFAULT NULL,
  `EjectorRetractPressure1` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ejectionparameters`
--

INSERT INTO `ejectionparameters` (`id`, `record_id`, `AirBlowTimeA`, `AirBlowPositionA`, `AirBlowADelay`, `AirBlowTimeB`, `AirBlowPositionB`, `AirBlowBDelay`, `EjectorForwardPosition1`, `EjectorForwardPosition2`, `EjectorForwardSpeed1`, `EjectorRetractPosition1`, `EjectorRetractPosition2`, `EjectorRetractSpeed1`, `EjectorForwardSpeed2`, `EjectorForwardPressure1`, `EjectorRetractSpeed2`, `EjectorRetractPressure1`) VALUES
(13, 'PARAM_20250522_988df', 16.6, 15.78, 74.34, 8, 112.89, 70.43, 70.88, 147.32, 38.7, 137.19, 57.83, 63.1, 49.9, 22.6, 66.2, 15),
(28, 'PARAM_20250718_1333e', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(29, 'PARAM_20250718_210a5', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(30, 'PARAM_20250718_dd40b', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(32, 'PARAM_20250718_9ec7c', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(33, 'PARAM_20250718_18709', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(34, 'PARAM_20250718_634d7', 0, 0, 0, 0, 0, 0, 30, 0, 0, 65, 0, 0, 0, 0, 0, 0),
(35, 'PARAM_20250718_1fe34', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(36, 'PARAM_20250721_15806', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(37, 'PARAM_20250721_73997', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(38, 'PARAM_20250731_9b44f', 7.6, 126.04, 31.34, 22.1, 137.5, 38.78, 146.17, 101.72, 59.5, 16.99, 45.51, 12.1, 32.9, 11.2, 17.6, 102.1),
(39, 'PARAM_20250731_a2a58', 16.1, 149.01, 21.25, 22, 142.39, 92.7, 10.04, 81.73, 65, 70.25, 113.52, 63.2, 30, 19.9, 68.3, 187.5),
(40, 'PARAM_20250731_3be2b', 23.3, 134.37, 61.61, 18.3, 13.82, 80.77, 74.15, 57.69, 88.6, 103.62, 132.19, 81, 65.8, 11.3, 92.4, 162.7),
(41, 'PARAM_20250731_da614', 20.2, 123.07, 70.77, 19.9, 31.94, 82.88, 86.45, 37, 34.1, 97.18, 49.01, 17.8, 71.5, 183.7, 46.1, 45.6),
(42, 'PARAM_20250731_ea370', 24.1, 113.37, 65.78, 11, 125.1, 61.84, 37.44, 58.43, 27.3, 90.04, 64.98, 54.2, 70.5, 90, 12, 68.3),
(43, 'PARAM_20250731_bf47c', 28.9, 127.57, 78.67, 5, 105.01, 59.97, 46.82, 78.78, 90.2, 84.24, 26.94, 91.7, 44.9, 134.2, 29.9, 160.4),
(44, 'PARAM_20250731_9d7d3', 13.4, 115.28, 86.87, 22.6, 32.03, 47.45, 130.37, 113.19, 79.9, 44.48, 140.63, 52.9, 90, 129.9, 49.8, 198.4),
(45, 'PARAM_20250731_50626', 25.7, 48.66, 36.08, 20.1, 103.12, 26.68, 20.66, 117.47, 86.3, 20.3, 142.28, 61.2, 91.4, 44.1, 50, 182.9),
(46, 'PARAM_20250806_0bf3f', 25.7, 48.66, 36.08, 20.1, 103.12, 26.68, 20.66, 117.47, 86.3, 20.3, 142.28, 61.2, 0, 44.1, 0, 182.9),
(47, 'PARAM_20250806_b3e70', 8.9, 12.58, 49.88, 12.9, 111.53, 7.38, 41.26, 53.96, 85.7, 107.47, 140.83, 33.3, 89, 45.4, 52.8, 186.8),
(48, 'PARAM_20250806_89709', 8.9, 12.58, 49.88, 12.9, 111.53, 7.38, 41.26, 53.96, 85.7, 107.47, 140.83, 33.3, 0, 45.4, 0, 186.8),
(49, 'PARAM_20250806_6a12f', 28.5, 32.64, 31.51, 20, 66.98, 67.81, 68.01, 49.62, 72.6, 106.79, 51.86, 41.8, 32.9, 90.4, 92.9, 98.8);

-- --------------------------------------------------------

--
-- Table structure for table `injectionparameters`
--

CREATE TABLE `injectionparameters` (
  `id` int(11) NOT NULL,
  `record_id` varchar(20) DEFAULT NULL,
  `RecoveryPosition` float DEFAULT NULL,
  `SecondStagePosition` float DEFAULT NULL,
  `Cushion` float DEFAULT NULL,
  `ScrewPosition1` float DEFAULT NULL,
  `ScrewPosition2` float DEFAULT NULL,
  `ScrewPosition3` float DEFAULT NULL,
  `InjectionSpeed1` float DEFAULT NULL,
  `InjectionSpeed2` float DEFAULT NULL,
  `InjectionSpeed3` float DEFAULT NULL,
  `InjectionPressure1` float DEFAULT NULL,
  `InjectionPressure2` float DEFAULT NULL,
  `InjectionPressure3` float DEFAULT NULL,
  `SuckBackPosition` float DEFAULT NULL,
  `SuckBackSpeed` float DEFAULT NULL,
  `SuckBackPressure` float DEFAULT NULL,
  `SprueBreak` float DEFAULT NULL,
  `SprueBreakTime` float DEFAULT NULL,
  `InjectionDelay` float DEFAULT NULL,
  `HoldingPressure1` float DEFAULT NULL,
  `HoldingPressure2` float DEFAULT NULL,
  `HoldingPressure3` float DEFAULT NULL,
  `HoldingSpeed1` float DEFAULT NULL,
  `HoldingSpeed2` float DEFAULT NULL,
  `HoldingSpeed3` float DEFAULT NULL,
  `HoldingTime1` float DEFAULT NULL,
  `HoldingTime2` float DEFAULT NULL,
  `HoldingTime3` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `injectionparameters`
--

INSERT INTO `injectionparameters` (`id`, `record_id`, `RecoveryPosition`, `SecondStagePosition`, `Cushion`, `ScrewPosition1`, `ScrewPosition2`, `ScrewPosition3`, `InjectionSpeed1`, `InjectionSpeed2`, `InjectionSpeed3`, `InjectionPressure1`, `InjectionPressure2`, `InjectionPressure3`, `SuckBackPosition`, `SuckBackSpeed`, `SuckBackPressure`, `SprueBreak`, `SprueBreakTime`, `InjectionDelay`, `HoldingPressure1`, `HoldingPressure2`, `HoldingPressure3`, `HoldingSpeed1`, `HoldingSpeed2`, `HoldingSpeed3`, `HoldingTime1`, `HoldingTime2`, `HoldingTime3`) VALUES
(14, 'PARAM_20250522_988df', 71.74, 23.68, 4.86, 122.39, 20.88, 14.38, 21.8, 26.5, 38.4, 152.2, 124, 185.9, 29.68, 97.1, 148.6, 21.14, 11.7, 89.38, 199.5, 55.7, 186.2, 44.9, 16.3, 94.4, 16.4, 1.8, 3.4),
(29, 'PARAM_20250718_1333e', 190, 7, 1, 150, 75, 30, 45, 45, 45, 42, 45, 50, 15, 0, 0, 0, 0, 0, 35, 35, 0, 0, 0, 0, 0, 0, 0),
(30, 'PARAM_20250718_210a5', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(31, 'PARAM_20250718_dd40b', 133, 5, 0, 110, 45, 20, 50, 60, 65, 60, 60, 60, 15, 10, 15, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(33, 'PARAM_20250718_9ec7c', 305090000, 5, 0.55, 115, 20, 20, 99, 99, 99, 75, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(34, 'PARAM_20250718_18709', 222, 15, 0.1, 222, 170, 40, 65, 75, 5, 65, 70, 60, 15, 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(35, 'PARAM_20250718_634d7', 35, 16, 1.9, 305, 259, 90, 53, 85, 60, 60, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 20, 0, 0),
(36, 'PARAM_20250718_1fe34', 230, 11, 10.1, 230, 208, 80, 65, 70, 70, 70, 70, 70, 15, 0, 0, 0, 0, 0, 50, 40, 30, 25, 25, 25, 2, 2, 1.96),
(37, 'PARAM_20250721_15806', 115, 2, 0, 115, 60, 30, 99, 99, 99, 99, 99, 99, 15, 1, 0, 0, 0, 0, 25, 0, 0, 0, 0, 0, 3, 0, 0),
(38, 'PARAM_20250721_73997', 115, 2, 0, 115, 60, 30, 99, 99, 99, 99, 99, 99, 15, 1, 0, 0, 0, 0, 25, 0, 0, 0, 0, 0, 3, 0, 0),
(39, 'PARAM_20250731_9b44f', 132.01, 78.52, 9.47, 116.38, 24.58, 44.98, 88.4, 23.4, 47.7, 89.7, 141.6, 182.9, 146.49, 41.6, 167.4, 67.13, 15.7, 20.25, 39.8, 29.6, 72.2, 59.1, 96.9, 19.8, 18.5, 1.7, 6.6),
(40, 'PARAM_20250731_a2a58', 37.51, 26.07, 5.53, 104.3, 108.73, 61.21, 70.7, 85.6, 68.1, 31.5, 130, 38.6, 122.34, 32.5, 87.1, 24.2, 5.8, 31.18, 97.5, 28.9, 134.1, 52.9, 17.8, 68.8, 13.2, 23.9, 0.5),
(41, 'PARAM_20250731_3be2b', 78.02, 67.86, 7.99, 74.74, 13.58, 24.94, 51.4, 76, 64.4, 104.8, 110.4, 174.8, 55.9, 37.3, 136.8, 42.63, 25.9, 43.2, 158.9, 126.6, 96.5, 69.8, 91.1, 46.1, 18, 19.3, 16.7),
(42, 'PARAM_20250731_da614', 89.86, 137.06, 7.56, 20.56, 120.84, 15.82, 53.3, 91.9, 74, 119.8, 150.5, 144.6, 33.27, 68.7, 35.4, 46.01, 28.7, 80.4, 184.7, 107.8, 110.6, 47.9, 53.5, 71.7, 22.4, 19, 27.7),
(43, 'PARAM_20250731_ea370', 72.79, 16.17, 9.64, 63.71, 27.64, 88.67, 30.8, 55.1, 96.8, 149.2, 176, 199.9, 49.17, 51.6, 90.4, 71.01, 3.2, 33.03, 103.8, 169.5, 163.1, 72.9, 29.6, 22.1, 20.6, 23.1, 29.8),
(44, 'PARAM_20250731_bf47c', 75.56, 58.53, 3.11, 46.91, 12.6, 66.54, 94.4, 53.3, 68.3, 25.6, 156.8, 46.4, 144.01, 74.7, 30.6, 2.86, 5, 47.72, 74.1, 194.6, 144.9, 89.8, 90.8, 65.5, 8.4, 0.7, 10),
(45, 'PARAM_20250731_9d7d3', 52.79, 143.93, 6.92, 14.61, 37.58, 20, 23.7, 79.6, 96.6, 183.8, 117.4, 161.4, 38.7, 31.7, 134.5, 93.94, 1.2, 60.29, 122.9, 145.8, 53.1, 45.6, 61.6, 69.9, 11.4, 29.8, 11.5),
(46, 'PARAM_20250731_50626', 120.72, 59.11, 5.83, 35.1, 145.49, 128.3, 80.8, 44.8, 28.4, 60.3, 30.5, 119.6, 98.09, 71.5, 169.4, 39.96, 29.9, 23.41, 194, 198.2, 146, 32.8, 55.4, 49.8, 20.2, 13.4, 23.1),
(47, 'PARAM_20250806_0bf3f', 120.72, 59.11, 5.83, 35.1, 145.49, 128.3, 80.8, 44.8, 28.4, 60.3, 30.5, 119.6, 98.09, 71.5, 169.4, 39.96, 29.9, 23.41, 194, 198.2, 146, 32.8, 55.4, 49.8, 20.2, 13.4, 23.1),
(48, 'PARAM_20250806_b3e70', 87.64, 109.43, 9.17, 55.22, 47.82, 13.69, 28, 57.8, 33.4, 58.2, 59.2, 25.6, 77.3, 94.8, 166.8, 23.55, 2.8, 6.64, 157.3, 98.6, 57.5, 39.1, 82.5, 57.2, 12.6, 19.6, 18.8),
(49, 'PARAM_20250806_89709', 87.64, 109.43, 9.17, 55.22, 47.82, 13.69, 28, 57.8, 33.4, 58.2, 59.2, 25.6, 77.3, 94.8, 166.8, 23.55, 2.8, 6.64, 157.3, 98.6, 57.5, 39.1, 82.5, 57.2, 12.6, 19.6, 18.8),
(50, 'PARAM_20250806_6a12f', 141.61, 24.65, 4.6, 35.74, 10.45, 107.14, 40.1, 14.6, 62.3, 41.6, 80.7, 178.7, 48.07, 83.4, 36.6, 26.73, 24.4, 60.63, 32.1, 128.9, 19.5, 55.3, 80.1, 89.5, 9.9, 5.3, 16.8);

-- --------------------------------------------------------

--
-- Table structure for table `materialcomposition`
--

CREATE TABLE `materialcomposition` (
  `id` int(11) NOT NULL,
  `record_id` varchar(20) DEFAULT NULL,
  `DryingTime` float DEFAULT NULL,
  `DryingTemperature` float DEFAULT NULL,
  `Material1_Type` varchar(255) DEFAULT NULL,
  `Material1_Brand` varchar(255) DEFAULT NULL,
  `Material1_MixturePercentage` float DEFAULT NULL,
  `Material2_Type` varchar(255) DEFAULT NULL,
  `Material2_Brand` varchar(255) DEFAULT NULL,
  `Material2_MixturePercentage` float DEFAULT NULL,
  `Material3_Type` varchar(255) DEFAULT NULL,
  `Material3_Brand` varchar(255) DEFAULT NULL,
  `Material3_MixturePercentage` float DEFAULT NULL,
  `Material4_Type` varchar(255) DEFAULT NULL,
  `Material4_Brand` varchar(255) DEFAULT NULL,
  `Material4_MixturePercentage` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `materialcomposition`
--

INSERT INTO `materialcomposition` (`id`, `record_id`, `DryingTime`, `DryingTemperature`, `Material1_Type`, `Material1_Brand`, `Material1_MixturePercentage`, `Material2_Type`, `Material2_Brand`, `Material2_MixturePercentage`, `Material3_Type`, `Material3_Brand`, `Material3_MixturePercentage`, `Material4_Type`, `Material4_Brand`, `Material4_MixturePercentage`) VALUES
(18, 'PARAM_20250522_988df', 25, 51.4, 'Sample-4004', 'Sample-7370', 39.74, 'Sample-6226', 'Sample-6542', 62.08, 'Sample-1844', 'Sample-9003', 35.15, 'Sample-4119', 'Sample-6264', 48.38),
(33, 'PARAM_20250718_1333e', 0, 0, 'PEHD', 'EVALENE 3601 NAT', 100, '', '', 0, '', '', 0, '', '', 0),
(34, 'PARAM_20250718_210a5', 0, 0, 'PEHD', 'EVALENE 8601 NAT', 70, 'PEHD', 'PEPSI BLUE G', 30, '', '', 0, '', '', 0),
(35, 'PARAM_20250718_dd40b', 0, 0, 'PEHD', 'EVALENE 8601 NAT', 70, 'PEHD', 'PEPSI BLUE G', 30, '', '', 0, '', '', 0),
(36, 'PARAM_20250718_c09f9', 6, 150, 'PET', 'NATURAL-G', 100, '', '', 0, '', '', 0, '', '', 0),
(38, 'PARAM_20250718_9ec7c', 6, 150, 'PET', 'NATURAL-G', 100, '', '', 0, '', '', 0, '', '', 0),
(39, 'PARAM_20250718_18709', 0, 0, 'PEHD', 'EVALENE 8601', 80, 'PEHD', 'PEPSI BLUE-G', 20, '', '', 0, '', '', 0),
(40, 'PARAM_20250718_634d7', 0, 0, 'PEHD', 'EVALENE 8601 NATURAL', 80, 'PEHD', 'PEPSI BLUE REGRIND', 20, '', '', 0, '', '', 0),
(41, 'PARAM_20250718_1fe34', 0, 0, 'PEHD', 'EVALENE 8601 NATURAL', 100, '', '', 0, '', '', 0, '', '', 0),
(42, 'PARAM_20250721_15806', 0, 0, 'SM340', 'PP TITANPRO', 100, 'MC-04_01', 'PELD', 10, '', '', 0, '', '', 0),
(43, 'PARAM_20250721_73997', 0, 0, 'SM340', 'PP TITANPRO', 100, 'MC-04_01', 'PELD', 10, '', '', 0, '', '', 0),
(44, 'PARAM_20250731_9b44f', 8.8, 69.1, 'Sample-3740', 'Sample-2587', 4.02, 'Sample-6278', 'Sample-1583', 41.36, 'Sample-5187', 'Sample-3885', 18.38, 'Sample-7328', 'Sample-8993', 20.52),
(45, 'PARAM_20250731_a2a58', 12.8, 211.1, 'Sample-3971', 'Sample-9396', 21.51, 'Sample-3959', 'Sample-3839', 61.38, 'Sample-9511', 'Sample-5286', 99.75, 'Sample-7913', 'Sample-3620', 23.47),
(46, 'PARAM_20250731_9f4a2', 9.9, 70.7, 'Sample-9855', 'Sample-8366', 83.83, 'Sample-2313', 'Sample-8158', 50.04, 'Sample-3395', 'Sample-5975', 51.96, 'Sample-7086', 'Sample-8678', 86.51),
(47, 'PARAM_20250731_efacc', 9.9, 70.7, 'Sample-9855', 'Sample-8366', 83.83, 'Sample-2313', 'Sample-8158', 50.04, 'Sample-3395', 'Sample-5975', 51.96, 'Sample-7086', 'Sample-8678', 86.51),
(48, 'PARAM_20250731_7144f', 9.9, 70.7, 'Sample-9855', 'Sample-8366', 83.83, 'Sample-2313', 'Sample-8158', 50.04, 'Sample-3395', 'Sample-5975', 51.96, 'Sample-7086', 'Sample-8678', 86.51),
(49, 'PARAM_20250731_3be2b', 9.9, 70.7, 'Sample-9855', 'Sample-8366', 83.83, 'Sample-2313', 'Sample-8158', 50.04, 'Sample-3395', 'Sample-5975', 51.96, 'Sample-7086', 'Sample-8678', 86.51),
(50, 'PARAM_20250731_da614', 27.7, 143.6, 'Sample-4507', 'Sample-7368', 44.74, 'Sample-3427', 'Sample-2150', 35.11, 'Sample-5319', 'Sample-7132', 60.37, 'Sample-1474', 'Sample-1028', 72.81),
(51, 'PARAM_20250731_ea370', 13.9, 105, 'Sample-6230', 'Sample-2512', 66.4, 'Sample-8135', 'Sample-2159', 80.23, 'Sample-8644', 'Sample-6350', 98.92, 'Sample-4451', 'Sample-5775', 59.85),
(52, 'PARAM_20250731_bf47c', 19.7, 140, 'Sample-8620', 'Sample-1036', 60.02, 'Sample-4608', 'Sample-2953', 2.29, 'Sample-9028', 'Sample-5823', 94.88, 'Sample-3095', 'Sample-9575', 8.8),
(53, 'PARAM_20250731_9d7d3', 10.4, 118.9, 'Sample-7843', 'Sample-2804', 35.24, 'Sample-4933', 'Sample-9483', 76.89, 'Sample-6423', 'Sample-1581', 7.37, 'Sample-3902', 'Sample-8123', 32.15),
(54, 'PARAM_20250731_50626', 26.1, 240.9, 'Sample-5769', 'Sample-2821', 67.05, 'Sample-8975', 'Sample-3078', 99.71, 'Sample-9235', 'Sample-5973', 97.21, 'Sample-1363', 'Sample-1501', 34.71),
(55, 'PARAM_20250806_0bf3f', 26.1, 240.9, 'Sample-5769', 'Sample-2821', 67.05, 'Sample-8975', 'Sample-3078', 99.71, 'Sample-9235', 'Sample-5973', 97.21, 'Sample-1363', 'Sample-1501', 34.71),
(56, 'PARAM_20250806_b3e70', 17.6, 131.6, 'Sample-4343', 'Sample-3536', 23.13, 'Sample-6267', 'Sample-2776', 85.89, 'Sample-9592', 'Sample-1891', 2.13, 'Sample-5223', 'Sample-5087', 72.34),
(57, 'PARAM_20250806_89709', 17.6, 131.6, 'Sample-4343', 'Sample-3536', 23.13, 'Sample-6267', 'Sample-2776', 85.89, 'Sample-9592', 'Sample-1891', 2.13, 'Sample-5223', 'Sample-5087', 72.34),
(58, 'PARAM_20250806_6a12f', 17.2, 159.6, 'Sample-4930', 'Sample-6933', 32.31, 'Sample-2040', 'Sample-7406', 73.48, 'Sample-5281', 'Sample-4829', 75.17, 'Sample-3377', 'Sample-2572', 84.53);

-- --------------------------------------------------------

--
-- Table structure for table `moldcloseparameters`
--

CREATE TABLE `moldcloseparameters` (
  `id` int(11) NOT NULL,
  `record_id` varchar(20) DEFAULT NULL COMMENT 'Links to parameter_records table',
  `MoldClosePos1` float DEFAULT NULL COMMENT 'Mold close position 1',
  `MoldClosePos2` float DEFAULT NULL COMMENT 'Mold close position 2',
  `MoldClosePos3` float DEFAULT NULL COMMENT 'Mold close position 3',
  `MoldClosePos4` float DEFAULT NULL COMMENT 'Mold close position 4',
  `MoldClosePos5` float DEFAULT NULL COMMENT 'Mold close position 5',
  `MoldClosePos6` float DEFAULT NULL COMMENT 'Mold close position 6',
  `MoldCloseSpd1` float DEFAULT NULL COMMENT 'Mold close speed 1',
  `MoldCloseSpd2` float DEFAULT NULL COMMENT 'Mold close speed 2',
  `MoldCloseSpd3` float DEFAULT NULL COMMENT 'Mold close speed 3',
  `MoldCloseSpd4` float DEFAULT NULL COMMENT 'Mold close speed 4',
  `MoldCloseSpd5` float DEFAULT NULL COMMENT 'Mold close speed 5',
  `MoldCloseSpd6` float DEFAULT NULL COMMENT 'Mold close speed 6',
  `MoldClosePressure1` float DEFAULT NULL COMMENT 'Mold close pressure 1',
  `MoldClosePressure2` float DEFAULT NULL COMMENT 'Mold close pressure 2',
  `MoldClosePressure3` float DEFAULT NULL COMMENT 'Mold close pressure 3',
  `MoldClosePressure4` float DEFAULT NULL COMMENT 'Mold close pressure 4 - NEW FIELD',
  `PCLORLP` varchar(255) DEFAULT NULL COMMENT 'PCL/LP parameter',
  `PCHORHP` varchar(255) DEFAULT NULL COMMENT 'PCH/HP parameter',
  `LowPresTimeLimit` float DEFAULT NULL COMMENT 'Low pressure time limit',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Mold close parameters including positions, speeds, and pressures';

--
-- Dumping data for table `moldcloseparameters`
--

INSERT INTO `moldcloseparameters` (`id`, `record_id`, `MoldClosePos1`, `MoldClosePos2`, `MoldClosePos3`, `MoldClosePos4`, `MoldClosePos5`, `MoldClosePos6`, `MoldCloseSpd1`, `MoldCloseSpd2`, `MoldCloseSpd3`, `MoldCloseSpd4`, `MoldCloseSpd5`, `MoldCloseSpd6`, `MoldClosePressure1`, `MoldClosePressure2`, `MoldClosePressure3`, `MoldClosePressure4`, `PCLORLP`, `PCHORHP`, `LowPresTimeLimit`, `created_at`, `updated_at`) VALUES
(1, 'PARAM_20250731_3be2b', 84.54, 50.49, 17.13, 35.82, 62.57, 6.52, 88.29, 8.32, 89.99, 22.37, 74.8, 17.45, 198.4, 132.4, 133.3, 121.1, 'Sample-8447', 'Sample-2253', 21.6, '2025-07-31 02:00:41', '2025-07-31 02:00:41'),
(2, 'PARAM_20250731_da614', 77.61, 41.3, 46.67, 86.33, 30.2, 55.96, 69.26, 47.62, 50.83, 7.17, 41.85, 42.72, 133.1, 183.5, 13.7, 41.2, 'Sample-8734', 'Sample-5254', 12.8, '2025-07-31 02:11:00', '2025-07-31 02:11:00'),
(3, 'PARAM_20250731_ea370', 69.82, 67.29, 35.92, 97.34, 74.51, 89.71, 1.17, 69.71, 77.09, 80.59, 46.39, 95.86, 192.9, 146.4, 14.8, 132.9, 'Sample-5239', 'Sample-4870', 7.4, '2025-07-31 03:17:44', '2025-07-31 03:17:44'),
(4, 'PARAM_20250731_bf47c', 62.92, 13.82, 39.27, 91.99, 94.8, 23.12, 9.06, 92.96, 15.3, 37.47, 90.54, 28.92, 87.3, 128.6, 67.6, 133.2, 'Sample-1657', 'Sample-2725', 9.9, '2025-07-31 03:24:10', '2025-07-31 03:24:10'),
(5, 'PARAM_20250731_9d7d3', 14.09, 41.75, 88.61, 15.06, 47.55, 33.64, 6.76, 35.44, 5.59, 20.62, 83.63, 81.22, 120.6, 14.2, 94.9, 117, 'Sample-3797', 'Sample-8144', 11.8, '2025-07-31 03:25:56', '2025-07-31 03:25:56'),
(6, 'PARAM_20250731_50626', 26.56, 2.09, 52.38, 18.3, 6.35, 59.21, 98.55, 12.51, 50.93, 27.73, 61.99, 61.21, 121.1, 85.2, 121.4, 143.5, 'Sample-2136', 'Sample-4132', 25.2, '2025-07-31 03:42:03', '2025-07-31 03:42:03'),
(7, 'PARAM_20250806_0bf3f', 26.56, 2.09, 52.38, 18.3, 6.35, 59.21, 98.55, 12.51, 50.93, 27.73, 61.99, 61.21, 121.1, 85.2, 121.4, 143.5, 'Sample-2136', 'Sample-4132', 25.2, '2025-08-06 01:16:58', '2025-08-06 01:16:58'),
(8, 'PARAM_20250806_b3e70', 71.08, 17.78, 72.56, 6.19, 68.5, 53.77, 58.8, 11.67, 92.03, 80.69, 10.07, 31.92, 17, 164.2, 148.7, 94.4, 'Sample-3997', 'Sample-8214', 10.1, '2025-08-06 15:34:06', '2025-08-06 15:34:06'),
(9, 'PARAM_20250806_89709', 71.08, 17.78, 72.56, 6.19, 68.5, 53.77, 58.8, 11.67, 92.03, 80.69, 10.07, 31.92, 17, 164.2, 148.7, 94.4, 'Sample-3997', 'Sample-8214', 10.1, '2025-08-06 15:36:57', '2025-08-06 15:36:57'),
(10, 'PARAM_20250806_6a12f', 33.5, 46.55, 34.9, 55.91, 55.52, 28.22, 22.73, 81.88, 86.27, 81.94, 84.28, 67.55, 147, 77.1, 57.4, 82.4, 'Sample-2039', 'Sample-3421', 24.9, '2025-08-06 15:41:09', '2025-08-06 15:41:09');

-- --------------------------------------------------------

--
-- Table structure for table `moldheatertemperatures`
--

CREATE TABLE `moldheatertemperatures` (
  `id` int(11) NOT NULL,
  `record_id` varchar(20) DEFAULT NULL,
  `Zone1` float DEFAULT NULL,
  `Zone2` float DEFAULT NULL,
  `Zone3` float DEFAULT NULL,
  `Zone4` float DEFAULT NULL,
  `Zone5` float DEFAULT NULL,
  `Zone6` float DEFAULT NULL,
  `Zone7` float DEFAULT NULL,
  `Zone8` float DEFAULT NULL,
  `Zone9` float DEFAULT NULL,
  `Zone10` float DEFAULT NULL,
  `Zone11` float DEFAULT NULL,
  `Zone12` float DEFAULT NULL,
  `Zone13` float DEFAULT NULL,
  `Zone14` float DEFAULT NULL,
  `Zone15` float DEFAULT NULL,
  `Zone16` float DEFAULT NULL,
  `MTCSetting` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `moldheatertemperatures`
--

INSERT INTO `moldheatertemperatures` (`id`, `record_id`, `Zone1`, `Zone2`, `Zone3`, `Zone4`, `Zone5`, `Zone6`, `Zone7`, `Zone8`, `Zone9`, `Zone10`, `Zone11`, `Zone12`, `Zone13`, `Zone14`, `Zone15`, `Zone16`, `MTCSetting`) VALUES
(1, 'PARAM_20240701_001', 45, 50, 45, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 10),
(2, 'PARAM_20240702_002', 40, 45, 40, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 8),
(3, 'PARAM_20240703_003', 55, 60, 55, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 12),
(9, 'PARAM_20250515_c0d50', 48.9, 166.6, 192.6, 208.9, 141.3, 105.6, 81.8, 230.9, 141.8, 196.9, 226.4, 112.1, 149.9, 227.2, 141.4, 241.7, 45.57),
(10, 'PARAM_20250517_3c0ae', 194.7, 92.1, 206.7, 244.9, 34.2, 131.4, 167.7, 64.1, 136.2, 123, 74, 187.8, 202, 198.5, 184.5, 105.1, 50.19),
(11, 'PARAM_20250517_a243f', 243.3, 217, 77.9, 113.4, 129.5, 65.4, 150, 229.2, 123.5, 156.5, 139.1, 223.5, 181.9, 184.3, 111.6, 36.5, 36.21),
(12, 'PARAM_20250517_3ac7b', 215.5, 72.1, 103.9, 76.5, 131.7, 98.8, 212.5, 206.7, 40.4, 147.6, 73.8, 221.2, 44.5, 89.9, 70.9, 35.2, 95.6),
(13, 'PARAM_20250517_42616', 237.9, 127.1, 83.8, 149.3, 171.4, 106.9, 46.9, 144.3, 186, 122.6, 45.2, 140.2, 51.8, 39.8, 234.5, 212.8, 12.21),
(14, 'PARAM_20250517_1d00a', 124.6, 182.3, 100.1, 86.7, 141.1, 41.9, 161.6, 237.8, 54.6, 49.6, 99.8, 111.1, 69.5, 75.3, 227.4, 218.8, 56.52),
(15, 'PARAM_20250517_7b02c', 161, 222, 171.5, 106.8, 124.1, 170.3, 166.2, 205.2, 114.6, 102.9, 96.5, 229.1, 185.1, 231.9, 112.3, 36.3, 62.82),
(16, 'PARAM_20250521_496e7', 142.4, 30.5, 161.4, 97, 244, 247.7, 244.3, 136.9, 208.4, 82.3, 206.7, 187.3, 249.2, 155, 242.6, 103.7, 84.2),
(17, 'PARAM_20250522_f3ab5', 89.4, 41.5, 37.9, 217.2, 115.8, 62.7, 171.6, 135.8, 51.3, 234.1, 226.4, 87.5, 233.9, 176.4, 169.9, 43.3, 58.55),
(18, 'PARAM_20250522_988df', 166.9, 173.5, 237.9, 242.2, 34.1, 159.6, 67.2, 231.3, 33.7, 180.4, 76, 129.9, 37.5, 174.2, 219.4, 240.2, 76.04),
(19, 'PARAM_20250708_c11f1', 136, 137, 168.6, 219.7, 207.4, 185.2, 179.5, 133.9, 143.1, 123.2, 221.7, 174.7, 229, 175.9, 189.3, 49.5, 85.45),
(20, 'PARAM_20250717_83756', 65.4, 124, 35.2, 107.7, 43.1, 53.6, 196.4, 205.9, 146.2, 164.5, 218.2, 121.5, 168.5, 200.2, 134.5, 62.6, 31.71),
(21, 'PARAM_20250717_cb661', 214, 99.4, 40.8, 51.5, 209.2, 133.4, 181.9, 89.7, 147.7, 165.9, 120, 199.4, 235, 88.5, 215.3, 134.7, 14),
(22, 'PARAM_20250717_9248f', 194.6, 202.4, 63.3, 31.1, 89.1, 120.8, 234.2, 131.8, 247.4, 104.4, 51.7, 180.6, 155.5, 236.2, 237, 57.3, 54.93),
(23, 'PARAM_20250717_a7d06', 37.7, 132.4, 95.3, 133.7, 187.3, 145, 237.5, 153.7, 205, 116.1, 140, 72.7, 235.1, 147, 162.9, 107.6, 29.62),
(24, 'PARAM_20250718_1ce2e', 170.6, 162.9, 218.4, 120.4, 113.2, 41.3, 122.8, 51.6, 123, 205, 244.5, 145.9, 38, 186.4, 99.9, 152, 12.08),
(33, 'PARAM_20250718_1333e', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(34, 'PARAM_20250718_210a5', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(35, 'PARAM_20250718_dd40b', 250, 230, 300, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(37, 'PARAM_20250718_9ec7c', 450, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(38, 'PARAM_20250718_18709', 210, 230, 280, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(39, 'PARAM_20250718_634d7', 255, 220, 250, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(40, 'PARAM_20250718_1fe34', 180, 170, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(41, 'PARAM_20250721_15806', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(42, 'PARAM_20250721_73997', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(43, 'PARAM_20250731_7144f', 92.4, 188.3, 180.8, 55.4, 59.2, 78.5, 228.8, 205.8, 80.2, 212.8, 112.5, 55.1, 59.8, 82.2, 87.4, 211.1, 26.16),
(44, 'PARAM_20250731_3be2b', 92.4, 188.3, 180.8, 55.4, 59.2, 78.5, 228.8, 205.8, 80.2, 212.8, 112.5, 55.1, 59.8, 82.2, 87.4, 211.1, 26.16),
(45, 'PARAM_20250731_da614', 189.9, 104.1, 167.3, 241.8, 67.9, 55.5, 109.4, 188.5, 242.3, 222.4, 199.2, 245.7, 183.6, 219, 80.5, 53.9, 28.89),
(46, 'PARAM_20250731_ea370', 76.8, 117, 137.2, 230.4, 37.6, 202.5, 194.8, 120.5, 188.7, 38.2, 184.7, 41.9, 38.8, 183.9, 199, 153.8, 92.72),
(47, 'PARAM_20250731_bf47c', 49, 159.1, 95.4, 38.9, 194.7, 175.5, 117.4, 37.3, 93.4, 105.5, 204.2, 40.6, 155.4, 161.7, 113.2, 124.1, 39.61),
(48, 'PARAM_20250731_9d7d3', 152.1, 155.4, 107.6, 84.9, 66.5, 209.1, 210.2, 200.9, 168.7, 146.9, 198.4, 56.9, 47.1, 84.9, 164.5, 129.8, 21.87),
(49, 'PARAM_20250731_50626', 131.5, 31.9, 186.2, 155.5, 203.7, 187, 115.6, 220.4, 72.5, 120.7, 85.7, 202.6, 34.7, 217.5, 70.4, 150, 31.23),
(50, 'PARAM_20250806_0bf3f', 131.5, 31.9, 186.2, 155.5, 203.7, 187, 115.6, 220.4, 72.5, 120.7, 85.7, 202.6, 34.7, 217.5, 70.4, 150, 31.23),
(51, 'PARAM_20250806_b3e70', 192.1, 34.6, 175.4, 145.3, 126.9, 192.6, 219.2, 166.4, 151.3, 79.4, 171.9, 202.1, 33.9, 34.8, 200.2, 104.1, 99.63),
(52, 'PARAM_20250806_89709', 192.1, 34.6, 175.4, 145.3, 126.9, 192.6, 219.2, 166.4, 151.3, 79.4, 171.9, 202.1, 33.9, 34.8, 200.2, 104.1, 99.63),
(53, 'PARAM_20250806_6a12f', 45.7, 53.2, 133.6, 189.3, 89.3, 226.4, 112.5, 82.5, 62.5, 223.2, 171.3, 155.1, 156.4, 246.5, 191.9, 144.7, 94.01);

-- --------------------------------------------------------

--
-- Table structure for table `moldopenparameters`
--

CREATE TABLE `moldopenparameters` (
  `id` int(11) NOT NULL,
  `record_id` varchar(20) DEFAULT NULL COMMENT 'Links to parameter_records table',
  `MoldOpenPos1` float DEFAULT NULL COMMENT 'Mold open position 1',
  `MoldOpenPos2` float DEFAULT NULL COMMENT 'Mold open position 2',
  `MoldOpenPos3` float DEFAULT NULL COMMENT 'Mold open position 3',
  `MoldOpenPos4` float DEFAULT NULL COMMENT 'Mold open position 4',
  `MoldOpenPos5` float DEFAULT NULL COMMENT 'Mold open position 5',
  `MoldOpenPos6` float DEFAULT NULL COMMENT 'Mold open position 6',
  `MoldOpenSpd1` float DEFAULT NULL COMMENT 'Mold open speed 1',
  `MoldOpenSpd2` float DEFAULT NULL COMMENT 'Mold open speed 2',
  `MoldOpenSpd3` float DEFAULT NULL COMMENT 'Mold open speed 3',
  `MoldOpenSpd4` float DEFAULT NULL COMMENT 'Mold open speed 4',
  `MoldOpenSpd5` float DEFAULT NULL COMMENT 'Mold open speed 5',
  `MoldOpenSpd6` float DEFAULT NULL COMMENT 'Mold open speed 6',
  `MoldOpenPressure1` float DEFAULT NULL COMMENT 'Mold open pressure 1',
  `MoldOpenPressure2` float DEFAULT NULL COMMENT 'Mold open pressure 2',
  `MoldOpenPressure3` float DEFAULT NULL COMMENT 'Mold open pressure 3',
  `MoldOpenPressure4` float DEFAULT NULL COMMENT 'Mold open pressure 4',
  `MoldOpenPressure5` float DEFAULT NULL COMMENT 'Mold open pressure 5',
  `MoldOpenPressure6` float DEFAULT NULL COMMENT 'Mold open pressure 6',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Mold open parameters including positions, speeds, and pressures';

--
-- Dumping data for table `moldopenparameters`
--

INSERT INTO `moldopenparameters` (`id`, `record_id`, `MoldOpenPos1`, `MoldOpenPos2`, `MoldOpenPos3`, `MoldOpenPos4`, `MoldOpenPos5`, `MoldOpenPos6`, `MoldOpenSpd1`, `MoldOpenSpd2`, `MoldOpenSpd3`, `MoldOpenSpd4`, `MoldOpenSpd5`, `MoldOpenSpd6`, `MoldOpenPressure1`, `MoldOpenPressure2`, `MoldOpenPressure3`, `MoldOpenPressure4`, `MoldOpenPressure5`, `MoldOpenPressure6`, `created_at`, `updated_at`) VALUES
(1, 'PARAM_20250731_7144f', 45.48, 66.46, 41.41, 26.97, 27.04, 48.12, 70.05, 98.09, 73.8, 26.27, 42.56, 70.75, 64.6, 42.2, 39.7, 51.8, 39.9, 173.1, '2025-07-31 01:59:49', '2025-07-31 01:59:49'),
(2, 'PARAM_20250731_3be2b', 45.48, 66.46, 41.41, 26.97, 27.04, 48.12, 70.05, 98.09, 73.8, 26.27, 42.56, 70.75, 64.6, 42.2, 39.7, 51.8, 39.9, 173.1, '2025-07-31 02:00:41', '2025-07-31 02:00:41'),
(3, 'PARAM_20250731_da614', 97.32, 17.18, 87.69, 21.03, 31.57, 26.19, 14.59, 8.48, 1.53, 14.65, 10.63, 62.08, 132, 109.9, 123, 108.7, 37.8, 65.5, '2025-07-31 02:11:00', '2025-07-31 02:11:00'),
(4, 'PARAM_20250731_ea370', 73.78, 23.41, 63.19, 67.56, 52.45, 35.93, 48.14, 72.88, 82.43, 4.5, 37.66, 88.9, 73.1, 129.8, 81.9, 167.5, 110.9, 174.7, '2025-07-31 03:17:44', '2025-07-31 03:17:44'),
(5, 'PARAM_20250731_bf47c', 93.24, 4.13, 41.18, 67.3, 43.13, 99.57, 71.79, 38.6, 14.13, 20.39, 90.21, 33.76, 78.5, 78.5, 100.5, 66.5, 99.1, 170.3, '2025-07-31 03:24:10', '2025-07-31 03:24:10'),
(6, 'PARAM_20250731_9d7d3', 73.32, 90.26, 27.13, 19.74, 2.28, 6.03, 87.21, 57.9, 66.93, 55.09, 22.11, 54.27, 196.4, 54.8, 160.7, 119.4, 196, 154.6, '2025-07-31 03:25:56', '2025-07-31 03:25:56'),
(7, 'PARAM_20250731_50626', 13.74, 31.56, 52.14, 93.82, 9.51, 82.01, 66.42, 6.13, 15.78, 78.52, 97.62, 24.12, 36.9, 99, 73, 43.3, 96.1, 173.9, '2025-07-31 03:42:03', '2025-07-31 03:42:03'),
(8, 'PARAM_20250806_0bf3f', 13.74, 31.56, 52.14, 93.82, 9.51, 82.01, 66.42, 6.13, 15.78, 78.52, 97.62, 24.12, 36.9, 99, 73, 43.3, 96.1, 173.9, '2025-08-06 01:16:58', '2025-08-06 01:16:58'),
(9, 'PARAM_20250806_b3e70', 93.28, 49.8, 15.09, 68.54, 91.74, 93.23, 95.31, 23.08, 70.22, 29.12, 19.66, 46.05, 148.9, 180.2, 105.4, 68.6, 35.6, 126.1, '2025-08-06 15:34:06', '2025-08-06 15:34:06'),
(10, 'PARAM_20250806_89709', 93.28, 49.8, 15.09, 68.54, 91.74, 93.23, 95.31, 23.08, 70.22, 29.12, 19.66, 46.05, 148.9, 180.2, 105.4, 68.6, 35.6, 126.1, '2025-08-06 15:36:57', '2025-08-06 15:36:57'),
(11, 'PARAM_20250806_6a12f', 60.27, 75.34, 73.76, 6.5, 67.47, 80.42, 71.29, 8.79, 99.29, 36.12, 98.41, 31.63, 101.6, 81.1, 138.4, 10, 20.7, 186.7, '2025-08-06 15:41:09', '2025-08-06 15:41:09');

-- --------------------------------------------------------

--
-- Table structure for table `moldoperationspecs`
--

CREATE TABLE `moldoperationspecs` (
  `id` int(11) NOT NULL,
  `record_id` varchar(20) DEFAULT NULL,
  `MoldCode` varchar(255) DEFAULT NULL,
  `ClampingForce` varchar(255) DEFAULT NULL,
  `OperationType` varchar(255) DEFAULT NULL,
  `CoolingMedia` varchar(255) DEFAULT NULL,
  `HeatingMedia` varchar(255) DEFAULT NULL,
  `StationaryCoolingMediaRemarks` text DEFAULT NULL,
  `MovableCoolingMediaRemarks` text DEFAULT NULL,
  `StationaryCoolingMedia` varchar(255) DEFAULT NULL COMMENT 'Stationary cooling media type',
  `MovableCoolingMedia` varchar(255) DEFAULT NULL COMMENT 'Movable cooling media type',
  `CoolingMediaRemarks` text DEFAULT NULL COMMENT 'Additional remarks about cooling media'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `moldoperationspecs`
--

INSERT INTO `moldoperationspecs` (`id`, `record_id`, `MoldCode`, `ClampingForce`, `OperationType`, `CoolingMedia`, `HeatingMedia`, `StationaryCoolingMediaRemarks`, `MovableCoolingMediaRemarks`, `StationaryCoolingMedia`, `MovableCoolingMedia`, `CoolingMediaRemarks`) VALUES
(1, 'PARAM_20240701_001', 'MC101', '50 tons', 'automatic', 'Chilled Water', 'Hot Oil', NULL, NULL, 'Chilled Water', 'Chilled Water', NULL),
(2, 'PARAM_20240702_002', 'MC202', '75 tons', 'automatic', 'Chilled Water', 'Electrical', NULL, NULL, 'Chilled Water', 'Chilled Water', NULL),
(3, 'PARAM_20240703_003', 'MC303', '100 tons', 'automatic', 'Chilled Water', 'Hot Oil', NULL, NULL, 'Chilled Water', 'Chilled Water', NULL),
(9, 'PARAM_20250515_c0d50', 'MC404', '45.6 tons', 'automatic', 'Oil Cooling', 'Steam', NULL, NULL, 'Oil Cooling', 'Oil Cooling', NULL),
(10, 'PARAM_20250517_3c0ae', 'MC202', '39.7 tons', 'semi-automatic', 'Glycol Solution', 'Electrical', NULL, NULL, 'Glycol Solution', 'Glycol Solution', NULL),
(11, 'PARAM_20250517_a243f', 'MC404', '108.5 tons', 'manual', 'Oil Cooling', 'Electrical', NULL, NULL, 'Oil Cooling', 'Oil Cooling', NULL),
(12, 'PARAM_20250517_3ac7b', 'MC505', '60.7 tons', 'automatic', 'Glycol Solution', 'Hot Oil', NULL, NULL, 'Glycol Solution', 'Glycol Solution', NULL),
(13, 'PARAM_20250517_42616', 'MC505', '50.1 tons', 'manual', 'Chilled Water', 'Steam', NULL, NULL, 'Chilled Water', 'Chilled Water', NULL),
(14, 'PARAM_20250517_1d00a', 'MC303', '26.0 tons', 'automatic', 'Chilled Water', 'Electrical', NULL, NULL, 'Chilled Water', 'Chilled Water', NULL),
(15, 'PARAM_20250517_7b02c', 'MC404', '130.7 tons', 'manual', 'Chilled Water', 'Steam', NULL, NULL, 'Chilled Water', 'Chilled Water', NULL),
(16, 'PARAM_20250521_496e7', 'MC202', '134.6 tons', 'automatic', 'Oil Cooling', 'Steam', NULL, NULL, 'Oil Cooling', 'Oil Cooling', NULL),
(17, 'PARAM_20250522_f3ab5', 'MC505', '146.6 tons', 'manual', 'Oil Cooling', 'Hot Oil', NULL, NULL, 'Oil Cooling', 'Oil Cooling', NULL),
(18, 'PARAM_20250522_988df', 'MC101', '198.9 tons', 'automatic', 'Glycol Solution', 'Hot Oil', NULL, NULL, 'Glycol Solution', 'Glycol Solution', NULL),
(19, 'PARAM_20250708_c11f1', 'MC505', '181.9 tons', 'automatic', 'Chilled', 'Steam', NULL, NULL, 'Chilled', 'Chilled', NULL),
(20, 'PARAM_20250717_83756', 'MC303', '164.5 tons', 'manual', 'Normal', 'Hot Oil', NULL, NULL, 'Normal', 'Normal', NULL),
(21, 'PARAM_20250717_cb661', 'MC505', '28.1 tons', 'semi-automatic', 'Chilled', 'Steam', NULL, NULL, 'Chilled', 'Chilled', NULL),
(22, 'PARAM_20250717_9248f', 'MC404', '23.9 tons', 'manual', 'Chilled', 'Electrical', NULL, NULL, 'Chilled', 'Chilled', NULL),
(23, 'PARAM_20250717_a7d06', 'MC404', '52.7 tons', 'manual', 'Chilled', 'Electrical', NULL, NULL, 'Chilled', 'Chilled', NULL),
(24, 'PARAM_20250718_1ce2e', 'MC303', '71.9 tons', 'automatic', 'MTC', 'Hot Oil', NULL, NULL, 'MTC', 'MTC', NULL),
(33, 'PARAM_20250718_1333e', '3096', '580', 'semi-automatic', 'Normal', 'MHC', NULL, NULL, 'Normal', 'Normal', NULL),
(34, 'PARAM_20250718_210a5', '3652', '690', 'automatic', 'Chilled', 'MHC', NULL, NULL, 'Chilled', 'Chilled', NULL),
(35, 'PARAM_20250718_dd40b', '3652', '690', 'automatic', 'Chilled', 'MHC', NULL, NULL, 'Chilled', 'Chilled', NULL),
(36, 'PARAM_20250718_c09f9', '4125', '50', 'semi-automatic', 'Normal', 'MHC', NULL, NULL, 'Normal', 'Normal', NULL),
(38, 'PARAM_20250718_9ec7c', '4125', '50', 'semi-automatic', 'Normal', 'MHC', NULL, NULL, 'Normal', 'Normal', NULL),
(39, 'PARAM_20250718_18709', '4072', '95', 'automatic', 'Chilled', 'MHC', NULL, NULL, 'Chilled', 'Chilled', NULL),
(40, 'PARAM_20250718_634d7', '3764', '89', 'automatic', 'Chilled', 'MTC', NULL, NULL, 'Chilled', 'Chilled', NULL),
(41, 'PARAM_20250718_1fe34', '3269', '99', 'semi-automatic', 'Chilled', 'MHC', NULL, NULL, 'Chilled', 'Chilled', NULL),
(42, 'PARAM_20250721_15806', '4125', '85@55', 'semi-automatic', 'Normal', 'MHC', NULL, NULL, 'Normal', 'Normal', NULL),
(43, 'PARAM_20250721_73997', '4125', '85@55', 'semi-automatic', 'Normal', 'MHC', NULL, NULL, 'Normal', 'Normal', NULL),
(44, 'PARAM_20250731_9f4a2', 'MC202', '166.8 tons', 'robot arm', NULL, 'Steam', 'Glycol Solution', 'Glycol Solution', 'Normal', 'Chilled', NULL),
(45, 'PARAM_20250731_efacc', 'MC202', '166.8 tons', 'robot arm', NULL, 'Steam', 'Glycol Solution', 'Glycol Solution', 'Normal', 'Chilled', NULL),
(46, 'PARAM_20250731_7144f', 'MC202', '166.8 tons', 'robot arm', NULL, 'Steam', 'Glycol Solution', 'Glycol Solution', 'Normal', 'Chilled', NULL),
(47, 'PARAM_20250731_3be2b', 'MC202', '166.8 tons', 'robot arm', NULL, 'Steam', 'Glycol Solution', 'Glycol Solution', 'Normal', 'Chilled', NULL),
(48, 'PARAM_20250731_da614', 'MC202', '164.7 tons', 'semi-automatic', NULL, 'Steam', 'Glycol Solution', 'Glycol Solution', 'Normal', 'Chilled', NULL),
(49, 'PARAM_20250731_ea370', 'MC404', '111.4 tons', 'manual', NULL, 'Hot Oil', 'Chilled Water', 'Chilled Water', 'Normal', 'MTC', NULL),
(50, 'PARAM_20250731_bf47c', 'MC101', '179.2 tons', 'semi-automatic', NULL, 'Hot Oil', 'Glycol Solution', 'Glycol Solution', 'Normal', 'Normal', NULL),
(51, 'PARAM_20250731_9d7d3', 'MC101', '141.2 tons', 'manual', NULL, 'Electrical', 'Oil Cooling', 'Oil Cooling', 'MTC', 'MTC', NULL),
(52, 'PARAM_20250731_50626', 'MC404', '122.3 tons', 'manual', NULL, 'Steam', 'Glycol Solution', 'Glycol Solution', 'MTC', 'MTC', NULL),
(53, 'PARAM_20250806_0bf3f', 'MC404', '122.3 tons', 'manual', NULL, 'Steam', '', '', 'MTC', 'MTC', NULL),
(54, 'PARAM_20250806_b3e70', 'MC404', '93.8 tons', 'manual', NULL, 'Electrical', 'Chilled Water', 'Chilled Water', 'Chilled', 'Normal', NULL),
(55, 'PARAM_20250806_89709', 'MC404', '93.8 tons', 'manual', NULL, 'Electrical', '', '', 'Chilled', 'Normal', NULL),
(56, 'PARAM_20250806_6a12f', 'MC404', '106.9 tons', 'semi-automatic', NULL, 'Electrical', 'Glycol Solution', 'Glycol Solution', 'Normal', 'MTC', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `parameters_submissions`
--

CREATE TABLE `parameters_submissions` (
  `id` int(11) NOT NULL,
  `productmachineinfo_id` int(11) NOT NULL,
  `productdetails_id` int(11) NOT NULL,
  `materialcomposition_id` int(11) NOT NULL,
  `colorantdetails_id` int(11) NOT NULL,
  `moldoperationspecs_id` int(11) NOT NULL,
  `timerparameters_id` int(11) NOT NULL,
  `barrelheatertemperatures_id` int(11) NOT NULL,
  `moldheatertemperatures_id` int(11) NOT NULL,
  `plasticizingparameters_id` int(11) NOT NULL,
  `injectionparameters_id` int(11) NOT NULL,
  `ejectionparameters_id` int(11) NOT NULL,
  `corepullsettings_id` int(11) NOT NULL,
  `additionalinformation_id` int(11) NOT NULL,
  `personnel_id` int(11) NOT NULL,
  `approval_status` enum('pending','approved','declined') NOT NULL DEFAULT 'pending',
  `qa_reviewer` varchar(255) DEFAULT NULL,
  `supervisor_reviewer` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `parameter_records`
--

CREATE TABLE `parameter_records` (
  `record_id` varchar(20) NOT NULL,
  `submission_date` timestamp NULL DEFAULT current_timestamp(),
  `status` enum('active','archived','deleted') DEFAULT 'active',
  `submitted_by` varchar(100) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `parameter_records`
--

INSERT INTO `parameter_records` (`record_id`, `submission_date`, `status`, `submitted_by`, `title`, `description`) VALUES
('PARAM_20250522_988df', '2025-05-22 06:36:59', 'active', 'Jessie Mallorca Castro', 'CLF 750B - Plastic Container', 'Production run completed with standard parameters. All quality checks passed. Material batch: MB-3604'),
('PARAM_20250718_1333e', '2025-07-18 03:06:22', 'active', 'Romart Canda', 'CLF 750A - CM-5', ''),
('PARAM_20250718_18709', '2025-07-18 05:28:47', 'active', 'Romart Canda', 'MIT 1050B - 1L PEPSI', ''),
('PARAM_20250718_1fe34', '2025-07-18 09:42:21', 'active', 'Jade Eduardo Derramas', 'TOS 850B - CNS-3C', '2 GATES ONLY DUE TO GAS TRAP.'),
('PARAM_20250718_210a5', '2025-07-18 03:22:56', 'active', 'Romart Canda', 'CLF 750B - 8oz Shell Plastic Crate (PEPSI)', ''),
('PARAM_20250718_634d7', '2025-07-18 06:04:37', 'active', 'Romart Canda', 'TOS 850B - 1L Shell Plastic Crate (Pepsi)', ''),
('PARAM_20250718_9ec7c', '2025-07-18 04:56:31', 'active', 'Unknown User', 'SUM 350 - UTILITY BIN 10L RECTANGULAR', 'F'),
('PARAM_20250718_b720b', '2025-07-18 04:56:14', 'active', 'Unknown User', 'Unnamed Record', ''),
('PARAM_20250718_c09f9', '2025-07-18 04:56:18', 'active', 'Unknown User', 'SUM 350 - UTILITY BIN 10L RECTANGULAR', ''),
('PARAM_20250718_dd40b', '2025-07-18 03:49:14', 'active', 'Romart Canda', 'CLF 750B - 8oz Shell Plastic Crate (PEPSI)', 'W/ HPP'),
('PARAM_20250721_15806', '2025-07-21 02:44:59', 'active', 'Kaishu San Jose', 'MIT 650D - UTILITY BIN 10L RECTANGULAR', 'AIR BLOW CONNECTED TO THE CORE SEQUENCE \r\nAn air line is connected to the Mold cavity; it blows air due to the mold open.\r\nCore mold \r\n1 1 - 1 3.0 7 - 1 2.0\r\n'),
('PARAM_20250721_73997', '2025-07-21 02:46:43', 'active', 'Kaishu San Jose', 'MIT 650D - UTILITY BIN 10L RECTANGULAR', 'AIR BLOW CONNECTED TO THE CORE SEQUENCE \r\nAn air line is connected to the Mold cavity; it blows air due to the mold open.\r\nCore mold \r\n1 1 - 1 3.0 7 - 1 2.0\r\n'),
('PARAM_20250731_3be2b', '2025-07-31 02:00:41', 'active', 'Aeron Paul Daliva', 'TOS 650A - Toy Box', 'Production run completed with standard parameters. All quality checks passed. Material batch: MB-3830'),
('PARAM_20250731_50626', '2025-07-31 03:42:03', 'active', 'Aeron Paul Daliva', 'MIT 1050B - CNS-3C', 'Production run completed with standard parameters. All quality checks passed. Material batch: MB-6908'),
('PARAM_20250731_7144f', '2025-07-31 01:59:49', 'active', 'Aeron Paul Daliva', 'TOS 650A - Toy Box', 'Production run completed with standard parameters. All quality checks passed. Material batch: MB-3830'),
('PARAM_20250731_9b44f', '2025-07-31 00:20:59', 'active', 'Aeron Paul Daliva', 'SUM 260C - Phone Case', 'Production run completed with standard parameters. All quality checks passed. Material batch: MB-1198'),
('PARAM_20250731_9d7d3', '2025-07-31 03:25:56', 'active', 'Aeron Paul Daliva', 'ARB 50 - Phone Case', 'Production run completed with standard parameters. All quality checks passed. Material batch: MB-6179'),
('PARAM_20250731_9f4a2', '2025-07-31 01:53:31', 'active', 'Aeron Paul Daliva', 'TOS 650A - Toy Box', 'Production run completed with standard parameters. All quality checks passed. Material batch: MB-3830'),
('PARAM_20250731_a2a58', '2025-07-31 00:50:02', 'active', 'Aeron Paul Daliva', 'CLF 750A - Food Container', 'Production run completed with standard parameters. All quality checks passed. Material batch: MB-7349'),
('PARAM_20250731_bf47c', '2025-07-31 03:24:10', 'active', 'Aeron Paul Daliva', 'ARB 50 - Food Container', 'Production run completed with standard parameters. All quality checks passed. Material batch: MB-1358'),
('PARAM_20250731_da614', '2025-07-31 02:11:00', 'active', 'Aeron Paul Daliva', 'TOS 850B - 1L SHELL PLASTIC CRATE (PEPSI)', 'Production run completed with standard parameters. All quality checks passed. Material batch: MB-2188'),
('PARAM_20250731_ea370', '2025-07-31 03:17:44', 'active', 'Aeron Paul Daliva', 'CLF 950A - 1L PEPSI', 'Production run completed with standard parameters. All quality checks passed. Material batch: MB-5876'),
('PARAM_20250731_efacc', '2025-07-31 01:57:36', 'active', 'Aeron Paul Daliva', 'TOS 650A - Toy Box', 'Production run completed with standard parameters. All quality checks passed. Material batch: MB-3830'),
('PARAM_20250806_0bf3f', '2025-08-06 01:16:58', 'active', 'Aeron Paul Daliva', 'MIT 1050B - CNS-3C', 'Production run completed with standard parameters. All quality checks passed. Material batch: MB-6908'),
('PARAM_20250806_6a12f', '2025-08-06 15:41:09', 'active', 'Aeron Paul Daliva', 'ARB 50 - Bottle Cap', 'Production run completed with standard parameters. All quality checks passed. Material batch: MB-7740'),
('PARAM_20250806_89709', '2025-08-06 15:36:57', 'active', 'Aeron Paul Daliva', 'TOS 850C - Phone Case', 'Production run completed with standard parameters. All quality checks passed. Material batch: MB-9961'),
('PARAM_20250806_b3e70', '2025-08-06 15:34:06', 'active', 'Aeron Paul Daliva', 'TOS 850C - Phone Case', 'Production run completed with standard parameters. All quality checks passed. Material batch: MB-9961');

-- --------------------------------------------------------

--
-- Table structure for table `personnel`
--

CREATE TABLE `personnel` (
  `id` int(11) NOT NULL,
  `record_id` varchar(20) DEFAULT NULL,
  `AdjusterName` varchar(255) DEFAULT NULL,
  `QAEName` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `personnel`
--

INSERT INTO `personnel` (`id`, `record_id`, `AdjusterName`, `QAEName`) VALUES
(13, 'PARAM_20250522_988df', 'Jessie Mallorca Castro', 'QA Robert Brown'),
(20, 'PARAM_20250718_1333e', 'Romart Canda', ''),
(21, 'PARAM_20250718_210a5', 'Romart Canda', ''),
(22, 'PARAM_20250718_dd40b', 'Romart Canda', ''),
(24, 'PARAM_20250718_9ec7c', 'Romart Canda', ''),
(25, 'PARAM_20250718_18709', 'Romart Canda', ''),
(26, 'PARAM_20250718_634d7', 'Romart Canda', ''),
(27, 'PARAM_20250718_1fe34', 'Jade Eduardo Derramas', 'Carl Francisco'),
(28, 'PARAM_20250721_15806', 'Kaishu San Jose', 'Ian ilustresimo'),
(29, 'PARAM_20250721_73997', 'Kaishu San Jose', 'Ian ilustresimo'),
(30, 'PARAM_20250731_9b44f', 'Aeron Paul Daliva', 'QA Robert Brown'),
(31, 'PARAM_20250731_a2a58', 'Aeron Paul Daliva', 'QA Robert Brown'),
(32, 'PARAM_20250731_3be2b', 'Aeron Paul Daliva', 'QA Robert Brown'),
(33, 'PARAM_20250731_da614', 'Aeron Paul Daliva', 'QA Mike Johnson'),
(34, 'PARAM_20250731_ea370', 'Aeron Paul Daliva', 'QA Robert Brown'),
(35, 'PARAM_20250731_bf47c', 'Aeron Paul Daliva', 'QA John Smith'),
(36, 'PARAM_20250731_9d7d3', 'Aeron Paul Daliva', 'QA John Smith'),
(37, 'PARAM_20250731_50626', 'Aeron Paul Daliva', 'QA John Smith'),
(38, 'PARAM_20250806_0bf3f', 'Aeron Paul Daliva', 'QA John Smith'),
(39, 'PARAM_20250806_b3e70', 'Aeron Paul Daliva', 'Stephanie Iris Sapno'),
(40, 'PARAM_20250806_89709', 'Aeron Paul Daliva', 'John Nero Abreu'),
(41, 'PARAM_20250806_6a12f', 'Aeron Paul Daliva', 'Ian Ilustresimo');

-- --------------------------------------------------------

--
-- Table structure for table `plasticizingparameters`
--

CREATE TABLE `plasticizingparameters` (
  `id` int(11) NOT NULL,
  `record_id` varchar(20) DEFAULT NULL,
  `ScrewRPM1` float DEFAULT NULL,
  `ScrewRPM2` float DEFAULT NULL,
  `ScrewRPM3` float DEFAULT NULL,
  `ScrewSpeed1` float DEFAULT NULL,
  `ScrewSpeed2` float DEFAULT NULL,
  `ScrewSpeed3` float DEFAULT NULL,
  `PlastPressure1` float DEFAULT NULL,
  `PlastPressure2` float DEFAULT NULL,
  `PlastPressure3` float DEFAULT NULL,
  `PlastPosition1` float DEFAULT NULL,
  `PlastPosition2` float DEFAULT NULL,
  `PlastPosition3` float DEFAULT NULL,
  `BackPressure1` float DEFAULT NULL,
  `BackPressure2` float DEFAULT NULL,
  `BackPressure3` float DEFAULT NULL,
  `BackPressureStartPosition` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `plasticizingparameters`
--

INSERT INTO `plasticizingparameters` (`id`, `record_id`, `ScrewRPM1`, `ScrewRPM2`, `ScrewRPM3`, `ScrewSpeed1`, `ScrewSpeed2`, `ScrewSpeed3`, `PlastPressure1`, `PlastPressure2`, `PlastPressure3`, `PlastPosition1`, `PlastPosition2`, `PlastPosition3`, `BackPressure1`, `BackPressure2`, `BackPressure3`, `BackPressureStartPosition`) VALUES
(14, 'PARAM_20250522_988df', 106.4, 147.4, 81.7, 63.1, 55.1, 37.2, 62.9, 107.3, 49.4, 77.13, 80.12, 73.69, 101.5, 55.1, 75, 180.6),
(29, 'PARAM_20250718_1333e', 15, 15, 15, 0, 0, 0, 0, 0, 0, 0, 0, 0, 5, 5, 0, 0),
(30, 'PARAM_20250718_210a5', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(31, 'PARAM_20250718_dd40b', 25, 25, 25, 40, 40, 40, 0, 0, 0, 0, 0, 0, 3, 3, 3, 0),
(33, 'PARAM_20250718_9ec7c', 99, 99, 99, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(34, 'PARAM_20250718_18709', 65, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(35, 'PARAM_20250718_634d7', 55, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(36, 'PARAM_20250718_1fe34', 0, 0, 0, 45, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(37, 'PARAM_20250721_15806', 0, 0, 0, 55, 55, 55, 0, 0, 0, 0, 20, 60, 0, 3, 3, 65),
(38, 'PARAM_20250721_73997', 0, 0, 0, 55, 55, 55, 0, 0, 0, 0, 20, 60, 0, 3, 3, 65),
(39, 'PARAM_20250731_9b44f', 53.8, 140.9, 155.6, 59.8, 60.5, 92, 192.5, 26.5, 48.1, 42.06, 45.08, 147.66, 191.7, 163.5, 183.6, 57.3),
(40, 'PARAM_20250731_a2a58', 137.3, 54.6, 65.7, 59.6, 66.7, 72.4, 103.6, 40.2, 49.1, 132.82, 15.65, 56.41, 117.6, 100.3, 81.4, 25.3),
(41, 'PARAM_20250731_3be2b', 144.1, 68.3, 111.3, 53.1, 99, 49.3, 125, 55.7, 15.4, 66.09, 20.69, 79.02, 182.1, 55.2, 195.9, 118.2),
(42, 'PARAM_20250731_da614', 46.3, 158, 176.7, 88.2, 19.7, 28.3, 180.7, 92.2, 67.8, 86.19, 56.23, 107.35, 187.5, 10.8, 127.6, 50.2),
(43, 'PARAM_20250731_ea370', 141.3, 113, 176.9, 18.5, 66.8, 36.6, 12.5, 25.2, 16.4, 118.63, 37.43, 107.92, 120.1, 59.7, 130.5, 108.9),
(44, 'PARAM_20250731_bf47c', 87.8, 109, 130, 49.8, 75.6, 28.8, 111.6, 91.7, 90.1, 30.33, 10.1, 91.33, 164.1, 50.6, 113.6, 63.1),
(45, 'PARAM_20250731_9d7d3', 103.4, 130, 155.1, 20.9, 92.3, 37, 164.6, 31.4, 17.6, 93.74, 75.6, 128.36, 187, 194.5, 86.1, 132.5),
(46, 'PARAM_20250731_50626', 47.4, 83.9, 172, 37.5, 45.5, 78.1, 138.3, 25.1, 101.5, 43.58, 30.5, 51.18, 192.8, 17.9, 91.9, 128),
(47, 'PARAM_20250806_0bf3f', 47.4, 83.9, 172, 37.5, 45.5, 78.1, 138.3, 25.1, 101.5, 43.58, 30.5, 51.18, 192.8, 17.9, 91.9, 128),
(48, 'PARAM_20250806_b3e70', 63.8, 93.2, 169.9, 87.7, 51.6, 67.7, 43.1, 197, 74.6, 70.88, 128.83, 108.28, 10, 75.2, 66.3, 12.2),
(49, 'PARAM_20250806_89709', 63.8, 93.2, 169.9, 87.7, 51.6, 67.7, 43.1, 197, 74.6, 70.88, 128.83, 108.28, 10, 75.2, 66.3, 12.2),
(50, 'PARAM_20250806_6a12f', 147.6, 131.9, 152.7, 82.8, 12, 15.2, 98.2, 27.3, 39.3, 90.44, 32.76, 75.07, 81.1, 146.7, 177.3, 187.3);

-- --------------------------------------------------------

--
-- Table structure for table `productdetails`
--

CREATE TABLE `productdetails` (
  `id` int(11) NOT NULL,
  `record_id` varchar(20) DEFAULT NULL,
  `ProductName` varchar(255) DEFAULT NULL,
  `Color` varchar(255) DEFAULT NULL,
  `MoldName` varchar(255) DEFAULT NULL,
  `ProductNumber` varchar(255) DEFAULT NULL,
  `CavityActive` int(11) DEFAULT NULL,
  `GrossWeight` float DEFAULT NULL,
  `NetWeight` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `productdetails`
--

INSERT INTO `productdetails` (`id`, `record_id`, `ProductName`, `Color`, `MoldName`, `ProductNumber`, `CavityActive`, `GrossWeight`, `NetWeight`) VALUES
(1, 'PARAM_20240701_001', 'Plastic Container', 'Clear', 'MOLD-C101', 'PC-100', 4, 120.5, 115),
(2, 'PARAM_20240702_002', 'Bottle Cap', 'Blue', 'MOLD-C202', 'BC-200', 8, 15.2, 14.8),
(3, 'PARAM_20240703_003', 'Phone Case', 'Black', 'MOLD-C303', 'PC-300', 2, 45, 42.5),
(9, 'PARAM_20250515_c0d50', 'Phone Case', 'Clear', 'Sample-9654', 'PN-5681', 7, 342.5, 104.6),
(10, 'PARAM_20250517_3c0ae', 'Phone Case', 'White', 'Sample-1569', 'PN-2259', 12, 324.3, 28.1),
(11, 'PARAM_20250517_a243f', 'Bottle Cap', 'Blue', 'Sample-1839', 'PN-3325', 15, 153.5, 458),
(12, 'PARAM_20250517_3ac7b', 'Plastic Container', 'White', 'Sample-7448', 'PN-6704', 10, 273.6, 121.8),
(13, 'PARAM_20250517_42616', 'Phone Case', 'Black', 'Sample-3624', 'PN-9710', 14, 239.8, 13.1),
(14, 'PARAM_20250517_1d00a', 'Toy Box', 'Red', 'Sample-9244', 'PN-2606', 7, 60.2, 128),
(15, 'PARAM_20250517_7b02c', 'Plastic Container', 'White', 'Sample-4700', 'PN-2282', 4, 134.3, 358.7),
(16, 'PARAM_20250521_496e7', 'Bottle Cap', 'Red', 'Sample-4460', 'PN-5148', 4, 106.9, 401.3),
(17, 'PARAM_20250522_f3ab5', 'Food Container', 'Yellow', 'Sample-2730', 'PN-5972', 5, 307.7, 290.5),
(18, 'PARAM_20250522_988df', 'Plastic Container', 'Gray', 'Sample-7121', 'PN-4356', 1, 195.8, 437.9),
(19, 'PARAM_20250708_c11f1', 'Bottle Cap', 'Blue', 'Sample-2813', 'PN-1918', 1, 140.7, 249.6),
(20, 'PARAM_20250717_83756', 'Phone Case', 'Clear', 'Sample-2908', 'PN-8523', 4, 272.2, 241.8),
(21, 'PARAM_20250717_cb661', 'Water Bottle', 'Red', 'Sample-5235', 'PN-3086', 14, 206.7, 283.8),
(22, 'PARAM_20250717_9248f', 'Food Container', 'White', 'Sample-3462', 'PN-3377', 16, 488.7, 183.7),
(23, 'PARAM_20250717_a7d06', 'Phone Case', 'Black', 'Sample-7347', 'PN-5678', 16, 471.9, 142.9),
(24, 'PARAM_20250718_1ce2e', 'Bottle Cap', 'White', 'Sample-4823', 'PN-5304', 7, 192.4, 231.7),
(33, 'PARAM_20250718_1333e', 'CM-5', 'BLUE', 'CM-5', '02', 1, 1295, 1291),
(34, 'PARAM_20250718_210a5', '8oz Shell Plastic Crate (PEPSI)', 'PEPSO BLUE G', '8OZ PEPSI', '8OZ', 1, 980, 0),
(35, 'PARAM_20250718_dd40b', '8oz Shell Plastic Crate (PEPSI)', 'BLUE', '80Z PEPSI', '', 1, 980, 0),
(36, 'PARAM_20250718_c09f9', 'UTILITY BIN 10L RECTANGULAR', 'NATURAL', 'UTILITY BIN', '01', 1, 0, 0),
(38, 'PARAM_20250718_9ec7c', 'UTILITY BIN 10L RECTANGULAR', 'NATURAL', 'UTILITY BIN', '01', 1, 0, 0),
(39, 'PARAM_20250718_18709', '1L PEPSI', 'BLUE', '1L PEPSO #4072', '01', 1, 1801, 0),
(40, 'PARAM_20250718_634d7', '1L Shell Plastic Crate (Pepsi)', 'BLUE', '', '01', 1, 1994, 0),
(41, 'PARAM_20250718_1fe34', 'CNS-3C', 'BLUE', 'CNS-3C', '02', 1, 0, 0),
(42, 'PARAM_20250721_15806', 'UTILITY BIN 10L RECTANGULAR', 'Light Gray', 'UTILITY BIN', '', 1, 447, 440),
(43, 'PARAM_20250721_73997', 'UTILITY BIN 10L RECTANGULAR', 'Light Gray', 'UTILITY BIN', '', 1, 447, 440),
(44, 'PARAM_20250731_9b44f', 'Phone Case', 'Green', 'Sample-4714', 'PN-4838', 7, 148.1, 227.8),
(45, 'PARAM_20250731_a2a58', 'Food Container', 'Blue', 'Sample-3981', 'PN-8856', 7, 259.9, 300),
(46, 'PARAM_20250731_9f4a2', 'Toy Box', 'Clear', 'Sample-8133', 'PN-7815', 11, 131.5, 344.1),
(47, 'PARAM_20250731_efacc', 'Toy Box', 'Clear', 'Sample-8133', 'PN-7815', 11, 131.5, 344.1),
(48, 'PARAM_20250731_7144f', 'Toy Box', 'Clear', 'Sample-8133', 'PN-7815', 11, 131.5, 344.1),
(49, 'PARAM_20250731_3be2b', 'Toy Box', 'Clear', 'Sample-8133', 'PN-7815', 11, 131.5, 344.1),
(50, 'PARAM_20250731_da614', '1L SHELL PLASTIC CRATE (PEPSI)', 'Yellow', 'Sample-2324', 'PN-9015', 3, 450, 60.5),
(51, 'PARAM_20250731_ea370', '1L PEPSI', 'Blue', 'Sample-1382', 'PN-1914', 3, 227.7, 449.1),
(52, 'PARAM_20250731_bf47c', 'Food Container', 'Gray', 'Sample-8152', 'PN-3772', 8, 263.5, 262.6),
(53, 'PARAM_20250731_9d7d3', 'Phone Case', 'Blue', 'Sample-2231', 'PN-9398', 2, 42.6, 236.8),
(54, 'PARAM_20250731_50626', 'CNS-3C', 'Clear', 'Sample-3468', 'PN-7342', 16, 18, 30.1),
(55, 'PARAM_20250806_0bf3f', 'CNS-3C', '', 'Sample-3468', 'PN-7342', 16, 18, 30.1),
(56, 'PARAM_20250806_b3e70', 'Phone Case', 'Yellow', 'Sample-7529', 'PN-5101', 7, 13.9, 353.5),
(57, 'PARAM_20250806_89709', 'Phone Case', 'Black', 'Sample-7529', 'PN-5101', 7, 13.9, 353.5),
(58, 'PARAM_20250806_6a12f', 'Bottle Cap', 'Green', 'Sample-9808', 'PN-1604', 10, 409.1, 151);

-- --------------------------------------------------------

--
-- Table structure for table `productmachineinfo`
--

CREATE TABLE `productmachineinfo` (
  `id` int(11) NOT NULL,
  `record_id` varchar(20) DEFAULT NULL,
  `Date` date NOT NULL,
  `Time` time NOT NULL,
  `startTime` time DEFAULT NULL,
  `endTime` time DEFAULT NULL,
  `MachineName` varchar(255) DEFAULT NULL,
  `RunNumber` varchar(255) DEFAULT NULL,
  `Category` varchar(255) DEFAULT NULL,
  `IRN` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `productmachineinfo`
--

INSERT INTO `productmachineinfo` (`id`, `record_id`, `Date`, `Time`, `startTime`, `endTime`, `MachineName`, `RunNumber`, `Category`, `IRN`) VALUES
(1, 'PARAM_20240701_001', '2024-07-01', '08:30:00', NULL, NULL, 'ARB 50', 'RN1001', 'Containers', 'IRN10234'),
(2, 'PARAM_20240702_002', '2024-07-02', '10:15:00', NULL, NULL, 'SUM 350', 'RN1002', 'Caps', 'IRN10235'),
(3, 'PARAM_20240703_003', '2024-07-03', '09:45:00', NULL, NULL, 'CLF 750A', 'RN1003', 'Accessories', 'IRN10236'),
(9, 'PARAM_20250515_c0d50', '2025-05-16', '01:54:00', NULL, NULL, 'TOS 650A', 'RN9749', 'Automotive', 'IRN45285'),
(10, 'PARAM_20250517_3c0ae', '2025-05-17', '09:00:00', NULL, NULL, 'CLF 950B', 'RN7847', 'Automotive', 'IRN41518'),
(11, 'PARAM_20250517_a243f', '2025-05-17', '11:16:00', NULL, NULL, 'CLF 750A', 'RN8065', 'Packaging', 'IRN65630'),
(12, 'PARAM_20250517_3ac7b', '2025-05-17', '11:21:00', NULL, NULL, 'MIT 1050B', 'RN3297', 'Automotive', 'IRN34834'),
(13, 'PARAM_20250517_42616', '2025-05-17', '11:28:00', NULL, NULL, 'MIT 1050B', 'RN8353', 'Containers', 'IRN29882'),
(14, 'PARAM_20250517_1d00a', '2025-05-17', '11:39:00', NULL, NULL, 'CLF 750C', 'RN8209', 'Automotive', 'IRN32540'),
(15, 'PARAM_20250517_7b02c', '2025-05-17', '13:31:00', NULL, NULL, 'SUM 260C', 'RN7394', 'Caps', 'IRN70597'),
(16, 'PARAM_20250521_496e7', '2025-05-22', '07:48:00', NULL, NULL, 'CLF 750A', 'RN9249', 'Caps', 'IRN90267'),
(17, 'PARAM_20250522_f3ab5', '2025-05-22', '13:31:00', NULL, NULL, 'CLF 750A', 'RN6366', 'Containers', 'IRN78179'),
(18, 'PARAM_20250522_988df', '2025-05-22', '14:36:00', NULL, NULL, 'CLF 750B', 'RN1660', 'Caps', 'IRN64463'),
(19, 'PARAM_20250708_c11f1', '2025-07-08', '22:04:00', NULL, NULL, 'MIT 1050B', 'RN2409', 'Colorant Testing', 'IRN86737'),
(20, 'PARAM_20250717_83756', '2025-07-17', '09:40:00', NULL, NULL, 'SUM 350', 'RN7048', 'Machine Preventive Maintenance', 'IRN32532'),
(21, 'PARAM_20250717_cb661', '2025-07-17', '09:45:00', NULL, NULL, 'MIT 1050B', 'RN9219', 'Machine Preventive Maintenance', 'IRN37040'),
(22, 'PARAM_20250717_9248f', '2025-07-17', '10:04:00', '10:04:00', '01:04:00', 'TOS 650A', 'RN6053', 'Machine Preventive Maintenance', 'IRN88061'),
(23, 'PARAM_20250717_a7d06', '2025-07-17', '11:58:00', '11:58:00', '11:00:00', 'SUM 260C', 'RN3325', 'Mold Preventive Maintenance', 'IRN62181'),
(24, 'PARAM_20250718_1ce2e', '2025-07-18', '09:49:00', '09:50:00', '09:50:00', 'CLF 750B', 'RN6473', 'Mass Production', 'IRN81201'),
(33, 'PARAM_20250718_1333e', '2025-07-18', '11:06:00', '13:45:00', '14:45:00', 'CLF 750A', '', 'Mass Production', ''),
(34, 'PARAM_20250718_210a5', '2025-07-18', '11:22:00', '07:14:00', '09:26:00', 'CLF 750B', '', 'Mass Production', ''),
(35, 'PARAM_20250718_dd40b', '2025-07-18', '11:47:00', '07:14:00', '09:26:00', 'CLF 750B', '', 'Mass Production', ''),
(36, 'PARAM_20250718_c09f9', '2025-07-18', '12:54:00', '12:19:00', '14:30:00', 'SUM 350', '', 'Mass Production', ''),
(38, 'PARAM_20250718_9ec7c', '2025-07-18', '12:55:00', '12:19:00', '14:30:00', 'SUM 350', '', 'Mass Production', ''),
(39, 'PARAM_20250718_18709', '2025-07-18', '13:28:00', '06:20:00', '08:50:00', 'MIT 1050B', '', 'Mass Production', ''),
(40, 'PARAM_20250718_634d7', '2025-07-18', '14:03:00', '11:49:00', '13:07:00', 'TOS 850B', '', 'Mass Production', ''),
(41, 'PARAM_20250718_1fe34', '2025-07-18', '17:42:00', '15:30:00', '17:30:00', 'TOS 850B', '', 'Mass Production', 'T8BMP-00324-20'),
(42, 'PARAM_20250721_15806', '2025-07-21', '10:44:00', '10:08:00', '10:31:00', 'MIT 650D', '', 'Mass Production', 'M6DMP-00201-20'),
(43, 'PARAM_20250721_73997', '2025-07-21', '10:45:00', '10:08:00', '10:31:00', 'MIT 650D', '', 'Mass Production', 'M6DMP-00201-20'),
(44, 'PARAM_20250731_9b44f', '2025-07-31', '00:00:01', '00:00:01', '00:00:24', 'SUM 260C', 'RN3674', 'Colorant Testing', 'IRN80425'),
(45, 'PARAM_20250731_a2a58', '2025-07-31', '00:00:05', '00:00:05', '00:00:03', 'CLF 750A', 'RN8744', 'Colorant Testing', 'IRN49891'),
(46, 'PARAM_20250731_9f4a2', '2025-07-31', '00:00:25', '00:00:25', '00:00:26', 'TOS 650A', 'RN9364', 'Material Testing', 'IRN11700'),
(47, 'PARAM_20250731_efacc', '2025-07-31', '00:00:25', '00:00:25', '00:00:26', 'TOS 650A', 'RN9364', 'Material Testing', 'IRN11700'),
(48, 'PARAM_20250731_7144f', '2025-07-31', '00:00:25', '00:00:25', '00:00:26', 'TOS 650A', 'RN9364', 'Material Testing', 'IRN11700'),
(49, 'PARAM_20250731_3be2b', '2025-07-31', '00:00:25', '00:00:25', '00:00:26', 'TOS 650A', 'RN9364', 'Material Testing', 'IRN11700'),
(50, 'PARAM_20250731_da614', '2025-07-31', '00:00:21', '00:00:21', '00:00:21', 'TOS 850B', 'RN8254', 'Product Improvement', 'IRN21605'),
(51, 'PARAM_20250731_ea370', '2025-07-31', '00:00:03', '00:00:03', '00:00:21', 'CLF 950A', 'RN1695', 'Product Improvement', 'IRN42022'),
(52, 'PARAM_20250731_bf47c', '2025-07-31', '00:00:10', '00:00:10', '00:00:25', 'ARB 50', 'RN1229', 'Mass Production', 'IRN34663'),
(53, 'PARAM_20250731_9d7d3', '2025-07-31', '00:00:26', '00:00:26', '00:00:03', 'ARB 50', 'RN2845', 'Machine Preventive Maintenance', 'IRN10401'),
(54, 'PARAM_20250731_50626', '2025-07-31', '11:42:03', '11:42:03', '11:42:03', 'MIT 1050B', 'RN1170', 'Machine Preventive Maintenance', 'IRN79219'),
(55, 'PARAM_20250806_0bf3f', '2025-08-06', '09:16:17', '09:16:17', NULL, 'MIT 1050B', 'RN1170', 'Machine Preventive Maintenance', 'IRN79219'),
(56, 'PARAM_20250806_b3e70', '2025-08-06', '23:34:06', '23:34:06', '23:34:06', 'TOS 850C', 'RN1247', 'New Mold Testing', 'IRN27467'),
(57, 'PARAM_20250806_89709', '2025-08-06', '23:34:12', '23:34:12', NULL, 'TOS 850C', 'RN1247', 'New Mold Testing', 'IRN27467'),
(58, 'PARAM_20250806_6a12f', '2025-08-06', '23:41:09', '23:41:09', '23:41:09', 'ARB 50', 'RN4741', 'Product Improvement', 'IRN35119');

-- --------------------------------------------------------

--
-- Table structure for table `timerparameters`
--

CREATE TABLE `timerparameters` (
  `id` int(11) NOT NULL,
  `record_id` varchar(20) DEFAULT NULL,
  `FillingTime` float DEFAULT NULL,
  `HoldingTime` float DEFAULT NULL,
  `MoldOpenCloseTime` float DEFAULT NULL,
  `ChargingTime` float DEFAULT NULL,
  `CoolingTime` float DEFAULT NULL,
  `CycleTime` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `timerparameters`
--

INSERT INTO `timerparameters` (`id`, `record_id`, `FillingTime`, `HoldingTime`, `MoldOpenCloseTime`, `ChargingTime`, `CoolingTime`, `CycleTime`) VALUES
(1, 'PARAM_20240701_001', 2.5, 5, 3, 1.5, 8, 20),
(2, 'PARAM_20240702_002', 1, 3, 2, 1, 5, 12),
(3, 'PARAM_20240703_003', 3, 6, 4, 2, 10, 25),
(9, 'PARAM_20250515_c0d50', 1.6, 10.6, 2.9, 4, 0, 8.7),
(10, 'PARAM_20250517_3c0ae', 26.3, 25, 21.2, 2.3, 0, 19.5),
(11, 'PARAM_20250517_a243f', 25, 28.7, 20.7, 0.8, 0, 3.7),
(12, 'PARAM_20250517_3ac7b', 19.5, 18.8, 1.9, 4.8, 0, 16.8),
(13, 'PARAM_20250517_42616', 8, 24.6, 10.3, 28.7, 0, 28.4),
(14, 'PARAM_20250517_1d00a', 19.7, 9.7, 19.9, 22.7, 0, 1.1),
(15, 'PARAM_20250517_7b02c', 18.9, 27.4, 26.1, 4.1, 0, 2.6),
(16, 'PARAM_20250521_496e7', 2.1, 10.1, 25.8, 18.8, 0, 27.7),
(17, 'PARAM_20250522_f3ab5', 1.8, 1.1, 9.7, 2.3, 0, 2.2),
(18, 'PARAM_20250522_988df', 6.8, 25.7, 13.4, 21.5, 0, 26.6),
(19, 'PARAM_20250708_c11f1', 8.1, 21.8, 12.6, 7.6, 0, 12.4),
(20, 'PARAM_20250717_83756', 8.2, 5.9, 6.9, 2.3, 0, 16.4),
(21, 'PARAM_20250717_cb661', 2.6, 15.1, 16, 21.2, 23.08, 20.7),
(22, 'PARAM_20250717_9248f', 16.5, 2.2, 26.5, 23.1, 23.89, 29.8),
(23, 'PARAM_20250717_a7d06', 10.9, 29.7, 24.8, 7.9, 0, 29.3),
(24, 'PARAM_20250718_1ce2e', 25.62, 26.7, 7.43, 27.1, 3.34, 27.4),
(33, 'PARAM_20250718_1333e', 9.35, 1.5, 31.25, 64.43, 60, 102.1),
(34, 'PARAM_20250718_210a5', 6.81, 1, 0, 13.66, 40, 67.4),
(35, 'PARAM_20250718_dd40b', 6.81, 1, 0, 13.66, 40, 67.4),
(36, 'PARAM_20250718_c09f9', 1.15, 5, 18.65, 24.83, 60, 84.8),
(38, 'PARAM_20250718_9ec7c', 1.15, 5, 18.65, 24.83, 60, 84.8),
(39, 'PARAM_20250718_18709', 6.93, 2, 0, 14.7, 25, 53),
(40, 'PARAM_20250718_634d7', 4.68, 2, 26.31, 28.51, 40, 73.49),
(41, 'PARAM_20250718_1fe34', 6.04, 5.96, 0, 30.73, 65, 107.7),
(42, 'PARAM_20250721_15806', 3.27, 3, 14.77, 15.25, 35, 74.59),
(43, 'PARAM_20250721_73997', 3.27, 3, 14.77, 15.25, 35, 74.59),
(44, 'PARAM_20250731_9b44f', 1.5, 13.5, 17.7, 10.6, 19, 22.1),
(45, 'PARAM_20250731_a2a58', 22.4, 7.9, 29.4, 15.3, 67.9, 13.8),
(46, 'PARAM_20250731_9f4a2', 25.7, 3.6, 5, 8.7, 7.9, 20.4),
(47, 'PARAM_20250731_efacc', 25.7, 3.6, 5, 8.7, 7.9, 20.4),
(48, 'PARAM_20250731_7144f', 25.7, 3.6, 5, 8.7, 7.9, 20.4),
(49, 'PARAM_20250731_3be2b', 25.7, 3.6, 5, 8.7, 7.9, 20.4),
(50, 'PARAM_20250731_da614', 3.4, 9.6, 29.6, 7.3, 6, 3.2),
(51, 'PARAM_20250731_ea370', 12.8, 25.7, 23.4, 20.8, 12.9, 27.1),
(52, 'PARAM_20250731_bf47c', 5.5, 16.3, 19.2, 13.1, 8, 17.5),
(53, 'PARAM_20250731_9d7d3', 7.3, 16.1, 2.1, 18.9, 0, 13.1),
(54, 'PARAM_20250731_50626', 20.8, 25.9, 9.3, 14.1, 2, 12.2),
(55, 'PARAM_20250806_0bf3f', 20.8, 25.9, 9.3, 14.1, 2, 12.2),
(56, 'PARAM_20250806_b3e70', 17.4, 21.7, 16.2, 5, 0, 23.4),
(57, 'PARAM_20250806_89709', 17.4, 21.7, 16.2, 5, 0, 23.4),
(58, 'PARAM_20250806_6a12f', 29.1, 20.8, 9.2, 28.4, 0, 21.6);

-- --------------------------------------------------------

--
-- Table structure for table `user_activity`
--

CREATE TABLE `user_activity` (
  `id` int(11) NOT NULL,
  `user_id_number` varchar(10) DEFAULT NULL,
  `full_name` varchar(255) DEFAULT NULL,
  `activity_type` varchar(100) DEFAULT NULL,
  `record_id` varchar(50) DEFAULT NULL,
  `session_data` text DEFAULT NULL,
  `additional_info` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_activity`
--

INSERT INTO `user_activity` (`id`, `user_id_number`, `full_name`, `activity_type`, `record_id`, `session_data`, `additional_info`, `ip_address`, `user_agent`, `created_at`) VALUES
(1, '000000', 'Aeron Paul Daliva', 'PARAMETER_SUBMISSION', 'PARAM_20250731_9b44f', '{\"id_number\":\"000000\",\"full_name\":\"Aeron Paul Daliva\",\"role\":\"admin\",\"last_activity\":1753921259}', 'Title: SUM 260C - Phone Case', '131.226.100.155', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', '2025-07-31 00:20:59'),
(2, '000000', 'Aeron Paul Daliva', 'PARAMETER_SUBMISSION', 'PARAM_20250731_a2a58', '{\"id_number\":\"000000\",\"full_name\":\"Aeron Paul Daliva\",\"role\":\"admin\",\"last_activity\":1753923002}', 'Title: CLF 750A - Food Container', '131.226.100.155', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', '2025-07-31 00:50:02'),
(3, '000000', 'Aeron Paul Daliva', 'PARAMETER_SUBMISSION', 'PARAM_20250731_9f4a2', '{\"id_number\":\"000000\",\"full_name\":\"Aeron Paul Daliva\",\"role\":\"admin\",\"last_activity\":1753926811}', 'Title: TOS 650A - Toy Box', '131.226.100.155', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', '2025-07-31 01:53:31'),
(4, '000000', 'Aeron Paul Daliva', 'PARAMETER_SUBMISSION', 'PARAM_20250731_efacc', '{\"id_number\":\"000000\",\"full_name\":\"Aeron Paul Daliva\",\"role\":\"admin\",\"last_activity\":1753927056}', 'Title: TOS 650A - Toy Box', '131.226.100.155', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', '2025-07-31 01:57:36'),
(5, '000000', 'Aeron Paul Daliva', 'PARAMETER_SUBMISSION', 'PARAM_20250731_7144f', '{\"id_number\":\"000000\",\"full_name\":\"Aeron Paul Daliva\",\"role\":\"admin\",\"last_activity\":1753927189}', 'Title: TOS 650A - Toy Box', '131.226.100.155', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', '2025-07-31 01:59:49'),
(6, '000000', 'Aeron Paul Daliva', 'PARAMETER_SUBMISSION', 'PARAM_20250731_3be2b', '{\"id_number\":\"000000\",\"full_name\":\"Aeron Paul Daliva\",\"role\":\"admin\",\"last_activity\":1753927241}', 'Title: TOS 650A - Toy Box', '131.226.100.155', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', '2025-07-31 02:00:41'),
(7, '000000', 'Aeron Paul Daliva', 'PARAMETER_SUBMISSION', 'PARAM_20250731_da614', '{\"id_number\":\"000000\",\"full_name\":\"Aeron Paul Daliva\",\"role\":\"admin\",\"last_activity\":1753927860}', 'Title: TOS 850B - 1L SHELL PLASTIC CRATE (PEPSI)', '131.226.100.155', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', '2025-07-31 02:11:00'),
(8, '000000', 'Aeron Paul Daliva', 'PARAMETER_SUBMISSION', 'PARAM_20250731_ea370', '{\"id_number\":\"000000\",\"full_name\":\"Aeron Paul Daliva\",\"role\":\"admin\",\"last_activity\":1753931864}', 'Title: CLF 950A - 1L PEPSI', '131.226.102.26', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', '2025-07-31 03:17:44'),
(9, '000000', 'Aeron Paul Daliva', 'PARAMETER_SUBMISSION', 'PARAM_20250731_bf47c', '{\"id_number\":\"000000\",\"full_name\":\"Aeron Paul Daliva\",\"role\":\"admin\",\"last_activity\":1753932250}', 'Title: ARB 50 - Food Container', '131.226.102.26', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', '2025-07-31 03:24:10'),
(10, '000000', 'Aeron Paul Daliva', 'PARAMETER_SUBMISSION', 'PARAM_20250731_9d7d3', '{\"id_number\":\"000000\",\"full_name\":\"Aeron Paul Daliva\",\"role\":\"admin\",\"last_activity\":1753932356}', 'Title: ARB 50 - Phone Case', '131.226.102.26', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', '2025-07-31 03:25:56'),
(11, '000000', 'Aeron Paul Daliva', 'PARAMETER_SUBMISSION', 'PARAM_20250731_50626', '{\"id_number\":\"000000\",\"full_name\":\"Aeron Paul Daliva\",\"role\":\"admin\",\"last_activity\":1753933323}', 'Title: MIT 1050B - CNS-3C', '131.226.102.26', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', '2025-07-31 03:42:03'),
(12, '000000', 'Aeron Paul Daliva', 'PARAMETER_SUBMISSION', 'PARAM_20250806_0bf3f', '{\"id_number\":\"000000\",\"full_name\":\"Aeron Paul Daliva\",\"role\":\"admin\",\"last_activity\":1754443018}', 'Title: MIT 1050B - CNS-3C', '131.226.102.49', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', '2025-08-06 01:16:58'),
(13, '000000', 'Aeron Paul Daliva', 'PARAMETER_SUBMISSION', 'PARAM_20250806_b3e70', '{\"id_number\":\"000000\",\"full_name\":\"Aeron Paul Daliva\",\"role\":\"admin\",\"last_activity\":1754494446}', 'Title: TOS 850C - Phone Case', '180.190.212.107', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', '2025-08-06 15:34:06'),
(14, '000000', 'Aeron Paul Daliva', 'PARAMETER_SUBMISSION', 'PARAM_20250806_89709', '{\"id_number\":\"000000\",\"full_name\":\"Aeron Paul Daliva\",\"role\":\"admin\",\"last_activity\":1754494617}', 'Title: TOS 850C - Phone Case', '180.190.212.107', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', '2025-08-06 15:36:57'),
(15, '000000', 'Aeron Paul Daliva', 'PARAMETER_SUBMISSION', 'PARAM_20250806_6a12f', '{\"id_number\":\"000000\",\"full_name\":\"Aeron Paul Daliva\",\"role\":\"admin\",\"last_activity\":1754494869}', 'Title: ARB 50 - Bottle Cap', '180.190.212.107', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', '2025-08-06 15:41:09');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `additionalinformation`
--
ALTER TABLE `additionalinformation`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_record_id_additionalinformation` (`record_id`);

--
-- Indexes for table `attachments`
--
ALTER TABLE `attachments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_record_id_attachments` (`record_id`);

--
-- Indexes for table `barrelheatertemperatures`
--
ALTER TABLE `barrelheatertemperatures`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_record_id_barrelheatertemperatures` (`record_id`);

--
-- Indexes for table `colorantdetails`
--
ALTER TABLE `colorantdetails`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_record_id_colorantdetails` (`record_id`);

--
-- Indexes for table `corepullsettings`
--
ALTER TABLE `corepullsettings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_record_id_corepullsettings` (`record_id`);

--
-- Indexes for table `ejectionparameters`
--
ALTER TABLE `ejectionparameters`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_record_id_ejectionparameters` (`record_id`);

--
-- Indexes for table `injectionparameters`
--
ALTER TABLE `injectionparameters`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_record_id_injectionparameters` (`record_id`);

--
-- Indexes for table `materialcomposition`
--
ALTER TABLE `materialcomposition`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_record_id_materialcomposition` (`record_id`);

--
-- Indexes for table `moldcloseparameters`
--
ALTER TABLE `moldcloseparameters`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_record_id_moldclose` (`record_id`);

--
-- Indexes for table `moldheatertemperatures`
--
ALTER TABLE `moldheatertemperatures`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_record_id_moldheatertemperatures` (`record_id`);

--
-- Indexes for table `moldopenparameters`
--
ALTER TABLE `moldopenparameters`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_record_id_moldopen` (`record_id`);

--
-- Indexes for table `moldoperationspecs`
--
ALTER TABLE `moldoperationspecs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_record_id_moldoperationspecs` (`record_id`);

--
-- Indexes for table `parameters_submissions`
--
ALTER TABLE `parameters_submissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `parameter_records`
--
ALTER TABLE `parameter_records`
  ADD PRIMARY KEY (`record_id`);

--
-- Indexes for table `personnel`
--
ALTER TABLE `personnel`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_record_id_personnel` (`record_id`);

--
-- Indexes for table `plasticizingparameters`
--
ALTER TABLE `plasticizingparameters`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_record_id_plasticizingparameters` (`record_id`);

--
-- Indexes for table `productdetails`
--
ALTER TABLE `productdetails`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_record_id_productdetails` (`record_id`);

--
-- Indexes for table `productmachineinfo`
--
ALTER TABLE `productmachineinfo`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_record_id_productmachineinfo` (`record_id`);

--
-- Indexes for table `timerparameters`
--
ALTER TABLE `timerparameters`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_record_id_timerparameters` (`record_id`);

--
-- Indexes for table `user_activity`
--
ALTER TABLE `user_activity`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `additionalinformation`
--
ALTER TABLE `additionalinformation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `attachments`
--
ALTER TABLE `attachments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `barrelheatertemperatures`
--
ALTER TABLE `barrelheatertemperatures`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `colorantdetails`
--
ALTER TABLE `colorantdetails`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `corepullsettings`
--
ALTER TABLE `corepullsettings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=128;

--
-- AUTO_INCREMENT for table `ejectionparameters`
--
ALTER TABLE `ejectionparameters`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `injectionparameters`
--
ALTER TABLE `injectionparameters`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `materialcomposition`
--
ALTER TABLE `materialcomposition`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `moldcloseparameters`
--
ALTER TABLE `moldcloseparameters`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `moldheatertemperatures`
--
ALTER TABLE `moldheatertemperatures`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `moldopenparameters`
--
ALTER TABLE `moldopenparameters`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `moldoperationspecs`
--
ALTER TABLE `moldoperationspecs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `parameters_submissions`
--
ALTER TABLE `parameters_submissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `personnel`
--
ALTER TABLE `personnel`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `plasticizingparameters`
--
ALTER TABLE `plasticizingparameters`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `productdetails`
--
ALTER TABLE `productdetails`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `productmachineinfo`
--
ALTER TABLE `productmachineinfo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `timerparameters`
--
ALTER TABLE `timerparameters`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `user_activity`
--
ALTER TABLE `user_activity`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
