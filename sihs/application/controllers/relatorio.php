<?php

include 'application/controllers/BaseController.php';

class relatorio extends BaseController {

    public function index() {
        $this->session->set_userdata('pagAtual', 'relatorio');

        $this->session->set_userdata('filtro_rel', "");

        $this->load->model('relatorio_model');

        $data['meses'] = $this->relatorio_model->get_meses_array();
        $data['anos'] = $this->relatorio_model->get_anos_propostas_array();
        $data['usuarios'] = $this->relatorio_model->get_usuarios_rel(false);
        $data['representates'] = $this->relatorio_model->get_usuario_representantes();

        $data['title'] = 'SIHS - Relatórios';
        $data['main'] = "relatorio/index";

        $this->load->view('in/template', $data);
    }

    public function rel_proj_desenv() {
        $this->load->model('relatorio_model');
        $this->load->model('usuariomodel');
        $this->load->model('programa_proposta_model');

        $filtro = $this->input->post();

        $this->session->set_userdata('filtro_rel', $filtro);

        $data['programa_proposta_model'] = $this->programa_proposta_model;
        $data['usuariomodel'] = $this->usuariomodel;
        $data['dados_rel'] = $this->relatorio_model->get_dados_rel_proj($filtro);
        $data['title'] = 'SIHS - Relatório Projetos Desenvolvidos';
        $data['main'] = "relatorio/rel_proj_desenv";

        $this->load->view('in/template', $data);
    }

