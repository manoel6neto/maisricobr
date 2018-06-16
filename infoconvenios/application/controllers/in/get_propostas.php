<?php

// Thomas: Não pode verificar autenticação pois roda direto do cron sem nenhuma autenticação. Apenas que façamos via url o processo de autenticar
//include 'application/controllers/BaseController.php';

class get_propostas extends CI_Controller {

    function __construct() {
        parent::__construct();

        $this->cookie_file_path = tempnam("/tmp", "CURLCOOKIE" . rand());
        $this->login = null;
    }

    //Thomas: Carregar as propostas do siconv para a tabela do banco de propostas
    public function get_propostas_siconv($idInicio = null, $idFim = null, $usuario_propnente = null, $senha_proponente = null) {
        ini_set('max_execution_time', 0);
        //Inicializando conexao no siconv
        //$this->curl_init_siconv();
        //$this->autentica_siconv->new_init_siconv_do_login("guest", "guest", $this->login, $this->cookie_file_path);
//        $this->autentica_siconv->new_init_siconv_do_login ("43346880559", "Laisa_M2012", $this->login, $this->cookie_file_path );
//        $this->autentica_siconv->open_page_programas_livre($this->cookie_file_path);

        if ($usuario_propnente == null && $senha_proponente == null) {
            $this->autentica_siconv->new_init_siconv_do_login("43346880559", "Laisa_M2012", $this->login, $this->cookie_file_path);
        } else {
            $this->autentica_siconv->new_init_siconv_do_login($usuario_propnente, $senha_proponente, $this->login, $this->cookie_file_path);
        }

        $this->load->model('banco_proposta_model');
        $this->load->model('programa_banco_proposta_model');
        $this->load->model('emenda_banco_proposta_model');

        //pode definir o offset de inicio e/ou fim. Ou pegar todos os valores do siconv
        if ($this->input->get_post('id_inicial', TRUE) !== false) {
            $id_inicial = $this->input->get_post('id_inicial', TRUE);
        } else if ($idInicio != null) {
            $id_inicial = $idInicio;
        } else {
            $id_inicial = null; // usar o metodo do getInicialFinal
        }

        if ($this->input->get_post('id_final', TRUE) !== false) {
            $id_final = $this->input->get_post('id_final', TRUE);
        } else if ($idFim != null) {
            $id_final = $idFim;
        } else {
            $id_final = null; // usar o metodo do getInicialFinal
        }

        //Laço para percorrer as propostas
        for ($count_id = $id_inicial; $count_id <= $id_final; $count_id++) {

            try {
                //Array com os valores para cada proposta verificar com eliumar a necessidade de pegar mais informacões
                $banco_proposta = array(
                    'objeto' => null,
                    'justificativa' => null,
                    'codigo_siconv' => null,
                    'ano' => null,
                    'id_siconv' => null,
                    'situacao' => null,
                    //'parecer' => null,
                    'modalidade' => null,
                    'proponente' => null,
                    'nome_proponente' => null,
                    'orgao' => null,
                    'codigo_programa' => null,
                    'nome_programa' => null,
                    'convenio' => null,
                    'empenhado' => null,
                    'data_inicio' => null,
                    'data_fim' => null,
                    'valor_global' => null,
                    'valor_repasse' => null,
                    'valor_contrapartida_financeira' => null,
                    'valor_contrapartida_bens' => null
                );

                //criar maneira para verificar se é um id válido
                // ---- Dados da proposta ---- //                
                $pagina = "https://www.convenios.gov.br/siconv/ConsultarProposta/ResultadoDaConsultaDePropostaDetalharProposta.do?idProposta={$count_id}";
                $documento = $this->autentica_siconv->new_obter_pagina($pagina, $this->login, $this->cookie_file_path);
                $documento = $this->removeSpaceSurplus($documento);

                if ($documento != null && $documento != "") {

                    //get codigo siconv
                    $txt1 = $this->getTextBetweenTags($documento, '<div id="convenio"> <img src="\/siconv\/layout\/default\/imagens\/seta_branco.gif" \/>Proposta ', '<\/div>');
                    if ($txt1 != null && count($txt1) > 0) {
                        $banco_proposta['codigo_siconv'] = trim($txt1[0]);
                    } else {
                        $txt1 = $this->getTextBetweenTags($documento, '<\/td> <td class="label" width="25%">Número da Proposta<\/td> <td class="field">', '<\/td> <\/tr>');
                        if ($txt1 != null && count($txt1) > 0) {
                            $banco_proposta['codigo_siconv'] = trim($txt1[0]);
                        }
                    }

                    // Continuando o processamento da proposta apenas no caso de conseguir consultar o codigo no siconv xxxxxxx/ano
                    if ($banco_proposta['codigo_siconv'] != null) {

                        //get situacao
                        $txt1 = $this->getTextBetweenTags($documento, '<tr class="status" id="tr-alterarStatus">', '<\/tr>');
                        if ($txt1 != null && count($txt1) > 0) {
                            $txt1 = $this->getTextBetweenTags($txt1[0], '<td class="field" width="40%">', '<\/td>');
                            if ($txt1 != null && count($txt1) > 0) {
                                $banco_proposta['situacao'] = trim($txt1[0]);
                            } else {
                                $txt1 = $this->getTextBetweenTags($documento, '<tr class="status" id="tr-alterarStatus"> <td class="label">Situação<\/td> <td colspan="4"> <table cellpadding="0" cellspacing="0"> <td class="field" colspan="4"> <div style="float:left">', '<\/div>');
                                if ($txt1 != null && count($txt1) > 0) {
                                    $banco_proposta['situacao'] = trim($txt1[0]);
                                }
                            }
                        }

                        // Inserir proposta no banco de dados apenas se a mesma não for uma proposta de histórico
                        if (strtolower($banco_proposta['situacao']) != 'historico' || strtolower($banco_proposta['situacao']) != 'histórico') {

                            //ano
                            $ano = explode('/', $banco_proposta['codigo_siconv']);
                            $banco_proposta['ano'] = trim($ano[1]);

                            //convenio
                            $txt1 = $this->getTextBetweenTags($documento, '<div id="convenio"> <img src="\/siconv\/layout\/default\/imagens\/seta_branco.gif" \/>Convênio', '<\/div> <\/div>');
                            if ($txt1 != null && count($txt1) > 0) {
                                $banco_proposta['convenio'] = trim($txt1[0]);
                            }

                            //get modalidade
                            $txt1 = $this->getTextBetweenTags($documento, '<tr class="modalidade" id="tr-alterarModalidade">', '<\/tr>');
                            if ($txt1 != null && count($txt1) > 0) {
                                $txt1 = $this->getTextBetweenTags($txt1[0], '<td class="field" width="40%">', '<\/td>');
                                if ($txt1 != null && count($txt1) > 0) {
                                    $banco_proposta['modalidade'] = trim($txt1[0]);
                                }
                            }

                            //get proponente
                            $txt1 = $this->getTextBetweenTags($documento, '<tr class="proponente" id="tr-alterarProponente" >', '<\/tr>');
                            if ($txt1 != null && count($txt1) > 0) {
                                $txt1 = $this->getTextBetweenTags($txt1[0], '<td class="field" colspan="4"> <div style="float:left">', '<\/div>');
                                if ($txt1 != null && count($txt1) > 0) {
                                    $proponente = explode(' - ', trim($txt1[0]));

                                    //pega o numero do proponente - cnpj
                                    $numero_proponente = $proponente[0];
                                    $numero_proponente = explode(' ', $numero_proponente);
                                    $numero_proponente = $numero_proponente[1];
                                    $numero_proponente = str_replace('.', '', $numero_proponente);
                                    $numero_proponente = str_replace('/', '', $numero_proponente);
                                    $numero_proponente = str_replace('-', '', $numero_proponente);
                                    $banco_proposta['proponente'] = trim($numero_proponente);

                                    //pega o nome do proponente - nome da cidade
                                    $nome_proponente = $proponente[1];
                                    $banco_proposta['nome_proponente'] = trim($nome_proponente);
                                }
                            }

                            //get orgao
                            $txt1 = $this->getTextBetweenTags($documento, '<div id="orgao">', '<\/div>');
                            if ($txt1 != null && count($txt1) > 0) {
                                $banco_proposta['orgao'] = trim($txt1[0]);
                            }

                            //get justificativa
                            $txt1 = $this->getTextBetweenTags($documento, '<tr class="justificativa" id="tr-alterarJustificativa">', '<\/tr>');
                            if ($txt1 != null && count($txt1) > 0) {
                                $txt1 = $this->getTextBetweenTags($txt1[0], '<td class="field" colspan="4">', '<\/td>');
                                if ($txt1 != null && count($txt1) > 0) {
                                    $banco_proposta['justificativa'] = trim($txt1[0]);
                                }
                            }

                            //get objeto
                            $txt1 = $this->getTextBetweenTags($documento, '<tr class="objetoConvenio" id="tr-alterarObjetoConvenio">', '<\/tr>');
                            if ($txt1 != null && count($txt1) > 0) {
                                $txt1 = $this->getTextBetweenTags($txt1[0], '<td class="field" colspan="4">', '<\/td>');
                                if ($txt1 != null && count($txt1) > 0) {
                                    $banco_proposta['objeto'] = trim($txt1[0]);
                                }
                            }

                            //get data inicio
                            $txt1 = $this->getTextBetweenTags($documento, '<tr class="inicioVigencia" id="tr-alterarInicioVigencia">', '<\/tr>');
                            if ($txt1 != null && count($txt1) > 0) {
                                $txt1 = $this->getTextBetweenTags($txt1[0], '<td class="field" colspan="4">', '<\/td>');
                                if ($txt1 != null && count($txt1) > 0) {
                                    $banco_proposta['data_inicio'] = date('d-m-Y', strtotime(str_replace('/', '-', trim($txt1[0]))));
                                    $banco_proposta['data_inicio'] = date('Y-m-d', strtotime($banco_proposta['data_inicio']));
                                }
                            }

                            //get data fim
                            $txt1 = $this->getTextBetweenTags($documento, '<tr class="terminoVigencia" id="tr-alterarTerminoVigencia">', '<\/tr>');
                            if ($txt1 != null && count($txt1) > 0) {
                                $txt1 = $this->getTextBetweenTags($txt1[0], '<td class="field" colspan="4">', '<\/td>');
                                if ($txt1 != null && count($txt1) > 0) {
                                    $banco_proposta['data_fim'] = date('d-m-Y', strtotime(str_replace('/', '-', trim($txt1[0]))));
                                    $banco_proposta['data_fim'] = date('Y-m-d', strtotime($banco_proposta['data_fim']));
                                }
                            }

                            //get valor global
                            $txt1 = $this->getTextBetweenTags($documento, '<td class="arvoreValores arvoreExibe" colspan="5"> <b>', '<\/b>');
                            if ($txt1 != null && count($txt1) > 0) {
                                //$txt1 = explode(" ", trim($txt1[0]));
                                //$txt1 = $txt1[1];
                                //$txt1 = str_replace('.', '', $txt1);
                                //$txt1 = str_replace(',', '.', $txt1);
                                $banco_proposta['valor_global'] = trim($txt1[0]);
                            }

                            //get valor repasse
                            $txt1 = $this->getTextBetweenTags($documento, '<span id="detalhePercentual"> <\/span> <div style="padding-left:100px"><b>', '<\/b> Valor de Repasse <\/div>');
                            if ($txt1 != null && count($txt1) > 0) {
                                //$txt1 = explode(" ", trim($txt1[0]));
                                //$txt1 = $txt1[1];
                                //$txt1 = str_replace('.', '', $txt1);
                                //$txt1 = str_replace(',', '.', $txt1);
                                $banco_proposta['valor_repasse'] = trim($txt1[0]);
                            }

                            //get valor contrapartida financeira
                            $txt1 = $this->getTextBetweenTags($documento, '<div style="padding-left:200px"><b>', '<\/b> Valor Contrapartida Financeira <\/div>');
                            if ($txt1 != null && count($txt1) > 0) {
                                //$txt1 = explode(" ", trim($txt1[0]));
                                //$txt1 = $txt1[1];
                                //$txt1 = str_replace('.', '', $txt1);
                                //$txt1 = str_replace(',', '.', $txt1);
                                $banco_proposta['valor_contrapartida_financeira'] = trim($txt1[0]);
                            }

                            //get valor contrapartida bens
                            $txt1 = $this->getTextBetweenTags($documento, '<\/b> Valor Contrapartida Financeira <\/div> <div style="padding-left:200px"><b>', '<\/b> Valor Contrapartida Bens e Serviços <\/div>');
                            if ($txt1 != null && count($txt1) > 0) {
                                //$txt1 = explode(" ", trim($txt1[0]));
                                //$txt1 = $txt1[1];
                                //$txt1 = str_replace('.', '', $txt1);
                                //$txt1 = str_replace(',', '.', $txt1);
                                $banco_proposta['valor_contrapartida_bens'] = trim($txt1[0]);
                            }

                            // ---- Programas ----- //
                            $pagina = "https://www.convenios.gov.br/siconv/ConsultarProposta/ResultadoDaConsultaDePropostaDetalharProposta.do?idProposta={$count_id}&destino=ListarProgramasProposta";
                            $documento = $this->autentica_siconv->new_obter_pagina($pagina, $this->login, $this->cookie_file_path);
                            $documento = $this->removeSpaceSurplus($documento);

                            //get codigo programa
                            $txt1 = $this->getTextBetweenTags($documento, '<div class="codigo">', '<\/div>');
                            if ($txt1 != null && count($txt1) > 0) {
                                $banco_proposta['codigo_programa'] = trim($txt1[0]);
                            }

                            //get nome programa
                            $txt1 = $this->getTextBetweenTags($documento, '<div class="nome">', '<\/div>');
                            if ($txt1 != null && count($txt1) > 0) {
                                $banco_proposta['nome_programa'] = trim($txt1[0]);
                            }

                            // ---- Outros dados ---- //
                            //set idSiconv
                            $banco_proposta['id_siconv'] = $count_id;

                            //var_dump($banco_proposta);
                            //die();

                            $id_insert_proposta = $this->banco_proposta_model->insert_or_update($banco_proposta);

                            //Zerando o cache de todos os models utilizados
                            $this->db->flush_cache();

                            if ($id_insert_proposta != null && $id_insert_proposta != 0) {
                                // ---- Programas ----- //
                                $pagina = "https://www.convenios.gov.br/siconv/ConsultarProposta/ResultadoDaConsultaDePropostaDetalharProposta.do?idProposta={$count_id}&destino=ListarProgramasProposta";
                                $documento = $this->autentica_siconv->new_obter_pagina($pagina, $this->login, $this->cookie_file_path);
                                $documento = $this->removeSpaceSurplus($documento);

                                $programa = array();
                                $array_programas = array();

                                //get codigo programa
                                $txt1 = $this->getTextBetweenTags($documento, '<div class="codigo">', '<\/div>');
                                if ($txt1 != null && count($txt1) > 0) {
                                    for ($indice = 0; $indice < count($txt1); $indice++) {
                                        $programa = $this->init_array_programa();
                                        $programa['codigo_programa'] = trim($txt1[$indice]);
                                        array_push($array_programas, $programa);
                                    }
                                }

                                //get nome programa
                                $txt1 = $this->getTextBetweenTags($documento, '<div class="nome">', '<\/div>');
                                if ($txt1 != null && count($txt1) > 0) {
                                    if (count($array_programas) > 0) {
                                        for ($indice = 0; $indice < count($txt1); $indice++) {
                                            $array_programas[$indice]['nome_programa'] = trim($txt1[$indice]);
                                        }
                                    }
                                }

                                //get id programa
                                $txt1 = $this->getTextBetweenTags($documento, '<td> <nobr>', '<\/a><\/nobr> <\/td>');
                                if ($txt1 != null && count($txt1) > 0) {
                                    if (count($array_programas) > 0) {
                                        for ($indice = 0; $indice < count($txt1); $indice++) {
                                            $temp = explode('id=', trim($txt1[$indice]));
                                            $temp = explode('\';', $temp[1]);
                                            $array_programas[$indice]['id_programa'] = trim($temp[0]);
                                        }
                                    }
                                }

                                if ($array_programas != null && count($array_programas) > 0) {
                                    for ($indice = 0; $indice < count($array_programas); $indice++) {
                                        $pagina = "https://www.convenios.gov.br/siconv/ListarProgramasProposta/ProgramasDaPropostaDetalhar.do?id={$array_programas[$indice]['id_programa']}";
                                        $documento = $this->autentica_siconv->new_obter_pagina($pagina, $this->login, $this->cookie_file_path);
                                        $documento = $this->removeSpaceSurplus($documento);

                                        //get regra de contra partida
                                        $txt1 = $this->getTextBetweenTags($documento, '<tr class="regraContrapartida" id="tr-voltarRegraContrapartida" > <td class="label">Regra Contrapartida<\/td> <td class="field">', '<\/td> <\/tr>');
                                        if ($txt1 != null && count($txt1) > 0) {
                                            $array_programas[$indice]['regra_contrapartida'] = trim($txt1[0]);
                                        }

                                        //get valor global
                                        $txt1 = $this->getTextBetweenTags($documento, '<tr class="valorGlobal" id="tr-voltarValorGlobal" > <td class="label">Valor Global do\(s\) Objeto\(s\)<\/td> <td class="field">', '<\/td> <\/tr>');
                                        if ($txt1 != null && count($txt1) > 0) {
                                            $array_programas[$indice]['valor_global'] = trim($txt1[0]);
                                        }

                                        //get total de contra partida
                                        $txt1 = $this->getTextBetweenTags($documento, '<tr class="valorContrapartida" id="tr-voltarValorContrapartida" > <td class="label">Valor de Contrapartida<\/td> <td class="field">', '<\/td> <\/tr>');
                                        if ($txt1 != null && count($txt1) > 0) {
                                            $array_programas[$indice]['total_contrapartida'] = trim($txt1[0]);
                                        }

                                        //get contra partida financeira
                                        $txt1 = $this->getTextBetweenTags($documento, '<tr class="valorContrapartidaFinanceira" id="tr-voltarValorContrapartidaFinanceira" > <td class="label">&nbsp;&nbsp;&nbsp;&nbsp;Valor de Contrapartida Financeira<\/td> <td class="field">', '<\/td> <\/tr>');
                                        if ($txt1 != null && count($txt1) > 0) {
                                            $array_programas[$indice]['contrapartida_financeira'] = trim($txt1[0]);
                                        }

                                        //get contra partida de bens e serviços
                                        $txt1 = $this->getTextBetweenTags($documento, '<tr class="valorContrapartidaBensServicos" id="tr-voltarValorContrapartidaBensServicos" > <td class="label">&nbsp;&nbsp;&nbsp;&nbsp;Valor de Contrapartida em Bens e Serviços<\/td> <td class="field">', '<\/td> <\/tr>');
                                        if ($txt1 != null && count($txt1) > 0) {
                                            $array_programas[$indice]['contrapartida_bens'] = trim($txt1[0]);
                                        }

                                        //get repasse
                                        $txt1 = $this->getTextBetweenTags($documento, '<tr class="valorRepasse" id="tr-voltarValorRepasse" > <td class="label">Valor de Repasse<\/td> <td class="field">', '<\/td> <\/tr>');
                                        if ($txt1 != null && count($txt1) > 0) {
                                            $array_programas[$indice]['repasse'] = trim($txt1[0]);
                                        }

                                        //get objeto                            
                                        $txt1 = $this->getTextBetweenTags($documento, '<div id="listaObjetos" class="table"> <table id="row"> <thead> <tr> <th class="nome">Objetos<\/th><\/tr><\/thead> <tbody id="tbodyrow"> <tr class="odd"> <td> <div class="nome">', '<\/div> <\/td><\/tr><\/tbody><\/table><\/div>');
                                        if ($txt1 != null && count($txt1) > 0) {
                                            $array_programas[$indice]['objeto'] = trim($txt1[0]);
                                        }

                                        $array_programas[$indice]['id_proposta_banco_proposta'] = $id_insert_proposta;
                                        $id_programa_insert = $this->programa_banco_proposta_model->insert_or_update($array_programas[$indice]);
                                        $this->db->flush_cache();

                                        if ($id_programa_insert != null && $id_programa_insert != 0) {

                                            $array_emendas = array(
                                                'id_programa_banco_proposta' => null,
                                                'codigo_emenda' => null,
                                                'valor_emenda' => null
                                            );

                                            //get ementa
                                            $txt1 = $this->getTextBetweenTags($documento, '<tr class="valorRepasse" id="tr-voltarValorRepasse" > <td class="label">', '<\/td> <\/tr>');

                                            //Adicionando tipo de proposta pelo tipo do programa pegar o tipo do ultimo programa
                                            $tipo = $txt1;
                                            foreach ($tipo as $tipo_linha) {
                                                if (strpos($tipo_linha, "Valor Proponente Específico") !== FALSE) {
                                                    $option = array(
                                                        'id_proposta' => $id_insert_proposta,
                                                        'tipo' => "Emenda Específica"
                                                    );

                                                    $this->banco_proposta_model->update_by_id_proposta($option);
                                                } elseif (strpos($tipo_linha, "Valor Repasse Voluntário") !== FALSE) {
                                                    $option = array(
                                                        'id_proposta' => $id_insert_proposta,
                                                        'tipo' => "Repasse Voluntário"
                                                    );

                                                    $this->banco_proposta_model->update_by_id_proposta($option);
                                                } elseif (strpos($tipo_linha, "Valor Emenda") !== FALSE) {
                                                    $option = array(
                                                        'id_proposta' => $id_insert_proposta,
                                                        'tipo' => "Emenda Parlamentar"
                                                    );

                                                    $this->banco_proposta_model->update_by_id_proposta($option);
                                                }
                                            }

                                            if ($txt1 != null && count($txt1) > 0) {
                                                if (count($txt1) > 1) {
                                                    foreach ($txt1 as $line) {
                                                        if (strpos($line, "Repasse") == FALSE) {
                                                            if (strpos($line, "Emenda") !== FALSE) {
                                                                $temp = explode('<td class="field">', trim($line));
                                                                //get codigo
                                                                if ($temp != null && count($temp > 0)) {
                                                                    $temp = explode('Emenda', trim($temp[0]));
                                                                    if ($temp != null && count($temp > 0)) {
                                                                        $temp = explode('(R$)', trim($temp[1]));
                                                                        $codigo = trim($temp[0]);

                                                                        $array_emendas['codigo_emenda'] = $codigo;
                                                                    }
                                                                }

                                                                //get valor
                                                                $temp = explode('<td class="field">', trim($line));
                                                                if ($temp != null && count($temp > 0)) {
                                                                    $temp = explode('-', trim($temp[1]));
                                                                    if ($temp != null && count($temp > 0)) {
                                                                        $valor = trim($temp[0]);

                                                                        $array_emendas['valor_emenda'] = $valor;
                                                                    }
                                                                }

                                                                $array_emendas['id_programa_banco_proposta'] = $id_programa_insert;
                                                                $this->emenda_banco_proposta_model->insert($array_emendas);
                                                            }
                                                        }
                                                    }
                                                } else {
                                                    if (strpos($txt1[0], "Emenda") !== FALSE) {
                                                        $temp = explode('<td class="field">', trim($txt1[0]));
                                                        if ($temp != null && count($temp > 0)) {
                                                            $temp = explode('Emenda', trim($temp[0]));
                                                            if ($temp != null && count($temp > 0)) {
                                                                $temp = explode('(R$)', trim($temp[1]));
                                                                $codigo = trim($temp[0]);

                                                                $array_emendas['codigo_emenda'] = $codigo;
                                                            }

                                                            //get valor
                                                            $temp = explode('<td class="field">', trim($line));
                                                            if ($temp != null && count($temp > 0)) {
                                                                $temp = explode('-', trim($temp[1]));
                                                                if ($temp != null && count($temp > 0)) {
                                                                    $valor = trim($temp[0]);

                                                                    $array_emendas['valor_emenda'] = $valor;
                                                                }
                                                            }

                                                            $array_emendas['id_programa_banco_proposta'] = $id_programa_insert;
                                                            $this->emenda_banco_proposta_model->insert($array_emendas);
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    } else {
                        if (strpos($documento, "Nenhum registro foi encontrado") === false) {
                            //Falha ao consultar o siconv porém não tem como garantir que a página não existe
                            //Siconv pode estar fora do ar ou respondendo de forma errada deve tentar novamente
                            // Forçando "autenticação" no siconv para evitar erros por sessão expirada
                            //$this->autentica_siconv->new_init_siconv_do_login("guest", "guest", $this->login, $this->cookie_file_path);
                            if ($usuario_propnente == null && $senha_proponente == null) {
                                $this->autentica_siconv->new_init_siconv_do_login("43346880559", "Laisa_M2012", $this->login, $this->cookie_file_path);
                            } else {
                                $this->autentica_siconv->new_init_siconv_do_login($usuario_propnente, $senha_proponente, $this->login, $this->cookie_file_path);
                            }
                            $count_id = $count_id - 1;
                        }
                    }
                } else {
                    //Caso documento seja vazio não conseguiu conectar no siconv e deve tentar novamente
                    $count_id = $count_id - 1;
                }
            } catch (Exception $e) {
                echo $e->getMessage();
            }
        }
    }

    public function import_from_siconv_to_esicar() {
        ini_set('max_execution_time', 0);
        $this->session->set_userdata('pagAtual', 'importsiconv');

        if ($this->input->get_post('codigos_propostas', TRUE) != false) {
            $array_codigos_propostas = explode(';', trim($this->input->get_post('codigos_propostas', TRUE)));
            $atende = $this->input->get_post('atende', TRUE);

            try {
                $this->import_mutiple_from_siconv($array_codigos_propostas, $atende);
                $this->encaminha(base_url('in/gestor/visualiza_propostas'));
            } catch (Exception $ex) {
                $this->alert("Erro ao importar propostas do siconv para o Info Convênios");
            }
        }

        $data['title'] = "Info Convênios - Importar do Siconv";
        $data['main'] = 'in/import_from_siconv_to_esicar';
        $this->load->view('in/template', $data);
    }

    public function import_mutiple_from_siconv($lista_propostas, $atende) {
        ini_set('max_execution_time', 0);

        for ($i = 0; $i < count($lista_propostas); $i++) {
            try {
                $this->import_from_siconv($lista_propostas[$i], $atende, $lista_propostas[$i], false);
            } catch (Exception $ex) {
                $this->alert("Erro ao importar propostas do siconv para o Info Convênios");
            }
        }
    }

    public function import_from_siconv($codigo_proposta = null, $banco_atende = null, $nome_proposta = null, $redirect = true) {

        //DEBUG
        //$codigo_proposta = '042857/2014';
        //$banco_atende = 'Todas as pessoas !!';
        //$nome_proposta = 'Testes Import Siconv banco proposta Info Convênios';

        try {
            ini_set('max_execution_time', 0);

            //Carregando os models necessários
            $this->load->model('banco_proposta_model');
            $this->load->model('proposta_model');
            $this->load->model('justificativa');
            $this->load->model('programa_proposta_model');
            $this->load->model('estados');
            $this->load->model('cronograma_model');
            $this->load->model('area_model');

            //Caso não receba o codigo da proposta e/ou banco_atende via parametro deve ler do post do formulário
            if ($codigo_proposta == null) {
                if ($this->input->get_post('codigo_proposta', TRUE) !== false) {
                    $codigo_proposta = $this->input->get_post('codigo_proposta', TRUE);
                }
            }
            if ($banco_atende == null) {
                if ($this->input->get_post('banco_atende', TRUE) !== false) {
                    $banco_atende = $this->input->get_post('banco_atende', TRUE);
                }
            }
            if ($nome_proposta == null) {
                if ($this->input->get_post('nome_proposta', TRUE) !== false) {
                    $nome_proposta = $this->input->get_post('nome_proposta', TRUE);
                }
            }

            //Teste final se não conseguir o valores necessários retorna alert com o erro - tenta carregar o id da proposta do banco de propostas
            $id_proposta_siconv = null;
            if ($codigo_proposta != null && $banco_atende != null && $nome_proposta != null) {
                $id_proposta_siconv = $this->banco_proposta_model->get_by_codigo_siconv($codigo_proposta);
                if ($id_proposta_siconv != null) {
                    //Executa toda a função de carregamento dos dados
                    //Autenticando no siconv
                    //$this->autentica_siconv->new_init_siconv_do_login("guest", "guest", $this->login, $this->cookie_file_path);
                    $this->autentica_siconv->new_init_siconv_do_login("43346880559", "Laisa_M2012", $this->login, $this->cookie_file_path);

                    //Carregar dados da proposta
                    //Array com os valores para cada proposta verificar com eliumar a necessidade de pegar mais informacões
                    $proposta_padrao = array(
                        'id_siconv' => null,
                        'situacao' => null,
                        'validade' => 0,
                        'proponente' => null,
                        'orgao' => null,
                        'data_inicio' => null,
                        'data_termino' => null,
                        'valor_global' => null,
                        'repasse' => null,
                        'contrapartida_financeira' => null,
                        'contrapartida_bens' => null,
                        'total_contrapartida' => null,
                        'padrao' => 1,
                        'id_proposta_atual' => null,
                        'id_proposta_efetiva' => null,
                        'banco_atende' => $banco_atende,
                        'nome' => $nome_proposta,
                        'area' => 0,
                        'percentual' => doubleval(0),
                        'padrao_oculto' => 1
                    );

                    $justificativa = array(
                        'Justificativa' => null,
                        'objeto' => null,
                        'capacidade' => 0,
                        'Proposta_idProposta' => null
                    );

                    // ---- Dados da proposta ---- //                
                    $pagina = "https://www.convenios.gov.br/siconv/ConsultarProposta/ResultadoDaConsultaDePropostaDetalharProposta.do?idProposta={$id_proposta_siconv}";
                    $documento = $this->autentica_siconv->new_obter_pagina($pagina, $this->login, $this->cookie_file_path);
                    $documento = $this->removeSpaceSurplus($documento);

                    if ($documento != null && $documento != "") {

                        //get codigo siconv
                        $txt1 = $this->getTextBetweenTags($documento, '<div id="convenio"> <img src="\/siconv\/layout\/default\/imagens\/seta_branco.gif" \/>Proposta ', '<\/div>');
                        if ($txt1 != null && count($txt1) > 0) {
                            $proposta_padrao['id_siconv'] = trim($txt1[0]);
                        } else {
                            $txt1 = $this->getTextBetweenTags($documento, '<\/td> <td class="label" width="25%">Número da Proposta<\/td> <td class="field">', '<\/td> <\/tr>');
                            if ($txt1 != null && count($txt1) > 0) {
                                $proposta_padrao['id_siconv'] = trim($txt1[0]);
                            }
                        }

                        // Continuando o processamento da proposta apenas no caso de conseguir consultar o codigo no siconv xxxxxxx/ano
                        if ($proposta_padrao['id_siconv'] != null && $codigo_proposta == $proposta_padrao['id_siconv']) {

                            //get situacao
                            $txt1 = $this->getTextBetweenTags($documento, '<tr class="status" id="tr-alterarStatus">', '<\/tr>');
                            if ($txt1 != null && count($txt1) > 0) {
                                $txt1 = $this->getTextBetweenTags($txt1[0], '<td class="field" width="40%">', '<\/td>');
                                if ($txt1 != null && count($txt1) > 0) {
                                    $proposta_padrao['situacao'] = trim($txt1[0]);
                                } else {
                                    $txt1 = $this->getTextBetweenTags($documento, '<tr class="status" id="tr-alterarStatus"> <td class="label">Situação<\/td> <td colspan="4"> <table cellpadding="0" cellspacing="0"> <td class="field" colspan="4"> <div style="float:left">', '<\/div>');
                                    if ($txt1 != null && count($txt1) > 0) {
                                        $proposta_padrao['situacao'] = trim($txt1[0]);
                                    }
                                }
                            }

                            //get proponente
                            $txt1 = $this->getTextBetweenTags($documento, '<tr class="proponente" id="tr-alterarProponente" >', '<\/tr>');
                            if ($txt1 != null && count($txt1) > 0) {
                                $txt1 = $this->getTextBetweenTags($txt1[0], '<td class="field" colspan="4"> <div style="float:left">', '<\/div>');
                                if ($txt1 != null && count($txt1) > 0) {
                                    $proponente = explode(' - ', trim($txt1[0]));

                                    //pega o numero do proponente - cnpj
                                    $numero_proponente = $proponente[0];
                                    $numero_proponente = explode(' ', $numero_proponente);
                                    $numero_proponente = $numero_proponente[1];
                                    $numero_proponente = str_replace('.', '', $numero_proponente);
                                    $numero_proponente = str_replace('/', '', $numero_proponente);
                                    $numero_proponente = str_replace('-', '', $numero_proponente);
                                    $proposta_padrao['proponente'] = trim($numero_proponente);
                                }
                            }

                            //get orgao
                            $txt1 = $this->getTextBetweenTags($documento, '<div id="orgao">', '<\/div>');
                            if ($txt1 != null && count($txt1) > 0) {
                                $orgao = trim($txt1[0]);
                                $nome_orgao = explode('-', trim($orgao));
                                $proposta_padrao['orgao'] = trim($nome_orgao[0]);
                                $nome_orgao = trim($nome_orgao[1]);
                            }

                            //get area
                            if ($nome_orgao != null) {
                                if ($nome_orgao != '') {
                                    foreach (explode(' ', $nome_orgao) as $parte_nome) {
                                        if ($parte_nome != 'A' && $parte_nome != 'DE' && $parte_nome != 'DO' && $parte_nome != 'DOS' && $parte_nome != 'MINISTERIO' && $parte_nome != 'CONSELHO' && $parte_nome != 'E' && $parte_nome != 'DA') {
                                            $proposta_padrao['area'] = $this->area_model->get_area_from_ministerio($parte_nome);
                                            if ($proposta_padrao['area'] != 0) {
                                                break;
                                            }
                                        }
                                    }
                                }
                            }

                            //Se a area ainda estiver em branco
                            if ($proposta_padrao['area'] == 0) {
                                $proposta_padrao['area'] = 18;
//                                if (strpos(strtolower($nome_orgao), 'racial') != false) {
//                                    $proposta_padrao['area'] = 18;
//                                }
                            }

                            //get data inicio
                            $txt1 = $this->getTextBetweenTags($documento, '<tr class="inicioVigencia" id="tr-alterarInicioVigencia">', '<\/tr>');
                            if ($txt1 != null && count($txt1) > 0) {
                                $txt1 = $this->getTextBetweenTags($txt1[0], '<td class="field" colspan="4">', '<\/td>');
                                if ($txt1 != null && count($txt1) > 0) {
                                    $proposta_padrao['data_inicio'] = date('d-m-Y', strtotime(str_replace('/', '-', trim($txt1[0]))));
                                    $proposta_padrao['data_inicio'] = date('Y-m-d', strtotime($proposta_padrao['data_inicio']));
                                }
                            }

                            //get data fim
                            $txt1 = $this->getTextBetweenTags($documento, '<tr class="terminoVigencia" id="tr-alterarTerminoVigencia">', '<\/tr>');
                            if ($txt1 != null && count($txt1) > 0) {
                                $txt1 = $this->getTextBetweenTags($txt1[0], '<td class="field" colspan="4">', '<\/td>');
                                if ($txt1 != null && count($txt1) > 0) {
                                    $proposta_padrao['data_termino'] = date('d-m-Y', strtotime(str_replace('/', '-', trim($txt1[0]))));
                                    $proposta_padrao['data_termino'] = date('Y-m-d', strtotime($proposta_padrao['data_termino']));
                                }
                            }

                            //get valor global
                            $txt1 = $this->getTextBetweenTags($documento, '<td class="arvoreValores arvoreExibe" colspan="5"> <b>', '<\/b>');
                            if ($txt1 != null && count($txt1) > 0) {
                                $proposta_padrao['valor_global'] = trim($txt1[0]);
                                $temp_double = trim(str_replace("R$", "", $proposta_padrao['valor_global']));
                                $temp_double = trim(str_replace(".", "", $temp_double));
                                $temp_double = trim(str_replace(",", ".", $temp_double));
                                $proposta_padrao['valor_global'] = number_format(doubleval($temp_double), 2, '.', "");
                            }

                            //get valor repasse
                            $txt1 = $this->getTextBetweenTags($documento, '<span id="detalhePercentual"> <\/span> <div style="padding-left:100px"><b>', '<\/b> Valor de Repasse <\/div>');
                            if ($txt1 != null && count($txt1) > 0) {
                                $proposta_padrao['repasse'] = trim($txt1[0]);
                                $temp_double = trim(str_replace("R$", "", $proposta_padrao['repasse']));
                                $temp_double = trim(str_replace(".", "", $temp_double));
                                $temp_double = trim(str_replace(",", ".", $temp_double));
                                $proposta_padrao['repasse'] = number_format(doubleval($temp_double), 2, '.', "");
                            }

                            //get valor contrapartida financeira
                            $txt1 = $this->getTextBetweenTags($documento, '<div style="padding-left:200px"><b>', '<\/b> Valor Contrapartida Financeira <\/div>');
                            if ($txt1 != null && count($txt1) > 0) {
                                $proposta_padrao['contrapartida_financeira'] = trim($txt1[0]);
                                $temp_double = trim(str_replace("R$", "", $proposta_padrao['contrapartida_financeira']));
                                $temp_double = trim(str_replace(".", "", $temp_double));
                                $temp_double = trim(str_replace(",", ".", $temp_double));
                                $proposta_padrao['contrapartida_financeira'] = number_format(doubleval($temp_double), 2, '.', "");
                            }

                            //get valor contrapartida bens
                            $txt1 = $this->getTextBetweenTags($documento, '<\/b> Valor Contrapartida Financeira <\/div> <div style="padding-left:200px"><b>', '<\/b> Valor Contrapartida Bens e Serviços <\/div>');
                            if ($txt1 != null && count($txt1) > 0) {
                                $proposta_padrao['contrapartida_bens'] = trim($txt1[0]);
                                $temp_double = trim(str_replace("R$", "", $proposta_padrao['contrapartida_bens']));
                                $temp_double = trim(str_replace(".", "", $temp_double));
                                $temp_double = trim(str_replace(",", ".", $temp_double));
                                $proposta_padrao['contrapartida_bens'] = number_format(doubleval($temp_double), 2, '.', "");
                            }

                            //Get total contra partida
                            $proposta_padrao['total_contrapartida'] = number_format(doubleval($proposta_padrao['contrapartida_financeira'] + $proposta_padrao['contrapartida_bens']), 2, '.', "");

                            //Get percentual
                            $proposta_padrao['percentual'] = doubleval(doubleval($proposta_padrao['total_contrapartida'] / $proposta_padrao['valor_global']) * 100);

                            //Insert a base da proposta
                            $id_insert_proposta_padrao = $this->proposta_model->add_records($proposta_padrao);

                            //Dados da justificativa
                            //get justificativa
                            $txt1 = $this->getTextBetweenTags($documento, '<tr class="justificativa" id="tr-alterarJustificativa">', '<\/tr>');
                            if ($txt1 != null && count($txt1) > 0) {
                                $txt1 = $this->getTextBetweenTags($txt1[0], '<td class="field" colspan="4">', '<\/td>');
                                if ($txt1 != null && count($txt1) > 0) {
                                    $justificativa['Justificativa'] = trim($txt1[0]);
                                }
                            }

                            //get objeto
                            $txt1 = $this->getTextBetweenTags($documento, '<tr class="objetoConvenio" id="tr-alterarObjetoConvenio">', '<\/tr>');
                            if ($txt1 != null && count($txt1) > 0) {
                                $txt1 = $this->getTextBetweenTags($txt1[0], '<td class="field" colspan="4">', '<\/td>');
                                if ($txt1 != null && count($txt1) > 0) {
                                    $justificativa['objeto'] = trim($txt1[0]);
                                }
                            }

                            //Insere a justificativa
                            $justificativa['Proposta_idProposta'] = $id_insert_proposta_padrao;
                            $id_insert_justifica_proposta_padrao = $this->justificativa->insert($justificativa);

                            //Programas
                            //Zerando o cache de todos os models utilizados
                            $this->db->flush_cache();

                            //Cronograma
                            $pagina = "https://www.convenios.gov.br/siconv/ConsultarProposta/ResultadoDaConsultaDePropostaDetalharProposta.do?idProposta={$id_proposta_siconv}&destino=DetalharCronoDesembolso";
                            $documento = $this->autentica_siconv->new_obter_pagina($pagina, $this->login, $this->cookie_file_path);
                            $documento = $this->removeSpaceSurplus($documento);

                            $tabela_parcelas = $this->getTextBetweenTags($documento, '<tbody id="tbodyrow">', '<\/tbody>');

                            $temp_tabs_odd = $this->getTextBetweenTags(trim($tabela_parcelas[0]), '<tr class="odd">', '<\/tr>');
                            $temp_tabs_even = $this->getTextBetweenTags(trim($tabela_parcelas[0]), '<tr class="even">', '<\/tr>');

                            $tabela_parcelas = array();

                            //add in main list odds
                            if ($temp_tabs_odd != null) {
                                if (count($temp_tabs_odd) > 0) {
                                    foreach ($temp_tabs_odd as $odd) {
                                        array_push($tabela_parcelas, $odd);
                                    }
                                }
                            }

                            //add in main list even
                            if ($temp_tabs_even != null) {
                                if (count($temp_tabs_even) > 0) {
                                    foreach ($temp_tabs_even as $even) {
                                        array_push($tabela_parcelas, $even);
                                    }
                                }
                            }

                            foreach ($tabela_parcelas as $linha) {
                                if (strpos($linha, '<div class="numeroParcela">') != false) {

                                    $array_parcelas = array(
                                        'responsavel' => '',
                                        'mes' => '',
                                        'ano' => '',
                                        'parcela' => '',
                                        'valor_meta' => doubleval(0),
                                        'Proposta_idProposta' => $id_insert_proposta_padrao,
                                        'exportado_siconv' => 0,
                                        'link_meta' => ''
                                    );

                                    //Responsavel
                                    $array_parcelas['responsavel'] = explode('<div class="tipoResponsavel">', $linha);
                                    $array_parcelas['responsavel'] = explode('</div>', $array_parcelas['responsavel'][1]);
                                    $array_parcelas['responsavel'] = trim($array_parcelas['responsavel'][0]);

                                    //mes
                                    $array_parcelas['mes'] = explode('<div class="mes">', $linha);
                                    $array_parcelas['mes'] = explode('</div>', $array_parcelas['mes'][1]);
                                    $array_parcelas['mes'] = $this->get_number_month_from_name(trim($array_parcelas['mes'][0]));

                                    //ano
                                    $array_parcelas['ano'] = explode('<div class="ano">', $linha);
                                    $array_parcelas['ano'] = explode('</div>', $array_parcelas['ano'][1]);
                                    $array_parcelas['ano'] = trim($array_parcelas['ano'][0]);

                                    //parcela
                                    $array_parcelas['parcela'] = explode('<div class="valorTexto">', $linha);
                                    $array_parcelas['parcela'] = explode('</div>', $array_parcelas['parcela'][1]);
                                    $array_parcelas['parcela'] = trim($array_parcelas['parcela'][0]);
                                    $temp_double = trim(str_replace("R$", "", $array_parcelas['parcela']));
                                    $temp_double = trim(str_replace(".", "", $temp_double));
                                    $temp_double = trim(str_replace(",", ".", $temp_double));
                                    $array_parcelas['parcela'] = doubleval($temp_double);

                                    //Link da meta
                                    $link_meta = explode('<nobr><a href="javascript:document.location=', $linha);
                                    $link_meta = explode(';', trim($link_meta[1]));
                                    $link_meta = explode("'", trim($link_meta[0]));
                                    $array_parcelas['link_meta'] = trim($link_meta[1]);
                                    $array_parcelas['link_meta'] = 'https://www.convenios.gov.br' . $array_parcelas['link_meta'];

                                    //Insert
                                    $this->cronograma_model->insert($array_parcelas);
                                }
                            }

                            if ($id_insert_proposta_padrao != null && $id_insert_proposta_padrao != 0) {
                                // ---- Programas ----- //
                                $pagina = "https://www.convenios.gov.br/siconv/ConsultarProposta/ResultadoDaConsultaDePropostaDetalharProposta.do?idProposta={$id_proposta_siconv}&destino=ListarProgramasProposta";
                                $documento = $this->autentica_siconv->new_obter_pagina($pagina, $this->login, $this->cookie_file_path);
                                $documento = $this->removeSpaceSurplus($documento);

                                $programa = array();
                                $array_programas = array();

                                //Pega o dados gerais dos programas
                                //get codigo programa e insere em cada posicao do array para um programa especifico
                                $txt1 = $this->getTextBetweenTags($documento, '<div class="codigo">', '<\/div>');
                                if ($txt1 != null && count($txt1) > 0) {
                                    for ($indice = 0; $indice < count($txt1); $indice++) {
                                        $programa = $this->init_array_programa_proposta_padrao();
                                        $programa['codigo_programa'] = trim($txt1[$indice]);
                                        array_push($array_programas, $programa);
                                    }
                                }

                                //get nome programa
                                $txt1 = $this->getTextBetweenTags($documento, '<div class="nome">', '<\/div>');
                                if ($txt1 != null && count($txt1) > 0) {
                                    if (count($array_programas) > 0) {
                                        for ($indice = 0; $indice < count($txt1); $indice++) {
                                            $array_programas[$indice]['nome_programa'] = trim($txt1[$indice]);
                                        }
                                    }
                                }

                                //get id programa
                                $txt1 = $this->getTextBetweenTags($documento, '<td> <nobr>', '<\/a><\/nobr> <\/td>');
                                if ($txt1 != null && count($txt1) > 0) {
                                    if (count($array_programas) > 0) {
                                        for ($indice = 0; $indice < count($txt1); $indice++) {
                                            $temp = explode('id=', trim($txt1[$indice]));
                                            $temp = explode('\';', $temp[1]);
                                            $array_programas[$indice]['id_programa'] = trim($temp[0]);
                                        }
                                    }
                                }

                                if ($array_programas != null && count($array_programas) > 0) {
                                    for ($indice_programas = 0; $indice_programas < count($array_programas); $indice_programas++) {
                                        $pagina = "https://www.convenios.gov.br/siconv/ListarProgramasProposta/ProgramasDaPropostaDetalhar.do?id={$array_programas[$indice_programas]['id_programa']}";
                                        $documento = $this->autentica_siconv->new_obter_pagina($pagina, $this->login, $this->cookie_file_path);
                                        $documento = $this->removeSpaceSurplus($documento);

                                        //get regra de contra partida
                                        $txt1 = $this->getTextBetweenTags($documento, '<tr class="regraContrapartida" id="tr-voltarRegraContrapartida" > <td class="label">Regra Contrapartida<\/td> <td class="field">', '<\/td> <\/tr>');
                                        if ($txt1 != null && count($txt1) > 0) {
                                            $array_programas[$indice_programas]['percentual'] = trim($txt1[0]);
                                        }

                                        //get valor global
                                        $txt1 = $this->getTextBetweenTags($documento, '<tr class="valorGlobal" id="tr-voltarValorGlobal" > <td class="label">Valor Global do\(s\) Objeto\(s\)<\/td> <td class="field">', '<\/td> <\/tr>');
                                        if ($txt1 != null && count($txt1) > 0) {
                                            $array_programas[$indice_programas]['valor_global'] = trim($txt1[0]);
                                            $temp_double = trim(str_replace("R$", "", $array_programas[$indice_programas]['valor_global']));
                                            $temp_double = trim(str_replace(".", "", $temp_double));
                                            $temp_double = trim(str_replace(",", ".", $temp_double));
                                            $array_programas[$indice_programas]['valor_global'] = number_format(doubleval($temp_double), 2, '.', "");
                                        }

                                        //get total de contra partida
                                        $txt1 = $this->getTextBetweenTags($documento, '<tr class="valorContrapartida" id="tr-voltarValorContrapartida" > <td class="label">Valor de Contrapartida<\/td> <td class="field">', '<\/td> <\/tr>');
                                        if ($txt1 != null && count($txt1) > 0) {
                                            $array_programas[$indice_programas]['total_contrapartida'] = trim($txt1[0]);
                                            $temp_double = trim(str_replace("R$", "", $array_programas[$indice_programas]['total_contrapartida']));
                                            $temp_double = trim(str_replace(".", "", $temp_double));
                                            $temp_double = trim(str_replace(",", ".", $temp_double));
                                            $array_programas[$indice_programas]['total_contrapartida'] = number_format(doubleval($temp_double), 2, '.', "");
                                        }

                                        //get contra partida financeira
                                        $txt1 = $this->getTextBetweenTags($documento, '<tr class="valorContrapartidaFinanceira" id="tr-voltarValorContrapartidaFinanceira" > <td class="label">&nbsp;&nbsp;&nbsp;&nbsp;Valor de Contrapartida Financeira<\/td> <td class="field">', '<\/td> <\/tr>');
                                        if ($txt1 != null && count($txt1) > 0) {
                                            $array_programas[$indice_programas]['contrapartida_financeira'] = trim($txt1[0]);
                                            $temp_double = trim(str_replace("R$", "", $array_programas[$indice_programas]['contrapartida_financeira']));
                                            $temp_double = trim(str_replace(".", "", $temp_double));
                                            $temp_double = trim(str_replace(",", ".", $temp_double));
                                            $array_programas[$indice_programas]['contrapartida_financeira'] = number_format(doubleval($temp_double), 2, '.', "");
                                        }

                                        //get contra partida de bens e serviços
                                        $txt1 = $this->getTextBetweenTags($documento, '<tr class="valorContrapartidaBensServicos" id="tr-voltarValorContrapartidaBensServicos" > <td class="label">&nbsp;&nbsp;&nbsp;&nbsp;Valor de Contrapartida em Bens e Serviços<\/td> <td class="field">', '<\/td> <\/tr>');
                                        if ($txt1 != null && count($txt1) > 0) {
                                            $array_programas[$indice_programas]['contrapartida_bens'] = trim($txt1[0]);
                                            $temp_double = trim(str_replace("R$", "", $array_programas[$indice_programas]['contrapartida_bens']));
                                            $temp_double = trim(str_replace(".", "", $temp_double));
                                            $temp_double = trim(str_replace(",", ".", $temp_double));
                                            $array_programas[$indice_programas]['contrapartida_bens'] = number_format(doubleval($temp_double), 2, '.', "");
                                        }

                                        //get repasse
                                        $txt1 = $this->getTextBetweenTags($documento, '<tr class="valorRepasse" id="tr-voltarValorRepasse" > <td class="label">Valor de Repasse<\/td> <td class="field">', '<\/td> <\/tr>');
                                        if ($txt1 != null && count($txt1) > 0) {
                                            $array_programas[$indice_programas]['repasse'] = trim($txt1[0]);
                                            $temp_double = trim(str_replace("R$", "", $array_programas[$indice_programas]['repasse']));
                                            $temp_double = trim(str_replace(".", "", $temp_double));
                                            $temp_double = trim(str_replace(",", ".", $temp_double));
                                            $array_programas[$indice_programas]['repasse'] = number_format(doubleval($temp_double), 2, '.', "");
                                        }

                                        //get objeto                            
                                        $txt1 = $this->getTextBetweenTags($documento, '<div id="listaObjetos" class="table"> <table id="row"> <thead> <tr> <th class="nome">Objetos<\/th><\/tr><\/thead> <tbody id="tbodyrow"> <tr class="odd"> <td> <div class="nome">', '<\/div> <\/td><\/tr><\/tbody><\/table><\/div>');
                                        if ($txt1 != null && count($txt1) > 0) {
                                            $array_programas[$indice_programas]['objeto'] = trim($txt1[0]);
                                        }

                                        //get qualificacao
                                        $array_programas[$indice_programas]['qualificacao'] = "";

                                        //get programa data
                                        $array_programas[$indice_programas]['programa'] = $pagina;

                                        $array_programas[$indice_programas]['id_proposta'] = $id_insert_proposta_padrao;
                                        $id_programa_insert_padrao = $this->programa_proposta_model->insert_programa_proposta($array_programas[$indice_programas]);
                                        $this->db->flush_cache();

                                        //Metas
                                        $meta = array();

                                        $pagina = "https://www.convenios.gov.br/siconv/ConsultarProposta/ResultadoDaConsultaDePropostaDetalharProposta.do?idProposta={$id_proposta_siconv}&destino=DetalharCronoFisico";
                                        $documento = $this->autentica_siconv->new_obter_pagina($pagina, $this->login, $this->cookie_file_path);
                                        $documento = $this->removeSpaceSurplus($documento);

                                        $lista_metas = $this->getTextBetweenTags($documento, '<nobr><a href=\"javascript:document.location=\'', ' class="buttonLink">Ver Etapas<\/a><\/nobr>');
                                        if (count($lista_metas) > 0) {
                                            for ($indice_metas = 0; $indice_metas < count($lista_metas); $indice_metas++) {
                                                $meta = $this->init_array_metas_padrao();

                                                //Get details meta
                                                $pagina = trim("https://www.convenios.gov.br" . str_replace("';\"", "", $lista_metas[$indice_metas]));
                                                $documento = $this->autentica_siconv->new_obter_pagina($pagina, $this->login, $this->cookie_file_path);
                                                $documento = $this->removeSpaceSurplus($documento);

                                                if ($documento != null && $documento != "") {
                                                    //get nome programa para pegar o id do programa para relacionamento com a meta
                                                    $nome_programa = null;
                                                    $txt1 = $this->getTextBetweenTags($documento, '<td class="label">Programa<\/td> <td class="field" colspan="3">', '<\/td> <\/tr>');
                                                    if ($txt1 != null && count($txt1) > 0) {
                                                        $nome_programa = trim($txt1[0]);
                                                    }

                                                    if ($nome_programa != null) {
                                                        $id_programa = $this->programa_proposta_model->get_id_programa_from_nome_proposta($nome_programa, $id_insert_proposta_padrao);
                                                        if ($id_programa != null) {

                                                            //get id_programa
                                                            $meta['id_programa'] = $id_programa;

                                                            //get id_proposta
                                                            $meta['Proposta_idProposta'] = $id_insert_proposta_padrao;

                                                            //get quantidade
                                                            $txt1 = $this->getTextBetweenTags($documento, '<tr class="quantidade" id="tr-incluirEtapaQuantidade" > <td class="label">Quantidade<\/td> <td class="field" colspan="3">', '<\/td> <\/tr>');
                                                            if ($txt1 != null && count($txt1) > 0) {
                                                                $quantidade = trim($txt1[0]);
                                                                $meta['quantidade'] = $quantidade;
                                                            }

                                                            //get especificacao
                                                            $txt1 = $this->getTextBetweenTags($documento, '<tr class="descricaoDaMeta" id="tr-incluirEtapaDescricaoDaMeta"> <td class="label" width="180">Descrição da Meta<\/td> <td class="field" colspan="3">', '<\/td> <\/tr>');
                                                            if ($txt1 != null && count($txt1) > 0) {
                                                                $meta['especificacao'] = trim($txt1[0]);
                                                            }

                                                            //get unidade fornecimento
                                                            $txt1 = $this->getTextBetweenTags($documento, '<tr class="unidadeFornecimento" id="tr-incluirEtapaUnidadeFornecimento" > <td class="label">Unidade Fornecimento<\/td> <td class="field" colspan="3">', '<\/td> <\/tr>');
                                                            if ($txt1 != null && count($txt1) > 0) {
                                                                $meta['fornecimento'] = trim($txt1[0]);
                                                            }

                                                            //get total (Valor da meta)
                                                            $txt1 = $this->getTextBetweenTags($documento, '<tr class="valorDaMeta" id="tr-incluirEtapaValorDaMeta"> ', '<\/td> <\/tr> ');
                                                            if ($txt1 != null && count($txt1) > 0) {
                                                                $meta['total'] = trim($txt1[0]);
                                                                $meta['total'] = explode("R$", $meta['total']);
                                                                $meta['total'] = trim($meta['total'][2]);
                                                                $temp_double = trim(str_replace(".", "", $meta['total']));
                                                                $temp_double = trim(str_replace(",", ".", $temp_double));
                                                                $meta['total'] = doubleval($temp_double);
                                                            }

                                                            //get valorUnitario
                                                            $meta['valorUnitario'] = doubleval($meta['total'] / $meta['quantidade']);

                                                            //get data inicio
                                                            $txt1 = $this->getTextBetweenTags($documento, '<tr class="dataInicioDaMeta" id="tr-incluirEtapaDataInicioDaMeta"> <td class="label">Data Início Meta<\/td> <td class="field" colspan="3">', '<\/td> <\/tr>');
                                                            if ($txt1 != null && count($txt1) > 0) {
                                                                $meta['data_inicio'] = date('d-m-Y', strtotime(str_replace('/', '-', trim($txt1[0]))));
                                                                $meta['data_inicio'] = date('Y-m-d', strtotime($meta['data_inicio']));
                                                            }

                                                            //get data termino
                                                            $txt1 = $this->getTextBetweenTags($documento, '<tr class="dataFimDaMeta" id="tr-incluirEtapaDataFimDaMeta"> <td class="label">Data de Término da Meta<\/td> <td class="field" colspan="3">', '<\/td> <\/tr>');
                                                            if ($txt1 != null && count($txt1) > 0) {
                                                                $meta['data_termino'] = date('d-m-Y', strtotime(str_replace('/', '-', trim($txt1[0]))));
                                                                $meta['data_termino'] = date('Y-m-d', strtotime($meta['data_termino']));
                                                            }

                                                            //get endereço
                                                            $txt1 = $this->getTextBetweenTags($documento, '<tr class="endereco" id="tr-incluirEtapaEndereco"> <td class="label">Endereço<\/td> <td class="field">', '<\/td>');
                                                            if ($txt1 != null && count($txt1) > 0) {
                                                                $meta['endereco'] = trim($txt1[0]);
                                                            }

                                                            //get cep
                                                            $txt1 = $this->getTextBetweenTags($documento, '<td class="label">CEP<\/td> <td class="field">', '<\/td> <\/tr>');
                                                            if ($txt1 != null && count($txt1) > 0) {
                                                                $meta['cep'] = trim($txt1[0]);
                                                            }

                                                            //get Municipio
                                                            $txt1 = $this->getTextBetweenTags($documento, '<tr class="municipio" id="tr-voltarMunicipio"> <td class="label">Município<\/td> <td class="field">', '<\/td> <\/tr>');
                                                            if ($txt1 != null && count($txt1) > 0) {
                                                                $codigo = explode("-", trim($txt1[0]));
                                                                $codigo = trim($codigo[0]);
                                                                $meta['municipio_nome'] = $meta['municipio_sigla'] = $codigo;
                                                            }

                                                            //get UF
                                                            $txt1 = $this->getTextBetweenTags($documento, '<td class="label">UF<\/td> <td class="field">', '<\/td> <\/tr>');
                                                            if ($txt1 != null && count($txt1) > 0) {
                                                                $sigla_estado = trim($txt1[0]);
                                                                $meta['UF'] = $this->estados->get_by_sigla($sigla_estado);
                                                            }

                                                            //Insert on database
                                                            $id_insert_meta = $this->proposta_model->insert_meta_proposta_padrao($meta);
                                                            $id_cronograma = 0;

                                                            //Cronograma_meta
                                                            if ($id_insert_meta != null) {
                                                                if ($id_insert_meta != 0) {

                                                                    $cronogramas_proposta = $this->cronograma_model->get_by_proposta($id_insert_proposta_padrao);
                                                                    //cronograma_meta
                                                                    if ($cronogramas_proposta != null) {
                                                                        if (count($cronogramas_proposta) > 0) {
                                                                            $array_cronograma_meta = array(
                                                                                'Meta_idMeta' => $id_insert_meta,
                                                                                'valor' => '',
                                                                                'exportado_siconv' => 0,
                                                                                'Cronograma_idCronograma' => $id_cronograma
                                                                            );

                                                                            foreach ($cronogramas_proposta as $crono) {
                                                                                //Carregando as metas do link metas
                                                                                $pagina_meta_crono = trim($crono->link_meta);
                                                                                $documento_meta_crono = $this->autentica_siconv->new_obter_pagina($pagina_meta_crono, $this->login, $this->cookie_file_path);
                                                                                $documento_meta_crono = $this->removeSpaceSurplus($documento_meta_crono);

                                                                                $tabela_metas = $this->getTextBetweenTags($documento_meta_crono, '<tbody id="tbodyrow">', '<\/tbody>');

                                                                                $temp_tabs_odd = $this->getTextBetweenTags(trim($tabela_metas[0]), '<tr class="odd">', '<\/tr>');
                                                                                $temp_tabs_even = $this->getTextBetweenTags(trim($tabela_metas[0]), '<tr class="even">', '<\/tr>');

                                                                                $tabela_metas = array();

                                                                                //add in main list odds
                                                                                if ($temp_tabs_odd != null) {
                                                                                    if (count($temp_tabs_odd) > 0) {
                                                                                        foreach ($temp_tabs_odd as $odd) {
                                                                                            array_push($tabela_metas, $odd);
                                                                                        }
                                                                                    }
                                                                                }

                                                                                //add in main list even
                                                                                if ($temp_tabs_even != null) {
                                                                                    if (count($temp_tabs_even) > 0) {
                                                                                        foreach ($temp_tabs_even as $even) {
                                                                                            array_push($tabela_metas, $even);
                                                                                        }
                                                                                    }
                                                                                }

                                                                                if ($tabela_metas != null) {
                                                                                    if (count($tabela_metas) > 0) {
                                                                                        foreach ($tabela_metas as $linha) {
                                                                                            if (strpos($linha, '<div class="dataInicio">') != false) {

                                                                                                //Data inicio
                                                                                                $temp_data_inicio = explode('<div class="dataInicio">', $linha);
                                                                                                $temp_data_inicio = explode('</div>', $temp_data_inicio[1]);
                                                                                                $temp_data_inicio = trim($temp_data_inicio[0]);
                                                                                                $temp_data_inicio = date('d-m-Y', strtotime(str_replace('/', '-', trim($temp_data_inicio))));
                                                                                                $temp_data_inicio = date('Y-m-d', strtotime($temp_data_inicio));

                                                                                                //Data Fim
                                                                                                $temp_data_fim = explode('<div class="dataFim">', $linha);
                                                                                                $temp_data_fim = explode('</div>', $temp_data_fim[1]);
                                                                                                $temp_data_fim = trim($temp_data_fim[0]);
                                                                                                $temp_data_fim = date('d-m-Y', strtotime(str_replace('/', '-', trim($temp_data_fim))));
                                                                                                $temp_data_fim = date('Y-m-d', strtotime($temp_data_fim));

                                                                                                //Descricao
                                                                                                $temp_descricao = explode('<div class="descricao">', $linha);
                                                                                                $temp_descricao = explode('</div>', $temp_descricao[1]);
                                                                                                $temp_descricao = trim($temp_descricao[0]);

                                                                                                //Valor
                                                                                                $temp_valor = explode('<div class="valorMeta">', $linha);
                                                                                                $temp_valor = explode('</div>', $temp_valor[1]);
                                                                                                $temp_valor = trim($temp_valor[0]);
                                                                                                $temp_double = trim(str_replace("R$", "", $temp_valor));
                                                                                                $temp_double = trim(str_replace(".", "", $temp_double));
                                                                                                $temp_double = trim(str_replace(",", ".", $temp_double));
                                                                                                $temp_valor = doubleval($temp_double);

                                                                                                if ($temp_data_inicio == $meta['data_inicio'] && $temp_data_fim == $meta['data_termino']) {
                                                                                                    $array_cronograma_meta['Meta_idMeta'] = $id_insert_meta;
                                                                                                    $array_cronograma_meta['valor'] = $temp_valor;
                                                                                                    $array_cronograma_meta['Cronograma_idCronograma'] = $crono->idCronograma;

                                                                                                    $this->cronograma_model->insert_cronograma_meta($array_cronograma_meta);
                                                                                                }
                                                                                            }
                                                                                        }
                                                                                    }
                                                                                }
                                                                            }
                                                                        }
                                                                    }
                                                                }
                                                            }

                                                            //Etapas
                                                            $etapa = array();
                                                            //Recarraga a pagina de metas
                                                            //Get details meta
                                                            $pagina = trim("https://www.convenios.gov.br" . str_replace("';\"", "", $lista_metas[$indice_metas]));
                                                            $documento = $this->autentica_siconv->new_obter_pagina($pagina, $this->login, $this->cookie_file_path);
                                                            $documento = $this->removeSpaceSurplus($documento);

                                                            $lista_etapas = $this->getTextBetweenTags($documento, '<nobr><a href=\"javascript:document.location=\'', ' class="buttonLink">Detalhar Etapa<\/a><\/nobr>');
                                                            if (count($lista_etapas) > 0) {
                                                                for ($indice_etapas = 0; $indice_etapas < count($lista_etapas); $indice_etapas++) {
                                                                    $etapa = $this->init_array_etapas_padrao();

                                                                    //Get details etapa
                                                                    $pagina = trim("https://www.convenios.gov.br" . str_replace("';\"", "", $lista_etapas[$indice_etapas]));
                                                                    $documento = $this->autentica_siconv->new_obter_pagina($pagina, $this->login, $this->cookie_file_path);
                                                                    $documento = $this->removeSpaceSurplus($documento);

                                                                    //get id_meta
                                                                    $etapa['Meta_idMeta'] = $id_insert_meta;

                                                                    //get especificacao
                                                                    $txt1 = $this->getTextBetweenTags($documento, '<tr class="especificacao" id="tr-voltarEspecificacao"> <td class="label">Especificação<\/td> <td class="field">', '<\/td> <\/tr>');
                                                                    if ($txt1 != null && count($txt1) > 0) {
                                                                        $etapa['especificacao'] = trim($txt1[0]);
                                                                    }

                                                                    //get quantidade
                                                                    $txt1 = $this->getTextBetweenTags($documento, '<tr class="quantidade" id="tr-voltarQuantidade" > <td class="label">Quantidade<\/td> <td class="field">', '<\/td> <\/tr>');
                                                                    if ($txt1 != null && count($txt1) > 0) {
                                                                        $quantidade = trim($txt1[0]);
                                                                        $etapa['quantidade'] = $quantidade;
                                                                    }

                                                                    //get unidade fornecimento
                                                                    $txt1 = $this->getTextBetweenTags($documento, '<tr class="unidadeFornecimento" id="tr-voltarUnidadeFornecimento" > <td class="label">Unidade Fornecimento<\/td> <td class="field">', '<\/td> <\/tr>');
                                                                    if ($txt1 != null && count($txt1) > 0) {
                                                                        $etapa['fornecimento'] = trim($txt1[0]);
                                                                    }

                                                                    //get total (Valor da etapa)
                                                                    $txt1 = $this->getTextBetweenTags($documento, '<tr class="valor" id="tr-voltarValor"> <td class="label">Valor<\/td> <td class="field">', '<\/td> <\/tr>');
                                                                    if ($txt1 != null && count($txt1) > 0) {
                                                                        $etapa['total'] = trim($txt1[0]);
                                                                        $etapa['total'] = explode("R$", $etapa['total']);
                                                                        $etapa['total'] = trim($etapa['total'][1]);
                                                                        $temp_double = trim(str_replace(".", "", $etapa['total']));
                                                                        $temp_double = trim(str_replace(",", ".", $temp_double));
                                                                        $etapa['total'] = doubleval($temp_double);
                                                                    }

                                                                    //get valorUnitario
                                                                    $etapa['valorUnitario'] = doubleval($etapa['total'] / $etapa['quantidade']);

                                                                    //get data inicio
                                                                    $txt1 = $this->getTextBetweenTags($documento, '<tr class="dataInicio" id="tr-voltarDataInicio"> <td class="label">Data de Início<\/td> <td class="field">', '<\/td> <\/tr>');
                                                                    if ($txt1 != null && count($txt1) > 0) {
                                                                        $etapa['data_inicio'] = date('d-m-Y', strtotime(str_replace('/', '-', trim($txt1[0]))));
                                                                        $etapa['data_inicio'] = date('Y-m-d', strtotime($etapa['data_inicio']));
                                                                    }

                                                                    //get data termino
                                                                    $txt1 = $this->getTextBetweenTags($documento, '<tr class="dataTermino" id="tr-voltarDataTermino"> <td class="label">Data de Término<\/td> <td class="field">', '<\/td> <\/tr>');
                                                                    if ($txt1 != null && count($txt1) > 0) {
                                                                        $etapa['data_termino'] = date('d-m-Y', strtotime(str_replace('/', '-', trim($txt1[0]))));
                                                                        $etapa['data_termino'] = date('Y-m-d', strtotime($etapa['data_termino']));
                                                                    }

                                                                    //get endereço
                                                                    $txt1 = $this->getTextBetweenTags($documento, '<tr class="endereco" id="tr-voltarEndereco"> <td class="label">Endereço<\/td> <td class="field">', '<\/td> <\/tr>');
                                                                    if ($txt1 != null && count($txt1) > 0) {
                                                                        $etapa['endereco'] = trim($txt1[0]);
                                                                    }

                                                                    //get cep
                                                                    $txt1 = $this->getTextBetweenTags($documento, '<tr class="cep" id="tr-voltarCep"> <td class="label">CEP<\/td> <td class="field">', '<\/td> <\/tr>');
                                                                    if ($txt1 != null && count($txt1) > 0) {
                                                                        $etapa['cep'] = trim($txt1[0]);
                                                                    }

                                                                    //get Municipio
                                                                    $txt1 = $this->getTextBetweenTags($documento, '<tr class="municipio" id="tr-voltarMunicipio"> <td class="label">Município<\/td> <td class="field">', '<\/td> <\/tr>');
                                                                    if ($txt1 != null && count($txt1) > 0) {
                                                                        $codigo = explode("-", trim($txt1[0]));
                                                                        $codigo = trim($codigo[0]);
                                                                        $etapa['municipio_nome'] = $etapa['municipio_sigla'] = $codigo;
                                                                    }

                                                                    //get UF
                                                                    $txt1 = $this->getTextBetweenTags($documento, '<td class="label">UF<\/td> <td class="field">', '<\/td> <\/tr>');
                                                                    if ($txt1 != null && count($txt1) > 0) {
                                                                        $sigla_estado = trim($txt1[0]);
                                                                        $etapa['UF'] = $this->estados->get_by_sigla($sigla_estado);
                                                                    }

                                                                    //Insert Etapa
                                                                    $id_insert_etapa_meta_proposta_padrao = $this->proposta_model->insert_etapa_meta_proposta_padrao($etapa);

                                                                    //Cronograma Etapa - TODO
                                                                    if ($id_insert_etapa_meta_proposta_padrao != null) {
                                                                        if ($id_insert_etapa_meta_proposta_padrao != 0) {

                                                                            if ($this->cronograma_model->get_id_cronogramameta_from_id_meta($etapa['Meta_idMeta']) != null) {

                                                                                $array_cronograma_etapa = array(
                                                                                    'Cronograma_meta_idCronograma_meta' => $this->cronograma_model->get_id_cronogramameta_from_id_meta($etapa['Meta_idMeta']),
                                                                                    'Etapa_idEtapa' => $id_insert_etapa_meta_proposta_padrao,
                                                                                    'valor' => $etapa['total'],
                                                                                    'exportado_siconv' => 0
                                                                                );

                                                                                $this->cronograma_model->insert_cronograma_etapa($array_cronograma_etapa);
                                                                            }
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                        //Crono desembolso - Plano de aplicacao detalhado
                                        $pagina = "https://www.convenios.gov.br/siconv/ConsultarProposta/ResultadoDaConsultaDePropostaDetalharProposta.do?idProposta={$id_proposta_siconv}&destino=DetalharBensProposta";
                                        $documento = $this->autentica_siconv->new_obter_pagina($pagina, $this->login, $this->cookie_file_path);
                                        $documento = $this->removeSpaceSurplus($documento);

                                        $tabela_desembolso = $this->getTextBetweenTags(trim($documento), '<tbody id="tbodyrow">', '<\/tbody>');
                                        $temp_tabs_odd = $this->getTextBetweenTags(trim($tabela_desembolso[0]), '<tr class="odd">', '<\/tr>');
                                        $temp_tabs_even = $this->getTextBetweenTags(trim($tabela_desembolso[0]), '<tr class="even">', '<\/tr>');

                                        $tabela_desembolso = array();

                                        //add in main list odds
                                        if ($temp_tabs_odd != null) {
                                            if (count($temp_tabs_odd) > 0) {
                                                foreach ($temp_tabs_odd as $odd) {
                                                    array_push($tabela_desembolso, $odd);
                                                }
                                            }
                                        }

                                        //add in main list even
                                        if ($temp_tabs_even != null) {
                                            if (count($temp_tabs_even) > 0) {
                                                foreach ($temp_tabs_even as $even) {
                                                    array_push($tabela_desembolso, $even);
                                                }
                                            }
                                        }

                                        //pegando os valores de cada linha
                                        if ($tabela_desembolso != null) {
                                            if (count($tabela_desembolso) > 0) {
                                                foreach ($tabela_desembolso as $linha) {
                                                    if (strpos($linha, '<div class="tipoDespesa">') != false) {

                                                        $array_bens = array(
                                                            'descricao' => '',
                                                            'natureza_aquisicao' => 1,
                                                            'natureza_despesa' => '',
                                                            'natureza_despesa_descricao' => '',
                                                            'quantidade' => '',
                                                            'valor_unitario' => '',
                                                            'total' => '',
                                                            'endereco' => '',
                                                            'cep' => '',
                                                            'municipio' => '',
                                                            'UF' => '',
                                                            'observacao' => '',
                                                            'Tipo_despesa_idTipo_despesa' => 1,
                                                            'exportado_siconv' => 0,
                                                            'Proposta_idProposta' => $id_insert_proposta_padrao,
                                                            'fornecimento' => '',
                                                            'id_programa' => ''
                                                        );

                                                        //Tipo Despesa
                                                        $tipo_despesa = explode('<div class="tipoDespesa">', $linha);
                                                        $tipo_despesa = explode('</div>', $tipo_despesa[1]);
                                                        $tipo_despesa = trim($tipo_despesa[0]);

                                                        switch ($tipo_despesa) {
                                                            case 'BEM':
                                                                $array_bens['Tipo_despesa_idTipo_despesa'] = 1;
                                                                break;
                                                            case 'SERVICO':
                                                                $array_bens['Tipo_despesa_idTipo_despesa'] = 2;
                                                                break;
                                                            case 'OBRA':
                                                                $array_bens['Tipo_despesa_idTipo_despesa'] = 3;
                                                                break;
                                                            case 'TRIBUTO':
                                                                $array_bens['Tipo_despesa_idTipo_despesa'] = 4;
                                                                break;
                                                            case 'DESPESA':
                                                                $array_bens['Tipo_despesa_idTipo_despesa'] = 5;
                                                                break;
                                                            case 'OUTROS':
                                                                $array_bens['Tipo_despesa_idTipo_despesa'] = 6;
                                                                break;
                                                        }

                                                        //Link para pagina de detalhes
                                                        $link_detalhes = explode('<a href="', $linha);
                                                        $link_detalhes = explode('" onmouseover="', $link_detalhes[1]);
                                                        $link_detalhes = trim($link_detalhes[0]);

                                                        //get pagina detalhes
                                                        $pagina_detalhes = "https://www.convenios.gov.br" . $link_detalhes;
                                                        $documento_detalhes = $this->autentica_siconv->new_obter_pagina($pagina_detalhes, $this->login, $this->cookie_file_path);
                                                        $documento_detalhes = $this->removeSpaceSurplus($documento_detalhes);

                                                        //get id programa
                                                        $nome_programa_detalhes = explode('<tr class="programa" id="tr-voltarPrograma" > <td class="label">Programa</td> <td class="field" colspan="3">', trim($documento_detalhes));
                                                        $nome_programa_detalhes = explode('</td> </tr>', trim($nome_programa_detalhes[1]));
                                                        $nome_programa_detalhes = trim($nome_programa_detalhes[0]);
                                                        $array_bens['id_programa'] = $this->programa_proposta_model->get_id_programa_from_nome_proposta($nome_programa_detalhes, $id_insert_proposta_padrao);

                                                        //get descricao
                                                        $descricao = explode('<tr class="descricao" id="tr-voltarDescricao" > <td class="label">', trim($documento_detalhes));
                                                        $descricao = explode('Descrição</td> <td class="field" colspan="3">', trim($descricao[1]));
                                                        $descricao = explode('</td> </tr>', trim($descricao[1]));
                                                        $descricao = trim($descricao[0]);
                                                        $array_bens['descricao'] = $descricao;

                                                        //get natureza despesa
                                                        $natureza_despesa = explode('<tr class="codigoNaturezaDespesa" id="tr-voltarCodigoNaturezaDespesa" > <td class="label">', trim($documento_detalhes));
                                                        $natureza_despesa = explode('da Natureza de Despesa</td> <td class="field" colspan="3">', trim($natureza_despesa[1]));
                                                        $natureza_despesa = explode('</td> </tr>', trim($natureza_despesa[1]));
                                                        $natureza_despesa = trim($natureza_despesa[0]);
                                                        $array_bens['natureza_despesa'] = $natureza_despesa;

                                                        //get natureza de despesa descricao
                                                        $natureza_despesa_descricao = explode('<tr class="nomeNaturezaDespesa" id="tr-voltarNomeNaturezaDespesa" >', trim($documento_detalhes));
                                                        $natureza_despesa_descricao = explode('Natureza de Despesa</td> <td class="field" colspan="3">', trim($natureza_despesa_descricao[1]));
                                                        $natureza_despesa_descricao = explode('</td> </tr>', $natureza_despesa_descricao[1]);
                                                        $natureza_despesa_descricao = trim($natureza_despesa_descricao[0]);
                                                        $array_bens['natureza_despesa_descricao'] = $natureza_despesa_descricao;

                                                        //get quantidade
                                                        $quantidade = explode('<tr class="quantidade" id="tr-voltarQuantidade" >', trim($documento_detalhes));
                                                        $quantidade = explode('<td class="label">Quantidade</td>', trim($quantidade[1]));
                                                        $quantidade = explode('<td class="field" colspan="3">', trim($quantidade[1]));
                                                        $quantidade = explode('</td> </tr>', trim($quantidade[1]));
                                                        $quantidade = trim($quantidade[0]);
                                                        $array_bens['quantidade'] = $quantidade;
                                                        $array_bens['quantidade'] = doubleval($array_bens['quantidade']);

                                                        //get valor unitario
                                                        $valor_unitario = explode('<tr class="valorUnitario" id="tr-voltarValorUnitario" > <td class="label">Valor Unitário</td> <td class="field">', trim($documento_detalhes));
                                                        $valor_unitario = explode('</td>', trim($valor_unitario[1]));
                                                        $valor_unitario = trim($valor_unitario[0]);
                                                        $array_bens['valor_unitario'] = $valor_unitario;
                                                        $array_bens['valor_unitario'] = explode("R$", $array_bens['valor_unitario']);
                                                        $array_bens['valor_unitario'] = trim($array_bens['valor_unitario'][1]);
                                                        $temp_double = trim(str_replace(".", "", $array_bens['valor_unitario']));
                                                        $temp_double = trim(str_replace(",", ".", $temp_double));
                                                        $array_bens['valor_unitario'] = doubleval($temp_double);

                                                        //get valor total
                                                        $total = explode('<td class="label">Valor Total</td> <td class="field">', trim($documento_detalhes));
                                                        $total = explode('</td> </tr>', trim($total[1]));
                                                        $total = trim($total[0]);
                                                        $array_bens['total'] = $total;
                                                        $array_bens['total'] = explode("R$", $array_bens['total']);
                                                        $array_bens['total'] = trim($array_bens['total'][1]);
                                                        $temp_double = trim(str_replace(".", "", $array_bens['total']));
                                                        $temp_double = trim(str_replace(",", ".", $temp_double));
                                                        $array_bens['total'] = doubleval($temp_double);

                                                        //get unidade fornecimento
                                                        $unidade_fornecimento = explode('<tr class="unidade" id="tr-voltarUnidade" >', trim($documento_detalhes));
                                                        $unidade_fornecimento = explode('<td class="label">Unidade de Fornecimento</td>', trim($unidade_fornecimento[1]));
                                                        $unidade_fornecimento = explode('<td class="field" colspan="3">', trim($unidade_fornecimento[1]));
                                                        $unidade_fornecimento = explode('</td> </tr>', trim($unidade_fornecimento[1]));
                                                        $unidade_fornecimento = trim($unidade_fornecimento[0]);
                                                        $array_bens['fornecimento'] = $unidade_fornecimento;

                                                        //get observacao
                                                        $observacao = explode('<tr class="observacao" id="tr-voltarObservacao" > <td class="label">Observação</td> <td class="field" colspan="3">', trim($documento_detalhes));
                                                        $observacao = explode('</td> </tr>', trim($observacao[1]));
                                                        $observacao = trim($observacao[0]);
                                                        $array_bens['observacao'] = $observacao;

                                                        //get endereco
                                                        $endereco = explode('<tr class="endereco" id="tr-voltarEndereco" > <td class="label">Endereço de Localização</td> <td class="field" colspan="3">', trim($documento_detalhes));
                                                        $endereco = explode('</td> </tr>', trim($endereco[1]));
                                                        $endereco = trim($endereco[0]);
                                                        $array_bens['endereco'] = $endereco;

                                                        //get cep
                                                        $cep = explode('<td class="label">CEP</td> <td class="field">', trim($documento_detalhes));
                                                        $cep = explode('</td> </tr>', trim($cep[1]));
                                                        $cep = trim($cep[0]);
                                                        $array_bens['cep'] = $cep;

                                                        //get UF
                                                        $uf = explode('<tr class="uf" id="tr-voltarUf" > <td class="label">UF</td> <td class="field">', trim($documento_detalhes));
                                                        $uf = explode('</td>', trim($uf[1]));
                                                        $uf = trim($uf[0]);
                                                        $array_bens['UF'] = $uf;

                                                        //get municipio
                                                        $municipio = explode('<td class="label">Código do Município</td> <td class="field">', trim($documento_detalhes));
                                                        $municipio = explode('</td> </tr>', trim($municipio[1]));
                                                        $municipio = trim($municipio[0]);
                                                        $array_bens['municipio'] = $municipio;

                                                        $this->cronograma_model->insert_despesa($array_bens);
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                    if ($redirect) {
                        $this->encaminha(base_url('in/gestor/visualiza_propostas'));
                    }
                } else {
                    $this->alert("Falha durante a operação. " . "Não foi possível encontrar o id_siconv da proposta informada.");
                }
            } else {
                $this->alert("Falha durante a operação. " . "Código ou banco atende não informados.");
            }
        } catch (Exception $e) {
            $this->alert("Falha durante a operação. " . $e->getMessage());
        }
    }

    public function get_number_month_from_name($name_month) {
        switch (strtolower($name_month)) {
            case 'janeiro':
                return 1;
                break;
            case 'fevereiro':
                return 2;
                break;
            case 'março':
                return 3;
                break;
            case 'abril':
                return 4;
                break;
            case 'maio':
                return 5;
                break;
            case 'junho':
                return 6;
                break;
            case 'julho':
                return 7;
                break;
            case 'agosto':
                return 8;
                break;
            case 'setembro':
                return 9;
                break;
            case 'outubro':
                return 10;
                break;
            case 'novembro':
                return 11;
                break;
            case 'dezembro':
                return 12;
                break;
        }
    }

    //Thomas: Atualizar as propostas para uma lista de cnpjs de proponentes
    public function update_propostas_from_list_cnpjs($cnpjs) {
        ini_set('max_execution_time', 0);
        //Inicializando conexao no siconv
        //$this->autentica_siconv->new_init_siconv_do_login("guest", "guest", $this->login, $this->cookie_file_path);
        $this->autentica_siconv->new_init_siconv_do_login("43346880559", "Laisa_M2012", $this->login, $this->cookie_file_path);

        $this->load->model('banco_proposta_model');

        $propostas = $this->banco_proposta_model->get_propostas_by_proponentes($cnpjs);

        if ($propostas != null && count($propostas) > 0) {
            for ($i = 0; $i < count($propostas); $i++) {
                try {
                    //Array com os valores para cada proposta verificar com eliumar a necessidade de pegar mais informacões
                    $banco_proposta = array(
                        'objeto' => null,
                        'justificativa' => null,
                        'codigo_siconv' => null,
                        'ano' => null,
                        'id_siconv' => null,
                        'situacao' => null,
                        'parecer' => null,
                        'modalidade' => null,
                        'proponente' => null,
                        'nome_proponente' => null,
                        'orgao' => null,
                        'codigo_programa' => null,
                        'nome_programa' => null,
                        'convenio' => null,
                        'empenhado' => null,
                        'data_inicio' => null,
                        'data_fim' => null,
                        'valor_global' => null,
                        'valor_repasse' => null,
                        'valor_contrapartida_financeira' => null,
                        'valor_contrapartida_bens' => null
                    );

                    //criar maneira para verificar se é um id válido
                    // ---- Dados da proposta ---- //                
                    $pagina = "https://www.convenios.gov.br/siconv/ConsultarProposta/ResultadoDaConsultaDePropostaDetalharProposta.do?idProposta={$propostas[i]->id_siconv}";
                    $documento = $this->autentica_siconv->new_obter_pagina($pagina, $this->login, $this->cookie_file_path);
                    $documento = $this->removeSpaceSurplus($documento);

                    if ($documento != null && $documento != "") {

                        //get codigo siconv
                        $txt1 = $this->getTextBetweenTags($documento, '<div id="convenio"> <img src="\/siconv\/layout\/default\/imagens\/seta_branco.gif" \/>Proposta ', '<\/div>');
                        if ($txt1 != null && count($txt1) > 0) {
                            $banco_proposta['codigo_siconv'] = trim($txt1[0]);
                        } else {
                            $txt1 = $this->getTextBetweenTags($documento, '<\/td> <td class="label" width="25%">Número da Proposta<\/td> <td class="field">', '<\/td> <\/tr>');
                            if ($txt1 != null && count($txt1) > 0) {
                                $banco_proposta['codigo_siconv'] = trim($txt1[0]);
                            }
                        }

                        // Continuando o processamento da proposta apenas no caso de conseguir consultar o codigo no siconv xxxxxxx/ano
                        if ($banco_proposta['codigo_siconv'] != null) {

                            //get situacao
                            $txt1 = $this->getTextBetweenTags($documento, '<tr class="status" id="tr-alterarStatus">', '<\/tr>');
                            if ($txt1 != null && count($txt1) > 0) {
                                $txt1 = $this->getTextBetweenTags($txt1[0], '<td class="field" width="40%">', '<\/td>');
                                if ($txt1 != null && count($txt1) > 0) {
                                    $banco_proposta['situacao'] = trim($txt1[0]);
                                } else {
                                    $txt1 = $this->getTextBetweenTags($documento, '<tr class="status" id="tr-alterarStatus"> <td class="label">Situação<\/td> <td colspan="4"> <table cellpadding="0" cellspacing="0"> <td class="field" colspan="4"> <div style="float:left">', '<\/div>');
                                    if ($txt1 != null && count($txt1) > 0) {
                                        $banco_proposta['situacao'] = trim($txt1[0]);
                                    }
                                }
                            }

                            // Inserir proposta no banco de dados apenas se a mesma não for uma proposta de histórico
                            if (strtolower($banco_proposta['situacao']) != 'historico' || strtolower($banco_proposta['situacao']) != 'histórico') {

                                //ano
                                $ano = explode('/', $banco_proposta['codigo_siconv']);
                                $banco_proposta['ano'] = trim($ano[1]);

                                //convenio
                                $txt1 = $this->getTextBetweenTags($documento, '<div id="convenio"> <img src="\/siconv\/layout\/default\/imagens\/seta_branco.gif" \/>Convênio', '<\/div> <\/div>');
                                if ($txt1 != null && count($txt1) > 0) {
                                    $banco_proposta['convenio'] = trim($txt1[0]);
                                }

                                //get modalidade
                                $txt1 = $this->getTextBetweenTags($documento, '<tr class="modalidade" id="tr-alterarModalidade">', '<\/tr>');
                                if ($txt1 != null && count($txt1) > 0) {
                                    $txt1 = $this->getTextBetweenTags($txt1[0], '<td class="field" width="40%">', '<\/td>');
                                    if ($txt1 != null && count($txt1) > 0) {
                                        $banco_proposta['modalidade'] = trim($txt1[0]);
                                    }
                                }

                                //get proponente
                                $txt1 = $this->getTextBetweenTags($documento, '<tr class="proponente" id="tr-alterarProponente" >', '<\/tr>');
                                if ($txt1 != null && count($txt1) > 0) {
                                    $txt1 = $this->getTextBetweenTags($txt1[0], '<td class="field" colspan="4"> <div style="float:left">', '<\/div>');
                                    if ($txt1 != null && count($txt1) > 0) {
                                        $proponente = explode(' - ', trim($txt1[0]));

                                        //pega o numero do proponente - cnpj
                                        $numero_proponente = $proponente[0];
                                        $numero_proponente = explode(' ', $numero_proponente);
                                        $numero_proponente = $numero_proponente[1];
                                        $numero_proponente = str_replace('.', '', $numero_proponente);
                                        $numero_proponente = str_replace('/', '', $numero_proponente);
                                        $numero_proponente = str_replace('-', '', $numero_proponente);
                                        $banco_proposta['proponente'] = trim($numero_proponente);

                                        //pega o nome do proponente - nome da cidade
                                        $nome_proponente = $proponente[1];
                                        $banco_proposta['nome_proponente'] = trim($nome_proponente);
                                    }
                                }

                                //get orgao
                                $txt1 = $this->getTextBetweenTags($documento, '<div id="orgao">', '<\/div>');
                                if ($txt1 != null && count($txt1) > 0) {
                                    $banco_proposta['orgao'] = trim($txt1[0]);
                                }

                                //get justificativa
                                $txt1 = $this->getTextBetweenTags($documento, '<tr class="justificativa" id="tr-alterarJustificativa">', '<\/tr>');
                                if ($txt1 != null && count($txt1) > 0) {
                                    $txt1 = $this->getTextBetweenTags($txt1[0], '<td class="field" colspan="4">', '<\/td>');
                                    if ($txt1 != null && count($txt1) > 0) {
                                        $banco_proposta['justificativa'] = trim($txt1[0]);
                                    }
                                }

                                //get objeto
                                $txt1 = $this->getTextBetweenTags($documento, '<tr class="objetoConvenio" id="tr-alterarObjetoConvenio">', '<\/tr>');
                                if ($txt1 != null && count($txt1) > 0) {
                                    $txt1 = $this->getTextBetweenTags($txt1[0], '<td class="field" colspan="4">', '<\/td>');
                                    if ($txt1 != null && count($txt1) > 0) {
                                        $banco_proposta['objeto'] = trim($txt1[0]);
                                    }
                                }

                                //get data inicio
                                $txt1 = $this->getTextBetweenTags($documento, '<tr class="inicioVigencia" id="tr-alterarInicioVigencia">', '<\/tr>');
                                if ($txt1 != null && count($txt1) > 0) {
                                    $txt1 = $this->getTextBetweenTags($txt1[0], '<td class="field" colspan="4">', '<\/td>');
                                    if ($txt1 != null && count($txt1) > 0) {
                                        $banco_proposta['data_inicio'] = date('d-m-Y', strtotime(str_replace('/', '-', trim($txt1[0]))));
                                        $banco_proposta['data_inicio'] = date('Y-m-d', strtotime($banco_proposta['data_inicio']));
                                    }
                                }

                                //get data fim
                                $txt1 = $this->getTextBetweenTags($documento, '<tr class="terminoVigencia" id="tr-alterarTerminoVigencia">', '<\/tr>');
                                if ($txt1 != null && count($txt1) > 0) {
                                    $txt1 = $this->getTextBetweenTags($txt1[0], '<td class="field" colspan="4">', '<\/td>');
                                    if ($txt1 != null && count($txt1) > 0) {
                                        $banco_proposta['data_fim'] = date('d-m-Y', strtotime(str_replace('/', '-', trim($txt1[0]))));
                                        $banco_proposta['data_fim'] = date('Y-m-d', strtotime($banco_proposta['data_fim']));
                                    }
                                }

                                //get valor global
                                $txt1 = $this->getTextBetweenTags($documento, '<td class="arvoreValores arvoreExibe" colspan="5"> <b>', '<\/b>');
                                if ($txt1 != null && count($txt1) > 0) {
                                    //$txt1 = explode(" ", trim($txt1[0]));
                                    //$txt1 = $txt1[1];
                                    //$txt1 = str_replace('.', '', $txt1);
                                    //$txt1 = str_replace(',', '.', $txt1);
                                    $banco_proposta['valor_global'] = trim($txt1[0]);
                                }

                                //get valor repasse
                                $txt1 = $this->getTextBetweenTags($documento, '<span id="detalhePercentual"> <\/span> <div style="padding-left:100px"><b>', '<\/b> Valor de Repasse <\/div>');
                                if ($txt1 != null && count($txt1) > 0) {
                                    //$txt1 = explode(" ", trim($txt1[0]));
                                    //$txt1 = $txt1[1];
                                    //$txt1 = str_replace('.', '', $txt1);
                                    //$txt1 = str_replace(',', '.', $txt1);
                                    $banco_proposta['valor_repasse'] = trim($txt1[0]);
                                }

                                //get valor contrapartida financeira
                                $txt1 = $this->getTextBetweenTags($documento, '<div style="padding-left:200px"><b>', '<\/b> Valor Contrapartida Financeira <\/div>');
                                if ($txt1 != null && count($txt1) > 0) {
                                    //$txt1 = explode(" ", trim($txt1[0]));
                                    //$txt1 = $txt1[1];
                                    //$txt1 = str_replace('.', '', $txt1);
                                    //$txt1 = str_replace(',', '.', $txt1);
                                    $banco_proposta['valor_contrapartida_financeira'] = trim($txt1[0]);
                                }

                                //get valor contrapartida bens
                                $txt1 = $this->getTextBetweenTags($documento, '<\/b> Valor Contrapartida Financeira <\/div> <div style="padding-left:200px"><b>', '<\/b> Valor Contrapartida Bens e Serviços <\/div>');
                                if ($txt1 != null && count($txt1) > 0) {
                                    //$txt1 = explode(" ", trim($txt1[0]));
                                    //$txt1 = $txt1[1];
                                    //$txt1 = str_replace('.', '', $txt1);
                                    //$txt1 = str_replace(',', '.', $txt1);
                                    $banco_proposta['valor_contrapartida_bens'] = trim($txt1[0]);
                                }

                                // ---- Programas ----- //
                                $pagina = "https://www.convenios.gov.br/siconv/ConsultarProposta/ResultadoDaConsultaDePropostaDetalharProposta.do?idProposta={$propostas[i]->id_siconv}&destino=ListarProgramasProposta";
                                $documento = $this->autentica_siconv->new_obter_pagina($pagina, $this->login, $this->cookie_file_path);
                                $documento = $this->removeSpaceSurplus($documento);

                                //get codigo programa
                                $txt1 = $this->getTextBetweenTags($documento, '<div class="codigo">', '<\/div>');
                                if ($txt1 != null && count($txt1) > 0) {
                                    $banco_proposta['codigo_programa'] = trim($txt1[0]);
                                }

                                //get nome programa
                                $txt1 = $this->getTextBetweenTags($documento, '<div class="nome">', '<\/div>');
                                if ($txt1 != null && count($txt1) > 0) {
                                    $banco_proposta['nome_programa'] = trim($txt1[0]);
                                }

                                // ---- Outros dados ---- //
                                //set idSiconv
                                $banco_proposta['id_siconv'] = $propostas[$i]->id_siconv;

                                $this->banco_proposta_model->insert_or_update($banco_proposta);

                                //Zerando o cache de todos os models utilizados
                                $this->db->flush_cache();
                            }
                        } else {
                            if (strpos($documento, "Nenhum registro foi encontrado") === false) {
                                //Falha ao consultar o siconv porém não tem como garantir que a página não existe
                                //Siconv pode estar fora do ar ou respondendo de forma errada deve tentar novamente
                                // Forçando "autenticação" no siconv para evitar erros por sessão expirada
                                //$this->new_init_siconv_do_login("guest", "guest");
                                $this->autentica_siconv->new_init_siconv_do_login("43346880559", "Laisa_M2012", $this->login, $this->cookie_file_path);
                                $i = $i - 1;
                            }
                        }
                    } else {
                        //Falha ao conectar no siconv
                        $i = $i - 1;
                    }
                } catch (Exception $e) {
                    echo $e->getMessage();
                }
            }
        }
    }

    public function init_array_programa() {
        $temp_array = array(
            'id_programa_banco_proposta' => null,
            'id_proposta_banco_proposta' => null,
            'nome_programa' => null,
            'codigo_programa' => null,
            'regra_contrapartida' => null,
            'valor_global' => null,
            'total_contrapartida' => null,
            'contrapartida_financeira' => null,
            'contrapartida_bens' => null,
            'repasse' => null,
            'id_programa' => null,
            'objeto' => null
        );

        return $temp_array;
    }

    public function init_array_programa_proposta_padrao() {
        $temp_array = array(
            'id_proposta' => null,
            'nome_programa' => null,
            'codigo_programa' => null,
            'programa' => null,
            'percentual' => null,
            'valor_global' => null,
            'total_contrapartida' => null,
            'contrapartida_financeira' => null,
            'contrapartida_bens' => null,
            'repasse' => null,
            'id_programa' => null,
            'objeto' => null,
            'qualificacao' => null
        );

        return $temp_array;
    }

    public function init_array_metas_padrao() {
        $temp_array = array(
            'especificacao' => null,
            'fornecimento' => null,
            'total' => null,
            'quantidade' => null,
            'valorUnitario' => null,
            'data_inicio' => null,
            'data_termino' => null,
            'UF' => null,
            'municipio_sigla' => null,
            'municipio_nome' => null,
            'endereco' => null,
            'cep' => null,
            'Proposta_idProposta' => null,
            'exportado_siconv' => 0,
            'id_programa' => null
        );

        return $temp_array;
    }

    public function init_array_etapas_padrao() {
        $temp_array = array(
            'especificacao' => null,
            'fornecimento' => null,
            'total' => null,
            'quantidade' => null,
            'valorUnitario' => null,
            'data_inicio' => null,
            'data_termino' => null,
            'UF' => null,
            'municipio_sigla' => null,
            'municipio_nome' => null,
            'endereco' => null,
            'cep' => null,
            'Meta_idMeta' => null,
            'exportado_siconv' => 0
        );

        return $temp_array;
    }

    public function atualiza_pareceres() {
        ini_set('max_execution_time', 0);

        $ids = $this->input->post('ids_siconv', true);

        foreach ($ids as $id) {
            //$this->get_parecer_empenho_banco_proposta_siconv($id);

            $this->get_propostas_siconv($id, $id);
        }

        $this->encaminha(base_url('index.php/in/dados_siconv/visualiza_propostas_pareceres'));
    }

    public function atualiza_propostas_automatico() {
        $this->load->model('usuariomodel');

        $this->db->distinct();
        $this->db->select("cnpj");
        $cnpjs = $this->db->get('cnpj_siconv')->result();

        $this->db->flush_cache();

        foreach ($cnpjs as $cnpj) {
            $this->db->distinct();
            $this->db->select("id_siconv");
            $this->db->where('proponente', $cnpj->cnpj);
            $ids = $this->db->get('banco_proposta')->result();

            $this->usuariomodel->enviar_email_cron('Propostas e Pareceres - Serviço iniciado em ' . date('d/m/Y H:i:s') . "<br/>- CNPJ Nº " . $cnpj->cnpj, "Propostas e Pareceres");

            foreach ($ids as $id)
                $this->get_propostas_siconv($id->id_siconv, $id->id_siconv);
        }

        $this->usuariomodel->enviar_email_cron('Propostas e Pareceres - Serviço Finalizado em ' . date('d/m/Y H:i:s'), "Propostas e Pareceres");
    }

    function encaminha($url) {
        echo "<script type='text/javascript'>
	window.location='" . $url . "';
	</script>";
    }

    public function testa_parecer() {
        $id_proposta_banco_propostas = $this->input->get('id', TRUE);
        //$this->autentica_siconv->new_init_siconv_do_login("guest", "guest", $this->login, $this->cookie_file_path);
        $this->autentica_siconv->new_init_siconv_do_login("43346880559", "Laisa_M2012", $this->login, $this->cookie_file_path);

        $this->load->model('banco_proposta_model');
        $this->load->model('empenhos_model');
        $this->load->model('parecer_banco_proposta_model');

        $banco_proposta = array(
            'id_siconv' => null,
            'parecer' => null,
            'empenhado' => null
        );

        $proposta = $this->banco_proposta_model->get_by_id($id_proposta_banco_propostas);

        ## Consultando pareceres
        // ---- Pareceres ---- //
        $pagina = "https://www.convenios.gov.br/siconv/ConsultarProposta/ResultadoDaConsultaDePropostaDetalharProposta.do?idProposta={$proposta->id_siconv}&destino=DetalharParecerProposta";
        $documento = $this->autentica_siconv->new_obter_pagina($pagina, $this->login, $this->cookie_file_path);
        $documento = $this->removeSpaceSurplus($documento);

        //get parecer
        $txt1 = $this->getTextBetweenTags($documento, '<div id="listaParecerProposta" class="table">', '<\/div><div id="tableFooter">');
        if ($txt1 != null && count($txt1) > 0) {
            $tabela = str_replace('id="row"', 'class="table"', $txt1[0]);
            if (strpos($tabela, "Nenhum registro foi encontrado") <= 0) {
                $tabelaComLinks = str_replace("javascript:document.location='", "javascript:document.location='https://www.convenios.gov.br", $tabela);
                $tabelaFinal = str_replace("';", "&path=/MostraPrincipalConsultarPrograma.do&Usr=guest&Pwd=guest';", $tabelaComLinks);
                $banco_proposta['parecer'] = $tabelaFinal;

                $datas = $this->getTextBetweenTags($tabelaFinal, '<div class="data">', "<\/div>");
                $botoes = $this->getTextBetweenTags($tabelaFinal, "<nobr>", "<\/nobr>");

                foreach ($botoes as $i => $botao) {
                    $link = $this->getTextBetweenTags($botao, "<a href=\"javascript:document.location='", "';\" class=\"buttonLink\">");

                    $paginaParecer = $this->autentica_siconv->new_obter_pagina($link[0], $this->login, $this->cookie_file_path);
                    $paginaParecer = $this->removeSpaceSurplus($paginaParecer);

                    $tabelaParecer = $this->getTextBetweenTags($paginaParecer, '<tbody>', '<\/tbody>');

                    $parecer = substr($tabelaParecer[0], strpos($tabelaParecer[0], '<textarea'));
                    $parecer = substr($parecer, 0, strpos($parecer, '</textarea>'));

                    $parecer = substr($parecer, strpos($parecer, '">') + 2);

                    $dataParecer = $datas[$i];
                    $idProposta = $this->getTextBetweenTags($botao, "idProposta=", "&idParecer");
                    $idParecer = $this->getTextBetweenTags($botao, "idParecer=", "&path");

                    $options = array(
                        'data_parecer' => implode("-", array_reverse(explode("/", $dataParecer))),
                        'id_proposta' => $proposta->id_siconv,
                        'id_parecer' => $idParecer[0],
                        'parecer' => $parecer
                    );

                    $this->parecer_banco_proposta_model->atualiza_parecer($options);
                }
            }
        }
    }

    public function get_parecer_empenho_banco_proposta_siconv($id_proposta_banco_propostas) {
        try {
            ini_set('max_execution_time', 0);
            //Inicializando conexao no siconv
            //$this->autentica_siconv->new_init_siconv_do_login("guest", "guest", $this->login, $this->cookie_file_path);
            $this->autentica_siconv->new_init_siconv_do_login("43346880559", "Laisa_M2012", $this->login, $this->cookie_file_path);

            $this->load->model('banco_proposta_model');
            $this->load->model('empenhos_model');
            $this->load->model('parecer_banco_proposta_model');

            $banco_proposta = array(
                'id_siconv' => null,
                'parecer' => null,
                'empenhado' => null
            );

            $proposta = $this->banco_proposta_model->get_by_id($id_proposta_banco_propostas);

            ## Consultando empenhado
            // ---- Dados da proposta ---- //                
            $pagina = "https://www.convenios.gov.br/siconv/ConsultarProposta/ResultadoDaConsultaDePropostaDetalharProposta.do?idProposta={$proposta->id_siconv}";
            $documento = $this->autentica_siconv->new_obter_pagina($pagina, $this->login, $this->cookie_file_path);
            $documento = $this->removeSpaceSurplus($documento);

            //empenhado
            $txt1 = $this->getTextBetweenTags($documento, '<tr><td class="label" width="14%">Empenhado<\/td> <td class="field" width="5%">', '<\/td>');
            if ($txt1 != null && count($txt1) > 0) {
                $banco_proposta['empenhado'] = trim($txt1[0]);
            }

            ## Consultando pareceres
            // ---- Pareceres ---- //
            $pagina = "https://www.convenios.gov.br/siconv/ConsultarProposta/ResultadoDaConsultaDePropostaDetalharProposta.do?idProposta={$proposta->id_siconv}&destino=DetalharParecerProposta";
            $documento = $this->autentica_siconv->new_obter_pagina($pagina, $this->login, $this->cookie_file_path);
            $documento = $this->removeSpaceSurplus($documento);

            //get parecer
            $txt1 = $this->getTextBetweenTags($documento, '<div id="listaParecerProposta" class="table">', '<\/div><div id="tableFooter">');
            if ($txt1 != null && count($txt1) > 0) {
                $tabela = str_replace('id="row"', 'class="table"', $txt1[0]);
                if (strpos($tabela, "Nenhum registro foi encontrado") <= 0) {
                    $tabelaComLinks = str_replace("javascript:document.location='", "javascript:document.location='https://www.convenios.gov.br", $tabela);
                    $tabelaFinal = str_replace("';", "&path=/MostraPrincipalConsultarPrograma.do&Usr=guest&Pwd=guest';", $tabelaComLinks);
                    $banco_proposta['parecer'] = $tabelaFinal;

                    $datas = $this->getTextBetweenTags($tabelaFinal, '<div class="data">', "<\/div>");
                    $botoes = $this->getTextBetweenTags($tabelaFinal, "<nobr>", "<\/nobr>");

                    foreach ($botoes as $i => $botao) {
                        $link = $this->getTextBetweenTags($botao, "<a href=\"javascript:document.location='", "';\" class=\"buttonLink\">");

                        $paginaParecer = $this->autentica_siconv->new_obter_pagina($link[0], $this->login, $this->cookie_file_path);
                        $paginaParecer = $this->removeSpaceSurplus($paginaParecer);

                        $tem_anexo_parecer = $this->getTextBetweenTags($paginaParecer, "<div id=\"listaAnexos\" class=\"table\">", "<\/div>");
                        $temAnexo = trim($tem_anexo_parecer[0]) != "Nenhum registro foi encontrado." ? true : false;

                        $paginaParecer = $this->autentica_siconv->new_obter_pagina($link[0], $this->login, $this->cookie_file_path);
                        $paginaParecer = $this->removeSpaceSurplus($paginaParecer);

                        $tabelaParecer = $this->getTextBetweenTags($paginaParecer, '<tbody>', '<\/tbody>');

                        $parecer = substr($tabelaParecer[0], strpos($tabelaParecer[0], '<textarea'));
                        $parecer = substr($parecer, 0, strpos($parecer, '</textarea>'));

                        $parecer = substr($parecer, strpos($parecer, '">') + 2);

                        $dataParecer = $datas[$i];
                        $idProposta = $this->getTextBetweenTags($botao, "idProposta=", "&idParecer");
                        $idParecer = $this->getTextBetweenTags($botao, "idParecer=", "&path");

                        $options = array(
                            'data_parecer' => implode("-", array_reverse(explode("/", $dataParecer))),
                            'id_proposta' => $proposta->id_siconv,
                            'id_parecer' => $idParecer[0],
                            'parecer' => $parecer,
                            'tem_anexo' => $temAnexo
                        );

                        $this->parecer_banco_proposta_model->atualiza_parecer($options);
                    }
                }
            }

            //PARECER PLANO DE TRABALHO
            $txt1 = $this->getTextBetweenTags($documento, '<div id="tituloListagem" class="table">', '<\/div><div id="tableFooter">');
            if ($txt1 != null && count($txt1) > 0) {
                $tabela = str_replace('id="row"', 'class="table"', $txt1[0]);
                if (strpos($tabela, "Nenhum registro foi encontrado") <= 0) {
                    $tabelaComLinks = str_replace("javascript:document.location='", "javascript:document.location='https://www.convenios.gov.br", $tabela);
                    $tabelaFinal = str_replace("';", "&path=/MostraPrincipalConsultarPrograma.do&Usr=guest&Pwd=guest';", $tabelaComLinks);
                    $banco_proposta['parecer_plano_trabalho'] = $tabelaFinal;

                    $datas = $this->getTextBetweenTags($tabelaFinal, '<div class="data">', "<\/div>");
                    $botoes = $this->getTextBetweenTags($tabelaFinal, "<nobr>", "<\/nobr>");

                    foreach ($botoes as $i => $botao) {
                        $link = $this->getTextBetweenTags($botao, "<a href=\"javascript:document.location='", "';\" class=\"buttonLink\">");

                        $paginaParecer = $this->autentica_siconv->new_obter_pagina($link[0], $this->login, $this->cookie_file_path);
                        $paginaParecer = $this->removeSpaceSurplus($paginaParecer);

                        $tem_anexo_parecer = $this->getTextBetweenTags($paginaParecer, "<div id=\"tituloListagem\" class=\"table\">", "<\/div>");
                        $temAnexo = strpos($tem_anexo_parecer[0], "Nenhum registro foi encontrado.") ? false : true;

                        $paginaParecer = $this->autentica_siconv->new_obter_pagina($link[0], $this->login, $this->cookie_file_path);
                        $paginaParecer = $this->removeSpaceSurplus($paginaParecer);

                        $tabelaParecer = $this->getTextBetweenTags($paginaParecer, '<tbody>', '<\/tbody>');

                        $parecer = substr($tabelaParecer[0], strpos($tabelaParecer[0], '<textarea'));
                        $parecer = substr($parecer, 0, strpos($parecer, '</textarea>'));

                        $parecer = substr($parecer, strpos($parecer, '">') + 2);

                        $dataParecer = $datas[$i];
                        $idProposta = $this->getTextBetweenTags($botao, "idProposta=", "&idParecer");
                        $idParecer = $this->getTextBetweenTags($botao, "idParecer=", "&path");

                        $options = array(
                            'data_parecer' => implode("-", array_reverse(explode("/", $dataParecer))),
                            'id_proposta' => $proposta->id_siconv,
                            'id_parecer' => $idParecer[0],
                            'parecer' => $parecer,
                            'tem_anexo' => $temAnexo
                        );

                        $this->parecer_banco_proposta_model->atualiza_parecer_plano_trabalho($options);
                    }
                }
            }

            //PARECER AJUSTE PLANO DE TRABALHO
            $txt1 = $this->getTextBetweenTags($documento, '<div id="listaParecerAjustePlanoTrabalho" class="table">', '<\/div><div id="tableFooter">');
            if ($txt1 != null && count($txt1) > 0) {
                $tabela = str_replace('id="row"', 'class="table"', $txt1[0]);
                if (strpos($tabela, "Nenhum registro foi encontrado") <= 0) {
                    $tabelaComLinks = str_replace("javascript:document.location='", "javascript:document.location='https://www.convenios.gov.br", $tabela);
                    $tabelaFinal = str_replace("';", "&path=/MostraPrincipalConsultarPrograma.do&Usr=guest&Pwd=guest';", $tabelaComLinks);
                    $banco_proposta['parecer_ajuste_plano_trabalho'] = $tabelaFinal;

                    $datas = $this->getTextBetweenTags($tabelaFinal, '<div class="data">', "<\/div>");
                    $botoes = $this->getTextBetweenTags($tabelaFinal, "<nobr>", "<\/nobr>");

                    foreach ($botoes as $i => $botao) {
                        $link = $this->getTextBetweenTags($botao, "<a href=\"javascript:document.location='", "';\" class=\"buttonLink\">");

                        $paginaParecer = $this->autentica_siconv->new_obter_pagina($link[0], $this->login, $this->cookie_file_path);
                        $paginaParecer = $this->removeSpaceSurplus($paginaParecer);

                        $tem_anexo_parecer = $this->getTextBetweenTags($paginaParecer, "<div id=\"tituloListagem\" class=\"table\">", "<\/div>");
                        $temAnexo = false;
                        if (!empty($tem_anexo_parecer))
                            $temAnexo = strpos($tem_anexo_parecer[0], "Nenhum registro foi encontrado.") ? false : true;

                        $paginaParecer = $this->autentica_siconv->new_obter_pagina($link[0], $this->login, $this->cookie_file_path);
                        $paginaParecer = $this->removeSpaceSurplus($paginaParecer);

                        $tabelaParecer = $this->getTextBetweenTags($paginaParecer, '<tbody>', '<\/tbody>');

                        $parecer = substr($tabelaParecer[0], strpos($tabelaParecer[0], '<textarea'));
                        $parecer = substr($parecer, 0, strpos($parecer, '</textarea>'));

                        $parecer = substr($parecer, strpos($parecer, '">') + 2);

                        $dataParecer = null;
                        if (!empty($datas))
                            $dataParecer = $datas[$i];
                        $idProposta = $this->getTextBetweenTags($botao, "idProposta=", "&idParecer");
                        $idParecer = $this->getTextBetweenTags($botao, "idParecer=", "&path");

                        $options = array(
                            'data_parecer' => implode("-", array_reverse(explode("/", $dataParecer))),
                            'id_proposta' => $proposta->id_siconv,
                            'id_parecer' => $idParecer[0],
                            'parecer' => $parecer,
                            'tem_anexo' => $temAnexo
                        );

                        $this->parecer_banco_proposta_model->atualiza_parecer_ajuste_plano_trabalho($options);
                    }
                }
            }

            // --- Empenhos --- //
            if ($banco_proposta['empenhado'] != null && $banco_proposta['empenhado'] == 'sim') {
                $pagina = "https://www.convenios.gov.br/siconv/ConsultarProposta/ResultadoDaConsultaDePropostaDetalharProposta.do?idProposta={$proposta->id_siconv}&destino=ListarEmpenhos";
                $documento = $this->autentica_siconv->new_obter_pagina($pagina, $this->login, $this->cookie_file_path);
                $documento = $this->removeSpaceSurplus($documento);

                //tabela empenhos
                $txt1 = $this->getTextBetweenTags($documento, '<tbody id="tbodyrow"> <tr class="odd">', '<\/tr> <\/tbody>');
                if ($txt1 != null && count($txt1) > 0) {
                    $tabela_empenhos = trim($txt1[0]);
                }

                //Array com todos os empenhos da proposta como sempre forcando a limpeza com o null
                $empenhos = null;
                $empenhos = array();

                $rows = $this->getTextBetweenTags($tabela_empenhos, '<td>', '<\/td>');
                if ($rows != null && count($rows) > 0) {
                    foreach ($rows as $row) {
                        $txt_link = $this->getTextBetweenTags(trim($row), '<a href=', '>');
                        if ($txt_link != null && count($txt_link) > 0) {

                            //array com os empenhos indivuais para adicionar no array com todos os empenhos
                            $empenho = null;
                            $empenho = array(
                                'id_proposta_siconv' => null,
                                'id_empenho_siconv' => null,
                                'especie_empenho' => null,
                                'tabela_cronograma_empenho' => null
                            );

                            //pegando o codigo e carregando o link para acesso as demais dados
                            $link_empenho_detalhado = explode(' ', $txt_link[0]);
                            $link_empenho_detalhado = $link_empenho_detalhado[0];

                            $link_empenho_detalhado = str_replace('"', '', $link_empenho_detalhado);
                            $pagina = "https://www.convenios.gov.br{$link_empenho_detalhado}";
                            $documento = $this->autentica_siconv->new_obter_pagina($pagina, $this->login, $this->cookie_file_path);
                            $documento = $this->removeSpaceSurplus($documento);

                            //get id empenho
                            $txt1 = $this->getTextBetweenTags($documento, '<input type="hidden" name="id" value="', '" id="anularId">');
                            if ($txt1 != null && count($txt1) > 0) {
                                $empenho['id_empenho_siconv'] = trim($txt1[0]);
                            } else {
                                $txt1 = $this->getTextBetweenTags($documento, '<input type="hidden" name="id" value="', '" id="cancelarId">');
                                if ($txt1 != null && count($txt1) > 0) {
                                    $empenho['id_empenho_siconv'] = trim($txt1[0]);
                                }
                            }

                            //get especie_empenho
                            $txt1 = $this->getTextBetweenTags($documento, '<td class="label">Espécie de Empenho<\/td> <td class="field">', '<\/td>');
                            if ($txt1 != null && count($txt1) > 0) {
                                $empenho['especie_empenho'] = trim($txt1[0]);
                            }

                            //get tabela cronograma empenho
                            $txt1 = $this->getTextBetweenTags($documento, '<div id="cronogramas" class="table"> <table id="row">', '<\/table><\/div>');
                            if ($txt1 != null && count($txt1) > 0) {
                                $empenho['tabela_cronograma_empenho'] = trim($txt1[0]);
                            }

                            //id proposta siconv
                            $empenho['id_proposta_siconv'] = $proposta->id_siconv;

                            if ($empenho['id_empenho_siconv'] != null && $empenho['especie_empenho'] != null && $empenho['tabela_cronograma_empenho'] != null && $empenho['id_proposta_siconv'] != null) {
                                array_push($empenhos, $empenho);
                            }
                        }
                    }
                }

                ## Adicionando os empenhos na tabela empenhos
                if ($empenhos != null && count($empenhos) > 0) {
                    $empenhos = array_map("unserialize", array_unique(array_map("serialize", $empenhos)));

                    foreach ($empenhos as $empenho) {
                        //adicionar empenhos novos ou atualizar os existentes
                        $this->empenhos_model->add_or_update_empenho($empenho);
                    }
                }
            }

            ## Atualizando os dados na tabela proposta
            $banco_proposta['id_siconv'] = $proposta->id_siconv;
            $this->banco_proposta_model->insert_or_update($banco_proposta);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function get_programas_banco_proposta_siconv($id_proposta_banco_propostas) {
        try {
            ini_set('max_execution_time', 0);
            //Inicializando conexao no siconv
            //$this->autentica_siconv->new_init_siconv_do_login("guest", "guest", $this->login, $this->cookie_file_path);
            $this->autentica_siconv->new_init_siconv_do_login("43346880559", "Laisa_M2012", $this->login, $this->cookie_file_path);

            $this->load->model('banco_proposta_model');
            $this->load->model('programa_banco_proposta_model');

            $proposta = $this->banco_proposta_model->get_by_id($id_proposta_banco_propostas);

            // ---- Programas ----- //
            $pagina = "https://www.convenios.gov.br/siconv/ConsultarProposta/ResultadoDaConsultaDePropostaDetalharProposta.do?idProposta={$proposta->id_siconv}&destino=ListarProgramasProposta";
            $documento = $this->autentica_siconv->new_obter_pagina($pagina, $this->login, $this->cookie_file_path);
            $documento = $this->removeSpaceSurplus($documento);

            $programa = array();
            $array_programas = array();

            //get codigo programa
            $txt1 = $this->getTextBetweenTags($documento, '<div class="codigo">', '<\/div>');
            if ($txt1 != null && count($txt1) > 0) {
                for ($indice = 0; $indice < count($txt1); $indice++) {
                    $programa = $this->init_array_programa();
                    $programa['codigo_programa'] = trim($txt1[$indice]);
                    array_push($array_programas, $programa);
                }
            }

            //get nome programa
            $txt1 = $this->getTextBetweenTags($documento, '<div class="nome">', '<\/div>');
            if ($txt1 != null && count($txt1) > 0) {
                if (count($array_programas) > 0) {
                    for ($indice = 0; $indice < count($txt1); $indice++) {
                        $array_programas[$indice]['nome_programa'] = trim($txt1[$indice]);
                    }
                }
            }

            //get id programa
            $txt1 = $this->getTextBetweenTags($documento, '<td> <nobr>', '<\/a><\/nobr> <\/td>');
            if ($txt1 != null && count($txt1) > 0) {
                if (count($array_programas) > 0) {
                    for ($indice = 0; $indice < count($txt1); $indice++) {
                        $temp = explode('id=', trim($txt1[$indice]));
                        $temp = explode('\';', $temp[1]);
                        $array_programas[$indice]['id_programa'] = trim($temp[0]);
                    }
                }
            }

            if ($array_programas != null && count($array_programas) > 0) {
                for ($indice = 0; $indice < count($array_programas); $indice++) {
                    $pagina = "https://www.convenios.gov.br/siconv/ListarProgramasProposta/ProgramasDaPropostaDetalhar.do?id={$array_programas[$indice]['id_programa']}";
                    $documento = $this->autentica_siconv->new_obter_pagina($pagina, $this->login, $this->cookie_file_path);
                    $documento = $this->removeSpaceSurplus($documento);

                    //get regra de contra partida
                    $txt1 = $this->getTextBetweenTags($documento, '<tr class="regraContrapartida" id="tr-voltarRegraContrapartida" > <td class="label">Regra Contrapartida<\/td> <td class="field">', '<\/td> <\/tr>');
                    if ($txt1 != null && count($txt1) > 0) {
                        $array_programas[$indice]['regra_contrapartida'] = trim($txt1[0]);
                    }

                    //get valor global
                    $txt1 = $this->getTextBetweenTags($documento, '<tr class="valorGlobal" id="tr-voltarValorGlobal" > <td class="label">Valor Global do\(s\) Objeto\(s\)<\/td> <td class="field">', '<\/td> <\/tr>');
                    if ($txt1 != null && count($txt1) > 0) {
                        $array_programas[$indice]['valor_global'] = trim($txt1[0]);
                    }

                    //get total de contra partida
                    $txt1 = $this->getTextBetweenTags($documento, '<tr class="valorContrapartida" id="tr-voltarValorContrapartida" > <td class="label">Valor de Contrapartida<\/td> <td class="field">', '<\/td> <\/tr>');
                    if ($txt1 != null && count($txt1) > 0) {
                        $array_programas[$indice]['total_contrapartida'] = trim($txt1[0]);
                    }

                    //get contra partida financeira
                    $txt1 = $this->getTextBetweenTags($documento, '<tr class="valorContrapartidaFinanceira" id="tr-voltarValorContrapartidaFinanceira" > <td class="label">&nbsp;&nbsp;&nbsp;&nbsp;Valor de Contrapartida Financeira<\/td> <td class="field">', '<\/td> <\/tr>');
                    if ($txt1 != null && count($txt1) > 0) {
                        $array_programas[$indice]['contrapartida_financeira'] = trim($txt1[0]);
                    }

                    //get contra partida de bens e serviços
                    $txt1 = $this->getTextBetweenTags($documento, '<tr class="valorContrapartidaBensServicos" id="tr-voltarValorContrapartidaBensServicos" > <td class="label">&nbsp;&nbsp;&nbsp;&nbsp;Valor de Contrapartida em Bens e Serviços<\/td> <td class="field">', '<\/td> <\/tr>');
                    if ($txt1 != null && count($txt1) > 0) {
                        $array_programas[$indice]['contrapartida_bens'] = trim($txt1[0]);
                    }

                    //get repasse
                    $txt1 = $this->getTextBetweenTags($documento, '<tr class="valorRepasse" id="tr-voltarValorRepasse" > <td class="label">Valor de Repasse<\/td> <td class="field">', '<\/td> <\/tr>');
                    if ($txt1 != null && count($txt1) > 0) {
                        $array_programas[$indice]['repasse'] = trim($txt1[0]);
                    }

                    //get ementa
                    $txt1 = $this->getTextBetweenTags($documento, '<tr class="valorRepasse" id="tr-voltarValorRepasse" > <td class="label">', '<\/td> <\/tr>');
                    if ($txt1 != null && count($txt1) > 0) {
                        if (count($txt1) > 1) {
                            $temp = explode('<td class="field">', trim($txt1[1]));
                            if ($temp != null && count($temp > 0)) {
                                $array_programas[$indice]['ementa'] = trim($temp[1]);
                            }
                        } else {
                            $temp = explode('<td class="field">', trim($txt1[0]));
                            if ($temp != null && count($temp > 0)) {
                                $array_programas[$indice]['ementa'] = trim($temp[1]);
                            }
                        }
                    }

                    //get objeto                            
                    $txt1 = $this->getTextBetweenTags($documento, '<div id="listaObjetos" class="table"> <table id="row"> <thead> <tr> <th class="nome">Objetos<\/th><\/tr><\/thead> <tbody id="tbodyrow"> <tr class="odd"> <td> <div class="nome">', '<\/div> <\/td><\/tr><\/tbody><\/table><\/div>');
                    if ($txt1 != null && count($txt1) > 0) {
                        $array_programas[$indice]['objeto'] = trim($txt1[0]);
                    }

                    $array_programas[$indice]['id_proposta_banco_proposta'] = $proposta->id_proposta;
                    $this->programa_banco_proposta_model->insert_or_update($array_programas[$indice]);
                }
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    //Thomas: Atualiza situacao e parecer de uma proposta específica de forma silenciosa
    public function update_status_porposta($id_proposta) {
        ini_set('max_execution_time', 0);
        $this->session->set_userdata('projEnviado', 'S');

        $this->load->model('proposta_model');

        $proposta = $this->proposta_model->get_by_id($id_proposta);
        $this->update_situacao_proposta($proposta->idProposta, $proposta->id_proposta_efetiva);
        $this->get_parecer_proposta($proposta->idProposta, $proposta->id_proposta_efetiva);
    }

    //Thomas: Atualiza parecer e situacao de todas as propostas dos gestores
    public function update_status_propostas() {
        ini_set('max_execution_time', 0);
        $this->load->model('usuariomodel');
        $this->load->model('proposta_model');
        $this->load->model('gestor');
        $this->load->model('banco_proposta_model');

        //Buscando primeiramente os gestores nivel id = 2
        $gestores = $this->usuariomodel->get_all_gestor(true);

        foreach ($gestores as $gestor) {
            if ($gestor->usuario_sistema == "M" || $gestor->usuario_sistema == "E") {
                //carregando os projetos do grupo desse gestor
                $propostas = $this->proposta_model->get_all_ativo_enviadas($gestor->id_usuario);
                $propostas_banco_propostas = array();

                $anos = array();
                array_push($anos, '2014');
                array_push($anos, '2015');

                $proponentes = $this->usuariomodel->get_proponentes_by_gestor($gestor->id_usuario);

                if ($proponentes != null) {
                    foreach ($proponentes as $proponente) {
                        $temp_array = $this->banco_proposta_model->get_by_proponente_anos($proponente->cnpj, $anos);
                        if ($temp_array != null) {
                            $propostas_banco_propostas = array_merge($propostas_banco_propostas, $temp_array);
                        }
                    }
                }

                $propostas_atualizadas = array();
                $propostas_aprovadas = array();
                $propostas_banco_proposta_atualizadas = array();
                $propostas_banco_proposta_aprovadas = array();

                //propostas cadastradas no esicar
                if ($propostas != null && count($propostas) > 0) {
                    $situacao_nova = null;
                    $parecer_novo = null;
                    $this->load->model('parecer_proposta_model');
                    foreach ($propostas as $proposta) {
                        $situacao_atual = $proposta->situacao;
                        $parecer_atual = $this->parecer_proposta_model->get_data_ultimo_parecer($proposta->id_proposta_efetiva);

                        $situacao_nova = $this->update_situacao_proposta($proposta->idProposta, $proposta->id_proposta_efetiva);
                        $parecer_novo = $this->get_parecer_proposta($proposta->idProposta, $proposta->id_proposta_efetiva);
                        if ($parecer_novo != null || $situacao_nova != null) {
                            //nova situacao
                            if ($situacao_nova != $situacao_atual) {
                                array_push($propostas_atualizadas, array($proposta->nome, $proposta->id_siconv, $proposta->id_proposta_efetiva, $situacao_nova));
                            }

                            //adiciounou novo parecer
                            if ($parecer_novo != $parecer_atual) {
                                array_push($propostas_atualizadas, array($proposta->nome, $proposta->id_siconv, $proposta->id_proposta_efetiva, 'Novo parecer para proposta !!'));
                            }

                            //nova situacao e aprovada
                            if ($situacao_nova != $situacao_atual && strpos("Aprovado", $situacao_nova) != false) {
                                array_push($propostas_aprovadas, array($proposta->nome, $proposta->id_siconv, $proposta->id_proposta_efetiva, $situacao_nova));
                                //$this->proposta_model->check_proposta_aprovada($proposta->idProposta, $gestor->id_usuario, $situacao_nova);
                            }
                        }
                    }
                }

                //propostas cadastradas fora do esicar - banco propostas - propostas e pareceres
                if ($propostas_banco_propostas != null && count($propostas_banco_propostas) > 0) {
                    $situacao_nova = null;
                    $parecer_novo = null;
                    $this->load->model('parecer_banco_proposta_model');
                    foreach ($propostas_banco_propostas as $proposta_banco_propostas) {
                        $situacao_atual = $proposta_banco_propostas->situacao;
                        $parecer_atual = $this->parecer_banco_proposta_model->get_data_ultimo_parecer($proposta_banco_propostas->id_siconv);

                        //TODO CRIAR FUNCOES PARA ATUALIZAR DO BANCO PROPOSTA
                        $situacao_nova = $this->update_situacao_proposta_banco_proposta($proposta_banco_propostas->id_proposta, $proposta_banco_propostas->id_siconv);
                        $parecer_novo = $this->get_aprecer_proposta_banco_proposta($proposta_banco_propostas->id_proposta, $proposta_banco_propostas->id_siconv);
                        if ($parecer_novo != null || $situacao_nova != null) {
                            //Nova Situacao
                            if ($situacao_nova != $situacao_atual) {
                                array_push($propostas_banco_proposta_atualizadas, array($proposta_banco_propostas->objeto, $proposta_banco_propostas->codigo_siconv, $proposta_banco_propostas->id_siconv, $situacao_nova));
                            }

                            //Adicionado novo parecer
                            if ($parecer_novo != $parecer_atual) {
                                array_push($propostas_banco_proposta_atualizadas, array($proposta_banco_propostas->objeto, $proposta_banco_propostas->codigo_siconv, $proposta_banco_propostas->id_siconv, 'Novo parecer para proposta !!'));
                            }

                            //Situacao alterou e foi aprovada
                            if ($situacao_nova != $situacao_atual && strpos("Aprovado", $situacao_nova) != false) {
                                array_push($propostas_banco_proposta_aprovadas, array($proposta_banco_propostas->objeto, $proposta_banco_propostas->codigo_siconv, $proposta_banco_propostas->id_siconv, $situacao_nova));
                                //$this->proposta_model->check_proposta_banco_proposta_aprovada($proposta_banco_propostas->id_proposta, $gestor->id_usuario, $situacao_nova);
                            }
                        }
                    }
                }
            } else {
                $dados_gestor = $this->gestor->get_by_usuario_only_gestor($gestor->id_usuario);
                $propostas = $this->proposta_model->get_all_propostas_emenda_parlamentar($dados_gestor->numero_parlamentar);

                $propostas_alterou_situacao = array();
                if ($propostas != null) {
                    if ($propostas != null && count($propostas) > 0) {
                        $situacao_nova = null;
                        foreach ($propostas as $proposta) {
                            $situacao_atual = $proposta->situacao;
                            $situacao_nova = $this->update_situacao_proposta($proposta->id_proposta, $proposta->id_siconv);
                            if ($situacao_atual != null && $situacao_nova != null && ($situacao_nova != $situacao_atual)) {
                                array_push($propostas_alterou_situacao, $proposta);
                            }
                        }
                    }
                }
            }

            if ($gestor->usuario_sistema == "M" || $gestor->usuario_sistema == "E") {
                //Envio de sms para as propsotas aprovadas
                if (count($propostas_aprovadas) > 0) {
                    if ($gestor->celular != null) {
                        if (strlen($gestor->celular) == 10 || strlen($gestor->celular) == 11) {
                            $mensagem = "Parabéns!! Novas propostas aprovadas: ";
                            foreach ($propostas_aprovadas as $aprovada) {
                                $mensagem .= ", " . $aprovada[1];
                            }

                            //Enviando para o gestor
                            $this->envia_sms_aprovada($mensagem, $gestor->celular);
                        }
                    }
                }

                //Envio de sms para propostas banco proposta aprovadas
                if (count($propostas_banco_proposta_aprovadas) > 0) {
                    if ($gestor->celular != null) {
                        if (strlen($gestor->celular) == 10 || strlen($gestor->celular) == 11) {
                            $mensagem = "Parabéns!! Novas propostas aprovadas: ";
                            foreach ($propostas_banco_proposta_aprovadas as $aprovadas_banco_proposta) {
                                $mensagem .= ", " . $aprovadas_banco_proposta[1];
                            }

                            //Enviando para o gestor
                            $this->envia_sms_aprovada($mensagem, $gestor->celular);
                        }
                    }
                }

                //propostas meramente atualizadas
                if (count($propostas_atualizadas) > 0) {
                    $origem = "no-reply@physisbrasil.com.br";
                    $assunto = "Atualizações de Informações de Propostas";
                    $texto = "";

                    foreach ($propostas_atualizadas as $nome_propostas) {
                        $texto .= "<b>Proposta:</b> <a href='https://www.convenios.gov.br/siconv/ConsultarProposta/ResultadoDaConsultaDePropostaDetalharProposta.do?idProposta={$nome_propostas[2]}&path=/MostraPrincipalConsultarPrograma.do&Usr=guest&Pwd=guest&destino=DetalharParecerProposta' target='_blank'>{$nome_propostas[1]}</a> - '{$nome_propostas[0]}', Situação: {$nome_propostas[3]}<br/>";
                    }

                    //$urlCabecalho = base_url() . "layout/assets/images/cabecalho_email_parecer.png";
                    //$urlRodape = base_url() . "layout/assets/images/rodape.png";

                    $urlCabecalho = "esicar.physisbrasil.com.br/esicar/layout/assets/images/cabecalho_email_parecer.png";
                    $urlRodape = "esicar.physisbrasil.com.br/esicar/layout/assets/images/rodape.png";

                    $mensagem = "<html>
			                <div align='center' style='background-color: #f6f6f6; height: 700px;'>
			                <p style='color: #f6f6f6;'>&nbsp;</p>
			                <div align='center' style='background-color: #6287c4; width: 520px; color:#fff;'>
			                <img src='{$urlCabecalho}' style='width: 520px; height: 255px;'/>
			                <div align='center' style='font-family: calibri; margin-left: 20px;'>
			                 
			                <div align='left'  style='width: 500px;'>
			                <div style='margin-left: 20px; padding-right: 1px; font-size: 17px; width: 460px;'>
			                
			                <p><h3>Relação de propostas com nova situação/novo parecer técnico.</h3></p>
			                <p style='color:red;'>
			                Click no link para abrir
			                </p>
			               
			                <p>{$texto}</p>
			                 
			                </div>
			                
			                </div>
			                 
			                </div>
			                <img src='{$urlRodape}' style='width: 520px; height: 70px;'/>
			                </div>
			                
			                </div>
			                </html>";

                    $this->envia_email($origem, $gestor->email, null, $assunto, $mensagem);

                    if ($gestor->id_nivel == 2) {
                        $this->envia_email($origem, "eliumar@physisbrasil.com.br", "max@physisbrasil.com.br", $assunto, $mensagem);

                        $this->load->model('encarregado_model');
                        $encarregados = $this->encarregado_model->get_by_gestor($this->gestor->get_all_by_usuario($gestor->id_usuario)->id_gestor);
                        foreach ($encarregados as $encarregado) {
                            $this->envia_email($origem, $encarregado->email, null, $assunto, $mensagem);
                        }
                    }
                }

                //propostas atualizadas banco propostas
                if (count($propostas_banco_proposta_atualizadas) > 0) {
                    $origem = "no-reply@physisbrasil.com.br";
                    $assunto = "Atualizações de Informações de Propostas";
                    $texto = "";

                    foreach ($propostas_banco_proposta_atualizadas as $nome_propostas) {
                        $texto .= "<b>Proposta:</b> <a href='https://www.convenios.gov.br/siconv/ConsultarProposta/ResultadoDaConsultaDePropostaDetalharProposta.do?idProposta={$nome_propostas[2]}&path=/MostraPrincipalConsultarPrograma.do&Usr=guest&Pwd=guest&destino=DetalharParecerProposta' target='_blank'>{$nome_propostas[1]}</a> - '{$nome_propostas[0]}', Situação: {$nome_propostas[3]}<br/>";
                    }

                    //$urlCabecalho = base_url() . "layout/assets/images/cabecalho_email_parecer.png";
                    //$urlRodape = base_url() . "layout/assets/images/rodape.png";

                    $urlCabecalho = "esicar.physisbrasil.com.br/esicar/layout/assets/images/cabecalho_email_parecer.png";
                    $urlRodape = "esicar.physisbrasil.com.br/esicar/layout/assets/images/rodape.png";

                    $mensagem = "<html>
			                <div align='center' style='background-color: #f6f6f6; height: 700px;'>
			                <p style='color: #f6f6f6;'>&nbsp;</p>
			                <div align='center' style='background-color: #6287c4; width: 520px; color:#fff;'>
			                <img src='{$urlCabecalho}' style='width: 520px; height: 255px;'/>
			                <div align='center' style='font-family: calibri; margin-left: 20px;'>
			                 
			                <div align='left'  style='width: 500px;'>
			                <div style='margin-left: 20px; padding-right: 1px; font-size: 17px; width: 460px;'>
			                
			                <p><h3>Relação de propostas com nova situação/novo parecer técnico.</h3></p>
			                <p style='color:red;'>
			                Click no link para abrir
			                </p>
			               
			                <p>{$texto}</p>
			                 
			                </div>
			                
			                </div>
			                 
			                </div>
			                <img src='{$urlRodape}' style='width: 520px; height: 70px;'/>
			                </div>
			                
			                </div>
			                </html>";

                    $this->envia_email($origem, $gestor->email, null, $assunto, $mensagem);

                    if ($gestor->id_nivel == 2) {
                        $this->envia_email($origem, "eliumar@physisbrasil.com.br", "max@physisbrasil.com.br", $assunto, $mensagem);

                        $this->load->model('encarregado_model');
                        $encarregados = $this->encarregado_model->get_by_gestor($this->gestor->get_all_by_usuario($gestor->id_usuario)->id_gestor);
                        foreach ($encarregados as $encarregado) {
                            $this->envia_email($origem, $encarregado->email, null, $assunto, $mensagem);
                        }
                    }
                }
            } else {
                if (count($propostas_alterou_situacao) > 0) {
                    $origem = "no-reply@physisbrasil.com.br";
                    $assunto = "Atualizações de Informações de Propostas de Emenda Parlamentar";
                    $texto = "";

                    foreach ($propostas_alterou_situacao as $nome_propostas) {
                        $texto .= "<b>Proposta:</b> <a href='https://www.convenios.gov.br/siconv/ConsultarProposta/ResultadoDaConsultaDePropostaDetalharProposta.do?idProposta={$nome_propostas->id_siconv}&path=/MostraPrincipalConsultarPrograma.do&Usr=guest&Pwd=guest&destino=DetalharParecerProposta' target='_blank'>{$nome_propostas->codigo_siconv}</a> - '{$nome_propostas->codigo_siconv}', Situação: {$nome_propostas->situacao}<br/>";
                    }

                    $urlCabecalho = "esicar.physisbrasil.com.br/esicar/layout/assets/images/cabecalho_email_parecer.png";
                    $urlRodape = "esicar.physisbrasil.com.br/esicar/layout/assets/images/rodape.png";

                    $mensagem = "<html>
			                <div align='center' style='background-color: #f6f6f6; height: 700px;'>
			                <p style='color: #f6f6f6;'>&nbsp;</p>
			                <div align='center' style='background-color: #6287c4; width: 520px; color:#fff;'>
			                <img src='{$urlCabecalho}' style='width: 520px; height: 255px;'/>
			                <div align='center' style='font-family: calibri; margin-left: 20px;'>
			                 
			                <div align='left'  style='width: 500px;'>
			                <div style='margin-left: 20px; padding-right: 1px; font-size: 17px; width: 460px;'>
			                
			                <p><h3>Relação de propostas com nova situação.</h3></p>
			                <p style='color:red;'>
			                Click no link para abrir
			                </p>
			               
			                <p>{$texto}</p>
			                 
			                </div>
			                
			                </div>
			                 
			                </div>
			                <img src='{$urlRodape}' style='width: 520px; height: 70px;'/>
			                </div>
			                
			                </div>
			                </html>";

                    $this->envia_email($origem, $gestor->email, null, $assunto, $mensagem);

                    if ($gestor->id_nivel == 2) {
                        $this->envia_email($origem, "eliumar@physisbrasil.com.br", "max@physisbrasil.com.br", $assunto, $mensagem);

                        $this->load->model('encarregado_model');
                        $encarregados = $this->encarregado_model->get_by_gestor($this->gestor->get_all_by_usuario($gestor->id_usuario)->id_gestor);
                        foreach ($encarregados as $encarregado) {
                            $this->envia_email($origem, $encarregado->email, null, $assunto, $mensagem);
                        }
                    }
                }
            }
        }
    }

    public function envia_sms_aprovada($mensagem, $telefone) {

        $telefone .= "55" . $telefone;
        //  		Max				Eliumar			Daiana			Allan
        $credencial = URLEncode("218565391A8CE4A44253ABF179EBC1505B7A0A3F"); //**Credencial da Conta 40 caracteres
        $principal = URLEncode("ESICAR");  //* SEU CODIGO PARA CONTROLE, não colocar e-mail
        $auxuser = URLEncode("USER_ATIVACAO"); //* SEU CODIGO PARA CONTROLE, não colocar e-mail
        $mobile = URLEncode($telefone); //* Numero do telefone  FORMATO: PAÍS+DDD(DOIS DÍGITOS)+NÚMERO
        $sendproj = URLEncode("N"); //* S = Envia o Remetente do SMS antes da mensagem , N = Não envia o Remetente do SMS
        $msg = mb_convert_encoding($mensagem, "UTF-8"); // Converte a mensagem para não ocorrer erros com caracteres semi-gráficos
        $msg = URLEncode($msg);
        $response = fopen("http://www.mpgateway.com/v_2_00/smspush/enviasms.aspx?CREDENCIAL=" . $credencial . "&PRINCIPAL_USER=" . $principal . "&AUX_USER=" . $auxuser . "&MOBILE=" . $mobile . "&SEND_PROJECT=" . $sendproj . "&MESSAGE=" . $msg, "r");
        $status_code = fgets($response, 4);
        echo "Codigo retornando do fopen=";
        echo $status_code;
    }

    public function update_empenho_prosposta_esicar($id_proposta, $id_siconv) {
        ini_set('max_execution_time', 0);
        $this->load->model('proposta_model');

        $proposta = array(
            'empenhado' => null
        );

        //$this->new_init_siconv_do_login("guest", "guest");
        $this->autentica_siconv->new_init_siconv_do_login("43346880559", "Laisa_M2012", $this->login, $this->cookie_file_path);
        ## Consultando empenhado
        // ---- Dados da proposta ---- //                
        $pagina = "https://www.convenios.gov.br/siconv/ConsultarProposta/ResultadoDaConsultaDePropostaDetalharProposta.do?idProposta={$id_siconv}";
        $documento = $this->autentica_siconv->new_obter_pagina($pagina, $this->login, $this->cookie_file_path);
        $documento = $this->removeSpaceSurplus($documento);

        //empenhado
        $txt1 = $this->getTextBetweenTags($documento, '<tr><td class="label" width="14%">Empenhado<\/td> <td class="field" width="5%">', '<\/td>');
        if ($txt1 != null && count($txt1) > 0) {
            $proposta['empenhado'] = trim($txt1[0]);
        }

        // --- Empenhos --- //
        if ($banco_proposta['empenhado'] != null && $banco_proposta['empenhado'] == 'sim') {
            $pagina = "https://www.convenios.gov.br/siconv/ConsultarProposta/ResultadoDaConsultaDePropostaDetalharProposta.do?idProposta={$id_siconv}&destino=ListarEmpenhos";
            $documento = $this->autentica_siconv->new_obter_pagina($pagina, $this->login, $this->cookie_file_path);
            $documento = $this->removeSpaceSurplus($documento);

            //tabela empenhos
            $txt1 = $this->getTextBetweenTags($documento, '<tbody id="tbodyrow"> <tr class="odd">', '<\/tr> <\/tbody>');
            if ($txt1 != null && count($txt1) > 0) {
                $tabela_empenhos = trim($txt1[0]);
            }

            //Array com todos os empenhos da proposta como sempre forcando a limpeza com o null
            $empenhos = null;
            $empenhos = array();

            $rows = $this->getTextBetweenTags($tabela_empenhos, '<td>', '<\/td>');
            if ($rows != null && count($rows) > 0) {
                foreach ($rows as $row) {
                    $txt_link = $this->getTextBetweenTags(trim($row), '<a href=', '>');
                    if ($txt_link != null && count($txt_link) > 0) {

                        //array com os empenhos indivuais para adicionar no array com todos os empenhos
                        $empenho = null;
                        $empenho = array(
                            'id_proposta_siconv' => null,
                            'id_empenho_siconv' => null,
                            'especie_empenho' => null,
                            'tabela_cronograma_empenho' => null
                        );

                        //pegando o codigo e carregando o link para acesso as demais dados
                        $link_empenho_detalhado = explode(' ', $txt_link[0]);
                        $link_empenho_detalhado = $link_empenho_detalhado[0];

                        $link_empenho_detalhado = str_replace('"', '', $link_empenho_detalhado);
                        $pagina = "https://www.convenios.gov.br{$link_empenho_detalhado}";
                        $documento = $this->autentica_siconv->new_obter_pagina($pagina, $this->login, $this->cookie_file_path);
                        $documento = $this->removeSpaceSurplus($documento);

                        //get id empenho
                        $txt1 = $this->getTextBetweenTags($documento, '<input type="hidden" name="id" value="', '" id="anularId">');
                        if ($txt1 != null && count($txt1) > 0) {
                            $empenho['id_empenho_siconv'] = trim($txt1[0]);
                        } else {
                            $txt1 = $this->getTextBetweenTags($documento, '<input type="hidden" name="id" value="', '" id="cancelarId">');
                            if ($txt1 != null && count($txt1) > 0) {
                                $empenho['id_empenho_siconv'] = trim($txt1[0]);
                            }
                        }

                        //get especie_empenho
                        $txt1 = $this->getTextBetweenTags($documento, '<td class="label">Espécie de Empenho<\/td> <td class="field">', '<\/td>');
                        if ($txt1 != null && count($txt1) > 0) {
                            $empenho['especie_empenho'] = trim($txt1[0]);
                        }

                        //get tabela cronograma empenho
                        $txt1 = $this->getTextBetweenTags($documento, '<div id="cronogramas" class="table"> <table id="row">', '<\/table><\/div>');
                        if ($txt1 != null && count($txt1) > 0) {
                            $empenho['tabela_cronograma_empenho'] = trim($txt1[0]);
                        }

                        //id proposta siconv
                        $empenho['id_proposta_siconv'] = $id_siconv;

                        if ($empenho['id_empenho_siconv'] != null && $empenho['especie_empenho'] != null && $empenho['tabela_cronograma_empenho'] != null && $empenho['id_proposta_siconv'] != null) {
                            array_push($empenhos, $empenho);
                        }
                    }
                }
            }

            ## Adicionando os empenhos na tabela empenhos
            if ($empenhos != null && count($empenhos) > 0) {
                $empenhos = array_map("unserialize", array_unique(array_map("serialize", $empenhos)));

                foreach ($empenhos as $empenho) {
                    //adicionar empenhos novos ou atualizar os existentes
                    $this->empenhos_esicar_model->add_or_update_empenho($empenho);
                }
            }

            $this->proposta_model->atualiza_proposta($id_proposta, $proposta);
        }
    }

    //Atualiza a situacao da proposta sem utilizar o simple_html_dom
    public function update_situacao_proposta($id, $id_proposta_siconv) {
        ini_set('max_execution_time', 0);
        $this->load->model('proposta_model');

        //$this->autentica_siconv->new_init_siconv_do_login("guest", "guest", $this->login, $this->cookie_file_path);
        $this->autentica_siconv->new_init_siconv_do_login("43346880559", "Laisa_M2012", $this->login, $this->cookie_file_path);

        $pagina = "https://www.convenios.gov.br/siconv/ConsultarProposta/ResultadoDaConsultaDePropostaDetalharProposta.do?idProposta={$id_proposta_siconv}";
        $documento = $this->autentica_siconv->new_obter_pagina($pagina, $this->login, $this->cookie_file_path);
        $documento = $this->removeSpaceSurplus($documento);

        $txt1 = $this->getTextBetweenTags($documento, '<tr class="status" id="tr-alterarStatus">', '<\/tr>');
        if ($txt1 != null && count($txt1) > 0) {
            $txt1 = $this->getTextBetweenTags($txt1[0], '<td class="field" width="40%">', '<\/td>');
            if ($txt1 != null && count($txt1) > 0) {
                $result = array(
                    'situacao' => $txt1[0]
                );
                $this->proposta_model->atualiza_proposta($id, $result);
                return $result['situacao'];
            } else {
                $txt1 = $this->getTextBetweenTags($documento, '<tr class="status" id="tr-alterarStatus"> <td class="label">Situação<\/td> <td colspan="4"> <table cellpadding="0" cellspacing="0"> <td class="field" colspan="4"> <div style="float:left">', '<\/div>');
                if ($txt1 != null && count($txt1) > 0) {
                    $result = array(
                        'situacao' => $txt1[0]
                    );
                    $this->proposta_model->atualiza_proposta($id, $result);
                    return $result['situacao'];
                }
            }
        }

        return null;
    }

    //Atualiza a situacao da proposta sem utilizar o simple_html_dom
    public function update_situacao_proposta_banco_proposta($id, $id_proposta_siconv) {
        ini_set('max_execution_time', 0);
        $this->load->model('banco_proposta_model');

        //$this->autentica_siconv->new_init_siconv_do_login("guest", "guest", $this->login, $this->cookie_file_path);
        $this->autentica_siconv->new_init_siconv_do_login("43346880559", "Laisa_M2012", $this->login, $this->cookie_file_path);

        $pagina = "https://www.convenios.gov.br/siconv/ConsultarProposta/ResultadoDaConsultaDePropostaDetalharProposta.do?idProposta={$id_proposta_siconv}";
        $documento = $this->autentica_siconv->new_obter_pagina($pagina, $this->login, $this->cookie_file_path);
        $documento = $this->removeSpaceSurplus($documento);

        $txt1 = $this->getTextBetweenTags($documento, '<tr class="status" id="tr-alterarStatus">', '<\/tr>');
        if ($txt1 != null && count($txt1) > 0) {
            $txt1 = $this->getTextBetweenTags($txt1[0], '<td class="field" width="40%">', '<\/td>');
            if ($txt1 != null && count($txt1) > 0) {
                $result = array(
                    'situacao' => $txt1[0]
                );
                $this->banco_proposta_model->atualiza_proposta($id, $result);
                return $result['situacao'];
            } else {
                $txt1 = $this->getTextBetweenTags($documento, '<tr class="status" id="tr-alterarStatus"> <td class="label">Situação<\/td> <td colspan="4"> <table cellpadding="0" cellspacing="0"> <td class="field" colspan="4"> <div style="float:left">', '<\/div>');
                if ($txt1 != null && count($txt1) > 0) {
                    $result = array(
                        'situacao' => $txt1[0]
                    );
                    $this->banco_proposta_model->atualiza_proposta($id, $result);
                    return $result['situacao'];
                }
            }
        }

        return null;
    }

    public function get_parecer_proposta($id, $id_proposta_siconv) {
        ini_set('max_execution_time', 0);
        //$this->autentica_siconv->new_init_siconv_do_login("guest", "guest", $this->login, $this->cookie_file_path);
        $this->autentica_siconv->new_init_siconv_do_login("43346880559", "Laisa_M2012", $this->login, $this->cookie_file_path);

        $pagina = "https://www.convenios.gov.br/siconv/ConsultarProposta/ResultadoDaConsultaDePropostaDetalharProposta.do?idProposta={$id_proposta_siconv}&destino=DetalharParecerProposta";
        $documento = $this->autentica_siconv->new_obter_pagina($pagina, $this->login, $this->cookie_file_path);
        $documento = $this->removeSpaceSurplus($documento);

        $txt1 = $this->getTextBetweenTags($documento, '<div id="listaParecerProposta" class="table">', '<\/div><div id="tableFooter">');

        if (count($txt1) > 0 && $txt1 != null) {
            $tabela = str_replace('id="row"', 'class="table"', $txt1[0]);
            $tabelaComLinks = str_replace("javascript:document.location='", "javascript:document.location='https://www.convenios.gov.br", $tabela);
            $tabelaFinal = str_replace("';", "&path=/MostraPrincipalConsultarPrograma.do&Usr=guest&Pwd=guest';", $tabelaComLinks);

            $datas = $this->getTextBetweenTags($tabelaFinal, '<div class="data">', "<\/div>");
            $botoes = $this->getTextBetweenTags($tabelaFinal, "<nobr>", "<\/nobr>");

            $this->load->model('parecer_proposta_model');
            $this->load->model('proposta_model');

            foreach ($botoes as $i => $botao) {
                $link = $this->getTextBetweenTags($botao, "<a href=\"javascript:document.location='", "';\" class=\"buttonLink\">");

                $paginaParecer = $this->autentica_siconv->new_obter_pagina($link[0], $this->login, $this->cookie_file_path);
                $paginaParecer = $this->removeSpaceSurplus($paginaParecer);

                $tem_anexo_parecer = $this->getTextBetweenTags($paginaParecer, "<div id=\"listaAnexos\" class=\"table\">", "<\/div>");
                $temAnexo = trim($tem_anexo_parecer[0]) != "Nenhum registro foi encontrado." ? true : false;

                $tabelaParecer = $this->getTextBetweenTags($paginaParecer, '<tbody>', '<\/tbody>');
                $parecer = substr($tabelaParecer[0], strpos($tabelaParecer[0], ' id="voltarParecer">') + 20);
                $parecer = substr($parecer, 0, strpos($parecer, '</textarea>'));

                $dataParecer = $datas[$i];
                $idProposta = $this->getTextBetweenTags($botao, "idProposta=", "&idParecer");
                $idParecer = $this->getTextBetweenTags($botao, "idParecer=", "&path");

                $options = array(
                    'data_parecer' => implode("-", array_reverse(explode("/", $dataParecer))),
                    'id_proposta' => $id_proposta_siconv,
                    'id_parecer' => $idParecer[0],
                    'parecer' => $parecer,
                    'tem_anexo' => $temAnexo
                );

                $this->parecer_proposta_model->atualiza_parecer($options);
            }

            $this->proposta_model->atualiza_parecer_proposta($id, array('parecer_proposta' => $tabelaFinal));

            return $this->parecer_proposta_model->get_data_ultimo_parecer($id_proposta_siconv);
        } else {
            return null;
        }
    }

    public function get_aprecer_proposta_banco_proposta($id, $id_proposta_siconv) {
        ini_set('max_execution_time', 0);

        $this->autentica_siconv->new_init_siconv_do_login("43346880559", "Laisa_M2012", $this->login, $this->cookie_file_path);

        $pagina = "https://www.convenios.gov.br/siconv/ConsultarProposta/ResultadoDaConsultaDePropostaDetalharProposta.do?idProposta={$id_proposta_siconv}&destino=DetalharParecerProposta";
        $documento = $this->autentica_siconv->new_obter_pagina($pagina, $this->login, $this->cookie_file_path);
        $documento = $this->removeSpaceSurplus($documento);

        $txt1 = $this->getTextBetweenTags($documento, '<div id="listaParecerProposta" class="table">', '<\/div><div id="tableFooter">');

        if (count($txt1) > 0 && $txt1 != null) {
            $tabela = str_replace('id="row"', 'class="table"', $txt1[0]);
            $tabelaComLinks = str_replace("javascript:document.location='", "javascript:document.location='https://www.convenios.gov.br", $tabela);
            $tabelaFinal = str_replace("';", "&path=/MostraPrincipalConsultarPrograma.do&Usr=guest&Pwd=guest';", $tabelaComLinks);

            $datas = $this->getTextBetweenTags($tabelaFinal, '<div class="data">', "<\/div>");
            $botoes = $this->getTextBetweenTags($tabelaFinal, "<nobr>", "<\/nobr>");

            $this->load->model('parecer_banco_proposta_model');
            $this->load->model('banco_proposta_model');

            foreach ($botoes as $i => $botao) {
                $link = $this->getTextBetweenTags($botao, "<a href=\"javascript:document.location='", "';\" class=\"buttonLink\">");

                $paginaParecer = $this->autentica_siconv->new_obter_pagina($link[0], $this->login, $this->cookie_file_path);
                $paginaParecer = $this->removeSpaceSurplus($paginaParecer);

                $tem_anexo_parecer = $this->getTextBetweenTags($paginaParecer, "<div id=\"listaAnexos\" class=\"table\">", "<\/div>");
                $temAnexo = trim($tem_anexo_parecer[0]) != "Nenhum registro foi encontrado." ? true : false;

                $tabelaParecer = $this->getTextBetweenTags($paginaParecer, '<tbody>', '<\/tbody>');
                $parecer = substr($tabelaParecer[0], strpos($tabelaParecer[0], ' id="voltarParecer">') + 20);
                $parecer = substr($parecer, 0, strpos($parecer, '</textarea>'));

                $dataParecer = $datas[$i];
                $idProposta = $this->getTextBetweenTags($botao, "idProposta=", "&idParecer");
                $idParecer = $this->getTextBetweenTags($botao, "idParecer=", "&path");

                $options = array(
                    'data_parecer' => implode("-", array_reverse(explode("/", $dataParecer))),
                    'id_proposta' => $id_proposta_siconv,
                    'id_parecer' => $idParecer[0],
                    'parecer' => $parecer,
                    'tem_anexo' => $temAnexo
                );

                $this->parecer_banco_proposta_model->atualiza_parecer($options);
            }

            return $this->parecer_banco_proposta_model->get_data_ultimo_parecer($id_proposta_siconv);
        } else {
            return null;
        }
    }

    public function check_validade_usuarios() {
        ini_set('max_execution_time', 0);

        try {
            $this->load->model('usuariomodel');

            $this->usuariomodel->check_validade_gestores();
        } catch (Exception $e) {
            echo 'Falha ao validar a validade dos usuarios';
        }
    }

    public function get_propostas_by_usuario() {
        ini_set('max_execution_time', 0);
        //echo date("d/m/Y H:i:s");

        $this->load->model('parecer_banco_proposta_model');
        $this->load->model('usuario_cnpj');

        if ($this->input->get('cnpj', TRUE) != '') {
            //$this->load->model('usuariomodel');
            //$usuario = $this->usuariomodel->get_by_id($this->input->get('id', TRUE));
            //$this->login_usuario = "43346880559";
            //$this->senha = "Laisa_M2012";
            //$this->autentica_siconv->new_init_siconv_do_login("guest", "guest", $this->login, $this->cookie_file_path);
            //$this->autentica_siconv->new_init_siconv_do_login("43346880559", "Laisa_M2012", $this->login, $this->cookie_file_path);

            $users_siconv = $this->usuario_cnpj->get_login_siconv($this->input->get('cnpj', TRUE));
            if ($users_siconv != null && count($users_siconv) > 0) {

                foreach ($users_siconv as $user_siconv) {
                    $retorno = $this->autentica_siconv->new_init_siconv_do_login(utf8_decode($user_siconv->login_siconv), utf8_decode(base64_decode($user_siconv->senha_siconv)), $this->login, $this->cookie_file_path, false);
                    //$retorno = $this->autentica_siconv->new_init_siconv_do_login("43346880559", "Laisa_M2012", $this->login, $this->cookie_file_path, false);
                    if ($retorno == null) {
                        break;
                    } else {
                        $this->cookie_file_path = tempnam("/tmp", "CURLCOOKIE" . rand());
                        $this->login = null;
                    }
                }

                ///ForwardAction.do?modulo=Principal&path=/MostraPrincipalConsultarProposta.do
                //$this->new_obter_paginaLogin($this->login_usuario, $this->senha);

                $pagina = "https://www.convenios.gov.br/siconv/ForwardAction.do?modulo=Principal&path=/MostraPrincipalConsultarProposta.do";
                echo utf8_decode($this->autentica_siconv->new_obter_pagina($pagina, $this->login, $this->cookie_file_path));

                $fields = array(
                    'invalidatePageControlCounter' => 1,
                    'destino' => '',
                    'numeroProposta' => '',
                    'orgaoProposta' => '',
                    'nomeOrgao' => '',
                    'situacaoProposta' => 'VAZIO',
                    'modalidade' => '',
                    'ano' => $this->input->get('ano', TRUE),
                    'enviadaInstituicaoMandataria' => '13',
                    'codigoPrograma' => '',
                    'codigoParlamentar' => '',
                    'numeroEmendaParlamentar' => '',
                    'nomeProponente' => '',
                    'tipoIdentificacao' => '5',
                    'identificacao' => $this->input->get('cnpj', TRUE),
                    'uf' => '',
                    'municipioProponente' => '',
                    'situacaoProponente' => '0',
                    'cpfResponsavel' => '',
                    'naturezaJuridica' => '',
                    'dataInicioEnvioPropostaAnalise' => '',
                    'dataFimEnvioPropostaAnalise' => '',
                    'camposParaExibirAsArray' => ''
                );

                $fields_string = null;
                foreach ($fields as $key => $value) {
                    $fields_string .= $key . '=' . $value . '&';
                }

                $pagina = "https://www.convenios.gov.br/siconv/ConsultarProposta/PreenchaOsDadosDaConsultaConsultar.do";
                echo $pagina . "?" . $fields_string;
                $documento = utf8_decode($this->obter_pagina_post($pagina, $fields, $fields_string));
                echo $documento;
                $documento = $this->removeSpaceSurplus($documento);

                $pages = $this->getTextBetweenTags($documento, "<span class=\"pagelinks\">", "<\/span>");
                $pages = explode("de", $pages[0]);
                $numPages = explode("(", $pages[1]);

                $numRegs = explode("item", $numPages[1]);

                $numRegs = trim($numRegs[0]);
                $numPages = trim($numPages[0]);

                echo $numPages . " - " . $numRegs;

                $listaIDS = array();
                for ($i = 1; $i <= $numPages; $i++) {
                    $tabela = $this->getTextBetweenTags($documento, "<tbody id=\"tbodyrow\">", "<\/tbody>");
                    $links = $this->getTextBetweenTags($tabela[0], "<div class=\"numeroProposta\">", "<\/a> <\/div>");
                    //var_dump($links);
                    foreach ($links as $link) {
                        $parteLink = explode("idProposta=", $link);
                        $parteLink = explode("&", $parteLink[1]);
                        $id = $parteLink[0];
                        echo $id . "<br>";
                        $listaIDS[] = $id;
                    }

                    $pagina = "https://www.convenios.gov.br/siconv/ConsultarProposta/PreenchaOsDadosDaConsultaConsultar.do?d-16544-t=listaResultado&d-16544-p=2&d-16544-g=" . ($i + 1) . "&tipo_consulta=CONSULTA_COMPLETA";
                    $documento = utf8_decode($this->autentica_siconv->new_obter_pagina($pagina, $this->login, $this->cookie_file_path));
                    echo $documento;
                    $documento = $this->removeSpaceSurplus($documento);
                }

                $listaIDS = array_chunk($listaIDS, 5);
//            var_dump($listaIDS);
                foreach ($listaIDS as $ids) {
                    foreach ($ids as $id)
                        $this->get_propostas_siconv($id, $id);
                }

                $this->db->select('id_proposta, id_siconv, codigo_siconv');
                $this->db->where('ano', $this->input->get('ano', TRUE));
                $this->db->where('proponente', $this->input->get('cnpj', TRUE));

                $ids = $this->db->get('banco_proposta')->result();
                $ids = array_chunk($ids, 5);
//            var_dump($ids);

                $lista_proposta_envia_email = array();

                foreach ($ids as $id) {
                    foreach ($id as $i) {
                        $parecer_novo = $this->parecer_banco_proposta_model->get_data_ultimo_parecer($i->id_siconv);
//                    var_dump(array($i->id_siconv, $parecer_novo));
                        $this->get_parecer_empenho_banco_proposta_siconv($i->id_proposta);
                        $parecer_retorno = $this->parecer_banco_proposta_model->get_data_ultimo_parecer($i->id_siconv);
                        if ($parecer_retorno != null) {
                            if ($parecer_novo != $parecer_retorno)
                                $lista_proposta_envia_email[$this->input->get('cnpj', TRUE)][] = $i->codigo_siconv;
                        }
                    }
                }

//            var_dump($lista_proposta_envia_email);
                foreach ($lista_proposta_envia_email as $cnpjKey => $codigos_envia_email) {
                    $dados = $this->usuario_cnpj->get_dados_envia_email($cnpjKey);

                    $origem = "no-reply@info-convenios.com.br";
                    $assunto = "Atualizações em propostas";
                    $urlLogo = base_url() . "layout/assets/images/logo_physis_login.png";

                    $mensagem = "<div align='left'  style='width: 400px;'><img src='{$urlLogo}' width='150' height='30'/><span style='font-size:20px; float: right; color: #428bca;'>Atualização de Propostas</span><br/><hr><br/>";
                    $mensagem .= "<b>Números das Propostas:</b><br/>";
                    foreach ($codigos_envia_email as $codigo) {
                        $mensagem .= "- {$codigo}<br/>";
                    }

                    $url = base_url() . "layout/assets/images/ass_email_suporte.png";
                    $mensagem .= "<br/><hr></div>";
                    $mensagem .= "<img src='{$url}' width='300' height='150'/>";

                    $this->envia_email($origem, $dados->email, null, $assunto, "<html><div align='center'>" . $mensagem . "</div></html>");
                    //echo $mensagem;
                }
            }
        }
//        echo date("d/m/Y H:i:s");
    }

    public function executa_python_script() {
        ini_set('max_execution_time', 0);
        $this->load->model('usuario_cnpj');
        $this->load->model('system_logs');
        $this->load->model('usuariomodel');
        $dados = $this->usuario_cnpj->get_cnpjs_atualizar();

        foreach ($dados as $d) {
            $this->system_logs->add_log_cron(INICIA_UPDATE_PROGRAMAS . ' - CNPJ: ' . $d->cnpj);
            $this->usuariomodel->enviar_email_cron('Serviço iniciado em ' . date('d/m/Y H:i:s') . ' - CNPJ: ' . $d->cnpj);

            $commandLine = 'python /var/www/esicar/python_scripts/request_proposta_usuario.py' . ' ' . $d->cnpj;
            $command = escapeshellcmd($commandLine);
            echo $command . "<br>";
            $output = exec($command);
            echo $output . "<br><br>";

            $this->system_logs->add_log_cron(FINALIZA_UPDATE_PROGRAMAS . ' - CNPJ: ' . $d->cnpj);
            $this->usuariomodel->enviar_email_cron('Serviço finalizado em ' . date('d/m/Y H:i:s') . ' - CNPJ: ' . $d->cnpj);
        }
    }

    public function executa_python_script_cnpjs_vendedores() {
        ini_set('max_execution_time', 0);
        $this->load->model('usuario_cnpj');
        $this->load->model('usuariomodel');
        $dados = $this->usuario_cnpj->get_cnpjs_vinculados_vendedores();

        $this->usuariomodel->enviar_email_cron('Serviço iniciado em ' . date('d/m/Y H:i:s'), "CNPJs Vendedores");

        foreach ($dados as $d) {
            $commandLine = 'python /var/www/esicar/python_scripts/request_proposta_usuario.py' . ' ' . $d->cnpj_vinculado;
            $command = escapeshellcmd($commandLine);
            echo $command . "<br>";
            $output = exec($command);
            echo $output . "<br><br>";
        }

        $this->db->where('id_usuario > ', 0);
        $this->db->delete('cnpj_vendedores');

        $this->usuariomodel->enviar_email_cron('Serviço finalizado em ' . date('d/m/Y H:i:s'), "CNPJs Vendedores");
    }

    function obter_paginaLogin($login_usuario, $senha) {
        $login_usuario = urlencode($login_usuario);
        $senha = urlencode($senha);
        $url = "https://www.convenios.gov.br/siconv/secure/EntrarLoginValidar.do?login=$login_usuario&senha=$senha";
        //$cookie_file_path = "application/views/configuracoes/cookie.txt";
        $cookie_file_path = $this->cookie_file_path;
        $agent = "Mozilla/5.0 (Windows; U; Windows NT 5.0; en-US; rv:1.4) Gecko/20030624 Netscape/7.1 (ax)";
        $ch = curl_init();
        // extra headers
        $headers[] = "Accept: */*";
        $headers[] = "Connection: Keep-Alive";

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

        if (strstr($content, '<span style="float:left">Erro') !== false) {
            $this->session->set_userdata('falha_login', 'S');
            $this->alert("Senha incorreta, verifique se a senha cadastrada confere com a do SICONV.");
            if ($this->session->userdata('nivel') == 1)
                $this->encaminha('escolher_proponente');
            else
                $this->encaminha(base_url('index.php/controle_usuarios/atualiza_usuario?id=' . $this->session->userdata('id_usuario')));
            die();
        }else if (strstr($content, 'nova senha') !== false) {
            $this->session->set_userdata('falha_login', 'S');
            $this->alert("Senha desatualizada, é necessário login no SICONV para atualizá-la");
            $this->encaminha_siconv();
            if ($this->session->userdata('nivel') == 1)
                $this->encaminha('escolher_proponente');
            else
                $this->encaminha(base_url('index.php/controle_usuarios/atualiza_usuario?id=' . $this->session->userdata('id_usuario')));
            die();
        }
        return $content;
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
          if (strstr ( $content, 'Acesso Livre' ) === false) {
          if ($id == 1) {
          echo "erro na página interna do siconv, entre em contato com o administrador.";
          die ();
          }

          $this->cookie_file_path = tempnam ( "/tmp", "CURLCOOKIE1" . rand () );
          return $this->obter_paginaLogin ( 1 );
          }
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

    function removeSpaceSurplus($str) {
        return preg_replace("/\s+/", ' ', trim($str));
    }

    function getTextBetweenTags($string, $tag1, $tag2) {
        $pattern = "/$tag1([\w\W]*?)$tag2/";
        preg_match_all($pattern, $string, $matches);
        return $matches[1];
    }

    function envia_email($origem, $destino, $copia, $assunto, $mensagem) {
        $this->load->model('usuariomodel');

        $this->load->library('email');

        $this->email->initialize($this->usuariomodel->inicializa_config_email($origem));

        $this->email->set_mailtype('html');
        $this->email->from($origem, "Info Convênios -- Info Convênios");
        $this->email->to($destino);
        if ($copia != null) {
            $this->email->cc($copia);
        }
        $this->email->subject($assunto);
        $this->email->message($mensagem);
        $this->email->send();
    }

    function obter_pagina_post($url, $fields, $fields_string) {

        //$cookie_file_path = "application/views/configuracoes/cookie.txt";
        $cookie_file_path = $this->cookie_file_path;
        $agent = "Mozilla/5.0 (Windows; U; Windows NT 5.0; en-US; rv:1.4) Gecko/20030624 Netscape/7.1 (ax)";
        $ch = curl_init();
        // extra headers
        $headers[] = "Accept: */*";
        $headers[] = "Connection: Keep-Alive";
        //$headers[] = 'Content-type: multipart/form-data';
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
            $this->alert("Houve um erro de conexão com o siconv. Por favor copie e cole o texto abaixo para adm@physisbrasil.com.br: " . $erro[0]);
            //echo $url;
            die();
        }

        return $content;
    }

    function alert($text) {
        echo "<script type='text/javascript'>alert('" . utf8_decode($text) . "');</script>";
    }

    public function get_propostas_by_parlamentar() {
        ini_set('max_execution_time', 0);
        echo date("d/m/Y H:i:s");

        $this->load->model('parecer_banco_proposta_model');
        $this->load->model('usuario_cnpj');

        if ($this->input->get('cod_parlamentar', TRUE) != '') {

            //$this->autentica_siconv->new_init_siconv_do_login("guest", "guest", $this->login, $this->cookie_file_path);
            $this->autentica_siconv->new_init_siconv_do_login("43346880559", "Laisa_M2012", $this->login, $this->cookie_file_path);

            $pagina = "https://www.convenios.gov.br/siconv/ForwardAction.do?modulo=Principal&path=/MostraPrincipalConsultarProposta.do";
            echo utf8_decode($this->autentica_siconv->new_obter_pagina($pagina, $this->login, $this->cookie_file_path));

            $fields = array(
                'invalidatePageControlCounter' => 1,
                'destino' => '',
                'numeroProposta' => '',
                'orgaoProposta' => '',
                'nomeOrgao' => '',
                'situacaoProposta' => 'VAZIO',
                'modalidade' => '',
                'ano' => '',
                'enviadaInstituicaoMandataria' => '13',
                'codigoPrograma' => '',
                'codigoParlamentar' => $this->input->get('cod_parlamentar', TRUE),
                'numeroEmendaParlamentar' => '',
                'nomeProponente' => '',
                'tipoIdentificacao' => '5',
                'identificacao' => '',
                'uf' => '',
                'municipioProponente' => '',
                'situacaoProponente' => '0',
                'cpfResponsavel' => '',
                'naturezaJuridica' => '',
                'dataInicioEnvioPropostaAnalise' => '',
                'dataFimEnvioPropostaAnalise' => '',
                'camposParaExibirAsArray' => ''
            );

            $fields_string = null;
            foreach ($fields as $key => $value) {
                $fields_string .= $key . '=' . $value . '&';
            }

            $pagina = "https://www.convenios.gov.br/siconv/ConsultarProposta/PreenchaOsDadosDaConsultaConsultar.do";
            echo $pagina . "?" . $fields_string;
            $documento = utf8_decode($this->obter_pagina_post($pagina, $fields, $fields_string));
            echo $documento;
            $documento = $this->removeSpaceSurplus($documento);

            $pages = $this->getTextBetweenTags($documento, "<span class=\"pagelinks\">", "<\/span>");
            $pages = explode("de", $pages[0]);
            $numPages = explode("(", $pages[1]);

            $numRegs = explode("item", $numPages[1]);

            $numRegs = trim($numRegs[0]);
            $numPages = trim($numPages[0]);

            echo $numPages . " - " . $numRegs;

            $listaIDS = array();
            for ($i = 1; $i <= $numPages; $i++) {
                $tabela = $this->getTextBetweenTags($documento, "<tbody id=\"tbodyrow\">", "<\/tbody>");
                $links = $this->getTextBetweenTags($tabela[0], "<div class=\"numeroProposta\">", "<\/a> <\/div>");
                //var_dump($links);
                foreach ($links as $link) {
                    $parteLink = explode("idProposta=", $link);
                    $parteLink = explode("&", $parteLink[1]);
                    $id = $parteLink[0];
                    echo $id . "<br>";
                    $listaIDS[] = $id;
                }

                $pagina = "https://www.convenios.gov.br/siconv/ConsultarProposta/PreenchaOsDadosDaConsultaConsultar.do?d-16544-t=listaResultado&d-16544-p=2&d-16544-g=" . ($i + 1) . "&tipo_consulta=CONSULTA_COMPLETA";
                $documento = utf8_decode($this->autentica_siconv->new_obter_pagina($pagina, $this->login, $this->cookie_file_path));
                echo $documento;
                $documento = $this->removeSpaceSurplus($documento);
            }

            $listaIDS = array_chunk($listaIDS, 5);
            var_dump($listaIDS);
            foreach ($listaIDS as $ids) {
                foreach ($ids as $id)
                    $this->get_propostas_siconv($id, $id);
            }
        }
        echo date("d/m/Y H:i:s");
    }

    public function executa_python_script_deputado() {
        ini_set('max_execution_time', 0);
        $this->load->model('usuariomodel');

        $offset = null;
        if ($this->input->get('offset', TRUE) != FALSE)
            $offset = $this->input->get('offset', TRUE);

        $listaParlamentares = $this->usuariomodel->get_parlamentar_by_cargo('DEPUTADO', 50, $offset);

        foreach ($listaParlamentares as $parlamentar) {
            $codigo = explode(",", $parlamentar->codigo_parlamentar);

            foreach ($codigo as $c) {
                $this->usuariomodel->enviar_email_cron('Serviço iniciado em ' . date('d/m/Y H:i:s') . "<br/>- Parlamentar " . trim($c) . " - {$parlamentar->nome_parlamentar}", "Propostas Parlamentares - Deputado");

                $commandLine = 'python /var/www/esicar/python_scripts/request_proposta_parlamentar.py' . ' ' . trim($c);
                $command = escapeshellcmd($commandLine);
                echo $command . "<br>";
                $output = exec($command);
                echo $output . "<br><br>";
            }
        }

        $this->usuariomodel->enviar_email_cron('Serviço finalizado em ' . date('d/m/Y H:i:s'), "Propostas Parlamentares - Deputado");
    }

    public function executa_python_script_senador() {
        ini_set('max_execution_time', 0);
        $this->load->model('usuariomodel');

        $offset = null;
        if ($this->input->get('offset', TRUE) != FALSE)
            $offset = $this->input->get('offset', TRUE);

        $listaParlamentares = $this->usuariomodel->get_parlamentar_by_cargo('SENADOR', 20, $offset);

        foreach ($listaParlamentares as $parlamentar) {
            $codigo = explode(",", $parlamentar->codigo_parlamentar);

            foreach ($codigo as $c) {
                $this->usuariomodel->enviar_email_cron('Serviço iniciado em ' . date('d/m/Y H:i:s') . "<br/>- Parlamentar " . trim($c) . " - {$parlamentar->nome_parlamentar}", "Propostas Parlamentares - Senador");

                $commandLine = 'python /var/www/esicar/python_scripts/request_proposta_parlamentar.py' . ' ' . trim($c);
                $command = escapeshellcmd($commandLine);
                echo $command . "<br>";
                $output = exec($command);
                echo $output . "<br><br>";
            }
        }

        $this->usuariomodel->enviar_email_cron('Serviço finalizado em ' . date('d/m/Y H:i:s'), "Propostas Parlamentares - Senador");
    }

}
