<?php

class get_programas extends CI_Controller {

    public function __construct() {
        parent::__construct();

        $this->cookie_file_path = tempnam("/tmp", "CURLCOOKIE" . rand());
        $this->login = null;
    }

    function programas_abertos() {
        ini_set("max_execution_time", 0);
        ini_set("memory_limit", "-1");

        try {
            $this->load->model('programa_model');
            $this->programa_model->programas_abertos();
        } catch (Exception $ex) {
            $this->load->model("log_model");
            $this->log_model->log_erro("get_programas/programas_abertos", "17", $ex->getMessage());
        }

        die();
    }

    public function get_ids_programas($numPagInicio = null) {
        ini_set('max_execution_time', 0);
        ini_set("memory_limit", "-1");

        try {
            $this->db->truncate('ids_programa');

            echo date("d/m/Y H:i:s");

            $this->load->model('usuariomodel');
            $this->load->model('get_programas_model');

            if ($numPagInicio == null) {
                $numPagInicio = 1;
//            $this->usuariomodel->enviar_email_cron('Programas - Serviço iniciado em ' . date('d/m/Y H:i:s'), "IDs Programas - " . $this->input->get('ano', TRUE) . " - " . date('d/m/Y H:i:s'));
            }

            //$this->autentica_siconv->new_init_siconv_do_login ( "guest", "guest", $this->login, $this->cookie_file_path );
            $this->load->model('senha_eliumar');
            $eliumar = $this->senha_eliumar->get_password();
            $this->autentica_siconv->new_init_siconv_do_login($eliumar->cpf, $eliumar->senha, $this->login, $this->cookie_file_path);

            $documento = utf8_decode($this->autentica_siconv->new_obter_pagina("https://www.convenios.gov.br/siconv/ForwardAction.do?modulo=programa&path=/ConsultarPrograma/ConsultarPrograma.do", $this->login, $this->cookie_file_path));
            $documento = $this->removeSpaceSurplus($documento);
            echo $documento;

            $invalidatePageControlCounter = $this->getTextBetweenTags($documento, "name=\"invalidatePageControlCounter\" value=\"", "\"\/>");
            // var_dump($invalidatePageControlCounter);

            $fields = array(
                'invalidatePageControlCounter' => $invalidatePageControlCounter [0],
                'orgao' => '',
                'qualificacaoProponente' => 0,
                'dataInicioVigencia' => '',
                'dataFimVigencia' => '',
                'dataInicioBeneficiarioEspecifico' => '',
                'dataFimBeneficiarioEspecifico' => '',
                'dataInicioEmendaParlamentar' => '',
                'dataFimEmendaParlamentar' => '',
                'apto' => 3,
                'anoPrograma' => $this->input->get('ano', TRUE),
                'codigoPrograma' => '',
                'nomePrograma' => '',
                'descricao' => '',
                'categoria' => '',
                'codigoParlamentar' => '',
                'numeroEmendaParlamentar' => '',
                'estado' => '',
                'modalidade' => ''
            );

            // var_dump($fields);

            $fieldStrings = null;
            foreach ($fields as $k => $v)
                $fieldStrings .= $k . "=" . $v . "&";

            $pagina = "https://www.convenios.gov.br/siconv/ConsultarPrograma/PreenchaOsDadosDaConsultaDeProgramaDeConvenioConsultar.do";

            echo $pagina . "?" . $fieldStrings;

            // A FAZER
            $documento = utf8_encode($this->obter_pagina_post($pagina, $fields, $fieldStrings));
            $documento = $this->removeSpaceSurplus($documento);
            echo $documento;

            $pages = $this->getTextBetweenTags($documento, "<span class=\"pagelinks\">", "<\/span>");
            $pages = explode("de", $pages [0]);
            $numPages = explode("(", $pages [1]);

            $numRegs = explode("item", $numPages [1]);

            $numRegs = trim($numRegs [0]);
            $numPages = trim($numPages [0]);

            echo $numPages . " - " . $numRegs;

            $numPagesFim = $numPages;

            $listaIDS = array();
            for ($i = $numPagInicio; $i <= $numPagesFim; $i ++) {
                $tabela = $this->getTextBetweenTags($documento, "<tbody id=\"tbodyrow\">", "<\/tbody>");
                $links = $this->getTextBetweenTags($tabela [0], "<a href=", "<\/a>");
                $codigos = array();
                //CARREGAR O ARRAY DE CODIGOS COM OS LINKS COMO CHAVE E O CODIGO COMO VALOR PARA PODER ASSOCIAR DEPOIS
                // var_dump($links);
                foreach ($links as $link) {
                    $parteLink = explode("id=", $link);
                    $parteLink = explode('" onmouseover', $parteLink [1]);
                    $id = $parteLink [0];
                    // echo $id."<br>";
                    $listaIDS [] = array(
                        'num_id' => $id,
                        'ano' => $this->input->get('ano', TRUE),
                        'codigo' => $codigos[$link]
                    );
                }

                $pagina = "https://www.convenios.gov.br/siconv/ConsultarPrograma/PreenchaOsDadosDaConsultaDeProgramaDeConvenioConsultar.do?d-16544-t=listaProgramas&d-16544-p=" . ($i + 1) . "&d-16544-g=" . ($i + 1) . "&tipo_consulta=CONSULTA_COMPLETA";
                $documento = utf8_decode($this->autentica_siconv->new_obter_pagina($pagina, $this->login, $this->cookie_file_path));
                echo $documento;
                $documento = $this->removeSpaceSurplus($documento);
            }

            $this->db->insert_batch('ids_programa', $listaIDS);

//        $this->usuariomodel->enviar_email_cron('Programas - Serviço finalizado em ' . date('d/m/Y H:i:s'), "IDs Programas - " . $this->input->get('ano', TRUE) . " - " . date('d/m/Y H:i:s'));

            echo date("d/m/Y H:i:s");
        } catch (Exception $ex) {
            $this->load->model("log_model");
            $this->log_model->log_erro("get_programas/get_ids_programas", "32", $ex->getMessage());
        }
    }

    public function get_ids_programas_aptos($numPagInicio = null) {
        ini_set('max_execution_time', 0);
        ini_set("memory_limit", "-1");

        try {
            $this->db->truncate('ids_programa_apto');

            $this->load->model('usuariomodel');
            $this->load->model('get_programas_model');

            if ($numPagInicio == null) {
                $numPagInicio = 1;
//            $this->usuariomodel->enviar_email_cron('Programas - Serviço iniciado em ' . date('d/m/Y H:i:s'), "IDs Programas - " . $this->input->get('ano', TRUE) . " - " . date('d/m/Y H:i:s'));
            }

            //$this->autentica_siconv->new_init_siconv_do_login ( "guest", "guest", $this->login, $this->cookie_file_path );
            $this->load->model('senha_eliumar');
            $eliumar = $this->senha_eliumar->get_password();
            $this->autentica_siconv->new_init_siconv_do_login($eliumar->cpf, $eliumar->senha, $this->login, $this->cookie_file_path);

            $documento = utf8_decode($this->autentica_siconv->new_obter_pagina("https://www.convenios.gov.br/siconv/ForwardAction.do?modulo=programa&path=/ConsultarPrograma/ConsultarPrograma.do", $this->login, $this->cookie_file_path));
            $documento = $this->removeSpaceSurplus($documento);

            $invalidatePageControlCounter = $this->getTextBetweenTags($documento, "name=\"invalidatePageControlCounter\" value=\"", "\"\/>");
            // var_dump($invalidatePageControlCounter);

            $fields = array(
                'invalidatePageControlCounter' => $invalidatePageControlCounter [0],
                'orgao' => '',
                'qualificacaoProponente' => 0,
                'dataInicioVigencia' => '',
                'dataFimVigencia' => '',
                'dataInicioBeneficiarioEspecifico' => '',
                'dataFimBeneficiarioEspecifico' => '',
                'dataInicioEmendaParlamentar' => '',
                'dataFimEmendaParlamentar' => '',
                'apto' => 'SIM',
                'anoPrograma' => $this->input->get('ano', TRUE),
                'codigoPrograma' => '',
                'nomePrograma' => '',
                'descricao' => '',
                'categoria' => '',
                'codigoParlamentar' => '',
                'numeroEmendaParlamentar' => '',
                'estado' => '',
                'modalidade' => ''
            );

            // var_dump($fields);
            $fieldStrings = null;
            foreach ($fields as $k => $v)
                $fieldStrings .= $k . "=" . $v . "&";

            $fieldStrings = substr($fieldStrings, 0, -1);
            $fieldStrings = rawurldecode($fieldStrings);

            $pagina = "https://www.convenios.gov.br/siconv/ConsultarPrograma/PreenchaOsDadosDaConsultaDeProgramaDeConvenioConsultar.do";

            //echo $pagina . "?" . $fieldStrings;
            // A FAZER
            $documento = utf8_encode($this->obter_pagina_post($pagina, $fields, $fieldStrings));
            $documento = $this->removeSpaceSurplus($documento);

            $pages = $this->getTextBetweenTags($documento, "<span class=\"pagelinks\">", "<\/span>");
            $pages = explode("de", $pages [0]);
            $numPages = explode("(", $pages [1]);

            $numRegs = explode("item", $numPages [1]);

            $numRegs = trim($numRegs [0]);
            $numPages = trim($numPages [0]);

            $numPagesFim = $numPages;

            $listaIDS = array();
            for ($i = $numPagInicio; $i <= $numPagesFim; $i ++) {
                $tabela = $this->getTextBetweenTags($documento, "<tbody id=\"tbodyrow\">", "<\/tbody>");
                $links = $this->getTextBetweenTags($tabela [0], "<a href=", "<\/a>");
                $codigos = array();
                //CARREGAR O ARRAY DE CODIGOS COM OS LINKS COMO CHAVE E O CODIGO COMO VALOR PARA PODER ASSOCIAR DEPOIS
                // var_dump($links);
                foreach ($links as $link) {
                    $parteLink = explode("id=", $link);
                    $parteLink = explode('" onmouseover', $parteLink [1]);
                    $id = $parteLink [0];
                    // echo $id."<br>";
                    $listaIDS [] = array(
                        'num_id' => $id,
                        'ano' => $this->input->get('ano', TRUE),
                        'codigo' => $codigos[$link]
                    );
                }

                $pagina = "https://www.convenios.gov.br/siconv/ConsultarPrograma/PreenchaOsDadosDaConsultaDeProgramaDeConvenioConsultar.do?d-16544-t=listaProgramas&d-16544-p=" . ($i + 1) . "&d-16544-g=" . ($i + 1) . "&tipo_consulta=CONSULTA_COMPLETA";
                $documento = utf8_decode($this->autentica_siconv->new_obter_pagina($pagina, $this->login, $this->cookie_file_path));
                $documento = $this->removeSpaceSurplus($documento);
            }

            $this->db->insert_batch('ids_programa_apto', $listaIDS);

//        $this->usuariomodel->enviar_email_cron('Programas - Serviço finalizado em ' . date('d/m/Y H:i:s'), "IDs Programas - " . $this->input->get('ano', TRUE) . " - " . date('d/m/Y H:i:s'));
        } catch (Exception $ex) {
            $this->load->model("log_model");
            $this->log_model->log_erro("get_programas/get_ids_programas", "32", $ex->getMessage());
        }
    }

