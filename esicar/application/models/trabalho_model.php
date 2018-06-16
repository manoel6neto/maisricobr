<?php

class trabalho_model extends CI_Model {

    function get_all() {
        $this->db->order_by('idTrabalho', 'desc');
        $query = $this->db->get('trabalho');

        return $query->result();
    }

    function get_metas_proposta($idProposta) {
        $this->db->where('Proposta_idProposta', $idProposta);
        $query = $this->db->get('meta');

        if ($query->num_rows > 0) {
            return $query->result();
        }

        return NULL;
    }

    public function get_dummy_programs($id_cnpj, $orgao) {

        //Pelo orgão
        $this->db->select('pv.*');
        $this->db->join('siconv_beneficiario sb', 'sb.codigo_programa = pv.codigo');
        $this->db->join('orgaos og', 'og.codigo = SUBSTR(pv.codigo, 1, 5)');
        $this->db->where("REPLACE(REPLACE(REPLACE(sb.cnpj, '/', ''), '-', ''), '.', '') = $id_cnpj");
        $this->db->where('og.codigo', $orgao);
        if ($this->session->userdata('sistema') == 'M') {
            //$this->db->like('pv.atende', 'municipal');
        } else if ($this->session->userdata('sistema') == 'E') {
            $this->db->like('pv.atende', 'estadual');
        }

        //$this->db->where('excluido is null');
        $query = $this->db->get('programas_vigencia pv');

        //Pelo orgão vinculado
        $this->db->select('pv.*');
        $this->db->join('siconv_beneficiario sb', 'sb.codigo_programa = pv.codigo');
        $this->db->join('orgaos og', 'og.codigo = SUBSTR(pv.codigo, 1, 5)');
        $this->db->where("REPLACE(REPLACE(REPLACE(sb.cnpj, '/', ''), '-', ''), '.', '') = $id_cnpj");
        $this->db->where('og.codigo', $orgao);
        if ($this->session->userdata('sistema') == 'M') {
            //$this->db->like('pv.atende', 'municipal');
        } else if ($this->session->userdata('sistema') == 'E') {
            $this->db->like('pv.atende', 'estadual');
        }

        //$this->db->where('excluido is null');
        $query2 = $this->db->get('programas_vigencia pv');

        $query = $query->result();
        $query2 = $query2->result();

        if ($query != null and $query2 != null) {
            $query_final = array_merge($query, $query2);
        } else if ($query != null) {
            $query_final = $query;
        } else {
            $query_final = $query2;
        }

        $beneficiario = array();
        if (count($query_final) > 0) {
            $beneficiario = $query_final;
        }

        //get estado
        $this->db->select('municipio_uf_sigla');
        $this->db->where("REPLACE(REPLACE(REPLACE(proponente_siconv.cnpj, '/', ''), '-', ''), '.', '') = $id_cnpj");
        $estado = $this->db->get('proponente_siconv');
        $estado = $estado->result();

        //get atende
        $this->db->select('natureza_juridica');
        $this->db->where("REPLACE(REPLACE(REPLACE(proponente_siconv.cnpj, '/', ''), '-', ''), '.', '') = $id_cnpj");
        $atende = $this->db->get('proponente_siconv')->row(0)->natureza_juridica;


//        $this->db->select('pv.*');
//        $this->db->join('orgaos og', 'og.nome = pv.orgao');
//        $this->db->where('og.codigo', $orgao);
//        $this->db->where_not_in('pv.codigo', $all_ben_cod);
//        $this->db->like('pv.atende', 'Municipal');
//        $this->db->like('pv.estados', 'todos');
        //$this->db->or_like('pv.estados', $estado[0]->municipio_uf_sigla);
//        $query = $this->db->get('programas_vigencia pv');
//        $query = $this->db->query("SELECT pv.* from programas_vigencia AS pv JOIN (orgaos AS og) ON (og.nome = pv.orgao) WHERE og.codigo = '{$orgao}' AND pv.qualificacao LIKE '%Proposta Voluntária%' AND pv.atende LIKE '%municipal%' AND (pv.estados LIKE '%todos%' OR pv.estados LIKE '%{$estado[0]->municipio_uf_sigla}%')");
        //Pelo orgão
        if ($this->session->userdata('sistema') == 'M') {
            $query = $this->db->query("SELECT pv.* from programas_vigencia AS pv JOIN (orgaos AS og) ON (og.codigo = SUBSTR(pv.codigo, 1, 5)) WHERE og.codigo = '{$orgao}' AND pv.qualificacao LIKE '%Proposta Voluntária%' AND (pv.estados LIKE '%todos%' OR pv.estados LIKE '%{$estado[0]->municipio_uf_sigla}%') AND atende LIKE '%$atende%'");
        } else if ($this->session->userdata('sistema') == 'E') {
            $query = $this->db->query("SELECT pv.* from programas_vigencia AS pv JOIN (orgaos AS og) ON (og.codigo = SUBSTR(pv.codigo, 1, 5)) WHERE og.codigo = '{$orgao}' AND pv.atende LIKE '%estadual%' AND pv.qualificacao LIKE '%Proposta Voluntária%' AND (pv.estados LIKE '%todos%' OR pv.estados LIKE '%{$estado[0]->municipio_uf_sigla}%') AND atende LIKE '%$atende%'");
        } else if ($this->session->userdata('sistema') == 'P') {
            $query = $this->db->query("SELECT pv.* from programas_vigencia AS pv JOIN (orgaos AS og) ON (og.codigo = SUBSTR(pv.codigo, 1, 5)) WHERE og.codigo = '{$orgao}' AND pv.qualificacao LIKE '%Proposta Voluntária%' AND atende LIKE '%$atende%'");
        }


        //Pelo orgao vinculado
        /* if ($this->session->userdata('sistema') == 'M') {
          $query2 = $this->db->query("SELECT pv.* from programas_vigencia AS pv JOIN (orgaos AS og) ON (og.nome = pv.orgao_vinculado) WHERE og.codigo = '{$orgao}' AND pv.qualificacao LIKE '%Proposta Voluntária%' AND (pv.estados LIKE '%todos%' OR pv.estados LIKE '%{$estado[0]->municipio_uf_sigla}%') AND atende LIKE '%$atende%'");
          } else if ($this->session->userdata('sistema') == 'E') {
          $query2 = $this->db->query("SELECT pv.* from programas_vigencia AS pv JOIN (orgaos AS og) ON (og.nome = pv.orgao_vinculado) WHERE og.codigo = '{$orgao}' AND pv.atende LIKE '%estadual%' AND pv.qualificacao LIKE '%Proposta Voluntária%' AND (pv.estados LIKE '%todos%' OR pv.estados LIKE '%{$estado[0]->municipio_uf_sigla}%') AND atende LIKE '%$atende%'");
          } else if ($this->session->userdata('sistema') == 'P') {
          $query2 = $this->db->query("SELECT pv.* from programas_vigencia AS pv JOIN (orgaos AS og) ON (og.nome = pv.orgao_vinculado  WHERE og.codigo = '{$orgao}' AND pv.qualificacao LIKE '%Proposta Voluntária%' AND atende LIKE '%$atende%'");
          } */

        $query = $query->result();
        //$query2 = $query2->result();

        /* if ($query != null and $query2 != null) {
          $query_final = array_merge($query, $query2);
          } else if ($query != null) {
          $query_final = $query;
          } else {
          $query_final = $query2;
          } */
        $query_final = $query;

        //TODO WRITE COMPLETE QUERY
        if ($beneficiario != null && count($beneficiario) > 0) {
            if (count($query_final) > 0) {
                $beneficiario = array_merge($beneficiario, $query_final);
            }
        } else {
            if (count($query_final) > 0) {
                $beneficiario = $query_final;
            }
        }

        if (count($beneficiario) > 0) {
            return array_unique($beneficiario, SORT_REGULAR);
        } else {
            return null;
        }
    }

    function verifica_trabalhos($id) {
        $this->load->model('proposta_model');
        $proposta = $this->proposta_model->get_by_id($id);

        $valor_percent = $proposta->percentual * 0.01 * $proposta->valor_global;
        //alert(valor_percent);
        if (number_format($valor_percent, 2, '.', '') > number_format($proposta->total_contrapartida, 2, '.', "")) {
            $this->alert('Valor de contrapartida abaixo do percentual de contrapartida');
            return false;
        }

        $this->db->flush_cache();

        $this->db->where('id_proposta', $id);
        $query = $this->db->get('programa_proposta');
        if ($query->num_rows > 0) {
            foreach ($query->result() as $prog) {
                if ($prog->objeto == null || $prog->objeto == '' || $prog->qualificacao == null || $prog->qualificacao == '') {
                    $this->alert('Existem programas sem objeto ou qualificacao selecionados.');
                    return false;
                }
            }
        }

        $this->db->flush_cache();

        if (number_format(doubleval($proposta->valor_global), 2, '.', "") != number_format(doubleval($proposta->total_contrapartida) + doubleval($proposta->repasse), 2, '.', "")) {
            $this->alert('Valor global deve ser igual ao repasse + contrapartida');
            return false;
        }

        $this->db->flush_cache();

        $this->db->where('Proposta_idProposta', $id);
        $query = $this->db->get('justificativa');
        if (count($query->result()) < 1) {
            $this->alert("A justificativa deve conter dados para finalizar a proposta.");
            return false;
        }

        $this->db->flush_cache();

        if (trim($query->row(0)->Justificativa) == '' || trim($query->row(0)->objeto) == '') {
            $this->alert("A justificativa deve conter os dados obrigatórios para finalizar a proposta.");
            return false;
        }

        $this->db->flush_cache();

        $this->db->where('Proposta_idProposta', $id);
        $this->db->where("(id_programa is null or id_programa = '')", '', FALSE);
        $num_meta_sem_programa = $this->db->get('meta')->num_rows();
        if ($num_meta_sem_programa > 0) {
            $this->alert("Existem uma ou mais metas não vinculadas a um programa.");
            return false;
        }

        $this->db->flush_cache();

        $this->db->where('Proposta_idProposta', $id);
        $this->db->where("(id_programa is null or id_programa = '')", '', FALSE);
        $num_despesa_sem_programa = $this->db->get('despesa')->num_rows();
        if ($num_despesa_sem_programa > 0) {
            $this->alert("Existem um ou mais itens do plano detalhado não vinculados a um programa.");
            return false;
        }

        $this->db->flush_cache();

        $valor_global = $proposta->valor_global - $proposta->contrapartida_bens;
        $valor_global_com_bens = $proposta->valor_global;
        $total_soma = 0;

        $this->db->where('Proposta_idProposta', $id);
        $query = $this->db->get('meta');

        $this->db->flush_cache();

        $indice_meta = 1;
        foreach ($query->result() as $obj) {
            $total_soma += $obj->total;

            $this->db->where('Meta_idMeta', $obj->idMeta);
            $query1 = $this->db->get('etapa');

            $this->db->flush_cache();

            //etapas
            $options_obj = array();
            $total_etapa = 0;

            foreach ($query1->result() as $obj1) {
                $total_etapa += $obj1->total;
            }

            if (number_format(doubleval($obj->total), 2, '.', "") != number_format(doubleval($total_etapa), 2, '.', "")) {
                $this->alert("Valor da meta $indice_meta = " . $obj->total . " e o valor das etapas = " . $total_etapa . ". Reveja as etapas para que o valor seja igual o valor da meta.");
                return false;
            }
            $indice_meta++;
        }
        if (number_format(doubleval($valor_global), 2, '.', "") != number_format(doubleval($total_soma), 2, '.', "")) {
            $this->alert("Valor global (retirando a contrapartida de bens) = " . $valor_global . " e o valor das metas = " . $total_soma . ". Reveja as metas para que o valor seja igual o valor da proposta.");
            return false;
        }

        //cronograma
        $total = 0;
        $repasse = $proposta->repasse;
        $contrapartida_financeira = $proposta->contrapartida_financeira;
        $total_concedente = 0;
        $total_convenente = 0;
        $chave = 1;
        $flag = false;
        $this->db->where('Proposta_idProposta', $id);
        $query = $this->db->get('cronograma');

        $this->db->flush_cache();

        foreach ($query->result() as $obj) {
            $totalDesmbolsoUnitario = 0;

            $total += $obj->parcela;
            $totalDesmbolsoUnitario += $obj->parcela;
            if ($obj->responsavel == 'CONCEDENTE')
                $total_concedente += $obj->parcela;
            else
                $total_convenente += $obj->parcela;
            $metas = $this->obter_metas_proposta($id);

            $valorMeta = 0;
            foreach ($metas as $meta_aux) {
                $meta = $this->obter_meta_cronograma($obj->idCronograma, $meta_aux->idMeta);
                $etapas = $this->obter_etapas_meta_proposta($meta_aux->idMeta);
                $idCronograma = $this->obter_meta_cronograma_id($obj->idCronograma, $meta_aux->idMeta);
                $total_etapa = 0;
                $total_etapa1 = 0;

                foreach ($etapas as $etapa) {
                    $valor_aux = $this->obter_etapa_cronograma_valor($idCronograma, $etapa->idEtapa);
                    if ($valor_aux != null) {
                        $total_etapa += $valor_aux;
                        $total_etapa1 = $total_etapa;
                    }
                }

                if (array_key_exists('valor', $meta)) {
                    if ($meta->valor > 0) {
                        if (number_format(round(doubleval($meta->valor), 2), 2, '.', "") != number_format(round(doubleval($total_etapa1), 2), 2, '.', "")) {
                            $this->alert("Cronograma de desembolso: Valor da meta " . $chave . " = " . $meta->valor . " e o valor das etapas = " . $total_etapa1 . ". Reveja as etapas para que o valor seja igual o valor da meta.");
                            return false;
                        }

                        $valorMeta += round($meta->valor, 3);
                    }
                }

                if (array_key_exists('total', $meta)) {
                    if ($meta->total > 0) {
                        if (number_format(round(doubleval($meta->total), 2), 2, '.', "") != number_format(round(doubleval($total_etapa1), 2), 2, '.', "")) {
                            $this->alert("Cronograma de desembolso: Valor da meta " . $chave . " = " . $meta->total . " e o valor das etapas = " . $total_etapa1 . ". Reveja as etapas para que o valor seja igual o valor da meta.");
                            return false;
                        }

                        $valorMeta += round($meta->valor, 3);
                    }
                }
            }

            $chave++;

            if (number_format(doubleval($totalDesmbolsoUnitario), 2, '.', "") != number_format(doubleval($valorMeta), 2, '.', "")) {
                $this->alert("Existem desembolsos não associados a metas. É necessário realizar esta associação.");
                return false;
            }
        }

        $this->db->select('SUM(total) as TOTAL_OBRAS');
        $this->db->where('Proposta_idProposta', $id);
        $obras = $this->db->get('despesa')->row(0);

        $this->db->flush_cache();

        if (number_format(doubleval($obras->TOTAL_OBRAS), 2, ".", "") != number_format(doubleval($valor_global_com_bens), 2, '.', "")) {
            $this->alert("O total do plano detalhado ($obras->TOTAL_OBRAS) deve ser igual ao valor global($valor_global_com_bens)");
            return false;
        }

        //O total das parcelas desembolsadas pelo concedente deve ser inferior ao valor total de repasse

        if (number_format(doubleval($contrapartida_financeira), 2, '.', "") != number_format(doubleval($total_convenente), 2, '.', "")) {
            $this->alert("O total das parcelas desembolsadas pelo convenente ($total_convenente) deve ser igual ao valor de contrapartida financeira ($contrapartida_financeira).");
            return false;
        } else if (number_format(doubleval($repasse), 2, '.', "") < number_format(doubleval($total_concedente), 2, '.', "")) {
            $this->alert("O total das parcelas desembolsadas pelo concedente ($total_concedente) deve ser inferior ou igual ao valor total de repasse($repasse).");
            return false;
        } else if (number_format(doubleval($valor_global), 2, '.', "") != number_format(doubleval($total), 2, '.', "")) {
            $this->alert("Valor global = " . $valor_global . " e o valor das metas = " . $total . ". Reveja as metas para que o valor seja igual o valor da proposta.");
            return false;
        }

        return true;
    }

