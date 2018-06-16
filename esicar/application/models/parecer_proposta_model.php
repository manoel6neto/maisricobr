<?php

class parecer_proposta_model extends CI_Model{
	
	public function atualiza_parecer($options){
		$this->db->where('id_proposta', $options['id_proposta']);
		$this->db->where('id_parecer', $options['id_parecer']);
		$this->db->where('data_parecer', $options['data_parecer']);
		$pareceres = $this->db->get('parecer_proposta');
		
		if(count($pareceres->result()) > 0){
			$dados = $pareceres->row(0);
			$this->db->where('id_parecer_proposta', $dados->id_parecer_proposta);
			$this->db->update('parecer_proposta', $options);
		}else
			$this->db->insert('parecer_proposta', $options);
	}
	
	public function get_by_id($idProposta, $idParecer){
		$this->db->where('id_proposta', $idProposta);
		$this->db->where('id_parecer', $idParecer);
		return $this->db->get('parecer_proposta')->row(0);
	}
	
	public function get_data_ultimo_parecer($idProposta){
		$this->db->where('id_proposta', $idProposta);
		$this->db->order_by('id_parecer_proposta', 'desc');
		if(count($this->db->get('parecer_proposta')->result()) > 0)
			$data_parecer = $this->db->get('parecer_proposta')->row(0)->data_parecer;
		else
			$data_parecer = null;
		
		return $data_parecer;
	}
}