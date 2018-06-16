<?php

include 'application/controllers/BaseController.php';

class Incluir_parcela_do_cronograma_de_desembolso extends BaseController {
	
	public function __construct(){
		parent::__construct();
	}

	function index(){
		
		$data['title'] = 'Info Convênios - incluir parcela do cronograma de desembolso';

			$data['main'] = 'in/incluir_parcela_do_cronograma_de_desembolso';
			$this->load->vars($data);
			$this->load->view('in/template_projeto');
	}
}
?>
