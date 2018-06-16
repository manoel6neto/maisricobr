<?php

class municipios_direito_vendedor_model extends CI_Model {

    public function insere($options) {
        $this->db->insert_batch('municipios_direito_vendedor', $options);
    }

    public function limpa_restricao($id_usuario) {
        $this->db->where('id_vendedor', $id_usuario);
        $this->db->delete('municipios_direito_vendedor');
    }

    public function get_lista_cidades_bloqueadas($id_vendedor) {
        $this->db->distinct("mv.municipio");
        $this->db->select("mv.municipio as codigo, ps.municipio as municipio");
        $this->db->join("proponente_siconv ps", "mv.municipio = ps.codigo_municipio", "inner");
        $this->db->where('id_vendedor', $id_vendedor);
        $municipio = $this->db->get('municipios_direito_vendedor mv')->result();

        $lista_municipios_bloquear = array();
        foreach ($municipio as $e)
            $lista_municipios_bloquear[$e->codigo] = $e->municipio;

        return $lista_municipios_bloquear;
    }

}
