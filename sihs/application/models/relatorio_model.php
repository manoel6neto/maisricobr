<?php

class relatorio_model extends CI_Model {

    function get_anos_propostas_array() {
        $this->db->distinct();
        $this->db->select('EXTRACT(year from data) AS ANO', FALSE);
        $this->db->order_by('ANO', 'DESC');
        $anos = $this->db->get('proposta')->result();

        $lista_anos = array();
        foreach ($anos as $ano)
            $lista_anos[$ano->ANO] = $ano->ANO;

        return $lista_anos;
    }

    public function get_meses_array() {
        return array(
            '01' => 'Janeiro',
            '02' => 'Fevereiro',
            '03' => 'Março',
            '04' => 'Abril',
            '05' => 'Maio',
            '06' => 'Junho',
            '07' => 'Julho',
            '08' => 'Agosto',
            '09' => 'Setembro',
            '10' => 'Outubro',
            '11' => 'Novembro',
            '12' => 'Dezembro',
        );
    }

    public function get_usuario_representantes() {
        $this->db->where('id_nivel', 4);
        $this->db->where('status', 'A');
        $this->db->order_by('nome', 'ASC');
        return $this->db->get('usuario')->result();
    }

    public function get_dados_rel_proj($filtro) {
        if ($this->session->userdata('nivel') == 5) {
            $this->load->model('usuario_gestor');
            $gestor = $this->usuario_gestor->get_by_usuario($this->session->userdata('id_usuario'));
            $this->db->flush_cache();

            $this->load->model('gestor');
            $user_gestor = $this->gestor->get_by_id($gestor->id_gestor);
            $this->db->flush_cache();
        }

        $this->db->select('CASE WHEN enviado = 0 AND era_padrao = 0 THEN "Propostas Elaboradas do Zero" WHEN enviado = 0 AND era_padrao = 1 THEN "Propostas Utilizadas do Banco" WHEN enviado = 1 THEN "Propostas Cadastradas Pelo Sistema" END AS TITULO, proposta.*, area.nome areanome', FALSE);

        if ($filtro['ano_inicio'] != "")
            $this->db->where('extract(year from data) >= ', $filtro['ano_inicio'], FALSE);

        if ($filtro['ano_fim'] != "")
            $this->db->where('extract(year from data) <= ', $filtro['ano_fim'], FALSE);

        $this->db->where('padrao', 0);
        $this->db->where('ativo', 1);
        switch ($filtro['tipo_projeto']) {
            case 1:
                $this->db->where('era_padrao', 0);
                $this->db->where('enviado', 0);
                break;
            case 2:
                $this->db->where('era_padrao', 1);
                break;
            case 3:
                $this->db->where('enviado', 1);
                break;
        }

        switch ($this->session->userdata('nivel')) {
            case 2:
                $this->db->distinct();
                $this->db->join('usuario_gestor', 'usuario_gestor.id_usuario = proposta.idGestor OR proposta.idGestor = ' . $this->session->userdata('id_usuario'), 'LEFT');
                $this->db->join('gestor', 'gestor.id_gestor = usuario_gestor.id_gestor', 'LEFT');
                $this->db->where('(gestor.id_usuario', $this->session->userdata('id_usuario'), FALSE);
                $this->db->or_where('proposta.idGestor', $this->session->userdata('id_usuario') . ")", FALSE);
                break;
            case 3:
                $this->db->where('idGestor', $this->session->userdata('id_usuario'));
                break;
            case 4:
                $this->db->where('idGestor', $this->session->userdata('id_usuario'));
                break;
            case 5:
                $this->db->distinct();
                $this->db->join('usuario_gestor', 'usuario_gestor.id_usuario = proposta.idGestor OR proposta.idGestor = ' . $user_gestor->id_usuario, 'LEFT');
                $this->db->join('gestor', 'gestor.id_gestor = usuario_gestor.id_gestor', 'LEFT');
                $this->db->where('(gestor.id_usuario', $user_gestor->id_usuario, FALSE);
                $this->db->or_where('proposta.idGestor', $user_gestor->id_usuario . ")", FALSE);
                break;
        }

        $this->db->join('area', 'area.id = proposta.area', 'LEFT');

        $this->db->order_by('TITULO', 'ASC');
        $this->db->order_by('extract(month from data)', 'ASC');

        $query = $this->db->get('proposta')->result();

        return $query;
    }

