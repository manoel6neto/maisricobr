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

    function get_by_user($user_id) {
        $this->db->select('n.*');
        $this->db->where('u.id_usuario', $user_id);
        $this->db->join('usuario u', 'u.id_nivel = n.id_nivel_usuario');
        $query = $this->db->get('nivel_usuario n');

        return $query->row(0);
    }

    ## Não tem metodos para add, atualizar ou remover. Dados são estáticos da aplicação.
}
