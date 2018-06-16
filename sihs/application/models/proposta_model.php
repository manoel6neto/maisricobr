<?php

class proposta_model extends CI_Model {

    public function delete_proposta($idProposta) {
        $this->db->where('idProposta', $idProposta);
        $this->db->delete('proposta');
    }

    function get_all($usuario) {
        //checar no modulo de usuario se esta ativo depois
        //$this->db->where('ativo', 1);
        //$this->db->where('idGestor', $usuario);
        $this->db->order_by('idProposta', 'desc');
        $query = $this->db->get('proposta');
        $resultado = $query->result();
        return $resultado;
    }

    function get_all_ativo() {
        $this->db->where('ativo', 1);
        $this->db->where('padrao', 0);
        $this->db->order_by('idProposta', 'desc');
        $query = $this->db->get('proposta');
        $resultado = $query->result();
        return $resultado;
    }

    //Thomas: Retorna as propostas ativas, que não são padrão e já foram enviadas
    function get_all_ativo_enviadas_all_users() {
        $this->db->where('ativo', 1);
        $this->db->where('padrao', 0);
        $this->db->where('enviado', 1);
        $query = $this->db->get('proposta');
        $resultado = $query->result();
        return $resultado;
    }

    //THOMAS: Retorna todos as propostas de um usuario pelo id. Utilizado para verifica se o vendedor ja cadastrou proposta com o cnpj indicado
    function get_all_by_usuario_cnpj($id_usuario, $cnpj) {
        $this->db->where('idGestor', $id_usuario);
        $this->db->where('proponente', $cnpj);
        $this->db->where('enviado', 1);
        $query = $this->db->get('proposta');

        return $query->result();
    }

    public function get_count_by_usuario($id_usuario) {
        $this->db->where('idGestor', $id_usuario);
        $query = $this->db->get('proposta');

        return $query->num_rows;
    }

    //INSERT META PARA PROPOSTA PADRAO IMPORT SICONV E-SICAR
    public function insert_meta_proposta_padrao($options) {
        $this->db->insert('meta', $options);
        return $this->db->insert_id();
    }

    //INSERT ETAPA PARA PROPOSTA PADRAO IMPORT SICONV E-SICAR
    public function insert_etapa_meta_proposta_padrao($options) {
        $this->db->insert('etapa', $options);
        return $this->db->insert_id();
    }

    //THOMAS: Carrega os projetos ativos não enviados para o siconv
    function get_all_ativo_cadastradas($id_usuario, $filtro = "") {
        $this->load->model('usuariomodel');
//        if ($this->usuariomodel->get_tipo_by_id($id_usuario) != "VENDEDOR") {
//            $id_gestor = $this->usuariomodel->get_id_gestor($id_usuario);
//            if ($id_gestor != null) {
//                if ($this->session->userdata('nivel') == 2 || $this->session->userdata('nivel') == 3 || $this->session->userdata('nivel') == 5)
//                    $usuarios_gestor = $this->usuariomodel->get_ids_grupo($id_gestor);
//                else if ($this->session->userdata('nivel') == 6 || $this->session->userdata('nivel') == 7 || $this->session->userdata('nivel') == 8)
//                    $usuarios_gestor = $this->usuariomodel->get_ids_grupo_subgestor($id_gestor);
//
//                $ids = array();
//                foreach ($usuarios_gestor as $usuario_gestor) {
//                    array_push($ids, $usuario_gestor->id_usuario);
//
//                    $users_subgestor = $this->usuariomodel->get_users_subgestor_by_gestor($usuario_gestor->id_usuario);
//                    if (!is_null($users_subgestor)) {
//                        foreach ($users_subgestor as $u)
//                            array_push($ids, $u->id_usuario);
//                    }
//                }
//                array_push($ids, $this->usuariomodel->get_gestor_user_id_by_gestor_id($id_gestor));
//                $this->db->or_where_in('idGestor', $ids);
//            }
//        } else {
        $cnpjs = $this->usuariomodel->get_cnpjs_by_usuario($id_usuario);
        $lista_cnpjs = array();
        foreach ($cnpjs as $cnpj)
            $lista_cnpjs[] = $cnpj->cnpj;

        //$this->db->where('idGestor', $id_usuario);
        if (count($lista_cnpjs) > 0)
            $this->db->where_in('proponente', $lista_cnpjs);
//        }

        if ($filtro != "")
            $this->db->where("(proposta.nome_programa like '%" . $filtro . "%' OR proposta.nome like '%" . $filtro . "%')");

        $this->db->select('proposta.*, area.nome areanome');
        $this->db->where('ativo', 1);
        $this->db->where('enviado', 0);
        $this->db->where('padrao', 0);
        $this->db->order_by('proponente', 'desc');
        $this->db->order_by('idProposta', 'desc');
        $this->db->join('area', 'area.id = proposta.area');
        $query = $this->db->get('proposta');

        $resultado = $query->result();
        return $resultado;
    }

    public function get_all_ativo_padrao_padrao_oculto() {
        $this->db->select('proposta.*, area.nome areanome');
        $this->db->where('ativo', 1);
        $this->db->where('padrao', 1);
        $this->db->where('padrao_oculto', 1);
        $this->db->order_by('idProposta', 'desc');
        $this->db->join('area', 'area.id = proposta.area');
        $query = $this->db->get('proposta');

        $resultado = $query->result();
        return $resultado;
    }

    public function ativa_padrao($idProposta) {

        $options = array(
            'padrao_oculto' => 0
        );

        $this->db->where('idProposta', $idProposta);
        $this->db->update('proposta', $options);
    }

