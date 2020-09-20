-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Erstellungszeit: 20. Sep 2020 um 22:41
-- Server-Version: 8.0.18
-- PHP-Version: 5.6.40

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `lcm_xm`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cmsarticle`
--

DROP TABLE IF EXISTS `cmsarticle`;
CREATE TABLE IF NOT EXISTS `cmsarticle` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `articleGroupId` int(11) NOT NULL,
  `order` int(11) NOT NULL,
  `publishDate` datetime NOT NULL,
  `active` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `cmsarticle`
--

INSERT INTO `cmsarticle` (`id`, `articleGroupId`, `order`, `publishDate`, `active`) VALUES
(1, 1, 1, '0000-00-00 00:00:00', 1),
(2, 1, 1, '0000-00-00 00:00:00', 1),
(3, 1, 2, '0000-00-00 00:00:00', 1),
(4, 5, 1, '2020-09-04 22:02:46', 1),
(5, 5, 2, '2020-09-04 23:02:11', 1);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cmsarticlefile`
--

DROP TABLE IF EXISTS `cmsarticlefile`;
CREATE TABLE IF NOT EXISTS `cmsarticlefile` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `articleId` int(11) NOT NULL,
  `order` int(11) NOT NULL,
  `filename` text NOT NULL,
  `frontImage` int(11) NOT NULL,
  `active` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `cmsarticlefile`
--

INSERT INTO `cmsarticlefile` (`id`, `articleId`, `order`, `filename`, `frontImage`, `active`) VALUES
(1, 1, 1, 'vision.jpg', 1, 1),
(2, 2, 1, 'working-hours.jpg', 1, 1),
(3, 3, 1, 'history.jpg', 1, 1),
(4, 4, 1, 'natjecanje1.jpg', 1, 1),
(5, 5, 1, 'natjecanje2.jpg', 1, 1),
(6, 6, 1, 'telephone.jpg', 1, 1);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cmsarticlefiletext`
--

DROP TABLE IF EXISTS `cmsarticlefiletext`;
CREATE TABLE IF NOT EXISTS `cmsarticlefiletext` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `articleFileId` int(11) NOT NULL,
  `languageId` int(11) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `cmsarticlefiletext`
--

INSERT INTO `cmsarticlefiletext` (`id`, `articleFileId`, `languageId`, `description`) VALUES
(1, 4, 1, '<p>Jedan od na&scaron;ih natjecatelja na zadatku</p>');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cmsarticlegroup`
--

DROP TABLE IF EXISTS `cmsarticlegroup`;
CREATE TABLE IF NOT EXISTS `cmsarticlegroup` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parentId` int(11) NOT NULL,
  `order` int(11) NOT NULL,
  `active` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `cmsarticlegroup`
--

INSERT INTO `cmsarticlegroup` (`id`, `parentId`, `order`, `active`) VALUES
(1, 0, 1, 1),
(5, 0, 2, 1),
(7, 0, 3, 1);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cmsarticlegrouptext`
--

DROP TABLE IF EXISTS `cmsarticlegrouptext`;
CREATE TABLE IF NOT EXISTS `cmsarticlegrouptext` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `articleGroupId` int(11) NOT NULL,
  `languageId` int(11) NOT NULL,
  `title` text NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `cmsarticlegrouptext`
--

