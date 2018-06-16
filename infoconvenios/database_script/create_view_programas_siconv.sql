DROP TABLE IF EXISTS TBMV_CIDADES;
DROP TABLE IF EXISTS TBMV_CIDADES_CNPJ;
DROP TABLE IF EXISTS TBMV_DF_CNPJ;
DROP TABLE IF EXISTS TBMV_PROPOSTAS_PROGRAMAS_ACRE;
DROP TABLE IF EXISTS TBMV_PROPOSTAS_PROGRAMAS_ALAGOAS;
DROP TABLE IF EXISTS TBMV_PROPOSTAS_PROGRAMAS_AMAPÁ;
DROP TABLE IF EXISTS TBMV_PROPOSTAS_PROGRAMAS_AMAZONAS;
DROP TABLE IF EXISTS TBMV_PROPOSTAS_PROGRAMAS_BAHIA;
DROP TABLE IF EXISTS TBMV_PROPOSTAS_PROGRAMAS_CEARÁ;
DROP TABLE IF EXISTS TBMV_PROPOSTAS_PROGRAMAS_DISTRITO_FEDERAL;
DROP TABLE IF EXISTS TBMV_PROPOSTAS_PROGRAMAS_ESPÍRITO_SANTO;
DROP TABLE IF EXISTS TBMV_PROPOSTAS_PROGRAMAS_GOIÁS;
DROP TABLE IF EXISTS TBMV_PROPOSTAS_PROGRAMAS_MARANHÃO;
DROP TABLE IF EXISTS TBMV_PROPOSTAS_PROGRAMAS_MATO_GROSSO;
DROP TABLE IF EXISTS TBMV_PROPOSTAS_PROGRAMAS_MATO_GROSSO_DO_SUL;
DROP TABLE IF EXISTS TBMV_PROPOSTAS_PROGRAMAS_MINAS_GERAIS;
DROP TABLE IF EXISTS TBMV_PROPOSTAS_PROGRAMAS_PARÁ;
DROP TABLE IF EXISTS TBMV_PROPOSTAS_PROGRAMAS_PARAÍBA;
DROP TABLE IF EXISTS TBMV_PROPOSTAS_PROGRAMAS_PARANÁ;
DROP TABLE IF EXISTS TBMV_PROPOSTAS_PROGRAMAS_PERNAMBUCO;
DROP TABLE IF EXISTS TBMV_PROPOSTAS_PROGRAMAS_PIAUÍ;
DROP TABLE IF EXISTS TBMV_PROPOSTAS_PROGRAMAS_RIO_DE_JANEIRO;
DROP TABLE IF EXISTS TBMV_PROPOSTAS_PROGRAMAS_RIO_GRANDE_DO_NORTE;
DROP TABLE IF EXISTS TBMV_PROPOSTAS_PROGRAMAS_RIO_GRANDE_DO_SUL;
DROP TABLE IF EXISTS TBMV_PROPOSTAS_PROGRAMAS_RONDÔNIA;
DROP TABLE IF EXISTS TBMV_PROPOSTAS_PROGRAMAS_RORAIMA;
DROP TABLE IF EXISTS TBMV_PROPOSTAS_PROGRAMAS_TOCANTINS;
DROP TABLE IF EXISTS TBMV_PROPOSTAS_PROGRAMAS_SERGIPE;
DROP TABLE IF EXISTS TBMV_PROPOSTAS_PROGRAMAS_SÃO_PAULO;
DROP TABLE IF EXISTS TBMV_PROPOSTAS_PROGRAMAS_SANTA_CATARINA;
DROP TABLE IF EXISTS TBMV_PROGRAMAS_RELATORIOS;

DROP VIEW IF EXISTS MV_CIDADES;
DROP VIEW IF EXISTS MV_CIDADES_CNPJ;
DROP VIEW IF EXISTS MV_DF_CNPJ;
DROP VIEW IF EXISTS MV_PROPOSTAS_PROGRAMAS_ACRE;
DROP VIEW IF EXISTS MV_PROPOSTAS_PROGRAMAS_ALAGOAS;
DROP VIEW IF EXISTS MV_PROPOSTAS_PROGRAMAS_AMAPÁ;
DROP VIEW IF EXISTS MV_PROPOSTAS_PROGRAMAS_AMAZONAS;
DROP VIEW IF EXISTS MV_PROPOSTAS_PROGRAMAS_BAHIA;
DROP VIEW IF EXISTS MV_PROPOSTAS_PROGRAMAS_CEARÁ;
DROP VIEW IF EXISTS MV_PROPOSTAS_PROGRAMAS_DISTRITO_FEDERAL;
DROP VIEW IF EXISTS MV_PROPOSTAS_PROGRAMAS_ESPÍRITO_SANTO;
DROP VIEW IF EXISTS MV_PROPOSTAS_PROGRAMAS_GOIÁS;
DROP VIEW IF EXISTS MV_PROPOSTAS_PROGRAMAS_MARANHÃO;
DROP VIEW IF EXISTS MV_PROPOSTAS_PROGRAMAS_MATO_GROSSO;
DROP VIEW IF EXISTS MV_PROPOSTAS_PROGRAMAS_MATO_GROSSO_DO_SUL;
DROP VIEW IF EXISTS MV_PROPOSTAS_PROGRAMAS_MINAS_GERAIS;
DROP VIEW IF EXISTS MV_PROPOSTAS_PROGRAMAS_PARÁ;
DROP VIEW IF EXISTS MV_PROPOSTAS_PROGRAMAS_PARAÍBA;
DROP VIEW IF EXISTS MV_PROPOSTAS_PROGRAMAS_PARANÁ;
DROP VIEW IF EXISTS MV_PROPOSTAS_PROGRAMAS_PERNAMBUCO;
DROP VIEW IF EXISTS MV_PROPOSTAS_PROGRAMAS_PIAUÍ;
DROP VIEW IF EXISTS MV_PROPOSTAS_PROGRAMAS_RIO_DE_JANEIRO;
DROP VIEW IF EXISTS MV_PROPOSTAS_PROGRAMAS_RIO_GRANDE_DO_NORTE;
DROP VIEW IF EXISTS MV_PROPOSTAS_PROGRAMAS_RIO_GRANDE_DO_SUL;
DROP VIEW IF EXISTS MV_PROPOSTAS_PROGRAMAS_RONDÔNIA;
DROP VIEW IF EXISTS MV_PROPOSTAS_PROGRAMAS_RORAIMA;
DROP VIEW IF EXISTS MV_PROPOSTAS_PROGRAMAS_TOCANTINS;
DROP VIEW IF EXISTS MV_PROPOSTAS_PROGRAMAS_SERGIPE;
DROP VIEW IF EXISTS MV_PROPOSTAS_PROGRAMAS_SÃO_PAULO;
DROP VIEW IF EXISTS MV_PROPOSTAS_PROGRAMAS_SANTA_CATARINA;
DROP VIEW IF EXISTS MV_PROGRAMAS_RELATORIOS;

CREATE TABLE TBMV_CIDADES AS
SELECT DISTINCT municipio, municipio_uf_nome, municipio_uf_sigla FROM `proponente_siconv`;

CREATE INDEX iEstado ON TBMV_CIDADES(municipio_uf_nome);

CREATE INDEX iMunicipio ON TBMV_CIDADES(municipio);

CREATE INDEX iSigla ON TBMV_CIDADES(municipio_uf_sigla);

