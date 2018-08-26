-- phpMyAdmin SQL Dump
-- version 4.7.9
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 22, 2018 at 11:39 AM
-- Server version: 10.1.31-MariaDB
-- PHP Version: 7.2.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `zubarska_ordinacija`
--

-- --------------------------------------------------------

--
-- Table structure for table `adresa`
--

CREATE TABLE `adresa` (
  `id` int(10) UNSIGNED NOT NULL,
  `id_grad` int(10) UNSIGNED NOT NULL,
  `id_ulica` int(10) UNSIGNED NOT NULL,
  `broj` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `adresa`
--

INSERT INTO `adresa` (`id`, `id_grad`, `id_ulica`, `broj`) VALUES
(1, 1, 1, 72),
(2, 1, 2, 125),
(3, 4, 3, 33),
(4, 1, 4, 25);

-- --------------------------------------------------------

--
-- Table structure for table `grad`
--

CREATE TABLE `grad` (
  `id` int(10) UNSIGNED NOT NULL,
  `naziv` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `grad`
--

INSERT INTO `grad` (`id`, `naziv`) VALUES
(1, 'Beograd'),
(2, 'Novi Sad'),
(3, 'Kragujevac'),
(4, 'NiÅ¡');

-- --------------------------------------------------------

--
-- Table structure for table `korisnik`
--

CREATE TABLE `korisnik` (
  `id` int(10) UNSIGNED NOT NULL,
  `ime_prezime` varchar(40) NOT NULL,
  `id_adresa` int(10) UNSIGNED NOT NULL,
  `br_tel` varchar(15) NOT NULL,
  `username` varchar(40) NOT NULL,
  `password` varchar(255) NOT NULL,
  `id_vrsta_kor` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `korisnik`
--

INSERT INTO `korisnik` (`id`, `ime_prezime`, `id_adresa`, `br_tel`, `username`, `password`, `id_vrsta_kor`) VALUES
(1, 'Katarina StanisavljeviÄ‡', 1, '0691122334', 'katarina', '$2y$10$6XrzUG7RoxJKSZc7ZUgyseATGPLghaMVWxNLsBE/lI5v12DV44oI.', 1),
(2, 'Bane IliÄ‡', 2, '063223344', 'drbane', '$2y$10$7ov5uxJchnY2bCeuNBN.ku5QPmPkFRX54RUiTtI42uYHSsrE28eae', 2),
(3, 'Pera PeriÄ‡', 3, '0645566423', 'pera', '$2y$10$4tVBYn0lGuVkqNrlBDjmpevCUMB40Fdyh32Wg9Ct8KwEcKY14rhhG', 3),
(4, 'Marko ZirojeviÄ‡', 4, '069212112', 'ziridfg', '$2y$10$ptnSvxrDkNhoQMrL8yeyROaS9q5Xxw8idWoAkuskPcQLAuomf8dBC', 3);

-- --------------------------------------------------------

--
-- Table structure for table `ulica`
--

CREATE TABLE `ulica` (
  `id` int(10) UNSIGNED NOT NULL,
  `naziv` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ulica`
--

INSERT INTO `ulica` (`id`, `naziv`) VALUES
(1, 'Kraljice Katarine'),
(2, 'Jurija Gagarina'),
(3, 'Kneza MiloÅ¡a'),
(4, 'Ratka MitroviÄ‡a');

-- --------------------------------------------------------

--
-- Table structure for table `usluga`
--

CREATE TABLE `usluga` (
  `id` int(10) UNSIGNED NOT NULL,
  `naziv` varchar(50) NOT NULL,
  `cena` int(11) NOT NULL COMMENT 'cena usluge',
  `vreme` int(11) NOT NULL COMMENT 'vreme trajanja usluge u minutima',
  `id_vrsta_usluge` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `usluga`
--

INSERT INTO `usluga` (`id`, `naziv`, `cena`, `vreme`, `id_vrsta_usluge`) VALUES
(1, 'StomatoloÅ¡ki pregled', 1100, 10, 1),
(2, 'Zalivanje fisura', 2500, 30, 2),
(3, 'Terapija dubokog karijesa', 1700, 30, 3),
(4, 'Rutinsko vaÄ‘enje zuba', 2400, 15, 4),
(5, 'Uklanjanje kamenca i poliranje', 3000, 20, 5),
(6, 'Livena nadogradnja', 2000, 25, 6),
(7, 'Komplikovano vaÄ‘enje', 6000, 30, 4),
(8, 'Redovno kontrola sa UZK', 1700, 15, 1),
(9, 'Prva pomoÄ‡', 1500, 15, 1),
(10, 'SpecijalistiÄki pregled i konsultacije', 2000, 20, 1),
(11, 'Uklanjanje mekih naslaga', 1000, 10, 2),
(12, 'VaÄ‘enje mleÄnog zuba', 1000, 5, 2),
(13, 'Kompozitni ispun jednopovrÅ¡inski', 2500, 30, 3),
(14, 'Kompozitni ispun dvopovrÅ¡inski', 3000, 40, 3),
(15, 'Kompozitni ispun tropovrÅ¡inski', 3500, 50, 3),
(16, 'Revizija punjenja', 3300, 35, 3),
(17, 'HirurÅ¡ko vaÄ‘enje zuba', 12000, 60, 4),
(18, 'MenadÅ¾ment mekog tkiva po zubu', 2600, 30, 5),
(19, 'Skidanje krunice', 1100, 10, 6),
(20, 'Reparatura', 4000, 30, 6);

-- --------------------------------------------------------

--
-- Table structure for table `vrsta_korisnika`
--

CREATE TABLE `vrsta_korisnika` (
  `id` int(10) UNSIGNED NOT NULL,
  `naziv` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `vrsta_korisnika`
--

INSERT INTO `vrsta_korisnika` (`id`, `naziv`) VALUES
(1, 'admin'),
(2, 'doktor'),
(3, 'pacijent');

-- --------------------------------------------------------

--
-- Table structure for table `vrsta_usluge`
--

CREATE TABLE `vrsta_usluge` (
  `id` int(10) UNSIGNED NOT NULL,
  `naziv` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `vrsta_usluge`
--

INSERT INTO `vrsta_usluge` (`id`, `naziv`) VALUES
(1, 'Pregledi'),
(2, 'DeÄja i preventivna stomatologija'),
(3, 'Bolesti zuba'),
(4, 'Oralna hirurgija'),
(5, 'Paradontologija'),
(6, 'Protetika');

-- --------------------------------------------------------

--
-- Table structure for table `zakazivanje`
--

CREATE TABLE `zakazivanje` (
  `id` int(10) UNSIGNED NOT NULL,
  `id_korisnik` int(10) UNSIGNED NOT NULL,
  `vreme_start` datetime NOT NULL,
  `vreme_kraj` datetime NOT NULL,
  `doktor` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `zakazivanje`
--

INSERT INTO `zakazivanje` (`id`, `id_korisnik`, `vreme_start`, `vreme_kraj`, `doktor`) VALUES
(6, 1, '2018-08-18 12:55:00', '2018-08-18 13:40:00', '2'),
(8, 1, '2018-08-27 13:25:00', '2018-08-27 14:10:00', '2'),
(9, 1, '2018-08-29 13:15:00', '2018-08-29 14:10:00', '2'),
(10, 1, '2018-08-19 13:00:00', '2018-08-19 13:30:00', '2'),
(14, 1, '2018-09-02 12:00:00', '2018-09-02 12:35:00', '2'),
(16, 1, '2018-08-18 13:30:00', '2018-08-18 13:55:00', '2'),
(17, 1, '2018-08-31 12:50:00', '2018-08-31 13:00:00', '2'),
(20, 1, '2018-08-26 18:55:00', '2018-08-26 19:50:00', '2'),
(23, 3, '2018-09-05 17:20:00', '2018-09-05 18:10:00', '2'),
(24, 3, '2018-09-01 19:35:00', '2018-09-01 19:40:00', '2'),
(25, 3, '2018-09-11 12:00:00', '2018-09-11 13:15:00', '2'),
(26, 3, '2018-09-03 12:10:00', '2018-09-03 12:50:00', '2'),
(29, 4, '2018-08-27 16:45:00', '2018-08-27 17:10:00', '2'),
(30, 3, '2018-08-22 14:00:00', '2018-08-22 15:15:00', '2'),
(31, 3, '2018-08-20 19:00:00', '2018-08-20 19:15:00', '2');

-- --------------------------------------------------------

--
-- Table structure for table `zakazivanje_usluge`
--

CREATE TABLE `zakazivanje_usluge` (
  `id_zakazivanje` int(10) UNSIGNED NOT NULL,
  `id_usluga` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `zakazivanje_usluge`
--

INSERT INTO `zakazivanje_usluge` (`id_zakazivanje`, `id_usluga`) VALUES
(6, 1),
(6, 3),
(8, 1),
(8, 2),
(9, 3),
(9, 5),
(10, 6),
(14, 3),
(16, 5),
(17, 12),
(20, 7),
(20, 5),
(24, 12),
(25, 9),
(25, 2),
(25, 6),
(26, 9),
(26, 10),
(29, 10),
(30, 10),
(30, 13),
(30, 5),
(31, 1),
(23, 8),
(23, 2);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `adresa`
--
ALTER TABLE `adresa`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_grad` (`id_grad`),
  ADD KEY `id_ulica` (`id_ulica`);

--
-- Indexes for table `grad`
--
ALTER TABLE `grad`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `korisnik`
--
ALTER TABLE `korisnik`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_vrsta_kor` (`id_vrsta_kor`),
  ADD KEY `id_adresa` (`id_adresa`);

--
-- Indexes for table `ulica`
--
ALTER TABLE `ulica`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `usluga`
--
ALTER TABLE `usluga`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_vrsta_usluge` (`id_vrsta_usluge`);

--
-- Indexes for table `vrsta_korisnika`
--
ALTER TABLE `vrsta_korisnika`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vrsta_usluge`
--
ALTER TABLE `vrsta_usluge`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `zakazivanje`
--
ALTER TABLE `zakazivanje`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_korisnik` (`id_korisnik`);

--
-- Indexes for table `zakazivanje_usluge`
--
ALTER TABLE `zakazivanje_usluge`
  ADD KEY `zakazivanje_usluge_ibfk_1` (`id_usluga`),
  ADD KEY `zakazivanje_usluge_ibfk_2` (`id_zakazivanje`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `adresa`
--
ALTER TABLE `adresa`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `grad`
--
ALTER TABLE `grad`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `korisnik`
--
ALTER TABLE `korisnik`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `ulica`
--
ALTER TABLE `ulica`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `usluga`
--
ALTER TABLE `usluga`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `vrsta_korisnika`
--
ALTER TABLE `vrsta_korisnika`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `vrsta_usluge`
--
ALTER TABLE `vrsta_usluge`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `zakazivanje`
--
ALTER TABLE `zakazivanje`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `adresa`
--
ALTER TABLE `adresa`
  ADD CONSTRAINT `adresa_ibfk_1` FOREIGN KEY (`id_grad`) REFERENCES `grad` (`id`),
  ADD CONSTRAINT `adresa_ibfk_2` FOREIGN KEY (`id_ulica`) REFERENCES `ulica` (`id`);

--
-- Constraints for table `korisnik`
--
ALTER TABLE `korisnik`
  ADD CONSTRAINT `korisnik_ibfk_1` FOREIGN KEY (`id_vrsta_kor`) REFERENCES `vrsta_korisnika` (`id`),
  ADD CONSTRAINT `korisnik_ibfk_2` FOREIGN KEY (`id_adresa`) REFERENCES `adresa` (`id`);

--
-- Constraints for table `usluga`
--
ALTER TABLE `usluga`
  ADD CONSTRAINT `usluga_ibfk_1` FOREIGN KEY (`id_vrsta_usluge`) REFERENCES `vrsta_usluge` (`id`);

--
-- Constraints for table `zakazivanje`
--
ALTER TABLE `zakazivanje`
  ADD CONSTRAINT `zakazivanje_ibfk_1` FOREIGN KEY (`id_korisnik`) REFERENCES `korisnik` (`id`);

--
-- Constraints for table `zakazivanje_usluge`
--
ALTER TABLE `zakazivanje_usluge`
  ADD CONSTRAINT `zakazivanje_usluge_ibfk_1` FOREIGN KEY (`id_usluga`) REFERENCES `usluga` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `zakazivanje_usluge_ibfk_2` FOREIGN KEY (`id_zakazivanje`) REFERENCES `zakazivanje` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
