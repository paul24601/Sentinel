-- Injection Molding Parameters Database - Remote Deployment Version
-- Cleaned for shared hosting import via phpMyAdmin
-- Target database: u158529957_injmold (or your actual database name)

-- Table structure for table `additionalinformation`
CREATE TABLE IF NOT EXISTS `additionalinformation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `record_id` varchar(20) DEFAULT NULL,
  `Info` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Table structure for table `attachments`
CREATE TABLE IF NOT EXISTS `attachments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `record_id` varchar(20) DEFAULT NULL,
  `FileName` varchar(255) NOT NULL,
  `FilePath` varchar(255) NOT NULL,
  `FileType` varchar(100) NOT NULL,
  `UploadedAt` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Table structure for table `barrelheatertemperatures`
CREATE TABLE IF NOT EXISTS `barrelheatertemperatures` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
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
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Continue with INSERT statements after creating this file with the full content...
