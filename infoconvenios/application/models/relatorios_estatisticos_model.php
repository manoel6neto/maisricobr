<?php

class relatorios_estatisticos_model extends CI_Model {

    // ------------- Consultas para pegar os dados das views propostas porgramas --------------------- //

    public function get_sigla_estado_cidade($cidade) {
        $this->db->select('municipio_uf_sigla');
        $this->db->distinct();
        $this->db->where('municipio', $cidade);
        $query = $this->db->get('MV_CIDADES');

        return $query->row(0);
    }

    public function get_nome_estado_cidade($cidade) {
        $this->db->select('municipio_uf_nome');
        $this->db->distinct();
        $this->db->where('municipio', $cidade);
        $query = $this->db->get('MV_CIDADES');

        return $query->row(0);
    }

    public function get_sigla_estado($estado) {
        $this->db->select('municipio_uf_sigla');
        $this->db->distinct();
        $this->db->where('municipio_uf_nome', $estado);
        $query = $this->db->get('MV_CIDADES');

        return $query->row(0)->municipio_uf_sigla;
    }

    public function get_all_estados_regiao($sigla_regiao) {
        $estados_nordeste = array(strtoupper('bahia'), strtoupper('alagoas'), strtoupper('cearÁ'), strtoupper('maranhÃo'), strtoupper('paraÍba'), strtoupper('pernambuco'), strtoupper('piauÍ'), strtoupper('rio grande do norte'), strtoupper('sergipe'));
        $estados_norte = array(strtoupper('acre'), strtoupper('amapÁ'), strtoupper('amazonas'), strtoupper('parÁ'), strtoupper('rondÔnia'), strtoupper('roraima'), strtoupper('tocantins'));
        $estados_centrooeste = array(strtoupper('goiÁs'), strtoupper('mato grosso'), strtoupper('mato grosso do sul'), strtoupper('distrito federal'));
        $estados_sudeste = array(strtoupper('espÍrito santo'), strtoupper('minas gerais'), strtoupper('rio de janeiro'), strtoupper('sÃo paulo'));
        $estados_sul = array(strtoupper('paranÁ'), strtoupper('rio grande do sul'), strtoupper('santa catarina'));

        if ($sigla_regiao == 'NE') {
            return $estados_nordeste;
        } elseif ($sigla_regiao == 'N') {
            return $estados_norte;
        } elseif ($sigla_regiao == 'CO') {
            return $estados_centrooeste;
        } elseif ($sigla_regiao == 'SE') {
            return $estados_sudeste;
        } elseif ($sigla_regiao == 'S') {
            return $estados_sul;
        } elseif ($sigla_regiao == 'TODOS') {
            return array_merge($estados_norte, $estados_nordeste, $estados_centrooeste, $estados_sudeste, $estados_sul);
        } else {
            return array();
        }
    }

    public function get_all_estados() {
        $this->db->select('municipio_uf_nome');
        $this->db->distinct();
        $query = $this->db->get('MV_CIDADES');

        return $query->result();
    }

    public function get_all_cidades_estado($estado) {
        $this->db->select('municipio');
        $this->db->distinct();
        $this->db->where('municipio_uf_nome', $estado);
        $this->db->where("municipio not like '%DEMAIS%'");
        $query = $this->db->get('MV_CIDADES');

        return $query->result();
    }

    // ---------------- Fim consultas  ------------------ //
    // ---------------- Consultas para pegar as propostas do banco de propostas e analisar os valores ---------------- //

