<?php

class municipios_direito_gestor_execucao_model extends CI_Model {

    public function insere($options) {
        $this->db->insert_batch('municipios_direito_gestor_execucao', $options);
    }

    public function limpa_restricao($id_usuario) {
        $this->db->where('id_gestor_execucao', $id_usuario);
        $this->db->delete('municipios_direito_gestor_execucao');
    }

    public function get_lista_cidades_bloqueadas($id_gestor_execucao) {
        $this->db->distinct("mv.municipio");
        $this->db->select("mv.municipio as codigo, ps.municipio as municipio");
        $this->db->join("proponente_siconv ps", "mv.municipio = ps.codigo_municipio", "inner");
        $this->db->where('id_gestor_execucao', $id_gestor_execucao);
        $municipio = $this->db->get('municipios_direito_gestor_execucao mv')->result();

        $lista_municipios_bloquear = array();
        foreach ($municipio as $e)
            $lista_municipios_bloquear[$e->codigo] = $e->municipio;

        return $lista_municipios_bloquear;
    }

}
