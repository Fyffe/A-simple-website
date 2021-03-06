-- phpMyAdmin SQL Dump
-- version 4.0.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Czas wygenerowania: 21 Wrz 2016, 13:48
-- Wersja serwera: 5.5.32-cll
-- Wersja PHP: 5.3.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Baza danych: `p489135_orangestripes`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `articles`
--

CREATE TABLE IF NOT EXISTS `articles` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `title` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `author` smallint(6) NOT NULL,
  `date` datetime NOT NULL,
  `text` text COLLATE utf8_unicode_ci NOT NULL,
  `img` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'default.png',
  PRIMARY KEY (`id`),
  KEY `author` (`author`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=35 ;

--
-- Zrzut danych tabeli `articles`
--

INSERT INTO `articles` (`id`, `title`, `author`, `date`, `text`, `img`) VALUES
(26, 'Test article number 1', 0, '2016-09-20 18:37:22', 'This is the first test article!', '1474389442.jpg'),
(27, 'Second test article', 0, '2016-09-20 18:37:57', 'This is the second test article!', '1474389478.png'),
(28, 'Third test article', 0, '2016-09-20 18:39:24', 'This is the third test article!', '1474389564.png'),
(29, 'Fourth test article', 0, '2016-09-20 18:39:57', 'This is the fourth test article!', '1474389598.png'),
(30, 'Fifth test article', 0, '2016-09-20 18:40:19', 'This is the fifth test article!', 'default.png'),
(31, 'Sixth test article', 0, '2016-09-20 18:52:27', 'Yeees... sixth.', '1474390348.jpg'),
(32, 'Seventh test article', 0, '2016-09-20 18:55:13', 'Another one', 'default.png'),
(33, 'Eigth article', 0, '2016-09-20 18:55:38', 'Yeah... eigth.', '1474390539.png'),
(34, 'Nineth article', 0, '2016-09-20 18:56:28', '[] [] <=|=<', '1474390588.jpg');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `username` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `code` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `activated` tinyint(1) NOT NULL DEFAULT '0',
  `permission` tinyint(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `username` (`username`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=9 ;

--
-- Zrzut danych tabeli `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `code`, `activated`, `permission`) VALUES
(0, 'fiffe', '62f2596b743b732c244ca5451a334b4f', 'lucaskobryn@gmail.com', 'MSGMxkb5jG9g', 1, 99),
(7, 'dankmemes3', '01d171e511af2faae1ead6e48b8e3877', 'ckh37232@zasod.com', '3lSoYoBkibiCJ2llu4PkMMobSYAit7SA1B9cFuJMJnUK3A1zzExssVYPKqkukn', 1, 0),
(8, 'asdf', '912ec803b2ce49e4a541068d495ab570', 'fafasd@ggg.pl', 'MI6BFgQfRKLq', 0, 0);

--
-- Ograniczenia dla zrzutów tabel
--

--
-- Ograniczenia dla tabeli `articles`
--
ALTER TABLE `articles`
  ADD CONSTRAINT `articles_ibfk_1` FOREIGN KEY (`author`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
         