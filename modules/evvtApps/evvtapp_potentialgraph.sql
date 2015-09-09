-- phpMyAdmin SQL Dump
-- version 3.2.2.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 18, 2012 at 10:55 AM
-- Server version: 5.1.61
-- PHP Version: 5.3.3-1ubuntu9.10

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `conti`
--

-- --------------------------------------------------------

--
-- Table structure for table `evvtapp_potentialgraph`
--

CREATE TABLE IF NOT EXISTS `evvtapp_potentialgraph` (
  `vtapppgid` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `dyndate` varchar(20) NOT NULL,
  `startdate` date NOT NULL,
  `enddate` date NOT NULL,
  `sstage` varchar(250) NOT NULL,
  `users` varchar(250) NOT NULL,
  PRIMARY KEY (`vtapppgid`),
  UNIQUE KEY `userid` (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `evvtapp_potentialgraph`
--

