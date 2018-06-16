<?php

class area_model extends CI_Model{

    public function get_area_from_ministerio($nome_ministerio) {
        $this->db->select('id');
        $this->db->like('nome', $nome_ministerio);
        $query = $this->db->get('area');
        
        if ($query->num_rows > 0) {
            return $query->row(0)->id;
        } else {
            return 0;
        }
    }
    
    public function get_all() {
        $this->db->distinct();
        $this->db->select('nome');
        $query = $this->db->get('area');
        
        return $query->result();
    }
    
}