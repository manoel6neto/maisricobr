<?php

class estados_direito_vendedor_model extends CI_Model{
	
	public function insere($options){
		$this->db->insert_batch('estados_direito_vendedor', $options);
	}
	
	public function limpa_restricao($id_usuario){
		$this->db->where('id_vendedor', $id_usuario);
		$this->db->delete('estados_direito_vendedor');
	}
	
	public function get_lista_estados_bloqueados($id_vendedor){
		$this->db->where('id_vendedor', $id_vendedor);
		$estados = $this->db->get('estados_direito_vendedor')->result();
		
		$lista_estados_bloquear = array();
		foreach ($estados as $e)
			$lista_estados_bloquear[] = $e->estado_sigla;
		
		return $lista_estados_bloquear;
	}
}