    function obter_enderecos($id) {
        $this->db->distinct();
        $this->db->where('proposta.idGestor', $id);
        $this->db->where('proposta.ativo', '1');
        $this->db->select('meta.endereco');
        $this->db->select('meta.cep');
        $this->db->from('meta');
        $this->db->join('proposta', 'meta.Proposta_idProposta = proposta.idProposta', 'left');
        $query = $this->db->get();
        return $query->result();
    }

    function atualiza_justificativa($id, $cidade) {

        $justificativa = $this->obter_justificativa_por_proposta($id);
        //echo $justificativa->idJustificativa; die();
        if (isset($justificativa->idJustificativa) !== false) {
            $dados = $this->obtem_dados_cidade($cidade);
            $just = str_replace("[Cidade]", $dados->Cidade, $justificativa->Justificativa);
            $just = str_replace("[Estado]", $dados->Estado, $just);
            $just = str_replace("[Mesorregiao]", $dados->Mesorregiao, $just);
            $just = str_replace("[Territorio_Cidadania]", $dados->Territorio_Cidadania, $just);
            $just = str_replace("[populacao]", $dados->populacao, $just);
            $just = str_replace("[Area_Territorial]", $dados->Area_Territorial, $just);
            $just = str_replace("[Densidade]", $dados->Densidade, $just);
            $just = str_replace("[Distancia_capital]", $dados->Distancia_capital, $just);
            $just = str_replace("[Per_capita]", $dados->Per_capita, $just);

            $options = array(
                'Proposta_idProposta' => $id,
                'Justificativa' => $just
            );
            $idJustificativa = $justificativa->idJustificativa;
            $this->add_justificativa($options, $idJustificativa);
        }
    }

    function obtem_estado($cnpj) {
        $nome = '';
        $cnpj = preg_replace("/[^0-9\s]/", "", $cnpj);

        $jsonurl = "http://api.convenios.gov.br/siconv/dados/proponente/$cnpj.json";

        $json = file_get_contents($jsonurl);

        if (trim($json) == '' || !isset($json)) {
            $jsonurl = "http://api.convenios.gov.br/siconv/dados/proponente/$cnpj.html";
            $json = file_get_contents($jsonurl);

            $json = $this->removeSpaceSurplus($json);
            $municpio = $this->getTextBetweenTags($json, "<dt>Municipio<\/dt> <dd><a href=\"", "\">");

            $json_final = file_get_contents($municpio[0]);
            $json_final = $this->removeSpaceSurplus($json_final);
            $estado = $this->getTextBetweenTags($json_final, "<\/dd><dt>Nome<\/dt><dd>", "<\/dd>");
            return $estado[0];
        }
        if (trim($nome) == '') {

            $json_output = json_decode($json);
            $municpio = $json_output->proponentes[0]->municipio->Municipio->href;

            $json_final = json_decode(file_get_contents($municpio . ".json"));
            return $json_final->municipios[0]->uf->nome;
        }
        return null;
    }

    function atualiza_proponente() {
        $query = $this->db->get('proponentes_municipios');
        foreach ($query->result() as $prop) {
            $this->db->where('cnpj', $prop->cnpj);
            $options = array(
                'estado' => $this->obtem_estado($prop->cnpj)
            );
            $this->db->update('proponentes_municipios', $options);
        }
    }

    function obtem_dados_cidade($string) {
        $id = substr($string, 0, 2) . '.' . substr($string, 2, 3) . '.' . substr($string, 5, 3) . '/' . substr($string, 8, 4) . '-' . substr($string, 12, 2);
        $this->db->like('cnpj', $id);
        $query = $this->db->get('proponentes_municipios');
        if (isset($query->row(0)->nome) !== false) {

            $nome_cidade = str_replace("PREFEITURA MUNICIPAL DE ", "", $query->row(0)->nome);
            $nome_cidade = str_replace("PREFEITURA MUNICIPAL", "", $nome_cidade);
            $nome_cidade = str_replace("MUNICIPIO DE ", "", $nome_cidade);
            $nome_cidade = trim(str_replace("PREFEITURA", "", $nome_cidade));

            $this->db->like('Cidade', $nome_cidade);
            $query1 = $this->db->get('dados_cidade');

            return $query1->row(0);
        }
    }

    function obter_por_usuario($id) {
        $this->db->where('Pessoa_idPessoa', $id);
        $this->db->from('proposta');
        $this->db->join('trabalho', 'trabalho.id_correspondente = proposta.idProposta', 'right');
        $this->db->where('proposta.ativo', 1);
        $this->db->order_by('idTrabalho', 'desc');
        $query = $this->db->get();

        return $query->result();
    }

    function obter_cidades_siconv() {
        $this->db->distinct();
        $this->db->order_by('Nome', 'asc');
        $query = $this->db->get('cidades_siconv');
        return $query->result();
    }

    function obter_uf_siconv() {
        $query = $this->db->get('unidade_fornecimento');
        return $query->result();
    }

    function verifica_uf_siconv($codigo) {
        $this->db->where('codigo', $codigo);
        $query = $this->db->get('unidade_fornecimento');

        if (count($query->result()) > 0)
            return true;
        return false;
    }

    function verifica_natureza_siconv($id, $codigo) {
        $this->db->where('codigo', $codigo);
        switch ($id) {
            case '1':
                $query = $this->db->get('bens');
                break;
            case '2':
                $query = $this->db->get('servicos');
                break;
            case '3':
                $query = $this->db->get('obras');
                break;
            case '4':
                $query = $this->db->get('tributos');
                break;
            case '5':
                $query = $this->db->get('outros');
                break;
            case '6':
                $query = $this->db->get('outros');
                break;
        }
        if (count($query->result()) > 0)
            return true;
        return false;
    }

    function obter_natureza_siconv($id) {
        $query = null;
        switch ($id) {
            case '1':
                $query = $this->db->get('bens');
                break;
            case '2':
                $query = $this->db->get('servicos');
                break;
            case '3':
                $query = $this->db->get('obras');
                break;
            case '4':
                $query = $this->db->get('tributos');
                break;
            case '5':
                $query = $this->db->get('outros');
                break;
            case '6':
                $query = $this->db->get('outros');
                break;
        }
        return $query->result();
    }

    function obter_por_gestor($id) {
        $this->db->where('ativo', 1);
        $query = $this->db->get('proposta');
        $query->row(0)->nome;

        $this->db->where('Pessoa_idPessoa', $id);
        $this->db->order_by('idTrabalho', 'desc');
        $query = $this->db->get('trabalho');
        return $query->result();
    }

    function obter_por_trabalho($cod_programa, $cod_cidades, $proposta, $usuario, $trabalho) {
        if ($cod_programa != '' && $cod_programa != '0')
            $this->db->where('codigo_programa', $cod_programa);
        if ($cod_cidades != '' && $cod_cidades != '0')
            $this->db->like('cidade', $cod_cidades);
        if ($proposta !== null && $proposta != '0')
            $this->db->where('id_correspondente', $proposta);
        if ($trabalho != '' && $trabalho != '0' && $trabalho !== null)
            $this->db->where('Tipo_trabalho_idTrabalho', $trabalho);
        $this->db->from('proposta');
        $this->db->join('trabalho', 'trabalho.id_correspondente = proposta.idProposta', 'right');
        $this->db->where('proposta.ativo', 1);
        $this->db->order_by('idTrabalho', 'desc');
        $query = $this->db->get();
        return $query->result();
    }

    function obter_por_cidade($cod_programa, $cod_cidades, $proposta, $usuario) {
        $this->db->select('*');
        $this->db->from('proposta');
        $this->db->join('trabalho', 'trabalho.id_correspondente = proposta.idProposta', 'right');
        $this->db->like('proposta.cidade', $cod_cidades);
        $this->db->where('proposta.ativo', 1);
        if ($cod_programa != '' && $cod_programa != '0')
            $this->db->where('codigo_programa', $cod_programa);
        if ($proposta !== null && $proposta != '0')
            $this->db->where('id_correspondente', $proposta);
        $query = $this->db->get();
        //$this->db->order_by('idTrabalho', 'desc');
        //echo $this->db->last_query(); die();
        return $query->result();
    }

    function obter_por_programa($cod_programa, $proposta, $usuario) {
        $this->db->select('*');
        $this->db->from('proposta');
        $this->db->join('trabalho', 'trabalho.id_correspondente = proposta.idProposta', 'right');
        $this->db->where('proposta.codigo_programa ', $cod_programa);
        $this->db->where('proposta.ativo', 1);
        if ($proposta !== null && $proposta != '0')
            $this->db->where('id_correspondente', $proposta);
        $this->db->order_by('idTrabalho', 'desc');
        $query = $this->db->get();

        return $query->result();
    }

    function obter_proposta_por_usuario($cod_programa, $cod_cidades, $proposta, $usuario, $proposta_id) {
        if ($cod_programa != '' && $cod_programa != '0')
            $this->db->where('codigo_programa', $cod_programa);
        if ($cod_cidades != '' && $cod_cidades != '0')
            $this->db->where('cidade', $cod_cidades);
        if ($proposta !== null && $proposta != '0')
            $this->db->where('id_correspondente', $proposta);
        if ($proposta_id !== null && $proposta_id != '0')
            $this->db->where('id_correspondente', $proposta_id);
        $this->db->from('proposta');
        $this->db->join('trabalho', 'trabalho.id_correspondente = proposta.idProposta', 'right');
        $this->db->where('proposta.ativo', 1);
        $this->db->order_by('idTrabalho', 'desc');
        $query = $this->db->get();
        return $query->result();
    }

    function obter_por_proposta_gestor($proposta) {
        $this->db->where('id_correspondente', $proposta);
        $this->db->from('proposta');
        $this->db->join('trabalho', 'trabalho.id_correspondente = proposta.idProposta', 'right');
        $this->db->where('proposta.ativo', 1);
        $this->db->order_by('idTrabalho', 'desc');
        $query = $this->db->get();

        return $query->result();
    }

    function obter_por_trabalho_e_proposta($cod_programa, $cod_cidades, $proposta, $usuario, $trabalho, $proposta_id) {
        if ($cod_programa != '' && $cod_programa != '0')
            $this->db->where('codigo_programa', $cod_programa);
        if ($cod_cidades != '' && $cod_cidades != '0')
            $this->db->where('cidade', $cod_cidades);
        if ($proposta !== null && $proposta != '0')
            $this->db->where('id_correspondente', $proposta);
        if ($proposta_id !== null && $proposta_id != '0')
            $this->db->where('id_correspondente', $proposta_id);
        $this->db->where('Tipo_trabalho_idTrabalho', $trabalho);
        $this->db->from('proposta');
        $this->db->join('trabalho', 'trabalho.id_correspondente = proposta.idProposta', 'right');
        $this->db->where('proposta.ativo', 1);
        $this->db->order_by('idTrabalho', 'desc');
        $query = $this->db->get();
        return $query->result();
    }

    function obter_por_proposta($idTrabalho, $idProposta) {
        $this->db->where('Tipo_trabalho_idTrabalho', $idTrabalho);
        $this->db->where('id_correspondente', $idProposta);
        $this->db->from('proposta');
        $this->db->join('trabalho', 'trabalho.id_correspondente = proposta.idProposta', 'right');
        $this->db->where('proposta.ativo', 1);
        $this->db->order_by('idTrabalho', 'desc');
        $query = $this->db->get();

        return $query->result();
    }

    function obter_status_trabalho($id) {
        $this->db->where('idstatus', $id);
        $query = $this->db->get('status');

        return $query->row(0)->nome;
    }

    function obter_tipo_trabalho($id) {
        $this->db->where('idTrabalho', $id);
        $query = $this->db->get('tipo_trabalho');
        return $query->row(0)->nome;
    }

    function obter_nomenclatura_trabalho($id) {
        $this->db->where('idTrabalho', $id);
        $query = $this->db->get('tipo_trabalho');
        return $query->row(0)->nomenclatura;
    }

    function obter_tipos_trabalho() {
        $query = $this->db->get('tipo_trabalho');
        return $query->result();
    }

    function obter_areas() {
        $query = $this->db->get('area');
        return $query->result();
    }

    function obter_log_trabalho($id) {
        $this->db->where('Trabalho_idTrabalho', $id);
        $query = $this->db->get('log_trabalho');
        return $query->result();
    }

    function obter_observacao($id) {
        $logs = $this->obter_log_trabalho($id);
        $obs = null;
        foreach ($logs as $log) {
            $obs = $log->observacao;
        }
        return $obs;
    }

    function obter_cronograma_por_id($id) {
        $this->db->where('idCronograma', $id);
        $query = $this->db->get('cronograma');
        return $query->row(0);
    }

    function obter_justificativa_por_id($id) {
        $this->db->where('idJustificativa', $id);
        $query = $this->db->get('justificativa');
        return $query->row(0);
    }

    function obter_justificativa_por_proposta($id) {
        $this->db->where('Proposta_idProposta ', $id);
        $query = $this->db->get('justificativa');
        return $query->row(0);
    }

    function obter_meta_por_id($id) {
        $this->db->where('idMeta', $id);
        $query = $this->db->get('meta');
        //echo $this->db->last_query()."<br>";
        return $query->row(0);
    }

    function obter_endereco_por_id($id) {
        $this->db->where('Proposta_idProposta', $id);
        $query = $this->db->get('endereco');
        return $query->row(0);
    }

    function obter_meta_id_cronograma($id) {
        if ($id === false)
            return null;
        $this->db->where('Cronograma_idCronograma', $id);
        $query = $this->db->get('cronograma_meta');

        if (isset($query->row(0)->Meta_idMeta) !== false)
            return $query->row(0)->Meta_idMeta;
        return null;
    }

    function obter_meta_cronograma($cronograma, $meta) {

        $this->db->where('Cronograma_idCronograma', $cronograma);
        $this->db->where('Meta_idMeta', $meta);
        $query = $this->db->get('cronograma_meta');

        return $query->row(0);
    }

    function obter_metas_crono($cronograma, $meta) {
        if ($cronograma != NULL)
            $this->db->where('c.Cronograma_idCronograma', $cronograma);
        $this->db->where('m.Proposta_idProposta', $meta);
        $this->db->join('cronograma_meta c', 'c.Meta_idMeta = m.idMeta', 'left');
        $query = $this->db->get('meta m');

        return $query->result();
    }

