<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class gerencia extends CI_Controller {

    function __construct() {
        parent::__construct();
        try {
            if ($this->session->userdata('id_usuario') == null || $this->session->userdata('id_usuario') == "") {
                sleep(2);
                redirect('in/login');
            }
        } catch (Exception $e)
        {
            redirect('in/login');
        }
    }

    public function index() {

        self::inicio();
    }

    function inicio() {

        $this->load->view('gerencia/principal');
    }
}