INSERT INTO `cmsarticlegrouptext` (`id`, `articleGroupId`, `languageId`, `title`, `description`) VALUES
(1, 1, 1, 'O nama', '<p><span style=\"font-size: xx-large; color: #ff0000;\">Dobrodo&scaron;li na na&scaron;e web stranice</span></p>\r\n<p>Saznajte čime se bavimo i za&scaron;to smo vodeći stručnjaci u nabavci fitness opreme!</p>\r\n<p><span style=\"color: #ffffff; background-color: #ff0000;\">&nbsp; &nbsp; &nbsp;Brže, vi&scaron;e jače&nbsp; &nbsp; &nbsp;</span><strong><br /></strong></p>'),
(2, 2, 1, 'Naši proizvodi', '<p><span style=\"font-size: xx-large; color: #ff0000;\">Fitness oprema, bicikli, ekstremni sportovi...</span></p>\r\n<p>Na&scaron;i proizvodi obuhvaćaju opremu za profesionalne i kućne teretane renomirnaih svjetskih brandova, te mnogo vi&scaron;e...</p>'),
(5, 1, 2, 'About us', '<p><span style=\"font-size: xx-large; color: #ff0000;\">Welcome to our web site</span></p>\r\n<p>Learn why we are leading experts in fitness equipment sales and procurement!</p>\r\n<p><span style=\"color: #ffffff; background-color: #ff0000;\">&nbsp; &nbsp; &nbsp;Faster, more, stronger&nbsp; &nbsp; &nbsp;</span></p>'),
(6, 2, 2, 'Our products', '<p><span style=\"font-size: xx-large; color: #ff0000;\">Fitness equipment, bycicles, extreme sports...</span></p>\r\n<p>Our products are top of the offer of world&#039;s most reknown brands, they are intended for professional use as well as home gyms and much more.</p>'),
(7, 5, 1, 'Natjecanja', '<p>Ovdje možete pročitati o natjecanjima koja sponzoriramo opremom iz na&scaron;eg asortimana</p>'),
(9, 5, 2, 'Contests', '<p>Here you can read up about various sport contests that we sponsor.</p>'),
(10, 7, 1, 'Kontakt', '<p>Ovdje možete naći na&scaron;e kontakt podatke te podatke na&scaron;eg osoblja zaduženog za komunikaciju s korisnicima i poslovnim partnerima.</p>\r\n<p>&nbsp;</p>\r\n<p><span style=\"background-color: #ff0000; color: #ffffff;\"><strong>&nbsp; &nbsp; &nbsp;Varaždin, Kratka ulica 13&nbsp; &nbsp; &nbsp;</strong></span></p>\r\n<p>Tel.: 042 000 000</p>\r\n<p>Mob.: 888 000 000</p>\r\n<p>Email: varazdin (at) xerces-max.com</p>\r\n<p>&nbsp;</p>\r\n<p><span style=\"background-color: #ff0000; color: #ffffff;\"><strong><strong>&nbsp; &nbsp; Čakovec, Glavni trg 1&nbsp; &nbsp; &nbsp;</strong></strong></span></p>\r\n<p>Tel.: 040 000 000</p>\r\n<p>Mob.: 888 100 000</p>\r\n<p>Email: cakovec (at) xerces-max.com</p>'),
(11, 7, 2, 'Contact', '<p><strong>Here you can find contact information of our shops and our sales personel.</strong></p>\r\n<p>&nbsp;</p>\r\n<p><span style=\"background-color: #ff0000; color: #ffffff;\"><strong>&nbsp; &nbsp; &nbsp;Varaždin, Kratka ulica 13&nbsp; &nbsp; &nbsp;</strong></span></p>\r\n<p>Tel.: 042 000 000</p>\r\n<p>Mob.: 888 000 000</p>\r\n<p>Email: varazdin (at) xerces-max.com</p>\r\n<p>&nbsp;</p>\r\n<p><span style=\"color: #ffffff; background-color: #ff0000;\"><strong><strong>&nbsp; &nbsp; Čakovec, Glavni trg 1&nbsp; &nbsp; &nbsp;</strong></strong></span></p>\r\n<p>Tel.: 040 000 000</p>\r\n<p>Mob.: 888 100 000</p>\r\n<p>Email: cakovec (at) xerces-max.com</p>');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cmsarticlelink`
--

DROP TABLE IF EXISTS `cmsarticlelink`;
CREATE TABLE IF NOT EXISTS `cmsarticlelink` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `articleId` int(11) NOT NULL,
  `linkedArticleId` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cmsarticletext`
--

DROP TABLE IF EXISTS `cmsarticletext`;
CREATE TABLE IF NOT EXISTS `cmsarticletext` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `articleId` int(11) NOT NULL,
  `languageId` int(11) NOT NULL,
  `title` text NOT NULL,
  `description` text NOT NULL,
  `text` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `cmsarticletext`
--

