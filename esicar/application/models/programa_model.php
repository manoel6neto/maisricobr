<?php

class programa_model extends CI_Model {

    function insere_programas() {
        $data = array(
            'nome_programa' => $matches1,
            'id_siconv' => $matches2,
            'cod_siconv' => $matches3,
            'ano' => $matches4
        );
        $this->db->insert('teste_programas', $data);
        return $this->db->affected_rows();
    }

    // retorna todos os programas em vigencias da view programas_vigencia
    function get_all_vigencia($limit = 1000, $offSet = 0) {
        if ($limit > 0) {
            //$this->db->where('excluido is null');
            $query = $this->db->get('programas_vigencia', $limit, $offSet);
        } else {
            //$this->db->where('excluido is null');
            $query = $this->db->get('programas_vigencia');
        }

        return $query;
    }

    function get_programa_by_codigo($codigo, $unico_codigo = false) {
        if (!$unico_codigo)
            $this->db->where_in('codigo', $codigo);
        else
            $this->db->where('codigo', $codigo);
        $this->db->order_by('orgao');
        $query = $this->db->get('siconv_programa');

        if (!$unico_codigo)
            return $query->result();
        else
            return $query->row(0);
    }

    public function get_dados_beneficiario($codigo_programa, $cnpj, $retornoCompleto = false) {
        $this->db->select('siconv_beneficiario.nome as emenda_nome');
        $this->db->select('siconv_beneficiario.valor as emenda_valor');
        $this->db->select('siconv_beneficiario.cnpj as emenda_cnpj');
        $this->db->select('siconv_beneficiario.parlamentar as parlamentar');
        $this->db->select('siconv_beneficiario.emenda as emenda');
        $listaCnpjs = array();
        foreach ($cnpj as $c) {
            if ($this->session->userdata('nivel') != 1)
                $listaCnpjs[] = $this->formatCPFCNPJ($c);
            else
                $listaCnpjs[] = $c;
        }

        $this->db->where_in('cnpj', $listaCnpjs);
        $this->db->where('codigo_programa', $codigo_programa);
        if (!$retornoCompleto)
            return $this->db->get('siconv_beneficiario')->row(0);
        else
            return $this->db->get('siconv_beneficiario')->result();
    }

    // Busca programas pelos campos preenchidos na view busca_programas
    function busca_programa($search = array(), $offSet = 0, $limit = 1000, $buscaEmendas = false) {
        //print_r($search);die;
        $this->load->model('cnpj_siconv');

        $this->db->_protect_identifiers = false;

        $podePesquisar = false;

        $lista_esferas_entidade = $this->cnpj_siconv->get_esfera_cnpj();
        $this->db->flush_cache();

        //limit hardset
        $estados = $this->usuariomodel->get_estados_by_usuario($this->session->userdata('id_usuario'));
        $this->db->flush_cache();

        $this->load->model('usuariomodel');
        $cnpjs = $this->usuariomodel->get_estados_by_usuario($this->session->userdata('id_usuario'));

        $this->db->flush_cache();
        if (isset($search ['apresentacao'])) {
            $tabela = "siconv_programa";
        } elseif (empty($this->input->post()) || isset($search ['vigencia'])) {
            $tabela = "programas_vigencia";
        } else {
            $tabela = "siconv_programa";
        }

        foreach ($cnpjs as $c)
            $lista_cnpjs[] = $this->formatCPFCNPJ($c->cnpj);

        if ($this->session->userdata('nivel') != 1) {
            if ($this->session->userdata('nivel') == 2) {
                $this->db->where('id_usuario', $this->session->userdata('id_usuario'));
                $query = $this->db->get('gestor')->row(0);
                $estado_parlamentar = $query->estado_parlamentar;
            } else if ($this->session->userdata('nivel') == 3) {
                $this->load->model('usuario_gestor');
                $usuario_gestor = $this->usuario_gestor->get_by_usuario($this->session->userdata('id_usuario'));

                $this->db->where('id_gestor', $usuario_gestor->id_gestor);
                $query = $this->db->get('gestor')->row(0);
            }

            if ($buscaEmendas && !empty($search) && !in_array('Proposta Voluntária', $search['qualificacao'])) {
                if ($this->session->userdata('nivel') == 4 || $this->session->userdata('nivel') == 15) {
                    if ($this->session->userdata('sistema') == 'M' || $this->session->userdata('sistema') == "E") {
                        $this->db->distinct();
                        $this->db->join('siconv_beneficiario', $tabela . '.codigo = siconv_beneficiario.codigo_programa');
                        $this->db->where_in('siconv_beneficiario.cnpj', $lista_cnpjs);
                    }
                } else if ($this->session->userdata('nivel') == 2 || $this->session->userdata('nivel') == 3 || $this->session->userdata('nivel') == 5 || $this->session->userdata('nivel') == 6 || $this->session->userdata('nivel') == 7 || $this->session->userdata('nivel') == 8) {
                    if ($this->session->userdata('sistema') == 'M' || $this->session->userdata('sistema') == "E") {
                        $this->db->distinct();
                        $this->db->join('siconv_beneficiario', $tabela . '.codigo = siconv_beneficiario.codigo_programa');
                        $this->db->where_in('siconv_beneficiario.cnpj', $lista_cnpjs);
                    }
                }
            }

            if (($this->session->userdata('usuario_sistema') == "T" && $this->session->userdata('nivel') == 4) || ($this->session->userdata('usuario_sistema') == "T" && $this->session->userdata('nivel') == 15) || $this->session->userdata('usuario_sistema') == "M") {
                $i = 1;
                $array = "(";
                $lista_estados = "";

                if ($this->session->userdata('nivel') == 4 || $this->session->userdata('nivel') == 15) {
                    if ($this->session->userdata('estado_parlamentar') != "") {
                        $lista_estados .= "estados like '%{$this->session->userdata('estado_parlamentar')}%'";
                        $i++;
                    }
                }

                foreach ($estados as $estado) {
                    if ($i == 1) {
                        $lista_estados .= "estados like '%{$estado->sigla}%'";
                        $i++;
                    } /* else
                      $lista_estados .= "or estados like '%{$estado->sigla}%'"; */
                }
                if ($lista_estados != "")
                    $array .= $lista_estados . " or ";
                $array .= " estados like 'Todos os Estados estão Aptos'";
                $array .= ")";
                $this->db->where($array);
            }else {

                $this->db->flush_cache();

                $array = "(";
                $array .= "estados like '%{$estado_parlamentar}%'";
                $array .= "OR estados like 'Todos os Estados estão Aptos'";
                $array .= ")";
                $this->db->where($array);
            }
        }

        //busca padrao quando a pagina é carregada
        if ($search == null) {
            if ($this->session->userdata('sistema') != "P") {
                $lista_filtro_esferas = array(
                    'consorcio' => "Consórcio Público",
                    'eco_mista' => "Empresa pública/Sociedade de economia mista",
                    'estadual' => "Administração Pública Estadual ou do Distrito Federal",
                    'municipal' => "Administração Pública Municipal",
                    'privada' => "Organização da Sociedade Civil"
                );

                $i = 1;
                $array = "(";
                foreach ($lista_esferas_entidade as $k => $v) {
                    if ($v) {
                        if ($i == 1) {
                            $array .= "atende like '%$lista_filtro_esferas[$k]%' ";
                            $i++;
                        } else
                            $array .= "or atende like '%$lista_filtro_esferas[$k]%'";
                    }
                }
                $array .= ")";
                $this->db->where($array);
                $this->db->where("`qualificacao` like '%Proposta Voluntária%'");
            }else {
                $this->db->where("(`atende` like '%Administração Pública Municipal%' OR `atende` like '%Consórcio Público%' OR `atende` like '%Administração Pública Estadual ou do Distrito Federal%' OR `atende` like '%Entidade Privada sem fins lucrativos%' OR `atende` like '%Organização da Sociedade Civil%' OR `atende` like '%Empresa pública/Sociedade de economia mista%')");
                $this->db->where("`qualificacao` like '%Proposta Voluntária%'");
            }

            $this->db->select('programas_vigencia.* ', FALSE);

//            $order = "CASE WHEN programas_vigencia.data_inicio IS NOT NULL AND programas_vigencia.data_inicio <> '0000-00-00' THEN programas_vigencia.data_inicio
//					    	WHEN programas_vigencia.data_inicio_benef IS NOT NULL AND programas_vigencia.data_inicio_benef <> '0000-00-00' THEN programas_vigencia.data_inicio_benef
//					    	WHEN programas_vigencia.data_inicio_parlam IS NOT NULL AND programas_vigencia.data_inicio_parlam <> '0000-00-00' THEN programas_vigencia.data_inicio_parlam END";
            $this->db->order_by("orgao", "ASC");

            //busca sendo feita
        } else {
            if (isset($search['cnpj'])) {
                foreach ($search['cnpj'] as $cnpj) {
                    if ($cnpj != "")
                        $podePesquisar = true;
                }

                if ($podePesquisar) {
                    //$this->db->select('siconv_beneficiario.nome as emenda_nome');
                    //$this->db->select('siconv_beneficiario.valor as emenda_valor');
                    //$this->db->select('siconv_beneficiario.cnpj as emenda_cnpj');
                    //$this->db->select('siconv_beneficiario.parlamentar as parlamentar');
                    //$this->db->select('siconv_beneficiario.emenda as emenda');
                    $this->db->distinct();
                    $this->db->select('siconv_beneficiario.codigo_programa as codigo_beneficiario');

                    $joinTabela = "siconv_programa.codigo";
                    if (isset($search ['vigencia']))
                        $joinTabela = "programas_vigencia.codigo";

                    $this->db->join('siconv_beneficiario', $joinTabela . ' = siconv_beneficiario.codigo_programa');
                    $listaCNPJs = array();

                    foreach ($search['cnpj'] as $cnpj) {
                        if ($this->session->userdata('nivel') != 1)
                            $cnpj = $this->formatCPFCNPJ($cnpj);
                        $listaCNPJs[] = $cnpj;
                    }

                    $this->db->where_in('siconv_beneficiario.cnpj', $listaCNPJs);
                }
            }

            if (isset($search ['atende'])) {
                $i = 1;
                $array = "(";
                foreach ($search ['atende'] as $a) {
                    if ($i == 1) {
                        $array .= "atende like '%$a%' ";
                        $i++;
                    } else
                        $array .= "or atende like '%$a%'";
                }
                $array .= ")";
                $this->db->where($array);
            }

            if (count($search ['qualificacao']) > 1 && in_array('Proposta Voluntária', $search ['qualificacao'])) {
                $this->db->distinct();
                $this->db->join('siconv_beneficiario', $tabela . '.codigo = siconv_beneficiario.codigo_programa', 'left');
                $i = 1;
                $array = "(";
                foreach ($search ['qualificacao'] as $qualificacao) {
                    if ($i == 1) {
                        $array .= "qualificacao like '%$qualificacao%'";
                        $i++;
                    } else {
                        $array .= "or qualificacao like '%$qualificacao%' AND siconv_beneficiario.cnpj in (";
                        for ($iPos = 0; $iPos < count($lista_cnpjs) - 1; $iPos++) {
                            $array .= "'" . $lista_cnpjs[$iPos] . "', ";
                        }
                        $array .= "'" . $lista_cnpjs[count($lista_cnpjs) - 1] . "'";
                        $array .= ")";
                    }
                }
                $array .= ")";
                $this->db->where($array);
            } elseif (isset($search ['qualificacao'])) {
                $i = 1;
                $array = "(";
                foreach ($search ['qualificacao'] as $a) {
                    if ($i == 1) {
                        $array .= "qualificacao like '%$a%'";
                        $i++;
                    } else
                        $array .= "or qualificacao like '%$a%'";
                }
                $array .= ")";
                $this->db->where($array);
            }

            // pesquisa pelo nome de busca
            if ($search ['pesquisa'] != '') {
                $filtroJoin = "siconv_programa.nome";
                if (isset($search ['vigencia']))
                    $filtroJoin = "programas_vigencia.nome";
                $filtro = "({$filtroJoin} like '%" . $search ['pesquisa'] . "%' OR descricao like '%" . $search ['pesquisa'] . "%' OR codigo like '%" . $search ['pesquisa'] . "%' OR orgao like '%" . $search ['pesquisa'] . "%' OR orgao_vinculado like '%" . $search ['pesquisa'] . "%')";
                $this->db->where($filtro);
            }

            //pesquisa pelo orgão
            if ($search ['orgao'] != '') { // select de orgão
                $this->db->like('orgao', $search ['orgao']);
            }

            // checkbox de programas em vigencia
            // pegando todos os programas em vigencia na view programas_vigencia
            // vigencia = data_inicio>data_atual>data_fim
            if (isset($search ['vigencia'])) {
                $this->db->select('programas_vigencia.* ', FALSE);

//                $order = "CASE WHEN programas_vigencia.data_inicio IS NOT NULL AND programas_vigencia.data_inicio <> '0000-00-00' THEN programas_vigencia.data_inicio
//						    	WHEN programas_vigencia.data_inicio_benef IS NOT NULL AND programas_vigencia.data_inicio_benef <> '0000-00-00' THEN programas_vigencia.data_inicio_benef
//						    	WHEN programas_vigencia.data_inicio_parlam IS NOT NULL AND programas_vigencia.data_inicio_parlam <> '0000-00-00' THEN programas_vigencia.data_inicio_parlam END";
                $this->db->order_by("orgao", "ASC");
                // vigencia não setada; buscando pela data;
                // pegar programas em siconv_programa
            } else {
                if ($search ['data_inicio'] != '') { // input de data de inicio
                    $this->db->where('(siconv_programa.data_inicio >=', "'" . implode("-", array_reverse(explode("/", $search ['data_inicio']))) . "'", FALSE);
                    $this->db->or_where('siconv_programa.data_inicio_benef >=', "'" . implode("-", array_reverse(explode("/", $search ['data_inicio']))) . "'", FALSE);
                    $this->db->or_where('siconv_programa.data_inicio_parlam >=', "'" . implode("-", array_reverse(explode("/", $search ['data_inicio']))) . "')", FALSE);
                }
                if ($search ['data_fim'] != '') { // input de data final
                    $this->db->where('(siconv_programa.data_fim <=', "'" . implode("-", array_reverse(explode("/", $search ['data_fim']))) . "'", FALSE);
                    $this->db->or_where('siconv_programa.data_fim_benef <=', "'" . implode("-", array_reverse(explode("/", $search ['data_fim']))) . "'", FALSE);
                    $this->db->or_where('siconv_programa.data_fim_parlam <=', "'" . implode("-", array_reverse(explode("/", $search ['data_fim']))) . "')", FALSE);
                }

//                $order = "CASE WHEN siconv_programa.data_inicio IS NOT NULL AND siconv_programa.data_inicio <> '0000-00-00' THEN siconv_programa.data_inicio
//                            WHEN siconv_programa.data_inicio_benef IS NOT NULL AND siconv_programa.data_inicio_benef <> '0000-00-00' THEN siconv_programa.data_inicio_benef
//                            WHEN siconv_programa.data_inicio_parlam IS NOT NULL AND siconv_programa.data_inicio_parlam <> '0000-00-00' THEN siconv_programa.data_inicio_parlam END";
                $this->db->order_by("orgao", "ASC");
                // pegando todos os programas em vigencia na tabela siconv_programa
                $this->db->select('siconv_programa.* ', FALSE);
            }

//            if ($podePesquisar)
//                $this->db->order_by('siconv_beneficiario.codigo_programa', 'asc');
        }

        $joinOculto = "programas_vigencia.codigo";
        if ($tabela == "siconv_programa")
            $joinOculto = "siconv_programa.codigo";

        if ((!isset($search['mostra_ocultos']) || $search['mostra_ocultos'] == 0) && !$buscaEmendas)
            $this->db->where($joinOculto . " NOT IN (select programas_ocultos.codigo_programa FROM programas_ocultos WHERE id_usuario = {$this->session->userdata('id_usuario')})");

        $this->db->where("{$tabela}.excluido", 0);
        $query = $this->db->get($tabela);

        //imprimindo a query realizada
        //echo $this->db->last_query () . "<br>";
        //total geral de tuplas encontradas
        $result ['total_rows'] = $query->num_rows();

        $this->db->limit($limit, $offSet);
        $query = $this->db->query($this->db->last_query() . " LIMIT {$limit} OFFSET {$offSet}");

        //numero de tuplas a serem exibidas; usando limit
        $result ['num_rows'] = $query->num_rows();

        //lista de tuplas retornadas com limit
        $result ['lista'] = $query->result();


        //pega os ids dos programas ocultos
        $this->db->select("codigo_programa");
        $result['codigos_ocultos'] = array();
        foreach ($this->db->get_where("programas_ocultos", array('id_usuario' => $this->session->userdata('id_usuario')))->result() as $row) {
            $result['codigos_ocultos'][] = $row->codigo_programa;
        }

        return $result;
    }

