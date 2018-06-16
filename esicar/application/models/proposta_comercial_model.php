<?php

class proposta_comercial_model extends CI_Model {

    public function get_all_propostas() {
        $this->load->model('usuariomodel');

        $cnpjs = $this->usuariomodel->get_cnpjs_by_usuario($this->session->userdata('id_usuario'));

        $lista_cnpjs = array();
        foreach ($cnpjs as $c)
            $lista_cnpjs[] = $c->cnpj;

        $result = null;
        if (count($lista_cnpjs) > 0) {
            $this->db->where_in('cnpj_proposta_comercial', $lista_cnpjs);
            $this->db->where('id_usuario', $this->session->userdata('id_usuario'));
            $this->db->or_where("(cnpj_proposta_comercial = '' AND tipo_proposta = 'Governos Estaduais')", null, FALSE);
            $this->db->order_by('id_proposta_comercial', 'desc');
            $result = $this->db->get('proposta_comercial')->result();
        }

        return $result;
    }

    public function get_proposta_by_id($id) {
        $this->db->where('id_proposta_comercial', $id);
        return $this->db->get('proposta_comercial')->row(0);
    }

    public function get_tipo_proposta_array() {
        $this->db->distinct();
        $this->db->select('tipo_proposta');
        $this->db->where_not_in('tipo_proposta', 'Casadinha');
        $result = $this->db->get('info_proposta_comercial')->result();

        $lista = array('' => 'Selecione');
        foreach ($result as $r)
            $lista[$r->tipo_proposta] = $r->tipo_proposta;

        return $lista;
    }

    public function get_tipo_empresa_interesse_array() {
        $this->db->distinct();
        $this->db->select('entidade');
        $this->db->where('tipo_proposta', 'Empresas Interesse Público');
        $result = $this->db->get('info_proposta_comercial')->result();

        $lista = array('' => 'Selecione');
        foreach ($result as $r)
            $lista[$r->entidade] = $r->entidade;

        return $lista;
    }

