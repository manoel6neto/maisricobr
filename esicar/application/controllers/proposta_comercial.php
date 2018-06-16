<?php

include 'application/controllers/BaseController.php';

class proposta_comercial extends BaseController {

    public function index() {
        $this->session->set_userdata('pagAtual', 'area_vendedor');

        $this->load->model('proposta_comercial_model');

        $data['proposta_cadastradas'] = $this->proposta_comercial_model->get_all_propostas();

        if ($this->session->userdata('gp') == true || $this->input->get('gp', TRUE) != false) {
            $data['title'] = 'G&P - Proposta Comercial';
        } else {
            $data['title'] = 'Physis - Proposta Comercial';
        }
        $data['main'] = "proposta_comercial/index";

        $this->load->view('in/template', $data);
    }

    public function gera_proposta() {
        $this->load->model('proposta_comercial_model');
        $this->load->model('cnpj_siconv');
        $this->load->model('relatorio_ganho_perca_model');

        if ($this->input->post()) {
            $this->form_validation->set_rules('tipo_proposta', 'Tipo Proposta', 'required');
            $this->form_validation->set_rules('descricao_proposta_comercial', 'Descrição Proposta', 'required|max_length[150]');

            if ($this->input->post('tipo_proposta') == 'Organizações Sociais Sem Fins Lucrativos' || $this->input->post('tipo_proposta') == 'Consórcios Públicos') {
                $this->form_validation->set_rules('nome_contato', 'Nome do Contado', 'required');
                $this->form_validation->set_rules('nome_instituicao', 'Nome da Instituição', 'required');
                $this->form_validation->set_rules('email_contato', 'Email do contato', 'required');
                $this->form_validation->set_rules('telefone_contato', 'Telefone', 'required');

                $options['nome_contato'] = $this->input->post('nome_contato');
                $options['nome_instituicao'] = $this->input->post('nome_instituicao');
                $options['email_contato'] = $this->input->post('email_contato');
                $options['telefone_contato'] = $this->input->post('telefone_contato');
            }

            $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

            $this->form_validation->set_message('required', 'O campo %s é obrigatório.');
            $this->form_validation->set_message('integer', 'O campo %s deve conter somente números.');
            $this->form_validation->set_message('max_length', 'O campo %s deve conter no máximo %s caracteres.');
            $this->form_validation->set_message('greater_than', 'O campo %s deve conter valor maior que %s.');

            $options['capa'] = $this->input->post('capa');
            $options['sobre'] = $this->input->post('sobre');
            $options['desempenho'] = $this->input->post('desempenho');
            $options['apresentacao'] = $this->input->post('apresentacao');
            $options['oportunidade'] = $this->input->post('oportunidade');
            $options['proposta'] = $this->input->post('proposta');
            $options['funcionalidade'] = $this->input->post('funcionalidade');
            $options['proposta_comercial'] = $this->input->post('proposta_comercial');
            $options['resultado'] = $this->input->post('resultado');
            $options['anexo1'] = $this->input->post('anexo1');
            $options['anexo2'] = $this->input->post('anexo2');
            $options['anexo3'] = $this->input->post('anexo3');
            $options['anexo4'] = $this->input->post('anexo4');
            $options['anexo5'] = $this->input->post('anexo5');
            $options['anexo6'] = $this->input->post('anexo6');
            $options['anexo7'] = $this->input->post('anexo7');
            $options['anexo8'] = $this->input->post('anexo8');


            $options['descricao_proposta_comercial'] = $this->input->post('descricao_proposta_comercial');
            $options['tipo_proposta'] = $this->input->post('tipo_proposta');
            $options['valor_proposta_comercial'] = $this->input->post('valor_proposta_comercial');
            $options['num_cnpj'] = $this->input->post('num_cnpj');
            if ($options['num_cnpj'] == '') {
                $options['num_cnpj'] = 0;
            }
            $options['num_cnpj_sem_fim'] = $this->input->post('num_cnpj_sem_fim');
            if ($options['num_cnpj_sem_fim'] == '') {
                $options['num_cnpj_sem_fim'] = 0;
            }
            $options['cnpj_proposta_comercial'] = $this->input->post('cnpj_proposta_comercial');
            $options['entidade_alvo'] = $this->input->post('entidade_alvo');
            $options['nome_entidade'] = $this->input->post('nome_entidade');
            $options['periodo_proposta_comercial'] = $this->input->post('periodo_proposta_comercial');
            $options['parcelas_proposta_comercial'] = $this->input->post('parcelas_proposta_comercial');
            //$options['entrada_proposta_comercial'] = $this->input->post('entrada_proposta_comercial');
            $options['data_cadastro'] = $this->input->post('data_cadastro');
            $options['id_usuario'] = $this->input->post('id_usuario');
            $options['percentual_desconto'] = $this->input->post('percentual_desconto');

            if ($this->form_validation->run() === TRUE) {
                $this->proposta_comercial_model->calcula_proposta($options, $this->input->post('empresas'));
                $this->alert('Proposta Cadastrada com sucesso!');
                $this->encaminha(base_url('index.php/proposta_comercial'));
            }
        }

        $cidade = $this->cnpj_siconv->get_cidade($this->session->userdata('id_usuario'));
        $data['tipo_proposta'] = $this->proposta_comercial_model->get_tipo_proposta_array();
//        $data['entidade_interesse'] = $this->proposta_comercial_model->get_tipo_empresa_interesse_array();
        $data['entidade_interesse'] = array();
        $data['empresas_privadas'] = $this->cnpj_siconv->get_cnpjs_por_cidade($cidade->municipio_uf_sigla, $cidade->municipio, 'Privada');
        if ($this->session->userdata('gp') == true || $this->input->get('gp', TRUE) != false) {
            $data['title'] = 'G&P - Proposta Comercial';
        } else {
            $data['title'] = 'Physis - Proposta Comercial';
        }
        $data['main'] = "proposta_comercial/gera_proposta";

        $this->load->view('in/template', $data);
    }