ALTER TABLE TBMV_CIDADES ROW_FORMAT=Fixed;

CREATE VIEW MV_CIDADES AS 
SELECT * FROM TBMV_CIDADES;

CREATE TABLE TBMV_CIDADES_CNPJ AS
SELECT DISTINCT REPLACE(REPLACE(REPLACE(cnpj, '.', ''), '/', ''), '-', '') AS cnpj, municipio, municipio_uf_nome FROM `proponente_siconv` 
WHERE esfera_administrativa = 'MUNICIPAL';

CREATE INDEX iEstado ON TBMV_CIDADES_CNPJ(municipio_uf_nome);

CREATE INDEX iMunicipio ON TBMV_CIDADES_CNPJ(municipio);

CREATE INDEX iCnpj ON TBMV_CIDADES_CNPJ(cnpj);

ALTER TABLE TBMV_CIDADES_CNPJ ROW_FORMAT=Fixed;

CREATE VIEW MV_CIDADES_CNPJ AS 
SELECT * FROM TBMV_CIDADES_CNPJ;

CREATE TABLE TBMV_DF_CNPJ AS
SELECT DISTINCT REPLACE(REPLACE(REPLACE(cnpj, '.', ''), '/', ''), '-', '') AS cnpj, municipio, municipio_uf_nome FROM `proponente_siconv` 
WHERE esfera_administrativa = 'ESTADUAL' AND municipio_uf_sigla = 'DF';

CREATE INDEX iEstado ON TBMV_DF_CNPJ(municipio_uf_nome);

CREATE INDEX iMunicipio ON TBMV_DF_CNPJ(municipio);

CREATE INDEX iCnpj ON TBMV_DF_CNPJ(cnpj);

ALTER TABLE TBMV_DF_CNPJ ROW_FORMAT=Fixed;

CREATE VIEW MV_DF_CNPJ AS 
SELECT * FROM TBMV_DF_CNPJ;

CREATE TABLE TBMV_PROPOSTAS_PROGRAMAS_ACRE AS 
SELECT banco_proposta.`ano`, `banco_proposta`.`codigo_programa`, `banco_proposta`.`nome_proponente`, 
`banco_proposta`.`proponente`, `banco_proposta`.`situacao`, `banco_proposta`.`valor_global`, 
`siconv_programa`.`qualificacao` FROM `banco_proposta` LEFT JOIN `proponente_siconv` ON 
(`banco_proposta`.`proponente` = REPLACE(REPLACE(REPLACE(`proponente_siconv`.`cnpj`, '.', ''), '/', ''), '-', '')) 
LEFT JOIN `siconv_programa` ON (`banco_proposta`.`codigo_programa` = `siconv_programa`.`codigo`) 
WHERE `proponente_siconv`.`municipio_uf_sigla` = 'AC' AND proponente_siconv.esfera_administrativa = 'MUNICIPAL';

CREATE INDEX iAno ON TBMV_PROPOSTAS_PROGRAMAS_ACRE(ano);

CREATE INDEX iProponente ON TBMV_PROPOSTAS_PROGRAMAS_ACRE(proponente);

CREATE INDEX iSituacao ON TBMV_PROPOSTAS_PROGRAMAS_ACRE(situacao);

CREATE INDEX iQualificacao ON TBMV_PROPOSTAS_PROGRAMAS_ACRE(qualificacao);

ALTER TABLE TBMV_PROPOSTAS_PROGRAMAS_ACRE ROW_FORMAT=Fixed;

CREATE VIEW MV_PROPOSTAS_PROGRAMAS_ACRE AS 
SELECT * FROM TBMV_PROPOSTAS_PROGRAMAS_ACRE;

CREATE TABLE TBMV_PROPOSTAS_PROGRAMAS_ALAGOAS AS 
SELECT banco_proposta.`ano`, `banco_proposta`.`codigo_programa`, `banco_proposta`.`nome_proponente`, 
`banco_proposta`.`proponente`, `banco_proposta`.`situacao`, `banco_proposta`.`valor_global`, 
`siconv_programa`.`qualificacao` FROM `banco_proposta` LEFT JOIN `proponente_siconv` ON 
(`banco_proposta`.`proponente` = REPLACE(REPLACE(REPLACE(`proponente_siconv`.`cnpj`, '.', ''), '/', ''), '-', '')) 
LEFT JOIN `siconv_programa` ON (`banco_proposta`.`codigo_programa` = `siconv_programa`.`codigo`) 
WHERE `proponente_siconv`.`municipio_uf_sigla` = 'AL' AND proponente_siconv.esfera_administrativa = 'MUNICIPAL';

CREATE INDEX iAno ON TBMV_PROPOSTAS_PROGRAMAS_ALAGOAS(ano);

CREATE INDEX iProponente ON TBMV_PROPOSTAS_PROGRAMAS_ALAGOAS(proponente);

CREATE INDEX iSituacao ON TBMV_PROPOSTAS_PROGRAMAS_ALAGOAS(situacao);

CREATE INDEX iQualificacao ON TBMV_PROPOSTAS_PROGRAMAS_ALAGOAS(qualificacao);

ALTER TABLE TBMV_PROPOSTAS_PROGRAMAS_ALAGOAS ROW_FORMAT=Fixed;

CREATE VIEW MV_PROPOSTAS_PROGRAMAS_ALAGOAS AS 
SELECT * FROM TBMV_PROPOSTAS_PROGRAMAS_ALAGOAS;

CREATE TABLE TBMV_PROPOSTAS_PROGRAMAS_AMAPÁ AS 
SELECT banco_proposta.`ano`, `banco_proposta`.`codigo_programa`, `banco_proposta`.`nome_proponente`, 
`banco_proposta`.`proponente`, `banco_proposta`.`situacao`, `banco_proposta`.`valor_global`, 
`siconv_programa`.`qualificacao` FROM `banco_proposta` LEFT JOIN `proponente_siconv` ON 
(`banco_proposta`.`proponente` = REPLACE(REPLACE(REPLACE(`proponente_siconv`.`cnpj`, '.', ''), '/', ''), '-', '')) 
LEFT JOIN `siconv_programa` ON (`banco_proposta`.`codigo_programa` = `siconv_programa`.`codigo`) 
WHERE `proponente_siconv`.`municipio_uf_sigla` = 'AP' AND proponente_siconv.esfera_administrativa = 'MUNICIPAL';

CREATE INDEX iAno ON TBMV_PROPOSTAS_PROGRAMAS_AMAPÁ(ano);

CREATE INDEX iProponente ON TBMV_PROPOSTAS_PROGRAMAS_AMAPÁ(proponente);

CREATE INDEX iSituacao ON TBMV_PROPOSTAS_PROGRAMAS_AMAPÁ(situacao);

CREATE INDEX iQualificacao ON TBMV_PROPOSTAS_PROGRAMAS_AMAPÁ(qualificacao);

ALTER TABLE TBMV_PROPOSTAS_PROGRAMAS_AMAPÁ ROW_FORMAT=Fixed;

CREATE VIEW MV_PROPOSTAS_PROGRAMAS_AMAPÁ AS 
SELECT * FROM TBMV_PROPOSTAS_PROGRAMAS_AMAPÁ;

