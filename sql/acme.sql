-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : jeu. 18 août 2022 à 00:25
-- Version du serveur : 8.0.26
-- Version de PHP : 8.0.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `acme`
--

-- --------------------------------------------------------

--
-- Structure de la table `category`
--

DROP TABLE IF EXISTS `category`;
CREATE TABLE IF NOT EXISTS `category` (
  `idCategory` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`idCategory`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `category`
--

INSERT INTO `category` (`idCategory`, `name`) VALUES
(1, 'Alpinisme'),
(2, 'Randonnée'),
(3, 'Escalade');

-- --------------------------------------------------------

--
-- Structure de la table `product`
--

DROP TABLE IF EXISTS `product`;
CREATE TABLE IF NOT EXISTS `product` (
  `idProduct` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `idCategory` int UNSIGNED NOT NULL,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `ref` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` decimal(6,2) UNSIGNED NOT NULL,
  PRIMARY KEY (`idProduct`),
  UNIQUE KEY `ref` (`ref`),
  KEY `idCategory` (`idCategory`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `product`
--

INSERT INTO `product` (`idProduct`, `idCategory`, `name`, `ref`, `price`) VALUES
(1, 1, 'Alpina', '210317 729', '1059.90'),
(2, 1, 'Oural', '210050 609', '240.95'),
(3, 1, 'Etna', '210029 309', '297.41'),
(4, 2, 'Stefi', '310945 426', '132.90'),
(5, 2, 'Pronto', '310551 974', '119.96'),
(6, 2, 'Dorte', '310914 967', '127.46'),
(7, 2, 'Anna', '320960 034', '105.00'),
(8, 3, 'Gunte', '430100 972', '116.96'),
(9, 3, 'Elfie', '430117 990', '125.96'),
(10, 3, 'Carla', '430112 303', '180.00');

-- --------------------------------------------------------

--
-- Structure de la table `session`
--

DROP TABLE IF EXISTS `session`;
CREATE TABLE IF NOT EXISTS `session` (
  `sid` char(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `data` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `dateSession` datetime NOT NULL,
  PRIMARY KEY (`sid`),
  KEY `dateSession` (`dateSession`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `symfony_session`
--

DROP TABLE IF EXISTS `symfony_session`;
CREATE TABLE IF NOT EXISTS `symfony_session` (
  `sid` char(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `data` blob NOT NULL,
  `dateSession` int UNSIGNED NOT NULL,
  `lifetime` int UNSIGNED NOT NULL,
  PRIMARY KEY (`sid`),
  KEY `dateSession` (`dateSession`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `idUser` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `log` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `pwd` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `lastName` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `firstName` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`idUser`),
  UNIQUE KEY `log` (`log`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`idUser`, `log`, `pwd`, `lastName`, `firstName`, `email`) VALUES
(1, 'max', '$2y$10$kDKsblRqmCIesNK3DPyKM.9rnWvd9KRdbhPZQ8BfZZ3RPUDF5TQMS', 'LEFORT', 'Max', 'gillesvds@adok.info'),
(2, 'tom', '$2y$10$RfltDWx61tClmId/E12uceF38NDS2AMKwnXqNyISwRh.v69rIBmLq', 'LEGRAND', 'Tom', 'contact@gillesvds.fr');

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `product_ibfk_1` FOREIGN KEY (`idCategory`) REFERENCES `category` (`idCategory`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
