<?php

class encarregado_model extends CI_Model {

    function get_all() {
        $query = $this->db->get('encarregado');
        return $query->result();
    }

    function get_by_id($id_encarregado) {
        $this->db->where('id_encarregado', $id_encarregado);
        $query = $this->db->get('encarregado');

        return $query->row(0);
    }

    function add_encarregado($options) {

        return $this->db->insert('encarregado', $options);
    }

    function get_by_gestor($id_gestor) {
        $this->db->where('id_gestor', $id_gestor);
        $query = $this->db->get('encarregado');

        return $query->result();
    }

    function del_by_id($id_encarregado) {
        $this->db->where('id_encarregado', $id_encarregado);
        $this->db->delete('encarregado');
    }

    function del_by_gestor($id_gestor) {
        $this->db->where('id_gestor', $id_gestor);
        $this->db->delete('encarregado');
    }

    function update_by_id($id_encarregado, $options) {
        $this->db->where('id_encarregado', $id_encarregado);
        return $this->db->update('encarregado', $options);
    }

    function adicionar_by_gestor_id($id_gestor, $encarregado) {
        $this->db->insert('encarregado', $encarregado);
    }
    
    function atualizar_by_gestor_id($id_gestor, $id_encarregado, $encarregado) {
            $this->db->where('id_gestor', $id_gestor);
            $this->db->where('id_encarregado', $id_encarregado);
            $this->db->update('encarregado', $encarregado);
    }

}
