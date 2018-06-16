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

        $min = $this->input->get_post('min', TRUE);
        $max = $this->input->get_post('max', TRUE);

        $dados_cidades_tabela_array = array();

        if ($min != NULL && $max != NULL) {
            $cidades = $this->modelrel->get_maiores_cidades($min, $max);
        } else {
            $cidades = $this->modelrel->get_maiores_cidades();
        }

        $soma_total = doubleval(0);
        foreach ($cidades as $cidade) {
            $propostas_cidade = $this->modelrel->get_propostas_por_cidade($cidade->cidade, $ano);

            if ($propostas_cidade != NULL) {
                $soma = doubleval(0);
                $quantidade_propostas = 0;
                $quantidade_voluntaria = 0;
                $quantidade_emendas = 0;
                foreach ($propostas_cidade as $prop) {
                    $soma = doubleval($soma) + doubleval(trim(explode("R$", str_replace(',', '.', str_replace('.', '', $prop->valor_global)))[1]));
                    $soma_total = $soma_total + doubleval(trim(explode("R$", str_replace(',', '.', str_replace('.', '', $prop->valor_global)))[1]));
                    $quantidade_propostas = $quantidade_propostas + 1;
                    if (strpos(trim($prop->tipo), trim('Repasse')) !== false) {
                        $quantidade_voluntaria = $quantidade_voluntaria + 1;
                    } else {
                        $quantidade_emendas = $quantidade_emendas + 1;
                    }
                }

                $array_dados_tabela = array(
                    'cidade' => $cidade->cidade,
                    'quantidade_total_propostas' => $quantidade_propostas,
                    'quantidade_voluntarias' => $quantidade_voluntaria,
                    'quantidade_emendas' => $quantidade_emendas,
                    'soma' => $soma
                );

                array_push($dados_cidades_tabela_array, $array_dados_tabela);
            }
        }

        $data['soma_total'] = $soma_total;
        $data['dados_tabela'] = $dados_cidades_tabela_array;
        $data['ano'] = $ano;
        $data['anos'] = array('2008', '2009', '2010', '2011', '2012', '2013', '2014', '2015', '2016');
        $data['title'] = 'Physis - Relatório Estatístico por cidades';
        $data['main'] = "relatorios_estatisticos/relatorio_cidades";
        $this->load->view('relatorios_estatisticos/temp_relatorios_estatisticos', $data);
    }

    public function lista_percas_emendas_maiores_cidades() {
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '-1');

        $this->load->model('relatorio_ganho_perca_model', 'modelrel');

        $ano = $this->input->get_post('ano', TRUE);
        if ($ano == NULL) {
            $ano = '2016';
        }

        $min = $this->input->get_post('min', TRUE);
        $max = $this->input->get_post('max', TRUE);

        $dados_cidades_tabela_array = array();

        if ($min != NULL && $max != NULL) {
            $cidades = $this->modelrel->get_maiores_cidades($min, $max);
        } else {
            $cidades = $this->modelrel->get_maiores_cidades();
        }

        $destinadas_total = 0;
        $utilizadas_total = 0;
        $soma_total_dest = doubleval(0);
        $soma_total_utilizada = doubleval(0);
        foreach ($cidades as $cidade) {
            $emendas_cidade = $this->modelrel->get_emendas_por_cidade($cidade->cidade, $ano);
            $propostas = $this->modelrel->get_propostas_por_cidade($cidade->cidade, $ano);

            if ($emendas_cidade != NULL) {
                $soma = doubleval(0);
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
                        $quantidade_emendas = $quantidade_emendas + 1;
                        $utilizadas_total = $utilizadas_total + 1;
                        $soma = doubleval($soma) + doubleval(trim(explode("R$", str_replace(',', '.', str_replace('.', '', $emenda->valor)))[1]));
                        $soma_total_utilizada = doubleval($soma_total_utilizada) + doubleval(trim(explode("R$", str_replace(',', '.', str_replace('.', '', $emenda->valor)))[1]));
                    }
                }

                $array_dados_tabela = array(
                    'cidade' => $cidade->cidade,
                    'quantidade_emendas' => $quantidade_emendas,
                    'soma' => $soma,
                    'quantidade_destinadas' => $quantidade_destinadas,
                    'soma_destinadas' => $soma_destinada
                );

                array_push($dados_cidades_tabela_array, $array_dados_tabela);
            }
        }

        $data['quantidade_total_destinadas'] = $destinadas_total;
        $data['quantidade_total_utilizadas'] = $utilizadas_total;
        $data['soma_total_destinadas'] = $soma_total_dest;
        $data['soma_total_utilizadas'] = $soma_total_utilizada;
        $data['dados_tabela'] = $dados_cidades_tabela_array;
        $data['ano'] = $ano;
        $data['anos'] = array('2008', '2009', '2010', '2011', '2012', '2013', '2014', '2015', '2016');
        $data['title'] = 'Physis - Relatório Estatístico por cidades';
        $data['main'] = "relatorios_estatisticos/relatorio_cidades_percas";
        $this->load->view('relatorios_estatisticos/temp_relatorios_estatisticos', $data);
    }

}