    public function retorna_num_ids() {
        ini_set('max_execution_time', 0);
        ini_set("memory_limit", "-1");

        try {
            $this->db->select('num_id');

            $this->db->where('ano', $this->input->get('ano', TRUE));

            $query = $this->db->get('ids_programa')->result();

            echo count($query);
        } catch (Exception $ex) {
            $this->load->model("log_model");
            $this->log_model->log_erro("get_programas/retorna_num_ids", "258", $ex->getMessage());
        }
    }

    public function retorna_num_ids_aptos() {
        ini_set('max_execution_time', 0);
        ini_set("memory_limit", "-1");

        try {
            $this->db->select('num_id');

            $this->db->where('ano', $this->input->get('ano', TRUE));

            $query = $this->db->get('ids_programa_apto')->result();

            echo count($query);
        } catch (Exception $ex) {
            $this->load->model("log_model");
            $this->log_model->log_erro("get_programas/retorna_num_ids_aptos", "276", $ex->getMessage());
        }
    }

    public function update_programas_siconv() {
        ini_set('max_execution_time', 0);
        ini_set("memory_limit", "-1");

        $id_programa = $this->input->get('id_programa', TRUE);

        $this->load->model('usuariomodel');
        $this->load->model('get_programas_model');
        $this->load->model("log_model");

        $this->load->model('senha_eliumar');
        $this->login = null;
        $this->cookie_file_path = tempnam("/tmp", "CURLCOOKIE" . rand());
        $eliumar = $this->senha_eliumar->get_password();
        $this->autentica_siconv->new_init_siconv_do_login($eliumar->cpf, $eliumar->senha, $this->login, $this->cookie_file_path);

        try {
            $pagina = "https://www.convenios.gov.br/siconv/ConsultarPrograma/ResultadoDaConsultaDeProgramaDeConvenioDetalhar.do?id=" . $id_programa;
            $documento = utf8_decode($this->autentica_siconv->new_obter_pagina($pagina, $this->login, $this->cookie_file_path));
            $documento = $this->removeSpaceSurplus($documento);

            $documento2 = $documento;

            $dados_programa = array();

            $codigo = $this->getTextBetweenTags($documento, "tr-alterarProgramaCodigo\" >", " <\/tr>");
            $codigo = $this->getTextBetweenTags($codigo [0], "<td class=\"field\"> ", " <\/td>");

            $nome = $this->getTextBetweenTags($documento, "tr-alterarProgramaNomeDoPrograma\" >", " <\/tr>");
            $nome = $this->getTextBetweenTags($nome [0], "<td class=\"field\"> ", " <\/td>");

            $orgao = $this->getTextBetweenTags($documento, "tr-alterarProgramaOrgao\" >", " <\/tr>");
            $orgao = $this->getTextBetweenTags($orgao [0], "<td class=\"field\"> ", " <\/td>");
            if (!empty($orgao [0])) {
                $orgao = strtok($orgao [0], "-");
                $orgao = trim(strtok("-"));
            }

            $orgao_vinculado = $this->getTextBetweenTags($documento, "tr-alterarProgramaOrgaoSubordinado\" >", " <\/tr>");
            if (!empty($orgao_vinculado [0])) {
                $orgao_vinculado = $this->getTextBetweenTags($orgao_vinculado [0], "<td class=\"field\"> ", " <\/td>");
                $orgao_vinculado = strtok($orgao_vinculado [0], "-");
                $orgao_vinculado = trim(strtok("-"));
            } else {
                $orgao_vinculado = $this->getTextBetweenTags($documento, "tr-alterarProgramaOrgaoExecutor\" >", " <\/tr>");
                if (!empty($orgao_vinculado [0])) {
                    $orgao_vinculado = $this->getTextBetweenTags($orgao_vinculado [0], "<td class=\"field\"> ", " <\/td>");
                    $orgao_vinculado = strtok($orgao_vinculado [0], "-");
                    $orgao_vinculado = trim(strtok("-"));
                } else
                    $orgao_vinculado = $orgao;
            }

            $qualificacao = $this->getTextBetweenTags($documento, "tr-alterarProgramaQualificacaoProponente\" >", " <\/tr>");
            $qualificacao = $this->getTextBetweenTags($qualificacao [0], "<td class=\"field\"> ", " <\/td>");

            $atende = $this->getTextBetweenTags($documento, "tr-alterarProgramaProgramaAtendea\" >", " <\/tr>");
            $atende = $this->getTextBetweenTags($atende [0], "<td class=\"field\"> ", " <\/td>");

            $textArea = $this->getTextBetweenTags($documento, "<td class=fieldCaixa colspan=2> ", " <\/td>");
            $descricao = $textArea [0];
            $observacao = $textArea [1];

            $estados = $this->getTextBetweenTags($documento, "tr-alterarProgramaEstadosHabilitados\" >", " <\/tr>");
            $estados = $this->getTextBetweenTags($estados [0], "<td class=\"field\"> ", " <\/td>");

            $data_inicio = $this->getTextBetweenTags($documento, "tr-alterarProgramaDataInicioVigencia\" >", " <\/tr>");
            if (!empty($data_inicio))
                $data_inicio = $this->getTextBetweenTags($documento, "\"dataInicioVigencia\" value=\"", "\" onmouseup=");

            $data_fim = $this->getTextBetweenTags($documento, "tr-alterarProgramaDataFimdeVigencia\" > ", "<\/tr>");
            if (!empty($data_fim))
                $data_fim = $this->getTextBetweenTags($data_fim [0], "\"dataFimdeVigencia\" value=\"", "\" onmouseup=");

            $data_inicio_benef = $this->getTextBetweenTags($documento, "tr-alterarProgramaDataInicioBeneficiarioEspecifico\" >", " <\/tr>");
            if (!empty($data_inicio_benef))
                $data_inicio_benef = $this->getTextBetweenTags($data_inicio_benef [0], "\"dataInicioBeneficiarioEspecifico\" value=\"", "\" onmouseup=");

            $data_fim_benef = $this->getTextBetweenTags($documento, "tr-alterarProgramaDataFimBeneficiarioEspecifico\" > ", "<\/tr>");
            if (!empty($data_fim_benef))
                $data_fim_benef = $this->getTextBetweenTags($data_fim_benef [0], "\"dataFimBeneficiarioEspecifico\" value=\"", "\" onmouseup=");

            $data_inicio_parlarm = $this->getTextBetweenTags($documento, "tr-alterarProgramaDataInicioEmendaParlamentar\" >", " <\/tr>");
            if (!empty($data_inicio_parlarm))
                $data_inicio_parlarm = $this->getTextBetweenTags($data_inicio_parlarm [0], "name=\"dataInicioEmendaParlamentar\" value=\"", "\" onmouseup=");

            $data_fim_parlarm = $this->getTextBetweenTags($documento, "tr-alterarProgramaDataFimEmendaParlamentar\" >", "<\/tr>");
            if (!empty($data_fim_parlarm))
                $data_fim_parlarm = $this->getTextBetweenTags($data_fim_parlarm [0], "name=\"dataFimEmendaParlamentar\" value=\"", "\" onmouseup=");

            $data_disp = $this->getTextBetweenTags($documento, "tr-alterarProgramaDataDeDisponibilizacao\">", "<\/tr>");
            if (!empty($data_disp))
                $data_disp = $this->getTextBetweenTags($data_disp [0], "name=\"dataDeDisponibilizacao\" value=\"", "\" onmouseup=");

            $data_renov_disp = $this->getTextBetweenTags($documento, "tr-alterarProgramaDataUltimaRenovacaoDisponibilizacao\" >", "<\/tr>");
            if (!empty($data_renov_disp))
                $data_renov_disp = $this->getTextBetweenTags($data_renov_disp [0], "<td class=\"field\"> ", " <\/td>");

            // Novos dados adicionados (chamamento, objeto, anexos)
            // Chamamento
            $chamamento = $this->getTextBetweenTags($documento, '<tr class="chamamentoPublico" id="tr-alterarProgramaChamamentoPublico" >', '<\/tr>');
            if (!empty($chamamento)) {
                $chamamento = $this->getTextBetweenTags($chamamento [0], '<td class="field">', '<\/td>');
                if (!empty($chamamento)) {
                    if (trim($chamamento [0]) == "Sim") {
                        $chamamento = 1;
                    } else {
                        $chamamento = 0;
                    }
                }
            }
            $chamamento = 0;

            // Objeto
            $pagina = "https://www.convenios.gov.br/siconv/programa/EditarObjetos/EditarObjetos.do?id=" . $id_programa;
            $documento2 = utf8_decode($this->autentica_siconv->new_obter_pagina($pagina, $this->login, $this->cookie_file_path));
            $documento2 = $this->removeSpaceSurplus($documento2);

            $objeto = $this->getTextBetweenTags($documento2, '<div id="listaObjetos" class="table"> <table id="row"> <thead> <tr> <th class="nome">Nome<\/th> <th><\/th><\/tr><\/thead> <tbody id="tbodyrow"> <tr class="odd"> <td> <div class="nome">', '<\/div> <\/td> <td> <\/td><\/tr><\/tbody><\/table><\/div>');
            if (!empty($objeto)) {
                $objeto = trim($objeto [0]);
            }

            // Anexos
            $anexos = 0;
            $pagina = "https://www.convenios.gov.br/siconv/programa/UploadArquivoPrograma/UploadArquivoPrograma.do?id=" . $id_programa;
            $documento2 = utf8_decode($this->autentica_siconv->new_obter_pagina($pagina, $this->login, $this->cookie_file_path));
            $documento2 = $this->removeSpaceSurplus($documento2);

            if (strpos($documento2, "Nenhum registro foi encontrado") === false) {
                try {
                    $insert_anexo['id_programa'] = $id_programa;
                    $insert_anexo['nome_arquivo'] = $this->getTextBetweenTags($documento2, '<div class="nomeArquivo">', '<\/div>');
                    $insert_anexo['data_anexo'] = $this->getTextBetweenTags($documento2, '<div class="dataUpload">', '<\/div>');
                    $insert_anexo['descricao'] = $this->getTextBetweenTags($documento2, '<div class="descricao">', '<\/div>');
                    $links = $this->getTextBetweenTags($documento2, '<div class="descricao">', '<\/table>');
                    $insert_anexo['link'] = $this->getTextBetweenTags($links[0], '<a href="javascript:document.location=\'', '\'');
                    if ($insert_anexo['id_programa'] != NULL && is_string($insert_anexo['id_programa']) == TRUE && $insert_anexo['nome_arquivo'] != NULL && is_string($insert_anexo['nome_arquivo'][0]) == TRUE && $insert_anexo['data_anexo'] != NULL && is_string($insert_anexo['data_anexo'][0]) == TRUE && $insert_anexo['descricao'] != NULL && is_string($insert_anexo['descricao'][0]) == TRUE && $insert_anexo['link'] != NULL && is_string($insert_anexo['link'][0]) == TRUE) {
                        $anexos = 1;
                    }
                } catch (Exception $ex) {
                    $anexos = 0;
                    $this->log_model->log_erro("get_programas/update_programa", "296", $ex->getMessage());
                }
            }

            //Editais
            $editais = 0;
            $link = "https://www.convenios.gov.br/siconv/ConsultarPrograma/ResultadoDaConsultaDeProgramaDeConvenioDetalhar.do?id=" . $id_programa;
            $documento2 = utf8_decode($this->autentica_siconv->new_obter_pagina($link, $this->login, $this->cookie_file_path));
            $documento2 = $this->removeSpaceSurplus($documento2);

            if (strpos($documento2, "Nenhum registro foi encontrado") === false) {
                try {
                    $insert_edital['id_programa'] = $id_programa;
                    $insert_edital['nome_arquivo'] = $this->getTextBetweenTags($documento2, '<div class="nome">', '<\/div>');
                    $unset_array = array();
                    foreach ($insert_edital['nome_arquivo'] as $key => $value) {
                        if (strpos($insert_edital['nome_arquivo'][$key], '.') === false) {
                            array_push($unset_array, $key);
                        }
                    }
                    if (!empty($unset_array)) {
                        foreach ($unset_array as $v) {
                            unset($insert_edital['nome_arquivo'][$v]);
                        }
                    }
                    $links = $this->getTextBetweenTags($documento2, '<div class="nome">', 'class="buttonLink">');
                    $insert_edital['link'] = $this->getTextBetweenTags($links[0], '<a href="javascript:document.location=\'', '\'');
                    if ($insert_edital['id_programa'] != NULL && is_string($insert_edital['id_programa']) == TRUE && $insert_edital['nome_arquivo'] != NULL && is_string($insert_edital['nome_arquivo'][0]) == TRUE && $insert_edital['link'] != NULL && is_string($insert_edital['link'][0]) == TRUE) {
                        $editais = 1;
                    }
                } catch (Exception $ex) {
                    $editais = 0;
                    $this->log_model->log_erro("get_programas/update_programa", "296", $ex->getMessage());
                }
            }

            // Regra de contrapartida
            $pagina_consulta = "https://www.convenios.gov.br/siconv/programa/ListarRegras/ListarRegras.do?id=" . $id_programa;
            $documento2 = utf8_decode($this->autentica_siconv->new_obter_pagina($pagina_consulta, $this->login, $this->cookie_file_path));
            $documento2 = $this->removeSpaceSurplus($documento2);

            $contra_partida = $this->getTextBetweenTags($documento2, '<div id="regras" class="table">', '<\/div><div id="tableFooter">');
            if (!empty($contra_partida)) {
                $contra_partida = trim($contra_partida [0]);
            }

            $link = "https://www.convenios.gov.br/siconv/ConsultarPrograma/ResultadoDaConsultaDeProgramaDeConvenioDetalhar.do?id=" . $id_programa;

            $dados_programa = array(
                'codigo' => utf8_encode($codigo [0]),
                'nome' => utf8_encode($nome [0]),
                'orgao' => utf8_encode($orgao),
                'orgao_vinculado' => utf8_encode($orgao_vinculado),
                'qualificacao' => !empty($qualificacao [0]) ? utf8_encode($qualificacao [0]) : null,
                'atende' => !empty($atende [0]) ? utf8_encode($atende [0]) : null,
                'descricao' => utf8_encode($descricao),
                'observacao' => utf8_encode($observacao),
                'estados' => !empty($estados [0]) ? utf8_encode($estados [0]) : null,
                'link' => $link,
                'data_inicio' => isset($data_inicio [0]) ? implode("-", array_reverse(explode("/", $data_inicio [0]))) : null,
                'data_fim' => isset($data_fim [0]) ? implode("-", array_reverse(explode("/", $data_fim [0]))) : null,
                'data_inicio_benef' => isset($data_inicio_benef [0]) ? implode("-", array_reverse(explode("/", $data_inicio_benef [0]))) : null,
                'data_fim_benef' => isset($data_fim_benef [0]) ? implode("-", array_reverse(explode("/", $data_fim_benef [0]))) : null,
                'data_inicio_parlam' => isset($data_inicio_parlarm [0]) ? implode("-", array_reverse(explode("/", $data_inicio_parlarm [0]))) : null,
                'data_fim_parlam' => isset($data_fim_parlarm [0]) ? implode("-", array_reverse(explode("/", $data_fim_parlarm [0]))) : null,
                'data_disp' => isset($data_disp [0]) ? implode("-", array_reverse(explode("/", $data_disp [0]))) : null,
                'data_renov_disp' => isset($data_renov_disp [0]) ? implode("-", array_reverse(explode("/", $data_renov_disp [0]))) : null,
                'ano' => !empty($data_disp [0]) ? substr(utf8_encode($data_disp [0]), - 4) : $this->input->get('ano', TRUE),
                'tem_chamamento' => isset($chamamento) ? $chamamento : 0,
                'objeto' => !empty($objeto) ? utf8_encode($objeto) : null,
                'anexos' => isset($anexos) ? $anexos : 0,
                'edital' => isset($editais) ? $editais : 0,
                'regra_contrapartida' => !empty($contra_partida) ? utf8_encode($contra_partida) : null,
                'excluido' => 0
            );

            try {
                $this->get_programas_model->insert_or_update($dados_programa);
            } catch (Exception $ex) {
                $this->log_model->log_erro("get_programas/update_programa", "296", $ex->getMessage());
            }

            try {
                $this->get_programas_model->insert_anexos($insert_anexo);
            } catch (Exception $ex) {
                $this->log_model->log_erro("get_programas/update_programa", "296", $ex->getMessage());
            }

            try {
                $this->get_programas_model->insert_editais($insert_edital);
            } catch (Exception $ex) {
                $this->log_model->log_erro("get_programas/update_programa", "296", $ex->getMessage());
            }
            //var_dump(">>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>INICIO BENEFICIARIOS>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>");

            $pagina = "https://www.convenios.gov.br/siconv/ConsultarPrograma/ResultadoDaConsultaDeProgramaDeConvenioDetalhar.do?id=" . $id_programa;
            $documento = utf8_decode($this->autentica_siconv->new_obter_pagina($pagina, $this->login, $this->cookie_file_path));
            $documento = $this->removeSpaceSurplus($documento);

            echo $documento;
            $dados_beneficiario = array();

            $beneficiariosEspecifico = $this->getTextBetweenTags($documento, "<div id=\"cnpjsBeneficiarioEspecifico\" class=\"table\">", "<\/div><div id=\"tableFooter\">");
            if (!empty($beneficiariosEspecifico)) {
                $pagBeneficiario = $this->getTextBetweenTags($beneficiariosEspecifico [0], "<span class=\"pagelinks\">", "<\/span>");
                $paginasBeneficiario = explode("de", $pagBeneficiario [0]);
                $paginasBeneficiario = explode("(", $paginasBeneficiario [1]);
                $numPagBeneficiario = explode("item", $paginasBeneficiario [1]);
                $paginasBeneficiario = trim($paginasBeneficiario [0]);
                $numPagBeneficiario = trim($numPagBeneficiario [0]);

                if ($paginasBeneficiario > 1) {
                    for ($n = 1; $n <= $paginasBeneficiario; $n ++) {
                        $cnpj = $this->getTextBetweenTags($beneficiariosEspecifico [0], "<div class=\"cnpjBeneficiario\">", "<\/div>");
                        $municipio = $this->getTextBetweenTags($beneficiariosEspecifico [0], "<div class=\"nome\">", "<\/div>");
                        $valorRepasse = $this->getTextBetweenTags($beneficiariosEspecifico [0], "<div class=\"valorRepassePropostaFormatado\">", "<\/div>");

                        foreach ($cnpj as $i => $c) {
                            $dados_beneficiario [] = array(
                                'codigo_programa' => utf8_encode($codigo [0]),
                                'cnpj' => utf8_encode($c),
                                'nome' => utf8_encode($municipio [$i]),
                                'valor' => utf8_encode($valorRepasse [$i]),
                                'data_inicio_benef' => isset($data_inicio_benef [0]) ? implode("-", array_reverse(explode("/", $data_inicio_benef [0]))) : null,
                                'data_fim_benef' => isset($data_fim_benef [0]) ? implode("-", array_reverse(explode("/", $data_fim_benef [0]))) : null,
                                'data_inicio_parlam' => null,
                                'data_fim_parlam' => null,
                                'excluido' => 0
                            );
                        }

                        $pagina = "https://www.convenios.gov.br/siconv/DetalharPrograma/DetalharPrograma.do?id={$id}&d-16544-t=cnpjsBeneficiarioEspecifico&d-16544-p=" . ($n + 1) . "&d-16544-g=" . ($n + 1);
                        $documentoBeneficiario = utf8_decode($this->autentica_siconv->new_obter_pagina($pagina, $this->login, $this->cookie_file_path));
                        echo $documentoBeneficiario;
                        $documentoBeneficiario = $this->removeSpaceSurplus($documentoBeneficiario);

                        $beneficiariosEspecifico = $this->getTextBetweenTags($documentoBeneficiario, "<div id=\"cnpjsBeneficiarioEspecifico\" class=\"table\">", "<\/div><div id=\"tableFooter\">");
                    }
                } else {
                    $cnpj = $this->getTextBetweenTags($beneficiariosEspecifico [0], "<div class=\"cnpjBeneficiario\">", "<\/div>");
                    $municipio = $this->getTextBetweenTags($beneficiariosEspecifico [0], "<div class=\"nome\">", "<\/div>");
                    $valorRepasse = $this->getTextBetweenTags($beneficiariosEspecifico [0], "<div class=\"valorRepassePropostaFormatado\">", "<\/div>");

                    foreach ($cnpj as $i => $c) {
                        $dados_beneficiario [] = array(
                            'codigo_programa' => utf8_encode($codigo [0]),
                            'cnpj' => utf8_encode($c),
                            'nome' => utf8_encode($municipio [$i]),
                            'valor' => utf8_encode($valorRepasse [$i]),
                            'data_inicio_benef' => isset($data_inicio_benef [0]) ? implode("-", array_reverse(explode("/", $data_inicio_benef [0]))) : null,
                            'data_fim_benef' => isset($data_fim_benef [0]) ? implode("-", array_reverse(explode("/", $data_fim_benef [0]))) : null,
                            'data_inicio_parlam' => null,
                            'data_fim_parlam' => null,
                            'excluido' => 0
                        );
                    }
                }
            }

            try {
                $this->get_programas_model->insert_or_update_benef($dados_beneficiario);
            } catch (Exception $ex) {
                $this->log_model->log_erro("get_programas/update_programa", "296", $ex->getMessage());
            }

            //var_dump(">>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>INICIO PARLAMENTARES>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>");

            $pagina = "https://www.convenios.gov.br/siconv/ConsultarPrograma/ResultadoDaConsultaDeProgramaDeConvenioDetalhar.do?id=" . $id_programa;
            $documento = utf8_decode($this->autentica_siconv->new_obter_pagina($pagina, $this->login, $this->cookie_file_path));
            $documento = $this->removeSpaceSurplus($documento);

            $dados_beneficiario = array();

            $beneficiariosParlamentar = $this->getTextBetweenTags($documento, "<div id=\"cnpjsEmendaParlamentar\" class=\"table\">", "<\/div><div id=\"tableFooter\">");
            if (!empty($beneficiariosParlamentar)) {
                $pagParlamentar = $this->getTextBetweenTags($beneficiariosParlamentar [0], "<span class=\"pagelinks\">", "<\/span>");
                $paginasParlamentar = explode("de", $pagParlamentar [0]);
                $paginasParlamentar = explode("(", $paginasParlamentar [1]);
                $numPagParlamentar = explode("item", $paginasParlamentar [1]);
                $paginasParlamentar = trim($paginasParlamentar [0]);
                $numPagParlamentar = trim($numPagParlamentar [0]);

                if ($paginasParlamentar > 1) {
                    for ($m = 1; $m <= $paginasParlamentar; $m ++) {
                        $emenda = $this->getTextBetweenTags($beneficiariosParlamentar [0], "<div class=\"numeroEmenda\">", "<\/div>");
                        $parlamentar = $this->getTextBetweenTags($beneficiariosParlamentar [0], "<div class=\"nomeParlamentar\">", "<\/div>");
                        $cnpj = $this->getTextBetweenTags($beneficiariosParlamentar [0], "<div class=\"cnpj\">", "<\/div>");
                        $municipio = $this->getTextBetweenTags($beneficiariosParlamentar [0], "<div class=\"nome\">", "<\/div>");
                        $valorRepasse = $this->getTextBetweenTags($beneficiariosParlamentar [0], "<div class=\"valorRepassePropostaFormatado\">", "<\/div>");

                        foreach ($emenda as $i => $e) {
                            $dados_beneficiario [] = array(
                                'codigo_programa' => utf8_encode($codigo [0]),
                                'cnpj' => utf8_encode($cnpj [$i]),
                                'nome' => utf8_encode($municipio [$i]),
                                'valor' => utf8_encode($valorRepasse [$i]),
                                'emenda' => utf8_encode($e),
                                'parlamentar' => utf8_encode($parlamentar [$i]),
                                'data_inicio_benef' => null,
                                'data_fim_benef' => null,
                                'data_inicio_parlam' => isset($data_inicio_parlarm [0]) ? implode("-", array_reverse(explode("/", $data_inicio_parlarm [0]))) : null,
                                'data_fim_parlam' => isset($data_fim_parlarm [0]) ? implode("-", array_reverse(explode("/", $data_fim_parlarm [0]))) : null,
                                'excluido' => 0
                            );
                        }

                        $pagina = "https://www.convenios.gov.br/siconv/DetalharPrograma/DetalharPrograma.do?id={$id}&d-16544-t=cnpjsEmendaParlamentar&d-16544-p=" . ($m + 1) . "&d-16544-g=" . ($m + 1);
                        $documentoParlamentar = utf8_decode($this->autentica_siconv->new_obter_pagina($pagina, $this->login, $this->cookie_file_path));
                        echo $documentoParlamentar;
                        $documentoParlamentar = $this->removeSpaceSurplus($documentoParlamentar);

                        $beneficiariosParlamentar = $this->getTextBetweenTags($documentoParlamentar, "<div id=\"cnpjsEmendaParlamentar\" class=\"table\">", "<\/div><div id=\"tableFooter\">");
                    }
                } else {
                    $emenda = $this->getTextBetweenTags($beneficiariosParlamentar [0], "<div class=\"numeroEmenda\">", "<\/div>");
                    $parlamentar = $this->getTextBetweenTags($beneficiariosParlamentar [0], "<div class=\"nomeParlamentar\">", "<\/div>");
                    $cnpj = $this->getTextBetweenTags($beneficiariosParlamentar [0], "<div class=\"cnpj\">", "<\/div>");
                    $municipio = $this->getTextBetweenTags($beneficiariosParlamentar [0], "<div class=\"nome\">", "<\/div>");
                    $valorRepasse = $this->getTextBetweenTags($beneficiariosParlamentar [0], "<div class=\"valorRepassePropostaFormatado\">", "<\/div>");

                    foreach ($emenda as $i => $e) {
                        $dados_beneficiario [] = array(
                            'codigo_programa' => utf8_encode($codigo [0]),
                            'cnpj' => utf8_encode($cnpj [$i]),
                            'nome' => utf8_encode($municipio [$i]),
                            'valor' => utf8_encode($valorRepasse [$i]),
                            'emenda' => utf8_encode($e),
                            'parlamentar' => utf8_encode($parlamentar [$i]),
                            'data_inicio_benef' => null,
                            'data_fim_benef' => null,
                            'data_inicio_parlam' => isset($data_inicio_parlarm [0]) ? implode("-", array_reverse(explode("/", $data_inicio_parlarm [0]))) : null,
                            'data_fim_parlam' => isset($data_fim_parlarm [0]) ? implode("-", array_reverse(explode("/", $data_fim_parlarm [0]))) : null,
                            'excluido' => 0
                        );
                    }
                }
            }

            try {
                $this->get_programas_model->insert_or_update_benef($dados_beneficiario, true);
            } catch (Exception $ex) {
                $this->log_model->log_erro("get_programas/update_programa", "296", $ex->getMessage());
            }
        } catch (Exception $e) {
            $this->log_model->log_erro("get_programas/update_programas_siconv", "174", $ex->getMessage());
        }
    }

