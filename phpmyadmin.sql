-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Created: Giu 07, 2020 alle 22:54
-- Server version: 10.4.11-MariaDB
-- PHP version: 7.4.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `website`
--

-- --------------------------------------------------------

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `author` int(11) NOT NULL,
  `post` int(11) NOT NULL,
  `date` datetime DEFAULT current_timestamp(),
  `content` text NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `topic` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `author` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `content` mediumtext NOT NULL,
  `topic` varchar(20) DEFAULT NULL,
  `date` datetime DEFAULT current_timestamp(),
  `type` varchar(20) NOT NULL,
  `description` varchar(256) DEFAULT NULL,
  `image` varchar(20) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

CREATE TABLE `topics` (
  `id` int(11) NOT NULL,
  `topic` varchar(20) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(20) NOT NULL,
  `email` varchar(128) NOT NULL,
  `password` varchar(128) NOT NULL,
  `role` int(1) NOT NULL DEFAULT 0,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `isBanned` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`);

-- --------------------------------------------------------

ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`);

-- --------------------------------------------------------

ALTER TABLE `topics`
  ADD PRIMARY KEY (`id`);

-- --------------------------------------------------------

ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

-- --------------------------------------------------------

ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;

-- --------------------------------------------------------

ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;

-- --------------------------------------------------------

ALTER TABLE `topics`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;

-- --------------------------------------------------------

ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;
COMMIT;


/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
