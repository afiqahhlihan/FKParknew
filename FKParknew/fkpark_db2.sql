SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(20) NOT NULL,
  `student_type` varchar(20) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `photo` varchar(255) DEFAULT 'uploads/default.jpg',
  PRIMARY KEY (`user_id`) -- Updated Primary Key
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `password`, `role`, `student_type`, `name`, `phone`, `photo`) VALUES
('CA23033', '$2y$10$HYXUoZ50qHhN8u08QGvmLezHSbqX9TJDN3bcP6k5bgwl8TSJEauDS', 'Student', 'undergraduate', 'NUR AFIQAH BINTI LIHAN', '0137849246', 'uploads/image-removebg-preview.png');

-- --------------------------------------------------------

--
-- Table structure for table `vehicles`
--

CREATE TABLE `vehicles` (
  `vehicle_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` varchar(20) NOT NULL,
  `vehicle_type` varchar(50) NOT NULL,
  `vehicle_brand` varchar(50) NOT NULL,
  `plate_number` varchar(20) NOT NULL,
  `license_class` varchar(10) NOT NULL,
  `license_due_date` date NOT NULL,
  `vehicle_grant` varchar(255) NOT NULL,
  PRIMARY KEY (`vehicle_id`),
  UNIQUE KEY `plate_number` (`plate_number`),
  KEY `user_id` (`user_id`) -- Updated Index
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Constraints for table `vehicles`
--
<<<<<<< HEAD
ALTER TABLE `vehicles`
  ADD CONSTRAINT `vehicles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;


-- --------------------------------------------------------

--
-- Table structure for table `parking_bookings`
--

CREATE TABLE `parking_bookings` (
  `booking_id` int(11) NOT NULL,
  `user_id` varchar(10) NOT NULL,
  `vehicle_plate` varchar(20) NOT NULL,
  `parking_slot` varchar(10) NOT NULL,
  `booking_date` date NOT NULL,
  `booking_time_start` time NOT NULL,
  `booking_time_end` time NOT NULL,
  `status` varchar(20) DEFAULT 'Active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for table `parking_bookings`
--
ALTER TABLE `parking_bookings`
  ADD PRIMARY KEY (`booking_id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for table `parking_bookings`
--
ALTER TABLE `parking_bookings`
  MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for table `parking_bookings`
--
ALTER TABLE `parking_bookings`
  ADD CONSTRAINT `parking_bookings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

COMMIT;
=======
>>>>>>> 32b9a13dd3259d11647a66209011d48317fed76a

ALTER TABLE `vehicles`
  ADD CONSTRAINT `fk_vehicles_users` FOREIGN KEY (`user_id`) 
  REFERENCES `users` (`user_id`) ON DELETE CASCADE; -- Updated Foreign Key Reference

COMMIT;