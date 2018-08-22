<?php

include 'application/controllers/BaseController.php';

class Dados_siconv extends BaseController {

    function __construct() {
        parent::__construct();

        $this->load->model('usuariomodel');

        if ($this->session->userdata('id_usuario') != "") {
            $this->usuario_logado = $this->usuariomodel->get_by_id($this->session->userdata('id_usuario'));
            $this->login_usuario = $this->usuario_logado->login_siconv;
            $this->senha = base64_decode($this->usuario_logado->senha_siconv);
            $this->login = $this->usuario_logado->nome;
        } else {
            $this->login_usuario = "43346880559";
            $this->senha = 'Laisa_M2012';
        }

        $this->cookie_file_path = tempnam("/tmp", "CURLCOOKIE" . rand());
        $this->login_siconv = null;
    }

    function teste() {
        $this->load->model('trabalho_model');
        $this->trabalho_model->atualiza_proponente();
        die();
    }

    function cidades_ajax() {
        $cod_estados = mysql_real_escape_string($_REQUEST ['cod_estados']);

        $this->load->model('cidades_model');
        $cidades_aux = $this->cidades_model->obter_cidades($cod_estados);

        foreach ($cidades_aux as $cidade) {
            $cidades [] = array(
                'cod_cidades' => $cidade ['cod_cidades'],
                'Codigo' => $cidade ['cod_cidades'],
                'nome' => $cidade ['nome']
            );
        }

        echo (json_encode($cidades));
    }

    // Função de busca por programas
    // Data de criação: 18/06/2014
    // Autores: Lucas
    // Descrição: Busca de programas por Data, Qualificação do Proponente,Qualificação da Proposta
    // e palavra-chave(buscando em todos os campos das tuplas). A ideia é criar uma busca única, rapida
    // e pontual. Uso de views no banco de dados serão implementadas para melhor desempenho.
    // View: in/busca_programas
    // Template: in/template
    function busca_programas($offSet = 0) {
        header('Content-Type: text/html; charset=utf-8');
        $this->load->model('programa_model'); // carregando model do programa
        $this->load->model('cnpj_siconv');
        $this->load->model('banco_proposta_model');
        ini_set('memory_limit', '-1');

        $data = $this->input->post(NULL, TRUE); // pega todos os post
        if ($data != null) {
            if ($this->input->post('data_inicio', TRUE) != false && $this->input->post('data_fim', TRUE) != false) {
                if ($data['data_inicio'] != '' && $data['data_fim'] != '') {
                    $data_inicio = $this->date_converter($data['data_inicio']);
                    $data_fim = $this->date_converter($data['data_fim']);
                    $date_data_inicio = strtotime($data_inicio);
                    $date_data_fim = strtotime($data_fim);

                    if ($data_inicio > $data_fim) {
                        $this->alert('Data de inicio maior que data final');
                    }
                }
            }
        }

        $result = $this->programa_model->busca_programa($data, $offSet, 1000, true);

        $data['filtro'] = is_array($data) ? $data : null;
        $data['anexos'] = $this->programa_model->busca_anexos();
        $data['editais'] = $this->programa_model->busca_editais();
        $data ['total_rows'] = $result ['total_rows'];
        $data ['num_rows'] = $result ['num_rows'];
        $data ['lista'] = $result ['lista'];
        $data ['cnpjs'] = $this->usuariomodel->get_cnpjs_by_usuario($this->session->userdata('id_usuario'));
        $data['lista_entidades'] = $this->cnpj_siconv->get_esfera_cnpj();

        $cnpjs = array();
        foreach ($data ['cnpjs'] as $cnpj) {
            $cnpjs[] = $cnpj->cnpj;
        }

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

        $this->session->set_userdata('pagAtual', 'busca_programas');

        $data['usuario_parlamentar'] = false;
        if ($this->session->userdata('nivel') == 2 || $this->session->userdata('nivel') == 3 || $this->session->userdata('nivel') == 4 || $this->session->userdata('nivel') == 15)
            $data['usuario_parlamentar'] = ($this->session->userdata('usuario_sistema') == "P" || ($this->session->userdata('usuario_sistema') == "T" && $this->session->userdata('estado_parlamentar') != ""));

        $data['cnpj_siconv'] = $this->cnpj_siconv;
        $data['banco_proposta_model'] = $this->banco_proposta_model;
        $data ['orgaos'] = $this->programa_model->obter_lista_distinct('orgao');
        if ($this->session->userdata('gp') == true || $this->input->get('gp', TRUE) != false) {
            $data ['title'] = "G&P - Busca de Programas"; // titulo da pagina
        } else {
            $data ['title'] = "Physis - Busca de Programas"; // titulo da pagina
        }
        $data ['main'] = 'in/busca_programas'; // view da pagina
        $data['programa_model'] = $this->programa_model;
        $data ['login'] = $this->usuario_logado->nome;
        $data['codigos_ocultos'] = $result ['codigos_ocultos'];
        $this->load->view('in/template', $data); // chamando template e passando todos os dados
    }

    public function date_converter($_date = null) {
        $format = '/^([0-9]{2})\/([0-9]{2})\/([0-9]{4})$/';
        if ($_date != null && preg_match($format, $_date, $partes)) {
            return $partes[3] . '-' . $partes[2] . '-' . $partes[1];
        }
        return false;
    }

    public function oculta_programas() {
        $options = array('id_usuario' => $this->session->userdata('id_usuario'), 'codigo_programa' => $this->input->post('idsPrograma', TRUE));

        if (!empty($options))
            $this->db->insert('programas_ocultos', $options);
    }

    public function desoculta_programas() {
        $options = array('id_usuario' => $this->session->userdata('id_usuario'), 'codigo_programa' => $this->input->post('idsPrograma', TRUE));

        if (!empty($options))
            $this->db->delete('programas_ocultos', $options);
    }

    public function busca_emendas() {
        header('Content-Type: text/html; charset=utf-8');
        $this->load->model('programa_model'); // carregando model do programa
        $this->load->model('banco_proposta_model');
        $this->load->model('usuariomodel');
        $this->load->model('programa_banco_proposta_model');
        $this->load->model('proponente_siconv_model');

        //Validando o submit do formulário
        $data = $this->input->post(NULL, TRUE); // pega todos os post
        $data['filtro'] = is_array($data) ? $data : null;
        $data['flag_buscou'] = false;
        $data['emendas_propostas'] = $this->programa_model->get_all_emendas_by_usuario($data);
        $data['emendas'] = $this->programa_model->get_emendas_by_parlamentar_from_beneficiario($data);
        $data['flag_buscou'] = true;

        //Carregamento default da página
        if (!isset($data['filtro']['anos'])) {
            $data['filtro']['anos'] = array(date('Y'));
        }
        $this->session->set_userdata('pagAtual', 'busca_emendas');
        $data['programa_model'] = $this->programa_model;
        $data['usuariomodel'] = $this->usuariomodel;
        $data['banco_proposta_model'] = $this->banco_proposta_model;
        $data['programa_banco_proposta_model'] = $this->programa_banco_proposta_model;
        $data['proponente_siconv_model'] = $this->proponente_siconv_model;
        $data['anos'] = $this->programa_model->get_all_years_for_emendas();

        if ($this->session->userdata('gp') == true || $this->input->get('gp', TRUE) != false) {
            $data ['title'] = "G&P - Busca de Emendas"; // titulo da pagina
        } else {
            $data ['title'] = "Physis - Busca de Emendas"; // titulo da pagina
        }
        $data ['main'] = 'in/busca_emendas'; // view da pagina
        $data['programa_model'] = $this->programa_model;
        $data ['login'] = $this->usuario_logado->nome;
        $this->load->view('in/template', $data); // chamando template e passando todos os dados
    }

    public function busca_emendas_geral() {
        ini_set("max_execution_time", 0);
        ini_set("memory_limit", "-1");

        $this->load->model('programa_model'); // carregando model do programa
        $this->load->model('banco_proposta_model');
        $this->load->model('usuariomodel');
        $this->load->model('programa_banco_proposta_model');
        $this->load->model('proponente_siconv_model');

        $data = $this->input->post(NULL, TRUE); // pega todos os post

        $data['filtro'] = is_array($data) ? $data : null;

        $data['emendas_propostas'] = $this->programa_model->get_all_emendas($data);
        $data['emendas'] = $this->programa_model->get_emendas_by_parlamentar_from_geral($data);
        $data['proponentes'] = $this->programa_model->get_municipios_by_emendas($data, true);
        $data['proponentes_emendas'] = $this->programa_model->get_municipios_by_emendas($data);

        $this->session->set_userdata('pagAtual', 'busca_emendas_geral');

        $data['programa_model'] = $this->programa_model;
        $data['usuariomodel'] = $this->usuariomodel;
        $data['banco_proposta_model'] = $this->banco_proposta_model;
        $data['programa_banco_proposta_model'] = $this->programa_banco_proposta_model;
        $data['proponente_siconv_model'] = $this->proponente_siconv_model;
        $data['anos'] = $this->programa_model->get_all_years_for_emendas_geral();

        if ($this->session->userdata('gp') == true || $this->input->get('gp', TRUE) != false) {
            $data ['title'] = "G&P - Busca de Emendas"; // titulo da pagina
        } else {
            $data ['title'] = "Physis - Busca de Emendas"; // titulo da pagina
        }
        $data ['main'] = 'in/busca_emendas_geral'; // view da pagina
        $data['programa_model'] = $this->programa_model;
        $data ['login'] = $this->usuario_logado->nome;
        $this->load->view('in/template', $data); // chamando template e passando todos os dados
    }

    public function busca_emendas_municipio($offSet = 0) {
        header('Content-Type: text/html; charset=utf-8');
        $this->load->model('programa_model'); // carregando model do programa
        $this->load->model('cnpj_siconv');
        $this->load->model('banco_proposta_model');

        $data = $this->input->post(NULL, TRUE); // pega todos os post
        if ($data != null) {
            if ($this->input->post('data_inicio', TRUE) != false && $this->input->post('data_fim', TRUE) != false) {
                if ($data['data_inicio'] != '' && $data['data_fim'] != '') {
                    $data_inicio = $this->date_converter($data['data_inicio']);
                    $data_fim = $this->date_converter($data['data_fim']);
                    $date_data_inicio = strtotime($data_inicio);
                    $date_data_fim = strtotime($data_fim);

                    if ($data_inicio > $data_fim) {
                        $this->alert('Data de inicio maior que data final');
                    }
                }
            }
        }

        $lista_cnpjs = array();
        if ($this->input->post('cnpj', TRUE) == FALSE) {
            if ($this->session->userdata('nivel') == 4 || $this->session->userdata('nivel') == 15) {
                if ($this->session->userdata('sistema') == 'M' || $this->session->userdata('sistema') == "E") {
                    $this->load->model('usuariomodel');
                    $cnpjs = $this->usuariomodel->get_estados_by_usuario($this->session->userdata('id_usuario'));

                    $this->db->flush_cache();

                    foreach ($cnpjs as $c)
                        $lista_cnpjs[] = $c->cnpj;
                }
            } else if ($this->session->userdata('nivel') == 2 || $this->session->userdata('nivel') == 3 || $this->session->userdata('nivel') == 5 || $this->session->userdata('nivel') == 6 || $this->session->userdata('nivel') == 7 || $this->session->userdata('nivel') == 8) {
                if ($this->session->userdata('sistema') == 'M' || $this->session->userdata('sistema') == "E") {
                    $this->load->model('usuariomodel');
                    $cnpjs = $this->usuariomodel->get_estados_by_usuario($this->session->userdata('id_usuario'));

                    $this->db->flush_cache();

                    foreach ($cnpjs as $c)
                        $lista_cnpjs[] = $c->cnpj;
                }
            }
        } else
            $lista_cnpjs = $this->input->post('cnpj', TRUE);

        $result = $this->programa_model->busca_programa_emendas($data, $offSet, 1000, true);
        $quantidade_parlamentar = 0;
        $quantidade_especifico = 0;
        $valor_emendas_disponibilizadas = doubleval(0);
        $valor_emendas_utilizados = doubleval(0);
        foreach ($result['lista'] as $value) {
            $dados_emenda = $this->programa_model->get_dados_beneficiario($value->codigo_beneficiario, $lista_cnpjs, true);
            foreach ($dados_emenda as $beneficiario) {
                $valor_emendas_disponibilizadas = doubleval($valor_emendas_disponibilizadas) + doubleval(trim(explode("R$", str_replace(',', '.', str_replace('.', '', $beneficiario->emenda_valor)))[1]));
                if ($beneficiario->emenda != "")
                    $quantidade_parlamentar++;
                else
                    $quantidade_especifico++;
            }

            $propostas = $this->banco_proposta_model->get_propostas_proponente_programa($lista_cnpjs, $value->codigo);
            foreach ($propostas as $proposta) {
                //if ($this->banco_proposta_model->verifica_proposta_aprovada($proposta->situacao))
                $valor_emendas_utilizados = doubleval($valor_emendas_utilizados) + doubleval(trim(explode("R$", str_replace(',', '.', str_replace('.', '', $proposta->valor_global)))[1]));
            }
        }

        $data['filtro'] = is_array($data) ? $data : null;
        $data['filtro']['filtro_cnpj']['cnpj'] = $lista_cnpjs;

        $data ['quantidade_parlamentar'] = $quantidade_parlamentar;
        $data ['quantidade_especifico'] = $quantidade_especifico;
        $data ['valor_emendas_disponibilizadas'] = $valor_emendas_disponibilizadas;
        $data ['valor_emendas_utilizados'] = $valor_emendas_utilizados;

        $data ['total_rows'] = $result ['total_rows'];
        $data ['num_rows'] = $result ['num_rows'];
        $data ['lista'] = $result ['lista'];
        $data ['cnpjs'] = $this->usuariomodel->get_cnpjs_by_usuario($this->session->userdata('id_usuario'));
        $data['lista_entidades'] = $this->cnpj_siconv->get_esfera_cnpj();

        $this->session->set_userdata('pagAtual', 'busca_emendas_geral');

        $data['usuario_parlamentar'] = false;
        if ($this->session->userdata('nivel') == 2 || $this->session->userdata('nivel') == 3 || $this->session->userdata('nivel') == 4 || $this->session->userdata('nivel') == 15)
            $data['usuario_parlamentar'] = ($this->session->userdata('usuario_sistema') == "P" || ($this->session->userdata('usuario_sistema') == "T" && $this->session->userdata('estado_parlamentar') != ""));

        $data['cnpj_siconv'] = $this->cnpj_siconv;
        $data['banco_proposta_model'] = $this->banco_proposta_model;
        $data ['orgaos'] = $this->programa_model->obter_lista_distinct('orgao');
        if ($this->session->userdata('gp') == true || $this->input->get('gp', TRUE) != false) {
            $data ['title'] = "G&P - Busca de Programas"; // titulo da pagina
        } else {
            $data ['title'] = "Physis - Busca de Programas"; // titulo da pagina
        }
        $data ['main'] = 'in/busca_emendas_municipio'; // view da pagina
        $data['programa_model'] = $this->programa_model;
        $data ['login'] = $this->usuario_logado->nome;
        $this->load->view('in/template', $data); // chamando template e passando todos os dados
    }

    public function rel_emendas_pdf() {
        $this->load->model('programa_model');
        $this->load->model('banco_proposta_model');
        $this->load->model('usuariomodel');
        $this->load->model('programa_banco_proposta_model');
        $this->load->model('proponente_siconv_model');

        $data = $this->input->post(NULL, TRUE); // pega todos os post

        if (isset($data['pdf_anos'])) {
            $data['anos'] = $data['pdf_anos'];
            unset($data['pdf_anos']);
        }

        $data['filtro'] = is_array($data) ? $data : null;

        //$data['emendas'] = $this->programa_model->get_all_emendas_by_usuario($data);
        $data['emendas_propostas'] = $this->programa_model->get_all_emendas_by_usuario($data);
        $data['emendas'] = $this->programa_model->get_emendas_by_parlamentar_from_beneficiario($data);

        $data['programa_model'] = $this->programa_model;
        $data['usuariomodel'] = $this->usuariomodel;
        $data['banco_proposta_model'] = $this->banco_proposta_model;
        $data['programa_banco_proposta_model'] = $this->programa_banco_proposta_model;
        $data['proponente_siconv_model'] = $this->proponente_siconv_model;

        if ($this->session->userdata('gp') == true || $this->input->get('gp', TRUE) != false) {
            $data ['title'] = "G&P - Busca de Emendas";
        } else {
            $data ['title'] = "Physis - Busca de Emendas";
        }
        $data['programa_model'] = $this->programa_model;

        $this->load->library('mPDF');
        ob_start(); // inicia o buffer
        $tabela = utf8_encode($tabela);

        $mpdf = new mPDF('', 'A4-L');
        $mpdf->allow_charset_conversion = true;
        $mpdf->charset_in = 'UTF-8';

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

        $tabela = $this->load->view('in/rel_emendas_pdf_geral_completo', $data, true);

        $mpdf->WriteHTML($tabela);
        $mpdf->Output();

        die();
    }

    public function rel_emendas_pdf_geral() {
        $this->load->model('programa_model'); // carregando model do programa
        $this->load->model('banco_proposta_model');
        $this->load->model('usuariomodel');
        $this->load->model('programa_banco_proposta_model');
        $this->load->model('proponente_siconv_model');

        $data = $this->input->post(NULL, TRUE); // pega todos os post

        $data['emendas'] = $this->programa_model->get_all_emendas($data);

        $data['programa_model'] = $this->programa_model;
        $data['usuariomodel'] = $this->usuariomodel;
        $data['banco_proposta_model'] = $this->banco_proposta_model;
        $data['programa_banco_proposta_model'] = $this->programa_banco_proposta_model;
        $data['proponente_siconv_model'] = $this->proponente_siconv_model;

        if ($this->session->userdata('gp') == true || $this->input->get('gp', TRUE) != false) {
            $data ['title'] = "G&P - Busca de Emendas"; // titulo da pagina
        } else {
            $data ['title'] = "Physis - Busca de Emendas"; // titulo da pagina
        }
        $data['programa_model'] = $this->programa_model;

        $this->load->library('mPDF');
        ob_start(); // inicia o buffer
        $tabela = utf8_encode($tabela);

        $mpdf = new mPDF('', 'A4-L');
        $mpdf->allow_charset_conversion = true;
        $mpdf->charset_in = 'UTF-8';

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

        $tabela = $this->load->view('in/rel_emendas_pdf_geral', $data, true);

        $mpdf->WriteHTML($tabela);
        $mpdf->Output();

        die();
    }