    public function get_usuarios_rel($mostraPrefeito = true) {
        if ($this->session->userdata('nivel') == 5) {
            $this->load->model('usuario_gestor');
            $gestor = $this->usuario_gestor->get_by_usuario($this->session->userdata('id_usuario'));
            $this->db->flush_cache();

            $this->load->model('gestor');
            $user_gestor = $this->gestor->get_by_id($gestor->id_gestor);
            $this->db->flush_cache();
        }

        switch ($this->session->userdata('nivel')) {
            case 1:
                $this->db->distinct();
                $this->db->select("usuario.*");

                if (!$mostraPrefeito)
                    $this->db->where('id_nivel <> ', 5);

                $this->db->order_by('id_nivel, nome');
                break;
            case 2:
//				$this->db->distinct();
//				$this->db->select("usuario.*");
//				$this->db->join('usuario_gestor', 'usuario_gestor.id_usuario = usuario.id_usuario OR usuario.id_usuario = '.$this->session->userdata('id_usuario'), 'LEFT');
//				$this->db->join('gestor', 'gestor.id_gestor = usuario_gestor.id_gestor', 'LEFT');
//				$this->db->where('gestor.id_usuario', $this->session->userdata('id_usuario'));
//				$this->db->or_where('usuario.id_usuario', $this->session->userdata('id_usuario'));
//				break;
                $this->db->distinct();
                $this->db->select("usuario.*");
                $this->db->where('id_usuario <> ', 1);

                if (!$mostraPrefeito)
                    $this->db->where('id_nivel <> ', 5);

                $this->db->order_by('id_nivel, nome');
                break;
            case 3:
                $this->db->where('usuario.id_usuario', $this->session->userdata('id_usuario'));
                break;
            case 4:
                $this->db->distinct();
                $this->db->select("usuario.*");
                $this->db->join("usuario_realizou_cadastro", "id_usuario = id_usuario_cadastrado");
                $this->db->where('usuario_realizou_cadastro.id_usuario_cadastrou', $this->session->userdata('id_usuario'));
                break;
            case 5:
                $this->db->distinct();
                $this->db->select("usuario.*");
                $this->db->join('usuario_gestor', 'usuario_gestor.id_usuario = usuario.id_usuario OR usuario.id_usuario = ' . $user_gestor->id_usuario, 'LEFT');
                $this->db->join('gestor', 'gestor.id_gestor = usuario_gestor.id_gestor', 'LEFT');
                $this->db->where('gestor.id_usuario', $user_gestor->id_usuario);
                $this->db->or_where('usuario.id_usuario', $user_gestor->id_usuario);
                break;
        }

        $query = $this->db->get('usuario')->result();

        $lista = array("" => "Todos");
        if ($this->session->userdata('nivel') == 1) {
            foreach ($query as $q)
//                $lista[$this->get_nome_nivel($q->id_nivel)][$q->id_usuario] = $q->nome;
                $lista[$q->id_usuario] = $q->nome;
        } else {
            foreach ($query as $q)
                $lista[$q->id_usuario] = $q->nome;
        }

        return $lista;
    }

    public function get_nome_nivel($id) {
        $this->db->where('id_nivel_usuario', $id);
        return $this->db->get('nivel_usuario')->row(0)->nome;
    }

