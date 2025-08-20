-- phpMyAdmin SQL Dump
-- version 5.2.1deb3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Aug 13, 2025 at 04:52 AM
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
(41, 'PARAM_20250806_6a12f', 'Production run completed with standard parameters. All quality checks passed. Material batch: MB-7740'),
(42, 'PARAM_20250807_82ca5', 'Production run completed with standard parameters. All quality checks passed. Material batch: MB-4105'),
(43, 'PARAM_20250807_7b874', 'Production run completed with standard parameters. All quality checks passed. Material batch: MB-4105'),
(44, 'PARAM_20250807_ddbfd', 'Production run completed with standard parameters. All quality checks passed. Material batch: MB-7740'),
(45, 'PARAM_20250807_5b8d3', 'Production run completed with standard parameters. All quality checks passed. Material batch: MB-4105'),
(46, 'PARAM_20250807_1b470', 'Production run completed with standard parameters. All quality checks passed. Material batch: MB-4105'),
(47, 'PARAM_20250807_a902d', 'Production run completed with standard parameters. All quality checks passed. Material batch: MB-8149'),
(48, 'PARAM_20250807_3b128', 'Production run completed with standard parameters. All quality checks passed. Material batch: MB-8753'),
(49, 'PARAM_20250807_ce464', 'Production run completed with standard parameters. All quality checks passed. Material batch: MB-3170'),
(50, 'PARAM_20250808_36494', 'Production run completed with standard parameters. All quality checks passed. Material batch: MB-7349'),
(51, 'PARAM_20250808_4762e', 'Production run completed with standard parameters. All quality checks passed. Material batch: MB-2955'),
(52, 'PARAM_20250808_a29db', 'Production run completed with standard parameters. All quality checks passed. Material batch: MB-5092'),
(53, 'PARAM_20250808_5fcbc', 'Production run completed with standard parameters. All quality checks passed. Material batch: MB-7492'),
(54, 'PARAM_20250808_67af2', 'Production run completed with standard parameters. All quality checks passed. Material batch: MB-5482'),
(55, 'PARAM_20250808_3d7f0', 'Production run completed with standard parameters. All quality checks passed. Material batch: MB-2492'),
(56, 'PARAM_20250808_3bbba', 'Production run completed with standard parameters. All quality checks passed. Material batch: MB-9719'),
(57, 'PARAM_20250808_770b0', 'Production run completed with standard parameters. All quality checks passed. Material batch: MB-5901'),
(58, 'PARAM_20250808_1ef67', 'Production run completed with standard parameters. All quality checks passed. Material batch: MB-1226');

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

