<?php

class senha_eliumar extends CI_Model {
    
    public function get_password() {
        $query = $this->db->get('senha_eliumar');
        return $query->row(0);
    }
    
}
