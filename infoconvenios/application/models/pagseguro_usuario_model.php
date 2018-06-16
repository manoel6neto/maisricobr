<?php

class pagseguro_usuario_model extends CI_Model {

    public function get_by_user($id_usuario) {
        $this->db->where('id_usuario', $id_usuario);
        $this->db->order_by('data_compra', 'DESC');
        return $this->db->get('pagseguro_usuario')->row(0);
    }
    
    public function ativa_compra($id_usuario, $cod_referencia) {
        $this->db->where('id_usuario', $id_usuario);
        $this->db->where('codigo_ref_compra', $cod_referencia);
        
        $array_update = array('ativa' => 1);
        
        $this->db->update('pagseguro_usuario', $array_update);
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
        $this->db->select("pg.*");
        $this->db->join('usuario u', 'u.id_usuario = pg.id_usuario');
//        $this->db->where('u.id_nivel', 2);
        $this->db->join('gestor', 'gestor.id_usuario = u.id_usuario');
//        $this->db->where('gestor.tipo_gestor', 10);
//        $this->db->where('pg.ativa = 1');
        $this->db->where('u.id_nivel = 2 AND gestor.tipo_gestor = 10 AND (pg.ativa = 1 OR pg.compra_paga = 0)');

        if ($filtro != "") {
            $this->db->like('ph.codigo_ref_compra', $filtro);
        }

        $this->db->order_by('id_usuario', 'desc');

        $query = $this->db->get('pagseguro_usuario pg')->result();

        return $query;
    }

    public function ativar_plano_desativar_restantes($id_usuario, $cod_referencia) {
        $this->db->flush_cache();
        
        //Desativar todas as outras
        $this->db->where('id_usuario', $id_usuario);
        $array_update = array('ativa' => 0);
        $this->db->update('pagseguro_usuario', $array_update);
        
        $this->db->flush_cache();

        //Ativar a compra
        $this->db->where('id_usuario', $id_usuario);
        $this->db->where('codigo_ref_compra', $cod_referencia);

        $array_update = array('ativa' => 1);
        $this->db->update('pagseguro_usuario', $array_update);
        
        $this->db->flush_cache();
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

//        $this->load->model('data_model');
//
//        $this->db->where('id_usuario', $id_usuario);
//        $this->db->update('gestor', array('validade' => $this->data_model->retornaNovaData(date("Y-m-d"), $dados->validade_plano, true)));

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