    function obter_etapas_crono($cronograma, $etapa) {
        if ($cronograma != NULL)
            $this->db->where('c.Cronograma_meta_idCronograma_meta', $cronograma);
        $this->db->where('e.Meta_idMeta', $etapa);
        $this->db->join('cronograma_etapa c', 'c.Etapa_idEtapa = e.idEtapa', 'left');
        $query = $this->db->get('etapa e');

        return $query->result();
    }

    function obter_meta_cronograma_id($cronograma, $meta) {

        $this->db->where('Cronograma_idCronograma', $cronograma);
        $this->db->where('Meta_idMeta', $meta);
        $query = $this->db->get('cronograma_meta');
        if (isset($query->row(0)->idCronograma_meta) !== false)
            return $query->row(0)->idCronograma_meta;
        return null;
    }

    function obter_restante_cronograma_meta($meta, $idcronograma) {
        $this->db->select_sum('Cronograma.valor_meta');
        $this->db->from('cronograma_meta');
        $this->db->join('cronograma', 'cronograma.idCronograma = cronograma_meta.Cronograma_idCronograma', 'right');
        if ($idcronograma !== false)
            $this->db->where('cronograma_meta.Cronograma_idCronograma != ' . $idcronograma);
        $this->db->where('cronograma_meta.Meta_idMeta', $meta);
        $query = $this->db->get();

        if (isset($query->row(0)->valor_meta) !== false)
            return $query->row(0)->valor_meta;
        return null;
    }

    function obter_etapa_cronograma_valor($idcronograma, $idEtapa) {

        $this->db->where('Cronograma_meta_idCronograma_meta', $idcronograma);
        $this->db->where('Etapa_idEtapa', $idEtapa);
        $query = $this->db->get('cronograma_etapa');
        if (isset($query->row(0)->valor) !== false)
            return $query->row(0)->valor;
        return null;
    }

    function obter_meta_cronograma_valor($idcronograma, $idMeta) {
        $this->db->where('Cronograma_idCronograma', $idcronograma);
        $this->db->where('Meta_idMeta', $idMeta);
        $query = $this->db->get('cronograma_meta');
        if (isset($query->row(0)->valor) !== false)
            return $query->row(0)->valor;
        return null;
    }

    function obter_metas_proposta($id, $id_programa = null) {
        if ($id_programa != null)
            $this->db->where('id_programa', $id_programa);
        $this->db->where('Proposta_idProposta', $id);
        $query = $this->db->get('meta');
        return $query->result();
    }

    function obter_metas_trabalho($id) {
        $this->db->where('Tipo_trabalho_idTrabalho', 2);
        $this->db->where('id_correspondente', $id);
        $query = $this->db->get('trabalho');
        return $query->result();
    }

    function obter_cronograma($id) {
        $this->db->where('Proposta_idProposta', $id);
        $this->db->order_by('responsavel', 'ASC');
        $this->db->order_by('idCronograma', 'ASC');
        $query = $this->db->get('cronograma');
        return $query->result();
    }

    function obter_meta_id($id) {

        $this->db->where('idMeta', $id);
        $query = $this->db->get('meta');

        return $query->row(0);
    }

    function obter_etapas_meta_proposta($id) {
        $this->db->where('Meta_idMeta', $id);
        $query = $this->db->get('etapa');
        return $query->result();
    }

    function obter_restante_etapa($id, $soExibe = false, $round = false) {
        $etapa = $this->obter_etapa_por_id($id);
        $this->db->where('Etapa_idEtapa', $id);

        $query = $this->db->get('cronograma_etapa');
        //echo $this->db->last_query();
        $soma = 0;

        foreach ($query->result() as $trabalho) {
            if ($round)
                $soma += round($trabalho->valor, 2);
            else
                $soma += $trabalho->valor;
        }
        //echo $soma; die();
        if (!$soExibe)
            return number_format(doubleval($etapa->total), 2, '.', "") - number_format($soma, 2, '.', "");
        else {
            if ($round)
                return number_format($soma, 2, '.', "");
            else
                return $soma;
        }
    }

    function obter_restante_meta($id, $soExibe = false) {
        $etapa = $this->obter_meta_por_id($id);
        $this->db->where('Meta_idMeta', $id);
        $query = $this->db->get('cronograma_meta');
        //echo $this->db->last_query()."<br>";
        $soma = 0;

        foreach ($query->result() as $trabalho) {
            $soma += doubleval($trabalho->valor);
        }
        //echo $soma; die();
        if (!$soExibe)
            return number_format(doubleval($etapa->total), 2, '.', "") - number_format($soma, 2, '.', "");
        else
            return number_format($soma, 2, '.', "");
    }

    function obter_etapa_por_id($id) {
        $this->db->where('idEtapa', $id);
        $query = $this->db->get('etapa');
        return $query->row(0);
    }

    function obter_despesas($id, $natureza = 0, $id_programa = null) {
        if ($id_programa != null)
            $this->db->where('id_programa', $id_programa);
        if ($natureza > 0)
            $this->db->where('natureza_aquisicao', $natureza);
        $this->db->where('Proposta_idProposta', $id);
        $query = $this->db->get('despesa');
        return $query->result();
    }

    function obter_despesa_por_id($id) {
        $this->db->where('idDespesa', $id);
        $query = $this->db->get('despesa');
        return $query->row(0);
    }

    function obter_despesas_tipo($id, $tipo) {
        if ($tipo != 0)
            $this->db->where('Tipo_despesa_idTipo_despesa', $tipo);
        $this->db->where('Proposta_idProposta', $id);
        $query = $this->db->get('despesa');
        return $query->result();
    }

    function obter_tipo_despesas() {
        //$this->db->where_not_in('nome', 'Despesa Administrativa');
        $this->db->order_by('idTipo_despesa');
        $query = $this->db->get('tipo_despesa');
        return $query->result();
    }

    function obter_nome_despesa($id) {
        $this->db->where('idTipo_despesa', $id);
        $query = $this->db->get('tipo_despesa');
        return $query->row(0);
    }

    function obter_trabalho($proposta, $usuario, $trabalho) {
        if ($proposta !== null)
            $this->db->where('id_correspondente', $proposta);
        if ($usuario !== null)
            $this->db->where('Pessoa_idPessoa', $usuario);
        $this->db->where('Tipo_trabalho_idTrabalho', $trabalho);
        $query = $this->db->get('trabalho');
        return $query->row(0);
    }

    function envia_email($email, $email1, $texto) {
        $this->load->library('email');

        $this->email->from('marcelomoura@physisbrasil.com.br', 'Physis Brasil');
        $this->email->to($email);
        $this->email->cc($email1);

        $this->email->subject('Aviso de trabalho do siconv');
        $this->email->message($texto);
        $this->email->send();
    }

    function altera_status_trabalho($proposta, $usuario, $trabalho, $status, $obs = null) {
        $trab = $this->obter_trabalho($proposta, $usuario, $trabalho);
        if ($trab->Status_idstatus == $status)//nao efetua alteração se o status for o mesmo
            return;
        if ($proposta !== null)
            $this->db->where('id_correspondente', $proposta);
        if ($usuario !== null)
            $this->db->where('Pessoa_idPessoa', $usuario);
        $this->db->where('Tipo_trabalho_idTrabalho', $trabalho);
        $options = array(
            'Status_idstatus' => $status
        );
        $this->db->update('trabalho', $options);

        $options1 = array(
            'Trabalho_idTrabalho' => $trab->idTrabalho,
            'Status_idstatus' => $status,
            'observacao' => $obs
        );
        $this->db->set('data_acao', 'NOW()', FALSE);
        $this->db->insert('log_trabalho', $options1);

        $this->load->model('proposta_model');
        $this->load->model('usuario_model');

        $prop_email = $this->proposta_model->get_by_id($proposta);
        $status_email = $this->obter_status_trabalho($status);
        $trabalho_email = $this->get_by_id($trab->idTrabalho);
        $usuario_email = $this->usuario_model->get_by_id($trabalho_email->Pessoa_idPessoa);
        $gestor_email = $this->usuario_model->get_by_id($prop_email->idGestor);

        $texto = "Atenção! A proposta \"" . $prop_email->nome . "\" foi alterada para o status \"$status_email\"!
Verifique seus trabalhos para acompanhar o andamento das atividades.
Obrigado,
	Physis Brasil";
        $this->envia_email($usuario_email->email, $gestor_email->email, $texto);
        return $this->db->affected_rows();
    }

    function altera_status_trabalho_id($trabalho, $status, $obs = null) {
        $this->db->where('idTrabalho', $trabalho);
        $query = $this->db->get('trabalho');
        //$resultado = $query->result();
        $resultado = $query->row(0);
        if ($resultado->Tipo_trabalho_idTrabalho == 2 || $resultado->Tipo_trabalho_idTrabalho == 3) {
            $this->db->where('id_correspondente', $resultado->id_correspondente);
            $this->db->where('Tipo_trabalho_idTrabalho', 2);
            $query = $this->db->get('trabalho');
            $trabalho1 = $query->row(0)->idTrabalho;

            $this->db->where('id_correspondente', $resultado->id_correspondente);
            $this->db->where('Tipo_trabalho_idTrabalho', 2);
            $options = array(
                'Status_idstatus' => $status
            );
            $this->db->update('trabalho', $options);

            $this->db->where('id_correspondente', $resultado->id_correspondente);
            $this->db->where('Tipo_trabalho_idTrabalho', 3);
            $query = $this->db->get('trabalho');
            $trabalho2 = $query->row(0)->idTrabalho;

            $this->db->where('id_correspondente', $resultado->id_correspondente);
            $this->db->where('Tipo_trabalho_idTrabalho', 3);
            $options = array(
                'Status_idstatus' => $status
            );
            $this->db->update('trabalho', $options);

            if ($status == 0)
                return $this->db->affected_rows();

            $options1 = array(
                'Trabalho_idTrabalho' => $trabalho1,
                'Status_idstatus' => $status
            );
            $this->db->set('data_acao', 'NOW()', FALSE);
            $this->db->insert('log_trabalho', $options1);

            $options1 = array(
                'Trabalho_idTrabalho' => $trabalho2,
                'Status_idstatus' => $status
            );
            $this->db->set('data_acao', 'NOW()', FALSE);
            $this->db->insert('log_trabalho', $options1);
        }

        $this->db->where('idTrabalho', $trabalho);
        $options = array(
            'Status_idstatus' => $status
        );
        $this->db->update('trabalho', $options);

        if ($status == 0)
            return $this->db->affected_rows();

        $options1 = array(
            'Trabalho_idTrabalho' => $trabalho,
            'Status_idstatus' => $status
        );
        $this->db->set('data_acao', 'NOW()', FALSE);
        $this->db->insert('log_trabalho', $options1);

        return $this->db->affected_rows();
    }

    function add_records($options = array()) {
        if ($options['Pessoa_idPessoa'] == 0)
            return false;
        $this->db->where('Tipo_trabalho_idTrabalho', $options['Tipo_trabalho_idTrabalho']);
        $this->db->where('id_correspondente', $options['id_correspondente']);
        $query = $this->db->get('trabalho');
        if (count($query->result()) > 0) {
            $this->db->where('Tipo_trabalho_idTrabalho', $options['Tipo_trabalho_idTrabalho']);
            $this->db->where('id_correspondente', $options['id_correspondente']);
            $this->db->update('trabalho', $options);
        } else
            $this->db->insert('trabalho', $options);

        return $this->db->affected_rows();
    }

    function copia_proposta($id) {
        $this->db->where('idProposta', $id);
        $query = $this->db->get('proposta');
        $options = $query->result();
        $options = $this->object_to_array($options[0]);
        $options['idProposta'] = null;
        $options['enviado'] = false;
        $options['padrao'] = false;
        $this->db->insert('proposta', $options);

        return $this->db->insert_id();
    }

    function copia_proposta_usuario($id, $pessoa, $viraPadrao = false, $usouPadrao = false, $options_proposta = array()) {
        $this->db->where('idProposta', $id);
        $query = $this->db->get('proposta');
        $options = $query->result();
        $options = $this->object_to_array($options[0]);
        $options['idProposta'] = null;
        $options['enviado'] = null;
        $options['id_siconv'] = null;
        $options['padrao'] = null;
        $options['idGestor'] = $pessoa;
        $options['virou_padrao'] = null;
        $options['padrao_oculto'] = null;
        $options['id_proposta_atual'] = null;
        $options['id_proposta_efetiva'] = null;
        $options['id_proposta_efetiva'] = null;
        $options['situacao'] = null;
        $options['banco_proposta'] = null;

        if (!empty($options_proposta))
            $options['repasse_especifico'] = $options_proposta['repasse_especifico'];

//         if(count($options_proposta) > 0){
//         	$options['percentual'] = $options_proposta['percentual'];
//         	$options['valor_global'] = $options_proposta['valor_global'];
//         	$options['total_contrapartida'] = $options_proposta['total_contrapartida'];
//         	$options['contrapartida_financeira'] = $options_proposta['contrapartida_financeira'];
//         	$options['contrapartida_bens'] = $options_proposta['contrapartida_bens'];
//         	$options['repasse'] = $options_proposta['repasse'];
//         	$options['repasse_voluntario'] = $options_proposta['repasse_voluntario'];
//         }

        if ($viraPadrao)
            $options['virou_padrao'] = 1;

        if ($usouPadrao) {
            $options['virou_padrao'] = 0;
            $options['era_padrao'] = 1;
        }

        $this->db->insert('proposta', $options);

        return $this->db->insert_id();
    }

    public function copiar_programa_proposta($id, $id_proposta) {
        $this->db->where('id_proposta', $id);
        $query_array = $this->db->get('programa_proposta')->result_array();

        $programas = null;
        foreach ($query_array as $array) {
            $options = $array;
            $options['id_programa_proposta'] = null;
            $options['id_proposta'] = $id_proposta;
            $this->db->insert('programa_proposta', $options);

            $programas = $options['id_programa'];
        }

        return $programas;
    }

    function copia_justificativa($id, $id_proposta) {
        $metas = array();
        $this->db->where('Proposta_idProposta', $id);
        $query = $this->db->get('justificativa');
        $options = $query->result();
        if (count($options) > 0) {
            $options = $this->object_to_array($options[0]);
            $options['idJustificativa'] = null;
            $options['Proposta_idProposta'] = $id_proposta;
            $this->db->insert('justificativa', $options);

            return $this->db->insert_id();
        } else {
            return 0;
        }
    }

    function copia_metas($id, $id_proposta, $id_programa) {
        $metas = array();
        $this->db->where('Proposta_idProposta', $id);
        $query = $this->db->get('meta');
        if (count($query->result()) > 0) {
            foreach ($query->result() as $meta) {
                $options = $this->object_to_array($meta);
                $options['idMeta'] = null;
                $options['Proposta_idProposta'] = $id_proposta;
                //$options['id_programa'] = $meta->id_programa;
                $this->db->insert('meta', $options);
                $metas[$meta->idMeta] = $this->db->insert_id();
            }
            return $metas;
        } else {
            return array();
        }
    }

