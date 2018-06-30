-- phpMyAdmin SQL Dump
-- version 4.7.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 01, 2018 at 08:13 AM
-- Server version: 10.1.24-MariaDB-cll-lve
-- PHP Version: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `raze4fasl_biobot`
--

-- --------------------------------------------------------

--
-- Table structure for table `Actions`
--

CREATE TABLE `Actions` (
  `tgid` varchar(32) NOT NULL,
  `action` varchar(1024) DEFAULT NULL,
  `data` text,
  `other` mediumtext
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `Chats`
--

CREATE TABLE `Chats` (
  `chatid` varchar(32) NOT NULL,
  `xfrom` varchar(32) DEFAULT NULL,
  `xto` varchar(32) DEFAULT NULL,
  `time` varchar(32) DEFAULT NULL,
  `message` mediumtext,
  `seen` varchar(8) DEFAULT NULL,
  `tags` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `Contents`
--

CREATE TABLE `Contents` (
  `fid` int(11) NOT NULL,
  `file` varchar(1024) DEFAULT NULL,
  `subject` varchar(1024) DEFAULT NULL,
  `category` varchar(64) DEFAULT NULL,
  `description` text,
  `type` varchar(32) DEFAULT NULL,
  `nscore` varchar(16) DEFAULT NULL,
  `dlcount` varchar(16) CHARACTER SET utf8 DEFAULT NULL,
  `other` text,
  `users` longtext,
  `cover` varchar(2048) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `Coupons`
--

CREATE TABLE `Coupons` (
  `cid` varchar(32) NOT NULL,
  `tgid` varchar(32) NOT NULL,
  `time` varchar(32) NOT NULL,
  `data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `Groups`
--

CREATE TABLE `Groups` (
  `groupid` varchar(32) NOT NULL,
  `members` varchar(32) DEFAULT NULL,
  `sendflag` varchar(32) DEFAULT NULL,
  `active` varchar(8) DEFAULT NULL,
  `stime` varchar(32) DEFAULT NULL,
  `referer` varchar(32) DEFAULT NULL,
  `history` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `Robot`
--

CREATE TABLE `Robot` (
  `uid` varchar(32) NOT NULL,
  `tgid` varchar(32) NOT NULL,
  `username` varchar(128) DEFAULT NULL,
  `name` varchar(512) DEFAULT NULL,
  `family` varchar(512) DEFAULT NULL,
  `active` varchar(8) DEFAULT '1',
  `score` varchar(32) DEFAULT '0',
  `realname` varchar(512) DEFAULT NULL,
  `gender` varchar(32) DEFAULT NULL,
  `email` varchar(512) DEFAULT NULL,
  `phone` varchar(16) DEFAULT NULL,
  `birth` varchar(16) DEFAULT NULL,
  `reagent` varchar(32) DEFAULT NULL,
  `subset` longtext,
  `ban` varchar(8) NOT NULL DEFAULT '0',
  `stime` varchar(32) DEFAULT NULL,
  `sendflag` int(11) NOT NULL DEFAULT '0',
  `lastuse` varchar(32) DEFAULT NULL,
  `history` longtext
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Actions`
--
ALTER TABLE `Actions`
  ADD PRIMARY KEY (`tgid`);

--
-- Indexes for table `Chats`
--
ALTER TABLE `Chats`
  ADD PRIMARY KEY (`chatid`);

--
-- Indexes for table `Contents`
--
ALTER TABLE `Contents`
  ADD PRIMARY KEY (`fid`);

--
-- Indexes for table `Coupons`
--
ALTER TABLE `Coupons`
  ADD PRIMARY KEY (`cid`);

--
-- Indexes for table `Groups`
--
ALTER TABLE `Groups`
  ADD PRIMARY KEY (`groupid`);

--
-- Indexes for table `Robot`
--
ALTER TABLE `Robot`
  ADD PRIMARY KEY (`uid`),
  ADD UNIQUE KEY `tgid` (`tgid`),
  ADD KEY `score` (`score`,`gender`,`reagent`,`ban`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Contents`
--
ALTER TABLE `Contents`
  MODIFY `fid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
