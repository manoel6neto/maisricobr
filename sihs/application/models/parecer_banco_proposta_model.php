<?php

class parecer_banco_proposta_model extends CI_Model{
    
    public function atualiza_parecer($options){
		$this->db->where('id_proposta', $options['id_proposta']);
		$this->db->where('id_parecer', $options['id_parecer']);
		$this->db->where('data_parecer', $options['data_parecer']);
		$pareceres = $this->db->get('parecer_proposta_banco_proposta');
		
		if(count($pareceres->result()) > 0){
			$dados = $pareceres->row(0);
			$this->db->where('id_parecer_proposta', $dados->id_parecer_proposta);
			$this->db->update('parecer_proposta_banco_proposta', $options);
		}else
			$this->db->insert('parecer_proposta_banco_proposta', $options);
	}
	
	public function atualiza_parecer_plano_trabalho($options){
		$this->db->where('id_proposta', $options['id_proposta']);
		$this->db->where('id_parecer', $options['id_parecer']);
		$this->db->where('data_parecer', $options['data_parecer']);
		$pareceres = $this->db->get('parecer_plano_trabalho_banco_proposta');
	
		if(count($pareceres->result()) > 0){
			$dados = $pareceres->row(0);
			$this->db->where('id_parecer_proposta', $dados->id_parecer_proposta);
			$this->db->update('parecer_plano_trabalho_banco_proposta', $options);
		}else
			$this->db->insert('parecer_plano_trabalho_banco_proposta', $options);
	}
	
	public function atualiza_parecer_ajuste_plano_trabalho($options){
		$this->db->where('id_proposta', $options['id_proposta']);
		$this->db->where('id_parecer', $options['id_parecer']);
		$this->db->where('data_parecer', $options['data_parecer']);
		$pareceres = $this->db->get('parecer_ajuste_pl_trabalho_banco_proposta');
	
		if(count($pareceres->result()) > 0){
			$dados = $pareceres->row(0);
			$this->db->where('id_parecer_proposta', $dados->id_parecer_proposta);
			$this->db->update('parecer_ajuste_pl_trabalho_banco_proposta', $options);
		}else
			$this->db->insert('parecer_ajuste_pl_trabalho_banco_proposta', $options);
	}
	
	public function get_by_id($idProposta, $idParecer){
		$this->db->where('id_proposta', $idProposta);
		$this->db->where('id_parecer', $idParecer);
		return $this->db->get('parecer_proposta_banco_proposta')->row(0);
	}
	
	public function get_data_ultimo_parecer($id_siconv){
                $data_parecer = null;
	
		$this->db->where('id_proposta', $id_siconv);
		$this->db->order_by('id_parecer_proposta', 'desc');
		$query = $this->db->get('parecer_proposta_banco_proposta');
		
		if ($query->num_rows > 0) {
                    $data_parecer = $query->row(0);
                    $data_parecer = $data_parecer->data_parecer;
		} else {
                    $data_parecer = null;
		}				
		
		return $data_parecer;
	}
        
        public function get_data_ultimo_parecer_plano_trabalho($id_siconv){
                $data_parecer = null;
	
		$this->db->where('id_proposta', $id_siconv);
		$this->db->order_by('id_parecer_proposta', 'desc');
		$query = $this->db->get('parecer_plano_trabalho_banco_proposta');
		
		if ($query->num_rows > 0) {
                    $data_parecer = $query->row(0);
                    $data_parecer = $data_parecer->data_parecer;
		} else {
                    $data_parecer = null;
		}				
		
		return $data_parecer;
	}
}