<?php

class empenhos_esicar_model extends CI_Model {

    function get_all() {
        $query = $this->db->get('empenhos_esicar');
        return $query->result();
    }

    function get_by_id($id_empenho) {
        $this->db->where('id_empenho', $id_empenho);
        $query = $this->db->get('empenhos_esicar');

        return $query->row(0);
    }

    function get_by_id_siconv($id_empenho_siconv) {
        $this->db->where('id_empenho_siconv', $id_empenho_siconv);
        $query = $this->db->get('empenhos_esicar');

        return $query->result();
    }

    function get_by_id_proposta_siconv($id_proposta_siconv) {
        $this->db->where('id_proposta_siconv', $id_proposta_siconv);
        $query = $this->db->get('empenhos_esicar');

        return $query->result();
    }

    function add_empenho($options) {

        return $this->db->insert('empenhos_esicar', $options);
    }

    function del_by_id_proposta_siconv($id_proposta_siconv) {
        
        $this->db->where('id_proposta_siconv', $id_proposta_siconv);
        $this->db->delete('empenhos_esicar');
    }
    
    function del_by_id_proposta_siconv_object($options) {
        
        $this->db->where('id_proposta_siconv', $options['id_proposta_siconv']);
        $this->db->delete('empenhos_esicar');
    }
    
    function add_or_update_empenho($options) {
        $this->db->where('id_empenho_siconv', $options['id_empenho_siconv']);
        $query = $this->db->get('empenhos_esicar');
        
        if ($query->num_rows > 0) {
            $this->db->where('id_empenho_siconv', $query->row(0)->id_empenho_siconv);
            return $this->db->update('empenhos_esicar', $options);
        } else {
            return $this->db->insert('empenhos_esicar', $options);
        }
    }

}
