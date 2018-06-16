<?php

class cronograma_model extends CI_Model {

    public function insert($options) {
        $this->db->insert('cronograma', $options);
        return $this->db->insert_id();
    }

    public function get_all() {
        $query = $this->db->get('cronograma');
        if ($query->num_rows > 0) {
            return $query->result();
        } else {
            return null;
        }
    }

    public function get_by_proposta($id_proposta) {
        $this->db->where('Proposta_idProposta', $id_proposta);
        $query = $this->db->get('cronograma');

        if ($query->num_rows > 0) {
            return $query->result();
        } else {
            return null;
        }
    }

    public function get_by_proposta_parcela_mes_ano_responsavel($id_proposta, $parcela, $mes, $ano, $responsavel) {
        $this->db->where('Proposta_idProposta', $id_proposta);
        $this->db->where('parcela', $parcela);
        $this->db->where('mes', $mes);
        $this->db->where('ano', $ano);
        $this->db->where('responsavel', $responsavel);
        $query = $this->db->get('cronograma');

        if ($query->num_rows > 0) {
            return $query->result();
        } else {
            return null;
        }
    }

    public function insert_cronograma_meta($options) {
        $this->db->insert('cronograma_meta', $options);
    }

    public function insert_despesa($options) {
        if (array_key_exists('idDespesa', $options)) {
            if ($options['idDespesa'] == '') {
                unset($options['idDespesa']);
            }
        }

        $this->db->insert('despesa', $options);
    }

    public function insert_cronograma_etapa($options) {
        $this->db->insert('cronograma_etapa', $options);
    }

    public function get_id_cronogramameta_from_id_meta($id_meta) {
        $this->db->select('idCronograma_meta');
        $this->db->where('Meta_idMeta', $id_meta);

        $query = $this->db->get('cronograma_meta');

        if ($query->num_rows > 0) {
            return $query->row(0)->idCronograma_meta;
        } else {
            return null;
        }
    }

}