    function gerapdf_lista() {
        $data ['ides'] = $this->input->post('ides', TRUE); // pega todos os post
        $data['cnpj'] = $this->input->post('cnpj', TRUE);

        $this->load->model('programa_model');
        $this->load->model('usuariomodel');

        ob_start(); // inicia o buffer

        $this->load->library('mpdf60/mpdf.php');
        $mpdf = new mPDF ();
        $mpdf->allow_charset_conversion = true;
        $mpdf->charset_in = 'UTF-8';
        $mpdf->SetDefaultFontSize(12);
        $mpdf->SetMargins(5, 5, 3);
        $mpdf->margin_bottom_collapse = true;
        setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');

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


        $orgao = "";
        $resultados = $this->programa_model->get_programa_by_codigo($data['ides']);
        $total = 0;
        $i = 0;
        foreach ($resultados as $key => $query) {

            if ($orgao == "" || $orgao != $query->orgao) {
                #Verificar para alinhar ao centro
                $tabela = "<div style='color:red; text-align:center;'>Relatório de Programas Abertos</div>";
                $tabela .= "<br /><br /><table style=\"font-size: 12px; page-break-inside: avoid;\">";
                //$tabela .= "<br /><br /><table style=\"font-size: 14px;\">";
                $total = 0;
            } else {
                $tabela = "<br /><br /><table style=\"font-size: 12px; page-break-inside: avoid;\">";
                //$tabela = "<br /><br /><table style=\"font-size: 14px;\">";
            }

            if (isset($data['cnpj']) && $data['cnpj'] != "") {
                $dados_emenda = $this->programa_model->get_dados_beneficiario($query->codigo, $data['cnpj'], true);
                if ($dados_emenda != null) {
                    $mostraTitulo = true;
                    foreach ($dados_emenda as $d) {
                        $tabela .= "<tr>";

                        if ($mostraTitulo)
                            $tabela .= "<td rowspan='" . count($dados_emenda) . "' style=\"vertical-align:top;\"><b>EMENDA(S): </b></td>";

                        $tabela .= "<td colspan='2' style=\"color:#428bca; width:800px;\">- " . $d->emenda_cnpj . " | " . $d->emenda_nome . " | " . $d->emenda_valor;

                        if (isset($d->emenda) && $d->emenda != "")
                            $tabela .= "<br/>&nbsp;<b>PARLAMENTAR: </b>" . $this->programa_model->get_parlamentar_by_emenda($d->emenda) . " | " . $d->emenda . "</td>";
                        else
                            $tabela .= "<br/>&nbsp;<b>ORGÃO: </b>" . trim($query->orgao_vinculado) . "</td>";

                        $tabela .= "</tr>";

                        $mostraTitulo = false;
                    }
                }
            }

            $tabela .= "<tr><td colspan='3'></td></tr>";

            $tabela .= "<tr><td colspan='3'><b>ORGÃO SUPERIOR: </b></td></tr>";
            $tabela .= "<tr><td colspan=\"3\" style=\"color:#00008B; font-size: 12px;\">" . trim($query->orgao) . "</td></tr>";
            $tabela .= "<tr><td colspan='3'></td></tr>";
            $tabela .= "<tr><td colspan='3'></td></tr>";

            $tabela .= "<tr><td colspan='3'><b>ORGÃO PROVENENTE: </b></td></tr>";
            $tabela .= "<tr><td colspan=\"3\" style=\"font-size: 12px;\">" . trim($query->orgao_vinculado) . "</td></tr>";
            $tabela .= "<tr><td colspan='3'></td></tr>";
            $tabela .= "<tr><td colspan='3'></td></tr>";

            $tabela .= "<tr><td colspan='3'><b>CÓDIGO DO PROGRAMA:</b></td></tr>";
            $tabela .= "<tr><td colspan=\"3\" style=\"font-size: 12px;\">" . $query->codigo . "</td></tr>";
            $tabela .= "<tr><td colspan='3'></td></tr>";
            $tabela .= "<tr><td colspan='3'></td></tr>";

            $tabela .= "<tr><td colspan='3'><b>QUALIFICAÇÃO: </b> </td></tr>";
            $tabela .= "<tr><td colspan=\"3\" style=\"text-align: justify; font-size: 12px;\">" . trim($query->qualificacao) . "</td></tr>";
            $tabela .= "<tr><td colspan='3'></td></tr>";
            $tabela .= "<tr><td colspan='3'></td></tr>";

            $tabela .= "<tr><td colspan='3'><b>VIGÊNCIA: </b></td></tr>";

            if (isset($query->data_inicio) && strtotime($query->data_inicio) > 0) {
                $tabela .= "<tr><td colspan='3'><b>Proposta Voluntária<br>Início:</b> " . implode('/', array_reverse(explode('-', $query->data_inicio))) . "&nbsp;&nbsp;
						<b>Fim:</b> " . implode('/', array_reverse(explode('-', $query->data_fim))) . "<br/><br/></td></tr>";
            }

            if (isset($query->data_inicio_benef) && strtotime($query->data_inicio_benef) > 0) {
                $tabela .= "<tr><td colspan='3'><b>Proposta de Proponente Específico do Concedente<br>Início:</b> " . implode('/', array_reverse(explode('-', $query->data_inicio_benef))) . "&nbsp;
						<b>Fim:</b> " . implode('/', array_reverse(explode('-', $query->data_fim_benef))) . "<br/><br/></td></tr>";
            }

            if (isset($query->data_inicio_parlam) && strtotime($query->data_inicio_parlam) > 0) {
                $tabela .= "<tr><td colspan='3'><b>Proposta de Proponente de Emenda Parlamentar<br>Início:</b> " . implode('/', array_reverse(explode('-', $query->data_inicio_parlam))) . "&nbsp;
						<b>Fim:</b> " . implode('/', array_reverse(explode('-', $query->data_fim_parlam))) . "<br/><br/></td></tr>";
            }

            //<b>Inicio:</b> " . implode ( "/", array_reverse ( explode ( "-", $query->data_inicio ) ) ) . "</td>";
            //$tabela .= "<td><b>Fim:</b> " . implode ( "/", array_reverse ( explode ( "-", $query->data_fim ) ) ) . "</td>";
            $tabela .= "<tr><td colspan='3'></td></tr>";
            $tabela .= "<tr><td colspan='3'></td></tr>";
            $tabela .= "<tr><td colspan='3'></td></tr>";
            $tabela .= "<tr><td colspan='3'><b>PROGRAMA: </b></td></tr>";
            $programa = $query->nome;
            $programa = ucfirst(strtolower(trim($programa)));
            $programa = str_replace("À", "à", $programa);
            $programa = str_replace("Ã", "ã", $programa);
            $programa = str_replace("É", "é", $programa);
            $programa = str_replace("Ç", "ç", $programa);
            $programa = str_replace("Ê", "ê", $programa);
            $programa = str_replace("Ú", "ú", $programa);
            $tabela .= "<tr><td colspan=\"3\" style=\"text-align: justify; font-size: 12px;\"><span style='font-size: 14px;'>" . trim($programa) . "</span></td></tr>";
            $tabela .= "<tr><td colspan='3'></td></tr>";
            $tabela .= "<tr><td colspan='3'></td></tr>";

            $tabela .= "<tr><td colspan='3'><b>DESCRIÇÃO: </b></td></tr>";
            $tabela .= "<tr><td colspan=\"3\" style=\"text-align: justify; font-size: 12px;\"><span style='font-size: 14px;'>" . trim($query->descricao) . "</span></td></tr>";
            $tabela .= "<tr><td colspan='3'></td></tr>";
            $tabela .= "<tr><td colspan='3'></td></tr>";

            $tabela .= "<tr><td colspan='3'><b>OBJETO: </b></td></tr></table>";
            $tabela .= "<table style=\"font-size: 12px; page-break-inside: avoid;\">";
            $objeto = $query->objeto;
            $objeto = ucfirst(strtolower(trim($objeto)));
            $objeto = str_replace("À", "à", $objeto);
            $objeto = str_replace("Ã", "ã", $objeto);
            $objeto = str_replace("É", "é", $objeto);
            $objeto = str_replace("Ç", "ç", $objeto);
            $objeto = str_replace("Ê", "ê", $objeto);
            $objeto = str_replace("Ú", "ú", $objeto);
            $tabela .= "<tr><td style=\"text-align: justify; font-size: 12px;\"><span style='font-size: 10px;'>" . trim(str_replace("&nbsp;", "", $objeto)) . "</span></td></tr>";

            $tabela .= "<tr><td colspan='3'><b>OBSERVAÇÃO: </b></td></tr></table>";

            $tabela .= "<table style=\"font-size: 12px; page-break-inside: avoid;\">";
            $observacao = $query->observacao;
            $observacao = ucfirst(strtolower(trim($observacao)));
            $observacao = str_replace("À", "à", $observacao);
            $observacao = str_replace("Ã", "ã", $observacao);
            $observacao = str_replace("É", "é", $observacao);
            $observacao = str_replace("Ç", "ç", $observacao);
            $observacao = str_replace("Ê", "ê", $observacao);
            $observacao = str_replace("Ú", "ú", $observacao);
            $tabela .= "<tr><td style=\"text-align: justify; font-size: 12px;\"><span style='font-size: 10px;'>" . trim(str_replace("&nbsp;", "", $observacao)) . "</span></td></tr>";
            $tabela .= "</table>";

            $orgao = $query->orgao;

            $total++;

            if ($orgao == "" || $orgao == $resultados[$i + 1]->orgao) {
                
            } else {
                $tabela .= "<br /><b>TOTAL:</b> " . $total . " programa" . ($total > 1 ? "s" : "");
            }

            if ($key != count($resultados) - 1)
                $tabela .= '<div style="page-break-before:always">&nbsp;</div>';
            $i++;
            $mpdf->WriteHTML($tabela, 2);
        }
        //echo $tabela;

        $this->load->model('system_logs');
        $this->system_logs->add_log(GERACAO_PROGRAMAS_PDF . " - IDs: " . implode(", ", $this->input->post('ides', TRUE)));

        $mpdf->Output();
        /*
         * $pdf = new pdf_model(); $pdf->insereDados($programas); $pdf->SetXY(1,2,true); $pdf->AliasNbPages(); $pdf->SetFont('Times','',16);
         */
        // $pdf->Output();

        die();
    }

    public function envia_programas_email() {
        $data ['ides'] = $this->input->post('ides', TRUE); // pega todos os post
        $data['cnpj'] = $this->input->post('cnpj', TRUE);

        define("_MPDF_TEMP_PATH", BASEPATH . '../arquivos_proposta/tmp');

        $this->load->library('mpdf60/mpdf');

        $this->load->model('programa_model');
        $this->load->model('usuariomodel');

        ob_start(); // inicia o buffer
        $tabela = utf8_encode($tabela);

        $mpdf = new mPDF ();
        $mpdf->allow_charset_conversion = true;
        $mpdf->charset_in = 'UTF-8';

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

        $orgao = "";
        $resultados = $this->programa_model->get_programa_by_codigo($data['ides']);
        $total = 0;
        $i = 0;

        foreach ($resultados as $query) {

            if ($orgao == "" || $orgao != $query->orgao) {
                #Verificar para alinhar ao centro
                $tabela = "<div style='color:red; text-align:center;'>Relatório de Programas Abertos</div>";
                $tabela .= "<br /><br /><table style=\"font-size: 14px;\">";
                $mpdf->AddPage();
                $total = 0;
            } else {
                $tabela = "<br /><br /><table style=\"font-size: 14px;\">";
            }

            if (isset($data['cnpj']) && $data['cnpj'] != "") {
                $dados_emenda = $this->programa_model->get_dados_beneficiario($query->codigo, $data['cnpj'], true);
                if ($dados_emenda != null) {
                    $mostraTitulo = true;
                    foreach ($dados_emenda as $d) {
                        $tabela .= "<tr>";

                        if ($mostraTitulo)
                            $tabela .= "<td rowspan='" . count($dados_emenda) . "'><b>EMENDA(S): </b></td>";

                        $tabela .= "<td colspan='2' style=\"color:#428bca; width:700px;\">- " . $d->emenda_cnpj . " | " . $d->emenda_nome . " | " . $d->emenda_valor;

                        if (isset($d->emenda) && $d->emenda != "")
                            $tabela .= "<br/>&nbsp;<b>PARLAMENTAR: </b>" . $this->programa_model->get_parlamentar_by_emenda($d->emenda) . " | " . $d->emenda . "</td>";
                        else
                            $tabela .= "<br/>&nbsp;<b>Orgão: </b>" . $query->orgao_vinculado . "</td>";

                        $tabela .= "</tr>";

                        $mostraTitulo = false;
                    }
                }
            }

            $tabela .= "<tr><td></td></tr>";

            $tabela .= "<tr><td colspan='3'><b>ÓRGÃO SUPERIOR: </b></td></tr>";
            $tabela .= "<tr><td colspan=\"3\" style=\"color:#00008B;\">" . $query->orgao . "</td></tr>";
            $tabela .= "<tr><td colspan='3'></td></tr>";
            $tabela .= "<tr><td colspan='3'></td></tr>";

            $tabela .= "<tr><td colspan='3'><b>ÓRGÃO PROVENENTE: </b></td></tr>";
            $tabela .= "<tr><td colspan=\"3\">" . $query->orgao_vinculado . "</td></tr>";
            $tabela .= "<tr><td colspan='3'></td></tr>";
            $tabela .= "<tr><td colspan='3'></td></tr>";

            $tabela .= "<tr><td colspan='3'><b>CÓDIGO DO PROGRAMA:</b></td></tr>";
            $tabela .= "<tr><td colspan=\"3\">" . $query->codigo . "</td></tr>";
            $tabela .= "<tr><td colspan='3'></td></tr>";
            $tabela .= "<tr><td colspan='3'></td></tr>";

            $tabela .= "<tr><td colspan='3'><b>QUALIFICAÇÃO: </b> </td></tr>";
            $tabela .= "<tr><td colspan='3' style=\"text-align: justify;\">" . $query->qualificacao . "</td></tr>";
            $tabela .= "<tr><td colspan='3'></td></tr>";
            $tabela .= "<tr><td colspan='3'></td></tr>";

            $tabela .= "<tr><td colspan='3'><b>VIGÊNCIA: </b></td></tr>";

            if (isset($query->data_inicio) && strtotime($query->data_inicio) > 0) {
                $tabela .= "<tr><td colspan='3' style='width:300px;'><b>Proposta Voluntária<br>Início:</b> " . implode('/', array_reverse(explode('-', $query->data_inicio))) . "&nbsp;&nbsp;
						<b>Fim:</b> " . implode('/', array_reverse(explode('-', $query->data_fim))) . "<br/><br/></td></tr>";
            }

            if (isset($query->data_inicio_benef) && strtotime($query->data_inicio_benef) > 0) {
                $tabela .= "<tr><td colspan='3' style='width:300px;'><b>Proposta de Proponente Específico do Concedente<br>Início:</b> " . implode('/', array_reverse(explode('-', $query->data_inicio_benef))) . "&nbsp;
						<b>Fim:</b> " . implode('/', array_reverse(explode('-', $query->data_fim_benef))) . "<br/><br/></td></tr>";
            }

            if (isset($query->data_inicio_parlam) && strtotime($query->data_inicio_parlam) > 0) {
                $tabela .= "<tr><td colspan='3' style='width:300px;'><b>Proposta de Proponente de Emenda Parlamentar<br>Início:</b> " . implode('/', array_reverse(explode('-', $query->data_inicio_parlam))) . "&nbsp;
						<b>Fim:</b> " . implode('/', array_reverse(explode('-', $query->data_fim_parlam))) . "<br/><br/></td></tr>";
            }

            //<b>Inicio:</b> " . implode ( "/", array_reverse ( explode ( "-", $query->data_inicio ) ) ) . "</td>";
            //$tabela .= "<td><b>Fim:</b> " . implode ( "/", array_reverse ( explode ( "-", $query->data_fim ) ) ) . "</td>";
            $tabela .= "<tr><td colspan='3'></td></tr>";
            $tabela .= "<tr><td colspan='3'></td></tr>";
            $tabela .= "<tr><td colspan='3'></td></tr>";
            $tabela .= "<tr><td colspan='3'><b>PROGRAMA: </b></td></tr>";
            $tabela .= "<tr><td colspan=\"3\" style=\"text-align: justify;\">" . $query->nome . "</td></tr>";
            $tabela .= "<tr><td colspan='3'></td></tr>";
            $tabela .= "<tr><td colspan='3'></td></tr>";

            $tabela .= "<tr><td colspan='3'><b>DESCRIÇÃO: </b></td></tr>";
            $tabela .= "<tr><td colspan=\"3\" style=\"text-align: justify;\">" . $query->descricao . "</td></tr>";
            $tabela .= "<tr><td colspan='3'></td></tr>";
            $tabela .= "<tr><td colspan='3'></td></tr>";

            $tabela .= "<tr><td colspan='3'><b>OBJETO: </b></td></tr>";
            $tabela .= "<tr><td colspan=\"3\" style=\"text-align: justify;\">" . $query->objeto . "</td></tr>";

            $tabela .= "<tr><td colspan='3'><b>OBSERVAÇÃO: </b></td></tr></table>";

            $tabela .= "<table style=\"font-size: 14px; page-break-inside: avoid;\">";
            $tabela .= "<tr><td style=\"text-align: justify;\">" . trim(str_replace("&nbsp;", "", $query->observacao)) . "</td></tr>";
            $tabela .= "</table>";

            $tabela .= "<table style=\"font-size: 14px; page-break-inside: avoid;\">";
            $tabela .= "<tr><td colspan='3'></td></tr>";
            $tabela .= "<tr><td colspan='3'></td></tr>";

            $tabela .= "<tr><td colspan='3'><b>INDICADO PARA: </b></td></tr>";
            $tabela .= "<tr><td colspan=\"3\" style=\"text-align: justify;\">" . $query->atende . "</td></tr>";
            $tabela .= "<tr><td colspan='3'></td></tr>";
            $tabela .= "<tr><td colspan='3'></td></tr>";

            if (isset($query->tem_chamamento) && $query->tem_chamamento == 1) {
                $tabela .= "<tr><td colspan='3'><b>CHAMAMENTO: </b>Sim</td></tr>";
            } else {
                $tabela .= "<tr><td colspan='3'><b>CHAMAMENTO: </b>Não</td></tr>";
            }

            if (isset($query->regra_contrapartida)) {
                $tabela .= "<tr><td colspan='3'></td></tr>";
                $tabela .= "<tr><td colspan='3'></td></tr>";
                $tabela .= "<tr><td colspan='3'><b>REGRA CONTRAPARTIDA: </b></td></tr>";
                $tabela .= "<tr><td align=\"center\" colspan=\"3\" style=\"text-align: center;\">" . str_replace("td", "td style=\"text-align:center\"", str_replace("tr class=\"even\"", "tr class=\"even\" style=\"text-align:center\"", str_replace("tr class=\"odd\"", "tr class=\"odd\" style=\"text-align:center\"", $query->regra_contrapartida))) . "</td></tr>";
            }
            $tabela .= "<tr><td colspan='3'></td></tr>";
            $tabela .= "<tr><td colspan='3'></td></tr>";

            $tabela .= "</table><br> <font style=\"font-size: 14px; text-align: justify;\">";
            $tabela .= "<b>Obs.: </b> " . $query->observacao . "</font><br>";




            $orgao = $query->orgao;

            $total++;

            if ($orgao == "" || $orgao == $resultados[$i + 1]->orgao) {
                
            } else
                $tabela .= "<br/><b>TOTAL:</b> " . $total . " programa" . ($total > 1 ? "s" : "");

            $i++;
            $mpdf->WriteHTML($tabela);
        }

        $nomeArquivo = $this->session->userdata('id_usuario') . "_" . rand(1111, 99999) . "_listaProgramas.pdf";

        $mpdf->Output(BASEPATH . '../arquivos_proposta/' . $nomeArquivo, 'F');

        $mailto = trim($this->input->post('email', TRUE), ",");
        $from_name = 'Physis Brasil';
        $from_mail = 'physisbrasil@gmail.com';
        $subject = $this->input->post('assunto', TRUE);
        $message = $this->input->post('mensagem', TRUE);
        $filename = $nomeArquivo;

//        $this->load->library('email');
//        $this->email->initialize($this->usuariomodel->inicializa_config_email($from_mail));
//        $this->email->set_mailtype('html');

        $this->load->library('email', $this->usuariomodel->inicializa_config_email_gmail("physisbrasil@gmail.com"));
        $this->email->set_newline("\r\n");

        $this->email->from($from_mail, $from_name);
        $this->email->to($mailto);

        $this->email->subject($subject);
        $this->email->message($message);
        $this->email->attach(BASEPATH . '../arquivos_proposta/' . $nomeArquivo);
        $this->email->send();

        echo "<script type='text/javascript'>alert('Email enviado com sucesso');</script>";
        echo "<script type='text/javascript'>window.close();</script>";

        die();
    }

    public function visualiza_propostas_pareceres() {
        $this->session->set_userdata('pagAtual', 'visualiza_propostas_pareceres');

        $this->load->model('banco_proposta_model');
        $this->load->model('usuario_cnpj');
        $this->load->model('cnpj_siconv');
        $this->load->model('programa_model');

        if ($this->input->post())
            $this->session->set_userdata('filtros', $this->input->post());

        $data['programa_model'] = $this->programa_model;
        $data['cnpj_siconv'] = $this->cnpj_siconv;
        if ($this->session->userdata('nivel') == 6 || $this->session->userdata('nivel') == 7 || $this->session->userdata('nivel') == 8)
            $data['num_cnpj'] = count($this->usuario_cnpj->get_all_by_subgestor($id_usuario));
        else
            $data['num_cnpj'] = count($this->usuario_cnpj->get_all_by_usuario($this->session->userdata('id_usuario'), 'LEFT'));
        $data['banco_proposta_model'] = $this->banco_proposta_model;
        $data['anos'] = $this->banco_proposta_model->get_anos_by_usuario();
        $data['dados_propostas'] = $this->banco_proposta_model->busca_programas_pareceres($this->session->userdata('filtros'));
        $data['main'] = 'in/propostas_pareceres';
        if ($this->session->userdata('gp') == true || $this->input->get('gp', TRUE) != false) {
            $data['title'] = 'G&P - Propostas e Pareceres';
        } else {
            $data['title'] = 'Physis - Propostas e Pareceres';
        }
        $data['parlamentares'] = $this->db->get('nomes_parlamentar')->result();

        $this->load->view('in/template', $data);
    }

    public function visualiza_propostas_por_objeto() {
        $this->session->set_userdata('pagAtual', 'visualiza_propostas_objeto');

        $this->load->model('banco_proposta_model');
        $this->load->model('usuario_cnpj');
        $this->load->model('cnpj_siconv');
        $this->load->model('programa_model');

        if ($this->input->post()) {
            $this->session->set_userdata('filtros', $this->input->post());
        }

        //print_r($this->input->post('estado'));die;

        $data['programa_model'] = $this->programa_model;
        $data['cnpj_siconv'] = $this->cnpj_siconv;
        if ($this->session->userdata('nivel') == 6 || $this->session->userdata('nivel') == 7 || $this->session->userdata('nivel') == 8)
            $data['num_cnpj'] = count($this->usuario_cnpj->get_all_by_subgestor($id_usuario));
        else
            $data['num_cnpj'] = count($this->usuario_cnpj->get_all_by_usuario($this->session->userdata('id_usuario'), 'LEFT'));
        $data['banco_proposta_model'] = $this->banco_proposta_model;
        $data['anos'] = $this->banco_proposta_model->get_anos_by_usuario();
        if ($this->session->userdata('nivel') == 12 || $this->session->userdata('nivel') == 13 || $this->session->userdata('nivel') == 14) {
            if ($this->session->userdata('filtros')['regiao'] != 'TODOS') {
                //$data['estados'] = 
            }
            $regioes = $this->verifica_regiao();
            $esferas = $this->get_esferas('TODOS');
            $data['esferas'] = $esferas;
            $data['regioes'] = $regioes;
            $data['main'] = 'in/propostas_objeto_gestor_execucao';
        } else {
            $data['main'] = 'in/propostas_objeto';
        }
        $data['dados_propostas'] = $this->banco_proposta_model->busca_programas_objeto($this->session->userdata('filtros'));
        if ($this->session->userdata('gp') == true || $this->input->get('gp', TRUE) != false) {
            $data['title'] = 'G&P - Propostas por Objeto';
        } else {
            $data['title'] = 'Physis - Propostas por Objeto';
        }
        $data['parlamentares'] = $this->db->get('nomes_parlamentar')->result();
        $this->load->view('in/template', $data);
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
        if (!empty($listaEsferas)) {
            $options = "";
            foreach ($listaEsferas as $value) {
                $options .= "<option value='{$value}'";
                if ($value == 'MUNICIPAL')
                    $options .= ">Governo Municipal</option>";
                elseif ($value == 'ESTADUAL')
                    $options .= ">Governo Estadual</option>";
                elseif ($value == 'CONSORCIO PUBLICO')
                    $options .= ">Consórcio Público</option>";
                elseif ($value == 'PRIVADA')
                    $options .= ">Organização da Sociedade Civil</option>";
                elseif ($value == 'EMPRESA PUBLICA SOCIEDADE ECONOMIA MISTA')
                    $options .= ">Empresas Públicas/Economia Mista</option>";
                elseif ($value == 'FEDERAL')
                    $options .= ">Governo Federal</option>";
                elseif ($value == 'ORGANISMO INTERNACIONAL')
                    $options .= ">Organismo Internacional</option>";
            }
        }

        if ($this->input->post() == FALSE || isset($_POST['pesquisa']))
            return $options;
        else
            echo $options;
    }

