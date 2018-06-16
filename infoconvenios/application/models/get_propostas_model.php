<?php

class get_propostas_model extends CI_Model {

    public function propostas($proposta) {
        //var_dump($proposta);

        $cod_siconv = $proposta['Número da Proposta'];
        $situacao = $proposta['Situação'];
        $id = $proposta['id'];
        $modalidade = $proposta['Modalidade'];
        $programa = $proposta['programa'];
        $data_proposta = implode("-", array_reverse(explode("/", $proposta['Data da Proposta'])));


        return $this->db->query("INSERT INTO `physi971_wp`.`siconv_proposta`( `id_siconv`, `cod_siconv`, `situacao`, `modalidade`, `programa`,`data_proposta`) 
    			VALUES( '$id', '$cod_siconv', '$situacao', '$modalidade', '$programa', '$data_proposta')");
    }

}
