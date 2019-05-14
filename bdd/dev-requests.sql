-- phpMyAdmin SQL Dump
-- version 4.4.15.10
-- https://www.phpmyadmin.net
--
-- Client :  localhost
-- Généré le :  Jeu 04 Octobre 2018 à 13:17
-- Version du serveur :  5.5.50-MariaDB
-- Version de PHP :  5.6.32

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES latin1 */;

--
-- Base de données :  `dev-requests`
--
CREATE DATABASE IF NOT EXISTS `dev-requests` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `dev-requests`;

-- --------------------------------------------------------

--
-- Structure de la table `departments`
--

DROP TABLE IF EXISTS `departments`;
CREATE TABLE IF NOT EXISTS `departments` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `costCenter` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `requests`
--

DROP TABLE IF EXISTS `requests`;
CREATE TABLE IF NOT EXISTS `requests` (
  `id` int(11) NOT NULL,
  `dateCreated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `applicant` varchar(255) NOT NULL,
  `idDepartment` int(11) NOT NULL,
  `projectName` varchar(255) NOT NULL,
  `currentSituationDescr` varchar(1000) NOT NULL,
  `currentIssueDescr` varchar(1000) NOT NULL,
  `proposedSolutionDescr` varchar(1000) NOT NULL,
  `addedFile` varchar(1000) NOT NULL,
  `benInvY1` double NOT NULL,
  `benInvY2` double NOT NULL,
  `benInvY3` double NOT NULL,
  `benInvY4` double NOT NULL,
  `benCostY1` double NOT NULL,
  `benCostY2` double NOT NULL,
  `benCostY3` double NOT NULL,
  `benCostY4` double NOT NULL,
  `benBenefY1` double NOT NULL,
  `benBenefY2` double NOT NULL,
  `benBenefY3` double NOT NULL,
  `benBenefY4` double NOT NULL,
  `budgetEstimated` varchar(255) NOT NULL,
  `budgetAvailable` varchar(255) NOT NULL,
  `projectManager` varchar(255) NOT NULL,
  `projectManagerBusiness` varchar(255) NOT NULL,
  `projectManagerIT` varchar(255) NOT NULL,
  `projSched1Business` double NOT NULL,
  `projSched1ExpDate` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `projSched1IT` double NOT NULL,
  `projSched1External` double NOT NULL,
  `projSched1Assets` double NOT NULL,
  `projSched2Business` double NOT NULL,
  `projSched2ExpDate` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `projSched2IT` double NOT NULL,
  `projSched2External` double NOT NULL,
  `projSched2Assets` double NOT NULL,
  `projSched3Business` double NOT NULL,
  `projSched3ExpDate` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `projSched3IT` double NOT NULL,
  `projSched3External` double NOT NULL,
  `projSched3Assets` double NOT NULL,
  `projSched4Business` double NOT NULL,
  `projSched4ExpDate` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `projSched4IT` double NOT NULL,
  `projSched4External` double NOT NULL,
  `projSched4Assets` double NOT NULL,
  `projSched5Business` double NOT NULL,
  `projSched5ExpDate` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `projSched5IT` double NOT NULL,
  `projSched5External` double NOT NULL,
  `projSched5Assets` double NOT NULL,
  `projSched6Business` double NOT NULL,
  `projSched6ExpDate` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `projSched6IT` double NOT NULL,
  `projSched6External` double NOT NULL,
  `projSched6Assets` double NOT NULL,
  `constraints` varchar(1000) NOT NULL,
  `idStatus` int(11) NOT NULL DEFAULT '1',
  `dateNewStatus` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `prevStatuses` varchar(5000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `statuses`
--

DROP TABLE IF EXISTS `statuses`;
CREATE TABLE IF NOT EXISTS `statuses` (
  `id` int(11) NOT NULL,
  `label` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL,
  `userName` varchar(255) NOT NULL,
  `passwd` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `userGroup` varchar(255) NOT NULL DEFAULT 'users'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Index pour les tables exportées
--

--
-- Index pour la table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `requests`
--
ALTER TABLE `requests`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `statuses`
--
ALTER TABLE `statuses`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `requests`
--
ALTER TABLE `requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `statuses`
--
ALTER TABLE `statuses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
