-- MySQL dump 10.13  Distrib 5.6.17, for Win64 (x86_64)
--
-- Host: physisbrasil.com.br    Database: physi971_pub_versao
-- ------------------------------------------------------
-- Server version	5.5.38-35.2

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
-- Table structure for table `aceite_licenca_uso`
--

DROP TABLE IF EXISTS `aceite_licenca_uso`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `aceite_licenca_uso` (
  `id_aceite_licenca_uso` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) NOT NULL,
  `data` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_aceite_licenca_uso`),
  KEY `fk_usuario_idx` (`id_usuario`),
  CONSTRAINT `fk_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `area`
--

DROP TABLE IF EXISTS `area`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `area` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `arquivos`
--

DROP TABLE IF EXISTS `arquivos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `arquivos` (
  `idArquivo` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nome_arquivo` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `descricao` varchar(5000) COLLATE utf8_unicode_ci NOT NULL,
  `arquivo` mediumblob NOT NULL,
  `tipo` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `tamanho` int(11) NOT NULL,
  `print_arquivo` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `data_hora_envio` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`idArquivo`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `banco`
--

DROP TABLE IF EXISTS `banco`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `banco` (
  `idbanco` int(11) NOT NULL,
  `nome` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`idbanco`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `banco_proposta`
--

DROP TABLE IF EXISTS `banco_proposta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `banco_proposta` (
  `id_proposta` int(11) NOT NULL AUTO_INCREMENT,
  `objeto` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `justificativa` longtext COLLATE utf8_unicode_ci,
  `codigo_siconv` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `id_siconv` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `situacao` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `parecer` longtext COLLATE utf8_unicode_ci,
  `modalidade` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `proponente` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `orgao` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `codigo_programa` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nome_programa` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `convenio` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `data_inicio` date DEFAULT NULL,
  `data_fim` date DEFAULT NULL,
  `valor_global` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `valor_repasse` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `valor_contrapartida_financeira` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `valor_contrapartida_bens` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nome_proponente` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ano` varchar(5) COLLATE utf8_unicode_ci DEFAULT NULL,
  `empenhado` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id_proposta`),
  KEY `idx_id_siconv` (`id_siconv`)
) ENGINE=InnoDB AUTO_INCREMENT=439871 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `programa_banco_proposta`
--

DROP TABLE IF EXISTS `programa_banco_proposta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `programa_banco_proposta` (
  `id_programa_banco_proposta` int(11) NOT NULL AUTO_INCREMENT,
  `id_proposta_banco_proposta` int(11) NOT NULL,
  `nome_programa` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `codigo_programa` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `regra_contrapartida` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `valor_global` varchar(50) DEFAULT NULL,
  `total_contrapartida` varchar(50) DEFAULT NULL,
  `contrapartida_financeira` varchar(50) DEFAULT NULL,
  `contrapartida_bens` varchar(50) DEFAULT NULL,
  `repasse` varchar(50) DEFAULT NULL,
  `id_programa` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `objeto` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id_programa_banco_proposta`),
  KEY `fk_Programa_Banco_Proposta_idx` (`id_proposta_banco_proposta`),
  CONSTRAINT `fk_Programa_Banco_Proposta` FOREIGN KEY (`id_proposta_banco_proposta`) REFERENCES `banco_proposta` (`id_proposta`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `emenda_banco_proposta`
--

DROP TABLE IF EXISTS `emenda_banco_proposta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `emenda_banco_proposta` (
  `id_emenda_banco_proposta` int(11) NOT NULL AUTO_INCREMENT,
  `id_programa_banco_proposta` int(11) NOT NULL,
  `codigo_emenda` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `valor_emenda` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id_emenda_banco_proposta`),
  KEY `fk_Emenda_Banco_Proposta_idx` (`id_programa_banco_proposta`),
  CONSTRAINT `fk_Emenda_Banco_Proposta` FOREIGN KEY (`id_programa_banco_proposta`) REFERENCES `programa_banco_proposta` (`id_programa_banco_proposta`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bens`
--

DROP TABLE IF EXISTS `bens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bens` (
  `Nome` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Codigo` int(8) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ci_sessions`
--

DROP TABLE IF EXISTS `ci_sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ci_sessions` (
  `session_id` varchar(40) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `ip_address` varchar(45) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `user_agent` varchar(120) COLLATE utf8_unicode_ci NOT NULL,
  `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
  `user_data` longtext COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`session_id`),
  KEY `last_activity_idx` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cidade_tag`
--

DROP TABLE IF EXISTS `cidade_tag`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cidade_tag` (
  `id_cidade_tag` int(11) NOT NULL AUTO_INCREMENT,
  `numero` int(11) NOT NULL,
  `cod_ibge` int(11) NOT NULL,
  `cidade` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `estado` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `gentilico` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `mesoregiao` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `microregiao` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `area` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `densidade` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `populacao` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `idhm` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `pib` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `renda` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ano_estimativa` varchar(4) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id_cidade_tag`)
) ENGINE=InnoDB AUTO_INCREMENT=5572 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPRESSED;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cidades`
--

DROP TABLE IF EXISTS `cidades`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cidades` (
  `estados_cod_estados` int(11) DEFAULT NULL,
  `cod_cidades` int(11) DEFAULT NULL,
  `nome` varchar(72) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cep` varchar(8) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cidades_siconv`
--

DROP TABLE IF EXISTS `cidades_siconv`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cidades_siconv` (
  `Nome` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `Codigo` varchar(6) COLLATE utf8_unicode_ci NOT NULL,
  `id_cidade` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id_cidade`),
  UNIQUE KEY `Codigo` (`Codigo`),
  UNIQUE KEY `Codigo_2` (`Codigo`)
) ENGINE=InnoDB AUTO_INCREMENT=5583 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cnpj_aberto`
--

DROP TABLE IF EXISTS `cnpj_aberto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cnpj_aberto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idPessoa` int(11) NOT NULL,
  `cnpj` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `cidade` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `usuario_siconv` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `senha_siconv` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cnpj_master`
--

DROP TABLE IF EXISTS `cnpj_master`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cnpj_master` (
  `idPessoa` int(11) NOT NULL,
  `cnpj` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `idProposta` int(11) NOT NULL,
  PRIMARY KEY (`idPessoa`,`idProposta`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cnpj_siconv`
--

DROP TABLE IF EXISTS `cnpj_siconv`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cnpj_siconv` (
  `id_cnpj_siconv` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id do cnpj',
  `cnpj` varchar(14) COLLATE utf8_unicode_ci NOT NULL,
  `id_cidade` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_cnpj_siconv`),
  KEY `id_cidade` (`id_cidade`),
  CONSTRAINT `cnpj_siconv_fk_cidades_siconv` FOREIGN KEY (`id_cidade`) REFERENCES `cidades_siconv` (`id_cidade`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `codigos`
--

DROP TABLE IF EXISTS `codigos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `codigos` (
  `codigo` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `data` datetime NOT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `confirma_cadastro`
--

DROP TABLE IF EXISTS `confirma_cadastro`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `confirma_cadastro` (
  `confirma_cadastro_id` int(11) NOT NULL AUTO_INCREMENT,
  `email_usuario` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `nome_usuario` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `cpf_usuario` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `tem_login_siconv` tinyint(1) NOT NULL,
  `senha_usuario` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `confirmado` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`confirma_cadastro_id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cronograma`
--

DROP TABLE IF EXISTS `cronograma`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cronograma` (
  `idCronograma` int(11) NOT NULL AUTO_INCREMENT,
  `responsavel` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mes` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ano` varchar(5) COLLATE utf8_unicode_ci DEFAULT NULL,
  `parcela` double DEFAULT NULL,
  `valor_meta` double DEFAULT NULL,
  `Proposta_idProposta` int(11) NOT NULL,
  PRIMARY KEY (`idCronograma`),
  KEY `fk_Cronograma_Proposta1_idx` (`Proposta_idProposta`)
) ENGINE=InnoDB AUTO_INCREMENT=471 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cronograma_etapa`
--

DROP TABLE IF EXISTS `cronograma_etapa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cronograma_etapa` (
  `idCronograma_etapa` int(11) NOT NULL AUTO_INCREMENT,
  `Cronograma_meta_idCronograma_meta` int(11) NOT NULL,
  `Etapa_idEtapa` int(11) NOT NULL,
  `valor` double DEFAULT NULL,
  PRIMARY KEY (`idCronograma_etapa`),
  KEY `fk_Cronograma_etapa_Cronograma_meta1_idx` (`Cronograma_meta_idCronograma_meta`),
  KEY `fk_Cronograma_etapa_Etapa1_idx` (`Etapa_idEtapa`),
  CONSTRAINT `Cronograma_etapa_ibfk_1` FOREIGN KEY (`Cronograma_meta_idCronograma_meta`) REFERENCES `cronograma_meta` (`idCronograma_meta`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `Cronograma_etapa_ibfk_2` FOREIGN KEY (`Etapa_idEtapa`) REFERENCES `etapa` (`idEtapa`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=1321 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cronograma_meta`
--

DROP TABLE IF EXISTS `cronograma_meta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cronograma_meta` (
  `idCronograma_meta` int(11) NOT NULL AUTO_INCREMENT,
  `Cronograma_idCronograma` int(11) NOT NULL,
  `Meta_idMeta` int(11) NOT NULL,
  `valor` double DEFAULT NULL,
  PRIMARY KEY (`idCronograma_meta`),
  KEY `fk_Cronograma_meta_Cronograma1_idx` (`Cronograma_idCronograma`),
  KEY `fk_Cronograma_meta_Meta1_idx` (`Meta_idMeta`),
  CONSTRAINT `Cronograma_meta_ibfk_1` FOREIGN KEY (`Cronograma_idCronograma`) REFERENCES `cronograma` (`idCronograma`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `Cronograma_meta_ibfk_2` FOREIGN KEY (`Meta_idMeta`) REFERENCES `meta` (`idMeta`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=468 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `dados_cidade`
--

DROP TABLE IF EXISTS `dados_cidade`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dados_cidade` (
  `Cidade` varchar(21) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Estado` varchar(11) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Mesorregiao` varchar(29) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Territorio_Cidadania` varchar(35) COLLATE utf8_unicode_ci DEFAULT NULL,
  `populacao` int(6) DEFAULT NULL,
  `Area_Territorial` decimal(10,2) DEFAULT NULL,
  `Densidade` decimal(5,2) DEFAULT NULL,
  `Distancia_capital` int(4) DEFAULT NULL,
  `Per_capita` decimal(7,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `despesa`
--

DROP TABLE IF EXISTS `despesa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `despesa` (
  `idDespesa` int(11) NOT NULL AUTO_INCREMENT,
  `descricao` text COLLATE utf8_unicode_ci,
  `natureza_aquisicao` int(11) DEFAULT NULL,
  `natureza_despesa` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `natureza_despesa_descricao` text COLLATE utf8_unicode_ci,
  `fornecimento` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `total` double DEFAULT NULL,
  `quantidade` double DEFAULT NULL,
  `valor_unitario` double DEFAULT NULL,
  `endereco` text COLLATE utf8_unicode_ci,
  `cep` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `municipio` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `UF` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `observacao` text COLLATE utf8_unicode_ci,
  `Proposta_idProposta` int(11) NOT NULL,
  `Tipo_despesa_idTipo_despesa` int(11) NOT NULL,
  PRIMARY KEY (`idDespesa`),
  KEY `fk_Despesa_Proposta1_idx` (`Proposta_idProposta`),
  KEY `fk_Despesa_Tipo_despesa1_idx` (`Tipo_despesa_idTipo_despesa`),
  CONSTRAINT `fk_Despesa_Proposta1` FOREIGN KEY (`Proposta_idProposta`) REFERENCES `proposta` (`idProposta`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `fk_Despesa_Tipo_despesa1` FOREIGN KEY (`Tipo_despesa_idTipo_despesa`) REFERENCES `tipo_despesa` (`idTipo_despesa`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=862 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `documento`
--

DROP TABLE IF EXISTS `documento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `documento` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` tinyint(1) NOT NULL,
  `idPessoa` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `empenhos`
--

DROP TABLE IF EXISTS `empenhos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `empenhos` (
  `id_empenho` int(11) NOT NULL AUTO_INCREMENT,
  `id_proposta_siconv` int(11) DEFAULT NULL,
  `id_empenho_siconv` int(11) DEFAULT NULL,
  `especie_empenho` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tabela_cronograma_empenho` longtext COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id_empenho`)
) ENGINE=InnoDB AUTO_INCREMENT=23114 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `endereco`
--

DROP TABLE IF EXISTS `endereco`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `endereco` (
  `UF` int(11) DEFAULT NULL,
  `municipio_sigla` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `municipio_nome` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `endereco` text COLLATE utf8_unicode_ci,
  `cep` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Proposta_idProposta` int(11) NOT NULL,
  PRIMARY KEY (`Proposta_idProposta`),
  KEY `fk_Meta_Proposta1_idx` (`Proposta_idProposta`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `estados`
--

DROP TABLE IF EXISTS `estados`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `estados` (
  `cod_estados` int(11) DEFAULT NULL,
  `sigla` varchar(2) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nome` varchar(72) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `etapa`
--

DROP TABLE IF EXISTS `etapa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `etapa` (
  `idEtapa` int(11) NOT NULL AUTO_INCREMENT,
  `especificacao` text COLLATE utf8_unicode_ci,
  `fornecimento` varchar(145) COLLATE utf8_unicode_ci DEFAULT NULL,
  `total` double DEFAULT NULL,
  `quantidade` double DEFAULT NULL,
  `valorUnitario` double DEFAULT NULL,
  `data_inicio` date DEFAULT NULL,
  `data_termino` date DEFAULT NULL,
  `UF` int(11) DEFAULT NULL,
  `municipio_sigla` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `municipio_nome` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `endereco` text COLLATE utf8_unicode_ci,
  `cep` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Meta_idMeta` int(11) NOT NULL,
  PRIMARY KEY (`idEtapa`),
  KEY `fk_Etapa_Meta1_idx` (`Meta_idMeta`),
  CONSTRAINT `fk_Etapa_Meta1` FOREIGN KEY (`Meta_idMeta`) REFERENCES `meta` (`idMeta`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=807 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `gestor`
--

DROP TABLE IF EXISTS `gestor`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gestor` (
  `id_gestor` int(11) NOT NULL AUTO_INCREMENT,
  `validade` date NOT NULL COMMENT 'pode não conter uma data de validade',
  `quantidade_cnpj` int(11) unsigned NOT NULL DEFAULT '1' COMMENT 'quantidade de cnpjs disponiveis para o gestor e suas sub contas',
  `id_usuario` int(11) NOT NULL,
  `inicio_vigencia` date NOT NULL,
  `tipo_gestor` int(11) NOT NULL,
  PRIMARY KEY (`id_gestor`),
  UNIQUE KEY `id_usuario` (`id_usuario`),
  CONSTRAINT `gestor_fk_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `encarregado`
--

DROP TABLE IF EXISTS `encarregado`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `encarregado` (
 `id_encarregado` int(11) NOT NULL AUTO_INCREMENT,
 `nome` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
 `email` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
 `id_gestor` int(11) NOT NULL,
  PRIMARY KEY (`id_encarregado`),
  KEY `fk_encarregado_gestor_idx` (`id_gestor`),
  CONSTRAINT `fk_encarregado_gestor` FOREIGN KEY (`id_gestor`) REFERENCES `gestor` (`id_gestor`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `justificativa`
--

DROP TABLE IF EXISTS `justificativa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `justificativa` (
  `idJustificativa` int(11) NOT NULL AUTO_INCREMENT,
  `Justificativa` text COLLATE utf8_unicode_ci,
  `objeto` text COLLATE utf8_unicode_ci,
  `capacidade` text COLLATE utf8_unicode_ci,
  `Proposta_idProposta` int(11) NOT NULL,
  PRIMARY KEY (`idJustificativa`),
  KEY `fk_Justificativa_Proposta1_idx` (`Proposta_idProposta`),
  CONSTRAINT `fk_Justificativa_Proposta1` FOREIGN KEY (`Proposta_idProposta`) REFERENCES `proposta` (`idProposta`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=208 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `log`
--

DROP TABLE IF EXISTS `log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `log` (
  `id_log` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) DEFAULT NULL,
  `operacao` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id_log`),
  KEY `id_usuario` (`id_usuario`),
  CONSTRAINT `log_fk_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE SET NULL ON UPDATE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `log_propostas`
--

DROP TABLE IF EXISTS `log_propostas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `log_propostas` (
  `cnpj` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `ano` int(11) NOT NULL,
  `numero_total` int(11) NOT NULL,
  `numero_aprovados` int(11) NOT NULL,
  `valor_global` double NOT NULL,
  `valor_repasse` double NOT NULL,
  `valor_contra_partida` double NOT NULL,
  `data` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`cnpj`,`ano`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `log_sistema`
--

DROP TABLE IF EXISTS `log_sistema`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `log_sistema` (
  `id_log_sistema` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id para cada log do sistema',
  `operacao` varchar(200) COLLATE utf8_unicode_ci NOT NULL COMMENT 'descricao sobre a operação realizada',
  `descricao` varchar(250) COLLATE utf8_unicode_ci NOT NULL COMMENT 'descricao detalhada do evento executado',
  PRIMARY KEY (`id_log_sistema`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `log_trabalho`
--

DROP TABLE IF EXISTS `log_trabalho`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `log_trabalho` (
  `idLog_trabalho` int(11) NOT NULL AUTO_INCREMENT,
  `data_acao` datetime DEFAULT NULL,
  `Trabalho_idTrabalho` int(11) NOT NULL,
  `Status_idstatus` int(11) NOT NULL,
  `observacao` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`idLog_trabalho`),
  KEY `fk_Log_trabalho_Trabalho1_idx` (`Trabalho_idTrabalho`),
  KEY `fk_Log_trabalho_Status1_idx` (`Status_idstatus`),
  CONSTRAINT `fk_Log_trabalho_Status1` FOREIGN KEY (`Status_idstatus`) REFERENCES `status` (`idstatus`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `fk_Log_trabalho_Trabalho1` FOREIGN KEY (`Trabalho_idTrabalho`) REFERENCES `trabalho` (`idTrabalho`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `meta`
--

DROP TABLE IF EXISTS `meta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `meta` (
  `idMeta` int(11) NOT NULL AUTO_INCREMENT,
  `especificacao` text COLLATE utf8_unicode_ci,
  `fornecimento` varchar(145) COLLATE utf8_unicode_ci DEFAULT NULL,
  `total` double DEFAULT NULL,
  `quantidade` double DEFAULT NULL,
  `valorUnitario` double DEFAULT NULL,
  `data_inicio` date DEFAULT NULL,
  `data_termino` date DEFAULT NULL,
  `UF` int(11) DEFAULT NULL,
  `municipio_sigla` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `municipio_nome` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `endereco` text COLLATE utf8_unicode_ci,
  `cep` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Proposta_idProposta` int(11) NOT NULL,
  PRIMARY KEY (`idMeta`),
  KEY `fk_Meta_Proposta1_idx` (`Proposta_idProposta`),
  CONSTRAINT `fk_Meta_Proposta1` FOREIGN KEY (`Proposta_idProposta`) REFERENCES `proposta` (`idProposta`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=241 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `nivel_usuario`
--

DROP TABLE IF EXISTS `nivel_usuario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `nivel_usuario` (
  `id_nivel_usuario` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id utilizado na ligação com o usuário',
  `descricao` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'descrição breve sobre o nível de usuários.',
  `nome` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT 'nome do nível.',
  PRIMARY KEY (`id_nivel_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `obras`
--

DROP TABLE IF EXISTS `obras`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `obras` (
  `Nome` varchar(41) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Codigo` int(8) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `orgaos`
--

DROP TABLE IF EXISTS `orgaos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `orgaos` (
  `codigo` int(5) NOT NULL DEFAULT '0',
  `nome` varchar(68) COLLATE utf8_unicode_ci DEFAULT NULL,
  `superior` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `outros`
--

DROP TABLE IF EXISTS `outros`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `outros` (
  `Nome` varchar(43) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Codigo` int(8) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `parecer_proposta`
--

DROP TABLE IF EXISTS `parecer_proposta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `parecer_proposta` (
  `id_parecer_proposta` int(11) NOT NULL AUTO_INCREMENT,
  `data_parecer` date NOT NULL,
  `id_proposta` int(11) NOT NULL,
  `id_parecer` int(11) NOT NULL,
  `parecer` longtext COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id_parecer_proposta`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `parecer_proposta_banco_proposta`
--

DROP TABLE IF EXISTS `parecer_proposta_banco_proposta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `parecer_proposta_banco_proposta` (
  `id_parecer_proposta` int(11) NOT NULL AUTO_INCREMENT,
  `data_parecer` date DEFAULT NULL,
  `id_proposta` int(11) DEFAULT NULL,
  `id_parecer` int(11) DEFAULT NULL,
  `parecer` longtext COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id_parecer_proposta`)
) ENGINE=InnoDB AUTO_INCREMENT=209935 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `permissoes_usuario`
--

DROP TABLE IF EXISTS `permissoes_usuario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `permissoes_usuario` (
  `id_permissoes_usuario` int(11) NOT NULL AUTO_INCREMENT,
  `consultar_programa` tinyint(1) NOT NULL DEFAULT '0',
  `relatorio_programa` tinyint(1) NOT NULL DEFAULT '0',
  `criar_usuario` tinyint(1) NOT NULL DEFAULT '0',
  `editar_usuario` tinyint(1) NOT NULL DEFAULT '0',
  `ativar_usuario` tinyint(1) NOT NULL DEFAULT '0',
  `vincular_cnpj_usuario` tinyint(1) NOT NULL DEFAULT '0',
  `editar_cnpj_usuario` tinyint(1) NOT NULL DEFAULT '0',
  `desativar_usuario` tinyint(1) NOT NULL DEFAULT '0',
  `criar_projeto` tinyint(1) NOT NULL DEFAULT '0',
  `editar_projeto` tinyint(1) NOT NULL DEFAULT '0',
  `alterar_end_projeto` tinyint(1) NOT NULL DEFAULT '0',
  `apagar_projeto` tinyint(1) NOT NULL DEFAULT '0',
  `apagar_projeto_padrao` tinyint(1) NOT NULL DEFAULT '0',
  `tornar_proj_padrao` tinyint(1) NOT NULL DEFAULT '0',
  `utilizar_padrao` tinyint(1) NOT NULL DEFAULT '0',
  `duplicar_projeto` tinyint(1) NOT NULL DEFAULT '0',
  `exportar_siconv` tinyint(1) NOT NULL DEFAULT '0',
  `consultar_proposta` tinyint(1) NOT NULL DEFAULT '0',
  `relatorio_proposta` tinyint(1) NOT NULL DEFAULT '0',
  `status_proposta` tinyint(1) NOT NULL DEFAULT '0',
  `parecer_proposta` tinyint(1) NOT NULL DEFAULT '0',
  `id_usuario` int(11) NOT NULL,
  PRIMARY KEY (`id_permissoes_usuario`),
  UNIQUE KEY `id_usuario` (`id_usuario`),
  CONSTRAINT `permissoes_usuario_fk_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `pessoa`
--

DROP TABLE IF EXISTS `pessoa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pessoa` (
  `idPessoa` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `pessoa` int(11) NOT NULL,
  `identificacao` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `login` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `senha` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `telefone` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `tipoPessoa` smallint(6) NOT NULL,
  `escolaridade` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nomeInstituicao` varchar(245) COLLATE utf8_unicode_ci DEFAULT NULL,
  `endereco` text COLLATE utf8_unicode_ci,
  `ativo` tinyint(1) DEFAULT NULL,
  `validade` date NOT NULL,
  `quantidade` int(11) NOT NULL,
  `idGestor` int(11) NOT NULL,
  PRIMARY KEY (`idPessoa`),
  UNIQUE KEY `login_UNIQUE` (`login`),
  UNIQUE KEY `login` (`login`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `proponentes_municipios`
--

DROP TABLE IF EXISTS `proponentes_municipios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `proponentes_municipios` (
  `id` int(13) DEFAULT NULL,
  `uri` varchar(62) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cep` int(8) DEFAULT NULL,
  `cnpj` varchar(18) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `cpf_responsavel` int(11) DEFAULT NULL,
  `endereco` varchar(88) COLLATE utf8_unicode_ci DEFAULT NULL,
  `esfera_administrativa` varchar(72) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fax` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `href_convenios` varchar(84) COLLATE utf8_unicode_ci DEFAULT NULL,
  `href_propostas` varchar(84) COLLATE utf8_unicode_ci DEFAULT NULL,
  `inscricao_estadual` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `inscricao_municipal` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `municipio` varchar(62) COLLATE utf8_unicode_ci DEFAULT NULL,
  `natureza_juridica` varchar(68) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nome` varchar(58) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nome_responsavel` varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL,
  `telefone` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `estado` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`cnpj`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `proposta`
--

DROP TABLE IF EXISTS `proposta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `proposta` (
  `idProposta` int(11) NOT NULL AUTO_INCREMENT,
  `nome_programa` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `codigo_programa` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `programa` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cidade` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nome` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `percentual` decimal(10,2) DEFAULT NULL,
  `valor_global` decimal(11,2) DEFAULT NULL,
  `total_contrapartida` decimal(11,2) DEFAULT NULL,
  `contrapartida_financeira` decimal(11,2) DEFAULT NULL,
  `contrapartida_bens` decimal(11,2) DEFAULT NULL,
  `repasse` decimal(11,2) DEFAULT NULL,
  `repasse_voluntario` decimal(11,2) DEFAULT NULL,
  `agencia` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `data` date DEFAULT NULL,
  `data_inicio` date DEFAULT NULL,
  `data_termino` date DEFAULT NULL,
  `idGestor` int(11) DEFAULT NULL,
  `banco` int(11) NOT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT '1',
  `proponente` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `orgao` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `id_programa` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `usuario_siconv` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `senha_siconv` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `enviado` tinyint(1) NOT NULL,
  `id_siconv` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `padrao` tinyint(1) NOT NULL,
  `area` int(11) NOT NULL,
  `validade` int(11) NOT NULL,
  `objeto` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `qualificacao` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `id_proposta_atual` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `id_proposta_efetiva` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `situacao` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `parecer_proposta` longtext COLLATE utf8_unicode_ci,
  `parecer_plano_trabalho` longtext COLLATE utf8_unicode_ci,
  `parecer_solicitacoes` longtext COLLATE utf8_unicode_ci,
  `data_update_status` datetime DEFAULT NULL,
  `empenhado` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`idProposta`),
  KEY `fk_Proposta_Banco1_idx` (`banco`),
  CONSTRAINT `fk_Proposta_Banco1` FOREIGN KEY (`banco`) REFERENCES `banco` (`idbanco`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=85 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Table structure for table `programa_proposta`
--

DROP TABLE IF EXISTS `programa_proposta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `programa_proposta` (
  `id_programa_proposta` int(11) NOT NULL AUTO_INCREMENT,
  `id_proposta` int(11) NOT NULL,
  `nome_programa` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `codigo_programa` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `programa` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `percentual` DOUBLE DEFAULT NULL,
  `valor_global` decimal(11,2) DEFAULT NULL,
  `total_contrapartida` decimal(11,2) DEFAULT NULL,
  `contrapartida_financeira` decimal(11,2) DEFAULT NULL,
  `contrapartida_bens` decimal(11,2) DEFAULT NULL,
  `repasse` decimal(11,2) DEFAULT NULL,
  `repasse_voluntario` decimal(11,2) DEFAULT NULL,
  `id_programa` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `objeto` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `qualificacao` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id_programa_proposta`),
  KEY `fk_Programa_Proposta_idx` (`id_proposta`),
  CONSTRAINT `fk_Programa_Proposta` FOREIGN KEY (`id_proposta`) REFERENCES `proposta` (`idProposta`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `servicos`
--

DROP TABLE IF EXISTS `servicos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `servicos` (
  `Nome` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Codigo` int(8) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sessao`
--

DROP TABLE IF EXISTS `sessao`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sessao` (
  `session_id` varchar(40) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `user_data` longtext COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`session_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `siconv_beneficiario`
--

DROP TABLE IF EXISTS `siconv_beneficiario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `siconv_beneficiario` (
  `codigo_programa` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `cnpj` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `nome` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `valor` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `emenda` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `parlamentar` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`codigo_programa`,`cnpj`,`nome`,`valor`,`emenda`,`parlamentar`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `siconv_programa`
--

DROP TABLE IF EXISTS `siconv_programa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `siconv_programa` (
  `codigo` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `nome` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `orgao` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `orgao_vinculado` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `qualificacao` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `atende` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `descricao` text COLLATE utf8_unicode_ci,
  `observacao` text COLLATE utf8_unicode_ci,
  `data_inicio` date DEFAULT NULL,
  `data_fim` date DEFAULT NULL,
  `ano` int(11) DEFAULT NULL,
  `estados` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `link` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `siconv_proposta`
--

DROP TABLE IF EXISTS `siconv_proposta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `siconv_proposta` (
  `id` int(11) NOT NULL,
  `numero` int(11) NOT NULL,
  `programa` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `siconv_usuario_programa`
--

DROP TABLE IF EXISTS `siconv_usuario_programa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `siconv_usuario_programa` (
  `idPessoa` int(11) NOT NULL DEFAULT '0',
  `codigoPrograma` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `aceito` tinyint(1) NOT NULL DEFAULT '0',
  `acesso` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`idPessoa`,`codigoPrograma`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `status`
--

DROP TABLE IF EXISTS `status`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `status` (
  `idstatus` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`idstatus`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `system_logs`
--

DROP TABLE IF EXISTS `system_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `system_logs` (
  `system_log_id` int(11) NOT NULL AUTO_INCREMENT,
  `acao` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `data` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`system_log_id`),
  KEY `id_usuario` (`id_usuario`),
  CONSTRAINT `system_logs_fk_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=1612 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci PACK_KEYS=0 ROW_FORMAT=COMPACT;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tipo_despesa`
--

DROP TABLE IF EXISTS `tipo_despesa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tipo_despesa` (
  `idTipo_despesa` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `valor` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`idTipo_despesa`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tipo_pessoa`
--

DROP TABLE IF EXISTS `tipo_pessoa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tipo_pessoa` (
  `idPessoa` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`idPessoa`)
) ENGINE=InnoDB AUTO_INCREMENT=100 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tipo_trabalho`
--

DROP TABLE IF EXISTS `tipo_trabalho`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tipo_trabalho` (
  `idTrabalho` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nomenclatura` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`idTrabalho`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `trabalho`
--

DROP TABLE IF EXISTS `trabalho`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `trabalho` (
  `idTrabalho` int(11) NOT NULL AUTO_INCREMENT,
  `Status_idstatus` int(11) NOT NULL,
  `Tipo_trabalho_idTrabalho` int(11) NOT NULL,
  `Pessoa_idPessoa` int(11) DEFAULT NULL,
  `id_correspondente` int(11) DEFAULT NULL,
  `data` date DEFAULT NULL,
  PRIMARY KEY (`idTrabalho`),
  KEY `fk_Trabalho_Status1_idx` (`Status_idstatus`),
  KEY `fk_Trabalho_Tipo_trabalho1_idx` (`Tipo_trabalho_idTrabalho`),
  CONSTRAINT `fk_Trabalho_Status1` FOREIGN KEY (`Status_idstatus`) REFERENCES `status` (`idstatus`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `fk_Trabalho_Tipo_trabalho1` FOREIGN KEY (`Tipo_trabalho_idTrabalho`) REFERENCES `tipo_trabalho` (`idTrabalho`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=924 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `transacoes`
--

DROP TABLE IF EXISTS `transacoes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `transacoes` (
  `id` int(11) NOT NULL,
  `status` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `tipo` int(11) NOT NULL,
  `idPessoa` int(11) NOT NULL,
  `horario` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tributos`
--

DROP TABLE IF EXISTS `tributos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tributos` (
  `Nome` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Codigo` int(8) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `unidade_fornecimento`
--

DROP TABLE IF EXISTS `unidade_fornecimento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `unidade_fornecimento` (
  `Codigo` varchar(8) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Nome` varchar(26) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `usuario`
--

DROP TABLE IF EXISTS `usuario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usuario` (
  `id_usuario` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id do usuario no sistema',
  `nome` varchar(200) COLLATE utf8_unicode_ci NOT NULL COMMENT 'nome do usuario - será aceito nome repetido',
  `email` varchar(200) COLLATE utf8_unicode_ci NOT NULL COMMENT 'email do usuário - obrigatório e não será aceito nome repetido',
  `telefone` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'telefone - pode ser repetido e pode não existir',
  `celular` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'igual ao telefone. Lembrando que são 10 numeros com ddd',
  `login` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT 'login no sistema deve ser unico e não nulo',
  `senha` varchar(200) COLLATE utf8_unicode_ci NOT NULL COMMENT 'senha deve ser unica e não nula',
  `login_siconv` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'login no siconv',
  `senha_siconv` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'senha no siconv',
  `id_nivel` int(11) DEFAULT NULL,
  `primeiro_acesso` varchar(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'S',
  `status` varchar(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'A',
  `desativado_gestor` varchar(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `entidade` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id_usuario`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `login` (`login`),
  UNIQUE KEY `email_2` (`email`),
  UNIQUE KEY `login_2` (`login`),
  KEY `id_nivel` (`id_nivel`),
  CONSTRAINT `usuario_fk_nivel` FOREIGN KEY (`id_nivel`) REFERENCES `nivel_usuario` (`id_nivel_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Table structure for table `usuario_cnpj`
--

DROP TABLE IF EXISTS `usuario_cnpj`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usuario_cnpj` (
  `id_usuario` int(11) NOT NULL,
  `id_cnpj` int(11) NOT NULL,
  KEY `id_usuario` (`id_usuario`),
  KEY `id_cnpj` (`id_cnpj`),
  CONSTRAINT `usuario_cnpj_fk_cnpj` FOREIGN KEY (`id_cnpj`) REFERENCES `cnpj_siconv` (`id_cnpj_siconv`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `usuario_cnpj_fk_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `usuario_gestor`
--

DROP TABLE IF EXISTS `usuario_gestor`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usuario_gestor` (
  `id_usuario` int(11) NOT NULL,
  `id_gestor` int(11) NOT NULL,
  UNIQUE KEY `id_usuario` (`id_usuario`),
  KEY `id_gestor` (`id_gestor`),
  CONSTRAINT `usuario_gestor_fk_gestor` FOREIGN KEY (`id_gestor`) REFERENCES `gestor` (`id_gestor`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `usuario_gestor_fk_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `usuario_realizou_cadastro`
--

DROP TABLE IF EXISTS `usuario_realizou_cadastro`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usuario_realizou_cadastro` (
  `id_usuario_cadastrado` int(11) NOT NULL,
  `id_usuario_cadastrou` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `info_proposta_comercial`
--

DROP TABLE IF EXISTS `info_proposta_comercial`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `info_proposta_comercial` (
  `id_info_proposta_comercial` int(11) NOT NULL AUTO_INCREMENT,
  `tipo_proposta` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `num_cnpjs` int(11) NOT NULL,
  `alvo_populacao` decimal(15,1) DEFAULT NULL,
  `entidade` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `valor_um_ano` decimal(20,2) NOT NULL,
  `valor_dois_anos` decimal(20,2) NOT NULL,
  PRIMARY KEY (`id_info_proposta_comercial`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `proposta_comercial`
--

DROP TABLE IF EXISTS `proposta_comercial`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `proposta_comercial` (
  `id_proposta_comercial` int(11) NOT NULL AUTO_INCREMENT,
  `cnpj_proposta_comercial` varchar(14) COLLATE utf8_unicode_ci NOT NULL,
  `valor_proposta_comercial` decimal(20,2) NOT NULL,
  `entrada_proposta_comercial` decimal(20,2) NOT NULL,
  `parcelas_proposta_comercial` int(11) NOT NULL,
  `periodo_proposta_comercial` int(11) NOT NULL,
  `data_cadastro` date NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `tipo_proposta` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `num_cnpj` int(11) NOT NULL DEFAULT '0',
  `entidade_alvo` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `descricao_proposta_comercial` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `valor_adicional` decimal(20,2) NOT NULL,
  PRIMARY KEY (`id_proposta_comercial`),
  KEY `fk_id_usuario_idx` (`id_usuario`),
  CONSTRAINT `fk_id_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

create table proponente_siconv(
	id_proponente_siconv integer auto_increment not null primary key,
	cnpj varchar(18) not null,
	nome varchar(255) not null,
	esfera_administrativa varchar(255) not null,
	codigo_municipio varchar(40) not null,
	municipio varchar(255) not null,
	municipio_uf_sigla varchar(2) not null,
	municipio_uf_nome varchar(60) not null,
	municipio_uf_regiao varchar(2) not null,
	endereco varchar(255) not null,
	cep varchar(8) not null,
	nome_responsavel varchar(255) not null,
	telefone varchar(30) not null,
	fax varchar(30) not null,
	natureza_juridica varchar(255) not null,
	inscricao_estadual varchar(255) not null,
	inscricao_municipal varchar(255) not null
);

create table cnpj_vendedores(
	id_usuario integer not null,
	cnpj_vinculado varchar(14) not null
);

alter table parecer_proposta_banco_proposta add column `visualizado_em` date DEFAULT NULL;

ALTER TABLE `banco_proposta` 
ADD INDEX `idx_ano` (`ano` ASC),
ADD INDEX `idx_proponente` (`proponente` ASC),
ADD INDEX `idx_codigo_siconv` (`codigo_siconv` ASC);

ALTER TABLE `proposta` 
ADD INDEX `idx_data` (`data` ASC),
ADD INDEX `idx_padrao` (`padrao` ASC),
ADD INDEX `idx_enviado` (`enviado` ASC),
ADD INDEX `idx_ativo` (`ativo` ASC);

ALTER TABLE `parecer_proposta_banco_proposta` 
ADD INDEX `idx_id_proposta` (`id_proposta` ASC),
ADD INDEX `idx_id_parecer` (`id_parecer` ASC);

/* Alterando campos double para decimal */
/*
ALTER TABLE `cronograma` MODIFY COLUMN `parcela` DECIMAL(11,2),
MODIFY COLUMN `valor_meta` DECIMAL(11,2);

ALTER TABLE `cronograma_etapa` MODIFY COLUMN `valor` DECIMAL(11,2);

ALTER TABLE `cronograma_meta` MODIFY COLUMN `valor` DECIMAL(11,2);

ALTER TABLE `despesa` MODIFY COLUMN `total` DECIMAL(11,2),
MODIFY COLUMN `quantidade` DECIMAL(11,2),
MODIFY COLUMN `valor_unitario` DECIMAL(11,2);

ALTER TABLE `etapa` MODIFY COLUMN `total` DECIMAL(11,2),
MODIFY COLUMN `quantidade` DECIMAL(11,2),
MODIFY COLUMN `valorUnitario` DECIMAL(11,2);

ALTER TABLE `log_propostas` MODIFY COLUMN `valor_global` DECIMAL(11,2),
MODIFY COLUMN `valor_repasse` DECIMAL(11,2),
MODIFY COLUMN `valor_contra_partida` DECIMAL(11,2);

ALTER TABLE `meta` MODIFY COLUMN `total` DECIMAL(11,2),
MODIFY COLUMN `quantidade` DECIMAL(11,2),
MODIFY COLUMN `valorUnitario` DECIMAL(11,2);
*/

/* Alterando a tabela de proposta */
ALTER TABLE `proposta` 
CHANGE COLUMN `codigo_programa` `codigo_programa` VARCHAR(255) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NULL DEFAULT NULL ,
CHANGE COLUMN `objeto` `objeto` VARCHAR(100) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NULL DEFAULT NULL ,
CHANGE COLUMN `qualificacao` `qualificacao` VARCHAR(100) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NULL DEFAULT NULL ;

/* Alterando as tabelas para colocar o campo com o status de enviado para o siconv */
ALTER TABLE `proposta`
ADD COLUMN `exportado_siconv` TINYINT(1) DEFAULT 0 AFTER `empenhado`;

ALTER TABLE `cronograma`
ADD COLUMN `exportado_siconv` TINYINT(1) DEFAULT 0 AFTER `Proposta_idProposta`;

ALTER TABLE `cronograma_etapa`
ADD COLUMN `exportado_siconv` TINYINT(1) DEFAULT 0 AFTER `valor`;

ALTER TABLE `cronograma_meta`
ADD COLUMN `exportado_siconv` TINYINT(1) DEFAULT 0 AFTER `valor`;

ALTER TABLE `despesa` 
ADD COLUMN `exportado_siconv` TINYINT(1) NULL DEFAULT 0 AFTER `Tipo_despesa_idTipo_despesa`,
ADD COLUMN `numero_programa_siconv` INT(11) NULL DEFAULT NULL AFTER `exportado_siconv`,
ADD COLUMN `id_programa` VARCHAR(50) NULL DEFAULT NULL AFTER `numero_programa_siconv`,
ADD COLUMN `id_programa_cadastrado` VARCHAR(50) NULL DEFAULT NULL AFTER `id_programa`;

ALTER TABLE `etapa`
ADD COLUMN `exportado_siconv` TINYINT(1) DEFAULT 0 AFTER `Meta_idMeta`;

ALTER TABLE `meta` 
ADD COLUMN `exportado_siconv` TINYINT(1) NULL DEFAULT 0 AFTER `Proposta_idProposta`,
ADD COLUMN `numero_programa_siconv` INT(11) NULL DEFAULT NULL AFTER `exportado_siconv`,
ADD COLUMN `id_programa` VARCHAR(50) NULL DEFAULT NULL AFTER `numero_programa_siconv`,
ADD COLUMN `id_programa_cadastrado` VARCHAR(50) NULL DEFAULT NULL AFTER `id_programa`;

ALTER TABLE `justificativa`
ADD COLUMN `necessita_completar` TINYINT(1) NULL DEFAULT 0 AFTER `Proposta_idProposta`;

ALTER TABLE `proposta` 
DROP FOREIGN KEY `fk_Proposta_Banco1`;
ALTER TABLE `proposta` 
CHANGE COLUMN `banco` `banco` INT(11) NULL DEFAULT NULL ;
ALTER TABLE `proposta` 
ADD CONSTRAINT `fk_Proposta_Banco1`
  FOREIGN KEY (`banco`)
  REFERENCES `banco` (`idbanco`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;

ALTER TABLE `proposta` 
CHANGE COLUMN `percentual` `percentual` DOUBLE NULL DEFAULT NULL ;

ALTER TABLE `programa_proposta` 
CHANGE COLUMN `percentual` `percentual` DOUBLE NULL DEFAULT NULL ;

ALTER TABLE `proposta` 
ADD COLUMN `virou_padrao` TINYINT(1) NULL DEFAULT 0;

ALTER TABLE `proposta` 
ADD COLUMN `era_padrao` TINYINT(1) NULL DEFAULT 0 AFTER `virou_padrao`;

ALTER TABLE `proposta` 
ADD COLUMN `data_envio` DATE NULL AFTER `era_padrao`;

ALTER TABLE `cnpj_siconv` 
ADD COLUMN `cnpj_instituicao` VARCHAR(100) NULL AFTER `id_cidade`;
  
ALTER TABLE `proposta` 
CHANGE COLUMN `nome` `nome` VARCHAR(190) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NULL DEFAULT NULL ;

ALTER TABLE `siconv_beneficiario` 
ADD COLUMN `data_inicio_benef` DATE NULL AFTER `parlamentar`,
ADD COLUMN `data_fim_benef` DATE NULL AFTER `data_inicio_benef`,
ADD COLUMN `data_inicio_parlam` DATE NULL AFTER `data_fim_benef`,
ADD COLUMN `data_fim_parlam` DATE NULL AFTER `data_inicio_parlam`;


ALTER TABLE `siconv_programa` 
ADD COLUMN `data_inicio_benef` DATE NULL,
ADD COLUMN `data_fim_benef` DATE NULL,
ADD COLUMN `data_inicio_parlam` DATE NULL,
ADD COLUMN `data_fim_parlam` DATE NULL,
ADD COLUMN `data_disp` DATE NULL AFTER `data_fim_parlam`,
ADD COLUMN `data_renov_disp` DATE NULL AFTER `data_disp`;

ALTER TABLE `proposta_comercial` 
ADD COLUMN `num_cnpj_autarquias` INT(11) NULL DEFAULT 0 AFTER `valor_adicional`,
ADD COLUMN `valor_adicional_autarquias` DECIMAL(20,2) NULL AFTER `num_cnpj_autarquias`,
ADD COLUMN `num_cnpj_sem_fim` INT(11) NULL DEFAULT 0 AFTER `valor_adicional_autarquias`,
ADD COLUMN `valor_adicional_sem_fim` DECIMAL(20,2) NULL AFTER `num_cnpj_sem_fim`,
ADD COLUMN `nome_entidade` VARCHAR(255) NULL AFTER `valor_adicional_sem_fim`;

ALTER TABLE `cnpj_siconv` 
ADD COLUMN `sigla` VARCHAR(2) NULL AFTER `cnpj_instituicao`,
ADD COLUMN `esfera_administrativa` VARCHAR(45) NULL AFTER `sigla`;

ALTER TABLE `cnpj_siconv` 
DROP FOREIGN KEY `cnpj_siconv_fk_cidades_siconv`;

ALTER TABLE `cidades_siconv` 
CHANGE COLUMN `Codigo` `Codigo` VARCHAR(6) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL FIRST,
ADD COLUMN `Sigla` VARCHAR(2) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL AFTER `Nome`;

ALTER TABLE `usuario` 
ADD COLUMN `data_cadastro` DATE NULL AFTER `entidade`;

ALTER TABLE `proponente_siconv` 
ADD INDEX `idx_municipio` (`codigo_municipio` ASC),
ADD INDEX `idx_municipio_uf_sigla` (`municipio_uf_sigla` ASC),
ADD INDEX `idx_esfera_administrativa` (`esfera_administrativa` ASC),
ADD INDEX `idx_cnpj` (`cnpj` ASC);

ALTER TABLE `siconv_programa` 
ADD COLUMN `tem_atualizacao` TINYINT NULL AFTER `data_renov_disp`;

ALTER TABLE `parecer_proposta_banco_proposta` 
ADD COLUMN `tem_anexo` TINYINT(1) NULL AFTER `visualizado_em`;

ALTER TABLE `parecer_proposta` 
ADD COLUMN `tem_anexo` TINYINT(1) NULL AFTER `parecer`;

ALTER TABLE `banco_proposta` 
ADD COLUMN `parecer_plano_trabalho` LONGTEXT NULL AFTER `empenhado`;

CREATE TABLE `parecer_plano_trabalho_banco_proposta` (
  `id_parecer_proposta` int(11) NOT NULL AUTO_INCREMENT,
  `data_parecer` date DEFAULT NULL,
  `id_proposta` int(11) DEFAULT NULL,
  `id_parecer` int(11) DEFAULT NULL,
  `parecer` longtext COLLATE utf8_unicode_ci,
  `tem_anexo` tinyint(1),
  `visualizado_em` date,
  PRIMARY KEY (`id_parecer_proposta`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `banco_proposta` 
ADD COLUMN `parecer_ajuste_plano_trabalho` LONGTEXT NULL AFTER `parecer_plano_trabalho`;

CREATE TABLE `parecer_ajuste_pl_trabalho_banco_proposta` (
  `id_parecer_proposta` int(11) NOT NULL AUTO_INCREMENT,
  `data_parecer` date DEFAULT NULL,
  `id_proposta` int(11) DEFAULT NULL,
  `id_parecer` int(11) DEFAULT NULL,
  `parecer` longtext COLLATE utf8_unicode_ci,
  `tem_anexo` tinyint(1),
  `visualizado_em` date,
  PRIMARY KEY (`id_parecer_proposta`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `proponente_siconv` 
ADD COLUMN `situacao` VARCHAR(255) NULL AFTER `inscricao_municipal`;

create table situacao_proponente_siconv(
	id_situacao_proponente_siconv int not null primary key auto_increment,
	data_inicio date,
    data_vencimento date,
    situacao varchar(70),
    cnpj varchar(18)
);

ALTER TABLE `arquivos` 
ADD COLUMN `tipo_arquivo` VARCHAR(1) NULL DEFAULT 'D' AFTER `data_hora_envio`;

ALTER TABLE `system_logs` 
CHANGE COLUMN `acao` `acao` TEXT CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL ;

ALTER TABLE `banco_proposta` 
ADD INDEX `idx_codigo_programa` (`codigo_programa` ASC);

create table estados_direito_vendedor(
	id_vendedor int not null,
    estado_sigla varchar(2) not null
);

create table esfadm_direito_vendedor(
	id_vendedor int not null,
    esfera_administrativa varchar(255) not null
);

create table municipios_direito_vendedor(
	id_vendedor int not null,
    municipio varchar(255) not null
);

create table proponente_direito_vendedor (
	id_vendedor int not null,
    proponente varchar(25) not null
);

ALTER TABLE `siconv_programa` 
ADD COLUMN `programa_novo` TINYINT(1) NULL DEFAULT 1 AFTER `tem_atualizacao`;

ALTER TABLE `usuario` 
ADD COLUMN `usuario_novo` VARCHAR(1) NULL DEFAULT 'S' AFTER `data_cadastro`;

ALTER TABLE `usuario` 
CHANGE COLUMN `telefone` `telefone` VARCHAR(11) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NULL COMMENT 'telefone - pode ser repetido e pode não existir' ,
CHANGE COLUMN `celular` `celular` VARCHAR(11) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NULL DEFAULT NULL COMMENT 'igual ao telefone. Lembrando que são 10 numeros com ddd' ;

ALTER TABLE `proposta` 
ADD COLUMN `repasse_especifico` DECIMAL(11,2) NULL AFTER `repasse_voluntario`;

ALTER TABLE `programa_proposta` 
ADD COLUMN `repasse_especifico` DECIMAL(11,2) NULL AFTER `repasse_voluntario`;

create table guarda_senha(
	login varchar(100),
    senha varchar(200),
    municipio int,
    cnpj varchar(14)
);

ALTER TABLE `encarregado` 
ADD COLUMN `funcao` VARCHAR(250) NULL AFTER `id_gestor`;

ALTER TABLE `usuario` 
ADD COLUMN `vendedor_visita` VARCHAR(1) NULL AFTER `usuario_novo`;

ALTER TABLE `proposta` 
ADD COLUMN `enviado_email_aprovado` VARCHAR(1) NULL AFTER `banco_atende`;

create table dados_rel_capacidade(
	id_rel int not null auto_increment primary key,
    descricao_rel varchar(100) not null,
    estado varchar(50) not null,
    municipio int(11) not null,
    nome_prefeito varchar(255) not null,
    codigo_programa varchar(20) not null,
    nome_programa varchar(255) not null,
    nome_engenheiro varchar(255),
    crea_engenheiro varchar(30),
    id_usuario int(11) not null,
    tipo_assinatura varchar(30),
    arquivo_assinatura varchar(155),
    brasao_prefeitura varchar(155)
);

create table dados_rel_contrapartida(
	id_rel int not null auto_increment primary key,
    descricao_rel varchar(100) not null,
    estado varchar(50) not null,
    municipio int(11) not null,
    nome_prefeito varchar(255) not null,
    codigo_programa varchar(20) not null,
    nome_programa varchar(255) not null,
    valor_contrapartida double(15,2) not null,
    vlr_extenso_contrapartida varchar(255) not null,
    id_usuario int(11) not null,
    tipo_assinatura varchar(30),
    arquivo_assinatura varchar(155),
    brasao_prefeitura varchar(155),
    num_lei varchar(25) not null,
    data_pub_lei date not null,
    ano_loa varchar(4) not null,
    orgao varchar(255) not null,
    unidade varchar(255) not null,
    proj_atividade varchar(255) not null,
    nat_despesa varchar(255) not null
);

create table `contato_municipio`(
	`id_contato_municipio` int not null primary key auto_increment,
    `nome_contato` varchar(150),
    `email_contato` varchar(150),
    `telefone_contato` varchar(11),
    `id_municipio` varchar(40),
    `sigla_uf` varchar(2),
    `id_usuario_cadastrou` int,
    `data_cadastro` date
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

create table cnpj_contato_municipio(
	`id_contato_municipio` int not null,
	`cnpj_contato` VARCHAR(18) not null
);

create table historico_contato_municipio(
	`id_historico_contato_municipio` int not null primary key auto_increment,
	`data_retorno` date,
	`obs_gerais` TEXT,
	`status_contato` VARCHAR(30) not null,
	`class_contato` VARCHAR(1),
	`id_contato_municipio` int not null
);

create table sugestao(
	id_sugestao int not null primary key auto_increment,
    sugestao text not null,
    id_municipio int not null,
    uf varchar(2) not null,
    id_usuario int not null,
    data_envio datetime not null
);

create table resposta_sugestao(
	id_resposta_sugestao int not null primary key auto_increment,
    resposta_sugestao text not null,
    id_sugestao int not null,
    data_envio datetime not null,
    id_usuario int not null
);

ALTER TABLE `gestor` 
ADD COLUMN `nivel_gestor` VARCHAR(1) NULL DEFAULT 'C' AFTER `tipo_gestor`,
ADD COLUMN `numero_parlamentar` VARCHAR(20) NULL AFTER `nivel_gestor`;


ALTER TABLE `usuario`
ADD COLUMN `vendedor_codigo_parlamentar` VARCHAR(50) NULL;

INSERT INTO `nivel_usuario` (`descricao`, `nome`) VALUES ('Prefeito. Visualização e acompanhamento dos processos', 'PREFEITO');

create table nomes_parlamentar(
    codigo_parlamentar varchar(255),
    nome_parlamentar varchar(255)
);

ALTER TABLE `usuario` 
DROP INDEX `login_2` ,
DROP INDEX `login` ,
DROP INDEX `email_2` ,
DROP INDEX `email` ;

ALTER TABLE `usuario` 
ADD COLUMN `usuario_sistema` VARCHAR(1) NULL DEFAULT 'T' AFTER `vendedor_visita`;

ALTER TABLE `nomes_parlamentar` 
ADD COLUMN `cargo_parlamentar` VARCHAR(255) NULL AFTER `nome_parlamentar`;

ALTER TABLE `gestor` 
ADD COLUMN `estado_parlamentar` VARCHAR(2) NULL AFTER `numero_parlamentar`;

create table certificado_usuario(
	data_curso date not null,
    id_usuario int not null
);

ALTER TABLE `siconv_programa`
ADD COLUMN `tem_chamamento` INT NULL AFTER `programa_novo`,
ADD COLUMN `objeto` VARCHAR(255) NULL AFTER `tem_chamamento`,
ADD COLUMN `anexos` VARCHAR(255) NULL AFTER `objeto`,
ADD COLUMN `regra_contrapartida` TEXT COLLATE utf8_unicode_ci NULL AFTER `anexos`;

ALTER TABLE `historico_contato_municipio` 
ADD COLUMN `data_visita` DATE NULL AFTER `id_contato_municipio`;

ALTER TABLE `contato_municipio` 
ADD COLUMN `celular_contato` VARCHAR(11) NULL AFTER `data_cadastro`,
ADD COLUMN `comercial_contato` VARCHAR(11) NULL AFTER `celular_contato`;

ALTER TABLE `proposta_comercial` 
ADD COLUMN `percentual_desconto` DECIMAL(5,2) DEFAULT 0 AFTER `nome_entidade`;

ALTER TABLE `banco_proposta`
ADD COLUMN `tipo` VARCHAR(255) NULL AFTER `parecer_ajuste_plano_trabalho`;

ALTER TABLE `siconv_programa` 
MODIFY `objeto` LONGTEXT;

ALTER TABLE `siconv_programa`
MODIFY `anexos` int(11);

create table programas_ocultos(
	codigo_programa varchar(20),
    id_usuario int
);

create table programas_impressos(
	codigo_programa varchar(20),
    id_usuario int
);

ALTER TABLE `nomes_parlamentar` 
ADD COLUMN `partido` VARCHAR(20) NULL AFTER `cargo_parlamentar`,
ADD COLUMN `uf` VARCHAR(2) NULL AFTER `partido`;

ALTER TABLE `certificado_usuario` 
ADD COLUMN `uf` VARCHAR(2) NULL AFTER `id_usuario`,
ADD COLUMN `municipio` VARCHAR(40) NULL AFTER `uf`;

ALTER TABLE `gestor` 
CHANGE COLUMN `numero_parlamentar` `numero_parlamentar` VARCHAR(255) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NULL DEFAULT NULL ;

ALTER TABLE `usuario` 
CHANGE COLUMN `vendedor_codigo_parlamentar` `vendedor_codigo_parlamentar` VARCHAR(255) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NULL DEFAULT NULL ;

INSERT INTO `nivel_usuario` (`id_nivel_usuario`, `descricao`, `nome`) VALUES (6, 'SubGestor.', 'SUBGESTOR');
INSERT INTO `nivel_usuario` (`id_nivel_usuario`, `descricao`, `nome`) VALUES (7, 'SubTécnico.', 'SUBTÉCNICO');
INSERT INTO `nivel_usuario` (`id_nivel_usuario`, `descricao`, `nome`) VALUES (8, 'Responsável Legal. Visualização e acompanhamento dos processos', 'RESPONSÁVEL LEGAL');
UPDATE `nivel_usuario` SET `descricao`='Responsável Legal. Visualização e acompanhamento dos processos', `nome`='RESPONSÁVEL LEGAL' WHERE `id_nivel_usuario`='5';


create table usuario_subgestor(
	id_usuario int not null,
    id_gestor int not null
);

ALTER TABLE `gestor` 
ADD COLUMN `tipo_subgestor` VARCHAR(2) NULL AFTER `estado_parlamentar`;

UPDATE `nivel_usuario` SET `descricao`='SubGestor. Gerir equipes com CNPJs pré-definidos' WHERE `id_nivel_usuario`='6';
UPDATE `nivel_usuario` SET `descricao`='Técnico. Não tem poder de gerir mas pode preencher propostas e enviar.', `nome`='TÉCNICO' WHERE `id_nivel_usuario`='7';

DROP TABLE IF EXISTS `emenda_programa_proposta`;
CREATE TABLE `emenda_programa_proposta` (
    `id_emenda_programa_proposta` int(11) NOT NULL AUTO_INCREMENT,
    `id_programa_proposta` int(11) NOT NULL,
    `valor_utilizado` decimal(20,2) DEFAULT NULL,
    `numero_emenda` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
    PRIMARY KEY (`id_emenda_programa_proposta`),
    KEY `fk_Emenda_Programa_Proposta_idx` (`id_programa_proposta`),
    CONSTRAINT `fk_Emenda_Programa_Proposta` FOREIGN KEY (`id_programa_proposta`) REFERENCES `programa_proposta` (`id_programa_proposta`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `cronograma`
ADD COLUMN `link_meta` VARCHAR(255) NULL AFTER `exportado_siconv`;

ALTER TABLE `siconv_beneficiario` 
ADD INDEX `index1` (`codigo_programa` ASC, `cnpj` ASC, `nome` ASC, `valor` ASC, `emenda` ASC, `parlamentar` ASC),
DROP PRIMARY KEY;

ALTER TABLE `proposta`
ADD COLUMN `padrao_oculto` tinyint(1) NOT NULL DEFAULT '0' AFTER `padrao`;

ALTER TABLE `permissoes_usuario` 
ADD COLUMN `visualiza_emendas` TINYINT(1) NOT NULL DEFAULT 0 AFTER `id_usuario`,
ADD COLUMN `visualiza_prop_parecer` TINYINT(1) NOT NULL DEFAULT 0 AFTER `visualiza_emendas`,
ADD COLUMN `visualiza_minhas_propostas` TINYINT(1) NOT NULL DEFAULT 0 AFTER `visualiza_prop_parecer`;

create table pagseguro_usuario(
	id_usuario int not null,
    codigo_ref_compra varchar(255) not null,
    data_compra date not null,
    validade_plano int not null,
    tipo_servico varchar(30) not null
);

ALTER TABLE `pagseguro_usuario` 
ADD COLUMN `compra_paga` TINYINT(1) NULL DEFAULT 0 AFTER `tipo_servico`;

ALTER TABLE `pagseguro_usuario`
MODIFY `codigo_ref_compra` VARCHAR(255) DEFAULT NULL;

ALTER TABLE `siconv_beneficiario`
ADD COLUMN `data_update_emenda` DATE DEFAULT NULL AFTER `data_fim_parlam`;

ALTER TABLE `siconv_beneficiario`
ADD COLUMN `data_update_emenda` DATE DEFAULT NULL AFTER `data_fim_parlam`;

ALTER TABLE `siconv_beneficiario`
ADD COLUMN `beneficiario_emenda_novo` TINYINT(1) DEFAULT 0 AFTER `data_update_emenda`;

DROP TABLE IF EXISTS `senha_eliumar`;
CREATE TABLE `senha_eliumar` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `cpf` varchar(255) NOT NULL,
    `senha` varchar(255) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `senha_eliumar` (`cpf`, `senha`) VALUES ('43346880559', 'Laisa_m2012');

ALTER TABLE `usuario`
ADD COLUMN `tem_desconto` TINYINT(1) NULL DEFAULT 0 AFTER `vendedor_codigo_parlamentar`;

ALTER TABLE `aceite_licenca_uso` DROP FOREIGN KEY `fk_usuario`;

ALTER TABLE `aceite_licenca_uso` ADD CONSTRAINT `fk_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE;

ALTER TABLE `proposta_comercial` DROP FOREIGN KEY `fk_id_usuario`;

ALTER TABLE `proposta_comercial` ADD FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE;

DROP TABLE IF EXISTS `infoconvenio_marketing_hash`;
CREATE TABLE `infoconvenio_marketing_hash` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `id_usuario` INT(11) DEFAULT NULL,
    `hash` VARCHAR(255) COLLATE utf8_unicode_ci NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `hash` (`hash`),
    UNIQUE KEY `id_usuario` (`id_usuario`),
    CONSTRAINT `fk_id_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `pagseguro_usuario`
ADD COLUMN `ativa` TINYINT(1) NULL DEFAULT 0 AFTER `compra_paga`;

ALTER TABLE `usuario`
ADD COLUMN `data_validade_desconto` DATE DEFAULT NULL AFTER `tem_desconto`;

DROP TABLE IF EXISTS `dados_info_consulta_avulsa`;
CREATE TABLE `dados_info_consulta_avulsa` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `cnpj` varchar(255) DEFAULT NULL,
    `estado` varchar(2) DEFAULT NULL,
    `email` varchar(255) NOT NULL,
    `resultado` longtext NOT NULL,
    `tipo_consulta` tinyint(1) NOT NULL DEFAULT 0,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `pagseguro_dados_info_consulta_avulsa`;
CREATE TABLE `pagseguro_dados_info_consulta_avulsa` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `id_dados_info_consulta_avulsa` int(11) NOT NULL,
    `codigo_ref_compra` varchar(255) NOT NULL,
    `data_compra` DATE NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `fk_id_dados_info_consulta_avulsa_idx` (`id_dados_info_consulta_avulsa`),
    CONSTRAINT `fk_id_dados_info_consulta_avulsa` FOREIGN KEY (`id_dados_info_consulta_avulsa`) REFERENCES `dados_info_consulta_avulsa` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `pagseguro_dados_info_consulta_avulsa`
ADD COLUMN `data_pagamento` DATE NOT NULL AFTER `data_compra`;

DROP TABLE IF EXISTS `dados_info_consulta_temporarios`;
CREATE TABLE `dados_info_consulta_temporarios` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `cnpj` varchar(255) DEFAULT NULL,
    `tipo_consulta` tinyint(1) NOT NULL DEFAULT 0,
    `data_consulta` DATE NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `proponente_siconv`
ADD COLUMN `email` VARCHAR(255) DEFAULT NULL;

ALTER TABLE `proponente_siconv` 
MODIFY `codigo_municipio` VARCHAR(40) DEFAULT NULL;

ALTER TABLE `proponente_siconv` 
MODIFY `fax` VARCHAR(40) DEFAULT NULL;

CREATE TABLE TBMV_CODIGOS_MUNICIPIOS AS SELECT DISTINCT municipio, municipio_uf_nome, municipio_uf_sigla, codigo_municipio FROM `proponente_siconv`;
CREATE INDEX iEstado ON TBMV_CODIGOS_MUNICIPIOS(municipio_uf_nome);
CREATE INDEX iMunicipio ON TBMV_CODIGOS_MUNICIPIOS(municipio);
CREATE INDEX iSigla ON TBMV_CODIGOS_MUNICIPIOS(municipio_uf_sigla);
CREATE INDEX iCodigoMunicipio ON TBMV_CODIGOS_MUNICIPIOS(codigo_municipio);
ALTER TABLE TBMV_CODIGOS_MUNICIPIOS ROW_FORMAT=Fixed;
CREATE VIEW MV_CODIGOS_MUNICIPIOS AS SELECT * FROM TBMV_CODIGOS_MUNICIPIOS;

ALTER TABLE `banco_proposta`
ADD COLUMN `data` DATE NOT NULL AFTER `tipo`;

ALTER TABLE `banco_proposta`
MODIFY `data` DATE DEFAULT NULL;

CREATE TABLE `ids_propostas_faltando`(
    `id` INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `id_proposta_siconv` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `proponente_siconv` 
ADD COLUMN `orgao` VARCHAR(255) DEFAULT NULL,
ADD COLUMN `area` VARCHAR(255) DEFAULT NULL,
ADD COLUMN `subarea` VARCHAR(255) DEFAULT NULL,
ADD COLUMN `situacao_aprovacao` VARCHAR(255) DEFAULT NULL,
ADD COLUMN `data_registro` DATE DEFAULT NULL,
ADD COLUMN `data_vencimento` DATE DEFAULT NULL;

ALTER TABLE `proponente_siconv`
ADD COLUMN `id_siconv` VARCHAR(255) DEFAULT NULL AFTER `data_vencimento`;

ALTER TABLE `siconv_programa`
ADD COLUMN `excluido` tinyint(1) DEFAULT NULL AFTER `regra_contrapartida`;

CREATE TABLE `codigos_programas_existentes_siconv_analise` (
    `id` INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `codigo_programa` VARCHAR(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 

ALTER TABLE `physis_esicar`.`proposta` 
ADD COLUMN `banco_proposta` TINYINT(1) NOT NULL DEFAULT 0 AFTER `enviado_email_aprovado`;



/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2014-10-22  8:40:26