    function copia_metas_padrao($id, $id_proposta, $id_programa) {
        $metas = array();
        $this->db->where('Proposta_idProposta', $id);
        $query = $this->db->get('meta');
        if (count($query->result()) > 0) {
            foreach ($query->result() as $meta) {
                $options = $this->object_to_array($meta);
                $options['idMeta'] = null;
                $options['Proposta_idProposta'] = $id_proposta;
                $options['id_programa'] = $id_programa;
                $this->db->insert('meta', $options);
                $metas[$meta->idMeta] = $this->db->insert_id();
            }
            return $metas;
        } else {
            return array();
        }
    }

    function copia_etapas($metas) {
        if (count($metas) > 0) {
            $etapas = array();
            foreach ($metas as $key => $meta) {
                $this->db->where('Meta_idMeta', $key);
                $query = $this->db->get('etapa');
                foreach ($query->result() as $etapa) {
                    $options = $this->object_to_array($etapa);
                    $options['idEtapa'] = null;
                    $options['Meta_idMeta'] = $meta;
                    $this->db->insert('etapa', $options);
                    $etapas[$etapa->idEtapa] = $this->db->insert_id();
                }
            }
            return $etapas;
        } else {
            return array();
        }
    }

    function copia_cronogramas($id, $id_proposta) {
        $cronogramas = array();
        $this->db->where('Proposta_idProposta', $id);
        $query = $this->db->get('cronograma');
        if (count($query->result()) > 0) {
            foreach ($query->result() as $cronograma) {
                $options = $this->object_to_array($cronograma);
                $options['idCronograma'] = null;
                $options['Proposta_idProposta'] = $id_proposta;
                $this->db->insert('cronograma', $options);
                $cronogramas[$cronograma->idCronograma] = $this->db->insert_id();
            }

            return $cronogramas;
        } else {
            return array();
        }
    }

    function copia_cronogramas_meta($cronogramas, $metas) {

        if (count($cronogramas) > 0 && count($metas) > 0) {
            $etapas = array();
            foreach ($cronogramas as $key => $cronograma) {
                $this->db->where('Cronograma_idCronograma', $key);
                $query = $this->db->get('cronograma_meta');
                foreach ($query->result() as $etapa) {
                    $options = $this->object_to_array($etapa);
                    $options['idCronograma_meta'] = null;
                    $options['Cronograma_idCronograma'] = $cronogramas[$options['Cronograma_idCronograma']];
                    $options['Meta_idMeta'] = $metas[$options['Meta_idMeta']];
                    $this->db->insert('cronograma_meta', $options);
                    $etapas[$etapa->idCronograma_meta] = $this->db->insert_id();
                }
            }

            return $etapas;
        } else {
            return array();
        }
    }

    function copia_cronogramas_etapa($cronogramas_meta, $etapas) {
        if (count($cronogramas_meta) > 0 && count($etapas) > 0) {
            $cronograma_etapas = array();
            foreach ($cronogramas_meta as $key => $cronograma) {
                $this->db->where('Cronograma_meta_idCronograma_meta', $key);
                $query = $this->db->get('cronograma_etapa');
                foreach ($query->result() as $etapa) {
                    $options = $this->object_to_array($etapa);
                    $options['idCronograma_etapa'] = null;
                    $options['Cronograma_meta_idCronograma_meta'] = $cronogramas_meta[$options['Cronograma_meta_idCronograma_meta']];
                    $options['Etapa_idEtapa'] = $etapas[$options['Etapa_idEtapa']];
                    $this->db->insert('cronograma_etapa', $options);
                    $cronograma_etapas[$etapa->idCronograma_etapa] = $this->db->insert_id();
                }
            }

            return $cronograma_etapas;
        } else {
            return array();
        }
    }

    function copia_despesas($id, $id_proposta, $id_programa) {

        $cronogramas = array();
        $this->db->where('Proposta_idProposta', $id);
        $query = $this->db->get('despesa');
        if (count($query->result()) > 0) {
            foreach ($query->result() as $bens) {
                $options = $this->object_to_array($bens);
                $options['idDespesa'] = null;
                $options['Proposta_idProposta'] = $id_proposta;
                //$options['id_programa'] = $bens->id_programa;
                $this->db->insert('despesa', $options);
                $cronogramas[$bens->idDespesa] = $this->db->insert_id();
            }

            return $cronogramas;
        } else {
            return array();
        }
    }

    function copia_despesas_padrao($id, $id_proposta, $id_programa) {

        $cronogramas = array();
        $this->db->where('Proposta_idProposta', $id);
        $query = $this->db->get('despesa');
        if (count($query->result()) > 0) {
            foreach ($query->result() as $bens) {
                $options = $this->object_to_array($bens);
                $options['idDespesa'] = null;
                $options['Proposta_idProposta'] = $id_proposta;
                $options['id_programa'] = $id_programa;
                $this->db->insert('despesa', $options);
                $cronogramas[$bens->idDespesa] = $this->db->insert_id();
            }

            return $cronogramas;
        } else {
            return array();
        }
    }

    function copia_trabalhos($id, $id_proposta) {
        $trabalhos = array();
        $this->db->where('id_correspondente', $id);
        $query = $this->db->get('trabalho');
        if (count($query->result()) > 0) {
            foreach ($query->result() as $trabalho) {
                $options = $this->object_to_array($trabalho);
                $options['idTrabalho'] = null;
                $options['Status_idstatus'] = 5;
                $options['id_correspondente'] = $id_proposta;
                $this->db->insert('trabalho', $options);
                $trabalhos[$trabalho->idTrabalho] = $this->db->insert_id();
            }

            return $trabalhos;
        } else {
            return array();
        }
    }

    function object_to_array($data) {
        if ((!is_array($data)) and ( !is_object($data)))
            return 'xxx'; //$data;

        $result = array();
        $data = (array) $data;
        foreach ($data as $key => $value) {
            if (is_object($value))
                $value = (array) $value;
            if (is_array($value))
                $result[$key] = object_to_array($value);
            else
                $result[$key] = $value;
        }
        return $result;
    }

    function add_justificativa($options = array(), $justificativa = null) {
        if ($options['Proposta_idProposta'] == 0)
            return false;

        $this->load->model('system_logs');

        $this->db->where('idJustificativa', $justificativa);
        $query = $this->db->get('justificativa');

        if (count($query->result()) > 0) {
            $this->db->where('idJustificativa', $justificativa);
            $this->db->update('justificativa', $options);

            $this->system_logs->add_log(EDT_JUSTI_PROJETO . " - Proj: " . $this->retorna_nome_proposta($options['Proposta_idProposta']) . ", Just: " . substr($options['Justificativa'], 0, 100));
            $this->system_logs->add_log(EDT_OBJ_PROJETO . " - Proj: " . $this->retorna_nome_proposta($options['Proposta_idProposta']) . ", Obj: " . substr($options['objeto'], 0, 100));
        } else {
            $this->db->insert('justificativa', $options);

            $this->system_logs->add_log(INC_JUSTI_PROJETO . " - Proj: " . $this->retorna_nome_proposta($options['Proposta_idProposta']) . ", Just: " . substr($options['Justificativa'], 0, 100));
            $this->system_logs->add_log(INC_OBJ_PROJETO . " - Proj: " . $this->retorna_nome_proposta($options['Proposta_idProposta']) . ", Obj: " . substr($options['objeto'], 0, 100));
        }

        return $this->db->affected_rows();
    }

    public function retorna_nome_proposta($id) {
        $this->load->model('proposta_model');
        $dados = $this->proposta_model->get_by_id($id);

        return substr($dados->nome, 0, 50);
    }

    function add_meta($options = array(), $idMeta = null) {
        if ($options['Proposta_idProposta'] == 0)
            return false;

        // obtém o percentual do novo valor
        $this->db->select("idMeta, total, quantidade");
        $this->db->where('idMeta', $idMeta);
        $meta = $this->db->get('meta')->row(0);

        $valorMeta_new = $options['total'];
        $percentNovoValor = $valorMeta_new / $meta->total;

        /*
         * Altera o valor da meta e realiza o log da operação
         */


        $this->load->model('system_logs');

        $this->db->where('idMeta', $idMeta);
        $query = $this->db->get('meta');
        if (count($query->result()) > 0) {
            $this->db->where('idMeta', $idMeta);
            $this->db->update('meta', $options);

            $this->system_logs->add_log(EDT_META_PROJETO . " - Proj: " . $this->retorna_nome_proposta($options['Proposta_idProposta']) . ", Meta: " . substr($options['especificacao'], 0, 80) . ", valor: " . number_format($options['total'], 2, ",", "."));
        } else {
            $this->db->insert('meta', $options);

            $this->system_logs->add_log(INC_META_PROJETO . " - Proj: " . $this->retorna_nome_proposta($options['Proposta_idProposta']) . ", Meta: " . substr($options['especificacao'], 0, 80) . ", valor: " . number_format($options['total'], 2, ",", "."));
        }

        $this->db->cache_delete_all();

        //Crono Físico
        //Etapas
        $this->db->select("idEtapa, quantidade, total");
        $this->db->where('Meta_idMeta', $idMeta);
        $etapas = $this->db->get('etapa')->result();

        $totalEtapas = 0;
        foreach ($etapas as $e) {
            $totalEtapas += $e->total;
        }

        $totalParcialEtapasAtePenultima = doubleval(0);
        if ($totalEtapas > 0) {
            foreach ($etapas as $keyEtapas => $etapa) {
                if ($keyEtapas == (count($etapas) - 1)) {
                    $difGlobalSomaEtapas = $valorMeta_new - round($totalParcialEtapasAtePenultima, 2);
                    $valorEtapa_new = round($difGlobalSomaEtapas, 2);
                    $valorEtapaUnitario = $valorEtapa_new / $etapa->quantidade;
                } else {
                    $valorEtapa_new = $etapa->total * $percentNovoValor;
                    $valorEtapa_new = round($valorEtapa_new, 2);
                    $valorEtapaUnitario = $valorEtapa_new / $etapa->quantidade;

                    $totalParcialEtapasAtePenultima = $totalParcialEtapasAtePenultima + $valorEtapa_new;
                }

                $optionsEtapa = array(
                    'total' => $valorEtapa_new,
                    'valorUnitario' => $valorEtapaUnitario
                );

                $this->db->where('Meta_idMeta', $meta->idMeta);
                $this->db->where('idEtapa', $etapa->idEtapa);
                $this->db->update('etapa', $optionsEtapa);

                $this->db->cache_delete_all();
            }
        }

        /*
         * Zerando o crono desembolso
         */

        $this->db->select("idCronograma, parcela");
        $this->db->where('Proposta_idProposta', $options['Proposta_idProposta']);
        $cronogramas = $this->db->get('cronograma')->result();

        foreach ($cronogramas as $cronograma) {
            $this->db->where('Cronograma_idCronograma', $cronograma->idCronograma);
            $this->db->delete('cronograma_meta');
        }

        return true;
    }

    function add_endereco($options = array()) {
        $option_despesa = $options;
        $option_despesa['municipio'] = $option_despesa['municipio_sigla'];
        unset($option_despesa['Proposta_idProposta']);
        unset($option_despesa['municipio_sigla']);
        unset($option_despesa['municipio_nome']);
        if ($options['Proposta_idProposta'] == 0)
            return false;
        //echo $options['idMeta'] die();
        $this->db->where('Proposta_idProposta', $options['Proposta_idProposta']);
        $query = $this->db->get('endereco');
        if (count($query->result()) > 0) {
            $this->db->where('Proposta_idProposta', $options['Proposta_idProposta']);
            $this->db->update('endereco', $options);
        } else {
            $this->db->insert('endereco', $options);
        }

        $this->db->where('Proposta_idProposta', $options['Proposta_idProposta']);
        $this->db->update('meta', $options);

        $this->db->where('Proposta_idProposta', $options['Proposta_idProposta']);
        $this->db->update('despesa', $option_despesa);

        $this->db->where('Proposta_idProposta', $options['Proposta_idProposta']);
        $query = $this->db->get('meta');
        $metas = $query->result();
        unset($options['Proposta_idProposta']);
        foreach ($metas as $meta) {
            $this->db->where('Meta_idMeta', $meta->idMeta);
            $this->db->update('etapa', $options);
        }

        return $this->db->affected_rows();
    }

    function add_etapa($options = array(), $idEtapa = null) {
        if ($options['Meta_idMeta'] == 0)
            return false;

        $this->load->model('system_logs');

        $this->db->where('idEtapa', $idEtapa);
        $query = $this->db->get('etapa');
        if (count($query->result()) > 0) {
            $this->db->where('idEtapa', $idEtapa);
            $this->db->update('etapa', $options);

            $this->system_logs->add_log(EDT_ETAPA_META . " - Meta: " . substr($this->obter_meta_por_id($options['Meta_idMeta'])->especificacao, 0, 50) . ", Etapa: " . substr($options['especificacao'], 0, 90) . ", valor: " . number_format($options['total'], 2, ",", "."));
        } else {
            $this->db->insert('etapa', $options);

            $this->system_logs->add_log(INC_ETAPA_META . " - Meta: " . substr($this->obter_meta_por_id($options['Meta_idMeta'])->especificacao, 0, 50) . ", Etapa: " . substr($options['especificacao'], 0, 90) . ", valor: " . number_format($options['total'], 2, ",", "."));
        }
        return $this->db->affected_rows();
    }

    function add_cronograma($options = array()) {
        if ($options['Proposta_idProposta'] == 0)
            return false;

        $this->load->model('system_logs');

        $this->db->where('idCronograma', $options['idCronograma']);
        $query = $this->db->get('cronograma');
        if (count($query->result()) > 0) {
            $this->db->where('responsavel', $options['responsavel']);
            $this->db->where('mes', $options['mes']);
            $this->db->where('idCronograma != ' . $options['idCronograma'] . ' AND ano = ', $options['ano']);
            $this->db->where('Proposta_idProposta', $options['Proposta_idProposta']);
            $query = $this->db->get('cronograma');
            if (count($query->result()) > 0)
                return false;

            $this->db->where('idCronograma', $options['idCronograma']);
            $this->db->update('cronograma', $options);

            $this->system_logs->add_log(EDT_CRONO_PROJETO . " - Proj: " . $this->retorna_nome_proposta($options['Proposta_idProposta']) . ", ID Crono: " . $options['idCronograma'] . ", Resp: " . $options['responsavel'] . ", Valor: " . number_format($options['parcela'], 2, ",", "."));
        }else {
            $this->db->where('responsavel', $options['responsavel']);
            $this->db->where('mes', $options['mes']);
            $this->db->where('ano', $options['ano']);
            $this->db->where('Proposta_idProposta', $options['Proposta_idProposta']);
            $query = $this->db->get('cronograma');
            if (count($query->result()) > 0)
                return false;
            $this->db->insert('cronograma', $options);

            $this->system_logs->add_log(INC_CRONO_PROJETO . " - Proj: " . $this->retorna_nome_proposta($options['Proposta_idProposta']) . ", ID Crono: " . $this->db->insert_id() . ", Resp: " . $options['responsavel'] . ", Valor: " . number_format($options['parcela'], 2, ",", "."));
        }
        return true;
    }

