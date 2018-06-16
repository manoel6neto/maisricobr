<?php

class get_proponentes_model extends CI_Model {

    public function insert_or_update($options) {
        $this->db->where('cnpj', $options['cnpj']);
        $query = $this->db->get('proponente_siconv');
        if (count($query->result()) > 0) {
            $this->db->where('id_proponente_siconv', $query->row(0)->id_proponente_siconv);
            $this->db->update('proponente_siconv', $options);
        } else
            $this->db->insert('proponente_siconv', $options);
    }

    public function check_exist($cnpj) {
        $this->db->where('cnpj', $cnpj);
        $query = $this->db->get('proponente_siconv');
        if (count($query->result()) > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function insert_or_update_situacao($options) {
        $this->db->where('cnpj', $options['cnpj']);
        $query = $this->db->get('situacao_proponente_siconv');
        if (count($query->result()) > 0) {
            $this->db->where('id_situacao_proponente_siconv', $query->row(0)->id_situacao_proponente_siconv);
            $this->db->update('situacao_proponente_siconv', $options);
        } else
            $this->db->insert('situacao_proponente_siconv', $options);
    }

}
