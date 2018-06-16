<?php

class cidade_tag extends CI_Model{
    
    function update_cidade_tag($options) {
        $this->db->where('id_cidade_tag', $options['id_cidade_tag']);
        $this->db->update('cidade_tag', $options);
        
        return $this->db->affected_rows();
    }
    
    function get_cidade_tag_from_nome_cidade($codigo_cidade) {
        #TODO fazer a busca por cidade e estado. Evitar cidades de nomes iguais
        $this->db->where('Codigo', $codigo_cidade);
        $query = $this->db->get('cidades_siconv');
        $query = $query->result();
        $cidade_siconv = $query[0];
        
        $this->db->where('estado', $this->retornaNomeEstado($cidade_siconv->Sigla));
        $this->db->where('cidade', str_replace("'", " ", $cidade_siconv->Nome));
        $cidade_tag = $this->db->get('cidade_tag');
        
        return $cidade_tag->row(0);
    }
    
    function get_cidade_tag_by_cod($codigo) {
        #TODO fazer a busca por cidade e estado. Evitar cidades de nomes iguais
        $this->db->select("ct.*");
        $this->db->join("proponente_siconv ps", "ps.municipio = ct.cidade and ps.municipio_uf_nome = ct.estado", "inner");
        $this->db->where('ps.codigo_municipio', $codigo);
        $query = $this->db->get("cidade_tag ct");
        return $query->row(0);
    }
    
    function get_cod_siconv_by_cidade($cidade) {
        $query = $this->db->get_where("proponente_siconv", array("municipio" => $cidade));
        return $query->row(0);
    }
    
    public function retornaNomeEstado($sigla){
    	$listaNomes = array(
    			'AC'=>'Acre',
    			'AL'=>'Alagoas',
    			'AP'=>'Amapá',
    			'AM'=>'Amazonas',
    			'BA'=>'Bahia',
    			'CE'=>'Ceará',
    			'DF'=>'Distrito Federal',
    			'ES'=>'Espírito Santo',
    			'GO'=>'Goiás',
    			'MA'=>'Maranhão',
    			'MT'=>'Mato Grosso',
    			'MS'=>'Mato Grosso do Sul',
    			'MG'=>'Minas Gerais',
    			'PA'=>'Pará',
    			'PB'=>'Paraíba',
    			'PR'=>'Paraná',
    			'PE'=>'Pernambuco',
    			'PI'=>'Piauí',
    			'RJ'=>'Rio de Janeiro',
    			'RN'=>'Rio Grande do Norte',
    			'RS'=>'Rio Grande do Sul',
    			'RO'=>'Rondônia',
    			'RR'=>'Roraima',
    			'SC'=>'Santa Catarina',
    			'SP'=>'São Paulo',
    			'SE'=>'Sergipe',
    			'TO'=>'Tocantins'
    	);
    	
    	return $listaNomes[$sigla];
    }
}