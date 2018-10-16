<?php

class Programa_Model extends CI_Model {

    // -------- programa_gppi table functions -------- //
    public function get_all_programa_gppi() {
        $query = $this->db->get('programa_gppi');

        if (count($query->result()) > 0) {
            return $query->result();
        }

        return NULL;
    }

    public function get_programa_gppi_from_id($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('programa_gppi');

        if (count($query->result()) > 0) {
            return $query->result();
        }

        return NULL;
    }

    public function delete_programa_gppi($id) {
        $this->db->where('id', $id);
        $this->db->delete('programa_gppi');

        return $this->db->affected_rows();
    }

    public function update_programa_gppi($id, $options) {
        $this->db->where('id', $id);
        $this->db->update('programa_gppi', $options);

        return $this->db->affected_rows();
    }
    
    public function insert_politicas_governo($options) {
        $this->db->insert('programa_gppi', $options);
        $return_id = $this->db->insert_id();

        return $return_id;
    }

    // ---- instituicao table functions ------ //
    public function get_all_instituicao() {
        $query = $this->db->get('instituicao');

        if (count($query->result()) > 0) {
            return $query->result();
        }

        return NULL;
    }

    public function get_instituicao_from_id($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('instituicao');

        if (count($query->result()) > 0) {
            return $query->result();
        }

        return NULL;
    }

    // ------ orgao_programa table functions ---------- //
    public function get_all_orgao_programa() {
        $query = $this->db->get('orgao_programa');

        if (count($query->result()) > 0) {
            return $query->result();
        }

        return NULL;
    }

    public function get_all_orgao_programa_from_instituicao_id($id_instituicao) {
        $this->db->where('id_instituicao', $id_instituicao);
        $query = $this->db->get('orgao_programa');

        if (count($query->result()) > 0) {
            return $query->result();
        }

        return NULL;
    }

    public function get_orgao_programa_from_id_orgao_programa($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('orgao_programa');

        if (count($query->result()) > 0) {
            return $query->result();
        }

        return NULL;
    }

    // ----- acao_programa & lig_programagppi_acaoprograma table functions ------ //
    public function get_all_acao_programa_from_id_programa($id_programa_gppi) {
        $this->db->distinct();
        $this->db->select('acao_programa.*');
        $this->db->join('acao_programa', 'acao_programa.id = lig_programagppi_acaoprograma.id_acao_programa');
        $this->db->where('id_programa_gppi', $id_programa_gppi);
        $query = $this->db->get('lig_programagppi_acaoprograma');

        if (count($query->result()) > 0) {
            return $query->result();
        }

        return NULL;
    }

    public function get_acao_programa_from_id($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('acao_programa');

        if (count($query->result()) > 0) {
            return $query->result();
        }

        return NULL;
    }

    public function delete_acao_programa($id) {
        $this->db->where('id', $id);
        $this->db->delete('acao_programa');

        return $this->db->affected_rows();
    }

    // --------- contemplacao table functions --------- //
    public function get_contemplacao_from_id($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('contemplacao_programa');

        if (count($query->result()) > 0) {
            return $query->result();
        }

        return NULL;
    }

    public function update_contemplacao($id, $options) {
        $this->db->where('id', $id);
        $query = $this->db->update('contemplacao_programa', $options);

        return $this->db->affected_rows();
    }

    public function delete_contemplacao($id) {
        $this->db->where('id', $id);
        $query = $this->db->delete('contemplacao_programa');

        return $this->db->affected_rows();
    }

    // ------- arquivo_programa table functions ---------- //
    public function get_all_arquivo_programa_from_id_programa_gppi($id_programa_gppi) {
        $this->db->distinct();
        $this->db->select('arquivo_programa.*');
        $this->db->join('arquivo_programa', 'arquivo_programa.id = lig_programagppi_arquivoprograma.id_arquivo_programa');
        $this->db->where('id_programa_gppi', $id_programa_gppi);
        $query = $this->db->get('lig_programagppi_arquivoprograma');

        if (count($query->result()) > 0) {
            return $query->result();
        }

        return NULL;
    }

    public function get_arquivo_programa_from_id($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('arquivo_programa');

        if (count($query->result()) > 0) {
            return $query->result();
        }

        return NULL;
    }

    public function delete_arquivo_programa($id) {
        $this->db->where('id', $id);
        $this->db->delete('arquivo_programa');

        return $this->db->affected_rows();
    }

    // --------- sumario_programa table functions ----------- //
    public function get_sumario_programa_from_id($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('sumario_programa');

        if (count($query->result()) > 0) {
            return $query->result();
        }

        return NULL;
    }

    public function delete_sumario_programa($id) {
        $this->db->where('id', $id);
        $this->db->delete('sumario_programa');

        return $this->db->affected_rows();
    }

    // ------------ estrutura_plano_acao_programa table functions -------------- //
    public function get_all_estrutura_plano_acao_programa_from_id_programa_gppi($id_programa_gppi) {
        $this->db->distinct();
        $this->db->select('estrutura_plano_acao_programa.*');
        $this->db->join('estrutura_plano_acao_programa', 'estrutura_plano_acao_programa.id = lig_programagppi_estruturaplanoacaoprograma.id_estrutura_plano_acao_programa');
        $this->db->where('id_programa_gppi', $id_programa_gppi);
        $query = $this->db->get('lig_programagppi_estruturaplanoacaoprograma');

        if (count($query->result()) > 0) {
            return $query->result();
        }

        return NULL;
    }

    public function get_estrutura_plano_acao_programa_from_id($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('estrutura_plano_acao_programa');

        if (count($query->result()) > 0) {
            return $query->result();
        }

        return NULL;
    }

    public function delete_estrutura_plano_acao_programa($id) {
        $this->db->where('id', $id);
        $this->db->delete('estrutura_plano_acao_programa');

        return $this->db->affected_rows();
    }

}
