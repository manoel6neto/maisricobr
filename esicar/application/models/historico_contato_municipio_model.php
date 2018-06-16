<?php

class historico_contato_municipio_model extends CI_Model{
	
	public function insert($options){
		$this->db->insert('historico_contato_municipio', $options);
	}
	
	public function insere_cnpj_contato($options){
		$this->db->where('cnpj_contato', $options['cnpj_contato']);
		$this->db->where('id_contato_municipio', $options['id_contato_municipio']);
		$query = $this->db->get('cnpj_contato_municipio');
		
		if($query->num_rows() <= 0)
			$this->db->insert('cnpj_contato_municipio', $options);
	}
	
//	public function get_ultima_data_retorno($id_contato_municipio){
//		$this->db->where('id_contato_municipio', $id_contato_municipio);
//		$this->db->order_by('id_historico_contato_municipio', 'DESC');
//		return $this->db->get('historico_contato_municipio')->row(0)->data_retorno;
//	}
        
        public function get_data_visita($id_contato_municipio){
		$this->db->where('id_contato_municipio', $id_contato_municipio);
		return $this->db->get('historico_contato_municipio')->row(0)->data_visita;
	}
	
	public function get_all_historico($idContato){
		$this->db->where('id_contato_municipio', $idContato);
		return $this->db->get('historico_contato_municipio')->result();
	}
	
	public function atualiza_historico($idHistorico, $options){
		$this->db->where('id_historico_contato_municipio', $idHistorico);
		$this->db->update('historico_contato_municipio', $options);
	}
	
	public function getClassVisita($class){
		if($class == "P")
			return "Positivo";
		else if($class == "N")
			return "Negativo";
		
		return "";
	}
        
        public function get_status ($id_contato_municipio){
            $this->db->select('status_contrato');
            $this->db->where('id_contato_municipio',$id_contato_municipio);
            return $this->db->get('historico_contato_municipio')->row(0)->status_contrato;
        }
}