    //THOMAS: Carrega os projetos ativos enviados para o siconv
    function get_all_ativo_enviadas($id_usuario, $filtro = "") {
        $this->load->model('usuariomodel');
//        if ($this->usuariomodel->get_tipo_by_id($id_usuario) != "VENDEDOR") {
//            $id_gestor = $this->usuariomodel->get_id_gestor($id_usuario);
//            if ($id_gestor != null) {
//                if ($this->session->userdata('nivel') == 2 || $this->session->userdata('nivel') == 3 || $this->session->userdata('nivel') == 5)
//                    $usuarios_gestor = $this->usuariomodel->get_ids_grupo($id_gestor);
//                else if ($this->session->userdata('nivel') == 6 || $this->session->userdata('nivel') == 7 || $this->session->userdata('nivel') == 8)
//                    $usuarios_gestor = $this->usuariomodel->get_ids_grupo_subgestor($id_gestor);
//                else
//                    $usuarios_gestor = $this->usuariomodel->get_ids_grupo($id_gestor);
//
//                $ids = array();
//                foreach ($usuarios_gestor as $usuario_gestor) {
//                    array_push($ids, $usuario_gestor->id_usuario);
//
//                    $users_subgestor = $this->usuariomodel->get_users_subgestor_by_gestor($usuario_gestor->id_usuario);
//                    if (!is_null($users_subgestor)) {
//                        foreach ($users_subgestor as $u)
//                            array_push($ids, $u->id_usuario);
//                    }
//                }
//                array_push($ids, $this->usuariomodel->get_gestor_user_id_by_gestor_id($id_gestor));
//                $this->db->or_where_in('idGestor', $ids);
//            }
//        } else {
        $cnpjs = array();
        $lista = $this->usuariomodel->get_cnpjs_by_usuario($id_usuario);
        foreach ($lista as $l)
            $cnpjs[] = $l->cnpj;
        if (count($cnpjs) > 0)
            $this->db->where_in('proponente', $cnpjs);
        //$this->db->where('idGestor', $id_usuario);
//        }

        if ($filtro != "")
            $this->db->where("(proposta.nome_programa like '%" . $filtro . "%' OR proposta.nome like '%" . $filtro . "%')");

        $this->db->select('proposta.*, area.nome areanome');
        $this->db->where('ativo', 1);
        $this->db->where('enviado', 1);
        $this->db->where('padrao', 0);
        $this->db->order_by('proponente', 'desc');
        $this->db->order_by('idProposta', 'desc');
        $this->db->join('area', 'area.id = proposta.area');
        $query = $this->db->get('proposta');
        $resultado = $query->result();

        return $resultado;
    }

    //THOMAS: Carrega todas os projetos padrões ativos.
    function get_all_ativo_padrao($filtro = "") {
        if ($filtro != "")
            $this->db->where("(proposta.nome like '%" . $filtro . "%' OR area.nome like '%" . $filtro . "%')");
        $this->db->select('proposta.*, area.nome areanome');
        $this->db->where('ativo', 1);
        $this->db->where('padrao', 1);
        $this->db->where('padrao_oculto', 0);
        $this->db->order_by('proposta.area', 'asc');
        $this->db->join('area', 'area.id = proposta.area');
        $query = $this->db->get('proposta');
        $resultado = $query->result();
        return $resultado;
    }

    function verifica_proponente($usuario) {
        $this->db->where('idPessoa', $usuario);
        $query = $this->db->get('cnpj_aberto');
        if (count($query->result_array()) > 0) {
            return true;
        } else {
            return false;
        }
    }

    function get_all_idsiconv($usuario) {
        $this->db->where('ativo', 1);
        $this->db->where('idGestor', $usuario);
        $this->db->distinct();
        $this->db->select('id_siconv');
        $this->db->order_by('idProposta', 'desc');
        $query = $this->db->get('proposta');
        $retorno = array();
        foreach ($query->result_array() as $sistema) {
            $pieces = explode("/", $sistema['id_siconv']);
            $retorno[] = intval($pieces[0]) . "/" . $pieces[1];
        }
        return $retorno;
    }

    function get_all_por_id($usuario) {
        $this->db->where('ativo', 1);
        $this->db->where('idGestor', $usuario);
        $this->db->order_by('idProposta', 'desc');
        $query = $this->db->get('proposta');
        return $query->result();
    }

    function get_all_abertas($usuario) {
        $this->db->select('proposta.*');
        $this->db->select('siconv_programa.data_fim as data_fim');
        $this->db->select('siconv_programa.data_inicio as data_inicio');
        $this->db->select('area.nome as area_nome');

        $this->db->where('Proposta.enviado', false);
        $this->db->where('Proposta.padrao', false);

        $this->db->from('proposta');
        $this->db->join('siconv_programa', 'proposta.codigo_programa = siconv_programa.codigo', 'left');
        $this->db->join('area', 'area.id = proposta.area', 'left');
        $this->db->where('proposta.ativo', 1);
        $this->db->order_by('siconv_programa.data_fim', 'asc');
        $this->db->order_by('proposta.idProposta', 'desc');

        $query = $this->db->get();

        return $query->result();
    }

    function get_all_abertas_user($usuario) {
        $this->db->select('proposta.*');
        $this->db->select('siconv_programa.data_fim as data_fim');
        $this->db->select('siconv_programa.data_inicio as data_inicio');
        $this->db->select('area.nome as area_nome');

        $this->db->where('proposta.enviado', false);
        $this->db->where('proposta.padrao', false);
        $this->db->where('proposta.idGestor', $usuario);
        $this->db->from('proposta');
        $this->db->join('siconv_programa', 'proposta.codigo_programa = siconv_programa.codigo', 'left');
        $this->db->join('Area', 'area.id = proposta.area', 'left');
        $this->db->where('proposta.ativo', 1);
        $this->db->order_by('siconv_programa.data_fim', 'asc');
        $this->db->order_by('proposta.idarea', 'desc');

        $query = $this->db->get();

        return $query->result();
    }