    public function calcula_proposta($options, $empresas) {
        $this->load->model('cidade_tag');
        $this->load->model('cnpj_siconv');
        $this->load->model('usuariomodel');

        if ($options['tipo_proposta'] != "Governos Estaduais") {
            $cnpj_proposta = $this->usuariomodel->get_cnpjs_by_usuario($this->session->userdata('id_usuario'));
            $options['cnpj_proposta_comercial'] = $cnpj_proposta[0]->cnpj;

            $dados_cidade_proposta = $this->cnpj_siconv->get_cidade_by_cnpj_siconv($options['cnpj_proposta_comercial']);

            $cod_cidade_proposta = $dados_cidade_proposta->Codigo;
            $cidade_proposta = $dados_cidade_proposta->Nome;

            $populacao_proposta = $this->cidade_tag->get_cidade_tag_from_nome_cidade($cod_cidade_proposta)->populacao;
            $options['entidade_alvo'] = $cod_cidade_proposta;
            $options['nome_entidade'] = $cidade_proposta;

            $this->db->where('tipo_proposta', $options['tipo_proposta']);
            if (isset($options['capital'])) {
                if ($options['capital'] == 1)
                    $this->db->where('entidade', 'CAPITAL');
            }else {
                if ($options['tipo_proposta'] == "Consórcios Públicos") {
                    $qtd_cnpjs = $options['num_associado'] > 30 ? 30 : $options['num_associado'];
                    //$this->db->where('num_cnpjs >= ', $qtd_cnpjs);
                } else if ($options['tipo_proposta'] == "Parlamentar") {
                    $qtd_cnpjs_parlamentar = $options['num_parlamentar'] > 50 ? 50 : $options['num_parlamentar'];
                    //$this->db->where('num_cnpjs >= ', $qtd_cnpjs_parlamentar);
                } else if ($options['tipo_proposta'] == "Governos Municipais") {
                    if ($populacao_proposta <= 30000) {
                        $this->db->where('alvo_populacao', 30000);
                    } else {
                        $this->db->where('alvo_populacao <=', $populacao_proposta);
                    }
                }
            }

            $resultado = $this->db->get('info_proposta_comercial')->result();
            if (isset($resultado[count($resultado) - 1])) {
                $valores = $resultado[count($resultado) - 1];
            }

            $valor_proposta = 0;
            if (isset($valores->valor_um_ano)) {
                $valor_proposta = $valores->valor_um_ano;
            }

            if ($options['tipo_proposta'] == "Parlamentar") {
                $valor_proposta = $valor_proposta * $qtd_cnpjs_parlamentar;
            } else if ($options['tipo_proposta'] == "Organizações Sociais Sem Fins Lucrativos") {
                $valor_proposta = $valor_proposta * count($empresas);
            }

            $valor_adicional = 0;
            //$valor_adicional_auta = 0;
            $valor_adicional_sem = 0;

            if ($options['tipo_proposta'] == "Empresas Interesse Público") {
                $valor_adicional = $this->calcula_valor_adicional($options);
            } else if ($options['tipo_proposta'] == "Governos Municipais") {
                $valor_adicional = $this->calcula_valor_adicional($options, 1);
                //$valor_adicional_auta = $this->calcula_valor_adicional($options, 2);
                $valor_adicional_sem = $this->calcula_valor_adicional($options, 3);
                $desconto = $valor_adicional_sem['desconto'];
                $valor_adicional_sem = $valor_adicional_sem['valor_adicional'];
            }

            $valor_proposta += $valor_adicional + $valor_adicional_sem;
        } else {
            $valor_proposta = str_replace(",", ".", str_replace(".", "", $options['valor_proposta_comercial']));
        }

        $valor_entrada_proposta = doubleval(0);

        if ($options['tipo_proposta'] == "Empresas Interesse Público")
            $options['entidade_alvo'] = $options['entidade'];

        if ($options['num_cnpj'] >= 1)
            $options['valor_adicional'] = $valor_adicional;

        //if($options['num_cnpj_autarquias'] >= 1)
        //$options['valor_adicional_autarquias'] = $valor_adicional_auta;

        if ($options['num_cnpj_sem_fim'] >= 1){
            $options['valor_adicional_sem_fim'] = $valor_adicional_sem;
            $options['percentual_desconto'] = $desconto;
        }

        $options['periodo_proposta_comercial'] = 12;
        $options['parcelas_proposta_comercial'] = $options['parcelas_proposta_comercial'];
        $options['valor_proposta_comercial'] = $valor_proposta;
        $options['entrada_proposta_comercial'] = $valor_entrada_proposta;
        $options['data_cadastro'] = date("Y-m-d");
        $options['num_cnpj'] = $options['num_cnpj'];
        $options['id_usuario'] = $this->session->userdata('id_usuario');

        unset($options['entidade']);
        unset($options['capital']);
        unset($options['num_associado']);
        unset($options['num_parlamentar']);
        unset($options['cnpj_extra']);
        #corrigiar para buscar o valor das Empresas Interesse Público

        $this->db->insert('proposta_comercial', $options);
        if ($empresas != NULL) {
            $data_empresa['id_proposta_comercial'] = $this->db->insert_id();
            foreach ($empresas as $value) {
                $data_empresa['cnpj'] = $value;
                $this->db->insert('empresas_proposta_comercial', $data_empresa);
            }
        }
    }