    public function get_programas_siconv() {
        ini_set('max_execution_time', 0);
        ini_set("memory_limit", "-1");

        $this->load->model('usuariomodel');
        $this->load->model('get_programas_model');
        $this->load->model("log_model");

        //Limpando a lista de excluidos
        if ($this->input->get('offset', TRUE) == 1) {
            $this->get_programas_model->limpar_excluidos($this->input->get('ano', TRUE));
        }

        $this->db->flush_cache();
        $listaIDS = $this->buscar_ids_apto($this->input->get('ano', TRUE), $this->input->get('offset', TRUE));

        if (empty($listaIDS)) {
            $this->log_model->log_erro("get_programas/get_programas", "Sem id para atualizacao", "Offset:" + $this->input->get('offset', TRUE));
            die();
        }

        $this->load->model('senha_eliumar');
        $this->login = null;
        $this->cookie_file_path = tempnam("/tmp", "CURLCOOKIE" . rand());
        $eliumar = $this->senha_eliumar->get_password();
        $this->autentica_siconv->new_init_siconv_do_login($eliumar->cpf, $eliumar->senha, $this->login, $this->cookie_file_path);

        foreach ($listaIDS as $numId) {
            try {
                $id = $numId->num_id;

                $pagina = "https://www.convenios.gov.br/siconv/ConsultarPrograma/ResultadoDaConsultaDeProgramaDeConvenioDetalhar.do?id=" . $id;
                $documento = utf8_decode($this->autentica_siconv->new_obter_pagina($pagina, $this->login, $this->cookie_file_path));
                $documento = $this->removeSpaceSurplus($documento);

                $documento2 = $documento;
                $dados_programa = array();

                $codigo = $this->getTextBetweenTags($documento, "tr-alterarProgramaCodigo\" >", " <\/tr>");
                $codigo = $this->getTextBetweenTags($codigo [0], "<td class=\"field\"> ", " <\/td>");

                $nome = $this->getTextBetweenTags($documento, "tr-alterarProgramaNomeDoPrograma\" >", " <\/tr>");
                $nome = $this->getTextBetweenTags($nome [0], "<td class=\"field\"> ", " <\/td>");

                $orgao = $this->getTextBetweenTags($documento, "tr-alterarProgramaOrgao\" >", " <\/tr>");
                $orgao = $this->getTextBetweenTags($orgao [0], "<td class=\"field\"> ", " <\/td>");
                if (!empty($orgao [0])) {
                    $orgao = strtok($orgao [0], "-");
                    $orgao = trim(strtok("-"));
                }

                $orgao_vinculado = $this->getTextBetweenTags($documento, "tr-alterarProgramaOrgaoSubordinado\" >", " <\/tr>");
                if (!empty($orgao_vinculado [0])) {
                    $orgao_vinculado = $this->getTextBetweenTags($orgao_vinculado [0], "<td class=\"field\"> ", " <\/td>");
                    $orgao_vinculado = strtok($orgao_vinculado [0], "-");
                    $orgao_vinculado = trim(strtok("-"));
                } else {
                    $orgao_vinculado = $this->getTextBetweenTags($documento, "tr-alterarProgramaOrgaoExecutor\" >", " <\/tr>");
                    if (!empty($orgao_vinculado [0])) {
                        $orgao_vinculado = $this->getTextBetweenTags($orgao_vinculado [0], "<td class=\"field\"> ", " <\/td>");
                        $orgao_vinculado = strtok($orgao_vinculado [0], "-");
                        $orgao_vinculado = trim(strtok("-"));
                    } else
                        $orgao_vinculado = $orgao;
                }

                $qualificacao = $this->getTextBetweenTags($documento, "tr-alterarProgramaQualificacaoProponente\" >", " <\/tr>");
                $qualificacao = $this->getTextBetweenTags($qualificacao [0], "<td class=\"field\"> ", " <\/td>");

                $atende = $this->getTextBetweenTags($documento, "tr-alterarProgramaProgramaAtendea\" >", " <\/tr>");
                $atende = $this->getTextBetweenTags($atende [0], "<td class=\"field\"> ", " <\/td>");

                $textArea = $this->getTextBetweenTags($documento, "<td class=fieldCaixa colspan=2> ", " <\/td>");
                $descricao = $textArea [0];
                $observacao = $textArea [1];

                $estados = $this->getTextBetweenTags($documento, "tr-alterarProgramaEstadosHabilitados\" >", " <\/tr>");
                $estados = $this->getTextBetweenTags($estados [0], "<td class=\"field\"> ", " <\/td>");

                $data_inicio = $this->getTextBetweenTags($documento, "tr-alterarProgramaDataInicioVigencia\" >", " <\/tr>");
                if (!empty($data_inicio))
                    $data_inicio = $this->getTextBetweenTags($documento, "\"dataInicioVigencia\" value=\"", "\" onmouseup=");

                $data_fim = $this->getTextBetweenTags($documento, "tr-alterarProgramaDataFimdeVigencia\" > ", "<\/tr>");
                if (!empty($data_fim))
                    $data_fim = $this->getTextBetweenTags($data_fim [0], "\"dataFimdeVigencia\" value=\"", "\" onmouseup=");

                $data_inicio_benef = $this->getTextBetweenTags($documento, "tr-alterarProgramaDataInicioBeneficiarioEspecifico\" >", " <\/tr>");
                if (!empty($data_inicio_benef))
                    $data_inicio_benef = $this->getTextBetweenTags($data_inicio_benef [0], "\"dataInicioBeneficiarioEspecifico\" value=\"", "\" onmouseup=");

                $data_fim_benef = $this->getTextBetweenTags($documento, "tr-alterarProgramaDataFimBeneficiarioEspecifico\" > ", "<\/tr>");
                if (!empty($data_fim_benef))
                    $data_fim_benef = $this->getTextBetweenTags($data_fim_benef [0], "\"dataFimBeneficiarioEspecifico\" value=\"", "\" onmouseup=");

                $data_inicio_parlarm = $this->getTextBetweenTags($documento, "tr-alterarProgramaDataInicioEmendaParlamentar\" >", " <\/tr>");
                if (!empty($data_inicio_parlarm))
                    $data_inicio_parlarm = $this->getTextBetweenTags($data_inicio_parlarm [0], "name=\"dataInicioEmendaParlamentar\" value=\"", "\" onmouseup=");

                $data_fim_parlarm = $this->getTextBetweenTags($documento, "tr-alterarProgramaDataFimEmendaParlamentar\" >", "<\/tr>");
                if (!empty($data_fim_parlarm))
                    $data_fim_parlarm = $this->getTextBetweenTags($data_fim_parlarm [0], "name=\"dataFimEmendaParlamentar\" value=\"", "\" onmouseup=");

                $data_disp = $this->getTextBetweenTags($documento, "tr-alterarProgramaDataDeDisponibilizacao\">", "<\/tr>");
                if (!empty($data_disp))
                    $data_disp = $this->getTextBetweenTags($data_disp [0], "name=\"dataDeDisponibilizacao\" value=\"", "\" onmouseup=");

                $data_renov_disp = $this->getTextBetweenTags($documento, "tr-alterarProgramaDataUltimaRenovacaoDisponibilizacao\" >", "<\/tr>");
                if (!empty($data_renov_disp))
                    $data_renov_disp = $this->getTextBetweenTags($data_renov_disp [0], "<td class=\"field\"> ", " <\/td>");

                // Novos dados adicionados (chamamento, objeto, anexos)
                // Chamamento
                $chamamento = $this->getTextBetweenTags($documento, '<tr class="chamamentoPublico" id="tr-alterarProgramaChamamentoPublico" >', '<\/tr>');
                if (!empty($chamamento)) {
                    $chamamento = $this->getTextBetweenTags($chamamento [0], '<td class="field">', '<\/td>');
                    if (!empty($chamamento)) {
                        if (trim($chamamento [0]) == "Sim") {
                            $chamamento = 1;
                        } else {
                            $chamamento = 0;
                        }
                    }
                }
                $chamamento = 0;

                // Objeto
                $pagina = "https://www.convenios.gov.br/siconv/programa/EditarObjetos/EditarObjetos.do?id=" . $id;
                $documento2 = utf8_decode($this->autentica_siconv->new_obter_pagina($pagina, $this->login, $this->cookie_file_path));
                $documento2 = $this->removeSpaceSurplus($documento2);

                $objeto = $this->getTextBetweenTags($documento2, '<div id="listaObjetos" class="table"> <table id="row"> <thead> <tr> <th class="nome">Nome<\/th> <th><\/th><\/tr><\/thead> <tbody id="tbodyrow"> <tr class="odd"> <td> <div class="nome">', '<\/div> <\/td> <td> <\/td><\/tr><\/tbody><\/table><\/div>');
                if (!empty($objeto)) {
                    $objeto = trim($objeto [0]);
                }

                // Anexos
                $anexos = 0;
                $pagina = "https://www.convenios.gov.br/siconv/programa/UploadArquivoPrograma/UploadArquivoPrograma.do?id=" . $id;
                $documento2 = utf8_decode($this->autentica_siconv->new_obter_pagina($pagina, $this->login, $this->cookie_file_path));
                $documento2 = $this->removeSpaceSurplus($documento2);

                if (strpos($documento2, "Nenhum registro foi encontrado") === false) {
                    try {
                        $insert_anexo['id_programa'] = $id;
                        $insert_anexo['nome_arquivo'] = $this->getTextBetweenTags($documento2, '<div class="nomeArquivo">', '<\/div>');
                        $insert_anexo['data_anexo'] = $this->getTextBetweenTags($documento2, '<div class="dataUpload">', '<\/div>');
                        $insert_anexo['descricao'] = $this->getTextBetweenTags($documento2, '<div class="descricao">', '<\/div>');
                        $links = $this->getTextBetweenTags($documento2, '<div class="descricao">', '<\/table>');
                        $insert_anexo['link'] = $this->getTextBetweenTags($links[0], '<a href="javascript:document.location=\'', '\'');
                        if ($insert_anexo['id_programa'] != NULL && is_string($insert_anexo['id_programa']) == TRUE && $insert_anexo['nome_arquivo'] != NULL && is_string($insert_anexo['nome_arquivo'][0]) == TRUE && $insert_anexo['data_anexo'] != NULL && is_string($insert_anexo['data_anexo'][0]) == TRUE && $insert_anexo['descricao'] != NULL && is_string($insert_anexo['descricao'][0]) == TRUE && $insert_anexo['link'] != NULL && is_string($insert_anexo['link'][0]) == TRUE) {
                            $anexos = 1;
                        }
                    } catch (Exception $ex) {
                        $anexos = 0;
                        $this->log_model->log_erro("get_programas/get_programas", "296", $ex->getMessage());
                    }
                }

                //Editais
                $editais = 0;
                $link = "https://www.convenios.gov.br/siconv/ConsultarPrograma/ResultadoDaConsultaDeProgramaDeConvenioDetalhar.do?id=" . $id;
                $documento2 = utf8_decode($this->autentica_siconv->new_obter_pagina($link, $this->login, $this->cookie_file_path));
                $documento2 = $this->removeSpaceSurplus($documento2);

                if (strpos($documento2, "Nenhum registro foi encontrado") === false) {
                    try {
                        $insert_edital['id_programa'] = $id;
                        $insert_edital['nome_arquivo'] = $this->getTextBetweenTags($documento2, '<div class="nome">', '<\/div>');
                        $unset_array = array();
                        foreach ($insert_edital['nome_arquivo'] as $key => $value) {
                            if (strpos($insert_edital['nome_arquivo'][$key], '.') === false) {
                                array_push($unset_array, $key);
                            }
                        }
                        if (!empty($unset_array)) {
                            foreach ($unset_array as $v) {
                                unset($insert_edital['nome_arquivo'][$v]);
                            }
                        }
                        $links = $this->getTextBetweenTags($documento2, '<div class="nome">', 'class="buttonLink">');
                        $insert_edital['link'] = $this->getTextBetweenTags($links[0], '<a href="javascript:document.location=\'', '\'');
                        if ($insert_edital['id_programa'] != NULL && is_string($insert_edital['id_programa']) == TRUE && $insert_edital['nome_arquivo'] != NULL && is_string($insert_edital['nome_arquivo'][0]) == TRUE && $insert_edital['link'] != NULL && is_string($insert_edital['link'][0]) == TRUE) {
                            $editais = 1;
                        }
                    } catch (Exception $ex) {
                        $editais = 0;
                        $this->log_model->log_erro("get_programas/get_programas", "296", $ex->getMessage());
                    }
                }

                // Regra de contrapartida
                $pagina_consulta = "https://www.convenios.gov.br/siconv/programa/ListarRegras/ListarRegras.do?id=" . $id;
                $documento2 = utf8_decode($this->autentica_siconv->new_obter_pagina($pagina_consulta, $this->login, $this->cookie_file_path));
                $documento2 = $this->removeSpaceSurplus($documento2);

                $contra_partida = $this->getTextBetweenTags($documento2, '<div id="regras" class="table">', '<\/div><div id="tableFooter">');
                if (!empty($contra_partida)) {
                    $contra_partida = trim($contra_partida [0]);
                }

                $link = "https://www.convenios.gov.br/siconv/ConsultarPrograma/ResultadoDaConsultaDeProgramaDeConvenioDetalhar.do?id=" . $id;

                $dados_programa = array(
                    'codigo' => utf8_encode($codigo [0]),
                    'nome' => utf8_encode($nome [0]),
                    'orgao' => utf8_encode($orgao),
                    'orgao_vinculado' => utf8_encode($orgao_vinculado),
                    'qualificacao' => !empty($qualificacao [0]) ? utf8_encode($qualificacao [0]) : null,
                    'atende' => !empty($atende [0]) ? utf8_encode($atende [0]) : null,
                    'descricao' => utf8_encode($descricao),
                    'observacao' => utf8_encode($observacao),
                    'estados' => !empty($estados [0]) ? utf8_encode($estados [0]) : null,
                    'link' => $link,
                    'data_inicio' => isset($data_inicio [0]) ? implode("-", array_reverse(explode("/", $data_inicio [0]))) : null,
                    'data_fim' => isset($data_fim [0]) ? implode("-", array_reverse(explode("/", $data_fim [0]))) : null,
                    'data_inicio_benef' => isset($data_inicio_benef [0]) ? implode("-", array_reverse(explode("/", $data_inicio_benef [0]))) : null,
                    'data_fim_benef' => isset($data_fim_benef [0]) ? implode("-", array_reverse(explode("/", $data_fim_benef [0]))) : null,
                    'data_inicio_parlam' => isset($data_inicio_parlarm [0]) ? implode("-", array_reverse(explode("/", $data_inicio_parlarm [0]))) : null,
                    'data_fim_parlam' => isset($data_fim_parlarm [0]) ? implode("-", array_reverse(explode("/", $data_fim_parlarm [0]))) : null,
                    'data_disp' => isset($data_disp [0]) ? implode("-", array_reverse(explode("/", $data_disp [0]))) : null,
                    'data_renov_disp' => isset($data_renov_disp [0]) ? implode("-", array_reverse(explode("/", $data_renov_disp [0]))) : null,
                    'ano' => !empty($data_disp [0]) ? substr(utf8_encode($data_disp [0]), - 4) : $this->input->get('ano', TRUE),
                    'tem_chamamento' => isset($chamamento) ? $chamamento : 0,
                    'objeto' => !empty($objeto) ? utf8_encode($objeto) : null,
                    'anexos' => isset($anexos) ? $anexos : 0,
                    'regra_contrapartida' => !empty($contra_partida) ? utf8_encode($contra_partida) : null,
                    'excluido' => 0
                );

                try {
                    $this->get_programas_model->insert_or_update($dados_programa);
                    $this->log_model->log_atualizacao_programa($id, $dados_programa['codigo'], 1);
                } catch (Exception $ex) {
                    $this->log_model->log_erro("get_programas/get_programas", "296", $ex->getMessage());
                }

                try {
                    $this->get_programas_model->insert_anexos($insert_anexo);
                } catch (Exception $ex) {
                    $this->log_model->log_erro("get_programas/get_programas", "296", $ex->getMessage());
                }

                try {
                    $this->get_programas_model->insert_editais($insert_edital);
                } catch (Exception $ex) {
                    $this->log_model->log_erro("get_programas/get_programas", "296", $ex->getMessage());
                }
                //var_dump(">>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>INICIO BENEFICIARIOS>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>");

                $pagina = "https://www.convenios.gov.br/siconv/ConsultarPrograma/ResultadoDaConsultaDeProgramaDeConvenioDetalhar.do?id=" . $id;
                $documento = utf8_decode($this->autentica_siconv->new_obter_pagina($pagina, $this->login, $this->cookie_file_path));
                $documento = $this->removeSpaceSurplus($documento);

                $dados_beneficiario = array();

                $beneficiariosEspecifico = $this->getTextBetweenTags($documento, "<div id=\"cnpjsBeneficiarioEspecifico\" class=\"table\">", "<\/div><div id=\"tableFooter\">");
                if (!empty($beneficiariosEspecifico)) {
                    $pagBeneficiario = $this->getTextBetweenTags($beneficiariosEspecifico [0], "<span class=\"pagelinks\">", "<\/span>");
                    $paginasBeneficiario = explode("de", $pagBeneficiario [0]);
                    $paginasBeneficiario = explode("(", $paginasBeneficiario [1]);
                    $numPagBeneficiario = explode("item", $paginasBeneficiario [1]);
                    $paginasBeneficiario = trim($paginasBeneficiario [0]);
                    $numPagBeneficiario = trim($numPagBeneficiario [0]);

                    if ($paginasBeneficiario > 1) {
                        for ($n = 1; $n <= $paginasBeneficiario; $n ++) {
                            $cnpj = $this->getTextBetweenTags($beneficiariosEspecifico [0], "<div class=\"cnpjBeneficiario\">", "<\/div>");
                            $municipio = $this->getTextBetweenTags($beneficiariosEspecifico [0], "<div class=\"nome\">", "<\/div>");
                            $valorRepasse = $this->getTextBetweenTags($beneficiariosEspecifico [0], "<div class=\"valorRepassePropostaFormatado\">", "<\/div>");

                            foreach ($cnpj as $i => $c) {
                                $dados_beneficiario [] = array(
                                    'codigo_programa' => utf8_encode($codigo [0]),
                                    'cnpj' => utf8_encode($c),
                                    'nome' => utf8_encode($municipio [$i]),
                                    'valor' => utf8_encode($valorRepasse [$i]),
                                    'data_inicio_benef' => isset($data_inicio_benef [0]) ? implode("-", array_reverse(explode("/", $data_inicio_benef [0]))) : null,
                                    'data_fim_benef' => isset($data_fim_benef [0]) ? implode("-", array_reverse(explode("/", $data_fim_benef [0]))) : null,
                                    'data_inicio_parlam' => null,
                                    'data_fim_parlam' => null,
                                    'excluido' => 0
                                );
                            }

                            $pagina = "https://www.convenios.gov.br/siconv/DetalharPrograma/DetalharPrograma.do?id={$id}&d-16544-t=cnpjsBeneficiarioEspecifico&d-16544-p=" . ($n + 1) . "&d-16544-g=" . ($n + 1);
                            $documentoBeneficiario = utf8_decode($this->autentica_siconv->new_obter_pagina($pagina, $this->login, $this->cookie_file_path));
                            $documentoBeneficiario = $this->removeSpaceSurplus($documentoBeneficiario);
                            $beneficiariosEspecifico = $this->getTextBetweenTags($documentoBeneficiario, "<div id=\"cnpjsBeneficiarioEspecifico\" class=\"table\">", "<\/div><div id=\"tableFooter\">");
                        }
                    } else {
                        $cnpj = $this->getTextBetweenTags($beneficiariosEspecifico [0], "<div class=\"cnpjBeneficiario\">", "<\/div>");
                        $municipio = $this->getTextBetweenTags($beneficiariosEspecifico [0], "<div class=\"nome\">", "<\/div>");
                        $valorRepasse = $this->getTextBetweenTags($beneficiariosEspecifico [0], "<div class=\"valorRepassePropostaFormatado\">", "<\/div>");

                        foreach ($cnpj as $i => $c) {
                            $dados_beneficiario [] = array(
                                'codigo_programa' => utf8_encode($codigo [0]),
                                'cnpj' => utf8_encode($c),
                                'nome' => utf8_encode($municipio [$i]),
                                'valor' => utf8_encode($valorRepasse [$i]),
                                'data_inicio_benef' => isset($data_inicio_benef [0]) ? implode("-", array_reverse(explode("/", $data_inicio_benef [0]))) : null,
                                'data_fim_benef' => isset($data_fim_benef [0]) ? implode("-", array_reverse(explode("/", $data_fim_benef [0]))) : null,
                                'data_inicio_parlam' => null,
                                'data_fim_parlam' => null,
                                'excluido' => 0
                            );
                        }
                    }
                }

                try {
                    $this->get_programas_model->insert_or_update_benef($dados_beneficiario);
                    $this->log_model->log_atualizacao_beneficiario($id, $dados_beneficiario, 1);
                } catch (Exception $ex) {
                    $this->log_model->log_erro("get_programas/get_programas", "296", $ex->getMessage());
                }

                //UPDATE EXCLUSAO EMENDAS VERIFICAR
                /*
                 * Atualiza status excluído das emendas especifico
                 */
//                $this->load->model('emenda_programa_proposta_model');
//                $emendas_cadastradas = $this->emenda_programa_proposta_model->get_emendas_by_programa(utf8_encode($codigo [0]), 'ESPECIFICO');
//                //var_dump($emendas_cadastradas);die;
//                foreach ($emendas_cadastradas as $emenda) {
//                    $existe = FALSE;
//                    foreach ($dados_beneficiario as $dados) {
//                        if ($dados == $emenda) {
//                            $existe = TRUE;
//                        }
//                    }
//                    if ($existe == FALSE) {
//                        $this->emenda_programa_proposta_model->update_excluido($emenda);
//                    }
//                }
                //var_dump(">>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>FIM BENEFICIARIOS>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>");
                //var_dump(">>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>INICIO PARLAMENTARES>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>");

                $pagina = "https://www.convenios.gov.br/siconv/ConsultarPrograma/ResultadoDaConsultaDeProgramaDeConvenioDetalhar.do?id=" . $id;
                $documento = utf8_decode($this->autentica_siconv->new_obter_pagina($pagina, $this->login, $this->cookie_file_path));
                $documento = $this->removeSpaceSurplus($documento);

                $dados_beneficiario = array();

                $beneficiariosParlamentar = $this->getTextBetweenTags($documento, "<div id=\"cnpjsEmendaParlamentar\" class=\"table\">", "<\/div><div id=\"tableFooter\">");
                if (!empty($beneficiariosParlamentar)) {
                    $pagParlamentar = $this->getTextBetweenTags($beneficiariosParlamentar [0], "<span class=\"pagelinks\">", "<\/span>");
                    $paginasParlamentar = explode("de", $pagParlamentar [0]);
                    $paginasParlamentar = explode("(", $paginasParlamentar [1]);
                    $numPagParlamentar = explode("item", $paginasParlamentar [1]);
                    $paginasParlamentar = trim($paginasParlamentar [0]);
                    $numPagParlamentar = trim($numPagParlamentar [0]);

                    if ($paginasParlamentar > 1) {
                        for ($m = 1; $m <= $paginasParlamentar; $m ++) {
                            $emenda = $this->getTextBetweenTags($beneficiariosParlamentar [0], "<div class=\"numeroEmenda\">", "<\/div>");
                            $parlamentar = $this->getTextBetweenTags($beneficiariosParlamentar [0], "<div class=\"nomeParlamentar\">", "<\/div>");
                            $cnpj = $this->getTextBetweenTags($beneficiariosParlamentar [0], "<div class=\"cnpj\">", "<\/div>");
                            $municipio = $this->getTextBetweenTags($beneficiariosParlamentar [0], "<div class=\"nome\">", "<\/div>");
                            $valorRepasse = $this->getTextBetweenTags($beneficiariosParlamentar [0], "<div class=\"valorRepassePropostaFormatado\">", "<\/div>");

                            foreach ($emenda as $i => $e) {
                                $dados_beneficiario [] = array(
                                    'codigo_programa' => utf8_encode($codigo [0]),
                                    'cnpj' => utf8_encode($cnpj [$i]),
                                    'nome' => utf8_encode($municipio [$i]),
                                    'valor' => utf8_encode($valorRepasse [$i]),
                                    'emenda' => utf8_encode($e),
                                    'parlamentar' => utf8_encode($parlamentar [$i]),
                                    'data_inicio_benef' => null,
                                    'data_fim_benef' => null,
                                    'data_inicio_parlam' => isset($data_inicio_parlarm [0]) ? implode("-", array_reverse(explode("/", $data_inicio_parlarm [0]))) : null,
                                    'data_fim_parlam' => isset($data_fim_parlarm [0]) ? implode("-", array_reverse(explode("/", $data_fim_parlarm [0]))) : null,
                                    'excluido' => 0
                                );
                            }

                            //var_dump($dados_beneficiario);
                            $pagina = "https://www.convenios.gov.br/siconv/DetalharPrograma/DetalharPrograma.do?id={$id}&d-16544-t=cnpjsEmendaParlamentar&d-16544-p=" . ($m + 1) . "&d-16544-g=" . ($m + 1);
                            $documentoParlamentar = utf8_decode($this->autentica_siconv->new_obter_pagina($pagina, $this->login, $this->cookie_file_path));
                            $documentoParlamentar = $this->removeSpaceSurplus($documentoParlamentar);

                            $beneficiariosParlamentar = $this->getTextBetweenTags($documentoParlamentar, "<div id=\"cnpjsEmendaParlamentar\" class=\"table\">", "<\/div><div id=\"tableFooter\">");
                        }
                    } else {
                        $emenda = $this->getTextBetweenTags($beneficiariosParlamentar [0], "<div class=\"numeroEmenda\">", "<\/div>");
                        $parlamentar = $this->getTextBetweenTags($beneficiariosParlamentar [0], "<div class=\"nomeParlamentar\">", "<\/div>");
                        $cnpj = $this->getTextBetweenTags($beneficiariosParlamentar [0], "<div class=\"cnpj\">", "<\/div>");
                        $municipio = $this->getTextBetweenTags($beneficiariosParlamentar [0], "<div class=\"nome\">", "<\/div>");
                        $valorRepasse = $this->getTextBetweenTags($beneficiariosParlamentar [0], "<div class=\"valorRepassePropostaFormatado\">", "<\/div>");

                        foreach ($emenda as $i => $e) {
                            $dados_beneficiario [] = array(
                                'codigo_programa' => utf8_encode($codigo [0]),
                                'cnpj' => utf8_encode($cnpj [$i]),
                                'nome' => utf8_encode($municipio [$i]),
                                'valor' => utf8_encode($valorRepasse [$i]),
                                'emenda' => utf8_encode($e),
                                'parlamentar' => utf8_encode($parlamentar [$i]),
                                'data_inicio_benef' => null,
                                'data_fim_benef' => null,
                                'data_inicio_parlam' => isset($data_inicio_parlarm [0]) ? implode("-", array_reverse(explode("/", $data_inicio_parlarm [0]))) : null,
                                'data_fim_parlam' => isset($data_fim_parlarm [0]) ? implode("-", array_reverse(explode("/", $data_fim_parlarm [0]))) : null,
                                'excluido' => 0
                            );
                        }
                    }
                }

                try {
                    $this->get_programas_model->insert_or_update_benef($dados_beneficiario, true);
                    $this->log_model->log_atualizacao_beneficiario($id, $dados_beneficiario['codigo_programa'], $dados_beneficiario['cnpj'], 1);
                } catch (Exception $ex) {
                    $this->log_model->log_erro("get_programas/get_programas", "296", $ex->getMessage());
                }
                //UPDATE EMENDAS PARLAMENTARES EXCLUIDAS VERIFICAR

                /*
                 * Atualiza status excluído das emendas parlamentares
                 */
//                $this->load->model('emenda_programa_proposta_model');
//                $emendas_cadastradas = $this->emenda_programa_proposta_model->get_emendas_by_programa(utf8_encode($codigo [0]), 'PARLAMENTAR');
//                //var_dump($emendas_cadastradas);die;
//                foreach ($emendas_cadastradas as $emenda) {
//                    $existe = FALSE;
//                    foreach ($dados_beneficiario as $dados) {
//                        if ($dados == $emenda) {
//                            $existe = TRUE;
//                        }
//                    }
//                    if ($existe == FALSE) {
//                        $this->emenda_programa_proposta_model->update_excluido($emenda);
//                    }
//                }
                //var_dump(">>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>FIM PARLAMENTARES>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>");
            } catch (Exception $e) {
                $this->log_model->log_erro("get_programas/get_programas_siconv", "543", $ex->getMessage());
            }
        }
    }

