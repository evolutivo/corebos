-- phpMyAdmin SQL Dump
-- version 3.2.2.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 18, 2012 at 10:51 AM
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
-- Table structure for table `vtiger_evvtapps`
--

CREATE TABLE IF NOT EXISTS `vtiger_evvtapps` (
  `evvtappsid` int(11) NOT NULL AUTO_INCREMENT,
  `appname` varchar(64) NOT NULL,
  `installdate` datetime NOT NULL,
  PRIMARY KEY (`evvtappsid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `vtiger_evvtapps`
--

INSERT INTO `vtiger_evvtapps` (`evvtappsid`, `appname`, `installdate`) VALUES
(1, 'Trash', '2011-12-16 00:18:00'),
(2, 'Configuration', '2011-12-16 00:18:00'),
(3, 'vtApps Store', '2011-12-16 00:23:00'),
(5, 'vtDemoGraph1', '2011-12-21 10:49:00'),
(6, 'vtDemoGraph2', '2011-12-21 10:50:00'),
(7, 'vtAppcomTSolucioListView', '2012-02-14 19:12:07'),
(8, 'customdbe60075199e662172151e81b87f6dd4', '2012-09-26 13:43:07');
