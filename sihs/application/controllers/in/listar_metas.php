<?php

include 'application/controllers/BaseController.php';

class Listar_metas extends BaseController {
	
	public function __construct(){
		parent::__construct();
	}

	function index(){
		
		$data['title'] = 'SIHS - Metas';

			$data['main'] = 'in/listar_metas';
			$this->load->vars($data);
			$this->load->view('in/template');
	}
}
?>
