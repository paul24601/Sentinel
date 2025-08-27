-- Injection Molding Parameters Database
-- Cleaned for remote hosting deployment
-- Generated on: 2025-08-25 03:20:12
-- Original file size: 57,222 bytes
-- 
-- INSTRUCTIONS:
-- 1. Create database in cPanel (e.g., 'injmold' becomes 'u158529957_injmold')
-- 2. Import this file via phpMyAdmin
-- 3. Ensure user has ALL PRIVILEGES on the database

-- Table structure for table `additionalinformation`
CREATE TABLE IF NOT EXISTS `additionalinformation` (
  `id` int(11) NOT NULL,
  `record_id` varchar(20) DEFAULT NULL,
  `Info` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `additionalinformation` (`id`, `record_id`, `Info`) VALUES
(13, 'PARAM_20250522_988df', 'Production run completed with standard parameters. All quality checks passed. Material batch: MB-3604'),
(20, 'PARAM_20250718_1333e', ''),
(21, 'PARAM_20250718_210a5', ''),
(22, 'PARAM_20250718_dd40b', 'W/ HPP'),
(25, 'PARAM_20250718_18709', ''),
(26, 'PARAM_20250718_634d7', ''),
(27, 'PARAM_20250718_1fe34', '2 GATES ONLY DUE TO GAS TRAP.'),
(28, 'PARAM_20250721_15806', 'AIR BLOW CONNECTED TO THE CORE SEQUENCE \r\nAn air line is connected to the Mold cavity; it blows air due to the mold open.\r\nCore mold \r\n1 1 - 1 3.0 7 - 1 2.0\r\n'),
(29, 'PARAM_20250721_73997', 'AIR BLOW CONNECTED TO THE CORE SEQUENCE \r\nAn air line is connected to the Mold cavity; it blows air due to the mold open.\r\nCore mold \r\n1 1 - 1 3.0 7 - 1 2.0\r\n');

CREATE TABLE IF NOT EXISTS `attachments` (
  `id` int(11) NOT NULL,
  `record_id` varchar(20) DEFAULT NULL,
  `FileName` varchar(255) NOT NULL,
  `FilePath` varchar(255) NOT NULL,
  `FileType` varchar(100) NOT NULL,
  `UploadedAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `attachments` (`id`, `record_id`, `FileName`, `FilePath`, `FileType`, `UploadedAt`) VALUES
(29, 'PARAM_20250522_988df', 'IMG_0711.jpeg', 'parameters/uploads/IRN64463_20250522_1436_CLF750B_RN1660_682ec60b995f0.jpeg', 'image/jpeg', '2025-05-22 06:36:59'),
(38, 'PARAM_20250718_dd40b', '1000001234.jpg', 'parameters/uploads/_20250718_1147_CLF750B__6879c43ade189.jpg', 'image/jpeg', '2025-07-18 03:49:14'),
(39, 'PARAM_20250718_dd40b', '1000001233.jpg', 'parameters/uploads/_20250718_1147_CLF750B__6879c43adf604.jpg', 'image/jpeg', '2025-07-18 03:49:14'),
(41, 'PARAM_20250718_18709', '1000001238.jpg', 'parameters/uploads/_20250718_1328_MIT1050B__6879db8f18f69.jpg', 'image/jpeg', '2025-07-18 05:28:47');

CREATE TABLE IF NOT EXISTS `barrelheatertemperatures` (
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

INSERT INTO `barrelheatertemperatures` (`id`, `record_id`, `Zone0`, `Zone1`, `Zone2`, `Zone3`, `Zone4`, `Zone5`, `Zone6`, `Zone7`, `Zone8`, `Zone9`, `Zone10`, `Zone11`, `Zone12`, `Zone13`, `Zone14`, `Zone15`, `Zone16`) VALUES
(18, 'PARAM_20250522_988df', 106.6, 121.1, 85.6, 238.9, 237.1, 70.4, 130.4, 190.1, 128.5, 144.8, 131.8, 43.8, 182.2, 248.2, 211.2, 61.7, 198.4),
(33, 'PARAM_20250718_1333e', 220, 220, 210, 200, 200, 200, 200, 200, 190, 190, 0, 0, 0, 0, 0, 0, 0),
(34, 'PARAM_20250718_210a5', 230, 230, 220, 220, 220, 220, 210, 210, 210, 200, 200, 190, 0, 0, 0, 0, 0),
(35, 'PARAM_20250718_dd40b', 230, 230, 220, 220, 220, 220, 210, 210, 210, 200, 200, 190, 0, 0, 0, 0, 0),
(39, 'PARAM_20250718_18709', 250, 250, 240, 230, 200, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(40, 'PARAM_20250718_634d7', 235, 230, 220, 210, 200, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(41, 'PARAM_20250718_1fe34', 220, 210, 210, 210, 180, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(42, 'PARAM_20250721_15806', 240, 220, 210, 200, 200, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(43, 'PARAM_20250721_73997', 240, 220, 210, 200, 200, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);

CREATE TABLE IF NOT EXISTS `colorantdetails` (
  `id` int(11) NOT NULL,
  `record_id` varchar(20) DEFAULT NULL,
  `Colorant` varchar(255) DEFAULT NULL,
  `Color` varchar(255) DEFAULT NULL,
  `Dosage` varchar(255) DEFAULT NULL,
  `Stabilizer` varchar(255) DEFAULT NULL,
  `StabilizerDosage` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `colorantdetails` (`id`, `record_id`, `Colorant`, `Color`, `Dosage`, `Stabilizer`, `StabilizerDosage`) VALUES
(18, 'PARAM_20250522_988df', 'Blue', 'Clear', 'White', 'White', 'Blue'),
(33, 'PARAM_20250718_1333e', 'MBW 5937', 'BLUE', '375', 'UV-STAB', '62.5'),
(34, 'PARAM_20250718_210a5', 'MB W5937', 'BLUE', '375', 'UV STAB', '62.5'),
(35, 'PARAM_20250718_dd40b', 'MB W5937', 'BLUE', '375', 'UV STAB', '62.5'),
(39, 'PARAM_20250718_18709', 'MB W5337', 'BLUE', '375', 'UV STAB', '62.5'),
(40, 'PARAM_20250718_634d7', 'MB W5937', 'BLUE', '375', 'UV STAB E7268', '62.5'),
(41, 'PARAM_20250718_1fe34', 'BLUE MB W5937', 'BLUE', '375', 'UV STAB E7268', '62.5'),
(42, 'PARAM_20250721_15806', 'MB AF9548', 'GRAY', '500', '', ''),
(43, 'PARAM_20250721_73997', 'MB AF9548', 'GRAY', '500', '', '');

CREATE TABLE IF NOT EXISTS `corepullsettings` (
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

INSERT INTO `corepullsettings` (`id`, `record_id`, `Section`, `Sequence`, `Pressure`, `Speed`, `Position`, `Time`, `LimitSwitch`) VALUES
(43, 'PARAM_20250522_988df', 'Core Set A', 65, 163.4, 20.3, 30.41, 13.1, '43.25'),
(44, 'PARAM_20250522_988df', 'Core Pull A', 43, 19.4, 66.2, 17.43, 19.9, '95.49'),
(45, 'PARAM_20250522_988df', 'Core Set B', 50, 59.2, 39.3, 64.03, 3.9, '33.33'),
(46, 'PARAM_20250522_988df', 'Core Pull B', 22, 179.6, 31.6, 10.13, 21.2, '4.37'),
(84, 'PARAM_20250718_1333e', 'Core Set A', 1, 50, 15, 700, 3, ''),
(85, 'PARAM_20250718_1333e', 'Core Pull A', 1, 98, 9, 700, 3, ''),
(86, 'PARAM_20250718_dd40b', 'Core Set A', 0, 65, 35, 620, 6, ''),
(87, 'PARAM_20250718_dd40b', 'Core Pull A', 1, 85, 70, 600, 4, '');

CREATE TABLE IF NOT EXISTS `ejectionparameters` (
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

INSERT INTO `ejectionparameters` (`id`, `record_id`, `AirBlowTimeA`, `AirBlowPositionA`, `AirBlowADelay`, `AirBlowTimeB`, `AirBlowPositionB`, `AirBlowBDelay`, `EjectorForwardPosition1`, `EjectorForwardPosition2`, `EjectorForwardSpeed1`, `EjectorRetractPosition1`, `EjectorRetractPosition2`, `EjectorRetractSpeed1`, `EjectorForwardSpeed2`, `EjectorForwardPressure1`, `EjectorRetractSpeed2`, `EjectorRetractPressure1`, `EjectorForwardTime`, `EjectorRetractTime`, `EjectorForwardPressure2`, `EjectorRetractPressure2`) VALUES
(13, 'PARAM_20250522_988df', 16.6, 15.78, 74.34, 8, 112.89, 70.43, 70.88, 147.32, 38.7, 137.19, 57.83, 63.1, 49.9, 22.6, 66.2, 15, NULL, NULL, NULL, NULL),
(28, 'PARAM_20250718_1333e', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL),
(29, 'PARAM_20250718_210a5', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL),
(30, 'PARAM_20250718_dd40b', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL),
(33, 'PARAM_20250718_18709', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL),
(34, 'PARAM_20250718_634d7', 0, 0, 0, 0, 0, 0, 30, 0, 0, 65, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL),
(35, 'PARAM_20250718_1fe34', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL),
(36, 'PARAM_20250721_15806', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL),
(37, 'PARAM_20250721_73997', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL);

CREATE TABLE IF NOT EXISTS `injectionparameters` (
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

INSERT INTO `injectionparameters` (`id`, `record_id`, `RecoveryPosition`, `SecondStagePosition`, `Cushion`, `ScrewPosition1`, `ScrewPosition2`, `ScrewPosition3`, `InjectionSpeed1`, `InjectionSpeed2`, `InjectionSpeed3`, `InjectionPressure1`, `InjectionPressure2`, `InjectionPressure3`, `SuckBackPosition`, `SuckBackSpeed`, `SuckBackPressure`, `SprueBreak`, `SprueBreakTime`, `InjectionDelay`, `HoldingPressure1`, `HoldingPressure2`, `HoldingPressure3`, `HoldingSpeed1`, `HoldingSpeed2`, `HoldingSpeed3`, `HoldingTime1`, `HoldingTime2`, `HoldingTime3`, `ScrewPosition4`, `ScrewPosition5`, `ScrewPosition6`, `InjectionSpeed4`, `InjectionSpeed5`, `InjectionSpeed6`, `InjectionPressure4`, `InjectionPressure5`, `InjectionPressure6`) VALUES
(14, 'PARAM_20250522_988df', 71.74, 23.68, 4.86, 122.39, 20.88, 14.38, 21.8, 26.5, 38.4, 152.2, 124, 185.9, 29.68, 97.1, 148.6, 21.14, 11.7, 89.38, 199.5, 55.7, 186.2, 44.9, 16.3, 94.4, 16.4, 1.8, 3.4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(29, 'PARAM_20250718_1333e', 190, 7, 1, 150, 75, 30, 45, 45, 45, 42, 45, 50, 15, 0, 0, 0, 0, 0, 35, 35, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(30, 'PARAM_20250718_210a5', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(31, 'PARAM_20250718_dd40b', 133, 5, 0, 110, 45, 20, 50, 60, 65, 60, 60, 60, 15, 10, 15, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(34, 'PARAM_20250718_18709', 222, 15, 0.1, 222, 170, 40, 65, 75, 5, 65, 70, 60, 15, 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(35, 'PARAM_20250718_634d7', 35, 16, 1.9, 305, 259, 90, 53, 85, 60, 60, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 20, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(36, 'PARAM_20250718_1fe34', 230, 11, 10.1, 230, 208, 80, 65, 70, 70, 70, 70, 70, 15, 0, 0, 0, 0, 0, 50, 40, 30, 25, 25, 25, 2, 2, 1.96, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(37, 'PARAM_20250721_15806', 115, 2, 0, 115, 60, 30, 99, 99, 99, 99, 99, 99, 15, 1, 0, 0, 0, 0, 25, 0, 0, 0, 0, 0, 3, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(38, 'PARAM_20250721_73997', 115, 2, 0, 115, 60, 30, 99, 99, 99, 99, 99, 99, 15, 1, 0, 0, 0, 0, 25, 0, 0, 0, 0, 0, 3, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

CREATE TABLE IF NOT EXISTS `materialcomposition` (
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

INSERT INTO `materialcomposition` (`id`, `record_id`, `DryingTime`, `DryingTemperature`, `Material1_Type`, `Material1_Brand`, `Material1_MixturePercentage`, `Material2_Type`, `Material2_Brand`, `Material2_MixturePercentage`, `Material3_Type`, `Material3_Brand`, `Material3_MixturePercentage`, `Material4_Type`, `Material4_Brand`, `Material4_MixturePercentage`) VALUES
(18, 'PARAM_20250522_988df', 25, 51.4, 'Sample-4004', 'Sample-7370', 39.74, 'Sample-6226', 'Sample-6542', 62.08, 'Sample-1844', 'Sample-9003', 35.15, 'Sample-4119', 'Sample-6264', 48.38),
(33, 'PARAM_20250718_1333e', 0, 0, 'PEHD', 'EVALENE 3601 NAT', 100, '', '', 0, '', '', 0, '', '', 0),
(34, 'PARAM_20250718_210a5', 0, 0, 'PEHD', 'EVALENE 8601 NAT', 70, 'PEHD', 'PEPSI BLUE G', 30, '', '', 0, '', '', 0),
(35, 'PARAM_20250718_dd40b', 0, 0, 'PEHD', 'EVALENE 8601 NAT', 70, 'PEHD', 'PEPSI BLUE G', 30, '', '', 0, '', '', 0),
(39, 'PARAM_20250718_18709', 0, 0, 'PEHD', 'EVALENE 8601', 80, 'PEHD', 'PEPSI BLUE-G', 20, '', '', 0, '', '', 0),
(40, 'PARAM_20250718_634d7', 0, 0, 'PEHD', 'EVALENE 8601 NATURAL', 80, 'PEHD', 'PEPSI BLUE REGRIND', 20, '', '', 0, '', '', 0),
(41, 'PARAM_20250718_1fe34', 0, 0, 'PEHD', 'EVALENE 8601 NATURAL', 100, '', '', 0, '', '', 0, '', '', 0),
(42, 'PARAM_20250721_15806', 0, 0, 'SM340', 'PP TITANPRO', 100, 'MC-04_01', 'PELD', 10, '', '', 0, '', '', 0),
(43, 'PARAM_20250721_73997', 0, 0, 'SM340', 'PP TITANPRO', 100, 'MC-04_01', 'PELD', 10, '', '', 0, '', '', 0);

CREATE TABLE IF NOT EXISTS `moldcloseparameters` (
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

INSERT INTO `moldcloseparameters` (`id`, `record_id`, `MoldClosePos1`, `MoldClosePos2`, `MoldClosePos3`, `MoldClosePos4`, `MoldClosePos5`, `MoldClosePos6`, `MoldCloseSpd1`, `MoldCloseSpd2`, `MoldCloseSpd3`, `MoldCloseSpd4`, `MoldCloseSpd5`, `MoldCloseSpd6`, `MoldClosePressure1`, `MoldClosePressure2`, `MoldClosePressure3`, `MoldClosePressure4`, `PCLORLP`, `PCHORHP`, `LowPresTimeLimit`, `created_at`, `updated_at`) VALUES

CREATE TABLE IF NOT EXISTS `moldheatertemperatures` (
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
(38, 'PARAM_20250718_18709', 210, 230, 280, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(39, 'PARAM_20250718_634d7', 255, 220, 250, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(40, 'PARAM_20250718_1fe34', 180, 170, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(41, 'PARAM_20250721_15806', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(42, 'PARAM_20250721_73997', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);

CREATE TABLE IF NOT EXISTS `moldopenparameters` (
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

INSERT INTO `moldopenparameters` (`id`, `record_id`, `MoldOpenPos1`, `MoldOpenPos2`, `MoldOpenPos3`, `MoldOpenPos4`, `MoldOpenPos5`, `MoldOpenPos6`, `MoldOpenSpd1`, `MoldOpenSpd2`, `MoldOpenSpd3`, `MoldOpenSpd4`, `MoldOpenSpd5`, `MoldOpenSpd6`, `MoldOpenPressure1`, `MoldOpenPressure2`, `MoldOpenPressure3`, `MoldOpenPressure4`, `MoldOpenPressure5`, `MoldOpenPressure6`, `created_at`, `updated_at`) VALUES

CREATE TABLE IF NOT EXISTS `moldoperationspecs` (
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
(39, 'PARAM_20250718_18709', '4072', '95', 'automatic', 'Chilled', 'MHC', NULL, NULL, 'Chilled', 'Chilled', NULL),
(40, 'PARAM_20250718_634d7', '3764', '89', 'automatic', 'Chilled', 'MTC', NULL, NULL, 'Chilled', 'Chilled', NULL),
(41, 'PARAM_20250718_1fe34', '3269', '99', 'semi-automatic', 'Chilled', 'MHC', NULL, NULL, 'Chilled', 'Chilled', NULL),
(42, 'PARAM_20250721_15806', '4125', '85@55', 'semi-automatic', 'Normal', 'MHC', NULL, NULL, 'Normal', 'Normal', NULL),
(43, 'PARAM_20250721_73997', '4125', '85@55', 'semi-automatic', 'Normal', 'MHC', NULL, NULL, 'Normal', 'Normal', NULL);

CREATE TABLE IF NOT EXISTS `parameters_submissions` (
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

CREATE TABLE IF NOT EXISTS `parameter_records` (
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

INSERT INTO `parameter_records` (`record_id`, `submission_date`, `status`, `review_status`, `first_reviewed_date`, `last_reviewed_date`, `reviewer_count`, `submitted_by`, `title`, `description`) VALUES
('PARAM_20250522_988df', '2025-05-22 06:36:59', 'active', 'pending', NULL, NULL, 0, 'Jessie Mallorca Castro', 'CLF 750B - Plastic Container', 'Production run completed with standard parameters. All quality checks passed. Material batch: MB-3604'),
('PARAM_20250718_1333e', '2025-07-18 03:06:22', 'active', 'pending', NULL, NULL, 0, 'Romart Canda', 'CLF 750A - CM-5', ''),
('PARAM_20250718_18709', '2025-07-18 05:28:47', 'active', 'pending', NULL, NULL, 0, 'Romart Canda', 'MIT 1050B - 1L PEPSI', ''),
('PARAM_20250718_1fe34', '2025-07-18 09:42:21', 'active', 'pending', NULL, NULL, 0, 'Jade Eduardo Derramas', 'TOS 850B - CNS-3C', '2 GATES ONLY DUE TO GAS TRAP.'),
('PARAM_20250718_210a5', '2025-07-18 03:22:56', 'active', 'pending', NULL, NULL, 0, 'Romart Canda', 'CLF 750B - 8oz Shell Plastic Crate (PEPSI)', ''),
('PARAM_20250718_634d7', '2025-07-18 06:04:37', 'active', 'pending', NULL, NULL, 0, 'Romart Canda', 'TOS 850B - 1L Shell Plastic Crate (Pepsi)', ''),

('PARAM_20250718_dd40b', '2025-07-18 03:49:14', 'active', 'pending', NULL, NULL, 0, 'Romart Canda', 'CLF 750B - 8oz Shell Plastic Crate (PEPSI)', 'W/ HPP'),
('PARAM_20250721_15806', '2025-07-21 02:44:59', 'active', 'pending', NULL, NULL, 0, 'Kaishu San Jose', 'MIT 650D - UTILITY BIN 10L RECTANGULAR', 'AIR BLOW CONNECTED TO THE CORE SEQUENCE \r\nAn air line is connected to the Mold cavity; it blows air due to the mold open.\r\nCore mold \r\n1 1 - 1 3.0 7 - 1 2.0\r\n'),
('PARAM_20250721_73997', '2025-07-21 02:46:43', 'active', 'pending', NULL, NULL, 0, 'Kaishu San Jose', 'MIT 650D - UTILITY BIN 10L RECTANGULAR', 'AIR BLOW CONNECTED TO THE CORE SEQUENCE \r\nAn air line is connected to the Mold cavity; it blows air due to the mold open.\r\nCore mold \r\n1 1 - 1 3.0 7 - 1 2.0\r\n');

CREATE TABLE IF NOT EXISTS `personnel` (
  `id` int(11) NOT NULL,
  `record_id` varchar(20) DEFAULT NULL,
  `AdjusterName` varchar(255) DEFAULT NULL,
  `QAEName` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `personnel` (`id`, `record_id`, `AdjusterName`, `QAEName`) VALUES
(13, 'PARAM_20250522_988df', 'Jessie Mallorca Castro', 'QA Robert Brown'),
(20, 'PARAM_20250718_1333e', 'Romart Canda', ''),
(21, 'PARAM_20250718_210a5', 'Romart Canda', ''),
(22, 'PARAM_20250718_dd40b', 'Romart Canda', ''),
(25, 'PARAM_20250718_18709', 'Romart Canda', ''),
(26, 'PARAM_20250718_634d7', 'Romart Canda', ''),
(27, 'PARAM_20250718_1fe34', 'Jade Eduardo Derramas', 'Carl Francisco'),
(28, 'PARAM_20250721_15806', 'Kaishu San Jose', 'Ian ilustresimo'),
(29, 'PARAM_20250721_73997', 'Kaishu San Jose', 'Ian ilustresimo');

CREATE TABLE IF NOT EXISTS `plasticizingparameters` (
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

INSERT INTO `plasticizingparameters` (`id`, `record_id`, `ScrewRPM1`, `ScrewRPM2`, `ScrewRPM3`, `ScrewSpeed1`, `ScrewSpeed2`, `ScrewSpeed3`, `PlastPressure1`, `PlastPressure2`, `PlastPressure3`, `PlastPosition1`, `PlastPosition2`, `PlastPosition3`, `BackPressure1`, `BackPressure2`, `BackPressure3`, `BackPressureStartPosition`) VALUES
(14, 'PARAM_20250522_988df', 106.4, 147.4, 81.7, 63.1, 55.1, 37.2, 62.9, 107.3, 49.4, 77.13, 80.12, 73.69, 101.5, 55.1, 75, 180.6),
(29, 'PARAM_20250718_1333e', 15, 15, 15, 0, 0, 0, 0, 0, 0, 0, 0, 0, 5, 5, 0, 0),
(30, 'PARAM_20250718_210a5', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(31, 'PARAM_20250718_dd40b', 25, 25, 25, 40, 40, 40, 0, 0, 0, 0, 0, 0, 3, 3, 3, 0),
(34, 'PARAM_20250718_18709', 65, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(35, 'PARAM_20250718_634d7', 55, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(36, 'PARAM_20250718_1fe34', 0, 0, 0, 45, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(37, 'PARAM_20250721_15806', 0, 0, 0, 55, 55, 55, 0, 0, 0, 0, 20, 60, 0, 3, 3, 65),
(38, 'PARAM_20250721_73997', 0, 0, 0, 55, 55, 55, 0, 0, 0, 0, 20, 60, 0, 3, 3, 65);

CREATE TABLE IF NOT EXISTS `productdetails` (
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
(39, 'PARAM_20250718_18709', '1L PEPSI', 'BLUE', '1L PEPSO #4072', '01', 1, 1801, 0),
(40, 'PARAM_20250718_634d7', '1L Shell Plastic Crate (Pepsi)', 'BLUE', '', '01', 1, 1994, 0),
(41, 'PARAM_20250718_1fe34', 'CNS-3C', 'BLUE', 'CNS-3C', '02', 1, 0, 0),
(42, 'PARAM_20250721_15806', 'UTILITY BIN 10L RECTANGULAR', 'Light Gray', 'UTILITY BIN', '', 1, 447, 440),
(43, 'PARAM_20250721_73997', 'UTILITY BIN 10L RECTANGULAR', 'Light Gray', 'UTILITY BIN', '', 1, 447, 440);

CREATE TABLE IF NOT EXISTS `productmachineinfo` (
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
(39, 'PARAM_20250718_18709', '2025-07-18', '13:28:00', '06:20:00', '08:50:00', 'MIT 1050B', '', 'Mass Production', ''),
(40, 'PARAM_20250718_634d7', '2025-07-18', '14:03:00', '11:49:00', '13:07:00', 'TOS 850B', '', 'Mass Production', ''),
(41, 'PARAM_20250718_1fe34', '2025-07-18', '17:42:00', '15:30:00', '17:30:00', 'TOS 850B', '', 'Mass Production', 'T8BMP-00324-20'),
(42, 'PARAM_20250721_15806', '2025-07-21', '10:44:00', '10:08:00', '10:31:00', 'MIT 650D', '', 'Mass Production', 'M6DMP-00201-20'),
(43, 'PARAM_20250721_73997', '2025-07-21', '10:45:00', '10:08:00', '10:31:00', 'MIT 650D', '', 'Mass Production', 'M6DMP-00201-20');

CREATE TABLE IF NOT EXISTS `supervisor_reviews` (
  `id` int(11) NOT NULL,
  `record_id` varchar(50) NOT NULL,
  `supervisor_name` varchar(100) NOT NULL,
  `supervisor_role` varchar(50) DEFAULT 'Supervisor',
  `review_date` datetime DEFAULT current_timestamp(),
  `review_notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `supervisor_reviews` (`id`, `record_id`, `supervisor_name`, `supervisor_role`, `review_date`, `review_notes`) VALUES

CREATE TABLE IF NOT EXISTS `timerparameters` (
  `id` int(11) NOT NULL,
  `record_id` varchar(20) DEFAULT NULL,
  `FillingTime` float DEFAULT NULL,
  `HoldingTime` float DEFAULT NULL,
  `MoldOpenCloseTime` float DEFAULT NULL,
  `ChargingTime` float DEFAULT NULL,
  `CoolingTime` float DEFAULT NULL,
  `CycleTime` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(39, 'PARAM_20250718_18709', 6.93, 2, 0, 14.7, 25, 53),
(40, 'PARAM_20250718_634d7', 4.68, 2, 26.31, 28.51, 40, 73.49),
(41, 'PARAM_20250718_1fe34', 6.04, 5.96, 0, 30.73, 65, 107.7),
(42, 'PARAM_20250721_15806', 3.27, 3, 14.77, 15.25, 35, 74.59),
(43, 'PARAM_20250721_73997', 3.27, 3, 14.77, 15.25, 35, 74.59);

CREATE TABLE IF NOT EXISTS `user_activity` (
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

INSERT INTO `user_activity` (`id`, `user_id_number`, `full_name`, `activity_type`, `record_id`, `session_data`, `additional_info`, `ip_address`, `user_agent`, `created_at`) VALUES

ALTER TABLE `additionalinformation`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_record_id_additionalinformation` (`record_id`);

ALTER TABLE `attachments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_record_id_attachments` (`record_id`);

ALTER TABLE `barrelheatertemperatures`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_record_id_barrelheatertemperatures` (`record_id`);

ALTER TABLE `colorantdetails`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_record_id_colorantdetails` (`record_id`);

ALTER TABLE `corepullsettings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_record_id_corepullsettings` (`record_id`);

ALTER TABLE `ejectionparameters`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_record_id_ejectionparameters` (`record_id`);

ALTER TABLE `injectionparameters`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_record_id_injectionparameters` (`record_id`);

ALTER TABLE `materialcomposition`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_record_id_materialcomposition` (`record_id`);

ALTER TABLE `moldcloseparameters`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_record_id_moldclose` (`record_id`);

ALTER TABLE `moldheatertemperatures`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_record_id_moldheatertemperatures` (`record_id`);

ALTER TABLE `moldopenparameters`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_record_id_moldopen` (`record_id`);

ALTER TABLE `moldoperationspecs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_record_id_moldoperationspecs` (`record_id`);

ALTER TABLE `parameters_submissions`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `parameter_records`
  ADD PRIMARY KEY (`record_id`),
  ADD KEY `idx_review_status` (`review_status`);

ALTER TABLE `personnel`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_record_id_personnel` (`record_id`);

ALTER TABLE `plasticizingparameters`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_record_id_plasticizingparameters` (`record_id`);

ALTER TABLE `productdetails`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_record_id_productdetails` (`record_id`);

ALTER TABLE `productmachineinfo`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_record_id_productmachineinfo` (`record_id`);

ALTER TABLE `supervisor_reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_record_id` (`record_id`),
  ADD KEY `idx_supervisor` (`supervisor_name`);

ALTER TABLE `timerparameters`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_record_id_timerparameters` (`record_id`);

ALTER TABLE `user_activity`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `additionalinformation`

ALTER TABLE `attachments`

ALTER TABLE `barrelheatertemperatures`

ALTER TABLE `colorantdetails`

ALTER TABLE `corepullsettings`

ALTER TABLE `ejectionparameters`

ALTER TABLE `injectionparameters`

ALTER TABLE `materialcomposition`

ALTER TABLE `moldcloseparameters`

ALTER TABLE `moldheatertemperatures`

ALTER TABLE `moldopenparameters`

ALTER TABLE `moldoperationspecs`

ALTER TABLE `parameters_submissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `personnel`

ALTER TABLE `plasticizingparameters`

ALTER TABLE `productdetails`

ALTER TABLE `productmachineinfo`

ALTER TABLE `supervisor_reviews`

ALTER TABLE `timerparameters`

ALTER TABLE `user_activity`

ALTER TABLE `supervisor_reviews`
  ADD CONSTRAINT `supervisor_reviews_ibfk_1` FOREIGN KEY (`record_id`) REFERENCES `parameter_records` (`record_id`) ON DELETE CASCADE;

;
;
;