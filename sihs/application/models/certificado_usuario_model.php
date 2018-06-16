<?php

class certificado_usuario_model extends CI_Model{
	
	public function insere($options){
		$this->db->insert('certificado_usuario', $options);
	}
	
	public function checa_cpf($cpf){
		$this->db->where('login', $cpf);
		$this->db->where_in('id_nivel', array(2,3,5));
		$query = $this->db->get('usuario');
		
		return $query->num_rows > 0;
	}
	
	public function check_tem_certificado(){
		$this->db->where('id_usuario', $this->session->userdata('id_usuario'));
		return $this->db->get('certificado_usuario')->num_rows > 0;
	}
	
	public function get_by_usuario($id_usuario){
		$this->db->where('id_usuario', $id_usuario);
		return $this->db->get('certificado_usuario')->row(0);
	}
	
	public function get_all_certificados(){
		$this->db->distinct();
		$this->db->select('u.nome, ps.municipio AS municipio_nome, ps.municipio_uf_sigla, c.*');
		$this->db->join('usuario u', 'c.id_usuario = u.id_usuario');
		$this->db->join('proponente_siconv ps', 'ps.codigo_municipio = c.municipio AND ps.municipio_uf_sigla = c.uf');
		
		$this->db->order_by('c.uf, municipio_nome, nome');
		
		$query = $this->db->get('certificado_usuario c');
		
		return $query->result();
	}
}