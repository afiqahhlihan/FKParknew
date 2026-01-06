-- --------------------------------------------------------

--
-- Table structure for table `parking_bookings`
--

CREATE TABLE `parkingbooking` (
  `booking_id` int(11) AUTO_INCREMENT NOT NULL,
  `user_id` varchar(10) NOT NULL,
  `vehicle_plate` varchar(20) NOT NULL,
  `parking_slot` varchar(10) NOT NULL,
  `booking_date` date NOT NULL,
  `booking_time_start` time NOT NULL,
  `booking_time_end` time NOT NULL,
  `status` TINYINT NOT NULL,
  PRIMARY KEY (booking_id)
) ENGINE=InnoDB;

--
-- Indexes for table `parkingbooking`
--
ALTER TABLE `parkingbooking`
  ADD PRIMARY KEY (`booking_id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for table `parkingbooking`
--
ALTER TABLE `parkingbooking`
  MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for table `parkingbooking`
--
ALTER TABLE `parkingbooking`
  ADD CONSTRAINT `parkingbooking_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

COMMIT;