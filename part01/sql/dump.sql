-- MySQL dump 10.13  Distrib 5.7.26, for Linux (x86_64)
--
-- Host: localhost    Database: systemice
-- ------------------------------------------------------
-- Server version	5.7.26-0ubuntu0.18.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `Hotels`
--

DROP TABLE IF EXISTS `Hotels`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Hotels` (
  `ID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `Name` varchar(255) DEFAULT NULL,
  `Description` longtext,
  `Address` varchar(255) DEFAULT NULL,
  `ArbitaryFields` json DEFAULT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `ID_UNIQUE` (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Hotels`
--

LOCK TABLES `Hotels` WRITE;
/*!40000 ALTER TABLE `Hotels` DISABLE KEYS */;
/*!40000 ALTER TABLE `Hotels` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Hotels_ArbitaryFields`
--

DROP TABLE IF EXISTS `Hotels_ArbitaryFields`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Hotels_ArbitaryFields` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Hotel_ID` int(10) unsigned NOT NULL,
  `Name` varchar(255) DEFAULT NULL,
  `Value` longtext,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `ID_UNIQUE` (`ID`),
  KEY `fk_Hotels_ArbitaryFields_Hotel` (`Hotel_ID`),
  CONSTRAINT `fk_Hotels_ArbitaryFields_Hotel` FOREIGN KEY (`Hotel_ID`) REFERENCES `Hotels` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Hotels_ArbitaryFields`
--

LOCK TABLES `Hotels_ArbitaryFields` WRITE;
/*!40000 ALTER TABLE `Hotels_ArbitaryFields` DISABLE KEYS */;
/*!40000 ALTER TABLE `Hotels_ArbitaryFields` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Hotels_ImageToHotel`
--

DROP TABLE IF EXISTS `Hotels_ImageToHotel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Hotels_ImageToHotel` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Image_ID` int(10) unsigned NOT NULL,
  `Hotel_ID` int(10) unsigned NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `ID_UNIQUE` (`ID`),
  KEY `fk_Hotels_ImageToHotel_1_idx` (`Image_ID`),
  KEY `fk_Hotels_ImageToHotel_2` (`Hotel_ID`),
  CONSTRAINT `fk_Hotels_ImageToHotel_1` FOREIGN KEY (`Image_ID`) REFERENCES `Images` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_Hotels_ImageToHotel_2` FOREIGN KEY (`Hotel_ID`) REFERENCES `Hotels` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Hotels_ImageToHotel`
--

LOCK TABLES `Hotels_ImageToHotel` WRITE;
/*!40000 ALTER TABLE `Hotels_ImageToHotel` DISABLE KEYS */;
/*!40000 ALTER TABLE `Hotels_ImageToHotel` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Hotels_ImageToRoom`
--

DROP TABLE IF EXISTS `Hotels_ImageToRoom`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Hotels_ImageToRoom` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Image_ID` int(10) unsigned DEFAULT NULL,
  `Room_ID` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `ID_UNIQUE` (`ID`),
  KEY `fk_Hotels_ImageToRoom_Image` (`Image_ID`),
  KEY `fk_Hotels_ImageToRoom_Room_idx` (`Room_ID`),
  CONSTRAINT `fk_Hotels_ImageToRoom_Image` FOREIGN KEY (`Image_ID`) REFERENCES `Images` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_Hotels_ImageToRoom_Room` FOREIGN KEY (`Room_ID`) REFERENCES `Hotels_Rooms` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Hotels_ImageToRoom`
--

LOCK TABLES `Hotels_ImageToRoom` WRITE;
/*!40000 ALTER TABLE `Hotels_ImageToRoom` DISABLE KEYS */;
/*!40000 ALTER TABLE `Hotels_ImageToRoom` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Hotels_Rooms`
--

DROP TABLE IF EXISTS `Hotels_Rooms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Hotels_Rooms` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Hotel_ID` int(10) unsigned NOT NULL,
  `Name` varchar(255) DEFAULT NULL,
  `Description` longtext,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `ID_UNIQUE` (`ID`),
  KEY `fk_Hotels_Rooms_ID` (`Hotel_ID`),
  CONSTRAINT `fk_Hotels_Rooms_ID` FOREIGN KEY (`Hotel_ID`) REFERENCES `Hotels` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Hotels_Rooms`
--

LOCK TABLES `Hotels_Rooms` WRITE;
/*!40000 ALTER TABLE `Hotels_Rooms` DISABLE KEYS */;
/*!40000 ALTER TABLE `Hotels_Rooms` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Hotels_ServiceToHotel`
--

DROP TABLE IF EXISTS `Hotels_ServiceToHotel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Hotels_ServiceToHotel` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Service_ID` int(10) unsigned NOT NULL,
  `Hotel_ID` int(11) unsigned NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `ID_UNIQUE` (`ID`),
  KEY `fk_Hotels_ServiceToHotel_Hotel` (`Hotel_ID`),
  KEY `fk_Hotels_ServiceToHotel_Service` (`Service_ID`),
  CONSTRAINT `fk_Hotels_ServiceToHotel_Hotel` FOREIGN KEY (`Hotel_ID`) REFERENCES `Hotels` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_Hotels_ServiceToHotel_Service` FOREIGN KEY (`Service_ID`) REFERENCES `Hotels_Services` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Hotels_ServiceToHotel`
--

LOCK TABLES `Hotels_ServiceToHotel` WRITE;
/*!40000 ALTER TABLE `Hotels_ServiceToHotel` DISABLE KEYS */;
/*!40000 ALTER TABLE `Hotels_ServiceToHotel` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Hotels_Services`
--

DROP TABLE IF EXISTS `Hotels_Services`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Hotels_Services` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Name` varchar(255) DEFAULT NULL,
  `Description` mediumtext,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `ID_UNIQUE` (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Hotels_Services`
--

LOCK TABLES `Hotels_Services` WRITE;
/*!40000 ALTER TABLE `Hotels_Services` DISABLE KEYS */;
/*!40000 ALTER TABLE `Hotels_Services` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Images`
--

DROP TABLE IF EXISTS `Images`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Images` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Name` varchar(255) DEFAULT NULL,
  `LocalPath` varchar(255) NOT NULL,
  `Url` varchar(255) NOT NULL,
  `Filesize` int(10) unsigned DEFAULT NULL,
  `Hash` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=4131 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Images`
--

LOCK TABLES `Images` WRITE;
/*!40000 ALTER TABLE `Images` DISABLE KEYS */;
/*!40000 ALTER TABLE `Images` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-07-17  2:52:26