    public function gerapdf_detalhe_proposta() {
        $this->load->model('banco_proposta_model');
        $dados_proposta = $this->banco_proposta_model->get_by_id($this->input->get('id', TRUE));

        $this->load->library('mPDF');

        ob_start(); // inicia o buffer
        $tabela = utf8_encode($tabela);

        $mpdf = new mPDF();
        $mpdf->allow_charset_conversion = true;
        $mpdf->charset_in = 'UTF-8';
        #Verificar para aumentar o tamanho da entidade
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

        $tabela .= "<div style='font-size:10px;'><h1>Detalhes Proposta " . $dados_proposta->codigo_siconv . "</h1>

					<b>Objeto:</b> <br>" . $dados_proposta->objeto . "<br/><br/>
					<b>Justificativa:</b> <br><div style='text-align:justify;'>" . $dados_proposta->justificativa . "</div><br/><br/>
					<b>Modalidade:</b> " . $dados_proposta->modalidade . "<br/><br/>
					<b>Orgão:</b> " . $dados_proposta->orgao . "<br/><br/>
					<b>Programa:</b> " . $dados_proposta->codigo_programa . " - " . $dados_proposta->nome_programa . "<br/><br/>
					<b>Início:</b> " . implode('/', array_reverse(explode('-', $dados_proposta->data_inicio))) . "<br/><br/>
					<b>Fim:</b> " . implode('/', array_reverse(explode('-', $dados_proposta->data_fim))) . "<br/><br/>
					<b>Valor Global:</b> " . str_replace("R$ ", "", $dados_proposta->valor_global) . "<br/><br/>
					<b>Repasse:</b> " . str_replace("R$ ", "", $dados_proposta->valor_repasse) . "<br/><br/>
					<b>Contrapartida Financeira:</b> " . str_replace("R$ ", "", $dados_proposta->valor_contrapartida_financeira) . "<br/><br/>
					<b>Contrapartida Bens:</b> " . str_replace("R$ ", "", $dados_proposta->valor_contrapartida_bens) . "<br/><br/>
					<b>Situação:</b> " . $dados_proposta->situacao . "<br/><br/>
					<b>Convênio:</b> " . $dados_proposta->convenio . "<br/><br/>";

        $pareceres = $this->banco_proposta_model->get_all_parecer_proposta($dados_proposta->id_siconv);

        if (!empty($pareceres)) {
            $tabela .= "<div style='text-align:center;'><h1><b>Lista de Pareceres Proposta</b></h1></div>";
            foreach ($pareceres as $parecer) {
                $tabela .= "<b>" . implode("/", array_reverse(explode("-", $parecer->data_parecer))) . "</b> - " . $parecer->parecer . "<br><br>";
            }
        }

        $pareceres_plano_trabalho = $this->banco_proposta_model->get_all_parecer_plano_trabalho($dados_proposta->id_siconv);

        if (!empty($pareceres_plano_trabalho)) {
            $tabela .= "<div style='text-align:center;'><h1><b>Lista de Pareceres Plano Trabalho</b></h1></div>";
            foreach ($pareceres_plano_trabalho as $parecer) {
                $tabela .= "<b>" . implode("/", array_reverse(explode("-", $parecer->data_parecer))) . "</b> - " . $parecer->parecer . "<br><br>";
            }
        }

        $pareceres_ajuste_plano_trabalho = $this->banco_proposta_model->get_all_parecer_ajuste_plano_trabalho($dados_proposta->id_siconv);

        if (!empty($pareceres_ajuste_plano_trabalho)) {
            $tabela .= "<div style='text-align:center;'><h1><b>Lista de Pareceres Ajuste Plano Trabalho</b></h1></div>";
            foreach ($pareceres_ajuste_plano_trabalho as $parecer) {
                $tabela .= "<b>" . implode("/", array_reverse(explode("-", $parecer->data_parecer))) . "</b> - " . $parecer->parecer . "<br><br>";
            }
        }

        $tabela .= "</div>";

        $mpdf->WriteHTML($tabela);

        $mpdf->Output();

        die();
    }

    public function gerapdf_lista_propostas() {
        ini_set('memory_limit', -1);

        $this->load->model('banco_proposta_model');
        $this->load->model('cnpj_siconv');
        $this->load->model('usuario_cnpj');
        $this->load->model('programa_model');
        $this->load->library('mPDF');

        ob_start(); // inicia o buffer
        $tabela = utf8_encode($tabela);

        $mpdf = new mPDF('', 'A4-L');
        $mpdf->allow_charset_conversion = true;
        $mpdf->charset_in = 'UTF-8';
        #Verificar para aumentar o tamanho da entidade
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

        $data ['ides'] = $this->input->post('ides', TRUE);

        $ano = "";
        $i = 0;
        $total = 0;
        $totalValorGlobal = 0;
        $totalValorRepasse = 0;
        $totalValorContrapartida = 0;
        $mostraCabecalho = true;

        $totalAprv = 0;
        $totalValorGlobalAprv = 0;
        $totalValorRepasseAprv = 0;
        $totalValorContrapartidaAprv = 0;

        $totalEnviado = 0;
        $totalValorGlobalEnviado = 0;
        $totalValorRepasseEnviado = 0;
        $totalValorContrapartidaEnviado = 0;

        $totalCadastrado = 0;
        $totalValorGlobalCadastrado = 0;
        $totalValorRepasseCadastrado = 0;
        $totalValorContrapartidaCadastrado = 0;

        $totalEnviadoGeral = 0;
        $totalValorGlobalEnviadoGeral = 0;
        $totalValorRepasseEnviadoGeral = 0;
        $totalValorContrapartidaEnviadoGeral = 0;

        $totalGeral = 0;
        $totalValorGlobalGeral = 0;
        $totalValorRepasseGeral = 0;
        $totalValorContrapartidaGeral = 0;

        $totalAprvGeral = 0;
        $totalValorGlobalAprvGeral = 0;
        $totalValorRepasseAprvGeral = 0;
        $totalValorContrapartidaAprvGeral = 0;

        $totalCadastradoGeral = 0;
        $totalValorGlobalCadastradoGeral = 0;
        $totalValorRepasseCadastradoGeral = 0;
        $totalValorContrapartidaCadastradoGeral = 0;

        $indiceProposta = 1;

        $dados_propostas = $this->banco_proposta_model->get_dados_by_ids($data ['ides']);
        if ($this->session->userdata('nivel') == 6 || $this->session->userdata('nivel') == 7 || $this->session->userdata('nivel') == 8)
            $num_cnpj = count($this->usuario_cnpj->get_all_by_subgestor($id_usuario));
        else
            $num_cnpj = count($this->usuario_cnpj->get_all_by_usuario($this->session->userdata('id_usuario'), 'LEFT'));

        foreach ($dados_propostas as $propostas) {

            if ($ano == "" || $ano != $propostas->ano) {
                $tabela = "<div style='color:red; text-align:center;'>Relatório de Propostas SICONV - Ano {$propostas->ano}</div>";
                $tabela .= "<br /><br /><table style=\"font-size: 13px; border-collapse: collapse;\">";
                $mpdf->AddPage();
                $total = 0;
                $mostraCabecalho = true;
                $total = 0;
                $totalValorGlobal = 0;
                $totalValorRepasse = 0;
                $totalValorContrapartida = 0;

                $totalAprv = 0;
                $totalValorGlobalAprv = 0;
                $totalValorRepasseAprv = 0;
                $totalValorContrapartidaAprv = 0;

                $totalEnviado = 0;
                $totalValorGlobalEnviado = 0;
                $totalValorRepasseEnviado = 0;
                $totalValorContrapartidaEnviado = 0;

                $totalCadastrado = 0;
                $totalValorGlobalCadastrado = 0;
                $totalValorRepasseCadastrado = 0;
                $totalValorContrapartidaCadastrado = 0;

                $indiceProposta = 1;
            }

            if ($mostraCabecalho) {
                $tabela .= "<tr>
                				<th style='border: 1px solid;'></th>
								<th style='border: 1px solid;'>Orgão Superior</th>
								<th style='border: 1px solid;'>Objeto</th>";
                if ($num_cnpj > 1):
                    $tabela .= "<th style='border: 1px solid;'>Instituição</th>";
                endif;
                $tabela .= "<th style='border: 1px solid;'>Nº Proposta</th>
								<th style='border: 1px solid;'>Convênio</th>
								<th style='border: 1px solid;'>Situação</th>
								<th style='border: 1px solid;'>Final Vigência</th>
								<th style='border: 1px solid;'>Valor Global</th>
								<th style='border: 1px solid;'>Valor Repasse</th>
								<th style='border: 1px solid;'>Contrapartida</th>
							</tr>";
                $mostraCabecalho = false;
            }

            $valorContrapartida = str_replace(",", ".", str_replace(".", "", str_replace("R$ ", "", $propostas->valor_contrapartida_financeira))) + str_replace(",", ".", str_replace(".", "", str_replace("R$ ", "", $propostas->valor_contrapartida_bens)));
            $tabela .= "<tr>
            				<td style='border: 1px solid; font-size:12px;'>" . $indiceProposta . "</td>
							<td style='border: 1px solid; font-size:12px;'>" . $propostas->orgao . "</td>
							<td style='border: 1px solid; font-size:12px;'>" . $propostas->objeto . "</td>";
            if ($num_cnpj > 1):
                $tabela .= "<td style='border: 1px solid; font-size:12px;'>" . $this->programa_model->formatCPFCNPJ($propostas->proponente) . " - " . $this->cnpj_siconv->get_nome_by_cnpj($propostas->proponente) . "</td>";
            endif;
            $tabela .= "<td style='border: 1px solid; text-align:center; font-size:12px;'>" . $propostas->codigo_siconv . "</td>
							<td style='border: 1px solid; font-size:12px;'>" . $propostas->convenio . "</td>
							<td style='border: 1px solid; font-size:12px;'>" . $propostas->situacao . "</td>
							<td style='border: 1px solid; text-align:center; font-size:12px;'>" . implode("/", array_reverse(explode("-", $propostas->data_fim))) . "</td>
							<td style='border: 1px solid; text-align:center; font-size:12px;'>" . str_replace("R$ ", "", $propostas->valor_global) . "</td>
							<td style='border: 1px solid; text-align:center; font-size:12px;'>" . str_replace("R$ ", "", $propostas->valor_repasse) . "</td>
							<td style='border: 1px solid; text-align:center; font-size:12px;'>" . number_format($valorContrapartida, 2, ",", ".") . "</td>
						</tr>";

            $totalValorGlobal += str_replace(",", ".", str_replace(".", "", str_replace("R$ ", "", $propostas->valor_global)));
            $totalValorRepasse += str_replace(",", ".", str_replace(".", "", str_replace("R$ ", "", $propostas->valor_repasse)));
            $totalValorContrapartida += $valorContrapartida;


            $ano = $propostas->ano;

            $total++;

            if ($this->banco_proposta_model->verifica_proposta_aprovada($propostas->situacao)) {
                $totalAprv++;
                $totalValorGlobalAprv += str_replace(",", ".", str_replace(".", "", str_replace("R$ ", "", $propostas->valor_global)));
                $totalValorRepasseAprv += str_replace(",", ".", str_replace(".", "", str_replace("R$ ", "", $propostas->valor_repasse)));
                $totalValorContrapartidaAprv += $valorContrapartida;

                $totalAprvGeral++;
            }

            if ($this->banco_proposta_model->verifica_proposta_enviada($propostas->situacao)) {
                $totalEnviado++;
                $totalValorGlobalEnviado += str_replace(",", ".", str_replace(".", "", str_replace("R$ ", "", $propostas->valor_global)));
                $totalValorRepasseEnviado += str_replace(",", ".", str_replace(".", "", str_replace("R$ ", "", $propostas->valor_repasse)));
                $totalValorContrapartidaEnviado += $valorContrapartida;

                $totalEnviadoGeral++;
            }

            if ($this->banco_proposta_model->verifica_proposta_cadastrado($propostas->situacao)) {
                $totalCadastrado++;
                $totalValorGlobalCadastrado += str_replace(",", ".", str_replace(".", "", str_replace("R$ ", "", $propostas->valor_global)));
                $totalValorRepasseCadastrado += str_replace(",", ".", str_replace(".", "", str_replace("R$ ", "", $propostas->valor_repasse)));
                $totalValorContrapartidaCadastrado += $valorContrapartida;

                $totalCadastradoGeral++;
            }

            $totalGeral++;

            if ($ano == "" || $ano == $dados_propostas[$i + 1]->ano) {
                
            } else {
                $totalValorGlobalGeral += $totalValorGlobal;
                $totalValorRepasseGeral += $totalValorRepasse;
                $totalValorContrapartidaGeral += $totalValorContrapartida;

                $totalValorGlobalAprvGeral += $totalValorGlobalAprv;
                $totalValorRepasseAprvGeral += $totalValorRepasseAprv;
                $totalValorContrapartidaAprvGeral += $totalValorContrapartidaAprv;

                $totalValorGlobalEnviadoGeral += $totalValorGlobalEnviado;
                $totalValorRepasseEnviadoGeral += $totalValorRepasseEnviado;
                $totalValorContrapartidaEnviadoGeral += $totalValorContrapartidaEnviado;

                $totalValorGlobalCadastradoGeral += $totalValorGlobalCadastrado;
                $totalValorRepasseCadastradoGeral += $totalValorRepasseCadastrado;
                $totalValorContrapartidaCadastradoGeral += $totalValorContrapartidaCadastrado;

                $tabela .= "<tr><td colspan='10'>&nbsp;</td></tr>";
                $tabela .= "<tr>
								<td colspan='3' style='border: 1px solid;'><b>Total de Propostas</b> {$total}</td>";

                if ($num_cnpj > 1):
                    $tabela .= "<td></td>";
                endif;

                $tabela .= "<td></td>
								<td></td>
								<td colspan='2' style='border: 1px solid; text-align:right;'><b>Total Propostas Ano</b></td>
								<td style='border: 1px solid; text-align:center;'>" . number_format($totalValorGlobal, 2, ",", ".") . "</td>
								<td style='border: 1px solid; text-align:center;'>" . number_format($totalValorRepasse, 2, ",", ".") . "</td>
								<td style='border: 1px solid; text-align:center;'>" . number_format($totalValorContrapartida, 2, ",", ".") . "</td>
							</tr>";
                $total2 = $total - $totalEnviado - $totalAprv;

                if ($this->input->post('statusProp', TRUE) == FALSE || $this->input->post('statusProp', TRUE) == "" || ($this->input->post('statusProp', TRUE) == TRUE && $this->input->post('statusProp', TRUE) == "1")) {
                    $tabela .= "<tr>
									<td colspan='3' style='border: 1px solid; color:red;'><b>Cadastradas e não Enviadas</b> {$totalCadastrado}</td>";

                    if ($num_cnpj > 1):
                        $tabela .= "<td></td>";
                    endif;

                    $tabela .= "<td></td>
									<td></td>
									<td colspan='2' style='border: 1px solid; text-align:right; color:red;'><b>Total Cadastrado Ano</b></td>
									<td style='border: 1px solid; text-align:center; color:red;'>" . number_format($totalValorGlobalCadastrado, 2, ",", ".") . "</td>
									<td style='border: 1px solid; text-align:center; color:red;'>" . number_format($totalValorRepasseCadastrado, 2, ",", ".") . "</td>
									<td style='border: 1px solid; text-align:center; color:red;'>" . number_format($totalValorContrapartidaCadastrado, 2, ",", ".") . "</td>
								</tr>";
                }


                if ($this->input->post('statusProp', TRUE) == FALSE || $this->input->post('statusProp', TRUE) == "" || ($this->input->post('statusProp', TRUE) == TRUE && $this->input->post('statusProp', TRUE) == "2")) {
                    $tabela .= "<tr>
									<td colspan='3' style='border: 1px solid; color:#428bca;'><b>Enviadas para Análise</b> {$totalEnviado}</td>";

                    if ($num_cnpj > 1):
                        $tabela .= "<td></td>";
                    endif;

                    $tabela .= "<td></td>
									<td></td>
									<td colspan='2' style='border: 1px solid; text-align:right; color:red;'><b>Total Enviadas Análise Ano</b></td>
									<td style='border: 1px solid; text-align:center; color:red;'>" . number_format($totalValorGlobalEnviado, 2, ",", ".") . "</td>
									<td style='border: 1px solid; text-align:center; color:red;'>" . number_format($totalValorRepasseEnviado, 2, ",", ".") . "</td>
									<td style='border: 1px solid; text-align:center; color:red;'>" . number_format($totalValorContrapartidaEnviado, 2, ",", ".") . "</td>
								</tr>";
                }

                if ($this->input->post('statusProp', TRUE) == FALSE || $this->input->post('statusProp', TRUE) == "" || ($this->input->post('statusProp', TRUE) == TRUE && $this->input->post('statusProp', TRUE) == "3")) {
                    $tabela .= "<tr style='margin:20px;'>
									<td colspan='3' style='border: 1px solid; color:green;'><b>Enviadas e Aprovadas</b> {$totalAprv}</td>";

                    if ($num_cnpj > 1):
                        $tabela .= "<td></td>";
                    endif;

                    $tabela .= "<td></td>
									<td></td>
									<td colspan='2' style='border: 1px solid; text-align:right; color:red;'><b>Total Aprovado</b></td>
									<td style='border: 1px solid; text-align:center; color:red;'>" . number_format($totalValorGlobalAprv, 2, ",", ".") . "</td>
									<td style='border: 1px solid; text-align:center; color:red;'>" . number_format($totalValorRepasseAprv, 2, ",", ".") . "</td>
									<td style='border: 1px solid; text-align:center; color:red;'>" . number_format($totalValorContrapartidaAprv, 2, ",", ".") . "</td>
								</tr>";
                }

                if (!isset($dados_propostas[$i + 1]->ano)) {
                    $tabela .= "<tr><td colspan='10'>&nbsp;</td></tr>";
                    $tabela .= "<tr><td colspan='10' style='font-size: 14px;'><b>DADOS GERAIS</b></td></tr>";
                    $tabela .= "<tr>
									<td colspan='3' style='border: 1px solid;'><b>Total Geral Propostas</b> {$totalGeral}</td>";

                    if ($num_cnpj > 1):
                        $tabela .= "<td></td>";
                    endif;

                    $tabela .= "<td></td>
									<td></td>
									<td colspan='2' style='border: 1px solid; text-align:right;'><b>Total Geral Proposta Ano:</b></td>
									<td style='border: 1px solid; text-align:center;'>" . number_format($totalValorGlobalGeral, 2, ",", ".") . "</td>
									<td style='border: 1px solid; text-align:center;'>" . number_format($totalValorRepasseGeral, 2, ",", ".") . "</td>
									<td style='border: 1px solid; text-align:center;'>" . number_format($totalValorContrapartidaGeral, 2, ",", ".") . "</td>
								</tr>";
                    $totalGeral2 = $totalGeral - $totalEnviadoGeral - $totalAprvGeral;

                    if ($this->input->post('statusProp', TRUE) == FALSE || $this->input->post('statusProp', TRUE) == "" || ($this->input->post('statusProp', TRUE) == TRUE && $this->input->post('statusProp', TRUE) == "1")) {
                        $tabela .= "<tr>
										<td colspan='3' style='border: 1px solid; color:red;'><b>Total Geral Cadastradas e não Enviadas</b> {$totalCadastradoGeral}</td>";

                        if ($num_cnpj > 1):
                            $tabela .= "<td></td>";
                        endif;

                        $tabela .= "<td></td>
										<td></td>
										<td colspan='2' style='border: 1px solid; text-align:right; color:red;'><b>Total Geral Cadastrado Ano:</b></td>
										<td style='border: 1px solid; text-align:center; color:red;'>" . number_format($totalValorGlobalCadastradoGeral, 2, ",", ".") . "</td>
										<td style='border: 1px solid; text-align:center; color:red;'>" . number_format($totalValorRepasseCadastradoGeral, 2, ",", ".") . "</td>
										<td style='border: 1px solid; text-align:center; color:red;'>" . number_format($totalValorContrapartidaCadastradoGeral, 2, ",", ".") . "</td>
									</tr>";
                    }

                    if ($this->input->post('statusProp', TRUE) == FALSE || $this->input->post('statusProp', TRUE) == "" || ($this->input->post('statusProp', TRUE) == TRUE && $this->input->post('statusProp', TRUE) == "2")) {
                        $tabela .= "<tr>
										<td colspan='3' style='border: 1px solid; color:#428bca;'><b>Total Geral Enviadas para Análise</b> {$totalEnviadoGeral}</td>";

                        if ($num_cnpj > 1):
                            $tabela .= "<td></td>";
                        endif;

                        $tabela .= "<td></td>
										<td></td>
										<td colspan='2' style='border: 1px solid; text-align:right; color:#428bca;'><b>Total Geral Em Análise Ano:</b></td>
										<td style='border: 1px solid; text-align:center; color:#428bca;'>" . number_format($totalValorGlobalEnviadoGeral, 2, ",", ".") . "</td>
										<td style='border: 1px solid; text-align:center; color:#428bca;'>" . number_format($totalValorRepasseEnviadoGeral, 2, ",", ".") . "</td>
										<td style='border: 1px solid; text-align:center; color:#428bca;'>" . number_format($totalValorContrapartidaEnviadoGeral, 2, ",", ".") . "</td>
									</tr>";
                    }

                    if ($this->input->post('statusProp', TRUE) == FALSE || $this->input->post('statusProp', TRUE) == "" || ($this->input->post('statusProp', TRUE) == TRUE && $this->input->post('statusProp', TRUE) == "3")) {
                        $tabela .= "<tr style='margin:20px;'>
										<td colspan='3' style='border: 1px solid; color:green;'><b>Total Geral Enviadas e Aprovadas:</b> {$totalAprvGeral}</td>";

                        if ($num_cnpj > 1):
                            $tabela .= "<td></td>";
                        endif;

                        $tabela .= "<td></td>
										<td></td>
										<td colspan='2' style='border: 1px solid; text-align:right; color:green;'><b>Total Geral Aprovado:</b></td>
										<td style='border: 1px solid; text-align:center; color:green;'>" . number_format($totalValorGlobalAprvGeral, 2, ",", ".") . "</td>
										<td style='border: 1px solid; text-align:center; color:green;'>" . number_format($totalValorRepasseAprvGeral, 2, ",", ".") . "</td>
										<td style='border: 1px solid; text-align:center; color:green;'>" . number_format($totalValorContrapartidaAprvGeral, 2, ",", ".") . "</td>
									</tr>";
                    }
                }

                $tabela .= "</table>";

                //echo $tabela;
                $mpdf->WriteHTML($tabela);
            }

            $indiceProposta++;

            $i++;
        }

        $mpdf->Output();

        die();
    }

