<?php
class arquivos_model extends CI_Model{
	
	public function lista_arquivos($limit = null, $offset = null, $tipo = 'D'){
		$this->db->_protect_identifiers=false;
		$this->db->where('tipo_arquivo', $tipo);
		
		$this->db->order_by("Case When idArquivo IN (13, 11, 10, 9, 8)  Then 1 Else 0 End", "desc");
		$this->db->order_by('data_hora_envio', 'desc');
		//$this->db->query('SELECT * from arquivos order by Case When idArquivo IN (13, 11, 10, 9)  Then 0 Else 1 End desc, data_hora_envio desc');
		$query = $this->db->get('arquivos', $limit, $offset);
		return $query->result();
	}
	
	public function insere_arquivo($options){
		$this->db->insert('arquivos', $options);
		return $this->db->insert_id();
	}
	
	public function retorna_arquivo($id){
		$this->db->where('idArquivo', $id);
		return $this->db->get('arquivos')->row(0);
	}
	
	function hex2bin($str){
		$bin = "";
		$i = 0;
		do {
			$bin .= chr(hexdec($str{$i}.$str{($i + 1)}));
			$i += 2;
		} while ($i < strlen($str));
		return $bin;
	}
	
	public function deleta_arquivo($id){
		$arquivo = $this->retorna_arquivo($id);
		$arquivo_nome = "";
		if($arquivo->print_arquivo != ""){
			$print_nome = explode("/", $arquivo->print_arquivo);
			$arquivo_nome = BASEPATH."../uploads/".$print_nome[count($print_nome)-1];
		}
		$podeDeletar = false;
		if(file_exists($arquivo_nome)){
			if(unlink($arquivo_nome))
				$podeDeletar = true;
		}else
			$podeDeletar = true;
		
		if($podeDeletar){
			$this->db->where('idArquivo', $id);
			$this->db->delete('arquivos');
		}
		
		return $this->db->affected_rows();
	}
}
?>