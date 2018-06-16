CREATE DEFINER = 'root'@'localhost' PROCEDURE `get_propostas_estado_sem_programa`(
        IN `estado` VARCHAR(100),
        IN `ano` INTEGER(11) UNSIGNED
    )
    NOT DETERMINISTIC
    CONTAINS SQL
    SQL SECURITY DEFINER
    COMMENT ''
BEGIN
	SELECT banco_proposta.`ano`, `banco_proposta`.`nome_proponente`,
     `banco_proposta`.`proponente`, `banco_proposta`.`situacao`, `banco_proposta`.`valor_global` 
       FROM `banco_proposta` LEFT JOIN `proponente_siconv` ON 
     (`banco_proposta`.`proponente` =
      REPLACE(REPLACE(REPLACE(`proponente_siconv`.`cnpj`, '.', ''), '/', ''), '-', ''))
       WHERE UPPER(`proponente_siconv`.`municipio_uf_nome`) = UPPER(estado) AND `banco_proposta`.`ano` = ano;
END;

CREATE DEFINER = 'root'@'localhost' PROCEDURE `get_nome_cidades_from_proponente_siconv`()
    NOT DETERMINISTIC
    CONTAINS SQL
    SQL SECURITY DEFINER
    COMMENT ''
BEGIN
	SELECT DISTINCT municipio FROM proponente_siconv;
END;

CREATE DEFINER = 'root'@'localhost' PROCEDURE `get_nome_cidades_from_proponente_siconv_por_estado`(
        IN `estado` VARCHAR(100)
    )
    NOT DETERMINISTIC
    CONTAINS SQL
    SQL SECURITY DEFINER
    COMMENT ''
BEGIN
	SELECT DISTINCT municipio FROM proponente_siconv
    WHERE UPPER(municipio_uf_nome) = UPPER(estado);
END;

CREATE DEFINER = 'root'@'localhost' PROCEDURE `get_cnpj_cidade_from_proponente_siconv_por_estado`(
        IN `estado` VARCHAR(100)
    )
    NOT DETERMINISTIC
    CONTAINS SQL
    SQL SECURITY DEFINER
    COMMENT ''
BEGIN
	SELECT DISTINCT REPLACE(REPLACE(REPLACE(cnpj, '.', ''), '/', ''), '-', ''), municipio FROM proponente_siconv
    WHERE UPPER(municipio_uf_nome) = UPPER(estado);
END;

