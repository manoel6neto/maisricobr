<?php

class apresentacao_model extends CI_Model {

    public function get_desempenho_centrooeste_municipal() {
        $query = $this->db->get('apresentacao_desempenho_centrooeste_municipal');

        if ($query->num_rows > 0) {
            return $query->row(0);
        }
    }

    public function get_desempenho_centrooeste_osc() {
        $query = $this->db->get('apresentacao_desempenho_centrooeste_osc');

        if ($query->num_rows > 0) {
            return $query->row(0);
        }
    }

    public function get_desempenho_centrooeste_estadual() {
        $query = $this->db->get('apresentacao_desempenho_centrooeste_estadual');

        if ($query->num_rows > 0) {
            return $query->row(0);
        }
    }

    public function get_desempenho_centrooeste_mista() {
        $query = $this->db->get('apresentacao_desempenho_centrooeste_mista');

        if ($query->num_rows > 0) {
            return $query->row(0);
        }
    }

    public function get_desempenho_centrooeste_consorcio() {
        $query = $this->db->get('apresentacao_desempenho_centrooeste_consorcio');

        if ($query->num_rows > 0) {
            return $query->row(0);
        }
    }

    public function get_desempenho_norte_municipal() {
        $query = $this->db->get('apresentacao_desempenho_norte_municipal');

        if ($query->num_rows > 0) {
            return $query->row(0);
        }
    }

    public function get_desempenho_norte_osc() {
        $query = $this->db->get('apresentacao_desempenho_norte_osc');

        if ($query->num_rows > 0) {
            return $query->row(0);
        }
    }

    public function get_desempenho_norte_estadual() {
        $query = $this->db->get('apresentacao_desempenho_norte_estadual');

        if ($query->num_rows > 0) {
            return $query->row(0);
        }
    }

    public function get_desempenho_norte_mista() {
        $query = $this->db->get('apresentacao_desempenho_norte_mista');

        if ($query->num_rows > 0) {
            return $query->row(0);
        }
    }

    public function get_desempenho_norte_consorcio() {
        $query = $this->db->get('apresentacao_desempenho_norte_consorcio');

        if ($query->num_rows > 0) {
            return $query->row(0);
        }
    }

    public function get_desempenho_nordeste_municipal() {
        $query = $this->db->get('apresentacao_desempenho_nordeste_municipal');

        if ($query->num_rows > 0) {
            return $query->row(0);
        }
    }

    public function get_desempenho_nordeste_osc() {
        $query = $this->db->get('apresentacao_desempenho_nordeste_osc');

        if ($query->num_rows > 0) {
            return $query->row(0);
        }
    }

    public function get_desempenho_nordeste_estadual() {
        $query = $this->db->get('apresentacao_desempenho_nordeste_estadual');

        if ($query->num_rows > 0) {
            return $query->row(0);
        }
    }

    public function get_desempenho_nordeste_mista() {
        $query = $this->db->get('apresentacao_desempenho_nordeste_mista');

        if ($query->num_rows > 0) {
            return $query->row(0);
        }
    }

    public function get_desempenho_nordeste_consorcio() {
        $query = $this->db->get('apresentacao_desempenho_nordeste_consorcio');

        if ($query->num_rows > 0) {
            return $query->row(0);
        }
    }

    public function get_desempenho_sul_municipal() {
        $query = $this->db->get('apresentacao_desempenho_sul_municipal');

        if ($query->num_rows > 0) {
            return $query->row(0);
        }
    }

    public function get_desempenho_sul_osc() {
        $query = $this->db->get('apresentacao_desempenho_sul_osc');

        if ($query->num_rows > 0) {
            return $query->row(0);
        }
    }

    public function get_desempenho_sul_estadual() {
        $query = $this->db->get('apresentacao_desempenho_sul_estadual');

        if ($query->num_rows > 0) {
            return $query->row(0);
        }
    }

    public function get_desempenho_sul_mista() {
        $query = $this->db->get('apresentacao_desempenho_sul_mista');

        if ($query->num_rows > 0) {
            return $query->row(0);
        }
    }

