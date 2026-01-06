-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 23, 2025 at 02:52 PM
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
-- Database: `fkpark_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `parkingareas`
--

CREATE TABLE `parkingareas` (
  `area_id` int(11) NOT NULL,
  `area_name` varchar(100) NOT NULL,
  `area_type` enum('Student','Visitor') NOT NULL,
  `capacity` int(11) NOT NULL,
  `status` enum('Active','Inactive') DEFAULT 'Active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `requires_booking` tinyint(1) NOT NULL DEFAULT 0,
  `closure_reason` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `parkingareas`
--

INSERT INTO `parkingareas` (`area_id`, `area_name`, `area_type`, `capacity`, `status`, `created_at`, `requires_booking`, `closure_reason`) VALUES
(1, 'Area A', 'Student', 50, 'Active', '2025-12-23 13:06:32', 0, NULL),
(2, 'Area B', '', 30, 'Active', '2025-12-23 13:06:32', 0, NULL),
(3, 'Area C', 'Visitor', 20, 'Inactive', '2025-12-23 13:06:32', 0, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `parkingareas`
--
ALTER TABLE `parkingareas`
  ADD PRIMARY KEY (`area_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `parkingareas`
--
ALTER TABLE `parkingareas`
  MODIFY `area_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
