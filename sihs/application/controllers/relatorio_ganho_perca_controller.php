<?php

include 'application/controllers/BaseController.php';

class relatorio_ganho_perca_controller extends BaseController {

    public function __construct() {
        parent::__construct();
    }

    public function lista_ganhos_maiores_cidades() {
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '-1');

        $this->load->model('relatorio_ganho_perca_model', 'modelrel');

        $ano = $this->input->get_post('ano', TRUE);
        if ($ano == NULL) {
            $ano = '2016';
        }

        $dados_cidades_tabela_array = array();

        $cidades = $this->modelrel->get_maiores_cidades();

        $soma_total = doubleval(0);
        $total_vol = 0;
        $total_emenda = 0;
        $total_parlamentar = 0;
        foreach ($cidades as $cidade) {
            $propostas_cidade = $this->modelrel->get_propostas_por_cidade($cidade->municipio, $ano);

            if ($propostas_cidade != NULL) {
                $soma = doubleval(0);
                $quantidade_propostas = 0;
                $quantidade_voluntaria = 0;
                $quantidade_emendas = 0;
                $quantidade_emendas_parlamentar = 0;
                foreach ($propostas_cidade as $prop) {
                    $soma = doubleval($soma) + doubleval(trim(explode("R$", str_replace(',', '.', str_replace('.', '', $prop->valor_global)))[1]));
                    $soma_total = $soma_total + doubleval(trim(explode("R$", str_replace(',', '.', str_replace('.', '', $prop->valor_global)))[1]));
                    $quantidade_propostas = $quantidade_propostas + 1;
                    if (strpos(trim($prop->tipo), trim('Repasse')) !== false) {
                        $quantidade_voluntaria = $quantidade_voluntaria + 1;
                        $total_vol = $total_vol + 1;
                    } else {
                        if (strpos(trim($prop->tipo), trim('Parlamentar')) !== false) {
                            $quantidade_emendas_parlamentar = $quantidade_emendas_parlamentar + 1;
                            $total_parlamentar = $total_parlamentar + 1;
                        } else {
                            $quantidade_emendas = $quantidade_emendas + 1;
                            $total_emenda = $total_emenda + 1;
                        }
                    }
                }

                $array_dados_tabela = array(
                    'cidade' => $cidade->municipio,
                    'quantidade_total_propostas' => $quantidade_propostas,
                    'quantidade_voluntarias' => $quantidade_voluntaria,
                    'quantidade_emendas' => $quantidade_emendas,
                    'quantidade_emendas_parlamentar' => $quantidade_emendas_parlamentar,
                    'soma' => $soma
                );

                array_push($dados_cidades_tabela_array, $array_dados_tabela);
            }
        }

