-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jun 21, 2020 at 07:55 PM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.4.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `chat`
--

-- --------------------------------------------------------

--
-- Table structure for table `says`
--

CREATE TABLE `says` (
  `id` int(11) NOT NULL,
  `t1` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `t2` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `t3` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `t4` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `opt` varchar(300) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `var` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `next` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cq` varchar(600) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cqb` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `says`
--

INSERT INTO `says` (`id`, `t1`, `t2`, `t3`, `t4`, `opt`, `var`, `next`, `type`, `cq`, `cqb`) VALUES
(0, 'Hello, I\'m King', 'Tell me your name first', NULL, NULL, NULL, 'user_name', '1', 'input', NULL, NULL),
(1, 'Hey there user_name.', 'Here are my scopes I can help you with', NULL, NULL, 'About Me,College,Studnet,Department,Principal', 'user_deatils_req', '2,3,48,500,6', 'opt', NULL, NULL),
(2, 'I am Awcator, Designed by <b> Awcaotr.', 'I am created using <b>JavaScript,Jquery & PHP , I coomunicate with the help of JSon Requests', 'I am here for helping you guys with a virtual introduction of this college', 'I represent Virtual Receptionist', 'Send me back to Main Menu,End Conversation', NULL, '1,99', 'opt', NULL, NULL),
(3, '<img src=\'https://schoolbook.getmyuni.com/assets/images/rev_img/63731__28926/15186800131518679987139.jpg\'>', 'Welcome to GECH ', NULL, NULL, 'Administration,Scholorships', 'college_details', '15,20', 'opt', NULL, NULL),
(4, 'To view your result please enter your USN', NULL, NULL, NULL, NULL, 'usn', '40&u_no=usn', 'input', NULL, NULL),
(5, 'Result of student_name is as shown below', NULL, NULL, NULL, NULL, NULL, '6&sname=student_name', 'msg', NULL, NULL),
(6, 'RESULT', NULL, NULL, NULL, NULL, NULL, '1', 'msg', NULL, NULL),
(40, 'Enter your DOB (YYYY-MM-DD)', NULL, NULL, NULL, NULL, 'dob', '41&u_no=usn&d_ob=dob', 'input', '', 0),
(41, NULL, NULL, NULL, NULL, NULL, NULL, '42', 'msg', 'select student.* from student join users on (users.usn = student.usn) where users.usn LIKE \'u_no\' and users.dob LIKE \'d_ob\'', 1),
(42, NULL, NULL, NULL, NULL, 'Send me back to Main Menu,End Conversation', NULL, '1,99', 'opt', NULL, NULL),
(47, NULL, NULL, NULL, NULL, NULL, NULL, '50&branch=branch_&sem=sem_', 'msg', NULL, NULL),
(48, 'Enter your branch (CAPITAL case)', NULL, NULL, NULL, NULL, 'branch_', '49&branch=branch_', 'input', NULL, NULL),
(49, 'Enter your sem (in number)', NULL, NULL, NULL, NULL, 'sem_', '47&sem=sem_&branch=branch_', 'input', NULL, NULL),
(50, 'Enter phone no or email for OTP auth', NULL, NULL, NULL, NULL, 'num_', '51&num=num_&branch=branch_&sem=sem_', 'input', NULL, NULL),
(51, NULL, NULL, NULL, NULL, NULL, NULL, '52&num=num&branch=branch&sem=sem', 'msg', 'select * from users where phone = \'num\' limit 1', 1),
(52, 'Enter the OTP received', NULL, NULL, NULL, NULL, 'otp_', '53&num=_num&otp=otp_&branch=branch_&sem=sem_', 'input', NULL, NULL),
(53, NULL, NULL, NULL, NULL, NULL, NULL, '54', 'msg', 'select student.* from student join users on (users.usn = student.usn) where users.phone = \'num_\'', 1),
(54, NULL, NULL, NULL, NULL, 'Send me back to Main Menu,End Conversation', NULL, '1,99', 'opt', NULL, 0),
(99, 'We want to get your email id', 'We use your email id to reach our updates to your inbox', 'Please enter your email id', NULL, NULL, 'email_id', '100&email_=email_id', 'input', NULL, NULL),
(100, 'Your email id is recorded. Thanks.', NULL, NULL, NULL, NULL, NULL, NULL, 'msg', 'INSERT INTO `users_emailid` (`id`, `email`, `created_on`) VALUES (NULL, \'email_\', current_timestamp());', 1),
(500, 'These are the departments that are avaialbe with GECH: ', NULL, NULL, NULL, NULL, 'dept_name', '501&dept=dept_name', 'input', 'select dept from teachers group by dept', 1),
(501, 'These are the avaiable faculty in the Dept. dept_name ', NULL, NULL, NULL, NULL, 'dept_name', '502&user_dept=dept_name', 'msg', NULL, NULL),
(502, NULL, NULL, NULL, NULL, NULL, 'teacher_name', '503&user_dept=dept_name&te=teacher_name', 'input', 'select name from teachers where dept=\'user_dept\'', 1),
(503, 'TEACHER', NULL, NULL, NULL, NULL, NULL, '1', 'msg', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE `student` (
  `usn` varchar(10) DEFAULT NULL,
  `name` varchar(20) DEFAULT NULL,
  `branch` varchar(120) NOT NULL,
  `sem` varchar(120) NOT NULL,
  `M1` int(10) DEFAULT NULL,
  `M2` int(10) DEFAULT NULL,
  `M3` int(10) DEFAULT NULL,
  `M4` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`usn`, `name`, `branch`, `sem`, `M1`, `M2`, `M3`, `M4`) VALUES
('4gh16cs048', 'sanjay', '0', '', 100, 99, 98, 99),
('4gh16cs017', 'Harshita', '0', '', 100, 100, 92, 91),
('4gh16cs056', 'spandana', '0', '', 100, 100, 100, 100),
('4gh16cs047', 'sania', '0', '', 100, 90, 91, 100),
('4gh16cs029', 'Meghana', '0', '', 100, 100, 92, 99),
('4gh18cs030', 'mine', '0', '', 90, 90, 10, 10),
('4gh16cs011', 'sir', '0', '', 111, 111, 19, 11),
('123', 'sohan_testing', 'CSE', '6', 99, 100, 67, 97);

-- --------------------------------------------------------

--
-- Table structure for table `teachers`
--

CREATE TABLE `teachers` (
  `id` int(11) DEFAULT NULL,
  `name` varchar(70) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dept` varchar(70) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tagline` varchar(700) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `teachers`
--

INSERT INTO `teachers` (`id`, `name`, `dept`, `tagline`, `image`) VALUES
(1, 'Dr.K.C.Ravishankar', 'CSE', 'Dr. K. C. Ravishankar did his B.E. from MCE, Hassan in 1990, M.Tech from IIT, Delhi in 1998 and Ph.D. from Visvesvaraya Technological University in 2009. He served at MCE from 1990-2010. His areas of interest include Databases, Image Processing and Cryptography. He has 6 international and 16 national publications to his credit', '<img src=\'http://www.gechassan.ac.in/img/staff/kcr.jpg\'>'),
(2, 'Mr.Annaiah', 'CSE', '<b>B.E., MTech.,- Assistant Professor </b> <br> Mr. Annaiah H. , did his BE(CS&E) from B.I.E.T., Davanagere in 2004 and M.Tech from N.I.E. Mysore in 2006. He had served at Infosys Technologies Limited, Mysore as a Senior Software Engineer for 4 years. His areas of interests are Networking and Database Management System', '<img src=\'http://www.gechassan.ac.in/img/staff/annaiah.jpg\'>'),
(3, 'Dr.Chetan', 'CSE', '<b>B.E., M.Tech., - Assistant Professor </b> <br> Mr. Chethan K C did his B.E from BMSCE, Bangalore and M.Tech from Dr AIT., Bangalore, He has presented a paper in the International Conference held at VIT, Vellore', '<img src=\'http://www.gechassan.ac.in/img/staff/chethan-cs.jpg\'>'),
(4, 'Dr. Prashanth S', 'CV', '<b>BE, MTech, PhD. - Professor & Head </b> <br>Dr.S.Prashanth obtained his B.E. from Malnad College of Engineering (MCE), Hassan in 1987, M.E. from University of Roorkee, Roorkee in 1996 and Ph.D. from IIT, Roorkee in 2004. Before joining GECH, He was serving at MCE in various academic positions since 1988', '<img src=\'http://www.gechassan.ac.in/img/staff/prashanth.jpg\'>'),
(5, 'Dr. Vijay Kumar H.A', 'CV', '<b>BE, MTech, PhD</b> <br> H.A.Vijaya Kumar obtained his B.E.from MCE, Hassan in 1993, and M.Tech. from BMSCE, Bangalore in 2005 and  Ph.D. from UVCE, Bangalore University. He has served UVCE, Department of civil', '<img src=\'http://www.gechassan.ac.in/img/staff/vijay.jpg\'>'),
(6, 'DR.Mahesh T S', 'ME', '<b>B.E., M.E., Ph.D.</b> <br> Dr. Mahesh T S B.E. from SIT Tumkur in 1992, ME from UVCE Bangalore University in 2005 and Pursuing Ph.D. at VTU Belgaum. Before joining GECH, he served at KIT, Tiptur from 1997-2010. His areas of interest are Design Optimization, Mechanical Vibrations, FEA and Smart Materials. He has 01 national publication to his credit', '<img src=\'http://www.gechassan.ac.in/img/staff/mahesh.jpg\'>'),
(7, 'Dr. Paramesha', 'ECE', '<b>BE, MTech, PhD. - Assistant Professor & Head </b> <br>Dr. Paramesha obtained his B.E. (Electronics and Communication) from MCE, Hassan in 1989, M.Tech. (Microwave Engg.) from IT BHU, Varanasi in 1997 and Ph.D. from IIT Kharagpur in 2007. He has served AIT Chikmagalur since 1990. He has 09 national/international publications to his credit', '<img src=\'http://www.gechassan.ac.in/img/staff/paramesh.jpg\'>'),
(8, 'Ms. Baby H.T.', 'ECE', '<b> BE, MTech - Assistant Professor & Edusat Coordinator </b> <br>Ms. Baby H.T. obtained her B.E. (Electronics and Communication Engineering) from Mysore University, in the year 2001, M.Tech. (Digital communication and Networking ) from Visvesvaraya Technological University, in the year 2005 ', '<img src=\'http://www.gechassan.ac.in/img/staff/baby.jpg\'>');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `usn` varchar(10) DEFAULT NULL,
  `email` varchar(70) DEFAULT NULL,
  `phone` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`usn`, `email`, `phone`) VALUES
('4gh16cs048', 'hsanjay.1998@gmail.com', '6360323549'),
('4gh16cs017', 'harshita.19.08.98@gmail.com', '8619493926'),
('4gh16cs056', 'spandanal1998@gmail.com', '8088224828'),
('4gh16cs047', 'sania99.hsn@gmail.com', '6361132885'),
('4gh16cs029', 'meghanasv2017@gmail.com', '8762553272'),
('4gh18cs030', 'nischushetty5@gmail.com', '9148114165'),
('4gh16cs011', 'annaiahh@gmail.com', '9008316684'),
('123', 'sohan.m.kulkarni@gmail.com', '8867706702');

-- --------------------------------------------------------

--
-- Table structure for table `users_emailid`
--

CREATE TABLE `users_emailid` (
  `id` int(11) NOT NULL,
  `email` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users_emailid`
--

INSERT INTO `users_emailid` (`id`, `email`) VALUES
(1, 'hsanjay.1998@gmail.com'),
(2, 'harshita.19.08.98@gmail.com'),
(3, 'spandanal1998@gmail.com'),
(4, 'sania99.hsn@gmail.com'),
(5, 'meghanasv2017@gmail.com'),
(6, 'nischushetty5@gmail.com');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `says`
--
ALTER TABLE `says`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users_emailid`
--
ALTER TABLE `users_emailid`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users_emailid`
--
ALTER TABLE `users_emailid`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
