<?php

class contato_municipio_model extends CI_Model{
	
	public function insert_or_update($options){
		if($options['id_contato_municipio']){
			$this->db->where('id_contato_municipio', $options['id_contato_municipio']);
			$this->db->update('contato_municipio', $options);
			
			return $options['id_contato_municipio'];
		}else{
			$this->db->insert('contato_municipio', $options);
			
			return $this->db->insert_id();
		}
	}
	
	public function getStatusContato($statusContato = ""){
		$status = array(
				'VISITA_1'=>'1ª VISITA',
				'VISITA_2'=>'2ª VISITA',
				'VISITA_3'=>'3ª VISITA',
				'VISITA_4'=>'4ª VISITA',
				'VISITA_5'=>'5ª VISITA',
				'ESCLARECIMENTO_EDITAL'=>'ESCLARECIMENTO EDITAL',
				'PREVISAO_PUBLICACAO'=>'PREVISÃO DA PUBLICAÇÃO',
				'PUBLICACAO'=>'PUBLICAÇÃO',
				'LICITACAO'=>'LICITAÇÃO',
				'ASSINATURA_CONTRATO'=>'ASSINATURA DO CONTRATO'
		);
		
		if($statusContato != "")
			return $status[$statusContato];
		
		return $status;
	}
	
	function formataCelular($numero){
		$tamanhoTelefone = strlen($numero);
		
		if($tamanhoTelefone < 8)
			return $numero;
		else if($tamanhoTelefone == 8)
			return substr($numero, 2, 4)."-".substr($numero, 6, 4);
		else if($tamanhoTelefone == 10)
			return "(".substr($numero, 0, 2).") ".substr($numero, 2, 4)."-".substr($numero, 6, 4);
		else if($tamanhoTelefone == 11)
			return "(".substr($numero, 0, 2).") ".substr($numero, 2, 5)."-".substr($numero, 7, 4);
	}
	
	public function get_dados_contato(){
		$this->load->model('usuariomodel');
		
		$dados_cnpj = $this->usuariomodel->get_cnpjs_by_usuario($this->session->userdata('id_usuario'));
		
		$this->db->where('id_usuario_cadastrou', $this->session->userdata('id_usuario'));
		
		return $this->db->get('contato_municipio')->result();
	}
	
	public function get_by_id($id){
		$this->db->where('id_contato_municipio', $id);
		return $this->db->get('contato_municipio')->row(0);
	}
	
	public function atualiza($id, $options){
		$this->db->where('id_contato_municipio', $id);
		$this->db->update('contato_municipio', $options);
	}
	
	public function verifica_alerta_retorno($id = null){
		$data_pesquisa = date('Y-m-d', strtotime("+1 day"));
		
		$this->db->where('data_retorno = ', $data_pesquisa);
		if($id != null)
			$this->db->where('contato_municipio.id_contato_municipio', $id);
		$this->db->where('data_retorno IS NOT NULL', null, FALSE);
		$this->db->where('id_usuario_cadastrou', $this->session->userdata('id_usuario'));
		$this->db->join('historico_contato_municipio', 'contato_municipio.id_contato_municipio = historico_contato_municipio.id_contato_municipio');
		$this->db->order_by('id_historico_contato_municipio', 'DESC');
		$query = $this->db->get('contato_municipio');
		
		if($id != null){
			if(count($query->result()) > 0)
				return $query->row(0)->data_retorno == "";
			
			return false;
		}
		
		$temDataAberta = false;
		foreach ($query->result() as $q){
			if($query->row(0)->data_retorno == ""){
				$temDataAberta = true;
				break;
			}
		}
		
		return $temDataAberta;
	}
	