CREATE TABLE TBMV_PROPOSTAS_PROGRAMAS_AMAZONAS AS 
SELECT banco_proposta.`ano`, `banco_proposta`.`codigo_programa`, `banco_proposta`.`nome_proponente`, 
`banco_proposta`.`proponente`, `banco_proposta`.`situacao`, `banco_proposta`.`valor_global`, 
`siconv_programa`.`qualificacao` FROM `banco_proposta` LEFT JOIN `proponente_siconv` ON 
(`banco_proposta`.`proponente` = REPLACE(REPLACE(REPLACE(`proponente_siconv`.`cnpj`, '.', ''), '/', ''), '-', '')) 
LEFT JOIN `siconv_programa` ON (`banco_proposta`.`codigo_programa` = `siconv_programa`.`codigo`) 
WHERE `proponente_siconv`.`municipio_uf_sigla` = 'AM' AND proponente_siconv.esfera_administrativa = 'MUNICIPAL';

CREATE INDEX iAno ON TBMV_PROPOSTAS_PROGRAMAS_AMAZONAS(ano);

CREATE INDEX iProponente ON TBMV_PROPOSTAS_PROGRAMAS_AMAZONAS(proponente);

CREATE INDEX iSituacao ON TBMV_PROPOSTAS_PROGRAMAS_AMAZONAS(situacao);

CREATE INDEX iQualificacao ON TBMV_PROPOSTAS_PROGRAMAS_AMAZONAS(qualificacao);

ALTER TABLE TBMV_PROPOSTAS_PROGRAMAS_AMAZONAS ROW_FORMAT=Fixed;

CREATE VIEW MV_PROPOSTAS_PROGRAMAS_AMAZONAS AS 
SELECT * FROM TBMV_PROPOSTAS_PROGRAMAS_AMAZONAS;

CREATE TABLE TBMV_PROPOSTAS_PROGRAMAS_BAHIA AS 
SELECT banco_proposta.`ano`, `banco_proposta`.`codigo_programa`, `banco_proposta`.`nome_proponente`, 
`banco_proposta`.`proponente`, `banco_proposta`.`situacao`, `banco_proposta`.`valor_global`, 
`siconv_programa`.`qualificacao` FROM `banco_proposta` LEFT JOIN `proponente_siconv` ON 
(`banco_proposta`.`proponente` = REPLACE(REPLACE(REPLACE(`proponente_siconv`.`cnpj`, '.', ''), '/', ''), '-', '')) 
LEFT JOIN `siconv_programa` ON (`banco_proposta`.`codigo_programa` = `siconv_programa`.`codigo`) 
WHERE `proponente_siconv`.`municipio_uf_sigla` = 'BA' AND proponente_siconv.esfera_administrativa = 'MUNICIPAL';

CREATE INDEX iAno ON TBMV_PROPOSTAS_PROGRAMAS_BAHIA(ano);

CREATE INDEX iProponente ON TBMV_PROPOSTAS_PROGRAMAS_BAHIA(proponente);

CREATE INDEX iSituacao ON TBMV_PROPOSTAS_PROGRAMAS_BAHIA(situacao);

CREATE INDEX iQualificacao ON TBMV_PROPOSTAS_PROGRAMAS_BAHIA(qualificacao);

ALTER TABLE TBMV_PROPOSTAS_PROGRAMAS_BAHIA ROW_FORMAT=Fixed;

CREATE VIEW MV_PROPOSTAS_PROGRAMAS_BAHIA AS 
SELECT * FROM TBMV_PROPOSTAS_PROGRAMAS_BAHIA;

CREATE TABLE TBMV_PROPOSTAS_PROGRAMAS_CEARÁ AS 
SELECT banco_proposta.`ano`, `banco_proposta`.`codigo_programa`, `banco_proposta`.`nome_proponente`, 
`banco_proposta`.`proponente`, `banco_proposta`.`situacao`, `banco_proposta`.`valor_global`, 
`siconv_programa`.`qualificacao` FROM `banco_proposta` LEFT JOIN `proponente_siconv` ON 
(`banco_proposta`.`proponente` = REPLACE(REPLACE(REPLACE(`proponente_siconv`.`cnpj`, '.', ''), '/', ''), '-', '')) 
LEFT JOIN `siconv_programa` ON (`banco_proposta`.`codigo_programa` = `siconv_programa`.`codigo`) 
WHERE `proponente_siconv`.`municipio_uf_sigla` = 'CE' AND proponente_siconv.esfera_administrativa = 'MUNICIPAL';

CREATE INDEX iAno ON TBMV_PROPOSTAS_PROGRAMAS_CEARÁ(ano);

CREATE INDEX iProponente ON TBMV_PROPOSTAS_PROGRAMAS_CEARÁ(proponente);

CREATE INDEX iSituacao ON TBMV_PROPOSTAS_PROGRAMAS_CEARÁ(situacao);

CREATE INDEX iQualificacao ON TBMV_PROPOSTAS_PROGRAMAS_CEARÁ(qualificacao);

ALTER TABLE TBMV_PROPOSTAS_PROGRAMAS_CEARÁ ROW_FORMAT=Fixed;

CREATE VIEW MV_PROPOSTAS_PROGRAMAS_CEARÁ AS 
SELECT * FROM TBMV_PROPOSTAS_PROGRAMAS_CEARÁ;

CREATE TABLE TBMV_PROPOSTAS_PROGRAMAS_DISTRITO_FEDERAL AS 
SELECT banco_proposta.`ano`, `banco_proposta`.`codigo_programa`, `banco_proposta`.`nome_proponente`, 
`banco_proposta`.`proponente`, `banco_proposta`.`situacao`, `banco_proposta`.`valor_global`, 
`siconv_programa`.`qualificacao` FROM `banco_proposta` LEFT JOIN `proponente_siconv` ON 
(`banco_proposta`.`proponente` = REPLACE(REPLACE(REPLACE(`proponente_siconv`.`cnpj`, '.', ''), '/', ''), '-', '')) 
LEFT JOIN `siconv_programa` ON (`banco_proposta`.`codigo_programa` = `siconv_programa`.`codigo`) 
WHERE `proponente_siconv`.`municipio_uf_sigla` = 'DF' AND proponente_siconv.esfera_administrativa = 'ESTADUAL';

CREATE INDEX iAno ON TBMV_PROPOSTAS_PROGRAMAS_DISTRITO_FEDERAL(ano);

CREATE INDEX iProponente ON TBMV_PROPOSTAS_PROGRAMAS_DISTRITO_FEDERAL(proponente);

CREATE INDEX iSituacao ON TBMV_PROPOSTAS_PROGRAMAS_DISTRITO_FEDERAL(situacao);

CREATE INDEX iQualificacao ON TBMV_PROPOSTAS_PROGRAMAS_DISTRITO_FEDERAL(qualificacao);

ALTER TABLE TBMV_PROPOSTAS_PROGRAMAS_DISTRITO_FEDERAL ROW_FORMAT=Fixed;

CREATE VIEW MV_PROPOSTAS_PROGRAMAS_DISTRITO_FEDERAL AS 
SELECT * FROM TBMV_PROPOSTAS_PROGRAMAS_DISTRITO_FEDERAL;

