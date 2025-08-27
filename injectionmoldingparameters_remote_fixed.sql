-- phpMyAdmin SQL Dump (Modified for Remote Deployment)
-- Fixed for shared hosting environment
-- Database: u158529957_spmc_injmold should be used instead of injectionmoldingparameters
--
-- IMPORTANT: Make sure your database name in cPanel is: u158529957_injmold
-- The full database name will be: u158529957_injmold
--
-- Before running this script:
-- 1. Create database in cPanel with name 'injmold' (it will become u158529957_injmold)
-- 2. Make sure user u158529957_spmc_injmold has ALL PRIVILEGES on u158529957_injmold
--

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- Select the correct database (use your actual database name)
-- USE `u158529957_injmold`;

-- --------------------------------------------------------

--
-- Table structure for table `additionalinformation`
--

CREATE TABLE IF NOT EXISTS `additionalinformation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `record_id` varchar(20) DEFAULT NULL,
  `Info` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Table structure for table `attachments`
--

CREATE TABLE IF NOT EXISTS `attachments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `record_id` varchar(20) DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `file_name` varchar(255) DEFAULT NULL,
  `file_size` int(11) DEFAULT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Table structure for table `barrelheatertemperatures`
--

CREATE TABLE IF NOT EXISTS `barrelheatertemperatures` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `record_id` varchar(20) DEFAULT NULL,
  `zone_1` decimal(5,2) DEFAULT NULL,
  `zone_2` decimal(5,2) DEFAULT NULL,
  `zone_3` decimal(5,2) DEFAULT NULL,
  `zone_4` decimal(5,2) DEFAULT NULL,
  `zone_5` decimal(5,2) DEFAULT NULL,
  `zone_6` decimal(5,2) DEFAULT NULL,
  `zone_7` decimal(5,2) DEFAULT NULL,
  `zone_8` decimal(5,2) DEFAULT NULL,
  `zone_9` decimal(5,2) DEFAULT NULL,
  `zone_10` decimal(5,2) DEFAULT NULL,
  `zone_11` decimal(5,2) DEFAULT NULL,
  `zone_12` decimal(5,2) DEFAULT NULL,
  `nozzle` decimal(5,2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Continue with the rest of the tables...
-- Note: I'll create a deployment script to handle the full import
