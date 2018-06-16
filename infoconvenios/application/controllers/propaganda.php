<?php

class propaganda extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
    }
    
    public function index() {
        
        $desconto = $this->input->get('desconto', TRUE);
        
        $data['desconto'] = $desconto;
        $data['video'] = "http://convenios.physisbrasil.com.br/infoconvenios/layout/assets/images/sc.mp4";
        $data['title'] = "Info Convênios - Propaganda";
        $data['main'] = 'propaganda/index';
        $this->load->view('in/template_login', $data);
    }
    
}

