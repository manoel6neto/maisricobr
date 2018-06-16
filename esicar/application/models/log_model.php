<?php

class log_model extends CI_Model {

    public function log_erro($local, $linha, $erro) {
        $options = array(
            'local' => $local,
            'linha' => $linha,
            'erro' => $erro
        );

        $this->db->insert('log_erro', $options);
    }

    public function get_log_erro() {
        $query = $this->db->get('log_erro');
        if ($query->num_rows > 0) {
            return $query->result();
        }

        return NULL;
    }

    public function log_atualizacao_programa($id_programa, $codigo_programa, $status) {
        $this->db->set('id_programa', $id_programa);
        $this->db->set('codigo_programa', $codigo_programa);
        $this->db->set('status', $status);

        $this->db->insert('log_atualizacao_programas');
    }

    public function get_log_atualizacao_programa() {
        $query = $this->db->get('log_atualizacao_programas');

        return $query->result();
    }

    public function log_atualizacao_beneficiario($id_programa, $dados, $status) {
        foreach ($dados as $value) {
            $this->db->set('id_programa', $id_programa);
            $this->db->set('codigo_programa', $value['codigo_programa']);
            $this->db->set('cnpj', $value['cnpj']);
            $this->db->set('status', $status);

            $this->db->insert('log_atualizacao_beneficiario');
        }
    }

    public function get_log_atualizacao_beneficiario() {
        $query = $this->db->get('log_atualizacao_beneficiario');

        return $query->result();
    }

}

?>