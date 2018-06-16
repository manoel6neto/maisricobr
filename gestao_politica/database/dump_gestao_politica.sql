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
INSERT INTO `ci_sessions` VALUES ('06btg12qab0cf7pd4aqd01or95mq3j14','201.86.30.242',1527972441,'__ci_last_regenerate|i:1527972441;'),('0gn3625qbs2carn7gfc5jbf680li6qte','177.220.175.83',1528198619,'__ci_last_regenerate|i:1528198619;'),('1a8ff5h6nchf22pfo8n1b8c03nrqaup6','177.220.172.200',1528293245,'__ci_last_regenerate|i:1528293245;'),('276qf19i0phu9v5969vmcsblkb9ppo02','::1',1528202051,'__ci_last_regenerate|i:1528202051;'),('2f9br1pg1r5tljklbl50ifnr8dno9lut','177.220.175.83',1528206288,'__ci_last_regenerate|i:1528206288;'),('2fhmts83elllrtc47rc5nl8iqlf78bgi','127.0.0.1',1528202208,'__ci_last_regenerate|i:1528202207;'),('2kj508k6s6tfb8im3mjp9noat8vnc9k0','177.220.172.200',1528289515,'__ci_last_regenerate|i:1528289515;'),('38md53euqrn8urg4t82djj8ebrqtt630','177.220.172.200',1528292461,'__ci_last_regenerate|i:1528292461;sessao|a:4:{s:18:\"id_usuario_sistema\";i:2;s:12:\"nome_usuario\";s:17:\"Alexandre Gouveia\";s:8:\"is_admin\";s:1:\"1\";s:6:\"logged\";b:1;}'),('3getrorbg0nbobsgdkcimf6as4lkljj7','177.220.173.211',1528112036,'__ci_last_regenerate|i:1528112028;sessao|a:4:{s:18:\"id_usuario_sistema\";i:1;s:12:\"nome_usuario\";s:20:\"Manoel Carvalho Neto\";s:8:\"is_admin\";s:1:\"1\";s:6:\"logged\";b:1;}'),('3lssqkcg3c33cjn1ebeuhnnl83ravaf3','127.0.0.1',1528202208,'__ci_last_regenerate|i:1528202208;'),('5g6qh29gnmhctruh9ri3i7sfc14fbknj','177.220.173.211',1528113091,'__ci_last_regenerate|i:1528113047;sessao|a:4:{s:18:\"id_usuario_sistema\";i:2;s:12:\"nome_usuario\";s:17:\"Alexandre Gouveia\";s:8:\"is_admin\";s:1:\"1\";s:6:\"logged\";b:1;}'),('5pdnei8fumvah53a2f1aesm15gv3vkc8','201.86.30.242',1527972466,'__ci_last_regenerate|i:1527972442;'),('5tj7nvldudgbuqj5ufbvhi8vmstbhpi2','177.220.172.200',1528380430,'__ci_last_regenerate|i:1528380430;sessao|a:4:{s:18:\"id_usuario_sistema\";i:2;s:12:\"nome_usuario\";s:17:\"Alexandre Gouveia\";s:8:\"is_admin\";s:1:\"1\";s:6:\"logged\";b:1;}'),('782rae1fjojsuuhkmpi402rb42g7lvb8','66.249.83.222',1528036474,'__ci_last_regenerate|i:1528036472;'),('7b7rj3euma5tjf708ekdn5nodq12ra8m','127.0.0.1',1528202208,'__ci_last_regenerate|i:1528202208;'),('7mjj7l8o5fqgo0210db8g1mtfmq94nem','177.220.172.200',1528296453,'__ci_last_regenerate|i:1528296453;sessao|a:4:{s:18:\"id_usuario_sistema\";i:2;s:12:\"nome_usuario\";s:17:\"Alexandre Gouveia\";s:8:\"is_admin\";s:1:\"1\";s:6:\"logged\";b:1;}'),('7t4haorjld4gv0vn1br8necimr101uoa','177.220.173.211',1528113967,'__ci_last_regenerate|i:1528113967;'),('8133kqe39197kpvcr8sfp621hvngsh4f','177.220.172.200',1528292452,'__ci_last_regenerate|i:1528292452;'),('8g9r32u2h5n1b72pfdt9cmbl34q337q9','127.0.0.1',1528202208,'__ci_last_regenerate|i:1528202208;'),('8qifin3fpn88cfhahanh38jiuuuuj177','177.220.172.200',1528377988,'__ci_last_regenerate|i:1528377988;sessao|a:4:{s:18:\"id_usuario_sistema\";i:4;s:12:\"nome_usuario\";s:15:\"Michel Platchek\";s:8:\"is_admin\";s:1:\"1\";s:6:\"logged\";b:1;}'),('90rp05mbsj32fklser2tne7i5t0g089f','177.220.150.66',1528307020,'__ci_last_regenerate|i:1528307020;sessao|a:4:{s:18:\"id_usuario_sistema\";i:2;s:12:\"nome_usuario\";s:17:\"Alexandre Gouveia\";s:8:\"is_admin\";s:1:\"1\";s:6:\"logged\";b:1;}'),('9it4hh3idu7tn0aegtb7mf4gvdcmga4m','191.177.187.234',1528115506,'__ci_last_regenerate|i:1528115506;'),('allbl88hajntnepoaoae4sca353nhnjb','66.249.83.77',1528283174,'__ci_last_regenerate|i:1528283174;'),('amjpia42n91v3fcr98p8jcvf6cod6vur','177.220.173.211',1528113909,'__ci_last_regenerate|i:1528113621;sessao|a:4:{s:18:\"id_usuario_sistema\";i:2;s:12:\"nome_usuario\";s:17:\"Alexandre Gouveia\";s:8:\"is_admin\";s:1:\"1\";s:6:\"logged\";b:1;}'),('b7msj0780ahcn8jigi009729vvridpvv','177.220.172.200',1528296451,'__ci_last_regenerate|i:1528296451;'),('bpi6a4k75hd9ql3a3nshi55t8rll0rcb','168.181.51.177',1527951086,'__ci_last_regenerate|i:1527951086;'),('d0q117ime1ar5efbgkj781a90jc8sagb','40.77.190.57',1528022279,'__ci_last_regenerate|i:1528022279;'),('d2d8tv84s1rqjbla214fug7cku03cqlo','127.0.0.1',1528202191,'__ci_last_regenerate|i:1528202190;sessao|a:4:{s:18:\"id_usuario_sistema\";i:1;s:12:\"nome_usuario\";s:20:\"Manoel Carvalho Neto\";s:8:\"is_admin\";s:1:\"1\";s:6:\"logged\";b:1;}'),('fa7m3vfchj85kfh6i2f3hbaho9gb4h0c','177.220.175.83',1528215472,'__ci_last_regenerate|i:1528215472;'),('g05oe4ovbav5eg7h85kksc84b7l4cvog','127.0.0.1',1528202180,'__ci_last_regenerate|i:1528202180;'),('gqgans1f417jemgries0hj90a3mdpaav','::1',1528204081,'__ci_last_regenerate|i:1528203977;sessao|a:4:{s:18:\"id_usuario_sistema\";i:1;s:12:\"nome_usuario\";s:20:\"Manoel Carvalho Neto\";s:8:\"is_admin\";s:1:\"1\";s:6:\"logged\";b:1;}'),('j22fme0nb6uepeo5gbe39t8gbhf5fc2k','::1',1528203146,'__ci_last_regenerate|i:1528203146;'),('ja7agovdiq6891lc2lv1tp45i3rfto17','186.216.174.121',1528307233,'__ci_last_regenerate|i:1528307233;'),('k8klil7jnji59pfgnls4lhuqp1gfgrpl','177.220.172.200',1528290769,'__ci_last_regenerate|i:1528290747;sessao|a:4:{s:18:\"id_usuario_sistema\";i:1;s:12:\"nome_usuario\";s:20:\"Manoel Carvalho Neto\";s:8:\"is_admin\";s:1:\"1\";s:6:\"logged\";b:1;}'),('ke40vj8p9b60161gdm1iphmgb9e95c0p','177.220.172.200',1528377972,'__ci_last_regenerate|i:1528377972;'),('kudesqb3aruernqqua7nvkru72u2agh7','177.220.175.83',1528215296,'__ci_last_regenerate|i:1528215296;'),('l5a9d8ns8cq4ce1tomftg1509vc26q9c','177.220.175.83',1528199117,'__ci_last_regenerate|i:1528199019;sessao|a:4:{s:18:\"id_usuario_sistema\";i:1;s:12:\"nome_usuario\";s:20:\"Manoel Carvalho Neto\";s:8:\"is_admin\";s:1:\"1\";s:6:\"logged\";b:1;}'),('ljuhb3ull5d9muushm3o4m264tqabrtr','177.220.173.229',1528222918,'__ci_last_regenerate|i:1528222918;'),('lomj6u112gudjk1qcdvlafibjtuoo24c','66.249.83.193',1527957046,'__ci_last_regenerate|i:1527957046;'),('m17b7cj16e8ki9rivgutba5rhd0po1rk','186.216.174.121',1528206187,'__ci_last_regenerate|i:1528206187;'),('m2dnvagnk22g1t8euvlnlprh3kl3fbk0','66.249.83.223',1528371302,'__ci_last_regenerate|i:1528371302;'),('m3es4uairplmi682l84agsi6hcv2n8g3','168.181.51.177',1527951075,'__ci_last_regenerate|i:1527951075;'),('m7gjjksiq24j49o2h5jhchpuv3o8sl6j','177.220.173.229',1528222925,'__ci_last_regenerate|i:1528222925;sessao|a:4:{s:18:\"id_usuario_sistema\";i:2;s:12:\"nome_usuario\";s:17:\"Alexandre Gouveia\";s:8:\"is_admin\";s:1:\"1\";s:6:\"logged\";b:1;}'),('ndal6jf7833dbjpn2ndi28bkqd0obo7m','177.220.172.200',1528380763,'__ci_last_regenerate|i:1528380759;sessao|a:4:{s:18:\"id_usuario_sistema\";i:2;s:12:\"nome_usuario\";s:17:\"Alexandre Gouveia\";s:8:\"is_admin\";s:1:\"1\";s:6:\"logged\";b:1;}'),('nli99kh9o6bqstgequpo5a3uen2s7n69','::1',1528202661,'__ci_last_regenerate|i:1528202367;sessao|a:4:{s:18:\"id_usuario_sistema\";i:1;s:12:\"nome_usuario\";s:20:\"Manoel Carvalho Neto\";s:8:\"is_admin\";s:1:\"1\";s:6:\"logged\";b:1;}'),('o3mpmre5ioo8ftbv7b29rn8uvoqvo288','187.112.186.59',1528265050,'__ci_last_regenerate|i:1528265050;sessao|a:4:{s:18:\"id_usuario_sistema\";i:2;s:12:\"nome_usuario\";s:17:\"Alexandre Gouveia\";s:8:\"is_admin\";s:1:\"1\";s:6:\"logged\";b:1;}'),('o8kmlmetl6sc29lhidho9nu8oo95p4sq','177.220.175.83',1528198810,'__ci_last_regenerate|i:1528198626;sessao|a:4:{s:18:\"id_usuario_sistema\";i:1;s:12:\"nome_usuario\";s:20:\"Manoel Carvalho Neto\";s:8:\"is_admin\";s:1:\"1\";s:6:\"logged\";b:1;}'),('omnc667ge7dn6o14qvlp5qhqt5qf9fma','177.220.175.83',1528200262,'__ci_last_regenerate|i:1528200262;'),('oo0bt38bqamhhpk1ub2c1fnru0u8a6p3','177.220.172.200',1528290044,'__ci_last_regenerate|i:1528289895;sessao|a:4:{s:18:\"id_usuario_sistema\";i:1;s:12:\"nome_usuario\";s:20:\"Manoel Carvalho Neto\";s:8:\"is_admin\";s:1:\"1\";s:6:\"logged\";b:1;}'),('ootn68jg6r5t2qnkreknuo4ugcrispuc','177.220.172.200',1528316242,'__ci_last_regenerate|i:1528316242;'),('ppvf3c2s9tdlqmc7act5pfhfahgqk3eu','177.220.173.211',1528112022,'__ci_last_regenerate|i:1528112022;'),('qallrku55l0einptiikn2knsl87tmci4','66.249.83.193',1528195011,'__ci_last_regenerate|i:1528195011;'),('qu3ehpgpd3e71oc9ujtfhpqska3rjuao','187.112.186.59',1528265048,'__ci_last_regenerate|i:1528265048;'),('repkmmvhk60ufa916vtgggkagl909qth','177.220.175.83',1528199342,'__ci_last_regenerate|i:1528199342;'),('rskl5iper4t7tm5mfug71j7bkd6e42k0','177.220.172.200',1528316491,'__ci_last_regenerate|i:1528316244;sessao|a:4:{s:18:\"id_usuario_sistema\";i:2;s:12:\"nome_usuario\";s:17:\"Alexandre Gouveia\";s:8:\"is_admin\";s:1:\"1\";s:6:\"logged\";b:1;}'),('s7930d7qg6tvae7ivbcirv49i6oln762','::1',1528204054,'__ci_last_regenerate|i:1528203975;'),('t3ie79j5i1man49l3n67mkl3kmfruab3','177.220.175.83',1528205928,'__ci_last_regenerate|i:1528205928;'),('t3s8ugh1b8ht31m8aulvn88r9tnbk4at','66.249.83.193',1528100651,'__ci_last_regenerate|i:1528100651;'),('tc9e4qklookuljr4sfa5lc560tq4g4jg','177.220.172.200',1528316594,'__ci_last_regenerate|i:1528316573;sessao|a:4:{s:18:\"id_usuario_sistema\";i:2;s:12:\"nome_usuario\";s:17:\"Alexandre Gouveia\";s:8:\"is_admin\";s:1:\"1\";s:6:\"logged\";b:1;}'),('u2317eampjlpj3d703fmpfcsgtivp1ar','177.220.172.200',1528380427,'__ci_last_regenerate|i:1528380427;'),('uiqma919v6l6uilrhclbr8p97rt2b6tc','127.0.0.1',1528202208,'__ci_last_regenerate|i:1528202208;'),('uirapilis5p2oe00sego5bmd7ssmf2gk','::1',1528202058,'__ci_last_regenerate|i:1528202057;sessao|a:4:{s:18:\"id_usuario_sistema\";i:1;s:12:\"nome_usuario\";s:20:\"Manoel Carvalho Neto\";s:8:\"is_admin\";s:1:\"1\";s:6:\"logged\";b:1;}'),('vccdiamo8mj7b976o9v1t5383vh1htlk','186.216.174.121',1528208768,'__ci_last_regenerate|i:1528208768;'),('vck33pobaq78q5mps8bomh65f76ejhpq','177.220.150.66',1528306728,'__ci_last_regenerate|i:1528306728;'),('vll2dsge6j9fjistkfht2o74gg9mn6gh','191.177.187.234',1528115031,'__ci_last_regenerate|i:1528115031;');
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
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario_aplicativo_cidadao`
--

LOCK TABLES `usuario_aplicativo_cidadao` WRITE;
/*!40000 ALTER TABLE `usuario_aplicativo_cidadao` DISABLE KEYS */;
INSERT INTO `usuario_aplicativo_cidadao` VALUES (1,'84942142920','84942142920',2,NULL),(3,'84942142920','84942142920',3,NULL),(4,'84942142920','84942142920',4,NULL),(5,'84942142920','84942142920',5,''),(6,'84942142920','84942142920',6,NULL),(7,'84093587515','84093587515',1,'');
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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario_assistencia_social`
--