    public function gera_pdf() {
        ini_set("max_execution_time", 0);
        ini_set("memory_limit", "-1");

        $this->load->model('proposta_comercial_model');
        $this->load->model('trabalho_model');
        $this->load->model('cnpj_siconv');
        $this->load->model('contato_municipio_model', 'cm');
        $this->load->model('proponente_siconv_model');
        $this->load->model('programa_model');
        $this->load->model('usuariomodel');
        $this->load->model('estados');
        $this->load->model('relatorio_ganho_perca_model');
        $this->load->model('cidade_tag');

        $nome_empresa = NULL;
        $proposta = $this->proposta_comercial_model->get_proposta_by_id($this->input->get('id', TRUE));
        $dados_cidade_proposta = $this->cnpj_siconv->get_cidade_by_cnpj_siconv($proposta->cnpj_proposta_comercial);

        $cod_cidade_proposta = $dados_cidade_proposta->Codigo;
        $cidade_proposta = $dados_cidade_proposta->Nome;
        $codigo_siconv_cidade = $this->cidade_tag->get_cod_siconv_by_cidade($cidade_proposta)->codigo_municipio;

        /*
         * Pega os cnpjs dos proponentes
         */
        //print_r($proposta->tipo_proposta);die;
        if ($proposta->tipo_proposta == 'Governos Municipais' || $proposta->tipo_proposta == 'Parlamentares') {
            $tipo = 'MUNICIPAL';
            $cnpjs = $this->relatorio_ganho_perca_model->get_cnpjs_por_cidade_geral($dados_cidade_proposta->Sigla, $cidade_proposta, 'MUNICIPAL');
        } elseif ($proposta->tipo_proposta == 'Governos Estaduais') {
            $cnpjs = $this->relatorio_ganho_perca_model->get_cnpjs_por_cidade_geral($dados_cidade_proposta->Sigla, $cidade_proposta, 'Estadual');
        } elseif ($proposta->tipo_proposta == 'Consórcios Públicos') {
            $tipo = 'CONSORCIO PUBLICO';
            $cnpjs = $this->relatorio_ganho_perca_model->get_cnpjs_por_cidade_geral($dados_cidade_proposta->Sigla, $cidade_proposta, 'Consórcios Públicos');
        } elseif ($proposta->tipo_proposta == 'Organizações Sociais Sem Fins Lucrativos') {
            $tipo = 'PRIVADA';
            $cnpjs = $this->proposta_comercial_model->get_empresas_by_proposta($proposta->id_proposta_comercial);
            if (count($cnpjs) == 1) {
                $nome_empresa = $this->cnpj_siconv->get_nome_by_cnpj(preg_replace("/\D+/", "", $cnpjs[0]->cnpj));
            }
        } elseif ($proposta->tipo_proposta == 'Incubadoras Públicas') {
            $tipo = 'TODAS';
            $cnpjs = $this->relatorio_ganho_perca_model->get_cnpjs_por_cidade_geral($dados_cidade_proposta->Sigla, $cidade_proposta, null);
        }

        $cnpjs_todas_esferas = $this->relatorio_ganho_perca_model->get_cnpjs_por_cidade_geral($dados_cidade_proposta->Sigla, $cidade_proposta, null);
        $cnpjs_municipal = $this->relatorio_ganho_perca_model->get_cnpjs_por_cidade_geral($dados_cidade_proposta->Sigla, $cidade_proposta, 'MUNICIPAL');
        $cnpjs_estadual = $this->relatorio_ganho_perca_model->get_cnpjs_por_cidade_geral($dados_cidade_proposta->Sigla, $cidade_proposta, 'ESTADUAL');
        $cnpjs_privada = $this->relatorio_ganho_perca_model->get_cnpjs_por_cidade_geral($dados_cidade_proposta->Sigla, $cidade_proposta, 'PRIVADA');
        $cnpjs_consorcio = $this->relatorio_ganho_perca_model->get_cnpjs_por_cidade_geral($dados_cidade_proposta->Sigla, $cidade_proposta, 'CONSORCIO PUBLICO');
        $cnpjs_mista = $this->relatorio_ganho_perca_model->get_cnpjs_por_cidade_geral($dados_cidade_proposta->Sigla, $cidade_proposta, 'EMPRESA PUBLICA SOCIEDADE ECONOMIA MISTA');

        if (count($cnpjs) > 0) {
            $proponente_cnpj = array();
            foreach ($cnpjs as $cnpj) {
                array_push($proponente_cnpj, $cnpj->cnpj);
            }
        }

        if (count($cnpjs_todas_esferas) > 0) {
            $proponente_cnpj_todas_esferas = array();
            foreach ($cnpjs_todas_esferas as $cnpj) {
                array_push($proponente_cnpj_todas_esferas, $cnpj->cnpj);
            }
        }

        if (count($cnpjs_municipal) > 0) {
            $proponente_cnpj_municipal = array();
            foreach ($cnpjs_municipal as $cnpj) {
                array_push($proponente_cnpj_municipal, $cnpj->cnpj);
            }
        }

        if (count($cnpjs_estadual) > 0) {
            $proponente_cnpj_estadual = array();
            foreach ($cnpjs_estadual as $cnpj) {
                array_push($proponente_cnpj_estadual, $cnpj->cnpj);
            }
        }

        if (count($cnpjs_privada) > 0) {
            $proponente_cnpj_privada = array();
            foreach ($cnpjs_privada as $cnpj) {
                array_push($proponente_cnpj_privada, $cnpj->cnpj);
            }
        }

        if (count($cnpjs_consorcio) > 0) {
            $proponente_cnpj_consorcio = array();
            foreach ($cnpjs_consorcio as $cnpj) {
                array_push($proponente_cnpj_consorcio, $cnpj->cnpj);
            }
        }

        if (count($cnpjs_mista) > 0) {
            $proponente_cnpj_mista = array();
            foreach ($cnpjs_mista as $cnpj) {
                array_push($proponente_cnpj_mista, $cnpj->cnpj);
            }
        }

        $estado = $this->estados->get_by_sigla_full($dados_cidade_proposta->Sigla);

        $proponente = $this->proponente_siconv_model->get_municipio_by_cnpj($proposta->cnpj_proposta_comercial);

        $array_esferas = array();
        array_push($array_esferas, $proponente->esfera_administrativa);
        $dados_contato_municipio = $this->cm->get_visita($proponente->codigo_municipio, $this->session->userdata('id_usuario'));
        $data['estado'] = $estado->sigla;
        $data['nome_estado'] = $estado->nome;
        $data['municipio'] = $cidade_proposta;
        $data['nome_empresa'] = $nome_empresa;
        $data['proposta'] = $proposta;
        $data['qntd_cnpjs'] = count($cnpjs);
        $data['proposta_comercial_model'] = $this->proposta_comercial_model;
        $data['usuario'] = $this->usuariomodel->get_by_id($this->session->userdata('id_usuario'));

        if (isset($proponente_cnpj_todas_esferas))
            $planilha_siconv_todas_esferas = $this->get_planilha($codigo_siconv_cidade, $estado->sigla, $proponente_cnpj_todas_esferas, $nome_empresa, 'TODAS', 1);
        if (isset($proponente_cnpj_municipal))
            $planilha_siconv_municipal = $this->get_planilha($codigo_siconv_cidade, $estado->sigla, $proponente_cnpj_municipal, $nome_empresa, 'GOV. MUNICIPAL', 2);
        if (isset($proponente_cnpj_estadual))
            $planilha_siconv_estadual = $this->get_planilha($codigo_siconv_cidade, $estado->sigla, $proponente_cnpj_estadual, $nome_empresa, 'GOV. ESTADUAL', 3);
        if (isset($proponente_cnpj_privada))
            $planilha_siconv_privada = $this->get_planilha($codigo_siconv_cidade, $estado->sigla, $proponente_cnpj_privada, $nome_empresa, 'O.S.C', 4);
        if (isset($proponente_cnpj_consorcio))
            $planilha_siconv_consorcio = $this->get_planilha($codigo_siconv_cidade, $estado->sigla, $proponente_cnpj_consorcio, $nome_empresa, 'CONSORCIO PUBLICO', 5);
        if (isset($proponente_cnpj_mista))
            $planilha_siconv_mista = $this->get_planilha($codigo_siconv_cidade, $estado->sigla, $proponente_cnpj_mista, $nome_empresa, 'EMPRESA PUBLICA SOCIEDADE ECONOMIA MISTA', 6);
        if (isset($proponente_cnpj)) {
            $planilha_siconv = $this->get_planilha($codigo_siconv_cidade, $estado->sigla, $proponente_cnpj, $nome_empresa, $tipo, NULL);
            $relatorio_siconv = $this->get_relatorio($codigo_siconv_cidade, $estado->sigla, $proponente_cnpj, $nome_empresa, $tipo);
        }

        if ($proposta->tipo_proposta != 'Organizações Sociais Sem Fins Lucrativos' && $proposta->tipo_proposta != 'Consórcios Públicos') {
            $data['nome_contato'] = $dados_contato_municipio->nome_contato;
            $data['email_contato'] = $dados_contato_municipio->email_contato;
            $data['telefone_contato'] = $dados_contato_municipio->telefone_contato;
            $data['instituicao_contato'] = '';
        }

        setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
        date_default_timezone_set('America/Sao_Paulo');
        session_start();
        $this->load->library('mpdf60/mpdf.php');
        $mpdf = new mPDF ();
        $mpdf->allow_charset_conversion = true;
        $mpdf->charset_in = 'UTF-8';
        $mpdf->SetDefaultFontSize(10);
        $mpdf->SetMargins(5, 5, 5);
        $mpdf->margin_bottom_collapse = true;
        $header = '<div align="left" style="font-size: 10px; color: #97a5b0">AUTOMAÇÃO DA GESTÃO NA CELEBRAÇÃO DE CONVENIOS SICONV</div><img src="' . base_url('layout/assets/images/logo_e_sicar.png') . '" width="60" height="20" style="float: right;"/>';
        if ($proposta->capa == 1) {
            $mpdf->WriteHTML($this->load->view('proposta_comercial/gera_pdf_capa_proposta', $data, TRUE), 2);
            $mpdf->SetHTMLHeader($header);
            $mpdf->AddPage();
            $mpdf->SetHTMLFooter("<div align='center' style='font-size: 10px; color: #97a5b0'>Rua Comendador Lustoza de Andrade, 489 – Bom Retiro – Curitiba-PR – CEP: 80.520-350  -  Fone: (41)3266-2826</div><brr><div align='center' style='font-size: 10px; color: #97a5b0'>Site: www.physisbrasil.com.br - Email:diretorianacional@physisbrasil.com.br</div>");
        } else {
            $mpdf->SetHTMLHeader($header);
            $mpdf->SetHTMLFooter("<div align='center' style='font-size: 10px; color: #97a5b0'>Rua Comendador Lustoza de Andrade, 489 – Bom Retiro – Curitiba-PR – CEP: 80.520-350  -  Fone: (41)3266-2826</div><brr><div align='center' style='font-size: 10px; color: #97a5b0'>Site: www.physisbrasil.com.br - Email:diretorianacional@physisbrasil.com.br</div>");
        }

        if ($proposta->sobre == 1) {
            $mpdf->WriteHTML($this->load->view('proposta_comercial/gera_pdf_sobre_proposta', $data, TRUE), 2);
            $mpdf->AddPage();
        }

        if ($proposta->desempenho == 1) {
            $mpdf->WriteHTML($this->load->view('proposta_comercial/gera_pdf_desempenho_proposta', $data, TRUE), 2);
            $mpdf->AddPage();
        }

        if ($proposta->apresentacao == 1) {
            $mpdf->WriteHTML($this->load->view('proposta_comercial/gera_pdf_apresentacao_proposta', $data, TRUE), 2);
            $mpdf->AddPage();
        }

        if ($proposta->oportunidade == 1) {
            $mpdf->WriteHTML($this->load->view('proposta_comercial/gera_pdf_oportunidade_proposta', $data, TRUE), 2);
            $mpdf->AddPage();
        }

        if ($proposta->proposta == 1) {
            $mpdf->WriteHTML($this->load->view('proposta_comercial/gera_pdf_proposta_proposta', $data, TRUE), 2);
            $mpdf->AddPage();
        }

        if ($proposta->funcionalidade == 1) {
            $mpdf->WriteHTML($this->load->view('proposta_comercial/gera_pdf_funcionalidade_proposta', $data, TRUE), 2);
            $mpdf->AddPage();
        }

        if ($proposta->proposta_comercial == 1) {
            $mpdf->WriteHTML($this->load->view('proposta_comercial/gera_pdf_proposta_comercial_proposta', $data, TRUE), 2);
            $mpdf->AddPage();
        }

        if ($proposta->resultado == 1) {
            $mpdf->WriteHTML($this->load->view('proposta_comercial/gera_pdf_resultado_proposta', $data, TRUE), 2);
            $mpdf->AddPage();
        }

        $data['date'] = utf8_encode(strftime('%d de %B de %Y', strtotime('today')));
        $mpdf->WriteHTML($this->load->view('proposta_comercial/gera_pdf_contato_proposta', $data, TRUE), 2);
        $mpdf->AddPage();

        if ($proposta->anexo1 == 1) {
            $mpdf->WriteHTML($planilha_siconv_todas_esferas, 2);
        }
        if ($proposta->anexo2 == 1) {
            $mpdf->WriteHTML($planilha_siconv_municipal, 2);
        }
        if ($proposta->anexo3 == 1) {
            $mpdf->WriteHTML($planilha_siconv_estadual, 2);
        }
        if ($proposta->anexo4 == 1) {
            $mpdf->WriteHTML($planilha_siconv_privada, 2);
        }
        if ($proposta->anexo5 == 1) {
            $mpdf->WriteHTML($planilha_siconv_consorcio, 2);
        }

        if ($proposta->anexo6 == 1) {
            $mpdf->WriteHTML($planilha_siconv_mista, 2);
        }

        if ($proposta->anexo7 == 1) {
            $mpdf->WriteHTML($this->load->view('proposta_comercial/gera_pdf_anexo7_proposta', $data, TRUE), 2);
            $mpdf->AddPage();
        }

        if ($proposta->anexo8 == 1) {
            $mpdf->WriteHTML($this->load->view('proposta_comercial/gera_pdf_anexo8_proposta', $data, TRUE), 2);
        }

        $mpdf->Output();

        exit;
    }

