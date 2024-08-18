-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- 主机： mariadb
-- 生成日期： 2023-12-17 14:43:12
-- 服务器版本： 10.8.8-MariaDB-1:10.8.8+maria~ubu2204
-- PHP 版本： 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 数据库： `coursework2`
--

-- --------------------------------------------------------

--
-- 表的结构 `audit`
--

CREATE TABLE `audit` (
  `id` bigint(255) NOT NULL,
  `table_name` varchar(255) NOT NULL,
  `action` varchar(255) NOT NULL,
  `userid` varchar(255) NOT NULL,
  `time` timestamp(6) NOT NULL DEFAULT current_timestamp(6) ON UPDATE current_timestamp(6),
  `details` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- 转存表中的数据 `audit`
--

INSERT INTO `audit` (`id`, `table_name`, `action`, `userid`, `time`, `details`) VALUES
(18, 'ownership', 'Insert', '1', '2023-12-16 21:29:59.687139', 'storing new ownership'),
(24, 'People', 'search', '3', '2023-12-16 22:52:46.799359', 'Search by people name'),
(25, 'People', 'search', '3', '2023-12-16 23:48:54.020540', 'Search by people name'),
(26, 'People', 'search', '3', '2023-12-16 23:48:54.028294', 'Search by people name'),
(27, 'Users', 'Insert', '3', '2023-12-17 13:23:45.339186', 'added new username and password'),
(28, 'fines', 'Insert', '3', '2023-12-17 13:24:58.247215', 'Associated fines with incident');

-- --------------------------------------------------------

--
-- 表的结构 `fines`
--

CREATE TABLE `fines` (
  `Fine_ID` int(11) NOT NULL,
  `Fine_Amount` int(11) NOT NULL,
  `Fine_Points` int(11) NOT NULL,
  `Incident_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- 转存表中的数据 `fines`
--

INSERT INTO `fines` (`Fine_ID`, `Fine_Amount`, `Fine_Points`, `Incident_ID`) VALUES
(1, 2000, 6, 3),
(2, 50, 0, 2),
(3, 500, 3, 4),
(6, 1000, 1, 5);

-- --------------------------------------------------------

--
-- 表的结构 `incident`
--

CREATE TABLE `incident` (
  `Incident_ID` int(11) NOT NULL,
  `Vehicle_ID` int(11) DEFAULT NULL,
  `People_ID` int(11) DEFAULT NULL,
  `Incident_Date` date NOT NULL,
  `Incident_Report` varchar(500) NOT NULL,
  `Offence_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- 转存表中的数据 `incident`
--

INSERT INTO `incident` (`Incident_ID`, `Vehicle_ID`, `People_ID`, `Incident_Date`, `Incident_Report`, `Offence_ID`) VALUES
(1, 15, 4, '2017-12-01', '40mph in a 30 limit', 1),
(2, 20, 8, '2017-11-01', 'Double parked', 4),
(3, 13, 4, '2017-09-17', '110mph on motorway', 1),
(4, 14, 2, '2017-08-22', 'Failure to stop at a red light - travelling 30mph', 8),
(5, 13, 4, '2017-10-17', 'Not wearing a seatbelt on the M1', 3),
(6, 35, 11, '2023-09-17', '130mph on motorway', 1);

-- --------------------------------------------------------

--
-- 表的结构 `Offence`
--

CREATE TABLE `Offence` (
  `Offence_ID` int(11) NOT NULL,
  `Offence_description` varchar(50) NOT NULL,
  `Offence_maxFine` int(11) NOT NULL,
  `Offence_maxPoints` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- 转存表中的数据 `Offence`
--

INSERT INTO `Offence` (`Offence_ID`, `Offence_description`, `Offence_maxFine`, `Offence_maxPoints`) VALUES
(1, 'Speeding', 1000, 3),
(2, 'Speeding on a motorway', 2500, 6),
(3, 'Seat belt offence', 500, 0),
(4, 'Illegal parking', 500, 0),
(5, 'Drink driving', 10000, 11),
(6, 'Driving without a licence', 10000, 0),
(7, 'Traffic light offences', 1000, 3),
(8, 'Cycling on pavement', 500, 0),
(9, 'Failure to have control of vehicle', 1000, 3),
(10, 'Dangerous driving', 1000, 11),
(11, 'Careless driving', 5000, 6),
(12, 'Dangerous cycling', 2500, 0);

-- --------------------------------------------------------

--
-- 表的结构 `ownership`
--

CREATE TABLE `ownership` (
  `People_ID` int(11) NOT NULL,
  `Vehicle_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- 转存表中的数据 `ownership`
--

INSERT INTO `ownership` (`People_ID`, `Vehicle_ID`) VALUES
(3, 12),
(8, 20),
(4, 15),
(4, 13),
(1, 16),
(2, 14),
(5, 17),
(6, 18),
(7, 21),
(11, 34),
(12, 35),
(17, 39),
(17, 40);

-- --------------------------------------------------------

--
-- 表的结构 `People`
--

CREATE TABLE `People` (
  `People_ID` int(11) NOT NULL,
  `People_name` varchar(50) DEFAULT NULL,
  `People_address` varchar(50) DEFAULT NULL,
  `People_licence` varchar(16) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- 转存表中的数据 `People`
--

INSERT INTO `People` (`People_ID`, `People_name`, `People_address`, `People_licence`) VALUES
(1, 'James Smith', '23 Barnsdale Road, Leicester', 'SMITH92LDOFJJ829'),
(2, 'Jennifer Allen', '46 Bramcote Drive, Nottingham', 'ALLEN88K23KLR9B3'),
(3, 'John Myers', '323 Derby Road, Nottingham', 'MYERS99JDW8REWL3'),
(4, 'James Smith', '26 Devonshire Avenue, Nottingham', 'SMITHR004JFS20TR'),
(5, 'Terry Brown', '7 Clarke Rd, Nottingham', 'BROWND3PJJ39DLFG'),
(6, 'Mary Adams', '38 Thurman St, Nottingham', 'ADAMSH9O3JRHH107'),
(7, 'Neil Becker', '6 Fairfax Close, Nottingham', 'BECKE88UPR840F9R'),
(8, 'Angela Smith', '30 Avenue Road, Grantham', 'SMITH222LE9FJ5DS'),
(9, 'Xene Medora', '22 House Drive, West Bridgford', 'MEDORH914ANBB223'),
(11, 'yincai', 'haishang kangting', 'ABC123'),
(12, 'yin jia', 'haishang kangting', 'DEF123'),
(14, 'helin lei', 'Midland Way, Nottingham', ''),
(15, 'helin lei', 'Midland Way, Nottingham', ''),
(16, 'helin lei', 'Midland Way, Nottingham', '0424'),
(17, 'beining', 'shandong', '999');

-- --------------------------------------------------------

--
-- 表的结构 `Users`
--

CREATE TABLE `Users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- 转存表中的数据 `Users`
--

INSERT INTO `Users` (`id`, `username`, `password`) VALUES
(1, 'mcnulty', 'plod123'),
(2, 'moreland', 'fuzz42'),
(3, 'daniels', 'copper99');

-- --------------------------------------------------------

--
-- 表的结构 `Vehicle`
--

CREATE TABLE `Vehicle` (
  `Vehicle_ID` int(11) NOT NULL,
  `Vehicle_type` varchar(20) NOT NULL,
  `Vehicle_colour` varchar(20) DEFAULT NULL,
  `Vehicle_licence` varchar(7) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- 转存表中的数据 `Vehicle`
--

INSERT INTO `Vehicle` (`Vehicle_ID`, `Vehicle_type`, `Vehicle_colour`, `Vehicle_licence`) VALUES
(12, 'Ford Fiesta', 'Blue', 'LB15AJL'),
(13, 'Ferrari 458', 'Red', 'MY64PRE'),
(14, 'Vauxhall Astra', 'Silver', 'FD65WPQ'),
(15, 'Honda Civic', 'Green', 'FJ17AUG'),
(16, 'Toyota Prius', 'Silver', 'FP16KKE'),
(17, 'Ford Mondeo', 'Black', 'FP66KLM'),
(18, 'Ford Focus', 'White', 'DJ14SLE'),
(20, 'Nissan Pulsar', 'Red', 'NY64KWD'),
(21, 'Renault Scenic', 'Silver', 'BC16OEA'),
(22, 'Hyundai i30', 'Grey', 'AD223NG'),
(34, 'Tesla Model Y', 'Yellow', 'ABC123'),
(35, 'Ford', 'Black', 'IPO'),
(36, '', '', 'MNO'),
(37, 'Ford', 'yellow', ''),
(38, 'Ford', 'yellow', '933'),
(39, 'BMW', 'White', 'QWE'),
(40, 'Tesla Model Y', 'White', 'IPO');

--
-- 转储表的索引
--

--
-- 表的索引 `audit`
--
ALTER TABLE `audit`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `fines`
--
ALTER TABLE `fines`
  ADD PRIMARY KEY (`Fine_ID`),
  ADD KEY `fk_fines_incident` (`Incident_ID`);

--
-- 表的索引 `incident`
--
ALTER TABLE `incident`
  ADD PRIMARY KEY (`Incident_ID`),
  ADD KEY `fk_incident_offence` (`Offence_ID`),
  ADD KEY `fk_incident_people` (`People_ID`),
  ADD KEY `fk_incident_vehicle` (`Vehicle_ID`);

--
-- 表的索引 `Offence`
--
ALTER TABLE `Offence`
  ADD PRIMARY KEY (`Offence_ID`);

--
-- 表的索引 `ownership`
--
ALTER TABLE `ownership`
  ADD KEY `fk_ownership_people` (`People_ID`),
  ADD KEY `fk_ownership_vehicle` (`Vehicle_ID`);

--
-- 表的索引 `People`
--
ALTER TABLE `People`
  ADD PRIMARY KEY (`People_ID`);

--
-- 表的索引 `Users`
--
ALTER TABLE `Users`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `Vehicle`
--
ALTER TABLE `Vehicle`
  ADD PRIMARY KEY (`Vehicle_ID`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `audit`
--
ALTER TABLE `audit`
  MODIFY `id` bigint(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- 使用表AUTO_INCREMENT `fines`
--
ALTER TABLE `fines`
  MODIFY `Fine_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- 使用表AUTO_INCREMENT `incident`
--
ALTER TABLE `incident`
  MODIFY `Incident_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- 使用表AUTO_INCREMENT `People`
--
ALTER TABLE `People`
  MODIFY `People_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- 使用表AUTO_INCREMENT `Users`
--
ALTER TABLE `Users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- 使用表AUTO_INCREMENT `Vehicle`
--
ALTER TABLE `Vehicle`
  MODIFY `Vehicle_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- 限制导出的表
--

--
-- 限制表 `fines`
--
ALTER TABLE `fines`
  ADD CONSTRAINT `fk_fines_incident` FOREIGN KEY (`Incident_ID`) REFERENCES `incident` (`Incident_ID`);

--
-- 限制表 `incident`
--
ALTER TABLE `incident`
  ADD CONSTRAINT `fk_incident_offence` FOREIGN KEY (`Offence_ID`) REFERENCES `Offence` (`Offence_ID`),
  ADD CONSTRAINT `fk_incident_people` FOREIGN KEY (`People_ID`) REFERENCES `People` (`People_ID`),
  ADD CONSTRAINT `fk_incident_vehicle` FOREIGN KEY (`Vehicle_ID`) REFERENCES `Vehicle` (`Vehicle_ID`);

--
-- 限制表 `ownership`
--
ALTER TABLE `ownership`
  ADD CONSTRAINT `fk_ownership_people` FOREIGN KEY (`People_ID`) REFERENCES `People` (`People_ID`),
  ADD CONSTRAINT `fk_ownership_vehicle` FOREIGN KEY (`Vehicle_ID`) REFERENCES `Vehicle` (`Vehicle_ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