    public function marcar_programas_excluidos() {
        ini_set('max_execution_time', 0);
        ini_set("memory_limit", "-1");

        try {
            $this->load->model('programa_model');
            $this->load->model('get_programas_model');
            $this->get_ids_programas();
            $listaIDS = $this->lista_ids($this->input->get('ano', TRUE));
            $idsCadastrados = $this->programa_model->get_ids_cadastrados($this->input->get('ano', TRUE));
            $excluidos = array_diff($idsCadastrados, $listaIDS);
            $codigo_excluidos = array();
            foreach ($excluidos as $key => $excluido) {
                $codigo_excluidos[] = $key;
            }
            echo "Excluindo ...";
            var_dump($codigo_excluidos);
            $this->get_programas_model->update_excluido($codigo_excluidos);
        } catch (Exception $ex) {
            $this->load->model("log_model");
            $this->log_model->log_erro("get_programas/marcar_programas_excluidos", "932", $ex->getMessage());
        }

        echo "Finalizado";
    }

    public function returna_dif_aptos_siconv_esicar() {
        ini_set('max_execution_time', 0);
        ini_set("memory_limit", "-1");

        try {
            $this->load->model('programa_model');
            $ano = $this->input->get('ano', TRUE);

            $ids_aptos = $this->programa_model->get_ids_aptos($ano);
            $ids_vigencia_esicar = $this->programa_model->get_ids_cadastrados_aptos($ano);
            $excluidos = array_diff($ids_aptos, $ids_vigencia_esicar);
            echo "Diferentes";
            var_dump($excluidos);
            echo "Finalizado";
        } catch (Exception $ex) {
            $this->load->model("log_model");
            $this->log_model->log_erro("get_programas/returna_dif_aptos_siconv_esicar", "1072", $ex->getMessage());
        }
    }

