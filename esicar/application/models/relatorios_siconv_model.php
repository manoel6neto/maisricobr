<?php

class relatorios_siconv_model extends CI_Model {

    public function get_nome_estado_cidade($cidade) {
        $this->db->select('municipio_uf_nome');
        $this->db->distinct();
        $this->db->where('municipio', $cidade);
        $query = $this->db->get('MV_CIDADES');

        return $query->row(0);
    }

    public function get_cnpjs_cidade($cidade) {
        $this->db->select('cnpj');
        $this->db->distinct();
        $this->db->where('municipio', $cidade);
        if ($cidade == "BRASILIA") {
            $query = $this->db->get('MV_DF_CNPJ');
        } else {
            $query = $this->db->get('MV_CIDADES_CNPJ');
        }

        return $query->result();
    }

    public function get_programas_abertos_estado($estado, $ano) {
        $sigla = $this->get_sigla_estado($estado);

        $this->db->where('ano', $ano);
        $query = $this->db->get('MV_SICONV_PROGRAMA');

        $programas_ano = $query->result();

        if (count($programas_ano) > 0) {
            $resultado = array();

            foreach ($programas_ano as $programa) {
                if (strstr($programa->atende, 'Municipal')) {
                    if ($programa->estados == 'Todos os Estados estão Aptos' || strstr($programa->estados, $sigla) != false) {
                        if (!in_array($programa->codigo, $resultado)) {
                            array_push($resultado, $programa);
                        }
                    }
                }
            }

            return $resultado;
        }
    }

    public function get_sigla_estado($estado) {
        $this->db->select('municipio_uf_sigla');
        $this->db->distinct();
        $this->db->where('municipio_uf_nome', $estado);
        $query = $this->db->get('MV_CIDADES');

        return $query->row(0)->municipio_uf_sigla;
    }

    public function get_programas_abertos_cidade($cidade, $ano) {
        $sigla = $this->get_sigla_estado_cidade($cidade);
        $this->db->select('s.codigo as codigo, s.atende as atende, s.ano as ano, s.estados as estados, s.qualificacao as qualificacao');
        $this->db->where('ano', $ano);
        $query = $this->db->get('siconv_programa s');

        $programas_ano = $query->result();

        if (count($programas_ano) > 0) {
            $resultado = array();
            $aux_resultado = array();

            foreach ($programas_ano as $programa) {
                if ($programa->estados == 'Todos os Estados estão Aptos' || strstr($programa->estados, $sigla->municipio_uf_sigla) !== false) {
                    if (!in_array($programa->codigo, $aux_resultado)) {
                        array_push($aux_resultado, $programa->codigo);
                        array_push($resultado, $programa);
                    }
                }
            }

            return $resultado;
        }
    }

    public function get_sigla_estado_cidade($cidade) {
        $this->db->select('municipio_uf_sigla');
        $this->db->distinct();
        $this->db->where('municipio', $cidade);
        $query = $this->db->get('MV_CIDADES');

        return $query->row(0);
    }

    public function get_emendas_por_cidade($cnpjs, $ano) {
        $this->db->select("sb.*, sp.qualificacao");
        $this->db->from("siconv_beneficiario sb");
        $this->db->join("siconv_programa sp", "sb.codigo_programa = sp.codigo", "inner");
        $this->db->where_in("cnpj", $cnpjs);
        $this->db->where_in("YEAR(sp.data_disp)", $ano);
        $this->db->where("sp.excluido is null");
        $query = $this->db->get();
        //$last_query = $this->db->last_query();

        if ($query->num_rows > 0) {
            $resultado_cnpj = $query->result();
            $aux_result = array();
            $aux_emenda = array();
            foreach ($resultado_cnpj as $result) {
                $filtro = array('codigo_programa' => $result->codigo_programa, 'cnpj' => $result->cnpj, 'emenda' => $result->emenda);
                if (!in_array($filtro, $aux_emenda)) {
                    array_push($aux_emenda, $filtro);
                    array_push($aux_result, $result);
                }
            }
            return $aux_result;
        }

        return NULL;
    }

    public function get_emendas_por_cidade_estado($cnpjs, $ano, $estado) {
        $this->db->select("sb.*, sp.qualificacao");
        $this->db->from("siconv_beneficiario sb");
        $this->db->join("siconv_programa sp", "sb.codigo_programa = sp.codigo", "inner");
        $this->db->where_in("cnpj", $cnpjs);
        $this->db->where_in("YEAR(sp.data_disp)", $ano);
        $this->db->where("(sp.estados like '%Todos%' or sp.estados like '%{$estado}%')", null, false);
        $this->db->where("sp.excluido", 0);
        $this->db->where("sb.excluido", 0);
        $query = $this->db->get();
        //$last_query = $this->db->last_query();

        if ($query->num_rows > 0) {
            $resultado_cnpj = $query->result();
            $aux_result = array();
            $aux_emenda = array();
            foreach ($resultado_cnpj as $result) {
                $filtro = array('codigo_programa' => $result->codigo_programa, 'cnpj' => $result->cnpj, 'emenda' => $result->emenda);
                if (!in_array($filtro, $aux_emenda)) {
                    array_push($aux_emenda, $filtro);
                    array_push($aux_result, $result);
                }
            }
            return $aux_result;
        }

        return NULL;
    }

    public function get_propostas_por_cidade_geral($cnpjs, $ano) {
        if ($cnpjs != NULL) {
            foreach ($cnpjs as $cnpj) {
                $array_cnpjs[] = str_replace('/', '', str_replace('-', '', str_replace('.', '', $cnpj)));
            }
            $this->db->distinct('codigo_sinconv');
            //$this->db->join('siconv_programa s', 'b.codigo_programa = s.codigo');
            $this->db->where_in('b.proponente', $array_cnpjs);
            $this->db->where('b.ano', $ano);
            $query = $this->db->get('banco_proposta b');

            if ($query->num_rows > 0) {
                return $query->result();
            }
        }

        return NULL;
    }

    public function get_esferas($regiao = NULL, $estado = NULL, $municipio = NULL) {

        $this->load->model('usuariomodel');
        $this->load->model('programa_model');
        $id_usuario = $this->session->userdata('id_usuario');

        if ($this->session->userdata('nivel') == 2 || $this->session->userdata('nivel') == 3) {
            $cnpjs = $this->usuariomodel->get_cnpjs_gestor_by_usuario($id_usuario);
            $listaCNPJs = array();
            foreach ($cnpjs as $cnpj) {
                $listaCNPJs[] = $this->programa_model->formatCPFCNPJ($cnpj->cnpj);
            }
            $this->db->where_in('cnpj', $listaCNPJs);
        }

        $this->db->distinct('esfera_administrativa');
        $this->db->select('esfera_administrativa');

        if ($regiao != NULL && $regiao != 'TODOS')
            $this->db->where_in('municipio_uf_regiao', $regiao);

        if ($estado != NULL)
            $this->db->where_in('municipio_uf_sigla', $estado);

        if ($municipio != NULL)
            $this->db->where_in('codigo_municipio', $municipio);

        $this->db->order_by("esfera_administrativa", "ASC");
        $query = $this->db->get('proponente_siconv')->result();
        foreach ($query as $q) {
            $result[] = $q->esfera_administrativa;
        }
        //$last_query = $this->db->last_query();
        return $result;
    }

    public function get_proponentes($esfera, $municipio, $uf, $regiao, $tipo = "", $id_usuario = 0, $vinculaParaSubgestor = false, $cidades) {
        $this->load->model('usuariomodel');
        $this->load->model('programa_model');

        if ($tipo == "GESTOR" && $id_usuario > 0) {
            $cnpjs = $this->usuariomodel->get_cnpjs_gestor_by_usuario($id_usuario);
            $listaCNPJs = array();
            $listaCNPJsSub = array();
            foreach ($cnpjs as $cnpj) {
                $listaCNPJs[] = $this->programa_model->formatCPFCNPJ($cnpj->cnpj);

                if ($id_usuario == $cnpj->id)
                    $listaCNPJsSub[] = $this->programa_model->formatCPFCNPJ($cnpj->cnpj);
            }

            if (!empty($listaCNPJs)) {
                if (!$vinculaParaSubgestor)
                    $this->db->where_not_in('cnpj', $listaCNPJs);
                else {
                    $this->db->where_in('cnpj', $listaCNPJs);
                    if (!empty($listaCNPJsSub))
                        $this->db->where_not_in('cnpj', $listaCNPJsSub);
                }
            }
        }

        $this->db->select("cnpj, nome");

        if ($municipio != "") {
            $this->db->where("codigo_municipio", $municipio);
        } elseif ($uf != "") {
            $this->db->where_in('codigo_municipio', $cidades);
        } else {
            foreach ($cidades as $cidade) {
                foreach ($cidade as $value) {
                    $cid[] = $value;
                }
            }
            $this->db->where_in('codigo_municipio', $cid);
        }

        if ($esfera != "") {
            $this->db->where_in('esfera_administrativa', $esfera);

            $this->db->order_by("nome", "ASC");
            $query = $this->db->get('proponente_siconv')->result();
            return $query;
        } else {
            return array(0);
        }
    }

    /*
     * verifica o nível, fator e oportunidades de cadastramento
     */

