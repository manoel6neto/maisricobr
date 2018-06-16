<?php

class esfadm_direito_vendedor_model extends CI_Model{
	
	public function insere($options){
		$this->db->insert_batch('esfadm_direito_vendedor', $options);
	}
	
	public function limpa_restricao($id_usuario){
		$this->db->where('id_vendedor', $id_usuario);
		$this->db->delete('esfadm_direito_vendedor');
	}
	
	public function get_lista_esferas_bloqueadas($id_usuario){
		$this->db->where('id_vendedor', $id_usuario);
		$esferas = $this->db->get('esfadm_direito_vendedor')->result();
		
		$lista_esferas_bloquear = array();
		foreach ($esferas as $e)
			$lista_esferas_bloquear[] = $e->esfera_administrativa;
		
		return $lista_esferas_bloquear;
	}
}