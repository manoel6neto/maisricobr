-- MySQL dump 10.13  Distrib 5.7.23, for Linux (x86_64)
--
-- Host: localhost    Database: gestao_politica
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
-- Table structure for table `beneficio`
--

DROP TABLE IF EXISTS `beneficio`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `beneficio` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `id_publico_alvo` int(10) NOT NULL,
  `id_orgao_gestor` int(10) NOT NULL,
  `id_tipo_beneficio` int(10) NOT NULL,
  `id_usuario_responsavel` int(10) DEFAULT NULL,
  `descricao` varchar(255) NOT NULL,
  `data_simulacao` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `valor_mensal_investido` decimal(10,2) DEFAULT NULL,
  `quantidade_beneficiarios` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_beneficio_publico_alvo_idx` (`id_publico_alvo`),
  KEY `fk_beneficio_orgao_gestor_idx` (`id_orgao_gestor`),
  KEY `fk_beneficio_tipo_beneficio_idx` (`id_tipo_beneficio`),
  KEY `fk_beneficio_usuario_idx` (`id_usuario_responsavel`),
  CONSTRAINT `fk_beneficio_orgao_gestor` FOREIGN KEY (`id_orgao_gestor`) REFERENCES `orgao_gestor` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_beneficio_publico_alvo` FOREIGN KEY (`id_publico_alvo`) REFERENCES `publico_alvo` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_beneficio_tipo_beneficio` FOREIGN KEY (`id_tipo_beneficio`) REFERENCES `tipo_beneficio` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_beneficio_usuario` FOREIGN KEY (`id_usuario_responsavel`) REFERENCES `usuario_sistema` (`id`) ON DELETE SET NULL ON UPDATE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `beneficio`
--

