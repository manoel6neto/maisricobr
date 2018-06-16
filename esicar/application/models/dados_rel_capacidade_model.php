<?php

class dados_rel_capacidade_model extends CI_Model{
	
	public function get_all_municipios(){
		$this->load->model('usuariomodel');
		$this->load->model('proponente_siconv_model');
		
		$cnpjs = $this->usuariomodel->get_cnpjs_by_usuario($this->session->userdata('id_usuario'));
		
		$lista_cidades = array();
		foreach ($cnpjs as $cnpj)
			$lista_cidades[$cnpj->id_cidade] = $this->proponente_siconv_model->get_municipio_nome($cnpj->id_cidade)->municipio;
		
		return $lista_cidades;
	}
	
	public function insere_dados_rel($options){
		$this->db->insert('dados_rel_capacidade', $options);
	}
	
	public function get_all_by_usuario(){
		if($this->session->userdata('nivel') != 2)
			$this->db->where('id_usuario', $this->session->userdata('id_usuario'));
		else if($this->session->userdata('nivel') == 2){
			$this->load->model('usuariomodel');
			$id_gestor = $this->usuariomodel->get_id_gestor($this->session->userdata('id_usuario'));
			$usuarios_gestor = $this->usuariomodel->get_ids_grupo($id_gestor);
			$ids = array();
			foreach ($usuarios_gestor as $usuario_gestor) {
				array_push($ids, $usuario_gestor->id_usuario);
			}
			array_push($ids, $this->usuariomodel->get_gestor_user_id_by_gestor_id($id_gestor));
			$this->db->or_where_in('id_usuario', $ids);
		}
		
		return $this->db->get('dados_rel_capacidade')->result();
	}
	
	public function get_by_id($id){
		$this->db->where('id_rel', $id);
		return $this->db->get('dados_rel_capacidade')->row(0);
	}
	
	public function get_nome_mes($mes){
		switch ($mes){
			case "01":
				return "Janeiro";
				break;
			case "02":
				return "Fevereiro";
				break;
			case "03":
				return "Março";
				break;
			case "04":
				return "Abril";
				break;
			case "05":
				return "Maio";
				break;
			case "06":
				return "Junho";
				break;
			case "07":
				return "Julho";
				break;
			case "08":
				return "Agosto";
				break;
			case "09":
				return "Setembro";
				break;
			case "10":
				return "Outubro";
				break;
			case "11":
				return "Novembro";
				break;
			case "12":
				return "Dezembro";
				break;
		}
	}
}