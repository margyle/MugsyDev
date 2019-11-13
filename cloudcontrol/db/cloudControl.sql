-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 09, 2019 at 07:52 PM
-- Server version: 5.5.55-0ubuntu0.14.04.1
-- PHP Version: 5.5.9-1ubuntu4.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--

--

-- --------------------------------------------------------

--

--

-- --------------------------------------------------------

--
-- Table structure for table `coffeeNotes`
--

CREATE TABLE IF NOT EXISTS `coffeeNotes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `coffeeId` int(11) NOT NULL,
  `coffeeNote` varchar(255) NOT NULL,
  `submittedBy` varchar(255) NOT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Table structure for table `coffeeNowMonitor`
--

CREATE TABLE IF NOT EXISTS `coffeeNowMonitor` (
  `id` char(36) NOT NULL DEFAULT '',
  `machineId` char(36) NOT NULL DEFAULT '',
  `startBrewStatus` int(1) NOT NULL,
  `requestingUser` char(36) NOT NULL DEFAULT '',
  UNIQUE KEY `unique_id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Triggers `coffeeNowMonitor`
--
DROP TRIGGER IF EXISTS `coffeeNowIdCreator`;
DELIMITER //
CREATE TRIGGER `coffeeNowIdCreator` BEFORE INSERT ON `coffeeNowMonitor`
 FOR EACH ROW SET NEW.id = UUID()
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `coffeeNowSettings`
--