LOCK TABLES `beneficio` WRITE;
/*!40000 ALTER TABLE `beneficio` DISABLE KEYS */;
/*!40000 ALTER TABLE `beneficio` ENABLE KEYS */;
UNLOCK TABLES;

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
INSERT INTO `ci_sessions` VALUES ('060qj3kne6plfc1svvb2d1b104j8lc9g','::1',1535478266,_binary '__ci_last_regenerate|i:1535478266;'),('0d62v9t11oo2bkp1jfgk153klshageku','::1',1535477490,_binary '__ci_last_regenerate|i:1535477309;'),('4qsl3pgk7kaqe7bto0sciuaduns2cqlj','::1',1535476726,_binary '__ci_last_regenerate|i:1535476459;sessao|a:4:{s:18:\"id_usuario_sistema\";i:1;s:12:\"nome_usuario\";s:20:\"Manoel Carvalho Neto\";s:8:\"is_admin\";s:1:\"1\";s:6:\"logged\";b:1;}'),('590odsp2rhgv421o6uoe51791f8esq83','177.183.202.188',1535489667,_binary '__ci_last_regenerate|i:1535489667;'),('5qne6d86jitk9vof0rikplrsqnkpj6e2','::1',1535478698,_binary '__ci_last_regenerate|i:1535478498;sessao|a:4:{s:18:\"id_usuario_sistema\";i:1;s:12:\"nome_usuario\";s:20:\"Manoel Carvalho Neto\";s:8:\"is_admin\";s:1:\"1\";s:6:\"logged\";b:1;}'),('7o3fo62c14kq18aadadkdi8gcoai078c','66.249.79.127',1535532242,_binary '__ci_last_regenerate|i:1535532242;'),('9g8h6ep974i3ef4l1c7fu2v0p76mm6e9','::1',1535544739,_binary '__ci_last_regenerate|i:1535544738;'),('9jdqcgqv0qnkrafudnbukmdh7eg27pjo','::1',1535544775,_binary '__ci_last_regenerate|i:1535544740;sessao|a:4:{s:18:\"id_usuario_sistema\";i:1;s:12:\"nome_usuario\";s:20:\"Manoel Carvalho Neto\";s:8:\"is_admin\";s:1:\"1\";s:6:\"logged\";b:1;}'),('d5hfhapo0op5ruidn8jb782n5mosso94','::1',1535476900,_binary '__ci_last_regenerate|i:1535476899;sessao|a:4:{s:18:\"id_usuario_sistema\";i:1;s:12:\"nome_usuario\";s:20:\"Manoel Carvalho Neto\";s:8:\"is_admin\";s:1:\"1\";s:6:\"logged\";b:1;}'),('flutsh61bn6p1s8srinfv80iuc4o2p9e','177.16.223.116',1535548989,_binary '__ci_last_regenerate|i:1535548985;sessao|a:4:{s:18:\"id_usuario_sistema\";i:2;s:12:\"nome_usuario\";s:17:\"Alexandre Gouveia\";s:8:\"is_admin\";s:1:\"1\";s:6:\"logged\";b:1;}'),('hrmsvcbr9kplurejeckgo6bgtsrjh9k9','177.220.172.196',1535484522,_binary '__ci_last_regenerate|i:1535484522;'),('in8sq5bh3dhacvtups30t5qpjl3qm3f9','::1',1535477818,_binary '__ci_last_regenerate|i:1535477568;sessao|a:4:{s:18:\"id_usuario_sistema\";i:1;s:12:\"nome_usuario\";s:20:\"Manoel Carvalho Neto\";s:8:\"is_admin\";s:1:\"1\";s:6:\"logged\";b:1;}'),('iom4lu0gd3a57pmmsilfojtf2hs3lrvl','177.16.223.116',1535548983,_binary '__ci_last_regenerate|i:1535548983;'),('j6crsko83j2apjrsjdp38behq76sbmro','::1',1535478190,_binary '__ci_last_regenerate|i:1535477889;sessao|a:4:{s:18:\"id_usuario_sistema\";i:1;s:12:\"nome_usuario\";s:20:\"Manoel Carvalho Neto\";s:8:\"is_admin\";s:1:\"1\";s:6:\"logged\";b:1;}'),('jcgfd4neib8tpba26m7f1qqarm0j1b46','177.220.172.236',1535547194,_binary '__ci_last_regenerate|i:1535547194;'),('jv8ee2lmtjaufaibbeiq7iqr0lqv3jtt','177.183.202.188',1535489743,_binary '__ci_last_regenerate|i:1535489721;sessao|a:4:{s:18:\"id_usuario_sistema\";i:8;s:12:\"nome_usuario\";s:27:\"Unidade Nordeste AD Gouveia\";s:8:\"is_admin\";s:1:\"0\";s:6:\"logged\";b:1;}'),('kfsvptnrqsv2ttp2iu1dvaru9m5s1qae','::1',1535475803,_binary '__ci_last_regenerate|i:1535475647;'),('n9n71sbflm46greh3aeglmp2tg52v7ik','::1',1535478492,_binary '__ci_last_regenerate|i:1535478192;sessao|a:4:{s:18:\"id_usuario_sistema\";i:1;s:12:\"nome_usuario\";s:20:\"Manoel Carvalho Neto\";s:8:\"is_admin\";s:1:\"1\";s:6:\"logged\";b:1;}'),('pdrccq5oq4tlo4jejuiv8jbht15uml6j','::1',1535475880,_binary '__ci_last_regenerate|i:1535475660;sessao|a:4:{s:18:\"id_usuario_sistema\";i:1;s:12:\"nome_usuario\";s:20:\"Manoel Carvalho Neto\";s:8:\"is_admin\";s:1:\"1\";s:6:\"logged\";b:1;}'),('q8d5oome9osi1fh1pp393ci2mgalqobb','::1',1535476294,_binary '__ci_last_regenerate|i:1535476146;'),('rafe7df17nruhh78csjfvlgp5f4o056e','::1',1535477523,_binary '__ci_last_regenerate|i:1535477233;sessao|a:4:{s:18:\"id_usuario_sistema\";i:1;s:12:\"nome_usuario\";s:20:\"Manoel Carvalho Neto\";s:8:\"is_admin\";s:1:\"1\";s:6:\"logged\";b:1;}'),('tvncp298lfdjepdo6tbh860k44bcntfk','::1',1535476710,_binary '__ci_last_regenerate|i:1535476605;'),('ufqtm2uv0tt3ls867a67qd7jo4636dmm','177.220.172.196',1535484525,_binary '__ci_last_regenerate|i:1535484525;sessao|a:4:{s:18:\"id_usuario_sistema\";i:2;s:12:\"nome_usuario\";s:17:\"Alexandre Gouveia\";s:8:\"is_admin\";s:1:\"1\";s:6:\"logged\";b:1;}'),('uhgcakmivrbert3rdscdovfqgvh72l3j','::1',1535547169,_binary '__ci_last_regenerate|i:1535547169;sessao|a:4:{s:18:\"id_usuario_sistema\";i:1;s:12:\"nome_usuario\";s:20:\"Manoel Carvalho Neto\";s:8:\"is_admin\";s:1:\"1\";s:6:\"logged\";b:1;}'),('vokmcs0ljour4dl6o5v9q4tpburhdtoo','::1',1535476446,_binary '__ci_last_regenerate|i:1535476152;sessao|a:4:{s:18:\"id_usuario_sistema\";i:1;s:12:\"nome_usuario\";s:20:\"Manoel Carvalho Neto\";s:8:\"is_admin\";s:1:\"1\";s:6:\"logged\";b:1;}');
/*!40000 ALTER TABLE `ci_sessions` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Table structure for table `criterio`
--

DROP TABLE IF EXISTS `criterio`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `criterio` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `descricao` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `criterio`
--

LOCK TABLES `criterio` WRITE;
/*!40000 ALTER TABLE `criterio` DISABLE KEYS */;
INSERT INTO `criterio` VALUES (1,'Bairro'),(2,'Cep'),(3,'Quantidade de Pessoas'),(4,'Quantidade de Crianças'),(5,'Quantidade de Idosos'),(6,'Renda familiar'),(7,'Renda'),(8,'Faixa Etária'),(9,'Idade'),(10,'Sexo'),(11,'Cor ou raça');
/*!40000 ALTER TABLE `criterio` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `criterio_beneficio`
--

DROP TABLE IF EXISTS `criterio_beneficio`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `criterio_beneficio` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `id_criterio` int(10) NOT NULL,
  `id_beneficio` int(10) NOT NULL,
  `tipo_filtro` varchar(2) NOT NULL,
  `valor_filtro` decimal(10,2) NOT NULL,
  `tipo_juncao` varchar(2) DEFAULT NULL,
  `ordenacao` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_criterio_beneficio_criterio_idx` (`id_criterio`),
  KEY `fk_criterio_beneficio_beneficio_idx` (`id_beneficio`),
  CONSTRAINT `fk_criterio_beneficio_beneficio` FOREIGN KEY (`id_beneficio`) REFERENCES `beneficio` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_criterio_beneficio_criterio` FOREIGN KEY (`id_criterio`) REFERENCES `criterio` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `criterio_beneficio`
--

LOCK TABLES `criterio_beneficio` WRITE;
/*!40000 ALTER TABLE `criterio_beneficio` DISABLE KEYS */;
/*!40000 ALTER TABLE `criterio_beneficio` ENABLE KEYS */;
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
-- Table structure for table `orgao_gestor`
--

