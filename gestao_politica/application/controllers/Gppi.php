<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Gppi extends CI_Controller {

    function __construct() {
        parent::__construct();
    }

    public function index() {
        ini_set("max_execution_time", 0);
        ini_set("memory_limit", "-1");

        $this->load->view('gppi/index');        
    }
    
    public function simulacao() {
        
        ini_set("max_execution_time", 0);
        ini_set("memory_limit", "-1");
        
        $this->load->view('gppi/simulacao');
    }

    public function teste() {
        ini_set("max_execution_time", 0);
        ini_set("memory_limit", "-1");

        $this->load->model('GPPI_Model');
        echo "------ Idosos ------";
        var_dump($this->GPPI_Model->get_beneficiarios_por_idoso());
        var_dump($this->GPPI_Model->count_familias_pessoas_return_object($this->GPPI_Model->get_beneficiarios_por_idoso()));
        echo "<br><br>";
        echo "------ Crianças ------";
        var_dump($this->GPPI_Model->get_beneficiarios_por_crianca());
        var_dump($this->GPPI_Model->count_familias_pessoas_return_object($this->GPPI_Model->get_beneficiarios_por_crianca()));
        echo "<br><br>";
        echo "------ Faixa Etária ------";
        var_dump($this->GPPI_Model->get_beneficiarios_por_faixa_etaria_pessoa(8, 50));
        var_dump($this->GPPI_Model->count_familias_pessoas_return_object($this->GPPI_Model->get_beneficiarios_por_faixa_etaria_pessoa(8, 50)));
        echo "<br><br>";
        echo "------ Idade Pessoa ------";
        var_dump($this->GPPI_Model->get_beneficiarios_por_idade_pessoa(27, '>='));
        var_dump($this->GPPI_Model->count_familias_pessoas_return_object($this->GPPI_Model->get_beneficiarios_por_idade_pessoa(27, '>=')));
        echo "<br><br>";
        echo "------ Raça ou Cor ------";
        var_dump($this->GPPI_Model->get_beneficiarios_por_raca(2));
        var_dump($this->GPPI_Model->count_familias_pessoas_return_object($this->GPPI_Model->get_beneficiarios_por_raca(2)));
        echo "<br><br>";
        echo "------ Sexo ------";
        var_dump($this->GPPI_Model->get_beneficiarios_por_sexo(1));
        var_dump($this->GPPI_Model->count_familias_pessoas_return_object($this->GPPI_Model->get_beneficiarios_por_sexo(1)));
        echo "<br><br>";
        echo "------ Renda Familia ------";
        var_dump($this->GPPI_Model->get_beneficiarios_por_renda_familia(2400, '>='));
        var_dump($this->GPPI_Model->count_familias_pessoas_return_object($this->GPPI_Model->get_beneficiarios_por_renda_familia(2400, '>=')));
        echo "<br><br>";
        echo "------ Renda Pessoa ------";
        var_dump($this->GPPI_Model->get_beneficiarios_por_renda_pessoa(1500, '>='));
        var_dump($this->GPPI_Model->count_familias_pessoas_return_object($this->GPPI_Model->get_beneficiarios_por_renda_pessoa(1500, '>=')));
        echo "<br><br>";
        echo "------ Cep ------";
        var_dump($this->GPPI_Model->get_beneficiarios_por_cep(1, 1, '80010130'));
        var_dump($this->GPPI_Model->count_familias_pessoas_return_object($this->GPPI_Model->get_beneficiarios_por_cep(1, 1, '80010130')));
        echo "<br><br>";
        echo "------ Bairro ------";
        var_dump($this->GPPI_Model->get_beneficiarios_por_bairro(1, 1, 'Bigorrilho'));
        var_dump($this->GPPI_Model->count_familias_pessoas_return_object($this->GPPI_Model->get_beneficiarios_por_bairro(1, 1, 'Bigorrilho')));
        echo "<br><br>";
    }

}