    // Busca programas pelos campos preenchidos na view busca_programas
    function busca_programa_emendas($search = array(), $offSet = 0, $limit = 1000, $buscaEmendas = false) {
        $this->load->model('cnpj_siconv');

        $this->db->_protect_identifiers = false;

        $podePesquisar = false;

        $lista_esferas_entidade = $this->cnpj_siconv->get_esfera_cnpj();
        $this->db->flush_cache();

        //limit hardset
        $estados = $this->usuariomodel->get_estados_by_usuario($this->session->userdata('id_usuario'));
        $this->db->flush_cache();

        if ($this->session->userdata('nivel') != 1) {
            if ($buscaEmendas && empty($search)) {
                $lista_cnpjs = array();
                if ($this->session->userdata('nivel') == 4 || $this->session->userdata('nivel') == 15) {
                    if ($this->session->userdata('sistema') == 'M' || $this->session->userdata('sistema') == "E") {
                        $this->load->model('usuariomodel');
                        $cnpjs = $this->usuariomodel->get_estados_by_usuario($this->session->userdata('id_usuario'));

                        $this->db->flush_cache();

                        foreach ($cnpjs as $c)
                            $lista_cnpjs[] = $this->formatCPFCNPJ($c->cnpj);

                        $this->db->distinct();
                        $this->db->select('siconv_beneficiario.codigo_programa as codigo_beneficiario');

                        $joinTabela = "programas_vigencia.codigo";

                        $this->db->join('siconv_beneficiario', $joinTabela . ' = siconv_beneficiario.codigo_programa');

                        $this->db->where_in('siconv_beneficiario.cnpj', $lista_cnpjs);
                    }
                } else if ($this->session->userdata('nivel') == 2 || $this->session->userdata('nivel') == 3 || $this->session->userdata('nivel') == 5 || $this->session->userdata('nivel') == 6 || $this->session->userdata('nivel') == 7 || $this->session->userdata('nivel') == 8) {
                    if ($this->session->userdata('sistema') == 'M' || $this->session->userdata('sistema') == "E") {
                        $this->load->model('usuariomodel');
                        $cnpjs = $this->usuariomodel->get_estados_by_usuario($this->session->userdata('id_usuario'));

                        $this->db->flush_cache();

                        foreach ($cnpjs as $c)
                            $lista_cnpjs[] = $this->formatCPFCNPJ($c->cnpj);

                        $this->db->distinct();
                        $this->db->select('siconv_beneficiario.codigo_programa as codigo_beneficiario');

                        $joinTabela = "programas_vigencia.codigo";

                        $this->db->join('siconv_beneficiario', $joinTabela . ' = siconv_beneficiario.codigo_programa');

                        $this->db->where_in('siconv_beneficiario.cnpj', $lista_cnpjs);
                    }
                }
            }

            if (($this->session->userdata('usuario_sistema') == "T" && $this->session->userdata('nivel') == 4) || ($this->session->userdata('usuario_sistema') == "T" && $this->session->userdata('nivel') == 15)) {
                $i = 1;
                $array = "(";
                $lista_estados = "";

                if ($this->session->userdata('nivel') == 4 || $this->session->userdata('nivel') == 15) {
                    if ($this->session->userdata('estado_parlamentar') != "") {
                        $lista_estados .= "estados like '%{$this->session->userdata('estado_parlamentar')}%'";
                        $i++;
                    }
                }

                foreach ($estados as $estado) {
                    if ($i == 1) {
                        $lista_estados .= "estados like '%{$estado->sigla}%'";
                        $i++;
                    } else
                        $lista_estados .= "or estados like '%{$estado->sigla}%'";
                }
                if ($lista_estados != "")
                    $array .= $lista_estados . " or ";
                $array .= " estados like 'Todos os Estados estão Aptos'";
                $array .= ")";
                $this->db->where($array);
            }else if ($this->session->userdata('usuario_sistema') == "P") {
                if ($this->session->userdata('nivel') == 2) {
                    $this->db->where('id_usuario', $this->session->userdata('id_usuario'));
                    $query = $this->db->get('gestor')->row(0);

                    $estado_parlamentar = $query->estado_parlamentar;
                } else if ($this->session->userdata('nivel') == 3) {
                    $this->load->model('usuario_gestor');
                    $usuario_gestor = $this->usuario_gestor->get_by_usuario($this->session->userdata('id_usuario'));

                    $this->db->where('id_gestor', $usuario_gestor->id_gestor);
                    $query = $this->db->get('gestor')->row(0);

                    $estado_parlamentar = $query->estado_parlamentar;
                }

                $this->db->flush_cache();

                $array = "(";
                $array .= "estados like '%{$estado_parlamentar}%'";
                $array .= "OR estados like 'Todos os Estados estão Aptos'";
                $array .= ")";
                $this->db->where($array);
            }
        }

        $tabela = "programas_vigencia";
        //busca padrao quando a pagina é carregada
        if ($search == null) {
            if ($this->session->userdata('sistema') != "P") {
                $lista_filtro_esferas = array(
                    'consorcio' => "Consórcio Público",
                    'eco_mista' => "Empresa pública/Sociedade de economia mista",
                    'estadual' => "Administração Pública Estadual ou do Distrito Federal",
                    'municipal' => "Administração Pública Municipal",
                    'privada' => "Organização da Sociedade Civil"
                );

                $i = 1;
                $array = "(";
                foreach ($lista_esferas_entidade as $k => $v) {
                    if ($v) {
                        if ($i == 1) {
                            $array .= "atende like '%$lista_filtro_esferas[$k]%' ";
                            $i++;
                        } else
                            $array .= "or atende like '%$lista_filtro_esferas[$k]%'";
                    }
                }
                $array .= ")";
                $this->db->where($array);
            }else {
                $this->db->where("(`atende` like '%Administração Pública Municipal%' OR `atende` like '%Consórcio Público%' OR `atende` like '%Administração Pública Estadual ou do Distrito Federal%' OR `atende` like '%Entidade Privada sem fins lucrativos%' OR `atende` like '%Organização da Sociedade Civil%' OR `atende` like '%Empresa pública/Sociedade de economia mista%')");
                $this->db->where("`qualificacao` like '%Proposta Voluntária%'");
            }

            $this->db->select('programas_vigencia.* ', FALSE);

            $order = "CASE WHEN programas_vigencia.data_inicio IS NOT NULL AND programas_vigencia.data_inicio <> '0000-00-00' THEN programas_vigencia.data_inicio
					    	WHEN programas_vigencia.data_inicio_benef IS NOT NULL AND programas_vigencia.data_inicio_benef <> '0000-00-00' THEN programas_vigencia.data_inicio_benef
					    	WHEN programas_vigencia.data_inicio_parlam IS NOT NULL AND programas_vigencia.data_inicio_parlam <> '0000-00-00' THEN programas_vigencia.data_inicio_parlam END";
            $this->db->order_by($order, "DESC");
            //busca sendo feita
        } else {
            if (isset($search['cnpj'])) {
                foreach ($search['cnpj'] as $cnpj) {
                    if ($cnpj != "")
                        $podePesquisar = true;
                }

                if ($podePesquisar) {
                    //$this->db->select('siconv_beneficiario.nome as emenda_nome');
                    //$this->db->select('siconv_beneficiario.valor as emenda_valor');
                    //$this->db->select('siconv_beneficiario.cnpj as emenda_cnpj');
                    //$this->db->select('siconv_beneficiario.parlamentar as parlamentar');
                    //$this->db->select('siconv_beneficiario.emenda as emenda');
                    $this->db->distinct();
                    $this->db->select('siconv_beneficiario.codigo_programa as codigo_beneficiario');

                    $joinTabela = "siconv_programa.codigo";
                    if (isset($search ['vigencia']))
                        $joinTabela = "programas_vigencia.codigo";

                    $this->db->join('siconv_beneficiario', $joinTabela . ' = siconv_beneficiario.codigo_programa');
                    $listaCNPJs = array();

                    foreach ($search['cnpj'] as $cnpj) {
                        if ($this->session->userdata('nivel') != 1)
                            $cnpj = $this->formatCPFCNPJ($cnpj);
                        $listaCNPJs[] = $cnpj;
                    }

                    $this->db->where_in('siconv_beneficiario.cnpj', $listaCNPJs);
                }
            }

            if (isset($search ['atende'])) {
                $i = 1;
                $array = "(";
                foreach ($search ['atende'] as $a) {
                    if ($i == 1) {
                        $array .= "atende like '%$a%' ";
                        $i++;
                    } else
                        $array .= "or atende like '%$a%'";
                }
                $array .= ")";
                $this->db->where($array);
            }

            if (isset($search ['qualificacao'])) {
                $i = 1;
                $array = "(";
                foreach ($search ['qualificacao'] as $a) {
                    if ($i == 1) {
                        $array .= "qualificacao like '%$a%'";
                        $i++;
                    } else
                        $array .= "or qualificacao like '%$a%'";
                }
                $array .= ")";
                $this->db->where($array);
            }

            // pesquisa pelo nome de busca
            if ($search ['pesquisa'] != '') {
                $filtroJoin = "siconv_programa.nome";
                if (isset($search ['vigencia']))
                    $filtroJoin = "programas_vigencia.nome";
                $filtro = "({$filtroJoin} like '%" . $search ['pesquisa'] . "%' OR descricao like '%" . $search ['pesquisa'] . "%' OR codigo like '%" . $search ['pesquisa'] . "%' OR orgao like '%" . $search ['pesquisa'] . "%' OR orgao_vinculado like '%" . $search ['pesquisa'] . "%')";
                $this->db->where($filtro);
            }

            //pesquisa pelo orgão
            if ($search ['orgao'] != '') { // select de orgão
                $this->db->like('orgao', $search ['orgao']);
            }

            // checkbox de programas em vigencia
            // pegando todos os programas em vigencia na view programas_vigencia
            // vigencia = data_inicio>data_atual>data_fim
            if (isset($search ['vigencia'])) {
                $this->db->select('programas_vigencia.* ', FALSE);
                $tabela = "programas_vigencia";

                $order = "CASE WHEN programas_vigencia.data_inicio IS NOT NULL AND programas_vigencia.data_inicio <> '0000-00-00' THEN programas_vigencia.data_inicio
						    	WHEN programas_vigencia.data_inicio_benef IS NOT NULL AND programas_vigencia.data_inicio_benef <> '0000-00-00' THEN programas_vigencia.data_inicio_benef
						    	WHEN programas_vigencia.data_inicio_parlam IS NOT NULL AND programas_vigencia.data_inicio_parlam <> '0000-00-00' THEN programas_vigencia.data_inicio_parlam END";
                $this->db->order_by($order, "DESC");
                // vigencia não setada; buscando pela data;
                // pegar programas em siconv_programa
            } else {
                if ($search ['data_inicio'] != '') { // input de data de inicio
                    $this->db->where('(siconv_programa.data_inicio >=', "'" . implode("-", array_reverse(explode("/", $search ['data_inicio']))) . "'", FALSE);
                    $this->db->or_where('siconv_programa.data_inicio_benef >=', "'" . implode("-", array_reverse(explode("/", $search ['data_inicio']))) . "'", FALSE);
                    $this->db->or_where('siconv_programa.data_inicio_parlam >=', "'" . implode("-", array_reverse(explode("/", $search ['data_inicio']))) . "')", FALSE);
                }
                if ($search ['data_fim'] != '') { // input de data final
                    $this->db->where('(siconv_programa.data_fim <=', "'" . implode("-", array_reverse(explode("/", $search ['data_fim']))) . "'", FALSE);
                    $this->db->or_where('siconv_programa.data_fim_benef <=', "'" . implode("-", array_reverse(explode("/", $search ['data_fim']))) . "'", FALSE);
                    $this->db->or_where('siconv_programa.data_fim_parlam <=', "'" . implode("-", array_reverse(explode("/", $search ['data_fim']))) . "')", FALSE);
                }

                $order = "CASE WHEN siconv_programa.data_inicio IS NOT NULL AND siconv_programa.data_inicio <> '0000-00-00' THEN siconv_programa.data_inicio
                            WHEN siconv_programa.data_inicio_benef IS NOT NULL AND siconv_programa.data_inicio_benef <> '0000-00-00' THEN siconv_programa.data_inicio_benef
                            WHEN siconv_programa.data_inicio_parlam IS NOT NULL AND siconv_programa.data_inicio_parlam <> '0000-00-00' THEN siconv_programa.data_inicio_parlam END";
                $this->db->order_by($order, "DESC");
                // pegando todos os programas em vigencia na tabela siconv_programa
                $this->db->select('siconv_programa.* ', FALSE);
                $tabela = "siconv_programa";
            }

            if ($podePesquisar)
                $this->db->order_by('siconv_beneficiario.codigo_programa', 'asc');
        }

        $joinOculto = "programas_vigencia.codigo";
        if ($tabela == "siconv_programa")
            $joinOculto = "siconv_programa.codigo";

        if ((!isset($search['mostra_ocultos']) || $search['mostra_ocultos'] == 0) && !$buscaEmendas)
            $this->db->where($joinOculto . " NOT IN (select programas_ocultos.codigo_programa FROM programas_ocultos WHERE id_usuario = {$this->session->userdata('id_usuario')})");
        $this->db->where("siconv_beneficiario.excluido", 0);
        $this->db->where("$tabela.excluido", 0);
        $query = $this->db->get($tabela);

        //imprimindo a query realizada
        //echo $this->db->last_query () . "<br>";
        //total geral de tuplas encontradas
        $result ['total_rows'] = $query->num_rows();

        $this->db->limit($limit, $offSet);
        $query = $this->db->query($this->db->last_query() . " LIMIT {$limit} OFFSET {$offSet}");

        //numero de tuplas a serem exibidas; usando limit
        $result ['num_rows'] = $query->num_rows();

        //lista de tuplas retornadas com limit
        $result ['lista'] = $query->result();

        return $result;
    }

