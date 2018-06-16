<?php

include 'application/controllers/BaseController.php';

class Gerencia_proposta_usuario extends BaseController {
	
	public function __construct(){
		parent::__construct();
	}

	function index(){
		
		$data['title'] = 'Info Convênios - gerenciamento de propostas do usuário';

			$data['main'] = 'in/gerencia_proposta_usuario';
			$this->load->vars($data);
			$this->load->view('in/template');
	}
}
?>
