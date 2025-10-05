-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: mysql-alexgargo78.alwaysdata.net
-- Generation Time: Oct 02, 2025 at 07:10 PM
-- Server version: 10.11.14-MariaDB
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `alexgargo78_banca-turing`
--

-- --------------------------------------------------------

--
-- Table structure for table `cliente`
--

CREATE TABLE `cliente` (
  `dni` varchar(10) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `direccion` varchar(200) NOT NULL,
  `telefono` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish2_ci;

--
-- Dumping data for table `cliente`
--

INSERT INTO `cliente` (`dni`, `nombre`, `direccion`, `telefono`) VALUES
('45678', 'Mota', 'Calle Falsa, 123', '555 444333'),
('65767', 'Pepito Lupiañez', 'Alhaurín', '867867867'),
('7234242358', 'pererepe', 'Calle Verdadera, 98', '555 7878'),
('748249079', 'pepe', 'rio', '650981852'),
('74824907L', 'pepe', 'rio', '650981852'),
('74824908L', 'pepe', 'rio', '650981853'),
('789787', 'Pepe', 'Calle Antequera', '555 8985'),
('873', 'Maria Sol', 'Calle Flora', '555 123456');

-- --------------------------------------------------------

--
-- Table structure for table `empleado`
--

CREATE TABLE `empleado` (
  `dni` varchar(10) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `cargo` varchar(30) NOT NULL,
  `sueldo` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish2_ci;

--
-- Dumping data for table `empleado`
--

INSERT INTO `empleado` (`dni`, `nombre`, `cargo`, `sueldo`) VALUES
('123456', 'Romualdo Fernández', 'director', 2400),
('13579', 'Saturnino Peláez', 'administrativo', 900);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cliente`
--
ALTER TABLE `cliente`
  ADD PRIMARY KEY (`dni`);

--
-- Indexes for table `empleado`
--
ALTER TABLE `empleado`
  ADD PRIMARY KEY (`dni`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
