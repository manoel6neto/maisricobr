<?php

class nivel_usuario extends CI_Model {

    function get_all() {
        $query = $this->db->get('nivel_usuario');
        return $query->result();
    }
    
    function get_by_id($id_nivel_usuario) {
        $this->db->where('id_nivel_usuario', $id_nivel_usuario);
        $query = $this->db->get('nivel_usuario');
        
        return $query->row(0);
    }
    
    ## Não tem metodos para add, atualizar ou remover. Dados são estáticos da aplicação.
}