    function add_cronograma_meta($options = array()) {
        if ($options['Cronograma_idCronograma'] == 0)
            return false;

        $this->load->model('system_logs');

        $this->db->where('Cronograma_idCronograma', $options['Cronograma_idCronograma']);
        $this->db->where('Meta_idMeta', $options['Meta_idMeta']);
        $query = $this->db->get('cronograma_meta');
        if (count($query->result()) > 0) {
            $this->db->where('Cronograma_idCronograma', $options['Cronograma_idCronograma']);
            $this->db->where('Meta_idMeta', $options['Meta_idMeta']);
            $this->db->update('cronograma_meta', $options);

            $this->system_logs->add_log(EDT_META_CRONO . " - Crono: " . $this->obter_cronograma_por_id($options['Cronograma_idCronograma'])->responsavel . ", Meta: " . substr($this->obter_meta_por_id($options['Meta_idMeta'])->especificacao, 0, 100) . ", Valor: " . number_format($options['valor'], 2, ",", "."));
        } else {
            $this->db->insert('cronograma_meta', $options);

            $this->system_logs->add_log(INC_META_CRONO . " - Crono: " . $this->obter_cronograma_por_id($options['Cronograma_idCronograma'])->responsavel . ", Meta: " . substr($this->obter_meta_por_id($options['Meta_idMeta'])->especificacao, 0, 100) . ", Valor: " . number_format($options['valor'], 2, ",", "."));
        }
        return $this->db->affected_rows();
    }

    function add_cronograma_etapa($options = array()) {
        if ($options['Cronograma_meta_idCronograma_meta'] == 0)
            return false;

        $this->load->model('system_logs');

        $this->db->where('Cronograma_meta_idCronograma_meta', $options['Cronograma_meta_idCronograma_meta']);
        $this->db->where('Etapa_idEtapa', $options['Etapa_idEtapa']);
        $query = $this->db->get('cronograma_etapa');
        if (count($query->result()) > 0) {
            $this->db->where('Cronograma_meta_idCronograma_meta', $options['Cronograma_meta_idCronograma_meta']);
            $this->db->where('Etapa_idEtapa', $options['Etapa_idEtapa']);
            $this->db->update('cronograma_etapa', $options);

            $this->system_logs->add_log(EDT_ETAPA_CRONO . " - ID Crono Meta: " . $query->row(0)->idCronograma_etapa . ", Etapa: " . substr($this->obter_etapa_por_id($options['Etapa_idEtapa'])->especificacao, 0, 100) . ", Valor: " . number_format($options['valor'], 2, ",", "."));
        } else {
            $this->db->insert('cronograma_etapa', $options);

            $this->system_logs->add_log(INC_ETAPA_CRONO . " - ID Crono Meta: " . $this->db->insert_id() . ", Etapa: " . substr($this->obter_etapa_por_id($options['Etapa_idEtapa'])->especificacao, 0, 100) . ", Valor: " . number_format($options['valor'], 2, ",", "."));
        }
        return $this->db->affected_rows();
    }

    function apagar_despesa($despesa) {
        $this->load->model('system_logs');

        $this->db->where('idDespesa', $despesa);
        $dados = $this->db->get('despesa')->row(0);

        $this->db->cache_delete_all();

        $this->db->where('idDespesa', $despesa);
        $this->db->delete('despesa');

        $this->system_logs->add_log(DEL_PLANO_PROJETO . " - Proj: " . $this->retorna_nome_proposta($dados->Proposta_idProposta) . ", Despesa: " . $this->obter_nome_despesa($dados->Tipo_despesa_idTipo_despesa)->nome . ", Valor: " . number_format($dados->total, 2, ",", "."));
    }

    function excluir_parcela_do_cronograma_de_desembolso($id) {
        $this->db->where('Cronograma_idCronograma', $id);
        $metasCrono = $this->db->get('cronograma_meta')->result();
        $this->db->flush_cache();

        foreach ($metasCrono as $meta) {
            $this->db->where('Cronograma_meta_idCronograma_meta', $meta->idCronograma_meta);
            $this->db->delete('cronograma_etapa');

            $this->db->flush_cache();

            $this->db->where('idCronograma_meta', $meta->idCronograma_meta);
            $this->db->delete('cronograma_meta');

            $this->db->flush_cache();
        }

        $this->db->where('idCronograma', $id);
        $this->db->delete('cronograma');
    }

    function apagar_meta($id) {
        $this->db->where('idMeta', $id);
        $this->db->delete('meta');
    }

    function apagar_etapa($id) {
        $this->db->where('idEtapa', $id);
        $this->db->delete('etapa');
    }

    function add_despesa($options = array()) {
        if ($options['Proposta_idProposta'] == 0)
            return false;

        $this->load->model('system_logs');

        $this->db->where('idDespesa', $options['idDespesa']);
        $query = $this->db->get('despesa');
        if (count($query->result()) > 0) {
            $this->db->where('idDespesa', $options['idDespesa']);
            $this->db->update('despesa', $options);

            $this->system_logs->add_log(EDT_PLANO_PROJETO . " - Proj: " . $this->retorna_nome_proposta($options['Proposta_idProposta']) . ", Despesa: " . $this->obter_nome_despesa($options['Tipo_despesa_idTipo_despesa'])->nome . ", Valor: " . number_format($options['total'], 2, ",", "."));
        } else {
            if (array_key_exists('idDespesa', $options)) {
                if ($options['idDespesa'] == '') {
                    unset($options['idDespesa']);
                }
            }

            $this->db->insert('despesa', $options);

            $this->system_logs->add_log(INC_PLANO_PROJETO . " - Proj: " . $this->retorna_nome_proposta($options['Proposta_idProposta']) . ", Despesa: " . $this->obter_nome_despesa($options['Tipo_despesa_idTipo_despesa'])->nome . ", Valor: " . number_format($options['total'], 2, ",", "."));
        }

        return $this->db->affected_rows();
    }

    function delete_record($id) {
        $this->db->where('idProposta', $id);
        $options['ativo'] = 0;
        $this->db->update('proposta', $options);
        return $this->db->affected_rows();
    }

    function update_record($id, $options = array()) {

        $this->db->where('idProposta', $id);
        $this->db->update('proposta', $options);
        return $this->db->affected_rows();
    }

    function get_by_id($id) {

        $this->db->where('idTrabalho', $id);
        $query = $this->db->get('trabalho');
        return $query->row(0);
    }

    function obter_bancos() {
        $query = $this->db->get('banco');
        return $query->result();
    }

    function verifica_trabalho_finalizado($id) {
        $this->db->where('id_correspondente', $id);
        $query = $this->db->get('trabalho');
        $trabalhos = $query->result();

        foreach ($trabalhos as $trabalho) {
            if ($trabalho->Status_idstatus != 5)
                return false;
        }
        return true;
    }

    function obter_saida_tela1($id) {
        $this->db->where('idProposta', $id);
        $query = $this->db->get('proposta');
        if (count($query->result()) > 0)
            return "document.getElementById('salvarValorGlobal').value='" . number_format($query->row(0)->valor_global, 2, ",", ".") . "';
				document.getElementById('salvarValorContrapartidaFinanceira').value='" . number_format($query->row(0)->contrapartida_financeira, 2, ",", ".") . "';
				document.getElementById('salvarValorContrapartidaBensServicos').value='" . number_format($query->row(0)->contrapartida_bens, 2, ",", ".") . "';
				document.getElementById('salvarValorRepassevoluntario').value='" . number_format($query->row(0)->repasse_voluntario, 2, ",", ".") . "';";
        else
            return '';
    }

    function obter_saida_tela1_online($id) {
        $this->db->where('idProposta', $id);
        $query = $this->db->get('proposta');
        return $query->row(0);
    }

    function obter_saida_tela2($id) {
        $this->db->where('idProposta', $id);
        $query = $this->db->get('proposta');

        $this->db->where('Proposta_idProposta', $id);
        $query1 = $this->db->get('justificativa');
        if (count($query1->result()) > 0)
            return "document.getElementById('inserirPropostaJustificativa').value='" . $query1->row(0)->Justificativa . "';
				document.getElementById('cadastrarPropostaObjetoConvenio').value='" . $query1->row(0)->objeto . "';
				document.getElementById('inserirPropostaInicioVigencia').value='" . implode("/", array_reverse(explode("-", $query->row(0)->data_inicio))) . "';
				document.getElementById('inserirPropostaTerminoVigencia').value='" . implode("/", array_reverse(explode("-", $query->row(0)->data_termino))) . "';
				document.getElementById('adicionarRepasseAnoRepasse').value='" . substr($query->row(0)->data_inicio, 0, 4) . "';
				document.getElementById('adicionarRepasseValorRepasse').value='" . number_format($query->row(0)->repasse, 2, ",", ".") . "';";
        else
            return '';
    }

    function obter_saida_tela2_online($id) {
        $saida = array();
        $this->db->where('idProposta', $id);
        $query = $this->db->get('proposta');

        $this->db->where('Proposta_idProposta', $id);
        $query1 = $this->db->get('justificativa');
        if (count($query1->result()) > 0) {
            $saida['Justificativa'] = $query1->row(0)->Justificativa;
            $saida['objeto'] = $query1->row(0)->objeto;
            $saida['banco'] = $query->row(0)->banco;
            $saida['agencia'] = $query->row(0)->agencia;
            $saida['data_inicio'] = implode("/", array_reverse(explode("-", $query->row(0)->data_inicio)));
            $saida['data_termino'] = implode("/", array_reverse(explode("-", $query->row(0)->data_termino)));
            $saida['anoRepasse'] = substr($query->row(0)->data_inicio, 0, 4);
            $saida['repasse'] = number_format($query->row(0)->repasse, 2, ",", ".");
        }
        return $saida;
    }

    function obter_saida_tela3($id) {
        $this->db->where('Proposta_idProposta', $id);
        $query = $this->db->get('meta');
        $metas = $query->result();
        $saida = array();

        foreach ($metas as $key => $meta) {
            $saida[$key][0] = $meta->idMeta;
            $saida[$key][1] = "document.getElementById('incluirEspecificacao').value='" . $meta->especificacao . "';
				document.getElementById('incluirCodUnidadeFornecimento').value='" . $meta->fornecimento . "';
				document.getElementById('incluirValor').value='" . number_format($meta->total, 2, ",", ".") . "';
				document.getElementById('incluirQuantidade').value='" . number_format($meta->quantidade, 2, ",", ".") . "';
				document.getElementById('incluirValorUnitario').value='" . number_format($meta->valorUnitario, 2, ",", ".") . "';
				document.getElementById('incluirDataInicio').value='" . implode("/", array_reverse(explode("-", $meta->data_inicio))) . "';
				document.getElementById('incluirDataFim').value='" . implode("/", array_reverse(explode("-", $meta->data_termino))) . "';
				document.getElementById('incluirUf').value='" . $meta->UF . "';
				document.getElementById('cadastrarParticipeCodigoMunicipio').value='" . $meta->municipio_sigla . "';
				document.getElementById('cadastrarParticipeNomeMunicipio').value='" . $meta->municipio_nome . "';
				document.getElementById('alterarEndereco').value='" . $meta->endereco . "';
				document.getElementById('alterarCep').value='" . $meta->cep . "';";
        }
        if (count($metas) > 0)
            return $saida;
        else
            return array();
    }

    function obter_saida_tela3_online($id) {
        $this->db->where('Proposta_idProposta', $id);
        $query = $this->db->get('meta');
        $metas = $query->result();
        $saida = array();

        foreach ($metas as $key => $meta) {
            $saida[$key][0] = $meta->idMeta;
            $saida[$key]['especificacao'] = $meta->especificacao;
            $saida[$key]['fornecimento'] = $meta->fornecimento;
            $saida[$key]['total'] = number_format($meta->total, 2, ",", ".");
            $saida[$key]['quantidade'] = number_format($meta->quantidade, 2, ",", ".");
            $saida[$key]['valorUnitario'] = number_format($meta->valorUnitario, 2, ",", ".");
            $saida[$key]['data_inicio'] = implode("/", array_reverse(explode("-", $meta->data_inicio)));
            $saida[$key]['data_termino'] = implode("/", array_reverse(explode("-", $meta->data_termino)));
            $saida[$key]['UF'] = $meta->UF;
            $saida[$key]['municipio_sigla'] = $meta->municipio_sigla;
            $saida[$key]['municipio_nome'] = $meta->municipio_nome;
            $saida[$key]['endereco'] = $meta->endereco;
            $saida[$key]['cep'] = $meta->cep;
            $saida[$key]['id_programa'] = $meta->id_programa;
        }
        return $saida;
    }

    function obter_saida_tela3_etapas($id) {
        $this->db->where('Meta_idMeta', $id);
        $query = $this->db->get('etapa');
        $metas = $query->result();
        $saida = array();

        foreach ($metas as $meta) {
            $saida[] = "document.getElementById('incluirEtapaEspecificacao').value='" . $meta->especificacao . "';
				document.getElementById('incluirEtapaCodUnidadeFornecimento').value='" . $meta->fornecimento . "';
				document.getElementById('incluirEtapaValor').value='" . number_format($meta->total, 2, ",", ".") . "';
				document.getElementById('incluirEtapaQuantidade').value='" . number_format($meta->quantidade, 2, ",", ".") . "';
				document.getElementById('incluirEtapaValorUnitario').value='" . number_format($meta->valorUnitario, 2, ",", ".") . "';
				document.getElementById('incluirEtapaDataInicio').value='" . implode("/", array_reverse(explode("-", $meta->data_inicio))) . "';
				document.getElementById('incluirEtapaDataFim').value='" . implode("/", array_reverse(explode("-", $meta->data_termino))) . "';
				document.getElementById('incluirEtapaUf').value='" . $meta->UF . "';
				document.getElementById('cadastrarParticipeCodigoMunicipio').value='" . $meta->municipio_sigla . "';
				document.getElementById('cadastrarParticipeNomeMunicipio').value='" . $meta->municipio_nome . "';
				document.getElementById('alterarEtapaEndereco').value='" . $meta->endereco . "';
				document.getElementById('alterarEtapaCep').value='" . $meta->cep . "';";
        }
        if (count($metas) > 0)
            return $saida;
        else
            return '';
    }

    function obter_saida_tela3_etapas_online($id) {
        $this->db->where('Meta_idMeta', $id);
        $this->db->order_by("especificacao, idEtapa");
        $query = $this->db->get('etapa');
        $metas = $query->result();
        $saida = array();

        foreach ($metas as $key => $meta) {
            $saida[$key][0] = $meta->idEtapa;
            $saida[$key]['especificacao'] = $meta->especificacao;
            $saida[$key]['fornecimento'] = $meta->fornecimento;
            $saida[$key]['total'] = number_format($meta->total, 2, ",", ".");
            $saida[$key]['quantidade'] = number_format($meta->quantidade, 2, ",", ".");
            $saida[$key]['valorUnitario'] = number_format($meta->valorUnitario, 2, ",", ".");
            $saida[$key]['data_inicio'] = implode("/", array_reverse(explode("-", $meta->data_inicio)));
            $saida[$key]['data_termino'] = implode("/", array_reverse(explode("-", $meta->data_termino)));
            $saida[$key]['UF'] = $meta->UF;
            $saida[$key]['municipio_sigla'] = $meta->municipio_sigla;
            $saida[$key]['municipio_nome'] = $meta->municipio_nome;
            $saida[$key]['endereco'] = $meta->endereco;
            $saida[$key]['cep'] = $meta->cep;
        }
        return $saida;
    }