DROP TABLE IF EXISTS `orgao_gestor`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `orgao_gestor` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `nome_orgao` varchar(200) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nome_orgao_UNIQUE` (`nome_orgao`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orgao_gestor`
--

LOCK TABLES `orgao_gestor` WRITE;
/*!40000 ALTER TABLE `orgao_gestor` DISABLE KEYS */;
INSERT INTO `orgao_gestor` VALUES (2,'Ministério do Desenvolvimento Social'),(1,'Secretaria de Governo');
/*!40000 ALTER TABLE `orgao_gestor` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `parametro_beneficio`
--

DROP TABLE IF EXISTS `parametro_beneficio`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `parametro_beneficio` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `nome_produto` varchar(255) NOT NULL,
  `valor_unitario` decimal(10,2) DEFAULT NULL,
  `quantidade` int(11) DEFAULT NULL,
  `id_beneficio` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_parametro_beneficio_beneficio_idx` (`id_beneficio`),
  CONSTRAINT `fk_parametro_beneficio_beneficio` FOREIGN KEY (`id_beneficio`) REFERENCES `beneficio` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `parametro_beneficio`
--

LOCK TABLES `parametro_beneficio` WRITE;
/*!40000 ALTER TABLE `parametro_beneficio` DISABLE KEYS */;
/*!40000 ALTER TABLE `parametro_beneficio` ENABLE KEYS */;
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
  `apelido` varchar(200) DEFAULT NULL,
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
-- Table structure for table `publico_alvo`
--