    public function send_proposta_usuario() {
        ini_set("max_execution_time", 0);
        ini_set("memory_limit", "-1");

        $this->load->model('proposta_comercial_model');
        $this->load->model('trabalho_model');
        $this->load->model('cnpj_siconv');
        $this->load->model('contato_municipio_model', 'cm');
        $this->load->model('proponente_siconv_model');
        $this->load->model('programa_model');
        $this->load->model('usuariomodel');
        $this->load->model('estados');
        $this->load->model('relatorio_ganho_perca_model');
        $this->load->model('cidade_tag');
        $nome_empresa = NULL;
        $proposta = $this->proposta_comercial_model->get_proposta_by_id($this->input->post('id', TRUE));
        $dados_cidade_proposta = $this->cnpj_siconv->get_cidade_by_cnpj_siconv($proposta->cnpj_proposta_comercial);

        $cod_cidade_proposta = $dados_cidade_proposta->Codigo;
        $cidade_proposta = $dados_cidade_proposta->Nome;
        $codigo_siconv_cidade = $this->cidade_tag->get_cod_siconv_by_cidade($cidade_proposta)->codigo_municipio;

        /*
         * Pega os cnpjs dos proponentes
         */
        //print_r($proposta->tipo_proposta);die;
        if ($proposta->tipo_proposta == 'Governos Municipais' || $proposta->tipo_proposta == 'Parlamentares') {
            $tipo = 'MUNICIPAL';
            $cnpjs = $this->relatorio_ganho_perca_model->get_cnpjs_por_cidade_geral($dados_cidade_proposta->Sigla, $cidade_proposta, 'MUNICIPAL');
        } elseif ($proposta->tipo_proposta == 'Governos Estaduais') {
            $cnpjs = $this->relatorio_ganho_perca_model->get_cnpjs_por_cidade_geral($dados_cidade_proposta->Sigla, $cidade_proposta, 'Estadual');
        } elseif ($proposta->tipo_proposta == 'Consórcios Públicos') {
            $tipo = 'CONSORCIO PUBLICO';
            $cnpjs = $this->relatorio_ganho_perca_model->get_cnpjs_por_cidade_geral($dados_cidade_proposta->Sigla, $cidade_proposta, 'Consórcios Públicos');
        } elseif ($proposta->tipo_proposta == 'Organizações Sociais Sem Fins Lucrativos') {
            $tipo = 'PRIVADA';
            $cnpjs = $this->proposta_comercial_model->get_empresas_by_proposta($proposta->id_proposta_comercial);
            if (count($cnpjs) == 1) {
                $nome_empresa = $this->cnpj_siconv->get_nome_by_cnpj(preg_replace("/\D+/", "", $cnpjs[0]->cnpj));
            }
        } elseif ($proposta->tipo_proposta == 'Incubadoras Públicas') {
            $tipo = 'TODAS';
            $cnpjs = $this->relatorio_ganho_perca_model->get_cnpjs_por_cidade_geral($dados_cidade_proposta->Sigla, $cidade_proposta, null);
        }

        $cnpjs_todas_esferas = $this->relatorio_ganho_perca_model->get_cnpjs_por_cidade_geral($dados_cidade_proposta->Sigla, $cidade_proposta, null);
        $cnpjs_municipal = $this->relatorio_ganho_perca_model->get_cnpjs_por_cidade_geral($dados_cidade_proposta->Sigla, $cidade_proposta, 'MUNICIPAL');
        $cnpjs_estadual = $this->relatorio_ganho_perca_model->get_cnpjs_por_cidade_geral($dados_cidade_proposta->Sigla, $cidade_proposta, 'ESTADUAL');
        $cnpjs_privada = $this->relatorio_ganho_perca_model->get_cnpjs_por_cidade_geral($dados_cidade_proposta->Sigla, $cidade_proposta, 'PRIVADA');
        $cnpjs_consorcio = $this->relatorio_ganho_perca_model->get_cnpjs_por_cidade_geral($dados_cidade_proposta->Sigla, $cidade_proposta, 'CONSORCIO PUBLICO');
        $cnpjs_mista = $this->relatorio_ganho_perca_model->get_cnpjs_por_cidade_geral($dados_cidade_proposta->Sigla, $cidade_proposta, 'EMPRESA PUBLICA SOCIEDADE ECONOMIA MISTA');

        if (count($cnpjs) > 0) {
            $proponente_cnpj = array();
            foreach ($cnpjs as $cnpj) {
                array_push($proponente_cnpj, $cnpj->cnpj);
            }
        }

        if (count($cnpjs_todas_esferas) > 0) {
            $proponente_cnpj_todas_esferas = array();
            foreach ($cnpjs_todas_esferas as $cnpj) {
                array_push($proponente_cnpj_todas_esferas, $cnpj->cnpj);
            }
        }

        if (count($cnpjs_municipal) > 0) {
            $proponente_cnpj_municipal = array();
            foreach ($cnpjs_municipal as $cnpj) {
                array_push($proponente_cnpj_municipal, $cnpj->cnpj);
            }
        }

        if (count($cnpjs_estadual) > 0) {
            $proponente_cnpj_estadual = array();
            foreach ($cnpjs_estadual as $cnpj) {
                array_push($proponente_cnpj_estadual, $cnpj->cnpj);
            }
        }

        if (count($cnpjs_privada) > 0) {
            $proponente_cnpj_privada = array();
            foreach ($cnpjs_privada as $cnpj) {
                array_push($proponente_cnpj_privada, $cnpj->cnpj);
            }
        }

        if (count($cnpjs_consorcio) > 0) {
            $proponente_cnpj_consorcio = array();
            foreach ($cnpjs_consorcio as $cnpj) {
                array_push($proponente_cnpj_consorcio, $cnpj->cnpj);
            }
        }

        if (count($cnpjs_mista) > 0) {
            $proponente_cnpj_mista = array();
            foreach ($cnpjs_mista as $cnpj) {
                array_push($proponente_cnpj_mista, $cnpj->cnpj);
            }
        }

        $estado = $this->estados->get_by_sigla_full($dados_cidade_proposta->Sigla);

        $proponente = $this->proponente_siconv_model->get_municipio_by_cnpj($proposta->cnpj_proposta_comercial);

        $array_esferas = array();
        array_push($array_esferas, $proponente->esfera_administrativa);
        $dados_contato_municipio = $this->cm->get_visita($proponente->codigo_municipio, $this->session->userdata('id_usuario'));
        $data['estado'] = $estado->sigla;
        $data['municipio'] = $cidade_proposta;
        $data['nome_empresa'] = $nome_empresa;
        $data['proposta'] = $proposta;
        $data['qntd_cnpjs'] = count($cnpjs);
        $data['proposta_comercial_model'] = $this->proposta_comercial_model;
        $data['usuario'] = $this->usuariomodel->get_by_id($this->session->userdata('id_usuario'));

        if (isset($proponente_cnpj_todas_esferas))
            $planilha_siconv_todas_esferas = $this->get_planilha($codigo_siconv_cidade, $estado->sigla, $proponente_cnpj_todas_esferas, $nome_empresa, 'TODAS', 1);
        if (isset($proponente_cnpj_municipal))
            $planilha_siconv_municipal = $this->get_planilha($codigo_siconv_cidade, $estado->sigla, $proponente_cnpj_municipal, $nome_empresa, 'GOV. MUNICIPAL', 2);
        if (isset($proponente_cnpj_estadual))
            $planilha_siconv_estadual = $this->get_planilha($codigo_siconv_cidade, $estado->sigla, $proponente_cnpj_estadual, $nome_empresa, 'GOV. ESTADUAL', 3);
        if (isset($proponente_cnpj_privada))
            $planilha_siconv_privada = $this->get_planilha($codigo_siconv_cidade, $estado->sigla, $proponente_cnpj_privada, $nome_empresa, 'O.S.C', 4);
        if (isset($proponente_cnpj_consorcio))
            $planilha_siconv_consorcio = $this->get_planilha($codigo_siconv_cidade, $estado->sigla, $proponente_cnpj_consorcio, $nome_empresa, 'CONSORCIO PUBLICO', 5);
        if (isset($proponente_cnpj_mista))
            $planilha_siconv_mista = $this->get_planilha($codigo_siconv_cidade, $estado->sigla, $proponente_cnpj_mista, $nome_empresa, 'EMPRESA PUBLICA SOCIEDADE ECONOMIA MISTA', 6);
        if (isset($proponente_cnpj)) {
            $planilha_siconv = $this->get_planilha($codigo_siconv_cidade, $estado->sigla, $proponente_cnpj, $nome_empresa, $tipo, NULL);
            $relatorio_siconv = $this->get_relatorio($codigo_siconv_cidade, $estado->sigla, $proponente_cnpj, $nome_empresa, $tipo);
        }

        if ($proposta->tipo_proposta != 'Organizações Sociais Sem Fins Lucrativos' && $proposta->tipo_proposta != 'Consórcios Públicos') {
            $mailto = $dados_contato_municipio->email_contato;
            $data['nome_contato'] = $dados_contato_municipio->nome_contato;
            $data['email_contato'] = $dados_contato_municipio->email_contato;
            $data['telefone_contato'] = $dados_contato_municipio->telefone_contato;
            $data['instituicao_contato'] = '';
        } else {
            $mailto = $proposta->email_contato;
        }

        session_start();
        $this->load->library('mpdf60/mpdf.php');
        $mpdf = new mPDF ();
        $mpdf->allow_charset_conversion = true;
        $mpdf->charset_in = 'UTF-8';
        $mpdf->SetDefaultFontSize(10);
        $mpdf->SetMargins(5, 5, 5);
        $mpdf->margin_bottom_collapse = true;
        $header = '<div align="left" style="font-size: 10px; color: #97a5b0">AUTOMAÇÃO DA GESTÃO NA CELEBRAÇÃO DE CONVENIOS SICONV</div><img src="' . base_url('layout/assets/images/logo_e_sicar.png') . '" width="60" height="20" style="float: right;"/>';
        if ($proposta->capa == 1) {
            $mpdf->WriteHTML($this->load->view('proposta_comercial/gera_pdf_capa_proposta', $data, TRUE), 2);
            $mpdf->SetHTMLHeader($header);
            $mpdf->AddPage();
            $mpdf->SetHTMLFooter("<div align='center' style='font-size: 10px; color: #97a5b0'>Rua Comendador Lustoza de Andrade, 489 – Bom Retiro – Curitiba-PR – CEP: 80.520-350  -  Fone: (41)3266-2826</div><brr><div align='center' style='font-size: 10px; color: #97a5b0'>Site: www.physisbrasil.com.br - Email:diretorianacional@physisbrasil.com.br</div>");
        } else {
            $mpdf->SetHTMLHeader($header);
            $mpdf->SetHTMLFooter("<div align='center' style='font-size: 10px; color: #97a5b0'>Rua Comendador Lustoza de Andrade, 489 – Bom Retiro – Curitiba-PR – CEP: 80.520-350  -  Fone: (41)3266-2826</div><brr><div align='center' style='font-size: 10px; color: #97a5b0'>Site: www.physisbrasil.com.br - Email:diretorianacional@physisbrasil.com.br</div>");
        }

        if ($proposta->sobre == 1) {
            $mpdf->WriteHTML($this->load->view('proposta_comercial/gera_pdf_sobre_proposta', $data, TRUE), 2);
            $mpdf->AddPage();
        }

        if ($proposta->desempenho == 1) {
            $mpdf->WriteHTML($this->load->view('proposta_comercial/gera_pdf_desempenho_proposta', $data, TRUE), 2);
            $mpdf->AddPage();
        }

        if ($proposta->apresentacao == 1) {
            $mpdf->WriteHTML($this->load->view('proposta_comercial/gera_pdf_apresentacao_proposta', $data, TRUE), 2);
            $mpdf->AddPage();
        }

        if ($proposta->oportunidade == 1) {
            $mpdf->WriteHTML($this->load->view('proposta_comercial/gera_pdf_oportunidade_proposta', $data, TRUE), 2);
            $mpdf->AddPage();
        }

        if ($proposta->proposta == 1) {
            $mpdf->WriteHTML($this->load->view('proposta_comercial/gera_pdf_proposta_proposta', $data, TRUE), 2);
            $mpdf->AddPage();
        }

        if ($proposta->funcionalidade == 1) {
            $mpdf->WriteHTML($this->load->view('proposta_comercial/gera_pdf_funcionalidade_proposta', $data, TRUE), 2);
            $mpdf->AddPage();
        }

        if ($proposta->proposta_comercial == 1) {
            $mpdf->WriteHTML($this->load->view('proposta_comercial/gera_pdf_proposta_comercial_proposta', $data, TRUE), 2);
            $mpdf->AddPage();
        }

        if ($proposta->resultado == 1) {
            $mpdf->WriteHTML($this->load->view('proposta_comercial/gera_pdf_resultado_proposta', $data, TRUE), 2);
            $mpdf->AddPage();
        }
        setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
        date_default_timezone_set('America/Sao_Paulo');
        $data['date'] = strftime('%d de %B de %Y', strtotime('today'));
        $mpdf->WriteHTML($this->load->view('proposta_comercial/gera_pdf_contato_proposta', $data, TRUE), 2);
        $mpdf->AddPage();

        if ($proposta->anexo1 == 1) {
            $mpdf->WriteHTML($planilha_siconv_todas_esferas, 2);
        }
        if ($proposta->anexo2 == 1) {
            $mpdf->WriteHTML($planilha_siconv_municipal, 2);
        }
        if ($proposta->anexo3 == 1) {
            $mpdf->WriteHTML($planilha_siconv_estadual, 2);
        }
        if ($proposta->anexo4 == 1) {
            $mpdf->WriteHTML($planilha_siconv_privada, 2);
        }
        if ($proposta->anexo5 == 1) {
            $mpdf->WriteHTML($planilha_siconv_consorcio, 2);
        }

        if ($proposta->anexo6 == 1) {
            $mpdf->WriteHTML($planilha_siconv_mista, 2);
        }

        if ($proposta->anexo7 == 1) {
            $mpdf->WriteHTML($this->load->view('proposta_comercial/gera_pdf_anexo7_proposta', $data, TRUE), 2);
            $mpdf->AddPage();
        }

        if ($proposta->anexo8 == 1) {
            $mpdf->WriteHTML($this->load->view('proposta_comercial/gera_pdf_anexo8_proposta', $data, TRUE), 2);
        }


        $nomeArquivo = $this->session->userdata('id_usuario') . "_" . rand(1111, 99999) . "_proposta_comercial_" . $cidade_proposta . ".pdf";
        $mpdf->Output(BASEPATH . '../arquivos_proposta/' . $nomeArquivo, 'F');

        $from_name = 'Physis Brasil';
        $from_mail = 'physisbrasil@gmail.com';
        $subject = $this->input->post('assunto', TRUE);
        $message = $this->input->post('mensagem', TRUE);
        $filename = $nomeArquivo;

        $message .= "<br><br>" . $dadosRepresentante->nome . "<br>" . $dadosRepresentante->entidade;

//        $this->load->library('email');
//
//        $this->email->initialize($this->usuariomodel->inicializa_config_email($from_mail));
//
//        $this->email->set_mailtype('html');

        $this->load->library('email', $this->usuariomodel->inicializa_config_email_gmail("physisbrasil@gmail.com"));
        $this->email->set_newline("\r\n");

        $this->email->reply_to($dadosRepresentante->email, $dadosRepresentante->nome);
        $this->email->from($from_mail, $dadosRepresentante->nome);
        $this->email->to($mailto);

        if ($this->input->post('email', TRUE)) {
            $this->email->cc(trim($this->input->post('email', TRUE), ","));
        } else {
            $this->email->cc(NULL);
        }

        $this->email->subject($subject);
        $this->email->message($message);
        $this->email->attach(BASEPATH . '../arquivos_proposta/' . $nomeArquivo);
        echo $this->email->send();

        exit;
    }