    public function verifica_nivel($cidade = null, $habitantes = null, $estado = false) {
        //Array com todas as capitais do Brasil
        $capitais = array(
            'PORTO VELHO',
            'MANAUS',
            'RIO BRANCO',
            'CAMPO GRANDE',
            'MACAPÁ', 'BRASÍLIA',
            'BOA VISTA',
            'CUIABÁ', 'PALMAS',
            'TERESINA',
            'SÃO PAULO',
            'RIO DE JANEIRO',
            'BELÉM',
            'SÃO LUÍS',
            'GOIÂNIA',
            'SALVADOR',
            'MACEIÓ',
            'PORTO ALEGRE',
            'CURITIBA',
            'FLORIANÓPOLIS',
            'BELO HORIZONTE',
            'FORTALEZA',
            'RECIFE',
            'JOÃO PESSOA',
            'ARACAJU',
            'NATAL',
            'VITÓRIA'
        );

        //Define o nível, fator e oportunidades de cadastramento

        if ($estado) {
            return array('fator' => 5, 'programas_ofertados' => 120, 'envio_proposta' => 480, 'programas_governo_municipal' => 60, 'programas_organizacoes_sociais' => 40, 'programas_empresas_mistas' => 10, 'programas_consorcios_publicos' => 10, 'propostas_governo_municipal' => 240, 'propostas_organizacoes_sociais' => 160, 'propostas_empresas_mistas' => 40, 'propostas_consorcios_publicos' => 40, 'emendas_parl_espec' => 8, 'propostas_governo_estadual' => 600, 'programas_governo_estadual' => 60);
        } else if (in_array($cidade, $capitais)) {
            return array('fator' => 4, 'programas_ofertados' => 120, 'envio_proposta' => 480, 'programas_governo_municipal' => 60, 'programas_organizacoes_sociais' => 40, 'programas_empresas_mistas' => 10, 'programas_consorcios_publicos' => 10, 'propostas_governo_municipal' => 240, 'propostas_organizacoes_sociais' => 160, 'propostas_empresas_mistas' => 40, 'propostas_consorcios_publicos' => 40, 'emendas_parl_espec' => 8, 'propostas_governo_estadual' => 60, 'programas_governo_estadual' => 60);
        } else if ($habitantes <= 30000) {
            return array('fator' => 0.5, 'programas_ofertados' => 120, 'envio_proposta' => 60, 'programas_governo_municipal' => 60, 'programas_organizacoes_sociais' => 40, 'programas_empresas_mistas' => 10, 'programas_consorcios_publicos' => 10, 'propostas_governo_municipal' => 30, 'propostas_organizacoes_sociais' => 20, 'propostas_empresas_mistas' => 5, 'propostas_consorcios_publicos' => 5, 'emendas_parl_espec' => 1, 'propostas_governo_estadual' => 60, 'programas_governo_estadual' => 60);
        } elseif ($habitantes > 30000 && $habitantes <= 50000) {
            return array('fator' => 1, 'programas_ofertados' => 120, 'envio_proposta' => 120, 'programas_governo_municipal' => 60, 'programas_organizacoes_sociais' => 40, 'programas_empresas_mistas' => 10, 'programas_consorcios_publicos' => 10, 'propostas_governo_municipal' => 60, 'propostas_organizacoes_sociais' => 40, 'propostas_empresas_mistas' => 10, 'propostas_consorcios_publicos' => 10, 'emendas_parl_espec' => 2, 'propostas_governo_estadual' => 60, 'programas_governo_estadual' => 60);
        } elseif ($habitantes > 50000 && $habitantes <= 100000) {
            return array('fator' => 2, 'programas_ofertados' => 120, 'envio_proposta' => 240, 'programas_governo_municipal' => 60, 'programas_organizacoes_sociais' => 40, 'programas_empresas_mistas' => 10, 'programas_consorcios_publicos' => 10, 'propostas_governo_municipal' => 120, 'propostas_organizacoes_sociais' => 80, 'propostas_empresas_mistas' => 20, 'propostas_consorcios_publicos' => 20, 'emendas_parl_espec' => 3, 'propostas_governo_estadual' => 60, 'programas_governo_estadual' => 60);
        } elseif ($habitantes > 100000) {
            return array('fator' => 3, 'programas_ofertados' => 120, 'envio_proposta' => 360, 'programas_governo_municipal' => 60, 'programas_organizacoes_sociais' => 40, 'programas_empresas_mistas' => 10, 'programas_consorcios_publicos' => 10, 'propostas_governo_municipal' => 180, 'propostas_organizacoes_sociais' => 120, 'propostas_empresas_mistas' => 30, 'propostas_consorcios_publicos' => 30, 'emendas_parl_espec' => 5, 'propostas_governo_estadual' => 60, 'programas_governo_estadual' => 60);
        }
    }

    /*
     * Soma a quatidade de itens no intervalo passado
     */

    public function quantidade_intervalo($array, $start, $end, $index = FALSE, $count = FALSE) {
        $result = 0;
        do {
            //Se passar o index especifico, soma utilizando ele, se não soma utilizando o array
            if (isset($array[$start]) && count($array[$start]) > 0) {
                if ($index) {
                    if ($count)
                        $result += count($array[$start][$index]);
                    else
                        $result += $array[$start][$index];
                } else {
                    if ($count)
                        $result += count($array[$start]);
                    else
                        $result += $array[$start];
                }
            }
            $start++;
        } while ($start <= $end);
        return $result;
    }

    public function valor_intervalo_emenda($array, $start, $end, $index = NULL) {
        $result = 0;
        do {
            //Se passar o index especifico, soma utilizando ele, se não soma utilizando o array
            if (key_exists($start, $array)) {
                if (count($array[$start]) > 0) {
                    if ($index) {
                        foreach ($array[$start][$index] as $key => $value) {
                            $result += doubleval(trim(explode("R$", str_replace(',', '.', str_replace('.', '', $value->valor)))[1]));
                        }
                    } else {
                        foreach ($array[$start] as $key => $value) {
                            $result += doubleval(trim(explode("R$", str_replace(',', '.', str_replace('.', '', $value->valor)))[1]));
                        }
                    }
                }
            }
            $start++;
        } while ($start <= $end);
        return $result;
    }

    public function check_emenda_programa_proposta_is_equal($id_proposta_banco_proposta, $codigo_emenda) {
        $this->db->where('id_proposta_banco_proposta', $id_proposta_banco_proposta);
        $query_programa = $this->db->get('programa_banco_proposta');

        if ($query_programa->num_rows > 0) {
            foreach ($query_programa->result() as $prog) {
                $this->db->where('id_programa_banco_proposta', $prog->id_programa_banco_proposta);
                $query_emenda = $this->db->get('emenda_banco_proposta');

                if ($query_emenda->num_rows > 0) {
                    foreach ($query_emenda->result() as $emenda) {
                        if ($emenda->codigo_emenda == $codigo_emenda->emenda) {
                            return true;
                        }
                    }
                }
            }
        }

        return false;
    }

