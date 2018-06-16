<?php

include 'application/controllers/BaseController.php';

class Listar_obras extends BaseController {
	
	public function __construct(){
		parent::__construct();
	}

	function index(){
		
		$data['title'] = 'SIHS - Listar Obras';

			$data['main'] = 'in/listar_obras';
			$this->load->vars($data);
			$this->load->view('in/template');
	}
}
?>
