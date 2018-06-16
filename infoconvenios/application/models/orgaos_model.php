<?php

class orgaos_model extends CI_Model{
	
	function get_all_orgaos(){
		$query = $this->db->get('teste_orgaos');
		return $query->result();
	}
		function insere_orgaos($matches1, $matches2 , $matches3 ){
		$data= array(
				'id_orgaos_siconv' => $matches1,
				'nome_orgao' => $matches3,
				'cod' => $matches2
			);
		$this->db->insert('teste_orgaos',$data);
		return $this->db->affected_rows();
	}
        
	function apagar_orgaos(){
		if($this->db->truncate('teste_orgaos'))
		 return 1;
		 else return 0;
	}
}