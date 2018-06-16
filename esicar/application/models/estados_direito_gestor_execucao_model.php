<?php

class estados_direito_gestor_execucao_model extends CI_Model{
	
	public function insere($options){
		$this->db->insert_batch('estados_direito_gestor_execucao', $options);
	}
	
	public function limpa_restricao($id_gestor_execucao){
		$this->db->where('id_gestor_execucao', $id_gestor_execucao);
		$this->db->delete('estados_direito_gestor_execucao');
	}
	
	public function get_lista_estados_bloqueados($id_gestor_execucao){
		$this->db->where('id_gestor_execucao', $id_gestor_execucao);
		$estados = $this->db->get('estados_direito_gestor_execucao')->result();
		
		$lista_estados_bloquear = array();
		foreach ($estados as $e)
			$lista_estados_bloquear[] = $e->estado_sigla;
		
		return $lista_estados_bloquear;
	}
}