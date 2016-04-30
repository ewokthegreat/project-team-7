-- phpMyAdmin SQL Dump
-- version 4.0.10.7
-- http://www.phpmyadmin.net
--
-- Host: localhost:3306
-- Generation Time: Apr 29, 2016 at 10:09 PM
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
-- Table structure for table `applicant`
--

DROP TABLE IF EXISTS `applicant`;
CREATE TABLE IF NOT EXISTS `applicant` (
  `applicantID` varchar(500) NOT NULL,
  `fbAuthToken` varchar(500) NOT NULL COMMENT 'Facebook oAuth Token',
  `firstName` varchar(25) NOT NULL,
  `lastName` varchar(25) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `profileLink` varchar(500) NOT NULL,
  `password` varchar(20) NOT NULL,
  `isAdmin` tinyint(1) NOT NULL DEFAULT '0',
  `profilePicture` varchar(500) NOT NULL,
  PRIMARY KEY (`applicantID`),
  UNIQUE KEY `applicantID` (`applicantID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
