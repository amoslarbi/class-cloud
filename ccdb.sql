-- phpMyAdmin SQL Dump
-- version 4.5.2
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Dec 19, 2017 at 07:42 PM
-- Server version: 5.7.9
-- PHP Version: 5.6.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ccdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
CREATE TABLE IF NOT EXISTS `admin` (
  `aid` int(11) NOT NULL AUTO_INCREMENT,
  `fname` varchar(80) NOT NULL,
  `mname` varchar(80) DEFAULT NULL,
  `lname` varchar(80) DEFAULT NULL,
  `gender` enum('m','f') NOT NULL,
  `address` varchar(80) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `avatar` varchar(80) DEFAULT NULL,
  `added` datetime NOT NULL,
  PRIMARY KEY (`aid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`aid`, `fname`, `mname`, `lname`, `gender`, `address`, `phone`, `avatar`, `added`) VALUES
(1, 'Admin', NULL, NULL, 'm', 'Galloway Apt. A31, Koforidua', '0207150717', NULL, '2017-03-01 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `admin_log`
--

DROP TABLE IF EXISTS `admin_log`;
CREATE TABLE IF NOT EXISTS `admin_log` (
  `alid` int(11) NOT NULL AUTO_INCREMENT,
  `aid` int(11) NOT NULL,
  `action` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `tstamp` datetime NOT NULL,
  PRIMARY KEY (`alid`),
  KEY `aid` (`aid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `appuser`
--

DROP TABLE IF EXISTS `appuser`;
CREATE TABLE IF NOT EXISTS `appuser` (
  `apid` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `email` varchar(80) NOT NULL,
  `password` varchar(80) NOT NULL,
  `rlid` int(11) NOT NULL,
  PRIMARY KEY (`apid`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `appuser`
--

INSERT INTO `appuser` (`apid`, `uid`, `email`, `password`, `rlid`) VALUES
(1, 1, 'reked@live.com', 'e10adc3949ba59abbe56e057f20f883e', 2),
(2, 1, 'asarebright81@gmail.com', '74db48aac5821ee7ecd720d16e92af1d', 1),
(3, 2, 'rocher.e7@gmail.com', '4167fba312e9678b67aad162623b520e', 2),
(4, 2, 'rek@cc.com', 'e10adc3949ba59abbe56e057f20f883e', 1),
(9, 1, 'admin@cc.com', 'e10adc3949ba59abbe56e057f20f883e', 3),
(10, 6, 'jeff@cc.com', '0a5a6b6d6a5636c43fa8b49dc779dfea', 2),
(11, 7, 'dominic@cc.com', 'e10adc3949ba59abbe56e057f20f883e', 2),
(12, 8, 'ama@cc.com', '3dd5e39f02b87feb4c4ce7eb92fa33b4', 2),
(13, 9, 'kris@cc.com', '6fff79b3979e58b52ffedacfaf83a6ba', 2),
(14, 10, 'jack@cc.com', 'e10adc3949ba59abbe56e057f20f883e', 2);

-- --------------------------------------------------------

--
-- Table structure for table `document`
--

DROP TABLE IF EXISTS `document`;
CREATE TABLE IF NOT EXISTS `document` (
  `dcid` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `text` text NOT NULL,
  `added` datetime NOT NULL,
  PRIMARY KEY (`dcid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `games`
--

DROP TABLE IF EXISTS `games`;
CREATE TABLE IF NOT EXISTS `games` (
  `gid` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `description` text,
  `path` varchar(80) NOT NULL,
  `added` datetime NOT NULL,
  PRIMARY KEY (`gid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `gradebook`
--

DROP TABLE IF EXISTS `gradebook`;
CREATE TABLE IF NOT EXISTS `gradebook` (
  `gid` int(11) NOT NULL AUTO_INCREMENT,
  `grade` varchar(3) NOT NULL,
  `maxscore` decimal(10,2) NOT NULL,
  `minscore` decimal(10,2) NOT NULL,
  PRIMARY KEY (`gid`),
  UNIQUE KEY `grade` (`grade`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `gradebook`
--

INSERT INTO `gradebook` (`gid`, `grade`, `maxscore`, `minscore`) VALUES
(2, 'A+', '100.00', '90.00'),
(3, 'A', '89.99', '80.00'),
(4, 'B+', '79.99', '75.00'),
(5, 'B', '74.99', '70.00'),
(6, 'C+', '69.99', '65.00'),
(7, 'C', '64.99', '60.00'),
(8, 'C-', '59.99', '55.00'),
(9, 'D', '54.99', '50.00'),
(10, 'E', '49.99', '45.00'),
(11, 'F', '44.99', '0.00');

-- --------------------------------------------------------

--
-- Table structure for table `image`
--

DROP TABLE IF EXISTS `image`;
CREATE TABLE IF NOT EXISTS `image` (
  `imid` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `description` text,
  `path` varchar(80) NOT NULL,
  `added` datetime NOT NULL,
  PRIMARY KEY (`imid`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `instructor`
--

DROP TABLE IF EXISTS `instructor`;
CREATE TABLE IF NOT EXISTS `instructor` (
  `inst_id` int(11) NOT NULL AUTO_INCREMENT,
  `fname` varchar(50) NOT NULL,
  `lname` varchar(50) NOT NULL,
  `mname` varchar(50) DEFAULT NULL,
  `address` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `gender` enum('m','f') NOT NULL,
  `avatar` varchar(80) DEFAULT NULL,
  `dob` date NOT NULL,
  `added` datetime NOT NULL,
  PRIMARY KEY (`inst_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `instructor`
--

INSERT INTO `instructor` (`inst_id`, `fname`, `lname`, `mname`, `address`, `phone`, `gender`, `avatar`, `dob`, `added`) VALUES
(1, 'Bright', 'Asare', '', NULL, NULL, 'm', NULL, '1970-01-01', '2017-02-22 20:41:57'),
(2, 'Kofi', 'Asare', '', NULL, NULL, 'm', NULL, '1980-12-03', '2017-03-13 08:38:04');

-- --------------------------------------------------------

--
-- Table structure for table `lesson_structure`
--

DROP TABLE IF EXISTS `lesson_structure`;
CREATE TABLE IF NOT EXISTS `lesson_structure` (
  `lstid` int(11) NOT NULL AUTO_INCREMENT,
  `slcid` int(11) NOT NULL,
  `mid` int(11) NOT NULL,
  `mnum` int(11) NOT NULL,
  `mtype` enum('q','g','v','d','i','a') NOT NULL,
  PRIMARY KEY (`lstid`),
  KEY `lsid` (`slcid`),
  KEY `mid` (`mid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `level`
--

DROP TABLE IF EXISTS `level`;
CREATE TABLE IF NOT EXISTS `level` (
  `lid` int(11) NOT NULL AUTO_INCREMENT,
  `level_name` varchar(50) NOT NULL,
  PRIMARY KEY (`lid`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `level`
--

INSERT INTO `level` (`lid`, `level_name`) VALUES
(1, 'JHS 1'),
(2, 'JHS 2'),
(3, 'JHS 3'),
(4, 'SHS 1'),
(5, 'SHS 2'),
(6, 'SHS 3'),
(7, 'SHS 4');

-- --------------------------------------------------------

--
-- Table structure for table `qanswers`
--

DROP TABLE IF EXISTS `qanswers`;
CREATE TABLE IF NOT EXISTS `qanswers` (
  `qaid` int(11) NOT NULL AUTO_INCREMENT,
  `qqid` int(11) NOT NULL,
  `options` text NOT NULL,
  PRIMARY KEY (`qaid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `queries`
--

DROP TABLE IF EXISTS `queries`;
CREATE TABLE IF NOT EXISTS `queries` (
  `qqid` int(11) NOT NULL AUTO_INCREMENT,
  `qid` int(11) NOT NULL,
  `question` text NOT NULL,
  `answer` text NOT NULL,
  `attach` varchar(100) DEFAULT NULL,
  `attach_type` enum('i','a') DEFAULT NULL,
  PRIMARY KEY (`qqid`),
  KEY `qid` (`qid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

DROP TABLE IF EXISTS `questions`;
CREATE TABLE IF NOT EXISTS `questions` (
  `qid` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(80) NOT NULL,
  `sid` int(11) NOT NULL,
  `quizqnum` int(11) NOT NULL,
  `added` datetime NOT NULL,
  PRIMARY KEY (`qid`),
  KEY `lid` (`sid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

DROP TABLE IF EXISTS `student`;
CREATE TABLE IF NOT EXISTS `student` (
  `stid` int(11) NOT NULL AUTO_INCREMENT,
  `fname` varchar(50) NOT NULL,
  `lname` varchar(50) NOT NULL,
  `mname` varchar(50) DEFAULT NULL,
  `lid` int(11) DEFAULT NULL,
  `inst_id` int(11) DEFAULT NULL,
  `gender` enum('m','f') NOT NULL,
  `dob` date NOT NULL,
  `avatar` varchar(80) DEFAULT NULL,
  `added` datetime NOT NULL,
  PRIMARY KEY (`stid`),
  KEY `lid` (`lid`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`stid`, `fname`, `lname`, `mname`, `lid`, `inst_id`, `gender`, `dob`, `avatar`, `added`) VALUES
(1, 'Roger', 'Edwin', '', NULL, NULL, 'm', '1970-01-01', NULL, '2017-02-22 19:48:01'),
(2, 'Mike', 'Lawer', '', NULL, NULL, 'm', '1970-01-01', NULL, '2017-02-22 21:10:34'),
(6, 'jeff', 'Owusu', '', 4, 2, 'm', '1970-01-01', NULL, '2017-03-16 14:40:29'),
(7, 'Dominic', 'Damoah', '', 5, 2, 'm', '1970-01-01', NULL, '2017-03-21 21:45:40'),
(8, 'Ama', 'Asare', '', 3, 2, 'f', '1970-01-01', NULL, '2017-03-22 22:05:44'),
(9, 'Kris', 'Edwin', '', 5, 2, 'm', '1970-01-01', NULL, '2017-03-31 00:12:59'),
(10, 'Jack', 'Mensah', '', NULL, NULL, 'm', '1970-01-01', NULL, '2017-12-08 07:14:40');

-- --------------------------------------------------------

--
-- Table structure for table `student_lessons`
--

DROP TABLE IF EXISTS `student_lessons`;
CREATE TABLE IF NOT EXISTS `student_lessons` (
  `stlid` int(11) NOT NULL AUTO_INCREMENT,
  `stid` int(11) NOT NULL,
  `slcid` int(11) NOT NULL,
  `score` decimal(10,2) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`stlid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `student_progress`
--

DROP TABLE IF EXISTS `student_progress`;
CREATE TABLE IF NOT EXISTS `student_progress` (
  `spid` int(11) NOT NULL AUTO_INCREMENT,
  `stid` int(11) NOT NULL,
  `slcid` int(11) NOT NULL,
  `progress` int(11) NOT NULL,
  `gradeid` int(11) DEFAULT NULL,
  `tstamp` datetime NOT NULL,
  PRIMARY KEY (`spid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `student_queries`
--

DROP TABLE IF EXISTS `student_queries`;
CREATE TABLE IF NOT EXISTS `student_queries` (
  `stqid` int(11) NOT NULL AUTO_INCREMENT,
  `stid` int(11) NOT NULL,
  `qqid` int(11) NOT NULL,
  `answer` text NOT NULL,
  `slcid` int(11) NOT NULL,
  `tstamp` datetime NOT NULL,
  PRIMARY KEY (`stqid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `student_subject_level`
--

DROP TABLE IF EXISTS `student_subject_level`;
CREATE TABLE IF NOT EXISTS `student_subject_level` (
  `sslid` int(11) NOT NULL AUTO_INCREMENT,
  `stid` int(11) NOT NULL,
  `slid` int(11) NOT NULL,
  `added` datetime NOT NULL,
  PRIMARY KEY (`sslid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `subject`
--

DROP TABLE IF EXISTS `subject`;
CREATE TABLE IF NOT EXISTS `subject` (
  `sid` int(11) NOT NULL AUTO_INCREMENT,
  `subject` varchar(80) NOT NULL,
  `icon` varchar(80) DEFAULT NULL,
  `added` datetime NOT NULL,
  PRIMARY KEY (`sid`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `subject`
--

INSERT INTO `subject` (`sid`, `subject`, `icon`, `added`) VALUES
(1, 'English', 'english.png', '2017-03-20 00:00:00'),
(2, 'Math', 'math.png', '2017-03-20 00:00:00'),
(3, 'Science', 'science.png', '2017-03-20 00:00:00'),
(4, 'Economics', 'cc28ef1e93a0ec8dd.jpg', '2017-03-22 17:39:32'),
(5, 'ICT', 'ccfc1b6b78413b86b.jpg', '2017-03-22 19:09:09'),
(6, 'Pre-Voc', 'cce0091f28a6a79e6.png', '2017-03-31 00:16:32');

-- --------------------------------------------------------

--
-- Table structure for table `subject_level`
--

DROP TABLE IF EXISTS `subject_level`;
CREATE TABLE IF NOT EXISTS `subject_level` (
  `slid` int(11) NOT NULL AUTO_INCREMENT,
  `sid` int(11) NOT NULL,
  `lid` int(11) NOT NULL,
  `added` datetime NOT NULL,
  PRIMARY KEY (`slid`),
  KEY `sid` (`sid`),
  KEY `lid` (`lid`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `subject_level`
--

INSERT INTO `subject_level` (`slid`, `sid`, `lid`, `added`) VALUES
(1, 1, 1, '2017-03-20 00:00:00'),
(2, 1, 2, '2017-03-20 00:00:00'),
(3, 2, 5, '2017-03-20 00:00:00'),
(4, 3, 6, '2017-03-20 00:00:00'),
(7, 5, 2, '2017-03-22 19:09:25');

-- --------------------------------------------------------

--
-- Table structure for table `subject_level_curriculum`
--

DROP TABLE IF EXISTS `subject_level_curriculum`;
CREATE TABLE IF NOT EXISTS `subject_level_curriculum` (
  `slcid` int(11) NOT NULL AUTO_INCREMENT,
  `slid` int(11) NOT NULL,
  `lesson_name` varchar(100) NOT NULL,
  `lesson_number` int(11) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`slcid`),
  KEY `slid` (`slid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `temp_student_queries`
--

DROP TABLE IF EXISTS `temp_student_queries`;
CREATE TABLE IF NOT EXISTS `temp_student_queries` (
  `stqid` int(11) NOT NULL AUTO_INCREMENT,
  `stid` int(11) NOT NULL,
  `qqid` int(11) NOT NULL,
  `answer` text NOT NULL,
  `slcid` int(11) NOT NULL,
  `tstamp` datetime NOT NULL,
  PRIMARY KEY (`stqid`),
  KEY `stid` (`stid`),
  KEY `qqid` (`qqid`),
  KEY `lid` (`slcid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `videonaudio`
--

DROP TABLE IF EXISTS `videonaudio`;
CREATE TABLE IF NOT EXISTS `videonaudio` (
  `vaid` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `description` text,
  `path` varchar(80) NOT NULL,
  `file_type` enum('a','v') NOT NULL,
  `added` datetime NOT NULL,
  PRIMARY KEY (`vaid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