CREATE TABLE TBMV_PROPOSTAS_PROGRAMAS_ESPÍRITO_SANTO AS 
SELECT banco_proposta.`ano`, `banco_proposta`.`codigo_programa`, `banco_proposta`.`nome_proponente`, 
`banco_proposta`.`proponente`, `banco_proposta`.`situacao`, `banco_proposta`.`valor_global`, 
`siconv_programa`.`qualificacao` FROM `banco_proposta` LEFT JOIN `proponente_siconv` ON 
(`banco_proposta`.`proponente` = REPLACE(REPLACE(REPLACE(`proponente_siconv`.`cnpj`, '.', ''), '/', ''), '-', '')) 
LEFT JOIN `siconv_programa` ON (`banco_proposta`.`codigo_programa` = `siconv_programa`.`codigo`) 
WHERE `proponente_siconv`.`municipio_uf_sigla` = 'ES' AND proponente_siconv.esfera_administrativa = 'MUNICIPAL';

CREATE INDEX iAno ON TBMV_PROPOSTAS_PROGRAMAS_ESPÍRITO_SANTO(ano);

CREATE INDEX iProponente ON TBMV_PROPOSTAS_PROGRAMAS_ESPÍRITO_SANTO(proponente);

CREATE INDEX iSituacao ON TBMV_PROPOSTAS_PROGRAMAS_ESPÍRITO_SANTO(situacao);

CREATE INDEX iQualificacao ON TBMV_PROPOSTAS_PROGRAMAS_ESPÍRITO_SANTO(qualificacao);

ALTER TABLE TBMV_PROPOSTAS_PROGRAMAS_ESPÍRITO_SANTO ROW_FORMAT=Fixed;

CREATE VIEW MV_PROPOSTAS_PROGRAMAS_ESPÍRITO_SANTO AS 
SELECT * FROM TBMV_PROPOSTAS_PROGRAMAS_ESPÍRITO_SANTO;

CREATE TABLE TBMV_PROPOSTAS_PROGRAMAS_GOIÁS AS 
SELECT banco_proposta.`ano`, `banco_proposta`.`codigo_programa`, `banco_proposta`.`nome_proponente`, 
`banco_proposta`.`proponente`, `banco_proposta`.`situacao`, `banco_proposta`.`valor_global`, 
`siconv_programa`.`qualificacao` FROM `banco_proposta` LEFT JOIN `proponente_siconv` ON 
(`banco_proposta`.`proponente` = REPLACE(REPLACE(REPLACE(`proponente_siconv`.`cnpj`, '.', ''), '/', ''), '-', '')) 
LEFT JOIN `siconv_programa` ON (`banco_proposta`.`codigo_programa` = `siconv_programa`.`codigo`) 
WHERE `proponente_siconv`.`municipio_uf_sigla` = 'GO' AND proponente_siconv.esfera_administrativa = 'MUNICIPAL';

CREATE INDEX iAno ON TBMV_PROPOSTAS_PROGRAMAS_GOIÁS(ano);

CREATE INDEX iProponente ON TBMV_PROPOSTAS_PROGRAMAS_GOIÁS(proponente);

CREATE INDEX iSituacao ON TBMV_PROPOSTAS_PROGRAMAS_GOIÁS(situacao);

CREATE INDEX iQualificacao ON TBMV_PROPOSTAS_PROGRAMAS_GOIÁS(qualificacao);

ALTER TABLE TBMV_PROPOSTAS_PROGRAMAS_GOIÁS ROW_FORMAT=Fixed;

CREATE VIEW MV_PROPOSTAS_PROGRAMAS_GOIÁS AS 
SELECT * FROM TBMV_PROPOSTAS_PROGRAMAS_GOIÁS;

CREATE TABLE TBMV_PROPOSTAS_PROGRAMAS_MARANHÃO AS 
SELECT banco_proposta.`ano`, `banco_proposta`.`codigo_programa`, `banco_proposta`.`nome_proponente`, 
`banco_proposta`.`proponente`, `banco_proposta`.`situacao`, `banco_proposta`.`valor_global`, 
`siconv_programa`.`qualificacao` FROM `banco_proposta` LEFT JOIN `proponente_siconv` ON 
(`banco_proposta`.`proponente` = REPLACE(REPLACE(REPLACE(`proponente_siconv`.`cnpj`, '.', ''), '/', ''), '-', '')) 
LEFT JOIN `siconv_programa` ON (`banco_proposta`.`codigo_programa` = `siconv_programa`.`codigo`) 
WHERE `proponente_siconv`.`municipio_uf_sigla` = 'MA' AND proponente_siconv.esfera_administrativa = 'MUNICIPAL';

CREATE INDEX iAno ON TBMV_PROPOSTAS_PROGRAMAS_MARANHÃO(ano);

CREATE INDEX iProponente ON TBMV_PROPOSTAS_PROGRAMAS_MARANHÃO(proponente);

CREATE INDEX iSituacao ON TBMV_PROPOSTAS_PROGRAMAS_MARANHÃO(situacao);

CREATE INDEX iQualificacao ON TBMV_PROPOSTAS_PROGRAMAS_MARANHÃO(qualificacao);

ALTER TABLE TBMV_PROPOSTAS_PROGRAMAS_MARANHÃO ROW_FORMAT=Fixed;

CREATE VIEW MV_PROPOSTAS_PROGRAMAS_MARANHÃO AS 
SELECT * FROM TBMV_PROPOSTAS_PROGRAMAS_MARANHÃO;

CREATE TABLE TBMV_PROPOSTAS_PROGRAMAS_MATO_GROSSO AS 
SELECT banco_proposta.`ano`, `banco_proposta`.`codigo_programa`, `banco_proposta`.`nome_proponente`, 
`banco_proposta`.`proponente`, `banco_proposta`.`situacao`, `banco_proposta`.`valor_global`, 
`siconv_programa`.`qualificacao` FROM `banco_proposta` LEFT JOIN `proponente_siconv` ON 
(`banco_proposta`.`proponente` = REPLACE(REPLACE(REPLACE(`proponente_siconv`.`cnpj`, '.', ''), '/', ''), '-', '')) 
LEFT JOIN `siconv_programa` ON (`banco_proposta`.`codigo_programa` = `siconv_programa`.`codigo`) 
WHERE `proponente_siconv`.`municipio_uf_sigla` = 'MT' AND proponente_siconv.esfera_administrativa = 'MUNICIPAL';

CREATE INDEX iAno ON TBMV_PROPOSTAS_PROGRAMAS_MATO_GROSSO(ano);

CREATE INDEX iProponente ON TBMV_PROPOSTAS_PROGRAMAS_MATO_GROSSO(proponente);

CREATE INDEX iSituacao ON TBMV_PROPOSTAS_PROGRAMAS_MATO_GROSSO(situacao);

CREATE INDEX iQualificacao ON TBMV_PROPOSTAS_PROGRAMAS_MATO_GROSSO(qualificacao);

ALTER TABLE TBMV_PROPOSTAS_PROGRAMAS_MATO_GROSSO ROW_FORMAT=Fixed;

CREATE VIEW MV_PROPOSTAS_PROGRAMAS_MATO_GROSSO AS 
SELECT * FROM TBMV_PROPOSTAS_PROGRAMAS_MATO_GROSSO;

CREATE TABLE TBMV_PROPOSTAS_PROGRAMAS_MATO_GROSSO_DO_SUL AS 
SELECT banco_proposta.`ano`, `banco_proposta`.`codigo_programa`, `banco_proposta`.`nome_proponente`, 
`banco_proposta`.`proponente`, `banco_proposta`.`situacao`, `banco_proposta`.`valor_global`, 
`siconv_programa`.`qualificacao` FROM `banco_proposta` LEFT JOIN `proponente_siconv` ON 
(`banco_proposta`.`proponente` = REPLACE(REPLACE(REPLACE(`proponente_siconv`.`cnpj`, '.', ''), '/', ''), '-', '')) 
LEFT JOIN `siconv_programa` ON (`banco_proposta`.`codigo_programa` = `siconv_programa`.`codigo`) 
WHERE `proponente_siconv`.`municipio_uf_sigla` = 'MS' AND proponente_siconv.esfera_administrativa = 'MUNICIPAL';

