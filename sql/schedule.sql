-- phpMyAdmin SQL Dump
-- version 4.8.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 2018 年 11 朁E11 日 01:08
-- サーバのバージョン： 10.1.32-MariaDB
-- PHP Version: 5.6.36

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `schedule`
--

-- --------------------------------------------------------

--
-- テーブルの構造 `m_plans`
--

CREATE TABLE `m_plans` (
  `ym` int(11) NOT NULL COMMENT '年月',
  `id` int(11) NOT NULL COMMENT 'ID',
  `date` int(11) NOT NULL DEFAULT '1' COMMENT '日付',
  `title` varchar(20) NOT NULL COMMENT 'タイトル',
  `detail` text COMMENT '詳細',
  `create_date` datetime DEFAULT NULL COMMENT '作成日',
  `update_date` datetime DEFAULT NULL COMMENT '更新日'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for table `m_plans`
--
ALTER TABLE `m_plans`
  ADD PRIMARY KEY (`id`,`ym`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
