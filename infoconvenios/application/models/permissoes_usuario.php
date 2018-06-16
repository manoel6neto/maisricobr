<?php

class permissoes_usuario extends CI_Model {

    function get_all() {
        $query = $this->db->get('permissoes_usuario');
        return $query->result();
    }

    function get_by_id($id_permissoes_usuario) {
        $this->db->where('id_permissoes_usuario', $id_permissoes_usuario);
        $query = $this->db->get('permissoes_usuario');
        return $query->row(0);
    }

    function update_by_id($id_permissoes_usuario, $options) {
        $this->db->where('id_permissoes_usuario', $id_permissoes_usuario);
        return $this->db->update('permissoes_usuario', $options);
    }

    function get_by_usuario_id($id_usuario) {
        $this->db->where('id_usuario', $id_usuario);
        $query = $this->db->get('permissoes_usuario');
        return $query->row(0);
    }

    function update_by_usuario_id($id_usuario, $options) {
        $this->db->where('id_usuario', $id_usuario);
        return $this->db->update('permissoes_usuario', $options);
    }

}
