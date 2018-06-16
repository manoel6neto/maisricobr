<?php

include 'application/controllers/BaseController.php';

class Incluir_bens_da_proposta extends BaseController {
	
	public function __construct(){
		parent::__construct();
	}

	function index(){
		
		$data['title'] = 'Info Convênios - incluir Bens da Proposta';

			$data['main'] = 'in/incluir_bens_da_proposta';
			$this->load->vars($data);
			$this->load->view('in/template');
	}
}
?>
