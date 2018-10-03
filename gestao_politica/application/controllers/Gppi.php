<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Gppi extends CI_Controller {

    function __construct() {
        parent::__construct();
        if ($this->session->userdata('sessao') == FALSE) {
            redirect('/login');
        }
    }

    public function index() {
        ini_set("max_execution_time", 0);
        ini_set("memory_limit", "-1");

        $this->load->model('Beneficio_Model');
        $this->load->model('Usuario_Sistema_Model');
        $this->load->model('Util_Model');
        $beneficios = $this->Beneficio_Model->get_all_beneficio();

        $data['beneficios'] = $beneficios;
        $data['beneficio_model'] = $this->Beneficio_Model;
        $data['usuario_sistema_model'] = $this->Usuario_Sistema_Model;
        $data['util_model'] = $this->Util_Model;

        $this->load->view('gppi/index', $data);
    }

    public function processos() {
        ini_set("max_execution_time", 0);
        ini_set("memory_limit", "-1");

        $this->load->model('GPPI_Model');
        $this->load->model('Util_Model');

        $data['gppi_model'] = $this->GPPI_Model;
        $data['util_model'] = $this->Util_Model;

        $this->load->view('gppi/processos', $data);
    }

    public function novo_projeto() {
        ini_set("max_execution_time", 0);
        ini_set("memory_limit", "-1");

        $this->load->model('GPPI_Model');
        $this->load->model('Util_Model');

        if ($this->input->post() != false) {
            redirect('Gppi/politicas_governo');
        }

        $data['gppi_model'] = $this->GPPI_Model;
        $data['util_model'] = $this->Util_Model;

        $this->load->view('gppi/novo_projeto', $data);
    }

    public function politicas_governo() {
        ini_set("max_execution_time", 0);
        ini_set("memory_limit", "-1");

        $this->load->model('GPPI_Model');
        $this->load->model('Util_Model');

        if ($this->input->post() != false) {
            redirect('Gppi/politicas_governo');
        }

        $data['gppi_model'] = $this->GPPI_Model;
        $data['util_model'] = $this->Util_Model;

        $this->load->view('gppi/politicas_governo', $data);
    }

    public function beneficios() {
        ini_set("max_execution_time", 0);
        ini_set("memory_limit", "-1");

        $this->load->model('Beneficio_Model');
        $this->load->model('Usuario_Sistema_Model');
        $this->load->model('Util_Model');
        $beneficios = $this->Beneficio_Model->get_all_beneficio();

        $data['beneficios'] = $beneficios;
        $data['beneficio_model'] = $this->Beneficio_Model;
        $data['usuario_sistema_model'] = $this->Usuario_Sistema_Model;
        $data['util_model'] = $this->Util_Model;

        $this->load->view('gppi/beneficios', $data);
    }

    public function excluir_beneficio() {
        ini_set("max_execution_time", 0);
        ini_set("memory_limit", "-1");

        $this->load->model('Beneficio_Model');

        $this->Beneficio_Model->delete_beneficio_by_id($this->input->get('id_beneficio', TRUE));
        redirect('Gppi/beneficios');
    }

    public function simulacao() {
        ini_set("max_execution_time", 0);
        ini_set("memory_limit", "-1");

        $this->load->model('Beneficio_Model');
        $this->load->model('GPPI_Model');
        $this->load->model('Criterio_Model');
        $this->load->model('Util_Model');

        if ($this->input->post() != false) {
            $post_data = $this->input->post();

            // Salvando dados do Benefício
            $options_beneficio = array(
                'id_publico_alvo' => intval($post_data['publicoAlvo']),
                'id_orgao_gestor' => intval($post_data['orgaoGestor']),
                'id_tipo_beneficio' => intval($post_data['tipoBeneficio']),
                'id_usuario_responsavel' => intval($this->session->userdata("sessao")['id_usuario_sistema']),
                'descricao' => $post_data['beneficio'],
                'valor_mensal_investido' => str_replace(',', '.', str_replace('.', '', $post_data['valorMensal'])),
                'quantidade_beneficiarios' => intval($post_data['quantidadeBeneficiarios'])
            );
            $id_insert = $this->Beneficio_Model->insert_beneficio($options_beneficio);

            // Salvando dados do Critério
            //chk1 - bairro
            if (array_key_exists('chk1', $post_data)) {
                $options = array(
                    'id_criterio' => 1,
                    'tipo_juncao' => $post_data['selectBairro'],
                    'bairro' => $post_data['bairro'],
                    'id_beneficio' => $id_insert
                );

                $this->Beneficio_Model->insert_criterio_beneficio($options);
            }
            if (array_key_exists('chk2', $post_data)) {
                //chk2 - cep
                $options = array(
                    'id_criterio' => 2,
                    'tipo_juncao' => $post_data['selectCep'],
                    'cep' => $post_data['cep'],
                    'id_beneficio' => $id_insert
                );

                $this->Beneficio_Model->insert_criterio_beneficio($options);
            }
            if (array_key_exists('chk3', $post_data)) {
                //chk3 - Quantidade de Crianças
                $options = array(
                    'id_criterio' => 4,
                    'tipo_juncao' => $post_data['selectCriancas'],
                    'quantidade' => $post_data['quantidadeCriancas'],
                    'id_beneficio' => $id_insert
                );

                $this->Beneficio_Model->insert_criterio_beneficio($options);
            }
            if (array_key_exists('chk4', $post_data)) {
                //chk4 - Quantidade de idosos
                $options = array(
                    'id_criterio' => 5,
                    'tipo_juncao' => $post_data['selectIdosos'],
                    'quantidade' => $post_data['quantidadeIdosos'],
                    'id_beneficio' => $id_insert
                );

                $this->Beneficio_Model->insert_criterio_beneficio($options);
            }
            if (array_key_exists('chk5', $post_data)) {
                //chk5 - Renda Familiar
                $options = array(
                    'id_criterio' => 6,
                    'tipo_filtro' => $post_data['selectRendaFamiliar'],
                    'tipo_juncao' => $post_data['selectCondicionalRendaFamiliar'],
                    'valor_filtro' => str_replace(',', '.', str_replace('.', '', $post_data['rendaFamiliar'])),
                    'id_beneficio' => $id_insert
                );

                $this->Beneficio_Model->insert_criterio_beneficio($options);
            }
            if (array_key_exists('chk6', $post_data)) {
                //chk6 - Renda Pessoal
                $options = array(
                    'id_criterio' => 7,
                    'tipo_filtro' => $post_data['selectRendaPessoal'],
                    'tipo_juncao' => $post_data['selectCondicionalRendaPessoal'],
                    'valor_filtro' => str_replace(',', '.', str_replace('.', '', $post_data['rendaPessoal'])),
                    'id_beneficio' => $id_insert
                );

                $this->Beneficio_Model->insert_criterio_beneficio($options);
            }
            if (array_key_exists('chk7', $post_data)) {
                //chk7 - Faixa Etária
                $options = array(
                    'id_criterio' => 8,
                    'tipo_juncao' => $post_data['selectFaixaEtaria'],
                    'idade_inicial' => $post_data['faixaEtariaInicial'],
                    'idade_final' => $post_data['faixaEtariaFinal'],
                    'id_beneficio' => $id_insert
                );


                $this->Beneficio_Model->insert_criterio_beneficio($options);
            }
            if (array_key_exists('chk8', $post_data)) {
                //chk8 - Idade
                $options = array(
                    'id_criterio' => 9,
                    'tipo_juncao' => $post_data['selectIdade'],
                    'idade_inicial' => $post_data['idade'],
                    'id_beneficio' => $id_insert
                );


                $this->Beneficio_Model->insert_criterio_beneficio($options);
            }
            if (array_key_exists('chk9', $post_data)) {
                //chk9 - Sexo
                $options = array(
                    'id_criterio' => 10,
                    'tipo_juncao' => $post_data['selectSexo'],
                    'id_sexo' => intval($post_data['sexo']),
                    'id_beneficio' => $id_insert
                );


                $this->Beneficio_Model->insert_criterio_beneficio($options);
            }
            if (array_key_exists('chk10', $post_data)) {
                //chk10 - Idade
                $options = array(
                    'id_criterio' => 11,
                    'tipo_juncao' => $post_data['selectCorRaca'],
                    'id_raca' => intval($post_data['corRaca']),
                    'id_beneficio' => $id_insert
                );

                $this->Beneficio_Model->insert_criterio_beneficio($options);
            }

            //PROCESSANDO OS PARAMETROS
            //ADICIONAR ADIÇÃO NO BANCO DE DADOS
            $parametros_array = $post_data['textHidden'];
            $temp_array = explode(';', $parametros_array);
            $parametros_array = array();
            foreach ($temp_array as $param) {
                $temp = explode(':', $param);
                if (count($temp) == 1) {
                    $inner_array = array(
                        'valor' => $temp[0]
                    );
                } else {
                    $inner_array = array(
                        'valor' => $temp[0],
                        'produto' => $temp[1],
                        'quantidade' => $temp[2]
                    );
                }

                array_push($parametros_array, $inner_array);
            }

//             Salvando dados do Benefício
            $options_parametro = array();
            foreach ($parametros_array as $parametro) {
                $temp_array = array(
                    'valor_unitario' => str_replace(',', '.', str_replace('.', '', $parametro['valor'])),
                    'nome_produto' => $parametro['produto'],
                    'quantidade' => intval($parametro['quantidade']),
                    'id_beneficio' => $id_insert
                );

                array_push($options_parametro, $temp_array);
            }

            $this->Beneficio_Model->insert_parametro_beneficio_batch($options_parametro);

            redirect('Gppi/beneficios');
            //$this->encaminha(base_url("index.php/Gppi/executa_simulacao?id_beneficio={$id_insert}"));
        }

        $data['sexo'] = $this->Beneficio_Model->get_all_selecao_sexo();
        $data['cor'] = $this->Beneficio_Model->get_all_selecao_cor_raca();
        $data['orgaos'] = $this->Beneficio_Model->get_all_orgao_gestor();
        $data['tipos_beneficio'] = $this->Beneficio_Model->get_all_tipo_beneficio();
        $data['publicos_alvo'] = $this->Beneficio_Model->get_all_publico_alvo();

        $this->load->view('gppi/simulacao', $data);
    }

    public function executa_simulacao() {
        ini_set("max_execution_time", 0);
        ini_set("memory_limit", "-1");

        $this->load->model('Beneficio_Model');
        $this->load->model('GPPI_Model');
        $this->load->model('Criterio_Model');
        $this->load->model('Usuario_Sistema_Model');
        $this->load->model('Util_Model');
        $this->load->model('Cadastro_Unico_Model');

        if ($this->input->get('id_beneficio', TRUE) != FALSE) {
            $id_beneficio = $this->input->get('id_beneficio', TRUE);
            $beneficio = $this->Beneficio_Model->get_beneficio_by_id($id_beneficio);
            $criterios = $this->Criterio_Model->get_criterio_by_beneficio_id($id_beneficio);
            $parametros = $this->Beneficio_Model->get_all_parametro_beneficio_by_id_beneficio($id_beneficio);

            //Iniciando o processamento - calculando o total de familias e pessoas através dos critérios
            $array_resultado = array();
            foreach ($criterios as $criterio) {
                $array_resultado_temp = array(
                    'familias' => array(),
                    'pessoas' => array()
                );
                switch ($criterio->id_criterio) {
                    case 1:
                        $array_resultado_temp = $this->GPPI_Model->get_beneficiarios_por_bairro(1, 1, $criterio->bairro);
                        break;
                    case 2:
                        $array_resultado_temp = $this->GPPI_Model->get_beneficiarios_por_cep(1, 1, $criterio->cep);
                        break;
                    case 3:
                        break;
                    case 4:
                        $array_resultado_temp = $this->GPPI_Model->get_beneficiarios_por_crianca();
                        break;
                    case 5:
                        $array_resultado_temp = $this->GPPI_Model->get_beneficiarios_por_idoso();
                        break;
                    case 6:
                        $array_resultado_temp = $this->GPPI_Model->get_beneficiarios_por_renda_familia(floatval($criterio->valor_filtro), $criterio->tipo_filtro);
                        break;
                    case 7:
                        $array_resultado_temp = $this->GPPI_Model->get_beneficiarios_por_renda_pessoa(floatval($criterio->valor_filtro), $criterio->tipo_filtro);
                        break;
                    case 8:
                        $array_resultado_temp = $this->GPPI_Model->get_beneficiarios_por_faixa_etaria_pessoa(intval($criterio->idade_inicial), intval($criterio->idade_final));
                        break;
                    case 9:
                        $array_resultado_temp = $this->GPPI_Model->get_beneficiarios_por_idade_pessoa(intval($criterio->idade_inicial), intval($criterio->tipo_filtro));
                        break;
                    case 10:
                        $array_resultado_temp = $this->GPPI_Model->get_beneficiarios_por_sexo(intval($criterio->id_sexo));
                        break;
                    case 11:
                        $array_resultado_temp = $this->GPPI_Model->get_beneficiarios_por_raca(intval($criterio->id_raca));
                        break;
                }

                if ($array_resultado_temp != NULL && count($array_resultado_temp) > 0) {
                    if (count($array_resultado) == 0) {
                        $array_resultado = $array_resultado_temp;
                    } else {
                        if ($criterio->tipo_juncao == 'E') {
                            //Tratar depois
                        } else if ($criterio->tipo_juncao == 'OU') {
                            //Tratar depois
                        }
                    }
                }
            }

            //Filtrando pelo orçamento
            $contagem_total_atendem_filtros = $this->GPPI_Model->count_familias_pessoas_return_object($array_resultado);
            $valor_custo_total = floatval(0);
            $valor_custo_total_aplicado_filtro = floatval(0);
            foreach ($parametros as $parametro) {
                $valor_total_parametro = floatval(floatval($parametro->valor_unitario) * $parametro->quantidade);
                $valor_custo_total = floatval($valor_custo_total + $valor_total_parametro);
                if ($beneficio->id_publico_alvo == 1) {
                    $valor_total_parametro = floatval($valor_total_parametro * $contagem_total_atendem_filtros['total_familias']);
                } else if ($beneficio->id_publico_alvo == 2) {
                    $valor_total_parametro = floatval($valor_total_parametro * $contagem_total_atendem_filtros['total_pessoas']);
                }
                $valor_custo_total_aplicado_filtro = floatval($valor_custo_total_aplicado_filtro + $valor_total_parametro);
            }

            //Totais com criterio sem o limitador
            $data['array_resultado_sem_limite'] = $array_resultado;
            $data['total_com_criterio_sem_limitador'] = $valor_custo_total_aplicado_filtro;
            $data['total_familias_com_criterio_sem_limitador'] = $contagem_total_atendem_filtros['total_familias'];
            $data['total_pessoas_com_criterio_sem_limitador'] = $contagem_total_atendem_filtros['total_pessoas'];

            //Aplicando o limitador
            if (($contagem_total_atendem_filtros['total_pessoas'] > $beneficio->quantidade_beneficiarios) && ($valor_custo_total_aplicado_filtro > floatval($beneficio->valor_mensal_investido))) {
                //Limitado pelos dois itens
            } else if ($contagem_total_atendem_filtros['total_pessoas'] > $beneficio->quantidade_beneficiarios) {
                $data['array_resultado_com_limite'] = $this->GPPI_Model->calcula_total_familias_limitado_por_pessoa($beneficio->quantidade_beneficiarios, $array_resultado, true);
                //Limitado pelo número de beneficiarios
                $valor_total_limitado_pela_quantidade_beneficiados = floatval($beneficio->quantidade_beneficiarios * $valor_custo_total);
                $data['total_com_criterio_com_limitador'] = $valor_total_limitado_pela_quantidade_beneficiados;
                $data['total_pessoas_com_criterio_com_limitador'] = $beneficio->quantidade_beneficiarios;
                $data['total_familias_com_criterio_com_limitador'] = $this->GPPI_Model->calcula_total_familias_limitado_por_pessoa($beneficio->quantidade_beneficiarios, $array_resultado);
            } else if ($valor_custo_total_aplicado_filtro > floatval($beneficio->valor_mensal_investido)) {
                $data['array_resultado_com_limite'] = $array_resultado;
                //Limitado pelo valor financeiro - calculando o multiplicador da entidade
                $multiplicador = intval($valor_custo_total_aplicado_filtro / $beneficio->valor_mensal_investido);
                $dados['total_pessoas_com_criterio_com_limitador'] = $this->GPPI_Model->calcula_total_familias_limitado_por_pessoa($multiplicador, $array_resultado);
                $dados['total_familias_com_criterio_com_limitador'] = $this->GPPI_Model->calcula_total_familias_limitado_por_pessoa($multiplicador, $array_resultado);
                $data['total_com_criterio_com_limitador'] = floatval($multiplicador * $valor_custo_total);
            } else {
                $data['array_resultado_com_limite'] = $array_resultado;
                $data['total_com_criterio_com_limitador'] = $valor_custo_total_aplicado_filtro;
                $data['total_pessoas_com_criterio_com_limitador'] = $contagem_total_atendem_filtros['total_pessoas'];
                $data['total_familias_com_criterio_com_limitador'] = $contagem_total_atendem_filtros['total_familias'];
            }

            $data['cadastro_unico_model'] = $this->Cadastro_Unico_Model;
            $data['redirect_resultado'] = TRUE;
            $data['criterios'] = $criterios;
            $data['parametros'] = $parametros;
            $data['beneficio_model'] = $this->Beneficio_Model;
            $data['usuario_sistema_model'] = $this->Usuario_Sistema_Model;
            $data['util_model'] = $this->Util_Model;
            $data['beneficio'] = $beneficio;
            $this->load->view('gppi/resultado_simulacao', $data);
        } else {
            redirect('simulacao');
        }
    }

    public function teste() {
        ini_set("max_execution_time", 0);
        ini_set("memory_limit", "-1");

        $this->load->model('GPPI_Model');
        echo "------ Idosos ------";
        var_dump($this->GPPI_Model->get_beneficiarios_por_idoso());
        var_dump($this->GPPI_Model->count_familias_pessoas_return_object($this->GPPI_Model->get_beneficiarios_por_idoso()));
        echo "<br><br>";
        echo "------ Crianças ------";
        var_dump($this->GPPI_Model->get_beneficiarios_por_crianca());
        var_dump($this->GPPI_Model->count_familias_pessoas_return_object($this->GPPI_Model->get_beneficiarios_por_crianca()));
        echo "<br><br>";
        echo "------ Faixa Etária ------";
        var_dump($this->GPPI_Model->get_beneficiarios_por_faixa_etaria_pessoa(8, 50));
        var_dump($this->GPPI_Model->count_familias_pessoas_return_object($this->GPPI_Model->get_beneficiarios_por_faixa_etaria_pessoa(8, 50)));
        echo "<br><br>";
        echo "------ Idade Pessoa ------";
        var_dump($this->GPPI_Model->get_beneficiarios_por_idade_pessoa(27, '>='));
        var_dump($this->GPPI_Model->count_familias_pessoas_return_object($this->GPPI_Model->get_beneficiarios_por_idade_pessoa(27, '>=')));
        echo "<br><br>";
        echo "------ Raça ou Cor ------";
        var_dump($this->GPPI_Model->get_beneficiarios_por_raca(2));
        var_dump($this->GPPI_Model->count_familias_pessoas_return_object($this->GPPI_Model->get_beneficiarios_por_raca(2)));
        echo "<br><br>";
        echo "------ Sexo ------";
        var_dump($this->GPPI_Model->get_beneficiarios_por_sexo(1));
        var_dump($this->GPPI_Model->count_familias_pessoas_return_object($this->GPPI_Model->get_beneficiarios_por_sexo(1)));
        echo "<br><br>";
        echo "------ Renda Familia ------";
        var_dump($this->GPPI_Model->get_beneficiarios_por_renda_familia(2400, '>='));
        var_dump($this->GPPI_Model->count_familias_pessoas_return_object($this->GPPI_Model->get_beneficiarios_por_renda_familia(2400.00, '>=')));
        echo "<br><br>";
        echo "------ Renda Pessoa ------";
        var_dump($this->GPPI_Model->get_beneficiarios_por_renda_pessoa(1500, '>='));
        var_dump($this->GPPI_Model->count_familias_pessoas_return_object($this->GPPI_Model->get_beneficiarios_por_renda_pessoa(1500.00, '>=')));
        echo "<br><br>";
        echo "------ Cep ------";
        var_dump($this->GPPI_Model->get_beneficiarios_por_cep(1, 1, '80010130'));
        var_dump($this->GPPI_Model->count_familias_pessoas_return_object($this->GPPI_Model->get_beneficiarios_por_cep(1, 1, '80010130')));
        echo "<br><br>";
        echo "------ Bairro ------";
        var_dump($this->GPPI_Model->get_beneficiarios_por_bairro(1, 1, 'Bigorrilho'));
        var_dump($this->GPPI_Model->count_familias_pessoas_return_object($this->GPPI_Model->get_beneficiarios_por_bairro(1, 1, 'Bigorrilho')));
        echo "<br><br>";
    }

    function encaminha($url) {
        echo "<script type='text/javascript'>window.location='" . $url . "';</script>";
        exit();
    }

}
