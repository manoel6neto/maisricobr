<?php

class cidades_siconv extends CI_Model {

    function get_all() {
        $query = $this->db->get('cidades_siconv');
        return $query->result();
    }

    function get_by_id($id_cidade) {
        $this->db->where('id_cidade', $id_cidade);
        $query = $this->db->get('cidades_siconv');
        return $query->row(0);
    }
    
    public function get_by_codigo($codigo){
    	$this->db->where('Codigo', $codigo);
    	$query = $this->db->get('cidades_siconv');
    	return $query->row(0);
    }
    
    function get_by_name($nome_cidade) {
        $this->db->where('Nome', $nome_cidade);
        $query = $this->db->get('cidades_siconv');
        return $query->row(0);
    }
    
    function get_citys_by_user_id($id_usuario) {
    
        $this->load->model('usuario_cnpj');
        $cnpjs = $this->usuario_cnpj->get_all_by_usuario($id_usuario);
        
        $citys = array();
        foreach ($cnpjs as $cnpj) {
            
            array_push($citys, $this->get_by_id($cnpj->id_cidade));
        }
        
        return $citys;
    }
    
    
}