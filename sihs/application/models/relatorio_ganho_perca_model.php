<?php

class relatorio_ganho_perca_model extends CI_Model {

    public function get_maiores_cidades($min = NULL, $max = NULL) {
        if ($min == NULL && $max == NULL) {
            $query = $this->db->query("select distinct municipio from mv_proponente_siconv_miguel order by municipio");
        } else {
            $query = $this->db->query("select distinct municipio from mv_proponente_siconv_miguel order by municipio");
        }

        if ($query->num_rows > 0) {
            return $query->result();
        }

        return NULL;
    }

    public function get_cnpjs_por_cidade($cod_cidade) {
        $this->db->distinct();
        $this->db->select('cnpj');
        $this->db->where('municipio', $cod_cidade);
        //$this->db->where('esfera_administrativa', 'MUNICIPAL');

        $query = $this->db->get('mv_proponente_siconv_miguel');

        if ($query->num_rows > 0) {
            return $query->result();
        }

        return NULL;
    }

    public function get_propostas_por_cidade($cod_cidade, $ano) {
        $result = $this->get_cnpjs_por_cidade($cod_cidade);

        if ($result != NULL) {
            $array_propostas = array();
            foreach ($result as $cnpj) {
                $this->db->distinct();
                $this->db->select('proponente, valor_global, ano, convenio, tipo, codigo_programa');
                $this->db->where('proponente', str_replace('/', '', str_replace('-', '', str_replace('.', '', $cnpj->cnpj))));
                $this->db->where('ano', $ano);
//                $this->db->where('convenio is not null');
                //$this->db->or_where('empenhado is not null');

                $query = $this->db->get('mv_banco_proposta_miguel');

                if ($query->num_rows > 0) {
                    $resultado_cnpj = $query->result();

                    foreach ($resultado_cnpj as $proposta) {
                        array_push($array_propostas, $proposta);
                    }
                }
            }

            if (count($array_propostas) > 0) {
                return $array_propostas;
            }
        }

        return NULL;
    }

    public function get_emendas_por_cidade($cod_cidade, $ano) {
        $array_emendas = array();
        $query = $this->db->query("select distinct * from mv_siconv_beneficiario_miguel where cnpj in (select cnpj from mv_proponente_siconv_miguel where municipio = \"{$cod_cidade}\") and (YEAR(data_inicio_benef) in ({$ano}) or YEAR(data_inicio_parlam) in ({$ano}))");
//                $this->db->where("cnpj', $cnpj->cnpj);
//                $this->db->or_where('data_inicio_benef is not null');
//                $this->db->or_where('data_inicio_parlam is not null');
//                $query = $this->db->get('siconv_beneficiario');

        if ($query->num_rows > 0) {
            $resultado_cnpj = $query->result();

            return $resultado_cnpj;
        }

        return NULL;
    }

}