    function get_emendas($id) {
        $this->db->where('codigo_programa', $id);
        $query = $this->db->get('siconv_beneficiario');

        if ($query->num_rows > 1)
            return $query;
        else
            return false;
    }

    function get_all() {
        $query = $this->db->get('siconv_programa');
        return $query->result();
    }

    function get_all_orgaos() {
        $query = $this->db->get('orgaos');
        return $query->result();
    }

    function obter_lista_distinct($nome) {
        $this->db->distinct();
        $this->db->select($nome);
        $this->db->where($nome . ' != \'\'');
        $query = $this->db->get('siconv_programa');
        return $query->result_array();
    }

    function obter_programas_por_usuario($id) {
        $this->db->join('siconv_programa', 'siconv_usuario_programa.codigoPrograma = siconv_programa.codigo');
        $this->db->where('siconv_usuario_programa.idPessoa', $id);
        $query = $this->db->get('siconv_usuario_programa');
        return $query->result();
    }

    function obter_programas_por_usuario_aceito($id) {
        $this->db->join('siconv_programa', 'siconv_usuario_programa.codigoPrograma = siconv_programa.codigo');
        $this->db->where('siconv_usuario_programa.idPessoa', $id);
        $this->db->where('siconv_usuario_programa.aceito', true);
        $query = $this->db->get('siconv_usuario_programa');
        return $query->result();
    }

    function obter_programas_por_usuario_todas($id) {
        $this->db->join('siconv_programa', 'siconv_usuario_programa.codigoPrograma = siconv_programa.codigo');
        $this->db->where('siconv_usuario_programa.idPessoa', $id);
        $query = $this->db->get('siconv_usuario_programa');
        return $query->result();
    }

    function programas_abertos($usuarioNovo = false, $idUsuario = null) {
        ini_set('max_execution_time', 0);
        ini_set("memory_limit", -1);

        //Removendo os programas n existentes mais no siconv
//        try {
//            $temp_table_progs = $this->db->get('codigos_programas_existentes_siconv_analise');
//            if ($temp_table_progs != null) {
//                if ($temp_table_progs->num_rows > 0) {
//                    $resultado = $temp_table_progs->result();
//                    $resultado_list = array();
//                    foreach ($resultado as $r) {
//                        array_push($resultado_list, $r->codigo_programa);
//                    }
//
//                    $this->db->where_not_in('codigo', $resultado_list);
//                    $this->db->where('excluido is null');
//                    $codigos_removidos = $this->db->get('siconv_programa');
//                    if ($codigos_removidos != null) {
//                        if ($codigos_removidos->num_rows > 0) {
//                            try {
//                                $result_removidos = $codigos_removidos->result();
//                                foreach ($result_removidos as $cod) {
//                                    $opt = array("excluido" => 1);
//                                    $this->db->where('codigo', $cod->codigo);
//                                    $this->db->update('siconv_programa', $opt);
//                                }
//                            } catch (Exception $e) {
//                                echo $e->getMessage();
//                            }
//                        }
//                    }
//                }
//            }
//            $this->db->truncate('codigos_programas_existentes_siconv_analise');
//        } catch (Exception $e) {
//            echo $e->getMessage();
//        }
        //Fim processamento de removidos
        //Iniciando o processamento de programas abertos
        $this->load->model('usuariomodel');
        $this->load->model('gestor');


        if (!$usuarioNovo) {
            $gestores = $this->usuariomodel->get_all_gestor(true, true);
        } else {
            $gestores = $this->usuariomodel->get_by_id_array($idUsuario);
        }

        if (!empty($gestores)) {
            foreach ($gestores as $gestor) {
                try {
                    $lista_copias_email = null;
                    $lista_encarregados = null;
                    $lista_tecnicos = null;

                    $estados = $this->usuariomodel->get_estados_by_usuario($gestor->id_usuario);
                    if ($gestor->id_nivel == 2) {
                        $dadosGestor = $this->gestor->get_by_usuario_only_gestor($gestor->id_usuario);
                    } else {
                        $dadosGestor = $this->gestor->get_all_by_usuario($gestor->id_usuario);
                    }

                    if ($gestor->usuario_sistema == "M" || $gestor->usuario_sistema == "E") {
                        $i = 1;
                        $array = "(";
                        $lista_estados = "";
                        foreach ($estados as $estado) {
                            if ($i == 1) {
                                $lista_estados .= "estados like '%{$estado->sigla}%'";
                                $i++;
                            } else
                                $lista_estados .= " or estados like '%{$estado->sigla}%'";
                        }

                        if ($lista_estados != "")
                            $array .= $lista_estados . " or ";
                        $array .= " estados like 'Todos os Estados estão Aptos'";
                        $array .= ")";
                    }else if ($gestor->usuario_sistema == "P") {
                        $estado_parlamentar = $dadosGestor->estado_parlamentar;

                        $array = "(";
                        $array .= "estados like '%{$estado_parlamentar}%'" . " or ";
                        $array .= " estados like 'Todos os Estados estão Aptos'";
                        $array .= ")";
                    }

                    switch ($gestor->usuario_sistema) {
                        case "M":
                            $array .= " and atende like '%Administração Pública Municipal%'";
                            break;
                        case "E":
                            $array .= " and atende like '%Administração Pública Estadual%'";
                    }

                    if ($gestor->id_nivel == 2) {
                        $id_gestor = $dadosGestor->id_gestor;

                        //Carregando os encarregados
                        try {
                            $this->load->model('encarregado_model');
                            $encarregados = $this->encarregado_model->get_by_gestor($id_gestor);
                            $j = 0;
                            foreach ($encarregados as $encarregado) {
                                if ($encarregado->email != null && $encarregado->email != '') {
                                    if ($j == 0) {
                                        $lista_encarregados = $encarregado->email;
                                        $j++;
                                    } else {
                                        $lista_encarregados .= "," . $encarregado->email;
                                    }
                                }
                            }
                        } catch (Exception $e) {
                            $lista_encarregados = null;
                            $this->load->model("log_model");
                            $this->log_model->log_erro("programaa_model/programas_abertos", "800", $e->getMessage());
                        }

                        //Carregando os técnicos
                        try {
                            $this->db->where('id_gestor', $id_gestor);
                            $ids_usuarios_gestor = $this->db->get('usuario_gestor');

                            if ($ids_usuarios_gestor->num_rows > 0) {
                                foreach ($ids_usuarios_gestor->result() as $id_usuario_gestor) {
                                    $this->db->where('id_usuario', $id_usuario_gestor->id_usuario);
                                    $usuario = $this->db->get('usuario');
                                    if ($usuario->num_rows > 0) {
                                        $usuario = $usuario->result();
                                        $usuario = $usuario[0];

                                        if ($usuario->email != null && $usuario->email != '') {
                                            if ($lista_tecnicos != null) {
                                                $lista_tecnicos .= "," . $usuario->email;
                                            } else {
                                                $lista_tecnicos = $usuario->email;
                                            }
                                        }
                                    }
                                }
                            }
                        } catch (Exception $ex) {
                            $lista_tecnicos = null;
                            $this->load->model("log_model");
                            $this->log_model->log_erro("get_programas/programas_abertos", "800", $ex->getMessage());
                        }

                        //ADICIONAR MÉTODO PARA ENVIAR O EMAIL TAMBÉM PARA OS SUBGESTORES
//                        try {
//                            $this->db->where('id_gestor', $id_gestor);
//                            $id_usuarios_gestor = $this->db->get('usuario_gestor');
//                            if ($id_usuarios_gestor->num_rows > 0) {
//                                foreach ($id_usuarios_gestor->result() as $id_usuario_individual) {
//                                    $this->db->where('id_usuario', $id_usuario_individual->id_usuario);
//                                    $usuario = $this->db->get('usuario');
//                                    if ($usuario->num_rows > 0) {
//                                        $usuario = $usuario->result();
//                                        $usuario = $usuario[0];
//
//                                        if ($lista_encarregados != NULL) {
//                                            $lista_encarregados .= "," . $usuario->email;
//                                        } else {
//                                            $lista_encarregados = $usuario->email;
//                                        }
//
//                                        if ($usuario->id_nivel == 6) {
//                                            $this->db->where('id_gestor', $usuario->id_usuario);
//                                            $query_encarregados_subgestor = $this->db->get('usuario_subgestor');
//
//                                            if ($query_encarregados_subgestor->num_rows > 0) {
//                                                foreach ($query_encarregados_subgestor->result() as $subgestorusuarios) {
//                                                    $this->db->where('id_usuario', $subgestorusuarios->id_usuario);
//                                                    $result_usuario_subgestor = $this->db->get('usuario');
//
//                                                    if ($result_usuario_subgestor->num_rows > 0) {
//                                                        $usuario_subgestor = $result_usuario_subgestor->result();
//                                                        $usuario_subgestor = $usuario_subgestor[0];
//
//                                                        if ($lista_encarregados != NULL) {
//                                                            if (strpos($lista_encarregados, $usuario_subgestor->email) !== false) {
//                                                                $lista_encarregados .= "," . $usuario_subgestor->email;
//                                                            }
//                                                        } else {
//                                                            $lista_encarregados = $usuario_subgestor->email;
//                                                        }
//                                                    }
//                                                }
//                                            }
//                                        }
//                                    }
//                                }
//                            }
//                        } catch (Exception $e) {
//                            $lista_encarregados = null;
//                            continue;
//                        }
                        if ($lista_encarregados != null && $lista_tecnicos != null) {
                            $lista_copias_email = $lista_tecnicos . $lista_encarregados;
                        } else {
                            if ($lista_encarregados != null) {
                                $lista_copias_email = $lista_encarregados;
                            } else {
                                $lista_copias_email = $lista_tecnicos;
                            }
                        }
                    }

                    try {
                        if ($gestor != null && $dadosGestor != null) {
                            if ($gestor->email != null && $gestor->email != "") {
                                $cnpjs = $this->usuariomodel->get_cnpjs_by_usuario($gestor->id_usuario);
                                $listaCNPJs = array();
                                foreach ($cnpjs as $cnpj) {
                                    $listaCNPJs[] = $this->formatCPFCNPJ($cnpj->cnpj);
                                }

                                try {
                                    $this->prepara_email($gestor->email, $lista_copias_email, $array, $usuarioNovo, false, $listaCNPJs);
                                } catch (Exception $ex) {
                                    $this->load->model("log_model");
                                    $this->log_model->log_erro("get_programas/programas_abertos", "800", $ex->getMessage());
                                }
                            }
                        }
                    } catch (Exception $ex) {
                        $this->load->model("log_model");
                        $this->log_model->log_erro("get_programas/programas_abertos", "800", $ex->getMessage());
                    }

                    try {
                        if (!$usuarioNovo) {
                            $cnpjs = $this->usuariomodel->get_cnpjs_by_usuario($gestor->id_usuario);
                            if (!empty($cnpjs)) {
                                $listaCNPJs = array();
                                foreach ($cnpjs as $cnpj) {
                                    $listaCNPJs[] = $this->formatCPFCNPJ($cnpj->cnpj);
                                }

                                if ($dadosGestor->tipo_gestor != 10 && $gestor->email != null && $gestor->email != "") {
                                    try {
                                        $this->prepara_email_emendas($gestor->email, $lista_copias_email, $array, $listaCNPJs, $usuarioNovo, null, null, false);
                                    } catch (Exception $ex) {
                                        $this->load->model("log_model");
                                        $this->log_model->log_erro("get_programas/programas_abertos", "800", $ex->getMessage());
                                    }
                                } else {
                                    if ($gestor->email != null && $gestor->email != "") {
                                        try {
                                            $this->prepara_email_emendas($gestor->email, $lista_copias_email, $array, $listaCNPJs, $usuarioNovo, null, null, true);
                                        } catch (Exception $ex) {
                                            $this->load->model("log_model");
                                            $this->log_model->log_erro("get_programas/programas_abertos", "800", $ex->getMessage());
                                        }
                                    }
                                }
                            } else {
                                if ($gestor->usuario_sistema == 'P') {
                                    //$this->prepara_email_emendas($gestor->email, $lista_encarregados, $array, $listaCNPJs, $usuarioNovo, $dadosGestor->numero_parlamentar);
                                }
                            }
                        }
                    } catch (Exception $ex) {
                        $this->load->model("log_model");
                        $this->log_model->log_erro("get_programas/programas_abertos", "800", $ex->getMessage());
                    }

                    //Alterado passando o numero do parlamentar (sem propostas)
                    try {
                        if ($gestor != null && $dadosGestor != null) {
                            if ($gestor->usuario_sistema == 'P') {
                                //$this->prepara_email_emendas($gestor->email, $lista_encarregados, $array, null, true, $dadosGestor->numero_parlamentar, 5);
                                //$this->prepara_email_emendas($gestor->email, $lista_encarregados, $array, null, true, $dadosGestor->numero_parlamentar, 0);
                                //$this->prepara_email_emendas_verifica_propostas_vencendo_so_cadastradas($gestor->email, $lista_encarregados, $dadosGestor->numero_parlamentar);
                            } else {
                                if ($dadosGestor->tipo_gestor != 10 && $gestor->email != null && $gestor->email != "") {
                                    try {
                                        $this->prepara_email_vencimento($gestor->email, $lista_copias_email, 10, $array, false); //vencimento 10 dias a frente
                                        $this->prepara_email_vencimento($gestor->email, $lista_copias_email, 5, $array, false); //vencimento 5 dias a frente
                                        $this->prepara_email_vencimento($gestor->email, $lista_copias_email, 0, $array, false); //vencimento no dia do envio
                                    } catch (Exception $ex) {
                                        $this->load->model("log_model");
                                        $this->log_model->log_erro("get_programas/programas_abertos", "800", $ex->getMessage());
                                        continue;
                                    }
                                } else {
                                    if ($gestor->email != null && $gestor->email != "") {
                                        try {
                                            $this->prepara_email_vencimento($gestor->email, $lista_copias_email, 10, $array, false); //vencimento 10 dias a frente
                                            $this->prepara_email_vencimento($gestor->email, $lista_copias_email, 5, $array, true); //vencimento 5 dias a frente
                                            $this->prepara_email_vencimento($gestor->email, $lista_copias_email, 0, $array, true); //vencimento no dia do envio
                                        } catch (Exception $ex) {
                                            $this->load->model("log_model");
                                            $this->log_model->log_erro("get_programas/programas_abertos", "800", $ex->getMessage());
                                            continue;
                                        }
                                    }
                                }
                            }
                        }
                    } catch (Exception $ex) {
                        $this->load->model("log_model");
                        $this->log_model->log_erro("get_programas/programas_abertos", "800", $ex->getMessage());
                        continue;
                    }
                } catch (Exception $ex) {
                    $this->load->model("log_model");
                    $this->log_model->log_erro("get_programas/programas_abertos", "800", $ex->getMessage());
                    continue;
                }
            }
        }

        try {
            if (!$usuarioNovo) {
                //envio para todos os administradores
                $this->db->where('id_nivel', 1);
                $admins = $this->db->get('usuario');

                if ($admins->num_rows > 0) {
                    $admins = $admins->result();
                    $destinatario = NULL;
                    $copia = NULL;

                    foreach ($admins as $key => $value) {
                        if ($value->email != NULL && $value->email != '') {
                            $this->prepara_email($value->email, NULL);
                        }
                    }
                }

                //Envio para todos os representantes
                $this->db->where('id_nivel', 4);
                $representantes = $this->db->get('usuario');

                if ($representantes->num_rows > 0) {
                    $representantes = $representantes->result();

                    foreach ($representantes as $repr) {
                        if ($repr->email != NULL && $repr->email != '') {
                            $this->prepara_email($repr->email, NULL);
                        }
                    }
                }
            }
        } catch (Exception $ex) {
            $this->load->model("log_model");
            $this->log_model->log_erro("get_programas/programas_abertos", "800", $ex->getMessage());
        }

        //Mantendo o envio estatico por enquanto
        try {
            if (!$usuarioNovo) {
                $this->prepara_email("manoel.carvalho.neto@gmail.com", "miguelaugusto5@yahoo.com.br");
            }
        } catch (Exception $ex) {
            $this->load->model("log_model");
            $this->log_model->log_erro("get_programas/programas_abertos", "800", $ex->getMessage());
        }

        try {
            $this->marca_programas_antigos();
        } catch (Exception $ex) {
            $this->load->model("log_model");
            $this->log_model->log_erro("get_programas/programas_abertos", "800", $ex->getMessage());
        }
    }