    public function gerapdf_lista_propostas_objeto() {
        ini_set('memory_limit', -1);

        $this->load->model('banco_proposta_model');
        $this->load->model('cnpj_siconv');
        $this->load->model('usuario_cnpj');
        $this->load->model('programa_model');
        session_start();
        $this->load->library('mpdf60/mpdf.php');
        $mpdf = new mPDF('', 'A4-L');
        ob_start(); // inicia o buffer
        $mpdf->allow_charset_conversion = true;
        $mpdf->charset_in = 'UTF-8';
        $mpdf->margin_bottom_collapse = true;
        #Verificar para aumentar o tamanho da entidade
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

        $tabela = utf8_encode($tabela);
        $mpdf->SetHeader($header, 'O');
        $mpdf->SetFooter('{DATE d/m/Y}||{PAGENO}/{nb}');

        $data ['ides'] = $this->input->post('ides', TRUE);

        $indiceProposta = 1;

        $dados_propostas = $this->banco_proposta_model->get_dados_by_ids($data ['ides']);
        if ($this->session->userdata('nivel') == 6 || $this->session->userdata('nivel') == 7 || $this->session->userdata('nivel') == 8)
            $num_cnpj = count($this->usuario_cnpj->get_all_by_subgestor($id_usuario));
        else
            $num_cnpj = count($this->usuario_cnpj->get_all_by_usuario($this->session->userdata('id_usuario'), 'LEFT'));
        $tabela .= "<div style='color:red; text-align:center;'>Relatório de Propostas SICONV</div>";
        $tabela .= "<br /><br /><table style=\"font-size: 13px; border-collapse: collapse; width: 100%;\">";
        $tabela .= "<tr>
                <th style='border: 1px solid;'></th>
                <th style='border: 1px solid;'>Objeto</th>
                <th style='border: 1px solid;'>Nº Proposta</th>
                <th style='border: 1px solid;'>Valor Global</th>
            </tr>";


        foreach ($dados_propostas as $propostas) {
            $tabela .= "<tr>
            				<td style='border: 1px solid; font-size:12px;'>" . $indiceProposta . "</td>
					<td style='border: 1px solid; font-size:12px;'>" . $propostas->objeto . "</td>";
            $tabela .= "<td style='border: 1px solid; font-size:12px;'>" . $propostas->codigo_siconv . "</td>
							<td style='border: 1px solid; font-size:12px;'>" . str_replace("R$ ", "", $propostas->valor_global) . "</td>
						</tr>";
            $indiceProposta++;
        }
        $tabela .= "</table>";

        $mpdf->WriteHTML($tabela);
        $mpdf->Output();

        exit;
    }

    public function detalha_propostas_pareceres() {
        $this->load->model('banco_proposta_model');
        $this->load->model('empenhos_model');

        if ($this->input->get('id', TRUE) != false && $this->input->get('idProposta', TRUE) != false && $this->input->get('idParecer', TRUE) != false) {
            if ($this->input->get('tipo', TRUE) == "plano_trabalho") {
                $parecer = $this->banco_proposta_model->get_parecer_plano_trabalho($this->input->get('idProposta', TRUE), $this->input->get('idParecer', TRUE));

                $this->banco_proposta_model->atualiza_data_visualizado("parecer_plano_trabalho_banco_proposta", array('visualizado_em' => date('Y-m-d')), "id_proposta = " . $this->input->get('idProposta', TRUE) . " AND id_parecer = " . $this->input->get('idParecer', TRUE));

                echo $parecer->parecer . ($parecer->tem_anexo ? "<br><br><a style='color:red;' target='_blank' href='https://www.convenios.gov.br/siconv/DetalharParecerProposta/ParecerPropostaVisualizarParecerPlanoTrabalho.do?path=/MostraPrincipalConsultarPrograma.do&Usr=guest&Pwd=guest&idProposta=" . $parecer->id_proposta . "&idParecer=" . $parecer->id_parecer . "'>Visualizar Anexo</a>" : "");
            } else if ($this->input->get('tipo', TRUE) == "ajuste_plano_trabalho") {
                $parecer = $this->banco_proposta_model->get_parecer_ajuste_plano_trabalho($this->input->get('idProposta', TRUE), $this->input->get('idParecer', TRUE));

                $this->banco_proposta_model->atualiza_data_visualizado("parecer_ajuste_pl_trabalho_banco_proposta", array('visualizado_em' => date('Y-m-d')), "id_proposta = " . $this->input->get('idProposta', TRUE) . " AND id_parecer = " . $this->input->get('idParecer', TRUE));

                echo $parecer->parecer . ($parecer->tem_anexo ? "<br><br><a style='color:red;' target='_blank' href='https://www.convenios.gov.br/siconv/DetalharParecerProposta/ParecerPropostaVisualizarParecerPlanoTrabalho.do?path=/MostraPrincipalConsultarPrograma.do&Usr=guest&Pwd=guest&idProposta=" . $parecer->id_proposta . "&idParecer=" . $parecer->id_parecer . "'>Visualizar Anexo</a>" : "");
            } else {
                $parecer = $this->banco_proposta_model->get_parecer_proposta($this->input->get('idProposta', TRUE), $this->input->get('idParecer', TRUE));

                $this->banco_proposta_model->atualiza_data_visualizado("parecer_proposta_banco_proposta", array('visualizado_em' => date('Y-m-d')), "id_proposta = " . $this->input->get('idProposta', TRUE) . " AND id_parecer = " . $this->input->get('idParecer', TRUE));

                echo $parecer->parecer . ($parecer->tem_anexo ? "<br><br><a style='color:red;' target='_blank' href='https://www.convenios.gov.br/siconv/DetalharParecerProposta/ParecerPropostaVisualizarParecer.do?path=/MostraPrincipalConsultarPrograma.do&Usr=guest&Pwd=guest&idProposta=" . $parecer->id_proposta . "&idParecer=" . $parecer->id_parecer . "'>Visualizar Anexo</a>" : "");
            }
        } else {
            $dados_proposta = $this->banco_proposta_model->get_by_id($this->input->get('id', TRUE));
            $data['dados_proposta'] = $dados_proposta;
            $data['dados_empenho'] = $this->empenhos_model->get_by_id_proposta_siconv($dados_proposta->id_siconv);
            $data['main'] = 'in/detalha_propostas_pareceres';
            if ($this->session->userdata('gp') == true || $this->input->get('gp', TRUE) != false) {
                $data['title'] = 'G&P - Propostas e Pareceres';
            } else {
                $data['title'] = 'Physis - Propostas e Pareceres';
            }

            $this->load->view('in/template', $data);
        }
    }

    public function detalha_propostas_objeto() {
        $this->load->model('banco_proposta_model');
        $this->load->model('empenhos_model');

        if ($this->input->get('id', TRUE) != false && $this->input->get('idProposta', TRUE) != false && $this->input->get('idParecer', TRUE) != false) {
            if ($this->input->get('tipo', TRUE) == "plano_trabalho") {
                $parecer = $this->banco_proposta_model->get_parecer_plano_trabalho($this->input->get('idProposta', TRUE), $this->input->get('idParecer', TRUE));

                $this->banco_proposta_model->atualiza_data_visualizado("parecer_plano_trabalho_banco_proposta", array('visualizado_em' => date('Y-m-d')), "id_proposta = " . $this->input->get('idProposta', TRUE) . " AND id_parecer = " . $this->input->get('idParecer', TRUE));

                echo $parecer->parecer . ($parecer->tem_anexo ? "<br><br><a style='color:red;' target='_blank' href='https://www.convenios.gov.br/siconv/DetalharParecerProposta/ParecerPropostaVisualizarParecerPlanoTrabalho.do?path=/MostraPrincipalConsultarPrograma.do&Usr=guest&Pwd=guest&idProposta=" . $parecer->id_proposta . "&idParecer=" . $parecer->id_parecer . "'>Visualizar Anexo</a>" : "");
            } else if ($this->input->get('tipo', TRUE) == "ajuste_plano_trabalho") {
                $parecer = $this->banco_proposta_model->get_parecer_ajuste_plano_trabalho($this->input->get('idProposta', TRUE), $this->input->get('idParecer', TRUE));

                $this->banco_proposta_model->atualiza_data_visualizado("parecer_ajuste_pl_trabalho_banco_proposta", array('visualizado_em' => date('Y-m-d')), "id_proposta = " . $this->input->get('idProposta', TRUE) . " AND id_parecer = " . $this->input->get('idParecer', TRUE));

                echo $parecer->parecer . ($parecer->tem_anexo ? "<br><br><a style='color:red;' target='_blank' href='https://www.convenios.gov.br/siconv/DetalharParecerProposta/ParecerPropostaVisualizarParecerPlanoTrabalho.do?path=/MostraPrincipalConsultarPrograma.do&Usr=guest&Pwd=guest&idProposta=" . $parecer->id_proposta . "&idParecer=" . $parecer->id_parecer . "'>Visualizar Anexo</a>" : "");
            } else {
                $parecer = $this->banco_proposta_model->get_parecer_proposta($this->input->get('idProposta', TRUE), $this->input->get('idParecer', TRUE));

                $this->banco_proposta_model->atualiza_data_visualizado("parecer_proposta_banco_proposta", array('visualizado_em' => date('Y-m-d')), "id_proposta = " . $this->input->get('idProposta', TRUE) . " AND id_parecer = " . $this->input->get('idParecer', TRUE));

                echo $parecer->parecer . ($parecer->tem_anexo ? "<br><br><a style='color:red;' target='_blank' href='https://www.convenios.gov.br/siconv/DetalharParecerProposta/ParecerPropostaVisualizarParecer.do?path=/MostraPrincipalConsultarPrograma.do&Usr=guest&Pwd=guest&idProposta=" . $parecer->id_proposta . "&idParecer=" . $parecer->id_parecer . "'>Visualizar Anexo</a>" : "");
            }
        } else {
            $dados_proposta = $this->banco_proposta_model->get_by_id($this->input->get('id', TRUE));
            $data['dados_proposta'] = $dados_proposta;
            $data['dados_empenho'] = $this->empenhos_model->get_by_id_proposta_siconv($dados_proposta->id_siconv);
            $data['main'] = 'in/detalha_propostas_objeto';
            if ($this->session->userdata('gp') == true || $this->input->get('gp', TRUE) != false) {
                $data['title'] = 'G&P - Propostas e Pareceres';
            } else {
                $data['title'] = 'Physis - Propostas e Pareceres';
            }

            $this->load->view('in/template', $data);
        }
    }

    public function get_data_visualizado() {
        $this->load->model('banco_proposta_model');
        $retorno = $this->banco_proposta_model->get_parecer_proposta($this->input->get('idProposta', TRUE), $this->input->get('idParecer', TRUE));

        if ($retorno != null) {
            if ($retorno->visualizado_em != null) {
                if ($retorno->visualizado_em != "") {
                    echo implode("/", array_reverse(explode("-", $retorno->visualizado_em)));
                }
            }
        }
    }

    function carrega_programas() {
        $data ['title'] = "Physis - Gestão de Usuários e Propostas";

        $this->load->model('programa_model');
        $id = $this->input->get_post('id', TRUE);
        $count_pag = - 1;
        if ($this->input->get_post('count_pag', TRUE) !== false)
            $count_pag = intval($this->input->get_post('count_pag', TRUE));
        $count_pag1 = - 1;
        if ($this->input->get_post('count_pag1', TRUE) !== false)
            $count_pag1 = intval($this->input->get_post('count_pag1', TRUE));
        $count_pag2 = - 1;
        if ($this->input->get_post('count_pag2', TRUE) !== false)
            $count_pag2 = intval($this->input->get_post('count_pag2', TRUE));
        $this->autentica_siconv->new_init_siconv_do_login($this->login_usuario, $this->senha, $this->login_siconv, $this->cookie_file_path);

        // retirei essa informação pois como os dados repetidos não serao substituidos,
        // nao faz sentido apagar para recarregar as mesmas informações. Só serão inseridos os novos programas
        // if ($id == '10')
        // $this->programa_model->apagar_dados();

        $anterior = array();
        $remotePageUrl = 'https://www.convenios.gov.br/siconv/ListarProgramasPrincipal/ListarProgramasPrincipal.do';
        $this->autentica_siconv->new_init_siconv_do_login($this->login_usuario, $this->senha, $this->login_siconv, $this->cookie_file_path);
        $documento = $this->autentica_siconv->new_obter_pagina($remotePageUrl, $this->login_siconv, $this->cookie_file_path);
        preg_match_all("/href\=\"([a-zA-Z_\.0-9\/\-\! :\&\-\;\@\$\=\?]*)\"/i", $documento, $matches);
        $orgaos = $this->getTextBetweenTags($documento, "<\/span> ", "<\/a><\/li>");

        if ($count_pag >= 0) { // MINISTERIO DAS CIDADES muito longo
            $documento1 = $this->autentica_siconv->new_obter_pagina("https://www.convenios.gov.br/siconv/ListarProgramasPrincipal/ConsultaOrgaosConsultar.do?id=1480", $this->login_siconv, $this->cookie_file_path);
            $prox = null;
            if ($count_pag == 0) {
                $anterior [0] = $remotePageUrl;
                $anterior [1] = "https://www.convenios.gov.br/siconv/ListarProgramasPrincipal/ConsultaOrgaosConsultar.do?id=1480";
                $this->imprimeDetalhePrograma_bd("https://www.convenios.gov.br/siconv/ListarProgramasPrincipal/ConsultaOrgaosConsultar.do?id=1480", "MINISTERIO DAS CIDADES", $anterior);
                $this->autentica_siconv->new_obter_pagina($remotePageUrl, $this->login_siconv, $this->cookie_file_path);
                $documento1 = $this->autentica_siconv->new_obter_pagina("https://www.convenios.gov.br/siconv/ListarProgramasPrincipal/ConsultaOrgaosConsultar.do?id=1480", $this->login_siconv, $this->cookie_file_path);
                preg_match_all("/href\=\"([a-zA-Z_\.0-9\/\-\! :\&\-\;\@\$\=\?]*)\"/i", $documento1, $matches1);
                foreach ($matches1 [1] as $pag1) {
                    if (strstr($pag1, 'resultado-da-consulta-de-programas-de-convenio.jsp?id=') !== false) {
                        $this->imprimeDetalhePrograma_bd(str_replace("&amp;", "&", $pag1), "MINISTERIO DAS CIDADES", $anterior);
                    }
                }
            }
            $anterior1 = array();
            $anterior2 = array();
            for ($i = ($count_pag * 10) + 1; $i <= ($count_pag * 10) + 10; $i ++) {
                if ($i == 1)
                    continue; // primeira página ja foi percorrida
                $prox [0] = str_replace("&amp;", "&", $prox [0]);
                $anterior1 [0] = "https://www.convenios.gov.br/siconv/ListarProgramasPrincipal/ConsultaOrgaosConsultar.do?id=1480";
                $anterior2 [0] = "https://www.convenios.gov.br/siconv/ListarProgramasPrincipal/ConsultaOrgaosConsultar.do?id=1480";
                $this->imprimeDetalhePrograma_bd("https://www.convenios.gov.br/siconv/ListarProgramasPrincipal/ConsultaOrgaosConsultar.do?id=1480&d-16544-t=listaProgramas&d-16544-g=" . $i, "MINISTERIO DAS CIDADES", $anterior1);
                $this->autentica_siconv->new_obter_pagina("https://www.convenios.gov.br/siconv/ListarProgramasPrincipal/ConsultaOrgaosConsultar.do?id=1480", $this->login_siconv, $this->cookie_file_path);
                $documento1 = $this->autentica_siconv->new_obter_pagina("https://www.convenios.gov.br/siconv/ListarProgramasPrincipal/ConsultaOrgaosConsultar.do?id=1480&d-16544-t=listaProgramas&d-16544-g=" . $i, $this->login_siconv, $this->cookie_file_path);
                $anterior2 [1] = "https://www.convenios.gov.br/siconv/ListarProgramasPrincipal/ConsultaOrgaosConsultar.do?id=1480&d-16544-t=listaProgramas&d-16544-g=" . $i;
                preg_match_all("/href\=\"([a-zA-Z_\.0-9\/\-\! :\&\-\;\@\$\=\?]*)\"/i", $documento1, $matches1);
                foreach ($matches1 [1] as $pag1) {
                    if (strstr($pag1, 'resultado-da-consulta-de-programas-de-convenio.jsp?id=') !== false) {
                        $this->imprimeDetalhePrograma_bd(str_replace("&amp;", "&", $pag1), "MINISTERIO DAS CIDADES", $anterior2);
                    }
                }
            }
            die();
        } else if ($count_pag1 >= 0) { // MINISTERIO DA SAÚDE muito longo
            $documento1 = $this->autentica_siconv->new_obter_pagina("https://www.convenios.gov.br/siconv/ListarProgramasPrincipal/ConsultaOrgaosConsultar.do?id=1472", $this->login_siconv, $this->cookie_file_path);
            $prox = null;
            if ($count_pag1 == 0) {
                $anterior [0] = $remotePageUrl;
                $anterior [1] = "https://www.convenios.gov.br/siconv/ListarProgramasPrincipal/ConsultaOrgaosConsultar.do?id=1472";
                $this->imprimeDetalhePrograma_bd("https://www.convenios.gov.br/siconv/ListarProgramasPrincipal/ConsultaOrgaosConsultar.do?id=1472", "MINISTERIO DA SAUDE", $anterior);
                $this->autentica_siconv->new_obter_pagina($remotePageUrl, $this->login_siconv, $this->cookie_file_path);
                $documento1 = $this->autentica_siconv->new_obter_pagina("https://www.convenios.gov.br/siconv/ListarProgramasPrincipal/ConsultaOrgaosConsultar.do?id=1472", $this->login_siconv, $this->cookie_file_path);
                preg_match_all("/href\=\"([a-zA-Z_\.0-9\/\-\! :\&\-\;\@\$\=\?]*)\"/i", $documento1, $matches1);
                foreach ($matches1 [1] as $pag1) {
                    if (strstr($pag1, 'resultado-da-consulta-de-programas-de-convenio.jsp?id=') !== false) {
                        $this->imprimeDetalhePrograma_bd(str_replace("&amp;", "&", $pag1), "MINISTERIO DA SAUDE", $anterior);
                    }
                }
            }
            $anterior1 = array();
            $anterior2 = array();
            for ($i = ($count_pag1 * 10) + 1; $i <= ($count_pag1 * 10) + 10; $i ++) {
                if ($i == 1)
                    continue; // primeira página ja foi percorrida
                $prox [0] = str_replace("&amp;", "&", $prox [0]);
                $anterior1 [0] = "https://www.convenios.gov.br/siconv/ListarProgramasPrincipal/ConsultaOrgaosConsultar.do?id=1472";
                $anterior2 [0] = "https://www.convenios.gov.br/siconv/ListarProgramasPrincipal/ConsultaOrgaosConsultar.do?id=1472";
                $this->imprimeDetalhePrograma_bd("https://www.convenios.gov.br/siconv/ListarProgramasPrincipal/ConsultaOrgaosConsultar.do?id=1472&d-16544-t=listaProgramas&d-16544-g=" . $i, "MINISTERIO DA SAUDE", $anterior1);
                $this->autentica_siconv->new_obter_pagina("https://www.convenios.gov.br/siconv/ListarProgramasPrincipal/ConsultaOrgaosConsultar.do?id=1472", $this->login_siconv, $this->cookie_file_path);
                $documento1 = $this->autentica_siconv->new_obter_pagina("https://www.convenios.gov.br/siconv/ListarProgramasPrincipal/ConsultaOrgaosConsultar.do?id=1472&d-16544-t=listaProgramas&d-16544-g=" . $i, $this->login_siconv, $this->cookie_file_path);
                $anterior2 [1] = "https://www.convenios.gov.br/siconv/ListarProgramasPrincipal/ConsultaOrgaosConsultar.do?id=1472&d-16544-t=listaProgramas&d-16544-g=" . $i;
                preg_match_all("/href\=\"([a-zA-Z_\.0-9\/\-\! :\&\-\;\@\$\=\?]*)\"/i", $documento1, $matches1);
                foreach ($matches1 [1] as $pag1) {
                    if (strstr($pag1, 'resultado-da-consulta-de-programas-de-convenio.jsp?id=') !== false) {
                        $this->imprimeDetalhePrograma_bd(str_replace("&amp;", "&", $pag1), "MINISTERIO DA SAUDE", $anterior2);
                    }
                }
            }
            die();
        } else if ($count_pag2 >= 0) { // MINISTERIO DA DEFESA muito longo
            $documento1 = $this->autentica_siconv->new_obter_pagina("https://www.convenios.gov.br/siconv/ListarProgramasPrincipal/ConsultaOrgaosConsultar.do?id=1494", $this->login_siconv, $this->cookie_file_path);
            $prox = null;
            if ($count_pag1 == 0) {
                $anterior [0] = $remotePageUrl;
                $anterior [1] = "https://www.convenios.gov.br/siconv/ListarProgramasPrincipal/ConsultaOrgaosConsultar.do?id=1494";
                $this->imprimeDetalhePrograma_bd("https://www.convenios.gov.br/siconv/ListarProgramasPrincipal/ConsultaOrgaosConsultar.do?id=1494", "MINISTERIO DA DEFESA", $anterior);
                $this->autentica_siconv->new_obter_pagina($remotePageUrl, $this->login_siconv, $this->cookie_file_path);
                $documento1 = $this->autentica_siconv->new_obter_pagina("https://www.convenios.gov.br/siconv/ListarProgramasPrincipal/ConsultaOrgaosConsultar.do?id=1494", $this->login_siconv, $this->cookie_file_path);
                preg_match_all("/href\=\"([a-zA-Z_\.0-9\/\-\! :\&\-\;\@\$\=\?]*)\"/i", $documento1, $matches1);
                foreach ($matches1 [1] as $pag1) {
                    if (strstr($pag1, 'resultado-da-consulta-de-programas-de-convenio.jsp?id=') !== false) {
                        $this->imprimeDetalhePrograma_bd(str_replace("&amp;", "&", $pag1), "MINISTERIO DA DEFESA", $anterior);
                    }
                }
            }
            $anterior1 = array();
            $anterior2 = array();
            for ($i = ($count_pag1 * 10) + 1; $i <= ($count_pag1 * 10) + 10; $i ++) {
                if ($i == 1)
                    continue; // primeira página ja foi percorrida
                $prox [0] = str_replace("&amp;", "&", $prox [0]);
                $anterior1 [0] = "https://www.convenios.gov.br/siconv/ListarProgramasPrincipal/ConsultaOrgaosConsultar.do?id=1494";
                $anterior2 [0] = "https://www.convenios.gov.br/siconv/ListarProgramasPrincipal/ConsultaOrgaosConsultar.do?id=1494";
                $this->imprimeDetalhePrograma_bd("https://www.convenios.gov.br/siconv/ListarProgramasPrincipal/ConsultaOrgaosConsultar.do?id=1494&d-16544-t=listaProgramas&d-16544-g=" . $i, "MINISTERIO DA DEFESA", $anterior1);
                $this->autentica_siconv->new_obter_pagina("https://www.convenios.gov.br/siconv/ListarProgramasPrincipal/ConsultaOrgaosConsultar.do?id=1494", $this->login_siconv, $this->cookie_file_path);
                $documento1 = $this->autentica_siconv->new_obter_pagina("https://www.convenios.gov.br/siconv/ListarProgramasPrincipal/ConsultaOrgaosConsultar.do?id=1494&d-16544-t=listaProgramas&d-16544-g=" . $i, $this->login_siconv, $this->cookie_file_path);
                $anterior2 [1] = "https://www.convenios.gov.br/siconv/ListarProgramasPrincipal/ConsultaOrgaosConsultar.do?id=1494&d-16544-t=listaProgramas&d-16544-g=" . $i;
                preg_match_all("/href\=\"([a-zA-Z_\.0-9\/\-\! :\&\-\;\@\$\=\?]*)\"/i", $documento1, $matches1);
                foreach ($matches1 [1] as $pag1) {
                    if (strstr($pag1, 'resultado-da-consulta-de-programas-de-convenio.jsp?id=') !== false) {
                        $this->imprimeDetalhePrograma_bd(str_replace("&amp;", "&", $pag1), "MINISTERIO DA DEFESA", $anterior2);
                    }
                }
            }
            die();
        }
        foreach ($matches [1] as $key => $pag) {
            if ($key > $id - 10 && $key <= $id && trim($pag) != '/siconv/ListarProgramasPrincipal/ConsultaOrgaosConsultar.do?id=1472' && trim($pag) != '/siconv/ListarProgramasPrincipal/ConsultaOrgaosConsultar.do?id=1480' && trim($pag) != '/siconv/ListarProgramasPrincipal/ConsultaOrgaosConsultar.do?id=1494') {
                // if (($key > $id-10 && $key <= $id && $key != 94 && $key != 126 && $key != 115 && $id != '113') || ($id == '113' && $key == 115 )){
                // if (($key > $id-10 && $key <= $id && $key != 91 && $key != 123 && $key != 112 && $id != '111') || ($id == '111' && $key == 112 )){

                $pag = str_replace("&amp;", "&", $pag);
                $anterior [0] = $remotePageUrl;
                $anterior [1] = "https://www.convenios.gov.br" . $pag;
                $this->imprimeDetalhePrograma_bd("https://www.convenios.gov.br" . $pag, $orgaos [$key - 1], $anterior);
                $this->autentica_siconv->new_obter_pagina($remotePageUrl, $this->login_siconv, $this->cookie_file_path);

                // echo $remotePageUrl."<br />";
                $documento1 = $this->autentica_siconv->new_obter_pagina("https://www.convenios.gov.br" . $pag, $this->login_siconv, $this->cookie_file_path);
                // echo "https://www.convenios.gov.br".$pag."<br />";
                preg_match_all("/href\=\"([a-zA-Z_\.0-9\/\-\! :\&\-\;\@\$\=\?]*)\"/i", $documento1, $matches1);
                // echo trim($pag)."<br>"; die();
                // echo trim($pag)."<br>"; die();
                foreach ($matches1 [1] as $pag1) {

                    if (strstr($pag1, 'resultado-da-consulta-de-programas-de-convenio.jsp?id=') !== false) {
                        $this->imprimeDetalhePrograma_bd(str_replace("&amp;", "&", $pag1), $orgaos [$key - 1], $anterior);
                    }
                }

                $url_sem_espaco = $this->removeSpaceSurplus($documento1);
                $prox = $this->getTextBetweenTags($url_sem_espaco, " \[<a href=\"", "\">Pr");
                $anterior1 = array();
                $anterior2 = array();

                while (count($prox) > 0) {

                    // echo "https://www.convenios.gov.br".$prox[0]."<br />";
                    $prox [0] = str_replace("&amp;", "&", $prox [0]);
                    $anterior1 [0] = "https://www.convenios.gov.br" . $pag;
                    $anterior2 [0] = "https://www.convenios.gov.br" . $pag;
                    $this->imprimeDetalhePrograma_bd("https://www.convenios.gov.br" . $prox [0], $orgaos [$key - 1], $anterior1);
                    $this->autentica_siconv->new_obter_pagina("https://www.convenios.gov.br" . $pag, $this->login_siconv, $this->cookie_file_path);
                    // echo "https://www.convenios.gov.br".$pag."<br />";
                    $documento1 = $this->autentica_siconv->new_obter_pagina("https://www.convenios.gov.br" . $prox [0], $this->login_siconv, $this->cookie_file_path);
                    // echo "https://www.convenios.gov.br".$prox[0]."<br />";
                    $anterior2 [1] = "https://www.convenios.gov.br" . $prox [0];
                    preg_match_all("/href\=\"([a-zA-Z_\.0-9\/\-\! :\&\-\;\@\$\=\?]*)\"/i", $documento1, $matches1);
                    foreach ($matches1 [1] as $pag1) {
                        if (strstr($pag1, 'resultado-da-consulta-de-programas-de-convenio.jsp?id=') !== false) {
                            $this->imprimeDetalhePrograma_bd(str_replace("&amp;", "&", $pag1), $orgaos [$key - 1], $anterior2);
                        }
                    }
                    $this->autentica_siconv->new_obter_pagina("https://www.convenios.gov.br" . $pag, $this->login_siconv, $this->cookie_file_path);
                    // echo "https://www.convenios.gov.br".$pag."<br />";
                    $documento2 = $this->autentica_siconv->new_obter_pagina("https://www.convenios.gov.br" . $prox [0], $this->login_siconv, $this->cookie_file_path);
                    // echo "https://www.convenios.gov.br".$prox[0]."<br />";
                    $prox = $this->getTextBetweenTags($documento2, " \[<a href=\"", "\">Pr");
                }
            }
        }

        die();
    }

