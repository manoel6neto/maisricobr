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

    public function teste() {
        ini_set("max_execution_time", 0);
        ini_set("memory_limit", "-1");

        $this->load->model('GPPI_Model');
        var_dump($this->GPPI_Model->get_beneficiarios_por_renda_pessoa(1000, '<'));
        var_dump($this->GPPI_Model->count_familias_pessoas_return_object($this->GPPI_Model->get_beneficiarios_por_renda_pessoa(1000, '<')));
    }

}
