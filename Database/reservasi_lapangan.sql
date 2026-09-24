-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Sep 24, 2026 at 12:23 AM
-- Server version: 8.0.30
-- PHP Version: 8.5.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `reservasi_lapangan`
--

-- --------------------------------------------------------

--
-- Table structure for table `lapangan`
--

CREATE TABLE `lapangan` (
  `id` int NOT NULL,
  `nama_lapangan` varchar(100) NOT NULL,
  `jenis` varchar(50) NOT NULL,
  `harga_per_jam` int NOT NULL,
  `status` enum('Tersedia','Maintenance') DEFAULT 'Tersedia'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `lapangan`
--

INSERT INTO `lapangan` (`id`, `nama_lapangan`, `jenis`, `harga_per_jam`, `status`) VALUES
(1, 'Goa ria futsal', 'futsal', 100000, 'Tersedia'),
(2, 'Paggo', 'Badminton', 25000, 'Tersedia'),
(3, 'Ziv', 'Badminton', 30000, 'Tersedia');

-- --------------------------------------------------------

--
-- Table structure for table `reservasi`
--

CREATE TABLE `reservasi` (
  `id` int NOT NULL,
  `lapangan_id` int NOT NULL,
  `nama_pemesan` varchar(100) NOT NULL,
  `tanggal` date NOT NULL,
  `jam_mulai` time NOT NULL,
  `jam_selesai` time NOT NULL,
  `status` enum('Pending','Dikonfirmasi','Selesai','Dibatalkan') DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `reservasi`
--

INSERT INTO `reservasi` (`id`, `lapangan_id`, `nama_pemesan`, `tanggal`, `jam_mulai`, `jam_selesai`, `status`) VALUES
(1, 1, 'Kiddo', '2026-12-23', '10:00:00', '14:00:00', 'Dikonfirmasi'),
(2, 3, 'Bakekok', '2026-09-04', '13:00:00', '14:30:00', 'Pending'),
(3, 2, 'Pais', '2026-09-03', '12:30:00', '14:30:00', 'Pending'),
(4, 3, 'Sukma', '2026-09-04', '15:00:00', '16:00:00', 'Pending');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `lapangan`
--
ALTER TABLE `lapangan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reservasi`
--
ALTER TABLE `reservasi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lapangan_id` (`lapangan_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `lapangan`
--
ALTER TABLE `lapangan`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `reservasi`
--
ALTER TABLE `reservasi`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `reservasi`
--
ALTER TABLE `reservasi`
  ADD CONSTRAINT `reservasi_ibfk_1` FOREIGN KEY (`lapangan_id`) REFERENCES `lapangan` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
