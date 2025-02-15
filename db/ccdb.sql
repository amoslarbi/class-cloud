-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Feb 15, 2025 at 12:44 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
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

CREATE TABLE `admin` (
  `aid` int(11) NOT NULL,
  `fname` varchar(80) NOT NULL,
  `mname` varchar(80) DEFAULT NULL,
  `lname` varchar(80) DEFAULT NULL,
  `gender` enum('m','f') NOT NULL,
  `address` varchar(80) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `avatar` varchar(80) DEFAULT NULL,
  `added` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`aid`, `fname`, `mname`, `lname`, `gender`, `address`, `phone`, `avatar`, `added`) VALUES
(1, 'Admin', NULL, NULL, 'm', 'Frankfurter Alle', '0207150717', NULL, '2025-01-02 13:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `admin_log`
--

CREATE TABLE `admin_log` (
  `alid` int(11) NOT NULL,
  `aid` int(11) NOT NULL,
  `action` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `tstamp` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `appuser`
--

CREATE TABLE `appuser` (
  `apid` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `email` varchar(80) NOT NULL,
  `password` varchar(80) NOT NULL,
  `rlid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `appuser`
--

INSERT INTO `appuser` (`apid`, `uid`, `email`, `password`, `rlid`) VALUES
(9, 1, 'admin@cc.com', 'e10adc3949ba59abbe56e057f20f883e', 3),
(15, 3, 'testparent1@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', 1),
(16, 11, 'teststudent1@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', 2),
(17, 12, 'teststudent2@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', 2);

-- --------------------------------------------------------

--
-- Table structure for table `document`
--

CREATE TABLE `document` (
  `dcid` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `text` text NOT NULL,
  `added` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `document`
--

INSERT INTO `document` (`dcid`, `title`, `text`, `added`) VALUES
(1, 'Login Page', '<p>Design a Login Page</p>', '2025-01-22 18:02:11');

-- --------------------------------------------------------

--
-- Table structure for table `games`
--

CREATE TABLE `games` (
  `gid` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `path` varchar(80) NOT NULL,
  `added` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gradebook`
--

CREATE TABLE `gradebook` (
  `gid` int(11) NOT NULL,
  `grade` varchar(3) NOT NULL,
  `maxscore` decimal(10,2) NOT NULL,
  `minscore` decimal(10,2) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

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

CREATE TABLE `image` (
  `imid` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `path` varchar(80) NOT NULL,
  `added` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `instructor`
--

CREATE TABLE `instructor` (
  `inst_id` int(11) NOT NULL,
  `fname` varchar(50) NOT NULL,
  `lname` varchar(50) NOT NULL,
  `mname` varchar(50) DEFAULT NULL,
  `address` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `gender` enum('m','f') NOT NULL,
  `avatar` varchar(80) DEFAULT NULL,
  `dob` date NOT NULL,
  `added` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `instructor`
--

INSERT INTO `instructor` (`inst_id`, `fname`, `lname`, `mname`, `address`, `phone`, `gender`, `avatar`, `dob`, `added`) VALUES
(3, 'Test', 'Parent 1', NULL, NULL, NULL, 'm', NULL, '2024-10-12', '2024-12-07 16:23:43');

-- --------------------------------------------------------

--
-- Table structure for table `lesson_structure`
--

CREATE TABLE `lesson_structure` (
  `lstid` int(11) NOT NULL,
  `slcid` int(11) NOT NULL,
  `mid` int(11) NOT NULL,
  `mnum` int(11) NOT NULL,
  `mtype` enum('q','g','v','d','i','a') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `lesson_structure`
--

INSERT INTO `lesson_structure` (`lstid`, `slcid`, `mid`, `mnum`, `mtype`) VALUES
(1, 4, 1, 33321, 'd');

-- --------------------------------------------------------

--
-- Table structure for table `level`
--

CREATE TABLE `level` (
  `lid` int(11) NOT NULL,
  `level_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `level`
--

INSERT INTO `level` (`lid`, `level_name`) VALUES
(1, 'Level 1'),
(2, 'Level 2'),
(3, 'Level 3'),
(4, 'Level 4'),
(5, 'Level 5');

-- --------------------------------------------------------

--
-- Table structure for table `qanswers`
--

CREATE TABLE `qanswers` (
  `qaid` int(11) NOT NULL,
  `qqid` int(11) NOT NULL,
  `options` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `queries`
--

CREATE TABLE `queries` (
  `qqid` int(11) NOT NULL,
  `qid` int(11) NOT NULL,
  `question` text NOT NULL,
  `answer` text NOT NULL,
  `attach` varchar(100) DEFAULT NULL,
  `attach_type` enum('i','a') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `qid` int(11) NOT NULL,
  `title` varchar(80) NOT NULL,
  `sid` int(11) NOT NULL,
  `quizqnum` int(11) NOT NULL,
  `added` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE `student` (
  `stid` int(11) NOT NULL,
  `fname` varchar(50) NOT NULL,
  `lname` varchar(50) NOT NULL,
  `mname` varchar(50) DEFAULT NULL,
  `lid` int(11) DEFAULT NULL,
  `inst_id` int(11) DEFAULT NULL,
  `gender` enum('m','f') NOT NULL,
  `dob` date NOT NULL,
  `avatar` varchar(80) DEFAULT NULL,
  `added` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`stid`, `fname`, `lname`, `mname`, `lid`, `inst_id`, `gender`, `dob`, `avatar`, `added`) VALUES
(11, 'Test', 'Student 1', NULL, 1, 3, 'm', '2025-01-01', NULL, '2025-01-22 11:13:49'),
(12, 'Test', 'Student 2', '', 2, 1, 'm', '2024-01-12', NULL, '2025-01-22 12:49:08');

-- --------------------------------------------------------

--
-- Table structure for table `student_lessons`
--

CREATE TABLE `student_lessons` (
  `stlid` int(11) NOT NULL,
  `stid` int(11) NOT NULL,
  `slcid` int(11) NOT NULL,
  `score` decimal(10,2) NOT NULL,
  `created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `student_progress`
--

CREATE TABLE `student_progress` (
  `spid` int(11) NOT NULL,
  `stid` int(11) NOT NULL,
  `slcid` int(11) NOT NULL,
  `progress` int(11) NOT NULL,
  `gradeid` int(11) DEFAULT NULL,
  `tstamp` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `student_progress`
--

INSERT INTO `student_progress` (`spid`, `stid`, `slcid`, `progress`, `gradeid`, `tstamp`) VALUES
(1, 8, 4, 100, NULL, '2025-01-22 18:02:31'),
(2, 11, 4, 100, NULL, '2025-01-23 11:58:08');

-- --------------------------------------------------------

--
-- Table structure for table `student_queries`
--

CREATE TABLE `student_queries` (
  `stqid` int(11) NOT NULL,
  `stid` int(11) NOT NULL,
  `qqid` int(11) NOT NULL,
  `answer` text NOT NULL,
  `slcid` int(11) NOT NULL,
  `tstamp` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `student_subject_level`
--

CREATE TABLE `student_subject_level` (
  `sslid` int(11) NOT NULL,
  `stid` int(11) NOT NULL,
  `slid` int(11) NOT NULL,
  `added` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `student_subject_level`
--

INSERT INTO `student_subject_level` (`sslid`, `stid`, `slid`, `added`) VALUES
(1, 1, 1, '2024-12-07 16:32:43'),
(2, 11, 1, '2025-01-22 11:14:46'),
(3, 11, 2, '2025-01-22 11:14:46'),
(4, 11, 7, '2025-01-22 11:14:46'),
(5, 12, 3, '2025-01-22 16:03:01'),
(6, 8, 1, '2025-01-22 17:55:54'),
(7, 8, 2, '2025-01-22 17:56:23');

-- --------------------------------------------------------

--
-- Table structure for table `subject`
--

CREATE TABLE `subject` (
  `sid` int(11) NOT NULL,
  `subject` varchar(80) NOT NULL,
  `icon` varchar(80) DEFAULT NULL,
  `added` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `subject`
--

INSERT INTO `subject` (`sid`, `subject`, `icon`, `added`) VALUES
(1, 'English', 'english.png', '2025-01-05 13:00:00'),
(2, 'Math', 'math.png', '2025-01-05 13:11:00'),
(3, 'Science', 'science.png', '2025-01-05 13:15:00'),
(4, 'Economics', 'cc28ef1e93a0ec8dd.jpg', '2025-01-05 17:39:32'),
(5, 'IT', 'ccfc1b6b78413b86b.jpg', '2025-01-06 19:09:09'),
(6, 'History', 'cce0091f28a6a79e6.png', '2025-01-07 00:16:32');

-- --------------------------------------------------------

--
-- Table structure for table `subject_level`
--

CREATE TABLE `subject_level` (
  `slid` int(11) NOT NULL,
  `sid` int(11) NOT NULL,
  `lid` int(11) NOT NULL,
  `added` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `subject_level`
--

INSERT INTO `subject_level` (`slid`, `sid`, `lid`, `added`) VALUES
(1, 1, 1, '2025-01-01 00:00:00'),
(2, 1, 2, '2025-01-01 00:00:00'),
(3, 2, 5, '2025-01-01 00:00:00'),
(4, 3, 6, '2025-01-01 20:00:00'),
(7, 5, 2, '2025-01-03 19:09:25');

-- --------------------------------------------------------

--
-- Table structure for table `subject_level_curriculum`
--

CREATE TABLE `subject_level_curriculum` (
  `slcid` int(11) NOT NULL,
  `slid` int(11) NOT NULL,
  `lesson_name` varchar(100) NOT NULL,
  `lesson_number` int(11) NOT NULL,
  `created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `subject_level_curriculum`
--

INSERT INTO `subject_level_curriculum` (`slcid`, `slid`, `lesson_name`, `lesson_number`, `created`) VALUES
(1, 1, 'Test Lesson', 12345678, '2024-12-07 16:35:56'),
(2, 1, 'dadasda', 123, '2025-01-22 17:58:41'),
(4, 2, 'dadasda', 333, '2025-01-22 18:01:36');

-- --------------------------------------------------------

--
-- Table structure for table `temp_student_queries`
--

CREATE TABLE `temp_student_queries` (
  `stqid` int(11) NOT NULL,
  `stid` int(11) NOT NULL,
  `qqid` int(11) NOT NULL,
  `answer` text NOT NULL,
  `slcid` int(11) NOT NULL,
  `tstamp` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `videonaudio`
--

CREATE TABLE `videonaudio` (
  `vaid` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `path` varchar(80) NOT NULL,
  `file_type` enum('a','v') NOT NULL,
  `added` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`aid`);

--
-- Indexes for table `admin_log`
--
ALTER TABLE `admin_log`
  ADD PRIMARY KEY (`alid`),
  ADD KEY `aid` (`aid`);

--
-- Indexes for table `appuser`
--
ALTER TABLE `appuser`
  ADD PRIMARY KEY (`apid`),
  ADD KEY `uid` (`uid`);

--
-- Indexes for table `document`
--
ALTER TABLE `document`
  ADD PRIMARY KEY (`dcid`);

--
-- Indexes for table `games`
--
ALTER TABLE `games`
  ADD PRIMARY KEY (`gid`);

--
-- Indexes for table `gradebook`
--
ALTER TABLE `gradebook`
  ADD PRIMARY KEY (`gid`),
  ADD UNIQUE KEY `grade` (`grade`);

--
-- Indexes for table `image`
--
ALTER TABLE `image`
  ADD PRIMARY KEY (`imid`);

--
-- Indexes for table `instructor`
--
ALTER TABLE `instructor`
  ADD PRIMARY KEY (`inst_id`);

--
-- Indexes for table `lesson_structure`
--
ALTER TABLE `lesson_structure`
  ADD PRIMARY KEY (`lstid`),
  ADD KEY `lsid` (`slcid`),
  ADD KEY `mid` (`mid`);

--
-- Indexes for table `level`
--
ALTER TABLE `level`
  ADD PRIMARY KEY (`lid`);

--
-- Indexes for table `qanswers`
--
ALTER TABLE `qanswers`
  ADD PRIMARY KEY (`qaid`);

--
-- Indexes for table `queries`
--
ALTER TABLE `queries`
  ADD PRIMARY KEY (`qqid`),
  ADD KEY `qid` (`qid`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`qid`),
  ADD KEY `lid` (`sid`);

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`stid`),
  ADD KEY `lid` (`lid`);

--
-- Indexes for table `student_lessons`
--
ALTER TABLE `student_lessons`
  ADD PRIMARY KEY (`stlid`);

--
-- Indexes for table `student_progress`
--
ALTER TABLE `student_progress`
  ADD PRIMARY KEY (`spid`);

--
-- Indexes for table `student_queries`
--
ALTER TABLE `student_queries`
  ADD PRIMARY KEY (`stqid`);

--
-- Indexes for table `student_subject_level`
--
ALTER TABLE `student_subject_level`
  ADD PRIMARY KEY (`sslid`);

--
-- Indexes for table `subject`
--
ALTER TABLE `subject`
  ADD PRIMARY KEY (`sid`);

--
-- Indexes for table `subject_level`
--
ALTER TABLE `subject_level`
  ADD PRIMARY KEY (`slid`),
  ADD KEY `sid` (`sid`),
  ADD KEY `lid` (`lid`);

--
-- Indexes for table `subject_level_curriculum`
--
ALTER TABLE `subject_level_curriculum`
  ADD PRIMARY KEY (`slcid`),
  ADD KEY `slid` (`slid`);

--
-- Indexes for table `temp_student_queries`
--
ALTER TABLE `temp_student_queries`
  ADD PRIMARY KEY (`stqid`),
  ADD KEY `stid` (`stid`),
  ADD KEY `qqid` (`qqid`),
  ADD KEY `lid` (`slcid`);

--
-- Indexes for table `videonaudio`
--
ALTER TABLE `videonaudio`
  ADD PRIMARY KEY (`vaid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `aid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `admin_log`
--
ALTER TABLE `admin_log`
  MODIFY `alid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `appuser`
--
ALTER TABLE `appuser`
  MODIFY `apid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `document`
--
ALTER TABLE `document`
  MODIFY `dcid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `games`
--
ALTER TABLE `games`
  MODIFY `gid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gradebook`
--
ALTER TABLE `gradebook`
  MODIFY `gid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `image`
--
ALTER TABLE `image`
  MODIFY `imid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `instructor`
--
ALTER TABLE `instructor`
  MODIFY `inst_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `lesson_structure`
--
ALTER TABLE `lesson_structure`
  MODIFY `lstid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `level`
--
ALTER TABLE `level`
  MODIFY `lid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `qanswers`
--
ALTER TABLE `qanswers`
  MODIFY `qaid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `queries`
--
ALTER TABLE `queries`
  MODIFY `qqid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `qid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `student`
--
ALTER TABLE `student`
  MODIFY `stid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `student_lessons`
--
ALTER TABLE `student_lessons`
  MODIFY `stlid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `student_progress`
--
ALTER TABLE `student_progress`
  MODIFY `spid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `student_queries`
--
ALTER TABLE `student_queries`
  MODIFY `stqid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `student_subject_level`
--
ALTER TABLE `student_subject_level`
  MODIFY `sslid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `subject`
--
ALTER TABLE `subject`
  MODIFY `sid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `subject_level`
--
ALTER TABLE `subject_level`
  MODIFY `slid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `subject_level_curriculum`
--
ALTER TABLE `subject_level_curriculum`
  MODIFY `slcid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `temp_student_queries`
--
ALTER TABLE `temp_student_queries`
  MODIFY `stqid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `videonaudio`
--
ALTER TABLE `videonaudio`
  MODIFY `vaid` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
