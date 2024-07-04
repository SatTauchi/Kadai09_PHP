-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- ホスト: localhost
-- 生成日時: 2024 年 6 月 27 日 05:05
-- サーバのバージョン： 10.4.28-MariaDB
-- PHP のバージョン: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- データベース: `gs_db_class`
--

-- --------------------------------------------------------

--
-- テーブルの構造 `kadai08_table`
--

CREATE TABLE `kadai08_table` (
  `id` int(12) NOT NULL,
  `date` date DEFAULT NULL,
  `fish` varchar(64) DEFAULT NULL,
  `place` varchar(64) DEFAULT NULL,
  `price` int(12) DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `photo` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- テーブルのデータのダンプ `kadai08_table`
--

INSERT INTO `kadai08_table` (`id`, `date`, `fish`, `place`, `price`, `remarks`, `photo`) VALUES
(12, '2024-06-27', 'ハマチ', '九州', 700, 'TEST', 'hamachi03.jpg'),
(13, '2024-07-01', 'マグロ', '北海道', 5000, 'TEST', 'maguro.jpeg'),
(14, '2024-06-29', 'アジ', '江戸前', 300, 'TEST', 'aji.jpeg'),
(15, '2024-06-25', 'アジ', '九州', 400, 'TEST', 'aji03.jpg'),
(17, '2024-06-01', 'アジ', '江戸前', 350, 'TEST', 'aji02.jpg'),
(18, '2024-06-04', 'アジ', '九州', 375, 'TEST', 'aji.jpeg');

--
-- ダンプしたテーブルのインデックス
--

--
-- テーブルのインデックス `kadai08_table`
--
ALTER TABLE `kadai08_table`
  ADD PRIMARY KEY (`id`);

--
-- ダンプしたテーブルの AUTO_INCREMENT
--

--
-- テーブルの AUTO_INCREMENT `kadai08_table`
--
ALTER TABLE `kadai08_table`
  MODIFY `id` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
