<?php

class get_programas_model extends CI_Model {

    public function insert_or_update($options) {
        $this->db->where('codigo', $options['codigo']);
        $query = $this->db->get('siconv_programa');
        if (count($query->result()) > 0) {

            $temAtualizacao = false;
            if ($options['data_fim'] != $query->row(0)->data_fim) {
                $temAtualizacao = true;
                $options['excluido'] = NULL;
            } else if ($options['data_fim_benef'] != $query->row(0)->data_fim_benef) {
                $temAtualizacao = true;
                $options['excluido'] = NULL;
            } else if ($options['data_fim_parlam'] != $query->row(0)->data_fim_parlam) {
                $temAtualizacao = true;
                $options['excluido'] = NULL;
            } else if ($options['data_disp'] != $query->row(0)->data_disp) {
                $temAtualizacao = true;
                $options['excluido'] = NULL;
            }

            $options['programa_novo'] = false;
            $options['tem_atualizacao'] = $temAtualizacao;

            $this->db->where('codigo', $options['codigo']);
            $this->db->update('siconv_programa', $options);
        } else {
            $options['programa_novo'] = true;
            $options['tem_atualizacao'] = false;
            $this->db->insert('siconv_programa', $options);
        }
    }

    public function insert_or_update_benef($options, $ehParlamentar = false) {
        foreach ($options as $op) {
            $this->db->where('codigo_programa', $op['codigo_programa']);
            $this->db->where('cnpj', $op['cnpj']);

            if (!$ehParlamentar) {
                $this->db->where('data_inicio_parlam is null', null);
                $this->db->where('emenda', '');
            } else {
                $this->db->where('emenda', $op['emenda']);
                $this->db->where('data_inicio_benef is null', null);
            }

            $query = $this->db->get('siconv_beneficiario');

            if (count($query->result()) > 0) {
                $this->db->where('codigo_programa', $op['codigo_programa']);
                $this->db->where('cnpj', $op['cnpj']);

                if (!$ehParlamentar) {
                    $this->db->where('data_inicio_parlam is null', null);
                    $this->db->where('emenda', '');
                } else {
                    $this->db->where('emenda', $op['emenda']);
                    $this->db->where('data_inicio_benef is null', null);
                }

                $this->db->update('siconv_beneficiario', $op);
            } else {
                $this->db->insert('siconv_beneficiario', $op);
            }
        }
    }

}
