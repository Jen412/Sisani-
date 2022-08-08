-- MariaDB dump 10.19  Distrib 10.4.24-MariaDB, for Win64 (AMD64)
--
-- Host: 127.0.0.1    Database: siseni
-- ------------------------------------------------------
-- Server version	10.4.24-MariaDB

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
-- Table structure for table `alumnos`
--

DROP TABLE IF EXISTS `alumnos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `alumnos` (
  `solicitud` varchar(6) NOT NULL,
  `alu_nombre` varchar(60) DEFAULT NULL,
  `alu_prom` int(4) DEFAULT NULL,
  `alu_apeP` varchar(60) DEFAULT NULL,
  `alu_apeM` varchar(60) DEFAULT NULL,
  `idCarrera` int(2) DEFAULT NULL,
  `cal_ceneval` int(4) DEFAULT NULL,
  PRIMARY KEY (`solicitud`),
  KEY `idCarrera_idx` (`idCarrera`),
  CONSTRAINT `idCarrera` FOREIGN KEY (`idCarrera`) REFERENCES `carreras` (`idCarrera`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `alumnos`
--

LOCK TABLES `alumnos` WRITE;
/*!40000 ALTER TABLE `alumnos` DISABLE KEYS */;
/*!40000 ALTER TABLE `alumnos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `calificaciones`
--