LOCK TABLES `usuario_assistencia_social` WRITE;
/*!40000 ALTER TABLE `usuario_assistencia_social` DISABLE KEYS */;
INSERT INTO `usuario_assistencia_social` VALUES (1,'alexandre@adgouveia.com.br','gestor2017',1,''),(2,'alexandre@adgouveia.com.br','gestor2017',2,NULL),(3,'alexandre@adgouveia.com.br','gestor2017',3,NULL),(4,'alexandre@adgouveia.com.br','gestor2017',4,NULL),(5,'alexandre@adgouveia.com.br','gestor2017',5,''),(6,'alexandre@adgouveia.com.br','gestor2017',6,NULL);
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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario_cad_imobiliario`
--

LOCK TABLES `usuario_cad_imobiliario` WRITE;
/*!40000 ALTER TABLE `usuario_cad_imobiliario` DISABLE KEYS */;
INSERT INTO `usuario_cad_imobiliario` VALUES (1,'84942142920','84942142920',2,NULL),(2,'84942142920','84942142920',1,''),(3,'84942142920','84942142920',3,NULL),(4,'84942142920','84942142920',4,NULL),(5,'84942142920','84942142920',5,''),(6,'84942142920','84942142920',6,NULL);
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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario_cad_unico`
--

LOCK TABLES `usuario_cad_unico` WRITE;
/*!40000 ALTER TABLE `usuario_cad_unico` DISABLE KEYS */;
INSERT INTO `usuario_cad_unico` VALUES (1,'alexandre@adgouveia.com.br','gestor2017',1,''),(2,'alexandre@adgouveia.com.br','gestor2017',2,NULL),(3,'alexandre@adgouveia.com.br','gestor2017',3,NULL),(4,'alexandre@adgouveia.com.br','gestor2017',4,NULL),(5,'alexandre@adgouveia.com.br','gestor2017',5,''),(6,'alexandre@adgouveia.com.br','gestor2017',6,NULL);
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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario_comunicacao_social`
--

LOCK TABLES `usuario_comunicacao_social` WRITE;
/*!40000 ALTER TABLE `usuario_comunicacao_social` DISABLE KEYS */;
INSERT INTO `usuario_comunicacao_social` VALUES (1,'84093587515','321',1,''),(2,'84942142920','84942142920',2,NULL),(3,'35503467515','321',3,NULL),(4,'67861059987','67861059987',4,NULL),(5,'84942142920','84942142920',5,''),(6,'84942142920','84942142920',6,NULL);
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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario_convenios`
--

