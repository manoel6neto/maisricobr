<?php

include 'application/controllers/BaseController.php';

class apresentacao extends BaseController {

    /**
     * Guarda o númemro (índice) da tela atual para saber qual serão a próximas e as anteriores telas.
     * 
     * Atualmente existem 11 telas:
     * 
     * 1 - Esferas administrativas (apresentacao/1_esferas.php)
     * 2 - Vídeo apresentação geral (apresentacao/2_video.php)
     * 3 - Estrutura - Indicadores de resultados (apresentacao/3_estrtura_indicadores.php)
     * 4 - Estrutura - Programas (oportunidades) (apresentacao/4_estrutura_programas.php)
     * 5 - Estrutura - Oportunidades por áreas (apresentacao/5_estrutura_oportunidades.php)
     * 6 - Estrutura - Ciclo anual de oportunidades (apresentacao/6_estrutura_ciclo.php)
     * 7 - Gerenciamento de propostas - Banco de proposta (apresentacao/8_gerenciamento_banco.php)
     * 8 - Gerenciamento de propostas - Fluxo do cadastramento (apresentacao/9_gerenciamento_fluxo.php)
     * 9 - Gerenciamento de propostas - Minhas propostas (apresentacao/10_gerenciamento_minhas_propostas.php)
     * 10 - Gerenciamento de propostas - Propostas e pareceres (apresentacao/12_propostas_pareceres.php)
     * 11 - Gerenciamento da gestão de recursos - Agenda (apresentacao/11_agenda.php)
     * 
     * Sempre que o contoller é criado, deve-se iniciar pelo index.
     */
    public function __construct() {
        parent::__construct();
    }

    public function index() {
        ini_set("max_execution_time", 0);
        ini_set("memory_limit", "-1");
        $this->db->flush_cache();

        $esfera = $this->input->post(null, true);
        if ($esfera != false) {
            $this->session->set_userdata('esfera_apresentacao', $esfera[key($esfera)]);
            //Dados da barra
            $data['num_pag'] = 2;
            $data['titulo'] = "VÍDEO DE APRESENTAÇÃO GERAL";
            $data['main'] = 'apresentacao/2_video';
        } else {
            //Dados da barra
            $data['num_pag'] = 1;
            $data['titulo'] = "ESFERAS ADMINISTRATIVAS";
            $data['main'] = 'apresentacao/1_esferas';
        }
        $data['title'] = "APRESENTAÇÃO ESICAR";
        $this->load->view('apresentacao/template_apresentacao', $data);
    }

    function alert($text) {
        echo "<script type='text/javascript'>alert('" . $text . "');</script>";
    }

    function encaminha($url) {
        echo "<script type='text/javascript'>window.location='" . $url . "';</script>";
    }