    public function get_dados_siconv($proponente, $regiao, $estado, $cidades_tag, $nivel_e_fator_de_cadastro, $nome_empresa, $tipo, $somente_osc = false, $habitantes = 0, $somente_municipal_diversos = null) {
        $this->load->model('banco_proposta_model', 'banco_proposta');
        //Define array de anos para verificação dos dados
        $anos = array("2018", "2017", "2016", "2015", "2014", "2013", "2012", "2011", "2010", "2009");

        $osc_que_enviaram = array();
        $osc_que_enviaram_por_ano = array();
        $orgaos_que_enviaram = array();
        $municipios_que_enviaram = 0;

        //Declaração de Váriaveis
        $valor_geral_aprovadas = doubleval(0);
        $valor_2017_aprovadas = doubleval(0);
        $valor_2018_a_2017_aprovadas = doubleval(0);
        $valor_2016_a_2013_aprovadas = doubleval(0);
        $valor_2012_a_2009_aprovadas = doubleval(0);
        $quantidade_geral_aprovadas = 0;
        $quantidade_geral_enviadas = 0;
        $quantidade_2017_enviadas = 0;
        $quantidade_2018_a_2017_enviadas = 0;
        $quantidade_2016_a_2013_enviadas = 0;
        $quantidade_2012_a_2009_enviadas = 0;
        $quantidade_2017_aprovadas = 0;
        $quantidade_2018_a_2017_aprovadas = 0;
        $quantidade_2016_a_2013_aprovadas = 0;
        $quantidade_2012_a_2009_aprovadas = 0;
        $dados_cidades_tabela_array = array();

        /*
         * Obtem dados em um intervalo de anos
         */

        foreach ($anos as $ano) {
            // Obtem dados dos programas, propostas e emendas
            $osc_que_enviaram_por_ano[$ano] = array();
            $proposta_cidade[$ano] = $this->get_propostas_por_cidade_geral($proponente, $ano);
            if (!empty($proposta_cidade[$ano])) {
                if ($somente_osc) {
                    foreach ($proposta_cidade[$ano] as $prop_cid) {
                        if (!in_array($prop_cid->proponente, $osc_que_enviaram)) {
                            array_push($osc_que_enviaram, $prop_cid->proponente);
                        }

                        if (!in_array($prop_cid->proponente, $osc_que_enviaram_por_ano[$ano])) {
                            array_push($osc_que_enviaram_por_ano[$ano], $prop_cid->proponente);
                        }
                    }
                } else {
                    foreach ($proposta_cidade[$ano] as $prop_cid) {
                        if (!in_array($prop_cid->proponente, $orgaos_que_enviaram)) {
                            array_push($orgaos_que_enviaram, $prop_cid->proponente);
                        }
                    }
                }
            }

            if ($somente_municipal_diversos != null) {
                $municipios_que_enviaram = count($somente_municipal_diversos);
            }

            $emendas_cidade[$ano] = $this->get_emendas_por_cidade_estado($proponente, $ano, $estado);
            $emenda_parlamentar[$ano] = array();
            $emenda_especifico_concedente[$ano] = array();
            $enviadas[$ano] = 0;
            $aprovadas[$ano] = 0;
            $valor_anual_aprovadas[$ano] = doubleval(0);

            //Filtra as emendas
            if ($emendas_cidade[$ano] != NULL) {
                $soma = doubleval(0);
                $quantidade_emendas = 0;
                $quantidade_emendas_aprovadas = 0;
                $valor_emendas_aprovadas = doubleval(0);
                $quantidade_emendas_analise = 0;
                $valor_emendas_analise = doubleval(0);
                foreach ($emendas_cidade[$ano] as $emenda) {
                    if ($emenda->emenda == "" || $emenda->emenda == null) {
                        array_push($emenda_especifico_concedente[$ano], $emenda);
                    } else {
                        array_push($emenda_parlamentar[$ano], $emenda);
                    }
                }

                foreach ($emenda_especifico_concedente[$ano] as $emenda) {
                    $nutilizada = true;
                    $aux_analise = 0;
                    $aux_valor_analise=0;
                    if ($proposta_cidade[$ano] != NULL && count($proposta_cidade[$ano]) > 0) {
                        foreach ($proposta_cidade[$ano] as $prop) {
                            if ($prop->codigo_programa == $emenda->codigo_programa && $prop->proponente == str_replace('/', '', str_replace('-', '', str_replace('.', '', $emenda->cnpj))) && $prop->ano == $ano && strpos(trim($prop->tipo), trim('Repasse')) == false) {
                                if ($this->banco_proposta->verifica_proposta_aprovada($prop->situacao)) {
                                    $quantidade_emendas_aprovadas++;
                                    $valor_emendas_aprovadas = doubleval($valor_emendas_aprovadas) + doubleval(trim(explode("R$", str_replace(',', '.', str_replace('.', '', $emenda->valor)))[1]));
                                    $nutilizada = FALSE;
                                    $quantidade_emendas_analise -= $aux_analise;
                                    $valor_emendas_analise -= $aux_valor_analise;
                                    continue 2;
                                } elseif ($this->banco_proposta->verifica_proposta_analise($prop->situacao)) {
                                    if ($aux_analise == 0) {
                                        $aux_analise++;
                                        $aux_valor_analise+= doubleval(trim(explode("R$", str_replace(',', '.', str_replace('.', '', $emenda->valor)))[1]));
                                        $quantidade_emendas_analise++;
                                        $valor_emendas_analise = doubleval($valor_emendas_analise) + doubleval(trim(explode("R$", str_replace(',', '.', str_replace('.', '', $emenda->valor)))[1]));
                                        $nutilizada = FALSE;
                                    }
                                }
                            }
                        }
                    }

                    if ($nutilizada) {
                        $quantidade_emendas++;
                        $soma = doubleval($soma) + doubleval(trim(explode("R$", str_replace(',', '.', str_replace('.', '', $emenda->valor)))[1]));
                    }
                }
                foreach ($emenda_parlamentar[$ano] as $emenda) {
                    $nutilizada = true;
                    $aux_analise = 0;
                    if ($proposta_cidade[$ano] != NULL && count($proposta_cidade[$ano]) > 0) {
                        foreach ($proposta_cidade[$ano] as $key => $prop) {
                            if ($prop->codigo_programa == $emenda->codigo_programa && $prop->proponente == str_replace('/', '', str_replace('-', '', str_replace('.', '', $emenda->cnpj))) && $prop->ano == $ano && strpos(trim($prop->tipo), trim('Repasse')) == false && $this->check_emenda_programa_proposta_is_equal($prop->id_proposta, $emenda)) {
                                if ($this->banco_proposta->verifica_proposta_aprovada($prop->situacao)) {
                                    $quantidade_emendas_aprovadas++;
                                    $valor_emendas_aprovadas = doubleval($valor_emendas_aprovadas) + doubleval(trim(explode("R$", str_replace(',', '.', str_replace('.', '', $emenda->valor)))[1]));
                                    $nutilizada = false;
                                    $quantidade_emendas_analise -= $aux_analise;
                                    continue 2;
                                } elseif ($this->banco_proposta->verifica_proposta_analise($prop->situacao)) {
                                    if ($aux_analise == 0) {
                                        $aux_analise++;
                                        $quantidade_emendas_analise++;
                                        $valor_emendas_analise = doubleval($valor_emendas_analise) + doubleval(trim(explode("R$", str_replace(',', '.', str_replace('.', '', $emenda->valor)))[1]));
                                        $nutilizada = false;
                                    }
                                }
                                //unset($aux_proposta_cidade[$key]);
                            }
                        }
                    }
                    if ($nutilizada) {
                        $quantidade_emendas++;
                        $soma = doubleval($soma) + doubleval(trim(explode("R$", str_replace(',', '.', str_replace('.', '', $emenda->valor)))[1]));
                    }
                }

                $array_dados_tabela = array(
                    'quantidade_emendas' => $quantidade_emendas,
                    'soma' => $soma,
                    'quantidade_emendas_aprovadas' => $quantidade_emendas_aprovadas,
                    'quantidade_emendas_analise' => $quantidade_emendas_analise,
                    'valor_emendas_aprovadas' => $valor_emendas_aprovadas,
                    'valor_emendas_analise' => $valor_emendas_analise
                );

                $dados_cidades_tabela_array[$ano] = $array_dados_tabela;
            }



            //Filtra as propostas
            if (count($proposta_cidade[$ano]) > 0) {
                foreach ($proposta_cidade[$ano] as $prop_ano) {
                    if ($this->banco_proposta->verifica_proposta_enviada($prop_ano->situacao)) {
                        $enviadas[$ano] ++;
                        $quantidade_geral_enviadas++;
                        if ($ano == '2018' || $ano == '2017') {
                            $quantidade_2018_a_2017_enviadas++;
                        } elseif ($ano == '2016' || $ano == '2015' || $ano == '2014' || $ano == '2013') {
                            $quantidade_2016_a_2013_enviadas++;
                        } elseif ($ano == '2012' || $ano == '2011' || $ano == '2010' || $ano == '2009') {
                            $quantidade_2012_a_2009_enviadas++;
                        }
                    }
                    if ($this->banco_proposta->verifica_proposta_aprovada($prop_ano->situacao)) {
                        $aprovadas[$ano] ++;
                        $quantidade_geral_aprovadas++;
                        $valor_anual_aprovadas[$ano] = doubleval($valor_anual_aprovadas[$ano]) + doubleval(trim(explode("R$", str_replace(',', '.', str_replace('.', '', $prop_ano->valor_global)))[1]));
                        $valor_geral_aprovadas = doubleval($valor_geral_aprovadas) + doubleval(trim(explode("R$", str_replace(',', '.', str_replace('.', '', $prop_ano->valor_global)))[1]));
                        if ($ano == '2018' || $ano == '2017') {
                            $quantidade_2018_a_2017_aprovadas++;
                            $valor_2018_a_2017_aprovadas = doubleval($valor_2018_a_2017_aprovadas) + doubleval(trim(explode("R$", str_replace(',', '.', str_replace('.', '', $prop_ano->valor_global)))[1]));
                        } elseif ($ano == '2016' || $ano == '2015' || $ano == '2014' || $ano == '2013') {
                            $quantidade_2016_a_2013_aprovadas++;
                            $valor_2016_a_2013_aprovadas = doubleval($valor_2016_a_2013_aprovadas) + doubleval(trim(explode("R$", str_replace(',', '.', str_replace('.', '', $prop_ano->valor_global)))[1]));
                        } elseif ($ano == '2012' || $ano == '2011' || $ano == '2010' || $ano == '2009') {
                            $quantidade_2012_a_2009_aprovadas++;
                            $valor_2012_a_2009_aprovadas = doubleval($valor_2012_a_2009_aprovadas) + doubleval(trim(explode("R$", str_replace(',', '.', str_replace('.', '', $prop_ano->valor_global)))[1]));
                        }
                    }
                }
            }
        }

        if ($somente_osc) {
            ///Osc que enviaram em 2018 a 2017
            $osc_2018_2017 = array();
            $osc_2018_2017 = array_merge($osc_que_enviaram_por_ano['2018'], $osc_que_enviaram_por_ano['2017']);
            $osc_2018_2017 = array_unique($osc_2018_2017);
            $quant_osc_2018_2017 = count($osc_2018_2017);

            //Osc que enviaram em 2016 a 2013
            $osc_2016_2013 = array();
            $osc_2016_2013 = array_merge($osc_que_enviaram_por_ano['2016'], $osc_que_enviaram_por_ano['2015'], $osc_que_enviaram_por_ano['2014'], $osc_que_enviaram_por_ano['2013']);
            $osc_2016_2013 = array_unique($osc_2016_2013);
            $quant_osc_2016_2013 = count($osc_2016_2013);

            //Osc que enviaram em 2012 a 20009
            $osc_2012_2009 = array();
            $osc_2012_2009 = array_merge($osc_que_enviaram_por_ano['2012'], $osc_que_enviaram_por_ano['2011'], $osc_que_enviaram_por_ano['2010'], $osc_que_enviaram_por_ano['2009']);
            $osc_2012_2009 = array_unique($osc_2012_2009);
            $quant_osc_2012_2009 = count($osc_2012_2009);
        } else {
            $quant_osc_2018_2017 = 1;
            $quant_osc_2016_2013 = 1;
            $quant_osc_2012_2009 = 1;
        }

        // Dados para enviar para view
        $dados = array(
            'cidade' => isset($cidades_tag->cidade) ? $cidades_tag->cidade : NULL,
            'estado' => $estado,
            'regiao' => $regiao,
            'nome_empresa' => $nome_empresa,
            'habitantes' => isset($cidades_tag->populacao) ? $cidades_tag->populacao : $habitantes,
            'nivel_e_fator_de_cadastro' => $nivel_e_fator_de_cadastro,
            'esfera' => $tipo != NULL ? array($tipo) : $this->input->post('esfera'),
            'enviadas' => $enviadas,
            'aprovadas' => $aprovadas,
            'enviadas_2018_a_2017' => $quantidade_2018_a_2017_enviadas,
            'enviadas_2016_a_2013' => $quantidade_2016_a_2013_enviadas,
            'enviadas_2012_a_2009' => $quantidade_2012_a_2009_enviadas,
            'quantidade_geral_enviadas' => $quantidade_geral_enviadas,
            'aprovadas_2018_a_2017' => $quantidade_2018_a_2017_aprovadas,
            'aprovadas_2016_a_2013' => $quantidade_2016_a_2013_aprovadas,
            'aprovadas_2012_a_2009' => $quantidade_2012_a_2009_aprovadas,
            'valor_aprovadas_2018_a_2017' => $valor_2018_a_2017_aprovadas,
            'valor_aprovadas_2016_a_2013' => $valor_2016_a_2013_aprovadas,
            'valor_aprovadas_2012_a_2009' => $valor_2012_a_2009_aprovadas,
            'quantidade_geral_aprovadas' => $quantidade_geral_aprovadas,
            'valor_anual_aprovadas' => $valor_anual_aprovadas,
            'valor_geral_aprovadas' => $valor_geral_aprovadas,
            'percentual_enviadas_aprovadas_2017_a_2018' => $quantidade_2018_a_2017_enviadas == 0 ? 0 : ($quantidade_2018_a_2017_aprovadas * 100) / $quantidade_2018_a_2017_enviadas,
            'percentual_enviadas_aprovadas_2009_a_2012' => $quantidade_2012_a_2009_enviadas == 0 ? 0 : ($quantidade_2012_a_2009_aprovadas * 100) / $quantidade_2012_a_2009_enviadas,
            'percentual_enviadas_aprovadas_2013_a_2016' => $quantidade_2016_a_2013_enviadas == 0 ? 0 : ($quantidade_2016_a_2013_aprovadas * 100) / $quantidade_2016_a_2013_enviadas,
            'percentual_enviadas_aprovadas_2009_a_2018' => $quantidade_geral_enviadas == 0 ? 0 : ($quantidade_geral_aprovadas * 100) / $quantidade_geral_enviadas,
            'quantidade_emenda_parlamentar_2017_a_2018' => $this->relatorios_siconv_model->quantidade_intervalo($emenda_parlamentar, 2017, 2018, FALSE, TRUE),
            'quantidade_emenda_parlamentar_2009_a_2012' => $this->relatorios_siconv_model->quantidade_intervalo($emenda_parlamentar, 2009, 2012, FALSE, TRUE),
            'quantidade_emenda_parlamentar_2013_a_2016' => $this->relatorios_siconv_model->quantidade_intervalo($emenda_parlamentar, 2013, 2016, FALSE, TRUE),
            'quantidade_emenda_especifico_2017_a_2018' => $this->relatorios_siconv_model->quantidade_intervalo($emenda_especifico_concedente, 2017, 2018, FALSE, TRUE),
            'quantidade_emenda_especifico_2009_a_2012' => $this->relatorios_siconv_model->quantidade_intervalo($emenda_especifico_concedente, 2009, 2012, FALSE, TRUE),
            'quantidade_emenda_especifico_2013_a_2016' => $this->relatorios_siconv_model->quantidade_intervalo($emenda_especifico_concedente, 2013, 2016, FALSE, TRUE),
            'valor_emenda_2017' => $this->relatorios_siconv_model->valor_intervalo_emenda($emendas_cidade, 2017, 2017),
            'valor_emenda_2009_a_2012' => $this->relatorios_siconv_model->valor_intervalo_emenda($emendas_cidade, 2009, 2012),
            'valor_emenda_2013_a_2016' => $this->relatorios_siconv_model->valor_intervalo_emenda($emendas_cidade, 2013, 2016),
            'quantidade_emendas_aprovadas_2017_a_2018' => $this->relatorios_siconv_model->quantidade_intervalo($dados_cidades_tabela_array, 2017, 2018, 'quantidade_emendas_aprovadas'),
            'quantidade_emendas_aprovadas_2009_a_2012' => $this->relatorios_siconv_model->quantidade_intervalo($dados_cidades_tabela_array, 2009, 2012, 'quantidade_emendas_aprovadas'),
            'quantidade_emendas_aprovadas_2013_a_2016' => $this->relatorios_siconv_model->quantidade_intervalo($dados_cidades_tabela_array, 2013, 2016, 'quantidade_emendas_aprovadas'),
            'quantidade_emendas_analise_2017_a_2018' => $this->relatorios_siconv_model->quantidade_intervalo($dados_cidades_tabela_array, 2017, 2018, 'quantidade_emendas_analise'),
            'quantidade_emendas_analise_2009_a_2012' => $this->relatorios_siconv_model->quantidade_intervalo($dados_cidades_tabela_array, 2009, 2012, 'quantidade_emendas_analise'),
            'quantidade_emendas_analise_2013_a_2016' => $this->relatorios_siconv_model->quantidade_intervalo($dados_cidades_tabela_array, 2013, 2016, 'quantidade_emendas_analise'),
            'valor_emendas_aprovadas_2017_a_2018' => $this->relatorios_siconv_model->quantidade_intervalo($dados_cidades_tabela_array, 2017, 2018, 'valor_emendas_aprovadas'),
            'valor_emendas_aprovadas_2009_a_2012' => $this->relatorios_siconv_model->quantidade_intervalo($dados_cidades_tabela_array, 2009, 2012, 'valor_emendas_aprovadas'),
            'valor_emendas_aprovadas_2013_a_2016' => $this->relatorios_siconv_model->quantidade_intervalo($dados_cidades_tabela_array, 2013, 2016, 'valor_emendas_aprovadas'),
            'valor_emendas_analise_2017_a_2018' => $this->relatorios_siconv_model->quantidade_intervalo($dados_cidades_tabela_array, 2017, 2018, 'valor_emendas_analise'),
            'valor_emendas_analise_2009_a_2012' => $this->relatorios_siconv_model->quantidade_intervalo($dados_cidades_tabela_array, 2009, 2012, 'valor_emendas_analise'),
            'valor_emendas_analise_2013_a_2016' => $this->relatorios_siconv_model->quantidade_intervalo($dados_cidades_tabela_array, 2013, 2016, 'valor_emendas_analise'),
            'quantidade_perda_emenda_2017_a_2018' => $this->relatorios_siconv_model->quantidade_intervalo($dados_cidades_tabela_array, 2017, 2018, 'quantidade_emendas'),
            'quantidade_perda_emenda_2009_a_2012' => $this->relatorios_siconv_model->quantidade_intervalo($dados_cidades_tabela_array, 2009, 2012, 'quantidade_emendas'),
            'quantidade_perda_emenda_2013_a_2016' => $this->relatorios_siconv_model->quantidade_intervalo($dados_cidades_tabela_array, 2013, 2016, 'quantidade_emendas'),
            'valor_perda_emenda_2017_a_2018' => $this->relatorios_siconv_model->quantidade_intervalo($dados_cidades_tabela_array, 2017, 2018, 'soma'),
            'valor_perda_emenda_2009_a_2012' => $this->relatorios_siconv_model->quantidade_intervalo($dados_cidades_tabela_array, 2009, 2012, 'soma'),
            'valor_perda_emenda_2013_a_2016' => $this->relatorios_siconv_model->quantidade_intervalo($dados_cidades_tabela_array, 2013, 2016, 'soma'),
        );

        $dados['quantidade_emenda_parlamentar_2009_a_2018'] = $dados['quantidade_emenda_parlamentar_2009_a_2012'] + $dados['quantidade_emenda_parlamentar_2013_a_2016'] + $dados['quantidade_emenda_parlamentar_2017_a_2018'];
        $dados['quantidade_emenda_especifico_2009_a_2018'] = $dados['quantidade_emenda_especifico_2009_a_2012'] + $dados['quantidade_emenda_especifico_2013_a_2016'] + $dados['quantidade_emenda_especifico_2017_a_2018'];
        $dados['valor_emenda_2009_a_2016'] = $dados['valor_emenda_2009_a_2012'] + $dados['valor_emenda_2013_a_2016'];
        $dados['quantidade_emendas_aprovadas_2009_a_2018'] = $dados['quantidade_emendas_aprovadas_2009_a_2012'] + $dados['quantidade_emendas_aprovadas_2013_a_2016'] + $dados['quantidade_emendas_aprovadas_2017_a_2018'];
        $dados['quantidade_emendas_analise_2009_a_2018'] = $dados['quantidade_emendas_analise_2009_a_2012'] + $dados['quantidade_emendas_analise_2013_a_2016'] + $dados['quantidade_emendas_analise_2017_a_2018'];
        $dados['valor_emendas_aprovadas_2009_a_2018'] = $dados['valor_emendas_aprovadas_2009_a_2012'] + $dados['valor_emendas_aprovadas_2013_a_2016'] + $dados['valor_emendas_aprovadas_2017_a_2018'];
        $dados['valor_emendas_analise_2009_a_2018'] = $dados['valor_emendas_analise_2009_a_2012'] + $dados['valor_emendas_analise_2013_a_2016'] + $dados['valor_emendas_analise_2017_a_2018'];
        $dados['quantidade_perda_emenda_2009_a_2018'] = $dados['quantidade_perda_emenda_2009_a_2012'] + $dados['quantidade_perda_emenda_2013_a_2016'] + $dados['quantidade_perda_emenda_2017_a_2018'];
        $dados['valor_perda_emenda_2009_a_2018'] = $dados['valor_perda_emenda_2009_a_2012'] + $dados['valor_perda_emenda_2013_a_2016'] + $dados['valor_perda_emenda_2017_a_2018'];

        if (count($this->input->post('esfera')) == 4 || $tipo == 'TODAS') {
            $dados['percentual_enviadas_2017_2018'] = ($quantidade_2018_a_2017_enviadas * 100) / ($nivel_e_fator_de_cadastro['envio_proposta'] * 2);
            $dados['percentual_enviadas_2009_a_2012'] = ($quantidade_2012_a_2009_enviadas * 100) / ($nivel_e_fator_de_cadastro['envio_proposta'] * 4);
            $dados['percentual_enviadas_2013_a_2016'] = ($quantidade_2016_a_2013_enviadas * 100) / ($nivel_e_fator_de_cadastro['envio_proposta'] * 4);
            $dados['percentual_enviadas_2009_a_2018'] = ($quantidade_geral_enviadas * 100) / ($nivel_e_fator_de_cadastro['envio_proposta'] * 10);
            $dados['percentual_aprovadas_2017_a_2018'] = ($quantidade_2018_a_2017_aprovadas * 100) / ($nivel_e_fator_de_cadastro['envio_proposta'] * 2);
            $dados['percentual_aprovadas_2009_a_2012'] = ($quantidade_2012_a_2009_aprovadas * 100) / ($nivel_e_fator_de_cadastro['envio_proposta'] * 4);
            $dados['percentual_aprovadas_2013_a_2016'] = ($quantidade_2016_a_2013_aprovadas * 100) / ($nivel_e_fator_de_cadastro['envio_proposta'] * 4);
            $dados['percentual_aprovadas_2009_a_2018'] = ($quantidade_geral_aprovadas * 100) / ($nivel_e_fator_de_cadastro['envio_proposta'] * 10);
            $dados['perdas_propostas_voluntarias_2017_2018'] = (($nivel_e_fator_de_cadastro['envio_proposta']) - $quantidade_2018_a_2017_enviadas);
            $dados['perdas_propostas_voluntarias_2009_2012'] = (($nivel_e_fator_de_cadastro['envio_proposta'] * 4) - $quantidade_2012_a_2009_enviadas);
            $dados['perdas_propostas_voluntarias_2013_2016'] = (($nivel_e_fator_de_cadastro['envio_proposta'] * 4) - $quantidade_2016_a_2013_enviadas);
            $dados['perdas_propostas_voluntarias'] = (($nivel_e_fator_de_cadastro['envio_proposta'] * 8) - $quantidade_geral_enviadas) + ($dados['quantidade_emenda_parlamentar_2009_a_2018'] + $dados['quantidade_emenda_especifico_2009_a_2018']);
        } elseif ($tipo == 'MUNICIPAL' || ($this->input->post('esfera') != NULL && count($this->input->post('esfera')) != 4 && in_array('MUNICIPAL', $this->input->post('esfera')))) {
            $dados['percentual_enviadas_2017_2018'] = ($quantidade_2018_a_2017_enviadas * 100) / ($nivel_e_fator_de_cadastro['propostas_governo_municipal'] * 2);
            $dados['percentual_enviadas_2009_a_2012'] = ($quantidade_2012_a_2009_enviadas * 100) / ($nivel_e_fator_de_cadastro['propostas_governo_municipal'] * 4);
            $dados['percentual_enviadas_2013_a_2016'] = ($quantidade_2016_a_2013_enviadas * 100) / ($nivel_e_fator_de_cadastro['propostas_governo_municipal'] * 4);
            $dados['percentual_enviadas_2009_a_2018'] = ($quantidade_geral_enviadas * 100) / ($nivel_e_fator_de_cadastro['propostas_governo_municipal'] * 10);
            $dados['percentual_aprovadas_2017_a_2018'] = ($quantidade_2018_a_2017_aprovadas * 100) / ($nivel_e_fator_de_cadastro['propostas_governo_municipal'] * 2);
            $dados['percentual_aprovadas_2009_a_2012'] = ($quantidade_2012_a_2009_aprovadas * 100) / ($nivel_e_fator_de_cadastro['propostas_governo_municipal'] * 4);
            $dados['percentual_aprovadas_2013_a_2016'] = ($quantidade_2016_a_2013_aprovadas * 100) / ($nivel_e_fator_de_cadastro['propostas_governo_municipal'] * 4);
            $dados['percentual_aprovadas_2009_a_2018'] = ($quantidade_geral_aprovadas * 100) / ($nivel_e_fator_de_cadastro['propostas_governo_municipal'] * 10);
            $dados['perdas_propostas_voluntarias_2017_2018'] = (($nivel_e_fator_de_cadastro['propostas_governo_municipal']) - $quantidade_2018_a_2017_enviadas);
            $dados['perdas_propostas_voluntarias_2009_2012'] = (($nivel_e_fator_de_cadastro['propostas_governo_municipal'] * 4) - $quantidade_2012_a_2009_enviadas);
            $dados['perdas_propostas_voluntarias_2013_2016'] = (($nivel_e_fator_de_cadastro['propostas_governo_municipal'] * 4) - $quantidade_2016_a_2013_enviadas);
            $dados['perdas_propostas_voluntarias'] = (($nivel_e_fator_de_cadastro['propostas_governo_municipal'] * 8) - $quantidade_geral_enviadas) + ($dados['quantidade_emenda_parlamentar_2009_a_2018'] + $dados['quantidade_emenda_especifico_2009_a_2018']);
        } elseif ($tipo == 'PRIVADA' || ($this->input->post('esfera') != NULL && count($this->input->post('esfera')) != 4 && in_array('PRIVADA', $this->input->post('esfera')))) {
            if ($quant_osc_2018_2017 != 0) {
                $dados['percentual_enviadas_2017_2018'] = (($quantidade_2018_a_2017_enviadas * 100) / ($nivel_e_fator_de_cadastro['propostas_organizacoes_sociais'] * 2)) / $quant_osc_2018_2017;
            } else {
                $dados['percentual_enviadas_2017_2018'] = 0;
            }

            if ($quant_osc_2012_2009 != 0) {
                $dados['percentual_enviadas_2009_a_2012'] = (($quantidade_2012_a_2009_enviadas * 100) / ($nivel_e_fator_de_cadastro['propostas_organizacoes_sociais'] * 4)) / $quant_osc_2012_2009;
            } else {
                $dados['percentual_enviadas_2009_a_2012'] = 0;
            }

            if ($quant_osc_2016_2013 != 0) {
                $dados['percentual_enviadas_2013_a_2016'] = (($quantidade_2016_a_2013_enviadas * 100) / ($nivel_e_fator_de_cadastro['propostas_organizacoes_sociais'] * 4)) / $quant_osc_2016_2013;
            } else {
                $dados['percentual_enviadas_2013_a_2016'] = 0;
            }

            if (($quant_osc_2012_2009 + $quant_osc_2016_2013 + $quant_osc_2018_2017) != 0) {
                $dados['percentual_enviadas_2009_a_2018'] = (($quantidade_geral_enviadas * 100) / ($nivel_e_fator_de_cadastro['propostas_organizacoes_sociais'] * 10)) / ($quant_osc_2012_2009 + $quant_osc_2016_2013 + $quant_osc_2018_2017);
            } else {
                $dados['percentual_enviadas_2009_a_2018'] = 0;
            }
            $dados['percentual_aprovadas_2017_a_2018'] = (($quantidade_2018_a_2017_aprovadas * 100) / ($nivel_e_fator_de_cadastro['propostas_organizacoes_sociais'] * 2)) / $quant_osc_2018_2017;
            $dados['percentual_aprovadas_2009_a_2012'] = (($quantidade_2012_a_2009_aprovadas * 100) / ($nivel_e_fator_de_cadastro['propostas_organizacoes_sociais'] * 4)) / $quant_osc_2012_2009;
            $dados['percentual_aprovadas_2013_a_2016'] = (($quantidade_2016_a_2013_aprovadas * 100) / ($nivel_e_fator_de_cadastro['propostas_organizacoes_sociais'] * 4)) / $quant_osc_2016_2013;
            $dados['percentual_aprovadas_2009_a_2018'] = (($quantidade_geral_aprovadas * 100) / ($nivel_e_fator_de_cadastro['propostas_organizacoes_sociais'] * 10)) / ($quant_osc_2012_2009 + $quant_osc_2016_2013 + $quant_osc_2018_2017);
            $dados['perdas_propostas_voluntarias_2017_2018'] = (($nivel_e_fator_de_cadastro['propostas_organizacoes_sociais']) - $quantidade_2018_a_2017_enviadas);
            $dados['perdas_propostas_voluntarias_2009_2012'] = (($nivel_e_fator_de_cadastro['propostas_organizacoes_sociais'] * 4) - $quantidade_2012_a_2009_enviadas);
            $dados['perdas_propostas_voluntarias_2013_2016'] = (($nivel_e_fator_de_cadastro['propostas_organizacoes_sociais'] * 4) - $quantidade_2016_a_2013_enviadas);
            $dados['perdas_propostas_voluntarias'] = (($nivel_e_fator_de_cadastro['propostas_organizacoes_sociais'] * 8) - $quantidade_geral_enviadas) + ($dados['quantidade_emenda_parlamentar_2009_a_2018'] + $dados['quantidade_emenda_especifico_2009_a_2018']);
        } elseif ($tipo == 'EMPRESA PUBLICA SOCIEDADE ECONOMIA MISTA' || ($this->input->post('esfera') != NULL && count($this->input->post('esfera')) != 4 && in_array('EMPRESA PUBLICA SOCIEDADE ECONOMIA MISTA', $this->input->post('esfera')))) {
            $dados['percentual_enviadas_2017_2018'] = ($quantidade_2018_a_2017_enviadas * 100) / ($nivel_e_fator_de_cadastro['propostas_empresas_mistas'] * 2);
            $dados['percentual_enviadas_2009_a_2012'] = ($quantidade_2012_a_2009_enviadas * 100) / ($nivel_e_fator_de_cadastro['propostas_empresas_mistas'] * 4);
            $dados['percentual_enviadas_2013_a_2016'] = ($quantidade_2016_a_2013_enviadas * 100) / ($nivel_e_fator_de_cadastro['propostas_empresas_mistas'] * 4);
            $dados['percentual_enviadas_2009_a_2018'] = ($quantidade_geral_enviadas * 100) / ($nivel_e_fator_de_cadastro['propostas_empresas_mistas'] * 10);
            $dados['percentual_aprovadas_2017_a_2018'] = ($quantidade_2018_a_2017_aprovadas * 100) / ($nivel_e_fator_de_cadastro['propostas_empresas_mistas'] * 2);
            $dados['percentual_aprovadas_2009_a_2012'] = ($quantidade_2012_a_2009_aprovadas * 100) / ($nivel_e_fator_de_cadastro['propostas_empresas_mistas'] * 4);
            $dados['percentual_aprovadas_2013_a_2016'] = ($quantidade_2016_a_2013_aprovadas * 100) / ($nivel_e_fator_de_cadastro['propostas_empresas_mistas'] * 4);
            $dados['percentual_aprovadas_2009_a_2018'] = ($quantidade_geral_aprovadas * 100) / ($nivel_e_fator_de_cadastro['propostas_empresas_mistas'] * 10);
            $dados['perdas_propostas_voluntarias_2017_2018'] = (($nivel_e_fator_de_cadastro['propostas_empresas_mistas']) - $quantidade_2018_a_2017_enviadas);
            $dados['perdas_propostas_voluntarias_2009_2012'] = (($nivel_e_fator_de_cadastro['propostas_empresas_mistas'] * 4) - $quantidade_2012_a_2009_enviadas);
            $dados['perdas_propostas_voluntarias_2013_2016'] = (($nivel_e_fator_de_cadastro['propostas_empresas_mistas'] * 4) - $quantidade_2016_a_2013_enviadas);
            $dados['perdas_propostas_voluntarias'] = (($nivel_e_fator_de_cadastro['propostas_empresas_mistas'] * 8) - $quantidade_geral_enviadas) + ($dados['quantidade_emenda_parlamentar_2009_a_2018'] + $dados['quantidade_emenda_especifico_2009_a_2018']);
        } elseif ($tipo == 'CONSORCIO PUBLICO' || ($this->input->post('esfera') != NULL && count($this->input->post('esfera')) != 4 && in_array('CONSORCIO PUBLICO', $this->input->post('esfera')))) {
            $dados['percentual_enviadas_2017_2018'] = ($quantidade_2018_a_2017_enviadas * 100) / ($nivel_e_fator_de_cadastro['propostas_consorcios_publicos'] * 2);
            $dados['percentual_enviadas_2009_a_2012'] = ($quantidade_2012_a_2009_enviadas * 100) / ($nivel_e_fator_de_cadastro['propostas_consorcios_publicos'] * 4);
            $dados['percentual_enviadas_2013_a_2016'] = ($quantidade_2016_a_2013_enviadas * 100) / ($nivel_e_fator_de_cadastro['propostas_consorcios_publicos'] * 4);
            $dados['percentual_enviadas_2009_a_2018'] = ($quantidade_geral_enviadas * 100) / ($nivel_e_fator_de_cadastro['propostas_consorcios_publicos'] * 10);
            $dados['percentual_aprovadas_2017_a_2018'] = ($quantidade_2018_a_2017_aprovadas * 100) / ($nivel_e_fator_de_cadastro['propostas_consorcios_publicos'] * 2);
            $dados['percentual_aprovadas_2009_a_2012'] = ($quantidade_2012_a_2009_aprovadas * 100) / ($nivel_e_fator_de_cadastro['propostas_consorcios_publicos'] * 4);
            $dados['percentual_aprovadas_2013_a_2016'] = ($quantidade_2016_a_2013_aprovadas * 100) / ($nivel_e_fator_de_cadastro['propostas_consorcios_publicos'] * 4);
            $dados['percentual_aprovadas_2009_a_2018'] = ($quantidade_geral_aprovadas * 100) / ($nivel_e_fator_de_cadastro['propostas_consorcios_publicos'] * 10);
            $dados['perdas_propostas_voluntarias_2017_2018'] = (($nivel_e_fator_de_cadastro['propostas_consorcios_publicos']) - $quantidade_2018_a_2017_enviadas);
            $dados['perdas_propostas_voluntarias_2009_2012'] = (($nivel_e_fator_de_cadastro['propostas_consorcios_publicos'] * 4) - $quantidade_2012_a_2009_enviadas);
            $dados['perdas_propostas_voluntarias_2013_2016'] = (($nivel_e_fator_de_cadastro['propostas_consorcios_publicos'] * 4) - $quantidade_2016_a_2013_enviadas);
            $dados['perdas_propostas_voluntarias'] = (($nivel_e_fator_de_cadastro['propostas_consorcios_publicos'] * 8) - $quantidade_geral_enviadas) + ($dados['quantidade_emenda_parlamentar_2009_a_2018'] + $dados['quantidade_emenda_especifico_2009_a_2018']);
        } elseif ($tipo == 'ESTADUAL' || ($this->input->post('esfera') != NULL && count($this->input->post('esfera')) != 4 && in_array('ESTADUAL', $this->input->post('esfera')))) {
            $dados['percentual_enviadas_2017_2018'] = ($quantidade_2018_a_2017_enviadas * 100) / ($nivel_e_fator_de_cadastro['propostas_governo_estadual'] * 2);
            $dados['percentual_enviadas_2009_a_2012'] = ($quantidade_2012_a_2009_enviadas * 100) / ($nivel_e_fator_de_cadastro['propostas_governo_estadual'] * 4);
            $dados['percentual_enviadas_2013_a_2016'] = ($quantidade_2016_a_2013_enviadas * 100) / ($nivel_e_fator_de_cadastro['propostas_governo_estadual'] * 4);
            $dados['percentual_enviadas_2009_a_2018'] = ($quantidade_geral_enviadas * 100) / ($nivel_e_fator_de_cadastro['propostas_governo_estadual'] * 10);
            $dados['percentual_aprovadas_2017_a_2018'] = ($quantidade_2018_a_2017_aprovadas * 100) / ($nivel_e_fator_de_cadastro['propostas_governo_estadual'] * 2);
            $dados['percentual_aprovadas_2009_a_2012'] = ($quantidade_2012_a_2009_aprovadas * 100) / ($nivel_e_fator_de_cadastro['propostas_governo_estadual'] * 4);
            $dados['percentual_aprovadas_2013_a_2016'] = ($quantidade_2016_a_2013_aprovadas * 100) / ($nivel_e_fator_de_cadastro['propostas_governo_estadual'] * 4);
            $dados['percentual_aprovadas_2009_a_2018'] = ($quantidade_geral_aprovadas * 100) / ($nivel_e_fator_de_cadastro['propostas_governo_estadual'] * 10);
            $dados['perdas_propostas_voluntarias_2017_2018'] = (($nivel_e_fator_de_cadastro['propostas_governo_estadual']) - $quantidade_2018_a_2017_enviadas);
            $dados['perdas_propostas_voluntarias_2009_2012'] = (($nivel_e_fator_de_cadastro['propostas_governo_estadual'] * 4) - $quantidade_2012_a_2009_enviadas);
            $dados['perdas_propostas_voluntarias_2013_2016'] = (($nivel_e_fator_de_cadastro['propostas_governo_estadual'] * 4) - $quantidade_2016_a_2013_enviadas);
            $dados['perdas_propostas_voluntarias'] = (($nivel_e_fator_de_cadastro['propostas_governo_estadual'] * 8) - $quantidade_geral_enviadas) + ($dados['quantidade_emenda_parlamentar_2009_a_2018'] + $dados['quantidade_emenda_especifico_2009_a_2018']);
        }

        if ($somente_osc) {
            $dados['total_osc_enviaram'] = count($osc_que_enviaram);
        } else {
            $dados['total_orgaos_enviaram'] = count($orgaos_que_enviaram);
        }

        if ($somente_municipal_diversos != null) {
            $dados['total_municipios_enviaram'] = $municipios_que_enviaram;
        }

        return $dados;
    }

