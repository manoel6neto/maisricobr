<?php

class cidades_model extends CI_Model{
	
	function get_all(){
		//$query = $this->db->query('select * from usuario');
		$this->db->like('nome', '');
		$query = $this->db->get('usuario');
		return $query->result();
	}
	
	function add_records($options = array()){
		//$query = $this->db->query('select * from usuario');
		$this->db->insert('pessoa', $options);
		return $this->db->affected_rows();
	}
	
	function adicionar_sessao($options = array()){
		$this->db->insert('sessao', $options);
		return $this->db->affected_rows();
	}
	
	function delete_record(){
		$this->db->where('id', $this->uri->segment(4));
		$this->db->delete('usuario');
		return $this->db->affected_rows();
	}
	
	function update_record($options = array()){
		if (isset($options['nome']))
			$this->db->set('nome', $options['nome']);
		if (isset($options['senha']))
			$this->db->set('senha', $options['senha']);
		$this->db->where('id', $options['id']);
		$this->db->update('usuario');
		return $this->db->affected_rows();
	}
	function get_by_id($id){
		
		$this->db->where('id', $id);
		$query = $this->db->get('usuario');
		return $query->row(0);
	}
	
	function obter_sessao($id){
		$this->db->where('session_id', $id);
		$query = $this->db->get('sessao');
		return $query->row(0)->user_data;
	}
	
	function obter_estados(){

		$this->db->order_by('sigla');
		$query = $this->db->get('estados');
		$area = $query->result_array();
		return $area;
	}	
	
	function obter_cidades($id){
		$this->db->distinct();
		$this->db->select("nome");
		$this->db->where('estados_cod_estados', $id);
		$this->db->order_by('nome');
		$query = $this->db->get('cidades');
		//echo $this->db->last_query();
		$area = $query->result_array();
		return $area;
	}	
	
	function obter_cidades_inverso($id){
		$this->db->distinct();
		$this->db->where('Sigla', $id);
		//$this->db->join('cidades_siconv', 'cidades_siconv.Nome = cidades.nome', 'right');
		$query = $this->db->get('cidades_siconv');
		$area = $query->result_array();
		return $area;
	}	
	
	function sigla_estado($id){

		$this->db->where('cod_estados', $id);
		$query = $this->db->get('estados');
		$area = $query->result_array();
		return $area;
	}	
	
	function obter_cnpj($id){

		$this->db->like('nome', 'prefeitura')
				->like('nome', $id);
		$query = $this->db->get('proponentes_municipios');
		$area = $query->result_array();
		if (count($area) == 0){
			$this->db->like('nome', 'municipio')
				->like('nome', $id);
			$query = $this->db->get('proponentes_municipios');
			$area = $query->result_array();
		}
		return $area;
	}	
	
	function obter_cidade_por_cnpj($string){
		$id = substr($string, 0, 2) . '.' . substr($string, 2, 3) . '.' . substr($string, 5, 3) . '/' . substr($string, 8, 4) . '-' . substr($string, 12, 2);
		$this->db->like('cnpj', $id);
		$query = $this->db->get('proponentes_municipios');
		if (isset($query->row(0)->nome) !== false)
			return $query->row(0)->nome;
		return null;
	}
	
	public function get_estado_by_cidade($nome_cidade){
		$this->db->where('nome', $nome_cidade);
		$estado = $this->db->get('cidades')->row(0);
		
		return $estado->estados_cod_estados;
	}
        
        public function get_sigla_estado_by_cidade($cidade, $estado){
            $this->db->select('municipio_uf_sigla');
            $this->db->where('municipio', $cidade);
            $this->db->where('municipio_uf_nome', $estado);
            $result = $this->db->get('MV_CIDADES')->row(0);
            return $result->municipio_uf_sigla;
        }
}

?>