    public function get_dados_visita() {
        $this->load->model('proposta_comercial_model');
        $this->load->model('cnpj_siconv');
        $this->load->model('contato_municipio_model', 'cm');
        $this->load->model('proponente_siconv_model');
        $this->load->model('programa_model');

        $proposta = $this->proposta_comercial_model->get_proposta_by_id($this->input->get('id', TRUE));

        $dados_cidade_proposta = $this->cnpj_siconv->get_cidade_by_cnpj_siconv($proposta->cnpj_proposta_comercial);

        $estado = $dados_cidade_proposta->Sigla;

        $proponente = $this->proponente_siconv_model->get_municipio_by_cnpj($proposta->cnpj_proposta_comercial);

        $dados_contato_municipio = $this->cm->get_visita($proponente->codigo_municipio, $this->session->userdata('id_usuario'));


        if ($proposta->tipo_proposta != 'Organizações Sociais Sem Fins Lucrativos' && $proposta->tipo_proposta != 'Consórcios Públicos') {
            $data['nome_contato'] = $dados_contato_municipio->nome_contato;
            $data['email_contato'] = $dados_contato_municipio->email_contato;
        } else {
            $data['nome_contato'] = $proposta->nome_contato;
            $data['email_contato'] = $proposta->email_contato;
        }

        echo json_encode($data);
    }