    function get_all_enviadas($usuario) {
        $this->db->select('proposta.*');
        $this->db->select('siconv_programa.data_fim as data_fim');
        $this->db->select('area.nome as area_nome');

        $this->db->where('proposta.enviado', true);
        $this->db->where('proposta.ativo', 1);

        $this->db->from('proposta');
        $this->db->join('siconv_programa', 'proposta.codigo_programa = siconv_programa.codigo', 'left');
        $this->db->join('Area', 'area.id = proposta.area', 'left');
        $this->db->where('proposta.ativo', 1);
        $this->db->order_by('siconv_programa.data_fim', 'asc');
        $this->db->order_by('proposta.idarea', 'desc');

        $query = $this->db->get();
        return $query->result();
    }

    function get_all_enviadas_user($usuario) {
        $this->db->select('proposta.*');
        $this->db->select('siconv_programa.data_fim as data_fim');
        $this->db->select('area.nome as area_nome');

        $this->db->where('proposta.enviado', true);
        $this->db->where('proposta.ativo', 1);
        $this->db->where('proposta.idGestor', $usuario);
        $this->db->from('proposta');
        $this->db->join('siconv_programa', 'proposta.codigo_programa = siconv_programa.codigo', 'left');
        $this->db->join('proposta', 'area.id = proposta.area', 'left');
        $this->db->where('proposta.ativo', 1);
        $this->db->order_by('siconv_programa.data_fim', 'asc');
        $this->db->order_by('proposta.idarea', 'desc');

        $query = $this->db->get();
        return $query->result();
    }

    function get_all_padrao($usuario = null) {

        $this->db->select('proposta.*');
        $this->db->select('siconv_programa.data_fim as data_fim');
        $this->db->select('area.nome as area_nome');

        if ($usuario != null)
            $this->db->where('(proposta.idGestor = ' . $usuario . ' OR proposta.padrao = true)', null);
        else
            $this->db->where('proposta.padrao', true);

        $this->db->where('proposta.ativo', 1);

        $this->db->from('proposta');
        $this->db->join('siconv_programa', 'proposta.codigo_programa = siconv_programa.codigo', 'left');
        $this->db->join('area', 'area.id = proposta.area', 'left');
        $this->db->where('proposta.ativo', 1);
        $this->db->order_by('siconv_programa.data_fim', 'asc');
        $this->db->order_by('proposta.idProposta', 'desc');

        $query = $this->db->get();

        return $query->result();
    }

    function torna_padrao($options, $id) {
        $options ['usuario_siconv'] = '';
        $options ['senha_siconv'] = '';
        $options ['proponente'] = '';
        $options ['cidade'] = '';

        $this->db->where('idProposta', $id);
        $this->db->update('proposta', $options);
        return $this->db->affected_rows();
    }

    function torna_projeto_padrao($id) {
        $options['usuario_siconv'] = '';
        $options['senha_siconv'] = '';
        #$options['proponente'] = '';
        $options['cidade'] = '';
        $options['id_siconv'] = '';
        $options['enviado'] = 0;
        $options['padrao'] = 1;
        $options['era_padrao'] = 0;
        $options['padrao_oculto'] = 0;

        $this->db->where('idProposta', $id);
        $this->db->update('proposta', $options);
        return $this->db->affected_rows();
    }

    function remove_padrao($id, $usuario_siconv, $senha_siconv, $proponente, $cidade) {
        $options['usuario_siconv'] = $usuario_siconv;
        $options['senha_siconv'] = base64_encode($senha_siconv);
        $options['proponente'] = $proponente;
        $options['cidade'] = $cidade;
        $options['id_siconv'] = '';
        $options['enviado'] = 0;
        $options['padrao'] = 0;

        $this->db->where('idProposta', $id);
        $this->db->update('proposta', $options);
        return $this->db->affected_rows();
    }

    function obter_programa_siconv($id) {
        $this->db->where('codigo', $id);
        $query = $this->db->get('siconv_programa');
        return $query->row(0);
    }

    function obter_programas($usuario) {
        // checar ativo no modulo de usuario
        //$this->db->where('ativo', 1);
        //$this->db->where('idGestor', $usuario);
        $this->db->group_by('codigo_programa');
        $query = $this->db->get('proposta');
        return $query->result();
    }

    function obter_cnpj_aberto($usuario) {

        $this->db->where('idPessoa', $usuario);
        $query = $this->db->get('cnpj_aberto');
        return $query->row(0);
    }

    function add_cnpj_aberto($options = array()) {
        $this->db->insert('cnpj_aberto', $options);
        return $this->db->affected_rows();
    }

    function altera_cnpj_aberto($options = array()) {
        $this->db->where('idPessoa', $options['idPessoa']);
        $this->db->update('cnpj_aberto', $options);
        return $this->db->affected_rows();
    }

    function verifica_cnpj_master($options = array()) {
        $this->db->distinct('cnpj');
        $this->db->group_by('cnpj');
        $this->db->where('idPessoa', $options['idPessoa']);
        $query = $this->db->get('cnpj_master');

        foreach ($query->result() as $cnpj) {//se encontrar o cnpj, nao adiciona e sai da função
            if ($cnpj->cnpj == $options['cnpj'])
                return true;
        }
        if ($query->num_rows() >= 30)
            return false; //ultrapassa o limite
        return true;
    }