CREATE TABLE IF NOT EXISTS `coffeeNowSettings` (
  `coffeeNowSettingsId` int(255) NOT NULL AUTO_INCREMENT,
  `coffeeNowMachineId` char(36) NOT NULL,
  `coffeeNowRecipeId` int(255) NOT NULL,
  `coffeeNowRecipeName` varchar(255) NOT NULL,
  `coffeeNowSettingsType` varchar(255) NOT NULL,
  UNIQUE KEY `coffeeNowSettingsId` (`coffeeNowSettingsId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `coffees`
--

CREATE TABLE IF NOT EXISTS `coffees` (
  `id` int(11) NOT NULL,
  `coffeeName` varchar(255) NOT NULL,
  `coffeeRoaster` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `region` varchar(255) NOT NULL,
  `weight` decimal(2,0) NOT NULL,
  `summary` text NOT NULL,
  `barcode` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `companies`
--

CREATE TABLE IF NOT EXISTS `companies` (
  `id` char(36) NOT NULL,
  `name` varchar(255) NOT NULL,
  `summary` varchar(255) NOT NULL,
  `logo` varchar(255) NOT NULL,
  UNIQUE KEY `unique_id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `customIntegrations`
--

CREATE TABLE IF NOT EXISTS `customIntegrations` (
  `id` varchar(36) NOT NULL,
  `creatorId` varchar(36) NOT NULL,
  `apiKey` varchar(255) NOT NULL,
  `releaseStatus` varchar(255) NOT NULL,
  `summary` varchar(255) NOT NULL,
  `logo` varchar(255) NOT NULL,
  UNIQUE KEY `unique_id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `keyDetective`
--

CREATE TABLE IF NOT EXISTS `keyDetective` (
  `id` varchar(36) NOT NULL,
  `apiKey` varchar(255) NOT NULL,
  `creatorId` varchar(36) NOT NULL,
  `integrationType` varchar(255) NOT NULL,
  `creationDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `machineId` char(36) NOT NULL DEFAULT '',
  UNIQUE KEY `unique_id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Triggers `keyDetective`
--
DROP TRIGGER IF EXISTS `setKeys`;
DELIMITER //
CREATE TRIGGER `setKeys` BEFORE INSERT ON `keyDetective`
 FOR EACH ROW SET NEW.id = UUID(), NEW.apiKey = UUID()
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `machines`
--

CREATE TABLE IF NOT EXISTS `machines` (
  `id` varchar(36) NOT NULL,
  `userId` varchar(36) NOT NULL,
  `machineName` varchar(255) NOT NULL,
  UNIQUE KEY `unique_id` (`id`),
  UNIQUE KEY `unique_userId` (`userId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Triggers `machines`
--
DROP TRIGGER IF EXISTS `uuid_for_machines`;
DELIMITER //
CREATE TRIGGER `uuid_for_machines` BEFORE INSERT ON `machines`
 FOR EACH ROW SET NEW.id = UUID()
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `machineStatus`
--

CREATE TABLE IF NOT EXISTS `machineStatus` (
  `machineId` char(36) NOT NULL,
  `onlineStatus` int(1) NOT NULL,
  `waterStatus` int(1) NOT NULL,
  `beanStatus` int(1) NOT NULL,
  `mugStatus` int(1) NOT NULL,
  `hardwareStatus` int(1) NOT NULL,
  PRIMARY KEY (`machineId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `machineStatuses`
--

CREATE TABLE IF NOT EXISTS `machineStatuses` (
  `id` varchar(36) NOT NULL,
  `machineId` varchar(36) NOT NULL,
  `onlineStatus` varchar(255) NOT NULL,
  `brewStatusMessage` varchar(255) NOT NULL DEFAULT '',
  `brewStatusCode` int(1) NOT NULL,
  `waterLevel` int(100) NOT NULL,
  `beanLevel` int(100) NOT NULL,
  `brewCount` int(255) NOT NULL,
  `currentBeans` varchar(255) DEFAULT NULL,
  UNIQUE KEY `unique_id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `officialIntegrations`
--

CREATE TABLE IF NOT EXISTS `officialIntegrations` (
  `id` varchar(36) NOT NULL,
  `integrationName` varchar(255) NOT NULL,
  `integrationType` varchar(255) NOT NULL,
  `integrationSummary` varchar(255) NOT NULL,
  `companyId` varchar(36) NOT NULL,
  `logo` varchar(255) NOT NULL,
  UNIQUE KEY `id` (`id`),
  KEY `Index_1` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `recipesList`
--

CREATE TABLE IF NOT EXISTS `recipesList` (
  `recipeListId` int(255) NOT NULL AUTO_INCREMENT,
  `createdBy` varchar(36) NOT NULL,
  `recipeListName` varchar(30) NOT NULL,
  `recipeListTemperature` decimal(4,0) NOT NULL,
  `recipeListGrindWeight` decimal(4,0) NOT NULL,
  `recipeListWaterWeight` decimal(4,0) NOT NULL,
  `recipeListTime` time NOT NULL,
  `recipeListSource` char(30) NOT NULL,
  `steps` varchar(255) NOT NULL,
  `firstStepTime` int(255) NOT NULL,
  UNIQUE KEY `recipeListId_2` (`recipeListId`),
  KEY `recipeListId` (`recipeListId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=334 ;

-- --------------------------------------------------------

--
-- Table structure for table `recipeSteps`
--

CREATE TABLE IF NOT EXISTS `recipeSteps` (
  `stepId` int(255) NOT NULL AUTO_INCREMENT,
  `recipeId` int(255) NOT NULL,
  `totalSteps` int(255) NOT NULL,
  `step` int(11) NOT NULL,
  `waterWeight` decimal(5,2) NOT NULL,
  `pourPattern` int(255) NOT NULL,
  `stepTime` int(255) NOT NULL,
  `notes` varchar(255) NOT NULL,
  PRIMARY KEY (`stepId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

-- --------------------------------------------------------

--
-- Table structure for table `twilio`
--

CREATE TABLE IF NOT EXISTS `twilio` (
  `id` char(36) NOT NULL,
  `userId` varchar(36) NOT NULL,
  `phoneNumber` varchar(255) NOT NULL,
  `apiKey` char(255) NOT NULL,
  `codeword` varchar(255) NOT NULL,
  `machineId` char(255) NOT NULL,
  UNIQUE KEY `unique_id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Triggers `twilio`
--
DROP TRIGGER IF EXISTS `uuid_for_twilio_id_and_api_key`;
DELIMITER //
CREATE TRIGGER `uuid_for_twilio_id_and_api_key` BEFORE INSERT ON `twilio`
 FOR EACH ROW SET NEW.id = UUID(), NEW.apiKey = UUID()
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` char(36) NOT NULL,
  `email` char(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `authSub` char(255) NOT NULL,
  `userName` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `unique_userName` (`userName`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Triggers `users`
--
DROP TRIGGER IF EXISTS `uuid_for_user_id`;
DELIMITER //
CREATE TRIGGER `uuid_for_user_id` BEFORE INSERT ON `users`
 FOR EACH ROW SET NEW.id = UUID()
//
DELIMITER ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;