    function obter_saida_tela4($id) {
        $this->db->where('Proposta_idProposta', $id);
        $query = $this->db->get('cronograma');
        $metas = $query->result();
        $saida = array();

        foreach ($metas as $meta) {
            $saida[] = "document.getElementById('incluirParcelaResponsavel').value='" . $meta->responsavel . "';
				document.getElementById('incluirParcelaMes').value='" . $meta->mes . "';
				document.getElementById('incluirParcelaAno').value='" . $meta->ano . "';
				document.getElementById('incluirParcelaValorParcela').value='" . number_format($meta->parcela, 2, ",", ".") . "';";
        }
        if (count($metas) > 0)
            return $saida;
        else
            return '';
    }

    function obter_saida_tela4_online($id) {
        $this->db->where('Proposta_idProposta', $id);
        $this->db->order_by('responsavel');
        $query = $this->db->get('cronograma');
        $metas = $query->result();
        $saida = array();

        foreach ($metas as $key => $meta) {
            $saida[$key][0] = $meta->idCronograma;
            $saida[$key]['idCronograma'] = $meta->idCronograma;
            $saida[$key]['responsavel'] = $meta->responsavel;
            $saida[$key]['mes'] = $meta->mes;
            $saida[$key]['ano'] = $meta->ano;
            $saida[$key]['parcela'] = number_format($meta->parcela, 2, ",", ".");
        }
        return $saida;
    }

    function obter_saida_tela5($id) {
        $this->db->where('Proposta_idProposta', $id);
        $query = $this->db->get('cronograma');
        $metas = $query->result();
        $saida = array();

        foreach ($metas as $key => $meta) {
            $saida[$key][0] = $meta->idCronograma;
            $saida[$key][1] = "document.getElementById('associarMetaValorMeta').value='" . number_format($meta->valor_meta, 2, ",", ".") . "';";
        }
        if (count($metas) > 0)
            return $saida;
        else
            return array();
    }

    function obter_saida_tela5_online($id) {
        $metas = array();
        $saida = array();

        $this->db->where('Cronograma_idCronograma', $id);
        $query = $this->db->get('cronograma_meta');
        $metas = $query->result();

        foreach ($metas as $key => $meta) {
            $id_meta = $this->obter_meta_por_id($meta->Meta_idMeta);
            $nome_meta = $id_meta->especificacao;
            $saida[$key][0] = $meta->idCronograma_meta;
            $saida[$key]['idCronograma_meta'] = $meta->idCronograma_meta;
            $saida[$key]['meta'] = $nome_meta;
            $saida[$key]['valor'] = number_format($meta->valor, 2, ",", ".");
        }
        return $saida;
    }

    function obter_saida_tela5_etapas($id) {

        $metas = array();
        $saida = array();

        $this->db->where('Cronograma_idCronograma', $id);
        $query2 = $this->db->get('Cronograma_meta');
        $query2_aux = $query2->result(); //só para contar e nao dar erro
        if (count($query2_aux) > 0) {
            $this->db->where('Cronograma_meta_idCronograma_meta', $query2->row(0)->idCronograma_meta);
            $query = $this->db->get('cronograma_etapa');
            $metas = $query->result();
        }

        foreach ($metas as $key => $meta) {
            $saida[] = "document.getElementById('salvarValorAVincular').value='" . number_format($meta->valor, 2, ",", ".") . "';";
        }
        if (count($metas) > 0)
            return $saida;
        else
            return '';
    }

    function obter_saida_tela5_etapas_online($id) {

        $query2_aux = array();
        $metas = array();
        $saida = array();

        $this->db->select("especificacao, cronograma_etapa.*");
        $this->db->where('Cronograma_meta_idCronograma_meta', $id);
        $this->db->join("etapa", "cronograma_etapa.Etapa_idEtapa = etapa.idEtapa");
        $this->db->order_by("especificacao, Etapa_idEtapa");
        $query = $this->db->get('cronograma_etapa');

        $metas = $query->result();

        foreach ($metas as $key => $meta) {
            $saida[$key][0] = $meta->idCronograma_etapa;
            $saida[$key]['Etapa_idEtapa'] = $meta->Etapa_idEtapa;
            $saida[$key]['valor'] = number_format($meta->valor, 2, ",", ".");
        }

        return $saida;
    }

    function obter_saida_tela6($id) {
        $saida = array();
        $this->db->where('Proposta_idProposta', $id);
        $query = $this->db->get('despesa');
        $despesas = $query->result();
        foreach ($despesas as $despesa) {
            $saida[] = "document.getElementById('incluirBensDescricaoItem').value='" . $despesa->descricao . "';
				document.getElementById('incluirBensCodigoNaturezaDespesa').value='" . $despesa->natureza_despesa . "';
				document.getElementById('incluirBensDescricaoNaturezaDespesa').value='" . $despesa->natureza_despesa_descricao . "';
				document.getElementById('incluirBensCodUnidadeFornecimento').value='" . $despesa->fornecimento . "';
				document.getElementById('incluirBensValor').value='" . number_format($despesa->total, 2, ",", ".") . "';
				document.getElementById('incluirBensQuantidade').value='" . number_format($despesa->quantidade, 2, ",", ".") . "';
				document.getElementById('incluirBensValorUnitario').value='" . number_format($despesa->valor_unitario, 2, ",", ".") . "';
				document.getElementById('incluirBensEndereco').value='" . $despesa->endereco . "';
				document.getElementById('incluirBensCEP').value='" . $despesa->cep . "';
				document.getElementById('incluirBensCodigoMunicipio').value='" . $despesa->municipio . "';
				document.getElementById('incluirBensSiglaUf').value='" . $despesa->UF . "';
				document.getElementById('incluirBensObservacao').value='" . $despesa->observacao . "';";
        }
        if (count($despesas) > 0)
            return $saida;
        else
            return array();
    }

    function obter_saida_tela6_online($id) {
        $saida = array();
        $this->db->where('Proposta_idProposta', $id);
        $query = $this->db->get('despesa');
        $despesas = $query->result();
        foreach ($despesas as $key => $despesa) {
            $saida[$key][0] = $despesa->idDespesa;
            $saida[$key]['despesa'] = $this->obter_nome_despesa($despesa->Tipo_despesa_idTipo_despesa)->valor;
            $saida[$key]['descricao'] = $despesa->descricao;
            $saida[$key]['natureza_aquisicao'] = $despesa->natureza_aquisicao;
            $saida[$key]['natureza_despesa'] = $despesa->natureza_despesa;
            $saida[$key]['fornecimento'] = $despesa->fornecimento;
            $saida[$key]['total'] = number_format($despesa->total, 2, ",", ".");
            $saida[$key]['quantidade'] = number_format($despesa->quantidade, 2, ",", ".");
            $saida[$key]['valor_unitario'] = number_format($despesa->valor_unitario, 2, ",", ".");
            $saida[$key]['endereco'] = $despesa->endereco;
            $saida[$key]['cep'] = $despesa->cep;
            $saida[$key]['municipio'] = $despesa->municipio;
            $saida[$key]['UF'] = $despesa->UF;
            $saida[$key]['observacao'] = $despesa->observacao;
            $saida[$key]['id_programa'] = $despesa->id_programa;
        }

        return $saida;
    }

    function alert($text) {
        echo "<script type='text/javascript'>alert('" . utf8_decode($text) . "');</script>";
    }

