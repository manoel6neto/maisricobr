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

        $this->load->model('Gppi_Model');
        $this->load->model('Util_Model');

        $data['gppi_model'] = $this->Gppi_Model;
        $data['util_model'] = $this->Util_Model;

        $this->load->view('gppi/processos', $data);
    }

    public function novo_projeto() {
        ini_set("max_execution_time", 0);
        ini_set("memory_limit", "-1");

        $this->load->model('Gppi_Model');
        $this->load->model('Util_Model');

        if ($this->input->post() != false) {
            redirect('Gppi/politicas_governo');
        }

        $data['gppi_model'] = $this->Gppi_Model;
        $data['util_model'] = $this->Util_Model;

        $this->load->view('gppi/novo_projeto', $data);
    }

    public function politicas_governo() {
        ini_set("max_execution_time", 0);
        ini_set("memory_limit", "-1");

        $this->load->model('Gppi_Model');
        $this->load->model('Util_Model');

        if ($this->input->post() != false) {
            redirect('Gppi/politicas_governo');
        }

        $data['gppi_model'] = $this->Gppi_Model;
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

    public function simulacao() {
        ini_set("max_execution_time", 0);
        ini_set("memory_limit", "-1");

        $this->load->model('Beneficio_Model');
        $this->load->model('GPPI_Model');
        $this->load->model('Criterio_Model');
        $this->load->model('Util_Model');

        if ($this->input->post() != false) {
            $post_data = $this->input->post();
        }

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

}