    function brute_force() {
        $remotePageUrl = 'https://www.convenios.gov.br/siconv/ListarProgramasPrincipal/ListarProgramasPrincipal.do';
        $this->autentica_siconv->new_init_siconv_do_login($this->login_usuario, $this->senha, $this->login_siconv, $this->cookie_file_path);
        $documento = $this->autentica_siconv->new_obter_pagina($remotePageUrl, $this->login_siconv, $this->cookie_file_path);
        preg_match_all("/href\=\"([a-zA-Z_\.0-9\/\-\! :\&\-\;\@\$\=\?]*)\"/i", $documento, $matches);

        echo $documento;
    }

    function brute_force1() {
        $remotePageUrl = 'https://www.convenios.gov.br/siconv/ListarProgramasPrincipal/ListarProgramasPrincipal.do';
        $this->autentica_siconv->new_init_siconv_do_login($this->login_usuario, $this->senha, $this->login_siconv, $this->cookie_file_path);
        $documento = $this->autentica_siconv->new_obter_pagina($remotePageUrl, $this->login_siconv, $this->cookie_file_path);
        preg_match_all("/href\=\"([a-zA-Z_\.0-9\/\-\! :\&\-\;\@\$\=\?]*)\"/i", $documento, $matches);

        $orgaos = $this->getTextBetweenTags($documento, "<\/span> ", "<\/a><\/li>");

        print_r($orgaos);
    }

