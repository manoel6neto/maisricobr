<?php

include 'application/controllers/BaseController.php';

class controle_relatorios extends BaseController {

    public function __construct() {
        parent::__construct();

        $this->load->model('usuariomodel');
        $this->usuario_logado = $this->usuariomodel->get_by_id($this->session->userdata('id_usuario'));
        $this->session->set_userdata('nivel', $this->usuario_logado->id_nivel);
    }

    public function index() {
        $this->session->set_userdata('pagAtual', '');

        $this->load->model('banco_proposta_model');
        $this->load->model('relatorios_estatisticos_model');

        $data['anos'] = $this->banco_proposta_model->get_anos_by_usuario();
        if ($this->session->userdata('gp') == true || $this->input->get('gp', TRUE) != false) {
            $data['title'] = 'G&P - Relatório Nacional de Propostas e Programas';
        } else {
            $data['title'] = 'Physis - Relatório Nacional de Propostas e Programas';
        }
        $data['main'] = "relatorios_estatisticos/gera_relatorio";

        $this->load->view('relatorios_estatisticos/temp_relatorios_estatisticos', $data);
    }

    public function gera_estatistica() {
        ini_set('max_execution_time', 0);
        $this->load->library('mPDF');

        $this->load->model('relatorios_estatisticos_model');

        $regiao = $this->input->post('regiao', TRUE);
        $estado = $this->input->post('estado', TRUE);
        $municipio = $this->input->post('municipio', TRUE);
        $ano = $this->input->post('ano', TRUE);

        $dados = array();
        $ehPorRegiao = false;
        if ($regiao != "") {
            if ($estado != "") {
                if ($municipio != "")
                    $dados = $this->relatorios_estatisticos_model->get_relatorios_municipio($municipio, $ano);
                else
                    $dados = $this->relatorios_estatisticos_model->get_relatorio_estado($estado, $ano);
            }else {
                $ehPorRegiao = true;
                $dados = $this->relatorios_estatisticos_model->get_relatorios_regiao($regiao, $ano);
            }
        } else {
            $ehPorRegiao = true;
            $dados = $this->relatorios_estatisticos_model->get_relatorios_regiao($regiao, $ano);
        }

        $data['nomeEstado'] = $estado;
        $data['nomeMunicipio'] = $municipio;
        $data['ano'] = $ano;

        $data['ehPorRegiao'] = $ehPorRegiao;

        $data['dados'] = $dados;

        $data['totalMunicipios'] = 0;
        $data['totalProgramasAbertos'] = 0;
        $data['totalProgramasUtilizados'] = 0;
        $data['totalVoluntarias'] = 0;
        $data['totalEmenda'] = 0;
        $data['totalEspecifico'] = 0;
        $data['totalEnviadas'] = 0;
        $data['totalAprovadas'] = 0;
        $data['totalSemEnvio'] = 0;
        $data['totalSemAprovada'] = 0;
        $data['totalUmaAprovada'] = 0;

        if ($ehPorRegiao) {
            $data['programasAbertos'] = array();
            $data['programasUtilizados'] = array();
            foreach ($dados as $linha) {
                $data['totalMunicipios'] = $data['totalMunicipios'] + $linha['quantidade_municipios'];
                $data['totalProgramasAbertos'] = $data['totalProgramasAbertos'] + $linha['programas_abertos'];
                $data['programasAbertos'] = array_merge($data['programasAbertos'], $linha['programas_abertos_objects']);
                $data['totalProgramasUtilizados'] = $data['totalProgramasUtilizados'] + $linha['programas_utilizados'];
                $data['programasUtilizados'] = array_merge($data['programasUtilizados'], $linha['programas_utilizados_objects']);
                $data['totalVoluntarias'] = $data['totalVoluntarias'] + $linha['qualificacao_voluntaria'];
                $data['totalEmenda'] = $data['totalEmenda'] + $linha['qualificacao_emenda'];
                $data['totalEspecifico'] = $data['totalEspecifico'] + $linha['qualificacao_especifico'];
                $data['totalEnviadas'] = $data['totalEnviadas'] + $linha['propostas_enviadas'];
                $data['totalAprovadas'] = $data['totalAprovadas'] + $linha['propostas_aprovadas'];
                $data['totalSemEnvio'] = $data['totalSemEnvio'] + $linha['cidades_sem_propostas_enviadas'];
                $data['totalSemAprovada'] = $data['totalSemAprovada'] + $linha['cidades_sem_propostas_aprovadas'];
                $data['totalUmaAprovada'] = $data['totalUmaAprovada'] + $linha['cidades_apenas_uma_proposta_aprovada'];
            }
            $programasAbertosUnicos = array();
            $programasUtilizadosUnicos = array();

            $data['totalProgramasAbertosUnicos'] = 0;
            $data['totalProgramasUtilizadosUnicos'] = 0;

            foreach ($data['programasAbertos'] as $prog) {
                if (!in_array($prog, $programasAbertosUnicos)) {
                    array_push($programasAbertosUnicos, $prog);
                }
            }

            foreach ($data['programasUtilizados'] as $prog) {
                if (!in_array($prog, $programasUtilizadosUnicos)) {
                    array_push($programasUtilizadosUnicos, $prog);
                }
            }

            $data['totalProgramasAbertosUnicos'] = count($programasAbertosUnicos);
            $data['totalProgramasUtilizadosUnicos'] = count($programasUtilizadosUnicos);
        }

        $mostraQtdMunicipio = $municipio == '' ? true : false;
        $data['mostraQtdMunicipio'] = $mostraQtdMunicipio;

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

        $tabela = $this->load->view('relatorios_estatisticos/pdf_estatistica', $data, TRUE);

//        echo '<pre>';
//        print_r($tabela);
//        echo '</pre>';
//        
//        die();
//        echo $tabela;
        $mpdf->WriteHTML($tabela);
//        
        $mpdf->Output();

        exit(0);
    }

    public function busca_estados() {
        $this->load->model('relatorios_estatisticos_model');

        $sigla_regiao = $this->input->post('regiao', TRUE);
        $estados = $this->relatorios_estatisticos_model->get_all_estados_regiao($sigla_regiao);
        asort($estados);

        $options = "<option value=''>Todos</option>";
        if (!empty($estados)) {
            foreach ($estados as $estado) {
                $options .= "<option value='{$estado}'>{$estado}</option>";
            }
        }

        echo $options;
    }

    public function busca_municipios() {
        $this->load->model('relatorios_estatisticos_model');

        $nome_municipio = $this->input->post('estado', TRUE);
        $municipios = $this->relatorios_estatisticos_model->get_all_cidades_estado($nome_municipio);
        asort($municipios);

        $options = "<option value=''>Todos</option>";
        if (!empty($municipios)) {
            foreach ($municipios as $municipio) {
                $options .= '<option value="' . utf8_decode(trim_slashes(trim($municipio->municipio))) . '">' . $municipio->municipio . '</option>';
            }
        }

        echo $options;
    }

}