    public function executa_python_programas() {
        ini_set('max_execution_time', 0);

        $commandLine = 'python /var/www/esicar/python_scripts/request_programas_siconv_final.py';
        $command = escapeshellcmd($commandLine);
        echo $command . "<br>";
        $output = exec($command);
        echo $output . "<br><br>";
    }

    function buscar_ids($ano, $offset = 0) {
        $this->db->select('num_id');

        $this->db->where('ano', $ano);
        $this->db->where('id', $offset);
        //$this->db->limit(1, $offset);
        //$this->db->order_by('id', 'ASC');

        $query = $this->db->get('ids_programa')->result();

        $this->db->flush_cache();

        return $query;
    }

    function buscar_ids_apto($ano, $offset = 0) {
        $this->db->flush_cache();
        $this->db->select('num_id');
        $this->db->where('ano', $ano);
        $this->db->where('id', $offset);
        //$this->db->limit(1, $offset);
        //$this->db->order_by('id', 'ASC');
        $query = $this->db->get('ids_programa_apto')->result();

        return $query;
    }

    function lista_ids($ano) {
        $this->db->select('num_id');

        $this->db->where('ano', $ano);
        $query = $this->db->get('ids_programa')->result();
        $result = array();
        foreach ($query as $id) {
            $result[] = $id->num_id;
        }
        $this->db->flush_cache();

        return $result;
    }