    public function get_relatorio_estado($estado, $ano) {
        $this->load->model('banco_proposta_model');

        $array_resultado = array(
            'quantidade_municipios' => 0,
            'programas_abertos' => 0,
            'programas_utilizados' => 0,
            'percentual_utilizacao_programas' => 0,
            'qualificacao_voluntaria' => 0,
            'percentual_voluntaria' => 0,
            'qualificacao_especifico' => 0,
            'percentual_especifico' => 0,
            'qualificacao_emenda' => 0,
            'percentual_emenda' => 0,
            'propostas_enviadas' => 0,
            'percentual_envio_por_municipio' => 0,
            'propostas_aprovadas' => 0,
            'percentual_aprovada_por_municipio' => 0,
            'percentual_aprovacao_propostas' => 0,
            'cidades_sem_propostas_enviadas' => 0,
            'cidades_sem_propostas_aprovadas' => 0,
            'cidades_apenas_uma_proposta_aprovada' => 0
        );

        // -- quantidade de cidades -- //
        $array_resultado['quantidade_municipios'] = count($this->get_all_cidades_estado($estado));

        // -- cidades e cnpjs -- //
        $this->db->distinct();
        $this->db->where('municipio_uf_nome', $estado);
        $this->db->where('municipio !=', "DEMAIS MUNICIPIOS");
        if ($estado == "DISTRITO FEDERAL") {
            $query = $this->db->get('MV_DF_CNPJ');
        } else {
            $query = $this->db->get('MV_CIDADES_CNPJ');
        }
        $cidades_cnpj_estado = $query->result();

        $this->db->where('ano', $ano);
        $query = $this->db->get('MV_PROPOSTAS_PROGRAMAS_' . str_replace(' ', '_', strtoupper($estado)));
        $propostas_estado = $query->result();

        if (count($propostas_estado) > 0) {

            // -- propostas enviadas -- //
            $propostas_enviadas = array();
            for ($i = 0; $i < count($propostas_estado); $i++) {
                if ($propostas_estado[$i]->situacao !== 'Cancelado' && $propostas_estado[$i]->situacao !== 'Proposta/Plano de Trabalho Cancelados' &&
                        $propostas_estado[$i]->situacao !== 'Proposta/Plano de Trabalho Cadastrados' && $propostas_estado[$i]->situacao !== 'Proposta/Plano de Trabalho em rascunho' &&
                        $propostas_estado[$i]->situacao !== 'Histórico') {
//                if ($this->banco_proposta_model->verifica_proposta_enviada($propostas_estado[$i]->situacao)) {
                    array_push($propostas_enviadas, $propostas_estado[$i]);
                }
            }
            $array_resultado['propostas_enviadas'] = count($propostas_enviadas);

            if (count($propostas_enviadas) > 0) {
                // -- programas utilizados -- // 
                $programas_utilizados = array();
                for ($i = 0; $i < count($propostas_enviadas); $i++) {
                    if (!in_array($propostas_enviadas[$i]->codigo_programa, $programas_utilizados)) {
                        array_push($programas_utilizados, $propostas_enviadas[$i]->codigo_programa);
                    }
                }
                $array_resultado['programas_utilizados'] = count($programas_utilizados);
                $array_resultado['programas_utilizados_objects'] = $programas_utilizados;

                // -- programas por qualificacao -- //
                // -- propostas aprovadas -- //
                $programas_volutaria = array();
                $programas_ementa = array();
                $programas_especifico = array();
                $propostas_aprovadas = array();
                $cidades_sem_enviadas = array();
                $cidades_sem_aprovadas = array();
                $cidades_com_apenas_uma_aprovada = array();
                $cidades_com_aprovadas_repeticao = array();
                for ($i = 0; $i < count($propostas_enviadas); $i++) {
                    if (in_array($propostas_enviadas[$i]->codigo_programa, $programas_utilizados)) {
                        if (strstr($propostas_enviadas[$i]->qualificacao, 'Voluntária') != false) {
                            if (!in_array($propostas_enviadas[$i]->codigo_programa, $programas_volutaria)) {
                                array_push($programas_volutaria, $propostas_enviadas[$i]->codigo_programa);
                            }
                        }
                        if (strstr($propostas_enviadas[$i]->qualificacao, 'Emenda') != false) {
                            if (!in_array($propostas_enviadas[$i]->codigo_programa, $programas_ementa)) {
                                array_push($programas_ementa, $propostas_enviadas[$i]->codigo_programa);
                            }
                        }
                        if (strstr($propostas_enviadas[$i]->qualificacao, 'Específico') != false) {
                            if (!in_array($propostas_enviadas[$i]->codigo_programa, $programas_especifico)) {
                                array_push($programas_especifico, $propostas_enviadas[$i]->codigo_programa);
                            }
                        }
                    }

//                    if ($propostas_enviadas[$i]->situacao !== 'Proposta/Plano de Trabalho Rejeitados' && $propostas_enviadas[$i]->situacao !== 'Proposta/Plano de Trabalho em Complementação' &&
//                            $propostas_enviadas[$i]->situacao !== 'Proposta/Plano de Trabalho em Análise' && $propostas_enviadas[$i]->situacao !== 'Proposta/Plano de Trabalho complementado em Análise' && $propostas_enviadas[$i]->situacao !== 'Proposta/Plano de Trabalho complementado enviada para Análise' && 
//                            $propostas_enviadas[$i]->situacao !== 'Proposta/Plano de Trabalho Rejeitados por Impedimento técnico') 
//                            {
                    if ($this->banco_proposta_model->verifica_proposta_aprovada($propostas_enviadas[$i]->situacao)) {
                        array_push($propostas_aprovadas, $propostas_enviadas[$i]);

                        if (!in_array($propostas_enviadas[$i]->proponente, $cidades_sem_aprovadas)) {
                            array_push($cidades_sem_aprovadas, $propostas_enviadas[$i]->proponente);
                        }

                        array_push($cidades_com_aprovadas_repeticao, $propostas_enviadas[$i]->proponente);
                    }

                    if (!in_array($propostas_enviadas[$i]->proponente, $cidades_sem_enviadas)) {
                        array_push($cidades_sem_enviadas, $propostas_enviadas[$i]->proponente);
                    }
                }

                $array_count = array_count_values($cidades_com_aprovadas_repeticao);
                foreach ($array_count as $key => $value) {
                    if ($value == 1) {
                        array_push($cidades_com_apenas_uma_aprovada, $key);
                    }
                }

//                echo '<PRE>';
//                print_r($propostas_aprovadas);
//                echo '</PRE>';
//                die();
                // -- Programas abertos -- //
                $programas_abertos_estado = $this->get_programas_abertos_estado($estado, $ano);

                $array_municipios_com_enviada = array();
                $array_municipio_com_aprovadas = array();
                $array_municipio_com_apenas_uma_aprovada = array();
                foreach ($cidades_sem_enviadas as $cidade_sem_envio) {
                    foreach ($cidades_cnpj_estado as $cce) {
                        if ($cce->cnpj == $cidade_sem_envio) {
                            $city = $cce->municipio;
                            break;
                        }
                    }
                    if (!in_array($city, $array_municipios_com_enviada)) {
                        array_push($array_municipios_com_enviada, $city);
                    }
                }

                foreach ($cidades_sem_aprovadas as $cidade_sem_aprovar) {
                    foreach ($cidades_cnpj_estado as $cce) {
                        if ($cce->cnpj == $cidade_sem_aprovar) {
                            $city = $cce->municipio;
                            break;
                        }
                    }
                    if (!in_array($city, $array_municipio_com_aprovadas)) {
                        array_push($array_municipio_com_aprovadas, $city);
                    }
                }

                foreach ($cidades_com_apenas_uma_aprovada as $cidade_uma_aprov) {
                    foreach ($cidades_cnpj_estado as $cce) {
                        if ($cce->cnpj == $cidade_uma_aprov) {
                            $city = $cce->municipio;
                            break;
                        }
                    }
                    array_push($array_municipio_com_apenas_uma_aprovada, $city);
                }

                $count_municipios_apenas_uma_aprovada = array_count_values($array_municipio_com_apenas_uma_aprovada);
                $array_municipio_com_apenas_uma_aprovada = array();
                foreach ($count_municipios_apenas_uma_aprovada as $key => $value) {
                    if ($value == 1) {
                        array_push($array_municipio_com_apenas_uma_aprovada, $key);
                    }
                }

                $array_resultado['programas_abertos'] = count($programas_abertos_estado);
                $array_resultado['programas_abertos_objects'] = $programas_abertos_estado;
                $array_resultado['percentual_utilizacao_programas'] = number_format(($array_resultado['programas_utilizados'] * 100) / $array_resultado['programas_abertos'], 2);
                $array_resultado['qualificacao_emenda'] = count($programas_ementa);
                $array_resultado['percentual_emenda'] = number_format(($array_resultado['qualificacao_emenda'] * 100) / $array_resultado['programas_utilizados'], 2);
                $array_resultado['qualificacao_especifico'] = count($programas_especifico);
                $array_resultado['percentual_especifico'] = number_format(($array_resultado['qualificacao_especifico'] * 100) / $array_resultado['programas_utilizados'], 2);
                $array_resultado['qualificacao_voluntaria'] = count($programas_volutaria);
                $array_resultado['percentual_voluntaria'] = number_format(($array_resultado['qualificacao_voluntaria'] * 100) / $array_resultado['programas_utilizados'], 2);
                $array_resultado['percentual_envio_por_municipio'] = number_format(($array_resultado['propostas_enviadas'] / $array_resultado['quantidade_municipios']), 2);
                $array_resultado['propostas_aprovadas'] = count($propostas_aprovadas);
                $array_resultado['percentual_aprovada_por_municipio'] = number_format(($array_resultado['propostas_aprovadas'] / $array_resultado['quantidade_municipios']), 2);
                $array_resultado['percentual_aprovacao_propostas'] = number_format(($array_resultado['propostas_aprovadas'] * 100) / $array_resultado['propostas_enviadas'], 2);
                $array_resultado['cidades_sem_propostas_enviadas'] = $array_resultado['quantidade_municipios'] - count($array_municipios_com_enviada);
                $array_resultado['cidades_sem_propostas_aprovadas'] = $array_resultado['quantidade_municipios'] - count($array_municipio_com_aprovadas);
                $array_resultado['cidades_apenas_uma_proposta_aprovada'] = count($array_municipio_com_apenas_uma_aprovada);
            }
        }

        return $array_resultado;
    }