(41, 'PARAM_20250718_18709', '1000001238.jpg', 'parameters/uploads/_20250718_1328_MIT1050B__6879db8f18f69.jpg', 'image/jpeg', '2025-07-18 05:28:47'),
(42, 'PARAM_20250806_6a12f', 'IMG_20241004_113233_133.jpg', 'parameters/uploads/IRN35119_20250806_2339_ARB50_RN4741_689377956e7e4.jpg', 'image/jpeg', '2025-08-06 15:41:09'),
(43, 'PARAM_20250806_6a12f', 'Parameters - Data Entry - Brave 2025-07-26 08-19-27.mp4', 'parameters/uploads/IRN35119_20250806_2339_ARB50_RN4741_689377957056a.mp4', 'video/mp4', '2025-08-06 15:41:09'),
(44, 'PARAM_20250807_1b470', 'IMG_20241029_134322_915.jpg', 'parameters/uploads/IRN58664_20250807_2332_CLF950B_RN7072_6894c74724bf0.jpg', 'image/jpeg', '2025-08-07 15:33:27'),
(45, 'PARAM_20250807_1b470', 'Parameters - Data Entry - Brave 2025-07-26 08-19-27.mp4', 'parameters/uploads/IRN58664_20250807_2332_CLF950B_RN7072_6894c7472574e.mp4', 'video/mp4', '2025-08-07 15:33:27');

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
(58, 'PARAM_20250806_6a12f', 56, 206.7, 66.6, 246, 88.9, 214, 162.2, 72, 60.7, 180.1, 60.1, 187, 241.5, 207.7, 106.8, 129, 220.5),
(59, 'PARAM_20250807_82ca5', 70.6, 44.6, 65, 155.3, 185.8, 232.1, 148.5, 73.8, 68.2, 77.2, 160.6, 227.6, 209.2, 69.3, 39.5, 191.3, 74.7),
(60, 'PARAM_20250807_7b874', 70.6, 146.4, 117.2, 33.8, 106.2, 72.7, 213.7, 162.6, 150.5, 247.6, 116.7, 187.4, 56.5, 135.1, 117.8, 159.1, 105.1),
(61, 'PARAM_20250807_ddbfd', 56, 45.7, 53.2, 133.6, 189.3, 89.3, 226.4, 112.5, 82.5, 62.5, 223.2, 171.3, 155.1, 156.4, 246.5, 191.9, 144.7),
(62, 'PARAM_20250807_5b8d3', 70.6, 146.4, 117.2, 33.8, 106.2, 72.7, 213.7, 162.6, 150.5, 247.6, 116.7, 187.4, 56.5, 135.1, 117.8, 159.1, 105.1),
(63, 'PARAM_20250807_1b470', 70.6, 146.4, 117.2, 33.8, 106.2, 72.7, 213.7, 162.6, 150.5, 247.6, 116.7, 187.4, 56.5, 135.1, 117.8, 159.1, 105.1),
(64, 'PARAM_20250807_a902d', 194.8, 97.8, 161.8, 114, 90.1, 162.5, 161, 114.2, 44.9, 134.3, 33.5, 165.4, 56.4, 34.2, 38.3, 215.1, 125.7),
(65, 'PARAM_20250807_3b128', 154.8, 216, 232.1, 164.6, 38.3, 97.7, 150.3, 152.8, 151.5, 96.9, 200.4, 134.3, 41.5, 197.5, 101.6, 81.4, 158.1),
(66, 'PARAM_20250807_ce464', 115, 97.7, 57.5, 247.1, 214.8, 51.4, 233.5, 221.8, 187, 195.7, 130, 121.2, 52.4, 56.1, 39.7, 213.7, 68.2),
(67, 'PARAM_20250808_36494', 101.6, 131.6, 227.9, 117.9, 215.4, 167.7, 134, 157.9, 135.1, 214.1, 154.7, 177.7, 168.9, 66.5, 67.5, 241.9, 210.6),
(68, 'PARAM_20250808_4762e', 160.5, 142.5, 244.7, 50.6, 152.9, 78.4, 177.3, 119.8, 47.1, 31.8, 247.8, 165, 46.5, 34.8, 181.9, 227.1, 117),
(69, 'PARAM_20250808_a29db', 214.9, 65.5, 187.4, 194.4, 97.1, 121.7, 53.1, 136.3, 222.7, 236.7, 120, 226.2, 34.9, 133.2, 236.6, 205, 54.3),
(70, 'PARAM_20250808_5fcbc', 135.6, 236.2, 51.8, 89.4, 102.6, 49.6, 107.9, 102.6, 177.6, 153.5, 198.8, 231.1, 226.8, 96.7, 62.6, 175.8, 39.1),
(71, 'PARAM_20250808_67af2', 153, 128.1, 191.1, 171.3, 183.5, 41, 45.3, 65.1, 91.3, 50.6, 34.2, 228.3, 242.1, 196.1, 159.6, 58.2, 103.8),
(72, 'PARAM_20250808_3d7f0', 190.4, 195.8, 135.6, 107.8, 37.8, 32.4, 48.1, 196.5, 151.8, 230.4, 134, 206.7, 175.3, 188.5, 54, 43.7, 128.7),
(73, 'PARAM_20250808_3bbba', 139.5, 117.8, 224.6, 74.2, 240.2, 229.6, 213.9, 77.5, 193.5, 200.7, 154.5, 174.2, 79.1, 247.7, 227.2, 198, 115.9),
(74, 'PARAM_20250808_770b0', 184.2, 50.9, 245.1, 222.8, 159.7, 202.7, 94.5, 79.9, 164.1, 75.7, 74.7, 199, 86.8, 176.4, 245.8, 35.6, 137.8),
(75, 'PARAM_20250808_1ef67', 167.5, 248.2, 243, 45.3, 215.8, 236.9, 38.8, 177.9, 33.1, 229.1, 154.5, 145, 233.1, 130.7, 36.6, 101.9, 151.7);

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
(58, 'PARAM_20250806_6a12f', 'Clear', 'Green', 'Blue', 'Yellow', 'Black'),
(59, 'PARAM_20250807_82ca5', 'Black', 'Black', 'Green', 'Green', 'Yellow'),
(60, 'PARAM_20250807_7b874', 'Black', 'Black', 'Green', 'Green', 'Yellow'),
(61, 'PARAM_20250807_ddbfd', 'Clear', 'Green', 'Blue', 'Yellow', 'Black'),
(62, 'PARAM_20250807_5b8d3', 'Black', 'Black', 'Green', 'Green', 'Yellow'),
(63, 'PARAM_20250807_1b470', 'Black', 'Black', 'Green', 'Green', 'Yellow'),
(64, 'PARAM_20250807_a902d', 'Clear', 'Red', 'Gray', 'White', 'Blue'),
(65, 'PARAM_20250807_3b128', 'Blue', 'White', 'Black', 'Clear', 'White'),
(66, 'PARAM_20250807_ce464', 'Yellow', 'Green', 'Red', 'Green', 'Red'),
(67, 'PARAM_20250808_36494', 'Black', 'Black', 'Black', 'Yellow', 'White'),
(68, 'PARAM_20250808_4762e', 'Clear', 'White', 'Yellow', 'Gray', 'Red'),
(69, 'PARAM_20250808_a29db', 'Blue', 'Blue', 'Yellow', 'Clear', 'Green'),
(70, 'PARAM_20250808_5fcbc', 'Green', 'Yellow', 'Green', 'Clear', 'Red'),
(71, 'PARAM_20250808_67af2', 'Yellow', 'Yellow', 'Gray', 'Black', 'Clear'),
(72, 'PARAM_20250808_3d7f0', 'Black', 'Gray', 'Clear', 'Gray', 'Blue'),
(73, 'PARAM_20250808_3bbba', 'Gray', 'White', 'White', 'Yellow', 'Clear'),
(74, 'PARAM_20250808_770b0', 'Gray', 'White', 'Green', 'Yellow', 'Yellow'),
(75, 'PARAM_20250808_1ef67', 'Gray', 'White', 'Gray', 'Gray', 'Green');

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
(127, 'PARAM_20250806_6a12f', 'Core Pull B', 54, 168.7, 89, 43.29, 22.2, '66.08'),
(128, 'PARAM_20250807_82ca5', 'Core Set A', 79, 53.1, 73, 12.18, 16.9, '60.52'),
(129, 'PARAM_20250807_82ca5', 'Core Pull A', 49, 17.7, 86.7, 97.66, 15.6, '32.15'),
(130, 'PARAM_20250807_82ca5', 'Core Set B', 91, 180.1, 57.6, 77.33, 12.1, '12.13'),
(131, 'PARAM_20250807_82ca5', 'Core Pull B', 88, 25.8, 64.1, 14.55, 11.9, '90.52'),
(132, 'PARAM_20250807_ddbfd', 'Core Set A', 59, 46.8, 80.2, 79.43, 21.4, '26.82'),
(133, 'PARAM_20250807_ddbfd', 'Core Pull A', 76, 113.9, 31.1, 128.61, 3.5, '53.83'),
(134, 'PARAM_20250807_ddbfd', 'Core Set B', 80, 145, 81.3, 78.75, 10.7, '42.08'),
(135, 'PARAM_20250807_ddbfd', 'Core Pull B', 54, 168.7, 89, 43.29, 22.2, '66.08'),
(136, 'PARAM_20250807_5b8d3', 'Core Set A', 79, 53.1, 73, 12.18, 16.9, '60.52'),
(137, 'PARAM_20250807_5b8d3', 'Core Pull A', 49, 17.7, 86.7, 97.66, 15.6, '32.15'),
(138, 'PARAM_20250807_5b8d3', 'Core Set B', 91, 180.1, 57.6, 77.33, 12.1, '12.13'),
(139, 'PARAM_20250807_5b8d3', 'Core Pull B', 88, 25.8, 64.1, 14.55, 11.9, '90.52'),
(140, 'PARAM_20250807_1b470', 'Core Set A', 79, 53.1, 73, 12.18, 16.9, '60.52'),
(141, 'PARAM_20250807_1b470', 'Core Pull A', 49, 17.7, 86.7, 97.66, 15.6, '32.15'),
(142, 'PARAM_20250807_1b470', 'Core Set B', 91, 180.1, 57.6, 77.33, 12.1, '12.13'),
(143, 'PARAM_20250807_1b470', 'Core Pull B', 88, 25.8, 64.1, 14.55, 11.9, '90.52'),
(144, 'PARAM_20250807_a902d', 'Core Set A', 10, 132, 17, 39.82, 27.5, '1'),
(145, 'PARAM_20250807_a902d', 'Core Pull A', 4, 105.5, 77.7, 90.65, 26.1, '1'),
(146, 'PARAM_20250807_a902d', 'Core Set B', 10, 15.4, 98.8, 63.19, 29.9, '1'),
(147, 'PARAM_20250807_a902d', 'Core Pull B', 9, 193.6, 76.5, 124.75, 20.6, '0'),
(148, 'PARAM_20250807_3b128', 'Core Set A', 3, 193.4, 86.5, 84.11, 17.1, '1'),
(149, 'PARAM_20250807_3b128', 'Core Pull A', 3, 21.6, 45.8, 107.22, 10.6, '1'),
(150, 'PARAM_20250807_3b128', 'Core Set B', 2, 45.8, 57.6, 133.42, 6.8, '1'),
(151, 'PARAM_20250807_3b128', 'Core Pull B', 2, 140.7, 74.6, 107.11, 19.8, '0'),
(152, 'PARAM_20250807_ce464', 'Core Set A', 6, 44.1, 18.8, 90.85, 6.4, '1'),
(153, 'PARAM_20250807_ce464', 'Core Pull A', 10, 192.6, 23.9, 69.32, 7.8, '0'),
(154, 'PARAM_20250807_ce464', 'Core Set B', 2, 105.7, 64, 104.25, 26.5, '0'),
(155, 'PARAM_20250807_ce464', 'Core Pull B', 10, 25, 85, 120.63, 29.9, '1'),
(156, 'PARAM_20250808_36494', 'Core Set A', 5, 37.4, 76.2, 89.06, 1.4, '0'),
(157, 'PARAM_20250808_36494', 'Core Pull A', 7, 170, 37.4, 76.03, 20.5, '0'),
(158, 'PARAM_20250808_36494', 'Core Set B', 8, 123.7, 93, 98.31, 2.9, '1'),
(159, 'PARAM_20250808_36494', 'Core Pull B', 4, 187.3, 50.3, 80.31, 19.4, '1'),
(160, 'PARAM_20250808_4762e', 'Core Set A', 10, 145.1, 27.4, 48.11, 1.4, '0'),
(161, 'PARAM_20250808_4762e', 'Core Pull A', 10, 168.7, 68, 15.11, 2.2, '0'),
(162, 'PARAM_20250808_4762e', 'Core Set B', 9, 50.3, 62.6, 109.14, 30, '0'),
(163, 'PARAM_20250808_4762e', 'Core Pull B', 8, 197.5, 19.6, 70.29, 28.5, '0'),
(164, 'PARAM_20250808_a29db', 'Core Set A', 10, 74.4, 41.9, 91.52, 2.7, '1'),
(165, 'PARAM_20250808_a29db', 'Core Pull A', 8, 172.8, 39, 133.18, 12.7, '0'),
(166, 'PARAM_20250808_a29db', 'Core Set B', 5, 10.3, 32.1, 45.66, 18.1, '0'),
(167, 'PARAM_20250808_a29db', 'Core Pull B', 9, 42.9, 94.7, 99.9, 16.4, '1'),
(168, 'PARAM_20250808_5fcbc', 'Core Set A', 9, 122.1, 33, 95.82, 27, '0'),
(169, 'PARAM_20250808_5fcbc', 'Core Pull A', 6, 162, 25.6, 19.61, 18.8, '1'),
(170, 'PARAM_20250808_5fcbc', 'Core Set B', 5, 52.5, 17.4, 11.83, 17.9, '0'),
(171, 'PARAM_20250808_5fcbc', 'Core Pull B', 6, 106, 90.7, 110.55, 20, '0'),
(172, 'PARAM_20250808_67af2', 'Core Set A', 3, 138.5, 80.4, 130.08, 4, '0'),
(173, 'PARAM_20250808_67af2', 'Core Pull A', 2, 48.6, 50.3, 16.19, 16.6, '0'),
(174, 'PARAM_20250808_67af2', 'Core Set B', 3, 13.3, 61.9, 56.55, 5, '0'),
(175, 'PARAM_20250808_67af2', 'Core Pull B', 4, 111.3, 99.2, 135.5, 11.1, '0'),
(176, 'PARAM_20250808_3d7f0', 'Core Set A', 5, 85.3, 42.8, 118.79, 9, '1'),
(177, 'PARAM_20250808_3d7f0', 'Core Pull A', 7, 59.8, 24.7, 73.13, 19.9, '0'),
(178, 'PARAM_20250808_3d7f0', 'Core Set B', 1, 120.8, 78.2, 72.03, 8, '1'),
(179, 'PARAM_20250808_3d7f0', 'Core Pull B', 10, 150.4, 56.7, 71.78, 24.2, '0'),
(180, 'PARAM_20250808_3bbba', 'Core Set A', 3, 122.9, 42.5, 75.12, 10.1, '1'),
(181, 'PARAM_20250808_3bbba', 'Core Pull A', 8, 156.2, 81.5, 50.95, 24.6, '1'),
(182, 'PARAM_20250808_3bbba', 'Core Set B', 10, 165.4, 97.8, 93.99, 1.5, '1'),
(183, 'PARAM_20250808_3bbba', 'Core Pull B', 1, 23.3, 36.4, 55.82, 15, '0'),
(184, 'PARAM_20250808_770b0', 'Core Set A', 4, 62.5, 78.6, 109.97, 10.6, '0'),
(185, 'PARAM_20250808_770b0', 'Core Pull A', 4, 11.4, 31.6, 30.79, 16.3, '1'),
(186, 'PARAM_20250808_770b0', 'Core Set B', 6, 54.2, 40.4, 49.21, 19.7, '1'),
(187, 'PARAM_20250808_770b0', 'Core Pull B', 10, 30.5, 77.6, 68.37, 16.1, '1'),
(188, 'PARAM_20250808_1ef67', 'Core Set A', 5, 102.3, 32, 72.43, 24.1, '0'),
(189, 'PARAM_20250808_1ef67', 'Core Pull A', 6, 21.8, 75.4, 79.45, 17.1, '0'),
(190, 'PARAM_20250808_1ef67', 'Core Set B', 7, 197.4, 13.1, 140.2, 28.4, '0'),
(191, 'PARAM_20250808_1ef67', 'Core Pull B', 10, 71.7, 46.8, 89.79, 23.5, '1');

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
  `EjectorRetractPressure1` float DEFAULT NULL,
  `EjectorForwardTime` float DEFAULT NULL,
  `EjectorRetractTime` float DEFAULT NULL,
  `EjectorForwardPressure2` float DEFAULT NULL,
  `EjectorRetractPressure2` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ejectionparameters`
--

INSERT INTO `ejectionparameters` (`id`, `record_id`, `AirBlowTimeA`, `AirBlowPositionA`, `AirBlowADelay`, `AirBlowTimeB`, `AirBlowPositionB`, `AirBlowBDelay`, `EjectorForwardPosition1`, `EjectorForwardPosition2`, `EjectorForwardSpeed1`, `EjectorRetractPosition1`, `EjectorRetractPosition2`, `EjectorRetractSpeed1`, `EjectorForwardSpeed2`, `EjectorForwardPressure1`, `EjectorRetractSpeed2`, `EjectorRetractPressure1`, `EjectorForwardTime`, `EjectorRetractTime`, `EjectorForwardPressure2`, `EjectorRetractPressure2`) VALUES
(13, 'PARAM_20250522_988df', 16.6, 15.78, 74.34, 8, 112.89, 70.43, 70.88, 147.32, 38.7, 137.19, 57.83, 63.1, 49.9, 22.6, 66.2, 15, NULL, NULL, NULL, NULL),
(28, 'PARAM_20250718_1333e', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL),
(29, 'PARAM_20250718_210a5', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL),
(30, 'PARAM_20250718_dd40b', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL),
(32, 'PARAM_20250718_9ec7c', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL),
(33, 'PARAM_20250718_18709', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL),
(34, 'PARAM_20250718_634d7', 0, 0, 0, 0, 0, 0, 30, 0, 0, 65, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL),
(35, 'PARAM_20250718_1fe34', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL),
(36, 'PARAM_20250721_15806', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL),
(37, 'PARAM_20250721_73997', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL),
(38, 'PARAM_20250731_9b44f', 7.6, 126.04, 31.34, 22.1, 137.5, 38.78, 146.17, 101.72, 59.5, 16.99, 45.51, 12.1, 32.9, 11.2, 17.6, 102.1, NULL, NULL, NULL, NULL),
(39, 'PARAM_20250731_a2a58', 16.1, 149.01, 21.25, 22, 142.39, 92.7, 10.04, 81.73, 65, 70.25, 113.52, 63.2, 30, 19.9, 68.3, 187.5, NULL, NULL, NULL, NULL),
(40, 'PARAM_20250731_3be2b', 23.3, 134.37, 61.61, 18.3, 13.82, 80.77, 74.15, 57.69, 88.6, 103.62, 132.19, 81, 65.8, 11.3, 92.4, 162.7, NULL, NULL, NULL, NULL),
(41, 'PARAM_20250731_da614', 20.2, 123.07, 70.77, 19.9, 31.94, 82.88, 86.45, 37, 34.1, 97.18, 49.01, 17.8, 71.5, 183.7, 46.1, 45.6, NULL, NULL, NULL, NULL),
(42, 'PARAM_20250731_ea370', 24.1, 113.37, 65.78, 11, 125.1, 61.84, 37.44, 58.43, 27.3, 90.04, 64.98, 54.2, 70.5, 90, 12, 68.3, NULL, NULL, NULL, NULL),
(43, 'PARAM_20250731_bf47c', 28.9, 127.57, 78.67, 5, 105.01, 59.97, 46.82, 78.78, 90.2, 84.24, 26.94, 91.7, 44.9, 134.2, 29.9, 160.4, NULL, NULL, NULL, NULL),
(44, 'PARAM_20250731_9d7d3', 13.4, 115.28, 86.87, 22.6, 32.03, 47.45, 130.37, 113.19, 79.9, 44.48, 140.63, 52.9, 90, 129.9, 49.8, 198.4, NULL, NULL, NULL, NULL),
(45, 'PARAM_20250731_50626', 25.7, 48.66, 36.08, 20.1, 103.12, 26.68, 20.66, 117.47, 86.3, 20.3, 142.28, 61.2, 91.4, 44.1, 50, 182.9, NULL, NULL, NULL, NULL),
(46, 'PARAM_20250806_0bf3f', 25.7, 48.66, 36.08, 20.1, 103.12, 26.68, 20.66, 117.47, 86.3, 20.3, 142.28, 61.2, 0, 44.1, 0, 182.9, NULL, NULL, NULL, NULL),
(47, 'PARAM_20250806_b3e70', 8.9, 12.58, 49.88, 12.9, 111.53, 7.38, 41.26, 53.96, 85.7, 107.47, 140.83, 33.3, 89, 45.4, 52.8, 186.8, NULL, NULL, NULL, NULL),
(48, 'PARAM_20250806_89709', 8.9, 12.58, 49.88, 12.9, 111.53, 7.38, 41.26, 53.96, 85.7, 107.47, 140.83, 33.3, 0, 45.4, 0, 186.8, NULL, NULL, NULL, NULL),
(49, 'PARAM_20250806_6a12f', 28.5, 32.64, 31.51, 20, 66.98, 67.81, 68.01, 49.62, 72.6, 106.79, 51.86, 41.8, 32.9, 90.4, 92.9, 98.8, NULL, NULL, NULL, NULL),
(50, 'PARAM_20250807_82ca5', 3.7, 113.27, 68.22, 25.1, 49.14, 24.35, 102.88, 26.68, 26.9, 38.69, 94.07, 11.8, 53.3, 12.9, 55.1, 16.4, 7.8, 10.1, NULL, NULL),
(51, 'PARAM_20250807_7b874', 3.7, 113.27, 68.22, 25.1, 49.14, 24.35, 102.88, 26.68, 26.9, 38.69, 94.07, 11.8, 0, 12.9, 0, 16.4, 7.8, 10.1, NULL, NULL),
(52, 'PARAM_20250807_ddbfd', 28.5, 32.64, 31.51, 20, 66.98, 67.81, 68.01, 49.62, 72.6, 106.79, 51.86, 41.8, 0, 90.4, 0, 98.8, 0, 0, NULL, NULL),
(53, 'PARAM_20250807_5b8d3', 3.7, 113.27, 68.22, 25.1, 49.14, 24.35, 102.88, 26.68, 26.9, 38.69, 94.07, 11.8, 0, 12.9, 0, 16.4, 7.8, 10.1, NULL, NULL),
(54, 'PARAM_20250807_1b470', 3.7, 113.27, 68.22, 25.1, 49.14, 24.35, 102.88, 26.68, 26.9, 38.69, 94.07, 11.8, 0, 12.9, 0, 16.4, 7.8, 10.1, NULL, NULL),
(55, 'PARAM_20250807_a902d', 2.1, 72.52, 53.02, 26.7, 19.35, 27.48, 142.33, 22.53, 70.3, 35.82, 146.65, 31, 86, 134.2, 93.9, 137.1, 20.4, 23.8, NULL, NULL),
(56, 'PARAM_20250807_3b128', 27.7, 143.01, 30.65, 15, 107.5, 44.17, 98.2, 106.55, 41, 36.16, 69.36, 57.6, 51.3, 104.4, 76.7, 81, 27.8, 1, NULL, NULL),
(57, 'PARAM_20250807_ce464', 5.1, 118.15, 86.58, 17.6, 37.09, 25.57, 145.28, 12.58, 18.6, 30.14, 86.49, 88.7, 47.3, 156.3, 72.1, 162.6, 18.4, 4.7, NULL, NULL),
(58, 'PARAM_20250808_36494', 25.6, 79, 55.29, 28.3, 149.61, 19.81, 82.68, 42.47, 31.5, 41.64, 86.91, 99.2, 15.3, 42, 61.9, 23.5, 7.8, 22.2, NULL, NULL),
(59, 'PARAM_20250808_4762e', 12.9, 85.48, 77.04, 9, 138.79, 63.38, 71.32, 111.82, 86.3, 89.87, 118.36, 100, 22.6, 162.5, 15.5, 167.9, 9.4, 23, NULL, NULL),
(60, 'PARAM_20250808_a29db', 18.9, 116.9, 96.41, 3.3, 82.47, 20.77, 64.3, 87.59, 76, 93.97, 19.51, 18.6, 40.8, 158.7, 90.2, 153.3, 29.5, 26.4, NULL, NULL),
(61, 'PARAM_20250808_5fcbc', 29.9, 123.16, 20.63, 4.9, 18.24, 46.83, 25.58, 143.92, 78.1, 93.21, 100.44, 76.6, 18.4, 160.3, 16.6, 13.8, 11.7, 20.5, NULL, NULL),
(62, 'PARAM_20250808_67af2', 23.8, 100.47, 5.33, 4.3, 19.42, 73.74, 130.21, 68.12, 30.8, 137.83, 86.61, 52.1, 50.5, 166.4, 44.2, 53.4, 29.9, 27.8, NULL, NULL),
(63, 'PARAM_20250808_3d7f0', 6.2, 110.61, 79.64, 13.4, 78.84, 43.5, 140.57, 78.48, 74.8, 27.26, 59.69, 91.1, 71.8, 167.9, 24, 182.1, 28.2, 12.7, NULL, NULL),
(64, 'PARAM_20250808_3bbba', 19.5, 118.15, 77.16, 14.7, 90.92, 82.91, 108.62, 33.95, 71.8, 86.62, 74.95, 31.2, 54.5, 84.3, 53.9, 189.8, 1.9, 16.5, NULL, NULL),
(65, 'PARAM_20250808_770b0', 7.8, 113.85, 46.99, 11.7, 73.46, 12.72, 81.87, 18.83, 61.7, 52.23, 146.7, 99.8, 32, 149.6, 98.2, 10.5, 8.3, 24, NULL, NULL),
(66, 'PARAM_20250808_1ef67', 24.7, 71.56, 3.46, 28, 56.7, 76.96, 110.76, 39.15, 14.4, 20.88, 43.85, 94.3, 75, 139.8, 17.2, 10.6, 15.6, 19.6, NULL, NULL);

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
  `HoldingTime3` float DEFAULT NULL,
  `ScrewPosition4` float DEFAULT NULL,
  `ScrewPosition5` float DEFAULT NULL,
  `ScrewPosition6` float DEFAULT NULL,
  `InjectionSpeed4` float DEFAULT NULL,
  `InjectionSpeed5` float DEFAULT NULL,
  `InjectionSpeed6` float DEFAULT NULL,
  `InjectionPressure4` float DEFAULT NULL,
  `InjectionPressure5` float DEFAULT NULL,
  `InjectionPressure6` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `injectionparameters`
--

INSERT INTO `injectionparameters` (`id`, `record_id`, `RecoveryPosition`, `SecondStagePosition`, `Cushion`, `ScrewPosition1`, `ScrewPosition2`, `ScrewPosition3`, `InjectionSpeed1`, `InjectionSpeed2`, `InjectionSpeed3`, `InjectionPressure1`, `InjectionPressure2`, `InjectionPressure3`, `SuckBackPosition`, `SuckBackSpeed`, `SuckBackPressure`, `SprueBreak`, `SprueBreakTime`, `InjectionDelay`, `HoldingPressure1`, `HoldingPressure2`, `HoldingPressure3`, `HoldingSpeed1`, `HoldingSpeed2`, `HoldingSpeed3`, `HoldingTime1`, `HoldingTime2`, `HoldingTime3`, `ScrewPosition4`, `ScrewPosition5`, `ScrewPosition6`, `InjectionSpeed4`, `InjectionSpeed5`, `InjectionSpeed6`, `InjectionPressure4`, `InjectionPressure5`, `InjectionPressure6`) VALUES
(14, 'PARAM_20250522_988df', 71.74, 23.68, 4.86, 122.39, 20.88, 14.38, 21.8, 26.5, 38.4, 152.2, 124, 185.9, 29.68, 97.1, 148.6, 21.14, 11.7, 89.38, 199.5, 55.7, 186.2, 44.9, 16.3, 94.4, 16.4, 1.8, 3.4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(29, 'PARAM_20250718_1333e', 190, 7, 1, 150, 75, 30, 45, 45, 45, 42, 45, 50, 15, 0, 0, 0, 0, 0, 35, 35, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(30, 'PARAM_20250718_210a5', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(31, 'PARAM_20250718_dd40b', 133, 5, 0, 110, 45, 20, 50, 60, 65, 60, 60, 60, 15, 10, 15, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(33, 'PARAM_20250718_9ec7c', 305090000, 5, 0.55, 115, 20, 20, 99, 99, 99, 75, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(34, 'PARAM_20250718_18709', 222, 15, 0.1, 222, 170, 40, 65, 75, 5, 65, 70, 60, 15, 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(35, 'PARAM_20250718_634d7', 35, 16, 1.9, 305, 259, 90, 53, 85, 60, 60, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 20, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(36, 'PARAM_20250718_1fe34', 230, 11, 10.1, 230, 208, 80, 65, 70, 70, 70, 70, 70, 15, 0, 0, 0, 0, 0, 50, 40, 30, 25, 25, 25, 2, 2, 1.96, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(37, 'PARAM_20250721_15806', 115, 2, 0, 115, 60, 30, 99, 99, 99, 99, 99, 99, 15, 1, 0, 0, 0, 0, 25, 0, 0, 0, 0, 0, 3, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(38, 'PARAM_20250721_73997', 115, 2, 0, 115, 60, 30, 99, 99, 99, 99, 99, 99, 15, 1, 0, 0, 0, 0, 25, 0, 0, 0, 0, 0, 3, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(39, 'PARAM_20250731_9b44f', 132.01, 78.52, 9.47, 116.38, 24.58, 44.98, 88.4, 23.4, 47.7, 89.7, 141.6, 182.9, 146.49, 41.6, 167.4, 67.13, 15.7, 20.25, 39.8, 29.6, 72.2, 59.1, 96.9, 19.8, 18.5, 1.7, 6.6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(40, 'PARAM_20250731_a2a58', 37.51, 26.07, 5.53, 104.3, 108.73, 61.21, 70.7, 85.6, 68.1, 31.5, 130, 38.6, 122.34, 32.5, 87.1, 24.2, 5.8, 31.18, 97.5, 28.9, 134.1, 52.9, 17.8, 68.8, 13.2, 23.9, 0.5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(41, 'PARAM_20250731_3be2b', 78.02, 67.86, 7.99, 74.74, 13.58, 24.94, 51.4, 76, 64.4, 104.8, 110.4, 174.8, 55.9, 37.3, 136.8, 42.63, 25.9, 43.2, 158.9, 126.6, 96.5, 69.8, 91.1, 46.1, 18, 19.3, 16.7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(42, 'PARAM_20250731_da614', 89.86, 137.06, 7.56, 20.56, 120.84, 15.82, 53.3, 91.9, 74, 119.8, 150.5, 144.6, 33.27, 68.7, 35.4, 46.01, 28.7, 80.4, 184.7, 107.8, 110.6, 47.9, 53.5, 71.7, 22.4, 19, 27.7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(43, 'PARAM_20250731_ea370', 72.79, 16.17, 9.64, 63.71, 27.64, 88.67, 30.8, 55.1, 96.8, 149.2, 176, 199.9, 49.17, 51.6, 90.4, 71.01, 3.2, 33.03, 103.8, 169.5, 163.1, 72.9, 29.6, 22.1, 20.6, 23.1, 29.8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(44, 'PARAM_20250731_bf47c', 75.56, 58.53, 3.11, 46.91, 12.6, 66.54, 94.4, 53.3, 68.3, 25.6, 156.8, 46.4, 144.01, 74.7, 30.6, 2.86, 5, 47.72, 74.1, 194.6, 144.9, 89.8, 90.8, 65.5, 8.4, 0.7, 10, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(45, 'PARAM_20250731_9d7d3', 52.79, 143.93, 6.92, 14.61, 37.58, 20, 23.7, 79.6, 96.6, 183.8, 117.4, 161.4, 38.7, 31.7, 134.5, 93.94, 1.2, 60.29, 122.9, 145.8, 53.1, 45.6, 61.6, 69.9, 11.4, 29.8, 11.5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(46, 'PARAM_20250731_50626', 120.72, 59.11, 5.83, 35.1, 145.49, 128.3, 80.8, 44.8, 28.4, 60.3, 30.5, 119.6, 98.09, 71.5, 169.4, 39.96, 29.9, 23.41, 194, 198.2, 146, 32.8, 55.4, 49.8, 20.2, 13.4, 23.1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(47, 'PARAM_20250806_0bf3f', 120.72, 59.11, 5.83, 35.1, 145.49, 128.3, 80.8, 44.8, 28.4, 60.3, 30.5, 119.6, 98.09, 71.5, 169.4, 39.96, 29.9, 23.41, 194, 198.2, 146, 32.8, 55.4, 49.8, 20.2, 13.4, 23.1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(48, 'PARAM_20250806_b3e70', 87.64, 109.43, 9.17, 55.22, 47.82, 13.69, 28, 57.8, 33.4, 58.2, 59.2, 25.6, 77.3, 94.8, 166.8, 23.55, 2.8, 6.64, 157.3, 98.6, 57.5, 39.1, 82.5, 57.2, 12.6, 19.6, 18.8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(49, 'PARAM_20250806_89709', 87.64, 109.43, 9.17, 55.22, 47.82, 13.69, 28, 57.8, 33.4, 58.2, 59.2, 25.6, 77.3, 94.8, 166.8, 23.55, 2.8, 6.64, 157.3, 98.6, 57.5, 39.1, 82.5, 57.2, 12.6, 19.6, 18.8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(50, 'PARAM_20250806_6a12f', 141.61, 24.65, 4.6, 35.74, 10.45, 107.14, 40.1, 14.6, 62.3, 41.6, 80.7, 178.7, 48.07, 83.4, 36.6, 26.73, 24.4, 60.63, 32.1, 128.9, 19.5, 55.3, 80.1, 89.5, 9.9, 5.3, 16.8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(51, 'PARAM_20250807_82ca5', 124.71, 112.81, 6.36, 124.09, 24.45, 133, 76.2, 16.6, 78.2, 45.6, 113.6, 187.6, 12.67, 54.9, 134.9, 17.98, 16, 81.66, 167.9, 17.8, 61.3, 89.8, 20, 21.5, 12.5, 8.2, 19.6, 76.53, 115.17, 137.45, 81.5, 46.1, 27.7, 148.8, 99.3, 91.6),
(52, 'PARAM_20250807_7b874', 124.71, 112.81, 6.36, 124.09, 24.45, 133, 76.2, 16.6, 78.2, 45.6, 113.6, 187.6, 12.67, 54.9, 134.9, 17.98, 16, 81.66, 167.9, 17.8, 61.3, 89.8, 20, 21.5, 12.5, 8.2, 19.6, 76.53, 115.17, 137.45, 81.5, 46.1, 27.7, 148.8, 99.3, 91.6),
(53, 'PARAM_20250807_ddbfd', 141.61, 24.65, 4.6, 35.74, 10.45, 107.14, 40.1, 14.6, 62.3, 41.6, 80.7, 178.7, 48.07, 83.4, 36.6, 26.73, 24.4, 60.63, 32.1, 128.9, 19.5, 55.3, 80.1, 89.5, 9.9, 5.3, 16.8, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(54, 'PARAM_20250807_5b8d3', 124.71, 112.81, 6.36, 124.09, 24.45, 133, 76.2, 16.6, 78.2, 45.6, 113.6, 187.6, 12.67, 54.9, 134.9, 17.98, 16, 81.66, 167.9, 17.8, 61.3, 89.8, 20, 21.5, 12.5, 8.2, 19.6, 76.53, 115.17, 137.45, 81.5, 46.1, 27.7, 148.8, 99.3, 91.6),
(55, 'PARAM_20250807_1b470', 124.71, 112.81, 6.36, 124.09, 24.45, 133, 76.2, 16.6, 78.2, 45.6, 113.6, 187.6, 12.67, 54.9, 134.9, 17.98, 16, 81.66, 167.9, 17.8, 61.3, 89.8, 20, 21.5, 12.5, 8.2, 19.6, 76.53, 115.17, 137.45, 81.5, 46.1, 27.7, 148.8, 99.3, 91.6),
(56, 'PARAM_20250807_a902d', 64.71, 26.37, 5.37, 95.45, 85.68, 18.38, 55.8, 79.3, 15.1, 196.8, 95.5, 20.7, 96.39, 16.8, 177.5, 52.32, 29.4, 58.03, 105.7, 160.8, 124.9, 14.9, 94.8, 42.3, 28.2, 16.8, 28.8, 34.98, 94.81, 128.21, 38.4, 29, 88.9, 132.8, 172.3, 68.9),
(57, 'PARAM_20250807_3b128', 94.76, 78.03, 4.93, 126.33, 126.61, 39.02, 30.9, 45.6, 15.3, 12.2, 168, 144.9, 111.76, 62.8, 175.5, 62.25, 16.9, 68.72, 42.2, 102.1, 81.7, 61.6, 69.3, 74.9, 2.6, 1.3, 4.4, 87.57, 108.11, 78.81, 47.5, 95.3, 55.7, 108.6, 175.6, 171.8),
(58, 'PARAM_20250807_ce464', 36.3, 40.27, 3.19, 58.12, 32.84, 83.78, 76.5, 43.5, 15.6, 186.7, 162.6, 72.3, 149.76, 57.7, 178.6, 6.79, 16.2, 19.89, 66.7, 13.8, 106.8, 65.2, 85, 76.9, 14.2, 25.4, 9, 84.3, 100.68, 125.88, 50, 47.7, 34.7, 43.9, 73.6, 135.1),
(59, 'PARAM_20250808_36494', 38.76, 66.75, 4.13, 135.41, 13.88, 120.71, 99, 82.1, 65.2, 25.4, 19.5, 106.7, 80.26, 94.9, 59.4, 94.93, 27, 97.69, 77.8, 124.2, 53.3, 65.8, 62.3, 83.1, 4, 28.3, 1.2, 65.07, 57.5, 96.48, 33.8, 18, 21.4, 141.8, 166.3, 79.6),
(60, 'PARAM_20250808_4762e', 16.12, 129.14, 7.19, 132.72, 96.53, 99.38, 94.7, 34.8, 88, 36.5, 112.2, 178.5, 41.35, 78.2, 36.2, 97.8, 18.7, 31.95, 93.8, 110.8, 190.9, 51.7, 57.9, 60.5, 27.4, 3.7, 17.9, 15.32, 36.8, 131.46, 91.6, 77, 74.5, 159, 102.9, 94.3),
(61, 'PARAM_20250808_a29db', 139.59, 103.29, 8.42, 101.76, 24.24, 100.09, 80.7, 23.1, 61.4, 189.4, 194.3, 27.8, 62.11, 63.4, 155.6, 24.99, 25.3, 67.52, 134.5, 157.3, 161.6, 16.7, 20.6, 39.7, 11, 18.1, 9.5, 83.51, 116.06, 108.79, 44.4, 51.5, 58.2, 113.4, 37.9, 112.9),
(62, 'PARAM_20250808_5fcbc', 19.31, 32.88, 6.16, 70.39, 11.01, 139.46, 10.8, 23.7, 14.3, 79.6, 10.8, 98.6, 74.66, 33.4, 75, 48.82, 28.2, 14.4, 131.6, 92, 36.5, 84, 94.6, 61.6, 23.8, 19.6, 13, 105.53, 13.91, 58.86, 61.7, 27.7, 61.1, 167.1, 14.3, 131.9),
(63, 'PARAM_20250808_67af2', 103.9, 136.23, 3.26, 70.84, 14.05, 27.99, 89.4, 21.3, 11.8, 94.9, 95.4, 56.5, 77.73, 19.5, 194.5, 83.83, 5.5, 47.55, 45.2, 63.2, 35.3, 37.4, 60.5, 93.5, 1.9, 20.7, 10.7, 45.3, 57.78, 105.27, 71.8, 41, 30, 77.5, 173, 40.3),
(64, 'PARAM_20250808_3d7f0', 104.21, 122.69, 6.3, 67.62, 36.96, 25.93, 13.4, 91.2, 71.4, 75.4, 11.5, 68, 89.25, 93.8, 49.4, 79.07, 8.9, 34.7, 163.6, 178.2, 54.8, 94.9, 13.4, 48.8, 18.1, 12.4, 6.9, 73.07, 54.48, 138.19, 67.1, 58.6, 50.1, 141.4, 189.6, 139.4),
(65, 'PARAM_20250808_3bbba', 42.63, 13.86, 6.44, 132.86, 124.45, 104.82, 24.6, 13.1, 32.4, 142.7, 197.4, 39.3, 139.46, 43.2, 39.5, 12, 8, 99.67, 60.5, 36.6, 123.3, 37.5, 28.1, 59.4, 18.5, 16.6, 16.7, 29.14, 93.6, 108.67, 74.1, 42.6, 60.5, 104.8, 136.9, 124),
(66, 'PARAM_20250808_770b0', 91.99, 23.12, 5.49, 37.56, 14.1, 143.18, 28.3, 47.1, 51.1, 72.9, 80, 140.7, 115.42, 29.9, 57.8, 93.96, 28.8, 5.05, 93.3, 192, 164.2, 23, 60.3, 15.8, 3.4, 4.3, 23.9, 43.36, 99.61, 101.94, 58.4, 15.9, 97, 37.7, 174.4, 113.9),
(67, 'PARAM_20250808_1ef67', 100, 10.43, 4.71, 36.36, 54.96, 70.27, 47.3, 53.5, 10.6, 71.5, 112.4, 29.5, 70.29, 73.1, 119.9, 56.29, 23.4, 9.53, 133.1, 153.5, 105.9, 26.2, 97.6, 44.4, 24.4, 1.4, 9.6, 80.22, 104.89, 147.33, 29.6, 88, 75.9, 104.6, 122.2, 125.8);

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
(58, 'PARAM_20250806_6a12f', 17.2, 159.6, 'Sample-4930', 'Sample-6933', 32.31, 'Sample-2040', 'Sample-7406', 73.48, 'Sample-5281', 'Sample-4829', 75.17, 'Sample-3377', 'Sample-2572', 84.53),
(59, 'PARAM_20250807_82ca5', 25.2, 133.9, 'Sample-7899', 'Sample-5911', 56.51, 'Sample-2644', 'Sample-8779', 30.03, 'Sample-1316', 'Sample-9715', 9.65, 'Sample-8130', 'Sample-9131', 97.93),
(60, 'PARAM_20250807_7b874', 25.2, 133.9, 'Sample-7899', 'Sample-5911', 56.51, 'Sample-2644', 'Sample-8779', 30.03, 'Sample-1316', 'Sample-9715', 9.65, 'Sample-8130', 'Sample-9131', 97.93),
(61, 'PARAM_20250807_ddbfd', 17.2, 159.6, 'Sample-4930', 'Sample-6933', 32.31, 'Sample-2040', 'Sample-7406', 73.48, 'Sample-5281', 'Sample-4829', 75.17, 'Sample-3377', 'Sample-2572', 84.53),
(62, 'PARAM_20250807_5b8d3', 25.2, 133.9, 'Sample-7899', 'Sample-5911', 56.51, 'Sample-2644', 'Sample-8779', 30.03, 'Sample-1316', 'Sample-9715', 9.65, 'Sample-8130', 'Sample-9131', 97.93),
(63, 'PARAM_20250807_1b470', 25.2, 133.9, 'Sample-7899', 'Sample-5911', 56.51, 'Sample-2644', 'Sample-8779', 30.03, 'Sample-1316', 'Sample-9715', 9.65, 'Sample-8130', 'Sample-9131', 97.93),
(64, 'PARAM_20250807_a902d', 6.6, 234.5, 'Sample-3043', 'Sample-7035', 58.88, 'Sample-9721', 'Sample-4672', 24.9, 'Sample-3587', 'Sample-7959', 45.98, 'Sample-8214', 'Sample-1607', 22.01),
(65, 'PARAM_20250807_3b128', 7.5, 130.2, 'Sample-3657', 'Sample-2531', 2.5, 'Sample-6830', 'Sample-6067', 97.2, 'Sample-5080', 'Sample-5024', 92.85, 'Sample-9362', 'Sample-7662', 42),
(66, 'PARAM_20250807_ce464', 1.2, 121, 'Sample-9847', 'Sample-2245', 45.44, 'Sample-7331', 'Sample-6771', 37.23, 'Sample-5328', 'Sample-5243', 74.93, 'Sample-5545', 'Sample-4643', 98.28),
(67, 'PARAM_20250808_36494', 16.3, 124.6, 'Sample-5261', 'Sample-3209', 89.22, 'Sample-8089', 'Sample-3634', 31.57, 'Sample-1076', 'Sample-1989', 49.26, 'Sample-2346', 'Sample-2755', 28.51),
(68, 'PARAM_20250808_4762e', 23.8, 110.5, 'Sample-9846', 'Sample-5727', 63.21, 'Sample-4603', 'Sample-3183', 54.25, 'Sample-6508', 'Sample-9102', 16.97, 'Sample-5095', 'Sample-5348', 5.56),
(69, 'PARAM_20250808_a29db', 6.7, 133.4, 'Sample-4987', 'Sample-7880', 67.45, 'Sample-3323', 'Sample-3769', 11.99, 'Sample-4236', 'Sample-7192', 10.19, 'Sample-8882', 'Sample-6251', 78.53),
(70, 'PARAM_20250808_5fcbc', 0.8, 205.3, 'Sample-3953', 'Sample-5713', 82.6, 'Sample-5399', 'Sample-2393', 88.42, 'Sample-5696', 'Sample-9729', 43.45, 'Sample-6471', 'Sample-5017', 78.62),
(71, 'PARAM_20250808_67af2', 16.6, 81.7, 'Sample-6792', 'Sample-6103', 7.44, 'Sample-1712', 'Sample-5622', 50.66, 'Sample-8555', 'Sample-7542', 14.4, 'Sample-2563', 'Sample-4188', 81.65),
(72, 'PARAM_20250808_3d7f0', 10.2, 165.1, 'Sample-3950', 'Sample-8777', 32.51, 'Sample-8527', 'Sample-4582', 11.75, 'Sample-7549', 'Sample-8960', 68.98, 'Sample-6086', 'Sample-3885', 49.04),
(73, 'PARAM_20250808_b5cf7', 60, 80, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(74, 'PARAM_20250808_3bbba', 7, 58.3, 'Sample-2713', 'Sample-2405', 7.33, 'Sample-7442', 'Sample-3698', 1.89, 'Sample-8256', 'Sample-6433', 7.08, 'Sample-5916', 'Sample-7538', 16.43),
(75, 'PARAM_20250808_770b0', 22.4, 79.4, 'Sample-9488', 'Sample-2765', 29.89, 'Sample-7321', 'Sample-4176', 40.47, 'Sample-6516', 'Sample-3676', 47.37, 'Sample-8914', 'Sample-4572', 79.13),
(76, 'PARAM_20250808_1ef67', 1.1, 223.6, 'Sample-5280', 'Sample-6346', 7.93, 'Sample-9037', 'Sample-6817', 22.34, 'Sample-7659', 'Sample-7616', 84.46, 'Sample-1486', 'Sample-1818', 99.18);

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
(10, 'PARAM_20250806_6a12f', 33.5, 46.55, 34.9, 55.91, 55.52, 28.22, 22.73, 81.88, 86.27, 81.94, 84.28, 67.55, 147, 77.1, 57.4, 82.4, 'Sample-2039', 'Sample-3421', 24.9, '2025-08-06 15:41:09', '2025-08-06 15:41:09'),
(11, 'PARAM_20250807_82ca5', 41.41, 23.96, 79.66, 17.94, 63.39, 31.93, 36.05, 1.35, 34.85, 46.02, 77.97, 24.75, 158.8, 102.2, 128.9, 155.8, 'Sample-5926', 'Sample-5268', 4.4, '2025-08-06 16:01:22', '2025-08-06 16:01:22'),
(12, 'PARAM_20250807_7b874', 41.41, 23.96, 79.66, 17.94, 63.39, 31.93, 36.05, 1.35, 34.85, 46.02, 77.97, 24.75, 158.8, 102.2, 128.9, 155.8, 'Sample-5926', 'Sample-5268', 4.4, '2025-08-06 16:09:30', '2025-08-06 16:09:30'),
(13, 'PARAM_20250807_ddbfd', 33.5, 46.55, 34.9, 55.91, 55.52, 28.22, 22.73, 81.88, 86.27, 81.94, 84.28, 67.55, 147, 77.1, 57.4, 82.4, 'Sample-2039', 'Sample-3421', 24.9, '2025-08-06 16:31:04', '2025-08-06 16:31:04'),
(14, 'PARAM_20250807_5b8d3', 41.41, 23.96, 79.66, 17.94, 63.39, 31.93, 36.05, 1.35, 34.85, 46.02, 77.97, 24.75, 158.8, 102.2, 128.9, 155.8, 'Sample-5926', 'Sample-5268', 4.4, '2025-08-07 02:38:33', '2025-08-07 02:38:33'),
(15, 'PARAM_20250807_1b470', 41.41, 23.96, 79.66, 17.94, 63.39, 31.93, 36.05, 1.35, 34.85, 46.02, 77.97, 24.75, 158.8, 102.2, 128.9, 155.8, 'Sample-5926', 'Sample-5268', 4.4, '2025-08-07 15:33:27', '2025-08-07 15:33:27'),
(16, 'PARAM_20250807_a902d', 98.43, 7.75, 45.29, 87.49, 15.05, 36.75, 73.38, 54.33, 85.23, 20.1, 19.67, 38.63, 179, 133.2, 16.1, 122.7, 'Sample-8948', 'Sample-1080', 16.1, '2025-08-07 15:51:28', '2025-08-07 15:51:28'),
(17, 'PARAM_20250807_3b128', 51.77, 25.25, 11.02, 85.97, 86.45, 46.94, 23.93, 14.12, 69.4, 61.02, 36.52, 61.75, 40.6, 159.3, 123.4, 17.6, 'Sample-8645', 'Sample-4424', 11.4, '2025-08-07 15:53:18', '2025-08-07 15:53:18'),
(18, 'PARAM_20250807_ce464', 18.82, 3.29, 67.74, 85.31, 65.63, 72.67, 32.66, 82.19, 50.28, 74.62, 47.18, 8.87, 199, 36.9, 70.1, 92.2, 'Sample-7182', 'Sample-4770', 14, '2025-08-07 15:59:56', '2025-08-07 15:59:56'),
(19, 'PARAM_20250808_36494', 91.49, 4.35, 58.55, 45.76, 61.12, 86.11, 24.55, 22.61, 38.35, 85.1, 72.86, 49.34, 131.5, 13.2, 34.7, 125.7, 'Sample-4064', 'Sample-8936', 6.7, '2025-08-07 16:12:27', '2025-08-07 16:12:27'),
(20, 'PARAM_20250808_4762e', 60.32, 56.1, 87.29, 20.85, 18.67, 9.65, 30.51, 38.16, 46.61, 81.85, 3.27, 27.75, 170, 186.1, 95.5, 98, 'Sample-4273', 'Sample-2975', 11.8, '2025-08-08 11:56:19', '2025-08-08 11:56:19'),
(21, 'PARAM_20250808_a29db', 77.34, 79.09, 39.94, 78.56, 68.8, 41.87, 71.08, 30.45, 52.06, 17.49, 26.16, 11.72, 145.4, 69.6, 100.4, 190.9, 'Sample-6362', 'Sample-6566', 9.3, '2025-08-08 12:10:01', '2025-08-08 12:10:01'),
(22, 'PARAM_20250808_5fcbc', 9.73, 35.6, 11.47, 45.98, 32.88, 13.73, 84.67, 95.31, 74.42, 77.93, 69.31, 37.48, 96.5, 125.2, 100.2, 187.4, 'Sample-4166', 'Sample-4366', 3.2, '2025-08-08 13:19:09', '2025-08-08 13:19:09'),
(23, 'PARAM_20250808_67af2', 88.66, 61.87, 32.71, 61.39, 92.22, 63.02, 20.11, 78.09, 82, 31.56, 10.89, 97.78, 124.6, 91.4, 128.4, 131.8, 'Sample-7654', 'Sample-7785', 20, '2025-08-08 13:26:27', '2025-08-08 13:26:27'),
(24, 'PARAM_20250808_3d7f0', 47.41, 19.73, 90.91, 79.19, 8.53, 34.86, 44.02, 44.9, 92.14, 73.23, 33.01, 16.5, 49.3, 66.4, 169.5, 91.4, 'Sample-4857', 'Sample-5690', 14.5, '2025-08-08 13:37:27', '2025-08-08 13:37:27'),
(25, 'PARAM_20250808_3bbba', 80.43, 96.35, 6.52, 59.24, 79.17, 78.52, 7.31, 43.26, 7.41, 99.08, 28.68, 89.72, 188.6, 55.3, 125.3, 24, 'Sample-5925', 'Sample-9190', 28.5, '2025-08-08 14:13:58', '2025-08-08 14:13:58'),
(26, 'PARAM_20250808_770b0', 60.72, 5.48, 52.58, 46.77, 64.01, 3.78, 24.52, 42.03, 68.38, 80.35, 90.94, 51.74, 71.2, 74.2, 10.7, 142.9, 'Sample-9863', 'Sample-4103', 4, '2025-08-08 14:29:12', '2025-08-08 14:29:12'),
(27, 'PARAM_20250808_1ef67', 39.06, 68.22, 76.37, 82.51, 46.51, 2.71, 42.08, 59.61, 50.02, 1.79, 37.99, 10.3, 191.7, 172.2, 113.5, 129, 'Sample-8059', 'Sample-5509', 7.2, '2025-08-08 14:41:06', '2025-08-08 14:41:06');

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
(53, 'PARAM_20250806_6a12f', 45.7, 53.2, 133.6, 189.3, 89.3, 226.4, 112.5, 82.5, 62.5, 223.2, 171.3, 155.1, 156.4, 246.5, 191.9, 144.7, 94.01),
(54, 'PARAM_20250807_82ca5', 146.4, 117.2, 33.8, 106.2, 72.7, 213.7, 162.6, 150.5, 247.6, 116.7, 187.4, 56.5, 135.1, 117.8, 159.1, 105.1, 80.24),
(55, 'PARAM_20250807_7b874', 146.4, 117.2, 33.8, 106.2, 72.7, 213.7, 162.6, 150.5, 247.6, 116.7, 187.4, 56.5, 135.1, 117.8, 159.1, 105.1, 80.24),
(56, 'PARAM_20250807_ddbfd', 45.7, 53.2, 133.6, 189.3, 89.3, 226.4, 112.5, 82.5, 62.5, 223.2, 171.3, 155.1, 156.4, 246.5, 191.9, 144.7, 94.01),
(57, 'PARAM_20250807_5b8d3', 146.4, 117.2, 33.8, 106.2, 72.7, 213.7, 162.6, 150.5, 247.6, 116.7, 187.4, 56.5, 135.1, 117.8, 159.1, 105.1, 80.24),
(58, 'PARAM_20250807_1b470', 146.4, 117.2, 33.8, 106.2, 72.7, 213.7, 162.6, 150.5, 247.6, 116.7, 187.4, 56.5, 135.1, 117.8, 159.1, 105.1, 80.24),
(59, 'PARAM_20250807_a902d', 239.2, 83.7, 118.9, 167.7, 138.1, 127, 49.8, 112.3, 215.8, 36, 146.9, 115.8, 31, 63.9, 75.9, 144.7, 57.04),
(60, 'PARAM_20250807_3b128', 94.4, 107.9, 234.4, 145.4, 228.3, 196, 39.9, 64.6, 244.3, 51.5, 56.7, 175.5, 175.5, 62, 60.7, 143, 9.83),
(61, 'PARAM_20250807_ce464', 132.2, 84.8, 244.9, 196.8, 222.5, 242, 185.8, 157.9, 148.6, 89.6, 230.1, 186, 49.6, 78, 177.3, 90.5, 2.12),
(62, 'PARAM_20250808_36494', 96.3, 105.3, 150.2, 186.5, 74.9, 92.9, 192.7, 98.2, 188.7, 95, 108.4, 161.8, 191.2, 205.8, 176.8, 184.3, 39.54),
(63, 'PARAM_20250808_4762e', 152.8, 201.4, 216, 45.8, 45.3, 186.7, 238.5, 99.7, 56.4, 124.5, 131.5, 221.2, 125.6, 179, 82.7, 175.2, 64.63),
(64, 'PARAM_20250808_a29db', 199.3, 87, 109.1, 146.1, 222, 107.7, 49.6, 145.8, 70.9, 104.3, 111, 117.8, 194.7, 147.8, 115.9, 101.2, 21.62),
(65, 'PARAM_20250808_5fcbc', 50.1, 60.7, 59.7, 118.8, 172.7, 167.1, 229.3, 82.6, 221.8, 236.6, 137.2, 234.7, 178.7, 144.8, 139.6, 116.7, 62.14),
(66, 'PARAM_20250808_67af2', 165, 134.1, 112.8, 169.9, 83.5, 116.5, 144.8, 89.6, 246, 240.5, 150.5, 203.8, 242.5, 49.9, 66.3, 133.7, 52.83),
(67, 'PARAM_20250808_3d7f0', 66.3, 61.5, 149.4, 245.7, 148.6, 42, 106.8, 94.9, 172.8, 51, 236.7, 62.5, 90.8, 79.4, 36.2, 97, 39.88),
(68, 'PARAM_20250808_3bbba', 247.1, 237.4, 188.9, 177.7, 84.5, 142.4, 240.7, 236.3, 47, 149.9, 32.9, 42.3, 39.3, 107.8, 187.4, 91.2, 93.3),
(69, 'PARAM_20250808_770b0', 179.4, 189.2, 244.9, 148.4, 192.5, 218.3, 240.2, 146.1, 210.2, 203.9, 76.8, 225, 62.7, 245.1, 236.9, 68.9, 88.94),
(70, 'PARAM_20250808_1ef67', 156.5, 102.8, 220.7, 44.4, 196.4, 224.4, 207.4, 160.3, 92.1, 167, 108.8, 209.4, 205.2, 136.1, 214, 98.5, 56.29);

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
(11, 'PARAM_20250806_6a12f', 60.27, 75.34, 73.76, 6.5, 67.47, 80.42, 71.29, 8.79, 99.29, 36.12, 98.41, 31.63, 101.6, 81.1, 138.4, 10, 20.7, 186.7, '2025-08-06 15:41:09', '2025-08-06 15:41:09'),
(12, 'PARAM_20250807_82ca5', 45.48, 64.26, 52.89, 12.2, 3.86, 88.3, 51.57, 98.34, 43.3, 26.53, 30.62, 48.82, 168.2, 168.2, 66.1, 167.1, 11.1, 10.9, '2025-08-06 16:01:22', '2025-08-06 16:01:22'),
(13, 'PARAM_20250807_7b874', 45.48, 64.26, 52.89, 12.2, 3.86, 88.3, 51.57, 98.34, 43.3, 26.53, 30.62, 48.82, 168.2, 168.2, 66.1, 167.1, 11.1, 10.9, '2025-08-06 16:09:30', '2025-08-06 16:09:30'),
(14, 'PARAM_20250807_ddbfd', 60.27, 75.34, 73.76, 6.5, 67.47, 80.42, 71.29, 8.79, 99.29, 36.12, 98.41, 31.63, 101.6, 81.1, 138.4, 10, 20.7, 186.7, '2025-08-06 16:31:04', '2025-08-06 16:31:04'),
(15, 'PARAM_20250807_5b8d3', 45.48, 64.26, 52.89, 12.2, 3.86, 88.3, 51.57, 98.34, 43.3, 26.53, 30.62, 48.82, 168.2, 168.2, 66.1, 167.1, 11.1, 10.9, '2025-08-07 02:38:33', '2025-08-07 02:38:33'),
(16, 'PARAM_20250807_1b470', 45.48, 64.26, 52.89, 12.2, 3.86, 88.3, 51.57, 98.34, 43.3, 26.53, 30.62, 48.82, 168.2, 168.2, 66.1, 167.1, 11.1, 10.9, '2025-08-07 15:33:27', '2025-08-07 15:33:27'),
(17, 'PARAM_20250807_a902d', 49.21, 31.46, 81.13, 44.7, 53.37, 95.47, 58.46, 29.83, 11.26, 70.89, 32.1, 4.82, 128.6, 156.2, 187.4, 107.6, 85, 40.8, '2025-08-07 15:51:28', '2025-08-07 15:51:28'),
(18, 'PARAM_20250807_3b128', 9.49, 92.24, 85.59, 29.14, 82.9, 56.39, 10.78, 16.97, 6.31, 52.86, 76.71, 75.15, 139.5, 140.4, 36.1, 138.4, 66.7, 167.8, '2025-08-07 15:53:18', '2025-08-07 15:53:18'),
(19, 'PARAM_20250807_ce464', 56.51, 59.12, 92.66, 48.67, 39.01, 72.7, 76.53, 25.52, 83.52, 71.19, 49, 91.65, 80.4, 49, 32, 44.5, 78.6, 80.7, '2025-08-07 15:59:56', '2025-08-07 15:59:56'),
(20, 'PARAM_20250808_36494', 28.78, 48.13, 61.17, 63.04, 3.95, 18.22, 87.09, 74.94, 87.39, 91.75, 75.18, 72.37, 170.3, 15.9, 98, 110.4, 134.7, 111.4, '2025-08-07 16:12:27', '2025-08-07 16:12:27'),
(21, 'PARAM_20250808_4762e', 23.4, 51.35, 77.56, 44.88, 64.86, 63.34, 59.17, 85.41, 81.05, 73.78, 61.66, 6.51, 160.7, 154.1, 63.2, 39.5, 133.5, 48.9, '2025-08-08 11:56:19', '2025-08-08 11:56:19'),
(22, 'PARAM_20250808_a29db', 29.52, 60.04, 91.04, 53.22, 61.37, 99.4, 1.21, 43.85, 26.07, 74.98, 62.89, 73.15, 67.9, 85.4, 145.1, 62.7, 45, 169.2, '2025-08-08 12:10:01', '2025-08-08 12:10:01'),
(23, 'PARAM_20250808_5fcbc', 42.63, 50.53, 57.13, 38.24, 16.42, 86.71, 25.39, 98.14, 87.48, 8.22, 42.99, 24.86, 75.8, 141.8, 136.6, 55.5, 164.2, 137.1, '2025-08-08 13:19:09', '2025-08-08 13:19:09'),
(24, 'PARAM_20250808_67af2', 32.02, 58.1, 9.59, 85.36, 15.2, 79.4, 12.16, 8.96, 51.38, 15.32, 61.58, 70.88, 180.2, 105.6, 184.9, 61.7, 39.9, 42.1, '2025-08-08 13:26:27', '2025-08-08 13:26:27'),
(25, 'PARAM_20250808_3d7f0', 14.1, 19.27, 54.57, 71.41, 77.85, 32.94, 30.9, 22.62, 41.15, 37.6, 63.68, 58.94, 15.8, 68.3, 64.7, 181.9, 109.2, 11.9, '2025-08-08 13:37:27', '2025-08-08 13:37:27'),
(26, 'PARAM_20250808_3bbba', 37.2, 56.88, 96.93, 77.64, 72.22, 19.85, 43.94, 10.01, 71.7, 90.28, 93.59, 31.27, 97.7, 12.6, 195.6, 27.8, 105.5, 73.7, '2025-08-08 14:13:58', '2025-08-08 14:13:58'),
(27, 'PARAM_20250808_770b0', 62.15, 80.04, 13.99, 3.09, 14.48, 68.79, 35.93, 1.4, 17.41, 82.61, 47.63, 5.19, 17.3, 33.4, 46.7, 144.4, 137.4, 63.8, '2025-08-08 14:29:12', '2025-08-08 14:29:12'),
(28, 'PARAM_20250808_1ef67', 34.97, 74.28, 38.61, 39.5, 66.24, 16.97, 80.67, 20.11, 40.84, 32.73, 31.24, 19.75, 101, 56.3, 71.2, 124.3, 84.4, 72.4, '2025-08-08 14:41:06', '2025-08-08 14:41:06');

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
(56, 'PARAM_20250806_6a12f', 'MC404', '106.9 tons', 'semi-automatic', NULL, 'Electrical', 'Glycol Solution', 'Glycol Solution', 'Normal', 'MTC', NULL),
(57, 'PARAM_20250807_82ca5', 'MC404', '70.4 tons', 'semi-automatic', NULL, 'Hot Oil', 'Chilled Water', 'Chilled Water', 'Normal', 'Chilled', NULL),
(58, 'PARAM_20250807_7b874', 'MC404', '70.4 tons', 'semi-automatic', NULL, 'Hot Oil', '', '', 'Normal', 'Chilled', NULL),
(59, 'PARAM_20250807_ddbfd', 'MC404', '106.9 tons', 'semi-automatic', NULL, 'Electrical', '', '', 'Normal', 'MTC', NULL),
(60, 'PARAM_20250807_5b8d3', 'MC404', '70.4 tons', 'semi-automatic', NULL, 'Hot Oil', '', '', 'Normal', 'Chilled', NULL),
(61, 'PARAM_20250807_1b470', 'MC404', '70.4 tons', 'semi-automatic', NULL, 'Hot Oil', 'Normal', 'Normal', 'Normal', 'Chilled', NULL),
(62, 'PARAM_20250807_a902d', 'MC101', '71.4 tons', 'semi-automatic', NULL, 'Hot Oil', 'Oil Cooling', 'Oil Cooling', 'Normal', 'Chilled', NULL),
(63, 'PARAM_20250807_3b128', 'MC505', '49.4 tons', 'semi-automatic', NULL, 'Hot Oil', 'Oil Cooling', 'Oil Cooling', 'Chilled', 'Normal', NULL),
(64, 'PARAM_20250807_ce464', 'MC303', '181.6 tons', 'semi-automatic', NULL, 'Steam', 'Chilled Water', 'Chilled Water', 'Normal', 'Normal', NULL),
(65, 'PARAM_20250808_36494', 'MC505', '107.0 tons', 'semi-automatic', NULL, 'Electrical', 'Glycol Solution', 'Glycol Solution', 'Chilled', 'Chilled', NULL),
(66, 'PARAM_20250808_4762e', 'MC505', '40.1 tons', 'manual', NULL, 'Electrical', 'Glycol Solution', 'Glycol Solution', 'Normal', 'Normal', NULL),
(67, 'PARAM_20250808_a29db', 'MC202', '58.6 tons', 'semi-automatic', NULL, 'Hot Oil', 'Chilled Water', 'Chilled Water', 'Chilled', 'Chilled', NULL),
(68, 'PARAM_20250808_5fcbc', 'MC404', '101.8 tons', 'manual', NULL, 'Hot Oil', 'Oil Cooling', 'Oil Cooling', 'Normal', 'Normal', NULL),
(69, 'PARAM_20250808_67af2', 'MC202', '21.4 tons', 'robot arm', NULL, 'Hot Oil', 'Glycol Solution', 'Glycol Solution', 'MTC', 'Normal', NULL),
(70, 'PARAM_20250808_3d7f0', 'MC404', '131.2 tons', 'manual', NULL, 'Electrical', 'Chilled Water', 'Chilled Water', 'MTC', 'Normal', NULL),
(71, 'PARAM_20250808_3bbba', 'MC202', '62.9 tons', 'robot arm', NULL, 'Electrical', 'Chilled Water', 'Chilled Water', 'Chilled', 'MTC', NULL),
(72, 'PARAM_20250808_770b0', 'MC202', '51.6 tons', 'automatic', NULL, 'Steam', 'Glycol Solution', 'Glycol Solution', 'Normal', 'Normal', NULL),
(73, 'PARAM_20250808_1ef67', 'MC505', '126.8 tons', 'manual', NULL, 'Steam', 'Oil Cooling', 'Oil Cooling', 'Chilled', 'Chilled', NULL);

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
  `review_status` enum('pending','reviewed','approved','needs_attention') DEFAULT 'pending',
  `first_reviewed_date` datetime DEFAULT NULL,
  `last_reviewed_date` datetime DEFAULT NULL,
  `reviewer_count` int(11) DEFAULT 0,
  `submitted_by` varchar(100) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `parameter_records`
--

INSERT INTO `parameter_records` (`record_id`, `submission_date`, `status`, `review_status`, `first_reviewed_date`, `last_reviewed_date`, `reviewer_count`, `submitted_by`, `title`, `description`) VALUES
('PARAM_20250522_988df', '2025-05-22 06:36:59', 'active', 'pending', NULL, NULL, 0, 'Jessie Mallorca Castro', 'CLF 750B - Plastic Container', 'Production run completed with standard parameters. All quality checks passed. Material batch: MB-3604'),
('PARAM_20250718_1333e', '2025-07-18 03:06:22', 'active', 'pending', NULL, NULL, 0, 'Romart Canda', 'CLF 750A - CM-5', ''),
('PARAM_20250718_18709', '2025-07-18 05:28:47', 'active', 'pending', NULL, NULL, 0, 'Romart Canda', 'MIT 1050B - 1L PEPSI', ''),
('PARAM_20250718_1fe34', '2025-07-18 09:42:21', 'active', 'pending', NULL, NULL, 0, 'Jade Eduardo Derramas', 'TOS 850B - CNS-3C', '2 GATES ONLY DUE TO GAS TRAP.'),
('PARAM_20250718_210a5', '2025-07-18 03:22:56', 'active', 'pending', NULL, NULL, 0, 'Romart Canda', 'CLF 750B - 8oz Shell Plastic Crate (PEPSI)', ''),
('PARAM_20250718_634d7', '2025-07-18 06:04:37', 'active', 'pending', NULL, NULL, 0, 'Romart Canda', 'TOS 850B - 1L Shell Plastic Crate (Pepsi)', ''),



('PARAM_20250718_dd40b', '2025-07-18 03:49:14', 'active', 'pending', NULL, NULL, 0, 'Romart Canda', 'CLF 750B - 8oz Shell Plastic Crate (PEPSI)', 'W/ HPP'),
('PARAM_20250721_15806', '2025-07-21 02:44:59', 'active', 'pending', NULL, NULL, 0, 'Kaishu San Jose', 'MIT 650D - UTILITY BIN 10L RECTANGULAR', 'AIR BLOW CONNECTED TO THE CORE SEQUENCE \r\nAn air line is connected to the Mold cavity; it blows air due to the mold open.\r\nCore mold \r\n1 1 - 1 3.0 7 - 1 2.0\r\n'),
('PARAM_20250721_73997', '2025-07-21 02:46:43', 'active', 'pending', NULL, NULL, 0, 'Kaishu San Jose', 'MIT 650D - UTILITY BIN 10L RECTANGULAR', 'AIR BLOW CONNECTED TO THE CORE SEQUENCE \r\nAn air line is connected to the Mold cavity; it blows air due to the mold open.\r\nCore mold \r\n1 1 - 1 3.0 7 - 1 2.0\r\n'),
('PARAM_20250731_3be2b', '2025-07-31 02:00:41', 'active', 'pending', NULL, NULL, 0, 'Aeron Paul Daliva', 'TOS 650A - Toy Box', 'Production run completed with standard parameters. All quality checks passed. Material batch: MB-3830'),
('PARAM_20250731_50626', '2025-07-31 03:42:03', 'active', 'pending', NULL, NULL, 0, 'Aeron Paul Daliva', 'MIT 1050B - CNS-3C', 'Production run completed with standard parameters. All quality checks passed. Material batch: MB-6908'),
('PARAM_20250731_7144f', '2025-07-31 01:59:49', 'active', 'pending', NULL, NULL, 0, 'Aeron Paul Daliva', 'TOS 650A - Toy Box', 'Production run completed with standard parameters. All quality checks passed. Material batch: MB-3830'),
('PARAM_20250731_9b44f', '2025-07-31 00:20:59', 'active', 'pending', NULL, NULL, 0, 'Aeron Paul Daliva', 'SUM 260C - Phone Case', 'Production run completed with standard parameters. All quality checks passed. Material batch: MB-1198'),
('PARAM_20250731_9d7d3', '2025-07-31 03:25:56', 'active', 'pending', NULL, NULL, 0, 'Aeron Paul Daliva', 'ARB 50 - Phone Case', 'Production run completed with standard parameters. All quality checks passed. Material batch: MB-6179'),
('PARAM_20250731_9f4a2', '2025-07-31 01:53:31', 'active', 'pending', NULL, NULL, 0, 'Aeron Paul Daliva', 'TOS 650A - Toy Box', 'Production run completed with standard parameters. All quality checks passed. Material batch: MB-3830'),
('PARAM_20250731_a2a58', '2025-07-31 00:50:02', 'active', 'pending', NULL, NULL, 0, 'Aeron Paul Daliva', 'CLF 750A - Food Container', 'Production run completed with standard parameters. All quality checks passed. Material batch: MB-7349'),
('PARAM_20250731_bf47c', '2025-07-31 03:24:10', 'active', 'pending', NULL, NULL, 0, 'Aeron Paul Daliva', 'ARB 50 - Food Container', 'Production run completed with standard parameters. All quality checks passed. Material batch: MB-1358'),
('PARAM_20250731_da614', '2025-07-31 02:11:00', 'active', 'pending', NULL, NULL, 0, 'Aeron Paul Daliva', 'TOS 850B - 1L SHELL PLASTIC CRATE (PEPSI)', 'Production run completed with standard parameters. All quality checks passed. Material batch: MB-2188'),
('PARAM_20250731_ea370', '2025-07-31 03:17:44', 'active', 'pending', NULL, NULL, 0, 'Aeron Paul Daliva', 'CLF 950A - 1L PEPSI', 'Production run completed with standard parameters. All quality checks passed. Material batch: MB-5876'),
('PARAM_20250731_efacc', '2025-07-31 01:57:36', 'active', 'pending', NULL, NULL, 0, 'Aeron Paul Daliva', 'TOS 650A - Toy Box', 'Production run completed with standard parameters. All quality checks passed. Material batch: MB-3830'),
('PARAM_20250806_0bf3f', '2025-08-06 01:16:58', 'active', 'pending', NULL, NULL, 0, 'Aeron Paul Daliva', 'MIT 1050B - CNS-3C', 'Production run completed with standard parameters. All quality checks passed. Material batch: MB-6908'),
('PARAM_20250806_6a12f', '2025-08-06 15:41:09', 'active', 'pending', NULL, NULL, 0, 'Aeron Paul Daliva', 'ARB 50 - Bottle Cap', 'Production run completed with standard parameters. All quality checks passed. Material batch: MB-7740'),
('PARAM_20250806_89709', '2025-08-06 15:36:57', 'active', 'pending', NULL, NULL, 0, 'Aeron Paul Daliva', 'TOS 850C - Phone Case', 'Production run completed with standard parameters. All quality checks passed. Material batch: MB-9961'),
('PARAM_20250806_b3e70', '2025-08-06 15:34:06', 'active', 'pending', NULL, NULL, 0, 'Aeron Paul Daliva', 'TOS 850C - Phone Case', 'Production run completed with standard parameters. All quality checks passed. Material batch: MB-9961'),
('PARAM_20250807_1b470', '2025-08-07 15:33:27', 'active', 'pending', NULL, NULL, 0, 'Aeron Paul Daliva', 'CLF 950B - Bottle Cap', 'Production run completed with standard parameters. All quality checks passed. Material batch: MB-4105'),
('PARAM_20250807_3b128', '2025-08-07 15:53:18', 'active', 'pending', NULL, NULL, 0, 'Aeron Paul Daliva', 'SUM 260C - Phone Case', 'Production run completed with standard parameters. All quality checks passed. Material batch: MB-8753'),
('PARAM_20250807_5b8d3', '2025-08-07 02:38:33', 'active', 'pending', NULL, NULL, 0, 'Aeron Paul Daliva', 'CLF 950B - Bottle Cap', 'Production run completed with standard parameters. All quality checks passed. Material batch: MB-4105'),
('PARAM_20250807_7b874', '2025-08-06 16:09:30', 'active', 'pending', NULL, NULL, 0, 'Aeron Paul Daliva', 'CLF 950B - Bottle Cap', 'Production run completed with standard parameters. All quality checks passed. Material batch: MB-4105'),
('PARAM_20250807_82ca5', '2025-08-06 16:01:22', 'active', 'pending', NULL, NULL, 0, 'Aeron Paul Daliva', 'CLF 950B - Bottle Cap', 'Production run completed with standard parameters. All quality checks passed. Material batch: MB-4105'),
('PARAM_20250807_a902d', '2025-08-07 15:51:28', 'active', 'pending', NULL, NULL, 0, 'Aeron Paul Daliva', 'TOS 850B - Toy Box', 'Production run completed with standard parameters. All quality checks passed. Material batch: MB-8149'),
('PARAM_20250807_ce464', '2025-08-07 15:59:56', 'active', 'pending', NULL, NULL, 0, 'Aeron Paul Daliva', 'CLF 950B - Phone Case', 'Production run completed with standard parameters. All quality checks passed. Material batch: MB-3170'),
('PARAM_20250807_ddbfd', '2025-08-06 16:31:04', 'active', 'pending', NULL, NULL, 0, 'Aeron Paul Daliva', 'ARB 50 - Bottle Cap', 'Production run completed with standard parameters. All quality checks passed. Material batch: MB-7740'),
('PARAM_20250808_1ef67', '2025-08-08 14:41:06', 'active', 'approved', '2025-08-08 23:07:17', '2025-08-08 23:08:12', 2, 'Aeron Paul Daliva', 'CLF 750C - Food Container', 'Production run completed with standard parameters. All quality checks passed. Material batch: MB-1226'),
('PARAM_20250808_36494', '2025-08-07 16:12:27', 'active', 'pending', NULL, NULL, 0, 'Aeron Paul Daliva', 'ARB 50 - Bottle Cap', 'Production run completed with standard parameters. All quality checks passed. Material batch: MB-7349'),
('PARAM_20250808_3bbba', '2025-08-08 14:13:58', 'active', 'pending', NULL, NULL, 0, 'Aeron Paul Daliva', 'CLF 750B - Bottle Cap', 'Production run completed with standard parameters. All quality checks passed. Material batch: MB-9719'),
('PARAM_20250808_3d7f0', '2025-08-08 13:37:27', 'active', 'pending', NULL, NULL, 0, 'Aeron Paul Daliva', 'CLF 950B - Bottle Cap', 'Production run completed with standard parameters. All quality checks passed. Material batch: MB-2492'),
('PARAM_20250808_4762e', '2025-08-08 11:56:19', 'active', 'pending', NULL, NULL, 0, 'Aeron Paul Daliva', 'TOS 850A - Phone Case', 'Production run completed with standard parameters. All quality checks passed. Material batch: MB-2955'),
('PARAM_20250808_5fcbc', '2025-08-08 13:19:09', 'active', 'pending', NULL, NULL, 0, 'Aeron Paul Daliva', 'SUM 350 - Bottle Cap', 'Production run completed with standard parameters. All quality checks passed. Material batch: MB-7492'),
('PARAM_20250808_67af2', '2025-08-08 13:26:27', 'active', 'pending', NULL, NULL, 0, 'Aeron Paul Daliva', 'CLF 750A - Toy Box', 'Production run completed with standard parameters. All quality checks passed. Material batch: MB-5482'),
('PARAM_20250808_770b0', '2025-08-08 14:29:12', 'active', 'pending', NULL, NULL, 0, 'Aeron Paul Daliva', 'SUM 260C - Bottle Cap', 'Production run completed with standard parameters. All quality checks passed. Material batch: MB-5901'),
('PARAM_20250808_a29db', '2025-08-08 12:10:01', 'active', 'pending', NULL, NULL, 0, 'Aeron Paul Daliva', 'ARB 50 - Water Bottle', 'Production run completed with standard parameters. All quality checks passed. Material batch: MB-5092'),
('PARAM_20250808_b5cf7', '2025-08-08 14:05:13', 'active', 'pending', NULL, NULL, 0, 'Aeron Paul Daliva', 'CLF 950B - Test Product', '');

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
(41, 'PARAM_20250806_6a12f', 'Aeron Paul Daliva', 'Ian Ilustresimo'),
(42, 'PARAM_20250807_82ca5', 'Aeron Paul Daliva', 'John Nero Abreu'),
(43, 'PARAM_20250807_7b874', 'Aeron Paul Daliva', 'John Nero Abreu'),
(44, 'PARAM_20250807_ddbfd', 'Aeron Paul Daliva', 'John Nero Abreu'),
(45, 'PARAM_20250807_5b8d3', 'Aeron Paul Daliva', 'Ian Ilustresimo'),
(46, 'PARAM_20250807_1b470', 'Aeron Paul Daliva', 'Ian Ilustresimo'),
(47, 'PARAM_20250807_a902d', 'Aeron Paul Daliva', 'John Nero Abreu'),
(48, 'PARAM_20250807_3b128', 'Aeron Paul Daliva', 'Stephanie Iris Sapno'),
(49, 'PARAM_20250807_ce464', 'Aeron Paul Daliva', 'Stephanie Iris Sapno'),
(50, 'PARAM_20250808_36494', 'Aeron Paul Daliva', 'Ian Ilustresimo'),
(51, 'PARAM_20250808_4762e', 'Aeron Paul Daliva', 'Stephanie Iris Sapno'),
(52, 'PARAM_20250808_a29db', 'Aeron Paul Daliva', 'Ian Ilustresimo'),
(53, 'PARAM_20250808_5fcbc', 'Aeron Paul Daliva', 'Stephanie Iris Sapno'),
(54, 'PARAM_20250808_67af2', 'Aeron Paul Daliva', 'Stephanie Iris Sapno'),
(55, 'PARAM_20250808_3d7f0', 'Aeron Paul Daliva', 'Stephanie Iris Sapno'),
(56, 'PARAM_20250808_3bbba', 'Aeron Paul Daliva', 'Ian Ilustresimo'),
(57, 'PARAM_20250808_770b0', 'Aeron Paul Daliva', 'Stephanie Iris Sapno'),
(58, 'PARAM_20250808_1ef67', 'Aeron Paul Daliva', 'Ian Ilustresimo');

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
(50, 'PARAM_20250806_6a12f', 147.6, 131.9, 152.7, 82.8, 12, 15.2, 98.2, 27.3, 39.3, 90.44, 32.76, 75.07, 81.1, 146.7, 177.3, 187.3),
(51, 'PARAM_20250807_82ca5', 130.3, 177.9, 158.9, 96.8, 95.9, 39.4, 55.8, 26.1, 31.3, 86.82, 88.41, 39.45, 89.1, 87.7, 160.6, 141.9),
(52, 'PARAM_20250807_7b874', 130.3, 177.9, 158.9, 96.8, 95.9, 39.4, 55.8, 26.1, 31.3, 86.82, 88.41, 39.45, 89.1, 87.7, 160.6, 141.9),
(53, 'PARAM_20250807_ddbfd', 147.6, 131.9, 152.7, 82.8, 12, 15.2, 98.2, 27.3, 39.3, 90.44, 32.76, 75.07, 81.1, 146.7, 177.3, 187.3),
(54, 'PARAM_20250807_5b8d3', 130.3, 177.9, 158.9, 96.8, 95.9, 39.4, 55.8, 26.1, 31.3, 86.82, 88.41, 39.45, 89.1, 87.7, 160.6, 141.9),
(55, 'PARAM_20250807_1b470', 130.3, 177.9, 158.9, 96.8, 95.9, 39.4, 55.8, 26.1, 31.3, 86.82, 88.41, 39.45, 89.1, 87.7, 160.6, 141.9),
(56, 'PARAM_20250807_a902d', 116.4, 133.9, 81.6, 66.7, 65.3, 20.9, 134.7, 104, 26.5, 62.62, 64.25, 33, 46.1, 176, 18.9, 177.2),
(57, 'PARAM_20250807_3b128', 140.9, 64.4, 74.5, 26, 44.1, 65.1, 162.5, 25.1, 34.5, 83.96, 61.4, 52.2, 54.1, 35.9, 166.5, 78.8),
(58, 'PARAM_20250807_ce464', 50.5, 144.5, 65.5, 55.2, 48.3, 50.1, 77.5, 120, 178.7, 117.18, 101.78, 40.66, 84.8, 125, 186.4, 146.8),
(59, 'PARAM_20250808_36494', 90.3, 31.1, 153.3, 69.3, 89.1, 43.9, 76.9, 163.7, 135.1, 113.96, 13.64, 20.77, 147.4, 112.9, 160.1, 149),
(60, 'PARAM_20250808_4762e', 40.5, 71.9, 93.7, 87.1, 25.1, 69.2, 112, 183.6, 68.3, 22.38, 78.24, 40.08, 105.9, 137.3, 109.9, 157.2),
(61, 'PARAM_20250808_a29db', 38.3, 175.3, 87.8, 23.5, 38, 71.8, 58.8, 171.3, 169.3, 49.68, 79.11, 104.74, 168.1, 49.7, 166.6, 142.7),
(62, 'PARAM_20250808_5fcbc', 126.1, 46.5, 64.3, 56.4, 12.5, 72.7, 189.5, 51.1, 49.9, 49.72, 124.18, 37.52, 176.8, 172.7, 68.7, 123.8),
(63, 'PARAM_20250808_67af2', 58.8, 143.1, 154.1, 27.4, 59.7, 10.2, 170.5, 86.8, 117.2, 81.52, 11.22, 66.86, 118.4, 15, 88.2, 27.5),
(64, 'PARAM_20250808_3d7f0', 108.9, 44.6, 52.9, 35.2, 27.6, 80, 163.2, 29.2, 136.4, 54.01, 70.13, 123.37, 18.2, 39.2, 100.8, 178.6),
(65, 'PARAM_20250808_3bbba', 46, 56.8, 43.1, 64.8, 51.2, 24.4, 152.3, 169.6, 139.8, 36.72, 83.28, 29.44, 188.6, 173.6, 192.6, 186.3),
(66, 'PARAM_20250808_770b0', 112.2, 100.9, 86.1, 79.4, 71.5, 79.6, 153.5, 146.7, 72.8, 18.79, 75.33, 113.29, 93.6, 162, 88.5, 138.7),
(67, 'PARAM_20250808_1ef67', 38.4, 44.8, 65.9, 13.8, 75.2, 93.3, 145.8, 74, 71.1, 130.31, 63.99, 126.38, 116.4, 50.9, 157.5, 43.3);

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
(58, 'PARAM_20250806_6a12f', 'Bottle Cap', 'Green', 'Sample-9808', 'PN-1604', 10, 409.1, 151),
(59, 'PARAM_20250807_82ca5', 'Bottle Cap', 'Yellow', 'Sample-2873', 'PN-3085', 8, 93.2, 98.1),
(60, 'PARAM_20250807_7b874', 'Bottle Cap', 'Black', 'Sample-2873', 'PN-3085', 8, 93.2, 98.1),
(61, 'PARAM_20250807_ddbfd', 'Bottle Cap', 'Black', 'Sample-9808', 'PN-1604', 10, 409.1, 151),
(62, 'PARAM_20250807_5b8d3', 'Bottle Cap', '', 'Sample-2873', 'PN-3085', 8, 93.2, 98.1),
(63, 'PARAM_20250807_1b470', 'Bottle Cap', 'Black', 'Sample-2873', 'PN-3085', 8, 93.2, 98.1),
(64, 'PARAM_20250807_a902d', 'Toy Box', 'Gray', 'Sample-2587', 'PN-2186', 15, 70.4, 362.6),
(65, 'PARAM_20250807_3b128', 'Phone Case', 'Green', 'Sample-9429', 'PN-4610', 14, 319.7, 129.1),
(66, 'PARAM_20250807_ce464', 'Phone Case', 'Gray', 'Sample-2288', 'PN-7927', 6, 170.4, 37.3),
(67, 'PARAM_20250808_36494', 'Bottle Cap', 'Gray', 'Sample-9303', 'PN-2966', 4, 211.7, 119.6),
(68, 'PARAM_20250808_4762e', 'Phone Case', 'Black', 'Sample-3489', 'PN-4410', 15, 280.3, 466.5),
(69, 'PARAM_20250808_a29db', 'Water Bottle', 'Clear', 'Sample-9274', 'PN-1331', 6, 22.3, 362.7),
(70, 'PARAM_20250808_5fcbc', 'Bottle Cap', 'Clear', 'Sample-3612', 'PN-2951', 9, 399.7, 70.4),
(71, 'PARAM_20250808_67af2', 'Toy Box', 'Green', 'Sample-1665', 'PN-2929', 13, 287, 330.9),
(72, 'PARAM_20250808_3d7f0', 'Bottle Cap', 'Blue', 'Sample-5908', 'PN-6490', 14, 14.4, 372.1),
(73, 'PARAM_20250808_b5cf7', 'Test Product', 'Blue', 'TestMold', 'PN-TEST', 1, 100, 90),
(74, 'PARAM_20250808_3bbba', 'Bottle Cap', 'Red', 'Sample-6818', 'PN-8433', 7, 104.8, 36.6),
(75, 'PARAM_20250808_770b0', 'Bottle Cap', 'Blue', 'Sample-4066', 'PN-1842', 12, 52, 160.8),
(76, 'PARAM_20250808_1ef67', 'Food Container', 'Red', 'Sample-7092', 'PN-8009', 9, 234.4, 459.7);

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
(58, 'PARAM_20250806_6a12f', '2025-08-06', '23:41:09', '23:41:09', '23:41:09', 'ARB 50', 'RN4741', 'Product Improvement', 'IRN35119'),
(59, 'PARAM_20250807_82ca5', '2025-08-07', '00:01:22', '00:01:22', '00:01:22', 'CLF 950B', 'RN7072', 'Mass Production', 'IRN58664'),
(60, 'PARAM_20250807_7b874', '2025-08-07', '00:08:22', '00:08:22', NULL, 'CLF 950B', 'RN7072', 'Mass Production', 'IRN58664'),
(61, 'PARAM_20250807_ddbfd', '2025-08-07', '00:30:46', '00:30:46', NULL, 'ARB 50', 'RN4741', 'Product Improvement', 'IRN35119'),
(62, 'PARAM_20250807_5b8d3', '2025-08-07', '10:20:12', '10:20:12', NULL, 'CLF 950B', 'RN7072', 'Mass Production', 'IRN58664'),
(63, 'PARAM_20250807_1b470', '2025-08-07', '23:32:35', '23:32:35', NULL, 'CLF 950B', 'RN7072', 'Mass Production', 'IRN58664'),
(64, 'PARAM_20250807_a902d', '2025-08-07', '23:51:28', '23:51:28', '23:51:28', 'TOS 850B', 'RN5535', 'Mold Preventive Maintenance', 'IRN82777'),
(65, 'PARAM_20250807_3b128', '2025-08-07', '23:53:18', '23:53:18', '23:53:18', 'SUM 260C', 'RN3126', 'Mold Preventive Maintenance', 'IRN79452'),
(66, 'PARAM_20250807_ce464', '2025-08-07', '23:59:56', '23:59:56', '23:59:56', 'CLF 950B', 'RN8156', 'New Mold Testing', 'IRN14923'),
(67, 'PARAM_20250808_36494', '2025-08-08', '00:12:27', '00:12:27', '00:12:27', 'ARB 50', 'RN3872', 'Mold Preventive Maintenance', 'IRN23494'),
(68, 'PARAM_20250808_4762e', '2025-08-08', '19:56:19', '19:56:19', '19:56:19', 'TOS 850A', 'RN5251', 'Product Improvement', 'IRN80700'),
(69, 'PARAM_20250808_a29db', '2025-08-08', '20:10:01', '20:10:01', '20:10:01', 'ARB 50', 'RN5091', 'Colorant Testing', 'IRN69024'),
(70, 'PARAM_20250808_5fcbc', '2025-08-08', '21:19:09', '21:19:09', '21:19:09', 'SUM 350', 'RN6415', 'Material Testing', 'IRN96330'),
(71, 'PARAM_20250808_67af2', '2025-08-08', '21:26:27', '21:26:27', '21:26:27', 'CLF 750A', 'RN7077', 'New Mold Testing', 'IRN85530'),
(72, 'PARAM_20250808_3d7f0', '2025-08-08', '21:37:27', '21:37:27', '21:37:27', 'CLF 950B', 'RN3850', 'Machine Preventive Maintenance', 'IRN67564'),
(73, 'PARAM_20250808_b5cf7', '2025-08-08', '21:55:00', '21:55:00', '22:05:13', 'CLF 950B', 'TEST123', 'Testing', 'IRN999'),
(74, 'PARAM_20250808_3bbba', '2025-08-08', '22:13:46', '22:13:46', '22:13:58', 'CLF 750B', 'RN8881', 'Mold Preventive Maintenance', 'IRN65385'),
(75, 'PARAM_20250808_770b0', '2025-08-08', '22:28:44', '22:28:44', '22:29:12', 'SUM 260C', 'RN2877', 'Product Improvement', 'IRN75782'),
(76, 'PARAM_20250808_1ef67', '2025-08-08', '22:40:34', '22:40:34', '22:41:06', 'CLF 750C', 'RN7097', 'Machine Preventive Maintenance', 'IRN97338');

-- --------------------------------------------------------

--
-- Table structure for table `supervisor_reviews`
--

CREATE TABLE `supervisor_reviews` (
  `id` int(11) NOT NULL,
  `record_id` varchar(50) NOT NULL,
  `supervisor_name` varchar(100) NOT NULL,
  `supervisor_role` varchar(50) DEFAULT 'Supervisor',
  `review_date` datetime DEFAULT current_timestamp(),
  `review_notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `supervisor_reviews`
