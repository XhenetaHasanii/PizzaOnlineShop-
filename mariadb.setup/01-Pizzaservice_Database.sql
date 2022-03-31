-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- CreationTime: 13. Apr 2020 um 14:05
-- Server-Version: 10.4.11-MariaDB

-- PHP-Version: 7.4.2

-- /*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
-- /*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
-- /*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
-- /*!40101 SET NAMES utf8mb4 */;

-- 
-- Database: `pizzaservice_2020`
--

CREATE DATABASE IF NOT EXISTS pizzaservice_2020
DEFAULT CHARACTER SET utf8
COLLATE utf8_unicode_ci;

USE pizzaservice_2020;

-- ----------------------------------------------------------------

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";
-- --------------------------------------------------------

--
-- Tabellenstruktur f�r Tabelle `article`
--

CREATE TABLE `article` (
  `id` int(11) NOT NULL,
  `name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `picture` varchar(254) COLLATE utf8_unicode_ci NOT NULL,
  `price` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Data for Table `article`
--

INSERT INTO `article` (`id`, `name`, `picture`, `price`) VALUES
(1, 'Salami', 'pizza.gif', 8.57),
(2, 'Vegetarian', 'pizza.gif', 12.5),
(3, 'Chicken Spinat', 'pizza.gif', 11.99);

-- --------------------------------------------------------

--
-- Tabellenstruktur f�r Tabelle `ordering`
--

CREATE TABLE `ordering` (
  `id` int(11) NOT NULL,
  `address` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten f�r Tabelle `ordering`
--

INSERT INTO `ordering` (`id`, `address`, `timestamp`) VALUES
(16, 'Birkenweg 7, 2. Stock, Telefon: 12325, Mustermann', '2020-04-13 12:58:56'),
(17, 'Sch�fferstr. 8b, 1637733, Musterfrau', '2020-04-13 13:01:49');

-- --------------------------------------------------------

--
-- Tabellenstruktur f�r Tabelle `ordered_articles`
--

CREATE TABLE `ordered_articles` (
  `id` int(11) NOT NULL,
  `f_article_id` int(11) NOT NULL,
  `f_order_id` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten f�r Tabelle `ordered_articles`
--

INSERT INTO `ordered_articles` (`id`, `f_article_id`, `f_order_id`, `status`) VALUES
(39, 1, 16, 3),
(40, 1, 16, 3),
(41, 2, 16, 3),
(42, 3, 16, 3),
(43, 2, 17, 1),
(44, 2, 17, 0),
(45, 3, 17, 1);

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes f�r die Tabelle `article`
--
ALTER TABLE `article`
  ADD PRIMARY KEY (`id`);

--
-- Indizes f�r die Tabelle `ordering`
--
ALTER TABLE `ordering`
  ADD PRIMARY KEY (`id`);

--
-- Indizes f�r die Tabelle `ordered_articles`
--
ALTER TABLE `ordered_articles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `index_article` (`f_article_id`) USING BTREE,
  ADD KEY `index_order` (`f_order_id`) USING BTREE;

--
-- AUTO_INCREMENT f�r exportierte Tabellen
--

--
-- AUTO_INCREMENT f�r Tabelle `article`
--
ALTER TABLE `article`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT f�r Tabelle `ordering`
--
ALTER TABLE `ordering`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT f�r Tabelle `ordered_articles`
--
ALTER TABLE `ordered_articles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `ordered_articles`
--
ALTER TABLE `ordered_articles`
  ADD CONSTRAINT `ordered_articles_ibfk_1` FOREIGN KEY (`f_order_id`) REFERENCES `ordering` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `ordered_articles_ibfk_2` FOREIGN KEY (`f_article_id`) REFERENCES `article` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

-- /*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
-- /*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
-- /*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
