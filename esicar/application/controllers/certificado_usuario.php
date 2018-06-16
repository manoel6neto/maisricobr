<?php
include 'application/controllers/BaseController.php';

class certificado_usuario extends BaseController{
	
	public function index(){
		$this->session->set_userdata('pagAtual', 'certificado');
		
		$this->load->model('certificado_usuario_model');
		
		$data['lista_certificados'] = $this->certificado_usuario_model->get_all_certificados();
		
		$data['main'] = "certificado_usuario/lista_certificados";
		$data['title'] = "Lista de Certificados";
		
		$this->load->view('in/template', $data);
	}
	
	public function criar_certificado(){
		$this->session->set_userdata('pagAtual', 'certificado');
		
		$this->load->model('usuariomodel');
		$this->load->model('certificado_usuario_model');
		$this->load->model('proponente_siconv_model');
		
		$data['podeSalvar'] = "";
		$data['mensagemErro'] = "";
		
		if($this->input->post(null, TRUE)){
			$this->form_validation->set_rules('data_curso', 'Data do Curso', 'required');
			$this->form_validation->set_rules('cpf_usuario', 'CPFs dos Participantes', 'required');
			$this->form_validation->set_rules('estado', 'Estado', 'required');
			$this->form_validation->set_rules('municipio', 'Municipio', 'required');
			
			$this->form_validation->set_message('required', 'O campo %s é obrigatório');
			
			if($this->form_validation->run()){
				$cpfs = explode(";", $this->input->post('cpf_usuario', TRUE));
				
				$podeSalvar = true;
				foreach ($cpfs as $login){
					if(!empty($login) & !$this->certificado_usuario_model->checa_cpf($login)){
						$podeSalvar = false;
						$data['mensagemErro'] = "O CPF {$login} é inválido.";
						break;
					}
				}
				
				if(!$podeSalvar){
					$data['podeSalvar'] = $podeSalvar;
				}else{
					foreach ($cpfs as $login){
						if(!empty($login))
							$this->certificado_usuario_model->insere(array(
									'data_curso'=>implode("-", array_reverse(explode("/", $this->input->post('data_curso', TRUE)))), 
									'id_usuario'=>$this->usuariomodel->get_by_login($login)->id_usuario,
									'uf'=>$this->input->post('estado', TRUE),
									'municipio'=>$this->input->post('municipio', TRUE)
							));
					}
					
					$this->alert("Certificado criado com sucesso.");
					$this->encaminha(base_url('index.php/certificado_usuario'));
				}
			}
		}
		
		$data['proponente_siconv_model'] = $this->proponente_siconv_model;
		$data['main'] = "certificado_usuario/index";
		$data['title'] = "Gerar Certificado";
		
		$this->load->view('in/template', $data);
	}
	
	public function imprime_certificado(){
		$this->load->model('usuariomodel');
		$this->load->model('proposta_comercial_model');
		$this->load->model('certificado_usuario_model');
		
		$this->load->library ( 'mPDF' );
		
		$data['usuariomodel'] = $this->usuariomodel;
		$data['proposta_comercial_model'] = $this->proposta_comercial_model;
		$data['dadosCertificado'] = $this->certificado_usuario_model->get_by_usuario($this->session->userdata('id_usuario'));
		
		ob_start (); // inicia o buffer
		
		$mpdf = new mPDF('', 'A4-L');
		$mpdf->allow_charset_conversion = true;
		$mpdf->charset_in = 'UTF-8';
		
		$mpdf->SetHTMLHeader("<div></div>");
		$mpdf->SetHTMLFooter("<div></div>");
// 		echo $this->load->view('certificado_usuario/gera_certificado', $data, TRUE);
		$mpdf->WriteHTML ( $this->load->view('certificado_usuario/gera_certificado', $data, TRUE) );
		
		$mpdf->Output ();
		
		exit();
	}
	
	function encaminha($url) {
		echo "<script type='text/javascript'>window.location='" . $url . "';</script>";
		exit();
	}
	
	function alert($text) {
		echo "<script type='text/javascript'>alert('" . utf8_decode($text) . "');</script>";
	}
}