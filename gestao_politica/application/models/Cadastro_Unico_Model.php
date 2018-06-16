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
        return $query->result();
    }

    public function get_integrantes_familia($id) {
        $CADUNICO = $this->load->database('cad_unico', TRUE);

        $CADUNICO->where('fk_id_familia', $id);
        $query = $CADUNICO->get('pessoa');
        $CADUNICO->close();
        return $query->result();
    }

    public function get_responsavel_familia($id) {
        $CADUNICO = $this->load->database('cad_unico', TRUE);

        $CADUNICO->where('fk_id_familia', $id);
        $CADUNICO->where('is_responsavel', 1);
        $query = $CADUNICO->get('pessoa');
        $CADUNICO->close();
        return $query->result();
    }

    public function get_pessoa_from_id($id) {
        $CADUNICO = $this->load->database('cad_unico', TRUE);

        $CADUNICO->where('id', $id);
        $query = $CADUNICO->get('pessoa');
        $CADUNICO->close();
        return $query->result();
    }

    public function get_consultas_pessoa($id) {
        $CADUNICO = $this->load->database('cad_unico', TRUE);

        $CADUNICO->where('fk_id_pessoa', $id);
        $query = $CADUNICO->get('consultas');
        $CADUNICO->close();
        return $query->result();
    }

    public function get_zoonoses_pessoa($id) {
        $CADUNICO = $this->load->database('cad_unico', TRUE);

        $CADUNICO->where('fk_id_pessoa', $id);
        $query = $CADUNICO->get('zoonoses');
        $CADUNICO->close();
        return $query->result();
    }

    public function get_renda_familia($id) {
        $CADUNICO = $this->load->database('cad_unico', TRUE);

        $CADUNICO->select('SUM(renda) as renda');
        $CADUNICO->where('fk_id_familia', $id);
        $query = $CADUNICO->get('pessoa');
        $CADUNICO->close();
        return $query->result();
    }

    public function date_format($date) {
        $time = strtotime($date);
        $myFormatForView = date("d/m/Y", $time);

        return $myFormatForView;
    }

}