        $data['soma_total'] = $soma_total;
        $data['total'] = $total_emenda + $total_parlamentar + $total_vol;
        $data['total_vol'] = $total_vol;
        $data['total_emenda'] = $total_emenda;
        $data['total_parlamentar'] = $total_parlamentar;
        $data['dados_tabela'] = $dados_cidades_tabela_array;
        $data['ano'] = $ano;
        $data['anos'] = array('2008', '2009', '2010', '2011', '2012', '2013', '2014', '2015', '2016');
        $data['title'] = 'Physis - Relatório Estatístico por cidades';
        $data['main'] = "relatorios_estatisticos/relatorio_cidades";
        $this->load->view('relatorios_estatisticos/temp_relatorios_estatisticos', $data);
    }

    public function lista_ganhos_maiores_cidades_pdf() {
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '-1');

        $this->load->model('relatorio_ganho_perca_model', 'modelrel');

        $ano = $this->input->get_post('ano', TRUE);
        if ($ano == NULL) {
            $ano = '2016';
        }

        $dados_cidades_tabela_array = array();

        $cidades = $this->modelrel->get_maiores_cidades();

        $soma_total = doubleval(0);
        $total_vol = 0;
        $total_emenda = 0;
        $total_parlamentar = 0;
        foreach ($cidades as $cidade) {
            $propostas_cidade = $this->modelrel->get_propostas_por_cidade($cidade->municipio, $ano);

            if ($propostas_cidade != NULL) {
                $soma = doubleval(0);
                $quantidade_propostas = 0;
                $quantidade_voluntaria = 0;
                $quantidade_emendas = 0;
                $quantidade_emendas_parlamentar = 0;
                foreach ($propostas_cidade as $prop) {
                    $soma = doubleval($soma) + doubleval(trim(explode("R$", str_replace(',', '.', str_replace('.', '', $prop->valor_global)))[1]));
                    $soma_total = $soma_total + doubleval(trim(explode("R$", str_replace(',', '.', str_replace('.', '', $prop->valor_global)))[1]));
                    $quantidade_propostas = $quantidade_propostas + 1;
                    if (strpos(trim($prop->tipo), trim('Repasse')) !== false) {
                        $quantidade_voluntaria = $quantidade_voluntaria + 1;
                        $total_vol = $total_vol + 1;
                    } else {
                        if (strpos(trim($prop->tipo), trim('Parlamentar')) !== false) {
                            $quantidade_emendas_parlamentar = $quantidade_emendas_parlamentar + 1;
                            $total_parlamentar = $total_parlamentar + 1;
                        } else {
                            $quantidade_emendas = $quantidade_emendas + 1;
                            $total_emenda = $total_emenda + 1;
                        }
                    }
                }

                $array_dados_tabela = array(
                    'cidade' => $cidade->municipio,
                    'quantidade_total_propostas' => $quantidade_propostas,
                    'quantidade_voluntarias' => $quantidade_voluntaria,
                    'quantidade_emendas' => $quantidade_emendas,
                    'quantidade_emendas_parlamentar' => $quantidade_emendas_parlamentar,
                    'soma' => $soma
                );

                array_push($dados_cidades_tabela_array, $array_dados_tabela);
            }
        }

        $this->load->library('mPDF');
        ob_start(); // inicia o buffer
        $tabela = utf8_encode($tabela);

        $mpdf = new mPDF();
        $mpdf->allow_charset_conversion = true;
        $mpdf->charset_in = 'UTF-8';

        $header = array(
            'L' => array(
                'content' => 'PHYSIS BRASIL',
                'font-size' => 8
            ),
            'C' => array(
                'content' => strtoupper("Relatório ganhos cidades Paraná."),
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

        $tabela = '<h3 class="bg-white content-heading border-bottom">Relatório Estatístico Ganhos Cidades - Ano:' . $ano . ' - ' . count($dados_tabela) . 'Cidades</h3>';
        $tabela .= '<table style="width: 100%; margin-left: 100px; border-collapse: collapse;">
                    <thead style="width: 100%">
                        <tr style="color: #428bca; font-size: 14px;">
                            <th>Cidade</th>
                            <th>Quant. Propostas</th>
                            <th>Quant. Voluntárias</th>
                            <th>Quant. Emendas Espc.</th>
                            <th>Quant. Emendas Parl.</th>
                            <th>Valor Global</th>
                        </tr>
                    </thead>
                    <tbody>';
        if (isset($dados_cidades_tabela_array) && count($dados_cidades_tabela_array) > 0) {
            foreach ($dados_cidades_tabela_array as $linha) {
                $tabela .= '<tr style="color: #31708f; font-size: 12px;">';
                $tabela .= '<td>' . $linha['cidade'] . '</td>';
                $tabela .= '<td>' . $linha['quantidade_total_propostas'] . '</td>';
                $tabela .= '<td>' . $linha['quantidade_voluntarias'] . '</td>';
                $tabela .= '<td>' . $linha['quantidade_emendas'] . '</td>';
                $tabela .= '<td>' . $linha['quantidade_emendas_parlamentar'] . '</td>';
                $tabela .= '<td>' . 'R$ ' . number_format($linha['soma'], 2, ',', '.') . '</td></tr>';
            }
            $tabela .= '<tr style="color: #31708f; font-size: 12px;">';
            $tabela .= '<td>Total: </td>';
            $tabela .= '<td>' . ($total_vol + $total_emenda + $total_parlamentar) . '</td>';
            $tabela .= '<td>' . $total_vol . '</td>';
            $tabela .= '<td>' . $total_emenda . '</td>';
            $tabela .= '<td>' . $total_parlamentar . '</td>';
            $tabela .= '<td>' . 'R$ ' . number_format($soma_total, 2, ',', '.') . '</td></tr>';
        } else {
            $tabela = '<h3>Nenhum dado encontrado</h3>';
        }

        $tabela .= "</tbody></table>";
        $mpdf->WriteHTML($tabela);
        $mpdf->Output();
        die();
    }

    public function lista_percas_emendas_maiores_cidades_pdf() {
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '-1');

        $this->load->model('relatorio_ganho_perca_model', 'modelrel');

        $ano = $this->input->get_post('ano', TRUE);
        if ($ano == NULL) {
            $ano = '2016';
        }

        $dados_cidades_tabela_array = array();

        $cidades = $this->modelrel->get_maiores_cidades();

        $destinadas_total = 0;
        $utilizadas_total = 0;
        $parlamentar_total = 0;
        $soma_total_dest = doubleval(0);
        $soma_total_parlamentar = doubleval(0);
        $soma_total_utilizada = doubleval(0);
        foreach ($cidades as $cidade) {
            $emendas_cidade = $this->modelrel->get_emendas_por_cidade($cidade->municipio, $ano);
            $propostas = $this->modelrel->get_propostas_por_cidade($cidade->municipio, $ano);

            if ($emendas_cidade != NULL) {
                $soma = doubleval(0);
                $soma_parlamanetar = doubleval(0);
                $quantidade_parlamentar = 0;
                $quantidade_emendas = 0;
                $soma_destinada = doubleval(0);
                $quantidade_destinadas = 0;
                foreach ($emendas_cidade as $emenda) {
                    $soma_destinada = doubleval($soma_destinada) + doubleval(trim(explode("R$", str_replace(',', '.', str_replace('.', '', $emenda->valor)))[1]));
                    $soma_total_dest = doubleval($soma_total_dest) + doubleval(trim(explode("R$", str_replace(',', '.', str_replace('.', '', $emenda->valor)))[1]));
                    $quantidade_destinadas = $quantidade_destinadas + 1;
                    $destinadas_total = $destinadas_total + 1;
                    $nutilizada = true;
                    if ($propostas != NULL && count($propostas) > 0) {
                        foreach ($propostas as $prop) {
                            if ($prop->codigo_programa == $emenda->codigo_programa && $prop->ano == $ano && strpos(trim($prop->tipo), trim('Repasse')) == false) {
                                $nutilizada = false;
                                break;
                            }
                        }
                    }

                    if ($nutilizada) {
                        if ($emenda->data_inicio_parlam != NULL) {
                            $quantidade_parlamentar = $quantidade_parlamentar + 1;
                            $soma_parlamanetar = doubleval($soma_parlamanetar) + doubleval(trim(explode("R$", str_replace(',', '.', str_replace('.', '', $emenda->valor)))[1]));
                            $parlamentar_total = $parlamentar_total + 1;
                            $soma_total_parlamentar = doubleval($soma_total_parlamentar) + + doubleval(trim(explode("R$", str_replace(',', '.', str_replace('.', '', $emenda->valor)))[1]));
                        } else {
                            $quantidade_emendas = $quantidade_emendas + 1;
                            $soma = doubleval($soma) + doubleval(trim(explode("R$", str_replace(',', '.', str_replace('.', '', $emenda->valor)))[1]));
                            $utilizadas_total = $utilizadas_total + 1;
                            $soma_total_utilizada = doubleval($soma_total_utilizada) + doubleval(trim(explode("R$", str_replace(',', '.', str_replace('.', '', $emenda->valor)))[1]));
                        }
                    }
                }

                $array_dados_tabela = array(
                    'cidade' => $cidade->municipio,
                    'quantidade_emendas' => $quantidade_emendas,
                    'soma' => $soma,
                    'quantidade_destinadas' => $quantidade_destinadas,
                    'soma_destinadas' => $soma_destinada,
                    'quantidade_parlamentar' => $quantidade_parlamentar,
                    'soma_parlamentar' => $soma_parlamanetar
                );

                array_push($dados_cidades_tabela_array, $array_dados_tabela);
            }
        }

        $this->load->library('mPDF');
        ob_start(); // inicia o buffer
        $tabela = utf8_encode($tabela);

        $mpdf = new mPDF();
        $mpdf->allow_charset_conversion = true;
        $mpdf->charset_in = 'UTF-8';

        $header = array(
            'L' => array(
                'content' => 'PHYSIS BRASIL',
                'font-size' => 8
            ),
            'C' => array(
                'content' => strtoupper("Relatório ganhos cidades Paraná."),
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

        $tabela .= '<h3 class="bg-white content-heading border-bottom">Relatório Estatístico Perdas Emendas Cidades - Ano: ' . $ano . ' - ' . count($dados_tabela) . 'Cidades</h3>';
        $tabela .= '<table style="width: 100%; margin-left: 100px; border-collapse: collapse;">';
        $tabela .= '<thead style="width: 100%">
                        <tr style="color: #428bca; font-size: 14px;">
                            <th>Cidade</th>
                            <th>Emendas Destinadas</th>
                            <th>Valor Destinado</th>
                            <th>Emendas Perdidas Espc.</th>
                            <th>Valor Perdido Espc.</th>
                            <th>Emendas Perdidas Parl.</th>
                            <th>Valor Perdido Parl.</th>
                        </tr>
                    </thead>
                    <tbody>';

        if (isset($dados_cidades_tabela_array) && count($dados_cidades_tabela_array) > 0) {
            foreach ($dados_cidades_tabela_array as $linha) {
                $tabela .= '<tr style="color: #31708f; font-size: 12px;">';
                $tabela .= '<td>' . $linha['cidade'] . '</td>';
                $tabela .= '<td>' . $linha['quantidade_destinadas'] . '</td>';
                $tabela .= '<td>' . 'R$ ' . number_format($linha['soma_destinadas'], 2, ',', '.') . '</td>';
                $tabela .= '<td>' . $linha['quantidade_emendas'] . '</td>';
                $tabela .= '<td>' . 'R$ ' . number_format($linha['soma'], 2, ',', '.') . '</td>';
                $tabela .= '<td>' . $linha['quantidade_parlamentar'] . '</td>';
                $tabela .= '<td>' . 'R$ ' . number_format($linha['soma_parlamentar'], 2, ',', '.') . '</td></tr>';
            }
            $tabela .= '<tr style="color: #31708f; font-size: 12px;">';
            $tabela .= '<td>Total: </td>';
            $tabela .= '<td>' . $quantidade_total_destinadas . '</td>';
            $tabela .= '<td>' . 'R$ ' . number_format($soma_total_destinadas, 2, ',', '.') . '</td>';
            $tabela .= '<td>' . $quantidade_total_utilizadas . '</td>';
            $tabela .= '<td>' . 'R$ ' . number_format($soma_total_utilizadas, 2, ',', '.') . '</td>';
            $tabela .= '<td>' . $quantidade_total_parlamentar . '</td>';
            $tabela .= '<td>' . 'R$ ' . number_format($soma_total_parlamentar, 2, ',', '.') . '</td></tr>';
        } else {
            $tabela = '<h3>Nenhum dado encontrado</h3>';
        }

        $tabela .= "</tbody></table>";
        $mpdf->WriteHTML($tabela);
        $mpdf->Output();
        die();
    }

    public function lista_percas_emendas_maiores_cidades() {
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '-1');

        $this->load->model('relatorio_ganho_perca_model', 'modelrel');

        $ano = $this->input->get_post('ano', TRUE);
        if ($ano == NULL) {
            $ano = '2016';
        }

        $dados_cidades_tabela_array = array();

        $cidades = $this->modelrel->get_maiores_cidades();

        $destinadas_total = 0;
        $utilizadas_total = 0;
        $parlamentar_total = 0;
        $soma_total_dest = doubleval(0);
        $soma_total_utilizada = doubleval(0);
        $soma_total_parlamentar = doubleval(0);
        foreach ($cidades as $cidade) {
            $emendas_cidade = $this->modelrel->get_emendas_por_cidade($cidade->municipio, $ano);
            $propostas = $this->modelrel->get_propostas_por_cidade($cidade->municipio, $ano);

            if ($emendas_cidade != NULL) {
                $soma = doubleval(0);
                $soma_parlamanetar = doubleval(0);
                $quantidade_emendas = 0;
                $soma_destinada = doubleval(0);
                $quantidade_destinadas = 0;
                $quantidade_parlamentar = 0;
                foreach ($emendas_cidade as $emenda) {
                    $soma_destinada = doubleval($soma_destinada) + doubleval(trim(explode("R$", str_replace(',', '.', str_replace('.', '', $emenda->valor)))[1]));
                    $soma_total_dest = doubleval($soma_total_dest) + doubleval(trim(explode("R$", str_replace(',', '.', str_replace('.', '', $emenda->valor)))[1]));
                    $quantidade_destinadas = $quantidade_destinadas + 1;
                    $destinadas_total = $destinadas_total + 1;
                    $nutilizada = true;
                    if ($propostas != NULL && count($propostas) > 0) {
                        foreach ($propostas as $prop) {
                            if ($prop->codigo_programa == $emenda->codigo_programa && $prop->ano == $ano && strpos(trim($prop->tipo), trim('Repasse')) == false) {
                                $nutilizada = false;
                                break;
                            }
                        }
                    }

                    if ($nutilizada) {
                        if ($emenda->data_inicio_parlam != NULL) {
                            $quantidade_parlamentar = $quantidade_parlamentar + 1;
                            $soma_parlamanetar = doubleval($soma_parlamanetar) + doubleval(trim(explode("R$", str_replace(',', '.', str_replace('.', '', $emenda->valor)))[1]));
                            $parlamentar_total = $parlamentar_total + 1;
                            $soma_total_parlamentar = doubleval($soma_total_parlamentar) + + doubleval(trim(explode("R$", str_replace(',', '.', str_replace('.', '', $emenda->valor)))[1]));
                        } else {
                            $quantidade_emendas = $quantidade_emendas + 1;
                            $soma = doubleval($soma) + doubleval(trim(explode("R$", str_replace(',', '.', str_replace('.', '', $emenda->valor)))[1]));
                            $utilizadas_total = $utilizadas_total + 1;
                            $soma_total_utilizada = doubleval($soma_total_utilizada) + doubleval(trim(explode("R$", str_replace(',', '.', str_replace('.', '', $emenda->valor)))[1]));
                        }
                    }
                }

                $array_dados_tabela = array(
                    'cidade' => $cidade->municipio,
                    'quantidade_emendas' => $quantidade_emendas,
                    'soma' => $soma,
                    'quantidade_destinadas' => $quantidade_destinadas,
                    'soma_destinadas' => $soma_destinada,
                    'quantidade_parlamentar' => $quantidade_parlamentar,
                    'soma_parlamentar' => $soma_parlamanetar
                );

                array_push($dados_cidades_tabela_array, $array_dados_tabela);
            }
        }

        $data['quantidade_total_destinadas'] = $destinadas_total;
        $data['quantidade_total_utilizadas'] = $utilizadas_total;
        $data['soma_total_destinadas'] = $soma_total_dest;
        $data['soma_total_utilizadas'] = $soma_total_utilizada;
        $data['soma_total_parlamentar'] = $soma_total_parlamentar;
        $data['quantidade_total_parlamentar'] = $parlamentar_total;
        $data['dados_tabela'] = $dados_cidades_tabela_array;
        $data['ano'] = $ano;
        $data['anos'] = array('2008', '2009', '2010', '2011', '2012', '2013', '2014', '2015', '2016');
        $data['title'] = 'Physis - Relatório Estatístico por cidades';
        $data['main'] = "relatorios_estatisticos/relatorio_cidades_percas";
        $this->load->view('relatorios_estatisticos/temp_relatorios_estatisticos', $data);
    }

    public function lista_ganhos_maiores_cidades_excel() {
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '-1');

        $this->load->model('relatorio_ganho_perca_model', 'modelrel');

        $ano = $this->input->get_post('ano', TRUE);
        if ($ano == NULL) {
            $ano = '2016';
        }

        $dados_cidades_tabela_array = array();

        $cidades = $this->modelrel->get_maiores_cidades();

        $soma_total = doubleval(0);
        foreach ($cidades as $cidade) {
            $propostas_cidade = $this->modelrel->get_propostas_por_cidade($cidade->municipio, $ano);

            if ($propostas_cidade != NULL) {
                $soma = doubleval(0);
                $quantidade_propostas = 0;
                $quantidade_voluntaria = 0;
                $quantidade_emendas = 0;
                $quantidade_emendas_parlamentar = 0;
                foreach ($propostas_cidade as $prop) {
                    $soma = doubleval($soma) + doubleval(trim(explode("R$", str_replace(',', '.', str_replace('.', '', $prop->valor_global)))[1]));
                    $soma_total = $soma_total + doubleval(trim(explode("R$", str_replace(',', '.', str_replace('.', '', $prop->valor_global)))[1]));
                    $quantidade_propostas = $quantidade_propostas + 1;
                    if (strpos(trim($prop->tipo), trim('Repasse')) !== false) {
                        $quantidade_voluntaria = $quantidade_voluntaria + 1;
                    } else {
                        if (strpos(trim($prop->tipo), trim('Parlamentar')) !== false) {
                            $quantidade_emendas_parlamentar = $quantidade_emendas_parlamentar + 1;
                        } else {
                            $quantidade_emendas = $quantidade_emendas + 1;
                        }
                    }
                }

                $array_dados_tabela = array(
                    'cidade' => $cidade->municipio,
                    'quantidade_total_propostas' => $quantidade_propostas,
                    'quantidade_voluntarias' => $quantidade_voluntaria,
                    'quantidade_emendas' => $quantidade_emendas,
                    'quantidade_emendas_parlamentar' => $quantidade_emendas_parlamentar,
                    'soma' => $soma
                );

                array_push($dados_cidades_tabela_array, $array_dados_tabela);
            }
        }

        // output headers so that the file is downloaded rather than displayed
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet; charset=ISO-8859-1');
        header('Content-Disposition: attachment; filename=relatorio_ganhos_cidades.xls');

        // create a file pointer connected to the output stream
        $output = fopen('php://output', 'w');

        // output the column headings
        fputcsv($output, array('cidade', 'quant. total', 'quant. volunt.', 'quant. emendas espec.', 'quant. emendas parl.', 'soma'));
        foreach ($dados_cidades_tabela_array as $user_to_csv) {
            // loop over the rows, outputting them
            $row = array(
                'cidade' => utf8_decode($user_to_csv['cidade']),
                'quant. total' => utf8_decode($user_to_csv['quantidade_total_propostas']),
                'quant. volunt.' => $user_to_csv['quantidade_voluntarias'],
                'quant. emendas espec.' => $user_to_csv['quantidade_emendas'],
                'quant. emendas parl.' => $user_to_csv['quantidade_emendas_parlamentar'],
                'soma' => $user_to_csv['soma']
            );

            fputcsv($output, $row);
        }
    }

    public function lista_percas_emendas_maiores_cidades_excel() {
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '-1');

        $this->load->model('relatorio_ganho_perca_model', 'modelrel');

        $ano = $this->input->get_post('ano', TRUE);
        if ($ano == NULL) {
            $ano = '2016';
        }

        $dados_cidades_tabela_array = array();

        $cidades = $this->modelrel->get_maiores_cidades();

        $destinadas_total = 0;
        $utilizadas_total = 0;
        $parlamentar_total = 0;
        $soma_total_dest = doubleval(0);
        $soma_total_parlamentar = doubleval(0);
        $soma_total_utilizada = doubleval(0);
        foreach ($cidades as $cidade) {
            $emendas_cidade = $this->modelrel->get_emendas_por_cidade($cidade->municipio, $ano);
            $propostas = $this->modelrel->get_propostas_por_cidade($cidade->municipio, $ano);

            if ($emendas_cidade != NULL) {
                $soma = doubleval(0);
                $soma_parlamanetar = doubleval(0);
                $quantidade_parlamentar = 0;
                $quantidade_emendas = 0;
                $soma_destinada = doubleval(0);
                $quantidade_destinadas = 0;
                $quantidade_parlamentar = 0;
                foreach ($emendas_cidade as $emenda) {
                    $soma_destinada = doubleval($soma_destinada) + doubleval(trim(explode("R$", str_replace(',', '.', str_replace('.', '', $emenda->valor)))[1]));
                    $soma_total_dest = doubleval($soma_total_dest) + doubleval(trim(explode("R$", str_replace(',', '.', str_replace('.', '', $emenda->valor)))[1]));
                    $quantidade_destinadas = $quantidade_destinadas + 1;
                    $destinadas_total = $destinadas_total + 1;
                    $nutilizada = true;
                    if ($propostas != NULL && count($propostas) > 0) {
                        foreach ($propostas as $prop) {
                            if ($prop->codigo_programa == $emenda->codigo_programa && $prop->ano == $ano && strpos(trim($prop->tipo), trim('Repasse')) == false) {
                                $nutilizada = false;
                                break;
                            }
                        }
                    }

                    if ($nutilizada) {
                        if ($emenda->data_inicio_parlam != NULL) {
                            $quantidade_parlamentar = $quantidade_parlamentar + 1;
                            $soma_parlamanetar = doubleval($soma_parlamanetar) + doubleval(trim(explode("R$", str_replace(',', '.', str_replace('.', '', $emenda->valor)))[1]));
                            $parlamentar_total = $parlamentar_total + 1;
                            $soma_total_parlamentar = doubleval($soma_total_parlamentar) + + doubleval(trim(explode("R$", str_replace(',', '.', str_replace('.', '', $emenda->valor)))[1]));
                        } else {
                            $quantidade_emendas = $quantidade_emendas + 1;
                            $soma = doubleval($soma) + doubleval(trim(explode("R$", str_replace(',', '.', str_replace('.', '', $emenda->valor)))[1]));
                            $utilizadas_total = $utilizadas_total + 1;
                            $soma_total_utilizada = doubleval($soma_total_utilizada) + doubleval(trim(explode("R$", str_replace(',', '.', str_replace('.', '', $emenda->valor)))[1]));
                        }
                    }
                }

                $array_dados_tabela = array(
                    'cidade' => $cidade->municipio,
                    'quantidade_emendas' => $quantidade_emendas,
                    'soma' => $soma,
                    'quantidade_destinadas' => $quantidade_destinadas,
                    'soma_destinadas' => $soma_destinada,
                    'quantidade_parlamentar' => $quantidade_parlamentar,
                    'soma_parlamentar' => $soma_parlamanetar
                );

                array_push($dados_cidades_tabela_array, $array_dados_tabela);
            }
        }

        // output headers so that the file is downloaded rather than displayed
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet; charset=ISO-8859-1');
        header('Content-Disposition: attachment; filename=relatorio_percas_cidades.xls');

        // create a file pointer connected to the output stream
        $output = fopen('php://output', 'w');

        // output the column headings
        fputcsv($output, array('cidade', 'quant. emendas', 'soma', 'quant. destinadas', 'soma destinadas', 'quant. parlamentar', 'soma parlamentar'));
        foreach ($dados_cidades_tabela_array as $user_to_csv) {
            // loop over the rows, outputting them
            $row = array(
                'cidade' => utf8_decode($user_to_csv['cidade']),
                'quant. emendas' => utf8_decode($user_to_csv['quantidade_emendas']),
                'soma' => $user_to_csv['soma'],
                'quant. destinadas' => $user_to_csv['quantidade_destinadas'],
                'soma destinadas' => $user_to_csv['soma_destinadas'],
                'quant. parlamentar' => $user_to_csv['quantidade_parlamentar'],
                'soma parlamentar' => $user_to_csv['soma_parlamentar']
            );

            fputcsv($output, $row);
        }
    }

}
