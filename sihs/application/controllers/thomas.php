<?php

class thomas extends CI_Controller {
    
    public function send_email() {
        $this->load->model('usuariomodel');
        
        $this->usuariomodel->envia_email_usuario_cadastrado("manoel.carvalho.neto@gmail.com", "SIMONE DE SOUZA BECKER", "00398142971", null, "123456");
        $this->alert("OK");
    }
    
    function alert($text) {
        echo "<script type='text/javascript'>alert('" . $text . "');</script>";
    }
    
}