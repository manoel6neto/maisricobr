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

        if (count($result_array) > 0) {
            foreach ($result_array['pessoas'] as $array_pessoas) {
                $total_pessoas = $total_pessoas + count($array_pessoas);
            }
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

                return $array_resultado;
            }
        }

        return NULL;
    }

    //Pegar familias e pessoas por cep
    public function get_beneficiarios_por_cep($id_estado, $id_cidade, $cep) {
        //Carregando o id do endereço com o cep: 
        $this->db->distinct();
        $this->db->select('id');
        $this->db->where('id_estado', $id_estado);
        $this->db->where('id_cidade', $id_cidade);
        $this->db->where('cep', $cep);
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

                return $array_resultado;
            }
        }

        return NULL;
    }

    //Pegar familias e pessoas por renda da pessoa
    public function get_beneficiarios_por_renda_pessoa($renda, $filtro) {
        //Carregando pessoas pela renda
        $this->db->distinct();
        $this->db->where("renda {$filtro} {$renda}");
        $query_pessoas_renda = $this->db->get('pessoa');

        if (count($query_pessoas_renda->result()) > 0) {
            $pessoas = $query_pessoas_renda->result();
            $array_ids_pessoas = array();
            $array_ids_familias = array();
            foreach ($pessoas as $pessoa) {
                $this->db->where_in('id_pessoa', $pessoa->id);
                $query_pessoa_familia = $this->db->get('familia_pessoa');

                foreach ($query_pessoa_familia->result() as $pessoa_familia) {
                    if (!in_array($pessoa_familia->id_familia, $array_ids_familias)) {
                        array_push($array_ids_familias, $pessoa_familia->id_familia);
                    }
                }
            }

            if (count($array_ids_familias) > 0) {
                //carregando as familias
                $this->db->distinct();
                $this->db->where_in('id', $array_ids_familias);
                $query_familias = $this->db->get('familia');
                $familias = $query_familias->result();

                $array_resultado = array(
                    'familias' => array(),
                    'pessoas' => array()
                );

                foreach ($familias as $fam) {
                    array_push($array_resultado['familias'], $fam);
                }

                foreach ($pessoas as $pess) {
                    if (!in_array($this->get_id_familia_por_pessoa($pess->id), $array_resultado['pessoas'])) {
                        $array_resultado['pessoas'][$this->get_id_familia_por_pessoa($pess->id)] = array();
                    }
                }

                foreach ($pessoas as $pess) {
                    array_push($array_resultado['pessoas'][$this->get_id_familia_por_pessoa($pess->id)], $pess);
                }

                return $array_resultado;
            }
        }

        return NULL;
    }

    //Pega o id_familia pelo id da pessoa
    public function get_id_familia_por_pessoa($id_pessoa) {
        $this->db->select('id_familia');
        $this->db->where('id_pessoa', $id_pessoa);
        $query_id_familia = $this->db->get('familia_pessoa');

        return $query_id_familia->row(0)->id_familia;
    }

    //Pega familia e pessoas por renda da familia
    public function get_beneficiarios_por_renda_familia($renda, $filtro) {
        $query_familias = $this->db->get('familia');
        $familias = $query_familias->result();

        //filtrando pela renda
        $array_familias_atendem_filtro = array();
        foreach ($familias as $familia) {
            $this->db->select('sum(pessoa.renda) as rendaf');
            $this->db->join('familia_pessoa', 'familia_pessoa.id_pessoa = pessoa.id');
            $this->db->where('familia_pessoa.id_familia', $familia->id);
            $query_soma = $this->db->get('pessoa');
            $soma = $query_soma->row(0)->rendaf;

            switch ($filtro) {
                case '>':
                    if ($soma > $renda) {
                        array_push($array_familias_atendem_filtro, $familia);
                    }
                    break;
                case '>=':
                    if ($soma >= $renda) {
                        array_push($array_familias_atendem_filtro, $familia);
                    }
                    break;
                case '=':
                    if ($soma == $renda) {
                        array_push($array_familias_atendem_filtro, $familia);
                    }
                    break;
                case '!=':
                    if ($soma != $renda) {
                        array_push($array_familias_atendem_filtro, $familia);
                    }
                    break;
                case '<':
                    if ($soma < $renda) {
                        array_push($array_familias_atendem_filtro, $familia);
                    }
                    break;
                case '<=':
                    if ($soma <= $renda) {
                        array_push($array_familias_atendem_filtro, $familia);
                    }
                    break;
            }
        }

        //Salvando o array de resultados
        $array_resultado = array(
            'familias' => array(),
            'pessoas' => array()
        );

        //Familias
        foreach ($array_familias_atendem_filtro as $familias_filtradas) {
            array_push($array_resultado['familias'], $familias_filtradas);
            //Consultando as pessoas na familia e inserindo o array de pessoas com o id da familia na frente
            $this->db->distinct();
            $this->db->select('id_familia, id_pessoa');
            $this->db->where('id_familia', $familias_filtradas->id);
            $query_pessoas_familia = $this->db->get('familia_pessoa');
            //gerando o indice para a familia no array
            $array_resultado['pessoas'][$familias_filtradas->id] = array();
            //carregando os objetos para as pessoas
            foreach ($query_pessoas_familia->result() as $id_pessoa) {
                //Consultando a pessoa inteira
                $this->db->where('id', $id_pessoa->id_pessoa);
                $query_pessoa = $this->db->get('pessoa');
                array_push($array_resultado['pessoas'][$familias_filtradas->id], $query_pessoa->row(0));
            }
        }

        return $array_resultado;
    }

    //Pegar familias e pessoas por sexo
    public function get_beneficiarios_por_sexo($id_sexo) {
        //Carregando pessoas pelo sexo
        $this->db->distinct();
        $this->db->where('id_sexo', $id_sexo);
        $query_pessoas_sexo = $this->db->get('pessoa');

        if (count($query_pessoas_sexo->result()) > 0) {
            $pessoas = $query_pessoas_sexo->result();
            $array_ids_pessoas = array();
            $array_ids_familias = array();
            foreach ($pessoas as $pessoa) {
                $this->db->where_in('id_pessoa', $pessoa->id);
                $query_pessoa_familia = $this->db->get('familia_pessoa');

                foreach ($query_pessoa_familia->result() as $pessoa_familia) {
                    if (!in_array($pessoa_familia->id_familia, $array_ids_familias)) {
                        array_push($array_ids_familias, $pessoa_familia->id_familia);
                    }
                }
            }

            if (count($array_ids_familias) > 0) {
                //carregando as familias
                $this->db->distinct();
                $this->db->where_in('id', $array_ids_familias);
                $query_familias = $this->db->get('familia');
                $familias = $query_familias->result();

                $array_resultado = array(
                    'familias' => array(),
                    'pessoas' => array()
                );

                foreach ($familias as $fam) {
                    array_push($array_resultado['familias'], $fam);
                }

                foreach ($pessoas as $pess) {
                    if (!in_array($this->get_id_familia_por_pessoa($pess->id), $array_resultado['pessoas'])) {
                        $array_resultado['pessoas'][$this->get_id_familia_por_pessoa($pess->id)] = array();
                    }
                }

                foreach ($pessoas as $pess) {
                    array_push($array_resultado['pessoas'][$this->get_id_familia_por_pessoa($pess->id)], $pess);
                }

                return $array_resultado;
            }
        }

        return NULL;
    }

    //Pegar familias e pessoas por raça
    public function get_beneficiarios_por_raca($id_cor) {
        //Carregando pessoas pela raca
        $this->db->distinct();
        $this->db->where('id_cor', $id_cor);
        $query_pessoas_cor = $this->db->get('pessoa');

        if (count($query_pessoas_cor->result()) > 0) {
            $pessoas = $query_pessoas_cor->result();
            $array_ids_pessoas = array();
            $array_ids_familias = array();
            foreach ($pessoas as $pessoa) {
                $this->db->where_in('id_pessoa', $pessoa->id);
                $query_pessoa_familia = $this->db->get('familia_pessoa');

                foreach ($query_pessoa_familia->result() as $pessoa_familia) {
                    if (!in_array($pessoa_familia->id_familia, $array_ids_familias)) {
                        array_push($array_ids_familias, $pessoa_familia->id_familia);
                    }
                }
            }

            if (count($array_ids_familias) > 0) {
                //carregando as familias
                $this->db->distinct();
                $this->db->where_in('id', $array_ids_familias);
                $query_familias = $this->db->get('familia');
                $familias = $query_familias->result();

                $array_resultado = array(
                    'familias' => array(),
                    'pessoas' => array()
                );

                foreach ($familias as $fam) {
                    array_push($array_resultado['familias'], $fam);
                }

                foreach ($pessoas as $pess) {
                    if (!in_array($this->get_id_familia_por_pessoa($pess->id), $array_resultado['pessoas'])) {
                        $array_resultado['pessoas'][$this->get_id_familia_por_pessoa($pess->id)] = array();
                    }
                }

                foreach ($pessoas as $pess) {
                    array_push($array_resultado['pessoas'][$this->get_id_familia_por_pessoa($pess->id)], $pess);
                }

                return $array_resultado;
            }
        }

        return NULL;
    }

    //Pegar pessoas e familias por idade da pessoa
    public function get_beneficiarios_por_idade_pessoa($idade, $filtro) {
        //Carregando pessoas pela idade
        $this->db->distinct();
        $this->db->select('pessoa.*, TIMESTAMPDIFF(YEAR, pessoa.data_nascimento, NOW()) AS idade');
        $query_pessoas_idade = $this->db->get('pessoa');

        if (count($query_pessoas_idade->result()) > 0) {
            $temp_pessoas = $query_pessoas_idade->result();
            $pessoas = array();

            foreach ($temp_pessoas as $tpess) {
                switch ($filtro) {
                    case '=':
                        if ($tpess->idade == $idade) {
                            array_push($pessoas, $tpess);
                        }
                        break;
                    case '!=':
                        if ($tpess->idade != $idade) {
                            array_push($pessoas, $tpess);
                        }
                        break;
                    case '>':
                        if ($tpess->idade > $idade) {
                            array_push($pessoas, $tpess);
                        }
                        break;
                    case '>=':
                        if ($tpess->idade >= $idade) {
                            array_push($pessoas, $tpess);
                        }
                        break;
                    case '<':
                        if ($tpess->idade < $idade) {
                            array_push($pessoas, $tpess);
                        }
                        break;
                    case '<=':
                        if ($tpess->idade <= $idade) {
                            array_push($pessoas, $tpess);
                        }
                        break;
                }
            }

            if (count($pessoas) > 0) {
                $array_ids_pessoas = array();
                $array_ids_familias = array();
                foreach ($pessoas as $pessoa) {
                    $this->db->where_in('id_pessoa', $pessoa->id);
                    $query_pessoa_familia = $this->db->get('familia_pessoa');

                    foreach ($query_pessoa_familia->result() as $pessoa_familia) {
                        if (!in_array($pessoa_familia->id_familia, $array_ids_familias)) {
                            array_push($array_ids_familias, $pessoa_familia->id_familia);
                        }
                    }
                }


                if (count($array_ids_familias) > 0) {
                    //carregando as familias
                    $this->db->distinct();
                    $this->db->where_in('id', $array_ids_familias);
                    $query_familias = $this->db->get('familia');
                    $familias = $query_familias->result();

                    $array_resultado = array(
                        'familias' => array(),
                        'pessoas' => array()
                    );

                    foreach ($familias as $fam) {
                        array_push($array_resultado['familias'], $fam);
                    }

                    foreach ($pessoas as $pess) {
                        if (!in_array($this->get_id_familia_por_pessoa($pess->id), $array_resultado['pessoas'])) {
                            $array_resultado['pessoas'][$this->get_id_familia_por_pessoa($pess->id)] = array();
                        }
                    }

                    foreach ($pessoas as $pess) {
                        array_push($array_resultado['pessoas'][$this->get_id_familia_por_pessoa($pess->id)], $pess);
                    }

                    return $array_resultado;
                }
            }
        }

        return NULL;
    }

    //Pegar pessoas e familias por faixa etária
    public function get_beneficiarios_por_faixa_etaria_pessoa($idade_inicial, $idade_final) {
        //Carregando pessoas pela faixa etaria
        $this->db->distinct();
        $this->db->select('pessoa.*, TIMESTAMPDIFF(YEAR, pessoa.data_nascimento, NOW()) AS idade');
        $query_pessoas_faixa = $this->db->get('pessoa');

        if (count($query_pessoas_faixa->result()) > 0) {
            $temp_pessoas = $query_pessoas_faixa->result();
            $pessoas = array();

            foreach ($temp_pessoas as $tpess) {
                if (($tpess->idade <= $idade_final) && ($tpess->idade >= $idade_inicial)) {
                    array_push($pessoas, $tpess);
                }
            }
        }

        if (count($pessoas) > 0) {
            $array_ids_pessoas = array();
            $array_ids_familias = array();
            foreach ($pessoas as $pessoa) {
                $this->db->where_in('id_pessoa', $pessoa->id);
                $query_pessoa_familia = $this->db->get('familia_pessoa');

                foreach ($query_pessoa_familia->result() as $pessoa_familia) {
                    if (!in_array($pessoa_familia->id_familia, $array_ids_familias)) {
                        array_push($array_ids_familias, $pessoa_familia->id_familia);
                    }
                }
            }


            if (count($array_ids_familias) > 0) {
                //carregando as familias
                $this->db->distinct();
                $this->db->where_in('id', $array_ids_familias);
                $query_familias = $this->db->get('familia');
                $familias = $query_familias->result();

                $array_resultado = array(
                    'familias' => array(),
                    'pessoas' => array()
                );

                foreach ($familias as $fam) {
                    array_push($array_resultado['familias'], $fam);
                }

                foreach ($pessoas as $pess) {
                    if (!in_array($this->get_id_familia_por_pessoa($pess->id), $array_resultado['pessoas'])) {
                        $array_resultado['pessoas'][$this->get_id_familia_por_pessoa($pess->id)] = array();
                    }
                }

                foreach ($pessoas as $pess) {
                    array_push($array_resultado['pessoas'][$this->get_id_familia_por_pessoa($pess->id)], $pess);
                }

                return $array_resultado;
            }
        }

        return NULL;
    }

    //Pegar pessoas e familias por crianças
    public function get_beneficiarios_por_crianca() {
        //Carregando pessoas por crianças
        $this->db->distinct();
        $this->db->select('pessoa.*, TIMESTAMPDIFF(YEAR, pessoa.data_nascimento, NOW()) AS idade');
        $query_pessoas_criancas = $this->db->get('pessoa');

        if (count($query_pessoas_criancas->result()) > 0) {
            $temp_pessoas = $query_pessoas_criancas->result();
            $pessoas = array();

            foreach ($temp_pessoas as $tpess) {
                if ($tpess->idade < 12) {
                    array_push($pessoas, $tpess);
                }
            }
        }

        if (count($pessoas) > 0) {
            $array_ids_pessoas = array();
            $array_ids_familias = array();
            foreach ($pessoas as $pessoa) {
                $this->db->where('id_pessoa', $pessoa->id);
                $query_pessoa_familia = $this->db->get('familia_pessoa');

                foreach ($query_pessoa_familia->result() as $pessoa_familia) {
                    if (!in_array($pessoa_familia->id_familia, $array_ids_familias)) {
                        array_push($array_ids_familias, $pessoa_familia->id_familia);
                    }
                }
            }


            if (count($array_ids_familias) > 0) {
                //carregando as familias
                $this->db->where_in('id', $array_ids_familias);
                $query_familias = $this->db->get('familia');
                $familias = $query_familias->result();

                $array_resultado = array(
                    'familias' => array(),
                    'pessoas' => array()
                );

                foreach ($familias as $fam) {
                    array_push($array_resultado['familias'], $fam);
                }

                foreach ($pessoas as $pess) {
                    if (!in_array($this->get_id_familia_por_pessoa($pess->id), $array_resultado['pessoas'])) {
                        $array_resultado['pessoas'][$this->get_id_familia_por_pessoa($pess->id)] = array();
                    }
                }

                foreach ($pessoas as $pess) {
                    array_push($array_resultado['pessoas'][$this->get_id_familia_por_pessoa($pess->id)], $pess);
                }

                return $array_resultado;
            }
        }

        return NULL;
    }

    //Pegar pessoas e familias por idoso
    public function get_beneficiarios_por_idoso() {
        //Carregando pessoas por idoso
        $this->db->distinct();
        $this->db->select('pessoa.*, TIMESTAMPDIFF(YEAR, pessoa.data_nascimento, NOW()) AS idade');
        $query_pessoas_idosos = $this->db->get('pessoa');

        if (count($query_pessoas_idosos->result()) > 0) {
            $temp_pessoas = $query_pessoas_idosos->result();
            $pessoas = array();

            foreach ($temp_pessoas as $tpess) {
                if ($tpess->idade >= 60) {
                    array_push($pessoas, $tpess);
                }
            }
        }

        if (count($pessoas) > 0) {
            $array_ids_pessoas = array();
            foreach ($pessoas as $pessoa) {
                $this->db->where_in('id_pessoa', $pessoa->id);
                $query_pessoa_familia = $this->db->get('familia_pessoa');

                $array_ids_familias = array();
                foreach ($query_pessoa_familia->result() as $pessoa_familia) {
                    if (!in_array($pessoa_familia->id_familia, $array_ids_familias)) {
                        array_push($array_ids_familias, $pessoa_familia->id_familia);
                    }
                }
            }


            if (count($array_ids_familias) > 0) {
                //carregando as familias
                $this->db->distinct();
                $this->db->where_in('id', $array_ids_familias);
                $query_familias = $this->db->get('familia');
                $familias = $query_familias->result();

                $array_resultado = array(
                    'familias' => array(),
                    'pessoas' => array()
                );

                foreach ($familias as $fam) {
                    array_push($array_resultado['familias'], $fam);
                }

                foreach ($pessoas as $pess) {
                    if (!in_array($this->get_id_familia_por_pessoa($pess->id), $array_resultado['pessoas'])) {
                        $array_resultado['pessoas'][$this->get_id_familia_por_pessoa($pess->id)] = array();
                    }
                }

                foreach ($pessoas as $pess) {
                    array_push($array_resultado['pessoas'][$this->get_id_familia_por_pessoa($pess->id)], $pess);
                }

                return $array_resultado;
            }
        }

        return NULL;
    }

}