    public function calcula_valor_adicional($options, $tipo = null) {
        if ($options['tipo_proposta'] == "Governos Municipais") {
            if ($options['num_cnpj'] >= 1) {
                $this->db->where('tipo_proposta', 'Empresas Interesse Público');

                $this->db->where('entidade', 'ECONOMIA MISTA');

                $valores = $this->db->get('info_proposta_comercial')->row(0);

                $valor_adicional = $valores->valor_um_ano;
                if ($options['periodo'] == 24)
                    $valor_adicional = $valores->valor_dois_anos;

                $valor_adicional = $valor_adicional * ($options['num_cnpj']);

                if ($tipo == 1)
                    return $valor_adicional;
            }

            /* if($options['num_cnpj_autarquias'] >= 1){
              $this->db->where('tipo_proposta', 'Empresas Interesse Público');

              $this->db->where('entidade', 'AUTARQUIAS');

              $valores = $this->db->get('info_proposta_comercial')->row(0);

              $valor_adicional = $valores->valor_um_ano;
              if($options['periodo'] == 24)
              $valor_adicional = $valores->valor_dois_anos;

              $valor_adicional = $valor_adicional*($options['num_cnpj_autarquias']);

              if($tipo == 2)
              return $valor_adicional;
              } */

            if ($options['num_cnpj_sem_fim'] >= 1) {
                $this->db->where('tipo_proposta', 'Organizações Sociais Sem Fins Lucrativos');
                $valores = $this->db->get('info_proposta_comercial')->row(0);
                
                if($options['num_cnpj_sem_fim'] >= 1 && $options['num_cnpj_sem_fim'] <= 5){
                    $percentual_desconto = 40;
                }elseif($options['num_cnpj_sem_fim']>=6 && $options['num_cnpj_sem_fim'] <=10){
                    $percentual_desconto = 45;
                }elseif($options['num_cnpj_sem_fim']>=11 && $options['num_cnpj_sem_fim'] <=20){
                    $percentual_desconto = 50;
                }elseif($options['num_cnpj_sem_fim']>=21 && $options['num_cnpj_sem_fim'] <=50){
                    $percentual_desconto = 55;
                }elseif($options['num_cnpj_sem_fim']>=51 && $options['num_cnpj_sem_fim'] <=100){
                    $percentual_desconto = 65;
                }elseif($options['num_cnpj_sem_fim']>=101 && $options['num_cnpj_sem_fim'] <=200){
                    $percentual_desconto = 75;
                }elseif($options['num_cnpj_sem_fim']>=201){
                    $percentual_desconto = 83;
                }
                $desconto = (12000 * $percentual_desconto) / 100;
                $valor_adicional = 12000 - $desconto;
                $valor_adicional = $valor_adicional * ($options['num_cnpj_sem_fim']);

                if ($tipo == 3){
                    return array('valor_adicional' => $valor_adicional, 'desconto' => $percentual_desconto);
                }
            }
        }else if ($options['tipo_proposta'] == "Empresas Interesse Público") {
            $this->db->where('tipo_proposta', 'Organizações Sociais Sem Fins Lucrativos');
            $this->db->where('entidade', $options['entidade']);
            $valores = $this->db->get('info_proposta_comercial')->row(0);

            $valor_adicional = $valores->valor_um_ano;
            return $valor_adicional;
        }
        return 0;
    }

    function obterEstadoNome($estado) {

        switch ($estado) {

            case "1": return "AC";
            case "2": return "AL";
            case "4": return "AM";
            case "3": return "AP";
            case "5": return "BA";
            case "6": return "CE";
            case "7": return "DF";
            case "8": return "ES";
            case "10": return "GO";
            case "11": return "MA";
            case "14": return "MG";
            case "13": return "MS";
            case "12": return "MT";
            case "15": return "PA";
            case "16": return "PB";
            case "18": return "PE";
            case "19": return "PI";
            case "17": return "PR";
            case "20": return "RJ";
            case "21": return "RN";
            case "23": return "RO";
            case "9": return "RR";
            case "22": return "RS";
            case "25": return "SC";
            case "27": return "SE";
            case "26": return "SP";
            case "24": return "TO";
        }
        return $estado;
    }

    public function get_nome_mes($mes) {
        switch ($mes) {
            case "01":
                return "Janeiro";
                break;
            case "02":
                return "Fevereiro";
                break;
            case "03":
                return "Março";
                break;
            case "04":
                return "Abril";
                break;
            case "05":
                return "Maio";
                break;
            case "06":
                return "Junho";
                break;
            case "07":
                return "Julho";
                break;
            case "08":
                return "Agosto";
                break;
            case "09":
                return "Setembro";
                break;
            case "10":
                return "Outubro";
                break;
            case "11":
                return "Novembro";
                break;
            case "12":
                return "Dezembro";
                break;
        }
    }

    public function get_empresas_by_proposta($id_proposta_comercial) {
        $query = $this->db->get_where("empresas_proposta_comercial", array("id_proposta_comercial" => $id_proposta_comercial));
        if ($query->num_rows > 0) {
            return $query->result();
        } else {
            return null;
        }
    }

}
