<?php

class GPPI_Model extends CI_Model {
    /*
     * Retorna um array de resultado com 
     * ['familias'] - retorna array com todos os objetos de familias presentes para o filtro selecionado
     * ['pessoas'] - retorna array com as pessoas indexado por um array com id da familia = ['pessoas']['id_familia'] e o objeto da pessoa
     * Para resultado de consultas conjutas basta aplicar a regra && ou || na ordem dos resultados
     */

    //Conta a quantidade de familias e de pessoas em um array do tipo resultado
    public function count_familias_pessoas_return_object($result_array) {
        $total_familias = 0;
        $total_pessoas = 0;
        $total_familias = count($result_array['familias']);

        foreach ($result_array['pessoas'] as $array_pessoas) {
            $total_pessoas = $total_pessoas + count($array_pessoas);
        }

        return array('total_familias' => $total_familias, 'total_pessoas' => $total_pessoas);
    }

    //Pegar familias e pessoas por bairro
    public function get_beneficiarios_por_bairro($id_estado, $id_cidade, $bairro) {
        //Carregando o id do endereço com o bairro: 
        $this->db->distinct();
        $this->db->select('id');
        $this->db->where('id_estado', $id_estado);
        $this->db->where('id_cidade', $id_cidade);
        $this->db->where('bairro', $bairro);
        $query_id_endereco = $this->db->get('endereco');

        if (count($query_id_endereco->result()) > 0) {
            $enderecos = $query_id_endereco->result();
            //Carregando as famílias que moram nos determinados endereços
            $array_ids = array();
            foreach ($enderecos as $endereco) {
                array_push($array_ids, $endereco->id);
            }

            //Consultando as famílias
            $this->db->where_in('id', $array_ids);
            $query_familias = $this->db->get('familia');

            if (count($query_familias->result()) > 0) {
                $familias = $query_familias->result();

                $array_resultado = array(
                    'familias' => array(),
                    'pessoas' => array()
                );

                foreach ($familias as $familia) {
                    array_push($array_resultado['familias'], $familia);
                    //Consultando as pessoas na familia e inserindo o array de pessoas com o id da familia na frente
                    $this->db->distinct();
                    $this->db->select('id_familia, id_pessoa');
                    $this->db->where('id_familia', $familia->id);
                    $query_pessoas_familia = $this->db->get('familia_pessoa');
                    //gerando o indice para a familia no array
                    $array_resultado['pessoas'][$familia->id] = array();
                    //carregando os objetos para as pessoas
                    foreach ($query_pessoas_familia->result() as $id_pessoa) {
                        //Consultando a pessoa inteira
                        $this->db->where('id', $id_pessoa->id_pessoa);
                        $query_pessoa = $this->db->get('pessoa');
                        array_push($array_resultado['pessoas'][$familia->id], $query_pessoa->row(0));
                    }
                }
            } else {
                return NULL;
            }
        }
        return NULL;
    }

}
