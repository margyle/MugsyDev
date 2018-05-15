# ************************************************************
# Sequel Pro SQL dump
# Version 4541
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: 127.0.0.1 (MySQL 5.5.50-0+deb8u1)
# Database: mugsy_v1
# Generation Time: 2018-05-15 01:26:37 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table brewSettings
# ------------------------------------------------------------

DROP TABLE IF EXISTS `brewSettings`;

CREATE TABLE `brewSettings` (
  `brewSettingsId` int(255) NOT NULL AUTO_INCREMENT,
  `grindSize` int(255) NOT NULL,
  `waterTemp` int(255) NOT NULL,
  `bloomTime` int(255) NOT NULL,
  `pumpTimingOn` int(255) NOT NULL,
  `pumpTimingOff` int(255) NOT NULL,
  `pourOverPatternId` int(255) NOT NULL,
  `grinderTime` int(255) NOT NULL,
  `tempHolder` int(255) NOT NULL,
  `userId` int(255) NOT NULL,
  PRIMARY KEY (`brewSettingsId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `brewSettings` WRITE;
/*!40000 ALTER TABLE `brewSettings` DISABLE KEYS */;

INSERT INTO `brewSettings` (`brewSettingsId`, `grindSize`, `waterTemp`, `bloomTime`, `pumpTimingOn`, `pumpTimingOff`, `pourOverPatternId`, `grinderTime`, `tempHolder`)
VALUES
	(1,0,195,15,10,10,8,3,75);

/*!40000 ALTER TABLE `brewSettings` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table coffeeTypes
# ------------------------------------------------------------

DROP TABLE IF EXISTS `coffeeTypes`;

CREATE TABLE `coffeeTypes` (
  `coffeeTypeId` int(255) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `varietyId` int(255) NOT NULL,
  `originId` int(255) NOT NULL,
  `upc` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `weight` decimal(10,2) NOT NULL,
  `companyName` varchar(255) NOT NULL,
  PRIMARY KEY (`coffeeTypeId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `coffeeTypes` WRITE;
/*!40000 ALTER TABLE `coffeeTypes` DISABLE KEYS */;

INSERT INTO `coffeeTypes` (`coffeeTypeId`, `name`, `varietyId`, `originId`, `upc`, `price`, `weight`, `companyName`)
VALUES
	(1,'Fast Forward',0,0,'663505000328',15.00,12.00,'Counter Culture Coffee'),
	(2,'Fast Forward',0,0,'663505000328',15.00,12.00,'Counter Culture Cofee');

/*!40000 ALTER TABLE `coffeeTypes` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table favorites
# ------------------------------------------------------------

DROP TABLE IF EXISTS `favorites`;

CREATE TABLE `favorites` (
  `favoriteId` int(255) NOT NULL AUTO_INCREMENT,
  `userId` int(255) NOT NULL,
  `favoriteType` varchar(255) NOT NULL,
  `brewSettingId` int(255) NOT NULL,
  `CoffeeTypeId` int(255) NOT NULL,
  PRIMARY KEY (`favoriteId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table friends
# ------------------------------------------------------------

DROP TABLE IF EXISTS `friends`;

CREATE TABLE `friends` (
  `friendshipId` int(255) NOT NULL AUTO_INCREMENT,
  `userId` int(255) NOT NULL,
  `friendUserId` int(255) NOT NULL,
  PRIMARY KEY (`friendshipId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table hardwareTypes
# ------------------------------------------------------------

DROP TABLE IF EXISTS `hardwareTypes`;

CREATE TABLE `hardwareTypes` (
  `hardwareTypeID` int(255) NOT NULL,
  `hardwareType` varchar(255) NOT NULL,
  `voltage` varchar(255) NOT NULL,
  `amps` varchar(255) NOT NULL,
  PRIMARY KEY (`hardwareTypeID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table integrations
# ------------------------------------------------------------

DROP TABLE IF EXISTS `integrations`;

CREATE TABLE `integrations` (
  `integrationId` int(255) NOT NULL,
  `integrationName` varchar(255) NOT NULL,
  `apiType` varchar(255) NOT NULL,
  `authenticationKey` varchar(255) NOT NULL,
  `userId` int(255) NOT NULL,
  PRIMARY KEY (`integrationId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table pinMappings
# ------------------------------------------------------------

DROP TABLE IF EXISTS `pinMappings`;

CREATE TABLE `pinMappings` (
  `pinMappingId` int(255) NOT NULL,
  `relayChannel` int(255) NOT NULL,
  `hardwareTypeId` int(255) NOT NULL,
  `pinNumber` int(10) NOT NULL,
  PRIMARY KEY (`pinMappingId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table pourOverPatterns
# ------------------------------------------------------------

DROP TABLE IF EXISTS `pourOverPatterns`;

CREATE TABLE `pourOverPatterns` (
  `pourOverPatternId` int(255) NOT NULL,
  `pourOverPattern` varchar(255) NOT NULL,
  PRIMARY KEY (`pourOverPatternId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table profile
# ------------------------------------------------------------

DROP TABLE IF EXISTS `profile`;

CREATE TABLE `profile` (
  `profileId` int(255) NOT NULL,
  `userId` int(255) NOT NULL,
  `profileType` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  PRIMARY KEY (`profileId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table userLevels
# ------------------------------------------------------------

DROP TABLE IF EXISTS `userLevels`;

CREATE TABLE `userLevels` (
  `userLevelId` int(255) NOT NULL AUTO_INCREMENT,
  `userLevelType` varchar(255) NOT NULL,
  PRIMARY KEY (`userLevelId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `userId` int(255) NOT NULL,
  `userName` varchar(255) NOT NULL,
  `userLevel` tinyint(255) NOT NULL,
  PRIMARY KEY (`userId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;




/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