LOCK TABLES `usuario_convenios` WRITE;
/*!40000 ALTER TABLE `usuario_convenios` DISABLE KEYS */;
INSERT INTO `usuario_convenios` VALUES (1,'84093587515','arroz10',1,''),(2,'84942142920','84942142920',2,NULL),(3,'35503467515','321',3,NULL),(4,'67861059987','67861059987',4,NULL),(5,'84942142920','84942142920',5,''),(6,'32889810925','32889810925',6,NULL);
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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario_educacao`
--

LOCK TABLES `usuario_educacao` WRITE;
/*!40000 ALTER TABLE `usuario_educacao` DISABLE KEYS */;
INSERT INTO `usuario_educacao` VALUES (1,'aledonikian@gmail.com','adg2308',1,''),(2,'aledonikian@gmail.com','adg2308',2,NULL),(3,'aledonikian@gmail.com','adg2308',3,NULL),(4,'aledonikian@gmail.com','adg2308',4,NULL),(5,'aledonikian@gmail.com','adg2308',5,''),(6,'aledonikian@gmail.com','adg2308',6,NULL);
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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario_politica_publica`
--

LOCK TABLES `usuario_politica_publica` WRITE;
/*!40000 ALTER TABLE `usuario_politica_publica` DISABLE KEYS */;
INSERT INTO `usuario_politica_publica` VALUES (1,'alexandre@adgouveia.com.br','gestor2017',1,''),(2,'alexandre@adgouveia.com.br','gestor2017',2,NULL),(3,'alexandre@adgouveia.com.br','gestor2017',3,NULL),(4,'alexandre@adgouveia.com.br','gestor2017',4,NULL),(5,'alexandre@adgouveia.com.br','gestor2017',5,''),(6,'alexandre@adgouveia.com.br','gestor2017',6,NULL);
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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario_saude`
--

LOCK TABLES `usuario_saude` WRITE;
/*!40000 ALTER TABLE `usuario_saude` DISABLE KEYS */;
INSERT INTO `usuario_saude` VALUES (1,'hmm.alexandre.gouveia','adg2308',1,''),(2,'hmm.alexandre.gouveia','adg2308',2,NULL),(3,'hmm.alexandre.gouveia','adg2308',3,NULL),(4,'hmm.alexandre.gouveia','adg2308',4,NULL),(5,'hmm.alexandre.gouveia','adg2308',5,''),(6,'hmm.alexandre.gouveia','adg2308',6,NULL);
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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario_sistema`
--

