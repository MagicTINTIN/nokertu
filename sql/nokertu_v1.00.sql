-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Nov 02, 2025 at 11:48 AM
-- Server version: 10.11.13-MariaDB
-- PHP Version: 8.4.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `nokertu`
--

-- --------------------------------------------------------

--
-- Table structure for table `cards`
--

CREATE TABLE `cards` (
  `ownerID` int(11) NOT NULL,
  `type` int(8) NOT NULL,
  `color` int(8) NOT NULL COMMENT '0=black 1=spades 2=clubs 3=diamonds 4=hearts',
  `value` int(11) NOT NULL,
  `marks` varchar(20) NOT NULL DEFAULT '' COMMENT 'opponents marks to see players card, also fold appear here',
  `properties` varchar(20) NOT NULL DEFAULT '' COMMENT 'properties like paper cards and ephemeral cards',
  `location` int(11) NOT NULL COMMENT '-n to -1=slots, 0=draw, 1 to n=position in deck'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `effects`
--

CREATE TABLE `effects` (
  `effectID` int(11) NOT NULL,
  `gameID` varchar(8) NOT NULL,
  `effectType` int(11) NOT NULL COMMENT 'type of effect (e.g. Eclipse)',
  `appliedTo` int(11) NOT NULL COMMENT 'playerID, or -1 for whole game',
  `roundsLeft` int(11) NOT NULL DEFAULT -1 COMMENT 'effect duration (-1 for whole game'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `games`
--

CREATE TABLE `games` (
  `_uuid` int(11) NOT NULL,
  `gameID` varchar(8) NOT NULL,
  `name` varchar(32) NOT NULL,
  `password` varchar(32) NOT NULL DEFAULT '',
  `timestamp` bigint(20) NOT NULL,
  `createdAt` datetime NOT NULL DEFAULT current_timestamp(),
  `maxPlayers` tinyint(4) NOT NULL DEFAULT 4,
  `gameState` tinyint(4) NOT NULL DEFAULT 0,
  `roundsCounter` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `players`
--

CREATE TABLE `players` (
  `ID` int(11) NOT NULL,
  `gameID` varchar(8) NOT NULL,
  `name` varchar(20) NOT NULL,
  `avatar` varchar(256) NOT NULL,
  `slotsNumber` int(11) NOT NULL DEFAULT 1,
  `plus2Placed` int(11) NOT NULL DEFAULT 0,
  `plus4Placed` int(11) NOT NULL DEFAULT 0,
  `times2Placed` int(11) NOT NULL DEFAULT 0,
  `tarotPlaced` int(11) NOT NULL DEFAULT 0,
  `totalNumberPerRound` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `effects`
--
ALTER TABLE `effects`
  ADD PRIMARY KEY (`effectID`);

--
-- Indexes for table `games`
--
ALTER TABLE `games`
  ADD PRIMARY KEY (`_uuid`);

--
-- Indexes for table `players`
--
ALTER TABLE `players`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `effects`
--
ALTER TABLE `effects`
  MODIFY `effectID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `games`
--
ALTER TABLE `games`
  MODIFY `_uuid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `players`
--
ALTER TABLE `players`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
