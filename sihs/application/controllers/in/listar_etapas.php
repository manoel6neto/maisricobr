<?php

include 'application/controllers/BaseController.php';

class Listar_etapas extends BaseController {
	
	public function __construct(){
		parent::__construct();
	}

	function index(){
		
		$data['title'] = 'SIHS - Etapas';

			$data['main'] = 'in/listar_etapas';
			$this->load->vars($data);
			$this->load->view('in/template');
	}
}
?>
