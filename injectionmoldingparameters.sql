-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 12, 2025 at 01:04 PM
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
-- Database: `injectionmoldingparameters`
--

-- --------------------------------------------------------

--
-- Table structure for table `additionalinformation`
--

CREATE TABLE `additionalinformation` (
  `id` int(11) NOT NULL,
  `Info` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `additionalinformation`
--

INSERT INTO `additionalinformation` (`id`, `Info`) VALUES
(1, 'Sample additional info'),
(2, 'Sample additional info'),
(3, 'Sample additional info'),
(4, 'Sample additional info'),
(5, 'Sample additional info'),
(6, 'Sample additional info');

-- --------------------------------------------------------

--
-- Table structure for table `attachments`
--

CREATE TABLE `attachments` (
  `id` int(11) NOT NULL,
  `FileName` varchar(255) NOT NULL,
  `FilePath` varchar(255) NOT NULL,
  `FileType` enum('image','video') NOT NULL,
  `UploadedAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attachments`
--

INSERT INTO `attachments` (`id`, `FileName`, `FilePath`, `FileType`, `UploadedAt`) VALUES
(1, 'actuator.jpg', 'uploads/90_2024-01-01_12:00_35_44.jpg', 'image', '2025-01-30 02:54:34'),
(2, '230ae8f3-af34-4099-b6eb-b7da396a9fdf.mp4', 'uploads/90_2024-01-01_12:00_35_44.mp4', 'video', '2025-01-30 02:54:34'),
(3, 'actuator.jpg', 'uploads/90_2024-01-01_12:00_35_44.jpg', 'image', '2025-01-30 02:58:38'),
(4, '230ae8f3-af34-4099-b6eb-b7da396a9fdf.mp4', 'uploads/90_2024-01-01_12:00_35_44.mp4', 'video', '2025-01-30 02:58:38'),
(5, 'actuator.jpg', 'uploads/90_2024-01-01_12:00_35_44.jpg', 'image', '2025-01-30 03:04:29'),
(6, '230ae8f3-af34-4099-b6eb-b7da396a9fdf.mp4', 'uploads/90_2024-01-01_12:00_35_44.mp4', 'video', '2025-01-30 03:04:29'),
(7, 'actuator.jpg', 'uploads/97_2024-01-01_12:00_98_94.jpg', 'image', '2025-01-30 03:39:46'),
(8, 'actuator1.jpg', 'uploads/97_2024-01-01_12:00_98_94.jpg', 'image', '2025-01-30 03:39:46'),
(9, '2024-10-07 13-28-32.mp4', 'uploads/97_2024-01-01_12:00_98_94.mp4', 'video', '2025-01-30 03:39:46'),
(10, '2024-10-08 11-32-06.mp4', 'uploads/97_2024-01-01_12:00_98_94.mp4', 'video', '2025-01-30 03:39:46'),
(11, 'actuator.jpg', 'uploads/97_2024-01-01_12:00_98_94.jpg', 'image', '2025-01-30 03:40:40'),
(12, 'actuator1.jpg', 'uploads/97_2024-01-01_12:00_98_94.jpg', 'image', '2025-01-30 03:40:40'),
(13, '2024-10-07 13-28-32.mp4', 'uploads/97_2024-01-01_12:00_98_94.mp4', 'video', '2025-01-30 03:40:40'),
(14, '2024-10-08 11-32-06.mp4', 'uploads/97_2024-01-01_12:00_98_94.mp4', 'video', '2025-01-30 03:40:40'),
(15, '97_20240101_1200_98_94_679b027d9c194.jpg', 'C:/xampp/htdocs/Sentinel/parameter/uploads/97_20240101_1200_98_94_679b027d9c194.jpg', 'image', '2025-01-30 04:39:25'),
(16, '97_20240101_1200_98_94_679b027d9c3e5.mp4', 'C:/xampp/htdocs/Sentinel/parameter/uploads/97_20240101_1200_98_94_679b027d9c3e5.mp4', 'video', '2025-01-30 04:39:25');

-- --------------------------------------------------------

--
-- Table structure for table `barrelheatertemperatures`
--

CREATE TABLE `barrelheatertemperatures` (
  `id` int(11) NOT NULL,
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

INSERT INTO `barrelheatertemperatures` (`id`, `Zone0`, `Zone1`, `Zone2`, `Zone3`, `Zone4`, `Zone5`, `Zone6`, `Zone7`, `Zone8`, `Zone9`, `Zone10`, `Zone11`, `Zone12`, `Zone13`, `Zone14`, `Zone15`, `Zone16`) VALUES
(1, 57, 52, 20, 43, 12, 35, 32, 5, 10, 39, 37, 82, 5, 21, 79, 54, 46),
(2, 57, 52, 20, 43, 12, 35, 32, 5, 10, 39, 37, 82, 5, 21, 79, 54, 46),
(3, 57, 52, 20, 43, 12, 35, 32, 5, 10, 39, 37, 82, 5, 21, 79, 54, 46),
(4, 79, 66, 95, 28, 58, 91, 93, 8, 22, 82, 43, 32, 91, 78, 44, 14, 7),
(5, 79, 66, 95, 28, 58, 91, 93, 8, 22, 82, 43, 32, 91, 78, 44, 14, 7),
(6, 79, 66, 95, 28, 58, 91, 93, 8, 22, 82, 43, 32, 91, 78, 44, 14, 7);

-- --------------------------------------------------------

--
-- Table structure for table `colorantdetails`
--

CREATE TABLE `colorantdetails` (
  `id` int(11) NOT NULL,
  `Colorant` varchar(255) DEFAULT NULL,
  `Color` varchar(255) DEFAULT NULL,
  `Dosage` varchar(255) DEFAULT NULL,
  `Stabilizer` varchar(255) DEFAULT NULL,
  `StabilizerDosage` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `colorantdetails`
--

INSERT INTO `colorantdetails` (`id`, `Colorant`, `Color`, `Dosage`, `Stabilizer`, `StabilizerDosage`) VALUES
(1, '80', '87', '25', '33', '60'),
(2, '80', '87', '25', '33', '60'),
(3, '80', '87', '25', '33', '60'),
(4, '40', '66', '96', '64', '83'),
(5, '40', '66', '96', '64', '83'),
(6, '40', '66', '96', '64', '83');

-- --------------------------------------------------------

--
-- Table structure for table `corepullsettings`
--

CREATE TABLE `corepullsettings` (
  `id` int(11) NOT NULL,
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

INSERT INTO `corepullsettings` (`id`, `Section`, `Sequence`, `Pressure`, `Speed`, `Position`, `Time`, `LimitSwitch`) VALUES
(1, 'Core Set A', 24, 57, 56, 91, 94, '15'),
(2, 'Core Pull A', 96, 42, 43, 59, 70, '63'),
(3, 'Core Set B', 39, 49, 95, 65, 60, '40'),
(4, 'Core Pull B', 56, 5, 19, 99, 28, '91'),
(5, 'Core Set A', 24, 57, 56, 91, 94, '15'),
(6, 'Core Pull A', 96, 42, 43, 59, 70, '63'),
(7, 'Core Set B', 39, 49, 95, 65, 60, '40'),
(8, 'Core Pull B', 56, 5, 19, 99, 28, '91'),
(9, 'Core Set A', 24, 57, 56, 91, 94, '15'),
(10, 'Core Pull A', 96, 42, 43, 59, 70, '63'),
(11, 'Core Set B', 39, 49, 95, 65, 60, '40'),
(12, 'Core Pull B', 56, 5, 19, 99, 28, '91'),
(13, 'Core Set A', 2, 38, 97, 43, 53, '75'),
(14, 'Core Pull A', 22, 23, 67, 25, 50, '5'),
(15, 'Core Set B', 46, 63, 5, 69, 68, '12'),
(16, 'Core Pull B', 54, 56, 11, 42, 44, '49'),
(17, 'Core Set A', 2, 38, 97, 43, 53, '75'),
(18, 'Core Pull A', 22, 23, 67, 25, 50, '5'),
(19, 'Core Set B', 46, 63, 5, 69, 68, '12'),
(20, 'Core Pull B', 54, 56, 11, 42, 44, '49'),
(21, 'Core Set A', 2, 38, 97, 43, 53, '75'),
(22, 'Core Pull A', 22, 23, 67, 25, 50, '5'),
(23, 'Core Set B', 46, 63, 5, 69, 68, '12'),
(24, 'Core Pull B', 54, 56, 11, 42, 44, '49');

-- --------------------------------------------------------

--
-- Table structure for table `ejectionparameters`
--

CREATE TABLE `ejectionparameters` (
  `id` int(11) NOT NULL,
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

INSERT INTO `ejectionparameters` (`id`, `AirBlowTimeA`, `AirBlowPositionA`, `AirBlowADelay`, `AirBlowTimeB`, `AirBlowPositionB`, `AirBlowBDelay`, `EjectorForwardPosition1`, `EjectorForwardPosition2`, `EjectorForwardSpeed1`, `EjectorRetractPosition1`, `EjectorRetractPosition2`, `EjectorRetractSpeed1`, `EjectorForwardSpeed2`, `EjectorForwardPressure1`, `EjectorRetractSpeed2`, `EjectorRetractPressure1`) VALUES
(1, 86, 2, 57, 31, 51, 60, 59, 75, 35, 61, 16, 19, 25, 90, 70, 44),
(2, 86, 2, 57, 31, 51, 60, 59, 75, 35, 61, 16, 19, 25, 90, 70, 44),
(3, 86, 2, 57, 31, 51, 60, 59, 75, 35, 61, 16, 19, 25, 90, 70, 44),
(4, 36, 49, 60, 7, 77, 64, 90, 66, 59, 96, 5, 85, 70, 35, 63, 43),
(5, 36, 49, 60, 7, 77, 64, 90, 66, 59, 96, 5, 85, 70, 35, 63, 43),
(6, 36, 49, 60, 7, 77, 64, 90, 66, 59, 96, 5, 85, 70, 35, 63, 43);

-- --------------------------------------------------------

--
-- Table structure for table `injectionparameters`
--

CREATE TABLE `injectionparameters` (
  `id` int(11) NOT NULL,
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

INSERT INTO `injectionparameters` (`id`, `RecoveryPosition`, `SecondStagePosition`, `Cushion`, `ScrewPosition1`, `ScrewPosition2`, `ScrewPosition3`, `InjectionSpeed1`, `InjectionSpeed2`, `InjectionSpeed3`, `InjectionPressure1`, `InjectionPressure2`, `InjectionPressure3`, `SuckBackPosition`, `SuckBackSpeed`, `SuckBackPressure`, `SprueBreak`, `SprueBreakTime`, `InjectionDelay`, `HoldingPressure1`, `HoldingPressure2`, `HoldingPressure3`, `HoldingSpeed1`, `HoldingSpeed2`, `HoldingSpeed3`, `HoldingTime1`, `HoldingTime2`, `HoldingTime3`) VALUES
(1, 84, 91, 44, 21, 56, 50, 33, 44, 82, 4, 72, 33, 77, 32, 74, 61, 47, 50, 72, 82, 20, 8, 66, 63, 10, 80, 61),
(2, 84, 91, 44, 21, 56, 50, 33, 44, 82, 4, 72, 33, 77, 32, 74, 61, 47, 50, 72, 82, 20, 8, 66, 63, 10, 80, 61),
(3, 84, 91, 44, 21, 56, 50, 33, 44, 82, 4, 72, 33, 77, 32, 74, 61, 47, 50, 72, 82, 20, 8, 66, 63, 10, 80, 61),
(4, 40, 50, 18, 9, 80, 88, 96, 2, 49, 46, 84, 49, 4, 43, 92, 65, 39, 35, 9, 11, 24, 18, 28, 92, 61, 38, 89),
(5, 40, 50, 18, 9, 80, 88, 96, 2, 49, 46, 84, 49, 4, 43, 92, 65, 39, 35, 9, 11, 24, 18, 28, 92, 61, 38, 89),
(6, 40, 50, 18, 9, 80, 88, 96, 2, 49, 46, 84, 49, 4, 43, 92, 65, 39, 35, 9, 11, 24, 18, 28, 92, 61, 38, 89);

-- --------------------------------------------------------

--
-- Table structure for table `materialcomposition`
--

CREATE TABLE `materialcomposition` (
  `id` int(11) NOT NULL,
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

INSERT INTO `materialcomposition` (`id`, `DryingTime`, `DryingTemperature`, `Material1_Type`, `Material1_Brand`, `Material1_MixturePercentage`, `Material2_Type`, `Material2_Brand`, `Material2_MixturePercentage`, `Material3_Type`, `Material3_Brand`, `Material3_MixturePercentage`, `Material4_Type`, `Material4_Brand`, `Material4_MixturePercentage`) VALUES
(1, 55, 29, '80', '27', 88, '35', '82', 17, '94', '58', 81, '95', '85', 55),
(2, 55, 29, '80', '27', 88, '35', '82', 17, '94', '58', 81, '95', '85', 55),
(3, 55, 29, '80', '27', 88, '35', '82', 17, '94', '58', 81, '95', '85', 55),
(4, 16, 69, '40', '3', 55, '15', '6', 39, '44', '56', 69, '45', '8', 68),
(5, 16, 69, '40', '3', 55, '15', '6', 39, '44', '56', 69, '45', '8', 68),
(6, 16, 69, '40', '3', 55, '15', '6', 39, '44', '56', 69, '45', '8', 68);

-- --------------------------------------------------------

--
-- Table structure for table `moldheatertemperatures`
--

CREATE TABLE `moldheatertemperatures` (
  `id` int(11) NOT NULL,
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
  `Zone16` float DEFAULT NULL,
  `MTCSetting` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `moldheatertemperatures`
--

INSERT INTO `moldheatertemperatures` (`id`, `Zone0`, `Zone1`, `Zone2`, `Zone3`, `Zone4`, `Zone5`, `Zone6`, `Zone7`, `Zone8`, `Zone9`, `Zone10`, `Zone11`, `Zone12`, `Zone13`, `Zone14`, `Zone15`, `Zone16`, `MTCSetting`) VALUES
(1, 50, 4, 73, 1, 27, 94, 32, 87, 34, 100, 75, 55, 73, 43, 42, 49, 48, 29),
(2, 50, 4, 73, 1, 27, 94, 32, 87, 34, 100, 75, 55, 73, 43, 42, 49, 48, 29),
(3, 50, 4, 73, 1, 27, 94, 32, 87, 34, 100, 75, 55, 73, 43, 42, 49, 48, 29),
(4, 14, 15, 82, 32, 57, 31, 23, 76, 90, 57, 66, 100, 60, 47, 10, 55, 47, 98),
(5, 14, 15, 82, 32, 57, 31, 23, 76, 90, 57, 66, 100, 60, 47, 10, 55, 47, 98),
(6, 14, 15, 82, 32, 57, 31, 23, 76, 90, 57, 66, 100, 60, 47, 10, 55, 47, 98);

-- --------------------------------------------------------

--
-- Table structure for table `moldoperationspecs`
--

CREATE TABLE `moldoperationspecs` (
  `id` int(11) NOT NULL,
  `MoldCode` varchar(255) DEFAULT NULL,
  `ClampingForce` varchar(255) DEFAULT NULL,
  `OperationType` varchar(255) DEFAULT NULL,
  `CoolingMedia` varchar(255) DEFAULT NULL,
  `HeatingMedia` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `moldoperationspecs`
--

INSERT INTO `moldoperationspecs` (`id`, `MoldCode`, `ClampingForce`, `OperationType`, `CoolingMedia`, `HeatingMedia`) VALUES
(1, '48', '83', '46', '42', '8'),
(2, '48', '83', '46', '42', '8'),
(3, '48', '83', '46', '42', '8'),
(4, '9', '96', '41', '36', '27'),
(5, '9', '96', '41', '36', '27'),
(6, '9', '96', '41', '36', '27');

-- --------------------------------------------------------

--
-- Table structure for table `personnel`
--

CREATE TABLE `personnel` (
  `id` int(11) NOT NULL,
  `AdjusterName` varchar(255) DEFAULT NULL,
  `QAEName` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `personnel`
--

INSERT INTO `personnel` (`id`, `AdjusterName`, `QAEName`) VALUES
(1, '1', '61'),
(2, '1', '61'),
(3, '1', '61'),
(4, '77', '41'),
(5, '77', '41'),
(6, '77', '41');

-- --------------------------------------------------------

--
-- Table structure for table `plasticizingparameters`
--

CREATE TABLE `plasticizingparameters` (
  `id` int(11) NOT NULL,
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

INSERT INTO `plasticizingparameters` (`id`, `ScrewRPM1`, `ScrewRPM2`, `ScrewRPM3`, `ScrewSpeed1`, `ScrewSpeed2`, `ScrewSpeed3`, `PlastPressure1`, `PlastPressure2`, `PlastPressure3`, `PlastPosition1`, `PlastPosition2`, `PlastPosition3`, `BackPressure1`, `BackPressure2`, `BackPressure3`, `BackPressureStartPosition`) VALUES
(1, 35, 24, 100, 87, 18, 18, 54, 78, 70, 18, 28, 94, 2, 93, 4, 63),
(2, 35, 24, 100, 87, 18, 18, 54, 78, 70, 18, 28, 94, 2, 93, 4, 63),
(3, 35, 24, 100, 87, 18, 18, 54, 78, 70, 18, 28, 94, 2, 93, 4, 63),
(4, 49, 46, 66, 4, 89, 77, 80, 12, 50, 65, 60, 80, 43, 65, 82, 61),
(5, 49, 46, 66, 4, 89, 77, 80, 12, 50, 65, 60, 80, 43, 65, 82, 61),
(6, 49, 46, 66, 4, 89, 77, 80, 12, 50, 65, 60, 80, 43, 65, 82, 61);

-- --------------------------------------------------------

--
-- Table structure for table `productdetails`
--

CREATE TABLE `productdetails` (
  `id` int(11) NOT NULL,
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

INSERT INTO `productdetails` (`id`, `ProductName`, `Color`, `MoldName`, `ProductNumber`, `CavityActive`, `GrossWeight`, `NetWeight`) VALUES
(1, '10', '78', '57', '9', 47, 55, 100),
(2, '10', '78', '57', '9', 47, 55, 100),
(3, '10', '78', '57', '9', 47, 55, 100),
(4, '15', '34', '93', '5', 12, 92, 79),
(5, '15', '34', '93', '5', 12, 92, 79),
(6, '15', '34', '93', '5', 12, 92, 79);

-- --------------------------------------------------------

--
-- Table structure for table `productmachineinfo`
--

CREATE TABLE `productmachineinfo` (
  `id` int(11) NOT NULL,
  `Date` date NOT NULL,
  `Time` time NOT NULL,
  `MachineName` varchar(255) DEFAULT NULL,
  `RunNumber` varchar(255) DEFAULT NULL,
  `Category` varchar(255) DEFAULT NULL,
  `IRN` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `productmachineinfo`
--

INSERT INTO `productmachineinfo` (`id`, `Date`, `Time`, `MachineName`, `RunNumber`, `Category`, `IRN`) VALUES
(1, '2024-01-01', '12:00:00', '35', '44', '44', '90'),
(2, '2024-01-01', '12:00:00', '35', '44', '44', '90'),
(3, '2024-01-01', '12:00:00', '35', '44', '44', '90'),
(4, '2024-01-01', '12:00:00', '98', '94', '12', '97'),
(5, '2024-01-01', '12:00:00', '98', '94', '12', '97'),
(6, '2024-01-01', '12:00:00', '98', '94', '12', '97');

-- --------------------------------------------------------

--
-- Table structure for table `timerparameters`
--

CREATE TABLE `timerparameters` (
  `id` int(11) NOT NULL,
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

INSERT INTO `timerparameters` (`id`, `FillingTime`, `HoldingTime`, `MoldOpenCloseTime`, `ChargingTime`, `CoolingTime`, `CycleTime`) VALUES
(1, 76, 19, 55, 25, 25, 9),
(2, 76, 19, 55, 25, 25, 9),
(3, 76, 19, 55, 25, 25, 9),
(4, 45, 53, 56, 66, 71, 26),
(5, 45, 53, 56, 66, 71, 26),
(6, 45, 53, 56, 66, 71, 26);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `additionalinformation`
--
ALTER TABLE `additionalinformation`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `attachments`
--
ALTER TABLE `attachments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `barrelheatertemperatures`
--
ALTER TABLE `barrelheatertemperatures`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `colorantdetails`
--
ALTER TABLE `colorantdetails`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `corepullsettings`
--
ALTER TABLE `corepullsettings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ejectionparameters`
--
ALTER TABLE `ejectionparameters`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `injectionparameters`
--
ALTER TABLE `injectionparameters`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `materialcomposition`
--
ALTER TABLE `materialcomposition`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `moldheatertemperatures`
--
ALTER TABLE `moldheatertemperatures`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `moldoperationspecs`
--
ALTER TABLE `moldoperationspecs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `personnel`
--
ALTER TABLE `personnel`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `plasticizingparameters`
--
ALTER TABLE `plasticizingparameters`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `productdetails`
--
ALTER TABLE `productdetails`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `productmachineinfo`
--
ALTER TABLE `productmachineinfo`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `timerparameters`
--
ALTER TABLE `timerparameters`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `additionalinformation`
--
ALTER TABLE `additionalinformation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `attachments`
--
ALTER TABLE `attachments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `barrelheatertemperatures`
--
ALTER TABLE `barrelheatertemperatures`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `colorantdetails`
--
ALTER TABLE `colorantdetails`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `corepullsettings`
--
ALTER TABLE `corepullsettings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `ejectionparameters`
--
ALTER TABLE `ejectionparameters`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `injectionparameters`
--
ALTER TABLE `injectionparameters`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `materialcomposition`
--
ALTER TABLE `materialcomposition`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `moldheatertemperatures`
--
ALTER TABLE `moldheatertemperatures`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `moldoperationspecs`
--
ALTER TABLE `moldoperationspecs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `personnel`
--
ALTER TABLE `personnel`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `plasticizingparameters`
--
ALTER TABLE `plasticizingparameters`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `productdetails`
--
ALTER TABLE `productdetails`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `productmachineinfo`
--
ALTER TABLE `productmachineinfo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `timerparameters`
--
ALTER TABLE `timerparameters`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
