<?php

class Cadastro_Unico_Model extends CI_Model {

    public function get_dados_cidade($id) {
        $CADUNICO = $this->load->database('cad_unico', TRUE);

        $query = $CADUNICO->get('cidade');
        $CADUNICO->close();
        return $query->result();
    }

    public function get_familias() {
        $CADUNICO = $this->load->database('cad_unico', TRUE);

        $query = $CADUNICO->get('familia');
        $CADUNICO->close();
        return $query->result();
    }

    public function get_familia_from_id($id) {
        $CADUNICO = $this->load->database('cad_unico', TRUE);

        $CADUNICO->where('id', $id);
        $query = $CADUNICO->get('familia');
        $CADUNICO->close();
        return $query->row(0);
    }

    public function get_integrantes_familia($id) {
        $CADUNICO = $this->load->database('cad_unico', TRUE);

        $CADUNICO->where('id_familia', $id);
        $CADUNICO->join('pessoa', 'familia_pessoa.id_pessoa = pessoa.id');
        $CADUNICO->join('funcao_familiar', 'familia_pessoa.id_funcao = funcao_familiar.id');
        $query = $CADUNICO->get('familia_pessoa');
        $CADUNICO->close();
        return $query->result();
    }

    public function get_responsavel_familia($id) {
        $CADUNICO = $this->load->database('cad_unico', TRUE);

        $CADUNICO->where('id_familia', $id);
        $CADUNICO->where('flag_responsavel', 1);
        $query = $CADUNICO->get('familia_pessoa');
        $CADUNICO->close();
        return $query->row(0);
    }

    public function get_pessoa_from_id($id) {
        $CADUNICO = $this->load->database('cad_unico', TRUE);

        $CADUNICO->where('id', $id);
        $query = $CADUNICO->get('pessoa');
        $CADUNICO->close();
        return $query->row(0);
    }
    
        public function get_pessoa_completo_from_id($id) {
        $CADUNICO = $this->load->database('cad_unico', TRUE);

        $CADUNICO->where('id', $id);
        $CADUNICO->join('familia_pessoa', 'pessoa.id = familia_pessoa.id_pessoa');
        $CADUNICO->join('familia', 'pessoa.id_familia = familia.id');
        $CADUNICO->join('endereco', 'familia.id_endereco = endereco.id');
        $query = $CADUNICO->get('pessoa');
        $CADUNICO->close();
        return $query->row(0);
    }

    public function get_pessoas_from_ids($integrantes) {
        $CADUNICO = $this->load->database('cad_unico', TRUE);

        $ids_integrantes = array();
        foreach ($integrantes as $integ) {
            array_push($ids_integrantes, $integ->id_pessoa);
        }

        $CADUNICO->where_in('id', $ids_integrantes);
        $query = $CADUNICO->get('pessoa');
        $CADUNICO->close();
        return $query->result();
    }
    
    

//    public function get_consultas_pessoa($id) {
//        $CADUNICO = $this->load->database('cad_unico', TRUE);
//
//        $CADUNICO->where('fk_id_pessoa', $id);
//        $query = $CADUNICO->get('consultas');
//        $CADUNICO->close();
//        return $query->result();
//    }
//    public function get_zoonoses_pessoa($id) {
//        $CADUNICO = $this->load->database('cad_unico', TRUE);
//
//        $CADUNICO->where('fk_id_pessoa', $id);
//        $query = $CADUNICO->get('zoonoses');
//        $CADUNICO->close();
//        return $query->result();
//    }
//
    public function get_renda_familia($id) {
        $CADUNICO = $this->load->database('cad_unico', TRUE);

        $CADUNICO->select('SUM(renda) as renda');
        $CADUNICO->where('id_familia', $id);
        $CADUNICO->join('pessoa', 'familia_pessoa.id_pessoa = pessoa.id');
        $CADUNICO->join('funcao_familiar', 'familia_pessoa.id_funcao = funcao_familiar.id');
        $query = $CADUNICO->get('familia_pessoa');
        $CADUNICO->close();
        return $query->result();
    }

    public function date_format($date) {
        $time = strtotime($date);
        $myFormatForView = date("d/m/Y", $time);

        return $myFormatForView;
    }

}