    public function get_proponentes_estado($esfera, $uf, $tipo = "", $id_usuario = 0, $vinculaParaSubgestor = false, $cidades) {
        $this->load->model('usuariomodel');
        $this->load->model('programa_model');

        if ($tipo == "GESTOR" && $id_usuario > 0) {
            $cnpjs = $this->usuariomodel->get_cnpjs_gestor_by_usuario($id_usuario);
            $listaCNPJs = array();
            $listaCNPJsSub = array();
            foreach ($cnpjs as $cnpj) {
                $listaCNPJs[] = $this->programa_model->formatCPFCNPJ($cnpj->cnpj);

                if ($id_usuario == $cnpj->id)
                    $listaCNPJsSub[] = $this->programa_model->formatCPFCNPJ($cnpj->cnpj);
            }

            if (!empty($listaCNPJs)) {
                if (!$vinculaParaSubgestor)
                    $this->db->where_not_in('cnpj', $listaCNPJs);
                else {
                    $this->db->where_in('cnpj', $listaCNPJs);
                    if (!empty($listaCNPJsSub))
                        $this->db->where_not_in('cnpj', $listaCNPJsSub);
                }
            }
        }

        $this->db->select("cnpj, nome");

        $this->db->where('municipio_uf_sigla', $uf);

        if ($esfera != "") {
            $this->db->where_in('esfera_administrativa', $esfera);

            $this->db->order_by("nome", "ASC");
            $query = $this->db->get('proponente_siconv')->result();
            //print_r($this->db->last_query());die;
            return $query;
        } else {
            return array(0);
        }
    }

