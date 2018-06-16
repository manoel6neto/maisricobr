CREATE VIEW `programas_vigencia` AS
    SELECT 
        `siconv_programa`.`codigo` AS `codigo`,
        `siconv_programa`.`nome` AS `nome`,
        `siconv_programa`.`orgao` AS `orgao`,
        `siconv_programa`.`orgao_vinculado` AS `orgao_vinculado`,
        `siconv_programa`.`qualificacao` AS `qualificacao`,
        `siconv_programa`.`atende` AS `atende`,
        `siconv_programa`.`descricao` AS `descricao`,
        `siconv_programa`.`observacao` AS `observacao`,
        `siconv_programa`.`data_inicio` AS `data_inicio`,
        `siconv_programa`.`data_inicio_benef` AS `data_inicio_benef`,
        `siconv_programa`.`data_inicio_parlam` AS `data_inicio_parlam`,
        `siconv_programa`.`data_fim` AS `data_fim`,
        `siconv_programa`.`data_fim_benef` AS `data_fim_benef`,
        `siconv_programa`.`data_fim_parlam` AS `data_fim_parlam`,
        `siconv_programa`.`ano` AS `ano`,
        `siconv_programa`.`estados` AS `estados`,
        `siconv_programa`.`link` AS `link`,
        `siconv_programa`.`tem_atualizacao` AS `tem_atualizacao`,
        `siconv_programa`.`data_disp` AS `data_disp`,
        `siconv_programa`.`programa_novo` AS `programa_novo`,
        `siconv_programa`.`tem_chamamento` AS `tem_chamamento`,
        `siconv_programa`.`objeto` AS `objeto`,
        `siconv_programa`.`anexos` AS `anexos`,
        `siconv_programa`.`regra_contrapartida` AS `regra_contrapartida`
    FROM
        `siconv_programa`
    WHERE
        ((`siconv_programa`.`data_fim` >= CURDATE()) OR (`siconv_programa`.`data_fim_benef` >= CURDATE()) OR (`siconv_programa`.`data_fim_parlam` >= CURDATE()));