    function updateValoresProjeto($id, $valorGlobal_new, $percentual, $options_proposta, $opcao_crono_fisico = 2) {
        $this->load->model('programa_proposta_model');
        $this->load->model('programa_model');

        $this->db->where('idProposta', $id);
        $propostas = $this->db->get('proposta')->row(0);

        $affected_rows = 0;

        $this->db->cache_delete_all();

        $valorGlobal_old = $propostas->valor_global;

        $programa_uso = $this->programa_proposta_model->get_programas_by_proposta_padrao($id);

        if ($valorGlobal_old !== $valorGlobal_new || $percentual !== $propostas->percentual || $options_proposta['id_programa'] != $propostas->id_programa) {
            $percentNovoValor = $valorGlobal_new / $valorGlobal_old;

            //Projeto
            $valor_contra = $options_proposta['contrapartida_financeira'];
            $valor_repasse = $options_proposta['repasse'];

            $proporcao_repasse = $valor_repasse / $propostas->repasse;
            $proporcao_contra = $propostas->contrapartida_financeira == 0 ? 0 : $valor_contra / $propostas->contrapartida_financeira;
            $optionsProjeto = array(
                'valor_global' => $valorGlobal_new,
                'total_contrapartida' => $options_proposta['total_contrapartida'],
                'contrapartida_financeira' => $valor_contra,
                'contrapartida_bens' => $options_proposta['contrapartida_bens'],
                'repasse' => $valor_repasse
            );
            if(isset($options_proposta['cnpj_proponente'])){
                $optionsProjeto['proponente'] = $options_proposta['cnpj_proponente'];
            }
            if(isset($options_proposta['orgao'])){
                $optionsProjeto['orgao'] = $options_proposta['orgao'];
            }

            if ($this->programa_model->check_tem_beneficiario($propostas->proponente, $programa_uso->codigo_programa)) {
                $optionsProjeto['repasse_especifico'] = $options_proposta['repasse_especifico'];
            } else {
                $optionsProjeto['repasse_voluntario'] = $options_proposta['repasse_voluntario'];
            }

            $this->db->where('idProposta', $id);
            $this->db->update('proposta', $optionsProjeto);

            $affected_rows += $this->db->affected_rows();
            if ($this->programa_proposta_model->get_num_programas_by_proposta($id) == 1) {
                if ($opcao_crono_fisico == 2) {
                    //Metas
                    $this->db->select("idMeta, total, quantidade");
                    $this->db->where('Proposta_idProposta', $id);
                    $metas = $this->db->get('meta')->result();

                    $this->db->cache_delete_all();

                    $totalParcialMetasAtePenultima = doubleval(0);
                    foreach ($metas as $keyMeta => $meta) {
                        if ($keyMeta == (count($metas) - 1)) {
                            $difGlobalSomaMetas = $valorGlobal_new - round($totalParcialMetasAtePenultima, 2);
                            $valorMeta_new = round($difGlobalSomaMetas, 2);
                            $valorMetaUnitario = $valorMeta_new / $meta->quantidade;
                        } else {
                            $valorMeta_new = $meta->total * $percentNovoValor;
                            $valorMeta_new = round($valorMeta_new, 2);
                            $valorMetaUnitario = $valorMeta_new / $meta->quantidade;

                            $totalParcialMetasAtePenultima = $totalParcialMetasAtePenultima + $valorMeta_new;
                        }

                        $optionsMeta = array(
                            'total' => $valorMeta_new,
                            'valorUnitario' => $valorMetaUnitario
                        );
                        
                        if(isset($options_proposta['id_programa'])){
                            $optionsMeta['id_programa'] =  $options_proposta['id_programa'];
                        }

                        $this->db->where('Proposta_idProposta', $id);
                        $this->db->where('idMeta', $meta->idMeta);
                        $this->db->update('meta', $optionsMeta);

                        $affected_rows += $this->db->affected_rows();

                        $this->db->cache_delete_all();

                        //Etapas
                        $this->db->select("idEtapa, quantidade, total");
                        $this->db->where('Meta_idMeta', $meta->idMeta);
                        $etapas = $this->db->get('etapa')->result();

                        $totalEtapas = 0;
                        foreach ($etapas as $e) {
                            $totalEtapas += $e->total;
                        }

                        $totalParcialEtapasAtePenultima = doubleval(0);
                        if ($totalEtapas > 0) {
                            foreach ($etapas as $keyEtapas => $etapa) {
                                if ($keyEtapas == (count($etapas) - 1)) {
                                    $difGlobalSomaEtapas = $valorMeta_new - round($totalParcialEtapasAtePenultima, 2);
                                    $valorEtapa_new = round($difGlobalSomaEtapas, 2);
                                    $valorEtapaUnitario = $valorEtapa_new / $etapa->quantidade;
                                } else {
                                    $valorEtapa_new = $etapa->total * $percentNovoValor;
                                    $valorEtapa_new = round($valorEtapa_new, 2);
                                    $valorEtapaUnitario = $valorEtapa_new / $etapa->quantidade;

                                    $totalParcialEtapasAtePenultima = $totalParcialEtapasAtePenultima + $valorEtapa_new;
                                }

                                $optionsEtapa = array(
                                    'total' => $valorEtapa_new,
                                    'valorUnitario' => $valorEtapaUnitario
                                );

                                $this->db->where('Meta_idMeta', $meta->idMeta);
                                $this->db->where('idEtapa', $etapa->idEtapa);
                                $this->db->update('etapa', $optionsEtapa);

                                $affected_rows += $this->db->affected_rows();

                                $this->db->cache_delete_all();
                            }
                        }
                    }

                    //Cronograma Convenente
                    //Obtem todos os programas convenentes da aproposta
                    $this->db->select("idCronograma, parcela");
                    $this->db->where('responsavel', 'CONVENENTE');
                    $this->db->where('Proposta_idProposta', $id);
                    $cronogramas = $this->db->get('cronograma')->result();

                    $this->db->cache_delete_all();

                    // Altera o valor do cronograma
                    foreach ($cronogramas as $cronograma) {
                        $update_cronograma['parcela'] = round($cronograma->parcela * $proporcao_contra, 2);
                        $this->db->where('idCronograma', $cronograma->idCronograma);
                        $this->db->update('cronograma', $update_cronograma);

                        //Pega todas as metas do cronograma
                        $this->db->where('Cronograma_idCronograma', $cronograma->idCronograma);
                        $crono_metas = $this->db->get('cronograma_meta')->result();

                        $proporcao_meta = $update_cronograma['parcela'] / $cronograma->parcela;

                        //Altera os valoes das metas
                        $valor_utilizado_meta = 0;
                        foreach ($crono_metas as $key => $meta) {
                            if ($key == count($crono_metas) - 1) {
                                $update_meta['valor'] = round($update_cronograma['parcela'] - $valor_utilizado_meta, 2);
                            } else {
                                $update_meta['valor'] = round($meta->valor * $proporcao_meta, 2);
                                $valor_utilizado_meta += $update_meta['valor'];
                            }

                            $this->db->where('Cronograma_idCronograma', $cronograma->idCronograma);
                            $this->db->where('Meta_idMeta', $meta->Meta_idMeta);
                            $this->db->update('cronograma_meta', $update_meta);

                            //Pega todas as etapas da meta
                            $this->db->where('Cronograma_meta_idCronograma_meta', $meta->idCronograma_meta);
                            $crono_etapas = $this->db->get('cronograma_etapa')->result();

                            //Altera os valores da etapa
                            $valor_utilizado_etapa = 0;
                            foreach ($crono_etapas as $key => $etapa) {
                                if ($key == count($crono_etapas) - 1) {
                                    $update_etapa['valor'] = round($update_meta['valor'] - $valor_utilizado_etapa, 2);
                                } else {
                                    $update_etapa['valor'] = round($etapa->valor * $percentNovoValor, 2);
                                    $valor_utilizado_etapa += $update_etapa['valor'];
                                }

                                $this->db->where('Cronograma_meta_idCronograma_meta', $meta->idCronograma_meta);
                                $this->db->where('Etapa_idEtapa', $etapa->Etapa_idEtapa);
                                $this->db->update('cronograma_etapa', $update_etapa);
                            }
                        }
                    }




                    //Cronograma Concedente
                    //Pega todos os cronogramas concedentes da proposta
                    $this->db->select("idCronograma, parcela");
                    $this->db->where('responsavel', 'CONCEDENTE');
                    $this->db->where('Proposta_idProposta', $id);
                    $cronogramas = $this->db->get('cronograma')->result();

                    $this->db->cache_delete_all();

                    //Altera o valor do cronograma
                    foreach ($cronogramas as $cronograma) {
                        $update_cronograma['parcela'] = round($cronograma->parcela * $proporcao_repasse, 2);
                        $this->db->where('idCronograma', $cronograma->idCronograma);
                        $this->db->update('cronograma', $update_cronograma);

                        //Pega todas as metas do cronograma
                        $this->db->where('Cronograma_idCronograma', $cronograma->idCronograma);
                        $crono_metas = $this->db->get('cronograma_meta')->result();

                        $proporcao_meta = $update_cronograma['parcela'] / $cronograma->parcela;

                        //Altera o valor das metas
                        $valor_utilizado_meta = 0;
                        foreach ($crono_metas as $key => $meta) {
                            if ($key == count($crono_metas) - 1) {
                                $update_meta['valor'] = round($update_cronograma['parcela'] - $valor_utilizado_meta, 2);
                            } else {
                                $update_meta['valor'] = round($meta->valor * $proporcao_meta, 2);
                                $valor_utilizado_meta += $update_meta['valor'];
                            }

                            $this->db->where('Cronograma_idCronograma', $cronograma->idCronograma);
                            $this->db->where('Meta_idMeta', $meta->Meta_idMeta);
                            $this->db->update('cronograma_meta', $update_meta);

                            //Pega todas as etapas da meta
                            $this->db->where('Cronograma_meta_idCronograma_meta', $meta->idCronograma_meta);
                            $crono_etapas = $this->db->get('cronograma_etapa')->result();

                            //Altera os valores da etapa
                            $valor_utilizado_etapa = 0;
                            foreach ($crono_etapas as $key => $etapa) {
                                if ($key == count($crono_etapas) - 1) {
                                    $update_etapa['valor'] = round($update_meta['valor'] - $valor_utilizado_etapa, 2);
                                } else {
                                    $update_etapa['valor'] = round($etapa->valor * $percentNovoValor, 2);
                                    $valor_utilizado_etapa += $update_etapa['valor'];
                                }

                                $this->db->where('Cronograma_meta_idCronograma_meta', $meta->idCronograma_meta);
                                $this->db->where('Etapa_idEtapa', $etapa->Etapa_idEtapa);
                                $this->db->update('cronograma_etapa', $update_etapa);
                            }
                        }
                    }
                } elseif ($opcao_crono_fisico == 3) {
                    $this->db->where('Proposta_idProposta', $id);
                    $this->db->delete('meta');
                }

                if ($opcao_crono_fisico == 1 || $opcao_crono_fisico == 3) {

                    /*
                     * Zerando o crono desembolso
                     */

                    $this->db->where('Proposta_idProposta', $id);
                    $this->db->delete('cronograma');
                }

                //Cronograma Convenente
                //Obtem todos os programas convenentes da aproposta
                /* $this->db->select("idCronograma, parcela");
                  $this->db->where('responsavel', 'CONVENENTE');
                  $this->db->where('Proposta_idProposta', $id);
                  $cronogramas = $this->db->get('cronograma')->result();

                  $this->db->cache_delete_all();

                  // Altera o valor do cronograma
                  foreach ($cronogramas as $cronograma) {
                  $update_cronograma['parcela'] = round($cronograma->parcela * $proporcao_contra, 2);
                  $this->db->where('idCronograma', $cronograma->idCronograma);
                  $this->db->update('cronograma', $update_cronograma);

                  //Pega todas as metas do cronograma
                  $this->db->where('Cronograma_idCronograma', $cronograma->idCronograma);
                  $crono_metas = $this->db->get('cronograma_meta')->result();

                  $proporcao_meta = $update_cronograma['parcela'] / $cronograma->parcela;

                  //Altera os valoes das metas
                  $valor_utilizado_meta = 0;
                  foreach ($crono_metas as $key => $meta) {
                  if ($key == count($crono_metas) - 1) {
                  $update_meta['valor'] = round($update_cronograma['parcela'] - $valor_utilizado_meta, 2);
                  } else {
                  $update_meta['valor'] = round($meta->valor * $proporcao_meta, 2);
                  $valor_utilizado_meta += $update_meta['valor'];
                  }

                  $this->db->where('Cronograma_idCronograma', $cronograma->idCronograma);
                  $this->db->where('Meta_idMeta', $meta->Meta_idMeta);
                  $this->db->update('cronograma_meta', $update_meta);

                  //Pega todas as etapas da meta
                  $this->db->where('Cronograma_meta_idCronograma_meta', $meta->idCronograma_meta);
                  $crono_etapas = $this->db->get('cronograma_etapa')->result();

                  //Altera os valores da etapa
                  $valor_utilizado_etapa = 0;
                  foreach ($crono_etapas as $key => $etapa) {
                  if ($key == count($crono_etapas) - 1) {
                  $update_etapa['valor'] = round($update_meta['valor'] - $valor_utilizado_etapa, 2);
                  } else {
                  $update_etapa['valor'] = round($etapa->valor * $percentNovoValor, 2);
                  $valor_utilizado_etapa += $update_etapa['valor'];
                  }

                  $this->db->where('Cronograma_meta_idCronograma_meta', $meta->idCronograma_meta);
                  $this->db->where('Etapa_idEtapa', $etapa->Etapa_idEtapa);
                  $this->db->update('cronograma_etapa', $update_etapa);
                  }
                  }
                  }




                  //Cronograma Concedente
                  //Pega todos os cronogramas concedentes da proposta
                  $this->db->select("idCronograma, parcela");
                  $this->db->where('responsavel', 'CONCEDENTE');
                  $this->db->where('Proposta_idProposta', $id);
                  $cronogramas = $this->db->get('cronograma')->result();

                  $this->db->cache_delete_all();

                  //Altera o valor do cronograma
                  foreach ($cronogramas as $cronograma) {
                  $update_cronograma['parcela'] = round($cronograma->parcela * $proporcao_repasse, 2);
                  $this->db->where('idCronograma', $cronograma->idCronograma);
                  $this->db->update('cronograma', $update_cronograma);

                  //Pega todas as metas do cronograma
                  $this->db->where('Cronograma_idCronograma', $cronograma->idCronograma);
                  $crono_metas = $this->db->get('cronograma_meta')->result();

                  $proporcao_meta = $update_cronograma['parcela'] / $cronograma->parcela;

                  //Altera o valor das metas
                  $valor_utilizado_meta = 0;
                  foreach ($crono_metas as $key => $meta) {
                  if ($key == count($crono_metas) - 1) {
                  $update_meta['valor'] = round($update_cronograma['parcela'] - $valor_utilizado_meta, 2);
                  } else {
                  $update_meta['valor'] = round($meta->valor * $proporcao_meta, 2);
                  $valor_utilizado_meta += $update_meta['valor'];
                  }

                  $this->db->where('Cronograma_idCronograma', $cronograma->idCronograma);
                  $this->db->where('Meta_idMeta', $meta->Meta_idMeta);
                  $this->db->update('cronograma_meta', $update_meta);

                  //Pega todas as etapas da meta
                  $this->db->where('Cronograma_meta_idCronograma_meta', $meta->idCronograma_meta);
                  $crono_etapas = $this->db->get('cronograma_etapa')->result();

                  //Altera os valores da etapa
                  $valor_utilizado_etapa = 0;
                  foreach ($crono_etapas as $key => $etapa) {
                  if ($key == count($crono_etapas) - 1) {
                  $update_etapa['valor'] = round($update_meta['valor'] - $valor_utilizado_etapa, 2);
                  } else {
                  $update_etapa['valor'] = round($etapa->valor * $percentNovoValor, 2);
                  $valor_utilizado_etapa += $update_etapa['valor'];
                  }

                  $this->db->where('Cronograma_meta_idCronograma_meta', $meta->idCronograma_meta);
                  $this->db->where('Etapa_idEtapa', $etapa->Etapa_idEtapa);
                  $this->db->update('cronograma_etapa', $update_etapa);
                  }
                  }
                  }

                  /*
                 * Versão antiga
                 */


                /* $totalParcialCronoConvenenteAtePenultima = doubleval(0);
                  foreach ($cronogramas as $keyCronoConve => $cronograma) {
                  $proporcao_crono = $proporcao_contra;

                  if ($keyCronoConve == (count($cronogramas) - 1)) {
                  $difGlobalSomaCronoConve = $valor_contra - round($totalParcialCronoConvenenteAtePenultima, 2);
                  $valorCrono_new = round($difGlobalSomaCronoConve, 2);
                  } else {
                  $valorCrono_new = $cronograma->parcela * $proporcao_crono;
                  $valorCrono_new = round($valorCrono_new, 2);

                  $totalParcialCronoConvenenteAtePenultima = $totalParcialCronoConvenenteAtePenultima + $valorCrono_new;
                  }

                  $optionsCronograma = array(
                  'parcela' => $valorCrono_new
                  );

                  //proporcao do valor associado a meta
                  $proporcao_meta = $valorCrono_new / $cronograma->parcela;

                  $this->db->where('Proposta_idProposta', $id);
                  $this->db->where('idCronograma', $cronograma->idCronograma);
                  $this->db->update('cronograma', $optionsCronograma);

                  $affected_rows += $this->db->affected_rows();

                  $this->db->cache_delete_all();

                  $this->db->where('Cronograma_idCronograma', $cronograma->idCronograma);
                  $crono_metas = $this->db->get('cronograma_meta')->result();

                  $this->db->cache_delete_all();

                  $totalParcialCronoConveneteMetasAtePenultimo = doubleval(0);
                  foreach ($crono_metas as $keyCronoConveMeta => $crono_meta) {
                  if ($keyCronoConveMeta == (count($crono_metas) - 1)) {
                  $difGlobalSomaCronoConveMetas = $valorCrono_new - round($totalParcialCronoConveneteMetasAtePenultimo, 2);
                  $valorCronoMeta_new = round($difGlobalSomaCronoConveMetas, 2);
                  } else {
                  $valorCronoMeta_new = $crono_meta->valor * $proporcao_meta;
                  $valorCronoMeta_new = round($valorCronoMeta_new, 2);

                  $totalParcialCronoConveneteMetasAtePenultimo = $totalParcialCronoConveneteMetasAtePenultimo + $valorCronoMeta_new;
                  }

                  $optionsCronoMeta = array(
                  'valor' => $valorCronoMeta_new
                  );

                  $proporcao_etapa = $valorCronoMeta_new / $crono_meta->valor;

                  $this->db->where('Cronograma_idCronograma', $cronograma->idCronograma);
                  $this->db->where('Meta_idMeta', $crono_meta->Meta_idMeta);
                  $this->db->update('cronograma_meta', $optionsCronoMeta);

                  $affected_rows += $this->db->affected_rows();

                  $this->db->cache_delete_all();

                  $this->db->where('Cronograma_meta_idCronograma_meta', $crono_meta->idCronograma_meta);
                  $crono_etapas = $this->db->get('cronograma_etapa')->result();


                  $this->db->cache_delete_all();

                  //Valor das etapas
                  $totalEtapas = 0;
                  foreach ($crono_etapas as $e) {
                  $totalEtapas += $e->valor;
                  }

                  $totalParcialCronoConvenenteMetasEtapasAtePenultima = doubleval(0);
                  if ($totalEtapas > 0) {
                  foreach ($crono_etapas as $keyCronoConveMetaEtapa => $crono_etapa) {
                  if ($keyCronoConveMetaEtapa == (count($crono_etapas) - 1)) {
                  $difGlobalSomaCronoConveMetasEtapas = $valorCronoMeta_new - round($totalParcialCronoConvenenteMetasEtapasAtePenultima, 2);
                  $valorCronoEtapa_new = round($difGlobalSomaCronoConveMetasEtapas, 2);
                  } else {
                  $valorCronoEtapa_new = $crono_etapa->valor * $proporcao_etapa;
                  $valorCronoEtapa_new = round($valorCronoEtapa_new, 2);

                  $totalParcialCronoConvenenteMetasEtapasAtePenultima = $totalParcialCronoConvenenteMetasEtapasAtePenultima + $valorCronoEtapa_new;
                  }

                  $optionsCronoEtapa = array(
                  'valor' => $valorCronoEtapa_new
                  );

                  $this->db->where('Cronograma_meta_idCronograma_meta', $crono_meta->idCronograma_meta);
                  $this->db->where('Etapa_idEtapa', $crono_etapa->Etapa_idEtapa);
                  $this->db->update('cronograma_etapa', $optionsCronoEtapa);

                  $affected_rows += $this->db->affected_rows();

                  $this->db->cache_delete_all();
                  }
                  }
                  }
                  }

                  //Cronograma Concedente
                  $this->db->select("idCronograma, parcela");
                  $this->db->where('responsavel', 'CONCEDENTE');
                  $this->db->where('Proposta_idProposta', $id);
                  $cronogramas = $this->db->get('cronograma')->result();

                  $this->db->cache_delete_all();

                  $totalParcialCronoConcedenteAtePenultima = doubleval(0);
                  foreach ($cronogramas as $keyCronoConce => $cronograma) {
                  $proporcao_crono = $proporcao_repasse;

                  if ($keyCronoConce == (count($cronogramas) - 1)) {
                  $difGlobalSomaCronoConce = $valor_repasse - round($totalParcialCronoConcedenteAtePenultima, 2);
                  $valorCrono_new = round($difGlobalSomaCronoConce, 2);
                  } else {
                  $valorCrono_new = $cronograma->parcela * $proporcao_crono;
                  $valorCrono_new = round($valorCrono_new, 2);

                  $totalParcialCronoConcedenteAtePenultima = $totalParcialCronoConcedenteAtePenultima + $valorCrono_new;
                  }

                  $optionsCronograma = array(
                  'parcela' => $valorCrono_new
                  );

                  $proporcao_meta = $valorCrono_new / $cronograma->parcela;

                  $this->db->where('Proposta_idProposta', $id);
                  $this->db->where('idCronograma', $cronograma->idCronograma);
                  $this->db->update('cronograma', $optionsCronograma);

                  $affected_rows += $this->db->affected_rows();

                  $this->db->cache_delete_all();

                  $this->db->where('Cronograma_idCronograma', $cronograma->idCronograma);
                  $crono_metas = $this->db->get('cronograma_meta')->result();

                  $this->db->cache_delete_all();

                  $totalParcialCronoConcedenteMetasAtePenualtima = doubleval(0);
                  foreach ($crono_metas as $keyCronoConceMeta => $crono_meta) {
                  if ($keyCronoConceMeta == (count($crono_metas) - 1)) {
                  $difGlobalSomaCronoConceMetas = $valorCrono_new - round($totalParcialCronoConcedenteMetasAtePenualtima, 2);
                  $valorCronoMeta_new = round($difGlobalSomaCronoConceMetas, 2);
                  } else {
                  $valorCronoMeta_new = $crono_meta->valor * $proporcao_meta;
                  $valorCronoMeta_new = round($valorCronoMeta_new, 2);

                  $totalParcialCronoConcedenteMetasAtePenualtima = $totalParcialCronoConcedenteMetasAtePenualtima + $valorCronoMeta_new;
                  }

                  $optionsCronoMeta = array(
                  'valor' => $valorCronoMeta_new
                  );

                  $proporcao_etapa = $valorCronoMeta_new / $crono_meta->valor;

                  $this->db->where('Cronograma_idCronograma', $cronograma->idCronograma);
                  $this->db->where('Meta_idMeta', $crono_meta->Meta_idMeta);
                  $this->db->update('cronograma_meta', $optionsCronoMeta);

                  $this->db->cache_delete_all();

                  $this->db->where('Cronograma_meta_idCronograma_meta', $crono_meta->idCronograma_meta);
                  $crono_etapas = $this->db->get('cronograma_etapa')->result();

                  $affected_rows += $this->db->affected_rows();

                  $this->db->cache_delete_all();

                  $totalEtapas = 0;
                  foreach ($crono_etapas as $e) {
                  $totalEtapas += $e->valor;
                  }

                  $totalParcialCronoConcedenteMetasEtapasAtePenultima = doubleval(0);
                  if ($totalEtapas > 0) {
                  foreach ($crono_etapas as $keyCronoConceMEtaEtapa => $crono_etapa) {
                  if ($keyCronoConceMEtaEtapa == (count($crono_etapas) - 1)) {
                  $difGlobalSomaCronoConceMetasEtapas = $valorCronoMeta_new - round($totalParcialCronoConcedenteMetasEtapasAtePenultima, 2);
                  $valorCronoEtapa_new = round($difGlobalSomaCronoConceMetasEtapas, 2);
                  } else {
                  $valorCronoEtapa_new = $crono_etapa->valor * $proporcao_etapa;
                  $valorCronoEtapa_new = round($valorCronoEtapa_new, 2);

                  $totalParcialCronoConcedenteMetasEtapasAtePenultima = $totalParcialCronoConcedenteMetasEtapasAtePenultima + $valorCronoEtapa_new;
                  }

                  $optionsCronoEtapa = array(
                  'valor' => $valorCronoEtapa_new
                  );

                  $this->db->where('Cronograma_meta_idCronograma_meta', $crono_meta->idCronograma_meta);
                  $this->db->where('Etapa_idEtapa', $crono_etapa->Etapa_idEtapa);
                  $this->db->update('cronograma_etapa', $optionsCronoEtapa);

                  $affected_rows += $this->db->affected_rows();

                  $this->db->cache_delete_all();
                  }
                  }
                  }
                  }

                  /*                 * *******Ajuste dos valores Cronofisico************* */
//                $this->db->where('Proposta_idProposta', $id);
//                $query1 = $this->db->get('meta');
//
//                $options_obj = array();
//                $lista_valores = array();
//                foreach ($query1->result() as $obj1) {
//                    $this->load->model('trabalho_model');
//
//                    $this->db->where('Meta_idMeta', $obj1->idMeta);
//                    $query2 = $this->db->get('etapa');
//
//                    //metapas
//                    $options_obj = array();
//                    foreach ($query2->result() as $obj2) {
//                        $restante_etapa = $this->trabalho_model->obter_restante_etapa($obj2->idEtapa, true);
//
//                        $lista_valores[$obj1->idMeta][] = array('idEtapa' => $obj2->idEtapa, 'total' => $restante_etapa);
//                    }
//                }
//
//                if (count($lista_valores) > 0) {
//                    foreach ($lista_valores as $key => $value) {
//                        $total = 0;
//                        foreach ($value as $arr) {
//                            $total += $arr['total'];
//                            $this->db->where('idEtapa', $arr['idEtapa']);
//                            $this->db->update('etapa', array('total' => $arr['total']));
//
//                            $this->db->flush_cache();
//                        }
//
//                        $this->db->where('idMeta', $key);
//                        $this->db->update('meta', array('total' => $total));
//
//                        $this->db->flush_cache();
//                    }
//                }
                /*                 * ********************** */


                //Bens
                $this->db->select("idDespesa, total, quantidade");
                $this->db->where('Proposta_idProposta', $id);
                $despesas = $this->db->get('despesa')->result();

                $this->db->cache_delete_all();

                $totalParcialDespesasAtePenultima = doubleval(0);
                foreach ($despesas as $keyDespesas => $despesa) {
                    if ($keyDespesas == (count($despesas) - 1)) {
                        $difGlobalDespesas = $valorGlobal_new - round($totalParcialDespesasAtePenultima, 2);
                        $valorDespesa_new = round($difGlobalDespesas, 2);
                        $valorDespesaUnitario = $valorDespesa_new / $despesa->quantidade;
                    } else {
                        $valorDespesa_new = $despesa->total * $percentNovoValor;
                        $valorDespesa_new = round($valorDespesa_new, 2);
                        $valorDespesaUnitario = $valorDespesa_new / $despesa->quantidade;

                        $totalParcialDespesasAtePenultima = $totalParcialDespesasAtePenultima + $valorDespesa_new;
                    }

                    $optionsDespesa = array(
                        'total' => $valorDespesa_new,
                        'valor_unitario' => $valorDespesaUnitario
                    );
                    
                    if(isset($options_proposta['id_programa'])){
                        $optionsDespesa['id_programa'] = $options_proposta['id_programa']; 
                    }

                    $this->db->where('Proposta_idProposta', $id);
                    $this->db->where('idDespesa', $despesa->idDespesa);
                    $this->db->update('despesa', $optionsDespesa);

                    $affected_rows += $this->db->affected_rows();

                    $this->db->cache_delete_all();
                }
            }

            return $affected_rows;
        }
        return 0;
    }

