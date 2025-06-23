-- phpMyAdmin SQL Dump
-- version 5.2.1deb3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 18, 2025 at 01:14 AM
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
-- Database: `dailymonitoringsheet`
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
  `shift` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `submissions`
--

INSERT INTO `submissions` (`id`, `date`, `machine`, `prn`, `product_name`, `mold_code`, `cycle_time_target`, `cycle_time_actual`, `weight_standard`, `weight_gross`, `weight_net`, `cavity_designed`, `cavity_active`, `remarks`, `name`, `shift`) VALUES
(1, '2024-11-26', 'CLF 750A', 'C7AMP-00339-20', 'CL-7', 3763, 80, 100, 2020.00, 1925.00, 1916.00, 1, 1, 'Waterleak sa base at cheek (rear side, operator side at top) w/sr.', 'Gilbert John Colo Delos Reyes', '1st shift'),
(2, '2024-11-26', 'MIT 650D', 'M6DMP-00169-20', '25/55F CRATE COVER', 3729, 60, 60, 408.00, 396.00, 383.00, 1, 1, '', 'Gilbert John Colo Delos Reyes', '1st shift'),
(3, '2024-11-26', 'CLF 950A', 'C9AMP-00415-20', 'CHICK CRATE', 4224, 90, 88, 2100.00, 2055.00, 2052.00, 1, 1, 'No standard cycle time and weight due to some added ribs (1985gm before mold revisions) shortshot on 1985gm ', 'Gilbert John Colo Delos Reyes', '1st shift'),
(4, '2024-11-26', 'TOS 850C', 'T8CMP-00282-20', 'CRATE COVER #1', 2820, 60, 79, 752.00, 723.00, 720.00, 1, 1, 'Mabagal mold close kaya di na reach yung standard cycle time. ', 'Gilbert John Colo Delos Reyes', '1st shift'),
(5, '2024-11-26', 'TOS 850B', 'T8MP-00244-20', 'ARIES SIDE CHAIR', 3277, 112, 93, 2365.00, 2293.00, 2285.00, 1, 1, '', 'Gilbert John Colo Delos Reyes', '1st shift'),
(6, '2024-11-26', 'TOS 850A', 'T8AMP-00282-20', 'TABLE LEG', 3728, 120, 113, 1500.00, 1540.00, 1384.00, 2, 2, 'Excessive flashing', 'Gilbert John Colo Delos Reyes', '1st shift'),
(7, '2024-11-26', 'SUM 350', 'S3MP-00227-20', 'LCC SLIDING DOOR', 4098, 60, 72, 406.00, 412.00, 411.00, 1, 1, 'Kailangan masiksik ng husto kaya sobrang bagal ng fill time niya (16sec). 406gm required weight\r\n', 'Gilbert John Colo Delos Reyes', '1st shift'),
(9, '2024-11-26', 'CLF 750C', 'C7CMP-00258-20', 'PNS-2', 3274, 80, 102, 1603.00, 1604.00, 1602.00, 1, 1, 'Mabagal charging due to blackspot at nahihirapan sa pagkuha ng produkto yung operator dahil maliit lang yung core out niya. ', 'Gilbert John Colo Delos Reyes', '1st shift'),
(10, '2024-11-26', 'CLF 950B', 'C9BMP-00380-20', 'DRYING TRAY (SMALL)', 3276, 100, 100, 2328.00, 2249.00, 2197.00, 1, 1, 'Di na kayang pabilisan pa due to blackspot', 'Gilbert John Colo Delos Reyes', '1st shift'),
(11, '2024-11-28', 'CLF 750C', 'C7CMP-00259-20', 'CL-3', 3165, 80, 114, 1867.00, 1863.00, 1860.00, 1, 1, 'Long charging time due to blackspot problem - reduce screw speed to eliminate blackspots, screw for overhaul and cleaning', 'Jessie Mallorca Castro', '1st shift'),
(12, '2024-11-28', 'CLF 750A', 'C7AMP-00340-20', 'CL-7', 3765, 80, 100, 2020.00, 1928.00, 1926.00, 1, 1, 'waterleak problem', 'Jessie Mallorca Castro', '1st shift'),
(13, '2024-11-28', 'CLF 750B', 'CAMP 00356-20', 'PM-1', 2789, 85, 120, 1812.00, 1760.00, 1756.00, 1, 1, 'excessive flashes\r\nlong cooling due to cooling system problem', 'Jessie Mallorca Castro', '1st shift'),
(14, '2024-11-28', 'MIT 650D', 'M6DMP-00169-20', '25/55F CRATE COVER', 3729, 60, 60, 408.00, 389.00, 376.00, 1, 1, '', 'Jessie Mallorca Castro', '1st shift'),
(15, '2024-11-28', 'CLF 950B', 'C9BMP-00384-20', 'CL-1', 2212, 100, 118, 3043.00, 2830.00, 2820.00, 1, 1, 'excessive flashes, waterleak on top cheek insert(with s.r.)', 'Jessie Mallorca Castro', '1st shift'),
(16, '2024-11-28', 'TOS 650A', 'T6AMP-00363-20', 'PM-2', 3306, 93, 120, 1300.00, 1249.00, 1245.00, 1, 1, 'excessive flashes on bottom near the gates, machine low clamping', 'Jessie Mallorca Castro', '1st shift'),
(17, '2024-11-29', 'CLF 950B', 'C9BMP-00384-20', 'CL-1', 2212, 100, 117, 3043.00, 2831.00, 2820.00, 1, 1, '3 gates lang, may clogged cooling channels.', 'Gilbert John Colo Delos Reyes', '1st shift'),
(18, '2024-11-29', 'SUM 350', 'S3MP-00227-20', 'LCC SLIDING DOOR', 4098, 60, 60, 406.00, 411.00, 410.00, 1, 1, 'Recommended wt. is >406g', 'Gilbert John Colo Delos Reyes', '1st shift'),
(19, '2024-11-29', 'CLF 750C', 'C7CMP-00259-20', 'CL-3', 3165, 80, 105, 1867.00, 1865.00, 1862.00, 1, 1, 'Blackspot problem - slow charging to eliminate blackspot', 'Gilbert John Colo Delos Reyes', '1st shift'),
(20, '2024-11-29', 'CLF 750B', 'C7BMP-00356-20', 'PM-1', 2789, 85, 137, 1812.00, 1745.00, 1742.00, 1, 1, 'excessive flashes long cooling due to cooling system problem', 'Gilbert John Colo Delos Reyes', '1st shift'),
(21, '2024-11-29', 'CLF 750A', 'C7AMP-00340-20', 'CL-7', 3763, 80, 100, 2020.00, 1935.00, 1930.00, 1, 1, 'waterleak problem', 'Gilbert John Colo Delos Reyes', '1st shift'),
(22, '2024-11-29', 'TOS 850B', 'T8BMP-00244-20', 'ARIES SIDE CHAIR', 3277, 112, 110, 2365.00, 2308.00, 2319.00, 1, 1, '', 'Gilbert John Colo Delos Reyes', '1st shift'),
(23, '2024-11-29', 'TOS 850A', 'T8AMP-00281-20', '1L PEPSI #4135', 4135, 60, 103, 1906.00, 1828.00, 1821.00, 1, 1, 'Long mold closing time\r\nExcessive flashes - with injection delay due to low clamping problem', 'Gilbert John Colo Delos Reyes', '1st shift'),
(24, '2024-12-02', 'SUM 350', 'S3MP-00228-20', '30/50F COVER', 3319, 50, 50, 295.00, 315.00, 307.00, 1, 1, '', 'Jessie Mallorca Castro', '2nd shift'),
(25, '2024-12-02', 'CLF 950A', 'C9AMP-00420-20', 'B-2 CRATE', 3185, 70, 119, 2389.00, 2362.00, 2358.00, 1, 1, 'excessive flashes\r\nwater leak at top slider insert', 'Jessie Mallorca Castro', '2nd shift'),
(26, '2024-12-02', 'TOS 850C', 'T8CMP-00283-20', 'B1-C', 3921, 57, 78, 1315.00, 1311.00, 1308.00, 1, 1, 'slow ejector adv & retract (setting VE&VR = 99%)\r\nlong clamping high pressure time', 'Jessie Mallorca Castro', '2nd shift'),
(27, '2024-12-02', 'TOS 850A', 'T8AMP-00281-20', '1L PEPSI #4135', 4135, 60, 100, 1906.00, 18225.00, 1820.00, 1, 1, 'with injection/filling delay due to machine low clamping\r\nlong clamping high pressure time', 'Jessie Mallorca Castro', '2nd shift'),
(28, '2024-12-02', 'CLF 750B', 'C7BMP-00356-20', 'PM-1', 2789, 85, 100, 1812.00, 1738.00, 1733.00, 1, 1, 'excessive flashes\r\ncooling channel problem(water leak, long cooling time)', 'Jessie Mallorca Castro', '2nd shift'),
(29, '2024-12-02', 'CLF 750C', 'C7CMP-00259-20', 'CL-3', 3165, 80, 114, 1867.00, 1863.00, 1859.00, 1, 1, 'blackspot problem -  reduce screw speed to eliminate blackspot', 'Jessie Mallorca Castro', '2nd shift'),
(30, '2024-12-04', 'TOS 850A', '', '1L PEPSI #4135', 4135, 60, 58, 1906.00, 1855.00, 1848.00, 1, 0, '', 'Ronaldo Gamol Casaje Jr', '1st shift'),
(31, '2024-12-11', 'TOS 850A', '', '1L PEPSI #4135', 4135, 60, 58, 1906.00, 1845.00, 1838.00, 1, 1, '', 'Ronaldo Gamol Casaje Jr', '1st shift'),
(32, '2024-12-12', 'TOS 850A', '', '1L PEPSI #4135', 4135, 60, 58, 1906.00, 1885.00, 1878.00, 1, 1, '', 'Ronaldo Gamol Casaje Jr', '1st shift'),
(33, '2024-12-12', 'TOS 850A', '', '1L PEPSI #4135', 4135, 60, 57, 1906.00, 1885.00, 1878.00, 1, 1, '', 'Ronaldo Gamol Casaje Jr', '1st shift'),
(34, '2025-01-27', 'CLF 750B', '', '8OZ PEPSI #3652', 3652, 50, 67, 961.00, 988.00, 984.00, 1, 1, 'Mold Problem -  Clogged cooling channel\r\nShortshot Problem ', 'Jessie Mallorca Castro', '1st shift'),
(35, '2025-01-27', 'CLF 750A', '', 'CL-7', 3763, 80, 95, 2020.00, 1940.00, 1936.00, 1, 1, 'Excessive Flash', 'Jessie Mallorca Castro', '1st shift'),
(36, '2025-01-27', 'TOS 850C', '', '8OZ PEPSI #3661', 3661, 50, 79, 890.00, 940.00, 936.00, 1, 1, 'Machine Problem - Slow mold high pressure; long mold closing time', 'Jessie Mallorca Castro', '1st shift'),
(37, '2025-01-27', 'TOS 850A', '', '1L PEPSI #3575', 3575, 66, 120, 2096.00, 2096.00, 2093.00, 1, 1, 'Machine Problem - Low pressure clamping\r\nMachine Problem - Slow mold high pressure; long mold close time', 'Jessie Mallorca Castro', '1st shift'),
(38, '2025-01-27', 'CLF 750C', '', 'CM-2', 2789, 85, 138, 2244.00, 2310.00, 2307.00, 1, 1, 'Mold Problem - waterleak problem on inserts\r\nShortshot Problem', 'Jessie Mallorca Castro', '1st shift'),
(39, '2025-01-27', 'CLF 950B', '', 'B-2 CRATE', 3185, 70, 100, 2389.00, 2340.00, 2334.00, 1, 1, 'Mold Problem - Waterleak problem; long cooling time\r\nExcessive Flash', 'Jessie Mallorca Castro', '1st shift'),
(40, '2025-01-27', 'TOS 650A', '', 'CM-6', 3306, 80, 124, 1450.00, 1366.00, 1361.00, 1, 1, 'Mold Problem -  Waterleak on inserts; long cycle time', 'Jessie Mallorca Castro', '1st shift'),
(41, '2025-01-27', 'MIT 650D', '', 'CP-1 BASE', 3668, 60, 91, 847.00, 825.00, 823.00, 1, 1, 'Machine Problem - low clamping', 'Jessie Mallorca Castro', '1st shift'),
(42, '2025-01-27', 'TOS 850B', '', 'CP-1 SIDES', 3667, 90, 90, 811.00, 794.00, 788.00, 2, 2, '', 'Jessie Mallorca Castro', '1st shift'),
(43, '2025-01-28', 'CLF 750A', '', 'CL-7', 3763, 80, 95, 2020.00, 1980.00, 1975.00, 1, 1, '', 'Jessie Mallorca Castro', '1st shift'),
(44, '2025-01-28', 'CLF 950A', '', 'PL-1PN', 3654, 85, 100, 2318.00, 2230.00, 2225.00, 1, 1, '', 'Jessie Mallorca Castro', '1st shift'),
(45, '2025-01-28', 'TOS 850C', '', '8OZ PEPSI #3661', 3661, 50, 80, 890.00, 945.00, 940.00, 1, 1, 'With wt. requirement ranging from 880 to 890grms\r\nMachine Problem -  Prolonged mold closing time\r\n', 'Jessie Mallorca Castro', '1st shift'),
(46, '2025-01-28', 'TOS 850B', '', 'CP-1 SIDES', 3667, 90, 81, 811.00, 785.00, 778.00, 2, 2, '', 'Jessie Mallorca Castro', '1st shift'),
(47, '2025-01-28', 'TOS 850A', '', '1L PEPSI #3575', 3575, 66, 120, 2096.00, 2096.00, 2090.00, 1, 1, 'Machine Problem - Prolonged mold closing time - Delayed clamping high pressure; Clamp low pressure problem - with injection delay', 'Jessie Mallorca Castro', '1st shift'),
(48, '2025-01-28', 'CLF 750C', '', 'LCC WIDTH', 4100, 85, 82, 888.00, 888.00, 880.00, 2, 2, '', 'Jessie Mallorca Castro', '1st shift'),
(49, '2025-01-28', 'MIT 1050B', '', '1L PEPSI 4072', 4072, 66, 64, 1850.00, 1850.00, 1843.00, 1, 1, '', 'Jessie Mallorca Castro', '1st shift'),
(50, '2025-01-28', 'TOS 650A', '', 'CM-7', 3306, 82, 125, 1480.00, 1395.00, 1390.00, 1, 1, 'Machine Problem - Prolonged mold closing time\r\nMold problem - water leak on inserts identified. Additionally, some fittings are missing', 'Jessie Mallorca Castro', '1st shift'),
(51, '2025-01-28', 'CLF 750C', '', 'CM-2', 2789, 85, 134, 2244.00, 2244.00, 2238.00, 1, 1, 'Mold problem - water leak on inserts identified. Additionally, some fittings are missing.', 'Jessie Mallorca Castro', '1st shift'),
(52, '2025-01-28', 'CLF 950B', '', 'B-2 CRATE', 3185, 70, 93, 2389.00, 2370.00, 2364.00, 1, 1, 'Mold problem - water leak on inserts; excessive flash due to high mold wear\r\n', 'Jessie Mallorca Castro', '1st shift'),
(53, '2025-01-28', 'TOS 650A', '', 'CM-6', 3306, 80, 123, 1450.00, 1344.00, 1338.00, 1, 1, 'Mold problem - water leak on inserts; missing fittings; excessive flash due to high mold wear', 'Jessie Mallorca Castro', '1st shift'),
(54, '2025-01-28', 'CLF 750B', '', '8OZ PEPSI #3652', 3652, 50, 68, 961.00, 980.00, 975.00, 1, 1, 'Mold problem - water leak on inserts', 'Jessie Mallorca Castro', '1st shift'),
(55, '2025-01-29', 'CLF 750A', '', 'CL-7', 3763, 80, 97, 2020.00, 1930.00, 1920.00, 1, 1, 'Mold problem - excessive flash due to high mold wear', 'Jessie Mallorca Castro', '1st shift'),
(56, '2025-01-29', 'TOS 850C', '', '8OZ PEPSI #3661', 3661, 50, 79, 890.00, 940.00, 935.00, 1, 1, 'Machine problem -  prolonged mold closing time', 'Jessie Mallorca Castro', '1st shift'),
(57, '2025-01-29', 'TOS 850A', '', '1L PEPSI #3575', 3575, 66, 120, 2096.00, 2096.00, 2090.00, 1, 1, 'Machine problem - prolonged mold closing time; with injection delay due to clamp low pressure', 'Jessie Mallorca Castro', '1st shift'),
(58, '2025-01-29', 'CLF 750C', '', 'LCC LENGTH', 3079, 64, 64, 1086.00, 1086.00, 1076.00, 2, 2, '', 'Jessie Mallorca Castro', '1st shift'),
(59, '2025-01-29', 'MIT 1050B', '', '1L PEPSI 4072', 4072, 66, 63, 1850.00, 1850.00, 1840.00, 1, 1, '', 'Jessie Mallorca Castro', '1st shift'),
(60, '2025-01-29', 'TOS 650A', '', 'CM-7', 3306, 82, 124, 1480.00, 1432.00, 1425.00, 1, 1, 'Mold problem - water leak on inserts; prolonged mold closing time; missing some fittings', 'Jessie Mallorca Castro', '1st shift'),
(61, '2025-01-29', 'TOS 850B', '', 'CP-1 SIDES', 3667, 90, 85, 811.00, 780.00, 773.00, 2, 2, '', 'Jessie Mallorca Castro', '1st shift'),
(62, '2025-02-11', 'CLF 750B', '', '8OZ PEPSI #3652', 3652, 50, 58, 961.00, 1035.00, 1027.00, 1, 1, 'Short shot when reduced', 'Kaishu San Jose', '3rd shift'),
(63, '2025-02-11', 'CLF 750A', 'C7AMP-00359-20', 'CP-1 SIDES', 3667, 90, 62, 811.00, 810.00, 774.00, 2, 2, '', 'Kaishu San Jose', '3rd shift'),
(64, '2025-02-11', 'TOS 850C', 'T8CMP-00287-20', '8OZ PEPSI #3661', 3661, 50, 74, 890.00, 942.00, 938.00, 1, 1, '', 'Kaishu San Jose', '3rd shift'),
(65, '2025-02-11', 'TOS 850A', 'T8AMP-00286-20', '1L PEPSI #3575', 3575, 66, 110, 2096.00, 2108.00, 2103.00, 1, 1, 'Machine problem - prolonged mold closing time; with injection delay due to clamp low pressure', 'Kaishu San Jose', '3rd shift'),
(66, '2025-02-11', 'SUM 350', '', '30/50F COVER', 3319, 50, 60, 295.00, 328.00, 302.00, 1, 1, '', 'Kaishu San Jose', '3rd shift'),
(67, '2025-02-11', 'CLF 750C', 'C7CMP-00289-20', 'CM-5', 3096, 78, 97, 1261.00, 1267.00, 1261.00, 1, 1, 'Low pressure and speed gas trap center of product and black spot', 'Kaishu San Jose', '3rd shift'),
(68, '2025-02-11', 'MIT 1050B', 'M10MP-00273-20', '1L PEPSI 4072', 4072, 66, 67, 1850.00, 1841.00, 1838.00, 1, 1, '', 'Kaishu San Jose', '3rd shift'),
(69, '2025-02-11', 'CLF 950B', 'C9BMP-00415-20', 'CL-2', 2212, 95, 125, 3048.00, 2857.00, 2846.00, 1, 1, 'Low back screw speed black spot problem, 1 IMO ', 'Kaishu San Jose', '3rd shift'),
(70, '2025-02-11', 'TOS 650A', '', 'CM-7', 3306, 82, 127, 1480.00, 1400.00, 1394.00, 1, 1, 'Water leak on core mold, no cooling water base, cooling time was extended \r\nFlashing when increased ', 'Kaishu San Jose', '3rd shift'),
(71, '2025-02-12', 'CLF 750B', 'C7BMP-00371-20', '8OZ PEPSI #3652', 3652, 50, 50, 961.00, 1035.00, 1026.00, 1, 1, '', 'Kaishu San Jose', '3rd shift'),
(72, '2025-02-12', 'CLF 750A', 'C7AMP-00359-20', 'CP-1 SIDES', 3667, 90, 60, 811.00, 811.00, 776.00, 2, 2, '2 IMO ', 'Kaishu San Jose', '3rd shift'),
(73, '2025-02-12', 'TOS 850C', 'T8CMP-00287-20', '8OZ PEPSI #3661', 3661, 50, 52, 890.00, 928.00, 920.00, 1, 1, '', 'Kaishu San Jose', '3rd shift'),
(74, '2025-02-12', 'TOS 850A', 'T8AMP-00286-20', '1L PEPSI #3575', 3575, 66, 110, 2096.00, 2102.00, 2095.00, 1, 1, '', 'Kaishu San Jose', '3rd shift'),
(75, '2025-02-12', 'SUM 350', 'S3MP-00244-20', '30/50F COVER', 3319, 50, 60, 295.00, 328.00, 303.00, 1, 1, '', 'Kaishu San Jose', '3rd shift'),
(76, '2025-02-12', 'CLF 750C', 'C7CMP-00289-20', 'CM-5', 3096, 78, 99, 1261.00, 1340.00, 1333.00, 1, 1, '', 'Kaishu San Jose', '3rd shift'),
(77, '2025-02-12', 'MIT 1050B', 'M10MP-00273-20', '1L PEPSI 4072', 4072, 66, 67, 1850.00, 1836.00, 1834.00, 1, 1, '', 'Kaishu San Jose', '3rd shift'),
(78, '2025-02-12', 'CLF 950B', 'C9BMP-00416-20', 'CL-2', 2212, 95, 120, 3048.00, 2761.00, 2757.00, 1, 1, 'W/robot', 'Kaishu San Jose', '3rd shift'),
(79, '2025-02-12', 'TOS 650A', 'T6AMP-00386-20', '50BJ TOP (UPPER)', 3259, 68, 77, 385.00, 369.00, 369.00, 1, 1, '', 'Kaishu San Jose', '3rd shift'),
(80, '2025-02-13', 'CLF 750B', 'C7BMP-00372-20', '8OZ PEPSI #3652', 3652, 50, 50, 961.00, 1035.00, 1025.00, 1, 1, '', 'Kaishu San Jose', '3rd shift'),
(81, '2025-02-13', 'CLF 750A', 'C7AMP-00359-20', 'CP-1 SIDES', 3667, 90, 63, 811.00, 811.00, 774.00, 2, 2, '', 'Kaishu San Jose', '3rd shift'),
(82, '2025-02-13', 'CLF 950A', 'C9AMP-00446-20', 'PL-1PN', 3654, 85, 96, 2318.00, 2217.00, 2210.00, 1, 1, 'Flashing ', 'Kaishu San Jose', '3rd shift'),
(83, '2025-02-13', 'TOS 850C', 'T8CMP-00287-20', '8OZ PEPSI #3661', 3661, 50, 53, 890.00, 929.00, 926.00, 1, 1, '', 'Kaishu San Jose', '3rd shift'),
(84, '2025-02-13', 'TOS 850A', 'T8BMP-00264-20', 'PARK BENCH LEG', 228, 120, 242, 2038.00, 1960.00, 1956.00, 1, 1, 'Flashing,sink mark, the product is hot cooling time was slowed down', 'Kaishu San Jose', '3rd shift'),
(85, '2025-02-13', 'TOS 850A', 'T8AMP-00286-20', '1L PEPSI #3575', 3575, 66, 111, 2096.00, 2091.00, 2077.00, 1, 1, '', 'Kaishu San Jose', '3rd shift'),
(86, '2025-02-13', 'SUM 350', 'S3MP-00245-20', '30/50F COVER', 3319, 50, 60, 295.00, 329.00, 305.00, 1, 1, '', 'Kaishu San Jose', '3rd shift'),
(87, '2025-02-13', 'CLF 750C', 'C7CMP-00290-20', 'CP-1 COLLAPSIBLE CRATE BASE', 3668, 60, 68, 847.00, 825.00, 824.00, 1, 1, '', 'Kaishu San Jose', '3rd shift'),
(88, '2025-02-13', 'MIT 1050B', 'M10MP-00273-20', '1L PEPSI 4072', 4072, 66, 67, 1850.00, 1833.00, 1830.00, 1, 1, '', 'Kaishu San Jose', '3rd shift'),
(89, '2025-02-13', 'CLF 950B', 'C9BMP-00417-20', 'CL-2', 2212, 95, 124, 3048.00, 2774.00, 2769.00, 1, 1, 'W/robot', 'Kaishu San Jose', '3rd shift'),
(90, '2025-02-13', 'TOS 650A', 'T6AMP-00388-20', 'CRATE COVER #1', 2820, 60, 67, 752.00, 722.00, 719.00, 1, 1, 'Flashing', 'Kaishu San Jose', '3rd shift'),
(91, '2025-02-14', 'CLF 750B', 'C7BMP-00372-20', '8OZ PEPSI #3652', 3652, 50, 47, 961.00, 1039.00, 1031.00, 1, 1, '', 'Kaishu San Jose', '3rd shift'),
(92, '2025-02-14', 'CLF 950A', 'C9AMP-00446-20', 'PL-1PN', 3654, 85, 94, 2318.00, 2210.00, 2202.00, 1, 1, '', 'Kaishu San Jose', '3rd shift'),
(93, '2025-02-14', 'TOS 850C', 'T8CMP-00287-20', '8OZ PEPSI #3661', 3661, 50, 53, 890.00, 926.00, 925.00, 1, 1, '', 'Kaishu San Jose', '3rd shift'),
(94, '2025-02-14', 'SUM 350', 'S3MP-00245-20', '30/50F COVER', 3319, 50, 63, 295.00, 334.00, 308.00, 1, 1, '', 'Kaishu San Jose', '3rd shift'),
(95, '2025-02-14', 'CLF 750C', 'C7CMP-00290-20', 'CP-1 BASE', 3668, 60, 60, 847.00, 824.00, 823.00, 1, 1, '', 'Kaishu San Jose', '3rd shift'),
(96, '2025-02-14', 'MIT 1050B', 'M10MP-00273-20', '1L PEPSI 4072', 4072, 66, 67, 1850.00, 1816.00, 1814.00, 1, 1, '', 'Kaishu San Jose', '3rd shift'),
(97, '2025-02-14', 'CLF 950B', 'C9BMP-00418-20', 'CL-2', 2212, 95, 127, 3048.00, 2674.00, 2662.00, 1, 1, 'W/robot', 'Kaishu San Jose', '3rd shift'),
(98, '2025-02-14', 'TOS 650A', 'T6AMP-00388-20', 'CRATE COVER #1', 2820, 60, 68, 752.00, 729.00, 723.00, 1, 1, '', 'Kaishu San Jose', '3rd shift'),
(99, '2025-02-15', 'CLF 950B', 'C9BMP-00418-20', 'CL-2', 2212, 95, 125, 3048.00, 2665.00, 2647.00, 1, 1, 'W/robot\r\nSilicon ', 'Kaishu San Jose', '3rd shift'),
(100, '2025-02-15', 'MIT 1050B', 'M10MP-00273-20', '1L PEPSI 4072', 4072, 66, 68, 1850.00, 1834.00, 1833.00, 1, 1, '10 shot silicon ', 'Kaishu San Jose', '3rd shift'),
(101, '2025-02-15', 'CLF 750C', 'C7CMP-00291-20', 'CM-2F', 2789, 95, 124, 0.00, 1949.00, 1944.00, 1, 1, 'Cooling time extended, product hot', 'Kaishu San Jose', '3rd shift'),
(102, '2025-02-15', 'SUM 350', 'S3MP-00245-20', '30/50F COVER', 3319, 50, 63, 295.00, 333.00, 309.00, 1, 1, '', 'Kaishu San Jose', '3rd shift'),
(103, '2025-02-15', 'TOS 850A', 'T8AMP-00286-20', '1L PEPSI #3575', 3575, 66, 110, 2096.00, 2111.00, 2097.00, 1, 1, '', 'Kaishu San Jose', '3rd shift'),
(104, '2025-02-15', 'TOS 850C', 'T8CMP-00288-20', '8OZ PEPSI #3661', 3661, 50, 55, 890.00, 934.00, 930.00, 1, 1, '', 'Kaishu San Jose', '3rd shift'),
(105, '2025-02-15', 'CLF 950A', 'C9AMP-00446-20', 'PL-1PN', 3654, 85, 107, 2318.00, 2137.00, 2130.00, 1, 1, '', 'Kaishu San Jose', '3rd shift'),
(106, '2025-02-15', 'CLF 750B', 'C7BMP-00372-20', '8OZ PEPSI #3652', 3652, 50, 50, 961.00, 1048.00, 1041.00, 1, 1, '', 'Kaishu San Jose', '3rd shift'),
(107, '2025-02-19', 'CLF 750A', 'C7AMP-00-20', 'CM-4 ', 2789, 95, 124, 1940.00, 1981.00, 1936.00, 1, 1, 'Low pressure and speed bubbles and short shot', 'Kaishu San Jose', '3rd shift'),
(108, '2025-02-19', 'CLF 950A', 'C9AMP-00448-20', 'PL-1PN', 3654, 85, 86, 2318.00, 2162.00, 2155.00, 1, 1, 'W/robot\r\nFlashing', 'Kaishu San Jose', '3rd shift'),
(109, '2025-02-19', 'TOS 850C', 'T8CMP-00288-20', '8OZ PEPSI #3661', 3661, 50, 55, 890.00, 936.00, 931.00, 1, 1, '', 'Kaishu San Jose', '3rd shift'),
(110, '2025-02-19', 'CLF 750C', 'C7CMP-00-20', 'VP-1 BODY', 2401, 70, 61, 450.00, 383.00, 381.00, 1, 1, 'Flashing ', 'Kaishu San Jose', '3rd shift'),
(111, '2025-02-19', 'MIT 1050B', 'M10MP-00274-20', '1L PEPSI 4072', 4072, 66, 68, 1850.00, 1844.00, 1843.00, 1, 1, '', 'Kaishu San Jose', '3rd shift'),
(112, '2025-02-19', 'CLF 950B', 'C9BMP-00419-20', 'CL-6', 3237, 100, 133, 2288.00, 2269.00, 2258.00, 1, 1, 'W/robot\r\nFlashing', 'Kaishu San Jose', '3rd shift'),
(136, '2025-03-17', 'CLF 750B', 'C7BMP-00375-20', '80Z PEPSI 2119', 3652, 50, 48, 961.00, 955.00, 951.00, 1, 1, '', 'Sherwin Ramos Sernechez', '1st shift'),
(137, '2025-03-17', 'CLF 750B', 'C7AMP-00364-20', 'CP-1 SIDES', 3667, 90, 74, 811.00, 805.00, 769.00, 2, 2, '', 'Sherwin Ramos Sernechez', '1st shift'),
(138, '2025-03-17', 'TOS 850C', 'T8CMP-00291-20', '8OZ PEPSI #3661', 3661, 50, 63, 890.00, 936.00, 933.00, 1, 1, 'prolonged cycle time due to slow mold closing or slow clamp high pressure build-up (+20s mold closing time), hpp low pressure problem causes the slow movement of core/slider during part release\r\n', 'Sherwin Ramos Sernechez', '1st shift'),
(139, '2025-03-17', 'CLF 750C', 'C7CMP-00303-20', 'CM-3', 2789, 100, 149, 2190.00, 2183.00, 2172.00, 1, 1, 'longer cooling time due to mold water leak', 'Sherwin Ramos Sernechez', '1st shift'),
(140, '2025-03-17', 'CLF 950B', 'C9BMP-00435-20', 'CL-5', 3237, 100, 115, 3043.00, 2251.00, 2241.00, 1, 1, '+15secs cooling time due to mold waterleak resulting to excessive flashes', 'Sherwin Ramos Sernechez', '1st shift'),
(141, '2025-03-17', 'CLF 950B', 'C9BMP-00435-20', 'CL-5', 3237, 100, 115, 3043.00, 2251.00, 2241.00, 1, 1, '+15 secs cooling time due to waterleak problem resulting to excessive flashes', 'Sherwin Ramos Sernechez', '1st shift'),
(142, '2025-03-17', 'CLF 950A', 'C9AMP-00461-20', 'PL-1PN', 3654, 85, 92, 2318.00, 2215.00, 2210.00, 1, 1, 'prolonged cycle time dahil nagsa-stuck up ang stripper plate, already with s.r. and for further troubleshooting of mold and hydraulic cylinder problem and repair', 'Sherwin Ramos Sernechez', '1st shift'),
(144, '2025-03-18', 'CLF 750B', 'C7BMP-00375-20', '8OZ PEPSI 2119', 2119, 50, 48, 960.00, 955.00, 951.00, 1, 1, '', 'Sherwin Ramos Sernechez', '1st shift'),
(145, '2025-03-19', 'CLF 950A', 'C9AMP-00461-20', 'PL-1PN', 3654, 85, 95, 2318.00, 2219.00, 2217.00, 1, 1, 'prolonged mold core out due to frequent stucked stripper\r\n ', 'Sherwin Ramos Sernechez', '1st shift'),
(146, '2025-03-19', 'TOS 850B', 'T8BMP-00274-20', 'CHICKEN FOOTING 12\"', 3670, 0, 87, 0.00, 1964.00, 1891.00, 1, 1, '', 'Sherwin Ramos Sernechez', '1st shift'),
(147, '2025-03-19', 'TOS 850A', 'T8AMP-00288-20', '1L PEPSI #3575', 3575, 66, 92, 2096.00, 2099.00, 2092.00, 1, 1, '+15 secs injection delay needed to have a good clamping pressure, prolonged core in and out movement due to powerpack ', 'Sherwin Ramos Sernechez', '1st shift'),
(148, '2025-03-19', 'CLF 750C', 'C7CMP-00303-20', 'CM-3', 2789, 100, 149, 2190.00, 2183.00, 2172.00, 1, 1, 'longer cooling time due to mold waterleak resulting to excessive flashing', 'Sherwin Ramos Sernechez', '1st shift'),
(149, '2025-03-19', 'CLF 950B', 'C9BMP-00436-20', 'CL-2', 2212, 95, 127, 3048.00, 2800.00, 2795.00, 1, 1, ' slow movement of robot arm needed to properly takeout the product because of low air pressue, it has also injection sprue break to compromise the drooling apperance located. running in full automatic with robot', 'Sherwin Ramos Sernechez', '1st shift'),
(150, '2025-03-19', 'TOS 650A', 'T6AMP-00408-20', 'PM-2', 2789, 85, 110, 1812.00, 1237.00, 1233.00, 1, 1, 'longer cooling time due to mold waterleak problem, resulting to more flashes ', 'Sherwin Ramos Sernechez', '1st shift'),
(151, '2025-03-20', 'CLF 750B', 'C7BMP-00376-20', '8OZ PEPSI 2119', 2119, 50, 48, 960.00, 955.00, 951.00, 1, 1, '', 'Sherwin Ramos Sernechez', '1st shift'),
(152, '2025-03-20', 'TOS 850C', 'T8CMP-00291-20', '8OZ PEPSI #3661', 3661, 50, 60, 890.00, 936.00, 933.00, 1, 1, '- prolonged cycle time due to slow movement of core in and core out\r\n- frequent shortshot , due to back pressure malfunction of machine , need to add stroke position to compensate the shortshot resulting to overweight', 'Sherwin Ramos Sernechez', '1st shift'),
(153, '2025-03-20', 'TOS 850B', 'T8BMP-00274-20', 'CHICKEN FOOTING 12\"', 3670, 0, 87, 0.00, 1964.00, 1891.00, 1, 1, '', 'Sherwin Ramos Sernechez', '1st shift'),
(154, '2025-03-20', 'TOS 850A', 'T8AMP-00288-20', '1L PEPSI #3575', 3575, 66, 92, 2096.00, 2099.00, 2092.00, 1, 1, '- +15-20 secs. injection delay needed to have a good clamping pressure\r\n- prolonged cycle time due to slow movement of core in and out', 'Sherwin Ramos Sernechez', '1st shift'),
(155, '2025-03-20', 'CLF 750C', 'C7CMP-00303-20', 'CM-3', 2789, 100, 149, 2190.00, 2183.00, 2172.00, 1, 1, 'longer cooling time due to mold waterleak resulting to excessive flashes', 'Sherwin Ramos Sernechez', '1st shift'),
(156, '2025-03-20', 'MIT 1050B', 'M10MP-00278-20', '1L PEPSI 4072', 4072, 66, 65, 1850.00, 1855.00, 1850.00, 1, 1, '', 'Sherwin Ramos Sernechez', '1st shift'),
(157, '2025-03-20', 'TOS 650A', 'T6AMP-00409-20', 'PM-2', 2789, 85, 115, 1812.00, 1237.00, 1233.00, 1, 1, 'longer cooling time due to mold water leak, resulting to excessive flashes ', 'Sherwin Ramos Sernechez', '1st shift'),
(160, '2025-03-21', 'CLF 750B', 'C7BMP-00376-20', '8OZ PEPSI 2119', 2119, 50, 48, 960.00, 955.00, 951.00, 1, 1, '', 'Sherwin Ramos Sernechez', '1st shift'),
(161, '2025-03-21', 'CLF 750A', 'C7AMP-00365-20', 'CP-1 BASE', 3668, 60, 59, 847.00, 847.00, 845.00, 1, 1, '', 'Sherwin Ramos Sernechez', '1st shift'),
(162, '2025-03-21', 'CLF 750A', 'C7AMP-00365-20', 'CP-1 BASE', 3668, 60, 59, 847.00, 847.00, 845.00, 1, 1, '', 'Sherwin Ramos Sernechez', '1st shift'),
(163, '2025-03-21', 'CLF 750A', 'C7AMP-00365-20', 'CP-1 BASE', 3668, 60, 59, 847.00, 847.00, 845.00, 1, 1, '', 'Sherwin Ramos Sernechez', '1st shift'),
(164, '2025-03-21', 'CLF 950A', 'C9AMP-00461-20', 'PL-1PN', 3654, 85, 95, 2318.00, 2219.00, 2217.00, 1, 1, 'prolonged cycle time due to mold waterleak, excessive flashing seen in the product', 'Sherwin Ramos Sernechez', '1st shift'),
(165, '2025-03-21', 'TOS 850C', 'T8CMP-00292-20', '8OZ PEPSI #3652', 3652, 50, 60, 961.00, 936.00, 933.00, 1, 1, 'prolonged cycle time to slow movement of core in and out \r\nalso slow plastic build up during high pressure', 'Sherwin Ramos Sernechez', '1st shift'),
(166, '2025-03-21', 'TOS 850B', 'T8BMP-00274-20', 'CHICKEN FOOTING 12\"', 3670, 0, 87, 0.00, 1964.00, 1891.00, 1, 1, '', 'Sherwin Ramos Sernechez', '1st shift'),
(167, '2025-03-21', 'SUM 350', 'S3MP-00249-20', 'HYDROPHONICS NET CUP', 0, 0, 21, 0.00, 18.00, 18.00, 4, 3, '3 active cavity only , zone #4 gate are restricted so plastic cannot penetrate smoothly causing to shortshot/flashing', 'Sherwin Ramos Sernechez', '1st shift'),
(168, '2025-03-21', 'SUM 350', 'S3MP-00249-20', 'HYDROPHONICS NET CUP', 0, 0, 21, 0.00, 18.00, 18.00, 4, 3, '3 active cavity only , zone #4 gate are restricted so plastic cannot penetrate smoothly causing to shortshot/flashing', 'Sherwin Ramos Sernechez', '1st shift'),
(169, '2025-02-03', 'CLF 750C', 'C7CMP-00303-20', 'CM-3', 2789, 100, 149, 2190.00, 2183.00, 2172.00, 1, 1, 'prolonged cooling time due to mold water leak resulting to excessive flashes at the product', 'Sherwin Ramos Sernechez', '1st shift'),
(170, '2025-02-03', 'CLF 750C', 'C7CMP-00303-20', 'CM-3', 2789, 100, 149, 2190.00, 2183.00, 2172.00, 1, 1, 'prolonged cooling time due to mold water leak resulting to excessive flashes at the product', 'Sherwin Ramos Sernechez', '1st shift'),
(171, '2025-03-21', 'MIT 1050B', 'M10MP-00278-20', '1L PEPSI 4072', 4072, 66, 65, 1855.00, 1859.00, 1855.00, 1, 1, '', 'Sherwin Ramos Sernechez', '1st shift'),
(172, '2025-03-21', 'CLF 950B', 'C9BMP-00436-20', 'CL-2', 2212, 95, 124, 3048.00, 2800.00, 2795.00, 1, 1, '- +25 secs charge delay to compensate drooling seen in the product\r\n- the machine also activated the injection sprue break after charging process', 'Sherwin Ramos Sernechez', '1st shift'),
(173, '2025-03-26', 'CLF 750B', 'C7BMP-00378-20', '8OZ PEPSI 2119', 2119, 50, 50, 960.00, 955.00, 951.00, 1, 1, '', 'Sherwin Ramos Sernechez', '1st shift'),
(174, '2025-03-26', 'CLF 750A', 'C7AMP-00366-20', 'CRATE COVER #3', 0, 0, 68, 0.00, 578.00, 575.00, 1, 1, '', 'Sherwin Ramos Sernechez', '1st shift'),
(175, '2025-03-26', 'TOS 850C', 'T8CMP-00292-20', '8 OZ #3652', 3652, 60, 60, 936.00, 936.00, 933.00, 1, 1, '', 'Sherwin Ramos Sernechez', '1st shift'),
(176, '2025-03-26', 'TOS 850B', 'T8BMP-00277-20', '36 2M', 3405, 128, 128, 1934.00, 1922.00, 1922.00, 1, 1, '', 'Sherwin Ramos Sernechez', '1st shift'),
(177, '2025-03-26', 'TOS 850A', 'T8AMP-00288-20', '1L PEPSI #3575', 3575, 66, 92, 2096.00, 2099.00, 2092.00, 1, 1, 'prolonged cycle time due to injection delay to acquire good clamping pressure', 'Sherwin Ramos Sernechez', '1st shift'),
(178, '2025-03-26', 'SUM 350', 'S3MP-00251-20', 'HYDROPHONICS NET CUP', 0, 0, 21, 0.00, 18.00, 18.00, 4, 3, '- 3 gates are active \r\n- gate #4 restricted ,plastic cannot penetrate smoothly', 'Sherwin Ramos Sernechez', '1st shift'),
(179, '2025-03-26', 'CLF 750C', 'C7CMP-00304-20', 'CM-3', 2789, 100, 150, 2190.00, 2183.00, 2172.00, 1, 1, 'longer cycle time needed due to mold water leak problem resulting to excessive flashes', 'Sherwin Ramos Sernechez', '1st shift'),
(180, '2025-03-26', 'CLF 950B', 'C9BMP-00442-20', 'CL-1', 2212, 100, 138, 3043.00, 2750.00, 2744.00, 1, 1, 'longer cooling time due mold water leak problem  ', 'Sherwin Ramos Sernechez', '1st shift'),
(181, '2025-03-26', 'TOS 650A', 'T6AMP-00416-20', 'CNS-1', 0, 116, 116, 1557.00, 1558.00, 1557.00, 1, 1, '', 'Sherwin Ramos Sernechez', '1st shift'),
(186, '2025-03-28', 'CLF 750B', 'C7BMP-00378-20', '80Z PEPSI 2119', 2119, 50, 57, 995.00, 991.00, 992.00, 1, 1, 'prolonged cooling time due to some clogged cooling lines', 'Sherwin Ramos Sernechez', '1st shift'),
(187, '2025-03-28', 'CLF 750A', 'C7AMP-00367-20', 'CL-7', 3765, 80, 113, 2020.00, 1978.00, 1980.00, 1, 1, 'prolonged cycle time due to robot arm auto picker, need in slow movement to have a better grip and also low air pressure', 'Sherwin Ramos Sernechez', '1st shift'),
(188, '2025-03-28', 'CLF 950A', 'C9AMP-00463-20', 'B-2 CRATE', 3185, 70, 106, 2389.00, 2385.00, 2383.00, 1, 1, 'slow injection time needed for flashing', 'Sherwin Ramos Sernechez', '1st shift'),
(189, '2025-03-28', 'TOS 850C', 'T8CMP-00292-20', '8OZ PEPSI #3652', 3652, 50, 70, 961.00, 981.00, 960.00, 1, 1, 'slow plastic build up ', 'Sherwin Ramos Sernechez', '1st shift'),
(190, '2025-03-28', 'TOS 850B', 'T8BMP-00278-20', 'CRATE COVER #1', 2820, 60, 78, 752.00, 703.00, 701.00, 1, 1, 'flowmark problem', 'Sherwin Ramos Sernechez', '1st shift'),
(191, '2025-03-28', 'TOS 850A', 'T8AMP-00288-20', '1L PEPSI #3575', 3575, 66, 99, 2096.00, 2080.00, 2078.00, 1, 1, 'injection delay is need to have a better clamping pressure', 'Sherwin Ramos Sernechez', '1st shift'),
(192, '2025-03-28', 'SUM 350', 'S3MP-00251-20', 'HYDROPHONICS NET CUP', 0, 0, 21, 0.00, 18.00, 18.00, 4, 3, 'gate #4 is restricted ', 'Sherwin Ramos Sernechez', '1st shift'),
(193, '2025-03-28', 'TOS 650A', 'T6AMP-00418-20', 'PNS-3', 3269, 101, 101, 1340.00, 1340.00, 1340.00, 1, 1, '', 'Sherwin Ramos Sernechez', '1st shift'),
(194, '2025-03-29', 'CLF 750B', 'C7BMP-00378-20', '80Z PEPSI 2119', 2119, 50, 48, 961.00, 963.00, 961.00, 1, 1, '', 'Sherwin Ramos Sernechez', '1st shift'),
(195, '2025-03-29', 'CLF 750B', 'C7BMP-00378-20', '80Z PEPSI 2119', 2119, 50, 48, 961.00, 963.00, 961.00, 1, 1, '', 'Sherwin Ramos Sernechez', '1st shift'),
(196, '2025-03-29', 'CLF 750A', 'C7BMP-00378-20', 'CL-7', 3763, 80, 10080, 2020.00, 1962.00, 1957.00, 1, 1, '', 'Sherwin Ramos Sernechez', '1st shift'),
(197, '2025-03-29', 'CLF 950A', 'C9AMP-00464-20', 'B-2 CRATE', 3185, 70, 106, 2389.00, 2385.00, 2383.00, 1, 1, 'slow injection speed due to crack gates and flashing problem', 'Sherwin Ramos Sernechez', '1st shift'),
(198, '2025-03-29', 'TOS 850C', 'T8CMP-00292-20', '8 OZ #3652', 3652, 60, 74, 936.00, 991.00, 990.00, 1, 1, 'longer plastic build up time', 'Sherwin Ramos Sernechez', '1st shift'),
(199, '2025-03-29', 'TOS 850B', 'T8BMP-00278-20', 'CRATE COVER #1', 2820, 60, 84, 752.00, 710.00, 708.00, 1, 1, 'flow mark problem', 'Sherwin Ramos Sernechez', '1st shift'),
(200, '2025-03-29', 'TOS 850A', 'T8AMP-00288-20', '1L PEPSI #3575', 3575, 66, 100, 2096.00, 2083.00, 2083.00, 1, 1, 'injection delay is need to have a good clamping pressure ', 'Sherwin Ramos Sernechez', '1st shift'),
(201, '2025-03-29', 'CLF 750C', 'C7CMP-00303-20', 'CM-3', 2789, 100, 155, 2190.00, 2289.00, 2286.00, 1, 1, 'prolonged cycle time due to mold waterleak problem', 'Sherwin Ramos Sernechez', '1st shift'),
(202, '2025-03-31', 'CLF 950B', 'C9BMP-00445-20', 'CL-1', 2212, 100, 131, 3043.00, 2749.00, 2732.00, 1, 1, 'EXCESSIVE FLASHES, WITH JIGGING ', 'Jade Eduardo Derramas', '2nd shift'),
(203, '2025-03-31', 'CLF 750C', 'C7CMP-00303-20', 'CM-3', 2789, 95, 160, 2235.00, 2109.00, 2097.00, 1, 1, 'EXCESSIVE FLASHES, COOLING LINE PROBLEM (WATERLEAK)', 'Jade Eduardo Derramas', '2nd shift'),
(204, '2025-03-31', 'TOS 850A', 'T8AMP-00289-20', '1L PEPSI #3575', 3575, 66, 95, 2096.00, 2113.00, 2103.00, 1, 1, 'EXCESSIVE FLASHES, COOLING LINE PROBLEM (WATERLEAK & CLOGGED COOLING LINES)', 'Jade Eduardo Derramas', '2nd shift'),
(205, '2025-03-31', 'TOS 850B', 'T8BMP-00278-20', 'CRATE COVER #1', 2820, 60, 70, 752.00, 713.00, 711.00, 1, 1, 'SLOW MOLD HI-PRESS', 'Jade Eduardo Derramas', '2nd shift'),
(206, '2025-03-31', 'CLF 950A', 'C9AMP-00467-20', '80F BODY', 3853, 120, 127, 3474.00, 3358.00, 3357.00, 1, 1, '', 'Jade Eduardo Derramas', '2nd shift'),
(207, '2025-03-31', 'MIT 650D', 'M6DMP-00184-20', '80F COVER', 3854, 80, 78, 610.00, 609.00, 609.00, 1, 1, '', 'Jade Eduardo Derramas', '2nd shift'),
(208, '2025-03-31', 'CLF 750B', 'C7BMP-00378-20', '80Z PEPSI 2119', 3652, 50, 49, 961.00, 946.00, 941.00, 1, 1, 'EXCESSIVE FLASHES, COOLING LINE PROBLEM (WATERLEAK AND CLOGGED COOLING LINES)', 'Jade Eduardo Derramas', '2nd shift'),
(210, '2025-04-08', 'TOS 850C', 'T8C', 'CM-3', 2789, 100, 55, 2235.00, 2194.00, 6565.00, 1, 1, 'Testing ', 'Jessie Mallorca Castro', '1st shift'),
(211, '2025-04-08', 'TOS 850C', 'T8C', 'CM-3', 2789, 100, 55, 2235.00, 2194.00, 6565.00, 1, 1, 'Testing ', 'Jessie Mallorca Castro', '1st shift'),
(214, '2025-04-10', 'CLF 950A', '123123', 'ARIES SIDE CHAIR', 3277, 112, 112, 2365.00, 1232.00, 1232.00, 1, 1, 'this is a test', 'Aeron Paul Daliva', '2nd shift'),
(215, '2025-05-08', 'CLF 950B', '123123', 'ARIES SIDE CHAIR', 3277, 112, 112, 2365.00, 12312.00, 12312.00, 1, 1, 'this is a test', 'Aeron Paul Daliva', '1st shift'),
(216, '2025-05-22', 'ARB 50', '123123', 'ARIES SIDE CHAIR', 3277, 112, 15, 2365.00, 123.00, 123.00, 1, 1, 'testing for automation', 'Aeron Paul Daliva', '1st shift'),
(217, '2025-05-22', 'ARB 50', '123123', 'ARIES SIDE CHAIR', 3277, 112, 15, 2365.00, 123.00, 123.00, 1, 1, 'testing for automation', 'Aeron Paul Daliva', '1st shift'),
(218, '2025-05-22', 'ARB 50', '123123', '1L PEPSI #3575', 3575, 66, 12, 2096.00, 123123.00, 123123.00, 1, 123, 'test tes tes ', 'Aeron Paul Daliva', '1st shift'),
(219, '2025-05-22', 'ARB 50', '123123', 'ARIES SIDE CHAIR', 3277, 112, 16, 2365.00, 2323.00, 2323.00, 1, 0, 'tes tes testestsetes ', 'Aeron Paul Daliva', '3rd shift'),
(220, '2025-05-22', 'ARB 50', '123123', 'CRATE COVER #1', 2822, 60, 13, 752.00, 123.00, 123.00, 1, 1, 'this is a test', 'Aeron Paul Daliva', '2nd shift'),
(221, '2025-05-22', 'CLF 750A', '123123', 'B-2 CRATE', 3185, 70, 8, 2389.00, 123123.00, 123123.00, 1, 1, 'this is a test', 'Aeron Paul Daliva', '1st shift'),
(222, '2025-05-22', 'CLF 750A', '123', 'ARIES SIDE CHAIR', 3277, 112, 26, 2365.00, 123.00, 123.00, 1, 1, 'this is a test', 'Aeron Paul Daliva', '1st shift'),
(223, '2025-05-22', 'CLF 750A', '123', 'ARIES SIDE CHAIR', 3277, 112, 13, 2365.00, 234.00, 234.00, 1, 1, 'this is a test', 'Aeron Paul Daliva', '1st shift'),
(224, '2025-06-03', 'SUM 260C', '0', '30/50F COVER', 3319, 50, 51, 295.00, 123.00, 123.00, 1, 1, 'testing', 'Aeron Paul Daliva', '1st shift'),
(225, '2025-06-03', 'SUM 260C', '0', '30/50F COVER', 3319, 50, 51, 295.00, 123.00, 123.00, 1, 1, 'testing', 'Aeron Paul Daliva', '1st shift'),
(226, '2025-06-03', 'SUM 260C', 'C7CMP-00259-20', '1L PEPSI #3575', 3575, 66, 32, 2096.00, 1233.00, 1233.00, 1, 1, 'testiing', 'Aeron Paul Daliva', '1st shift'),
(227, '2025-06-03', 'CLF 750A', 'C7CMP-00259-20', '25/55F CRATE COVER', 3729, 60, 20, 408.00, 322.00, 322.00, 1, 0, 'testing', 'Aeron Paul Daliva', '2nd shift');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=228;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
