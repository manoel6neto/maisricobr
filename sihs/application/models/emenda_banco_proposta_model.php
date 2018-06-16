<?php

class emenda_banco_proposta_model extends CI_Model {

    function get_all() {
        $query = $this->db->get('emenda_banco_proposta');
        return $query->result();
    }

    function get_by_id($id_emenda_banco_proposta) {
        $this->db->where('id_emenda_banco_proposta', $id_emenda_banco_proposta);
        $query = $this->db->get('emenda_banco_proposta');

        return $query->row(0);
    }

    function get_by_id_programa($id_programa_banco_proposta) {
        $this->db->where('id_programa_banco_proposta', $id_programa_banco_proposta);
        $query = $this->db->get('emenda_banco_proposta');

        return $query->result();
    }

    function get_by_id_proposta($id_proposta) {
        $this->load->model('programa_banco_proposta_model');

        $programas = $this->programa_banco_proposta_model->get_by_id_proposta($id_proposta);
        $emendas = array();
        foreach ($programas as $programa) {
            $tempEmendas = $this->get_by_id_programa($programa->id_programa_banco_proposta);
            foreach ($tempEmendas as $emenda) {
                array_push($emendas, $emenda);
            }
        }

        return $emendas;
    }

    //Inserir uma emenda no banco de propostas
    function insert($options) {
        $this->db->where('id_programa_banco_proposta', $options['id_programa_banco_proposta']);
        $this->db->where('codigo_emenda', $options['codigo_emenda']);
        $query = $this->db->get('emenda_banco_proposta');

        if ($query->num_rows == 0) {
            return $this->db->insert('emenda_banco_proposta', $options);
        }
    }

}
