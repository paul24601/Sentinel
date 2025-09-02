-- Users, Departments, and User-Departments Tables - Sentinel MES System
-- Safe for import into existing databases
-- Generated: September 2, 2025

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- Suppress warnings for safer import
SET sql_notes = 0;

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Table structure for table `departments`
--

CREATE TABLE IF NOT EXISTS `departments` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `departments`
--

INSERT IGNORE INTO `departments` (`id`, `name`) VALUES
(1, 'Injection'),
(3, 'Production'),
(4, 'PUVC'),
(2, 'TS');

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id_number` varchar(6) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('adjuster','supervisor','admin','Quality Control Inspection','Quality Assurance Engineer','Quality Assurance Supervisor') NOT NULL DEFAULT 'adjuster',
  `password_changed` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT IGNORE INTO `users` (`id_number`, `full_name`, `password`, `role`, `password_changed`) VALUES
('000000', 'Aeron Paul Daliva', '$2y$10$4/7GwB8Tvsk3duZzZYKynOoTxbLTPUOjTl9PqzhY2ixlaskDJjgwu', 'admin', 1),
('000001', 'Mariela Ilustre Segura', '$2y$10$6lonn1a3rhdkBzh/sf8yEONe18pI1ofsSfNrn2ebBbPRkcNjKHk2m', 'admin', 1),
('000111', 'Aeron Paul QCI Daliva', '$2y$10$U8htshxNknTHZdDrbjFP/.quuI7vTt.OxIeIXAptB8I3UvPdwIarS', 'Quality Control Inspection', 1),
('010101', 'Aeron Paul Daliva QA', '$2y$10$BucHDBhzK1GkSsgQBc5WIejDbNJtnEqcwpCS5SLDz3BKeG7m.tv16', 'Quality Assurance Engineer', 1),
('011203', 'Alexander Ocampo', '$2y$10$o3hZE5wTJg8E0k2MMe7Dge4pYAf.iNl/59U8g5tOwJ1bMxTuegz.K', 'admin', 1),
('105367', 'Romart Canda', '$2y$10$7ayVa7uX288zCMVWRWNrNeu4epkE8fu04ADv2uoAwabLL2hbIqmre', 'admin', 1),
('105368', 'Jade Cordova', '$2y$10$AAML/mg92duxTopU4M2z.eDKYpk/5yUAGTA.YmM9meaMphm0YtbQq', 'admin', 1),
('105466', 'Juswell Navallo', '$2y$10$mzIP9IaO1WaUT9I44VWaM.cGw/fQ64RX0eqNHdw6gEoMw.UgtndEG', 'Quality Assurance Engineer', 0),
('301863', 'Rolando Corpuz Guarin', '$2y$10$OTigIL/ldpAkL5IzCq7fPuNi16vSjiF7NCVm3Eb6lCGfdoSY52WMy', 'supervisor', 0),
('302997', 'Rafael Balasbas Galvez', '$2y$10$KrHxeJlDOBw1T0GMJb.lB.XWpu7an.7zWFJqkDH8mjqiLRoZ3FfJO', 'supervisor', 0),
('303642', 'James Aldea', '$2y$10$W7vZ78EQ6Zu7.x5nMyi3.eBePxkqulp6pbqZsm7LP1pWbl4kBuVCy', 'Quality Assurance Engineer', 0),
('306132', 'Albert Gutierrez', '$2y$10$dmR4.7Purr.aFGC5.O6LA.hMFDAT/5u8Rs0OFQgIhogSkIKZUoMMC', 'Quality Control Inspection', 0),
('306968', 'Ronaldo Gamol Casaje Jr', '$2y$10$U8.Z9I7106bL3e45niQ.K.itrksWvGUDR3IMV2qQ03bd0apPsJZIm', 'admin', 1),
('307477', 'Vernabe Bernabe', '$2y$10$splzD1FwezrYi2rCAyqwV.KM9mmtaMGo4VWGeInBCErLV3af7XCuu', 'Quality Control Inspection', 0),
('307583', 'Jessie Mallorca Castro', '$2y$10$rZ2GVmrJbkczMAKFs8JNb.6xETiYta9QBtWrltdPClYHRrkLJ76SS', 'admin', 1),
('307921', 'Arvin Esparas', '$2y$10$rGB0EyxzTAoCUNjzINO3d.Gm70hrsEFfRuKi8Q32qLcKVGp1mC86i', 'admin', 1),
('308193', 'John Nero Abreu', '$2y$10$BSVTEQoX3BdlnSn6JneHzO74riSWOQZsJgYpPTCQwSNp5Cl1J6wii', 'Quality Assurance Supervisor', 1),
('309125', 'Llander Elicor Poliquit', '$2y$10$v2efYCCA0i4KytBNGBlFh.uU0Lp/CJvNj5.ra7v7N1GiB2pKPlg8u', 'adjuster', 1),
('309246', 'Jade Eduardo Derramas', '$2y$10$8nkb7Fngbh1k893AuOYSVO2AK5UrZ/sFter2/V7yvK7pOqccJHANC', 'adjuster', 1),
('309325', 'Sherwin Ramos Sernechez', '$2y$10$ZVUWjSBtMG3ESUlrfzfWB.MTtR2fXtQLUZSbpgjAy9qAPkGxBQVdu', 'adjuster', 1),
('309487', 'Kaishu San Jose', '$2y$10$U1H03nZluj1O2rFuY38rbOwFjyQlimLGbzRcvw0pa5.BhaiVqPjby', 'adjuster', 1),
('309535', 'Gilbert John Colo Delos Reyes', '$2y$10$//AKWYi1OtJJrolPqrySMeOn0cNLjrTNIBVnaIlt45mPz3ZX/Lh2a', 'adjuster', 1),
('309582', 'Carl Francisco', '$2y$10$fRr4FKvs.izmn67UynwYje68.XwZ51TObIAuWmJbNKim9B.xpDFUC', 'Quality Assurance Supervisor', 0),
('309603', 'Ian Ilustresimo', '$2y$10$o2hUe3jne/pCIBpZqSlc7ufIWC13kk0GBbGu3dRSddO5Jksc3vhI2', 'Quality Assurance Supervisor', 0),
('309787', 'Stephanie Iris Sapno', '$2y$10$fEpDAYlcQW0M7LbUvds8W.evv5IsNv0T6M66z1ult4nJrk5evJOja', 'Quality Assurance Supervisor', 0);

--
-- Table structure for table `user_departments`
--

CREATE TABLE IF NOT EXISTS `user_departments` (
  `id` int(11) NOT NULL,
  `user_id_number` varchar(6) NOT NULL,
  `department_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_departments`
--

INSERT IGNORE INTO `user_departments` (`id`, `user_id_number`, `department_id`) VALUES
(4, '000111', 1),
(5, '000111', 3);

-- --------------------------------------------------------

--
-- Safe Primary Key Creation
-- Only adds primary keys if they don't already exist
--

-- Check and add primary key for departments
SET @sql = (SELECT IF(
    (SELECT COUNT(*) FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
     WHERE TABLE_SCHEMA = DATABASE() 
     AND TABLE_NAME = 'departments' 
     AND CONSTRAINT_NAME = 'PRIMARY') = 0,
    'ALTER TABLE `departments` ADD PRIMARY KEY (`id`)',
    'SELECT "Primary key already exists on departments" as message'
));

PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Check and add primary key for users
SET @sql = (SELECT IF(
    (SELECT COUNT(*) FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
     WHERE TABLE_SCHEMA = DATABASE() 
     AND TABLE_NAME = 'users' 
     AND CONSTRAINT_NAME = 'PRIMARY') = 0,
    'ALTER TABLE `users` ADD PRIMARY KEY (`id_number`)',
    'SELECT "Primary key already exists on users" as message'
));

PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Check and add primary key for user_departments
SET @sql = (SELECT IF(
    (SELECT COUNT(*) FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
     WHERE TABLE_SCHEMA = DATABASE() 
     AND TABLE_NAME = 'user_departments' 
     AND CONSTRAINT_NAME = 'PRIMARY') = 0,
    'ALTER TABLE `user_departments` ADD PRIMARY KEY (`id`)',
    'SELECT "Primary key already exists on user_departments" as message'
));

PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Restore settings and commit
SET sql_notes = 1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
