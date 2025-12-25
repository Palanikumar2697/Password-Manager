-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 02, 2023 at 05:24 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `expense_tracker_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_budget`
--

CREATE TABLE `tbl_budget` (
  `tbl_budget_id` int(11) NOT NULL,
  `monthly_budget` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_budget`
--

INSERT INTO `tbl_budget` (`tbl_budget_id`, `monthly_budget`) VALUES
(1, 10000);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_expense`
--

CREATE TABLE `tbl_expense` (
  `tbl_expense_id` int(11) NOT NULL,
  `tbl_expense_category_id` int(11) NOT NULL,
  `expense_name` text NOT NULL,
  `expense_date` date NOT NULL,
  `expense_spent` int(11) NOT NULL,
  `expense_description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_expense`
--

INSERT INTO `tbl_expense` (`tbl_expense_id`, `tbl_expense_category_id`, `expense_name`, `expense_date`, `expense_spent`, `expense_description`) VALUES
(1, 1, 'Lunch', '2023-09-01', 300, 'Lunch on SM City'),
(2, 3, 'Dinner', '2023-09-01', 100, 'Aw'),
(3, 6, 'Electricity', '2023-09-02', 1500, ''),
(4, 5, 'Bus', '2023-09-02', 50, '');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_expense_category`
--

CREATE TABLE `tbl_expense_category` (
  `tbl_expense_category_id` int(11) NOT NULL,
  `category_name` text NOT NULL,
  `category_budget` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_expense_category`
--

INSERT INTO `tbl_expense_category` (`tbl_expense_category_id`, `category_name`, `category_budget`) VALUES
(1, 'Allowance', 800),
(3, 'Food', 2000),
(5, 'Transportation', 400),
(6, 'Utilities', 2000);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_expense`
--
ALTER TABLE `tbl_expense`
  ADD PRIMARY KEY (`tbl_expense_id`);

--
-- Indexes for table `tbl_expense_category`
--
ALTER TABLE `tbl_expense_category`
  ADD PRIMARY KEY (`tbl_expense_category_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_expense`
--
ALTER TABLE `tbl_expense`
  MODIFY `tbl_expense_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tbl_expense_category`
--
ALTER TABLE `tbl_expense_category`
  MODIFY `tbl_expense_category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
