-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 20, 2025 at 03:07 PM
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
-- Database: `quanlykhoahoc`
--

-- --------------------------------------------------------

--
-- Table structure for table `score`
--

CREATE TABLE `score` (
  `Score_ID` int(11) NOT NULL,
  `Course_ID` int(11) DEFAULT NULL,
  `User_ID` int(11) DEFAULT NULL,
  `Score1` float DEFAULT NULL,
  `Score2` float DEFAULT NULL,
  `Score3` float DEFAULT NULL,
  `Score4` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `score`
--

INSERT INTO `score` (`Score_ID`, `Course_ID`, `User_ID`, `Score1`, `Score2`, `Score3`, `Score4`) VALUES
(46, 201, 201, 10, 0, 0, 0),
(47, 201, 202, 9, 0, 0, 0),
(48, 201, 203, 0, 0, 0, 0),
(49, 201, 204, 0, 0, 0, 0),
(50, 201, 205, 0, 0, 0, 0),
(51, 201, 206, 0, 0, 0, 0),
(52, 201, 207, 0, 0, 0, 0),
(53, 201, 208, 0, 0, 0, 0),
(54, 201, 209, 0, 0, 0, 0),
(55, 201, 210, 0, 0, 0, 0),
(56, 201, 211, 0, 0, 0, 0),
(57, 201, 212, 0, 0, 0, 0),
(58, 201, 213, 0, 0, 0, 0),
(59, 201, 214, 0, 0, 0, 0),
(60, 201, 215, 0, 0, 0, 0),
(61, 202, 213, NULL, NULL, NULL, NULL),
(62, 202, 214, NULL, NULL, NULL, NULL),
(63, 202, 215, NULL, NULL, NULL, NULL),
(64, 202, 216, NULL, NULL, NULL, NULL),
(65, 202, 217, NULL, NULL, NULL, NULL),
(66, 202, 218, NULL, NULL, NULL, NULL),
(67, 202, 219, NULL, NULL, NULL, NULL),
(68, 202, 220, NULL, NULL, NULL, NULL),
(69, 202, 221, NULL, NULL, NULL, NULL),
(70, 202, 222, NULL, NULL, NULL, NULL),
(71, 202, 223, NULL, NULL, NULL, NULL),
(72, 202, 224, NULL, NULL, NULL, NULL),
(73, 202, 225, NULL, NULL, NULL, NULL),
(74, 202, 201, NULL, NULL, NULL, NULL),
(75, 202, 202, NULL, NULL, NULL, NULL),
(76, 202, 203, NULL, NULL, NULL, NULL),
(77, 202, 204, NULL, NULL, NULL, NULL),
(78, 202, 205, NULL, NULL, NULL, NULL),
(79, 202, 206, NULL, NULL, NULL, NULL),
(80, 202, 207, NULL, NULL, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `score`
--
ALTER TABLE `score`
  ADD PRIMARY KEY (`Score_ID`),
  ADD KEY `Course_ID` (`Course_ID`),
  ADD KEY `User_ID` (`User_ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `score`
--
ALTER TABLE `score`
  MODIFY `Score_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `score`
--
ALTER TABLE `score`
  ADD CONSTRAINT `score_ibfk_1` FOREIGN KEY (`Course_ID`) REFERENCES `course` (`Course_ID`),
  ADD CONSTRAINT `score_ibfk_2` FOREIGN KEY (`User_ID`) REFERENCES `user` (`Person_ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