CREATE INDEX iAno ON TBMV_PROPOSTAS_PROGRAMAS_MATO_GROSSO_DO_SUL(ano);

CREATE INDEX iProponente ON TBMV_PROPOSTAS_PROGRAMAS_MATO_GROSSO_DO_SUL(proponente);

CREATE INDEX iSituacao ON TBMV_PROPOSTAS_PROGRAMAS_MATO_GROSSO_DO_SUL(situacao);

CREATE INDEX iQualificacao ON TBMV_PROPOSTAS_PROGRAMAS_MATO_GROSSO_DO_SUL(qualificacao);

ALTER TABLE TBMV_PROPOSTAS_PROGRAMAS_MATO_GROSSO_DO_SUL ROW_FORMAT=Fixed;

CREATE VIEW MV_PROPOSTAS_PROGRAMAS_MATO_GROSSO_DO_SUL AS 
SELECT * FROM TBMV_PROPOSTAS_PROGRAMAS_MATO_GROSSO_DO_SUL;

CREATE TABLE TBMV_PROPOSTAS_PROGRAMAS_MINAS_GERAIS AS 
SELECT banco_proposta.`ano`, `banco_proposta`.`codigo_programa`, `banco_proposta`.`nome_proponente`, 
`banco_proposta`.`proponente`, `banco_proposta`.`situacao`, `banco_proposta`.`valor_global`, 
`siconv_programa`.`qualificacao` FROM `banco_proposta` LEFT JOIN `proponente_siconv` ON 
(`banco_proposta`.`proponente` = REPLACE(REPLACE(REPLACE(`proponente_siconv`.`cnpj`, '.', ''), '/', ''), '-', '')) 
LEFT JOIN `siconv_programa` ON (`banco_proposta`.`codigo_programa` = `siconv_programa`.`codigo`) 
WHERE `proponente_siconv`.`municipio_uf_sigla` = 'MG' AND proponente_siconv.esfera_administrativa = 'MUNICIPAL';

CREATE INDEX iAno ON TBMV_PROPOSTAS_PROGRAMAS_MINAS_GERAIS(ano);

CREATE INDEX iProponente ON TBMV_PROPOSTAS_PROGRAMAS_MINAS_GERAIS(proponente);

CREATE INDEX iSituacao ON TBMV_PROPOSTAS_PROGRAMAS_MINAS_GERAIS(situacao);

CREATE INDEX iQualificacao ON TBMV_PROPOSTAS_PROGRAMAS_MINAS_GERAIS(qualificacao);

ALTER TABLE TBMV_PROPOSTAS_PROGRAMAS_MINAS_GERAIS ROW_FORMAT=Fixed;

CREATE VIEW MV_PROPOSTAS_PROGRAMAS_MINAS_GERAIS AS 
SELECT * FROM TBMV_PROPOSTAS_PROGRAMAS_MINAS_GERAIS;

CREATE TABLE TBMV_PROPOSTAS_PROGRAMAS_PARÁ AS 
SELECT banco_proposta.`ano`, `banco_proposta`.`codigo_programa`, `banco_proposta`.`nome_proponente`, 
`banco_proposta`.`proponente`, `banco_proposta`.`situacao`, `banco_proposta`.`valor_global`, 
`siconv_programa`.`qualificacao` FROM `banco_proposta` LEFT JOIN `proponente_siconv` ON 
(`banco_proposta`.`proponente` = REPLACE(REPLACE(REPLACE(`proponente_siconv`.`cnpj`, '.', ''), '/', ''), '-', '')) 
LEFT JOIN `siconv_programa` ON (`banco_proposta`.`codigo_programa` = `siconv_programa`.`codigo`) 
WHERE `proponente_siconv`.`municipio_uf_sigla` = 'PA' AND proponente_siconv.esfera_administrativa = 'MUNICIPAL';

CREATE INDEX iAno ON TBMV_PROPOSTAS_PROGRAMAS_PARÁ(ano);

CREATE INDEX iProponente ON TBMV_PROPOSTAS_PROGRAMAS_PARÁ(proponente);

CREATE INDEX iSituacao ON TBMV_PROPOSTAS_PROGRAMAS_PARÁ(situacao);

CREATE INDEX iQualificacao ON TBMV_PROPOSTAS_PROGRAMAS_PARÁ(qualificacao);

ALTER TABLE TBMV_PROPOSTAS_PROGRAMAS_PARÁ ROW_FORMAT=Fixed;

CREATE VIEW MV_PROPOSTAS_PROGRAMAS_PARÁ AS 
SELECT * FROM TBMV_PROPOSTAS_PROGRAMAS_PARÁ;

CREATE TABLE TBMV_PROPOSTAS_PROGRAMAS_PARAÍBA AS 
SELECT banco_proposta.`ano`, `banco_proposta`.`codigo_programa`, `banco_proposta`.`nome_proponente`, 
`banco_proposta`.`proponente`, `banco_proposta`.`situacao`, `banco_proposta`.`valor_global`, 
`siconv_programa`.`qualificacao` FROM `banco_proposta` LEFT JOIN `proponente_siconv` ON 
(`banco_proposta`.`proponente` = REPLACE(REPLACE(REPLACE(`proponente_siconv`.`cnpj`, '.', ''), '/', ''), '-', '')) 
LEFT JOIN `siconv_programa` ON (`banco_proposta`.`codigo_programa` = `siconv_programa`.`codigo`) 
WHERE `proponente_siconv`.`municipio_uf_sigla` = 'PB' AND proponente_siconv.esfera_administrativa = 'MUNICIPAL';

CREATE INDEX iAno ON TBMV_PROPOSTAS_PROGRAMAS_PARAÍBA(ano);

CREATE INDEX iProponente ON TBMV_PROPOSTAS_PROGRAMAS_PARAÍBA(proponente);

CREATE INDEX iSituacao ON TBMV_PROPOSTAS_PROGRAMAS_PARAÍBA(situacao);

CREATE INDEX iQualificacao ON TBMV_PROPOSTAS_PROGRAMAS_PARAÍBA(qualificacao);

ALTER TABLE TBMV_PROPOSTAS_PROGRAMAS_PARAÍBA ROW_FORMAT=Fixed;

CREATE VIEW MV_PROPOSTAS_PROGRAMAS_PARAÍBA AS 
SELECT * FROM TBMV_PROPOSTAS_PROGRAMAS_PARAÍBA;

CREATE TABLE TBMV_PROPOSTAS_PROGRAMAS_PARANÁ AS 
SELECT banco_proposta.`ano`, `banco_proposta`.`codigo_programa`, `banco_proposta`.`nome_proponente`, 
`banco_proposta`.`proponente`, `banco_proposta`.`situacao`, `banco_proposta`.`valor_global`, 
`siconv_programa`.`qualificacao` FROM `banco_proposta` LEFT JOIN `proponente_siconv` ON 
(`banco_proposta`.`proponente` = REPLACE(REPLACE(REPLACE(`proponente_siconv`.`cnpj`, '.', ''), '/', ''), '-', '')) 
LEFT JOIN `siconv_programa` ON (`banco_proposta`.`codigo_programa` = `siconv_programa`.`codigo`) 
WHERE `proponente_siconv`.`municipio_uf_sigla` = 'PR' AND proponente_siconv.esfera_administrativa = 'MUNICIPAL';

