<?php

class capacitare_model extends CI_Model {

    public function get_all() {
        $this->db->flush_cache();
        $query = $this->db->get('capacitare');
        if ($query->num_rows > 0) {
            return $query->result();
        }

        return NULL;
    }

    public function insert_data($email, $telefone) {
        $options = array(
            'email' => $email,
            'telefone' => $telefone
        );

        $this->db->insert('capacitare', $options);
        return $this->db->affected_rows();
    }

    public function format_data($dataString) {
        $phpdate = strtotime($dataString);
        $myFormatForView = date("d/m/Y - H:i", $phpdate);
        
        return $myFormatForView;
    }

}