INSERT INTO `cmsarticletext` (`id`, `articleId`, `languageId`, `title`, `description`, `text`) VALUES
(1, 1, 1, 'Naša vizija poslovne izvrsnosti', '<p>Ovdje možete saznati o na&scaron;oj misiji i viziji, ciljevima, onome čime se vodimo i pogledu na klijente te o najvi&scaron;oj razini usluge koju redovito pružamo na&scaron;im klijentima.</p>', '<p>Tvrtka smo koja se od 1999. godine bavi uvozom najkvalitetnije opreme za moderne fitness centre. Težimo biti najbolji poslovni partner sportskim ustanovama raznih profila s naglaskom na fitness i rekreaciju.</p>'),
(2, 2, 1, 'Poslovnice i radno vrijeme', '<p>Ovdje možete naći popis poslovnica, njihove lokacije i radno vrijeme. Informacije o blagdanskom radnom vremenu također ćete naći ovdje.</p>', '<p>Ovdje možete naći popis poslovnica, njihove lokacije i radno vrijeme. Informacije o blagdanskom radnom vremenu također ćete naći ovdje.</p>\r\n<table border=\"0\">\r\n<tbody>\r\n<tr>\r\n<td>\r\n<p>&nbsp;</p>\r\n<p><strong>Varaždin, Kratka ulica 13<strong>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</strong></strong></p>\r\n<p>Ponedjeljak&nbsp;09:00 - 21:00</p>\r\n<p>Utorak&nbsp;09:00 - 21:00</p>\r\n<p>Srijeda&nbsp;09:00 - 21:00</p>\r\n<p>Četvrtak&nbsp;09:00 - 21:00</p>\r\n<p>Petak&nbsp;09:00 - 21:00</p>\r\n<p>Subota&nbsp;09:00 - 21:00</p>\r\n<p>Nedjelja&nbsp;09:00 - 14:00</p>\r\n</td>\r\n<td>\r\n<p>&nbsp;</p>\r\n<p><strong>Čakovec, Glavni trg 1</strong></p>\r\n<p>Ponedjeljak&nbsp;09:00 - 21:00</p>\r\n<p>Utorak&nbsp;09:00 - 21:00</p>\r\n<p>Srijeda&nbsp;09:00 - 21:00</p>\r\n<p>Četvrtak&nbsp;09:00 - 21:00</p>\r\n<p>Petak&nbsp;09:00 - 21:00</p>\r\n<p>Subota&nbsp;09:00 - 21:00</p>\r\n<p>Nedjelja&nbsp;09:00 - 14:00</p>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>'),
(3, 1, 2, 'Our vision of business excellence', '<p>Here you can find out more about our mission and vision, about what drives us and how we view our customers and the hgihest level of service we provide.</p>', '<p>We are a company founded in 1999., since then we import the finest, most high-quality gym equipment for modern fitness centers. We strive to be the best possible business partner to various sport facilities of different profiles with emphasis on fitness and recreation.</p>'),
(4, 2, 2, 'Locations and working hours', '<p>Here you will find a list of our locations and working hours. We will post here any changes to working hours due to holidays.</p>', '<p>Here you will find a list of our locations and working hours. We will post here any changes to working hours due to holidays.</p>\r\n<table border=\"0\">\r\n<tbody>\r\n<tr>\r\n<td>\r\n<p>&nbsp;</p>\r\n<p><strong>Varaždin, Kratka ulica 13&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</strong></p>\r\n<p>Monday 09:00 - 21:00</p>\r\n<p>Tuesday 09:00 - 21:00</p>\r\n<p>Wednesday 09:00 - 21:00</p>\r\n<p>Thursday 09:00 - 21:00</p>\r\n<p>Friday 09:00 - 21:00</p>\r\n<p>Saturday 09:00 - 21:00</p>\r\n<p>Sunday 09:00 - 14:00</p>\r\n</td>\r\n<td>\r\n<p>&nbsp;</p>\r\n<p><strong>Čakovec, Glavni trg 1</strong></p>\r\n<p>Monday&nbsp;09:00 - 21:00</p>\r\n<p>Tuesday&nbsp;09:00 - 21:00</p>\r\n<p>Wednesday&nbsp;09:00 - 21:00</p>\r\n<p>Thursday&nbsp;09:00 - 21:00</p>\r\n<p>Friday&nbsp;09:00 - 21:00</p>\r\n<p>Saturday&nbsp;09:00 - 21:00</p>\r\n<p>Sunday&nbsp;09:00 - 14:00</p>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p>&nbsp;</p>\r\n<div id=\"gtx-trans\" style=\"position: absolute; left: 413px; top: -20px;\"></div>'),
(5, 3, 1, 'Povijest tvrtke', '<p>Tvrtka je osnovana u Varaždinu 1999. godine kao ekskluzivni dobavljač branda fitness opreme Alpha za područje Republike Hrvatske. Od onda tvrtka je otvorila nove poslovnice, zaposlila dodatno osoblje i&nbsp;vi&scaron;estruko&nbsp;povećala promet.</p>', '<p>Tvrtka je osnovana u Varaždinu 1999. godine kao ekskluzivni dobavljač branda fitness opreme Alpha za područje Republike Hrvatske. Od onda tvrtka je otvorila nove poslovnice, zaposlila dodatno osoblje i&nbsp;vi&scaron;estruko&nbsp;povećala promet.</p>'),
(6, 4, 1, 'Kup hrvatske u penjanju', '<p>I ove godine na&scaron;a tvrtka sudjelovala je u najvećem penjačkom eventu u Hrvatskoj. Natjecatelji su ostvarili vrhunske rezultate, u tome im je pomogla oprema vrsnih proizvođača koju je moguće kupiti na na&scaron;im lokacijama.</p>', '<p>I ove godine na&scaron;a tvrtka sudjelovala je u najvećem penjačkom eventu u Hrvatskoj. Natjecatelji su ostvarili vrhunske rezultate, u tome im je pomogla oprema vrsnih proizvođača koju je moguće kupiti na na&scaron;im lokacijama.</p>'),
(7, 5, 1, 'Europsko prvenstvo u penjanju', '<p>U Če&scaron;koj održano 25. prvenstvo Europe u slobodnom penjanju. Natjecanje je ove godine okupilo rekordni broj sudionika, a sudjelovali su i na&scaron;i predstavnici opremljeni vrhunskom opremom najboljih svjetskih proizvođača dobavljenom od strane na&scaron;e tvrtke kao glavnog sponzora na&scaron;ih natjecatelja.</p>', '<p>U Če&scaron;koj održano 25. prvenstvo Europe u slobodnom penjanju. Natjecanje je ove godine okupilo rekordni broj sudionika, a sudjelovali su i na&scaron;i predstavnici opremljeni vrhunskom opremom najboljih svjetskih proizvođača dobavljenom od strane na&scaron;e tvrtke kao glavnog sponzora na&scaron;ih natjecatelja.</p>'),
(8, 3, 2, 'Company history', '<div>\r\n<p>Company was founded in Varaždin in 1999. as an exclusive distributer of fitness brand supplies Alpha for Republic of Croatia. Since then the company opened up new locations, employed new employees and multiplied it&#039;s income several times.</p>\r\n</div>', '<p>Company was founded in Varaždin in 1999. as an exclusive distributer of fitness brand supplies Alpha for Republic of Croatia. Since then the company opened up new locations, employed new employees and multiplied it&#039;s income several times.</p>'),
(9, 4, 2, 'Croatian climbing cup', '<p>This year too our com&scaron;any has participated in greatest climbing event in Croatia. Contestnats have achieved top results, they were helped by state-of-the-art equipment made by world0s finest manufacturers, all of it is available in our shops or online.</p>', '<p>This year too our com&scaron;any has participated in greatest climbing event in Croatia. Contestnats have achieved top results, they were helped by state-of-the-art equipment made by world0s finest manufacturers, all of it is available in our shops or online.</p>'),
(10, 5, 2, 'European climbing championship', '<p>In Czech Republic there was a 25th European championship in free climbing. This year&#039;s contest has gathered a recor dnumber of participants. Our representatives participated equipped with top quality climbing equipment made by world&#039;s lead manufaturers. Our company was the main sponsor of the event.</p>', '<p>In Czech Republic there was a 25th European championship in free climbing. This year&#039;s contest has gathered a recor dnumber of participants. Our representatives participated equipped with top quality climbing equipment made by world&#039;s lead manufaturers. Our company was the main sponsor of the event.</p>'),
(11, 6, 1, 'Kontakt', '<p>Ovdje možete naći na&scaron;e kontakt podatke te podatke na&scaron;eg osoblja zaduženog za komunikaciju s korisnicima i poslovnim partnerima.</p>\r\n<p>&nbsp;</p>\r\n<p><span><strong>&nbsp; &nbsp; &nbsp;Varaždin, Kratka ulica 13&nbsp; &nbsp; &nbsp;</strong></span></p>\r\n<p>Tel.: 042 000 000</p>\r\n<p>Mob.: 888 000 000</p>\r\n<p>Email: varazdin (at) xerces-max.com</p>\r\n<p>&nbsp;</p>\r\n<p><span><strong><strong>&nbsp; &nbsp; Čakovec, Glavni trg 1&nbsp; &nbsp; &nbsp;</strong></strong></span></p>\r\n<p>Tel.: 040 000 000</p>\r\n<p>Mob.: 888 100 000</p>\r\n<p>Email: cakovec (at) xerces-max.com</p>', '<p>Ovdje možete naći na&scaron;e kontakt podatke te podatke na&scaron;eg osoblja zaduženog za komunikaciju s korisnicima i poslovnim partnerima.</p>\r\n<p>&nbsp;</p>\r\n<p><span><strong>&nbsp; &nbsp; &nbsp;Varaždin, Kratka ulica 13&nbsp; &nbsp; &nbsp;</strong></span></p>\r\n<p>Tel.: 042 000 000</p>\r\n<p>Mob.: 888 000 000</p>\r\n<p>Email: varazdin (at) xerces-max.com</p>\r\n<p>&nbsp;</p>\r\n<p><span><strong><strong>&nbsp; &nbsp; Čakovec, Glavni trg 1&nbsp; &nbsp; &nbsp;</strong></strong></span></p>\r\n<p>Tel.: 040 000 000</p>\r\n<p>Mob.: 888 100 000</p>\r\n<p>Email: cakovec (at) xerces-max.com</p>');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cmslanguage`
--

DROP TABLE IF EXISTS `cmslanguage`;
CREATE TABLE IF NOT EXISTS `cmslanguage` (
  `id` int(11) NOT NULL,
  `title` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `cmslanguage`
--

INSERT INTO `cmslanguage` (`id`, `title`) VALUES
(1, 'Hrvatski'),
(2, 'English');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cmsmenu`
--

