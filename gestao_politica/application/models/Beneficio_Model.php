<?php

class Beneficio_Model extends CI_Model {

    //Beneficio
    public function get_all_beneficio() {
        $query = $this->db->get('beneficio');
        if (count($query->result()) > 0) {
            return $query->result();
        }

        return NULL;
    }

    public function get_beneficio_by_id($id_beneficio) {
        $this->db->where('id', $id_beneficio);
        $query = $this->db->get('beneficio');
        if (count($query->result()) > 0) {
            return $query->row(0);
        }

        return NULL;
    }

    public function get_beneficio_by_usuario_id($id_usuario) {
        $this->db->where('id_usuario', $id_usuario);
        $query = $this->db->get('beneficio');
        if (count($query->result()) > 0) {
            return $query->result();
        }

        return NULL;
    }

    public function insert_beneficio($options) {
        $this->db->insert('beneficio', $options);
        $return_id = $this->db->insert_id();

        return $return_id;
    }

    public function delete_beneficio_by_id($id_beneficio) {
        $this->db->where('id', $id_beneficio);
        $this->db->delete('beneficio');

        return $this->db->affected_rows();
    }

    public function update_beneficio_by_id($id_beneficio, $options) {
        $this->db->where('id', $id_beneficio);
        $this->db->update('beneficio', $options);

        return $this->db->affected_rows();
    }

    public function get_all_publico_alvo() {
        $query = $this->db->get('publico_alvo');
        if (count($query->result()) > 0) {
            return $query->result();
        }

        return NULL;
    }

}
