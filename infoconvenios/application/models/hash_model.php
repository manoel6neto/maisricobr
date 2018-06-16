<?php

class hash_model extends CI_Model {

    function get_all() {
        $query = $this->db->get('infoconvenio_marketing_hash');
        if ($query->num_rows > 0) {
            return $query->result();
        }

        return null;
    }

    function get_by_id($id) {
        $this->db->where('id', $id);

        $query = $this->db->get('infoconvenio_marketing_hash');
        if ($query->num_rows > 0) {
            return $query->row(0);
        }

        return null;
    }

    function get_usuario($id_usuario) {
        $this->db->where('id_usuario', $id_usuario);

        $query = $this->db->get('infoconvenio_marketing_hash');
        if ($query->num_rows > 0) {
            return $query->row(0);
        }

        return null;
    }

    function get_by_hash($hash) {
        $this->db->where('hash', $hash);

        $query = $this->db->get('infoconvenio_marketing_hash');
        if ($query->num_rows > 0) {
            return $query->row(0);
        }

        return null;
    }

    function get_available() {
        $this->db->where('id_usuario is null');

        $query = $this->db->get('infoconvenio_marketing_hash');
        if ($query->num_rows > 0) {
            return $query->row(0);
        }

        return null;
    }

    function get_used() {
        $this->db->where('id_usuario is not null');

        $query = $this->db->get('infoconvenio_marketing_hash');
        if ($query->num_rows > 0) {
            return $query->row(0);
        }

        return null;
    }

    function get_user_by_hash($hash) {
        $this->db->select('usuario.*');
        $this->db->join('usuario', 'usuario.id_usuario = infoconvenio_marketing_hash.id_usuario');
        $this->db->where('hash', $hash);

        $query = $this->db->get('infoconvenio_marketing_hash');
        if ($query->num_rows > 0) {
            return $query->row(0);
        }

        return null;
    }
    
    function mark_hash_for_user($id_user, $hash) {
        $options = array('id_usuario' => $id_user);

        $this->db->where('hash', $hash);
        $this->db->update('infoconvenio_marketing_hash', $options);
        
        return $this->db->affected_rows();
    }

}
