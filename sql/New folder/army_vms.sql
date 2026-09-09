-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 05, 2026 at 09:24 AM
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
-- Database: `army_vms`
--

-- --------------------------------------------------------

--
-- Table structure for table `drivers`
--

CREATE TABLE `drivers` (
  `id` varchar(20) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `rank` varchar(50) DEFAULT NULL,
  `license_class` varchar(5) DEFAULT NULL,
  `license_expiry` date DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `whatsapp` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `status` enum('idle','on_duty','off','inactive') DEFAULT 'idle',
  `assigned_vehicles` text DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `drivers`
--

INSERT INTO `drivers` (`id`, `first_name`, `last_name`, `rank`, `license_class`, `license_expiry`, `phone`, `whatsapp`, `email`, `status`, `assigned_vehicles`, `notes`, `created_at`) VALUES
('D-2569', 'Sithumina', 'gagen', 'Lance Corporal', 'C', '2027-04-03', '+94765658967', '+94765658967', '', 'on_duty', NULL, '', '2026-04-04 04:38:57'),
('D9995', 'Aruna', 'Subash', 'Private', 'A', '2027-04-03', '+94765658966', '+94765658966', 'arunasubash522@gmail.com', 'idle', NULL, '', '2026-04-03 11:19:44');

-- --------------------------------------------------------

--
-- Table structure for table `driver_vehicles`
--

CREATE TABLE `driver_vehicles` (
  `driver_id` varchar(20) NOT NULL,
  `vehicle_id` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `driver_vehicles`
--

INSERT INTO `driver_vehicles` (`driver_id`, `vehicle_id`) VALUES
('D-2569', 'A4568');

-- --------------------------------------------------------

--
-- Table structure for table `fuel_stock`
--

CREATE TABLE `fuel_stock` (
  `id` int(11) NOT NULL,
  `diesel` int(11) DEFAULT 0,
  `petrol` int(11) DEFAULT 0,
  `diesel_threshold` int(11) DEFAULT 2000,
  `petrol_threshold` int(11) DEFAULT 1000,
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `fuel_stock`
--

INSERT INTO `fuel_stock` (`id`, `diesel`, `petrol`, `diesel_threshold`, `petrol_threshold`, `last_updated`) VALUES
(1, 9770, 8000, 2000, 1000, '2026-04-03 17:10:48');

-- --------------------------------------------------------

--
-- Table structure for table `fuel_transactions`
--

CREATE TABLE `fuel_transactions` (
  `id` varchar(20) NOT NULL,
  `type` enum('allocation','refill') NOT NULL,
  `date` date NOT NULL,
  `vehicle_id` varchar(20) DEFAULT NULL,
  `fuel_type` enum('diesel','petrol') NOT NULL,
  `amount` int(11) NOT NULL,
  `purpose` varchar(255) DEFAULT NULL,
  `authorized_by` varchar(100) DEFAULT NULL,
  `stock_balance_diesel` int(11) DEFAULT NULL,
  `stock_balance_petrol` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `fuel_transactions`
--

INSERT INTO `fuel_transactions` (`id`, `type`, `date`, `vehicle_id`, `fuel_type`, `amount`, `purpose`, `authorized_by`, `stock_balance_diesel`, `stock_balance_petrol`, `created_at`) VALUES
('FT8EAFB0', 'allocation', '2026-04-03', 'A 2565', 'diesel', 80, 'emergency', 'Capt. MN MADHUSANKA', 9770, 8000, '2026-04-03 17:10:48');

-- --------------------------------------------------------

--
-- Table structure for table `geofences`
--

CREATE TABLE `geofences` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `type` enum('forbidden','authorized') DEFAULT 'forbidden',
  `coordinates` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `geofences`
--

INSERT INTO `geofences` (`id`, `name`, `type`, `coordinates`, `created_at`) VALUES
(2, 'area23', 'forbidden', '[{\"lat\":6.424483546180726,\"lng\":79.7299808615397},{\"lat\":7.171750602122866,\"lng\":79.7299808615397},{\"lat\":7.171750602122866,\"lng\":80.54267847840603},{\"lat\":6.424483546180726,\"lng\":80.54267847840603}]', '2026-04-05 06:14:31'),
(3, '12', 'forbidden', '[{\"lat\":9.804642871755279,\"lng\":80.20566343621861},{\"lat\":9.84286949354721,\"lng\":80.20566343621861},{\"lat\":9.84286949354721,\"lng\":80.26417903743307},{\"lat\":9.804642871755279,\"lng\":80.26417903743307}]', '2026-04-05 06:15:36');

-- --------------------------------------------------------

--
-- Table structure for table `gps_devices`
--

CREATE TABLE `gps_devices` (
  `id` varchar(20) NOT NULL,
  `vehicle_id` varchar(20) DEFAULT NULL,
  `type` varchar(30) DEFAULT NULL,
  `update_interval` int(11) DEFAULT 30,
  `status` enum('online','offline') DEFAULT 'online',
  `last_update` datetime DEFAULT NULL,
  `latitude` decimal(10,7) DEFAULT NULL,
  `longitude` decimal(10,7) DEFAULT NULL,
  `accuracy` int(11) DEFAULT NULL,
  `battery_level` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gps_tracking`
--

CREATE TABLE `gps_tracking` (
  `id` int(11) NOT NULL,
  `vehicle_id` varchar(20) DEFAULT NULL,
  `latitude` decimal(10,7) DEFAULT NULL,
  `longitude` decimal(10,7) DEFAULT NULL,
  `accuracy` int(11) DEFAULT NULL,
  `source` varchar(20) DEFAULT NULL,
  `timestamp` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `gps_tracking`
--

INSERT INTO `gps_tracking` (`id`, `vehicle_id`, `latitude`, `longitude`, `accuracy`, `source`, `timestamp`) VALUES
(1, NULL, 9.8276660, 80.2352820, 10, 'driver_gps', '2026-04-02 13:22:12'),
(2, NULL, 9.8276660, 80.2352820, 10, 'driver_gps', '2026-04-02 13:27:10'),
(3, NULL, 9.8276660, 80.2352820, 10, 'driver_gps', '2026-04-02 13:32:12'),
(4, NULL, 9.8276660, 80.2352820, 10, 'driver_gps', '2026-04-02 13:37:12'),
(5, NULL, 9.8276660, 80.2352820, 10, 'driver_gps', '2026-04-02 13:42:12'),
(6, NULL, 9.8279526, 80.2354411, 10, 'driver_gps', '2026-04-02 13:53:42'),
(7, 'A 2565', 9.8278431, 80.2354305, 10, 'driver_gps', '2026-04-05 09:04:07'),
(8, 'A 2565', 9.8278446, 80.2354312, 10, 'driver_gps', '2026-04-05 09:04:08'),
(9, 'A 2565', 9.8278449, 80.2354311, 10, 'driver_gps', '2026-04-05 09:04:09'),
(10, 'A 2565', 9.8278449, 80.2354309, 10, 'driver_gps', '2026-04-05 09:04:10'),
(11, 'A 2565', 9.8278448, 80.2354307, 10, 'driver_gps', '2026-04-05 09:04:11'),
(12, 'A 2565', 9.8278447, 80.2354305, 10, 'driver_gps', '2026-04-05 09:04:12'),
(13, 'A 2565', 9.8278445, 80.2354302, 10, 'driver_gps', '2026-04-05 09:04:13'),
(14, 'A 2565', 9.8278443, 80.2354300, 10, 'driver_gps', '2026-04-05 09:04:14'),
(15, 'A 2565', 9.8278441, 80.2354297, 10, 'driver_gps', '2026-04-05 09:04:15'),
(16, 'A 2565', 9.8278439, 80.2354294, 10, 'driver_gps', '2026-04-05 09:04:16'),
(17, 'A 2565', 9.8278438, 80.2354292, 10, 'driver_gps', '2026-04-05 09:04:17'),
(18, 'A 2565', 9.8278494, 80.2354338, 10, 'driver_gps', '2026-04-05 09:04:17'),
(19, 'A 2565', 9.8278518, 80.2354356, 10, 'driver_gps', '2026-04-05 09:04:18'),
(20, 'A 2565', 9.8278523, 80.2354358, 10, 'driver_gps', '2026-04-05 09:04:19'),
(21, 'A 2565', 9.8278522, 80.2354356, 10, 'driver_gps', '2026-04-05 09:04:20'),
(22, 'A 2565', 9.8278518, 80.2354352, 10, 'driver_gps', '2026-04-05 09:04:21'),
(23, 'A 2565', 9.8278515, 80.2354347, 10, 'driver_gps', '2026-04-05 09:04:22'),
(24, 'A 2565', 9.8278512, 80.2354344, 10, 'driver_gps', '2026-04-05 09:04:23'),
(25, 'A 2565', 9.8278508, 80.2354338, 10, 'driver_gps', '2026-04-05 09:04:24'),
(26, 'A 2565', 9.8278503, 80.2354328, 10, 'driver_gps', '2026-04-05 09:04:26'),
(27, 'A 2565', 9.8278498, 80.2354329, 10, 'driver_gps', '2026-04-05 09:04:27'),
(28, 'A 2565', 9.8278501, 80.2354324, 10, 'driver_gps', '2026-04-05 09:04:27'),
(29, 'A 2565', 9.8278498, 80.2354323, 10, 'driver_gps', '2026-04-05 09:04:28'),
(30, 'A 2565', 9.8278570, 80.2354371, 10, 'driver_gps', '2026-04-05 09:04:28'),
(31, 'A 2565', 9.8278591, 80.2354386, 10, 'driver_gps', '2026-04-05 09:04:29'),
(32, 'A 2565', 9.8278593, 80.2354386, 10, 'driver_gps', '2026-04-05 09:04:30'),
(33, 'A 2565', 9.8278587, 80.2354387, 10, 'driver_gps', '2026-04-05 09:04:31'),
(34, 'A 2565', 9.8278583, 80.2354383, 10, 'driver_gps', '2026-04-05 09:04:32'),
(35, 'A 2565', 9.8278579, 80.2354378, 10, 'driver_gps', '2026-04-05 09:04:33'),
(36, 'A 2565', 9.8278574, 80.2354372, 10, 'driver_gps', '2026-04-05 09:04:34'),
(37, 'A 2565', 9.8278569, 80.2354367, 10, 'driver_gps', '2026-04-05 09:04:35'),
(38, 'A 2565', 9.8278564, 80.2354361, 10, 'driver_gps', '2026-04-05 09:04:36'),
(39, 'A 2565', 9.8278558, 80.2354356, 10, 'driver_gps', '2026-04-05 09:04:37'),
(40, 'A 2565', 9.8278552, 80.2354357, 10, 'driver_gps', '2026-04-05 09:04:38'),
(41, 'A 2565', 9.8278553, 80.2354356, 10, 'driver_gps', '2026-04-05 09:04:38'),
(42, 'A 2565', 9.8278648, 80.2354422, 10, 'driver_gps', '2026-04-05 09:04:38'),
(43, 'A 2565', 9.8278649, 80.2354428, 10, 'driver_gps', '2026-04-05 09:04:39'),
(44, 'A 2565', 9.8278645, 80.2354426, 10, 'driver_gps', '2026-04-05 09:04:40'),
(45, 'A 2565', 9.8278639, 80.2354421, 10, 'driver_gps', '2026-04-05 09:04:41'),
(46, 'A 2565', 9.8278632, 80.2354415, 10, 'driver_gps', '2026-04-05 09:04:42'),
(47, 'A 2565', 9.8278626, 80.2354409, 10, 'driver_gps', '2026-04-05 09:04:43'),
(48, 'A 2565', 9.8278615, 80.2354407, 10, 'driver_gps', '2026-04-05 09:04:44'),
(49, 'A 2565', 9.8278578, 80.2354376, 10, 'driver_gps', '2026-04-05 09:04:45'),
(50, 'A 2565', 9.8278539, 80.2354352, 10, 'driver_gps', '2026-04-05 09:04:46'),
(51, 'A 2565', 9.8278507, 80.2354330, 10, 'driver_gps', '2026-04-05 09:04:47'),
(52, 'A 2565', 9.8278497, 80.2354319, 10, 'driver_gps', '2026-04-05 09:04:48'),
(53, 'A 2565', 9.8278474, 80.2354307, 10, 'driver_gps', '2026-04-05 09:04:48'),
(54, 'A 2565', 9.8278473, 80.2354302, 10, 'driver_gps', '2026-04-05 09:04:49'),
(55, 'A 2565', 9.8278545, 80.2354358, 10, 'driver_gps', '2026-04-05 09:04:49'),
(56, 'A 2565', 9.8278546, 80.2354363, 10, 'driver_gps', '2026-04-05 09:04:50'),
(57, 'A 2565', 9.8278544, 80.2354361, 10, 'driver_gps', '2026-04-05 09:04:51'),
(58, 'A 2565', 9.8278543, 80.2354359, 10, 'driver_gps', '2026-04-05 09:04:52'),
(59, 'A 2565', 9.8278536, 80.2354354, 10, 'driver_gps', '2026-04-05 09:04:53'),
(60, 'A 2565', 9.8278529, 80.2354351, 10, 'driver_gps', '2026-04-05 09:04:54'),
(61, 'A 2565', 9.8278506, 80.2354334, 10, 'driver_gps', '2026-04-05 09:04:55'),
(62, 'A 2565', 9.8278479, 80.2354309, 10, 'driver_gps', '2026-04-05 09:04:56'),
(63, 'A 2565', 9.8278472, 80.2354303, 10, 'driver_gps', '2026-04-05 09:04:57'),
(64, 'A 2565', 9.8278443, 80.2354277, 10, 'driver_gps', '2026-04-05 09:04:58'),
(65, 'A 2565', 9.8278436, 80.2354273, 10, 'driver_gps', '2026-04-05 09:04:59'),
(66, 'A 2565', 9.8278433, 80.2354268, 10, 'driver_gps', '2026-04-05 09:04:59'),
(67, 'A 2565', 9.8278486, 80.2354312, 10, 'driver_gps', '2026-04-05 09:04:59'),
(68, 'A 2565', 9.8278504, 80.2354329, 10, 'driver_gps', '2026-04-05 09:05:00'),
(69, 'A 2565', 9.8278506, 80.2354331, 10, 'driver_gps', '2026-04-05 09:05:01'),
(70, 'A 2565', 9.8278505, 80.2354329, 10, 'driver_gps', '2026-04-05 09:05:02'),
(71, 'A 2565', 9.8278503, 80.2354326, 10, 'driver_gps', '2026-04-05 09:05:03'),
(72, 'A 2565', 9.8278501, 80.2354324, 10, 'driver_gps', '2026-04-05 09:05:04'),
(73, 'A 2565', 9.8278498, 80.2354322, 10, 'driver_gps', '2026-04-05 09:05:05'),
(74, 'A 2565', 9.8278496, 80.2354320, 10, 'driver_gps', '2026-04-05 09:05:06'),
(75, 'A 2565', 9.8278493, 80.2354318, 10, 'driver_gps', '2026-04-05 09:05:07'),
(76, 'A 2565', 9.8278490, 80.2354316, 10, 'driver_gps', '2026-04-05 09:05:08'),
(77, 'A 2565', 9.8278489, 80.2354311, 10, 'driver_gps', '2026-04-05 09:05:09'),
(78, 'A 2565', 9.8278486, 80.2354313, 10, 'driver_gps', '2026-04-05 09:05:09'),
(79, 'A 2565', 9.8278486, 80.2354312, 10, 'driver_gps', '2026-04-05 09:05:10'),
(80, 'A 2565', 9.8278551, 80.2354375, 10, 'driver_gps', '2026-04-05 09:05:10'),
(81, 'A 2565', 9.8278566, 80.2354389, 10, 'driver_gps', '2026-04-05 09:05:11'),
(82, 'A 2565', 9.8278566, 80.2354391, 10, 'driver_gps', '2026-04-05 09:05:12'),
(83, 'A 2565', 9.8278563, 80.2354389, 10, 'driver_gps', '2026-04-05 09:05:13'),
(84, 'A 2565', 9.8278559, 80.2354387, 10, 'driver_gps', '2026-04-05 09:05:14'),
(85, 'A 2565', 9.8278553, 80.2354385, 10, 'driver_gps', '2026-04-05 09:05:15'),
(86, 'A 2565', 9.8278548, 80.2354382, 10, 'driver_gps', '2026-04-05 09:05:16'),
(87, 'A 2565', 9.8278542, 80.2354380, 10, 'driver_gps', '2026-04-05 09:05:17'),
(88, 'A 2565', 9.8278540, 80.2354380, 10, 'driver_gps', '2026-04-05 09:05:18'),
(89, 'A 2565', 9.8278529, 80.2354369, 10, 'driver_gps', '2026-04-05 09:05:19'),
(90, 'A 2565', 9.8278532, 80.2354375, 10, 'driver_gps', '2026-04-05 09:05:19'),
(91, 'A 2565', 9.8278528, 80.2354374, 10, 'driver_gps', '2026-04-05 09:05:20'),
(92, 'A 2565', 9.8278593, 80.2354421, 10, 'driver_gps', '2026-04-05 09:05:20'),
(93, 'A 2565', 9.8278615, 80.2354436, 10, 'driver_gps', '2026-04-05 09:05:21'),
(94, 'A 2565', 9.8278616, 80.2354438, 10, 'driver_gps', '2026-04-05 09:05:22'),
(95, 'A 2565', 9.8278612, 80.2354436, 10, 'driver_gps', '2026-04-05 09:05:23'),
(96, 'A 2565', 9.8278607, 80.2354434, 10, 'driver_gps', '2026-04-05 09:05:24'),
(97, 'A 2565', 9.8278600, 80.2354431, 10, 'driver_gps', '2026-04-05 09:05:25'),
(98, 'A 2565', 9.8278596, 80.2354428, 10, 'driver_gps', '2026-04-05 09:05:26'),
(99, 'A 2565', 9.8278590, 80.2354425, 10, 'driver_gps', '2026-04-05 09:05:27'),
(100, 'A 2565', 9.8278583, 80.2354423, 10, 'driver_gps', '2026-04-05 09:05:28'),
(101, 'A 2565', 9.8278576, 80.2354420, 10, 'driver_gps', '2026-04-05 09:05:29'),
(102, 'A 2565', 9.8278572, 80.2354415, 10, 'driver_gps', '2026-04-05 09:05:30'),
(103, 'A 2565', 9.8278565, 80.2354417, 10, 'driver_gps', '2026-04-05 09:05:30'),
(104, 'A 2565', 9.8278636, 80.2354466, 10, 'driver_gps', '2026-04-05 09:05:31'),
(105, 'A 2565', 9.8278654, 80.2354476, 10, 'driver_gps', '2026-04-05 09:05:32'),
(106, 'A 2565', 9.8278653, 80.2354477, 10, 'driver_gps', '2026-04-05 09:05:33'),
(107, 'A 2565', 9.8278648, 80.2354474, 10, 'driver_gps', '2026-04-05 09:05:34'),
(108, 'A 2565', 9.8278643, 80.2354471, 10, 'driver_gps', '2026-04-05 09:05:35'),
(109, 'A 2565', 9.8278636, 80.2354467, 10, 'driver_gps', '2026-04-05 09:05:36'),
(110, 'A 2565', 9.8278630, 80.2354462, 10, 'driver_gps', '2026-04-05 09:05:37'),
(111, 'A 2565', 9.8278623, 80.2354458, 10, 'driver_gps', '2026-04-05 09:05:38'),
(112, 'A 2565', 9.8278615, 80.2354454, 10, 'driver_gps', '2026-04-05 09:05:39'),
(113, 'A 2565', 9.8278607, 80.2354450, 10, 'driver_gps', '2026-04-05 09:05:40'),
(114, 'A 2565', 9.8278605, 80.2354444, 10, 'driver_gps', '2026-04-05 09:05:41'),
(115, 'A 2565', 9.8278705, 80.2354521, 10, 'driver_gps', '2026-04-05 09:05:41'),
(116, 'A 2565', 9.8278703, 80.2354517, 10, 'driver_gps', '2026-04-05 09:05:43'),
(117, 'A 2565', 9.8278708, 80.2354520, 10, 'driver_gps', '2026-04-05 09:05:42'),
(118, 'A 2565', 9.8278695, 80.2354512, 10, 'driver_gps', '2026-04-05 09:05:44'),
(119, 'A 2565', 9.8278687, 80.2354507, 10, 'driver_gps', '2026-04-05 09:05:45'),
(120, 'A 2565', 9.8278676, 80.2354502, 10, 'driver_gps', '2026-04-05 09:05:46'),
(121, 'A 2565', 9.8278667, 80.2354497, 10, 'driver_gps', '2026-04-05 09:05:47'),
(122, 'A 2565', 9.8278609, 80.2354462, 10, 'driver_gps', '2026-04-05 09:05:48'),
(123, 'A 2565', 9.8278556, 80.2354426, 10, 'driver_gps', '2026-04-05 09:05:49'),
(124, 'A 2565', 9.8278514, 80.2354398, 10, 'driver_gps', '2026-04-05 09:05:50'),
(125, 'A 2565', 9.8278504, 80.2354390, 10, 'driver_gps', '2026-04-05 09:05:51'),
(126, 'A 2565', 9.8278503, 80.2354389, 10, 'driver_gps', '2026-04-05 09:05:51'),
(127, 'A 2565', 9.8278588, 80.2354450, 10, 'driver_gps', '2026-04-05 09:05:51'),
(128, 'A 2565', 9.8278590, 80.2354454, 10, 'driver_gps', '2026-04-05 09:05:52'),
(129, 'A 2565', 9.8278585, 80.2354452, 10, 'driver_gps', '2026-04-05 09:05:53'),
(130, 'A 2565', 9.8278582, 80.2354451, 10, 'driver_gps', '2026-04-05 09:05:54'),
(131, 'A 2565', 9.8278576, 80.2354448, 10, 'driver_gps', '2026-04-05 09:05:55'),
(132, 'A 2565', 9.8278575, 80.2354447, 10, 'driver_gps', '2026-04-05 09:05:56'),
(133, 'A 2565', 9.8278568, 80.2354443, 10, 'driver_gps', '2026-04-05 09:05:56'),
(134, 'A 2565', 9.8278561, 80.2354440, 10, 'driver_gps', '2026-04-05 09:05:57'),
(135, 'A 2565', 9.8278531, 80.2354418, 10, 'driver_gps', '2026-04-05 09:05:58'),
(136, 'A 2565', 9.8278499, 80.2354394, 10, 'driver_gps', '2026-04-05 09:05:59'),
(137, 'A 2565', 9.8278472, 80.2354375, 10, 'driver_gps', '2026-04-05 09:06:00'),
(138, 'A 2565', 9.8278444, 80.2354353, 10, 'driver_gps', '2026-04-05 09:06:02'),
(139, 'A 2565', 9.8278465, 80.2354368, 10, 'driver_gps', '2026-04-05 09:06:01'),
(140, 'A 2565', 9.8278444, 80.2354354, 10, 'driver_gps', '2026-04-05 09:06:01'),
(141, 'A 2565', 9.8278501, 80.2354401, 10, 'driver_gps', '2026-04-05 09:06:02'),
(142, 'A 2565', 9.8278511, 80.2354413, 10, 'driver_gps', '2026-04-05 09:06:03'),
(143, 'A 2565', 9.8278513, 80.2354416, 10, 'driver_gps', '2026-04-05 09:06:04'),
(144, 'A 2565', 9.8278507, 80.2354414, 10, 'driver_gps', '2026-04-05 09:06:05'),
(145, 'A 2565', 9.8278502, 80.2354412, 10, 'driver_gps', '2026-04-05 09:06:06'),
(146, 'A 2565', 9.8278494, 80.2354407, 10, 'driver_gps', '2026-04-05 09:06:08'),
(147, 'A 2565', 9.8278489, 80.2354404, 10, 'driver_gps', '2026-04-05 09:06:09'),
(148, 'A 2565', 9.8278484, 80.2354402, 10, 'driver_gps', '2026-04-05 09:06:10'),
(149, 'A 2565', 9.8278480, 80.2354399, 10, 'driver_gps', '2026-04-05 09:06:11'),
(150, 'A 2565', 9.8278482, 80.2354399, 10, 'driver_gps', '2026-04-05 09:06:11'),
(151, 'A 2565', 9.8278477, 80.2354398, 10, 'driver_gps', '2026-04-05 09:06:12'),
(152, 'A 2565', 9.8278545, 80.2354443, 10, 'driver_gps', '2026-04-05 09:06:12'),
(153, 'A 2565', 9.8278565, 80.2354460, 10, 'driver_gps', '2026-04-05 09:06:13'),
(154, 'A 2565', 9.8278566, 80.2354463, 10, 'driver_gps', '2026-04-05 09:06:14'),
(155, 'A 2565', 9.8278564, 80.2354462, 10, 'driver_gps', '2026-04-05 09:06:15'),
(156, 'A 2565', 9.8278559, 80.2354460, 10, 'driver_gps', '2026-04-05 09:06:16'),
(157, 'A 2565', 9.8278555, 80.2354459, 10, 'driver_gps', '2026-04-05 09:06:17'),
(158, 'A 2565', 9.8278551, 80.2354456, 10, 'driver_gps', '2026-04-05 09:06:18'),
(159, 'A 2565', 9.8278546, 80.2354454, 10, 'driver_gps', '2026-04-05 09:06:19'),
(160, 'A 2565', 9.8278540, 80.2354451, 10, 'driver_gps', '2026-04-05 09:06:20'),
(161, 'A 2565', 9.8278535, 80.2354448, 10, 'driver_gps', '2026-04-05 09:06:21'),
(162, 'A 2565', 9.8278532, 80.2354442, 10, 'driver_gps', '2026-04-05 09:06:22'),
(163, 'A 2565', 9.8278528, 80.2354444, 10, 'driver_gps', '2026-04-05 09:06:22'),
(164, 'A 2565', 9.8278527, 80.2354439, 10, 'driver_gps', '2026-04-05 09:06:23'),
(165, 'A 2565', 9.8278622, 80.2354516, 10, 'driver_gps', '2026-04-05 09:06:23'),
(166, 'A 2565', 9.8278622, 80.2354516, 10, 'driver_gps', '2026-04-05 09:06:24'),
(167, 'A 2565', 9.8278618, 80.2354514, 10, 'driver_gps', '2026-04-05 09:06:25'),
(168, 'A 2565', 9.8278612, 80.2354511, 10, 'driver_gps', '2026-04-05 09:06:26'),
(169, 'A 2565', 9.8278605, 80.2354507, 10, 'driver_gps', '2026-04-05 09:06:27'),
(170, 'A 2565', 9.8278598, 80.2354503, 10, 'driver_gps', '2026-04-05 09:06:28'),
(171, 'A 2565', 9.8278564, 80.2354477, 10, 'driver_gps', '2026-04-05 09:06:29'),
(172, 'A 2565', 9.8278532, 80.2354452, 10, 'driver_gps', '2026-04-05 09:06:30'),
(173, 'A 2565', 9.8278499, 80.2354425, 10, 'driver_gps', '2026-04-05 09:06:31'),
(174, 'A 2565', 9.8278473, 80.2354402, 10, 'driver_gps', '2026-04-05 09:06:32'),
(175, 'A 2565', 9.8278468, 80.2354396, 10, 'driver_gps', '2026-04-05 09:06:33'),
(176, 'A 2565', 9.8278463, 80.2354394, 10, 'driver_gps', '2026-04-05 09:06:33'),
(177, 'A 2565', 9.8278523, 80.2354433, 10, 'driver_gps', '2026-04-05 09:06:33'),
(178, 'A 2565', 9.8278546, 80.2354449, 10, 'driver_gps', '2026-04-05 09:06:34'),
(179, 'A 2565', 9.8278547, 80.2354451, 10, 'driver_gps', '2026-04-05 09:06:35'),
(180, 'A 2565', 9.8278545, 80.2354450, 10, 'driver_gps', '2026-04-05 09:06:36'),
(181, 'A 2565', 9.8278542, 80.2354447, 10, 'driver_gps', '2026-04-05 09:06:37'),
(182, 'A 2565', 9.8278537, 80.2354444, 10, 'driver_gps', '2026-04-05 09:06:38'),
(183, 'A 2565', 9.8278533, 80.2354442, 10, 'driver_gps', '2026-04-05 09:06:39'),
(184, 'A 2565', 9.8278530, 80.2354440, 10, 'driver_gps', '2026-04-05 09:06:40'),
(185, 'A 2565', 9.8278526, 80.2354437, 10, 'driver_gps', '2026-04-05 09:06:41'),
(186, 'A 2565', 9.8278523, 80.2354435, 10, 'driver_gps', '2026-04-05 09:06:42'),
(187, 'A 2565', 9.8278522, 80.2354430, 10, 'driver_gps', '2026-04-05 09:06:43'),
(188, 'A 2565', 9.8278519, 80.2354431, 10, 'driver_gps', '2026-04-05 09:06:43'),
(189, 'A 2565', 9.8278519, 80.2354429, 10, 'driver_gps', '2026-04-05 09:06:43'),
(190, 'A 2565', 9.8278593, 80.2354476, 10, 'driver_gps', '2026-04-05 09:06:44'),
(191, 'A 2565', 9.8278609, 80.2354485, 10, 'driver_gps', '2026-04-05 09:06:45'),
(192, 'A 2565', 9.8278610, 80.2354485, 10, 'driver_gps', '2026-04-05 09:06:46'),
(193, 'A 2565', 9.8278606, 80.2354483, 10, 'driver_gps', '2026-04-05 09:06:47'),
(194, 'A 2565', 9.8278598, 80.2354478, 10, 'driver_gps', '2026-04-05 09:06:49'),
(195, 'A 2565', 9.8278594, 80.2354474, 10, 'driver_gps', '2026-04-05 09:06:50'),
(196, 'A 2565', 9.8278589, 80.2354470, 10, 'driver_gps', '2026-04-05 09:06:51');

-- --------------------------------------------------------

--
-- Table structure for table `movements`
--

CREATE TABLE `movements` (
  `id` varchar(20) NOT NULL,
  `vehicle_id` varchar(20) DEFAULT NULL,
  `driver_id` varchar(20) DEFAULT NULL,
  `check_out` datetime DEFAULT NULL,
  `expected_return` datetime DEFAULT NULL,
  `destination` varchar(255) DEFAULT NULL,
  `purpose` varchar(255) DEFAULT NULL,
  `authorized_by` varchar(100) DEFAULT NULL,
  `status` enum('out','completed','pending') DEFAULT 'out',
  `check_in` datetime DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `return_notes` text DEFAULT NULL,
  `checked_in_by` varchar(100) DEFAULT NULL,
  `checkout_odometer` int(11) DEFAULT 0,
  `checkin_odometer` int(11) DEFAULT 0,
  `checkout_fuel_level` int(11) DEFAULT 0,
  `fuel_level` int(11) DEFAULT NULL,
  `condition_status` varchar(30) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `movements`
--

INSERT INTO `movements` (`id`, `vehicle_id`, `driver_id`, `check_out`, `expected_return`, `destination`, `purpose`, `authorized_by`, `status`, `check_in`, `notes`, `return_notes`, `checked_in_by`, `checkout_odometer`, `checkin_odometer`, `checkout_fuel_level`, `fuel_level`, `condition_status`, `created_at`) VALUES
('69cff356d5e1d', 'A 2565', 'D9995', '2026-04-03 19:05:26', '2026-04-05 21:03:00', 'Jaffna', 'mission', 'Maj. Perera', 'completed', '2026-04-03 19:06:15', '', '', 'Capt. Silva', 2005, 2025, 100, 45, 'good', '2026-04-03 17:05:26'),
('69d0961d31e28', 'A4568', 'D-2569', '2026-04-04 06:39:57', '2026-05-05 21:27:00', 'colombo', 'maintenance', 'Lt. Fernando', 'completed', '2026-04-04 06:41:17', '', '', 'Lt. Fernando', 150, 550, 100, 60, 'needs_maintenance', '2026-04-04 04:39:57');

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `id` varchar(20) NOT NULL,
  `type` varchar(30) NOT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `generated_by` varchar(100) DEFAULT NULL,
  `content` longtext DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `reports`
--

INSERT INTO `reports` (`id`, `type`, `start_date`, `end_date`, `generated_by`, `content`, `created_at`) VALUES
('RPTMNIFMXFW', 'movement', '2026-03-31', '2026-04-03', 'Capt. MN MADHUSANKA', '<div style=\"font-family:Arial,sans-serif;padding:20px;\">\n                <div style=\"text-align:center;margin-bottom:25px;\">\n                    <h1 style=\"color:#006837;\">SRI LANKA ARMY</h1>\n                    <h3 style=\"color:#d4af37;\">VEHICLE MANAGEMENT SYSTEM</h3>\n                    <h2>MOVEMENT REPORT</h2>\n                    <p>Period: 2026-03-31 to 2026-04-03</p>\n                    <hr style=\"border:2px solid #006837;\">\n                </div>\n                <h3 style=\"color:#006837;margin-top:20px;\">Vehicle Movement Summary</h3>\n                    <p><strong>Total:</strong> 1 &nbsp; <strong>Completed:</strong> 1 &nbsp; <strong>Out:</strong> 0</p>\n                <table style=\"width:100%;border-collapse:collapse;margin-top:15px;\">\n                    <thead><tr><th style=\"padding:10px;border:1px solid #ddd;background:#006837;color:white;\">Vehicle</th><th style=\"padding:10px;border:1px solid #ddd;background:#006837;color:white;\">Driver</th><th style=\"padding:10px;border:1px solid #ddd;background:#006837;color:white;\">Destination</th><th style=\"padding:10px;border:1px solid #ddd;background:#006837;color:white;\">Purpose</th><th style=\"padding:10px;border:1px solid #ddd;background:#006837;color:white;\">Check Out</th><th style=\"padding:10px;border:1px solid #ddd;background:#006837;color:white;\">Check In</th><th style=\"padding:10px;border:1px solid #ddd;background:#006837;color:white;\">Authorized By</th></tr></thead>\n                    <tbody><tr><td style=\"padding:8px;border:1px solid #ddd;\">null</td><td style=\"padding:8px;border:1px solid #ddd;\">null</td><td style=\"padding:8px;border:1px solid #ddd;\">colombo</td><td style=\"padding:8px;border:1px solid #ddd;\">mission</td><td style=\"padding:8px;border:1px solid #ddd;\">4/1/2026, 8:49:18 AM</td><td style=\"padding:8px;border:1px solid #ddd;\">4/1/2026, 8:50:37 AM</td><td style=\"padding:8px;border:1px solid #ddd;\">Capt. Silva</td></tr></tbody>\n                </table>\n                <div style=\"margin-top:40px;text-align:right;\">\n                    <p>Generated by: Capt. MN MADHUSANKA</p>\n                    <p>Date: 4/3/2026, 10:27:39 AM</p>\n                    <p style=\"margin-top:40px;\">_________________________</p>\n                    <p>Authorizing Officer</p>\n                </div>\n            </div>', '2026-04-03 04:57:39'),
('RPTMNILH5DX', 'daily', '2026-03-31', '2026-04-03', 'Capt. MN MADHUSANKA', '<div style=\"font-family:Arial,sans-serif;padding:20px;\">\n                <div style=\"text-align:center;margin-bottom:25px;\">\n                    <h1 style=\"color:#006837;\">SRI LANKA ARMY</h1>\n                    <h3 style=\"color:#d4af37;\">VEHICLE MANAGEMENT SYSTEM</h3>\n                    <h2>DAILY REPORT</h2>\n                    <p>Period: 2026-03-31 to 2026-04-03</p>\n                    <hr style=\"border:2px solid #006837;\">\n                </div>\n                <h3 style=\"color:#006837;margin-top:20px;\">Vehicle Movement Summary</h3>\n                    <p><strong>Total:</strong> 2 &nbsp; <strong>Completed:</strong> 1 &nbsp; <strong>Out:</strong> 1</p>\n                <table style=\"width:100%;border-collapse:collapse;margin-top:15px;\">\n                    <thead><tr><th style=\"padding:10px;border:1px solid #ddd;background:#006837;color:white;\">Vehicle</th><th style=\"padding:10px;border:1px solid #ddd;background:#006837;color:white;\">Driver</th><th style=\"padding:10px;border:1px solid #ddd;background:#006837;color:white;\">Destination</th><th style=\"padding:10px;border:1px solid #ddd;background:#006837;color:white;\">Purpose</th><th style=\"padding:10px;border:1px solid #ddd;background:#006837;color:white;\">Check Out</th><th style=\"padding:10px;border:1px solid #ddd;background:#006837;color:white;\">Check In</th><th style=\"padding:10px;border:1px solid #ddd;background:#006837;color:white;\">Authorized By</th></tr></thead>\n                    <tbody><tr><td style=\"padding:8px;border:1px solid #ddd;\">A 2570</td><td style=\"padding:8px;border:1px solid #ddd;\">Kumara Subash</td><td style=\"padding:8px;border:1px solid #ddd;\">cbo</td><td style=\"padding:8px;border:1px solid #ddd;\">mission</td><td style=\"padding:8px;border:1px solid #ddd;\">4/3/2026, 8:24:12 AM</td><td style=\"padding:8px;border:1px solid #ddd;\">Not returned</td><td style=\"padding:8px;border:1px solid #ddd;\">Maj. Perera</td></tr><tr><td style=\"padding:8px;border:1px solid #ddd;\">null</td><td style=\"padding:8px;border:1px solid #ddd;\">null</td><td style=\"padding:8px;border:1px solid #ddd;\">colombo</td><td style=\"padding:8px;border:1px solid #ddd;\">mission</td><td style=\"padding:8px;border:1px solid #ddd;\">4/1/2026, 8:49:18 AM</td><td style=\"padding:8px;border:1px solid #ddd;\">4/1/2026, 8:50:37 AM</td><td style=\"padding:8px;border:1px solid #ddd;\">Capt. Silva</td></tr></tbody>\n                </table>\n                <div style=\"margin-top:40px;text-align:right;\">\n                    <p>Generated by: Capt. MN MADHUSANKA</p>\n                    <p>Date: 4/3/2026, 1:11:07 PM</p>\n                    <p style=\"margin-top:40px;\">_________________________</p>\n                    <p>Authorizing Officer</p>\n                </div>\n            </div>', '2026-04-03 07:41:07'),
('RPTMNJ5OYWT', 'fuel', '2026-03-31', '2026-04-03', 'Capt. MN MADHUSANKA', '<div style=\"font-family:Arial,sans-serif;padding:20px;\">\n                <div style=\"text-align:center;margin-bottom:25px;\">\n                    <h1 style=\"color:#006837;\">SRI LANKA ARMY</h1>\n                    <h3 style=\"color:#d4af37;\">VEHICLE MANAGEMENT SYSTEM</h3>\n                    <h2>FUEL REPORT</h2>\n                    <p>Period: 2026-03-31 to 2026-04-03</p>\n                    <hr style=\"border:2px solid #006837;\">\n                </div>\n                <h3 style=\"color:#006837;margin-top:20px;\">Fuel Consumption Summary</h3>\n                    <p><strong>Total:</strong> 0L &nbsp; <strong>Diesel:</strong> 0L &nbsp; <strong>Petrol:</strong> 0L</p>\n                <table style=\"width:100%;border-collapse:collapse;margin-top:15px;\">\n                    <thead><tr><th style=\"padding:10px;border:1px solid #ddd;background:#006837;color:white;\">Date</th><th style=\"padding:10px;border:1px solid #ddd;background:#006837;color:white;\">Vehicle</th><th style=\"padding:10px;border:1px solid #ddd;background:#006837;color:white;\">Type</th><th style=\"padding:10px;border:1px solid #ddd;background:#006837;color:white;\">Amount</th><th style=\"padding:10px;border:1px solid #ddd;background:#006837;color:white;\">Purpose</th><th style=\"padding:10px;border:1px solid #ddd;background:#006837;color:white;\">Authorized By</th></tr></thead>\n                    <tbody><tr><td colspan=\"6\" style=\"padding:15px;text-align:center;\">No records found</td></tr></tbody>\n                </table>\n                <div style=\"margin-top:40px;text-align:right;\">\n                    <p>Generated by: Capt. MN MADHUSANKA</p>\n                    <p>Date: 4/3/2026, 10:37:04 PM</p>\n                    <p style=\"margin-top:40px;\">_________________________</p>\n                    <p>Authorizing Officer</p>\n                </div>\n            </div>', '2026-04-03 17:07:04'),
('RPTMNJ5PGIB', 'fuel', '2026-03-31', '2026-04-03', 'Capt. MN MADHUSANKA', '<div style=\"font-family:Arial,sans-serif;padding:20px;\">\n                <div style=\"text-align:center;margin-bottom:25px;\">\n                    <h1 style=\"color:#006837;\">SRI LANKA ARMY</h1>\n                    <h3 style=\"color:#d4af37;\">VEHICLE MANAGEMENT SYSTEM</h3>\n                    <h2>FUEL REPORT</h2>\n                    <p>Period: 2026-03-31 to 2026-04-03</p>\n                    <hr style=\"border:2px solid #006837;\">\n                </div>\n                <h3 style=\"color:#006837;margin-top:20px;\">Fuel Consumption Summary</h3>\n                    <p><strong>Total:</strong> 0L &nbsp; <strong>Diesel:</strong> 0L &nbsp; <strong>Petrol:</strong> 0L</p>\n                <table style=\"width:100%;border-collapse:collapse;margin-top:15px;\">\n                    <thead><tr><th style=\"padding:10px;border:1px solid #ddd;background:#006837;color:white;\">Date</th><th style=\"padding:10px;border:1px solid #ddd;background:#006837;color:white;\">Vehicle</th><th style=\"padding:10px;border:1px solid #ddd;background:#006837;color:white;\">Type</th><th style=\"padding:10px;border:1px solid #ddd;background:#006837;color:white;\">Amount</th><th style=\"padding:10px;border:1px solid #ddd;background:#006837;color:white;\">Purpose</th><th style=\"padding:10px;border:1px solid #ddd;background:#006837;color:white;\">Authorized By</th></tr></thead>\n                    <tbody><tr><td colspan=\"6\" style=\"padding:15px;text-align:center;\">No records found</td></tr></tbody>\n                </table>\n                <div style=\"margin-top:40px;text-align:right;\">\n                    <p>Generated by: Capt. MN MADHUSANKA</p>\n                    <p>Date: 4/3/2026, 10:37:27 PM</p>\n                    <p style=\"margin-top:40px;\">_________________________</p>\n                    <p>Authorizing Officer</p>\n                </div>\n            </div>', '2026-04-03 17:07:27'),
('RPTMNJ5PJ57', 'movement', '2026-03-31', '2026-04-03', 'Capt. MN MADHUSANKA', '<div style=\"font-family:Arial,sans-serif;padding:20px;\">\n                <div style=\"text-align:center;margin-bottom:25px;\">\n                    <h1 style=\"color:#006837;\">SRI LANKA ARMY</h1>\n                    <h3 style=\"color:#d4af37;\">VEHICLE MANAGEMENT SYSTEM</h3>\n                    <h2>MOVEMENT REPORT</h2>\n                    <p>Period: 2026-03-31 to 2026-04-03</p>\n                    <hr style=\"border:2px solid #006837;\">\n                </div>\n                <h3 style=\"color:#006837;margin-top:20px;\">Vehicle Movement Summary</h3>\n                    <p><strong>Total:</strong> 1 &nbsp; <strong>Completed:</strong> 1 &nbsp; <strong>Out:</strong> 0</p>\n                <table style=\"width:100%;border-collapse:collapse;margin-top:15px;\">\n                    <thead><tr><th style=\"padding:10px;border:1px solid #ddd;background:#006837;color:white;\">Vehicle</th><th style=\"padding:10px;border:1px solid #ddd;background:#006837;color:white;\">Driver</th><th style=\"padding:10px;border:1px solid #ddd;background:#006837;color:white;\">Destination</th><th style=\"padding:10px;border:1px solid #ddd;background:#006837;color:white;\">Purpose</th><th style=\"padding:10px;border:1px solid #ddd;background:#006837;color:white;\">Check Out</th><th style=\"padding:10px;border:1px solid #ddd;background:#006837;color:white;\">Check In</th><th style=\"padding:10px;border:1px solid #ddd;background:#006837;color:white;\">Authorized By</th></tr></thead>\n                    <tbody><tr><td style=\"padding:8px;border:1px solid #ddd;\">A 2565</td><td style=\"padding:8px;border:1px solid #ddd;\">Aruna Subash</td><td style=\"padding:8px;border:1px solid #ddd;\">Jaffna</td><td style=\"padding:8px;border:1px solid #ddd;\">mission</td><td style=\"padding:8px;border:1px solid #ddd;\">4/3/2026, 7:05:26 PM</td><td style=\"padding:8px;border:1px solid #ddd;\">4/3/2026, 7:06:15 PM</td><td style=\"padding:8px;border:1px solid #ddd;\">Maj. Perera</td></tr></tbody>\n                </table>\n                <div style=\"margin-top:40px;text-align:right;\">\n                    <p>Generated by: Capt. MN MADHUSANKA</p>\n                    <p>Date: 4/3/2026, 10:37:30 PM</p>\n                    <p style=\"margin-top:40px;\">_________________________</p>\n                    <p>Authorizing Officer</p>\n                </div>\n            </div>', '2026-04-03 17:07:30');

-- --------------------------------------------------------

--
-- Table structure for table `service_records`
--

CREATE TABLE `service_records` (
  `id` varchar(20) NOT NULL,
  `vehicle_id` varchar(20) DEFAULT NULL,
  `date` date NOT NULL,
  `type` enum('routine','repair','inspection') NOT NULL,
  `description` text DEFAULT NULL,
  `cost` decimal(10,2) DEFAULT NULL,
  `technician` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `service_records`
--

INSERT INTO `service_records` (`id`, `vehicle_id`, `date`, `type`, `description`, `cost`, `technician`, `created_at`) VALUES
('S375066', NULL, '2026-04-03', 'inspection', 'Inspection pass', 150.00, 'capt Aruna subash', '2026-04-03 05:09:23');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `rank` varchar(50) DEFAULT NULL,
  `role` enum('admin','officer','driver','mechanic','fuel_manager') NOT NULL DEFAULT 'officer',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `full_name`, `rank`, `role`, `created_at`) VALUES
(1, 'admin', '240be518fabd2724ddb6f04eeb1da5967448d7e831c08c8fa822809f74c720a9', 'Capt. MN MADHUSANKA', 'Captain', 'admin', '2026-03-31 08:59:26'),
(9, 'D-2569', '2879c83c1addddbdc0e440affaa53d57600bc7ae0dfa380a8ad9968c41330319', 'Sithum Kumara', 'Private', 'driver', '2026-04-05 06:51:42');

-- --------------------------------------------------------

--
-- Table structure for table `vehicles`
--

CREATE TABLE `vehicles` (
  `id` varchar(20) NOT NULL,
  `type` varchar(50) NOT NULL,
  `category` enum('light','heavy','threeWheel','motorcycle','apc','utility') NOT NULL,
  `model` varchar(100) NOT NULL,
  `registration` varchar(30) NOT NULL,
  `year` year(4) NOT NULL,
  `fuel_type` enum('diesel','petrol') NOT NULL DEFAULT 'diesel',
  `fuel_capacity` int(11) NOT NULL DEFAULT 80,
  `fuel_level` int(11) NOT NULL DEFAULT 100,
  `status` enum('active','inactive','maintenance') NOT NULL DEFAULT 'active',
  `unit` varchar(100) DEFAULT NULL,
  `assigned_driver` varchar(20) DEFAULT NULL,
  `work_ticket` varchar(50) DEFAULT NULL,
  `work_ticket_status` enum('active','pending','completed') DEFAULT 'active',
  `in_out_status` enum('idle','out') DEFAULT 'idle',
  `latitude` decimal(10,7) DEFAULT NULL,
  `longitude` decimal(10,7) DEFAULT NULL,
  `location_accuracy` int(11) DEFAULT NULL,
  `location_source` varchar(20) DEFAULT NULL,
  `location_timestamp` datetime DEFAULT NULL,
  `image_path` varchar(500) DEFAULT NULL,
  `vehicle_photo_path` varchar(500) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `next_maintenance_date` date DEFAULT NULL,
  `current_odometer` int(11) DEFAULT 0,
  `is_geofence_violation` tinyint(1) DEFAULT 0,
  `last_geofence_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `vehicles`
--

INSERT INTO `vehicles` (`id`, `type`, `category`, `model`, `registration`, `year`, `fuel_type`, `fuel_capacity`, `fuel_level`, `status`, `unit`, `assigned_driver`, `work_ticket`, `work_ticket_status`, `in_out_status`, `latitude`, `longitude`, `location_accuracy`, `location_source`, `location_timestamp`, `image_path`, `vehicle_photo_path`, `created_at`, `updated_at`, `next_maintenance_date`, `current_odometer`, `is_geofence_violation`, `last_geofence_id`) VALUES
('A 2565', 'Light Vehicles', 'light', 'Defender', 'A 2570', '2019', 'diesel', 80, 100, 'active', '1st Battalion', 'D-2569', 'wt 12356', 'active', 'idle', 9.8278589, 80.2354470, 10, 'driver_gps', '2026-04-05 09:06:51', NULL, NULL, '2026-04-03 17:04:22', '2026-04-05 07:06:52', '2026-05-06', 2025, 1, 3),
('A4568', '', '', '', '', '2016', '', 30, 0, 'maintenance', '2nd Battalion', 'D-9995', 'SLA/2026/1', 'active', 'idle', 6.9483430, 79.8562750, 10, 'gps', '2026-04-04 04:37:49', NULL, NULL, '2026-04-04 04:37:49', '2026-04-05 07:04:41', '2026-05-01', 550, 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `whatsapp_messages`
--

CREATE TABLE `whatsapp_messages` (
  `id` varchar(20) NOT NULL,
  `type` varchar(30) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `recipient` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `drivers`
--
ALTER TABLE `drivers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `driver_vehicles`
--
ALTER TABLE `driver_vehicles`
  ADD PRIMARY KEY (`driver_id`,`vehicle_id`),
  ADD KEY `vehicle_id` (`vehicle_id`);

--
-- Indexes for table `fuel_stock`
--
ALTER TABLE `fuel_stock`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fuel_transactions`
--
ALTER TABLE `fuel_transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vehicle_id` (`vehicle_id`);

--
-- Indexes for table `geofences`
--
ALTER TABLE `geofences`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gps_devices`
--
ALTER TABLE `gps_devices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vehicle_id` (`vehicle_id`);

--
-- Indexes for table `gps_tracking`
--
ALTER TABLE `gps_tracking`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vehicle_id` (`vehicle_id`);

--
-- Indexes for table `movements`
--
ALTER TABLE `movements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vehicle_id` (`vehicle_id`),
  ADD KEY `driver_id` (`driver_id`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `service_records`
--
ALTER TABLE `service_records`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vehicle_id` (`vehicle_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `vehicles`
--
ALTER TABLE `vehicles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `registration` (`registration`);

--
-- Indexes for table `whatsapp_messages`
--
ALTER TABLE `whatsapp_messages`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `fuel_stock`
--
ALTER TABLE `fuel_stock`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `geofences`
--
ALTER TABLE `geofences`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `gps_tracking`
--
ALTER TABLE `gps_tracking`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=197;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `driver_vehicles`
--
ALTER TABLE `driver_vehicles`
  ADD CONSTRAINT `driver_vehicles_ibfk_1` FOREIGN KEY (`driver_id`) REFERENCES `drivers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `driver_vehicles_ibfk_2` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `fuel_transactions`
--
ALTER TABLE `fuel_transactions`
  ADD CONSTRAINT `fuel_transactions_ibfk_1` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `gps_devices`
--
ALTER TABLE `gps_devices`
  ADD CONSTRAINT `gps_devices_ibfk_1` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `gps_tracking`
--
ALTER TABLE `gps_tracking`
  ADD CONSTRAINT `gps_tracking_ibfk_1` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `movements`
--
ALTER TABLE `movements`
  ADD CONSTRAINT `movements_ibfk_1` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `movements_ibfk_2` FOREIGN KEY (`driver_id`) REFERENCES `drivers` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `service_records`
--
ALTER TABLE `service_records`
  ADD CONSTRAINT `service_records_ibfk_1` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
