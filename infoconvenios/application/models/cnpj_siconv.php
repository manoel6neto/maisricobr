<?php

class cnpj_siconv extends CI_Model {

    function get_all() {
        $query = $this->db->get('cnpj_siconv');
        return $query->result();
    }

    function get_by_id($id_cnpj_siconv) {
        $this->db->where('id_cnpj_siconv', $id_cnpj_siconv);
        $query = $this->db->get('cnpj_siconv');
        return $query->row(0);
    }

    function get_cidade_by_cnpj_siconv($cnpj_siconv) {
    	$this->load->model('proponente_siconv_model');
    	
    	$lista_ids = array();
    	if($this->session->userdata('nivel') != 1){
	    	$ids = $this->db->query('SELECT id_cnpj FROM usuario_cnpj WHERE id_usuario = '.$this->session->userdata('id_usuario'));
	    	foreach ($ids->result() as $id)
	    		$lista_ids[] = $id->id_cnpj;
    	}
    	
    	if(count($lista_ids) > 0)
    		$this->db->where_in('id_cnpj_siconv', $lista_ids, FALSE);
    	else
    		$this->db->distinct();
        $this->db->where('cnpj', $cnpj_siconv);
        $query_cnpj = $this->db->get('cnpj_siconv');
        
        
        if ($query_cnpj->row(0)->id_cidade > 0) {
        	$this->db->where('Nome', str_replace("'", " ", $this->proponente_siconv_model->get_municipio_nome($query_cnpj->row(0)->id_cidade)->municipio));
        	$this->db->where('Sigla', $query_cnpj->row(0)->sigla);
            $query_cidade_siconv = $this->db->get('cidades_siconv');
            
            if ($query_cidade_siconv->num_rows > 0) {
                return $query_cidade_siconv->row(0);
            }
        }

        return null;
    }

    function delete_by_id($id_cnpj_siconv) {
        $this->db->where('id_cnpj_siconv', $id_cnpj_siconv);
        $this->db->delete('cnpj_siconv');
        return $this->db->affected_rows();
    }
    
    public function insere_cnpj($options){
    	$this->db->insert('cnpj_siconv', $options);
    	return $this->db->insert_id();
    }
    
    public function get_estado_by_cidade($id_cidade){
    	$this->db->where('id_cidade', $id_cidade);
    	$cidade = $this->db->get('cidades_siconv')->row(0);
    	
    	$this->load->model('cidades_model');
    	$id_estado = $this->cidades_model->get_estado_by_cidade($cidade->Nome);
    	
    	return $id_estado;
    }
    
    public function atualiza_cnpj($id_cnpj_siconv, $options){
    	$this->db->where('id_cnpj_siconv', $id_cnpj_siconv);
    	$this->db->update('cnpj_siconv', $options);
    	
    	return $this->db->affected_rows();
    }
    
    public function get_nome_by_cnpj($cnpj, $buscaPorProponente = false){
    	if(!$buscaPorProponente){
	    	$this->db->where('cnpj', $cnpj);
	    	return $this->db->get('cnpj_siconv')->row(0)->cnpj_instituicao;
    	}else{
    		$this->load->model('programa_model');
    		$this->db->where('cnpj', $this->programa_model->formatCPFCNPJ($cnpj));
    		return $this->db->get('proponente_siconv')->row(0)->nome;
    	}
    }
    
    public function delete_by_usuario($id_usuario){
    	$this->db->where('id_usuario', $id_usuario);
    	$query = $this->db->get('usuario_cnpj')->result();
    	
    	$this->db->flush_cache();
    	
    	foreach ($query as $cnpj){
    		$this->db->where('id_cnpj_siconv', $cnpj->id_cnpj);
    		$this->db->delete('cnpj_siconv');
    	}
    	
    	$this->db->where('id_usuario', $id_usuario);
    	$this->db->delete('usuario_cnpj');
    }
    
    public function get_esfera_cnpj(){
    	$this->load->model('usuariomodel');
    	
    	$cnpjs = $this->usuariomodel->get_cnpjs_by_usuario($this->session->userdata('id_usuario'));
    	
    	$lista_entidades = array('consorcio'=>false, 'eco_mista'=>false, 'estadual'=>false, 'municipal'=>false, 'privada'=>false);
    	foreach ($cnpjs as $cnpj){
    		switch ($cnpj->esfera_administrativa){
    			case "CONSORCIO PUBLICO":
    				$lista_entidades['consorcio'] = true;
    				break;
    			case "EMPRESA PUBLICA SOCIEDADE ECONOMIA MISTA":
    				$lista_entidades['eco_mista'] = true;
    				break;
    			case "ESTADUAL":
    				$lista_entidades['estadual'] = true;
    				break;
    			case "MUNICIPAL":
    				$lista_entidades['municipal'] = true;
    				break;
    			case "PRIVADA":
    				$lista_entidades['privada'] = true;
    				break;
    		}
    	}
    	
    	$ehTudoFalso = true;
    	foreach ($lista_entidades as $entidade){
    		if($entidade)
    			$ehTudoFalso = false;
    	}
    	
    	if($ehTudoFalso)
    		$lista_entidades['municipal'] = true;
    	
    	return $lista_entidades;
    }
}