    public function get_relatorios_municipio($cidade, $ano) {
        $this->load->model('banco_proposta_model');

        $array_resultado = array(
            'programas_abertos' => 0,
            'programas_utilizados' => 0,
            'percentual_utilizacao_programas' => 0,
            'qualificacao_voluntaria' => 0,
            'percentual_voluntaria' => 0,
            'qualificacao_especifico' => 0,
            'percentual_especifico' => 0,
            'qualificacao_emenda' => 0,
            'percentual_emenda' => 0,
            'propostas_enviadas' => 0,
            'propostas_aprovadas' => 0,
            'percentual_aprovacao_propostas' => 0
        );

        // Descobrindo o estado e os cnpjs dessa cidade //
        $estado = $this->get_nome_estado_cidade($cidade);
        $cnpjs_cidade = $this->get_cnpjs_cidade($cidade);
        $cnpjs = array();
        foreach ($cnpjs_cidade as $cnpj) {
            array_push($cnpjs, $cnpj->cnpj);
        }

        $this->db->where('ano', $ano);
        $this->db->where_in('proponente', $cnpjs);
        $query = $this->db->get('MV_PROPOSTAS_PROGRAMAS_' . str_replace(' ', '_', $estado->municipio_uf_nome));
        $propostas_filtradas = $query->result();

        if (count($propostas_filtradas) > 0) {

            // -- propostas enviadas -- //
            $propostas_enviadas = array();
            for ($i = 0; $i < count($propostas_filtradas); $i++) {
                if ($propostas_filtradas[$i]->situacao !== 'Cancelado' && $propostas_filtradas[$i]->situacao !== 'Proposta/Plano de Trabalho Cancelados' &&
                        $propostas_filtradas[$i]->situacao !== 'Proposta/Plano de Trabalho Cadastrados' && $propostas_filtradas[$i]->situacao !== 'Proposta/Plano de Trabalho em rascunho' &&
                        $propostas_filtradas[$i]->situacao !== 'Histórico') {
//                if ($this->banco_proposta_model->verifica_proposta_enviada($propostas_estado[$i]->situacao)) {
                    array_push($propostas_enviadas, $propostas_filtradas[$i]);
                }
            }
            $array_resultado['propostas_enviadas'] = count($propostas_enviadas);

            if (count($propostas_enviadas) > 0) {
                // -- programas utilizados -- // 
                $programas_utilizados = array();
                for ($i = 0; $i < count($propostas_enviadas); $i++) {
                    if (!in_array($propostas_enviadas[$i]->codigo_programa, $programas_utilizados)) {
                        array_push($programas_utilizados, $propostas_enviadas[$i]->codigo_programa);
                    }
                }
                $array_resultado['programas_utilizados'] = count($programas_utilizados);

                // -- programas por qualificacao -- //
                // -- propostas aprovadas -- //
                $programas_volutaria = array();
                $programas_ementa = array();
                $programas_especifico = array();
                $propostas_aprovadas = array();
                for ($i = 0; $i < count($propostas_enviadas); $i++) {
                    if (in_array($propostas_enviadas[$i]->codigo_programa, $programas_utilizados)) {
                        if (strstr($propostas_enviadas[$i]->qualificacao, 'Voluntária') != false) {
                            if (!in_array($propostas_enviadas[$i]->codigo_programa, $programas_volutaria)) {
                                array_push($programas_volutaria, $propostas_enviadas[$i]->codigo_programa);
                            }
                        }
                        if (strstr($propostas_enviadas[$i]->qualificacao, 'Emenda') != false) {
                            if (!in_array($propostas_enviadas[$i]->codigo_programa, $programas_ementa)) {
                                array_push($programas_ementa, $propostas_enviadas[$i]->codigo_programa);
                            }
                        }
                        if (strstr($propostas_enviadas[$i]->qualificacao, 'Específico') != false) {
                            if (!in_array($propostas_enviadas[$i]->codigo_programa, $programas_especifico)) {
                                array_push($programas_especifico, $propostas_enviadas[$i]->codigo_programa);
                            }
                        }
                    }

                    if ($this->banco_proposta_model->verifica_proposta_aprovada($propostas_enviadas[$i]->situacao)) {
                        array_push($propostas_aprovadas, $propostas_enviadas[$i]);
                    }
                }

                // -- Programas abertos -- //
                $programas_abertos_estado = $this->get_programas_abertos_estado($estado->municipio_uf_nome, $ano);

                $array_resultado['programas_abertos'] = count($programas_abertos_estado);
                $array_resultado['percentual_utilizacao_programas'] = number_format(($array_resultado['programas_utilizados'] * 100) / $array_resultado['programas_abertos'], 2);
                $array_resultado['qualificacao_emenda'] = count($programas_ementa);
                $array_resultado['percentual_emenda'] = number_format(($array_resultado['qualificacao_emenda'] * 100) / $array_resultado['programas_utilizados'], 2);
                $array_resultado['qualificacao_especifico'] = count($programas_especifico);
                $array_resultado['percentual_especifico'] = number_format(($array_resultado['qualificacao_especifico'] * 100) / $array_resultado['programas_utilizados'], 2);
                $array_resultado['qualificacao_voluntaria'] = count($programas_volutaria);
                $array_resultado['percentual_voluntaria'] = number_format(($array_resultado['qualificacao_voluntaria'] * 100) / $array_resultado['programas_utilizados'], 2);
                $array_resultado['propostas_aprovadas'] = count($propostas_aprovadas);
                $array_resultado['percentual_aprovacao_propostas'] = number_format(($array_resultado['propostas_aprovadas'] * 100) / $array_resultado['propostas_enviadas'], 2);
            }
        }

        return $array_resultado;
    }

