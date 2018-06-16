<?php

class get_proponentes extends CI_Controller {

    public function __construct() {
        parent::__construct();

        $this->cookie_file_path = tempnam("/tmp", "CURLCOOKIE" . rand());
        $this->login = null;
    }

    public function get_api_proponentes($url = "http://api.convenios.gov.br/siconv/v1/consulta/proponentes.json") {
        ini_set('max_execution_time', 0);

        try {
            $this->load->model('usuariomodel');
            $this->load->model('get_proponentes_model');

            echo '<meta charset="UTF-8">';

            //$this->autentica_siconv->new_init_siconv_do_login("guest", "guest", $this->login, $this->cookie_file_path);
            $this->cookie_file_path = tempnam("/tmp", "CURLCOOKIE" . rand());
            $this->login = null;
            $this->autentica_siconv->new_init_siconv_do_login("43346880559", "Laisa_M2012", $this->login, $this->cookie_file_path);

            $this->usuariomodel->enviar_email_cron('Proponentes - Serviço iniciado em ' . date('d/m/Y H:i:s') . "<br>URL: " . $url, "Proponentes");

            $jsonRetorno = json_decode(utf8_encode($this->autentica_siconv->new_obter_pagina($url, $this->login, $this->cookie_file_path)));

            $proxUrl = $jsonRetorno->metadados;
            $registros = $jsonRetorno->proponentes;

            $data_array = $this->init_array();

            foreach ($registros as $registro) {
                try {
                    $data_array['cnpj'] = $this->removeSpaceSurplus($registro->cnpj);

                    if (!$this->get_proponentes_model->check_exist($registro->cnpj)) {
                        $data_array['nome'] = $this->removeSpaceSurplus($registro->nome);
                        $data_array['endereco'] = $this->removeSpaceSurplus($registro->endereco);
                        $data_array['cep'] = $this->removeSpaceSurplus($registro->cep);
                        $data_array['nome_responsavel'] = $this->removeSpaceSurplus($registro->nome_responsavel);
                        $data_array['telefone'] = $this->removeSpaceSurplus($registro->telefone);
                        $data_array['fax'] = $this->removeSpaceSurplus($registro->fax);
                        $data_array['inscricao_estadual'] = $this->removeSpaceSurplus($registro->inscricao_estadual);
                        $data_array['inscricao_municipal'] = $this->removeSpaceSurplus($registro->inscricao_municipal);

                        $this->cookie_file_path = tempnam("/tmp", "CURLCOOKIE" . rand());
                        $this->login = null;
                        $this->autentica_siconv->new_init_siconv_do_login("43346880559", "Laisa_M2012", $this->login, $this->cookie_file_path);
                        $natureza_juridica = json_decode(utf8_decode($this->autentica_siconv->new_obter_pagina($registro->natureza_juridica->NaturezaJuridica->href . ".json", $this->login, $this->cookie_file_path)));
                        $esfera_administrativa = json_decode(utf8_decode($this->autentica_siconv->new_obter_pagina($registro->esfera_administrativa->EsferaAdministrativa->href . ".json", $this->login, $this->cookie_file_path)));
                        $municipio = json_decode(utf8_decode($this->autentica_siconv->new_obter_pagina($registro->municipio->Municipio->href . ".json", $this->login, $this->cookie_file_path)));
                        $habilitacao = json_decode(utf8_decode($this->autentica_siconv->new_obter_pagina(str_replace("?", ".json?", $registro->habilitacoes->href), $this->login, $this->cookie_file_path)));

                        if (!empty($habilitacao->habilitacoes_area_atuacao)) {
                            var_dump($habilitacao->habilitacoes_area_atuacao);
                            $dados_situacao = array(
                                'data_inicio' => implode("-", array_reverse(explode("/", $this->removeSpaceSurplus($habilitacao->habilitacoes_area_atuacao[0]->data_inicio)))),
                                'data_vencimento' => implode("-", array_reverse(explode("/", $this->removeSpaceSurplus($habilitacao->habilitacoes_area_atuacao[0]->data_vencimento)))),
                                'situacao' => $this->removeSpaceSurplus($habilitacao->habilitacoes_area_atuacao[0]->situacao),
                                'cnpj' => $data_array['cnpj']
                            );

                            $this->get_proponentes_model->insert_or_update_situacao($dados_situacao);
                        }

                        $data_array['esfera_administrativa'] = $this->removeSpaceSurplus($esfera_administrativa->esferaadministrativas[0]->nome);
                        $data_array['codigo_municipio'] = $this->removeSpaceSurplus($municipio->municipios[0]->cod_siconv);
                        $data_array['municipio'] = $this->removeSpaceSurplus($municipio->municipios[0]->nome);
                        $data_array['municipio_uf_sigla'] = $this->removeSpaceSurplus($municipio->municipios[0]->uf->sigla);
                        $data_array['municipio_uf_nome'] = $this->removeSpaceSurplus($municipio->municipios[0]->uf->nome);
                        $data_array['municipio_uf_regiao'] = $this->removeSpaceSurplus($municipio->municipios[0]->uf->regiao->sigla);
                        $data_array['natureza_juridica'] = $this->removeSpaceSurplus($natureza_juridica->naturezajuridicas[0]->nome);

                        //$this->autentica_siconv->new_init_siconv_do_login("guest", "guest", $this->login, $this->cookie_file_path);
                        $this->cookie_file_path = tempnam("/tmp", "CURLCOOKIE" . rand());
                        $this->login = null;
                        $this->autentica_siconv->new_init_siconv_do_login("43346880559", "Laisa_M2012", $this->login, $this->cookie_file_path);

                        $documento = $this->removeSpaceSurplus($this->autentica_siconv->new_obter_pagina("https://www.convenios.gov.br/siconv/ForwardAction.do?modulo=Principal&path=/MostraPrincipalConsultarProponente.do", $this->login, $this->cookie_file_path));
                        $invalidatePageControlCounter = $this->getTextBetweenTags($documento, "name=\"invalidatePageControlCounter\" value=\"", "\"\/>");

                        $fields = array(
                            'invalidatePageControlCounter' => $invalidatePageControlCounter[0],
                            'ufAcessoLivre' => '',
                            'municipioAcessoLivre' => '',
                            'nome' => '',
                            'identificacao' => preg_replace('/[^0-9]/', '', (string) $data_array['cnpj']),
                            'tipoIdentificacao' => 1,
                            'situacao' => '0',
                            'cpfResponsavel' => '',
                            'cpfResponsavelCredenciamento' => '',
                            'cpfCadastrador' => '',
                            'uf' => '',
                            'municipio' => '',
                            'unidadeCadastradora' => '',
                            'esferaAdministrativa' => '8'
                        );

                        $fields_string = null;
                        foreach ($fields as $k => $v) {
                            $fields_string .= $k . "=" . $v . "&";
                        }

                        $this->cookie_file_path = tempnam("/tmp", "CURLCOOKIE" . rand());
                        $this->login = null;
                        $this->autentica_siconv->new_init_siconv_do_login("43346880559", "Laisa_M2012", $this->login, $this->cookie_file_path);
                        $documento = $this->removeSpaceSurplus($this->obter_pagina_post("https://www.convenios.gov.br/siconv/ConsultarParticipe/PreenchaOsDadosDaConsultaConsultar.do?tipo_consulta=CONSULTA_COMPLETA", $fields, $fields_string));
                        $situacao = $this->getTextBetweenTags($documento, "<tbody id=\"tbodyrow\">", "<\/tbody>");
                        if (!empty($situacao)) {
                            $situacao = $this->getTextBetweenTags($situacao[0], "<div class=\"situacao\">", "<\/a> <\/div>");
                            $situacao = explode("\">", $situacao[0]);

                            $data_array['situacao'] = $situacao[1];
                        }

                        //var_dump($data_array);

                        $this->get_proponentes_model->insert_or_update($data_array);
                    }
                } catch (Exception $e) {
                    continue;
                }
            }

            if (isset($proxUrl->proximos)) {
                $this->get_api_proponentes($proxUrl->proximos);
            }
        } catch (Exception $e) {
            $this->usuariomodel->enviar_email_cron('Proponentes - Falha: ' . $e->getMessage() . ' - ' . date('d/m/Y H:i:s'), "Proponentes");
        }

        $this->usuariomodel->enviar_email_cron('Proponentes - Serviço finalizado em ' . date('d/m/Y H:i:s'), "Proponentes");
    }

