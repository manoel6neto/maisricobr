<?php

class pagseguro_usuario_model extends CI_Model {

    public function get_by_user($id_usuario) {
        $this->db->where('id_usuario', $id_usuario);
        $this->db->order_by('data_compra', 'DESC');
        return $this->db->get('pagseguro_usuario')->row(0);
    }

    public function get_by_cod_ref($id_usuario, $cod_referencia) {
        $this->db->where('id_usuario', $id_usuario);
        if ($cod_referencia == null) {
            $this->db->where('codigo_ref_compra is null');
        } else {
            $this->db->like('codigo_ref_compra', $cod_referencia);    
        }
        return $this->db->get('pagseguro_usuario')->row(0);
    }

    public function get_all_compra($id_usuario) {
        $this->db->where('id_usuario', $id_usuario);
        $this->db->order_by('data_compra', 'DESC');
        return $this->db->get('pagseguro_usuario')->result();
    }

    public function get_all_usuario($filtro = "") {
        $this->db->select("usuario.*, nivel_usuario.nome AS nome_nivel");
        $this->db->join('nivel_usuario', "nivel_usuario.id_nivel_usuario = usuario.id_nivel");
        $this->db->where('id_nivel', 2);
        
        $this->db->join('gestor', 'gestor.id_usuario = usuario.id_usuario');
        $this->db->where('tipo_gestor', 10);

        if ($filtro != "") {
            $this->db->join('pagseguro_usuario pg', 'pg.id_usuario = usuario.id_usuario');
            $this->db->like('codigo_ref_compra', $filtro);
        }

        $query = $this->db->get('usuario')->result();

        return $query;
    }

    public function get_user_by_id($id_usuario) {
        $this->db->where('id_usuario', $id_usuario);
        return $this->db->get('usuario')->row(0);
    }

    public function set_permissao_plano($id_usuario, $cod_referencia) {
        $this->load->model('usuariomodel');

        $dados = $this->get_by_cod_ref($id_usuario, $cod_referencia);

        $this->db->flush_cache();

        $servico = explode(",", $dados->tipo_servico);

        //Se não foi comprado os três tipos de serviço, será alterada a permissão para que o usuario tenha acesso somente ao que comprou
        if (count($servico) < 3) {
            $this->load->model('permissoes_usuario', 'pu');

            if (!in_array('PA', $servico)) {
                $this->pu->update_by_usuario_id($id_usuario, array('consultar_programa' => 0, 'relatorio_programa' => 0));
            } else {
                $this->pu->update_by_usuario_id($id_usuario, array('consultar_programa' => 1, 'relatorio_programa' => 1));
            }

            if (!in_array('ED', $servico)) {
                $this->pu->update_by_usuario_id($id_usuario, array('visualiza_emendas' => 0));
            } else {
                $this->pu->update_by_usuario_id($id_usuario, array('visualiza_emendas' => 1));
            }

            if (!in_array('SP', $servico)) {
                $this->pu->update_by_usuario_id($id_usuario, array('visualiza_prop_parecer' => 0));
            } else {
                $this->pu->update_by_usuario_id($id_usuario, array('visualiza_prop_parecer' => 1));
            }

            $this->db->flush_cache();
        }

        $this->load->model('data_model');

        $this->db->where('id_usuario', $id_usuario);
        $this->db->update('gestor', array('validade' => $this->data_model->retornaNovaData(date("Y-m-d"), $dados->validade_plano, true)));

        $this->db->flush_cache();

        $this->db->where('id_usuario', $id_usuario);
        if ($cod_referencia == null) {
            $this->db->where('codigo_ref_compra is null');
        } else {
            $this->db->like('codigo_ref_compra', $cod_referencia);
        }
        $this->db->update('pagseguro_usuario', array('compra_paga' => 1));
    }

}
