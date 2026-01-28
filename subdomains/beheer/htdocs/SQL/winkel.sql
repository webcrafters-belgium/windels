-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Gegenereerd op: 15 dec 2024 om 11:08
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
-- Database: `winkel`
--

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
(68, '20085', 'Roze/Oranje Ovalen Halsketting', '20230715_140226.jpg', '<br />\r\n<b>Deprecated</b>:  htmlspecialchars(): Passing null to parameter #1 ($string) of type string is deprecated in <b>C:\\Abyss Web Server\\htdocs\\winkel\\edit_product.php</b> on line <b>329</b><br />\r\n', 7, 0.130, 0.00, 40.00, '01:00', 6, 2.77, '3', 21.00, 10.47, '2024-03-05', 'briefpakket', 'epoxy', 1, 2.50),
(69, '20008', 'sieraad 11', '20230715_140420.jpg', NULL, 14, 0.130, 0.00, 40.00, '01:00', 6, 2.77, '3', 21.00, 12.01, '2024-03-05', 'briefpakket', 'epoxy', 1, 2.50),
(70, '20009', 'sieraad 15', '20230715_140644.jpg', NULL, 7, 0.130, 0.00, 40.00, '01:00', 6, 2.77, '3', 21.00, 10.47, '2024-03-05', 'briefpakket', 'epoxy', 0, 2.50),
(71, '20010', 'oorbellen ruitvormig', '20230715_140739.jpg', NULL, 3, 0.130, 0.28, 40.00, '00:30', 6, 2.77, '3', 21.00, 7.94, '2024-03-05', 'briefpakket', 'epoxy', 0, 2.50),
(72, '20011', 'Halsketting rechthoekig met halve diamant + ovalen oorbellen', '20230715_140814.jpg', NULL, 28, 0.130, 0.00, 40.00, '01:00', 6, 2.77, '3', 21.00, 15.09, '2024-03-05', 'briefpakket', 'epoxy', 1, 2.50),
(73, '20064', 'sieraad 20', '20230715_141019.jpg', NULL, 6, 0.130, 0.00, 40.00, '01:00', 6, 2.77, '3', 21.00, 10.25, '2024-03-05', 'briefpakket', 'epoxy', 2, 2.50),
(74, '20069', 'Sieradensetje van kleine hartjes', '20230715_141119.jpg', '<br />\r\n<b>Deprecated</b>:  htmlspecialchars(): Passing null to parameter #1 ($string) of type string is deprecated in <b>C:\\Abyss Web Server\\htdocs\\winkel\\edit_product.php</b> on line <b>329</b><br />\r\n', 6, 0.130, 0.00, 40.00, '01:00', 6, 2.77, '3', 21.00, 10.25, '2024-03-05', 'briefpakket', 'epoxy', 0, 2.50),
(75, '20087', 'sieraad 22', '20230715_141232.jpg', NULL, 11, 0.130, 0.00, 40.00, '01:00', 6, 2.77, '3', 21.00, 11.35, '2024-03-05', 'briefpakket', 'epoxy', 0, 2.50),
(76, '20086', 'Groen hartvormig halsketting', '20230715_140041.jpg', '<br />\r\n<b>Deprecated</b>:  htmlspecialchars(): Passing null to parameter #1 ($string) of type string is deprecated in <b>C:\\Abyss Web Server\\htdocs\\winkel\\edit_product.php</b> on line <b>329</b><br />\r\n', 5, 0.130, 0.00, 40.00, '01:00', 6, 2.77, '3', 21.00, 10.03, '2024-03-05', 'briefpakket', 'epoxy', 1, 2.50),
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
(115, '20055', 'Elegante Epoxy Halskettingen', 'SSA53087.JPG', '<br />\r\n<b>Deprecated</b>:  htmlspecialchars(): Passing null to parameter #1 ($string) of type string is deprecated in <b>C:\\Abyss Web Server\\htdocs\\winkel\\edit_product.php</b> on line <b>329</b><br />\r\n', 8, 0.130, 0.00, 40.00, '01:00', 6, 2.77, '3', 21.00, 10.69, '2024-01-01', 'briefpakket', 'epoxy', 2, 2.50),
(116, '20056', 'Ovalen glinsterende halsketting', '20240217_111437.jpg', '<br />\r\n<b>Deprecated</b>:  htmlspecialchars(): Passing null to parameter #1 ($string) of type string is deprecated in <b>C:\\Abyss Web Server\\htdocs\\winkel\\edit_product.php</b> on line <b>329</b><br />\r\n', 7, 0.130, 0.00, 40.00, '01:00', 6, 2.77, '3', 21.00, 10.47, '2024-01-01', 'briefpakket', 'epoxy', 1, 2.50),
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
(131, '20073', 'halsketting lila bloem', '20240601_150052.jpg', '<br />\r\n<b>Deprecated</b>:  htmlspecialchars(): Passing null to parameter #1 ($string) of type string is deprecated in <b>C:\\Abyss Web Server\\htdocs\\winkel\\edit_product.php</b> on line <b>329</b><br />\r\n', 4, 0.130, 0.00, 40.00, '01:00', 6, 2.77, '3', 21.00, 9.81, '2024-01-01', 'briefpakket', 'epoxy', 1, 2.50),
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
(160, '20112', 'Epoxyhars kerstornamenten', '20231202_154218.jpg', '<br />\r\n<b>Deprecated</b>:  htmlspecialchars(): Passing null to parameter #1 ($string) of type string is deprecated in <b>C:\\Abyss Web Server\\htdocs\\winkel\\edit_product.php</b> on line <b>329</b><br />\r\n', 58, 0.020, 0.00, 40.00, '00:30', 6, 2.77, '3', 21.00, 8.77, '2024-01-01', 'kleine_doos', 'epoxy', 4, 2.50),
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
(194, '20098', '( 6 onderleggers )6 flessenhouder + onderleggers ', '20240131_144303.jpg', '<br />\r\n<b>Deprecated</b>:  htmlspecialchars(): Passing null to parameter #1 ($string) of type string is deprecated in <b>C:\\Abyss Web Server\\htdocs\\winkel\\edit_product.php</b> on line <b>329</b><br />\r\n', 734, 0.020, 0.00, 40.00, '01:00', 6, 2.77, '3', 21.00, 38.03, '2024-03-18', 'kleine_doos', 'epoxy', 6, 2.50),
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
(252, '20182', 'Glitter kerstblok', '20182.jpg', '<br />\r\n<b>Deprecated</b>:  htmlspecialchars(): Passing null to parameter #1 ($string) of type string is deprecated in <b>C:\\Abyss Web Server\\htdocs\\winkel\\edit_product.php</b> on line <b>329</b><br />\r\n', 124, 0.016, 0.00, 40.00, '00:30', 6, 2.77, '1,2,3', 21.00, 10.17, '2024-10-21', 'klein_doos', 'epoxy', 1, 2.50),
(253, '20183', 'Epoxy decoratieblok met blauwe rozen en harten', '20241020_104842.jpg', '<br />\r\n<b>Deprecated</b>:  htmlspecialchars(): Passing null to parameter #1 ($string) of type string is deprecated in <b>C:\\Abyss Web Server\\htdocs\\winkel\\edit_product.php</b> on line <b>329</b><br />\r\n', 94, 0.016, 0.00, 40.00, '00:30', 6, 2.77, '1,2,3', 21.00, 9.36, '2024-10-21', 'klein_doos', 'epoxy', 1, 2.50),
(254, '20184', 'hartje', '20241020_104755.jpg', NULL, 42, 0.016, 0.00, 40.00, '00:30', 6, 2.77, '1,2,3', 21.00, 7.95, '2024-10-21', 'klein_doos', 'epoxy', 1, 2.50),
(255, '20185', 'Kerstdecoratieblok met glitter en feestelijke illustraties', '20241021_100448.jpg', '<br />\r\n<b>Deprecated</b>:  htmlspecialchars(): Passing null to parameter #1 ($string) of type string is deprecated in <b>C:\\Abyss Web Server\\htdocs\\winkel\\edit_product.php</b> on line <b>329</b><br />\r\n', 81, 0.016, 0.00, 40.00, '00:30', 6, 2.77, '1,2,3', 21.00, 9.01, '2024-10-21', 'klein_doos', 'epoxy', 1, 2.50),
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
(272, '20202', 'Minimalistische kaarsenhouder met huisjesdesign', '20241205_145936.jpg', 'afmetingen L: 19 cm B: 105 cm H: 125 cm', 462, 0.009, 1.28, 40.00, '00:20', 6, 2.77, '2', 21.00, 15.32, '2024-12-06', 'klein_doos', 'terrazzo', 1, 2.50),
(273, '20203', 'Feestelijk verpakt cadeau pakket voor badkamer', 'IMG-20241208-WA0015.jpeg', 'luxe zeeppompje , onderzetter , schelp, zeepje , handdoek  , washandje', 988, 0.009, 2.36, 40.00, '01:00', 6, 2.77, '2', 21.00, 32.22, '2024-12-10', 'klein_doos', 'terrazzo', 1, 5.00),
(274, '20204', 'Epoxyhars sleutelhangerbord in marmerlook', '20204.jpg', 'L 20,5 cm B 9 cm met sleutelhanger 13 cm D 7 mm', 87, 0.016, 1.81, 40.00, '00:45', 6, 2.77, '2', 21.00, 13.29, '2024-12-11', 'klein_doos', 'epoxy', 1, 2.50);

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

