-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Feb 05, 2025 at 07:19 PM
-- Server version: 8.2.0
-- PHP Version: 8.3.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `user_management`
--

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `id` int NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `user_id` int NOT NULL,
  `description` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`id`, `name`, `user_id`, `description`) VALUES
(1, 'Mathematics for Everyday Life', 3, 'Explore practical applications of math in real-world situations.'),
(2, 'Introduction to Business', 1, 'Learn the fundamentals of business, marketing, and finance.'),
(3, 'Art and Creativity', 0, 'A journey into artistic techniques and creative expression.'),
(4, 'Science and the Modern World', 3, 'Understand the impact of science on society and everyday life.'),
(5, 'History and Culture', 1, 'Discover key historical events and their influence on modern culture.'),
(6, 'Health and Wellness', 0, 'Learn about nutrition, fitness, and mental well-being.'),
(7, 'Introduction to Technology', 0, 'An overview of how technology shapes our world.'),
(8, 'Environmental Studies', 3, 'Explore sustainability, climate change, and environmental conservation.'),
(9, 'Public Speaking and Communication', 1, 'Develop confidence in speaking and effective communication skills.'),
(10, 'Creative Writing and Storytelling', 0, 'Improve writing skills and explore different storytelling techniques.');

-- --------------------------------------------------------

--
-- Table structure for table `course_enrollments`
--

