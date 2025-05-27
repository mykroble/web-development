-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 25, 2024 at 11:12 AM
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
-- Database: `dentistappointment`
--

-- --------------------------------------------------------

--
-- Table structure for table `patient`
--

CREATE TABLE `patient` (
  `id` int(11) NOT NULL,
  `fname` varchar(25) NOT NULL,
  `mname` varchar(25) NOT NULL,
  `lname` varchar(25) NOT NULL,
  `gender` varchar(10) NOT NULL,
  `birthdate` date NOT NULL,
  `phone_number` bigint(11) NOT NULL,
  `street` varchar(50) NOT NULL,
  `barangay` varchar(50) NOT NULL,
  `city` varchar(50) NOT NULL,
  `province` varchar(50) NOT NULL,
  `marital_status` varchar(20) NOT NULL,
  `occupation` varchar(50) NOT NULL,
  `reg_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `patient`
--

INSERT INTO `patient` (`id`, `fname`, `mname`, `lname`, `gender`, `birthdate`, `phone_number`, `street`, `barangay`, `city`, `province`, `marital_status`, `occupation`, `reg_date`) VALUES
(10, 'Janritch', 'Duallo', 'Diputado', 'Female', '2004-01-01', 1231231312, 'B. Aranas St.', 'San Nicolas Proper', 'Cebu City', 'no clue', 'Not Married', 'Unemployed', '2024-05-22 11:32:13'),
(11, 'Shion', 'Hiroshima', 'Suzuki', 'Male', '2001-11-30', 13413412341, 'sesame', 'Okinawa', 'Tokyo', 'Toyota', 'Married', 'Unemployed', '2024-05-22 13:08:35'),
(12, 'Myk', 'Erolf', 'Roble', 'Male', '2004-02-20', 99999999999, 'sesame st.', 'I dont know', 'Cebu City', 'no clue', 'Married', 'Unemployed', '2024-05-24 10:06:31'),
(18, 'Chris', 'Ray', 'Bellarmino', 'Male', '1999-12-01', 82838931993, 'sesame', 'talamban', 'Cebu City', 'cebu', 'Married', 'Teacher', '2024-05-25 17:01:35'),
(19, 'Max', 'Jos', 'Verstappen', 'Male', '2000-01-01', 8493293182, 'Redbull st.', 'Holland', 'Cebu City', 'Netherlands', 'Married', 'F1 racer', '2024-05-25 17:08:00');

-- --------------------------------------------------------

--
-- Table structure for table `schedule`
--

CREATE TABLE `schedule` (
  `id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `visit_type` varchar(50) NOT NULL,
  `date_start` datetime NOT NULL,
  `date_end` datetime NOT NULL,
  `dentist` varchar(20) NOT NULL,
  `status` varchar(10) NOT NULL,
  `time_made` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `schedule`
--

INSERT INTO `schedule` (`id`, `patient_id`, `visit_type`, `date_start`, `date_end`, `dentist`, `status`, `time_made`) VALUES
(32, 10, 'Cleaning', '2024-05-25 10:30:00', '2024-05-25 12:00:00', 'Dr. Sainz', 'COMPLETED', '2024-05-25 16:44:07'),
(33, 11, 'Braces', '2024-05-25 13:00:00', '2024-05-25 14:00:00', 'Dr. Sainz', 'PENDING', '2024-05-25 16:45:00'),
(34, 12, 'Cleaning', '2024-05-25 15:00:00', '2024-05-25 16:00:00', 'Dr. Norris', 'PENDING', '2024-05-25 16:46:03'),
(36, 18, 'Cleaning', '2024-05-24 18:04:00', '2024-05-24 19:05:00', 'Dr. Norris', 'COMPLETED', '2024-05-25 17:05:19');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `patient`
--
ALTER TABLE `patient`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `schedule`
--
ALTER TABLE `schedule`
  ADD PRIMARY KEY (`id`),
  ADD KEY `Foreign` (`patient_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `patient`
--
ALTER TABLE `patient`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `schedule`
--
ALTER TABLE `schedule`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `schedule`
--
ALTER TABLE `schedule`
  ADD CONSTRAINT `Foreign` FOREIGN KEY (`patient_id`) REFERENCES `patient` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