    function get_view($tela_atual) {
        ini_set("max_execution_time", 0);
        ini_set('memory_limit', '-1');

        $data = array();

        switch ($tela_atual) {
            case 1:
                $data['titulo'] = "ESFERAS ADMINISTRATIVAS";
                $data['main'] = 'apresentacao/1_esferas';
                break;
            case 2:
                $data['titulo'] = "VÍDEO DE APRESENTAÇÃO GERAL";
                $data['main'] = 'apresentacao/2_video';
                break;
            case 3:
                $this->load->model("apresentacao_model");
                $this->load->model("usuariomodel");
                $this->load->model("cidades_siconv");
                $this->load->model("relatorios_siconv_model");

                $esfera_selecionada = $this->session->userdata('esfera_apresentacao');
                $data['esfera'] = $esfera_selecionada;
                $cnpjs_aux = $this->usuariomodel->get_cnpjs_by_usuario($this->session->userdata('id_usuario'));
                $cidade = $this->cidades_siconv->get_city_by_id($cnpjs_aux[0]->id_cidade);
                $data['cidade'] = $cidade->municipio;
                $data['estado'] = $cidade->municipio_uf_nome;

                $filtro_esfera = null;
                $esfera = null;

                /* Independentemente da esfera selecionada, as regiões serão setadas.
                 * Consultas extraídas das condições, reduzindo linha de código */
                if ($cidade->municipio_uf_nome == 'MT' ||
                        $cidade->municipio_uf_nome == 'GO' ||
                        $cidade->municipio_uf_nome == 'MS') {
                    $data['centro_oeste'] = $this->apresentacao_model->get_desempenho_centrooeste_municipal();
                } elseif ($cidade->municipio_uf_nome == 'AC' ||
                        $cidade->municipio_uf_nome == 'AM' ||
                        $cidade->municipio_uf_nome == 'RR' ||
                        $cidade->municipio_uf_nome == 'RO' ||
                        $cidade->municipio_uf_nome == 'AP' ||
                        $cidade->municipio_uf_nome == 'PA' ||
                        $cidade->municipio_uf_nome == 'TO') {
                    $data['norte'] = $this->apresentacao_model->get_desempenho_norte_municipal();
                } elseif ($cidade->municipio_uf_nome == 'PR' ||
                        $cidade->municipio_uf_nome == 'SC' ||
                        $cidade->municipio_uf_nome == 'RS') {
                    $data['sul'] = $this->apresentacao_model->get_desempenho_sul_municipal();
                } elseif ($cidade->municipio_uf_nome == 'MG' ||
                        $cidade->municipio_uf_nome == 'SP' ||
                        $cidade->municipio_uf_nome == 'RJ' ||
                        $cidade->municipio_uf_nome == 'ES') {
                    $data['sudeste'] = $this->apresentacao_model->get_desempenho_sudeste_municipal();
                } else {
                    $data['nordeste'] = $this->apresentacao_model->get_desempenho_nordeste_municipal();
                }

                if ($esfera_selecionada == 'municipal') {
                    $filtro_esfera = array("MUNICIPAL");
                    $esfera = 1;
                    $data['nacional'] = $this->apresentacao_model->get_desempenho_nacional_municipal();

//                    if ($cidade->municipio_uf_nome == 'MT' ||
//                            $cidade->municipio_uf_nome == 'GO' ||
//                            $cidade->municipio_uf_nome == 'MS') {
//                        $data['centro_oeste'] = $this->apresentacao_model->get_desempenho_centrooeste_municipal();
//                    } elseif ($cidade->municipio_uf_nome == 'AC' ||
//                            $cidade->municipio_uf_nome == 'AM' ||
//                            $cidade->municipio_uf_nome == 'RR' ||
//                            $cidade->municipio_uf_nome == 'RO' ||
//                            $cidade->municipio_uf_nome == 'AP' ||
//                            $cidade->municipio_uf_nome == 'PA' ||
//                            $cidade->municipio_uf_nome == 'TO') {
//                        $data['norte'] = $this->apresentacao_model->get_desempenho_norte_municipal();
//                    } elseif ($cidade->municipio_uf_nome == 'PR' ||
//                            $cidade->municipio_uf_nome == 'SC' ||
//                            $cidade->municipio_uf_nome == 'RS') {
//                        $data['sul'] = $this->apresentacao_model->get_desempenho_sul_municipal();
//                    } elseif ($cidade->municipio_uf_nome == 'MG' ||
//                            $cidade->municipio_uf_nome == 'SP' ||
//                            $cidade->municipio_uf_nome == 'RJ' ||
//                            $cidade->municipio_uf_nome == 'ES') {
//                        $data['sudeste'] = $this->apresentacao_model->get_desempenho_sudeste_municipal();
//                    } else {
//                        $data['nordeste'] = $this->apresentacao_model->get_desempenho_nordeste_municipal();
//                    }
                } else if ($esfera_selecionada == 'osc') {
                    $filtro_esfera = array("PRIVADA");
                    $esfera = 2;
                    $data['nacional'] = $this->apresentacao_model->get_desempenho_nacional_osc();
//                    $data['centro_oeste'] = $this->apresentacao_model->get_desempenho_centrooeste_osc();
//                    $data['norte'] = $this->apresentacao_model->get_desempenho_norte_osc();
//                    $data['nordeste'] = $this->apresentacao_model->get_desempenho_nordeste_osc();
//                    $data['sul'] = $this->apresentacao_model->get_desempenho_sul_osc();
//                    $data['sudeste'] = $this->apresentacao_model->get_desempenho_sudeste_osc();
                } else if ($esfera_selecionada == 'estadual') {
                    $filtro_esfera = array("ESTADUAL");
                    $esfera = 3;
                    $data['nacional'] = $this->apresentacao_model->get_desempenho_nacional_estadual();
//                    $data['centro_oeste'] = $this->apresentacao_model->get_desempenho_centrooeste_estadual();
//                    $data['norte'] = $this->apresentacao_model->get_desempenho_norte_estadual();
//                    $data['nordeste'] = $this->apresentacao_model->get_desempenho_nordeste_estadual();
//                    $data['sul'] = $this->apresentacao_model->get_desempenho_sul_estadual();
//                    $data['sudeste'] = $this->apresentacao_model->get_desempenho_sudeste_estadual();
                } else if ($esfera_selecionada == 'mista') {
                    $filtro_esfera = array("EMPRESAS MISTAS");
                    $esfera = 4;
                    $data['nacional'] = $this->apresentacao_model->get_desempenho_nacional_mista();
//                    $data['centro_oeste'] = $this->apresentacao_model->get_desempenho_centrooeste_mista();
//                    $data['norte'] = $this->apresentacao_model->get_desempenho_norte_mista();
//                    $data['nordeste'] = $this->apresentacao_model->get_desempenho_nordeste_mista();
//                    $data['sul'] = $this->apresentacao_model->get_desempenho_sul_mista();
//                    $data['sudeste'] = $this->apresentacao_model->get_desempenho_sudeste_mista();
                } else if ($esfera_selecionada == 'consorcio') {
                    $filtro_esfera = array("CONSORCIO PUBLICO");
                    $esfera = 5;
                    $data['nacional'] = $this->apresentacao_model->get_desempenho_nacional_consorcio();
//                    $data['centro_oeste'] = $this->apresentacao_model->get_desempenho_centrooeste_consorcio();
//                    $data['norte'] = $this->apresentacao_model->get_desempenho_norte_consorcio();
//                    $data['nordeste'] = $this->apresentacao_model->get_desempenho_nordeste_consorcio();
//                    $data['sul'] = $this->apresentacao_model->get_desempenho_sul_consorcio();
//                    $data['sudeste'] = $this->apresentacao_model->get_desempenho_sudeste_consorcio();
                }

                /*
                 * Municipal
                 */

                $proponentes = $this->relatorios_siconv_model->get_proponentes_cidade($filtro_esfera, $cidade->codigo_municipio, null, null, null, null);
                $proponentes_aux = array();
                foreach ($proponentes as $cnpj) {
                    $proponentes_aux[] = $cnpj->cnpj;
                }
                $data['municipal'] = $this->relatorios_siconv_model->get_dados_siconv($proponentes_aux, null, null, null, null, null, null);

                /*
                 * Estadual
                 */
                $data['estadual'] = $this->apresentacao_model->get_desempenho_estadual(mb_strtolower($cidade->municipio_uf_sigla), $esfera);

                $data['titulo'] = "ESTRUTURA - INDICADORES DE RESULTADOS";
                $data['main'] = 'apresentacao/3_estrutura_indicadores';
                break;
            case 4:
                $this->load->model('programa_model');
                $this->load->model('cnpj_siconv');
                $this->load->model('banco_proposta_model');
                $this->load->model('usuariomodel');

                $esfera_selecionada = $this->session->userdata('esfera_apresentacao');

                $data ['cnpjs'] = $this->usuariomodel->get_cnpjs_by_usuario($this->session->userdata('id_usuario'));
                $cnpjs = array();
                foreach ($data ['cnpjs'] as $cnpj) {
                    $cnpjs[] = $cnpj->cnpj;
                }

                //Filtro esfera
                $filtro_esfera = null;
                if ($esfera_selecionada == 'municipal') {
                    $filtro_esfera = Array("Administração Pública Municipal");
                } else if ($esfera_selecionada == 'osc') {
                    $filtro_esfera = Array("Organização da Sociedade Civil");
                } else if ($esfera_selecionada == 'estadual') {
                    $filtro_esfera = Array("Administração Pública Estadual ou do Distrito Federal");
                } else if ($esfera_selecionada == 'mista') {
                    $filtro_esfera = Array("Empresa pública/Sociedade de economia mista");
                } else if ($esfera_selecionada == 'consorcio') {
                    $filtro_esfera = Array("Consórcio Público");
                }

                // Proposta Voluntária
                $filtro = Array("vigencia" => "vigencia", "qualificacao" => Array("Proposta Voluntária"), "atende" => $filtro_esfera, "orgao" => "", "pesquisa" => "");
                $result_voluntaria = $this->programa_model->busca_programa($filtro, 0, 3, true);
                $data ['num_rows_voluntaria'] = $result_voluntaria ['num_rows'];
                $data ['lista_voluntaria'] = $result_voluntaria ['lista'];
                if (!empty($data['lista_voluntaria']))
                    $data ['num_propostas_programas_voluntaria'] = count($this->banco_proposta_model->get_propostas_proponente_programa($cnpjs, $data ['lista_voluntaria']));
                else
                    $data ['num_propostas_programas_voluntaria'] = 0;

                $data['num_programas_utilizados_voluntaria'] = 0;
                foreach ($data ['lista_voluntaria'] as $programa) {
                    if ($this->banco_proposta_model->check_programa_tem_propostas($programa->codigo, $cnpjs)) {
                        $data['num_programas_utilizados_voluntaria'] ++;
                    }
                }
                $data['codigos_ocultos_voluntaria'] = $result_voluntaria ['codigos_ocultos'];

                // Proposta de Proponente de Emenda Parlamentar
                $filtro = Array("vigencia" => "vigencia", "qualificacao" => Array("Proposta de Proponente de Emenda Parlamentar"), "atende" => $filtro_esfera, "orgao" => "", "pesquisa" => "");
                $result_ep = $this->programa_model->busca_programa($filtro, 0, 3, true);
                $data ['num_rows_ep'] = $result_ep ['num_rows'];
                $data ['lista_ep'] = $result_ep ['lista'];

                if (!empty($data['lista_ep']))
                    $data ['num_propostas_programas_ep'] = count($this->banco_proposta_model->get_propostas_proponente_programa($cnpjs, $data ['lista_ep']));
                else
                    $data ['num_propostas_programas_ep'] = 0;

                $data['num_programas_utilizados_ep'] = 0;
                foreach ($data ['lista_ep'] as $programa) {
                    if ($this->banco_proposta_model->check_programa_tem_propostas($programa->codigo, $cnpjs)) {
                        $data['num_programas_utilizados_ep'] ++;
                    }
                }
                $data['codigos_ocultos_ep'] = $result_ep ['codigos_ocultos'];

                // Proposta de Proponente Específico do Concedente
                $filtro = Array("vigencia" => "vigencia", "qualificacao" => Array("Proposta de Proponente Específico do Concedente"), "atende" => $filtro_esfera, "orgao" => "", "pesquisa" => "");
                $result_ec = $this->programa_model->busca_programa($filtro, 0, 3, true);
                $data ['num_rows_ec'] = $result_ec ['num_rows'];
                $data ['lista_ec'] = $result_ec ['lista'];
                if (!empty($data['lista_osc']))
                    $data ['num_propostas_programas_ec'] = count($this->banco_proposta_model->get_propostas_proponente_programa($cnpjs, $data ['lista_ec']));
                else
                    $data ['num_propostas_programas_ec'] = 0;

                $data['num_programas_utilizados_ec'] = 0;
                foreach ($data ['lista_ec'] as $programa) {
                    if ($this->banco_proposta_model->check_programa_tem_propostas($programa->codigo, $cnpjs)) {
                        $data['num_programas_utilizados_ec'] ++;
                    }
                }
                $data['codigos_ocultos_ec'] = $result_ec ['codigos_ocultos'];
                $data['lista_entidades'] = $this->cnpj_siconv->get_esfera_cnpj();

                $data['titulo'] = "ESTRUTURA - PROGRAMAS (OPORTUNIDADES)";
                $data['main'] = 'apresentacao/4_estrutura_programas';
                break;
            case 5:
                $this->load->model('apresentacao_model');

                $esfera_selecionada = $this->session->userdata('esfera_apresentacao');

                $programas_por_ministerio = $this->apresentacao_model->get_programas_from_area($esfera_selecionada);
                $total = 0;
                foreach ($programas_por_ministerio as $ministerio) {
                    foreach ($ministerio as $prog) {
                        $total++;
                    }
                }

                $data['programas_ministerio'] = $programas_por_ministerio;
                $data['total'] = $total;
                $data['titulo'] = "ESTRUTURA - OPORTUNIDADES POR ÁREAS";
                $data['main'] = 'apresentacao/5_estrutura_oportunidades';
                break;
            case 6:
                date_default_timezone_set('America/Sao_Paulo');
                $ano = date('Y') - 1;
                $this->load->model('programa_model'); // carregando model do programa
                $this->load->model('cnpj_siconv');
                $this->load->model('banco_proposta_model');
                $this->load->model('usuariomodel');

                $esfera_selecionada = $this->session->userdata('esfera_apresentacao');
                $data['esfera'] = $esfera_selecionada;
                $data ['cnpjs'] = $this->usuariomodel->get_cnpjs_by_usuario($this->session->userdata('id_usuario'));
                $cnpjs = array();
                foreach ($data ['cnpjs'] as $cnpj) {
                    $cnpjs[] = $cnpj->cnpj;
                }

                $filtro_esfera = NULL;
                if ($esfera_selecionada == 'municipal') {
                    $filtro_esfera = Array("Administração Pública Municipal");
                } else if ($esfera_selecionada == 'osc') {
                    $filtro_esfera = Array("Organização da Sociedade Civil");
                } else if ($esfera_selecionada == 'estadual') {
                    $filtro_esfera = Array("Administração Pública Estadual ou do Distrito Federal");
                } else if ($esfera_selecionada == 'mista') {
                    $filtro_esfera = Array("Empresa pública/Sociedade de economia mista");
                } else if ($esfera_selecionada == 'consorcio') {
                    $filtro_esfera = Array("Consórcio Público");
                }

                $filtro = Array("data_inicio" => "01/01/" . $ano, "data_fim" => "31/12/" . $ano, "qualificacao" => Array("Proposta Voluntária"), "atende" => $filtro_esfera, "orgao" => "", "pesquisa" => "", "apresentacao" => TRUE);
                $result = $this->programa_model->busca_programa($filtro, 0, 500, true);

                $data ['num_rows'] = $result ['num_rows'];
                $data ['lista'] = $result ['lista'];

                if (!empty($data['lista']))
                    $data ['num_propostas_programas'] = count($this->banco_proposta_model->get_propostas_proponente_programa($cnpjs, $data ['lista']));
                else
                    $data ['num_propostas_programas'] = 0;

                $data['num_programas_utilizados'] = 0;
                foreach ($data ['lista'] as $programa) {
                    if ($this->banco_proposta_model->check_programa_tem_propostas($programa->codigo, $cnpjs)) {
                        $data['num_programas_utilizados'] ++;
                    }
                }
                $data['codigos_ocultos'] = $result ['codigos_ocultos'];

                $data['lista_entidades'] = $this->cnpj_siconv->get_esfera_cnpj();
                $data['titulo'] = "ESTRUTURA - CICLO ANUAL DE OPORTUNIDADES";
                $data['main'] = 'apresentacao/6_estrutura_ciclo';
                break;
            case 7:
                $this->load->model('proposta_model');
                $this->load->model('data_model');
                $data['propostas'] = $this->proposta_model->get_all_ativo_padrao($this->input->post('pesquisar', TRUE));
                $data['titulo'] = "GERENCIAMENTO DE PROPOSTAS - BANCO DE PROPOSTA";
                $data['main'] = 'apresentacao/7_gerenciamento_banco';
                break;
            case 8:
                $data['titulo'] = "GERENCIAMENTO DE PROPOSTAS - FLUXO DE CADASTRAMENTO";
                $data['main'] = 'apresentacao/8_gerenciamento_fluxo';
                break;
            case 9:
                $data['titulo'] = "GERENCIAMENTO DE PROPOSTAS - MINHAS PROPOSTAS";
                /* A view de propostas aqui é a mesma da de visualização de propostas reais.
                 * O campo 'apresentacao' vai ser checado para bloquear os botões de ações na tela.
                 */
                $data['apresentacao'] = true;
//                $data['main'] = 'apresentacao/9_gerenciamento_minhas_propostas';

                /* Código idêntico ao código em "in\gestor.php" função "visualiza_propostas()" */
                ini_set("max_execution_time", 0);
                ini_set("memory_limit", "-1");

                $this->db->flush_cache();
                $usuario = $this->session->userdata('id_usuario');
                $this->session->set_userdata('pagAtual', 'visualiza_propostas');

                $this->load->model('proposta_model');
                $this->load->model('usuariomodel');
                $this->load->model('trabalho_model');
                $this->load->model('cnpj_siconv');
                $this->load->model('programa_proposta_model');
                $this->load->model('area_model');

                $filtro = "";
                if ($this->input->post('filtro', TRUE) != false)
                    $filtro = $this->input->post('filtro', TRUE);

                $data['propostas_enviadas'] = $this->proposta_model->get_all_ativo_enviadas($usuario, $filtro);
                $data['propostas_cadastradas'] = $this->proposta_model->get_all_ativo_cadastradas($usuario, $filtro);
                $data['programa_proposta_model'] = $this->programa_proposta_model;
                $data['propostas_importadas'] = $this->proposta_model->get_all_ativo_padrao_padrao_oculto();
                $data['usuariomodel'] = $this->usuariomodel;

                $data['areas'] = $this->area_model->get_all();

                //Olhar falta filtro sistema estado
                if ($this->session->userdata('nivel') == 4) {
                    $data['cnpjs'] = $this->usuariomodel->get_municipio_by_vendedor($usuario);
                } else if ($this->session->userdata('nivel') == 1) {
                    $data['cnpjs'] = $this->usuariomodel->get_lista_cidade_by_admin();
                } else {
                    $data['cnpjs'] = $this->usuariomodel->get_lista_cidades_by_cnpj($usuario);
                }

                $data['propostas_mais_trinta_dias'] = $this->proposta_model->checa_propostas_trinta_dias();
                $data['main'] = 'gestor/visualiza_propostas';

                break;
            case 10:
                $this->load->model('programa_model');
                $this->load->model('banco_proposta_model');
                $data['banco_proposta_model'] = $this->banco_proposta_model;
                $data['programa_model'] = $this->programa_model;
                $data['anos'] = $this->banco_proposta_model->get_anos_by_usuario();

                $filtro = $this->input->post();
                $filtro['anos'] = array("TODOS");
                foreach ($data['anos'] as $ano) {
                    array_push($filtro['anos'], $ano->ano);
                }

                $data['dados_propostas'] = $this->banco_proposta_model->busca_programas_pareceres($filtro);
                if ($this->session->userdata('nivel') == 6 || $this->session->userdata('nivel') == 7 || $this->session->userdata('nivel') == 8)
                    $data['num_cnpj'] = count($this->usuario_cnpj->get_all_by_subgestor($id_usuario));
                else
                    $data['num_cnpj'] = count($this->usuario_cnpj->get_all_by_usuario($this->session->userdata('id_usuario'), 'LEFT'));
                $data['titulo'] = "GERENCIAMENTO DE PROPOSTAS - PROPOSTAS E PARECERES";
                $data['main'] = 'apresentacao/10_propostas_pareceres';
                break;
            case 11:
                date_default_timezone_set('America/Sao_Paulo');
                $ano = date('Y');

                $calendario[0] = array('titulo' => 'Reunião de apresentação geral', 'descricao' => 'Libera acesso aos técnicos', 'data_inicio' => $ano . '-01-05', 'data_fim' => $ano . '-01-05', 'existente' => '1');
                $calendario[1] = array('titulo' => 'Reunião com secretários', 'descricao' => 'Libera acesso aos secretários', 'data_inicio' => $ano . '-01-06', 'data_fim' => $ano . '-01-06', 'existente' => '1');
                $calendario[2] = array('titulo' => 'Reunião com entidades', 'descricao' => 'Formação da incubadora', 'data_inicio' => $ano . '-01-10', 'data_fim' => $ano . '-01-10', 'existente' => '1');
                $calendario[3] = array('titulo' => 'Reunião com prefeito', 'descricao' => 'Entrega das propostas e seleção de propostas para vereadores e lideres', 'data_inicio' => $ano . '-01-15', 'data_fim' => $ano . '-01-15', 'existente' => '1');
                $calendario[4] = array('titulo' => 'Reunião com vereadores', 'descricao' => 'Distribuição de propostas', 'data_inicio' => $ano . '-01-15', 'data_fim' => $ano . '-01-15', 'existente' => '1');
                $calendario[5] = array('titulo' => 'Reunião com as comunidades', 'descricao' => 'Anuncio de propostas ', 'data_inicio' => $ano . '-01-15', 'data_fim' => $ano . '-01-15', 'existente' => '1');

                //Agenda anual
                $calendario[6] = array('titulo' => 'Programação de emendas genérica', 'descricao' => 'Programação de emendas genérica', 'data_inicio' => $ano . '-11-01', 'data_fim' => $ano . '-11-30', 'existente' => '1');
                $calendario[7] = array('titulo' => 'Programação de emendas de bancadas', 'descricao' => 'Programação de emendas de bancadas', 'data_inicio' => $ano . '-11-01', 'data_fim' => $ano . '-11-30', 'existente' => '1');
                $calendario[8] = array('titulo' => 'Reprogramação de recursos de emendas ', 'descricao' => 'Reprogramação de recursos de emendas ', 'data_inicio' => $ano . '-11-01', 'data_fim' => $ano . '-12-31', 'existente' => '1');
                $calendario[9] = array('titulo' => 'Raspa tacho', 'descricao' => 'Raspa tacho', 'data_inicio' => $ano . '-12-01', 'data_fim' => $ano . '-12-31', 'existente' => '1');
                $calendario[10] = array('titulo' => 'Programação de emendas impositivas', 'descricao' => 'Programação de emendas impositivas', 'data_inicio' => $ano . '-11-01', 'data_fim' => $ano . '-03-31', 'existente' => '1');
                $calendario[11] = array('titulo' => 'Consolidação de emendas genéricas', 'descricao' => 'Consolidação de emendas genéricas', 'data_inicio' => $ano . '-01-01', 'data_fim' => $ano . '-04-30', 'existente' => '1');
                $calendario[12] = array('titulo' => 'Aprovação de emendas', 'descricao' => 'Aprovação de emendas', 'data_inicio' => $ano . '-04-01', 'data_fim' => $ano . '-08-31', 'existente' => '1');
                $data['eventos'] = $calendario;
                $data['titulo'] = "GERENCIAMENTO DE RECURSOS - AGENDA";
                $data['main'] = 'apresentacao/11_agenda';
                break;
        }

        return $data;
    }

    function page() {
        $tela_atual = $this->input->get('num_pag');

        $data = $this->get_view($tela_atual);

        $data['title'] = "APRESENTAÇÃO ESICAR";
        $data['num_pag'] = $tela_atual;

        $this->load->view('apresentacao/template_apresentacao', $data);
    }

}
