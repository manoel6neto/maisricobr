<?php

include 'application/controllers/BaseController.php';

class licenca_uso extends BaseController{
	
	public function index(){
		if($this->input->post()){
			$this->load->model('usuariomodel');
			$this->usuariomodel->atualiza_aceite_termos();
			
			redirect('controle_usuarios/primeiro_acesso');
		}
		
		$data['title'] = 'SIHS - Licença de Uso';
		$data['main'] = "controle_usuarios/licenca_uso";
		 
		$this->load->view('in/template_login', $data);
	}
}