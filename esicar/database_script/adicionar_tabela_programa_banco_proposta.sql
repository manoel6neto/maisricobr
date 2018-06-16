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
  `valor_ementa` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `deputado_ementa` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id_programa_banco_proposta`),
  KEY `fk_Programa_Banco_Proposta_idx` (`id_proposta_banco_proposta`),
  CONSTRAINT `fk_Programa_Banco_Proposta` FOREIGN KEY (`id_proposta_banco_proposta`) REFERENCES `banco_proposta` (`id_proposta`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */; 