    public function get_proponentes_cidade($esfera, $cidade, $tipo = "", $id_usuario = 0, $vinculaParaSubgestor = false, $cidades) {
        $this->load->model('usuariomodel');
        $this->load->model('programa_model');

        if ($tipo == "GESTOR" && $id_usuario > 0) {
            $cnpjs = $this->usuariomodel->get_cnpjs_gestor_by_usuario($id_usuario);
            $listaCNPJs = array();
            $listaCNPJsSub = array();
            foreach ($cnpjs as $cnpj) {
                $listaCNPJs[] = $this->programa_model->formatCPFCNPJ($cnpj->cnpj);

                if ($id_usuario == $cnpj->id)
                    $listaCNPJsSub[] = $this->programa_model->formatCPFCNPJ($cnpj->cnpj);
            }

            if (!empty($listaCNPJs)) {
                if (!$vinculaParaSubgestor)
                    $this->db->where_not_in('cnpj', $listaCNPJs);
                else {
                    $this->db->where_in('cnpj', $listaCNPJs);
                    if (!empty($listaCNPJsSub))
                        $this->db->where_not_in('cnpj', $listaCNPJsSub);
                }
            }
        }

        $this->db->select("cnpj, nome");

        $this->db->where('codigo_municipio', $cidade);

        if ($esfera != "") {
            $this->db->where_in('esfera_administrativa', $esfera);

            $this->db->order_by("nome", "ASC");
            $query = $this->db->get('proponente_siconv')->result();
            //print_r($this->db->last_query());die;
            return $query;
        } else {
            return array(0);
        }
    }

