-- MySQL dump 10.13  Distrib 5.7.22, for Linux (x86_64)
--
-- Host: localhost    Database: gestao_politica
-- ------------------------------------------------------
-- Server version	5.7.22-0ubuntu0.16.04.1

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
INSERT INTO `ci_sessions` VALUES ('06btg12qab0cf7pd4aqd01or95mq3j14','201.86.30.242',1527972441,'__ci_last_regenerate|i:1527972441;'),('3getrorbg0nbobsgdkcimf6as4lkljj7','177.220.173.211',1528112036,'__ci_last_regenerate|i:1528112028;sessao|a:4:{s:18:\"id_usuario_sistema\";i:1;s:12:\"nome_usuario\";s:20:\"Manoel Carvalho Neto\";s:8:\"is_admin\";s:1:\"1\";s:6:\"logged\";b:1;}'),('5g6qh29gnmhctruh9ri3i7sfc14fbknj','177.220.173.211',1528113091,'__ci_last_regenerate|i:1528113047;sessao|a:4:{s:18:\"id_usuario_sistema\";i:2;s:12:\"nome_usuario\";s:17:\"Alexandre Gouveia\";s:8:\"is_admin\";s:1:\"1\";s:6:\"logged\";b:1;}'),('5pdnei8fumvah53a2f1aesm15gv3vkc8','201.86.30.242',1527972466,'__ci_last_regenerate|i:1527972442;'),('782rae1fjojsuuhkmpi402rb42g7lvb8','66.249.83.222',1528036474,'__ci_last_regenerate|i:1528036472;'),('amjpia42n91v3fcr98p8jcvf6cod6vur','177.220.173.211',1528113621,'__ci_last_regenerate|i:1528113621;'),('bpi6a4k75hd9ql3a3nshi55t8rll0rcb','168.181.51.177',1527951086,'__ci_last_regenerate|i:1527951086;'),('d0q117ime1ar5efbgkj781a90jc8sagb','40.77.190.57',1528022279,'__ci_last_regenerate|i:1528022279;'),('lomj6u112gudjk1qcdvlafibjtuoo24c','66.249.83.193',1527957046,'__ci_last_regenerate|i:1527957046;'),('m3es4uairplmi682l84agsi6hcv2n8g3','168.181.51.177',1527951075,'__ci_last_regenerate|i:1527951075;'),('ppvf3c2s9tdlqmc7act5pfhfahgqk3eu','177.220.173.211',1528112022,'__ci_last_regenerate|i:1528112022;'),('t3s8ugh1b8ht31m8aulvn88r9tnbk4at','66.249.83.193',1528100651,'__ci_last_regenerate|i:1528100651;');
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario_aplicativo_cidadao`
--

LOCK TABLES `usuario_aplicativo_cidadao` WRITE;
/*!40000 ALTER TABLE `usuario_aplicativo_cidadao` DISABLE KEYS */;
INSERT INTO `usuario_aplicativo_cidadao` VALUES (1,'84942142920','84942142920',2,NULL);
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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario_assistencia_social`
--

LOCK TABLES `usuario_assistencia_social` WRITE;
/*!40000 ALTER TABLE `usuario_assistencia_social` DISABLE KEYS */;
INSERT INTO `usuario_assistencia_social` VALUES (1,'alexandre@adgouveia.com.br','gestor2017',1,NULL),(2,'alexandre@adgouveia.com.br','gestor2017',2,NULL);
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario_cad_imobiliario`
--

LOCK TABLES `usuario_cad_imobiliario` WRITE;
/*!40000 ALTER TABLE `usuario_cad_imobiliario` DISABLE KEYS */;
INSERT INTO `usuario_cad_imobiliario` VALUES (1,'84942142920','84942142920',2,NULL);
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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario_cad_unico`
--

LOCK TABLES `usuario_cad_unico` WRITE;
/*!40000 ALTER TABLE `usuario_cad_unico` DISABLE KEYS */;
INSERT INTO `usuario_cad_unico` VALUES (1,'alexandre@adgouveia.com.br','gestor2017',1,NULL),(2,'alexandre@adgouveia.com.br','gestor2017',2,NULL);
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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario_comunicacao_social`
--

LOCK TABLES `usuario_comunicacao_social` WRITE;
/*!40000 ALTER TABLE `usuario_comunicacao_social` DISABLE KEYS */;
INSERT INTO `usuario_comunicacao_social` VALUES (1,'84093587515','321',1,NULL),(2,'84942142920','84942142920',2,NULL);
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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario_convenios`
--

LOCK TABLES `usuario_convenios` WRITE;
/*!40000 ALTER TABLE `usuario_convenios` DISABLE KEYS */;
INSERT INTO `usuario_convenios` VALUES (1,'84093587515','arroz10',1,NULL),(2,'84942142920','84942142920',2,NULL);
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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario_educacao`
--

LOCK TABLES `usuario_educacao` WRITE;
/*!40000 ALTER TABLE `usuario_educacao` DISABLE KEYS */;
INSERT INTO `usuario_educacao` VALUES (1,'aledonikian@gmail.com','adg2308',1,NULL),(2,'aledonikian@gmail.com','adg2308',2,NULL);
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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario_politica_publica`
--

LOCK TABLES `usuario_politica_publica` WRITE;
/*!40000 ALTER TABLE `usuario_politica_publica` DISABLE KEYS */;
INSERT INTO `usuario_politica_publica` VALUES (1,'alexandre@adgouveia.com.br','gestor2017',1,NULL),(2,'alexandre@adgouveia.com.br','gestor2017',2,NULL);
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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario_saude`
--

LOCK TABLES `usuario_saude` WRITE;
/*!40000 ALTER TABLE `usuario_saude` DISABLE KEYS */;
INSERT INTO `usuario_saude` VALUES (1,'hmm.alexandre.gouveia','adg2308',1,NULL),(2,'hmm.alexandre.gouveia','adg2308',2,NULL);
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
  `is_admin` tinyint(4) NOT NULL DEFAULT '0',
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
INSERT INTO `usuario_sistema` VALUES (1,'Manoel Carvalho Neto','manoel.carvalho.neto@gmail.com','604ac8afa883dea6169e73e26c34a15114032b28','84093587515','2018-04-19 13:39:52',1),(2,'Alexandre Gouveia','alexandre@adgouveia.com.br','28b4e5ffedf5737b9f7475ada9a7463470afbe17','84942142920','2018-06-04 11:42:26',1);
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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario_terceiro_setor`
--

LOCK TABLES `usuario_terceiro_setor` WRITE;
/*!40000 ALTER TABLE `usuario_terceiro_setor` DISABLE KEYS */;
INSERT INTO `usuario_terceiro_setor` VALUES (1,'aledonikian@gmail.com','2308adgouveia',1,NULL),(2,'aledonikian@gmail.com','2308adgouveia',2,NULL);
/*!40000 ALTER TABLE `usuario_terceiro_setor` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-06-04  9:01:17
