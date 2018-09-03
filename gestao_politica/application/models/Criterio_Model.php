<?php

class Criterio_Model extends CI_Model {

    // Criterio    
    public function get_all_criterio() {
        $query = $this->db->get('criterio');
        if (count($query->result()) > 0) {
            return $query->result();
        }
        return NULL;
    }

    public function get_criterio_by_id($id_criterio) {
        $this->db->where('id', $id_criterio);
        $query = $this->db->get('criterio');
        if (count($query->result()) > 0) {
            return $query->result();
        }
        return NULL;
    }

    public function get_criterio_by_usuario_id($id_usuario) {
        $this->db->where('id_usuario', $id_usuario);
        $query = $this->db->get('usuario');
        if (count($query->result()) > 0) {
            return $query->result();
        }
        return NULL;
    }

    public function insert_criterio($options) {
        $this->db->where('criterio', $options);
        $return_id = $this->db->insert_id();

        return $return_id;
    }

    public function delete_criterio_by_id($id_criterio) {
        $this->db->where('criterio', $id_criterio);
        $this->db->delete('criterio');

        return $this->db->affected_rows();
    }

    public function update_criterio_by_id($id_criterio, $options) {
        $this->db->where('id', $id_criterio);
        $this->db->update('id', $options);

        return $this->db->affected_rows();
    }

    //Criterio Beneficio

    public function get_all_criterio_beneficio() {
        $query = $this->db->get('criterio_beneficio');
        if (count($query->result()) > 0) {
            return $query->result();
        }
        return NULL;
    }

    public function get_criterio_beneficio_by_id($id_criterio) {
        $this->db->where('id', $id_criterio);
        $query = $this->db->get('criterio_beneficio');
        if (count($query->result()) > 0) {
            return $query->result();
        }
        return NULL;
    }

    public function get_criterio_beneficio_by_criterio_id($id_criterio) {
        $this->db->where('id_criterio', $id_criterio);
        $query = $this->db->get('criterio_beneficio');
        if (count($query->result()) > 0) {
            return $query->result();
        }
        return NULL;
    }

    public function get_criterio_beneficio_by_beneficio_id($id_beneficio) {
        $this->db->where('id_beneficio', $id_beneficio);
        $query = $this->db->get('criterio_beneficio');
        if (count($query->result()) > 0) {
            return $query->result();
        }
        return NULL;
    }

    public function get_criterio_by_beneficio_id($id_beneficio) {
        $this->db->select('criterio.*');
        $this->db->join('criterio_beneficio cb', 'cb.id_criterio = c.id');
        $this->db->where('cb.id_beneficio', $id_beneficio);
        $query_criterio = $this->db->get('criterio c');

        if (count($query_criterio->result()) > 0) {
            return $query_criterio->result();
        }

        return NULL;
    }

    public function insert_criterio_beneficio($options) {
        $this->db->where('criterio_beneficio', $options);
        $return_id = $this->db->insert_id();

        return $return_id;
    }

    public function delete_criterio_beneficio_by_id($id_criterio) {
        $this->db->where('criterio_beneficio', $id_criterio);
        $this->db->delete('criterio_beneficio');

        return $this->db->affected_rows();
    }

    public function update_criterio_beneficio_by_id($id_criterio, $options) {
        $this->db->where('id', $id_criterio);
        $this->db->update('criterio_beneficio', $options);

        return $this->db->affected_rows();
    }

}