    function add_cnpj_master($options = array()) {

        $insert_query = $this->db->insert_string('cnpj_master', $options);
        $insert_query = str_replace('INSERT INTO', 'INSERT IGNORE INTO', $insert_query);
        $this->db->query($insert_query);
    }

    function add_log_propostas($options = array()) {
        if ($options['ano'] == "0")
            return;
        $this->db->where('cnpj', $options['cnpj']);
        $this->db->where('ano', $options['ano']);
        $query = $this->db->get('log_propostas');
        $populacao = $query->result_array();

        if (count($populacao) > 0 && $options['ano'] == '2014') {
            $this->db->where('cnpj', $options['cnpj']);
            $this->db->where('ano', $options['ano']);
            $this->db->update('log_propostas', $options);
        }

        $insert_query = $this->db->insert_string('log_propostas', $options);
        $insert_query = str_replace('INSERT INTO', 'INSERT IGNORE INTO', $insert_query);
        $this->db->query($insert_query);
        //die ();
    }

    function add_records($options = array()) {
        $this->db->insert('proposta', $options);
        return $this->db->insert_id();
    }

    function inserir_proposta_siconv($options = array()) {
        $this->db->insert('siconv_proposta', $options);
        return $this->db->affected_rows();
    }

    function delete_record($id) {
        $this->db->where('idProposta', $id);
        $options['ativo'] = 0;
        $this->db->update('proposta', $options);
        return $this->db->affected_rows();
    }

    function atualiza_proposta($id, $options = array()) {

        $this->db->where('idProposta', $id);
        $this->db->update('proposta', $options);
    }

    function update_record_enviado($id, $options) {

        $this->db->where('idProposta', $id);
        $this->db->update('proposta', $options);
        return $this->db->affected_rows();
    }

    function update_proposta($id, $options) {
        $this->db->where('idProposta', $id);
        $this->db->update('proposta', $options);

        return $this->db->affected_rows();
    }