    public function get_dados_rel_usuario($filtro) {
        if ($this->session->userdata('nivel') == 5) {
            $this->load->model('usuario_gestor');
            $gestor = $this->usuario_gestor->get_by_usuario($this->session->userdata('id_usuario'));
            $this->db->flush_cache();

            $this->load->model('gestor');
            $user_gestor = $this->gestor->get_by_id($gestor->id_gestor);
            $this->db->flush_cache();
        }

        $this->db->select('u.nome AS TITULO, proposta.*, area.nome areanome', FALSE);

        if ($filtro['usuario'] != "")
            $this->db->where('idGestor', $filtro['usuario']);
        else {
            $this->db->distinct();
            switch ($this->session->userdata('nivel')) {
                case 1:
                    $this->db->join('usuario_gestor', 'usuario_gestor.id_usuario = proposta.idGestor OR proposta.idGestor = ' . $this->session->userdata('id_usuario'), 'LEFT');
                    break;
                case 2:
                    $this->db->join('usuario_gestor', 'usuario_gestor.id_usuario = proposta.idGestor OR proposta.idGestor = ' . $this->session->userdata('id_usuario'), 'LEFT');
                    $this->db->join('gestor', 'gestor.id_gestor = usuario_gestor.id_gestor', 'LEFT');
                    $this->db->where('(gestor.id_usuario', $this->session->userdata('id_usuario'), FALSE);
                    $this->db->or_where('proposta.idGestor', $this->session->userdata('id_usuario') . ")", FALSE);
                    break;
                case 3:
                    $this->db->where('idGestor', $this->session->userdata('id_usuario'));
                    break;
                case 4:
                    $this->db->where('idGestor', $this->session->userdata('id_usuario'));
                    break;
                case 5:
                    $this->db->join('usuario_gestor', 'usuario_gestor.id_usuario = proposta.idGestor OR proposta.idGestor = ' . $user_gestor->id_usuario, 'LEFT');
                    $this->db->join('gestor', 'gestor.id_gestor = usuario_gestor.id_gestor', 'LEFT');
                    $this->db->where('(gestor.id_usuario', $user_gestor->id_usuario, FALSE);
                    $this->db->or_where('proposta.idGestor', $user_gestor->id_usuario . ")", FALSE);
                    break;
            }
        }

        $this->db->where('padrao', 0);
        $this->db->where('ativo', 1);

        $this->db->join('area', 'area.id = proposta.area', 'LEFT');
        $this->db->join('usuario u', 'u.id_usuario = proposta.idGestor');

        $this->db->order_by('TITULO', 'ASC');
        $this->db->order_by('extract(month from data)', 'ASC');
        $query = $this->db->get('proposta')->result();

        return $query;
    }

