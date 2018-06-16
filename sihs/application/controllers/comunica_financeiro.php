<?php

class comunica_financeiro extends CI_Controller{
	
	public function ativa_desativa_usuario(){
		$this->load->model('usuariomodel');
		
		$idUsuario = $this->input->get('id', TRUE);
		$status = $this->input->get('status', TRUE);
		
		$num_rows = $this->usuariomodel->muda_status_usuario($idUsuario, $status);
	}
}