    function update_record($id, $options = array()) {
        $this->load->model('programa_proposta_model');
        //$data_projeto = $options['data'];
        $this->db->where('idProposta', $id);
        $query = $this->db->get('proposta');
        if ($options['valor_global'] != null) {

//         	$options['valor_global'] = str_replace(".", "", $options['valor_global']);
//         	$options['valor_global'] = str_replace(",", ".", $options['valor_global']);

            $proporcao = $options['valor_global'] / $query->row(0)->valor_global;

            $dataInicioProp_old = $query->row(0)->data_inicio;

            if (!isset($options['data_inicio'])) {
                $options['data_inicio'] = $query->row(0)->data_inicio;
                $options['data_termino'] = $query->row(0)->data_termino;
                $hoje = $options['data_inicio'];
                $final = $options['data_termino'];
            } else {
                $hoje = $options['data_inicio'];
                $final = $options['data_termino'];
            }

            if (!array_key_exists("total_contrapartida", $options))
                $options['total_contrapartida'] = $proporcao * $query->row(0)->total_contrapartida;
            if (!array_key_exists("contrapartida_financeira", $options))
                $options['contrapartida_financeira'] = $proporcao * $query->row(0)->contrapartida_financeira;
            if (!array_key_exists("contrapartida_bens", $options))
                $options['contrapartida_bens'] = $proporcao * $query->row(0)->contrapartida_bens;
            if (!array_key_exists("repasse", $options))
                $options['repasse'] = $proporcao * $query->row(0)->repasse;
            if (!array_key_exists("repasse_voluntario", $options))
                $options['repasse_voluntario'] = $proporcao * $query->row(0)->repasse_voluntario;

            if ($this->programa_proposta_model->get_num_programas_by_proposta($id) == 1) {
                $proporcao_despesa = $proporcao;
                $sohRecursoConvenio = false;
                if ($options['contrapartida_bens'] > 0 && $options['contrapartida_bens'] != $query->row(0)->contrapartida_bens) {
                    $valor = $options['contrapartida_bens'] - $query->row(0)->contrapartida_bens;
                    $proporcao_despesa = $proporcao - ($valor / ($options['valor_global'] - $query->row(0)->contrapartida_bens));
                    $proporcao_despesa_bens = $proporcao + ($valor / ($options['valor_global'] - $query->row(0)->contrapartida_bens));
                    $sohRecursoConvenio = true;
                } else if ($query->row(0)->contrapartida_bens > 0 && $options['contrapartida_bens'] == 0) {
                    $this->db->where('natureza_aquisicao', 2);
                    $this->db->where('Proposta_idProposta', $id);
                    $this->db->delete('despesa');
                }

                $proporcao_repasse = $options['repasse'] / $query->row(0)->repasse;
                $proporcao_contra = $options['contrapartida_financeira'] / $query->row(0)->contrapartida_financeira;

                //metas
                $this->db->where('Proposta_idProposta', $id);
                $query = $this->db->get('meta');
                foreach ($query->result() as $obj) {
                    $options_obj = array();
                    //$options_obj['data'] = $data_projeto;
                    $options_obj['data_inicio'] = $hoje;
                    $options_obj['data_termino'] = $final;
                    $options_obj['valorUnitario'] = $proporcao * $obj->valorUnitario;
                    $options_obj['total'] = $proporcao * $obj->total;
                    $this->db->where('idMeta', $obj->idMeta);
                    $this->db->update('meta', $options_obj);

                    $this->db->where('Meta_idMeta', $obj->idMeta);
                    $query1 = $this->db->get('etapa');
                    //etapas
                    $options_obj = array();
                    foreach ($query1->result() as $obj1) {
                        //$options_obj['data'] = $data_projeto;
                        $options_obj['data_inicio'] = $hoje;
                        $options_obj['data_termino'] = $final;
                        $options_obj['valorUnitario'] = $proporcao * $obj1->valorUnitario;
                        $options_obj['total'] = $proporcao * $obj1->total;
                        $this->db->where('idEtapa', $obj1->idEtapa);
                        $this->db->update('etapa', $options_obj);
                    }
                }

                //cronograma
                $this->db->where('Proposta_idProposta', $id);
                $query = $this->db->get('cronograma');
                $this->load->model('data_model');
                $ajustaMeta = false;
                foreach ($query->result() as $obj) {
                    $ajustaMeta = true;
                    $proporcao_crono = $proporcao_repasse;
                    if (strtoupper($obj->responsavel) == "CONVENENTE")
                        $proporcao_crono = $proporcao_contra;

                    $dataCronoFull = $obj->ano . "-" . $obj->mes . "-01";
                    $dataIniCrono = $this->data_model->calcula_datas_inicio($dataInicioProp_old, $options['data_inicio'], $dataCronoFull);
                    if (strtotime($dataIniCrono) > strtotime($options['data_termino']))
                        $dataCronoPartes = explode("-", $options['data_termino']);
                    else
                        $dataCronoPartes = explode("-", $dataIniCrono);

                    $datahora = strtotime($hoje);
                    $options_obj = array();
                    $options_obj['mes'] = $dataCronoPartes [1];
                    $options_obj['ano'] = $dataCronoPartes [0];
                    $options_obj['parcela'] = ($proporcao_crono * $obj->parcela);
                    $options_obj['valor_meta'] = ($proporcao_crono * $obj->valor_meta);

                    $proporcao_meta = $options_obj['parcela'] / $obj->parcela;

                    $this->db->where('idCronograma', $obj->idCronograma);
                    $this->db->update('cronograma', $options_obj);

                    $this->db->where('Cronograma_idCronograma', $obj->idCronograma);
                    $query1 = $this->db->get('cronograma_meta');
                    //metas
                    $options_obj = array();

                    foreach ($query1->result() as $obj1) {
                        $options_obj['valor'] = ($proporcao_meta * $obj1->valor);

                        $proporcao_etapa = $options_obj['valor'] / $obj1->valor;

                        $this->db->where('idCronograma_meta', $obj1->idCronograma_meta);
                        $this->db->update('cronograma_meta', $options_obj);

                        $this->db->where('Cronograma_meta_idCronograma_meta', $obj1->idCronograma_meta);
                        $query2 = $this->db->get('cronograma_etapa');

                        //metapas
                        $options_obj = array();
                        foreach ($query2->result() as $obj2) {
                            $options_obj['valor'] = ($proporcao_etapa * $obj2->valor);

                            $this->db->where('idCronograma_etapa', $obj2->idCronograma_etapa);
                            $this->db->update('cronograma_etapa', $options_obj);
                        }
                    }
                }

                /*                 * *********Ajusta valores da meta************** */
                if ($ajustaMeta) {
                    $this->db->where('Proposta_idProposta', $id);
                    $query1 = $this->db->get('meta');

                    $options_obj = array();
                    $lista_valores = array();
                    foreach ($query1->result() as $obj1) {
                        $this->load->model('trabalho_model');

                        $this->db->where('Meta_idMeta', $obj1->idMeta);
                        $query2 = $this->db->get('etapa');

                        //metapas
                        $options_obj = array();
                        foreach ($query2->result() as $obj2) {
                            $restante_etapa = $this->trabalho_model->obter_restante_etapa($obj2->idEtapa, true);

                            $lista_valores[$obj1->idMeta][] = array('idEtapa' => $obj2->idEtapa, 'total' => $restante_etapa);
                        }
                    }

                    if (count($lista_valores) > 0) {
                        foreach ($lista_valores as $key => $value) {
                            $total = 0;
                            foreach ($value as $arr) {
                                $total += $arr['total'];
                                $this->db->where('idEtapa', $arr['idEtapa']);
                                $this->db->update('etapa', array('total' => $arr['total']));

                                $this->db->flush_cache();
                            }

                            $this->db->where('idMeta', $key);
                            $this->db->update('meta', array('total' => $total));

                            $this->db->flush_cache();
                        }
                    }
                }
                /*                 * *********************** */

                //despesas
                if ($sohRecursoConvenio)
                    $this->db->where('natureza_aquisicao', 1);
                $this->db->where('Proposta_idProposta', $id);
                $query = $this->db->get('despesa');
                foreach ($query->result() as $obj) {
                    $options_obj = array();
                    $options_obj['valor_unitario'] = $proporcao_despesa * $obj->valor_unitario;
                    $options_obj['total'] = $proporcao_despesa * $obj->total;
                    $this->db->where('idDespesa', $obj->idDespesa);
                    $this->db->update('despesa', $options_obj);
                }
            }
        }
        //echo $id;
        //var_dump($options); die();
        $this->db->where('idProposta', $id);
        $this->db->update('proposta', $options);
        return $this->db->affected_rows();
    }

    function get_by_id($id) {

        $this->db->where('proposta.idProposta', $id);
        $this->db->where('proposta.ativo', 1);
        $this->db->select('proposta.*');
        $this->db->select('area.nome as area_nome');
        $this->db->from('area');
        $this->db->join('proposta', 'proposta.area = area.id', 'right');
        $query = $this->db->get();
        //echo $this->db->last_query();
        //var_dump($query); die();
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
        $this->db->where('estados_cod_estados', $id)
                ->order_by('nome');
        $query = $this->db->get('cidades');
        $area = $query->result_array();
        return $area;
    }