--
-- Gegevens worden geëxporteerd voor tabel `inkoop_products`
--

INSERT INTO `inkoop_products` (`id`, `sku`, `title`, `product_image`, `product_description`, `purchase_price`, `extra_parts_price`, `margin`, `hours_worked`, `created_by_user`, `company_cost_per_product`, `sold_in_branches`, `vat_percentage`, `total_product_price`, `created_on`, `shipping_method`, `category`, `stock`) VALUES
(1, '40000', 'Panasonic 4x AA Batterijen', '40000.jpeg', 'Panasonic 4x AA Batterijen - Krachtige Langdurige Energiebron', 0.990, 0.00, 40.00, '0', 1, 0.00, '2', 21.00, 1.39, '2024-12-14', 'klein_doos', 'inkoop', 12),
(2, '40001', 'Panasonic 4x AAA Batterijen ', '../product_img/40001.jpeg', 'Panasonic 4x AA Batterijen - Krachtige Langdurige Energiebron', 0.990, 0.00, 40.00, '0', 1, 0.00, '2', 21.00, 1.39, '2024-12-14', 'briefpakket', 'inkoop', 12);

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
(464, 272, 'terrazzo', 'total_product_price', '76.54', '13.147698666667', 5, '2024-12-06 14:01:20'),
(465, 272, 'terrazzo', 'extra_parts_price', '0.00', '1.28', 5, '2024-12-10 09:06:44'),
(466, 272, 'terrazzo', 'total_product_price', '13.15', '15.316018666667', 5, '2024-12-10 09:06:44'),
(467, 273, 'terrazzo', 'hours_worked', '00:45', '01:00', 5, '2024-12-10 10:08:25'),
(468, 273, 'terrazzo', 'total_product_price', '30.11', '32.223268', 5, '2024-12-10 10:08:25'),
(469, 273, 'terrazzo', 'extra_parts_price', '2.36', '0', 5, '2024-12-10 10:09:11'),
(470, 273, 'terrazzo', 'total_product_price', '32.22', '28.225428', 5, '2024-12-10 10:09:11'),
(471, 273, 'terrazzo', 'extra_parts_price', '0.00', '2.36', 5, '2024-12-10 10:09:40'),
(472, 273, 'terrazzo', 'total_product_price', '28.23', '32.223268', 5, '2024-12-10 10:09:40'),
(473, 273, 'terrazzo', 'title', 'Feestelijk verpakt cadeaupakket met kerstthema', 'Feestelijk verpakt cadeau pakket voor badkamer', 5, '2024-12-10 10:10:36'),
(474, 273, 'terrazzo', 'total_product_price', '32.22', '32.223268', 5, '2024-12-10 10:10:36'),
(475, 160, 'epoxy', 'title', 'kerstballen ( schijven )', 'Epoxyhars kerstornamenten', 5, '2024-12-10 12:14:15'),
(476, 160, 'epoxy', 'total_product_price', '8.77', '8.77492', 5, '2024-12-10 12:14:16'),
(477, 274, 'epoxy', 'extra_parts_price', '1.85', '1.81', 5, '2024-12-11 12:26:02'),
(478, 274, 'epoxy', 'total_product_price', '13.36', '13.292818', 5, '2024-12-11 12:26:02'),
(479, 274, 'epoxy', 'product_image', '20241211_130523.jpg', 'sku 20204.jpg', 5, '2024-12-13 13:16:40'),
(480, 274, 'epoxy', 'total_product_price', '13.29', '13.292818', 5, '2024-12-13 13:16:40'),
(481, 274, 'epoxy', 'product_image', 'sku 20204.jpg', '20204.jpg', 5, '2024-12-13 13:44:07'),
(482, 274, 'epoxy', 'total_product_price', '13.29', '13.292818', 5, '2024-12-13 13:44:08'),
(483, 194, 'epoxy', 'title', '( 6 onderleggers )5 flessenhouder + onderleggers ', '( 6 onderleggers )6 flessenhouder + onderleggers ', 5, '2024-12-13 14:39:22'),
(484, 194, 'epoxy', 'amount_grams', '444', '734', 5, '2024-12-13 14:39:22'),
(485, 194, 'epoxy', 'total_product_price', '23.97', '38.0303', 5, '2024-12-13 14:39:22'),
(486, 76, 'epoxy', 'title', 'sieraad 7', 'Groen hartvormig halsketting', 5, '2024-12-14 09:59:06'),
(487, 76, 'epoxy', 'total_product_price', '10.03', '10.02848', 5, '2024-12-14 09:59:07'),
(488, 68, 'epoxy', 'title', 'sieraad 10', 'Roze/Oranje Ovalen Halsketting', 5, '2024-12-14 10:04:14'),
(489, 68, 'epoxy', 'total_product_price', '10.47', '10.46892', 5, '2024-12-14 10:04:14'),
(490, 115, 'epoxy', 'title', 'rode halsketting', 'Elegante Epoxy Halskettingen', 5, '2024-12-14 10:36:55'),
(491, 115, 'epoxy', 'total_product_price', '10.69', '10.68914', 5, '2024-12-14 10:36:55'),
(492, 116, 'epoxy', 'title', 'Sieraad rond met bloem', 'Ovalen glinsterende halsketting', 5, '2024-12-14 10:42:52'),
(493, 116, 'epoxy', 'total_product_price', '10.47', '10.46892', 5, '2024-12-14 10:42:52'),
(494, 131, 'epoxy', 'title', 'sieraad lila bloem', 'halsketting lila bloem', 5, '2024-12-14 10:48:51'),
(495, 131, 'epoxy', 'total_product_price', '9.81', '9.80826', 5, '2024-12-14 10:48:51'),
(496, 74, 'epoxy', 'title', 'sieraad 21', 'Sieradensetje van kleine hartjes', 5, '2024-12-14 11:59:31'),
(497, 74, 'epoxy', 'total_product_price', '10.25', '10.2487', 5, '2024-12-14 11:59:31'),
(498, 252, 'epoxy', 'product_image', '../product_img/no_picture.jpg', '20182.jpg', 5, '2024-12-14 13:03:07'),
(499, 252, 'epoxy', 'total_product_price', '10.17', '10.170776', 5, '2024-12-14 13:03:08'),
(500, 255, 'epoxy', 'title', 'glitterblok nieuwjaar', 'Kerstdecoratieblok met glitter en feestelijke illustraties', 5, '2024-12-14 15:02:42'),
(501, 255, 'epoxy', 'total_product_price', '9.01', '9.005304', 5, '2024-12-14 15:02:42'),
(502, 253, 'epoxy', 'title', 'glitterblok bloemen', 'Epoxy decoratieblok met blauwe rozen en harten', 5, '2024-12-14 15:10:09'),
(503, 253, 'epoxy', 'total_product_price', '9.36', '9.357656', 5, '2024-12-14 15:10:09');

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
(60, '20202', 'terrazzo', 1, 'Nieuw', 6, '2024-12-06 14:00:57'),
(61, '20203', 'terrazzo', 1, 'Nieuw', 6, '2024-12-10 10:07:19'),
(62, '20204', 'epoxy', 1, 'Nieuw', 6, '2024-12-11 12:24:21'),
(64, '40000', 'inkoop', 12, 'Nieuw', 1, '2024-12-14 18:25:43'),
(65, '40001', 'inkoop', 12, 'Nieuw', 1, '2024-12-14 19:07:36');

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
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=275;

--
-- AUTO_INCREMENT voor een tabel `inkoop_products`
--
ALTER TABLE `inkoop_products`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

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
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=504;

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
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

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
