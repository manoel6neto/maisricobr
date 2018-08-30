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

}
