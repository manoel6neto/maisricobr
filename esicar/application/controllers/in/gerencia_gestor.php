<?php

include 'application/controllers/BaseController.php';

class Gerencia_gestor extends BaseController {
	
	public function __construct(){
		parent::__construct();
	}

	function index(){
		
		$data['title'] = 'Physis - gerenciamento de gestores';

			$data['main'] = 'in/gerencia_gestor';
			$this->load->vars($data);
			$this->load->view('in/template');
	}
}
?>