    public function get_dados_siconv_apresentacao($proponente, $regiao, $estado, $cidades_tag, $nivel_e_fator_de_cadastro, $nome_empresa, $tipo) {
        $this->load->model('banco_proposta_model', 'banco_proposta');
        //Define array de anos para verificação dos dados
        $anos = array("2018");

        //Declaração de Váriaveis
        $valor_geral_aprovadas = doubleval(0);
        $valor_2018_aprovadas = doubleval(0);
        $valor_2016_a_2013_aprovadas = doubleval(0);
        $valor_2012_a_2009_aprovadas = doubleval(0);
        $quantidade_geral_aprovadas = 0;
        $quantidade_geral_enviadas = 0;
        $quantidade_2018_enviadas = 0;
        $quantidade_2016_a_2013_enviadas = 0;
        $quantidade_2012_a_2009_enviadas = 0;
        $quantidade_2018_aprovadas = 0;
        $quantidade_2016_a_2013_aprovadas = 0;
        $quantidade_2012_a_2009_aprovadas = 0;
        $dados_cidades_tabela_array = array();

        /*
         * Obtem dados em um intervalo de anos
         */

        foreach ($anos as $ano) {


            // Obtem dados dos programas, propostas e emendas
            $proposta_cidade[$ano] = $this->get_propostas_por_cidade_geral($proponente, $ano);
            $emendas_cidade[$ano] = $this->get_emendas_por_cidade($proponente, $ano);
            $emenda_parlamentar[$ano] = array();
            $emenda_especifico_concedente[$ano] = array();
            $enviadas[$ano] = 0;
            $aprovadas[$ano] = 0;
            $valor_anual_aprovadas[$ano] = doubleval(0);

            //Filtra as emendas
            if ($emendas_cidade[$ano] != NULL) {
                $soma = doubleval(0);
                $quantidade_emendas = 0;
                $quantidade_emendas_aprovadas = 0;
                $valor_emendas_aprovadas = doubleval(0);
                $quantidade_emendas_analise = 0;
                $valor_emendas_analise = doubleval(0);
                foreach ($emendas_cidade[$ano] as $emenda) {
                    if ($emenda->emenda == "") {
                        array_push($emenda_especifico_concedente[$ano], $emenda);
                    } else {
                        array_push($emenda_parlamentar[$ano], $emenda);
                    }
                }
                foreach ($emenda_especifico_concedente[$ano] as $emenda) {
                    $nutilizada = true;
                    $aux_analise = 0;
                    if ($proposta_cidade[$ano] != NULL && count($proposta_cidade[$ano]) > 0) {
                        foreach ($proposta_cidade[$ano] as $prop) {
                            if ($prop->codigo_programa == $emenda->codigo_programa && $prop->proponente == str_replace('/', '', str_replace('-', '', str_replace('.', '', $emenda->cnpj))) && $prop->ano == $ano && strpos(trim($prop->tipo), trim('Repasse')) == false && $prop->valor_repasse == $emenda->valor) {
                                if ($this->banco_proposta->verifica_proposta_aprovada($prop->situacao)) {
                                    $quantidade_emendas_aprovadas++;
                                    $valor_emendas_aprovadas = doubleval($valor_emendas_aprovadas) + doubleval(trim(explode("R$", str_replace(',', '.', str_replace('.', '', $emenda->valor)))[1]));
                                    $nutilizada = FALSE;
                                    $quantidade_emendas_analise -= $aux_analise;
                                    continue 2;
                                } elseif ($this->banco_proposta->verifica_proposta_analise($prop->situacao)) {
                                    $aux_analise++;
                                    $quantidade_emendas_analise++;
                                    $valor_emendas_analise = doubleval($valor_emendas_analise) + doubleval(trim(explode("R$", str_replace(',', '.', str_replace('.', '', $emenda->valor)))[1]));
                                    $nutilizada = FALSE;
                                }
                            }
                        }
                    }

                    if ($nutilizada) {
                        $quantidade_emendas++;
                        $soma = doubleval($soma) + doubleval(trim(explode("R$", str_replace(',', '.', str_replace('.', '', $emenda->valor)))[1]));
                    }
                }

                foreach ($emenda_parlamentar[$ano] as $emenda) {
                    $nutilizada = true;
                    $aux_analise = 0;
                    if ($proposta_cidade[$ano] != NULL && count($proposta_cidade[$ano]) > 0) {
                        foreach ($proposta_cidade[$ano] as $key => $prop) {
                            if ($prop->codigo_programa == $emenda->codigo_programa && $prop->proponente == str_replace('/', '', str_replace('-', '', str_replace('.', '', $emenda->cnpj))) && $prop->ano == $ano && strpos(trim($prop->tipo), trim('Repasse')) == false && $prop->valor_repasse == $emenda->valor) {
                                if ($this->banco_proposta->verifica_proposta_aprovada($prop->situacao)) {
                                    $quantidade_emendas_aprovadas++;
                                    $valor_emendas_aprovadas = doubleval($valor_emendas_aprovadas) + doubleval(trim(explode("R$", str_replace(',', '.', str_replace('.', '', $emenda->valor)))[1]));
                                    $nutilizada = false;
                                    $quantidade_emendas_analise -= $aux_analise;
                                    continue 2;
                                } elseif ($this->banco_proposta->verifica_proposta_analise($prop->situacao)) {
                                    $aux_analise++;
                                    $quantidade_emendas_analise++;
                                    $valor_emendas_analise = doubleval($valor_emendas_analise) + doubleval(trim(explode("R$", str_replace(',', '.', str_replace('.', '', $emenda->valor)))[1]));
                                    $nutilizada = false;
                                }
                                //unset($aux_proposta_cidade[$key]);
                            }
                        }
                    }
                    if ($nutilizada) {
                        $quantidade_emendas++;
                        $soma = doubleval($soma) + doubleval(trim(explode("R$", str_replace(',', '.', str_replace('.', '', $emenda->valor)))[1]));
                    }
                }

                $array_dados_tabela = array(
                    'quantidade_emendas' => $quantidade_emendas,
                    'soma' => $soma,
                    'quantidade_emendas_aprovadas' => $quantidade_emendas_aprovadas,
                    'quantidade_emendas_analise' => $quantidade_emendas_analise,
                    'valor_emendas_aprovadas' => $valor_emendas_aprovadas,
                    'valor_emendas_analise' => $valor_emendas_analise
                );

                $dados_cidades_tabela_array[$ano] = $array_dados_tabela;
            }


            //Filtra as propostas
            if (count($proposta_cidade[$ano]) > 0) {
                foreach ($proposta_cidade[$ano] as $prop_ano) {
                    if ($this->banco_proposta->verifica_proposta_enviada($prop_ano->situacao)) {
                        $enviadas[$ano] ++;
                        $quantidade_geral_enviadas++;
                        if ($ano == '2018') {
                            $quantidade_2018_enviadas++;
                        } elseif ($ano == '2016' || $ano == '2015' || $ano == '2014' || $ano == '2013') {
                            $quantidade_2016_a_2013_enviadas++;
                        } elseif ($ano == '2012' || $ano == '2011' || $ano == '2010' || $ano == '2009') {
                            $quantidade_2012_a_2009_enviadas++;
                        }
                    }
                    if ($this->banco_proposta->verifica_proposta_aprovada($prop_ano->situacao)) {
                        $aprovadas[$ano] ++;
                        $quantidade_geral_aprovadas++;
                        $valor_anual_aprovadas[$ano] = doubleval($valor_anual_aprovadas[$ano]) + doubleval(trim(explode("R$", str_replace(',', '.', str_replace('.', '', $prop_ano->valor_global)))[1]));
                        $valor_geral_aprovadas = doubleval($valor_geral_aprovadas) + doubleval(trim(explode("R$", str_replace(',', '.', str_replace('.', '', $prop_ano->valor_global)))[1]));
                        if ($ano == '2018') {
                            $quantidade_2018_aprovadas++;
                            $valor_2017_aprovadas = doubleval($valor_2017_aprovadas) + doubleval(trim(explode("R$", str_replace(',', '.', str_replace('.', '', $prop_ano->valor_global)))[1]));
                        } elseif ($ano == '2016' || $ano == '2015' || $ano == '2014' || $ano == '2013') {
                            $quantidade_2016_a_2013_aprovadas++;
                            $valor_2016_a_2013_aprovadas = doubleval($valor_2016_a_2013_aprovadas) + doubleval(trim(explode("R$", str_replace(',', '.', str_replace('.', '', $prop_ano->valor_global)))[1]));
                        } elseif ($ano == '2012' || $ano == '2011' || $ano == '2010' || $ano == '2009') {
                            $quantidade_2012_a_2009_aprovadas++;
                            $valor_2012_a_2009_aprovadas = doubleval($valor_2012_a_2009_aprovadas) + doubleval(trim(explode("R$", str_replace(',', '.', str_replace('.', '', $prop_ano->valor_global)))[1]));
                        }
                    }
                }
            }
        }

        // Dados para enviar para view
        $dados = array(
            'cidade' => isset($cidades_tag->cidade) ? $cidades_tag->cidade : NULL,
            'estado' => $estado,
            'regiao' => $regiao,
            'nome_empresa' => $nome_empresa,
            'habitantes' => isset($cidades_tag->populacao) ? $cidades_tag->populacao : NULL,
            'nivel_e_fator_de_cadastro' => $nivel_e_fator_de_cadastro,
            'esfera' => $tipo != NULL ? array($tipo) : $this->input->post('esfera'),
            'enviadas' => $enviadas,
            'aprovadas' => $aprovadas,
            'valor_anual_aprovadas' => $valor_anual_aprovadas,
            'percentual_enviadas_aprovadas_2018' => $quantidade_2018_enviadas == 0 ? 0 : ($quantidade_2018_aprovadas * 100) / $quantidade_2018_enviadas,
            'quantidade_emenda_parlamentar_2018' => $this->relatorios_siconv_model->quantidade_intervalo($emenda_parlamentar, 2018, 2018, FALSE, TRUE),
            'quantidade_emenda_especifico_2018' => $this->relatorios_siconv_model->quantidade_intervalo($emenda_especifico_concedente, 2018, 2018, FALSE, TRUE),
            'valor_emenda_2018' => $this->relatorios_siconv_model->valor_intervalo_emenda($emendas_cidade, 2018, 2018),
            'quantidade_emendas_aprovadas_2018' => $this->relatorios_siconv_model->quantidade_intervalo($dados_cidades_tabela_array, 2018, 2018, 'quantidade_emendas_aprovadas'),
            'quantidade_emendas_analise_2018' => $this->relatorios_siconv_model->quantidade_intervalo($dados_cidades_tabela_array, 2018, 2018, 'quantidade_emendas_analise'),
            'valor_emendas_aprovadas_2018' => $this->relatorios_siconv_model->quantidade_intervalo($dados_cidades_tabela_array, 2018, 2018, 'valor_emendas_aprovadas'),
            'valor_emendas_analise_2018' => $this->relatorios_siconv_model->quantidade_intervalo($dados_cidades_tabela_array, 2018, 2018, 'valor_emendas_analise'),
            'quantidade_perda_emenda_2018' => $this->relatorios_siconv_model->quantidade_intervalo($dados_cidades_tabela_array, 2018, 2018, 'quantidade_emendas'),
            'valor_perda_emenda_2018' => $this->relatorios_siconv_model->quantidade_intervalo($dados_cidades_tabela_array, 2018, 2018, 'soma'),
        );


        if (count($this->input->post('esfera')) == 4 || $tipo == 'TODAS') {
            $dados['percentual_enviadas_2018'] = ($quantidade_2018_enviadas * 100) / ($nivel_e_fator_de_cadastro['envio_proposta']);
            $dados['percentual_aprovadas_2018'] = ($quantidade_2018_aprovadas * 100) / ($nivel_e_fator_de_cadastro['envio_proposta']);
            $dados['perdas_propostas_voluntarias_2018'] = (($nivel_e_fator_de_cadastro['envio_proposta']) - $quantidade_2018_enviadas);
        } elseif ($tipo == 'MUNICIPAL' || ($this->input->post('esfera') != NULL && count($this->input->post('esfera')) != 4 && in_array('MUNICIPAL', $this->input->post('esfera')))) {
            $dados['percentual_enviadas_2018'] = ($quantidade_2018_enviadas * 100) / ($nivel_e_fator_de_cadastro['propostas_governo_municipal']);
            $dados['percentual_aprovadas_2018'] = ($quantidade_2018_aprovadas * 100) / ($nivel_e_fator_de_cadastro['propostas_governo_municipal']);
            $dados['perdas_propostas_voluntarias_2018'] = (($nivel_e_fator_de_cadastro['propostas_governo_municipal']) - $quantidade_2018_enviadas);
        } elseif ($tipo == 'PRIVADA' || ($this->input->post('esfera') != NULL && count($this->input->post('esfera')) != 4 && in_array('PRIVADA', $this->input->post('esfera')))) {
            $dados['percentual_enviadas_2018'] = ($quantidade_2018_enviadas * 100) / ($nivel_e_fator_de_cadastro['propostas_organizacoes_sociais']);
            $dados['percentual_aprovadas_2018'] = ($quantidade_2018_aprovadas * 100) / ($nivel_e_fator_de_cadastro['propostas_organizacoes_sociais']);
            $dados['perdas_propostas_voluntarias_2018'] = (($nivel_e_fator_de_cadastro['propostas_organizacoes_sociais']) - $quantidade_2018_enviadas);
        } elseif ($tipo == 'EMPRESA PUBLICA SOCIEDADE ECONOMIA MISTA' || ($this->input->post('esfera') != NULL && count($this->input->post('esfera')) != 4 && in_array('EMPRESA PUBLICA SOCIEDADE ECONOMIA MISTA', $this->input->post('esfera')))) {
            $dados['percentual_enviadas_2018'] = ($quantidade_2018_enviadas * 100) / ($nivel_e_fator_de_cadastro['propostas_empresas_mistas']);
            $dados['percentual_aprovadas_2018'] = ($quantidade_2018_aprovadas * 100) / ($nivel_e_fator_de_cadastro['propostas_empresas_mistas']);
            $dados['perdas_propostas_voluntarias_2018'] = (($nivel_e_fator_de_cadastro['propostas_empresas_mistas']) - $quantidade_2018_enviadas);
        } elseif ($tipo == 'CONSORCIO PUBLICO' || ($this->input->post('esfera') != NULL && count($this->input->post('esfera')) != 4 && in_array('CONSORCIO PUBLICO', $this->input->post('esfera')))) {
            $dados['percentual_enviadas_2018'] = ($quantidade_2018_enviadas * 100) / ($nivel_e_fator_de_cadastro['propostas_consorcios_publicos']);
            $dados['percentual_aprovadas_2018'] = ($quantidade_2018_aprovadas * 100) / ($nivel_e_fator_de_cadastro['propostas_consorcios_publicos']);
            $dados['perdas_propostas_voluntarias_2018'] = (($nivel_e_fator_de_cadastro['propostas_consorcios_publicos']) - $quantidade_2018_enviadas);
        }
        return $dados;
    }

}