LOCK TABLES `usuario_sistema` WRITE;
/*!40000 ALTER TABLE `usuario_sistema` DISABLE KEYS */;
INSERT INTO `usuario_sistema` VALUES (1,'Manoel Carvalho Neto','manoel.carvalho.neto@gmail.com','604ac8afa883dea6169e73e26c34a15114032b28','84093587515','2018-04-19 13:39:52',1),(2,'Alexandre Gouveia','alexandre@adgouveia.com.br','28b4e5ffedf5737b9f7475ada9a7463470afbe17','84942142920','2018-06-04 11:42:26',1),(3,'Miguel Augusto Silva Batista','miguelaugusto5@yahoo.com.br','d2482c230aad36512b42ece66216a5346f6799cc','35503467515','2018-06-05 11:38:52',1),(4,'Michel Platchek','platchek@hotmail.com','e35325739ed809b4d4acc66cdb0fc185dda021dd','67861059987','2018-06-05 11:40:08',1),(5,'Paulo Menezes','professorpaulomenezes@gmail.com','011ea0f085ec6dea301e6dc981ee4fc7bdbdddb0','14352095168','2018-06-05 11:44:07',1),(6,'Luiz Roberto Romano','luizroberto@romano.adv.br','71ef71ed437594f6e30be4e06938822a43512357','32889810925','2018-06-05 13:39:30',1);
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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario_terceiro_setor`
--

LOCK TABLES `usuario_terceiro_setor` WRITE;
/*!40000 ALTER TABLE `usuario_terceiro_setor` DISABLE KEYS */;
INSERT INTO `usuario_terceiro_setor` VALUES (1,'aledonikian@gmail.com','2308adgouveia',1,''),(2,'aledonikian@gmail.com','2308adgouveia',2,NULL),(3,'aledonikian@gmail.com','2308adgouveia',3,NULL),(4,'aledonikian@gmail.com','2308adgouveia',4,NULL),(5,'aledonikian@gmail.com','2308adgouveia',5,''),(6,'aledonikian@gmail.com','2308adgouveia',6,NULL);
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

-- Dump completed on 2018-06-07 11:48:54
