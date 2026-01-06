-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 26, 2025 at 03:59 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

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
-- Table structure for table `demeritpoint`
--

CREATE TABLE `demeritpoint` (
  `violationType` varchar(50) NOT NULL,
  `violationDate` date NOT NULL,
  `demeritPoint` int(11) NOT NULL,
  `description` varchar(300) NOT NULL,
  `summon_id` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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

-- --------------------------------------------------------

--
-- Table structure for table `parkingbooking`
--

CREATE TABLE `parkingbooking` (
  `booking_id` int(11) NOT NULL,
  `user_id` varchar(10) NOT NULL,
  `vehicle_plate` varchar(20) NOT NULL,
  `parking_slot` varchar(10) NOT NULL,
  `booking_date` date NOT NULL,
  `booking_time_start` time NOT NULL,
  `booking_time_end` time NOT NULL,
  `status` enum('ACTIVE','IN USE','CANCELLED','COMPLETED') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `parkingbooking`
--

INSERT INTO `parkingbooking` (`booking_id`, `user_id`, `vehicle_plate`, `parking_slot`, `booking_date`, `booking_time_start`, `booking_time_end`, `status`) VALUES
(2, 'ST6318', 'JWS 1234', '20', '2025-12-27', '09:30:00', '10:30:00', 'CANCELLED'),
(4, 'ST6318', 'JWS 1234', '20', '2025-12-27', '19:38:14', '09:40:00', ''),
(5, 'ST6318', 'JWS 1234', '20', '2025-12-27', '01:45:08', '10:40:00', ''),
(6, 'ST6318', 'JWS 1234', '4', '2025-12-27', '01:36:13', '10:00:00', ''),
(8, 'ST6318', 'JWS 1234', '15', '2025-12-26', '01:49:27', '01:49:38', 'COMPLETED'),
(9, 'ST6318', 'JWS 1234', '15', '2025-12-26', '14:00:00', '16:00:00', 'ACTIVE'),
(10, 'ST6318', 'JWS 1234', '1', '2025-12-26', '14:00:00', '16:00:00', 'ACTIVE');

-- --------------------------------------------------------

--
-- Table structure for table `parkingspace`
--

CREATE TABLE `parkingspace` (
  `space_id` int(11) NOT NULL,
  `area_id` int(11) NOT NULL,
  `space_number` varchar(20) NOT NULL,
  `status` enum('Available','Occupied','Reserved','Disabled') DEFAULT 'Available',
  `qr_token` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `parkingspace`
--

INSERT INTO `parkingspace` (`space_id`, `area_id`, `space_number`, `status`, `qr_token`) VALUES
(1, 1, 'A-01', 'Available', 'da321a08a4fab020'),
(2, 1, 'A-02', 'Available', '121340cce66bc188'),
(3, 1, 'A-03', 'Available', 'faac4ee784e54e97'),
(4, 1, 'A-04', 'Available', '13d5e1ba53f59501'),
(5, 1, 'A-05', 'Available', 'bfb1830fd7059005'),
(6, 1, 'A-06', 'Available', '252702db698bb9b2'),
(7, 1, 'A-07', 'Available', '23527013e7f76fea'),
(8, 1, 'A-08', 'Available', 'c2796193d7286c22'),
(9, 1, 'A-09', 'Available', '1da34080833a03c9'),
(10, 1, 'A-10', 'Available', '64dd1ae748ba9877'),
(11, 1, 'A-11', 'Available', 'a505f0cd5729357f'),
(12, 1, 'A-12', 'Available', 'f69c5d33277bd15c'),
(13, 1, 'A-13', 'Available', '66418e975f7c1e7f'),
(14, 1, 'A-14', 'Available', 'b21ca38ba9166ce5'),
(15, 1, 'A-15', 'Available', '3150b96c7ce69e48'),
(16, 1, 'A-16', 'Available', '0f1f22b2a2a5c860'),
(17, 1, 'A-17', 'Available', '149c92cbf53738b6'),
(18, 1, 'A-18', 'Available', 'b0f885d1c6f57403'),
(19, 1, 'A-19', 'Available', 'a1fed9f2192c9e75'),
(20, 1, 'A-20', 'Available', '22e2f1265233d74d'),
(21, 1, 'A-21', 'Available', 'b06b0b80499de623'),
(22, 1, 'A-22', 'Available', 'cc2be01705be2f21'),
(23, 1, 'A-23', 'Available', '6f41858635a8e26e'),
(24, 1, 'A-24', 'Available', '09728a6be28a29bf'),
(25, 1, 'A-25', 'Available', 'a828bb648863f059'),
(26, 1, 'A-26', 'Available', 'a7eddd6d322b397d'),
(27, 1, 'A-27', 'Available', '022e666727393c77'),
(28, 1, 'A-28', 'Available', 'c9bbd5747a99c539'),
(29, 1, 'A-29', 'Available', '0966d3d3883c5688'),
(30, 1, 'A-30', 'Available', '55404bc8aab2b37a'),
(31, 1, 'A-31', 'Available', '6fd513e41d03d2c5'),
(32, 1, 'A-32', 'Available', '8a0a85d72c1ea9da'),
(33, 1, 'A-33', 'Available', '17106813cc4bc3a7'),
(34, 1, 'A-34', 'Available', '2ad803370d26ff36'),
(35, 1, 'A-35', 'Available', '108debfba33b12c5'),
(36, 1, 'A-36', 'Available', '7205e33417726e54'),
(37, 1, 'A-37', 'Available', '9f8a4240a3d6a6d9'),
(38, 1, 'A-38', 'Available', 'e4a3efc0ea8f9d07'),
(39, 1, 'A-39', 'Available', 'a86fbf296e0b99b0'),
(40, 1, 'A-40', 'Available', '06f00cee9be050c9'),
(41, 1, 'A-41', 'Available', 'af77be392951d369'),
(42, 1, 'A-42', 'Available', '5dd4fee1b1d5d196'),
(43, 1, 'A-43', 'Available', 'daba632327e08b1e'),
(44, 1, 'A-44', 'Available', '51416234f8a6e03a'),
(45, 1, 'A-45', 'Available', 'd0acbf7c5ff90229'),
(46, 1, 'A-46', 'Available', '3aeafdacd6a2b25a'),
(47, 1, 'A-47', 'Available', '385ef39986a30d59'),
(48, 1, 'A-48', 'Available', '139068085cd6159a'),
(49, 1, 'A-49', 'Available', '934961ddaae3e4af'),
(50, 1, 'A-50', 'Available', 'df3b00ad2eec3391');

-- --------------------------------------------------------

--
-- Table structure for table `test_table`
--

CREATE TABLE `test_table` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `trafficsummon`
--

CREATE TABLE `trafficsummon` (
  `summon_id` varchar(15) NOT NULL,
  `vehicle_id` varchar(15) NOT NULL,
  `summonDate` date NOT NULL,
  `summonStatus` enum('Paid','Unpaid') NOT NULL,
  `summonViolationType` varchar(100) NOT NULL,
  `summonDemeritPoint` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `trafficsummon`
--

INSERT INTO `trafficsummon` (`summon_id`, `vehicle_id`, `summonDate`, `summonStatus`, `summonViolationType`, `summonDemeritPoint`) VALUES
('', '3', '2025-12-24', 'Unpaid', 'Accident Caused', 20),
('', '3', '2025-12-24', 'Unpaid', 'Accident Caused', 20),
('', '3', '2025-12-24', 'Unpaid', 'Accident Caused', 20),
('', '3', '2025-12-24', 'Unpaid', 'Accident Caused', 20);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` varchar(10) NOT NULL,
  `matric_id` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `photo` varchar(255) DEFAULT 'uploads/default.jpg',
  `total_demerit_point` int(11) DEFAULT 0,
  `enforcement_status` varchar(255) DEFAULT 'Warning given'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `matric_id`, `password`, `role`, `name`, `phone`, `photo`, `total_demerit_point`, `enforcement_status`) VALUES
('ST1894', 'ca2301', '$2y$10$caY071j/dO2R1/sQpIsM4uWY2i1ejnYxCkEhubDx5LstYY8XVtn/2', 'Administrator', 'arash zayden', '0123456789', 'uploads/default.jpg', 80, 'Revoke of in campus vehicle permission for the entire study duration'),
('ST2893', '23038', '$2y$10$z325mB3UPHBi8JA/hqgsmeqZm04pbMLwGXmAb3DZi7s1RHG3mnoRi', 'SMU Staff', 'NRIN', '111', 'uploads/default.jpg', 80, 'Revoke of in campus vehicle permission for the entire study duration'),
('ST6318', 'CA23033', '$2y$10$HYXUoZ50qHhN8u08QGvmLezHSbqX9TJDN3bcP6k5bgwl8TSJEauDS', 'Student', 'nrafiqah', '0137849246', 'uploads/image-removebg-preview.png', 80, 'Revoke of in campus vehicle permission for the entire study duration'),
('ST6599', 'CA230388', '$2y$10$Z5vrCNFaVVCedQMxLVdSdeqz91tHUX00p1fsMOeRnZd01ogUNvbBy', 'Student', 'didi', '011', 'uploads/default.jpg', 80, 'Revoke of in campus vehicle permission for the entire study duration'),
('ST7361', 'CA23038', '$2y$10$mwlOlXM9Qrkv7wUVwuJgouS1NELcOS9KtbYA1BOk4hR2gv9uGosou', 'Student', 'nurin', '123', 'uploads/default.jpg', 80, 'Revoke of in campus vehicle permission for the entire study duration'),
('ST8033', 'CA23023', '$2y$10$qrJgEPib/vhH23l/ADfEzuetPOHmICZ6/rxQYLN7fanzX4yxHXgvG', 'Administrator', 'amyra', '0104033837', 'uploads/default.jpg', 80, 'Revoke of in campus vehicle permission for the entire study duration');

-- --------------------------------------------------------

--
-- Table structure for table `vehicles`
--

CREATE TABLE `vehicles` (
  `vehicle_id` int(11) NOT NULL,
  `user_id` varchar(10) NOT NULL,
  `vehicle_type` varchar(50) NOT NULL,
  `vehicle_brand` varchar(50) NOT NULL,
  `plate_number` varchar(20) NOT NULL,
  `license_class` varchar(10) NOT NULL,
  `license_due_date` date NOT NULL,
  `vehicle_grant` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vehicles`
--

INSERT INTO `vehicles` (`vehicle_id`, `user_id`, `vehicle_type`, `vehicle_brand`, `plate_number`, `license_class`, `license_due_date`, `vehicle_grant`) VALUES
(1, 'ST6318', 'Car', 'Mazda', 'JKL2323', 'D', '2026-02-16', 'uploads/1766395289_Brainstorm Storyboard Whiteboard (2).png'),
(2, 'ST7361', 'Car', 'Tesla', '12445', 'D', '2028-11-23', 'uploads/1766472375_Database3.accdb'),
(3, 'ST6599', 'SUV/MPV', 'TESLA', 'JWA 2222', 'D', '2028-12-24', 'uploads/1766543499_afiqah.docx');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `parkingareas`
--
ALTER TABLE `parkingareas`
  ADD PRIMARY KEY (`area_id`);

--
-- Indexes for table `parkingbooking`
--
ALTER TABLE `parkingbooking`
  ADD PRIMARY KEY (`booking_id`);

--
-- Indexes for table `parkingspace`
--
ALTER TABLE `parkingspace`
  ADD PRIMARY KEY (`space_id`),
  ADD UNIQUE KEY `qr_token` (`qr_token`),
  ADD KEY `area_id` (`area_id`);

--
-- Indexes for table `test_table`
--
ALTER TABLE `test_table`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `matric_id` (`matric_id`);

--
-- Indexes for table `vehicles`
--
ALTER TABLE `vehicles`
  ADD PRIMARY KEY (`vehicle_id`),
  ADD UNIQUE KEY `plate_number` (`plate_number`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `parkingareas`
--
ALTER TABLE `parkingareas`
  MODIFY `area_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `parkingbooking`
--
ALTER TABLE `parkingbooking`
  MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `parkingspace`
--
ALTER TABLE `parkingspace`
  MODIFY `space_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `test_table`
--
ALTER TABLE `test_table`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vehicles`
--
ALTER TABLE `vehicles`
  MODIFY `vehicle_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `parkingspace`
--
ALTER TABLE `parkingspace`
  ADD CONSTRAINT `parkingspace_ibfk_1` FOREIGN KEY (`area_id`) REFERENCES `parkingareas` (`area_id`) ON DELETE CASCADE;

--
-- Constraints for table `vehicles`
--
ALTER TABLE `vehicles`
  ADD CONSTRAINT `vehicles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
