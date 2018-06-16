<?php

include 'application/controllers/BaseController.php';

class Listar_cronograma extends BaseController {
	
	public function __construct(){
		parent::__construct();
	}

	function index(){
		
		$data['title'] = 'Physis - Cronograma de desembolso';

			$data['main'] = 'in/listar_cronograma';
			$this->load->vars($data);
			$this->load->view('in/template');
	}
}
?>
