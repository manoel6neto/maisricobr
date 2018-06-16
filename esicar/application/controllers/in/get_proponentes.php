<?php

date_default_timezone_set('America/Sao_Paulo');

class get_proponentes extends CI_Controller {

    function __construct() {
        parent::__construct();

        $this->cookie_file_path = tempnam("/tmp", "CURLCOOKIE" . rand());
        $this->login = null;
    }

    public function get_proponentes_siconv_json() {
        ini_set("max_execution_time", 0);
        ini_set("memory_limit", "-1");
        ini_set("allow_url_fopen", 1);

        //URLS
        //https://www.convenios.gov.br/siconv/programa/ListarProgramas/ListarProgramas.do?Usr=guest&Pwd=guest
        //https://www.convenios.gov.br/siconv/Principal/Principal.do
        //https://idp.convenios.gov.br/idp/?LLO=true
        //https://idp.convenios.gov.br/idp/?WilyCmd=cmdJS
        //https://idp.convenios.gov.br/idp/
        //https://idp.convenios.gov.br/idp//public/refresh.jsp?1499691569899
        //https://transfere.convenios.gov.br/habilitacao/?LLO=true&=1499691661452
        //https://transfere.convenios.gov.br/habilitacao/consulta-entidade.html
        //https://transfere.convenios.gov.br/habilitacao/api/entidade/88630413000109?completo
        //https://transfere.convenios.gov.br/habilitacao/api/entidade
        //https://transfere.convenios.gov.br/habilitacao/api/usuario/ativacao
        //https://transfere.convenios.gov.br/habilitacao/api/entidade?pagina=1

        try {
            try {
                $lista_proponentes_completa = array(
                    'tipo' => null,
                    'cnpj' => null,
                    'nome' => null,
                    'status' => null
                );

                $pagina = "https://transfere.convenios.gov.br/habilitacao/consulta-entidade.html?Usr=guest&Pwd=guest";
                $documento = $this->autentica_siconv->openPage($pagina, null);
                $documento = $this->removeSpaceSurplus($documento);

            } catch (Exception $e) {
                echo $e->getMessage();
            }

            if (true) {

                //Array com os valores para cada propoente
                $proponente_siconv_atualizacao = array(
                    'cnpj' => null,
                    'nome' => null,
                    'esfera_administrativa' => null,
                    'codigo_municipio' => null,
                    'municipio' => null,
                    'municipio_uf_sigla' => null,
                    'municipio_uf_nome' => null,
                    'municipio_uf_regiao' => null,
                    'endereco' => null,
                    'cep' => null,
                    'nome_responsavel' => null,
                    'telefone' => null,
                    'fax' => null,
                    'natureza_juridica' => null,
                    'inscricao_estadual' => null,
                    'inscricao_municipal' => null,
                    'situacao' => null,
                    'email' => null,
                    'orgao' => null,
                    'area' => null,
                    'subarea' => null,
                    'situacao_aprovacao' => null,
                    'data_registro' => null,
                    'data_vencimento' => null,
                    'id_siconv' => null
                );

                //get cnpj
                $txt1 = $this->getTextBetweenTags($documento, '<td class="label" align=right>Identificação<\/td> <td class="field" colspan=2> ', '<\/td> <\/tr> ');
                if ($txt1 != null && count($txt1) > 0) {
                    $proponente_siconv['cnpj'] = trim($txt1[0]);
                    $proponente_siconv['cnpj'] = str_replace('&nbsp;', '', $proponente_siconv['cnpj']);
                }

                //Continuar processamento se proponente n for com cnpj nulo
                if ($proponente_siconv['cnpj'] != null) {
                    if ($proponente_siconv['cnpj'] != '') {

                        //get nome
                        $txt1 = $this->getTextBetweenTags($documento, '<td class="label">Razão Social<\/td> <td class="field" colspan=4> ', '<\/td> <\/tr> ');
                        if ($txt1 != null && count($txt1) > 0) {
                            $proponente_siconv['nome'] = trim($txt1[0]);
                            $proponente_siconv['nome'] = str_replace('&nbsp;', '', $proponente_siconv['nome']);
                            $proponente_siconv['nome'] = html_entity_decode($proponente_siconv['nome'], ENT_QUOTES);
                        } else {
                            $txt1 = $this->getTextBetweenTags($documento, '<td class="label">Nome<\/td> <td class="field" colspan=4> ', '<\/td> <\/tr> ');
                            $proponente_siconv['nome'] = trim($txt1[0]);
                            $proponente_siconv['nome'] = str_replace('&nbsp;', '', $proponente_siconv['nome']);
                            $proponente_siconv['nome'] = html_entity_decode($proponente_siconv['nome'], ENT_QUOTES);
                        }

                        if ($proponente_siconv['nome'] != null && $proponente_siconv['nome'] != '') {

                            //get esfera administrativa
                            $txt1 = $this->getTextBetweenTags($documento, '<td class="label">Natureza Jurídica<\/td> <td class="field" colspan=4> ', '<\/td> ');
                            if ($txt1 != null && count($txt1) > 0) {
                                $temp_esfera = trim($txt1[0]);
                                $temp_esfera = str_replace('&nbsp;', '', $temp_esfera);

                                if ($temp_esfera == 'Entidade Privada sem fins lucrativos' || $temp_esfera == 'Organização da Sociedade Civil') {
                                    $proponente_siconv['esfera_administrativa'] = 'PRIVADA';
                                } else if ($temp_esfera == 'Administração Pública Municipal') {
                                    $proponente_siconv['esfera_administrativa'] = 'MUNICIPAL';
                                } else if ($temp_esfera == 'Administração Pública Federal') {
                                    $proponente_siconv['esfera_administrativa'] = 'FEDERAL';
                                } else if ($temp_esfera == 'Administração Pública Estadual ou do Distrito Federal') {
                                    $proponente_siconv['esfera_administrativa'] = 'ESTADUAL';
                                } else if ($temp_esfera == 'Empresa pública\/Sociedade de economia mista') {
                                    $proponente_siconv['esfera_administrativa'] = 'EMPRESA PUBLICA SOCIEDADE ECONOMIA MISTA';
                                } else if ($temp_esfera == 'Consórcio Público') {
                                    $proponente_siconv['esfera_administrativa'] = 'CONSORCIO PUBLICO';
                                } else {
                                    $proponente_siconv['esfera_administrativa'] = 'ORGANISMO INTERNACIONAL';
                                }
                            }

                            //get municipio
                            $txt1 = $this->getTextBetweenTags($documento, '<td class="label">Município<\/td> <td class="field" colspan=2> ', '<\/td> ');
                            if ($txt1 != null && count($txt1) > 0) {
                                $proponente_siconv['municipio'] = utf8_encode(trim($txt1[0]));
                                $proponente_siconv['municipio'] = str_replace('&nbsp;', '', $proponente_siconv['municipio']);
                                $proponente_siconv['municipio'] = html_entity_decode($proponente_siconv['municipio'], ENT_QUOTES);
                            }

                            //get estado sigla
                            $txt1 = $this->getTextBetweenTags($documento, '<td class="label" align=right>UF<\/td> <td class="field" colspan=2> ', '<\/td> <\/tr> ');
                            if ($txt1 != null && count($txt1) > 0) {
                                $proponente_siconv['municipio_uf_sigla'] = trim($txt1[0]);
                                $proponente_siconv['municipio_uf_sigla'] = str_replace('&nbsp;', '', $proponente_siconv['municipio_uf_sigla']);
                            }

                            //get estado nome
                            if ($proponente_siconv['municipio_uf_sigla'] != null) {
                                $this->db->where('sigla', $proponente_siconv['municipio_uf_sigla']);
                                $query = $this->db->get('estados');

                                if ($query->num_rows > 0) {
                                    $result = $query->row(0);
                                    $proponente_siconv['municipio_uf_nome'] = $result->nome;
                                }
                            }

                            //get codigo municipio
                            $proponente_siconv['codigo_municipio'] = $this->proponente_siconv_model->get_codigo_municipio($proponente_siconv['municipio'], $proponente_siconv['municipio_uf_nome'], $proponente_siconv['municipio_uf_sigla']);

                            //get estado regiao
                            if ($proponente_siconv['municipio_uf_nome'] != null) {
                                switch ($proponente_siconv['municipio_uf_sigla']) {
                                    case 'RJ':
                                    case 'SP':
                                    case 'MG':
                                    case 'ES':
                                        $proponente_siconv['municipio_uf_regiao'] = 'SE';
                                        break;
                                    case 'RS':
                                    case 'PR':
                                    case 'SC':
                                        $proponente_siconv['municipio_uf_regiao'] = 'S';
                                        break;
                                    case 'GO':
                                    case 'MT':
                                    case 'MS':
                                        $proponente_siconv['municipio_uf_regiao'] = 'CO';
                                        break;
                                    case 'AL':
                                    case 'BA':
                                    case 'CE':
                                    case 'MA':
                                    case 'PB':
                                    case 'PE':
                                    case 'PI':
                                    case 'RN':
                                    case 'SE':
                                        $proponente_siconv['municipio_uf_regiao'] = 'NE';
                                        break;
                                    case 'AM':
                                    case 'AC':
                                    case 'PA':
                                    case 'AP':
                                    case 'RO':
                                    case 'RR':
                                    case 'TO':
                                        $proponente_siconv['municipio_uf_regiao'] = 'N';
                                        break;
                                }
                            }

                            //get endereco
                            $txt1 = $this->getTextBetweenTags($documento, '<td class="label">Endereço<\/td> <td class="field" colspan=4> ', '<\/td> <\/tr> ');
                            if ($txt1 != null && count($txt1) > 0) {
                                $proponente_siconv['endereco'] = trim($txt1[0]);
                                $proponente_siconv['endereco'] = str_replace('&nbsp;', '', $proponente_siconv['endereco']);
                                $proponente_siconv['endereco'] = html_entity_decode($proponente_siconv['endereco'], ENT_QUOTES);
                            }

                            //get cep
                            $txt1 = $this->getTextBetweenTags($documento, '<td class="label" align=right>CEP<\/td> <td class="field" colspan=2> ', '<\/td> <\/tr> ');
                            if ($txt1 != null && count($txt1) > 0) {
                                $proponente_siconv['cep'] = trim($txt1[0]);
                                $proponente_siconv['cep'] = str_replace('&nbsp;', '', $proponente_siconv['cep']);
                                $proponente_siconv['cep'] = str_replace('-', '', $proponente_siconv['cep']);
                            }

                            //get nome responsavel
                            $txt1 = $this->getTextBetweenTags($documento, '<td class="label">Nome do Responsável<\/td> <td class="field" colspan="3"> ', '<\/td> ');
                            if ($txt1 != null && count($txt1) > 0) {
                                $proponente_siconv['nome_responsavel'] = trim($txt1[0]);
                                $proponente_siconv['nome_responsavel'] = str_replace('&nbsp;', '', $proponente_siconv['nome_responsavel']);
                            }

                            //get telefone
                            $txt1 = $this->getTextBetweenTags($documento, '<td class="label">Telefone<\/td> <td class="field" colspan=2> ', '<\/td> ');
                            if ($txt1 != null && count($txt1) > 0) {
                                $proponente_siconv['telefone'] = trim($txt1[0]);
                                $proponente_siconv['telefone'] = str_replace('&nbsp;', '', $proponente_siconv['telefone']);
                            }

                            //get fax
                            $txt1 = $this->getTextBetweenTags($documento, '<td class="label" align=right>Telex\/Fax\/Caixa Postal<\/td> <td class="field" colspan=2> ', '<\/td> <\/tr> ');
                            if ($txt1 != null && count($txt1) > 0) {
                                $proponente_siconv['fax'] = trim($txt1[0]);
                                $proponente_siconv['fax'] = str_replace('&nbsp;', '', $proponente_siconv['fax']);
                            }

                            //get natureza juridica
                            $txt1 = $this->getTextBetweenTags($documento, '<td class="label">Natureza Jurídica<\/td> <td class="field" colspan=4> ', '<\/td> ');
                            if ($txt1 != null && count($txt1) > 0) {
                                $proponente_siconv['natureza_juridica'] = trim($txt1[0]);
                                $proponente_siconv['natureza_juridica'] = str_replace('&nbsp;', '', $proponente_siconv['natureza_juridica']);
                            }

                            //get inscricao estadual
                            $txt1 = $this->getTextBetweenTags($documento, '<td class="label">Inscrição Estadual<\/td> <td class="field" colspan=2> ', '<\/td> ');
                            if ($txt1 != null && count($txt1) > 0) {
                                $proponente_siconv['inscricao_estadual'] = trim($txt1[0]);
                                $proponente_siconv['inscricao_estadual'] = str_replace('&nbsp;', '', $proponente_siconv['inscricao_estadual']);
                            }

                            //get inscricao municipal
                            $txt1 = $this->getTextBetweenTags($documento, '<td class="label" align=right>Inscrição Municipal<\/td> <td class="field" colspan=2> ', '<\/td> <\/tr> ');
                            if ($txt1 != null && count($txt1) > 0) {
                                $proponente_siconv['inscricao_municipal'] = trim($txt1[0]);
                                $proponente_siconv['inscricao_municipal'] = str_replace('&nbsp;', '', $proponente_siconv['inscricao_municipal']);
                            }

                            //get situacao
                            $txt1 = $this->getTextBetweenTags($documento, '<td class="label" align=right>Situação<\/td> <td class="field" colspan=2> ', '<\/td> <\/tr> ');
                            if ($txt1 != null && count($txt1) > 0) {
                                $proponente_siconv['situacao'] = trim($txt1[0]);
                                $proponente_siconv['situacao'] = str_replace('&nbsp;', '', $proponente_siconv['situacao']);
                            }

                            //get email
                            $txt1 = $this->getTextBetweenTags($documento, '<td class="label">E-mail<\/td> <td class="field" colspan=4> ', '<\/td> <\/tr> ');
                            if ($txt1 != null && count($txt1) > 0) {
                                $proponente_siconv['email'] = trim($txt1[0]);
                                $proponente_siconv['email'] = str_replace('&nbsp;', '', $proponente_siconv['email']);
                            }

                            if ($proponente_siconv['esfera_administrativa'] == 'PRIVADA') {

                                //Busca as informações extras de entidade privada
                                $dados_privada = array(
                                    'orgao' => null,
                                    'area' => null,
                                    'subarea' => null,
                                    'situacao_aprovacao' => null,
                                    'data_registro' => null,
                                    'data_vencimento' => null
                                );

                                // ---- Dados extra entidade privada ---- //                
                                $pagina_privado = "https://www.convenios.gov.br/siconv/participe/ListarHabilitacoesCapacidadeTec/ListarHabilitacoesCapacidadeTec.do?id={$count_id}";
                                $documento_privado = $this->autentica_siconv->new_obter_pagina($pagina_privado, $this->login, $this->cookie_file_path);
                                $documento_privado = $this->removeSpaceSurplus($documento_privado);

                                if ($documento_privado != null && $documento_privado != '') {

                                    //Carregando a tabela para saber qual cadastro pegar
                                    $txt1 = $this->getTextBetweenTags($documento_privado, '<tbody id="tbodyrow"> ', '<\/tbody> ');
                                    if ($txt1 != null && count($txt1) > 0) {
                                        $txt1 = $this->getTextBetweenTags(trim($txt1[0]), '<tr class=', '<\/tr> ');

                                        $list_arrays_temp = array();
                                        foreach ($txt1 as $row) {
                                            $temp_array_data_link = array(
                                                'data' => null,
                                                'link' => null
                                            );

                                            //Filtrando a data e o link
                                            $data = $this->getTextBetweenTags($row, '<div class="dataVencimento">', '<\/div> ');
                                            if ($data != null && count($data) > 0) {
                                                $data = trim($data[0]);
                                                $data = date('d-m-Y', strtotime(str_replace('/', '-', trim($data))));
                                                //$data = date('Y-m-d', strtotime($data));
                                            }

                                            $link = $this->getTextBetweenTags($row, '<a href="javascript:document.location=', ';" class="buttonLink">');
                                            if ($link != null && count($link) > 0) {
                                                $link = trim($link[0]);
                                                $link = str_replace("'", "", $link);
                                                $link = "https://www.convenios.gov.br" . $link;
                                            }

                                            //Adicionando no array
                                            $temp_array_data_link['data'] = $data;
                                            $temp_array_data_link['link'] = $link;

                                            //Adicionando no array maior
                                            array_push($list_arrays_temp, $temp_array_data_link);
                                        }

                                        if ($list_arrays_temp != null && count($list_arrays_temp) > 0) {
                                            $maior_data = null;
                                            foreach ($list_arrays_temp as $tarray) {
                                                if ($maior_data == null) {
                                                    $maior_data = $tarray;
                                                } else {
                                                    if (strtotime($maior_data['data']) < strtotime($tarray['data'])) {
                                                        $maior_data = $tarray;
                                                    }
                                                }
                                            }

                                            //Carregar o link e processar os dados para finalizar o insert
                                            // ---- Dados extra entidade privada ---- //                
                                            if ($maior_data != null) {
                                                $pagina_privado_final = $maior_data['link'];
                                                $documento_privado_final = $this->autentica_siconv->new_obter_pagina($pagina_privado_final, $this->login, $this->cookie_file_path);
                                                $documento_privado_final = $this->removeSpaceSurplus($documento_privado_final);

                                                if ($documento_privado_final != null && $documento_privado_final != '') {
                                                    //filtrando o orgao
                                                    $txt1 = $this->getTextBetweenTags($documento_privado_final, '<tr class="orgaoPlain" id="tr-aprovarHabilitacaoOrgaoPlain"> <td class="label">Órgão <\/td> <td class="field"> ', '<\/td> <\/tr> ');
                                                    if ($txt1 != null && count($txt1) > 0) {
                                                        $txt1 = trim($txt1[0]);
                                                        $proponente_siconv['orgao'] = $txt1;
                                                    }

                                                    //filtrando a area
                                                    $txt1 = $this->getTextBetweenTags($documento_privado_final, '<td class="label">Área de Atuação <\/td> <td class="field"> ', '<\/td> <\/tr> ');
                                                    if ($txt1 != null && count($txt1) > 0) {
                                                        $txt1 = trim($txt1[0]);
                                                        $proponente_siconv['area'] = $txt1;
                                                    }

                                                    //filtrando a subarea
                                                    $txt1 = $this->getTextBetweenTags($documento_privado_final, '<tr class="subareaPlain" id="tr-aprovarHabilitacaoSubareaPlain"> <td class="label">Subárea de Atuação <\/td> <td class="field"> ', '<\/td> <\/tr> ');
                                                    if ($txt1 != null && count($txt1) > 0) {
                                                        $txt1 = trim($txt1[0]);
                                                        $proponente_siconv['subarea'] = $txt1;
                                                    }

                                                    //filtrando a situacao
                                                    $txt1 = $this->getTextBetweenTags($documento_privado_final, '<tr class="situacaoHabilitacao" id="tr-aprovarHabilitacaoSituacaoHabilitacao"> <td class="label">Situação da aprovação <\/td> <td class="field"> ', '<\/td> <\/tr> ');
                                                    if ($txt1 != null && count($txt1) > 0) {
                                                        $txt1 = trim($txt1[0]);
                                                        $proponente_siconv['situacao_aprovacao'] = $txt1;
                                                    }

                                                    //filtrando a data de registro da aprovacao (caso exista)
                                                    $txt1 = $this->getTextBetweenTags($documento_privado_final, '<tr class="dataAprovacao" id="tr-aprovarHabilitacaoDataAprovacao" > ', '<\/td> <\/tr> ');
                                                    if ($txt1 != null && count($txt1) > 0) {
                                                        $txt1 = trim($txt1[0]);
                                                        $txt1 = explode('<td class="field">', $txt1);
                                                        if ($txt1 != null && count($txt1) > 0) {
                                                            $txt1 = trim($txt1[1]);
                                                            $proponente_siconv['data_registro'] = $txt1;
                                                            $proponente_siconv['data_registro'] = date('d-m-Y', strtotime(str_replace('/', '-', $proponente_siconv['data_registro'])));
                                                            $proponente_siconv['data_registro'] = date('Y-m-d', strtotime($proponente_siconv['data_registro']));
                                                        }
                                                    }

                                                    //filtrando a data de vencimento
                                                    $txt1 = $this->getTextBetweenTags($documento_privado_final, '<td class="label">Validade da aprovação <\/td> <td class="field"> ', '<\/td> <\/tr> ');
                                                    if ($txt1 != null && count($txt1) > 0) {
                                                        $txt1 = trim($txt1[0]);
                                                        $proponente_siconv['data_vencimento'] = $txt1;
                                                        $proponente_siconv['data_vencimento'] = date('d-m-Y', strtotime(str_replace('/', '-', $proponente_siconv['data_vencimento'])));
                                                        $proponente_siconv['data_vencimento'] = date('Y-m-d', strtotime($proponente_siconv['data_vencimento']));
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }

                            //id_siconv
                            $proponente_siconv['id_siconv'] = $count_id;

                            //Salvar ou atualizar o registro
//                                $this->usuariomodel->enviar_email_cron('Atualizando proponente siconv - Capturado CNPJ: ' . $proponente_siconv['cnpj'] . '. Datetime: ' . date('d/m/Y H:i:s'), "Proponente Siconv - UPDATE");
                            $this->db->flush_cache();
                            $this->get_proponentes_model->insert_or_update($proponente_siconv);
                        }
                    }
                }
            }
        } catch (Exception $ex) {
            //tratar exception
        }
    }

    function removeSpaceSurplus($str) {
        return preg_replace("/\s+/", ' ', trim($str));
    }

    function getTextBetweenTags($string, $tag1, $tag2) {
        try {
            $pattern = "/$tag1([\w\W]*?)$tag2/";
            preg_match_all($pattern, $string, $matches);
            return $matches[1];
        } catch (Exception $ex) {
            //Tratar exception
        }
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

    function alert($text) {
        echo "<script type='text/javascript'>alert('" . utf8_encode($text) . "');</script>";
    }

}
