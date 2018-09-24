<?php

class Beneficio_Model extends CI_Model {

    // ------------ Beneficio -----------------
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

    // ------------- Publico Alvo -------------------
    public function get_all_publico_alvo() {
        $query = $this->db->get('publico_alvo');
        if (count($query->result()) > 0) {
            return $query->result();
        }

        return NULL;
    }

    public function get_publico_alvo_by_id($id_publico_alvo) {
        $this->db->where('id', $id_publico_alvo);
        $query = $this->db->get('publico_alvo');
        if (count($query->result()) > 0) {
            return $query->row(0);
        }

        return NULL;
    }

    // ----------- Orgão Gestor -------------
    public function get_all_orgao_gestor() {
        $query = $this->db->get('orgao_gestor');
        if (count($query->result()) > 0) {
            return $query->result();
        }

        return NULL;
    }

    public function get_orgao_gestor_by_id($id_orgao_gestor) {
        $this->db->where('id', $id_orgao_gestor);
        $query = $this->db->get('orgao_gestor');
        if (count($query->result()) > 0) {
            return $query->row(0);
        }

        return NULL;
    }

    // ------------- Tipo Benefício ---------------
    public function get_all_tipo_beneficio() {
        $query = $this->db->get('tipo_beneficio');
        if (count($query->result()) > 0) {
            return $query->result();
        }

        return NULL;
    }

    public function get_tipo_beneficio_by_id($id_tipo_beneficio) {
        $this->db->where('id', $id_tipo_beneficio);
        $query = $this->db->get('tipo_beneficio');
        if (count($query->result()) > 0) {
            return $query->row(0);
        }

        return NULL;
    }

    // -------------- Parâmetro Benefício --------------------
    public function get_all_parametro_beneficio_by_id_beneficio($id_beneficio) {
        $this->db->where('id_beneficio', $id_beneficio);
        $query = $this->db->get('parametro_beneficio');
        if (count($query->result()) > 0) {
            return $query->result();
        }

        return NULL;
    }

    public function insert_parametro_beneficio($options) {
        $this->db->insert('parametro_beneficio', $options);
        $return_id = $this->db->insert_id();

        return $return_id;
    }

    public function delete_parametro_beneficio_by_id_beneficio($id_beneficio) {
        $this->db->where('id_beneficio', $id_beneficio);
        $this->db->delete('parametro_beneficio');

        return $this->db->affected_rows();
    }

    // -------------- Critério de Seleção - Sexo --------------------
    public function get_all_selecao_sexo() {
        $query = $this->db->get('sexo');
        if (count($query->result()) > 0) {
            return $query->result();
        }

        return NULL;
    }

    // -------------- Critério de Seleção - Cor ou Raça --------------------
    public function get_all_selecao_cor_raca() {
        $query = $this->db->get('cor');
        if (count($query->result()) > 0) {
            return $query->result();
        }

        return NULL;
    }

    // -------------- Insert - Critérios de Seleção --------------------

    public function insert_criterio_beneficio($options) {
        $this->db->flush_cache();
        $this->db->insert('criterio_beneficio', $options);
        $return_id = $this->db->insert_id();

        return $return_id;
    }

}
