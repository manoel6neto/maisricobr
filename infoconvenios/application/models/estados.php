<?php

class estados extends CI_Model {

    function get_all() {
        $query = $this->db->get('estados');
        return $query->result();
    }

    function get_by_id($id_estado) {
        $this->db->where('cod_estados', $id_estado);
        $query = $this->db->get('estados');
        return $query->row(0);
    }
    
    function get_by_sigla($sigla) {
        $this->db->select('cod_estados');
        $this->db->where('sigla', $sigla);
        $query = $this->db->get('estados');
        
        if ($query->num_rows > 0) {
            return $query->row(0)->cod_estados;
        } else {
            return null;
        }
    }
}