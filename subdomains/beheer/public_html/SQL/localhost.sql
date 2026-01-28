-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Gegenereerd op: 07 dec 2024 om 11:02
-- Serverversie: 8.0.26
-- PHP-versie: 8.1.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `boekhouding`
--
CREATE DATABASE IF NOT EXISTS `boekhouding` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `boekhouding`;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `boekhoud_entries`
--
USE windelsbe_db;
CREATE TABLE `boekhoud_entries` (
  `id` int NOT NULL,
  `type` enum('cashbook','receipts','purchases','sales','pettycash') NOT NULL,
  `date` date NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `vat_rate` decimal(5,2) DEFAULT '0.00',
  `description` text NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `boekhoud_entries`
--

INSERT INTO `boekhoud_entries` (`id`, `type`, `date`, `amount`, `vat_rate`, `description`, `created_at`) VALUES
(1, 'sales', '2023-07-03', 3.10, 6.00, 'Verse groenten - Dagontvangsten ', '2024-08-29 12:30:00'),
(2, 'sales', '2023-07-19', 1.73, 6.00, 'Verse groenten - Dagontvangsten ', '2024-08-29 12:30:01'),
(3, 'sales', '2023-08-06', 9.86, 6.00, 'Verse groenten - Dagontvangsten ', '2024-08-29 12:30:01'),
(4, 'sales', '2023-08-11', 6.54, 6.00, 'Verse groenten - Dagontvangsten ', '2024-08-29 12:30:01'),
(5, 'sales', '2023-08-14', 6.00, 6.00, 'Verse groenten - Dagontvangsten ', '2024-08-29 12:30:01'),
(6, 'sales', '2023-08-17', 16.67, 6.00, 'Verse groenten - Dagontvangsten ', '2024-08-29 12:30:01'),
(7, 'sales', '2023-08-18', 11.15, 6.00, 'Verse groenten - Dagontvangsten ', '2024-08-29 12:30:01'),
(8, 'sales', '2023-08-18', 1.20, 6.00, 'Verse groenten - Dagontvangsten ', '2024-08-29 12:30:01'),
(9, 'sales', '2023-08-20', 18.67, 6.00, 'Verse groenten - Dagontvangsten ', '2024-08-29 12:30:01'),
(10, 'sales', '2023-08-27', 16.40, 6.00, 'Deco - Dagontvangsten ', '2024-08-29 12:30:01'),
(11, 'sales', '2023-08-29', 9.75, 6.00, 'Verse groenten - Dagontvangsten ', '2024-08-29 12:30:01'),
(12, 'sales', '2023-09-05', 20.10, 6.00, 'Verse groenten - Dagontvangsten ', '2024-08-29 12:30:01'),
(13, 'sales', '2023-09-06', 8.32, 6.00, 'Verse groenten - Dagontvangsten ', '2024-08-29 12:30:02'),
(14, 'sales', '2023-09-10', 3.95, 6.00, 'Verse groenten - Dagontvangsten ', '2024-08-29 12:30:02'),
(15, 'sales', '2023-09-12', 6.20, 6.00, 'Verse groenten - Dagontvangsten ', '2024-08-29 12:30:02'),
(16, 'sales', '2023-09-13', 6.44, 6.00, 'Verse groenten - Dagontvangsten ', '2024-08-29 12:30:02'),
(17, 'sales', '2023-09-23', 10.40, 6.00, 'Verse groenten - Dagontvangsten ', '2024-08-29 12:30:02'),
(18, 'sales', '2023-09-24', 2.82, 6.00, 'Verse groenten - Dagontvangsten ', '2024-08-29 12:30:02'),
(19, 'sales', '2023-10-01', 24.17, 6.00, 'algemeen - Dagontvangsten ', '2024-08-29 12:30:02'),
(20, 'sales', '2023-10-01', 4.99, 6.00, 'Verse groenten - Dagontvangsten ', '2024-08-29 12:30:02'),
(21, 'sales', '2023-10-05', 3.00, 6.00, 'Verse groenten - Dagontvangsten ', '2024-08-29 12:30:02'),
(22, 'sales', '2023-10-06', 3.00, 6.00, 'Verse groenten - Dagontvangsten ', '2024-08-29 12:30:02'),
(23, 'sales', '2023-10-08', 2.03, 6.00, 'Verse groenten - Dagontvangsten ', '2024-08-29 12:30:02'),
(24, 'sales', '2023-10-09', 56.41, 6.00, 'Deco - Dagontvangsten ', '2024-08-29 12:30:02'),
(25, 'sales', '2023-10-15', 21.00, 6.00, 'Verse groenten - Dagontvangsten ', '2024-08-29 12:30:02'),
(26, 'sales', '2023-10-18', 21.01, 6.00, 'kerst - Dagontvangsten ', '2024-08-29 12:30:02'),
(27, 'sales', '2023-10-19', 18.90, 6.00, 'kerst - Dagontvangsten ', '2024-08-29 12:30:02'),
(28, 'sales', '2023-10-19', 51.03, 6.00, 'kerst - Dagontvangsten ', '2024-08-29 12:30:03'),
(29, 'sales', '2023-10-19', 34.03, 6.00, 'kerst - Dagontvangsten ', '2024-08-29 12:30:03'),
(30, 'sales', '2023-10-23', 20.00, 6.00, 'Verse groenten - Dagontvangsten ', '2024-08-29 12:30:03'),
(31, 'sales', '2023-10-23', 3.40, 6.00, 'Verse groenten - Dagontvangsten ', '2024-08-29 12:30:03'),
(32, 'sales', '2023-11-05', 6.96, 6.00, 'Deco - Dagontvangsten ', '2024-08-29 12:30:03'),
(33, 'sales', '2023-11-12', 58.00, 6.00, 'label my light - Dagontvangsten ', '2024-08-29 12:30:03'),
(34, 'sales', '2023-11-18', 1.89, 6.00, 'kerst - Dagontvangsten ', '2024-08-29 12:30:03'),
(35, 'sales', '2023-11-27', 94.50, 6.00, 'kerst - Dagontvangsten ', '2024-08-29 12:30:03'),
(36, 'sales', '2023-11-30', 19.00, 6.00, 'label my light - Dagontvangsten ', '2024-08-29 12:30:03'),
(37, 'sales', '2023-12-23', 28.91, 6.00, 'Deco - Dagontvangsten ', '2024-08-29 12:30:03'),
(38, 'sales', '2023-12-23', 25.85, 6.00, 'algemeen - Dagontvangsten ', '2024-08-29 12:30:03'),
(39, 'sales', '2024-01-19', 28.00, 6.00, 'algemeen - Dagontvangsten ', '2024-08-29 12:30:03'),
(40, 'sales', '2024-02-26', 6.00, 6.00, 'Gompie - Dagontvangsten ', '2024-08-29 12:30:03'),
(41, 'sales', '2024-04-13', 33.98, 6.00, 'algemeen - Dagontvangsten ', '2024-08-29 12:30:03'),
(42, 'sales', '2024-05-18', 39.35, 6.00, 'Deco - Dagontvangsten ', '2024-08-29 12:30:03'),
(43, 'sales', '2024-05-26', 29.33, 6.00, 'algemeen - Dagontvangsten ', '2024-08-29 12:30:03'),
(44, 'sales', '2024-05-29', 15.13, 6.00, 'Deco - Dagontvangsten ', '2024-08-29 12:30:04'),
(45, 'sales', '2024-06-16', 1.00, 6.00, 'Deco - Dagontvangsten ', '2024-08-29 12:30:04'),
(46, 'sales', '2024-07-13', 5.05, 6.00, 'Deco - Dagontvangsten ', '2024-08-29 12:30:04'),
(47, 'sales', '2024-07-13', 11.60, 6.00, 'Deco - Dagontvangsten ', '2024-08-29 12:30:04'),
(48, 'sales', '2024-07-13', 0.40, 6.00, 'Deco - Dagontvangsten ', '2024-08-29 12:30:04'),
(49, 'sales', '2024-07-15', 2.50, 6.00, 'Verse groenten - Dagontvangsten ', '2024-08-29 12:30:04'),
(50, 'sales', '2024-07-22', 4.05, 6.00, 'Deco - Dagontvangsten ', '2024-08-29 12:30:04'),
(51, 'sales', '2024-07-25', 30.40, 6.00, 'Deco - Dagontvangsten ', '2024-08-29 12:30:04'),
(52, 'sales', '2024-08-10', 3.20, 6.00, 'Verse groenten - Dagontvangsten ', '2024-08-29 12:30:04'),
(53, 'sales', '2024-08-20', 4.06, 6.00, 'Deco - Dagontvangsten ', '2024-08-29 12:30:04'),
(54, 'purchases', '2023-07-16', 189.95, 21.00, 'labelprinter - 123inkt.be B.V.', '2024-08-29 12:30:04'),
(55, 'purchases', '2023-07-16', 199.00, 21.00, 'Tablet voor kassa - Krefel', '2024-08-29 12:30:04'),
(56, 'purchases', '2023-07-20', 28.85, 21.00, 'Epoxyhars/mallen - ItsOkay', '2024-08-29 12:30:04'),
(57, 'purchases', '2023-07-23', 14.29, 21.00, 'Mallen - Allie express', '2024-08-29 12:30:05'),
(58, 'purchases', '2023-07-24', 79.35, 21.00, 'Verpakking - Rotim.nl', '2024-08-29 12:30:05'),
(59, 'purchases', '2023-08-20', 77.10, 21.00, 'reclame/ promo materiaal - Vistaprint', '2024-08-29 12:30:05'),
(60, 'purchases', '2023-09-25', 268.75, 21.00, 'Geurkaarsen - Label my light', '2024-08-29 12:30:05'),
(61, 'purchases', '2023-10-01', 38.97, 21.00, 'reclame/ promo materiaal - Vistaprint', '2024-08-29 12:30:05'),
(62, 'purchases', '2023-10-20', 75.04, 21.00, 'kerst decoratie - action', '2024-08-29 12:30:05'),
(63, 'purchases', '2023-11-04', 30.00, 21.00, 'tafels - jysk', '2024-08-29 12:30:05'),
(64, 'purchases', '2023-11-20', 50.78, 21.00, 'gompie - gompie', '2024-08-29 12:30:05'),
(65, 'purchases', '2023-12-01', 77.42, 21.00, 'gompie - gompie', '2024-08-29 12:30:05'),
(66, 'purchases', '2023-12-01', 76.17, 21.00, 'De Verpakkingswinkel - De Verpakkingswinkel', '2024-08-29 12:30:05'),
(67, 'purchases', '2024-01-29', 15.69, 21.00, 'dagontvangstenboek - Ava', '2024-08-29 12:30:05'),
(68, 'purchases', '2024-03-03', 36.43, 21.00, 'kaarsvet - De kaarswinkel', '2024-08-29 12:30:05'),
(69, 'purchases', '2024-07-08', 35.39, 21.00, 'dagontvangstenboek - Ava', '2024-08-29 12:30:06'),
(70, 'pettycash', '2023-07-15', 261.07, 21.00, 'Website/onderhoud - Combell Nv', '2024-08-29 12:30:06'),
(71, 'pettycash', '2023-07-21', 130.00, 21.00, 'Opstartkosten vergoeding  van de zaak - Vessco BVBA', '2024-08-29 12:30:06'),
(72, 'pettycash', '2023-07-26', 8.80, 21.00, 'Verzendkosten pakketen - Myparcel', '2024-08-29 12:30:06'),
(73, 'pettycash', '2023-08-01', 0.05, 21.00, 'transactie en bijdrage kosten - Sumup', '2024-08-29 12:30:06'),
(74, 'pettycash', '2023-08-31', 3.25, 21.00, 'rekening bijdrage - kbc', '2024-08-29 12:30:06'),
(75, 'pettycash', '2023-09-01', 0.05, 21.00, 'transactie en bijdrage kosten - Sumup', '2024-08-29 12:30:06'),
(76, 'pettycash', '2023-09-07', 10.12, 21.00, 'transactie en bijdrage kosten - Sumup', '2024-08-29 12:30:06'),
(77, 'pettycash', '2023-09-29', 3.25, 21.00, 'rekening bijdrage - kbc', '2024-08-29 12:30:06'),
(78, 'pettycash', '2023-10-01', 0.05, 21.00, 'transactie en bijdrage kosten - Sumup', '2024-08-29 12:30:06'),
(79, 'pettycash', '2023-10-08', 194.64, 21.00, 'transactie en bijdrage kosten - Sumup', '2024-08-29 12:30:06'),
(80, 'pettycash', '2023-10-08', 199.09, 21.00, 'Sociale bijdrage kwartaal 3-4 - Acerta', '2024-08-29 12:30:07'),
(81, 'pettycash', '2023-10-23', 8.80, 21.00, 'Verzendkosten pakketen - Myparcel', '2024-08-29 12:30:07'),
(82, 'pettycash', '2023-10-31', 0.07, 21.00, 'transactie en bijdrage kosten - payconiq', '2024-08-29 12:30:07'),
(83, 'pettycash', '2023-10-31', 0.00, 21.00, 'transactiekosten - mollie', '2024-08-29 12:30:07'),
(84, 'pettycash', '2023-10-31', 1.04, 21.00, ' - ', '2024-08-29 12:30:07'),
(85, 'pettycash', '2023-10-31', 0.00, 21.00, ' - ', '2024-08-29 12:30:07'),
(86, 'pettycash', '2023-10-31', 3.25, 21.00, 'rekening bijdrage - kbc', '2024-08-29 12:30:07'),
(87, 'pettycash', '2023-11-30', 0.00, 21.00, 'rekening bijdrage - kbc', '2024-08-29 12:30:07'),
(88, 'pettycash', '2023-11-30', 3.25, 21.00, 'rekening bijdrage - kbc', '2024-08-29 12:30:07'),
(89, 'pettycash', '2023-12-01', 0.98, 21.00, 'transactie en bijdrage kosten - Sumup', '2024-08-29 12:30:07'),
(90, 'pettycash', '2023-12-29', 3.25, 21.00, 'rekening bijdrage - kbc', '2024-08-29 12:30:07'),
(91, 'pettycash', '2024-01-31', 3.25, 21.00, 'rekening bijdrage - kbc', '2024-08-29 12:30:08'),
(92, 'pettycash', '2024-02-12', 0.00, 21.00, ' - ', '2024-08-29 12:30:08'),
(93, 'pettycash', '2024-02-12', 9.32, 21.00, 'Verzendkosten pakketen - Myparcel', '2024-08-29 12:30:08'),
(94, 'pettycash', '2024-02-29', 3.75, 21.00, 'rekening bijdrage - kbc', '2024-08-29 12:30:08'),
(95, 'pettycash', '2024-02-29', 3.25, 21.00, 'rekening bijdrage - kbc', '2024-08-29 12:30:08'),
(96, 'pettycash', '2024-03-11', 78.05, 21.00, 'diesel auto - Q8', '2024-08-29 12:30:08'),
(97, 'pettycash', '2024-03-20', 19.12, 21.00, 'Advertentie\'s - Meta Platforms, Inc', '2024-08-29 12:30:08'),
(98, 'pettycash', '2024-03-28', 3.11, 21.00, 'rekening bijdrage - kbc', '2024-08-29 12:30:08'),
(99, 'pettycash', '2024-04-01', 11.00, 21.00, 'Advertentie\'s - Meta Platforms, Inc', '2024-08-29 12:30:08'),
(100, 'pettycash', '2024-04-15', 17.81, 21.00, 'Verzendkosten pakketen - Myparcel', '2024-08-29 12:30:08'),
(101, 'pettycash', '2024-04-20', 95.88, 21.00, 'Sociale bijdrage kwartaal 1 - Acerta', '2024-08-29 12:30:08'),
(102, 'pettycash', '2024-04-28', 0.00, 21.00, 'Advertentie\'s - Meta Platforms, Inc', '2024-08-29 12:30:09'),
(103, 'pettycash', '2024-04-28', 0.00, 21.00, 'Advertentie\'s - Meta Platforms, Inc', '2024-08-29 12:30:09'),
(104, 'pettycash', '2024-04-30', 0.55, 21.00, 'transactiekosten - mollie', '2024-08-29 12:30:09'),
(105, 'pettycash', '2024-04-30', 3.58, 21.00, 'rekening bijdrage - kbc', '2024-08-29 12:30:09'),
(106, 'pettycash', '2024-04-30', 2.17, 21.00, 'Advertentie\'s - Meta Platforms, Inc', '2024-08-29 12:30:09'),
(107, 'pettycash', '2024-04-30', 2.17, 21.00, 'Advertentie\'s - Meta Platforms, Inc', '2024-08-29 12:30:10'),
(108, 'pettycash', '2024-05-06', 20.82, 21.00, 'Abbonementenkosten - Payconiq', '2024-08-29 12:30:10'),
(109, 'pettycash', '2024-05-20', 19.12, 21.00, 'Advertentie\'s - Meta Platforms, Inc', '2024-08-29 12:30:10'),
(110, 'pettycash', '2024-05-20', 20.00, 21.00, 'Advertentie\'s - Meta Platforms, Inc', '2024-08-29 12:30:10'),
(111, 'pettycash', '2024-05-24', 49.73, 21.00, 'Website/onderhoud - Combell Nv', '2024-08-29 12:30:10'),
(112, 'pettycash', '2024-05-25', 256.63, 21.00, 'Website/onderhoud - Combell Nv', '2024-08-29 12:30:10'),
(113, 'pettycash', '2024-05-27', 9.32, 21.00, 'Verzendkosten pakketen - Myparcel', '2024-08-29 12:30:10'),
(114, 'pettycash', '2024-05-27', 95.88, 21.00, 'Sociale bijdrage kwartaal 2 - Acerta', '2024-08-29 12:30:10'),
(115, 'pettycash', '2024-05-31', 1.16, 21.00, 'transactiekosten - mollie', '2024-08-29 12:30:10'),
(116, 'pettycash', '2024-05-31', 3.75, 21.00, 'rekening bijdrage - kbc', '2024-08-29 12:30:10'),
(117, 'pettycash', '2024-05-31', 9.01, 21.00, 'Advertentie\'s - Meta Platforms, Inc', '2024-08-29 12:30:11'),
(118, 'pettycash', '2024-06-25', 16.53, 21.00, 'Advertentie\'s - Meta Platforms, Inc', '2024-08-29 12:30:11'),
(119, 'pettycash', '2024-06-28', 3.10, 21.00, 'rekening bijdrage - kbc', '2024-08-29 12:30:11'),
(120, 'pettycash', '2024-06-28', 16.53, 21.00, 'Advertentie\'s - Meta Platforms, Inc', '2024-08-29 12:30:11'),
(121, 'pettycash', '2024-06-30', 9.31, 21.00, 'Advertentie\'s - Meta Platforms, Inc', '2024-08-29 12:30:11'),
(122, 'pettycash', '2024-07-02', 18.18, 21.00, 'Advertentie\'s - Meta Platforms, Inc', '2024-08-29 12:30:11'),
(123, 'pettycash', '2024-07-06', 22.31, 21.00, 'Advertentie\'s - Meta Platforms, Inc', '2024-08-29 12:30:11'),
(124, 'pettycash', '2024-07-10', 25.55, 21.00, 'Advertentie\'s - Meta Platforms, Inc', '2024-08-29 12:30:11'),
(125, 'pettycash', '2024-07-12', 27.27, 21.00, 'Advertentie\'s - Meta Platforms, Inc', '2024-08-29 12:30:11'),
(126, 'pettycash', '2024-07-16', 33.06, 21.00, 'Advertentie\'s - Meta Platforms, Inc', '2024-08-29 12:30:11'),
(127, 'pettycash', '2024-07-16', 0.57, 21.00, 'transactie en bijdrage kosten - Sumup', '2024-08-29 12:30:12'),
(128, 'pettycash', '2024-07-19', 242.73, 21.00, 'FAVV-Heffing 2024 - FAVV Federaal Voedselagentschap', '2024-08-29 12:30:12'),
(129, 'pettycash', '2024-07-23', 41.32, 21.00, 'Advertentie\'s - Meta Platforms, Inc', '2024-08-29 12:30:12'),
(130, 'pettycash', '2024-07-29', 41.32, 21.00, 'Advertentie\'s - Meta Platforms, Inc', '2024-08-29 12:30:12'),
(131, 'pettycash', '2024-07-31', 43.50, 21.00, 'Advertentie\'s - Meta Platforms, Inc', '2024-08-29 12:30:12'),
(132, 'pettycash', '2024-08-12', 50.00, 21.00, 'Advertentie\'s - Meta Platforms, Inc', '2024-08-29 12:30:12'),
(133, 'pettycash', '2024-08-19', 50.00, 21.00, 'Advertentie\'s - Meta Platforms, Inc', '2024-08-29 12:30:12');

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `boekhoud_entries`
--
ALTER TABLE `boekhoud_entries`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT voor geëxporteerde tabellen
--

--
-- AUTO_INCREMENT voor een tabel `boekhoud_entries`
--
ALTER TABLE `boekhoud_entries`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=134;
--
-- Database: `company_admin`
--
CREATE DATABASE IF NOT EXISTS `company_admin` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `company_admin`;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `admins_user`
--

CREATE TABLE `admins_user` (
  `adminuser_id` int NOT NULL,
  `admin_id` int NOT NULL,
  `adminuser_email` varchar(255) NOT NULL,
  `adminuser_phone` varchar(255) NOT NULL,
  `adminuser_mobile` varchar(255) NOT NULL,
  `adminuser_adres` varchar(255) NOT NULL,
  `adminuser_number` varchar(255) NOT NULL,
  `adminuser_bus` varchar(255) NOT NULL,
  `adminuser_postbus` varchar(255) NOT NULL,
  `adminuser_city` varchar(255) NOT NULL,
  `adminuser_country` varchar(255) NOT NULL,
  `adminuser_citizenship` varchar(255) NOT NULL,
  `adminuser_nationalnumber` varchar(255) NOT NULL,
  `adminuser_drivinglicence` varchar(255) NOT NULL,
  `adminuser_accountnumber` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Gegevens worden geëxporteerd voor tabel `admins_user`
--

INSERT INTO `admins_user` (`adminuser_id`, `admin_id`, `adminuser_email`, `adminuser_phone`, `adminuser_mobile`, `adminuser_adres`, `adminuser_number`, `adminuser_bus`, `adminuser_postbus`, `adminuser_city`, `adminuser_country`, `adminuser_citizenship`, `adminuser_nationalnumber`, `adminuser_drivinglicence`, `adminuser_accountnumber`) VALUES
(1, 1, 'andywindels5@gmail.com', '+3211753319', '+32497107717', 'Beukenlaan', '8', '', '3930', 'Hamont-Achel', 'België', 'gehuwd', '91.05.05-173.71', 'ja', 'BE90 7370 1036 8232'),
(2, 2, 'andywindels5@gmail.com', '+3211753319', '+32497107717', 'Beukenlaan', '8', '', '3930', 'Hamont-Achel', 'België', 'gehuwd', '91.05.05-173.71', 'ja', 'BE90 7370 1036 8232'),
(3, 3, 'henricools@hotmail.com', '+3211753319', '+32493687073', 'Beukenlaan ', '8', '', '3930 ', 'Hamont-Achel', 'België', 'gehuwd', '90-11-28-000-00', 'ja', 'BE11 7360 5942 0648'),
(4, 4, 'kyani.windels1@gmail.com', '', '+32491636870', 'Mulderstraat ', '2', '', '9968', 'Assenede', 'België', 'ongehuwd', '06.02.23-127.31', 'nee', 'BE77 7350 5390 8342'),
(5, 5, 'frankywindels@gmail.com', '', '+32497132475', 'Mulderstraat ', '2', '', '9968', 'Assenede', 'België', 'gehuwd', '63.12.03-000-00', 'ja', 'BE85 7374 3010 4006'),
(6, 6, 'frankywindels@gmail.com', '', '+32497132475', 'Mulderstraat ', '2', '', '9968', 'Assenede', 'België', 'gehuwd', '70-12-16-000-00', 'ja', 'BE40 7374 3026 4963');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `admins_userfunctie`
--

CREATE TABLE `admins_userfunctie` (
  `admins_functieuserid` int NOT NULL,
  `admins_userid` int NOT NULL,
  `admin_functieid` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Gegevens worden geëxporteerd voor tabel `admins_userfunctie`
--

INSERT INTO `admins_userfunctie` (`admins_functieuserid`, `admins_userid`, `admin_functieid`) VALUES
(1, 1, 1),
(2, 2, 2),
(3, 3, 2),
(4, 4, 3),
(5, 5, 3),
(6, 6, 4);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `admins_workforce`
--

CREATE TABLE `admins_workforce` (
  `admin_id` int NOT NULL,
  `card_id` int NOT NULL,
  `admin_username` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `admin_name` varchar(255) NOT NULL,
  `admin_firstname` varchar(255) NOT NULL,
  `admin_email` varchar(255) NOT NULL,
  `admin_pass` varchar(255) NOT NULL,
  `admin_image` text NOT NULL,
  `admin_filliaal` varchar(255) NOT NULL,
  `admin_employed` varchar(255) NOT NULL,
  `admin_offduty` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `admin_contract_period` varchar(255) NOT NULL,
  `admins_actief` varchar(255) NOT NULL,
  `admin_role` varchar(255) NOT NULL,
  `last_activity` timestamp NULL DEFAULT NULL,
  `session_id` varchar(255) DEFAULT NULL,
  `2fa_enabled` tinyint(1) DEFAULT '0',
  `2fa_secret` varchar(64) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Gegevens worden geëxporteerd voor tabel `admins_workforce`
--

INSERT INTO `admins_workforce` (`admin_id`, `card_id`, `admin_username`, `admin_name`, `admin_firstname`, `admin_email`, `admin_pass`, `admin_image`, `admin_filliaal`, `admin_employed`, `admin_offduty`, `admin_contract_period`, `admins_actief`, `admin_role`, `last_activity`, `session_id`, `2fa_enabled`, `2fa_secret`) VALUES
(1, 39308001, 'admin', 'Windels', 'Andy', 'admin@windelsgreen-decoresin.com', '$2y$10$O0SI58s9uBoPio.Oc0F6r.rNn1rWv71pCKKjr9yUkrlG1FnVVJ3vG', 'df3dd906-e04a-4890-994f-cb58ff9ca9a5.jpg', '1', '2023-07-01', '', 'onbepaald duur', '0', 'Admin', '2024-12-06 16:50:31', 'k7bb6mshhfrvu3p64bddq8q3dr', 1, 'FQIWCN6G3PSUHSKW'),
(2, 39308011, 'Winkel_andy', 'Windels', 'Andy', 'admin@windelsgreen-decoresin.com', '$2y$10$O0SI58s9uBoPio.Oc0F6r.rNn1rWv71pCKKjr9yUkrlG1FnVVJ3vG', 'df3dd906-e04a-4890-994f-cb58ff9ca9a5.jpg', '2', '2023-07-01', '', 'onbepaalde duur', '0', 'Winkelmedewerker', '2024-11-24 21:16:49', NULL, 0, NULL),
(3, 39308002, 'Winkel_henri', 'Cools', 'Henri', 'medewerkers@windelsgreen-decoresin.com', '$2y$10$nPhXlz95Uq62jeBZSxIV0ua.1adZtim/InPs0l/m119BIc.ZEQZ.C', '79376465_2220143814954050_3286634211850584064_n.jpg', '2', '2023-07-01', '2025-06-30', 'onbepaald duur', '0', 'Winkelmedewerker', NULL, NULL, 0, NULL),
(4, 39308003, 'Winkel_kyani', 'Windels', 'Kyani', 'medewerkers@windelsgreen-decoresin.com', '$2y$10$DeZJ/B5u7eCqivtGjNjvnu5mgEZmRgyB6/aA5MRKfpesix05N030y', '87497305_509890549903385_5162983287487987712_n.jpg', '3', '2023-07-01', '2024-10-31', '1 jaar', '0', 'Kantoormedewerker', '2024-11-23 12:26:23', NULL, 0, NULL),
(5, 39308004, 'Winkel_franky', 'Windels', 'Franky', 'medewerkers@windelsgreen-decoresin.com', '$2y$10$DeZJ/B5u7eCqivtGjNjvnu5mgEZmRgyB6/aA5MRKfpesix05N030y', '1624600758225.jpeg', '3', '2023-07-01', '2025-06-30', 'onbepaald duur', '0', 'Winkelmedewerker', '2024-12-06 14:06:40', 'm0918lubqticlqeqtdlsqpgbbh', 1, '5PWQDJL2BJNOKYXA'),
(6, 39308004, 'Winkel_diane', 'De smedt', 'Diane', 'medewerkers@windelsgreen-decoresin.com', '$2y$10$DeZJ/B5u7eCqivtGjNjvnu5mgEZmRgyB6/aA5MRKfpesix05N030y', '162725339_10221241590687591_8321872301754379547_n.jpg', '3', '2023-07-01', '2025-06-30', 'onbepaald duur', '0', 'Winkelmedewerker', '2024-12-05 15:38:59', 'odafv2tb9pcred4nm58gqaefps', 0, NULL);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `alerts`
--

CREATE TABLE `alerts` (
  `id` int NOT NULL,
  `type` enum('info','warning','danger') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `icon` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `start_datetime` datetime NOT NULL,
  `end_datetime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Gegevens worden geëxporteerd voor tabel `alerts`
--

INSERT INTO `alerts` (`id`, `type`, `icon`, `title`, `message`, `start_datetime`, `end_datetime`) VALUES
(1, 'warning', 'fas fa-exclamation-circle', 'Belangrijk: Tijdelijke Onbeschikbaarheid van het Portaal', '<p>Van <em><strong>2 Mei. 2024 tot 31 Dec. 2025 tussen 06:00 en 18:00</strong></em> zal dit portaal <strong><em>soms niet beschikbaar</em></strong> zijn wegens werken aan het <em><strong>elektriciteitsnet/internet</strong></em>.</p>\n<p>Daarom zal de server soms tijdelijk onderbroken zijn.</p>\n<p><strong>Sorry voor het ongemak.</strong></p>', '2024-05-02 07:00:00', '2025-12-31 23:59:59'),
(2, 'info', 'fas fa-info-circle', 'Nieuw: Productinformatie Bijwerken', 'Bij het invoegen of bewerken van products is het essentieel om een volledige beschrijving toe te voegen. Deze beschrijving moet de volgende details bevatten:</p>\r\n    <ul>\r\n        <li>Lengte</li>\r\n        <li>Breedte</li>\r\n        <li>Hoogte</li>\r\n        <li>Gewicht</li>\r\n        <li>Materiaal</li>\r\n        <li>Kleur</li>\r\n        <li>Andere relevante specificaties</li>\r\n    </ul>\r\n    <p>Deze informatie is cruciaal voor een duidelijke en professionele presentatie op onze webshop, in de winkel, of tijdens marktevenementen. Zorg ervoor dat alle velden nauwkeurig en volledig worden ingevuld om onze klanten van de juiste productinformatie te voorzien.</p>\r\n    <p><strong>Dank voor je aandacht en medewerking!</strong></p>', '2024-09-13 15:56:00', '2024-09-20 21:00:00'),
(3, 'info', 'fas fa-info-circle', 'Belangrijk: Tijdelijke Onbeschikbaarheid van het Portaal:', '<p><span style=\"color: #0c5460; font-family: \'Open Sans\', sans-serif; font-size: 16px; background-color: #d1ecf1;\">Het portaal zal even&nbsp;</span><span style=\"box-sizing: border-box; font-weight: bolder; color: #0c5460; font-family: \'Open Sans\', sans-serif; font-size: 16px;\"><em style=\"box-sizing: border-box;\">Niet beschikbaar</em></span><span style=\"color: #0c5460; font-family: \'Open Sans\', sans-serif; font-size: 16px; background-color: #d1ecf1;\">&nbsp;zijn op&nbsp;</span><span style=\"box-sizing: border-box; font-weight: bolder; color: #0c5460; font-family: \'Open Sans\', sans-serif; font-size: 16px;\"><em style=\"box-sizing: border-box;\">31 Okt. 2024 tussen 10:00 en 18:00</em></span><span style=\"color: #0c5460; font-family: \'Open Sans\', sans-serif; font-size: 16px; background-color: #d1ecf1;\">.</span><br style=\"box-sizing: border-box; color: #0c5460; font-family: \'Open Sans\', sans-serif; font-size: 16px;\" /><span style=\"color: #0c5460; font-family: \'Open Sans\', sans-serif; font-size: 16px; background-color: #d1ecf1;\">Dit wegens nieuwe Update.</span></p>', '2024-10-29 00:00:00', '2024-10-31 10:43:00'),
(4, 'info', 'fas fa-info-circle', 'Wegen werken', '<p><span style=\"color: #0c5460; font-family: \'Open Sans\', sans-serif; font-size: 16px;\">Wegens wegen werken kan het zijn dat we de winkel voor 1 tot 2 dagen moeten sluiten.</span></p>', '2024-10-28 00:00:00', '2024-11-08 08:59:00');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `break_logs`
--

CREATE TABLE `break_logs` (
  `id` int NOT NULL,
  `time_log_id` int NOT NULL,
  `break_start` datetime DEFAULT NULL,
  `break_end` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `total_break_minutes` decimal(5,2) DEFAULT NULL,
  `modified_by_admin` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Gegevens worden geëxporteerd voor tabel `break_logs`
--

INSERT INTO `break_logs` (`id`, `time_log_id`, `break_start`, `break_end`, `created_at`, `total_break_minutes`, `modified_by_admin`) VALUES
(1, 4, '2024-09-05 12:12:51', '2024-09-05 13:08:43', '2024-09-05 11:08:43', 0.93, 0),
(4, 5, '2024-09-06 15:30:51', '2024-09-06 17:05:43', '2024-09-06 13:30:51', 1.58, 0),
(5, 6, '2024-09-07 12:30:51', '2024-09-07 13:05:43', '2024-09-07 10:30:51', 0.58, 0),
(6, 10, '2024-09-12 13:53:00', '2024-09-12 15:12:00', '2024-09-12 11:53:58', 1.32, 0),
(7, 11, '2024-09-13 12:17:49', '2024-09-13 12:42:56', '2024-09-13 10:17:49', 0.42, 0),
(8, 12, '2024-09-14 12:15:00', '2024-09-14 13:07:00', '2024-09-14 11:11:00', 0.87, 0),
(9, 13, '2024-09-15 12:00:00', '2024-09-15 12:30:00', '2024-09-15 11:01:59', 0.50, 0),
(10, 14, '2024-09-15 12:00:00', '2024-09-15 13:00:00', '2024-09-15 11:58:07', 1.00, 0),
(11, 19, '2024-09-19 12:15:00', '2024-09-19 12:45:00', '2024-09-19 10:15:53', 0.50, 0),
(12, 19, '2024-09-19 19:15:24', '2024-09-19 19:49:46', '2024-09-19 17:15:24', 0.57, 0),
(13, 22, '2024-09-20 14:13:47', '2024-09-20 14:59:57', '2024-09-20 12:13:47', 0.77, 0),
(14, 22, '2024-09-20 18:30:47', '2024-09-20 18:45:57', '2024-09-20 16:30:47', 0.25, 0),
(15, 23, '2024-09-21 12:30:00', '2024-09-21 13:04:00', '2024-09-21 13:33:53', 0.57, 0),
(16, 24, '2024-09-22 12:15:00', '2024-09-22 12:49:00', '2024-09-23 05:37:05', 0.57, 0),
(17, 28, '2024-09-26 12:10:00', '2024-09-26 12:35:00', '2024-09-26 13:35:45', 25.00, 0),
(18, 28, '2024-09-26 17:11:00', '2024-09-26 17:20:00', '2024-09-26 15:32:20', 9.00, 0),
(19, 32, '2024-09-28 12:00:00', '2024-09-28 12:29:00', '2024-09-29 09:35:22', 0.29, 1),
(20, 31, '2024-09-28 12:00:00', '2024-09-28 12:31:00', '2024-09-29 09:43:45', 31.00, 0),
(21, 36, '2024-10-03 12:21:00', '2024-10-03 12:55:00', '2024-10-04 06:23:26', 34.00, 0),
(22, 36, '2024-10-03 18:00:00', '2024-10-04 18:33:00', '2024-10-04 06:24:04', 33.00, 0),
(23, 37, '2024-10-05 12:00:00', '2024-10-05 12:30:00', '2024-10-06 07:45:22', 30.00, 0),
(24, 41, '2024-10-10 12:00:00', '2024-10-10 13:00:00', '2024-10-10 20:28:14', 60.00, 0),
(25, 41, '2024-10-10 18:00:00', '2024-10-10 19:00:00', '2024-10-10 20:28:57', 60.00, 0),
(26, 42, '2024-10-11 12:10:00', '2024-10-11 12:47:00', '2024-10-11 16:39:12', 37.00, 0),
(27, 42, '2024-10-11 18:39:00', '2024-10-11 19:07:00', '2024-10-11 16:39:20', 28.00, 1),
(28, 43, '2024-10-12 12:10:00', '2024-10-12 12:40:00', '2024-10-12 17:16:17', 30.00, 1),
(29, 46, '2024-10-17 12:34:00', '2024-10-17 13:07:00', '2024-10-18 16:39:20', 33.00, 0),
(30, 48, '2024-10-18 12:44:00', '2024-10-18 13:27:00', '2024-10-18 16:40:51', 43.00, 0),
(31, 46, '2024-10-17 18:43:00', '2024-10-17 19:20:00', '2024-10-18 16:43:52', 37.00, 0),
(32, 48, '2024-10-18 18:48:20', '2024-10-18 19:18:34', '2024-10-18 16:48:20', 30.00, 0),
(33, 49, '2024-10-19 12:00:00', '2024-10-19 12:31:00', '2024-10-19 17:13:32', 31.00, 0),
(34, 57, '2024-10-21 09:55:58', '2024-10-21 10:05:51', '2024-10-21 07:55:58', 9.00, 0),
(35, 57, '2024-10-21 10:20:40', '2024-10-21 10:24:28', '2024-10-21 08:20:40', 3.00, 0),
(36, 58, '2024-10-21 09:55:58', '2024-10-21 10:05:51', '2024-10-21 07:55:58', 9.00, 0),
(37, 58, '2024-10-21 10:20:40', '2024-10-21 10:24:28', '2024-10-21 08:20:40', 3.00, 0),
(38, 77, '2024-11-01 11:04:28', '2024-11-01 14:45:34', '2024-11-01 13:45:28', 0.00, 0),
(39, 79, '2024-11-02 10:28:13', '2024-11-02 10:39:13', '2024-11-02 09:28:13', 11.00, 0),
(40, 79, '2024-11-02 11:43:17', '2024-11-02 14:01:15', '2024-11-02 10:43:17', 137.00, 0),
(41, 82, '2024-11-08 12:00:20', '2024-11-08 12:28:05', '2024-11-08 11:00:20', 27.00, 0),
(42, 82, '2024-11-08 13:24:52', '2024-11-08 13:37:59', '2024-11-08 12:24:52', 13.00, 0),
(43, 82, '2024-11-08 14:13:36', '2024-11-08 14:21:33', '2024-11-08 13:13:36', 7.00, 0),
(44, 82, '2024-11-08 14:38:48', '2024-11-08 14:55:39', '2024-11-08 13:38:48', 16.00, 0);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `buttons`
--

CREATE TABLE `buttons` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `category` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `position` int NOT NULL DEFAULT '0',
  `url` varchar(255) NOT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `visible` tinyint(1) NOT NULL DEFAULT '1',
  `display_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Gegevens worden geëxporteerd voor tabel `buttons`
--

INSERT INTO `buttons` (`id`, `name`, `category`, `position`, `url`, `icon`, `visible`, `display_date`) VALUES
(1, 'Kantoor', 'home', 0, '/kantoor/index.php', 'fas fa-building', 1, NULL),
(2, 'Winkel', 'home', 0, '/winkel/index.php', 'fas fa-store', 1, NULL),
(3, 'Favv', 'winkel', 4, '/voedselproblemen/index.php', 'fas fa-clipboard-list', 1, NULL),
(4, 'Magazijn', 'home', 2, '/magazijn/index.php', 'fas fa-warehouse', 1, NULL),
(5, 'Winkel Tellingen', 'winkel', 2, '/winkeltellingen/index.php', 'fas fa-chart-bar', 1, NULL),
(8, 'schappenplan', 'winkel', 3, '/schappenplan/index.php', 'fas fa-warehouse ', 1, NULL),
(10, 'klok in/out system rapport', 'kantoor', 4, '/user_reports.php', 'fas fa-clock', 1, NULL),
(11, 'uurrooster', 'kantoor', 5, '/admin_rooster.php', 'fas fa-calendar', 1, NULL),
(12, 'loonfiche maken', 'kantoor', 6, '/manage_payslips.php', 'fas fa-file-invoice-dollar', 1, NULL),
(13, 'Berichten', 'kantoor', 1, '/send_message.php', 'fa fa-envelope', 1, NULL),
(14, 'uurrooster', 'kantoor', 3, '/user_rooster.php', 'fa fa-calendar', 1, NULL),
(15, 'Gdpr wijzigen', 'kantoor', 10, '/gdpr_admin.php', 'fas fa-bullhorn', 1, NULL),
(17, 'Kassa Beheer', 'kantoor', 8, '/kantoor/cash_count.php', 'fas fa-cash-register', 1, NULL),
(18, 'Boekhouding', 'kantoor', 9, '/kantoor/boekhouding/index.php', 'fas fa-book', 1, NULL),
(19, 'Producten', 'winkel', 1, '/winkel/products.php', 'fas fa-tshirt', 1, NULL),
(20, 'Home', 'winkel', 0, '/index.php', 'fas fa-home', 1, NULL),
(21, 'Home', 'kantoor', 0, '/index.php', 'fas fa-home', 1, NULL),
(22, 'Home', 'magazijn', 0, '/index.php', 'fas fa-home', 1, NULL),
(23, 'Kalender', 'kantoor', 2, '/kantoor/calendar.php', 'fas fa-calendar', 1, NULL),
(24, 'Producten', 'magazijn', 0, '/magazijn/products.php', 'fas fa-tshirt', 1, NULL),
(25, 'Leveranciers', 'magazijn', 0, '/magazijn/manage_supplier.php', 'fas fa-dolly', 1, NULL),
(26, 'Bestellingen', 'winkel', 6, '/winkel/orders_view.php', 'fas fa-dolly', 1, NULL),
(27, 'Bestellingen', 'magazijn', 6, '/magazijn/warehouse_orders.php', 'fas fa-dolly', 1, NULL),
(28, 'Taken', 'kantoor', 7, '/kantoor/tasks_overview.php', 'fa fa-tasks', 1, NULL),
(29, 'Klantenbestand', 'kantoor', 10, '/kantoor/klantenbestand.php', 'fa fa-users', 1, NULL);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `button_roles`
--

CREATE TABLE `button_roles` (
  `button_id` int NOT NULL,
  `role` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Gegevens worden geëxporteerd voor tabel `button_roles`
--

INSERT INTO `button_roles` (`button_id`, `role`) VALUES
(1, 'Admin'),
(1, 'Filiaalmanager'),
(1, 'Winkelmedewerker'),
(2, 'Admin'),
(2, 'Filiaalmanager'),
(2, 'Jobstudend'),
(2, 'Kassamedewerker'),
(2, 'Stagiair'),
(2, 'Winkelmedewerker'),
(3, 'Admin'),
(3, 'Filiaalmanager'),
(3, 'Kassamedewerker'),
(3, 'Winkelmedewerker'),
(4, 'Admin'),
(4, 'Filiaalmanager'),
(4, 'Winkelmedewerker'),
(5, 'Admin'),
(5, 'Filiaalmanager'),
(5, 'Kassamedewerker'),
(5, 'Winkelmedewerker'),
(8, 'All'),
(10, 'Admin'),
(11, 'Admin'),
(12, 'Admin'),
(13, 'Admin'),
(14, 'Filiaalmanager'),
(14, 'Jobstudend'),
(14, 'Kassamedewerker'),
(14, 'Stagiair'),
(14, 'Winkelmedewerker'),
(15, 'Admin'),
(17, 'Admin'),
(17, 'Filiaalmanager'),
(17, 'Kassamedewerker'),
(17, 'Winkelmedewerker'),
(18, 'Admin'),
(18, 'Filiaalmanager'),
(18, 'Kassamedewerker'),
(18, 'Winkelmedewerker'),
(19, 'All'),
(20, 'All'),
(21, 'All'),
(22, 'All'),
(23, 'All'),
(24, 'Admin'),
(24, 'Filiaalmanager'),
(24, 'Winkelmedewerker'),
(25, 'Admin'),
(25, 'Filiaalmanager'),
(25, 'Winkelmedewerker'),
(26, 'Admin'),
(27, 'Filiaalmanager'),
(27, 'Winkelmedewerker'),
(28, 'Admin'),
(28, 'Filiaalmanager'),
(28, 'Winkelmedewerker'),
(29, 'Admin'),
(29, 'Filiaalmanager'),
(29, 'Winkelmedewerker');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `chatemessages`
--

CREATE TABLE `chatemessages` (
  `id` int NOT NULL,
  `sender_id` int NOT NULL,
  `receiver_id` int NOT NULL,
  `message` text NOT NULL,
  `timestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `is_read` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Gegevens worden geëxporteerd voor tabel `chatemessages`
--

INSERT INTO `chatemessages` (`id`, `sender_id`, `receiver_id`, `message`, `timestamp`, `is_read`) VALUES
(1, 1, 2, 'Welkom op de nieuwe chat functie', '2024-09-26 10:00:00', 0),
(2, 1, 3, 'Welkom op de nieuwe chat functie', '2024-09-26 10:00:00', 0),
(3, 1, 4, 'Welkom op de nieuwe chat functie', '2024-09-26 10:00:00', 0),
(4, 1, 5, 'Welkom op de nieuwe chat functie', '2024-09-26 10:00:00', 0),
(5, 1, 6, 'Welkom op de nieuwe chat functie', '2024-09-26 10:00:00', 0),
(10, 2, 1, 'Welkom op de nieuwe chat functie', '2024-09-26 10:00:00', 0),
(11, 1, 5, 'test bericht', '2024-11-02 15:51:10', 0),
(12, 5, 1, 'Hoi Andy, de prijskaartjes van de kaarsen zal je moeten aanpassen , ik heb alles gelijk gezet kassa ,website en webshop, bij controle op je webshop zaten heel wat fouten zoals 50% ipv 40%  , ook waar alleen maar stearine werd berekend en geen paraffine', '2024-11-10 14:45:44', 0),
(13, 5, 1, 'moet website zijn i p v webshop', '2024-11-10 14:46:57', 0),
(14, 5, 1, 'waarschijnlijk staan de cashmere woods geurkaars en cashmere woods/amber geurkaars niet op de webshop', '2024-11-10 14:51:17', 0),
(15, 1, 5, 'oke bedankt nog voor de informatie.', '2024-11-11 18:43:35', 0);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `company`
--

CREATE TABLE `company` (
  `company_id` int NOT NULL,
  `company_image` varchar(255) NOT NULL,
  `company_name` varchar(255) NOT NULL,
  `company_address` text NOT NULL,
  `company_country` varchar(255) NOT NULL,
  `company_zipcode` varchar(255) NOT NULL,
  `company_city` varchar(255) NOT NULL,
  `company_mapsname` varchar(255) NOT NULL,
  `company_phone` varchar(255) NOT NULL,
  `company_phone2` varchar(255) NOT NULL,
  `company_mobile` varchar(255) NOT NULL,
  `company_mobile2` varchar(255) NOT NULL,
  `company_emailaddress` varchar(255) NOT NULL,
  `company_emailaddress2` varchar(255) NOT NULL,
  `company_emailaddress3` varchar(255) NOT NULL,
  `company_emailaddress4` varchar(255) NOT NULL,
  `company_number` varchar(255) NOT NULL,
  `company_vat` varchar(255) NOT NULL,
  `company_branchnumber` varchar(255) NOT NULL,
  `ip_adress_filiaal` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Gegevens worden geëxporteerd voor tabel `company`
--

INSERT INTO `company` (`company_id`, `company_image`, `company_name`, `company_address`, `company_country`, `company_zipcode`, `company_city`, `company_mapsname`, `company_phone`, `company_phone2`, `company_mobile`, `company_mobile2`, `company_emailaddress`, `company_emailaddress2`, `company_emailaddress3`, `company_emailaddress4`, `company_number`, `company_vat`, `company_branchnumber`, `ip_adress_filiaal`) VALUES
(1, 'ecom-store-logo.png', 'Windels green &amp; deco resin', 'Beukenlaan 8', 'België', '3930', 'Hamont-Achel', 'Windels+green+%26+deco+resin', '+3211753319', '', '+32497107717', '', 'admin@windelsgreen-decoresin.com', 'webshop@windelsgreen-decoresin.com', 'admin@windelsgreen-decoresin.com', 'support@windelsgreen-decoresin.com', '0803859883', 'BE0803859883', 'office39308', '192.168.232.*'),
(2, 'ecom-store-logo.png', 'Windels green &amp; deco resin', 'Beukenlaan 8', 'België', '3930', 'Hamont-Achel', 'Windels+green+%26+deco+resin', '+3211753319', '', '+32497107717', '', 'filiaal-39308@windelsgreen-decoresin.com', 'webshop@windelsgreen-decoresin.com', 'admin@windelsgreen-decoresin.com', 'support@windelsgreen-decoresin.com', '0803859883', 'BE0803859883', 'filiaal39308', '192.168.232.*'),
(3, 'ecom-store-logo.png', 'Windels green &amp; deco resin', 'Mulderstraat 2', 'België', '9968', 'Oosteeklo', 'Windels+green+%26+deco+resin', '+3211753319', '', '', '', 'filiaal-99682@windelsgreen-decoresin.com', 'epoxyharsverkoop@windelsgreen-decoresin.com', '', '', '0803859883', 'BE0803859883', 'filiaal99682', '');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `contracts`
--

CREATE TABLE `contracts` (
  `contract_id` int NOT NULL,
  `admin_id` int NOT NULL,
  `contract_type` varchar(255) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `work_hours` int NOT NULL,
  `base_hourly_rate` decimal(10,2) NOT NULL,
  `renewal_count` int DEFAULT '0',
  `termination_reason` varchar(255) DEFAULT NULL,
  `termination_comments` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Gegevens worden geëxporteerd voor tabel `contracts`
--

INSERT INTO `contracts` (`contract_id`, `admin_id`, `contract_type`, `start_date`, `end_date`, `work_hours`, `base_hourly_rate`, `renewal_count`, `termination_reason`, `termination_comments`) VALUES
(1, 1, 'Zelfstandig', '2023-07-01', NULL, 10, 10.00, 4, NULL, NULL),
(2, 2, 'Zelfstandig', '2023-07-01', NULL, 33, 10.00, 4, NULL, NULL),
(3, 3, 'Bijbaan', '2023-07-01', '2025-06-30', 14, 10.00, 1, NULL, NULL),
(4, 5, 'Bijbaan', '2023-07-01', '2025-06-30', 14, 10.00, 1, NULL, NULL),
(5, 4, 'Freelance', '2023-07-01', '2024-10-31', 14, 10.00, 1, 'Einde van seizoensperiode - studentenarbeid', 'Beëindiging van de studentenarbeidsovereenkomst aan het einde van de seizoens- of vakantieperiode.'),
(6, 6, 'Bijbaan', '2023-07-01', '2025-06-30', 14, 10.00, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `daily_messages`
--

CREATE TABLE `daily_messages` (
  `id` int NOT NULL,
  `message` text NOT NULL,
  `message_type` enum('default','custom') DEFAULT 'default',
  `date` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Gegevens worden geëxporteerd voor tabel `daily_messages`
--

INSERT INTO `daily_messages` (`id`, `message`, `message_type`, `date`) VALUES
(1, 'Elke dag biedt nieuwe mogelijkheden. Grijp ze met beide handen!', 'custom', '2024-09-26'),
(2, 'Vandaag is de perfecte dag om je dromen na te jagen.', 'custom', '2024-09-27'),
(3, 'Kleine stappen leiden tot grote resultaten. Blijf vooruit gaan!', 'custom', '2024-09-28'),
(4, 'Het beste moet nog komen. Blijf geloven in jezelf!', 'custom', '2024-09-29'),
(5, 'Focus op wat je kunt beheersen en laat de rest los.', 'custom', '2024-09-30'),
(6, 'Je kracht ligt in je vastberadenheid. Geef nooit op!', 'custom', '2024-10-01'),
(7, 'De toekomst begint vandaag. Maak er iets moois van!', 'custom', '2024-10-02'),
(8, 'Blijf positief, zelfs als het moeilijk wordt. Je kunt dit!', 'custom', '2024-10-03'),
(9, 'Elke uitdaging is een kans om sterker te worden.', 'custom', '2024-10-04'),
(10, 'Grote dingen beginnen met kleine daden. Zet die eerste stap!', 'custom', '2024-10-05');

--
-- Triggers `daily_messages`
--
DELIMITER $$
CREATE TRIGGER `before_insert_daily_messages` BEFORE INSERT ON `daily_messages` FOR EACH ROW BEGIN
  IF NEW.date IS NULL THEN
    SET NEW.date = CURDATE();
  END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `employment`
--

CREATE TABLE `employment` (
  `employment_id` int NOT NULL,
  `admin_id` int NOT NULL,
  `position` varchar(255) NOT NULL,
  `branch` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Gegevens worden geëxporteerd voor tabel `employment`
--

INSERT INTO `employment` (`employment_id`, `admin_id`, `position`, `branch`) VALUES
(1, 1, 'Zaakvoeder', 'Office_39308'),
(2, 2, 'Winkelmedewerker', 'filiaal-39308'),
(3, 3, 'Winkelmedewerker', 'filiaal-39308'),
(4, 4, 'freelancer', 'filiaal-99682'),
(5, 5, 'Winkelmedewerker', 'filiaal-99682'),
(6, 6, 'Winkelmedewerker', 'filiaal-99682');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `gdpr_acceptance`
--

CREATE TABLE `gdpr_acceptance` (
  `id` int NOT NULL,
  `admin_id` int NOT NULL,
  `acceptance_date` datetime NOT NULL,
  `gdpr_version` int DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Gegevens worden geëxporteerd voor tabel `gdpr_acceptance`
--

INSERT INTO `gdpr_acceptance` (`id`, `admin_id`, `acceptance_date`, `gdpr_version`) VALUES
(4, 1, '2024-09-30 12:32:31', 2),
(5, 5, '2024-10-01 13:05:23', 2),
(6, 2, '2024-10-24 17:50:56', 2),
(7, 6, '2024-10-24 17:57:10', 2);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `gdpr_content`
--

CREATE TABLE `gdpr_content` (
  `version` int NOT NULL,
  `content` text NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Gegevens worden geëxporteerd voor tabel `gdpr_content`
--

INSERT INTO `gdpr_content` (`version`, `content`, `updated_at`) VALUES
(1, '<p>Bij Windels Green & Deco Resin nemen we uw privacy serieus. We verzamelen en gebruiken uw persoonlijke gegevens alleen voor legitieme zakelijke doeleinden, zoals het verwerken van uw bestellingen en het verbeteren van onze diensten. We delen uw gegevens niet met derden zonder uw toestemming, tenzij dit wettelijk verplicht is.</p>\r\n            <p>Uw rechten onder de GDPR omvatten:</p>\r\n            <ul>\r\n                <li>Het recht om geïnformeerd te worden over hoe uw gegevens worden verzameld en gebruikt.</li>\r\n                <li>Het recht om toegang te vragen tot uw persoonlijke gegevens.</li>\r\n                <li>Het recht om onnauwkeurige of onvolledige gegevens te laten corrigeren.</li>\r\n                <li>Het recht om te verzoeken dat uw gegevens worden gewist (recht om vergeten te worden).</li>\r\n                <li>Het recht om bezwaar te maken tegen de verwerking van uw gegevens.</li>\r\n                <li>Het recht om de overdracht van uw gegevens naar een andere organisatie te vragen.</li>\r\n            </ul>\r\n            <p>Als u vragen heeft over onze GDPR-voorwaarden of uw rechten wilt uitoefenen, neem dan contact met ons op via info@windelsgreen-decoresin.com.</p>', '2024-08-01 00:00:00'),
(2, '<p>Bij Windels Green & Deco Resin nemen we uw privacy serieus. We verzamelen en gebruiken uw persoonlijke gegevens alleen voor legitieme zakelijke doeleinden, zoals het verwerken van uw bestellingen en het verbeteren van onze diensten. We delen uw gegevens niet met derden zonder uw toestemming, tenzij dit wettelijk verplicht is.</p>\r\n            <p>Uw rechten onder de GDPR omvatten:</p>\r\n            <ul>\r\n                <li>Het recht om geïnformeerd te worden over hoe uw gegevens worden verzameld en gebruikt.</li>\r\n                <li>Het recht om toegang te vragen tot uw persoonlijke gegevens.</li>\r\n                <li>Het recht om onnauwkeurige of onvolledige gegevens te laten corrigeren.</li>\r\n                <li>Het recht om te verzoeken dat uw gegevens worden gewist (recht om vergeten te worden).</li>\r\n                <li>Het recht om bezwaar te maken tegen de verwerking van uw gegevens.</li>\r\n                <li>Het recht om de overdracht van uw gegevens naar een andere organisatie te vragen.</li>\r\n            </ul>\r\n            <p>Als u vragen heeft over onze GDPR-voorwaarden of uw rechten wilt uitoefenen, neem dan contact met ons op via info@windelsgreen-decoresin.com.</p>\r\n            <hr>\r\n            <h5>Andere Persoonlijke Gegevensverwerkingen</h5>\r\n            <p>Daarnaast verzamelen en gebruiken we ook uw gegevens voor de volgende functies:</p>\r\n            <ul>\r\n                <li>Chat en berichtenverkeer binnen ons platform</li>\r\n                <li>In- en uitklokken voor werkuren</li>\r\n                <li>Toegang tot loonfiches en arbeidscontracten</li>\r\n                <li>Beheer van uw uurrooster en beschikbaarheid</li>\r\n            </ul>\r\n            <p>Deze gegevens worden veilig opgeslagen en alleen gebruikt voor interne doeleinden om u zo goed mogelijk van dienst te zijn.</p>', '2024-09-30 00:00:00');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `gdpr_version`
--

CREATE TABLE `gdpr_version` (
  `id` int NOT NULL,
  `version` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Gegevens worden geëxporteerd voor tabel `gdpr_version`
--

INSERT INTO `gdpr_version` (`id`, `version`) VALUES
(1, 1),
(2, 2);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `messages`
--

CREATE TABLE `messages` (
  `id` int NOT NULL,
  `admin_id` int NOT NULL,
  `title` varchar(255) NOT NULL,
  `body` text NOT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `recipient_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Gegevens worden geëxporteerd voor tabel `messages`
--

INSERT INTO `messages` (`id`, `admin_id`, `title`, `body`, `image_path`, `created_at`, `recipient_id`) VALUES
(4, 1, 'Nieuwe update ', '<p>Hallo iedereen,</p>\r\n<p>Het is zover de nieuwe update 0.0.5.6 is geinstaleerd.</p>\r\n<p>Deze bevatten nieuwe functie en verbeteringen.</p>\r\n<ul>\r\n<li>Inkoop products toegevoegd aan products en categorie.</li>\r\n<li>Nieuw chat met je collega\'s functie&nbsp;</li>\r\n<li>Beter klok in/uit ( nieuw totaal aantal plus/min uren te zien die je hebt op/afgebouwd.)</li>\r\n<li>Bug fouten opgelost.</li>\r\n</ul>\r\n<p>Hopelijk vinden jullie deze update leuk.</p>\r\n<p>Met vriendelijke groeten,</p>\r\n<p>Admin team van Windels green &amp; deco resin.</p>', NULL, '2024-09-26 17:48:01', NULL),
(5, 1, 'Belangrijk: Tijdelijke Onbeschikbaarheid van het Portaal', '<p>Hallo iedereen,</p>\r\n<p>Zoals jullie al weten zijn er wegenwerken aan gang. Daarom kan dit portaal soms niet beschikbaar zijn door stroom en internet onderbrekingen.</p>\r\n<p>dit kan nog duren tot eind 2025.&nbsp;</p>\r\n<p>Omdat de onderbreking soms onaangekodigd is. Laat ik jullie via hier weten dat dus de kans op onderbreking groot is. houd hier rekening mee.</p>\r\n<p>Ik probeer dit ook optijd te comuniceren in de bestaande whatsappgroep zodra de onderbreking wel bekend is.</p>\r\n<p>Met vriendelijke groeten, Andy&nbsp;</p>', NULL, '2024-09-27 20:37:31', NULL),
(6, 1, 'Nieuw Loonfiche beschikbaar', '<p>Beste Medewerker,</p>\n<p>Je nieuwe loonfiche is nu beschikbaar in je profiel onder het tabblad Loonfiche.</p>\n<p>Mvg Andy Windels</p>\n<p>Zaakvoerder</p>', NULL, '2024-10-01 15:00:53', NULL),
(7, 1, 'Loonfiche beschikbaar voor Oktober 2024', '<p>Beste Medewerker,</p><p>Je nieuwe loonfiche voor de maand Oktober 2024 is gegenereerd.</p><p>En is nu beschikbaar in je profiel onder de tabbladnaam Loonfiche.</p><p>Met vriendelijke groeten,</p><p>Andy Windels (Zaakvoerder)</p>', NULL, '2024-11-03 11:10:23', 1),
(8, 1, 'Loonfiche beschikbaar voor Oktober 2024', '<p>Beste Medewerker,</p><p>Je nieuwe loonfiche voor de maand Oktober 2024 is gegenereerd.</p><p>En is nu beschikbaar in je profiel onder de tabbladnaam Loonfiche.</p><p>Met vriendelijke groeten,</p><p>Andy Windels (Zaakvoerder)</p>', NULL, '2024-11-03 11:10:45', 2),
(9, 1, 'Loonfiche beschikbaar voor Oktober 2024', '<p>Beste Medewerker,</p><p>Je nieuwe loonfiche voor de maand Oktober 2024 is gegenereerd.</p><p>En is nu beschikbaar in je profiel onder de tabbladnaam Loonfiche.</p><p>Met vriendelijke groeten,</p><p>Andy Windels (Zaakvoerder)</p>', NULL, '2024-11-03 11:10:57', 3),
(10, 1, 'Loonfiche beschikbaar voor Oktober 2024', '<p>Beste Medewerker,</p><p>Je nieuwe loonfiche voor de maand Oktober 2024 is gegenereerd.</p><p>En is nu beschikbaar in je profiel onder de tabbladnaam Loonfiche.</p><p>Met vriendelijke groeten,</p><p>Andy Windels (Zaakvoerder)</p>', NULL, '2024-11-03 11:11:07', 5),
(11, 1, 'Loonfiche beschikbaar voor Oktober 2024', '<p>Beste Medewerker,</p><p>Je nieuwe loonfiche voor de maand Oktober 2024 is gegenereerd.</p><p>En is nu beschikbaar in je profiel onder de tabbladnaam Loonfiche.</p><p>Met vriendelijke groeten,</p><p>Andy Windels (Zaakvoerder)</p>', NULL, '2024-11-03 11:11:17', 6);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `payslips`
--

CREATE TABLE `payslips` (
  `payslip_id` int NOT NULL,
  `admin_id` int NOT NULL,
  `month` int NOT NULL,
  `year` int NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Gegevens worden geëxporteerd voor tabel `payslips`
--

INSERT INTO `payslips` (`payslip_id`, `admin_id`, `month`, `year`, `file_path`, `created_at`) VALUES
(1, 1, 9, 2024, '/uploads/payslips/Loonfiche_september_2024.pdf', '2024-10-01 12:57:37'),
(2, 3, 9, 2024, '/uploads/payslips/Loonfiche_september_2024.pdf', '2024-10-01 12:57:54'),
(3, 4, 9, 2024, '/uploads/payslips/Loonfiche_september_2024.pdf', '2024-10-01 12:58:10'),
(4, 5, 9, 2024, '/uploads/payslips/Loonfiche_september_2024.pdf', '2024-10-01 12:58:24'),
(5, 6, 9, 2024, '/uploads/payslips/Loonfiche_september_2024.pdf', '2024-10-01 12:58:37'),
(6, 1, 10, 2024, '/uploads/payslips/Loonfiche_oktober_2024.pdf', '2024-11-03 10:10:23'),
(7, 2, 10, 2024, '/uploads/payslips/Loonfiche_oktober_2024.pdf', '2024-11-03 10:10:45'),
(8, 3, 10, 2024, '/uploads/payslips/Loonfiche_oktober_2024.pdf', '2024-11-03 10:10:57'),
(9, 5, 10, 2024, '/uploads/payslips/Loonfiche_oktober_2024.pdf', '2024-11-03 10:11:07'),
(10, 6, 10, 2024, '/uploads/payslips/Loonfiche_oktober_2024.pdf', '2024-11-03 10:11:17');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `schedules`
--

CREATE TABLE `schedules` (
  `id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `week_number` int DEFAULT NULL,
  `year` int DEFAULT NULL,
  `day_of_week` enum('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `status` enum('Planned','Sick','Vacation','Unpaid Leave','Absent','Overtime','Holiday') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'Planned',
  `comments` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `total_planned_hours` decimal(5,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Gegevens worden geëxporteerd voor tabel `schedules`
--

INSERT INTO `schedules` (`id`, `user_id`, `week_number`, `year`, `day_of_week`, `start_time`, `end_time`, `status`, `comments`, `total_planned_hours`) VALUES
(1, 1, 37, 2024, 'Monday', '19:00:00', '21:00:00', 'Planned', '', 2.00),
(2, 1, 37, 2024, 'Tuesday', '19:00:00', '21:00:00', 'Planned', '', 2.00),
(3, 1, 37, 2024, 'Wednesday', '19:00:00', '21:00:00', 'Planned', '', 2.00),
(4, 1, 37, 2024, 'Thursday', '10:00:00', '21:00:00', 'Planned', '', 11.00),
(5, 1, 37, 2024, 'Friday', '10:00:00', '21:00:00', 'Planned', '', 11.00),
(6, 1, 37, 2024, 'Saturday', '10:00:00', '18:00:00', 'Planned', '', 8.00),
(7, 1, 37, 2024, 'Sunday', '11:00:00', '18:00:00', 'Planned', '', 7.00),
(15, 2, 37, 2024, 'Monday', '19:00:00', '21:00:00', 'Planned', '', 2.00),
(16, 2, 37, 2024, 'Tuesday', '19:00:00', '21:00:00', 'Planned', '', 2.00),
(17, 2, 37, 2024, 'Wednesday', '19:00:00', '21:00:00', 'Planned', '', 2.00),
(18, 2, 37, 2024, 'Thursday', '10:00:00', '21:00:00', 'Planned', '', 11.00),
(19, 2, 37, 2024, 'Friday', '10:00:00', '21:00:00', 'Planned', '', 11.00),
(20, 2, 37, 2024, 'Saturday', '10:00:00', '18:00:00', 'Planned', '', 8.00),
(21, 2, 37, 2024, 'Sunday', NULL, NULL, 'Planned', '', NULL),
(22, 3, 37, 2024, 'Monday', '19:00:00', '21:00:00', 'Planned', '', 2.00),
(23, 3, 37, 2024, 'Tuesday', '19:00:00', '21:00:00', 'Planned', '', 2.00),
(24, 3, 37, 2024, 'Wednesday', '19:00:00', '21:00:00', 'Planned', '', 2.00),
(25, 3, 37, 2024, 'Thursday', '19:00:00', '21:00:00', 'Planned', '', 2.00),
(26, 3, 37, 2024, 'Friday', '18:00:00', '21:00:00', 'Planned', '', 3.00),
(27, 3, 37, 2024, 'Saturday', NULL, NULL, 'Planned', '', NULL),
(28, 3, 37, 2024, 'Sunday', NULL, NULL, 'Planned', '', NULL),
(29, 4, 37, 2024, 'Monday', NULL, NULL, 'Planned', '', 0.00),
(30, 4, 37, 2024, 'Tuesday', NULL, NULL, 'Planned', '', 0.00),
(31, 4, 37, 2024, 'Wednesday', NULL, NULL, 'Planned', '', 0.00),
(32, 4, 37, 2024, 'Thursday', NULL, NULL, 'Planned', '', 0.00),
(33, 4, 37, 2024, 'Friday', NULL, NULL, 'Planned', '', 0.00),
(34, 4, 37, 2024, 'Saturday', NULL, NULL, 'Planned', '', 0.00),
(35, 4, 37, 2024, 'Sunday', NULL, NULL, 'Planned', '', 0.00),
(36, 5, 37, 2024, 'Monday', NULL, NULL, 'Planned', '', NULL),
(37, 5, 37, 2024, 'Tuesday', '13:00:00', '17:00:00', 'Planned', '', 4.00),
(38, 5, 37, 2024, 'Wednesday', '13:00:00', '17:00:00', 'Planned', '', 4.00),
(39, 5, 37, 2024, 'Thursday', NULL, NULL, 'Planned', '', NULL),
(40, 5, 37, 2024, 'Friday', NULL, NULL, 'Planned', '', NULL),
(41, 5, 37, 2024, 'Saturday', NULL, NULL, 'Planned', '', NULL),
(42, 5, 37, 2024, 'Sunday', '11:00:00', '17:30:00', 'Planned', '', 6.50),
(43, 6, 37, 2024, 'Monday', NULL, NULL, 'Planned', '', 0.00),
(44, 6, 37, 2024, 'Tuesday', '14:00:00', '17:00:00', 'Planned', '', 3.00),
(45, 6, 37, 2024, 'Wednesday', '14:00:00', '17:00:00', 'Planned', '', 3.00),
(46, 6, 37, 2024, 'Thursday', NULL, NULL, 'Planned', '', 0.00),
(47, 6, 37, 2024, 'Friday', NULL, NULL, 'Planned', '', 0.00),
(48, 6, 37, 2024, 'Saturday', NULL, NULL, 'Planned', '', 0.00),
(49, 6, 37, 2024, 'Sunday', '10:00:00', '19:00:00', 'Planned', '', 9.00),
(50, 1, 36, 2024, 'Monday', '19:00:00', '21:00:00', 'Planned', '', 2.00),
(51, 1, 36, 2024, 'Tuesday', '19:00:00', '21:00:00', 'Planned', '', 2.00),
(52, 1, 36, 2024, 'Wednesday', '19:00:00', '21:00:00', 'Planned', '', 2.00),
(53, 1, 36, 2024, 'Thursday', '10:00:00', '21:00:00', 'Planned', '', 11.00),
(54, 1, 36, 2024, 'Friday', '10:00:00', '21:00:00', 'Planned', '', 11.00),
(55, 1, 36, 2024, 'Saturday', '10:00:00', '18:00:00', 'Planned', '', 8.00),
(56, 1, 36, 2024, 'Sunday', NULL, NULL, 'Planned', '', NULL),
(57, 2, 36, 2024, 'Monday', '19:00:00', '21:00:00', 'Planned', '', 2.00),
(58, 2, 36, 2024, 'Tuesday', '19:00:00', '21:00:00', 'Planned', '', 2.00),
(59, 2, 36, 2024, 'Wednesday', '19:00:00', '21:00:00', 'Planned', '', 2.00),
(60, 2, 36, 2024, 'Thursday', '10:00:00', '21:00:00', 'Planned', '', 11.00),
(61, 2, 36, 2024, 'Friday', '10:00:00', '21:00:00', 'Planned', '', 11.00),
(62, 2, 36, 2024, 'Saturday', '10:00:00', '18:00:00', 'Planned', '', 8.00),
(63, 2, 36, 2024, 'Sunday', NULL, NULL, 'Planned', '', NULL),
(64, 3, 36, 2024, 'Monday', '19:00:00', '21:00:00', 'Planned', '', 2.00),
(65, 3, 36, 2024, 'Tuesday', '19:00:00', '21:00:00', 'Planned', '', 2.00),
(66, 3, 36, 2024, 'Wednesday', '19:00:00', '21:00:00', 'Planned', '', 2.00),
(67, 3, 36, 2024, 'Thursday', '19:00:00', '21:00:00', 'Planned', '', 2.00),
(68, 3, 36, 2024, 'Friday', '19:00:00', '21:00:00', 'Planned', '', 2.00),
(69, 3, 36, 2024, 'Saturday', NULL, NULL, 'Planned', '', NULL),
(70, 3, 36, 2024, 'Sunday', NULL, NULL, 'Planned', '', NULL),
(71, 4, 36, 2024, 'Monday', NULL, NULL, 'Planned', '', 0.00),
(72, 4, 36, 2024, 'Tuesday', NULL, NULL, 'Planned', '', 0.00),
(73, 4, 36, 2024, 'Wednesday', NULL, NULL, 'Planned', '', 0.00),
(74, 4, 36, 2024, 'Thursday', NULL, NULL, 'Planned', '', 0.00),
(75, 4, 36, 2024, 'Friday', NULL, NULL, 'Planned', '', 0.00),
(76, 4, 36, 2024, 'Saturday', NULL, NULL, 'Planned', '', 0.00),
(77, 4, 36, 2024, 'Sunday', NULL, NULL, 'Planned', '', 0.00),
(78, 5, 36, 2024, 'Monday', '14:00:00', '17:00:00', 'Planned', '', 3.00),
(79, 5, 36, 2024, 'Tuesday', '14:00:00', '17:00:00', 'Planned', '', 3.00),
(80, 5, 36, 2024, 'Wednesday', NULL, NULL, 'Planned', '', 0.00),
(81, 5, 36, 2024, 'Thursday', NULL, NULL, 'Planned', '', 0.00),
(82, 5, 36, 2024, 'Friday', NULL, NULL, 'Planned', '', 0.00),
(83, 5, 36, 2024, 'Saturday', '10:00:00', '19:00:00', 'Planned', '', 9.00),
(84, 5, 36, 2024, 'Sunday', NULL, NULL, 'Planned', '', 0.00),
(85, 6, 36, 2024, 'Monday', '14:00:00', '17:00:00', 'Planned', '', 3.00),
(86, 6, 36, 2024, 'Tuesday', '14:00:00', '17:00:00', 'Planned', '', 3.00),
(87, 6, 36, 2024, 'Wednesday', NULL, NULL, 'Planned', '', 0.00),
(88, 6, 36, 2024, 'Thursday', NULL, NULL, 'Planned', '', 0.00),
(89, 6, 36, 2024, 'Friday', NULL, NULL, 'Planned', '', 0.00),
(90, 6, 36, 2024, 'Saturday', '10:00:00', '19:00:00', 'Planned', '', 9.00),
(91, 6, 36, 2024, 'Sunday', NULL, NULL, 'Planned', '', 0.00),
(92, 6, 38, 2024, 'Monday', NULL, NULL, 'Sick', '', NULL),
(93, 6, 38, 2024, 'Tuesday', NULL, NULL, 'Sick', '', NULL),
(94, 6, 38, 2024, 'Wednesday', NULL, NULL, 'Sick', '', NULL),
(95, 6, 38, 2024, 'Thursday', NULL, NULL, 'Sick', '', NULL),
(96, 6, 38, 2024, 'Friday', NULL, NULL, 'Sick', '', NULL),
(97, 6, 38, 2024, 'Saturday', NULL, NULL, 'Sick', '', NULL),
(98, 6, 38, 2024, 'Sunday', NULL, NULL, 'Sick', '', NULL),
(99, 5, 38, 2024, 'Monday', NULL, NULL, 'Planned', '', NULL),
(100, 5, 38, 2024, 'Tuesday', '09:00:00', '12:00:00', 'Planned', '', 3.00),
(101, 5, 38, 2024, 'Wednesday', '09:00:00', '12:00:00', 'Planned', '', 3.00),
(102, 5, 38, 2024, 'Thursday', '09:00:00', '12:00:00', 'Planned', '', 3.00),
(103, 5, 38, 2024, 'Friday', '09:00:00', '12:00:00', 'Planned', '', 3.00),
(104, 5, 38, 2024, 'Saturday', '10:00:00', '12:00:00', 'Planned', '', 2.00),
(105, 5, 38, 2024, 'Sunday', NULL, NULL, 'Planned', '', NULL),
(106, 4, 38, 2024, 'Monday', NULL, NULL, 'Planned', '', 0.00),
(107, 4, 38, 2024, 'Tuesday', NULL, NULL, 'Planned', '', 0.00),
(108, 4, 38, 2024, 'Wednesday', NULL, NULL, 'Planned', '', 0.00),
(109, 4, 38, 2024, 'Thursday', NULL, NULL, 'Planned', '', 0.00),
(110, 4, 38, 2024, 'Friday', NULL, NULL, 'Planned', '', 0.00),
(111, 4, 38, 2024, 'Saturday', NULL, NULL, 'Planned', '', 0.00),
(112, 4, 38, 2024, 'Sunday', NULL, NULL, 'Planned', '', 0.00),
(113, 3, 38, 2024, 'Monday', '19:00:00', '21:00:00', 'Planned', '', 2.00),
(114, 3, 38, 2024, 'Tuesday', '19:00:00', '21:00:00', 'Planned', '', 2.00),
(115, 3, 38, 2024, 'Wednesday', '19:00:00', '21:00:00', 'Planned', '', 2.00),
(116, 3, 38, 2024, 'Thursday', '19:00:00', '21:00:00', 'Planned', '', 2.00),
(117, 3, 38, 2024, 'Friday', '19:00:00', '21:00:00', 'Planned', '', 2.00),
(118, 3, 38, 2024, 'Saturday', '13:00:00', '17:00:00', 'Planned', '', 4.00),
(119, 3, 38, 2024, 'Sunday', NULL, NULL, 'Planned', '', NULL),
(120, 2, 38, 2024, 'Monday', '19:00:00', '21:00:00', 'Planned', '', 2.00),
(121, 2, 38, 2024, 'Tuesday', '19:00:00', '21:00:00', 'Planned', '', 2.00),
(122, 2, 38, 2024, 'Wednesday', '19:00:00', '21:00:00', 'Planned', '', 2.00),
(123, 2, 38, 2024, 'Thursday', '10:00:00', '21:00:00', 'Planned', '', 11.00),
(124, 2, 38, 2024, 'Friday', '10:00:00', '21:00:00', 'Planned', '', 11.00),
(125, 2, 38, 2024, 'Saturday', '10:00:00', '18:00:00', 'Planned', '', 8.00),
(126, 2, 38, 2024, 'Sunday', NULL, NULL, 'Planned', '', NULL),
(127, 1, 38, 2024, 'Monday', '19:00:00', '21:00:00', 'Planned', '', 2.00),
(128, 1, 38, 2024, 'Tuesday', '19:00:00', '21:00:00', 'Planned', '', 2.00),
(129, 1, 38, 2024, 'Wednesday', '19:00:00', '21:00:00', 'Planned', '', 2.00),
(130, 1, 38, 2024, 'Thursday', '10:00:00', '21:00:00', 'Planned', '', 11.00),
(131, 1, 38, 2024, 'Friday', '10:00:00', '21:00:00', 'Planned', '', 11.00),
(132, 1, 38, 2024, 'Saturday', '10:00:00', '18:00:00', 'Planned', '', 8.00),
(133, 1, 38, 2024, 'Sunday', NULL, NULL, 'Planned', '', NULL),
(274, 1, 39, 2024, 'Monday', '19:00:00', '21:00:00', 'Planned', '', 2.00),
(275, 1, 39, 2024, 'Tuesday', '19:00:00', '21:00:00', 'Planned', '', 2.00),
(276, 1, 39, 2024, 'Wednesday', '19:00:00', '21:00:00', 'Planned', '', 2.00),
(277, 1, 39, 2024, 'Thursday', '10:00:00', '21:00:00', 'Planned', '', 11.00),
(278, 1, 39, 2024, 'Friday', '10:00:00', '21:00:00', 'Planned', '', 11.00),
(279, 1, 39, 2024, 'Saturday', '10:00:00', '18:00:00', 'Planned', '', 8.00),
(280, 1, 39, 2024, 'Sunday', NULL, NULL, 'Planned', '', NULL),
(281, 2, 39, 2024, 'Monday', '19:00:00', '21:00:00', 'Planned', '', 2.00),
(282, 2, 39, 2024, 'Tuesday', '19:00:00', '21:00:00', 'Planned', '', 2.00),
(283, 2, 39, 2024, 'Wednesday', '19:00:00', '21:00:00', 'Planned', '', 2.00),
(284, 2, 39, 2024, 'Thursday', '10:00:00', '21:00:00', 'Planned', '', 11.00),
(285, 2, 39, 2024, 'Friday', '10:00:00', '21:00:00', 'Planned', '', 11.00),
(286, 2, 39, 2024, 'Saturday', '10:00:00', '18:00:00', 'Planned', '', 8.00),
(287, 2, 39, 2024, 'Sunday', NULL, NULL, 'Planned', '', NULL),
(288, 3, 39, 2024, 'Monday', NULL, NULL, 'Planned', '', NULL),
(289, 3, 39, 2024, 'Tuesday', NULL, NULL, 'Planned', '', NULL),
(290, 3, 39, 2024, 'Wednesday', NULL, NULL, 'Planned', '', NULL),
(291, 3, 39, 2024, 'Thursday', NULL, NULL, 'Planned', '', NULL),
(292, 3, 39, 2024, 'Friday', '10:00:00', '18:00:00', 'Planned', '', 8.00),
(293, 3, 39, 2024, 'Saturday', '10:00:00', '18:00:00', 'Planned', '', 8.00),
(294, 3, 39, 2024, 'Sunday', NULL, NULL, 'Planned', '', NULL),
(302, 4, 39, 2024, 'Monday', NULL, NULL, 'Planned', '', 0.00),
(303, 4, 39, 2024, 'Tuesday', NULL, NULL, 'Planned', '', 0.00),
(304, 4, 39, 2024, 'Wednesday', NULL, NULL, 'Planned', '', 0.00),
(305, 4, 39, 2024, 'Thursday', NULL, NULL, 'Planned', '', 0.00),
(306, 4, 39, 2024, 'Friday', NULL, NULL, 'Planned', '', 0.00),
(307, 4, 39, 2024, 'Saturday', NULL, NULL, 'Planned', '', 0.00),
(308, 4, 39, 2024, 'Sunday', NULL, NULL, 'Planned', '', 0.00),
(309, 5, 39, 2024, 'Monday', NULL, NULL, 'Planned', '', NULL),
(310, 5, 39, 2024, 'Tuesday', '09:00:00', '12:00:00', 'Planned', '', 3.00),
(311, 5, 39, 2024, 'Wednesday', '09:00:00', '12:00:00', 'Planned', '', 3.00),
(312, 5, 39, 2024, 'Thursday', '09:00:00', '12:00:00', 'Planned', '', 3.00),
(313, 5, 39, 2024, 'Friday', '09:00:00', '12:00:00', 'Planned', '', 3.00),
(314, 5, 39, 2024, 'Saturday', '10:00:00', '12:00:00', 'Planned', '', 2.00),
(315, 5, 39, 2024, 'Sunday', NULL, NULL, 'Planned', '', NULL),
(316, 6, 39, 2024, 'Monday', NULL, NULL, 'Sick', '', NULL),
(317, 6, 39, 2024, 'Tuesday', NULL, NULL, 'Sick', '', NULL),
(318, 6, 39, 2024, 'Wednesday', NULL, NULL, 'Sick', '', NULL),
(319, 6, 39, 2024, 'Thursday', NULL, NULL, 'Sick', '', NULL),
(320, 6, 39, 2024, 'Friday', NULL, NULL, 'Sick', '', NULL),
(321, 6, 39, 2024, 'Saturday', NULL, NULL, 'Sick', '', NULL),
(322, 6, 39, 2024, 'Sunday', NULL, NULL, 'Sick', '', NULL),
(323, 1, 40, 2024, 'Monday', '19:00:00', '21:00:00', 'Planned', '', 2.00),
(324, 1, 40, 2024, 'Tuesday', '19:00:00', '21:00:00', 'Planned', '', 2.00),
(325, 1, 40, 2024, 'Wednesday', '19:00:00', '21:00:00', 'Planned', '', 2.00),
(326, 1, 40, 2024, 'Thursday', '10:00:00', '21:00:00', 'Planned', '', 11.00),
(327, 1, 40, 2024, 'Friday', '10:00:00', '21:00:00', 'Overtime', '', -11.00),
(328, 1, 40, 2024, 'Saturday', '10:00:00', '18:00:00', 'Planned', '', 8.00),
(329, 1, 40, 2024, 'Sunday', NULL, NULL, 'Planned', '', 0.00),
(330, 2, 40, 2024, 'Monday', '19:00:00', '21:00:00', 'Planned', '', 2.00),
(331, 2, 40, 2024, 'Tuesday', '19:00:00', '21:00:00', 'Planned', '', 2.00),
(332, 2, 40, 2024, 'Wednesday', '19:00:00', '21:00:00', 'Planned', '', 2.00),
(333, 2, 40, 2024, 'Thursday', '10:00:00', '21:00:00', 'Planned', '', 11.00),
(334, 2, 40, 2024, 'Friday', '10:00:00', '21:00:00', 'Planned', '', 11.00),
(335, 2, 40, 2024, 'Saturday', '10:00:00', '18:00:00', 'Planned', '', 8.00),
(336, 2, 40, 2024, 'Sunday', NULL, NULL, 'Planned', '', NULL),
(337, 3, 40, 2024, 'Monday', NULL, NULL, 'Planned', '', 0.00),
(338, 3, 40, 2024, 'Tuesday', '19:00:00', '21:00:00', 'Planned', '', 2.00),
(339, 3, 40, 2024, 'Wednesday', '19:00:00', '21:00:00', 'Planned', '', 2.00),
(340, 3, 40, 2024, 'Thursday', '19:00:00', '21:00:00', 'Planned', '', 2.00),
(341, 3, 40, 2024, 'Friday', '14:00:00', '18:00:00', 'Vacation', '', 4.00),
(342, 3, 40, 2024, 'Saturday', '14:00:00', '18:00:00', 'Planned', '', 4.00),
(343, 3, 40, 2024, 'Sunday', NULL, NULL, 'Planned', '', 0.00),
(351, 4, 40, 2024, 'Monday', NULL, NULL, 'Planned', '', 0.00),
(352, 4, 40, 2024, 'Tuesday', NULL, NULL, 'Planned', '', 0.00),
(353, 4, 40, 2024, 'Wednesday', NULL, NULL, 'Planned', '', 0.00),
(354, 4, 40, 2024, 'Thursday', NULL, NULL, 'Planned', '', 0.00),
(355, 4, 40, 2024, 'Friday', NULL, NULL, 'Planned', '', 0.00),
(356, 4, 40, 2024, 'Saturday', NULL, NULL, 'Planned', '', 0.00),
(357, 4, 40, 2024, 'Sunday', NULL, NULL, 'Planned', '', 0.00),
(358, 5, 40, 2024, 'Monday', NULL, NULL, 'Planned', '', 0.00),
(359, 5, 40, 2024, 'Tuesday', NULL, NULL, 'Planned', '', 0.00),
(360, 5, 40, 2024, 'Wednesday', NULL, NULL, 'Planned', '', 0.00),
(361, 5, 40, 2024, 'Thursday', '10:00:00', '15:15:00', 'Planned', '', 5.25),
(362, 5, 40, 2024, 'Friday', '10:00:00', '15:15:00', 'Planned', '', 5.25),
(363, 5, 40, 2024, 'Saturday', '10:00:00', '14:00:00', 'Planned', '', 4.00),
(364, 5, 40, 2024, 'Sunday', NULL, NULL, 'Planned', '', 0.00),
(365, 6, 40, 2024, 'Monday', NULL, NULL, 'Sick', '', NULL),
(366, 6, 40, 2024, 'Tuesday', NULL, NULL, 'Sick', '', NULL),
(367, 6, 40, 2024, 'Wednesday', NULL, NULL, 'Sick', '', NULL),
(368, 6, 40, 2024, 'Thursday', NULL, NULL, 'Sick', '', NULL),
(369, 6, 40, 2024, 'Friday', NULL, NULL, 'Sick', '', NULL),
(370, 6, 40, 2024, 'Saturday', NULL, NULL, 'Sick', '', NULL),
(371, 6, 40, 2024, 'Sunday', NULL, NULL, 'Sick', '', NULL),
(509, 1, 41, 2024, 'Monday', '19:00:00', '21:00:00', 'Planned', '', 2.00),
(510, 1, 41, 2024, 'Tuesday', '19:00:00', '21:00:00', 'Planned', '', 2.00),
(511, 1, 41, 2024, 'Wednesday', '19:00:00', '21:00:00', 'Planned', '', 2.00),
(512, 1, 41, 2024, 'Thursday', '10:00:00', '21:00:00', 'Planned', '', 11.00),
(513, 1, 41, 2024, 'Friday', '10:00:00', '21:00:00', 'Planned', '', 11.00),
(514, 1, 41, 2024, 'Saturday', '10:00:00', '18:00:00', 'Planned', '', 8.00),
(515, 1, 41, 2024, 'Sunday', NULL, NULL, 'Planned', '', 0.00),
(516, 2, 41, 2024, 'Monday', '19:00:00', '21:00:00', 'Planned', '', 2.00),
(517, 2, 41, 2024, 'Tuesday', '19:00:00', '21:00:00', 'Planned', '', 2.00),
(518, 2, 41, 2024, 'Wednesday', '19:00:00', '21:00:00', 'Planned', '', 2.00),
(519, 2, 41, 2024, 'Thursday', '10:00:00', '21:00:00', 'Planned', '', 11.00),
(520, 2, 41, 2024, 'Friday', '10:00:00', '21:00:00', 'Planned', '', 11.00),
(521, 2, 41, 2024, 'Saturday', '10:00:00', '18:00:00', 'Planned', '', 8.00),
(522, 2, 41, 2024, 'Sunday', NULL, NULL, 'Planned', '', 0.00),
(523, 3, 41, 2024, 'Monday', NULL, NULL, 'Planned', '', 0.00),
(524, 3, 41, 2024, 'Tuesday', '19:00:00', '21:00:00', 'Planned', '', 2.00),
(525, 3, 41, 2024, 'Wednesday', NULL, NULL, 'Planned', '', 0.00),
(526, 3, 41, 2024, 'Thursday', '19:00:00', '21:00:00', 'Planned', '', 2.00),
(527, 3, 41, 2024, 'Friday', '10:00:00', '21:00:00', 'Planned', '', 11.00),
(528, 3, 41, 2024, 'Saturday', NULL, NULL, 'Planned', '', 0.00),
(529, 3, 41, 2024, 'Sunday', NULL, NULL, 'Planned', '', 0.00),
(530, 4, 41, 2024, 'Monday', NULL, NULL, 'Planned', '', 0.00),
(531, 4, 41, 2024, 'Tuesday', NULL, NULL, 'Planned', '', 0.00),
(532, 4, 41, 2024, 'Wednesday', NULL, NULL, 'Planned', '', 0.00),
(533, 4, 41, 2024, 'Thursday', NULL, NULL, 'Planned', '', 0.00),
(534, 4, 41, 2024, 'Friday', NULL, NULL, 'Planned', '', 0.00),
(535, 4, 41, 2024, 'Saturday', NULL, NULL, 'Planned', '', 0.00),
(536, 4, 41, 2024, 'Sunday', NULL, NULL, 'Planned', '', 0.00),
(537, 5, 41, 2024, 'Monday', '10:00:00', '12:00:00', 'Planned', '', 2.00),
(538, 5, 41, 2024, 'Tuesday', '10:00:00', '12:00:00', 'Planned', '', 2.00),
(539, 5, 41, 2024, 'Wednesday', '10:00:00', '12:00:00', 'Planned', '', 2.00),
(540, 5, 41, 2024, 'Thursday', '10:00:00', '12:00:00', 'Planned', '', 2.00),
(541, 5, 41, 2024, 'Friday', '10:00:00', '12:00:00', 'Planned', '', 2.00),
(542, 5, 41, 2024, 'Saturday', '10:00:00', '14:00:00', 'Planned', '', 4.00),
(543, 5, 41, 2024, 'Sunday', NULL, NULL, 'Planned', '', 0.00),
(544, 6, 41, 2024, 'Monday', NULL, NULL, 'Sick', '', 0.00),
(545, 6, 41, 2024, 'Tuesday', NULL, NULL, 'Sick', '', 0.00),
(546, 6, 41, 2024, 'Wednesday', NULL, NULL, 'Sick', '', 0.00),
(547, 6, 41, 2024, 'Thursday', NULL, NULL, 'Sick', '', 0.00),
(548, 6, 41, 2024, 'Friday', NULL, NULL, 'Sick', '', 0.00),
(549, 6, 41, 2024, 'Saturday', NULL, NULL, 'Sick', '', 0.00),
(550, 6, 41, 2024, 'Sunday', NULL, NULL, 'Sick', '', 0.00),
(551, 1, 42, 2024, 'Monday', '19:00:00', '21:00:00', 'Planned', '', 2.00),
(552, 1, 42, 2024, 'Tuesday', '19:00:00', '21:00:00', 'Planned', '', 2.00),
(553, 1, 42, 2024, 'Wednesday', '19:00:00', '21:00:00', 'Overtime', '', -2.00),
(554, 1, 42, 2024, 'Thursday', '10:00:00', '21:00:00', 'Planned', '', 11.00),
(555, 1, 42, 2024, 'Friday', '10:00:00', '21:00:00', 'Planned', '', 11.00),
(556, 1, 42, 2024, 'Saturday', '10:00:00', '18:00:00', 'Overtime', '', -8.00),
(557, 1, 42, 2024, 'Sunday', '11:00:00', '16:00:00', 'Planned', '', 5.00),
(558, 2, 42, 2024, 'Monday', '19:00:00', '21:00:00', 'Planned', '', 2.00),
(559, 2, 42, 2024, 'Tuesday', '19:00:00', '21:00:00', 'Planned', '', 2.00),
(560, 2, 42, 2024, 'Wednesday', NULL, NULL, 'Unpaid Leave', '', 0.00),
(561, 2, 42, 2024, 'Thursday', '10:00:00', '21:00:00', 'Planned', '', 11.00),
(562, 2, 42, 2024, 'Friday', '10:00:00', '21:00:00', 'Planned', '', 11.00),
(563, 2, 42, 2024, 'Saturday', '10:00:00', '18:00:00', 'Planned', '', 8.00),
(564, 2, 42, 2024, 'Sunday', NULL, NULL, 'Planned', '', 0.00),
(565, 3, 42, 2024, 'Monday', NULL, NULL, 'Planned', '', 0.00),
(566, 3, 42, 2024, 'Tuesday', NULL, NULL, 'Planned', '', 0.00),
(567, 3, 42, 2024, 'Wednesday', '19:00:00', '21:00:00', 'Planned', '', 2.00),
(568, 3, 42, 2024, 'Thursday', '19:00:00', '21:00:00', 'Planned', '', 2.00),
(569, 3, 42, 2024, 'Friday', '18:15:00', '21:00:00', 'Planned', '', 2.75),
(570, 3, 42, 2024, 'Saturday', '09:45:00', '18:00:00', 'Planned', '', 8.25),
(571, 3, 42, 2024, 'Sunday', NULL, NULL, 'Planned', '', 0.00),
(579, 4, 42, 2024, 'Monday', NULL, NULL, 'Planned', '', 0.00),
(580, 4, 42, 2024, 'Tuesday', NULL, NULL, 'Planned', '', 0.00),
(581, 4, 42, 2024, 'Wednesday', NULL, NULL, 'Planned', '', 0.00),
(582, 4, 42, 2024, 'Thursday', NULL, NULL, 'Planned', '', 0.00),
(583, 4, 42, 2024, 'Friday', NULL, NULL, 'Planned', '', 0.00),
(584, 4, 42, 2024, 'Saturday', NULL, NULL, 'Planned', '', 0.00),
(585, 4, 42, 2024, 'Sunday', NULL, NULL, 'Planned', '', 0.00),
(586, 5, 42, 2024, 'Monday', '10:00:00', '12:00:00', 'Planned', '', 2.00),
(587, 5, 42, 2024, 'Tuesday', '10:00:00', '12:00:00', 'Planned', '', 2.00),
(588, 5, 42, 2024, 'Wednesday', '10:00:00', '12:00:00', 'Planned', '', 2.00),
(589, 5, 42, 2024, 'Thursday', '10:00:00', '12:00:00', 'Planned', '', 2.00),
(590, 5, 42, 2024, 'Friday', '10:00:00', '12:00:00', 'Planned', '', 2.00),
(591, 5, 42, 2024, 'Saturday', '10:00:00', '14:00:00', 'Planned', '', 4.00),
(592, 5, 42, 2024, 'Sunday', NULL, NULL, 'Planned', '', 0.00),
(593, 6, 42, 2024, 'Monday', NULL, NULL, 'Sick', '', 0.00),
(594, 6, 42, 2024, 'Tuesday', NULL, NULL, 'Sick', '', 0.00),
(595, 6, 42, 2024, 'Wednesday', NULL, NULL, 'Sick', '', 0.00),
(596, 6, 42, 2024, 'Thursday', NULL, NULL, 'Sick', '', 0.00),
(597, 6, 42, 2024, 'Friday', NULL, NULL, 'Sick', '', 0.00),
(598, 6, 42, 2024, 'Saturday', NULL, NULL, 'Sick', '', 0.00),
(599, 6, 42, 2024, 'Sunday', NULL, NULL, 'Planned', '', 0.00),
(600, 6, 43, 2024, 'Monday', '14:00:00', '16:00:00', 'Planned', '', 2.00),
(601, 6, 43, 2024, 'Tuesday', '14:00:00', '16:00:00', 'Planned', '', 2.00),
(602, 6, 43, 2024, 'Wednesday', '14:00:00', '16:00:00', 'Planned', '', 2.00),
(603, 6, 43, 2024, 'Thursday', '14:00:00', '16:00:00', 'Planned', '', 2.00),
(604, 6, 43, 2024, 'Friday', '14:00:00', '16:00:00', 'Planned', '', 2.00),
(605, 6, 43, 2024, 'Saturday', '13:00:00', '17:00:00', 'Planned', '', 4.00),
(606, 6, 43, 2024, 'Sunday', NULL, NULL, 'Planned', '', 0.00),
(607, 5, 43, 2024, 'Monday', '10:00:00', '12:00:00', 'Planned', '', 2.00),
(608, 5, 43, 2024, 'Tuesday', '10:00:00', '12:00:00', 'Planned', '', 2.00),
(609, 5, 43, 2024, 'Wednesday', '10:00:00', '12:00:00', 'Planned', '', 2.00),
(610, 5, 43, 2024, 'Thursday', '10:00:00', '12:00:00', 'Planned', '', 2.00),
(611, 5, 43, 2024, 'Friday', '10:00:00', '12:00:00', 'Planned', '', 2.00),
(612, 5, 43, 2024, 'Saturday', '10:00:00', '14:00:00', 'Planned', '', 4.00),
(613, 5, 43, 2024, 'Sunday', NULL, NULL, 'Planned', '', 0.00),
(614, 4, 43, 2024, 'Monday', NULL, NULL, 'Planned', '', 0.00),
(615, 4, 43, 2024, 'Tuesday', NULL, NULL, 'Planned', '', 0.00),
(616, 4, 43, 2024, 'Wednesday', NULL, NULL, 'Planned', '', 0.00),
(617, 4, 43, 2024, 'Thursday', NULL, NULL, 'Planned', '', 0.00),
(618, 4, 43, 2024, 'Friday', NULL, NULL, 'Planned', '', 0.00),
(619, 4, 43, 2024, 'Saturday', NULL, NULL, 'Planned', '', 0.00),
(620, 4, 43, 2024, 'Sunday', NULL, NULL, 'Planned', '', 0.00),
(621, 3, 43, 2024, 'Monday', NULL, NULL, 'Planned', '', 0.00),
(622, 3, 43, 2024, 'Tuesday', '19:00:00', '21:00:00', 'Planned', '', 2.00),
(623, 3, 43, 2024, 'Wednesday', '19:00:00', '21:00:00', 'Planned', '', 2.00),
(624, 3, 43, 2024, 'Thursday', '10:00:00', '21:00:00', 'Planned', '', 11.00),
(625, 3, 43, 2024, 'Friday', NULL, NULL, 'Planned', '', 0.00),
(626, 3, 43, 2024, 'Saturday', NULL, NULL, 'Overtime', '', 0.00),
(627, 3, 43, 2024, 'Sunday', NULL, NULL, 'Planned', '', 0.00),
(628, 2, 43, 2024, 'Monday', '19:00:00', '21:00:00', 'Planned', '', 2.00),
(629, 2, 43, 2024, 'Tuesday', '19:00:00', '21:00:00', 'Planned', '', 2.00),
(630, 2, 43, 2024, 'Wednesday', '19:00:00', '21:00:00', 'Planned', '', 2.00),
(631, 2, 43, 2024, 'Thursday', '10:00:00', '21:00:00', 'Planned', '', 11.00),
(632, 2, 43, 2024, 'Friday', '10:00:00', '21:00:00', 'Planned', '', 11.00),
(633, 2, 43, 2024, 'Saturday', '10:00:00', '18:00:00', 'Planned', '', 8.00),
(634, 2, 43, 2024, 'Sunday', NULL, NULL, 'Planned', '', 0.00),
(635, 1, 43, 2024, 'Monday', '19:00:00', '21:00:00', 'Planned', '', 2.00),
(636, 1, 43, 2024, 'Tuesday', '19:00:00', '21:00:00', 'Planned', '', 2.00),
(637, 1, 43, 2024, 'Wednesday', '19:00:00', '21:00:00', 'Planned', '', 2.00),
(638, 1, 43, 2024, 'Thursday', '10:00:00', '21:00:00', 'Planned', '', 11.00),
(639, 1, 43, 2024, 'Friday', '10:00:00', '21:00:00', 'Planned', '', 11.00),
(640, 1, 43, 2024, 'Saturday', '10:00:00', '18:00:00', 'Planned', '', 8.00),
(641, 1, 43, 2024, 'Sunday', NULL, NULL, 'Planned', '', 0.00),
(733, 3, 44, 2024, 'Monday', NULL, NULL, 'Planned', '', 0.00),
(734, 3, 44, 2024, 'Tuesday', NULL, NULL, 'Planned', '', 0.00),
(735, 3, 44, 2024, 'Wednesday', NULL, NULL, 'Planned', '', 0.00),
(736, 3, 44, 2024, 'Thursday', '10:00:00', '21:00:00', 'Planned', '', 11.00),
(737, 3, 44, 2024, 'Friday', NULL, NULL, 'Planned', '', 0.00),
(738, 3, 44, 2024, 'Saturday', '14:00:00', '18:00:00', 'Planned', '', 4.00),
(739, 3, 44, 2024, 'Sunday', NULL, NULL, 'Planned', '', 0.00),
(747, 1, 44, 2024, 'Monday', '19:00:00', '21:00:00', 'Planned', '', 2.00),
(748, 1, 44, 2024, 'Tuesday', '19:00:00', '21:00:00', 'Planned', '', 2.00),
(749, 1, 44, 2024, 'Wednesday', '19:00:00', '21:00:00', 'Planned', '', 2.00),
(750, 1, 44, 2024, 'Thursday', '10:00:00', '21:00:00', 'Planned', '', 11.00),
(751, 1, 44, 2024, 'Friday', NULL, NULL, 'Planned', '', 0.00),
(752, 1, 44, 2024, 'Saturday', '10:00:00', '18:00:00', 'Planned', '', 8.00),
(753, 1, 44, 2024, 'Sunday', NULL, NULL, 'Planned', '', 0.00),
(754, 2, 44, 2024, 'Monday', '19:00:00', '21:00:00', 'Planned', '', 2.00),
(755, 2, 44, 2024, 'Tuesday', '19:00:00', '21:00:00', 'Planned', '', 2.00),
(756, 2, 44, 2024, 'Wednesday', '19:00:00', '21:00:00', 'Planned', '', 2.00),
(757, 2, 44, 2024, 'Thursday', '10:00:00', '21:00:00', 'Planned', '', 11.00),
(758, 2, 44, 2024, 'Friday', NULL, NULL, 'Planned', '', 0.00),
(759, 2, 44, 2024, 'Saturday', '10:00:00', '18:00:00', 'Planned', '', 8.00),
(760, 2, 44, 2024, 'Sunday', NULL, NULL, 'Planned', '', 0.00),
(761, 4, 44, 2024, 'Monday', NULL, NULL, 'Planned', '', 0.00),
(762, 4, 44, 2024, 'Tuesday', NULL, NULL, 'Planned', '', 0.00),
(763, 4, 44, 2024, 'Wednesday', NULL, NULL, 'Planned', '', 0.00),
(764, 4, 44, 2024, 'Thursday', NULL, NULL, 'Planned', '', 0.00),
(765, 4, 44, 2024, 'Friday', NULL, NULL, 'Planned', '', 0.00),
(766, 4, 44, 2024, 'Saturday', NULL, NULL, 'Planned', '', 0.00),
(767, 4, 44, 2024, 'Sunday', NULL, NULL, 'Planned', '', 0.00),
(768, 5, 44, 2024, 'Monday', '14:00:00', '16:00:00', 'Planned', '', 2.00),
(769, 5, 44, 2024, 'Tuesday', '14:00:00', '16:00:00', 'Planned', '', 2.00),
(770, 5, 44, 2024, 'Wednesday', '14:00:00', '16:00:00', 'Planned', '', 2.00),
(771, 5, 44, 2024, 'Thursday', '14:00:00', '16:00:00', 'Planned', '', 2.00),
(772, 5, 44, 2024, 'Friday', NULL, NULL, 'Planned', '', 0.00),
(773, 5, 44, 2024, 'Saturday', '13:00:00', '17:00:00', 'Planned', '', 4.00),
(774, 5, 44, 2024, 'Sunday', NULL, NULL, 'Planned', '', 0.00),
(775, 6, 44, 2024, 'Monday', '14:00:00', '16:00:00', 'Planned', '', 2.00),
(776, 6, 44, 2024, 'Tuesday', '14:00:00', '16:00:00', 'Planned', '', 2.00),
(777, 6, 44, 2024, 'Wednesday', '14:00:00', '16:00:00', 'Planned', '', 2.00),
(778, 6, 44, 2024, 'Thursday', '14:00:00', '16:00:00', 'Planned', '', 2.00),
(779, 6, 44, 2024, 'Friday', NULL, NULL, 'Planned', '', 0.00),
(780, 6, 44, 2024, 'Saturday', '13:00:00', '17:00:00', 'Planned', '', 4.00),
(781, 6, 44, 2024, 'Sunday', NULL, NULL, 'Planned', '', 0.00),
(831, 1, 45, 2024, 'Monday', NULL, NULL, 'Planned', '', 0.00),
(832, 1, 45, 2024, 'Tuesday', NULL, NULL, 'Planned', '', 0.00),
(833, 1, 45, 2024, 'Wednesday', NULL, NULL, 'Planned', '', 0.00),
(834, 1, 45, 2024, 'Thursday', '18:00:00', '21:00:00', 'Planned', '', 3.00),
(835, 1, 45, 2024, 'Friday', '18:00:00', '21:00:00', 'Planned', '', 3.00),
(836, 1, 45, 2024, 'Saturday', NULL, NULL, 'Planned', '', 0.00),
(837, 1, 45, 2024, 'Sunday', '10:00:00', '12:00:00', 'Planned', '', 2.00),
(838, 2, 45, 2024, 'Monday', '19:00:00', '21:00:00', 'Planned', '', 2.00),
(839, 2, 45, 2024, 'Tuesday', '19:00:00', '21:00:00', 'Planned', '', 2.00),
(840, 2, 45, 2024, 'Wednesday', '19:00:00', '21:00:00', 'Planned', '', 2.00),
(841, 2, 45, 2024, 'Thursday', '10:00:00', '18:00:00', 'Planned', '', 8.00),
(842, 2, 45, 2024, 'Friday', '10:00:00', '18:00:00', 'Planned', '', 8.00),
(843, 2, 45, 2024, 'Saturday', '10:00:00', '18:00:00', 'Planned', '', 8.00),
(844, 2, 45, 2024, 'Sunday', NULL, NULL, 'Planned', '', 0.00),
(845, 3, 45, 2024, 'Monday', NULL, NULL, 'Planned', '', 0.00),
(846, 3, 45, 2024, 'Tuesday', '19:00:00', '21:00:00', 'Planned', '', 2.00),
(847, 3, 45, 2024, 'Wednesday', '19:00:00', '21:00:00', 'Planned', '', 2.00),
(848, 3, 45, 2024, 'Thursday', NULL, NULL, 'Planned', '', 0.00),
(849, 3, 45, 2024, 'Friday', NULL, NULL, 'Planned', '', 0.00),
(850, 3, 45, 2024, 'Saturday', NULL, NULL, 'Planned', '', 0.00),
(851, 3, 45, 2024, 'Sunday', NULL, NULL, 'Planned', '', 0.00),
(852, 4, 45, 2024, 'Monday', NULL, NULL, 'Planned', '', 0.00),
(853, 4, 45, 2024, 'Tuesday', NULL, NULL, 'Planned', '', 0.00),
(854, 4, 45, 2024, 'Wednesday', NULL, NULL, 'Planned', '', 0.00),
(855, 4, 45, 2024, 'Thursday', NULL, NULL, 'Planned', '', 0.00),
(856, 4, 45, 2024, 'Friday', NULL, NULL, 'Planned', '', 0.00),
(857, 4, 45, 2024, 'Saturday', NULL, NULL, 'Planned', '', 0.00),
(858, 4, 45, 2024, 'Sunday', NULL, NULL, 'Planned', '', 0.00),
(859, 5, 45, 2024, 'Monday', '14:00:00', '16:00:00', 'Planned', '', 2.00),
(860, 5, 45, 2024, 'Tuesday', '14:00:00', '16:00:00', 'Planned', '', 2.00),
(861, 5, 45, 2024, 'Wednesday', '14:00:00', '16:00:00', 'Planned', '', 2.00),
(862, 5, 45, 2024, 'Thursday', NULL, NULL, 'Planned', '', 0.00),
(863, 5, 45, 2024, 'Friday', '14:00:00', '16:00:00', 'Planned', '', 2.00),
(864, 5, 45, 2024, 'Saturday', '10:00:00', '16:30:00', 'Planned', '', 6.50),
(865, 5, 45, 2024, 'Sunday', NULL, NULL, 'Planned', '', 0.00),
(873, 6, 45, 2024, 'Monday', '14:00:00', '16:00:00', 'Planned', '', 2.00),
(874, 6, 45, 2024, 'Tuesday', '14:00:00', '16:00:00', 'Planned', '', 2.00),
(875, 6, 45, 2024, 'Wednesday', '14:00:00', '16:00:00', 'Planned', '', 2.00),
(876, 6, 45, 2024, 'Thursday', NULL, NULL, 'Planned', '', 0.00),
(877, 6, 45, 2024, 'Friday', '14:00:00', '16:00:00', 'Planned', '', 2.00),
(878, 6, 45, 2024, 'Saturday', '10:00:00', '16:30:00', 'Planned', '', 6.50),
(879, 6, 45, 2024, 'Sunday', NULL, NULL, 'Planned', '', 0.00),
(887, 1, 51, 2024, 'Monday', NULL, NULL, 'Planned', '', 0.00),
(888, 1, 51, 2024, 'Tuesday', NULL, NULL, 'Planned', '', 0.00),
(889, 1, 51, 2024, 'Wednesday', NULL, NULL, 'Planned', '', 0.00),
(890, 1, 51, 2024, 'Thursday', NULL, NULL, 'Planned', '', 0.00),
(891, 1, 51, 2024, 'Friday', '14:00:00', '18:00:00', 'Planned', '', 4.00),
(892, 1, 51, 2024, 'Saturday', '22:00:00', '23:00:00', 'Planned', '', 1.00),
(893, 1, 51, 2024, 'Sunday', '20:00:00', '21:00:00', 'Planned', '', 1.00),
(894, 2, 51, 2024, 'Monday', '19:00:00', '21:00:00', 'Planned', '', 2.00),
(895, 2, 51, 2024, 'Tuesday', '19:00:00', '21:00:00', 'Planned', '', 2.00),
(896, 2, 51, 2024, 'Wednesday', '19:00:00', '21:00:00', 'Planned', '', 2.00),
(897, 2, 51, 2024, 'Thursday', '10:00:00', '18:00:00', 'Planned', '', 8.00),
(898, 2, 51, 2024, 'Friday', '10:00:00', '13:00:00', 'Planned', '', 3.00),
(899, 2, 51, 2024, 'Saturday', '14:00:00', '21:00:00', 'Planned', '', 7.00),
(900, 2, 51, 2024, 'Sunday', '10:00:00', '18:00:00', 'Planned', '', 8.00),
(915, 5, 51, 2024, 'Monday', NULL, NULL, 'Planned', '', 0.00),
(916, 5, 51, 2024, 'Tuesday', NULL, NULL, 'Planned', '', 0.00),
(917, 5, 51, 2024, 'Wednesday', NULL, NULL, 'Planned', '', 0.00),
(918, 5, 51, 2024, 'Thursday', NULL, NULL, 'Planned', '', 0.00),
(919, 5, 51, 2024, 'Friday', NULL, NULL, 'Planned', '', 0.00),
(920, 5, 51, 2024, 'Saturday', '14:00:00', '21:00:00', 'Planned', '', 7.00),
(921, 5, 51, 2024, 'Sunday', '10:00:00', '18:00:00', 'Planned', '', 8.00),
(922, 6, 51, 2024, 'Monday', NULL, NULL, 'Planned', '', 0.00),
(923, 6, 51, 2024, 'Tuesday', NULL, NULL, 'Planned', '', 0.00),
(924, 6, 51, 2024, 'Wednesday', NULL, NULL, 'Planned', '', 0.00),
(925, 6, 51, 2024, 'Thursday', NULL, NULL, 'Planned', '', 0.00),
(926, 6, 51, 2024, 'Friday', NULL, NULL, 'Planned', '', 0.00),
(927, 6, 51, 2024, 'Saturday', '14:00:00', '21:00:00', 'Planned', '', 7.00),
(928, 6, 51, 2024, 'Sunday', '10:00:00', '18:00:00', 'Planned', '', 8.00),
(929, 6, 52, 2024, 'Monday', NULL, NULL, 'Planned', '', 0.00),
(930, 6, 52, 2024, 'Tuesday', NULL, NULL, 'Overtime', '', 0.00),
(931, 6, 52, 2024, 'Wednesday', NULL, NULL, 'Holiday', '', 0.00),
(932, 6, 52, 2024, 'Thursday', NULL, NULL, 'Holiday', '', 0.00),
(933, 6, 52, 2024, 'Friday', '14:00:00', '16:00:00', 'Planned', '', 2.00),
(934, 6, 52, 2024, 'Saturday', '14:00:00', '16:00:00', 'Planned', '', 2.00),
(935, 6, 52, 2024, 'Sunday', NULL, NULL, 'Planned', '', 0.00),
(936, 5, 52, 2024, 'Monday', NULL, NULL, 'Planned', '', 0.00),
(937, 5, 52, 2024, 'Tuesday', NULL, NULL, 'Overtime', '', 0.00),
(938, 5, 52, 2024, 'Wednesday', NULL, NULL, 'Holiday', '', 0.00),
(939, 5, 52, 2024, 'Thursday', NULL, NULL, 'Holiday', '', 0.00),
(940, 5, 52, 2024, 'Friday', '14:00:00', '16:00:00', 'Planned', '', 2.00),
(941, 5, 52, 2024, 'Saturday', '14:00:00', '16:00:00', 'Planned', '', 2.00),
(942, 5, 52, 2024, 'Sunday', NULL, NULL, 'Planned', '', 0.00),
(943, 1, 52, 2024, 'Monday', NULL, NULL, 'Planned', '', 0.00),
(944, 1, 52, 2024, 'Tuesday', NULL, NULL, 'Planned', '', 0.00),
(945, 1, 52, 2024, 'Wednesday', NULL, NULL, 'Holiday', '', 0.00),
(946, 1, 52, 2024, 'Thursday', NULL, NULL, 'Holiday', '', 0.00),
(947, 1, 52, 2024, 'Friday', NULL, NULL, 'Planned', '', 0.00),
(948, 1, 52, 2024, 'Saturday', NULL, NULL, 'Planned', '', 0.00),
(949, 1, 52, 2024, 'Sunday', '10:00:00', '12:00:00', 'Planned', '', 2.00),
(950, 2, 52, 2024, 'Monday', '19:00:00', '21:00:00', 'Planned', '', 2.00),
(951, 2, 52, 2024, 'Tuesday', NULL, NULL, 'Overtime', '', 0.00),
(952, 2, 52, 2024, 'Wednesday', NULL, NULL, 'Holiday', '', 0.00),
(953, 2, 52, 2024, 'Thursday', NULL, NULL, 'Holiday', '', 0.00),
(954, 2, 52, 2024, 'Friday', '10:00:00', '18:00:00', 'Planned', '', 8.00),
(955, 2, 52, 2024, 'Saturday', '10:00:00', '18:00:00', 'Planned', '', 8.00),
(956, 2, 52, 2024, 'Sunday', NULL, NULL, 'Planned', '', 0.00),
(957, 4, 52, 2024, 'Monday', NULL, NULL, 'Planned', '', 0.00),
(958, 4, 52, 2024, 'Tuesday', NULL, NULL, 'Planned', '', 0.00),
(959, 4, 52, 2024, 'Wednesday', NULL, NULL, 'Planned', '', 0.00),
(960, 4, 52, 2024, 'Thursday', NULL, NULL, 'Planned', '', 0.00),
(961, 4, 52, 2024, 'Friday', NULL, NULL, 'Planned', '', 0.00),
(962, 4, 52, 2024, 'Saturday', NULL, NULL, 'Planned', '', 0.00),
(963, 4, 52, 2024, 'Sunday', NULL, NULL, 'Planned', '', 0.00),
(964, 3, 52, 2024, 'Monday', '19:00:00', '21:00:00', 'Planned', '', 2.00),
(965, 3, 52, 2024, 'Tuesday', NULL, NULL, 'Overtime', '', 0.00),
(966, 3, 52, 2024, 'Wednesday', NULL, NULL, 'Holiday', '', 0.00),
(967, 3, 52, 2024, 'Thursday', NULL, NULL, 'Holiday', '', 0.00),
(968, 3, 52, 2024, 'Friday', NULL, NULL, 'Planned', '', 0.00),
(969, 3, 52, 2024, 'Saturday', NULL, NULL, 'Planned', '', 0.00),
(970, 3, 52, 2024, 'Sunday', NULL, NULL, 'Planned', '', 0.00),
(971, 1, 1, 2025, 'Monday', NULL, NULL, 'Planned', '', 0.00),
(972, 1, 1, 2025, 'Tuesday', NULL, NULL, 'Overtime', '', 0.00),
(973, 1, 1, 2025, 'Wednesday', NULL, NULL, 'Holiday', '', 0.00),
(974, 1, 1, 2025, 'Thursday', NULL, NULL, 'Planned', '', 0.00),
(975, 1, 1, 2025, 'Friday', NULL, NULL, 'Planned', '', 0.00),
(976, 1, 1, 2025, 'Saturday', NULL, NULL, 'Planned', '', 0.00),
(977, 1, 1, 2025, 'Sunday', '10:00:00', '12:00:00', 'Planned', '', 2.00),
(978, 2, 1, 2025, 'Monday', '19:00:00', '21:00:00', 'Planned', '', 2.00),
(979, 2, 1, 2025, 'Tuesday', NULL, NULL, 'Overtime', '', 0.00),
(980, 2, 1, 2025, 'Wednesday', NULL, NULL, 'Holiday', '', 0.00),
(981, 2, 1, 2025, 'Thursday', '10:00:00', '18:00:00', 'Planned', '', 8.00),
(982, 2, 1, 2025, 'Friday', '10:00:00', '18:00:00', 'Planned', '', 8.00),
(983, 2, 1, 2025, 'Saturday', '10:00:00', '18:00:00', 'Planned', '', 8.00),
(984, 2, 1, 2025, 'Sunday', NULL, NULL, 'Planned', '', 0.00),
(985, 3, 1, 2025, 'Monday', NULL, NULL, 'Planned', '', 0.00),
(986, 3, 1, 2025, 'Tuesday', NULL, NULL, 'Overtime', '', 0.00),
(987, 3, 1, 2025, 'Wednesday', NULL, NULL, 'Holiday', '', 0.00),
(988, 3, 1, 2025, 'Thursday', NULL, NULL, 'Planned', '', 0.00),
(989, 3, 1, 2025, 'Friday', NULL, NULL, 'Planned', '', 0.00),
(990, 3, 1, 2025, 'Saturday', NULL, NULL, 'Planned', '', 0.00),
(991, 3, 1, 2025, 'Sunday', NULL, NULL, 'Planned', '', 0.00),
(992, 4, 1, 2025, 'Monday', NULL, NULL, 'Planned', '', 0.00),
(993, 4, 1, 2025, 'Tuesday', NULL, NULL, 'Planned', '', 0.00),
(994, 4, 1, 2025, 'Wednesday', NULL, NULL, 'Planned', '', 0.00),
(995, 4, 1, 2025, 'Thursday', NULL, NULL, 'Planned', '', 0.00),
(996, 4, 1, 2025, 'Friday', NULL, NULL, 'Planned', '', 0.00),
(997, 4, 1, 2025, 'Saturday', NULL, NULL, 'Planned', '', 0.00),
(998, 4, 1, 2025, 'Sunday', NULL, NULL, 'Planned', '', 0.00),
(999, 5, 1, 2025, 'Monday', NULL, NULL, 'Planned', '', 0.00),
(1000, 5, 1, 2025, 'Tuesday', NULL, NULL, 'Overtime', '', 0.00),
(1001, 5, 1, 2025, 'Wednesday', NULL, NULL, 'Holiday', '', 0.00),
(1002, 5, 1, 2025, 'Thursday', NULL, NULL, 'Planned', '', 0.00),
(1003, 5, 1, 2025, 'Friday', NULL, NULL, 'Planned', '', 0.00),
(1004, 5, 1, 2025, 'Saturday', NULL, NULL, 'Planned', '', 0.00),
(1005, 5, 1, 2025, 'Sunday', NULL, NULL, 'Planned', '', 0.00),
(1006, 6, 1, 2025, 'Monday', NULL, NULL, 'Planned', '', 0.00),
(1007, 6, 1, 2025, 'Tuesday', NULL, NULL, 'Overtime', '', 0.00),
(1008, 6, 1, 2025, 'Wednesday', NULL, NULL, 'Holiday', '', 0.00),
(1009, 6, 1, 2025, 'Thursday', NULL, NULL, 'Planned', '', 0.00),
(1010, 6, 1, 2025, 'Friday', NULL, NULL, 'Planned', '', 0.00),
(1011, 6, 1, 2025, 'Saturday', NULL, NULL, 'Planned', '', 0.00),
(1012, 6, 1, 2025, 'Sunday', NULL, NULL, 'Planned', '', 0.00),
(1139, 6, 46, 2024, 'Monday', NULL, NULL, 'Planned', '', 0.00),
(1140, 6, 46, 2024, 'Tuesday', '14:00:00', '16:00:00', 'Planned', '', 2.00),
(1141, 6, 46, 2024, 'Wednesday', '14:00:00', '16:00:00', 'Planned', '', 2.00),
(1142, 6, 46, 2024, 'Thursday', '14:00:00', '16:00:00', 'Planned', '', 2.00),
(1143, 6, 46, 2024, 'Friday', '14:00:00', '16:00:00', 'Planned', '', 2.00),
(1144, 6, 46, 2024, 'Saturday', '13:00:00', '17:00:00', 'Planned', '', 4.00),
(1145, 6, 46, 2024, 'Sunday', NULL, NULL, 'Planned', '', 0.00),
(1146, 5, 46, 2024, 'Monday', NULL, NULL, 'Planned', '', 0.00),
(1147, 5, 46, 2024, 'Tuesday', '14:00:00', '16:00:00', 'Planned', '', 2.00),
(1148, 5, 46, 2024, 'Wednesday', '14:00:00', '16:00:00', 'Planned', '', 2.00),
(1149, 5, 46, 2024, 'Thursday', '14:00:00', '16:00:00', 'Planned', '', 2.00),
(1150, 5, 46, 2024, 'Friday', '14:00:00', '16:00:00', 'Planned', '', 2.00),
(1151, 5, 46, 2024, 'Saturday', '13:00:00', '17:00:00', 'Planned', '', 4.00),
(1152, 5, 46, 2024, 'Sunday', NULL, NULL, 'Planned', '', 0.00),
(1153, 3, 46, 2024, 'Monday', '19:00:00', '21:00:00', 'Planned', '', 2.00),
(1154, 3, 46, 2024, 'Tuesday', NULL, NULL, 'Planned', '', 0.00),
(1155, 3, 46, 2024, 'Wednesday', NULL, NULL, 'Planned', '', 0.00),
(1156, 3, 46, 2024, 'Thursday', NULL, NULL, 'Planned', '', 0.00),
(1157, 3, 46, 2024, 'Friday', NULL, NULL, 'Planned', '', 0.00),
(1158, 3, 46, 2024, 'Saturday', '10:00:00', '18:00:00', 'Planned', '', 8.00),
(1159, 3, 46, 2024, 'Sunday', NULL, NULL, 'Planned', '', 0.00),
(1160, 2, 46, 2024, 'Monday', NULL, NULL, 'Planned', '', 0.00),
(1161, 2, 46, 2024, 'Tuesday', '19:00:00', '21:00:00', 'Planned', '', 2.00),
(1162, 2, 46, 2024, 'Wednesday', '19:00:00', '21:00:00', 'Planned', '', 2.00),
(1163, 2, 46, 2024, 'Thursday', '10:00:00', '18:00:00', 'Planned', '', 8.00),
(1164, 2, 46, 2024, 'Friday', '10:00:00', '18:00:00', 'Planned', '', 8.00),
(1165, 2, 46, 2024, 'Saturday', '10:00:00', '18:00:00', 'Planned', '', 8.00),
(1166, 2, 46, 2024, 'Sunday', NULL, NULL, 'Planned', '', 0.00),
(1167, 1, 46, 2024, 'Monday', NULL, NULL, 'Planned', '', 0.00),
(1168, 1, 46, 2024, 'Tuesday', NULL, NULL, 'Planned', '', 0.00),
(1169, 1, 46, 2024, 'Wednesday', NULL, NULL, 'Planned', '', 0.00),
(1170, 1, 46, 2024, 'Thursday', '09:00:00', '10:00:00', 'Planned', '', 1.00),
(1171, 1, 46, 2024, 'Friday', NULL, NULL, 'Planned', '', 0.00),
(1172, 1, 46, 2024, 'Saturday', NULL, NULL, 'Planned', '', 0.00),
(1173, 1, 46, 2024, 'Sunday', NULL, NULL, 'Planned', '', 0.00),
(1174, 4, 46, 2024, 'Monday', NULL, NULL, 'Planned', '', 0.00),
(1175, 4, 46, 2024, 'Tuesday', NULL, NULL, 'Planned', '', 0.00),
(1176, 4, 46, 2024, 'Wednesday', NULL, NULL, 'Planned', '', 0.00),
(1177, 4, 46, 2024, 'Thursday', NULL, NULL, 'Planned', '', 0.00),
(1178, 4, 46, 2024, 'Friday', NULL, NULL, 'Planned', '', 0.00),
(1179, 4, 46, 2024, 'Saturday', NULL, NULL, 'Planned', '', 0.00),
(1180, 4, 46, 2024, 'Sunday', NULL, NULL, 'Planned', '', 0.00),
(1188, 3, 47, 2024, 'Monday', NULL, NULL, 'Planned', '', 0.00),
(1189, 3, 47, 2024, 'Tuesday', NULL, NULL, 'Planned', '', 0.00),
(1190, 3, 47, 2024, 'Wednesday', '19:00:00', '21:00:00', 'Planned', '', 2.00),
(1191, 3, 47, 2024, 'Thursday', '10:00:00', '18:00:00', 'Planned', '', 8.00),
(1192, 3, 47, 2024, 'Friday', NULL, NULL, 'Planned', '', 0.00),
(1193, 3, 47, 2024, 'Saturday', NULL, NULL, 'Planned', '', 0.00),
(1194, 3, 47, 2024, 'Sunday', NULL, NULL, 'Planned', '', 0.00),
(1195, 5, 47, 2024, 'Monday', '14:00:00', '16:00:00', 'Planned', '', 2.00),
(1196, 5, 47, 2024, 'Tuesday', '14:00:00', '16:00:00', 'Planned', '', 2.00),
(1197, 5, 47, 2024, 'Wednesday', '14:00:00', '16:00:00', 'Planned', '', 2.00),
(1198, 5, 47, 2024, 'Thursday', '14:00:00', '16:00:00', 'Planned', '', 2.00),
(1199, 5, 47, 2024, 'Friday', '14:00:00', '16:00:00', 'Planned', '', 2.00),
(1200, 5, 47, 2024, 'Saturday', '13:00:00', '17:00:00', 'Planned', '', 4.00),
(1201, 5, 47, 2024, 'Sunday', NULL, NULL, 'Planned', '', 0.00),
(1202, 6, 47, 2024, 'Monday', '14:00:00', '16:00:00', 'Planned', '', 2.00),
(1203, 6, 47, 2024, 'Tuesday', '14:00:00', '16:00:00', 'Planned', '', 2.00),
(1204, 6, 47, 2024, 'Wednesday', '14:00:00', '16:00:00', 'Planned', '', 2.00),
(1205, 6, 47, 2024, 'Thursday', '14:00:00', '16:00:00', 'Planned', '', 2.00),
(1206, 6, 47, 2024, 'Friday', '14:00:00', '16:00:00', 'Planned', '', 2.00),
(1207, 6, 47, 2024, 'Saturday', '13:00:00', '17:00:00', 'Planned', '', 4.00),
(1208, 6, 47, 2024, 'Sunday', NULL, NULL, 'Planned', '', 0.00),
(1209, 1, 47, 2024, 'Monday', '18:00:00', '21:00:00', 'Planned', '', 3.00),
(1210, 1, 47, 2024, 'Tuesday', NULL, NULL, 'Planned', '', 0.00),
(1211, 1, 47, 2024, 'Wednesday', NULL, NULL, 'Planned', '', 0.00),
(1212, 1, 47, 2024, 'Thursday', '18:00:00', '21:00:00', 'Planned', '', 3.00),
(1213, 1, 47, 2024, 'Friday', '18:00:00', '21:00:00', 'Planned', '', 3.00),
(1214, 1, 47, 2024, 'Saturday', NULL, NULL, 'Planned', '', 0.00),
(1215, 1, 47, 2024, 'Sunday', NULL, NULL, 'Planned', '', 0.00),
(1216, 2, 47, 2024, 'Monday', '19:00:00', '21:00:00', 'Planned', '', 2.00),
(1217, 2, 47, 2024, 'Tuesday', '19:00:00', '21:00:00', 'Planned', '', 2.00),
(1218, 2, 47, 2024, 'Wednesday', '19:00:00', '21:00:00', 'Planned', '', 2.00),
(1219, 2, 47, 2024, 'Thursday', '10:00:00', '18:00:00', 'Planned', '', 8.00),
(1220, 2, 47, 2024, 'Friday', '10:00:00', '18:00:00', 'Planned', '', 8.00),
(1221, 2, 47, 2024, 'Saturday', '10:00:00', '18:00:00', 'Planned', '', 8.00),
(1222, 2, 47, 2024, 'Sunday', NULL, NULL, 'Planned', '', 0.00),
(1223, 4, 47, 2024, 'Monday', NULL, NULL, 'Planned', '', 0.00),
(1224, 4, 47, 2024, 'Tuesday', NULL, NULL, 'Planned', '', 0.00),
(1225, 4, 47, 2024, 'Wednesday', NULL, NULL, 'Planned', '', 0.00),
(1226, 4, 47, 2024, 'Thursday', NULL, NULL, 'Planned', '', 0.00),
(1227, 4, 47, 2024, 'Friday', NULL, NULL, 'Planned', '', 0.00),
(1228, 4, 47, 2024, 'Saturday', NULL, NULL, 'Planned', '', 0.00),
(1229, 4, 47, 2024, 'Sunday', NULL, NULL, 'Planned', '', 0.00),
(1230, 4, 48, 2024, 'Monday', NULL, NULL, 'Planned', '', 0.00),
(1231, 4, 48, 2024, 'Tuesday', NULL, NULL, 'Planned', '', 0.00),
(1232, 4, 48, 2024, 'Wednesday', NULL, NULL, 'Planned', '', 0.00),
(1233, 4, 48, 2024, 'Thursday', NULL, NULL, 'Planned', '', 0.00),
(1234, 4, 48, 2024, 'Friday', NULL, NULL, 'Planned', '', 0.00),
(1235, 4, 48, 2024, 'Saturday', NULL, NULL, 'Planned', '', 0.00),
(1236, 4, 48, 2024, 'Sunday', NULL, NULL, 'Planned', '', 0.00),
(1244, 1, 48, 2024, 'Monday', '19:00:00', '21:00:00', 'Planned', '', 2.00),
(1245, 1, 48, 2024, 'Tuesday', NULL, NULL, 'Planned', '', 0.00),
(1246, 1, 48, 2024, 'Wednesday', NULL, NULL, 'Planned', '', 0.00),
(1247, 1, 48, 2024, 'Thursday', NULL, NULL, 'Planned', '', 0.00),
(1248, 1, 48, 2024, 'Friday', '19:00:00', '21:00:00', 'Planned', '', 2.00),
(1249, 1, 48, 2024, 'Saturday', NULL, NULL, 'Planned', '', 0.00),
(1250, 1, 48, 2024, 'Sunday', NULL, NULL, 'Planned', '', 0.00),
(1251, 2, 48, 2024, 'Monday', '19:00:00', '21:00:00', 'Planned', '', 2.00),
(1252, 2, 48, 2024, 'Tuesday', '19:00:00', '21:00:00', 'Planned', '', 2.00),
(1253, 2, 48, 2024, 'Wednesday', '19:00:00', '21:00:00', 'Planned', '', 2.00),
(1254, 2, 48, 2024, 'Thursday', '10:00:00', '18:00:00', 'Planned', '', 8.00),
(1255, 2, 48, 2024, 'Friday', '10:00:00', '18:00:00', 'Planned', '', 8.00),
(1256, 2, 48, 2024, 'Saturday', '10:00:00', '18:00:00', 'Planned', '', 8.00),
(1257, 2, 48, 2024, 'Sunday', NULL, NULL, 'Planned', '', 0.00),
(1258, 3, 48, 2024, 'Monday', NULL, NULL, 'Planned', '', 0.00),
(1259, 3, 48, 2024, 'Tuesday', '19:00:00', '21:00:00', 'Planned', '', 2.00),
(1260, 3, 48, 2024, 'Wednesday', NULL, NULL, 'Planned', '', 0.00),
(1261, 3, 48, 2024, 'Thursday', NULL, NULL, 'Planned', '', 0.00),
(1262, 3, 48, 2024, 'Friday', '10:00:00', '18:00:00', 'Planned', '', 8.00),
(1263, 3, 48, 2024, 'Saturday', NULL, NULL, 'Planned', '', 0.00),
(1264, 3, 48, 2024, 'Sunday', NULL, NULL, 'Planned', '', 0.00),
(1265, 5, 48, 2024, 'Monday', '14:00:00', '16:00:00', 'Planned', '', 2.00),
(1266, 5, 48, 2024, 'Tuesday', '14:00:00', '16:00:00', 'Planned', '', 2.00),
(1267, 5, 48, 2024, 'Wednesday', '14:00:00', '16:00:00', 'Planned', '', 2.00),
(1268, 5, 48, 2024, 'Thursday', '14:00:00', '16:00:00', 'Planned', '', 2.00),
(1269, 5, 48, 2024, 'Friday', '14:00:00', '16:00:00', 'Planned', '', 2.00),
(1270, 5, 48, 2024, 'Saturday', '13:00:00', '17:00:00', 'Planned', '', 4.00),
(1271, 5, 48, 2024, 'Sunday', NULL, NULL, 'Planned', '', 0.00),
(1272, 6, 48, 2024, 'Monday', '14:00:00', '16:00:00', 'Planned', '', 2.00),
(1273, 6, 48, 2024, 'Tuesday', '14:00:00', '16:00:00', 'Planned', '', 2.00),
(1274, 6, 48, 2024, 'Wednesday', '14:00:00', '16:00:00', 'Planned', '', 2.00),
(1275, 6, 48, 2024, 'Thursday', '14:00:00', '16:00:00', 'Planned', '', 2.00),
(1276, 6, 48, 2024, 'Friday', '14:00:00', '16:00:00', 'Planned', '', 2.00),
(1277, 6, 48, 2024, 'Saturday', '13:00:00', '17:00:00', 'Planned', '', 4.00),
(1278, 6, 48, 2024, 'Sunday', NULL, NULL, 'Planned', '', 0.00),
(1279, 3, 49, 2024, 'Monday', NULL, NULL, 'Planned', '', 0.00),
(1280, 3, 49, 2024, 'Tuesday', NULL, NULL, 'Planned', '', 0.00),
(1281, 3, 49, 2024, 'Wednesday', '19:00:00', '21:00:00', 'Planned', '', 2.00),
(1282, 3, 49, 2024, 'Thursday', NULL, NULL, 'Planned', '', 0.00),
(1283, 3, 49, 2024, 'Friday', '10:00:00', '18:00:00', 'Planned', '', 8.00),
(1284, 3, 49, 2024, 'Saturday', NULL, NULL, 'Planned', '', 0.00),
(1285, 3, 49, 2024, 'Sunday', NULL, NULL, 'Planned', '', 0.00),
(1286, 1, 49, 2024, 'Monday', NULL, NULL, 'Planned', '', 0.00),
(1287, 1, 49, 2024, 'Tuesday', NULL, NULL, 'Planned', '', 0.00),
(1288, 1, 49, 2024, 'Wednesday', NULL, NULL, 'Planned', '', 0.00),
(1289, 1, 49, 2024, 'Thursday', NULL, NULL, 'Planned', '', 0.00),
(1290, 1, 49, 2024, 'Friday', NULL, NULL, 'Planned', '', 0.00),
(1291, 1, 49, 2024, 'Saturday', '16:00:00', '18:00:00', 'Planned', '', 2.00),
(1292, 1, 49, 2024, 'Sunday', '10:00:00', '12:00:00', 'Planned', '', 2.00),
(1293, 2, 49, 2024, 'Monday', '19:00:00', '21:00:00', 'Planned', '', 2.00),
(1294, 2, 49, 2024, 'Tuesday', '19:00:00', '21:00:00', 'Planned', '', 2.00),
(1295, 2, 49, 2024, 'Wednesday', '19:00:00', '21:00:00', 'Planned', '', 2.00),
(1296, 2, 49, 2024, 'Thursday', '10:00:00', '18:00:00', 'Planned', '', 8.00),
(1297, 2, 49, 2024, 'Friday', '10:00:00', '18:00:00', 'Planned', '', 8.00),
(1298, 2, 49, 2024, 'Saturday', '10:00:00', '18:00:00', 'Planned', '', 8.00),
(1299, 2, 49, 2024, 'Sunday', NULL, NULL, 'Planned', '', 0.00),
(1300, 5, 49, 2024, 'Monday', '14:00:00', '16:00:00', 'Planned', '', 2.00),
(1301, 5, 49, 2024, 'Tuesday', '14:00:00', '16:00:00', 'Planned', '', 2.00),
(1302, 5, 49, 2024, 'Wednesday', '14:00:00', '16:00:00', 'Planned', '', 2.00),
(1303, 5, 49, 2024, 'Thursday', '14:00:00', '16:00:00', 'Planned', '', 2.00),
(1304, 5, 49, 2024, 'Friday', '14:00:00', '16:00:00', 'Planned', '', 2.00),
(1305, 5, 49, 2024, 'Saturday', '13:00:00', '17:00:00', 'Planned', '', 4.00),
(1306, 5, 49, 2024, 'Sunday', NULL, NULL, 'Planned', '', 0.00),
(1307, 6, 49, 2024, 'Monday', '14:00:00', '16:00:00', 'Planned', '', 2.00),
(1308, 6, 49, 2024, 'Tuesday', '14:00:00', '16:00:00', 'Planned', '', 2.00),
(1309, 6, 49, 2024, 'Wednesday', '14:00:00', '16:00:00', 'Planned', '', 2.00),
(1310, 6, 49, 2024, 'Thursday', '14:00:00', '16:00:00', 'Planned', '', 2.00),
(1311, 6, 49, 2024, 'Friday', '14:00:00', '16:00:00', 'Planned', '', 2.00),
(1312, 6, 49, 2024, 'Saturday', '13:00:00', '17:00:00', 'Planned', '', 4.00),
(1313, 6, 49, 2024, 'Sunday', NULL, NULL, 'Planned', '', 0.00),
(1314, 4, 49, 2024, 'Monday', NULL, NULL, 'Planned', '', 0.00),
(1315, 4, 49, 2024, 'Tuesday', NULL, NULL, 'Planned', '', 0.00),
(1316, 4, 49, 2024, 'Wednesday', NULL, NULL, 'Planned', '', 0.00),
(1317, 4, 49, 2024, 'Thursday', NULL, NULL, 'Planned', '', 0.00),
(1318, 4, 49, 2024, 'Friday', NULL, NULL, 'Planned', '', 0.00),
(1319, 4, 49, 2024, 'Saturday', NULL, NULL, 'Planned', '', 0.00),
(1320, 4, 49, 2024, 'Sunday', NULL, NULL, 'Planned', '', 0.00),
(1321, 1, 50, 2024, 'Monday', NULL, NULL, 'Planned', '', 0.00),
(1322, 1, 50, 2024, 'Tuesday', NULL, NULL, 'Planned', '', 0.00),
(1323, 1, 50, 2024, 'Wednesday', NULL, NULL, 'Planned', '', 0.00),
(1324, 1, 50, 2024, 'Thursday', NULL, NULL, 'Planned', '', 0.00),
(1325, 1, 50, 2024, 'Friday', NULL, NULL, 'Planned', '', 0.00),
(1326, 1, 50, 2024, 'Saturday', NULL, NULL, 'Planned', '', 0.00),
(1327, 1, 50, 2024, 'Sunday', '10:00:00', '18:00:00', 'Planned', '', 8.00),
(1328, 2, 50, 2024, 'Monday', '19:00:00', '21:00:00', 'Planned', '', 2.00),
(1329, 2, 50, 2024, 'Tuesday', '19:00:00', '21:00:00', 'Planned', '', 2.00),
(1330, 2, 50, 2024, 'Wednesday', '19:00:00', '21:00:00', 'Planned', '', 2.00),
(1331, 2, 50, 2024, 'Thursday', '10:00:00', '18:00:00', 'Planned', '', 8.00),
(1332, 2, 50, 2024, 'Friday', '10:00:00', '18:00:00', 'Planned', '', 8.00),
(1333, 2, 50, 2024, 'Saturday', '10:00:00', '18:00:00', 'Planned', '', 8.00),
(1334, 2, 50, 2024, 'Sunday', NULL, NULL, 'Planned', '', 0.00),
(1349, 3, 50, 2024, 'Monday', '19:00:00', '21:00:00', 'Planned', '', 2.00),
(1350, 3, 50, 2024, 'Tuesday', NULL, NULL, 'Planned', '', 0.00),
(1351, 3, 50, 2024, 'Wednesday', NULL, NULL, 'Planned', '', 0.00),
(1352, 3, 50, 2024, 'Thursday', NULL, NULL, 'Planned', '', 0.00),
(1353, 3, 50, 2024, 'Friday', NULL, NULL, 'Planned', '', 0.00),
(1354, 3, 50, 2024, 'Saturday', '10:00:00', '18:00:00', 'Planned', '', 8.00),
(1355, 3, 50, 2024, 'Sunday', NULL, NULL, 'Planned', '', 0.00),
(1356, 3, 51, 2024, 'Monday', NULL, NULL, 'Planned', '', 0.00),
(1357, 3, 51, 2024, 'Tuesday', NULL, NULL, 'Planned', '', 0.00),
(1358, 3, 51, 2024, 'Wednesday', NULL, NULL, 'Planned', '', 0.00),
(1359, 3, 51, 2024, 'Thursday', NULL, NULL, 'Planned', '', 0.00),
(1360, 3, 51, 2024, 'Friday', NULL, NULL, 'Planned', '', 0.00),
(1361, 3, 51, 2024, 'Saturday', '14:00:00', '21:00:00', 'Planned', '', 7.00),
(1362, 3, 51, 2024, 'Sunday', '10:00:00', '18:00:00', 'Planned', '', 8.00),
(1363, 5, 50, 2024, 'Monday', '14:00:00', '16:00:00', 'Planned', '', 2.00),
(1364, 5, 50, 2024, 'Tuesday', '14:00:00', '16:00:00', 'Planned', '', 2.00),
(1365, 5, 50, 2024, 'Wednesday', '14:00:00', '16:00:00', 'Planned', '', 2.00),
(1366, 5, 50, 2024, 'Thursday', '14:00:00', '16:00:00', 'Planned', '', 2.00),
(1367, 5, 50, 2024, 'Friday', '14:00:00', '16:00:00', 'Planned', '', 2.00);
INSERT INTO `schedules` (`id`, `user_id`, `week_number`, `year`, `day_of_week`, `start_time`, `end_time`, `status`, `comments`, `total_planned_hours`) VALUES
(1368, 5, 50, 2024, 'Saturday', '13:00:00', '17:00:00', 'Planned', '', 4.00),
(1369, 5, 50, 2024, 'Sunday', NULL, NULL, 'Planned', '', 0.00),
(1370, 6, 50, 2024, 'Monday', '14:00:00', '16:00:00', 'Planned', '', 2.00),
(1371, 6, 50, 2024, 'Tuesday', '14:00:00', '16:00:00', 'Planned', '', 2.00),
(1372, 6, 50, 2024, 'Wednesday', '14:00:00', '16:00:00', 'Planned', '', 2.00),
(1373, 6, 50, 2024, 'Thursday', '14:00:00', '16:00:00', 'Planned', '', 2.00),
(1374, 6, 50, 2024, 'Friday', '14:00:00', '16:00:00', 'Planned', '', 2.00),
(1375, 6, 50, 2024, 'Saturday', '13:00:00', '17:00:00', 'Planned', '', 4.00),
(1376, 6, 50, 2024, 'Sunday', NULL, NULL, 'Planned', '', 0.00);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `tasks`
--

CREATE TABLE `tasks` (
  `id` int NOT NULL,
  `task_name` varchar(255) NOT NULL,
  `task_description` text,
  `task_type` enum('day','other') DEFAULT 'other',
  `creation_date` datetime DEFAULT CURRENT_TIMESTAMP,
  `end_date` datetime DEFAULT NULL,
  `completed` tinyint(1) DEFAULT '0',
  `user_id` int NOT NULL,
  `created_by` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Gegevens worden geëxporteerd voor tabel `tasks`
--

INSERT INTO `tasks` (`id`, `task_name`, `task_description`, `task_type`, `creation_date`, `end_date`, `completed`, `user_id`, `created_by`) VALUES
(3, 'Foto toevoegen aan nieuw artikel (blokken)', '<p>Andy kan jij een fout maken en toevoegen aan je artikel en die dringend op de webshop zetten voor verkoop.</p>', 'other', '2024-10-31 16:33:55', '2024-11-09 18:00:00', 1, 1, 1),
(4, 'Product omschrijving', '<p>Bij insert_produts.php map epoxyhars na kijken bij product omschrijving. ( voeg niet mee toe)</p>', 'other', '2024-12-01 13:20:53', '2024-12-05 10:00:00', 1, 1, 1);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `task_assignments`
--

CREATE TABLE `task_assignments` (
  `task_id` int NOT NULL,
  `assigned_user_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Gegevens worden geëxporteerd voor tabel `task_assignments`
--

INSERT INTO `task_assignments` (`task_id`, `assigned_user_id`) VALUES
(3, 1),
(4, 1),
(3, 2);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `time_logs`
--

CREATE TABLE `time_logs` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `date` date DEFAULT NULL,
  `day_of_week` enum('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday') DEFAULT NULL,
  `clock_in` datetime DEFAULT NULL,
  `clock_out` datetime DEFAULT NULL,
  `status` enum('in','out') DEFAULT 'out',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `type` enum('Present','Sick','Vacation','Unpaid Leave') DEFAULT 'Present',
  `total_clocked_hours` decimal(5,2) DEFAULT NULL,
  `modified_by_admin` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Gegevens worden geëxporteerd voor tabel `time_logs`
--

INSERT INTO `time_logs` (`id`, `user_id`, `date`, `day_of_week`, `clock_in`, `clock_out`, `status`, `created_at`, `updated_at`, `type`, `total_clocked_hours`, `modified_by_admin`) VALUES
(1, 1, '2024-09-02', 'Monday', '2024-09-02 19:00:00', '2024-09-02 21:00:00', 'out', '2024-09-02 17:00:00', '2024-09-24 08:42:02', 'Present', 2.00, 0),
(2, 1, '2024-09-03', 'Tuesday', '2024-09-03 19:00:00', '2024-09-03 21:00:00', 'out', '2024-09-03 17:00:00', '2024-09-24 08:42:02', 'Present', 2.00, 0),
(3, 1, '2024-09-04', 'Wednesday', '2024-09-04 19:00:00', '2024-09-04 21:00:00', 'out', '2024-09-04 17:00:00', '2024-09-24 08:42:02', 'Present', 2.00, 0),
(4, 1, '2024-09-05', 'Thursday', '2024-09-05 09:34:53', '2024-09-05 21:02:18', 'out', '2024-09-05 12:21:56', '2024-09-24 08:43:28', 'Present', 11.46, 0),
(5, 1, '2024-09-06', 'Friday', '2024-09-06 08:26:38', '2024-09-06 21:03:25', 'out', '2024-09-06 06:26:38', '2024-09-24 08:43:28', 'Present', 12.61, 0),
(6, 1, '2024-09-07', 'Saturday', '2024-09-07 09:50:00', '2024-09-07 18:00:00', 'out', '2024-09-07 08:45:26', '2024-09-24 08:42:02', 'Present', 8.17, 0),
(7, 1, '2024-09-09', 'Monday', '2024-09-09 19:00:00', '2024-09-09 21:00:00', 'out', '2024-09-12 09:14:09', '2024-09-24 08:42:02', 'Present', 2.00, 0),
(8, 1, '2024-09-10', 'Tuesday', '2024-09-10 19:00:00', '2024-09-10 21:00:00', 'out', '2024-09-12 09:14:47', '2024-09-24 08:42:02', 'Present', 2.00, 0),
(9, 1, '2024-09-11', 'Wednesday', '2024-09-11 19:00:00', '2024-09-11 21:00:00', 'out', '2024-09-12 09:15:22', '2024-09-24 08:42:02', 'Present', 2.00, 0),
(10, 1, '2024-09-12', 'Thursday', '2024-09-12 10:00:00', '2024-09-12 21:11:15', 'out', '2024-09-12 09:16:02', '2024-09-24 08:43:28', 'Present', 11.19, 0),
(11, 1, '2024-09-13', 'Friday', '2024-09-13 09:02:12', '2024-09-13 21:04:40', 'out', '2024-09-13 07:02:12', '2024-09-24 08:43:28', 'Present', 12.04, 0),
(12, 1, '2024-09-14', 'Saturday', '2024-09-14 10:11:24', '2024-09-14 19:48:32', 'out', '2024-09-14 08:11:24', '2024-09-24 08:42:02', 'Present', 9.62, 0),
(13, 1, '2024-09-15', 'Sunday', '2024-09-15 11:00:00', '2024-09-15 18:00:00', 'out', '2024-09-15 11:01:01', '2024-09-24 08:42:02', 'Present', 7.00, 0),
(14, 5, '2024-09-15', 'Sunday', '2024-09-15 11:00:00', '2024-09-15 18:00:00', 'out', '2024-09-15 11:04:12', '2024-10-12 07:34:05', 'Present', 7.00, 1),
(15, 1, '2024-09-16', 'Monday', '2024-09-16 19:00:00', '2024-09-16 21:00:00', 'out', '2024-09-16 19:46:27', '2024-09-24 08:42:02', 'Present', 2.00, 0),
(16, 1, '2024-08-17', 'Tuesday', '2024-08-17 19:00:00', '2024-08-17 21:00:00', 'out', '2024-09-18 17:24:31', '2024-09-24 08:42:02', 'Present', 2.00, 0),
(17, 1, '2024-09-18', 'Wednesday', '2024-09-18 19:24:00', '2024-09-18 21:40:00', 'out', '2024-09-18 17:24:43', '2024-09-24 08:42:02', 'Present', 2.27, 0),
(18, 1, '2024-09-17', 'Tuesday', '2024-09-17 19:00:00', '2024-09-17 21:00:00', 'out', '2024-09-18 17:26:44', '2024-09-24 08:42:02', 'Present', 2.00, 0),
(19, 1, '2024-09-19', 'Thursday', '2024-09-19 08:50:05', '2024-09-19 21:41:09', 'out', '2024-09-19 06:50:05', '2024-09-24 08:42:02', 'Present', 12.85, 0),
(21, 3, '2024-09-18', 'Wednesday', '2024-09-18 19:20:00', '2024-09-18 21:00:00', 'out', '2024-09-19 10:23:28', '2024-10-12 07:34:14', 'Present', 1.67, 1),
(22, 1, '2024-09-20', 'Friday', '2024-09-20 09:34:45', '2024-09-20 21:02:49', 'out', '2024-09-20 07:34:45', '2024-09-24 08:42:02', 'Present', 11.47, 0),
(23, 1, '2024-09-21', 'Saturday', '2024-09-21 09:21:00', '2024-09-21 18:20:00', 'out', '2024-09-21 08:54:51', '2024-09-24 08:42:02', 'Present', 8.98, 0),
(24, 1, '2024-09-22', 'Sunday', '2024-09-22 10:22:00', '2024-09-22 15:30:00', 'out', '2024-09-22 08:22:17', '2024-09-24 06:43:59', 'Present', 5.13, 0),
(25, 1, '2024-09-23', 'Monday', '2024-09-23 19:00:00', '2024-09-23 21:15:48', 'out', '2024-09-23 17:00:00', '2024-09-24 08:43:28', 'Present', 2.26, 0),
(26, 1, '2024-09-24', 'Tuesday', '2024-09-24 19:00:00', '2024-09-24 21:03:00', 'out', '2024-09-25 11:15:01', '2024-09-25 11:15:01', 'Present', 2.05, 0),
(27, 1, '2024-09-25', 'Wednesday', '2024-09-25 19:00:00', '2024-09-25 21:00:00', 'out', '2024-09-26 06:58:51', '2024-09-26 06:58:51', 'Present', 2.00, 0),
(28, 1, '2024-09-26', 'Thursday', '2024-09-26 10:15:33', '2024-09-26 21:50:24', 'out', '2024-09-26 08:15:33', '2024-09-26 19:50:24', 'Present', 11.57, 0),
(29, 1, '2024-09-27', 'Friday', '2024-09-27 10:00:00', '2024-09-27 20:00:00', 'out', '2024-09-27 18:44:56', '2024-09-27 18:44:56', 'Present', 10.00, 0),
(30, 3, '2024-09-27', 'Friday', '2024-09-27 10:00:00', '2024-09-27 20:00:00', 'out', '2024-09-27 18:48:01', '2024-10-12 07:34:33', 'Present', 10.00, 1),
(31, 1, '2024-09-28', 'Saturday', '2024-09-28 10:00:00', '2024-09-28 18:00:00', 'out', '2024-09-29 09:33:08', '2024-09-29 09:33:08', 'Present', 8.00, 0),
(32, 3, '2024-09-28', 'Saturday', '2024-09-28 10:00:00', '2024-09-28 18:00:00', 'out', '2024-09-29 09:33:48', '2024-10-12 07:34:38', 'Present', 8.00, 1),
(33, 1, '2024-09-30', 'Monday', '2024-09-30 19:00:00', '2024-09-30 21:00:00', 'out', '2024-10-01 12:51:24', '2024-10-01 12:51:24', 'Present', 2.00, 0),
(34, 1, '2024-10-01', 'Tuesday', '2024-10-01 19:00:00', '2024-10-01 21:00:00', 'out', '2024-10-02 07:42:16', '2024-10-02 07:42:16', 'Present', 2.00, 0),
(35, 1, '2024-10-02', 'Wednesday', '2024-10-02 19:00:00', '2024-10-02 21:17:00', 'out', '2024-10-03 07:17:59', '2024-10-03 07:17:59', 'Present', 2.28, 0),
(36, 1, '2024-10-03', 'Thursday', '2024-10-03 10:00:00', '2024-10-03 21:00:00', 'out', '2024-10-04 06:22:40', '2024-10-06 09:03:43', 'Present', 11.00, 0),
(37, 1, '2024-10-05', 'Saturday', '2024-10-05 10:00:00', '2024-10-05 18:00:00', 'out', '2024-10-06 07:44:15', '2024-10-06 07:44:15', 'Present', 8.00, 0),
(38, 1, '2024-10-07', 'Monday', '2024-10-07 19:00:00', '2024-10-07 21:00:00', 'out', '2024-10-10 05:45:46', '2024-10-10 05:45:46', 'Present', 2.00, 0),
(39, 1, '2024-10-08', 'Tuesday', '2024-10-08 19:00:00', '2024-10-08 21:00:00', 'out', '2024-10-10 05:46:17', '2024-10-10 05:46:17', 'Present', 2.00, 0),
(40, 1, '2024-10-09', 'Wednesday', '2024-10-09 19:00:00', '2024-10-09 21:00:00', 'out', '2024-10-10 05:46:49', '2024-10-10 05:46:49', 'Present', 2.00, 0),
(41, 1, '2024-10-10', 'Thursday', '2024-10-10 07:47:00', '2024-10-10 21:00:00', 'out', '2024-10-10 05:47:33', '2024-10-10 20:27:31', 'Present', 13.22, 0),
(42, 1, '2024-10-11', 'Friday', '2024-10-11 10:14:00', '2024-10-11 21:00:00', 'out', '2024-10-11 08:14:54', '2024-10-12 07:14:47', 'Present', 10.77, 1),
(43, 1, '2024-10-12', 'Saturday', '2024-10-12 10:00:00', '2024-10-12 18:00:00', 'out', '2024-10-12 17:15:31', '2024-10-12 17:15:31', 'Present', 8.00, 1),
(44, 1, '2024-10-14', 'Monday', '2024-10-14 19:00:00', '2024-10-14 21:00:00', 'out', '2024-10-18 16:31:20', '2024-10-18 16:31:20', 'Present', 2.00, 1),
(45, 1, '2024-10-15', 'Tuesday', '2024-10-15 19:00:00', '2024-10-15 21:00:00', 'out', '2024-10-18 16:31:48', '2024-10-18 16:31:48', 'Present', 2.00, 1),
(46, 1, '2024-10-17', 'Thursday', '2024-10-17 08:00:00', '2024-10-17 19:02:00', 'out', '2024-10-18 16:32:54', '2024-10-18 16:32:54', 'Present', 11.03, 1),
(47, 1, '2024-10-16', 'Wednesday', '2024-10-16 19:00:00', '2024-10-16 19:00:00', 'out', '2024-10-18 16:35:35', '2024-10-18 16:37:56', 'Present', 0.00, 1),
(48, 1, '2024-10-18', 'Friday', '2024-10-18 10:45:00', '2024-10-18 20:13:00', 'out', '2024-10-18 16:38:16', '2024-10-18 18:18:28', 'Present', 9.47, 1),
(49, 1, '2024-10-19', 'Saturday', '2024-10-19 10:15:19', '2024-10-19 19:12:54', 'out', '2024-10-19 08:15:19', '2024-10-19 17:12:54', 'Present', 8.95, 0),
(50, 6, '2024-10-19', 'Saturday', '2024-10-19 13:00:00', '2024-10-19 16:00:00', 'out', '2024-10-20 13:32:28', '2024-10-20 13:32:28', 'Present', 3.00, 1),
(51, 5, '2024-10-19', 'Saturday', '2024-10-19 14:00:00', '2024-10-19 15:00:00', 'out', '2024-10-20 13:33:27', '2024-10-20 13:33:27', 'Present', 1.00, 1),
(52, 1, '2024-10-20', 'Sunday', '2024-10-20 13:00:00', '2024-10-20 16:00:00', 'out', '2024-10-21 06:41:37', '2024-10-21 06:41:37', 'Present', 3.00, 1),
(53, 3, '2024-10-20', 'Sunday', '2024-10-20 13:00:00', '2024-10-20 16:00:00', 'out', '2024-10-21 06:42:36', '2024-10-21 06:42:36', 'Present', 3.00, 1),
(54, 5, '2024-10-20', 'Sunday', '2024-10-20 10:00:00', '2024-10-20 16:00:00', 'out', '2024-10-21 06:43:55', '2024-10-21 06:43:55', 'Present', 6.00, 1),
(55, 6, '2024-10-20', 'Sunday', '2024-10-20 10:00:00', '2024-10-20 16:00:00', 'out', '2024-10-21 06:44:30', '2024-10-21 06:44:30', 'Present', 6.00, 1),
(57, 5, '2024-10-21', 'Monday', '2024-10-21 09:26:58', '2024-10-21 11:30:32', 'out', '2024-10-21 07:26:58', '2024-10-21 09:30:32', 'Present', 2.05, 0),
(58, 6, '2024-10-21', 'Monday', '2024-10-21 09:26:58', '2024-10-21 11:30:32', 'out', '2024-10-21 07:26:58', '2024-10-21 09:30:32', 'Present', 2.05, 0),
(59, 1, '2024-10-21', 'Monday', '2024-10-21 19:00:00', '2024-10-21 21:00:00', 'out', '2024-10-22 06:36:19', '2024-10-22 06:36:19', 'Present', 2.00, 1),
(60, 5, '2024-10-27', 'Sunday', '2024-10-27 10:16:59', '2024-10-27 11:03:01', 'out', '2024-10-27 09:16:59', '2024-10-27 10:03:01', 'Present', 0.77, 0),
(61, 6, '2024-10-29', 'Tuesday', '2024-10-29 15:00:38', '2024-10-29 17:35:47', 'out', '2024-10-29 14:00:38', '2024-10-29 16:35:47', 'Present', 2.58, 0),
(62, 6, '2024-10-30', 'Wednesday', '2024-10-30 14:18:49', '2024-10-30 14:57:15', 'out', '2024-10-30 13:18:49', '2024-10-30 13:57:15', 'Present', 0.63, 0),
(63, 5, '2024-04-17', 'Saturday', '2024-04-27 17:00:00', '2024-04-27 21:15:00', 'out', '2024-10-30 18:44:11', '2024-10-30 18:44:11', 'Present', 4.25, 1),
(64, 6, '2024-04-27', 'Saturday', '2024-04-27 17:00:00', '2024-04-27 21:15:00', 'out', '2024-10-30 18:44:55', '2024-10-30 18:44:55', 'Present', 4.25, 1),
(65, 5, '2024-06-16', 'Sunday', '2024-06-16 11:00:00', '2024-06-16 18:45:00', 'out', '2024-10-30 18:47:06', '2024-10-30 18:47:06', 'Present', 7.75, 1),
(66, 6, '2024-06-16', 'Sunday', '2024-06-16 11:00:00', '2024-06-16 18:45:00', 'out', '2024-10-30 18:47:45', '2024-10-30 18:47:45', 'Present', 7.75, 1),
(67, 5, '2024-07-22', 'Monday', '2024-07-22 15:00:00', '2024-07-22 18:30:00', 'out', '2024-10-30 18:50:47', '2024-10-30 18:50:47', 'Present', 3.50, 1),
(68, 6, '2024-07-22', 'Monday', '2024-07-22 15:00:00', '2024-07-22 18:30:00', 'out', '2024-10-30 18:51:42', '2024-10-30 18:51:42', 'Present', 3.50, 1),
(69, 5, '2024-07-13', 'Saturday', '2024-07-13 11:15:00', '2024-07-13 18:30:00', 'out', '2024-10-30 18:54:03', '2024-10-30 18:54:03', 'Present', 7.25, 1),
(70, 6, '2024-07-13', 'Saturday', '2024-07-13 11:15:00', '2024-07-13 18:30:00', 'out', '2024-10-30 18:54:43', '2024-10-30 18:54:43', 'Present', 7.25, 1),
(71, 5, '2024-08-20', 'Tuesday', '2024-08-20 15:30:00', '2024-08-20 18:00:00', 'out', '2024-10-30 18:56:25', '2024-10-30 18:56:25', 'Present', 2.50, 1),
(72, 6, '2024-08-20', 'Tuesday', '2024-08-20 15:30:00', '2024-08-20 18:00:00', 'out', '2024-10-30 18:57:01', '2024-10-30 18:57:01', 'Present', 2.50, 1),
(73, 5, '2024-08-23', 'Friday', '2024-08-23 17:00:00', '2024-08-23 22:15:00', 'out', '2024-10-30 18:57:50', '2024-10-30 18:57:50', 'Present', 5.25, 1),
(74, 6, '2024-08-23', 'Friday', '2024-08-23 17:00:00', '2024-08-23 22:15:00', 'out', '2024-10-30 18:58:53', '2024-10-30 18:58:53', 'Present', 5.25, 1),
(76, 6, '2024-11-01', 'Friday', '2024-11-01 13:46:47', '2024-11-01 16:56:05', 'out', '2024-11-01 12:46:47', '2024-11-01 15:56:05', 'Present', 3.15, 0),
(77, 5, '2024-11-01', 'Friday', '2024-11-01 09:44:27', '2024-11-01 17:52:00', 'out', '2024-11-01 13:45:27', '2024-11-04 16:52:14', 'Present', 18.80, 0),
(79, 5, '2024-11-02', 'Saturday', '2024-11-02 09:33:47', '2024-11-02 15:40:51', 'out', '2024-11-02 08:33:47', '2024-11-02 14:40:51', 'Present', 6.12, 0),
(80, 6, '2024-11-02', 'Saturday', '2024-11-02 12:55:41', '2024-11-02 14:48:58', 'out', '2024-11-02 11:55:41', '2024-11-02 13:48:58', 'Present', 1.88, 0),
(81, 5, '2024-11-07', 'Thursday', '2024-11-07 15:00:35', '2024-11-07 16:49:20', 'out', '2024-11-07 14:00:35', '2024-11-07 15:49:20', 'Present', 1.80, 0),
(82, 5, '2024-11-08', 'Friday', '2024-11-08 11:04:07', '2024-11-08 15:35:31', 'out', '2024-11-08 10:04:07', '2024-11-08 14:35:31', 'Present', 4.52, 0),
(83, 5, '2024-11-22', 'Friday', '2024-11-22 14:36:00', '2024-11-22 14:46:00', 'out', '2024-11-22 14:06:03', '2024-11-22 15:13:26', 'Present', 0.17, 1),
(84, 5, '2024-11-22', 'Friday', '2024-11-22 14:36:00', '2024-11-22 16:10:00', 'out', '2024-11-22 15:14:39', '2024-11-22 15:14:39', 'Present', 1.57, 1),
(85, 1, '2024-11-22', 'Friday', '2024-11-22 16:15:16', '2024-11-22 16:25:31', 'out', '2024-11-22 15:15:16', '2024-11-22 15:25:31', 'Present', 0.17, 0),
(86, 6, '2024-11-22', 'Friday', '2024-11-22 14:36:00', '2024-11-22 16:10:00', 'out', '2024-11-22 15:24:03', '2024-11-22 15:25:07', 'Present', 1.57, 1),
(87, 6, '2024-11-27', 'Wednesday', '2024-11-27 12:27:00', '2024-11-27 15:46:00', 'out', '2024-11-27 11:27:50', '2024-12-01 12:05:14', 'Present', 3.32, 1),
(88, 5, '2024-11-27', 'Wednesday', '2024-11-27 13:38:54', '2024-11-27 15:56:19', 'out', '2024-11-27 12:38:54', '2024-12-01 12:02:41', 'Present', 2.28, 0),
(90, 6, '2024-11-28', 'Thursday', '2024-11-28 14:11:26', '2024-11-28 16:21:56', 'out', '2024-11-28 13:11:26', '2024-11-28 15:21:56', 'Present', 2.17, 0),
(91, 5, '2024-11-30', 'Saturday', '2024-11-30 15:57:17', '2024-11-30 17:30:47', 'out', '2024-11-30 14:57:17', '2024-11-30 16:30:47', 'Present', 1.55, 0),
(92, 6, '2024-12-05', 'Thursday', '2024-12-05 14:16:43', '2024-12-05 16:35:08', 'out', '2024-12-05 13:16:43', '2024-12-05 15:35:08', 'Present', 2.30, 0),
(93, 5, '2024-12-05', 'Thursday', '2024-12-05 15:08:22', '2024-12-05 16:41:44', 'out', '2024-12-05 14:08:22', '2024-12-05 15:41:44', 'Present', 1.55, 0);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `user_communication_messages`
--

CREATE TABLE `user_communication_messages` (
  `id` int NOT NULL,
  `message` text NOT NULL,
  `link_message` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `message_type` enum('day','week') NOT NULL,
  `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Gegevens worden geëxporteerd voor tabel `user_communication_messages`
--

INSERT INTO `user_communication_messages` (`id`, `message`, `link_message`, `message_type`, `date`) VALUES
(6, '<p>Hallo iedereen,</p>\r\n<p>Er is een nieuw verslag bericht beschikbaar op ondrive in de map verslagen.</p>\r\n<p>Lees deze eens rustig door. Dit verslag is opgemaakt van wegen besprekening van afgelopen zondag.</p>\r\n<p>Fijne Donderdag allemaal,</p>\r\n<p>Andy</p>', 'https://coolswindels.sharepoint.com/:b:/s/Filiaal-99682/ERw7YwZ5bRFEoyTmw7JwmrsBIi2XkKkBU8XKX9dYkzt1kQ?e=PNdV1y', 'day', '2024-10-24 14:19:00'),
(7, '<p>Hallo allemaal,</p>\r\n<p>Deze week komt er weer een belangerijke update op dit platform. We slaan 1 update over en plaatsen een grote update met extra beveiliging bij inlogen zoals een 2 stap verificatie. nieuwe pagina om bestelling te plaatsen van de winkel naar Oosteeklo toe zodat ze in Oosteeklo weten wat er moet gemaakt worden aan products. En we voegen een functie Taken toe zodat we aan elkaar taken kunnen doorgeven die nodig zijn. Zo moet er minder gebeld of gewhatsappt worden naar elkaar. en is het werk vlotter gedaan. De update staat geplant voor vrijdag 1/10 of zaterdag 2/10 als alles goed gaat. opdit moment testen we alle functie.</p>\r\n<p>Veel succes deze week met alles.&nbsp;</p>\r\n<p>Mvg, Andy</p>', '', 'week', '2024-10-27 22:05:00'),
(8, '<p>Hallo iedereen,&nbsp;</p>\r\n<p>Kleine mededeling de kerst markt in Oudsbergen is Deventief goedgekeurd.</p>\r\n<p>De tickets krijgen we begin dcember als ook de reclame afiche.</p>\r\n<p>&nbsp;</p>\r\n<p>Fijne donderdag verder.</p>', '', 'day', '2024-10-31 16:40:00'),
(9, '<p>Hallo allemaal,</p>\r\n<p>Vanaf vandaag gaan de winter openingsuren in en dit tussen Nov en Mei&nbsp;</p>\r\n<p>van Ma tot Wo van 19:00 tot 21:00 en Van Do tot Za van 10:00 tot 18:00</p>\r\n<p>Fijne vrijdag allemaal en geniet van de feestdag.</p>', '', 'day', '2024-11-01 10:00:00'),
(10, '<p>De werken in de straat zijn terug gestart dus de put voor de deur is terug dicht ze schuiven op naar de hoofd weg. Dat wil zeggen dat we terug open kunnen vanaf 2 dec en de webshop terug open op 30 Nov.&nbsp;</p>\r\n<p>er wordt geen nieuwe aanvraag voor sluitings premi aangevraagd. &nbsp;</p>\r\n<p>&nbsp;</p>\r\n<p>Mvg. Andy</p>', '', 'day', '2024-11-21 12:59:00'),
(11, '<p>Het probleem met inloggen zonder 2 stap verificatie is opgelost.</p>\r\n<p>Mijn excuses voor het ongemak. Iedereen kan terug normaal inloggen.</p>\r\n<p>Mvg Andy</p>', '', 'day', '2024-11-23 09:05:00'),
(12, '<p>Hallo Allemaal,</p>\r\n<p>Zonet is de webshop geopend voor bestelligen vanaf maandag open we ook de winkel terug. Dus we gaan volop terug moeten promoten van onze webshop en artikelen.</p>\r\n<p>Mvg Andy</p>', '', 'day', '2024-11-30 10:00:00'),
(13, '<p>Hallo allemaal,</p>\r\n<p>We hebben op donderdag 28-11 akkoord gegeven aan Matthias van https://matthias.webcrafters.be/&nbsp; om onze gloednieuwe website te bouwen dus vanaf eind juli gaan we onze nieuwe website online zetten. en gaan we met windels green &amp; deco resin naar 2.0 we gaan onze products zoals handgemaakte kaarsen en terrazzo in de kijker zetten.&nbsp; ook zal er van 1 webshop naar 2 webshops gaan 1 voor handgemaakte products en de 2de voor onze verse products.&nbsp; In mei wordt&nbsp; er een vergadering gehouden hoe we heropening gaan organiseren. en we allemaal gaan doen. in tussen tijds gaan we ook de uiterijk van de&nbsp; winkel aanpassen door schilder werken uit te voeren. en kijken om alles anders te zetten.</p>', '', 'week', '2024-12-02 10:00:00');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `user_communication_messages_read`
--

CREATE TABLE `user_communication_messages_read` (
  `id` int NOT NULL,
  `message_id` int NOT NULL,
  `admin_id` int NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `read_on` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Gegevens worden geëxporteerd voor tabel `user_communication_messages_read`
--

INSERT INTO `user_communication_messages_read` (`id`, `message_id`, `admin_id`, `is_read`, `read_on`) VALUES
(1, 7, 1, 1, NULL),
(2, 8, 1, 1, NULL),
(3, 7, 5, 1, '2024-10-31 19:07:19'),
(4, 10, 1, 1, '2024-11-21 12:00:18'),
(5, 11, 1, 1, '2024-11-24 20:39:28'),
(6, 12, 5, 1, '2024-11-30 14:57:02');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `user_messages`
--

CREATE TABLE `user_messages` (
  `id` int NOT NULL,
  `message_id` int NOT NULL,
  `admin_id` int NOT NULL,
  `is_read` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Gegevens worden geëxporteerd voor tabel `user_messages`
--

INSERT INTO `user_messages` (`id`, `message_id`, `admin_id`, `is_read`) VALUES
(19, 4, 1, 1),
(20, 4, 2, 1),
(21, 4, 3, 0),
(22, 4, 4, 0),
(23, 4, 5, 1),
(24, 4, 6, 1),
(25, 5, 1, 1),
(26, 5, 2, 1),
(27, 5, 3, 0),
(28, 5, 4, 0),
(29, 5, 5, 1),
(30, 5, 6, 1),
(31, 6, 1, 1),
(32, 6, 2, 1),
(33, 6, 3, 0),
(34, 6, 4, 0),
(35, 6, 5, 1),
(36, 6, 6, 1),
(37, 7, 1, 1),
(38, 8, 2, 0),
(39, 9, 3, 0),
(40, 10, 5, 1),
(41, 11, 6, 0);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `whistleblower_reports`
--

CREATE TABLE `whistleblower_reports` (
  `id` int NOT NULL,
  `admin_id` int NOT NULL,
  `report_date` datetime NOT NULL,
  `report` text NOT NULL,
  `status` varchar(50) DEFAULT 'Open'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `admins_user`
--
ALTER TABLE `admins_user`
  ADD PRIMARY KEY (`adminuser_id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Indexen voor tabel `admins_userfunctie`
--
ALTER TABLE `admins_userfunctie`
  ADD PRIMARY KEY (`admins_functieuserid`);

--
-- Indexen voor tabel `admins_workforce`
--
ALTER TABLE `admins_workforce`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexen voor tabel `alerts`
--
ALTER TABLE `alerts`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `break_logs`
--
ALTER TABLE `break_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `time_log_id` (`time_log_id`);

--
-- Indexen voor tabel `buttons`
--
ALTER TABLE `buttons`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `button_roles`
--
ALTER TABLE `button_roles`
  ADD PRIMARY KEY (`button_id`,`role`);

--
-- Indexen voor tabel `chatemessages`
--
ALTER TABLE `chatemessages`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `company`
--
ALTER TABLE `company`
  ADD PRIMARY KEY (`company_id`);

--
-- Indexen voor tabel `contracts`
--
ALTER TABLE `contracts`
  ADD PRIMARY KEY (`contract_id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Indexen voor tabel `daily_messages`
--
ALTER TABLE `daily_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `employment`
--
ALTER TABLE `employment`
  ADD PRIMARY KEY (`employment_id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Indexen voor tabel `gdpr_acceptance`
--
ALTER TABLE `gdpr_acceptance`
  ADD PRIMARY KEY (`id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Indexen voor tabel `gdpr_content`
--
ALTER TABLE `gdpr_content`
  ADD PRIMARY KEY (`version`);

--
-- Indexen voor tabel `gdpr_version`
--
ALTER TABLE `gdpr_version`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `payslips`
--
ALTER TABLE `payslips`
  ADD PRIMARY KEY (`payslip_id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Indexen voor tabel `schedules`
--
ALTER TABLE `schedules`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`,`week_number`,`year`,`day_of_week`);

--
-- Indexen voor tabel `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexen voor tabel `task_assignments`
--
ALTER TABLE `task_assignments`
  ADD PRIMARY KEY (`task_id`,`assigned_user_id`),
  ADD KEY `assigned_user_id` (`assigned_user_id`);

--
-- Indexen voor tabel `time_logs`
--
ALTER TABLE `time_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexen voor tabel `user_communication_messages`
--
ALTER TABLE `user_communication_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `user_communication_messages_read`
--
ALTER TABLE `user_communication_messages_read`
  ADD PRIMARY KEY (`id`),
  ADD KEY `message_id` (`message_id`),
  ADD KEY `user_id` (`admin_id`);

--
-- Indexen voor tabel `user_messages`
--
ALTER TABLE `user_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `message_id` (`message_id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Indexen voor tabel `whistleblower_reports`
--
ALTER TABLE `whistleblower_reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- AUTO_INCREMENT voor geëxporteerde tabellen
--

--
-- AUTO_INCREMENT voor een tabel `admins_user`
--
ALTER TABLE `admins_user`
  MODIFY `adminuser_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT voor een tabel `admins_userfunctie`
--
ALTER TABLE `admins_userfunctie`
  MODIFY `admins_functieuserid` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT voor een tabel `admins_workforce`
--
ALTER TABLE `admins_workforce`
  MODIFY `admin_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT voor een tabel `alerts`
--
ALTER TABLE `alerts`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT voor een tabel `break_logs`
--
ALTER TABLE `break_logs`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT voor een tabel `buttons`
--
ALTER TABLE `buttons`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT voor een tabel `chatemessages`
--
ALTER TABLE `chatemessages`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT voor een tabel `company`
--
ALTER TABLE `company`
  MODIFY `company_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT voor een tabel `contracts`
--
ALTER TABLE `contracts`
  MODIFY `contract_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT voor een tabel `daily_messages`
--
ALTER TABLE `daily_messages`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT voor een tabel `employment`
--
ALTER TABLE `employment`
  MODIFY `employment_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT voor een tabel `gdpr_acceptance`
--
ALTER TABLE `gdpr_acceptance`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT voor een tabel `gdpr_version`
--
ALTER TABLE `gdpr_version`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT voor een tabel `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT voor een tabel `payslips`
--
ALTER TABLE `payslips`
  MODIFY `payslip_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT voor een tabel `schedules`
--
ALTER TABLE `schedules`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1489;

--
-- AUTO_INCREMENT voor een tabel `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT voor een tabel `time_logs`
--
ALTER TABLE `time_logs`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=94;

--
-- AUTO_INCREMENT voor een tabel `user_communication_messages`
--
ALTER TABLE `user_communication_messages`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT voor een tabel `user_communication_messages_read`
--
ALTER TABLE `user_communication_messages_read`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT voor een tabel `user_messages`
--
ALTER TABLE `user_messages`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT voor een tabel `whistleblower_reports`
--
ALTER TABLE `whistleblower_reports`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- Beperkingen voor geëxporteerde tabellen
--

--
-- Beperkingen voor tabel `admins_user`
--
ALTER TABLE `admins_user`
  ADD CONSTRAINT `fk_admin_id` FOREIGN KEY (`admin_id`) REFERENCES `admins_workforce` (`admin_id`);

--
-- Beperkingen voor tabel `break_logs`
--
ALTER TABLE `break_logs`
  ADD CONSTRAINT `break_logs_ibfk_1` FOREIGN KEY (`time_log_id`) REFERENCES `time_logs` (`id`) ON DELETE CASCADE;

--
-- Beperkingen voor tabel `button_roles`
--
ALTER TABLE `button_roles`
  ADD CONSTRAINT `button_roles_ibfk_1` FOREIGN KEY (`button_id`) REFERENCES `buttons` (`id`) ON DELETE CASCADE;

--
-- Beperkingen voor tabel `contracts`
--
ALTER TABLE `contracts`
  ADD CONSTRAINT `contracts_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `admins_workforce` (`admin_id`);

--
-- Beperkingen voor tabel `employment`
--
ALTER TABLE `employment`
  ADD CONSTRAINT `employment_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `admins_workforce` (`admin_id`);

--
-- Beperkingen voor tabel `gdpr_acceptance`
--
ALTER TABLE `gdpr_acceptance`
  ADD CONSTRAINT `gdpr_acceptance_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `admins_workforce` (`admin_id`);

--
-- Beperkingen voor tabel `payslips`
--
ALTER TABLE `payslips`
  ADD CONSTRAINT `payslips_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `admins_workforce` (`admin_id`);

--
-- Beperkingen voor tabel `tasks`
--
ALTER TABLE `tasks`
  ADD CONSTRAINT `tasks_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `admins_workforce` (`admin_id`);

--
-- Beperkingen voor tabel `task_assignments`
--
ALTER TABLE `task_assignments`
  ADD CONSTRAINT `task_assignments_ibfk_1` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `task_assignments_ibfk_2` FOREIGN KEY (`assigned_user_id`) REFERENCES `admins_workforce` (`admin_id`) ON DELETE CASCADE;

--
-- Beperkingen voor tabel `time_logs`
--
ALTER TABLE `time_logs`
  ADD CONSTRAINT `time_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `admins_workforce` (`admin_id`) ON DELETE CASCADE;

--
-- Beperkingen voor tabel `user_communication_messages_read`
--
ALTER TABLE `user_communication_messages_read`
  ADD CONSTRAINT `user_communication_messages_read_ibfk_1` FOREIGN KEY (`message_id`) REFERENCES `user_communication_messages` (`id`),
  ADD CONSTRAINT `user_communication_messages_read_ibfk_2` FOREIGN KEY (`admin_id`) REFERENCES `admins_workforce` (`admin_id`);

--
-- Beperkingen voor tabel `user_messages`
--
ALTER TABLE `user_messages`
  ADD CONSTRAINT `user_messages_ibfk_1` FOREIGN KEY (`message_id`) REFERENCES `messages` (`id`),
  ADD CONSTRAINT `user_messages_ibfk_2` FOREIGN KEY (`admin_id`) REFERENCES `admins_workforce` (`admin_id`);

--
-- Beperkingen voor tabel `whistleblower_reports`
--
ALTER TABLE `whistleblower_reports`
  ADD CONSTRAINT `whistleblower_reports_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `admins_workforce` (`admin_id`);
--
-- Database: `kantoor`
--
CREATE DATABASE IF NOT EXISTS `kantoor` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;
USE `kantoor`;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `events`
--

CREATE TABLE `events` (
  `id` int NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `location` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `start` datetime NOT NULL,
  `end` datetime NOT NULL,
  `employee_id` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `color` varchar(7) COLLATE utf8_unicode_ci NOT NULL DEFAULT '#378006',
  `status` enum('goedkeurd','afwachtend','geweigerd','afgelast') COLLATE utf8_unicode_ci DEFAULT 'afwachtend'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8_unicode_ci;

--
-- Gegevens worden geëxporteerd voor tabel `events`
--

INSERT INTO `events` (`id`, `title`, `location`, `start`, `end`, `employee_id`, `created_at`, `color`, `status`) VALUES
(2, 'Jaarmarkt Oosteeklo', 'Oosteeklo', '2024-08-20 17:00:00', '2024-08-20 21:30:00', 1, '2024-08-07 18:47:30', '#378006', 'goedkeurd'),
(3, 'Avondmarkt Moerbeke', 'Moerbeke', '2024-08-23 17:00:00', '2024-08-23 22:00:00', 1, '2024-08-07 18:50:56', '#378006', 'goedkeurd'),
(5, 'Indoor kerstmarkt', 'Oudsbergen', '2024-12-21 14:00:00', '2024-12-21 21:00:00', 1, '2024-11-08 09:53:30', '#378006', 'goedkeurd'),
(6, 'Indoor kerstmarkt', 'Oudsbergen', '2024-12-22 10:00:00', '2024-12-22 18:00:00', 1, '2024-11-08 09:53:38', '#378006', 'goedkeurd');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `event_waitlist`
--

CREATE TABLE `event_waitlist` (
  `id` int NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `location` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `start` datetime NOT NULL,
  `end` datetime NOT NULL,
  `employee_id` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `color` varchar(7) COLLATE utf8_unicode_ci DEFAULT '#378006',
  `status` enum('afwachtend','geweigerd','afgelast') COLLATE utf8_unicode_ci DEFAULT 'afwachtend'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8_unicode_ci;

--
-- Gegevens worden geëxporteerd voor tabel `event_waitlist`
--

INSERT INTO `event_waitlist` (`id`, `title`, `location`, `start`, `end`, `employee_id`, `created_at`, `color`, `status`) VALUES
(1, 'kerstmarkt Hamont', 'stationstraat 3930 Hamont-achel', '2024-12-28 09:30:00', '2024-12-28 16:00:00', 1, '2024-11-07 13:37:08', '#378006', 'afwachtend');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `holidays`
--

CREATE TABLE `holidays` (
  `id` int NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8_unicode_ci;

--
-- Gegevens worden geëxporteerd voor tabel `holidays`
--

INSERT INTO `holidays` (`id`, `title`, `date`) VALUES
(1, 'Nieuwjaar', '2024-01-01'),
(2, 'Pasen', '2024-03-31'),
(3, 'Paasmaandag', '2024-04-01'),
(4, 'Dag van de Arbeid', '2024-05-01'),
(5, 'O.L.H. Hemelvaart', '2024-05-09'),
(6, 'Pinksteren', '2024-05-19'),
(7, 'Pinkstermaandag', '2024-05-20'),
(8, 'Nationale Feestdag', '2024-07-21'),
(9, 'O.L.V. Hemelvaart', '2024-08-15'),
(10, 'Allerheiligen', '2024-11-01'),
(11, 'Wapenstilstand', '2024-11-11'),
(12, 'Kerstmis', '2024-12-25'),
(13, 'Nieuwjaar', '2025-01-01'),
(14, 'Pasen', '2025-04-20'),
(15, 'Paasmaandag', '2025-04-21'),
(16, 'Dag van de Arbeid', '2025-05-01'),
(17, 'O.L.H. Hemelvaart', '2025-05-29'),
(18, 'Pinksteren', '2025-06-08'),
(19, 'Pinkstermaandag', '2025-06-09'),
(20, 'Nationale Feestdag', '2025-07-21'),
(21, 'O.L.V. Hemelvaart', '2025-08-15'),
(22, 'Allerheiligen', '2025-11-01'),
(23, 'Wapenstilstand', '2025-11-11'),
(24, 'Kerstmis', '2025-12-25'),
(25, 'Nieuwjaar', '2026-01-01'),
(26, 'Pasen', '2026-04-05'),
(27, 'Paasmaandag', '2026-04-06'),
(28, 'Dag van de Arbeid', '2026-05-01'),
(29, 'O.L.H. Hemelvaart', '2026-05-14'),
(30, 'Pinksteren', '2026-05-24'),
(31, 'Pinkstermaandag', '2026-05-25'),
(32, 'Nationale Feestdag', '2026-07-21'),
(33, 'O.L.V. Hemelvaart', '2026-08-15'),
(34, 'Allerheiligen', '2026-11-01'),
(35, 'Wapenstilstand', '2026-11-11'),
(36, 'Kerstmis', '2026-12-25');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `salary`
--

CREATE TABLE `salary` (
  `id` int NOT NULL,
  `artikelid` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `Artikelshift` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `price` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `productiename` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `dag` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `maand` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `jaar` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8_unicode_ci;

--
-- Gegevens worden geëxporteerd voor tabel `salary`
--

INSERT INTO `salary` (`id`, `artikelid`, `Artikelshift`, `price`, `productiename`, `dag`, `maand`, `jaar`) VALUES
(1, '20013', '01:30', '5.8', '6', '2024', '05', '17'),
(2, '20076', '01:00', '1.17', '6', '2024', '05', '29'),
(3, '20074', '01:00', '1.95', '6', '2024', '05', '29'),
(4, '20076', '01:00', '1.17', '6', '2024', '05', '29');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(255) NOT NULL DEFAULT '',
  `password` varchar(255) NOT NULL DEFAULT '',
  `permissions` varchar(10) NOT NULL DEFAULT '',
  `homedir` varchar(1000) NOT NULL DEFAULT '',
  `email` varchar(255) NOT NULL DEFAULT '',
  `akey` varchar(255) NOT NULL DEFAULT '',
  `usage` bigint DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Gegevens worden geëxporteerd voor tabel `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `permissions`, `homedir`, `email`, `akey`, `usage`) VALUES
(1, 'admin', '0192023a7bbd73250516f069df18b500', 'rwu', '', '', '', NULL),
(2, 'Winkel_franky', '', 'r', '', '', '', NULL);

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `event_waitlist`
--
ALTER TABLE `event_waitlist`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `holidays`
--
ALTER TABLE `holidays`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `salary`
--
ALTER TABLE `salary`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `akey` (`akey`);

--
-- AUTO_INCREMENT voor geëxporteerde tabellen
--

--
-- AUTO_INCREMENT voor een tabel `events`
--
ALTER TABLE `events`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT voor een tabel `event_waitlist`
--
ALTER TABLE `event_waitlist`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT voor een tabel `holidays`
--
ALTER TABLE `holidays`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT voor een tabel `salary`
--
ALTER TABLE `salary`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT voor een tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
--
-- Database: `kassa`
--
CREATE DATABASE IF NOT EXISTS `kassa` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `kassa`;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `cash_count`
--

CREATE TABLE `cash_count` (
  `id` int NOT NULL,
  `cash_register_number` varchar(255) NOT NULL,
  `start_amount` decimal(10,2) NOT NULL,
  `cash_earned` decimal(10,2) NOT NULL,
  `card_earned` decimal(10,2) NOT NULL,
  `in_out_amount` decimal(10,2) NOT NULL,
  `calculated_total` decimal(10,2) NOT NULL,
  `cash_total` decimal(10,2) NOT NULL,
  `difference` decimal(10,2) NOT NULL,
  `new_start_amount` decimal(10,2) NOT NULL,
  `user_name` varchar(255) NOT NULL,
  `count_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `denomination_500` int DEFAULT '0',
  `denomination_200` int DEFAULT '0',
  `denomination_100` int NOT NULL DEFAULT '0',
  `denomination_50` int DEFAULT '0',
  `denomination_20` int DEFAULT '0',
  `denomination_10` int DEFAULT '0',
  `denomination_5` int DEFAULT '0',
  `denomination_2` int DEFAULT '0',
  `denomination_1` int DEFAULT '0',
  `denomination_0_5` int DEFAULT '0',
  `denomination_0_2` int DEFAULT '0',
  `denomination_0_1` int DEFAULT '0',
  `denomination_0_05` int DEFAULT '0',
  `denomination_0_02` int DEFAULT '0',
  `denomination_0_01` int DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `cash_count`
--

INSERT INTO `cash_count` (`id`, `cash_register_number`, `start_amount`, `cash_earned`, `card_earned`, `in_out_amount`, `calculated_total`, `cash_total`, `difference`, `new_start_amount`, `user_name`, `count_date`, `denomination_500`, `denomination_200`, `denomination_100`, `denomination_50`, `denomination_20`, `denomination_10`, `denomination_5`, `denomination_2`, `denomination_1`, `denomination_0_5`, `denomination_0_2`, `denomination_0_1`, `denomination_0_05`, `denomination_0_02`, `denomination_0_01`) VALUES
(1, 'Kassa 1', 258.95, 0.00, 0.00, 0.00, 0.00, 285.95, 0.00, 258.95, 'Andy W', '2024-08-15 21:11:03', 0, 0, 0, 1, 4, 5, 6, 9, 14, 16, 21, 31, 33, 0, 0),
(2, 'Kassa 1', 258.95, 0.00, 0.00, 0.00, 0.00, 285.95, 0.00, 258.95, 'Andy W', '2024-08-16 21:05:27', 0, 0, 0, 1, 4, 5, 6, 9, 14, 16, 21, 31, 33, 0, 0),
(3, 'Kassa 1', 258.95, 0.00, 0.00, 0.00, 0.00, 285.95, 0.00, 258.95, 'Andy W', '2024-08-17 18:07:58', 0, 0, 0, 1, 4, 5, 6, 9, 14, 16, 21, 31, 33, 0, 0),
(5, 'Kassa 1', 258.95, 0.00, 0.00, -5.00, 253.95, 253.95, 0.00, 253.95, 'Andy W', '2024-08-19 21:14:06', 0, 0, 0, 1, 4, 5, 5, 9, 14, 16, 21, 31, 33, 0, 0),
(6, 'Kassa 2', 253.95, 0.00, 4.06, 5.00, 258.95, 258.95, 0.00, 258.95, 'Andy W', '2024-08-20 21:16:41', 0, 0, 0, 1, 4, 5, 6, 9, 14, 16, 21, 31, 33, 0, 0),
(7, 'Kassa 1', 258.95, 0.00, 47.93, -11.00, 247.95, 242.95, 0.00, 247.95, 'Andy W', '2024-10-05 17:57:55', 0, 0, 0, 1, 4, 4, 6, 9, 14, 14, 21, 31, 33, 0, 0),
(8, 'Kassa 1', 247.95, 3.40, 11.06, 0.00, 251.35, 251.35, 0.00, 251.35, 'Andy W', '2024-10-19 19:45:20', 0, 0, 0, 1, 4, 4, 7, 9, 13, 13, 21, 30, 33, 0, 0);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `sales`
--

CREATE TABLE `sales` (
  `id` int NOT NULL,
  `cash_register_number` varchar(50) DEFAULT NULL,
  `sale_date` date DEFAULT NULL,
  `total_amount` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `sales`
--

INSERT INTO `sales` (`id`, `cash_register_number`, `sale_date`, `total_amount`) VALUES
(1, 'Kassa 1', '2024-08-15', 0.00),
(2, 'Kassa 1', '2024-08-16', 0.00),
(3, 'Kassa 1', '2024-08-17', 0.00),
(4, 'Kassa 1', '2024-08-19', 0.00),
(5, 'Kassa 2', '2024-08-20', 9.06),
(6, 'Kassa 1', '2024-10-05', 47.93),
(7, 'Kassa 1', '2024-10-19', 14.46);

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `cash_count`
--
ALTER TABLE `cash_count`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT voor geëxporteerde tabellen
--

--
-- AUTO_INCREMENT voor een tabel `cash_count`
--
ALTER TABLE `cash_count`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT voor een tabel `sales`
--
ALTER TABLE `sales`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- Database: `magazijn`
--
CREATE DATABASE IF NOT EXISTS `magazijn` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `magazijn`;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `magazijn_products`
--

CREATE TABLE `magazijn_products` (
  `id` int NOT NULL,
  `sku` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `product_image` varchar(255) DEFAULT NULL,
  `purchase_price_excl_vat` decimal(10,2) DEFAULT NULL,
  `purchase_price_incl_vat` decimal(10,2) DEFAULT NULL,
  `quantity_packaged` int DEFAULT NULL,
  `supplier_id` int DEFAULT NULL,
  `stock` int DEFAULT '0',
  `created_by_user` int DEFAULT NULL,
  `created_on` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `magazijn_products`
--

INSERT INTO `magazijn_products` (`id`, `sku`, `title`, `product_image`, `purchase_price_excl_vat`, `purchase_price_incl_vat`, `quantity_packaged`, `supplier_id`, `stock`, `created_by_user`, `created_on`) VALUES
(445, '20230001', 'pomp zilver ', '20231226_152338.jpg', NULL, 4.95, 1, 1, 0, 0, '2024-02-11'),
(446, '20230002', 'pomp goud', '20231226_152619.jpg', NULL, 4.95, 1, 1, 0, 0, '2024-02-11'),
(447, '20230003', 'pomp XL zilver ', '20231226_152753.jpg', NULL, 6.99, 1, 2, 0, 0, '2024-02-11'),
(448, '20230004', 'pomp XL goud', '20231226_172747.jpg', NULL, 6.99, 1, 2, 0, 0, '2024-02-11'),
(449, '20230005', 'buisje vaas', '20231226_152937.jpg', NULL, 1.65, 1, 1, 0, 0, '2024-02-11'),
(450, '20230006', 'buisje blauw vaas', '20231226_153030.jpg', NULL, 1.00, 1, 2, 0, 0, '2024-02-11'),
(451, '20230007', 'buisje vaas paars', '20231226_153045.jpg', NULL, 1.00, 1, 2, 0, 0, '2024-02-11'),
(452, '20230008', 'radarwerk klok groot', '20240108_131911.jpg', NULL, 6.95, 1, 1, 0, 0, '2024-02-11'),
(453, '20230009', 'radarwerk klok klein', '20240108_131924.jpg', NULL, 4.95, 1, 1, 0, 0, '2024-02-11'),
(454, '20230010', 'geurstokjes', '20231226_153347.jpg', NULL, 3.50, 1, 2, 0, 0, '2024-02-11'),
(455, '20230011', 'handvaten goud set van 2', '20231226_153509.jpg', NULL, 4.75, 1, 1, 0, 0, '2024-02-11'),
(456, '20230012', 'Led verlichting', 'Afbeelding5.jpg', NULL, 6.57, 1, 1, 0, 0, '2024-02-11'),
(457, '20230013', 'kurk', '20231226_153701.jpg', NULL, 0.30, 1, 1, 0, 0, '2024-02-11'),
(458, '20230014', 'spiegel', '20231226_153750.jpg', NULL, 1.20, 1, 12, 0, 0, '2024-02-11'),
(459, '20230015', 'etagiere goud', '20231226_153842.jpg', NULL, 4.75, 1, 1, 0, 0, '2024-02-11'),
(460, '20230016', 'etagiere zilver', '20231226_153924.jpg', NULL, 4.75, 1, 1, 0, 0, '2024-02-11'),
(461, '20230017', 'flesopener', '20231226_154006.jpg', NULL, 0.79, 1, 1, 0, 0, '2024-02-11'),
(462, '20230018', 'haarspeld goud', '20231226_154100.jpg', NULL, 1.65, 1, 1, 0, 0, '2024-02-11'),
(463, '20230019', 'wax koord zwart', '20240104_175750.jpg', NULL, 0.42, 1, 1, 0, 0, '2024-02-11'),
(464, '20230020', 'wax koord bordeaux', '20240104_175937.jpg', NULL, 0.42, 1, 1, 0, 0, '2024-02-11'),
(465, '20230021', 'wax koord roze', '20240104_180429.jpg', NULL, 0.42, 1, 1, 0, 0, '2024-02-11'),
(466, '20230022', 'wax koord donker blauw', '20240104_180450.jpg', NULL, 0.42, 1, 1, 0, 0, '2024-02-11'),
(467, '20230023', 'wax koord licht blauw', '20240104_180514.jpg', NULL, 0.42, 1, 1, 0, 0, '2024-02-11'),
(468, '20230024', 'wax koord rood', '20240104_180719.jpg', NULL, 0.42, 1, 1, 0, 0, '2024-02-11'),
(469, '20230025', 'wax koord grijs', '20240104_180748.jpg', NULL, 0.42, 1, 1, 0, 0, '2024-02-11'),
(470, '20230026', 'wax koord wit', '20240104_180819.jpg', NULL, 0.42, 1, 1, 0, 0, '2024-02-11'),
(471, '20230027', 'goud ketting + slotje', '20240105_163724.jpg', NULL, 1.95, 1, 1, 0, 0, '2024-02-11'),
(472, '20230028', 'zilver ketting + slotje', '20240105_163603.jpg', NULL, 1.61, 1, 1, 0, 0, '2024-02-11'),
(473, '20230029', 'wax koord', '20240105_163523.jpg', NULL, 0.42, 1, 1, 0, 0, '2024-02-11'),
(474, '20230030', 'oorhaken (zilver of goud)', 'oorhaken.jpg', NULL, 0.28, 1, 1, 0, 0, '2024-02-11'),
(475, '20230031', 'pompje parfum goud', '20240108_131811.jpg', NULL, 0.99, 1, 3, 0, 0, '2024-02-11'),
(476, '20230032', 'pompje parfum zilver', '20240108_131822.jpg', NULL, 0.99, 1, 3, 0, 0, '2024-02-11'),
(477, '20230033', 'snoep hartjes', '', NULL, 0.82, 1, 4, 0, 0, '2024-02-11'),
(478, '20230034', 'bollen chocolade', '', NULL, 0.80, 1, 4, 0, 0, '2024-02-11'),
(479, '20230035', 'losse chocolade ', '', NULL, 1.64, 1, 4, 0, 0, '2024-02-11'),
(480, '20230036', 'gouden uurwerk', '20240115_150030.jpg', NULL, 2.50, 1, 5, 0, 0, '2024-02-11'),
(481, '20230037', 'zilveren uurwerk', '20240115_150046.jpg', NULL, 2.50, 1, 5, 0, 0, '2024-02-11'),
(482, '20230038', 'Lege Geschenk Manden ', '', NULL, 1.65, 1, 6, 0, 0, '2024-02-11'),
(483, '20230039', 'Dozen  wit 395x290x275mm', '', NULL, 0.81, 1, 7, 0, 0, '2024-02-11'),
(484, '20230040', 'Dozen  wit 250x200x150mm', '', NULL, 0.48, 1, 7, 0, 0, '2024-02-11'),
(485, '20230041', 'Bubbel envelope B wit', '', NULL, 0.07, 1, 7, 0, 0, '2024-02-11'),
(486, '20230042', 'Rode Wijn', '', NULL, 4.95, 1, 15, 0, 0, '2024-02-11'),
(487, '20230043', 'Witte Wijn', '', NULL, 4.95, 1, 15, 0, 0, '2024-02-11'),
(488, '20230044', 'Wijn glazen', '', NULL, 2.06, 3, 4, 0, 0, '2024-02-11'),
(489, '20230045', 'Rollen verpakking plakband', '20240119_114423.jpg', NULL, 0.00, 0, 11, 0, 0, '2024-02-11'),
(490, '20230047', 'Handoekken', '', NULL, 1.02, 1, 9, 0, 0, '2024-02-11'),
(491, '20230048', 'Washand grijs', '', NULL, 0.45, 1, 9, 0, 0, '2024-02-11'),
(492, '20230049', 'Washand licht grijs', '', NULL, 0.45, 1, 9, 0, 0, '2024-02-11'),
(493, '20230050', 'Washand wit', '', NULL, 0.45, 1, 9, 0, 0, '2024-02-11'),
(494, '20230051', 'Zeep', '', NULL, 0.85, 1, 9, 0, 0, '2024-02-11'),
(495, '20230052', 'Kartonnetjes voor juwelen', '20240119_113636.jpg', NULL, 0.00, 0, 12, 0, 0, '2024-02-11'),
(496, '20230053', 'Zakjes voor juwelen', '20240119_113642.jpg', NULL, 0.00, 0, 12, 0, 0, '2024-02-11'),
(497, '20230054', 'Bijzettafeltjes', '', NULL, 8.26, 1, 8, 0, 0, '2024-02-11'),
(498, '20230055', 'visite kaartjes', '20240131_145343.jpg', NULL, 32.73, 100, 10, 0, 0, '2024-02-11'),
(499, '20230056', 'Merry christmas kaartjes', '20240131_145414.jpg', NULL, 0.00, 0, 14, 0, 0, '2024-02-11'),
(500, '20230057', 'Kleine ronde logo stickers', '', NULL, 66.33, 150, 10, 0, 0, '2024-02-11'),
(501, '20230058', 'samen tegen verspilling stickers', '', NULL, 11.11, 24, 10, 0, 0, '2024-02-11'),
(502, '20230059', 'bedankt kaarten', '20240204_104403.jpg', NULL, 39.13, 100, 10, 0, 0, '2024-02-11'),
(503, '20230060', 'papieren zakken (groenten & fruit)', '', NULL, 9.92, 50, 13, 0, 0, '2024-02-11'),
(504, '20230061', 'papieren tassen', '', NULL, 11.98, 50, 13, 0, 0, '2024-02-11'),
(505, '20230062', 'epoxyhars standaard flessen', '', NULL, 16.53, 1000, 2, 0, 0, '2024-02-11'),
(506, '20230063', 'Grote ronde logo stickers', '', NULL, 57.94, 120, 10, 0, 0, '2024-02-11'),
(507, '20230064', 'epoxyhars voeding flessen', '', NULL, 33.05, 1600, 2, 0, 0, '2024-02-11'),
(508, '20230065', 'epoxyhars sieraden flessen', '', NULL, 82.64, 600, 1, 0, 0, '2024-02-11'),
(509, '20230066', 'haarborstel', '20240217_111946.jpg', NULL, 1.45, 1, 3, 0, 0, '2024-02-17'),
(510, '20230067', 'Parafine 1 kg (Diane)', 'no_picture.jpg', NULL, 5.74, 1, 16, 0, 0, '2024-03-16'),
(511, '20230068', 'Stearine 500gr (Diane)', 'no_picture.jpg', NULL, 7.02, 0, 16, 0, 0, '2024-03-16'),
(512, '20230069', 'Kleur pil kerstrood(diane)', 'no_picture.jpg', NULL, 0.79, 1, 16, 0, 0, '2024-03-16'),
(513, '20230070', 'Kleur pil Licht groen(diane)', 'no_picture.jpg', NULL, 0.79, 1, 16, 0, 0, '2024-03-16'),
(514, '20230071', 'Kleur pil Zonne geel(diane)', 'no_picture.jpg', NULL, 0.79, 1, 16, 0, 0, '2024-03-16'),
(515, '20230072', 'Kleur pil Donker blauw(diane)', 'no_picture.jpg', NULL, 0.79, 1, 16, 0, 0, '2024-03-16'),
(516, '20230073', 'Kleur pil Wit(diane)', 'no_picture.jpg', NULL, 0.79, 1, 16, 0, 0, '2024-03-16'),
(517, '20230074', 'Parafine 1 kg ', 'no_picture.jpg', NULL, 5.74, 1, 16, 0, 0, '2024-03-16'),
(518, '20230075', 'Stearine 500gr ', 'no_picture.jpg', NULL, 3.51, 0, 16, 0, 0, '2024-03-16'),
(519, '20230076', 'Kleur pil zonnegeel ', 'no_picture.jpg', NULL, 0.79, 1, 16, 0, 0, '2024-03-16'),
(520, '20230077', 'Kleur pil Oranje ', 'no_picture.jpg', NULL, 0.79, 1, 16, 0, 0, '2024-03-16'),
(521, '20230078', 'Kleur pil Midnight Blue ', 'no_picture.jpg', NULL, 0.79, 1, 16, 0, 0, '2024-03-16'),
(522, '20230079', 'Kleur pil Honey', 'no_picture.jpg', NULL, 0.79, 1, 16, 0, 0, '2024-03-16'),
(523, '20230080', 'Geurolie Good Habits Sakura 20ml (diane)', 'no_picture.jpg', NULL, 3.26, 1, 16, 0, 0, '2024-03-16'),
(524, '20230081', 'Geurolie Cashmere Woods 20ml', 'no_picture.jpg', NULL, 3.72, 1, 16, 0, 0, '2024-03-16'),
(525, '20230082', 'The Candlesshop Colletion Geurolie Pumpkin Halloween 20ml', 'no_picture.jpg', NULL, 3.10, 1, 16, 0, 0, '2024-03-16'),
(526, '20230083', 'Geurolie Amber 20ml', 'no_picture.jpg', NULL, 4.09, 1, 16, 0, 0, '2024-03-16'),
(527, '20230084', 'Geurolie Monkey Farts 20ml', 'no_picture.jpg', NULL, 3.51, 1, 16, 0, 0, '2024-03-16'),
(528, '20230085', 'Waxinepitje met pitvoet (100mm) 25st', 'no_picture.jpg', NULL, 3.43, 1, 16, 0, 0, '2024-03-16'),
(529, '20230086', 'kaars potje', 'IMG_1299 2024-03-19 20_09_16.JPG', NULL, 8.08, 12, 5, 0, 0, '2024-03-19'),
(530, '20230087', 'parfum Angel', '20240322_110610.jpg', NULL, 12.40, 1, 17, 0, 0, '2024-03-23');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `product_changes`
--

CREATE TABLE `product_changes` (
  `id` int NOT NULL,
  `product_id` int NOT NULL,
  `category` varchar(255) NOT NULL,
  `field_name` varchar(255) NOT NULL,
  `old_value` text,
  `new_value` text,
  `changed_by_user` int DEFAULT NULL,
  `change_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `stock_count`
--

CREATE TABLE `stock_count` (
  `id` int NOT NULL,
  `product_sku` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL,
  `counted_stock` int NOT NULL,
  `counted_by_user` int NOT NULL,
  `count_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `stock_history`
--

CREATE TABLE `stock_history` (
  `id` int NOT NULL,
  `product_sku` varchar(255) DEFAULT NULL,
  `category` varchar(255) DEFAULT NULL,
  `stock_change` int DEFAULT NULL,
  `reason` varchar(255) DEFAULT NULL,
  `changed_by_user` int DEFAULT NULL,
  `change_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `stock_history`
--

INSERT INTO `stock_history` (`id`, `product_sku`, `category`, `stock_change`, `reason`, `changed_by_user`, `change_date`) VALUES
(1, '20068', 'magazijn', 1, 'Nieuw', 5, '2024-09-05 11:13:12'),
(2, '20171', 'magazijn', 1, 'Nieuw', 5, '2024-09-06 08:23:12'),
(3, '20172', 'magazijn', 1, 'Nieuw', 5, '2024-09-12 10:36:27'),
(4, '20175', 'magazijn', 1, 'Nieuw', 5, '2024-09-12 16:31:10');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `suppliers`
--

CREATE TABLE `suppliers` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `contact_info` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `suppliers`
--

INSERT INTO `suppliers` (`id`, `name`, `contact_info`) VALUES
(1, 'itsOkay / Trust Commerce B.V.', 'Street: Kerkeplaat 12, Postcode: 3313 LC, City: Dordrecht, Country: Nederland, Phone: , Mail: , Tax Number: NL861644992B01, URL: https://itsokay.nl'),
(2, 'PolyesterShoppen BV', 'Street: Oostbaan 680, Postcode: 2841 ML, City: Moordrecht, Country: Nederland, Phone: , Mail: support@polyestershoppen.nl, Tax Number: BE0681.545.556, URL: https://polyestershoppen.nl'),
(3, 'epoxymallen', 'Street: , Postcode: , City: , Country: , Phone: , Mail: , Tax Number: , URL: https://epoxymallen.be'),
(4, 'Action', 'Street: , Postcode: , City: , Country: , Phone: , Mail: , Tax Number: , URL: https://Action.com'),
(5, 'Temu', 'Street: , Postcode: , City: , Country: , Phone: , Mail: , Tax Number: , URL: https://Temu.com'),
(6, 'De verpakkingswinkel', 'Street: Via Raeka 23, Postcode: 6003 NK, City: Weert, Country: Nederland, Phone: +31475317741, Mail: info@deverpakkingswinkel.com, Tax Number: NL007338004B09, URL: https://deverpakkingswinkel.com'),
(7, 'rotim verpakkingen bv', 'Street: kerkenbos 1028, Postcode: 6546 BA, City: Nijmegen, Country: Nederland, Phone: +31243221000, Mail: info@rotim.com, Tax Number: NL813758087B01, URL: https://rotim.com'),
(8, 'Jysk', 'Street: Sportlaan 34, Postcode: 3960, City: Bree, Country: België, Phone: 0471868084, Mail: , Tax Number: BE0666889252, URL: https://jysk.be'),
(9, 'Gompie partijhandel', 'Street: Lijmziederwei 34, Postcode: 5551 SB, City: Valkenswaard, Country: Nederland, Phone: +32655771807, Mail: info@partijstunter.eu, Tax Number: Nl001491168B93, URL: https://partijstunter.eu'),
(10, 'Vistaprint', 'Street: Hudsonweg 8, Postcode: 5928 LW, City: Venlo, Country: Nederland, Phone: , Mail: , Tax Number: Nl812139513B01, URL: https://vistaprint.nl'),
(11, 'Myparcel', 'Street: , Postcode: , City: , Country: , Phone: , Mail: , Tax Number: , URL: '),
(12, 'Joom', 'Street: , Postcode: , City: , Country: , Phone: , Mail: , Tax Number: , URL: https://Joom.com'),
(13, 'Ava', 'Street: , Postcode: , City: , Country: , Phone: , Mail: , Tax Number: , URL: https://Ava.be'),
(14, 'Timco Voordeelmarkt', 'Street: , Postcode: , City: , Country: , Phone: , Mail: , Tax Number: , URL: https://timcovoordeelmarkt.nl'),
(15, 'Wijnvoordeel', 'Street: , Postcode: , City: , Country: , Phone: , Mail: , Tax Number: , URL: https://wijnvoordeel.nl'),
(16, 'Kaarswinkel', 'Street: Pinkewad 9, Postcode: 1132NC, City: Volendam, Country: Nederland, Phone: +31622030391, Mail: info@kaarwinkel.nl, Tax Number: NL002133259B49, URL: https://kaarwinkel.nl'),
(17, 'Femma Geurmelts & More', 'Street: Cato Elderinklaan 92, Postcode: 7576, City: EC Oldenzaal, Country: Nederland, Phone:  06 28167574, Mail: info@femmageurmeltsmore.nl, Tax Number: 0, URL: https://femmageurmeltsmore.nl/');

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `magazijn_products`
--
ALTER TABLE `magazijn_products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `supplier_id` (`supplier_id`);

--
-- Indexen voor tabel `product_changes`
--
ALTER TABLE `product_changes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `changed_by_user` (`changed_by_user`);

--
-- Indexen voor tabel `stock_count`
--
ALTER TABLE `stock_count`
  ADD PRIMARY KEY (`id`),
  ADD KEY `counted_by_user` (`counted_by_user`);

--
-- Indexen voor tabel `stock_history`
--
ALTER TABLE `stock_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `changed_by_user` (`changed_by_user`);

--
-- Indexen voor tabel `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT voor geëxporteerde tabellen
--

--
-- AUTO_INCREMENT voor een tabel `magazijn_products`
--
ALTER TABLE `magazijn_products`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=531;

--
-- AUTO_INCREMENT voor een tabel `product_changes`
--
ALTER TABLE `product_changes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `stock_count`
--
ALTER TABLE `stock_count`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `stock_history`
--
ALTER TABLE `stock_history`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT voor een tabel `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Beperkingen voor geëxporteerde tabellen
--

--
-- Beperkingen voor tabel `magazijn_products`
--
ALTER TABLE `magazijn_products`
  ADD CONSTRAINT `magazijn_products_ibfk_1` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`);

--
-- Beperkingen voor tabel `stock_history`
--
ALTER TABLE `stock_history`
  ADD CONSTRAINT `stock_history_ibfk_1` FOREIGN KEY (`changed_by_user`) REFERENCES `company_admin`.`admins_workforce` (`admin_id`);
--
-- Database: `phpmyadmin`
--
CREATE DATABASE IF NOT EXISTS `phpmyadmin` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `phpmyadmin`;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `pma__bookmark`
--

CREATE TABLE `pma__bookmark` (
  `id` int UNSIGNED NOT NULL,
  `dbase` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `user` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `label` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `query` text COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8_bin COMMENT='Bookmarks';

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `pma__central_columns`
--

CREATE TABLE `pma__central_columns` (
  `db_name` varchar(64) COLLATE utf8_bin NOT NULL,
  `col_name` varchar(64) COLLATE utf8_bin NOT NULL,
  `col_type` varchar(64) COLLATE utf8_bin NOT NULL,
  `col_length` text COLLATE utf8_bin,
  `col_collation` varchar(64) COLLATE utf8_bin NOT NULL,
  `col_isNull` tinyint(1) NOT NULL,
  `col_extra` varchar(255) COLLATE utf8_bin DEFAULT '',
  `col_default` text COLLATE utf8_bin
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8_bin COMMENT='Central list of columns';

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `pma__column_info`
--

CREATE TABLE `pma__column_info` (
  `id` int UNSIGNED NOT NULL,
  `db_name` varchar(64) COLLATE utf8_bin NOT NULL DEFAULT '',
  `table_name` varchar(64) COLLATE utf8_bin NOT NULL DEFAULT '',
  `column_name` varchar(64) COLLATE utf8_bin NOT NULL DEFAULT '',
  `comment` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `mimetype` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `transformation` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `transformation_options` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `input_transformation` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `input_transformation_options` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8_bin COMMENT='Column information for phpMyAdmin';

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `pma__designer_settings`
--

CREATE TABLE `pma__designer_settings` (
  `username` varchar(64) COLLATE utf8_bin NOT NULL,
  `settings_data` text COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8_bin COMMENT='Settings related to Designer';

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `pma__export_templates`
--

CREATE TABLE `pma__export_templates` (
  `id` int UNSIGNED NOT NULL,
  `username` varchar(64) COLLATE utf8_bin NOT NULL,
  `export_type` varchar(10) COLLATE utf8_bin NOT NULL,
  `template_name` varchar(64) COLLATE utf8_bin NOT NULL,
  `template_data` text COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8_bin COMMENT='Saved export templates';

--
-- Gegevens worden geëxporteerd voor tabel `pma__export_templates`
--

INSERT INTO `pma__export_templates` (`id`, `username`, `export_type`, `template_name`, `template_data`) VALUES
(1, 'root', 'server', 'database202108', '{\"quick_or_custom\":\"custom\",\"what\":\"sql\",\"db_select[]\":[\"admin\",\"adminpanel\",\"company_admin\",\"infoscherm\",\"kassabeheer\",\"logo\",\"melding\",\"multi_login\",\"phplogin\",\"phpmyadmin\",\"priveapp\",\"tweedehandsreclamescherm\",\"walnoten\",\"walnotenreclamescherm\",\"walnotenshop\",\"walnotenverkoop\",\"webshop\"],\"aliases_new\":\"\",\"output_format\":\"sendit\",\"filename_template\":\"@SERVER@\",\"remember_template\":\"on\",\"charset\":\"utf-8\",\"compression\":\"none\",\"maxsize\":\"\",\"codegen_structure_or_data\":\"data\",\"codegen_format\":\"0\",\"csv_separator\":\",\",\"csv_enclosed\":\"\\\"\",\"csv_escaped\":\"\\\"\",\"csv_terminated\":\"AUTO\",\"csv_null\":\"NULL\",\"csv_structure_or_data\":\"data\",\"excel_null\":\"NULL\",\"excel_columns\":\"something\",\"excel_edition\":\"win\",\"excel_structure_or_data\":\"data\",\"json_structure_or_data\":\"data\",\"json_unicode\":\"something\",\"latex_caption\":\"something\",\"latex_structure_or_data\":\"structure_and_data\",\"latex_structure_caption\":\"Structuur van de tabel @TABLE@\",\"latex_structure_continued_caption\":\"Structuur van de tabel @TABLE@ (vervolgd)\",\"latex_structure_label\":\"tab:@TABLE@-structure\",\"latex_relation\":\"something\",\"latex_comments\":\"something\",\"latex_mime\":\"something\",\"latex_columns\":\"something\",\"latex_data_caption\":\"Inhoud van tabel @TABLE@\",\"latex_data_continued_caption\":\"Inhoud van tabel @TABLE@ (vervolgd)\",\"latex_data_label\":\"tab:@TABLE@-data\",\"latex_null\":\"\\\\textit{NULL}\",\"mediawiki_structure_or_data\":\"data\",\"mediawiki_caption\":\"something\",\"mediawiki_headers\":\"something\",\"htmlword_structure_or_data\":\"structure_and_data\",\"htmlword_null\":\"NULL\",\"ods_null\":\"NULL\",\"ods_structure_or_data\":\"data\",\"odt_structure_or_data\":\"structure_and_data\",\"odt_relation\":\"something\",\"odt_comments\":\"something\",\"odt_mime\":\"something\",\"odt_columns\":\"something\",\"odt_null\":\"NULL\",\"pdf_report_title\":\"\",\"pdf_structure_or_data\":\"data\",\"phparray_structure_or_data\":\"data\",\"sql_include_comments\":\"something\",\"sql_header_comment\":\"\",\"sql_use_transaction\":\"something\",\"sql_compatibility\":\"NONE\",\"sql_structure_or_data\":\"structure_and_data\",\"sql_create_table\":\"something\",\"sql_auto_increment\":\"something\",\"sql_create_view\":\"something\",\"sql_create_trigger\":\"something\",\"sql_backquotes\":\"something\",\"sql_type\":\"INSERT\",\"sql_insert_syntax\":\"both\",\"sql_max_query_size\":\"999999\",\"sql_hex_for_binary\":\"something\",\"sql_utc_time\":\"something\",\"texytext_structure_or_data\":\"structure_and_data\",\"texytext_null\":\"NULL\",\"yaml_structure_or_data\":\"data\",\"\":null,\"as_separate_files\":null,\"csv_removeCRLF\":null,\"csv_columns\":null,\"excel_removeCRLF\":null,\"json_pretty_print\":null,\"htmlword_columns\":null,\"ods_columns\":null,\"sql_dates\":null,\"sql_relation\":null,\"sql_mime\":null,\"sql_disable_fk\":null,\"sql_views_as_tables\":null,\"sql_metadata\":null,\"sql_drop_database\":null,\"sql_drop_table\":null,\"sql_if_not_exists\":null,\"sql_simple_view_export\":null,\"sql_view_current_user\":null,\"sql_or_replace_view\":null,\"sql_procedure_function\":null,\"sql_truncate\":null,\"sql_delayed\":null,\"sql_ignore\":null,\"texytext_columns\":null}'),
(2, 'root', 'server', 'webcrafters', '{\"quick_or_custom\":\"quick\",\"what\":\"sql\",\"db_select[]\":[\"boekhouding\",\"company_admin\",\"kantoor\",\"kassa\",\"magazijn\",\"phpmyadmin\",\"priveapp\",\"voedselproblemen\",\"winkel\"],\"aliases_new\":\"\",\"output_format\":\"sendit\",\"filename_template\":\"@SERVER@\",\"remember_template\":\"on\",\"charset\":\"utf-8\",\"compression\":\"none\",\"maxsize\":\"\",\"codegen_structure_or_data\":\"data\",\"codegen_format\":\"0\",\"csv_separator\":\",\",\"csv_enclosed\":\"\\\"\",\"csv_escaped\":\"\\\"\",\"csv_terminated\":\"AUTO\",\"csv_null\":\"NULL\",\"csv_columns\":\"something\",\"csv_structure_or_data\":\"data\",\"excel_null\":\"NULL\",\"excel_columns\":\"something\",\"excel_edition\":\"win\",\"excel_structure_or_data\":\"data\",\"json_structure_or_data\":\"data\",\"json_unicode\":\"something\",\"latex_caption\":\"something\",\"latex_structure_or_data\":\"structure_and_data\",\"latex_structure_caption\":\"Structuur van de tabel @TABLE@\",\"latex_structure_continued_caption\":\"Structuur van de tabel @TABLE@ (vervolgd)\",\"latex_structure_label\":\"tab:@TABLE@-structure\",\"latex_relation\":\"something\",\"latex_comments\":\"something\",\"latex_mime\":\"something\",\"latex_columns\":\"something\",\"latex_data_caption\":\"Inhoud van tabel @TABLE@\",\"latex_data_continued_caption\":\"Inhoud van tabel @TABLE@ (vervolgd)\",\"latex_data_label\":\"tab:@TABLE@-data\",\"latex_null\":\"\\\\textit{NULL}\",\"mediawiki_structure_or_data\":\"data\",\"mediawiki_caption\":\"something\",\"mediawiki_headers\":\"something\",\"htmlword_structure_or_data\":\"structure_and_data\",\"htmlword_null\":\"NULL\",\"ods_null\":\"NULL\",\"ods_structure_or_data\":\"data\",\"odt_structure_or_data\":\"structure_and_data\",\"odt_relation\":\"something\",\"odt_comments\":\"something\",\"odt_mime\":\"something\",\"odt_columns\":\"something\",\"odt_null\":\"NULL\",\"pdf_report_title\":\"\",\"pdf_structure_or_data\":\"data\",\"phparray_structure_or_data\":\"data\",\"sql_include_comments\":\"something\",\"sql_header_comment\":\"\",\"sql_use_transaction\":\"something\",\"sql_compatibility\":\"NONE\",\"sql_structure_or_data\":\"structure_and_data\",\"sql_create_table\":\"something\",\"sql_auto_increment\":\"something\",\"sql_create_view\":\"something\",\"sql_create_trigger\":\"something\",\"sql_backquotes\":\"something\",\"sql_type\":\"INSERT\",\"sql_insert_syntax\":\"both\",\"sql_max_query_size\":\"50000\",\"sql_hex_for_binary\":\"something\",\"sql_utc_time\":\"something\",\"texytext_structure_or_data\":\"structure_and_data\",\"texytext_null\":\"NULL\",\"yaml_structure_or_data\":\"data\",\"\":null,\"as_separate_files\":null,\"csv_removeCRLF\":null,\"excel_removeCRLF\":null,\"json_pretty_print\":null,\"htmlword_columns\":null,\"ods_columns\":null,\"sql_dates\":null,\"sql_relation\":null,\"sql_mime\":null,\"sql_disable_fk\":null,\"sql_views_as_tables\":null,\"sql_metadata\":null,\"sql_drop_database\":null,\"sql_drop_table\":null,\"sql_if_not_exists\":null,\"sql_simple_view_export\":null,\"sql_view_current_user\":null,\"sql_or_replace_view\":null,\"sql_procedure_function\":null,\"sql_truncate\":null,\"sql_delayed\":null,\"sql_ignore\":null,\"texytext_columns\":null}');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `pma__favorite`
--

CREATE TABLE `pma__favorite` (
  `username` varchar(64) COLLATE utf8_bin NOT NULL,
  `tables` text COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8_bin COMMENT='Favorite tables';

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `pma__history`
--

CREATE TABLE `pma__history` (
  `id` bigint UNSIGNED NOT NULL,
  `username` varchar(64) COLLATE utf8_bin NOT NULL DEFAULT '',
  `db` varchar(64) COLLATE utf8_bin NOT NULL DEFAULT '',
  `table` varchar(64) COLLATE utf8_bin NOT NULL DEFAULT '',
  `timevalue` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `sqlquery` text COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8_bin COMMENT='SQL history for phpMyAdmin';

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `pma__navigationhiding`
--

CREATE TABLE `pma__navigationhiding` (
  `username` varchar(64) COLLATE utf8_bin NOT NULL,
  `item_name` varchar(64) COLLATE utf8_bin NOT NULL,
  `item_type` varchar(64) COLLATE utf8_bin NOT NULL,
  `db_name` varchar(64) COLLATE utf8_bin NOT NULL,
  `table_name` varchar(64) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8_bin COMMENT='Hidden items of navigation tree';

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `pma__pdf_pages`
--

CREATE TABLE `pma__pdf_pages` (
  `db_name` varchar(64) COLLATE utf8_bin NOT NULL DEFAULT '',
  `page_nr` int UNSIGNED NOT NULL,
  `page_descr` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8_bin COMMENT='PDF relation pages for phpMyAdmin';

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `pma__recent`
--

CREATE TABLE `pma__recent` (
  `username` varchar(64) COLLATE utf8_bin NOT NULL,
  `tables` text COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8_bin COMMENT='Recently accessed tables';

--
-- Gegevens worden geëxporteerd voor tabel `pma__recent`
--

INSERT INTO `pma__recent` (`username`, `tables`) VALUES
('root', '[{\"db\":\"winkel\",\"table\":\"stock_count\"},{\"db\":\"company_admin\",\"table\":\"admins_workforce\"},{\"db\":\"winkel\",\"table\":\"epoxy_products\"},{\"db\":\"company_admin\",\"table\":\"break_logs\"},{\"db\":\"company_admin\",\"table\":\"time_logs\"},{\"db\":\"company_admin\",\"table\":\"schedules\"},{\"db\":\"company_admin\",\"table\":\"employment\"},{\"db\":\"company_admin\",\"table\":\"payslips\"},{\"db\":\"company_admin\",\"table\":\"contracts\"},{\"db\":\"priveapp\",\"table\":\"slider\"}]');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `pma__relation`
--

CREATE TABLE `pma__relation` (
  `master_db` varchar(64) COLLATE utf8_bin NOT NULL DEFAULT '',
  `master_table` varchar(64) COLLATE utf8_bin NOT NULL DEFAULT '',
  `master_field` varchar(64) COLLATE utf8_bin NOT NULL DEFAULT '',
  `foreign_db` varchar(64) COLLATE utf8_bin NOT NULL DEFAULT '',
  `foreign_table` varchar(64) COLLATE utf8_bin NOT NULL DEFAULT '',
  `foreign_field` varchar(64) COLLATE utf8_bin NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8_bin COMMENT='Relation table';

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `pma__savedsearches`
--

CREATE TABLE `pma__savedsearches` (
  `id` int UNSIGNED NOT NULL,
  `username` varchar(64) COLLATE utf8_bin NOT NULL DEFAULT '',
  `db_name` varchar(64) COLLATE utf8_bin NOT NULL DEFAULT '',
  `search_name` varchar(64) COLLATE utf8_bin NOT NULL DEFAULT '',
  `search_data` text COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8_bin COMMENT='Saved searches';

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `pma__table_coords`
--

CREATE TABLE `pma__table_coords` (
  `db_name` varchar(64) COLLATE utf8_bin NOT NULL DEFAULT '',
  `table_name` varchar(64) COLLATE utf8_bin NOT NULL DEFAULT '',
  `pdf_page_number` int NOT NULL DEFAULT '0',
  `x` float UNSIGNED NOT NULL DEFAULT '0',
  `y` float UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8_bin COMMENT='Table coordinates for phpMyAdmin PDF output';

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `pma__table_info`
--

CREATE TABLE `pma__table_info` (
  `db_name` varchar(64) COLLATE utf8_bin NOT NULL DEFAULT '',
  `table_name` varchar(64) COLLATE utf8_bin NOT NULL DEFAULT '',
  `display_field` varchar(64) COLLATE utf8_bin NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8_bin COMMENT='Table information for phpMyAdmin';

--
-- Gegevens worden geëxporteerd voor tabel `pma__table_info`
--

INSERT INTO `pma__table_info` (`db_name`, `table_name`, `display_field`) VALUES
('company_admin', 'admins_workforce', 'admin_name');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `pma__table_uiprefs`
--

CREATE TABLE `pma__table_uiprefs` (
  `username` varchar(64) COLLATE utf8_bin NOT NULL,
  `db_name` varchar(64) COLLATE utf8_bin NOT NULL,
  `table_name` varchar(64) COLLATE utf8_bin NOT NULL,
  `prefs` text COLLATE utf8_bin NOT NULL,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8_bin COMMENT='Tables'' UI preferences';

--
-- Gegevens worden geëxporteerd voor tabel `pma__table_uiprefs`
--

INSERT INTO `pma__table_uiprefs` (`username`, `db_name`, `table_name`, `prefs`, `last_update`) VALUES
('root', 'company_admin', 'admins_workforce', '[]', '2024-10-01 08:16:10'),
('root', 'company_admin', 'buttons', '{\"sorted_col\":\"`id` ASC\"}', '2024-11-08 10:33:17'),
('root', 'priveapp', 'evenement', '{\"sorted_col\":\"`id` DESC\"}', '2024-06-20 11:02:20'),
('root', 'priveapp', 'paying_off', '{\"sorted_col\":\"`id` ASC\"}', '2024-07-03 16:27:04'),
('root', 'priveapp', 'updates', '{\"sorted_col\":\"`id`  DESC\"}', '2024-11-17 18:53:20'),
('root', 'webshop', 'pending_orders', '{\"sorted_col\":\"`pending_orders`.`companynumber`  DESC\"}', '2021-11-08 18:51:51'),
('root', 'webshop', 'products', '{\"sorted_col\":\"`product_keywords` ASC\"}', '2023-06-30 12:41:11'),
('root', 'winkel', 'stock_count', '{\"sorted_col\":\"`count_date` DESC\"}', '2024-09-01 15:06:46');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `pma__tracking`
--

CREATE TABLE `pma__tracking` (
  `db_name` varchar(64) COLLATE utf8_bin NOT NULL,
  `table_name` varchar(64) COLLATE utf8_bin NOT NULL,
  `version` int UNSIGNED NOT NULL,
  `date_created` datetime NOT NULL,
  `date_updated` datetime NOT NULL,
  `schema_snapshot` text COLLATE utf8_bin NOT NULL,
  `schema_sql` text COLLATE utf8_bin,
  `data_sql` longtext COLLATE utf8_bin,
  `tracking` set('UPDATE','REPLACE','INSERT','DELETE','TRUNCATE','CREATE DATABASE','ALTER DATABASE','DROP DATABASE','CREATE TABLE','ALTER TABLE','RENAME TABLE','DROP TABLE','CREATE INDEX','DROP INDEX','CREATE VIEW','ALTER VIEW','DROP VIEW') COLLATE utf8_bin DEFAULT NULL,
  `tracking_active` int UNSIGNED NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8_bin COMMENT='Database changes tracking for phpMyAdmin';

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `pma__userconfig`
--

CREATE TABLE `pma__userconfig` (
  `username` varchar(64) COLLATE utf8_bin NOT NULL,
  `timevalue` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `config_data` text COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8_bin COMMENT='User preferences storage for phpMyAdmin';

--
-- Gegevens worden geëxporteerd voor tabel `pma__userconfig`
--

INSERT INTO `pma__userconfig` (`username`, `timevalue`, `config_data`) VALUES
('root', '2024-12-07 10:01:41', '{\"lang\":\"nl\",\"Console\\/Mode\":\"collapse\",\"ThemeDefault\":\"pmahomme\"}');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `pma__usergroups`
--

CREATE TABLE `pma__usergroups` (
  `usergroup` varchar(64) COLLATE utf8_bin NOT NULL,
  `tab` varchar(64) COLLATE utf8_bin NOT NULL,
  `allowed` enum('Y','N') COLLATE utf8_bin NOT NULL DEFAULT 'N'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8_bin COMMENT='User groups with configured menu items';

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `pma__users`
--

CREATE TABLE `pma__users` (
  `username` varchar(64) COLLATE utf8_bin NOT NULL,
  `usergroup` varchar(64) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8_bin COMMENT='Users and their assignments to user groups';

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `pma__bookmark`
--
ALTER TABLE `pma__bookmark`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `pma__central_columns`
--
ALTER TABLE `pma__central_columns`
  ADD PRIMARY KEY (`db_name`,`col_name`);

--
-- Indexen voor tabel `pma__column_info`
--
ALTER TABLE `pma__column_info`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `db_name` (`db_name`,`table_name`,`column_name`);

--
-- Indexen voor tabel `pma__designer_settings`
--
ALTER TABLE `pma__designer_settings`
  ADD PRIMARY KEY (`username`);

--
-- Indexen voor tabel `pma__export_templates`
--
ALTER TABLE `pma__export_templates`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `u_user_type_template` (`username`,`export_type`,`template_name`);

--
-- Indexen voor tabel `pma__favorite`
--
ALTER TABLE `pma__favorite`
  ADD PRIMARY KEY (`username`);

--
-- Indexen voor tabel `pma__history`
--
ALTER TABLE `pma__history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `username` (`username`,`db`,`table`,`timevalue`);

--
-- Indexen voor tabel `pma__navigationhiding`
--
ALTER TABLE `pma__navigationhiding`
  ADD PRIMARY KEY (`username`,`item_name`,`item_type`,`db_name`,`table_name`);

--
-- Indexen voor tabel `pma__pdf_pages`
--
ALTER TABLE `pma__pdf_pages`
  ADD PRIMARY KEY (`page_nr`),
  ADD KEY `db_name` (`db_name`);

--
-- Indexen voor tabel `pma__recent`
--
ALTER TABLE `pma__recent`
  ADD PRIMARY KEY (`username`);

--
-- Indexen voor tabel `pma__relation`
--
ALTER TABLE `pma__relation`
  ADD PRIMARY KEY (`master_db`,`master_table`,`master_field`),
  ADD KEY `foreign_field` (`foreign_db`,`foreign_table`);

--
-- Indexen voor tabel `pma__savedsearches`
--
ALTER TABLE `pma__savedsearches`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `u_savedsearches_username_dbname` (`username`,`db_name`,`search_name`);

--
-- Indexen voor tabel `pma__table_coords`
--
ALTER TABLE `pma__table_coords`
  ADD PRIMARY KEY (`db_name`,`table_name`,`pdf_page_number`);

--
-- Indexen voor tabel `pma__table_info`
--
ALTER TABLE `pma__table_info`
  ADD PRIMARY KEY (`db_name`,`table_name`);

--
-- Indexen voor tabel `pma__table_uiprefs`
--
ALTER TABLE `pma__table_uiprefs`
  ADD PRIMARY KEY (`username`,`db_name`,`table_name`);

--
-- Indexen voor tabel `pma__tracking`
--
ALTER TABLE `pma__tracking`
  ADD PRIMARY KEY (`db_name`,`table_name`,`version`);

--
-- Indexen voor tabel `pma__userconfig`
--
ALTER TABLE `pma__userconfig`
  ADD PRIMARY KEY (`username`);

--
-- Indexen voor tabel `pma__usergroups`
--
ALTER TABLE `pma__usergroups`
  ADD PRIMARY KEY (`usergroup`,`tab`,`allowed`);

--
-- Indexen voor tabel `pma__users`
--
ALTER TABLE `pma__users`
  ADD PRIMARY KEY (`username`,`usergroup`);

--
-- AUTO_INCREMENT voor geëxporteerde tabellen
--

--
-- AUTO_INCREMENT voor een tabel `pma__bookmark`
--
ALTER TABLE `pma__bookmark`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `pma__column_info`
--
ALTER TABLE `pma__column_info`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `pma__export_templates`
--
ALTER TABLE `pma__export_templates`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT voor een tabel `pma__history`
--
ALTER TABLE `pma__history`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `pma__pdf_pages`
--
ALTER TABLE `pma__pdf_pages`
  MODIFY `page_nr` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `pma__savedsearches`
--
ALTER TABLE `pma__savedsearches`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- Database: `priveapp`
--
CREATE DATABASE IF NOT EXISTS `priveapp` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `priveapp`;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `adminusers`
--

CREATE TABLE `adminusers` (
  `id` int NOT NULL,
  `image` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `username` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `email` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `password` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `type` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `navbar` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `darkmode` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `selfnoti` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `selfchat` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `login` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Last_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Gegevens worden geëxporteerd voor tabel `adminusers`
--

INSERT INTO `adminusers` (`id`, `image`, `username`, `email`, `password`, `type`, `navbar`, `darkmode`, `selfnoti`, `selfchat`, `login`, `date`, `Last_date`) VALUES
(1, 'avatar5.png', 'Andy', 'andywindels5@gmail.com', '$2y$10$eqHv68LwTErB5g80thZ57eMd3KoYDuGSvcmqxO.yimOJgOV1A9l4e', 'admin', 'true', 'false', 'true', 'false', '2024-11-10 16:21:10', '2021-06-11 12:50:10', '2024-11-10 16:21:10');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `albumimage`
--

CREATE TABLE `albumimage` (
  `id` int NOT NULL,
  `albumname` varchar(255) NOT NULL,
  `albumgroup` varchar(255) NOT NULL,
  `userid` varchar(255) NOT NULL,
  `uploadalbum` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Gegevens worden geëxporteerd voor tabel `albumimage`
--

INSERT INTO `albumimage` (`id`, `albumname`, `albumgroup`, `userid`, `uploadalbum`) VALUES
(1, 'trouw', '1', '1', '2022-12-05 16:15:01'),
(2, 'userimage', '1', '1', '2022-12-07 10:11:00'),
(3, 'slider', '1', '1', '2022-12-07 10:14:08'),
(4, 'family', '1', '1', '2022-12-07 10:16:24'),
(5, 'about', '1', '1', '2022-12-07 10:16:47');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `appsystem`
--

CREATE TABLE `appsystem` (
  `id` int NOT NULL,
  `systeemupdate` varchar(255) NOT NULL,
  `systeemvacation` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Gegevens worden geëxporteerd voor tabel `appsystem`
--

INSERT INTO `appsystem` (`id`, `systeemupdate`, `systeemvacation`) VALUES
(1, 'false', 'false');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `evenement`
--

CREATE TABLE `evenement` (
  `id` int NOT NULL,
  `userid` varchar(255) NOT NULL,
  `title` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `bjaar` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `bmaand` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `bdag` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `buur` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `bmin` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `ejaar` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `emaand` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `edag` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `euur` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `emin` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `fullday` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `locatie` varchar(255) NOT NULL,
  `text` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `groupsen` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `usersids` varchar(255) NOT NULL,
  `color` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `status` varchar(255) NOT NULL,
  `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

--
-- Gegevens worden geëxporteerd voor tabel `evenement`
--

INSERT INTO `evenement` (`id`, `userid`, `title`, `bjaar`, `bmaand`, `bdag`, `buur`, `bmin`, `ejaar`, `emaand`, `edag`, `euur`, `emin`, `fullday`, `locatie`, `text`, `groupsen`, `usersids`, `color`, `status`, `date`, `last_date`) VALUES
(732, '2', 'Henri Werk', '2022', '12', '08', '09', '00', '2022', '12', '08', '18', '00', 'false', 'Aalst-waarle, Nederland', '', '2', '', '#007bff', 'Toegevoegd', '2022-12-03 18:35:59', '2022-12-12 09:33:49'),
(733, '2', 'Henri Werk', '2022', '12', '10', '09', '30', '2022', '12', '10', '17', '00', 'false', 'Aalst-waarle, Nederland', '', '2', '', '#007bff', 'Toegevoegd', '2022-12-03 18:37:11', '2022-12-12 09:33:49'),
(734, '1', 'Andy Solidaris', '2022', '12', '08', '11', '20', '2022', '12', '08', '11', '50', 'false', 'Stadswaag 3 3930 Hamont', 'Afspraak voor sociaal tegemoetkoming te krijgen via de mogelijkheid gehandicapt.', '6', '', '#f56954', 'Toegevoegd', '2022-12-06 17:36:33', '2022-12-12 13:22:59'),
(735, '3', 'Martha Activiteit', '2022', '12', '09', '11', '00', '2022', '12', '09', '15', '00', 'false', 'Valkenswaard', 'OKRA brunch voor wandelaars', '2', '', '#17a2b8', 'Toegevoegd', '2022-12-06 22:04:17', '2022-12-12 09:33:49'),
(736, '2', 'Henri Werk', '2022', '12', '09', '09', '00', '2022', '12', '09', '20', '00', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2022-12-08 20:45:44', '2022-12-12 09:33:49'),
(737, '1', 'Debby & Rick', '2022', '12', '17', '19', '00', '2022', '12', '17', '23', '59', 'false', 'Pelt', 'Bijeenkomst. ', '2', '1,2', '#00a65a', 'Toegevoegd', '2022-12-08 21:59:53', '2022-12-12 13:22:52'),
(738, '3', 'Martha Activiteit', '2022', '12', '14', '19', '00', '2022', '12', '14', '22', '00', 'false', 'de vranken', 'zaal versieren voor kerstfeest', '2', '', '#17a2b8', 'Toegevoegd', '2022-12-10 10:46:07', '2022-12-12 09:33:49'),
(739, '3', 'Martha Activiteit', '2022', '12', '15', '18', '00', '2022', '12', '15', '23', '00', 'false', 'de vranken', 'kerstfeest FERM', '2', '', '#17a2b8', 'Toegevoegd', '2022-12-10 10:48:11', '2022-12-12 09:33:49'),
(745, '2', 'Henri Werk', '2022', '12', '12', '09', '00', '2022', '12', '12', '18', '00', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2022-12-11 09:27:50', '2022-12-12 09:33:49'),
(746, '2', 'Henri Werk', '2022', '12', '13', '09', '00', '2022', '12', '13', '18', '00', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2022-12-11 09:28:40', '2022-12-12 09:33:49'),
(747, '2', 'Henri Werk', '2022', '12', '15', '09', '00', '2022', '12', '15', '18', '00', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2022-12-11 09:29:54', '2022-12-12 09:33:49'),
(748, '2', 'Henri Werk', '2022', '12', '17', '09', '30', '2022', '12', '17', '17', '00', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2022-12-11 09:31:21', '2022-12-12 09:33:49'),
(749, '2', 'Henri Werk', '2022', '12', '18', '12', '00', '2022', '12', '18', '17', '00', 'false', 'Aalst-waarle, Nederland', 'Koopzondag ', '2', '', '#007bff', 'Toegevoegd', '2022-12-11 09:33:06', '2022-12-12 09:33:49'),
(751, '1', 'Andy ago interim', '2022', '12', '15', '10', '00', '2022', '12', '15', '10', '5', 'false', 'Achel', 'Video gesprek voor zoeken naar een job. ', '6', '', '#ffc107', 'Gewijzigd', '2022-12-13 14:09:17', '2022-12-15 14:13:03'),
(752, '1', 'kerstfeest', '2022', '12', '24', '17', '00', '2022', '12', '24', '20', '00', 'false', 'Pelt', 'Familie kerstfeest ', '4', '1, 2, 3', '#00a65a', 'Toegevoegd', '2022-12-16 16:37:37', '2022-12-16 16:39:41'),
(753, '1', 'kyani vakantie', '2022', '12', '24', '13', '00', '2023', '01', '01', '19', '00', 'false', 'Achel', 'kyani komt zoals elk jaar terug kerst vieren bij zen broer.', '2,6', '1,2,6', '#00a65a', 'Toegevoegd', '2022-12-16 16:48:56', '2022-12-20 10:58:36'),
(754, '1', 'Kerstdiner   ', '2022', '12', '25', '16', '00', '2022', '12', '25', '22', '00', 'false', 'Achel', 'Kerst vieren met lekker eten Onder 4 personen.', '2', '1, 2, 3,6', '#00a65a', 'Toegevoegd', '2022-12-17 16:00:50', '2022-12-20 10:58:59'),
(755, '1', 'Kerstdiner   ', '2022', '12', '26', '16', '00', '2022', '12', '26', '23', '59', 'false', 'Achel', 'Kerst vieren met lekker eten Onder 4 personen.', '6', '1, 2,6', '#00a65a', 'Toegevoegd', '2022-12-17 16:08:06', '2022-12-20 10:58:49'),
(756, '2', 'Henri Cools Werk', '2022', '12', '19', '09', '00', '2022', '12', '19', '18', '00', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2022-12-20 07:43:46', '2022-12-20 07:43:46'),
(757, '2', 'Henri Cools Werk', '2022', '12', '20', '09', '00', '2022', '12', '20', '22', '00', 'false', 'Aalst-waarle, Nederland', 'Werk + kerstborrel ', '2', '', '#007bff', 'Toegevoegd', '2022-12-20 07:45:02', '2022-12-20 07:45:02'),
(758, '2', 'Henri Cools Werk', '2022', '12', '22', '09', '00', '2022', '12', '21', '18', '00', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2022-12-20 07:45:53', '2022-12-20 07:45:53'),
(759, '2', 'Henri Cools Werk', '2022', '12', '24', '09', '30', '2022', '12', '24', '16', '00', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2022-12-20 07:46:54', '2022-12-20 07:46:54'),
(760, '2', 'Henri Cools Werk', '2022', '12', '27', '09', '00', '2022', '12', '27', '18', '00', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2022-12-20 07:47:54', '2022-12-20 07:47:54'),
(761, '2', 'Henri Cools Werk', '2022', '12', '29', '09', '00', '2022', '12', '29', '18', '00', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2022-12-20 07:48:53', '2022-12-20 07:48:53'),
(762, '2', 'Henri Cools Werk', '2022', '12', '31', '09', '30', '2022', '12', '31', '16', '00', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2022-12-20 07:49:44', '2022-12-20 07:49:44'),
(763, '1', 'Solidaris', '2022', '12', '29', '11', '00', '2022', '12', '29', '11', '30', 'false', 'Stadwaag 3 3930 Hamont-Achel ', 'Papieren in orde brengen voor aanvraag sociaal tarief. ', '6', '', '#f56954', 'Toegevoegd', '2022-12-20 11:39:39', '2022-12-20 11:39:39'),
(764, '1', 'Tom eten', '2022', '12', '29', '19', '30', '2022', '12', '29', '22', '00', 'false', 'Achel', 'Kerstavond ', '6', '1, 2, 6', '#00a65a', 'Toegevoegd', '2022-12-23 16:16:19', '2022-12-23 16:16:19'),
(765, '1', 'VDAB bellen', '2022', '12', '29', '08', '00', '2022', '12', '29', '18', '00', 'false', 'Achel', 'Bellen. VDAB. ', '2', '', '#ffc107', 'Toegevoegd', '2022-12-23 16:18:20', '2022-12-23 16:18:20'),
(767, '2', 'Henri Cools Werk', '2023', '01', '02', '08', '30', '2023', '01', '02', '18', '00', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2022-12-31 17:12:19', '2023-01-08 12:09:57'),
(768, '2', 'Henri Cools Werk', '2023', '01', '03', '09', '00', '2023', '01', '03', '9', '00', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2022-12-31 17:13:31', '2023-01-08 12:09:46'),
(772, '1', 'Sollicitatie gesprek timco', '2023', '01', '02', '15', '00', '2023', '01', '02', '15', '30', 'false', 'Waarle', '', '2', '', '#ffc107', 'Toegevoegd', '2023-01-01 01:55:26', '2023-01-01 01:55:26'),
(773, '1', 'Andy Windels Werk', '2023', '01', '04', '09', '00', '2023', '01', '04', '18', '00', 'false', 'Oploo', '', '2', '', '#ffc107', 'Toegevoegd', '2023-01-02 22:33:57', '2023-01-02 22:33:57'),
(774, '1', 'Andy Windels Werk', '2023', '01', '05', '09', '00', '2023', '01', '05', '18', '00', 'false', 'Oploo', '', '2', '', '#ffc107', 'Toegevoegd', '2023-01-02 22:36:40', '2023-01-02 22:36:40'),
(775, '1', 'Andy Windels Werk', '2023', '01', '06', '09', '00', '2023', '01', '06', '18', '00', 'false', 'Oploo', '', '2', '', '#ffc107', 'Toegevoegd', '2023-01-02 22:37:39', '2023-01-02 22:37:39'),
(776, '1', 'Andy Windels Werk', '2023', '01', '07', '09', '30', '2023', '01', '07', '17', '00', 'false', 'Oploo', '', '2', '', '#ffc107', 'Gewijzigd', '2023-01-02 22:39:39', '2023-01-02 22:48:26'),
(783, '1', 'Andy Windels Werk', '2023', '01', '10', '09', '00', '2023', '01', '10', '18', '00', 'false', 'Oploo', '', '2', '', '#ffc107', 'Toegevoegd', '2023-01-07 07:14:45', '2023-01-07 07:14:45'),
(784, '1', 'Andy Windels Werk', '2023', '01', '11', '09', '00', '2023', '01', '11', '18', '00', 'false', 'Oploo', '', '2', '', '#ffc107', 'Toegevoegd', '2023-01-07 07:15:23', '2023-01-07 07:15:23'),
(785, '1', 'Andy Windels Werk', '2023', '01', '12', '09', '00', '2023', '01', '12', '18', '00', 'false', 'Oploo', '', '2', '', '#ffc107', 'Toegevoegd', '2023-01-07 07:16:27', '2023-01-07 07:16:27'),
(786, '1', 'Andy Windels Werk', '2023', '01', '12', '09', '00', '2023', '01', '12', '18', '00', 'false', 'Oploo', '', '2', '', '#ffc107', 'Toegevoegd', '2023-01-07 07:16:28', '2023-01-07 07:16:28'),
(787, '1', 'Andy Windels Werk', '2023', '01', '13', '09', '00', '2023', '01', '13', '20', '00', 'false', 'Oploo', '', '2', '', '#ffc107', 'Gewijzigd', '2023-01-07 07:17:33', '2023-01-14 07:40:49'),
(788, '1', 'Andy Windels Werk', '2023', '01', '14', '09', '30', '2023', '01', '14', '17', '00', 'false', 'Oploo', '', '2', '', '#ffc107', 'Toegevoegd', '2023-01-07 07:18:15', '2023-01-07 07:18:15'),
(789, '1', 'Andy Windels Werk', '2023', '01', '16', '09', '00', '2023', '01', '16', '18', '00', 'false', 'Oploo', '', '2', '', '#ffc107', 'Toegevoegd', '2023-01-07 07:19:40', '2023-01-07 07:19:40'),
(790, '1', 'Andy Windels Werk', '2023', '01', '17', '09', '00', '2023', '01', '17', '18', '00', 'false', 'Oploo', '', '2', '', '#ffc107', 'Toegevoegd', '2023-01-07 07:20:33', '2023-01-07 07:20:33'),
(791, '1', 'Andy Windels Werk', '2023', '01', '18', '09', '00', '2023', '01', '18', '18', '00', 'false', 'Oploo', '', '2', '', '#ffc107', 'Toegevoegd', '2023-01-07 07:21:11', '2023-01-07 07:21:11'),
(792, '1', 'Andy Windels Werk', '2023', '01', '19', '09', '00', '2023', '01', '19', '18', '00', 'false', 'Oploo', '', '2', '', '#ffc107', 'Toegevoegd', '2023-01-07 07:21:47', '2023-01-07 07:21:47'),
(793, '1', 'Andy Windels Werk', '2023', '01', '21', '09', '30', '2023', '01', '21', '17', '00', 'false', 'Oploo', '', '2', '', '#ffc107', 'Toegevoegd', '2023-01-07 07:22:41', '2023-01-07 07:22:41'),
(794, '3', 'Martha Sturmans Activiteit', '2023', '01', '08', '10', '00', '2023', '01', '08', '20', '00', 'false', 'musical Vergeet Barbera in Puurs', '', '2', '1, 2, 3', '#17a2b8', 'Toegevoegd', '2023-01-07 09:44:22', '2023-01-07 09:44:22'),
(795, '3', 'Martha Sturmans Activiteit', '2023', '01', '11', '13', '00', '2023', '01', '11', '20', '00', 'false', 'de vranken feestmiddag samana herent', '', '2', '1, 2', '#17a2b8', 'Toegevoegd', '2023-01-07 09:47:05', '2023-01-07 09:47:05'),
(796, '3', 'Martha Sturmans Activiteit', '2023', '01', '12', '19', '30', '2023', '01', '12', '22', '30', 'false', 'de vranken vergadering ferm', '', '2', '1, 2, 3', '#17a2b8', 'Toegevoegd', '2023-01-07 09:49:47', '2023-01-07 09:49:47'),
(797, '2', 'Henri psygoloog', '2023', '01', '17', '10', '30', '2023', '01', '17', '11', '30', 'false', 'Pelt ', ' ', '', '', '#f56954', 'Toegevoegd', '2023-01-10 19:40:38', '2023-01-10 19:40:38'),
(798, '2', 'Henri ziekenhuis ', '2023', '01', '20', '10', '15', '2023', '01', '20', '11', '15', 'false', 'Edegem ', ' ', '', '', '#f56954', 'Toegevoegd', '2023-01-10 19:43:53', '2023-01-10 19:43:53'),
(799, '2', 'Henri trixxo', '2023', '01', '12', '09', '00', '2023', '01', '12', '10', '00', 'false', 'Lommel', 'Gesprek bij trixxo Lommel ', '', '', '#007bff', 'Toegevoegd', '2023-01-11 09:34:01', '2023-01-11 09:34:01'),
(800, '1', 'Andy Windels Werk', '2023', '01', '23', '09', '00', '2023', '01', '23', '18', '00', 'false', 'Oploo', '', '2', '', '#ffc107', 'Toegevoegd', '2023-01-14 07:37:12', '2023-01-14 07:37:12'),
(801, '1', 'Andy Windels Werk', '2023', '01', '25', '09', '00', '2023', '01', '25', '18', '00', 'false', 'Oploo', '', '2', '', '#ffc107', 'Toegevoegd', '2023-01-14 07:37:59', '2023-01-14 07:37:59'),
(802, '1', 'Andy Windels Werk', '2023', '01', '26', '09', '00', '2023', '01', '26', '18', '00', 'false', 'Oploo', '', '2', '', '#ffc107', 'Toegevoegd', '2023-01-14 07:38:39', '2023-01-14 07:38:39'),
(803, '1', 'Andy Windels Werk', '2023', '01', '27', '09', '00', '2023', '01', '27', '20', '00', 'false', 'Oploo', '', '2', '', '#ffc107', 'Gewijzigd', '2023-01-14 07:39:42', '2023-01-29 13:13:43'),
(804, '1', 'Andy Windels Werk', '2023', '01', '28', '09', '30', '2023', '01', '28', '16', '30', 'false', 'Oploo', '', '2', '', '#ffc107', 'Gewijzigd', '2023-01-14 07:40:25', '2023-01-29 13:14:07'),
(805, '1', 'Andy Windels Werk', '2023', '01', '30', '09', '00', '2023', '01', '30', '18', '00', 'false', 'Oploo', '', '2', '', '#ffc107', 'Toegevoegd', '2023-01-14 07:41:37', '2023-01-14 07:41:37'),
(806, '1', 'Andy Windels Werk', '2023', '01', '31', '09', '00', '2023', '01', '31', '18', '00', 'false', 'Oploo', '', '2', '', '#ffc107', 'Toegevoegd', '2023-01-14 07:42:17', '2023-01-14 07:42:17'),
(807, '1', 'Andy Windels Werk', '2023', '02', '02', '09', '00', '2023', '02', '02', '18', '00', 'false', 'Oploo', '', '2', '', '#ffc107', 'Toegevoegd', '2023-01-14 07:44:59', '2023-01-14 07:44:59'),
(808, '1', 'Andy Windels Werk', '2023', '02', '03', '09', '00', '2023', '02', '03', '20', '00', 'false', 'Oploo', '', '2', '', '#ffc107', 'Gewijzigd', '2023-01-14 07:45:39', '2023-01-14 07:49:35'),
(809, '1', 'Andy Windels Werk', '2023', '02', '04', '09', '30', '2023', '02', '04', '17', '00', 'false', 'Oploo', '', '2', '', '#ffc107', 'Toegevoegd', '2023-01-14 07:46:31', '2023-01-14 07:46:31'),
(810, '3', 'Martha Sturmans Activiteit', '2023', '01', '22', '11', '00', '2023', '01', '22', '18', '00', 'false', 'de vranken', 'nieuwjaarsfeestje buurt en appartement', '2', '1, 2', '#17a2b8', 'Toegevoegd', '2023-01-21 18:24:05', '2023-01-21 18:24:05'),
(811, '1', 'Bepa construct (architect)', '2023', '01', '23', '09', '30', '2023', '01', '23', '10', '30', 'false', 'Achel', 'Architect komt kijken naar scheur de van het plafond in de garage. ', '2', '2', '#00a65a', 'Gewijzigd', '2023-01-22 10:52:30', '2023-01-22 10:53:11'),
(812, '1', 'Andy Windels Werk', '2023', '02', '06', '09', '00', '2023', '02', '06', '18', '00', 'false', 'Oploo ', '', '2', '', '#ffc107', 'Toegevoegd', '2023-01-30 07:14:12', '2023-01-30 07:14:12'),
(813, '1', 'Andy Windels Werk', '2023', '02', '06', '09', '00', '2023', '02', '06', '18', '00', 'false', 'Oploo ', '', '2', '', '#ffc107', 'Toegevoegd', '2023-01-30 07:14:26', '2023-01-30 07:14:26'),
(814, '1', 'Andy Windels Werk', '2023', '02', '07', '09', '00', '2023', '02', '07', '18', '00', 'false', 'Oploo', '', '2', '', '#ffc107', 'Toegevoegd', '2023-01-30 07:15:04', '2023-01-30 07:15:04'),
(815, '1', 'Andy Windels Werk', '2023', '02', '07', '09', '00', '2023', '02', '07', '18', '00', 'false', 'Oploo', '', '2', '', '#ffc107', 'Toegevoegd', '2023-01-30 07:15:14', '2023-01-30 07:15:14'),
(816, '1', 'Andy Windels Werk', '2023', '02', '09', '09', '00', '2023', '02', '09', '18', '00', 'false', 'Oploo', '', '2', '', '#ffc107', 'Toegevoegd', '2023-01-30 07:15:53', '2023-01-30 07:15:53'),
(817, '1', 'Andy Windels Werk', '2023', '02', '09', '09', '00', '2023', '02', '09', '18', '00', 'false', 'Oploo', '', '2', '', '#ffc107', 'Toegevoegd', '2023-01-30 07:16:33', '2023-01-30 07:16:33'),
(818, '1', 'Andy Windels Werk', '2023', '02', '10', '09', '00', '2023', '02', '10', '18', '00', 'false', 'Oploo', '', '2', '', '#ffc107', 'Toegevoegd', '2023-01-30 07:17:08', '2023-01-30 07:17:08'),
(819, '1', 'Andy Windels Werk', '2023', '02', '11', '09', '30', '2023', '02', '11', '17', '00', 'false', 'Oploo', '', '2', '', '#ffc107', 'Toegevoegd', '2023-01-30 07:17:52', '2023-01-30 07:17:52'),
(820, '2', 'Onderhoud ketel', '2023', '02', '03', '08', '00', '2023', '01', '03', '12', '00', 'false', 'Thuis achel', 'Onderhoud ketel ', '', '', '#007bff', 'Toegevoegd', '2023-01-30 16:41:15', '2023-01-30 16:41:15'),
(821, '2', 'Henri gesprek ', '2023', '01', '31', '10', '15', '2023', '01', '31', '11', '15', 'false', 'Pelt', '', '', '', '#f56954', 'Toegevoegd', '2023-01-30 16:42:45', '2023-01-30 16:42:45'),
(822, '2', 'Garage Henri', '2023', '02', '01', '13', '00', '2023', '02', '01', '18', '00', 'false', 'Pelt', 'Henri garage en keuring ', '', '', '#f56954', 'Toegevoegd', '2023-01-30 16:49:56', '2023-01-30 16:49:56'),
(823, '1', 'Andy Windels Werk', '2023', '02', '13', '09', '00', '2023', '02', '13', '18', '00', 'false', 'Oploo', '', '2', '', '#ffc107', 'Toegevoegd', '2023-01-31 06:48:28', '2023-01-31 06:48:28'),
(824, '1', 'Andy Windels Werk', '2023', '02', '14', '09', '00', '2023', '02', '14', '18', '00', 'false', 'Oploo', '', '2', '', '#ffc107', 'Toegevoegd', '2023-01-31 06:49:14', '2023-01-31 06:49:14'),
(825, '1', 'Andy Windels Werk', '2023', '02', '16', '09', '00', '2023', '02', '16', '18', '00', 'false', 'Oploo', '', '2', '', '#ffc107', 'Toegevoegd', '2023-01-31 06:50:04', '2023-01-31 06:50:04'),
(826, '1', 'Andy Windels Werk', '2023', '02', '17', '09', '00', '2023', '02', '17', '20', '00', 'false', 'Oploo', 'Sleutels Oploo meenemen en alarmcode vragen. ', '2', '', '#ffc107', 'Toegevoegd', '2023-01-31 06:51:33', '2023-01-31 06:51:33'),
(827, '1', 'Andy Windels Werk', '2023', '02', '18', '09', '30', '2023', '02', '18', '17', '00', 'false', 'Oploo', 'Eerste werkdag als assistent Filiaalmanger alleen. ', '2', '', '#ffc107', 'Toegevoegd', '2023-01-31 06:53:15', '2023-01-31 06:53:15'),
(828, '2', 'Henri Cools Werk', '2023', '02', '06', '13', '00', '2023', '02', '06', '18', '15', 'false', 'Aalst-waarle, Nederland', 'Valkenswaard', '2', '', '#007bff', 'Toegevoegd', '2023-01-31 18:32:46', '2023-01-31 18:32:46'),
(829, '2', 'Henri Cools Werk', '2023', '02', '07', '13', '00', '2023', '02', '07', '18', '15', 'false', 'Aalst-waarle, Nederland', 'Valkenswaard ', '2', '', '#007bff', 'Toegevoegd', '2023-01-31 18:34:20', '2023-01-31 18:34:20'),
(830, '2', 'Henri Cools Werk', '2023', '02', '08', '10', '00', '2023', '02', '08', '17', '00', 'false', 'Aalst-waarle, Nederland', ' Ok ', '2', '', '#007bff', 'Toegevoegd', '2023-01-31 18:36:17', '2023-01-31 18:36:17'),
(831, '2', 'Henri Cools Werk', '2023', '02', '09', '17', '00', '2023', '02', '08', '21', '00', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-01-31 18:38:02', '2023-01-31 18:38:02'),
(832, '2', 'Henri Cools Werk', '2023', '02', '11', '09', '00', '2023', '02', '11', '18', '15', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-01-31 18:39:42', '2023-01-31 18:39:42'),
(833, '2', 'Henri Cools Werk', '2023', '02', '13', '13', '00', '2023', '02', '13', '18', '15', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-01-31 18:40:50', '2023-01-31 18:40:50'),
(834, '2', 'Henri Cools Werk', '2023', '02', '14', '13', '00', '2023', '02', '14', '18', '15', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-01-31 18:41:55', '2023-01-31 18:41:55'),
(835, '2', 'Henri Cools Werk', '2023', '02', '15', '08', '00', '2023', '02', '15', '15', '00', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-01-31 18:43:30', '2023-01-31 18:43:30'),
(836, '2', 'Henri Cools Werk', '2023', '02', '16', '17', '00', '2023', '02', '16', '21', '00', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-01-31 18:44:24', '2023-01-31 18:44:24'),
(837, '2', 'Henri Cools Werk', '2023', '02', '18', '09', '15', '2023', '02', '18', '18', '15', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-01-31 18:45:51', '2023-01-31 18:45:51'),
(838, '2', 'Henri Cools Werk', '2023', '02', '20', '13', '00', '2023', '02', '20', '18', '15', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-01-31 18:46:56', '2023-01-31 18:46:56'),
(839, '2', 'Henri Cools Werk', '2023', '02', '21', '08', '00', '2023', '02', '21', '13', '00', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-01-31 18:48:24', '2023-01-31 18:48:24'),
(840, '2', 'Henri Cools Werk', '2023', '02', '22', '08', '00', '2023', '02', '22', '15', '00', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-01-31 18:49:27', '2023-01-31 18:49:27'),
(841, '2', 'Henri Cools Werk', '2023', '02', '23', '09', '00', '2023', '02', '23', '17', '00', 'false', 'Valkenswaard Nederland ', ' ', '2', '', '#007bff', 'Gewijzigd', '2023-01-31 18:50:35', '2023-02-22 17:32:19'),
(842, '1', 'Henri Cools Werk', '2023', '02', '25', '08', '00', '2023', '02', '25', '17', '00', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#007bff', 'Gewijzigd', '2023-01-31 18:52:43', '2023-02-16 11:22:30'),
(843, '1', 'Andy Windels Werk', '2023', '02', '21', '09', '00', '2023', '02', '21', '18', '00', 'false', 'Oploo', '', '2', '', '#ffc107', 'Toegevoegd', '2023-02-05 22:33:29', '2023-02-05 22:33:29'),
(844, '1', 'Andy Windels Werk', '2023', '02', '23', '09', '00', '2023', '02', '23', '18', '00', 'false', 'Oploo', '', '2', '', '#ffc107', 'Toegevoegd', '2023-02-05 22:34:38', '2023-02-05 22:34:38'),
(845, '1', 'Andy Windels Werk', '2023', '02', '24', '09', '00', '2023', '02', '24', '20', '00', 'false', 'Oploo', '', '2', '', '#ffc107', 'Gewijzigd', '2023-02-05 22:35:28', '2023-02-16 11:21:31'),
(846, '1', 'Andy Windels Werk', '2023', '02', '25', '09', '30', '2023', '02', '25', '17', '00', 'false', 'Oploo', '', '2', '', '#ffc107', 'Toegevoegd', '2023-02-05 22:36:27', '2023-02-05 22:36:27'),
(847, '1', 'Andy Windels Werk', '2023', '02', '27', '09', '00', '2023', '02', '27', '18', '00', 'false', 'Oploo', '', '2', '', '#ffc107', 'Toegevoegd', '2023-02-05 22:37:10', '2023-02-05 22:37:10'),
(848, '1', 'Andy Windels Werk', '2023', '02', '28', '09', '00', '2023', '02', '28', '18', '00', 'false', 'Oploo', '', '2', '', '#ffc107', 'Toegevoegd', '2023-02-05 22:38:00', '2023-02-05 22:38:00'),
(849, '1', 'Andy Windels Werk', '2023', '03', '02', '09', '00', '2023', '03', '02', '18', '00', 'false', 'Oploo', '', '2', '', '#ffc107', 'Toegevoegd', '2023-02-05 22:38:38', '2023-02-05 22:38:38'),
(850, '1', 'Andy Windels Werk', '2023', '03', '03', '09', '00', '2023', '03', '03', '18', '00', 'false', 'Oploo', '', '2', '', '#ffc107', 'Toegevoegd', '2023-02-05 22:39:30', '2023-02-05 22:39:30'),
(851, '1', 'Andy Windels Werk', '2023', '03', '04', '09', '30', '2023', '03', '04', '17', '00', 'false', 'Oploo', '', '2', '', '#ffc107', 'Toegevoegd', '2023-02-05 22:40:11', '2023-02-05 22:40:11'),
(852, '1', 'Natasja verjaardag', '2023', '02', '10', '20', '00', '2023', '02', '10', '22', '00', 'false', 'Valkenswaard', 'Verjaardag Natasja oud collega timco', '2', '2', '#ffc107', 'Toegevoegd', '2023-02-10 06:46:41', '2023-02-10 06:46:41'),
(853, '1', 'Ouders andy', '2023', '02', '19', '11', '00', '2023', '02', '19', '19', '00', 'false', 'Oosteeklo', 'Verjaardag Kyani vieren. ', '2, 7', '1, 2, 4, 5, 6', '#00a65a', 'Toegevoegd', '2023-02-13 22:02:09', '2023-02-13 22:02:09'),
(854, '2', 'Henri Cools Werk', '2023', '02', '28', '08', '00', '2023', '02', '28', '17', '00', 'false', 'Valkenswaard', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-02-20 08:01:37', '2023-02-20 08:01:37'),
(855, '2', 'Henri Cools Werk', '2023', '03', '01', '08', '00', '2023', '03', '01', '17', '00', 'false', 'Valkenswaard', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-02-20 08:02:51', '2023-02-20 08:02:51'),
(856, '2', 'Henri Cools Werk', '2023', '03', '02', '17', '00', '2023', '03', '02', '21', '00', 'false', 'Valkenswaard', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-02-20 08:04:25', '2023-02-20 08:04:25'),
(857, '2', 'Henri Cools Werk', '2023', '03', '03', '08', '00', '2023', '03', '03', '17', '00', 'false', 'Valkenswaard', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-02-20 08:05:22', '2023-02-20 08:05:22'),
(858, '2', 'Henri Cools Werk', '2023', '03', '07', '08', '00', '2023', '03', '07', '15', '00', 'false', 'Valkenswaard', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-02-20 08:07:50', '2023-02-20 08:07:50'),
(859, '2', 'Henri Cools Werk', '2023', '03', '08', '08', '00', '2023', '03', '08', '17', '00', 'false', 'Valkenswaard', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-02-20 08:08:51', '2023-02-20 08:08:51'),
(860, '2', 'Henri Cools Werk', '2023', '03', '10', '08', '00', '2023', '03', '10', '17', '00', 'false', 'Valkenswaard', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-02-20 08:10:10', '2023-02-20 08:10:10'),
(861, '2', 'Henri Cools Werk', '2023', '03', '11', '12', '00', '2023', '03', '11', '18', '15', 'false', 'Valkenswaard', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-02-20 08:11:42', '2023-02-20 08:11:42'),
(862, '2', 'Henri Cools Werk', '2023', '03', '11', '12', '00', '2023', '03', '11', '18', '15', 'false', 'Valkenswaard', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-02-20 08:40:53', '2023-02-20 08:40:53'),
(863, '2', 'Henri Cools Werk', '2023', '02', '27', '09', '00', '2023', '02', '27', '17', '00', 'false', 'Valkenswaard', ' Extra dag ', '2', '', '#007bff', 'Toegevoegd', '2023-02-24 20:00:23', '2023-02-24 20:00:23'),
(864, '2', 'Henri Cools Werk', '2023', '02', '27', '09', '00', '2023', '02', '27', '17', '00', 'false', 'Valkenswaard', ' Extra dag ', '2', '', '#007bff', 'Toegevoegd', '2023-02-24 20:01:40', '2023-02-24 20:01:40'),
(865, '1', 'Andy Windels Werk', '2023', '03', '06', '09', '00', '2023', '03', '06', '18', '00', 'false', 'Oploo, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2023-02-27 07:09:20', '2023-02-27 07:09:20'),
(866, '1', 'Andy Windels Werk', '2023', '03', '07', '09', '00', '2023', '03', '07', '18', '00', 'false', 'Oploo, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2023-02-27 07:10:10', '2023-02-27 07:10:10'),
(867, '1', 'Andy Windels Werk', '2023', '03', '09', '09', '00', '2023', '03', '09', '18', '00', 'false', 'Oploo, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2023-02-27 07:11:03', '2023-02-27 07:11:03'),
(868, '1', 'Andy Windels Werk', '2023', '03', '10', '09', '00', '2023', '03', '10', '18', '00', 'false', 'Oploo, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2023-02-27 07:11:50', '2023-02-27 07:11:50'),
(869, '1', 'Andy Windels Werk', '2023', '03', '11', '09', '30', '2023', '03', '11', '17', '00', 'false', 'Oploo, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2023-02-27 07:12:58', '2023-02-27 07:12:58'),
(870, '1', 'Andy Windels Werk', '2023', '03', '11', '09', '30', '2023', '03', '11', '17', '00', 'false', 'Oploo, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2023-02-27 07:12:59', '2023-02-27 07:12:59'),
(871, '1', 'Andy Windels Werk', '2023', '03', '12', '12', '00', '2023', '03', '12', '17', '00', 'false', 'Oploo, Nederland', 'Koopzondag', '2', '', '#ffc107', 'Toegevoegd', '2023-02-27 07:13:45', '2023-02-27 07:13:45'),
(872, '1', 'Andy Windels Werk', '2023', '03', '13', '09', '00', '2023', '03', '13', '18', '00', 'false', 'Oploo, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2023-02-27 07:15:12', '2023-02-27 07:15:12'),
(873, '1', 'Andy Windels Werk', '2023', '03', '14', '09', '00', '2023', '03', '14', '13', '00', 'false', 'Oploo, Nederland', ' ', '2', '', '#ffc107', 'Gewijzigd', '2023-02-27 07:15:58', '2023-03-16 22:28:57'),
(874, '1', 'Andy Windels Werk', '2023', '03', '17', '09', '00', '2023', '03', '17', '18', '00', 'false', 'Oploo, Nederland', ' ', '2', '', '#ffc107', 'Gewijzigd', '2023-02-27 07:16:42', '2023-03-11 09:09:50'),
(875, '1', 'Andy Windels Werk', '2023', '03', '18', '09', '30', '2023', '03', '18', '17', '00', 'false', 'Oploo, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2023-02-27 07:18:06', '2023-02-27 07:18:06'),
(876, '1', 'Andy Windels Werk', '2023', '03', '20', '09', '00', '2023', '03', '20', '18', '00', 'false', 'Oploo, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2023-02-27 08:24:17', '2023-02-27 08:24:17'),
(877, '1', 'Andy Windels Werk', '2023', '03', '21', '09', '00', '2023', '03', '21', '18', '00', 'false', 'Oploo, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2023-02-27 08:24:53', '2023-02-27 08:24:53'),
(878, '1', 'Andy Windels Werk', '2023', '03', '22', '09', '00', '2023', '03', '22', '18', '00', 'false', 'Oploo, Nederland', ' ', '2', '', '#ffc107', 'Gewijzigd', '2023-02-27 08:25:33', '2023-03-07 20:31:35'),
(879, '1', 'Andy Windels Werk', '2023', '03', '24', '09', '00', '2023', '03', '24', '18', '00', 'false', 'Oploo, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2023-02-27 08:26:21', '2023-02-27 08:26:21'),
(880, '1', 'Andy Windels Werk', '2023', '03', '25', '09', '30', '2023', '03', '25', '17', '00', 'false', 'Oploo, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2023-02-27 08:26:58', '2023-02-27 08:26:58'),
(881, '2', 'Henri Cools Werk', '2023', '03', '14', '11', '00', '2023', '03', '14', '18', '15', 'false', 'Valkenswaard', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-03-01 06:48:55', '2023-03-01 06:48:55'),
(882, '2', 'Henri Cools Werk', '2023', '03', '15', '08', '00', '2023', '03', '15', '17', '00', 'false', 'Valkenswaard', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-03-01 06:49:51', '2023-03-01 06:49:51'),
(883, '2', 'Henri Cools Werk', '2023', '03', '17', '08', '00', '2023', '03', '17', '17', '00', 'false', 'Valkenswaard', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-03-01 06:51:05', '2023-03-01 06:51:05'),
(884, '2', 'Henri Cools Werk', '2023', '03', '18', '12', '00', '2023', '03', '18', '18', '15', 'false', 'Valkenswaard', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-03-01 06:52:08', '2023-03-01 06:52:08'),
(885, '2', 'Henri Cools Werk', '2023', '03', '20', '12', '00', '2023', '03', '20', '21', '00', 'false', 'Valkenswaard', 'Werken tot 18h maar nog 3h balans winkel tellen ', '2', '', '#007bff', 'Toegevoegd', '2023-03-01 06:54:10', '2023-03-01 06:54:10'),
(886, '2', 'Henri Cools Werk', '2023', '03', '21', '09', '00', '2023', '03', '21', '18', '15', 'false', 'Valkenswaard', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-03-01 06:55:19', '2023-03-01 06:55:19'),
(887, '2', 'Henri Cools Werk', '2023', '03', '22', '08', '00', '2023', '03', '22', '17', '00', 'false', 'Valkenswaard', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-03-01 06:56:15', '2023-03-01 06:56:15'),
(888, '2', 'Henri Cools Werk', '2023', '03', '24', '08', '00', '2023', '03', '24', '17', '00', 'false', 'Valkenswaard', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-03-01 06:57:03', '2023-03-01 06:57:03'),
(889, '1', 'Andy Windels Werk', '2023', '03', '27', '09', '00', '2023', '03', '27', '18', '00', 'false', 'Oploo, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2023-03-07 20:34:43', '2023-03-07 20:34:43'),
(890, '1', 'Andy Windels Werk', '2023', '03', '28', '09', '00', '2023', '03', '28', '18', '00', 'false', 'Oploo, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2023-03-07 20:35:16', '2023-03-07 20:35:16'),
(891, '1', 'Andy Windels Werk', '2023', '03', '30', '09', '00', '2023', '03', '30', '18', '00', 'false', 'Aalst-waarle, Nederland', ' Voor meubels', '2', '', '#ffc107', 'Gewijzigd', '2023-03-07 20:35:54', '2023-03-24 17:54:16'),
(892, '1', 'Andy Windels Werk', '2023', '03', '31', '09', '00', '2023', '03', '31', '18', '00', 'false', 'Oploo, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2023-03-07 20:36:33', '2023-03-07 20:36:33'),
(893, '1', 'Andy Windels Werk', '2023', '04', '01', '09', '30', '2023', '04', '01', '17', '00', 'false', 'Oploo, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2023-03-07 20:37:14', '2023-03-07 20:37:14'),
(894, '1', 'Andy Windels Werk', '2023', '04', '03', '09', '00', '2023', '04', '03', '18', '00', 'false', 'Oploo, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2023-03-07 20:38:11', '2023-03-07 20:38:11'),
(895, '1', 'Andy Windels Werk', '2023', '04', '04', '08', '00', '2023', '04', '04', '18', '00', 'false', 'Aalst-waarle, Nederland', ' Personeels te kort opvangen', '2', '', '#ffc107', 'Gewijzigd', '2023-03-07 20:38:35', '2023-03-31 11:16:45'),
(896, '1', 'Andy Windels Werk', '2023', '04', '06', '09', '00', '2023', '04', '06', '18', '00', 'false', 'Aalst-waarle, Nederland', ' Personeel te kort opvangen', '2', '', '#ffc107', 'Gewijzigd', '2023-03-07 20:39:04', '2023-03-31 11:18:04'),
(897, '1', 'Andy Windels Werk', '2023', '04', '07', '09', '00', '2023', '04', '07', '18', '00', 'false', 'Oploo, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2023-03-07 20:39:53', '2023-03-07 20:39:53'),
(898, '1', 'Andy Windels Werk', '2023', '04', '08', '09', '30', '2023', '04', '08', '17', '00', 'false', 'Oploo, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2023-03-07 20:40:22', '2023-03-07 20:40:22'),
(899, '2', 'Henri Cools Werk', '2023', '03', '29', '08', '00', '2023', '03', '29', '17', '00', 'false', 'Valkenswaard', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-03-14 21:03:47', '2023-03-14 21:03:47'),
(900, '2', 'Henri Cools Werk', '2023', '03', '30', '09', '00', '2023', '03', '30', '18', '00', 'false', 'Valkenswaard', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-03-14 21:04:50', '2023-03-14 21:04:50'),
(901, '2', 'Henri Cools Werk', '2023', '03', '31', '08', '00', '2023', '03', '31', '17', '00', 'false', 'Valkenswaard', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-03-14 21:06:07', '2023-03-14 21:06:07'),
(902, '2', 'Henri Cools Werk', '2023', '04', '01', '09', '00', '2023', '04', '01', '18', '15', 'false', 'Valkenswaard', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-03-14 21:07:02', '2023-03-14 21:07:02'),
(903, '1', 'KBC ', '2023', '03', '23', '11', '00', '2023', '03', '23', '12', '00', 'false', 'KBC pelt', 'Informatie lening ', '2', '1, 2, 3', '#ffc107', 'Toegevoegd', '2023-03-18 12:34:15', '2023-03-18 12:34:15'),
(904, '1', 'Verjaardag Tom', '2023', '03', '25', '20', '00', '2023', '03', '25', '23', '00', 'false', 'Haasbostraat pelt', 'Verjaardag vieren ', '6', '1, 2', '#00a65a', 'Gewijzigd', '2023-03-25 06:57:29', '2023-03-25 06:58:31'),
(905, '2', 'Henri Cools Werk', '2023', '04', '03', '08', '00', '2023', '04', '03', '17', '00', 'false', 'Valkenswaard', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-03-28 17:22:35', '2023-03-28 17:22:35'),
(906, '2', 'Henri Cools Werk', '2023', '04', '05', '08', '00', '2023', '04', '05', '17', '00', 'false', 'Valkenswaard', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-03-28 17:23:45', '2023-03-28 17:23:45'),
(907, '2', 'Henri Cools Werk', '2023', '04', '06', '12', '00', '2023', '04', '06', '18', '00', 'false', 'Valkenswaard', 'Daarna tot 21h vergadering ', '2', '', '#007bff', 'Toegevoegd', '2023-03-28 17:25:11', '2023-03-28 17:25:11'),
(908, '2', 'Henri Cools Werk', '2023', '04', '08', '09', '00', '2023', '04', '08', '18', '00', 'false', 'Valkenswaard', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-03-28 17:26:42', '2023-03-28 17:26:42'),
(909, '2', 'Henri Cools Werk', '2023', '04', '08', '09', '00', '2023', '04', '08', '18', '00', 'false', 'Valkenswaard', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-03-28 17:27:21', '2023-03-28 17:27:21'),
(910, '2', 'Henri Cools Werk', '2023', '04', '12', '08', '00', '2023', '04', '12', '17', '00', 'false', 'Valkenswaard', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-03-28 17:28:22', '2023-03-28 17:28:22'),
(911, '2', 'Henri Cools Werk', '2023', '04', '13', '08', '00', '2023', '04', '13', '17', '00', 'false', 'Valkenswaard', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-03-28 17:29:40', '2023-03-28 17:29:40'),
(912, '2', 'Henri Cools Werk', '2023', '04', '14', '08', '00', '2023', '04', '14', '13', '00', 'false', 'Valkenswaard', ' ', '2', '', '#007bff', 'Gewijzigd', '2023-03-28 17:30:43', '2023-03-28 17:36:29'),
(913, '2', 'Henri Cools Werk', '2023', '04', '15', '09', '00', '2023', '04', '15', '18', '00', 'false', 'Valkenswaard', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-03-28 17:33:37', '2023-03-28 17:33:37'),
(914, '1', 'Andy Windels Werk', '2023', '04', '10', '12', '00', '2023', '04', '10', '17', '00', 'false', 'Oploo, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2023-03-31 20:13:17', '2023-03-31 20:13:17'),
(915, '1', 'Andy Windels Werk', '2023', '04', '11', '09', '00', '2023', '04', '11', '18', '00', 'false', 'Oploo, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2023-03-31 20:14:00', '2023-03-31 20:14:00'),
(916, '1', 'Andy Windels Werk', '2023', '04', '12', '09', '00', '2023', '04', '12', '18', '00', 'false', 'Oploo, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2023-03-31 20:14:32', '2023-03-31 20:14:32'),
(917, '1', 'Andy Windels Werk', '2023', '04', '13', '09', '00', '2023', '04', '13', '18', '00', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2023-03-31 20:15:09', '2023-03-31 20:15:09'),
(918, '1', 'Andy Windels Werk', '2023', '04', '17', '09', '00', '2023', '04', '17', '18', '00', 'false', 'Oploo, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2023-03-31 20:15:49', '2023-03-31 20:15:49'),
(919, '1', 'Andy Windels Werk', '2023', '04', '18', '09', '00', '2023', '04', '18', '18', '00', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2023-03-31 20:16:21', '2023-03-31 20:16:21'),
(920, '1', 'Andy Windels Werk', '2023', '04', '20', '09', '00', '2023', '04', '20', '18', '00', 'false', 'Oploo, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2023-03-31 20:17:10', '2023-03-31 20:17:10'),
(921, '1', 'Andy Windels Werk', '2023', '04', '21', '09', '00', '2023', '04', '21', '18', '00', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2023-03-31 20:17:42', '2023-03-31 20:17:42'),
(922, '1', 'Andy Windels Werk', '2023', '04', '22', '09', '30', '2023', '04', '22', '17', '00', 'false', 'Oploo, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2023-03-31 20:18:30', '2023-03-31 20:18:30'),
(923, '3', 'Martha Sturmans Activiteit', '2023', '04', '05', '13', '00', '2023', '04', '05', '20', '00', 'false', 'helpen verjaardagsfeestje mathias', '', '2', '', '#17a2b8', 'Toegevoegd', '2023-04-04 18:43:18', '2023-04-04 18:43:18'),
(924, '3', 'Martha Sturmans Activiteit', '2023', '04', '07', '12', '00', '2023', '04', '10', '20', '00', 'true', 'vakantie tilburg met de fam. van otten', '', '2', '', '#17a2b8', 'Toegevoegd', '2023-04-04 18:44:45', '2023-04-04 18:44:45'),
(925, '1', 'Andy Windels Werk', '2023', '04', '24', '09', '00', '2023', '04', '24', '18', '00', 'false', 'Oploo, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2023-04-09 17:04:27', '2023-04-09 17:04:27'),
(926, '1', 'Andy Windels Werk', '2023', '04', '25', '09', '00', '2023', '04', '25', '18', '00', 'false', 'Oploo, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2023-04-09 17:05:59', '2023-04-09 17:05:59'),
(928, '1', 'Andy Windels Werk', '2023', '04', '28', '09', '00', '2023', '04', '28', '20', '00', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2023-04-09 17:07:44', '2023-04-09 17:07:44'),
(929, '1', 'Andy Windels Werk', '2023', '04', '29', '09', '30', '2023', '04', '29', '17', '00', 'false', 'Oploo, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2023-04-09 17:09:41', '2023-04-09 17:09:41'),
(930, '2', 'Henri Cools Werk', '2023', '04', '17', '08', '00', '2023', '04', '17', '17', '00', 'false', 'Valkenswaard', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-04-12 20:37:29', '2023-04-12 20:37:29'),
(931, '2', 'Henri Cools Werk', '2023', '04', '19', '08', '00', '2023', '04', '19', '17', '00', 'false', 'Valkenswaard', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-04-12 20:39:03', '2023-04-12 20:39:03'),
(932, '2', 'Henri Cools Werk', '2023', '04', '21', '08', '00', '2023', '04', '21', '17', '00', 'false', 'Valkenswaard', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-04-12 20:40:11', '2023-04-12 20:40:11'),
(933, '2', 'Henri Cools Werk', '2023', '04', '22', '10', '00', '2023', '04', '22', '18', '16', 'false', 'Valkenswaard', ' ', '2', '', '#007bff', 'Gewijzigd', '2023-04-12 20:41:33', '2023-04-17 06:42:22'),
(934, '2', 'Henri Cools Werk', '2023', '04', '24', '08', '00', '2023', '04', '24', '17', '00', 'false', 'Valkenswaard', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-04-12 20:45:18', '2023-04-12 20:45:18'),
(935, '2', 'Henri Cools Werk', '2023', '04', '26', '08', '00', '2023', '04', '26', '17', '00', 'false', 'Valkenswaard', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-04-12 20:46:47', '2023-04-12 20:46:47'),
(936, '2', 'Henri Cools Werk', '2023', '04', '28', '08', '00', '2023', '04', '28', '17', '00', 'false', 'Valkenswaard', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-04-12 20:47:55', '2023-04-12 20:47:55'),
(937, '2', 'Henri Cools Werk', '2023', '04', '29', '08', '00', '2023', '04', '29', '17', '00', 'false', 'Valkenswaard', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-04-12 20:49:08', '2023-04-12 20:49:08'),
(938, '2', 'Henri Cools Werk', '2023', '05', '01', '08', '00', '2023', '05', '01', '17', '00', 'false', 'Valkenswaard', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-04-17 06:43:51', '2023-04-17 06:43:51'),
(939, '2', 'Henri Cools Werk', '2023', '05', '02', '08', '00', '2023', '05', '02', '17', '00', 'false', 'Valkenswaard', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-04-17 06:44:37', '2023-04-17 06:44:37'),
(940, '2', 'Henri Cools Werk', '2023', '05', '03', '08', '00', '2023', '05', '03', '13', '00', 'false', 'Valkenswaard', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-04-17 06:47:12', '2023-04-17 06:47:12'),
(941, '2', 'Henri Cools Werk', '2023', '05', '04', '08', '00', '2023', '05', '04', '17', '00', 'false', 'Valkenswaard', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-04-17 06:48:08', '2023-04-17 06:48:08'),
(942, '2', 'Henri Cools Werk', '2023', '05', '06', '12', '00', '2023', '05', '06', '18', '15', 'false', 'Valkenswaard', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-04-17 06:49:27', '2023-04-17 06:49:27'),
(943, '1', 'Onderhoud auto garage Kia ', '2023', '05', '10', '08', '30', '2023', '05', '10', '10', '00', 'false', 'Pelt ', 'Onderhoud auto', '2', '', '#ffc107', 'Toegevoegd', '2023-04-19 13:42:24', '2023-04-19 13:42:24'),
(944, '2', 'Henri Cools Werk', '2023', '04', '25', '13', '00', '2023', '04', '25', '18', '00', 'false', 'Valkenswaard', 'Invallen collega ', '2', '', '#007bff', 'Toegevoegd', '2023-04-20 18:15:41', '2023-04-20 18:15:41'),
(945, '1', 'Andy Windels Werk', '2023', '05', '01', '09', '00', '2023', '05', '01', '18', '00', 'false', 'Oploo, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2023-04-23 09:03:10', '2023-04-23 09:03:10'),
(946, '1', 'Andy Windels Werk', '2023', '05', '02', '09', '00', '2023', '05', '02', '18', '00', 'false', 'Oploo, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2023-04-23 09:03:52', '2023-04-23 09:03:52'),
(947, '1', 'Andy Windels Werk', '2023', '05', '04', '09', '00', '2023', '05', '04', '18', '00', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2023-04-23 09:04:32', '2023-04-23 09:04:32'),
(948, '1', 'Andy Windels Werk', '2023', '05', '05', '09', '00', '2023', '05', '05', '20', '00', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2023-04-23 09:05:15', '2023-04-23 09:05:15'),
(949, '1', 'Andy Windels Werk', '2023', '05', '06', '09', '30', '2023', '05', '06', '17', '00', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2023-04-23 09:05:59', '2023-04-23 09:05:59'),
(950, '1', 'Moederdag ', '2023', '05', '14', '10', '00', '2023', '05', '14', '19', '00', 'false', 'Oosteeklo', 'Moederdag vieren ', '7', '2, 4, 5, 6', '#00a65a', 'Gewijzigd', '2023-04-23 09:11:03', '2023-05-07 12:27:48'),
(952, '1', 'fam cools bijeenkomst ', '2023', '04', '23', '13', '00', '2023', '04', '23', '14', '00', 'false', 'Achel', 'We gaan iets meedelen en bespreken.', '4', '2, 3, 7, 8', '#00a65a', 'Toegevoegd', '2023-04-23 12:12:56', '2023-04-23 12:12:56'),
(954, '1', 'Andy Windels Werk', '2023', '05', '08', '09', '00', '2023', '05', '08', '18', '00', 'false', 'Oploo, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2023-05-01 06:50:02', '2023-05-01 06:50:02'),
(955, '1', 'Andy Windels Werk', '2023', '05', '09', '09', '00', '2023', '05', '09', '18', '00', 'false', 'Oploo, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2023-05-01 06:50:40', '2023-05-01 06:50:40'),
(956, '1', 'Andy Windels Werk', '2023', '05', '12', '09', '00', '2023', '05', '12', '18', '00', 'false', 'Oploo, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2023-05-01 06:51:38', '2023-05-01 06:51:38'),
(957, '1', 'Andy Windels Werk', '2023', '05', '13', '09', '30', '2023', '05', '13', '17', '00', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2023-05-01 06:52:17', '2023-05-01 06:52:17'),
(958, '1', 'Andy Windels Werk', '2023', '05', '15', '09', '00', '2023', '05', '15', '18', '00', 'false', 'Oploo, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2023-05-01 06:53:17', '2023-05-01 06:53:17'),
(959, '1', 'Andy Windels Werk', '2023', '05', '16', '09', '00', '2023', '05', '16', '18', '00', 'false', 'Oploo, Nederland', ' ', '2', '', '#ffc107', 'Gewijzigd', '2023-05-01 06:53:58', '2023-05-08 08:47:39'),
(960, '1', 'Andy Windels Werk', '2023', '05', '19', '09', '00', '2023', '05', '19', '20', '00', 'false', 'Oploo, Nederland', ' ', '2', '', '#ffc107', 'Gewijzigd', '2023-05-01 06:55:15', '2023-05-08 08:48:44'),
(961, '1', 'Andy Windels Werk', '2023', '05', '20', '09', '30', '2023', '05', '20', '17', '00', 'false', 'Oploo, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2023-05-01 06:56:32', '2023-05-01 06:56:32'),
(962, '1', 'Vakantie Andy henri', '2023', '06', '18', '00', '00', '2023', '07', '03', '00', '00', 'true', 'Achel, Duitsland ', 'Vakantie 1 week van 18 tot 25 juni in Duitsland 2 week gewoon thuis ', '2', '2', '#00a65a', 'Gewijzigd', '2023-05-01 07:00:36', '2023-06-08 14:03:48'),
(963, '1', 'Notaris ', '2023', '06', '26', '15', '00', '2023', '06', '26', '16', '30', 'false', 'Pelt', 'Akte teken voor aankoop woning Henri en Andy', '2', '2, 3', '#00a65a', 'Toegevoegd', '2023-05-01 07:03:24', '2023-05-01 07:03:24'),
(964, '2', 'Henri Cools Werk', '2023', '05', '08', '08', '00', '2023', '05', '08', '17', '00', 'false', 'Valkenswaard', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-05-07 12:28:55', '2023-05-07 12:28:55'),
(965, '2', 'Henri Cools Werk', '2023', '05', '11', '08', '00', '2023', '05', '11', '17', '00', 'false', 'Valkenswaard', ' ', '2', '', '#007bff', 'Gewijzigd', '2023-05-07 12:29:51', '2023-05-07 12:44:52'),
(966, '2', 'Henri Cools Werk', '2023', '05', '09', '08', '00', '2023', '05', '09', '13', '00', 'false', 'Valkenswaard', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-05-07 12:31:10', '2023-05-07 12:31:10'),
(967, '2', 'Henri Cools Werk', '2023', '05', '12', '08', '00', '2023', '05', '12', '17', '00', 'false', 'Valkenswaard', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-05-07 12:32:37', '2023-05-07 12:32:37'),
(968, '2', 'Henri Cools Werk', '2023', '05', '15', '08', '00', '2023', '05', '15', '17', '00', 'false', 'Valkenswaard', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-05-07 12:33:56', '2023-05-07 12:33:56'),
(969, '2', 'Henri Cools Werk', '2023', '05', '17', '08', '00', '2023', '05', '17', '17', '00', 'false', 'Valkenswaard', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-05-07 12:35:10', '2023-05-07 12:35:10'),
(970, '2', 'Henri Cools Werk', '2023', '05', '20', '08', '00', '2023', '05', '20', '17', '00', 'false', 'Valkenswaard', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-05-07 12:36:32', '2023-05-07 12:36:32'),
(971, '2', 'Henri Cools Werk', '2023', '05', '19', '08', '00', '2023', '05', '19', '17', '00', 'false', 'Valkenswaard', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-05-07 12:37:16', '2023-05-07 12:37:16'),
(972, '2', 'Henri Cools Werk', '2023', '05', '22', '08', '00', '2023', '05', '22', '17', '00', 'false', 'Valkenswaard', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-05-07 12:38:26', '2023-05-07 12:38:26'),
(973, '2', 'Henri Cools Werk', '2023', '05', '23', '08', '00', '2023', '05', '23', '17', '00', 'false', 'Valkenswaard', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-05-07 12:39:08', '2023-05-07 12:39:08'),
(974, '2', 'Henri Cools Werk', '2023', '05', '26', '08', '00', '2023', '05', '26', '17', '00', 'false', 'Valkenswaard', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-05-07 12:40:32', '2023-05-07 12:40:32'),
(975, '2', 'Henri Cools Werk', '2023', '05', '27', '09', '00', '2023', '05', '27', '18', '15', 'false', 'Valkenswaard', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-05-07 12:41:59', '2023-05-07 12:41:59'),
(976, '1', 'moederdag ', '2023', '05', '18', '14', '00', '2023', '05', '18', '17', '00', 'false', 'pelt', 'Moederdag en verjaardag paps vieren.', '2', '2, 3', '#ffc107', 'Toegevoegd', '2023-05-11 14:22:48', '2023-05-11 14:22:48'),
(977, '1', 'dvv', '2023', '05', '15', '20', '00', '2023', '05', '15', '21', '00', 'false', 'Hamont-achel', 'Invullen belasting brief + woning verzekering overzetten.', '2', '2', '#ffc107', 'Toegevoegd', '2023-05-11 17:33:43', '2023-05-11 17:33:43'),
(978, '3', 'Martha Sturmans Activiteit', '2023', '05', '16', '09', '00', '2023', '05', '16', '12', '00', 'false', '', 'moeilijk bereikbaar, weet niet hoelaat ze bellen voor invullen belastingaangifte', '2', '', '#17a2b8', 'Toegevoegd', '2023-05-15 18:26:47', '2023-05-15 18:26:47'),
(979, '3', 'Martha Sturmans Activiteit', '2023', '05', '16', '09', '00', '2023', '05', '16', '12', '00', 'false', '', 'moeilijk bereikbaar, weet niet hoelaat ze bellen voor invullen belastingaangifte', '2', '', '#17a2b8', 'Toegevoegd', '2023-05-15 18:26:53', '2023-05-15 18:26:53'),
(980, '2', 'Henri Cools Werk', '2023', '05', '31', '12', '00', '2023', '05', '31', '18', '15', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-05-17 17:27:31', '2023-05-17 17:27:31'),
(981, '2', 'Henri Cools Werk', '2023', '06', '01', '08', '00', '2023', '06', '01', '17', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-05-18 07:32:28', '2023-05-18 07:32:28'),
(982, '1', 'Andy Windels Werk', '2023', '05', '22', '09', '00', '2023', '05', '22', '17', '00', 'false', 'Oploo, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2023-05-19 06:44:31', '2023-05-19 06:44:31'),
(983, '1', 'Andy Windels Werk', '2023', '05', '23', '09', '00', '2023', '05', '23', '18', '00', 'false', 'Oploo, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2023-05-19 06:45:41', '2023-05-19 06:45:41'),
(984, '2', 'Henri Cools Werk', '2023', '06', '02', '08', '00', '2023', '06', '02', '17', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-05-19 06:46:09', '2023-05-19 06:46:09');
INSERT INTO `evenement` (`id`, `userid`, `title`, `bjaar`, `bmaand`, `bdag`, `buur`, `bmin`, `ejaar`, `emaand`, `edag`, `euur`, `emin`, `fullday`, `locatie`, `text`, `groupsen`, `usersids`, `color`, `status`, `date`, `last_date`) VALUES
(985, '1', 'Andy Windels Werk', '2023', '05', '24', '09', '00', '2023', '05', '24', '18', '00', 'false', 'Oploo, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2023-05-19 06:46:32', '2023-05-19 06:46:32'),
(986, '1', 'Andy Windels Werk', '2023', '05', '25', '09', '00', '2023', '05', '25', '18', '00', 'false', 'Oploo, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2023-05-19 06:47:20', '2023-05-19 06:47:20'),
(987, '2', 'Henri Cools Werk', '2023', '06', '03', '08', '00', '2023', '06', '03', '17', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Gewijzigd', '2023-05-19 06:47:31', '2023-06-02 21:53:27'),
(988, '1', 'Andy Windels Werk', '2023', '05', '27', '09', '30', '2023', '05', '27', '17', '00', 'false', 'Oploo, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2023-05-19 06:48:17', '2023-05-19 06:48:17'),
(989, '1', 'Andy Windels Werk', '2023', '05', '27', '09', '30', '2023', '05', '27', '17', '00', 'false', 'Oploo, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2023-05-19 06:54:12', '2023-05-19 06:54:12'),
(990, '1', 'Bellen paesen. ', '2023', '05', '26', '10', '00', '2023', '05', '26', '12', '00', 'false', 'Achel', 'Bellen voor zonnepanelen ', '', '1', '#00a65a', 'Toegevoegd', '2023-05-19 06:57:04', '2023-05-19 06:57:04'),
(991, '1', 'Andy Windels Werk', '2023', '05', '29', '12', '00', '2023', '05', '29', '17', '00', 'false', 'Oploo, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2023-05-21 21:58:52', '2023-05-21 21:58:52'),
(992, '1', 'Andy Windels Werk', '2023', '05', '30', '09', '00', '2023', '05', '30', '18', '00', 'false', 'Oploo, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2023-05-21 21:59:33', '2023-05-21 21:59:33'),
(993, '1', 'Andy Windels Werk', '2023', '06', '01', '09', '00', '2023', '06', '01', '18', '00', 'false', 'Oploo, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2023-05-21 22:00:09', '2023-05-21 22:00:09'),
(994, '1', 'Andy Windels Werk', '2023', '06', '02', '09', '00', '2023', '06', '02', '18', '00', 'false', 'Oploo, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2023-05-21 22:05:37', '2023-05-21 22:05:37'),
(995, '1', 'Andy Windels Werk', '2023', '06', '03', '09', '30', '2023', '06', '03', '17', '00', 'false', 'Oploo, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2023-05-21 22:06:26', '2023-05-21 22:06:26'),
(996, '1', 'Andy Windels Werk', '2023', '06', '05', '09', '00', '2023', '06', '05', '18', '00', 'false', 'Oploo, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2023-05-21 22:07:39', '2023-05-21 22:07:39'),
(997, '1', 'Andy Windels Werk', '2023', '06', '06', '09', '00', '2023', '06', '06', '18', '00', 'false', 'Oploo, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2023-05-21 22:08:08', '2023-05-21 22:08:08'),
(999, '1', 'Andy Windels Werk', '2023', '06', '09', '09', '00', '2023', '06', '09', '20', '00', 'false', 'Oploo, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2023-05-21 22:09:24', '2023-05-21 22:09:24'),
(1000, '1', 'Andy Windels Werk', '2023', '06', '10', '09', '30', '2023', '06', '10', '17', '00', 'false', 'Oploo, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2023-05-21 22:10:09', '2023-05-21 22:10:09'),
(1001, '1', 'Andy Windels Werk', '2023', '06', '12', '09', '00', '2023', '06', '12', '18', '00', 'false', 'Oploo, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2023-05-21 22:11:02', '2023-05-21 22:11:02'),
(1002, '1', 'Andy Windels Werk', '2023', '06', '13', '09', '00', '2023', '06', '13', '18', '00', 'false', 'Oploo, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2023-05-21 22:11:42', '2023-05-21 22:11:42'),
(1003, '1', 'Andy Windels Werk', '2023', '06', '15', '09', '00', '2023', '06', '15', '18', '00', 'false', 'Oploo, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2023-05-21 22:12:14', '2023-05-21 22:12:14'),
(1004, '1', 'Andy Windels Werk', '2023', '06', '16', '09', '00', '2023', '06', '16', '18', '00', 'false', 'Oploo, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2023-05-21 22:12:43', '2023-05-21 22:12:43'),
(1005, '1', 'Andy Windels Werk', '2023', '06', '17', '09', '30', '2023', '06', '17', '17', '00', 'false', 'Oploo, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2023-05-21 22:13:18', '2023-05-21 22:13:18'),
(1006, '1', 'Ludwig Dvv hamont', '2023', '06', '26', '19', '30', '2023', '06', '26', '21', '00', 'false', 'Hamont-Achel ', 'Info sparen en beleggen. ', '2', '2', '#00a65a', 'Toegevoegd', '2023-05-22 22:07:07', '2023-05-22 22:07:07'),
(1007, '2', 'Henri Cools Werk', '2023', '06', '06', '08', '00', '2023', '06', '06', '17', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-05-26 19:40:33', '2023-05-26 19:40:33'),
(1008, '2', 'Henri Cools Werk', '2023', '06', '07', '12', '00', '2023', '06', '07', '18', '15', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-05-26 19:41:29', '2023-05-26 19:41:29'),
(1009, '2', 'Henri Cools Werk', '2023', '06', '09', '08', '00', '2023', '06', '09', '17', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-05-26 19:43:22', '2023-05-26 19:43:22'),
(1010, '2', 'Henri Cools Werk', '2023', '06', '10', '09', '00', '2023', '06', '10', '18', '15', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-05-26 19:44:16', '2023-05-26 19:44:16'),
(1011, '2', 'Henri Cools Werk', '2023', '06', '12', '08', '00', '2023', '06', '12', '17', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-05-26 19:46:00', '2023-05-26 19:46:00'),
(1012, '2', 'Henri Cools Werk', '2023', '06', '13', '13', '00', '2023', '06', '13', '18', '15', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-05-26 19:47:03', '2023-05-26 19:47:03'),
(1013, '2', 'Henri Cools Werk', '2023', '06', '14', '08', '00', '2023', '06', '14', '17', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-05-26 19:47:57', '2023-05-26 19:47:57'),
(1014, '2', 'Henri Cools Werk', '2023', '06', '17', '08', '00', '2023', '06', '17', '17', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-05-26 19:49:05', '2023-05-26 19:49:05'),
(1015, '1', 'Vaderdag ouders andy', '2023', '06', '11', '10', '30', '2023', '06', '11', '19', '00', 'false', 'Oosteeklo', 'Vaderdag vieren met etentje in Eeklo\r\n', '2', '1, 2', '#00a65a', 'Toegevoegd', '2023-06-08 14:02:20', '2023-06-08 14:02:20'),
(1016, '1', 'Gemeentehuis hamont', '2023', '09', '04', '10', '00', '2023', '09', '04', '11', '00', 'false', 'Hamont', 'Rijbewijs en paspoort vervangen bij gemeentehuis', '2', '1, 2', '#00a65a', 'Toegevoegd', '2023-06-08 21:19:49', '2023-06-08 21:19:49'),
(1017, '2', 'Henri Cools Werk', '2023', '07', '04', '08', '00', '2023', '07', '04', '17', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-06-25 20:49:26', '2023-06-25 20:49:26'),
(1018, '2', 'Henri Cools Werk', '2023', '07', '05', '08', '00', '2023', '07', '05', '17', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-06-25 20:50:12', '2023-06-25 20:50:12'),
(1019, '2', 'Henri Cools Werk', '2023', '07', '07', '08', '00', '2023', '07', '07', '17', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-06-25 20:51:00', '2023-06-25 20:51:00'),
(1020, '2', 'Henri Cools Werk', '2023', '07', '08', '09', '00', '2023', '07', '08', '18', '15', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-06-25 20:51:38', '2023-06-25 20:51:38'),
(1021, '2', 'Henri Cools Werk', '2023', '07', '12', '08', '00', '2023', '07', '12', '17', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-06-25 20:52:51', '2023-06-25 20:52:51'),
(1022, '2', 'Henri Cools Werk', '2023', '07', '13', '08', '00', '2023', '07', '13', '17', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-06-25 20:53:45', '2023-06-25 20:53:45'),
(1023, '2', 'Henri Cools Werk', '2023', '07', '14', '08', '00', '2023', '07', '14', '17', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-06-25 20:54:23', '2023-06-25 20:54:23'),
(1024, '2', 'Henri Cools Werk', '2023', '07', '15', '08', '00', '2023', '07', '15', '17', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-06-25 20:54:57', '2023-06-25 20:54:57'),
(1025, '2', 'Henri Cools Werk', '2023', '07', '17', '12', '00', '2023', '07', '17', '18', '15', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-06-25 20:56:40', '2023-06-25 20:56:40'),
(1026, '2', 'Henri Cools Werk', '2023', '07', '18', '08', '00', '2023', '07', '18', '17', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-06-25 20:57:38', '2023-06-25 20:57:38'),
(1027, '2', 'Henri Cools Werk', '2023', '07', '20', '12', '00', '2023', '07', '20', '18', '15', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-06-25 20:58:50', '2023-06-25 20:58:50'),
(1029, '2', 'Henri Cools Werk', '2023', '07', '22', '09', '00', '2023', '07', '22', '18', '15', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-06-25 21:00:33', '2023-06-25 21:00:33'),
(1030, '1', 'Andy Windels Werk', '2023', '07', '03', '09', '00', '2023', '07', '03', '18', '00', 'false', 'Oploo, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2023-05-21 22:12:43', '2023-05-21 22:12:43'),
(1031, '1', 'Andy Windels Werk', '2023', '07', '04', '09', '00', '2023', '07', '04', '18', '00', 'false', 'Oploo, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2023-05-21 22:12:14', '2023-05-21 22:12:14'),
(1032, '1', 'Andy Windels Werk', '2023', '07', '06', '09', '00', '2023', '07', '06', '18', '00', 'false', 'Oploo, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2023-05-21 22:11:42', '2023-05-21 22:11:42'),
(1033, '1', 'Andy Windels Werk', '2023', '07', '07', '09', '00', '2023', '07', '07', '18', '00', 'false', 'Oploo, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2023-05-21 22:11:02', '2023-05-21 22:11:02'),
(1034, '1', 'Andy Windels Werk', '2023', '07', '08', '09', '30', '2023', '07', '08', '17', '00', 'false', 'Oploo, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2023-05-21 22:10:09', '2023-05-21 22:10:09'),
(1035, '1', 'Andy Windels Werk', '2023', '07', '10', '09', '00', '2023', '07', '10', '18', '00', 'false', 'Oploo, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2023-07-10 06:32:41', '2023-07-10 06:32:41'),
(1036, '1', 'Andy Windels Werk', '2023', '07', '11', '09', '00', '2023', '07', '11', '18', '00', 'false', 'Oploo, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2023-07-10 06:33:29', '2023-07-10 06:33:29'),
(1037, '1', 'Andy Windels Werk', '2023', '07', '13', '09', '00', '2023', '07', '13', '18', '00', 'false', 'Oploo, Nederland', ' ', '2', '', '#ffc107', 'Gewijzigd', '2023-07-10 06:34:15', '2023-07-10 06:36:50'),
(1038, '1', 'Andy Windels Werk', '2023', '07', '14', '09', '00', '2023', '07', '14', '20', '00', 'false', 'Oploo, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2023-07-10 06:35:09', '2023-07-10 06:35:09'),
(1039, '1', 'Andy Windels Werk', '2023', '07', '15', '09', '30', '2023', '07', '15', '17', '00', 'false', 'Oploo, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2023-07-10 06:35:57', '2023-07-10 06:35:57'),
(1040, '1', 'Andy Windels Werk', '2023', '07', '17', '09', '00', '2023', '07', '17', '18', '00', 'false', 'Oploo, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2023-07-10 06:38:26', '2023-07-10 06:38:26'),
(1041, '1', 'Andy Windels Werk', '2023', '07', '18', '09', '00', '2023', '07', '18', '18', '00', 'false', 'Oploo, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2023-07-10 06:38:57', '2023-07-10 06:38:57'),
(1042, '1', 'Andy Windels Werk', '2023', '07', '20', '09', '00', '2023', '07', '20', '18', '00', 'false', 'Oploo, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2023-07-10 06:39:28', '2023-07-10 06:39:28'),
(1043, '1', 'Andy Windels Werk', '2023', '07', '21', '09', '00', '2023', '07', '21', '18', '00', 'false', 'Oploo, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2023-07-10 06:40:03', '2023-07-10 06:40:03'),
(1044, '1', 'Andy Windels Werk', '2023', '07', '22', '09', '30', '2023', '07', '22', '17', '00', 'false', 'Oploo, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2023-07-10 06:40:43', '2023-07-10 06:40:43'),
(1045, '1', 'Bqq werk ', '2023', '07', '12', '18', '00', '2023', '07', '12', '22', '00', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2023-07-10 06:43:32', '2023-07-10 06:43:32'),
(1046, '1', 'Vakantie', '2023', '09', '04', '00', '00', '2023', '09', '11', '00', '00', 'true', 'Achel', 'Weekje vakantie thuis', '2', '', '#00a65a', 'Gewijzigd', '2023-07-10 06:46:53', '2023-07-10 06:47:32'),
(1047, '1', 'Ouders Andy', '2023', '08', '06', '12', '00', '2023', '08', '06', '19', '00', 'false', 'Achel', 'Ouders Andy komen naar Achel. Viering van het huis. ', '2', '2, 4, 5, 6', '#00a65a', 'Gewijzigd', '2023-07-10 06:50:01', '2023-08-02 16:18:36'),
(1050, '2', 'Henri Cools Werk', '2023', '07', '28', '15', '00', '2023', '07', '28', '21', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Gewijzigd', '2023-07-14 18:37:48', '2023-07-23 11:32:10'),
(1051, '2', 'Henri Cools Werk', '2023', '07', '29', '08', '00', '2023', '07', '29', '18', '15', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Gewijzigd', '2023-07-14 18:39:01', '2023-07-23 11:33:11'),
(1052, '2', 'Henri Cools Werk', '2023', '07', '30', '09', '30', '2023', '07', '30', '18', '15', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-07-14 18:39:56', '2023-07-14 18:39:56'),
(1053, '2', 'Henri Cools Werk', '2023', '07', '31', '08', '00', '2023', '07', '31', '18', '15', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-07-14 18:42:43', '2023-07-14 18:42:43'),
(1054, '2', 'Henri Cools Werk', '2023', '08', '01', '12', '00', '2023', '08', '01', '18', '15', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-07-14 18:43:42', '2023-07-14 18:43:42'),
(1055, '2', 'Henri Cools Werk', '2023', '08', '03', '10', '00', '2023', '08', '03', '18', '15', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-07-14 18:44:59', '2023-07-14 18:44:59'),
(1056, '2', 'Henri Cools Werk', '2023', '08', '04', '08', '00', '2023', '08', '04', '17', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-07-14 18:45:42', '2023-07-14 18:45:42'),
(1057, '2', 'Henri Cools Werk', '2023', '08', '05', '08', '00', '2023', '08', '05', '17', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-07-14 18:46:37', '2023-07-14 18:46:37'),
(1058, '2', 'Henri Cools Werk', '2023', '08', '05', '08', '00', '2023', '08', '05', '17', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-07-14 18:47:31', '2023-07-14 18:47:31'),
(1059, '2', 'Henri Cools Werk', '2023', '07', '19', '08', '00', '2023', '07', '19', '17', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-07-14 18:49:36', '2023-07-14 18:49:36'),
(1060, '2', 'Henri Cools Werk', '2023', '07', '24', '12', '00', '2023', '07', '24', '18', '15', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-07-23 11:30:16', '2023-07-23 11:30:16'),
(1061, '2', 'Henri Cools Werk', '2023', '07', '26', '08', '00', '2023', '07', '26', '14', '30', 'false', 'Valkenswaard, Nederland', 'Extra uren ', '2', '', '#007bff', 'Toegevoegd', '2023-07-26 06:41:02', '2023-07-26 06:41:02'),
(1062, '2', 'Henri Cools Werk', '2023', '08', '08', '13', '00', '2023', '08', '08', '18', '15', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-07-26 06:43:24', '2023-07-26 06:43:24'),
(1063, '2', 'Henri Cools Werk', '2023', '08', '09', '08', '00', '2023', '08', '09', '18', '15', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-07-26 06:44:33', '2023-07-26 06:44:33'),
(1064, '2', 'Henri Cools Werk', '2023', '08', '11', '08', '00', '2023', '08', '11', '17', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-07-26 06:45:20', '2023-07-26 06:45:20'),
(1065, '2', 'Henri Cools Werk', '2023', '08', '12', '08', '00', '2023', '08', '12', '18', '15', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-07-26 06:46:17', '2023-07-26 06:46:17'),
(1066, '2', 'Henri Cools Werk', '2023', '08', '15', '08', '00', '2023', '08', '15', '18', '15', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-08-08 07:53:33', '2023-08-08 07:53:33'),
(1067, '2', 'Henri Cools Werk', '2023', '08', '16', '08', '00', '2023', '08', '16', '18', '15', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-08-08 07:54:20', '2023-08-08 07:54:20'),
(1068, '2', 'Henri Cools Werk', '2023', '08', '19', '08', '00', '2023', '08', '19', '18', '15', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-08-08 07:55:33', '2023-08-08 07:55:33'),
(1069, '2', 'Henri Cools Werk', '2023', '08', '22', '12', '00', '2023', '08', '22', '18', '15', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-08-08 07:57:38', '2023-08-08 07:57:38'),
(1070, '2', 'Henri Cools Werk', '2023', '08', '23', '12', '00', '2023', '08', '23', '18', '15', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-08-08 07:58:32', '2023-08-08 07:58:32'),
(1071, '2', 'Henri Cools Werk', '2023', '08', '24', '08', '00', '2023', '08', '24', '17', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-08-08 07:59:29', '2023-08-08 07:59:29'),
(1072, '2', 'Henri Cools Werk', '2023', '08', '26', '08', '00', '2023', '08', '26', '18', '15', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-08-08 08:01:03', '2023-08-08 08:01:03'),
(1073, '2', 'Henri Cools Werk', '2023', '09', '25', '08', '00', '2023', '09', '25', '17', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-09-19 20:52:36', '2023-09-19 20:52:36'),
(1074, '2', 'Henri Cools Werk', '2023', '09', '26', '11', '00', '2023', '09', '26', '17', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-09-19 20:53:52', '2023-09-19 20:53:52'),
(1075, '2', 'Henri Cools Werk', '2023', '09', '29', '17', '00', '2023', '09', '29', '21', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-09-19 20:55:22', '2023-09-19 20:55:22'),
(1076, '2', 'Henri Cools Werk', '2023', '09', '30', '13', '00', '2023', '09', '30', '18', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-09-19 20:57:28', '2023-09-19 20:57:28'),
(1078, '2', 'Henri Cools Werk', '2023', '10', '02', '08', '00', '2023', '10', '02', '17', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-09-19 20:59:51', '2023-09-19 20:59:51'),
(1079, '2', 'Henri Cools Werk', '2023', '10', '03', '08', '00', '2023', '10', '03', '17', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-09-19 21:00:36', '2023-09-19 21:00:36'),
(1080, '2', 'Henri Cools Werk', '2023', '10', '04', '08', '00', '2023', '10', '04', '17', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-09-19 21:01:33', '2023-09-19 21:01:33'),
(1081, '2', 'Henri Cools Werk', '2023', '10', '06', '17', '00', '2023', '10', '06', '21', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-09-19 21:02:55', '2023-09-19 21:02:55'),
(1082, '2', 'Henri Cools Werk', '2023', '10', '07', '09', '00', '2023', '10', '07', '17', '00', 'false', 'Valkenswaard, Nederland', 'Curcus BHV ', '2', '', '#007bff', 'Toegevoegd', '2023-09-19 21:04:27', '2023-09-19 21:04:27'),
(1083, '2', 'Henri Cools Werk', '2023', '09', '18', '15', '00', '2023', '09', '18', '20', '30', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-09-19 21:06:17', '2023-09-19 21:06:17'),
(1084, '2', 'Henri Cools Werk', '2023', '09', '19', '08', '00', '2023', '09', '19', '13', '15', 'false', 'Valkenswaard, Nederland', '  ', '2', '', '#007bff', 'Toegevoegd', '2023-09-19 21:07:10', '2023-09-19 21:07:10'),
(1085, '2', 'Henri Cools Werk', '2023', '09', '20', '08', '00', '2023', '09', '20', '17', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-09-19 21:08:04', '2023-09-19 21:08:04'),
(1087, '1', 'Henri Cools Werk', '2023', '09', '21', '10', '00', '2023', '09', '21', '18', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Gewijzigd', '2023-09-19 21:09:11', '2023-09-20 22:26:29'),
(1088, '2', 'Henri Cools Werk', '2023', '09', '22', '15', '00', '2023', '09', '22', '21', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-09-19 21:10:19', '2023-09-19 21:10:19'),
(1089, '2', 'Henri Cools Werk', '2023', '09', '24', '09', '30', '2023', '09', '24', '18', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-09-19 21:11:09', '2023-09-19 21:11:09'),
(1091, '2', 'Begrafenis ', '2023', '09', '27', '11', '00', '2023', '09', '27', '14', '00', 'false', '', 'Begrafenis opa Andy ', '', '', '#00a65a', 'Toegevoegd', '2023-09-19 21:14:40', '2023-09-19 21:14:40'),
(1092, '1', 'Andy Windels Werk', '2023', '09', '21', '09', '00', '2023', '09', '21', '18', '00', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2023-09-20 22:29:14', '2023-09-20 22:29:14'),
(1093, '1', 'Andy Windels Werk', '2023', '09', '22', '09', '00', '2023', '09', '22', '18', '00', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2023-09-20 22:30:13', '2023-09-20 22:30:13'),
(1094, '1', 'Andy Windels Werk', '2023', '09', '23', '09', '30', '2023', '09', '23', '17', '00', 'false', 'Oploo, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2023-09-20 22:31:29', '2023-09-20 22:31:29'),
(1095, '1', 'Andy Windels Werk', '2023', '09', '25', '09', '00', '2023', '09', '25', '18', '00', 'false', 'Oploo, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2023-09-20 22:32:42', '2023-09-20 22:32:42'),
(1096, '1', 'Andy Windels Werk', '2023', '09', '28', '09', '00', '2023', '09', '28', '18', '00', 'false', 'Oploo, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2023-09-20 22:33:43', '2023-09-20 22:33:43'),
(1097, '1', 'Andy Windels Werk', '2023', '09', '29', '09', '00', '2023', '09', '29', '18', '00', 'false', 'Oploo, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2023-09-20 22:34:21', '2023-09-20 22:34:21'),
(1098, '1', 'Andy Windels Werk', '2023', '09', '30', '09', '30', '2023', '09', '30', '17', '00', 'false', 'Oploo, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2023-09-20 22:34:56', '2023-09-20 22:34:56'),
(1099, '1', 'Andy Windels Werk', '2023', '10', '02', '09', '00', '2023', '10', '02', '18', '00', 'false', 'Oploo, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2023-09-20 22:36:03', '2023-09-20 22:36:03'),
(1100, '1', 'Andy Windels Werk', '2023', '10', '03', '09', '00', '2023', '10', '03', '18', '00', 'false', 'Oploo, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2023-09-20 22:36:46', '2023-09-20 22:36:46'),
(1101, '1', 'Andy Windels Werk', '2023', '10', '05', '09', '00', '2023', '10', '05', '18', '00', 'false', 'Oploo, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2023-09-20 22:37:26', '2023-09-20 22:37:26'),
(1102, '1', 'Andy Windels Werk', '2023', '10', '06', '09', '00', '2023', '10', '06', '18', '00', 'false', 'Oploo, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2023-09-20 22:38:06', '2023-09-20 22:38:06'),
(1103, '1', 'Andy Windels Werk', '2023', '10', '07', '09', '30', '2023', '10', '07', '17', '00', 'false', 'Oploo, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2023-09-20 22:38:51', '2023-09-20 22:38:51'),
(1104, '1', 'Begrafenis Peter Andy', '2023', '09', '27', '11', '00', '2023', '09', '27', '17', '00', 'false', 'Lievegem ', 'Begrafenis van de vader van andy vader ', '2, 5', '2, 4, 5, 6', '#00a65a', 'Gewijzigd', '2023-09-20 22:42:31', '2023-09-20 22:44:15'),
(1105, '1', 'Andy Windels Werk', '2023', '09', '26', '09', '00', '2023', '09', '26', '17', '00', 'false', 'Oploo, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2023-09-20 22:48:23', '2023-09-20 22:48:23'),
(1106, '2', 'Henri Cools Werk', '2023', '10', '09', '13', '00', '2023', '10', '09', '18', '30', 'true', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Gewijzigd', '2023-10-06 09:22:12', '2023-10-06 09:23:40'),
(1107, '2', 'Henri Cools Werk', '2023', '10', '10', '08', '00', '2023', '10', '10', '17', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-10-06 09:24:37', '2023-10-06 09:24:37'),
(1108, '2', 'Henri Cools Werk', '2023', '10', '11', '12', '00', '2023', '10', '11', '17', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-10-06 09:25:23', '2023-10-06 09:25:23'),
(1109, '2', 'Henri Cools Werk', '2023', '10', '13', '16', '00', '2023', '10', '13', '21', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Gewijzigd', '2023-10-06 09:26:28', '2023-10-11 17:37:48'),
(1110, '2', 'Henri Cools Werk', '2023', '10', '14', '08', '00', '2023', '10', '14', '18', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-10-06 09:27:39', '2023-10-06 09:27:39'),
(1111, '2', 'Henri Cools Werk', '2023', '10', '16', '12', '00', '2023', '10', '16', '18', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-10-06 09:30:22', '2023-10-06 09:30:22'),
(1112, '2', 'Henri Cools Werk', '2023', '10', '18', '08', '00', '2023', '10', '18', '17', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-10-06 09:31:06', '2023-10-06 09:31:06'),
(1113, '2', 'Henri Cools Werk', '2023', '10', '19', '08', '00', '2023', '10', '19', '17', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-10-06 09:31:53', '2023-10-06 09:31:53'),
(1114, '2', 'Henri Cools Werk', '2023', '10', '20', '13', '00', '2023', '10', '20', '21', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-10-06 09:32:46', '2023-10-06 09:32:46'),
(1115, '2', 'Henri Cools Werk', '2023', '10', '12', '08', '00', '2023', '10', '12', '13', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-10-11 17:37:05', '2023-10-11 17:37:05'),
(1116, '2', 'Henri Cools Werk', '2023', '10', '30', '10', '00', '2023', '10', '30', '18', '15', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-10-28 22:17:00', '2023-10-28 22:17:00'),
(1117, '2', 'Henri Cools Werk', '2023', '10', '31', '13', '00', '2023', '10', '31', '21', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-10-28 22:17:44', '2023-10-28 22:17:44'),
(1118, '2', 'Henri Cools Werk', '2023', '11', '01', '12', '00', '2023', '11', '01', '18', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-10-28 22:18:59', '2023-10-28 22:18:59'),
(1119, '2', 'Henri Cools Werk', '2023', '11', '02', '13', '00', '2023', '11', '02', '21', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-10-28 22:19:53', '2023-10-28 22:19:53'),
(1120, '2', 'Henri Cools Werk', '2023', '11', '06', '12', '00', '2023', '11', '06', '18', '15', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-10-28 22:21:31', '2023-10-28 22:21:31'),
(1121, '2', 'Henri Cools Werk', '2023', '11', '08', '10', '00', '2023', '11', '08', '17', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-10-28 22:22:55', '2023-10-28 22:22:55'),
(1122, '2', 'Henri Cools Werk', '2023', '11', '10', '13', '00', '2023', '11', '10', '21', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-10-28 22:24:11', '2023-10-28 22:24:11'),
(1123, '2', 'Henri Cools Werk', '2023', '11', '12', '09', '30', '2023', '11', '12', '18', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-10-28 22:25:51', '2023-10-28 22:25:51'),
(1124, '2', 'Henri Cools Werk', '2023', '11', '13', '12', '00', '2023', '11', '13', '18', '15', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-10-28 22:27:02', '2023-10-28 22:27:02'),
(1125, '2', 'Henri Cools Werk', '2023', '11', '16', '09', '00', '2023', '11', '16', '17', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-10-28 22:28:20', '2023-10-28 22:28:20'),
(1126, '2', 'Henri Cools Werk', '2023', '11', '17', '13', '00', '2023', '11', '17', '21', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-10-28 22:28:58', '2023-10-28 22:28:58'),
(1127, '2', 'Henri Cools Werk', '2023', '11', '19', '09', '30', '2023', '11', '19', '18', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-10-28 22:29:44', '2023-10-28 22:29:44'),
(1128, '1', 'Andy Windels Werk', '2023', '11', '01', '09', '00', '2023', '11', '01', '18', '00', 'false', 'Oploo, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2023-11-01 06:36:01', '2023-11-01 06:36:01'),
(1129, '1', 'Andy Windels Werk', '2023', '11', '02', '09', '00', '2023', '11', '02', '18', '00', 'false', 'Oploo, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2023-11-01 06:36:52', '2023-11-01 06:36:52'),
(1130, '1', 'Metejoor + weekend ouders', '2023', '11', '03', '10', '00', '2023', '11', '04', '20', '00', 'false', 'Antwerpen', ' ', '7', '1, 2, 5', '#00a65a', 'Toegevoegd', '2023-11-01 06:38:30', '2023-11-01 06:38:30'),
(1131, '1', 'Andy Windels Werk', '2023', '11', '06', '09', '00', '2023', '11', '06', '18', '00', 'false', 'Oploo, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2023-11-01 06:39:25', '2023-11-01 06:39:25'),
(1132, '1', 'Andy Windels Werk', '2023', '11', '07', '09', '00', '2023', '11', '07', '18', '00', 'false', 'Oploo, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2023-11-01 06:40:00', '2023-11-01 06:40:00'),
(1133, '1', 'Andy Windels Werk', '2023', '11', '09', '09', '00', '2023', '11', '09', '18', '00', 'false', 'Oploo, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2023-11-01 06:40:30', '2023-11-01 06:40:30'),
(1134, '1', 'Andy Windels Werk', '2023', '11', '10', '09', '00', '2023', '11', '10', '18', '00', 'false', 'Oploo, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2023-11-01 06:41:02', '2023-11-01 06:41:02'),
(1135, '1', 'Andy Windels Werk', '2023', '11', '11', '09', '30', '2023', '11', '11', '17', '00', 'false', 'Oploo, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2023-11-01 06:42:16', '2023-11-01 06:42:16'),
(1136, '1', 'Andy Windels Werk', '2023', '11', '13', '09', '00', '2023', '11', '13', '18', '00', 'false', 'Oploo, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2023-11-01 06:43:24', '2023-11-01 06:43:24'),
(1137, '1', 'Vakantie Andy ', '2023', '11', '14', '00', '00', '2023', '12', '03', '23', '59', 'false', 'Achel', ' ', '2', '1', '#00a65a', 'Gewijzigd', '2023-11-01 06:44:39', '2023-11-23 16:55:27'),
(1138, '1', 'Gesprek 2 action', '2023', '11', '16', '11', '00', '2023', '11', '16', '11', '30', 'false', 'Aalst waarle Nederland ', 'Gesprek over de dagen en contract opmaak', '2', '', '#00a65a', 'Toegevoegd', '2023-11-14 19:16:53', '2023-11-14 19:16:53'),
(1139, '1', 'Werkoverleg ouders andy', '2023', '11', '17', '11', '00', '2023', '11', '17', '20', '00', 'false', 'Oosteeklo', 'Werk overleg alleen Andy gaat naar ouders. ', '2, 3', '', '#00a65a', 'Gewijzigd', '2023-11-14 19:18:44', '2023-11-23 16:57:03'),
(1140, '1', 'Andy Windels Werk', '2023', '12', '04', '09', '00', '2023', '12', '04', '18', '00', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2023-11-23 16:48:14', '2023-11-23 16:48:14'),
(1141, '1', 'Andy Windels Werk', '2023', '12', '05', '09', '00', '2023', '12', '05', '18', '00', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2023-11-23 16:49:20', '2023-11-23 16:49:20'),
(1142, '1', 'Andy Windels Werk', '2023', '12', '06', '11', '30', '2023', '12', '06', '20', '15', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2023-11-23 16:50:12', '2023-11-23 16:50:12'),
(1143, '1', 'Garage Kia Auto Andy', '2023', '11', '24', '14', '30', '2023', '11', '24', '17', '30', 'false', 'Pelt', 'Na zicht keuring plus nieuwe remschijven en banden. ', '', '', '#00a65a', 'Toegevoegd', '2023-11-23 16:52:04', '2023-11-23 16:52:04'),
(1144, '1', 'Ouder andy', '2023', '12', '03', '11', '00', '2023', '12', '03', '19', '00', 'false', 'Oosteeklo', 'Verjaardag vader en henri vieren. ', '2, 7', '1, 2, 4, 5, 6', '#00a65a', 'Toegevoegd', '2023-11-23 16:53:49', '2023-11-23 16:53:49'),
(1145, '2', 'Henri Cools Werk', '2023', '12', '05', '13', '00', '2023', '12', '05', '19', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-12-03 10:24:18', '2023-12-03 10:24:18'),
(1146, '2', 'Henri Cools Werk', '2023', '12', '06', '08', '00', '2023', '12', '06', '17', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-12-03 10:25:01', '2023-12-03 10:25:01'),
(1147, '2', 'Henri Cools Werk', '2023', '12', '07', '17', '00', '2023', '12', '07', '21', '00', 'false', 'Valkenswaard, Nederland', 'Gewijzigd ', '2', '', '#007bff', 'Toegevoegd', '2023-12-03 10:26:04', '2023-12-03 10:26:04'),
(1148, '2', 'Henri Cools Werk', '2023', '12', '08', '12', '00', '2023', '12', '08', '17', '00', 'false', 'Valkenswaard, Nederland', 'Gewijzigd ', '2', '', '#007bff', 'Toegevoegd', '2023-12-03 10:27:14', '2023-12-03 10:27:14'),
(1149, '2', 'Henri Cools Werk', '2023', '12', '09', '08', '00', '2023', '12', '09', '13', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-12-03 10:28:03', '2023-12-03 10:28:03'),
(1150, '2', 'Henri Cools Werk', '2023', '12', '12', '08', '00', '2023', '12', '12', '13', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-12-03 10:29:22', '2023-12-03 10:29:22'),
(1151, '2', 'Henri Cools Werk', '2023', '12', '13', '08', '00', '2023', '12', '13', '17', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-12-03 10:30:10', '2023-12-03 10:30:10'),
(1152, '2', 'Henri Cools Werk', '2023', '12', '14', '09', '00', '2023', '12', '14', '18', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-12-03 10:31:00', '2023-12-03 10:31:00'),
(1155, '2', 'Henri Cools Werk', '2023', '12', '15', '12', '00', '2023', '12', '15', '21', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-12-03 10:31:59', '2023-12-03 10:31:59'),
(1156, '1', 'Andy Windels Werk', '2023', '12', '11', '09', '00', '2023', '12', '11', '18', '00', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2023-12-07 18:44:57', '2023-12-07 18:44:57'),
(1157, '1', 'Andy Windels Werk', '2023', '12', '12', '09', '00', '2023', '12', '12', '18', '00', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2023-12-07 18:44:57', '2023-12-07 18:44:57'),
(1158, '1', 'Andy Windels Werk', '2023', '12', '13', '09', '00', '2023', '12', '13', '18', '00', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2023-12-07 18:44:57', '2023-12-07 18:44:57'),
(1159, '1', 'Andy Windels Werk', '2023', '12', '18', '11', '30', '2023', '12', '18', '20', '15', 'false', 'Aalst-waarle, Nederland', ' Avond werken van wege te weinig mensen en ziektes ', '2', '', '#ffc107', 'Gewijzigd', '2023-12-07 18:44:57', '2023-12-11 22:46:53'),
(1160, '1', 'Andy Windels Werk', '2023', '12', '19', '09', '00', '2023', '12', '19', '18', '00', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2023-12-07 18:44:57', '2023-12-07 18:44:57'),
(1161, '1', 'Andy Windels Werk', '2023', '12', '20', '09', '00', '2023', '12', '20', '18', '00', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2023-12-07 18:44:57', '2023-12-07 18:44:57'),
(1162, '1', 'Andy Windels Werk', '2023', '12', '27', '09', '00', '2023', '12', '27', '18', '15', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2023-12-07 18:44:57', '2023-12-07 18:44:57'),
(1163, '1', 'Sinterklaas bijeenkomst', '2023', '12', '09', '14', '30', '2023', '12', '09', '18', '30', 'false', 'Beukenlaan 8, 3930 Hamont-achel', 'Sinterklaas viering', '2, 4', '1, 2, 3, 7, 8', '#00a65a', 'Toegevoegd', '2023-12-08 23:27:04', '2023-12-08 23:27:04'),
(1164, '1', 'Andy Windels Werk', '2023', '12', '10', '00', '00', '2023', '12', '10', '19', '00', 'false', 'Oosteeklo', 'Pakketjes maken voor de webshop. ', '2', '', '#ffc107', 'Toegevoegd', '2023-12-10 08:03:42', '2023-12-10 08:03:42'),
(1165, '1', 'Andy Windels Werk', '2024', '01', '02', '09', '00', '2024', '01', '02', '18', '00', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2023-12-11 22:47:46', '2023-12-11 22:50:05'),
(1166, '1', 'Andy Windels Werk', '2024', '01', '03', '09', '00', '2024', '01', '03', '18', '00', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2023-12-11 22:48:32', '2023-12-11 22:48:32'),
(1168, '1', 'Andy Windels Werk', '2024', '01', '08', '09', '00', '2024', '01', '08', '18', '00', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2023-12-11 22:51:30', '2023-12-11 22:51:30'),
(1169, '1', 'Andy Windels Werk', '2024', '01', '09', '09', '00', '2024', '01', '09', '18', '00', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2023-12-11 22:52:05', '2023-12-11 22:52:05'),
(1170, '1', 'Andy Windels Werk', '2024', '01', '10', '09', '00', '2024', '01', '10', '18', '00', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2023-12-11 22:52:37', '2023-12-11 22:52:37'),
(1174, '2', 'Henri Cools Werk', '2023', '12', '18', '13', '00', '2023', '12', '18', '18', '30', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-12-13 06:44:09', '2023-12-13 06:44:09'),
(1175, '2', 'Henri Cools Werk', '2023', '12', '19', '08', '00', '2023', '12', '19', '13', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-12-13 06:44:55', '2023-12-13 06:44:55'),
(1176, '2', 'Henri Cools Werk', '2023', '12', '21', '13', '00', '2023', '12', '21', '18', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-12-13 06:46:25', '2023-12-13 06:46:25'),
(1177, '2', 'Henri Cools Werk', '2023', '12', '22', '12', '00', '2023', '12', '22', '21', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-12-13 06:47:13', '2023-12-13 06:47:13'),
(1178, '2', 'Henri Cools Werk', '2023', '12', '24', '09', '30', '2023', '12', '24', '17', '30', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-12-13 06:48:18', '2023-12-13 06:48:18'),
(1179, '2', 'Henri Cools Werk', '2023', '12', '26', '09', '30', '2023', '12', '26', '17', '30', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-12-13 06:49:27', '2023-12-13 06:49:27'),
(1180, '2', 'Henri Cools Werk', '2023', '12', '27', '12', '00', '2023', '12', '27', '19', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-12-19 18:24:04', '2023-12-19 18:24:04'),
(1181, '2', 'Henri Cools Werk', '2023', '12', '29', '14', '00', '2023', '12', '29', '21', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-12-19 18:24:56', '2023-12-19 18:24:56'),
(1182, '2', 'Henri Cools Werk', '2023', '12', '30', '10', '00', '2023', '12', '30', '18', '30', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-12-19 18:25:55', '2023-12-19 18:25:55'),
(1183, '2', 'Henri Cools Werk', '2024', '01', '02', '12', '00', '2024', '01', '02', '21', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-12-19 18:27:10', '2023-12-19 18:27:10'),
(1184, '2', 'Henri Cools Werk', '2024', '01', '03', '13', '00', '2024', '01', '03', '21', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-12-19 18:28:09', '2023-12-19 18:28:09'),
(1185, '2', 'Henri Cools Werk', '2024', '01', '05', '08', '00', '2024', '01', '05', '17', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-12-19 18:29:30', '2023-12-19 18:29:30'),
(1186, '2', 'Henri Cools Werk', '2024', '01', '06', '08', '00', '2024', '01', '06', '17', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2023-12-19 18:30:12', '2023-12-19 18:30:12'),
(1187, '1', 'Kyani halen ', '2023', '12', '23', '11', '00', '2023', '12', '23', '15', '00', 'false', 'Oosteeklo', '', '7', '1, 2, 4, 5, 6', '#ffc107', 'Toegevoegd', '2023-12-22 09:36:27', '2023-12-22 09:36:27'),
(1188, '1', 'Andy Windels Werk', '2024', '01', '15', '09', '00', '2024', '01', '15', '18', '15', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2024-01-02 08:51:36', '2024-01-02 08:51:36'),
(1189, '1', 'Andy Windels Werk', '2024', '01', '16', '09', '00', '2024', '01', '16', '18', '15', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2024-01-02 08:52:30', '2024-01-02 08:52:30'),
(1190, '1', 'Andy Windels Werk', '2024', '01', '17', '09', '00', '2024', '01', '17', '18', '15', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2024-01-02 08:53:21', '2024-01-02 08:53:21'),
(1191, '1', 'Andy Windels Werk', '2024', '01', '22', '09', '00', '2024', '01', '22', '18', '00', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2024-01-03 10:32:30', '2024-01-03 10:32:30'),
(1192, '1', 'Andy Windels Werk', '2024', '01', '23', '09', '00', '2024', '01', '23', '18', '00', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2024-01-03 10:33:21', '2024-01-03 10:33:21'),
(1194, '1', 'Andy Windels Werk', '2024', '01', '24', '09', '00', '2024', '01', '24', '18', '00', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2024-01-03 10:34:22', '2024-01-03 10:34:22'),
(1195, '2', 'Henri Cools Werk', '2024', '01', '16', '08', '00', '2024', '01', '16', '17', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-01-03 10:39:57', '2024-01-03 10:39:57'),
(1196, '2', 'Henri Cools Werk', '2024', '01', '17', '12', '00', '2024', '01', '17', '19', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-01-03 10:41:51', '2024-01-03 10:41:51'),
(1197, '2', 'Henri Cools Werk', '2024', '01', '19', '08', '00', '2024', '01', '19', '17', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-01-03 10:42:38', '2024-01-03 10:42:38'),
(1198, '2', 'Henri Cools Werk', '2024', '01', '21', '09', '30', '2024', '01', '21', '18', '30', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-01-03 10:43:34', '2024-01-03 10:43:34'),
(1199, '2', 'Henri Cools Werk', '2024', '01', '08', '12', '00', '2024', '01', '08', '18', '30', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-01-03 10:45:14', '2024-01-03 10:45:14'),
(1200, '2', 'Henri Cools Werk', '2024', '01', '09', '14', '00', '2024', '01', '09', '21', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-01-03 10:45:57', '2024-01-03 10:45:57'),
(1201, '2', 'Henri Cools Werk', '2024', '01', '10', '10', '00', '2024', '01', '10', '18', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-01-03 10:46:41', '2024-01-03 10:46:41'),
(1202, '2', 'Henri Cools Werk', '2024', '01', '11', '13', '00', '2024', '01', '11', '18', '30', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-01-03 10:47:46', '2024-01-03 10:47:46'),
(1203, '2', 'Henri Cools Werk', '2024', '01', '12', '08', '00', '2024', '01', '12', '17', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-01-03 10:48:36', '2024-01-03 10:48:36'),
(1204, '1', 'Reiniging schouw', '2024', '02', '09', '10', '00', '2024', '02', '09', '12', '00', 'false', '', 'Reiniging schouw openhaart', '2', '1, 2', '#00a65a', 'Gewijzigd', '2024-01-11 13:59:54', '2024-01-11 14:00:30'),
(1205, '1', 'Andy Windels Werk', '2024', '01', '29', '09', '00', '2024', '01', '29', '18', '15', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#ffc107', 'Gewijzigd', '2024-01-20 15:43:48', '2024-01-20 22:10:32'),
(1206, '1', 'Andy Windels Werk', '2024', '01', '30', '09', '00', '2024', '01', '30', '18', '15', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2024-01-20 15:44:27', '2024-01-20 15:44:27'),
(1207, '1', 'Andy Windels Werk', '2024', '01', '31', '09', '00', '2024', '01', '31', '18', '15', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#ffc107', 'Gewijzigd', '2024-01-20 15:45:53', '2024-01-20 22:09:48'),
(1208, '1', 'Andy Windels Werk', '2024', '02', '05', '09', '00', '2024', '02', '05', '18', '00', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2024-01-20 15:46:27', '2024-01-20 15:46:27'),
(1209, '1', 'Andy Windels Werk', '2024', '02', '06', '09', '00', '2024', '02', '06', '18', '00', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2024-01-20 15:47:06', '2024-01-20 15:47:06'),
(1210, '1', 'Andy Windels Werk', '2024', '02', '07', '08', '00', '2024', '02', '07', '17', '00', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2024-01-20 15:47:44', '2024-01-20 15:47:44'),
(1211, '2', 'Henri Cools Werk', '2024', '01', '23', '12', '00', '2024', '01', '23', '21', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-01-20 22:03:00', '2024-01-20 22:03:00'),
(1212, '2', 'Henri Cools Werk', '2024', '01', '24', '10', '00', '2024', '01', '24', '18', '45', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-01-20 22:04:05', '2024-01-20 22:04:05'),
(1213, '2', 'Henri Cools Werk', '2024', '01', '26', '12', '00', '2024', '01', '26', '21', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-01-20 22:04:52', '2024-01-20 22:04:52'),
(1214, '2', 'Henri Cools Werk', '2024', '01', '27', '08', '00', '2024', '01', '27', '13', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-01-20 22:05:40', '2024-01-20 22:05:40'),
(1215, '2', 'Henri Cools Werk', '2024', '01', '29', '09', '30', '2024', '01', '29', '18', '30', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-01-20 22:06:45', '2024-01-20 22:06:45'),
(1216, '2', 'Henri Cools Werk', '2024', '01', '30', '08', '00', '2024', '01', '30', '17', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-01-20 22:07:47', '2024-01-20 22:07:47'),
(1217, '2', 'Henri Cools Werk', '2024', '02', '02', '08', '00', '2024', '02', '02', '17', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-01-20 22:08:39', '2024-01-20 22:08:39'),
(1218, '2', 'Henri Cools Werk', '2024', '02', '03', '08', '00', '2024', '02', '03', '17', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-01-20 22:09:44', '2024-01-20 22:09:44'),
(1219, '2', 'Henri garage ', '2024', '02', '01', '08', '30', '2024', '02', '01', '13', '00', 'false', 'Pelt ', 'Garage en keuring auto ', '', '', '#ffc107', 'Toegevoegd', '2024-01-20 22:11:27', '2024-01-20 22:11:27'),
(1220, '1', 'Andy Windels Werk', '2024', '02', '12', '09', '00', '2024', '02', '12', '18', '00', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2024-01-29 21:28:51', '2024-01-29 21:28:51'),
(1221, '1', 'Andy Windels Werk', '2024', '02', '13', '09', '00', '2024', '02', '13', '18', '00', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2024-01-29 21:29:31', '2024-01-29 21:29:31'),
(1222, '1', 'Andy Windels Werk', '2024', '02', '14', '08', '00', '2024', '02', '14', '17', '00', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2024-01-29 21:30:07', '2024-01-29 21:30:07'),
(1223, '1', 'Andy Windels Werk', '2024', '02', '19', '09', '00', '2024', '02', '19', '18', '00', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2024-01-29 21:31:40', '2024-01-29 21:31:40'),
(1224, '1', 'Andy Windels Werk', '2024', '02', '20', '09', '00', '2024', '02', '20', '18', '00', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2024-01-29 21:34:15', '2024-01-29 21:34:15'),
(1225, '1', 'Andy Windels Werk', '2024', '02', '21', '08', '00', '2024', '02', '21', '17', '00', 'false', 'Aalst-waarle, Nederland', '  ', '2', '', '#ffc107', 'Toegevoegd', '2024-01-29 21:35:02', '2024-01-29 21:35:02'),
(1226, '1', 'Andy Windels Werk', '2024', '02', '21', '08', '00', '2024', '02', '21', '17', '00', 'false', 'Aalst-waarle, Nederland', '  ', '2', '', '#ffc107', 'Toegevoegd', '2024-01-29 21:54:24', '2024-01-29 21:54:24'),
(1227, '2', 'Henri Cools Werk', '2024', '02', '05', '12', '00', '2024', '02', '05', '18', '30', 'false', 'Valkenswaard, Nederland', ' \r\n', '2', '', '#007bff', 'Toegevoegd', '2024-01-31 17:57:42', '2024-01-31 17:57:42');
INSERT INTO `evenement` (`id`, `userid`, `title`, `bjaar`, `bmaand`, `bdag`, `buur`, `bmin`, `ejaar`, `emaand`, `edag`, `euur`, `emin`, `fullday`, `locatie`, `text`, `groupsen`, `usersids`, `color`, `status`, `date`, `last_date`) VALUES
(1228, '2', 'Henri Cools Werk', '2024', '02', '07', '10', '00', '2024', '02', '07', '18', '30', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-01-31 17:58:30', '2024-01-31 17:58:30'),
(1229, '2', 'Henri Cools Werk', '2024', '02', '08', '10', '00', '2024', '02', '08', '18', '30', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-01-31 17:59:23', '2024-01-31 17:59:23'),
(1230, '2', 'Henri Cools Werk', '2024', '02', '09', '07', '45', '2024', '02', '09', '17', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-01-31 18:01:15', '2024-01-31 18:01:15'),
(1231, '2', 'Henri Cools Werk', '2024', '02', '12', '10', '00', '2024', '02', '12', '18', '30', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-01-31 18:03:03', '2024-01-31 18:03:03'),
(1232, '2', 'Henri Cools Werk', '2024', '02', '14', '07', '45', '2024', '02', '14', '17', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-01-31 18:04:30', '2024-01-31 18:04:30'),
(1233, '2', 'Henri Cools Werk', '2024', '02', '16', '07', '45', '2024', '02', '16', '17', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-01-31 18:05:43', '2024-01-31 18:05:43'),
(1234, '2', 'Henri Cools Werk', '2024', '02', '17', '08', '00', '2024', '02', '17', '17', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-01-31 18:06:30', '2024-01-31 18:06:30'),
(1235, '1', 'Andy Windels Werk', '2024', '02', '26', '08', '00', '2024', '02', '26', '17', '00', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2024-02-06 20:21:20', '2024-02-06 20:21:20'),
(1236, '1', 'Andy Windels Werk', '2024', '02', '27', '09', '00', '2024', '02', '27', '18', '00', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2024-02-06 20:21:53', '2024-02-06 20:21:53'),
(1237, '1', 'Andy Windels Werk', '2024', '02', '28', '08', '00', '2024', '02', '28', '17', '00', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2024-02-06 20:22:27', '2024-02-06 20:22:27'),
(1238, '1', 'Andy Windels Werk', '2024', '03', '04', '09', '00', '2024', '03', '04', '18', '00', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2024-02-15 08:50:57', '2024-02-15 08:50:57'),
(1239, '1', 'Andy Windels Werk', '2024', '03', '05', '09', '00', '2024', '03', '05', '18', '00', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2024-02-15 08:55:33', '2024-02-15 08:55:33'),
(1240, '1', 'Andy Windels Werk', '2024', '03', '06', '09', '00', '2024', '03', '06', '18', '00', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2024-02-15 08:56:55', '2024-02-15 08:56:55'),
(1241, '2', 'Henri Cools Werk', '2024', '02', '19', '13', '00', '2024', '02', '19', '18', '30', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-02-15 09:01:36', '2024-02-15 09:01:36'),
(1242, '2', 'Henri Cools Werk', '2024', '02', '20', '12', '00', '2024', '02', '20', '21', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-02-15 09:02:29', '2024-02-15 09:02:29'),
(1243, '2', 'Henri Cools Werk', '2024', '02', '21', '09', '00', '2024', '02', '21', '18', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-02-15 09:03:29', '2024-02-15 09:03:29'),
(1244, '2', 'Henri Cools Werk', '2024', '02', '23', '12', '00', '2024', '02', '23', '21', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-02-15 09:04:42', '2024-02-15 09:04:42'),
(1245, '2', 'Henri Cools Werk', '2024', '02', '26', '09', '00', '2024', '02', '26', '18', '30', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-02-15 09:06:08', '2024-02-15 09:06:08'),
(1246, '2', 'Henri Cools Werk', '2024', '02', '27', '14', '00', '2024', '02', '27', '21', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-02-15 09:07:02', '2024-02-15 09:07:02'),
(1247, '2', 'Henri Cools Werk', '2024', '02', '29', '07', '45', '2024', '02', '29', '17', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-02-15 09:08:05', '2024-02-15 09:08:05'),
(1248, '2', 'Henri Cools Werk', '2024', '03', '02', '08', '00', '2024', '03', '02', '17', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-02-15 09:08:56', '2024-02-15 09:08:56'),
(1249, '1', 'Andy Windels Werk', '2024', '03', '11', '09', '00', '2024', '03', '11', '18', '00', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2024-02-19 10:40:50', '2024-02-19 10:40:50'),
(1250, '1', 'Andy Windels Werk', '2024', '03', '12', '09', '00', '2024', '03', '12', '18', '00', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2024-02-19 10:41:28', '2024-02-19 10:41:28'),
(1251, '1', 'Andy Windels Werk', '2024', '03', '13', '09', '00', '2024', '03', '13', '18', '00', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2024-02-19 10:42:01', '2024-02-19 10:42:01'),
(1252, '2', 'Henri Cools Werk', '2024', '03', '05', '07', '00', '2024', '03', '05', '13', '30', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-02-23 10:51:09', '2024-02-23 10:51:09'),
(1253, '2', 'Henri Cools Werk', '2024', '03', '06', '07', '00', '2024', '03', '06', '14', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-02-23 10:51:58', '2024-02-23 10:51:58'),
(1254, '2', 'Henri Cools Werk', '2024', '03', '07', '07', '00', '2024', '03', '07', '14', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-02-23 10:52:57', '2024-02-23 10:52:57'),
(1255, '2', 'Henri Cools Werk', '2024', '03', '08', '07', '45', '2024', '03', '08', '17', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-02-23 10:54:06', '2024-02-23 10:54:06'),
(1256, '2', 'Henri Cools Werk', '2024', '03', '09', '08', '00', '2024', '03', '09', '13', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-02-23 10:55:24', '2024-02-23 10:55:24'),
(1257, '1', 'Andy Windels Werk', '2024', '03', '18', '13', '00', '2024', '03', '18', '21', '00', 'false', 'Aalst-waarle, Nederland', 'Van wege tellingen in de winkel moeten we andere uren werken', '2', '', '#ffc107', 'Toegevoegd', '2024-02-23 15:32:34', '2024-02-23 15:32:34'),
(1258, '1', 'Andy Windels Werk', '2024', '03', '19', '09', '00', '2024', '03', '19', '18', '00', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2024-02-23 15:33:08', '2024-02-23 15:33:08'),
(1259, '1', 'Andy Windels Werk', '2024', '03', '20', '08', '00', '2024', '03', '20', '17', '00', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2024-02-23 15:33:44', '2024-02-23 15:33:44'),
(1260, '2', 'Henri Cools Werk', '2024', '03', '11', '14', '00', '2024', '03', '11', '21', '00', 'false', 'Valkenswaard, Nederland', 'Balans ', '2', '', '#007bff', 'Toegevoegd', '2024-03-03 21:47:45', '2024-03-03 21:47:45'),
(1261, '2', 'Henri Cools Werk', '2024', '03', '12', '14', '00', '2024', '03', '12', '21', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-03-03 21:48:48', '2024-03-03 21:48:48'),
(1262, '2', 'Henri Cools Werk', '2024', '03', '13', '07', '45', '2024', '03', '13', '17', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-03-03 21:50:13', '2024-03-03 21:50:13'),
(1263, '2', 'Henri Cools Werk', '2024', '03', '15', '07', '00', '2024', '03', '15', '16', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-03-03 21:51:09', '2024-03-03 21:51:09'),
(1264, '1', 'Henri Cools Werk', '2024', '03', '16', '08', '00', '2024', '03', '16', '15', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Gewijzigd', '2024-03-03 21:52:02', '2024-03-15 23:16:37'),
(1265, '1', 'Andy Windels Werk', '2024', '03', '25', '09', '00', '2024', '03', '25', '18', '00', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2024-03-04 10:43:20', '2024-03-04 10:43:20'),
(1266, '1', 'Andy Windels Werk', '2024', '03', '26', '09', '00', '2024', '03', '26', '18', '00', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2024-03-04 10:43:57', '2024-03-04 10:43:57'),
(1267, '1', 'Andy Windels Werk', '2024', '03', '27', '08', '00', '2024', '03', '27', '17', '00', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2024-03-04 10:44:26', '2024-03-04 10:44:26'),
(1268, '2', 'Henri Cools Werk', '2024', '03', '18', '12', '00', '2024', '03', '18', '21', '30', 'false', 'Valkenswaard, Nederland', ' 12 tot 18h Valkenswaard \r\n18 tot 21.30 balans Waalre ', '2', '', '#007bff', 'Gewijzigd', '2024-03-06 15:42:56', '2024-03-14 08:25:28'),
(1269, '2', 'Henri Cools Werk', '2024', '03', '19', '07', '00', '2024', '03', '19', '12', '30', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-03-06 15:44:00', '2024-03-06 15:44:00'),
(1270, '2', 'Henri Cools Werk', '2024', '03', '20', '14', '00', '2024', '03', '20', '21', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-03-06 15:44:47', '2024-03-06 15:44:47'),
(1271, '2', 'Henri Cools Werk', '2024', '03', '22', '08', '00', '2024', '03', '22', '17', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-03-06 15:46:05', '2024-03-06 15:46:05'),
(1272, '2', 'Henri Cools Werk', '2024', '03', '22', '08', '00', '2024', '03', '22', '17', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-03-06 15:46:36', '2024-03-06 15:46:36'),
(1275, '2', 'Henri Cools Werk', '2024', '03', '23', '09', '00', '2024', '03', '23', '17', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-03-06 15:52:18', '2024-03-06 15:52:18'),
(1276, '2', 'Henri Cools Werk', '2024', '03', '24', '09', '30', '2024', '03', '24', '12', '30', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-03-06 15:53:04', '2024-03-06 15:53:04'),
(1277, '1', 'Pensioen viering Lotus pa Andy', '2024', '03', '22', '14', '00', '2024', '03', '22', '18', '00', 'false', 'Oosteeklo', 'Andy is naar Oosteeklo voor de viering bij Lotus van pa zijn pensioen. ', '2', '1', '#ffc107', 'Toegevoegd', '2024-03-09 09:54:23', '2024-03-09 09:54:23'),
(1278, '1', 'Andy Windels Werk', '2024', '04', '02', '09', '00', '2024', '04', '02', '18', '00', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2024-03-11 10:41:58', '2024-03-11 10:41:58'),
(1279, '1', 'Andy Windels Werk', '2024', '04', '03', '08', '00', '2024', '04', '03', '17', '00', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2024-03-11 10:42:30', '2024-03-11 10:42:30'),
(1280, '2', 'Henri Cools Werk', '2024', '03', '25', '09', '00', '2024', '03', '25', '18', '30', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-03-14 08:19:48', '2024-03-14 08:19:48'),
(1281, '2', 'Henri Cools Werk', '2024', '03', '28', '10', '00', '2024', '03', '28', '19', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-03-14 08:20:57', '2024-03-14 08:20:57'),
(1282, '1', 'Henri Cools Werk', '2024', '03', '29', '10', '00', '2024', '03', '29', '17', '00', 'false', 'Valkenswaard, Nederland', 'Goede vrijdag ', '2', '', '#007bff', 'Gewijzigd', '2024-03-14 08:21:55', '2024-03-29 08:58:01'),
(1283, '2', 'Henri Cools Werk', '2024', '03', '30', '09', '30', '2024', '03', '30', '18', '30', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-03-14 08:23:18', '2024-03-14 08:23:18'),
(1284, '1', 'Andy Windels Werk', '2024', '04', '08', '09', '00', '2024', '04', '08', '18', '00', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2024-03-19 19:17:11', '2024-03-19 19:17:11'),
(1285, '1', 'Andy Windels Werk', '2024', '04', '09', '09', '00', '2024', '04', '09', '18', '00', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2024-03-19 19:17:44', '2024-03-19 19:17:44'),
(1286, '1', 'Andy Windels Werk', '2024', '04', '10', '09', '00', '2024', '04', '10', '18', '00', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2024-03-19 19:18:32', '2024-03-19 19:21:10'),
(1287, '1', 'Vakantie', '2024', '04', '15', '00', '00', '2024', '04', '22', '23', '59', 'true', '', ' ', '2', '', '#ffc107', 'Toegevoegd', '2024-03-19 19:23:05', '2024-03-19 19:23:05'),
(1288, '2', 'Henri Cools Werk', '2024', '04', '01', '12', '00', '2024', '04', '01', '18', '30', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-03-28 08:23:44', '2024-03-28 08:23:44'),
(1289, '2', 'Henri Cools Werk', '2024', '04', '02', '08', '00', '2024', '04', '02', '17', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-03-28 08:24:31', '2024-03-28 08:24:31'),
(1290, '2', 'Henri Cools Werk', '2024', '04', '04', '07', '45', '2024', '04', '04', '17', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-03-28 08:25:17', '2024-03-28 08:25:17'),
(1291, '2', 'Henri Cools Werk', '2024', '04', '06', '09', '00', '2024', '04', '06', '18', '30', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-03-28 08:26:08', '2024-03-28 08:26:08'),
(1292, '2', 'Henri Cools Werk', '2024', '04', '09', '07', '00', '2024', '04', '09', '16', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-03-28 08:27:13', '2024-03-28 08:27:13'),
(1293, '2', 'Henri Cools Werk', '2024', '04', '10', '14', '00', '2024', '04', '10', '21', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-03-28 08:28:05', '2024-03-28 08:28:05'),
(1294, '2', 'Henri Cools Werk', '2024', '04', '12', '07', '00', '2024', '04', '12', '16', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-03-28 08:29:04', '2024-03-28 08:29:04'),
(1295, '2', 'Henri Cools Werk', '2024', '04', '13', '08', '00', '2024', '04', '13', '18', '30', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-03-28 08:29:53', '2024-03-28 08:29:53'),
(1296, '1', 'Begrafenis ', '2024', '04', '05', '11', '00', '2024', '04', '05', '17', '00', 'false', 'Lievegem/Eeklo', 'Begrafenis (Meter Andy)', '2', '', '#00a65a', 'Toegevoegd', '2024-03-29 09:00:54', '2024-03-29 09:00:54'),
(1297, '1', 'Pasen ', '2024', '03', '31', '14', '00', '2024', '03', '31', '20', '00', 'false', 'Achel', 'Lekker eten voor Pasen ', '2', '1, 2, 3', '#00a65a', 'Toegevoegd', '2024-03-29 09:03:45', '2024-03-29 09:03:45'),
(1298, '1', 'Trouw jubileum ma en pa (feestje)', '2024', '07', '06', '12', '00', '2024', '07', '07', '19', '00', 'false', 'Oosteeklo', 'Feestje met zussen van mijn Andy vaders ', '7', '1, 2, 4, 5, 6', '#00a65a', 'Gewijzigd', '2024-04-05 16:52:58', '2024-06-29 09:30:11'),
(1299, '1', 'Andy Windels Werk', '2024', '04', '22', '08', '00', '2024', '04', '22', '17', '00', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2024-04-08 08:43:24', '2024-04-08 08:43:24'),
(1300, '1', 'Andy Windels Werk', '2024', '04', '23', '09', '00', '2024', '04', '23', '18', '00', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2024-04-08 08:44:03', '2024-04-08 08:44:03'),
(1301, '1', 'Andy Windels Werk', '2024', '04', '24', '08', '00', '2024', '04', '24', '17', '00', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2024-04-08 08:44:43', '2024-04-08 08:44:43'),
(1302, '1', 'Markt Sleidinge', '2024', '04', '27', '17', '00', '2024', '04', '28', '01', '00', 'false', 'Sleidinge', '', '', '', '#00a65a', 'Toegevoegd', '2024-04-08 08:46:18', '2024-04-08 08:46:18'),
(1303, '1', 'Personeel feest action Waalre', '2024', '06', '30', '14', '00', '2024', '06', '30', '22', '00', 'false', 'Waarle', ' ', '2', '', '#ffc107', 'Toegevoegd', '2024-04-08 12:39:26', '2024-04-08 12:39:26'),
(1304, '1', 'Andy Windels Werk', '2024', '04', '29', '09', '00', '2024', '04', '29', '18', '00', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2024-04-09 19:41:56', '2024-04-09 19:41:56'),
(1305, '1', 'Andy Windels Werk', '2024', '04', '30', '09', '00', '2024', '04', '30', '18', '00', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2024-04-09 19:42:29', '2024-04-09 19:42:29'),
(1306, '1', 'Andy Windels Werk', '2024', '05', '01', '08', '00', '2024', '05', '01', '17', '00', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2024-04-09 19:43:01', '2024-04-09 19:43:01'),
(1307, '2', 'Henri Cools Werk', '2024', '04', '22', '09', '00', '2024', '04', '22', '18', '30', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-04-09 20:14:11', '2024-04-09 20:14:11'),
(1308, '2', 'Henri Cools Werk', '2024', '04', '23', '09', '00', '2024', '04', '23', '17', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-04-09 20:15:01', '2024-04-09 20:15:01'),
(1309, '2', 'Henri Cools Werk', '2024', '04', '24', '14', '00', '2024', '04', '24', '21', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-04-09 20:16:09', '2024-04-09 20:16:09'),
(1310, '2', 'Henri Cools Werk', '2024', '04', '27', '09', '00', '2024', '04', '27', '18', '30', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-04-09 20:17:34', '2024-04-09 20:17:34'),
(1311, '2', 'Henri Cools Werk', '2024', '04', '29', '08', '00', '2024', '04', '29', '18', '30', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-04-23 07:38:46', '2024-04-23 07:38:46'),
(1312, '2', 'Henri Cools Werk', '2024', '04', '29', '08', '00', '2024', '04', '29', '18', '30', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-04-23 07:38:48', '2024-04-23 07:38:48'),
(1313, '2', 'Henri Cools Werk', '2024', '04', '29', '14', '00', '2024', '04', '30', '21', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-04-23 07:39:44', '2024-04-23 07:39:44'),
(1314, '2', 'Henri Cools Werk', '2024', '05', '01', '17', '00', '2024', '05', '01', '21', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-04-23 07:40:59', '2024-04-23 07:40:59'),
(1315, '2', 'Henri Cools Werk', '2024', '05', '02', '11', '00', '2024', '05', '02', '18', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-04-23 07:41:53', '2024-04-23 07:41:53'),
(1316, '2', 'Henri Cools Werk', '2024', '05', '03', '10', '00', '2024', '05', '03', '17', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-04-23 07:44:13', '2024-04-23 07:44:13'),
(1317, '1', 'Andy Windels Werk', '2024', '05', '06', '09', '00', '2024', '05', '06', '18', '15', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2024-04-23 08:38:31', '2024-04-23 08:38:31'),
(1318, '1', 'Andy Windels Werk', '2024', '05', '07', '09', '00', '2024', '05', '07', '18', '15', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2024-04-23 08:39:06', '2024-04-23 08:39:06'),
(1319, '1', 'Andy Windels Werk', '2024', '05', '08', '09', '00', '2024', '05', '08', '18', '15', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2024-04-23 08:39:38', '2024-04-23 08:39:38'),
(1320, '1', 'Andy Windels Werk', '2024', '05', '08', '09', '00', '2024', '05', '08', '18', '15', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2024-04-23 08:40:15', '2024-04-23 08:40:15'),
(1321, '1', 'Andy Windels Werk', '2024', '05', '13', '09', '00', '2024', '05', '13', '18', '00', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2024-05-01 13:01:33', '2024-05-01 13:01:33'),
(1322, '1', 'Andy Windels Werk', '2024', '05', '13', '09', '00', '2024', '05', '13', '18', '00', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2024-05-01 13:01:35', '2024-05-01 13:01:35'),
(1323, '1', 'Andy Windels Werk', '2024', '05', '14', '09', '00', '2024', '05', '14', '18', '00', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2024-05-01 13:02:55', '2024-05-01 13:02:55'),
(1324, '1', 'Andy Windels Werk', '2024', '05', '15', '09', '00', '2024', '05', '15', '18', '00', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2024-05-01 13:05:24', '2024-05-01 18:07:06'),
(1325, '1', 'Andy Windels Werk', '2024', '05', '21', '09', '00', '2024', '05', '21', '18', '00', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2024-05-01 13:05:24', '2024-05-01 18:07:06'),
(1326, '1', 'Andy Windels Werk', '2024', '05', '22', '08', '00', '2024', '05', '22', '17', '00', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2024-05-01 13:02:55', '2024-05-01 13:02:55'),
(1327, '1', 'Andy met oud collega’s Oploo drinken. ', '2024', '05', '26', '14', '00', '2024', '05', '26', '17', '00', 'false', 'Stationsstraat 17, 5751 HA Deurne, Nederland', 'Iets gaan drinken met oud collega’s van Timco oploo\r\n\r\nLocatie is nog niet bekend. ', '', '', '#00a65a', 'Gewijzigd', '2024-05-01 18:10:19', '2024-05-07 20:00:06'),
(1328, '1', 'Andy met oud collega’s Oploo drinken. ', '2024', '05', '26', '14', '00', '2024', '05', '26', '17', '00', 'false', 'Onbekend ', 'Iets gaan drinken met oud collega’s van Timco oploo\r\n\r\nLocatie is nog niet bekend. ', '', '', '#00a65a', 'Toegevoegd', '2024-05-01 18:19:42', '2024-05-01 18:19:42'),
(1329, '2', 'Henri Cools Werk', '2024', '05', '06', '08', '00', '2024', '05', '06', '18', '30', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-05-03 18:34:35', '2024-05-03 18:34:35'),
(1330, '2', 'Henri Cools Werk', '2024', '05', '07', '07', '00', '2024', '05', '07', '16', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-05-03 18:35:23', '2024-05-03 18:35:23'),
(1331, '2', 'Henri Cools Werk', '2024', '05', '08', '14', '00', '2024', '05', '08', '21', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-05-03 18:36:04', '2024-05-03 18:36:04'),
(1332, '2', 'Henri Cools Werk', '2024', '05', '09', '10', '00', '2024', '05', '09', '18', '30', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-05-03 18:36:57', '2024-05-03 18:36:57'),
(1333, '2', 'Henri Cools Werk', '2024', '05', '13', '08', '00', '2024', '05', '13', '18', '30', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-05-03 18:38:05', '2024-05-03 18:38:05'),
(1334, '2', 'Henri Cools Werk', '2024', '05', '14', '07', '00', '2024', '05', '14', '14', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-05-03 18:38:40', '2024-05-03 18:38:40'),
(1335, '2', 'Henri Cools Werk', '2024', '05', '15', '15', '00', '2024', '05', '15', '21', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-05-03 18:39:53', '2024-05-03 18:39:53'),
(1336, '2', 'Henri Cools Werk', '2024', '05', '16', '08', '00', '2024', '05', '16', '17', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-05-03 18:40:49', '2024-05-03 18:40:49'),
(1337, '2', 'Henri Cools Werk', '2024', '05', '17', '07', '00', '2024', '05', '17', '14', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-05-03 18:42:07', '2024-05-03 18:42:07'),
(1338, '2', 'Henri Cools Werk', '2024', '05', '21', '07', '45', '2024', '05', '21', '17', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-05-03 18:43:14', '2024-05-03 18:43:14'),
(1339, '2', 'Henri Cools Werk', '2024', '05', '22', '07', '45', '2024', '05', '22', '17', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-05-03 18:44:25', '2024-05-03 18:44:25'),
(1340, '2', 'Henri Cools Werk', '2024', '05', '23', '08', '00', '2024', '05', '23', '17', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-05-03 18:45:28', '2024-05-03 18:45:28'),
(1341, '2', 'Henri Cools Werk', '2024', '05', '25', '08', '00', '2024', '05', '25', '17', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-05-03 18:46:12', '2024-05-03 18:46:12'),
(1342, '1', 'Dorpsfeest achel', '2024', '06', '16', '12', '00', '2024', '06', '16', '18', '00', 'false', 'Achel', 'Staanplaats markt Achel ', '7', '1, 2, 4, 5, 6', '#ffc107', 'Toegevoegd', '2024-05-04 12:29:07', '2024-05-04 12:29:07'),
(1343, '2', 'Henri Cools Werk', '2024', '05', '18', '08', '00', '2024', '05', '18', '13', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-05-05 16:26:42', '2024-05-05 16:26:42'),
(1344, '1', 'Andy Windels Werk', '2024', '05', '27', '09', '00', '2024', '05', '27', '18', '00', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2024-05-08 20:14:25', '2024-05-08 20:14:25'),
(1345, '1', 'Andy Windels Werk', '2024', '05', '28', '09', '00', '2024', '05', '28', '18', '00', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2024-05-08 20:14:58', '2024-05-08 20:14:58'),
(1346, '1', 'Andy Windels Werk', '2024', '05', '29', '08', '00', '2024', '05', '29', '17', '00', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2024-05-08 20:15:32', '2024-05-08 20:15:32'),
(1347, '1', 'Andy Windels Werk', '2024', '06', '03', '09', '00', '2024', '06', '03', '18', '15', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2024-05-10 16:47:29', '2024-05-10 16:47:29'),
(1348, '1', 'Andy Windels Werk', '2024', '06', '04', '08', '00', '2024', '06', '04', '17', '00', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2024-05-10 16:49:48', '2024-05-10 16:49:48'),
(1349, '1', 'Andy Windels Werk', '2024', '06', '05', '09', '00', '2024', '06', '05', '18', '15', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2024-05-10 16:50:26', '2024-05-10 16:50:26'),
(1350, '2', 'Henri Cools Werk', '2024', '05', '27', '10', '00', '2024', '05', '27', '18', '30', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-05-10 16:52:32', '2024-05-10 16:52:32'),
(1351, '2', 'Henri Cools Werk', '2024', '05', '28', '07', '00', '2024', '05', '28', '15', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-05-10 16:53:13', '2024-05-10 16:53:13'),
(1352, '2', 'Henri Cools Werk', '2024', '05', '29', '15', '00', '2024', '05', '29', '21', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-05-10 16:54:01', '2024-05-10 16:54:01'),
(1353, '2', 'Henri Cools Werk', '2024', '05', '30', '08', '00', '2024', '05', '30', '13', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-05-10 16:54:59', '2024-05-10 16:54:59'),
(1354, '2', 'Henri Cools Werk', '2024', '05', '31', '07', '00', '2024', '05', '31', '16', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-05-10 16:55:56', '2024-05-10 16:55:56'),
(1355, '2', 'Henri Cools Werk', '2024', '06', '03', '10', '00', '2024', '06', '03', '18', '30', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-05-20 18:16:45', '2024-05-20 18:16:45'),
(1356, '2', 'Henri Cools Werk', '2024', '06', '04', '07', '00', '2024', '06', '04', '15', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-05-20 18:17:27', '2024-05-20 18:17:27'),
(1357, '2', 'Henri Cools Werk', '2024', '06', '05', '12', '00', '2024', '06', '05', '18', '45', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-05-20 18:18:37', '2024-05-20 18:18:37'),
(1358, '2', 'Henri Cools Werk', '2024', '06', '06', '10', '00', '2024', '06', '06', '18', '30', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-05-20 18:19:27', '2024-05-20 18:19:27'),
(1359, '2', 'Henri Cools Werk', '2024', '06', '07', '07', '00', '2024', '06', '07', '15', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-05-20 18:20:11', '2024-05-20 18:20:11'),
(1360, '1', 'Vakantie', '2024', '06', '10', '00', '00', '2024', '06', '16', '23', '59', 'true', '', 'Vakantie ', '2', '', '#00a65a', 'Toegevoegd', '2024-05-24 09:26:46', '2024-05-24 09:26:46'),
(1361, '1', 'Vakantie ', '2024', '07', '22', '00', '00', '2024', '07', '28', '23', '59', 'true', '', 'Vakantie', '2', '', '#00a65a', 'Toegevoegd', '2024-05-24 09:28:00', '2024-05-24 09:28:00'),
(1362, '1', 'Vakantie', '2024', '08', '19', '00', '00', '2024', '08', '25', '23', '59', 'true', '', 'Vakantie ', '2', '', '#00a65a', 'Toegevoegd', '2024-05-24 09:28:52', '2024-05-24 09:28:52'),
(1363, '1', 'Andy Windels Werk', '2024', '06', '17', '08', '00', '2024', '06', '17', '17', '00', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2024-05-24 09:30:45', '2024-05-24 09:30:45'),
(1364, '1', 'Andy Windels Werk', '2024', '06', '18', '08', '00', '2024', '06', '18', '17', '00', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2024-05-24 09:31:25', '2024-05-24 09:31:25'),
(1365, '1', 'Andy Windels Werk', '2024', '06', '19', '08', '00', '2024', '06', '19', '17', '00', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2024-05-24 09:31:58', '2024-05-24 09:31:58'),
(1366, '1', 'Andy Windels Werk', '2024', '06', '24', '09', '00', '2024', '06', '24', '18', '00', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2024-06-03 07:58:00', '2024-06-03 07:58:00'),
(1367, '1', 'Andy Windels Werk', '2024', '06', '25', '09', '00', '2024', '06', '25', '18', '00', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2024-06-03 07:58:47', '2024-06-03 07:58:47'),
(1368, '1', 'Andy Windels Werk', '2024', '06', '26', '08', '00', '2024', '06', '26', '17', '00', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2024-06-03 07:59:42', '2024-06-03 07:59:42'),
(1369, '1', 'Andy Windels Werk', '2024', '07', '01', '09', '00', '2024', '07', '01', '18', '15', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2024-06-05 21:59:58', '2024-06-05 21:59:58'),
(1370, '1', 'Andy Windels Werk', '2024', '07', '02', '09', '00', '2024', '07', '02', '18', '15', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2024-06-05 22:00:30', '2024-06-05 22:00:30'),
(1371, '1', 'Andy Windels Werk', '2024', '07', '03', '09', '00', '2024', '07', '03', '18', '15', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2024-06-05 22:01:41', '2024-06-05 22:01:41'),
(1372, '1', 'Andy Windels Werk', '2024', '07', '03', '09', '00', '2024', '07', '03', '18', '15', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2024-06-05 22:01:42', '2024-06-05 22:01:42'),
(1373, '2', 'Henri Cools Werk', '2024', '06', '18', '08', '00', '2024', '06', '18', '17', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-06-07 18:09:16', '2024-06-07 18:09:16'),
(1374, '2', 'Henri Cools Werk', '2024', '06', '19', '08', '00', '2024', '06', '19', '17', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-06-07 18:09:55', '2024-06-07 18:09:55'),
(1375, '2', 'Henri Cools Werk', '2024', '06', '20', '08', '00', '2024', '06', '20', '17', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-06-07 18:10:37', '2024-06-07 18:10:37'),
(1376, '2', 'Henri Cools Werk', '2024', '06', '22', '08', '00', '2024', '06', '22', '17', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-06-07 18:11:27', '2024-06-07 18:11:27'),
(1377, '2', 'Henri Cools Werk', '2024', '06', '24', '08', '00', '2024', '06', '24', '18', '30', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-06-07 18:12:23', '2024-06-07 18:12:23'),
(1378, '2', 'Henri Cools Werk', '2024', '06', '26', '08', '00', '2024', '06', '26', '18', '30', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-06-07 18:13:18', '2024-06-20 13:00:46'),
(1379, '2', 'Henri Cools Werk', '2024', '06', '27', '08', '00', '2024', '06', '27', '17', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-06-07 18:14:04', '2024-06-07 18:14:04'),
(1381, '2', 'Henri Cools Werk', '2024', '06', '28', '08', '00', '2024', '06', '28', '17', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-06-07 19:03:16', '2024-06-07 19:03:16'),
(1382, '2', 'Henri Cools Werk', '2024', '07', '01', '08', '00', '2024', '07', '01', '17', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-06-10 18:33:46', '2024-06-10 18:33:46'),
(1383, '2', 'Henri Cools Werk', '2024', '07', '02', '08', '00', '2024', '07', '02', '17', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-06-10 18:34:20', '2024-06-10 18:34:20'),
(1384, '2', 'Henri Cools Werk', '2024', '07', '03', '08', '00', '2024', '07', '03', '17', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-06-10 18:34:58', '2024-06-10 18:34:58'),
(1385, '2', 'Henri Cools Werk', '2024', '07', '05', '08', '00', '2024', '07', '05', '17', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-06-10 18:36:10', '2024-06-10 18:36:10'),
(1386, '1', 'Andy Windels Werk', '2024', '07', '08', '08', '00', '2024', '07', '08', '17', '00', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2024-06-17 22:13:02', '2024-06-17 22:13:02'),
(1387, '1', 'Andy Windels Werk', '2024', '07', '09', '09', '00', '2024', '07', '09', '18', '15', 'false', 'Aalst-waarle, Nederland', ' ', '2', '1', '#ffc107', 'Toegevoegd', '2024-06-17 22:14:16', '2024-06-17 22:14:16'),
(1388, '1', 'Andy Windels Werk', '2024', '07', '10', '09', '00', '2024', '07', '10', '18', '15', 'false', 'Aalst-waarle, Nederland', ' ', '2', '1', '#ffc107', 'Toegevoegd', '2024-06-17 22:15:02', '2024-06-17 22:15:02'),
(1389, '2', 'Henri Cools Werk', '2024', '06', '30', '09', '45', '2024', '06', '30', '18', '15', 'false', 'Waarle, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-06-20 13:02:18', '2024-06-20 13:04:39'),
(1390, '1', 'Andy Windels Werk', '2024', '07', '15', '09', '00', '2024', '07', '15', '18', '15', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2024-06-20 15:39:48', '2024-06-20 15:39:48'),
(1391, '1', 'Andy Windels Werk', '2024', '07', '16', '09', '00', '2024', '07', '16', '18', '15', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2024-06-20 15:40:43', '2024-06-20 15:40:43'),
(1392, '1', 'Andy Windels Werk', '2024', '07', '17', '09', '00', '2024', '07', '17', '18', '15', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2024-06-20 15:41:33', '2024-06-20 15:41:33'),
(1393, '2', 'Henri Cools Werk', '2024', '07', '08', '09', '00', '2024', '07', '08', '18', '30', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-06-25 19:46:55', '2024-06-25 19:46:55'),
(1394, '2', 'Henri Cools Werk', '2024', '07', '09', '08', '00', '2024', '07', '09', '15', '30', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-06-25 19:47:44', '2024-06-25 19:47:44'),
(1395, '2', 'Henri Cools Werk', '2024', '07', '10', '09', '00', '2024', '07', '10', '18', '30', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-06-25 19:48:26', '2024-06-25 19:48:26'),
(1396, '2', 'Henri Cools Werk', '2024', '07', '12', '08', '00', '2024', '07', '12', '17', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-06-25 19:49:16', '2024-06-25 19:49:16'),
(1397, '1', 'Andy Windels Werk', '2024', '07', '29', '09', '00', '2024', '07', '29', '18', '15', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2024-07-10 08:41:24', '2024-07-10 08:41:24'),
(1398, '1', 'Andy Windels Werk', '2024', '07', '30', '09', '00', '2024', '07', '30', '18', '15', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2024-07-10 08:41:58', '2024-07-10 08:41:58'),
(1399, '1', 'Andy Windels Werk', '2024', '07', '31', '09', '00', '2024', '07', '31', '18', '15', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2024-07-10 08:42:32', '2024-07-10 08:42:32'),
(1400, '1', 'Markt hasselt', '2024', '07', '13', '12', '00', '2024', '07', '13', '18', '00', 'false', '', 'Met bedrijf op de markt. ', '', '', '#00a65a', 'Toegevoegd', '2024-07-10 08:44:01', '2024-07-10 08:44:01'),
(1401, '1', 'Markt pelt', '2024', '07', '22', '17', '00', '2024', '07', '22', '22', '00', 'false', '', 'Met bedrijf op de markt ', '', '', '#00a65a', 'Toegevoegd', '2024-07-10 08:44:49', '2024-07-10 08:44:49'),
(1402, '1', 'Andy Windels Werk', '2024', '08', '05', '09', '00', '2024', '08', '05', '18', '15', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2024-07-11 20:31:22', '2024-07-11 20:31:22'),
(1403, '1', 'Andy Windels Werk', '2024', '08', '06', '09', '00', '2024', '08', '06', '18', '15', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2024-07-11 20:32:03', '2024-07-11 20:32:03'),
(1404, '1', 'Andy Windels Werk', '2024', '08', '07', '09', '00', '2024', '08', '07', '18', '15', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2024-07-11 20:32:53', '2024-07-11 20:32:53'),
(1405, '1', 'Andy Windels Werk', '2024', '08', '12', '09', '00', '2024', '08', '12', '18', '00', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2024-07-11 20:33:39', '2024-07-11 20:33:39'),
(1406, '1', 'Andy Windels Werk', '2024', '08', '13', '09', '00', '2024', '08', '13', '18', '00', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2024-07-11 20:34:11', '2024-07-11 20:34:11'),
(1407, '1', 'Andy Windels Werk', '2024', '08', '14', '08', '00', '2024', '08', '14', '17', '00', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2024-07-11 20:34:46', '2024-07-11 20:34:46'),
(1408, '2', 'Henri Cools Werk', '2024', '07', '15', '08', '00', '2024', '07', '15', '18', '30', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-07-11 20:37:34', '2024-07-11 20:37:34'),
(1409, '2', 'Henri Cools Werk', '2024', '07', '17', '09', '00', '2024', '07', '17', '18', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-07-11 20:38:21', '2024-07-11 20:38:21'),
(1410, '2', 'Henri Cools Werk', '2024', '07', '18', '09', '00', '2024', '07', '18', '18', '30', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-07-11 20:39:19', '2024-07-11 20:39:19'),
(1411, '2', 'Henri Cools Werk', '2024', '07', '20', '09', '00', '2024', '07', '20', '18', '30', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-07-11 20:40:25', '2024-07-11 20:40:25'),
(1412, '2', 'Henri Cools Werk', '2024', '07', '29', '09', '00', '2024', '07', '29', '18', '30', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-07-11 20:42:27', '2024-07-11 20:42:27'),
(1413, '2', 'Henri Cools Werk', '2024', '07', '31', '09', '00', '2024', '07', '31', '18', '30', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-07-11 20:43:15', '2024-07-11 20:43:15'),
(1414, '2', 'Henri Cools Werk', '2024', '08', '01', '09', '00', '2024', '08', '01', '18', '30', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-07-11 20:44:07', '2024-07-11 20:44:07'),
(1415, '2', 'Henri Cools Werk', '2024', '08', '03', '09', '00', '2024', '08', '03', '18', '30', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-07-11 20:45:02', '2024-07-11 20:45:02'),
(1416, '1', 'Auto Andy onderhoud', '2024', '08', '29', '10', '00', '2024', '08', '29', '11', '00', 'false', '', 'Onderhoud auto Kia Lummens', '', '', '#00a65a', 'Toegevoegd', '2024-07-19 16:20:13', '2024-07-19 16:20:13'),
(1417, '1', 'Auto henri onderhoud', '2024', '08', '29', '08', '30', '2024', '08', '29', '10', '00', 'false', '', 'Onderhoud auto Kia lummens', '', '', '#00a65a', 'Toegevoegd', '2024-07-19 16:21:35', '2024-07-19 16:21:35'),
(1418, '2', 'Henri Cools Werk', '2024', '08', '05', '08', '00', '2024', '08', '05', '18', '30', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-07-28 09:35:02', '2024-07-28 09:35:02'),
(1419, '2', 'Henri Cools Werk', '2024', '08', '07', '09', '00', '2024', '08', '07', '18', '30', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-07-28 09:36:39', '2024-07-28 09:36:39'),
(1420, '2', 'Henri Cools Werk', '2024', '08', '08', '09', '00', '2024', '08', '08', '18', '30', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-07-28 09:37:45', '2024-07-28 09:37:45'),
(1421, '2', 'Henri Cools Werk', '2024', '08', '10', '09', '00', '2024', '08', '10', '18', '30', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-07-28 09:38:25', '2024-07-28 09:38:25'),
(1422, '2', 'Henri Cools Werk', '2024', '08', '12', '08', '00', '2024', '08', '12', '18', '30', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-07-28 09:39:23', '2024-07-28 09:39:23'),
(1423, '2', 'Henri Cools Werk', '2024', '08', '13', '08', '00', '2024', '08', '13', '17', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-07-28 09:40:08', '2024-07-28 09:40:08'),
(1424, '2', 'Henri Cools Werk', '2024', '08', '14', '08', '00', '2024', '08', '14', '17', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-07-28 09:41:12', '2024-07-28 09:41:12'),
(1425, '2', 'Henri Cools Werk', '2024', '08', '15', '08', '00', '2024', '08', '15', '17', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-07-28 09:41:48', '2024-07-28 09:41:48'),
(1426, '1', 'Andy Windels Werk', '2024', '08', '26', '08', '00', '2024', '08', '26', '17', '00', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2024-08-05 08:45:00', '2024-08-05 08:45:00'),
(1427, '1', 'Andy Windels Werk', '2024', '08', '27', '09', '00', '2024', '08', '27', '18', '00', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2024-08-05 08:45:40', '2024-08-05 08:45:40'),
(1428, '1', 'Andy Windels Werk', '2024', '08', '28', '09', '00', '2024', '08', '28', '18', '00', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2024-08-05 08:46:08', '2024-08-05 08:46:08'),
(1429, '1', 'Andy Windels Werk', '2024', '09', '02', '08', '00', '2024', '09', '02', '17', '00', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2024-08-05 08:46:44', '2024-08-05 08:46:44'),
(1430, '1', 'Andy Windels Werk', '2024', '09', '03', '09', '00', '2024', '09', '03', '18', '00', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2024-08-05 08:47:20', '2024-08-05 08:47:20'),
(1431, '1', 'Andy Windels Werk', '2024', '09', '04', '09', '00', '2024', '09', '04', '18', '00', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2024-08-05 08:47:54', '2024-08-05 08:47:54'),
(1432, '1', 'Henri Cools Werk', '2024', '08', '18', '12', '00', '2024', '08', '18', '18', '30', 'false', 'Valkenswaard, Nederland', '', '2', '2', '#007bff', 'Toegevoegd', '2024-08-12 07:58:21', '2024-08-12 07:58:21'),
(1433, '2', 'Henri Cools Werk', '2024', '08', '26', '09', '00', '2024', '08', '26', '18', '30', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-08-18 08:27:12', '2024-08-18 08:27:12'),
(1434, '2', 'Henri Cools Werk', '2024', '08', '27', '09', '00', '2024', '08', '27', '18', '30', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-08-18 08:27:51', '2024-08-18 08:27:51'),
(1435, '2', 'Henri Cools Werk', '2024', '08', '30', '08', '00', '2024', '08', '30', '17', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-08-18 08:28:41', '2024-08-18 08:28:41'),
(1436, '2', 'Henri Cools Werk', '2024', '08', '31', '09', '00', '2024', '08', '31', '18', '30', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-08-18 08:29:27', '2024-08-18 08:29:27'),
(1437, '2', 'Henri Cools Werk', '2024', '09', '02', '09', '00', '2024', '09', '02', '18', '30', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-08-18 08:30:34', '2024-08-18 08:30:34'),
(1438, '2', 'Henri Cools Werk', '2024', '09', '05', '09', '00', '2024', '09', '05', '18', '30', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-08-18 08:31:26', '2024-08-18 08:31:26'),
(1439, '2', 'Henri Cools Werk', '2024', '09', '06', '08', '00', '2024', '09', '06', '17', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-08-18 08:32:06', '2024-08-18 08:32:06'),
(1440, '2', 'Henri Cools Werk', '2024', '09', '07', '09', '00', '2024', '09', '07', '18', '30', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-08-18 08:32:54', '2024-08-18 08:32:54'),
(1441, '1', 'Andy Windels Werk', '2024', '09', '09', '09', '00', '2024', '09', '09', '18', '00', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2024-08-25 20:44:49', '2024-08-25 20:44:49'),
(1442, '1', 'Andy Windels Werk', '2024', '09', '10', '09', '00', '2024', '09', '10', '18', '00', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2024-08-25 20:45:25', '2024-08-25 20:45:25'),
(1443, '1', 'Andy Windels Werk', '2024', '09', '11', '09', '00', '2024', '09', '11', '18', '00', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2024-08-25 20:46:06', '2024-08-25 20:46:06'),
(1444, '1', 'Andy Windels Werk', '2024', '09', '16', '09', '00', '2024', '09', '16', '18', '00', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2024-08-25 20:47:12', '2024-08-25 20:47:12'),
(1445, '1', 'Andy Windels Werk', '2024', '09', '17', '09', '00', '2024', '09', '17', '18', '00', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2024-08-25 20:47:47', '2024-08-25 20:47:47'),
(1446, '1', 'Andy Windels Werk', '2024', '09', '18', '08', '00', '2024', '09', '18', '17', '00', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2024-08-25 20:48:23', '2024-08-25 20:48:23'),
(1447, '1', 'Andy Windels Werk', '2024', '09', '23', '09', '00', '2024', '09', '23', '18', '15', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2024-08-28 15:35:12', '2024-08-28 15:35:12'),
(1448, '1', 'Andy Windels Werk', '2024', '09', '24', '09', '00', '2024', '09', '24', '18', '15', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#ffc107', 'Gewijzigd', '2024-08-28 15:35:51', '2024-08-28 15:37:33'),
(1449, '1', 'Andy Windels Werk', '2024', '09', '25', '09', '00', '2024', '09', '25', '18', '15', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2024-08-28 15:36:29', '2024-08-28 15:36:29'),
(1450, '2', 'Henri Cools Werk', '2024', '09', '09', '08', '00', '2024', '09', '09', '17', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-08-28 16:14:45', '2024-08-28 16:14:45'),
(1451, '2', 'Henri Cools Werk', '2024', '09', '10', '08', '00', '2024', '09', '10', '17', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-08-28 16:15:48', '2024-08-28 16:15:48'),
(1452, '2', 'Henri Cools Werk', '2024', '09', '11', '08', '00', '2024', '09', '11', '17', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-08-28 16:16:19', '2024-08-28 16:16:19'),
(1453, '2', 'Henri Cools Werk', '2024', '09', '12', '08', '00', '2024', '09', '12', '17', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-08-28 16:16:51', '2024-08-28 16:16:51'),
(1454, '2', 'Henri Cools Werk', '2024', '09', '16', '08', '00', '2024', '09', '16', '17', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Gewijzigd', '2024-08-28 16:17:51', '2024-08-28 16:18:49'),
(1455, '2', 'Henri Cools Werk', '2024', '09', '18', '08', '00', '2024', '09', '18', '17', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-08-28 16:19:25', '2024-08-28 16:19:25'),
(1456, '2', 'Henri Cools Werk', '2024', '09', '19', '08', '00', '2024', '09', '19', '17', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-08-28 16:20:06', '2024-08-28 16:20:06'),
(1457, '2', 'Henri Cools Werk', '2024', '09', '20', '08', '00', '2024', '09', '20', '17', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-08-28 16:20:42', '2024-08-28 16:20:42'),
(1458, '1', 'Andy Windels Werk', '2024', '09', '30', '09', '00', '2024', '09', '30', '18', '00', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2024-09-08 21:51:44', '2024-09-08 21:51:44');
INSERT INTO `evenement` (`id`, `userid`, `title`, `bjaar`, `bmaand`, `bdag`, `buur`, `bmin`, `ejaar`, `emaand`, `edag`, `euur`, `emin`, `fullday`, `locatie`, `text`, `groupsen`, `usersids`, `color`, `status`, `date`, `last_date`) VALUES
(1459, '1', 'Andy Windels Werk', '2024', '10', '01', '09', '00', '2024', '10', '01', '18', '00', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2024-09-08 21:52:24', '2024-09-08 21:52:24'),
(1460, '1', 'Andy Windels Werk', '2024', '10', '02', '08', '00', '2024', '10', '02', '17', '00', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2024-09-08 21:52:59', '2024-09-08 21:52:59'),
(1461, '1', 'Andy Windels Werk', '2024', '10', '07', '09', '00', '2024', '10', '07', '18', '00', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2024-09-16 18:52:48', '2024-09-16 18:52:48'),
(1462, '1', 'Andy Windels Werk', '2024', '10', '08', '09', '00', '2024', '10', '08', '18', '00', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2024-09-16 18:53:34', '2024-09-16 18:53:34'),
(1463, '1', 'Andy Windels Werk', '2024', '10', '09', '09', '00', '2024', '10', '09', '18', '00', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2024-09-16 18:54:16', '2024-09-16 18:54:16'),
(1464, '2', 'Henri Cools Werk', '2024', '09', '23', '09', '00', '2024', '09', '23', '18', '30', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-09-17 17:46:07', '2024-09-17 17:46:07'),
(1465, '2', 'Henri Cools Werk', '2024', '09', '24', '08', '00', '2024', '09', '24', '17', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-09-17 17:46:55', '2024-09-17 17:46:55'),
(1466, '2', 'Henri Cools Werk', '2024', '09', '25', '08', '00', '2024', '09', '25', '17', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-09-17 17:47:32', '2024-09-17 17:47:32'),
(1467, '2', 'Henri Cools Werk', '2024', '09', '26', '08', '00', '2024', '09', '26', '17', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-09-17 17:48:09', '2024-09-17 17:48:09'),
(1468, '2', 'Henri Cools Werk', '2024', '09', '30', '08', '00', '2024', '09', '30', '17', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-09-22 09:28:06', '2024-09-22 09:28:06'),
(1469, '2', 'Henri Cools Werk', '2024', '10', '01', '08', '00', '2024', '10', '01', '17', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-09-22 09:28:45', '2024-09-22 09:28:45'),
(1470, '2', 'Henri Cools Werk', '2024', '10', '02', '08', '00', '2024', '10', '02', '17', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-09-22 09:29:21', '2024-09-22 09:29:21'),
(1471, '2', 'Henri ziekenhuis ', '2024', '10', '04', '10', '30', '2024', '10', '04', '12', '00', 'false', '', ' ', '', '', '#f56954', 'Toegevoegd', '2024-09-22 09:30:52', '2024-09-22 09:30:52'),
(1472, '2', 'Henri Cools Werk', '2024', '10', '06', '09', '30', '2024', '10', '06', '18', '30', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-09-22 09:31:41', '2024-09-22 09:31:41'),
(1473, '1', 'Andy Windels Werk', '2024', '10', '21', '09', '00', '2024', '10', '21', '18', '00', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2024-09-30 15:16:47', '2024-09-30 15:16:47'),
(1474, '1', 'Andy Windels Werk', '2024', '10', '22', '09', '00', '2024', '10', '22', '18', '00', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2024-09-30 15:17:52', '2024-09-30 15:17:52'),
(1475, '1', 'Andy Windels Werk', '2024', '10', '23', '09', '00', '2024', '10', '23', '18', '00', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2024-09-30 15:18:35', '2024-09-30 15:18:35'),
(1476, '1', 'Andy Windels Werk', '2024', '10', '16', '09', '00', '2024', '10', '16', '18', '00', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2024-09-30 15:19:34', '2024-09-30 15:19:34'),
(1477, '1', 'Andy Windels Werk', '2024', '10', '15', '09', '00', '2024', '10', '15', '18', '00', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2024-09-30 15:20:27', '2024-09-30 15:20:27'),
(1478, '1', 'Andy Windels Werk', '2024', '10', '14', '09', '00', '2024', '10', '14', '18', '00', 'false', 'Aalst-waarle, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2024-09-30 15:21:02', '2024-09-30 15:21:02'),
(1479, '1', 'Andy Windels Werk', '2024', '10', '28', '08', '00', '2024', '10', '28', '17', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#ffc107', 'Gewijzigd', '2024-09-30 15:21:49', '2024-09-30 15:24:46'),
(1480, '1', 'Andy Windels Werk', '2024', '10', '29', '08', '00', '2024', '10', '29', '17', '00', 'false', 'Valkenswaard, Nederland', '', '2', '', '#ffc107', 'Toegevoegd', '2024-09-30 15:22:45', '2024-09-30 15:22:45'),
(1481, '1', 'Andy Windels Werk', '2024', '10', '30', '08', '00', '2024', '10', '30', '17', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2024-09-30 15:23:42', '2024-09-30 15:23:42'),
(1483, '2', 'Henri Cools Werk', '2024', '10', '07', '08', '00', '2024', '10', '07', '17', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-09-30 17:37:03', '2024-09-30 17:37:03'),
(1484, '2', 'Henri Cools Werk', '2024', '10', '09', '08', '00', '2024', '10', '09', '17', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-09-30 17:37:43', '2024-09-30 17:37:43'),
(1485, '2', 'Henri Cools Werk', '2024', '10', '10', '08', '00', '2024', '10', '10', '17', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-09-30 17:38:26', '2024-09-30 17:38:26'),
(1486, '2', 'Henri Cools Werk', '2024', '10', '12', '09', '00', '2024', '10', '12', '18', '30', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-09-30 17:39:17', '2024-09-30 17:39:17'),
(1487, '2', 'Henri Cools Werk', '2024', '10', '14', '08', '00', '2024', '10', '14', '17', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-09-30 17:40:24', '2024-09-30 17:40:24'),
(1488, '2', 'Henri Cools Werk', '2024', '10', '15', '08', '00', '2024', '10', '15', '17', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-09-30 17:41:20', '2024-09-30 17:41:20'),
(1489, '2', 'Henri Cools Werk', '2024', '10', '17', '08', '00', '2024', '10', '17', '17', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-09-30 17:42:11', '2024-09-30 17:42:11'),
(1490, '2', 'Henri Cools Werk', '2024', '10', '18', '08', '00', '2024', '10', '18', '17', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-09-30 17:42:52', '2024-09-30 17:42:52'),
(1491, '2', 'Henri Cools Werk', '2024', '10', '21', '09', '00', '2024', '10', '21', '18', '30', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-09-30 17:43:43', '2024-09-30 17:43:43'),
(1492, '2', 'Henri Cools Werk', '2024', '10', '22', '08', '00', '2024', '10', '22', '17', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-09-30 17:44:37', '2024-09-30 17:44:37'),
(1493, '2', 'Henri Cools Werk', '2024', '10', '25', '08', '00', '2024', '10', '25', '17', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-09-30 17:45:34', '2024-09-30 17:45:34'),
(1494, '2', 'Henri Cools Werk', '2024', '10', '26', '08', '00', '2024', '10', '26', '17', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Gewijzigd', '2024-09-30 17:46:14', '2024-10-20 08:28:02'),
(1495, '2', 'Henri Cools Werk', '2024', '10', '28', '08', '00', '2024', '10', '28', '17', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-09-30 17:47:13', '2024-09-30 17:47:13'),
(1497, '2', 'Henri Cools Werk', '2024', '10', '30', '08', '00', '2024', '10', '30', '17', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-09-30 17:48:31', '2024-09-30 17:48:31'),
(1498, '2', 'Henri Cools Werk', '2024', '11', '01', '08', '00', '2024', '11', '01', '17', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-09-30 17:49:31', '2024-09-30 17:49:31'),
(1499, '2', 'Henri Cools Werk', '2024', '11', '04', '08', '00', '2024', '11', '04', '17', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-10-20 08:25:00', '2024-10-20 08:25:00'),
(1500, '2', 'Henri Cools Werk', '2024', '11', '07', '08', '00', '2024', '11', '07', '17', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-10-20 08:25:44', '2024-10-20 08:25:44'),
(1501, '2', 'Henri Cools Werk', '2024', '11', '08', '08', '00', '2024', '11', '08', '17', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-10-20 08:26:20', '2024-10-20 08:26:20'),
(1502, '2', 'Henri Cools Werk', '2024', '11', '09', '09', '00', '2024', '11', '09', '18', '30', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-10-20 08:27:14', '2024-10-20 08:27:14'),
(1503, '1', 'Andy Windels Werk', '2024', '11', '04', '08', '00', '2024', '11', '04', '17', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2024-10-20 08:30:24', '2024-10-20 08:30:24'),
(1504, '1', 'Andy Windels Werk', '2024', '11', '05', '08', '00', '2024', '11', '05', '17', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2024-10-20 08:31:02', '2024-10-20 08:31:02'),
(1505, '1', 'Andy Windels Werk', '2024', '11', '06', '08', '00', '2024', '11', '06', '17', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#ffc107', 'Gewijzigd', '2024-10-20 08:31:37', '2024-11-15 20:09:21'),
(1506, '2', 'Henri Cools Werk', '2024', '11', '02', '08', '00', '2024', '11', '02', '18', '30', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-10-21 13:05:57', '2024-10-21 13:05:57'),
(1507, '1', 'Garage kia', '2024', '11', '21', '15', '00', '2024', '11', '21', '16', '30', 'false', 'Pelt', 'Na zicht keuring plus nieuwe banden Auto Andy. ', '1', '1', '#00a65a', 'Toegevoegd', '2024-10-25 11:05:24', '2024-10-25 11:05:24'),
(1508, '1', 'Andy Windels Werk', '2024', '11', '11', '08', '00', '2024', '11', '11', '17', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2024-10-27 16:32:19', '2024-10-27 16:32:19'),
(1509, '1', 'Andy Windels Werk', '2024', '11', '12', '08', '00', '2024', '11', '12', '17', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2024-10-27 16:33:12', '2024-10-27 16:33:12'),
(1510, '2', 'Henri Cools Werk', '2024', '11', '12', '09', '00', '2024', '11', '12', '18', '30', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-10-27 16:33:19', '2024-10-27 16:33:19'),
(1511, '1', 'Andy Windels Werk', '2024', '11', '13', '08', '00', '2024', '11', '13', '17', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2024-10-27 16:34:03', '2024-10-27 16:34:03'),
(1512, '2', 'Henri Cools Werk', '2024', '11', '13', '09', '00', '2024', '11', '13', '18', '30', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-10-27 16:34:34', '2024-10-27 16:34:34'),
(1513, '2', 'Henri Cools Werk', '2024', '11', '14', '09', '00', '2024', '11', '14', '18', '30', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-10-27 16:35:42', '2024-10-27 16:35:42'),
(1514, '2', 'Henri Cools Werk', '2024', '11', '15', '08', '00', '2024', '11', '15', '17', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-10-27 16:37:08', '2024-10-27 16:37:08'),
(1515, '1', 'Andy Windels Werk', '2024', '11', '18', '09', '00', '2024', '11', '18', '18', '30', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2024-10-28 21:46:56', '2024-10-28 21:46:56'),
(1516, '1', 'Andy Windels Werk', '2024', '11', '19', '08', '00', '2024', '11', '19', '17', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2024-10-28 21:47:33', '2024-10-28 21:47:33'),
(1517, '1', 'Andy Windels Werk', '2024', '11', '20', '09', '00', '2024', '11', '20', '18', '30', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2024-10-28 21:48:17', '2024-10-28 21:48:17'),
(1518, '2', 'Henri Cools Werk', '2024', '11', '18', '09', '00', '2024', '11', '18', '18', '30', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-10-31 16:56:55', '2024-10-31 16:56:55'),
(1519, '2', 'Henri Cools Werk', '2024', '11', '19', '08', '00', '2024', '11', '19', '17', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-10-31 16:58:01', '2024-10-31 16:58:01'),
(1520, '2', 'Henri Cools Werk', '2024', '11', '22', '08', '00', '2024', '11', '22', '17', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-10-31 16:58:56', '2024-10-31 16:58:56'),
(1521, '2', 'Henri Cools Werk', '2024', '11', '23', '08', '00', '2024', '11', '23', '17', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-10-31 16:59:33', '2024-10-31 16:59:33'),
(1522, '1', 'Matthias Geilen - webcrafters', '2024', '11', '08', '16', '45', '2024', '11', '08', '18', '00', 'false', 'Kleine Fonteinstraat 9 Bocholt ', 'Bespreking en kennis making voor bekijken om een webshop te bouwen voor bedrijf. ', '2', '1', '#00a65a', 'Toegevoegd', '2024-11-02 12:52:42', '2024-11-02 12:52:42'),
(1523, '1', 'Ben - websitebouwer', '2024', '11', '14', '10', '00', '2024', '11', '14', '11', '30', 'false', 'Kapelanijstraat 1, 3900 Pelt België', 'Vrijblijvende Bespreking en offerte opmaken website. ', '2', '1', '#00a65a', 'Toegevoegd', '2024-11-04 19:43:41', '2024-11-04 19:43:41'),
(1524, '1', 'Andy Windels Werk', '2024', '11', '25', '09', '00', '2024', '11', '25', '18', '30', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2024-11-11 06:39:00', '2024-11-11 06:39:00'),
(1525, '1', 'Andy Windels Werk', '2024', '11', '26', '08', '00', '2024', '11', '26', '17', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2024-11-11 06:40:12', '2024-11-11 06:40:12'),
(1526, '1', 'Andy Windels Werk', '2024', '11', '27', '08', '00', '2024', '11', '27', '17', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2024-11-11 06:41:04', '2024-11-11 06:41:04'),
(1527, '2', 'Henri Cools Werk', '2024', '11', '25', '09', '00', '2024', '11', '25', '18', '30', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-11-16 09:19:48', '2024-11-16 09:19:48'),
(1528, '2', 'Henri Cools Werk', '2024', '11', '25', '09', '00', '2024', '11', '25', '18', '30', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-11-16 09:20:51', '2024-11-16 09:20:51'),
(1529, '2', 'Henri Cools Werk', '2024', '11', '27', '08', '00', '2024', '11', '27', '17', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-11-16 09:21:32', '2024-11-16 09:21:32'),
(1530, '2', 'Henri Cools Werk', '2024', '11', '28', '08', '00', '2024', '11', '28', '17', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-11-16 09:22:15', '2024-11-16 09:22:15'),
(1531, '2', 'Henri Cools Werk', '2024', '11', '30', '09', '00', '2024', '11', '30', '18', '30', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-11-16 09:23:01', '2024-11-16 09:23:01'),
(1532, '2', 'Henri Cools Werk', '2024', '12', '02', '09', '00', '2024', '12', '02', '18', '30', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-11-16 09:24:30', '2024-11-16 09:24:30'),
(1533, '1', 'Andy Windels Werk', '2024', '12', '02', '09', '00', '2024', '12', '02', '18', '30', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2024-11-16 09:25:23', '2024-11-16 09:25:23'),
(1534, '2', 'Henri Cools Werk', '2024', '12', '03', '08', '00', '2024', '12', '03', '17', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-11-16 09:25:24', '2024-11-16 09:25:24'),
(1535, '2', 'Henri Cools Werk', '2024', '12', '05', '08', '00', '2024', '12', '05', '17', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-11-16 09:26:07', '2024-11-16 09:26:07'),
(1536, '2', 'Henri Cools Werk', '2024', '12', '07', '09', '00', '2024', '12', '07', '18', '30', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-11-16 09:26:54', '2024-11-16 09:26:54'),
(1537, '1', 'Andy Windels Werk', '2024', '12', '03', '08', '00', '2024', '12', '03', '17', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2024-11-16 09:27:20', '2024-11-16 09:27:20'),
(1538, '1', 'Andy Windels Werk', '2024', '12', '04', '08', '00', '2024', '12', '04', '17', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#ffc107', 'Toegevoegd', '2024-11-16 09:28:10', '2024-11-16 09:28:10'),
(1539, '1', 'Kerstmarkt Oudsbergen', '2024', '12', '21', '14', '00', '2024', '12', '21', '21', '00', 'false', 'Oudsbergen', '', '7', '1, 2, 3, 5, 6', '#00a65a', 'Toegevoegd', '2024-11-17 16:45:21', '2024-11-17 16:45:21'),
(1540, '1', 'Kerstmarkt oudsbergen', '2024', '12', '22', '10', '00', '2024', '12', '22', '18', '00', 'false', 'Oudsbergen', '', '7', '1, 2, 3, 4, 5, 6', '#00a65a', 'Toegevoegd', '2024-11-17 17:11:48', '2024-11-17 17:11:48'),
(1541, '1', 'kerstmarkt Hamont (carrefour)', '2024', '12', '28', '10', '00', '2024', '12', '28', '16', '00', 'false', 'Hamont-Achel', '', '7', '1, 2, 3, 4, 5, 6', '#00a65a', 'Toegevoegd', '2024-11-17 17:16:07', '2024-11-17 17:16:07'),
(1542, '1', 'Andy Windels Werk', '2024', '12', '09', '09', '00', '2024', '12', '09', '18', '30', 'false', 'Valkenswaard, Nederland', '', '2', '', '#ffc107', 'Toegevoegd', '2024-11-27 06:32:13', '2024-11-27 06:32:13'),
(1543, '1', 'Andy Windels Werk', '2024', '12', '10', '08', '00', '2024', '12', '10', '17', '00', 'false', 'Valkenswaard, Nederland', '', '2', '', '#ffc107', 'Toegevoegd', '2024-11-27 06:32:48', '2024-11-27 06:32:48'),
(1544, '1', 'Andy Windels Werk', '2024', '12', '11', '08', '00', '2024', '12', '11', '17', '00', 'false', 'Valkenswaard, Nederland', '', '2', '', '#ffc107', 'Toegevoegd', '2024-11-27 06:33:22', '2024-11-27 06:33:22'),
(1545, '1', 'Andy Windels Werk', '2024', '12', '16', '09', '00', '2024', '12', '16', '18', '30', 'false', 'Valkenswaard, Nederland', '', '2', '', '#ffc107', 'Toegevoegd', '2024-11-27 06:34:13', '2024-11-27 06:34:13'),
(1546, '1', 'Andy Windels Werk', '2024', '12', '17', '08', '00', '2024', '12', '17', '17', '00', 'false', 'Valkenswaard, Nederland', '', '2', '', '#ffc107', 'Toegevoegd', '2024-11-27 06:34:53', '2024-11-27 06:34:53'),
(1547, '1', 'Andy Windels Werk', '2024', '12', '18', '08', '00', '2024', '12', '18', '17', '00', 'false', 'Valkenswaard, Nederland', '', '2', '', '#ffc107', 'Toegevoegd', '2024-11-27 06:35:20', '2024-11-27 06:35:20'),
(1548, '2', 'Henri Cools Werk', '2024', '12', '10', '08', '00', '2024', '12', '10', '17', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-11-27 06:37:09', '2024-11-27 06:37:09'),
(1549, '2', 'Henri Cools Werk', '2024', '12', '11', '08', '00', '2024', '12', '11', '17', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-11-27 06:37:58', '2024-11-27 06:37:58'),
(1550, '2', 'Henri Cools Werk', '2024', '12', '12', '08', '00', '2024', '12', '12', '17', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-11-27 06:38:47', '2024-11-27 06:38:47'),
(1551, '2', 'Henri Cools Werk', '2024', '12', '13', '08', '00', '2024', '12', '13', '17', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-11-27 06:39:50', '2024-11-27 06:39:50'),
(1552, '2', 'Henri Cools Werk', '2024', '12', '16', '09', '00', '2024', '12', '16', '18', '30', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-11-27 06:41:30', '2024-11-27 06:41:30'),
(1553, '2', 'Henri Cools Werk', '2024', '12', '17', '08', '00', '2024', '12', '17', '17', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-11-27 06:42:15', '2024-11-27 06:42:15'),
(1554, '2', 'Henri Cools Werk', '2024', '12', '19', '08', '00', '2024', '12', '19', '17', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-11-27 06:43:12', '2024-11-27 06:43:12'),
(1555, '2', 'Henri Cools Werk', '2024', '12', '20', '08', '00', '2024', '12', '20', '17', '00', 'false', 'Valkenswaard, Nederland', ' ', '2', '', '#007bff', 'Toegevoegd', '2024-11-27 06:43:50', '2024-11-27 06:43:50');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `image`
--

CREATE TABLE `image` (
  `id` int NOT NULL,
  `title_image` varchar(255) NOT NULL,
  `url_image` text NOT NULL,
  `album_image` varchar(255) NOT NULL,
  `userid` varchar(255) NOT NULL,
  `image_upload` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Gegevens worden geëxporteerd voor tabel `image`
--

INSERT INTO `image` (`id`, `title_image`, `url_image`, `album_image`, `userid`, `image_upload`) VALUES
(1, 'image1', 'p1080235.JPG', '1', '1', '2022-12-05 17:16:04'),
(2, 'image2', 'p1080276.JPG', '1', '1', '2022-12-05 17:16:04'),
(3, 'image3', 'p1080277.JPG', '1', '1', '2022-12-05 17:16:04'),
(4, 'image4', 'p1080281.JPG', '1', '1', '2022-12-05 17:16:04'),
(6, 'image6', 'p1080346.JPG', '1', '1', '2022-12-05 17:16:04'),
(7, 'image7', 'p1080220.JPG', '1', '1', '2022-12-05 17:16:04'),
(9, 'image9', 'p1080297.JPG', '1', '1', '2022-12-05 17:16:04'),
(10, 'image10', 'p1080309.JPG', '1', '1', '2022-12-05 17:16:04'),
(11, 'image11', 'p1080310.JPG', '1', '1', '2022-12-05 17:16:04'),
(12, 'image12', 'p1080327.JPG', '1', '1', '2022-12-05 17:16:04'),
(13, 'image13', 'p1080337.JPG', '1', '1', '2022-12-05 17:16:04'),
(14, 'image14', 'p1080350.JPG', '1', '1', '2022-12-05 17:16:04'),
(15, 'image15', 'p1080253.JPG', '1', '1', '2022-12-05 17:16:04'),
(16, 'image16', 'p1080275.JPG', '1', '1', '2022-12-05 17:16:04'),
(17, 'userimage6', 'avatar6.png', '2', 'all', '2022-12-05 17:16:04'),
(18, 'userimage2', 'avatar2.png', '2', 'all', '2022-12-05 17:16:04'),
(19, 'userimage3', 'avatar3.png', '2', 'all', '2022-12-05 17:16:04'),
(20, 'userimage4', 'avatar4.png', '2', 'all', '2022-12-05 17:16:04'),
(21, 'userimage5', 'avatar5.png', '2', 'all', '2022-12-05 17:16:04'),
(22, 'slideimage1', 'slider-01.jpg', '3', '1', '2022-12-05 17:16:04'),
(23, 'sliderimage2', 'slider-02.jpg', '3', '1', '2022-12-05 17:16:04'),
(24, 'sliderimage3', 'slider-03.jpg', '3', '1', '2022-12-05 17:16:04'),
(26, 'userimage937231021', 'IMG_1541.jpg', '2', '1', '2022-12-10 12:51:42'),
(27, 'userimage1', 'avatar1.png', '2', 'all', '2022-12-05 17:16:04');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `notify`
--

CREATE TABLE `notify` (
  `id` int NOT NULL,
  `title` varchar(255) NOT NULL,
  `evenement_id` varchar(255) NOT NULL,
  `cr_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Gegevens worden geëxporteerd voor tabel `notify`
--

INSERT INTO `notify` (`id`, `title`, `evenement_id`, `cr_date`) VALUES
(1, '1 nieuwe evenement toegevoegd', '1542', '2024-11-27 06:32:14'),
(2, '1 nieuwe evenement toegevoegd', '1543', '2024-11-27 06:32:48'),
(3, '1 nieuwe evenement toegevoegd', '1544', '2024-11-27 06:33:22'),
(4, '1 nieuwe evenement toegevoegd', '1545', '2024-11-27 06:34:13'),
(5, '1 nieuwe evenement toegevoegd', '1546', '2024-11-27 06:34:53'),
(6, '1 nieuwe evenement toegevoegd', '1547', '2024-11-27 06:35:20'),
(7, '1 nieuwe evenement toegevoegd', '1548', '2024-11-27 06:37:09'),
(8, '1 nieuwe evenement toegevoegd', '1549', '2024-11-27 06:37:58'),
(9, '1 nieuwe evenement toegevoegd', '1550', '2024-11-27 06:38:47'),
(10, '1 nieuwe evenement toegevoegd', '1551', '2024-11-27 06:39:50'),
(11, '1 nieuwe evenement toegevoegd', '1552', '2024-11-27 06:41:30'),
(12, '1 nieuwe evenement toegevoegd', '1553', '2024-11-27 06:42:15'),
(13, '1 nieuwe evenement toegevoegd', '1554', '2024-11-27 06:43:12'),
(14, '1 nieuwe evenement toegevoegd', '1555', '2024-11-27 06:43:50');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `notifystatus`
--

CREATE TABLE `notifystatus` (
  `id` int NOT NULL,
  `userid` varchar(255) NOT NULL,
  `notifyid` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `paying_off`
--

CREATE TABLE `paying_off` (
  `id` int NOT NULL,
  `date` varchar(255) NOT NULL,
  `user` varchar(255) NOT NULL,
  `userid` varchar(255) NOT NULL,
  `bedrag` varchar(255) NOT NULL,
  `notice` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `ontvangen` varchar(255) NOT NULL,
  `daterange` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Gegevens worden geëxporteerd voor tabel `paying_off`
--

INSERT INTO `paying_off` (`id`, `date`, `user`, `userid`, `bedrag`, `notice`, `status`, `ontvangen`, `daterange`) VALUES
(1, '02-07-2023', 'Martha Sturmans', '3', '628,20', '2023Jul- Afbetalinglening A005600W037', 'Betaald', '02-07-2023', '20230702'),
(2, '02-08-2023', 'Martha Sturmans', '3', '628,20', '2023Aug- Afbetalinglening A005600W037', 'Betaald', '02-08-2023', '20230802'),
(3, '02-09-2023', 'Martha Sturmans', '3', '628,20', '2023Sep- Afbetalinglening A005600W037', 'Betaald', '02-09-2023', '20230902'),
(4, '01-10-2023', 'Martha Sturmans', '3', '628,20', '2023Oct- Afbetalinglening A005600W037', 'Betaald', '02-10-2023', '20231001'),
(5, '02-11-2023', 'Martha Sturmans', '3', '628,20', '2023Nov- Afbetalinglening A005600W037', 'Betaald', '02-11-2023', '20231102'),
(6, '01-12-2023', 'Martha Sturmans', '3', '628,20', '2023Dec- Afbetalinglening A005600W037', 'Betaald', '02-12-2023', '20231201'),
(7, '01-01-2024', 'Martha Sturmans', '3', '628,20', '2024Jan- Afbetalinglening A005600W037', 'Betaald', '02-01-2024', '20240101'),
(8, '02-02-2024', 'Martha Sturmans', '3', '628,20', '2024Feb- Afbetalinglening A005600W037', 'Betaald', '03-02-2024', '20240202'),
(9, '01-03-2024', 'Martha Sturmans', '3', '628,20', '2024Mar- Afbetalinglening A005600W037', 'Betaald', '01-03-2024', '20240301'),
(10, '01-04-2024', 'Martha Sturmans', '3', '628,20', '2024Apr- Afbetalinglening A005600W037', 'Betaald', '01-04-2024', '20240401'),
(11, '02-05-2024', 'Martha Sturmans', '3', '628,20', '2024May- Afbetalinglening A005600W037', 'Betaald', '02-05-2024', '20240502'),
(12, '02-06-2024', 'Martha Sturmans', '3', '628,20', '2024Jun- Afbetalinglening A005600W037', 'Betaald', '02-06-2024', '20240602'),
(13, '02-07-2024', 'Martha Sturmans', '3', '628,20', '2024Jul- Afbetalinglening A005600W037', 'Betaald', '02-07-2024', '20240702'),
(14, '03-08-2024', 'Martha Sturmans', '3', '628,20', '2024Aug- Afbetalinglening A005600W037', 'Betaald', '03-08-2024', '20240803'),
(15, '02-09-2024', 'Martha Sturmans', '3', '628,20', '2024Sep- Afbetalinglening A005600W037', 'Betaald', '07-09-2024', '20240902'),
(16, '02-10-2024', 'Martha Sturmans', '3', '628,20', '2024Oct- Afbetalinglening A005600W037', 'Betaald', '02-10-2024', '20241002'),
(17, '02-11-2024', 'Martha Sturmans', '3', '628,20', '2024Nov- Afbetalinglening A005600W037', 'Betaald', '02-11-2024', '20241102'),
(18, '02-12-2024', 'Martha Sturmans', '3', '628.20', '2024Dec- Afbetalinglening A005600W037', 'Betaald', '02-12-2024', '20241202');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `slider`
--

CREATE TABLE `slider` (
  `id` int NOT NULL,
  `image1` int NOT NULL,
  `trans1` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `text1` varchar(300) NOT NULL,
  `text2` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Gegevens worden geëxporteerd voor tabel `slider`
--

INSERT INTO `slider` (`id`, `image1`, `trans1`, `text1`, `text2`) VALUES
(1, 22, 'zipReveal', 'Welkom op onze familie website.', ''),
(2, 23, 'blocksReveal', 'Bij de familie Cools - Windels zit je goed.', ''),
(3, 24, 'shrinkReveal', 'Wij maken de gezelligheid door warmte en veel liefde.', '');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `updates`
--

CREATE TABLE `updates` (
  `id` int NOT NULL,
  `version` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `text` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `updatedatum` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Gegevens worden geëxporteerd voor tabel `updates`
--

INSERT INTO `updates` (`id`, `version`, `text`, `updatedatum`) VALUES
(1, '0.0.1', '<p>&nbsp;&nbsp;<strong>&nbsp;Lancering&nbsp;Fam.&nbsp;Cools-Windels&nbsp;App.</strong></p>\r\n\r\n<p style=\"margin-left: 40px;\">Functie&#39;s&nbsp;in&nbsp;de&nbsp;app&nbsp;zijn.</p>\r\n\r\n<ul style=\"margin-left: 40px;\">\r\n	<li>Planning&nbsp;bekijken</li>\r\n	<li>Planning&nbsp;toevoegen,wijzigen&nbsp;en&nbsp;of&nbsp;verwijderen</li>\r\n	<li>Gebruikers&nbsp;toevoegen&nbsp;(&nbsp;enkel&nbsp;admin).</li>\r\n	<li>Email&nbsp;meldingen&nbsp;vesrturen&nbsp;naar&nbsp;gebruiker&nbsp;als&nbsp;er&nbsp;nieuwe&nbsp;planing,&nbsp;wijzingen&nbsp;of&nbsp;annulatie&nbsp;planningen&nbsp;zijn.</li>\r\n</ul>\r\n\r\n<p>&nbsp;</p>\r\n', '2021-06-11 13:32:38'),
(2, '0.0.2', '<p>&nbsp;&nbsp;<strong>&nbsp;Wijzegen&nbsp;Fam.&nbsp;Cools-Windels&nbsp;App.</strong></p>\r\n\r\n<p style=\"margin-left: 40px;\">Functie&#39;s&nbsp;in&nbsp;de&nbsp;app&nbsp;zijn.</p>\r\n\r\n<ul style=\"margin-left: 40px;\">\r\n	<li>Planning&nbsp;bekijken</li>\r\n	<li>Planning&nbsp;toevoegen,wijzigen&nbsp;en&nbsp;of&nbsp;verwijderen</li>\r\n	<li>Gebruikers&nbsp;toevoegen&nbsp;(&nbsp;enkel&nbsp;admin).</li>\r\n	<li>Email&nbsp;meldingen&nbsp;vesrturen&nbsp;naar&nbsp;gebruiker&nbsp;als&nbsp;er&nbsp;nieuwe&nbsp;planing,&nbsp;wijzingen&nbsp;of&nbsp;annulatie&nbsp;planningen&nbsp;zijn.</li>\r\n\r\n<li>Bug&nbsp;oplossen.</li>\r\n</ul>\r\n\r\n<p>&nbsp;</p>\r\n', '2021-07-18 08:12:32'),
(3, '0.0.3', '<p>&nbsp;&nbsp;<strong>&nbsp;Wijzegen&nbsp;Fam.&nbsp;Cools-Windels&nbsp;App.</strong></p>\r\n\r\n<p style=\"margin-left: 40px;\">Functie&#39;s&nbsp;in&nbsp;de&nbsp;app&nbsp;zijn.</p>\r\n\r\n<ul style=\"margin-left: 40px;\">\r\n\r\n<li>Bugs&nbsp;oplossen.</li>\r\n\r\n<li>Planning&nbsp;layout&nbsp;aanpassen.</li>\r\n\r\n</ul>\r\n\r\n<p>&nbsp;</p>\r\n', '2022-08-21 17:48:22'),
(4, '0.0.4', '<p>&nbsp;&nbsp;<strong>&nbsp;Wijzegen&nbsp;Fam.&nbsp;Cools-Windels&nbsp;App.</strong></p>\r\n\r\n<p style=\"margin-left: 40px;\">Nieuwe&nbsp;Functie&#39;s&nbsp;in&nbsp;de&nbsp;app&nbsp;zijn.</p>\r\n\r\n<ul style=\"margin-left: 40px;\">\r\n\r\n<li>Chatten&nbsp;met&nbsp;andere&nbsp;gebruikers. </li>\r\n\r\n<li>App&nbsp;instellingen&nbsp;toegevoegd.</li>\r\n\r\n</ul>\r\n\r\n<p>&nbsp;</p>\r\n', '2022-09-11 13:12:32'),
(5, '0.0.5', '<p>&nbsp;&nbsp;<strong>&nbsp;Wijzegen&nbsp;Fam.&nbsp;Cools-Windels&nbsp;App.</strong></p>\r\n\r\n<p style=\"margin-left: 40px;\">Nieuwe&nbsp;Functie&#39;s&nbsp;in&nbsp;de&nbsp;app&nbsp;zijn.</p>\r\n\r\n<ul style=\"margin-left: 40px;\">\r\n\r\n<li>Enkele&nbsp;bugsApp&nbsp;(fouten)&nbsp;opgelost.</li>\r\n\r\n<li>fout&nbsp;bij&nbsp;het&nbsp;instellen&nbsp;van&nbsp;mail&nbsp;meldingen&nbsp;in&nbsp;en&nbsp;uitschakelen&nbsp;in&nbsp;de&nbsp;app&nbsp;instellingen&nbsp;zijn&nbsp;opgelost.</li>\r\n\r\n<li>En&nbsp;nieuw&nbsp;functie&nbsp;menu&nbsp;balk&nbsp;vastzetten&nbsp;en&nbsp;of&nbsp;de&nbsp;app&nbsp;op&nbsp;donker&nbsp;scherm&nbsp;zetten.</li>\r\n\r\n</ul>\r\n\r\n<p>&nbsp;&nbsp;Alvast&nbsp;bedankt.</p>\r\n', '2021-10-10 18:56:23'),
(6, '0.0.6', '<p><meta charset=\"UTF-8\" />omschrijving</p>\n\n<p>&nbsp;&nbsp;<strong>&nbsp;Wijzegen&nbsp;Fam.&nbsp;Cools-Windels&nbsp;App.</strong></p>\n\n<p>Nieuwe&nbsp;Functie&#39;s&nbsp;in&nbsp;de&nbsp;app&nbsp;zijn.</p>\n\n<ul>\n	<li>Enkele&nbsp;bugsApp&nbsp;(fouten)&nbsp;opgelost.</li>\n	<li>Nieuwe functie prive documenten en foto toevoegen in de map documenten &nbsp;</li>\n</ul>\n\n<p>&nbsp;&nbsp;Alvast&nbsp;bedankt</p>\n', '2021-11-21 15:58:02'),
(7, '0.0.7', '<p><meta charset=\"UTF-8\" />omschrijving</p>\n\n<p>&nbsp;&nbsp;<strong>&nbsp;Wijzegen&nbsp;Fam.&nbsp;Cools-Windels&nbsp;App.</strong></p>\n\n<p>Gewijzigde&nbsp;Functie&#39;s&nbsp;in&nbsp;de&nbsp;app&nbsp;zijn.</p>\n\n<ul>\n	<li>Eigen agenda sluiten.</li>\n	<li>chate sluiten. En verwijderen.</li>\n        <li>Prive documenten sluiten. En verwijderen.</li>\n        <li>Alle documenten sluiten. En verwijderen.</li>\n        <li>Agenda cools-windels aangepast en bugs opgelost.</li>\n        <li>Agenda cools-windels Nieuwe evenement lijsten toegevoegd.</li>\n</ul>\n\n<p>&nbsp;&nbsp;Alvast&nbsp;bedankt</p>\n', '2022-02-13 09:18:14'),
(8, '0.0.8', '<p><meta charset=\"UTF-8\" />omschrijving</p>\n\n<p>&nbsp;&nbsp;<strong>&nbsp;Wijzegen&nbsp;Fam.&nbsp;Cools-Windels&nbsp;App.</strong></p>\n\n<p>Gewijzigde&nbsp;Functie&#39;s&nbsp;in&nbsp;de&nbsp;app&nbsp;zijn.</p>\n\n<ul>\n	<li>Lay-out uitzicht vernieuwd en bugs opgelost.</li>\n\n</ul>\n\n<p>&nbsp;&nbsp;Alvast&nbsp;bedankt</p>\n', '2022-06-05 09:37:52'),
(9, '0.0.9', '<p><meta charset=\"UTF-8\" />omschrijving</p>\r\n\r\n<p>&nbsp;&nbsp;<strong>&nbsp;Wijzegen&nbsp;Fam.&nbsp;Cools-Windels&nbsp;App.</strong></p>\r\n\r\n<p>Gewijzigde&nbsp;Functie&#39;s&nbsp;in&nbsp;de&nbsp;app&nbsp;zijn.</p>\r\n\r\n<ul>\r\n	<li>Het oplossen van bug fouten  en een verbetering in beveiliging.</li>\r\n<li>Nieuw chat functie. Je kan vanaf nu chatte met Andy voor al je hulp en/of problemen in de fam.cools-windels app</li>\r\n<li>Nieuw pagina voor bewerken van evenement</li>\r\n<li>Alle gebruikers kunnen nu een evenement toevoegen</li>\r\n<li>Er zijn nieuwe informatie aan evenement toegevoegd. Zoals locatie, schrijven van een opmerking.</li>\r\n</ul>\r\n\r\n<p>&nbsp;&nbsp;Alvast&nbsp;bedankt</p>\r\n', '2022-11-25 16:00:19'),
(10, '0.1.0', '<p><meta charset=\"UTF-8\" />omschrijving</p>\r\n\r\n<p>&nbsp;&nbsp;<strong>&nbsp;Wijzegen&nbsp;Fam.&nbsp;Cools-Windels&nbsp;App.</strong></p>\r\n\r\n<p>Gewijzigde&nbsp;Functie&#39;s&nbsp;in&nbsp;de&nbsp;app&nbsp;zijn.</p>\r\n\r\n<ul>\r\n	<li>Het oplossen van bug fouten  en een verbetering in beveiliging.</li>\r\n<li>Nieuw start scherm. Je nieuw start scherm is vanaf nu je eigen agenda van de dag zelf. en verteld jouw welke activiteiten je die dag hebt.</li>\r\n<li>Nieuw browser meldingen. Je krijgt vanaf nu een melding in jouw browser te zien als er nieuwe evenementen zijn toegevoegd, Gewijzigd of geannuleerd zijn. De meldingen worden automatisch verwijderd zodra je de pagina opent of je afmeld op de fam.cools-windels webapp.</li>\r\n<li>De menu balk is onzichtbaar geworden en kan zichtbaar gemaakt worden door een knop.</li>\r\n<li>Verbeterde login, afmeld en inactiviteit melding deze is gebruik vriendelijker gemaakt.</li>\r\n</ul>\r\n\r\n<p>Hopelijk vindt je deze update leuk.</p>\r\n<p>&nbsp;&nbsp;Alvast&nbsp;bedankt</p>\r\n', '2022-12-01 10:33:13'),
(11, '0.1.1', '<p><meta charset=\"UTF-8\" />omschrijving</p>\r\n\r\n<p>&nbsp;&nbsp;<strong>&nbsp;Wijzegen&nbsp;Fam.&nbsp;Cools-Windels&nbsp;App.</strong></p>\r\n\r\n<p>Gewijzigde&nbsp;Functie&#39;s&nbsp;in&nbsp;de&nbsp;app&nbsp;zijn.</p>\r\n\r\n<ul>\r\n<li>Het oplossen van bug fouten en een verbetering in beveiliging.</li>\r\n<li>Nieuw website scherm. Je nieuw start </li>\r\n</ul>\r\n\r\n<p>Hopelijk vindt je deze update leuk.</p>\r\n<p>&nbsp;&nbsp;Alvast&nbsp;bedankt</p>\r\n', '2022-12-01 10:33:13'),
(12, '0.1.2', '<p><meta charset=\"UTF-8\" />omschrijving</p>\n\n<p>&nbsp;&nbsp;<strong>&nbsp;Wijzegen&nbsp;Fam.&nbsp;Cools-Windels&nbsp;App.</strong></p>\n\n<p>Gewijzigde&nbsp;Functie&#39;s&nbsp;in&nbsp;de&nbsp;app&nbsp;zijn.</p>\n\n<ul>\n<li>Het afbetalingslijst hebben we een verbeterde sortering aangebracht. Nieuwe datums staan voortaan boven aan. Oudere datums volgen op elkaar naar beneden op. </li>\n<li>Het oplossen van bug fouten en een verbetering in beveiliging.</li>\n</ul>\n\n<p>Hopelijk vindt je deze update leuk.</p>\n<p>&nbsp;&nbsp;Alvast&nbsp;bedankt</p>\n', '2024-06-06 09:33:13'),
(13, '0.1.3', '<p><meta charset=\"UTF-8\" />omschrijving</p>\n\n<p>&nbsp;&nbsp;<strong>&nbsp;Wijzegen&nbsp;Fam.&nbsp;Cools-Windels&nbsp;App.</strong></p>\n\n<p>Gewijzigde&nbsp;Functie&#39;s&nbsp;in&nbsp;de&nbsp;app&nbsp;zijn.</p>\n\n<ul>\n<li>Het oplossen van bug fouten en een verbetering in beveiliging.</li>\n</ul>\n\n<p>Hopelijk vindt je deze update leuk.</p>\n<p>&nbsp;&nbsp;Alvast&nbsp;bedankt</p>\n', '2024-06-20 10:53:03'),
(14, '0.2.0', '<p><meta charset=\"UTF-8\" />omschrijving</p>\r\n\r\n<p>&nbsp;&nbsp;<strong>&nbsp;Wijzegen&nbsp;Fam.&nbsp;Cools-Windels&nbsp;App.</strong></p>\r\n\r\n<p>Gewijzigde&nbsp;Functie&#39;s&nbsp;in&nbsp;de&nbsp;app&nbsp;zijn.</p>\r\n\r\n<ul>\r\n<li>Nieuw uitzicht met minder functies.</li>\r\n<li>Nieuwe beveiliging bij het inlogen. We hebben 2 stap verificatie toegevoegd wat inloggen en beveiligen van je account veiliger maakt.</li>\r\n<li>Het oplossen van bug fouten en een verbetering in beveiliging.</li>\r\n</ul>\r\n\r\n<p>Hopelijk vindt je deze update leuk.</p>\r\n<p>&nbsp;&nbsp;Alvast&nbsp;bedankt</p>\r\n', '2024-11-17 17:03:57');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `userevenement`
--

CREATE TABLE `userevenement` (
  `id` int NOT NULL,
  `userid` varchar(255) NOT NULL,
  `title` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `bjaar` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `bmaand` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `bdag` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `buur` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `bmin` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `ejaar` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `emaand` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `edag` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `euur` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `emin` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `fullday` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `color` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `usergroup`
--

CREATE TABLE `usergroup` (
  `id` int NOT NULL,
  `groupname` varchar(255) NOT NULL,
  `userid` varchar(255) NOT NULL,
  `create_groupdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Gegevens worden geëxporteerd voor tabel `usergroup`
--

INSERT INTO `usergroup` (`id`, `groupname`, `userid`, `create_groupdate`) VALUES
(1, 'Alle gebruikers', 'a', '2022-12-09 14:05:09'),
(2, 'Woning ma cools', 'a', '2022-12-09 14:05:09'),
(3, 'Fam.Cools-Windels', 'a', '2022-12-09 14:05:09'),
(4, 'Fam.Cools', 'a', '2022-12-09 14:05:09'),
(5, 'Fam.Windels', 'a', '2022-12-09 14:05:09'),
(6, 'Henri & Andy', 'a', '2022-12-09 14:05:09'),
(7, 'Ouders Andy en henri', 'a', '2022-12-09 14:05:09');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `userlog`
--

CREATE TABLE `userlog` (
  `id` int NOT NULL,
  `userid` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `logindate` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `status` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Gegevens worden geëxporteerd voor tabel `userlog`
--

INSERT INTO `userlog` (`id`, `userid`, `logindate`, `status`) VALUES
(811, '3', '2023-12-07 18:40:30', 'login'),
(812, '1', '2023-12-08 23:28:18', 'logout'),
(813, '', '2023-12-08 23:29:03', 'logout'),
(814, '1', '2023-12-09 23:47:12', 'login'),
(815, '3', '2023-12-17 18:44:54', 'login'),
(816, '3', '2023-12-19 18:42:25', 'login'),
(817, '3', '2023-12-31 15:36:02', 'login'),
(818, '3', '2024-01-02 11:53:20', 'login'),
(819, '1', '2024-01-03 10:36:03', 'logout'),
(820, '1', '2024-01-03 12:15:07', 'login'),
(821, '3', '2024-01-07 18:18:21', 'login'),
(822, '3', '2024-01-09 21:55:43', 'login'),
(823, '3', '2024-01-16 09:24:54', 'login'),
(824, '1', '2024-01-20 22:11:06', 'logout'),
(825, '1', '2024-01-20 22:11:52', 'login'),
(826, '3', '2024-01-29 10:02:56', 'login'),
(827, '2', '2024-01-31 17:56:30', 'login'),
(828, '3', '2024-02-01 09:36:26', 'login'),
(829, '3', '2024-02-02 09:55:20', 'login'),
(830, '3', '2024-02-06 10:05:48', 'login'),
(831, '1', '2024-02-06 20:20:25', 'login'),
(832, '1', '2024-02-06 20:25:15', 'logout'),
(833, '1', '2024-02-08 09:16:22', 'login'),
(834, '3', '2024-02-09 18:42:04', 'login'),
(835, '3', '2024-02-11 18:07:50', 'login'),
(836, '3', '2024-02-13 18:39:50', 'login'),
(837, '1', '2024-02-15 08:49:41', 'login'),
(838, '2', '2024-02-15 08:59:32', 'login'),
(839, '3', '2024-02-15 18:31:00', 'login'),
(840, '3', '2024-02-16 09:57:07', 'login'),
(841, '3', '2024-02-17 18:09:26', 'login'),
(842, '1', '2024-02-18 21:33:47', 'login'),
(843, '3', '2024-02-19 09:17:32', 'login'),
(844, '2', '2024-02-23 10:49:59', 'login'),
(845, '1', '2024-02-26 07:01:40', 'login'),
(846, '3', '2024-02-26 09:23:00', 'login'),
(847, '3', '2024-02-28 09:02:32', 'login'),
(848, '1', '2024-03-01 11:35:16', 'login'),
(849, '3', '2024-03-01 18:43:39', 'login'),
(850, '1', '2024-03-02 10:52:33', 'login'),
(851, '3', '2024-03-02 18:20:57', 'login'),
(852, '1', '2024-03-03 11:48:24', 'login'),
(853, '2', '2024-03-03 21:46:45', 'login'),
(854, '3', '2024-03-04 18:15:14', 'login'),
(855, '3', '2024-03-05 18:39:46', 'login'),
(856, '1', '2024-03-06 07:48:45', 'login'),
(857, '2', '2024-03-06 15:41:43', 'login'),
(858, '1', '2024-03-07 08:58:24', 'login'),
(859, '3', '2024-03-07 18:09:20', 'login'),
(860, '3', '2024-03-07 18:10:14', 'login'),
(861, '1', '2024-03-08 08:41:56', 'login'),
(862, '1', '2024-03-09 09:52:04', 'login'),
(863, '3', '2024-03-10 18:27:15', 'login'),
(864, '3', '2024-03-10 18:29:18', 'login'),
(865, '3', '2024-03-12 16:45:26', 'login'),
(866, '3', '2024-03-13 17:37:52', 'login'),
(867, '2', '2024-03-14 08:18:02', 'login'),
(868, '3', '2024-03-14 18:08:51', 'login'),
(869, '3', '2024-03-14 18:08:52', 'login'),
(870, '3', '2024-03-14 18:08:54', 'login'),
(871, '3', '2024-03-14 18:08:54', 'login'),
(872, '3', '2024-03-14 18:08:56', 'login'),
(873, '1', '2024-03-14 21:08:06', 'login'),
(874, '3', '2024-03-15 18:38:16', 'login'),
(875, '1', '2024-03-17 14:28:43', 'login'),
(876, '1', '2024-03-19 08:18:29', 'login'),
(877, '3', '2024-03-19 09:52:18', 'login'),
(878, '3', '2024-03-19 18:33:55', 'login'),
(879, '1', '2024-03-22 13:10:51', 'login'),
(880, '3', '2024-03-26 21:44:28', 'login'),
(881, '2', '2024-03-28 08:22:43', 'login'),
(882, '3', '2024-03-28 18:11:20', 'login'),
(883, '3', '2024-04-01 10:31:04', 'login'),
(884, '3', '2024-04-04 18:09:34', 'login'),
(885, '1', '2024-04-07 14:18:54', 'login'),
(886, '3', '2024-04-07 17:54:38', 'login'),
(887, '2', '2024-04-09 20:13:03', 'login'),
(888, '3', '2024-04-11 18:18:53', 'login'),
(889, '3', '2024-04-12 09:40:43', 'login'),
(890, '3', '2024-04-12 09:41:35', 'login'),
(891, '1', '2024-04-12 11:22:22', 'login'),
(892, '3', '2024-04-13 08:53:07', 'login'),
(893, '3', '2024-04-14 09:50:17', 'login'),
(894, '3', '2024-04-20 18:19:03', 'login'),
(895, '2', '2024-04-23 07:37:14', 'login'),
(896, '3', '2024-04-23 09:28:59', 'login'),
(897, '1', '2024-04-25 11:27:05', 'login'),
(898, '3', '2024-04-25 18:14:37', 'login'),
(899, '3', '2024-04-28 18:26:45', 'login'),
(900, '3', '2024-04-30 18:43:04', 'login'),
(901, '3', '2024-05-01 18:43:05', 'login'),
(902, '1', '2024-05-03 18:31:50', 'logout'),
(903, '1', '2024-05-03 18:32:00', 'login'),
(904, '1', '2024-05-03 18:32:01', 'login'),
(905, '2', '2024-05-03 18:32:10', 'login'),
(906, '2', '2024-05-05 16:25:30', 'login'),
(907, '3', '2024-05-06 13:49:04', 'login'),
(908, '1', '2024-05-08 20:17:53', 'login'),
(909, '3', '2024-05-09 16:46:21', 'login'),
(910, '1', '2024-05-12 15:43:12', 'login'),
(911, '3', '2024-05-13 09:09:49', 'login'),
(912, '3', '2024-05-18 09:22:19', 'login'),
(913, '2', '2024-05-20 18:15:51', 'login'),
(914, '3', '2024-05-23 08:37:57', 'login'),
(915, '1', '2024-05-29 17:43:05', 'login'),
(916, '3', '2024-06-01 09:34:21', 'login'),
(917, '1', '2024-06-05 22:02:30', 'logout'),
(918, '1', '2024-06-05 22:15:35', 'login'),
(919, '2', '2024-06-07 18:08:06', 'login'),
(920, '1', '2024-06-07 20:19:14', 'login'),
(921, '3', '2024-06-08 09:45:47', 'login'),
(922, '1', '2024-06-08 16:19:10', 'login'),
(923, '1', '2024-06-08 16:29:24', 'login'),
(924, '1', '2024-06-08 18:30:14', 'login'),
(925, '1', '2024-06-08 18:46:29', 'logout'),
(926, '1', '2024-06-08 18:46:36', 'login'),
(927, '1', '2024-06-08 19:08:41', 'logout'),
(928, '1', '2024-06-09 07:43:49', 'login'),
(929, '2', '2024-06-10 18:32:01', 'login'),
(930, '3', '2024-06-11 18:14:54', 'login'),
(931, '3', '2024-06-12 15:26:23', 'login'),
(932, '1', '2024-06-14 20:39:46', 'logout'),
(933, '1', '2024-06-14 20:40:45', 'login'),
(934, '1', '2024-06-14 20:48:06', 'logout'),
(935, '1', '2024-06-16 20:47:44', 'login'),
(936, '1', '2024-06-17 22:15:25', 'logout'),
(937, '1', '2024-06-18 15:01:00', 'login'),
(938, '1', '2024-06-20 10:44:36', 'logout'),
(939, '1', '2024-06-20 10:44:47', 'login'),
(940, '1', '2024-06-20 11:19:01', 'login'),
(941, '1', '2024-06-20 15:52:26', 'logout'),
(942, '1', '2024-06-20 22:54:31', 'login'),
(943, '3', '2024-06-22 09:55:45', 'login'),
(944, '1', '2024-06-23 19:54:39', 'logout'),
(945, '3', '2024-06-25 09:40:10', 'login'),
(946, '1', '2024-06-25 19:48:01', 'login'),
(947, '3', '2024-06-28 09:21:34', 'login'),
(948, '1', '2024-06-28 15:15:37', 'logout'),
(949, '', '2024-06-28 15:15:37', 'logout'),
(950, '1', '2024-06-29 09:23:57', 'login'),
(951, '3', '2024-06-30 09:25:42', 'login'),
(952, '3', '2024-07-02 15:21:08', 'login'),
(953, '3', '2024-07-03 13:39:45', 'login'),
(954, '3', '2024-07-05 18:14:53', 'login'),
(955, '2', '2024-07-11 20:36:02', 'login'),
(956, '3', '2024-07-12 18:26:47', 'login'),
(957, '1', '2024-07-15 08:45:42', 'login'),
(958, '1', '2024-07-19 16:19:11', 'login'),
(959, '3', '2024-07-21 10:15:29', 'login'),
(960, '3', '2024-07-26 11:06:15', 'login'),
(961, '3', '2024-07-26 11:06:20', 'login'),
(962, '1', '2024-07-27 15:08:23', 'login'),
(963, '2', '2024-07-28 09:33:16', 'login'),
(964, '1', '2024-07-31 19:19:03', 'login'),
(965, '1', '2024-07-31 19:23:29', 'logout'),
(966, '3', '2024-07-31 19:23:55', 'login'),
(967, '1', '2024-07-31 19:37:58', 'logout'),
(968, '3', '2024-07-31 19:40:18', 'logout'),
(969, '1', '2024-07-31 19:41:05', 'login'),
(970, '1', '2024-07-31 19:41:09', 'logout'),
(971, '3', '2024-07-31 19:41:24', 'login'),
(972, '3', '2024-07-31 19:45:07', 'logout'),
(973, '3', '2024-07-31 19:45:27', 'login'),
(974, '3', '2024-07-31 19:45:31', 'logout'),
(975, '3', '2024-07-31 19:59:00', 'login'),
(976, '1', '2024-08-02 18:27:56', 'login'),
(977, '3', '2024-08-02 18:37:03', 'login'),
(978, '3', '2024-08-07 09:42:15', 'login'),
(979, '3', '2024-08-07 18:20:20', 'login'),
(980, '3', '2024-08-11 09:29:34', 'login'),
(981, '1', '2024-08-12 07:55:27', 'login'),
(982, '3', '2024-08-14 09:08:20', 'login'),
(983, '1', '2024-08-14 22:19:07', 'login'),
(984, '1', '2024-08-15 10:06:41', 'login'),
(985, '1', '2024-08-15 10:06:42', 'login'),
(986, '3', '2024-08-15 18:29:03', 'login'),
(987, '3', '2024-08-16 18:20:29', 'login'),
(988, '2', '2024-08-18 08:25:15', 'login'),
(989, '3', '2024-08-19 09:19:43', 'login'),
(990, '3', '2024-08-26 18:16:52', 'login'),
(991, '2', '2024-08-28 16:13:10', 'login'),
(992, '3', '2024-08-30 18:17:45', 'login'),
(993, '3', '2024-08-31 09:21:36', 'login'),
(994, '3', '2024-09-01 21:05:56', 'login'),
(995, '1', '2024-09-02 10:41:59', 'login'),
(996, '3', '2024-09-07 18:21:37', 'login'),
(997, '1', '2024-09-07 22:13:40', 'login'),
(998, '2', '2024-09-17 17:44:29', 'login'),
(999, '3', '2024-09-19 10:28:42', 'login'),
(1000, '1', '2024-09-20 13:12:05', 'login'),
(1001, '3', '2024-09-21 09:18:36', 'login'),
(1002, '2', '2024-09-22 09:26:59', 'login'),
(1003, '1', '2024-09-22 09:50:30', 'login'),
(1004, '1', '2024-09-22 19:45:40', 'login'),
(1005, '1', '2024-09-25 14:42:57', 'login'),
(1006, '1', '2024-09-25 16:01:11', 'login'),
(1007, '1', '2024-09-25 17:30:57', 'login'),
(1008, '3', '2024-09-26 09:36:47', 'login'),
(1009, '3', '2024-09-29 09:36:18', 'login'),
(1010, '3', '2024-09-29 18:11:45', 'login'),
(1011, '1', '2024-09-30 15:15:48', 'login'),
(1012, '2', '2024-09-30 17:35:54', 'login'),
(1013, '3', '2024-09-30 18:38:50', 'login'),
(1014, '1', '2024-10-01 14:49:59', 'login'),
(1015, '1', '2024-10-01 16:53:43', 'login'),
(1016, '1', '2024-10-02 09:43:59', 'login'),
(1017, '1', '2024-10-02 15:50:53', 'login'),
(1018, '3', '2024-10-02 21:54:14', 'login'),
(1019, '3', '2024-10-03 09:27:57', 'login'),
(1020, '1', '2024-10-03 13:08:31', 'login'),
(1021, '1', '2024-10-03 16:12:49', 'login'),
(1022, '1', '2024-10-06 17:53:18', 'login'),
(1023, '1', '2024-10-10 08:09:12', 'login'),
(1024, '1', '2024-10-10 08:09:12', 'login'),
(1025, '1', '2024-10-10 13:07:58', 'login'),
(1026, '3', '2024-10-11 09:32:46', 'login'),
(1027, '1', '2024-10-12 11:08:16', 'login'),
(1028, '3', '2024-10-12 12:11:24', 'login'),
(1029, '1', '2024-10-12 15:05:57', 'login'),
(1030, '3', '2024-10-13 19:47:54', 'login'),
(1031, '3', '2024-10-13 19:47:55', 'login'),
(1032, '3', '2024-10-14 18:22:05', 'login'),
(1033, '3', '2024-10-17 17:41:01', 'login'),
(1034, '3', '2024-10-17 17:41:03', 'login'),
(1035, '1', '2024-10-17 21:16:09', 'login'),
(1036, '1', '2024-10-18 18:55:00', 'login'),
(1037, '1', '2024-10-18 18:55:00', 'login'),
(1038, '1', '2024-10-19 19:39:58', 'login'),
(1039, '2', '2024-10-20 08:21:53', 'login'),
(1040, '3', '2024-10-20 18:34:07', 'login'),
(1041, '2', '2024-10-21 13:04:02', 'login'),
(1042, '3', '2024-10-22 10:06:51', 'login'),
(1043, '3', '2024-10-24 12:43:02', 'login'),
(1044, '1', '2024-10-25 11:03:32', 'login'),
(1045, '1', '2024-10-25 17:44:30', 'login'),
(1046, '1', '2024-10-25 21:12:13', 'login'),
(1047, '3', '2024-10-26 18:29:00', 'login'),
(1048, '1', '2024-10-27 15:42:51', 'login'),
(1049, '2', '2024-10-27 16:32:14', 'login'),
(1050, '1', '2024-10-27 16:38:38', 'logout'),
(1051, '1', '2024-10-28 18:17:05', 'login'),
(1052, '1', '2024-10-28 21:45:04', 'login'),
(1053, '3', '2024-10-29 10:28:24', 'login'),
(1054, '1', '2024-10-30 20:24:28', 'login'),
(1055, '1', '2024-10-31 10:27:35', 'login'),
(1056, '2', '2024-10-31 16:54:24', 'login'),
(1057, '1', '2024-11-01 09:36:46', 'login'),
(1058, '3', '2024-11-01 18:31:50', 'login'),
(1059, '1', '2024-11-02 10:26:14', 'login'),
(1060, '1', '2024-11-02 10:26:15', 'login'),
(1061, '3', '2024-11-02 18:41:11', 'login'),
(1062, '1', '2024-11-03 11:27:36', 'login'),
(1063, '3', '2024-11-04 18:41:07', 'login'),
(1064, '1', '2024-11-04 19:41:49', 'login'),
(1065, '1', '2024-11-06 06:55:38', 'login'),
(1066, '1', '2024-11-06 06:55:39', 'login'),
(1067, '1', '2024-11-07 14:54:57', 'login'),
(1068, '3', '2024-11-07 18:42:37', 'login'),
(1069, '3', '2024-11-09 09:43:53', 'login'),
(1070, '3', '2024-11-09 09:44:41', 'login'),
(1071, '1', '2024-11-10 09:30:50', 'login'),
(1072, '1', '2024-11-10 16:21:10', 'login'),
(1073, '1', '2024-11-11 06:30:24', 'login'),
(1074, '3', '2024-11-12 09:46:42', 'login'),
(1075, '3', '2024-11-12 09:46:44', 'login'),
(1076, '3', '2024-11-14 10:02:47', 'login'),
(1077, '1', '2024-11-14 12:14:26', 'login'),
(1078, '1', '2024-11-14 16:07:24', 'login'),
(1079, '1', '2024-11-14 19:00:18', 'login'),
(1080, '1', '2024-11-15 10:53:14', 'login'),
(1081, '1', '2024-11-15 10:54:17', 'login'),
(1082, '1', '2024-11-15 11:39:52', 'logout'),
(1083, '1', '2024-11-15 12:51:06', 'login'),
(1084, '1', '2024-11-15 12:51:15', 'logout'),
(1085, '1', '2024-11-15 13:32:11', 'login'),
(1086, '1', '2024-11-15 13:48:33', 'login'),
(1087, '1', '2024-11-15 13:50:21', 'logout'),
(1088, '1', '2024-11-15 14:13:03', 'login'),
(1089, '1', '2024-11-15 15:24:22', 'login'),
(1090, '1', '2024-11-15 20:53:38', 'logout'),
(1091, '1', '2024-11-15 21:01:57', 'login'),
(1092, '2', '2024-11-16 09:18:36', 'login'),
(1093, '3', '2024-11-16 10:02:30', 'login'),
(1094, '1', '2024-11-16 10:05:49', 'login'),
(1095, '1', '2024-11-16 13:32:29', 'logout'),
(1096, '3', '2024-11-16 13:34:12', 'login'),
(1097, '3', '2024-11-16 14:01:08', 'logout'),
(1098, '1', '2024-11-16 14:01:13', 'login'),
(1099, '1', '2024-11-16 16:55:48', 'logout'),
(1100, '', '2024-11-16 16:57:50', 'logout'),
(1101, '1', '2024-11-16 17:11:47', 'login'),
(1102, '1', '2024-11-16 17:20:52', 'logout'),
(1103, '1', '2024-11-16 17:58:00', 'login'),
(1104, '3', '2024-11-16 20:40:03', 'logout'),
(1105, '3', '2024-11-16 20:40:09', 'login'),
(1106, '3', '2024-11-16 20:41:56', 'login'),
(1107, '3', '2024-11-16 20:44:18', 'logout'),
(1108, '1', '2024-11-17 10:17:37', 'logout'),
(1109, '1', '2024-11-17 11:55:13', 'logout'),
(1110, '1', '2024-11-17 12:10:08', 'logout'),
(1111, '1', '2024-11-17 12:15:14', 'logout'),
(1112, '', '2024-11-17 12:16:38', 'logout'),
(1113, '1', '2024-11-17 12:29:26', 'logout'),
(1114, '1', '2024-11-17 12:29:37', 'login'),
(1115, '1', '2024-11-17 12:40:14', 'login'),
(1116, '1', '2024-11-17 12:41:17', 'logout'),
(1117, '1', '2024-11-17 12:41:22', 'login'),
(1118, '1', '2024-11-17 12:42:30', 'logout'),
(1119, '1', '2024-11-17 12:43:00', 'logout'),
(1120, '1', '2024-11-17 16:51:47', 'login'),
(1121, '1', '2024-11-17 17:40:29', 'logout'),
(1122, '2', '2024-11-17 18:13:33', 'login'),
(1123, '1', '2024-11-17 20:27:35', 'logout'),
(1124, '3', '2024-11-19 18:50:46', 'login'),
(1125, '', '2024-11-19 19:36:51', 'logout'),
(1126, '3', '2024-11-20 18:30:42', 'login'),
(1127, '2', '2024-11-27 06:35:55', 'login'),
(1128, '3', '2024-11-27 09:36:40', 'login'),
(1129, '3', '2024-12-01 18:33:02', 'login'),
(1130, '3', '2024-12-02 10:46:26', 'login'),
(1131, '3', '2024-12-04 10:44:37', 'login');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `image` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `username` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `email` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `password` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `type` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `navbar` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `darkmode` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `selfnoti` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `groupsen` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `login` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Last_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `two_step_enabled` tinyint(1) NOT NULL,
  `two_step_secret` varchar(32) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Gegevens worden geëxporteerd voor tabel `users`
--

INSERT INTO `users` (`id`, `image`, `username`, `email`, `password`, `type`, `navbar`, `darkmode`, `selfnoti`, `groupsen`, `login`, `date`, `Last_date`, `two_step_enabled`, `two_step_secret`) VALUES
(1, '26', 'Andy Windels', 'andywindels5@gmail.com', '$2y$10$eqHv68LwTErB5g80thZ57eMd3KoYDuGSvcmqxO.yimOJgOV1A9l4e', 'admin', 'true', 'false', 'false', '1,2,3,4,5,6,7', '2024-11-17 16:51:47', '2021-06-11 12:50:10', '2024-11-17 17:39:38', 1, 'VTJZ7HATR2XFDTMW'),
(2, '21', 'Henri Cools', 'henricools@hotmail.com', '$2y$10$LucDsexoX0F55qJ62SPk8uShbhI35zfcRT6AsU3sFnTAIej.Xlj8C', 'admin', 'true', 'false', 'true', '1,2,3,4,5,6,7', '2024-11-27 06:35:55', '2021-06-11 21:03:58', '2024-11-27 06:35:55', 0, NULL),
(3, '17', 'Martha Sturmans', 'martha@cools-windels.be', '$2y$10$funjOdgjFN6lo7b3kQI65ePxSAX5NHYukIFJrDh4T6f0GBr5COZtu', 'user', 'true', 'false', 'true', '2, 3, 4, 7', '2024-12-04 10:44:37', '2021-06-11 09:37:34', '2024-12-04 10:44:37', 0, NULL),
(4, '21', 'Franky Windels', 'windelsfranky@gmail.com', '$2y$10$IiTIagb5mIpzPruBUd.eZu0jC9nUG/Ya6KK.TXrxFYRoyekdKI6RO', 'user', 'true', 'false', 'false', '3,5,7', '2023-04-23 11:21:49', '2021-06-15 17:00:51', '2024-11-17 17:17:52', 0, NULL),
(5, '19', 'Diane De smedt', 'diane.desmedt@gmail.com', '$2y$10$IiTIagb5mIpzPruBUd.eZu0jC9nUG/Ya6KK.TXrxFYRoyekdKI6RO', 'user', 'true', 'false', 'false', '3,5,7', '', '2021-07-30 14:11:51', '2024-11-17 17:17:56', 0, NULL),
(6, '21', 'Kyani Windels', 'kyani.windels1@gmail.com', '$2y$10$IiTIagb5mIpzPruBUd.eZu0jC9nUG/Ya6KK.TXrxFYRoyekdKI6RO', 'user', 'true', 'false', 'false', '3,5', '2022-08-14 12:09:10', '2021-07-30 14:11:51', '2023-01-02 10:55:44', 0, NULL),
(7, '21', 'Mirjam Cools', 'andywindels5@gmail.com', '$2y$10$gouiRejkqFlvHChBzToWbucVNKH9Q2GQINL4eyncUVrErkk7UtiSK', 'user', '', '', '', '4', '', '2023-04-23 11:49:01', '2023-04-23 11:49:01', 0, NULL),
(8, '21', 'Jolanda Cools', 'andywindels5@gmail.com', '$2y$10$YlQyIYwlK5qRV//kNafpke9Ke04OtblccVIrxrvQ259oVT0WSrUo.', 'user', '', '', '', '4', '', '2023-04-23 11:54:25', '2023-04-23 11:54:25', 0, NULL);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `usersetup`
--

CREATE TABLE `usersetup` (
  `id` int NOT NULL,
  `userid` varchar(255) NOT NULL,
  `navbar` varchar(255) NOT NULL,
  `darkmode` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `calendarnoti` varchar(255) NOT NULL,
  `emailnoti` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `calendaremailnoti` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `updatenoti` varchar(255) NOT NULL,
  `noti_notimail` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Gegevens worden geëxporteerd voor tabel `usersetup`
--

INSERT INTO `usersetup` (`id`, `userid`, `navbar`, `darkmode`, `calendarnoti`, `emailnoti`, `calendaremailnoti`, `updatenoti`, `noti_notimail`) VALUES
(1, '1', 'true', 'false', 'true', 'true', 'true', 'true', 'true'),
(2, '2', 'true', 'false', 'false', 'false', 'false', 'false', 'false'),
(3, '3', 'true', 'false', 'true', 'true', 'true', 'false', 'false'),
(4, '4', 'true', 'false', 'false', 'true', 'true', 'false', 'false'),
(5, '5', 'true', 'false', 'false', 'false', 'false', 'false', 'false'),
(6, '6', 'true', 'false', 'false', 'false', 'false', 'false', 'false'),
(7, '7', 'true', 'false', 'false', 'false', 'false', 'false', 'false'),
(8, '8', 'true', 'false', 'false', 'false', 'false', 'false', 'false');

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `adminusers`
--
ALTER TABLE `adminusers`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `albumimage`
--
ALTER TABLE `albumimage`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `appsystem`
--
ALTER TABLE `appsystem`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `evenement`
--
ALTER TABLE `evenement`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `image`
--
ALTER TABLE `image`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `notify`
--
ALTER TABLE `notify`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `notifystatus`
--
ALTER TABLE `notifystatus`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `paying_off`
--
ALTER TABLE `paying_off`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `slider`
--
ALTER TABLE `slider`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `updates`
--
ALTER TABLE `updates`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `userevenement`
--
ALTER TABLE `userevenement`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `usergroup`
--
ALTER TABLE `usergroup`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `userlog`
--
ALTER TABLE `userlog`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `usersetup`
--
ALTER TABLE `usersetup`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT voor geëxporteerde tabellen
--

--
-- AUTO_INCREMENT voor een tabel `adminusers`
--
ALTER TABLE `adminusers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT voor een tabel `albumimage`
--
ALTER TABLE `albumimage`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT voor een tabel `appsystem`
--
ALTER TABLE `appsystem`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT voor een tabel `evenement`
--
ALTER TABLE `evenement`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1556;

--
-- AUTO_INCREMENT voor een tabel `image`
--
ALTER TABLE `image`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT voor een tabel `notify`
--
ALTER TABLE `notify`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT voor een tabel `notifystatus`
--
ALTER TABLE `notifystatus`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT voor een tabel `paying_off`
--
ALTER TABLE `paying_off`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT voor een tabel `slider`
--
ALTER TABLE `slider`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT voor een tabel `updates`
--
ALTER TABLE `updates`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT voor een tabel `userevenement`
--
ALTER TABLE `userevenement`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `usergroup`
--
ALTER TABLE `usergroup`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT voor een tabel `userlog`
--
ALTER TABLE `userlog`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1132;

--
-- AUTO_INCREMENT voor een tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT voor een tabel `usersetup`
--
ALTER TABLE `usersetup`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- Database: `voedselproblemen`
--
CREATE DATABASE IF NOT EXISTS `voedselproblemen` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `voedselproblemen`;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `bewerkingsgeschiedenis`
--

CREATE TABLE `bewerkingsgeschiedenis` (
  `id` int NOT NULL,
  `melding_id` int DEFAULT NULL,
  `medewerker_id` int DEFAULT NULL,
  `veld` varchar(255) DEFAULT NULL,
  `oude_waarde` text,
  `nieuwe_waarde` text,
  `datum_tijd` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `extern_producten`
--

CREATE TABLE `extern_producten` (
  `id` int NOT NULL,
  `vers_productie_id` int NOT NULL,
  `vers_lotnummerproduct` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `vers_products_id` int NOT NULL,
  `product_naam` varchar(255) NOT NULL,
  `fabrikant` varchar(255) NOT NULL,
  `aankoop_datum` date NOT NULL,
  `lotnummer_fabrikant` varchar(255) NOT NULL,
  `gebruikte_hoeveelheid` decimal(10,6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `extern_producten`
--

INSERT INTO `extern_producten` (`id`, `vers_productie_id`, `vers_lotnummerproduct`, `vers_products_id`, `product_naam`, `fabrikant`, `aankoop_datum`, `lotnummer_fabrikant`, `gebruikte_hoeveelheid`) VALUES
(1, 1, '26-20240809-D4225A16', 26, 'Geleisuiker', 'Consun Beet Company', '2024-07-25', 'L3531023', 0.600000),
(2, 1, '26-20240809-D4225A16', 26, 'Gelatine', 'Gelita Ag', '2024-08-09', 'A22801Q', 0.016600),
(6, 2, '28-20241002-7C1E50FC', 28, 'Aardappelen', 'Boni Colruyt', '2024-10-02', '28282', 1.000000),
(7, 2, '28-20241002-7C1E50FC', 28, 'Knoflook', 'Colruyt', '2024-10-02', 'L-385', 0.010000),
(8, 2, '28-20241002-7C1E50FC', 28, 'Bouillon ', 'Knorr', '2023-09-15', 'L30440X098', 0.080000),
(9, 3, '28-20241010-6EACB773', 28, 'Aardappelen', 'Boni Colruyt', '2024-10-02', '28282', 0.750000),
(10, 3, '28-20241010-6EACB773', 28, 'Knoflook', 'Colruyt', '2024-10-02', 'L-385', 0.009000),
(11, 3, '28-20241010-6EACB773', 28, 'Bouillon ', 'Knorr', '2023-09-15', 'L30440X098', 0.070000);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `medewerkers`
--

CREATE TABLE `medewerkers` (
  `id` int NOT NULL,
  `username` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `medewerkers`
--

INSERT INTO `medewerkers` (`id`, `username`) VALUES
(1, 'admin'),
(2, 'Winkel_andy'),
(3, 'Winkel_henri'),
(4, 'Winkel_kyani'),
(5, 'Winkel_franky'),
(6, 'Winkel_diane');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `meldingen`
--

CREATE TABLE `meldingen` (
  `id` int NOT NULL,
  `dossiernummer` varchar(255) NOT NULL,
  `datum_melding` date NOT NULL,
  `naam_klant` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email_klant` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `productnaam` varchar(255) NOT NULL,
  `probleem` varchar(255) NOT NULL,
  `gezondheidsklachten` enum('Ja','Nee') NOT NULL,
  `beschrijving` text NOT NULL,
  `batchnummer` varchar(255) NOT NULL,
  `aankoopdatum` date NOT NULL,
  `houdbaarheidsdatum` date NOT NULL,
  `documenten_meegeleverd` enum('Ja','Nee') NOT NULL,
  `status` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `locked_by` int DEFAULT NULL,
  `medewerker_id` int DEFAULT NULL,
  `notitie` text,
  `datum_tijd` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `laatste_bewerking_datum` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `laatste_bewerking_medewerker_id` int DEFAULT NULL,
  `favv_dossier` tinyint(1) DEFAULT '0',
  `favv_dossiernummer` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `notities`
--

CREATE TABLE `notities` (
  `id` int NOT NULL,
  `melding_id` int DEFAULT NULL,
  `medewerker_id` int DEFAULT NULL,
  `notitie` text,
  `datum_tijd` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `terugroepacties`
--

CREATE TABLE `terugroepacties` (
  `id` int NOT NULL,
  `dossiernummer` varchar(255) NOT NULL,
  `productnaam` varchar(255) NOT NULL,
  `klacht` varchar(255) NOT NULL,
  `klachtinformatie` text,
  `favv_dossiernummer` varchar(255) DEFAULT NULL,
  `favv_klachtinformatie` text,
  `datum_terugroepactie` date NOT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'Nieuw'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `vers_productie`
--

CREATE TABLE `vers_productie` (
  `id` int NOT NULL,
  `lotnummer` varchar(50) NOT NULL,
  `vers_product_id` int NOT NULL,
  `aantal_gemaakt` int NOT NULL,
  `productie_datum` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `vervaldatum` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `vers_productie`
--

INSERT INTO `vers_productie` (`id`, `lotnummer`, `vers_product_id`, `aantal_gemaakt`, `productie_datum`, `vervaldatum`) VALUES
(1, '26-20240809-D4225A16', 26, 2, '2024-08-09 18:47:10', '2025-08-09'),
(2, '28-20241002-7C1E50FC', 28, 9, '2024-10-02 19:18:07', '2025-01-02'),
(3, '28-20241010-6EACB773', 28, 4, '2024-10-10 10:31:31', '2025-01-10');

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `bewerkingsgeschiedenis`
--
ALTER TABLE `bewerkingsgeschiedenis`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bewerkingsgeschiedenis_ibfk_1` (`melding_id`),
  ADD KEY `bewerkingsgeschiedenis_ibfk_2` (`medewerker_id`);

--
-- Indexen voor tabel `extern_producten`
--
ALTER TABLE `extern_producten`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vers_productie_id` (`vers_productie_id`),
  ADD KEY `fk_vers_products` (`vers_products_id`),
  ADD KEY `vers_lotnummer` (`vers_lotnummerproduct`);

--
-- Indexen voor tabel `medewerkers`
--
ALTER TABLE `medewerkers`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `meldingen`
--
ALTER TABLE `meldingen`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_naam_klant` (`naam_klant`),
  ADD KEY `idx_email_klant` (`email_klant`),
  ADD KEY `idx_productnaam` (`productnaam`),
  ADD KEY `idx_dossiernummer` (`dossiernummer`),
  ADD KEY `FK_medewerker` (`medewerker_id`),
  ADD KEY `FK_laatste_bewerking_medewerker` (`laatste_bewerking_medewerker_id`);

--
-- Indexen voor tabel `notities`
--
ALTER TABLE `notities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notities_ibfk_1` (`melding_id`),
  ADD KEY `notities_ibfk_2` (`medewerker_id`);

--
-- Indexen voor tabel `terugroepacties`
--
ALTER TABLE `terugroepacties`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `vers_productie`
--
ALTER TABLE `vers_productie`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vers_product_id` (`vers_product_id`),
  ADD KEY `idx_lotnummer` (`lotnummer`);

--
-- AUTO_INCREMENT voor geëxporteerde tabellen
--

--
-- AUTO_INCREMENT voor een tabel `bewerkingsgeschiedenis`
--
ALTER TABLE `bewerkingsgeschiedenis`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT voor een tabel `extern_producten`
--
ALTER TABLE `extern_producten`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT voor een tabel `medewerkers`
--
ALTER TABLE `medewerkers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT voor een tabel `meldingen`
--
ALTER TABLE `meldingen`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT voor een tabel `notities`
--
ALTER TABLE `notities`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `terugroepacties`
--
ALTER TABLE `terugroepacties`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `vers_productie`
--
ALTER TABLE `vers_productie`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Beperkingen voor geëxporteerde tabellen
--

--
-- Beperkingen voor tabel `bewerkingsgeschiedenis`
--
ALTER TABLE `bewerkingsgeschiedenis`
  ADD CONSTRAINT `bewerkingsgeschiedenis_ibfk_1` FOREIGN KEY (`melding_id`) REFERENCES `meldingen` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bewerkingsgeschiedenis_ibfk_2` FOREIGN KEY (`medewerker_id`) REFERENCES `medewerkers` (`id`);

--
-- Beperkingen voor tabel `extern_producten`
--
ALTER TABLE `extern_producten`
  ADD CONSTRAINT `extern_producten_ibfk_1` FOREIGN KEY (`vers_productie_id`) REFERENCES `vers_productie` (`id`),
  ADD CONSTRAINT `fk_vers_products` FOREIGN KEY (`vers_products_id`) REFERENCES `winkel`.`vers_products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `vers_lotnummer` FOREIGN KEY (`vers_lotnummerproduct`) REFERENCES `vers_productie` (`lotnummer`);

--
-- Beperkingen voor tabel `meldingen`
--
ALTER TABLE `meldingen`
  ADD CONSTRAINT `FK_laatste_bewerking_medewerker` FOREIGN KEY (`laatste_bewerking_medewerker_id`) REFERENCES `medewerkers` (`id`),
  ADD CONSTRAINT `FK_medewerker` FOREIGN KEY (`medewerker_id`) REFERENCES `medewerkers` (`id`);

--
-- Beperkingen voor tabel `notities`
--
ALTER TABLE `notities`
  ADD CONSTRAINT `notities_ibfk_1` FOREIGN KEY (`melding_id`) REFERENCES `meldingen` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `notities_ibfk_2` FOREIGN KEY (`medewerker_id`) REFERENCES `medewerkers` (`id`);

--
-- Beperkingen voor tabel `vers_productie`
--
ALTER TABLE `vers_productie`
  ADD CONSTRAINT `vers_productie_ibfk_1` FOREIGN KEY (`vers_product_id`) REFERENCES `winkel`.`vers_products` (`id`);
--
-- Database: `winkel`
--
CREATE DATABASE IF NOT EXISTS `winkel` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `winkel`;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `cat_magazijn`
--

CREATE TABLE `cat_magazijn` (
  `id` int NOT NULL,
  `title_cat` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `cat_product`
--

CREATE TABLE `cat_product` (
  `id` int NOT NULL,
  `title_cat` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `epoxy_products`
--

CREATE TABLE `epoxy_products` (
  `id` int NOT NULL,
  `sku` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `product_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `product_description` text COLLATE utf8mb4_general_ci,
  `amount_grams` int NOT NULL,
  `price_per_gram` decimal(10,3) NOT NULL,
  `extra_parts_price` decimal(10,2) DEFAULT NULL,
  `margin` decimal(5,2) DEFAULT NULL,
  `hours_worked` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `created_by_user` int DEFAULT NULL,
  `company_cost_per_product` decimal(10,2) DEFAULT '94.90',
  `sold_in_branches` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `vat_percentage` decimal(5,2) DEFAULT '21.00',
  `total_product_price` decimal(10,2) DEFAULT '0.00',
  `created_on` date DEFAULT NULL,
  `shipping_method` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `category` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `stock` int DEFAULT '0',
  `hourly_rate` decimal(10,2) DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `epoxy_products`
--

INSERT INTO `epoxy_products` (`id`, `sku`, `title`, `product_image`, `product_description`, `amount_grams`, `price_per_gram`, `extra_parts_price`, `margin`, `hours_worked`, `created_by_user`, `company_cost_per_product`, `sold_in_branches`, `vat_percentage`, `total_product_price`, `created_on`, `shipping_method`, `category`, `stock`, `hourly_rate`) VALUES
(64, '20092', 'Haarborstel', '20240222_113507.jpg', NULL, 77, 0.020, 1.75, 40.00, '01:00', 6, 2.77, '3', 21.00, 14.50, '2024-02-25', 'kleine_doos', 'epoxy', 2, 2.50),
(65, '20093', 'theelichthouder maan', '20240222_112622.jpg', NULL, 145, 0.020, 0.00, 40.00, '01:00', 6, 2.77, '3', 21.00, 13.84, '2024-02-25', 'kleine_doos', 'epoxy', 1, 2.50),
(66, '20094', 'eierhouder', '20240222_113436.jpg', NULL, 212, 0.020, 0.00, 40.00, '01:00', 6, 2.77, '3', 21.00, 16.11, '2024-02-25', 'kleine_doos', 'epoxy', 1, 2.50),
(67, '20007', 'Halsketting drop', '20230715_140013.jpg', NULL, 5, 0.130, 0.00, 40.00, '01:00', 6, 2.77, '3', 21.00, 10.03, '2024-03-05', 'briefpakket', 'epoxy', 1, 2.50),
(68, '20085', 'sieraad 10', '20230715_140226.jpg', NULL, 7, 0.130, 0.00, 40.00, '01:00', 6, 2.77, '3', 21.00, 10.47, '2024-03-05', 'briefpakket', 'epoxy', 1, 2.50),
(69, '20008', 'sieraad 11', '20230715_140420.jpg', NULL, 14, 0.130, 0.00, 40.00, '01:00', 6, 2.77, '3', 21.00, 12.01, '2024-03-05', 'briefpakket', 'epoxy', 1, 2.50),
(70, '20009', 'sieraad 15', '20230715_140644.jpg', NULL, 7, 0.130, 0.00, 40.00, '01:00', 6, 2.77, '3', 21.00, 10.47, '2024-03-05', 'briefpakket', 'epoxy', 0, 2.50),
(71, '20010', 'oorbellen ruitvormig', '20230715_140739.jpg', NULL, 3, 0.130, 0.28, 40.00, '00:30', 6, 2.77, '3', 21.00, 7.94, '2024-03-05', 'briefpakket', 'epoxy', 0, 2.50),
(72, '20011', 'Halsketting rechthoekig met halve diamant + ovalen oorbellen', '20230715_140814.jpg', NULL, 28, 0.130, 0.00, 40.00, '01:00', 6, 2.77, '3', 21.00, 15.09, '2024-03-05', 'briefpakket', 'epoxy', 1, 2.50),
(73, '20064', 'sieraad 20', '20230715_141019.jpg', NULL, 6, 0.130, 0.00, 40.00, '01:00', 6, 2.77, '3', 21.00, 10.25, '2024-03-05', 'briefpakket', 'epoxy', 2, 2.50),
(74, '20069', 'sieraad 21', '20230715_141119.jpg', NULL, 6, 0.130, 0.00, 40.00, '01:00', 6, 2.77, '3', 21.00, 10.25, '2024-03-05', 'briefpakket', 'epoxy', 0, 2.50),
(75, '20087', 'sieraad 22', '20230715_141232.jpg', NULL, 11, 0.130, 0.00, 40.00, '01:00', 6, 2.77, '3', 21.00, 11.35, '2024-03-05', 'briefpakket', 'epoxy', 0, 2.50),
(76, '20086', 'sieraad 7', '20230715_140041.jpg', NULL, 5, 0.130, 0.00, 40.00, '01:00', 6, 2.77, '3', 21.00, 10.03, '2024-03-05', 'briefpakket', 'epoxy', 1, 2.50),
(77, '20089', 'parfumflesje Angel', '20240305_103909.jpg', NULL, 111, 0.020, 8.31, 40.00, '01:00', 6, 2.77, '3', 21.00, 26.77, '2024-03-05', 'kleine_doos', 'epoxy', 3, 2.50),
(78, '20116', 'WC rolhouder', '20240306_100056.jpg', NULL, 560, 0.020, 0.99, 40.00, '02:00', 6, 2.77, '3', 21.00, 42.28, '2024-03-06', 'grote_doos', 'epoxy', 1, 5.00),
(79, '20019', 'flessen houder + 2 onderleggers', '20240105_145335.jpg', NULL, 328, 0.020, 0.00, 40.00, '01:00', 6, 2.77, '3', 21.00, 20.04, '2024-01-01', 'kleine_doos', 'epoxy', 2, 2.50),
(80, '20020', 'fopspeen', 'SSA53158.JPG', NULL, 14, 0.130, 0.58, 40.00, '01:00', 6, 2.77, '3', 21.00, 12.99, '2024-01-01', 'briefpakket', 'epoxy', 1, 2.50),
(81, '20021', 'hart+ hart', '20230902_192220.jpg', NULL, 566, 0.020, 0.00, 40.00, '01:00', 6, 2.77, '3', 21.00, 32.34, '2024-01-01', 'kleine_doos', 'epoxy', 2, 5.00),
(82, '20022', 'gekleurd kommetje', '20230902_192121.jpg', NULL, 110, 0.020, 0.00, 40.00, '01:00', 6, 2.77, '3', 21.00, 12.65, '2024-01-01', 'kleine_doos', 'epoxy', 2, 2.50),
(83, '20023', 'Geur flesje', '20230809_164247.jpg', NULL, 276, 0.020, 1.75, 40.00, '01:00', 6, 2.77, '3', 21.00, 21.24, '2024-01-01', 'kleine_doos', 'epoxy', 2, 2.50),
(84, '20024', 'grote onderlegger', 'SSA53003.JPG', NULL, 236, 0.020, 0.75, 40.00, '01:00', 6, 2.77, '3', 21.00, 18.19, '2024-01-01', 'kleine_doos', 'epoxy', 3, 2.50),
(85, '20025', 'Sieraad 16 (halsketting verticale balk)', '20230715_140711.jpg', NULL, 8, 0.130, 0.00, 40.00, '01:00', 6, 2.77, '3', 21.00, 10.69, '2024-01-01', 'briefpakket', 'epoxy', 0, 2.50),
(86, '20026', 'Hand', '20230702_123130.jpg', NULL, 532, 0.020, 0.00, 40.00, '01:00', 6, 2.77, '3', 21.00, 31.19, '2024-01-01', 'kleine_doos', 'epoxy', 2, 5.00),
(87, '20027', 'handgreep kaarsenhouder', '20231216_094709.jpg', NULL, 319, 0.020, 0.00, 40.00, '01:00', 6, 2.77, '3', 21.00, 19.74, '2024-01-01', 'kleine_doos', 'epoxy', 1, 2.50),
(88, '20028', 'happy new year', '20230920_103218.jpg', NULL, 351, 0.020, 0.00, 40.00, '01:00', 6, 2.77, '3', 21.00, 20.82, '2024-01-01', 'kleine_doos', 'epoxy', 1, 2.50),
(89, '20029', 'Horoscoop hanger', 'SSA53096.JPG', NULL, 1, 0.130, 0.48, 40.00, '01:00', 6, 2.77, '3', 21.00, 9.96, '2024-01-01', 'briefpakket', 'epoxy', 0, 2.50),
(90, '20030', 'Horoscoop oorbellen', 'SSA53091.JPG', NULL, 2, 0.130, 0.20, 40.00, '01:00', 6, 2.77, '3', 21.00, 9.71, '2024-01-01', 'briefpakket', 'epoxy', 0, 2.50),
(91, '20031', 'Horoscoop sleutelhanger', 'SSA53089.JPG', NULL, 1, 0.130, 0.58, 40.00, '01:00', 6, 2.77, '3', 21.00, 10.13, '2024-01-01', 'briefpakket', 'epoxy', 0, 2.50),
(92, '20032', 'Kaarsenhouder', '20240222_113723.jpg', NULL, 208, 0.020, 0.00, 40.00, '01:00', 6, 2.77, '3', 21.00, 15.97, '2024-01-01', 'kleine_doos', 'epoxy', 2, 2.50),
(93, '20033', 'Kelk', 'SSA53129.JPG', NULL, 94, 0.020, 0.00, 40.00, '01:00', 6, 2.77, '3', 21.00, 12.11, '2024-01-01', 'kleine_doos', 'epoxy', 1, 2.50),
(94, '20034', 'kerstboom ', 'SSA53014.JPG', NULL, 570, 0.020, 0.00, 40.00, '01:00', 6, 2.77, '3', 21.00, 32.47, '2024-01-01', 'kleine_doos', 'epoxy', 1, 5.00),
(95, '20035', 'kerstboom hanger', '20230920_102954.jpg', NULL, 39, 0.020, 0.00, 40.00, '00:30', 6, 2.77, '3', 21.00, 8.13, '2024-01-01', 'kleine_doos', 'epoxy', 2, 2.50),
(96, '20036', 'kersthuisje', '20231001_173331.jpg', NULL, 196, 0.020, 0.66, 40.00, '01:00', 6, 2.77, '3', 21.00, 16.69, '2024-01-01', 'kleine_doos', 'epoxy', 1, 2.50),
(97, '20037', 'kerstman hanger', '20230920_103024.jpg', NULL, 39, 0.020, 0.00, 40.00, '00:30', 6, 2.77, '3', 21.00, 8.13, '2024-01-01', 'kleine_doos', 'epoxy', 2, 2.50),
(98, '20038', 'Kerstster hanger', '20231202_140907.jpg', NULL, 24, 0.020, 0.00, 40.00, '00:30', 6, 2.77, '3', 21.00, 7.62, '2024-01-01', 'kleine_doos', 'epoxy', 3, 2.50),
(99, '20039', 'klein vaasje', '20230902_192154.jpg', NULL, 273, 0.020, 0.00, 40.00, '1', 6, 2.77, '3', 21.00, 13.94, '2024-01-01', 'kleine_doos', 'epoxy', 1, 2.50),
(100, '20040', 'letter', '20230805_113053.jpg', NULL, 7, 0.020, 0.00, 40.00, '01:00', 6, 2.77, '3', 21.00, 9.16, '2024-01-01', 'kleine_doos', 'epoxy', 0, 2.50),
(101, '20041', 'Love', '20240107_170442.jpg', NULL, 122, 0.020, 0.00, 40.00, '01:00', 6, 2.77, '3', 21.00, 13.06, '2024-01-01', 'kleine_doos', 'epoxy', 1, 2.50),
(102, '20042', 'Love luxe', '20240131_144331.jpg', '', 170, 0.020, 0.00, 40.00, '01:00', 6, 2.77, '3', 21.00, 14.69, '2024-01-01', 'kleine_doos', 'epoxy', 2, 2.50),
(103, '20043', 'grote klok', '20240114_131619.jpg', NULL, 350, 0.020, 5.74, 40.00, '01:00', 6, 2.77, '3', 21.00, 30.51, '2024-01-01', 'grote_doos', 'epoxy', 1, 2.50),
(104, '20044', 'make-up spiegel', 'SSA53145.JPG', NULL, 135, 0.020, 1.11, 40.00, '01:00', 6, 2.77, '3', 21.00, 15.38, '2024-01-01', 'kleine_doos', 'epoxy', 2, 2.50),
(105, '20045', 'merry christmas', '20240905_164728.jpg', NULL, 244, 0.020, 0.00, 40.00, '01:00', 6, 2.77, '3', 21.00, 17.19, '2024-01-01', 'kleine_doos', 'epoxy', 1, 2.50),
(106, '20046', 'nachtlamp', '20230902_192428.jpg', NULL, 271, 0.020, 0.00, 40.00, '01:00', 6, 2.77, '3', 21.00, 18.11, '2024-01-01', 'kleine_doos', 'epoxy', 2, 2.50),
(107, '20047', 'Onderlegger + houder', '20230702_123259 (2).jpg', NULL, 345, 0.020, 0.00, 40.00, '01:00', 6, 2.77, '3', 21.00, 20.62, '2024-01-01', 'kleine_doos', 'epoxy', 1, 2.50),
(108, '20048', 'gevulde oesterschelp', 'SSA53076.JPG', NULL, 41, 0.020, 0.10, 40.00, '01:00', 6, 2.77, '3', 21.00, 10.49, '2024-01-01', 'kleine_doos', 'epoxy', 0, 2.50),
(109, '20049', 'ovalen bakje', 'SSA53149.JPG', NULL, 188, 0.020, 0.00, 40.00, '01:00', 6, 2.77, '3', 21.00, 15.30, '2024-01-01', 'kleine_doos', 'epoxy', 1, 2.50),
(110, '20050', 'Sieraad 4 (ovalen halsketting)', '20230715_135906.jpg', NULL, 9, 0.130, 0.00, 40.00, '01:00', 6, 2.77, '3', 21.00, 10.91, '2024-01-01', 'briefpakket', 'epoxy', 1, 2.50),
(111, '20051', 'pennenhouder', 'SSA53007.JPG', NULL, 372, 0.020, 0.00, 40.00, '01:00', 6, 2.77, '3', 21.00, 21.53, '2024-01-01', 'kleine_doos', 'epoxy', 1, 2.50),
(112, '20052', 'Sieraad 3 (halsketting klavertje 5)', '20230715_135829.jpg', NULL, 8, 0.130, 0.00, 40.00, '01:00', 6, 2.77, '3', 21.00, 10.69, '2024-01-01', 'kleine_doos', 'epoxy', 0, 2.50),
(113, '20053', 'Sieraad 2 (rechthoekig halsketting met steentjes)', '20230715_135743.jpg', NULL, 16, 0.130, 0.00, 40.00, '01:00', 6, 2.77, '3', 21.00, 12.45, '2024-01-01', 'kleine_doos', 'epoxy', 1, 2.50),
(114, '20054', 'rechthoekige vaas', 'SSA53165.JPG', NULL, 177, 0.020, 1.30, 40.00, '01:00', 6, 2.77, '3', 21.00, 17.13, '2024-01-01', 'kleine_doos', 'epoxy', 1, 2.50),
(115, '20055', 'rode halsketting', 'SSA53087.JPG', NULL, 8, 0.130, 0.00, 40.00, '01:00', 6, 2.77, '3', 21.00, 10.69, '2024-01-01', 'briefpakket', 'epoxy', 2, 2.50),
(116, '20056', 'Sieraad rond met bloem', '20240217_111437.jpg', NULL, 7, 0.130, 0.00, 40.00, '01:00', 6, 2.77, '3', 21.00, 10.47, '2024-01-01', 'briefpakket', 'epoxy', 1, 2.50),
(117, '20057', 'Sieraad 13 (ronde zwarte sieraad)', '20230715_140528.jpg', NULL, 7, 0.130, 0.00, 40.00, '01:00', 6, 2.77, '3', 21.00, 10.47, '2024-01-01', 'briefpakket', 'epoxy', 0, 2.50),
(118, '20058', 'roze-geel rechthoekig sieraad', '20230715_140114.jpg', NULL, 6, 0.130, 0.00, 40.00, '01:00', 6, 2.77, '3', 21.00, 10.25, '2024-01-01', 'briefpakket', 'epoxy', 0, 2.50),
(119, '20059', 'Roze rechthoekig sieraad', '20230715_140143.jpg', NULL, 5, 0.130, 0.00, 40.00, '01:00', 6, 2.77, '3', 21.00, 10.03, '2024-01-01', 'briefpakket', 'epoxy', 0, 2.50),
(120, '20060', 'Ruitvormig gekleurd sieraadsetje', '20230720_083404.jpg', NULL, 5, 0.130, 0.00, 40.00, '01:00', 6, 2.77, '3', 21.00, 10.03, '2024-01-01', 'briefpakket', 'epoxy', 1, 2.50),
(121, '20061', 'schaal', '20231216_094642.jpg', NULL, 143, 0.020, 0.00, 40.00, '01:00', 6, 2.77, '3', 21.00, 13.77, '2024-01-01', 'kleine_doos', 'epoxy', 0, 2.50),
(122, '20062', 'Serveerplank', '20230731_095915.jpg', NULL, 260, 0.020, 0.00, 40.00, '01:00', 6, 2.77, '3', 21.00, 17.74, '2024-01-01', 'kleine_doos', 'epoxy', 1, 2.50),
(123, '20063', 'set 2 kleine kerstbomen', '20230920_103715.jpg', NULL, 26, 0.020, 0.00, 40.00, '01:00', 6, 2.77, '3', 21.00, 9.81, '2024-01-01', 'kleine_doos', 'epoxy', 0, 2.50),
(124, '20065', 'set van 2 boekenleggers', '20230731_080933.jpg', NULL, 38, 0.020, 0.42, 40.00, '01:00', 6, 2.77, '3', 21.00, 10.93, '2024-01-01', 'kleine_doos', 'epoxy', 1, 2.50),
(125, '20066', 'Set van 2 roze rechthoekige sieraden', '20230802_085434.jpg', NULL, 12, 0.130, 0.00, 40.00, '01:00', 6, 2.77, '3', 21.00, 11.57, '2024-01-01', 'briefpakket', 'epoxy', 0, 2.50),
(126, '20067', 'set  van 3 kersthangers', '20230921_183606.jpg', NULL, 102, 0.020, 0.00, 40.00, '01:00', 6, 2.77, '3', 21.00, 12.38, '2024-01-01', 'briefpakket', 'epoxy', 1, 2.50),
(127, '20068', 'set 9 kerstfiguurtjes', '20240904_131713.jpg', NULL, 35, 0.020, 0.00, 40.00, '01:00', 6, 2.77, '3', 21.00, 10.11, '2024-01-01', 'briefpakket', 'epoxy', 0, 2.50),
(128, '20070', 'sieraad champagne', '20230715_135557 (2).jpg', NULL, 16, 0.130, 0.00, 40.00, '01:00', 6, 2.77, '3', 21.00, 12.45, '2024-01-01', 'briefpakket', 'epoxy', 1, 2.50),
(129, '20071', 'Sieraad Hartenverbinding (dubbel hartjes )', '20230715_140923.jpg', NULL, 3, 0.130, 0.00, 40.00, '01:00', 6, 2.77, '3', 21.00, 9.59, '2024-01-01', 'briefpakket', 'epoxy', 1, 2.50),
(130, '20072', 'sieraad mopshond', '20230810_082317.jpg', NULL, 5, 0.130, 0.00, 40.00, '01:00', 6, 2.77, '3', 21.00, 10.03, '2024-01-01', 'briefpakket', 'epoxy', 1, 2.50),
(131, '20073', 'sieraad lila bloem', '20240601_150052.jpg', NULL, 4, 0.130, 0.00, 40.00, '01:00', 6, 2.77, '3', 21.00, 9.81, '2024-01-01', 'briefpakket', 'epoxy', 1, 2.50),
(132, '20074', 'sieraadsetje  hartvormig', 'SSA53050.JPG', NULL, 15, 0.130, 0.00, 40.00, '01:00', 6, 2.77, '3', 21.00, 12.23, '2024-01-01', 'briefpakket', 'epoxy', 1, 2.50),
(133, '20075', 'sieraadsetje gekleurd', 'SSA53051.JPG', NULL, 14, 0.130, 0.00, 40.00, '01:00', 6, 2.77, '3', 21.00, 12.01, '2024-01-01', 'briefpakket', 'epoxy', 1, 2.50),
(134, '20076', 'sieraadset roze bloemen', 'SSA53047.JPG', NULL, 9, 0.130, 0.00, 40.00, '01:00', 6, 2.77, '3', 21.00, 10.91, '2024-01-01', 'briefpakket', 'epoxy', 0, 2.50),
(135, '20077', 'Vliegervormig sieraadsetje ', '20240601_145545.jpg', NULL, 4, 0.130, 0.00, 40.00, '01:00', 6, 2.77, '3', 21.00, 9.81, '2024-01-01', 'briefpakket', 'epoxy', 1, 2.50),
(136, '20078', 'Sieraden Rekje', '20230702_153553 (2).jpg', NULL, 135, 0.020, 0.00, 40.00, '01:00', 6, 2.77, '3', 21.00, 13.50, '2024-01-01', 'kleine_doos', 'epoxy', 1, 2.50),
(137, '20079', 'Sleutelhanger cijfers en letters', '20231128_153013.jpg', '', 7, 0.020, 0.75, 40.00, '00:15', 6, 2.77, '3', 21.00, 7.26, '2024-01-01', 'briefpakket', 'epoxy', 28, 2.50),
(138, '20080', 'sneeuvlok onderlegger', 'SSA53101.JPG', NULL, 139, 0.020, 0.00, 40.00, '01:00', 6, 2.77, '3', 21.00, 13.64, '2024-01-01', 'kleine_doos', 'epoxy', 1, 2.50),
(139, '20081', 'sneeuwvlokken', '20241020_185119.jpg', '', 12, 0.020, 0.00, 40.00, '00:20', 6, 2.77, '3', 21.00, 6.51, '2024-01-01', 'kleine_doos', 'epoxy', 2, 2.50),
(140, '20082', 'staande klok', '20240121_125706.jpg', NULL, 303, 0.020, 2.06, 40.00, '01:00', 6, 2.77, '3', 21.00, 22.68, '2024-01-01', 'kleine_doos', 'epoxy', 2, 2.50),
(141, '20083', 'staander ( houder)', 'SSA53102.JPG', NULL, 13, 0.020, 0.00, 40.00, '01:00', 6, 2.77, '3', 21.00, 9.37, '2024-01-01', 'kleine_doos', 'epoxy', 2, 2.50),
(142, '20084', 'vaas', '20230702_135142.jpg', NULL, 300, 0.020, 1.31, 40.00, '01:00', 6, 2.77, '3', 21.00, 21.31, '2024-01-01', 'kleine_doos', 'epoxy', 2, 2.50),
(143, '20088', 'Resin Deluxe Wijncadeau (wijn. glazen. snoepgoed)', 'IMG_0861-scaled.jpeg', NULL, 326, 0.020, 7.65, 40.00, '01:00', 6, 2.77, '3', 21.00, 32.93, '2024-01-01', 'grote_doos', 'epoxy', 0, 2.50),
(144, '20090', 'zittende beer', '20231222_083956.jpg', NULL, 220, 0.020, 0.00, 40.00, '01:00', 6, 2.77, '3', 21.00, 16.38, '2024-01-01', 'kleine_doos', 'epoxy', 1, 2.50),
(145, '20091', 'zwangere vrouw met foto', '20230805_113133.jpg', NULL, 248, 0.020, 0.27, 40.00, '01:30', 6, 2.77, '3', 21.00, 19.90, '2024-01-01', 'kleine_doos', 'epoxy', 2, 2.50),
(146, '20095', 'flessenhouder 5 onderleggers', '20240202_085933.jpg', NULL, 652, 0.020, 0.00, 40.00, '01:00', 6, 2.77, '3', 21.00, 35.25, '2024-01-01', 'kleine_doos', 'epoxy', 2, 5.00),
(147, '20099', 'Standaard Zeepompje ', '20230702_123002.jpg', NULL, 255, 0.020, 4.95, 40.00, '01:00', 6, 2.77, '3', 21.00, 25.95, '2024-01-01', 'kleine_doos', 'epoxy', 2, 2.50),
(148, '20100', 'stylevolle zeepompje ', '20240108_131734.jpg', '', 290, 0.016, 5.52, 40.00, '01:00', 6, 2.77, '3', 21.00, 26.14, '2024-01-01', 'kleine_doos', 'epoxy', 2, 2.50),
(149, '20101', '20021 met foto', '20230902_192220.jpg', NULL, 566, 0.020, 0.27, 40.00, '01:00', 6, 2.77, '3', 21.00, 32.80, '2024-01-01', 'kleine_doos', 'epoxy', 0, 5.00),
(150, '20102', 'oorbel sneeuwvlok', 'SSA53156.JPG', NULL, 4, 0.130, 0.28, 40.00, '00:30', 6, 2.77, '3', 21.00, 8.17, '2024-01-01', 'briefpakket', 'epoxy', 1, 2.50),
(151, '20103', 'oorbel kerstman', 'SSA53155.JPG', NULL, 4, 0.130, 0.28, 40.00, '00:30', 6, 2.77, '3', 21.00, 8.17, '2024-01-01', 'briefpakket', 'epoxy', 1, 2.50),
(152, '20104', 'oorbel kerstboom', 'SSA53154.JPG', NULL, 4, 0.130, 0.28, 40.00, '00:30', 6, 2.77, '3', 21.00, 8.17, '2024-01-01', 'briefpakket', 'epoxy', 1, 2.50),
(153, '20105', 'oorbel sneeuwman', 'SSA53168.JPG', NULL, 4, 0.130, 0.28, 40.00, '00:30', 6, 2.77, '3', 21.00, 8.17, '2024-01-01', 'briefpakket', 'epoxy', 1, 2.50),
(154, '20106', 'Set van 4 kerstoorbellen', 'SSA53167.JPG', NULL, 16, 0.130, 1.12, 40.00, '01:00', 6, 2.77, '3', 21.00, 14.35, '2024-01-01', 'briefpakket', 'epoxy', 1, 2.50),
(155, '20107', 'Geschenkmand  geurfles + Label my light geur flesje', 'c4b67bbf-410c-4096-9535-242cade8715c.JPG', NULL, 276, 0.020, 11.66, 40.00, '01:00', 6, 2.77, '3', 21.00, 38.03, '2024-01-01', 'grote_doos', 'epoxy', 0, 2.50),
(156, '20108', '1 voet', 'SSA53161.JPG', NULL, 13, 0.130, 0.58, 40.00, '01:00', 6, 2.77, '3', 21.00, 12.77, '2024-01-01', 'briefpakket', 'epoxy', 0, 2.50),
(157, '20109', 'voeten', 'SSA53153.JPG', NULL, 26, 0.130, 1.16, 40.00, '01:00', 6, 2.77, '3', 21.00, 16.62, '2024-01-01', 'briefpakket', 'epoxy', 1, 2.50),
(158, '20110', 'bijzettafel', '20240624_193204.jpg', NULL, 2000, 0.020, 10.00, 40.00, '2', 6, 2.77, '3', 21.00, 89.40, '2024-01-01', 'grote_doos', 'epoxy', 1, 10.00),
(160, '20112', 'kerstballen ( schijven )', '20231202_154218.jpg', NULL, 58, 0.020, 0.00, 40.00, '00:30', 6, 2.77, '3', 21.00, 8.77, '2024-01-01', 'kleine_doos', 'epoxy', 4, 2.50),
(161, '20113', 'kerstbal (grote schijf )', '20231202_154324.jpg', NULL, 41, 0.020, 0.00, 40.00, '00:30', 6, 2.77, '3', 21.00, 8.20, '2024-01-01', 'kleine_doos', 'epoxy', 2, 2.50),
(162, '20121', 'Dienblad rond 18 Æ', '20230702_153655 (2).jpg', NULL, 323, 0.020, 0.00, 40.00, '01:00', 6, 2.77, '3', 21.00, 19.87, '2024-01-01', 'kleine_doos', 'epoxy', 1, 2.50),
(163, '20123', 'Dienblad rechthoekig', '20240710_110330.jpg', NULL, 584, 0.020, 3.75, 40.00, '01:00', 6, 2.77, '3', 21.00, 39.30, '2024-01-01', 'kleine_doos', 'epoxy', 1, 5.00),
(164, '20125', 'hartje (groot)', '\n', NULL, 400, 0.020, 0.00, 40.00, '01:00', 6, 2.77, '3', 21.00, 22.48, '2024-01-01', 'kleine_doos', 'epoxy', 0, 2.50),
(165, '20132', 'hartje (groot) met foto', '', NULL, 400, 0.020, 0.27, 40.00, '01:00', 6, 2.77, '3', 21.00, 22.94, '2024-01-01', 'kleine_doos', 'epoxy', 1, 2.50),
(166, '20133', 'hartje (klein)', '', NULL, 165, 0.020, 0.00, 40.00, '01:00', 6, 2.77, '3', 21.00, 14.52, '2024-01-01', 'kleine_doos', 'epoxy', 0, 2.50),
(167, '20134', 'hartje (klein) met foto', '20230702_123052 (2).jpg', NULL, 165, 0.020, 0.27, 40.00, '01:00', 6, 2.77, '3', 21.00, 14.97, '2024-01-01', 'kleine_doos', 'epoxy', 1, 2.50),
(168, '20135', 'Denker L', 'SSA53105.JPG', NULL, 91, 0.020, 0.00, 40.00, '01:00', 6, 2.77, '3', 21.00, 12.01, '2024-01-01', 'kleine_doos', 'epoxy', 0, 2.50),
(169, '20136', 'Denker M', 'SSA53103.JPG', NULL, 76, 0.020, 0.00, 40.00, '01:00', 6, 2.77, '3', 21.00, 11.50, '2024-01-01', 'kleine_doos', 'epoxy', 0, 2.50),
(170, '20137', 'Denker R', 'SSA53104.JPG', NULL, 73, 0.020, 0.00, 40.00, '01:00', 6, 2.77, '3', 21.00, 11.40, '2024-01-01', 'kleine_doos', 'epoxy', 0, 2.50),
(171, '20138', 'klok met houder', '\n', NULL, 107, 0.020, 3.95, 40.00, '01:00', 6, 2.77, '3', 21.00, 19.24, '2024-01-01', 'kleine_doos', 'epoxy', 1, 2.50),
(172, '20141', 'Set Denkers', 'SSA53106.JPG', NULL, 240, 0.020, 0.00, 40.00, '01:00', 6, 2.77, '3', 21.00, 17.06, '2024-01-01', 'kleine_doos', 'epoxy', 3, 2.50),
(173, '20163', 'klokje', '20230702_122904.jpg', NULL, 94, 0.020, 3.95, 40.00, '01:00', 6, 2.77, '3', 21.00, 18.80, '2024-01-01', 'kleine_doos', 'epoxy', 1, 2.50),
(174, '20115', 'Sieraad met babyvoetje', '20230715_140610.jpg', NULL, 11, 0.130, 0.00, 40.00, '01:00', 6, 2.77, '3', 21.00, 11.35, '2024-01-01', 'briefpakket', 'epoxy', 1, 2.50),
(175, '20001', 'Halsketting 2 delig hart', 'SSA53061.JPG', NULL, 13, 0.130, 0.00, 40.00, '01:00', 6, 2.77, '3', 21.00, 11.79, '2024-03-13', 'briefpakket', 'epoxy', 1, 2.50),
(176, '20002', 'Halsketting 3 delig ', 'SSA53062.JPG', NULL, 8, 0.130, 0.00, 40.00, '01:00', 6, 2.77, '3', 21.00, 10.69, '2024-03-13', 'briefpakket', 'epoxy', 1, 2.50),
(177, '20003', 'Bloempothouder', '20240121_125740.jpg', NULL, 125, 0.020, 0.00, 40.00, '01:00', 6, 2.77, '3', 21.00, 13.16, '2024-03-13', 'kleine_doos', 'epoxy', 1, 2.50),
(178, '20004', 'Bloempothouder poes', '20240122_095907.jpg', NULL, 156, 0.020, 0.00, 40.00, '1', 6, 2.77, '3', 21.00, 9.98, '2024-03-13', 'kleine_doos', 'epoxy', 1, 2.50),
(179, '20005', 'boekenlegger', '20230712_105717.jpg', '', 19, 0.020, 0.21, 40.00, '00:20', 6, 2.77, '3', 21.00, 7.10, '2024-03-13', 'briefpakket', 'epoxy', 2, 2.50),
(180, '20006', 'Deco hart', '20240122_123343.jpg', NULL, 114, 0.020, 0.00, 40.00, '01:00', 6, 2.77, '3', 21.00, 12.79, '2024-03-13', 'kleine_doos', 'epoxy', 1, 2.50),
(181, '20114', 'groot ruitvormig sieradenset', '20240217_111802.jpg', NULL, 9, 0.130, 0.05, 40.00, '01:00', 6, 2.77, '3', 21.00, 10.99, '2024-03-13', '', 'epoxy', 1, 2.50),
(182, '20012', 'gekleurd sieradenset', 'SSA53068.JPG', '', 15, 0.130, 0.00, 40.00, '00:30', 6, 2.77, '3', 21.00, 10.11, '2024-03-13', 'briefpakket', 'epoxy', 4, 2.50),
(183, '20017', 'Flesopener', '20230702_123925.jpg', NULL, 40, 0.020, 0.63, 40.00, '01:00', 6, 2.77, '3', 21.00, 11.35, '2024-03-17', 'kleine_doos', 'epoxy', 6, 2.50),
(184, '20018', 'Set flesopeners', '20230724_160035.jpg', NULL, 76, 0.020, 1.26, 40.00, '01:00', 6, 2.77, '3', 21.00, 13.64, '2024-03-17', 'kleine_doos', 'epoxy', 0, 2.50),
(185, '20014', 'Etagiere', '347451334_1398890700902822_2303698714170225244_n (2).jpg', NULL, 373, 0.020, 3.94, 40.00, '01:00', 6, 2.77, '3', 21.00, 28.24, '2024-03-17', 'grote_doos', 'epoxy', 1, 2.50),
(186, '20013', 'Epo zeepparadijs geschenkmand', 'IMG_0684-scaled.webp', NULL, 290, 0.020, 11.56, 40.00, '01:30', 6, 2.77, '3', 21.00, 40.45, '2024-03-17', 'grote_doos', 'epoxy', 2, 2.50),
(187, '20118', 'Resi deluxe wijnmand (glazen + wijn )', '', NULL, 326, 0.020, 6.66, 40.00, '01:00', 6, 2.77, '3', 21.00, 31.25, '2024-03-17', 'grote_doos', 'epoxy', 2, 2.50),
(188, '20117', 'resin deluxe wijnmand  (+ chocolade )', '', NULL, 326, 0.020, 7.63, 40.00, '01:00', 6, 2.77, '3', 21.00, 32.90, '2024-03-17', 'grote_doos', 'epoxy', 0, 2.50),
(189, '20119', 'resin deluxe wijnmand  (+ chocolade +snoep)', '', NULL, 326, 0.020, 8.62, 40.00, '01:00', 6, 2.77, '3', 21.00, 34.57, '2024-03-17', 'grote_doos', 'epoxy', 0, 2.50),
(190, '20015', 'extra letter', '20230809_171644.jpg', NULL, 7, 0.020, 0.00, 40.00, '00:30', 6, 2.77, '3', 21.00, 7.05, '2024-03-17', 'kleine_doos', 'epoxy', 0, 2.50),
(191, '20016', 'extra sleutelhanger', '20231128_152955.jpg', NULL, 7, 0.020, 0.58, 40.00, '00:30', 6, 2.77, '3', 21.00, 8.03, '2024-03-17', 'kleine_doos', 'epoxy', 0, 2.50),
(192, '20096', '5 flessenhouder + onderleggers ( houder )', '20240131_144348.jpg', NULL, 290, 0.020, 0.00, 40.00, '01:00', 6, 2.77, '3', 21.00, 18.75, '2024-03-18', 'kleine_doos', 'epoxy', 2, 2.50),
(193, '20097', '5 flessenhouder + onderleggers ( 5 onderleggers )', '20240131_144303.jpg', NULL, 370, 0.020, 0.00, 40.00, '01:00', 6, 2.77, '3', 21.00, 21.46, '2024-03-18', 'kleine_doos', 'epoxy', 5, 2.50),
(194, '20098', '( 6 onderleggers )5 flessenhouder + onderleggers ', '20240131_144303.jpg', NULL, 444, 0.020, 0.00, 40.00, '01:00', 6, 2.77, '3', 21.00, 23.97, '2024-03-18', 'kleine_doos', 'epoxy', 6, 2.50),
(195, '20000', 'licht kleurig rechthoekig halsketting', '20240403_131114.jpg', NULL, 9, 0.130, 0.00, 40.00, '01:00', 6, 2.77, '3', 21.00, 10.91, '2024-04-03', 'briefpakket', 'epoxy', 0, 2.50),
(196, '20120', 'hartvormige kandelaar', '20240403_131208.jpg', NULL, 58, 0.020, 0.00, 40.00, '01:00', 6, 2.77, '3', 21.00, 10.89, '2024-04-03', 'kleine_doos', 'epoxy', 1, 2.50),
(197, '20122', 'ronde kandelaar', '20240403_131318.jpg', NULL, 55, 0.020, 0.00, 40.00, '01:00', 6, 2.77, '3', 21.00, 10.79, '2024-04-03', 'kleine_doos', 'epoxy', 1, 2.50),
(198, '20124', 'Sieraad rond met bloem', '20230715_135946.jpg', NULL, 4, 0.130, 0.00, 40.00, '01:00', 6, 2.77, '3', 21.00, 9.81, '2024-04-06', 'briefpakket', 'epoxy', 1, 2.50),
(199, '20126', 'Stervormige kandelaar', '20240407_090504.jpg', NULL, 35, 0.020, 0.00, 40.00, '01:00', 6, 2.77, '3', 21.00, 10.11, '2024-04-07', 'kleine_doos', 'epoxy', 1, 2.50),
(200, '20127', 'Kegel', '20240422_085433.jpg', NULL, 260, 0.020, 0.00, 40.00, '01:00', 6, 2.77, '3', 21.00, 17.74, '2024-04-19', 'kleine_doos', 'epoxy', 2, 2.50),
(201, '20128', 'baby', '20240419_151202.jpg', NULL, 55, 0.020, 0.00, 40.00, '01:00', 6, 2.77, '3', 21.00, 10.79, '2024-04-21', 'kleine_doos', 'epoxy', 3, 2.50),
(202, '20129', 'Sieraad poes bol wol + hart', '20240422_144105.jpg', NULL, 4, 0.130, 0.00, 40.00, '01:00', 6, 2.77, '3', 21.00, 9.81, '2024-04-22', 'briefpakket', 'epoxy', 1, 2.50),
(203, '20130', 'rechthoekig sieraad poes met hartjes en poot', '20240423_103437.jpg', NULL, 8, 0.130, 0.00, 40.00, '01:00', 6, 2.77, '3', 21.00, 10.69, '2024-04-23', 'briefpakket', 'epoxy', 1, 2.50),
(204, '20131', 'ovalen sieraad met voetjes', '20240423_103755.jpg', NULL, 3, 0.130, 0.00, 40.00, '01:00', 6, 2.77, '3', 21.00, 9.59, '2024-04-23', 'briefpakket', 'epoxy', 1, 2.50),
(205, '20139', 'vierkant sieraad met voetjes', '20240423_103507.jpg', NULL, 2, 0.130, 0.00, 40.00, '01:00', 6, 2.77, '3', 21.00, 9.37, '2024-04-23', 'briefpakket', 'epoxy', 1, 2.50),
(206, '20140', 'ovalen sieraad poes', '20240423_103533.jpg', NULL, 4, 0.130, 0.00, 40.00, '01:00', 6, 2.77, '3', 21.00, 9.81, '2024-04-23', 'briefpakket', 'epoxy', 1, 2.50),
(207, '20142', 'rechthoekig sieraad poes met bril', '20240423_103621.jpg', NULL, 3, 0.130, 0.00, 40.00, '01:00', 6, 2.77, '3', 21.00, 9.59, '2024-04-23', 'briefpakket', 'epoxy', 1, 2.50),
(208, '20144', 'rechthoekig sieraad hond', '20240423_103715.jpg', NULL, 4, 0.130, 0.00, 40.00, '01:00', 6, 2.77, '3', 21.00, 9.81, '2024-04-23', 'briefpakket', 'epoxy', 1, 2.50),
(209, '20145', 'vierkant sieraad hond', '20240423_103555.jpg', NULL, 3, 0.130, 0.00, 40.00, '01:00', 6, 2.77, '3', 21.00, 9.59, '2024-04-23', 'briefpakket', 'epoxy', 1, 2.50),
(210, '20146', 'vierkant sieraad honden in mand', '20240423_103737.jpg', NULL, 5, 0.130, 0.00, 40.00, '01:00', 6, 2.77, '3', 21.00, 10.03, '2024-04-23', 'briefpakket', 'epoxy', 1, 2.50),
(211, '20143', 'bol sieraad poes', '20240423_103650.jpg', NULL, 4, 0.130, 0.00, 40.00, '01:00', 6, 2.77, '3', 21.00, 9.81, '2024-04-23', 'briefpakket', 'epoxy', 1, 2.50),
(212, '20147', 'klok in hart', '20240605_155335.jpg', NULL, 1182, 0.020, 2.07, 40.00, '01:00', 6, 2.77, '3', 21.00, 61.80, '2024-06-05', 'kleine_doos', 'epoxy', 1, 8.00),
(213, '20148', 'baby wolk', '20240611_161214.jpg', NULL, 71, 0.020, 1.64, 40.00, '01:00', 6, 2.77, '3', 21.00, 14.11, '2024-06-11', 'kleine_doos', 'epoxy', 1, 2.50),
(214, '20149', 'Engel', '20240611_162215.jpg', NULL, 35, 0.020, 0.50, 40.00, '01:00', 6, 2.77, '3', 21.00, 10.96, '2024-06-11', 'kleine_doos', 'epoxy', 1, 2.50),
(215, '20150', 'set van 3 haarspelden', '20240611_160239.jpg', NULL, 9, 0.020, 4.09, 40.00, '01:00', 6, 2.77, '3', 21.00, 16.16, '2024-06-11', 'briefpakket', 'epoxy', 1, 2.50),
(216, '20151', 'Paardenkop', '20240615_091423.jpg', NULL, 182, 0.020, 0.00, 40.00, '01:00', 6, 2.77, '3', 21.00, 15.09, '2024-06-15', 'kleine_doos', 'epoxy', 0, 2.50),
(217, '20152', 'Zwaan', '20240615_091820.jpg', NULL, 172, 0.020, 0.00, 40.00, '01:00', 6, 2.77, '3', 21.00, 14.75, '2024-06-15', 'kleine_doos', 'epoxy', 1, 2.50),
(218, '20153', 'Poes', '20240626_165954.jpg', NULL, 115, 0.020, 0.00, 40.00, '01:00', 6, 2.77, '3', 21.00, 12.82, '2024-06-26', 'kleine_doos', 'epoxy', 1, 2.50),
(219, '20154', 'Kandelaar', '20240626_165710.jpg', NULL, 118, 0.020, 0.00, 40.00, '01:00', 6, 2.77, '3', 21.00, 12.93, '2024-06-26', 'kleine_doos', 'epoxy', 1, 2.50),
(225, '20155', 'Terrazzo Ovale bakje', 'IMG_23092024-08-2418_39_18.jpg', NULL, 149, 0.013, 0.00, 40.00, '00:30', 6, 2.77, '3', 21.00, 10.09, '2024-08-24', 'klein_doos', 'terrazzo', 2, 0.00),
(226, '20156', 'Terrazzo Kaarshouder', 'IMG_23102024-08-2418_39_19.jpg', NULL, 233, 0.013, 0.00, 40.00, '00:30', 6, 2.77, '3', 21.00, 11.94, '2024-08-24', 'klein_doos', 'terrazzo', 2, 0.00),
(227, '20157', 'schelp', '20240901_153339.jpeg', '', 83, 0.008, 0.00, 40.00, '00:30', 6, 2.77, '3', 21.00, 7.93, '2024-09-01', 'klein_doos', 'terrazzo', 4, 0.00),
(228, '20158', 'Kerstengel met hartje', '20240904_113845.jpg', NULL, 22, 0.020, 0.00, 40.00, '00:30', 6, 2.77, '2,3', 21.00, 7.56, '2024-09-05', 'klein_doos', 'epoxy', 1, 0.00),
(229, '20159', 'Set van 6 kerstfiguren', '20240904_132207.jpg', NULL, 202, 0.020, 0.00, 40.00, '01:00', 6, 2.77, '2,3', 21.00, 15.77, '2024-09-05', 'klein_doos', 'epoxy', 1, 0.00),
(230, '20160', 'Sneeuwman', '20240904_113732.jpg', NULL, 38, 0.020, 0.00, 40.00, '00:30', 6, 2.77, '2,3', 21.00, 8.10, '2024-09-05', 'klein_doos', 'epoxy', 1, 0.00),
(232, '20162', 'Sneeuwvlok', '20240904_114032.jpg', NULL, 33, 0.020, 0.00, 40.00, '00:30', 6, 2.77, '2,3', 21.00, 7.93, '2024-09-05', 'klein_doos', 'epoxy', 1, 0.00),
(234, '20164', 'Kerstboom (klein)', '20240904_113705.jpg', NULL, 34, 0.020, 0.00, 40.00, '00:30', 6, 2.77, '2,3', 21.00, 7.96, '2024-09-05', 'klein_doos', 'epoxy', 0, 0.00),
(235, '20165', 'Gele ster', '20240904_113650.jpg', NULL, 31, 0.020, 0.00, 40.00, '00:30', 6, 2.77, '2,3', 21.00, 7.86, '2024-09-05', 'klein_doos', 'epoxy', 0, 0.00),
(236, '20166', 'Kerstsok', '20240904_113747.jpg', NULL, 34, 0.020, 0.00, 40.00, '00:30', 6, 2.77, '2,3', 21.00, 7.96, '2024-09-05', 'klein_doos', 'epoxy', 1, 0.00),
(237, '20167', 'Sok met hartjes', '20240904_113827.jpg', NULL, 32, 0.020, 0.00, 40.00, '00:30', 6, 2.77, '2,3', 21.00, 7.89, '2024-09-05', 'klein_doos', 'epoxy', 1, 0.00),
(238, '20168', 'kerstboom XMAS', '20240904_114226.jpg', NULL, 270, 0.020, 0.00, 40.00, '01:00', 6, 2.77, '2,3', 21.00, 18.07, '2024-09-05', 'klein_doos', 'epoxy', 1, 0.00),
(239, '20169', 'Kerstboom met voet', '20240904_113905.jpg', NULL, 44, 0.020, 0.00, 40.00, '00:45', 6, 2.77, '2,3', 21.00, 9.36, '2024-09-05', 'klein_doos', 'epoxy', 1, 0.00),
(240, '20170', 'Set van 4 kerstmedailles', '20240904_131803.jpg', NULL, 30, 0.020, 0.00, 40.00, '01:00', 6, 2.77, '2,3', 21.00, 9.94, '2024-09-05', 'klein_doos', 'epoxy', 1, 0.00),
(241, '20171', 'Kerstengel', '20240904_113806.jpg', NULL, 28, 0.020, 0.00, 40.00, '00:30', 6, 2.77, '2,3', 21.00, 7.76, '2024-09-06', 'klein_doos', 'epoxy', 0, 0.00),
(242, '20172', 'Set van 4 schelpen', '20240912_171342.jpg', '', 325, 0.008, 0.00, 40.00, '02:00', 6, 2.77, '3', 21.00, 17.57, '2024-09-12', 'klein_doos', 'terrazzo', 1, 0.00),
(243, '20173', 'Ovalen onderzetter', '20240912_181932.jpg', '', 173, 0.008, 0.00, 40.00, '00:40', 6, 2.77, '1,2,3', 21.00, 9.86, '2024-09-12', 'klein_doos', 'terrazzo', 1, 0.00),
(244, '20174', 'Sierlijk kaarspotje', '20240912_171040.jpg', '', 168, 0.008, 0.00, 40.00, '00:30', 6, 2.77, '1,2,3', 21.00, 9.09, '2024-09-12', 'klein_doos', 'terrazzo', 1, 0.00),
(245, '20175', 'Kaars pot met deksel', '20240912_182012.jpg', '', 253, 0.008, 0.00, 40.00, '00:30', 6, 2.77, '1,2,3', 21.00, 10.24, '2024-09-12', 'klein_doos', 'terrazzo', 0, 0.00),
(246, '20176', 'Klein kaars potje met deksel', '20240913_090518.jpg', '', 102, 0.008, 0.00, 40.00, '00:30', 6, 2.77, '1,2,3', 21.00, 8.19, '2024-09-13', 'klein_doos', 'terrazzo', 1, 0.00),
(247, '20177', 'Balpen', '20240915_142747.jpg', '', 12, 0.016, 0.00, 40.00, '00:30', 6, 2.77, '3', 21.00, 7.14, '2024-09-15', 'klein_doos', 'epoxy', 2, 0.00),
(248, '20178', 'Terrazzo Style volle zeepompje', '20240915_142600.jpg', '', 420, 0.008, 5.52, 40.00, '01:00', 6, 2.77, '3', 21.00, 23.97, '2024-09-19', 'klein_doos', 'terrazzo', 0, 0.00),
(249, '20179', 'Eland', '20241020_112505.jpg', NULL, 68, 0.016, 0.00, 40.00, '00:30', 6, 2.77, '1,2,3', 21.00, 8.65, '2024-10-21', 'klein_doos', 'epoxy', 1, 2.50),
(250, '20180', 'Lotusbloem', '20241020_104729.jpg', NULL, 97, 0.016, 0.00, 40.00, '00:30', 6, 2.77, '1,2,3', 21.00, 9.44, '2024-10-21', 'klein_doos', 'epoxy', 1, 2.50),
(251, '20181', 'Moeder - kind', '20241020_104627.jpg', NULL, 195, 0.016, 0.00, 40.00, '00:30', 6, 2.77, '1,2,3', 21.00, 12.10, '2024-10-21', 'klein_doos', 'epoxy', 1, 2.50),
(252, '20182', 'Glitter kerstblok', '../product_img/no_picture.jpg', NULL, 124, 0.016, 0.00, 40.00, '00:30', 6, 2.77, '1,2,3', 21.00, 10.17, '2024-10-21', 'klein_doos', 'epoxy', 1, 2.50),
(253, '20183', 'glitterblok bloemen', '20241020_104842.jpg', NULL, 94, 0.016, 0.00, 40.00, '00:30', 6, 2.77, '1,2,3', 21.00, 9.36, '2024-10-21', 'klein_doos', 'epoxy', 1, 2.50),
(254, '20184', 'hartje', '20241020_104755.jpg', NULL, 42, 0.016, 0.00, 40.00, '00:30', 6, 2.77, '1,2,3', 21.00, 7.95, '2024-10-21', 'klein_doos', 'epoxy', 1, 2.50),
(255, '20185', 'glitterblok nieuwjaar', '20241021_100448.jpg', NULL, 81, 0.016, 0.00, 40.00, '00:30', 6, 2.77, '1,2,3', 21.00, 9.01, '2024-10-21', 'klein_doos', 'epoxy', 1, 2.50),
(256, '20186', 'diepe schaal', '20241021_100908.jpg', NULL, 500, 0.016, 0.00, 40.00, '00:45', 6, 2.77, '1,2,3', 21.00, 24.60, '2024-10-21', 'klein_doos', 'epoxy', 1, 5.00),
(257, '20187', 'Slede', '20241021_104123.jpg', NULL, 92, 0.016, 0.00, 40.00, '00:45', 6, 2.77, '1,2,3', 21.00, 10.36, '2024-10-21', 'klein_doos', 'epoxy', 1, 2.50),
(258, '20188', 'Magneet letter', '20241030_130200.jpg', '', 8, 0.016, 0.87, 40.00, '00:20', 6, 2.77, '1,2,3', 21.00, 7.79, '2024-10-30', 'briefpakket', 'epoxy', 9, 2.50),
(259, '20189', 'Kerst kegel', '20241031_123731.jpg', NULL, 260, 0.016, 2.00, 40.00, '00:50', 6, 2.77, '1,2,3', 21.00, 18.66, '2024-11-01', 'klein_doos', 'epoxy', 1, 2.50),
(260, '20190', 'ovalen sierlijk sieradenset', '20241101_160711.jpg', '', 9, 0.016, 0.00, 40.00, '00:20', 6, 2.77, '1,2,3', 21.00, 6.35, '2024-11-01', 'briefpakket', 'epoxy', 1, 2.50),
(261, '20191', 'Hartje kandelaar', '20241101_174815.jpg', NULL, 101, 0.008, 0.00, 40.00, '00:30', 6, 2.77, '1,2,3', 21.00, 8.18, '2024-11-01', 'klein_doos', 'terrazzo', 1, 2.50),
(262, '20192', 'Drievoudige Terrazzo Theelichthouder', '20241101_164250.jpg', NULL, 368, 0.008, 0.00, 40.00, '00:30', 6, 2.77, '1,2,3', 21.00, 11.80, '2024-11-02', 'klein_doos', 'terrazzo', 1, 2.50),
(263, '20193', 'Stervormige Theelichthouder van Terrazzo', '20241101_164934.jpg', '', 58, 0.008, 0.00, 40.00, '00:20', 6, 2.77, '1,2,3', 21.00, 6.89, '2024-11-02', 'klein_doos', 'terrazzo', 1, 2.50),
(264, '20194', 'Moderne Ronde Kaarshouder in Marmerlook', '20241101_164952.jpg', '', 111, 0.008, 0.00, 40.00, '00:20', 6, 2.77, '1,2,3', 21.00, 7.61, '2024-11-02', 'klein_doos', 'terrazzo', 1, 2.50),
(265, '20195', 'Foto staander', '20241101_164547.jpg', '', 59, 0.008, 0.00, 40.00, '00:20', 6, 2.77, '2', 21.00, 6.90, '2024-11-02', 'briefpakket', 'terrazzo', 1, 2.50),
(266, '20196', 'Luxe golvende epoxy onderzetter', '20241129_151305.jpg', '', 299, 0.016, 0.00, 40.00, '00:45', 6, 2.77, '1,2,3', 21.00, 15.97, '2024-11-29', 'grote_doos', 'epoxy', 1, 2.50),
(267, '20197', 'Kersttafereel in Epoxy met LED-verlichting', '20241130_154946.jpg', 'afmeting 15cm', 645, 0.016, 7.24, 25.00, '00:40', 6, 2.77, '1,2,3', 21.00, 35.79, '2024-11-30', 'klein_doos', 'epoxy', 1, 5.00),
(268, '20198', 'Epoxyroos in Blauwglitter', '20241130_154745.jpg', NULL, 26, 0.016, 0.00, 40.00, '00:20', 6, 2.77, '1,2,3', 21.00, 6.81, '2024-11-30', 'briefpakket', 'epoxy', 1, 2.50),
(269, '20199', 'Epoxy O-ring Vaasje met Glaskoker', '20241130_154603.jpg', NULL, 237, 0.016, 0.65, 40.00, '00:30', 6, 2.77, '1,2,3', 21.00, 14.33, '2024-11-30', 'klein_doos', 'epoxy', 1, 2.50),
(270, '20200', 'Vriendschapsvaas in Terrazzo-stijl', '20241130_155141.jpg', '', 468, 0.009, 0.00, 40.00, '00:25', 6, 2.77, '', 21.00, 13.59, '2024-11-30', 'klein_doos', 'terrazzo', 1, 2.50),
(271, '20201', 'Hexagon Kaarshouder in Minimalistische Stijl', '20241130_155209.jpg', NULL, 154, 0.009, 0.00, 40.00, '00:15', 6, 2.77, '1,2,3', 21.00, 8.10, '2024-11-30', 'klein_doos', 'terrazzo', 1, 2.50),
(272, '20202', 'Minimalistische kaarsenhouder met huisjesdesign', '20241205_145936.jpg', 'afmetingen L: 19 cm B: 105 cm H: 125 cm', 462, 0.009, 0.00, 40.00, '00:20', 6, 2.77, '2', 21.00, 13.15, '2024-12-06', 'klein_doos', 'terrazzo', 1, 2.50);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `inkoop_products`
--

CREATE TABLE `inkoop_products` (
  `id` int NOT NULL,
  `sku` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `product_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `product_description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `purchase_price` decimal(10,3) NOT NULL,
  `extra_parts_price` decimal(10,2) DEFAULT NULL,
  `margin` decimal(5,2) DEFAULT NULL,
  `hours_worked` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created_by_user` int DEFAULT NULL,
  `company_cost_per_product` decimal(10,2) DEFAULT '94.90',
  `sold_in_branches` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `vat_percentage` decimal(5,2) DEFAULT '21.00',
  `total_product_price` decimal(10,2) DEFAULT NULL,
  `created_on` date DEFAULT NULL,
  `shipping_method` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `category` varchar(255) COLLATE utf8mb4_general_ci DEFAULT 'inkoop',
  `stock` int DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `kaarsen_products`
--

CREATE TABLE `kaarsen_products` (
  `id` int NOT NULL,
  `sku` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `product_image` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `product_description` text COLLATE utf8mb4_general_ci,
  `amount_paraffin_grams` int NOT NULL,
  `amount_stearin_grams` int NOT NULL,
  `price_per_gram_paraffin` decimal(10,3) NOT NULL,
  `price_per_gram_stearin` decimal(10,3) NOT NULL,
  `extra_parts_price` decimal(10,2) DEFAULT NULL,
  `margin` decimal(5,2) DEFAULT NULL,
  `hours_worked` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `created_by_user` int DEFAULT NULL,
  `company_cost_per_product` decimal(10,2) DEFAULT '94.90',
  `sold_in_branches` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `vat_percentage` decimal(5,2) DEFAULT '21.00',
  `total_product_price` decimal(10,2) DEFAULT NULL,
  `created_on` date DEFAULT NULL,
  `shipping_method` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `category` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `stock` int DEFAULT '0',
  `hourly_rate` decimal(10,2) DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `kaarsen_products`
--

INSERT INTO `kaarsen_products` (`id`, `sku`, `title`, `product_image`, `product_description`, `amount_paraffin_grams`, `amount_stearin_grams`, `price_per_gram_paraffin`, `price_per_gram_stearin`, `extra_parts_price`, `margin`, `hours_worked`, `created_by_user`, `company_cost_per_product`, `sold_in_branches`, `vat_percentage`, `total_product_price`, `created_on`, `shipping_method`, `category`, `stock`, `hourly_rate`) VALUES
(1, '30000', 'beertje 1', '35_3fcf19cb.jpg', '', 54, 6, 0.006, 0.008, 0.24, 40.00, '00:20', 6, 2.77, '1,2,3', 21.00, 7.14, '2024-02-25', 'kleine doos', 'kaars', 1, 2.50),
(2, '30001', 'beertje 2', '20240722_085817.jpg', '', 54, 6, 0.006, 0.008, 0.24, 40.00, '00:30', 6, 2.77, '1,2,3', 21.00, 7.85, '2024-03-04', 'kleine_doos', 'kaars', 1, 2.50),
(3, '30002', 'kaars bol', 'IMG-20240314-WA0011.jpeg', '', 153, 17, 0.008, 0.008, 0.24, 40.00, '00:30', 6, 2.77, '3', 21.00, 9.52, '2024-03-14', 'kleine_doos', 'kaars', 1, 2.50),
(4, '30004', 'hond', '20240722_085830.jpg', '', 63, 7, 0.006, 0.008, 0.24, 40.00, '00:30', 6, 2.77, '1,2,3', 21.00, 7.95, '2024-03-17', 'kleine_doos', 'kaars', 1, 2.50),
(5, '30005', 'gekrulde kaars', '20241109_115248.jpg', '', 54, 6, 0.006, 0.008, 0.24, 40.00, '00:20', 5, 2.77, '3', 21.00, 7.14, '2024-03-17', 'kleine_doos', 'kaars', 1, 2.50),
(6, '30006', 'pijler kaars', 'IMG-20240314-WA0013.jpeg', '', 99, 11, 0.006, 0.008, 0.24, 40.00, '00:30', 6, 2.77, '1,2,3', 21.00, 8.37, '2024-03-17', 'kleine_doos', 'kaars', 1, 2.50),
(7, '30007', 'Geurloos kaars | potje 80 ml', 'IMG_1318 2024-03-24 16_01_55.JPG', '', 72, 8, 0.006, 0.008, 0.24, 40.00, '00:20', 6, 2.77, '1,2,3', 21.00, 7.35, '2024-03-24', 'kleine_doos', 'kaars', 3, 2.50),
(8, '30008', 'Monkey frats | geurkaars | potje 80ml', 'IMG_1320 2024-03-24 16_01_56.JPG', '', 72, 8, 0.006, 0.008, 0.32, 40.00, '00:20', 6, 2.77, '1,2,3', 21.00, 7.49, '2024-03-24', 'kleine_doos', 'kaars', 1, 2.50),
(9, '30003', 'Amber | geurkaars | potje 80ml', 'IMG_1327 2024-03-24 16_59_55.JPG', '', 72, 8, 0.006, 0.008, 0.32, 40.00, '00:20', 6, 2.77, '1,2,3', 21.00, 7.49, '2024-03-24', 'kleine_doos', 'kaars', 2, 2.50),
(10, '30009', 'bubbel box kaars 6x6', 'IMG_1765 2024-06-07 07_38_48.JPG', '', 135, 15, 0.006, 0.008, 0.26, 40.00, '00:20', 6, 2.77, '3', 21.00, 8.12, '2024-06-07', 'kleine_doos', 'kaars', 0, 2.50),
(11, '30010', 'bubbel box kaars 4x4', 'IMG_1766 2024-06-07 07_38_49.JPG', '', 36, 4, 0.006, 0.008, 0.24, 40.00, '00:20', 6, 2.77, '1,2,3', 21.00, 6.93, '2024-06-07', 'kleine_doos', 'kaars', 0, 2.50),
(12, '30011', 'bubbel box kaars set', 'IMG_1768 2024-06-07 07_38_50.JPG', '', 171, 19, 0.006, 0.008, 0.48, 40.00, '00:25', 6, 2.77, '1,2,3', 21.00, 9.27, '2024-06-07', 'kleine_doos', 'kaars', 0, 2.50),
(13, '30012', 'bubbel box kaars set 3 stuks', 'IMG_1768 2024-06-07 07_38_50.JPG', '', 207, 23, 0.006, 0.008, 0.48, 40.00, '00:30', 6, 2.77, '1,2,3', 21.00, 10.04, '2024-06-07', 'kleine_doos', 'kaars', 1, 2.50),
(14, '30013', 'Men body', '20240722_085846.jpg', '', 90, 10, 0.006, 0.008, 0.24, 40.00, '00:30', 6, 2.77, '1,2,3', 21.00, 8.27, '2024-06-15', 'kleine_doos', 'kaars', 1, 2.50),
(15, '30014', 'handen kaars', '20240615_091459.jpg', '', 153, 17, 0.006, 0.008, 0.24, 40.00, '00:30', 6, 2.77, '1,2,3', 21.00, 9.00, '2024-06-15', 'kleine_doos', 'kaars', 1, 2.50),
(16, '30015', 'Cashmere Woods | geurkaars | potje 80ml', 'IMG_1320 2024-03-24 16_01_56.JPG', '', 72, 8, 0.006, 0.008, 0.32, 40.00, '00:20', 6, 2.77, '1,2,3', 21.00, 7.49, '2024-06-06', 'kleine_doos', 'kaars', 1, 2.50),
(17, '30016', 'Cashmere Woods / Amber | geurkaars | potje 80ml', 'IMG_1320 2024-03-24 16_01_56.JPG', '', 72, 8, 0.006, 0.008, 0.32, 40.00, '00:20', 6, 2.77, '1,2,3', 21.00, 7.49, '2024-06-06', 'kleine_doos', 'kaars', 1, 2.50),
(18, '30017', 'Lady body', '20240722_085905.jpg', '', 72, 8, 0.006, 0.008, 0.24, 40.00, '00:30', 6, 2.77, '1,2,3', 21.00, 8.06, '2024-07-12', 'kleine_doos', 'kaars', 1, 2.50),
(19, '30018', 'Vogel kaars', '20240712_105704.jpg', '', 36, 4, 0.006, 0.008, 0.24, 40.00, '00:30', 6, 2.77, '1,2,3', 21.00, 7.64, '2024-07-12', 'kleine_doos', 'kaars', 1, 2.50),
(20, '30019', 'dikke gekrulde kaars', '20240712_105455.jpg', '', 162, 18, 0.006, 0.008, 0.24, 40.00, '00:30', 6, 2.77, '1,2,3', 21.00, 9.11, '2024-07-12', 'kleine_doos', 'kaars', 1, 2.50),
(21, '30020', 'Kubus', '20240712_105519.jpg', '', 324, 36, 0.006, 0.008, 0.24, 40.00, '00:30', 6, 2.77, '1,2,3', 21.00, 11.00, '2024-07-12', 'kleine_doos', 'kaars', 1, 2.50),
(22, '30021', 'Kegel ', '20240712_105544.jpg', '', 144, 16, 0.006, 0.008, 0.24, 40.00, '00:30', 6, 2.77, '1,2,3', 21.00, 8.90, '2024-07-12', 'kleine_doos', 'kaars', 1, 2.50),
(23, '30022', 'Boogkaars', '20240722_085920.jpg', '', 126, 14, 0.006, 0.008, 0.24, 40.00, '00:30', 6, 2.77, '1,2,3', 21.00, 8.69, '2024-07-22', 'kleine_doos', 'kaars', 1, 2.50),
(24, '30023', 'kleine kubus', '20240722_085943.jpg', '', 27, 3, 0.006, 0.008, 0.24, 40.00, '00:20', 6, 2.77, '1,2,3', 21.00, 6.83, '2024-07-22', 'kleine_doos', 'kaars', 1, 2.50),
(25, '30024', 'middel kubus', '20240722_085935.jpg', '', 135, 15, 0.006, 0.008, 0.24, 40.00, '00:30', 6, 2.77, '1,2,3', 21.00, 8.79, '2024-07-22', 'kleine_doos', 'kaars', 1, 2.50),
(26, '30025', 'Terrazzo kaarspotje met Halloween geur', 'IMG_2516.jpg', 'ronde pot dia 6 cm', 117, 13, 0.008, 0.006, 2.55, 40.00, '00:40', 1, 2.77, '1,2,3', 21.00, 13.55, '2024-09-22', 'klein_doos', 'kaars', 0, 0.00),
(27, '30026', 'Terrazzo kaarspotje met Halloween geur', 'IMG_2516.jpg', 'ronde pot dia 6 cm', 117, 13, 0.008, 0.006, 2.55, 40.00, '00:40', 1, 2.77, '3', 21.00, 13.55, '2024-09-22', 'klein_doos', 'kaars', 0, 2.50),
(28, '30027', 'Terrazzo kaarspotje sierlijk met Halloween geur', 'IMG_2518.jpg', 'rond potje dia meter 45 mm', 90, 10, 0.008, 0.006, 1.69, 40.00, '00:40', 1, 2.77, '1,2,3', 21.00, 11.70, '2024-09-22', 'klein_doos', 'kaars', 0, 2.50),
(29, '30028', 'Terrazzo mini kaarspotje met Halloween geur', 'IMG_2519.jpg', 'rond dia 30mm', 27, 3, 0.008, 0.006, 1.03, 40.00, '00:35', 1, 2.77, '3', 21.00, 9.30, '2024-09-22', 'klein_doos', 'kaars', 0, 2.50),
(30, '30029', 'Kerstbol', '20241020_184735.jpg', ' kerstbol ', 135, 15, 0.006, 0.008, 0.29, 40.00, '00:30', 5, 2.77, '1,2,3', 21.00, 8.88, '2024-10-21', 'klein_doos', 'kaars', 1, 2.50),
(31, '30030', 'Verbonden Harten Kaars', '20241108_105432.jpg', 'hartvormige kaars', 27, 3, 0.006, 0.008, 0.10, 40.00, '00:20', 5, 2.77, '1,2,3', 21.00, 6.59, '2024-11-08', 'klein_doos', 'kaars', 1, 2.50),
(32, '30031', 'Kerstboom en Sneeuwpop Kaars', '20241109_114644.jpg', 'Kesrtboom kaars', 207, 23, 0.006, 0.008, 0.30, 40.00, '01:00', 5, 2.77, '1,2,3', 21.00, 11.85, '2024-11-09', 'klein_doos', 'kaars', 1, 2.50);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `orders`
--

CREATE TABLE `orders` (
  `id` int NOT NULL,
  `order_code` varchar(6) NOT NULL,
  `status` enum('Concept','Verzonden','Verwerken','Afgehandeld','Geannuleerd') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'Concept',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `order_items`
--

CREATE TABLE `order_items` (
  `id` int NOT NULL,
  `order_id` int DEFAULT NULL,
  `product_id` int DEFAULT NULL,
  `quantity` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `product_changes`
--

CREATE TABLE `product_changes` (
  `id` int NOT NULL,
  `product_id` int NOT NULL,
  `category` varchar(255) NOT NULL,
  `field_name` varchar(255) NOT NULL,
  `old_value` text,
  `new_value` text,
  `changed_by_user` int DEFAULT NULL,
  `change_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `product_changes`
--

INSERT INTO `product_changes` (`id`, `product_id`, `category`, `field_name`, `old_value`, `new_value`, `changed_by_user`, `change_date`) VALUES
(1, 160, 'epoxy', 'amount_grams', '45', '58', 5, '2024-08-21 09:54:02'),
(2, 160, 'epoxy', 'total_product_price', '10.45', '10.89242', 5, '2024-08-21 09:54:02'),
(3, 228, 'epoxy', 'hours_worked', '1', '01:00', 5, '2024-09-05 10:03:44'),
(4, 228, 'epoxy', 'price_per_gram', '0.160', '0.02', 5, '2024-09-05 10:03:45'),
(5, 228, 'epoxy', 'total_product_price', '14.89', '9.67274', 5, '2024-09-05 10:03:46'),
(6, 228, 'epoxy', 'total_product_price', '9.67', '9.67274', 5, '2024-09-05 10:05:11'),
(7, 230, 'epoxy', 'product_image', '20240904_132207.jpg', '20240904_113732.jpg', 5, '2024-09-05 10:41:16'),
(8, 230, 'epoxy', 'hours_worked', '1', '', 5, '2024-09-05 10:41:18'),
(9, 230, 'epoxy', 'total_product_price', '15.77', '11.53614', 5, '2024-09-05 10:41:19'),
(10, 230, 'epoxy', 'title', 'Set van 6 kerstfiguren', 'Sneeuwman', 5, '2024-09-05 10:42:41'),
(11, 230, 'epoxy', 'hours_worked', '', '01:00', 5, '2024-09-05 10:42:42'),
(12, 230, 'epoxy', 'amount_grams', '202', '34', 5, '2024-09-05 10:42:42'),
(13, 230, 'epoxy', 'total_product_price', '11.54', '10.0793', 5, '2024-09-05 10:42:42'),
(14, 235, 'epoxy', 'product_image', '../product_img/no_picture.jpg', '20240904_113650.jpg', 5, '2024-09-05 10:52:37'),
(15, 235, 'epoxy', 'hours_worked', '1', '', 5, '2024-09-05 10:52:38'),
(16, 235, 'epoxy', 'total_product_price', '9.98', '5.74266', 5, '2024-09-05 10:52:39'),
(17, 235, 'epoxy', 'hours_worked', '', '01:00', 5, '2024-09-05 10:53:10'),
(18, 235, 'epoxy', 'total_product_price', '5.74', '9.97766', 5, '2024-09-05 10:53:11'),
(19, 230, 'epoxy', 'amount_grams', '34', '38', 5, '2024-09-05 10:58:14'),
(20, 230, 'epoxy', 'total_product_price', '10.08', '10.21482', 5, '2024-09-05 10:58:16'),
(21, 232, 'epoxy', 'hours_worked', '1', '01:00', 5, '2024-09-05 10:59:37'),
(22, 232, 'epoxy', 'amount_grams', '34', '33', 5, '2024-09-05 10:59:37'),
(23, 232, 'epoxy', 'total_product_price', '10.08', '10.04542', 5, '2024-09-05 10:59:38'),
(24, 229, 'epoxy', 'hours_worked', '1', '01:00', 5, '2024-09-05 11:00:51'),
(25, 229, 'epoxy', 'total_product_price', '15.77', '15.77114', 5, '2024-09-05 11:00:52'),
(26, 239, 'epoxy', 'product_image', '../product_img/no_picture.jpg', '20240904_113905.jpg', 5, '2024-09-05 11:06:29'),
(27, 239, 'epoxy', 'hours_worked', '1', '01:00', 5, '2024-09-05 11:06:31'),
(28, 239, 'epoxy', 'total_product_price', '10.42', '10.4181', 5, '2024-09-05 11:06:31'),
(29, 127, 'epoxy', 'product_image', '20230921_183720.jpg', '20240904_131713.jpg', 5, '2024-09-05 11:12:23'),
(30, 127, 'epoxy', 'total_product_price', '10.11', '10.11318', 5, '2024-09-05 11:12:24'),
(31, 229, 'epoxy', 'amount_grams', '202', '200', 5, '2024-09-05 14:35:46'),
(32, 229, 'epoxy', 'total_product_price', '15.77', '15.70338', 5, '2024-09-05 14:35:46'),
(33, 229, 'epoxy', 'amount_grams', '200', '202', 5, '2024-09-05 14:37:39'),
(34, 229, 'epoxy', 'total_product_price', '15.70', '15.77114', 5, '2024-09-05 14:37:39'),
(35, 105, 'epoxy', 'product_image', '20230920_103059.jpg', '20240905_164728.jpg', 5, '2024-09-05 14:50:23'),
(36, 105, 'epoxy', 'amount_grams', '232', '244', 5, '2024-09-05 14:50:23'),
(37, 105, 'epoxy', 'total_product_price', '16.79', '17.1941', 5, '2024-09-05 14:50:23'),
(38, 139, 'epoxy', 'hours_worked', '01:00', '00:30', 5, '2024-09-05 14:55:00'),
(39, 139, 'epoxy', 'total_product_price', '9.33', '7.21644', 5, '2024-09-05 14:55:00'),
(40, 152, 'epoxy', 'hours_worked', '01:00', '00:30', 5, '2024-09-05 15:04:22'),
(41, 152, 'epoxy', 'total_product_price', '10.28', '8.16508', 5, '2024-09-05 15:04:22'),
(42, 151, 'epoxy', 'hours_worked', '01:00', '00:30', 5, '2024-09-05 15:06:28'),
(43, 151, 'epoxy', 'total_product_price', '10.28', '8.16508', 5, '2024-09-05 15:06:28'),
(44, 153, 'epoxy', 'hours_worked', '01:00', '00:30', 5, '2024-09-05 15:07:49'),
(45, 153, 'epoxy', 'total_product_price', '10.28', '8.16508', 5, '2024-09-05 15:07:49'),
(46, 150, 'epoxy', 'hours_worked', '01:00', '00:30', 5, '2024-09-05 15:09:33'),
(47, 150, 'epoxy', 'total_product_price', '10.28', '8.16508', 5, '2024-09-05 15:09:33'),
(48, 235, 'epoxy', 'hours_worked', '01:00', '00:30', 5, '2024-09-06 08:02:47'),
(49, 235, 'epoxy', 'total_product_price', '9.98', '7.86016', 5, '2024-09-06 08:02:47'),
(50, 234, 'epoxy', 'hours_worked', '1', '00:30', 5, '2024-09-06 08:03:56'),
(51, 234, 'epoxy', 'total_product_price', '10.08', '7.9618', 5, '2024-09-06 08:03:56'),
(52, 95, 'epoxy', 'hours_worked', '01:00', '00:30', 5, '2024-09-06 08:04:55'),
(53, 95, 'epoxy', 'total_product_price', '10.25', '8.1312', 5, '2024-09-06 08:04:55'),
(54, 228, 'epoxy', 'hours_worked', '01:00', '00:30', 5, '2024-09-06 08:05:50'),
(55, 228, 'epoxy', 'total_product_price', '9.67', '7.55524', 5, '2024-09-06 08:05:51'),
(56, 97, 'epoxy', 'hours_worked', '01:00', '00:30', 5, '2024-09-06 08:06:49'),
(57, 97, 'epoxy', 'total_product_price', '10.25', '8.1312', 5, '2024-09-06 08:06:49'),
(58, 161, 'epoxy', 'hours_worked', '01:00', '00:30', 5, '2024-09-06 08:08:03'),
(59, 161, 'epoxy', 'total_product_price', '10.32', '8.19896', 5, '2024-09-06 08:08:03'),
(60, 160, 'epoxy', 'hours_worked', '01:00', '00:30', 5, '2024-09-06 08:08:56'),
(61, 160, 'epoxy', 'total_product_price', '10.89', '8.77492', 5, '2024-09-06 08:08:56'),
(62, 236, 'epoxy', 'hours_worked', '1', '00:30', 5, '2024-09-06 08:09:39'),
(63, 236, 'epoxy', 'total_product_price', '10.08', '7.9618', 5, '2024-09-06 08:09:40'),
(64, 98, 'epoxy', 'hours_worked', '01:00', '00:30', 5, '2024-09-06 08:10:27'),
(65, 98, 'epoxy', 'total_product_price', '9.74', '7.623', 5, '2024-09-06 08:10:27'),
(66, 230, 'epoxy', 'hours_worked', '01:00', '00:30', 5, '2024-09-06 08:12:25'),
(67, 230, 'epoxy', 'total_product_price', '10.21', '8.09732', 5, '2024-09-06 08:12:25'),
(68, 232, 'epoxy', 'hours_worked', '01:00', '00:30', 5, '2024-09-06 08:13:07'),
(69, 232, 'epoxy', 'total_product_price', '10.05', '7.92792', 5, '2024-09-06 08:13:07'),
(70, 139, 'epoxy', 'hours_worked', '00:30', '00:20', 5, '2024-09-06 08:14:04'),
(71, 139, 'epoxy', 'total_product_price', '7.22', '6.5106066666667', 5, '2024-09-06 08:14:04'),
(72, 237, 'epoxy', 'hours_worked', '1', '00:30', 5, '2024-09-06 08:14:49'),
(73, 237, 'epoxy', 'total_product_price', '10.01', '7.89404', 5, '2024-09-06 08:14:49'),
(74, 241, 'epoxy', 'hours_worked', '0.5', '00:30', 5, '2024-09-06 08:19:20'),
(75, 241, 'epoxy', 'total_product_price', '7.76', '7.75852', 5, '2024-09-06 08:19:20'),
(76, 239, 'epoxy', 'hours_worked', '01:00', '00:45', 5, '2024-09-06 08:21:33'),
(77, 239, 'epoxy', 'total_product_price', '10.42', '9.35935', 5, '2024-09-06 08:21:33'),
(78, 242, 'terrazzo', 'hours_worked', '1:30', '01:30', 1, '2024-09-12 12:14:40'),
(79, 242, 'terrazzo', 'sold_in_branches', '2,3', '3', 1, '2024-09-12 12:14:40'),
(80, 242, 'terrazzo', 'total_product_price', '3.89', '18.20203', 1, '2024-09-12 12:14:40'),
(81, 241, 'epoxy', 'total_product_price', '7.76', '7.75852', 1, '2024-09-12 12:18:09'),
(82, 242, 'terrazzo', 'product_image', '../product_img/no_picture.jpg', '20240912_171342.jpg', 5, '2024-09-12 15:26:06'),
(83, 242, 'terrazzo', 'total_product_price', '18.20', '18.20203', 5, '2024-09-12 15:26:06'),
(84, 242, 'terrazzo', 'hours_worked', '01:30', '02:00', 5, '2024-09-12 15:31:07'),
(85, 242, 'terrazzo', 'total_product_price', '18.20', '20.31953', 5, '2024-09-12 15:31:08'),
(86, 243, 'terrazzo', 'product_image', '20240912_171016.jpg', '20240912_181932.jpg', 5, '2024-09-12 16:28:36'),
(87, 243, 'terrazzo', 'total_product_price', '11.33', '11.325519333333', 5, '2024-09-12 16:28:36'),
(88, 245, 'terrazzo', 'product_image', '../product_img/no_picture.jpg', '20240912_182012.jpg', 5, '2024-09-12 16:30:10'),
(89, 245, 'terrazzo', 'total_product_price', '12.38', '12.381446', 5, '2024-09-12 16:30:10'),
(90, 245, 'terrazzo', 'total_product_price', '12.38', '12.381446', 5, '2024-09-12 16:30:50'),
(91, 102, 'epoxy', 'amount_grams', '210', '170', 5, '2024-09-15 09:31:49'),
(92, 102, 'epoxy', 'total_product_price', '16.04', '14.68698', 5, '2024-09-15 09:31:49'),
(93, 246, 'terrazzo', 'total_product_price', '9.06', '9.056124', 5, '2024-09-15 09:52:30'),
(94, 246, 'terrazzo', 'price_per_gram', '0.013', '0.008', 5, '2024-09-15 09:53:21'),
(95, 246, 'terrazzo', 'total_product_price', '9.06', '8.192184', 5, '2024-09-15 09:53:21'),
(96, 245, 'terrazzo', 'price_per_gram', '0.013', '0.008', 5, '2024-09-15 09:54:43'),
(97, 245, 'terrazzo', 'total_product_price', '12.38', '10.238536', 5, '2024-09-15 09:54:43'),
(98, 244, 'terrazzo', 'price_per_gram', '0.013', '0.008', 5, '2024-09-15 09:55:25'),
(99, 244, 'terrazzo', 'total_product_price', '10.51', '9.086616', 5, '2024-09-15 09:55:25'),
(100, 243, 'terrazzo', 'price_per_gram', '0.013', '0.008', 5, '2024-09-15 09:56:02'),
(101, 243, 'terrazzo', 'total_product_price', '11.33', '9.8602093333333', 5, '2024-09-15 09:56:02'),
(102, 242, 'terrazzo', 'price_per_gram', '0.013', '0.008', 5, '2024-09-15 09:57:00'),
(103, 242, 'terrazzo', 'total_product_price', '20.32', '17.56678', 5, '2024-09-15 09:57:00'),
(104, 227, 'terrazzo', 'price_per_gram', '0.013', '0.008', 5, '2024-09-15 09:57:32'),
(105, 227, 'terrazzo', 'total_product_price', '8.64', '7.934696', 5, '2024-09-15 09:57:32'),
(106, 148, 'epoxy', 'price_per_gram', '0.020', '0.016', 5, '2024-09-15 11:40:06'),
(107, 148, 'epoxy', 'total_product_price', '28.10', '26.13842', 5, '2024-09-15 11:40:07'),
(108, 148, 'epoxy', 'amount_grams', '290', '420', 5, '2024-09-15 11:45:05'),
(109, 148, 'epoxy', 'price_per_gram', '0.016', '0.008', 5, '2024-09-15 11:45:05'),
(110, 148, 'epoxy', 'total_product_price', '26.14', '23.9701', 5, '2024-09-15 11:45:05'),
(111, 148, 'epoxy', 'amount_grams', '420', '290', 5, '2024-09-15 11:46:24'),
(112, 148, 'epoxy', 'price_per_gram', '0.008', '0.016', 5, '2024-09-15 11:46:24'),
(113, 148, 'epoxy', 'total_product_price', '23.97', '26.13842', 5, '2024-09-15 11:46:24'),
(114, 148, 'epoxy', 'total_product_price', '26.14', '26.13842', 5, '2024-09-15 11:47:47'),
(115, 247, 'epoxy', 'product_image', '../product_img/no_picture.jpg', '20240915_142747.jpg', 5, '2024-09-17 13:42:50'),
(116, 247, 'epoxy', 'hours_worked', '01:00', '00:30', 5, '2024-09-17 13:42:50'),
(117, 247, 'epoxy', 'total_product_price', '9.25', '7.135128', 5, '2024-09-17 13:42:50'),
(118, 248, 'terrazzo', 'title', 'Terrazzo Style volle zeeppompje', 'Terrazzo Style volle zeepompje', 1, '2024-09-19 08:58:11'),
(119, 248, 'terrazzo', 'product_image', '../product_img/no_picture.jpg', '20240915_142600.jpg', 1, '2024-09-19 08:58:12'),
(120, 248, 'terrazzo', 'extra_parts_price', '0.00', '5.52', 1, '2024-09-19 08:58:12'),
(121, 248, 'terrazzo', 'total_product_price', '14.62', '23.9701', 1, '2024-09-19 08:58:12'),
(122, 27, 'kaars', 'hours_worked', '0', '', 1, '2024-09-22 15:31:38'),
(123, 27, 'kaars', 'hours_worked', '0', '01:10', 1, '2024-09-22 15:32:01'),
(124, 26, 'kaars', 'hours_worked', '1', '01:10', 1, '2024-09-22 15:35:07'),
(125, 26, 'kaars', 'hours_worked', '1', '01:10', 1, '2024-09-22 15:41:23'),
(126, 26, 'kaars', 'amount_paraffin_grams', '117', '0', 1, '2024-09-22 15:41:23'),
(127, 26, 'kaars', 'amount_stearin_grams', '13', '0', 1, '2024-09-22 15:41:23'),
(128, 26, 'kaars', 'price_per_gram_paraffin', '0.080', '0', 1, '2024-09-22 15:41:23'),
(129, 26, 'kaars', 'price_per_gram_stearin', '0.070', '0', 1, '2024-09-22 15:41:24'),
(130, 26, 'kaars', 'total_product_price', '29.75', '13.055093333333', 1, '2024-09-22 15:41:24'),
(131, 27, 'kaars', 'hours_worked', '0', '01:10', 1, '2024-09-22 15:49:24'),
(132, 27, 'kaars', 'price_per_gram_paraffin', '0.080', '0.008', 1, '2024-09-22 15:49:24'),
(133, 27, 'kaars', 'price_per_gram_stearin', '0.070', '0.007', 1, '2024-09-22 15:49:25'),
(134, 27, 'kaars', 'total_product_price', '25.51', '14.794831333333', 1, '2024-09-22 15:49:25'),
(135, 26, 'kaars', 'amount_paraffin_grams', '0', '117', 1, '2024-09-22 15:50:32'),
(136, 26, 'kaars', 'amount_stearin_grams', '0', '13', 1, '2024-09-22 15:50:32'),
(137, 26, 'kaars', 'price_per_gram_paraffin', '0.000', '0.008', 1, '2024-09-22 15:50:32'),
(138, 26, 'kaars', 'price_per_gram_stearin', '0.000', '0.007', 1, '2024-09-22 15:50:32'),
(139, 26, 'kaars', 'total_product_price', '13.06', '14.794831333333', 1, '2024-09-22 15:50:32'),
(140, 29, 'kaars', 'hours_worked', '0', '00:40', 1, '2024-09-22 16:44:53'),
(141, 29, 'kaars', 'total_product_price', '6.47', '9.2893313333333', 1, '2024-09-22 16:44:53'),
(142, 29, 'kaars', 'hours_worked', '00:40', '00:35', 1, '2024-09-22 16:48:37'),
(143, 29, 'kaars', 'total_product_price', '9.29', '8.9364146666667', 1, '2024-09-22 16:48:37'),
(144, 28, 'kaars', 'hours_worked', '1:10', '00:40', 1, '2024-09-22 16:53:39'),
(145, 28, 'kaars', 'total_product_price', '8.30', '11.123933333333', 1, '2024-09-22 16:53:39'),
(146, 27, 'kaars', 'hours_worked', '01:10', '00:40', 1, '2024-09-22 16:54:09'),
(147, 27, 'kaars', 'total_product_price', '14.79', '12.677331333333', 1, '2024-09-22 16:54:09'),
(148, 26, 'kaars', 'hours_worked', '01:10', '00:40', 1, '2024-09-22 16:54:48'),
(149, 26, 'kaars', 'total_product_price', '14.79', '12.677331333333', 1, '2024-09-22 16:54:48'),
(150, 27, 'vers', 'total_product_price', '2.86', '2.86412', 1, '2024-09-30 07:58:47'),
(151, 27, 'vers', 'amount_grams', '1.000', '0', 1, '2024-09-30 07:59:36'),
(152, 27, 'vers', 'total_product_price', '2.86', '1.00912', 1, '2024-09-30 07:59:36'),
(153, 27, 'vers', 'amount_grams', '0.000', '0.5', 1, '2024-09-30 08:02:16'),
(154, 27, 'vers', 'total_product_price', '1.01', '1.93662', 1, '2024-09-30 08:02:17'),
(155, 27, 'vers', 'amount_grams', '0.500', '0.8', 1, '2024-09-30 08:02:31'),
(156, 27, 'vers', 'total_product_price', '1.94', '1.00912', 1, '2024-09-30 08:02:31'),
(157, 27, 'vers', 'total_product_price', '1.01', '2.49312', 1, '2024-09-30 08:02:45'),
(158, 28, 'vers', 'hours_worked', '01:00', '00:10', 1, '2024-10-02 16:55:18'),
(159, 28, 'vers', 'total_product_price', '40.39', '12.581846666667', 1, '2024-10-02 16:55:18'),
(160, 28, 'vers', 'total_product_price', '12.58', '12.581846666667', 1, '2024-10-02 18:58:12'),
(161, 179, 'epoxy', 'hours_worked', '01:00', '00:20', 5, '2024-10-10 16:01:29'),
(162, 179, 'epoxy', 'total_product_price', '9.93', '7.1035066666667', 5, '2024-10-10 16:01:29'),
(163, 139, 'epoxy', 'product_image', '20230902_192027.jpg', '20241020_185119.jpg', 5, '2024-10-21 09:23:50'),
(164, 139, 'epoxy', 'total_product_price', '6.51', '6.5106066666667', 5, '2024-10-21 09:23:50'),
(165, 139, 'epoxy', 'total_product_price', '6.51', '6.5106066666667', 5, '2024-10-21 09:26:04'),
(166, 258, 'epoxy', 'hours_worked', '00:30', '00:20', 5, '2024-10-30 12:31:32'),
(167, 258, 'epoxy', 'total_product_price', '8.50', '7.7946586666667', 5, '2024-10-30 12:31:32'),
(168, 258, 'epoxy', 'hours_worked', '00:20', '00:25', 5, '2024-10-30 12:31:50'),
(169, 258, 'epoxy', 'total_product_price', '7.79', '8.1475753333333', 5, '2024-10-30 12:31:50'),
(170, 258, 'epoxy', 'hours_worked', '00:25', '00:20', 5, '2024-11-01 08:45:40'),
(171, 258, 'epoxy', 'total_product_price', '8.15', '7.7946586666667', 5, '2024-11-01 08:45:40'),
(172, 137, 'epoxy', 'extra_parts_price', '0.58', '0.75', 5, '2024-11-01 14:23:57'),
(173, 137, 'epoxy', 'hours_worked', '1', '00:15', 5, '2024-11-01 14:23:57'),
(174, 137, 'epoxy', 'total_product_price', '5.91', '7.25879', 5, '2024-11-01 14:23:57'),
(175, 260, 'epoxy', 'hours_worked', '00:15', '00:20', 5, '2024-11-01 16:34:54'),
(176, 260, 'epoxy', 'amount_grams', '11', '9', 5, '2024-11-01 16:34:54'),
(177, 260, 'epoxy', 'total_product_price', '6.05', '6.3479826666667', 5, '2024-11-01 16:34:55'),
(178, 182, 'epoxy', 'hours_worked', '01:00', '00:30', 5, '2024-11-01 16:36:58'),
(179, 182, 'epoxy', 'total_product_price', '12.23', '10.11318', 5, '2024-11-01 16:36:58'),
(180, 263, 'terrazzo', 'product_image', '../product_img/no_picture.jpg', '20241101_164934.jpg', 5, '2024-11-02 10:02:05'),
(181, 263, 'terrazzo', 'total_product_price', '6.89', '6.8900626666667', 5, '2024-11-02 10:02:05'),
(182, 264, 'terrazzo', 'product_image', '../product_img/no_picture.jpg', '20241101_164952.jpg', 5, '2024-11-02 10:17:58'),
(183, 264, 'terrazzo', 'total_product_price', '7.61', '7.6083186666667', 5, '2024-11-02 10:17:59'),
(184, 31, 'kaars', 'hours_worked', '0', '00:20', 5, '2024-11-08 10:16:44'),
(185, 31, 'kaars', 'price_per_gram_paraffin', '0.060', '0.006', 5, '2024-11-08 10:16:44'),
(186, 31, 'kaars', 'price_per_gram_stearin', '0.080', '0.008', 5, '2024-11-08 10:16:45'),
(187, 31, 'kaars', 'total_product_price', '8.42', '6.6291866666667', 5, '2024-11-08 10:16:45'),
(188, 31, 'kaars', 'amount_paraffin_grams', '31', '27', 5, '2024-11-08 10:18:25'),
(189, 31, 'kaars', 'total_product_price', '6.63', '6.5885306666667', 5, '2024-11-08 10:18:25'),
(190, 5, 'kaars', 'product_image', '20240712_105734.jpg', '20241109_115248.jpg', 5, '2024-11-09 11:46:47'),
(191, 5, 'kaars', 'margin', '50.00', '40', 5, '2024-11-09 11:46:47'),
(192, 5, 'kaars', 'created_by_user', '6', '5', 5, '2024-11-09 11:46:48'),
(193, 5, 'kaars', 'amount_paraffin_grams', '0', '54', 5, '2024-11-09 11:46:48'),
(194, 5, 'kaars', 'amount_stearin_grams', '60', '6', 5, '2024-11-09 11:46:48'),
(195, 5, 'kaars', 'price_per_gram_paraffin', '0.000', '0.006', 5, '2024-11-09 11:46:48'),
(196, 5, 'kaars', 'price_per_gram_stearin', '0.010', '0.008', 5, '2024-11-09 11:46:48'),
(197, 5, 'kaars', 'total_product_price', '8.82', '7.846608', 5, '2024-11-09 11:46:48'),
(198, 32, 'kaars', 'hours_worked', '0', '01:00', 5, '2024-11-09 11:53:09'),
(199, 32, 'kaars', 'total_product_price', '7.62', '11.851224', 5, '2024-11-09 11:53:09'),
(200, 30, 'kaars', 'hours_worked', '0', '00:30', 5, '2024-11-09 11:54:39'),
(201, 30, 'kaars', 'total_product_price', '6.76', '8.87656', 5, '2024-11-09 11:54:39'),
(202, 30, 'kaars', 'product_description', 'groene kerstbol ', ' kerstbol ', 5, '2024-11-09 11:55:23'),
(203, 30, 'kaars', 'total_product_price', '8.88', '8.87656', 5, '2024-11-09 11:55:23'),
(204, 5, 'kaars', 'hours_worked', '00:30', '00:20', 5, '2024-11-09 12:04:19'),
(205, 5, 'kaars', 'total_product_price', '7.85', '7.1407746666667', 5, '2024-11-09 12:04:19'),
(206, 3, 'kaars', 'margin', '50.00', '40', 5, '2024-11-09 12:18:47'),
(207, 3, 'kaars', 'amount_paraffin_grams', '0', '153', 5, '2024-11-09 12:18:47'),
(208, 3, 'kaars', 'amount_stearin_grams', '170', '17', 5, '2024-11-09 12:18:47'),
(209, 3, 'kaars', 'price_per_gram_paraffin', '0.000', '0.008', 5, '2024-11-09 12:18:47'),
(210, 3, 'kaars', 'price_per_gram_stearin', '0.020', '0.008', 5, '2024-11-09 12:18:47'),
(211, 3, 'kaars', 'total_product_price', '13.90', '9.52028', 5, '2024-11-09 12:18:48'),
(212, 1, 'kaars', 'margin', '50.00', '40', 5, '2024-11-10 12:30:17'),
(213, 1, 'kaars', 'hours_worked', '00:10', '00:20', 5, '2024-11-10 12:30:17'),
(214, 1, 'kaars', 'sold_in_branches', '3', '1,2,3', 5, '2024-11-10 12:30:17'),
(215, 1, 'kaars', 'shipping_method', 'briefpakket', 'kleine doos', 5, '2024-11-10 12:30:18'),
(216, 1, 'kaars', 'amount_paraffin_grams', '0', '54', 5, '2024-11-10 12:30:18'),
(217, 1, 'kaars', 'amount_stearin_grams', '60', '6', 5, '2024-11-10 12:30:18'),
(218, 1, 'kaars', 'price_per_gram_paraffin', '0.000', '0.006', 5, '2024-11-10 12:30:18'),
(219, 1, 'kaars', 'price_per_gram_stearin', '0.020', '0.008', 5, '2024-11-10 12:30:18'),
(220, 1, 'kaars', 'total_product_price', '8.40', '7.1407746666667', 5, '2024-11-10 12:30:18'),
(221, 2, 'kaars', 'margin', '50.00', '40', 5, '2024-11-10 12:32:13'),
(222, 2, 'kaars', 'sold_in_branches', '3', '1,2,3', 5, '2024-11-10 12:32:13'),
(223, 2, 'kaars', 'amount_paraffin_grams', '0', '54', 5, '2024-11-10 12:32:13'),
(224, 2, 'kaars', 'amount_stearin_grams', '60', '6', 5, '2024-11-10 12:32:13'),
(225, 2, 'kaars', 'price_per_gram_paraffin', '0.000', '0.006', 5, '2024-11-10 12:32:13'),
(226, 2, 'kaars', 'price_per_gram_stearin', '0.010', '0.008', 5, '2024-11-10 12:32:14'),
(227, 2, 'kaars', 'total_product_price', '8.82', '7.846608', 5, '2024-11-10 12:32:14'),
(228, 4, 'kaars', 'margin', '50.00', '40', 5, '2024-11-10 12:34:04'),
(229, 4, 'kaars', 'sold_in_branches', '3', '1,2,3', 5, '2024-11-10 12:34:04'),
(230, 4, 'kaars', 'amount_paraffin_grams', '0', '63', 5, '2024-11-10 12:34:04'),
(231, 4, 'kaars', 'amount_stearin_grams', '70', '7', 5, '2024-11-10 12:34:04'),
(232, 4, 'kaars', 'price_per_gram_paraffin', '0.000', '0.006', 5, '2024-11-10 12:34:04'),
(233, 4, 'kaars', 'price_per_gram_stearin', '0.010', '0.008', 5, '2024-11-10 12:34:04'),
(234, 4, 'kaars', 'total_product_price', '9.00', '7.951636', 5, '2024-11-10 12:34:05'),
(235, 6, 'kaars', 'extra_parts_price', '0.10', '0.24', 5, '2024-11-10 12:36:18'),
(236, 6, 'kaars', 'margin', '50.00', '40', 5, '2024-11-10 12:36:19'),
(237, 6, 'kaars', 'sold_in_branches', '3', '1,2,3', 5, '2024-11-10 12:36:19'),
(238, 6, 'kaars', 'amount_paraffin_grams', '0', '99', 5, '2024-11-10 12:36:19'),
(239, 6, 'kaars', 'amount_stearin_grams', '110', '11', 5, '2024-11-10 12:36:19'),
(240, 6, 'kaars', 'price_per_gram_paraffin', '0.000', '0.006', 5, '2024-11-10 12:36:19'),
(241, 6, 'kaars', 'price_per_gram_stearin', '0.020', '0.008', 5, '2024-11-10 12:36:19'),
(242, 6, 'kaars', 'total_product_price', '11.47', '8.371748', 5, '2024-11-10 12:36:19'),
(243, 9, 'kaars', 'extra_parts_price', '0.08', '0.24', 5, '2024-11-10 12:37:55'),
(244, 9, 'kaars', 'margin', '50.00', '40', 5, '2024-11-10 12:37:55'),
(245, 9, 'kaars', 'hours_worked', '00:10', '00:20', 5, '2024-11-10 12:37:55'),
(246, 9, 'kaars', 'sold_in_branches', '3', '1,2,3', 5, '2024-11-10 12:37:55'),
(247, 9, 'kaars', 'amount_paraffin_grams', '0', '72', 5, '2024-11-10 12:37:55'),
(248, 9, 'kaars', 'amount_stearin_grams', '80', '8', 5, '2024-11-10 12:37:55'),
(249, 9, 'kaars', 'price_per_gram_paraffin', '0.000', '0.006', 5, '2024-11-10 12:37:55'),
(250, 9, 'kaars', 'price_per_gram_stearin', '0.010', '0.008', 5, '2024-11-10 12:37:55'),
(251, 9, 'kaars', 'total_product_price', '7.38', '7.3508306666667', 5, '2024-11-10 12:37:56'),
(252, 7, 'kaars', 'extra_parts_price', '0.00', '0.24', 5, '2024-11-10 12:40:01'),
(253, 7, 'kaars', 'margin', '50.00', '40', 5, '2024-11-10 12:40:01'),
(254, 7, 'kaars', 'hours_worked', '00:10', '00:20', 5, '2024-11-10 12:40:01'),
(255, 7, 'kaars', 'sold_in_branches', '3', '1,2,3', 5, '2024-11-10 12:40:01'),
(256, 7, 'kaars', 'amount_paraffin_grams', '0', '72', 5, '2024-11-10 12:40:01'),
(257, 7, 'kaars', 'amount_stearin_grams', '80', '8', 5, '2024-11-10 12:40:01'),
(258, 7, 'kaars', 'price_per_gram_paraffin', '0.000', '0.006', 5, '2024-11-10 12:40:01'),
(259, 7, 'kaars', 'price_per_gram_stearin', '0.010', '0.008', 5, '2024-11-10 12:40:01'),
(260, 7, 'kaars', 'total_product_price', '7.24', '7.3508306666667', 5, '2024-11-10 12:40:01'),
(261, 8, 'kaars', 'hours_worked', '00:10', '00:20', 5, '2024-11-10 12:41:30'),
(262, 8, 'kaars', 'sold_in_branches', '3', '1,2,3', 5, '2024-11-10 12:41:30'),
(263, 8, 'kaars', 'total_product_price', '7.38', '8.13725', 5, '2024-11-10 12:41:30'),
(264, 8, 'kaars', 'extra_parts_price', '0.08', '0.32', 5, '2024-11-10 12:42:16'),
(265, 8, 'kaars', 'margin', '50.00', '40', 5, '2024-11-10 12:42:16'),
(266, 8, 'kaars', 'amount_paraffin_grams', '0', '72', 5, '2024-11-10 12:42:17'),
(267, 8, 'kaars', 'amount_stearin_grams', '80', '8', 5, '2024-11-10 12:42:17'),
(268, 8, 'kaars', 'price_per_gram_paraffin', '0.000', '0.006', 5, '2024-11-10 12:42:17'),
(269, 8, 'kaars', 'price_per_gram_stearin', '0.010', '0.008', 5, '2024-11-10 12:42:17'),
(270, 8, 'kaars', 'total_product_price', '8.14', '7.4863506666667', 5, '2024-11-10 12:42:17'),
(271, 9, 'kaars', 'extra_parts_price', '0.24', '0.32', 5, '2024-11-10 12:42:48'),
(272, 9, 'kaars', 'total_product_price', '7.35', '7.4863506666667', 5, '2024-11-10 12:42:48'),
(273, 16, 'kaars', 'extra_parts_price', '0.08', '0.32', 5, '2024-11-10 12:45:22'),
(274, 16, 'kaars', 'margin', '50.00', '40', 5, '2024-11-10 12:45:22'),
(275, 16, 'kaars', 'hours_worked', '00:10', '00:20', 5, '2024-11-10 12:45:22'),
(276, 16, 'kaars', 'sold_in_branches', '3', '1,2,3', 5, '2024-11-10 12:45:22'),
(277, 16, 'kaars', 'amount_paraffin_grams', '0', '72', 5, '2024-11-10 12:45:22'),
(278, 16, 'kaars', 'amount_stearin_grams', '80', '8', 5, '2024-11-10 12:45:22'),
(279, 16, 'kaars', 'price_per_gram_paraffin', '0.000', '0.006', 5, '2024-11-10 12:45:22'),
(280, 16, 'kaars', 'price_per_gram_stearin', '0.010', '0.008', 5, '2024-11-10 12:45:23'),
(281, 16, 'kaars', 'total_product_price', '7.38', '7.4863506666667', 5, '2024-11-10 12:45:23'),
(282, 17, 'kaars', 'extra_parts_price', '0.08', '0.32', 5, '2024-11-10 12:46:34'),
(283, 17, 'kaars', 'margin', '50.00', '40', 5, '2024-11-10 12:46:34'),
(284, 17, 'kaars', 'hours_worked', '00:10', '00:20', 5, '2024-11-10 12:46:34'),
(285, 17, 'kaars', 'sold_in_branches', '3', '1,2,3', 5, '2024-11-10 12:46:34'),
(286, 17, 'kaars', 'amount_paraffin_grams', '0', '72', 5, '2024-11-10 12:46:34'),
(287, 17, 'kaars', 'amount_stearin_grams', '80', '8', 5, '2024-11-10 12:46:34'),
(288, 17, 'kaars', 'price_per_gram_paraffin', '0.000', '0.006', 5, '2024-11-10 12:46:34'),
(289, 17, 'kaars', 'price_per_gram_stearin', '0.010', '0.08', 5, '2024-11-10 12:46:34'),
(290, 17, 'kaars', 'total_product_price', '7.38', '8.4620946666667', 5, '2024-11-10 12:46:34'),
(291, 17, 'kaars', 'total_product_price', '8.46', '8.4620946666667', 5, '2024-11-10 12:47:08'),
(292, 17, 'kaars', 'price_per_gram_stearin', '0.080', '0.008', 5, '2024-11-10 12:47:32'),
(293, 17, 'kaars', 'total_product_price', '8.46', '7.4863506666667', 5, '2024-11-10 12:47:32'),
(294, 10, 'kaars', 'margin', '50.00', '40', 5, '2024-11-10 12:48:56'),
(295, 10, 'kaars', 'hours_worked', '00:15', '00:20', 5, '2024-11-10 12:48:56'),
(296, 10, 'kaars', 'amount_paraffin_grams', '0', '135', 5, '2024-11-10 12:48:56'),
(297, 10, 'kaars', 'amount_stearin_grams', '150', '15', 5, '2024-11-10 12:48:56'),
(298, 10, 'kaars', 'price_per_gram_paraffin', '0.000', '0.006', 5, '2024-11-10 12:48:56'),
(299, 10, 'kaars', 'price_per_gram_stearin', '0.010', '0.008', 5, '2024-11-10 12:48:56'),
(300, 10, 'kaars', 'total_product_price', '9.36', '8.1199066666667', 5, '2024-11-10 12:48:56'),
(301, 11, 'kaars', 'extra_parts_price', '0.13', '0.24', 5, '2024-11-10 12:50:35'),
(302, 11, 'kaars', 'margin', '50.00', '40', 5, '2024-11-10 12:50:35'),
(303, 11, 'kaars', 'hours_worked', '00:10', '00:20', 5, '2024-11-10 12:50:35'),
(304, 11, 'kaars', 'sold_in_branches', '3', '1,2,3', 5, '2024-11-10 12:50:35'),
(305, 11, 'kaars', 'amount_paraffin_grams', '0', '36', 5, '2024-11-10 12:50:35'),
(306, 11, 'kaars', 'amount_stearin_grams', '40', '4', 5, '2024-11-10 12:50:35'),
(307, 11, 'kaars', 'price_per_gram_paraffin', '0.000', '0.006', 5, '2024-11-10 12:50:35'),
(308, 11, 'kaars', 'price_per_gram_stearin', '0.010', '0.008', 5, '2024-11-10 12:50:35'),
(309, 11, 'kaars', 'total_product_price', '6.75', '6.9307186666667', 5, '2024-11-10 12:50:35'),
(310, 11, 'kaars', 'extra_parts_price', '0.24', '0.15', 5, '2024-11-10 12:51:01'),
(311, 11, 'kaars', 'total_product_price', '6.93', '6.7782586666667', 5, '2024-11-10 12:51:01'),
(312, 11, 'kaars', 'extra_parts_price', '0.15', '0.24', 5, '2024-11-10 12:51:32'),
(313, 11, 'kaars', 'total_product_price', '6.78', '6.9307186666667', 5, '2024-11-10 12:51:32'),
(314, 12, 'kaars', 'extra_parts_price', '0.30', '0.32', 5, '2024-11-10 12:52:44'),
(315, 12, 'kaars', 'margin', '50.00', '40', 5, '2024-11-10 12:52:44'),
(316, 12, 'kaars', 'sold_in_branches', '3', '1,2,3', 5, '2024-11-10 12:52:44'),
(317, 12, 'kaars', 'amount_paraffin_grams', '0', '171', 5, '2024-11-10 12:52:44'),
(318, 12, 'kaars', 'amount_stearin_grams', '190', '19', 5, '2024-11-10 12:52:44'),
(319, 12, 'kaars', 'price_per_gram_paraffin', '0.000', '0.006', 5, '2024-11-10 12:52:44'),
(320, 12, 'kaars', 'price_per_gram_stearin', '0.010', '0.008', 5, '2024-11-10 12:52:44'),
(321, 12, 'kaars', 'total_product_price', '10.91', '8.9945753333333', 5, '2024-11-10 12:52:44'),
(322, 12, 'kaars', 'extra_parts_price', '0.32', '0.48', 5, '2024-11-10 12:53:27'),
(323, 12, 'kaars', 'total_product_price', '8.99', '9.2656153333333', 5, '2024-11-10 12:53:27'),
(324, 13, 'kaars', 'extra_parts_price', '0.40', '0.48', 5, '2024-11-10 12:54:49'),
(325, 13, 'kaars', 'margin', '50.00', '40', 5, '2024-11-10 12:54:49'),
(326, 13, 'kaars', 'sold_in_branches', '3', '1,2,3', 5, '2024-11-10 12:54:49'),
(327, 13, 'kaars', 'amount_paraffin_grams', '0', '207', 5, '2024-11-10 12:54:49'),
(328, 13, 'kaars', 'amount_stearin_grams', '230', '23', 5, '2024-11-10 12:54:49'),
(329, 13, 'kaars', 'price_per_gram_paraffin', '0.000', '0.006', 5, '2024-11-10 12:54:50'),
(330, 13, 'kaars', 'price_per_gram_stearin', '0.010', '0.008', 5, '2024-11-10 12:54:50'),
(331, 13, 'kaars', 'total_product_price', '12.20', '10.038644', 5, '2024-11-10 12:54:50'),
(332, 14, 'kaars', 'margin', '50.00', '40', 5, '2024-11-10 12:56:12'),
(333, 14, 'kaars', 'sold_in_branches', '3', '1,2,3', 5, '2024-11-10 12:56:13'),
(334, 14, 'kaars', 'amount_paraffin_grams', '0', '90', 5, '2024-11-10 12:56:13'),
(335, 14, 'kaars', 'amount_stearin_grams', '100', '10', 5, '2024-11-10 12:56:13'),
(336, 14, 'kaars', 'price_per_gram_paraffin', '0.000', '0.006', 5, '2024-11-10 12:56:13'),
(337, 14, 'kaars', 'price_per_gram_stearin', '0.010', '0.008', 5, '2024-11-10 12:56:13'),
(338, 14, 'kaars', 'total_product_price', '9.55', '8.26672', 5, '2024-11-10 12:56:13'),
(339, 15, 'kaars', 'margin', '50.00', '40', 5, '2024-11-10 12:57:13'),
(340, 15, 'kaars', 'sold_in_branches', '3', '1,2,3', 5, '2024-11-10 12:57:13'),
(341, 15, 'kaars', 'amount_paraffin_grams', '0', '153', 5, '2024-11-10 12:57:13'),
(342, 15, 'kaars', 'amount_stearin_grams', '170', '17', 5, '2024-11-10 12:57:13'),
(343, 15, 'kaars', 'price_per_gram_paraffin', '0.000', '0.006', 5, '2024-11-10 12:57:13'),
(344, 15, 'kaars', 'price_per_gram_stearin', '0.020', '0.008', 5, '2024-11-10 12:57:13'),
(345, 15, 'kaars', 'total_product_price', '13.90', '9.001916', 5, '2024-11-10 12:57:13'),
(346, 18, 'kaars', 'margin', '50.00', '40', 5, '2024-11-10 12:58:14'),
(347, 18, 'kaars', 'sold_in_branches', '3', '1,2,3', 5, '2024-11-10 12:58:14'),
(348, 18, 'kaars', 'amount_paraffin_grams', '0', '72', 5, '2024-11-10 12:58:14'),
(349, 18, 'kaars', 'amount_stearin_grams', '80', '8', 5, '2024-11-10 12:58:14'),
(350, 18, 'kaars', 'price_per_gram_paraffin', '0.000', '0.006', 5, '2024-11-10 12:58:14'),
(351, 18, 'kaars', 'price_per_gram_stearin', '0.010', '0.008', 5, '2024-11-10 12:58:14'),
(352, 18, 'kaars', 'total_product_price', '9.18', '8.056664', 5, '2024-11-10 12:58:14'),
(353, 19, 'kaars', 'margin', '50.00', '40', 5, '2024-11-10 12:59:13'),
(354, 19, 'kaars', 'sold_in_branches', '3', '1,2,3', 5, '2024-11-10 12:59:13'),
(355, 19, 'kaars', 'amount_paraffin_grams', '0', '36', 5, '2024-11-10 12:59:13'),
(356, 19, 'kaars', 'amount_stearin_grams', '40', '4', 5, '2024-11-10 12:59:13'),
(357, 19, 'kaars', 'price_per_gram_paraffin', '0.000', '0.006', 5, '2024-11-10 12:59:13'),
(358, 19, 'kaars', 'price_per_gram_stearin', '0.010', '0.008', 5, '2024-11-10 12:59:13'),
(359, 19, 'kaars', 'total_product_price', '8.46', '7.636552', 5, '2024-11-10 12:59:13'),
(360, 20, 'kaars', 'margin', '50.00', '40', 5, '2024-11-10 13:00:16'),
(361, 20, 'kaars', 'sold_in_branches', '3', '1,2,3', 5, '2024-11-10 13:00:16'),
(362, 20, 'kaars', 'amount_paraffin_grams', '0', '162', 5, '2024-11-10 13:00:16'),
(363, 20, 'kaars', 'amount_stearin_grams', '180', '18', 5, '2024-11-10 13:00:17'),
(364, 20, 'kaars', 'price_per_gram_paraffin', '0.000', '0.006', 5, '2024-11-10 13:00:17'),
(365, 20, 'kaars', 'price_per_gram_stearin', '0.010', '0.008', 5, '2024-11-10 13:00:17'),
(366, 20, 'kaars', 'total_product_price', '11.00', '9.106944', 5, '2024-11-10 13:00:17'),
(367, 21, 'kaars', 'margin', '50.00', '40', 5, '2024-11-10 13:01:57'),
(368, 21, 'kaars', 'sold_in_branches', '3', '1,2,3', 5, '2024-11-10 13:01:57'),
(369, 21, 'kaars', 'amount_paraffin_grams', '0', '324', 5, '2024-11-10 13:01:57'),
(370, 21, 'kaars', 'amount_stearin_grams', '360', '36', 5, '2024-11-10 13:01:57'),
(371, 21, 'kaars', 'price_per_gram_paraffin', '0.000', '0.006', 5, '2024-11-10 13:01:57'),
(372, 21, 'kaars', 'price_per_gram_stearin', '0.010', '0.008', 5, '2024-11-10 13:01:57'),
(373, 21, 'kaars', 'total_product_price', '14.27', '10.997448', 5, '2024-11-10 13:01:57'),
(374, 22, 'kaars', 'margin', '50.00', '40', 5, '2024-11-10 13:03:02'),
(375, 22, 'kaars', 'sold_in_branches', '3', '1,2,3', 5, '2024-11-10 13:03:02'),
(376, 22, 'kaars', 'amount_paraffin_grams', '0', '144', 5, '2024-11-10 13:03:02'),
(377, 22, 'kaars', 'amount_stearin_grams', '160', '16', 5, '2024-11-10 13:03:02'),
(378, 22, 'kaars', 'price_per_gram_paraffin', '0.000', '0.006', 5, '2024-11-10 13:03:02'),
(379, 22, 'kaars', 'price_per_gram_stearin', '0.010', '0.008', 5, '2024-11-10 13:03:02'),
(380, 22, 'kaars', 'total_product_price', '10.64', '8.896888', 5, '2024-11-10 13:03:02'),
(381, 23, 'kaars', 'margin', '50.00', '40', 5, '2024-11-10 13:05:05'),
(382, 23, 'kaars', 'sold_in_branches', '3', '1,2,3', 5, '2024-11-10 13:05:05'),
(383, 23, 'kaars', 'amount_paraffin_grams', '0', '126', 5, '2024-11-10 13:05:05'),
(384, 23, 'kaars', 'amount_stearin_grams', '140', '14', 5, '2024-11-10 13:05:05'),
(385, 23, 'kaars', 'price_per_gram_paraffin', '0.000', '0.006', 5, '2024-11-10 13:05:05'),
(386, 23, 'kaars', 'price_per_gram_stearin', '0.010', '0.008', 5, '2024-11-10 13:05:06'),
(387, 23, 'kaars', 'total_product_price', '10.27', '8.686832', 5, '2024-11-10 13:05:06'),
(388, 24, 'kaars', 'margin', '50.00', '40', 5, '2024-11-10 13:05:56'),
(389, 24, 'kaars', 'sold_in_branches', '3', '1,2,3', 5, '2024-11-10 13:05:56'),
(390, 24, 'kaars', 'amount_paraffin_grams', '0', '27', 5, '2024-11-10 13:05:56'),
(391, 24, 'kaars', 'amount_stearin_grams', '30', '3', 5, '2024-11-10 13:05:56'),
(392, 24, 'kaars', 'price_per_gram_paraffin', '0.000', '0.006', 5, '2024-11-10 13:05:56'),
(393, 24, 'kaars', 'price_per_gram_stearin', '0.010', '0.008', 5, '2024-11-10 13:05:56'),
(394, 24, 'kaars', 'total_product_price', '8.28', '7.531524', 5, '2024-11-10 13:05:56'),
(395, 24, 'kaars', 'hours_worked', '00:30', '00:20', 5, '2024-11-10 13:06:15'),
(396, 24, 'kaars', 'total_product_price', '7.53', '6.8256906666667', 5, '2024-11-10 13:06:15'),
(397, 25, 'kaars', 'margin', '50.00', '40', 5, '2024-11-10 13:07:11'),
(398, 25, 'kaars', 'sold_in_branches', '3', '1,2,3', 5, '2024-11-10 13:07:11'),
(399, 25, 'kaars', 'amount_paraffin_grams', '0', '135', 5, '2024-11-10 13:07:11'),
(400, 25, 'kaars', 'amount_stearin_grams', '150', '15', 5, '2024-11-10 13:07:11'),
(401, 25, 'kaars', 'price_per_gram_paraffin', '0.000', '0.006', 5, '2024-11-10 13:07:11'),
(402, 25, 'kaars', 'price_per_gram_stearin', '0.010', '0.008', 5, '2024-11-10 13:07:11'),
(403, 25, 'kaars', 'total_product_price', '10.45', '8.79186', 5, '2024-11-10 13:07:11'),
(404, 26, 'kaars', 'sold_in_branches', '3', '1,2,3', 5, '2024-11-10 13:08:18'),
(405, 26, 'kaars', 'price_per_gram_stearin', '0.007', '0.006', 5, '2024-11-10 13:08:18'),
(406, 26, 'kaars', 'total_product_price', '12.68', '12.655309333333', 5, '2024-11-10 13:08:18'),
(407, 26, 'kaars', 'extra_parts_price', '2.02', '2.55', 5, '2024-11-10 13:10:47'),
(408, 26, 'kaars', 'total_product_price', '12.66', '13.553129333333', 5, '2024-11-10 13:10:47'),
(409, 27, 'kaars', 'extra_parts_price', '2.02', '2.55', 5, '2024-11-10 13:11:46'),
(410, 27, 'kaars', 'price_per_gram_stearin', '0.007', '0.006', 5, '2024-11-10 13:11:46'),
(411, 27, 'kaars', 'total_product_price', '12.68', '13.553129333333', 5, '2024-11-10 13:11:46'),
(412, 28, 'kaars', 'extra_parts_price', '1.34', '1.69', 5, '2024-11-10 13:12:27'),
(413, 28, 'kaars', 'sold_in_branches', '3', '1,2,3', 5, '2024-11-10 13:12:27'),
(414, 28, 'kaars', 'price_per_gram_stearin', '0.007', '0.006', 5, '2024-11-10 13:12:27'),
(415, 28, 'kaars', 'total_product_price', '11.12', '11.699893333333', 5, '2024-11-10 13:12:27'),
(416, 29, 'kaars', 'extra_parts_price', '0.81', '1.03', 5, '2024-11-10 13:13:12'),
(417, 29, 'kaars', 'price_per_gram_stearin', '0.007', '0.006', 5, '2024-11-10 13:13:12'),
(418, 29, 'kaars', 'total_product_price', '8.94', '9.3040126666667', 5, '2024-11-10 13:13:12'),
(419, 32, 'kaars', 'margin', '40.00', '50', 5, '2024-11-13 09:49:13'),
(420, 32, 'kaars', 'total_product_price', '11.85', '12.69774', 5, '2024-11-13 09:49:13'),
(421, 32, 'kaars', 'margin', '50.00', '40', 5, '2024-11-13 09:49:39'),
(422, 32, 'kaars', 'total_product_price', '12.70', '11.851224', 5, '2024-11-13 09:49:39'),
(423, 248, 'terrazzo', 'amount_grams', '420', '676', 5, '2024-11-13 09:53:11'),
(424, 248, 'terrazzo', 'total_product_price', '23.97', '31.674412', 5, '2024-11-13 09:53:11'),
(425, 248, 'terrazzo', 'amount_grams', '676', '420', 5, '2024-11-13 09:53:43'),
(426, 248, 'terrazzo', 'total_product_price', '31.67', '23.9701', 5, '2024-11-13 09:53:43'),
(427, 30, 'kaars', 'amount_paraffin_grams', '135', '540', 5, '2024-11-17 15:05:10'),
(428, 30, 'kaars', 'amount_stearin_grams', '15', '60', 5, '2024-11-17 15:05:11'),
(429, 30, 'kaars', 'total_product_price', '8.88', '15.72032', 5, '2024-11-17 15:05:11'),
(430, 30, 'kaars', 'hours_worked', '00:30', '01:15', 5, '2024-11-17 15:05:53'),
(431, 30, 'kaars', 'total_product_price', '15.72', '22.07282', 5, '2024-11-17 15:05:53'),
(432, 30, 'kaars', 'hours_worked', '01:15', '00:30', 5, '2024-11-17 15:06:25'),
(433, 30, 'kaars', 'amount_paraffin_grams', '540', '135', 5, '2024-11-17 15:06:25'),
(434, 30, 'kaars', 'amount_stearin_grams', '60', '15', 5, '2024-11-17 15:06:25'),
(435, 30, 'kaars', 'total_product_price', '22.07', '8.87656', 5, '2024-11-17 15:06:25'),
(436, 266, 'epoxy', 'price_per_gram', '0.080', '0.016', 5, '2024-11-29 15:44:02'),
(437, 266, 'epoxy', 'total_product_price', '48.39', '15.972726', 5, '2024-11-29 15:44:02'),
(438, 267, 'epoxy', 'extra_parts_price', '9.17', '7.24', 5, '2024-11-30 15:19:02'),
(439, 267, 'epoxy', 'total_product_price', '46.18', '42.90902', 5, '2024-11-30 15:19:02'),
(440, 267, 'epoxy', 'hours_worked', '01:00', '00:45', 5, '2024-11-30 15:25:50'),
(441, 267, 'epoxy', 'total_product_price', '42.91', '40.79152', 5, '2024-11-30 15:25:50'),
(442, 267, 'epoxy', 'hours_worked', '00:45', '00:40', 5, '2024-11-30 15:26:08'),
(443, 267, 'epoxy', 'total_product_price', '40.79', '40.085686666667', 5, '2024-11-30 15:26:08'),
(444, 267, 'epoxy', 'margin', '40.00', '30', 5, '2024-11-30 15:36:37'),
(445, 267, 'epoxy', 'total_product_price', '40.09', '37.222423333333', 5, '2024-11-30 15:36:37'),
(446, 267, 'epoxy', 'margin', '30.00', '25', 5, '2024-11-30 15:37:00'),
(447, 267, 'epoxy', 'total_product_price', '37.22', '35.790791666667', 5, '2024-11-30 15:37:00'),
(448, 270, 'terrazzo', 'product_image', '../product_img/no_picture.jpg', '20241130_155141.jpg', 5, '2024-11-30 16:20:16'),
(449, 270, 'terrazzo', 'total_product_price', '13.67', '13.668321333333', 5, '2024-11-30 16:20:16'),
(450, 265, 'terrazzo', 'hours_worked', '00:20', '00:15', 1, '2024-12-01 12:16:17'),
(451, 265, 'terrazzo', 'total_product_price', '6.90', '6.550698', 1, '2024-12-01 12:16:17'),
(452, 265, 'terrazzo', 'hours_worked', '00:15', '00:20', 1, '2024-12-01 12:16:47'),
(453, 265, 'terrazzo', 'sold_in_branches', '1,2,3', '2', 1, '2024-12-01 12:16:47'),
(454, 265, 'terrazzo', 'total_product_price', '6.55', '6.9036146666667', 1, '2024-12-01 12:16:47'),
(455, 267, 'epoxy', 'product_description', '', 'afmeting 15cm', 1, '2024-12-01 12:17:42'),
(456, 267, 'epoxy', 'total_product_price', '35.79', '35.790791666667', 1, '2024-12-01 12:17:42'),
(457, 270, 'terrazzo', 'amount_grams', '473', '468', 5, '2024-12-06 09:17:25'),
(458, 270, 'terrazzo', 'total_product_price', '13.67', '13.592091333333', 5, '2024-12-06 09:17:25'),
(459, 248, 'terrazzo', 'amount_grams', '420', '993', 5, '2024-12-06 13:53:26'),
(460, 248, 'terrazzo', 'total_product_price', '23.97', '35.970396', 5, '2024-12-06 13:53:26'),
(461, 248, 'terrazzo', 'amount_grams', '993', '420', 5, '2024-12-06 13:53:59'),
(462, 248, 'terrazzo', 'total_product_price', '35.97', '23.9701', 5, '2024-12-06 13:53:59'),
(463, 272, 'terrazzo', 'price_per_gram', '0.090', '0.009', 5, '2024-12-06 14:01:20'),
(464, 272, 'terrazzo', 'total_product_price', '76.54', '13.147698666667', 5, '2024-12-06 14:01:20');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `product_schap`
--

CREATE TABLE `product_schap` (
  `id` int NOT NULL,
  `schap_id` int NOT NULL,
  `plank_nummer` int NOT NULL,
  `positie_op_plank` float NOT NULL,
  `epoxy_product_id` int DEFAULT NULL,
  `kaarsen_product_id` int DEFAULT NULL,
  `vers_product_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `stock_count`
--

CREATE TABLE `stock_count` (
  `id` int NOT NULL,
  `product_sku` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL,
  `counted_stock` int NOT NULL,
  `counted_by_user` int NOT NULL,
  `count_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `stock_count`
--

INSERT INTO `stock_count` (`id`, `product_sku`, `category`, `counted_stock`, `counted_by_user`, `count_date`) VALUES
(1, '20092', 'epoxy', 1, 1, '2024-08-13 18:54:14'),
(2, '20093', 'epoxy', 1, 1, '2024-08-13 18:54:14'),
(3, '20094', 'epoxy', 1, 1, '2024-08-13 18:54:14'),
(4, '20007', 'epoxy', 1, 1, '2024-08-13 18:54:14'),
(5, '20085', 'epoxy', 1, 1, '2024-08-13 18:54:14'),
(6, '20008', 'epoxy', 1, 1, '2024-08-13 18:54:15'),
(7, '20009', 'epoxy', 1, 1, '2024-08-13 18:54:15'),
(8, '20010', 'epoxy', 1, 1, '2024-08-13 18:54:15'),
(9, '20011', 'epoxy', 1, 1, '2024-08-13 18:54:16'),
(10, '20064', 'epoxy', 1, 1, '2024-08-13 18:54:16'),
(11, '20069', 'epoxy', 1, 1, '2024-08-13 18:54:16'),
(12, '20087', 'epoxy', 1, 1, '2024-08-13 18:54:16'),
(13, '20086', 'epoxy', 1, 1, '2024-08-13 18:54:16'),
(14, '20089', 'epoxy', 1, 1, '2024-08-13 18:54:16'),
(15, '20116', 'epoxy', 1, 1, '2024-08-13 18:54:16'),
(16, '20019', 'epoxy', 1, 1, '2024-08-13 18:54:17'),
(17, '20020', 'epoxy', 1, 1, '2024-08-13 18:54:17'),
(18, '20021', 'epoxy', 1, 1, '2024-08-13 18:54:17'),
(19, '20022', 'epoxy', 1, 1, '2024-08-13 18:54:17'),
(20, '20023', 'epoxy', 1, 1, '2024-08-13 18:54:17'),
(21, '20024', 'epoxy', 1, 1, '2024-08-13 18:54:17'),
(22, '20025', 'epoxy', 1, 1, '2024-08-13 18:54:17'),
(23, '20026', 'epoxy', 1, 1, '2024-08-13 18:54:18'),
(24, '20027', 'epoxy', 1, 1, '2024-08-13 18:54:18'),
(25, '20028', 'epoxy', 1, 1, '2024-08-13 18:54:18'),
(26, '20029', 'epoxy', 1, 1, '2024-08-13 18:54:18'),
(27, '20030', 'epoxy', 1, 1, '2024-08-13 18:54:18'),
(28, '20031', 'epoxy', 0, 1, '2024-08-13 18:54:18'),
(29, '20032', 'epoxy', 1, 1, '2024-08-13 18:54:18'),
(30, '20033', 'epoxy', 1, 1, '2024-08-13 18:54:19'),
(31, '20034', 'epoxy', 1, 1, '2024-08-13 18:54:19'),
(32, '20035', 'epoxy', 1, 1, '2024-08-13 18:54:19'),
(33, '20036', 'epoxy', 1, 1, '2024-08-13 18:54:19'),
(34, '20037', 'epoxy', 1, 1, '2024-08-13 18:54:19'),
(35, '20038', 'epoxy', 1, 1, '2024-08-13 18:54:19'),
(36, '20039', 'epoxy', 1, 1, '2024-08-13 18:54:20'),
(37, '20040', 'epoxy', 0, 1, '2024-08-13 18:54:20'),
(38, '20041', 'epoxy', 1, 1, '2024-08-13 18:54:20'),
(39, '20042', 'epoxy', 1, 1, '2024-08-13 18:54:20'),
(40, '20043', 'epoxy', 1, 1, '2024-08-13 18:54:20'),
(41, '20044', 'epoxy', 1, 1, '2024-08-13 18:54:21'),
(42, '20045', 'epoxy', 1, 1, '2024-08-13 18:54:21'),
(43, '20046', 'epoxy', 1, 1, '2024-08-13 18:54:21'),
(44, '20047', 'epoxy', 1, 1, '2024-08-13 18:54:21'),
(45, '20048', 'epoxy', 1, 1, '2024-08-13 18:54:21'),
(46, '20049', 'epoxy', 1, 1, '2024-08-13 18:54:22'),
(47, '20050', 'epoxy', 1, 1, '2024-08-13 18:54:22'),
(48, '20051', 'epoxy', 1, 1, '2024-08-13 18:54:22'),
(49, '20052', 'epoxy', 1, 1, '2024-08-13 18:54:22'),
(50, '20053', 'epoxy', 1, 1, '2024-08-13 18:54:23'),
(51, '20054', 'epoxy', 1, 1, '2024-08-13 18:54:23'),
(52, '20055', 'epoxy', 1, 1, '2024-08-13 18:54:23'),
(53, '20056', 'epoxy', 1, 1, '2024-08-13 18:54:23'),
(54, '20057', 'epoxy', 1, 1, '2024-08-13 18:54:24'),
(55, '20058', 'epoxy', 1, 1, '2024-08-13 18:54:24'),
(56, '20059', 'epoxy', 1, 1, '2024-08-13 18:54:24'),
(57, '20060', 'epoxy', 1, 1, '2024-08-13 18:54:24'),
(58, '20061', 'epoxy', 1, 1, '2024-08-13 18:54:24'),
(59, '20062', 'epoxy', 1, 1, '2024-08-13 18:54:24'),
(60, '20063', 'epoxy', 1, 1, '2024-08-13 18:54:24'),
(61, '20065', 'epoxy', 1, 1, '2024-08-13 18:54:25'),
(62, '20066', 'epoxy', 1, 1, '2024-08-13 18:54:25'),
(63, '20067', 'epoxy', 1, 1, '2024-08-13 18:54:25'),
(64, '20068', 'epoxy', 1, 1, '2024-08-13 18:54:25'),
(65, '20070', 'epoxy', 1, 1, '2024-08-13 18:54:25'),
(66, '20071', 'epoxy', 1, 1, '2024-08-13 18:54:25'),
(67, '20072', 'epoxy', 1, 1, '2024-08-13 18:54:26'),
(68, '20073', 'epoxy', 1, 1, '2024-08-13 18:54:26'),
(69, '20074', 'epoxy', 1, 1, '2024-08-13 18:54:26'),
(70, '20075', 'epoxy', 1, 1, '2024-08-13 18:54:26'),
(71, '20076', 'epoxy', 1, 1, '2024-08-13 18:54:27'),
(72, '20077', 'epoxy', 1, 1, '2024-08-13 18:54:27'),
(73, '20078', 'epoxy', 1, 1, '2024-08-13 18:54:27'),
(74, '20079', 'epoxy', 1, 1, '2024-08-13 18:54:27'),
(75, '20080', 'epoxy', 1, 1, '2024-08-13 18:54:28'),
(76, '20081', 'epoxy', 1, 1, '2024-08-13 18:54:28'),
(77, '20082', 'epoxy', 1, 1, '2024-08-13 18:54:28'),
(78, '20083', 'epoxy', 1, 1, '2024-08-13 18:54:28'),
(79, '20084', 'epoxy', 1, 1, '2024-08-13 18:54:28'),
(80, '20088', 'epoxy', 1, 1, '2024-08-13 18:54:29'),
(81, '20090', 'epoxy', 1, 1, '2024-08-13 18:54:29'),
(82, '20091', 'epoxy', 1, 1, '2024-08-13 18:54:29'),
(83, '20095', 'epoxy', 1, 1, '2024-08-13 18:54:29'),
(84, '20099', 'epoxy', 1, 1, '2024-08-13 18:54:29'),
(85, '20100', 'epoxy', 1, 1, '2024-08-13 18:54:29'),
(86, '20101', 'epoxy', 1, 1, '2024-08-13 18:54:30'),
(87, '20102', 'epoxy', 1, 1, '2024-08-13 18:54:30'),
(88, '20103', 'epoxy', 1, 1, '2024-08-13 18:54:30'),
(89, '20104', 'epoxy', 1, 1, '2024-08-13 18:54:30'),
(90, '20105', 'epoxy', 1, 1, '2024-08-13 18:54:30'),
(91, '20106', 'epoxy', 1, 1, '2024-08-13 18:54:31'),
(92, '20107', 'epoxy', 1, 1, '2024-08-13 18:54:31'),
(93, '20108', 'epoxy', 1, 1, '2024-08-13 18:54:31'),
(94, '20109', 'epoxy', 1, 1, '2024-08-13 18:54:32'),
(95, '20110', 'epoxy', 1, 1, '2024-08-13 18:54:32'),
(96, '20112', 'epoxy', 1, 1, '2024-08-13 18:54:33'),
(97, '20113', 'epoxy', 1, 1, '2024-08-13 18:54:33'),
(98, '20121', 'epoxy', 1, 1, '2024-08-13 18:54:33'),
(99, '20123', 'epoxy', 1, 1, '2024-08-13 18:54:33'),
(100, '20125', 'epoxy', 1, 1, '2024-08-13 18:54:33'),
(101, '20132', 'epoxy', 1, 1, '2024-08-13 18:54:33'),
(102, '20133', 'epoxy', 1, 1, '2024-08-13 18:54:34'),
(103, '20134', 'epoxy', 1, 1, '2024-08-13 18:54:34'),
(104, '20135', 'epoxy', 0, 1, '2024-08-13 18:54:34'),
(105, '20136', 'epoxy', 0, 1, '2024-08-13 18:54:35'),
(106, '20137', 'epoxy', 0, 1, '2024-08-13 18:54:35'),
(107, '20138', 'epoxy', 1, 1, '2024-08-13 18:54:35'),
(108, '20141', 'epoxy', 1, 1, '2024-08-13 18:54:35'),
(109, '20163', 'epoxy', 1, 1, '2024-08-13 18:54:35'),
(110, '20115', 'epoxy', 1, 1, '2024-08-13 18:54:35'),
(111, '20001', 'epoxy', 1, 1, '2024-08-13 18:54:35'),
(112, '20002', 'epoxy', 1, 1, '2024-08-13 18:54:36'),
(113, '20003', 'epoxy', 1, 1, '2024-08-13 18:54:36'),
(114, '20004', 'epoxy', 1, 1, '2024-08-13 18:54:36'),
(115, '20005', 'epoxy', 1, 1, '2024-08-13 18:54:36'),
(116, '20006', 'epoxy', 1, 1, '2024-08-13 18:54:36'),
(117, '20114', 'epoxy', 1, 1, '2024-08-13 18:54:37'),
(118, '20012', 'epoxy', 1, 1, '2024-08-13 18:54:37'),
(119, '20017', 'epoxy', 1, 1, '2024-08-13 18:54:37'),
(120, '20018', 'epoxy', 1, 1, '2024-08-13 18:54:37'),
(121, '20014', 'epoxy', 1, 1, '2024-08-13 18:54:37'),
(122, '20013', 'epoxy', 2, 1, '2024-08-13 18:54:37'),
(123, '20118', 'epoxy', 2, 1, '2024-08-13 18:54:38'),
(124, '20117', 'epoxy', 0, 1, '2024-08-13 18:54:38'),
(125, '20119', 'epoxy', 0, 1, '2024-08-13 18:54:38'),
(126, '20015', 'epoxy', 0, 1, '2024-08-13 18:54:38'),
(127, '20016', 'epoxy', 0, 1, '2024-08-13 18:54:38'),
(128, '20096', 'epoxy', 1, 1, '2024-08-13 18:54:38'),
(129, '20000', 'epoxy', 1, 1, '2024-08-13 18:54:38'),
(130, '20120', 'epoxy', 1, 1, '2024-08-13 18:54:38'),
(131, '20122', 'epoxy', 1, 1, '2024-08-13 18:54:39'),
(132, '20124', 'epoxy', 1, 1, '2024-08-13 18:54:39'),
(133, '20126', 'epoxy', 1, 1, '2024-08-13 18:54:39'),
(134, '20127', 'epoxy', 1, 1, '2024-08-13 18:54:39'),
(135, '20128', 'epoxy', 1, 1, '2024-08-13 18:54:39'),
(136, '20129', 'epoxy', 1, 1, '2024-08-13 18:54:39'),
(137, '20130', 'epoxy', 1, 1, '2024-08-13 18:54:39'),
(138, '20131', 'epoxy', 1, 1, '2024-08-13 18:54:40'),
(139, '20139', 'epoxy', 1, 1, '2024-08-13 18:54:40'),
(140, '20140', 'epoxy', 1, 1, '2024-08-13 18:54:40'),
(141, '20142', 'epoxy', 1, 1, '2024-08-13 18:54:40'),
(142, '20144', 'epoxy', 1, 1, '2024-08-13 18:54:41'),
(143, '20145', 'epoxy', 1, 1, '2024-08-13 18:54:41'),
(144, '20146', 'epoxy', 1, 1, '2024-08-13 18:54:41'),
(145, '20143', 'epoxy', 1, 1, '2024-08-13 18:54:42'),
(146, '20147', 'epoxy', 1, 1, '2024-08-13 18:54:42'),
(147, '20148', 'epoxy', 1, 1, '2024-08-13 18:54:42'),
(148, '20149', 'epoxy', 1, 1, '2024-08-13 18:54:43'),
(149, '20150', 'epoxy', 1, 1, '2024-08-13 18:54:43'),
(150, '20092', 'epoxy', 1, 1, '2024-08-13 18:54:48'),
(151, '20093', 'epoxy', 1, 1, '2024-08-13 18:54:48'),
(152, '20094', 'epoxy', 1, 1, '2024-08-13 18:54:48'),
(153, '20007', 'epoxy', 1, 1, '2024-08-13 18:54:48'),
(154, '20085', 'epoxy', 1, 1, '2024-08-13 18:54:48'),
(155, '20008', 'epoxy', 1, 1, '2024-08-13 18:54:48'),
(156, '20009', 'epoxy', 1, 1, '2024-08-13 18:54:49'),
(157, '20010', 'epoxy', 1, 1, '2024-08-13 18:54:49'),
(158, '20011', 'epoxy', 1, 1, '2024-08-13 18:54:49'),
(159, '20064', 'epoxy', 1, 1, '2024-08-13 18:54:49'),
(160, '20069', 'epoxy', 1, 1, '2024-08-13 18:54:49'),
(161, '20087', 'epoxy', 1, 1, '2024-08-13 18:54:49'),
(162, '20086', 'epoxy', 1, 1, '2024-08-13 18:54:49'),
(163, '20089', 'epoxy', 1, 1, '2024-08-13 18:54:49'),
(164, '20116', 'epoxy', 1, 1, '2024-08-13 18:54:49'),
(165, '20019', 'epoxy', 1, 1, '2024-08-13 18:54:49'),
(166, '20020', 'epoxy', 1, 1, '2024-08-13 18:54:49'),
(167, '20021', 'epoxy', 1, 1, '2024-08-13 18:54:49'),
(168, '20022', 'epoxy', 1, 1, '2024-08-13 18:54:49'),
(169, '20023', 'epoxy', 1, 1, '2024-08-13 18:54:50'),
(170, '20024', 'epoxy', 1, 1, '2024-08-13 18:54:50'),
(171, '20025', 'epoxy', 1, 1, '2024-08-13 18:54:50'),
(172, '20026', 'epoxy', 1, 1, '2024-08-13 18:54:50'),
(173, '20027', 'epoxy', 1, 1, '2024-08-13 18:54:50'),
(174, '20028', 'epoxy', 1, 1, '2024-08-13 18:54:50'),
(175, '20029', 'epoxy', 1, 1, '2024-08-13 18:54:50'),
(176, '20030', 'epoxy', 1, 1, '2024-08-13 18:54:50'),
(177, '20031', 'epoxy', 0, 1, '2024-08-13 18:54:50'),
(178, '20032', 'epoxy', 1, 1, '2024-08-13 18:54:50'),
(179, '20033', 'epoxy', 1, 1, '2024-08-13 18:54:50'),
(180, '20034', 'epoxy', 1, 1, '2024-08-13 18:54:50'),
(181, '20035', 'epoxy', 1, 1, '2024-08-13 18:54:50'),
(182, '20036', 'epoxy', 1, 1, '2024-08-13 18:54:50'),
(183, '20037', 'epoxy', 1, 1, '2024-08-13 18:54:50'),
(184, '20038', 'epoxy', 1, 1, '2024-08-13 18:54:50'),
(185, '20039', 'epoxy', 1, 1, '2024-08-13 18:54:51'),
(186, '20040', 'epoxy', 0, 1, '2024-08-13 18:54:51'),
(187, '20041', 'epoxy', 1, 1, '2024-08-13 18:54:51'),
(188, '20042', 'epoxy', 1, 1, '2024-08-13 18:54:51'),
(189, '20043', 'epoxy', 1, 1, '2024-08-13 18:54:51'),
(190, '20044', 'epoxy', 1, 1, '2024-08-13 18:54:51'),
(191, '20045', 'epoxy', 1, 1, '2024-08-13 18:54:51'),
(192, '20046', 'epoxy', 1, 1, '2024-08-13 18:54:51'),
(193, '20047', 'epoxy', 1, 1, '2024-08-13 18:54:51'),
(194, '20048', 'epoxy', 1, 1, '2024-08-13 18:54:51'),
(195, '20049', 'epoxy', 1, 1, '2024-08-13 18:54:51'),
(196, '20050', 'epoxy', 1, 1, '2024-08-13 18:54:51'),
(197, '20051', 'epoxy', 1, 1, '2024-08-13 18:54:51'),
(198, '20052', 'epoxy', 1, 1, '2024-08-13 18:54:51'),
(199, '20053', 'epoxy', 1, 1, '2024-08-13 18:54:51'),
(200, '20054', 'epoxy', 1, 1, '2024-08-13 18:54:51'),
(201, '20055', 'epoxy', 1, 1, '2024-08-13 18:54:51'),
(202, '20056', 'epoxy', 1, 1, '2024-08-13 18:54:52'),
(203, '20057', 'epoxy', 1, 1, '2024-08-13 18:54:52'),
(204, '20058', 'epoxy', 1, 1, '2024-08-13 18:54:52'),
(205, '20059', 'epoxy', 1, 1, '2024-08-13 18:54:52'),
(206, '20060', 'epoxy', 1, 1, '2024-08-13 18:54:52'),
(207, '20061', 'epoxy', 1, 1, '2024-08-13 18:54:52'),
(208, '20062', 'epoxy', 1, 1, '2024-08-13 18:54:52'),
(209, '20063', 'epoxy', 1, 1, '2024-08-13 18:54:52'),
(210, '20065', 'epoxy', 1, 1, '2024-08-13 18:54:52'),
(211, '20066', 'epoxy', 1, 1, '2024-08-13 18:54:52'),
(212, '20067', 'epoxy', 1, 1, '2024-08-13 18:54:52'),
(213, '20068', 'epoxy', 1, 1, '2024-08-13 18:54:52'),
(214, '20070', 'epoxy', 1, 1, '2024-08-13 18:54:52'),
(215, '20071', 'epoxy', 1, 1, '2024-08-13 18:54:52'),
(216, '20072', 'epoxy', 1, 1, '2024-08-13 18:54:52'),
(217, '20073', 'epoxy', 1, 1, '2024-08-13 18:54:53'),
(218, '20074', 'epoxy', 1, 1, '2024-08-13 18:54:53'),
(219, '20075', 'epoxy', 1, 1, '2024-08-13 18:54:53'),
(220, '20076', 'epoxy', 1, 1, '2024-08-13 18:54:53'),
(221, '20077', 'epoxy', 1, 1, '2024-08-13 18:54:53'),
(222, '20078', 'epoxy', 1, 1, '2024-08-13 18:54:53'),
(223, '20079', 'epoxy', 1, 1, '2024-08-13 18:54:53'),
(224, '20080', 'epoxy', 1, 1, '2024-08-13 18:54:53'),
(225, '20081', 'epoxy', 1, 1, '2024-08-13 18:54:53'),
(226, '20082', 'epoxy', 1, 1, '2024-08-13 18:54:53'),
(227, '20083', 'epoxy', 1, 1, '2024-08-13 18:54:53'),
(228, '20084', 'epoxy', 1, 1, '2024-08-13 18:54:53'),
(229, '20088', 'epoxy', 1, 1, '2024-08-13 18:54:53'),
(230, '20090', 'epoxy', 1, 1, '2024-08-13 18:54:53'),
(231, '20091', 'epoxy', 1, 1, '2024-08-13 18:54:53'),
(232, '20095', 'epoxy', 1, 1, '2024-08-13 18:54:53'),
(233, '20099', 'epoxy', 1, 1, '2024-08-13 18:54:54'),
(234, '20100', 'epoxy', 1, 1, '2024-08-13 18:54:54'),
(235, '20101', 'epoxy', 1, 1, '2024-08-13 18:54:54'),
(236, '20102', 'epoxy', 1, 1, '2024-08-13 18:54:54'),
(237, '20103', 'epoxy', 1, 1, '2024-08-13 18:54:54'),
(238, '20104', 'epoxy', 1, 1, '2024-08-13 18:54:54'),
(239, '20105', 'epoxy', 1, 1, '2024-08-13 18:54:54'),
(240, '20106', 'epoxy', 1, 1, '2024-08-13 18:54:54'),
(241, '20107', 'epoxy', 1, 1, '2024-08-13 18:54:54'),
(242, '20108', 'epoxy', 1, 1, '2024-08-13 18:54:54'),
(243, '20109', 'epoxy', 1, 1, '2024-08-13 18:54:54'),
(244, '20110', 'epoxy', 1, 1, '2024-08-13 18:54:54'),
(245, '20112', 'epoxy', 1, 1, '2024-08-13 18:54:54'),
(246, '20113', 'epoxy', 1, 1, '2024-08-13 18:54:54'),
(247, '20121', 'epoxy', 1, 1, '2024-08-13 18:54:55'),
(248, '20123', 'epoxy', 1, 1, '2024-08-13 18:54:55'),
(249, '20125', 'epoxy', 1, 1, '2024-08-13 18:54:55'),
(250, '20132', 'epoxy', 1, 1, '2024-08-13 18:54:55'),
(251, '20133', 'epoxy', 1, 1, '2024-08-13 18:54:55'),
(252, '20134', 'epoxy', 1, 1, '2024-08-13 18:54:55'),
(253, '20135', 'epoxy', 0, 1, '2024-08-13 18:54:55'),
(254, '20136', 'epoxy', 0, 1, '2024-08-13 18:54:55'),
(255, '20137', 'epoxy', 0, 1, '2024-08-13 18:54:55'),
(256, '20138', 'epoxy', 1, 1, '2024-08-13 18:54:55'),
(257, '20141', 'epoxy', 1, 1, '2024-08-13 18:54:55'),
(258, '20163', 'epoxy', 1, 1, '2024-08-13 18:54:55'),
(259, '20115', 'epoxy', 1, 1, '2024-08-13 18:54:55'),
(260, '20001', 'epoxy', 1, 1, '2024-08-13 18:54:55'),
(261, '20002', 'epoxy', 1, 1, '2024-08-13 18:54:56'),
(262, '20003', 'epoxy', 1, 1, '2024-08-13 18:54:56'),
(263, '20004', 'epoxy', 1, 1, '2024-08-13 18:54:56'),
(264, '20005', 'epoxy', 1, 1, '2024-08-13 18:54:56'),
(265, '20006', 'epoxy', 1, 1, '2024-08-13 18:54:56'),
(266, '20114', 'epoxy', 1, 1, '2024-08-13 18:54:56'),
(267, '20012', 'epoxy', 1, 1, '2024-08-13 18:54:56'),
(268, '20017', 'epoxy', 1, 1, '2024-08-13 18:54:56'),
(269, '20018', 'epoxy', 1, 1, '2024-08-13 18:54:56'),
(270, '20014', 'epoxy', 1, 1, '2024-08-13 18:54:56'),
(271, '20013', 'epoxy', 2, 1, '2024-08-13 18:54:56'),
(272, '20118', 'epoxy', 2, 1, '2024-08-13 18:54:56'),
(273, '20117', 'epoxy', 0, 1, '2024-08-13 18:54:56'),
(274, '20119', 'epoxy', 0, 1, '2024-08-13 18:54:57'),
(275, '20015', 'epoxy', 0, 1, '2024-08-13 18:54:57'),
(276, '20016', 'epoxy', 0, 1, '2024-08-13 18:54:57'),
(277, '20096', 'epoxy', 1, 1, '2024-08-13 18:54:57'),
(278, '20000', 'epoxy', 1, 1, '2024-08-13 18:54:57'),
(279, '20120', 'epoxy', 1, 1, '2024-08-13 18:54:57'),
(280, '20122', 'epoxy', 1, 1, '2024-08-13 18:54:57'),
(281, '20124', 'epoxy', 1, 1, '2024-08-13 18:54:57'),
(282, '20126', 'epoxy', 1, 1, '2024-08-13 18:54:57'),
(283, '20127', 'epoxy', 1, 1, '2024-08-13 18:54:57'),
(284, '20128', 'epoxy', 1, 1, '2024-08-13 18:54:58'),
(285, '20129', 'epoxy', 1, 1, '2024-08-13 18:54:58'),
(286, '20130', 'epoxy', 1, 1, '2024-08-13 18:54:58'),
(287, '20131', 'epoxy', 1, 1, '2024-08-13 18:54:58'),
(288, '20139', 'epoxy', 1, 1, '2024-08-13 18:54:58'),
(289, '20140', 'epoxy', 1, 1, '2024-08-13 18:54:58'),
(290, '20142', 'epoxy', 1, 1, '2024-08-13 18:54:58'),
(291, '20144', 'epoxy', 1, 1, '2024-08-13 18:54:58'),
(292, '20145', 'epoxy', 1, 1, '2024-08-13 18:54:58'),
(293, '20146', 'epoxy', 1, 1, '2024-08-13 18:54:58'),
(294, '20143', 'epoxy', 1, 1, '2024-08-13 18:54:58'),
(295, '20147', 'epoxy', 1, 1, '2024-08-13 18:54:58'),
(296, '20148', 'epoxy', 1, 1, '2024-08-13 18:54:58'),
(297, '20149', 'epoxy', 1, 1, '2024-08-13 18:54:59'),
(298, '20150', 'epoxy', 1, 1, '2024-08-13 18:54:59'),
(299, '20151', 'epoxy', 1, 1, '2024-08-13 18:54:59'),
(300, '20152', 'epoxy', 1, 1, '2024-08-13 18:54:59'),
(301, '20153', 'epoxy', 1, 1, '2024-08-13 18:54:59'),
(302, '20154', 'epoxy', 1, 1, '2024-08-13 18:54:59'),
(303, '30000', 'kaarsen', 1, 1, '2024-08-13 18:54:59'),
(304, '30001', 'kaarsen', 1, 1, '2024-08-13 18:54:59'),
(305, '30002', 'kaarsen', 1, 1, '2024-08-13 18:55:00'),
(306, '30004', 'kaarsen', 1, 1, '2024-08-13 18:55:00'),
(307, '30005', 'kaarsen', 1, 1, '2024-08-13 18:55:00'),
(308, '30006', 'kaarsen', 1, 1, '2024-08-13 18:55:00'),
(309, '30007', 'kaarsen', 1, 1, '2024-08-13 18:55:00'),
(310, '30008', 'kaarsen', 1, 1, '2024-08-13 18:55:00'),
(311, '30003', 'kaarsen', 1, 1, '2024-08-13 18:55:01'),
(312, '30009', 'kaarsen', 1, 1, '2024-08-13 18:55:01'),
(313, '30010', 'kaarsen', 1, 1, '2024-08-13 18:55:01'),
(314, '30011', 'kaarsen', 1, 1, '2024-08-13 18:55:01'),
(315, '30012', 'kaarsen', 1, 1, '2024-08-13 18:55:02'),
(316, '30013', 'kaarsen', 1, 1, '2024-08-13 18:55:02'),
(317, '30014', 'kaarsen', 1, 1, '2024-08-13 18:55:02'),
(318, '30015', 'kaarsen', 1, 1, '2024-08-13 18:55:03'),
(319, '30016', 'kaarsen', 1, 1, '2024-08-13 18:55:03'),
(320, '30017', 'kaarsen', 1, 1, '2024-08-13 18:55:03'),
(321, '30018', 'kaarsen', 1, 1, '2024-08-13 18:55:03'),
(322, '30019', 'kaarsen', 1, 1, '2024-08-13 18:55:03'),
(323, '30020', 'kaarsen', 1, 1, '2024-08-13 18:55:03'),
(324, '30021', 'kaarsen', 1, 1, '2024-08-13 18:55:04'),
(325, '30022', 'kaarsen', 1, 1, '2024-08-13 18:55:04'),
(326, '30023', 'kaarsen', 1, 1, '2024-08-13 18:55:04'),
(327, '30024', 'kaarsen', 1, 1, '2024-08-13 18:55:04'),
(328, '10015', 'vers', 0, 1, '2024-08-13 18:55:05'),
(329, '10011', 'vers', 0, 1, '2024-08-13 18:55:05'),
(330, '10016', 'vers', 0, 1, '2024-08-13 18:55:05'),
(331, '10013', 'vers', 0, 1, '2024-08-13 18:55:05'),
(332, '10028', 'vers', 0, 1, '2024-08-13 18:55:05'),
(333, '10014', 'vers', 0, 1, '2024-08-13 18:55:05'),
(334, '1002282', 'vers', 0, 1, '2024-08-13 18:55:05'),
(335, '1002283', 'vers', 0, 1, '2024-08-13 18:55:05'),
(336, '100228', 'vers', 0, 1, '2024-08-13 18:55:05'),
(337, '1002281', 'vers', 0, 1, '2024-08-13 18:55:05'),
(338, '10030', 'vers', 0, 1, '2024-08-13 18:55:05'),
(339, '20092', 'epoxy', 2, 2, '2024-08-21 08:50:37'),
(340, '20093', 'epoxy', 1, 2, '2024-08-21 08:50:37'),
(341, '20094', 'epoxy', 1, 2, '2024-08-21 08:50:37'),
(342, '20007', 'epoxy', 1, 2, '2024-08-21 08:50:37'),
(343, '20085', 'epoxy', 1, 2, '2024-08-21 08:50:38'),
(344, '20008', 'epoxy', 1, 2, '2024-08-21 08:50:38'),
(345, '20009', 'epoxy', 0, 2, '2024-08-21 08:50:38'),
(346, '20010', 'epoxy', 0, 2, '2024-08-21 08:50:38'),
(347, '20011', 'epoxy', 1, 2, '2024-08-21 08:50:38'),
(348, '20064', 'epoxy', 2, 2, '2024-08-21 08:50:38'),
(349, '20069', 'epoxy', 0, 2, '2024-08-21 08:50:38'),
(350, '20087', 'epoxy', 0, 2, '2024-08-21 08:50:38'),
(351, '20086', 'epoxy', 1, 2, '2024-08-21 08:50:39'),
(352, '20089', 'epoxy', 3, 2, '2024-08-21 08:50:39'),
(353, '20116', 'epoxy', 1, 2, '2024-08-21 08:50:39'),
(354, '20019', 'epoxy', 2, 2, '2024-08-21 08:50:39'),
(355, '20020', 'epoxy', 1, 2, '2024-08-21 08:50:39'),
(356, '20021', 'epoxy', 2, 2, '2024-08-21 08:50:39'),
(357, '20022', 'epoxy', 2, 2, '2024-08-21 08:50:39'),
(358, '20023', 'epoxy', 2, 2, '2024-08-21 08:50:39'),
(359, '20024', 'epoxy', 3, 2, '2024-08-21 08:50:40'),
(360, '20025', 'epoxy', 0, 2, '2024-08-21 08:50:40'),
(361, '20026', 'epoxy', 2, 2, '2024-08-21 08:50:40'),
(362, '20027', 'epoxy', 1, 2, '2024-08-21 08:50:40'),
(363, '20028', 'epoxy', 1, 2, '2024-08-21 08:50:40'),
(364, '20029', 'epoxy', 0, 2, '2024-08-21 08:50:40'),
(365, '20030', 'epoxy', 0, 2, '2024-08-21 08:50:40'),
(366, '20031', 'epoxy', 0, 2, '2024-08-21 08:50:40'),
(367, '20032', 'epoxy', 2, 2, '2024-08-21 08:50:41'),
(368, '20033', 'epoxy', 1, 2, '2024-08-21 08:50:41'),
(369, '20034', 'epoxy', 1, 2, '2024-08-21 08:50:41'),
(370, '20035', 'epoxy', 1, 2, '2024-08-21 08:50:41'),
(371, '20036', 'epoxy', 1, 2, '2024-08-21 08:50:41'),
(372, '20037', 'epoxy', 1, 2, '2024-08-21 08:50:41'),
(373, '20038', 'epoxy', 1, 2, '2024-08-21 08:50:41'),
(374, '20039', 'epoxy', 1, 2, '2024-08-21 08:50:41'),
(375, '20040', 'epoxy', 0, 2, '2024-08-21 08:50:41'),
(376, '20041', 'epoxy', 1, 2, '2024-08-21 08:50:41'),
(377, '20042', 'epoxy', 2, 2, '2024-08-21 08:50:41'),
(378, '20043', 'epoxy', 1, 2, '2024-08-21 08:50:41'),
(379, '20044', 'epoxy', 2, 2, '2024-08-21 08:50:42'),
(380, '20045', 'epoxy', 0, 2, '2024-08-21 08:50:42'),
(381, '20046', 'epoxy', 2, 2, '2024-08-21 08:50:42'),
(382, '20047', 'epoxy', 1, 2, '2024-08-21 08:50:42'),
(383, '20048', 'epoxy', 0, 2, '2024-08-21 08:50:42'),
(384, '20049', 'epoxy', 1, 2, '2024-08-21 08:50:42'),
(385, '20050', 'epoxy', 1, 2, '2024-08-21 08:50:42'),
(386, '20051', 'epoxy', 1, 2, '2024-08-21 08:50:42'),
(387, '20052', 'epoxy', 0, 2, '2024-08-21 08:50:42'),
(388, '20053', 'epoxy', 1, 2, '2024-08-21 08:50:42'),
(389, '20054', 'epoxy', 1, 2, '2024-08-21 08:50:43'),
(390, '20055', 'epoxy', 2, 2, '2024-08-21 08:50:43'),
(391, '20056', 'epoxy', 1, 2, '2024-08-21 08:50:43'),
(392, '20057', 'epoxy', 0, 2, '2024-08-21 08:50:43'),
(393, '20058', 'epoxy', 0, 2, '2024-08-21 08:50:43'),
(394, '20059', 'epoxy', 0, 2, '2024-08-21 08:50:43'),
(395, '20060', 'epoxy', 1, 2, '2024-08-21 08:50:43'),
(396, '20061', 'epoxy', 0, 2, '2024-08-21 08:50:43'),
(397, '20062', 'epoxy', 1, 2, '2024-08-21 08:50:44'),
(398, '20063', 'epoxy', 0, 2, '2024-08-21 08:50:44'),
(399, '20065', 'epoxy', 1, 2, '2024-08-21 08:50:44'),
(400, '20066', 'epoxy', 0, 2, '2024-08-21 08:50:44'),
(401, '20067', 'epoxy', 1, 2, '2024-08-21 08:50:44'),
(402, '20068', 'epoxy', 0, 2, '2024-08-21 08:50:44'),
(403, '20070', 'epoxy', 1, 2, '2024-08-21 08:50:44'),
(404, '20071', 'epoxy', 1, 2, '2024-08-21 08:50:44'),
(405, '20072', 'epoxy', 1, 2, '2024-08-21 08:50:45'),
(406, '20073', 'epoxy', 1, 2, '2024-08-21 08:50:45'),
(407, '20074', 'epoxy', 1, 2, '2024-08-21 08:50:45'),
(408, '20075', 'epoxy', 1, 2, '2024-08-21 08:50:45'),
(409, '20076', 'epoxy', 0, 2, '2024-08-21 08:50:46'),
(410, '20077', 'epoxy', 1, 2, '2024-08-21 08:50:46'),
(411, '20078', 'epoxy', 1, 2, '2024-08-21 08:50:46'),
(412, '20080', 'epoxy', 1, 2, '2024-08-21 08:50:46'),
(413, '20081', 'epoxy', 1, 2, '2024-08-21 08:50:46'),
(414, '20082', 'epoxy', 2, 2, '2024-08-21 08:50:46'),
(415, '20083', 'epoxy', 2, 2, '2024-08-21 08:50:47'),
(416, '20084', 'epoxy', 2, 2, '2024-08-21 08:50:47'),
(417, '20088', 'epoxy', 0, 2, '2024-08-21 08:50:47'),
(418, '20090', 'epoxy', 1, 2, '2024-08-21 08:50:47'),
(419, '20091', 'epoxy', 2, 2, '2024-08-21 08:50:47'),
(420, '20095', 'epoxy', 2, 2, '2024-08-21 08:50:47'),
(421, '20099', 'epoxy', 2, 2, '2024-08-21 08:50:48'),
(422, '20100', 'epoxy', 2, 2, '2024-08-21 08:50:48'),
(423, '20101', 'epoxy', 0, 2, '2024-08-21 08:50:48'),
(424, '20102', 'epoxy', 1, 2, '2024-08-21 08:50:48'),
(425, '20103', 'epoxy', 1, 2, '2024-08-21 08:50:48'),
(426, '20104', 'epoxy', 1, 2, '2024-08-21 08:50:48'),
(427, '20105', 'epoxy', 1, 2, '2024-08-21 08:50:48'),
(428, '20106', 'epoxy', 1, 2, '2024-08-21 08:50:48'),
(429, '20107', 'epoxy', 0, 2, '2024-08-21 08:50:48'),
(430, '20108', 'epoxy', 0, 2, '2024-08-21 08:50:49'),
(431, '20109', 'epoxy', 1, 2, '2024-08-21 08:50:49'),
(432, '20110', 'epoxy', 1, 2, '2024-08-21 08:50:49'),
(433, '20112', 'epoxy', 4, 2, '2024-08-21 08:50:49'),
(434, '20113', 'epoxy', 1, 2, '2024-08-21 08:50:49'),
(435, '20121', 'epoxy', 1, 2, '2024-08-21 08:50:49'),
(436, '20123', 'epoxy', 1, 2, '2024-08-21 08:50:49'),
(437, '20125', 'epoxy', 0, 2, '2024-08-21 08:50:49'),
(438, '20132', 'epoxy', 1, 2, '2024-08-21 08:50:49'),
(439, '20133', 'epoxy', 0, 2, '2024-08-21 08:50:50'),
(440, '20134', 'epoxy', 1, 2, '2024-08-21 08:50:50'),
(441, '20135', 'epoxy', 0, 2, '2024-08-21 08:50:50'),
(442, '20136', 'epoxy', 0, 2, '2024-08-21 08:50:50'),
(443, '20137', 'epoxy', 0, 2, '2024-08-21 08:50:50'),
(444, '20138', 'epoxy', 1, 2, '2024-08-21 08:50:50'),
(445, '20141', 'epoxy', 2, 2, '2024-08-21 08:50:50'),
(446, '20163', 'epoxy', 1, 2, '2024-08-21 08:50:50'),
(447, '20115', 'epoxy', 1, 2, '2024-08-21 08:50:50'),
(448, '20001', 'epoxy', 1, 2, '2024-08-21 08:50:51'),
(449, '20002', 'epoxy', 1, 2, '2024-08-21 08:50:51'),
(450, '20003', 'epoxy', 1, 2, '2024-08-21 08:50:51'),
(451, '20004', 'epoxy', 1, 2, '2024-08-21 08:50:51'),
(452, '20005', 'epoxy', 2, 2, '2024-08-21 08:50:51'),
(453, '20006', 'epoxy', 1, 2, '2024-08-21 08:50:51'),
(454, '20114', 'epoxy', 1, 2, '2024-08-21 08:50:51'),
(455, '20012', 'epoxy', 4, 2, '2024-08-21 08:50:51'),
(456, '20017', 'epoxy', 6, 2, '2024-08-21 08:50:52'),
(457, '20018', 'epoxy', 0, 2, '2024-08-21 08:50:52'),
(458, '20014', 'epoxy', 1, 2, '2024-08-21 08:50:52'),
(459, '20013', 'epoxy', 2, 2, '2024-08-21 08:50:52'),
(460, '20118', 'epoxy', 2, 2, '2024-08-21 08:50:52'),
(461, '20117', 'epoxy', 0, 2, '2024-08-21 08:50:52'),
(462, '20119', 'epoxy', 0, 2, '2024-08-21 08:50:52'),
(463, '20015', 'epoxy', 0, 2, '2024-08-21 08:50:52'),
(464, '20016', 'epoxy', 0, 2, '2024-08-21 08:50:52'),
(465, '20096', 'epoxy', 2, 2, '2024-08-21 08:50:52'),
(466, '20097', 'epoxy', 5, 2, '2024-08-21 08:50:53'),
(467, '20098', 'epoxy', 6, 2, '2024-08-21 08:50:53'),
(468, '20000', 'epoxy', 0, 2, '2024-08-21 08:50:53'),
(469, '20120', 'epoxy', 1, 2, '2024-08-21 08:50:53'),
(470, '20122', 'epoxy', 1, 2, '2024-08-21 08:50:53'),
(471, '20124', 'epoxy', 1, 2, '2024-08-21 08:50:53'),
(472, '20126', 'epoxy', 1, 2, '2024-08-21 08:50:53'),
(473, '20127', 'epoxy', 2, 2, '2024-08-21 08:50:53'),
(474, '20128', 'epoxy', 3, 2, '2024-08-21 08:50:53'),
(475, '20129', 'epoxy', 1, 2, '2024-08-21 08:50:53'),
(476, '20130', 'epoxy', 1, 2, '2024-08-21 08:50:54'),
(477, '20131', 'epoxy', 1, 2, '2024-08-21 08:50:54'),
(478, '20139', 'epoxy', 1, 2, '2024-08-21 08:50:54'),
(479, '20140', 'epoxy', 1, 2, '2024-08-21 08:50:54'),
(480, '20142', 'epoxy', 1, 2, '2024-08-21 08:50:54'),
(481, '20144', 'epoxy', 1, 2, '2024-08-21 08:50:54'),
(482, '20145', 'epoxy', 1, 2, '2024-08-21 08:50:54'),
(483, '20146', 'epoxy', 1, 2, '2024-08-21 08:50:54'),
(484, '20143', 'epoxy', 1, 2, '2024-08-21 08:50:54'),
(485, '20147', 'epoxy', 1, 2, '2024-08-21 08:50:54'),
(486, '20148', 'epoxy', 1, 2, '2024-08-21 08:50:54'),
(487, '20149', 'epoxy', 1, 2, '2024-08-21 08:50:54'),
(488, '20150', 'epoxy', 1, 2, '2024-08-21 08:50:55'),
(489, '20151', 'epoxy', 1, 2, '2024-08-21 08:50:55'),
(490, '20152', 'epoxy', 1, 2, '2024-08-21 08:50:55'),
(491, '20153', 'epoxy', 1, 2, '2024-08-21 08:50:55'),
(492, '20154', 'epoxy', 1, 2, '2024-08-21 08:50:55'),
(493, '30000', 'kaarsen', 1, 2, '2024-08-21 08:50:55'),
(494, '30001', 'kaarsen', 1, 2, '2024-08-21 08:50:55'),
(495, '30002', 'kaarsen', 1, 2, '2024-08-21 08:50:55'),
(496, '30004', 'kaarsen', 1, 2, '2024-08-21 08:50:55'),
(497, '30005', 'kaarsen', 1, 2, '2024-08-21 08:50:55'),
(498, '30006', 'kaarsen', 1, 2, '2024-08-21 08:50:55'),
(499, '30007', 'kaarsen', 3, 2, '2024-08-21 08:50:55'),
(500, '30008', 'kaarsen', 1, 2, '2024-08-21 08:50:56'),
(501, '30003', 'kaarsen', 2, 2, '2024-08-21 08:50:56'),
(502, '30009', 'kaarsen', 0, 2, '2024-08-21 08:50:56'),
(503, '30010', 'kaarsen', 0, 2, '2024-08-21 08:50:56'),
(504, '30011', 'kaarsen', 0, 2, '2024-08-21 08:50:56'),
(505, '30012', 'kaarsen', 1, 2, '2024-08-21 08:50:56'),
(506, '30013', 'kaarsen', 1, 2, '2024-08-21 08:50:56'),
(507, '30014', 'kaarsen', 1, 2, '2024-08-21 08:50:57'),
(508, '30015', 'kaarsen', 1, 2, '2024-08-21 08:50:57'),
(509, '30016', 'kaarsen', 1, 2, '2024-08-21 08:50:57'),
(510, '30017', 'kaarsen', 1, 2, '2024-08-21 08:50:57'),
(511, '30018', 'kaarsen', 1, 2, '2024-08-21 08:50:57'),
(512, '30019', 'kaarsen', 1, 2, '2024-08-21 08:50:57'),
(513, '30020', 'kaarsen', 1, 2, '2024-08-21 08:50:57'),
(514, '30021', 'kaarsen', 1, 2, '2024-08-21 08:50:57'),
(515, '30022', 'kaarsen', 1, 2, '2024-08-21 08:50:57'),
(516, '30023', 'kaarsen', 1, 2, '2024-08-21 08:50:57'),
(517, '30024', 'kaarsen', 1, 2, '2024-08-21 08:50:57'),
(518, '10015', 'vers', 0, 2, '2024-08-21 08:50:58'),
(519, '10011', 'vers', 0, 2, '2024-08-21 08:50:58'),
(520, '10016', 'vers', 0, 2, '2024-08-21 08:50:58'),
(521, '10013', 'vers', 0, 2, '2024-08-21 08:50:58'),
(522, '10028', 'vers', 0, 2, '2024-08-21 08:50:58'),
(523, '10014', 'vers', 0, 2, '2024-08-21 08:50:58'),
(524, '1002282', 'vers', 0, 2, '2024-08-21 08:50:59'),
(525, '1002283', 'vers', 0, 2, '2024-08-21 08:50:59'),
(526, '100228', 'vers', 0, 2, '2024-08-21 08:50:59'),
(527, '1002281', 'vers', 0, 2, '2024-08-21 08:50:59'),
(528, '10030', 'vers', 0, 2, '2024-08-21 08:50:59');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `stock_history`
--

CREATE TABLE `stock_history` (
  `id` int NOT NULL,
  `product_sku` varchar(50) DEFAULT NULL,
  `category` varchar(50) DEFAULT NULL,
  `stock_change` int DEFAULT NULL,
  `reason` varchar(255) NOT NULL,
  `changed_by_user` int DEFAULT NULL,
  `change_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `stock_history`
--

INSERT INTO `stock_history` (`id`, `product_sku`, `category`, `stock_change`, `reason`, `changed_by_user`, `change_date`) VALUES
(1, '20110', 'epoxy', 1, 'Nieuw', 1, '2024-08-04 15:07:03'),
(2, '10030', 'vers', -2, 'Verkocht', 1, '2024-08-13 18:09:16'),
(3, '20079', 'epoxy', 27, 'Nieuw', 1, '2024-08-24 18:20:45'),
(5, '20158', 'epoxy', 1, 'Nieuw', 5, '2024-09-05 10:05:47'),
(6, '20162', 'epoxy', 1, 'Nieuw', 5, '2024-09-05 10:45:20'),
(7, '20160', 'epoxy', 1, 'Nieuw', 5, '2024-09-05 10:45:35'),
(8, '20167', 'epoxy', 1, 'Nieuw', 5, '2024-09-05 10:57:28'),
(9, '20166', 'epoxy', 1, 'Nieuw', 5, '2024-09-05 10:57:40'),
(10, '20159', 'epoxy', 1, 'Nieuw', 5, '2024-09-05 11:01:29'),
(11, '20168', 'epoxy', 1, 'Nieuw', 5, '2024-09-05 11:04:38'),
(12, '20170', 'epoxy', 1, 'Nieuw', 5, '2024-09-05 11:09:16'),
(13, '20169', 'epoxy', 1, 'Nieuw', 5, '2024-09-05 11:09:29'),
(14, '20045', 'epoxy', 1, 'Nieuw', 5, '2024-09-05 15:02:23'),
(15, '20038', 'epoxy', 1, 'Nieuw', 5, '2024-09-05 15:14:51'),
(16, '20113', 'epoxy', 1, 'Nieuw', 5, '2024-09-05 15:17:09'),
(17, '20155', 'terrazzo', 1, 'Nieuw', 5, '2024-09-11 12:44:04'),
(18, '20156', 'terrazzo', 1, 'Nieuw', 5, '2024-09-11 12:45:15'),
(19, '20172', 'terrazzo', 1, 'Nieuw', 1, '2024-09-12 15:23:04'),
(20, '20157', 'terrazzo', 4, 'Nieuw', 1, '2024-09-12 15:25:04'),
(21, '20173', 'epoxy', 1, 'Nieuw', 5, '2024-09-12 15:28:40'),
(22, '20174', 'terrazzo', 1, 'Nieuw', 5, '2024-09-12 15:30:24'),
(23, '20176', 'terrazzo', 1, 'Nieuw', 5, '2024-09-13 07:15:47'),
(24, '20177', 'epoxy', 2, 'Nieuw', 5, '2024-09-17 13:43:53'),
(25, '10031', 'vers', 0, 'Nieuw', 1, '2024-09-30 07:57:26'),
(26, '10032', 'vers', 9, 'Nieuw', 1, '2024-10-02 16:54:46'),
(27, '30029', 'kaars', 1, 'Nieuw', 5, '2024-10-21 07:36:31'),
(28, '20179', 'epoxy', 1, 'Nieuw', 6, '2024-10-21 07:40:34'),
(29, '20180', 'epoxy', 1, 'Nieuw', 6, '2024-10-21 07:44:25'),
(30, '20181', 'epoxy', 1, 'Nieuw', 6, '2024-10-21 07:46:58'),
(31, '20182', 'epoxy', 1, 'Nieuw', 6, '2024-10-21 07:49:28'),
(32, '20183', 'epoxy', 1, 'Nieuw', 6, '2024-10-21 07:52:16'),
(33, '20184', 'epoxy', 1, 'Nieuw', 6, '2024-10-21 07:55:13'),
(34, '20185', 'epoxy', 1, 'Nieuw', 6, '2024-10-21 08:15:43'),
(35, '20186', 'epoxy', 1, 'Nieuw', 6, '2024-10-21 08:18:39'),
(36, '20187', 'epoxy', 1, 'Nieuw', 6, '2024-10-21 08:50:23'),
(37, '20081', 'epoxy', 1, 'Nieuw', 5, '2024-10-21 09:27:02'),
(38, '20188', 'epoxy', 9, 'Nieuw', 6, '2024-10-30 12:30:24'),
(39, '20151', 'epoxy', -1, 'Verkocht', 1, '2024-10-31 10:31:03'),
(40, '20189', 'epoxy', 1, 'Nieuw', 6, '2024-11-01 09:33:22'),
(41, '20190', 'epoxy', 1, 'Nieuw', 6, '2024-11-01 16:34:07'),
(42, '20191', 'terrazzo', 1, 'Nieuw', 6, '2024-11-01 16:49:44'),
(43, '20192', 'terrazzo', 1, 'Nieuw', 6, '2024-11-02 09:25:29'),
(44, '20193', 'terrazzo', 1, 'Nieuw', 6, '2024-11-02 10:01:33'),
(45, '20194', 'terrazzo', 1, 'Nieuw', 6, '2024-11-02 10:17:31'),
(46, '20195', 'terrazzo', 1, 'Nieuw', 6, '2024-11-02 10:31:16'),
(47, '30030', 'kaars', 1, 'Nieuw', 5, '2024-11-08 10:15:23'),
(48, '30031', 'kaars', 1, 'Nieuw', 5, '2024-11-09 11:51:54'),
(49, '20196', 'epoxy', 1, 'Nieuw', 6, '2024-11-29 15:43:18'),
(50, '20197', 'epoxy', 1, 'Nieuw', 6, '2024-11-30 15:17:27'),
(51, '20198', 'epoxy', 1, 'Nieuw', 6, '2024-11-30 15:30:02'),
(52, '20199', 'epoxy', 1, 'Nieuw', 6, '2024-11-30 15:33:23'),
(53, '20200', 'terrazzo', 1, 'Nieuw', 6, '2024-11-30 16:19:48'),
(54, '20201', 'terrazzo', 1, 'Nieuw', 6, '2024-11-30 16:25:39'),
(55, '20141', 'epoxy', 1, 'Nieuw', 5, '2024-11-30 17:23:42'),
(56, '20038', 'epoxy', 1, 'Nieuw', 5, '2024-11-30 17:27:11'),
(57, '20035', 'epoxy', 1, 'Nieuw', 5, '2024-11-30 17:27:23'),
(58, '20037', 'epoxy', 1, 'Nieuw', 5, '2024-11-30 17:27:34'),
(59, '30029', 'epoxy', 3, 'Nieuw', 5, '2024-12-01 10:32:19'),
(60, '20202', 'terrazzo', 1, 'Nieuw', 6, '2024-12-06 14:00:57');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `vers_products`
--

CREATE TABLE `vers_products` (
  `id` int NOT NULL,
  `sku` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `product_image` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `product_description` mediumtext COLLATE utf8mb4_general_ci,
  `amount_grams` decimal(10,3) NOT NULL,
  `unit` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `price_per_unit` decimal(10,2) NOT NULL,
  `extra_parts_price` decimal(10,2) DEFAULT NULL,
  `margin` decimal(5,2) DEFAULT NULL,
  `hours_worked` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `created_by_user` int DEFAULT NULL,
  `company_cost_per_product` decimal(10,2) DEFAULT '94.90',
  `sold_in_branches` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `vat_percentage` decimal(5,2) DEFAULT '21.00',
  `total_product_price` decimal(10,2) DEFAULT NULL,
  `created_on` date DEFAULT NULL,
  `shipping_method` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `category` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `stock` varchar(255) COLLATE utf8mb4_general_ci DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `vers_products`
--

INSERT INTO `vers_products` (`id`, `sku`, `title`, `product_image`, `product_description`, `amount_grams`, `unit`, `price_per_unit`, `extra_parts_price`, `margin`, `hours_worked`, `created_by_user`, `company_cost_per_product`, `sold_in_branches`, `vat_percentage`, `total_product_price`, `created_on`, `shipping_method`, `category`, `stock`) VALUES
(1, '10015', 'Courgette', 'large_80c08fb0-e30f-4fdd-8977-d41bd4ebd70c.jpg', NULL, 0.000, 'stuk', 0.14, 0.00, 40.00, '00:30', 2, 0.58, '2', 6.00, 1.67, '2024-03-23', 'kleine_doos', 'vers', '0'),
(2, '10011', 'Tomaten', '20220117_085425239_iOS.jpg', NULL, 1.000, 'kg', 1.42, 0.00, 40.00, '00:30', 2, 0.58, '2', 6.00, 3.78, '2024-03-23', 'kleine_doos', 'vers', '0'),
(3, '10016', 'Sla', 'OIP.jpeg', NULL, 0.000, 'stuk', 0.14, 0.00, 40.00, '00:30', 2, 0.58, '2', 6.00, 1.67, '2024-03-23', 'kleine_doos', 'vers', '0'),
(4, '10013', 'Komkommer', 'graines-de-concombre.jpg', NULL, 0.400, 'stuk', 0.14, 0.00, 40.00, '00:30', 2, 0.58, '2', 6.00, 1.75, '2024-03-23', 'kleine_doos', 'vers', '0'),
(5, '10028', 'Rababer', 'rabarber-305.jpg', NULL, 1.000, 'kg', 1.13, 0.00, 40.00, '00:30', 2, 0.58, '2', 6.00, 3.35, '2024-03-23', 'kleine_doos', 'vers', '0'),
(6, '10014', 'Wortel', 'wortel.jpg', NULL, 1.000, 'kg', 0.71, 0.00, 40.00, '00:30', 2, 0.58, '2', 6.00, 2.72, '2024-03-23', 'kleine_doos', 'vers', '0'),
(7, '1002282', 'Gele paprika', 'R.jpg', NULL, 0.150, 'stuk', 0.14, 0.00, 40.00, '00:30', 2, 0.58, '2', 6.00, 1.70, '2024-03-23', 'kleine_doos', 'vers', '0'),
(8, '1002283', 'Oranje paprika', 'R.jpg', NULL, 0.150, 'stuk', 0.14, 0.00, 40.00, '00:30', 2, 0.58, '2', 6.00, 1.70, '2024-03-23', 'kleine_doos', 'vers', '0'),
(9, '100228', 'Rood paprika', 'R.jpg', NULL, 0.150, 'stuk', 0.14, 0.00, 40.00, '00:30', 2, 0.58, '2', 6.00, 1.70, '2024-03-23', 'kleine_doos', 'vers', '0'),
(10, '1002281', 'Groen paprika', 'R.jpg', NULL, 0.150, 'stuk', 0.14, 0.00, 40.00, '00:30', 2, 0.58, '2', 6.00, 1.70, '2024-03-23', 'kleine_doos', 'vers', '0'),
(26, '10030', 'Rabarber confituur', '../product_img/IMG_1865 2024-06-11 09_10_55.jpg', NULL, 1.000, 'stuk', 1.04, 0.00, 40.00, '00:30', 2, 0.58, '2', 6.00, 3.21, '2024-08-09', 'klein_doos', 'vers', '0'),
(27, '10031', 'prei', '../product_img/بصل-اخضر-1.jpg', NULL, 0.800, 'kg', 1.25, 0.00, 40.00, '00:00', 2, 0.68, '2', 6.00, 2.49, '2024-09-30', 'klein_doos', 'vers', '0'),
(28, '10064', 'Prei-corugettesoep', 'IMG_2633 2024-10-02 18_52_34.jpg', '', 9.000, 'stuk', 0.85, 0.00, 40.00, '00:10', 2, 0.68, '2', 6.00, 12.58, '2024-10-02', 'klein_doos', 'vers', '13');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `winkel_schappen`
--

CREATE TABLE `winkel_schappen` (
  `id` int NOT NULL,
  `naam` varchar(255) NOT NULL,
  `locatie` varchar(255) NOT NULL,
  `breedte` float NOT NULL,
  `hoogte` float NOT NULL,
  `aantal_planken` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `cat_magazijn`
--
ALTER TABLE `cat_magazijn`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `cat_product`
--
ALTER TABLE `cat_product`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `epoxy_products`
--
ALTER TABLE `epoxy_products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by_user` (`created_by_user`);

--
-- Indexen voor tabel `inkoop_products`
--
ALTER TABLE `inkoop_products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by_user` (`created_by_user`);

--
-- Indexen voor tabel `kaarsen_products`
--
ALTER TABLE `kaarsen_products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by_user` (`created_by_user`);

--
-- Indexen voor tabel `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `order_code` (`order_code`);

--
-- Indexen voor tabel `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexen voor tabel `product_changes`
--
ALTER TABLE `product_changes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `changed_by_user` (`changed_by_user`);

--
-- Indexen voor tabel `product_schap`
--
ALTER TABLE `product_schap`
  ADD PRIMARY KEY (`id`),
  ADD KEY `schap_id` (`schap_id`),
  ADD KEY `epoxy_product_id` (`epoxy_product_id`),
  ADD KEY `kaarsen_product_id` (`kaarsen_product_id`),
  ADD KEY `vers_product_id` (`vers_product_id`);

--
-- Indexen voor tabel `stock_count`
--
ALTER TABLE `stock_count`
  ADD PRIMARY KEY (`id`),
  ADD KEY `counted_by_user` (`counted_by_user`);

--
-- Indexen voor tabel `stock_history`
--
ALTER TABLE `stock_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `changed_by_user` (`changed_by_user`);

--
-- Indexen voor tabel `vers_products`
--
ALTER TABLE `vers_products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by_user` (`created_by_user`);

--
-- Indexen voor tabel `winkel_schappen`
--
ALTER TABLE `winkel_schappen`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT voor geëxporteerde tabellen
--

--
-- AUTO_INCREMENT voor een tabel `cat_magazijn`
--
ALTER TABLE `cat_magazijn`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `cat_product`
--
ALTER TABLE `cat_product`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `epoxy_products`
--
ALTER TABLE `epoxy_products`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=273;

--
-- AUTO_INCREMENT voor een tabel `inkoop_products`
--
ALTER TABLE `inkoop_products`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `kaarsen_products`
--
ALTER TABLE `kaarsen_products`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT voor een tabel `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `product_changes`
--
ALTER TABLE `product_changes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=465;

--
-- AUTO_INCREMENT voor een tabel `product_schap`
--
ALTER TABLE `product_schap`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `stock_count`
--
ALTER TABLE `stock_count`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=529;

--
-- AUTO_INCREMENT voor een tabel `stock_history`
--
ALTER TABLE `stock_history`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT voor een tabel `vers_products`
--
ALTER TABLE `vers_products`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT voor een tabel `winkel_schappen`
--
ALTER TABLE `winkel_schappen`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- Beperkingen voor geëxporteerde tabellen
--

--
-- Beperkingen voor tabel `epoxy_products`
--
ALTER TABLE `epoxy_products`
  ADD CONSTRAINT `epoxy_products_ibfk_1` FOREIGN KEY (`created_by_user`) REFERENCES `company_admin`.`admins_workforce` (`admin_id`);

--
-- Beperkingen voor tabel `kaarsen_products`
--
ALTER TABLE `kaarsen_products`
  ADD CONSTRAINT `kaarsen_products_ibfk_1` FOREIGN KEY (`created_by_user`) REFERENCES `company_admin`.`admins_workforce` (`admin_id`);

--
-- Beperkingen voor tabel `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`);

--
-- Beperkingen voor tabel `product_changes`
--
ALTER TABLE `product_changes`
  ADD CONSTRAINT `product_changes_ibfk_1` FOREIGN KEY (`changed_by_user`) REFERENCES `company_admin`.`admins_workforce` (`admin_id`);

--
-- Beperkingen voor tabel `product_schap`
--
ALTER TABLE `product_schap`
  ADD CONSTRAINT `product_schap_ibfk_1` FOREIGN KEY (`schap_id`) REFERENCES `winkel_schappen` (`id`),
  ADD CONSTRAINT `product_schap_ibfk_2` FOREIGN KEY (`epoxy_product_id`) REFERENCES `epoxy_products` (`id`),
  ADD CONSTRAINT `product_schap_ibfk_3` FOREIGN KEY (`kaarsen_product_id`) REFERENCES `kaarsen_products` (`id`),
  ADD CONSTRAINT `product_schap_ibfk_4` FOREIGN KEY (`vers_product_id`) REFERENCES `vers_products` (`id`);

--
-- Beperkingen voor tabel `stock_count`
--
ALTER TABLE `stock_count`
  ADD CONSTRAINT `stock_count_ibfk_1` FOREIGN KEY (`counted_by_user`) REFERENCES `company_admin`.`admins_workforce` (`admin_id`);

--
-- Beperkingen voor tabel `stock_history`
--
ALTER TABLE `stock_history`
  ADD CONSTRAINT `stock_history_ibfk_1` FOREIGN KEY (`changed_by_user`) REFERENCES `company_admin`.`admins_workforce` (`admin_id`);

--
-- Beperkingen voor tabel `vers_products`
--
ALTER TABLE `vers_products`
  ADD CONSTRAINT `vers_products_ibfk_1` FOREIGN KEY (`created_by_user`) REFERENCES `company_admin`.`admins_workforce` (`admin_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