    public function busca_dados_proposta($id) {
        $this->db->where('idProposta', $id);
        $query = $this->db->get('proposta');
        return $query->row(0);
    }

    public function update_projeto_from_padrao($id_proposta, $dados_atualizar_programa) {
        $this->db->where('idProposta', $id_proposta);
        $this->db->update('proposta', $dados_atualizar_programa);
    }

    public function retorna_datas_projeto($id) {
        $this->db->select('data_inicio, data_termino');
        $this->db->where('idProposta', $id);
        return $this->db->get('proposta')->row(0);
    }

    public function verifica_cnpj_utilizado($cnpj, $idUsuario) {
        $this->db->select("COUNT(*) AS NUM");
        $this->db->where('proponente', $cnpj);
        $this->db->where('idGestor', $idUsuario);
        $num = $this->db->get('proposta')->row(0)->NUM;

        return $num > 0 ? true : false;
    }

    public function atualiza_parecer_proposta($id_proposta, $options) {
        $this->db->where('idProposta', $id_proposta);
        $this->db->update('proposta', $options);
    }

    public function checa_propostas_trinta_dias() {
        $this->load->model('usuariomodel');

        $id_gestor = $this->usuariomodel->get_id_gestor($this->session->userdata('id_usuario'));
        if ($id_gestor != null) {
            $usuarios_gestor = $this->usuariomodel->get_ids_grupo($id_gestor);
            $ids = array();
            foreach ($usuarios_gestor as $usuario_gestor)
                array_push($ids, $usuario_gestor->id_usuario);

            array_push($ids, $this->usuariomodel->get_gestor_user_id_by_gestor_id($id_gestor));
            $this->db->where_in('idGestor', $ids);
        } else
            $this->db->where_in('idGestor', $this->session->userdata('id_usuario'));

        $this->db->select("idProposta, DATEDIFF('" . date("Y-m-d") . "', data) AS diff", false);
        $this->db->where('padrao', 0);
        $this->db->where('enviado', 0);
        $this->db->where('ativo', 1);
        $this->db->where("(DATEDIFF('" . date("Y-m-d") . "', data) = ", "30", false);
        $this->db->or_where("DATEDIFF('" . date("Y-m-d") . "', data) = ", "40)", false);
        $retorno = $this->db->get('proposta')->result();

        $lista_ids = array();
        foreach ($retorno as $r)
            $lista_ids[$r->idProposta] = $r->diff;

        return $lista_ids;
    }

    public function replace_chars($string) {
        $string = str_replace('"', '', $string);
        $string = str_replace("'", "", $string);
        $string = str_replace("<", "", $string);
        $string = str_replace(">", "", $string);
        $string = str_replace("“", "", $string);
        $string = str_replace("”", "", $string);

        return $string;
    }

    public function atualiza_etapas_proposta($tabela, $options, $chave, $primary) {
        $this->db->where($chave, $primary);
        $this->db->update($tabela, $options);
    }

    /**
     * Recebe o CNPJ que está sendo utilizado para o desenvolvimento da proposta e codigo do programa
     * e retorna o valor disponivel do Proponente Especifico
     * @param unknown $cnpj
     * @param unknown $codigo
     */
    public function check_valor_disp_beneficiario($cnpj, $codigo) {
        $this->load->model('programa_model');

        $this->db->where('codigo', $codigo);
        $this->db->where('cnpj', $this->programa_model->formatCPFCNPJ($cnpj));
        $this->db->join("siconv_beneficiario", "codigo = codigo_programa");
        $benefic = $this->db->get('siconv_programa')->row(0);

        $this->db->flush_cache();

        $valorProponente = 0;
        if (isset($benefic->valor))
            $valorProponente = str_replace(",", ".", str_replace(".", "", str_replace("R$", "", $benefic->valor)));

        $this->db->select('SUM(programa_proposta.repasse_especifico) AS valor');
        $this->db->where('proponente', $cnpj);
        $this->db->where('(situacao <> ', "'Proposta/Plano de Trabalho Cancelados'", FALSE);
        $this->db->or_where('situacao IS NULL', ')', FALSE);
        $this->db->where('ativo', 1);
        $this->db->where('programa_proposta.codigo_programa', $codigo);
        $this->db->join('programa_proposta', 'idProposta = id_proposta');
        $proposta = $this->db->get('proposta')->row(0);

        $valorDisponivel = $valorProponente - $proposta->valor;

        return $valorDisponivel;
    }

