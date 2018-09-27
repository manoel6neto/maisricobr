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

    public function remove_cadastro($id) {
        $this->db->where('id', $id);
        $this->db->delete('capacitare');
    }

    public function get_from_email($email) {
        $this->db->where('email', $email);
        $query = $this->db->get('capacitare');

        if ($query->num_rows > 0) {
            return $query->result();
        }

        return NULL;
    }

    public function get_from_celular($celular) {
        $this->db->where('telefone', $celular);
        $query = $this->db->get('capacitare');

        if ($query->num_rows > 0) {
            return $query->result();
        }

        return NULL;
    }

    public function get_all_by_evento($evento_id) {
        $this->db->flush_cache();
        $this->db->distinct();
        $this->db->where('id_evento', $evento_id);
        $query = $this->db->get('capacitare');
        if ($query->num_rows > 0) {
            return $query->result();
        }

        return NULL;
    }

    public function get_eventos() {
        $this->db->flush_cache();
        $this->db->order_by('data_evento', 'desc');
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

    public function get_evento_ativo() {
        $this->db->where('ativo', 1);
        $query = $this->db->get('capacitare_evento');
        if ($query->num_rows > 0) {
            return $query->row(0);
        }

        return NULL;
    }

    public function set_evento_ativo($id_evento) {
        //Removendo os ativos
        $this->db->set('ativo', 0);
        $this->db->update('capacitare_evento');

        //Ativando o novo como ativo
        $this->db->where('id', $id_evento);
        $this->db->set('ativo', 1);
        $this->db->update('capacitare_evento');
    }

    public function insert_evento($options) {
        $this->db->insert('capacitare_evento', $options);
        return $this->db->insert_id();
    }

    public function remove_evento($id_evento) {
        $this->db->where('id', $id_evento);
        $this->db->delete('capacitare_evento');
    }

    public function insert_data($email, $telefone) {
        $evento_ativo = $this->get_evento_ativo();
        if ($evento_ativo != NULL) {
            $options = array(
                'email' => $email,
                'telefone' => $telefone,
                'id_evento' => $evento_ativo->id
            );

            $this->db->insert('capacitare', $options);
            return $this->db->insert_id();
        }

        return NULL;
    }

    public function format_data($dataString) {
        $phpdate = strtotime($dataString);
        $myFormatForView = date("d/m/Y - H:i", $phpdate);

        return $myFormatForView;
    }

    public function format_data_only($dataString) {
        $phpdate = strtotime($dataString);
        $myFormatForView = date("d/m/Y", $phpdate);

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