    public function deleta_proposta() {
        $this->db->where('id_proposta_comercial', $this->input->get('id', TRUE));
        $this->db->delete('proposta_comercial');

        $this->alert('Proposta deletada com sucesso!');
        $this->encaminha(base_url('index.php/proposta_comercial'));
    }

    function alert($text) {
        echo "<script type='text/javascript'>alert('" . utf8_decode($text) . "');</script>";
    }

    function encaminha($url) {
        echo "<script type='text/javascript'>window.location='" . $url . "';</script>";
        exit();
    }

    /*
     * Gera relatório Siconv
     */

    public function get_planilha($cod_cidade, $estado, $proponente, $nome_empresa, $tipo, $anexo) {
        //Carrega models
        $this->load->model('cidade_tag');
        $this->load->model('relatorio_ganho_perca_model', 'modelrel');
        $this->load->model('relatorios_siconv_model');
        $this->load->model('banco_proposta_model', 'banco_proposta');

        //Pega informações da cidade
        $cidades_tag = $this->cidade_tag->get_cidade_tag_by_cod($cod_cidade);

        //verifica o nível, fator e programas de cadastramento da cidade
        $nivel_e_fator_de_cadastro = $this->relatorios_siconv_model->verifica_nivel(strtoupper($cidades_tag->cidade), $cidades_tag->populacao);

        $dados = $this->relatorios_siconv_model->get_dados_siconv($proponente, NULL, $estado, $cidades_tag, $nivel_e_fator_de_cadastro, $nome_empresa, $tipo);
        $dados['anexo'] = $anexo;
        $tabela = $this->load->view('relatorios_estatisticos/pdf_planilha_siconv', $dados, TRUE);
        return $tabela;
    }

    public function get_relatorio($cod_cidade, $estado, $proponente, $nome_empresa, $tipo) {
        $this->load->model('cidade_tag');
        $this->load->model('relatorio_ganho_perca_model', 'modelrel');
        $this->load->model('relatorios_siconv_model');
        $this->load->model('banco_proposta_model', 'banco_proposta');

        //Pega informações da cidade
        $cidades_tag = $this->cidade_tag->get_cidade_tag_by_cod($cod_cidade);

        //verifica o nível, fator e programas de cadastramento da cidade
        $nivel_e_fator_de_cadastro = $this->relatorios_siconv_model->verifica_nivel(strtoupper($cidades_tag->cidade), $cidades_tag->populacao);

        $dados = $this->relatorios_siconv_model->get_dados_siconv($proponente, NULL, $estado, $cidades_tag, $nivel_e_fator_de_cadastro, $nome_empresa, $tipo);
        $dados['proposta_comercial'] = true;
        $relatorio = $this->load->view('relatorios_estatisticos/pdf_relatorio_siconv', $dados, TRUE);
        $proposta = $this->load->view('relatorios_estatisticos/pdf_proposta_siconv', $dados, TRUE);
        return array("relatorio" => $relatorio, "proposta" => $proposta);
    }

}
