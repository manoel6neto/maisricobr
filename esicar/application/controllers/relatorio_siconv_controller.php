<?php

include 'application/controllers/BaseController.php';

class relatorio_siconv_controller extends BaseController {

    public function __construct() {
        parent::__construct();
    }

    /*
     * Formulário para gerar o relatório do siconv
     */

    public function planilha() {
        $this->session->set_userdata('pagAtual', '');
        $this->load->model('relatorios_estatisticos_model');
        $this->load->model('proponente_siconv_model');
        $regioes = $this->verifica_regiao();
        $esferas = $this->get_esferas('TODOS');
        $data['esferas'] = $esferas;
        $data['regioes'] = $regioes;
        $data['proponente_siconv_model'] = $this->proponente_siconv_model;
        if ($this->session->userdata('gp') == true || $this->input->get('gp', TRUE) != false) {
            $data['title'] = 'G&P - Planilha de aproveitamento de oportunidades SICONV';
        } else {
            $data['title'] = 'Physis - Planilha de aproveitamento de oportunidades SICONV';
        }
        $data['title_header'] = 'Planilha de aproveitamento de oportunidades SICONV';
        $data['main'] = "relatorios_estatisticos/relatorio_siconv";
        $data['action'] = "index.php/relatorio_siconv_controller/gera_planilha";
        $this->load->view('relatorios_estatisticos/temp_relatorios_estatisticos', $data);
    }

    public function relatorio() {
        $this->session->set_userdata('pagAtual', '');

        $this->load->model('relatorios_estatisticos_model');
        $this->load->model('proponente_siconv_model');
        $regioes = $this->verifica_regiao();
        $data['regioes'] = $regioes;
        $esferas = $this->get_esferas('TODOS');
        $data['esferas'] = $esferas;
        $data['proponente_siconv_model'] = $this->proponente_siconv_model;
        if ($this->session->userdata('gp') == true || $this->input->get('gp', TRUE) != false) {
            $data['title'] = 'G&P - Relatório Siconv';
        } else {
            $data['title'] = 'Physis - Relatório Siconv';
        }
        $data['title_header'] = 'Relatório Siconv';
        $data['main'] = "relatorios_estatisticos/relatorio_siconv";
        $data['action'] = "index.php/relatorio_siconv_controller/gera_relatorio";
        $this->load->view('relatorios_estatisticos/temp_relatorios_estatisticos', $data);
    }

    /*
     * Gera relatório Siconv
     */

    public function gera_planilha() {
        $this->load->model('relatorios_siconv_model');
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '-1');
        $this->load->library('mpdf60/mpdf.php');

        //Carrega models
        $this->load->model('cidade_tag');
        $this->load->model('relatorios_siconv_model');

        //Pega dados do formulário
        $regiao = $this->input->get_post('regiao');
        $estado = $this->input->get_post('estado');
        $cod_cidade = $this->input->get_post('municipio');
        $proponente = $this->input->get_post('proponente');

        //Flag de controle para label de osc
        $somente_osc = false;
        $esferas = $this->input->get_post('esfera');
        if (count($esferas) == 1 && in_array('PRIVADA', $esferas)) {
            $somente_osc = true;
        }

        $cidades = null;
        $habitantes = 0;