	public function verifica_alerta_marca_retorno($id = null){
		if($id != null)
			$this->db->where('contato_municipio.id_contato_municipio', $id);
		//$this->db->where('data_retorno IS NULL', null, FALSE);
		//$this->db->where('DATE_ADD(data_cadastro, INTERVAL -2 DAY) <= ', date('Y-m-d'), FALSE);
		$this->db->where('id_usuario_cadastrou', $this->session->userdata('id_usuario'));
		$this->db->join('historico_contato_municipio', 'contato_municipio.id_contato_municipio = historico_contato_municipio.id_contato_municipio');
		$this->db->order_by('id_historico_contato_municipio', 'DESC');
		$query = $this->db->get('contato_municipio');
		
		if($id != null){
			if(count($query->result()) > 0)
				return $query->row(0)->data_retorno == "";
		}
		
		$temDataAberta = false;
		foreach ($query->result() as $q){
			if($query->row(0)->data_retorno == ""){
				$temDataAberta = true;
				break;
			}
		}
		
		return $temDataAberta;
	}
	
	public function get_ultimo_contato($idCidade, $uf, $cnpj, $esfera){
		if($idCidade != "" && $uf != "" && $cnpj != ""){
			$this->db->_protect_identifiers = false;
			
			$listaEsferas = "";
			foreach ($esfera as $e)
				$listaEsferas .= "'".$e."', ";
			
			$listaEsferas = trim($listaEsferas, ", ");
			
			$this->db->where('id_municipio', $idCidade);
			$this->db->where('sigla_uf', $uf);
			$this->db->where_in('ps.cnpj', $cnpj);
			$this->db->where('id_usuario_cadastrou', $this->session->userdata('id_usuario'));
			$this->db->join('proponente_siconv ps', "id_municipio = codigo_municipio", FALSE);
			$this->db->join('cnpj_contato_municipio', "contato_municipio.id_contato_municipio = cnpj_contato_municipio.id_contato_municipio AND cnpj_contato IN (select cnpj from proponente_siconv where esfera_administrativa IN (".$listaEsferas.") and codigo_municipio  =  '{$idCidade}' AND `sigla_uf` =  '{$uf}')");
			$this->db->join('historico_contato_municipio', 'contato_municipio.id_contato_municipio = historico_contato_municipio.id_contato_municipio');
			$dados = $this->db->get('contato_municipio')->row(0);
			//echo $this->db->last_query();
			if(!empty($dados))
				return array_merge((array)$dados, array('lista_status'=>$this->removeStatusUtilizados($dados->id_contato_municipio)));
			else
				return array('nome_contato'=>'', 'email_contato'=>'', 'telefone_contato'=>'', 'id_contato_municipio'=>'', 'status_contato'=>'', 'lista_status'=>'');
		}else
			return array('nome_contato'=>'', 'email_contato'=>'', 'telefone_contato'=>'', 'id_contato_municipio'=>'', 'status_contato'=>'', 'lista_status'=>'');
	}
	
	public function removeStatusUtilizados($idContato){
		$this->db->where('id_contato_municipio', $idContato);
		$listaStatus = $this->db->get('historico_contato_municipio')->result();
		
		$statusGeral = $this->getStatusContato();
		
		foreach ($listaStatus as $status)
			unset($statusGeral[$status->status_contato]);
			
		return $statusGeral;
	}
	
	public function checkTemContatoCadastrado(){
		$sql = "SELECT distinct contato_municipio.* FROM contato_municipio join proponente_siconv ps on id_municipio = codigo_municipio 
					join cnpj_siconv on REPLACE(REPLACE(REPLACE(ps.cnpj, '/', ''), '-', ''), '.', '') = cnpj_siconv.cnpj
					join usuario_cnpj on (id_cnpj = id_cnpj_siconv and id_usuario = {$this->session->userdata('id_usuario')})
					where id_usuario_cadastrou = {$this->session->userdata('id_usuario')}";
		
		$query = $this->db->query($sql);
		if($query->num_rows > 0){
			$dados = $query->row(0);
			if($dados->nome_contato == "" || $dados->email_contato == "")
				return "SEM_CONTATO#".$dados->id_contato_municipio;
			
			return "";
		}else
			return "SEM_CADASTRO";
	}
}