<?php

class justificativa extends CI_Model {

    function get_by_id($id) {

        $this->db->where('idJustificativa', $id);
        $query = $this->db->get('justificativa');
        return $query->row(0);
    }

    function update_texto($id, $texto) {

        $this->db->set('Justificativa', $texto);
        $this->db->where('idJustificativa', $id);
        $this->db->update('justificativa');
        return $this->db->affected_rows();
    }
    
    function insert($options) {
        $this->db->insert('justificativa', $options);
        return $this->db->insert_id();
    }

}
