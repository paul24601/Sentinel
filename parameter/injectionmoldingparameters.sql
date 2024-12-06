-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 02, 2024 at 05:21 AM
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
-- Table structure for table `additionalinfo`
--

CREATE TABLE `additionalinfo` (
  `ID` int(11) NOT NULL,
  `Info` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `attachments`
--

CREATE TABLE `attachments` (
  `ID` int(11) NOT NULL,
  `ImagePath` varchar(255) DEFAULT NULL,
  `VideoPath` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `colorantdetails`
--

CREATE TABLE `colorantdetails` (
  `ID` int(11) NOT NULL,
  `Colorant` varchar(255) DEFAULT NULL,
  `Color` varchar(255) DEFAULT NULL,
  `Dosage` decimal(10,2) DEFAULT NULL,
  `Stabilizer` varchar(255) DEFAULT NULL,
  `StabilizerDosage` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `coresettings`
--

CREATE TABLE `coresettings` (
  `ID` int(11) NOT NULL,
  `CoreSet` varchar(255) DEFAULT NULL,
  `Sequence` int(11) DEFAULT NULL,
  `Pressure` decimal(10,2) DEFAULT NULL,
  `Speed` decimal(10,2) DEFAULT NULL,
  `Position` decimal(10,2) DEFAULT NULL,
  `Time` decimal(10,2) DEFAULT NULL,
  `LimitSwitch` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ejectionparameters`
--

CREATE TABLE `ejectionparameters` (
  `ID` int(11) NOT NULL,
  `AirBlowTime` decimal(10,2) DEFAULT NULL,
  `AirBlowPosition` decimal(10,2) DEFAULT NULL,
  `AirBlowDelay` decimal(10,2) DEFAULT NULL,
  `EjectorForwardPosition` decimal(10,2) DEFAULT NULL,
  `EjectorForwardSpeed` decimal(10,2) DEFAULT NULL,
  `EjectorRetractPosition` decimal(10,2) DEFAULT NULL,
  `EjectorRetractSpeed` decimal(10,2) DEFAULT NULL,
  `ForwardPressure` decimal(10,2) DEFAULT NULL,
  `RetractPressure` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `injectionparameters`
--

CREATE TABLE `injectionparameters` (
  `ID` int(11) NOT NULL,
  `RecoveryPosition` decimal(10,2) DEFAULT NULL,
  `SecondStagePosition` decimal(10,2) DEFAULT NULL,
  `Cushion` decimal(10,2) DEFAULT NULL,
  `ScrewPosition` decimal(10,2) DEFAULT NULL,
  `InjectionSpeed` decimal(10,2) DEFAULT NULL,
  `InjectionPressure` decimal(10,2) DEFAULT NULL,
  `SuckBackPosition` decimal(10,2) DEFAULT NULL,
  `SuckBackSpeed` decimal(10,2) DEFAULT NULL,
  `SuckBackPressure` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `materialcomposition`
--

CREATE TABLE `materialcomposition` (
  `ID` int(11) NOT NULL,
  `DryingTime` int(11) DEFAULT NULL,
  `DryingTemperature` decimal(5,2) DEFAULT NULL,
  `Type` varchar(255) DEFAULT NULL,
  `Brand` varchar(255) DEFAULT NULL,
  `MixturePercentage` decimal(5,2) DEFAULT NULL,
  `MaterialOrder` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `moldingsettings`
--

CREATE TABLE `moldingsettings` (
  `ID` int(11) NOT NULL,
  `MoldPart` varchar(255) DEFAULT NULL,
  `Position` decimal(10,2) DEFAULT NULL,
  `Speed` decimal(10,2) DEFAULT NULL,
  `Pressure` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `moldoperationspecs`
--

CREATE TABLE `moldoperationspecs` (
  `ID` int(11) NOT NULL,
  `MoldCode` varchar(255) DEFAULT NULL,
  `ClampingForce` varchar(255) DEFAULT NULL,
  `OperationType` varchar(255) DEFAULT NULL,
  `CoolingMedia` varchar(255) DEFAULT NULL,
  `HeatingMedia` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personnel`
--

CREATE TABLE `personnel` (
  `ID` int(11) NOT NULL,
  `AdjusterName` varchar(255) DEFAULT NULL,
  `QAEName` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `plasticizingparameters`
--

CREATE TABLE `plasticizingparameters` (
  `ID` int(11) NOT NULL,
  `ScrewRPM` int(11) DEFAULT NULL,
  `ScrewSpeed` decimal(10,2) DEFAULT NULL,
  `PlastPressure` decimal(10,2) DEFAULT NULL,
  `PlastPosition` decimal(10,2) DEFAULT NULL,
  `BackPressure` decimal(10,2) DEFAULT NULL,
  `BackPressureStartPosition` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `productdetails`
--

CREATE TABLE `productdetails` (
  `ID` int(11) NOT NULL,
  `ProductName` varchar(255) DEFAULT NULL,
  `Color` varchar(255) DEFAULT NULL,
  `MoldName` varchar(255) DEFAULT NULL,
  `ProductNumber` varchar(255) DEFAULT NULL,
  `CavityActive` int(11) DEFAULT NULL,
  `GrossWeight` decimal(10,2) DEFAULT NULL,
  `NetWeight` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `productmachineinfo`
--

CREATE TABLE `productmachineinfo` (
  `ID` int(11) NOT NULL,
  `Date` date DEFAULT NULL,
  `Time` time DEFAULT NULL,
  `MachineName` varchar(255) DEFAULT NULL,
  `RunNumber` varchar(255) DEFAULT NULL,
  `Category` varchar(255) DEFAULT NULL,
  `IRN` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `temperaturesettings`
--

CREATE TABLE `temperaturesettings` (
  `ID` int(11) NOT NULL,
  `HeaterZone` int(11) DEFAULT NULL,
  `Temperature` decimal(5,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `timerparameters`
--

CREATE TABLE `timerparameters` (
  `ID` int(11) NOT NULL,
  `FillingTime` decimal(10,2) DEFAULT NULL,
  `HoldingTime` decimal(10,2) DEFAULT NULL,
  `MoldOpenCloseTime` decimal(10,2) DEFAULT NULL,
  `ChargingTime` decimal(10,2) DEFAULT NULL,
  `CoolingTime` decimal(10,2) DEFAULT NULL,
  `CycleTime` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `additionalinfo`
--
ALTER TABLE `additionalinfo`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `attachments`
--
ALTER TABLE `attachments`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `colorantdetails`
--
ALTER TABLE `colorantdetails`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `coresettings`
--
ALTER TABLE `coresettings`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `ejectionparameters`
--
ALTER TABLE `ejectionparameters`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `injectionparameters`
--
ALTER TABLE `injectionparameters`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `materialcomposition`
--
ALTER TABLE `materialcomposition`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `moldingsettings`
--
ALTER TABLE `moldingsettings`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `moldoperationspecs`
--
ALTER TABLE `moldoperationspecs`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `personnel`
--
ALTER TABLE `personnel`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `plasticizingparameters`
--
ALTER TABLE `plasticizingparameters`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `productdetails`
--
ALTER TABLE `productdetails`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `productmachineinfo`
--
ALTER TABLE `productmachineinfo`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `temperaturesettings`
--
ALTER TABLE `temperaturesettings`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `timerparameters`
--
ALTER TABLE `timerparameters`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `additionalinfo`
--
ALTER TABLE `additionalinfo`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `attachments`
--
ALTER TABLE `attachments`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `colorantdetails`
--
ALTER TABLE `colorantdetails`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `coresettings`
--
ALTER TABLE `coresettings`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ejectionparameters`
--
ALTER TABLE `ejectionparameters`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `injectionparameters`
--
ALTER TABLE `injectionparameters`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `materialcomposition`
--
ALTER TABLE `materialcomposition`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `moldingsettings`
--
ALTER TABLE `moldingsettings`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `moldoperationspecs`
--
ALTER TABLE `moldoperationspecs`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `personnel`
--
ALTER TABLE `personnel`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `plasticizingparameters`
--
ALTER TABLE `plasticizingparameters`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `productdetails`
--
ALTER TABLE `productdetails`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `productmachineinfo`
--
ALTER TABLE `productmachineinfo`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `temperaturesettings`
--
ALTER TABLE `temperaturesettings`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `timerparameters`
--
ALTER TABLE `timerparameters`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;


-- Add missing fields to existing tables and create necessary relationships.

-- 1. Add Foreign Key Relationships
ALTER TABLE `attachments`
    ADD COLUMN `ProductMachineInfoID` INT(11) DEFAULT NULL,
    ADD CONSTRAINT `fk_attachments_productmachineinfo`
        FOREIGN KEY (`ProductMachineInfoID`) REFERENCES `productmachineinfo`(`ID`) ON DELETE CASCADE;

ALTER TABLE `temperaturesettings`
    ADD COLUMN `MachineID` INT(11) DEFAULT NULL,
    ADD CONSTRAINT `fk_temperaturesettings_productmachineinfo`
        FOREIGN KEY (`MachineID`) REFERENCES `productmachineinfo`(`ID`) ON DELETE CASCADE;

ALTER TABLE `materialcomposition`
    ADD COLUMN `ProductDetailsID` INT(11) DEFAULT NULL,
    ADD CONSTRAINT `fk_materialcomposition_productdetails`
        FOREIGN KEY (`ProductDetailsID`) REFERENCES `productdetails`(`ID`) ON DELETE CASCADE;

ALTER TABLE `coresettings`
    ADD COLUMN `ProductMachineInfoID` INT(11) DEFAULT NULL,
    ADD CONSTRAINT `fk_coresettings_productmachineinfo`
        FOREIGN KEY (`ProductMachineInfoID`) REFERENCES `productmachineinfo`(`ID`) ON DELETE CASCADE;

ALTER TABLE `colorantdetails`
    ADD COLUMN `ProductDetailsID` INT(11) DEFAULT NULL,
    ADD CONSTRAINT `fk_colorantdetails_productdetails`
        FOREIGN KEY (`ProductDetailsID`) REFERENCES `productdetails`(`ID`) ON DELETE CASCADE;

-- 2. Expand Injection Parameters Table
ALTER TABLE `injectionparameters`
    ADD COLUMN `HoldingPressure1` DECIMAL(10,2) DEFAULT NULL,
    ADD COLUMN `HoldingPressure2` DECIMAL(10,2) DEFAULT NULL,
    ADD COLUMN `HoldingPressure3` DECIMAL(10,2) DEFAULT NULL,
    ADD COLUMN `HoldingTime1` DECIMAL(10,2) DEFAULT NULL,
    ADD COLUMN `HoldingTime2` DECIMAL(10,2) DEFAULT NULL,
    ADD COLUMN `HoldingTime3` DECIMAL(10,2) DEFAULT NULL;

-- 3. Add Mold Movement Table
CREATE TABLE `moldmovementparameters` (
    `ID` INT(11) NOT NULL AUTO_INCREMENT,
    `Stage` INT(11) DEFAULT NULL,
    `Position` DECIMAL(10,2) DEFAULT NULL,
    `Speed` DECIMAL(10,2) DEFAULT NULL,
    `Pressure` DECIMAL(10,2) DEFAULT NULL,
    `Type` VARCHAR(255) DEFAULT NULL COMMENT 'MoldOpen or MoldClose',
    `ProductMachineInfoID` INT(11) DEFAULT NULL,
    PRIMARY KEY (`ID`),
    CONSTRAINT `fk_moldmovement_productmachineinfo`
        FOREIGN KEY (`ProductMachineInfoID`) REFERENCES `productmachineinfo`(`ID`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- 4. Add Multi-Stage Core Pull Table
CREATE TABLE `corepullsettings` (
    `ID` INT(11) NOT NULL AUTO_INCREMENT,
    `Sequence` INT(11) DEFAULT NULL,
    `Pressure` DECIMAL(10,2) DEFAULT NULL,
    `Speed` DECIMAL(10,2) DEFAULT NULL,
    `Position` DECIMAL(10,2) DEFAULT NULL,
    `Time` DECIMAL(10,2) DEFAULT NULL,
    `LimitSwitch` VARCHAR(255) DEFAULT NULL,
    `Type` VARCHAR(255) DEFAULT NULL COMMENT 'CorePullA or CorePullB',
    `ProductMachineInfoID` INT(11) DEFAULT NULL,
    PRIMARY KEY (`ID`),
    CONSTRAINT `fk_corepull_productmachineinfo`
        FOREIGN KEY (`ProductMachineInfoID`) REFERENCES `productmachineinfo`(`ID`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- 5. Add Relationships for Timer Parameters
ALTER TABLE `timerparameters`
    ADD COLUMN `ProductMachineInfoID` INT(11) DEFAULT NULL,
    ADD CONSTRAINT `fk_timerparameters_productmachineinfo`
        FOREIGN KEY (`ProductMachineInfoID`) REFERENCES `productmachineinfo`(`ID`) ON DELETE CASCADE;

-- 6. Add Plasticizing Parameters Relationship
ALTER TABLE `plasticizingparameters`
    ADD COLUMN `ProductMachineInfoID` INT(11) DEFAULT NULL,
    ADD CONSTRAINT `fk_plasticizingparameters_productmachineinfo`
        FOREIGN KEY (`ProductMachineInfoID`) REFERENCES `productmachineinfo`(`ID`) ON DELETE CASCADE;

-- 7. Ejection Parameters Update
ALTER TABLE `ejectionparameters`
    ADD COLUMN `EjectorForwardPosition2` DECIMAL(10,2) DEFAULT NULL,
    ADD COLUMN `EjectorRetractPosition2` DECIMAL(10,2) DEFAULT NULL,
    ADD COLUMN `EjectorForwardSpeed2` DECIMAL(10,2) DEFAULT NULL,
    ADD COLUMN `EjectorRetractSpeed2` DECIMAL(10,2) DEFAULT NULL,
    ADD COLUMN `ProductMachineInfoID` INT(11) DEFAULT NULL,
    ADD CONSTRAINT `fk_ejectionparameters_productmachineinfo`
        FOREIGN KEY (`ProductMachineInfoID`) REFERENCES `productmachineinfo`(`ID`) ON DELETE CASCADE;

-- 8. Update Attachments for Additional Files
ALTER TABLE `attachments`
    ADD COLUMN `AdditionalFilePath` VARCHAR(255) DEFAULT NULL COMMENT 'For additional file uploads';
