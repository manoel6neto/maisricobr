<?php

class compram extends CI_Model {

    public function get_emendas_from_cnpj($cnpj) {
        $this->db->where("REPLACE(REPLACE(REPLACE(cnpj, '/', ''), '-', ''), '.', '') = $cnpj");
        $this->db->join('programas_vigencia', 'programas_vigencia.codigo = siconv_beneficiario.codigo_programa');
        $query = $this->db->get('siconv_beneficiario');

        if ($query->num_rows > 0) {
            return $query->result();
        }

        return null;
    }

    public function get_programas_from_estado($estado) {
        $this->db->where("qualificacao like '%Proposta Volunt%' and (estados = 'Todos os Estados estão Aptos' or estados like '%{$estado}%')");
        $query = $this->db->get('programas_vigencia');

        if ($query->num_rows > 0) {
            return $query->result();
        }

        return null;
    }

    public function get_programas_from_cnpj($cnpj) {
        $this->db->where("REPLACE(REPLACE(REPLACE(cnpj, '/', ''), '-', ''), '.', '') = $cnpj");
        $query_proponente = $this->db->get('proponente_siconv');

        if ($query_proponente->num_rows > 0) {
            $proponente = $query_proponente->row(0);

            $filtro_atende = "";
            if ($proponente->esfera_administrativa == "MUNICIPAL") {
                $filtro_atende = "Municipal";
            } elseif ($proponente->esfera_administrativa == "ESTADUAL") {
                $filtro_atende = "Estadual ou do Distrito Federal";
            } elseif ($proponente->esfera_administrativa == "PRIVADA") {
                $filtro_atende = "Privada";
            } elseif ($proponente->esfera_administrativa == "FEDERAL") {
                $filtro_atende = "Federal";
            } elseif ($proponente->esfera_administrativa == "ORGANISMO INTERNACIONAL") {
                $filtro_atende = "Internacional";
            } elseif ($proponente->esfera_administrativa == "CONSORCIO PUBLICO") {
                $filtro_atende = "Consórcio Público";
            } elseif ($proponente->esfera_administrativa == "EMPRESA PUBLICA SOCIEDADE ECONOMIA MISTA") {
                $filtro_atende = "Sociedade de economia mista";
            }

            $this->db->where("qualificacao like '%Proposta Volunt%' and atende like '%{$filtro_atende}%' and (estados = 'Todos os Estados estão Aptos' or estados like '%{$proponente->municipio_uf_sigla}%')");
            $query_programa = $this->db->get('programas_vigencia');

            if ($query_programa->num_rows > 0) {
                return $query_programa->result();
            }
        }

        return null;
    }

    public function insert_dados_info_compra_avulso($cnpj, $estado, $email, $resultado) {
        $array_insert_dados_info = array(
            'cnpj' => $cnpj,
            'estado' => $estado,
            'email' => $email,
            'resultado' => $resultado,
            'tipo_consulta' => $cnpj != null ? 0 : 1
        );

        $this->db->insert('dados_info_consulta_avulsa', $array_insert_dados_info);
        return $this->db->insert_id();
    }

    public function insert_pagseguro_dados_info_compra_avulso($id_dados_info_compra_avulso, $codigo_ref_compra, $data) {
        $array_pagseguro_dados_info = array(
            'id_dados_info_consulta_avulsa' => $id_dados_info_compra_avulso,
            'codigo_ref_compra' => $codigo_ref_compra,
            'data_compra' => $data
        );

        $this->db->insert('pagseguro_dados_info_consulta_avulsa', $array_pagseguro_dados_info);
        return $this->db->insert_id();
    }

    public function update_data_pagamento($id_pagseguro_dados_info_consulta_avulsa) {
        $array_update_data = array(
            'data_pagamento' => date("Y-m-d")
        );

        $this->db->where('id', $id_pagseguro_dados_info_consulta_avulsa);
        $this->db->update('pagseguro_dados_info_consulta_avulsa', $array_update_data);
    }

    public function get_pagseguro_dados_info_consulta_avulsa_from_reference($reference) {
        $this->db->where('codigo_ref_compra', $reference);
        $query = $this->db->get('pagseguro_dados_info_consulta_avulsa');

        if ($query->num_rows > 0) {
            return $query->row(0);
        }

        return null;
    }

    public function get_dados_info_consulta_avulsa_from_id($id_dados_info_consulta_avulsa) {
        $this->db->where('id', $id_dados_info_consulta_avulsa);
        $query = $this->db->get('dados_info_consulta_avulsa');

        if ($query->num_rows > 0) {
            return $query->row(0);
        }

        return null;
    }

    public function delete_dados_info_avulsa_from_id($id_dados_info_consulta_avulsa) {
        $this->db->where('id', $id_dados_info_consulta_avulsa);
        $this->db->delete('dados_info_consulta_avulsa');
    }

    public function get_nome_from_propoenente($cnpj) {
        $this->db->select('nome');
        $this->db->where("REPLACE(REPLACE(REPLACE(cnpj, '/', ''), '-', ''), '.', '') = $cnpj");
        $query = $this->db->get('proponente_siconv');

        if ($query->num_rows > 0) {
            return $query->row(0);
        }

        return null;
    }
    
    public function test_cnpj($cnpj) {
        $this->db->where("REPLACE(REPLACE(REPLACE(cnpj, '/', ''), '-', ''), '.', '') = $cnpj");
        $query = $this->db->get('proponente_siconv');
        
        if ($query->num_rows > 0) {
            return true;
        }
        
        return false;
    }
    
    public function insert_cnpj_consulta_temporaria($cnpj, $tipo_consulta) {
        $array_insert = array (
            'cnpj' => $cnpj,
            'tipo_consulta' => $tipo_consulta,
            'data_consulta' => date("Y-m-d")
        );
        
        $this->db->insert('dados_info_consulta_temporarios', $array_insert);
    }

}