    public function prepara_email_vencimento($email, $email_cc = null, $num_dias, $filtro_estados = "", $infoconv = false) {
        try {
            $vencimento = $this->get_programas_vencimento($num_dias, $filtro_estados);
            if ($vencimento != null) {
                $texto = "Lista de Programas com vencimento hoje:<br><br>";
                $titulo = "Programa com vencimento hoje";

                if ($num_dias == 5) {
                    $titulo = "Programa com vencimento em 5 dias";
                    $texto = "Lista de Programas com vencimento em 5 dias:<br><br>";
                } elseif ($num_dias == 10) {
                    $titulo = "Programa com vencimento em 10 dias";
                    $texto = "Lista de Programas com vencimento em 10 dias:<br><br>";
                } else {
                    $titulo = "Programa com vencimento hoje";
                    $texto = "Lista de Programas com vencimento hoje:<br><br>";
                }

                foreach ($vencimento as $prog) {
                    $style = "";
                    if ($prog->tem_atualizacao)
                        $style = "style='color:red;'";
                    $texto .= "<a " . $style . " href=\"" . $prog->link . "&path=/MostraPrincipalConsultarPrograma.do&Usr=guest&Pwd=guest\" target=\"_blank\">" . $prog->codigo . "</a>: ";
                    $texto .= $prog->orgao;
                    $texto .= " (" . $prog->qualificacao . ") - ";
                    $texto .= $prog->atende;
                    $texto .= ($prog->tem_atualizacao ? " <span style='color:red;'>(Programa com alteração na vigência)</span>" : "") . "<br />";
                    $texto .= "<b>Nome do Programa:</b> " . $prog->nome . "<br/>";
                    $texto .= "<b>Descrição:</b> " . $prog->descricao . "<br />";
                    $texto .= "<b>Estados Atendidos:</b> " . $prog->estados . "<br/>";

                    if (isset($prog->data_inicio) && strtotime($prog->data_inicio) > 0) {
                        $texto .= "<b>Proposta Voluntária<br>Início:</b> " . implode('/', array_reverse(explode('-', $prog->data_inicio))) . "&nbsp;&nbsp;
						<b>Fim:</b> " . implode('/', array_reverse(explode('-', $prog->data_fim))) . "<br/><br/>";
                    }

                    if (isset($prog->data_inicio_benef) && strtotime($prog->data_inicio_benef) > 0) {
                        $texto .= "<b>Proposta de Proponente Específico do Concedente<br>Início:</b> " . implode('/', array_reverse(explode('-', $prog->data_inicio_benef))) . "&nbsp;
						<b>Fim:</b> " . implode('/', array_reverse(explode('-', $prog->data_fim_benef))) . "<br/><br/>";
                    }

                    if (isset($prog->data_inicio_parlam) && strtotime($prog->data_inicio_parlam) > 0) {
                        $texto .= "<b>Proposta de Proponente de Emenda Parlamentar<br>Início:</b> " . implode('/', array_reverse(explode('-', $prog->data_inicio_parlam))) . "&nbsp;
						<b>Fim:</b> " . implode('/', array_reverse(explode('-', $prog->data_fim_parlam))) . "<br/><br/>";
                    }

                    $ben = $this->obter_beneficiarios($prog->codigo);
                    if (count($ben) > 0)
                        $texto .= "<b>Beneficiários:</b><br />";
                    foreach ($ben as $beneficiario) {
                        $texto .= " - CNPJ: " . $beneficiario->cnpj . "<br />";
                        $texto .= " - Nome: " . $beneficiario->nome . "<br />";
                        $texto .= " - Valor: " . $beneficiario->valor . "<br />";
                        if (isset($beneficiario->emenda) && $beneficiario->emenda != "")
                            $texto .= " - Emenda: " . $beneficiario->emenda . "<br />";

                        if (isset($beneficiario->parlamentar) && $beneficiario->parlamentar != "")
                            $texto .= " - Parlamentar: " . $beneficiario->parlamentar . "<br />";
                        $texto .= "----------------------------<br />";
                    }

                    $texto .= "<br />";
                }

                if ($infoconv) {
                    $this->envia_email_infoconveios($email, $email_cc, $texto, $titulo);
                } else {
                    $this->envia_email($email, $email_cc, $texto, $titulo);
                }
            }
        } catch (Exception $ex) {
            $this->load->model("log_model");
            $this->log_model->log_erro("get_programas/programas_abertos", "800", $ex->getMessage());
        }
    }

    public function prepara_email_emendas_verifica_propostas_vencendo_so_cadastradas($email, $email_cc = null, $numeroParlamentar) {
        $texto = "Lista de Propostas de emenda somente cadastradas e com vencimento em 5 dias.<br><br>";
        $numDias = 5;

        //Get programas vencimento 5 dias do parlamentar
        $this->db->select('pv.*, sb.emenda');
        $this->db->join('siconv_beneficiario sb', 'pv.codigo = sb.codigo_programa');
        $this->db->where("sb.emenda <> '' AND sb.emenda LIKE '" . $numeroParlamentar . "%'");
        $this->db->where('pv.data_fim_parlam', "DATE_ADD(CURDATE(), INTERVAL {$numDias} DAY))", FALSE);
        $programas_com_emendas = $this->db->get('programas_vigencia pv')->result();

        if (count($programas_com_emendas) > 0) {
            $propostas_banco_proposta = array();
            foreach ($programas_com_emendas as $programa) {
                //Inicializando $tempPropostas por segurança
                $tempPropostas = array();

                //Consulta - pegando para cada um dos codigos de programas as propostas a partir do programa e emenda do banco de propostas
                $this->db->join('programa_banco_proposta pbp', 'pbp.id_proposta_banco_proposta = id_proposta');
                $this->db->join('emenda_banco_proposta ebp', 'ebp.id_programa_banco_proposta = pbp.id_programa_banco_proposta');
                $this->db->where('pbp.codigo_programa', $programa->codigo);
                $this->db->where('ebp.codigo_emenda', $programa->emenda);

                //Filtrando para pegar apenas as propostas com status de cadastradas sem envio
                $this->db->where('bp.situacao', 'Proposta/Plano de Trabalho Cadastrados');

                $this->db->select('bp.*, ebp.codigo_emenda');
                $query = $this->db->get('banco_proposta bp');
                $tempPropostas = $query->result();

                if (count($tempPropostas) > 0) {
                    $propostas_banco_proposta = array_merge($propostas_banco_proposta, $tempPropostas);
                }
            }

            if (!empty($propostas_banco_proposta)) {
                foreach ($propostas_banco_proposta as $prop) {
                    $texto .= "<b>" . $prop->codigo_siconv . ": </b><br />";
                    $texto .= "<b>Orgão :</b>" . $prop->orgao . "<br />";
                    $texto .= "<b>Objeto :</b>" . $prop->orgao . "<br />";
                    $texto .= "<b>Valor Global :</b>" . $prop->valor_global . "<br />";
                    $texto .= "<b>Situação :</b>" . $prop->situacao . "<br />";
                    $texto .= "<b>Proponente :</b>" . $prop->nome_proponente . "<br />";
                    $texto .= "<br />";
                    $texto .= "<b>Código do programa :</b>" . $prop->codigo_programa . "<br />";
                    $texto .= "<b>Nome do programa :</b>" . $prop->nome_programa . "<br />";
                    $texto .= "<b>Emenda :</b>" . $prop->codigo_emenda . "<br />";
                    $texto .= "<br />";
                }

                $texto .= "<br />";

                $this->envia_email($email, $email_cc, $texto, "Propostas apenas cadastradas que utilizam emenda, tendo programa com vencimento em 5 dias.");
            }
        }
    }