    public function get_programas_abertos_estado($estado, $ano) {
        $sigla = $this->get_sigla_estado($estado);

        $this->db->where('ano', $ano);
        $query = $this->db->get('MV_PROGRAMAS_RELATORIOS');

        $programas_ano = $query->result();

        if (count($programas_ano) > 0) {
            $resultado = array();

            foreach ($programas_ano as $programa) {
                if (strstr($programa->atende, 'Municipal') != false) {
                    if ($programa->estados == 'Todos os Estados estão Aptos' || strstr($programa->estados, $sigla) != false) {
                        if (!in_array($programa->codigo, $resultado)) {
                            array_push($resultado, $programa->codigo);
                        }
                    }
                }
            }

            return $resultado;
        }
    }

    public function get_programas_abertos_cidade($cidade, $ano) {
        $sigla = $this->get_sigla_estado_cidade($cidade);

        $this->db->where('ano', $ano);
        $query = $this->db->get('MV_PROGRAMAS_RELATORIOS');

        $programas_ano = $query->result();

        if (count($programas_ano) > 0) {
            $resultado = array();

            foreach ($programas_ano as $programa) {
                if (strstr($programa->atende, 'Municipal') != false) {
                    if ($programa->estados == 'Todos os Estados estão Aptos' || strstr($programa->estados, $sigla) != false) {
                        if (!in_array($programa->codigo, $resultado)) {
                            array_push($resultado, $programa->codigo);
                        }
                    }
                }
            }

            return $resultado;
        }
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

    public function get_relatorios_regiao($regiao, $ano) {
        $array_resultado = array();
        $array_estados = $this->get_all_estados_regiao($regiao);
        asort($array_estados);
        foreach ($array_estados as $estado) {
            $array_resultado[$estado] = $this->get_relatorio_estado($estado, $ano);
        }

        return $array_resultado;
    }

}