--

INSERT INTO `supervisor_reviews` (`id`, `record_id`, `supervisor_name`, `supervisor_role`, `review_date`, `review_notes`) VALUES
(1, 'PARAM_20250808_1ef67', 'Aeron Paul Daliva', 'Supervisor', '2025-08-08 23:07:17', ''),
(2, 'PARAM_20250808_1ef67', 'Aeron Paul Daliva', 'Supervisor', '2025-08-08 23:08:12', '');

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
(58, 'PARAM_20250806_6a12f', 29.1, 20.8, 9.2, 28.4, 0, 21.6),
(59, 'PARAM_20250807_82ca5', 26, 27.6, 17.4, 20.7, 23.6, 20.6),
(60, 'PARAM_20250807_7b874', 26, 27.6, 17.4, 20.7, 23.6, 20.6),
(61, 'PARAM_20250807_ddbfd', 29.1, 20.8, 9.2, 28.4, 0, 21.6),
(62, 'PARAM_20250807_5b8d3', 26, 27.6, 17.4, 20.7, 23.6, 20.6),
(63, 'PARAM_20250807_1b470', 26, 27.6, 17.4, 20.7, 23.6, 20.6),
(64, 'PARAM_20250807_a902d', 12, 12.3, 1.3, 6.4, 23, 28.9),
(65, 'PARAM_20250807_3b128', 10.7, 23.4, 26, 2.5, 32, 1),
(66, 'PARAM_20250807_ce464', 11, 15.1, 15.4, 6.8, 0, 26.7),
(67, 'PARAM_20250808_36494', 27, 9.1, 7.1, 25.3, 0, 14.7),
(68, 'PARAM_20250808_4762e', 9.5, 24.5, 0.8, 26.8, 20.5, 19.7),
(69, 'PARAM_20250808_a29db', 29.6, 24.3, 16.5, 15, 0, 18.2),
(70, 'PARAM_20250808_5fcbc', 8.8, 11, 16.4, 9.6, 0, 26.5),
(71, 'PARAM_20250808_67af2', 11.7, 25.8, 4.1, 18.7, 0, 27.6),
(72, 'PARAM_20250808_3d7f0', 19.1, 25.7, 18.2, 24.6, 0, 3.1),
(73, 'PARAM_20250808_3bbba', 10.8, 14.5, 12.6, 1.5, 0, 18.8),
(74, 'PARAM_20250808_770b0', 8.5, 28.6, 19.7, 1.5, 0, 24.8),
(75, 'PARAM_20250808_1ef67', 19.4, 19, 25.2, 7.3, 0, 6.9);

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
(15, '000000', 'Aeron Paul Daliva', 'PARAMETER_SUBMISSION', 'PARAM_20250806_6a12f', '{\"id_number\":\"000000\",\"full_name\":\"Aeron Paul Daliva\",\"role\":\"admin\",\"last_activity\":1754494869}', 'Title: ARB 50 - Bottle Cap', '180.190.212.107', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', '2025-08-06 15:41:09'),
(16, '000000', 'Aeron Paul Daliva', 'PARAMETER_SUBMISSION', 'PARAM_20250807_82ca5', '{\"id_number\":\"000000\",\"full_name\":\"Aeron Paul Daliva\",\"role\":\"admin\",\"last_activity\":1754496082}', 'Title: CLF 950B - Bottle Cap', '180.190.212.107', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', '2025-08-06 16:01:22'),
(17, '000000', 'Aeron Paul Daliva', 'PARAMETER_SUBMISSION', 'PARAM_20250807_7b874', '{\"id_number\":\"000000\",\"full_name\":\"Aeron Paul Daliva\",\"role\":\"admin\",\"last_activity\":1754496570}', 'Title: CLF 950B - Bottle Cap', '180.190.212.107', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', '2025-08-06 16:09:30'),
(18, '000000', 'Aeron Paul Daliva', 'PARAMETER_SUBMISSION', 'PARAM_20250807_ddbfd', '{\"id_number\":\"000000\",\"full_name\":\"Aeron Paul Daliva\",\"role\":\"admin\",\"last_activity\":1754497864}', 'Title: ARB 50 - Bottle Cap', '180.190.212.107', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', '2025-08-06 16:31:04'),
(19, '000000', 'Aeron Paul Daliva', 'PARAMETER_SUBMISSION', 'PARAM_20250807_5b8d3', '{\"id_number\":\"000000\",\"full_name\":\"Aeron Paul Daliva\",\"role\":\"admin\",\"last_activity\":1754534313}', 'Title: CLF 950B - Bottle Cap', '180.190.212.107', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', '2025-08-07 02:38:33'),
(20, '000000', 'Aeron Paul Daliva', 'PARAMETER_SUBMISSION', 'PARAM_20250807_1b470', '{\"id_number\":\"000000\",\"full_name\":\"Aeron Paul Daliva\",\"role\":\"admin\",\"last_activity\":1754580807}', 'Title: CLF 950B - Bottle Cap', '180.190.212.107', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', '2025-08-07 15:33:27'),
(21, '000000', 'Aeron Paul Daliva', 'PARAMETER_SUBMISSION', 'PARAM_20250807_a902d', '{\"id_number\":\"000000\",\"full_name\":\"Aeron Paul Daliva\",\"role\":\"admin\",\"last_activity\":1754581888}', 'Title: TOS 850B - Toy Box', '180.190.212.107', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', '2025-08-07 15:51:28'),
(22, '000000', 'Aeron Paul Daliva', 'PARAMETER_SUBMISSION', 'PARAM_20250807_3b128', '{\"id_number\":\"000000\",\"full_name\":\"Aeron Paul Daliva\",\"role\":\"admin\",\"last_activity\":1754581998}', 'Title: SUM 260C - Phone Case', '180.190.212.107', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', '2025-08-07 15:53:18'),
(23, '000000', 'Aeron Paul Daliva', 'PARAMETER_SUBMISSION', 'PARAM_20250807_ce464', '{\"id_number\":\"000000\",\"full_name\":\"Aeron Paul Daliva\",\"role\":\"admin\",\"last_activity\":1754582396}', 'Title: CLF 950B - Phone Case', '180.190.212.107', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', '2025-08-07 15:59:56'),
(24, '000000', 'Aeron Paul Daliva', 'PARAMETER_SUBMISSION', 'PARAM_20250808_36494', '{\"id_number\":\"000000\",\"full_name\":\"Aeron Paul Daliva\",\"role\":\"admin\",\"last_activity\":1754583147}', 'Title: ARB 50 - Bottle Cap', '180.190.212.107', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', '2025-08-07 16:12:27'),
(25, '000000', 'Aeron Paul Daliva', 'PARAMETER_SUBMISSION', 'PARAM_20250808_4762e', '{\"id_number\":\"000000\",\"full_name\":\"Aeron Paul Daliva\",\"role\":\"admin\",\"last_activity\":1754654179}', 'Title: TOS 850A - Phone Case', '180.190.212.107', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-08-08 11:56:19'),
(26, '000000', 'Aeron Paul Daliva', 'PARAMETER_SUBMISSION', 'PARAM_20250808_a29db', '{\"id_number\":\"000000\",\"full_name\":\"Aeron Paul Daliva\",\"role\":\"admin\",\"last_activity\":1754655001}', 'Title: ARB 50 - Water Bottle', '180.190.212.107', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-08-08 12:10:01'),
(27, '000000', 'Aeron Paul Daliva', 'PARAMETER_SUBMISSION', 'PARAM_20250808_5fcbc', '{\"id_number\":\"000000\",\"full_name\":\"Aeron Paul Daliva\",\"role\":\"admin\",\"last_activity\":1754659149}', 'Title: SUM 350 - Bottle Cap', '180.190.212.107', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-08-08 13:19:09'),
(28, '000000', 'Aeron Paul Daliva', 'PARAMETER_SUBMISSION', 'PARAM_20250808_67af2', '{\"id_number\":\"000000\",\"full_name\":\"Aeron Paul Daliva\",\"role\":\"admin\",\"last_activity\":1754659587}', 'Title: CLF 750A - Toy Box', '180.190.212.107', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-08-08 13:26:27'),
(29, '000000', 'Aeron Paul Daliva', 'PARAMETER_SUBMISSION', 'PARAM_20250808_3d7f0', '{\"id_number\":\"000000\",\"full_name\":\"Aeron Paul Daliva\",\"role\":\"admin\",\"last_activity\":1754660247}', 'Title: CLF 950B - Bottle Cap', '180.190.212.107', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-08-08 13:37:27'),
(30, '000000', 'Aeron Paul Daliva', 'PARAMETER_SUBMISSION', 'PARAM_20250808_b5cf7', '{\"id_number\":\"000000\",\"full_name\":\"Aeron Paul Daliva\",\"role\":\"admin\",\"last_activity\":1754661912}', 'Title: CLF 950B - Test Product', '180.190.212.107', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-08-08 14:05:13'),
(31, '000000', 'Aeron Paul Daliva', 'PARAMETER_SUBMISSION', 'PARAM_20250808_3bbba', '{\"id_number\":\"000000\",\"full_name\":\"Aeron Paul Daliva\",\"role\":\"admin\",\"last_activity\":1754662437}', 'Title: CLF 750B - Bottle Cap', '180.190.212.107', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-08-08 14:13:58'),
(32, '000000', 'Aeron Paul Daliva', 'PARAMETER_SUBMISSION', 'PARAM_20250808_770b0', '{\"id_number\":\"000000\",\"full_name\":\"Aeron Paul Daliva\",\"role\":\"admin\",\"last_activity\":1754663351}', 'Title: SUM 260C - Bottle Cap', '180.190.212.107', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-08-08 14:29:12'),
(33, '000000', 'Aeron Paul Daliva', 'PARAMETER_SUBMISSION', 'PARAM_20250808_1ef67', '{\"id_number\":\"000000\",\"full_name\":\"Aeron Paul Daliva\",\"role\":\"admin\",\"last_activity\":1754664065}', 'Title: CLF 750C - Food Container', '180.190.212.107', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-08-08 14:41:06');

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
  ADD PRIMARY KEY (`record_id`),
  ADD KEY `idx_review_status` (`review_status`);

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
-- Indexes for table `supervisor_reviews`
--
ALTER TABLE `supervisor_reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_record_id` (`record_id`),
  ADD KEY `idx_supervisor` (`supervisor_name`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `attachments`
--
ALTER TABLE `attachments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `barrelheatertemperatures`
--
ALTER TABLE `barrelheatertemperatures`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;

--
-- AUTO_INCREMENT for table `colorantdetails`
--
ALTER TABLE `colorantdetails`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;

--
-- AUTO_INCREMENT for table `corepullsettings`
--
ALTER TABLE `corepullsettings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=192;

--
-- AUTO_INCREMENT for table `ejectionparameters`
--
ALTER TABLE `ejectionparameters`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- AUTO_INCREMENT for table `injectionparameters`
--
ALTER TABLE `injectionparameters`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT for table `materialcomposition`
--
ALTER TABLE `materialcomposition`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;

--
-- AUTO_INCREMENT for table `moldcloseparameters`
--
ALTER TABLE `moldcloseparameters`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `moldheatertemperatures`
--
ALTER TABLE `moldheatertemperatures`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- AUTO_INCREMENT for table `moldopenparameters`
--
ALTER TABLE `moldopenparameters`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `moldoperationspecs`
--
ALTER TABLE `moldoperationspecs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT for table `parameters_submissions`
--
ALTER TABLE `parameters_submissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `personnel`
--
ALTER TABLE `personnel`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `plasticizingparameters`
--
ALTER TABLE `plasticizingparameters`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT for table `productdetails`
--
ALTER TABLE `productdetails`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;

--
-- AUTO_INCREMENT for table `productmachineinfo`
--
ALTER TABLE `productmachineinfo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;

--
-- AUTO_INCREMENT for table `supervisor_reviews`
--
ALTER TABLE `supervisor_reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `timerparameters`
--
ALTER TABLE `timerparameters`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;

--
-- AUTO_INCREMENT for table `user_activity`
--
ALTER TABLE `user_activity`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `supervisor_reviews`
--
ALTER TABLE `supervisor_reviews`
  ADD CONSTRAINT `supervisor_reviews_ibfk_1` FOREIGN KEY (`record_id`) REFERENCES `parameter_records` (`record_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