        if ($cod_cidade == NULL) {
            $cidades = $this->input->get_post('cidades');
            $cidades = explode(',', $cidades);
            $nivel_e_fator_de_cadastro = array('fator' => 3, 'programas_ofertados' => 0, 'envio_proposta' => 0, 'programas_governo_municipal' => 0, 'programas_organizacoes_sociais' => 0, 'programas_empresas_mistas' => 0, 'programas_consorcios_publicos' => 0, 'propostas_governo_municipal' => 0, 'propostas_organizacoes_sociais' => 0, 'propostas_empresas_mistas' => 0, 'propostas_consorcios_publicos' => 0, 'emendas_parl_espec' => 0);
            foreach ($cidades as $value) {
                $cidades_tag = $this->cidade_tag->get_cidade_tag_by_cod($value);
                if (count($esferas) == 1 && in_array('ESTADUAL', $esferas)) {
                    $habitantes += $cidades_tag->populacao;
                } else {
                    $niveis_cidades = $this->relatorios_siconv_model->verifica_nivel(strtoupper($cidades_tag->cidade), $cidades_tag->populacao);
                    $nivel_e_fator_de_cadastro['programas_ofertados'] += $niveis_cidades['programas_ofertados'];
                    $nivel_e_fator_de_cadastro['envio_proposta'] += $niveis_cidades['envio_proposta'];
                    $nivel_e_fator_de_cadastro['programas_governo_municipal'] += $niveis_cidades['programas_governo_municipal'];
                    $nivel_e_fator_de_cadastro['programas_governo_estadual'] += $niveis_cidades['programas_governo_estadual'];
                    $nivel_e_fator_de_cadastro['programas_organizacoes_sociais'] += $niveis_cidades['programas_organizacoes_sociais'];
                    $nivel_e_fator_de_cadastro['programas_empresas_mistas'] += $niveis_cidades['programas_empresas_mistas'];
                    $nivel_e_fator_de_cadastro['programas_consorcios_publicos'] += $niveis_cidades['programas_consorcios_publicos'];
                    $nivel_e_fator_de_cadastro['propostas_governo_municipal'] += $niveis_cidades['propostas_governo_municipal'];
                    $nivel_e_fator_de_cadastro['propostas_governo_estadual'] += $niveis_cidades['propostas_governo_estadual'];
                    $nivel_e_fator_de_cadastro['propostas_organizacoes_sociais'] += $niveis_cidades['propostas_organizacoes_sociais'];
                    $nivel_e_fator_de_cadastro['propostas_empresas_mistas'] += $niveis_cidades['propostas_empresas_mistas'];
                    $nivel_e_fator_de_cadastro['propostas_consorcios_publicos'] += $niveis_cidades['propostas_consorcios_publicos'];
                    $nivel_e_fator_de_cadastro['emendas_parl_espec'] += $niveis_cidades['emendas_parl_espec'];
                    $habitantes += $cidades_tag->populacao;
                }
            }
            unset($cidades_tag);

            if (count($esferas) == 1 && in_array('ESTADUAL', $esferas)) {
                $niveis_cidades = $this->relatorios_siconv_model->verifica_nivel(null, null, true);
                $nivel_e_fator_de_cadastro['programas_ofertados'] += $niveis_cidades['programas_ofertados'];
                $nivel_e_fator_de_cadastro['envio_proposta'] += $niveis_cidades['envio_proposta'];
                $nivel_e_fator_de_cadastro['programas_governo_municipal'] += $niveis_cidades['programas_governo_municipal'];
                $nivel_e_fator_de_cadastro['programas_governo_estadual'] += $niveis_cidades['programas_governo_estadual'];
                $nivel_e_fator_de_cadastro['programas_organizacoes_sociais'] += $niveis_cidades['programas_organizacoes_sociais'];
                $nivel_e_fator_de_cadastro['programas_empresas_mistas'] += $niveis_cidades['programas_empresas_mistas'];
                $nivel_e_fator_de_cadastro['programas_consorcios_publicos'] += $niveis_cidades['programas_consorcios_publicos'];
                $nivel_e_fator_de_cadastro['propostas_governo_municipal'] += $niveis_cidades['propostas_governo_municipal'];
                $nivel_e_fator_de_cadastro['propostas_governo_estadual'] += $niveis_cidades['propostas_governo_estadual'];
                $nivel_e_fator_de_cadastro['propostas_organizacoes_sociais'] += $niveis_cidades['propostas_organizacoes_sociais'];
                $nivel_e_fator_de_cadastro['propostas_empresas_mistas'] += $niveis_cidades['propostas_empresas_mistas'];
                $nivel_e_fator_de_cadastro['propostas_consorcios_publicos'] += $niveis_cidades['propostas_consorcios_publicos'];
                $nivel_e_fator_de_cadastro['emendas_parl_espec'] += $niveis_cidades['emendas_parl_espec'];
            }
        } else {
            //Pega informações da cidade
            $cidades_tag = $this->cidade_tag->get_cidade_tag_by_cod($cod_cidade);

            //verifica o nível, fator e programas de cadastramento da cidade
            $nivel_e_fator_de_cadastro = $this->relatorios_siconv_model->verifica_nivel(strtoupper($cidades_tag->cidade), $cidades_tag->populacao);
        }