CREATE INDEX iAno ON TBMV_PROPOSTAS_PROGRAMAS_PARANÁ(ano);

CREATE INDEX iProponente ON TBMV_PROPOSTAS_PROGRAMAS_PARANÁ(proponente);

CREATE INDEX iSituacao ON TBMV_PROPOSTAS_PROGRAMAS_PARANÁ(situacao);

CREATE INDEX iQualificacao ON TBMV_PROPOSTAS_PROGRAMAS_PARANÁ(qualificacao);

ALTER TABLE TBMV_PROPOSTAS_PROGRAMAS_PARANÁ ROW_FORMAT=Fixed;

CREATE VIEW MV_PROPOSTAS_PROGRAMAS_PARANÁ AS 
SELECT * FROM TBMV_PROPOSTAS_PROGRAMAS_PARANÁ;

CREATE TABLE TBMV_PROPOSTAS_PROGRAMAS_PERNAMBUCO AS 
SELECT banco_proposta.`ano`, `banco_proposta`.`codigo_programa`, `banco_proposta`.`nome_proponente`, 
`banco_proposta`.`proponente`, `banco_proposta`.`situacao`, `banco_proposta`.`valor_global`, 
`siconv_programa`.`qualificacao` FROM `banco_proposta` LEFT JOIN `proponente_siconv` ON 
(`banco_proposta`.`proponente` = REPLACE(REPLACE(REPLACE(`proponente_siconv`.`cnpj`, '.', ''), '/', ''), '-', '')) 
LEFT JOIN `siconv_programa` ON (`banco_proposta`.`codigo_programa` = `siconv_programa`.`codigo`) 
WHERE `proponente_siconv`.`municipio_uf_sigla` = 'PE' AND proponente_siconv.esfera_administrativa = 'MUNICIPAL';

CREATE INDEX iAno ON TBMV_PROPOSTAS_PROGRAMAS_PERNAMBUCO(ano);

CREATE INDEX iProponente ON TBMV_PROPOSTAS_PROGRAMAS_PERNAMBUCO(proponente);

CREATE INDEX iSituacao ON TBMV_PROPOSTAS_PROGRAMAS_PERNAMBUCO(situacao);

CREATE INDEX iQualificacao ON TBMV_PROPOSTAS_PROGRAMAS_PERNAMBUCO(qualificacao);

ALTER TABLE TBMV_PROPOSTAS_PROGRAMAS_PERNAMBUCO ROW_FORMAT=Fixed;

CREATE VIEW MV_PROPOSTAS_PROGRAMAS_PERNAMBUCO AS 
SELECT * FROM TBMV_PROPOSTAS_PROGRAMAS_PERNAMBUCO;

CREATE TABLE TBMV_PROPOSTAS_PROGRAMAS_PIAUÍ AS 
SELECT banco_proposta.`ano`, `banco_proposta`.`codigo_programa`, `banco_proposta`.`nome_proponente`, 
`banco_proposta`.`proponente`, `banco_proposta`.`situacao`, `banco_proposta`.`valor_global`, 
`siconv_programa`.`qualificacao` FROM `banco_proposta` LEFT JOIN `proponente_siconv` ON 
(`banco_proposta`.`proponente` = REPLACE(REPLACE(REPLACE(`proponente_siconv`.`cnpj`, '.', ''), '/', ''), '-', '')) 
LEFT JOIN `siconv_programa` ON (`banco_proposta`.`codigo_programa` = `siconv_programa`.`codigo`) 
WHERE `proponente_siconv`.`municipio_uf_sigla` = 'PI' AND proponente_siconv.esfera_administrativa = 'MUNICIPAL';

CREATE INDEX iAno ON TBMV_PROPOSTAS_PROGRAMAS_PIAUÍ(ano);

CREATE INDEX iProponente ON TBMV_PROPOSTAS_PROGRAMAS_PIAUÍ(proponente);

CREATE INDEX iSituacao ON TBMV_PROPOSTAS_PROGRAMAS_PIAUÍ(situacao);

CREATE INDEX iQualificacao ON TBMV_PROPOSTAS_PROGRAMAS_PIAUÍ(qualificacao);

ALTER TABLE TBMV_PROPOSTAS_PROGRAMAS_PIAUÍ ROW_FORMAT=Fixed;

CREATE VIEW MV_PROPOSTAS_PROGRAMAS_PIAUÍ AS 
SELECT * FROM TBMV_PROPOSTAS_PROGRAMAS_PIAUÍ;

CREATE TABLE TBMV_PROPOSTAS_PROGRAMAS_RIO_DE_JANEIRO AS 
SELECT banco_proposta.`ano`, `banco_proposta`.`codigo_programa`, `banco_proposta`.`nome_proponente`, 
`banco_proposta`.`proponente`, `banco_proposta`.`situacao`, `banco_proposta`.`valor_global`, 
`siconv_programa`.`qualificacao` FROM `banco_proposta` LEFT JOIN `proponente_siconv` ON 
(`banco_proposta`.`proponente` = REPLACE(REPLACE(REPLACE(`proponente_siconv`.`cnpj`, '.', ''), '/', ''), '-', '')) 
LEFT JOIN `siconv_programa` ON (`banco_proposta`.`codigo_programa` = `siconv_programa`.`codigo`) 
WHERE `proponente_siconv`.`municipio_uf_sigla` = 'RJ' AND proponente_siconv.esfera_administrativa = 'MUNICIPAL';

CREATE INDEX iAno ON TBMV_PROPOSTAS_PROGRAMAS_RIO_DE_JANEIRO(ano);

CREATE INDEX iProponente ON TBMV_PROPOSTAS_PROGRAMAS_RIO_DE_JANEIRO(proponente);

CREATE INDEX iSituacao ON TBMV_PROPOSTAS_PROGRAMAS_RIO_DE_JANEIRO(situacao);

CREATE INDEX iQualificacao ON TBMV_PROPOSTAS_PROGRAMAS_RIO_DE_JANEIRO(qualificacao);

ALTER TABLE TBMV_PROPOSTAS_PROGRAMAS_RIO_DE_JANEIRO ROW_FORMAT=Fixed;

CREATE VIEW MV_PROPOSTAS_PROGRAMAS_RIO_DE_JANEIRO AS 
SELECT * FROM TBMV_PROPOSTAS_PROGRAMAS_RIO_DE_JANEIRO;

CREATE TABLE TBMV_PROPOSTAS_PROGRAMAS_RIO_GRANDE_DO_NORTE AS 
SELECT banco_proposta.`ano`, `banco_proposta`.`codigo_programa`, `banco_proposta`.`nome_proponente`, 
`banco_proposta`.`proponente`, `banco_proposta`.`situacao`, `banco_proposta`.`valor_global`, 
`siconv_programa`.`qualificacao` FROM `banco_proposta` LEFT JOIN `proponente_siconv` ON 
(`banco_proposta`.`proponente` = REPLACE(REPLACE(REPLACE(`proponente_siconv`.`cnpj`, '.', ''), '/', ''), '-', '')) 
LEFT JOIN `siconv_programa` ON (`banco_proposta`.`codigo_programa` = `siconv_programa`.`codigo`) 
WHERE `proponente_siconv`.`municipio_uf_sigla` = 'RN' AND proponente_siconv.esfera_administrativa = 'MUNICIPAL';