    public function check_proposta_aprovada($idProposta, $idusuario, $situacao) {
        $this->load->model('banco_proposta_model');
        $this->load->model('encarregado_model');
        $this->load->model('usuariomodel');
        $this->load->model('gestor');

        $dados_gestor = $this->gestor->get_by_usuario_only_gestor($gestor->id_usuario);

        if ($this->banco_proposta_model->verifica_proposta_aprovada($situacao)) {
            if ($dados_gestor->tipo_gestor == 10) {
                $proposta = $this->get_by_id($idProposta);

                $usuario = $this->usuariomodel->get_by_id($idusuario);

                $msg = "Proposta Número: <b><a style='color:#666666;' href='https://www.convenios.gov.br/siconv/ConsultarProposta/ResultadoDaConsultaDePropostaDetalharProposta.do?idProposta={$proposta->id_proposta_efetiva}&path=/MostraPrincipalConsultarPrograma.do&Usr=guest&Pwd=guest&destino=DetalharParecerProposta' target='_blank'>" . $proposta->id_siconv . "</a></b>";

                $origem = "no-reply@info-convenios.com";
                $assunto = "Parabéns, Proposta Aprovada no SICONV";

                $urlCabecalho = base_url() . "layout/assets/images/cab_prop_apro_novo.gif";
                $urlRodape = base_url() . "layout/assets/images/rodape_emails_novo.png";

                $mensagem = "<html>
                                <div align='center' style='background-color: #eeeeee;'>
                                    <p style='color: #ffffff;'>&nbsp;</p>
                                    <div align='center' style='background-color: #ffffff; width: 580px; color: #666666; border: solid #aaaaaa; border-width: 1px; border-radius: 1%;'>
			    		<img src='{$urlCabecalho}' style='width: 580px;'/>
                                        <div align='center' style='font-family: calibri; margin-left: 20px;'>
                                            <div align='left' style='width: 550px; margin-top: 50px;'>
                                                <div style='margin-left: 20px; padding-right:1px; font-size: 16px; width: 500px;'>
                                                    <p>Parabéns, uma nova proposta foi aprovada no SICONV.</p>
                                                    <p>{$msg}</p>
                                                </div>
                                                <br><br>
                                                <div style='margin-left: 20px; font-size: 14px;'>
                                                    <p>Atenciosamente,</p>
                                                    <p>Departamento de Atendimento ao Cliente</p>
                                                </div>
                                            </div>
			    		</div>
			    		<img src='{$urlRodape}' style='width: 580px; margin-top: 30px;'/>
                                    </div>
                                </div>
                            </html>";

                $this->envia_email_infoconvenios($origem, $usuario->email, null, $assunto, $mensagem);

                $this->update_status_envio_email($idProposta);
            } else {
                $proposta = $this->get_by_id($idProposta);

                $usuario = $this->usuariomodel->get_by_id($idusuario);

                $encarregados = $this->encarregado_model->get_by_gestor($this->gestor->get_all_by_usuario($usuario->id_usuario)->id_gestor);

                $msg = "Proposta Número: <b><a style='color:#fff;' href='https://www.convenios.gov.br/siconv/ConsultarProposta/ResultadoDaConsultaDePropostaDetalharProposta.do?idProposta={$proposta->id_proposta_efetiva}&path=/MostraPrincipalConsultarPrograma.do&Usr=guest&Pwd=guest&destino=DetalharParecerProposta' target='_blank'>" . $proposta->id_siconv . "</a></b>";

                $origem = "no-reply@physisbrasil.com.br";
                $assunto = "Parabéns, Proposta Aprovada no SICONV";

                $urlCabecalho = base_url() . "layout/assets/images/cabecalho_email_parabens.png";
                $urlRodape = base_url() . "layout/assets/images/rodape.png";

                $mensagem = "<html>
			    		<div align='center' style='background-color: #f6f6f6; height: 700px;'>
			    		<p style='color: #f6f6f6;'>&nbsp;</p>
			    		<div align='center' style='background-color: #6287c4; width: 520px; color:#fff;'>
			    		<img src='{$urlCabecalho}' style='width: 524px; height: 255px; margin-top: -2px;'/>
			    		<div align='center' style='font-family: calibri; margin-left: 20px;'>
			    		
			    		<div align='left'  style='width: 500px;'>
			    		<div style='margin-left: 20px; padding-right: 1px; font-size: 17px; width: 460px;'>
			    		 
			    		<p>Parabéns, uma nova proposta enviada pelo sistema e-SICAR foi aprovada no SICONV.</p>
			    		<p>{$msg}</p>
			    		
			    		</div>
			    		 
			    		</div>
			    		
			    		</div>
			    		<img src='{$urlRodape}' style='width: 520px; height: 70px;'/>
			    		</div>
			    		 
			    		</div>
			    		</html>";

                $this->envia_email($origem, $usuario->email, null, $assunto, $mensagem);

                foreach ($encarregados as $encarregado)
                    $this->envia_email($origem, $encarregado->email, null, $assunto, $mensagem);

                $this->update_status_envio_email($idProposta);
            }
        }
    }

