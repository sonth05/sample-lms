-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 02, 2025 at 06:57 PM
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
-- Table structure for table `class_chat`
--

CREATE TABLE `class_chat` (
  `id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `user_name` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `class_chat`
--

INSERT INTO `class_chat` (`id`, `course_id`, `user_name`, `message`, `created_at`) VALUES
(1, 1, 'nguyenvanan', 'hello', '2025-05-02 15:51:05'),
(2, 1, 'nguyenvanan', 'hêlo', '2025-05-02 15:51:07'),
(3, 1, 'nguyenvanan', 'helo', '2025-05-02 15:51:10'),
(4, 1, 'nguyenvanan', 'xin chào', '2025-05-02 15:53:49'),
(5, 1, 'SV010', 'hi', '2025-05-02 16:19:54'),
(6, 2, 'SV010', 'hi', '2025-05-02 16:43:13');

-- --------------------------------------------------------

--
-- Table structure for table `course`
--

CREATE TABLE `course` (
  `Course_ID` int(11) NOT NULL,
  `Course_Name` varchar(100) DEFAULT NULL,
  `Course_Description` text DEFAULT NULL,
  `Teacher_ID` int(11) DEFAULT NULL,
  `Class_Code` varchar(50) DEFAULT NULL,
  `Schedule` varchar(100) DEFAULT NULL,
  `Status` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `course`
--

INSERT INTO `course` (`Course_ID`, `Course_Name`, `Course_Description`, `Teacher_ID`, `Class_Code`, `Schedule`, `Status`) VALUES
(1, 'Hệ thống thông tin quản lý 1', 'Giới thiệu MIS 1', 100, '242_eCIT4011_02', 'Tiết 7-9, Thứ 3', 'Đang diễn ra'),
(2, 'Hệ thống thông tin quản lý 2', 'Giới thiệu MIS 2', 100, '242_eCIT4012_02', 'Tiết 4-6, Thứ 2', 'Đang diễn ra'),
(3, 'Phân tích thiết kế HTTT', 'Dựng UML và ERD', 100, '242_eCIT4013_02', 'Tiết 1-3, Thứ 4', 'Đang diễn ra'),
(4, 'Quản trị dự án phần mềm', 'PMBOK & Agile', 100, '242_eCIT4014_02', 'Tiết 10-12, Thứ 5', 'Đang diễn ra'),
(5, 'Cơ sở dữ liệu nâng cao', 'SQL, NoSQL', 101, '242_eCIT2621_02', 'Tiết 7-9, Thứ 4', 'Đang diễn ra'),
(6, 'Phân tích dữ liệu kinh doanh', 'Data Analytics', 101, '242_eCIT2622_02', 'Tiết 1-3, Thứ 3', 'Đang diễn ra'),
(7, 'Business Intelligence', 'OLAP, ETL', 101, '242_eCIT2623_02', 'Tiết 4-6, Thứ 5', 'Đang diễn ra'),
(8, 'Lập trình Web nâng cao', 'JavaScript & PHP', 102, '242_eCIT3011_02', 'Tiết 7-9, Thứ 6', 'Đang diễn ra'),
(9, 'Mạng máy tính', 'TCP/IP & Routing', 102, '242_eCIT3012_02', 'Tiết 10-12, Thứ 2', 'Đang diễn ra'),
(10, 'Phát triển ứng dụng di động', 'Android & iOS', 102, '242_eCIT3013_02', 'Tiết 7-9, Thứ 2', 'Đang diễn ra');

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
  `Score4` float DEFAULT NULL,
  `Score5` float NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `score`
--

INSERT INTO `score` (`Score_ID`, `Course_ID`, `User_ID`, `Score1`, `Score2`, `Score3`, `Score4`, `Score5`) VALUES
(81, 1, 201, 0, 0, 0, 0, 0),
(82, 1, 202, 0, 0, 0, 0, 0),
(83, 1, 203, 0, 0, 0, 0, 0),
(84, 1, 204, 0, 0, 0, 0, 0),
(85, 1, 205, 0, 0, 0, 0, 0),
(86, 1, 206, 0, 0, 0, 0, 0),
(87, 1, 207, 0, 0, 0, 0, 0),
(88, 1, 208, 0, 0, 0, 0, 0),
(89, 1, 209, 0, 0, 0, 0, 0),
(90, 1, 210, 0, 0, 0, 0, 0),
(91, 2, 201, 0, 0, 0, 0, 0),
(92, 2, 202, 0, 0, 0, 0, 0),
(93, 2, 203, 0, 0, 0, 0, 0),
(94, 2, 204, 0, 0, 0, 0, 0),
(95, 2, 205, 0, 0, 0, 0, 0),
(96, 2, 206, 0, 0, 0, 0, 0),
(97, 2, 207, 0, 0, 0, 0, 0),
(98, 2, 208, 0, 0, 0, 0, 0),
(99, 2, 209, 0, 0, 0, 0, 0),
(100, 2, 210, 0, 0, 0, 0, 0),
(101, 3, 201, 0, 0, 0, 0, 0),
(102, 3, 202, 0, 0, 0, 0, 0),
(103, 3, 203, 0, 0, 0, 0, 0),
(104, 3, 204, 0, 0, 0, 0, 0),
(105, 3, 205, 0, 0, 0, 0, 0),
(106, 3, 206, 0, 0, 0, 0, 0),
(107, 3, 207, 0, 0, 0, 0, 0),
(108, 3, 208, 0, 0, 0, 0, 0),
(109, 3, 209, 0, 0, 0, 0, 0),
(110, 3, 210, 0, 0, 0, 0, 0),
(111, 4, 201, 0, 0, 0, 0, 0),
(112, 4, 202, 0, 0, 0, 0, 0),
(113, 4, 203, 0, 0, 0, 0, 0),
(114, 4, 204, 0, 0, 0, 0, 0),
(115, 4, 205, 0, 0, 0, 0, 0),
(116, 4, 206, 0, 0, 0, 0, 0),
(117, 4, 207, 0, 0, 0, 0, 0),
(118, 4, 208, 0, 0, 0, 0, 0),
(119, 4, 209, 0, 0, 0, 0, 0),
(120, 4, 210, 0, 0, 0, 0, 0),
(121, 5, 201, 0, 0, 0, 0, 0),
(122, 5, 202, 0, 0, 0, 0, 0),
(123, 5, 203, 0, 0, 0, 0, 0),
(124, 5, 204, 0, 0, 0, 0, 0),
(125, 5, 205, 0, 0, 0, 0, 0),
(126, 5, 206, 0, 0, 0, 0, 0),
(127, 5, 207, 0, 0, 0, 0, 0),
(128, 5, 208, 0, 0, 0, 0, 0),
(129, 5, 209, 0, 0, 0, 0, 0),
(130, 5, 210, 0, 0, 0, 0, 0),
(131, 6, 201, 0, 0, 0, 0, 0),
(132, 6, 202, 0, 0, 0, 0, 0),
(133, 6, 203, 0, 0, 0, 0, 0),
(134, 6, 204, 0, 0, 0, 0, 0),
(135, 6, 205, 0, 0, 0, 0, 0),
(136, 6, 206, 0, 0, 0, 0, 0),
(137, 6, 207, 0, 0, 0, 0, 0),
(138, 6, 208, 0, 0, 0, 0, 0),
(139, 6, 209, 0, 0, 0, 0, 0),
(140, 6, 210, 0, 0, 0, 0, 0),
(141, 7, 201, 0, 0, 0, 0, 0),
(142, 7, 202, 0, 0, 0, 0, 0),
(143, 7, 203, 0, 0, 0, 0, 0),
(144, 7, 204, 0, 0, 0, 0, 0),
(145, 7, 205, 0, 0, 0, 0, 0),
(146, 7, 206, 0, 0, 0, 0, 0),
(147, 7, 207, 0, 0, 0, 0, 0),
(148, 7, 208, 0, 0, 0, 0, 0),
(149, 7, 209, 0, 0, 0, 0, 0),
(150, 7, 210, 0, 0, 0, 0, 0),
(151, 8, 201, 0, 0, 0, 0, 0),
(152, 8, 202, 0, 0, 0, 0, 0),
(153, 8, 203, 0, 0, 0, 0, 0),
(154, 8, 204, 0, 0, 0, 0, 0),
(155, 8, 205, 0, 0, 0, 0, 0),
(156, 8, 206, 0, 0, 0, 0, 0),
(157, 8, 207, 0, 0, 0, 0, 0),
(158, 8, 208, 0, 0, 0, 0, 0),
(159, 8, 209, 0, 0, 0, 0, 0),
(160, 8, 210, 0, 0, 0, 0, 0),
(161, 9, 201, 0, 0, 0, 0, 0),
(162, 9, 202, 0, 0, 0, 0, 0),
(163, 9, 203, 0, 0, 0, 0, 0),
(164, 9, 204, 0, 0, 0, 0, 0),
(165, 9, 205, 0, 0, 0, 0, 0),
(166, 9, 206, 0, 0, 0, 0, 0),
(167, 9, 207, 0, 0, 0, 0, 0),
(168, 9, 208, 0, 0, 0, 0, 0),
(169, 9, 209, 0, 0, 0, 0, 0),
(170, 9, 210, 0, 0, 0, 0, 0),
(171, 10, 201, 0, 0, 0, 0, 0),
(172, 10, 202, 0, 0, 0, 0, 0),
(173, 10, 203, 0, 0, 0, 0, 0),
(174, 10, 204, 0, 0, 0, 0, 0),
(175, 10, 205, 0, 0, 0, 0, 0),
(176, 10, 206, 0, 0, 0, 0, 0),
(177, 10, 207, 0, 0, 0, 0, 0),
(178, 10, 208, 0, 0, 0, 0, 0),
(179, 10, 209, 0, 0, 0, 0, 0),
(180, 10, 210, 0, 0, 0, 0, 0),
(181, 1, 213, 0, 0, 0, 0, 0),
(182, 2, 213, 0, 0, 0, 0, 0),
(183, 3, 213, 0, 0, 0, 0, 0),
(184, 1, 214, 0, 0, 0, 0, 0),
(185, 2, 214, 0, 0, 0, 0, 0),
(186, 3, 214, 0, 0, 0, 0, 0),
(187, 1, 215, 0, 0, 0, 0, 0),
(188, 2, 215, 0, 0, 0, 0, 0),
(189, 3, 215, 0, 0, 0, 0, 0),
(190, 1, 216, 0, 0, 0, 0, 0),
(191, 2, 216, 0, 0, 0, 0, 0),
(192, 3, 216, 0, 0, 0, 0, 0),
(193, 1, 217, 0, 0, 0, 0, 0),
(194, 2, 217, 0, 0, 0, 0, 0),
(195, 3, 217, 0, 0, 0, 0, 0),
(196, 1, 218, 0, 0, 0, 0, 0),
(197, 2, 218, 0, 0, 0, 0, 0),
(198, 3, 218, 0, 0, 0, 0, 0),
(199, 1, 219, 0, 0, 0, 0, 0),
(200, 2, 219, 0, 0, 0, 0, 0),
(201, 3, 219, 0, 0, 0, 0, 0),
(202, 1, 220, 0, 0, 0, 0, 0),
(203, 2, 220, 0, 0, 0, 0, 0),
(204, 3, 220, 0, 0, 0, 0, 0),
(205, 1, 221, 0, 0, 0, 0, 0),
(206, 2, 221, 0, 0, 0, 0, 0),
(207, 3, 221, 0, 0, 0, 0, 0),
(208, 1, 222, 0, 0, 0, 0, 0),
(209, 2, 222, 0, 0, 0, 0, 0),
(210, 3, 222, 0, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `taikhoan`
--

CREATE TABLE `taikhoan` (
  `ID` int(11) NOT NULL,
  `Username` varchar(50) DEFAULT NULL,
  `Password` varchar(255) DEFAULT NULL,
  `Failed_Attempts` int(11) DEFAULT 0,
  `Is_Locked` tinyint(1) DEFAULT 0,
  `Person_ID` int(11) DEFAULT NULL,
  `Email` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `taikhoan`
--

INSERT INTO `taikhoan` (`ID`, `Username`, `Password`, `Failed_Attempts`, `Is_Locked`, `Person_ID`, `Email`) VALUES
(64, 'nguyenvanan', '123456', 0, 0, 100, 'nguyenvanan@tmu.edu.vn'),
(65, 'trinhthangbinh', '123456', 0, 0, 101, 'trinhthangbinh@tmu.edu.vn'),
(66, 'lethicamtut', '123456', 0, 0, 102, 'lethicamtut@tmu.edu.vn'),
(67, 'SV001', '123456', 0, 0, 201, 'phamthidiemmy@student.tmu.edu.vn'),
(68, 'SV002', '123456', 0, 0, 202, 'vudung@student.tmu.edu.vn'),
(69, 'SV003', '123456', 0, 0, 203, 'dothiha@student.tmu.edu.vn'),
(70, 'SV004', '123456', 0, 0, 204, 'hovankhai@student.tmu.edu.vn'),
(71, 'SV005', '123456', 0, 0, 205, 'buitilinhchi@student.tmu.edu.vn'),
(72, 'SV006', '123456', 0, 0, 206, 'phanvanminhquan@student.tmu.edu.vn'),
(73, 'SV007', '123456', 0, 0, 207, 'dangthingtram@student.tmu.edu.vn'),
(74, 'SV008', '123456', 0, 0, 208, 'ngovanhat@student.tmu.edu.vn'),
(75, 'SV009', '123456', 0, 0, 209, 'lythiquynhanh@student.tmu.edu.vn'),
(76, 'SV010', '123456', 0, 0, 210, 'truongminhsang@student.tmu.edu.vn'),
(77, 'SV011', '123456', 0, 0, 211, 'phungthithao@student.tmu.edu.vn'),
(78, 'SV012', '123456', 0, 0, 212, 'huynhvantam@student.tmu.edu.vn'),
(79, 'SV013', '123456', 0, 0, 213, 'sv013@student.tmu.edu.vn'),
(80, 'SV014', '123456', 0, 0, 214, 'sv014@student.tmu.edu.vn'),
(81, 'SV015', '123456', 0, 0, 215, 'sv015@student.tmu.edu.vn'),
(82, 'SV016', '123456', 0, 0, 216, 'sv016@student.tmu.edu.vn'),
(83, 'SV017', '123456', 0, 0, 217, 'sv017@student.tmu.edu.vn'),
(84, 'SV018', '123456', 0, 0, 218, 'sv018@student.tmu.edu.vn'),
(85, 'SV019', '123456', 0, 0, 219, 'sv019@student.tmu.edu.vn'),
(86, 'SV020', '123456', 0, 0, 220, 'sv020@student.tmu.edu.vn'),
(87, 'SV021', '123456', 0, 0, 221, 'sv021@student.tmu.edu.vn'),
(88, 'SV022', '123456', 0, 0, 222, 'sv022@student.tmu.edu.vn');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `Person_ID` int(11) NOT NULL,
  `Person_Name` varchar(100) DEFAULT NULL,
  `Birthday` date DEFAULT NULL,
  `Gender` enum('Male','Female','Other') DEFAULT NULL,
  `Role` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`Person_ID`, `Person_Name`, `Birthday`, `Gender`, `Role`) VALUES
(100, 'Nguyễn Văn An', '1975-01-01', 'Male', 'teacher'),
(101, 'Trịnh Thăng Bình', '1980-05-12', 'Male', 'teacher'),
(102, 'Lê Thị Cẩm Tú', '1978-09-23', 'Female', 'teacher'),
(201, 'Phạm Thị Diễm My', '2001-01-15', 'Female', 'student'),
(202, 'Vũ Văn Dũng', '2001-02-20', 'Male', 'student'),
(203, 'Đỗ Thị Hà', '2001-03-05', 'Female', 'student'),
(204, 'Hồ Văn Khải', '2001-04-10', 'Male', 'student'),
(205, 'Bùi Thị Linh Chi', '2001-05-12', 'Female', 'student'),
(206, 'Phan Văn Minh Quân', '2001-06-18', 'Male', 'student'),
(207, 'Đặng Thị Ngọc Trâm', '2001-07-22', 'Female', 'student'),
(208, 'Ngô Văn Phát', '2001-08-30', 'Male', 'student'),
(209, 'Lý Thị Quỳnh Anh', '2001-09-14', 'Female', 'student'),
(210, 'Trương Minh Sang', '2001-10-03', 'Male', 'student'),
(211, 'Phùng Thị Thanh Thảo', '2001-11-25', 'Female', 'student'),
(212, 'Huỳnh Văn Tâm', '2001-12-05', 'Male', 'student'),
(213, 'Cao Minh Khang', '2002-01-10', 'Male', 'student'),
(214, 'Nguyễn Thị Thanh', '2002-02-14', 'Female', 'student'),
(215, 'Phạm Văn Long', '2002-03-05', 'Male', 'student'),
(216, 'Lê Xuân Duy', '2002-04-20', 'Male', 'student'),
(217, 'Đặng Thị Hương', '2002-05-12', 'Female', 'student'),
(218, 'Vũ Ngọc Anh', '2002-06-18', 'Female', 'student'),
(219, 'Hồ Bá Sơn', '2002-07-22', 'Male', 'student'),
(220, 'Trần Phú Thịnh', '2002-08-30', 'Male', 'student'),
(221, 'Bùi Thị Phương', '2002-09-14', 'Female', 'student'),
(222, 'Phan Tuấn Kiệt', '2002-10-03', 'Male', 'student'),
(225, 'Trần Trà My', '2003-12-25', 'Male', 'Student');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `class_chat`
--
ALTER TABLE `class_chat`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `course`
--
ALTER TABLE `course`
  ADD PRIMARY KEY (`Course_ID`),
  ADD KEY `fk_course_teacher` (`Teacher_ID`);

--
-- Indexes for table `score`
--
ALTER TABLE `score`
  ADD PRIMARY KEY (`Score_ID`),
  ADD KEY `Course_ID` (`Course_ID`),
  ADD KEY `User_ID` (`User_ID`);

--
-- Indexes for table `taikhoan`
--
ALTER TABLE `taikhoan`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `Username` (`Username`),
  ADD KEY `Person_ID` (`Person_ID`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`Person_ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `class_chat`
--
ALTER TABLE `class_chat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `course`
--
ALTER TABLE `course`
  MODIFY `Course_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=203;

--
-- AUTO_INCREMENT for table `score`
--
ALTER TABLE `score`
  MODIFY `Score_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=211;

--
-- AUTO_INCREMENT for table `taikhoan`
--
ALTER TABLE `taikhoan`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=89;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `Person_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=226;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `course`
--
ALTER TABLE `course`
  ADD CONSTRAINT `fk_course_teacher` FOREIGN KEY (`Teacher_ID`) REFERENCES `user` (`Person_ID`);

--
-- Constraints for table `score`
--
ALTER TABLE `score`
  ADD CONSTRAINT `score_ibfk_1` FOREIGN KEY (`Course_ID`) REFERENCES `course` (`Course_ID`),
  ADD CONSTRAINT `score_ibfk_2` FOREIGN KEY (`User_ID`) REFERENCES `user` (`Person_ID`);

--
-- Constraints for table `taikhoan`
--
ALTER TABLE `taikhoan`
  ADD CONSTRAINT `taikhoan_ibfk_1` FOREIGN KEY (`Person_ID`) REFERENCES `user` (`Person_ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
