-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jul 01, 2021 at 05:41 PM
-- Server version: 10.4.17-MariaDB
-- PHP Version: 8.0.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `InfyBank`
--

-- --------------------------------------------------------

--
-- Table structure for table `Admin_Details`
--

CREATE TABLE `Admin_Details` (
  `Email_Id` varchar(25) NOT NULL,
  `Phone_Number` bigint(10) NOT NULL,
  `Admin_Name` varchar(30) NOT NULL,
  `Password` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `Admin_Details`
--

INSERT INTO `Admin_Details` (`Email_Id`, `Phone_Number`, `Admin_Name`, `Password`) VALUES
('Betty@infybank.com', 8765943210, 'Betty Jones', 'BettyJ@123'),
('Joy@infybank.com', 9876543210, 'Joy', 'Joy@123');

-- --------------------------------------------------------

--
-- Table structure for table `Bank_Offers`
--

CREATE TABLE `Bank_Offers` (
  `Offer_Id` int(11) NOT NULL,
  `Offer_Name` varchar(25) DEFAULT NULL,
  `Offer_Details` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `Bank_Offers`
--

INSERT INTO `Bank_Offers` (`Offer_Id`, `Offer_Name`, `Offer_Details`) VALUES
(101, 'Home Loan', 'Apply home loan with 0.5% interest'),
(102, 'Credit card', 'Apply for platinum and earn 1000 credit points');

-- --------------------------------------------------------

--
-- Table structure for table `Branch_Details`
--

CREATE TABLE `Branch_Details` (
  `IFSC_Code` varchar(20) NOT NULL,
  `Branch_Name` varchar(20) NOT NULL,
  `Manager_Name` varchar(20) DEFAULT NULL,
  `Custome_Count` int(11) DEFAULT NULL,
  `Staff_Count` int(11) DEFAULT NULL,
  `Branch_Rank` int(11) DEFAULT NULL,
  `Branch_Address` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `Branch_Details`
--

INSERT INTO `Branch_Details` (`IFSC_Code`, `Branch_Name`, `Manager_Name`, `Custome_Count`, `Staff_Count`, `Branch_Rank`, `Branch_Address`) VALUES
('IB0001234', 'Mysore', 'Peter', 1500, 200, 1, 'InfyBank, Hootagali, Electronics city, Mysore-570027'),
('IB0001235', 'Bangalore', 'Paul', 1500, 200, 1, 'InfyBank, Bangalore Electronics city, Bangalore-570039');

-- --------------------------------------------------------

--
-- Table structure for table `Credit_Card_Details`
--

CREATE TABLE `Credit_Card_Details` (
  `Card_Name` varchar(25) NOT NULL,
  `Minimum_Amount` int(11) DEFAULT NULL,
  `Maximum_Amount` int(11) DEFAULT NULL,
  `Eligibility` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `Credit_Card_Details`
--

INSERT INTO `Credit_Card_Details` (`Card_Name`, `Minimum_Amount`, `Maximum_Amount`, `Eligibility`) VALUES
('Gold', 15000, 45000, 180000),
('Platinum', 50000, 1000000, 6000000),
('Silver', 10000, 30000, 120000);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Admin_Details`
--
ALTER TABLE `Admin_Details`
  ADD PRIMARY KEY (`Email_Id`);

--
-- Indexes for table `Bank_Offers`
--
ALTER TABLE `Bank_Offers`
  ADD PRIMARY KEY (`Offer_Id`);

--
-- Indexes for table `Branch_Details`
--
ALTER TABLE `Branch_Details`
  ADD PRIMARY KEY (`IFSC_Code`);

--
-- Indexes for table `Credit_Card_Details`
--
ALTER TABLE `Credit_Card_Details`
  ADD PRIMARY KEY (`Card_Name`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