DROP TABLE IF EXISTS `publico_alvo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `publico_alvo` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `descricao` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `descricao_UNIQUE` (`descricao`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `publico_alvo`
--

LOCK TABLES `publico_alvo` WRITE;
/*!40000 ALTER TABLE `publico_alvo` DISABLE KEYS */;
INSERT INTO `publico_alvo` VALUES (1,'Família'),(2,'Pessoa');
/*!40000 ALTER TABLE `publico_alvo` ENABLE KEYS */;
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
-- Table structure for table `tipo_beneficio`
--

DROP TABLE IF EXISTS `tipo_beneficio`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tipo_beneficio` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `descricao` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tipo_beneficio`
--

LOCK TABLES `tipo_beneficio` WRITE;
/*!40000 ALTER TABLE `tipo_beneficio` DISABLE KEYS */;
INSERT INTO `tipo_beneficio` VALUES (1,'Distribuição de Produtos'),(2,'Transferência de Renda');
/*!40000 ALTER TABLE `tipo_beneficio` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario_aplicativo_cidadao`
--

LOCK TABLES `usuario_aplicativo_cidadao` WRITE;
/*!40000 ALTER TABLE `usuario_aplicativo_cidadao` DISABLE KEYS */;
INSERT INTO `usuario_aplicativo_cidadao` VALUES (1,'84942142920','84942142920',2,''),(3,'84942142920','84942142920',3,''),(4,'84942142920','84942142920',4,''),(5,'84942142920','84942142920',5,''),(6,'84942142920','84942142920',6,''),(7,'manoel.carvalho.neto@gmail.com','manoelcarvalho321',1,''),(8,'04069569448','04069569448',8,''),(9,'84942142920','84942142920',7,'');
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
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario_assistencia_social`
--

LOCK TABLES `usuario_assistencia_social` WRITE;
/*!40000 ALTER TABLE `usuario_assistencia_social` DISABLE KEYS */;
INSERT INTO `usuario_assistencia_social` VALUES (2,'alexandre@adgouveia.com.br','gestor2017',2,''),(3,'alexandre@adgouveia.com.br','gestor2017',3,''),(4,'alexandre@adgouveia.com.br','gestor2017',4,''),(5,'alexandre@adgouveia.com.br','gestor2017',5,''),(6,'alexandre@adgouveia.com.br','gestor2017',6,''),(7,'04069569448','04069569448',8,''),(8,'84942142920','84942142920',7,''),(9,'mun.admin','admin123',1,'');
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
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario_cad_imobiliario`
--

LOCK TABLES `usuario_cad_imobiliario` WRITE;
/*!40000 ALTER TABLE `usuario_cad_imobiliario` DISABLE KEYS */;
INSERT INTO `usuario_cad_imobiliario` VALUES (1,'84942142920','84942142920',2,''),(2,'84942142920','84942142920',1,''),(3,'84942142920','84942142920',3,''),(4,'84942142920','84942142920',4,''),(5,'84942142920','84942142920',5,''),(6,'84942142920','84942142920',6,''),(7,'04069569448','04069569448',8,''),(8,'84942142920','84942142920',7,'');
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
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario_cad_unico`
--

LOCK TABLES `usuario_cad_unico` WRITE;
/*!40000 ALTER TABLE `usuario_cad_unico` DISABLE KEYS */;
INSERT INTO `usuario_cad_unico` VALUES (1,'alexandre@adgouveia.com.br','gestor2017',1,''),(2,'alexandre@adgouveia.com.br','gestor2017',2,''),(3,'alexandre@adgouveia.com.br','gestor2017',3,''),(4,'alexandre@adgouveia.com.br','gestor2017',4,''),(5,'alexandre@adgouveia.com.br','gestor2017',5,''),(6,'alexandre@adgouveia.com.br','gestor2017',6,''),(7,'04069569448','04069569448',8,''),(8,'84942142920','84942142920',7,'');
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
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario_comunicacao_social`
--

