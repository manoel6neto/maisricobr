CREATE DATABASE  IF NOT EXISTS `gestao_cad_unico` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `gestao_cad_unico`;
-- MySQL dump 10.13  Distrib 5.7.17, for Win64 (x86_64)
--
-- Host: convenios.physisbrasil.com.br    Database: gestao_cad_unico
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
-- Table structure for table `cidade`
--

DROP TABLE IF EXISTS `cidade`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cidade` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) NOT NULL,
  `habitantes` float unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  KEY `id_nome` (`nome`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cidade`
--

LOCK TABLES `cidade` WRITE;
/*!40000 ALTER TABLE `cidade` DISABLE KEYS */;
INSERT INTO `cidade` VALUES (1,'Mandirituba',25662);
/*!40000 ALTER TABLE `cidade` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `consultas`
--

DROP TABLE IF EXISTS `consultas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `consultas` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `fk_id_pessoa` int(10) unsigned NOT NULL,
  `convenio` varchar(45) NOT NULL,
  `data` date NOT NULL,
  `profissional` varchar(255) NOT NULL,
  `status` varchar(100) NOT NULL,
  `unidade` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  KEY `fk_pessoa_consulta_idx` (`fk_id_pessoa`),
  CONSTRAINT `fk_pessoa_consulta` FOREIGN KEY (`fk_id_pessoa`) REFERENCES `pessoa` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `consultas`
--

LOCK TABLES `consultas` WRITE;
/*!40000 ALTER TABLE `consultas` DISABLE KEYS */;
INSERT INTO `consultas` VALUES (1,1,'SUS','2018-05-22','Maiza Vaz Tostes','Finalizada','PA - Central'),(2,1,'SUS','2018-05-03','Médico','Finalizada','PA - Central'),(3,1,'SUS','2018-04-30','Sabrina Leticia Zeglin Nicolau Cavalli','Finalizada','Ambulatório - Central'),(4,2,'SUS','2018-03-27','Monique Oselame Possamai','Finalizada','UBS - Élsio de Assis'),(5,2,'SUS','2018-03-09','Lucivania Domingues Borges','Finalizada','UBS - Élsio de Assis'),(6,3,'SUS','2018-01-10','Maiza Vaz Tostes','Finalizada','PA - Central');
/*!40000 ALTER TABLE `consultas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `familia`
--

DROP TABLE IF EXISTS `familia`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `familia` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `codigo` int(10) unsigned NOT NULL,
  `codigo_ibge` int(10) unsigned NOT NULL,
  `fk_id_cidade` int(10) unsigned NOT NULL,
  `bairro` varchar(255) NOT NULL,
  `endereco` varchar(255) NOT NULL,
  `cep` varchar(9) NOT NULL,
  `tipo_imovel` varchar(100) NOT NULL,
  `num_pavimentos` int(10) unsigned NOT NULL DEFAULT '0',
  `tipo_construcao` varchar(100) NOT NULL,
  `cod_setor_censitario` varchar(50) DEFAULT NULL,
  `tipo_esgoto` varchar(100) NOT NULL,
  `tipo_iluminacao` varchar(100) NOT NULL,
  `tipo_agua` varchar(100) NOT NULL,
  `coleta_lixo` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `latitude` varchar(255) DEFAULT NULL,
  `longitude` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  UNIQUE KEY `codigo_UNIQUE` (`codigo`),
  UNIQUE KEY `codigo_ibge_UNIQUE` (`codigo_ibge`),
  KEY `fk_cidade_familia_idx` (`fk_id_cidade`),
  CONSTRAINT `fk_cidade_familia` FOREIGN KEY (`fk_id_cidade`) REFERENCES `cidade` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `familia`
--

LOCK TABLES `familia` WRITE;
/*!40000 ALTER TABLE `familia` DISABLE KEYS */;
INSERT INTO `familia` VALUES (1,132564,12365,1,'Centro','Rua Anita Muller Palu 162','83800-000','Alugado',2,'Alvenaria','001256348755663','Rede','Interna','Encanada',1,'-25.780036','-49.327849');
/*!40000 ALTER TABLE `familia` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pessoa`
--

DROP TABLE IF EXISTS `pessoa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pessoa` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `fk_id_familia` int(10) unsigned NOT NULL,
  `nome` varchar(255) NOT NULL,
  `data_nascimento` date DEFAULT NULL,
  `rg` varchar(10) DEFAULT NULL,
  `cpf` varchar(15) NOT NULL,
  `nis` varchar(20) DEFAULT NULL,
  `carteira_trabalho` varchar(20) DEFAULT NULL,
  `titulo_eleitor` varchar(20) DEFAULT NULL,
  `is_responsavel` tinyint(4) NOT NULL DEFAULT '0',
  `funcao` varchar(255) NOT NULL,
  `renda` float unsigned DEFAULT '0',
  `num_sus` varchar(20) DEFAULT NULL,
  `escolaridade` varchar(255) NOT NULL,
  `trabalho` varchar(255) DEFAULT NULL,
  `carteira_assinada` tinyint(3) unsigned DEFAULT '0',
  `cns` varchar(20) DEFAULT NULL,
  `cms` varchar(20) DEFAULT NULL,
  `sexo` varchar(40) NOT NULL,
  `vacinacao_em_dia` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `celular` varchar(50) DEFAULT NULL,
  `email` varchar(200) DEFAULT NULL,
  `facebook` varchar(255) DEFAULT NULL,
  `instagram` varchar(255) DEFAULT NULL,
  `link_foto` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  KEY `index_responsavel` (`is_responsavel`),
  KEY `fk_familia_pessoa_idx` (`fk_id_familia`),
  CONSTRAINT `fk_familia_pessoa` FOREIGN KEY (`fk_id_familia`) REFERENCES `familia` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pessoa`
--

LOCK TABLES `pessoa` WRITE;
/*!40000 ALTER TABLE `pessoa` DISABLE KEYS */;
INSERT INTO `pessoa` VALUES (1,1,'Leandro Claudino Barbosa','1983-07-21',NULL,'046.251.009-33','1234569875','400.64524.13-1',NULL,1,'Responsável familiar',3000,'126300070052','Superior Incompleto','Técnico Informática',1,'126300070052','0049116','Masculino',1,'(41) 99119-2425','leandro.claudino@gmail.com','https://www.facebook.com/maingear/','https://www.instagram.com/maingear','foto_fake.png'),(2,1,'Zulmira Da Luz Franco Barbosa','1978-09-12','140597633','029.952.789-13','1236548957',NULL,NULL,0,'Esposa',1200,'702907553619577','Segundo Grau','Doméstica',0,'702907553619577','0007906','Feminino',1,'(41) 99134-1440','zulimara23@hotmail.com','https://www.facebook.com/originpc/','https://www.instagram.com/originpc','foto_fake.png'),(3,1,'Luiz Franco Barbosa','2005-11-29','144818711','554.974.899-15','1263547894',NULL,NULL,0,'Filho',0,'706208071184367','Ensino Fundamental','Estudante',0,'706208071184367','0027558','Masculino',1,NULL,NULL,NULL,NULL,'foto_fake.png');
/*!40000 ALTER TABLE `pessoa` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zoonoses`
--

DROP TABLE IF EXISTS `zoonoses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zoonoses` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `fk_id_pessoa` int(10) unsigned NOT NULL,
  `categoria` varchar(100) NOT NULL,
  `nome` varchar(255) DEFAULT NULL,
  `data_nascimento` date NOT NULL,
  `raca` varchar(100) DEFAULT NULL,
  `cor` varchar(100) DEFAULT NULL,
  `sexo` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  KEY `fk_pessoa_zoonose_idx` (`fk_id_pessoa`),
  CONSTRAINT `fk_pessoa_zoonose` FOREIGN KEY (`fk_id_pessoa`) REFERENCES `pessoa` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zoonoses`
--

LOCK TABLES `zoonoses` WRITE;
/*!40000 ALTER TABLE `zoonoses` DISABLE KEYS */;
INSERT INTO `zoonoses` VALUES (1,1,'Cachorro','Amy','2010-01-10','Beagle','Bicolor','Fêmea'),(2,2,'Gato','Filó','2011-03-25','Persa','Branco','Fêmea');
/*!40000 ALTER TABLE `zoonoses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping events for database 'gestao_cad_unico'
--

--
-- Dumping routines for database 'gestao_cad_unico'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-06-11 11:57:51