    public function prepara_email_emendas($email, $email_cc = null, $filtro = "", $listaCNPJs = null, $usuarioNovo = false, $numeroParlamentar = null, $numDias = null, $infoconv = false) {
        try {
            if (!$usuarioNovo) {
                if ($numeroParlamentar == null) {
                    $texto = "Lista de Programas com Emendas e Beneficíarios Específicos do dia " . date("d/m/Y", strtotime("-1 days")) . ":<br><br>";
                    $this->db->where('(programa_novo IS NULL OR programa_novo = 1)', null);
                } else {
                    $texto = "Lista de Programas com Emendas do dia " . date("d/m/Y", strtotime("-1 days")) . ":<br><br>";
                }
            } else {
                if ($numeroParlamentar == null) {
                    $texto = "Lista de Programas Abertos:<br><br>";
                } else {
                    if ($numDias == null) {
                        $texto = "Lista de Programas Abertos com emendas:<br><br>";
                    } else {
                        if ($numDias == 10) {
                            $texto = "Lista de Programas Abertos com emendas com vencimento em 10 dias.<br><br>";
                        } elseif ($numDias == 5) {
                            $texto = "Lista de Programas Abertos com emendas com vencimento em 5 dias.<br><br>";
                        } else {
                            $texto = "Lista de Programas Abertos com emendas com vencimento hoje.<br><br>";
                        }
                    }
                }
            }

            if ($filtro != "") {
                $this->db->where($filtro);
            }

            $query_final = array();

            if ($numeroParlamentar == null) {
                $this->db->where_in('cnpj', $listaCNPJs);
                $this->db->join('siconv_beneficiario', 'codigo = codigo_programa');
                $this->db->where('siconv_beneficiario.excluido', 0);
                $this->db->where('programas_vigencia.excluido', 0);
                $query = $this->db->get('programas_vigencia')->result();

                $this->db->flush_cache();

                $this->db->where('tem_atualizacao = 1');
                if ($filtro != "")
                    $this->db->where($filtro);

                $this->db->where_in('cnpj', $listaCNPJs);
                $this->db->join('siconv_beneficiario', 'codigo = codigo_programa');
                $this->db->where('siconv_beneficiario.excluido', 0);
                $this->db->where('programas_vigencia.excluido', 0);
                $query2 = $this->db->get('programas_vigencia')->result();

                $this->db->flush_cache();

                $query_final = array_merge($query, $query2);
            } else {
                $this->db->distinct();
                $this->db->select('pv.*');
                $this->db->join('siconv_beneficiario sb', 'pv.codigo = sb.codigo_programa');
                if (!$infoconv) {
                    $this->db->where("sb.emenda <> '' AND sb.emenda LIKE '" . $numeroParlamentar . "%'");
                } else {
                    $this->db->where("sb.emenda <> ''");
                }

                if ($numDias != null) {
                    $this->db->where('(pv.data_fim', "DATE_ADD(CURDATE(), INTERVAL {$numDias} DAY)", FALSE);
                    $this->db->or_where('pv.data_fim_parlam', "DATE_ADD(CURDATE(), INTERVAL {$numDias} DAY))", FALSE);
                } else {
                    $this->db->where('(pv.programa_novo IS NULL OR pv.programa_novo = 1)', null);
                }

                $query = $this->db->get('programas_vigencia pv')->result();

                $this->db->flush_cache();

                if ($numDias == null) {
                    $this->db->where('pv.tem_atualizacao = 1');
                }
                if ($filtro != "") {
                    $this->db->where($filtro);
                }

                $this->db->distinct();
                $this->db->select('pv.*');
                $this->db->join('siconv_beneficiario sb', 'pv.codigo = sb.codigo_programa');
                $this->db->where("sb.emenda <> '' AND sb.emenda LIKE '" . $numeroParlamentar . "%'");

                if ($numDias != null) {
                    $this->db->where('(pv.data_fim', "DATE_ADD(CURDATE(), INTERVAL {$numDias} DAY)", FALSE);
                    $this->db->or_where('pv.data_fim_parlam', "DATE_ADD(CURDATE(), INTERVAL {$numDias} DAY))", FALSE);
                } else {
                    $this->db->where('(pv.programa_novo IS NULL OR pv.programa_novo = 1)', null);
                }

                $query2 = $this->db->get('programas_vigencia pv')->result();

                $query_final = array_merge($query, $query2);

                if ($numDias != null) {
                    $toRemove = array();
                    foreach ($query_final as $programa) {
                        $this->db->where('codigo', $programa->codigo);
                        $result = $this->db->get('programa_banco_proposta');
                        if (count($result->result()) > 0) {
                            array_push($toRemove, $programa);
                        }
                    }

                    $query_final = array_diff($query_final, $toRemove);
                }
            }

            if (!empty($query_final)) {
                foreach ($query_final as $prog) {
                    $style = "";
                    if ($prog->tem_atualizacao)
                        $style = "style='color:red;'";
                    $texto .= "<a " . $style . " href=\"" . $prog->link . "&path=/MostraPrincipalConsultarPrograma.do&Usr=guest&Pwd=guest\" target=\"_blank\">" . $prog->codigo . "</a>: ";
                    $texto .= $prog->orgao;
                    $texto .= " (" . $prog->qualificacao . ") - ";
                    $texto .= $prog->atende;
                    $texto .= ($prog->tem_atualizacao ? " <span style='color:red;'>(Programa com alteração na vigência)</span>" : "") . "<br />";
                    $texto .= "<b>Nome do Programa:</b> " . $prog->nome . "<br/>";
                    $texto .= "<b>Descrição:</b> " . $prog->descricao . "<br />";
                    $texto .= "<b>Estados Atendidos:</b> " . $prog->estados . "<br/>";

                    if (isset($prog->data_inicio) && strtotime($prog->data_inicio) > 0) {
                        $texto .= "<b>Proposta Voluntária<br>Início:</b> " . implode('/', array_reverse(explode('-', $prog->data_inicio))) . "&nbsp;&nbsp;
						<b>Fim:</b> " . implode('/', array_reverse(explode('-', $prog->data_fim))) . "<br/><br/>";
                    }

                    if (isset($prog->data_inicio_benef) && strtotime($prog->data_inicio_benef) > 0) {
                        $texto .= "<b>Proposta de Proponente Específico do Concedente<br>Início:</b> " . implode('/', array_reverse(explode('-', $prog->data_inicio_benef))) . "&nbsp;
						<b>Fim:</b> " . implode('/', array_reverse(explode('-', $prog->data_fim_benef))) . "<br/><br/>";
                    }

                    if (isset($prog->data_inicio_parlam) && strtotime($prog->data_inicio_parlam) > 0) {
                        $texto .= "<b>Proposta de Proponente de Emenda Parlamentar<br>Início:</b> " . implode('/', array_reverse(explode('-', $prog->data_inicio_parlam))) . "&nbsp;
						<b>Fim:</b> " . implode('/', array_reverse(explode('-', $prog->data_fim_parlam))) . "<br/><br/>";
                    }

                    if ($numeroParlamentar == null) {
                        $ben = $this->obter_beneficiario_unico($prog->codigo, $listaCNPJs);
                        if (count($ben) > 0)
                            $texto .= "<b>Beneficiários:</b><br />";
                        foreach ($ben as $beneficiario) {
                            $texto .= " - CNPJ: " . $beneficiario->cnpj . "<br />";
                            $texto .= " - Nome: " . $beneficiario->nome . "<br />";
                            $texto .= " - Valor: " . $beneficiario->valor . "<br />";
                            if (isset($beneficiario->emenda) && $beneficiario->emenda != "")
                                $texto .= " - Emenda: " . $beneficiario->emenda . "<br />";

                            if (isset($beneficiario->parlamentar) && $beneficiario->parlamentar != "")
                                $texto .= " - Parlamentar: " . $beneficiario->parlamentar . "<br />";
                            $texto .= "----------------------------<br />";
                        }
                    } else {
                        $ben = $this->obter_beneficiarios_programa_parlamentar($prog->codigo, $numeroParlamentar);
                        if (count($ben) > 0) {
                            $texto .= "<b>Beneficiários:</b><br />";
                        }
                        foreach ($ben as $beneficiario) {
                            $texto .= " - CNPJ: " . $beneficiario->cnpj . "<br />";
                            $texto .= " - Nome: " . $beneficiario->nome . "<br />";
                            $texto .= " - Valor: " . $beneficiario->valor . "<br />";
                            if (isset($beneficiario->emenda) && $beneficiario->emenda != "") {
                                $texto .= " - Emenda: " . $beneficiario->emenda . "<br />";
                            }

                            if (isset($beneficiario->parlamentar) && $beneficiario->parlamentar != "") {
                                $texto .= " - Parlamentar: " . $beneficiario->parlamentar . "<br />";
                            }
                            $texto .= "----------------------------<br />";
                        }
                    }

                    $texto .= "<br />";
                }

                if ($numDias == null) {
                    if ($infoconv) {
                        $this->envia_email_infoconveios($email, $email_cc, $texto, "Programa Novo com Emenda/Beneficíario Lançado no SICONV");
                    } else {
                        $this->envia_email($email, $email_cc, $texto, "Programa Novo com Emenda/Beneficíario Lançado no SICONV");
                    }
                } else {
                    if ($numDias == 5) {
                        if ($infoconv) {
                            $this->envia_email_infoconveios($email, $email_cc, $texto, "Programa com Emenda com vencimento em 5 dias sem propostas cadastradas.");
                        } else {
                            $this->envia_email($email, $email_cc, $texto, "Programa com Emenda com vencimento em 5 dias sem propostas cadastradas.");
                        }
                    } else {
                        if ($infoconv) {
                            $this->envia_email_infoconveios($email, $email_cc, $texto, "Programa com Emenda com vencimento hoje sem propostas cadastradas.");
                        } else {
                            $this->envia_email($email, $email_cc, $texto, "Programa com Emenda com vencimento hoje sem propostas cadastradas.");
                        }
                    }
                }
            }
        } catch (Exception $ex) {
            $this->load->model("log_model");
            $this->log_model->log_erro("get_programas/programas_abertos", "800", $ex->getMessage());
        }
    }

    public function valida_beneficiario($listaCnpjs, $listaBen) {
        if ($listaBen != NULL && $listaCnpjs != NULL) {
            if ($listaBen > 0 && $listaCnpjs > 0) {
                foreach ($listaBen as $beneficiario) {
                    if (in_array($beneficiario->cnpj, $listaCnpjs)) {
                        return true;
                    }
                }
            }
        }

        return false;
    }

    public function valida_beneficiario_com_retorno($listaCnpjs, $listaBen) {
        $ebenef = array();
        if ($listaBen != NULL && $listaCnpjs != NULL) {
            if ($listaBen > 0 && $listaCnpjs > 0) {
                foreach ($listaBen as $beneficiario) {
                    if (in_array($beneficiario->cnpj, $listaCnpjs)) {
                        array_push($ebenef, $beneficiario);
                    }
                }
            }
        }

        return $ebenef;
    }

    public function prepara_email($email, $email_cc = null, $filtro = "", $usuarioNovo = false, $infoconv = false, $listaCNPJs = null) {
        try {
            if (!$usuarioNovo) {
                $texto = "Lista de Programas Novos do dia " . date("d/m/Y", strtotime("today")) . ":<br><br>";

                $this->db->where('programa_novo', 1);
                $this->db->where('tem_atualizacao', 0);
            } else {
                $texto = "Lista de Programas Abertos:<br><br>";
            }

            if ($filtro != "") {
                $this->db->where($filtro);
            }
            $this->db->where('excluido', 0);
            $query = $this->db->get('programas_vigencia')->result();

            $this->db->flush_cache();

            $this->db->where('tem_atualizacao = 1');
            if ($filtro != "") {
                $this->db->where($filtro);
            }
            $this->db->where('excluido', 0);
            $query2 = $this->db->get('programas_vigencia')->result();

            $this->db->flush_cache();

            if ($query != null && !empty($query)) {
                $query_final = array_merge($query, $query2);
            } else {
                $query_final = $query2;
            }

            if (!empty($query_final)) {
                $envia = false;
                foreach ($query_final as $prog) {
                    try {
                        if (!$infoconv) {
                            $ben = $this->obter_beneficiarios($prog->codigo);
                            if ($this->valida_beneficiario($listaCNPJs, $ben)) {
                                $envia = true;
                                $style = "";
                                if ($prog->tem_atualizacao)
                                    $style = "style='color:red;'";
                                $texto .= "<a " . $style . " href=\"" . $prog->link . "&path=/MostraPrincipalConsultarPrograma.do&Usr=guest&Pwd=guest\" target=\"_blank\">" . $prog->codigo . "</a>: ";
                                $texto .= $prog->orgao;
                                $texto .= " (" . $prog->qualificacao . ") - ";
                                $texto .= $prog->atende;
                                $texto .= ($prog->tem_atualizacao ? " <span style='color:red;'>(Programa com alteração na vigência)</span>" : "") . "<br />";
                                $texto .= "<b>Nome do Programa:</b> " . $prog->nome . "<br/>";
                                $texto .= "<b>Descrição:</b> " . $prog->descricao . "<br />";
                                $texto .= "<b>Estados Atendidos:</b> " . $prog->estados . "<br/>";

                                if (isset($prog->data_inicio) && strtotime($prog->data_inicio) > 0) {
                                    $texto .= "<b>Proposta Voluntária<br>Início:</b> " . implode('/', array_reverse(explode('-', $prog->data_inicio))) . "&nbsp;&nbsp;
						<b>Fim:</b> " . implode('/', array_reverse(explode('-', $prog->data_fim))) . "<br/><br/>";
                                }

                                if (isset($prog->data_inicio_benef) && strtotime($prog->data_inicio_benef) > 0) {
                                    $texto .= "<b>Proposta de Proponente Específico do Concedente<br>Início:</b> " . implode('/', array_reverse(explode('-', $prog->data_inicio_benef))) . "&nbsp;
						<b>Fim:</b> " . implode('/', array_reverse(explode('-', $prog->data_fim_benef))) . "<br/><br/>";
                                }

                                if (isset($prog->data_inicio_parlam) && strtotime($prog->data_inicio_parlam) > 0) {
                                    $texto .= "<b>Proposta de Proponente de Emenda Parlamentar<br>Início:</b> " . implode('/', array_reverse(explode('-', $prog->data_inicio_parlam))) . "&nbsp;
						<b>Fim:</b> " . implode('/', array_reverse(explode('-', $prog->data_fim_parlam))) . "<br/><br/>";
                                }

                                $bens = $this->valida_beneficiario_com_retorno($listaCNPJs, $ben);
                                if (count($bens) > 0)
                                    $texto .= "<b>Beneficiários:</b><br />";
                                foreach ($bens as $beneficiario) {
                                    $texto .= " - CNPJ: " . $beneficiario->cnpj . "<br />";
                                    $texto .= " - Nome: " . $beneficiario->nome . "<br />";
                                    $texto .= " - Valor: " . $beneficiario->valor . "<br />";
                                    if (isset($beneficiario->emenda) && $beneficiario->emenda != "")
                                        $texto .= " - Emenda: " . $beneficiario->emenda . "<br />";

                                    if (isset($beneficiario->parlamentar) && $beneficiario->parlamentar != "")
                                        $texto .= " - Parlamentar: " . $beneficiario->parlamentar . "<br />";
                                    $texto .= "----------------------------<br />";
                                }

                                $texto .= "<br />";
                            } else {
                                if ($listaCNPJs == NULL || $listaCNPJs == 0 || strpos($prog->qualificacao, 'Voluntária')) {
                                    $envia = true;
                                    $style = "";
                                    if ($prog->tem_atualizacao)
                                        $style = "style='color:red;'";
                                    $texto .= "<a " . $style . " href=\"" . $prog->link . "&path=/MostraPrincipalConsultarPrograma.do&Usr=guest&Pwd=guest\" target=\"_blank\">" . $prog->codigo . "</a>: ";
                                    $texto .= $prog->orgao;
                                    $texto .= " (" . $prog->qualificacao . ") - ";
                                    $texto .= $prog->atende;
                                    $texto .= ($prog->tem_atualizacao ? " <span style='color:red;'>(Programa com alteração na vigência)</span>" : "") . "<br />";
                                    $texto .= "<b>Nome do Programa:</b> " . $prog->nome . "<br/>";
                                    $texto .= "<b>Descrição:</b> " . $prog->descricao . "<br />";
                                    $texto .= "<b>Estados Atendidos:</b> " . $prog->estados . "<br/>";

                                    if (isset($prog->data_inicio) && strtotime($prog->data_inicio) > 0) {
                                        $texto .= "<b>Proposta Voluntária<br>Início:</b> " . implode('/', array_reverse(explode('-', $prog->data_inicio))) . "&nbsp;&nbsp;
						<b>Fim:</b> " . implode('/', array_reverse(explode('-', $prog->data_fim))) . "<br/><br/>";
                                    }

                                    if (isset($prog->data_inicio_benef) && strtotime($prog->data_inicio_benef) > 0) {
                                        $texto .= "<b>Proposta de Proponente Específico do Concedente<br>Início:</b> " . implode('/', array_reverse(explode('-', $prog->data_inicio_benef))) . "&nbsp;
						<b>Fim:</b> " . implode('/', array_reverse(explode('-', $prog->data_fim_benef))) . "<br/><br/>";
                                    }

                                    if (isset($prog->data_inicio_parlam) && strtotime($prog->data_inicio_parlam) > 0) {
                                        $texto .= "<b>Proposta de Proponente de Emenda Parlamentar<br>Início:</b> " . implode('/', array_reverse(explode('-', $prog->data_inicio_parlam))) . "&nbsp;
						<b>Fim:</b> " . implode('/', array_reverse(explode('-', $prog->data_fim_parlam))) . "<br/><br/>";
                                    }

                                    $ben = $this->obter_beneficiarios($prog->codigo);
                                    if (count($ben) > 0)
                                        $texto .= "<b>Beneficiários:</b><br />";
                                    foreach ($ben as $beneficiario) {
                                        $texto .= " - CNPJ: " . $beneficiario->cnpj . "<br />";
                                        $texto .= " - Nome: " . $beneficiario->nome . "<br />";
                                        $texto .= " - Valor: " . $beneficiario->valor . "<br />";
                                        if (isset($beneficiario->emenda) && $beneficiario->emenda != "")
                                            $texto .= " - Emenda: " . $beneficiario->emenda . "<br />";

                                        if (isset($beneficiario->parlamentar) && $beneficiario->parlamentar != "")
                                            $texto .= " - Parlamentar: " . $beneficiario->parlamentar . "<br />";
                                        $texto .= "----------------------------<br />";
                                    }

                                    $texto .= "<br />";
                                }
                            }
                        } else {
                            $envia = true;
                            $style = "";
                            if ($prog->tem_atualizacao)
                                $style = "style='color:red;'";
                            $texto .= "<a " . $style . " href=\"" . $prog->link . "&path=/MostraPrincipalConsultarPrograma.do&Usr=guest&Pwd=guest\" target=\"_blank\">" . $prog->codigo . "</a>: ";
                            $texto .= $prog->orgao;
                            $texto .= " (" . $prog->qualificacao . ") - ";
                            $texto .= $prog->atende;
                            $texto .= ($prog->tem_atualizacao ? " <span style='color:red;'>(Programa com alteração na vigência)</span>" : "") . "<br />";
                            $texto .= "<b>Nome do Programa:</b> " . $prog->nome . "<br/>";
                            $texto .= "<b>Descrição:</b> " . $prog->descricao . "<br />";
                            $texto .= "<b>Estados Atendidos:</b> " . $prog->estados . "<br/>";

                            if (isset($prog->data_inicio) && strtotime($prog->data_inicio) > 0) {
                                $texto .= "<b>Proposta Voluntária<br>Início:</b> " . implode('/', array_reverse(explode('-', $prog->data_inicio))) . "&nbsp;&nbsp;
						<b>Fim:</b> " . implode('/', array_reverse(explode('-', $prog->data_fim))) . "<br/><br/>";
                            }

                            if (isset($prog->data_inicio_benef) && strtotime($prog->data_inicio_benef) > 0) {
                                $texto .= "<b>Proposta de Proponente Específico do Concedente<br>Início:</b> " . implode('/', array_reverse(explode('-', $prog->data_inicio_benef))) . "&nbsp;
						<b>Fim:</b> " . implode('/', array_reverse(explode('-', $prog->data_fim_benef))) . "<br/><br/>";
                            }

                            if (isset($prog->data_inicio_parlam) && strtotime($prog->data_inicio_parlam) > 0) {
                                $texto .= "<b>Proposta de Proponente de Emenda Parlamentar<br>Início:</b> " . implode('/', array_reverse(explode('-', $prog->data_inicio_parlam))) . "&nbsp;
						<b>Fim:</b> " . implode('/', array_reverse(explode('-', $prog->data_fim_parlam))) . "<br/><br/>";
                            }

                            $ben = $this->obter_beneficiarios($prog->codigo);
                            if (count($ben) > 0)
                                $texto .= "<b>Beneficiários:</b><br />";
                            foreach ($ben as $beneficiario) {
                                $texto .= " - CNPJ: " . $beneficiario->cnpj . "<br />";
                                $texto .= " - Nome: " . $beneficiario->nome . "<br />";
                                $texto .= " - Valor: " . $beneficiario->valor . "<br />";
                                if (isset($beneficiario->emenda) && $beneficiario->emenda != "")
                                    $texto .= " - Emenda: " . $beneficiario->emenda . "<br />";

                                if (isset($beneficiario->parlamentar) && $beneficiario->parlamentar != "")
                                    $texto .= " - Parlamentar: " . $beneficiario->parlamentar . "<br />";
                                $texto .= "----------------------------<br />";
                            }

                            $texto .= "<br />";
                        }
                    } catch (Exception $ex) {
                        $this->load->model("log_model");
                        $this->log_model->log_erro("get_programas/programas_abertos", "800", $ex->getMessage());
                        //ignorando o erro momentaneamente
                    }
                }

                if ($envia) {
                    if ($infoconv) {
                        $this->envia_email_infoconveios($email, $email_cc, $texto, "Programa Novo Lançado no SICONV");
                    } else {
                        $this->envia_email($email, $email_cc, $texto, "Programa Novo Lançado no SICONV");
                    }
                }
            }
        } catch (Exception $ex) {
            $this->load->model("log_model");
            $this->log_model->log_erro("get_programas/programas_abertos", "800", $ex->getMessage());
        }
    }