CREATE TABLE `course_enrollments` (
  `course_id` int NOT NULL,
  `user_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `course_enrollments`
--

INSERT INTO `course_enrollments` (`course_id`, `user_id`) VALUES
(2, 2),
(6, 2),
(7, 2),
(1, 4),
(2, 4),
(3, 4),
(5, 4),
(7, 4),
(8, 4),
(9, 4),
(10, 4),
(1, 5),
(2, 5),
(3, 5),
(8, 5),
(9, 5),
(10, 5),
(3, 6),
(5, 6),
(7, 6),
(9, 6),
(10, 6),
(1, 7),
(3, 7),
(5, 7),
(6, 7),
(7, 7),
(9, 7),
(10, 7),
(1, 8),
(2, 8),
(4, 8),
(5, 8),
(8, 8),
(9, 8),
(10, 8),
(1, 9),
(2, 9),
(3, 9),
(7, 9),
(8, 9),
(10, 9),
(1, 10),
(2, 10),
(3, 10),
(9, 10),
(10, 10),
(2, 11),
(3, 11),
(5, 11),
(7, 11),
(9, 11),
(1, 12),
(2, 12),
(3, 12),
(5, 12),
(6, 12),
(8, 12),
(9, 12),
(10, 12),
(1, 13),
(2, 13),
(3, 13),
(4, 13),
(5, 13),
(6, 13),
(7, 13),
(8, 13),
(9, 13),
(10, 13),
(5, 14),
(6, 14),
(7, 14),
(9, 14),
(10, 14),
(1, 15),
(2, 15),
(3, 15),
(4, 15),
(5, 15),
(6, 15),
(8, 15),
(10, 15),
(1, 16),
(2, 16),
(4, 16),
(6, 16),
(7, 16),
(8, 16),
(9, 16),
(1, 17),
(4, 17),
(5, 17),
(6, 17),
(8, 17),
(10, 17),
(1, 18),
(3, 18),
(4, 18),
(5, 18),
(7, 18),
(8, 18),
(9, 18),
(3, 19),
(5, 19),
(6, 19),
(7, 19),
(8, 19),
(1, 20),
(2, 20),
(6, 20),
(9, 20),
(10, 20),
(1, 21),
(3, 21),
(5, 21),
(6, 21),
(9, 21),
(5, 22),
(6, 22),
(7, 22),
(10, 22),
(1, 23),
(2, 23),
(3, 23),
(6, 23),
(7, 23),
(8, 23),
(9, 23),
(10, 23);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `user_type` tinyint NOT NULL COMMENT '0 for staff, 1 for student',
  `api_key` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `username`, `password`, `user_type`, `api_key`) VALUES
(0, 'Arthur', 'Williams', 'arthwills17', '$2y$10$dhFTK/Kr.LruLQ5T7muEsutkX5xEu3Wx0fn2ORDwuZOSY15k4.Zj6', 0, NULL),
(1, 'Marcus', 'Davies', 'marcsdavs', '$2y$10$WWLWh7mQ6nhb2pcFiBWaGOu4xqIm09pFLJiDSOUT8PDNVcu/V3H1.', 0, NULL),
(2, 'Emily', 'Warnock', 'emilywarnock', '$2y$10$N32EaH9CS9dcC2Fv4KEnXO6.Wbv0z2oUjBo8vPEnK.NzZ.9atoDU2', 1, NULL),
(3, 'Ethan', 'Gayle', 'ethangayle23', '$2y$10$EIVpKlJBHz0wak3g5oA..e8vxmFSItDNvxam7Dl3ul3pGcrRkOdKi', 0, NULL),
(4, 'James', 'Smith', 'jamessmith101', '$2y$10$ATR30k5FDgmMOZKDeivurulR3D89eCUG0CvnEAfWeUo5uSfHmKxOu', 1, NULL),
(5, 'Emma', 'Johnson', 'emmajohnson834', '$2y$10$unbYyukY6UvqSRbTkzsrK.y4WlDANFan/BecK9eptWI/cpkoa.u1i', 1, NULL),
(6, 'Oliver', 'Brown', 'oliverbrown825', '$2y$10$auB63fctUwbvQyxpCv1rQu/F/VJyGsmm.2UAeu/0B1Ek5m35MzIwK', 1, NULL),
(7, 'Sophia', 'Davis', 'sophiadavis828', '$2y$10$5KxyerDdR5JuBQjSzT769eQpGEVgMH/0hAYvcL9v2aBPU7tJIR1ai', 1, NULL),
(8, 'Liam', 'Miller', 'liammiller630', '$2y$10$55iy9hJGtnfhXBRGelj1vOtVRuBdhX0FBT7HHTonPLRmlwY6paiYK', 1, NULL),
(9, 'Ava', 'Wilson', 'avawilson120', '$2y$10$pwl5Gcklq7pHn6m8XkYK7.iWMsiw3mKZv69k1hconrpwaWfvSj.um', 1, NULL),
(10, 'Noah', 'Moore', 'noahmoore369', '$2y$10$.drPaWzxsxUayhp4leSS1eEIk7Te/X1QWSFVJHt59zLipJ1R8MUxq', 1, NULL),
(11, 'Isabella', 'Taylor', 'isabellataylor712', '$2y$10$zMUXDFc7uitRWW.VXkX4pufzq2fFPkPPsHn8b60a4YOuImimK4xVe', 1, NULL),
(12, 'Ethan', 'Anderson', 'ethananderson992', '$2y$10$mzF.6CVvSCqBcM1Sbibsk.SF5CnqELEyiGShibI48kSLt.4vj28Li', 1, NULL),
(13, 'Mia', 'Thomas', 'miathomas430', '$2y$10$yZwVAnxwSabvtuRYm8AYsOWGbsrhYbZlvBC.on8cMxL9OloLa4y0C', 1, NULL),
(14, 'Lucas', 'Martinez', 'lucasmartinez217', '$2y$10$ezJTBWpnkaW4TPDLGqh0TuCTYOhqZDLIyWhzfRQ1hDFwhuDo5uWna', 1, NULL),
(15, 'Charlotte', 'White', 'charlottewhite652', '$2y$10$DW/taLeYzjwXA9/pVcOH5O2yknYEzhj3XdYQkEgzGmN8wv4fT.FEC', 1, NULL),
(16, 'Mason', 'Harris', 'masonharris315', '$2y$10$WL./yP9NzY2InKs2JNjJXO/gv0pBfnDM./6exw443so8LA6GfO4AS', 1, NULL),
(17, 'Amelia', 'Clark', 'ameliaclark641', '$2y$10$2O/uDGayacllIyUNQbA/GehoR9ySDqNiLPm0Y0GPRBI7FtPGKQGZy', 1, NULL),
(18, 'Elijah', 'Lewis', 'elijahlewis749', '$2y$10$R3SjByMKkODGzgt3p6fulO9YIwvyHh2YNTIH3zuIeAgI2pZebcmbO', 1, NULL),
(19, 'Harper', 'Walker', 'harperwalker869', '$2y$10$NiluSHUZAh3wTCEV3P0HpOkVgxfYU7GAFCEMuDy43EXj6nt1Pga4y', 1, NULL),
(20, 'Benjamin', 'Hall', 'benjaminhall670', '$2y$10$Gtc4ywkV.E6YhcMyliyfGuGMx10s4R4wDVSGyVfA4hWTWw0xLvWnW', 1, NULL),
(21, 'Evelyn', 'Allen', 'evelynallen375', '$2y$10$H8PuSMxNl6JZLMdSNCbfvuv38vE1ASVfhe/UtcvNQwkbnSja9.CUy', 1, NULL),
(22, 'Henry', 'Young', 'henryyoung333', '$2y$10$m1Nuu5w2KfobAWoZycJFlu8c.dqzQfe2djC4rO8Flk3bQs2sWn9ei', 1, NULL),
(23, 'Abigail', 'King', 'abigailking396', '$2y$10$WPKYNaOjpRM38EQfxSy3VuCExRmG1rV8D/C6gL/Gq7J65rlUAPuDu', 1, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `course_enrollments`
--
ALTER TABLE `course_enrollments`
  ADD PRIMARY KEY (`course_id`,`user_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `course_enrollments`
--
ALTER TABLE `course_enrollments`
  ADD CONSTRAINT `course_enrollments_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `course_enrollments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
