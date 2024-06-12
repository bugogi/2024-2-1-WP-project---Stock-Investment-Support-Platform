-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- 생성 시간: 24-06-12 16:49
-- 서버 버전: 10.4.32-MariaDB
-- PHP 버전: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 데이터베이스: `wp_project_db`
--

-- --------------------------------------------------------

--
-- 테이블 구조 `stocks`
--

CREATE TABLE `stocks` (
  `SID` int(11) NOT NULL,
  `stock_name` varchar(255) NOT NULL,
  `dom_or_over` int(1) NOT NULL,
  `pre_price` int(15) NOT NULL,
  `cur_price` int(15) NOT NULL,
  `like_num` int(4) NOT NULL DEFAULT 0,
  `corp_info` varchar(255) NOT NULL,
  `rec_ryu` tinyint(1) NOT NULL DEFAULT 0,
  `rec_woo` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 테이블의 덤프 데이터 `stocks`
--

INSERT INTO `stocks` (`SID`, `stock_name`, `dom_or_over`, `pre_price`, `cur_price`, `like_num`, `corp_info`, `rec_ryu`, `rec_woo`) VALUES
(1, 'Samsung Electronics', 0, 38000, 42000, 8124, 'A global leader in technology, specializing in consumer electronics, semiconductors, and telecommunications.', 1, 0),
(2, 'Hyundai Motor Company', 0, 5200, 7000, 626, 'A major automobile manufacturer, producing a wide range of vehicles including electric and hydrogen cars.', 0, 0),
(3, 'LG Electronics', 0, 12500, 10920, 81, 'Produces a diverse range of electronics and home appliances, known for its advancements in OLED technology.', 0, 0),
(4, 'SK Hynix', 0, 18050, 17030, 6250, 'A leading semiconductor company specializing in memory chips like DRAM and NAND flash.', 1, 0),
(5, 'POSCO', 0, 5900, 7780, 345, 'One of the largest steel producers in the world, supplying various steel products for multiple industries.', 0, 1),
(6, 'Kakao', 0, 24300, 20100, 539, 'An internet company known for its messaging app, KakaoTalk, and various digital services including banking and ride-hailing.', 0, 0),
(7, 'Apple Inc.', 1, 174630, 150780, 7255, 'Designs, manufactures, and markets consumer electronics, software, and services, including the iPhone, iPad, and Mac.', 0, 0),
(8, 'Microsoft Corporation', 1, 79020, 83300, 1909, 'A global leader in software, services, devices, and solutions, known for products like Windows, Office, and Azure cloud services.', 0, 0),
(9, 'Amazon.com, Inc.', 1, 30950, 34050, 2003, 'Operates the world\'s largest online retail platform and provides cloud computing services through AWS.', 0, 0),
(10, 'Alphabet Inc.', 1, 270120, 280470, 100, 'The parent company of Google, specializing in internet-related services and products, including search, advertising, and YouTube.', 0, 1),
(11, 'Tesla, Inc.', 1, 6700, 6000, 8315, 'Designs, manufactures, and sells electric vehicles and energy storage products, known for its innovation in autonomous driving technology.', 1, 0),
(12, 'Johnson & Johnson', 1, 59070, 62230, 485, 'A multinational corporation that develops medical devices, pharmaceuticals, and consumer health products.', 0, 1);

-- --------------------------------------------------------

--
-- 테이블 구조 `users`
--

CREATE TABLE `users` (
  `SID` int(11) NOT NULL,
  `user_name` varchar(255) NOT NULL,
  `user_account` varchar(20) NOT NULL,
  `user_balance` int(10) NOT NULL DEFAULT 0,
  `user_ID` varchar(20) NOT NULL,
  `user_PW` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 테이블의 덤프 데이터 `users`
--

INSERT INTO `users` (`SID`, `user_name`, `user_account`, `user_balance`, `user_ID`, `user_PW`) VALUES
(1, 'Heo Seung Woo', '2101179821011798', 300000, 'Heo', '0124'),
(4, 'Kim Min Jae', '19961115', 0, 'Kim', '1111'),
(5, 'Son Heung Min', '19920708', 0, 'Son', '2222');

-- --------------------------------------------------------

--
-- 테이블 구조 `user_held_stocks`
--

CREATE TABLE `user_held_stocks` (
  `SID` int(11) NOT NULL,
  `stock_ID` int(4) NOT NULL,
  `user_ID` int(4) NOT NULL,
  `held_num` int(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 테이블의 덤프 데이터 `user_held_stocks`
--

INSERT INTO `user_held_stocks` (`SID`, `stock_ID`, `user_ID`, `held_num`) VALUES
(8, 2, 1, 8),
(9, 11, 1, 5),
(10, 1, 1, 16);

-- --------------------------------------------------------

--
-- 테이블 구조 `user_like`
--

CREATE TABLE `user_like` (
  `SID` int(11) NOT NULL,
  `stock_ID` int(4) NOT NULL,
  `user_ID` int(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 테이블의 덤프 데이터 `user_like`
--

INSERT INTO `user_like` (`SID`, `stock_ID`, `user_ID`) VALUES
(10, 2, 1),
(15, 10, 1),
(16, 7, 1),
(23, 2, 4),
(24, 6, 4),
(25, 3, 4),
(26, 11, 4),
(29, 4, 1);

--
-- 덤프된 테이블의 인덱스
--

--
-- 테이블의 인덱스 `stocks`
--
ALTER TABLE `stocks`
  ADD PRIMARY KEY (`SID`);

--
-- 테이블의 인덱스 `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`SID`);

--
-- 테이블의 인덱스 `user_held_stocks`
--
ALTER TABLE `user_held_stocks`
  ADD PRIMARY KEY (`SID`);

--
-- 테이블의 인덱스 `user_like`
--
ALTER TABLE `user_like`
  ADD PRIMARY KEY (`SID`);

--
-- 덤프된 테이블의 AUTO_INCREMENT
--

--
-- 테이블의 AUTO_INCREMENT `stocks`
--
ALTER TABLE `stocks`
  MODIFY `SID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- 테이블의 AUTO_INCREMENT `users`
--
ALTER TABLE `users`
  MODIFY `SID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- 테이블의 AUTO_INCREMENT `user_held_stocks`
--
ALTER TABLE `user_held_stocks`
  MODIFY `SID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- 테이블의 AUTO_INCREMENT `user_like`
--
ALTER TABLE `user_like`
  MODIFY `SID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
