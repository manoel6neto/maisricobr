<?php

class regioes extends CI_Model {

    function get_all() {
        $query = $this->db->get('tb_regioes');
        return $query->result_array();
    }
}