    public function get_dados_rel_sistema() {
        if ($this->session->userdata('nivel') == 5) {
            $this->load->model('usuario_gestor');
            $gestor = $this->usuario_gestor->get_by_usuario($this->session->userdata('id_usuario'));
            $this->db->flush_cache();

            $this->load->model('gestor');
            $user_gestor = $this->gestor->get_by_id($gestor->id_gestor);
            $this->db->flush_cache();
        }

        $this->load->model('usuariomodel');

        if ($this->session->userdata('nivel') == 1 || $this->session->userdata('nivel') == 4)
            $retorno['num_usuarios'] = $this->db->select('COUNT(*) AS NUM')->get('usuario')->row(0)->NUM;
        else {
            $this->db->distinct();
            $this->db->select('nome');
            $this->db->join('usuario_gestor', 'usuario_gestor.id_usuario = usuario.id_usuario OR usuario.id_usuario = ' . $this->session->userdata('id_usuario'), 'LEFT');
            $this->db->join('gestor', 'gestor.id_gestor = usuario_gestor.id_gestor', 'LEFT');
            $this->db->where('gestor.id_usuario', $this->session->userdata('id_usuario'));
            $this->db->or_where('usuario.id_usuario', $this->session->userdata('id_usuario'));
            $retorno['num_usuarios'] = $this->db->get('usuario')->num_rows();
        }

        if ($this->session->userdata('nivel') == 1 || $this->session->userdata('nivel') == 4)
            $retorno['cnpjs_vinculados'] = $this->db->select('COUNT(*) AS NUM')->get('cnpj_siconv')->row(0)->NUM;
        else
            $retorno['cnpjs_vinculados'] = count($this->usuariomodel->get_cnpjs_by_usuario($this->session->userdata('id_usuario')));

        if ($this->session->userdata('nivel') == 1 || $this->session->userdata('nivel') == 4)
            $retorno['ministerios_utilizados'] = $this->db->select('orgao')->group_by('orgao')->get('proposta')->num_rows();
        else {
            $id_gestor = $this->usuariomodel->get_id_gestor($this->session->userdata('id_usuario'));
            if ($id_gestor != null) {
                $usuarios_gestor = $this->usuariomodel->get_ids_grupo($id_gestor);
                $ids = array();
                foreach ($usuarios_gestor as $usuario_gestor) {
                    array_push($ids, $usuario_gestor->id_usuario);
                }
                array_push($ids, $this->usuariomodel->get_gestor_user_id_by_gestor_id($id_gestor));
                $this->db->or_where_in('idGestor', $ids);
            }
            $this->db->distinct();
            $this->db->select('orgao');
            $this->db->where('ativo', 1);
            $this->db->where('padrao', 0);
            $retorno['ministerios_utilizados'] = $this->db->get('proposta')->num_rows();
        }

        $retorno['bancos_utilizados'] = $this->db->select('banco')->group_by('banco')->get('proposta')->num_rows();
        $retorno['usuario_bloqueado'] = $this->db->select('nome')->where('status', 'I')->where('id_nivel', 2)->get('usuario')->result();
        $retorno['logaram_sistema'] = $this->db->like('user_data', '"nivel";s:1:"2"')->or_like('user_data', '"nivel";s:1:"3"')->get('ci_sessions')->num_rows();

        $this->db->flush_cache();

        $this->db->select('CASE WHEN enviado = 0 AND era_padrao = 0 THEN "ZERO" WHEN enviado = 0 AND era_padrao = 1 THEN "BANCO" WHEN enviado = 1 THEN "ENVIADA" END AS TITULO, proposta.*, area.nome areanome  ', FALSE);

        $this->db->where('padrao', 0);
        $this->db->where('ativo', 1);

        switch ($this->session->userdata('nivel')) {
            case 2:
                $this->db->distinct();
                $this->db->join('usuario_gestor', 'usuario_gestor.id_usuario = proposta.idGestor OR proposta.idGestor = ' . $this->session->userdata('id_usuario'), 'LEFT');
                $this->db->join('gestor', 'gestor.id_gestor = usuario_gestor.id_gestor', 'LEFT');
                $this->db->where('(gestor.id_usuario', $this->session->userdata('id_usuario'), FALSE);
                $this->db->or_where('proposta.idGestor', $this->session->userdata('id_usuario') . ")", FALSE);
                break;
            case 3:
                $this->db->where('idGestor', $this->session->userdata('id_usuario'));
                break;
            case 4:
                $this->db->where('idGestor', $this->session->userdata('id_usuario'));
                break;
            case 5:
                $this->db->distinct();
                $this->db->join('usuario_gestor', 'usuario_gestor.id_usuario = proposta.idGestor OR proposta.idGestor = ' . $user_gestor->id_usuario, 'LEFT');
                $this->db->join('gestor', 'gestor.id_gestor = usuario_gestor.id_gestor', 'LEFT');
                $this->db->where('(gestor.id_usuario', $user_gestor->id_usuario, FALSE);
                $this->db->or_where('proposta.idGestor', $user_gestor->id_usuario . ")", FALSE);
                break;
        }

        $this->db->join('area', 'area.id = proposta.area', 'LEFT');

        $sql = $this->db->get('proposta')->result();

        $query = $this->db->query("SELECT TITULO, COUNT(TITULO) AS num FROM (" . $this->db->last_query() . ") a GROUP BY TITULO")->result();

        $retorno['elab_zero'] = 0;
        $retorno['util_banco'] = 0;
        $retorno['envi_sistema'] = 0;

        foreach ($query as $q) {
            switch ($q->TITULO) {
                case "BANCO":
                    $retorno['util_banco'] = $q->num;
                    break;
                case "ENVIADA":
                    $retorno['envi_sistema'] = $q->num;
                    break;
                case "ZERO":
                    $retorno['elab_zero'] = $q->num;
                    break;
            }
        }

        return $retorno;
    }