    function lista_ids_apto($ano) {
        $this->db->select('num_id');

        $this->db->where('ano', $ano);
        $query = $this->db->get('ids_programa_apto')->result();
        $result = array();
        foreach ($query as $id) {
            $result[] = $id->num_id;
        }
        $this->db->flush_cache();

        return $result;
    }

    function curl_init_siconv() {
        $url = "https://www.convenios.gov.br/siconv/ForwardAction.do?modulo=Principal&path=/MostraPrincipalConsultarPrograma.do&Usr=guest&Pwd=guest";
        // echo file_get_contents($url); die();
        // $cookie_file_path = "application/views/configuracoes/cookie.txt";
        $cookie_file_path = $this->cookie_file_path;
        $agent = "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.1) Gecko/20061204 Firefox/2.0.0.1";
        $ch = curl_init();
        // extra headers
        $headers [] = "Accept: */*";
        $headers [] = "Connection: Keep-Alive";

        // basic curl options for all requests
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_USERAGENT, $agent);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file_path);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file_path);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);

        // set first URL
        curl_setopt($ch, CURLOPT_URL, $url);

        // execute session to get cookies and required form inputs
        $content = curl_exec($ch);

        curl_close($ch);
        /*
         * if (strstr ( $content, 'Acesso Livre' ) === false) {
         * if ($id == 1) {
         * echo "erro na página interna do siconv, entre em contato com o administrador.";
         * die ();
         * }
         *
         * $this->cookie_file_path = tempnam ( "/tmp", "CURLCOOKIE1" . rand () );
         * return $this->obter_paginaLogin ( 1 );
         * }
         */
        return $content;
    }

    function obter_pagina($url) {
        // $cookie_file_path = "application/views/configuracoes/cookie.txt";
        $cookie_file_path = $this->cookie_file_path;

        $agent = "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.1) Gecko/20061204 Firefox/2.0.0.1";
        $ch = curl_init();
        // extra headers
        $headers [] = "Accept: */*";
        $headers [] = "Connection: Keep-Alive";

        // basic curl options for all requests
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_USERAGENT, $agent);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file_path);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file_path);

        // set first URL
        curl_setopt($ch, CURLOPT_URL, $url);

        // execute session to get cookies and required form inputs
        $content = curl_exec($ch);
        curl_close($ch);

        return $content;
    }

    function obter_pagina_post($url, $fields, $fields_string) {

        // $cookie_file_path = "application/views/configuracoes/cookie.txt";
        $cookie_file_path = $this->cookie_file_path;
        $agent = "Mozilla/5.0 (Windows; U; Windows NT 5.0; en-US; rv:1.4) Gecko/20030624 Netscape/7.1 (ax)";
        $ch = curl_init();
        // extra headers
        $headers [] = "Accept: */*";
        $headers [] = "Connection: Keep-Alive";
        // $headers[] = 'Content-type: multipart/form-data';
        // basic curl options for all requests
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_USERAGENT, $agent);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file_path);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file_path);
        curl_setopt($ch, CURLOPT_POST, count($fields));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);

        // set first URL
        curl_setopt($ch, CURLOPT_URL, $url);

        // execute session to get cookies and required form inputs
        $content = curl_exec($ch);

        curl_close($ch);
        if (strstr($content, '<div class="errors">') !== false) {

            $aux = $this->removeSpaceSurplus($content);
            $erro = $this->getTextBetweenTags($aux, "<div class=\"error\">", "<\/div>");
            $this->alert("Houve um erro de conexão com o siconv. Por favor copie e cole o texto abaixo para adm@physisbrasil.com.br: " . $erro [0]);
            // echo $url;
            die();
        }

        return $content;
    }

    function removeSpaceSurplus($str) {
        return preg_replace("/\s+/", ' ', trim($str));
    }

    function getTextBetweenTags($string, $tag1, $tag2) {
        $pattern = "/$tag1([\w\W]*?)$tag2/";
        preg_match_all($pattern, $string, $matches);
        return $matches [1];
    }

    function alert($text) {
        echo "<script type='text/javascript'>alert('" . utf8_encode($text) . "');</script>";
    }

}