    function carrega_propostas() {
        ini_set("max_execution_time", 0);
        ini_set('memory_limit', '1000M');
        $this->load->model('programa_model');
        $indice_lista = 0;
        if ($this->input->get_post('indice_lista', TRUE) !== false)
            $indice_lista = $this->input->get_post('indice_lista', TRUE);

        $itens = 500;
        $jsonurl = "http://api.convenios.gov.br/siconv/v1/consulta/propostas.json?offset=" . $indice_lista;
        $json = file_get_contents($jsonurl, 0, null, null);
        $json_output = json_decode($json);

        if ($json == '') {
            echo "Siconv com problemas internos, por favor tente novamente mais tarde.";
        }
        if (sizeof($json_output->propostas) == 0) {
            echo "Finalizado";
            die();
        }

        foreach ($json_output->propostas as $proposta) {
            $url = "http://api.convenios.gov.br/siconv/id/proposta/" . $proposta->id . ".json";
            $ch = curl_init();
            $timeout = 5;
            $headers = array(
                "Cache-Control: no-cache"
            );
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
            // curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);
            $json = curl_exec($ch);
            curl_close($ch);

            $programa = '';
            $json_output1 = json_decode($json);
            /*
             * echo $url; echo $json; var_dump ($json_output1); die();
             */
            if (trim($json_output1) == '' || !isset($json_output1)) {

                $ch = curl_init();
                $timeout = 15;
                $headers = array(
                    "Cache-Control: no-cache"
                );
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_URL, "http://api.convenios.gov.br/siconv/id/proposta/" . $proposta->id . ".html");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
                curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);
                $json = curl_exec($ch);
                curl_close($ch);
                // echo $json; die();
                $programa = $this->getTextBetweenTags($json, "<a href\=\"http:\/\/api.convenios.gov.br\/siconv\/id\/programa\/", "\">Programa");
                // $programa = $programa[0];
                $programa = $this->obterCodigoPrograma($programa [0]);
                $json = $this->removeSpaceSurplus($json);
                $numero = $this->getTextBetweenTags($json, "<dt>Numero Proposta<\/dt> <dd>", "<\/dd>");
                $numero = $numero [0];
            }
            echo "teste1";
            die();
            if (trim($programa) == '') {
                $programa = $this->obterCodigoPrograma($json_output1->propostas [0]->programas [0]->associacao [0]->Programa->id);
                $numero = $orgaos->propostas [0]->numero_proposta;
            }
            echo "teste2";
            $data = array(
                'id' => $proposta->id,
                'numero' => $numero,
                'programa' => $programa
            );
            var_dump($data);
            $inserido = $this->programa_model->inserir_proposta_siconv($data);
        }
        $result1 = $indice_lista + $itens;
        $this->encaminha('carrega_propostas?indice_lista=' . $result1);
    }

    function programas_tabela() {
        $data ['title'] = "Physis - Gestão de Usuários e Propostas";
        $data ['main'] = 'in/programas_tabela';
        $this->load->model('programa_model');
        $programas = $this->programa_model->get_all();
        $listaPrograma = array();

        foreach ($programas as $programa) {
            if (isset($listaPrograma [$programa->orgao] [$programa->orgao_vinculado] [$programa->ano]) === false)
                $listaPrograma [$programa->orgao] [$programa->orgao_vinculado] [$programa->ano] = 1;
            else
                $listaPrograma [$programa->orgao] [$programa->orgao_vinculado] [$programa->ano] ++;
        }
        $data ['login'] = '';
        $data ['listaPrograma'] = $listaPrograma;
        $this->load->view('in/template', $data);
    }

    function programas() {
        $this->load->model('programa_model');
        $this->load->model('usuariomodel');
        $logado = true;
        $usuario_logado = $this->usuario_logado;
        if ($this->input->get_post('operation', TRUE) !== false) {
            $this->programa_model->apagar_dados_usuario_programa($this->input->get_post('usuario', TRUE));
            foreach ($this->input->post(NULL, TRUE) as $key => $prog) {
                if ($key != 'operation' && $key != 'todos_estado' && $key != 'todos_municipio' && $key != 'usuario') {
                    echo $key . ' => ' . $prog . '<br />';
                    $data = array(
                        'acesso' => true,
                        'aceito' => false,
                        'idPessoa' => $this->input->get_post('usuario', TRUE),
                        'codigoPrograma' => $prog
                    );

                    $inserido = $this->programa_model->insere_programa_usuario($data);
                }
            }
        }
        $this->load->model('cidades_model');
        $data ['title'] = "Physis - Gestão de Usuários e Propostas";
        $data ['main'] = 'in/programas';

        helper_geral::setPagAtual("programas");

        $data ['cidades'] = $this->cidades_model->obter_estados();
        $data ['orgaos'] = $this->programa_model->obter_lista_distinct('orgao');
        $data ['qualificacao'] = $this->programa_model->obter_lista_distinct('qualificacao');
        $data ['atende'] = $this->programa_model->obter_lista_distinct('atende');
        $data ['lista'] = null;
        $data ['listaAceito'] = null;

        $data ['usuario'] = $this->input->get_post('usuario', TRUE);
        if ($logado == true) {
            $data ['lista'] = $this->programa_model->obter_programas_por_usuario($data ['usuario']);
            $data ['listaAceito'] = $this->programa_model->obter_programas_por_usuario_aceito($data ['usuario']);
        }
        $data ['login'] = "Olá, " . $usuario_logado->nome . " - <a href=\"" . base_url() . "index.php/in/login/sair\">Sair</a>";

        $this->load->view('in/template', $data);
    }

    function permitir_mudancas() {
        $this->load->model('programa_model');
        $this->load->model('usuariomodel');
        $logado = true;

        if ($this->input->get_post('usuario', TRUE) !== false) {
            $this->programa_model->permitir_programas_por_usuario($this->input->get_post('usuario', TRUE));
            $this->alert("Concedida!");
            $this->voltaPagina();
        } else {
            $this->alert("Sem usuário definido");
            $this->voltaPagina();
        }
    }

    function propostas_programas() {
        // ini_set("max_execution_time", 0);
        // ini_set('memory_limit', '1000M');
        if ($this->input->get_post('cod_cidades', TRUE) !== false || $this->input->get_post('temp', TRUE) !== false) {
            $listaPropostas = array();
            $this->load->model('cidades_model');
            $this->load->model('programa_model');
            $estado = $this->input->get_post('cod_estados', TRUE);
            $cidade = $this->input->get_post('cod_cidades', TRUE);

            $cnpj = $this->cidades_model->obter_cnpj($cidade);

            $aleatorio = 0;
            $numero_prop = 0;

            if ($this->input->get_post('temp', TRUE) !== false)
                $aleatorio = floatval($this->input->get_post('temp', TRUE));
            else
                $aleatorio = rand(0, 9999999);
            if ($this->input->get_post('numero_prop', TRUE) !== false)
                $numero_prop = floatval($this->input->get_post('numero_prop', TRUE));

            foreach ($cnpj as $cidade_aux) {

                $cidade_aux ['cnpj'] = preg_replace("/[^0-9\s]/", "", $cidade_aux ['cnpj']);
                $jsonurl = "http://api.convenios.gov.br/siconv/v1/consulta/propostas.json?id_proponente=" . $cidade_aux ['cnpj'];

                $listaPropostas = $this->retorna_propostas_programas($jsonurl, $listaPropostas, $numero_prop, $aleatorio, $cidade);
            }

            $this->encaminha('propostas_programas_listar?temp=' . $aleatorio);
            die();

            /*
             * if (!array_key_exists(123, $lista)) { $lista[123][null] = 1; } foreach($lista as $key => $l){ var_dump($l); echo count($l)."<br>"; }
             */
        } else {
            $data ['title'] = "Physis - Gestão de Usuários e Propostas";
            $data ['main'] = 'in/propostas_programas';
            $this->load->model('cidades_model');

            $data ['cidades'] = $this->cidades_model->obter_estados();
            $data ['login'] = '';
            $this->load->view('in/template', $data);
        }
    }

    function propostas_programas_listar() {
        $this->load->model('programa_model');
        $aleatorio = $this->input->get_post('temp', TRUE);
        $tmpfname = getcwd() . "/configuracoes/programa" . $aleatorio;
        $handle = fopen($tmpfname, "r");
        $listaPropostas1 = fgets($handle);
        fclose($handle);
        unlink($tmpfname);

        $compressed1 = $this->_decode_string_array($listaPropostas1);
        $listaPropostas = $compressed1;

        $data ['title'] = "Physis - Gestão de Usuários e Propostas";
        $data ['main'] = 'in/propostas_programas_listar';
        $data ['programa_model'] = $this->programa_model;
        $data ['listaPropostas'] = $listaPropostas;
        $data ['login'] = '';
        $this->load->view('in/template', $data);
    }

    function propostas() {
        $data ['title'] = "Physis - Gestão de Usuários e Propostas";
        $data ['main'] = 'in/propostas';
        $this->load->model('cidades_model');

        $data ['cidades'] = $this->cidades_model->obter_estados();
        $data ['login'] = '';
        $data ['sistema'] = false;
        $this->load->view('in/template', $data);
    }

    function propostas_sistema() {
        $data ['title'] = "Physis - Gestão de Usuários e Propostas";
        $data ['main'] = 'in/propostas';
        $this->load->model('cidades_model');

        $data ['cidades'] = $this->cidades_model->obter_estados();
        $data ['login'] = '';
        $data ['sistema'] = true;
        // echo $this->session->userdata['session_id']; die();
        $this->load->view('in/template', $data);
    }

    function proposta_tabela() {
        // error_reporting(E_WARNING);
        $this->load->model('cidades_model');
        $this->load->model('proposta_model');
        $this->load->model('usuario_model');

        $data ['title'] = "Physis - Gestão de Usuários e Propostas";
        $data ['main'] = 'in/proposta_tabela';
        $listaPropostas = array();
        $ano = $this->input->get_post('ano', TRUE);

        $sistema = array();
        $usuario_logado = $this->usuario_model->get_by_login($this->session->userdata('login'));
        if ($this->input->get_post('sistema', TRUE) == true)
            $sistema = $this->proposta_model->get_all_idsiconv($usuario_logado->idPessoa);

        $cidade = $this->input->get_post('cod_cidades', TRUE);
        $cnpj = $this->cidades_model->obter_cnpj($cidade);

        foreach ($cnpj as $cidade) {

            $cidade ['cnpj'] = preg_replace("/[^0-9\s]/", "", $cidade ['cnpj']);
            $jsonurl = "http://api.convenios.gov.br/siconv/v1/consulta/propostas.json?id_proponente=" . $cidade ['cnpj'];
            // echo $jsonurl;
            $listaPropostas = $this->imprimeProposta($jsonurl, $ano, $listaPropostas, $sistema);
            $listaPropostas ['cnpj'] = $cidade ['cnpj'];
            // var_dump($listaPropostas); die();
        }
        // $this->session->set_userdata('teste', '1234567');
        $cidade = $this->input->get_post('cod_cidades', TRUE);
        $aleatorio = rand(0, 9999999);

        $tee = $this->_encode_string_array(serialize($listaPropostas));
        $tmpfname = getcwd() . "/configuracoes/temp" . $aleatorio;

        $handle = fopen($tmpfname, "w+");
        if ($handle == false)
            die('Não foi possível criar o arquivo.');
        fwrite($handle, $tee);
        fseek($handle, 0);
        fclose($handle);

        $tee = $this->_encode_string_array(serialize($this->input->post(NULL, TRUE)));
        $tmpfname = getcwd() . "/configuracoes/dados" . $aleatorio;

        $handle = fopen($tmpfname, "w+");
        if ($handle == false)
            die('Não foi possível criar o arquivo.');
        fwrite($handle, $tee);
        fseek($handle, 0);
        fclose($handle);

        // echo fgets($handle);
        // fclose($handle);
        // $this->session->sess_destroy();
        // $this->session->set_userdata('temp', $tmpfname);

        $this->encaminha('pdf1_programas?temp=' . $aleatorio . '&ano=0&interno=0');
        die();
    }

    function pdf1_programas() {
        $aleatorio = $this->input->get_post('temp', TRUE);
        $ano = intval($this->input->get_post('ano', TRUE));
        $interno = intval($this->input->get_post('interno', TRUE));
        $tmpfname = getcwd() . "/configuracoes/temp" . $aleatorio;
        // print_r ($this->session->userdata); die();
        // echo $tmpfname; die();
        $handle = fopen($tmpfname, "r");
        if ($handle == false) {
            $this->alert('Sem dados para exibir. Efetue uma nova busca!');
            $this->voltaPagina();
        }
        $listaPropostas1 = fgets($handle);
        fclose($handle);
        // unlink($tmpfname);

        $tmpfname1 = getcwd() . "/configuracoes/dados" . $aleatorio;

        $handle1 = fopen($tmpfname1, "r");
        $dado_post1 = fgets($handle1);
        fclose($handle1);
        // unlink($tmpfname1);
        $dado_post = $this->_decode_string_array($dado_post1);

        $compressed1 = $this->_decode_string_array($listaPropostas1);
        $listaPropostas = $compressed1;

        // $tabela = "PHYSIS BRASIL CONSULTORIA E ASSESSORIA SOLIDÁRIA";
        $tabela = "";
        ksort($listaPropostas);
        $count = 0;

        $tmpfname = getcwd() . "/configuracoes/temp_completo" . $aleatorio;
        $cnpj_relatorio = $listaPropostas ['cnpj'];
        unset($listaPropostas ['cnpj']);
        // echo $listaPropostas['cnpj']; die();
        foreach ($listaPropostas as $chave => $lista) {

            if ($count < $ano) { // para que varra uma vez só
                $count ++;
                continue;
            } else if ($count > $ano) { // para que varra uma vez só
                break;
            }

            $aprovados = array();
            $numero_aprovados = 0;
            $numero_total = 0;
            $soma = array();

            if ($this->input->get_post('numero_total', TRUE) !== false)
                $numero_total = floatval($this->input->get_post('numero_total', TRUE));
            if ($this->input->get_post('numero_aprovados', TRUE) !== false)
                $numero_aprovados = floatval($this->input->get_post('numero_aprovados', TRUE));

            if ($this->input->get_post('valor_global', TRUE) !== false)
                $soma ['valor_global'] = floatval($this->input->get_post('valor_global', TRUE));
            if ($this->input->get_post('valor_repasse', TRUE) !== false)
                $soma ['valor_repasse'] = floatval($this->input->get_post('valor_repasse', TRUE));
            if ($this->input->get_post('valor_contra_partida', TRUE) !== false)
                $soma ['valor_contra_partida'] = floatval($this->input->get_post('valor_contra_partida', TRUE));

            if ($this->input->get_post('aprovados_global', TRUE) !== false)
                $aprovados ['valor_global'] = floatval($this->input->get_post('aprovados_global', TRUE));
            if ($this->input->get_post('aprovados_repasse', TRUE) !== false)
                $aprovados ['valor_repasse'] = floatval($this->input->get_post('aprovados_repasse', TRUE));
            if ($this->input->get_post('aprovados_contra_partida', TRUE) !== false)
                $aprovados ['valor_contra_partida'] = floatval($this->input->get_post('aprovados_contra_partida', TRUE));

            if ($interno == 0) { // para que varra uma vez só
                // $tabela .= "<br /><br />Cidade: ".$cidade."<br />";
                $tabela .= "<br />Ano: " . $chave . "<br />";
                $tabela .= "<table border=\"1\" style=\"font-size: 12px;\"><tr><td>Órgão superior</td><td>Objeto</td><td>Nº. Prop</td><td>Nº. Conv</td>
			<td>Situação</td><td>Fim, vigência</td><td>Valor global</td>
			<td>Valor repasse</td><td>Valor contrapartida</td></tr>";
            }
            $count1 = 0;
            foreach ($lista as $chave1 => $lista1) {
                if ($count1 < $interno) { // para que varra uma vez só
                    $count1 ++;
                    continue;
                } else if ($count1 > $interno) { // para que varra uma vez só
                    break;
                }
                foreach ($lista1 as $chave2 => $lista2) {
                    $situacao = $this->obterNomeSituacao($chave2, $chave1);
                    /*
                     * var_dump($this->input->post(NULL, TRUE)); echo $situacao; die();
                     */

                    if (in_array(mb_strtolower($situacao, 'UTF-8'), array_map('strtolower', $dado_post))) {
                        // if (array_search($situacao, $this->input->post(NULL, TRUE))){
                        $numero_total ++;
                        $tabela .= "<tr><td>" . $this->obterOrgaoSuperior($chave1) . "</td>";
                        $tabela .= "<td style=\"font-size: 10px;\">" . $lista2 ['objeto'] . "</td>";
                        $tabela .= "<td>" . $lista2 ['numero'] . "</td>";
                        $tabela .= "<td>" . $lista2 ['convenio'] . "</td>";
                        $tabela .= "<td>" . $situacao . "</td>";
                        $tabela .= "<td>" . implode("/", array_reverse(explode("-", $lista2 ['fim']))) . "</td>";
                        $tabela .= "<td>R$" . number_format($lista2 ['valor_global'], 2, ",", ".") . "</td>";
                        $tabela .= "<td>R$" . number_format($lista2 ['valor_repasse'], 2, ",", ".") . "</td>";
                        $tabela .= "<td>R$" . number_format($lista2 ['valor_contra_partida'], 2, ",", ".") . "</td>";
                        if (isset($soma ['valor_global']) !== false)
                            $soma ['valor_global'] += (float) $lista2 ['valor_global'];
                        else
                            $soma ['valor_global'] = (float) $lista2 ['valor_global'];

                        if (isset($soma ['valor_repasse']) !== false)
                            $soma ['valor_repasse'] += (float) $lista2 ['valor_repasse'];
                        else
                            $soma ['valor_repasse'] = (float) $lista2 ['valor_repasse'];

                        if (isset($soma ['valor_contra_partida']) !== false)
                            $soma ['valor_contra_partida'] += (float) $lista2 ['valor_contra_partida'];
                        else
                            $soma ['valor_contra_partida'] = (float) $lista2 ['valor_contra_partida'];
                        if ($lista2 ['convenio'] != null) {
                            $numero_aprovados ++;
                            if (isset($aprovados ['valor_global']) !== false)
                                $aprovados ['valor_global'] += (float) $lista2 ['valor_global'];
                            else
                                $aprovados ['valor_global'] = (float) $lista2 ['valor_global'];

                            if (isset($aprovados ['valor_repasse']) !== false)
                                $aprovados ['valor_repasse'] += (float) $lista2 ['valor_repasse'];
                            else
                                $aprovados ['valor_repasse'] = (float) $lista2 ['valor_repasse'];

                            if (isset($aprovados ['valor_contra_partida']) !== false)
                                $aprovados ['valor_contra_partida'] += (float) $lista2 ['valor_contra_partida'];
                            else
                                $aprovados ['valor_contra_partida'] = (float) $lista2 ['valor_contra_partida'];
                        }
                        $tabela .= "</tr>";
                    }
                }
                $handle = fopen($tmpfname, "a");
                if ($handle == false)
                    die('Não foi possível criar o arquivo.');
                fwrite($handle, $tabela);
                // fseek($handle, 0);
                fclose($handle);
                $count1 ++;
                $this->encaminha('pdf1_programas?temp=' . $aleatorio . '&ano=' . $count . '&interno=' . $count1 . '&numero_total=' . $numero_total . '&numero_aprovados=' . $numero_aprovados . '&valor_global=' . $soma ['valor_global'] . '&valor_repasse=' . $soma ['valor_repasse'] . '&valor_contra_partida=' . $soma ['valor_contra_partida'] . '&aprovados_global=' . $aprovados ['valor_global'] . '&aprovados_repasse=' . $aprovados ['valor_repasse'] . '&aprovados_contra_partida=' . $aprovados ['valor_contra_partida']);
                die();
            }

            $tabela .= "<tr><td>Propostas cadastradas:</td>";
            $tabela .= "<td>" . $numero_total . "</td>";
            // inserir aqui ---------------------------------------------- cnpj ano numero_total numero_aprovados valor_global valor_repasse valor_contra_partida data
            $tabela .= "<td></td>";
            $tabela .= "<td></td>";
            $tabela .= "<td colspan=2 style=\"color: red;\">Total cadastrado</td>";
            $tabela .= "<td style=\"color: red;\">R$" . number_format($soma ['valor_global'], 2, ",", ".") . "</td>";
            $tabela .= "<td style=\"color: red;\">R$" . number_format($soma ['valor_repasse'], 2, ",", ".") . "</td>";
            $tabela .= "<td style=\"color: red;\">R$" . number_format($soma ['valor_contra_partida'], 2, ",", ".") . "</td><tr>";

            $tabela .= "<tr><td>Propostas aprovadas:</td>";
            $tabela .= "<td>" . $numero_aprovados . "</td>";
            $tabela .= "<td></td>";
            $tabela .= "<td></td>";
            $tabela .= "<td colspan=2>Total aprovado</td>";
            if (isset($aprovados ['valor_global']) !== false)
                $tabela .= "<td>R$" . number_format($aprovados ['valor_global'], 2, ",", ".") . "</td>";
            else
                $tabela .= "<td></td>";
            if (isset($aprovados ['valor_repasse']) !== false)
                $tabela .= "<td>R$" . number_format($aprovados ['valor_repasse'], 2, ",", ".") . "</td>";
            else
                $tabela .= "<td></td>";
            if (isset($aprovados ['valor_contra_partida']) !== false)
                $tabela .= "<td>R$" . number_format($aprovados ['valor_contra_partida'], 2, ",", ".") . "</td><tr>";
            else
                $tabela .= "<td></td><tr>";
            $tabela .= "</table>";

            $data = array(
                'cnpj' => $cnpj_relatorio,
                'ano' => $chave,
                'numero_total' => $numero_total,
                'numero_aprovados' => $numero_aprovados,
                'valor_global' => $soma ['valor_global'],
                'valor_repasse' => $soma ['valor_repasse'],
                'valor_contra_partida' => $soma ['valor_contra_partida']
            );

            $this->load->model('proposta_model');
            $inserido = $this->proposta_model->add_log_propostas($data);

            $handle = fopen($tmpfname, "a");
            if ($handle == false)
                die('Não foi possível criar o arquivo.');
            fwrite($handle, $tabela);
            // fseek($handle, 0);
            fclose($handle);
            $count ++;
            $this->encaminha('pdf1_programas?temp=' . $aleatorio . '&ano=' . $count . '&interno=0');
            die();
        }

        $this->encaminha('pdf_programas?temp=' . $aleatorio);
        die();
    }

    function pdf_programas() {
        error_reporting(E_WARNING);
        // $this->load->model('cidades_model');
        $aleatorio = $this->input->get_post('temp', TRUE);
        $tmpfname = getcwd() . "/configuracoes/temp_completo" . $aleatorio;
        // print_r ($this->session->userdata); die();
        // echo $tmpfname; die();
        $handle = fopen($tmpfname, "r");
        if ($handle == false) {
            $this->alert('Sem dados para exibir. Efetue uma nova busca!');
            $this->voltaPagina();
        }
        $listaPropostas = '';
        while (!feof($handle)) {
            $listaPropostas .= fgets($handle);
        }

        fclose($handle);
        $tmpfname1 = getcwd() . "/configuracoes/temp" . $aleatorio;
        $tmpfname2 = getcwd() . "/configuracoes/dados" . $aleatorio;
        unlink($tmpfname);
        unlink($tmpfname1);
        unlink($tmpfname2);
        /*
         * $tmpfname1 = getcwd()."/configuracoes/dados".$aleatorio; //print_r ($this->session->userdata); die(); //echo $tmpfname; die(); $handle1 = fopen($tmpfname1, "r"); $dado_post1 = fgets($handle1); fclose($handle1); //unlink($tmpfname1); $dado_post = $this->_decode_string_array($dado_post1);
         */
        // $listaPropostas = utf8_encode($listaPropostas);
        // echo $listaPropostas; die();
        $this->load->library('mPDF');
        // $this->load->model('pdf_model');
        ob_start(); // inicia o buffer

        /*
         * $received = urldecode($_GET['tabela']);//decodifica o valor passado pelo link $received = stripslashes($received);//limpa a string de \ antes de " $listaPropostas = unserialize($received);//transforma a string em array
         */

        // echo $tabela; die();
        $mpdf = new mPDF ();
        $mpdf->allow_charset_conversion = true;
        $mpdf->charset_in = 'UTF-8';
        $mpdf->WriteHTML($listaPropostas);

        if ($usuario_logado->tipoPessoa != 9)
            $mpdf->Output();
        else {
            $nom = "Relatorio1_" . $usuario_logado->idPessoa;
            $ident = $this->usuario_model->adiciona_relatorio($usuario_logado->idPessoa, $nom);
            $mpdf->Output("configuracoes/$nom.pdf", "F");
            $this->alert("Arquivo gerado, efetue o pagamento para ter acesso ao arquivo.");
            $this->encaminha('../usuario/compra_relatorio?id=' . $ident . '&nome=' . $nom);
        }
        /*
         * $pdf = new pdf_model(); $pdf->insereDados($programas); $pdf->SetXY(1,2,true); $pdf->AliasNbPages(); $pdf->SetFont('Times','',16);
         */
        // $pdf->Output();

        die();
    }

    function _encode_string_array($stringArray) {
        $s = mysql_real_escape_string(strtr(base64_encode(addslashes(gzcompress($stringArray, 9))), '+/=', '-_,'));
        return $s;
    }

    function _decode_string_array($stringArray) {
        $s = unserialize(gzuncompress(stripslashes(base64_decode(strtr($stringArray, '-_,', '+/=')))));
        return $s;
    }

    function programas_abertos() {
        $this->load->model('programa_model');
        $this->programa_model->programas_abertos();
        die();
    }

    function lista_programas() {
        $data ['title'] = "Physis - Gestão de Usuários e Propostas";
        $data ['main'] = 'in/listar_programas';
        $this->load->model('programa_model');

        $dataInicio = implode("-", array_reverse(explode("/", $this->input->get_post('Dt_Inicio', TRUE))));
        $dataFim = implode("-", array_reverse(explode("/", $this->input->get_post('Dt_Fim', TRUE))));
        // if (strtotime(str_replace("/","-",$dataFim_Programa))>=strtotime($dataInicio) && strtotime(str_replace("/","-",$dataFim_Programa))<=strtotime($dataFim)){
        $this->load->model('cidades_model');
        $estado = $this->input->get_post('cod_estados', TRUE);
        $estado = $this->obterEstadoNome($estado);
        $cidade = $this->input->get_post('cod_cidades', TRUE);
        $cnpj = $this->cidades_model->obter_cnpj($cidade);
        $orgao = $this->input->get_post('orgao', TRUE);
        $vigencia = $this->input->get_post('vigencia', TRUE);

        if ($cidade == '')
            $data ['listaCidade'] = array();
        else
            $data ['listaCidade'] = $this->programa_model->obter_por_cidade($dataInicio, $dataFim, $cidade, $orgao, '', '', $vigencia);

        $data ['listaEstado'] = $this->programa_model->obter_por_estado($dataInicio, $dataFim, $estado, $orgao, '', '', $vigencia);
        $data ['dados_post'] = $this->input->post(NULL, TRUE);
        $data ['login'] = '';
        $data ['usuario'] = $this->input->get_post('usuario', TRUE);

        $this->load->view('in/template', $data);
    }

    function relatorio() {
        $data ['title'] = "Physis - Gestão de Usuários e Propostas";
        $data ['main'] = 'in/relatorio';
        $this->load->model('programa_model');
        $this->load->model('usuariomodel');
        $logado = true;
        $usuario_logado = $this->usuario_logado;
        $data ['login'] = "Olá, " . $usuario_logado->nome . " - <a href=\"" . base_url() . "index.php/in/login/sair\">Sair</a>";
        $data ['lista'] = null;
        $data ['usuario'] = $this->input->get_post('usuario', TRUE);
        if ($logado == true) {
            if ($this->input->get_post('todas', TRUE) == 1) {
                $data ['lista'] = $this->programa_model->obter_programas_por_usuario_todas($data ['usuario']);
                $data ['todas'] = 1;
            } else {
                $data ['lista'] = $this->programa_model->obter_programas_por_usuario_aceito($data ['usuario']);
                $data ['todas'] = 0;
            }
        }
        $this->load->view('in/template', $data);
    }

    function gerapdf() {
        // $this->load->library('FPDF');
        $this->load->library('mPDF');
        // $this->load->model('pdf_model');

        $data ['title'] = "Physis - Gestão de Usuários e Propostas";
        $data ['main'] = 'in/relatorio';
        $this->load->model('programa_model');
        $this->load->model('usuariomodel');
        $logado = true;
        $usuario_logado = $this->usuario_logado;
        $programas = null;
        if ($logado == true) {
            if ($this->input->get_post('todas', TRUE) == 1)
                $programas = $this->programa_model->obter_programas_por_usuario_todas($this->input->get_post('usuario', TRUE));
            else
                $programas = $this->programa_model->obter_programas_por_usuario_aceito($this->input->get_post('usuario', TRUE));
        }
        ob_start(); // inicia o buffer
        $tabela = utf8_encode($tabela);

        $mpdf = new mPDF ();
        $mpdf->allow_charset_conversion = true;
        $mpdf->charset_in = 'UTF-8';
        $tabela = "PHYSIS BRASIL CONSULTORIA E ASSESSORIA SOLIDÁRIA";
        foreach ($programas as $programa) {
            $tabela .= "<br /><br /><table style=\"font-size: 13px;\">";
            $tabela .= "<tr><td><b>CÓDIGO DO PROGRAMA:</b></td><td colspan=3>" . $programa->codigo . "</td></tr>";
            $tabela .= "<tr><td><b>ÓRGÃO SUPERIOR: </b></td><td colspan=3 bgcolor=\"grey\">" . $programa->orgao . "</td></tr>";
            $tabela .= "<tr><td><b>ÓRGÃO PROVENENTE: </b></td><td colspan=3>" . $programa->orgao_vinculado . "</td></tr>";
            $tabela .= "<tr><td><b>INÍCIO VIGÊNCIA: </b></td><td>" . implode("/", array_reverse(explode("-", $programa->data_inicio))) . "</td>";
            $tabela .= "<td><b>FIM VIGÊNCIA: </b> " . implode("/", array_reverse(explode("-", $programa->data_fim))) . "</td>";
            $tabela .= "<td><b>QUALIFICAÇÃO: </b> " . $programa->qualificacao . "</td></tr>";
            $tabela .= "<tr><td><b>PROGRAMA: </b></td><td colspan=3>" . $programa->nome . "</td></tr>";
            $tabela .= "</table> <font style=\"font-size: 13px;\">";
            $tabela .= $programa->descricao . "<br>";
            $tabela .= "<b>Obs.: </b> " . $programa->observacao . "<br>";
            $tabela .= "<b>INDICADO PARA: </b> " . $programa->atende . "<br></font>";
        }
        $mpdf->WriteHTML($tabela);
        $mpdf->Output();
        /*
         * $pdf = new pdf_model(); $pdf->insereDados($programas); $pdf->SetXY(1,2,true); $pdf->AliasNbPages(); $pdf->SetFont('Times','',16);
         */
        // $pdf->Output();

        die();
    }

    function encaminha($url) {
        echo "<script type='text/javascript'>
	window.location='" . $url . "';
	</script>";
    }

    function alert($text) {
        echo "<script type='text/javascript'>alert('" . utf8_decode($text) . "');</script>";
    }

    function voltaPagina() {
        echo "<script type='text/javascript'>history.back();</script>";
    }

    function getTextBetweenTags($string, $tag1, $tag2) {
        $pattern = "/$tag1([\w\W]*?)$tag2/";
        preg_match_all($pattern, $string, $matches);
        return $matches [1];
    }

    function imprimeDetalhePrograma_bd($pag, $orgao, $anterior) {
        $superior = '';
        $validacao = array();

        foreach ($anterior as $key => $aux) {
            $this->autentica_siconv->new_obter_pagina($aux, $this->login_siconv, $this->cookie_file_path);
            // echo $aux." ant<br />";
        }

        $url1 = $this->autentica_siconv->new_obter_pagina($pag, $this->login_siconv, $this->cookie_file_path);

        // echo $url1;
        $url1 = $this->removeSpaceSurplus($url1);
        preg_match_all("/href\=\"([a-zA-Z_\.0-9\/\-\! :\&\-\;\@\$\=\?]*)\"/i", $url1, $matches1);

        foreach ($matches1 [1] as $key => $pag1) {
            if (strstr($pag1, 'ResultadoDaConsultaDeProgramasDeConvenioDetalhar') !== false) {
                $this->autentica_siconv->new_obter_pagina($pag, $this->login_siconv, $this->cookie_file_path);
                // echo $pag.".22222<br />";
                $url1_ = $this->autentica_siconv->new_obter_pagina("https://www.convenios.gov.br" . $pag1, $this->login_siconv, $this->cookie_file_path);
                // echo "https://www.convenios.gov.br".$pag1."<br />";
                $url_sem_espaco = $this->removeSpaceSurplus($url1_);

                $orgao_vinculado = '';
                $orgao = '';
                $codigo = $this->getTextBetweenTags($url_sem_espaco, "Código do Programa<\/td> <td class=\"field\">", "<\/td>");
                // testando erros na página antes de processar
                if (isset($codigo [0]) === false) {
                    // echo $url1_;
                    $this->autentica_siconv->new_init_siconv_do_login($this->login_usuario, $this->senha, $this->login_siconv, $this->cookie_file_path);
                    foreach ($anterior as $key => $aux)
                        $this->autentica_siconv->new_obter_pagina($aux, $this->login_siconv, $this->cookie_file_path);
                    $this->autentica_siconv->new_obter_pagina($pag, $this->login_siconv, $this->cookie_file_path);
                    $url1_ = $this->autentica_siconv->new_obter_pagina("https://www.convenios.gov.br" . $pag1, $this->login_siconv, $this->cookie_file_path);
                    // echo $url1_;
                    $url_sem_espaco = $this->removeSpaceSurplus($url1_);
                    $codigo = $this->getTextBetweenTags($url_sem_espaco, "Código do Programa<\/td> <td class=\"field\">", "<\/td>");
                }

                $sup = $this->getTextBetweenTags($url_sem_espaco, "Órgão<\/td> <td class=\"field\">", "<\/td>");
                $exec = $this->getTextBetweenTags($url_sem_espaco, "Órgão Vinculado<\/td> <td class=\"field\">", "<\/td>");
                $qualificacao = $this->getTextBetweenTags($url_sem_espaco, "Qualificação da proposta<\/td> <td class=\"field\">", "<\/td>");
                $atende = $this->getTextBetweenTags($url_sem_espaco, "Programa Atende a<\/td> <td class=\"field\">", "<\/td>");
                $nome = $this->getTextBetweenTags($url_sem_espaco, "Nome do Programa<\/td> <td class=\"field\">", "<\/td>");
                $descricao = $this->getTextBetweenTags($url_sem_espaco, "Descrição <\/td> <tr> <td class=fieldCaixa colspan=2>", "<\/td>");
                $observacao = $this->getTextBetweenTags($url_sem_espaco, "Observação <\/tr> <tr> <td class=fieldCaixa colspan=2>", "<\/td>");

                $data_inicio = $this->getTextBetweenTags($url_sem_espaco, "\"dataInicioVigencia\" value=\"", "\" onmouseup");
                if (count($data_inicio) == 0)
                    $data_inicio = $this->getTextBetweenTags($url_sem_espaco, "\"dataInicioBeneficiarioEspecifico\" value=\"", "\" onmouseup");
                else if (trim($data_inicio [0]) == '')
                    $data_inicio = array();
                if (count($data_inicio) == 0)
                    $data_inicio = $this->getTextBetweenTags($url_sem_espaco, "\"dataInicioEmendaParlamentar\" value=\"", "\" onmouseup");
                else if (trim($data_inicio [0]) == '')
                    $data_inicio = array();
                if (count($data_inicio) == 0)
                    $data_inicio = $this->getTextBetweenTags($url_sem_espaco, "\"dataDeDisponibilizacao\" value=\"", "\" onmouseup");
                else if (trim($data_inicio [0]) == '')
                    $data_inicio = array();

                $data_fim = $this->getTextBetweenTags($url_sem_espaco, "\"dataFimdeVigencia\" value=\"", "\" onmouseup");
                if (count($data_fim) == 0)
                    $data_fim = $this->getTextBetweenTags($url_sem_espaco, "\"dataFimBeneficiarioEspecifico\" value=\"", "\" onmouseup");
                else if (trim($data_fim [0]) == '')
                    $data_fim = array();
                if (count($data_fim) == 0)
                    $data_fim = $this->getTextBetweenTags($url_sem_espaco, "\"dataFimEmendaParlamentar\" value=\"", "\" onmouseup");
                else if (trim($data_fim [0]) == '')
                    $data_fim = array();
                if (count($data_fim) == 0)
                    $data_fim = $this->getTextBetweenTags($url_sem_espaco, "\"dataDeDisponibilizacao\" value=\"", "\" onmouseup");
                else if (trim($data_fim [0]) == '')
                    $data_fim = array();

                $ano_disponibilizacao = $this->getTextBetweenTags($url_sem_espaco, "\"dataDeDisponibilizacao\" value=\"", "\" onmouseup");
                if (trim($ano_disponibilizacao [0]) == '')
                    $ano_disponibilizacao = $data_inicio;
                $estados = $this->getTextBetweenTags($url_sem_espaco, "Estados Habilitados<\/td> <td class=\"field\">", "<\/td>");

                if (isset($sup [0]) !== false) {
                    $orgao = strtok($sup [0], "-");
                    $orgao = trim(strtok("-"));
                }

                if (isset($exec [0]) !== false) {
                    $orgao_vinculado = strtok($exec [0], "-");
                    $orgao_vinculado = trim(strtok("-"));
                } else {
                    $orgao_vinculado = $orgao;
                }

                $data = array(
                    'link' => "https://www.convenios.gov.br" . $pag1,
                    'codigo' => trim($codigo [0]),
                    'nome' => trim($nome [0]),
                    'orgao' => trim($orgao),
                    'orgao_vinculado' => trim($orgao_vinculado),
                    'qualificacao' => trim($qualificacao [0]),
                    'atende' => trim($atende [0]),
                    'descricao' => trim($descricao [0]),
                    'observacao' => trim($observacao [0]),
                    'data_inicio' => implode("-", array_reverse(explode("/", trim($data_inicio [0])))),
                    'data_fim' => implode("-", array_reverse(explode("/", trim($data_fim [0])))),
                    'ano' => substr(trim($ano_disponibilizacao [0]), - 4),
                    'estados' => trim($estados [0])
                );

                $inserido = $this->programa_model->add_records($data);

                $cidades_habilitadas_beneficiario = $this->getTextBetweenTags($url_sem_espaco, "<div class=\"cnpjBeneficiario\">", "<\/div>");
                $valor_beneficiario = $this->getTextBetweenTags($url_sem_espaco, "<\/td> <td> <div class=\"valorRepassePropostaFormatado\">", "<\/div>");
                $nome_cidades = $this->getTextBetweenTags($url_sem_espaco, "<\/td> <td> <div class=\"nome\">", "<\/div>");
                $emenda = $this->getTextBetweenTags($url_sem_espaco, "<td> <div class=\"numeroEmenda\">", "<\/div>");
                $parlamentar = $this->getTextBetweenTags($url_sem_espaco, "<\/td> <td> <div class=\"nomeParlamentar\">", "<\/div>");
                $cidades_habilitadas_especifico = $this->getTextBetweenTags($url_sem_espaco, "<div class=\"cnpj\">", "<\/div>");
                $laco_inicial = 0; // para pegar os valores dos dois laços, caso precise
                foreach ($cidades_habilitadas_beneficiario as $chave => $cidade_habilitada) {
                    $parl = null;
                    $ement = null;
                    if (isset($parlamentar [$chave]) !== false) {
                        $parl = trim($parlamentar [$chave]);
                        $ement = trim($emenda [$chave]);
                    }
                    $data = array(
                        'codigo_programa' => trim($codigo [0]),
                        'cnpj' => trim($cidade_habilitada),
                        'nome' => trim($nome_cidades [$chave]),
                        'emenda' => $ement,
                        'parlamentar' => $parl,
                        'valor' => trim($valor_beneficiario [$chave])
                    );

                    $inserido = $this->programa_model->insere_beneficiario($data);
                    $laco_inicial ++;
                }

                foreach ($cidades_habilitadas_especifico as $chave => $cidade_habilitada) {
                    $parl = null;
                    $ement = null;
                    if (isset($parlamentar [$laco_inicial]) !== false) {
                        $parl = trim($parlamentar [$laco_inicial]);
                        $ement = trim($emenda [$laco_inicial]);
                    }
                    $data = array(
                        'codigo_programa' => trim($codigo [0]),
                        'cnpj' => trim($cidade_habilitada),
                        'nome' => trim($nome_cidades [$laco_inicial]),
                        'emenda' => $ement,
                        'parlamentar' => $parl,
                        'valor' => trim($valor_beneficiario [$laco_inicial])
                    );

                    $inserido = $this->programa_model->insere_beneficiario($data);
                    $laco_inicial ++;
                }
                $paginas_beneficiario = $this->getTextBetweenTags($url_sem_espaco, "id=\"cnpjsBeneficiarioEspecifico\" class=\"table\"> <span class=\"pagelinks\">Página ", "<\/span>");
                $paginas_especifico = $this->getTextBetweenTags($url_sem_espaco, "id=\"cnpjsEmendaParlamentar\" class=\"table\"> <span class=\"pagelinks\">Página ", "<\/span>");
                // echo "https://www.convenios.gov.br".$pag1." - codigo: ".$codigo[0]."<br />";
                $complemento_url = 'cnpjsBeneficiarioEspecifico';
                $tok = 0;
                if (isset($paginas_beneficiario [0]) || isset($paginas_especifico [0])) {
                    if (isset($paginas_beneficiario [0])) {
                        $tok = strtok($paginas_beneficiario [0], "(");
                        $tok = strtok($tok, "de");
                        $tok = (int) trim(strtok("de"));
                    }
                    $maior = $tok;
                    if (isset($paginas_especifico [0])) {
                        $tok = strtok($paginas_especifico [0], "(");
                        $tok = strtok($tok, "de");
                        $tok = (int) trim(strtok("de"));
                    }
                    // para formar a string que percorrerá os cnpjs que contem duas categorias, a maior percorre tudo
                    if ($maior < $tok) {
                        $maior = $tok;
                        $complemento_url = 'cnpjsEmendaParlamentar';
                    }
                    // pegando o id para as subpaginas
                    $pos = strripos($pag1, "id=");
                    $id = '';
                    if ($pos !== false) {
                        $id = substr($pag1, $pos + 3);
                    }
                    $flag1 = true;

                    for ($i = 2; $i <= $maior && $flag1 == true; $i ++) {

                        $this->autentica_siconv->new_obter_pagina("https://www.convenios.gov.br" . $pag1, $this->login_siconv, $this->cookie_file_path);
                        $url1_aux = $this->autentica_siconv->new_obter_pagina("https://www.convenios.gov.br/siconv/DetalharPrograma/DetalharPrograma.do?id=$id&d-16544-t=$complemento_url&d-16544-p=$i&d-16544-g=$i", $this->login_siconv, $this->cookie_file_path);

                        // echo "https://www.convenios.gov.br".$pag1."<br />";
                        // echo "https://www.convenios.gov.br/siconv/DetalharPrograma/DetalharPrograma.do?id=$id&d-16544-t=$complemento_url&d-16544-p=$i&d-16544-g=$i"."<br />";
                        $url_sem_espaco = $this->removeSpaceSurplus($url1_aux);
                        $cidades_habilitadas_beneficiario = $this->getTextBetweenTags($url_sem_espaco, "<div class=\"cnpjBeneficiario\">", "<\/div>");
                        $valor_beneficiario = $this->getTextBetweenTags($url_sem_espaco, "<\/td> <td> <div class=\"valorRepassePropostaFormatado\">", "<\/div>");
                        $nome_cidades = $this->getTextBetweenTags($url_sem_espaco, "<\/td> <td> <div class=\"nome\">", "<\/div>");
                        $emenda = $this->getTextBetweenTags($url_sem_espaco, "<td> <div class=\"numeroEmenda\">", "<\/div>");
                        $parlamentar = $this->getTextBetweenTags($url_sem_espaco, "<\/td> <td> <div class=\"nomeParlamentar\">", "<\/div>");
                        $cidades_habilitadas_especifico = $this->getTextBetweenTags($url_sem_espaco, "<div class=\"cnpj\">", "<\/div>");

                        $laco_inicial = 0; // para pegar os valores dos dois laços, caso precise
                        foreach ($cidades_habilitadas_beneficiario as $chave => $cidade_habilitada) {
                            $parl = null;
                            $ement = null;
                            if (isset($parlamentar [$chave]) !== false) {
                                $parl = trim($parlamentar [$chave]);
                                $ement = trim($emenda [$chave]);
                            }
                            $data = array(
                                'codigo_programa' => trim($codigo [0]),
                                'cnpj' => trim($cidade_habilitada),
                                'nome' => trim($nome_cidades [$chave]),
                                'emenda' => $ement,
                                'parlamentar' => $parl,
                                'valor' => trim($valor_beneficiario [$chave])
                            );

                            $inserido = $this->programa_model->insere_beneficiario($data);
                            $laco_inicial ++;
                        }

                        foreach ($cidades_habilitadas_especifico as $chave => $cidade_habilitada) {
                            $parl = null;
                            $ement = null;
                            if (isset($parlamentar [$laco_inicial]) !== false) {
                                $parl = trim($parlamentar [$laco_inicial]);
                                $ement = trim($emenda [$laco_inicial]);
                            }
                            $data = array(
                                'codigo_programa' => trim($codigo [0]),
                                'cnpj' => trim($cidade_habilitada),
                                'nome' => trim($nome_cidades [$laco_inicial]),
                                'emenda' => $ement,
                                'parlamentar' => $parl,
                                'valor' => trim($valor_beneficiario [$laco_inicial])
                            );

                            $inserido = $this->programa_model->insere_beneficiario($data);
                            $laco_inicial ++;
                        }
                    }
                }
                /*
                 * if (trim($codigo[0]) == '5300020080002'){ echo "teste"; die(); }
                 */
            } else if (strstr($pag1, 'error-page') !== false) {
                echo "Siconv encaminhando para página com erro: " . $pag . "<br />";
            }
        }
        /*
         * $anos = $this->getTextBetweenTags($url1, "<div class=\"anoPrograma\">", "<\/div>"); foreach($anos as $key => $ano){ if (isset($listaPrograma[$superior][$orgao][$ano]) === false) $listaPrograma[$superior][$orgao][$ano] = 1; else $listaPrograma[$superior][$orgao][$ano]++; } return $listaPrograma;
         */
    }

    function imprimeDetalhePrograma($pag, $orgao, $listaPrograma, $anterior) {
        $superior = '';
        $flag = false; // verificar se pagina ja pegou o superior
        $validacao = array();

        // $this->obter_paginaLogin();
        foreach ($anterior as $key => $aux) {
            $this->autentica_siconv->new_obter_pagina($aux, $this->login_siconv, $this->cookie_file_path);
            // echo $aux." ant<br />";
        }

        // $this->obter_pagina($remotePageUrl);
        $url1 = $this->autentica_siconv->new_obter_pagina($pag, $this->login_siconv, $this->cookie_file_path);
        // echo $anterior."<br>";
        // echo $pag." p1<br>";
        $url1 = $this->removeSpaceSurplus($url1);
        // echo "1<br>";
        // echo $url1;
        preg_match_all("/href\=\"([a-zA-Z_\.0-9\/\-\! :\&\-\;\@\$\=\?]*)\"/i", $url1, $matches1);
        // preg_match_all("/href\=\"(*)\"/i", $url1, $matches1);
        // echo "2<br>";
        foreach ($matches1 [1] as $key => $pag1) {
            if ($flag == true) {
                // echo "flag";
                break;
            }
            // echo $pag1."..".$key." - 3<br>";
            // if ($pag1 == 'siconv/principal/ListarProgramasPrincipal/ConsultaOrgaosConsultar.do?id=') //erro intermitente de causa desconhecida
            // $listaPrograma = $this->imprimeDetalhePrograma($pag, $orgao, $listaPrograma);
            if (strstr($pag1, 'ResultadoDaConsultaDeProgramasDeConvenioDetalhar') !== false) {
                // $this->obter_paginaLogin();
                $this->autentica_siconv->new_obter_pagina($pag, $this->login_siconv, $this->cookie_file_path);
                $url1_ = $this->autentica_siconv->new_obter_pagina("https://www.convenios.gov.br" . $pag1, $this->login_siconv, $this->cookie_file_paths);
                $url_sem_espaco = $this->removeSpaceSurplus($url1_);
                // echo $pag1." 4<br>";
                $sup = $this->getTextBetweenTags($url_sem_espaco, "Órgão<\/td> <td class=\"field\">", "<\/td>");
                // $superior = $sup[0];
                // echo"-1-";
                if (isset($sup [0]) !== false) {
                    $superior = strtok($sup [0], "-");
                    $superior = trim(strtok("-"));
                    $flag = true;
                    break;
                } else {
                    echo "Página com erro: " . $pag . "<br />";
                    echo "Página com erro: https://www.convenios.gov.br" . $pag1 . "<br />";
                    echo $url1_;
                    /*
                     * $remotePageUrl = 'https://www.convenios.gov.br/siconv/ListarProgramasPrincipal/ListarProgramasPrincipal.do'; //$this->cookie_file_path = tempnam ("/tmp", "CURLCOOKIE".); $this->obter_paginaLogin(); $this->obter_pagina($remotePageUrl); return $this->imprimeDetalhePrograma($pag, $orgao, $listaPrograma, $anterior);
                     */
                    // $listaPrograma = $this->imprimeDetalhePrograma($pag, $orgao, $listaPrograma, $anterior);
                    // echo "Siconv deu um erro fatal!<br />Por favor atualize a página."; die();
                }
                // echo $superior." -5<br>";
            }    // resolvendo problemas de somas erraas
            else if (strstr($pag1, 'error-page') !== false) {
                /*
                 * unlink("application/views/configuracoes/cookie.txt"); $cria = fopen("application/views/configuracoes/cookie.txt","w+"); fclose($cria); $login = '43346880559'; $senha = 'Laisa_M2012'; $url = "https://www.convenios.gov.br/siconv/secure/EntrarLoginValidar.do?login=$login&senha=$senha"; $this->obter_pagina($url);
                 */
                echo "Siconv encaminhando para página com erro: " . $pag . "<br />";
                // $listaPrograma = $this->imprimeDetalhePrograma($pag, $orgao, $listaPrograma);
            }
        }
        // echo "6<br>";
        $anos = $this->getTextBetweenTags($url1, "<div class=\"anoPrograma\">", "<\/div>");
        foreach ($anos as $key => $ano) {
            if (isset($listaPrograma [$superior] [$orgao] [$ano]) === false)
                $listaPrograma [$superior] [$orgao] [$ano] = 1;
            else
                $listaPrograma [$superior] [$orgao] [$ano] ++;
            // echo $superior." - ".$orgao." - ".$key ." - ".$ano." codnao<br>";
        }
        // echo "7<br>";
        return $listaPrograma;
    }

    function imprimeDetalhe($pag, $dataInicio, $dataFim, $estado, $cnpj, $anterior) {
        foreach ($anterior as $key => $aux) {
            $this->autentica_siconv->new_obter_pagina($aux, $this->login_siconv, $this->cookie_file_path);
            // echo $aux." ant<br />";
        }
        // echo $pag." ant<br />";

        $url1 = $this->autentica_siconv->new_obter_pagina($pag, $this->login_siconv, $this->cookie_file_path);
        // echo $url1;
        $url_sem_espaco = $this->removeSpaceSurplus($url1);
        $dataFim_Programa1 = $this->getTextBetweenTags($url_sem_espaco, $this->removeSpaceSurplus("\"dataFimdeVigencia\" value=\""), "\"");
        if (count($dataFim_Programa1) > 0 && trim($dataFim_Programa1 [0]) != '') {
            $dataFim_Programa = $dataFim_Programa1 [0];
        } else {
            $dataFim_Programa2 = $this->getTextBetweenTags($url_sem_espaco, $this->removeSpaceSurplus("\"dataFimBeneficiarioEspecifico\" value=\""), "\"");
            if (count($dataFim_Programa2) > 0 && trim($dataFim_Programa2 [0]) != '') {
                $dataFim_Programa = $dataFim_Programa2 [0]; // echo ".1.";
            } else {
                $dataFim_Programa2 = $this->getTextBetweenTags($url_sem_espaco, $this->removeSpaceSurplus("\"dataFimEmendaParlamentar\" value=\""), "\"");
                if (count($dataFim_Programa2) > 0 && trim($dataFim_Programa2 [0]) != '') {
                    $dataFim_Programa = $dataFim_Programa2 [0]; // echo ".2.";
                } else {
                    $dataFim_Programa2 = $this->getTextBetweenTags($url_sem_espaco, $this->removeSpaceSurplus("\"dataDeDisponibilizacao\" value=\""), "\"");
                    if (count($dataFim_Programa2) > 0 && trim($dataFim_Programa2 [0]) != '') {
                        $dataFim_Programa = $dataFim_Programa2 [0]; // echo ".3.";
                    } else {
                        echo "Página com erro: " . $pag . "<br />";
                        $remotePageUrl = 'https://www.convenios.gov.br/siconv/ListarProgramasPrincipal/ListarProgramasPrincipal.do';
                        // $this->cookie_file_path = tempnam ("/tmp", "CURLCOOKIE".rand());
                        $this->autentica_siconv->new_init_siconv_do_login($this->login_usuario, $this->senha, $this->login_siconv, $this->cookie_file_path);
                        $this->autentica_siconv->new_obter_pagina($remotePageUrl, $this->login_siconv, $this->cookie_file_path);
                        $this->imprimeDetalhe($pag, $orgao, $listaPrograma, $anterior);
                    }
                }
            }
        }
        // echo $dataFim_Programa.">=".$dataInicio." && ".$dataFim_Programa."<=".$dataFim."<br>";
        if (strtotime(str_replace("/", "-", $dataFim_Programa)) >= strtotime(str_replace("/", "-", $dataInicio)) && strtotime(str_replace("/", "-", $dataFim_Programa)) <= strtotime(str_replace("/", "-", $dataFim))) {
            // echo ".1.";
            $nome = $this->getTextBetweenTags($url_sem_espaco, "Nome do Programa<\/td> <td class=\"field\">", "<\/td>");
            $codigo = $this->getTextBetweenTags($url_sem_espaco, "Código do Programa<\/td> <td class=\"field\">", "<\/td>");
            $this->codigoEstado [] = trim($codigo [0]);
            $this->pagEstado [] = $pag;
            $this->listaEstado [] = "<a href='" . $pag . "'>" . $codigo [0] . "</a> - " . $nome [0];
            // echo strtotime(str_replace("/","-",$dataFim_Programa)).">=".strtotime(str_replace("/","-",$dataInicio))." && ".strtotime(str_replace("/","-",$dataFim_Programa))."<=".strtotime(str_replace("/","-",$dataFim))."<br>";
            // echo $dataFim_Programa." -dt- ".$dataInicio.",".$dataFim."<br>";
            $estados_habilitados = $this->getTextBetweenTags($url_sem_espaco, "Estados Habilitados<\/td> <td class=\"field\">", "<\/td>");
            // echo $estado." -Habilitado ".count($estados_habilitados).".".$estados_habilitados[0]."<br />";
            // echo $indiciePagina."https://www.convenios.gov.br".$pag1."<br>";
            /*
             * if (strstr($estados_habilitados[0], $estado) !== false || strstr($estados_habilitados[0], 'Todos os Estados estão Aptos') !== false ){ //echo $indiciePagina."https://www.convenios.gov.br".$pag1." estado<br>"; $this->codigoEstado[] = $codigo[0]; $this->listaEstado[] = "<a href='https://www.convenios.gov.br".$pag."'>".$codigo[0]."</a> - ".$nome[0]; }
             */
            // echo ".2.";
            $cidades_habilitadas = $this->getTextBetweenTags($url_sem_espaco, "class=\"cnpjBeneficiario\">", "<\/div>");
            if (count($cidades_habilitadas) == 0)
                $cidades_habilitadas = $this->getTextBetweenTags($url_sem_espaco, "class=\"cnpj\">", "<\/div>");
            $flag = true;
            // echo count($cidades_habilitadas)."--cidade--<br>";
            foreach ($cidades_habilitadas as $cidade_habilitada) {
                foreach ($cnpj as $cidade) {
                    if (strstr($cidade_habilitada, $cidade ['cnpj']) !== false) {
                        // echo $indiciePagina."https://www.convenios.gov.br".$pag1." cidade<br>";
                        $this->codigoCidade [] = trim($codigo [0]);
                        $this->pagCidade [] = $pag;
                        $this->listaCidade [] = "<a href='" . $pag . "'>" . $codigo [0] . "</a> - " . $nome [0];
                        // echo $codigo[0]; die();
                        $flag = false;
                        break;
                    }
                }
                if ($flag == false)
                    break; // saindo do foreach do meio
            }
            // echo ".3.";
            $paginas = $this->getTextBetweenTags($url1, "span class=\"pagelinks\">Página ", "<\/span>");
            if (isset($paginas [0])) {
                $tok = strtok($paginas [0], "(");
                $tok = strtok($tok, "de");
                $tok = (int) trim(strtok("de"));
                // pegando o id para as subpaginas
                $pos = strripos($pag, "id");
                $id = '';
                if ($pos !== false) {
                    $id = substr($dataInicio, $pos + 2);
                }
                $flag1 = true;
                // echo $id." - id<br>";
                for ($i = 2; $i <= $tok && $flag1 == true; $i ++) {
                    // $this->obter_paginaLogin();
                    $this->autentica_siconv->new_obter_pagina("https://www.convenios.gov.br" . $pag, $this->login_siconv, $this->cookie_file_path);
                    // echo "https://www.convenios.gov.br".$pag."<br />";
                    // echo "https://www.convenios.gov.br/siconv/DetalharPrograma/DetalharPrograma.do?id=$id&d-16544-t=cnpjsEmendaParlamentar&d-16544-p=$i&d-16544-g=$i"."<br />";
                    $url1_aux = $this->autentica_siconv->new_obter_pagina("https://www.convenios.gov.br/siconv/DetalharPrograma/DetalharPrograma.do?id=$id&d-16544-t=cnpjsEmendaParlamentar&d-16544-p=$i&d-16544-g=$i", $this->login_siconv, $this->cookie_file_path);
                    $cidades_habilitadas = $this->getTextBetweenTags($url1_aux, "class=\"cnpjBeneficiario\">", "<\/div>");
                    if (count($cidades_habilitadas) == 0)
                        $cidades_habilitadas = $this->getTextBetweenTags($url1_aux, "class=\"cnpj\">", "<\/div>");
                    foreach ($cidades_habilitadas as $cidade_habilitada) {
                        foreach ($cnpj as $cidade) {
                            if (strstr($cidade_habilitada, $cidade ['cnpj']) !== false) {
                                // echo $indiciePagina."https://www.convenios.gov.br".$pag1." cidade1<br>";
                                $this->codigoCidade [] = trim($codigo [0]);
                                $this->pagCidade [] = $pag;
                                $this->listaCidade [] = "<a href='" . $pag . "'>" . $codigo [0] . "</a> - " . $nome [0];
                                // echo $codigo[0]; die();
                                $flag1 = false;
                                break;
                            }
                        }
                        if ($flag1 == false)
                            break; // saindo do foreach do meio
                    }
                }
            }
            // echo ".4.";
        }
        // echo ".5.";
    }

    function obter_paginaLogin($id = null) {
        $login = $this->login_usuario;
        $senha = $this->senha;
        $url = "https://www.convenios.gov.br/siconv/secure/EntrarLoginValidar.do?login=$login&senha=$senha";
        // echo file_get_contents($url); die();
        // $cookie_file_path = "application/views/configuracoes/cookie.txt";
        $cookie_file_path = $this->cookie_file_path;
        $agent = "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.1) Gecko/20061204 Firefox/2.0.0.1";
        $ch = curl_init();
        // extra headers
        $headers [] = "Accept: */*";
        $headers [] = "Connection: Keep-Alive";

        // basic curl options for all requests
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_USERAGENT, $agent);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file_path);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file_path);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);

        // set first URL
        curl_setopt($ch, CURLOPT_URL, $url);

        // execute session to get cookies and required form inputs
        $content = curl_exec($ch);

        curl_close($ch);

        if (strstr($content, 'ELIUMAR CARLOS DE SOUSA SILVA') === false) {
            if ($id == 1) {
                echo "erro na página interna do siconv, entre em contato com o administrador.";
                die();
            }
            $this->senha = 'Laisa_M2012';
            $this->cookie_file_path = tempnam("/tmp", "CURLCOOKIE1" . rand());
            return $this->autentica_siconv->new_init_siconv_do_login($this->login_usuario, $this->senha, $this->login_siconv, $this->cookie_file_path);
        }

        return $content;
    }

    function obter_pagina($url) {
        // $cookie_file_path = "application/views/configuracoes/cookie.txt";
        $cookie_file_path = $this->cookie_file_path;

        $agent = "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.1) Gecko/20061204 Firefox/2.0.0.1";
        $ch = curl_init();
        // extra headers
        $headers [] = "Accept: */*";
        $headers [] = "Connection: Keep-Alive";

        // basic curl options for all requests
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_USERAGENT, $agent);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file_path);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file_path);

        // set first URL
        curl_setopt($ch, CURLOPT_URL, $url);

        // execute session to get cookies and required form inputs
        $content = curl_exec($ch);
        curl_close($ch);

        return $content;
    }

    function removeSpaceSurplus($str) {
        return preg_replace("/\s+/", ' ', trim($str));
    }

    function obterNomeOrgao($id, $numero) {
        // return 'teste';
        // echo ".".$id.".<br>";
        $numero = trim($numero);
        $remotePageUrl = 'https://www.convenios.gov.br/siconv/proposta/CopiarDadosProposta/CopiarDadosProposta.do';
        $remotePageUrl1 = 'https://www.convenios.gov.br/siconv/CopiarDadosProposta/ConsultarPropostasEConveniosConsultar.do?numeroProposta=' . $numero;

        $nome = '';
        if ((int) $id > 0) {
            $jsonurl = "http://api.convenios.gov.br/siconv/dados/programa/$id.json";
            $id_orgao = null;
            if ($json = file_get_contents($jsonurl)) {
                $json_output = json_decode($json);
                $id_orgao = $json_output->programas [0]->orgao_superior->Orgao->id;
            }

            $jsonurl = "http://api.convenios.gov.br/siconv/dados/orgao/$id_orgao.json";

            if ($json = file_get_contents($jsonurl)) {
                if (trim($json) == '' || !isset($json)) {
                    $jsonurl = "http://api.convenios.gov.br/siconv/dados/orgao/$id_orgao.html";
                    $json = file_get_contents($jsonurl);
                    $json = $this->removeSpaceSurplus($json);
                    $nome = $this->getTextBetweenTags($json, "<dt>Nome<\/dt> <dd>", "<\/dd>");
                    $nome = $nome [0];
                } else {
                    $json_output = json_decode($json);
                    $nome = $json_output->orgaos [0]->nome;
                }
            }
        }

        // caso não encontre em local nenhum, varre direto na página do siconv
        if (trim($nome) == '' || trim($nome) == 'NÃO SE APLICA') {

            $this->autentica_siconv->new_init_siconv_do_login($this->login_usuario, $this->senha, $this->login_siconv, $this->cookie_file_path);
            // obter_pagina('https://www.convenios.gov.br/portal/acessoLivre.html');
            $documento = $this->autentica_siconv->new_obter_pagina($remotePageUrl, $this->login_siconv, $this->cookie_file_path);
            $documento1 = $this->autentica_siconv->new_obter_pagina($remotePageUrl1, $this->login_siconv, $this->cookie_file_path);

            $idProposta = '0';
            preg_match_all("/href\=\"([a-zA-Z_\.0-9\/\-\! :\&\-\;\@\$\=\?]*)\"/i", $documento1, $matches1);
            foreach ($matches1 [1] as $pag1) {
                if (strstr($pag1, 'ResultadoConsultaPropostaSelecionar') !== false) {
                    $tok = explode("idProposta=", $pag1);
                    $idProposta = $tok [1];
                }
            }

            $remotePageUrl2 = 'https://www.convenios.gov.br/siconv/ConsultarProposta/ResultadoDaConsultaDePropostaDetalharProposta.do?idProposta=' . $idProposta;
            $documento2 = $this->autentica_siconv->new_obter_pagina($remotePageUrl2, $this->login_siconv, $this->cookie_file_path);
            $url_sem_espaco = $this->removeSpaceSurplus($documento2);
            $sup = $this->getTextBetweenTags($url_sem_espaco, "<td class=\"label\">Órgão<\/td> <td class=\"field\" colspan=\"4\">", "<\/td>");
            $superior = strtok($sup [0], "-");
            $superior = trim(strtok("-"));

            return $superior;
        }

        return $nome;
    }

    function obterNomeSituacao($id, $numero) {
        // return 'teste';
        // echo $numero.".".$id.".<br>";
        $jsonurl = "http://api.convenios.gov.br/siconv/dados/proposta/$numero.json";
        $nome = '';
        $remotePageUrl = 'https://www.convenios.gov.br/siconv/proposta/CopiarDadosProposta/CopiarDadosProposta.do';
        $remotePageUrl1 = 'https://www.convenios.gov.br/siconv/CopiarDadosProposta/ConsultarPropostasEConveniosConsultar.do?numeroProposta=' . $numero;

        if ($json = file_get_contents($jsonurl)) {
            $json_output = json_decode($json);
            $programa = $json_output->propostas [0]->programas [0]->associacao [0]->Programa->id;
            $numero = $json_output->propostas [0]->numero_proposta;

            $remotePageUrl1 = 'https://www.convenios.gov.br/siconv/CopiarDadosProposta/ConsultarPropostasEConveniosConsultar.do?numeroProposta=' . $numero;
            if ($id != '0') {
                $id = trim($id);

                $jsonurl = "http://api.convenios.gov.br/siconv/dados/situacao_proposta/$id.json";
                // echo $jsonurl."<br>";
                $json = file_get_contents($jsonurl);
                if (trim($json) == '' || !isset($json)) {
                    $jsonurl = "http://api.convenios.gov.br/siconv/dados/situacao_proposta/$id.html";
                    $json = file_get_contents($jsonurl);
                    $json = $this->removeSpaceSurplus($json);
                    $nome = $this->getTextBetweenTags($json, "<dt>Nome<\/dt> <dd>", "<\/dd>");
                    $nome = $nome [0];
                } else {
                    $json_output = json_decode($json);
                    $nome = $json_output->situacaopropostas [0]->nome;
                }
            }
        }

        // caso não encontre em local nenhum, varre direto na página do siconv
        if (trim($nome) == '' || trim($nome) == 'NÃO SE APLICA') {
            // echo trim($nome)."...<br />";
            $this->autentica_siconv->new_init_siconv_do_login($this->login_usuario, $this->senha, $this->login_siconv, $this->cookie_file_path);
            // obter_pagina('https://www.convenios.gov.br/portal/acessoLivre.html');
            $documento = $this->autentica_siconv->new_obter_pagina($remotePageUrl, $this->login_siconv, $this->cookie_file_path);
            $documento1 = $this->autentica_siconv->new_obter_pagina($remotePageUrl1, $this->login_siconv, $this->cookie_file_path);

            $idProposta = '0';
            preg_match_all("/href\=\"([a-zA-Z_\.0-9\/\-\! :\&\-\;\@\$\=\?]*)\"/i", $documento1, $matches1);
            foreach ($matches1 [1] as $pag1) {
                if (strstr($pag1, 'ResultadoConsultaPropostaSelecionar') !== false) {
                    $tok = explode("idProposta=", $pag1);
                    $idProposta = $tok [1];
                }
            }
            $remotePageUrl2 = 'https://www.convenios.gov.br/siconv/ConsultarProposta/ResultadoDaConsultaDePropostaDetalharProposta.do?idProposta=' . $idProposta;
            $documento2 = $this->autentica_siconv->new_obter_pagina($remotePageUrl2, $this->login_siconv, $this->cookie_file_path);
            $url_sem_espaco = $this->removeSpaceSurplus($documento2);
            $sup = $this->getTextBetweenTags($url_sem_espaco, "Situação<\/td> <td colspan=\"4\"> <table cellpadding=\"0\" cellspacing=\"0\"> <td class=\"field\" width=\"40\%\">", "<\/td>");
            return strtoupper($sup [0]);
        }
        return $nome;
    }

    function obterOrgaoSuperior($id) {
        $nome = '';
        $id = trim($id);
        $jsonurl = "http://api.convenios.gov.br/siconv/dados/proposta/$id.json";

        $json = file_get_contents($jsonurl);
        // echo $jsonurl;
        if (trim($json) == '' || !isset($json)) {
            $jsonurl = "http://api.convenios.gov.br/siconv/dados/proposta/$id.html";
            $json = file_get_contents($jsonurl);
            // echo $jsonurl."123<br />";
            $nome = $this->getTextBetweenTags($json, "\">Programa ", ": ");
            $numero = $this->getTextBetweenTags($json, "Numero Proposta<\/dt>
          <dd>", "<\/dd>");
            // if (trim($nome[0]) == '' || !isset($nome[0])) $nome[0] = obterOrgaoSuperior($id);
            // echo "!!".$numero[0]."--".$id."__";
            $nome = $this->obterNomeOrgao(trim($nome [0]), $numero [0]);
        }
        if (trim($nome) == '') {

            $json_output = json_decode($json);
            $programa = $json_output->propostas [0]->programas [0]->associacao [0]->Programa->id;
            $numero = $json_output->propostas [0]->numero_proposta;
            // echo "&&".$numero."**";
            $nome = $this->obterNomeOrgao(trim($programa), $numero);
        }

        return $nome;
    }

    function obterNomePrograma($id) {
        $id = trim($id);
        $jsonurl = "http://api.convenios.gov.br/siconv/dados/programa/$id.json";
        $ch = curl_init();
        $timeout = 15;
        $headers = array(
            "Cache-Control: no-cache"
        );
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_URL, $jsonurl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);
        $json = curl_exec($ch);
        curl_close($ch);
        $numero = '';
        // echo $jsonurl;
        if (trim($json) == '' || !isset($json)) {
            $jsonurl = "http://api.convenios.gov.br/siconv/dados/programa/$id.html";
            $ch = curl_init();
            $timeout = 15;
            $headers = array(
                "Cache-Control: no-cache"
            );
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_URL, $jsonurl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
            curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);
            $json = curl_exec($ch);
            curl_close($ch);
            $json = $this->removeSpaceSurplus($json);
            $numero = $this->getTextBetweenTags($json, "Nome<\/dt> <dd>", "<\/dd>");
            $numero = $numero [0];
        }
        if (trim($numero) == '') {
            $json_output = json_decode($json);
            $numero = $json_output->programas [0]->nome;
        }
        if (trim($numero) == '')
            return $this->obterNomePrograma($id);
        return $numero;
    }

    function obterCodigoPrograma($id) {
        $id = trim($id);
        $jsonurl = "http://api.convenios.gov.br/siconv/dados/programa/$id.json";
        $ch = curl_init();
        $timeout = 15;
        $headers = array(
            "Cache-Control: no-cache"
        );
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_URL, $jsonurl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);
        $json = curl_exec($ch);
        curl_close($ch);
        $numero = '';
        // echo $jsonurl;
        if (trim($json) == '' || !isset($json)) {
            $jsonurl = "http://api.convenios.gov.br/siconv/dados/programa/$id.html";
            $ch = curl_init();
            $timeout = 15;
            $headers = array(
                "Cache-Control: no-cache"
            );
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_URL, $jsonurl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
            curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);
            $json = curl_exec($ch);
            curl_close($ch);
            $json = $this->removeSpaceSurplus($json);
            $numero = $this->getTextBetweenTags($json, "Cód. do Programa no SICONV<\/dt> <dd>", "<\/dd>");
            $numero = $numero [0];
        }
        if (trim($numero) == '') {
            $json_output = json_decode($json);
            $numero = $json_output->programas [0]->cod_programa_siconv;
        }
        if (trim($numero) == '')
            return $this->obterCodigoPrograma($id);
        return $numero;
    }

    function obterNumeroProposta($id) {
        $id = trim($id);
        $jsonurl = "http://api.convenios.gov.br/siconv/dados/proposta/$id.json";
        $ch = curl_init();
        $timeout = 15;
        $headers = array(
            "Cache-Control: no-cache"
        );
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_URL, $jsonurl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);
        $json = curl_exec($ch);
        curl_close($ch);
        $numero = '';
        // echo $jsonurl;
        if (trim($json) == '' || !isset($json)) {
            $jsonurl = "http://api.convenios.gov.br/siconv/dados/proposta/$id.html";
            $ch = curl_init();
            $timeout = 15;
            $headers = array(
                "Cache-Control: no-cache"
            );
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_URL, $jsonurl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
            curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);
            $json = curl_exec($ch);
            curl_close($ch);
            $json = $this->removeSpaceSurplus($json);
            $numero = $this->getTextBetweenTags($json, "Numero Proposta<\/dt> <dd>", "<\/dd>");
            $numero = $numero [0];
        }
        if (trim($numero) == '') {
            $json_output = json_decode($json);
            $numero = $json_output->propostas [0]->numero_proposta;
        }
        if (trim($numero) == '')
            return $this->obterNumeroProposta($id);
        return $numero;
    }

    function ordena_por_estado($listaPropostas) {
        $saida = array();
        foreach ($listaPropostas as $programa) {
            $saida [$programa->orgao] [$programa->codigo] ['nome_programa'] = $programa->descricao;
        }

        return $saida;
    }

    function retorna_propostas_programas($jsonurl, $listaPropostas, $numero_prop, $aleatorio, $cidade) {
        $tmpfname = getcwd() . "/configuracoes/programa" . $aleatorio;

        $handle = fopen($tmpfname, "r");
        if ($handle != false) {
            $listaPropostas1 = fgets($handle);
            if (trim($listaPropostas1) != '') {
                $compressed1 = $this->_decode_string_array($listaPropostas1);
                $listaPropostas = $compressed1;
            }
            fclose($handle);
        }
        $json = file_get_contents($jsonurl, 0, null, null);
        $json_output = json_decode($json);

        if ($json == '') {
            echo "Siconv com problemas internos, por favor tente novamente mais tarde.";
        }

        $count = 0;

        foreach ($json_output->propostas as $proposta) {

            if ($count < $numero_prop) { // para que varra uma vez só
                $count ++;
                continue;
            } else if ($count > $numero_prop) { // para que varra uma vez só
                break;
            }
            // echo $count." - ".$numero_prop." - ".count($json_output->propostas)."<br>";
            $url = "http://api.convenios.gov.br/siconv/id/proposta/" . $proposta->id . ".json";
            $ch = curl_init();
            $timeout = 5;
            $headers = array(
                "Cache-Control: no-cache"
            );
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
            // curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);
            $json = curl_exec($ch);
            curl_close($ch);

            $programa = '';
            if (trim($json) == '' || !isset($json)) {
                $ch = curl_init();
                $timeout = 15;
                $headers = array(
                    "Cache-Control: no-cache"
                );
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_URL, "http://api.convenios.gov.br/siconv/id/proposta/" . $proposta->id . ".html");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
                curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);
                $json = curl_exec($ch);
                curl_close($ch);

                $programa = $this->getTextBetweenTags($json, "<a href\=\"http:\/\/api.convenios.gov.br\/siconv\/id\/programa\/", "\">Programa");
                $programa = $programa [0];
            }
            if (trim($programa) == '') {
                $orgaos = json_decode($json);
                $programa = $this->obterCodigoPrograma($orgaos->propostas [0]->programas [0]->associacao [0]->Programa->id);
            }
            if (trim($programa) == '')
                return $this->retorna_propostas_programas($jsonurl, $listaPropostas, $numero_prop, $aleatorio, $cidade);

            $listaPropostas [$this->obterOrgaoSuperior($proposta->id)] [$programa] [$proposta->id] ['objeto'] = $proposta->objeto_resumido;
            $listaPropostas [$this->obterOrgaoSuperior($proposta->id)] [$programa] [$proposta->id] ['numero'] = $this->obterNumeroProposta($proposta->id);
            $listaPropostas [$this->obterOrgaoSuperior($proposta->id)] [$programa] [0] ['nome_programa'] = $this->obterNomePrograma($orgaos->propostas [0]->programas [0]->associacao [0]->Programa->id);

            $handle = fopen($tmpfname, "w+");
            if ($handle == false)
                die('Não foi possível criar o arquivo.');

            $tee = $this->_encode_string_array(serialize($listaPropostas));

            fwrite($handle, $tee);
            fclose($handle);

            $count ++;
            $this->encaminha('propostas_programas?temp=' . $aleatorio . '&numero_prop=' . $count . '&cod_cidades=' . $cidade);
            die();
        }
        // if (isset($json_output->metadados->proximos)) $listaPropostas = $this->retorna_propostas_programas($json_output->metadados->proximos, $listaPropostas, $numero_prop, $aleatorio, $cidade);

        return $listaPropostas;
    }

    function imprimeProposta($jsonurl, $ano, $listaPropostas, $sistema) {
        $json = file_get_contents($jsonurl, 0, null, null);
        $json_output = json_decode($json);

        if ($json == '') {
            echo "Siconv com problemas internos, por favor tente novamente mais tarde.";
        }
        // var_dump($json_output->propostas); die();

        foreach ($json_output->propostas as $orgaos) {
            // echo ".";
            $situacao = null;
            if ($orgaos->situacao == null)
                $situacao = 0;
            else
                $situacao = $orgaos->situacao->SituacaoProposta->id;
            $numero_proposta = $this->obterNumeroProposta($orgaos->id);

            // echo trim(substr($orgaos->data_cadastramento_proposta, 0, 4))."-".trim($ano)."<br />";
            if ((trim(substr($orgaos->data_cadastramento_proposta, 0, 4)) == trim($ano) || trim($ano) == '') && (count($sistema) == 0 || $this->in_arrayr($numero_proposta, $sistema))) {

                /*
                 * 105503/2010 $pagina = "https://www.convenios.gov.br/siconv/IncluirProgramasProposta/EscolherProponenteEscolherProponente.do"; $fields = array( 'invalidatePageControlCounter' => '1', 'cnpjProponente' => $tela1->proponente ); $fields_string = null; foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; } rtrim($fields_string, '&'); echo utf8_decode($this->obter_pagina_post($pagina, $fields, $fields_string));
                 */
                $jsonurl1 = $orgaos->href;
                $json1 = file_get_contents($jsonurl1 . ".json", 0, null, null);
                $json_output1 = json_decode($json1);

                if ($json1 == '') {
                    echo "Siconv com problemas internos, por favor tente novamente mais tarde.";
                }
                // echo $json_output1->propostas.".json<br>";
                // var_dump($orgaos); echo "<br>";
                $listaPropostas [trim(substr($orgaos->data_cadastramento_proposta, 0, 4))] [$orgaos->id] [$situacao] ['objeto'] = $orgaos->objeto_resumido;
                $listaPropostas [trim(substr($orgaos->data_cadastramento_proposta, 0, 4))] [$orgaos->id] [$situacao] ['numero'] = $numero_proposta;
                $listaPropostas [trim(substr($orgaos->data_cadastramento_proposta, 0, 4))] [$orgaos->id] [$situacao] ['fim'] = $orgaos->fim_execucao;
                $listaPropostas [trim(substr($orgaos->data_cadastramento_proposta, 0, 4))] [$orgaos->id] [$situacao] ['convenio'] = $json_output1->propostas [0]->convenio->Convenio->id;
                $listaPropostas [trim(substr($orgaos->data_cadastramento_proposta, 0, 4))] [$orgaos->id] [$situacao] ['valor_global'] = $orgaos->valor_global;
                $listaPropostas [trim(substr($orgaos->data_cadastramento_proposta, 0, 4))] [$orgaos->id] [$situacao] ['valor_repasse'] = $orgaos->valor_repasse;
                $listaPropostas [trim(substr($orgaos->data_cadastramento_proposta, 0, 4))] [$orgaos->id] [$situacao] ['valor_contra_partida'] = $orgaos->valor_contra_partida;
            }
        }
        if (isset($json_output->metadados->proximos))
            $listaPropostas = $this->imprimeProposta($json_output->metadados->proximos, $ano, $listaPropostas);
        return $listaPropostas;
    }

    function obterEstado($estado) {
        switch ($estado) {
            case 7 :
                return 27;
            case 8 :
                return 7;
            case 10 :
                return 8;
            case 11 :
                return 9;
            case 14 :
                return 12;
            case 13 :
                return 11;
            case 12 :
                return 10;
            case 15 :
                return 13;
            case 16 :
                return 14;
            case 18 :
                return 16;
            case 19 :
                return 17;
            case 17 :
                return 15;
            case 20 :
                return 18;
            case 21 :
                return 19;
            case 23 :
                return 21;
            case 9 :
                return 22;
            case 22 :
                return 20;
            case 25 :
                return 23;
            case 27 :
                return 25;
            case 26 :
                return 24;
            case 24 :
                return 26;
        }
        return $estado;
    }

    function obterEstadoNome($estado) {
        switch ($estado) {

            case "1" :
                return "AC";
            case "2" :
                return "AL";
            case "4" :
                return "AM";
            case "3" :
                return "AP";
            case "5" :
                return "BA";
            case "6" :
                return "CE";
            case "7" :
                return "DF";
            case "8" :
                return "ES";
            case "10" :
                return "GO";
            case "11" :
                return "MA";
            case "14" :
                return "MG";
            case "13" :
                return "MS";
            case "12" :
                return "MT";
            case "15" :
                return "PA";
            case "16" :
                return "PB";
            case "18" :
                return "PE";
            case "19" :
                return "PI";
            case "17" :
                return "PR";
            case "20" :
                return "RJ";
            case "21" :
                return "RN";
            case "23" :
                return "RO";
            case "9" :
                return "RR";
            case "22" :
                return "RS";
            case "25" :
                return "SC";
            case "27" :
                return "SE";
            case "26" :
                return "SP";
            case "24" :
                return "TO";
        }
        return $estado;
    }

    function obter_pagina_post($url, $fields, $fields_string) {

        // $cookie_file_path = "application/views/configuracoes/cookie.txt";
        $cookie_file_path = $this->cookie_file_path;
        $agent = "Mozilla/5.0 (Windows; U; Windows NT 5.0; en-US; rv:1.4) Gecko/20030624 Netscape/7.1 (ax)";
        $ch = curl_init();
        // extra headers
        $headers [] = "Accept: */*";
        $headers [] = "Connection: Keep-Alive";

        // basic curl options for all requests
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_USERAGENT, $agent);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file_path);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file_path);
        curl_setopt($ch, CURLOPT_POST, count($fields));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);

        // set first URL
        curl_setopt($ch, CURLOPT_URL, $url);

        // execute session to get cookies and required form inputs
        $content = curl_exec($ch);
        curl_close($ch);

        return $content;
    }

    function in_arrayr($needle, $haystack) {
        foreach ($haystack as $v) {
            if ($needle == $v)
                return true;
            elseif (is_array($v))
                return in_array($needle, $v);
        }
        return false;
    }

}

/* End of file student.php */
/* Location: ./system/application/controllers/student.php */
