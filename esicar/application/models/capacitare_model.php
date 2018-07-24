<?php

class capacitare_model extends CI_Model {

    public function get_all() {
        $this->db->flush_cache();
        $this->db->distinct();
        $query = $this->db->get('capacitare');
        if ($query->num_rows > 0) {
            return $query->result();
        }

        return NULL;
    }

    public function get_eventos() {
        $this->db->flush_cache();
        $query = $this->db->get('capacitare_evento');
        if ($query->num_rows > 0) {
            return $query->result();
        }

        return NULL;
    }

    public function get_by_evento($evento_id) {
        $this->db->flush_cache();
        $this->db->distinct();
        $this->db->where('id', $evento_id);
        $query = $this->db->get('capacitare_evento');
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

    public function format_data_date($dataString) {
        $phpdate = strtotime($dataString);
        $myFormatForView = date("d/m/Y", $phpdate);

        return $myFormatForView;
    }

    function mask($val, $mask) {
        $maskared = '';
        $k = 0;
        for ($i = 0; $i <= strlen($mask) - 1; $i++) {
            if ($mask[$i] == '#') {
                if (isset($val[$k]))
                    $maskared .= $val[$k++];
            }
            else {
                if (isset($mask[$i]))
                    $maskared .= $mask[$i];
            }
        }
        return $maskared;
    }

}
