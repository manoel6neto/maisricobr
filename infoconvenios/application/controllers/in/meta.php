<?php

include 'application/controllers/BaseController.php';

class Meta extends BaseController {
	
	public function __construct(){
		parent::__construct();
	}

	function index(){
		
		$data['title'] = 'Info Convênios - Meta';

			$data['main'] = 'in/meta';
			$this->load->vars($data);
			$this->load->view('in/template');
	}
}
?>
