CREATE DATABASE  IF NOT EXISTS `gestao_politica` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `gestao_politica`;
-- MySQL dump 10.13  Distrib 5.7.17, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: gestao_politica
-- ------------------------------------------------------
-- Server version	5.7.21-log

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
-- Table structure for table `ci_sessions`
--

DROP TABLE IF EXISTS `ci_sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ci_sessions` (
  `id` varchar(40) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int(10) unsigned NOT NULL DEFAULT '0',
  `data` blob NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ci_sessions_timestamp` (`timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ci_sessions`
--

LOCK TABLES `ci_sessions` WRITE;
/*!40000 ALTER TABLE `ci_sessions` DISABLE KEYS */;
/*!40000 ALTER TABLE `ci_sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuario_aplicativo_cidadao`
--

DROP TABLE IF EXISTS `usuario_aplicativo_cidadao`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usuario_aplicativo_cidadao` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(255) NOT NULL,
  `senha` longtext NOT NULL,
  `id_usuario_sistema` int(11) NOT NULL,
  `url_cliente` longtext,
  PRIMARY KEY (`id`),
  KEY `sistema_aplicativo_cidadao_idx` (`id_usuario_sistema`),
  CONSTRAINT `sistema_aplicativo_cidadao` FOREIGN KEY (`id_usuario_sistema`) REFERENCES `usuario_sistema` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario_aplicativo_cidadao`
--

LOCK TABLES `usuario_aplicativo_cidadao` WRITE;
/*!40000 ALTER TABLE `usuario_aplicativo_cidadao` DISABLE KEYS */;
/*!40000 ALTER TABLE `usuario_aplicativo_cidadao` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuario_assistencia_social`
--

DROP TABLE IF EXISTS `usuario_assistencia_social`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usuario_assistencia_social` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(255) NOT NULL,
  `senha` longtext NOT NULL,
  `id_usuario_sistema` int(11) NOT NULL,
  `url_cliente` longtext,
  PRIMARY KEY (`id`),
  KEY `sistema_assistencia_social_idx` (`id_usuario_sistema`),
  CONSTRAINT `sistema_assistencia_social` FOREIGN KEY (`id_usuario_sistema`) REFERENCES `usuario_sistema` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario_assistencia_social`
--

LOCK TABLES `usuario_assistencia_social` WRITE;
/*!40000 ALTER TABLE `usuario_assistencia_social` DISABLE KEYS */;
INSERT INTO `usuario_assistencia_social` VALUES (1,'alexandre@adgouveia.com.br','gestor2017',1,NULL);
/*!40000 ALTER TABLE `usuario_assistencia_social` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuario_cad_imobiliario`
--

DROP TABLE IF EXISTS `usuario_cad_imobiliario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usuario_cad_imobiliario` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(255) NOT NULL,
  `senha` longtext NOT NULL,
  `id_usuario_sistema` int(11) NOT NULL,
  `url_cliente` longtext,
  PRIMARY KEY (`id`),
  KEY `sistema_cad_imobiliario_idx` (`id_usuario_sistema`),
  CONSTRAINT `sistema_cad_imobiliario` FOREIGN KEY (`id_usuario_sistema`) REFERENCES `usuario_sistema` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario_cad_imobiliario`
--

LOCK TABLES `usuario_cad_imobiliario` WRITE;
/*!40000 ALTER TABLE `usuario_cad_imobiliario` DISABLE KEYS */;
/*!40000 ALTER TABLE `usuario_cad_imobiliario` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuario_cad_unico`
--

DROP TABLE IF EXISTS `usuario_cad_unico`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usuario_cad_unico` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(255) NOT NULL,
  `senha` longtext NOT NULL,
  `id_usuario_sistema` int(11) NOT NULL,
  `url_cliente` longtext,
  PRIMARY KEY (`id`),
  KEY `sistema_cad_unico_idx` (`id_usuario_sistema`),
  CONSTRAINT `sistema_cad_unico` FOREIGN KEY (`id_usuario_sistema`) REFERENCES `usuario_sistema` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario_cad_unico`
--

LOCK TABLES `usuario_cad_unico` WRITE;
/*!40000 ALTER TABLE `usuario_cad_unico` DISABLE KEYS */;
INSERT INTO `usuario_cad_unico` VALUES (1,'alexandre@adgouveia.com.br','gestor2017',1,NULL);
/*!40000 ALTER TABLE `usuario_cad_unico` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuario_comunicacao_social`
--

DROP TABLE IF EXISTS `usuario_comunicacao_social`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usuario_comunicacao_social` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(255) NOT NULL,
  `senha` longtext NOT NULL,
  `id_usuario_sistema` int(11) NOT NULL,
  `url_cliente` longtext,
  PRIMARY KEY (`id`),
  KEY `sistema_comunicacao_social_idx` (`id_usuario_sistema`),
  CONSTRAINT `sistema_comunicacao_social` FOREIGN KEY (`id_usuario_sistema`) REFERENCES `usuario_sistema` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario_comunicacao_social`
--

LOCK TABLES `usuario_comunicacao_social` WRITE;
/*!40000 ALTER TABLE `usuario_comunicacao_social` DISABLE KEYS */;
INSERT INTO `usuario_comunicacao_social` VALUES (1,'84093587515','321',1,NULL);
/*!40000 ALTER TABLE `usuario_comunicacao_social` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuario_convenios`
--

DROP TABLE IF EXISTS `usuario_convenios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usuario_convenios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(11) NOT NULL,
  `senha` longtext NOT NULL,
  `id_usuario_sistema` int(11) NOT NULL,
  `url_cliente` longtext,
  PRIMARY KEY (`id`),
  KEY `sistema_convenios_idx` (`id_usuario_sistema`),
  CONSTRAINT `sistema_convenios` FOREIGN KEY (`id_usuario_sistema`) REFERENCES `usuario_sistema` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario_convenios`
--

LOCK TABLES `usuario_convenios` WRITE;
/*!40000 ALTER TABLE `usuario_convenios` DISABLE KEYS */;
INSERT INTO `usuario_convenios` VALUES (1,'84093587515','arroz10',1,NULL);
/*!40000 ALTER TABLE `usuario_convenios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuario_educacao`
--

DROP TABLE IF EXISTS `usuario_educacao`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usuario_educacao` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(255) NOT NULL,
  `senha` longtext NOT NULL,
  `id_usuario_sistema` int(11) NOT NULL,
  `url_cliente` longtext,
  PRIMARY KEY (`id`),
  KEY `sistema_educacao_idx` (`id_usuario_sistema`),
  CONSTRAINT `sistema_educacao` FOREIGN KEY (`id_usuario_sistema`) REFERENCES `usuario_sistema` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario_educacao`
--

LOCK TABLES `usuario_educacao` WRITE;
/*!40000 ALTER TABLE `usuario_educacao` DISABLE KEYS */;
INSERT INTO `usuario_educacao` VALUES (1,'aledonikian@gmail.com','adg2308',1,NULL);
/*!40000 ALTER TABLE `usuario_educacao` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuario_politica_publica`
--

DROP TABLE IF EXISTS `usuario_politica_publica`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usuario_politica_publica` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(255) NOT NULL,
  `senha` longtext NOT NULL,
  `id_usuario_sistema` int(11) NOT NULL,
  `url_cliente` longtext,
  PRIMARY KEY (`id`),
  KEY `sistema_politica_publica_idx` (`id_usuario_sistema`),
  CONSTRAINT `sistema_politica_publica` FOREIGN KEY (`id_usuario_sistema`) REFERENCES `usuario_sistema` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario_politica_publica`
--

LOCK TABLES `usuario_politica_publica` WRITE;
/*!40000 ALTER TABLE `usuario_politica_publica` DISABLE KEYS */;
INSERT INTO `usuario_politica_publica` VALUES (1,'alexandre@adgouveia.com.br','gestor2017',1,NULL);
/*!40000 ALTER TABLE `usuario_politica_publica` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuario_saude`
--

DROP TABLE IF EXISTS `usuario_saude`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usuario_saude` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(255) NOT NULL,
  `senha` longtext NOT NULL,
  `id_usuario_sistema` int(11) NOT NULL,
  `url_cliente` longtext,
  PRIMARY KEY (`id`),
  KEY `sistema_saude_idx` (`id_usuario_sistema`),
  CONSTRAINT `sistema_saude` FOREIGN KEY (`id_usuario_sistema`) REFERENCES `usuario_sistema` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario_saude`
--

LOCK TABLES `usuario_saude` WRITE;
/*!40000 ALTER TABLE `usuario_saude` DISABLE KEYS */;
INSERT INTO `usuario_saude` VALUES (1,'hmm.alexandre.gouveia','adg2308',1,NULL);
/*!40000 ALTER TABLE `usuario_saude` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuario_sistema`
--

DROP TABLE IF EXISTS `usuario_sistema`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usuario_sistema` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `senha` longtext NOT NULL,
  `cpf` varchar(11) NOT NULL,
  `data_criacao` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cpf_UNIQUE` (`cpf`),
  KEY `cpf_INDEX` (`cpf`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario_sistema`
--

LOCK TABLES `usuario_sistema` WRITE;
/*!40000 ALTER TABLE `usuario_sistema` DISABLE KEYS */;
INSERT INTO `usuario_sistema` VALUES (1,'Manoel Carvalho Neto','manoel.carvalho.neto@gmail.com','604ac8afa883dea6169e73e26c34a15114032b28','84093587515','2018-04-19 13:39:52'),(2,'Fulano de Tal','fulanodetal@gmail.com','604ac8afa883dea6169e73e26c34a15114032b28','84093587514','2018-04-19 14:35:43');
/*!40000 ALTER TABLE `usuario_sistema` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuario_terceiro_setor`
--

DROP TABLE IF EXISTS `usuario_terceiro_setor`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usuario_terceiro_setor` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(255) NOT NULL,
  `senha` longtext NOT NULL,
  `id_usuario_sistema` int(11) NOT NULL,
  `url_cliente` longtext,
  PRIMARY KEY (`id`),
  KEY `sistema_terceiro_setor_idx` (`id_usuario_sistema`),
  CONSTRAINT `sistema_terceiro_setor` FOREIGN KEY (`id_usuario_sistema`) REFERENCES `usuario_sistema` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario_terceiro_setor`
--

LOCK TABLES `usuario_terceiro_setor` WRITE;
/*!40000 ALTER TABLE `usuario_terceiro_setor` DISABLE KEYS */;
INSERT INTO `usuario_terceiro_setor` VALUES (1,'aledonikian@gmail.com','2308adgouveia',1,NULL);
/*!40000 ALTER TABLE `usuario_terceiro_setor` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping events for database 'gestao_politica'
--

--
-- Dumping routines for database 'gestao_politica'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-04-20 13:13:21