LOCK TABLES `usuario_comunicacao_social` WRITE;
/*!40000 ALTER TABLE `usuario_comunicacao_social` DISABLE KEYS */;
INSERT INTO `usuario_comunicacao_social` VALUES (1,'84093587515','321',1,''),(2,'84942142920','84942142920',2,''),(3,'35503467515','321',3,''),(4,'67861059987','67861059987',4,''),(5,'84942142920','84942142920',5,''),(6,'84942142920','84942142920',6,''),(7,'04069569448','04069569448',8,''),(8,'84942142920','84942142920',7,'');
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
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario_convenios`
--

LOCK TABLES `usuario_convenios` WRITE;
/*!40000 ALTER TABLE `usuario_convenios` DISABLE KEYS */;
INSERT INTO `usuario_convenios` VALUES (1,'84093587515','arroz10',1,''),(2,'84942142920','84942142920',2,''),(3,'35503467515','321',3,''),(4,'67861059987','67861059987',4,''),(5,'14352095168','14352095168',5,''),(6,'32889810925','32889810925',6,''),(7,'04069569448','04069569448',8,''),(8,'84942142920','84942142920',7,'');
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
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario_educacao`
--

LOCK TABLES `usuario_educacao` WRITE;
/*!40000 ALTER TABLE `usuario_educacao` DISABLE KEYS */;
INSERT INTO `usuario_educacao` VALUES (1,'aracaju@proesc.com','proesc@2018',1,''),(2,'aracaju@proesc.com','proesc@2018',2,''),(3,'aledonikian@gmail.com','adg2308',3,''),(4,'aledonikian@gmail.com','adg2308',4,''),(5,'aracaju@proesc.com','proesc@2018',5,''),(6,'aledonikian@gmail.com','adg2308',6,''),(7,'aracaju@proesc.com','proesc@2018',8,''),(8,'aracaju@proesc.com','proesc@2018',7,'');
/*!40000 ALTER TABLE `usuario_educacao` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuario_pesquisa`
--

DROP TABLE IF EXISTS `usuario_pesquisa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usuario_pesquisa` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(255) NOT NULL,
  `senha` longtext NOT NULL,
  `id_usuario_sistema` int(11) NOT NULL,
  `url_cliente` longtext,
  PRIMARY KEY (`id`),
  KEY `sistema_pesquisa_idx` (`id_usuario_sistema`),
  CONSTRAINT `sistema_pesquisa` FOREIGN KEY (`id_usuario_sistema`) REFERENCES `usuario_sistema` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario_pesquisa`
--

LOCK TABLES `usuario_pesquisa` WRITE;
/*!40000 ALTER TABLE `usuario_pesquisa` DISABLE KEYS */;
INSERT INTO `usuario_pesquisa` VALUES (1,'84093587515','arroz10',1,''),(2,'84942142920','84942142920',2,''),(3,'84093587515','arroz10',3,''),(4,'67861059987','67861059987',4,''),(5,'14352095168','14352095168',5,''),(6,'84093587515','arroz10',6,''),(7,'04069569448','04069569448',8,''),(8,'84942142920','84942142920',7,'');
/*!40000 ALTER TABLE `usuario_pesquisa` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario_politica_publica`
--

LOCK TABLES `usuario_politica_publica` WRITE;
/*!40000 ALTER TABLE `usuario_politica_publica` DISABLE KEYS */;
INSERT INTO `usuario_politica_publica` VALUES (1,'alexandre@adgouveia.com.br','gestor2017',1,''),(2,'alexandre@adgouveia.com.br','gestor2017',2,''),(3,'alexandre@adgouveia.com.br','gestor2017',3,''),(4,'alexandre@adgouveia.com.br','gestor2017',4,''),(5,'alexandre@adgouveia.com.br','gestor2017',5,''),(6,'alexandre@adgouveia.com.br','gestor2017',6,''),(7,'04069569448','04069569448',8,''),(8,'84942142920','84942142920',7,'');
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
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario_saude`
--

LOCK TABLES `usuario_saude` WRITE;
/*!40000 ALTER TABLE `usuario_saude` DISABLE KEYS */;
INSERT INTO `usuario_saude` VALUES (1,'mun.admin','admin123',1,''),(2,'hmm.alexandre.gouveia','adg2308',2,''),(3,'hmm.alexandre.gouveia','adg2308',3,''),(4,'hmm.alexandre.gouveia','adg2308',4,''),(5,'hmm.alexandre.gouveia','adg2308',5,''),(6,'hmm.alexandre.gouveia','adg2308',6,''),(7,'04069569448','04069569448',8,''),(8,'84942142920','84942142920',7,'');
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
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario_sistema`
--

