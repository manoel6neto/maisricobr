<?php

class estados extends CI_Model {

    function get_all() {
        $query = $this->db->get('estados');
        return $query->result();
    }

    function get_by_id($id_estado) {
        $this->db->where('cod_estados', $id_estado);
        $query = $this->db->get('estados');
        return $query->row(0);
    }

    function get_by_sigla($sigla) {
        $this->db->select('cod_estados');
        $this->db->where('sigla', $sigla);
        $query = $this->db->get('estados');

        if ($query->num_rows > 0) {
            return $query->row(0)->cod_estados;
        } else {
            return null;
        }
    }

    function get_by_sigla_full($sigla) {
        $this->db->select('*');
        $this->db->where('sigla', $sigla);
        $query = $this->db->get('estados');

        if ($query->num_rows > 0) {
            return $query->row(0);
        } else {
            return null;
        }
    }

    function get_estados_by_regiao($regiao_id) {
        $this->db->select("nome, sigla");
        if ($regiao_id != NULL)
            $this->db->where("regiao_id", $regiao_id);
        $query = $this->db->get("tb_estados");

        if ($query->num_rows > 0) {
            return $query->result_array();
        } else {
            return null;
        }
    }

    function get_siglas_by_regiao($regiao_id) {
        $this->db->select("sigla");
        if ($regiao_id != NULL)
            $this->db->where("regiao_id", $regiao_id);
        $query = $this->db->get("tb_estados");

        if ($query->num_rows > 0) {
            foreach ($query->result() as $value) {
                $aux_result[] = $value->sigla;
            }
            return $aux_result;
        } else {
            return null;
        }
    }

    public function get_lista_estados_permitidos() {
        if ($this->session->userdata('nivel') == 4 || $this->session->userdata('nivel') == 15) {
            $this->load->model('estados_direito_vendedor_model');
            return $this->estados_direito_vendedor_model->get_lista_estados_bloqueados($this->session->userdata('id_usuario'));
        }

        if ($this->session->userdata('nivel') == 2) {
            $this->load->model('usuariomodel');
            return $this->usuariomodel->get_estados_by_usuario($this->session->userdata('id_usuario'));
        }

        return NULL;
    }

    public function get_lista_estados_permitidos_relatorio() {
        if ($this->session->userdata('nivel') == 4 || $this->session->userdata('nivel') == 15) {
            $this->load->model('estados_direito_vendedor_model');
            return $this->estados_direito_vendedor_model->get_lista_estados_bloqueados($this->session->userdata('id_usuario'));
        }

        if ($this->session->userdata('nivel') == 2 || $this->session->userdata('nivel') == 3) {
            $this->load->model('usuariomodel');
            $estados = $this->usuariomodel->get_estados_by_usuario($this->session->userdata('id_usuario'));
            foreach ($estados as $estado) {
                $result[] = $estado->sigla;
            }
            return $result;
        }

        if ($this->session->userdata('nivel') == 1 || $this->session->userdata('nivel') == 12) {
            $estados = $this->get_all();
            foreach ($estados as $estado) {
                $result[] = $estado->sigla;
            }
            return $result;
        }

        if ($this->session->userdata('nivel') == 13 || $this->session->userdata('nivel') == 14) {
            $this->load->model('estados_direito_gestor_execucao_model');
            return $this->estados_direito_gestor_execucao_model->get_lista_estados_bloqueados($this->session->userdata('id_usuario'));
        }
    }

}
