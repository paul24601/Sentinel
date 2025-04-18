-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 04, 2025 at 11:43 PM
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
(11, '2024-11-28', 'CLF 750C', 'C7CMP-00259-20', 'CL-3', 3165, 80, 114, 1867.00, 1863.00, 1860.00, 1, 1, 'Long charging time due to blackspot problem - reduce screw speed to eliminate blackspots, screw for overhaul and cleaning\r\n', 'Jessie Mallorca Castro', '1st shift'),
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
(61, '2025-01-29', 'TOS 850B', '', 'CP-1 SIDES', 3667, 90, 85, 811.00, 780.00, 773.00, 2, 2, '', 'Jessie Mallorca Castro', '1st shift');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id_number` varchar(6) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('adjuster','supervisor','admin') NOT NULL DEFAULT 'adjuster',
  `password_changed` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_number`, `full_name`, `password`, `role`, `password_changed`) VALUES
('000000', 'Aeron Paul Daliva', '$2y$10$4/7GwB8Tvsk3duZzZYKynOoTxbLTPUOjTl9PqzhY2ixlaskDJjgwu', 'admin', 1),
('000001', 'Mariela Ilustre Segura', '$2y$10$b4cUlTzWLLAm95hxA8UNpeer0W8LXF3SbaaKTfV2236ojU7h4ztmO', 'admin', 0),
('010101', 'Juan Dela Cruz', '$2y$10$cH9VNbUKYDP.919tA1UheOYHzLVarxvrNd8icHA.78xRE7uniMJtu', 'adjuster', 0),
('105368', 'Jade Cordova', '$2y$10$AAML/mg92duxTopU4M2z.eDKYpk/5yUAGTA.YmM9meaMphm0YtbQq', 'admin', 1),
('301863', 'Rolando Corpuz Guarin', '$2y$10$OTigIL/ldpAkL5IzCq7fPuNi16vSjiF7NCVm3Eb6lCGfdoSY52WMy', 'supervisor', 0),
('302997', 'Rafael Balasbas Galvez', '$2y$10$KrHxeJlDOBw1T0GMJb.lB.XWpu7an.7zWFJqkDH8mjqiLRoZ3FfJO', 'supervisor', 0),
('306968', 'Ronaldo Gamol Casaje Jr', '$2y$10$U8.Z9I7106bL3e45niQ.K.itrksWvGUDR3IMV2qQ03bd0apPsJZIm', 'admin', 1),
('307583', 'Jessie Mallorca Castro', '$2y$10$rZ2GVmrJbkczMAKFs8JNb.6xETiYta9QBtWrltdPClYHRrkLJ76SS', 'admin', 1),
('307921', 'Arvin Esparas', '$2y$10$Am3HAr8FjFQGEYcV1akLqu067bW852aUVs2Ugr0ncdAwIfChyy1/.', 'admin', 0),
('309125', 'Llander Elicor Poliquit', '$2y$10$OSAS2af3uJ1lN906q.9aWO1YDX.AgJ84LJm2LyTt0Q5hmfaRjtwv6', 'adjuster', 1),
('309246', 'Jade Eduardo Derramas', '$2y$10$8nkb7Fngbh1k893AuOYSVO2AK5UrZ/sFter2/V7yvK7pOqccJHANC', 'adjuster', 1),
('309325', 'Sherwin Ramos Sernechez', '$2y$10$/2OOSTrmVyqLr3oPBlfT9O4E6Zm00UDtRImrWKIUhzwwSYmhBTC9m', 'adjuster', 1),
('309487', 'Kaishu San Jose', '$2y$10$Z9eQle/JyAYSfDugO4UBBelLd0RqxIXOqBvW9vCWN1J7btRZbp06e', 'adjuster', 1),
('309535', 'Gilbert John Colo Delos Reyes', '$2y$10$dcTc1WnGTRiOAnRVTbWPGO706J.8D7WTr9Rt7sF9G3oQGWtUrtWa6', 'adjuster', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `submissions`
--
ALTER TABLE `submissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_number`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `submissions`
--
ALTER TABLE `submissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
