-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : jeu. 26 sep. 2024 à 05:25
-- Version du serveur : 8.3.0
-- Version de PHP : 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `sslwenow`
--

-- --------------------------------------------------------

--
-- Structure de la table `cartes`
--

DROP TABLE IF EXISTS `cartes`;
CREATE TABLE IF NOT EXISTS `cartes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `nom_carte` varchar(50) NOT NULL,
  `numero_carte` varchar(16) NOT NULL,
  `cvc` varchar(3) NOT NULL,
  `date_expiration` varchar(5) NOT NULL,
  `disponible` tinyint(1) DEFAULT '1',
  `somme` decimal(10,2) DEFAULT '0.00',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `cartes`
--

INSERT INTO `cartes` (`id`, `user_id`, `nom_carte`, `numero_carte`, `cvc`, `date_expiration`, `disponible`, `somme`) VALUES
(1, 1, 'Gold Plated', '9759248452696576', '845', '12/29', 1, 0.00),
(2, 1, 'Silver Plated', '8246248462698577', '456', '11/29', 0, 0.00),
(3, 2, 'Jean Lafois', '4484126668461833', '603', '02/29', 1, 699.00);

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `pseudo` varchar(50) NOT NULL,
  `identifiant` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `age` int NOT NULL,
  `genre` enum('Homme','Femme','Autre') NOT NULL,
  `pays` enum('France','Belgique','Suisse','UK') NOT NULL,
  `solde` decimal(10,2) DEFAULT '0.00',
  `date_creation` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `identifiant` (`identifiant`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `pseudo`, `identifiant`, `password`, `age`, `genre`, `pays`, `solde`, `date_creation`) VALUES
(1, 'Traxxou', '0170306', '5f4dcc3b5aa765d61d8327deb882cf99', 25, 'Homme', 'France', 8513.85, '2024-09-26 02:03:14'),
(2, 'Kley', '9676234', '30f562e1deff9459861080b8aa93e2b7', 19, 'Homme', 'Belgique', 0.00, '2024-09-26 02:43:06');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