    public function get_situacao_proponentes_faltantes() {
        $this->db->where('situacao IS NULL', null, FALSE);
        $query = $this->db->get('proponente_siconv')->result();

        $array_dados = array_chunk($query, 5);
        var_dump($array_dados);

        //$this->autentica_siconv->new_init_siconv_do_login("guest", "guest", $this->login, $this->cookie_file_path);
        $this->autentica_siconv->new_init_siconv_do_login("43346880559", "Laisa_M2012", $this->login, $this->cookie_file_path);

        foreach ($array_dados as $array) {
            foreach ($array as $dado) {
                $documento = $this->removeSpaceSurplus($this->autentica_siconv->new_obter_pagina("https://www.convenios.gov.br/siconv/ForwardAction.do?modulo=Principal&path=/MostraPrincipalConsultarProponente.do", $this->login, $this->cookie_file_path));
                $invalidatePageControlCounter = $this->getTextBetweenTags($documento, "name=\"invalidatePageControlCounter\" value=\"", "\"\/>");

                $fields = array(
                    'invalidatePageControlCounter' => $invalidatePageControlCounter[0],
                    'ufAcessoLivre' => '',
                    'municipioAcessoLivre' => '',
                    'nome' => '',
                    'identificacao' => preg_replace('/[^0-9]/', '', (string) $dado->cnpj),
                    'tipoIdentificacao' => 1,
                    'situacao' => '0',
                    'cpfResponsavel' => '',
                    'cpfResponsavelCredenciamento' => '',
                    'cpfCadastrador' => '',
                    'uf' => '',
                    'municipio' => '',
                    'unidadeCadastradora' => '',
                    'esferaAdministrativa' => '8'
                );

                $fields_string = null;
                foreach ($fields as $k => $v)
                    $fields_string .= $k . "=" . $v . "&";

                $documento = $this->removeSpaceSurplus($this->obter_pagina_post("https://www.convenios.gov.br/siconv/ConsultarParticipe/PreenchaOsDadosDaConsultaConsultar.do?tipo_consulta=CONSULTA_COMPLETA", $fields, $fields_string));
                $situacao = $this->getTextBetweenTags($documento, "<tbody id=\"tbodyrow\">", "<\/tbody>");
                if (!empty($situacao)) {
                    $situacao = $this->getTextBetweenTags($situacao[0], "<div class=\"situacao\">", "<\/a> <\/div>");
                    $situacao = explode("\">", $situacao[0]);

                    $data_array['situacao'] = $situacao[1];

                    $this->db->where('id_proponente_siconv', $dado->id_proponente_siconv);
                    $this->db->update('proponente_siconv', $data_array);

                    $this->db->flush_cache();
                }
            }
        }
    }

    public function init_array() {
        return array(
            'cnpj' => null,
            'nome' => null,
            'esfera_administrativa' => null,
            'codigo_municipio' => null,
            'municipio' => null,
            'endereco' => null,
            'cep' => null,
            'nome_responsavel' => null,
            'telefone' => null,
            'fax' => null,
            'natureza_juridica' => null,
            'inscricao_estadual' => null,
            'inscricao_municipal' => null,
            'situacao' => null
        );
    }

    function removeSpaceSurplus($str) {
        return preg_replace("/\s+/", ' ', trim($str));
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
            echo "Houve um erro de conexão com o siconv. Por favor copie e cole o texto abaixo para adm@physisbrasil.com.br: " . $erro[0];
            //echo $url;
            die();
        }

        return $content;
    }

    function getTextBetweenTags($string, $tag1, $tag2) {
        $pattern = "/$tag1([\w\W]*?)$tag2/";
        preg_match_all($pattern, $string, $matches);
        return $matches[1];
    }

}