    public function check_proposta_banco_proposta_aprovada($idProposta, $idusuario, $situacao) {
        $this->load->model('banco_proposta_model');
        $this->load->model('encarregado_model');
        $this->load->model('usuariomodel');
        $this->load->model('gestor');

        $dados_gestor = $this->gestor->get_by_usuario_only_gestor($idusuario);

        if ($this->banco_proposta_model->verifica_proposta_aprovada($situacao)) {
            if ($dados_gestor->tipo_gestor == 10) {
                $proposta = $this->banco_proposta_model->get_by_id($idProposta);

                $usuario = $this->usuariomodel->get_by_id($idusuario);

                $msg = "Proposta Número: <b><a style='color:#666666;' href='https://www.convenios.gov.br/siconv/ConsultarProposta/ResultadoDaConsultaDePropostaDetalharProposta.do?idProposta={$proposta->id_siconv}&path=/MostraPrincipalConsultarPrograma.do&Usr=guest&Pwd=guest&destino=DetalharParecerProposta' target='_blank'>" . $proposta->codigo_siconv . "</a></b>";

                $origem = "no-reply@info-convenios.com";
                $assunto = "Parabéns, Proposta Aprovada no SICONV";

                $mensagem = "<html>
                                <div align='center' style='background-color: #eeeeee;'>
                                    <p style='color: #ffffff;'>&nbsp;</p>
                                    <div align='center' style='background-color: #ffffff; width: 580px; color: #666666; border: solid #aaaaaa; border-width: 1px; border-radius: 1%;'>
                                        <div align='center' style='font-family: calibri; margin-left: 20px;'>
                                            <div align='left' style='width: 550px; margin-top: 50px;'>
                                                <div style='margin-left: 20px; padding-right:1px; font-size: 16px; width: 500px;'>
                                                    <p>Parabéns, uma nova proposta foi aprovada no SICONV.</p>
                                                    <p>{$msg}</p>
                                                </div>
                                                <br><br>
                                                <div style='margin-left: 20px; font-size: 14px;'>
                                                    <p>Atenciosamente,</p>
                                                    <p>Departamento de Atendimento ao Cliente</p>
                                                </div>
                                            </div>
			    		</div>
                                    </div>
                                </div>
                            </html>";

                $this->envia_email_infoconvenios($origem, $usuario->email, null, $assunto, $mensagem);

                $this->update_status_envio_email($idProposta);
            } else {
                $proposta = $this->banco_proposta_model->get_by_id($idProposta);

                $usuario = $this->usuariomodel->get_by_id($idusuario);

                $encarregados = $this->encarregado_model->get_by_gestor($this->gestor->get_all_by_usuario($usuario->id_usuario)->id_gestor);

                $msg = "Proposta Número: <b><a style='color:#fff;' href='https://www.convenios.gov.br/siconv/ConsultarProposta/ResultadoDaConsultaDePropostaDetalharProposta.do?idProposta={$proposta->id_siconv}&path=/MostraPrincipalConsultarPrograma.do&Usr=guest&Pwd=guest&destino=DetalharParecerProposta' target='_blank'>" . $proposta->codigo_siconv . "</a></b>";

                $origem = "no-reply@physisbrasil.com.br";
                $assunto = "Parabéns, Proposta Aprovada no SICONV";

                $mensagem = "<html>
                                <div align='center' style='background-color: #eeeeee;'>
                                    <p style='color: #ffffff;'>&nbsp;</p>
                                    <div align='center' style='background-color: #ffffff; width: 580px; color: #666666; border: solid #aaaaaa; border-width: 1px; border-radius: 1%;'>
                                        <div align='center' style='font-family: calibri; margin-left: 20px;'>
                                            <div align='left' style='width: 550px; margin-top: 50px;'>
                                                <div style='margin-left: 20px; padding-right:1px; font-size: 16px; width: 500px;'>
                                                    <p>Parabéns, uma nova proposta foi aprovada no SICONV.</p>
                                                    <p>{$msg}</p>
                                                </div>
                                                <br><br>
                                                <div style='margin-left: 20px; font-size: 14px;'>
                                                    <p>Atenciosamente,</p>
                                                    <p>Departamento de Atendimento ao Cliente</p>
                                                </div>
                                            </div>
			    		</div>
                                    </div>
                                </div>
                            </html>";

                $this->envia_email($origem, $usuario->email, null, $assunto, $mensagem);

                foreach ($encarregados as $encarregado) {
                    $this->envia_email($origem, $encarregado->email, null, $assunto, $mensagem);
                }

                $this->update_status_envio_email($idProposta);
            }
        }
    }

    public function update_status_envio_email($idProposta) {
        $this->db->where('idProposta', $idProposta);
        $this->db->update('proposta', array('enviado_email_aprovado' => 'S'));
    }

    function envia_email($origem, $destino, $copia, $assunto, $mensagem) {
        $this->load->model('usuariomodel');

        $this->load->library('email');

        $this->email->initialize($this->usuariomodel->inicializa_config_email($origem));

        $this->email->set_mailtype('html');
        $this->email->from($origem, "Captação Recursos SIHS -- SIHS");
        $this->email->to($destino);
        if ($copia != null) {
            $this->email->cc($copia);
        }
        $this->email->subject($assunto);
        $this->email->message($mensagem);
        $this->email->send();
    }

    function envia_email_infoconvenios($origem, $destino, $copia, $assunto, $mensagem) {
        $this->load->model('usuariomodel');

        $this->load->library('email');

        $this->email->initialize($this->usuariomodel->inicializa_config_email($origem));

        $this->email->set_mailtype('html');
        $this->email->from($origem, "Info Convênios");
        $this->email->to($destino);
        if ($copia != null) {
            $this->email->cc($copia);
        }
        $this->email->subject($assunto);
        $this->email->message($mensagem);
        $this->email->send();
    }

    //Return all propostas from parlamentar
    public function get_all_propostas_emenda_parlamentar($numero_parlamentar) {
        $this->db->select('pv.*, sb.emenda');
        $this->db->join('siconv_beneficiario sb', 'pv.codigo = sb.codigo_programa');
        $this->db->where("sb.emenda <> '' AND sb.emenda LIKE '" . $numero_parlamentar . "%'");
        $programas_com_emendas = $this->db->get('siconv_programa pv')->result();

        $propostas_banco_proposta = array();
        if (count($programas_com_emendas) > 0) {
            foreach ($programas_com_emendas as $programa) {
                $tempPropostas = array();

                //Consulta - pegando para cada um dos codigos de programas as propostas a partir do programa e emenda do banco de propostas
                $this->db->join('programa_banco_proposta pbp', 'pbp.id_proposta_banco_proposta = id_proposta');
                $this->db->join('emenda_banco_proposta ebp', 'ebp.id_programa_banco_proposta = pbp.id_programa_banco_proposta');
                $this->db->where('pbp.codigo_programa', $programa->codigo);
                $this->db->where('ebp.codigo_emenda', $programa->emenda);

                $this->db->select('bp.*, ebp.codigo_emenda');
                $query = $this->db->get('banco_proposta bp');
                $tempPropostas = $query->result();

                if (count($tempPropostas) > 0) {
                    $propostas_banco_proposta = array_merge($propostas_banco_proposta, $tempPropostas);
                }
            }
        }

        if (count($propostas_banco_proposta) > 0) {
            return $propostas_banco_proposta;
        } else {
            return null;
        }
    }

}

?>
