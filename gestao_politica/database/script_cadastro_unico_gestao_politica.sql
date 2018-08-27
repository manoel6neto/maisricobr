-- MySQL dump 10.13  Distrib 5.7.23, for Linux (x86_64)
--
-- Host: localhost    Database: gestao_politica_cadastro_unico
-- ------------------------------------------------------
-- Server version	5.7.23-0ubuntu0.16.04.1

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
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `cod_ibge` int(10) unsigned NOT NULL,
  `populacao` float unsigned NOT NULL,
  `longitude` decimal(10,10) DEFAULT NULL,
  `latitude` decimal(10,10) DEFAULT NULL,
  `id_estado` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cod_ibge_UNIQUE` (`cod_ibge`),
  KEY `fk_cidade_estado_idx` (`id_estado`),
  CONSTRAINT `fk_cidade_estado` FOREIGN KEY (`id_estado`) REFERENCES `estado` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Relação das cidades relacionada com os estados';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cidade`
--

LOCK TABLES `cidade` WRITE;
/*!40000 ALTER TABLE `cidade` DISABLE KEYS */;
/*!40000 ALTER TABLE `cidade` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cor`
--

DROP TABLE IF EXISTS `cor`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cor` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `descricao` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cor`
--

LOCK TABLES `cor` WRITE;
/*!40000 ALTER TABLE `cor` DISABLE KEYS */;
/*!40000 ALTER TABLE `cor` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `endereco`
--

DROP TABLE IF EXISTS `endereco`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `endereco` (
  `id` int(10) NOT NULL,
  `bairro` varchar(100) NOT NULL,
  `logradouro` varchar(255) NOT NULL,
  `numero` varchar(10) DEFAULT NULL,
  `cep` varchar(8) DEFAULT NULL,
  `cod_setor_censitario` varchar(50) DEFAULT NULL,
  `latitude` decimal(10,10) DEFAULT NULL,
  `longitude` decimal(10,10) DEFAULT NULL,
  `id_estado` int(10) NOT NULL,
  `id_cidade` int(10) NOT NULL,
  `id_zona_residencial` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_endereco_zona_residencial_idx` (`id_zona_residencial`),
  KEY `fk_endereco_estado_idx` (`id_estado`),
  KEY `fk_endereco_cidade_idx` (`id_cidade`),
  CONSTRAINT `fk_endereco_cidade` FOREIGN KEY (`id_cidade`) REFERENCES `cidade` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_endereco_estado` FOREIGN KEY (`id_estado`) REFERENCES `estado` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_endereco_zona_residencial` FOREIGN KEY (`id_zona_residencial`) REFERENCES `zona_residencial` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Relação dos endereços';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `endereco`
--

LOCK TABLES `endereco` WRITE;
/*!40000 ALTER TABLE `endereco` DISABLE KEYS */;
/*!40000 ALTER TABLE `endereco` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `escolaridade`
--

DROP TABLE IF EXISTS `escolaridade`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `escolaridade` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `descricao` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `escolaridade`
--

LOCK TABLES `escolaridade` WRITE;
/*!40000 ALTER TABLE `escolaridade` DISABLE KEYS */;
/*!40000 ALTER TABLE `escolaridade` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `estado`
--

DROP TABLE IF EXISTS `estado`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `estado` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `nome` varchar(50) NOT NULL,
  `sigla` varchar(2) NOT NULL,
  `cod_ibge` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nome_UNIQUE` (`nome`),
  UNIQUE KEY `sigla_UNIQUE` (`sigla`),
  UNIQUE KEY `cod_ibge_UNIQUE` (`cod_ibge`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Relação dos estados presentes no sistema';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `estado`
--

LOCK TABLES `estado` WRITE;
/*!40000 ALTER TABLE `estado` DISABLE KEYS */;
/*!40000 ALTER TABLE `estado` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `familia`
--

DROP TABLE IF EXISTS `familia`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `familia` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `codigo` varchar(10) NOT NULL,
  `id_endereco` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `codigo_UNIQUE` (`codigo`),
  KEY `fk_familia_endereco_idx` (`id_endereco`),
  CONSTRAINT `fk_familia_endereco` FOREIGN KEY (`id_endereco`) REFERENCES `endereco` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Base da familia com os dados gerais';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `familia`
--

LOCK TABLES `familia` WRITE;
/*!40000 ALTER TABLE `familia` DISABLE KEYS */;
/*!40000 ALTER TABLE `familia` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `familia_pessoa`
--

DROP TABLE IF EXISTS `familia_pessoa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `familia_pessoa` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `id_familia` int(10) NOT NULL,
  `id_pessoa` int(10) NOT NULL,
  `id_funcao` int(10) DEFAULT NULL,
  `flag_responsavel` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_pessoa_UNIQUE` (`id_pessoa`),
  KEY `fk_familia_pessoa_familia_idx` (`id_familia`),
  KEY `fk_familia_pessoa_funcao_idx` (`id_funcao`),
  CONSTRAINT `fk_familia_pessoa_familia` FOREIGN KEY (`id_familia`) REFERENCES `familia` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_familia_pessoa_funcao` FOREIGN KEY (`id_funcao`) REFERENCES `funcao_familiar` (`id`) ON DELETE SET NULL ON UPDATE SET NULL,
  CONSTRAINT `fk_familia_pessoa_pessoa` FOREIGN KEY (`id_pessoa`) REFERENCES `pessoa` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Ligação entre familia e pessoa';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `familia_pessoa`
--

LOCK TABLES `familia_pessoa` WRITE;
/*!40000 ALTER TABLE `familia_pessoa` DISABLE KEYS */;
/*!40000 ALTER TABLE `familia_pessoa` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `foto_pessoa`
--

DROP TABLE IF EXISTS `foto_pessoa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `foto_pessoa` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `data` blob NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `foto_pessoa`
--

LOCK TABLES `foto_pessoa` WRITE;
/*!40000 ALTER TABLE `foto_pessoa` DISABLE KEYS */;
/*!40000 ALTER TABLE `foto_pessoa` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `funcao_familiar`
--

DROP TABLE IF EXISTS `funcao_familiar`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `funcao_familiar` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `descricao` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COMMENT='Descrição das funções familiares';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `funcao_familiar`
--

LOCK TABLES `funcao_familiar` WRITE;
/*!40000 ALTER TABLE `funcao_familiar` DISABLE KEYS */;
INSERT INTO `funcao_familiar` VALUES (1,'Marido'),(2,'Esposa'),(3,'Filho(a)'),(4,'Neto(a)'),(5,'Sobrinho(a)'),(6,'Pai'),(7,'Mãe'),(8,'Primo(a)');
/*!40000 ALTER TABLE `funcao_familiar` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pessoa`
--

DROP TABLE IF EXISTS `pessoa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pessoa` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `nome` varchar(150) NOT NULL,
  `rg` varchar(10) DEFAULT NULL,
  `cpf` varchar(11) DEFAULT NULL,
  `nis` varchar(20) DEFAULT NULL,
  `ctps` varchar(40) DEFAULT NULL,
  `titulo_eleitor` varchar(40) DEFAULT NULL,
  `cns` varchar(20) DEFAULT NULL,
  `email` varchar(200) DEFAULT NULL,
  `celular` varchar(11) DEFAULT NULL,
  `telefone` varchar(10) DEFAULT NULL,
  `renda` decimal(10,2) unsigned DEFAULT NULL,
  `facebook` varchar(200) DEFAULT NULL,
  `instagram` varchar(200) DEFAULT NULL,
  `id_sexo` int(10) NOT NULL,
  `id_cor` int(10) DEFAULT NULL,
  `id_escolaridade` int(10) DEFAULT NULL,
  `id_profissao` int(10) DEFAULT NULL,
  `id_foto` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `rg_UNIQUE` (`rg`),
  UNIQUE KEY `cpf_UNIQUE` (`cpf`),
  UNIQUE KEY `nis_UNIQUE` (`nis`),
  UNIQUE KEY `ctps_UNIQUE` (`ctps`),
  UNIQUE KEY `titulo_eleitor_UNIQUE` (`titulo_eleitor`),
  UNIQUE KEY `cns_UNIQUE` (`cns`),
  UNIQUE KEY `email_UNIQUE` (`email`),
  UNIQUE KEY `celular_UNIQUE` (`celular`),
  KEY `fk_pessoa_sexo_idx` (`id_sexo`),
  KEY `fk_pessoa_cor_idx` (`id_cor`),
  KEY `fk_pessoa_escolaridade_idx` (`id_escolaridade`),
  KEY `fk_pessoa_profissao_idx` (`id_profissao`),
  KEY `fk_pessoa_foto_idx` (`id_foto`),
  CONSTRAINT `fk_pessoa_cor` FOREIGN KEY (`id_cor`) REFERENCES `cor` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_pessoa_escolaridade` FOREIGN KEY (`id_escolaridade`) REFERENCES `escolaridade` (`id`) ON DELETE SET NULL ON UPDATE SET NULL,
  CONSTRAINT `fk_pessoa_foto` FOREIGN KEY (`id_foto`) REFERENCES `foto_pessoa` (`id`) ON DELETE SET NULL ON UPDATE SET NULL,
  CONSTRAINT `fk_pessoa_profissao` FOREIGN KEY (`id_profissao`) REFERENCES `profissao` (`id`) ON DELETE SET NULL ON UPDATE SET NULL,
  CONSTRAINT `fk_pessoa_sexo` FOREIGN KEY (`id_sexo`) REFERENCES `sexo` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pessoa`
--

LOCK TABLES `pessoa` WRITE;
/*!40000 ALTER TABLE `pessoa` DISABLE KEYS */;
/*!40000 ALTER TABLE `pessoa` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `profissao`
--

DROP TABLE IF EXISTS `profissao`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `profissao` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `descricao` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `profissao`
--

LOCK TABLES `profissao` WRITE;
/*!40000 ALTER TABLE `profissao` DISABLE KEYS */;
/*!40000 ALTER TABLE `profissao` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sexo`
--

DROP TABLE IF EXISTS `sexo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sexo` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `descricao` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `descricao_UNIQUE` (`descricao`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='Tabela com a lista de sexos';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sexo`
--

LOCK TABLES `sexo` WRITE;
/*!40000 ALTER TABLE `sexo` DISABLE KEYS */;
INSERT INTO `sexo` VALUES (2,'FEMININO'),(1,'MASCULINO');
/*!40000 ALTER TABLE `sexo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zona_residencial`
--

DROP TABLE IF EXISTS `zona_residencial`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zona_residencial` (
  `id` int(10) NOT NULL,
  `descricao` varchar(10) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `descricao_UNIQUE` (`descricao`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Relação das zonas residenciais';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zona_residencial`
--

LOCK TABLES `zona_residencial` WRITE;
/*!40000 ALTER TABLE `zona_residencial` DISABLE KEYS */;
INSERT INTO `zona_residencial` VALUES (2,'RURAL'),(1,'URBANA');
/*!40000 ALTER TABLE `zona_residencial` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-08-27 14:49:22