CREATE INDEX iAno ON TBMV_PROPOSTAS_PROGRAMAS_RIO_GRANDE_DO_NORTE(ano);

CREATE INDEX iProponente ON TBMV_PROPOSTAS_PROGRAMAS_RIO_GRANDE_DO_NORTE(proponente);

CREATE INDEX iSituacao ON TBMV_PROPOSTAS_PROGRAMAS_RIO_GRANDE_DO_NORTE(situacao);

CREATE INDEX iQualificacao ON TBMV_PROPOSTAS_PROGRAMAS_RIO_GRANDE_DO_NORTE(qualificacao);

ALTER TABLE TBMV_PROPOSTAS_PROGRAMAS_RIO_GRANDE_DO_NORTE ROW_FORMAT=Fixed;

CREATE VIEW MV_PROPOSTAS_PROGRAMAS_RIO_GRANDE_DO_NORTE AS 
SELECT * FROM TBMV_PROPOSTAS_PROGRAMAS_RIO_GRANDE_DO_NORTE;

CREATE TABLE TBMV_PROPOSTAS_PROGRAMAS_RIO_GRANDE_DO_SUL AS 
SELECT banco_proposta.`ano`, `banco_proposta`.`codigo_programa`, `banco_proposta`.`nome_proponente`, 
`banco_proposta`.`proponente`, `banco_proposta`.`situacao`, `banco_proposta`.`valor_global`, 
`siconv_programa`.`qualificacao` FROM `banco_proposta` LEFT JOIN `proponente_siconv` ON 
(`banco_proposta`.`proponente` = REPLACE(REPLACE(REPLACE(`proponente_siconv`.`cnpj`, '.', ''), '/', ''), '-', '')) 
LEFT JOIN `siconv_programa` ON (`banco_proposta`.`codigo_programa` = `siconv_programa`.`codigo`) 
WHERE `proponente_siconv`.`municipio_uf_sigla` = 'RS' AND proponente_siconv.esfera_administrativa = 'MUNICIPAL';

CREATE INDEX iAno ON TBMV_PROPOSTAS_PROGRAMAS_RIO_GRANDE_DO_SUL(ano);

CREATE INDEX iProponente ON TBMV_PROPOSTAS_PROGRAMAS_RIO_GRANDE_DO_SUL(proponente);

CREATE INDEX iSituacao ON TBMV_PROPOSTAS_PROGRAMAS_RIO_GRANDE_DO_SUL(situacao);

CREATE INDEX iQualificacao ON TBMV_PROPOSTAS_PROGRAMAS_RIO_GRANDE_DO_SUL(qualificacao);

ALTER TABLE TBMV_PROPOSTAS_PROGRAMAS_RIO_GRANDE_DO_SUL ROW_FORMAT=Fixed;

CREATE VIEW MV_PROPOSTAS_PROGRAMAS_RIO_GRANDE_DO_SUL AS 
SELECT * FROM TBMV_PROPOSTAS_PROGRAMAS_RIO_GRANDE_DO_SUL;

CREATE TABLE TBMV_PROPOSTAS_PROGRAMAS_RONDÔNIA AS 
SELECT banco_proposta.`ano`, `banco_proposta`.`codigo_programa`, `banco_proposta`.`nome_proponente`, 
`banco_proposta`.`proponente`, `banco_proposta`.`situacao`, `banco_proposta`.`valor_global`, 
`siconv_programa`.`qualificacao` FROM `banco_proposta` LEFT JOIN `proponente_siconv` ON 
(`banco_proposta`.`proponente` = REPLACE(REPLACE(REPLACE(`proponente_siconv`.`cnpj`, '.', ''), '/', ''), '-', '')) 
LEFT JOIN `siconv_programa` ON (`banco_proposta`.`codigo_programa` = `siconv_programa`.`codigo`) 
WHERE `proponente_siconv`.`municipio_uf_sigla` = 'RO' AND proponente_siconv.esfera_administrativa = 'MUNICIPAL';

CREATE INDEX iAno ON TBMV_PROPOSTAS_PROGRAMAS_RONDÔNIA(ano);

CREATE INDEX iProponente ON TBMV_PROPOSTAS_PROGRAMAS_RONDÔNIA(proponente);

CREATE INDEX iSituacao ON TBMV_PROPOSTAS_PROGRAMAS_RONDÔNIA(situacao);

CREATE INDEX iQualificacao ON TBMV_PROPOSTAS_PROGRAMAS_RONDÔNIA(qualificacao);

ALTER TABLE TBMV_PROPOSTAS_PROGRAMAS_RONDÔNIA ROW_FORMAT=Fixed;

CREATE VIEW MV_PROPOSTAS_PROGRAMAS_RONDÔNIA AS 
SELECT * FROM TBMV_PROPOSTAS_PROGRAMAS_RONDÔNIA;

CREATE TABLE TBMV_PROPOSTAS_PROGRAMAS_RORAIMA AS 
SELECT banco_proposta.`ano`, `banco_proposta`.`codigo_programa`, `banco_proposta`.`nome_proponente`, 
`banco_proposta`.`proponente`, `banco_proposta`.`situacao`, `banco_proposta`.`valor_global`, 
`siconv_programa`.`qualificacao` FROM `banco_proposta` LEFT JOIN `proponente_siconv` ON 
(`banco_proposta`.`proponente` = REPLACE(REPLACE(REPLACE(`proponente_siconv`.`cnpj`, '.', ''), '/', ''), '-', '')) 
LEFT JOIN `siconv_programa` ON (`banco_proposta`.`codigo_programa` = `siconv_programa`.`codigo`) 
WHERE `proponente_siconv`.`municipio_uf_sigla` = 'RR' AND proponente_siconv.esfera_administrativa = 'MUNICIPAL';

CREATE INDEX iAno ON TBMV_PROPOSTAS_PROGRAMAS_RORAIMA(ano);

CREATE INDEX iProponente ON TBMV_PROPOSTAS_PROGRAMAS_RORAIMA(proponente);

CREATE INDEX iSituacao ON TBMV_PROPOSTAS_PROGRAMAS_RORAIMA(situacao);

CREATE INDEX iQualificacao ON TBMV_PROPOSTAS_PROGRAMAS_RORAIMA(qualificacao);

ALTER TABLE TBMV_PROPOSTAS_PROGRAMAS_RORAIMA ROW_FORMAT=Fixed;

CREATE VIEW MV_PROPOSTAS_PROGRAMAS_RORAIMA AS 
SELECT * FROM TBMV_PROPOSTAS_PROGRAMAS_RORAIMA;

CREATE TABLE TBMV_PROPOSTAS_PROGRAMAS_TOCANTINS AS 
SELECT banco_proposta.`ano`, `banco_proposta`.`codigo_programa`, `banco_proposta`.`nome_proponente`, 
`banco_proposta`.`proponente`, `banco_proposta`.`situacao`, `banco_proposta`.`valor_global`, 
`siconv_programa`.`qualificacao` FROM `banco_proposta` LEFT JOIN `proponente_siconv` ON 
(`banco_proposta`.`proponente` = REPLACE(REPLACE(REPLACE(`proponente_siconv`.`cnpj`, '.', ''), '/', ''), '-', '')) 
LEFT JOIN `siconv_programa` ON (`banco_proposta`.`codigo_programa` = `siconv_programa`.`codigo`) 
WHERE `proponente_siconv`.`municipio_uf_sigla` = 'TO' AND proponente_siconv.esfera_administrativa = 'MUNICIPAL';

