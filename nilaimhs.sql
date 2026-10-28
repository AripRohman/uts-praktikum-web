-- phpMyAdmin SQL Dump
-- version 6.0.0-dev+20261006.e3e877e8b4
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Oct 28, 2026 at 01:27 AM
-- Server version: 8.4.3
-- PHP Version: 8.3.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db5d`
--

-- --------------------------------------------------------

--
-- Table structure for table `nilaimhs`
--
CREATE TABLE `nilaimhs` (
  `nim` varchar(20) NOT NULL,
  `nama_mhs` varchar(100) NOT NULL,
  `nilai_akhir` decimal(5,2) NOT NULL,
  `nilai_huruf` varchar(2) NOT NULL,
  `predikat` varchar(20) NOT NULL,
  `reg_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `gambar` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `nilaimhs`
--

INSERT INTO `nilaimhs` (`nim`, `nama_mhs`, `nilai_akhir`, `nilai_huruf`, `predikat`, `reg_date`, `gambar`) VALUES
('32014548', 'Padhil', 25.00, 'E', 'Sangat Buruk', '2026-10-27 08:45:35', 'gambar/ea23bb6291eaeb0d9e9da17d672bb0f1.png'),
('32015210', 'sisi', 23.00, 'E', 'Sangat Buruk', '2026-10-27 13:09:58', 'gambar/5c830af1a4d6def62415401e73f02907.png'),
('320241500798', 'jerry', 80.00, 'B', 'Baik', '2026-10-27 08:33:43', 'gambar/Screenshot 2024-10-16 110038.png'),
('3202415008', 'bima', 65.00, 'C', 'Cukup', '2026-10-27 08:31:42', 'gambar/Screenshot 2024-10-17 183626.png'),
('32024155007', 'jack', 80.00, 'B', 'Baik', '2026-10-27 08:32:09', 'gambar/Screenshot 2024-11-04 093835.png'),
('3202415600', 'Ikbal', 75.00, 'B', 'Baik', '2026-10-27 08:30:35', 'gambar/hostpot.png'),
('320241568', 'PUSTA', 50.00, 'D', 'Buruk', '2026-10-27 08:44:49', 'gambar/030692a4ac74fa51fc8b33a96cfc7e47.png'),
('3202416006', 'Arip Rohman', 95.00, 'A', 'Sangat Baik', '2026-10-27 08:40:06', 'gambar/0f0471771c3c6c230167a25c47dd496b.jpg'),
('32024165005', 'surya', 52.00, 'C', 'Cukup', '2026-10-27 08:32:30', 'gambar/Screenshot 2024-10-14 225536.png'),
('3230142352', 'hijir', 65.00, 'C', 'Cukup', '2026-10-27 08:44:25', 'gambar/c6af80b3b168e7a5a4b6e447f7ede4fb.png'),
('3241506658', 'Diana', 50.00, 'D', 'Buruk', '2026-10-27 08:31:18', 'gambar/Screenshot 2024-10-14 152702.png'),
('325512546', 'Rohman Arip', 90.00, 'A', 'Sangat Baik', '2026-10-27 08:43:37', 'gambar/65f85a8a10d0be2e27f8c12556389457.png');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `nilaimhs`
--
ALTER TABLE `nilaimhs`
  ADD PRIMARY KEY (`nim`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