DROP TABLE IF EXISTS `cmsmenu`;
CREATE TABLE IF NOT EXISTS `cmsmenu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parentId` int(11) NOT NULL,
  `order` int(11) NOT NULL,
  `active` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=62 DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `cmsmenu`
--

INSERT INTO `cmsmenu` (`id`, `parentId`, `order`, `active`) VALUES
(1, 0, 1, 1),
(4, 1, 5, 1),
(5, 1, 2, 1),
(2, 1, 1, 1),
(3, 1, 4, 1),
(6, 1, 3, 1),
(10, 5, 10, 1),
(11, 5, 11, 1),
(12, 5, 12, 1),
(56, 3, 22, 1),
(57, 3, 21, 1),
(58, 3, 23, 1),
(59, 3, 24, 1),
(60, 58, 31, 1),
(61, 58, 32, 1);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cmsmenutext`
--

DROP TABLE IF EXISTS `cmsmenutext`;
CREATE TABLE IF NOT EXISTS `cmsmenutext` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `languageId` int(11) NOT NULL,
  `title` text NOT NULL,
  `url` text NOT NULL,
  `menuItemId` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=38 DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `cmsmenutext`
--

INSERT INTO `cmsmenutext` (`id`, `languageId`, `title`, `url`, `menuItemId`) VALUES
(1, 1, 'Glavna stranica', '/', 2),
(4, 1, 'Kontakt', '/kontakt', 4),
(3, 1, 'Proizvodi', '/proizvodi', 3),
(5, 1, 'O nama', '/o-nama', 5),
(6, 1, 'Natjecanja', '/natjecanja', 6),
(7, 2, 'Main page', '/', 2),
(8, 2, 'Contact', '/contact', 4),
(9, 2, 'Products', '/products', 3),
(10, 2, 'About us', '/about-us', 5),
(11, 2, 'Contests', '/contests', 6),
(12, 1, 'Naša vizija poslovne izvrsnosti', '/o-nama/nasa-vizija-poslovne-izvrsnosti', 10),
(13, 2, 'Our vision of business excellence', '/about-us/our-vision-of-business-excellence', 10),
(14, 1, 'Poslovnice i radno vrijeme', '/o-nama/poslovnice-i-radno-vrijeme', 10),
(15, 2, 'Locations and working hours', '/about-us/locations-and-working-hours', 10),
(16, 1, 'Povijest tvrtke', '/o-nama/povijest-tvrtke', 10),
(17, 2, 'Company history', '/about-us/company-history', 10),
(32, 1, 'Fitness oprema', '/proizvodi/fitness-oprema', 58),
(31, 1, 'Bicikli', '/proizvodi/bicikli', 57),
(30, 1, 'Penjačka oprema', '/proizvodi/penjacka-oprema', 56),
(29, 2, 'Skateboards', '/products/skateboards', 59),
(28, 2, 'Fitness equipment', '/products/fitness-equipment', 58),
(27, 2, 'Bicycles', '/products/bicycles', 57),
(26, 2, 'Climbing equipment', '/products/climbing-equipment', 56),
(33, 1, 'Skejtbordi', '/proizvodi/skejtbordi', 59),
(34, 1, 'Sprave za vježbanje', '/products/fitness-oprema/sprave-za-vjezbanje', 60),
(35, 2, ' Gym devices', '/products/fitness-equipment/gym-devices', 60),
(36, 1, 'Utezi', '/products/fitness-oprema/utezi', 61),
(37, 2, ' Weights', '/products/fitness-equipment/weights', 61);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cmsorder`
--

