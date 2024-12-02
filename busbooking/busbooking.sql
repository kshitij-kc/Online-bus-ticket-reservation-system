-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 10, 2024 at 04:17 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `busbooking`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`) VALUES
(1, 'admin', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `booking`
--

CREATE TABLE `booking` (
  `id` int(11) NOT NULL,
  `passenger_name` varchar(255) NOT NULL,
  `telephone` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `boarding_place` varchar(255) NOT NULL,
  `Your_destination` varchar(255) NOT NULL,
  `cost` varchar(20) NOT NULL,
  `booking_status` varchar(50) NOT NULL,
  `transaction_id` varchar(50) NOT NULL,
  `route_id` int(11) NOT NULL,
  `bus_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `booking`
--

INSERT INTO `booking` (`id`, `passenger_name`, `telephone`, `email`, `boarding_place`, `Your_destination`, `cost`, `booking_status`, `transaction_id`, `route_id`, `bus_id`) VALUES
(1, 'ratnesh pandit', '1234567890', 'ratnesh@gmail.com', 'pokhara', 'rautahat', '1000', 'Success', 'txn_001', 9, 10),
(2, 'ratnesh pandit', '1234567890', 'ratnesh@gmail.com', 'pokhara', 'katmandu', '1200', 'Success', 'txn_002', 12, 11),
(3, 'prachanda', '9876543210', 'prachanda@gmail.com', 'katmandu', 'rautahat', '1300', 'Success', 'txn_003', 9, 10),
(4, 'prachanda', '9876543210', 'prachanda@gmail.com', 'katmandu', 'kaves', '1100', 'Success', 'txn_004', 13, 14),
(5, 'kshitij Chhetri', '1234567890', 'kshitij@gmail.com', 'pokhara', 'rautahat', '1200', 'Success', 'txn_005', 9, 10),
(6, 'kshitij Chhetri', '1234567890', 'kshitij@gmail.com', 'pokhara', 'katmandu', '1300', 'Success', 'txn_006', 12, 11),
(7, 'ram', '2345678901', 'ram@gmail.com', 'katmandu', 'rautahat', '1100', 'Success', 'txn_007', 9, 12),
(8, 'ram', '2345678901', 'ram@gmail.com', 'katmandu', 'kaves', '1150', 'Success', 'txn_008', 13, 13),
(9, 'sugam', '3456789012', 'sugam@gmail.com', 'pokhara', 'rautahat', '1000', 'Success', 'txn_009', 9, 11),
(10, 'sugam', '3456789012', 'sugam@gmail.com', 'pokhara', 'katmandu', '1200', 'Success', 'txn_010', 12, 12),
(11, 'tej', '4567890123', 'tej@gmail.com', 'katmandu', 'rautahat', '1250', 'Success', 'txn_011', 9, 10),
(12, 'tej', '4567890123', 'tej@gmail.com', 'katmandu', 'kaves', '1050', 'Success', 'txn_012', 13, 14),
(13, 'kshitij Chhetri', '1234567890', 'kshitij@gmail.com', 'pokhara', 'rautahat', '5400', 'Success', 'txn_689276', 0, 0),
(14, 'kshitij Chhetri', '1234567890', 'kshitij@gmail.com', 'pokhara', 'rautahat', '4000', 'Pending', 'txn_4174', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `bus`
--

CREATE TABLE `bus` (
  `id` int(11) NOT NULL,
  `Bus_Name` varchar(255) NOT NULL,
  `Tel` varchar(20) NOT NULL,
  `bus_picture` varchar(100) NOT NULL,
  `seat_available` int(11) NOT NULL,
  `booked_seats` int(11) NOT NULL,
  `route` varchar(250) NOT NULL,
  `route_name` varchar(100) NOT NULL,
  `cost` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bus`
--

INSERT INTO `bus` (`id`, `Bus_Name`, `Tel`, `bus_picture`, `seat_available`, `booked_seats`, `route`, `route_name`, `cost`) VALUES
(10, 'Test Bus', '12234567890', '', 11, 9, '9', 'Kathmandu To Rautahat', '1200'),
(11, 'Sathi Deluxe', '9811223344', '', 30, 0, '10', '', '1500'),
(12, 'Nepal', '9833445566', '', 20, 0, '10', '', '3000'),
(13, 'Jwala Deluxe', '9755665544', '', 9, 1, '11', '', '4000'),
(18, 'asdasdasd', '1234567890', 'image/1.jpg', 20, 0, '9', '', '200'),
(19, 'Benedict Adkins', '1234567890', '0', 9, 5, '12', 'Kathmandu To Nepalgunj', '5400');

-- --------------------------------------------------------

--
-- Table structure for table `route`
--

CREATE TABLE `route` (
  `id` int(11) NOT NULL,
  `via_city` varchar(255) NOT NULL,
  `destination` varchar(255) NOT NULL,
  `bus_name` varchar(255) NOT NULL,
  `departure_date` date NOT NULL,
  `departure_time` time(6) NOT NULL,
  `bus_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `route`
--

INSERT INTO `route` (`id`, `via_city`, `destination`, `bus_name`, `departure_date`, `departure_time`, `bus_id`) VALUES
(9, 'kathmandu', 'Rautahat', 'delux ', '2024-05-12', '19:00:00.000000', 0),
(10, 'kathmandu', 'Pokhra', 'sathi deluxe ', '2024-09-22', '18:00:00.000000', 0),
(11, 'kathmandu ', 'janakpur', 'Nepal', '2024-09-22', '19:00:00.000000', 0),
(12, 'kathmandu', 'Nepalgunj', 'Jwala Deluxe', '2024-09-16', '16:00:00.000000', 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `First_Name` varchar(255) NOT NULL,
  `Last_Name` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `First_Name`, `Last_Name`, `username`, `email`, `password`) VALUES
(6, 'ratnesh ', 'pandit', 'ratnesh ', 'ratnesh@gmail.com', 'f3ed11bbdb94fd9ebdefbaf646ab94d3'),
(7, 'sugam', 'chaudhary', 'sugam', 'sugam@gmail.com', 'f3ed11bbdb94fd9ebdefbaf646ab94d3'),
(8, 'prachanda', 'bk', 'prachanda bk', 'prachanda@gamil.com', 'f3ed11bbdb94fd9ebdefbaf646ab94d3'),
(9, 'ram ', 'ram ', 'ram', 'ram@gmail.com', 'ram'),
(10, '123', '123', 'ram', '@', '123'),
(11, 'keshab', 'lal', 'keshab', 'keshab@gmail.com', 'keshab'),
(12, 'raunak', 'kushwaha', 'raunak', 'raunak@gmail.com', 'raunak'),
(13, 'ratnesh', 'pandit', 'ratnesh', 'ratnesh123@gmail.com', 'ratnesh'),
(14, 'kshitij', 'Chhetri', 'kshitij', 'kshitij@gmail.com', 'f3ed11bbdb94fd9ebdefbaf646ab94d3');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `booking`
--
ALTER TABLE `booking`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bus`
--
ALTER TABLE `bus`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `route`
--
ALTER TABLE `route`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `booking`
--
ALTER TABLE `booking`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `bus`
--
ALTER TABLE `bus`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `route`
--
ALTER TABLE `route`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