    public function get_desempenho_sul_consorcio() {
        $query = $this->db->get('apresentacao_desempenho_sul_consorcio');

        if ($query->num_rows > 0) {
            return $query->row(0);
        }
    }

    public function get_desempenho_sudeste_municipal() {
        $query = $this->db->get('apresentacao_desempenho_sudeste_municipal');

        if ($query->num_rows > 0) {
            return $query->row(0);
        }
    }

    public function get_desempenho_sudeste_osc() {
        $query = $this->db->get('apresentacao_desempenho_sudeste_osc');

        if ($query->num_rows > 0) {
            return $query->row(0);
        }
    }

    public function get_desempenho_sudeste_estadual() {
        $query = $this->db->get('apresentacao_desempenho_sudeste_estadual');

        if ($query->num_rows > 0) {
            return $query->row(0);
        }
    }

    public function get_desempenho_sudeste_mista() {
        $query = $this->db->get('apresentacao_desempenho_sudeste_mista');

        if ($query->num_rows > 0) {
            return $query->row(0);
        }
    }

    public function get_desempenho_sudeste_consorcio() {
        $query = $this->db->get('apresentacao_desempenho_sudeste_consorcio');

        if ($query->num_rows > 0) {
            return $query->row(0);
        }
    }

    public function get_desempenho_nacional_municipal() {
        $query = $this->db->get('apresentacao_desempenho_nacional_municipal');

        if ($query->num_rows > 0) {
            return $query->row(0);
        }
    }

    public function get_desempenho_nacional_osc() {
        $query = $this->db->get('apresentacao_desempenho_nacional_osc');

        if ($query->num_rows > 0) {
            return $query->row(0);
        }
    }

    public function get_desempenho_nacional_estadual() {
        $query = $this->db->get('apresentacao_desempenho_nacional_estadual');

        if ($query->num_rows > 0) {
            return $query->row(0);
        }
    }

    public function get_desempenho_nacional_mista() {
        $query = $this->db->get('apresentacao_desempenho_nacional_mista');

        if ($query->num_rows > 0) {
            return $query->row(0);
        }
    }

    public function get_desempenho_nacional_consorcio() {
        $query = $this->db->get('apresentacao_desempenho_nacional_consorcio');

        if ($query->num_rows > 0) {
            return $query->row(0);
        }
    }

    public function get_programas_from_area($esfera) {
        ini_set("max_execution_time", 0);
        ini_set("memory_limit", "-1");
        header('Content-Type: text/html; charset=utf-8');
        $this->load->model('programa_model');

        $data = array();
        $data['apresentacao'] = true;
        $data['qualificacao'] = array('Proposta Voluntária');
        $data['pesquisa'] = '';
        $data['orgao'] = '';
        $data['data_inicio'] = '01/01/2016';
        $data['data_fim'] = '31/12/2016';
        /*
         * A esfera vem como uma string mas 'programa_model->busca_programa' trata como um array por conta da vinculação de CNPJ que permite a seleção de mais de uma esfera.
         * Por isso é preciso enviar um array no campo 'atende'.
         */

        $data['atende'] = array($esfera);

        $result = $this->programa_model->busca_programa($data, 0, 1000, false);
        $result = $result['lista'];

        $array_ministerios_programas = array();
        foreach ($result as $programa) {
            if (!array_key_exists($programa->orgao, $array_ministerios_programas)) {
                $array_ministerios_programas[$programa->orgao] = array();
                array_push($array_ministerios_programas[$programa->orgao], $programa);
            } else {
                array_push($array_ministerios_programas[$programa->orgao], $programa);
            }
        }

        return $array_ministerios_programas;
    }

    public function get_desempenho_estadual($uf, $esfera) {
        $this->db->where('id_esfera_administrativa', $esfera);
        $query = $this->db->get('apresentacao_desempenho_' . $uf);

        if ($query->num_rows > 0) {
            return $query->row(0);
        }
    }

}