DROP TABLE IF EXISTS `calificaciones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `calificaciones` (
  `idMateriaGrupo` int(11) NOT NULL,
  `solicitud` varchar(6) NOT NULL,
  `calif` int(11) DEFAULT NULL,
  PRIMARY KEY (`solicitud`,`idMateriaGrupo`),
  KEY `FK_materiaGrupo_idx` (`idMateriaGrupo`),
  CONSTRAINT `FK_materiaGrupo` FOREIGN KEY (`idMateriaGrupo`) REFERENCES `materiagrupo` (`idMateriaGrupo`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_solicitud` FOREIGN KEY (`solicitud`) REFERENCES `alumnos` (`solicitud`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `calificaciones`
--

LOCK TABLES `calificaciones` WRITE;
/*!40000 ALTER TABLE `calificaciones` DISABLE KEYS */;
/*!40000 ALTER TABLE `calificaciones` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `carreras`
--

DROP TABLE IF EXISTS `carreras`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `carreras` (
  `idCarrera` int(2) NOT NULL,
  `nomCarrera` varchar(70) DEFAULT NULL,
  PRIMARY KEY (`idCarrera`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `carreras`
--

LOCK TABLES `carreras` WRITE;
/*!40000 ALTER TABLE `carreras` DISABLE KEYS */;
INSERT INTO `carreras` VALUES (4,'INGENIERÍA ELECTRÓNICA'),(5,'INGENIERÍA MECÁNICA'),(6,'INGENIERÍA ELÉCTRICA'),(15,'INGENIERÍA EN SISTEMAS COMPUTACIONALES'),(16,'INGENIERÍA INDUSTRIAL'),(20,'INGENIERÍA AMBIENTAL'),(21,'ARQUITECTURA'),(22,'CONTADOR PÚBLICO'),(23,'INGENIERÍA EN GESTIÓN EMPRESARIAL'),(24,'INGENIERÍA INFORMÁTICA');
/*!40000 ALTER TABLE `carreras` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `config`
--

DROP TABLE IF EXISTS `config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `config` (
  `idConfig` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) DEFAULT NULL,
  `descripcion` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`idConfig`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `config`
--

LOCK TABLES `config` WRITE;
/*!40000 ALTER TABLE `config` DISABLE KEYS */;
INSERT INTO `config` VALUES (1,'ACEPTADOS','cantidad de aspirantes aceptados'),(2,'CURSO','');
/*!40000 ALTER TABLE `config` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `detalles_config`
--

DROP TABLE IF EXISTS `detalles_config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `detalles_config` (
  `idDetallesConfig` int(11) NOT NULL,
  `idConfig` int(11) DEFAULT NULL,
  `idCarrera` int(2) DEFAULT NULL,
  `cantidadGrupos` int(4) DEFAULT NULL,
  `num_Alumnos` int(4) DEFAULT NULL,
  PRIMARY KEY (`idDetallesConfig`),
  KEY `idConfig_idx` (`idConfig`),
  KEY `idCarrera_idx` (`idCarrera`),
  CONSTRAINT `carrera--config` FOREIGN KEY (`idCarrera`) REFERENCES `carreras` (`idCarrera`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `idConfig` FOREIGN KEY (`idConfig`) REFERENCES `config` (`idConfig`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detalles_config`
--

LOCK TABLES `detalles_config` WRITE;
/*!40000 ALTER TABLE `detalles_config` DISABLE KEYS */;
INSERT INTO `detalles_config` VALUES (62,1,4,2,27),(82,1,5,2,45),(84,1,6,1,45),(85,1,15,3,12),(86,1,16,1,40),(87,1,20,1,45),(88,1,21,1,40),(89,1,22,2,45),(90,1,23,1,40),(91,1,24,1,45),(92,2,4,1,45),(93,2,5,1,45),(94,2,6,1,45),(95,2,15,3,45),(96,2,16,2,34),(97,2,20,1,45),(98,2,21,1,45),(99,2,22,1,45),(100,2,23,1,45),(101,2,24,1,45);
/*!40000 ALTER TABLE `detalles_config` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `grupos`
--

DROP TABLE IF EXISTS `grupos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `grupos` (
  `idGrupo` varchar(15) NOT NULL,
  `solicitud` varchar(6) NOT NULL,
  `letraGrupo` char(1) DEFAULT NULL,
  PRIMARY KEY (`idGrupo`,`solicitud`),
  KEY `grupos-Alumnos_idx` (`solicitud`),
  CONSTRAINT `grupos-Alumnos` FOREIGN KEY (`solicitud`) REFERENCES `alumnos` (`solicitud`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `grupos`
--

LOCK TABLES `grupos` WRITE;
/*!40000 ALTER TABLE `grupos` DISABLE KEYS */;
/*!40000 ALTER TABLE `grupos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `maestros`
--

DROP TABLE IF EXISTS `maestros`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `maestros` (
  `idMaestro` int(11) NOT NULL AUTO_INCREMENT,
  `nombreMaestro` varchar(70) DEFAULT NULL,
  `rfc` varchar(13) DEFAULT NULL,
  PRIMARY KEY (`idMaestro`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `maestros`
--

LOCK TABLES `maestros` WRITE;
/*!40000 ALTER TABLE `maestros` DISABLE KEYS */;
INSERT INTO `maestros` VALUES (2,'ZAMUDIO ALAMILLA JORGE','XAXX010101000'),(3,'VILLEGAS MORALES HECTOR','XAXX010101001');
/*!40000 ALTER TABLE `maestros` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `materiagrupo`
--

DROP TABLE IF EXISTS `materiagrupo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `materiagrupo` (
  `idMateriaGrupo` int(11) NOT NULL AUTO_INCREMENT,
  `idMateria` int(11) DEFAULT NULL,
  `idMaestro` int(11) DEFAULT NULL,
  `idGrupo` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`idMateriaGrupo`),
  KEY `idMateria_idx` (`idMateria`),
  KEY `idMaestro_idx` (`idMaestro`),
  KEY `idGrupo_idx` (`idGrupo`),
  CONSTRAINT `idGrupo` FOREIGN KEY (`idGrupo`) REFERENCES `grupos` (`idGrupo`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `idMaestro` FOREIGN KEY (`idMaestro`) REFERENCES `maestros` (`idMaestro`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `idMateria` FOREIGN KEY (`idMateria`) REFERENCES `materias` (`idMateria`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `materiagrupo`
--

LOCK TABLES `materiagrupo` WRITE;
/*!40000 ALTER TABLE `materiagrupo` DISABLE KEYS */;
/*!40000 ALTER TABLE `materiagrupo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `materias`
--

DROP TABLE IF EXISTS `materias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `materias` (
  `idMateria` int(11) NOT NULL,
  `nombreMateria` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`idMateria`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `materias`
--

LOCK TABLES `materias` WRITE;
/*!40000 ALTER TABLE `materias` DISABLE KEYS */;
INSERT INTO `materias` VALUES (1,'MATEMÁTICAS'),(2,'DESARROLLO HUMANO'),(3,'INTRODUCCIÓN A LA CARRERA');
/*!40000 ALTER TABLE `materias` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `idUser` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(200) DEFAULT NULL,
  `create` date DEFAULT NULL,
  `role` varchar(45) DEFAULT NULL,
  `rfc` varchar(13) DEFAULT NULL,
  PRIMARY KEY (`idUser`),
  UNIQUE KEY `email_UNIQUE` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'fer-410@live.com.mx','$2y$10$IaN9jpfhBPE85iKP3wB6xOU3tKznYawxqPcSyG8bXH2DqhYwLLo/6','0000-00-00','admin','admin'),(2,'jfernando_410@hotmail.com','$2y$10$.l.ILOADJBMSCMh.X7lS/O/Nyjyi7EHMrMgGxoqOd8dlSfmtwEI6W','0000-00-00','maestro','XAXX010101000');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-08-08 11:56:23
