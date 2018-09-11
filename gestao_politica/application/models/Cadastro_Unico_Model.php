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

    public function get_familias_completo() {
        $CADUNICO = $this->load->database('cad_unico', TRUE);

        $CADUNICO->select('familia.*, endereco.*');
        $CADUNICO->join('endereco', 'endereco.id = familia.id_endereco');
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
        $CADUNICO->join('sexo', 'pessoa.id_sexo = sexo.id');
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

        $CADUNICO->select('pessoa.*, familia_pessoa.*');
        $CADUNICO->where('pessoa.id', $id);
        $CADUNICO->join('familia_pessoa', 'pessoa.id = familia_pessoa.id_pessoa');
        $query = $CADUNICO->get('pessoa');
        $CADUNICO->close();
        return $query->row(0);
    }

    public function get_pessoa($id) {
        $CADUNICO = $this->load->database('cad_unico', TRUE);

        $CADUNICO->select('pessoa.*, familia_pessoa.*, funcao_familiar.*');
        $CADUNICO->where('pessoa.id', $id);
        $CADUNICO->join('familia_pessoa', 'pessoa.id = familia_pessoa.id_pessoa');
        $CADUNICO->join('funcao_familiar', 'familia_pessoa.id_funcao = funcao_familiar.id');
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

    public function get_sexo_from_id_sexo($id) {
        $CADUNICO = $this->load->database('cad_unico', TRUE);

        $CADUNICO->where('id', $id);
        $query_sexo = $CADUNICO->get('sexo');
        return $query_sexo->row(0)->descricao;
    }
    
    public function get_escolaridade_from_id_escolaridade($id) {
        $CADUNICO = $this->load->database('cad_unico', TRUE);

        $CADUNICO->where('id', $id);
        $query_sexo = $CADUNICO->get('escolaridade');
        return $query_sexo->row(0)->descricao;
    }
    
    public function get_profissao_from_id_profissao($id) {
        $CADUNICO = $this->load->database('cad_unico', TRUE);

        $CADUNICO->where('id', $id);
        $query_sexo = $CADUNICO->get('profissao');
        return $query_sexo->row(0)->descricao;
    }
    
    public function get_funcao_from_id_funcao($id) {
        $CADUNICO = $this->load->database('cad_unico', TRUE);

        $CADUNICO->where('id', $id);
        $query_sexo = $CADUNICO->get('funcao_familiar');
        return $query_sexo->row(0)->descricao;
    }
    
    public function get_raca_from_id_raca($id) {
        $CADUNICO = $this->load->database('cad_unico', TRUE);

        $CADUNICO->where('id', $id);
        $query_sexo = $CADUNICO->get('cor');
        return $query_sexo->row(0)->descricao;
    }

    public function get_foto_from_id($id) {
        $CADUNICO = $this->load->database('cad_unico', TRUE);

        $CADUNICO->where('id', $id);
        $query_sexo = $CADUNICO->get('foto_pessoa');
        return $query_sexo->row(0)->data;
    }

}