    public function marca_programas_antigos() {
        $filtro = "((`siconv_programa`.`data_fim` >= CURDATE())
            OR (`siconv_programa`.`data_fim_benef` >= CURDATE())
            OR (`siconv_programa`.`data_fim_parlam` >= CURDATE()))";
        $this->db->update('siconv_programa', array('programa_novo' => '0'), $filtro);
    }

    function obter_beneficiarios($codigo) {
        $this->db->where('codigo_programa', $codigo);

        $query = $this->db->get('siconv_beneficiario');
        return $query->result();
    }

    public function obter_beneficiario_unico($codigo, $listaCNPJs) {
        $this->db->where('codigo_programa', $codigo);
        $this->db->where_in('cnpj', $listaCNPJs);

        $query = $this->db->get('siconv_beneficiario');
        return $query->result();
    }

    public function obter_beneficiarios_programa_parlamentar($codigo, $numeroParlamentar) {
        $this->db->where('codigo_programa', $codigo);
        $this->db->where("emenda like '" . $numeroParlamentar . "%'");

        $query = $this->db->get('siconv_beneficiario');
        return $query->result();
    }

    function obter_programas_emenda($dataInicio, $dataFim, $vigencia, $emenda, $cnpj) {
        $data_atual = date('Y-m-d');

        $this->db->select('siconv_beneficiario.codigo_programa as codigo_programa');
        $this->db->select('siconv_beneficiario.cnpj as cnpj_beneficiario');
        $this->db->select('siconv_beneficiario.nome as nome_beneficiario');
        $this->db->select('siconv_beneficiario.valor as valor_beneficiario');
        $this->db->select('siconv_beneficiario.emenda as emenda_beneficiario');
        $this->db->select('siconv_beneficiario.parlamentar as parlamentar_beneficiario');
        $this->db->select('proponentes_municipios.estado as estado');
        $this->db->select('siconv_programa.*');

        $this->db->from('siconv_beneficiario');
        $this->db->join('siconv_programa', 'siconv_beneficiario.codigo_programa = siconv_programa.codigo', 'left');
        $this->db->join('proponentes_municipios', 'proponentes_municipios.cnpj = siconv_beneficiario.cnpj', 'left');
        if ($vigencia == null) {
            $this->db->where('siconv_programa.data_fim >=', $dataInicio);
            $this->db->where('siconv_programa.data_fim <=', $dataFim);
        } else {
            $this->db->where('siconv_programa.data_inicio <=', $data_atual);
            $this->db->where('siconv_programa.data_fim >=', $data_atual);
        }
        if (trim($emenda) != '' && $emenda != false)
            $this->db->where('siconv_beneficiario.emenda', $emenda);
        if (trim($cnpj) != '' && $cnpj != false) {
            $cnpj = $this->formatCPFCNPJ($cnpj);

            $this->db->where('siconv_beneficiario.cnpj', $cnpj);
        }
        $query = $this->db->get();

        return $query->result();
    }

    function formatCPFCNPJ($string) {
        $output = preg_replace("[' '-./ t]", '', $string);
        $size = (strlen($output) - 2);
        if ($size != 9 && $size != 12)
            return false;
        $mask = ($size == 9) ? '###.###.###-##' : '##.###.###/####-##';
        $index = - 1;
        for ($i = 0; $i < strlen($mask); $i ++) :
            if ($mask [$i] == '#')
                $mask [$i] = $output [++$index];
        endfor
        ;
        return $mask;
    }

    function obter_por_estado($dataInicio, $dataFim, $estado, $orgao = null, $qualificacao = null, $atende = null, $vigencia = null) {
        $data_atual = date('Y-m-d');
        $this->db->where('(estados like(\'Todos os Estados estão Aptos\') OR estados LIKE \'%' . $estado . '%\')', null);
        if ($vigencia == null) {
            $this->db->where('data_fim >=', $dataInicio);
            $this->db->where('data_fim <=', $dataFim);
        } else {
            $this->db->where('data_inicio <=', $data_atual);
            $this->db->where('data_fim >=', $data_atual);
        }
        if ($orgao !== '')
            $this->db->where('orgao', $orgao);
        if ($qualificacao !== '')
            $this->db->where('qualificacao', $qualificacao);
        if ($atende !== '')
            $this->db->where('atende', $atende);
        // $insert_query = $this->db->insert_string('siconv_beneficiario', $options);
        // $insert_query = str_replace('INSERT INTO', 'INSERT IGNORE INTO', $insert_query);
        $query = $this->db->get('siconv_programa');
        // echo $this->db->last_query(); die();
        return $query->result();
    }

    function obter_por_cidade($dataInicio, $dataFim, $cnpj, $orgao = null, $qualificacao = null, $atende = null, $vigencia = null) {
        $data_atual = date('Y-m-d');
        $this->db->select('*');
        $this->db->from('siconv_beneficiario');
        $this->db->join('siconv_programa', 'siconv_beneficiario.codigo_programa = siconv_programa.codigo');
        if ($vigencia == null) {
            $this->db->where('siconv_programa.data_fim >=', $dataInicio);
            $this->db->where('siconv_programa.data_fim <=', $dataFim);
        } else {
            $this->db->where('siconv_programa.data_inicio <=', $data_atual);
            $this->db->where('siconv_programa.data_fim >=', $data_atual);
        }
        // $this->db->where('siconv_beneficiario.cnpj', $cnpj);
        $this->db->where('(siconv_beneficiario.nome like \'%municipio%\' OR siconv_beneficiario.nome like \'%prefeitura%\')');
        $this->db->like('siconv_beneficiario.nome', $cnpj);
        if ($orgao !== '')
            $this->db->where('siconv_programa.orgao', $orgao);
        if ($qualificacao !== '')
            $this->db->where('siconv_programa.qualificacao', $qualificacao);
        if ($atende !== '')
            $this->db->where('siconv_programa.atende', $atende);
        $query = $this->db->get();
        // echo $this->db->last_query(); die();
        return $query->result();
    }

    function add_records($options = array()) {
        $insert_query = $this->db->insert_string('siconv_programa', $options);
        $insert_query = str_replace('INSERT INTO', 'INSERT IGNORE INTO', $insert_query);
        $this->db->query($insert_query);
        return $this->db->affected_rows();
    }

    function programa_usuario_permissao($id) {
        $this->db->where('idPessoa', $id);
        $query = $this->db->get('siconv_usuario_programa');
        if (isset($query->row(0)->acesso) !== false)
            return $query->row(0)->acesso;
        return null;
    }

    function insere_programa_usuario($options = array()) {
        $this->db->insert('siconv_usuario_programa', $options);
        return $this->db->affected_rows();
    }

    function aceita_programa_usuario($options = array()) {
        $options1 = array(
            'aceito' => true
        );
        $this->db->where('idPessoa', $options ['idPessoa']);
        $this->db->where('codigoPrograma', $options ['codigoPrograma']);
        $this->db->update('siconv_usuario_programa', $options1);
        return $this->db->affected_rows();
    }

    function insere_beneficiario($options = array()) {
        $insert_query = $this->db->insert_string('siconv_beneficiario', $options);
        $insert_query = str_replace('INSERT INTO', 'INSERT IGNORE INTO', $insert_query);
        $this->db->query($insert_query);
        return $this->db->affected_rows();
    }

    function delete_record($id) {
        $this->db->where('idProposta', $id);
        $options ['ativo'] = 0;
        $this->db->update('proposta', $options);
        return $this->db->affected_rows();
    }

    function apagar_dados() {
        $this->db->truncate('siconv_beneficiario');
        $this->db->truncate('siconv_programa');
    }

    function apagar_dados_usuario_programa($id) {
        $this->db->where('idPessoa', $id);
        $this->db->delete('siconv_usuario_programa');
    }

    function apagar_dados_usuario_programa_nao_aceitos($id) {
        $options = array(
            'aceito' => false,
            'acesso' => false
        );
        $this->db->where('idPessoa', $id);
        $this->db->update('siconv_usuario_programa', $options);
        return $this->db->affected_rows();
    }

    function permitir_programas_por_usuario($id) {
        $options = array(
            'acesso' => true
        );
        $this->db->where('idPessoa', $id);
        $this->db->update('siconv_usuario_programa', $options);
        return $this->db->affected_rows();
    }

    function update_record($id, $options = array()) {
        $this->db->where('idProposta', $id);
        $this->db->update('proposta', $options);
        return $this->db->affected_rows();
    }

    function obter_por_codigo($programas) {
        $this->db->where('codigo', $programas);
        $query = $this->db->get('siconv_programa');
        return $query->row(0);
    }

    function obter_por_usuario($id) {
        $this->db->distinct();
        $this->db->select('id_correspondente');
        $this->db->where('Pessoa_idPessoa', $id);
        $query = $this->db->get('trabalho');
        return $query->result();
    }

    function obter_bancos() {
        $query = $this->db->get('banco');
        return $query->result();
    }

    function obter_estados() {
        $this->db->order_by('sigla');
        $query = $this->db->get('estados');
        $area = $query->result_array();
        return $area;
    }

    function obter_cidades($id) {
        $this->db->distinct();
        $this->db->where('estados_cod_estados', $id)->order_by('nome');
        $query = $this->db->get('cidades');
        $area = $query->result_array();
        return $area;
    }

    public function envia_email($email, $email1, $texto, $assunto) {
        $this->load->model('usuariomodel');

        $this->load->library('email', $this->usuariomodel->inicializa_config_email_gmail("physisbrasil@gmail.com"));
        $this->email->set_newline("\r\n");

        $this->email->from('physisbrasil@gmail.com', 'Captação Recursos E-Sicar');
        $this->email->to($email);

        if ($email1 != null && $email1 != '' && $email1 != NULL) {
            $this->email->cc($email1);
        } else {
            $this->email->cc(NULL);
        }

        $this->email->subject($assunto);
        $this->email->message(utf8_decode("<html>" . $texto . "</html>"));
        $result = $this->email->send();
        return $result;
    }

    public function envia_email_host($email, $email1, $texto, $assunto) {
        $this->load->model('usuariomodel');

        $this->load->library('email', $this->usuariomodel->inicializa_config_email("contato@physisbrasil.com.br"));
        $this->email->set_newline("\r\n");

        $this->email->from('contato@physisbrasil.com.br', 'Captação Recursos E-Sicar');
        $this->email->to($email);
        if ($email1 != null)
            $this->email->cc($email1);

        $this->email->subject($assunto);
        $this->email->message(utf8_decode("<html>" . $texto . "</html>"));
        $result = $this->email->send();
        return $result;
    }

    function envia_email_infoconveios($email, $email1, $texto, $assunto) {
        $this->load->model('usuariomodel');

        $this->load->library('email');

        $this->email->initialize($this->usuariomodel->inicializa_config_email("no-reply@info-convenios.com"));
        $this->email->from('no-reply@info-convenios.com', 'Info Convenios');
        $this->email->to($email);
        if ($email1 != null)
            $this->email->cc($email1);

        $this->email->subject($assunto);
        $this->email->message(utf8_decode("<html>" . $texto . "</html>"));
        $this->email->send();
    }

    public function updateDatasProjeto($id) {
        if ($id > 0) {
            $qtdRegistrosAlterados = 0;

            $this->load->model('data_model');

            $this->db->select("data_inicio, data_termino");
            $this->db->where("idProposta", $id);
            $proposta = $this->db->get("proposta")->row(0);

            $this->db->cache_delete_all();

            $qtdDiasProposta = $this->data_model->retornaDiffDatas($proposta->data_inicio, $proposta->data_termino);
            $dataInicioProp_old = $proposta->data_inicio;
            $dataTerminoProp_old = $proposta->data_termino;

            $proposta->data_inicio = $this->gera_nova_data();
            $proposta->data_termino = $this->data_model->retornaNovaData($proposta->data_inicio, $qtdDiasProposta);

            $this->db->select("idMeta, data_inicio, data_termino");
            $this->db->where("Proposta_idProposta", $id);
            $metas = $this->db->get("meta")->result();

            $this->db->cache_delete_all();

            foreach ($metas as $meta) {
                $dataIniMeta = $this->data_model->calcula_datas_inicio($dataInicioProp_old, $proposta->data_inicio, $meta->data_inicio);
                $dataFimMeta = $this->data_model->retornaNovaData($dataIniMeta, $this->data_model->retornaDiffDatas($meta->data_inicio, $meta->data_termino));

                $optionsMeta = array(
                    'data_inicio' => $dataIniMeta,
                    'data_termino' => $dataFimMeta
                );

                $this->db->where('Proposta_idProposta', $id);
                $this->db->where('idMeta', $meta->idMeta);
                $this->db->update('meta', $optionsMeta);

                $qtdRegistrosAlterados += $this->db->affected_rows();

                $this->db->cache_delete_all();

                $this->db->where('Meta_idMeta', $meta->idMeta);
                $this->db->update('etapa', $optionsMeta);

                $qtdRegistrosAlterados += $this->db->affected_rows();

                $this->db->cache_delete_all();
            }

            $this->db->select("idCronograma, mes, ano");
            $this->db->where('Proposta_idProposta', $id);
            $cronogramas = $this->db->get('cronograma')->result();

            foreach ($cronogramas as $cronograma) {
                $dataCronoFull = $cronograma->ano . "-" . $cronograma->mes . "-01";
                $dataIniCrono = $this->data_model->calcula_datas_inicio($dataInicioProp_old, $proposta->data_inicio, $dataCronoFull);

                if (strtotime($dataIniCrono) > strtotime($proposta->data_termino))
                    $dataCronoPartes = explode("-", $proposta->data_termino);
                else
                    $dataCronoPartes = explode("-", $dataIniCrono);

                $optionsCrono = array(
                    'ano' => $dataCronoPartes [0],
                    'mes' => $dataCronoPartes [1]
                );

                $this->db->where('Proposta_idProposta', $id);
                $this->db->where('idCronograma', $cronograma->idCronograma);
                $this->db->update('cronograma', $optionsCrono);

                $qtdRegistrosAlterados += $this->db->affected_rows();

                $this->db->cache_delete_all();
            }

            $optionsProj = array(
                'data_inicio' => $proposta->data_inicio,
                'data_termino' => $proposta->data_termino
            );

            $this->db->where('idProposta', $id);
            $this->db->update('proposta', $optionsProj);

            $qtdRegistrosAlterados += $this->db->affected_rows();

            return $qtdRegistrosAlterados;
        } else
            return "Erro ao tentar acessar o projeto.";
    }

    public function gera_nova_data() {
        $dia = date("d");
        if ($dia <= 15)
            return date("Y-m-d", mktime(0, 0, 0, date("m") + 1, "01", date("Y")));
        else
            return date("Y-m-d", mktime(0, 0, 0, date("m") + 2, "01", date("Y")));
    }

    public function getListaEstados() {
        return array("1" => "AC",
            "2" => "AL",
            "4" => "AM",
            "3" => "AP",
            "5" => "BA",
            "6" => "CE",
            "27" => "DF",
            "7" => "ES",
            "8" => "GO",
            "9" => "MA",
            "12" => "MG",
            "11" => "MS",
            "10" => "MT",
            "13" => "PA",
            "14" => "PB",
            "16" => "PE",
            "17" => "PI",
            "15" => "PR",
            "18" => "RJ",
            "19" => "RN",
            "21" => "RO",
            "22" => "RR",
            "20" => "RS",
            "23" => "SC",
            "25" => "SE",
            "24" => "SP",
            "26" => "TO");
    }

    public function get_parlamentar_by_emenda($emenda) {
        $this->db->where("substr(emenda, 1, 4) = '" . substr($emenda, 0, 4) . "'");
        $this->db->where('parlamentar is not null');
        $parlamentar = $this->db->get('siconv_beneficiario')->row(0);

        if ($parlamentar != null)
            return $parlamentar->parlamentar;
        else
            return "";
    }

    public function get_programas_vencimento($dias_add, $filtro_estado = "", $filtro_proponente = "") {
        if ($filtro_estado != "") {
            $this->db->where($filtro_estado);
        } else {
            if ($this->session->userdata('nivel') != 1) {
                $estados = $this->usuariomodel->get_estados_by_usuario($this->session->userdata('id_usuario'));
                $this->db->where('id_usuario', $this->session->userdata('id_usuario'));
                $dadosGestor = $this->db->get('gestor')->row(0);

                $this->db->flush_cache();

                $array = "1=1";

                if (($this->session->userdata('usuario_sistema') == "T" && $this->session->userdata('nivel') == 4) || ($this->session->userdata('usuario_sistema') == "T" && $this->session->userdata('nivel') == 15) || $this->session->userdata('usuario_sistema') == "M" || $this->session->userdata('usuario_sistema') == "E") {
                    $i = 1;
                    $array .= " and (";
                    $lista_estados = "";
                    foreach ($estados as $estado) {
                        if ($this->session->userdata('nivel') != 1 && $this->session->userdata('nivel') != 4 && $this->session->userdata('usuario_sistema') == "E") {
                            $lista_estados .= "estados like '%{$estado->sigla}%'";
                            break;
                        }
                        if ($i == 1) {
                            $lista_estados .= "estados like '%{$estado->sigla}%'";
                            $i++;
                        } else
                            $lista_estados .= "or estados like '%{$estado->sigla}%'";
                    }
                    if ($lista_estados != "")
                        $array .= $lista_estados . " or ";
                    $array .= " estados like 'Todos os Estados estão Aptos'";
                    $array .= ")";
                }else if ($this->session->userdata('usuario_sistema') == "P") {
                    if ($this->session->userdata('nivel') == 3 || $this->session->userdata('nivel') == 5) {
                        $usuario_gestor = $this->db->query("SELECT * FROM gestor join usuario_gestor on gestor.id_gestor = usuario_gestor.id_gestor where usuario_gestor.id_usuario = " . $this->session->userdata('id_usuario'))->row(0);
                        $estado_parlamentar = $usuario_gestor->estado_parlamentar;
                    } else
                        $estado_parlamentar = $dadosGestor->estado_parlamentar;

                    $array .= " and (";
                    $array .= "estados like '%{$estado_parlamentar}%'" . " or ";
                    $array .= " estados like 'Todos os Estados estão Aptos'";
                    $array .= ")";
                }

                switch ($this->session->userdata('usuario_sistema')) {
                    case "M":
                        $array .= " and atende like '%Administração Pública Municipal%'";
                        break;
                    case "E":
                        $array .= " and atende like '%Estadual%'";
                        break;
                    case "T":
                        $array .= " and atende like '%Administração Pública Municipal%' and atende like '%Estadual%'";
                        break;
                }

                $this->db->where($array, null, false);
            }
        }

        $this->db->where('(data_fim', "DATE_ADD(CURDATE(), INTERVAL {$dias_add} DAY)", FALSE);
        $this->db->or_where('data_fim_benef', "DATE_ADD(CURDATE(), INTERVAL {$dias_add} DAY)", FALSE);
        $this->db->or_where('data_fim_parlam', "DATE_ADD(CURDATE(), INTERVAL {$dias_add} DAY))", FALSE);
        //$this->db->where('excluido is null');
        $this->db->where('programas_vigencia.excluido', 0);
        $query = $this->db->get('programas_vigencia')->result();

        //Filtro para emendas tem que verificar a qualificação do programa
        $programas_validos = array();
        foreach ($query as $programa) {
            if (strpos($programa->qualificacao, 'Concedente') !== FALSE || strpos($programa->qualificacao, 'Parlamentar') !== FALSE) {
                foreach ($filtro_proponente as $cnpj) {
                    $this->db->where('codigo_programa', $programa->codigo);
                    $this->db->where('cnpj', $this->formatCPFCNPJ($cnpj));
                    $this->db->where('excluido', 0);
                    $result = $this->db->get('siconv_beneficiario');
                    if ($result->num_rows > 0) {
                        array_push($programas_validos, $programa);
                    }
                }
            } else {
                array_push($programas_validos, $programa);
            }
        }

        return $programas_validos;
    }

    /**
     * Recebe o CNPJ que está sendo utilizado para o desenvolvimento da proposta e codigo do programa
     * e retorna TRUE se o programa para este proponente é especifico beneficiário e FALSE caso contrario
     * @param unknown $cnpj
     * @param unknown $codigo
     */
    public function check_tem_beneficiario($cnpj, $codigo) {
        $this->db->where('codigo', $codigo);
        $this->db->where('cnpj', $this->formatCPFCNPJ($cnpj));
        $this->db->where('siconv_beneficiario.emenda = ""');
        $this->db->join("siconv_beneficiario", "codigo = codigo_programa");
        $query = $this->db->get('siconv_programa');

        return $query->num_rows > 0;
    }

    public function get_emendas_by_parlamentar_from_beneficiario($filtro = null) {
        $numParlamentar = null;

        if ($this->session->userdata('nivel') == 2) {
            $this->db->where('id_usuario', $this->session->userdata('id_usuario'));
            $gestor = $this->db->get('gestor')->row(0);

            $numParlamentar = $gestor->numero_parlamentar;
        } else if ($this->session->userdata('nivel') == 4 || $this->session->userdata('nivel') == 15) {
            $this->load->model('usuariomodel');
            $usuario = $this->usuariomodel->get_by_id($this->session->userdata('id_usuario'));

            $numParlamentar = $usuario->vendedor_codigo_parlamentar;
        }

        $this->db->flush_cache();

        $this->db->select('sb.codigo_programa, sb.cnpj, sb.nome, sb.emenda, sb.data_inicio_parlam, sb.valor');

        if (!is_null($filtro)) {
            if (isset($filtro['num_emenda']) && $filtro['num_emenda'] != "") {
                $this->db->where('emenda', $filtro['num_emenda']);
            }

            if (isset($filtro['proponente_nome'])) {
                if (is_array($filtro['proponente_nome'])) {
                    /* Nessa busca não precisa remover barras e pontos dos CNPJS 
                      Por se tratar de um array de CNPJs que vêm dum multiselect,
                      ele nunca vem vazio. É vazio quando o primeiro elemento é uma string vazia */
                    if ($filtro['proponente_nome'] != null && $filtro['proponente_nome'][0] != '') {
                        $this->db->where_in('cnpj', $filtro['proponente_nome']);
//                        foreach ($filtro['proponente_nome'] as $p) {
//                            if ($p != '')
//                                $this->db->or_where('cnpj', $p);
//                        }
                    }
                } else {
                    if ($filtro['proponente_nome'] != '') {
                        $this->db->like('nome', $filtro['proponente_nome']);
                    }
                }
            }

            if (isset($filtro['anos'])) {
                if (!in_array("TODOS", $filtro['anos']))
                    $this->db->where_in('EXTRACT(year from sb.data_inicio_parlam) ', $filtro['anos'], FALSE);
            } else {
                $this->db->where('EXTRACT(year from sb.data_inicio_parlam) = ', date("Y"), FALSE);
            }
        } else
            $this->db->where('EXTRACT(year from sb.data_inicio_parlam) = ', date("Y"), FALSE);

        $filtroParlamentar = "";
        $codigos = explode(",", $numParlamentar);
        foreach ($codigos as $c)
            $filtroParlamentar .= "sb.emenda LIKE '" . trim($c) . "%' OR ";

        $filtroParlamentar = "(" . trim($filtroParlamentar, " OR ") . ")";

        $this->db->where($filtroParlamentar);
        $this->db->order_by('EXTRACT(year from sb.data_inicio_parlam)', 'desc');
        $this->db->order_by('sb.emenda', 'desc');
        $query = $this->db->get('siconv_beneficiario sb');

        return $query->result();
    }

    public function get_municipios_by_emendas($data = null, $montaDropDown = false) {
        $this->load->model('proponente_siconv_model');

        $emendas = $this->get_emendas_by_parlamentar_from_geral($data, $montaDropDown);

        $listaMunicipios = array();
        $listaPropFeitos = array();
        foreach ($emendas as $e) {
            if (!in_array($e->cnpj, $listaPropFeitos)) {
                $municipio = $this->proponente_siconv_model->get_municipio_by_cnpj($e->cnpj, false, $data, $montaDropDown);
                if (!empty($municipio)) {
                    if (!$montaDropDown) {
                        $listaMunicipios[$municipio->municipio][] = $municipio->codigo_municipio;
                        $listaMunicipios[$municipio->municipio][] = $municipio->cnpj;
                    } else
                        $listaMunicipios[$municipio->municipio] = $municipio->codigo_municipio;
                }
            }

            $listaPropFeitos[] = $e->cnpj;
        }

        ksort($listaMunicipios);
        return $listaMunicipios;
    }

    public function get_esferas_by_emendas($data = null) {
        $this->load->model('proponente_siconv_model');

        $emendas = $this->get_emendas_by_parlamentar_from_geral($data, true);

        $listaEsferas = array();
        $listaEsfFeitas = array();
        foreach ($emendas as $e) {
            if (!in_array($e->cnpj, $listaEsfFeitas)) {
                $esfera = $this->proponente_siconv_model->get_instituicao_esfera($e->cnpj, true);
                if (!empty($esfera))
                    $listaEsferas[$esfera->esfera_administrativa] = $esfera->esfera_administrativa;
            }

            $listaEsfFeitas[] = $e->cnpj;
        }

        return $listaEsferas;
    }

    public function get_emendas_by_parlamentar_from_geral($filtro = null, $montaDropDown = false) {
        $estado_parlamentar = null;
        $lista_cnpjs = array();

        if ($this->session->userdata('nivel') == 4 || $this->session->userdata('nivel') == 15) {
            if ($this->session->userdata('sistema') == 'M') {
                $this->load->model('usuariomodel');
                $cnpjs = $this->usuariomodel->get_estados_by_usuario($this->session->userdata('id_usuario'));

                $estado_parlamentar = $cnpjs[0]->sigla;

                foreach ($cnpjs as $c)
                    $lista_cnpjs[] = $this->formatCPFCNPJ($c->cnpj);
            } else {
                $estado_parlamentar = $this->session->userdata('estado_parlamentar');
            }
        } else if ($this->session->userdata('nivel') == 2 || $this->session->userdata('nivel') == 3 || $this->session->userdata('nivel') == 5 || $this->session->userdata('nivel') == 6 || $this->session->userdata('nivel') == 7 || $this->session->userdata('nivel') == 8) {
            if ($this->session->userdata('sistema') == 'M') {
                $this->load->model('usuariomodel');
                $cnpjs = $this->usuariomodel->get_estados_by_usuario($this->session->userdata('id_usuario'));

                $estado_parlamentar = $cnpjs[0]->sigla;

                foreach ($cnpjs as $c)
                    $lista_cnpjs[] = $this->formatCPFCNPJ($c->cnpj);
            } else {
                $this->db->where('id_usuario', $this->session->userdata('id_usuario'));
                $query = $this->db->get('gestor')->row(0);

                $estado_parlamentar = $query->estado_parlamentar;
            }
        }

        $this->db->flush_cache();

        $this->db->select('sb.codigo_programa, sb.cnpj, sb.nome, sb.emenda, sb.parlamentar, sb.data_inicio_parlam, sb.valor');

        if (!is_null($filtro)) {
            if (isset($filtro['num_emenda']) && $filtro['num_emenda'] != "") {
                $this->db->where('sb.emenda', $filtro['num_emenda']);
            }


            if (isset($filtro['proponente_nome'])) {
                if (is_array($filtro['proponente_nome'])) {
                    /* Nessa busca não precisa remover barras e pontos dos CNPJS */
                    /* Usado no sistema parlamentar ao carregar emendas de vários proponentes (pelo CNPJ */
                    if ($filtro['proponente_nome'] != null) {
                        $this->db->where_in('sb.cnpj', $filtro['proponente_nome']);
//                        foreach ($filtro['proponente_nome'] as $p) {
//                            if ($p != '')
//                                $this->db->where('sb.cnpj', $p);
//                        }
                    }
                } else {
                    if ($filtro['proponente_nome'] != "") {
                        $this->db->like('sb.nome', $filtro['proponente_nome']);
                    }
                }
            }

            if (isset($filtro['anos'])) {
                if (!in_array("TODOS", $filtro['anos']))
                    $this->db->where_in('EXTRACT(year from sb.data_inicio_parlam) ', $filtro['anos'], FALSE);
            } else {
                $this->db->where('EXTRACT(year from sb.data_inicio_parlam) = ', date("Y"), FALSE);
                //$this->db->where('EXTRACT(year from sb.data_inicio_parlam) = ', 2017, FALSE);
            }

            if (!$montaDropDown) {
                if (isset($filtro['proponente']))
                    $this->db->where_in('codigo_municipio', $filtro['proponente']);
            }
        } else
            $this->db->where('EXTRACT(year from sb.data_inicio_parlam) = ', date("Y"), FALSE);
        //$this->db->where('EXTRACT(year from sb.data_inicio_parlam) = ', 2017, FALSE);

        $this->db->order_by('EXTRACT(year from sb.data_inicio_parlam)', 'desc');
        $this->db->order_by('sb.emenda', 'desc');

        if (!is_null($estado_parlamentar))
            $this->db->where('municipio_uf_sigla', $estado_parlamentar);

        if (!empty($lista_cnpjs))
            $this->db->where_in('ps.cnpj', $lista_cnpjs);

        $this->db->join('proponente_siconv ps', "ps.cnpj = sb.cnpj and emenda is not null and emenda <> 'null' and emenda <> ''");
        $query = $this->db->get('siconv_beneficiario sb');

        return $query->result();
    }

    public function get_all_emendas_by_usuario($filtro = null) {
        $numParlamentar = null;
        $lista_cnpjs = array();

        if ($this->session->userdata('nivel') == 2) {
            if ($this->session->userdata('sistema') == 'M') {
                $this->load->model('usuariomodel');
                $cnpjs = $this->usuariomodel->get_estados_by_usuario($this->session->userdata('id_usuario'));

                $estado_parlamentar = $cnpjs[0]->sigla;

                foreach ($cnpjs as $c)
                    $lista_cnpjs[] = $c->cnpj;
            } else {
                $this->db->where('id_usuario', $this->session->userdata('id_usuario'));
                $gestor = $this->db->get('gestor')->row(0);

                $numParlamentar = $gestor->numero_parlamentar;
            }
        } else if ($this->session->userdata('nivel') == 4 || $this->session->userdata('nivel') == 15) {
            $this->load->model('usuariomodel');
            $usuario = $this->usuariomodel->get_by_id($this->session->userdata('id_usuario'));

            $numParlamentar = $usuario->vendedor_codigo_parlamentar;
        }

        $this->db->flush_cache();

        //$this->db->select('b.*, e.codigo_emenda');
        $filtroParlamentar = "";
        $codigos = explode(",", $numParlamentar);
        foreach ($codigos as $c)
            $filtroParlamentar .= "codigo_emenda LIKE '" . trim($c) . "%' OR ";

        $filtroParlamentar = "(" . trim($filtroParlamentar, " OR ") . ")";

        if (!is_null($filtro)) {
            if (isset($filtro['num_emenda']) && $filtro['num_emenda'] != "") {
                $this->db->where('codigo_emenda', $filtro['num_emenda']);
            } else {
                //$this->db->like('codigo_emenda', $numParlamentar, 'after');
                $this->db->where($filtroParlamentar);
            }

            if (isset($filtro['proponente_nome'])) {
                if (is_array($filtro['proponente_nome'])) {

                    /* Usado no sistema parlamentar ao carregar emendas de vários proponentes (pelo CNPJ */
                    if ($filtro['proponente_nome'] != null) {
                        $trimmedCNPJ = array();
                        /* Tira as barras e pontos dos CNPJS pra fazer a busca */
                        foreach ($filtro['proponente_nome'] as $p) {
                            if ($p != '') {
                                $trimmedCNPJ[] = str_replace("-", "", str_replace("/", "", str_replace(".", "", $p)));
                            }
                        }
                        if (!empty($trimmedCNPJ)) {
                            $this->db->where_in('b.proponente', $trimmedCNPJ);
                        }
//                        foreach ($filtro['proponente_nome'] as $p) {
//                            if ($p != '')
//                                $this->db->where('b.proponente', $p);
//                        }
                    }
                } else {
                    if ($filtro['proponente_nome'] != "") {
                        $this->db->like('b.proponente', $filtro['proponente_nome']);
                    }
                }
            }
        } else {
            //$this->db->like('codigo_emenda', $numParlamentar, 'after');
            $this->db->where($filtroParlamentar);
        }

        if (!empty($lista_cnpjs)) {
            $this->db->where_in('b.proponente', $lista_cnpjs);
            $this->db->where('EXTRACT(year from b.data_inicio) = ', date("Y"), FALSE);
        }

        $this->db->join('programa_banco_proposta p', 'id_proposta = id_proposta_banco_proposta');
        $this->db->join('emenda_banco_proposta e', 'p.id_programa_banco_proposta = e.id_programa_banco_proposta');
        //$this->db->join('banco_proposta b', '');
        //$this->db->join('siconv_programa', 'siconv_programa.codigo = siconv_beneficiario.codigo_programa');
        $this->db->where('b.situacao !=', 'Cancelado');
        $this->db->where('b.situacao !=', 'Proposta/Plano de Trabalho Cancelados');
        $this->db->where('b.situacao !=', 'Proposta/Plano de Trabalho em rascunho');
        $query = $this->db->get('banco_proposta b')->result();

        return $query;
    }

    public function get_all_emendas($filtro = null) {
        $estado_parlamentar = null;
        $lista_cnpjs = array();
        if ($this->session->userdata('nivel') == 4 || $this->session->userdata('nivel') == 15) {
            if ($this->session->userdata('sistema') == 'M') {
                $this->load->model('usuariomodel');
                $cnpjs = $this->usuariomodel->get_estados_by_usuario($this->session->userdata('id_usuario'));

                $estado_parlamentar = $cnpjs[0]->sigla;

                foreach ($cnpjs as $c)
                    $lista_cnpjs[] = $this->formatCPFCNPJ($c->cnpj);
            } else {
                $estado_parlamentar = $this->session->userdata('estado_parlamentar');
            }
        } else if ($this->session->userdata('nivel') == 2 || $this->session->userdata('nivel') == 3 || $this->session->userdata('nivel') == 5 || $this->session->userdata('nivel') == 6 || $this->session->userdata('nivel') == 7 || $this->session->userdata('nivel') == 8) {
            if ($this->session->userdata('sistema') == 'M') {
                $this->load->model('usuariomodel');
                $cnpjs = $this->usuariomodel->get_estados_by_usuario($this->session->userdata('id_usuario'));

                $estado_parlamentar = $cnpjs[0]->sigla;

                foreach ($cnpjs as $c)
                    $lista_cnpjs[] = $this->formatCPFCNPJ($c->cnpj);
            } else {
                $this->db->where('id_usuario', $this->session->userdata('id_usuario'));
                $query = $this->db->get('gestor')->row(0);

                $estado_parlamentar = $query->estado_parlamentar;
            }
        }

        $this->db->flush_cache();
        $this->db->_protect_identifiers = false;

        $this->db->select('b.situacao as situacao_proposta, b.*, p.*, e.*, ps.*');

        if (!is_null($filtro)) {
            if (isset($filtro['num_emenda']) && $filtro['num_emenda'] != "") {
                $this->db->where('codigo_emenda', $filtro['num_emenda']);
            }

            if (isset($filtro['proponente']))
                $this->db->where_in('codigo_municipio', $filtro['proponente']);
        }

        $this->db->join('programa_banco_proposta p', 'id_proposta = id_proposta_banco_proposta');
        $this->db->join('emenda_banco_proposta e', 'p.id_programa_banco_proposta = e.id_programa_banco_proposta');

        $this->db->join('proponente_siconv ps', "REPLACE(REPLACE(REPLACE(ps.cnpj, '/', ''), '-', ''), '.', '') = b.proponente", FALSE);

        $this->db->where('b.situacao !=', 'Cancelado');
        $this->db->where('b.situacao !=', 'Proposta/Plano de Trabalho Cancelados');
        $this->db->where('b.situacao !=', 'Proposta/Plano de Trabalho em rascunho');

        if ($this->session->userdata('nivel') == 1 || $this->session->userdata('sistema') == "M")
            $this->db->where('ano', date('Y'));

        if (!is_null($estado_parlamentar))
            $this->db->where('municipio_uf_sigla', $estado_parlamentar);

        if (!empty($lista_cnpjs))
            $this->db->where_in('cnpj', $lista_cnpjs);

        $query = $this->db->get('banco_proposta b')->result();

        return $query;
    }

    public function get_all_years_for_emendas() {
        $numParlamentar = null;

        if ($this->session->userdata('nivel') == 2) {
            $this->db->where('id_usuario', $this->session->userdata('id_usuario'));
            $gestor = $this->db->get('gestor')->row(0);

            $numParlamentar = $gestor->numero_parlamentar;
        } else if ($this->session->userdata('nivel') == 4 || $this->session->userdata('nivel') == 15) {
            $this->load->model('usuariomodel');
            $usuario = $this->usuariomodel->get_by_id($this->session->userdata('id_usuario'));

            $numParlamentar = $usuario->vendedor_codigo_parlamentar;
        }

        $this->db->flush_cache();

        $filtroParlamentar = "";
        $codigos = explode(",", $numParlamentar);
        foreach ($codigos as $c)
            $filtroParlamentar .= "sb.emenda LIKE '" . trim($c) . "%' OR ";

        $filtroParlamentar = "(" . trim($filtroParlamentar, " OR ") . ")";

        $this->db->distinct();
        $this->db->select('EXTRACT(year from sb.data_inicio_parlam) AS data_inicio_parlam');
        $this->db->where("(sb.data_inicio_parlam IS NOT NULL AND sb.data_inicio_parlam <> '0000-00-00')");
        $this->db->where($filtroParlamentar);
        //$this->db->order_by('sb.data_inicio_parlam', 'desc');
        $query = $this->db->get('siconv_beneficiario sb');
        $result = $query->result();

        $array_anos = array();

        foreach ($result as $data) {
            array_push($array_anos, $data->data_inicio_parlam);
        }

        arsort($array_anos);
        return $array_anos;
    }

    public function get_all_years_for_emendas_geral() {
        $this->db->flush_cache();

        $this->db->distinct();
        $this->db->select('EXTRACT(year from sb.data_inicio_parlam) AS data_inicio_parlam');
        $this->db->where("(sb.data_inicio_parlam IS NOT NULL AND sb.data_inicio_parlam <> '0000-00-00')");
        //$this->db->order_by('sb.data_inicio_parlam', 'desc');
        $query = $this->db->get('siconv_beneficiario sb');
        $result = $query->result();

        $array_anos = array();

        foreach ($result as $data) {
            array_push($array_anos, $data->data_inicio_parlam);
        }

        arsort($array_anos);
        return $array_anos;
    }

    public function get_dados_programa($codigo) {
        $this->db->where('codigo', $codigo);
        return $this->db->get('siconv_programa')->row(0);
    }

    public function get_valor_restante_emenda($emenda, $proponente) {
//     	$this->load->model('banco_proposta_model');
//     	$this->db->where('cnpj', $this->formatCPFCNPJ($proponente));
//     	$this->db->where('emenda', $emenda);
//     	$dadosEmenda = $this->db->get('siconv_beneficiario')->row(0);
//     	$valor_emenda = str_replace(",", ".", str_replace(".", "", str_replace("R$ ", "", $dadosEmenda->valor)));
//     	$this->db->flush_cache();
//     	$propostas = $this->banco_proposta_model->get_propostas_by_emenda($emenda, $dadosEmenda->codigo_programa);
//     	$total_proposta = 0;
//     	foreach ($propostas as $prop)
//     		$total_proposta += str_replace(",", ".", str_replace(".", "", str_replace("R$ ", "", $prop->valor_emenda)));
        //return "R$ ".number_format(($valor_emenda-$total_proposta), 2, ",", ".");
        return "";
    }

    public function get_valor_emenda($emenda, $proponente) {
        $this->db->where('cnpj', $this->formatCPFCNPJ($proponente));
        $this->db->where('emenda', $emenda);
        $dadosEmenda = $this->db->get('siconv_beneficiario')->row(0);

        if (isset($dadosEmenda->valor))
            return $dadosEmenda->valor;
        else
            return "";
    }

    public function busca_anexos() {
        $anexos = $this->db->get("anexos")->result();
        foreach ($anexos as $value) {
            $result[$value->id_programa][] = array("nome_arquivo" => $value->nome_arquivo, "data_anexo" => $value->data_anexo, "descricao" => $value->descricao, "link" => $value->link);
        }
        return $result;
    }

    public function busca_editais() {
        $anexos = $this->db->get("editais")->result();
        foreach ($anexos as $value) {
            $result[$value->id_programa][] = array("nome_arquivo" => $value->nome_arquivo, "link" => $value->link);
        }
        return $result;
    }

    public function get_ids_cadastrados($ano) {
        $this->db->select('link, codigo');
        $this->db->where('ano', $ano);
        $query = $this->db->get('siconv_programa')->result();
        $result = array();
        foreach ($query as $id) {
            $result[$id->codigo] = substr($id->link, 108);
        }

        return $result;
    }

    public function get_ids_cadastrados_aptos($ano) {
        $this->db->select('link, codigo');
        $this->db->where('ano', $ano);
        $this->db->where('excluido', 0);
        $query = $this->db->get('programas_vigencia')->result();
        $result = array();
        foreach ($query as $id) {
            $result[$id->codigo] = substr($id->link, 108);
        }

        return $result;
    }

    public function get_by_siconv_id($siconv_id) {
        $this->db->where("link like '%={$siconv_id}'");
        return $this->db->get('siconv_programa')->row(0);
    }

    public function get_ids_aptos($ano) {
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

}

?>
