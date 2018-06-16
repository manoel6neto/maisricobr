<?php

class numeros extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->cookie_file_path = tempnam("/tmp", "CURLCOOKIE" . rand());
    }

    public function get_planilha_transparencia() {
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '-1');

        $count = 267;

        $array_final = array();

        for ($i = 1; $i <= $count; $i++) {
            $temp_array = array();

            $pagina = "http://portaldatransparencia.gov.br/convenios/ConveniosLista.asp?UF=pr&CodMunicipio=7667&CodOrgao=&TipoConsulta=0&Periodo=&Pagina={$i}";
            $documento = $this->obter_pagina($pagina);
            $documento = $this->removeSpaceSurplus($documento);

            if ($documento != NULL && $documento != "") {

                $tabela = $this->getTextBetweenTags($documento, '<tbody>', '<\/tbody>');
                $tabela = trim($tabela[1]);
                $rows = $this->getTextBetweenTags($tabela, '<tr >', '<\/tr>');
                $rows2 = $this->getTextBetweenTags($tabela, '<tr class="linhaPar" >', '<\/tr>');
                
                $rows = array_merge($rows, $rows2);
                
                foreach ($rows as $row) {
                    $temp_array = array();
                    $codigo = $this->getTextBetweenTags($row, '<td class="">', '<\/td>');
                    $codigo = $codigo[0];
                    $codigo = explode(' ', $codigo);
                    $codigo = $codigo[3];
                    array_push($temp_array, $codigo);

                    $objeto = $this->getTextBetweenTags($row, '<td class="" style="width: 30em;">', '<\/td>');
                    $objeto = trim($objeto[0]);
                    array_push($temp_array, $objeto);

                    $orgao = $this->getTextBetweenTags($row, '<td class="">', '<\/td>');
                    $orgao = trim($orgao[1]);
                    array_push($temp_array, $orgao);

                    $conv = $this->getTextBetweenTags($row, '<td class="">', '<\/td>');
                    $conv = trim($conv[2]);
                    array_push($temp_array, $conv);

                    $valorconv = $this->getTextBetweenTags($row, '<td class="colunaValor">', '<\/td>');
                    $valorconv = trim($valorconv[0]);
                    array_push($temp_array, $valorconv);

                    $datalib = $this->getTextBetweenTags($row, '<td class="colunaValor">', '<\/td>');
                    $datalib = trim($datalib[1]);
                    array_push($temp_array, $datalib);

                    $valorlib = $this->getTextBetweenTags($row, '<td class="colunaValor">', '<\/td>');
                    $valorlib = trim($valorlib[2]);
                    array_push($temp_array, $valorlib);

                    if (count($temp_array) > 0) {
                        array_push($array_final, $temp_array);
                    }
                }
            }
        }

        header('Content-Type: text/csv; charset=ISO-8859-1');
        header('Content-Disposition: attachment; filename=planilha_dados_abertos.csv');

        // create a file pointer connected to the output stream
        $output = fopen('php://output', 'w');

        // output the column headings
        fputcsv($output, array('codigo', 'objeto', 'orgao', 'convenente', 'valor_conveniado', 'data_ultima_lib', 'valor_ultima_lib'));
        foreach ($array_final as $linha) {
            // loop over the rows, outputting them
            $row = array(
                'codigo' => utf8_decode($linha[0]),
                'objeto' => utf8_decode($linha[1]),
                'orgao' => utf8_decode($linha[2]),
                'convenente' => utf8_decode($linha[3]),
                'valor_conveniado' => utf8_decode($linha[4]),
                'data_ultima_lib' => utf8_decode($linha[5]),
                'valor_ultima_lib' => utf8_decode($linha[6])
            );

            fputcsv($output, $row);
        }
    }

    public function index() {
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '-1');

        //Get total destinado 2015
        $resultado_query = $this->db->query("SELECT DISTINCT codigo_programa, cnpj, valor FROM siconv_beneficiario WHERE (YEAR(data_inicio_benef) in ('2015') or YEAR(data_inicio_parlam) in ('2015')) AND valor IS NOT NULL;");
        $soma_emendas = doubleval(0);
        if ($resultado_query->num_rows > 0) {
            foreach ($resultado_query->result() as $result_row) {
                $soma_emendas = doubleval($soma_emendas) + doubleval(trim(explode("R$", str_replace(',', '.', str_replace('.', '', $result_row->valor)))[1]));
            }
        }

        $resultado_query = $this->db->query("SELECT DISTINCT codigo_siconv, valor_global FROM banco_proposta WHERE situacao NOT IN ('Cancelado', 'Histórico', 'Proposta/Plano de Trabalho Rejeitados', 'Proposta/Plano de Trabalho Cancelados') AND ano = '2015' AND situacao IS NOT NULL;");
        $soma_propostas_cadastradas = doubleval(0);
        if ($resultado_query->num_rows > 0) {
            foreach ($resultado_query->result() as $result_row) {
                $soma_propostas_cadastradas = doubleval($soma_propostas_cadastradas) + doubleval(trim(explode("R$", str_replace(',', '.', str_replace('.', '', $result_row->valor_global)))[1]));
            }
        }

        $resultado_query = $this->db->query("SELECT DISTINCT codigo_siconv, valor_global FROM banco_proposta WHERE situacao NOT IN ('Cancelado', 'Histórico', 'Proposta/Plano de Trabalho Rejeitados', 'Proposta/Plano de Trabalho Cancelados') AND ano = '2015' AND situacao IS NOT NULL AND (convenio IS NOT NULL OR convenio != '');");
        $soma_propostas_conveniadas = doubleval(0);
        if ($resultado_query->num_rows > 0) {
            foreach ($resultado_query->result() as $result_row) {
                $soma_propostas_conveniadas = doubleval($soma_propostas_conveniadas) + doubleval(trim(explode("R$", str_replace(',', '.', str_replace('.', '', $result_row->valor_global)))[1]));
            }
        }

        $resultado_query = $this->db->query("SELECT DISTINCT codigo_siconv, valor_global FROM banco_proposta WHERE situacao NOT IN ('Cancelado', 'Histórico', 'Proposta/Plano de Trabalho Rejeitados', 'Proposta/Plano de Trabalho Cancelados') AND ano = '2015' AND situacao IS NOT NULL AND (empenhado IS NOT NULL OR empenhado != '');");
        $soma_propostas_empenhadas = doubleval(0);
        if ($resultado_query->num_rows > 0) {
            foreach ($resultado_query->result() as $result_row) {
                $soma_propostas_empenhadas = doubleval($soma_propostas_empenhadas) + doubleval(trim(explode("R$", str_replace(',', '.', str_replace('.', '', $result_row->valor_global)))[1]));
            }
        }

        $resultado_query = $this->db->query("SELECT DISTINCT codigo_siconv FROM banco_proposta WHERE situacao NOT IN ('Cancelado', 'Histórico', 'Proposta/Plano de Trabalho Rejeitados', 'Proposta/Plano de Trabalho Cancelados') AND ano = '2015' AND situacao IS NOT NULL;");
        $total_propostas = $resultado_query->num_rows;

        $resultado_query = $this->db->query("SELECT DISTINCT codigo_siconv, valor_global FROM banco_proposta WHERE situacao NOT IN ('Cancelado', 'Histórico', 'Proposta/Plano de Trabalho Rejeitados', 'Proposta/Plano de Trabalho Cancelados') AND ano = '2015' AND situacao IS NOT NULL AND (convenio IS NOT NULL OR convenio != '');");
        $total_propostas_conveniadas = $resultado_query->num_rows;

        $resultado_query = $this->db->query("SELECT DISTINCT codigo_siconv, valor_global FROM banco_proposta WHERE situacao NOT IN ('Cancelado', 'Histórico', 'Proposta/Plano de Trabalho Rejeitados', 'Proposta/Plano de Trabalho Cancelados') AND ano = '2015' AND situacao IS NOT NULL AND (empenhado IS NOT NULL OR empenhado != '');");
        $total_propostas_empenhadas = $resultado_query->num_rows;

        //Resultados
        echo "Resultados\n";
        echo "Total de propostas " . ": " . $total_propostas . "<br>";
        echo "Total de propostas conveniadas" . ": " . $total_propostas_conveniadas . "<br>";
        echo "Total de propostas empenhadas" . ": " . $total_propostas_empenhadas . "<br>";
        echo "Valor total disponibilizado via emendas " . "R$ " . number_format($soma_emendas, 2, ',', '.') . "<br>";
        echo "Valor total propostas cadastradas " . "R$ " . number_format($soma_propostas_cadastradas, 2, ',', '.') . "<br>";
        echo "Valor total propostas conveniadas " . "R$ " . number_format($soma_propostas_conveniadas, 2, ',', '.') . "<br>";
        echo "Valor total propostas empenhadas " . "R$ " . number_format($soma_propostas_empenhadas, 2, ',', '.') . "<br>";

        die();
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

    function getTextBetweenTags($string, $tag1, $tag2) {
        $pattern = "/$tag1([\w\W]*?)$tag2/";
        preg_match_all($pattern, $string, $matches);
        return $matches[1];
    }

}
