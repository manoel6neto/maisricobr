<?php

class cnpj_model extends CI_Model {
    
    function get_all_or_user($filter = NULL){
        if ($filter != NULL) { $this->db->where('idPessoa =', $filter); }
        $query = $this->db->get('cnpj_aberto');
        return $query->result();
    }
}

