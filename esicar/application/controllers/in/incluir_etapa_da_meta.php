<?php

include 'application/controllers/BaseController.php';

class Incluir_etapa_da_meta extends BaseController {
	
	public function __construct(){
		parent::__construct();
	}

	function index(){
		
		$data['title'] = 'Physis - incluir etapa da meta';

			$data['main'] = 'in/incluir_etapa_da_meta';
			$this->load->vars($data);
			$this->load->view('in/template');
	}
}
?>
