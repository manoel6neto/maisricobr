<?php
class confirma_cadastro extends CI_Model{
	
	public function insere($options){
		$this->db->insert('confirma_cadastro', $options);
	}
	
	public function busca_dados_validar($email, $cpf){
		$this->db->where('email_usuario', $email);
		$this->db->where('cpf_usuario', $cpf);
		return $this->db->get('confirma_cadastro')->row(0);
	}
	
	public function atualiza_cadastro_confirmado($email, $cpf){
		$this->db->where('email_usuario', $email);
		$this->db->where('cpf_usuario', $cpf);
		$this->db->update('confirma_cadastro', array('confirmado'=>1));
	}
	
	public function verifica_cadastro_confirmado($email, $cpf){
		$this->db->where('email_usuario', $email);
		$this->db->where('cpf_usuario', $cpf);
		$query = $this->db->get('confirma_cadastro')->row(0);
		
		if(!empty($query))
			return $query->confirmado;
		else
			return true;
	}
	
	public function atualiza($options, $id){
		$this->db->where('confirma_cadastro_id', $id);
		$this->db->update('confirma_cadastro', $options);
	}
}