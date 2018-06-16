<?php

class programa_proposta_model extends CI_Model {

    public function insere_programa($options) {
        $array_emendas = array();
        if (array_key_exists('valores_emendas', $options)) {
            $array_emendas = $options['valores_emendas'];
            unset($options['valores_emendas']);
        }

        if (array_key_exists('contrapartida_bens', $options)) {
            if ($options['contrapartida_bens'] == '') {
                unset($options['contrapartida_bens']);
            }
        }

        foreach ($options as $prog) {
            $this->db->insert('programa_proposta', $prog);
            $id = $this->db->insert_id();

            foreach ($array_emendas as $emenda) {
                if ($emenda['numero_programa'] == $prog['codigo_programa']) {
                    $array_emenda_programa_proposta = array(
                        'id_programa_proposta' => $id,
                        'valor_utilizado' => $emenda['valor_utilizado'],
                        'numero_emenda' => $emenda['numero_emenda']
                    );

                    $this->db->insert('emenda_programa_proposta', $array_emenda_programa_proposta);
                }
            }
        }
    }

    public function insert_programa_proposta($options) {
        if ($options['contrapartida_bens'] == '') {
            unset($options['contrapartida_bens']);
        }

        $this->db->insert('programa_proposta', $options);
        return $this->db->insert_id();
    }

    public function get_id_programa_from_nome_proposta($nome, $id_proposta) {
        $this->db->select('id_programa');
        $this->db->where('nome_programa', $nome);
        $this->db->where('id_proposta', $id_proposta);
        $query = $this->db->get('programa_proposta');

        if ($query->num_rows > 0) {
            return $query->row(0)->id_programa;
        } else {
            return null;
        }
    }

    public function get_programas_by_proposta($id) {
        $this->db->where('id_proposta', $id);
        return $this->db->get('programa_proposta')->result();
    }
    
    public function get_programas_by_proposta_programa($id, $codigo_programa) {
        $this->db->where('id_proposta', $id);
        $this->db->where('codigo_programa', $codigo_programa);
        return $this->db->get('programa_proposta')->result();
    }

    public function get_programas_by_proposta_padrao($id) {
        $this->db->where('id_proposta', $id);
        return $this->db->get('programa_proposta')->row(0);
    }

    public function get_programa_by_proposta_array($id) {
        $this->db->where('id_proposta', $id);
        return $this->db->get('programa_proposta')->result_array();
    }

    public function get_num_programas_by_proposta($id) {
        $this->db->where('id_proposta', $id);
        return $this->db->get('programa_proposta')->num_rows();
    }

    public function get_by_proposta_programa($idProposta, $idPrograma) {
        $this->db->where('id_proposta', $idProposta);
        $this->db->where('id_programa', $idPrograma);
        return $this->db->get('programa_proposta')->row(0);
    }

    public function get_valores_programas_meta($id) {
        $this->db->where('id_proposta', $id);
        $programas = $this->db->get('programa_proposta')->result();

        $this->db->flush_cache();

        $lista_valores = array();

        foreach ($programas as $p) {
            $this->db->select('SUM(total) as TOTAL', FALSE);
            $this->db->where('Proposta_idProposta', $id);
            $this->db->where('id_programa', $p->id_programa);
            $meta = $this->db->get('meta')->row(0);

            $this->db->flush_cache();

            $lista_valores[$p->id_programa] = $p->valor_global - $meta->TOTAL;
        }

        return $lista_valores;
    }

    public function get_valores_programas_despesa($id) {
        $this->db->where('id_proposta', $id);
        $programas = $this->db->get('programa_proposta')->result();

        $this->db->flush_cache();

        $lista_valores = array();

        foreach ($programas as $p) {
            $this->db->select('SUM(total) as TOTAL', FALSE);
            $this->db->where('Proposta_idProposta', $id);
            $this->db->where('id_programa', $p->id_programa);
            $despesa = $this->db->get('despesa')->row(0);

            $this->db->flush_cache();

            $lista_valores[$p->id_programa] = $p->valor_global - $despesa->TOTAL;
        }

        return $lista_valores;
    }

    public function update_or_delete_programa($id, $options) {
        $this->db->select('id_programa_proposta');
        $this->db->where('id_proposta', $id);
        $lista_ids = $this->db->get('programa_proposta')->result();
        $this->db->flush_cache();

        $lista_ids_old = array();
        foreach ($lista_ids as $l)
            $lista_ids_old[] = $l->id_programa_proposta;

        $lista_ids_atual = array();
        $lista_ids_programa = array();
        foreach ($options as $o) {
            $lista_ids_atual[] = $o['id_programa_proposta'];
            $lista_ids_programa[] = $o['id_programa'];
        }

        $lista_ids_deletar = array_diff($lista_ids_old, $lista_ids_atual);
        if (count($lista_ids_deletar) > 0) {
            $this->db->where_in('id_programa_proposta', $lista_ids_deletar)->delete('programa_proposta');
            $this->db->where('Proposta_idProposta', $id)->where_not_in('id_programa', $lista_ids_programa)->update('meta', array('id_programa' => NULL));
            $this->db->where('Proposta_idProposta', $id)->where_not_in('id_programa', $lista_ids_programa)->update('despesa', array('id_programa' => NULL));
        }

        $this->db->flush_cache();

        foreach ($options as $valores) {
            if (in_array($valores['id_programa_proposta'], $lista_ids_old)) {
                $this->db->where('id_programa_proposta', $valores['id_programa_proposta']);
                $this->db->update('programa_proposta', $valores);
            } else if ($valores['id_programa_proposta'] == "") {
                unset($valores['id_programa_proposta']);
                $valores['id_proposta'] = $id;
                $this->db->insert('programa_proposta', $valores);
            }
        }
    }

    public function get_nome_programa($id_proposta, $id_programa) {
        $this->db->where('id_proposta', $id_proposta);
        $this->db->where('id_programa', $id_programa);
        return $this->db->get('programa_proposta')->row(0);
    }

}
