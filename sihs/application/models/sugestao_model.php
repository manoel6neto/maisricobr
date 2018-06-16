<?php

class sugestao_model extends CI_Model{
	
	public function insere($options){
		$this->db->insert('sugestao', $options);
	}
	
	public function get_all(){
		if($this->session->userdata('nivel') != 1)
			$this->db->where('id_usuario', $this->session->userdata('id_usuario'));
		
		$this->db->order_by('data_envio', 'DESC');
		return $this->db->get('sugestao')->result();
	}
	
	public function get_by_id($id){
		$this->db->where('id_sugestao', $id);
		return $this->db->get('sugestao')->row(0);
	}
	
	function envia_email($email, $nome, $texto, $assunto = "Nova Sugestão e-SICAR", $para = "eliumar@physisbrasil.com.br") {
		$this->load->model('usuariomodel');
		 
		$this->load->library('email');
	
		$this->email->initialize($this->usuariomodel->inicializa_config_email("contato@physisbrasil.com.br"));
		$this->email->from('contato@physisbrasil.com.br', 'Captação Recursos SIHS');
		$this->email->to($para);
		
		$texto .= "<br><br>".$nome."<br>".$email;
	
		$this->email->subject($assunto);
		$this->email->message("<html>" . $texto . "</html>");
		$this->email->send();
	}
	
	public function insere_resposta($options){
		$this->db->insert('resposta_sugestao', $options);
	}
	
	public function checa_tem_resposta($id){
		$this->db->where('id_sugestao', $id);
		$query = $this->db->get('resposta_sugestao');
		
		return $query->num_rows();
	}
	
	public function get_resposta_by_id($id){
		$this->db->where('id_sugestao', $id);
		return $this->db->get('resposta_sugestao')->row(0);
	}
}