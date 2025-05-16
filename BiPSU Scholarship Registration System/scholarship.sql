-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 16, 2025 at 05:04 PM
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
-- Database: `scholarship`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `student_number` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user','','') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`name`, `email`, `student_number`, `password`, `role`) VALUES
('Admin', 'admin@gmail.com', 'Admin', '$2y$10$/THKkrjjGArp333VbgIN0.9.N8Tzgos2W4klfymjEANkqNT5HG.Ue', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `basic_info`
--

CREATE TABLE `basic_info` (
  `ID` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `student_number` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','admin') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `basic_info`
--

INSERT INTO `basic_info` (`ID`, `name`, `email`, `student_number`, `password`, `role`) VALUES
(4, 'Joluwee Clive Gargoles', 'jolu123@gmail.com', '23-1-09798', '$2y$10$iuGXaeaseWr4NWNvIPqbLeAn42/sner6d.9lKBRKzVMsodgVA8cmi', 'user'),
(5, 'Lester Sabino', 'lestersabino@gmail.com', '23-1-00789', '$2y$10$Y7XTm2vdQB/5Uxmdt2a2G.qpe.CylJFkstEPiliHdJRsgOzJkvk3O', 'user'),
(6, 'John Paul Davin', 'johnpaul@gmail.com', '23-1-09876', '$2y$10$RpaR3KPhcQ.BvnsCTwrPMOa4VnTAdZvoZxJ36aztkbWaZXA5E/iGG', 'user'),
(7, 'Benjamin Hermosa', 'tipsybenj@gmail.com', '23-1-22468', '$2y$10$DZfKe340dNHUVHGAOC52QOgZC6wERwqTXfDnqqAyDJDQWf7f4yZw2', 'user'),
(13, 'Timothy A Shortland', 'timothyshortland11@gmail.com', '23-1-02917', '$2y$10$TnKi4qV33//JbBFAE/.TVO92IgU.qz0tpcb96XHcKJ.Ke/slFD49G', 'user'),
(14, 'Janrix Estrada', 'janrix@gmail.com', '23-1-00234', '$2y$10$HdImzhYi2RKit/JaJDcy5uFoBhTQKi6fESwV5IzZZZMYeyX8LyBVq', 'user');

-- --------------------------------------------------------

--
-- Table structure for table `registration`
--

CREATE TABLE `registration` (
  `student_number` varchar(255) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `middle_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `gender` enum('male','female','other') NOT NULL,
  `age` varchar(255) NOT NULL,
  `phone_number` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `country` varchar(255) NOT NULL,
  `province` varchar(255) NOT NULL,
  `municipality` varchar(255) NOT NULL,
  `barangay` varchar(255) NOT NULL,
  `department` varchar(255) NOT NULL,
  `program` varchar(255) NOT NULL,
  `year_level` varchar(255) NOT NULL,
  `grades` varchar(255) NOT NULL,
  `prospectus` varchar(255) NOT NULL,
  `school_id` varchar(255) NOT NULL,
  `status` enum('Pending','Active','Inactive') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `registration`
--

INSERT INTO `registration` (`student_number`, `first_name`, `middle_name`, `last_name`, `gender`, `age`, `phone_number`, `email`, `country`, `province`, `municipality`, `barangay`, `department`, `program`, `year_level`, `grades`, `prospectus`, `school_id`, `status`) VALUES
('23-1-00789', 'James Lester', 'S', 'Sabino', 'male', '19', '09652587789', 'lestersabino@gmail.com', '', 'Biliran', 'Almeria', 'Barangay', 'SME', 'Bachelor of Science in Accountancy', '4th Year', 'Report of Grades.pdf', 'Certificate of Enrollment.pdf', 'Valid School ID.jpg', 'Active'),
('23-1-02917', 'Timothy', 'Asilo', 'Shortland', 'male', '20', '09351509894', 'timothyshortland11@gmail.com', 'Philippines', 'Biliran', 'Biliran', 'Busali', 'SOE', 'Bachelor of Science in Computer Engineering', '3rd Year', 'Report of Grades.pdf', 'Certificate of Enrollment.pdf', 'Valid School ID.jpg', 'Active'),
('23-1-09794', 'John Rex', 'Sanchez', 'Estrada', 'male', '19', '09782598023', 'janrix13@gmail.com', '', 'Biliran', 'Kawayan', 'Kawayanan', 'STCS', 'Bachelor of Science in Information Technology', '2nd Year', 'Report of Grades.pdf', 'Certificate of Enrollment.pdf', 'Valid School ID.jpg', 'Active'),
('23-1-09798', 'Joluwee', 'Clive', 'Gargoles', 'male', '19', '09682598023', 'jolu123@gmail.com', '', 'Biliran', 'Kawayan', 'Kawayanan', 'STEd', 'Bachelor in Technology and Livelihood Education', '2nd Year', 'Report of Grades.pdf', 'Certificate of Enrollment.pdf', 'Valid School ID.jpg', 'Active'),
('23-1-22468', 'Benjamin', 'Andrei', 'Hermosa', 'male', '20', '09062467768', 'tipsybenj@gmail.com', '', 'Biliran', 'Almeria', 'Barangay', 'SAS', 'Bachelor of Arts in English', '2nd Year', 'Report of Grades.pdf', 'Certificate of Enrollment.pdf', 'Valid School ID.jpg', 'Active');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `basic_info`
--
ALTER TABLE `basic_info`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `registration`
--
ALTER TABLE `registration`
  ADD UNIQUE KEY `ID` (`student_number`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `basic_info`
--
ALTER TABLE `basic_info`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
