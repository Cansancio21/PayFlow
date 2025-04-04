-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 04, 2025 at 06:45 PM
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
-- Database: `split`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_archive`
--

CREATE TABLE `tbl_archive` (
  `b_id` int(50) NOT NULL,
  `b_code` varchar(200) NOT NULL,
  `b_BName` varchar(200) NOT NULL,
  `b_name` varchar(200) NOT NULL,
  `b_involvedP` varchar(200) NOT NULL,
  `b_date` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_archive`
--

INSERT INTO `tbl_archive` (`b_id`, `b_code`, `b_BName`, `b_name`, `b_involvedP`, `b_date`) VALUES
(56, 'BILL-25-2753', 'Nelle Sanders12', 'Helen Calderon', 'Fugit nisi dolore e', '2017-07-15');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_bills`
--

CREATE TABLE `tbl_bills` (
  `b_id` int(50) NOT NULL,
  `b_code` varchar(200) NOT NULL,
  `b_BName` varchar(200) NOT NULL,
  `b_name` varchar(200) NOT NULL,
  `b_involvedP` varchar(200) NOT NULL,
  `b_date` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_bills`
--

INSERT INTO `tbl_bills` (`b_id`, `b_code`, `b_BName`, `b_name`, `b_involvedP`, `b_date`) VALUES
(57, 'BILL-25-5980', 'Hyacinth Whitaker', 'Kylynn Mayo', 'Quia qui aut saepe d', '2024-01-09');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user`
--

CREATE TABLE `tbl_user` (
  `u_id` int(50) NOT NULL,
  `u_fname` varchar(200) NOT NULL,
  `u_lname` varchar(200) NOT NULL,
  `u_nickname` varchar(200) NOT NULL,
  `u_email` varchar(200) NOT NULL,
  `u_username` varchar(200) NOT NULL,
  `u_password` varchar(200) NOT NULL,
  `u_confirm` varchar(200) NOT NULL,
  `u_type` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_user`
--

INSERT INTO `tbl_user` (`u_id`, `u_fname`, `u_lname`, `u_nickname`, `u_email`, `u_username`, `u_password`, `u_confirm`, `u_type`) VALUES
(1, 'awawaw', 'awawaw', 'awawawaw', 'awawawaw@gmail.com', 'ryan12', '$2y$10$185ToM7w4NEqDIxJtNSU8ugKwnfcw.upQNaVIePxwxX0RpIgeqF9W', 'Ryan12345!', 'standard'),
(2, 'awawawaw', 'awawawaw', 'shhhhh', 'ryancansancio7@gmail.com', 'ryanss', '$2y$10$K4w4y1s3wHSLiYL9n/hEQuQVBEqbPKUNeLikrQSyXEz02AWZvY/22', 'Ryan12345!', 'standard'),
(3, 'ryan', 'cansancio', 'nayr', 'ryancansancio7@gmail.com', 'ryan123456', '$2y$10$mKP1NCO8N/BydI7eIw3bbuz74lQthJp59ZYUflmfgy10s6eUbAIqe', 'Ryan12345!', 'standard'),
(4, 'cansancio', 'ryan', 'nayr', 'ryancansancio7@gmail.com', 'ryan123456', '$2y$10$8SrgXV3LZvSIwT1Nci7GdOQUOYJMW9YaAq0Yt5qDSNGTUIzNBniKu', 'Ryan12345!', 'standard'),
(5, 'cansancio', 'ryan', 'nyarrr', 'ryancansancio7@gmail.com', 'oicnasnac123', '$2y$10$FOxe/g.9d1V2/ee5wBdm8uUJTE0UQN0c0E76wz/jiPnOVbnjkrGSG', 'Ryan12345!', 'standard'),
(6, 'ryan', 'Cansancio', 'hahah', 'ridytohu@mailinator.com', 'hash', '$2y$10$SaxntCe0G478bZc7vcS99uV2Ub/MmNfHnWcyl.R.OPLDm/zxErSJO', 'Ryan12345!', 'Standard'),
(7, 'awawaw', 'awaw', 'hahahahahahahah', 'ryancansancio7@gmail.com', 'haahhahahahaha', '$2y$10$QY9dTSs520JAvAIJeW7lYulVNRc526pP/o5Z6DccDIxnp4cDlzbZi', 'Ryan12345!', 'Standard');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_archive`
--
ALTER TABLE `tbl_archive`
  ADD PRIMARY KEY (`b_id`);

--
-- Indexes for table `tbl_bills`
--
ALTER TABLE `tbl_bills`
  ADD PRIMARY KEY (`b_id`);

--
-- Indexes for table `tbl_user`
--
ALTER TABLE `tbl_user`
  ADD PRIMARY KEY (`u_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_archive`
--
ALTER TABLE `tbl_archive`
  MODIFY `b_id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `tbl_bills`
--
ALTER TABLE `tbl_bills`
  MODIFY `b_id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT for table `tbl_user`
--
ALTER TABLE `tbl_user`
  MODIFY `u_id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