    public function rel_proj_desenv_pdf() {
        ini_set('memory_limit', -1);

        $this->load->model('relatorio_model');
        $this->load->model('usuariomodel');
        $this->load->model('programa_proposta_model');

        $filtro = $this->session->userdata('filtro_rel');

        $dados_rel = $this->relatorio_model->get_dados_rel_proj($filtro);

        $this->load->library('mPDF');
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
                'content' => 'SIHS',
                'font-size' => 8
            ),
            'line' => 1
        );
        $mpdf->SetHeader($header, 'O');
        $mpdf->SetFooter('{DATE d/m/Y}||{PAGENO}/{nb}');

        $titulo = "";
        $i = 0;
        $j = 0;
        foreach ($dados_rel as $dados) {
            $qtd = 0;

            foreach ($dados_rel as $d) {
                if ($d->TITULO == $dados->TITULO)
                    $qtd++;
            }

            if ($titulo == "" || $titulo != $dados->TITULO) {
                $mpdf->AddPage();

                $tabela = '<h1 class="bg-white content-heading border-bottom" style="text-align:center; font-size:18px;">Relatório de Projetos Desenvolvidos</h1>';
                $tabela .= '<p style="font-size:15px;">' . $dados->TITULO . ' <span style="color: #428bca;">(' . $qtd . ')</span></p>';

                $tabela .= '<table class="table">';
                $titulo = $dados->TITULO;
                $j++;
            }

            $listaProgramas = "";

            $programas = $this->programa_proposta_model->get_programas_by_proposta($dados->idProposta);
            foreach ($programas as $p)
                $listaProgramas .= "- " . substr($p->nome_programa, 0, 180) . (strlen($p->nome_programa) > 180 ? "..." : "") . "<br>";

            $labelDataEnvio = "";
            if ($dados->TITULO == "Projetos Enviados Pelo Sistema")
                $labelDataEnvio = "Data Envio";

            $tabela .= "<tr style='background-color:#DCDCDC;'><td style='color:red;'>Responsável</td><td style='color:#428bca;'>" . $this->usuariomodel->get_by_id($dados->idGestor)->nome . "</td><td>Data Criação</td><td>" . implode("/", array_reverse(explode("-", $dados->data))) . "</td><td>{$labelDataEnvio}</td><td>" . implode("/", array_reverse(explode("-", $dados->data_envio))) . "</td></tr>";
            $tabela .= "<tr><td>Município</td><td colspan='4'>ITABUNA</td></tr>";
            $tabela .= "<tr><td>Área</td><td colspan='4'>{$dados->areanome}</td></tr>";
            $tabela .= "<tr><td>Nome do Projeto</td><td colspan='4'>{$dados->nome}</td></tr>";
            $tabela .= "<tr><td>Programa</td><td colspan='4'>{$listaProgramas}</td></tr>";
            $tabela .= "<tr><td>Valor</td><td>" . number_format($dados->valor_global, 2, ",", ".") . "</td><td>Data Inicio</td><td>" . implode("/", array_reverse(explode("-", $dados->data_inicio))) . "</td><td>Data Fim</td><td>" . implode("/", array_reverse(explode("-", $dados->data_termino))) . "</td></tr>";

            $tabela .= "<tr><td colspan='5'>&nbsp;</td></tr>";

            $titulo = $dados->TITULO;

            if (isset($dados_rel[$i + 1]->TITULO) && ($titulo == "" || $titulo == $dados_rel[$i + 1]->TITULO)) {
                
            } else {
                $tabela .= "</table>";

                $mpdf->WriteHTML($tabela);
            }

            $i++;
        }

        $mpdf->Output();

        die();
    }

    public function rel_atividade_usuario() {
        $this->load->model('relatorio_model');
        $this->load->model('usuariomodel');
        $this->load->model('programa_proposta_model');
        $this->load->model('proponente_siconv_model');

        $filtro = $this->input->post();

        $this->session->set_userdata('filtro_rel', $filtro);

        $data['proponente_siconv_model'] = $this->proponente_siconv_model;
        $data['programa_proposta_model'] = $this->programa_proposta_model;
        $data['usuariomodel'] = $this->usuariomodel;
        $data['dados_rel'] = $this->relatorio_model->get_dados_rel_usuario($filtro);
        $data['title'] = 'SIHS - Relatório Atividade Usuário';
        $data['main'] = "relatorio/rel_atividade_usuario";

        $this->load->view('in/template', $data);
    }

    public function rel_atividade_usuario_pef() {
        ini_set('memory_limit', -1);

        $this->load->model('relatorio_model');
        $this->load->model('usuariomodel');
        $this->load->model('programa_proposta_model');
        $this->load->model('proponente_siconv_model');

        $filtro = $this->input->post();

        $filtro = $this->session->userdata('filtro_rel');

        $dados_rel = $this->relatorio_model->get_dados_rel_usuario($filtro);
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
                'content' => 'SIHS',
                'font-size' => 8
            ),
            'line' => 1
        );
        $mpdf->SetHeader($header, 'O');
        $mpdf->SetFooter('{DATE d/m/Y}||{PAGENO}/{nb}');

        $titulo = "";
        $i = 0;
        $j = 0;

        foreach ($dados_rel as $dados) {
            $qtd = 0;

            foreach ($dados_rel as $d) {
                if ($d->TITULO == $dados->TITULO)
                    $qtd++;
            }

            if ($titulo == "" || $titulo != $dados->TITULO) {
                $mpdf->AddPage();

                $tabela = '<h1 class="bg-white content-heading border-bottom" style="text-align:center; font-size:18px;">Relatório de Projetos por Usuário</h1>';
                $tabela .= '<p style="font-size:15px;">' . $dados->TITULO . ' <span style="color: #428bca;">(' . $qtd . ')</span></p>';

                $tabela .= '<table class="table">';
                $titulo = $dados->TITULO;
                $j++;
            } else
                $titulo = $dados->TITULO;

            $listaProgramas = "";

            $programas = $this->programa_proposta_model->get_programas_by_proposta($dados->idProposta);
            foreach ($programas as $p)
                $listaProgramas .= "- " . substr($p->nome_programa, 0, 180) . (strlen($p->nome_programa) > 180 ? "..." : "") . "<br>";

            $labelDataEnvio = "";
            if ($dados->TITULO == "Projetos Enviados Pelo Sistema")
                $labelDataEnvio = "Data Envio";

            $tabela .= "<tr style='background-color:#DCDCDC;'><td style='color:red;'>Data Criação</td><td>" . implode("/", array_reverse(explode("-", $dados->data))) . "</td><td style='color:red;'>{$labelDataEnvio}</td><td colspan='3'>" . implode("/", array_reverse(explode("-", $dados->data_envio))) . "</td></tr>";
            if ($dados->id_siconv != NULL && $dados->id_siconv != "") {
                $tabela .= "<tr><td>Código Siconv</td><td colspan='4'>" . $dados->id_siconv . "</td></tr>";
            } else {
                $tabela .= "<tr><td>Código Siconv</td><td colspan='4'>" . "Não enviada" . "</td></tr>";
            }
            $tabela .= "<tr><td>Município</td><td colspan='4'>" . $this->proponente_siconv_model->get_municipio_by_cnpj($dados->proponente)->municipio . "</td></tr>";
            $tabela .= "<tr><td>Área</td><td colspan='4'>{$dados->areanome}</td></tr>";
            $tabela .= "<tr><td>Nome do Projeto</td><td colspan='4'>{$dados->nome}</td></tr>";
            $tabela .= "<tr><td>Programa</td><td colspan='4'>{$listaProgramas}</td></tr>";
            $tabela .= "<tr><td>Valor</td><td>" . number_format($dados->valor_global, 2, ",", ".") . "</td><td>Data Inicio</td><td>" . implode("/", array_reverse(explode("-", $dados->data_inicio))) . "</td><td>Data Fim</td><td>" . implode("/", array_reverse(explode("-", $dados->data_termino))) . "</td></tr>";

            $tabela .= "<tr><td colspan='5'>&nbsp;</td></tr>";

            if (isset($dados_rel[$i + 1]->TITULO) && ($titulo == "" || $titulo == $dados_rel[$i + 1]->TITULO)) {
                
            } else {
                $tabela .= "</table>";

                $mpdf->WriteHTML($tabela);
            }

            $i++;
        }

        $mpdf->Output();

        die();
    }

    public function rel_desempenho_sistema() {
        $this->load->model('relatorio_model');
        $this->load->model('usuariomodel');
        $this->load->model('programa_proposta_model');

        $filtro = $this->input->post();

        $this->session->set_userdata('filtro_rel', $filtro);

        $data['programa_proposta_model'] = $this->programa_proposta_model;
        $data['usuariomodel'] = $this->usuariomodel;
        $data['dados_rel'] = $this->relatorio_model->get_dados_rel_sistema();
        $data['title'] = 'SIHS - Relatório Desempenho Sistema';
        $data['main'] = "relatorio/rel_desempenho_sistema";

        $this->load->view('in/template', $data);
    }

    public function gera_rel_quantitativo() {
        $this->load->model('relatorio_model');

        $this->load->library('mPDF');
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
                'content' => 'SIHS',
                'font-size' => 8
            ),
            'line' => 1
        );
        $mpdf->SetHeader($header, 'O');
        $mpdf->SetFooter('{DATE d/m/Y}||{PAGENO}/{nb}');

        $data = $this->relatorio_model->get_dados_resl_quantitativos($this->input->post('nome_governo', TRUE));

        $tabela = $this->load->view('relatorio/rel_quantitativo', $data, TRUE);

        $mpdf->WriteHTML($tabela);

        $mpdf->Output();

        die();
    }

    public function gera_rel_quantitativo_ajax() {
        $this->load->model('relatorio_model');

        $tabela = "";

        $tabela = utf8_encode($tabela);

        $data = $this->relatorio_model->get_dados_resl_quantitativos();

        $tabela = $this->load->view('relatorio/rel_quantitativo', $data, TRUE);

        echo $tabela;

        die();
    }

    public function rel_visita_representante() {
        $this->load->model('relatorio_model');
        $this->load->model('contato_municipio_model');
        $this->load->model('historico_contato_municipio_model');

        if ($this->session->userdata('nivel') == 4) {
            $filtro = $this->session->userdata('id_usuario');
            $this->session->set_userdata('filtro_rel', $filtro);
        } else {
            $filtro = $this->input->post('usuario', TRUE);
            $this->session->set_userdata('filtro_rel', $filtro);
        }

        $data['historico_contato_municipio_model'] = $this->historico_contato_municipio_model;
        $data['contato_municipio_model'] = $this->contato_municipio_model;
        $data['dados_rel'] = $this->relatorio_model->get_dados_visita_representante($filtro);

        $data['title'] = 'SIHS - Relatório Atividade Usuário';
        $data['main'] = "relatorio/rel_visita";

        $this->load->view('in/template', $data);
    }

    public function rel_visita_representante_pdf() {
        $this->load->model('relatorio_model');
        $this->load->model('contato_municipio_model');
        $this->load->model('historico_contato_municipio_model');

        $filtro = $this->session->userdata('filtro_rel');

        $data['historico_contato_municipio_model'] = $this->historico_contato_municipio_model;
        $data['contato_municipio_model'] = $this->contato_municipio_model;
        $dados_rel = $this->relatorio_model->get_dados_visita_representante($filtro);

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
                'content' => 'SIHS',
                'font-size' => 8
            ),
            'line' => 1
        );

        $mpdf->SetHeader($header, 'O');
        $mpdf->SetFooter('{DATE d/m/Y}||{PAGENO}/{nb}');

        //$tabela = $this->load->view('relatorio/rel_visita_pdf', $data, TRUE);


        $titulo = "";
        $i = 0;
        $j = 0;
        foreach ($dados_rel as $dados) {
            $qtd = 0;

            foreach ($dados_rel as $d) {
                if ($d->nome == $dados->nome)
                    $qtd++;
            }

            if ($titulo == "" || $titulo != $dados->nome) {
                if ($this->session->userdata('nivel') == 1)
                    $tabela = '<div style="text-align: center;"><h1>Relatório de Visitas Por Representante</h1></div>';
                else
                    $tabela = '<div style="text-align: center;"><h1>Relatório de Visitas Realizadas</h1></div>';

                $tabela .= '<div class="panel-heading">
								<h4 class="panel-title"><span style="color: red;">' . $dados->nome . ' <span style="color: #428bca;">(' . $qtd . ')</span></span></h4>
							</div>';

                $tabela .= '<table class="table" style="font-size:12px;">';

                $mpdf->AddPage();

                $titulo = $dados->nome;
                $j++;
            } else
                $titulo = $dados->nome;

            $tabela .= "<tr style='background-color:#DCDCDC;'>
						<td style='border: 1px solid; font-size:13px;' colspan='2'><b>Município:</b> {$dados->municipio} / {$dados->municipio_uf_sigla}</td>
						<td style='border: 1px solid;'><b>Contato:</b> {$dados->nome_contato}</td>
						<td style='border: 1px solid;'><b>Email:</b> {$dados->email_contato}</td>
						<td style='border: 1px solid;'><b>Telefones:</b> " . $this->contato_municipio_model->formataCelular($dados->telefone_contato) . " / " . $this->contato_municipio_model->formataCelular($dados->celular_contato) . " / " . $this->contato_municipio_model->formataCelular($dados->comercial_contato) . "</td>
						</tr>";

            $historico_contato = $this->historico_contato_municipio_model->get_all_historico($dados->id_contato_municipio);

            $tabela .= "<tr><td style='border: 1px solid;' colspan='5'><b>Histórico da Visita</b></td></tr>";

            foreach ($historico_contato as $historico) {
                $tabela .= "<tr>
							<td style='border: 1px solid;'><b>Status:</b> {$this->contato_municipio_model->getStatusContato($historico->status_contato)}</td>
							<td style='border: 1px solid;'><b>Data da Visita:</b> " . implode("/", array_reverse(explode("-", $historico->data_visita))) . "</td>
							<td style='border: 1px solid;'><b>Data do Retorno:</b> " . implode("/", array_reverse(explode("-", $historico->data_retorno))) . "</td>
							<td style='border: 1px solid;'><b>Classificação:</b> {$this->historico_contato_municipio_model->getClassVisita($historico->class_contato)}</td>
							<td style='width:400px; border: 1px solid;'><b>Obs Gerais:</b> {$historico->obs_gerais}</td>
							</tr>";
            }

            $tabela .= "<tr><td colspan='5'></td></tr>";

            if (isset($dados_rel[$i + 1]->nome) && ($titulo == "" || $titulo == $dados_rel[$i + 1]->nome)) {
                
            } else {
                $tabela .= "</table>";

                $mpdf->WriteHTML($tabela);
            }

            $i++;
        }

        $mpdf->Output();

        die();
    }

}
