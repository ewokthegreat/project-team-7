-- phpMyAdmin SQL Dump
-- version 4.0.10.7
-- http://www.phpmyadmin.net
--
-- Host: localhost:3306
-- Generation Time: Apr 29, 2016 at 10:10 PM
-- Server version: 5.5.48-MariaDB
-- PHP Version: 5.4.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `ewoktheg_spider`
--

-- --------------------------------------------------------

--
-- Table structure for table `scan`
--

DROP TABLE IF EXISTS `scan`;
CREATE TABLE IF NOT EXISTS `scan` (
  `scanID` varchar(100) NOT NULL,
  `applicantID` varchar(100) NOT NULL,
  `score` int(4) NOT NULL,
  `date` date NOT NULL,
  `path` varchar(200) NOT NULL,
  PRIMARY KEY (`scanID`),
  UNIQUE KEY `scanID` (`scanID`),
  KEY `applicantID` (`applicantID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `scan`
--
ALTER TABLE `scan`
ADD CONSTRAINT `scan_ibfk_1` FOREIGN KEY (`applicantID`) REFERENCES `applicant` (`applicantID`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