LOCK TABLES `usuario_sistema` WRITE;
/*!40000 ALTER TABLE `usuario_sistema` DISABLE KEYS */;
INSERT INTO `usuario_sistema` VALUES (1,'Manoel Carvalho Neto','manoel.carvalho.neto@gmail.com','604ac8afa883dea6169e73e26c34a15114032b28','84093587515','2018-04-19 13:39:52',1),(2,'Alexandre Gouveia','alexandre@adgouveia.com.br','28b4e5ffedf5737b9f7475ada9a7463470afbe17','84942142920','2018-06-04 11:42:26',1),(3,'Miguel Augusto Silva Batista','miguelaugusto5@yahoo.com.br','d2482c230aad36512b42ece66216a5346f6799cc','35503467515','2018-06-05 11:38:52',1),(4,'Michel Platchek','platchek@hotmail.com','e35325739ed809b4d4acc66cdb0fc185dda021dd','67861059987','2018-06-05 11:40:08',1),(5,'Paulo Menezes','professorpaulomenezes@gmail.com','011ea0f085ec6dea301e6dc981ee4fc7bdbdddb0','14352095168','2018-06-05 11:44:07',0),(6,'Luiz Roberto Romano','luizroberto@romano.adv.br','71ef71ed437594f6e30be4e06938822a43512357','32889810925','2018-06-05 13:39:30',1),(7,'Rosi Bianchin','vendas@adgouveia.com.br','15778587225c9aa9ecf76874d3582e291bd3b3ff','86465783991','2018-08-09 17:17:51',0),(8,'Unidade Nordeste AD Gouveia','unidade_ne@adgouveia.com.br','12075a671cd164c5a9b317acad3aaea64dad71fa','04069569448','2018-08-09 17:25:56',0);
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
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario_terceiro_setor`
--

LOCK TABLES `usuario_terceiro_setor` WRITE;
/*!40000 ALTER TABLE `usuario_terceiro_setor` DISABLE KEYS */;
INSERT INTO `usuario_terceiro_setor` VALUES (1,'aledonikian@gmail.com','2308adgouveia',1,''),(2,'aledonikian@gmail.com','2308adgouveia',2,''),(3,'aledonikian@gmail.com','2308adgouveia',3,''),(4,'aledonikian@gmail.com','2308adgouveia',4,''),(5,'aledonikian@gmail.com','2308adgouveia',5,''),(6,'aledonikian@gmail.com','2308adgouveia',6,''),(7,'04069569448','04069569448',8,''),(8,'84942142920','84942142920',7,'');
/*!40000 ALTER TABLE `usuario_terceiro_setor` ENABLE KEYS */;
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

-- Dump completed on 2018-08-29 10:43:18