        $dados = $this->relatorios_siconv_model->get_dados_siconv($proponente, $regiao, $estado, $cidades_tag, $nivel_e_fator_de_cadastro, NULL, NULL, $somente_osc, $habitantes, $cidades);

        //Gera PDF
        ob_start(); // inicia o buffer

        $mpdf = new mPDF ();
        $mpdf->allow_charset_conversion = true;
        $mpdf->charset_in = 'UTF-8';
        $mpdf->SetDefaultFontSize(9);
        $mpdf->SetMargins(5, 5, 5);
        $mpdf->margin_bottom_collapse = true;

        $header = array(
            'L' => array(
                'content' => 'PHYSIS BRASIL',
                'font-size' => 8
            ),
            'C' => array(
                'content' => strtoupper($this->session->userdata('entidade')),
                'font-size' => 10
            ),
            'R' => array(
                'content' => 'e-Sicar',
                'font-size' => 8
            ),
            'line' => 1
        );

        $mpdf->SetHeader($header, 'O');
        $mpdf->SetFooter('{DATE d/m/Y}||{PAGENO}/{nb}');
        $tabela = $this->load->view('relatorios_estatisticos/pdf_planilha_siconv', $dados, TRUE);
        $mpdf->WriteHTML($tabela);
//        
        $mpdf->Output();