    public function add_endereco_copia($options = array()) {
        $this->db->insert('endereco', $options);
    }

    public function getCidadePorCnpj($cnpj) {
        $this->load->model('proponente_siconv_model');

        $this->db->distinct();
        $this->db->where('cnpj', $cnpj);
        $cidade = $this->db->get('cnpj_siconv')->row(0);

        $this->db->where('Nome', $this->proponente_siconv_model->get_municipio_nome($cidade->id_cidade)->municipio);
        $this->db->where('Sigla', $cidade->sigla);
        $cidade_nome = $this->db->get('cidades_siconv')->row(0);

        return array('cidade_codigo' => $cidade_nome->Codigo, 'cidade_nome' => $cidade_nome->Nome, 'estado' => $cidade_nome->Sigla);
    }

    public function getEstadoPorCidade($cidade) {
        $this->db->where('nome', $cidade);
        $estado = $this->db->get('cidades')->row(0);

        return $estado->estados_cod_estados;
    }

    public function retorna_datas_meta($id) {
        $this->db->select("data_inicio, data_termino");
        $this->db->where('idMeta', $id);
        return $this->db->get('meta')->row(0);
    }

    public function update_datas_meta_etapa($id, $data_inicio, $data_termino) {
        $this->load->model('data_model');

        $this->db->select('idMeta, data_inicio, data_termino');
        $this->db->where('Proposta_idProposta', $id);
        $metas = $this->db->get('meta')->result();

        foreach ($metas as $meta) {
            $data_ini_meta = $meta->data_inicio;
            $data_fim_meta = $meta->data_termino;

            $diffIniMeta = $this->data_model->retornaDiffDatas($data_inicio, $data_ini_meta);
            if ($diffIniMeta < 0)
                $data_ini_meta = $data_inicio;

            $diffFimMeta = $this->data_model->retornaDiffDatas($data_fim_meta, $data_termino);
            if ($diffFimMeta > 0)
                $data_fim_meta = $data_termino;

            $this->db->where('idMeta', $meta->idMeta);
            $this->db->update('meta', array('data_inicio' => $data_ini_meta, 'data_termino' => $data_fim_meta));

            $this->db->cache_delete_all();

            $this->db->select('idEtapa, data_inicio, data_termino');
            $this->db->where('Meta_idMeta', $meta->idMeta);
            $etapas = $this->db->get('etapa')->result();

            foreach ($etapas as $etapa) {
                $data_ini_etapa = $etapa->data_inicio;
                $data_fim_etapa = $etapa->data_termino;

                $diffIniEtapa = $this->data_model->retornaDiffDatas($data_ini_meta, $data_ini_etapa);
                if ($diffIniEtapa < 0)
                    $data_ini_etapa = $data_ini_meta;

                $diffFimEtapa = $this->data_model->retornaDiffDatas($data_fim_etapa, $data_fim_meta);
                if ($diffFimEtapa > 0)
                    $data_fim_etapa = $data_fim_meta;

                $this->db->where('idEtapa', $etapa->idEtapa);
                $this->db->update('etapa', array('data_inicio' => $data_ini_etapa, 'data_termino' => $data_fim_etapa));

                $this->db->cache_delete_all();
            }
        }
    }

    public function obter_total_cronograma_utilizado($idProposta, $respCronograma) {
        $this->db->select('SUM(parcela) AS total_parcela');
        $this->db->where('Proposta_idProposta', $idProposta);
        $this->db->where('responsavel', $respCronograma);
        $total_parcela = $this->db->get('cronograma')->row(0)->total_parcela;

        if ($respCronograma == "CONCEDENTE")
            $this->db->select('repasse AS valor');
        else
            $this->db->select('contrapartida_financeira AS valor');

        $this->db->where('idProposta', $idProposta);
        $valor = $this->db->get('proposta')->row(0)->valor;

        return $valor - $total_parcela;
    }

    public function desassocia_valor($crono_id, $id_meta, $options) {
        $meta_cronograma = $this->obter_meta_cronograma($crono_id, $id_meta);
        $this->db->where('Cronograma_meta_idCronograma_meta', $meta_cronograma->idCronograma_meta);
        $etapas = $this->db->get('cronograma_etapa')->result();

        //$this->load->model('system_logs');
        //$this->system_logs->add_log(EDT_META_CRONO." - Crono: ".$this->obter_cronograma_por_id($crono_id)->responsavel.", Meta: ".substr($this->obter_meta_por_id($id_meta)->especificacao, 0 , 100).", Valor: ".number_format($options['valor'], 2, ",", "."));

        foreach ($etapas as $etapa) {
            $this->db->where('idCronograma_etapa', $etapa->idCronograma_etapa);
            $this->db->delete('cronograma_etapa');

            //$this->system_logs->add_log(EDT_ETAPA_CRONO." - ID Crono Meta: ".$etapa->idCronograma_etapa.", Etapa: ".substr($this->obter_etapa_por_id($etapa->Etapa_idEtapa)->especificacao, 0, 100).", Valor: ".number_format($optionsEtapa['valor'], 2, ",", "."));
        }

        $this->db->where('Cronograma_idCronograma', $crono_id);
        $this->db->where('Meta_idMeta', $id_meta);
        $this->db->delete('cronograma_meta');
    }

    public function desassocia_etapa($idCronograma_meta, $idEtapa) {
        $this->db->where('Cronograma_meta_idCronograma_meta', $idCronograma_meta);
        $this->db->where('Etapa_idEtapa', $idEtapa);

        $etapa = $this->db->get('cronograma_etapa')->row(0);
        $this->db->cache_delete_all();

        $this->db->where('idCronograma_etapa', $etapa->idCronograma_etapa);
        $this->db->delete('cronograma_etapa');

        //$this->load->model('system_logs');
        //$this->system_logs->add_log(EDT_ETAPA_CRONO." - ID Crono Meta: ".$etapa->idCronograma_etapa.", Etapa: ".substr($this->obter_etapa_por_id($etapa->Etapa_idEtapa)->especificacao, 0, 100).", Valor: ".number_format(0.00, 2, ",", "."));
    }

    public function obtem_percent_proposta($id) {
        $this->db->where('idProposta', $id);
        $valor = $this->db->get('proposta')->row(0);

        return $valor->percentual;
    }

    public function verifica_meta_foi_associado($idMeta) {
        $total = 0;

        $this->db->select('COUNT(*) AS num');
        $this->db->where('Meta_idMeta', $idMeta);
        $query = $this->db->get('cronograma_meta')->row(0);

        $total += $query->num;

        $this->db->select('COUNT(*) AS num');
        $this->db->where('Meta_idMeta', $idMeta);
        $query2 = $this->db->get('etapa')->row(0);

        $total += $query2->num;

        return $total > 0;
    }

    public function verifica_etapa_foi_associada($idEtapa) {
        $this->db->select('COUNT(*) AS num');
        $this->db->where('Etapa_idEtapa', $idEtapa);
        $query = $this->db->get('cronograma_etapa')->row(0);

        return $query->num > 0;
    }

    public function verifica_meta_foi_associada_por_crono($idMeta, $idCronograma) {
        $this->db->select('COUNT(*) AS num');
        $this->db->where('Meta_idMeta', $idMeta);
        $this->db->where('Cronograma_idCronograma', $idCronograma);
        $query = $this->db->get('cronograma_meta')->row(0);

        return $query->num > 0;
    }

    public function verifica_etapa_foi_associada_por_crono($idMeta, $idCronograma, $idEtapa) {
        $this->db->where('Meta_idMeta', $idMeta);
        $this->db->where('Cronograma_idCronograma', $idCronograma);
        $query = $this->db->get('cronograma_meta')->row(0);

        $this->db->select('COUNT(*) AS num');
        $this->db->where('Etapa_idEtapa', $idEtapa);
        $this->db->where('Cronograma_meta_idCronograma_meta', $query->idCronograma_meta);
        $query = $this->db->get('cronograma_etapa')->row(0);

        return $query->num > 0;
    }

    public function verifica_crono_foi_associado($idCrono) {
        $total = 0;
        $this->db->where('Cronograma_idCronograma', $idCrono);
        $metasCrono = $this->db->get('cronograma_meta')->result();
        $this->db->flush_cache();

        foreach ($metasCrono as $meta) {
            $this->db->select('SUM(valor) AS SOMA');
            $this->db->where('Cronograma_meta_idCronograma_meta', $meta->idCronograma_meta);
            $query1 = $this->db->get('cronograma_etapa')->row(0);

            $total += $query1->SOMA;

            $this->db->flush_cache();

            $this->db->select('SUM(valor) AS SOMA');
            $this->db->where('idCronograma_meta', $meta->idCronograma_meta);
            $query2 = $this->db->get('cronograma_meta')->row(0);

            $total += $query2->SOMA;


            $this->db->flush_cache();
        }

        return $total > 0;
    }

    public function tudo_ok_cronograma($idCronograma, $parcela) {
        $total_cronograma = 0;
        $this->db->where('Cronograma_idCronograma', $idCronograma);
        $metasCrono = $this->db->get('cronograma_meta')->result();

        foreach ($metasCrono as $meta) {
            $this->db->where('Cronograma_meta_idCronograma_meta', $meta->idCronograma_meta);
            $etapas = $this->db->get('cronograma_etapa')->result();
            foreach ($etapas as $etapa)
                $total_cronograma += $etapa->valor;
        }

        return round($total_cronograma, 2) == round($parcela, 2);
    }

    public function obter_proposta_by_id($id) {

        $this->db->where('idProposta', $id);
        return $this->db->get('proposta')->row(0);
    }

}