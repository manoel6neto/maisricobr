<?php

include 'application/controllers/BaseController.php';

class Incluir_justificativa extends BaseController {
	
	public function __construct(){
		parent::__construct();
	}

	function index(){
		
		$data['title'] = 'Info Convênios - incluir justificativa';

			$data['main'] = 'in/incluir_justificativa';
			$this->load->vars($data);
			$this->load->view('in/template');
	}
}
?>