        exit(0);
    }

    public function gera_relatorio() {
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '-1');
        $this->load->library('mpdf60/mpdf.php');

        //Carrega models
        $this->load->model('cidade_tag');
        $this->load->model('relatorio_ganho_perca_model', 'modelrel');
        $this->load->model('relatorios_siconv_model');

        //Pega dados do formulário
        $regiao = $this->input->get_post('regiao');
        $estado = $this->input->get_post('estado');
        $cod_cidade = $this->input->get_post('municipio');
        $proponente = $this->input->get_post('proponente');
        if ($cod_cidade == NULL) {
            $cidades = $this->input->get_post('cidades');
            $cidades = explode(',', $cidades);
            $nivel_e_fator_de_cadastro = array('fator' => 3, 'programas_ofertados' => 0, 'envio_proposta' => 0, 'programas_governo_municipal' => 0, 'programas_organizacoes_sociais' => 0, 'programas_empresas_mistas' => 0, 'programas_consorcios_publicos' => 0, 'propostas_governo_municipal' => 0, 'propostas_organizacoes_sociais' => 0, 'propostas_empresas_mistas' => 0, 'propostas_consorcios_publicos' => 0, 'emendas_parl_espec' => 0);
            $habitantes = 0;
            foreach ($cidades as $value) {
                $cidades_tag = $this->cidade_tag->get_cidade_tag_by_cod($value);
                $niveis_cidades = $this->relatorios_siconv_model->verifica_nivel(strtoupper($cidades_tag->cidade), $cidades_tag->populacao);
                $nivel_e_fator_de_cadastro['programas_ofertados'] += $niveis_cidades['programas_ofertados'];
                $nivel_e_fator_de_cadastro['envio_proposta'] += $niveis_cidades['envio_proposta'];
                $nivel_e_fator_de_cadastro['programas_governo_municipal'] += $niveis_cidades['programas_governo_municipal'];
                $nivel_e_fator_de_cadastro['programas_organizacoes_sociais'] += $niveis_cidades['programas_organizacoes_sociais'];
                $nivel_e_fator_de_cadastro['programas_empresas_mistas'] += $niveis_cidades['programas_empresas_mistas'];
                $nivel_e_fator_de_cadastro['programas_consorcios_publicos'] += $niveis_cidades['programas_consorcios_publicos'];
                $nivel_e_fator_de_cadastro['propostas_governo_municipal'] += $niveis_cidades['propostas_governo_municipal'];
                $nivel_e_fator_de_cadastro['propostas_organizacoes_sociais'] += $niveis_cidades['propostas_organizacoes_sociais'];
                $nivel_e_fator_de_cadastro['propostas_empresas_mistas'] += $niveis_cidades['propostas_empresas_mistas'];
                $nivel_e_fator_de_cadastro['propostas_consorcios_publicos'] += $niveis_cidades['propostas_consorcios_publicos'];
                $nivel_e_fator_de_cadastro['emendas_parl_espec'] += $niveis_cidades['emendas_parl_espec'];
                $habitantes += $cidades_tag->populacao;
            }
            unset($cidades_tag);
        } else {
            //Pega informações da cidade
            $cidades_tag = $this->cidade_tag->get_cidade_tag_by_cod($cod_cidade);

            //verifica o nível, fator e programas de cadastramento da cidade
            $nivel_e_fator_de_cadastro = $this->relatorios_siconv_model->verifica_nivel(strtoupper($cidades_tag->cidade), $cidades_tag->populacao);
        }

        $dados = $this->relatorios_siconv_model->get_dados_siconv($proponente, $regiao, $estado, $cidades_tag, $nivel_e_fator_de_cadastro, NULL, NULL);

        //Gera PDF
        ob_start(); // inicia o buffer

        $mpdf = new mPDF ();
        $mpdf->allow_charset_conversion = true;
        $mpdf->charset_in = 'UTF-8';
        $mpdf->SetDefaultFontSize(10);
        $mpdf->SetMargins(5, 5, 5);
        $mpdf->margin_bottom_collapse = true;

        $header = array(
            'L' => array(
                'content' => 'PHYSIS BRASIL',
                'font-size' => 8
            ),
            'C' => array(
                'content' => strtoupper($this->session->userdata('entidade')),
                'font-size' => 12
            ),
            'R' => array(
                'content' => 'e-Sicar',
                'font-size' => 8
            ),
            'line' => 1
        );

        $mpdf->SetHeader($header, 'O');
        $mpdf->SetFooter('{DATE d/m/Y}||{PAGENO}/{nb}');
        $relatorio = $this->load->view('relatorios_estatisticos/pdf_relatorio_siconv', $dados, TRUE);
        $mpdf->WriteHTML($relatorio);
        $proposta = $this->load->view('relatorios_estatisticos/pdf_proposta_siconv', $dados_proposta, TRUE);
        $mpdf->WriteHTML($proposta);
        $mpdf->Output();

        exit(0);
    }

    public function busca_estados() {

        $sigla_regiao = $this->input->post('regiao', TRUE);
        $select_estados = $this->input->post('select_estados', TRUE);
        $estados = $this->get_all_estados_regiao($sigla_regiao);

        $options = "<option value=''>Todos</option>";
        if (!empty($estados)) {
            foreach ($estados as $estado) {
                $options .= "<option value='{$estado['sigla']}'";
                $options .= $select_estados == $estado['sigla'] ? 'selected' : '';
                $options .= isset($estado['disabled']) ? 'disabled' : '';
                $options .= ">" . $estado['nome'] . "</option>";
            }
        }

        echo $options;
    }

    public function get_all_estados_regiao($sigla_regiao) {
        $this->load->model('estados');
        $estados = $this->estados->get_lista_estados_permitidos_relatorio();

        if ($sigla_regiao == 'NE') {
            $estados_nordeste = $this->estados->get_estados_by_regiao(2);
            foreach ($estados_nordeste as $key => $value) {
                if (in_array($value['sigla'], $estados)) {
                    unset($estados_nordeste[$key]['disabled']);
                    $estados_nordeste[$key]['verificado'] = true;
                } else {
                    $estados_nordeste[$key]['disabled'] = 'disabled';
                }
            }
            return $estados_nordeste;
        } elseif ($sigla_regiao == 'N') {
            $estados_norte = $this->estados->get_estados_by_regiao(1);
            foreach ($estados_norte as $key => $value) {
                if (in_array($value['sigla'], $estados)) {
                    unset($estados_norte[$key]['disabled']);
                    $estados_norte[$key]['verificado'] = true;
                } else {
                    $estados_norte[$key]['disabled'] = 'disabled';
                }
            }
            return $estados_norte;
        } elseif ($sigla_regiao == 'CO') {
            $estados_centrooeste = $this->estados->get_estados_by_regiao(3);
            foreach ($estados_centrooeste as $key => $value) {
                if (in_array($value['sigla'], $estados)) {
                    unset($estados_centrooeste[$key]['disabled']);
                    $estados_centrooeste[$key]['verificado'] = true;
                } else {
                    $estados_centrooeste[$key]['disabled'] = 'disabled';
                }
            }
            return $estados_centrooeste;
        } elseif ($sigla_regiao == 'SE') {
            $estados_sudeste = $this->estados->get_estados_by_regiao(4);
            foreach ($estados_sudeste as $key => $value) {
                if (in_array($value['sigla'], $estados)) {
                    unset($estados_sudeste[$key]['disabled']);
                    $estados_sudeste[$key]['verificado'] = true;
                } else {
                    $estados_sudeste[$key]['disabled'] = 'disabled';
                }
            }
            return $estados_sudeste;
        } elseif ($sigla_regiao == 'S') {
            $estados_sul = $this->estados->get_estados_by_regiao(5);
            foreach ($estados_sul as $key => $value) {
                if (in_array($value['sigla'], $estados)) {
                    unset($estados_sul[$key]['disabled']);
                    $estados_sul[$key]['verificado'] = true;
                } else {
                    $estados_sul[$key]['disabled'] = 'disabled';
                }
            }
            return $estados_sul;
        } elseif ($sigla_regiao == 'TODOS') {
            $all_estados = $this->estados->get_estados_by_regiao(NULL);
            foreach ($all_estados as $key => $value) {
                if (in_array($value['sigla'], $estados)) {
                    unset($all_estados[$key]['disabled']);
                    $all_estados[$key]['verificado'] = true;
                } else {
                    $all_estados[$key]['disabled'] = 'disabled';
                }
            }
            return $all_estados;
        } else {
            return array();
        }
    }

    public function verifica_regiao() {
        $this->load->model('proponente_siconv_model');
        $regiões[0] = array('sigla' => 'TODOS', 'nome' => strtoupper('Todos'));
        $regiões[1] = array('sigla' => 'NE', 'nome' => strtoupper('Nordeste'), 'disabled' => 'disabled');
        $regiões[2] = array('sigla' => 'N', 'nome' => strtoupper('Norte'), 'disabled' => 'disabled');
        $regiões[3] = array('sigla' => 'CO', 'nome' => strtoupper('Centro Oeste'), 'disabled' => 'disabled');
        $regiões[4] = array('sigla' => 'SE', 'nome' => strtoupper('Sudeste'), 'disabled' => 'disabled');
        $regiões[5] = array('sigla' => 'S', 'nome' => strtoupper('Sul'), 'disabled' => 'disabled');
        $estados = $this->proponente_siconv_model->getListaEstados();
        if (isset($estados['BA']) || isset($estados['AL']) || isset($estados['CE']) || isset($estados['MA']) || isset($estados['PB']) || isset($estados['PE']) || isset($estados['PI']) || isset($estados['RN']) || isset($estados['SE'])) {
            unset($regiões[1]['disabled']);
        }

        if (isset($estados['AC']) || isset($estados['AP']) || isset($estados['AM']) || isset($estados['PA']) || isset($estados['RO']) || isset($estados['RR']) || isset($estados['TO'])) {
            unset($regiões[2]['disabled']);
        }

        if (isset($estados['GO']) || isset($estados['MT']) || isset($estados['MS']) || isset($estados['DF'])) {
            unset($regiões[3]['disabled']);
        }

        if (isset($estados['ES']) || isset($estados['MG']) || isset($estados['RJ']) || isset($estados['SP'])) {
            unset($regiões[4]['disabled']);
        }

        if (isset($estados['PR']) || isset($estados['RS']) || isset($estados['SC'])) {
            unset($regiões[5]['disabled']);
        }

        return $regiões;
    }

    public function get_lista_proponentes() {
        $cidades = null;
        $this->load->model('relatorios_siconv_model');
        $estados_permitidos = array();
        $regioes_permitidas = array();
        if ($this->input->post('municipio', TRUE) == "") {
            if ($this->input->post('uf', TRUE) == "") {
                if ($this->input->post('regiao', TRUE) == "TODOS") {
                    $regioes = $this->verifica_regiao();
                    unset($regioes[0]);
                    foreach ($regioes as $regiao) {
                        if (!isset($regiao['disabled'])) {
                            array_push($regioes_permitidas, $regiao['sigla']);
                        }
                    }
                    foreach ($regioes_permitidas as $regiao_permitida) {
                        $estados = $this->get_all_estados_regiao($regiao_permitida);
                        foreach ($estados as $estado) {
                            if (!isset($estado['disabled'])) {
                                array_push($estados_permitidos, $estado['sigla']);
                            }
                        }
                    }
                    foreach ($estados_permitidos as $estado_permitido) {
                        $cidades[] = $this->get_lista_cidades_limitado($estado_permitido);
                    }
                } else {
                    $estados = $this->get_all_estados_regiao($this->input->post('regiao', TRUE));
                    foreach ($estados as $estado) {
                        if (!isset($estado['disabled'])) {
                            array_push($estados_permitidos, $estado['sigla']);
                        }
                    }
                    foreach ($estados_permitidos as $estado_permitido) {
                        $cidades[] = $this->get_lista_cidades_limitado($estado_permitido);
                    }
                }
            } else {
                $cidades = $this->get_lista_cidades_limitado($this->input->post('uf', TRUE));
            }
        }
        $esferas = $this->input->post('esfera', TRUE);
        $listaCidades = $this->relatorios_siconv_model->get_proponentes($this->input->post('esfera', TRUE), $this->input->post('municipio', TRUE), $this->input->post('uf', TRUE), $this->input->post('regiao', TRUE), $this->input->post('tipo', TRUE), $this->input->post('id', TRUE), ($this->session->userdata('nivel') == 2 && $this->session->userdata('usuario_sistema') != "P"), $cidades);
        $selected_proponente = array();
        if ($this->input->post('selected_proponente', TRUE))
            $selected_proponente = $this->input->post('selected_proponente', TRUE);
        $option = array();
        foreach ($listaCidades as $cidade) {
            $option[] = array("label" => $cidade->cnpj . " - " . $cidade->nome, "value" => $cidade->cnpj, in_array($cidade->cnpj, $selected_proponente) ? "selected" : "" => "true");
        }
        $dados = array('cidades' => $cidades, 'option' => $option);
        echo json_encode($dados);
    }

    public function get_lista_cidades_limitado($uf) {
        $this->load->model('proponente_siconv_model');
        $this->load->model('municipios_direito_vendedor_model');

        $listaCidades = $this->proponente_siconv_model->get_municipio($uf);
        if ($this->session->userdata('nivel') == 1 || $this->session->userdata('nivel') == 2 || $this->session->userdata('nivel') == 3) {
            foreach ($listaCidades as $cidade) {
                $option[] = $cidade->codigo_municipio;
            }
        } else {
            $listaPermitidos = $this->municipios_direito_vendedor_model->get_lista_cidades_bloqueadas($this->session->userdata('id_usuario'));
            $array_permitidos = array();
            foreach ($listaPermitidos as $citys) {
                array_push($array_permitidos, $citys);
            }

            foreach ($listaCidades as $cidade) {
                if (in_array($cidade->municipio, $array_permitidos)) {
                    $option[] = $cidade->codigo_municipio;
                }
            }
        }

        return $option;
    }

    public function get_lista_cidades() {
        $this->load->model('proponente_siconv_model');
        $this->load->model('municipios_direito_vendedor_model');
        $this->load->model('municipios_direito_gestor_execucao_model');

        $listaCidades = $this->proponente_siconv_model->get_municipio($this->input->post('uf', TRUE));
        $selected_city = $this->input->post('selected_city', TRUE);
        if ($this->session->userdata('nivel') == 1 || $this->session->userdata('nivel') == 2 || $this->session->userdata('nivel') == 3 || $this->session->userdata('nivel') == 12 || $this->session->userdata('nivel') == 13) {
            $option = "<option value=''>TODOS</option>";
            foreach ($listaCidades as $cidade) {
                $option .= "<option value='" . $cidade->codigo_municipio . "' ";
                $option .= $selected_city == $cidade->codigo_municipio ? 'selected' : '';
                $option .= ">" . $cidade->municipio;
                $option .= "</option>";
            }
        } else {
            if ($this->session->userdata('nivel') == 14) {
                $cidades = $this->municipios_direito_gestor_execucao_model->get_lista_cidades_bloqueadas($this->session->userdata('id_usuario'));
            } else {
                $cidades = $this->municipios_direito_vendedor_model->get_lista_cidades_bloqueadas($this->session->userdata('id_usuario'));
            }
            foreach ($cidades as $key => $value) {
                $listaPermitidos[] = $key;
            }
            $option = "<option value=''>TODOS</option>";
            foreach ($listaCidades as $cidade) {
                if (in_array($cidade->codigo_municipio, $listaPermitidos)) {
                    $option .= "<option value='" . $cidade->codigo_municipio;
                    $option .= $selected_city == $cidade->codigo_municipio ? 'selected' : '';
                    $option .= "'>" . $cidade->municipio;
                    $option .= "</option>";
                }
            }
        }

        echo $option;
    }

    public function get_esferas() {
        $this->load->model('relatorios_siconv_model');
        $this->load->model('municipios_direito_vendedor_model');
        $regiao = $this->input->post('regiao', TRUE);
        $estado = $this->input->post('estado', TRUE);
        $municipio = $this->input->post('municipio', TRUE);

        if ($this->session->userdata('nivel') == 4 || $this->session->userdata('nivel') == 15) {
            if ($this->input->post() == FALSE)
                $regiao = 'TODOS';
            if ($estado == FALSE) {
                $this->load->model('estados');
                $estados_permitidos = $this->get_all_estados_regiao($regiao);
                foreach ($estados_permitidos as $value) {
                    if (!isset($value['disabled'])) {
                        $estado[] = $value['sigla'];
                    }
                }
            }

            if ($municipio == FALSE) {
                $municipio_permitidos = $this->municipios_direito_vendedor_model->get_lista_cidades_bloqueadas($this->session->userdata('id_usuario'));
                foreach ($municipio_permitidos as $key => $value) {
                    $municipio[] = $key;
                }
            }
        }

        $listaEsferas = $this->relatorios_siconv_model->get_esferas($regiao, $estado, $municipio);
        $select_esfera = array();
        if ($this->input->post('selected_esfera', TRUE))
            $select_esfera = $this->input->post('selected_esfera', TRUE);
        if (!empty($listaEsferas)) {
            $options = "";
            foreach ($listaEsferas as $value) {
                $options .= "<option value='{$value}'";
                if ($value == 'MUNICIPAL') {
                    $options .= in_array($value, $select_esfera) ? 'selected' : '';
                    $options .= ">Governo Municipal</option>";
                } elseif ($value == 'ESTADUAL') {
                    $options .= in_array($value, $select_esfera) ? 'selected' : '';
                    $options .= ">Governo Estadual</option>";
                } elseif ($value == 'CONSORCIO PUBLICO') {
                    $options .= in_array($value, $select_esfera) ? 'selected' : '';
                    $options .= ">Consórcio Público</option>";
                } elseif ($value == 'PRIVADA') {
                    $options .= in_array($value, $select_esfera) ? 'selected' : '';
                    $options .= ">Organização da Sociedade Civil</option>";
                } elseif ($value == 'EMPRESA PUBLICA SOCIEDADE ECONOMIA MISTA') {
                    $options .= in_array($value, $select_esfera) ? 'selected' : '';
                    $options .= ">Empresas Públicas/Economia Mista</option>";
                } elseif ($value == 'FEDERAL') {
                    $options .= in_array($value, $select_esfera) ? 'selected' : '';
                    $options .= ">Governo Federal</option>";
                } elseif ($value == 'ORGANISMO INTERNACIONAL') {
                    $options .= in_array($value, $select_esfera) ? 'selected' : '';
                    $options .= ">Organismo Internacional</option>";
                }
            }
        }

        if ($this->input->post() == FALSE)
            return $options;
        else
            echo $options;
    }

}