CREATE INDEX iAno ON TBMV_PROPOSTAS_PROGRAMAS_TOCANTINS(ano);

CREATE INDEX iProponente ON TBMV_PROPOSTAS_PROGRAMAS_TOCANTINS(proponente);

CREATE INDEX iSituacao ON TBMV_PROPOSTAS_PROGRAMAS_TOCANTINS(situacao);

CREATE INDEX iQualificacao ON TBMV_PROPOSTAS_PROGRAMAS_TOCANTINS(qualificacao);

ALTER TABLE TBMV_PROPOSTAS_PROGRAMAS_TOCANTINS ROW_FORMAT=Fixed;

CREATE VIEW MV_PROPOSTAS_PROGRAMAS_TOCANTINS AS 
SELECT * FROM TBMV_PROPOSTAS_PROGRAMAS_TOCANTINS;

CREATE TABLE TBMV_PROPOSTAS_PROGRAMAS_SERGIPE AS 
SELECT banco_proposta.`ano`, `banco_proposta`.`codigo_programa`, `banco_proposta`.`nome_proponente`, 
`banco_proposta`.`proponente`, `banco_proposta`.`situacao`, `banco_proposta`.`valor_global`, 
`siconv_programa`.`qualificacao` FROM `banco_proposta` LEFT JOIN `proponente_siconv` ON 
(`banco_proposta`.`proponente` = REPLACE(REPLACE(REPLACE(`proponente_siconv`.`cnpj`, '.', ''), '/', ''), '-', '')) 
LEFT JOIN `siconv_programa` ON (`banco_proposta`.`codigo_programa` = `siconv_programa`.`codigo`) 
WHERE `proponente_siconv`.`municipio_uf_sigla` = 'SE' AND proponente_siconv.esfera_administrativa = 'MUNICIPAL';

CREATE INDEX iAno ON TBMV_PROPOSTAS_PROGRAMAS_SERGIPE(ano);

CREATE INDEX iProponente ON TBMV_PROPOSTAS_PROGRAMAS_SERGIPE(proponente);

CREATE INDEX iSituacao ON TBMV_PROPOSTAS_PROGRAMAS_SERGIPE(situacao);

CREATE INDEX iQualificacao ON TBMV_PROPOSTAS_PROGRAMAS_SERGIPE(qualificacao);

ALTER TABLE TBMV_PROPOSTAS_PROGRAMAS_SERGIPE ROW_FORMAT=Fixed;

CREATE VIEW MV_PROPOSTAS_PROGRAMAS_SERGIPE AS 
SELECT * FROM TBMV_PROPOSTAS_PROGRAMAS_SERGIPE;

CREATE TABLE TBMV_PROPOSTAS_PROGRAMAS_SÃO_PAULO AS 
SELECT banco_proposta.`ano`, `banco_proposta`.`codigo_programa`, `banco_proposta`.`nome_proponente`, 
`banco_proposta`.`proponente`, `banco_proposta`.`situacao`, `banco_proposta`.`valor_global`, 
`siconv_programa`.`qualificacao` FROM `banco_proposta` LEFT JOIN `proponente_siconv` ON 
(`banco_proposta`.`proponente` = REPLACE(REPLACE(REPLACE(`proponente_siconv`.`cnpj`, '.', ''), '/', ''), '-', '')) 
LEFT JOIN `siconv_programa` ON (`banco_proposta`.`codigo_programa` = `siconv_programa`.`codigo`) 
WHERE `proponente_siconv`.`municipio_uf_sigla` = 'SP' AND proponente_siconv.esfera_administrativa = 'MUNICIPAL';

CREATE INDEX iAno ON TBMV_PROPOSTAS_PROGRAMAS_SÃO_PAULO(ano);

CREATE INDEX iProponente ON TBMV_PROPOSTAS_PROGRAMAS_SÃO_PAULO(proponente);

CREATE INDEX iSituacao ON TBMV_PROPOSTAS_PROGRAMAS_SÃO_PAULO(situacao);

CREATE INDEX iQualificacao ON TBMV_PROPOSTAS_PROGRAMAS_SÃO_PAULO(qualificacao);

ALTER TABLE TBMV_PROPOSTAS_PROGRAMAS_SÃO_PAULO ROW_FORMAT=Fixed;

CREATE VIEW MV_PROPOSTAS_PROGRAMAS_SÃO_PAULO AS 
SELECT * FROM TBMV_PROPOSTAS_PROGRAMAS_SÃO_PAULO;

CREATE TABLE TBMV_PROPOSTAS_PROGRAMAS_SANTA_CATARINA AS 
SELECT banco_proposta.`ano`, `banco_proposta`.`codigo_programa`, `banco_proposta`.`nome_proponente`, 
`banco_proposta`.`proponente`, `banco_proposta`.`situacao`, `banco_proposta`.`valor_global`, 
`siconv_programa`.`qualificacao` FROM `banco_proposta` LEFT JOIN `proponente_siconv` ON 
(`banco_proposta`.`proponente` = REPLACE(REPLACE(REPLACE(`proponente_siconv`.`cnpj`, '.', ''), '/', ''), '-', '')) 
LEFT JOIN `siconv_programa` ON (`banco_proposta`.`codigo_programa` = `siconv_programa`.`codigo`) 
WHERE `proponente_siconv`.`municipio_uf_sigla` = 'SC' AND proponente_siconv.esfera_administrativa = 'MUNICIPAL';

CREATE INDEX iAno ON TBMV_PROPOSTAS_PROGRAMAS_SANTA_CATARINA(ano);

CREATE INDEX iProponente ON TBMV_PROPOSTAS_PROGRAMAS_SANTA_CATARINA(proponente);

CREATE INDEX iSituacao ON TBMV_PROPOSTAS_PROGRAMAS_SANTA_CATARINA(situacao);

CREATE INDEX iQualificacao ON TBMV_PROPOSTAS_PROGRAMAS_SANTA_CATARINA(qualificacao);

ALTER TABLE TBMV_PROPOSTAS_PROGRAMAS_SANTA_CATARINA ROW_FORMAT=Fixed;

CREATE VIEW MV_PROPOSTAS_PROGRAMAS_SANTA_CATARINA AS SELECT * FROM TBMV_PROPOSTAS_PROGRAMAS_SANTA_CATARINA;

CREATE TABLE TBMV_PROGRAMAS_RELATORIOS AS
SELECT DISTINCT codigo, atende, ano, estados FROM siconv_programa;

CREATE INDEX iEstado ON TBMV_PROGRAMAS_RELATORIOS(estados);

CREATE INDEX iAno ON TBMV_PROGRAMAS_RELATORIOS(ano);

CREATE INDEX iCodigo ON TBMV_PROGRAMAS_RELATORIOS(codigo);

CREATE INDEX iAtende ON TBMV_PROGRAMAS_RELATORIOS(atende);

ALTER TABLE TBMV_PROGRAMAS_RELATORIOS ROW_FORMAT=Fixed;

CREATE VIEW MV_PROGRAMAS_RELATORIOS AS 
SELECT * FROM TBMV_PROGRAMAS_RELATORIOS;
