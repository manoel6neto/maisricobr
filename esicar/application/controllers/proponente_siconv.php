<?php

include 'application/controllers/BaseController.php';

class proponente_siconv extends BaseController {

    public function __construct() {
        parent::__construct();
    }

    public function get_lista_cidades() {
        $this->load->model('proponente_siconv_model');

        $municipio = $this->input->post('municipio', TRUE);

        $listaCidades = $this->proponente_siconv_model->get_municipio($this->input->post('uf', TRUE));

        if ($this->input->post('multiselect', TRUE)) {
            $option = array();
            foreach ($listaCidades as $cidade) {
                $option[] = array("label" => $cidade->municipio, "value" => $cidade->codigo_municipio);
            }
            echo json_encode($option);
        } else {
            $option = "<option value=''>Escolha</option>";
            foreach ($listaCidades as $cidade) {
                if ($cidade->codigo_municipio == $municipio)
                    $selected = "selected";
                else
                    $selected = "";
                $option .= "<option " . $selected . " value='" . $cidade->codigo_municipio . "'>" . $cidade->municipio . "</option>";
            }
            echo $option;
        }
    }

    public function get_lista_cidades_limitado() {
        $this->load->model('proponente_siconv_model');
        $this->load->model('municipios_direito_vendedor_model');

        $municipio = $this->input->post('municipio', TRUE);

        $listaCidades = $this->proponente_siconv_model->get_municipio($this->input->post('uf', TRUE));
        $municipio_permitidos = $this->municipios_direito_vendedor_model->get_lista_cidades_bloqueadas($this->session->userdata('id_usuario'));
        foreach ($municipio_permitidos as $key => $value) {
            $listaPermitidos[] = $key;
        }
        $array_permitidos = array();
        foreach ($listaPermitidos as $citys) {
            array_push($array_permitidos, $citys);
        }

        $option = "<option value=''>Escolha</option>";
        foreach ($listaCidades as $cidade) {
            if (in_array($cidade->codigo_municipio, $array_permitidos)) {
                if ($cidade->codigo_municipio == $municipio)
                    $selected = "selected";
                else
                    $selected = "";
                $option .= "<option " . $selected . " value='" . $cidade->codigo_municipio . "'>" . $cidade->municipio . "</option>";
            }
        }

        echo $option;
    }

    public function get_lista_cidades_restrito() {
        $this->load->model('proponente_siconv_model');
        $this->load->model('municipios_direito_vendedor_model');

        $municipios = $this->input->post('municipio_restrito', TRUE);
        if ($municipios == NULL)
            $municipios = array(0);

        $uf = $this->input->post('uf', TRUE);
        if (is_array($uf)) {
            $listaCidades = $this->proponente_siconv_model->get_municipio_estados($uf);
            if ($this->session->userdata('nivel') == 15) {
                $municipio_permitidos = $this->municipios_direito_vendedor_model->get_lista_cidades_bloqueadas($this->session->userdata('id_usuario'));
                foreach ($municipio_permitidos as $key => $value) {
                    $listaPermitidos[] = $key;
                }
                $array_permitidos = array();
                foreach ($listaPermitidos as $citys) {
                    array_push($array_permitidos, $citys);
                }

                $option = array();
                foreach ($listaCidades as $cidade) {
                    if (in_array($cidade->codigo_municipio, $array_permitidos)) {
                        if (in_array($cidade->codigo_municipio, $municipios))
                            $selected = "selected";
                        else
                            $selected = "";
                        $option[] = array("label" => $cidade->municipio, "value" => $cidade->codigo_municipio, "selected" => $selected);
                    }
                }
            }else {
                $option = array();
                foreach ($listaCidades as $cidade) {
                    if (in_array($cidade->codigo_municipio, $municipios))
                        $selected = "selected";
                    else
                        $selected = "";
                    $option[] = array("label" => $cidade->municipio, "value" => $cidade->codigo_municipio, "selected" => $selected);
                }
            }

            echo json_encode($option);
        } else {
            $option[] = array();
            echo json_encode($option);
        }
    }

    public function get_lista_proponentes() {
        $this->load->model('proponente_siconv_model');

        $listaCidades = $this->proponente_siconv_model->get_proponentes($this->input->post('esfera', TRUE), $this->input->post('municipio', TRUE), $this->input->post('uf', TRUE), $this->input->post('tipo', TRUE), $this->input->post('id', TRUE), ($this->session->userdata('nivel') == 2 && $this->session->userdata('usuario_sistema') != "P"));

        $option = array();
        foreach ($listaCidades as $cidade) {
            $option[] = array("label" => $cidade->cnpj . " - " . $cidade->nome, "value" => $cidade->cnpj);
        }

        echo json_encode($option);
    }

    public function relacao_entidades() {
        $this->session->set_userdata('pagAtual', 'relacao_entidades');

        $this->load->model('proponente_siconv_model');

        $data['lista_proponentes'] = null;
        $data['filtro'] = null;
        $data['filtro_estado'] = null;
        $data['filtro_municipio'] = null;
        $data['filtro_esfera'] = null;
        $data['filtro_situacao'] = null;

        if ($this->input->post('estado', TRUE) != false) {
            $data['filtro_estado'] = $this->input->post('estado', TRUE);
        }

        if ($this->input->post('municipio', TRUE) != false) {
            $data['filtro_municipio'] = $this->input->post('municipio', TRUE);
        }

        if ($this->input->post('esfera', TRUE) != false) {
            $data['filtro_esfera'] = $this->input->post('esfera', TRUE);
        }

        if ($this->input->post('nome_entidade', TRUE) != false) {
            $data['filtro'] = $this->input->post('nome_entidade', TRUE);
        }

        if ($this->input->post('situacao', TRUE) != false) {
            $data['filtro_situacao'] = $this->input->post('situacao', TRUE);
        }

        if ($this->input->post('nome_entidade', TRUE) != false || $this->input->post('esfera', TRUE) != false || $this->input->post('municipio', TRUE) != false || $this->input->post('estado', TRUE) != false) {
            $data['lista_proponentes'] = $this->proponente_siconv_model->get_entidades_multi_filtro($this->input->post('nome_entidade', TRUE), $this->input->post('esfera', TRUE), $this->input->post('municipio', TRUE), $this->input->post('estado', TRUE), $this->input->post('situacao', TRUE));
        }

        $data['proponente_siconv_model'] = $this->proponente_siconv_model;
        $data['title'] = 'Physis - Relação de Entidades';
        $data['main'] = "proponente_siconv/index";

        $this->load->view('in/template', $data);
    }