DROP TABLE IF EXISTS `cmsorder`;
CREATE TABLE IF NOT EXISTS `cmsorder` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `clientId` int(11) NOT NULL,
  `orderPaymentMethodTypeId` int(11) NOT NULL,
  `orderDate` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cmsorderpaymentmethodtext`
--

DROP TABLE IF EXISTS `cmsorderpaymentmethodtext`;
CREATE TABLE IF NOT EXISTS `cmsorderpaymentmethodtext` (
  `id` int(11) NOT NULL,
  `orderPaymentMethodTypeId` int(11) NOT NULL,
  `text` text NOT NULL,
  `languageId` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cmsorderpaymentmethodtype`
--

DROP TABLE IF EXISTS `cmsorderpaymentmethodtype`;
CREATE TABLE IF NOT EXISTS `cmsorderpaymentmethodtype` (
  `id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cmsorderrow`
--

DROP TABLE IF EXISTS `cmsorderrow`;
CREATE TABLE IF NOT EXISTS `cmsorderrow` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `orderid` int(11) NOT NULL,
  `productid` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cmsproduct`
--

DROP TABLE IF EXISTS `cmsproduct`;
CREATE TABLE IF NOT EXISTS `cmsproduct` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parentId` int(11) NOT NULL,
  `order` int(11) NOT NULL,
  `price` decimal(11,0) NOT NULL,
  `showCart` tinyint(1) NOT NULL,
  `productGroupId` int(11) NOT NULL,
  `active` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `cmsproduct`
--

INSERT INTO `cmsproduct` (`id`, `parentId`, `order`, `price`, `showCart`, `productGroupId`, `active`) VALUES
(9, 1, 1, '0', 1, 1, 1),
(10, 2, 2, '1199', 1, 2, 1),
(8, 2, 1, '3500', 1, 2, 1),
(13, 6, 1, '2330', 1, 6, 1),
(14, 6, 2, '2900', 1, 6, 1),
(11, 3, 1, '1399', 1, 3, 1),
(12, 3, 2, '344', 1, 3, 1),
(15, 7, 1, '599', 1, 7, 1),
(16, 7, 2, '578', 1, 7, 1),
(17, 8, 1, '452', 1, 8, 1),
(18, 1, 2, '0', 1, 1, 1),
(19, 8, 2, '333', 1, 8, 1);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cmsproductextrainfo`
--

DROP TABLE IF EXISTS `cmsproductextrainfo`;
CREATE TABLE IF NOT EXISTS `cmsproductextrainfo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `productId` int(11) NOT NULL,
  `productExtraInfoTypeId` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `cmsproductextrainfo`
--

INSERT INTO `cmsproductextrainfo` (`id`, `productId`, `productExtraInfoTypeId`) VALUES
(1, 8, 1),
(2, 10, 1),
(3, 12, 1);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cmsproductextrainfotext`
--

DROP TABLE IF EXISTS `cmsproductextrainfotext`;
CREATE TABLE IF NOT EXISTS `cmsproductextrainfotext` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `languageId` int(11) NOT NULL,
  `productExtraInfoTypeId` int(11) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `cmsproductextrainfotext`
--

INSERT INTO `cmsproductextrainfotext` (`id`, `languageId`, `productExtraInfoTypeId`, `description`) VALUES
(1, 1, 1, 'Proizvod odmah dostupan'),
(2, 2, 1, 'Available immediatelly');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cmsproductextrainfotype`
--

DROP TABLE IF EXISTS `cmsproductextrainfotype`;
CREATE TABLE IF NOT EXISTS `cmsproductextrainfotype` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `cmsproductextrainfotype`
--

INSERT INTO `cmsproductextrainfotype` (`id`, `description`) VALUES
(1, '0'),
(2, 'Poruka nedostupnosti');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cmsproductfile`
--

DROP TABLE IF EXISTS `cmsproductfile`;
CREATE TABLE IF NOT EXISTS `cmsproductfile` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `productId` int(11) NOT NULL,
  `filename` text NOT NULL,
  `frontImage` tinyint(1) NOT NULL,
  `active` int(11) NOT NULL,
  `order` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `cmsproductfile`
--

INSERT INTO `cmsproductfile` (`id`, `productId`, `filename`, `frontImage`, `active`, `order`) VALUES
(1, 8, 'bike-1.jpg', 1, 1, 1),
(3, 10, 'bike-2.jpg', 1, 1, 1),
(4, 11, 'climbing-2.jpg', 1, 1, 1),
(5, 12, 'climbing-1.jpg', 1, 1, 1),
(6, 13, 'chest-press-1.jpg', 1, 1, 1),
(7, 14, 'chest-press-2.jpg', 1, 1, 1),
(8, 15, 'dumbell-1.jpg', 1, 1, 1),
(9, 16, 'dumbell-5.jpg', 0, 1, 1),
(11, 17, 'skate-1.jpg', 1, 1, 1),
(12, 19, 'skate-4.jpg', 1, 1, 1);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cmsproductfiletext`
--

DROP TABLE IF EXISTS `cmsproductfiletext`;
CREATE TABLE IF NOT EXISTS `cmsproductfiletext` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `productFileId` int(11) NOT NULL,
  `languageId` int(11) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cmsproductgroup`
--

DROP TABLE IF EXISTS `cmsproductgroup`;
CREATE TABLE IF NOT EXISTS `cmsproductgroup` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parentId` int(11) NOT NULL,
  `order` int(11) NOT NULL,
  `active` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `cmsproductgroup`
--

INSERT INTO `cmsproductgroup` (`id`, `parentId`, `order`, `active`) VALUES
(1, 0, 1, 1),
(2, 1, 1, 1),
(5, 1, 3, 1),
(3, 1, 2, 1),
(6, 5, 1, 1),
(7, 5, 2, 1),
(8, 1, 4, 1);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cmsproductgrouptext`
--

DROP TABLE IF EXISTS `cmsproductgrouptext`;
CREATE TABLE IF NOT EXISTS `cmsproductgrouptext` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `productGroupId` int(11) NOT NULL,
  `title` text NOT NULL,
  `languageId` int(11) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `cmsproductgrouptext`
--

INSERT INTO `cmsproductgrouptext` (`id`, `productGroupId`, `title`, `languageId`, `description`) VALUES
(1, 1, 'Proizvodi', 1, '<p>Ovdje se nalazi cjelokupni katalog svih na&scaron;ih proizvoda, dostupnih u trgovinama i online</p>'),
(2, 2, 'Bicikli', 1, '<p>Ovdje možete naći na&scaron;u cjelokupnu ponudu bicikala, od gradskih bicikala za slobodno vrijeme do maratonskih jurilica!</p>'),
(4, 3, 'Penjačka oprema', 1, '<p>Top brandovi penjačke opreme za profesionalce i rekreativce</p>'),
(6, 3, 'Climbing equipment', 2, '<p>Top brands of climbing equipment for professionals and recreational climbers</p>'),
(7, 2, 'Bicycles', 2, '<p>Here you can find everything - from casual city bikes to marathon racers!</p>'),
(8, 1, 'Products', 2, '<p>Here you can find entire catalog of all our products available in shops and online</p>'),
(9, 5, 'Fitness oprema', 1, '<p>Izbor opreme za teretane i osobnu upotrebu</p>'),
(10, 5, 'Fitness equipment', 2, '<p>Great choice of equipment for gyms and perosnal use</p>'),
(11, 6, 'Sprave za vježbanje', 1, '<p>Sprave za teretane i upotrebu u&nbsp;profesionalnom sportu</p>'),
(12, 6, 'Gym devices', 2, '<p>Devices intended for gyms and professional sports usage</p>'),
(13, 7, 'Weights', 2, '<p>Collections of weights of different weights and shapes</p>'),
(14, 7, 'Utezi', 1, '<p>Kolekcije utega raznih oblika i težina</p>'),
(15, 8, 'Skejtbordi', 1, '<p>Profi i rekreativni skejtbordi</p>'),
(16, 8, 'Skateboards', 2, '<p>Professional and recreational skateboards</p>');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cmsproductlink`
--

DROP TABLE IF EXISTS `cmsproductlink`;
CREATE TABLE IF NOT EXISTS `cmsproductlink` (
  `id` int(11) NOT NULL,
  `productId` int(11) NOT NULL,
  `linkedProductId` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cmsproducttext`
--

DROP TABLE IF EXISTS `cmsproducttext`;
CREATE TABLE IF NOT EXISTS `cmsproducttext` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `productId` int(11) NOT NULL,
  `languageId` int(11) NOT NULL,
  `title` text NOT NULL,
  `description` text NOT NULL,
  `text` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=30 DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `cmsproducttext`
--

INSERT INTO `cmsproducttext` (`id`, `productId`, `languageId`, `title`, `description`, `text`) VALUES
(10, 9, 1, 'MTB XZ', '<p>Odličan mountain-bike za sve uvjete.</p>', '<p>Odličan mountain-bike za sve uvjete. Uključeno jasmtvo 2 godine.</p>'),
(9, 8, 2, 'MTB3X', '<p>Bicycle for all terrains</p>', '<p>Bicycle for all terrains. Comes with 2 year guarantee for bike&#039;s frame.</p>'),
(8, 8, 1, 'MTB3X', '<p>Bicikl za sve terene</p>', '<p>Bicikl za sve terene, 2 godine jamstva na okvir</p>'),
(11, 10, 1, 'City Bike 345E', '<p>Bicikl za gradsku vožnju. Idealan za svakodnevnu upotrebu.</p>', '<p>Bicikl za gradsku vožnju. Idealan za svakodnevnu upotrebu.</p>'),
(12, 11, 1, 'Kaciga Pro1', '<p>Odlično za profi i rekreativno penjanje.</p>', '<p>Odlično za profi i rekreativno penjanje.</p>'),
(13, 11, 2, 'Helmet Pro1', '<p>Excellent for professional and recreational climbing.</p>', '<p>Excellent for professional and recreational climbing.</p>'),
(14, 10, 2, 'City Bike 345E', '<p>Ideal for everyday use in city.</p>', '<p>Ideal for everyday use in city.</p>'),
(15, 12, 1, 'Penjačka užad', '<p>Profesionalna kvaliteta, 50 metara, užad: 1 x 10 m, 2 x 20 m.</p>', '<p>Profesionalna kvaliteta, 50 metara, užad: 1 x 10 m, 2 x 20 m.</p>'),
(16, 12, 2, 'Climbing ropes', '<p><span style=\"white-space: pre;\">Professional quality, 50 meters, ropes: : 1 x 10 m, 2 x 20 m.</span></p>', '<p><span>Professional quality, 50 meters, ropes: </span><span>: 1 x 10 m, 2 x 20 m.</span></p>'),
(17, 13, 2, 'Chest press XE1', '<p>Chest press for professional use in gyms.</p>', '<p>Chest&nbsp;press for professional use in gyms.</p>\r\n<div id=\"gtx-trans\" style=\"position: absolute; left: 11px; top: 21px;\"></div>'),
(18, 13, 1, 'Leptir sprava XE1', '<p>Leptir&nbsp;sprava za profesionalnu upotrebu u teretanama.</p>', '<p>Leptir sprava za profesionalnu upotrebu u teretanama.</p>\r\n<div id=\"gtx-trans\" style=\"position: absolute; left: 5px; top: 21px;\"></div>'),
(19, 14, 1, 'Leptir sprava XE2', '<p>Multifunkcionalna leptir sprava za profesionalnu upotrebu u teretanama.&nbsp;</p>', '<p>Multifunkcionalna leptir&nbsp;sprava za profesionalnu upotrebu u teretanama.&nbsp;</p>'),
(20, 14, 2, 'Chest press XE2', '<p>Multifunctional chest press for professional use in gyms.</p>', '<p>Multifunctional chest press for professional use in gyms.</p>'),
(21, 15, 2, 'Weights Conan XL', '<p>Classic gym weights: 1, 5, 10, 15, 20, 25 kg.&nbsp;</p>', '<p>Classic gym weights: 1, 5, 10, 15, 20, 25 kg.&nbsp;</p>'),
(22, 15, 1, 'Utezi Conan XL', '<p>Klasični profesionalni utezi. U težinama: 1, 2, 5, 10, 15, 20, 25 kg.</p>', '<p>Klasični profesionalni utezi. U težinama: 1, 2, 5, 10, 15, 20, 25 kg.</p>'),
(23, 16, 1, 'Utezi ABC', '<p>Utezi težina 5, 10, 15, 20, 25 kg.</p>', '<p>Utezi težina 5, 10, 15, 20, 25 kg.</p>'),
(24, 16, 2, 'Weights ABC', '<p>Weights of 5, 10, 15, 20, 25 kg.</p>', '<p>Weights of 5, 10, 15, 20, 25 kg.</p>'),
(25, 17, 2, 'Skateboard XLE1', '<p>Excellent all round skateboard.</p>', '<p>Excellent all round skateboard.</p>'),
(26, 17, 1, 'Skateboard XLE1', '<p>Odličan skateboard za sve uvjete.</p>', '<p>Odličan skateboard za sve uvjete.</p>'),
(27, 18, 2, '', '', ''),
(28, 19, 1, 'Skateboard A1', '<p>Skate vrhunske kvalitete.</p>', '<p>Skate vrhunske kvalitete.</p>'),
(29, 19, 2, 'Skateboard A1', '<p>Top quality skateboard.</p>', '<p>Top quality skateboard.</p>');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cmsuser`
--

DROP TABLE IF EXISTS `cmsuser`;
CREATE TABLE IF NOT EXISTS `cmsuser` (
  `id` char(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `firstname` text NOT NULL,
  `lastname` text NOT NULL,
  `company` text NOT NULL,
  `address` text NOT NULL,
  `city` text NOT NULL,
  `postalcode` text NOT NULL,
  `state` text NOT NULL,
  `phone` text NOT NULL,
  `fax` text NOT NULL,
  `email` text NOT NULL,
  `admin` tinyint(1) NOT NULL,
  `password` text NOT NULL,
  `active` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `cmsuser`
--

INSERT INTO `cmsuser` (`id`, `firstname`, `lastname`, `company`, `address`, `city`, `postalcode`, `state`, `phone`, `fax`, `email`, `admin`, `password`, `active`) VALUES
('', 'Ivan', 'I', 'Firma', '', '', '', '', '', '', 'ivan@ivan.com', 0, 'a', 0),
('f4b2b406-c9ab-4cc4-b8a2-8124443712b4', 'Ivan', 'I', '', '', '', '', '', '', '', 'test@test.com', 1, 'a', 1);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
