<?php

class programa_banco_proposta_model extends CI_Model {
    
    function get_all() {
        $query = $this->db->get('programa_banco_proposta');
        return $query->result();
    }
    
    function get_by_id($id_programa_banco_proposta) {
        $this->db->where('id_programa_banco_proposta', $id_programa_banco_proposta);
        $query = $this->db->get('programa_banco_proposta');
        
        return $query->row(0);
    }
    
    function get_by_id_proposta($id_proposta_banco_proposta) {
        $this->db->where('id_proposta_banco_proposta', $id_proposta_banco_proposta);
        $query = $this->db->get('programa_banco_proposta');
        
        return $query->result();
    }
    
    //Inserir ou atualizar um programa na proposta do banco de propostas
    function insert_or_update($options) {
        $this->db->where('codigo_programa', $options['codigo_programa']);
        $this->db->where('id_proposta_banco_proposta', $options['id_proposta_banco_proposta']);
        $query = $this->db->get('programa_banco_proposta');
        
        if ($query->num_rows > 0) {
            $options['id_programa_banco_proposta'] = $query->row(0)->id_programa_banco_proposta;
            $this->db->where('id_programa_banco_proposta', $query->row(0)->id_programa_banco_proposta);            
            $this->db->update('programa_banco_proposta', $options);
            return $query->row(0)->id_programa_banco_proposta;
        } else {
            unset($options['id_programa_banco_proposta']);
            $this->db->insert('programa_banco_proposta', $options);
            return $this->db->insert_id();
        }
    }
    
    public function get_programas_by_proposta($id){
    	$this->db->where('id_proposta_banco_proposta', $id);
    	return $this->db->get('programa_banco_proposta')->result();
    }
}