    public function gerar_pdf_rel() {
        ini_set('max_execution_time', 0);
        //ini_set("memory_limit", "256M");
        ini_set("memory_limit", "-1");

        if ($this->input->post('id_proponente', TRUE) != false) {
            $this->load->model('proponente_siconv_model');

            $lista_proponentes = $this->proponente_siconv_model->get_entidades_by_id($this->input->post('id_proponente', TRUE));

            $this->load->library('mPDF');

            ob_start(); // inicia o buffer
            $tabela = utf8_encode($tabela);

            $mpdf = new mPDF('', 'A4-L');
//            $mpdf->cacheTables = true;
//            $mpdf->simpleTables = true;
            $mpdf->packTableData = true;
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
            $tabela = "<div style='color:red; text-align:center;'>Relação de Entidades Cadastradas no SICONV</div><br>";
            $tabela .= "<table class='table' style=\"font-size: 13px; border-collapse: collapse;\">
					<tr><th colspan='9' style='color: red; text-align:center; font-size: 16px; border: 1px solid;'><span>Total de Registros: " . count($lista_proponentes) . "</span></th></tr>
			                    	
			                    	<tr style='color: #428bca; font-size: 16px;'>
			                    		<th style='border: 1px solid;'>Nome Entidade</th>
			                    		<th style='border: 1px solid;'>CNPJ</th>
                                                        <th style='border: 1px solid;'>Esfera Administrativa</th>
			                    		<th style='border: 1px solid;'>Município</th>
			                    		<th style='border: 1px solid;'>UF</th>
			                    		<th style='border: 1px solid;'>Responsável</th>
                                                        <th style='border: 1px solid;'>Email</th>
                                                        <th style='border: 1px solid;'>Situação</th>
                                                        <th style='border: 1px solid;'>Datas</th>
			                    	</tr>";

            foreach ($lista_proponentes as $p):
                //Situacao entidade privada
                if ($p->esfera_administrativa == 'PRIVADA') {
                    if ($p->situacao_aprovacao == null) {
                        $tabela .= "<tr>
	                    		<td style='border: 1px solid;'>" . $p->nome . "</td>
	                    		<td style='border: 1px solid;'>" . $p->cnpj . "</td>
	                    		<td style='border: 1px solid;'>" . $p->esfera_administrativa . "</td>
	                    		<td style='border: 1px solid;'>" . $p->municipio . "</td>
	                    		<td style='border: 1px solid;'>" . $p->municipio_uf_nome . "</td>
	                    		<td style='border: 1px solid;'>" . $p->nome_responsavel . "</td>
                                        <td style='border: 1px solid;'>" . $p->email . "</td>
	                    		<td style='border: 1px solid;'> - </td>
                                        <td style='border: 1px solid;'> - </td>
	                    	</tr>";
                    } else {
                        $tabela .= "<tr>
	                    		<td style='border: 1px solid;'>" . $p->nome . "</td>
	                    		<td style='border: 1px solid;'>" . $p->cnpj . "</td>
	                    		<td style='border: 1px solid;'>" . $p->esfera_administrativa . "</td>
	                    		<td style='border: 1px solid;'>" . $p->municipio . "</td>
	                    		<td style='border: 1px solid;'>" . $p->municipio_uf_nome . "</td>
	                    		<td style='border: 1px solid;'>" . $p->nome_responsavel . "</td>
                                        <td style='border: 1px solid;'>" . $p->email . "</td>
	                    		<td style='border: 1px solid;'>" . $p->situacao_aprovacao . "</td>
                                        <td style='border: 1px solid;'>" . implode("/", array_reverse(explode("-", $p->data_registro))) . " - " . implode("/", array_reverse(explode("-", $p->data_vencimento))) . "</td>
	                    	</tr>";
                    }
                } else {
                    $tabela .= "<tr>
	                    		<td style='border: 1px solid;'>" . $p->nome . "</td>
	                    		<td style='border: 1px solid;'>" . $p->cnpj . "</td>
	                    		<td style='border: 1px solid;'>" . $p->esfera_administrativa . "</td>
	                    		<td style='border: 1px solid;'>" . $p->municipio . "</td>
	                    		<td style='border: 1px solid;'>" . $p->municipio_uf_nome . "</td>
	                    		<td style='border: 1px solid;'>" . $p->nome_responsavel . "</td>
                                        <td style='border: 1px solid;'>" . $p->email . "</td>
	                    		<td style='border: 1px solid;'>" . $p->situacao . "</td>
                                        <td style='border: 1px solid;'> - </td>
	                    	</tr>";
                }
            endforeach;

            $tabela .= "</table>";

            $mpdf->WriteHTML($tabela);

            $mpdf->Output('entidades.pdf', 'D');

            die();
        }
    }

}
