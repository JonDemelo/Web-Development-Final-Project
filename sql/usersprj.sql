-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Dec 01, 2012 at 12:47 AM
-- Server version: 5.5.28-1~dotdeb.0
-- PHP Version: 5.3.18-1~dotdeb.0

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `jdemelo89903_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `usersprj`
--

CREATE TABLE IF NOT EXISTS `usersprj` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(30) NOT NULL,
  `birthday` varchar(20) NOT NULL,
  `ip` varchar(20) NOT NULL,
  `logindate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=80 ;

--
-- Dumping data for table `usersprj`
--

INSERT INTO `usersprj` (`id`, `email`, `birthday`, `ip`, `logindate`) VALUES
(76, 'a@a.a', '01-12-2012', '174.94.30.85', '2012-12-01 08:36:06'),
(77, 't@t.t', '12-08-2012', '174.94.30.85', '2012-12-01 08:36:41'),
(78, 'asdas@asdad.ca', '01-10-2012', '174.94.30.85', '2012-12-01 08:37:10'),
(79, 't@t.t', '01-10-2012', '174.94.30.85', '2012-12-01 08:39:49');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
