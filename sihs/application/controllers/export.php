<?php

class export extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function gerar_export() {
        try {
            // -- Variáveis para armazenar os dados dos dumps -- //
            $dados_propostas = "/var/www/sihs/dumps/dados_sihs.sql";
            $nome_arquivo = "dados_sihs.sql";

            // -- Rodando o mysqldump para as propostas -- //
            $return_var = NULL;
            $output = NULL;
            $command = "/usr/bin/mysqldump -f -u root -h localhost -pPhysis_2013 physis_esicar proposta "
                    . "despesa empenhos endereco etapa justificativa meta obras parecer_proposta siconv_programa > " . $dados_propostas;
            exec($command, $output, $return_var);
            if ($return_var == 0) {
                header('Content-Type: application/octet-stream');
                header("Content-Transfer-Encoding: Binary");
                header("Content-disposition: attachment; filename=\"" . $nome_arquivo . "\"");
                readfile($dados_propostas);
            }
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    function alert($text) {
        echo "<script type='text/javascript'>confirm('" . $text . "');</script>";
    }

    function encaminha($url) {
        echo "<script type='text/javascript'>window.location='" . $url . "';</script>";
    }

}