    public function get_dados_resl_quantitativos($nome_governo = "") {
        $this->load->model('usuariomodel');
        $this->load->model('banco_proposta_model');
        $this->load->model('programa_model');
        $this->load->model('proponente_siconv_model');

        $retorno = array();

        $ano_busca = date('Y') - 1;
        $dados_cnpj = $this->usuariomodel->get_cnpjs_by_usuario($this->session->userdata('id_usuario'));

        $ehEstadual = false;
        $lista_cnpj = array();
        foreach ($dados_cnpj as $cnpj) {
            $lista_cnpj[] = $cnpj->cnpj;
            $esferaADM = $this->proponente_siconv_model->get_instituicao_esfera($this->programa_model->formatCPFCNPJ($cnpj->cnpj), true);
            if (!empty($esferaADM)) {
                if ($esferaADM->esfera_administrativa == "ESTADUAL")
                    $ehEstadual = true;
            }
        }

        $retorno['ehEstadual'] = $ehEstadual;

        $retorno['estado'] = $dados_cnpj[0]->sigla;
        $retorno['cidade'] = $this->proponente_siconv_model->get_municipio_nome($dados_cnpj[0]->id_cidade)->municipio;

        $retorno['nome_governo'] = $nome_governo;

        $anos_filtro = $this->banco_proposta_model->get_anos_by_usuario();
        $lista_anos = array();

        //Busca das propostas encaminhadas
        foreach ($anos_filtro as $ano)
            $lista_anos[] = $ano->ano;

        $retorno['ano_busca'] = $ano_busca;
        $retorno['lista_anos'] = $lista_anos;

        $propostas = $this->banco_proposta_model->busca_programas_pareceres(array('anos' => $lista_anos, 'pesquisa' => ''));
        $this->db->flush_cache();

        $num_cadastradas = array();
        $num_aprovadas = array();

        $num_propostas = count($propostas);
        $retorno['num_propostas_cadastradas'] = $num_propostas;

        foreach ($propostas as $proposta) {
            if (!$this->banco_proposta_model->verifica_proposta_cadastrado($proposta->situacao)) {
                if (isset($num_cadastradas[$proposta->ano]))
                    $num_cadastradas[$proposta->ano] = $num_cadastradas[$proposta->ano] + 1;
                else
                    $num_cadastradas[$proposta->ano] = 1;
            }

            if ($this->banco_proposta_model->verifica_proposta_aprovada($proposta->situacao)) {
                if (isset($num_aprovadas[$proposta->ano]))
                    $num_aprovadas[$proposta->ano] = $num_aprovadas[$proposta->ano] + 1;
                else
                    $num_aprovadas[$proposta->ano] = 1;
            }
        }

        $retorno['num_aprovadas'] = $num_aprovadas;
        $retorno['num_cadastradas'] = $num_cadastradas;
        $retorno['num_total_aprovadas'] = array_sum($num_aprovadas);
        $retorno['num_propostas'] = array_sum($num_cadastradas);

        //Dados relacionados ao ano anterior
        $filtro_programas = array(
            'data_inicio' => $ano_busca . '-01-01',
            'data_fim' => $ano_busca . '-12-31',
            'qualificacao' => array(
                'Proposta Voluntária',
                'Proposta de Proponente Específico do Concedente',
                'Proposta de Proponente de Emenda Parlamentar'
            ),
            'atende' => array(
                'Administração Pública Municipal'
            ),
            'orgao' => '',
            'pesquisa' => ''
        );

        if ($ehEstadual)
            $filtro_programas['atende'][] = 'Administração Pública Estadual ou do Distrito Federal';

        $programas_municipio = $this->programa_model->busca_programa($filtro_programas);

        $filtro_programas = array(
            'data_inicio' => $ano_busca . '-01-01',
            'data_fim' => $ano_busca . '-12-31',
            'qualificacao' => array(
                'Proposta Voluntária'
            ),
            'atende' => array(
                'Administração Pública Municipal'
            ),
            'orgao' => '',
            'pesquisa' => ''
        );

        if ($ehEstadual)
            $filtro_programas['atende'][] = 'Administração Pública Estadual ou do Distrito Federal';

        $programas_municipio_voluntario = $this->programa_model->busca_programa($filtro_programas);

        $filtro_programas = array(
            'data_inicio' => $ano_busca . '-01-01',
            'data_fim' => $ano_busca . '-12-31',
            'qualificacao' => array(
                'Proposta de Proponente de Emenda Parlamentar'
            ),
            'atende' => array(
                'Administração Pública Municipal'
            ),
            'orgao' => '',
            'pesquisa' => ''
        );

        if ($ehEstadual)
            $filtro_programas['atende'][] = 'Administração Pública Estadual ou do Distrito Federal';

        $programas_municipio_emenda = $this->programa_model->busca_programa($filtro_programas);

        $filtro_programas = array(
            'data_inicio' => $ano_busca . '-01-01',
            'data_fim' => $ano_busca . '-12-31',
            'qualificacao' => array(
                'Proposta de Proponente Específico do Concedente'
            ),
            'atende' => array(
                'Administração Pública Municipal'
            ),
            'orgao' => '',
            'pesquisa' => ''
        );

        if ($ehEstadual)
            $filtro_programas['atende'][] = 'Administração Pública Estadual ou do Distrito Federal';

        $programas_municipio_especifico = $this->programa_model->busca_programa($filtro_programas);

        $retorno['programas_municipio'] = $programas_municipio['total_rows'];
        $retorno['programas_municipio_voluntario'] = $programas_municipio_voluntario['total_rows'];
        $retorno['programas_municipio_emenda'] = $programas_municipio_emenda['total_rows'];
        $retorno['programas_municipio_especifico'] = $programas_municipio_especifico['total_rows'];

        $filtro_programas = array(
            'data_inicio' => $ano_busca . '-01-01',
            'data_fim' => $ano_busca . '-12-31',
            'qualificacao' => array(
                'Proposta Voluntária',
                'Proposta de Proponente Específico do Concedente',
                'Proposta de Proponente de Emenda Parlamentar'
            ),
            'atende' => array(
                'Administração Pública Estadual ou do Distrito Federal'
            ),
            'orgao' => '',
            'pesquisa' => ''
        );
        $programas_estado = $this->programa_model->busca_programa($filtro_programas);

        $filtro_programas = array(
            'data_inicio' => $ano_busca . '-01-01',
            'data_fim' => $ano_busca . '-12-31',
            'qualificacao' => array(
                'Proposta Voluntária'
            ),
            'atende' => array(
                'Administração Pública Estadual ou do Distrito Federal'
            ),
            'orgao' => '',
            'pesquisa' => ''
        );
        $programas_estado_voluntario = $this->programa_model->busca_programa($filtro_programas);

        $filtro_programas = array(
            'data_inicio' => $ano_busca . '-01-01',
            'data_fim' => $ano_busca . '-12-31',
            'qualificacao' => array(
                'Proposta de Proponente de Emenda Parlamentar'
            ),
            'atende' => array(
                'Administração Pública Estadual ou do Distrito Federal'
            ),
            'orgao' => '',
            'pesquisa' => ''
        );
        $programas_estado_emenda = $this->programa_model->busca_programa($filtro_programas);

        $filtro_programas = array(
            'data_inicio' => $ano_busca . '-01-01',
            'data_fim' => $ano_busca . '-12-31',
            'qualificacao' => array(
                'Proposta de Proponente Específico do Concedente'
            ),
            'atende' => array(
                'Administração Pública Estadual ou do Distrito Federal'
            ),
            'orgao' => '',
            'pesquisa' => ''
        );
        $programas_estado_especifico = $this->programa_model->busca_programa($filtro_programas);

        $retorno['programas_estado'] = $programas_estado['total_rows'];
        $retorno['programas_estado_voluntario'] = $programas_estado_voluntario['total_rows'];
        $retorno['programas_estado_emenda'] = $programas_estado_emenda['total_rows'];
        $retorno['programas_estado_especifico'] = $programas_estado_especifico['total_rows'];

        $propostas_estado = $this->banco_proposta_model->busca_programas_pareceres(array('anos' => array($ano_busca), 'pesquisa' => ''), true, array('estado' => $retorno['estado'], 'esfera' => 'MUNICIPAL'));

        $num_cadastradas_estado = 0;
        $num_aprovadas_estado = 0;
        foreach ($propostas_estado as $p) {
            if (!$this->banco_proposta_model->verifica_proposta_cadastrado($p->situacao))
                $num_cadastradas_estado++;
            if ($this->banco_proposta_model->verifica_proposta_aprovada($p->situacao))
                $num_aprovadas_estado++;
        }

        $retorno['num_cadastradas_estado'] = $num_cadastradas_estado;
        $retorno['num_aprovadas_estado'] = $num_aprovadas_estado;

        $programas_vigencia = $this->programa_model->busca_programa();

        $retorno['programas_vigencia'] = $programas_vigencia['total_rows'];

        $filtro_programas = array(
            'vigencia' => 'vigencia',
            'qualificacao' => array(
                'Proposta de Proponente de Emenda Parlamentar'
            ),
            'atende' => array(
                'Administração Pública Municipal'
            ),
            'orgao' => '',
            'pesquisa' => '',
            'cnpj' => $lista_cnpj
        );
        $programas_vigencia_emenda = $this->programa_model->busca_programa($filtro_programas);

        $retorno['programas_vigencia_emenda'] = $programas_vigencia_emenda['total_rows'];

        $filtro_programas = array(
            'vigencia' => 'vigencia',
            'qualificacao' => array(
                'Proposta de Proponente Específico do Concedente'
            ),
            'atende' => array(
                'Administração Pública Municipal'
            ),
            'orgao' => '',
            'pesquisa' => '',
            'cnpj' => $lista_cnpj
        );
        $programas_vigencia_especifico = $this->programa_model->busca_programa($filtro_programas);

        $retorno['programas_vigencia_especifico'] = $programas_vigencia_especifico['total_rows'];

        return $retorno;
    }

    public function get_dados_visita_representante($usuario = "") {
        if (!empty($usuario))
            $this->db->where('id_usuario_cadastrou', $usuario);

        $this->db->distinct();
        $this->db->select("contato_municipio.*, usuario.nome, proponente_siconv.municipio, municipio_uf_sigla");

        $this->db->join('usuario', 'id_usuario = id_usuario_cadastrou');
        $this->db->join('proponente_siconv', 'codigo_municipio = id_municipio');

        $this->db->order_by('usuario.nome', 'ASC');
        $this->db->order_by('municipio', 'ASC');

        $query = $this->db->get('contato_municipio')->result();

        return $query;
    }

}
